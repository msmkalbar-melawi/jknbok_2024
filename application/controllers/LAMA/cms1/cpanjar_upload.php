<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cpanjar_upload extends CI_Controller {

    public $org_keu = "";
    public $skpd_keu = "";
    
    function __contruct()
    {   
        parent::__construct();
    }

    function index(){
        $data['page_title']= 'UPLOAD PANJAR';
        $this->template->set('title', 'INPUT UPLOAD PANJAR');   
        $this->template->load('template','tukd/cms/panjar_upload',$data) ; 
    }   

    function load_list_belumpanjar_upload_cms(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" ";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where a.kd_skpd='$skpd' and a.status_upload='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.*,b.nm_kegiatan,isnull((select sum(nilai) from trhtrmpot_cmsbank where no_voucher=a.no_panjar and kd_skpd=a.kd_skpd ),0) [pot],
            isnull((select sum(nilai) from tr_panjar_transfercms where no_bukti=a.no_panjar and kd_skpd=a.kd_skpd),0) [bersih] 
            FROM tr_panjar_cmsbank a  join trskpd b on a.kd_kegiatan=b.kd_kegiatan
        where a.kd_skpd='$skpd' and a.status_upload='0' $and         
        order by cast(a.no_kas as int),a.kd_skpd" );     
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_bukti' => $resulte['no_kas'],
                        'tgl_bukti' => $resulte['tgl_kas'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                         'kd_kegiatan' => $resulte['kd_kegiatan'],
                         'nm_kegiatan' => $resulte['nm_kegiatan'],
                         'bersih' => number_format($resulte['bersih'],2),
                         'pot' => number_format($resulte['pot'],2)                                                     
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
        order by cast(a.no_voucher as int),a.kd_skpd"); */  
        
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

    function load_listpanjar_upload_cms(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_kas='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where a.kd_skpd='$skpd' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.* FROM tr_panjar_cmsbank a 
        where a.kd_skpd='$skpd' $and 
        and a.no_kas not in (SELECT top $offset a.no_kas FROM tr_panjar_cmsbank a  
        WHERE a.kd_skpd='$skpd' $and order by cast(a.no_kas as int))
        order by cast(a.no_kas as int),a.kd_skpd");     
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}
            
            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'],'nm_skpd','ms_skpd','kd_skpd');
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $nmskpd,                        
                        'no_bukti' => $resulte['no_kas'],
                        'tgl_bukti' => $resulte['tgl_kas'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']
                                                                              
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
    }

    function load_hdraf_upload_panjar(){
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
        
        $sql = "SELECT count(*) as total from trhupload_cmsbank_panjar a
        where a.kd_skpd='$skpd' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.* FROM trhupload_cmsbank_panjar a               
        where a.kd_skpd='$skpd' $and         
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

    function load_total_upload_panjar($tgl=''){
       $kode    = $this->session->userdata('kdskpd');
       //$tgl     = $this->input->post('cari');
              
            $sql = "SELECT
                        SUM (a.nilai) AS total_upload
                    FROM
                        tr_panjar_cmsbank a                 
                    WHERE
                        a.kd_skpd = $kode
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

    function simpan_uploadcms_panjar(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $skpd     = $this->input->post('skpd');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');      
        $urut_tgl = $this->input->post('urut_tglupload');
        
        date_default_timezone_set('Asia/Jakarta');
        $update     = date('Y-m-d');
        $msg        = array();

    if($tabel == 'trdupload_cmsbank_panjar') {
            // Simpan Detail //                       
                $sql = "delete from trhupload_cmsbank_panjar where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                $sql = "delete from trdupload_cmsbank_panjar where no_upload='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdupload_cmsbank_panjar (no_bukti,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,no_upload_tgl) "; 
                    $asg = $this->db->query($sql.$csql);
                    
                    $skpd = $this->session->userdata('kdskpd'); 
                    $sql = "insert into trhupload_cmsbank_panjar (no_upload,tgl_upload,kd_skpd,total,no_upload_tgl) values ('$nomor','$update','$skpd','$total','$urut_tgl')";
                    $asg = $this->db->query($sql);
                    
                    $sql = "UPDATE
                            tr_panjar_cmsbank
                            SET tr_panjar_cmsbank.status_upload = Table_B.status_upload,
                                 tr_panjar_cmsbank.tgl_upload = Table_B.tgl_upload
                        FROM tr_panjar_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_bukti,b.kd_bp from trhupload_cmsbank_panjar a left join 
                        trdupload_cmsbank_panjar b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_bp='$skpd' and a.no_upload='$nomor') AS Table_B ON tr_panjar_cmsbank.no_kas = Table_B.no_bukti AND tr_panjar_cmsbank.kd_skpd = Table_B.kd_skpd
                        where tr_panjar_cmsbank.kd_skpd='$skpd'
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
    
    function load_draf_upload_panjar(){
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.no_upload='$kriteria'";            
        }
        
        $skpd = $this->session->userdata('kdskpd');
        
        $sql = "SELECT count(*) as total from trhupload_cmsbank_panjar a left join trdupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where a.kd_skpd='$skpd' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.*,b.* FROM trhupload_cmsbank_panjar a left join trdupload_cmsbank_panjar b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        where a.kd_skpd='$skpd' $and         
        order by cast(a.no_upload as int),a.kd_skpd");      
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_upload']==1){
            $stt="&#10004";}else{$stt="X";}
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'total' => number_format($resulte['total'],2),
                        'viewtotal' => number_format($resulte['nilai'],2),
                        'nilai' => number_format($resulte['nilai'],2),
                        'status_upload' => $stt,
                        'status_uploadx' => $resulte['status_upload'],
                        'tgl_upload' => $resulte['tgl_upload'],
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

    function csv_cmsbank_panjar($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='OB';                 
        
        $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=a.kd_skpd) as nm_skpd,
        b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank_panjar a 
        left join trdupload_cmsbank_panjar b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload
        left join tr_panjar_transfercms c on c.kd_skpd=b.kd_skpd and c.no_bukti=b.no_bukti 
        where a.kd_skpd='$skpd' and a.no_upload='$nomor'");
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
            $nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($resulte['rekening_awal'])).";".rtrim($resulte['nm_rekening_tujuan']).";".str_replace(" ","",rtrim($resulte['rekening_tujuan'])).";".$nilai.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }
    

}