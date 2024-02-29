<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class anggaran_murni extends CI_Controller {

 
    function __construct(){  
        parent::__construct();
         $this->load->model('anggaran_murni_model');
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');

        }    
    } 
    
    function config_anggaran()
    {
        $data['page_title']= 'INPUT KONFIGURASI ANGGARAN';
        $this->template->set('title', 'INPUT KONFIGURASI ANGGARAN');   
        $this->template->load('template','anggaran/config_anggaran',$data) ; 
    }

    function akses_gaji(){
                $sql="  SELECT kd_skpd, nm_skpd,
                        case when status_keg=0 then 
                        '<label class=\"switch\"><input type=\"checkbox\"  onclick=\"javascript:aktif('''+kd_skpd+''');\"><span class=\"slider round\"></span></label>' else
                        '<label class=\"switch\"><input type=\"checkbox\" checked  onclick=\"javascript:aktif('''+kd_skpd+''');\"><span class=\"slider round\"></span></label>' end as status from(
                        select a.kd_skpd, a.nm_skpd, b.status_keg from ms_skpd a left join trskpd b on a.kd_skpd=b.kd_skpd WHERE right(kd_sub_kegiatan,10)='01.2.02.01')xx";
    
    $hasil=array();
     $exe=$this->db->query($sql);
     foreach($exe->result() as $oke){
        $kd_skpd=$oke->kd_skpd;
        $nm_skpd=$oke->nm_skpd;
        $status=$oke->status;
        $hasil[]=array(
            'kd_skpd'=>$kd_skpd,
            'nm_skpd'=>$nm_skpd,
            'status'=>$status
        );
     }
        $data['isi']=$hasil;
        $data['page_title']= 'AKSES INPUT GAJI';
        $this->template->set('title', 'AKSES INPUT GAJI');   
        $this->template->load('template','anggaran/akses_input_gaji',$data) ; 
    }

    function tambah_rka_penyusunan()
    {
        $cRet              = '<h3 style="border-left: 6px solid #2196F3!important;background-color: #ddffff!important; padding: 5px; width: 30%;">Input RKA Murni</h3>' ;
        $data['prev']      = $cRet;
        $data['page_title']= 'Input RKA Murni';
        $this->template->set('title', 'Input RKA Murni');   
        $this->template->load('template','anggaran/rka/tambah_rka_penyusunan',$data) ; 
   }

    function tambah_rka_geser(){
        $cRet              = '<h3 style="border-left: 6px solid #2196F3!important;background-color: #ddffff!important; padding: 5px; width: 30%;">Input RKA PERGESERAN</h3>' ;
        $data['prev']      = $cRet;
        $data['page_title']= 'Input RKA PERGESERAN';
        $this->template->set('title', 'Input RKA PERGESERAN');   
        $this->template->load('template','anggaran/rka/tambah_rka_geser',$data) ; 
   }
   
    /*function skpduser(){
        $lccr = $this->input->post('q');
        $data = $this->master_model->skpduser($lccr);  
        echo json_encode($data);
    }*/

    function skpduser() {
            $lccr = $this->input->post('q');
            $id  = $this->session->userdata('pcUser');
            $skpd     = $this->session->userdata('kdskpd');
            $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%'))  and kd_skpd='$skpd' order by kd_skpd ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],  
                            'nm_skpd' => $resulte['nm_skpd'],
                            'jns' => $resulte['jns']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }

    /*function skpduser_induk(){
        $lccr = $this->input->post('q');
        $data = $this->master_model->skpduser_induk($lccr);  
        echo json_encode($data);
    }*/

    function skpduser_induk() {
        $lccr = $this->input->post('q');
            $id  = $this->session->userdata('pcUser');
            $skpd     = $this->session->userdata('kdskpd');
            $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%'))  and kd_skpd='$skpd' order by kd_skpd ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],  
                            'nm_skpd' => $resulte['nm_skpd'],
                            'jns' => $resulte['jns']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }
    function load_nilai_kua_rancang($cskpd=''){    
        $result=$this->anggaran_murni_model->load_nilai_kua_rancang($cskpd);
        echo $result;
    }

    function config_skpd2(){
        $skpd     =  $this->input->post('kdskpd');
        $result=$this->anggaran_murni_model->config_skpd2($skpd);
        echo json_encode($result);
    }

    function ambil_sdana(){
        $skpd     = $this->session->userdata('kdskpd');
        $kd_skpd  = $this->input->post('kdskpd');
        $lccr  = $this->input->post('q');        
        $result   = $this->anggaran_murni_model->ambil_sdana($skpd,$kd_skpd,$lccr);    
        echo $result;
    } 

    function thapus_rancang($skpd='',$kegiatan='',$rek='') {
        $data=$this->anggaran_murni_model->thapus_rancang($skpd,$kegiatan,$rek);
        $this->select_rka_rancang($kegiatan);
    }

    function select_rka_rancang($kegiatan='',$skpd='') {
        $result=$this->anggaran_murni_model->select_rka_rancang($kegiatan,$skpd);    
        echo json_encode($result);
    }    

    function tsimpan_ar_rancang(){
        
        $kdskpd = $this->input->post('kd_skpd');
        $kdkegi = $this->input->post('kd_kegiatan');
        $kdrek  = $this->input->post('kd_rek5');
        $nilai  = $this->input->post('nilai');
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
    	
    	$data=$this->anggaran_murni_model->tsimpan_ar_rancang($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4);            
          
    	echo json_encode($data);
        
    }

    function tsimpan_ar_geser(){
        
        $kdskpd = $this->input->post('kd_skpd');
        $kdkegi = $this->input->post('kd_kegiatan');
        $kdrek  = $this->input->post('kd_rek5');
        $nilai  = $this->input->post('nilai');
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
        
        $data=$this->anggaran_murni_model->tsimpan_ar_geser($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4);            
          
        echo json_encode($data);
        
    }                                                                               

 function tsimpan_ar_ubah(){
        
        $kdskpd = $this->input->post('kd_skpd');
        $kdkegi = $this->input->post('kd_kegiatan');
        $kdrek  = $this->input->post('kd_rek5');
        $nilai  = $this->input->post('nilai');
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
        
        $data=$this->anggaran_murni_model->tsimpan_ar_ubah($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4);            
          
        echo json_encode($data);
        
    }

    // function tsimpan_ar_ubah(){
        
    //     $kdskpd = $this->input->post('kd_skpd');
    //     $kdkegi = $this->input->post('kd_kegiatan');
    //     $kdrek  = $this->input->post('kd_rek5');
    //     $nilai  = $this->input->post('nilai');
    //     $sdana1 = $this->input->post('dana1');
    //     $sdana2 = $this->input->post('dana2');
    //     $sdana3 = $this->input->post('dana3');
    //     $sdana4 = $this->input->post('dana4');
    //     $ndana1 = $this->input->post('vdana1');
    //     $ndana2 = $this->input->post('vdana2');
    //     $ndana3 = $this->input->post('vdana3');
    //     $ndana4 = $this->input->post('vdana4');
        
    //     $data=$this->anggaran_murni_model->tsimpan_ar_geser($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4);            
          
    //     echo json_encode($data);
        
    // }
    function pgiat_rancang($cskpd='') {

        $lccr = $this->input->post('q');
        $data=$this->anggaran_murni_model->pgiat_rancang($lccr,$cskpd);
        echo $data;
           
    }

    function ambil_rekening5_all_ar() {
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr    = $this->input->post('q');
        $notin   = $this->input->post('reknotin');
        $jnskegi = $this->input->post('jns_kegi');
    	$data    = $this->anggaran_murni_model->ambil_rekening5_all_ar($kd_skpd,$lccr,$notin,$jnskegi);
        echo ($data);
    }

    function cek_transaksi(){
        $skpd     = $this->input->post('skpd');
        $kegiatan = $this->input->post('kegiatan');
        $rek      = $this->input->post('rek6');
        $data     =$this->anggaran_murni_model->cek_transaksi($skpd,$kegiatan,$rek);
        echo $data;
    }

    function load_sum_rek_rancang(){

        $kdskpd = $this->input->post('skpd');
        $sub_kegiatan = $this->input->post('keg');
        $result=$this->anggaran_murni_model->load_sum_rek_rancang($kdskpd,$sub_kegiatan);
        echo json_encode($result);   
    }

    function load_sum_rek_rinci_rancang(){
        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek = $this->input->post('rek');
        $result=$this->anggaran_murni_model->load_sum_rek_rinci_rancang($kdskpd,$kegiatan,$rek);
        echo json_encode($result);   
    }

    function rka_rinci_rancang($skpd='',$kegiatan='',$rekening='',$idlokasi='') {
        $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;        
        $result = $this->anggaran_murni_model->rka_rinci_rancang($skpd,$kegiatan,$rekening,$norka,$idlokasi);
        echo json_encode($result);
    }

    
    function tsimpan_rinci_jk_rancang(){
        $norka     = $this->input->post('no');
        $csql      = $this->input->post('sql');
        $cskpd     = $this->input->post('skpd');
        $kegiatan  = $this->input->post('giat'); 
        $rekening  = $this->input->post('rek');
        $id        = $this->session->userdata('pcNama');
        $sdana1 = $this->input->post('dana1');
        $sdana2 = $this->input->post('dana2');
        $sdana3 = $this->input->post('dana3');
        $sdana4 = $this->input->post('dana4');
        $ndana1 = $this->input->post('vdana1');
        $ndana2 = $this->input->post('vdana2');
        $ndana3 = $this->input->post('vdana3');
        $ndana4 = $this->input->post('vdana4');
        
        $result = $this->anggaran_murni_model->tsimpan_rinci_jk_rancang($norka,$csql,$cskpd,$kegiatan,$rekening,$id,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4);               
        echo $result;
    }

    function rka_rinci($skpd='',$kegiatan='',$rekening='') {     
        $result =$this->anggaran_murni_model->rka_rinci($skpd,$kegiatan,$rekening);
        echo ($result);
    }

    function thapus_rinci_ar_all_rancang(){ 
        $norka = $this->input->post('vnorka');
        $rek   = $this->input->post('rek');
        $skpd  = $this->input->post('skpd');
        $giat  = $this->input->post('giat');
        $data  = $this->anggaran_murni_model->thapus_rinci_ar_all_rancang($norka,$rek,$skpd,$giat);        
        echo $data;
    }

    function simpan_det_keg_rancang(){
        
        $skpd=$this->input->post('skpd');
        $giat=$this->input->post('giat');
        $lokasi=$this->input->post('lokasi');      
        $keterangan=$this->input->post('keterangan');      
        $waktu_giat=$this->input->post('waktu_giat');      
        $waktu_giat2=$this->input->post('waktu_giat2');
        $sub_keluaran=$this->input->post('sub_keluaran');      
        $sas_prog  =$this->input->post('sas_prog'); 
        $cap_prog  =$this->input->post('cap_prog'); 
        $tu_capai  =$this->input->post('tu_capai'); 
        $tk_capai  =$this->input->post('tk_capai'); 
        $tu_capai_p=$this->input->post('tu_capai_p'); 
        $tk_capai_p=$this->input->post('tk_capai_p');
        $tu_mas =$this->input->post('tu_mas'); 
        $tk_mas =$this->input->post('tk_mas'); 
        $tu_mas_p =$this->input->post('tu_mas_p'); 
        $tk_mas_p =$this->input->post('tk_mas_p'); 
        $tu_kel =$this->input->post('tu_kel'); 
        $tk_kel =$this->input->post('tk_kel'); 
        $tu_kel_p =$this->input->post('tu_kel_p'); 
        $tk_kel_p =$this->input->post('tk_kel_p'); 
        $tu_has =$this->input->post('tu_has'); 
        $tk_has =$this->input->post('tk_has'); 
        $tu_has_p =$this->input->post('tu_has_p'); 
        $tk_has_p =$this->input->post('tk_has_p'); 
        $kel_sa =$this->input->post('kel_sa'); 
        $ttd=$this->input->post('ttd');      
        $ang_lalu=$this->input->post('lalu');    

        $data=$this->anggaran_murni_model->simpan_det_keg_rancang($ttd, $ang_lalu, $skpd,$giat,$lokasi,$keterangan,$waktu_giat,$waktu_giat2,$sub_keluaran,$sas_prog,  
        $cap_prog,$tu_capai,$tk_capai,$tu_capai_p,$tk_capai_p,$tu_mas,$tk_mas,$tu_mas_p, 
        $tk_mas_p,$tu_kel,$tk_kel,$tu_kel_p,$tk_kel_p,$tu_has,$tk_has,$tu_has_p,$tk_has_p,$kel_sa,$ttd ,$ang_lalu,$ttd); 

        echo $data;
    }

    function load_det_keg_rancang(){
        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $result=$this->anggaran_murni_model->load_det_keg_rancang($kdskpd,$kegiatan);
        echo json_encode($result);   
    }

    function cek_anggaran(){
        $data['page_title']= 'Cek Anggaran';
        $this->template->set('title', 'Cek Anggaran');   
        $this->template->load('template','anggaran/rka/cek_anggaran',$data) ; 
    }

    function pengesahan_dpa()
    {
        $data['page_title']= 'Pengesahan DPA & DPPA';
        $this->template->set('title', 'Pengesahan DPA & DPPA');   
        $this->template->load('template','anggaran/rka/pengesahan_dpa',$data) ; 
    }

   function load_pengesahan_dpa(){
        $kriteria = $this->input->post('cari');
        echo $this->anggaran_murni_model->load_pengesahan_dpa($kriteria);   
    }

    function simpan_pengesahan(){
        $kdskpd = $this->input->post('kdskpd');
        $sdpa = $this->input->post('stdpa');
        $srka = $this->input->post('strka');
        $sdppa = $this->input->post('stdppa');
        $nodpa = $this->input->post('no');
        $tanggal1 = $this->input->post('tgl');
        $nodppa = $this->input->post('no2');
        $tanggal2 = $this->input->post('tgl2');
        $sdpasempurna = $this->input->post('stsempurna');
        $nosempurna = $this->input->post('no3');
        $tanggal3 = $this->input->post('tgl3');
        $sdpasempurnax = $this->input->post('stsempurnax');
        $nosempurnax = $this->input->post('no3x');
        $tanggal3x = $this->input->post('tgl3x');
        $norka = $this->input->post('no4');
        $tanggal4 = $this->input->post('tgl4');
        $last_update =  date('Y-m-d H:i:s');
        echo $this->anggaran_murni_model->simpan_pengesahan($kdskpd,$sdpa,$srka,$sdppa,$nodpa,$tanggal1,$nodppa,$tanggal2,$sdpasempurna,$nosempurna,$tanggal3,$sdpasempurnax,$nosempurnax,$tanggal3x,$norka,$tanggal4,$last_update);
    }

  function simpan_rincian_dpo(){
        $id                 = $this->session->userdata('pcNama');
        $header             = $this->input->post('header');
        $kd_barang             = $this->input->post('kd_barang');
        $kode               = $this->input->post('kode');
        $kd_kegiatan        = $this->input->post('kd_kegiatan');      
        $kd_rek5            = $this->input->post('kd_rek');      
        $no_po              = $this->input->post('no_po');
        $cek              = $this->input->post('no_po');       
        $no_trdrka          = $this->input->post('no_trdrka');      
        $uraian             = $this->input->post('uraian');      
        $volume1            = $this->input->post('volume1');      
        $volume1_sempurna1  = $this->input->post('volume1_sempurna1');      
        $volume_ubah1       = $this->input->post('volume_ubah1');      
        $satuan1            = $this->input->post('satuan1');      
        $satuan_sempurna1   = $this->input->post('satuan_sempurna1');      
        $satuan_ubah1       = $this->input->post('satuan_ubah1');      
        $harga1             = $this->input->post('harga1');
        $harga_sempurna1    = $this->input->post('harga_sempurna1'); 
        $harga_ubah1        = $this->input->post('harga_ubah1');      
        $total              = $this->input->post('total');      
        $total_sempurna     = $this->input->post('total_sempurna');
        $total_ubah         = $this->input->post('total_ubah'); 
        $kode_unik          = $this->input->post('unik');
        $sdana1             = $this->input->post('sdana1');
        $sdana2             = $this->input->post('sdana2'); 
        $sdana3             = $this->input->post('sdana3');
        $nsumber2           = $this->input->post('nsumber2'); 
        $nsumber3           = $this->input->post('nsumber3');     
        $kd_lokasi          = $this->input->post('kd_lokasi');

        /*untuk menyimpan nilai no_po*/
        if($no_po==''){
            $unik=$this->db->query("SELECT isnull(max(cast(no_po as int)),0)+10 as nopo from trdpo where no_trdrka='$no_trdrka'")->row();            
            $no_po=$unik->nopo;
        }else{
            $no_po=$no_po-1;  /*nomer no_po ketika di sisipkan*/        
        }


        $unik=$this->db->query("SELECT isnull(max(cast(unik as int)),0)+1 as oke from trdpo")->row();
        $kd_sisb=$unik->oke; /*kode unik di trdpo*/

        $sql ="INSERT into trdpo(header,kode,kd_barang,kd_rek6,no_po,no_trdrka,uraian,volume1,volume_sempurna1,volume_ubah1,satuan1,satuan_sempurna1,satuan_ubah1,harga1,harga_sempurna1,harga_ubah1,total,total_sempurna,total_ubah,unik,kd_lokasi)
                        values ('$header','$kode','$kd_barang','$kd_rek5','$no_po','$no_trdrka','$uraian','$volume1','$volume1_sempurna1','$volume_ubah1','$satuan1','$satuan_sempurna1','$satuan_ubah1','$harga1','$harga_sempurna1','$harga_ubah1','$total','$total_sempurna','$total_ubah','$kd_sisb','$kd_lokasi')"; 
        $this->db->query($sql);

        if($cek!=''){ /*untuk penyisipan*/ 
            $update_nopo="UPDATE a SET a.no_po=b.unix
                        from trdpo a inner join 
                        (SELECT ROW_NUMBER() OVER(ORDER BY cast(no_po as int))*10 unix, no_po from trdpo where no_trdrka='$no_trdrka') b 
                        on a.no_po=b.no_po where no_trdrka='$no_trdrka'";
            $this->db->query($update_nopo);
        }


        $query1 = $this->db->query("UPDATE trdrka set
            sumber              = '$sdana1',
            sumber2             = '$sdana2',
            sumber3             = '$sdana3',
            sumber1_su          = '$sdana1',
            sumber2_su          = '$sdana2',
            sumber3_su          = '$sdana3',
            sumber1_ubah        = '$sdana1',
            sumber2_ubah        = '$sdana2',
            sumber3_ubah        = '$sdana3',
            nilai_sumber        = (select abs(sum(total)-$nsumber2-$nsumber3) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sumber2       = $nsumber2,
            nilai_sumber3       = $nsumber3,
            nsumber1_su         = (select abs(sum(total_sempurna)-$nsumber2-$nsumber3) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber2_su         = $nsumber2,
            nsumber3_su         = $nsumber3,
            nsumber1_ubah       = (select abs(sum(total_ubah)-$nsumber2-$nsumber3) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber2_ubah       = $nsumber2,
            nsumber3_ubah       = $nsumber3,
            nilai               = (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sempurna      = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah          = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            username            ='$id',last_update=getdate() where no_trdrka='$no_trdrka' ");          

        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)");  

        echo $query1 = $this->db->query("UPDATE trskpd set 
            total           = (select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            total_sempurna  = (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            total_ubah      = (select sum(nilai_ubah) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            username1       = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)"); 

    }


  function simpan_rincian_dpo_geser(){
        $id                 = $this->session->userdata('pcNama');
        $header             = $this->input->post('header');
        $kd_barang          = $this->input->post('kd_barang');
        $kode               = $this->input->post('kode');
        $kd_kegiatan        = $this->input->post('kd_kegiatan');      
        $kd_rek5            = $this->input->post('kd_rek');      
        $no_po              = $this->input->post('no_po'); /*spesifikasi*/
        $skpd               = $this->input->post('skpd');
        $cek                = $this->input->post('no_po');       
        $no_trdrka          = $this->input->post('no_trdrka');      
        $uraian             = $this->input->post('uraian');      
        $volume1            = $this->input->post('volume1');      
        $volume1_sempurna1  = $this->input->post('volume1_sempurna1');      
        $volume_ubah1       = $this->input->post('volume_ubah1');      
        $satuan1            = $this->input->post('satuan1');      
        $satuan_sempurna1   = $this->input->post('satuan_sempurna1');      
        $satuan_ubah1       = $this->input->post('satuan_ubah1');      
        $harga1             = $this->input->post('harga1');
        $harga_sempurna1    = $this->input->post('harga_sempurna1'); 
        $harga_ubah1        = $this->input->post('harga_ubah1');      
        $total              = $this->input->post('total');      
        $total_sempurna     = $this->input->post('total_sempurna');
        $total_ubah         = $this->input->post('total_ubah'); 
        $kode_unik          = $this->input->post('unik');
        $kd_barang          = $this->input->post('kd_barang');
        $id_standar_harga   = $this->input->post('id_standar_harga'); 
        $nsumber2           = $this->input->post('nsumber2'); 
        $nsumber3           = $this->input->post('nsumber3');     
        $kd_lokasi          = $this->input->post('kd_lokasi');

        if($kode_unik==''){
            $max =$this->db->query("SELECT max(no_po) ful from trdpo where no_trdrka='$no_trdrka'")->row()->ful;
            $kode_unik=$max+2;
        }else{
            $kode_unik=$kode_unik-1;
        }
            $this->db->query("INSERT into trdpo (header, ket_bl_teks, spesifikasi_sempurna, spesifikasi_ubah, no_trdrka, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian, satuan_sempurna1, satuan_ubah, volume_sempurna1, volume_ubah, harga_sempurna1,harga_ubah, total_sempurna, total_ubah, kd_barang, id_standar_harga,no_po) values 
            ('$header','$kode','$no_po','$no_po','$no_trdrka','$skpd','$kd_kegiatan','$kd_rek5','$uraian','$satuan_sempurna1','$satuan_sempurna1','$volume1_sempurna1','$volume1_sempurna1','$harga_sempurna1','$harga_sempurna1','$total_sempurna','$total_sempurna','$kd_barang','$id_standar_harga','$kode_unik')");

        $this->db->query("UPDATE a SET
                            a.no_po=b.urutan
                            from trdpo a inner join (
                            SELECT 
                               ROW_NUMBER() OVER (
                                ORDER BY no_po
                               )*2 urutan, no_po,
                                    no_trdrka
                            FROM 
                               trdpo WHERE no_trdrka='$no_trdrka'
                            ) b on a.no_po=b.no_po and a.no_trdrka=b.no_trdrka
                            WHERE a.no_trdrka='$no_trdrka'");

        $query1 = $this->db->query("UPDATE trdrka set
            nsumber1_su         = (select abs(sum(total_sempurna)) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nsumber1_ubah       = (select abs(sum(total_ubah)) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nilai_sempurna      = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah          = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            username            ='$id',last_update=getdate() where no_trdrka='$no_trdrka' "); 
                     
         $this->db->query("UPDATE a SET
                        a.kd_skpd=b.kd_subskpd
                        from trdpo a inner join (
                        select * from kegiatan_bp where kd_skpd='$skpd') b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                        where a.kd_skpd='$skpd'");

        echo $query1 = $this->db->query("UPDATE trskpd set 
            total_sempurna  = (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            total_ubah      = (select sum(nilai_ubah) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)),
            username1       = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22)"); 

    }

   function update_rincian_dpo(){

        $id             = $this->session->userdata('pcNama');
        $lokasi         = $this->input->post('lokasi');
        $kdbarang_edit  = $this->input->post('kdbarang_edit');
        $header         = $this->input->post('header');
        $kode           = $this->input->post('kode');
        $kd_kegiatan    = $this->input->post('kd_kegiatan');      
        $kd_rek5        = $this->input->post('kd_rek5');      
        $no_po          = $this->input->post('no_po');      
        $no_trdrka      = $this->input->post('no_trdrka');      
        $uraian         = $this->input->post('uraian');        
        $volume         = $this->input->post('volume1');       
        $satuan         = $this->input->post('satuan1');      
        $harga          = $this->input->post('harga1');      
        $total          = $this->input->post('total'); 
        $unik           = $this->input->post('unik');
        $sdana1           = $this->input->post('sdana1');  

        $sql="UPDATE trdpo set
            kd_lokasi       ='$lokasi',
            kd_barang       ='$kdbarang_edit',
            header          ='$header',
            kode            ='$kode',
            uraian          ='$uraian',
            volume1         ='$volume',
            volume_sempurna1='$volume',
            volume_ubah1    ='$volume',
            satuan1         ='$satuan',
            satuan_sempurna1='$satuan',
            satuan_ubah1    ='$satuan',
            harga1          ='$harga',
            harga_sempurna1 ='$harga',
            harga_ubah1     ='$harga',
            total           ='$total',
            total_sempurna  ='$total',
            total_ubah      ='$total'
            where unik='$unik' and no_trdrka='$no_trdrka'";
        
         $this->db->query($sql);
        $query1 = $this->db->query("
            UPDATE trdrka set
            nilai          = (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sempurna = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah     = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sumber   = (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_su    = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_ubah  = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            username       = '$id',last_update=getdate() where no_trdrka='$no_trdrka' ");  

        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)");  
           
            $query1 = $this->db->query("UPDATE trskpd set 
            total         = (select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_sempurna= (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_ubah    = (select sum(nilai_ubah) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            username1     = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ");    

    }
 //apbd perubahan
    function simpan_rincian_dpo_ubah(){
        $id                 = $this->session->userdata('pcNama');
        $header             = $this->input->post('header');
        $nama_header        = $this->input->post('kode');
        $kd_kegiatan        = $this->input->post('kd_kegiatan');      
        $kd_rek5            = $this->input->post('kd_rek');      
        $spesifikasi        = $this->input->post('spesifikasi'); 
        $skpd               = $this->input->post('skpd');  
        $no_trdrka          = $this->input->post('no_trdrka');      
        $uraian             = $this->input->post('uraian');

        $volume1            = $this->input->post('volume1');
        $volume2            = $this->input->post('volume2');  
        $volume3            = $this->input->post('volume3');  
        $volume4            = $this->input->post('volume4');

        $koefisien            = $this->input->post('koefisien');   
   
        $satuan1            = $this->input->post('satuan1');
        $satuan2            = $this->input->post('satuan2'); 
        $satuan3            = $this->input->post('satuan3'); 
        $satuan4            = $this->input->post('satuan4'); 

        $pajak            = $this->input->post('pajak'); 
   

       // $harga_ubah1        =$this->input->post('harga_ubah')
        $harga             = $this->input->post('harga'); 
        $total              = $this->input->post('total');      

        $kode_unik          = $this->input->post('unik');
        $kd_barang          = $this->input->post('kd_barang');
        $id_standar_harga   = $this->input->post('id_standar_harga');     


        if($kode_unik==''){
            $max =$this->db->query("SELECT max(no_po) ful from trdpo where no_trdrka='$no_trdrka'")->row()->ful;
            $kode_unik=$max+2;
        }else{
            $kode_unik=$kode_unik-1;
        }
            $this->db->query("INSERT into trdpo 
            (
                header,                            
             ket_bl_teks,                                   
             spesifikasi_ubah,                  
             no_trdrka,                         
             kd_skpd,                           
             kd_sub_kegiatan,                   
             kd_rek6,                           
             uraian,                                          
             satuan_ubah1,                      
             satuan_ubah2,                      
             satuan_ubah3,                      
             satuan_ubah4,                                     
             volume_ubah1,                      
             volume_ubah2,                      
             volume_ubah3,                      
             volume_ubah4,
             harga,
             harga_sempurna1,                                   
             harga_ubah,                                          
             total_ubah,                        
             kd_barang,                         
             id_standar_harga,                  
             no_po,                                             
             pajak_ubah,
             koefisien_ubah                      
             ) values 
            (
            '$header',                         
             '$nama_header'  ,                                   
             '$spesifikasi',                     
             '$no_trdrka',                      
             '$skpd',                           
             '$kd_kegiatan',                    
             '$kd_rek5',                        
             '$uraian',                                                
             '$satuan1',                         
             '$satuan2',                        
             '$satuan3',                        
             '$satuan4',                                                
             '$volume1',                         
             '$volume2',                        
             '$volume3',                        
             '$volume4',
             '$harga',
             '$harga',                                                 
             '$harga',                                                   
             '$total',                           
             '$kd_barang',                      
             '$id_standar_harga',               
             '$kode_unik',                                                  
             '$pajak',
             '$koefisien'                           
         )");

        $this->db->query("UPDATE a SET
                            a.no_po=b.urutan
                            from trdpo a inner join (

                            SELECT 
                               ROW_NUMBER() OVER (
                                ORDER BY no_po
                               )*2 urutan, no_po,
                                    no_trdrka
                            FROM 
                               trdpo WHERE no_trdrka='$no_trdrka'
                            ) b on a.no_po=b.no_po and a.no_trdrka=b.no_trdrka
                            WHERE a.no_trdrka='$no_trdrka'");

        $query1 = $this->db->query("UPDATE trdrka set
            nsumber1_ubah       = (select abs(sum(total_ubah)) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nilai_sempurna      = (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah          = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            username            ='$id',last_update=getdate() where no_trdrka='$no_trdrka' "); 
                     
        // echo $this->db->query("UPDATE a SET
        //                 a.kd_skpd=b.kd_subskpd
        //                 from trdpo a inner join (
        //                 select * from kegiatan_bp where kd_skpd='$skpd') b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
        //                 where a.kd_skpd='$skpd'");


    }
//stop
   function update_rincian_dpo_geser(){

        $id             = $this->session->userdata('pcNama');
        $lokasi         = $this->input->post('lokasi');
        $kdbarang_edit  = $this->input->post('kdbarang_edit');
        $header         = $this->input->post('header');
        $kode           = $this->input->post('kode');
        $kd_kegiatan    = $this->input->post('kd_kegiatan');      
        $kd_rek5        = $this->input->post('kd_rek5');      
        $no_po          = $this->input->post('no_po');      
        $no_trdrka      = $this->input->post('no_trdrka');      
        $uraian         = $this->input->post('uraian');        
        $volume         = $this->input->post('volume1');       
        $satuan         = $this->input->post('satuan1');      
        $harga          = $this->input->post('harga1');      
        $total          = $this->input->post('total'); 
        $unik           = $this->input->post('unik');
        $sdana1           = $this->input->post('sdana1');  

        $sql="UPDATE trdpo set
            header          ='$header',
            kode            ='$kode',
            uraian          ='$uraian',
            volume_sempurna1='$volume',
            volume_ubah1    ='$volume',
            satuan_sempurna1='$satuan',
            satuan_ubah     ='$satuan',
            harga_sempurna1 ='$harga',
            total_sempurna  ='$total',
            total_ubah      ='$total'
            where id='$unik'";
        
         $this->db->query($sql);
        $query1 = $this->db->query("
            UPDATE trdrka set
            nilai_sempurna = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah     = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_su    = (select abs(sum(total_sempurna)) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nsumber1_ubah  = (select abs(sum(total_sempurna)) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            username       = '$id',last_update=getdate() where no_trdrka='$no_trdrka' ");  

        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(total_sempurna) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(total_sempurna) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)");  
           
            $query1 = $this->db->query("UPDATE trskpd set 
            total_sempurna= (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_ubah    = (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            username1     = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ");    

    }
 function hapus_rincian_dpo(){
        $id         = $this->session->userdata('pcNama');        
        $kode_unik  = $this->input->post('kode_unik');
        $skpd       = $this->input->post('skpd');
        $kd_kegiatan= $this->input->post('giat');
        $norka      = $this->input->post('norka');

        $cek=$this->db->query("SELECT count(no_trdrka) cek from trdpo where id='$kode_unik' AND kode_sbl<>''")->row()->cek;
        if($cek>0){
            echo "7";
            die();
        }
        $sql="DELETE trdpo where id='$kode_unik'";
        $this->db->query($sql);
        $hapuskosong=$this->db->query("SELECT count(no_trdrka) oke from trdpo WHERE no_trdrka='$norka'")->row();
        if($hapuskosong->oke==0){
            $this->db->query("DELETE trdskpd_ro WHERE kd_skpd+'.'+kd_kegiatan+'.'+kd_rek6='$norka'");
        }

        $query1 = $this->db->query("
            UPDATE trdrka set
            nilai           = (select isnull(sum(total),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah      = (select isnull(sum(total_ubah),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sempurna  = (select isnull(sum(total_sempurna1),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sumber    = (select isnull(sum(total),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_ubah   = (select isnull(sum(total_ubah),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nsumber1_su     = (select isnull(sum(total_sempurna1),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            username        ='$id',last_update=getdate() where no_trdrka='$norka' ");  

        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)");  
    
        echo $query1 = $this->db->query("
            UPDATE trskpd set
            total           = (select isnull(sum(nilai),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)), 
            total_ubah      = (select isnull(sum(nilai_ubah),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)),
            total_sempurna  = (select isnull(sum(nilai_sempurna),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)),
            username       = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22) ");    

    }

   function hapus_rincian_dpo_lama(){
        $id         = $this->session->userdata('pcNama');        
        $kode_unik  = $this->input->post('kode_unik');
        $skpd       = $this->input->post('skpd');
        $kd_kegiatan= $this->input->post('giat');
        $norka      = $this->input->post('norka');

        $cek=$this->db->query("SELECT count(no_trdrka) cek from trdpo where id='$kode_unik' AND kode_sbl<>''")->row()->cek;
        if($cek>0){
            echo "7";
            die();
        }
        $sql="DELETE trdpo where id='$kode_unik'";
        $this->db->query($sql);
        $hapuskosong=$this->db->query("SELECT count(no_trdrka) oke from trdpo WHERE no_trdrka='$norka'")->row();
        if($hapuskosong->oke==0){
            $this->db->query("DELETE trdskpd_ro WHERE kd_skpd+'.'+kd_kegiatan+'.'+kd_rek6='$norka'");
        }

        $query1 = $this->db->query("
            UPDATE trdrka set
            nilai           = (select isnull(sum(total),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah      = (select isnull(sum(total_ubah),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sempurna  = (select isnull(sum(total_sempurna),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sumber    = (select isnull(sum(total),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_ubah   = (select isnull(sum(total_ubah),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nsumber1_su     = (select isnull(sum(total_sempurna),0) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            username        ='$id',last_update=getdate() where no_trdrka='$norka' ");  

        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$norka',22)");  
    
        echo $query1 = $this->db->query("
            UPDATE trskpd set
            total           = (select isnull(sum(nilai),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)), 
            total_ubah      = (select isnull(sum(nilai_ubah),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)),
            total_sempurna  = (select isnull(sum(nilai_sempurna),0) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22)),
            username1       = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$skpd',22) ");    

    }

    function save_lokasi(){
        $lokasi  = $this->input->post('lokasi');
        $skpd  = $this->input->post('skpd');
        $subkeluar  = $this->input->post('subkeluar');
        if($lokasi==''){
            echo 0;
            die();
        }
        $cek="SELECT count(*) dd from ms_lokasi where nm_lokasi='$lokasi' and subkeluar='$subkeluar' and kd_skpd='$skpd'";
        $exe=$this->db->query($cek)->row();
        if($exe->dd<1){
            $gas=$this->db->query("INSERT INTO ms_lokasi (nm_lokasi,kd_skpd,subkeluar) values ('$lokasi','$skpd','$subkeluar')");
            echo 1;
        }else{
            echo 2;
        }

    }

    function edit_lokasi(){
        $kdlokasi  = $this->input->post('kdlokasi');
        $lokasi  = $this->input->post('lokasi');
        $subkeluar  = $this->input->post('subkeluar');
        $skpd  = $this->input->post('skpd');
        if($lokasi==''){
            echo 0;
            die();
        }
        $cek="SELECT count(*) dd from ms_lokasi where nm_lokasi='$lokasi' and subkeluar='$subkeluar' and kd_skpd='$skpd'";
        $exe=$this->db->query($cek)->row();
        if($exe->dd<1){
            $gas=$this->db->query("UPDATE ms_lokasi set nm_lokasi='$lokasi',subkeluar='$subkeluar' where kd_lokasi='$kdlokasi'");
            echo 1;
        }else{
            echo 2;
        }

    }

    function hapus_lokasi(){
        $kdlokasi  = $this->input->post('kdlokasi');
        $cek="SELECT count(kd_lokasi) dd from trdpo where kd_lokasi='$kdlokasi'";
        $exe=$this->db->query($cek)->row();
        if($exe->dd==0){
            $gas=$this->db->query("DELETE ms_lokasi where kd_lokasi='$kdlokasi'");
            echo 1;
        }else{
            echo 2;
        }

    }

    function kunci_gaji(){
        $skpd  = $this->input->post('skpd');
        $que="SELECT status_keg stat from trskpd where kd_skpd='$skpd' and right(kd_sub_kegiatan,10)='01.2.02.01'";
        $cek=$this->db->query($que)->row();
        if($cek->stat==1){
            echo $this->db->query("UPDATE trskpd set status_keg='0' where kd_skpd='$skpd' and right(kd_sub_kegiatan,10)='01.2.02.01'");
        }else{
            echo $this->db->query("UPDATE trskpd set status_keg='1' where kd_skpd='$skpd' and right(kd_sub_kegiatan,10)='01.2.02.01'");
        }
    }
    function update_rincian_dpo_sementara_ubah(){
        $id             = $this->session->userdata('pcNama');
        $header         = $this->input->post('header');
        $kode           = $this->input->post('kode');     
        $uraian         = $this->input->post('uraian');

        $volume1         = $this->input->post('volume1');
        $volume2         = $this->input->post('volume2'); 
        $volume3         = $this->input->post('volume3'); 
        $volume4         = $this->input->post('volume4'); 

        $satuan1         = $this->input->post('satuan1');
        $satuan2         = $this->input->post('satuan2');  
        $satuan3         = $this->input->post('satuan3');  
        $satuan4         = $this->input->post('satuan4');  

        $pajak          = $this->input->post('pajak');
        $koefisien          = $this->input->post('koefisien');

        $harga          = $this->input->post('harga');
        $id_standar_harga= $this->input->post('id_standar_harga_edit'); 
        $kd_barang       = $this->input->post('kd_barang_edit');       
        $total          = $this->input->post('total'); 
        $unik           = $this->input->post('unik');
        $kd_kegiatan    = $this->input->post('kd_kegiatan');      
        $kd_rek5        = $this->input->post('kd_rek5'); 
        $no_trdrka      = $this->input->post('no_trdrka');
        $spesifikasi      = $this->input->post('spesifikasi');    


        $sql="UPDATE trdpo set
            header          ='$header',
            ket_bl_teks     ='$kode',
            uraian          ='$uraian',
            kd_barang       ='$kd_barang',
            id_standar_harga='$id_standar_harga',

            satuan_ubah1='$satuan1',
            satuan_ubah2='$satuan2',
            satuan_ubah3='$satuan3',
            satuan_ubah4='$satuan4',

            volume_ubah1='$volume1',
            volume_ubah2='$volume2',
            volume_ubah3='$volume3',
            volume_ubah4='$volume4',

            pajak_ubah='$pajak',
            harga_ubah      ='$harga',

            koefisien_ubah     ='$koefisien',

            total_ubah      ='$total',

            spesifikasi_ubah      ='$spesifikasi'
            where id='$unik'";
        
         $this->db->query($sql);
        echo $query1 = $this->db->query("
            UPDATE trdrka set
            nilai_ubah     = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_ubah  = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            username       = '$id',last_update=getdate() where no_trdrka='$no_trdrka' ");  

    }
    
    function update_rincian_dpo_sementara(){
        $id             = $this->session->userdata('pcNama');
        $header         = $this->input->post('header');
        $kode           = $this->input->post('kode');     
        $uraian         = $this->input->post('uraian');        
        $volume         = $this->input->post('volume1');       
        $satuan         = $this->input->post('satuan1');      
        $harga          = $this->input->post('harga1');      
        $total          = $this->input->post('total'); 
        $unik           = $this->input->post('unik');
        $kd_kegiatan    = $this->input->post('kd_kegiatan');      
        $kd_rek5        = $this->input->post('kd_rek5'); 
        $no_trdrka      = $this->input->post('no_trdrka');   
                
        $sql="UPDATE trdpo set
            header          ='$header',
            kode            ='$kode',
            uraian          ='$uraian',
            volume1         ='$volume',
            volume_sempurna1='$volume',
            satuan1         ='$satuan',
            satuan_sempurna1='$satuan',
            harga          ='$harga',
            harga_sempurna1 ='$harga',
            harga_ubah     ='$harga',
            total           ='$total',
            total_sempurna  ='$total',
            total_ubah      ='$total'
            where id='$unik'";
        
         $this->db->query($sql);
        $query1 = $this->db->query("
            UPDATE trdrka set
            nilai          = (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sempurna = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah     = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_sumber   = (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_su    = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_ubah  = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            username       = '$id',last_update=getdate() where no_trdrka='$no_trdrka' ");  

        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas          =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)");  
           
            $query1 = $this->db->query("UPDATE trskpd set 
            total         = (select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_sempurna= (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_ubah    = (select sum(nilai_ubah) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            username1     = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ");    

    }

    function update_rincian_dpo_sementara_geser(){
        $id             = $this->session->userdata('pcNama');
        $header         = $this->input->post('header');
        $kode           = $this->input->post('kode');     
        $uraian         = $this->input->post('uraian');        
        $volume         = $this->input->post('volume1');       
        $satuan         = $this->input->post('satuan1');      
        $harga          = $this->input->post('harga1');
        $id_standar_harga= $this->input->post('id_standar_harga_edit'); 
        $kd_barang       = $this->input->post('kd_barang_edit');       
        $total          = $this->input->post('total'); 
        $unik           = $this->input->post('unik');
        $kd_kegiatan    = $this->input->post('kd_kegiatan');      
        $kd_rek5        = $this->input->post('kd_rek5'); 
        $no_trdrka      = $this->input->post('no_trdrka');
        $spesifikasi      = $this->input->post('spesifikasi');    
                
        $sql="UPDATE trdpo set
            header          ='$header',
            ket_bl_teks     ='$kode',
            uraian          ='$uraian',
            kd_barang       ='$kd_barang',
            id_standar_harga='$id_standar_harga',
            satuan_sempurna1='$satuan',
            satuan_ubah     ='$satuan',
            volume_sempurna1='$volume',
            volume_ubah     ='$volume',
            harga_sempurna1 ='$harga',
            harga_ubah      ='$harga',
            total_sempurna  ='$total',
            total_ubah      ='$total',
            spesifikasi_ubah      ='$spesifikasi',
            spesifikasi_sempurna      ='$spesifikasi'
            where id='$unik'";
        
         $this->db->query($sql);
        $query1 = $this->db->query("
            UPDATE trdrka set
            nilai_sempurna = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nilai_ubah     = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
            nsumber1_su    = (select sum(total_sempurna) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            nsumber1_ubah  = (select sum(total_ubah) as nl from trdpo where no_trdrka=trdrka.no_trdrka)-nsumber2_su-nsumber3_su,
            username       = '$id',last_update=getdate() where no_trdrka='$no_trdrka' ");  

        $query1 = $this->db->query("UPDATE trskpd set 
            tk_mas_sempurna =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)),
            tk_mas_ubah     =(select 'Rp. '+(SELECT CAST(CONVERT(VARCHAR, CAST(sum(nilai) AS MONEY), 1) AS VARCHAR)) as jum from trdrka where left(kd_sub_kegiatan,12)=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22))
            where kd_kegiatan=left('$kd_kegiatan',12) and left(kd_skpd,22)=left('$no_trdrka',22)");  
           
            $query1 = $this->db->query("UPDATE trskpd set 
            total_sempurna= (select sum(nilai_sempurna) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            total_ubah    = (select sum(nilai_ubah) as jum from trdrka where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ),
            username1     = '$id',last_update=getdate() where kd_sub_kegiatan='$kd_kegiatan' and left(kd_skpd,22)=left('$no_trdrka',22) ");    

    }


    function get_realisasi_keg($cskpd=''){
        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');

        
        $query1 = $this->db->query("SELECT sum(isnull(z.lalu,0)) total
 from(
SELECT a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6,
            (SELECT SUM(nilai) FROM 
                        (
                        SELECT
                            SUM (isnull(c.nilai,0)) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek6 = a.kd_rek6   AND d.jns_spp='1'                   
                        UNION ALL
                        SELECT
                            SUM (isnull(c.nilai,0)) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND left(d.kd_skpd,17) = left(a.kd_skpd,17)
                        AND c.kd_rek6 = a.kd_rek6
                        AND c.no_voucher <> 'x'
                        AND d.jns_spp='1' AND d.status_validasi='0'
                        UNION ALL
                        SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND left(x.kd_skpd,7) = left(a.kd_skpd,7)
                        AND x.kd_rek6 = a.kd_rek6
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=a.kd_skpd )
                        )r) AS lalu                    
                        FROM trdrka a )z
                                                where z.kd_sub_kegiatan ='$kegiatan'
                        group by z.kd_sub_kegiatan");
        
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                            'id' => $ii,        
                            'nrealisasi' => number_format($resulte['total'],2,'.',',')                                              
                        );
                        $ii++;
        }
       
           echo json_encode($result);
           $query1->free_result();  
    }


function get_realisasi_rek($cskpd=''){
        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek5 = $this->input->post('rek5');

        
        $query1 = $this->db->query("SELECT sum(isnull(z.lalu,0)) total
 from(
SELECT a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.kd_skpd,
            (SELECT SUM(nilai) FROM 
                        (
                        SELECT
                            SUM (isnull(c.nilai,0)) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND d.kd_skpd = a.kd_skpd
                        AND c.kd_rek6 = a.kd_rek6   AND d.jns_spp='1'                   
                        UNION ALL
                        SELECT
                            SUM (isnull(c.nilai,0)) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND d.kd_skpd = a.kd_skpd
                        AND c.kd_rek6 = a.kd_rek6
                        AND c.no_voucher <> 'x'
                        AND d.jns_spp='1' AND d.status_validasi='0'
                        UNION ALL
                        SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND x.kd_skpd = a.kd_skpd
                        AND x.kd_rek6 = a.kd_rek6
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL				
                        select sum(isnull(x.rupiah * -1, 0)) as nilai from trdkasin_pkd x
                        inner join trhkasin_pkd y on x.kd_skpd = y.kd_skpd and x.kd_sub_kegiatan = y.kd_sub_kegiatan and x.no_sts = y.no_sts 
                        where x.kd_skpd = a.kd_skpd 
                        and x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        and x.kd_rek6 = a.kd_rek6
                        and y.jns_trans = '5'
                        UNION ALL
                        SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd=a.kd_skpd )
                        )r) AS lalu                    
                        FROM trdrka a )z
                        where z.kd_sub_kegiatan ='$kegiatan'
                        and z.kd_skpd = '$kdskpd'
                        and z.kd_rek6 ='$rek5'
                        group by z.kd_sub_kegiatan,z.kd_rek6 ");
        
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                            'id' => $ii,        
                            'nrealisasi' => number_format($resulte['total'],2,'.',',')                                              
                        );
                        $ii++;
        }
       
           echo json_encode($result);
           $query1->free_result();  
    }


    function get_realisasi($cskpd=''){

        $query1 = $this->db->query("SELECT total=isnull(spp,0)+isnull(transaksi,0) from ms_skpd a left join
                                    (           
                                        select b.kd_skpd,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where b.jns_spp not in ('1','2') and b.kd_skpd='$cskpd' and (sp2d_batal<>'1' or sp2d_batal is null ) group by b.kd_skpd
                                    ) as d on a.kd_skpd=d.kd_skpd
                                    left join 
                                    (
                                        select f.kd_skpd,sum(f.nilai) [transaksi] from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$cskpd' and e.jns_spp ='1' group by f.kd_skpd
                                        ) g on a.kd_skpd=g.kd_skpd
                                    where a.kd_skpd='$cskpd'"); 
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                            'id' => $ii,        
                            'nrealisasi' => number_format($resulte['total'],2,'.',',')                                              
                        );
                        $ii++;
        }
       
           echo json_encode($result);
           $query1->free_result();  
    }

    function subheader(){
        $no_trdrka=$this->input->post('no_trdrka');
        $sql=$this->db->query("SELECT * from trdpo where no_trdrka='$no_trdrka' and header='1'");
        $ii = 0;
        $result = array();
        foreach($sql->result() as $ok){
                 $result[] = array(
                            'id' => $ii,        
                            'uraian' => $ok->uraian                                             
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

    function cetak_Semua($skpd){
        $sql=$this->db->query("SELECT kd_kegiatan from trskpd where left(kd_skpd,17)=left('$skpd',17) and jns_kegiatan='5' group by left(kd_skpd,17), kd_kegiatan");
        $ii = 0;
        $result = array();
        foreach($sql->result() as $ok){
                 $result[] = array(
                            'id' => $ii,        
                            'kd_kegiatan' => $ok->kd_kegiatan                                             
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

    function standarhargasipd(){
        $cari = $this->input->post('q');
        $sql=$this->db->query("SELECT top 200 * from ms_standar_harga where kode_standar_harga+nama_standar_harga+nama_kel_standar_harga+spesifikasi  collate SQL_Latin1_General_CP1_CI_AS like '%$cari%'");
        $ii = 0;
        $result = array();
        foreach($sql->result() as $ok){
                 $result[] = array(
                            'id' => $ii,        
                            'id_standar_harga' => $ok->id_standar_harga,
                            'kode_standar_harga' => $ok->kode_standar_harga,
                            'nama_standar_harga' => $ok->nama_standar_harga,
                            'kode_kel_standar_harga' => $ok->kode_kel_standar_harga,
                            'nama_kel_standar_harga' => $ok->nama_kel_standar_harga,
                            'harga_satuan' => number_format($ok->harga_satuan,'2','.',','),
                            'harga_preview' => number_format($ok->harga_satuan,'2',',','.'),
                            'satuan' => $ok->satuan,
                            'spesifikasi' => $ok->spesifikasi                                             
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

}