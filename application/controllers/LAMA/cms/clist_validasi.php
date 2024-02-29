<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class clist_validasi extends CI_Controller {
	public $org_keu = "";
	public $skpd_keu = "";
	
	function __contruct(){	
		parent::__construct();
	}

    function index(){
        $data['page_title']= 'DAFTAR VALIDASI NON TUNAI';
        $this->template->set('title', 'DAFTAR VALIDASI NON TUNAI');   
        $this->template->load('template','tukd/cms/list_validasi',$data) ; 
    }    
    
    function load_listbelum_validasi(){
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
		
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a 
        where $init_skpd and a.status_upload='1' and a.status_validasi='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload, CASE WHEN a.jns_spp IN ('4','6') 
THEN (SELECT SUM(x.nilai) tot_pot 
FROM trspmpot x INNER JOIN trhsp2d b ON x.kd_skpd=b.kd_skpd AND x.no_spm=b.no_spm
				INNER JOIN trhtransout_cmsbank c ON b.kd_skpd=c.kd_skpd AND b.no_sp2d=c.no_sp2d
WHERE c.kd_skpd='$skpd' AND c.no_sp2d=a.no_sp2d AND c.jns_spp IN ('4','6')) ELSE 0 END AS tot_pot FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        where $init_skpd and a.status_upload='1' and status_validasi='0' $and  
        group by 
        a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload, a.jns_spp       
        order by a.kd_skpd,cast(a.no_voucher as int) ");
        
        /*
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        where left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' and status_validasi='0' $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' and status_validasi='0' $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd");	*/		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_pot' => $resulte['status_trmpot'],                                                       
                        'tot_pot' => number_format($resulte['tot_pot'],2)                                                      
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}
             
	function load_list_validasi(){
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
		
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a 
        where $init_skpd and a.status_upload='1' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        where $init_skpd and a.status_upload='1' $and         
        group by 
        a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload
        order by cast(a.no_voucher as int),a.kd_skpd");	
        
        
        /*
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        where left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd");	
        */
        	
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_pot' => $resulte['status_trmpot']                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}

    function no_urut_validasicms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $init_skpd = "KD_SKPD = '$kd_skpd'";
    
	$query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
	select no_validasi nomor, 'Urut Validasi cms' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank where kd_skpd = '$kd_skpd' 
    union all
    select no_validasi nomor, 'Urut Validasi cms Panjar' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank_panjar where kd_skpd = '$kd_skpd'
    ) 
    z WHERE $init_skpd ");
	    $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $resulte['nomor']
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }
 
   function simpan_validasicms(){
        $tabel    = $this->input->post('tabel');                
        $skpd     = $this->input->post('skpd');
        $csql     = $this->input->post('sql');      
        $nval     = $this->input->post('no');  
        
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

	if($tabel == 'trvalidasi_cmsbank') {
                            
                    $sql = "insert into trvalidasi_cmsbank(no_voucher,tgl_voucher,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,tgl_validasi,status_validasi,no_validasi,no_bukti)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       $sql = "UPDATE
                            trhtransout_cmsbank
                            SET trhtransout_cmsbank.status_validasi = Table_B.status_validasi,
		                        trhtransout_cmsbank.tgl_validasi = Table_B.tgl_validasi,
                                trhtransout_cmsbank.no_bukti = Table_B.no_bukti,
                                trhtransout_cmsbank.tgl_bukti = Table_B.tgl_validasi
                        FROM trhtransout_cmsbank     
                        INNER JOIN (select a.no_voucher,a.no_bukti,a.kd_skpd,a.kd_bp,a.tgl_validasi,a.status_validasi from trvalidasi_cmsbank a
                        where a.kd_skpd='$skpd' and no_validasi='$nval') AS Table_B ON trhtransout_cmsbank.no_voucher = Table_B.no_voucher AND trhtransout_cmsbank.kd_skpd = Table_B.kd_skpd
                        where left(trhtransout_cmsbank.kd_skpd,7)=left('$skpd',7)
                        ";
                        $asg = $this->db->query($sql);
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql = "INSERT INTO trhtransout (no_kas, tgl_kas, no_bukti, tgl_bukti, no_sp2d, ket, username, tgl_update, kd_skpd, nm_skpd, total, no_tagih, sts_tagih, tgl_tagih, jns_spp, pay, no_kas_pot, panjar, no_panjar)
                                    SELECT b.no_bukti as no_kas, b.tgl_validasi as tgl_kas, a.no_bukti, a.tgl_bukti, a.no_sp2d, a.ket, b.kd_skpd as username, a.tgl_update, b.kd_skpd, a.nm_skpd, a.total, a.no_tagih, a.sts_tagih, a.tgl_tagih, a.jns_spp, a.pay, a.no_kas_pot, a.panjar, a.no_panjar
                                    FROM trhtransout_cmsbank a left join trvalidasi_cmsbank b on b.no_voucher=a.no_voucher and a.kd_skpd=b.kd_skpd
                                    WHERE b.no_validasi='$nval' and b.kd_skpd='$skpd'";
                            $asg = $this->db->query($sql);
                            
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);                     
                                }  else {
                                    
                                    $sql = "INSERT INTO trdtransout (no_bukti, no_sp2d, kd_kegiatan, nm_kegiatan, kd_rek5, nm_rek5, nilai, kd_skpd, nil_pad, nil_dak, nil_dau, nil_dbhp, nil_daknf, nil_did,nil_hpp, sumber)
                                            SELECT c.no_bukti, a.no_sp2d, b.kd_kegiatan, b.nm_kegiatan, b.kd_rek5, b.nm_rek5, b.nilai, b.kd_skpd, 
											CASE WHEN b.sumber like '%PAD%' THEN b.nilai ELSE 0 END AS nil_pad,
											CASE WHEN b.sumber like '%DAK FISIK%' THEN b.nilai ELSE 0 END AS nil_dak,
											CASE WHEN b.sumber like '%DAU%' THEN b.nilai ELSE 0 END AS nil_dau,
											CASE WHEN b.sumber like '%DBHP%' THEN b.nilai ELSE 0 END AS nil_dbhp,
											CASE WHEN b.sumber like '%DAK NON FISIK%' THEN b.nilai ELSE 0 END AS nil_daknf,
											CASE WHEN b.sumber like '%DID%' THEN b.nilai ELSE 0 END AS nil_did,
											CASE WHEN b.sumber like '%HPP%' THEN b.nilai ELSE 0 END AS nil_hpp,
											b.sumber
                                            FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b on b.no_voucher=a.no_voucher and a.kd_skpd=b.kd_skpd
                                            LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                            WHERE c.no_validasi='$nval' and c.kd_skpd='$skpd'";
                                    $asg = $this->db->query($sql);                                    
                                    
                                    if (!($asg)){
                                        $msg = array('pesan'=>'0');
                                        echo json_encode($msg);                     
                                    }  else {                                                                        
                                        //Hpotongan
                                        $sql = "INSERT INTO trhtrmpot (no_bukti, tgl_bukti, ket, username, tgl_update, kd_skpd, nm_skpd, no_sp2d, nilai, npwp, jns_spp, 
                                                status, kd_kegiatan, nm_kegiatan, kd_rek5, nm_rek5, nmrekan, pimpinan, alamat, ebilling, 
                                                rekening_tujuan, nm_rekening_tujuan, no_kas,pay)
                                                SELECT cast(c.no_bukti as int)+1 as no_bukti, c.tgl_validasi as tgl_bukti, d.ket, d.username, d.tgl_update, d.kd_skpd, d.nm_skpd, d.no_sp2d, d.nilai, d.npwp, d.jns_spp, d.status, d.kd_kegiatan, d.nm_kegiatan, d.kd_rek5, d.nm_rek5, d.nmrekan, d.pimpinan, d.alamat, d.ebilling, d.rekening_tujuan, d.nm_rekening_tujuan, c.no_bukti, 'BANK' 
                                                FROM trhtrmpot_cmsbank d JOIN trhtransout_cmsbank a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd
                                                LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                                WHERE c.no_validasi='$nval' and a.status_trmpot='1' and c.kd_skpd='$skpd'";
                                            $asg = $this->db->query($sql);                                    
                                    
                                            if (!($asg)){
                                                $msg = array('pesan'=>'0');
                                                echo json_encode($msg);                     
                                            }  else {                                                                        
                                        
                                                    $sql = "INSERT INTO trdtrmpot (no_bukti, kd_rek5, nm_rek5, nilai, kd_skpd, kd_rek_trans,ebilling,rekanan,npwp)
                                                    SELECT cast(c.no_bukti as int)+1 as no_bukti, b.kd_rek5, b.nm_rek5, b.nilai, b.kd_skpd, b.kd_rek_trans,b.ebilling,b.rekanan,b.npwp
                                                    FROM trhtrmpot_cmsbank d inner join trdtrmpot_cmsbank b on b.no_bukti=d.no_bukti and b.kd_skpd=d.kd_skpd
                                                    LEFT JOIN trhtransout_cmsbank a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd
                                                    LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                                    WHERE c.no_validasi='$nval' and a.status_trmpot='1' and c.kd_skpd='$skpd'";
                                                    $asg = $this->db->query($sql);                                    
                                    
                                                if (!($asg)){
                                                    $msg = array('pesan'=>'0');
                                                    echo json_encode($msg);                     
                                                }  else {                                                                        
                                                    
                                                    
                                                    $sql = "INSERT INTO trdtransout_transfer(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)
                                                    SELECT a.no_bukti, a.tgl_bukti, d.rekening_awal, d.nm_rekening_tujuan, d.rekening_tujuan, d.bank_tujuan, d.kd_skpd, d.nilai
                                                    FROM trdtransout_transfercms d 
                                                    LEFT JOIN trhtransout_cmsbank a on d.no_voucher=a.no_voucher and a.kd_skpd=d.kd_skpd
                                                    LEFT JOIN trvalidasi_cmsbank c on c.no_voucher=a.no_voucher and a.kd_skpd=c.kd_skpd
                                                    WHERE c.no_validasi='$nval' and c.kd_skpd='$skpd' ";
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
                        }
                    }                    
                       					                
        }
    }    
 	
    function load_list_telahvalidasi(){
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        
        $kriteria = $this->input->post('cari');
        $and ='';
        if ($kriteria <> ''){                               
            $and=" and a.tgl_validasi='$kriteria'";            
        }
        
		$skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
		
        $sql = "SELECT a.no_bukti,count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where $init_skpd and status_upload='1' $and group by a.no_bukti" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	
        $query1 = $this->db->query("SELECT a.kd_skpd,a.no_voucher,a.tgl_voucher,a.ket,a.total,a.status_upload,a.status_validasi,
        a.tgl_upload,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,
        a.ket_tujuan,a.status_trmpot,c.no_upload,d.no_bukti FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        left join trvalidasi_cmsbank d on d.no_voucher = c.no_voucher and d.kd_bp = c.kd_bp
        where $init_skpd and a.status_upload='1' and a.status_validasi='1' $and 
        group by 
        a.kd_skpd,a.no_voucher,a.tgl_voucher,a.ket,a.total,a.status_upload,a.status_validasi,
        a.tgl_upload,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,
        a.ket_tujuan,a.status_trmpot,c.no_upload,d.no_bukti
        order by cast(d.no_bukti as int),a.tgl_validasi,a.kd_skpd");		
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_voucher' => $resulte['no_voucher'],  
                        'no_bku' => $resulte['no_bukti'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'ket' => $resulte['ket'],
                        'total' => number_format($resulte['total'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
                        'rekening_awal' => $resulte['rekening_awal'],
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan' => $resulte['rekening_tujuan'],
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan'],
                        'status_pot' => $resulte['status_trmpot']                                                       
                        );
                        $ii++;
        }
        
        $result["total"] = $total->total;        
        $result["rows"] = $row;           
        echo json_encode($result);           
	}

    function batal_validasicms(){
        $tabel    = $this->input->post('tabel');  
        $skpd     = $this->input->post('skpd');
        $nbku     = $this->input->post('nobukti');   
        $nbku_i   = strval($nbku)+1;     
        $nval     = $this->input->post('novoucher'); 
        $tglbku   = $this->input->post('tglvalid');
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

		$spjbulan = $this->tukd_model->cek_status_spj($skpd_ss);

		$prv = $this->db->query("SELECT COUNT(*) tot_lpj, 
								(SELECT CASE WHEN MONTH(a.tgl_bukti)<='$spjbulan' THEN 1 ELSE 0 END FROM trhtransout a WHERE  a.panjar = '0' AND a.kd_skpd='$skpd' AND a.no_bukti='$nbku') tot_spj 
								from trlpj z where z.no_bukti = '$nbku' and z.kd_skpd = '$skpd'");
		$prvn = $prv->row();          
		$cek_lpj = $prvn->tot_lpj;  
		$cek_spj = $prvn->tot_spj;  

		if($cek_lpj != 0 || $cek_spj != 0){
			$msg = array('pesan'=>'3');
            echo json_encode($msg);
		}else{

	if($tabel == 'trvalidasi_cmsbank') {
	                
                    //hapus Htrans   
                    $sql ="delete from trhtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);   
                            
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       
                       $sql ="delete from trdtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
                       $asg = $this->db->query($sql);   
                    
                        $asg = $this->db->query($sql);
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql ="delete from trvalidasi_cmsbank where no_bukti='$nbku' and no_voucher='$nval' and kd_skpd='$skpd'";
                            $asg = $this->db->query($sql);
                            
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);                     
                                }  else {
                                    
                                    $sql ="update trhtransout_cmsbank set status_validasi='0', tgl_validasi='' where no_voucher='$nval' and kd_skpd='$skpd'";
                                    $asg = $this->db->query($sql);                                   
                                    
                                    if (!($asg)){
                                        $msg = array('pesan'=>'0');
                                        echo json_encode($msg);                     
                                    }  else {                                                                        
                                        //Hpotongan
                                        $sql = "select count(*) as jml from trhtransout_cmsbank where no_voucher='$nval' and kd_skpd='$skpd' and status_trmpot='1'";
                                            $asg = $this->db->query($sql)->row();                                    
                                                $initjml = $asg->jml;
                                                
                                                if($initjml=='1'){
                                                
                                                $sql = "delete trhtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                                                $asg = $this->db->query($sql);                                    
                                    
                                                if (!($asg)){
                                                    $msg = array('pesan'=>'0');
                                                    echo json_encode($msg);                     
                                                }  else {                  
                                                        
                                                    $sql = "delete trdtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                                                    $asg = $this->db->query($sql);                                    
                                    
                                                    if (!($asg)){
                                                        $msg = array('pesan'=>'0');
                                                        echo json_encode($msg);                     
                                                    }  else {                  
                                                        
                                                        $sql = "delete trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
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
                                                    
                                                }else{
                                                        $sql = "delete trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
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
                    }                    
            }            					                
        }
    }    

	
}