<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lpjup extends CI_Controller {
    public $org_keu = "";
    public $skpd_keu = "";

    // public $ppkd1 = "4.02.02.01";
    // public $ppkd2 = "4.02.02.02";
    
    function __construct()
    {   
        parent::__construct();
        //$this->load->library('template');
        
  
    }
    function right($value, $count){
    return substr($value, ($count*-1));
    }

    function left($string, $count){
    return substr($string, 0, $count);
    }

    function config_tahun() 
    {
        $result = array();
         $tahun  = $this->session->userdata('pcThang');
         $result = $tahun;
         echo json_encode($result);
           
    }

    function tambah_tanggal(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT DATEADD(DAY,1,MAX(tgl_akhir)) as tanggal_awal FROM trhlpj WHERE jenis='1' AND kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);  
        
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'tgl_awal' => $resulte['tanggal_awal']
                        
                        );
                        $ii++;
        }
        

        
        
        echo json_encode($result);
        $query1->free_result();   
    }

    function  getBulan($bln){
        switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "April";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
    }
    }

     function  tanggal_format_indonesia($tgl){
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;

        }
    

    

    function load_jenis_beban($jenis='') {
     if ($jenis==3){
        $result = array(( 
                        array(
                        "id"   => 1 ,
                        "text" => " TU",
                        "selected"=>true
                        ) 
                    ) 
                );
         
     } else if($jenis==4){
        $result = array(( 
                        array(
                        "id"   => 1 ,
                        "text" => " Gaji & Tunjangan"
                        ) 
                    ) ,
                        ( 
                        array( 
                      "id"   => 2 ,
                      "text" => " Kespeg"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 3 ,
                      "text" => " Uang Makan"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 4 ,
                      "text" => " Upah Pungut"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 5 ,
                      "text" => " Upah Pungut PBB"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 6 ,
                      "text" => " Upah Pungut PBB-KB PKB & BBN-KB"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 7 ,
                      "text" => " Tambahan/Kekurangan Gaji & Tunjangan"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 8 ,
                      "text" => " Tunjangan Transport"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 9 ,
                      "text" => " Tunjangan Lainnya"
                        ) 
                    )
                );          
     } else if($jenis==6){
        $result = array(( 
                        array(
                        "id"   => 1 ,
                        "text" => " LS Rutin (PNS)"
                        ) 
                    ) ,
                        ( 
                        array( 
                      "id"   => 2 ,
                      "text" => " LS Rutin (Non PNS)"
                        ) 
                    ),
                        ( 
                        array( 
                      "id"   => 3 ,
                      "text" => " LS Pihak Ketiga"
                        ) 
                    )
                    
                );          
     }
                 echo json_encode($result);
               
            
             }

    function config_bank2(){
        
         $lccr   = $this->input->post('q');
        $sql    = "SELECT kode, nama FROM ms_bank where upper(kode) like '%$lccr%' or upper(nama) like '%$lccr%' order by kode ";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_bank' => $resulte['kode'],
                        'nama_bank' => $resulte['nama']                                                                                        
                        );
                        $ii++;
        }
        
        
        echo json_encode($result);
        $query1->free_result();   
    }
    
    function pilih_ttd($dns='') {
        $lccr = $this->input->post('q');
        $sql = "SELECT nip,nama,jabatan,kd_skpd FROM ms_ttd where kd_skpd ='$dns' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],  
                        'jabatan' => $resulte['jabatan'],
                        'kd_skpd' => $resulte['kd_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();       
    }

    function load_ttd($ttd){
        $kd_skpd = $this->session->userdata('kdskpd'); 
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode='$ttd'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $mas->free_result();
           
    }

    function load_ttd2($ttd1='', $ttd2=''){
        $kd_skpd = $this->session->userdata('kdskpd'); 
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode IN('$ttd1','$ttd2')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $mas->free_result();
           
    }

    
    

    function lpj()
    {
        $data['page_title']= 'INPUT LPJ UP';
        $this->template->set('title', 'INPUT LPJ UP');   
        $this->template->load('template','tukd/transaksi/tambah_lpj',$data) ; 
    }

    function load_lpj() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> ''){                               
            $where=" and (upper(no_lpj) like upper('%$kriteria%') or tgl_lpj like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";            
        }

        $sql = "SELECT count(*) as tot from trhlpj WHERE  kd_skpd = '$kd_skpd' AND jenis = '1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
                
        $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd = '$kd_skpd') as nm_skpd FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        
        foreach($query1->result_array() as $resulte){ 
            $row[] = array(
                        'id' => $ii,
                        'kd_skpd'    => $resulte['kd_skpd'],      
                        'nm_skpd'    => $resulte['nm_skpd'],                          
                        'ket'   => $resulte['keterangan'],
                        'no_lpj'   => $resulte['no_lpj'],
                        'tgl_lpj'      => $resulte['tgl_lpj'],
                        'status'      => $resulte['status'],
                        'tgl_awal'      => $resulte['tgl_awal'],
                        'tgl_akhir'      => $resulte['tgl_akhir']
                        );
                        $ii++;
        }
           
       $result["total"] = $total->tot;
       $result["rows"] = $row; 
       $query1->free_result();   
       echo json_encode($result);
    }

    function select_data1_lpj_ag($lpj='') {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT a.kd_skpd, a.no_lpj,a.no_bukti,a.kd_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai FROM trlpj a INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                WHERE a.no_lpj='$lpj' AND a.kd_skpd='$kd_skpd'";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'idx'        => $ii,
                        'no_bukti'   => $resulte['no_bukti'],                       
                        'kdkegiatan' => $resulte['kd_kegiatan'],     
                        'kdrek6'     => $resulte['kd_rek6'],  
                        'nmrek6'     => $resulte['nm_rek6'],                         
                        'nilai1'      => number_format($resulte['nilai'])

                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }

    function load_giat_lpj(){

        $nomor = $this->input->post('lpj');
        $query1 = $this->db->query("
        select a.kd_sub_kegiatan, c.nm_sub_kegiatan
        from trlpj a 
        INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trskpd c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan AND a.kd_skpd=c.kd_skpd
        WHERE a.no_lpj = '$nomor'
        GROUP BY a.kd_sub_kegiatan,c.nm_sub_kegiatan
        ORDER BY a.kd_sub_kegiatan");  
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
           
           //return $result;
           echo json_encode($result);
           $query1->free_result();  
    }

    function config_up(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT SUM(a.nilai) as nilai FROM trdspp a INNER JOIN trhspp b ON a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' AND b.jns_spp='1' AND (sp2d_batal is null or sp2d_batal<>'1')"; 
        $query1 = $this->db->query($sql);  
        
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'nilai_up' => $resulte['nilai']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();   
    }

    function cek_sisa_spd(){
        $skpd = $this->session->userdata('kdskpd');
        $spp    = $this->input->post('spp');
        $jns    = $this->input->post('jns');
        $nobukti    = $this->input->post('nobukti');
        $jnsspp = $this->input->post('jnsspp');
        $nospd = $this->input->post('nospd');        
        if($nospd==''){
            $tgl = '';
        }else{
            $tgl    = $this->tukd_model->get_nama($nospd,'tgl_spd','trhspd','no_spd');
        }

        $nosp2d = $this->input->post('nosp2d');
        if($nosp2d!=''){
             $spp    = $this->tukd_model->get_nama($nosp2d,'no_spp','trhsp2d','no_sp2d');
        }

        
        $query = $this->tukd_model->cek_sisa_spd($skpd,$jns,$spp,$nobukti,$tgl); 
        $result = array();
        $ii = 0;
        foreach ($query->result_array() as $row){
            $result[] = array(
                        'id' => $ii,        
                        'keluar'  =>  $row['keluar1'],
                        'spd'  =>  $row['spd'],
                        'keluarspp'  =>  $row['keluarspp']                         
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query->free_result(); 
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

    function simpan_hlpj(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');
        
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis,status2,jenis2) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1','0','1')";
        $query1 = $this->db->query($csql);
                        
                if($query1){
                    echo '2';
                }else{
                    echo '0';
                }
            }

    function simpan_lpj(){
      
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $csql     = $this->input->post('sql');            
        
        $sql = "delete from trlpj where no_lpj='$nlpj' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trlpj (no_lpj,no_bukti,tgl_lpj,keterangan,kd_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd)"; 
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

    function update_hlpj_up(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');

        $csql = "delete from trhlpj where no_lpj= '$no_simpan'  and kd_skpd='$kdskpd'";
        $query1 = $this->db->query($csql);
        $csql = "delete from trlpj where no_lpj= '$no_simpan' and kd_skpd='$kdskpd' ";
        $query1 = $this->db->query($csql);
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1')";
        $query1 = $this->db->query($csql);
                        
                if($query1){
                    echo '2';
                }else{
                    echo '0';
                }
        }

    function simpan_lpj_update(){
        $kdskpd  = $this->session->userdata('kdskpd');  
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $csql     = $this->input->post('sql');            
        
        $sql = "delete from trlpj where no_lpj='$no_simpan' AND kd_skpd='$kdskpd'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT INTO trlpj (no_lpj,no_bukti,tgl_lpj,keterangan,kd_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd)"; 
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

    function dsimpan_spp()
    {
           $no_spp      = $this->input->post('cnospp');
           $kd_kegiatan = $this->input->post('ckdgiat');
           $kd_rek5     = $this->input->post('ckdrek');
           $vno_bukti   = $this->input->post('cnobukti');
                        
           $sql = "delete from trdspp where no_spp='$no_spp' and kd_sub_kegiatan='$kd_kegiatan' and kd_rek6='$kd_rek5' and no_bukti='$vno_bukti' ";
           $asg = $this->db->query($sql);

           echo '1';
    }

    function dsimpan_hapus()
    {
           $no_spp  = trim($this->input->post('cno_spp'));
           $lcid    = $this->input->post('lcid');
           $lcid_h  = $this->input->post('lcid_h');
           
           if (  $lcid <> $lcid_h ) {
               $sql     = " delete from trdspp where no_spp='$no_spp' ";
               $asg     = $this->db->query($sql);
               if ($asg > 0){   
                    echo '1';
                    exit();
               } else {
                    echo '0';
                    exit();
               }
          }     
    }



    }
?>