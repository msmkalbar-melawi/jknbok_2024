<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_rka extends CI_Controller {

public $ppkd = "4.02.02";
public $ppkd1 = "4.02.02.02";
public $keu1 = "4.02.02.01";
public $kdbkad="5-02.0-00.0-00.02.01";

public $ppkd_lama = "4.02.02";
public $ppkd1_lama = "4.02.02.02";
 
    function __construct(){  
        parent::__construct();
        $this->load->model('cetak_rka_model');
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }    
    } 

    function cetak_rka_rekap($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' SKPD Penetapan');   
        $this->template->load('template','anggaran/rka/penetapan/rka_skpd_penetapan',$data) ; 
    }

    function cetak_rka_rekap_geser($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' SKPD Pergeseran');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_rekap',$data) ; 
    }

    function cetak_rka_rekap_geser2($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' SKPD Pergeseran');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_rekap2',$data) ; 
    }

    function cetak_rka_rekap_ubah($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' SKPD Perubahan');   
        $this->template->load('template','anggaran/rka/perubahan/dpa_skpd_rekap',$data) ; 
    }

    function cetak_rka_pendapatan($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' 1 Penyusunan');   
        $this->template->load('template','anggaran/rka/penetapan/rka_pendapatan_penyusunan',$data) ; 
    }

    function cetak_rka_pendapatan_geser($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' 1 Pergeseran');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_pendapatan',$data) ; 
    }

    function cetak_rka_pendapatan_geser2($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' 1 Pergeseran');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_pendapatan2',$data) ; 
    }

    function cetak_rka_pendapatan_ubah($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' 1 Perubahan');   
        $this->template->load('template','anggaran/rka/perubahan/dpa_skpd_pendapatan',$data) ; 
    }

    function rka22_penyusunan($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Belanja SKPD Penyusunan');   
        $this->template->load('template','anggaran/rka/penyusunan/rka22_penyusunan',$data) ; 
    }

    function rka_belanja_geser($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Belanja SKPD Penyusunan');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_belanja',$data) ; 
    }

    function rka_belanja_geser2($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Belanja SKPD Penyusunan');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_belanja2',$data) ; 
    }

    function rka_belanja_ubah($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Belanja SKPD Penyusunan');   
        $this->template->load('template','anggaran/rka/perubahan/dpa_skpd_belanja',$data) ; 
    }

    function cetak_rka_pembiayaan($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK '.$jenis.' 31';
        $this->template->set('title', 'CETAK '.$jenis.' 31');   
        $this->template->load('template','anggaran/rka/penetapan/rka_pembiayaan_penetapan',$data) ; 
    }

    function cetak_rka_pembiayaan_pergeseran($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK '.$jenis.' PERGESERAN';
        $this->template->set('title', 'CETAK '.$jenis.' PERGESERAN');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_pembiayaan',$data) ; 
    }

    function cetak_rka_pembiayaan_pergeseran2($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK '.$jenis.' PERGESERAN';
        $this->template->set('title', 'CETAK '.$jenis.' PERGESERAN');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_pembiayaan2',$data) ; 
    }

    function cetak_rka_pembiayaan_perubahan($jenis=''){
        $data['jenis']=$jenis;
        $data['page_title']= 'CETAK '.$jenis.' PERUBAHAN';
        $this->template->set('title', 'CETAK '.$jenis.' PERUBAHAN');   
        $this->template->load('template','anggaran/rka/perubahan/dpa_skpd_pembiayaan',$data) ; 
    }

    function list_cetakrka_skpd(){
        if($this->session->userdata('type')=='1'){
            $data['jenis']="RKA";
            $data['page_title']= 'CETAK RKA ';
            $this->template->set('title', 'CETAK RKA ');   
            $this->template->load('template','anggaran/rka/penetapan/list_cetak_skpd',$data) ;
        }else{
            $this->list_cetakrka_belanja_rinci();
        }

    }

    function list_cetakdpa_skpd(){
        if($this->session->userdata('type')=='1'){
            $data['jenis']="DPA";
            $data['page_title']= 'CETAK DPA ';
            $this->template->set('title', 'CETAK DPA ');   
            $this->template->load('template','anggaran/rka/penetapan/list_cetak_skpd',$data) ;
        }else{
            $this->list_cetakdpa_belanja_rinci();
        }

    }

    function list_cetakdpa_skpd_geser(){
        if($this->session->userdata('type')=='1'){
            $data['jenis']="DPA";
            $data['page_title']= 'CETAK DPA ';
            $this->template->set('title', 'CETAK DPA ');   
            $this->template->load('template','anggaran/rka/pergeseran/list_cetak_skpd',$data) ;
        }else{
            $this->list_cetakdpa_belanja_rinci_pergeseran();
        }
    }

    function list_cetakdpa_skpd_geser2(){
        if($this->session->userdata('type')=='1'){
            $data['jenis']="DPA";
            $data['page_title']= 'CETAK DPA ';
            $this->template->set('title', 'CETAK DPA ');   
            $this->template->load('template','anggaran/rka/pergeseran/list_cetak_skpd2',$data) ;
        }else{
            $this->list_cetakdpa_belanja_rinci_pergeseran2();
        }
    }

    function list_cetakdpa_skpd_ubah(){
        if($this->session->userdata('type')=='1'){
            $data['jenis']="DPA";
            $data['page_title']= 'CETAK DPA ';
            $this->template->set('title', 'CETAK DPA ');   
            $this->template->load('template','anggaran/rka/perubahan/list_cetak_skpd',$data) ;
        }else{
            $this->list_cetakdpa_belanja_rinci_perubahan();
        }

    }

    function list_cetakrka_belanja_rinci($skpd=''){
        $data['skpd']=$skpd;
        $data['jenis']="RKA";
        $data['page_title']= 'CETAK RKA ';
        $this->template->set('title', 'CETAK RKA ');   
        $this->template->load('template','anggaran/rka/penetapan/rka_rincian_belanja',$data) ;
    }

    function list_cetakdpa_belanja_rinci($skpd=''){
        $data['skpd']=$skpd;
        $data['jenis']="DPA";
        $data['page_title']= 'CETAK DPA ';
        $this->template->set('title', 'CETAK DPA ');   
        $this->template->load('template','anggaran/rka/penetapan/rka_rincian_belanja',$data) ;
    }

    function list_cetakdpa_belanja_rinci_pergeseran($skpd=''){
        $data['skpd']=$skpd;
        $data['jenis']="DPA";
        $data['page_title']= 'CETAK DPA ';
        $this->template->set('title', 'CETAK DPA ');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_rincian_belanja',$data) ;
    }


    function list_cetakdpa_belanja_rinci_pergeseran2($skpd=''){
        $data['skpd']=$skpd;
        $data['jenis']="DPA";
        $data['page_title']= 'CETAK DPA ';
        $this->template->set('title', 'CETAK DPA ');   
        $this->template->load('template','anggaran/rka/pergeseran/dpa_skpd_rincian_belanja2',$data) ;
    }

    function list_cetakdpa_belanja_rinci_perubahan($skpd=''){
        $data['skpd'] =$skpd;
        $data['jenis']="DPA";
        $data['page_title']= 'CETAK DPPA ';
        $this->template->set('title', 'CETAK DPPA ');   
        $this->template->load('template','anggaran/rka/perubahan/dpa_skpd_rincian_belanja',$data) ;
    }

    function load_tanda_tangan($skpd=''){ 
        $lccr = $this->input->post('q');    
        echo $this->master_ttd->load_tanda_tangan($skpd,$lccr); 
    } 

    function load_tanda_tangan_bud($skpd=''){ 
        $lccr = $this->input->post('q');    
        echo $this->master_ttd->load_tanda_tangan_bud($skpd,$lccr); 
    } 
    function load_tanda_tangan_ppkd($skpd=''){ 
        $lccr = $this->input->post('q');    
        echo $this->master_ttd->load_ttd_ppkd($skpd,$lccr); 
    } 
    /*cetak rka 0 skpd*/
    function preview_rka_skpd_penetapan(){
        $tgl_ttd= $this->uri->segment(2);
        $ttd1   = $this->uri->segment(3);
        $ttd2   = $this->uri->segment(4);
        $id     = $this->uri->segment(5);
        $cetak  = $this->uri->segment(6);
        $detail = $this->uri->segment(7);
        $doc    = $this->uri->segment(8);
        $gaji   = $this->uri->segment(9);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_rka_model->preview_rka_skpd_penetapan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji);
                
    } 

    function preview_rka_skpd_pergeseran(){
        $tgl_ttd= $this->uri->segment(2);
        $ttd1   = $this->uri->segment(3);
        $ttd2   = $this->uri->segment(4);
        $id     = $this->uri->segment(5);
        $cetak  = $this->uri->segment(6);
        $detail = $this->uri->segment(7);
        $doc    = $this->uri->segment(8);
        $gaji   = $this->uri->segment(9);
        $status1= $this->uri->segment(10);
        $status2= $this->uri->segment(11);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_rka_model->preview_rka_skpd_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji, $status1, $status2);
        
         
    } 

    function preview_rka_skpd_pergeseran2(){
        $tgl_ttd= $this->uri->segment(3);
        $ttd1   = $this->uri->segment(4);
        $ttd2   = $this->uri->segment(5);
        $id     = $this->uri->segment(6);
        $cetak  = $this->uri->segment(7);
        $detail = $this->uri->segment(8);
        $doc    = $this->uri->segment(9);
        $gaji   = $this->uri->segment(10);
        $status1= $this->uri->segment(11);
        $status2= $this->uri->segment(12);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
       echo $this->cetak_rka_model->preview_rka_skpd_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji, $status1, $status2);
         
    } 

    /*cetak rka 2 skpd*/
    function preview_pendapatan_penyusunan(){
        $tgl_ttd = $this->uri->segment(2);
        $ttd1    = $this->uri->segment(3);
        $ttd2    = $this->uri->segment(4);
        $id      = $this->uri->segment(5);
        $cetak   = $this->uri->segment(6);
        $doc     = $this->uri->segment(7);
        echo $this->cetak_rka_model->preview_pendapatan_penyusunan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc);
    }

    function preview_pendapatan_pergeseran(){
        $tgl_ttd = $this->uri->segment(2);
        $ttd1    = $this->uri->segment(3);
        $ttd2    = $this->uri->segment(4);
        $id      = $this->uri->segment(5);
        $cetak   = $this->uri->segment(6);
        $doc     = $this->uri->segment(7);
        $status_anggaran1     = $this->uri->segment(8);
        $status_anggaran2     = $this->uri->segment(9);
        echo $this->cetak_rka_model->preview_pendapatan_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc, $status_anggaran1, $status_anggaran2);
    }

     function preview_pendapatan_pergeseran2(){
        $tgl_ttd = $this->uri->segment(2);
        $ttd1    = $this->uri->segment(3);
        $ttd2    = $this->uri->segment(4);
        $id      = $this->uri->segment(5);
        $cetak   = $this->uri->segment(6);
        $doc     = $this->uri->segment(7);
        $status_anggaran1     = $this->uri->segment(8);
        $status_anggaran2     = $this->uri->segment(9);
        echo $this->cetak_rka_model->preview_pendapatan_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc, $status_anggaran1, $status_anggaran2);
    }

    function preview_belanja_penyusunan(){
        $tgl_ttd = $this->uri->segment(2);
        $ttd1    = $this->uri->segment(3);
        $ttd2    = $this->uri->segment(4);
        $id      = $this->uri->segment(5);
        $cetak   = $this->uri->segment(6);
        $doc     = $this->uri->segment(7);
        echo $this->cetak_rka_model->preview_belanja_penyusunan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc);
    }

    function preview_belanja_pergeseran(){
        $tgl_ttd = $this->uri->segment(2);
        $ttd1    = $this->uri->segment(3);
        $ttd2    = $this->uri->segment(4);
        $id      = $this->uri->segment(5);
        $cetak   = $this->uri->segment(6);
        $doc     = $this->uri->segment(7);
        $status1     = $this->uri->segment(8);
        $status2     = $this->uri->segment(9);
        echo $this->cetak_rka_model->preview_belanja_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc,$status1,$status2);
    }

    function preview_belanja_pergeseran2(){
        $tgl_ttd = $this->uri->segment(2);
        $ttd1    = $this->uri->segment(3);
        $ttd2    = $this->uri->segment(4);
        $id      = $this->uri->segment(5);
        $cetak   = $this->uri->segment(6);
        $doc     = $this->uri->segment(7);
        $status1     = $this->uri->segment(8);
        $status2     = $this->uri->segment(9);
        echo $this->cetak_rka_model->preview_belanja_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc,$status1,$status2);
    }

    function preview_rka_pembiayaan_penetapan(){
        $tgl_ttd= $this->uri->segment(2);
        $ttd1   = $this->uri->segment(3);
        $ttd2   = $this->uri->segment(4);
        $id     = $this->uri->segment(5);
        $cetak  = $this->uri->segment(6);
        $detail = $this->uri->segment(7);
        $doc    = $this->uri->segment(8);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_rka_model->preview_rka_pembiayaan_penetapan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc);            
    } 

    function preview_rka_pembiayaan_pergeseran(){
        $tgl_ttd= $this->uri->segment(3);
        $ttd1   = $this->uri->segment(4);
        $ttd2   = $this->uri->segment(5);
        $id     = $this->uri->segment(6);
        $cetak  = $this->uri->segment(7);
        $detail = $this->uri->segment(8);
        $doc    = $this->uri->segment(9);
        $status_anggaran1    = $this->uri->segment(10);
        $status_anggaran2    = $this->uri->segment(11);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_rka_model->preview_rka_pembiayaan_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$status_anggaran1,$status_anggaran2);            
    }

     function preview_rka_pembiayaan_pergeseran2(){
        $tgl_ttd= $this->uri->segment(3);
        $ttd1   = $this->uri->segment(4);
        $ttd2   = $this->uri->segment(5);
        $id     = $this->uri->segment(6);
        $cetak  = $this->uri->segment(7);
        $detail = $this->uri->segment(8);
        $doc    = $this->uri->segment(9);
        $status_anggaran1    = $this->uri->segment(10);
        $status_anggaran2    = $this->uri->segment(11);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_rka_model->preview_rka_pembiayaan_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$status_anggaran1,$status_anggaran2);            
    }

     function preview_rka221_penyusunan(){
        $id = $this->uri->segment(2);
        $giat = $this->uri->segment(3);
        $cetak = $this->uri->segment(4);
        $atas = $this->uri->segment(5);
        $bawah = $this->uri->segment(6);
        $kiri = $this->uri->segment(7);
        $kanan = $this->uri->segment(8);
        $jns_an = $this->uri->segment(9);
        $tgl_ttd= $_REQUEST['tgl_ttd'];
        $ttd1= $_REQUEST['ttd1'];
        $ttd2= $_REQUEST['ttd2'];
        //$jns_an="RKA"; 
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_rka_model->preview_rka221_penyusunan($id,$giat,$cetak,$atas,$bawah,$kiri,$kanan,$tgl_ttd,$ttd1,$ttd2, $tanggal_ttd,$jns_an);
       
    }


    function rka221_penyusunan(){   
        $id = $this->session->userdata('kdskpd');
        $type = $this->session->userdata('type');
        if($type=='1'){
            $this->index('0','ms_skpd','kd_skpd','nm_skpd','RKA 221 Penyusunan','penyusunan/rka221_penyusunan','');
        }else{
            $this->daftar_kegiatan_penyusunan($id);
        }
    }

    function index($offset=0,$lctabel,$field,$field1,$judul,$list,$lccari){
        $data['page_title'] = "CETAK $judul";
        if(empty($lccari)){
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        }else{
            $total_rows = $this->master_model->get_count_teang($lctabel,$field,$field1,$lccari);
            $lc = "";
        }      
        $config['base_url']         = site_url("cetak_rka/".$list);
        $config['total_rows']       = $total_rows;
        $config['per_page']         = '10';
        $config['uri_segment']      = 3;
        $config['num_links']        = 5;
        $config['full_tag_open']    = '<ul class="page-navi">';
        $config['full_tag_close']   = '</ul>';
        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="current">';
        $config['cur_tag_close']    = '</li>';
        $config['prev_link']        = '&lt;';
        $config['prev_tag_open']    = '<li>';
        $config['prev_tag_close']   = '</li>';
        $config['next_link']        = '&gt;';
        $config['next_tag_open']    = '<li>';
        $config['next_tag_close']   = '</li>';
        $config['last_link']        = 'Last';
        $config['last_tag_open']    = '<li>';
        $config['last_tag_close']   = '</li>';
        $config['first_link']       = 'First';
        $config['first_tag_open']   = '<li>';
        $config['first_tag_close']  = '</li>';
        $limit                      = $config['per_page'];  
        $offset                     = $this->uri->segment(3);  
        $offset                     = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
          
        if(empty($offset)){  
            $offset=0;  
        }
               
        if(empty($lccari)){     
            $data['list']       = $this->master_model->getAll($lctabel,$field,$limit, $offset);
        }else {
            $data['list']       = $this->master_model->getCari($lctabel,$field,$field1,$limit, $offset,$lccari);
        }
        $data['num']            = $offset;
        $data['total_rows']     = $total_rows;
        
        $this->pagination->initialize($config);
        $a=$judul;
        $data['sikap'] = 'list';
        $this->template->set('title', 'CETAK '.$judul);
        $this->template->load('template', "anggaran/rka/".$list, $data);
    }

    function daftar_kegiatan_penyusunan($offset=0){
        $type = $this->session->userdata('type');
        if($type=='1'){
            $id = $this->uri->segment(2);
        }else{
            $id = $this->session->userdata('kdskpd');
        }

        $data['page_title'] = "DAFTAR KEGIATAN";
        
        $total_rows = $this->rka_model->get_count($id);
        $config['base_url']         = base_url("cetak_rka/daftar_kegiatan_penyusunan/$id");
        $config['total_rows']       = $total_rows;
        $config['per_page']         = '10';
        $config['uri_segment']      = 3;
        $config['num_links']        = 5;
        $config['full_tag_open']    = '<ul class="page-navi">';
        $config['full_tag_close']   = '</ul>';
        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li class="current">';
        $config['cur_tag_close']    = '</li>';
        $config['prev_link']        = '&lt;';
        $config['prev_tag_open']    = '<li>';
        $config['prev_tag_close']   = '</li>';
        $config['next_link']        = '&gt;';
        $config['next_tag_open']    = '<li>';
        $config['next_tag_close']   = '</li>';
        $config['last_link']        = 'Last';
        $config['last_tag_open']    = '<li>';
        $config['last_tag_close']   = '</li>';
        $config['first_link']       = 'First';
        $config['first_tag_open']   = '<li>';
        $config['first_tag_close']  = '</li>';
        $limit                      = $config['per_page'];  
        $offset                     = $this->uri->segment(3);  
        $offset                     = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
          
        if(empty($offset))  
        {  
            $offset=0;  
        }
    
        $data['list']       = $this->rka_model->getAll($limit, $offset,$id);
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;
        
                $this->pagination->initialize($config);
        
        $this->template->set('title', 'Master Data kegiatan');
        $this->template->load('template', 'anggaran/rka/penetapan/list_penyusunan', $data);
    }

    function cekkua()
    {
        $data['page_title']= 'CEK KUA SKPD';
        $this->template->set('title', 'CEK KUA SKPD');   
        $this->template->load('template','anggaran/rka/cek_kua',$data) ; 
    }

   function cek_kua(){
        $sql="SELECT nilai_kua-nilai_ang sel,* from(SELECT a.nilai_kua, a.nm_skpd, a.kd_skpd,
                                (SELECT SUM(nilai) FROM trdrka WHERE LEFT(kd_rek6,1)='5' AND left(kd_skpd,22) = left(a.kd_skpd,22)) as nilai_ang,
                                (SELECT SUM(nilai_sempurna) FROM trdrka WHERE LEFT(kd_rek6,1)='5' AND left(kd_skpd,22) =left(a.kd_skpd,22)) as nilai_angg_sempurna,
                                (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek6,1)='5' AND left(kd_skpd,22) = a.kd_skpd) as nilai_angg_ubah
                                FROM ms_skpd a )xx ORDER by kd_skpd";
        $exe=$this->db->query($sql);
        $tbl="<table cellpadding='5px' cellspacing='0' border='1' style='border-collapse:collapse'>
                <tr> 
                    <td align='center' bgcolor='#cccccc'>Kode SKPD</td>
                    <td align='center' bgcolor='#cccccc'>Nama SKPD</td>
                    <td align='center' bgcolor='#cccccc'>Nilai KUA</td>
                    <td align='center' bgcolor='#cccccc'>TOTAL BELANJA</td>
                    <td align='center' bgcolor='#cccccc'>SELISIH</td>
                </tr>";
        foreach($exe->result() as $isi){
            $sel=number_format($isi->sel,2,',','.');
            $nilai_kua=number_format($isi->nilai_kua,2,',','.');
            $nilai_ang=number_format($isi->nilai_ang,2,',','.');
            $nm_skpd=$isi->nm_skpd;
            $kd_skpd=$isi->kd_skpd;

            if($isi->sel<0){
                $bgcolor="bgcolor='red'";
            }else{
                $bgcolor='';
            }
            $tbl .="<tr> 
                        <td $bgcolor align='center'>$kd_skpd</td>
                        <td $bgcolor align='left'>$nm_skpd</td>
                        <td $bgcolor align='right'>$nilai_kua</td>
                        <td $bgcolor align='right'>$nilai_ang</td>
                        <td $bgcolor align='right'>$sel</td>
                    </tr>";
        }

        $tbl .="</table>";
        echo "$tbl";
    }
