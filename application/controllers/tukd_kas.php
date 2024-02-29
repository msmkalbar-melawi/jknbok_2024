<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tukd_kas extends CI_Controller {
	
	function __contruct()
	{	
		parent::__construct();
	}

function sts_kas()
    {
        $data['page_title']= 'INPUT S T S KAS';
        $this->template->set('title', 'INPUT S T S KAS');   
        $this->template->load('template','tukd/pendapatan/sts_kas',$data) ; 
    }

function load_sts_kas() {
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$kd_skpd = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(a.no_sts) like upper('%$kriteria%') or a.tgl_sts like '%$kriteria%')";            
        }

		$sql = "SELECT count(*) as tot from trhkasin_pkd a WHERE  kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
       $sql = "SELECT TOP $rows a.* ,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd a 
				where a.kd_skpd='$kd_skpd' and a.jns_trans in ('5','1') $where and no_sts not in (
				SELECT TOP $offset a.no_sts from trhkasin_pkd a WHERE  a.kd_skpd='$kd_skpd' and a.jns_trans in ('5','1') order by CAST(no_sts as int)) 
				order by CAST(no_sts as int)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
       
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total'],2,'.',','),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'jns_cp' => $resulte['jns_cp'],
                        'pot_khusus' => $resulte['pot_khusus'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'bank' => $resulte['bank']                                                                                           
                        );
                        $ii++;
        }
           
       $result["total"] = $total->tot;
        $result["rows"] = $row; 
        $query1->free_result();   
        echo json_encode($result);
    	   
	}


