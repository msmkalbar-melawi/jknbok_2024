<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Tukd_cms 
 *  
 * @package 
 * @author Boomer 
 * @copyright 2016
 * @version $Id$ 
 * @access public
 */ 
class Tunai_trmpot extends CI_Controller {

public $ppkd = "4.02.01";
public $ppkd1 = "4.02.01.00";


public $org_keu = "";
public $skpd_keu = "";

    function __construct() 
    {    
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }        
    } 

    function trmpot_pndhbank()
    {
        $data['page_title']= 'P O T O N G A N';
        $this->template->set('title', 'PENERIMAAN POTONGAN');   
        $this->template->load('template','tukd/tunai/trmpot',$data) ;  
    } 
    function load_pot_in(){
    
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtrmpot where kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        
        $sql = "SELECT top $rows * from trhtrmpot where kd_skpd='$kd_skpd' AND no_bukti not in (SELECT top $offset no_bukti FROM trhtrmpot where kd_skpd='$kd_skpd' order by no_bukti) $where order by no_bukti,kd_skpd";

        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                         'id' => $ii,
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],        
                        'ket' => $resulte['ket'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'nilai' => $resulte['nilai'],
                        'kd_giat' => $resulte['kd_sub_kegiatan'],
                        'nm_giat' => $resulte['nm_sub_kegiatan'],
                        'kd_rek' => $resulte['kd_rek6'],
                        'nm_rek' => $resulte['nm_rek6'],
                        'rekanan' => $resulte['nmrekan'],
                        'dir' => $resulte['pimpinan'],
                        'alamat' => $resulte['alamat'],
                        'npwp' => $resulte['npwp'],
                        'jns_beban' => $resulte['jns_spp'],
                        'status' => $resulte['status'], 
                        'no_kas' => $resulte['no_kas'],
                        'pay' => $resulte['pay']                                                                                            
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }

 function load_sp2d_trimpot() {
        
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr   = $this->input->post('q') ;
        $sql = "
        SELECT b.no_sp2d,a.jns_spp FROM trhtransout a INNER JOIN trdtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti = b.no_bukti
        WHERE upper(b.no_sp2d) like upper('%$lccr%') AND b.kd_skpd='$kd_skpd'
        GROUP BY b.no_sp2d,jns_spp order by no_sp2d";
        
       $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
          
            $row[] = array(
                        'id' => $ii,
                        'no_sp2d' => $resulte['no_sp2d'],
                        'jns_spp' => $resulte['jns_spp']
                        );
                        $ii++;
        }
           
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }


 function load_kegiatan_pot(){
        $sp2d = str_replace('123456789','/',$this->uri->segment(3));
        $query1 = $this->db->query("SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdtransout a
            INNER JOIN trhtransout c ON a.no_bukti = c.no_bukti
            AND a.kd_skpd = c.kd_skpd
            WHERE a.no_sp2d = '$sp2d'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_giat' => $resulte['kd_sub_kegiatan'],                     
                        'nm_giat' => $resulte['nm_sub_kegiatan'],                     
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
            $query1->free_result(); 
    }
    
function load_no_trans_pot() {
        
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr   = $this->input->post('q') ;
        $sql = "SELECT * from(
                select a.no_bukti,tgl_bukti,a.no_sp2d,a.ket,a.kd_skpd,sum(b.nilai) [nilai]
                from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where a.kd_skpd='$kd_skpd'
                and a.jns_spp in ('1','2','3','4','5','6') and (a.no_panjar<>'1' or a.no_panjar is null) and (upper(a.no_sp2d) like upper('%$lccr%') or upper(a.no_bukti) like upper('%$lccr%'))
                group by a.no_bukti,tgl_bukti,a.no_sp2d,a.ket,a.kd_skpd 
                union all 
                select no_panjar [no_bukti],tgl_panjar [tgl_bukti],'' [No SP2D],keterangan [ket],kd_skpd,nilai from tr_panjar 
                where kd_skpd='$kd_skpd' and upper(no_panjar) like upper('%$lccr%')
                ) as c order by tgl_bukti,cast(no_bukti as int)";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
          
            $row[] = array(
                        'id' => $ii,
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'ket' => $resulte['ket'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nilai' => number_format($resulte['nilai'],"2",",","."),
                        );
                        $ii++;
        }
           
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    }

