<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Tukd extends CI_Controller {

	function __contruct()
	{	
		parent::__construct();
  
	}
    
    function spp()
	{	
	 $this->index('0','trhspp','no_spp','kd_skpd','SPP','spp','');
	}
	
    function cari_spp()
	{
		
	$lccr =  $this->input->post('pencarian');
        $this->index('0','trhspp','no_spp','kd_skpd','SPP','spp',$lccr);
	}
    
	function index($offset=0,$lctabel,$field,$field1,$judul,$list,$lccari)
	{
		$data['page_title'] = " $judul";
		
		//$total_rows = $this->master_model->get_count($lctabel);
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
	    //$data['isi']=$this->aktif_menu();
        //$data['isi']=$this->aktif_menu(); 
        //$data['isi']= $this->session->userdata('lcisi');         
		//$data['list'] 		= $this->master_model->getAll($lctabel,$field,$limit, $offset);
         if(empty($lccari)){     
		$data['list'] 		= $this->master_model->getAll($lctabel,$field,$limit, $offset);
        }else {
            $data['list'] 		= $this->master_model->getCari($lctabel,$field,$field1,$limit, $offset,$lccari);
        }
		$data['num']		= $offset;
		$data['total_rows'] = $total_rows;
		
				$this->pagination->initialize($config);
		$a=$judul;
		$this->template->set('title', 'PENATAUSAHAAN ');
		$this->template->load('template', "tukd/".$list."/list", $data);
	}
    
   function tambah_spp()
	{
		$wy=$this->rka_model->combo_bank();
        $jk=$this->rka_model->combo_bln();         
        $cRet='';
        
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                   <tr>
                        <td>Kode Urusan</td>
                        <td>:</td>
                        <td>$wy</td>
                        </tr>
                  ";
         
         $cRet .="<tr>
                        <td>Kode SKPD</td>
                        <td>:</td>
                        <td>$jk</td>
                        </tr>
                  </table>";
        $data['prev']= $cRet;
        $data['page_title']= 'PILIH KEGIATAN';
        $this->template->set('title', 'DETAIL KEGIATAN');          
        $this->template->load('template','tukd/spp',$data) ; 
        //$this->load->view('anggaran/rka/tambah_rka',$data) ;
   }
   
   function tambah()
	{
		
		$config = array(
               array(
                     'field'   => 'no_spp',
                     'label'   => 'no spp',
                     'rules'   => 'trim|required'
                    ),
                
               array(
                     'field'   => 'kd_skpd',
                     'label'   => 'Kd skpd',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'keperluan',
                     'label'   => 'keperluan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'jns_spp',
                     'label'   => 'Jns spp',
                     'rules'   => 'trim|required'
               )
                  
            );
			
		$this->form_validation->set_message('required', '%s harus diisi !');
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "Master Data Kegiatan &raquo; Tambah";
            //$lc = "select kd_program,nm_program from m_prog order by kd_program";
//            $query = $this->db->query($lc);
//            $data["program"]=$query->result();
            //$data["jumrow"]=$this->db->get('m_prog')->num_rows();
		}
		else
		{		
						$no_spp = $this->input->post('no_spp');
						$kd_skpd = $this->input->post('skpd');
                        $tgl_spp = $this->input->post('dd');
                        $jns_kegiatan = $this->input->post('jns_beban');
                        $bulan = $this->input->post('kebutuhan_bulan');
                        $keperluan = $this->input->post('ketentuan');
						
						
			//$this->tukd_model->save('trhspp',$data);
            $query = $this->db->query(" insert into trhspp(no_spp,kd_skpd,tgl_spp,jns_kegiatan,bulan,keperluan) values('$no_spp','$kd_skpd','$tgl_spp','$jns_kegiatan','$bulan','$keperluan') ");
            echo"$query";						
			$this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
			
			redirect('tukd/spp');

		}
		
		$this->template->set('title', 'Master Data Kegiatan &raquo; Tambah Data');
		$this->template->load('template', 'tukd/spp/tambah', $data);
	}
    
    function simpan()
	{
			
						$no_spp = $this->input->post('no_spp');
						$kd_skpd = $this->input->post('skpd');
                        $tgl_spp = $this->input->post('dd');
                        $jns_spp = $this->input->post('jns_beban');
                        $bulan = $this->input->post('kebutuhan_bulan');
                        $keperluan = $this->input->post('ketentuan');
						
						
			//$this->tukd_model->save('trhspp',$data);
            $query = $this->db->query(" insert into trhspp(no_spp,kd_skpd,tgl_spp,jns_spp,bulan,keperluan) values('$no_spp','$kd_skpd','$tgl_spp','$jns_spp','$bulan','$keperluan') ");
            //echo"$query";						
			
			
			redirect('tukd/spp');

		
		
		$this->template->set('title', 'Master Data Kegiatan &raquo; Tambah Data');
		$this->template->load('template', 'tukd/spp/tambah', $data);
	}
   
  ///////// Transout ///
        
   function transout()
    {
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI');   
        $this->template->load('template','tukd/transout',$data) ; 
    }
    
    function simpan_transout(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = $this->input->post('beban');
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm     = $this->session->userdata('pcNama');    
        $update     = date('d-m-y H:i:s');      
        $msg        = array();
        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);
            if ($asg){
                $sql = "insert into trhtransout(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban')";
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
            
        } else if ($tabel == 'trdtransout') {
            
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$nomor'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai)"; 
    
                    $asg = $this->db->query($sql.$csql);
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }                                                                 
        }
         
        //$asg->free_result();
    }
    
    function hapus_transout(){
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout where no_bukti='$nomor'";
        $asg = $this->db->query($sql);
        if ($asg){
            $sql = "delete from trhtransout where no_bukti='$nomor'";
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
    
    function load_transout(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or upper(nm_skpd) like 
                    upper('%$kriteria%') or upper(ket) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as total from trhtransout where jns_spp in ('1','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT * from trhtransout where jns_spp in ('1','3') $where order by tgl_bukti,no_bukti,kd_skpd limit $offset,$rows";
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
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp']                                                                                              
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_dtransout(){        
        $nomor = $this->input->post('no');    
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_kegiatan=b.kd_kegiatan AND f.kd_rek5=b.kd_rek5) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_kegiatan = b.kd_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek5) AS anggaran FROM trhtransout a INNER JOIN
                trdtransout b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' ORDER BY b.kd_kegiatan,b.kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nilai'         => $resulte['nilai'],
                        'lalu'          => $resulte['lalu'],
                        'sp2d'          => $resulte['sp2d'],   
                        'anggaran'      => $resulte['anggaran']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function load_sp2d(){
       //$beban='',$giat=''
       $beban   = $this->input->post('jenis');
       $giat    = $this->input->post('giat');
       $kode    = $this->input->post('kd');
       $bukti   = $this->input->post('bukti');
       $where = '';
       if ($beban=='1'){
        $sisa = "c.nilai + (SELECT IF(SUM(v.nilai) IS NULL,0,SUM(v.nilai)) FROM trhspp z INNER JOIN trhspm s ON z.no_spp=s.no_spp 
                INNER JOIN trhsp2d v ON s.no_spm=v.no_spm WHERE z.jns_spp='2' AND z.kd_skpd=c.kd_skpd )
                -(SELECT IF(SUM(nilai) IS NULL,0,SUM(nilai)) FROM trdtransout WHERE no_sp2d = c.no_sp2d and no_bukti <> '$bukti') AS sisa";
       }else{
        $sisa = "c.nilai -(SELECT IF(SUM(nilai) IS NULL,0,SUM(nilai)) FROM trdtransout WHERE no_sp2d = c.no_sp2d and no_bukti <> '$bukti') AS sisa";   
       }
       if (($beban != '' && $giat == '') || ($beban == '1')){
            $where = " and a.jns_spp='$beban'"; 
       }
       if ($giat !='' && $beban != '1'){
            $where = " and a.jns_spp='$beban' and d.kd_kegiatan='$giat'";
       }
        $kriteria = $this->input->post('q');
            $sql = "SELECT DISTINCT c.no_sp2d,c.tgl_sp2d,c.nilai,
                    $sisa                   
                    FROM trhspp a INNER JOIN trhspm b ON a.no_spp=b.no_spp
                    INNER JOIN trhsp2d c ON b.no_spm=c.no_spm
                    INNER JOIN trdspp d ON a.no_spp=d.no_spp
                    WHERE c.kd_skpd = '$kode' AND c.status = 1 $where ORDER BY c.no_sp2d,c.tgl_sp2d";
       //and UPPER(no_sp2d) LIKE '%$kriteria%'  
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'no_sp2d' => $resulte['no_sp2d'],
                        'tgl_sp2d' => $resulte['tgl_sp2d'],
                        'nilai' => $resulte['nilai'],
                        'sisa' => $resulte['sisa']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
    
    function skpd() {        
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd']
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}
        
     function load_trskpd() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        
        $jns_beban='';
        $cgiat = '';
        if ($jenis !=''){
            $jns_beban = "and a.jns_kegiatan='$jenis'";
        }
        if ($giat !=''){                               
            $cgiat = " and a.kd_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        $sql = "SELECT a.kd_kegiatan,b.nm_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan
                WHERE LEFT(a.kd_kegiatan,4)= LEFT('$cskpd',4) $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_kegiatan) LIKE UPPER('%$lccr%'))";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program'],
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}

    function load_rek() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
            
        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
        
        if ($jenis=='1'){
            $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_kegiatan = a.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd  AND c.kd_rek5=a.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis') AS lalu,
                    0 AS sp2d,nilai AS anggaran  FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn ";
                    
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis') AS lalu,
                    a.nilai AS sp2d,(SELECT SUM(nilai) FROM trdrka WHERE kd_kegiatan = b.kd_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek5) AS anggaran 
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp INNER JOIN trhspm c ON b.no_spp=c.no_spp INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm
                    WHERE d.no_sp2d = '$sp2d' and b.kd_kegiatan='$giat' $notIn ";
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
    
    function out_lalu(){
         $giat = $this->input->post('giat');
         $sp2d = $this->input->post('sp2d');
         $rek  = $this->input->post('rek');
         $nomor  = $this->input->post('nomor');
         $tgl  = $this->input->post('tgl');
         $skpd  = $this->input->post('skpd');
         $jenis  = $this->input->post('jenis');
         $sql = "SELECT b.no_bukti,b.no_sp2d,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,b.nm_rek5,
                (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_kegiatan=b.kd_kegiatan AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> b.no_bukti AND d.tgl_bukti<=a.tgl_bukti AND d.jns_spp = a.jns_spp) AS lalu,
                (SELECT SUM(g.nilai) FROM trdspp g INNER JOIN trhspm h ON g.no_spp=h.no_spp INNER JOIN trhsp2d i ON h.no_spm=i.no_spm WHERE i.no_sp2d=b.no_sp2d) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_kegiatan = b.kd_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek5=b.kd_rek5) AS anggaran FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti
                WHERE a.kd_skpd = '$skpd' AND b.kd_kegiatan = '$giat' AND b.kd_rek5 = '$rek' AND a.tgl_bukti <> '$tgl' AND 
                b.no_sp2d = '$sp2d' AND a.no_bukti = '$nomor'";                           
         $query1 = $this->db->query($sql);        
         $row = $query1->row();
         $result[] = array('lalu' =>$row->rp);
         //$result = $row->rp;
         echo json_encode($result);
         //echo $result;
         $query1->free_result();
    }
    
 
}