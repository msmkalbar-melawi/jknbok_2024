<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cpanjar_validasi extends CI_Controller {

    public $org_keu = "";
    public $skpd_keu = "";
    
    function __contruct()
    {   
        parent::__construct();
    }

    function index(){
        $data['page_title']= 'VALIDASI PANJAR';
        $this->template->set('title', 'INPUT VALIDASI PANJAR');   
        $this->template->load('template','tukd/cms/panjar_validasi',$data) ; 
    }  



    function no_urut()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT  case when max(nomor+1) is null then 1 else max(nomor) end as nomor from (
            select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
            select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
            select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
            select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
            select no_panjar nomor, 'Pemberian Panjar' ket,kd_skpd from tr_panjar where  isnumeric(no_panjar)=1  union ALL
            select no_panjar nomor, 'Pemberian Panjar CMS' ket,kd_skpd from tr_panjar_cmsbank where  isnumeric(no_panjar)=1  union ALL
            select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
            select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
            select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
            select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
            select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
            select no_bukti+1 nomor, 'Ambil Simpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND (status_drop !='1' OR status_drop is null) union ALL
            select no_bukti nomor, 'Ambil Drop Dana' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop ='1' union ALL
            select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
            select no_kas nomor, 'Setor Simpanan CMS' ket,kd_skpd_sumber kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_bukti)=1 union all
            select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
            select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='3' union ALL
            select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
            select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
            select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_kas)=1 union all
        select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE KD_SKPD = '$kd_skpd'");
      $ii = 0;
      foreach ($query1->result_array() as $resulte) {
          $result = array(
              'id' => $ii,
              'no_urut' => $resulte['nomor']
          );
          $ii++;
      }

      echo json_encode($result);
      $query1->free_result();

    }

    function load_listbelum_validasi_panjar(){
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
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where a.kd_skpd='$skpd' and a.status_upload='1' and a.status_validasi='0' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.*,c.no_upload FROM tr_panjar_cmsbank a 
        left join trdupload_cmsbank_panjar c on a.no_kas = c.no_bukti and a.kd_skpd = c.kd_skpd
        where a.kd_skpd='$skpd' and a.status_upload='1' and a.status_validasi='0' $and         
        order by cast(a.no_kas as int),a.kd_skpd");     
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_kas'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_bukti' => $resulte['tgl_kas'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
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
    
    function load_list_validasi_panjar(){
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
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where a.kd_skpd='$skpd' and status_upload='1' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM tr_panjar_cmsbank a 
        left join trdupload_cmsbank_panjar c on a.no_kas = c.no_bukti and a.kd_skpd = c.kd_skpd
        where a.kd_skpd='$skpd' and a.status_upload='1' $and 
        and a.no_kas not in (SELECT top $offset a.no_kas FROM tr_panjar_cmsbank a  
        WHERE a.kd_skpd='$skpd' and a.status_upload='1' $and order by cast(a.no_kas as int))
        order by cast(a.no_kas as int),a.kd_skpd" );
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'no_bukti' => $resulte['no_panjar'],                        
                        'no_upload' => $resulte['no_upload'],
                        'tgl_bukti' => $resulte['tgl_panjar'],
                        'ket' => $resulte['keterangan'],
                        'total' => number_format($resulte['nilai'],2),
                        'status_upload' => $resulte['status_upload'],
                        'status_validasix' => $resulte['status_validasi'],
                        'tgl_upload' => $resulte['tgl_upload'],
                        'status_validasi' => $stt_val,
                        'tgl_validasi' => $resulte['tgl_validasi'],
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

    function simpan_validasicms_panjar(){
        $tabel    = $this->input->post('tabel');                
        $skpd     = $this->input->post('skpd');
        $csql     = $this->input->post('sql');      
        $nval     = $this->input->post('no');  
        
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

    if($tabel == 'trvalidasi_cmsbank_panjar') {
                    
                    $sql = "delete from trvalidasi_cmsbank_panjar where kd_bp='$skpd_ss' and no_validasi='$nval'"; 
                    $asg = $this->db->query($sql);
                            
                    $sql = "insert into trvalidasi_cmsbank_panjar(no_voucher,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,tgl_validasi,status_validasi,no_validasi,no_bukti)"; 
                    $asg = $this->db->query($sql.$csql);
                   
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else { 
                                           
                       $sql = "UPDATE
                            tr_panjar_cmsbank
                            SET tr_panjar_cmsbank.status_validasi = Table_B.status_validasi,
                                tr_panjar_cmsbank.tgl_validasi = Table_B.tgl_validasi                                
                        FROM tr_panjar_cmsbank     
                        INNER JOIN (select a.no_voucher [no_bukti],a.kd_skpd,a.kd_bp,a.tgl_validasi,a.status_validasi from trvalidasi_cmsbank_panjar a
                        where a.kd_bp='$skpd_ss' and no_validasi='$nval') AS Table_B ON tr_panjar_cmsbank.no_kas = Table_B.no_bukti AND tr_panjar_cmsbank.kd_skpd = Table_B.kd_skpd
                        where tr_panjar_cmsbank.kd_skpd='$skpd_ss'
                        ";
                        $asg = $this->db->query($sql);
                        
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql = "INSERT INTO tr_panjar (no_kas,tgl_kas,no_panjar,tgl_panjar,kd_skpd,pengguna,nilai,keterangan,pay,rek_bank,kd_sub_kegiatan,status,jns,no_panjar_lalu)
                                    SELECT b.no_bukti,a.tgl_kas,b.no_bukti,a.tgl_panjar,a.kd_skpd,a.pengguna,a.nilai,a.keterangan,a.pay,a.rek_bank,a.kd_sub_kegiatan,a.status,a.jns,
                                    (case when a.jns='2' then no_panjar_lalu else b.no_bukti end) no_panjar_lalu
                                    FROM tr_panjar_cmsbank a left join trvalidasi_cmsbank_panjar b on b.no_voucher=a.no_kas and a.kd_skpd=b.kd_skpd
                                    WHERE b.no_validasi='$nval' and b.kd_bp='$skpd_ss'";
                            $asg = $this->db->query($sql);
                            
                                if (!($asg)){
                                $msg = array('pesan'=>'0');
                                echo json_encode($msg);                     
                                }  else { 
                                    //Hpotongan
                                    $sql = "INSERT INTO trhtrmpot (no_bukti, tgl_bukti, ket, username, tgl_update, kd_skpd, nm_skpd, no_sp2d, nilai, npwp, jns_spp, 
                                            status, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nmrekan, pimpinan, alamat, ebilling, 
                                            rekening_tujuan, nm_rekening_tujuan, no_kas,pay)
                                            SELECT cast(c.no_bukti as int)+1 as no_bukti, c.tgl_validasi as tgl_bukti, d.ket, d.username, d.tgl_update, d.kd_skpd, d.nm_skpd, d.no_sp2d, d.nilai, d.npwp, d.jns_spp, d.status, d.kd_sub_kegiatan, d.nm_sub_kegiatan, d.kd_rek6, d.nm_rek6, d.nmrekan, d.pimpinan, d.alamat, d.ebilling, d.rekening_tujuan, d.nm_rekening_tujuan, c.no_bukti, 'BANK' 
                                            FROM trhtrmpot_cmsbank d JOIN tr_panjar_cmsbank a on d.no_voucher=a.no_panjar and a.kd_skpd=d.kd_skpd
                                            LEFT JOIN trvalidasi_cmsbank_panjar c on c.no_voucher=a.no_panjar and a.kd_skpd=c.kd_skpd
                                            WHERE c.no_validasi='$nval' and c.kd_skpd='$skpd' ";
                                    $asg = $this->db->query($sql);                                    

                                    if (!($asg)){
                                        $msg = array('pesan'=>'0');
                                        echo json_encode($msg);                     
                                    }  else {                                                                        
                                        $sql = "INSERT INTO trdtrmpot (no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans,ebilling)
                                        SELECT cast(c.no_bukti as int)+1 as no_bukti, b.kd_rek6, b.nm_rek6, b.nilai, b.kd_skpd, b.kd_rek_trans,b.ebilling
                                        FROM trhtrmpot_cmsbank d inner join trdtrmpot_cmsbank b on b.no_bukti=d.no_bukti and b.kd_skpd=d.kd_skpd
                                        LEFT JOIN tr_panjar_cmsbank a on d.no_voucher=a.no_panjar and a.kd_skpd=d.kd_skpd
                                        LEFT JOIN trvalidasi_cmsbank_panjar c on c.no_voucher=a.no_panjar and a.kd_skpd=c.kd_skpd
                                        WHERE c.no_validasi='$nval' and c.kd_skpd='$skpd'";
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
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank a 
        where a.kd_skpd='$skpd' and a.status_upload='1' and a.status_validasi='1' $and " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $query1 = $this->db->query("SELECT a.*,c.no_upload ,d.no_bukti
FROM tr_panjar_cmsbank a 
left join trdupload_cmsbank_panjar c on a.no_kas = c.no_bukti and a.kd_skpd = c.kd_skpd
left join trvalidasi_cmsbank_panjar d on a.no_kas=d.no_voucher and a.kd_skpd = d.kd_skpd and a.tgl_kas=d.tgl_bukti
where a.kd_skpd='$skpd' and a.status_upload='1' and a.status_validasi='1' $and         
order by cast(a.no_kas as int),a.kd_skpd");     
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            
            if($resulte['status_validasi']==1){
            $stt_val="&#10004";}else{$stt_val="X";}            
               
            $row[] = array(
                         'id' => $ii,        
                         'kd_skpd' => $resulte['kd_skpd'],
                         'no_voucher' => $resulte['no_kas'],  
                         'no_bku' => $resulte['no_bukti'],                        
                         'no_upload' => $resulte['no_upload'],
                         'tgl_voucher' => $resulte['tgl_kas'],
                         'ket' => $resulte['keterangan'],
                         'total' => number_format($resulte['nilai'],2),
                         'status_upload' => $resulte['status_upload'],
                         'status_validasix' => $resulte['status_validasi'],
                         'tgl_upload' => $resulte['tgl_upload'],
                         'status_validasi' => $stt_val,
                         'tgl_validasi' => $resulte['tgl_validasi'],
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
 
    // function load_list_telahvalidasi(){
    //     $result = array();
    //     $row = array();
    //     $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    //     $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    //     $offset = ($page-1)*$rows;        
        
    //     $kriteria = $this->input->post('cari');
    //     $and ='';
    //     if ($kriteria <> ''){                               
    //         $and=" and a.tgl_validasi='$kriteria'";            
    //     }
    //     if ($kriteria <> ''){                               
    //         $and2=" and c.tgl_validasi='$kriteria'";            
    //     }

    //     $skpd = $this->session->userdata('kdskpd');
    //     $init_skpd = "a.kd_skpd='$skpd'";
        
    //     $sql = "SELECT b.no_bukti,count(*) as total from tr_panjar_cmsbank a left join trvalidasi_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and a.no_kas=b.no_voucher 
    //     where $init_skpd and a.status_upload='1' $and group by b.no_bukti" ;
    //     $query1 = $this->db->query($sql);
    //     $total = $query1->row();
        
    //     $query1 = $this->db->query("SELECT a.*,c.no_upload FROM tr_panjar_cmsbank a 
    //     left join trdupload_cmsbank_panjar c on a.no_kas = c.no_bukti and a.kd_skpd = c.kd_skpd
    //     where a.kd_skpd='$skpd' and a.status_upload='1' and a.status_validasi='0' $and         
    //     order by cast(a.no_kas as int),a.kd_skpd ");        
    //     $result = array();
    //     $ii     = 0;
    //     foreach($query1->result_array() as $resulte)
    //     { 
            
    //         if($resulte['status_validasi']==1){
    //         $stt_val="&#10004";}else{$stt_val="X";}            
               
    //         $row[] = array(
    //                     'id' => $ii,        
    //                     'kd_skpd' => $resulte['kd_skpd'],
    //                     'no_voucher' => $resulte['no_kas'],  
    //                     'no_bku' => $resulte['no_bukti'],                        
    //                     'no_upload' => $resulte['no_upload'],
    //                     'tgl_voucher' => $resulte['tgl_kas'],
    //                     'ket' => $resulte['ket'],
    //                     'total' => number_format($resulte['total'],2),
    //                     'status_upload' => $resulte['status_upload'],
    //                     'status_validasix' => $resulte['status_validasi'],
    //                     'tgl_upload' => $resulte['tgl_upload'],
    //                     'status_validasi' => $stt_val,
    //                     'tgl_validasi' => $resulte['tgl_validasi'],
    //                     'rekening_awal' => $resulte['rekening_awal'],
    //                     'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
    //                     'rekening_tujuan' => $resulte['rekening_tujuan'],
    //                     'bank_tujuan' => $resulte['bank_tujuan'],
    //                     'ket_tujuan' => $resulte['ket_tujuan']
    //                     //'status_pot' => $resulte['status_trmpot']                                                       
    //                     );
    //                     $ii++;
    //     }
        
    //     $result["total"] = $total->total;        
    //     $result["rows"] = $row;           
    //     echo json_encode($result);           
    // }

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
        $cek_skpd = $this->db->query("SELECT count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
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

    function batal_validasicms(){
        $tabel    = $this->input->post('tabel');  
        $skpd     = $this->input->post('skpd');
        $nbku     = $this->input->post('nobukti');   
        $nbku_i   = strval($nbku)+1;     
        $nval     = $this->input->post('novoucher'); 
        $tglbku   = $this->input->post('tglvalid');
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

    if($tabel == 'trvalidasi_cmsbank_panjar') {
                    
                    //hapus Htrans   
                    $sql ="delete from trvalidasi_cmsbank_panjar where no_bukti='$nbku' and no_voucher='$nval' and kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);   
                            
                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);                     
                    }  else {                        
                       
                       $sql ="delete from tr_panjar where no_kas='$nbku' and tgl_kas='$tglbku' and kd_skpd='$skpd'";
                       $asg = $this->db->query($sql);  

                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);                     
                        }  else {                     
                            
                            $sql ="update tr_panjar_cmsbank set status_validasi='0', tgl_validasi='' where no_kas='$nval' and kd_skpd='$skpd'";
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