//anyar
function preview_rincian_belanja_skpd_pergeseran(){

    $id = $this->uri->segment(3);
    $giat = $this->uri->segment(4);
    $cetak = $this->uri->segment(5);
    $atas = $this->uri->segment(6);
    $bawah = $this->uri->segment(7);
    $kiri = $this->uri->segment(8);
    $kanan = $this->uri->segment(9);
    $status1 = $this->uri->segment(10);
    $status2 = $this->uri->segment(11);
    
    


    $tgl_ttd= $_REQUEST['tgl_ttd'];
    $ttd1= $_REQUEST['ttd1'];
    $ttd2= $_REQUEST['ttd2'];
    $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);



    $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
             $sqlsclient=$this->db->query($sqlsc);
             foreach ($sqlsclient->result() as $rowsc)
            {
               
                $tgl=$rowsc->tgl_rka;
                $tanggal = '';//$this->tanggal_format_indonesia($tgl);
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
                $thn     = $rowsc->thn_ang;
                $thnl =$thn-1;
                $thnd =$thn+1; 
            }
   $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(id_ttd, ' ', 'a')='$ttd1' )  ";
             $sqlttd=$this->db->query($sqlttd1);
             foreach ($sqlttd->result() as $rowttd)
            {
                $nip=$rowttd->nip; 
                $pangkat=$rowttd->pangkat;
                $nama= $rowttd->nm;
                $jabatan  = $rowttd->jab;
                //$jabatan  = str_replace('Kuasa Pengguna Anggaran','',$jabatan);
                if($jabatan=='Kuasa Pengguna Anggaran'){
                    $kuasa="";
                }else{
                    $kuasa="Kuasa Pengguna Anggaran";
                }
                
            
            }
          
    $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(id_ttd, ' ', 'a')='$ttd2')  ";
             $sqlttd2=$this->db->query($sqlttd2);
             foreach ($sqlttd2->result() as $rowttd2)
            {
                $nip2=$rowttd2->nip;
                $pangkat2=$rowttd2->pangkat;
                $nama2= $rowttd2->nm;
                $jabatan2  = $rowttd2->jab;
   
                
                if($jabatan2=='Pengguna Anggaran'){
                    $kuasa2="";
                }else{
                    $kuasa2="Pengguna Anggaran";
                }
            }

    $judul_dpa="DOKUMEN PELAKSANAAN ANGGARAN";
    $kd_dpa="DPA";
    $nama_status="Perubahan";


    switch ($status1) {
        case 'nilai':
            $status_anggaran1="";
            $status_anggaran1x="";
            $status_speksifikasi1="";
            $status_harga1="";
            $status_satuan1="";
            $status_volume1="";
            $pajak1="";
            $koefisien1="";
            break;
        
        case 'nilai_ubah':
            $status_anggaran1="_ubah";
            $status_anggaran1x="_ubah";
            $status_speksifikasi1="_ubah";
            $status_harga1="_ubah";
            $status_satuan1="_ubah1";
            $status_volume1="_ubah1";
            $pajak1="_ubah";
            $koefisien1="_ubah";
            break;

        case 'nilai_sempurna':
            $status_anggaran1="_sempurna";
            $status_anggaran1x="_sempurna";
            $status_speksifikasi1="_sempurna";
            $status_harga1="_sempurna1";
            $status_satuan1="_sempurna1";
            $status_volume1="_sempurna1";
            $pajak1="_sempurna";
            $koefisien1="_sempurna";
            break;

        case 'sempurna2':
            $status_anggaran1="_sempurna2";
            $status_anggaran1x="sempurna2";
            $status_speksifikasi1="_sempurna2";
            $status_harga1="_sempurna2";
            $status_satuan1="_sempurna21";
            $status_volume1="_sempurna2";
            $pajak1="_sempurna1";
            $koefisien1="_sempurna2";
            break;

        case 'sempurna3':
            $status_anggaran1="_sempurna3";
            $status_anggaran1x="sempurna3";
            $status_speksifikasi1="_sempurna3";
            $status_harga1="_sempurna3";
            $status_satuan1="_sempurna31";
            $status_volume1="_sempurna3";
            $pajak1="_sempurna3";
            $koefisien1="_sempurna3";
            break;

        case 'sempurna4':
            $status_anggaran1="_sempurna4";
            $status_anggaran1x="sempurna4";
            $status_speksifikasi1="_sempurna4";
            $status_harga1="_sempurna4";
            $status_satuan1="_sempurna41";
            $status_volume1="_sempurna4";
            $pajak1="_sempurna4";
            $koefisien1="_sempurna4";
            break;

        case 'sempurna5':
            $status_anggaran1="_sempurna5";
            $status_anggaran1x="sempurna5";
            $status_speksifikasi1="_sempurna5";
            $status_harga1="_sempurna5";
            $status_satuan1="_sempurna51";
            $status_volume1="_sempurna5";
            $pajak1="_sempurna5";
            $koefisien1="_sempurna5";
            break;

        case 'sempurna6':
            $status_anggaran1="_sempurna6";
            $status_anggaran1x="sempurna6";
            $status_speksifikasi1="_sempurna6";
            $status_harga1="_sempurna6";
            $status_satuan1="_sempurna61";
            $status_volume1="_sempurna6";
            $pajak1="_sempurna6";
            $koefisien1="_sempurna6";
            break;

        default:
            $status_anggaran1="_ubah";
            $status_anggaran1x="_ubah";
            $status_speksifikasi1="";
            $status_harga1="_ubah";
            $status_satuan1="_ubah1";
            $status_volume1="_ubah1";
            $pajak1="_ubah";
            $koefisien1="_ubah";
            break;
    }

    
    switch ($status2) {
        case 'nilai':
            $status_anggaran2="";
            $status_anggaran2x="";
            $status_speksifikasi2="";
            $status_harga2="";
            $status_satuan2="";
            $status_volume2="";
            $pajak2="";
            $koefisien3="";

            break;
        
        case 'nilai_ubah':
            $status_anggaran2="_ubah";
            $status_anggaran2x="_ubah";
            $status_speksifikasi2="_ubah";
            $status_harga2="_ubah";
            $status_satuan2="_ubah1";
            $status_volume2="_ubah1";
            $pajak2="_ubah";
            $koefisien3="_ubah";
            $judul_dpa="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
            $kd_dpa="DPPA";
            $nama_status="Perubahan";
            break;

        case 'nilai_sempurna':
            $status_anggaran2="_sempurna";
            $status_anggaran2x="_sempurna";
            $status_speksifikasi2="_sempurna";
            $status_harga2="_sempurna1";
            $status_satuan2="_sempurna1";
            $status_volume2="_sempurna1";
            $pajak2="_sempurna";
            $koefisien3="_sempurna";
            break;

        case 'sempurna2':
            $status_anggaran2="_sempurna2";
            $status_anggaran2x="sempurna2";
            $status_speksifikasi2="_sempurna2";
            $status_harga2="_sempurna2";
            $status_satuan2="_sempurna21";
            $status_volume2="_sempurna2";
            $pajak2="_sempurna2";
            $koefisien3="_sempurna2";
            break;

        case 'sempurna3':
            $status_anggaran2="_sempurna3";
            $status_anggaran2x="sempurna3";
            $status_speksifikasi2="_sempurna3";
            $status_harga2="_sempurna3";
            $status_satuan2="_sempurna31";
            $status_volume2="_sempurna3";
            $pajak2="_sempurna3";
            $koefisien3="_sempurna3";
            break;

        case 'sempurna4':
            $status_anggaran2="_sempurna4";
            $status_anggaran2x="sempurna4";
            $status_speksifikasi2="_sempurna4";
            $status_harga2="_sempurna4";
            $status_satuan2="_sempurna41";
            $status_volume2="_sempurna4";
            $pajak2="_sempurna4";
            $koefisien3="_sempurna4";
            break;

        case 'sempurna5':
            $status_anggaran2="_sempurna5";
            $status_anggaran2x="sempurna5";
            $status_speksifikasi2="_sempurna5";
            $status_harga2="_sempurna5";
            $status_satuan2="_sempurna51";
            $status_volume2="_sempurna5";
            $pajak2="_sempurna5";
            $koefisien3="_sempurna5";
            break;

        case 'sempurna6':
            $status_anggaran2="_sempurna6";
            $status_anggaran2x="sempurna6";
            $status_speksifikasi2="_sempurna6";
            $status_harga2="_sempurna6";
            $status_satuan2="_sempurna61";
            $status_volume2="_sempurna6";
            $pajak2="_sempurna6";
            $koefisien3="_sempurna6";
            break;

        default:
            $status_anggaran2="_ubah";
            $status_anggaran2x="_ubah";
            $status_speksifikasi2="";
            $status_harga2="_ubah";
            $status_satuan2="_ubah1";
            $status_volume2="_ubah1";
            $pajak2="_ubah";
            $koefisien3="_ubah";
            $judul_dpa="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
            $kd_dpa="DPPA";
            $nama_status="Perubahan";
            break;


    }




     $sqlorg="SELECT top 1 * FROM (SELECT g.kd_urusan,g.nm_urusan,f.kd_bidang_urusan,f.nm_bidang_urusan,left(a.kd_gabungan,22) kd_skpd ,a.nm_skpd,a.kd_program,a.nm_program,a.sasaran_program,a.capaian_program,c.kd_kegiatan,c.nm_kegiatan,a.kd_sub_kegiatan,a.nm_sub_kegiatan,SUM(d.nilai) AS nilai,a.tu_capai,
        a.tu_mas,
        a.tu_kel,
        a.tu_has,
        a.tk_capai,
        a.tk_mas,
        a.tk_kel,
        a.tk_has,a.lokasi,d.sumber,a.kel_sasaran_kegiatan,a.ang_lalu FROM trskpd a 
        INNER JOIN ms_kegiatan c ON a.kd_kegiatan=
        c.kd_kegiatan
        INNER JOIN trdrka d ON a.kd_kegiatan=left(d.kd_sub_kegiatan,12)
        
        INNER JOIN ms_skpd e ON a.kd_skpd=e.kd_skpd
        INNER JOIN ms_bidang_urusan f ON a.kd_bidang_urusan=f.kd_bidang_urusan
        INNER JOIN ms_urusan g ON left(a.kd_bidang_urusan,1)=g.kd_urusan
        where  left(a.kd_gabungan,17)=left('$id',17)
        GROUP BY 
        g.kd_urusan,
        g.nm_urusan,
        f.kd_bidang_urusan,
        f.nm_bidang_urusan,
        left(a.kd_gabungan,22) ,
        a.nm_skpd,
        a.kd_program,
        a.nm_program,
        a.sasaran_program,
        a.capaian_program,
        c.kd_kegiatan,
        c.nm_kegiatan,
        a.kd_sub_kegiatan,
        a.nm_sub_kegiatan,
        a.tu_capai,
        a.tu_mas,
        a.tu_kel,
        a.tu_has,
        a.tk_capai,
        a.tk_mas,
        a.tk_kel,
        a.tk_has,
        a.lokasi,
        d.sumber,
        a.kel_sasaran_kegiatan,
        a.ang_lalu) OKE
                            where left(kd_sub_kegiatan,12)='$giat' and left(kd_skpd,17)=left('$id',17)
            ";
             $sqlorg1=$this->db->query($sqlorg);
             foreach ($sqlorg1->result() as $roworg)
            {
                $kd_urusan=$roworg->kd_urusan;                    
                $nm_urusan= $roworg->nm_urusan;
                $kd_bidang_urusan=$roworg->kd_bidang_urusan;                    
                $nm_bidang_urusan= $roworg->nm_bidang_urusan;
                $kd_skpd  = $roworg->kd_skpd;
                $nm_skpd  = $roworg->nm_skpd;
                $kd_prog  = $roworg->kd_program;
                $nm_prog  = $roworg->nm_program;
                $sasaran_prog  = $roworg->sasaran_program;
                $capaian_prog  = $roworg->capaian_program;
                $kd_giat  = $roworg->kd_kegiatan;
                $nm_giat  = $roworg->nm_kegiatan;
                $lokasi  = $roworg->lokasi;
                $tu_capai  = $roworg->tu_capai;
                $tu_mas  = $roworg->tu_mas;
                $tu_kel  = $roworg->tu_kel;
                $tu_has  = $roworg->tu_has;
                $tk_capai  = $roworg->tk_capai;
                $tk_mas  = $roworg->tk_mas;
                $tk_kel  = $roworg->tk_kel;
                $tk_has  = $roworg->tk_has;
                $sas_giat = $roworg->kel_sasaran_kegiatan;
                $ang_lalu = $roworg->ang_lalu;
            }
    $kd_urusan= empty($roworg->kd_urusan) || ($roworg->kd_urusan) == '' ? '' : ($roworg->kd_urusan);
    $nm_urusan= empty($roworg->nm_urusan) || ($roworg->nm_urusan) == '' ? '' : ($roworg->nm_urusan);
    $kd_bidang_urusan= empty($roworg->kd_bidang_urusan) || ($roworg->kd_bidang_urusan) == '' ? '' : ($roworg->kd_bidang_urusan);
    $nm_bidang_urusan= empty($roworg->nm_bidang_urusan) || ($roworg->nm_bidang_urusan) == '' ? '' : ($roworg->nm_bidang_urusan);
    $kd_skpd= empty($roworg->kd_skpd) || ($roworg->kd_skpd) == '' ? '' : ($roworg->kd_skpd);
    $nm_skpd= empty($roworg->nm_skpd) || ($roworg->nm_skpd) == '' ? '' : ($roworg->nm_skpd);
    $kd_prog= empty($roworg->kd_program) || ($roworg->kd_program) == '' ? '' : ($roworg->kd_program);
    $nm_prog= empty($roworg->nm_program) || ($roworg->nm_program) == '' ? '' : ($roworg->nm_program);
    $sasaran_prog= empty($roworg->sasaran_program) || ($roworg->sasaran_program) == '' ? '' : ($roworg->sasaran_program);
    $capaian_prog= empty($roworg->capaian_program) || ($roworg->capaian_program) == '' ? '' : ($roworg->capaian_program);
    $kd_giat= empty($roworg->kd_kegiatan) || ($roworg->kd_kegiatan) == '' ? '' : ($roworg->kd_kegiatan);
    $nm_giat= empty($roworg->nm_kegiatan) || ($roworg->nm_kegiatan) == '' ? '' : ($roworg->nm_kegiatan);
    $lokasi= empty($roworg->lokasi) || ($roworg->lokasi) == '' ? '' : ($roworg->lokasi);
    $tu_capai= empty($roworg->tu_capai) || ($roworg->tu_capai) == '' ? '' : ($roworg->tu_capai);
    $tu_mas= empty($roworg->tu_mas) || ($roworg->tu_mas) == '' ? '' : ($roworg->tu_mas);
    $tu_kel= empty($roworg->tu_kel) || ($roworg->tu_kel) == '' ? '' : ($roworg->tu_kel);
    $tu_has= empty($roworg->tu_has) || ($roworg->tu_has) == '' ? '' : ($roworg->tu_has);
    $tk_capai= empty($roworg->tk_capai) || ($roworg->tk_capai) == '' ? '' : ($roworg->tk_capai);
    $tk_mas= empty($roworg->tk_mas) || ($roworg->tk_mas) == '' ? '' : ($roworg->tk_mas);
    $tk_kel= empty($roworg->tk_kel) || ($roworg->tk_kel) == '' ? '' : ($roworg->tk_kel);
    $tk_has= empty($roworg->tk_has) || ($roworg->tk_has) == '' ? '' : ($roworg->tk_has);
    $sas_giat= empty($roworg->kel_sasaran_kegiatan) || ($roworg->kel_sasaran_kegiatan) == '' ? '' : ($roworg->kel_sasaran_kegiatan);
    $ang_lalu= empty($roworg->ang_lalu) || ($roworg->ang_lalu) == '' || ($roworg->ang_lalu) == 'Null' ? 0 : ($roworg->ang_lalu);

    $sqltp="SELECT SUM(nilai$status_anggaran1x) AS totb, SUM(nilai$status_anggaran2x) AS totb2 FROM trdrka WHERE left(kd_sub_kegiatan,12)='$giat' AND left(kd_skpd,17)=left('$id',17)";
             $sqlb=$this->db->query($sqltp);
             foreach ($sqlb->result() as $rowb)
            {
               $totp  =number_format($rowb->totb,0,',','.');
               $totp1 =number_format($rowb->totb*1.1,0,',','.');
               $totp2  =number_format($rowb->totb2,0,',','.');
               $totp12 =number_format($rowb->totb2*1.1,0,',','.');
            }
            
    $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
    $cRet='';
    $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                <tr> 
                     <td width='80%' align='center'><strong>$judul_dpa <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                     <td width='20%' rowspan='2' align='center'><strong> FORMULIR <br /> $kd_dpa - $judul_dpa SKPD    
