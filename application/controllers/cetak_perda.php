<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cetak_perda extends CI_Controller { 

   
    function __construct(){  
        
        parent::__construct();
        $this->load->model('cetak_perda_model');
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        }    
    } 

    function laporan_perda_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN I";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda');   
        $this->template->load('template','anggaran/lap_apbd/laporan_perda',$data) ; 
    }

    function laporan_perwa_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN I";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/laporan_perda',$data) ; 
    }

    function laporan_perda_pergeseran($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN I PERGESERAN";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda');   
        $this->template->load('template','anggaran/lap_apbd/pergeseran/laporan_perda_pergeseran',$data) ; 
    }

    function laporan_perwa_pergeseran($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN I PERGESERAN";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/pergeseran/laporan_perda_pergeseran',$data) ; 
    }

    function laporan_perda_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN I PERUBAHAN";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/laporan_perda_perubahan',$data) ; 
    }

    function laporan_perwa_perubahan($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN I PERUBAHAN";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/laporan_perda_perubahan',$data) ; 
    }

    function laporan_perda2_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN II";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/lampiran2_murni',$data) ; 
    }

    function laporan_perda2_pergeseran($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN II Pergeseran";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda Pergeseran');   
        $this->template->load('template','anggaran/lap_apbd/pergeseran/lampiran_perda2_pergeseran',$data) ; 
    }

    function laporan_perda2_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN II PERUBAHAN";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan perda PERUBAHAN');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda2_perubahan',$data) ; 
    }

    function laporan_perwa2_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN II";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/lampiranperwa2',$data) ; 
    }

    function laporan_perwa2_pergeseran($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN II PERGESERAN";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa Pergeseran');   
        $this->template->load('template','anggaran/lap_apbd/pergeseran/lampiran_perwa2_pergeseran',$data) ; 
    }

    function laporan_perwa2_perubahan($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN II PERGESERAN";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa Pergeseran');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perwa2_perubahan',$data) ; 
    }

    function laporan_perda3_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN III";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda III');   
        $this->template->load('template','anggaran/lap_apbd/lampiran3_murni',$data) ; 
    }

    function laporan_perda3_pergeseran($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN III PERGESERAN";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda III PERGESERAN');   
        $this->template->load('template','anggaran/lap_apbd/pergeseran/lampiran_perda3_pergeseran',$data) ; 
    }

    function laporan_perda3_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN III PERUBAHAN";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda III PERUBAHAN');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda3_perubahan',$data) ; 
    }

    function laporan_perwa3_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN III";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa III');   
        $this->template->load('template','anggaran/lap_apbd/lampiran3_murni',$data) ; 
    }

    function laporan_perda4_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN IV";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda IV');   
        $this->template->load('template','anggaran/lap_apbd/lampiran4_murnid',$data) ; 
    }

    function laporan_perda4_pergeseran($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN IV Pergeseran";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda IV Pergeseran');   
        $this->template->load('template','anggaran/lap_apbd/pergeseran/lampiran_perda4_pergeseran',$data) ; 
    }

    function laporan_perda4_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN IV Perubahan";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda IV Perubahan');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda4_perubahan',$data) ; 
    }

    function laporan_perwa4_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN IV";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa IV');   
        $this->template->load('template','anggaran/lap_apbd/lampiran4_murnid',$data) ; 
    }

    function laporan_perda5_murni($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN V";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/lampiran5_murni',$data) ; 
    }

    function laporan_perda5_pergeseran($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN V PERGESERAN";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda Pergeseran');   
        $this->template->load('template','anggaran/lap_apbd/pergeseran/lampiran_perda5_pergeseran',$data) ; 
    }

    function laporan_perda5_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN V PERUBAHAN";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda Perubahan');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda5_perubahan',$data) ; 
    }

    function laporan_perwa5_murni($jenis='PERWA'){
        $data['jenis']="PERWA LAMPIRAN V";
        $data['jenis1']="PERWA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perwa');   
        $this->template->load('template','anggaran/lap_apbd/lampiran5_murni',$data) ; 
    }

    function laporan_perda6_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN VI PERUBAHAN";
        $data['jenis1']="PERDA_PERUBAHAN";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda Perubahan');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda6_perubahan',$data) ; 
    }

    function laporan_perda7_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN VII PERUBAHAN";
        $data['jenis1']="PERDA_PERUBAHAN";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda Perubahan');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda7_perubahan',$data) ; 
    }

    function laporan_perda8_perubahan($jenis='PERDA'){
        $data['jenis']="PERDA LAMPIRAN VIII Perubahan";
        $data['jenis1']="PERDA_MURNI";
        $data['page_title']= 'CETAK';
        $this->template->set('title', 'Cetak '.$jenis.' Laporan Perda VIII Perubahan');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda8_perubahan',$data) ; 
    }

    function laporan_perda9_perubahan(){
        $data['jenis']="PERDA LAMPIRAN VII PERUBAHAN";
        $data['jenis1']="PERDA_PERUBAHAN";
        $data['page_title']= 'PERDA LAMPIRAN IX PERUBAHAN';
        $this->template->set('title', 'PERDA LAMPIRAN IX PERUBAHAN');   
        $this->template->load('template','anggaran/lap_apbd/perubahan/lampiran_perda9_perubahan',$data) ;  
    } 

    function cetak_perda_murni(){
        $tgl_ttd= $this->uri->segment(3);
        $ttd1   = $this->uri->segment(4);
        $ttd2   = $this->uri->segment(5);
        $id     = $this->uri->segment(6);
        $cetak  = $this->uri->segment(7);
        $detail = $this->uri->segment(8);
        $doc    = $this->uri->segment(9);
        $gaji   = $this->uri->segment(10);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_perda_model->cetak_perda_murni($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji);
     }
	 function cetak_perda_murni_ak(){
        $tgl_ttd= $this->uri->segment(3);
        $ttd1   = $this->uri->segment(4);
        $ttd2   = $this->uri->segment(5);
        $id     = $this->uri->segment(6);
        $cetak  = $this->uri->segment(7);
        $detail = $this->uri->segment(8);
        $doc    = $this->uri->segment(9);
        $gaji   = $this->uri->segment(10);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_perda_model->cetak_perda_murni_ak($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji);
     }

    function cetak_perda_pergeseran(){
        $tgl_ttd= $this->uri->segment(3);
        $ttd1   = $this->uri->segment(4);
        $ttd2   = $this->uri->segment(5);
        $id     = $this->uri->segment(6);
        $cetak  = $this->uri->segment(7);
        $detail = $this->uri->segment(8);
        $doc    = $this->uri->segment(9);
        $gaji   = $this->uri->segment(10);
        $status1= $this->uri->segment(12);
        $status2= $this->uri->segment(13);
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        echo $this->cetak_perda_model->cetak_perda_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji,$status1,$status2);
     }

    function akses_gaji(){
        $sql="SELECT kd_skpd, nm_skpd,
        case when status_keg=0 then 
        '<label class=\"switch\"><input type=\"checkbox\" onclick=\"javascript:aktif(\"'+kd_skpd+'\");\"><span class=\"slider round\"></span></label>' else
        '<label class=\"switch\"><input type=\"checkbox\" onclick=\"javascript:aktif(\"'+kd_skpd+'\");\"><span class=\"slider round\"></span></label>' end as status from(
        select a.kd_skpd, a.nm_skpd, b.status_keg from ms_skpd a left join trskpd b on a.kd_skpd=b.kd_skpd WHERE right(kd_sub_kegiatan,10)='01.2.02.01')xx";
    
        $data=array();
        $exe=$this->db->query($sql);
         foreach($exe->result() as $oke){
            $kd_skpd=$oke->kd_skpd;
            $nm_skpd=$oke->nm_skpd;
            $status=$oke->status;
            $data[]=array(
                'kd_skpd'=>$kd_skpd,
                'nm_skpd'=>$nm_skpd,
                'status'=>$status
            );
         }

        echo json_encode($data);
    }

    function program_sin($kd_skpd=''){
        $lccr = $this->input->post('q');

        $sql = "SELECT * from (

        SELECT left(kd_sub_kegiatan,7) kd_program, 
        (select nm_program from ms_program WHERE kd_program=left(kd_sub_kegiatan,7)) nm_program from trdrka 
        WHERE left(kd_rek6,1)='5' and left(no_trdrka,22)='$kd_skpd' and left(kd_sub_kegiatan,7) not in 
        (select kd_program from mapping_sinkron_anggaran where kd_skpd='$kd_skpd') GROUP BY left(kd_sub_kegiatan,7)

    ) iii where kd_program like '%$lccr%' or nm_program like '%$lccr%'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }  

    function kegiatan_sin($kd_skpd=''){
        $lccr = $this->input->post('q');

        $sql = "SELECT * from (

        SELECT left(kd_sub_kegiatan,12) kd_kegiatan, 
        (select nm_kegiatan from ms_kegiatan WHERE kd_kegiatan=left(kd_sub_kegiatan,12)) nm_kegiatan from trdrka 
        WHERE left(kd_rek6,1)='5' and left(no_trdrka,22)='$kd_skpd' and left(kd_sub_kegiatan,12) not in 
        (select kd_kegiatan from list_spm_dasar) GROUP BY left(kd_sub_kegiatan,12)

    ) iii where kd_kegiatan like '%$lccr%' or nm_kegiatan like '%$lccr%'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_program' => $resulte['kd_kegiatan'],  
                        'nm_program' => $resulte['nm_kegiatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }

    function jenis_prioritas(){
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM sinkron_anggaran";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'jns_prioritas' => $resulte['jns_prioritas'],  
                        'nm_prioritas' => $resulte['nm_prioritas']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
            $query1->free_result();        
    }  

    function jenis_spm_dasar(){
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM spm_pelayanan_dasar where bidang_spm like '%$lccr%' or jns_pelayanan_dasar like '%$lccr%'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kode' => $resulte['kode'],  
                        'bidang_spm' => $resulte['bidang_spm'],
                        'jns_pelayanan_dasar' => $resulte['jns_pelayanan_dasar']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
            $query1->free_result();        
    }     

    function simpan_mapping_perda6(){
        $kode=$this->input->post('jns_prio');
        $kegiatan=$this->input->post('program');
        $skpd=$this->input->post('skpd');


        echo $sql=$this->db->query("INSERT into list_spm_dasar (kode,kd_kegiatan, kd_skpd) 
            values('$kode','$kegiatan','$skpd')");
    }  

    function simpan_mapping_sin(){
        $nm_prio=$this->input->post('nm_prio');
        $jns_prio=$this->input->post('jns_prio');
        $program=$this->input->post('program');
        $skpd=$this->input->post('skpd');


        echo $sql=$this->db->query("INSERT into mapping_sinkron_anggaran (kd_program,jns_prioritas,nm_prioritas, kd_skpd) 
            values('$program','$jns_prio','$nm_prio','$skpd')");
    }  

    function tabel_sinkron($skpd='') {    
        $sql = "select *, b.nm_program from mapping_sinkron_anggaran a inner join ms_program b on a.kd_program=b.kd_program where kd_skpd='$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,
                        'kd_program' => $resulte['kd_program'],
                        'nm_program' => $resulte['nm_program'],                          
                        'nm_prioritas' => $resulte['nm_prioritas'],  
                        'jns_prioritas' => $resulte['jns_prioritas']                   
                        );
                        $ii++;
        }
        echo json_encode($result);
    } 

    function tabel_bidang_spm($skpd='') {    
        $sql = "SELECT a.*,  (select nm_kegiatan from ms_kegiatan where kd_kegiatan=a.kd_kegiatan) nm_program, (select bidang_spm from spm_pelayanan_dasar where kode=a.kode) bidang_spm, (select jns_pelayanan_dasar from spm_pelayanan_dasar where kode=a.kode ) jns_pelayanan_dasar from list_spm_dasar a where a.kd_skpd='$skpd'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,
                        'kd_program' => $resulte['kd_kegiatan'],
                        'nm_program' => $resulte['nm_program'],                          
                        'bidang_spm' => $resulte['bidang_spm'],  
                        'jns_pelayanan_dasar' => $resulte['jns_pelayanan_dasar']                   
                        );
                        $ii++;
        }
        echo json_encode($result);
    } 

    function hapus_sinkron(){
        $program=$this->input->post('program');

        echo $sql=$this->db->query("DELETE from mapping_sinkron_anggaran where kd_program='$program'");
    }     

    function hapus_bidang_spm(){
        $program=$this->input->post('program');

        echo $sql=$this->db->query("DELETE from list_spm_dasar where kd_kegiatan='$program'");
    }  

    function lampiran2_murni($tgl='',$doc='',$pdf=''){
        echo $this->cetak_perda_model->lampiran2_murni($tgl,$doc,$pdf);
    }
	function lampiran2_murni_ak($tgl='',$doc='',$pdf=''){
        echo $this->cetak_perda_model->lampiran2_murni_ak($tgl,$doc,$pdf);
    }

    function lampiran2_pergeseran($tgl='',$doc='',$pdf='',$status_anggaran1='',$status_anggaran2=''){
        echo $this->cetak_perda_model->lampiran2_pergeseran($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2);
    }

    function lampiran3_murni($tgl='',$doc='',$pdf='',$skpd='',$urusan=''){
        echo $this->cetak_perda_model->lampiran3_murni($tgl,$doc,$pdf,$skpd,$urusan);
    }

    function lampiran3_pergeseran($tgl='',$doc='',$pdf='',$skpd='',$status_anggaran1='',$status_anggaran2='',$urusan=''){
        echo $this->cetak_perda_model->lampiran3_pergeseran($tgl,$doc,$pdf,$skpd,$status_anggaran1,$status_anggaran2,$urusan);
    }

    function lampiran4_murnid($tgl='',$doc='',$pdf='',$skpd='',$urusan=''){
        echo $this->cetak_perda_model->lampiran4_murnid($tgl,$doc,$pdf,$skpd,$urusan);
    }

    function lampiran4_pergeseran($tgl='',$doc='',$pdf='',$status_anggaran1='',$status_anggaran2='',$skpd='',$urusan=''){
        echo $this->cetak_perda_model->lampiran4_pergeseran($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2,$skpd,$urusan);
    }

    function lampiran5_murni($tgl='',$doc='',$pdf=''){
        echo $this->cetak_perda_model->lampiran5_murni($tgl,$doc,$pdf);
    }

    function lampiran5_pergeseran($tgl='',$doc='',$pdf='',$status_anggaran1='',$status_anggaran2=''){
        echo $this->cetak_perda_model->lampiran5_pergeseran($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2);
    }

    function perda6($tgl_ttd='',$perda='',$dowload='',$jenis_anggaran='',$judul=''){
        echo $this->cetak_perda_model->perda6($tgl_ttd,$perda,$dowload,$jenis_anggaran,$judul);
    }

    function perda7($tgl_ttd='',$perda='',$dowload='',$jenis_anggaran='',$judul=''){
       echo $this->cetak_perda_model->perda7($tgl_ttd,$perda,$dowload,$jenis_anggaran,$judul);
    }

    function perda8($tgl='',$doc='',$pdf='',$status_anggaran1='',$status_anggaran2='',$skpd='',$urusan=''){
        echo $this->cetak_perda_model->perda8($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2,$skpd,$urusan);
    }

    function perda9($jns,$anggaran,$tgl,$judul){
        echo $this->cetak_perda_model->perda9($jns,$anggaran,$tgl,$judul);
    }

    function perda10(){
        echo $this->cetak_perda_model->perda10();
    }

    function perda11(){
        echo $this->cetak_perda_model->perda11();
    }

    function perda12(){
        echo $this->cetak_perda_model->perda12();
    }

    function perda13(){
        echo $this->cetak_perda_model->perda13();
    }

    function perda14(){
        echo $this->cetak_perda_model->perda14();
    }

    function perda15(){
        echo $this->cetak_perda_model->perda15();
    }

    function perda16(){
        echo $this->cetak_perda_model->perda16();
    }
}