function load_tetap_sts() {
        $skpd = $this->uri->segment(3);
        $kd_rek5 = $this->uri->segment(4);
        $sql = "SELECT * from tr_terima where kd_skpd = '$skpd' and kd_rek6 = '$kd_rek5' and sts_tetap = '1' order by no_tetap";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'no_tetap' => $resulte['no_terima'],
                        'tgl_tetap' => $resulte['tgl_terima'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai']),
                        'kd_rek6' => $resulte['kd_rek6']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}


function ambil_rek_tetap() {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
               
        $sql = "SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek4, a.kd_sub_kegiatan FROM 
        trdrka_pend a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek4 c on left(a.kd_rek6,6)=c.kd_rek4 
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
						'nm_rek4' => $resulte['nm_rek4'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan']                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

function load_jns_str(){
        $lccr ='[{   
        "id":"5",  
        "text":"Belanja"  
    },{  
        "id":"1",  
        "text":"Rekening Kas"  
    }]';  
    echo $lccr;
    }

 function load_sp2d_sts($cskpd='') {
			$lccr = $this->input->post('q');
            $kode = $this->uri->segment(3);
            $lcskpd  = $this->session->userdata('kdskpd');
            //$lcskpd = $this->uri->segment(4);
			if($kode == 1){
			$sql = "SELECT no_sp2d,jns_spp,CASE jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '2' THEN 'GU' ELSE 'TU' END AS jns_cp from trhsp2d  WHERE kd_skpd = '$lcskpd' AND upper(no_sp2d) like upper('%$lccr%')" ;
			} else {
			$sql = "SELECT no_sp2d,jns_spp,CASE jns_spp WHEN '4' THEN 'LS GAJI' WHEN '6' THEN 'LS BARANG/JASA' WHEN '1' THEN 'UP' WHEN '2' THEN 'GU' ELSE 'TU' END AS jns_cp from trhsp2d  WHERE kd_skpd = '$lcskpd' AND jns_spp in('4','6') AND upper(no_sp2d) like upper('%$lccr%')" ;
			}
                
            //echo $sql;    
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'no_sp2d' => $resulte['no_sp2d'],
							'jns_spp' => $resulte['jns_spp'],
							'jns_cp' => $resulte['jns_cp']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
        	   
    	}

   function load_trskpd() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        $cgiat = '';
        $jns_beban = "and a.jns_kegiatan='5'";
       
        if ($giat !=''){                               
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        $sql = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,(select nm_program from ms_program where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                WHERE a.kd_skpd='$cskpd' AND a.status_keg='1' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program'],
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}

function config_tahun() {
        $result = array();
         $tahun  = $this->session->userdata('pcThang');
		 $result = $tahun;
         echo json_encode($result);
	}

function ambil_rek_t_sts() {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
               
        $sql = "SELECT DISTINCT a.kd_rek6, (SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6 = a.kd_rek6) AS nm_rek FROM tr_terima a
                WHERE kd_skpd = '$lckdskpd' and upper(a.kd_rek6) like upper('%$lccr%')";
        
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek' => $resulte['nm_rek']                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}


function ambil_rek_sts() {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
        $lcgiat = $this->uri->segment(4);
        $lcfilt = $this->uri->segment(6);
		$sp2d = str_replace('123456789','/',$this->uri->segment(5));
        $lc = '';
        if ($lcfilt!=''){
            $lcfilt = str_replace('A',"'",$lcfilt);
            $lcfilt = str_replace('B',",",$lcfilt);
            $lc = " and a.kd_rek6 not in ($lcfilt)";
        }
        
       
            // $sql = "SELECT z.*, nilai-isnull(transaksi,0)-isnull(cp,0) as sisa FROM (SELECT a.kd_rek6,a.nm_rek6,SUM(a.nilai) as nilai,
			// 		(SELECT sum(nilai) FROM trdtransout WHERE no_sp2d=c.no_sp2d and kd_sub_kegiatan=a.kd_sub_kegiatan and kd_rek6=a.kd_rek6) as transaksi,
			// 		(select sum(f.rupiah) from trhkasin_pkd e join trdkasin_pkd f on e.no_sts=f.no_sts and e.kd_skpd=f.kd_skpd
			// 		where f.kd_sub_kegiatan=a.kd_sub_kegiatan and e.no_sp2d='$sp2d' and f.kd_rek6=a.kd_rek6) [cp]
			// 		FROM trdspp a INNER JOIN trhspp b ON a.no_spp = b.no_spp  and a.kd_skpd=b.kd_skpd
			// 		INNER JOIN trhsp2d c ON c.no_spp = b.no_spp and c.kd_skpd=b.kd_skpd where c.no_sp2d ='$sp2d'
			// 		AND c.kd_skpd = '$lckdskpd' /*and a.kd_sub_kegiatan = '$lcgiat' */
			// 		AND upper(a.kd_rek6) like upper('%$lccr%') $lc GROUP BY kd_rek6, nm_rek6,no_sp2d,a.kd_sub_kegiatan)z
			// 		";

            $sql="SELECT z.*, nilai-isnull(transaksi,0)-isnull(cp,0) as sisa FROM (SELECT d.kd_rek6,d.nm_rek6,SUM(a.nilai) as nilai,0 as transaksi, (select sum(f.rupiah) from trhkasin_pkd e join trdkasin_pkd f on e.no_sts=f.no_sts and e.kd_skpd=f.kd_skpd where f.kd_sub_kegiatan=a.kd_sub_kegiatan and e.no_sp2d='$sp2d' and f.kd_rek6=a.kd_rek6) [cp] FROM trdspp a INNER JOIN trhspp b ON a.no_spp = b.no_spp and a.kd_skpd=b.kd_skpd INNER JOIN trhsp2d c ON c.no_spp = b.no_spp and c.kd_skpd=b.kd_skpd INNER JOIN trdtransout d ON d.kd_skpd=c.kd_skpd AND d.no_sp2d=c.no_sp2d where c.no_sp2d ='$sp2d' AND c.kd_skpd = '$lckdskpd' and d.kd_sub_kegiatan = '$lcgiat' AND upper(a.kd_rek6) like upper('%$lccr%') GROUP BY a.kd_rek6,a.nm_rek6,d.kd_rek6, d.nm_rek6,d.no_sp2d,a.kd_sub_kegiatan)z";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6'],                  
                        'nilai' => $resulte['nilai'] ,                 
                        'transaksi' => $resulte['transaksi'],                  
                        'sisa' => $resulte['sisa']                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}


	function ambil_rek1() {
        $lccr = $this->input->post('q');
        $lcfilt = $this->uri->segment(3);
        $lc = '';
        if ($lcfilt!=''){
            $lcfilt = str_replace('A',"'",$lcfilt);
            $lcfilt = str_replace('B',",",$lcfilt);
            $lc = " and a.kd_rek6 not in ($lcfilt)";
        }
            $sql = "SELECT a.kd_rek6,a.nm_rek6 FROM ms_rek6 a
            where left(kd_rek6,4)='1101' $lc";
            
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6']                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
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

    function simpan_sts_ar(){
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        $nomor_kas   = $this->input->post('lckas');
        $tgl_kas     = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
        $sumber      = $this->input->post('sts');  
        $sp2d        = $this->input->post('sp2d');  
        $jns_cp      = $this->input->post('jns_cp');  
        $potlain     = $this->input->post('potlain'); 
        $cjenis_bayar     = $this->input->post('cjenis_bayar');  
        $nmskpd      = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
		$last_update = date('d-m-y H:i:s');
      // $last_update = " ";
        $msg = array();
        if ($tabel == 'trhkasin_pkd') {
            
            $sql = "delete from trhkasin_pkd where kd_skpd='$skpd' and no_sts='$nomor'";
            $asg = $this->db->query($sql);
            $sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            $sql = "delete from trhju where kd_skpd='$skpd' and no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            
            if ($asg){
				if($jnsrek==5){
				 $sql = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,bank) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','$potlain','$sp2d','$jns_cp','$cjenis_bayar')";
				} else{
				 $sql = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,bank) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','0','$sp2d','$jns_cp','$cjenis_bayar')";
				}
               
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd where no_sts='$nomor' AND kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan) values $lcnilaidet";
                        $asg = $this->db->query($sql); 
                    }                
                }            
            } 
            echo '2';
        }
    }


    function update_sts_kas_ag(){
        
        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        $nohide       = $this->input->post('nohide');
        $nomor_kas       = $this->input->post('lckas');
        $tgl_kas       = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
        $sumber      = $this->input->post('sts');  
        $sp2d        = $this->input->post('sp2d');  
        $jns_cp        = $this->input->post('jns_cp');
        $potlain   = $this->input->post('potlain');  
        $no_terima   = $this->input->post('no_terima');  
        $cjenis_bayar   = $this->input->post('cjenis_bayar'); 
        $nmskpd      = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
        $last_update = date('d-m-y H:i:s');
      // $last_update = " ";
        $msg = array();
        if ($tabel == 'trhkasin_pkd') {
            
            $sql = "delete from trhkasin_pkd where kd_skpd='$skpd' and no_sts='$nohide'";
            $asg = $this->db->query($sql);
            if ($asg){
                if($jnsrek==5){
                 $sql = "insert into trhkasin_pkd(no_kas,no_sts,kd_skpd,tgl_sts,tgl_kas,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,bank) 
                        values('$nomor_kas','$nomor','$skpd','$tgl','$tgl_kas','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','$potlain','$sp2d','$jns_cp','$cjenis_bayar')";
                } else{
                 $sql = "insert into trhkasin_pkd(no_kas,no_sts,kd_skpd,tgl_sts,tgl_kas,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima,bank) 
                        values('$nomor_kas','$nomor','$skpd','$tgl','$tgl_kas','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','0','$sp2d','$jns_cp','$no_terima','$cjenis_bayar')";
                }
               
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg){
                    $sql = "delete from trdkasin_pkd where no_sts='$nohide' AND kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);    
                    if(!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{
                        $sql = "insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan) values $lcnilaidet";
                        $asg = $this->db->query($sql); 
                    }                
                }            
            } 
            echo '2';
        }
        
       
    }


    function load_sisa_tunai(){
		$kd_skpd  = $this->session->userdata('kdskpd');        
        $query1 = $this->db->query("SELECT 
				SUM(case when jns=1 then jumlah else 0 end ) AS terima,
				SUM(case when jns=2 then jumlah else 0 end) AS keluar
				FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE kd_skpd='$kd_skpd' UNION ALL
				select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
				c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='TUNAI' UNION ALL
				select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE kd_skpd='$kd_skpd' AND pay='TUNAI' UNION ALL
				select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN('4','2') and pot_khusus =0  AND a.kd_skpd='$kd_skpd' and bank='TNK'
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd				
				UNION ALL
				SELECT	a.tgl_bukti AS tgl,	a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
								FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
								LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
								LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c
								ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar <> 1
								AND a.kd_skpd='$kd_skpd' 
								AND a.no_bukti NOT IN(
								select no_bukti from trhtransout 
								where no_sp2d in 
								(SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								 and  no_kas not in
								(SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
								GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
						UNION ALL
				SELECT	tgl_bukti AS tgl,	no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
								from trhtransout 
								WHERE pay = 'TUNAI' AND panjar <> 1 and no_sp2d in 
								(SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND   no_kas not in
								(SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
							
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'
				
				UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' AND kd_skpd='$kd_skpd'UNION ALL
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' AND kd_skpd='$kd_skpd'UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'AND kd_skpd='$kd_skpd' UNION ALL
				SELECT '' AS tgl,'' AS bku,'' AS ket,sld_awal+sld_awalpajak AS jumlah,'1' AS jns,kd_skpd AS kode FROM ms_skpd WHERE kd_skpd='$kd_skpd'		
				) a 
				where  kode='$kd_skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'sisa' => number_format(($resulte['terima'] - $resulte['keluar']),2,'.',',')                      
                        );
                        $ii++;
        }
           
           //return $result;
		   echo json_encode($result);
            $query1->free_result();	
	}

    // sisa bank
    function load_sisa_bank(){
        $lctgl2 = $this->input->post('tgl');
        $lcskpd  = $this->session->userdata('kdskpd');  
         $asql = $this->db->query("SELECT terima-keluar as sisa FROM(select
      SUM(case when jns=1 then jumlah else 0 end) AS terima,
      SUM(case when jns=2 then jumlah else 0 end) AS keluar
      from (
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
      SELECT '2022-01-01' AS tgl, null AS bku,
	    'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
                union
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM TRHINLAIN WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
       SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
       a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
       from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
             left join
       (
        select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
       
        where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
       ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
              WHERE pay='BANK' and 
             (panjar not in ('1') or panjar is null) 
             union 
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
             join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
      UNION
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

      SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
            left join 
            (
                select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
                where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
             ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd 
            where a.pay='BANK' and a.kd_skpd='$lcskpd'                  
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all           
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            ) a
            where tgl<='$lctgl2' and kode='$lcskpd') a 
            ");  
            echo json_encode($asql->result()); 
    }

    function hapus_sts(){
        $nomor = $this->input->post('no');
	    $kd_skpd = $this->session->userdata('kdskpd');
		
        
        $sql = "select status from trhkasin_pkd where kd_skpd ='$kd_skpd' and no_sts = '$nomor'";
        $a = $this->db->query($sql)->row()->status;
        //echo ($a);
        // echo($a);
        // exit();
        if($a==1){
            echo '1';
            die();
        }
        
		$sql = "UPDATE a set a.kunci=0 
								from tr_terima a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_terima=b.no_terima and a.kd_sub_kegiatan=b.kd_sub_kegiatan
								where a.kd_skpd='$kd_skpd' and b.no_sts='$nomor'";
                        $asg = $this->db->query($sql);
		
		
        $sql = "DELETE from trhkasin_pkd where no_sts='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
		$sql = "DELETE from trdkasin_pkd where no_sts='$nomor'  AND kd_skpd='$kd_skpd'";
		$asg = $this->db->query($sql);
        echo '2';          
    }



	function load_trskpd1($cskpd='') {
            $lccr='';        
            $lccr = $this->uri->segment(3);
            if(strlen($lccr)==1){
                $lcpj = 1;
            }else{
                $lcpj = 2;
            }
            $lcskpd  = $this->session->userdata('kdskpd');
            //$lcskpd = $this->uri->segment(4);
            $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trskpd a 
                    WHERE left(a.jns_sub_kegiatan,$lcpj)='$lccr' and a.kd_skpd = '$lcskpd'" ;    
            //echo $sql;    
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                            'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
        	   
    	}

    	function load_dsts_sisa() {    
        $lcskpd  = $this->session->userdata('kdskpd');
        $kriteria = $this->input->post('no');
        //$kriteria = $this->uri->segment(3);
        $sql = "SELECT a.*, (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek 
        from trdkasin_pkd a where a.no_sts = '$kriteria'  AND a.kd_skpd = '$lcskpd' and left(a.kd_rek6,1)<>'4' order by a.no_sts";
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_rek6' => $resulte['kd_rek6'],
                        'nm_rek' => $resulte['nm_rek'],
                        'rupiah' =>  number_format($resulte['rupiah'],2,'.',','),
                        'no_terima' => $resulte['no_terima']
						);
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}

  function load_trskpd_sts_ag() {
            $sp2d='';        
			$sp2d = str_replace('123456789','/',$this->uri->segment(3));
            $lcskpd  = $this->session->userdata('kdskpd');
			$sql1="SELECT jns_spp, no_spp FROM trhsp2d WHERE no_sp2d='$sp2d'";
			$sql2=$this->db->query($sql1);
			foreach ($sql2->result() as $row1){
				$jns=$row1->jns_spp;                    
			}
			if($jns ==1|| $jns==2){
				 $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti = b.no_bukti  AND a.kd_skpd=b.kd_skpd
					where a.no_sp2d ='$sp2d' group by a.kd_sub_kegiatan,a.nm_sub_kegiatan";    
			} else{
            $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdspp a INNER JOIN trhsp2d b ON a.no_spp = b.no_spp  AND a.kd_skpd=b.kd_skpd
			where b.no_sp2d ='$sp2d' group by a.kd_sub_kegiatan,a.nm_sub_kegiatan ";   
			}
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                            'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
        	   
    	}  

function load_sts() {
		$kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and (upper(a.no_sts) like upper('%$kriteria%') or a.tgl_sts like '%$kriteria%' or a.kd_skpd like'%$kriteria%' or
            upper(a.keterangan) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='4' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();
        $spjbulan = $this->tukd_model->cek_status_spj_pend($kd_skpd);
		$sql = "
		SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
		(CASE WHEN month(a.tgl_sts)<='$spjbulan' THEN 1 ELSE 0 END) ketspj,a.user_name
		FROM trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='4' 
		$where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd where kd_skpd='$kd_skpd' and jns_trans='4' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
		";
		
		$query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
         if ($resulte['ketspj']=='1'){
				$s='&#10004';
			}else{
				$s='&#10008';			
			}   
            $row[] = array( 
						'id' => $ii,        
                        'no_sts' => $resulte['no_sts'],
                        'tgl_sts' => $resulte['tgl_sts'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total'],2,'.',','),
                        'kd_bank' => $resulte['kd_bank'],
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'jns_trans' => $resulte['jns_trans'],
                        'rek_bank' => $resulte['rek_bank'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'no_cek' => $resulte['no_cek'],
                        'status' => $resulte['status'],
						'sumber' => $resulte['sumber'],
						'no_terima' => $resulte['no_terima'],
                        'nm_skpd' => $resulte['nm_skpd'],                                                                                           
                        'spj' => $resulte['ketspj'],   
						'user_nm' => $resulte['user_name'], 		
                        'simbol' => $s                                                                                           
                        );
                        $ii++;
				}
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();	
    	   
	}


//////////////////////////////////////////////////////////////////
}

?>