</strong></td>
                </tr>
                <tr>
                     <td style='vertical-align:top;' align='center'><strong>$kab</strong> <br /><strong>TAHUN ANGGARAN $thn</strong></td>
                </tr>

              </table>";
              
    $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0'>
                    <tr>
                        <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;No DPA</td>
                        <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                        <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$nodpa</td>
                        <td width='60%' style='vertical-align:top;border-left: none;' align='left'></td>
                    </tr>                            
                    <tr>
                        <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Urusan Pemerintahan</td>
                        <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                        <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_urusan</td>
                        <td width='60%' style='vertical-align:top;border-left: none;' align='left'>$nm_urusan</td>
                    </tr>
                    <tr>
                        <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Bidang Urusan</td>
                        <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                        <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_bidang_urusan </td>
                        <td width='60%' style='vertical-align:top;border-left: none;' align='left'> $nm_bidang_urusan</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Program</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_prog</td>
                        <td align='left' style='vertical-align:top;border-left: none;'>$nm_prog</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Sasaran Program</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$sasaran_prog</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Capaian Program</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$tu_capai - $tk_capai</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Kegiatan</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_giat</td>
                        <td align='left' style='vertical-align:top;border-left: none;'>$nm_giat</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Organisasi</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>".substr($kd_skpd,0,17)."</td>
                        <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Unit Organisasi</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_skpd</td>
                        <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnl</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td colspan ='2'  align='left' style='vertical-align:top;border-left: none;'>Rp. ".number_format($ang_lalu,0,',','.')." (".$this->rka_model->terbilang($ang_lalu*1)." rupiah)</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thn</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp (".$this->rka_model->terbilang($rowb->totb*1)." rupiah)</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnd</td>
                        <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                        <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp1 (".$this->rka_model->terbilang($rowb->totb*1.1)." rupiah)</td>
                    </tr>
                    <tr>
                <td colspan='4'  width='100%' align='left'>&nbsp;</td>
            </tr>
                </table>    
                    
                ";
    $cRet .= "<table style='border-collapse:collapse;font-size:10px' width='100%' align='left' border='1' cellspacing='0' cellpadding='0'>
                <tr>
                    <td colspan='5'  align='center' >Indikator & Tolak Ukur Kinerja Kegiatan</td>
                </tr>";
    $cRet .="<tr>
             <td  rowspan='2' align='center'>Indikator </td>
             <td  colspan='2' align='center'>Sebelum $nama_status </td>
             <td  colspan='2' align='center'>Setelah $nama_status </td>
            </tr>"; 
    $cRet .="<tr>
             <td  align='center'>Tolak Ukur Kerja </td>
             <td  align='center'>Target Kinerja </td>
             <td  align='center'>Tolak Ukur Kerja </td>
             <td  align='center'>Target Kinerja </td>
            </tr>";          

    $cRet .=" <tr align='center'>
                <td >Capaian Kegiatan </td>
                <td>$tu_capai</td>
                <td>$tk_capai</td>
                <td>$tu_capai</td>
                <td>$tk_capai</td>
             </tr>";
    $cRet .=" <tr align='center'>
                <td>Masukan </td>
                <td>Dana yang dibutuhkan</td>
                <td>Rp. $totp</td>
                <td>Dana yang dibutuhkan</td>
                <td>Rp. $totp2</td>
            </tr>";
    $cRet .=" <tr align='center'>
                <td>Keluaran </td>
                <td>$tu_kel</td>
                <td>$tk_kel</td>
                <td>$tu_kel</td>
                <td>$tk_kel</td>
              </tr>";
    $cRet .=" <tr align='center'>
                <td>Hasil sd </td>
                <td>$tu_has</td>
                <td>$tk_has</td>
                <td>$tu_has</td>
                <td>$tk_has</td>
              </tr>";
    $cRet .= "<tr>
                <td colspan='5'  align='left'>Kelompok Sasaran Kegiatan : $sas_giat</td>
            </tr>";
    $cRet .= "<tr>
                <td colspan='5' align='left'>&nbsp;</td>
            </tr>"; 
            $cRet .= "<tr>
                <td colspan='5' bgcolor='#CCCCCC' align='left'>&nbsp;</td>
            </tr>";                
    
    $cRet .= "<tr>
                    <td colspan='5' align='center'>RINCIAN ANGGARAN BELANJA KEGIATAN SATUAN KERJA PERANGKAT DAERAH</td>
              </tr>";
                
    $cRet .="</table>";
//rincian sub kegiatan
 

           $sqlsub="SELECT id_skpd, id_sub_kegiatan, left(a.kd_gabungan,22) skpd, a.nm_skpd, a.kd_sub_kegiatan as kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2 FROM trskpd a
            
            WHERE left(a.kd_sub_kegiatan,12)='$giat' AND left(a.kd_skpd,17)=left('$id',17)
            group by left(a.kd_gabungan,22) ,a.nm_skpd, a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2,id_skpd, id_sub_kegiatan";
             $sqlbsub=$this->db->query($sqlsub);
             foreach ($sqlbsub->result() as $rowsub)
            {
               $sub         =$rowsub->kd_sub_kegiatan;
               $nm_sub      =$rowsub->nm_sub_kegiatan;
               $sub_keluaran=$rowsub->sub_keluaran;
               $lokasi      =$rowsub->lokasi;
               $skpd        =$rowsub->skpd;
               $id_skpd     =$rowsub->id_skpd;
               $id_sub_kegiatan     =$rowsub->id_sub_kegiatan;
               $nm_skpd     =$rowsub->nm_skpd;
               $waktu_giat  =$rowsub->waktu_giat;
               $waktu_giat2 =$rowsub->waktu_giat2;
               $keterangan  ="";

           
             $kodesumberdana=$this->db->query("SELECT top 1 sumber+' '+isnull(sumber2,'')+' '+isnull(sumber3,'')+' '+isnull(sumber4,'') as sumber from trdrka where kd_sub_kegiatan='$sub' and kd_skpd='$id'
                union all 
                select ''"); 
             foreach($kodesumberdana->result() as $oke){
                $sumbr=$oke->sumber;
             }
                $cRet .="
                <pagebreak type='NEXT-ODD'   suppress='off' />
                <table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                    <tr>
                        <td width style='tical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sub Kegiatan</td>
                        <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                        <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$sub - $nm_sub</td>
                    </tr>
                    <tr>
                        <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sumber Pendanaan</td>
                        <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                        <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$sumbr</td>
                    </tr>
                    <tr>
                        <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Lokasi</td>
                        <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                        <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'><table  style='border-collapse:collapse;font-size:12px'> $skpd - $nm_skpd"; 
                                $okeii=$this->db->query("SELECT * from sipd_lokout where id_sub_skpd='$id_skpd' and id_sub_giat='$id_sub_kegiatan'");
                                foreach($okeii->result() as $ac){
                                    $oke=$ac->daerahteks;
                                   
                                    $cRet.="<tr><td>$oke </td></tr>";
                                }

                $cRet.="</table></td>
                    </tr>
                    <tr>
                        <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Waktu Pelaksanaan</td>
                        <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                        <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'> ".$this->support->getBulan($waktu_giat)." s/d ".$this->support->getBulan($waktu_giat2)."</td>
                    </tr>
                    <tr>
                        <td align='left' style='vertical-align:top;border-left: solid 1px black;border-bottom: solid 1px black;' align='left'>&nbsp;Keluaran Sub Kegiatan</td>
                        <td align='center' style='vertical-align:top;border-bottom: solid 1px black;'>:</td>
                        <td align='left' colspan='3' style='vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;'>
                               
                                <table  style='border-collapse:collapse;font-size:12px'>"; 
                                $okeii=$this->db->query("SELECT * from sipd_output where id_sub_skpd='$id_skpd' and id_sub_giat='$id_sub_kegiatan'");
                                foreach($okeii->result() as $ac){
                                    $oke=$ac->outputteks;
                                    $jiwa=$ac->targetoutputteks;
                                    $cRet.="<tr><td>$oke - $jiwa</td></tr>";
                                }

                $cRet.="</table>
                        </td>
                    </tr>
                    </table>
                    
                ";

                $cRet .= "<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'>
                      <thead>
                    <tr>
                        <td rowspan='3' bgcolor='#CCCCCC' align='center'><b>Kode Rekening</b></td>                            
                        <td rowspan='3' bgcolor='#CCCCCC' align='center'><b>Uraian</b></td>
                        <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Sebelum $nama_status</b></td>
                        <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Setelah $nama_status</b></td>
                        <td colspan='2' rowspan='2' bgcolor='#CCCCCC' align='center'><b>Bertambah/(Berkurang)</b></td>

                    </tr>                 
                    <tr>
                        <td colspan='4' bgcolor='#CCCCCC' align='center'><b>Rincian Perhitungan</b></td>
                        <td rowspan='2' bgcolor='#CCCCCC' align='center'><b>Jumlah(Rp.)</b></td>
                        <td colspan='4' bgcolor='#CCCCCC' align='center'><b>Rincian Perhitungan</b></td>
                        <td rowspan='2' bgcolor='#CCCCCC' align='center'><b>Jumlah(Rp.)</b></td>

                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center'>Koefisien</td>
                        <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                        <td bgcolor='#CCCCCC' align='center'>Harga</td>
                        <td bgcolor='#CCCCCC' align='center'>PPN</td>
                        <td bgcolor='#CCCCCC' align='center'>Koefisien</td>
                        <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                        <td bgcolor='#CCCCCC' align='center'>Harga</td>
                        <td bgcolor='#CCCCCC' align='center'>PPN</td>
                        <td bgcolor='#CCCCCC' align='center'>Rp</td>
                        <td bgcolor='#CCCCCC' align='center'>%</td>
                    </tr>    
                 
                </thead> 
                 
                    <tr>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;1</td>                            
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;2</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;3</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;4</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;5</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;6</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;7</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;8</td>                            
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;9</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;10</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;11</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;12</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;13</td>
                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' >&nbsp;14</td>
                    </tr>
                    ";

                    $sql1="SELECT * FROM(SELECT 0 header,0 no_po, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama, '' spek, '' as koefisien,0 AS pajak1,' 'AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS pajak2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.nilai$status_anggaran1x) AS nilai, SUM(a.nilai$status_anggaran2x) AS nilai2,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE a.kd_sub_kegiatan='$sub' AND left(a.no_trdrka,22)='$skpd' 
                            GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
                            UNION ALL 
                            SELECT 0 header, 0 no_po,LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.nilai$status_anggaran1x) AS nilai, SUM(a.nilai$status_anggaran2x) AS nilai2,'2' AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE a.kd_sub_kegiatan='$sub'
                            AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
                            UNION ALL  
                            SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.nilai$status_anggaran1x) AS nilai, SUM(a.nilai$status_anggaran2x) AS nilai2,'3' AS id FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE a.kd_sub_kegiatan='$sub'
                            AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
                            UNION ALL 
                            SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.nilai$status_anggaran1x) AS nilai, SUM(a.nilai$status_anggaran2x) AS nilai2,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE a.kd_sub_kegiatan='$sub'
                            AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
                            UNION ALL 
                            SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,8) AS rek1,RTRIM(LEFT(a.kd_rek6,8)) AS rek,b.nm_rek5 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.nilai$status_anggaran1x) AS nilai, SUM(a.nilai$status_anggaran2x) AS nilai2,'5' AS id FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE a.kd_sub_kegiatan='$sub'
                            AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5
                            UNION ALL
                            SELECT 0 header, 0 no_po, a.kd_rek6 AS rek1,RTRIM(a.kd_rek6) AS rek,b.nm_rek6 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.nilai$status_anggaran1x) AS nilai, SUM(a.nilai$status_anggaran2x) AS nilai2,'6' AS id FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE a.kd_sub_kegiatan='$sub'
                            AND left(a.no_trdrka,22)='$skpd'  GROUP BY a.kd_rek6,b.nm_rek6
                            UNION ALL

                            SELECT * FROM (SELECT b.header,b.no_po as no_pos,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien,0 AS volume,' ' AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2,'7' AS id FROM trdpo a LEFT JOIN trdpo b ON b.subs_bl_teks=a.uraian
                            AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE LEFT(a.no_trdrka,22)='$skpd' AND 
                            SUBSTRING(a.no_trdrka,24,15)='$sub' GROUP BY RIGHT(a.no_trdrka,12),b.header, b.no_po,b.uraian)z WHERE header='1' 
                            UNION ALL
                            SELECT * FROM (SELECT b.header,b.no_po as no_pos,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien, 0 AS volume,' ' AS satuan, 0 AS harga, '' spek2, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                            SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 ,'8' AS id FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
                            AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE LEFT(a.no_trdrka,22)='$skpd' AND 
                            SUBSTRING(a.no_trdrka,24,15)='$sub' GROUP BY RIGHT(a.no_trdrka,12),b.header, b.no_po,b.uraian)z WHERE header='1' 
                                
                                UNION ALL
                                SELECT a. header,a.no_po as no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,a.uraian AS nama,spesifikasi$status_speksifikasi1 as spek, koefisien$koefisien1 koefisien1, pajak$pajak2 AS volume,a.satuan$status_satuan1 AS satuan, a.harga$status_harga1 AS harga, a.spesifikasi$status_speksifikasi2 as spek, koefisien$koefisien3 koefisien2, pajak$pajak2 AS volume,a.satuan$status_satuan2 AS satuan, harga$status_harga2 AS harga,
                                a.total$status_anggaran1 AS nilai, a.total$status_anggaran2 AS nilai2 ,'9' AS id FROM trdpo a  WHERE LEFT(a.no_trdrka,22)='$skpd' AND SUBSTRING(no_trdrka,24,15)='$sub' AND (header='0' or header is null)
                                ) a ORDER BY a.rek1, a.no_po";
             
            $query = $this->db->query($sql1);
            $nilangsub=0;
            $nilangsub2=0;

                    foreach ($query->result() as $row)
                    {
                        $rek=$row->rek;
                        $reke=$this->support->dotrek($rek);
                        $uraian=$row->nama;
                        $spek_komp=$row->spek;
                        $koefisien=$row->koefisien;
                        $spek_komp2=$row->spek2;
                        $koefisien2=$row->koefisien2;

                    //    $volum=$row->volume;
                        $sat=$row->satuan;
                        $sat2=$row->satuan2;
                        $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,0,',','.');
                        $volum= empty($row->volume) || $row->volume == 0 ? '' :$row->volume;
                        $hrg2= empty($row->harga2) || $row->harga2 == 0 ? '' :number_format($row->harga2,0,',','.');
                        $volum2= empty($row->volume2) || $row->volume == 0 ? '' :$row->volume2;

                        //$hrg=number_format($row->harga,"2",".",",");
                        $nila= empty($row->nilai) || $row->nilai == 0 ? '' :number_format($row->nilai,0,',','.');
                        $nila2= empty($row->nilai2) || $row->nilai2 == 0 ? '' :number_format($row->nilai2,0,',','.');

                        $selisih= $this->support->format_bulat($row->nilai2-$row->nilai);
                        if($row->nilai==0){
                            $persen=0;
                        }else{
                            $persen= $this->support->format_bulat((($row->nilai2-$row->nilai)/$row->nilai)*100);
                        }
                        

                                
                        
                        if ($row->id<='8'){
                            $ppn='';
                            
                       
                         $cRet    .= " <tr>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='left'><b>$reke</b></td>                                     
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' ><b>$uraian</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'><b>$koefisien</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'><b>$sat</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'><b>$hrg</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'><b>$ppn</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'><b>$nila</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='left'><b>$koefisien</b></td>                                     
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' ><b>$sat2</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'><b>$hrg2</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'><b>$ppn</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'><b>$nila2</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'><b>$selisih</b></td>
                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'><b>$persen</b></td>
                                     </tr>
                                         ";

                                     }else{
                                        $ppn=$row->pajak1;
                                        $ppn2=$row->pajak2;

                                        $cRet    .= " <tr>
                                                        <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;'  align='left'>$reke</td>                                     
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' >$uraian <br>&nbsp;&nbsp;&nbsp; $spek_komp2</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$koefisien</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'>$sat</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$hrg</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'><b>$ppn</b></td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$nila</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='left'>".$koefisien2."</td>                                     
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' > ".$sat2."</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$hrg2."</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$ppn2."</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$nila2."</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$selisih</td>
                                                         <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'>$persen</td>
                                                </tr>
                                         ";
                                         $nilangsub= $nilangsub+$row->nilai;
                                         $nilangsub2= $nilangsub2+$row->nilai2;
                                         $selisih=$this->support->format_bulat($nilangsub2-$nilangsub);
                                         if($nilangsub==0){
                                            $persen=$this->support->format_bulat(0);
                                         }else{
                                            $persen=$this->support->format_bulat((($nilangsub2-$nilangsub)/$nilangsub)*100);                                                   
                                         }

                                               
                                     }
                                     
                    }

                    $cRet    .=" 
                                <tr>                                    
                                 <td colspan='6' align='right' style='vertical-align:top;' >Jumlah Anggaran Sub Kegiatan Sebelum $nama_status</td>
                                 <td style='vertical-align:top;'  align='right'>".number_format($nilangsub,0,',','.')."</td>
                                 <td colspan='4' align='right' style='vertical-align:top;' >Jumlah Anggaran Sub Kegiatan Setelah $nama_status</td>
                                 <td style='vertical-align:top;'  align='right'>".number_format($nilangsub2,0,',','.')."</td>
                                 <td style='vertical-align:top;'  align='right'>".$selisih."</td>
                                 <td style='vertical-align:top;'  align='center'>".$persen."</td>
                                 </tr>
                                                                          
                                 <tr>                                    
                                 <td colspan='14'  align='right' style='vertical-align:top;'>&nbsp;</td>
                                 </tr>
                                 </table> "; 

