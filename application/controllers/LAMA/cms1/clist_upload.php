<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class clist_upload extends CI_Controller {
	public $org_keu = "";
	public $skpd_keu = "";
	
	function __contruct(){	
		parent::__construct();
	}
	
    function index(){
        $data['page_title']= 'DAFTAR TRANSAKSI NON TUNAI';
        $this->template->set('title', 'DAFTAR TRANSAKSI NON TUNAI');   
        $this->template->load('template','tukd/cms/list_upload',$data) ; 
    }

    function load_listbelum_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_voucher='$kriteria'";            
        }
        $skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where $init_skpd and status_upload='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join (
        select a.no_voucher,a.kd_skpd,sum(a.nilai) bersih from trdtransout_transfercms a where $init_skpd
        group by no_voucher,kd_skpd)c on c.no_voucher=a.no_voucher and c.kd_skpd=a.kd_skpd
        where $init_skpd and status_upload='0' $and         
        group by 
a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih        
        order by cast(a.no_voucher as int),a.kd_skpd");		
        
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'no_tgl' => $resulte['no_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'bersih' => number_format($resulte['bersih'],2),
                        'pot' => number_format($resulte['total']-$resulte['bersih'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan']
                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}

    function load_list_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_voucher='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
        
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where $init_skpd $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join (
        select a.no_voucher,a.kd_skpd,sum(a.nilai) bersih from trdtransout_transfercms a where $init_skpd
        group by no_voucher,kd_skpd)c on c.no_voucher=a.no_voucher and c.kd_skpd=a.kd_skpd
        where $init_skpd $and    
        group by 
        a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.bersih     
        order by cast(a.no_voucher as int),a.kd_skpd");		
        
        /*$query1 = $this->db->query("SELECT top $rows a.*,b.* FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where left(a.kd_skpd,7)=left('$skpd',7) $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd");	*/	
        
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'no_tgl' => $resulte['no_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'bersih' => number_format($resulte['bersih'],2),
                        'pot' => number_format($resulte['total']-$resulte['bersih'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan']
                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}
    
   function load_hdraf_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        		
        $skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
		
        $sql = "SELECT count(*) as total from trhupload_cmsbank a
        where $init_skpd $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.* FROM trhupload_cmsbank a               
        where $init_skpd $and         
        order by cast(a.no_upload as int),a.kd_skpd");		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        {                         
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_upload' => $resulte['no_upload'],
                        'no_upload_tgl' => $resulte['no_upload_tgl'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2)                                 
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	} 
     
    function load_list_telahupload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_upload='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
        
        $sql = "SELECT c.no_upload,count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher
        where $init_skpd and a.status_upload='1' and a.status_validasi='0' $and group by c.no_upload" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.no_upload,c.no_upload_tgl FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher
        where $init_skpd and a.status_upload='1' and a.status_validasi='0' $and 
group by 
a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_kegiatan,b.nm_kegiatan,c.no_upload,c.no_upload_tgl       
        order by cast(c.no_upload as int),cast(a.no_voucher as int),a.kd_skpd");		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'no_tgl' => $resulte['no_tgl'],
                        'no_upload' => $resulte['no_upload'],
                        'no_upload_tgl' => $resulte['no_upload_tgl'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_sp2d' => $resulte['no_sp2d'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'nm_kegiatan' => $resulte['nm_kegiatan']
                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}
 
    function load_total_upload($tgl=''){
	   $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
						SUM (b.nilai) AS total_upload
					FROM
						trhtransout_cmsbank a
					JOIN trdtransout_cmsbank b ON a.no_voucher = b.no_voucher and a.kd_skpd = b.kd_skpd
					WHERE
						left(a.kd_skpd,7) = '$kode'
					AND a.status_upload = '1' AND a.tgl_upload='$tgl'";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'xtotal_upload' => number_format($resulte['total_upload'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
 
    function no_urut_uploadcms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    
    $init_skpd = "KD_SKPD = '$kd_skpd'";
    
	$query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
	select no_upload nomor, 'Urut Upload Pengeluaran cms' ket, kd_skpd from trhupload_cmsbank where $init_skpd 
    union all
    select no_upload nomor, 'Urut Upload Panjar Bank cms' ket, kd_skpd from trhupload_cmsbank_panjar where $init_skpd     
    union all
    select no_upload nomor, 'Urut Upload Penerimaan cms' ket, kd_skpd from trhupload_sts_cmsbank where $init_skpd
    ) 
    z WHERE $init_skpd");
	    $ii = 0;
        $nomor = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            $nomor = $resulte['nomor'];
                        
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $nomor
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }
    
    function no_urut_uploadcmsharian(){
    $kd_skpd = $this->session->userdata('kdskpd');  
    
    $init_skpd = "a.kd_skpd = '$kd_skpd'";
    $init_skpd2 = "kd_skpd = '$kd_skpd'";
    
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d");
    
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
		select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Pengeluaran cms' ket, a.kd_skpd from trdupload_cmsbank a
		left join trhupload_cmsbank b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where $init_skpd
		union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Panjar Bank cms' ket, a.kd_skpd from trdupload_cmsbank_panjar a
		left join trhupload_cmsbank_panjar b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where $init_skpd
		union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Penerimaan cms' ket, a.kd_skpd from trdupload_sts_cmsbank a
		left join trhupload_sts_cmsbank b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where $init_skpd
    ) 
    z WHERE $init_skpd2 AND tanggal='$tanggal'");
    
    
	    $ii = 0;
        $nomor = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if(strlen($resulte['nomor'])==1){
                $nomor = "00".$resulte['nomor'];    
            }else if(strlen($resulte['nomor'])==2){
                $nomor = "0".$resulte['nomor'];    
            }else if(strlen($resulte['nomor'])==3){
                $nomor = $resulte['nomor'];    
            }
                        
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $nomor
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }
 
    function simpan_uploadcms(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $skpd     = $this->input->post('skpd');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');      
        $urut_tgl = $this->input->post('urut_tglupload');
        
        date_default_timezone_set('Asia/Jakarta');
        $update     = date('Y-m-d');
        $msg        = array();

	if($tabel == 'trdupload_cmsbank'){
            // Simpan Detail //                       
                $sql = "delete from trhupload_cmsbank where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                $sql = "delete from trdupload_cmsbank where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdupload_cmsbank(no_voucher,tgl_voucher,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,no_upload_tgl)"; 
                    $asg = $this->db->query($sql.$csql);                    
                    
                    $sql = "insert into trhupload_cmsbank(no_upload,tgl_upload,kd_skpd,total,no_upload_tgl) values ('$nomor','$update','$skpd','$total','$urut_tgl')";
                    $asg = $this->db->query($sql);
                    
                    $sql = "UPDATE
                            trhtransout_cmsbank
                            SET trhtransout_cmsbank.status_upload = Table_B.status_upload,
		                         trhtransout_cmsbank.tgl_upload = Table_B.tgl_upload
                        FROM trhtransout_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp from trhupload_cmsbank a left join 
                        trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_bp='$skpd' and a.no_upload='$nomor') AS Table_B ON trhtransout_cmsbank.no_voucher = Table_B.no_voucher AND trhtransout_cmsbank.kd_skpd = Table_B.kd_skpd
                        where left(trhtransout_cmsbank.kd_skpd,7)=left('$skpd',7)
                        ";
                    $asg = $this->db->query($sql);
                       
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }    
 
     function load_draf_upload(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.no_upload='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
		
        $sql = "SELECT count(*) as total from trhupload_cmsbank a 
        left join trdupload_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where $init_skpd $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT b.kd_skpd,b.no_voucher,b.tgl_voucher,a.no_upload,a.tgl_upload,a.total,b.nilai,b.status_upload,
b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,b.bank_tujuan,b.ket_tujuan,c.bersih FROM trhupload_cmsbank a 
        left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload
        left join (
        select a.no_voucher,a.kd_skpd,sum(a.nilai) bersih from trdtransout_transfercms a where $init_skpd
        group by no_voucher,kd_skpd)c on c.no_voucher=b.no_voucher and c.kd_skpd=b.kd_skpd          
        where $init_skpd $and 
        group by 
        b.kd_skpd,b.no_voucher,b.tgl_voucher,a.no_upload,a.tgl_upload,a.total,b.nilai,b.status_upload,
b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,b.bank_tujuan,b.ket_tujuan,c.bersih
        order by cast(a.no_upload as int),b.kd_skpd");		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2),
                        'viewtotal' => number_format($resulte['nilai'],2),
                        'viewbersih' => number_format($resulte['bersih'],2),
                        'viewpot' => number_format($resulte['nilai']-$resulte['bersih'],2),
                        'nilai' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],                        
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']
                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}
    
	function simpan_bataluploadcms(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomor_up = $this->input->post('noup');        
        $skpd     = $this->input->post('skpd');        
        $update     = date('Y-m-d');
        $msg        = array();

	if($tabel == 'trdupload_cmsbank') {
            // Simpan Detail //               
                $sql_h = "select count(*) as jum from trdupload_cmsbank where no_upload='$nomor_up' AND kd_skpd='$skpd'";
                    $asg_h = $this->db->query($sql_h)->row();
                    $inith = $asg_h->jum; 
                    
                    if($inith>1){
                        $sql = "delete from trdupload_cmsbank where no_voucher='$nomor' and no_upload='$nomor_up' AND kd_skpd='$skpd'";
                        $asg = $this->db->query($sql);
                        
                        
                        $sql = "UPDATE
                            trhupload_cmsbank
                            SET trhupload_cmsbank.total = Table_B.total		                         
                        FROM trhupload_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp,sum(b.nilai) as total from trhupload_cmsbank a left join 
                        trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_bp='$skpd' and a.no_upload='$nomor_up'
                        group by a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp) AS Table_B ON trhupload_cmsbank.no_upload = Table_B.no_upload AND trhupload_cmsbank.kd_skpd = Table_B.kd_skpd
                        where left(trhupload_cmsbank.kd_skpd,7)=left('$skpd',7)
                        ";
                        $asg = $this->db->query($sql);                        
                        
                    }else{
                        $sql = "delete from trdupload_cmsbank where no_voucher='$nomor' and no_upload='$nomor_up' AND kd_skpd='$skpd'";
                        $asg = $this->db->query($sql);
                        
                        $sql = "delete from trhupload_cmsbank where no_upload='$nomor_up' AND kd_skpd='$skpd'";
                        $asg = $this->db->query($sql);                                   
                    }                        
                
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{                                
                    $sql = "update trhtransout_cmsbank set status_upload='0', tgl_upload='' where no_voucher='$nomor' AND kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);                    
                                           
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }    


	
}