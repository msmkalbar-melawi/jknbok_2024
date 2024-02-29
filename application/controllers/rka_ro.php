<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Rka_ro extends CI_Controller
{

    public $ppkd = "";
    public $ppkd1 = "";

    function __construct()
    {
        parent::__construct();
        $this->load->model('support');
        $this->load->model('cek_anggaran_model');
        if ($this->session->userdata('pcNama') == '') {
            redirect('welcome');
        }
    }
    //modul input angkas all
    function input_angkas()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO ';
        $data['jns_anggaran'] = $this->cek_anggaran_model->cek_anggaran($kdskpd);
        $this->template->set('title', 'INPUT ANGKAS');
        $this->template->load('template', 'anggaran/angkas_penyempurnaan/angkas_rop2', $data);
    }

    function load_jangkas($jang)
    {
        // $lccr = $this->input->post('q');  
        $result = $this->master_model->load_jangkas($jang);
        echo $result;
    }

    function load_jang()
    {
        // $lccr = $this->input->post('q');  
        $result = $this->master_model->load_jang();
        echo $result;
    }

    //
    function angkas_ro()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO MURNI';
        $this->template->set('title', 'INPUT ANGKAS MURNI');
        $this->template->load('template', 'anggaran/angkas/angkas_ro', $data);
    }

    function angkas_ro1()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO MURNI GESER 1';
        $this->template->set('title', 'INPUT ANGKAS MURNI GESER 1');
        $this->template->load('template', 'anggaran/angkas/angkas_ro1', $data);
    }

    function angkas_penyempurnaan()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PENYEMPURNAAN';
        $this->template->set('title', 'INPUT ANGKAS PENYEMPURNAAN');
        $this->template->load('template', 'anggaran/angkas_penyempurnaan/angkas_rop', $data);
    }

    function anggaran_kas_penyempurnaan1()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PENYEMPURNAAN 1 GESER 1';
        $this->template->set('title', 'INPUT ANGKAS PENYEMPURNAAN');
        $this->template->load('template', 'anggaran/angkas_penyempurnaan/angkas_rop11', $data);
    }

    function angkas_sempurna()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO MURNI';
        $this->template->set('title', 'INPUT ANGKAS MURNI');
        $this->template->load('template', 'anggaran/angkas/angkas_sempurna', $data);
    }

    function angkas_geser()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PERGESERAN';
        $this->template->set('title', 'INPUT ANGKAS PERGESERAN');
        $this->template->load('template', 'anggaran/angkas/angkas_geser', $data);
    }

    function cek_anggaran_geser()
    {
        $data['page_title'] = 'Cek Anggaran Kas Pergeseran';
        $this->template->set('title', 'Cek Anggaran Kas Persgeseran');
        $this->template->load('template', 'anggaran/angkas/cek_anggaran_geser', $data);
    }

    function preview_cetakan_cek_anggaran_geser()
    {
        $id = $this->uri->segment(3);
        $cetak = $this->uri->segment(4);
        $status_ang = $this->uri->segment(5);
        echo $this->angkas_ro_model->preview_cetakan_cek_anggaran_geser($id, $cetak, $status_ang);
    }

    function cetak_angkas_giat_geser($jns_anggaran = '')
    {
        $data['jns_ang']   = $jns_anggaran;
        $data['page_title'] = 'Cetak Angkas Murni Subkegiatan';
        $this->template->set('title', 'Cetak Angkas Murni Subkegiatan');
        $this->template->load('template', 'anggaran/angkas/cetak_angkas_giat_geser', $data);
    }

    function angkas_ubah()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PERUBAHAN';
        $this->template->set('title', 'INPUT ANGKAS PERUBAHAN ');
        $this->template->load('template', 'anggaran/angkas/angkas_ubah', $data);
    }

    function skpduser()
    {
        $lccr = $this->input->post('q');
        $result = $this->master_model->skpduser($lccr);
        echo json_encode($result);
    }

    function ambil_rek_angkas_ro($kegiatan = '', $skpd = '')
    {
        $result = $this->angkas_ro_model->ambil_rek_angkas_ro($kegiatan, $skpd);
        echo json_encode($result);
    }

    // function ambil_rek_angkas_ro_geser($kegiatan = '', $skpd = '')
    // {
    //     $result = $this->angkas_ro_model->ambil_rek_angkas_ro_geser($kegiatan, $skpd);
    //     echo json_encode($result);
    // }

    function ambil_rek_angkas_ro_geser()
    {
        $kegiatan   = $this->uri->segment(3);
        $skpd      = $this->uri->segment(4);
        $rak        = $this->uri->segment(5);
        // echo($jns_ang); 

        $result = $this->angkas_ro_model->ambil_rek_angkas_ro_geser($kegiatan, $skpd, $rak);
        echo json_encode($result);
    }

    function load_giat($cskpd = '', $rak = '', $jns_ang = '')
    {
        // echo($jns_ang);

        $lccr = $this->input->post('q');
        $result = $this->angkas_ro_model->load_giat($cskpd, $lccr, $rak, $jns_ang);
        echo json_encode($result);
    }

    function load_giat_sempurna($cskpd = '', $lccr = '')
    {
        $cskpd = $this->uri->segment(3);
        $jns_ang = $this->uri->segment(4);
        $lccr = $this->input->post('q');
        $result = $this->angkas_ro_model->load_giat_sempurna($cskpd, $lccr, $jns_ang);
        echo json_encode($result);
    }

    function total_triwulan($status = '', $skpd = '')
    {
        $kd_kegiatan = $this->input->post('kegiatan');
        $result = $this->angkas_ro_model->total_triwulan($status, $kd_kegiatan, $skpd);
        echo json_encode($result);
    }

    function total_triwulan_geser($status = '', $skpd = '')
    {
        // echo($status);
        $kd_kegiatan = $this->input->post('kegiatan');
        $result = $this->angkas_ro_model->total_triwulan_geser($status, $kd_kegiatan, $skpd);
        echo json_encode($result);
    }

    function total_triwulan_sempurna($status = '', $skpd = '')
    {
        $kd_kegiatan = $this->input->post('kegiatan');
        $result = $this->angkas_ro_model->total_triwulan($status, $kd_kegiatan, $skpd);
        echo json_encode($result);
    }

    function load_trdskpd($status = '', $skpd = '')
    {
        $kegiatan = $this->input->post('p');
        $rekening = $this->input->post('s');
        $result = $this->angkas_ro_model->load_trdskpd($kegiatan, $rekening, $status, $skpd);
        echo json_encode($result);
    }

    function load_trdskpd_geser($status = '', $skpd = '')
    {
        // echo($jns_ang);
        $kegiatan = $this->input->post('p');
        $rekening = $this->input->post('s');
        $result = $this->angkas_ro_model->load_trdskpd_geser($kegiatan, $rekening, $status, $skpd);
        echo json_encode($result);
    }

    function simpan_trskpd_ro()
    {
        $id  = $this->session->userdata('pcUser');
        $cskpd = $this->input->post('cskpd');
        $cgiat = $this->input->post('cgiat');
        $crek5 = $this->input->post('crek5');
        $bln1 = $this->input->post('jan');
        $bln2 = $this->input->post('feb');
        $bln3 = $this->input->post('mar');
        $bln4 = $this->input->post('apr');
        $bln5 = $this->input->post('mei');
        $bln6 = $this->input->post('jun');
        $bln7 = $this->input->post('jul');
        $bln8 = $this->input->post('ags');
        $bln9 = $this->input->post('sep');
        $bln10 = $this->input->post('okt');
        $bln11 = $this->input->post('nov');
        $bln12 = $this->input->post('des');
        $tr1 = $this->input->post('tr1');
        $tr2 = $this->input->post('tr2');
        $tr3 = $this->input->post('tr3');
        $tr4 = $this->input->post('tr4');
        $status = $this->input->post('csts');
        $jns_ang = $this->input->post('jns_ang');
        $tabell = 'trdskpd_ro';
        $user_name  =  $this->session->userdata('pcNama');
        // echo($cgiat);
        // echo($crek5);
        $result = $this->angkas_ro_model->simpan_trskpd_ro($jns_ang, $status, $cskpd, $cgiat, $crek5, $bln1, $bln2, $bln3, $bln4, $bln5, $bln6, $bln7, $bln8, $bln9, $bln10, $bln11, $bln12, $tr1, $tr2, $tr3, $tr4, $user_name);

        echo $result;
    }

    function hapus_angkas()
    {
        $kd_skpd  = $this->input->post('skpd');
        $sub_keg = $this->input->post('kd_sub_keg');
        $rek6 = $this->input->post('kd_rek6');

        // $xx = array('skpd' => $kd_skpd,
        //             'kegiatan' => $sub_keg,
        //             'rek6' => $rek6);

        $sql = "delete trdskpd_ro WHERE kd_skpd = '$kd_skpd' AND kd_sub_kegiatan = '$sub_keg' AND kd_rek6 = '$rek6'";
        // print_r($sql);die();
        $this->db->query($sql);
    }

    function realisasi_angkas_ro($skpd = '')
    {
        $skpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek5 = $this->input->post('rek5');
        $result = $this->angkas_ro_model->realisasi_angkas_ro($skpd, $kegiatan, $rek5);
        echo $result;
    }

    function realisasi_angkas_ro_bulan($skpd = '')
    {
        $skpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek5 = $this->input->post('rek5');
        $result = $this->angkas_ro_model->realisasi_angkas_ro_bulan($skpd, $kegiatan, $rek5);
        echo $result;
    }

    function  tanggal_format_indonesia($tgl)
    {
        $tanggal  =  substr($tgl, 8, 2);
        $bulan  = $this->support->getBulan(substr($tgl, 5, 2));
        $tahun  =  substr($tgl, 0, 4);
        return  $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    function cetak_angkas_ro($jns_anggaran = '')
    {
        $data['jns_ang']   = $jns_anggaran;
        $data['page_title'] = 'Cetak Angkas Murni RO';
        $this->template->set('title', 'Cetak Angkas Murni RO');
        $this->template->load('template', 'anggaran/angkas/cetak_angkas_ro', $data);
    }

    function cetak_angkas_giat($jns_anggaran = '')
    {
        $data['jns_ang']   = $jns_anggaran;
        $data['page_title'] = 'Cetak Angkas Murni Subkegiatan';
        $this->template->set('title', 'Cetak Angkas Murni Subkegiatan');
        $this->template->load('template', 'anggaran/angkas/cetak_angkas_giat', $data);
    }

    function cetak_angkas_ro_preview($aa = '', $tgl = '', $ttd1 = '', $ttd2 = '', $tj_ang = '', $tj_angkas = '', $skpd = '', $giat = '', $hit = '', $cetak = '')
    {
        echo $this->angkas_ro_model->cetak_angkas_ro($tgl, $ttd1, $ttd2, $tj_ang, $tj_angkas, $skpd, $giat, $hit, $cetak);
    }


    // function cetak_angkas_ro_preview($aa = '', $tgl = '', $ttd1 = '', $ttd2 = '', $jenis = '', $skpd = '', $giat = '', $hit = '', $cetak = '')
    // {
    //     echo $this->angkas_ro_model->cetak_angkas_ro($tgl, $ttd1, $ttd2, $jenis, $skpd, $giat, $hit, $cetak);
    // }

    function cetak_angkas_giat_preview($aa = '', $tgl = '', $ttd1 = '', $ttd2 = '', $jenis = '', $skpd = '', $cetak = '', $hit = '')
    {
        echo $this->angkas_ro_model->cetak_angkas_giat($tgl, $ttd1, $ttd2, $jenis, $skpd, $cetak, $hit);
    }

    // function preview_cetakan_cek_anggaran()
    // {
    //     $id = $this->uri->segment(3);
    //     $cetak = $this->uri->segment(4);
    //     $status_ang = $this->uri->segment(5);
    //     echo $this->angkas_ro_model->preview_cetakan_cek_anggaran($id, $cetak, $status_ang);
    // }
    function cek_angkas()
    {
        $data['page_title'] = 'Cek Anggaran';
        $this->template->set('title', 'Cek Anggaran');
        $this->template->load('template', 'anggaran/angkas/cek_anggaran', $data);
    }

    function preview_cetakan_cek_anggaran()
    {

        $id = $this->uri->segment(3);
        $sts_angkas = $this->uri->segment(6);
        $cetak = $this->uri->segment(4);
        $status_ang = $this->uri->segment(5);
        if ($sts_angkas == 'murni') {
            $field_angkas = 'nilai';
        } else if ($sts_angkas == 'susun') {
            $field_angkas = 'nilai_susun';
        } else if ($sts_angkas == 'murni_geser1') {
            $field_angkas = 'nilai_susun1';
        } else if ($sts_angkas == 'murni_geser2') {
            $field_angkas = 'nilai_susun2';
        } else if ($sts_angkas == 'murni_geser3') {
            $field_angkas = 'nilai_susun3';
        } else if ($sts_angkas == 'murni_geser4') {
            $field_angkas = 'nilai_susun4';
        } else if ($sts_angkas == 'murni_geser5') {
            $field_angkas = 'nilai_susun5';
        } else if ($sts_angkas == 'sempurna') {
            $field_angkas = 'nilai_sempurna';
        } else if ($sts_angkas == 'sempurna1_geser1') {
            $field_angkas = 'nilai_sempurna11';
        } else if ($sts_angkas == 'sempurna1_geser2') {
            $field_angkas = 'nilai_sempurna12';
        } else if ($sts_angkas == 'sempurna1_geser3') {
            $field_angkas = 'nilai_sempurna13';
        } else if ($sts_angkas == 'sempurna1_geser4') {
            $field_angkas = 'nilai_sempurna14';
        } else if ($sts_angkas == 'sempurna1_geser5') {
            $field_angkas = 'nilai_sempurna15';
        } else if ($sts_angkas == 'sempurna2') {
            $field_angkas = 'nilai_sempurna2';
        } else if ($sts_angkas == 'sempurna2_geser1') {
            $field_angkas = 'nilai_sempurna21';
        } else if ($sts_angkas == 'sempurna2_geser2') {
            $field_angkas = 'nilai_sempurna22';
        } else if ($sts_angkas == 'sempurna2_geser3') {
            $field_angkas = 'nilai_sempurna23';
        } else if ($sts_angkas == 'sempurna2_geser4') {
            $field_angkas = 'nilai_sempurna24';
        } else if ($sts_angkas == 'sempurna2_geser5') {
            $field_angkas = 'nilai_sempurna25';
        } else if ($sts_angkas == 'sempurna3') {
            $field_angkas = 'nilai_sempurna3';
        } else if ($sts_angkas == 'sempurna3_geser1') {
            $field_angkas = 'nilai_sempurna31';
        } else if ($sts_angkas == 'sempurna3_geser2') {
            $field_angkas = 'nilai_sempurna32';
        } else if ($sts_angkas == 'sempurna3_geser3') {
            $field_angkas = 'nilai_sempurna33';
        } else if ($sts_angkas == 'sempurna3_geser4') {
            $field_angkas = 'nilai_sempurna34';
        } else if ($sts_angkas == 'sempurna3_geser5') {
            $field_angkas = 'nilai_sempurna35';
        } else if ($sts_angkas == 'sempurna4') {
            $field_angkas = 'nilai_sempurna4';
        } else if ($sts_angkas == 'sempurna4_geser1') {
            $field_angkas = 'nilai_sempurna41';
        } else if ($sts_angkas == 'sempurna4_geser2') {
            $field_angkas = 'nilai_sempurna42';
        } else if ($sts_angkas == 'sempurna4_geser3') {
            $field_angkas = 'nilai_sempurna43';
        } else if ($sts_angkas == 'sempurna4_geser4') {
            $field_angkas = 'nilai_sempurna44';
        } else if ($sts_angkas == 'sempurna4_geser5') {
            $field_angkas = 'nilai_sempurna45';
        } else if ($sts_angkas == 'sempurna5') {
            $field_angkas = 'nilai_sempurna5';
        } else if ($sts_angkas == 'sempurna5_geser1') {
            $field_angkas = 'nilai_sempurna51';
        } else if ($sts_angkas == 'sempurna5_geser2') {
            $field_angkas = 'nilai_sempurna52';
        } else if ($sts_angkas == 'sempurna5_geser3') {
            $field_angkas = 'nilai_sempurna53';
        } else if ($sts_angkas == 'sempurna5_geser4') {
            $field_angkas = 'nilai_sempurna1';
        } else if ($sts_angkas == 'sempurna5_geser5') {
            $field_angkas = 'nilai_sempurna55';
        } else if ($sts_angkas == 'ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($sts_angkas == 'ubah11') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'ubah12') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'ubah13') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'ubah14') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }


        echo $this->angkas_ro_model->preview_cetakan_cek_anggaran($id, $cetak, $status_ang, $field_angkas);
    }

    function load_ttd_unit($skpd)
    {
        $lccr = $this->input->post('q');
        $result = $this->master_ttd->load_ttd_unit($skpd);
        echo $result;
    }

    function load_ttd_bud()
    {
        $lccr = $this->input->post('q');
        $result = $this->master_ttd->load_ttd_bud();
        echo $result;
    }

    function ambil_rak()
    {
        $result = $this->angkas_ro_model->ambil_rak();
        echo json_encode($result);
    }
    function ambil_rak_angkas()
    {
        $jns_ang = $this->uri->segment(3);
        $result = $this->angkas_ro_model->ambil_rak_angkas($jns_ang);
        echo json_encode($result);
    }

    function stts_kunci_angkas()
    {
        $kunci_rak  = $this->input->post('kunci_rak');
        $kd_skpd    = $this->session->userdata('kdskpd');
        $result     = $this->angkas_ro_model->stts_kunci_angkas($kunci_rak, $kd_skpd);
        echo json_encode($result);
    }
}
