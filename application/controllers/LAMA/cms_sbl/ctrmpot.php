<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ctrmpot extends CI_Controller {

	public $org_keu = "";
	public $skpd_keu = "";
	
	function __construct(){	
		parent::__construct();
	}
    function index() {
        $data['page_title']= 'P O T O N G A N';
        $this->template->set('title', 'PENERIMAAN POTONGAN');   
        $this->template->load('template','tukd/cms/trmpot',$data) ; 
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
       
        $sql = "SELECT count(*) as total from trhtrmpot_cmsbank where kd_skpd='$kd_skpd' $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
		$sql = "SELECT top $rows a.*,(select status_upload from trhtransout_cmsbank where no_voucher=a.no_voucher and kd_skpd='$kd_skpd') as status_uploadx
				FROM trhtrmpot_cmsbank a where a.kd_skpd='$kd_skpd' 
				AND a.no_bukti not in (SELECT top $offset no_bukti FROM trhtrmpot_cmsbank where kd_skpd='$kd_skpd' order by no_bukti) 
				$where 
				order by no_bukti,kd_skpd";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
			if ($resulte['status']=='1'){
				$s1='&#10004';
			}else{
				$s1='&#10008';			
			}
            if ($resulte['status_uploadx']=='1'){
				$s1='&#10004';
			}else{
				$s1='&#10008';			
			}
            $row[] = array(
                         'id' 			=> $ii,
                        'no_bukti' 		=> $resulte['no_bukti'],
                        'no_voucher' 	=> $resulte['no_voucher'],
                        'tgl_bukti' 	=> $resulte['tgl_bukti'],
                        'kd_skpd' 		=> $resulte['kd_skpd'],
                        'nm_skpd'		=> $resulte['nm_skpd'],        
                        'ket' 			=> $resulte['ket'],
                        'no_sp2d' 		=> $resulte['no_sp2d'],
                        'nilai' 		=> $resulte['nilai'],
                        'kd_giat' 		=> $resulte['kd_kegiatan'],
                        'nm_giat' 		=> $resulte['nm_kegiatan'],
                        'kd_rek' 		=> $resulte['kd_rek5'],
                        'nm_rek' 		=> $resulte['nm_rek5'],
                        'rekanan' 		=> $resulte['nmrekan'],
                        'dir' 			=> $resulte['pimpinan'],
                        'alamat' 		=> $resulte['alamat'],
                        'npwp' 			=> $resulte['npwp'],
                        'jns_beban' 	=> $resulte['jns_spp'],
                        'status' 		=> $resulte['status'],                                                                                           
                        'simbol_s'		=> $s1,                                                                                           
                        'status_u' 		=> $resulte['status_uploadx'],                                                                                           
                        'simbol_u'		=> $s1,                                                                                           
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
	
	
	function load_trans(){
	   $kode    = $this->session->userdata('kdskpd');
       /*
       $sql = "SELECT DISTINCT a.no_tgl,a.no_voucher,a.tgl_voucher,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,a.jns_spp,a.total 
            FROM trhtransout_cmsbank a
            JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd
            WHERE a.kd_skpd = '$kode' and a.no_voucher not in (select no_voucher from trhtrmpot_cmsbank a where a.kd_skpd = '$kode') 
            and a.status_upload not in ('1') and a.jns_spp in ('1','2','3')
            order by a.tgl_voucher,a.no_voucher";
        */
       $sql = "select * from(
                    SELECT DISTINCT a.no_tgl,a.no_voucher,a.tgl_voucher,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,a.jns_spp,a.total 
                    FROM trhtransout_cmsbank a
                    JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd
                    WHERE a.kd_skpd = '$kode' and a.no_voucher not in (select no_voucher from trhtrmpot_cmsbank a where a.kd_skpd = '$kode') 
                    and a.status_upload not in ('1') and a.jns_spp in ('1','2','3')
                ) as a union
                select '' no_tgl,no_panjar [no_voucher],tgl_panjar [tgl_voucher],'' no_sp2d,kd_kegiatan,'' [nm_kegiatan],'' [kd_rek5],'' [nm_rek5],'1' [jns_spp],
                nilai [total]
                from tr_panjar_cmsbank where kd_skpd='$kode' and status_upload not in ('1')  and no_panjar not in 
                (select no_voucher from trhtrmpot_cmsbank a where a.kd_skpd = '$kode')
                order by tgl_voucher,no_voucher";


        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,  
                        'no_tgl' => $resulte['no_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_rek5' => $resulte['kd_rek5'],
                        'nm_rek5' => $resulte['nm_rek5'],
                        'jns_spp' => $resulte['jns_spp'],
                        'total' => number_format($resulte['total'],2)                              
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    
	function load_sp2d() {
		$kd_skpd 	= $this->session->userdata('kdskpd');
        $lccr   	= $this->input->post('q') ;
        $sql 		= "SELECT b.no_sp2d,a.jns_spp FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.kd_skpd=b.kd_skpd 
		AND a.no_voucher = b.no_voucher
		WHERE upper(b.no_sp2d) like upper('%$lccr%') AND b.kd_skpd='$kd_skpd'
		GROUP BY b.no_sp2d,jns_spp order by no_sp2d";
       $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
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
		$sp2d = str_replace('123456789','/',$this->uri->segment(4));
        $no_panjar = $this->uri->segment(5);
        $skpd = $this->session->userdata('kdskpd');
        /*
        $query1 = $this->db->query("SELECT DISTINCT a.kd_kegiatan,a.nm_kegiatan FROM trdtransout_cmsbank a
			INNER JOIN trhtransout_cmsbank c ON a.no_voucher = c.no_voucher
			AND a.kd_skpd = c.kd_skpd
			WHERE a.no_sp2d = '$sp2d'");*/
        $query1 = $this->db->query("select DISTINCT * from(            
                                    SELECT DISTINCT a.kd_kegiatan,a.nm_kegiatan FROM trdtransout_cmsbank a
                                                INNER JOIN trhtransout_cmsbank c ON a.no_voucher = c.no_voucher
                                                AND a.kd_skpd = c.kd_skpd
                                                WHERE a.no_sp2d = '$sp2d'  
                                    union 
                                    select a.kd_kegiatan,b.nm_kegiatan from tr_panjar_cmsbank a join trdrka b on a.kd_kegiatan=b.kd_kegiatan where a.kd_skpd='$skpd'  and a.no_panjar='$no_panjar'
                                    ) as c  ");              
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' => $ii,        
                        'kd_giat' => $resulte['kd_kegiatan'],                     
                        'nm_giat' => $resulte['nm_kegiatan'],                     
                        );
                        $ii++;
        }
		echo json_encode($result);
        $query1->free_result();	
	}
	
	
	function load_rek_pot(){
		$sp2d = str_replace('123456789','/',$this->uri->segment(4));
		$kd_giat_pot = $this->uri->segment(5);
        $query1 = $this->db->query("SELECT kd_rek5, nm_rek5 FROM trdrka where kd_kegiatan = '$kd_giat_pot'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek' => $resulte['kd_rek5'],                     
                        'nm_rek' => $resulte['nm_rek5'],                     
                        );
                        $ii++;
        }
		echo json_encode($result);
        $query1->free_result();	
	}
	
	
	function simpan_potongan(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');  
		$nomorvou = $this->input->post('novoucher');		
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

        $update     = date('Y-m-d H:i:s');      
        $msg        = array();

		// Simpan Header //
         if ($tabel == 'trhtrmpot_cmsbank') {
			$sql = "delete from trhtrmpot_cmsbank where kd_skpd='$skpd' and no_bukti='$nomor'";
			$asg = $this->db->query($sql);	 
            if ($asg){
				$sql = "insert into trhtrmpot_cmsbank(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_kegiatan, nm_kegiatan, kd_rek5,nm_rek5,nmrekan, pimpinan,alamat,no_voucher,rekening_tujuan,nm_rekening_tujuan,status_upload) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$nomorvou','','','0')";
                $asg = $this->db->query($sql);
				$sql = "update trhtransout_cmsbank set status_trmpot = '1' where kd_skpd='$skpd' and no_voucher='$nomorvou'";
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
            
        }elseif($tabel == 'trdtrmpot_cmsbank') {		 
            // Simpan Detail //                       
                $sql = "delete from trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
						
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtrmpot_cmsbank(no_bukti,kd_rek5,nm_rek5,nilai,kd_skpd,kd_rek_trans,ebilling)"; 
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
	
	 function load_trm_pot(){
		$skpd = $this->session->userdata('kdskpd');
		$bukti = $this->input->post('bukti');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdtrmpot_cmsbank where no_bukti='$bukti' AND kd_skpd='$skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
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
    
		function trdtrmpot_list() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('nomor');
        $sql = "SELECT * FROM trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd ='$kd_skpd' order by kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' => $ii,   
                        'kd_rek_trans' => $resulte['kd_rek_trans'],  
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],  
                        'no_bill' => $resulte['ebilling'],
						'nilai' => number_format($resulte['nilai'],2,'.',',')
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	 //$query1->free_result();   
	}
    
	function simpan_potongan_edit(){
        $tabel    = $this->input->post('tabel');   
        $no_bku   = $this->input->post('no_bku');
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');  
		$nomorvou = $this->input->post('novoucher');		
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

        $update     = date('Y-m-d H:i:s');      
        $msg        = array();

		// Simpan Header //
         if ($tabel == 'trhtrmpot_cmsbank') {
			$sql = "delete from trhtrmpot_cmsbank where kd_skpd='$skpd' and no_bukti='$no_bku'";
			$asg = $this->db->query($sql);	 
            if ($asg){
				$sql = "insert into trhtrmpot_cmsbank(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_kegiatan, nm_kegiatan, kd_rek5,nm_rek5,nmrekan, pimpinan,alamat,no_voucher,rekening_tujuan,nm_rekening_tujuan,status_upload) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$nomorvou','','','0')";
                $asg = $this->db->query($sql);
				$sql = "update trhtransout_cmsbank set status_trmpot = '1' where kd_skpd='$skpd' and no_voucher='$nomorvou'";
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
            
        }elseif($tabel == 'trdtrmpot_cmsbank') {		 
            // Simpan Detail //                       
                $sql = "delete from trdtrmpot_cmsbank where no_bukti='$no_bku' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
						
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtrmpot_cmsbank(no_bukti,kd_rek5,nm_rek5,nilai,kd_skpd,kd_rek_trans,ebilling)"; 
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
	
	function hapus_trmpot(){
        $nomor 		= $this->input->post('no');
		$kd_skpd  	= $this->session->userdata('kdskpd');
        $msg 		= array();
		$sql 		= "delete from trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg 		= $this->db->query($sql);
        $sql 		= "delete from trhtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg 		= $this->db->query($sql);
        $msg 		= array('pesan'=>'1');
        echo json_encode($msg);
    }

    function load_dtransout_trdmpot(){        
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $sql = "select a.* from trdtrmpot_cmsbank a left join trhtrmpot_cmsbank b on b.no_bukti=a.no_bukti and a.kd_skpd=b.kd_skpd where b.no_voucher='$nomor' and b.kd_skpd='$skpd' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nilai'         => $resulte['nilai'],
                        'nilai_nformat' => number_format($resulte['nilai'],2,'.',',')//number_format($resulte['nilai'],2,'.',',')                                                                                                                                                           
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }    
	
}