if($ttd1!='tanpa'){
    $sqlttd1="SELECT top 1  nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' union all select '','','','' ORDER by nip";
    $sqlttd=$this->db->query($sqlttd1);
    foreach ($sqlttd->result() as $rowttd){
                $nip=$rowttd->nip;  
                $pangkat=$rowttd->pangkat;  
                $nama= $rowttd->nm;
                $jabatan  = $rowttd->jab;
    }
            
    $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                        $jabatan 
                        <br><br>
                        <br><br>
                        <br><br>
                        <b>$nama</b><br>
                        <u>NIP. $nip</u></td>";
      
}else{
    $tambahan="";
}
        $angkas5=$this->db->query("SELECT  kd_skpd, 
                                        isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                        isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                        isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                        isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                        isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                        isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                        isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                        isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                        isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                        isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                        isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                        isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                        select bulan, left(kd_gabungan,22) kd_skpd , sum(nilai$status_anggaran2x) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' and kd_sub_kegiatan='$sub' GROUP BY bulan, left(kd_gabungan,22)
                                        ) okey where kd_skpd='$skpd' GROUP BY kd_skpd
                                        union all
                                        SELECT '' oke, 0,0,0,0,0,0,0,0,0,0,0,0 "

                                    )->row();
        
        $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                    <tr>
                        <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                        $tambahan
                    </tr>
                    <tr>
                        <td width='30%'>Januari</td>
                        <td width='30%' align='right'>".number_format($angkas5->jan,0,',','.')."</td>                                
                    </tr>
                    <tr>
                        <td width='30%'>Februari</td>
                        <td width='30%' align='right'>".number_format($angkas5->feb,0,',','.')."</td>                                 
                    </tr>
                    <tr>
                        <td width='30%'>Maret</td>
                        <td width='30%' align='right'>".number_format($angkas5->mar,0,',','.')."</td>                              
                    </tr>
                    <tr>
                        <td width='30%'>April</td>
                        <td width='30%' align='right'>".number_format($angkas5->apr,0,',','.')."</td>                                
                    </tr>
                    <tr>
                        <td width='30%'>Mei</td>
                        <td width='30%' align='right'>".number_format($angkas5->mei,0,',','.')."</td>                            
                    </tr>
                    <tr>
                        <td width='30%'>Juni</td>
                        <td width='30%' align='right'>".number_format($angkas5->jun,0,',','.')."</td>                                 
                    </tr>
                    <tr>
                        <td width='30%'>Juli</td>
                        <td width='30%' align='right'>".number_format($angkas5->jul,0,',','.')."</td>                                 
                    </tr>
                    <tr>
                        <td width='30%'>Agustus</td>
                        <td width='30%' align='right'>".number_format($angkas5->ags,0,',','.')."</td>                                 
                    </tr>
                    <tr>
                        <td width='30%'>September</td>
                        <td width='30%' align='right'>".number_format($angkas5->sept,0,',','.')."</td>                                  
                    </tr>
                    <tr>
                        <td width='30%'>Oktober</td>
                        <td width='30%' align='right'>".number_format($angkas5->okt,0,',','.')."</td>                                  
                    </tr>
                    <tr>
                        <td width='30%'>November</td>
                        <td width='30%' align='right'>".number_format($angkas5->nov,0,',','.')."</td>                                 
                    </tr>
                    <tr>
                        <td width='30%'>Desember</td>
                        <td width='30%' align='right'>".number_format($angkas5->des,0,',','.')."</td>                                 
                    </tr>
                    <tr>
                        <td width='30%' align='right'>Jumlah</td>
                        <td width='30%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,0,',','.')."</td>                               
                    </tr>

                </table>";



                                 $cRet.="</pagebreak>";
            }

            


                    $cRet    .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'> 
                                

                                 <tr>                                    
                                 <td colspan='5' align='right' style='vertical-align:top;' width='40%'>Jumlah Anggaran Kegiatan Sebelum $nama_status</td>
                                 <td style='vertical-align:top;' width='20%' align='right'>$totp</td>
                                 <td colspan='4' align='right' style='vertical-align:top;' width='40%'>Jumlah Anggaran Kegiatan Setelah $nama_status</td>
                                 <td style='vertical-align:top;' width='20%' align='right'>$totp2</td>
                                 </tr>
                                 </table>";
  
        





    
    $data['prev']= $cRet;    
    $judul='RKA-rincian_belanja_'.$id.'';
    switch($cetak) { 
    case 1;
       // echo($cRet);
        $this->master_pdf->_mpdf_down($giat,$nm_giat,$cRet,10,10,10,'1');
    break;
    case 2;        
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename= $judul.xls");
        $this->load->view('anggaran/rka/perkadaII', $data);
    break;
    case 3;     
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Content-Type: application/vnd.ms-word");
        header("Content-Disposition: attachment; filename= $judul.doc");
        $this->load->view('anggaran/rka/perkadaII', $data);
    break;
    case 0; 

        //$this->master_pdf->_mpdf_margin('',$cRet,$kanan,$kiri,10,'1','',$atas,$bawah); 
        //echo ("<title>RKA Rincian Belanja</title>");
       echo($cRet);
    break;
    }
}
}
//end anyar


function preview_rincian_belanja_skpd_pergeseran2(){

            $id = $this->uri->segment(3);
            $giat = $this->uri->segment(4);
            $cetak = $this->uri->segment(5);
            $atas = $this->uri->segment(6);
            $bawah = $this->uri->segment(7);
            $kiri = $this->uri->segment(8);
            $kanan = $this->uri->segment(9);
            $status1 = $this->uri->segment(10);
            $status2 = $this->uri->segment(11);
            
            
     

            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
     
  
     
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        $tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                        $thnl =$thn-1;
                        $thnd =$thn+1; 
                    }
           $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(id_ttd, ' ', 'a')='$ttd1' )  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip; 
                        $pangkat=$rowttd->pangkat;
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        //$jabatan  = str_replace('Kuasa Pengguna Anggaran','',$jabatan);
                        if($jabatan=='Kuasa Pengguna Anggaran'){
                            $kuasa="";
                        }else{
                            $kuasa="Kuasa Pengguna Anggaran";
                        }
                        
                    
                    }
                  
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(id_ttd, ' ', 'a')='$ttd2')  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip;
                        $pangkat2=$rowttd2->pangkat;
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
           
                        
                        if($jabatan2=='Pengguna Anggaran'){
                            $kuasa2="";
                        }else{
                            $kuasa2="Pengguna Anggaran";
                        }
                    }

            $judul_dpa="DOKUMEN PELAKSANAAN ANGGARAN";
            $kd_dpa="DPA";
            $nama_status="Pergeseran";

            if($status1=='nilai'){
                $status_anggaran1="";
                $status_speksifikasi1="";
                $status_harga1="";
                $status_satuan1="";
                $status_volume1="";
            }else if($status1=='nilai_sempurna'){
                $status_anggaran1="_sempurna";
                $status_speksifikasi1="_sempurna";
                $status_harga1="_sempurna1";
                $status_satuan1="_sempurna1";
                $status_volume1="_sempurna1";
            }else{
                $status_anggaran1="_ubah";
                $status_speksifikasi1="";
                $status_harga1="_ubah";
                $status_satuan1="_ubah";
                $status_volume1="_ubah";
            }

            if($status2=='nilai'){
                $status_anggaran2="";
                $status_speksifikasi2="";
                $status_harga2="";
                $status_satuan2="";
                $status_volume2="";
            }else if($status2=='nilai_sempurna'){
                $status_anggaran2="_sempurna";
                $status_speksifikasi2="_sempurna";
                $status_harga2="_sempurna1";
                $status_satuan2="_sempurna1";
                $status_volume2="_sempurna1";
            }else{
                $status_anggaran2="_ubah";
                $status_speksifikasi2="_ubah";
                $status_harga2="_ubah";
                $status_satuan2="_ubah";
                $status_volume2="_ubah";
                $judul_dpa="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                $kd_dpa="DPPA";
                $nama_status="Perubahan";
            }

             $sqlorgg="SELECT * FROM ms_organisasi WHERE kd_org=left('$id',17)";
                 $sqlorgg1=$this->db->query($sqlorgg);
                 foreach ($sqlorgg1->result() as $rowdns)
                {
                   
                    $kd_org=$rowdns->kd_org;                    
                    $nm_org= $rowdns->nm_org;
                }




            $sqlorg="SELECT top 1 * FROM (
SELECT g.kd_urusan,g.nm_urusan,f.kd_bidang_urusan,f.nm_bidang_urusan,left(a.kd_gabungan,22) kd_skpd ,e.nm_skpd,a.kd_program,a.nm_program,
a.sasaran_program,a.capaian_program,c.kd_kegiatan,c.nm_kegiatan,a.kd_sub_kegiatan,a.nm_sub_kegiatan,SUM(d.nilai) AS nilai,a.tu_capai_sempurna tu_capai, 
a.tu_mas_sempurna tu_mas, a.tu_kel_sempurna tu_kel, a.tu_has_sempurna tu_has, a.tk_capai_sempurna tk_capai, a.tk_mas_sempurna tk_mas, a.tk_kel_sempurna tk_kel, 
a.tk_has_sempurna tk_has,a.lokasi,d.sumber,a.kel_sasaran_kegiatan,a.ang_lalu FROM trskpd a 
INNER JOIN ms_kegiatan c ON a.kd_kegiatan= c.kd_kegiatan 
INNER JOIN trdrka d ON a.kd_kegiatan=left(d.kd_sub_kegiatan,12) 
INNER JOIN ms_skpd e ON a.kd_skpd=e.kd_skpd 
INNER JOIN ms_bidang_urusan f ON a.kd_bidang_urusan=f.kd_bidang_urusan 
INNER JOIN ms_urusan g ON left(a.kd_bidang_urusan,1)=g.kd_urusan
 where left(a.kd_gabungan,22)=left('$id',22) 