function load_rek_pot(){
        $sp2d = str_replace('123456789','/',$this->uri->segment(3));
        $kd_giat_pot = $this->uri->segment(4);
        $query1 = $this->db->query("SELECT a.kd_rek6, a.nm_rek6 FROM trdtransout a
            INNER JOIN trhtransout b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd WHERE
             a.no_sp2d = '$sp2d' AND a.kd_sub_kegiatan = '$kd_giat_pot'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek' => $resulte['kd_rek6'],                     
                        'nm_rek' => $resulte['nm_rek6'],                     
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
            $query1->free_result(); 
    }
function perusahaan() {                 
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');        
        $sql = "SELECT TOP 5 nmrekan, pimpinan, npwp, alamat FROM trhspp WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
                    AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
                    GROUP BY nmrekan, pimpinan, npwp, alamat
                UNION ALL
                SELECT TOP 5 nmrekan, pimpinan, npwp, alamat FROM trhtrmpot WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
                    AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
                    GROUP BY nmrekan, pimpinan, npwp, alamat
                UNION ALL
                SELECT nmrekan, pimpinan, npwp, alamat FROM trhtrmpot_cmsbank WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
                    AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
                    GROUP BY nmrekan, pimpinan, npwp, alamat";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'nmrekan' => $resulte['nmrekan'],  
                        'pimpinan' => $resulte['pimpinan'],      
                        'npwp' => $resulte['npwp'],
                        'alamat' => $resulte['alamat'],
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

function simpan_potongan_edit2(){
        $tabel    = $this->input->post('tabel'); 
        $no_bku   = $this->input->post('no_bku');
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $ket      = $this->input->post('ket');
        $total    = $this->input->post('total'); 
        $beban    = $this->input->post('beban');
        $npwp    = $this->input->post('npwp');      
        $csql     = $this->input->post('sql');            
        $no_sp2d     = $this->input->post('no_sp2d');            
        $kd_giat     = $this->input->post('kd_giat');            
        $nm_giat     = $this->input->post('nm_giat');            
        $kd_rek     = $this->input->post('kd_rek');            
        $nm_rek     = $this->input->post('nm_rek');            
        $rekanan     = $this->input->post('rekanan');            
        $dir     = $this->input->post('dir');            
        $alamat     = $this->input->post('alamat');            
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $csqljur     = $this->input->post('sqljur');            
        $cnotrans     = $this->input->post('notrans');
        $cpay     = $this->input->post('pay');
       // $update     = date('y-m-d H:i:s');      
        $msg        = array();
                $sql = "update trhtrmpot set no_kas='$cnotrans',pay='$cpay' where no_bukti='$nomor' and kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
   
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

    function hapus_trmpot(){
        $nomor = $this->input->post('no');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $msg = array();
        $sql = "delete from trdtrmpot where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        $sql = "delete from trhtrmpot where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }

    function load_trm_pot(){
        $skpd = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        //$id=str_replace('123456789','/',$spp);
        $query1 = $this->db->query("SELECT sum(nilai) as rektotal from trdtrmpot where no_bukti='$bukti' AND kd_skpd='$skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['rektotal'],"2",",","."),
                        'rektotal1' => $resulte['rektotal']                       
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
           $query1->free_result();  
    }
    

    function simpan_potongan(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $ket      = $this->input->post('ket');
        $total    = $this->input->post('total'); 
        $beban    = $this->input->post('beban');
        $npwp    = $this->input->post('npwp');      
        $csql     = $this->input->post('sql');            
        $no_sp2d     = $this->input->post('no_sp2d');            
        $kd_giat     = $this->input->post('kd_giat');            
        $nm_giat     = $this->input->post('nm_giat');            
        $kd_rek     = $this->input->post('kd_rek');            
        $nm_rek     = $this->input->post('nm_rek');            
        $rekanan     = $this->input->post('rekanan');            
        $dir     = $this->input->post('dir');            
        $alamat     = $this->input->post('alamat');            
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $csqljur     = $this->input->post('sqljur');
        $cnotrans     = $this->input->post('notrans');            
        $cpay     = $this->input->post('pay');   
       // $update     = date('y-m-d H:i:s');      
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtrmpot') {
            $sql = "delete from trhtrmpot where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);
            

            if ($asg){
                
                $sql = "INSERT into trhtrmpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6,nm_rek6,nmrekan, pimpinan,alamat,no_kas,pay) 
                        values('$nomor','$tgl','$ket','$usernm','','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$cnotrans','$cpay')";
                $asg = $this->db->query($sql);
                
                
            
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }elseif($tabel == 'trdtrmpot') {
        $total2    = $this->input->post('total'); 
            
            // Simpan Detail //                       
                $sql = "delete from trdtrmpot where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                        
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtrmpot(no_bukti,kd_rek6,nm_rek6,rekanan,npwp,nilai,kd_skpd,kd_rek_trans,ebilling)"; 
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }

    function rek_pot() {
        $lccr   = $this->input->post('q') ;
        $sql    = " SELECT kd_rek6,nm_rek6 FROM ms_pot where ( upper(kd_rek6) like upper('%$lccr%')
                    OR upper(nm_rek6) like upper('%$lccr%') )  ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();       
    }

    function trdtrmpot_list() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('nomor');
        
        $sql = "SELECT * FROM trdtrmpot where no_bukti='$nomor' AND kd_skpd ='$kd_skpd' order by kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,   
                        'kd_rek_trans' => $resulte['kd_rek_trans'],  
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6'],  
                        //'pot' => $resulte['pot'],
                        //'nilai' => $resulte['nilai']
                        'no_bill' => $resulte['ebilling'],
                        'nilai' => number_format($resulte['nilai'],2,'.',',')
                        );
                        $ii++;
        }
           
        echo json_encode($result);
         //$query1->free_result();   
    }
















 }