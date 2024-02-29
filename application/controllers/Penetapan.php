<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Penetapan extends CI_Controller {

	public $org_keu = "";
	public $skpd_keu = "";
	
	function __contruct()
	{	
		parent::__construct();
	}
     
    function rp_minus($nilai){
        if($nilai<0){
            $nilai = $nilai * (-1);
            $nilai = '('.number_format($nilai,"2",",",".").')';    
        }else{
            $nilai = number_format($nilai,"2",",","."); 
        }
        
        return $nilai;
    }

    function config_tahun() {
        $result = array();
         $tahun  = $this->session->userdata('pcThang');
         $result = $tahun;
         echo json_encode($result);
    }


    function index()
    {
        $data['page_title']= 'INPUT PENETAPAN PENDAPATAN';
        $this->template->set('title', 'INPUT PENETAPAN PENDAPATAN');   
        $this->template->load('template','tukd/pendapatan/penetapan',$data) ; 
    }

    function config_skpd(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
        ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' and 
        tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd)";
        $query1 = $this->db->query($sql);  

        $test = $query1->num_rows();

    $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'jns_ang' => $resulte['jns_ang']
                        );
                        $ii++;
        }
               
        
        echo json_encode($result);
        $query1->free_result();   
    } 

    function skpd() {
        //$lccr = $this->input->post('q');
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd,a.nm_skpd FROM  ms_skpd a LEFT JOIN trhrka b ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();       
    }


    function load_tetap() {
       $skpd     = $this->session->userdata('kdskpd');
       $result = array();
       $row = array();
       $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
       $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
       $offset = ($page-1)*$rows;        
       $kriteria = $this->input->post('cari');
       $where ='';
       if ($kriteria <> ''){                               
        $where=" AND (a.no_tetap LIKE '%$kriteria%' OR a.tgl_tetap LIKE '%$kriteria%' OR a.keterangan LIKE '%$kriteria%') ";            
         }
       
      $sql = "SELECT count(*) as total from tr_tetap a WHERE a.kd_skpd = '$skpd' $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
      $sql = "
                SELECT top $rows a.*, (SELECT b.nm_rek6 FROM ms_rek6 b WHERE a.kd_rek6=b.kd_rek6) as nm_rek6, b.sumber FROM tr_tetap a 
                left join tr_terima b on a.no_tetap=b.no_tetap and a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$skpd'
                $where AND a.no_tetap NOT IN (SELECT TOP $offset a.no_tetap FROM tr_tetap a WHERE a.kd_skpd='$skpd' $where 
                ORDER BY a.tgl_tetap,a.no_tetap ) ORDER BY tgl_tetap,no_tetap";

        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(  
                        'id' => $ii,        
                        'no_tetap'          => $resulte['no_tetap'],
                        'tgl_tetap'         => $resulte['tgl_tetap'],
                        'kd_skpd'           => $resulte['kd_skpd'],
                        'keterangan'        => $resulte['keterangan'],    
                        'nilai'             => number_format($resulte['nilai']),
                        'kd_rek6'           => $resulte['kd_rek6'],
                        'jenis'             => $resulte['jenis'],
                        'nm_rek6'           => $resulte['nm_rek6'],
                        'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'kd_rek'            => $resulte['kd_rek_lo'],
                        'sumber'            => $resulte['sumber'],
                        'user_name'         => $resulte['user_name']        
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
        
    }

    function cek_simpan(){
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');        
        if ($field2==''){
        $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else{
        $hasil=$this->db->query(" select count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor' ");      
        }
        foreach ($hasil->result_array() as $row){
        $jumlah=$row['jumlah']; 
        }
        if($jumlah>0){
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        } else{
        $msg = array('pesan'=>'0');
        echo json_encode($msg);
        }
        
    }

    function simpan_tetap_ag() {
            $tabel          = $this->input->post('tabel');
            $lckolom        = $this->input->post('kolom');
            $lcnilai        = $this->input->post('nilai');
            $cid            = $this->input->post('cid');
            $lcid           = $this->input->post('lcid');
            $usernm         = $this->session->userdata('pcNama');
            $sql            = "insert into $tabel $lckolom values $lcnilai";

            // $datainsert = array('user_name' => $usernm );
            // // $this->db->insert('tr_tetap',$datainsert);

            // $this->db->set('user_name',$usernm);
            // $this->db->where('no_tetap', $cid);
            // $this->db->update('tr_tetap');
            

            $asg            = $this->db->query($sql);
            if ( $asg > 0 ){
                echo '2';
            } else {
                echo '0';
                exit();
            }
        
    }


     function update_tetap_ag() {
            $tabel          = $this->input->post('tabel');
            $lckolom        = $this->input->post('kolom');
            $lcnilai        = $this->input->post('nilai');
            $cid            = $this->input->post('cid');
            $lcid           = $this->input->post('lcid');
            $nohide         = $this->input->post('no_hide');
            $skpd           = $this->session->userdata('kdskpd');
            $usernm         = $this->session->userdata('pcNama');
            
            
            $sql            = "delete from tr_tetap where kd_skpd='$skpd' and no_tetap='$nohide'";
            $asg            = $this->db->query($sql);
            if ($asg){
                $sql        = "insert into $tabel $lckolom values $lcnilai";
                $asg        = $this->db->query($sql);
                if ( $asg > 0 ){                    
                    echo '2';
                } else {
                    echo '0';
                    exit();
                }
            }
    }


    function ambil_rek_tetap() {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
        /*print_r(substr($lckdskpd,0, 17));
        exit();*/
        // if (substr($lckdskpd,0, 22) =='5.02.0.00.0.00.01'){                               
        //     $trdrka="trdrka";            
        // }else{
        //     $trdrka="trdrka";
        // }

        $sql = "SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM 
        trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 
        where a.kd_skpd = '$lckdskpd' and left(a.kd_rek6,1)='4' and 
        (upper(a.kd_rek6) like upper('%$lccr%') or b.nm_rek6 like '%$lccr%') order by kd_rek6";
        
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],
                        'kd_rek' => $resulte['kd_rek'],  
                        'nm_rek' => $resulte['nm_rek'],
                        'nm_rek5' => $resulte['nm_rek5'],
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    } 

	function hapus_tetap(){
        //no:cnomor,skpd:cskpd
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql = "delete from tr_tetap where no_tetap='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);

        // (delete jurnal sudah di pasang di triggers)
        // $sql1 = "delete from trhju_pkd where no_voucher='$nomor' and kd_skpd = '$skpd'";
        // $asg1 = $this->db->query($sql1);
        // $sql2 = "delete from trdju_pkd where no_voucher='$nomor' and kd_unit = '$skpd'";
        // $asg2 = $this->db->query($sql2);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }


//PENETAPAN LANGSUNG
function penetapan_langsung(){
        $data['page_title']= 'INPUT PENETAPAN LANGSUNG';
        $this->template->set('title', 'INPUT PENETAPAN LANGSUNG');   
        $this->template->load('template','tukd/pendapatan/penetapan_langsung',$data) ; 
    }


function load_pengirim() {
        $skpd = $this->session->userdata('kdskpd');               
        $lccr = $this->input->post('q');
        if(substr($skpd,0,17)=='5-02.0-00.0-00.02'){
            $where = "kd_skpd='$skpd'";
        }else{
            $where = "LEFT(kd_skpd,15)=LEFT('$skpd',15)";
        }
        
        $sql = "select * from ms_pengirim WHERE $where 
                AND (UPPER(kd_pengirim) LIKE UPPER('%$lccr%') OR UPPER(nm_pengirim) LIKE UPPER('%$lccr%')) 
                order by cast(kd_pengirim as int)";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_pengirim' => $resulte['kd_pengirim'],  
                        'nm_pengirim' => $resulte['nm_pengirim'],
                        'kd_skpd'     => $resulte['kd_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }

    function simpan_tetap_ag_semua() {
            $nomor      = $this->input->post('nomor');
            $tgl        = $this->input->post('tgl');
            $skpd       = $this->input->post('skpd');
            $giat       = $this->input->post('giat');
            $kd_rek6    = $this->input->post('kd_rek6');
            $sumber    = $this->input->post('sumber');
            $kd_rek_lo  = $this->input->post('kd_rek_lo');
            $nilai      = $this->input->post('nilai');
            $ket        = $this->input->post('ket');
            $status     = $this->input->post('cstatus');
            
            if($status=='1'){
                $no_tetap='';
                $tgl_tetap='';
                $sts_tetap='0';
            }else{
                $no_tetap=$nomor;
                $tgl_tetap=$tgl;
                $sts_tetap='1';
                $sql        = "insert into tr_tetap(no_tetap,tgl_tetap,kd_skpd,kd_rek6,kd_sub_kegiatan,kd_rek_lo,nilai,keterangan) 
                            values ('$nomor','$tgl','$skpd','$kd_rek6','$giat','$kd_rek_lo','$nilai','$ket')";
                $asg         = $this->db->query($sql);
                }
                    $sql            = "insert into tr_terima(no_terima,tgl_terima,no_tetap,tgl_tetap,sts_tetap,kd_skpd,kd_sub_kegiatan,kd_rek6,kd_rek_lo,nilai,keterangan,jenis,sumber) 
                                            values ('$nomor/TRM','$tgl','$no_tetap','$tgl_tetap','$sts_tetap','$skpd','$giat','$kd_rek6','$kd_rek_lo','$nilai','$ket','1','$sumber')";
                    $asg         = $this->db->query($sql);
                        if ( $asg > 0 ) {
                                echo '1';
                            } else {
                                echo '0';
                                exit();
                            }
    }


    function update_tetap_ag_semua() {
            $nomor      = $this->input->post('nomor');
            $nohide     = $this->input->post('nohide');
            $tgl        = $this->input->post('tgl');
            $skpd       = $this->input->post('skpd');
            $giat       = $this->input->post('giat');
            $kd_rek6    = $this->input->post('kd_rek6');
            $kd_rek_lo  = $this->input->post('kd_rek_lo');
            $nilai      = $this->input->post('nilai');
            $ket        = $this->input->post('ket');
            $status     = $this->input->post('cstatus');

        $cek=$this->db->query(" SELECT count(*) as jumlah FROM tr_terima where no_terima='$nomor/TRM' and kd_skpd = '$skpd' and kunci='1'");
        foreach ($cek->result_array() as $row){
            $jmlrow=$row['jumlah']; 
        }

        if ($jmlrow>='1'){
            echo '0';
        }else{

            if($status=='1'){
                $no_tetap='';
                $tgl_tetap='';
                $sts_tetap='0';
            }else{
                $no_tetap=$nomor;
                $tgl_tetap=$tgl;
                $sts_tetap='1';
                $sql1        = "DELETE tr_tetap WHERE no_tetap='$nohide' AND kd_skpd='$skpd'";
                $asg1         = $this->db->query($sql1);
                $sql2        = "insert into tr_tetap(no_tetap,tgl_tetap,kd_skpd,kd_rek6,kd_sub_kegiatan,kd_rek_lo,nilai,keterangan) 
                            values ('$nomor','$tgl','$skpd','$kd_rek6','$giat','$kd_rek_lo','$nilai','$ket')";
                $asg2         = $this->db->query($sql2);
                }   

                    $sql5    = "DELETE trhkasin_pkd WHERE no_sts='$nohide/STS' AND kd_skpd='$skpd'";
                    $asg5    = $this->db->query($sql5);
                    $sql6    = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,jns_trans,rek_bank,no_kas,tgl_kas,no_cek,status,sumber,jns_cp,pot_khusus,no_sp2d,no_terima) 
                                values ('$nomor/STS','$skpd','$tgl','$ket','$nilai','','$giat','4','$kd_rek_lo','$nomor/STS','$tgl','','','0','','0','','$nomor/TRM')";
                    $asg6    = $this->db->query($sql6);


                    $sql7    = "DELETE trdkasin_pkd WHERE no_sts='$nohide/STS' AND kd_skpd='$skpd'";
                                $asg7    = $this->db->query($sql7);
                                $sql8    = "insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan) 
                                            values ('$skpd','$nomor/STS','$kd_rek6','$nilai','$giat')";
                                $asg8    = $this->db->query($sql8);


                    $sql3        = "DELETE tr_terima WHERE no_terima='$nohide/TRM' AND kd_skpd='$skpd'";
                    $asg3         = $this->db->query($sql3);
                    $sql4            = "insert into tr_terima(no_terima,tgl_terima,no_tetap,tgl_tetap,sts_tetap,kd_skpd,kd_sub_kegiatan,kd_rek6,kd_rek_lo,nilai,keterangan,jenis) 
                                            values ('$nomor/TRM','$tgl','$no_tetap','$tgl_tetap','$sts_tetap','$skpd','$giat','$kd_rek6','$kd_rek_lo','$nilai','$ket','1')";
                    

                    $asg4         = $this->db->query($sql4);
                            if ($asg4>0){
                                
                                
                                echo '1';
                            } else {
                                echo '0';
                                exit();
                            }
            
        }

            
            
    } 


    /////////////////////
	
}

?>