GROUP BY g.kd_urusan, g.nm_urusan, f.kd_bidang_urusan, f.nm_bidang_urusan, left(a.kd_gabungan,22) , e.nm_skpd, a.kd_program, a.nm_program, 
a.sasaran_program, a.capaian_program, c.kd_kegiatan, c.nm_kegiatan, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.tu_capai_sempurna, a.tu_mas_sempurna, a.tu_kel_sempurna,
 a.tu_has_sempurna, a.tk_capai_sempurna, a.tk_mas_sempurna, a.tk_kel_sempurna, a.tk_has_sempurna, a.lokasi, d.sumber, a.kel_sasaran_kegiatan, a.ang_lalu) OKE 
 where left(kd_sub_kegiatan,12)='$giat' and left(kd_skpd,22)=left('$id',22)
                    ";
                     $sqlorg1=$this->db->query($sqlorg);
                     foreach ($sqlorg1->result() as $roworg)
                    {
                        $kd_urusan=$roworg->kd_urusan;                    
                        $nm_urusan= $roworg->nm_urusan;
                        $kd_bidang_urusan=$roworg->kd_bidang_urusan;                    
                        $nm_bidang_urusan= $roworg->nm_bidang_urusan;
                        $kd_skpd  = $roworg->kd_skpd;
                        $nm_skpd  = $roworg->nm_skpd;
                        $kd_prog  = $roworg->kd_program;
                        $nm_prog  = $roworg->nm_program;
                        $sasaran_prog  = $roworg->sasaran_program;
                        $capaian_prog  = $roworg->capaian_program;
                        $kd_giat  = $roworg->kd_kegiatan;
                        $nm_giat  = $roworg->nm_kegiatan;
                        $lokasi  = $roworg->lokasi;
                        $tu_capai  = $roworg->tu_capai;
                        $tu_mas  = $roworg->tu_mas;
                        $tu_kel  = $roworg->tu_kel;
                        $tu_has  = $roworg->tu_has;
                        $tk_capai  = $roworg->tk_capai;
                        $tk_mas  = $roworg->tk_mas;
                        $tk_kel  = $roworg->tk_kel;
                        $tk_has  = $roworg->tk_has;
                        $sas_giat = $roworg->kel_sasaran_kegiatan;
                        $ang_lalu = $roworg->ang_lalu;
                    }
            $kd_urusan= empty($roworg->kd_urusan) || ($roworg->kd_urusan) == '' ? '' : ($roworg->kd_urusan);
            $nm_urusan= empty($roworg->nm_urusan) || ($roworg->nm_urusan) == '' ? '' : ($roworg->nm_urusan);
            $kd_bidang_urusan= empty($roworg->kd_bidang_urusan) || ($roworg->kd_bidang_urusan) == '' ? '' : ($roworg->kd_bidang_urusan);
            $nm_bidang_urusan= empty($roworg->nm_bidang_urusan) || ($roworg->nm_bidang_urusan) == '' ? '' : ($roworg->nm_bidang_urusan);
            $kd_skpd= empty($roworg->kd_skpd) || ($roworg->kd_skpd) == '' ? '' : ($roworg->kd_skpd);
            $nm_skpd= empty($roworg->nm_skpd) || ($roworg->nm_skpd) == '' ? '' : ($roworg->nm_skpd);
            $kd_prog= empty($roworg->kd_program) || ($roworg->kd_program) == '' ? '' : ($roworg->kd_program);
            $nm_prog= empty($roworg->nm_program) || ($roworg->nm_program) == '' ? '' : ($roworg->nm_program);
            $sasaran_prog= empty($roworg->sasaran_program) || ($roworg->sasaran_program) == '' ? '' : ($roworg->sasaran_program);
            $capaian_prog= empty($roworg->capaian_program) || ($roworg->capaian_program) == '' ? '' : ($roworg->capaian_program);
            $kd_giat= empty($roworg->kd_kegiatan) || ($roworg->kd_kegiatan) == '' ? '' : ($roworg->kd_kegiatan);
            $nm_giat= empty($roworg->nm_kegiatan) || ($roworg->nm_kegiatan) == '' ? '' : ($roworg->nm_kegiatan);
            $lokasi= empty($roworg->lokasi) || ($roworg->lokasi) == '' ? '' : ($roworg->lokasi);
            $tu_capai= empty($roworg->tu_capai) || ($roworg->tu_capai) == '' ? '' : ($roworg->tu_capai);
            $tu_mas= empty($roworg->tu_mas) || ($roworg->tu_mas) == '' ? '' : ($roworg->tu_mas);
            $tu_kel= empty($roworg->tu_kel) || ($roworg->tu_kel) == '' ? '' : ($roworg->tu_kel);
            $tu_has= empty($roworg->tu_has) || ($roworg->tu_has) == '' ? '' : ($roworg->tu_has);
            $tk_capai= empty($roworg->tk_capai) || ($roworg->tk_capai) == '' ? '' : ($roworg->tk_capai);
            $tk_mas= empty($roworg->tk_mas) || ($roworg->tk_mas) == '' ? '' : ($roworg->tk_mas);
            $tk_kel= empty($roworg->tk_kel) || ($roworg->tk_kel) == '' ? '' : ($roworg->tk_kel);
            $tk_has= empty($roworg->tk_has) || ($roworg->tk_has) == '' ? '' : ($roworg->tk_has);
            $sas_giat= empty($roworg->kel_sasaran_kegiatan) || ($roworg->kel_sasaran_kegiatan) == '' ? '' : ($roworg->kel_sasaran_kegiatan);
            $ang_lalu= empty($roworg->ang_lalu) || ($roworg->ang_lalu) == '' || ($roworg->ang_lalu) == 'Null' ? 0 : ($roworg->ang_lalu);

            $sqltp="SELECT SUM(nilaisempurna1) AS totb, SUM(nilaisempurna2) AS totb2 FROM trdrka WHERE left(kd_sub_kegiatan,12)='$giat' AND left(kd_skpd,22)=left('$id',22)";
                     $sqlb=$this->db->query($sqltp);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $totp  =number_format($rowb->totb,"2",",",".");
                       $totp1 =number_format($rowb->totb*1.1,"2",",",".");
                       $totp2  =number_format($rowb->totb2,"2",",",".");
                       $totp12 =number_format($rowb->totb2*1.1,"2",",",".");
                    }
                    
            $nodpa=$this->db->query("SELECT no_dpa_sempurna as no_dpa from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $cRet='';
            $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                        <tr> 
                             <td width='80%' align='center'><strong>$judul_dpa <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width='20%' rowspan='2' align='center'><strong> FORMULIR <br /> $kd_dpa - $judul_dpa SKPD    
      </strong></td>
                        </tr>
                        <tr>
                             <td style='vertical-align:top;' align='center'><strong>$kab</strong> <br /><strong>TAHUN ANGGARAN $thn</strong></td>
                        </tr>
                      </table>";
                      
            $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0'>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;No DPA</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$nodpa</td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'></td>
                            </tr>                            
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Urusan Pemerintahan</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_urusan</td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'>$nm_urusan</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Bidang Urusan</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_bidang_urusan </td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'> $nm_bidang_urusan</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_prog</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_prog</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Sasaran Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$sasaran_prog</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Capaian Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$tu_capai - $tk_capai</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Kegiatan</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_giat</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_giat</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Organisasi</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_org</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_org</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Unit Organisasi</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_skpd</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnl</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2'  align='left' style='vertical-align:top;border-left: none;'>Rp. ".number_format($ang_lalu,"2",",",".")." (".$this->rka_model->terbilang($ang_lalu*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thn</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp (".$this->rka_model->terbilang($rowb->totb*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnd</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp1 (".$this->rka_model->terbilang($rowb->totb*1.1)." rupiah)</td>
                            </tr>
                            <tr>
                        <td colspan='4'  width='100%' align='left'>&nbsp;</td>
                    </tr>
                        </table>    
                            
                        ";
            $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1' cellspacing='2' cellpadding='5'>
                        <tr>
                            <td colspan='5'  align='center' >Indikator & Tolak Ukur Kinerja Kegiatan</td>
                        </tr>";
            $cRet .="<tr>
                     <td  rowspan='2' align='center'>Indikator </td>
                     <td  colspan='2' align='center'>Sebelum $nama_status </td>
                     <td  colspan='2' align='center'>Setelah $nama_status </td>
                    </tr>"; 
            $cRet .="<tr>
                     <td  align='center'>Tolak Ukur Kerja </td>
                     <td  align='center'>Target Kinerja </td>
                     <td  align='center'>Tolak Ukur Kerja </td>
                     <td  align='center'>Target Kinerja </td>
                    </tr>";          

            $cRet .=" <tr align='center'>
                        <td >Capaian Kegiatan </td>
                        <td>$tu_capai</td>
                        <td>$tk_capai</td>
                        <td>$tu_capai</td>
                        <td>$tk_capai</td>
                     </tr>";
            $cRet .=" <tr align='center'>
                        <td>Masukan </td>
                        <td>Dana yang dibutuhkan</td>
                        <td>Rp. $totp</td>
                        <td>Dana yang dibutuhkan</td>
                        <td>Rp. $totp2</td>
                    </tr>";
            $cRet .=" <tr align='center'>
                        <td>Keluaran </td>
                        <td>$tu_kel</td>
                        <td>$tk_kel</td>
                        <td>$tu_kel</td>
                        <td>$tk_kel</td>
                      </tr>";
            $cRet .=" <tr align='center'>
                        <td>Hasil </td>
                        <td>$tu_has</td>
                        <td>$tk_has</td>
                        <td>$tu_has</td>
                        <td>$tk_has</td>
                      </tr>";
            $cRet .= "<tr>
                        <td colspan='5'  align='left'>Kelompok Sasaran Kegiatan : $sas_giat</td>
                    </tr>";
            $cRet .= "<tr>
                        <td colspan='5' align='left'>&nbsp;</td>
                    </tr>"; 
                    $cRet .= "<tr>
                        <td colspan='5' bgcolor='#CCCCCC' align='left'>&nbsp;</td>
                    </tr>";                
            
            $cRet .= "<tr>
                            <td colspan='5' align='center'>RINCIAN ANGGARAN BELANJA KEGIATAN SATUAN KERJA PERANGKAT DAERAH</td>
                      </tr>";
                        
            $cRet .="</table>";
    //rincian sub kegiatan
         

                   $sqlsub="SELECT id_skpd, id_sub_kegiatan, left(a.kd_gabungan,22) skpd, a.nm_skpd, a.kd_sub_kegiatan as kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2 FROM trskpd a
                    
                    WHERE left(a.kd_sub_kegiatan,12)='$giat' AND left(a.kd_skpd,22)=left('$id',22)
                    group by left(a.kd_gabungan,22) ,a.nm_skpd, a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2,id_skpd, id_sub_kegiatan";
                     $sqlbsub=$this->db->query($sqlsub);
                     foreach ($sqlbsub->result() as $rowsub)
                    {
                       $sub         =$rowsub->kd_sub_kegiatan;
                       $nm_sub      =$rowsub->nm_sub_kegiatan;
                       $sub_keluaran=$rowsub->sub_keluaran;
                       $lokasi      =$rowsub->lokasi;
                       $skpd        =$rowsub->skpd;
                       $id_skpd     =$rowsub->id_skpd;
                       $id_sub_kegiatan     =$rowsub->id_sub_kegiatan;
                       $nm_skpd     =$rowsub->nm_skpd;
                       $waktu_giat  =$rowsub->waktu_giat;
                       $waktu_giat2 =$rowsub->waktu_giat2;
                       $keterangan  ="";

                   
                     /*$kodesumberdana=$this->db->query("SELECT top 1 sumber+' '+isnull(sumber2,'')+' '+isnull(sumber3,'')+' '+isnull(sumber4,'') as sumber from trdrka where kd_sub_kegiatan='$sub' and kd_skpd='$id'
                        union all 
                        select ''"); 
                     foreach($kodesumberdana->result() as $oke){
                        $sumbr=$oke->sumber;
                     }*/
                     $sqlsumber="SELECT kd_sumberdana,sumber,nm_sumberdana FROM v_sumber1_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber1  = $rowsumber->nm_sumberdana;
                        $kdsumber1  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber2 FROM v_sumber2_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber2  = $rowsumber->sumber2;
                        $kdsumber2  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber3 FROM v_sumber3_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber3  = $rowsumber->sumber3;
                        $kdsumber3  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber4 FROM v_sumber4_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber4  = $rowsumber->sumber4;
                        $kdsumber4  = $rowsumber->kd_sumberdana;
                        
                    }

                    if ($kdsumber2==''){
                        $kodesumberdana=$nmsumber1;
                    }else if ($kdsumber2==''){
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2;                    
                    }else if($kdsumber3==''){
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2.'<br />'.$nmsumber3;
                    }else{
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2.'<br />'.$nmsumber3.'<br />'.$nmsumber4;    
                    }
                        $cRet .="
                        
                        <table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                            <tr>
                                <td width style='tical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sub Kegiatan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$sub - $nm_sub</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sumber Pendanaan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$kodesumberdana</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Lokasi</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'><table  style='border-collapse:collapse;font-size:12px'>"; 
                                        
                                           
                                            $cRet.="<tr><td>$lokasi </td></tr>";
                                        

                        $cRet.="</table></td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Waktu Pelaksanaan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'> $waktu_giat</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-left: solid 1px black;border-bottom: solid 1px black;' align='left'>&nbsp;Keluaran Sub Kegiatan</td>
                                <td align='center' style='vertical-align:top;border-bottom: solid 1px black;'>:</td>
                                <td align='left' colspan='3' style='vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;'>
                                       
                                        <table  style='border-collapse:collapse;font-size:12px'>"; 
                                        $okeii=$this->db->query("SELECT * from sipd_output where id_sub_skpd='$id_skpd' and id_sub_giat='$id_sub_kegiatan'");
                                        foreach($okeii->result() as $ac){
                                            $oke=$ac->outputteks;
                                            $jiwa=$ac->targetoutputteks;
                                            $cRet.="<tr><td>$oke - $jiwa</td></tr>";
                                        }

                        $cRet.="</table>
                                </td>
                            </tr>
                            </table>
                            
                        ";

                        $cRet .= "<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'>
                              <thead>
                            <tr>
                                <td rowspan='3' bgcolor='#CCCCCC' align='center'><b>Kode Rekening</b></td>                            
                                <td rowspan='3' bgcolor='#CCCCCC' align='center'><b>Uraian</b></td>
                                <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Sebelum $nama_status</b></td>
                                <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Setelah $nama_status</b></td>
                                <td colspan='2' rowspan='2' bgcolor='#CCCCCC' align='center'><b>Bertambah/(Berkurang)</b></td>
                            </tr>                 
                            <tr>
                                <td colspan='4' bgcolor='#CCCCCC' align='center'><b>Rincian Perhitungan</b></td>
                                <td rowspan='2' bgcolor='#CCCCCC' align='center'><b>Jumlah(Rp.)</b></td>
                                <td colspan='4' bgcolor='#CCCCCC' align='center'><b>Rincian Perhitungan</b></td>
                                <td rowspan='2' bgcolor='#CCCCCC' align='center'><b>Jumlah(Rp.)</b></td>
                            </tr>
                            <tr>
                                <td bgcolor='#CCCCCC' align='center'>Koefisien</td>
                                <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                                <td bgcolor='#CCCCCC' align='center'>Harga</td>
                                <td bgcolor='#CCCCCC' align='center'>PPN</td>
                                <td bgcolor='#CCCCCC' align='center'>Koefisien</td>
                                <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                                <td bgcolor='#CCCCCC' align='center'>Harga</td>
                                <td bgcolor='#CCCCCC' align='center'>PPN</td>
                                <td bgcolor='#CCCCCC' align='center'>Rp</td>
                                <td bgcolor='#CCCCCC' align='center'>%</td>
                            </tr>    
                         
                        </thead> 
                         
                           
                            ";

                            $sql1="SELECT * FROM(SELECT 1 header,0 no_po, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama, '' spek, '' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga,  '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilaisempurna1) AS nilai, SUM(a.nilaisempurna2) AS nilai2,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE a.kd_sub_kegiatan='$sub' AND left(a.no_trdrka,22)='$skpd' 
                                    GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
                                    UNION ALL 
                                    SELECT 1 header, 0 no_po,LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilaisempurna1) AS nilai, SUM(a.nilaisempurna2) AS nilai2,'2' AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
                                    UNION ALL  
                                    SELECT 1 header, 0 no_po, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan, 0 AS harga,  '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilaisempurna1) AS nilai, SUM(a.nilaisempurna2) AS nilai2,'3' AS id FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
                                    UNION ALL 
                                    SELECT 1 header, 0 no_po, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilaisempurna1) AS nilai, SUM(a.nilaisempurna2) AS nilai2,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
                                    UNION ALL 
                                    SELECT 1 header, 0 no_po, LEFT(a.kd_rek6,8) AS rek1,RTRIM(LEFT(a.kd_rek6,8)) AS rek,b.nm_rek5 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilaisempurna1) AS nilai, SUM(a.nilaisempurna2) AS nilai2,'5' AS id FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5
                                    UNION ALL
                                    SELECT 1 header, 0 no_po, a.kd_rek6 AS rek1,RTRIM(a.kd_rek6) AS rek,b.nm_rek6 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilaisempurna1) AS nilai, SUM(a.nilaisempurna2) AS nilai2,'6' AS id FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY a.kd_rek6,b.nm_rek6
                                    UNION ALL
                                    
SELECT b.header,b.no_po as no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien, 0 AS volume, ' ' AS satuan, 
0 AS harga,'' koefisien2, 
 0 AS volume,'' AS satuan, 0 AS harga,SUM(c.total_sempurna1) AS nilai,SUM(c.total_sempurna2) AS nilai2,'7' AS id FROM trdpo a 
 LEFT JOIN trdpo b ON b.id=a.header_id AND b.header ='1' AND a.no_trdrka=b.no_trdrka 
 inner join trdpo c on a.id=c.sub_header_id and a.no_trdrka=c.no_trdrka 
WHERE LEFT(a.no_trdrka,22)='$skpd' AND SUBSTRING(a.no_trdrka,24,15)='$sub' and b.header='1' 
GROUP BY RIGHT(a.no_trdrka,12),b.header, b.no_po,b.uraian
UNION ALL

SELECT b.header,b.no_po as no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien, 0 AS volume, ' ' AS satuan, 
0 AS harga,'' koefisien2, 
 0 AS volume,'' AS satuan, 0 AS harga,SUM(a.total_sempurna1) AS nilai,SUM(a.total_sempurna2) AS nilai2,'7' AS id 
 FROM trdpo a 
LEFT JOIN trdpo b ON b.id=a.sub_header_id AND b.header ='1' AND a.no_trdrka=b.no_trdrka 
WHERE LEFT(a.no_trdrka,22)='$skpd' AND SUBSTRING(a.no_trdrka,24,15)='$sub' and b.header='1'
GROUP BY RIGHT(a.no_trdrka,12),b.header, b.no_po,b.uraian 

UNION ALL
SELECT a. header,a.no_po as no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,a.uraian AS nama,spesifikasi as spek,
 a.koefisien_sempurna koefisien1, a.volume_sempurna1 AS volume,a.satuan_sempurna1 AS satuan, a.harga_sempurna1 AS harga,a.tkoefisien_sempurna2 koefisien2, 
 a.volume_sempurna21 AS volume,a.satuan_sempurna21 AS satuan, harga_sempurna2 AS harga, a.total_sempurna1 AS nilai, a.total_sempurna2 AS nilai2 ,'9' AS id FROM trdpo a 
