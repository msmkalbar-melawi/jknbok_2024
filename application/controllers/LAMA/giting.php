<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Giting extends CI_Controller {

	function __contruct()
	{	
		parent::__construct();
	}
    
	function index($offset=0,$lctabel,$field,$field1,$judul,$list,$lccari)
	{
		$data['page_title'] = " $judul";
        if(empty($lccari)){
            $total_rows = $this->tukd_model->get_count($lctabel);
            $lc = "/.$lccari";
        }else{
            $total_rows = $this->tukd_model->get_count_teang($lctabel,$field,$field1,$lccari);
            $lc = "";
        }
		// pagination        
        if(empty($lccari)){
		$config['base_url']		= site_url("tukd/".$list);
        }else{
        $config['base_url']		= site_url("tukd/cari_".$list);    
        }
		$config['total_rows'] 	= $total_rows;
		$config['per_page'] 	= '10';
		$config['uri_segment'] 	= 3;
		$config['num_links'] 	= 5;
		$config['full_tag_open'] = '<ul class="page-navi">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="current">';
		$config['cur_tag_close'] = '</li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$limit            		= $config['per_page'];  
		$offset         		= $this->uri->segment(3);  
		$offset         		= ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
		  
		if(empty($offset))  
		{  
			$offset=0;  
		}


        if(empty($lccari)){     
		$data['list'] 		= $this->master_model->getAll($lctabel,$field,$limit, $offset);
        }else {
            $data['list'] 		= $this->master_model->getCari($lctabel,$field,$field1,$limit,$offset,$lccari);
        }
		$data['num']		= $offset;
		$data['total_rows'] = $total_rows;
		
				$this->pagination->initialize($config);
		$a=$judul;
		$this->template->set('title', 'PENATAUSAHAAN ');
		$this->template->load('template', "tukd/".$list."/list", $data);
	}
	
	 function load_panjar(){
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trhpanjar a where a.kd_skpd='$kd_skpd'  $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT * FROM trhpanjar 
        WHERE  kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'status' => $resulte['stat'],   
                        'jns_spp' => $resulte['jns_spp'],                                                                                         
						
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
	
	function load_sum_tot_panjar(){

		$nomor    = $this->input->post('nomor');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdpanjar where no_bukti='$nomor'");  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['rektotal'],2,'.',','),
                        'rektotal1' => $resulte['rektotal']                       
                        );
                        $ii++;
        }
           
           //return $result;
		   echo json_encode($result);
           $query1->free_result();	
	}
	
	function load_trdpanjar(){        
        $nomor = $this->input->post('no');
        $sql = "SELECT b.*,(SELECT SUM(nilai) FROM trdrka  WHERE kd_kegiatan = b.kd_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek5) AS anggaran FROM trhpanjar a INNER JOIN
                trdpanjar b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' ORDER BY b.kd_kegiatan,b.kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii, 
						'no_sp2d'		=> $resulte['no_sp2d'],
                        'no_bukti'      => $resulte['no_bukti'],
                       
						'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nilai'         => $resulte['nilai'],
                        'anggaran'      => $resulte['anggaran']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
	
	
	function load_rek_panjar() {    
		$skpd = $this->session->userdata('kdskpd'); 
        $giat   = $this->input->post('giat');  
        $nomor  = $this->input->post('no');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
		$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');

        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
		
		
		if ($stsubah==0){
			$field='nilai';		
			
		}else{
			$field='nilai_ubah';				
		}
        
        
		
        $sql = " SELECT kd_rek5, nm_rek5, $field AS anggaran from trdrka WHERE kd_subkegiatan = '$giat' AND kd_skpd = '$skpd'";
	    $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'anggaran' => $resulte['anggaran']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}
	
	
	
	function cek_simpan_panjar(){
	    $nomor    = $this->input->post('no');

		$hasil=$this->db->query(" select count(*) as jumlah FROM trhpanjar where no_bukti='$nomor' ");
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
	
	
	
	function simpan_panjar(){

        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $ket      = $this->input->post('ket');
        $total    = $this->input->post('total'); 
        $beban     = $this->input->post('beban');            		
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
           
        $update     = date('y-m-d H:i:s');      
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhpanjar') {
            $sql = "delete from trhpanjar where kd_skpd='$skpd' and no_bukti='$nomor'";
			$asg = $this->db->query($sql);
			

            if ($asg){
                
				$sql = "insert into trhpanjar(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,jns_spp,total,stat,nojawab,notrans,tgl_jawab) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$beban','$total','0','0','0','0')";
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
            
        }elseif($tabel == 'trdpanjar') {
            
            // Simpan Detail //                       
                $sql = "delete from trdpanjar where no_bukti='$nomor'";
                $asg = $this->db->query($sql);
				
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdpanjar(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_subkegiatan,nm_subkegiatan,kd_rek5,nm_rek5,nilai)"; 
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
	
	 function simpan_panjar_edit(){

        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku    = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $ket      = $this->input->post('ket');
        $beban     = $this->input->post('beban');            		
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
           
        $update     = date('y-m-d H:i:s');      
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhpanjar') {
            $sql = "delete from trhpanjar where kd_skpd='$skpd' and no_bukti='$no_bku'";
			$asg = $this->db->query($sql);
			

            if ($asg){
                
				$sql = "insert into trhpanjar(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,jns_spp,total,stat,nojawab,notrans,tgl_jawab) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$beban','$total','0','0','0','0')";
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
            
        }elseif($tabel == 'trdpanjar') {
            
            // Simpan Detail //                       
                $sql = "delete from trdpanjar where no_bukti='$no_bku'";
                $asg = $this->db->query($sql);
				
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdpanjar(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_subkegiatan,nm_subkegiatan,kd_rek5,nm_rek5,nilai)"; 
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
	
	
	
	function hapus_panjar(){
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdpanjar where no_bukti='$nomor'";
        $asg = $this->db->query($sql);

		if ($asg){
            $sql = "delete from trhpanjar where no_bukti='$nomor'";
            $asg = $this->db->query($sql);

			if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }
	
	
//Pertanggung jawaban Panjar


function load_jpanjar() {
	    $skpd     = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="and (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(ket) like upper('%$kriteria%'))";            
        }
        
        $sql = "SELECT nojawab,no_bukti,tgl_jawab, tgl_bukti,kd_skpd, nm_skpd, ket from trhpanjar where kd_skpd='$skpd'  AND stat = '1' $where order by no_bukti";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'nojawab' => $resulte['nojawab'],
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],        
                        'tgl_jawab' => $resulte['tgl_jawab'],        
                        'kd_skpd' => $resulte['kd_skpd'],
						'nm_skpd' => $resulte['nm_skpd'],
                        'ket' => $resulte['ket']    
                        );
                        $ii++;
        }
        echo json_encode($result);
	}
	
	
	
	function ambil_panjar() {
		$kd_skpd = $this->session->userdata('kdskpd');
        //$lccr = $this->input->post('q');
        $sql = "SELECT a.*, SUM(b.nilai) as nilai FROM trhpanjar a INNER JOIN
				trdpanjar b ON a.no_bukti=b.no_bukti WHERE a.stat='0' GROUP BY no_bukti
				 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],    
                        'nilai' => $resulte['nilai'],
                        'ket' => $resulte['ket']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();	   
	}
	
	
	function hapus_jawab_panjar(){
        //no:cnomor,skpd:cskpd
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql = "UPDATE trhpanjar SET stat='0',nojawab='0',tgl_jawab='0' WHERE no_bukti='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);
        
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }
	
	
// Transaksi Panjar


function load_transout_panjar(){
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd'  $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' AND jenis_trans='1' $where order by tgl_bukti,no_bukti,kd_skpd limit $offset,$rows";
        $query1 = $this->db->query($sql);  
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
	
	
	
	function load_trskpd() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        $no_panjar =$this->input->post('nopanjar');

        $jns_beban='';
        $cgiat = '';
        if ($jenis !=''){
            $jns_beban = "and jns_kegiatan='$jenis'";
        }
        if ($giat !=''){                               
            $cgiat = " and kd_subkegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        //$sql = "SELECT a.kd_subkegiatan,a.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan FROM trdpanjar a inner join trhpanjar b ON a.no_bukti=b.no_bukti";    
		
		$sql = "SELECT a.kd_subkegiatan,a.nm_subkegiatan,a.kd_kegiatan,a.nm_kegiatan,no_sp2d FROM trdpanjar a inner join trhpanjar b ON a.no_bukti=b.no_bukti	WHERE b.kd_skpd ='$cskpd' 
		 AND b.stat='1' AND b.no_bukti='$no_panjar'
		 $cgiat AND (UPPER(kd_subkegiatan) LIKE UPPER('%$lccr%') OR UPPER(nm_subkegiatan) LIKE UPPER('%$lccr%'))";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_subkegiatan'],  
                        'nm_kegiatan' => $resulte['nm_subkegiatan'],
						'kd_kegiatan1' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan1' => $resulte['nm_kegiatan'],
						'no_sp2d' => $resulte['no_sp2d']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}
	
function load_sp2d_panjar(){
       //$beban='',$giat=''
       $giat    = $this->input->post('giat');
       $kode    = $this->input->post('kd');
       $where = '';
       
      
        $kriteria = $this->input->post('q');
            $sql = "SELECT DISTINCT no_sp2d from trdpanjar where kd_subkegiatan = '$giat'";
       //and UPPER(no_sp2d) LIKE '%$kriteria%'  
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'no_sp2d' => $resulte['no_sp2d'],
                         );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }


	
function load_rek() {    
		$skpd = $this->session->userdata('kdskpd'); 
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
		$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
		
        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
		
		
		if ($stsubah==0){
			$field='nilai';		
			
		}else{
			$field='nilai_ubah';				
		}
        
        if ($jenis=='1'){
		
			 $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = a.kd_subkegiatan AND 
                    d.kd_skpd=a.kd_skpd  AND c.kd_rek5=a.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis') AS lalu,
                    0 AS sp2d,$field AS anggaran  FROM trdrka a WHERE a.kd_subkegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn";
					
            /*$sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = a.kd_subkegiatan AND 
                    d.kd_skpd=z.kd_skpd  AND c.kd_rek5=a.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis') AS lalu,
                    0 AS sp2d,nilai AS anggaran  FROM trdpanjar a INNER JOIN trhpanjar z ON a.no_bukti = z.no_bukti WHERE 
					a.kd_subkegiatan= '$giat' AND z.kd_skpd = '$kode' $notIn ";
             */       
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = b.kd_subkegiatan AND 
                    d.kd_skpd=a.kd_skpd AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' AND c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,(SELECT SUM($field) FROM trdrka WHERE kd_subkegiatan = b.kd_subkegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek5) AS anggaran 
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp INNER JOIN trhspm c ON b.no_spp=c.no_spp INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm
                    WHERE d.no_sp2d = '$sp2d' and b.kd_subkegiatan='$giat' $notIn ";
        }        
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}
	
	
	
	function load_rek_trans() {    
		$skpd = $this->session->userdata('kdskpd'); 
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
		$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
		
        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
		
		
		if ($stsubah==0){
			$field='nilai';		
			
		}else{
			$field='nilai_ubah';				
		}
        
        if ($jenis=='1'){
		
			/* $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = a.kd_subkegiatan AND 
                    d.kd_skpd=a.kd_skpd  AND c.kd_rek5=a.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis') AS lalu,
                    0 AS sp2d,$field AS anggaran  FROM trdrka a WHERE a.kd_subkegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn";
			*/		
            $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = a.kd_subkegiatan AND 
                    d.kd_skpd=z.kd_skpd  AND c.kd_rek5=a.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' AND d.jenis_trans = 'P') AS lalu,
                    0 AS sp2d,nilai AS anggaran  FROM trdpanjar a INNER JOIN trhpanjar z ON a.no_bukti = z.no_bukti WHERE 
					a.kd_subkegiatan= '$giat' AND z.kd_skpd = '$kode' $notIn ";
                    
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_subkegiatan = b.kd_subkegiatan AND 
                    d.kd_skpd=a.kd_skpd AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' AND c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,(SELECT SUM($field) FROM trdrka WHERE kd_subkegiatan = b.kd_subkegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek5) AS anggaran 
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp INNER JOIN trhspm c ON b.no_spp=c.no_spp INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm
                    WHERE d.no_sp2d = '$sp2d' and b.kd_subkegiatan='$giat' $notIn ";
        }        
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}
	
	
	function simpan_transout_panjar(){

        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas      = $this->input->post('tglkas');
        //$nokaspot    = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nopanjar     = $this->input->post('cnopanjar');
           
        $update     = date('y-m-d H:i:s');      
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
			$asg = $this->db->query($sql);
			if ($beban=='1' or $beban=='3'){
	           $sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$nomor'";
			   $asg = $this->db->query($sql);
               $sqlx = "delete from trhju where kd_skpd='$skpd' and no_voucher='$nomor'";
			   $asgx = $this->db->query($sqlx);
			}

            if ($asg){
                
				$sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,jenis_trans) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','1')";
                $asg = $this->db->query($sql);
				
				$sql = "UPDATE trhpanjar SET notrans = '$nomor' WHERE no_bukti='$nopanjar'";
                $asg = $this->db->query($sql);

				if ($beban=='1' or $beban=='3'){
					$sql3 = " insert into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel) 
							  values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$total','0')";
					$asg3 = $this->db->query($sql3);
                    
                    $sql3x = " insert into trhju(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel) 
							  values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$total','0')";
					$asg3x = $this->db->query($sql3x);
				}



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
            
        }elseif($tabel == 'trdtransout') {
            
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$nomor'";
                $asg = $this->db->query($sql);
				if ($beban=='1' or $beban =='3'){
					$sql = "delete from trdju_pkd where no_voucher='$nomor'";
					$asg = $this->db->query($sql);
                    $sqlx = "delete from trdju where no_voucher='$nomor'";
					$asgx = $this->db->query($sqlx);
				}
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_subkegiatan,nm_subkegiatan,kd_rek5,nm_rek5,nilai)"; 
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

				$hasil=$this->db->query(" select * from trdtransout where no_bukti='$nomor' ");  	
				foreach ($hasil->result_array() as $row){
					$no	   =$row['no_bukti'];	
					$sp2d  =$row['no_sp2d'];	
					$kdgiat=$row['kd_kegiatan'];	
					$nmgiat=$row['nm_kegiatan'];
					$kdsubgiat=$row['kd_subkegiatan'];	
					$nmsubgiat=$row['nm_subkegiatan'];
					$kdrek5=$row['kd_rek5'];	
					$nmrek5=$row['nm_rek5'];	
					$nilai =$row['nilai'];	
	  			    
                    $kdrek4=$this->tukd_model->get_nama($kdrek5,'kd_rek4','ms_rek5','kd_rek5');
					$kdrek9=$this->tukd_model->get_nama($kdrek5,'map_lo','ms_rek5','kd_rek5');
					$nmrek9=$this->tukd_model->get_nama($kdrek9,'nm_rek5','ms_rek5','kd_rek5');
                    $kdrek64=$this->tukd_model->get_nama($kdrek5,'kd_rek64','ms_rek5','kd_rek5');
					$nmrek64=$this->tukd_model->get_nama($kdrek64,'nm_rek64','ms_rek5','kd_rek64');
					$rek3=substr($kdrek5,0,3);
	  			    $rekutang=$this->tukd_model->get_nama($kdrek64,'piutang_utang','ms_rek5','kd_rek64');
					$nmrekutang=$this->tukd_model->get_nama($rekutang,'nm_rek5','ms_rek5','kd_rek5');
                    if($kdrek4=='52201' || $kdrek4=='52201' ){
                        $kdrek_p=$this->tukd_model->get_nama($kdrek5,'persed_kdp','ms_rek5','kd_rek5');
                        $nmrek_p=$this->tukd_model->get_nama($kdrek_p,'nm_rek5','ms_rek5','kd_rek5');    
                    }

					$this->db->query("UPDATE trdpanjar SET notrans = '$no', nilai_trans='$nilai' WHERE no_bukti = '$nopanjar' AND kd_subkegiatan='$kdsubgiat' AND kd_rek5='$kdrek5'");		

					
					
					
					//if ($beban=='1'){
						if ($status=='1'){
						  //permen 64
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$rekutang','$nmrekutang',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek64','$nmrek64',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                          
                            
                          //permen  21 
                           $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$rekutang','$nmrekutang',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek5','$nmrek5',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                          
					    }else{
					       //permen 64
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek9','$nmrek9',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek64','$nmrek64',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                          
                           //permen  21 
                           $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek9','$nmrek9',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek5','$nmrek5',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                         					   
					    }
					//}
				}   
        }
    }
	
	 function simpan_transout_panjar_edit(){

        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku    = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas      = $this->input->post('tglkas');
        //$nokaspot    = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nopanjar     = $this->input->post('cnopanjar');

        $update     = date('y-m-d H:i:s');      
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$no_bku'";
			$asg = $this->db->query($sql);
			if ($beban=='1' or $beban=='3'){
	           $sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$no_bku'";
			   $asg = $this->db->query($sql);
               $sqlx = "delete from trhju where kd_skpd='$skpd' and no_voucher='$no_bku'";
			   $asgx = $this->db->query($sqlx);
			}

            if ($asg){
                
				$sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,jenis_trans) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','1')";
                $asg = $this->db->query($sql);
				
				$sql = "UPDATE trhpanjar SET notrans = '$nomor' WHERE no_bukti='$nopanjar'";
                $asg = $this->db->query($sql);
				
				
				

				if ($beban=='1' or $beban=='3'){
					$sql3 = " insert into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel) 
							  values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$total','0')";
					$asg3 = $this->db->query($sql3);
                    
                    $sql3x = " insert into trhju(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel) 
							  values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$total','0')";
					$asg3x = $this->db->query($sql3x);
				}



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
            
        }elseif($tabel == 'trdtransout') {
            
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$no_bku'";
                $asg = $this->db->query($sql);
				if ($beban=='1' or $beban =='3'){
					$sql = "delete from trdju_pkd where no_voucher='$no_bku'";
					$asg = $this->db->query($sql);
                    $sqlx = "delete from trdju where no_voucher='$no_bku'";
					$asgx = $this->db->query($sqlx);
				}
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_subkegiatan,nm_subkegiatan,kd_rek5,nm_rek5,nilai)"; 
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

				$hasil=$this->db->query(" select * from trdtransout where no_bukti='$nomor' ");  	
				foreach ($hasil->result_array() as $row){
					$no	   =$row['no_bukti'];	
					$sp2d  =$row['no_sp2d'];	
					$kdgiat=$row['kd_kegiatan'];	
					$nmgiat=$row['nm_kegiatan'];
					$kdsubgiat=$row['kd_subkegiatan'];	
					$nmsubgiat=$row['nm_subkegiatan'];
					$kdrek5=$row['kd_rek5'];	
					$nmrek5=$row['nm_rek5'];	
					$nilai =$row['nilai'];	
	  			    
                    $kdrek4=$this->tukd_model->get_nama($kdrek5,'kd_rek4','ms_rek5','kd_rek5');
					$kdrek9=$this->tukd_model->get_nama($kdrek5,'map_lo','ms_rek5','kd_rek5');
					$nmrek9=$this->tukd_model->get_nama($kdrek9,'nm_rek5','ms_rek5','kd_rek5');
                    $kdrek64=$this->tukd_model->get_nama($kdrek5,'kd_rek64','ms_rek5','kd_rek5');
					$nmrek64=$this->tukd_model->get_nama($kdrek64,'nm_rek64','ms_rek5','kd_rek64');
					$rek3=substr($kdrek5,0,3);
	  			    $rekutang=$this->tukd_model->get_nama($kdrek64,'piutang_utang','ms_rek5','kd_rek64');
					$nmrekutang=$this->tukd_model->get_nama($rekutang,'nm_rek5','ms_rek5','kd_rek5');
                    if($kdrek4=='52201' || $kdrek4=='52201' ){
                        $kdrek_p=$this->tukd_model->get_nama($kdrek5,'persed_kdp','ms_rek5','kd_rek5');
                        $nmrek_p=$this->tukd_model->get_nama($kdrek_p,'nm_rek5','ms_rek5','kd_rek5');    
                    }

					$this->db->query("UPDATE trdpanjar SET notrans = '$no', nilai_trans='$nilai' WHERE no_bukti = '$nopanjar' AND kd_subkegiatan='$kdsubgiat' AND kd_rek5='$kdrek5'");		

					
					
					
					
					//if ($beban=='1'){
						if ($status=='1'){
						  //permen 64
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$rekutang','$nmrekutang',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek64','$nmrek64',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                          
                            
                          //permen  21 
                           $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$rekutang','$nmrekutang',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek5','$nmrek5',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                          
					    }else{
					       //permen 64
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek9','$nmrek9',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek64','$nmrek64',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju_pkd(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                          
                           //permen  21 
                           $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek9','$nmrek9',$nilai,'0','D','$beban','1','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','1110301','Kas di Bendahara Pengeluaran','0','$nilai','K','','2','1') ");		

						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','$kdsubgiat','$nmsubgiat','$kdgiat','$nmgiat','$kdrek5','$nmrek5',$nilai,'0','D','$beban','3','1') ");       			
						   $this->db->query("insert into trdju(no_voucher,kd_subkegiatan,nm_subkegiatan,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
											 values('$no','','','','','3120501','Estimasi Perubahan SAL','0','$nilai','K','','4','0') ");
                         					   
					    }
					//}
				}   
        }
    }
	
	function hapus_transout_panjar(){
        $nomor = $this->input->post('no');
        $nopanjar = $this->input->post('nopanjar');
        $msg = array();
        $sql = "delete from trdtransout where no_bukti='$nomor'";
        $asg = $this->db->query($sql);

		$sql = "delete from trdju_pkd where no_voucher='$nomor'";
        $asg = $this->db->query($sql);
        
        $sql = "delete from trdju where no_voucher='$nomor'";
        $asg = $this->db->query($sql);
		
		$sql = "UPDATE trdpanjar SET notrans='0', nilai_trans='0' WHERE no_bukti = '$nopanjar'";
        $asg = $this->db->query($sql);

		if ($asg){
            $sql = "delete from trhtransout where no_bukti='$nomor'";
            $asg = $this->db->query($sql);

			$sql = "UPDATE trhpanjar SET notrans = '0' where no_bukti='$nopanjar'";
            $asg = $this->db->query($sql);
			
            $sql = "delete from trhju_pkd where no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            
            $sql = "delete from trhju where no_voucher='$nomor'";
            $asg = $this->db->query($sql);

			
			if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }
	
	
	
	function load_no_panjar(){

        $query1 = $this->db->query("SELECT no_bukti, tgl_bukti,jns_spp FROM trhpanjar where stat='1'");  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'jns_spp' => $resulte['jns_spp']                       
                        );
                        $ii++;
        }
           
           //return $result;
		   echo json_encode($result);
           $query1->free_result();	
	}
	
	
	
	
	
	
	}