WHERE LEFT(a.no_trdrka,22)='$skpd' AND SUBSTRING(no_trdrka,24,15)='$sub'   AND (header='0' or header is null)
) a ORDER BY a.rek1, a.no_po";
                     
                    $query = $this->db->query($sql1);
                    $nilangsub=0;
                    $nilangsub2=0;

                            foreach ($query->result() as $row)
                            {
                                $rek=$row->rek;
                                $reke=$this->support->dotrek($rek);
                                $uraian=$row->nama;
                                $spek_komp=$row->spek;
                                $koefisien=$row->koefisien;
                                //$spek_komp2=$row->spek2;
                                $koefisien2=$row->koefisien2;

                            //    $volum=$row->volume;
                                $sat=$row->satuan;
                                $sat2=$row->satuan2;
                                $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                                $volum= empty($row->volume) || $row->volume == 0 ? '' :$row->volume;
                                $hrg2= empty($row->harga2) || $row->harga2 == 0 ? '' :number_format($row->harga2,2,',','.');
                                $volum2= empty($row->volume2) || $row->volume == 0 ? '' :$row->volume2;

                                //$hrg=number_format($row->harga,"2",".",",");
                                $nila= empty($row->nilai) || $row->nilai == 0 ? '' :number_format($row->nilai,2,',','.');
                                $nila2= empty($row->nilai2) || $row->nilai2 == 0 ? '' :number_format($row->nilai2,2,',','.');

                                $selisih= $this->support->rp_minus($row->nilai2-$row->nilai);
                                if($row->nilai==0){
                                    $persen=0;
                                }else{
                                    $persen= $this->support->rp_minus((($row->nilai2-$row->nilai)/$row->nilai)*100);
                                }
                                

                                        
                                
                                if ($row->header=='1'){
                                    $ppn='';
                                    
                               
                                 $cRet    .= " <tr>
                                                 <td ><b>$reke</b></td>                                     
                                                 <td  ><b>$uraian</b></td>
                                                 <td  ><b>$koefisien</b></td>
                                                 <td  ><b>$sat</b></td>
                                                 <td  ><b>$hrg</b></td>
                                                 <td  ><b>$ppn</b></td>
                                                 <td  align='right'><b>$nila</b></td>
                                                 <td ><b>$koefisien</b></td>                                     
                                                 <td  ><b>$sat2</b></td>
                                                 <td  ><b>$hrg2</b></td>
                                                 <td  ><b>$ppn</b></td>
                                                 <td  align='right'><b>$nila2</b></td>
                                                 <td  align='right'><b>$selisih</b></td>
                                                 <td  align='center'><b>$persen</b></td>
                                             </tr>
                                                 ";

                                             }else{
                                                $ppn=0;
                                                $ppn2=0;

                                                $cRet    .= " <tr>
                                                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;'  align='left'>$reke</td>                                     
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' >$uraian <br>&nbsp;&nbsp;&nbsp; $spek_komp </td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$koefisien</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'>$sat</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$hrg</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'><b>$ppn</b></td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$nila</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='left'>".$koefisien2."</td>                                     
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' > ".$sat2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$hrg2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$ppn2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$nila2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$selisih</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'>$persen</td>
                                                        </tr>
                                                 ";
                                                 $nilangsub= $nilangsub+$row->nilai;
                                                 $nilangsub2= $nilangsub2+$row->nilai2;
                                                 $selisih=$this->support->rp_minus($nilangsub2-$nilangsub);
                                                 if($nilangsub==0){
                                                   $persen=0;
                                                 }else{
                                                 $persen=$this->support->rp_minus((($nilangsub2-$nilangsub)/$nilangsub)*100);
                                                }       
                                             }
                                             
                            }

                            $cRet    .=" 
                                        <tr>                                    
                                         <td colspan='6' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >Jumlah Anggaran Sub Kegiatan Sebelum $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format($nilangsub,2,',','.')."</td>
                                         <td colspan='4' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >Jumlah Anggaran Sub Kegiatan Setelah $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format($nilangsub2,2,',','.')."</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".$selisih."</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='center'>".$persen."</td>
                                         </tr>
                                                                                  
                                         <tr>                                    
                                         <td colspan='14'  align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;'>&nbsp;</td>
                                         </tr>
                                         </table> ";
                    }

                    


                            $cRet    .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'> 
                                        
                                         <tr>                                    
                                         <td colspan='5' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'>Jumlah Anggaran Kegiatan Sebelum $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$totp</td>
                                         <td colspan='4' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'>Jumlah Anggaran Kegiatan Setelah $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$totp2</td>
                                         </tr>
                                         </table>";
          
                

        if($ttd1!='tanpa'){
            $sqlttd1="SELECT top 1  nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' union all select '','','','' ORDER by nip";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama</u><br>
                                $nip
                                <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                            Drs. ALFIAN, MM</u><br/>
                                            NIP. 196602101986031011
                        
                                </td>";
              
        }else{
            $tambahan="";
        }
                
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd kd_skpd , sum(nilai_sempurna2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' and left(kd_sub_kegiatan,len('$giat'))='$giat' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $sql="SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd kd_skpd , sum(nilai_sempurna2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' and left(kd_sub_kegiatan,len('$giat'))='$giat' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ";
                $res = $this->db->query($sql);
                if ($res->num_rows()==0){
                $ang_jan=0;
                $ang_feb=0;
                $ang_mar=0;
                $ang_apr=0;
                $ang_mei=0;
                $ang_jun=0;
                $ang_jul=0;
                $ang_ags=0;
                $ang_sep=0;
                $ang_okt=0;
                $ang_nov=0;
                $ang_des=0;
                }else{
                    $ang_jan=$angkas5->jan;
                $ang_feb=$angkas5->feb;
                $ang_mar=$angkas5->mar;
                $ang_apr=$angkas5->apr;
                $ang_mei=$angkas5->mei;
                $ang_jun=$angkas5->jun;
                $ang_jul=$angkas5->jul;
                $ang_ags=$angkas5->ags;
                $ang_sep=$angkas5->sept;
                $ang_okt=$angkas5->okt;
                $ang_nov=$angkas5->nov;
                $ang_des=$angkas5->des;
                }
                
                
                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($ang_jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($ang_feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($ang_mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($ang_apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($ang_mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($ang_jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($ang_jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($ang_ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($ang_sep,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($ang_okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($ang_nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($ang_des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%' align='right'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($ang_des+$ang_nov+$ang_jan+$ang_feb+$ang_mar+$ang_apr+$ang_mei+$ang_jun+$ang_jul+$ang_ags+$ang_sep+$ang_okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";
                     $cRet .= "<table style=\"border-collapse:collapse;font-size:25px;border-top: solid 1px black;border-bottom: solid 1px black;border-right: solid 1px black;border-left: solid 1px black;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                    <tr>
                        
                        <td style=\"border-left: solid 1px black;border-bottom: solid 1px black;\" width=\"60%\" colspan=\"6\" align=\"center\">TIM ANGGARAN PEMERINTAH DAERAH</td>
                    </tr>
                    <tr>
                        
                        <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">N0</td>
                        <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\" width=\"35%\" align=\"center\">Nama</td>
                        <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"center\">NIP</td>
                        <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\" width=\"30%\" align=\"center\">JABATAN</td>
                        <td style=\"border-bottom: solid 1px black;\" width=\"10%\" align=\"center\" colspan=\"2\">TANDA TANGAN</td>
                    </tr>
                    <tr>
                        
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" align=\"center\">1</td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" ></td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" align=\"center\"></td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" ></td>
                        <td style=\"vertical-align:top;border-bottom: solid 1px black;\" width=\"10%\"></td>
                        <td style=\"vertical-align:top;border-bottom: solid 1px black;\"width=\"10%\"></td>

                    </tr>
                    <tr>
                        
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" align=\"center\">2</td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" ></td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" align=\"center\"></td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" ></td>
                        <td style=\"vertical-align:top;border-bottom: solid 1px black;\" width=\"10%\"></td>
                        <td style=\"vertical-align:top;border-bottom: solid 1px black;\"width=\"10%\"></td>

                    </tr>
                    <tr>
                        
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" align=\"center\">3</td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" ></td>
                        <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\" ></td>
                        <td style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\" ></td>
                        <td style=\"vertical-align:top;border-bottom: solid 1px black;\" width=\"10%\"></td>
                        <td style=\"vertical-align:top;border-bottom: solid 1px black;\"width=\"10%\"></td>

                    </tr>
                    <tr>
                        
                        <td  style=\"border-right: solid 1px black;\" align=\"center\">4</td>
                        <td style=\"border-right: solid 1px black;\" ></td>
                        <td style=\"border-right: solid 1px black;\" ></td>
                        <td style=\"border-right: solid 1px black;\" ></td>
                        <td ></td>
                        <td ></td>

                    </tr>
                    <tr>
                        
                        <td  style=\"border-right: solid 1px black;\" align=\"center\"></td>
                        <td style=\"border-right: solid 1px black;\" ></td>
                        <td style=\"border-right: solid 1px black;\"></td>
                        <td  style=\"border-right: solid 1px black;\"></td>
                        <td ></td>
                        <td ></td>

                    </tr>

                  ";
            $cRet .= "</table>";



            
            $data['prev']= $cRet;    
            $judul='RKA-rincian_belanja_'.$id.'';
            switch($cetak) { 
            case 1;
               // echo($cRet);
                $this->support->_mpdf2($giat,$nm_giat,$cRet,10,10,10,'1');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0; 

                //$this->master_pdf->_mpdf_margin('',$cRet,$kanan,$kiri,10,'1','',$atas,$bawah); 
                //echo ("<title>RKA Rincian Belanja</title>");
               echo($cRet);
            break;
            }
        }

function preview_rincian_belanja_skpd_pergeseran11(){

            $id = $this->uri->segment(3);
            $giat = $this->uri->segment(4);
            $cetak = $this->uri->segment(5);
            $atas = $this->uri->segment(6);
            $bawah = $this->uri->segment(7);
            $kiri = $this->uri->segment(8);
            $kanan = $this->uri->segment(9);
            $status1 = $this->uri->segment(10);
            $status2 = $this->uri->segment(11);
            
            
     

            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
     
  
     
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        $tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                        $thnl =$thn-1;
                        $thnd =$thn+1; 
                    }
           $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(id_ttd, ' ', 'a')='$ttd1' )  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip; 
                        $pangkat=$rowttd->pangkat;
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        //$jabatan  = str_replace('Kuasa Pengguna Anggaran','',$jabatan);
                        if($jabatan=='Kuasa Pengguna Anggaran'){
                            $kuasa="";
                        }else{
                            $kuasa="Kuasa Pengguna Anggaran";
                        }
                        
                    
                    }
                  
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(id_ttd, ' ', 'a')='$ttd2')  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip;
                        $pangkat2=$rowttd2->pangkat;
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
           
                        
                        if($jabatan2=='Pengguna Anggaran'){
                            $kuasa2="";
                        }else{
                            $kuasa2="Pengguna Anggaran";
                        }
                    }

            $judul_dpa="DOKUMEN PELAKSANAAN ANGGARAN";
            $kd_dpa="DPA";
            $nama_status="Pergeseran";

            if($status1=='nilai'){
                $status_anggaran1="";
                $status_speksifikasi1="";
                $status_harga1="";
                $status_satuan1="";
                $status_volume1="";
            }else if($status1=='nilai_sempurna'){
                $status_anggaran1="_sempurna";
                $status_speksifikasi1="_sempurna";
                $status_harga1="_sempurna1";
                $status_satuan1="_sempurna1";
                $status_volume1="_sempurna1";
            }else{
                $status_anggaran1="_ubah";
                $status_speksifikasi1="";
                $status_harga1="_ubah";
                $status_satuan1="_ubah";
                $status_volume1="_ubah";
            }

            if($status2=='nilai'){
                $status_anggaran2="";
                $status_speksifikasi2="";
                $status_harga2="";
                $status_satuan2="";
                $status_volume2="";
            }else if($status2=='nilai_sempurna'){
                $status_anggaran2="_sempurna";
                $status_speksifikasi2="_sempurna";
                $status_harga2="_sempurna1";
                $status_satuan2="_sempurna1";
                $status_volume2="_sempurna1";
            }else{
                $status_anggaran2="_ubah";
                $status_speksifikasi2="_ubah";
                $status_harga2="_ubah";
                $status_satuan2="_ubah";
                $status_volume2="_ubah";
                $judul_dpa="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                $kd_dpa="DPPA";
                $nama_status="Perubahan";
            }



            $sqlorg="SELECT top 1 * FROM (SELECT g.kd_urusan,g.nm_urusan,f.kd_bidang_urusan,f.nm_bidang_urusan,left(a.kd_gabungan,22) kd_skpd ,e.nm_skpd,a.kd_program,a.nm_program,a.sasaran_program,a.capaian_program,c.kd_kegiatan,c.nm_kegiatan,a.kd_sub_kegiatan,a.nm_sub_kegiatan,SUM(d.nilai) AS nilai,a.tu_capai,
                a.tu_mas,
                a.tu_kel,
                a.tu_has,
                a.tk_capai,
                a.tk_mas,
                a.tk_kel,
                a.tk_has,a.lokasi,d.sumber,a.kel_sasaran_kegiatan,a.ang_lalu FROM trskpd a 
                INNER JOIN ms_kegiatan c ON a.kd_kegiatan=
                c.kd_kegiatan
                INNER JOIN trdrka d ON a.kd_kegiatan=left(d.kd_sub_kegiatan,12)
                
                INNER JOIN ms_skpd e ON a.kd_skpd=e.kd_skpd
                INNER JOIN ms_bidang_urusan f ON a.kd_bidang_urusan=f.kd_bidang_urusan
                INNER JOIN ms_urusan g ON left(a.kd_bidang_urusan,1)=g.kd_urusan
                where  left(a.kd_gabungan,22)=left('$id',22)
                GROUP BY 
                g.kd_urusan,
                g.nm_urusan,
                f.kd_bidang_urusan,
                f.nm_bidang_urusan,
                left(a.kd_gabungan,22) ,
                e.nm_skpd,
                a.kd_program,
                a.nm_program,
                a.sasaran_program,
                a.capaian_program,
                c.kd_kegiatan,
                c.nm_kegiatan,
                a.kd_sub_kegiatan,
                a.nm_sub_kegiatan,
                a.tu_capai,
                a.tu_mas,
                a.tu_kel,
                a.tu_has,
                a.tk_capai,
                a.tk_mas,
                a.tk_kel,
                a.tk_has,
                a.lokasi,
                d.sumber,
                a.kel_sasaran_kegiatan,
                a.ang_lalu) OKE
                                    where left(kd_sub_kegiatan,12)='$giat' and left(kd_skpd,22)=left('$id',22)
                    ";
                     $sqlorg1=$this->db->query($sqlorg);
                     foreach ($sqlorg1->result() as $roworg)
                    {
                        $kd_urusan=$roworg->kd_urusan;                    
                        $nm_urusan= $roworg->nm_urusan;
                        $kd_bidang_urusan=$roworg->kd_bidang_urusan;                    
                        $nm_bidang_urusan= $roworg->nm_bidang_urusan;
                        $kd_skpd  = $roworg->kd_skpd;
                        $nm_skpd  = $roworg->nm_skpd;
                        $kd_prog  = $roworg->kd_program;
                        $nm_prog  = $roworg->nm_program;
                        $sasaran_prog  = $roworg->sasaran_program;
                        $capaian_prog  = $roworg->capaian_program;
                        $kd_giat  = $roworg->kd_kegiatan;
                        $nm_giat  = $roworg->nm_kegiatan;
                        $lokasi  = $roworg->lokasi;
                        $tu_capai  = $roworg->tu_capai;
                        $tu_mas  = $roworg->tu_mas;
                        $tu_kel  = $roworg->tu_kel;
                        $tu_has  = $roworg->tu_has;
                        $tk_capai  = $roworg->tk_capai;
                        $tk_mas  = $roworg->tk_mas;
                        $tk_kel  = $roworg->tk_kel;
                        $tk_has  = $roworg->tk_has;
                        $sas_giat = $roworg->kel_sasaran_kegiatan;
                        $ang_lalu = $roworg->ang_lalu;
                    }
            $kd_urusan= empty($roworg->kd_urusan) || ($roworg->kd_urusan) == '' ? '' : ($roworg->kd_urusan);
            $nm_urusan= empty($roworg->nm_urusan) || ($roworg->nm_urusan) == '' ? '' : ($roworg->nm_urusan);
            $kd_bidang_urusan= empty($roworg->kd_bidang_urusan) || ($roworg->kd_bidang_urusan) == '' ? '' : ($roworg->kd_bidang_urusan);
            $nm_bidang_urusan= empty($roworg->nm_bidang_urusan) || ($roworg->nm_bidang_urusan) == '' ? '' : ($roworg->nm_bidang_urusan);
            $kd_skpd= empty($roworg->kd_skpd) || ($roworg->kd_skpd) == '' ? '' : ($roworg->kd_skpd);
            $nm_skpd= empty($roworg->nm_skpd) || ($roworg->nm_skpd) == '' ? '' : ($roworg->nm_skpd);
            $kd_prog= empty($roworg->kd_program) || ($roworg->kd_program) == '' ? '' : ($roworg->kd_program);
            $nm_prog= empty($roworg->nm_program) || ($roworg->nm_program) == '' ? '' : ($roworg->nm_program);
            $sasaran_prog= empty($roworg->sasaran_program) || ($roworg->sasaran_program) == '' ? '' : ($roworg->sasaran_program);
            $capaian_prog= empty($roworg->capaian_program) || ($roworg->capaian_program) == '' ? '' : ($roworg->capaian_program);
            $kd_giat= empty($roworg->kd_kegiatan) || ($roworg->kd_kegiatan) == '' ? '' : ($roworg->kd_kegiatan);
            $nm_giat= empty($roworg->nm_kegiatan) || ($roworg->nm_kegiatan) == '' ? '' : ($roworg->nm_kegiatan);
            $lokasi= empty($roworg->lokasi) || ($roworg->lokasi) == '' ? '' : ($roworg->lokasi);
            $tu_capai= empty($roworg->tu_capai) || ($roworg->tu_capai) == '' ? '' : ($roworg->tu_capai);
            $tu_mas= empty($roworg->tu_mas) || ($roworg->tu_mas) == '' ? '' : ($roworg->tu_mas);
            $tu_kel= empty($roworg->tu_kel) || ($roworg->tu_kel) == '' ? '' : ($roworg->tu_kel);
            $tu_has= empty($roworg->tu_has) || ($roworg->tu_has) == '' ? '' : ($roworg->tu_has);
            $tk_capai= empty($roworg->tk_capai) || ($roworg->tk_capai) == '' ? '' : ($roworg->tk_capai);
            $tk_mas= empty($roworg->tk_mas) || ($roworg->tk_mas) == '' ? '' : ($roworg->tk_mas);
            $tk_kel= empty($roworg->tk_kel) || ($roworg->tk_kel) == '' ? '' : ($roworg->tk_kel);
            $tk_has= empty($roworg->tk_has) || ($roworg->tk_has) == '' ? '' : ($roworg->tk_has);
            $sas_giat= empty($roworg->kel_sasaran_kegiatan) || ($roworg->kel_sasaran_kegiatan) == '' ? '' : ($roworg->kel_sasaran_kegiatan);
            $ang_lalu= empty($roworg->ang_lalu) || ($roworg->ang_lalu) == '' || ($roworg->ang_lalu) == 'Null' ? 0 : ($roworg->ang_lalu);

            $sqltp="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE left(kd_sub_kegiatan,12)='$giat' AND left(kd_skpd,22)=left('$id',22)";
                     $sqlb=$this->db->query($sqltp);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $totp  =number_format($rowb->totb,"2",",",".");
                       $totp1 =number_format($rowb->totb*1.1,"2",",",".");
                       $totp2  =number_format($rowb->totb2,"2",",",".");
                       $totp12 =number_format($rowb->totb2*1.1,"2",",",".");
                    }
                    
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $cRet='';
            $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                        <tr> 
                             <td width='80%' align='center'><strong>$judul_dpa <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width='20%' rowspan='2' align='center'><strong> FORMULIR <br /> $kd_dpa - $judul_dpa SKPD    
      </strong></td>
                        </tr>
                        <tr>
                             <td style='vertical-align:top;' align='center'><strong>$kab</strong> <br /><strong>TAHUN ANGGARAN $thn</strong></td>
                        </tr>
                      </table>";
                      
            $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0'>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;No DPA</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$nodpa</td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'></td>
                            </tr>                            
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Urusan Pemerintahan</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_urusan</td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'>$nm_urusan</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Bidang Urusan</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_bidang_urusan </td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'> $nm_bidang_urusan</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_prog</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_prog</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Sasaran Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$sasaran_prog</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Capaian Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$tu_capai - $tk_capai</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Kegiatan</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_giat</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_giat</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Organisasi</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>".substr($kd_skpd,0,17)."</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Unit Organisasi</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_skpd</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnl</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2'  align='left' style='vertical-align:top;border-left: none;'>Rp. ".number_format($ang_lalu,"2",",",".")." (".$this->rka_model->terbilang($ang_lalu*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thn</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp (".$this->rka_model->terbilang($rowb->totb*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnd</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp1 (".$this->rka_model->terbilang($rowb->totb*1.1)." rupiah)</td>
                            </tr>
                            <tr>
                        <td colspan='4'  width='100%' align='left'>&nbsp;</td>
                    </tr>
                        </table>    
                            
                        ";
            $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1' cellspacing='2' cellpadding='5'>
                        <tr>
                            <td colspan='5'  align='center' >Indikator & Tolak Ukur Kinerja Kegiatan</td>
                        </tr>";
            $cRet .="<tr>
                     <td  rowspan='2' align='center'>Indikator </td>
                     <td  colspan='2' align='center'>Sebelum $nama_status </td>
                     <td  colspan='2' align='center'>Setelah $nama_status </td>
                    </tr>"; 
            $cRet .="<tr>
                     <td  align='center'>Tolak Ukur Kerja </td>
                     <td  align='center'>Target Kinerja </td>
                     <td  align='center'>Tolak Ukur Kerja </td>
                     <td  align='center'>Target Kinerja </td>
                    </tr>";          

            $cRet .=" <tr align='center'>
                        <td >Capaian Kegiatan </td>
                        <td>$tu_capai</td>
                        <td>$tk_capai</td>
                        <td>$tu_capai</td>
                        <td>$tk_capai</td>
                     </tr>";
            $cRet .=" <tr align='center'>
                        <td>Masukan </td>
                        <td>Dana yang dibutuhkan</td>
                        <td>Rp. $totp</td>
                        <td>Dana yang dibutuhkan</td>
                        <td>Rp. $totp2</td>
                    </tr>";
            $cRet .=" <tr align='center'>
                        <td>Keluaran </td>
                        <td>$tu_kel</td>
                        <td>$tk_kel</td>
                        <td>$tu_kel</td>
                        <td>$tk_kel</td>
                      </tr>";
            $cRet .=" <tr align='center'>
                        <td>Hasil </td>
                        <td>$tu_has</td>
                        <td>$tk_has</td>
                        <td>$tu_has</td>
                        <td>$tk_has</td>
                      </tr>";
            $cRet .= "<tr>
                        <td colspan='5'  align='left'>Kelompok Sasaran Kegiatan : $sas_giat</td>
                    </tr>";
            $cRet .= "<tr>
                        <td colspan='5' align='left'>&nbsp;</td>
                    </tr>"; 
                    $cRet .= "<tr>
                        <td colspan='5' bgcolor='#CCCCCC' align='left'>&nbsp;</td>
                    </tr>";                
            
            $cRet .= "<tr>
                            <td colspan='5' align='center'>RINCIAN ANGGARAN BELANJA KEGIATAN SATUAN KERJA PERANGKAT DAERAH</td>
                      </tr>";
                        
            $cRet .="</table>";
    //rincian sub kegiatan
         

                   $sqlsub="SELECT id_skpd, id_sub_kegiatan, left(a.kd_gabungan,22) skpd, a.nm_skpd, a.kd_sub_kegiatan as kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2 FROM trskpd a
                    
                    WHERE left(a.kd_sub_kegiatan,12)='$giat' AND left(a.kd_skpd,22)=left('$id',22)
                    group by left(a.kd_gabungan,22) ,a.nm_skpd, a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2,id_skpd, id_sub_kegiatan";
                     $sqlbsub=$this->db->query($sqlsub);
                     foreach ($sqlbsub->result() as $rowsub)
                    {
                       $sub         =$rowsub->kd_sub_kegiatan;
                       $nm_sub      =$rowsub->nm_sub_kegiatan;
                       $sub_keluaran=$rowsub->sub_keluaran;
                       $lokasi      =$rowsub->lokasi;
                       $skpd        =$rowsub->skpd;
                       $id_skpd     =$rowsub->id_skpd;
                       $id_sub_kegiatan     =$rowsub->id_sub_kegiatan;
                       $nm_skpd     =$rowsub->nm_skpd;
                       $waktu_giat  =$rowsub->waktu_giat;
                       $waktu_giat2 =$rowsub->waktu_giat2;
                       $keterangan  ="";

                   
                     /*$kodesumberdana=$this->db->query("SELECT top 1 sumber+' '+isnull(sumber2,'')+' '+isnull(sumber3,'')+' '+isnull(sumber4,'') as sumber from trdrka where kd_sub_kegiatan='$sub' and kd_skpd='$id'
                        union all 
                        select ''"); 
                     foreach($kodesumberdana->result() as $oke){
                        $sumbr=$oke->sumber;
                     }*/
                     $sqlsumber="SELECT kd_sumberdana,sumber,nm_sumberdana FROM v_sumber1_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber1  = $rowsumber->nm_sumberdana;
                        $kdsumber1  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber2 FROM v_sumber2_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber2  = $rowsumber->sumber2;
                        $kdsumber2  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber3 FROM v_sumber3_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber3  = $rowsumber->sumber3;
                        $kdsumber3  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber4 FROM v_sumber4_sempurna where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber4  = $rowsumber->sumber4;
                        $kdsumber4  = $rowsumber->kd_sumberdana;
                        
                    }

                    if ($kdsumber2==''){
                        $kodesumberdana=$nmsumber1;
                    }else if ($kdsumber2==''){
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2;                    
                    }else if($kdsumber3==''){
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2.'<br />'.$nmsumber3;
                    }else{
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2.'<br />'.$nmsumber3.'<br />'.$nmsumber4;    
                    }
                        $cRet .="
                        
                        <table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                            <tr>
                                <td width style='tical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sub Kegiatan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$sub - $nm_sub</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sumber Pendanaan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$kodesumberdana</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Lokasi</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'><table  style='border-collapse:collapse;font-size:12px'>"; 
                                        $okeii=$this->db->query("SELECT * from sipd_lokout where id_sub_skpd='$id_skpd' and id_sub_giat='$id_sub_kegiatan'");
                                        foreach($okeii->result() as $ac){
                                            $oke=$ac->daerahteks;
                                           
                                            $cRet.="<tr><td>$oke </td></tr>";
                                        }

                        $cRet.="</table></td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Waktu Pelaksanaan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'> ".$this->support->getBulan($waktu_giat)." s/d ".$this->support->getBulan($waktu_giat2)."</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-left: solid 1px black;border-bottom: solid 1px black;' align='left'>&nbsp;Keluaran Sub Kegiatan</td>
                                <td align='center' style='vertical-align:top;border-bottom: solid 1px black;'>:</td>
                                <td align='left' colspan='3' style='vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;'>
                                       
                                        <table  style='border-collapse:collapse;font-size:12px'>"; 
                                        $okeii=$this->db->query("SELECT * from sipd_output where id_sub_skpd='$id_skpd' and id_sub_giat='$id_sub_kegiatan'");
                                        foreach($okeii->result() as $ac){
                                            $oke=$ac->outputteks;
                                            $jiwa=$ac->targetoutputteks;
                                            $cRet.="<tr><td>$oke - $jiwa</td></tr>";
                                        }

                        $cRet.="</table>
                                </td>
                            </tr>
                            </table>
                            
                        ";

                        $cRet .= "<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'>
                              <thead>
                            <tr>
                                <td rowspan='3' bgcolor='#CCCCCC' align='center'><b>Kode Rekening</b></td>                            
                                <td rowspan='3' bgcolor='#CCCCCC' align='center'><b>Uraian</b></td>
                                <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Sebelum $nama_status</b></td>
                                <td colspan='5' bgcolor='#CCCCCC' align='center'><b>Setelah $nama_status</b></td>
                                <td colspan='2' rowspan='2' bgcolor='#CCCCCC' align='center'><b>Bertambah/(Berkurang)</b></td>
                            </tr>                 
                            <tr>
                                <td colspan='4' bgcolor='#CCCCCC' align='center'><b>Rincian Perhitungan</b></td>
                                <td rowspan='2' bgcolor='#CCCCCC' align='center'><b>Jumlah(Rp.)</b></td>
                                <td colspan='4' bgcolor='#CCCCCC' align='center'><b>Rincian Perhitungan</b></td>
                                <td rowspan='2' bgcolor='#CCCCCC' align='center'><b>Jumlah(Rp.)</b></td>
                            </tr>
                            <tr>
                                <td bgcolor='#CCCCCC' align='center'>Koefisien</td>
                                <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                                <td bgcolor='#CCCCCC' align='center'>Harga</td>
                                <td bgcolor='#CCCCCC' align='center'>PPN</td>
                                <td bgcolor='#CCCCCC' align='center'>Koefisien</td>
                                <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                                <td bgcolor='#CCCCCC' align='center'>Harga</td>
                                <td bgcolor='#CCCCCC' align='center'>PPN</td>
                                <td bgcolor='#CCCCCC' align='center'>Rp</td>
                                <td bgcolor='#CCCCCC' align='center'>%</td>
                            </tr>    
                         
                        </thead> 
                         
                           
                            ";

                            $sql1="SELECT * FROM(SELECT 1 header,0 no_po, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama, '' spek, '' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga,  '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE a.kd_sub_kegiatan='$sub' AND left(a.no_trdrka,22)='$skpd' 
                                    GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
                                    UNION ALL 
                                    SELECT 1 header, 0 no_po,LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'2' AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
                                    UNION ALL  
                                    SELECT 1 header, 0 no_po, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan, 0 AS harga,  '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'3' AS id FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
                                    UNION ALL 
                                    SELECT 1 header, 0 no_po, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
                                    UNION ALL 
                                    SELECT 1 header, 0 no_po, LEFT(a.kd_rek6,8) AS rek1,RTRIM(LEFT(a.kd_rek6,8)) AS rek,b.nm_rek5 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'5' AS id FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5
                                    UNION ALL
                                    SELECT 1 header, 0 no_po, a.kd_rek6 AS rek1,RTRIM(a.kd_rek6) AS rek,b.nm_rek6 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan, 0 AS harga, '' as koefisien2,0 AS volume2,' 'AS satuan2, 0 AS harga2,
                                    SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'6' AS id FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE a.kd_sub_kegiatan='$sub'
                                    AND left(a.no_trdrka,22)='$skpd'  GROUP BY a.kd_rek6,b.nm_rek6
                                    UNION ALL
                                        SELECT a. header,a.no_po as no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,a.uraian AS nama,spesifikasi$status_speksifikasi1 as spek, CAST(a.volume$status_volume1 as varchar)+' '+a.satuan$status_satuan1 koefisien1, a.volume$status_volume1 AS volume,a.satuan$status_satuan1 AS satuan, a.harga$status_harga1 AS harga,a.koefisien_sempurna  koefisien2, a.volume$status_volume2 AS volume,a.satuan$status_satuan2 AS satuan, harga$status_harga2 AS harga,
                                        a.total$status_anggaran1 AS nilai, a.total_sempurna1 AS nilai2 ,'9' AS id FROM trdpo a  WHERE LEFT(a.no_trdrka,22)='$skpd' AND SUBSTRING(no_trdrka,24,15)='$sub' AND (header='0' or header is null)
                                        ) a ORDER BY a.rek1, a.no_po";
                     
                    $query = $this->db->query($sql1);
                    $nilangsub=0;
                    $nilangsub2=0;

                            foreach ($query->result() as $row)
                            {
                                $rek=$row->rek;
                                $reke=$this->support->dotrek($rek);
                                $uraian=$row->nama;
                                $spek_komp=$row->spek;
                                $koefisien=$row->koefisien;
                                //$spek_komp2=$row->spek2;
                                $koefisien2=$row->koefisien2;

                            //    $volum=$row->volume;
                                $sat=$row->satuan;
                                $sat2=$row->satuan2;
                                $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                                $volum= empty($row->volume) || $row->volume == 0 ? '' :$row->volume;
                                $hrg2= empty($row->harga2) || $row->harga2 == 0 ? '' :number_format($row->harga2,2,',','.');
                                $volum2= empty($row->volume2) || $row->volume == 0 ? '' :$row->volume2;

                                //$hrg=number_format($row->harga,"2",".",",");
                                $nila= empty($row->nilai) || $row->nilai == 0 ? '' :number_format($row->nilai,2,',','.');
                                $nila2= empty($row->nilai2) || $row->nilai2 == 0 ? '' :number_format($row->nilai2,2,',','.');

                                $selisih= $this->support->rp_minus($row->nilai2-$row->nilai);
                                if($row->nilai==0){
                                    $persen=0;
                                }else{
                                    $persen= $this->support->rp_minus((($row->nilai2-$row->nilai)/$row->nilai)*100);
                                }
                                

                                        
                                
                                if ($row->header=='1'){
                                    $ppn='';
                                    
                               
                                 $cRet    .= " <tr>
                                                 <td ><b>$reke</b></td>                                     
                                                 <td  ><b>$uraian</b></td>
                                                 <td  ><b>$koefisien</b></td>
                                                 <td  ><b>$sat</b></td>
                                                 <td  ><b>$hrg</b></td>
                                                 <td  ><b>$ppn</b></td>
                                                 <td  align='right'><b>$nila</b></td>
                                                 <td ><b>$koefisien</b></td>                                     
                                                 <td  ><b>$sat2</b></td>
                                                 <td  ><b>$hrg2</b></td>
                                                 <td  ><b>$ppn</b></td>
                                                 <td  align='right'><b>$nila2</b></td>
                                                 <td  align='right'><b>$selisih</b></td>
                                                 <td  align='center'><b>$persen</b></td>
                                             </tr>
                                                 ";

                                             }else{
                                                $ppn=0;
                                                $ppn2=0;

                                                $cRet    .= " <tr>
                                                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;'  align='left'>$reke</td>                                     
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' >$uraian <br>&nbsp;&nbsp;&nbsp; $spek_komp  </td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$koefisien</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'>$sat</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$hrg</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'><b>$ppn</b></td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$nila</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='left'>".$koefisien2."</td>                                     
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' > ".$sat2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$hrg2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$ppn2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>".$nila2."</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='right'>$selisih</td>
                                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center'>$persen</td>
                                                        </tr>
                                                 ";
                                                 $nilangsub= $nilangsub+$row->nilai;
                                                 $nilangsub2= $nilangsub2+$row->nilai2;
                                                 $selisih=$this->support->rp_minus($nilangsub2-$nilangsub);
                                                 if($nilangsub==0){
                                                    $persen==0;
                                                }else{
                                                    $persen=$this->support->rp_minus((($nilangsub2-$nilangsub)/$nilangsub)*100);

                                                }
                                                       
                                             }
                                             
                            }

                            $cRet    .=" 
                                        <tr>                                    
                                         <td colspan='6' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >Jumlah Anggaran Sub Kegiatan Sebelum $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format($nilangsub,2,',','.')."</td>
                                         <td colspan='4' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >Jumlah Anggaran Sub Kegiatan Setelah $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format($nilangsub2,2,',','.')."</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".$selisih."</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='center'>".$persen."</td>
                                         </tr>
                                                                                  
                                         <tr>                                    
                                         <td colspan='14'  align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;'>&nbsp;</td>
                                         </tr>
                                         </table> ";
                    }

                    


                            $cRet    .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'> 
                                        
                                         <tr>                                    
                                         <td colspan='5' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'>Jumlah Anggaran Kegiatan Sebelum $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$totp</td>
                                         <td colspan='4' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'>Jumlah Anggaran Kegiatan Setelah $nama_status</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$totp2</td>
                                         </tr>
                                         </table>";
          
                

        if($ttd1!='tanpa'){
            $sqlttd1="SELECT top 1  nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' union all select '','','','' ORDER by nip";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u></td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd kd_skpd , sum(nilai_sempurna15) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' and left(kd_sub_kegiatan,len('$giat'))='$giat' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                
                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%' align='right'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";



            
            $data['prev']= $cRet;    
            $judul='RKA-rincian_belanja_'.$id.'';
            switch($cetak) { 
            case 1;
               // echo($cRet);
                $this->support->_mpdf2($giat,$nm_giat,$cRet,10,10,10,'1');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0; 

                //$this->master_pdf->_mpdf_margin('',$cRet,$kanan,$kiri,10,'1','',$atas,$bawah); 
                //echo ("<title>RKA Rincian Belanja</title>");
               echo($cRet);
            break;
            }
        }

function preview_rincian_belanja_skpd_penetapan(){

            $id = $this->uri->segment(3);
            $giat = $this->uri->segment(4);
            $cetak = $this->uri->segment(5);
            $atas = $this->uri->segment(6);
            $bawah = $this->uri->segment(7);
            $kiri = $this->uri->segment(8);
            $kanan = $this->uri->segment(9);
            
            
     

            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
     
  
     
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        $tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                        $thnl =$thn-1;
                        $thnd =$thn+1; 
                    }
           $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kd_skpd= '$id' AND kode in ('PA','KPA') AND(REPLACE(id_ttd, ' ', 'a')='$ttd1' )  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip; 
                        $pangkat=$rowttd->pangkat;
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        //$jabatan  = str_replace('Kuasa Pengguna Anggaran','',$jabatan);
                        if($jabatan=='Kuasa Pengguna Anggaran'){
                            $kuasa="";
                        }else{
                            $kuasa="Kuasa Pengguna Anggaran";
                        }
                        
                    
                    }
                  
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND(REPLACE(id_ttd, ' ', 'a')='$ttd2')  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip;
                        $pangkat2=$rowttd2->pangkat;
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
           
                        
                        if($jabatan2=='Pengguna Anggaran'){
                            $kuasa2="";
                        }else{
                            $kuasa2="Pengguna Anggaran";
                        }
                    }
            $sqlorg="SELECT top 1 * FROM (SELECT g.kd_urusan,g.nm_urusan,f.kd_bidang_urusan,f.nm_bidang_urusan,left(a.kd_gabungan,22) kd_skpd ,e.nm_skpd,a.kd_program,a.nm_program,a.sasaran_program,a.capaian_program,c.kd_kegiatan,c.nm_kegiatan,a.kd_sub_kegiatan,a.nm_sub_kegiatan,SUM(d.nilai) AS nilai,a.tu_capai,
                a.tu_mas,
                a.tu_kel,
                a.tu_has,
                a.tk_capai,
                a.tk_mas,
                a.tk_kel,
                a.tk_has,a.lokasi,d.sumber,a.kel_sasaran_kegiatan,a.ang_lalu FROM trskpd a 
                INNER JOIN ms_kegiatan c ON a.kd_kegiatan=
                c.kd_kegiatan
                INNER JOIN trdrka d ON a.kd_kegiatan=left(d.kd_sub_kegiatan,12)
                
                INNER JOIN ms_skpd e ON a.kd_skpd=e.kd_skpd
                INNER JOIN ms_bidang_urusan f ON a.kd_bidang_urusan=f.kd_bidang_urusan
                INNER JOIN ms_urusan g ON left(a.kd_bidang_urusan,1)=g.kd_urusan
                where  left(a.kd_gabungan,17)=left('$id',17)
                GROUP BY 
                g.kd_urusan,
                g.nm_urusan,
                f.kd_bidang_urusan,
                f.nm_bidang_urusan,
                left(a.kd_gabungan,22) ,
                e.nm_skpd,
                a.kd_program,
                a.nm_program,
                a.sasaran_program,
                a.capaian_program,
                c.kd_kegiatan,
                c.nm_kegiatan,
                a.kd_sub_kegiatan,
                a.nm_sub_kegiatan,
                a.tu_capai,
                a.tu_mas,
                a.tu_kel,
                a.tu_has,
                a.tk_capai,
                a.tk_mas,
                a.tk_kel,
                a.tk_has,
                a.lokasi,
                d.sumber,
                a.kel_sasaran_kegiatan,
                a.ang_lalu) OKE
                                    where left(kd_sub_kegiatan,12)='$giat' and left(kd_skpd,17)=left('$id',17)
                    ";
                     $sqlorg1=$this->db->query($sqlorg);
                     foreach ($sqlorg1->result() as $roworg)
                    {
                        $kd_urusan=$roworg->kd_urusan;                    
                        $nm_urusan= $roworg->nm_urusan;
                        $kd_bidang_urusan=$roworg->kd_bidang_urusan;                    
                        $nm_bidang_urusan= $roworg->nm_bidang_urusan;
                        $kd_skpd  = $roworg->kd_skpd;
                        $nm_skpd  = $roworg->nm_skpd;
                        $kd_prog  = $roworg->kd_program;
                        $nm_prog  = $roworg->nm_program;
                        $sasaran_prog  = $roworg->sasaran_program;
                        $capaian_prog  = $roworg->capaian_program;
                        $kd_giat  = $roworg->kd_kegiatan;
                        $nm_giat  = $roworg->nm_kegiatan;
                        $lokasi  = $roworg->lokasi;
                        $tu_capai  = $roworg->tu_capai;
                        $tu_mas  = $roworg->tu_mas;
                        $tu_kel  = $roworg->tu_kel;
                        $tu_has  = $roworg->tu_has;
                        $tk_capai  = $roworg->tk_capai;
                        $tk_mas  = $roworg->tk_mas;
                        $tk_kel  = $roworg->tk_kel;
                        $tk_has  = $roworg->tk_has;
                        $sas_giat = $roworg->kel_sasaran_kegiatan;
                        $ang_lalu = $roworg->ang_lalu;
                    }
            $kd_urusan= empty($roworg->kd_urusan) || ($roworg->kd_urusan) == '' ? '' : ($roworg->kd_urusan);
            $nm_urusan= empty($roworg->nm_urusan) || ($roworg->nm_urusan) == '' ? '' : ($roworg->nm_urusan);
            $kd_bidang_urusan= empty($roworg->kd_bidang_urusan) || ($roworg->kd_bidang_urusan) == '' ? '' : ($roworg->kd_bidang_urusan);
            $nm_bidang_urusan= empty($roworg->nm_bidang_urusan) || ($roworg->nm_bidang_urusan) == '' ? '' : ($roworg->nm_bidang_urusan);
            $kd_skpd= empty($roworg->kd_skpd) || ($roworg->kd_skpd) == '' ? '' : ($roworg->kd_skpd);
            $nm_skpd= empty($roworg->nm_skpd) || ($roworg->nm_skpd) == '' ? '' : ($roworg->nm_skpd);
            $kd_prog= empty($roworg->kd_program) || ($roworg->kd_program) == '' ? '' : ($roworg->kd_program);
            $nm_prog= empty($roworg->nm_program) || ($roworg->nm_program) == '' ? '' : ($roworg->nm_program);
            $sasaran_prog= empty($roworg->sasaran_program) || ($roworg->sasaran_program) == '' ? '' : ($roworg->sasaran_program);
            $capaian_prog= empty($roworg->capaian_program) || ($roworg->capaian_program) == '' ? '' : ($roworg->capaian_program);
            $kd_giat= empty($roworg->kd_kegiatan) || ($roworg->kd_kegiatan) == '' ? '' : ($roworg->kd_kegiatan);
            $nm_giat= empty($roworg->nm_kegiatan) || ($roworg->nm_kegiatan) == '' ? '' : ($roworg->nm_kegiatan);
            $lokasi= empty($roworg->lokasi) || ($roworg->lokasi) == '' ? '' : ($roworg->lokasi);
            $tu_capai= empty($roworg->tu_capai) || ($roworg->tu_capai) == '' ? '' : ($roworg->tu_capai);
            $tu_mas= empty($roworg->tu_mas) || ($roworg->tu_mas) == '' ? '' : ($roworg->tu_mas);
            $tu_kel= empty($roworg->tu_kel) || ($roworg->tu_kel) == '' ? '' : ($roworg->tu_kel);
            $tu_has= empty($roworg->tu_has) || ($roworg->tu_has) == '' ? '' : ($roworg->tu_has);
            $tk_capai= empty($roworg->tk_capai) || ($roworg->tk_capai) == '' ? '' : ($roworg->tk_capai);
            $tk_mas= empty($roworg->tk_mas) || ($roworg->tk_mas) == '' ? '' : ($roworg->tk_mas);
            $tk_kel= empty($roworg->tk_kel) || ($roworg->tk_kel) == '' ? '' : ($roworg->tk_kel);
            $tk_has= empty($roworg->tk_has) || ($roworg->tk_has) == '' ? '' : ($roworg->tk_has);
            $sas_giat= empty($roworg->kel_sasaran_kegiatan) || ($roworg->kel_sasaran_kegiatan) == '' ? '' : ($roworg->kel_sasaran_kegiatan);
            $ang_lalu= empty($roworg->ang_lalu) || ($roworg->ang_lalu) == '' || ($roworg->ang_lalu) == 'Null' ? 0 : ($roworg->ang_lalu);

            $sqltp="SELECT SUM(nilai) AS totb FROM trdrka WHERE left(kd_sub_kegiatan,12)='$giat' AND left(kd_skpd,17)=left('$id',17)";
                     $sqlb=$this->db->query($sqltp);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $totp  =number_format($rowb->totb,"2",",",".");
                       $totp1 =number_format($rowb->totb*1.1,"2",",",".");
                    }
                    
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $cRet='';
            $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                        <tr> 
                             <td width='80%' align='center'><strong>DOKUMEN PELAKSANAAN ANGGARAN <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width='20%' style='vertical-align:top;' rowspan='2' align='center'><strong><br /><br />FORMULIR <br /> DPA-DOKUMEN
    PELAKSANAAN ANGGARAN SKPD    
      </strong></td>
                        </tr>
                        <tr>
                             <td style='vertical-align:top;' align='center'><strong>$kab</strong> <br /><strong>TAHUN ANGGARAN $thn</strong></td>
                        </tr>
                      </table>";
                      
            $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0'>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;No DPA</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$nodpa</td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'></td>
                            </tr>                            
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Urusan Pemerintahan</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_urusan</td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'>$nm_urusan</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Bidang Urusan</td>
                                <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                                <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_bidang_urusan </td>
                                <td width='60%' style='vertical-align:top;border-left: none;' align='left'> $nm_bidang_urusan</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_prog</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_prog</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Sasaran Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$sasaran_prog</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Capaian Program</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$tu_capai - $tk_capai</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Kegiatan</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_giat</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_giat</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Organisasi</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>".substr($kd_skpd,0,17)."</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Unit Organisasi</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_skpd</td>
                                <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnl</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2'  align='left' style='vertical-align:top;border-left: none;'>Rp. ".number_format($ang_lalu,"2",",",".")." (".$this->rka_model->terbilang($ang_lalu*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thn</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp (".$this->rka_model->terbilang($rowb->totb*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thnd</td>
                                <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                                <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp1 (".$this->rka_model->terbilang($rowb->totb*1.1)." rupiah)</td>
                            </tr>
                            <tr>
                        <td colspan='4'  width='100%' align='left'>&nbsp;</td>
                    </tr>
                        </table>    
                            
                        ";
            $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                        <tr>
                            <td colspan='3'  align='center' >Indikator & Tolak Ukur Kinerja Kegiatan</td>
                        </tr>";
            $cRet .="<tr>
                     <td width='20%'  align='center'>Indikator </td>
                     <td width='40%' align='center'>Tolak Ukur Kerja </td>
                     <td width='40%' align='center'>Target Kinerja </td>
                    </tr>";          

            $cRet .=" <tr align='center'>
                        <td >Capaian Kegiatan </td>
                        <td>$tu_capai</td>
                        <td>$tk_capai</td>
                     </tr>";
            $cRet .=" <tr align='center'>
                        <td>Masukan </td>
                        <td>Dana yang dibutuhkan</td>
                        <td>Rp. $totp</td>
                    </tr>";
            $cRet .=" <tr align='center'>
                        <td>Keluaran </td>
                        <td>$tu_kel</td>
                        <td>$tk_kel</td>
                      </tr>";
            $cRet .=" <tr align='center'>
                        <td>Hasil </td>
                        <td>$tu_has</td>
                        <td>$tk_has</td>
                      </tr>";
            $cRet .= "<tr>
                        <td colspan='3'  width='100%' align='left'>Kelompok Sasaran Kegiatan : $sas_giat</td>
                    </tr>";
            $cRet .= "<tr>
                        <td colspan='3' width='100%' align='left'>&nbsp;</td>
                    </tr>"; 
                    $cRet .= "<tr>
                        <td colspan='3' bgcolor='#CCCCCC' width='100%' align='left'>&nbsp;</td>
                    </tr>";                
            
            $cRet .= "<tr>
                            <td colspan='3' align='center'>RINCIAN ANGGARAN BELANJA KEGIATAN SATUAN KERJA PERANGKAT DAERAH</td>
                      </tr>";
                        
            $cRet .="</table>";
    //rincian sub kegiatan
         

                   $sqlsub="SELECT id_skpd, id_sub_kegiatan, left(a.kd_gabungan,22) skpd, a.nm_skpd, a.kd_sub_kegiatan as kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2 FROM trskpd a
                    
                    WHERE left(a.kd_sub_kegiatan,12)='$giat' AND left(a.kd_skpd,17)=left('$id',17)
                    group by left(a.kd_gabungan,22) ,a.nm_skpd, a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.sub_keluaran,a.lokasi,a.waktu_giat,a.waktu_giat2,id_skpd, id_sub_kegiatan";
                     $sqlbsub=$this->db->query($sqlsub);
                     foreach ($sqlbsub->result() as $rowsub)
                    {
                       $sub         =$rowsub->kd_sub_kegiatan;
                       $nm_sub      =$rowsub->nm_sub_kegiatan;
                       $sub_keluaran=$rowsub->sub_keluaran;
                       $lokasi      =$rowsub->lokasi;
                       $skpd        =$rowsub->skpd;
                       $id_skpd     =$rowsub->id_skpd;
                       $id_sub_kegiatan     =$rowsub->id_sub_kegiatan;
                       $nm_skpd     =$rowsub->nm_skpd;
                       $waktu_giat  =$rowsub->waktu_giat;
                       $waktu_giat2 =$rowsub->waktu_giat2;
                       $keterangan  ="";

                   
                     $kodesumberdana=$this->db->query("SELECT top 1 sumber+' '+isnull(sumber2,'')+' '+isnull(sumber3,'')+' '+isnull(sumber4,'') as sumber from trdrka where kd_sub_kegiatan='$sub' and kd_skpd='$id'
                        union all 
                        select ''"); 
                     foreach($kodesumberdana->result() as $oke){
                        $sumbr=$oke->sumber;
                     }
                        $cRet .="
                        <pagebreak type='NEXT-ODD' resetpagenum='1' pagenumstyle='1' suppress='off' />
                        <table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                            <tr>
                                <td width style='tical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sub Kegiatan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$sub - $nm_sub</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Sumber Pendanaan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'>$sumbr</td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Lokasi</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'><table  style='border-collapse:collapse;font-size:12px'>"; 
                                        $okeii=$this->db->query("SELECT * from sipd_lokout where id_sub_skpd='$id_skpd' and id_sub_giat='$id_sub_kegiatan'");
                                        foreach($okeii->result() as $ac){
                                            $oke=$ac->daerahteks;
                                           
                                            $cRet.="<tr><td>$oke </td></tr>";
                                        }

                        $cRet.="</table></td>
                            </tr>
                            <tr>
                                <td width='20%' style='vertical-align:top;border-left: solid 1px black;' align='left'>&nbsp;Waktu Pelaksanaan</td>
                                <td width='5%'  style='vertical-align:top;' align='center'>:</td>
                                <td width='75%' colspan='3' style='vertical-align:top;border-right: solid 1px black;' align='left'> ".$this->support->getBulan($waktu_giat)." s/d ".$this->support->getBulan($waktu_giat2)."</td>
                            </tr>
                            <tr>
                                <td align='left' style='vertical-align:top;border-left: solid 1px black;border-bottom: solid 1px black;' align='left'>&nbsp;Keluaran Sub Kegiatan</td>
                                <td align='center' style='vertical-align:top;border-bottom: solid 1px black;'>:</td>
                                <td align='left' colspan='3' style='vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;'>
                                       
                                        <table  style='border-collapse:collapse;font-size:12px'>"; 
                                        $okeii=$this->db->query("SELECT * from sipd_output where id_sub_skpd='$id_skpd' and id_sub_giat='$id_sub_kegiatan'");
                                        foreach($okeii->result() as $ac){
                                            $oke=$ac->outputteks;
                                            $jiwa=$ac->targetoutputteks;
                                            $cRet.="<tr><td>$oke - $jiwa</td></tr>";
                                        }

                        $cRet.="</table>
                                </td>
                            </tr>
                            </table>
                            
                        ";

                        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                              <thead>                 
                            <tr><td rowspan='2' bgcolor='#CCCCCC' width='10%' align='center'><b>Kode Rekening</b></td>                            
                                <td rowspan='2' bgcolor='#CCCCCC' width='40%' align='center'><b>Uraian</b></td>
                                <td colspan='4' bgcolor='#CCCCCC' width='30%' align='center'><b>Rincian Perhitungan</b></td>
                                <td rowspan='2' bgcolor='#CCCCCC' width='20%' align='center'><b>Jumlah(Rp.)</b></td></tr>
                            <tr>
                                <td width='9%'    bgcolor='#CCCCCC' align='center'>Koefisien</td>
                                <td width='9%'    bgcolor='#CCCCCC' align='center'>Satuan</td>
                                <td width='9%'   bgcolor='#CCCCCC' align='center'>Harga</td>
                                <td width='3%'    bgcolor='#CCCCCC' align='center'>PPN</td>
                            </tr>    
                         
                        </thead> 
                         
                            <tr>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='10%'>&nbsp;1</td>                            
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='40%'>&nbsp;2</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='9%'>&nbsp;3</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='9%'>&nbsp;4</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='9%'>&nbsp;5</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='3%'>&nbsp;6</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='20%'>&nbsp;7</td>
                            </tr>
                            ";

                            $sql1="SELECT * FROM(SELECT 0 header,0 no_po, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama, '' spek, '' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE a.kd_sub_kegiatan='$sub' AND left(a.no_trdrka,22)='$skpd' 
    GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
    UNION ALL 
    SELECT 0 header, 0 no_po,LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'2' AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE a.kd_sub_kegiatan='$sub'
    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
    UNION ALL  
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE a.kd_sub_kegiatan='$sub'
    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
    UNION ALL 
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE a.kd_sub_kegiatan='$sub'
    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
    UNION ALL 
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,8) AS rek1,RTRIM(LEFT(a.kd_rek6,8)) AS rek,b.nm_rek5 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE a.kd_sub_kegiatan='$sub'
    AND left(a.no_trdrka,22)='$skpd'  GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5
    UNION ALL
    SELECT 0 header, 0 no_po, a.kd_rek6 AS rek1,RTRIM(a.kd_rek6) AS rek,b.nm_rek6 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE a.kd_sub_kegiatan='$sub'
    AND left(a.no_trdrka,22)='$skpd'  GROUP BY a.kd_rek6,b.nm_rek6
    UNION ALL
    SELECT * FROM (SELECT b.header,b.no_po as no_pos,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien,0 AS volume,
' ' AS satuan, 0 AS harga,SUM(a.total) AS nilai,'7' AS id FROM trdpo a LEFT JOIN trdpo b ON b.subs_bl_teks=a.uraian
AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE LEFT(a.no_trdrka,22)='$skpd' AND 
SUBSTRING(a.no_trdrka,24,15)='$sub' GROUP BY RIGHT(a.no_trdrka,12),b.header, b.no_po,b.uraian)z WHERE header='1' 
UNION ALL
SELECT * FROM (SELECT b.header,b.no_po as no_pos,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien, 0 AS volume,
' ' AS satuan, 0 AS harga,SUM(a.total) AS nilai,'8' AS id FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE LEFT(a.no_trdrka,22)='$skpd' AND 
SUBSTRING(a.no_trdrka,24,15)='$sub' GROUP BY RIGHT(a.no_trdrka,12),b.header, b.no_po,b.uraian)z WHERE header='1' 
    
    UNION ALL
    SELECT a. header,a.no_po as no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,a.uraian AS nama,spesifikasi as spek,koefisien,a.volume1 AS volume,a.satuan1 AS satuan,
    harga AS harga,a.total AS nilai,'9' AS id FROM trdpo a  WHERE LEFT(a.no_trdrka,22)='$skpd' AND SUBSTRING(no_trdrka,24,15)='$sub' AND (header='0' or header is null)
    ) a ORDER BY a.rek1, a.no_po
    ";
                     
                    $query = $this->db->query($sql1);
                    $nilangsub=0;

                            foreach ($query->result() as $row)
                            {
                                $rek=$row->rek;
                                $reke=$this->support->dotrek($rek);
                                $uraian=$row->nama;
                                $spek_komp=$row->spek;
                                $koefisien=$row->koefisien;

                            //    $volum=$row->volume;
                                $sat=$row->satuan;
                                $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                                $volum= empty($row->volume) || $row->volume == 0 ? '' :$row->volume;

                                //$hrg=number_format($row->harga,"2",".",",");
                                $nila= empty($row->nilai) || $row->nilai == 0 ? '' :number_format($row->nilai,2,',','.');

                                        
                                

                                if ($row->id<='8'){
                                    $ppn='';
                               
                                 $cRet    .= " <tr><td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='10%' align='left'><b>$reke</b></td>                                     
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='40%'><b>$uraian</b></td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='9%' align='right'><b>$koefisien</b></td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='9%' align='center'><b>$sat</b></td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='9%' align='right'><b>$hrg</b></td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='3%' align='center'><b>$ppn</b></td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='20%' align='right'><b>$nila</b></td></tr>
                                                 ";

                                             }else{
                                                $ppn=0;
                                                $cRet    .= " <tr><td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='10%' align='left'>$reke</td>                                     
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='40%'>$uraian<br /> &nbsp;&nbsp;&nbsp; $spek_komp</td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='9%' align='right'>$koefisien&nbsp;&nbsp;</td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='9%' align='center'>$sat&nbsp;&nbsp;</td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='9%' align='right'>$hrg&nbsp;&nbsp;</td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='3%' align='center'><b>$ppn&nbsp;&nbsp;</b></td>
                                                 <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' width='20%' align='right'>$nila&nbsp;&nbsp;</td></tr>
                                                 ";
                                                 $nilangsub= $nilangsub+$row->nilai;        
                                             }
                                             
                            }

                            $cRet    .=" 
                                        <tr>                                    
                                         <td colspan='6' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'>Jumlah Anggaran Sub Kegiatan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($nilangsub,2,',','.')."</td></tr>
                                         <tr>                                    
                                         <td colspan='7'  align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;' width='40%'>&nbsp;</td></tr>
                                         </table> </pagebreak>";
                    }

                    


                            $cRet    .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'> 
                                        
                                         <tr>                                    
                                         <td colspan='5' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'>Jumlah Anggaran Kegiatan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$totp</td></tr>
                                         </table>";


            



            
                     
                  
                      
                

        if($ttd1!='tanpa'){
            $sqlttd1="SELECT top 1  nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' union all select '','','','' ORDER by nip";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u></td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' and left(kd_subkegiatan,len('$giat'))='$giat' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                
                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";



            
            $data['prev']= $cRet;    
            $judul='RKA-rincian_belanja_'.$id.'';
            switch($cetak) { 
            case 1;
               // echo($cRet);
                $this->master_pdf->_mpdf_down($giat,$nm_giat,$cRet,10,10,10,'0');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0; 
             //$this->master_pdf->_mpdf_margin('',$cRet,$kanan,$kiri,10,'1','',$atas,$bawah); 
                //echo ("<title>RKA Rincian Belanja</title>");
               echo($cRet);
            break;
            }
        }
