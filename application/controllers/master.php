<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Master extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }

    function test()
    {

        $this->load->dbutil();
        $query = $this->db->query("SELECT * FROM ms_bank");
        $config = array(
            'root'    => 'root',
            'element' => 'element',
            'newline' => "\n",
            'tab'    => "\t"
        );

        echo $this->dbutil->xml_from_result($query, $config);
    }

    function fungsi()
    {
        $this->index('0', 'ms_fungsi', 'kd_fungsi', 'nm_fungsi', 'Fungsi', 'fungsi', '');
    }

    function urusan()
    {
        $this->index('0', 'ms_urusan', 'kd_urusan', 'nm_urusan', 'Urusan', 'urusan', '');
    }

    function skpd()
    {
        $this->index('0', 'ms_skpd', 'kd_skpd', 'nm_skpd', 'SKPD', 'skpd', '');
    }

    function unit()
    {
        $this->index('0', 'ms_unit', 'kd_unit', 'nm_unit', 'Unit Kerja', 'unit', '');
    }

    function program()
    {
        $this->index('0', 'm_prog', 'kd_program', 'nm_program', 'Program', 'program', '');
    }

    function kegiatan()
    {
        $this->index('0', 'm_giat', 'kd_kegiatan', 'nm_kegiatan', 'Kegiatan', 'kegiatan', '');
    }

    function rek1()
    {
        $this->index('0', 'ms_rek1', 'kd_rek1', 'nm_rek1', 'Rekening Akun', 'rek1', '');
    }

    function rek2()
    {
        $this->index('0', 'ms_rek2', 'kd_rek2', 'nm_rek2', 'Rekening Kelompok', 'rek2', '');
    }

    function rek3()
    {
        $this->index('0', 'ms_rek3', 'kd_rek3', 'nm_rek3', 'Rekening Jenis', 'rek3', '');
    }

    function rek4()
    {
        $this->index('0', 'ms_rek4', 'kd_rek4', 'nm_rek4', 'Rekening Objek', 'rek4', '');
    }

    function rek5()
    {
        $this->index('0', 'ms_rek5', 'kd_rek5', 'nm_rek5', 'Rekening Rincian Objek', 'rek5', '');
    }

    function ttd()
    {
        $this->index('0', 'ms_ttd', 'nip', 'nama', 'Penandatangan', 'ttd', '');
    }

    function bank()
    {
        $this->index('0', 'ms_bank', 'kode', 'nama', 'Bank', 'bank', '');
    }
    function user()
    {
        $this->index('0', '[user]', 'id_user', 'nama', 'User', 'user', '');
    }
    function user_edit()
    {
        $this->index('0', '[user]', 'id_user', 'nama', 'Edit User', 'user_edit', '');
    }

    function ganti_skpd()
    {
        $this->index3('0', '[user]', 'id_user', 'nama', 'Ganti SKPD', 'ganti_skpd', '');
    }
    function set_skpd()
    {
        $data['page_title'] = 'GANTI SKPD';
        $this->template->set('title', 'GANTI SKPD');
        $this->template->load('template', 'master/ganti_skpd/gantiskpd', $data);
    }

    function mrekening()
    {
        $data['page_title'] = 'Master Rekening Bank';
        $this->template->set('title', 'Master Rekening Bank');
        $this->template->load('template', 'master/rekening/mrekening', $data);
    }

    function simpan_master_cek()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel WHERE $cid='$lcid' and kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                $msg = array('pesan' => '2');
                echo json_encode($msg);
                exit();
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        }
    }

    function mkontrak()
    {
        $data['page_title'] = 'INPUT RESTITUSI';
        $this->template->set('title', 'INPUT RESTITUSI');
        $this->template->load('template', 'master/mkontrak', $data);
    }

    function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $kd_skpd  = $this->session->userdata('kdskpd');

        $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where no_kontrak='$nomor' and kd_skpd = '$kd_skpd' ");
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        }
    }

    function load_kontrak()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_kontrak) like upper('%$kriteria%') or upper(nilai) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from ms_kontrak WHERE kd_skpd='$skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT TOP $rows * from ms_kontrak WHERE no_kontrak+'.'+kd_skpd not in (select TOP $offset no_kontrak+'.'+kd_skpd from ms_kontrak where kd_skpd='$skpd' ) and kd_skpd='$skpd' $where order by no_kontrak";

        // SELECT TOP $rows * from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where AND no_bukti not in (SELECT TOP $offset no_bukti from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where order by no_bukti) order by no_bukti,kd_skpd 
        $query1 = $this->db->query($sql);

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_kontrak' => $resulte['no_kontrak'],
                'nilai'      => number_format($resulte['nilai'], "2", ".", ","),
                'nm_kerja'   => $resulte['nm_kerja'],
                'nmpel'     => $resulte['nmpel'],
                'tgl_kerja'  => $resulte['tgl_kerja']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_kontrak_lama()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $cek = explode(".", $skpd);
        $ck = $cek[3];
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        if ($ck == "00") {
            $par = "kd_skpd='$skpd'";
        } else {
            $par = "kd_skpd='$skpd'";
        }


        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(nilai) like upper('%$kriteria%') or no_kontrak like'%$kriteria%')";
        }


        $sql = "SELECT count(*) as total from ms_kontrak WHERE kd_skpd='$skpd'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        $sql = "SELECT TOP $rows * from ms_kontrak WHERE no_kontrak+'.'+kd_skpd not in (select TOP $offset no_kontrak+'.'+kd_skpd from ms_kontrak where kd_skpd='$skpd' ) and $par $where order by no_kontrak";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id'         => $ii,
                'no_kontrak' => $resulte['no_kontrak'],
                'nilai'      => number_format($resulte['nilai'], "2", ".", ","),
                'nm_kerja'   => $resulte['nm_kerja'],
                'nmpel'     => $resulte['nmpel'],
                'tgl_kerja'  => $resulte['tgl_kerja']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();

        // echo json_encode($result);

    }

    function simpan_master_kontrak()
    {
        $tabel   = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $nkontrak = $this->input->post('nil_kontrak');
        $sql     = 0;

        $sql = "select $cid from $tabel WHERE $cid='$lcid' and nilai='$nkontrak' ";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                echo '2';
            } else {
                echo '0';
            }
        }
    }

    function hapus_master_kontrak()
    {
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $skpd = $this->session->userdata('kdskpd');
        $hasil = $this->db->query("SELECT COUNT(a.no_kontrak) as jumlah FROM ms_kontrak a INNER JOIN trhtagih b on b.kontrak=a.no_kontrak AND a.kd_skpd=b.kd_skpd WHERE b.kd_skpd='$skpd' AND a.no_kontrak='$cnid'");

        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
            $csql = "delete from $ctabel where no_kontrak = '$cnid' and kd_skpd='$skpd'";
            $asg = $this->db->query($csql);
        }
    }

    function load_rek_bank()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "and (upper(a.nm_rekening) like upper('%$kriteria%') or a.rekening like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from ms_rekening_bank a where a.kd_skpd = '$kd_skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT a.*,b.nama from ms_rekening_bank a left join ms_bank b on b.kode = a.bank where a.kd_skpd = '$kd_skpd' $where order by nm_rekening,rekening "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['jenis'] == '1') {
                $jns_rek = "Rekening Pegawai";
            } else if ($resulte['jenis'] == '2') {
                $jns_rek = "Rekanan Pihak Ketiga";
            } else if ($resulte['jenis'] == '3') {
                $jns_rek = "Penampung Pajak";
            }

            $row[] = array(
                'id' => $ii,
                'rekening' => $resulte['rekening'],
                'nm_rekening' => $resulte['nm_rekening'],
                'bank' => $resulte['bank'],
                'nmbank' => $resulte['nama'],
                'kd_skpd' => $resulte['kd_skpd'],
                'jenis' => $resulte['jenis'],
                'ket' => $resulte['keterangan'],
                'nm_jenis' => $jns_rek
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function hapus_master_rek()
    {
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $skpd = $this->session->userdata('kdskpd');

        $csql = "delete from $ctabel where $cid = '$cnid' and kd_skpd='$skpd'";

        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function user_online()
    {
        $this->index2('0', '[user]', 'id_user', 'nama', 'User Online', 'user_online', '');
    }

    function sclient()
    {
        $data['page_title'] = 'MASTER SCLIENT';
        $this->template->set('title', 'INPUT MASTER SCLIENT');
        $this->template->load('template', 'master/sclient', $data);
    }

    function mperusahaan()
    {
        $data['page_title'] = 'Master Perusahaan';
        $this->template->set('title', 'Master Perusahaan');
        $this->template->load('template', 'master/perusahaan/mperusahaan', $data);
    }

    function tapd()
    {
        $data['page_title'] = 'MASTER TAPD';
        $this->template->set('title', 'INPUT TAPD');
        $this->template->load('template', 'master/tapd', $data);
    }

    function index($offset = 0, $lctabel, $field, $field1, $judul, $list, $lccari)
    {
        $data['page_title'] = "Master Data $judul";
        if (empty($lccari)) {
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        } else {
            $total_rows = $this->master_model->get_count_teang($lctabel, $field, $field1, $lccari);
            $lc = "";
        }
        if (empty($lccari)) {
            $config['base_url']        = site_url("master/" . $list);
        } else {
            $config['base_url']        = site_url("master/cari_" . $list);
        }
        $config['total_rows']     = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']     = 3;
        $config['num_links']     = 5;
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
        $limit                    = $config['per_page'];
        $offset                 = $this->uri->segment(3);
        $offset                 = (!is_numeric($offset) || $offset < 1) ? 0 : $offset;

        if (empty($offset)) {
            $offset = 0;
        }
        //$data['isi']=$this->aktif_menu();
        //$data['isi']=$this->aktif_menu(); 
        //$data['isi']= $this->session->userdata('lcisi');         
        //$data['list'] 		= $this->master_model->getAll($lctabel,$field,$limit, $offset);
        if (empty($lccari)) {
            $data['list']         = $this->master_model->getAll($lctabel, $field, $limit, $offset);
        } else {
            $data['list']         = $this->master_model->getCari($lctabel, $field, $field1, $limit, $offset, $lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;

        $this->pagination->initialize($config);
        $a = $judul;
        $this->template->set('title', 'Master Data ');
        $this->template->load('template', "master/" . $list . "/list", $data);
    }


    function index2($offset = 0, $lctabel, $field, $field1, $judul, $list, $lccari)
    {
        $data['page_title'] = "Master Data $judul";
        if (empty($lccari)) {
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        } else {
            $total_rows = $this->master_model->get_count_teang($lctabel, $field, $field1, $lccari);
            $lc = "";
        }
        if (empty($lccari)) {
            $config['base_url']        = site_url("master/" . $list);
        } else {
            $config['base_url']        = site_url("master/cari_" . $list);
        }
        $config['total_rows']     = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']     = 3;
        $config['num_links']     = 5;
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
        $limit                    = $config['per_page'];
        $offset                 = $this->uri->segment(3);
        $offset                 = (!is_numeric($offset) || $offset < 1) ? 0 : $offset;

        if (empty($offset)) {
            $offset = 0;
        }
        //$data['isi']=$this->aktif_menu();
        //$data['isi']=$this->aktif_menu(); 
        //$data['isi']= $this->session->userdata('lcisi');         
        //$data['list'] 		= $this->master_model->getAll($lctabel,$field,$limit, $offset);
        if (empty($lccari)) {
            $data['list']         = $this->master_model->getAll2($lctabel, $field, $limit, $offset);
        } else {
            $data['list']         = $this->master_model->getCari($lctabel, $field, $field1, $limit, $offset, $lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;

        $this->pagination->initialize($config);
        $a = $judul;
        $this->template->set('title', 'Master Data ');
        $this->template->load('template', "master/" . $list . "/list", $data);
    }


    function index3($offset = 0, $lctabel, $field, $field1, $judul, $list, $lccari)
    {
        $data['page_title'] = "Master Data $judul";
        if (empty($lccari)) {
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        } else {
            $total_rows = $this->master_model->get_count_teang($lctabel, $field, $field1, $lccari);
            $lc = "";
        }
        if (empty($lccari)) {
            $config['base_url']        = site_url("master/" . $list);
        } else {
            $config['base_url']        = site_url("master/cari_" . $list);
        }
        $config['total_rows']     = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']     = 3;
        $config['num_links']     = 5;
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
        $limit                    = $config['per_page'];
        $offset                 = $this->uri->segment(3);
        $offset                 = (!is_numeric($offset) || $offset < 1) ? 0 : $offset;

        if (empty($offset)) {
            $offset = 0;
        }
        //$data['isi']=$this->aktif_menu();
        //$data['isi']=$this->aktif_menu(); 
        //$data['isi']= $this->session->userdata('lcisi');         
        //$data['list'] 		= $this->master_model->getAll($lctabel,$field,$limit, $offset);
        if (empty($lccari)) {
            $data['list']         = $this->master_model->getAll3($lctabel, $field, $limit, $offset);
        } else {
            $data['list']         = $this->master_model->getCari($lctabel, $field, $field1, $limit, $offset, $lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;

        $this->pagination->initialize($config);
        $a = $judul;
        $this->template->set('title', 'Master Data ');
        $this->template->load('template', "master/" . $list . "/list", $data);
    }


    function edit_user_2()
    {

        $id = $this->uri->segment(3);

        $data['list']         = $this->db->query("SELECT a.*,b.user_id FROM dyn_menu a LEFT JOIN (SELECT * FROM otori WHERE user_id = '$id') b ON a.id = b.menu_id order by a.id");

        if (($id == "") || ($this->master_model->get_by_id('[user]', 'id_user', $id)->num_rows() <= 0)) :

            redirect('master/ganti_skpd');

        endif;

        //*
        $config = array(
            array(
                'field'   => 'id_user',
                'label'   => 'ID',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'user_name',
                'label'   => 'User Uame',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'password',
                'label'   => 'Password',
                'rules'   => 'trim'
            ),
            array(
                'field'   => 'type',
                'label'   => 'Type',
                'rules'   => 'trim'
            ),
            array(
                'field'   => 'nama',
                'label'   => 'Nama',
                'rules'   => 'trim|required'
            )

        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data User &raquo; Ubah Data";
            $data['user'] = $this->master_model->get_by_id('[user]', 'id_user', $id)->row();
        } else {
            if ((md5($this->input->post('password')) == $this->input->post('password_before')) || ($this->input->post('password') == "")) {
                $data = array(
                    'user_name' => $this->input->post('user_name'),
                    'password' => $this->input->post('password_before'),
                    //'password' => md5($this->input->post('password')),
                    'kd_skpd' => $this->input->post('pcskpd'),
                    'nama' => $this->input->post('nama'),
                    'type' => $this->input->post('type')
                );
            } else {
                $data = array(
                    'user_name' => $this->input->post('user_name'),
                    //'password' => $this->input->post('password'),
                    'kd_skpd' => $this->input->post('pcskpd'),
                    'password' => md5($this->input->post('password')),
                    'nama' => $this->input->post('nama'),
                    'type' => $this->input->post('type')
                );
            }
            //id_user' => $this->input->post('id_user'),
            $this->master_model->delete("otori", "user_id", $this->input->post('id_user'));
            //*
            $max = count($this->input->post('otori_id')) - 1;
            for ($i = 0; $i <= $max; $i++) {
                $id_menu = $this->input->post('otori_id');

                $data_otori = array(
                    'user_id' => $this->input->post('id_user'),
                    'menu_id' => $id_menu[$i],
                    'akses' => "1"
                );
                $this->master_model->save('otori', $data_otori);
            }
            //*/

            $this->master_model->update('[user]', 'id_user', $id, $data);

            $this->session->set_flashdata('notify', 'Data User berhasil diupdate !');

            redirect('master/ganti_skpd');
        }

        $this->template->set('title', 'Master Data User &raquo; Ubah Data');
        $this->template->load('template', 'master/ganti_skpd/edit', $data);
        //*/
    }



    function logout_user()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('[user]', 'id_user', $id)->num_rows() <= 0)) :

            redirect('master/user');

        else :

            $sql = "Update [user] SET status='0' WHERE id_user = '$id'";
            $query1 = $this->db->query($sql);
            $this->session->set_flashdata('notify', 'User telah Log Out !');
            redirect('master/user_online');

        endif;
    }

    function logout_user_all()
    {
        $sql = "Update [user] SET status='0'";
        $query1 = $this->db->query($sql);
        $this->session->set_flashdata('notify', 'User telah Log Out !');
        redirect('master/user_online');
    }


    function cetak_fungsi()
    {
        $data['page_title'] = "Master Data Fungsi";
        $data['list']         = $this->master_model->getAllc('ms_fungsi', 'kd_fungsi');
        //$this->template->load('template','master/fungsi/list_preview', $data);
        $this->load->view('master/fungsi/list_preview', $data);
    }


    function get_sclient()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,thn_ang,provinsi,kab_kota,daerah,tgl_rka,tgl_dpa,tgl_ubah,tgl_dppa,rek_kasda,rek_kasin,rek_kasout,rk_skpd,rk_skpkd,spd_head1,spd_head2,spd_head3,spd_head4,
                ingat1,ingat2,ingat3,ingat4,ingat5 FROM sclient WHERE kd_skpd = '$kd_skpd'";
        $query1 = $this->db->query($sql);
        //$test = "hai";
        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'thn_ang' => $resulte['thn_ang'],
                'provinsi' => $resulte['provinsi'],
                'kab_kota' => $resulte['kab_kota'],
                'daerah' => $resulte['daerah'],
                'tgl_rka' => $resulte['tgl_rka'],
                'tgl_dpa' => $resulte['tgl_dpa'],
                'tgl_ubah' => $resulte['tgl_ubah'],
                'tgl_dppa' => $resulte['tgl_dppa'],
                'rek_kasda' => $resulte['rek_kasda'],
                'rek_kasin' => $resulte['rek_kasin'],
                'rek_kasout' => $resulte['rek_kasout'],
                'rk_skpd' => $resulte['rk_skpd'],
                'rk_skpkd' => $resulte['rk_skpkd'],
                'head1' => $resulte['spd_head1'],
                'head2' => $resulte['spd_head2'],
                'head3' => $resulte['spd_head3'],
                'head4' => $resulte['spd_head4'],
                'ingat1' => $resulte['ingat1'],
                'ingat2' => $resulte['ingat2'],
                'ingat3' => $resulte['ingat3'],
                'ingat4' => $resulte['ingat4'],
                'ingat5' => $resulte['ingat5']
            );
            $ii++;
        }

        if ($test === 0) {
            $result = array(
                'kd_skpd' => '',
                'thn_ang' => '',
                'provinsi' => '',
                'kab_kota' => '',
                'daerah' => '',
                'tgl_rka' => '',
                'tgl_dpa' => '',
                'tgl_ubah' => '',
                'tgl_dppa' => '',
                'rek_kasda' => '',
                'rek_kasin' => '',
                'rek_kasout' => '',
                'rk_skpd' => '',
                'rk_skpkd' => '',
                'spd_head1' => '',
                'spd_head2' => '',
                'spd_head3' => '',
                'spd_head4' => '',
                'ingat1' => '',
                'ingat2' => '',
                'ingat3' => '',
                'ingat4' => '',
                'ingat5' => ''
            );
            $ii++;
        }


        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_sclient()
    {
        $tabel      = $this->input->post('tabel');
        $cskpd      = $this->input->post('cskpd');
        $cthn       = $this->input->post('cthn');
        $cprov      = $this->input->post('cprov');
        $ckab       = $this->input->post('ckab');
        $cibu       = $this->input->post('cibu');
        $ctgl_rka   = $this->input->post('ctgl_rka');
        $ctgl_dpa   = $this->input->post('ctgl_dpa');
        $ctgl_ubah  = $this->input->post('ctgl_ubah');
        $ctgl_dppa  = $this->input->post('ctgl_dppa');
        $crek_kasda = $this->input->post('crek_kasda');
        $crek_kasin = $this->input->post('crek_kasin');
        $crek_kasout = $this->input->post('crek_kasout');
        $crk_skpd   = $this->input->post('crk_skpd');
        $crk_skpkd  = $this->input->post('crk_skpkd');
        $chead1     = $this->input->post('chead1');
        $chead2     = $this->input->post('chead2');
        $chead3     = $this->input->post('chead3');
        $chead4     = $this->input->post('chead4');
        $cingat1    = $this->input->post('cingat1');
        $cingat2    = $this->input->post('cingat2');
        $cingat3    = $this->input->post('cingat3');
        $cingat4    = $this->input->post('cingat4');
        $cingat5    = $this->input->post('cingat5');



        $sql = "delete from sclient WHERE kd_skpd='$cskpd'";
        $asg = $this->db->query($sql);

        if ($asg) {
            $sql = "insert into sclient(kd_skpd,thn_ang,provinsi,kab_kota,daerah,tgl_rka,tgl_dpa,tgl_ubah,tgl_dppa,rek_kasda,rek_kasin,rek_kasout,rk_skpd,rk_skpkd,
                    spd_head1,spd_head2,spd_head3,spd_head4,ingat1,ingat2,ingat3,ingat4,ingat5) 
            values('$cskpd','$cthn','$cprov','$ckab','$cibu','$ctgl_rka','$ctgl_dpa','$ctgl_ubah','$ctgl_dppa','$crek_kasda','$crek_kasin','$crek_kasout','$crk_skpd','$crk_skpkd',
            '$chead1','$chead2','$chead3','$chead4','$cingat1','$cingat2','$cingat3','$cingat4','$cingat5')";
            $asg = $this->db->query($sql);
        }
    }

    function get_tapd()
    {

        $sql = "SELECT no,nip,nama,jabatan FROM tapd";

        $query1 = $this->db->query($sql);


        //$test = "hai";
        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        if ($test === 0) {
            $result = array(
                'nip' => '',
                'nama' => '',
                'jabatan' => ''
            );
            $ii++;
        }


        echo json_encode($result);
        $query1->free_result();
    }

    // Tamba data
    function tambah_fungsi()
    {

        $config = array(
            array(
                'field'   => 'kd_fungsi',
                'label'   => 'Kd Fungsi',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_fungsi',
                'label'   => 'Nm Fungsi',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Fungsi &raquo; Tambah";
        } else {

            $data = array(
                'kd_fungsi' => $this->input->post('kd_fungsi'),
                'nm_fungsi' => $this->input->post('nm_fungsi'),
            );

            $this->master_model->save('ms_fungsi', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/fungsi');
        }

        $this->template->set('title', 'Master Data Fungsi &raquo; Tambah Data');
        $this->template->load('template', 'master/fungsi/tambah', $data);
    }

    function cari_fungsi()
    {

        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_fungsi', 'kd_fungsi', 'nm_fungsi', 'Fungsi', 'fungsi', $lccr);
    }

    // Ubah data
    function edit_fungsi()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_fungsi', 'kd_fungsi', $id)->num_rows() <= 0)) :

            redirect('master/fungsi');

        endif;

        $config = array(
            array(
                'field'   => 'kd_fungsi',
                'label'   => 'Kd Fungsi',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_fungsi',
                'label'   => 'Nm Fungsi',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Fungsi &raquo; Ubah Data";
            $data['fungsi'] = $this->master_model->get_by_id('ms_fungsi', 'kd_fungsi', $id)->row();
        } else {

            $data = array(
                'kd_fungsi' => $this->input->post('kd_fungsi'),
                'nm_fungsi' => $this->input->post('nm_fungsi'),
            );

            $this->master_model->update('ms_fungsi', 'kd_fungsi', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/fungsi');
        }

        $this->template->set('title', 'Master Data Fungsi &raquo; Ubah Data');
        $this->template->load('template', 'master/fungsi/edit', $data);
    }

    // hapus data
    function hapus_fungsi()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_fungsi', 'kd_fungsi', $id)->num_rows() <= 0)) :

            redirect('master/fungsi');

        else :

            $this->master_model->delete('ms_fungsi', 'kd_fungsi', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/fungsi');

        endif;
    }

    function preview_fungsi()
    {
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER FUNGSI</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE FUNGSI</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA FUNGSI</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td></tr>
                        ";

        //$query = $this->db->query('SELECT kd_fungsi,nm_fungsi FROM ms_fungsi');
        $query = $this->master_model->getAllc('ms_fungsi', 'kd_fungsi');

        foreach ($query->result() as $row) {
            $coba1 = $row->kd_fungsi;
            $coba2 = $row->nm_fungsi;
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba2</td></tr>";
        }

        $cRet    .= "</table>";
        $data['prev'] = $cRet;
        $this->_mpdf('', $cRet, 10, 10, 10, 0);
        //$this->template->load('template','master/fungsi/list_preview',$data);


    }

    function cari_urusan()
    {

        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_urusan', 'kd_urusan', 'nm_urusan', 'Urusan', 'urusan', $lccr);
    }

    // Tamba data
    function tambah_urusan()
    {

        $config = array(
            array(
                'field'   => 'kd_urusan',
                'label'   => 'Kd Urusan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_urusan',
                'label'   => 'Nm Urusan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_fungsi',
                'label'   => 'kd_fungsi',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data urusan &raquo; Tambah";
            $lc = "select kd_fungsi,nm_fungsi from ms_fungsi  order by kd_fungsi";
            $query = $this->db->query($lc);
            $data["kdfungsi"] = $query->result();
        } else {

            $data = array(
                'kd_urusan' => $this->input->post('kd_urusan'),
                'nm_urusan' => $this->input->post('nm_urusan'),
                'kd_fungsi' => $this->input->post('kd_fungsi')
            );

            $this->master_model->save('ms_urusan', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/urusan');
        }

        $this->template->set('title', 'Master Data Urusan &raquo; Tambah Data');
        $this->template->load('template', 'master/urusan/tambah', $data);
    }

    // Ubah data
    function edit_urusan()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_urusan', 'kd_urusan', $id)->num_rows() <= 0)) :

            redirect('master/urusan');

        endif;

        $config = array(
            array(
                'field'   => 'kd_urusan',
                'label'   => 'Kd Urusan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_urusan',
                'label'   => 'Nm Urusan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_fungsi',
                'label'   => 'kd_fungsi',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data urusan &raquo; Ubah Data";
            $data['urusan'] = $this->master_model->get_by_id('ms_urusan', 'kd_urusan', $id)->row();
            $lc = "select kd_fungsi,nm_fungsi from ms_fungsi  order by kd_fungsi";
            $query = $this->db->query($lc);
            $data["kdfungsi"] = $query->result();
        } else {

            $data = array(
                'kd_urusan' => $this->input->post('kd_urusan'),
                'nm_urusan' => $this->input->post('nm_urusan'),
                'kd_fungsi' => $this->input->post('kd_fungsi')
            );

            $this->master_model->update('ms_urusan', 'kd_urusan', $id, $data);

            $this->session->set_flashdata('notify', 'Data  berhasil diupdate !');

            redirect('master/urusan');
        }

        $this->template->set('title', 'Master Data urusan &raquo; Ubah Data');
        $this->template->load('template', 'master/urusan/edit', $data);
    }

    // hapus data
    function hapus_urusan()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_urusan', 'kd_urusan', $id)->num_rows() <= 0)) :

            redirect('master/urusan');

        else :

            $this->master_model->delete('ms_urusan', 'kd_urusan', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/urusan');

        endif;
    }

    function preview_urusan()
    {
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER URUSAN</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE URUSAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA URUSAN</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td></tr>
                        ";

        $query = $this->master_model->getAllc('ms_urusan', 'kd_urusan');

        foreach ($query->result() as $row) {
            $coba1 = $row->kd_urusan;
            $coba2 = $row->nm_urusan;
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba2</td></tr>";
        }

        $cRet    .= "</table>";
        $data['prev'] = $cRet;
        $this->_mpdf('', $cRet, 10, 10, 10, 0);
        //$this->template->load('template','master/urusan/list_preview',$data);


    }

    function cari_skpd()
    {

        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_skpd', 'kd_skpd', 'nm_skpd', 'SKPD', 'skpd', $lccr);
    }

    // Tamba data
    function tambah_skpd()
    {
        $this->load->model('master_model');
        $config = array(
            array(
                'field'   => 'kd_urusan',
                'label'   => 'Kode Urusan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_skpd',
                'label'   => 'Kode Skpd',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_skpd',
                'label'   => 'Nama Skpd',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'npwp',
                'label'   => 'NPWP',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening SKPD &raquo; Tambah";
            $lc = "select a.kd_urusan,a.nm_urusan,a.kd_fungsi,b.nm_fungsi from ms_urusan a inner join ms_fungsi b on a.kd_fungsi=b.kd_fungsi where a.tipe='S' order by a.kd_urusan";
            $query = $this->db->query($lc);
            $data["kdurus"] = $query->result();
        } else {

            $data = array(
                'kd_urusan' => $this->input->post('kd_urusan'),
                'kd_skpd' => $this->input->post('kd_skpd'),
                'nm_skpd' => $this->input->post('nm_skpd'),
                'npwp' => $this->input->post('npwp')
            );

            $this->master_model->save('ms_skpd', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/skpd');
        }

        $this->template->set('title', 'Master Data Rekening skpd &raquo; Tambah Data');
        $this->template->load('template', 'master/skpd/tambah', $data);
    }

    // Ubah data
    function edit_skpd()
    {
        $this->load->model('master_model');
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_skpd', 'kd_skpd', $id)->num_rows() <= 0)) :

            redirect('master/skpd');

        endif;

        $config = array(
            array(
                'field'   => 'kd_urusan',
                'label'   => 'Kode Urusan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_skpd',
                'label'   => 'Kode Skpd',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_skpd',
                'label'   => 'Nama Skpd',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'npwp',
                'label'   => 'NPWP',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data SKPD &raquo; Ubah Data";
            $data['skpd'] = $this->master_model->get_by_id('ms_skpd', 'kd_skpd', $id)->row();
            $lc = "select kd_urusan,nm_urusan from ms_urusan where tipe='S' order by kd_urusan";
            $query = $this->db->query($lc);
            $data["kdurus"] = $query->result();
        } else {

            $data = array(
                'kd_urusan' => $this->input->post('kd_urusan'),
                'kd_skpd' => $this->input->post('kd_skpd'),
                'nm_skpd' => $this->input->post('nm_skpd'),
                'npwp' => $this->input->post('npwp'),
            );

            $this->master_model->update('ms_skpd', 'kd_skpd', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/skpd');
        }

        $this->template->set('title', 'Master Data Rekening SKPD &raquo; Ubah Data');
        $this->template->load('template', 'master/skpd/edit', $data);
    }

    // hapus data
    function hapus_skpd()
    {
        $this->load->model('master_model');
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_skpd', 'kd_skpd', $id)->num_rows() <= 0)) :

            redirect('master/skpd');

        else :

            $this->master_model->delete('ms_skpd', 'kd_skpd', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/skpd');

        endif;
    }

    function preview_skpd()
    {
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"4\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER SKPD</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE SKPD</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE URUSAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA SKPD</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NPWP</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td></tr>
                        ";

        //$query = $this->db->query('SELECT kd_fungsi,nm_fungsi FROM ms_fungsi');
        $query = $this->master_model->getAllc('ms_skpd', 'kd_skpd');

        foreach ($query->result() as $row) {
            $coba1 = $row->kd_skpd;
            $coba2 = $row->kd_urusan;
            $coba3 = $row->nm_skpd;
            $coba4 = $row->npwp;
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba3</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba4</td></tr>";
        }

        $cRet    .= "</table>";
        $data['prev'] = $cRet;
        $this->_mpdf('', $cRet, 10, 10, 10, 0);
        //$this->template->load('template','master/fungsi/list_preview',$data);


    }

    function tambah_unit()
    {

        $config = array(
            array(
                'field'   => 'kd_unit',
                'label'   => 'kd_unit',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_unit',
                'label'   => 'nm_unit',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_skpd',
                'label'   => 'Kode Skpd',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Unit Kerja &raquo; Tambah";
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"] = $query->result();
        } else {

            $data = array(
                'kd_unit' => $this->input->post('kd_unit'),
                'nm_unit' => $this->input->post('nm_unit'),
                'kd_skpd' => $this->input->post('kd_skpd')

            );

            $this->master_model->save('ms_unit', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/unit');
        }

        $this->template->set('title', 'Master Unit Kerja &raquo; Tambah Data');
        $this->template->load('template', 'master/unit/tambah', $data);
    }

    // Ubah data
    function edit_unit()
    {
        $id = $this->uri->segment(3);
        $id = str_replace('%20', ' ', $id);
        //echo($id);

        if (($id == "") || ($this->master_model->get_by_id('ms_unit', 'kd_unit', $id)->num_rows() <= 0)) :
            echo ($id);
            redirect('master/unit');

        endif;

        $config = array(
            array(
                'field'   => 'kd_unit',
                'label'   => 'kd_unit',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_unit',
                'label'   => 'nm_unit',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_skpd',
                'label'   => 'Kode Skpd',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Unit Kerja &raquo; Ubah Data";
            $data['unit'] = $this->master_model->get_by_id('ms_unit', 'kd_unit', $id)->row();
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"] = $query->result();
        } else {

            $data = array(
                'kd_unit' => $this->input->post('kd_unit'),
                'nm_unit' => $this->input->post('nm_unit'),
                'kd_skpd' => $this->input->post('kd_skpd')
            );

            $this->master_model->update('ms_unit', 'kd_unit', $id, $data);

            $this->session->set_flashdata('notify', 'Data berhasil diupdate !');

            redirect('master/unit');
        }

        $this->template->set('title', 'Master Unit Kerja &raquo; Ubah Data');
        $this->template->load('template', 'master/unit/edit', $data);
    }

    // hapus data
    function hapus_unit()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_unit', 'kd_unit', $id)->num_rows() <= 0)) :

            redirect('master/unit');

        else :

            $this->master_model->delete('ms_unit', 'kd_unit', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/unit');

        endif;
    }

    function cari_program()
    {

        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'm_prog', 'kd_program', 'nm_program', 'Program', 'program', $lccr);
    }

    // Tamba data
    function tambah_program()
    {

        $config = array(
            array(
                'field'   => 'kd_program',
                'label'   => 'Kd program',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_program',
                'label'   => 'Nm program',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Program &raquo; Tambah";
        } else {

            $data = array(
                'kd_program' => $this->input->post('kd_program'),
                'nm_program' => $this->input->post('nm_program'),
            );

            $this->master_model->save('m_prog', $data);

            $this->session->set_flashdata('notify', 'Data  berhasil disimpan !');

            redirect('master/program');
        }

        $this->template->set('title', 'Master Data Program &raquo; Tambah Data');
        $this->template->load('template', 'master/program/tambah', $data);
    }

    // Ubah data
    function edit_program()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('m_prog', 'kd_program', $id)->num_rows() <= 0)) :

            redirect('program');

        endif;

        $config = array(
            array(
                'field'   => 'kd_program',
                'label'   => 'Kd program',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_program',
                'label'   => 'Nm program',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Program &raquo; Ubah Data";
            $data['program'] = $this->master_model->get_by_id('m_prog', 'kd_program', $id)->row();
        } else {

            $data = array(
                'kd_program' => $this->input->post('kd_program'),
                'nm_program' => $this->input->post('nm_program'),
            );

            $this->master_model->update('m_prog', 'kd_program', $id, $data);

            $this->session->set_flashdata('notify', 'Data  berhasil diupdate !');

            redirect('master/program');
        }

        $this->template->set('title', 'Master Data Program &raquo; Ubah Data');
        $this->template->load('template', 'master/program/edit', $data);
    }

    // hapus data
    function hapus_program()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('m_prog', 'kd_program', $id)->num_rows() <= 0)) :

            redirect('master/program');

        else :

            $this->master_model->delete('m_prog', 'kd_program', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/program');

        endif;
    }

    function preview_program()
    {
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"2\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER PROGRAM</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE PROGRAM</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA PROGRAM</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td></tr>
                        ";

        $query = $this->master_model->getAllc('m_prog', 'kd_program');

        foreach ($query->result() as $row) {
            $coba1 = $row->kd_program;
            $coba2 = $row->nm_program;
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba2</td></tr>";
        }

        $cRet    .= "</table>";
        $data['prev'] = $cRet;
        $this->_mpdf('', $cRet, 10, 10, 10, 0);
        //$this->template->load('template','master/fungsi/list_preview',$data);


    }

    function cari_kegiatan()
    {

        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'm_giat', 'kd_kegiatan', 'nm_kegiatan', 'Kegiatan', 'kegiatan', $lccr);
    }

    // Tamba data
    function tambah_kegiatan()
    {

        $config = array(
            array(
                'field'   => 'kd_program',
                'label'   => 'Kd Program',
                'rules'   => 'trim|required'
            ),

            array(
                'field'   => 'kd_kegiatan',
                'label'   => 'Kd Kegiatan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_kegiatan',
                'label'   => 'Nm Kegiatan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'jns_kegiatan',
                'label'   => 'Jns Kegiatan',
                'rules'   => 'trim|required'
            )

        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Kegiatan &raquo; Tambah";
            $lc = "select kd_program,nm_program from m_prog order by kd_program";
            $query = $this->db->query($lc);
            $data["program"] = $query->result();
            //$data["jumrow"]=$this->db->get('m_prog')->num_rows();
        } else {

            $data = array(
                'kd_program' => $this->input->post('kd_program'),
                'kd_kegiatan' => $this->input->post('kd_kegiatan'),
                'nm_kegiatan' => $this->input->post('nm_kegiatan'),
                'jns_kegiatan' => $this->input->post('jns_kegiatan'),
            );

            $this->master_model->save('m_giat', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/kegiatan');
        }

        $this->template->set('title', 'Master Data Kegiatan &raquo; Tambah Data');
        $this->template->load('template', 'master/kegiatan/tambah', $data);
    }

    // Ubah data
    function edit_kegiatan()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('m_giat', 'kd_kegiatan', $id)->num_rows() <= 0)) :

            redirect('master/kegiatan');

        endif;

        $config = array(
            array(
                'field'   => 'kd_program',
                'label'   => 'Kd Program',
                'rules'   => 'trim|required'
            ),

            array(
                'field'   => 'kd_kegiatan',
                'label'   => 'Kd Kegiatan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_kegiatan',
                'label'   => 'Nm Kegiatan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'jns_kegiatan',
                'label'   => 'Jns Kegiatan',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Kegiatan &raquo; Ubah Data";
            $data['kegiatan'] = $this->master_model->get_by_id('m_giat', 'kd_kegiatan', $id)->row();
            $lc = "select kd_program,nm_program from m_prog order by kd_program";
            $query = $this->db->query($lc);
            $data["program"] = $query->result();
            $data["jumrow"] = $this->db->get('m_prog')->num_rows();
        } else {

            $data = array(
                'kd_program' => $this->input->post('kd_program'),
                'kd_kegiatan' => $this->input->post('kd_kegiatan'),
                'nm_kegiatan' => $this->input->post('nm_kegiatan'),
                'jns_kegiatan' => $this->input->post('jns_kegiatan'),
            );

            $this->master_model->update('m_giat', 'kd_kegiatan', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/kegiatan');
        }

        $this->template->set('title', 'Master Data Kegiatan &raquo; Ubah Data');
        $this->template->load('template', 'master/kegiatan/edit', $data);
    }

    // hapus data
    function hapus_kegiatan()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('m_giat', 'kd_kegiatan', $id)->num_rows() <= 0)) :

            redirect('master/kegiatan');

        else :

            $this->master_model->delete('m_giat', 'kd_kegiatan', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/kegiatan');

        endif;
    }

    function preview_kegiatan()
    {
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"4\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER KEGIATAN</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE KEGIATAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE PROGRAM</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA KEGIATAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>JENIS</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td></tr>
                        ";

        //$query = $this->db->query('SELECT kd_fungsi,nm_fungsi FROM ms_fungsi');
        $query = $this->master_model->getAllc('m_giat', 'kd_kegiatan');

        foreach ($query->result() as $row) {
            $coba1 = $row->kd_kegiatan;
            $coba2 = $row->kd_program;
            $coba3 = $row->nm_kegiatan;
            $coba4 = $row->jns_kegiatan;
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba3</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba4</td></tr>";
        }

        $cRet    .= "</table>";
        $data['prev'] = $cRet;
        $this->_mpdf('', $cRet, 10, 10, 10, 0);
        //$this->template->load('template','master/fungsi/list_preview',$data);


    }

    function tambah_rek1()
    {

        $config = array(
            array(
                'field'   => 'kd_rek1',
                'label'   => 'Kode Rekening 1',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_rek1',
                'label'   => 'Nama Rekening 1',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening 1 &raquo; Tambah";
        } else {

            $data = array(
                'kd_rek1' => $this->input->post('kd_rek1'),
                'nm_rek1' => $this->input->post('nm_rek1'),
            );

            $this->master_model->save('ms_rek1', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/rek1');
        }

        $this->template->set('title', 'Master Data rekening 1 &raquo; Tambah Data');
        $this->template->load('template', 'master/rek1/tambah', $data);
    }

    // Ubah data
    function edit_rek1()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek1', 'kd_rek1', $id)->num_rows() <= 0)) :

            redirect('master/rek1');

        endif;

        $config = array(
            array(
                'field'   => 'kd_rek1',
                'label'   => 'Kode Rekening 1',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_rek1',
                'label'   => 'Nama Rekening 1',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening akun &raquo; Ubah Data";
            $data['rek1'] = $this->master_model->get_by_id('ms_rek1', 'kd_rek1', $id)->row();
        } else {

            $data = array(
                'kd_rek1' => $this->input->post('kd_rek1'),
                'nm_rek1' => $this->input->post('nm_rek1'),
            );

            $this->master_model->update('ms_rek1', 'kd_rek1', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/rek1');
        }

        $this->template->set('title', 'Master Data Rekening Akun  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek1/edit', $data);
    }

    // hapus data
    function hapus_rek1()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek1', 'kd_rek1', $id)->num_rows() <= 0)) :

            redirect('master/rek1');

        else :

            $this->master_model->delete('ms_rek1', 'kd_rek1', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/rek1');

        endif;
    }

    function tambah_rek2()
    {

        $config = array(
            array(
                'field'   => 'kd_rek2',
                'label'   => 'Kode Rekening 2',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek1',
                'label'   => 'Kode Rekening 1',
                'rules'   => 'trim|required'
            ),

            array(
                'field'   => 'nm_rek2',
                'label'   => 'Nama Rekening 2',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Tambah";
            $lc = "select kd_rek1,nm_rek1 from ms_rek1 order by kd_rek1";
            $query = $this->db->query($lc);
            $data["kdrek"] = $query->result();
        } else {

            $data = array(
                'kd_rek2' => $this->input->post('kd_rek2'),
                'kd_rek1' => $this->input->post('kd_rek1'),
                'nm_rek2' => $this->input->post('nm_rek2'),
            );

            $this->master_model->save('ms_rek2', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/rek2');
        }

        $this->template->set('title', 'Master Data Rekening Kelompok &raquo; Tambah Data');
        $this->template->load('template', 'master/rek2/tambah', $data);
    }

    // Ubah data
    function edit_rek2()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek2', 'kd_rek2', $id)->num_rows() <= 0)) :

            redirect('master/rek2');

        endif;

        $config = array(
            array(
                'field'   => 'kd_rek2',
                'label'   => 'Kode Rekening kelompok',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek1',
                'label'   => 'Kode Rekening Akun',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_rek2',
                'label'   => 'Nama Rekening Kelompok',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Ubah Data";
            $data['rek2'] = $this->master_model->get_by_id('ms_rek2', 'kd_rek2', $id)->row();
            $lc = "select kd_rek1,nm_rek1 from ms_rek1 order by kd_rek1";
            $query = $this->db->query($lc);
            $data["kdrek"] = $query->result();
        } else {

            $data = array(
                'kd_rek2' => $this->input->post('kd_rek2'),
                'kd_rek1' => $this->input->post('kd_rek1'),
                'nm_rek2' => $this->input->post('nm_rek2'),
            );

            $this->master_model->update('ms_rek2', 'kd_rek2', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/rek2');
        }

        $this->template->set('title', 'Master Data Rekening Kelompok  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek2/edit', $data);
    }

    // hapus data
    function hapus_rek2()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek2', 'kd_rek2', $id)->num_rows() <= 0)) :

            redirect('master/rek2');

        else :

            $this->master_model->delete('ms_rek2', 'kd_rek2', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/rek2');

        endif;
    }

    function cari_rek3()
    {
        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_rek3', 'kd_rek3', 'nm_rek3', 'Rekening Jenis', 'rek3', $lccr);
    }

    function tambah_rek3()
    {

        $config = array(
            array(
                'field'   => 'kd_rek2',
                'label'   => 'Kode Rekening kelompok',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek3',
                'label'   => 'Kode Rekening Jenis',
                'rules'   => 'trim|required'
            ),

            array(
                'field'   => 'nm_rek3',
                'label'   => 'Nama Rekening Jenis',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Tambah";
            $lc = "select kd_rek2,nm_rek2 from ms_rek2 order by kd_rek2";
            $query = $this->db->query($lc);
            $data["kdrek2"] = $query->result();
        } else {

            $data = array(
                'kd_rek2' => $this->input->post('kd_rek2'),
                'kd_rek3' => $this->input->post('kd_rek3'),
                'nm_rek3' => $this->input->post('nm_rek3'),
            );

            $this->master_model->save('ms_rek3', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/rek3');
        }

        $this->template->set('title', 'Master Data Rekening Jenis &raquo; Tambah Data');
        $this->template->load('template', 'master/rek3/tambah', $data);
    }

    // Ubah data
    function edit_rek3()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek3', 'kd_rek3', $id)->num_rows() <= 0)) :

            redirect('master/rek3');

        endif;

        $config = array(
            array(
                'field'   => 'kd_rek2',
                'label'   => 'Kode Rekening kelompok',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek3',
                'label'   => 'Kode Rekening jenis',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_rek3',
                'label'   => 'Nama Rekening Jenis',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Jenis &raquo; Ubah Data";
            $data['rek3'] = $this->master_model->get_by_id('ms_rek3', 'kd_rek3', $id)->row();
            $lc = "select kd_rek2,nm_rek2 from ms_rek2 order by kd_rek2";
            $query = $this->db->query($lc);
            $data["kdrek2"] = $query->result();
        } else {

            $data = array(
                'kd_rek2' => $this->input->post('kd_rek2'),
                'kd_rek3' => $this->input->post('kd_rek3'),
                'nm_rek3' => $this->input->post('nm_rek3'),
            );

            $this->master_model->update('ms_rek3', 'kd_rek3', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/rek3');
        }

        $this->template->set('title', 'Master Data Rekening Jenis  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek3/edit', $data);
    }

    // hapus data
    function hapus_rek3()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek3', 'kd_rek3', $id)->num_rows() <= 0)) :

            redirect('master/rek3');

        else :

            $this->master_model->delete('ms_rek3', 'kd_rek3', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/rek3');

        endif;
    }

    function cari_rek4()
    {
        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_rek4', 'kd_rek4', 'nm_rek4', 'Rekening Objek', 'rek4', $lccr);
    }

    function tambah_rek4()
    {

        $config = array(
            array(
                'field'   => 'kd_rek3',
                'label'   => 'Kode Rekening kelompok',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek4',
                'label'   => 'Kode Rekening Objek',
                'rules'   => 'trim|required'
            ),

            array(
                'field'   => 'nm_rek4',
                'label'   => 'Nama Rekening objek',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Kelompok &raquo; Tambah";
            $lc = "select kd_rek3,nm_rek3 from ms_rek3 order by kd_rek3";
            $query = $this->db->query($lc);
            $data["kdrek"] = $query->result();
        } else {

            $data = array(
                'kd_rek3' => $this->input->post('kd_rek3'),
                'kd_rek4' => $this->input->post('kd_rek4'),
                'nm_rek4' => $this->input->post('nm_rek4'),
            );

            $this->master_model->save('ms_rek4', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/rek4');
        }

        $this->template->set('title', 'Master Data Rekening Objek &raquo; Tambah Data');
        $this->template->load('template', 'master/rek4/tambah', $data);
    }

    // Ubah data
    function edit_rek4()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek4', 'kd_rek4', $id)->num_rows() <= 0)) :

            redirect('master/rek4');

        endif;

        $config = array(
            array(
                'field'   => 'kd_rek3',
                'label'   => 'Kode Rekening kelompok',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek4',
                'label'   => 'Kode Rekening Objek',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_rek4',
                'label'   => 'Nama Rekening Objek',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Objek &raquo; Ubah Data";
            $data['rek4'] = $this->master_model->get_by_id('ms_rek4', 'kd_rek4', $id)->row();
            $lc = "select kd_rek3,nm_rek3 from ms_rek3 order by kd_rek3";
            $query = $this->db->query($lc);
            $data["kdrek"] = $query->result();
        } else {

            $data = array(
                'kd_rek3' => $this->input->post('kd_rek3'),
                'kd_rek4' => $this->input->post('kd_rek4'),
                'nm_rek4' => $this->input->post('nm_rek4'),
            );

            $this->master_model->update('ms_rek4', 'kd_rek4', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/rek4');
        }

        $this->template->set('title', 'Master Data Rekening Objek  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek4/edit', $data);
    }

    // hapus data
    function hapus_rek4()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek4', 'kd_rek4', $id)->num_rows() <= 0)) :

            redirect('master/rek4');

        else :

            $this->master_model->delete('ms_rek4', 'kd_rek4', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/rek4');

        endif;
    }

    function cari_rek5()
    {
        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_rek5', 'kd_rek5', 'nm_rek5', 'Rekening Rincian Objek', 'rek5', $lccr);
    }

    function tambah_rek5()
    {

        $config = array(
            array(
                'field'   => 'kd_rek4',
                'label'   => 'Kode Rekening Objek',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek5',
                'label'   => 'Kode Rekening Rincian Objek',
                'rules'   => 'trim|required'
            ),

            array(
                'field'   => 'nm_rek5',
                'label'   => 'Nama Rekening Rincian Objek',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Rincian &raquo; Tambah";
            $lc = "select kd_rek4,nm_rek4 from ms_rek4 order by kd_rek4";
            $query = $this->db->query($lc);
            $data["kdrek"] = $query->result();
        } else {

            $data = array(
                'kd_rek4' => $this->input->post('kd_rek4'),
                'kd_rek5' => $this->input->post('kd_rek5'),
                'nm_rek5' => $this->input->post('nm_rek5'),
            );

            $this->master_model->save('ms_rek5', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/rek5');
        }

        $this->template->set('title', 'Master Data Rekening Rincian Objek &raquo; Tambah Data');
        $this->template->load('template', 'master/rek5/tambah', $data);
    }

    // Ubah data
    function edit_rek5()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek5', 'kd_rek5', $id)->num_rows() <= 0)) :

            redirect('master/rek5');

        endif;

        $config = array(
            array(
                'field'   => 'kd_rek4',
                'label'   => 'Kode Rekening Objek',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_rek5',
                'label'   => 'Kode Rekening Rincian Objek',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nm_rek5',
                'label'   => 'Nama Rekening Rincian Objek',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Rekening Rincian Objek &raquo; Ubah Data";
            $data['rek5'] = $this->master_model->get_by_id('ms_rek5', 'kd_rek5', $id)->row();
            $lc = "select kd_rek4,nm_rek4 from ms_rek4 order by kd_rek4";
            $query = $this->db->query($lc);
            $data["kdrek"] = $query->result();
        } else {

            $data = array(
                'kd_rek4' => $this->input->post('kd_rek4'),
                'kd_rek5' => $this->input->post('kd_rek5'),
                'nm_rek5' => $this->input->post('nm_rek5'),
            );

            $this->master_model->update('ms_rek5', 'kd_rek5', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/rek5');
        }

        $this->template->set('title', 'Master Data Rekening Rincian Objek  &raquo; Ubah Data');
        $this->template->load('template', 'master/rek5/edit', $data);
    }

    // hapus data
    function hapus_rek5()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_rek5', 'kd_rek5', $id)->num_rows() <= 0)) :

            redirect('master/rek5');

        else :

            $this->master_model->delete('ms_rek5', 'kd_rek5', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/rek5');

        endif;
    }

    function cari_ttd()
    {
        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_ttd', 'nip', 'nama', 'Penandatangan', 'ttd', $lccr);
    }

    function tambah_ttd()
    {

        $config = array(
            array(
                'field'   => 'nip',
                'label'   => 'NIP',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nama',
                'label'   => 'Nama',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'jabatan',
                'label'   => 'Jabatan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'pangkat',
                'label'   => 'pangkat',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_skpd',
                'label'   => 'Kode Skpd',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kode',
                'label'   => 'Kode',
                'rules'   => 'trim|required'
            )



        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Tanda tangan &raquo; Tambah";
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"] = $query->result();
        } else {

            $data = array(
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
                'pangkat' => $this->input->post('pangkat'),
                'kd_skpd' => $this->input->post('kd_skpd'),
                'kode' => $this->input->post('kode')


            );

            $this->master_model->save('ms_ttd', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/ttd');
        }

        $this->template->set('title', 'Master Data Tanda Tangan &raquo; Tambah Data');
        $this->template->load('template', 'master/ttd/tambah', $data);
    }

    // Ubah data
    function edit_ttd()
    {
        $id = $this->uri->segment(3);
        $id = str_replace('%20', ' ', $id);
        //echo($id);

        if (($id == "") || ($this->master_model->get_by_id('ms_ttd', 'nip', $id)->num_rows() <= 0)) :
            echo ($id);
            redirect('master/ttd');

        endif;

        $config = array(
            array(
                'field'   => 'nip',
                'label'   => 'NIP',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nama',
                'label'   => 'Nama',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'jabatan',
                'label'   => 'Jabatan',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'pangkat',
                'label'   => 'pangkat',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kd_skpd',
                'label'   => 'Kode Skpd',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'kode',
                'label'   => 'Kode',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Tanda Tangan &raquo; Ubah Data";
            $data['ttd'] = $this->master_model->get_by_id('ms_ttd', 'nip', $id)->row();
            $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
            $query = $this->db->query($lc);
            $data["skpd"] = $query->result();
        } else {

            $data = array(
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
                'pangkat' => $this->input->post('pangkat'),
                'kd_skpd' => $this->input->post('kd_skpd'),
                'kode' => $this->input->post('kode')
            );

            $this->master_model->update('ms_ttd', 'nip', $id, $data);

            $this->session->set_flashdata('notify', 'Data berhasil diupdate !');

            redirect('master/ttd');
        }

        $this->template->set('title', 'Master Data Tanda tangan &raquo; Ubah Data');
        $this->template->load('template', 'master/ttd/edit', $data);
    }

    // hapus data
    function hapus_ttd()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_ttd', 'nip', $id)->num_rows() <= 0)) :

            redirect('master/ttd');

        else :

            $this->master_model->delete('ms_ttd', 'nip', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/ttd');

        endif;
    }

    function cari_bank()
    {
        $lccr =  $this->input->post('pencarian');
        $this->index('0', 'ms_bank', 'kode', 'nama', 'Bank', 'bank', $lccr);
    }

    function tambah_bank()
    {

        $config = array(
            array(
                'field'   => 'kode',
                'label'   => 'Kode',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nama',
                'label'   => 'Nama',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Bank &raquo; Tambah";
        } else {

            $data = array(
                'kode' => $this->input->post('kode'),
                'nama' => $this->input->post('nama'),
            );

            $this->master_model->save('ms_bank', $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');

            redirect('master/bank');
        }

        $this->template->set('title', 'Master Data Bank &raquo; Tambah Data');
        $this->template->load('template', 'master/bank/tambah', $data);
    }

    // Ubah data
    function edit_bank()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_bank', 'kode', $id)->num_rows() <= 0)) :

            redirect('master/bank');

        endif;

        $config = array(
            array(
                'field'   => 'kode',
                'label'   => 'Kode',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nama',
                'label'   => 'Nama',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data Bank &raquo; Ubah Data";
            $data['bank'] = $this->master_model->get_by_id('ms_bank', 'kode', $id)->row();
        } else {

            $data = array(
                'kode' => $this->input->post('kode'),
                'nama' => $this->input->post('nama'),
            );

            $this->master_model->update('ms_bank', 'kode', $id, $data);

            $this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');

            redirect('master/bank');
        }

        $this->template->set('title', 'Master Data Bank &raquo; Ubah Data');
        $this->template->load('template', 'master/bank/edit', $data);
    }

    // hapus data
    function hapus_bank()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('ms_bank', 'kode', $id)->num_rows() <= 0)) :

            redirect('master/bank');

        else :

            $this->master_model->delete('ms_bank', 'kode', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/bank');

        endif;
    }


    ////my
    ////my
    function cari_user()
    {
        $lccr =  $this->input->post('pencarian');
        $this->index('0', '[user]', 'user_name', 'nama', 'USER', 'user', $lccr);
    }


    function edit_user()
    {

        $id = $this->uri->segment(3);

        $data['list']         = $this->db->query("SELECT a.*,b.user_id FROM dyn_menu a LEFT JOIN (SELECT * FROM otori WHERE user_id = '$id') b ON a.id = b.menu_id order by a.id");

        if (($id == "") || ($this->master_model->get_by_id('[user]', 'id_user', $id)->num_rows() <= 0)) :

            redirect('master/user');

        endif;

        //*
        $config = array(
            array(
                'field'   => 'id_user',
                'label'   => 'ID',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'user_name',
                'label'   => 'User Uame',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'password',
                'label'   => 'Password',
                'rules'   => 'trim'
            ),
            array(
                'field'   => 'type',
                'label'   => 'Type',
                'rules'   => 'trim'
            ),
            array(
                'field'   => 'nama',
                'label'   => 'Nama',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'bidang',
                'label'   => 'Bidang',
                'rules'   => 'trim|required'
            )

        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data User &raquo; Ubah Data";
            $data['user'] = $this->master_model->get_by_id('[user]', 'id_user', $id)->row();
        } else {
            if ((md5($this->input->post('password')) == $this->input->post('password_before')) || ($this->input->post('password') == "")) {
                $data = array(
                    'user_name' => $this->input->post('user_name'),
                    'password' => $this->input->post('password_before'),
                    //'password' => md5($this->input->post('password')),
                    'kd_skpd' => $this->input->post('pcskpd'),
                    'nama' => $this->input->post('nama'),
                    'type' => $this->input->post('type'),
                    'bidang' => $this->input->post('bidang')
                );
            } else {
                $data = array(
                    'user_name' => $this->input->post('user_name'),
                    //'password' => $this->input->post('password'),
                    'kd_skpd' => $this->input->post('pcskpd'),
                    'password' => md5($this->input->post('password')),
                    'nama' => $this->input->post('nama'),
                    'type' => $this->input->post('type'),
                    'bidang' => $this->input->post('bidang')
                );
            }
            //id_user' => $this->input->post('id_user'),
            $this->master_model->delete("otori", "user_id", $this->input->post('id_user'));
            //*
            $max = count($this->input->post('otori_id')) - 1;
            for ($i = 0; $i <= $max; $i++) {
                $id_menu = $this->input->post('otori_id');

                $data_otori = array(
                    'user_id' => $this->input->post('id_user'),
                    'menu_id' => $id_menu[$i],
                    'akses' => "1"
                );
                $this->master_model->save('otori', $data_otori);
            }
            //*/

            $this->master_model->update('[user]', 'id_user', $id, $data);

            $this->session->set_flashdata('notify', 'Data User berhasil diupdate !');

            redirect('master/user');
        }

        $this->template->set('title', 'Master Data User &raquo; Ubah Data');
        $this->template->load('template', 'master/user/edit', $data);
        //*/
    }


    function tambah_user()
    {
        $data['list']         = $this->db->query("SELECT * FROM dyn_menu ORDER BY page_id");

        $config = array(
            array(
                'field'   => 'user_name',
                'label'   => 'User_name',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'password',
                'label'   => 'Password',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'type',
                'label'   => 'Type',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'nama',
                'label'   => 'Nama',
                'rules'   => 'trim|required'
            ),
            array(
                'field'   => 'bidang',
                'label'   => 'Bidang',
                'rules'   => 'trim|required'
            )
        );

        $this->form_validation->set_message('required', '%s harus diisi !');
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Master Data User &raquo; Tambah";
        } else {

            $data = array(
                'user_name' => $this->input->post('user_name'),
                'password' => md5($this->input->post('password')),
                'kd_skpd' => $this->input->post('pcskpd'),
                'type' => $this->input->post('type'),
                'nama' => $this->input->post('nama'),
                'jenis' => "2",
                'bidang' => $this->input->post('bidang')
            );
            //						
            $this->master_model->save('[user]', $data);

            //*
            $max = count($this->input->post('otori_id')) - 1;
            for ($i = 0; $i <= $max; $i++) {
                $id_menu = $this->input->post('otori_id');

                $data_otori = array(
                    'user_id' => $this->input->post('id_user'),
                    'menu_id' => $id_menu[$i],
                    'akses' => "1"
                );
                $this->master_model->save('otori', $data_otori);
            }
            //*/

            $this->session->set_flashdata('notify', 'Data User berhasil disimpan !');

            redirect('master/user');
        }

        $this->template->set('title', 'Master Data User &raquo; Tambah Data');
        $this->template->load('template', 'master/user/tambah', $data);
    }



    function hapus_user()
    {
        $id = $this->uri->segment(3);

        if (($id == "") || ($this->master_model->get_by_id('[user]', 'id_user', $id)->num_rows() <= 0)) :

            redirect('master/user');

        else :

            $this->master_model->delete("otori", "user_id", $id);

            $this->master_model->delete('[user]', 'id_user', $id);

            $this->session->set_flashdata('notify', 'Data berhasil dihapus !');

            redirect('master/user');

        endif;
    }

    function error()
    {
        $data['page_title'] = 'Dalam Penyesuaian';
        $this->template->set('title', 'Dalam Penyesuaian');
        $this->template->load('template', 'master/error', $data);
    }

    function mfungsi()
    {
        $data['page_title'] = 'Master FUNGSI';
        $this->template->set('title', 'Master Fungsi');
        $this->template->load('template', 'master/fungsi/mfungsi', $data);
    }

    function mhukum()
    {
        $data['page_title'] = 'Master Dasar Hukum';
        $this->template->set('title', 'Master Dasar Hukum');
        $this->template->load('template', 'master/fungsi/mhukum', $data);
    }

    function murusan()
    {
        $data['page_title'] = 'Master URUSAN';
        $this->template->set('title', 'Master Urusan');
        $this->template->load('template', 'master/urusan/murusan', $data);
    }

    function mskpd()
    {
        $data['page_title'] = 'Master SKPD';
        $this->template->set('title', 'Master SKPD');
        $this->template->load('template', 'master/skpd/mskpd', $data);
    }

    function standar_harga()
    {
        $data['page_title'] = 'Master Daftar Harga';
        $this->template->set('title', 'Master Daftar Harga');
        $this->template->load('template', 'master/harga/standar_harga', $data);
    }

    function munit()
    {
        $data['page_title'] = 'Master UNIT';
        $this->template->set('title', 'Master UNIT');
        $this->template->load('template', 'master/unit/munit', $data);
    }

    function mprogram()
    {
        $data['page_title'] = 'Master PROGRAM';
        $this->template->set('title', 'Master PROGRAM');
        $this->template->load('template', 'master/program/mprogram', $data);
    }

    function mkegiatan()
    {
        $data['page_title'] = 'Master KEGIATAN';
        $this->template->set('title', 'Master KEGIATAN');
        $this->template->load('template', 'master/kegiatan/mkegiatan', $data);
    }

    function mrek1()
    {
        $data['page_title'] = 'Master Rekening Akun';
        $this->template->set('title', 'Master Rekening Akun');
        $this->template->load('template', 'master/rek1/mrek1', $data);
    }

    function mrek2()
    {
        $data['page_title'] = 'Master Rekening Kelompok';
        $this->template->set('title', 'Master Rekening Kelompok');
        $this->template->load('template', 'master/rek2/mrek2', $data);
    }

    function mrek3()
    {
        $data['page_title'] = 'Master Rekening Jenis';
        $this->template->set('title', 'Master Rekening Jenis');
        $this->template->load('template', 'master/rek3/mrek3', $data);
    }

    function mrek4()
    {
        $data['page_title'] = 'Master Rekening Objek';
        $this->template->set('title', 'Master Rekening Objek');
        $this->template->load('template', 'master/rek4/mrek4', $data);
    }

    function mrek5()
    {
        $data['page_title'] = 'Master Rekening Rincian Objek';
        $this->template->set('title', 'Master Rekening Rincian Objek');
        $this->template->load('template', 'master/rek5/mrek5', $data);
        //echo CI_VERSION; 
    }

    function config_skpd()
    {
        $skpd       = $this->session->userdata('kdskpd');
        $sql        = "SELECT kd_skpd,nm_skpd FROM  ms_skpd a WHERE kd_skpd = '$skpd'";
        $query1     = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function mbank()
    {
        $data['page_title'] = 'Master BANK';
        $this->template->set('title', 'Master Bank');
        $this->template->load('template', 'master/bank/mbank', $data);
        //echo CI_VERSION; 
    }

    function mttd()
    {
        $data['page_title'] = 'Master Penandatangan';
        $this->template->set('title', 'Master Penandatangan');
        $this->template->load('template', 'master/ttd/mttd', $data);
        //echo CI_VERSION; 
    }

    //mttd_angg	
    function mttd_angg()
    {
        $data['page_title'] = 'Master Penandatangan Anggaran';
        $this->template->set('title', 'Master Penandatangan Anggaran');
        $this->template->load('template', 'master/ttd/mttd_angg', $data);
        //echo CI_VERSION; 
    }

    function load_ttd_angg()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $cbidang = '1';
        $kriteria = $this->input->post('cari');
        $where = " kd_skpd='$kd_skpd' and ";
        if ($kriteria <> '') {
            $where = " kd_skpd='$kd_skpd' and (upper(nama) like upper('%$kriteria%') or nip like'%$kriteria%') and ";
        }

        $sql = "SELECT count(*) as tot from ms_ttd where $where bidang='$cbidang' ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from ms_ttd where $where bidang='$cbidang' order by nip "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan'],
                'pangkat' => $resulte['pangkat'],
                'kd_skpd' => $resulte['kd_skpd'],
                'kode' => $resulte['kode']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }
    //end mttd_angg

    function rekening_objek()
    {
        $data['page_title'] = 'Master Rekening Objek';
        $this->template->set('title', 'Master Rekening');
        $this->template->load('template', 'master/rek5/rincian_objek', $data);
    }

    function load_rek1()
    {

        $sql = " SELECT kd_rek2,nm_rek2 FROM ms_rek2 ORDER BY kd_rek2 ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek2' => $resulte['kd_rek2'],
                'nm_rek2' => $resulte['nm_rek2']

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek5($reke = '')
    {
        $rek = $reke;
        $sql = " SELECT kd_rek4,kd_rek5,map_lra1,map_lo,nm_rek5 FROM ms_rek5  where substr(kd_rek5,1,2)='$rek' ORDER BY kd_rek5 ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek4' => $resulte['kd_rek4'],
                'kd_rek5' => $resulte['kd_rek5'],
                'map_lra1' => $resulte['map_lra1'],
                'map_lo' => $resulte['map_lo'],
                'nm_rek5' => $resulte['nm_rek5']

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_rek5()
    {

        $rek4 = $this->input->post('rek4');
        $rek5 = $this->input->post('rek5');
        $rek_lra = $this->input->post('rek_lra');
        $rek_lo = $this->input->post('rek_lo');
        $nama = $this->input->post('nama');

        $query = $this->db->query(" delete from ms_rek5 where kd_rek4='$rek4' and kd_rek5='$rek5'");
        $query = $this->db->query(" insert into ms_rek5(kd_rek4,kd_rek5,map_lra1,map_lo,nm_rek5) values('$rek4','$rek5','$rek_lra','$rek_lo','$nama') ");


        $this->select_rka($kegiatan);
    }

    function load_fungsi()
    {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_fungsi) like upper('%$kriteria%') or kd_fungsi like'%$kriteria%')";
        }

        $sql = "SELECT * from ms_fungsi $where order by kd_fungsi";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_fungsi' => $resulte['kd_fungsi'],
                'nm_fungsi' => $resulte['nm_fungsi']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_fungsi()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_fungsi, nm_fungsi FROM ms_fungsi where upper(kd_fungsi) like upper('%$lccr%') or upper(nm_fungsi) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_fungsi' => $resulte['kd_fungsi'],
                'nm_fungsi' => $resulte['nm_fungsi']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_urusan()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_urusan) like upper('%$kriteria%') or kd_urusan like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from ms_urusan $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from ms_urusan $where order by kd_urusan "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_urusan' => $resulte['kd_urusan'],
                'kd_fungsi' => $resulte['kd_fungsi'],
                'nm_urusan' => $resulte['nm_urusan']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function ambil_urusan()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_urusan, nm_urusan FROM ms_urusan where upper(kd_urusan) like upper('%$lccr%') or upper(nm_urusan) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_urusan' => $resulte['kd_urusan'],
                'nm_urusan' => $resulte['nm_urusan']
            );
            $ii++;
        }

        echo json_encode($result);
    }


    function load_skpd()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(nm_skpd) like upper('%$kriteria%') or kd_skpd like'%$kriteria%')";
        }
        $sql = "SELECT count(*) as tot from ms_skpd WHERE kd_skpd='$kd_skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $sql = "SELECT * from ms_skpd WHERE kd_skpd='$kd_skpd' $where order by kd_skpd "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'kd_urusan' => $resulte['kd_urusan'],
                'nm_skpd' => $resulte['nm_skpd'],
                'npwp' => $resulte['npwp'],
                'alamat' => $resulte['alamat'],
                'kodepos' => $resulte['kodepos'],
                'bank' => $resulte['bank'],
                'email' => $resulte['email'],
                'rekening' => $resulte['rekening'],
                'rekening_pend' => $resulte['rekening_pend'],
                'nilai_kua' => number_format($resulte['nilai_kua'], "2", ".", ","),
                'obskpd' => $resulte['obskpd']
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }


    function ambil_skpd()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_skpd, nm_skpd FROM ms_skpd where kd_skpd = '$kd_skpd' AND (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_bank()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM ms_bank where upper(kode) like upper('%$lccr%') or upper(nama) like upper('%$lccr%')";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        echo json_encode($result);
    }



    function ambil_pelaksana_kontrak()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_perusahaan where kd_skpd='$kd_skpd' AND (nama like '%$lccr%')";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        echo json_encode($result);
    }



    function load_unit()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_unit) like upper('%$kriteria%') or kd_unit like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from ms_unit $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from ms_unit $where order by kd_unit "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_unit' => $resulte['kd_unit'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_unit' => $resulte['nm_unit']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_program()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_program) like upper('%$kriteria%') or kd_program like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from m_prog $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from m_prog $where order by kd_program "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function loadm_program()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        //$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        //	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        //	    $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "where kd_skpd='$kd_skpd'";
        if ($kriteria <> '') {
            $where = "where kd_skpd='$kd_skpd' and (upper(nm_program) like upper('%$kriteria%') or kd_program like'%$kriteria%')";
        }

        //$sql = "SELECT count(*) as tot from m_prog $where" ;
        //        $query1 = $this->db->query($sql);
        //        $total = $query1->row();
        //        


        //$sql = "SELECT * from m_prog $where order by kd_program limit $offset,$rows";
        $sql = "SELECT kd_program,nm_program from trskpd $where group by kd_program,nm_program order by kd_program ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program']
            );
            $ii++;
        }

        //$result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function ambil_program()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_program, nm_program FROM m_prog where upper(kd_program) like upper('%$lccr%') or upper(nm_program) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_kegiatan()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "where kd_skpd='$kd_skpd'";
        if ($kriteria <> '') {
            $where = "where kd_skpd='$kd_skpd' and (upper(nm_kegiatan) like upper('%$kriteria%') or kd_kegiatan like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from trskpd $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        // $sql = "SELECT * from trskpd $where order by kd_kegiatan ";
        $sql = " SELECT TOP $rows * from trskpd $where and kd_gabungan not in (SELECT TOP  $offset kd_gabungan from trskpd)  order by kd_kegiatan";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_kegiatan' => $resulte['kd_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_kegiatan' => $resulte['nm_kegiatan'],
                'jns_kegiatan' => $resulte['jns_kegiatan']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_rekening1()
    {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_rek1) like upper('%$kriteria%') or kd_rek1 like'%$kriteria%')";
        }

        $sql = "SELECT * from ms_rek1 $where order by kd_rek1";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek1' => $resulte['kd_rek1'],
                'nm_rek1' => $resulte['nm_rek1']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_rekening1()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek1, nm_rek1 FROM ms_rek1 where upper(kd_rek1) like upper('%$lccr%') or upper(nm_rek1) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek1' => $resulte['kd_rek1'],
                'nm_rek1' => $resulte['nm_rek1']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_rekening2()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_rek2) like upper('%$kriteria%') or kd_rek2 like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from ms_rek2 $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from ms_rek2 $where order by kd_rek2 "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_rek2' => $resulte['kd_rek2'],
                'kd_rek1' => $resulte['kd_rek1'],
                'nm_rek2' => $resulte['nm_rek2'],
                'kelompok' => $resulte['kelompok'],
                'lra' => $resulte['lra']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function ambil_rekening2()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek2, nm_rek2 FROM ms_rek2 where upper(kd_rek2) like upper('%$lccr%') or upper(nm_rek2) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek2' => $resulte['kd_rek2'],
                'nm_rek2' => $resulte['nm_rek2']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_rekening3()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_rek3) like upper('%$kriteria%') or kd_rek3 like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from ms_rek3 $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from ms_rek3 $where order by kd_rek3 "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_rek3' => $resulte['kd_rek3'],
                'kd_rek2' => $resulte['kd_rek2'],
                'nm_rek3' => $resulte['nm_rek3'],
                'kelompok' => $resulte['kelompok'],
                'lra' => $resulte['lra']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function ambil_rekening3()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek3, nm_rek3 FROM ms_rek3 where upper(kd_rek3) like upper('%$lccr%') or upper(nm_rek3) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek3' => $resulte['kd_rek3'],
                'nm_rek3' => $resulte['nm_rek3']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_rekening4()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_rek4) like upper('%$kriteria%') or kd_rek4 like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from ms_rek4 $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from ms_rek4 $where order by kd_rek4";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_rek4' => $resulte['kd_rek4'],
                'kd_rek3' => $resulte['kd_rek3'],
                'nm_rek4' => $resulte['nm_rek4'],
                'kelompok' => $resulte['kelompok'],
                'lra' => $resulte['lra']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function ambil_rekening4()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek4, nm_rek4 FROM ms_rek4 where upper(kd_rek4) like upper('%$lccr%') or upper(nm_rek4) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek4' => $resulte['kd_rek4'],
                'nm_rek4' => $resulte['nm_rek4']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_rekening4_64()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek4_64, nm_rek4_64 FROM ms_rek4_64 where upper(kd_rek4_64) like upper('%$lccr%') or upper(nm_rek4_64) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek4' => $resulte['kd_rek4_64'],
                'nm_rek4' => $resulte['nm_rek4_64']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_rekening5()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where left(kd_rek5,2)='52' and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek5'],
                'nm_rek5' => $resulte['nm_rek5']
            );
            $ii++;
        }

        echo json_encode($result);
    }


    function load_rekening5()
    {

        $result = array();
        $row    = array();
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = " where (upper(a.nm_rek64) like upper('%$kriteria%') or a.kd_rek64 like'%$kriteria%') or
                       (upper(a.nm_rek5) like upper('%$kriteria%') or a.kd_rek5 like'%$kriteria%') ";
        }

        $sql = "SELECT count(*) as tot from ms_rek5 a $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();


        $sql = "SELECT TOP 20 PERCENT a.*,b.nm_rek5 AS nm_lo FROM ms_rek5 a LEFT JOIN ms_rek5 b ON a.map_lo=b.kd_rek5 $where order by kd_rek64 ";
        //        $sql = "SELECT a.*,b.nm_rek5 AS nm_lo FROM ms_rek5 a LEFT JOIN ms_rek5 b ON a.map_lo=b.kd_rek5 $where order by kd_rek64 limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_rek4' => $resulte['kd_rek4'],
                'kd_rek4_64' => $resulte['kd_rek4_64'],
                'kd_rek5' => $resulte['kd_rek5'],
                'kd_rek64' => $resulte['kd_rek64'],
                'map_lra1' => $resulte['map_lra1'],
                'map_lo' => $resulte['map_lo'],
                'nm_rek5' => $resulte['nm_rek5'],
                'nm_rek64' => $resulte['nm_rek64'],
                'nm_reklo' => $resulte['nm_lo']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }




    function load_daftar_harga()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(uraian) like upper('%$kriteria%') or kd_harga like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from ms_harga $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from ms_harga $where order by kd_rek5 "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_harga' => $resulte['kd_harga'],
                'kd_rek5' => $resulte['kd_rek5'],
                'uraian' => $resulte['uraian'],
                'satuan' => $resulte['satuan'],
                'harga' => $resulte['harga'],
                'harga1' => number_format($resulte['harga'])
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_bank()
    {
        $lccr = $this->input->post('q');
        /* $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(nama) like upper('%$kriteria%') or kode like'%$kriteria%')";            
        }
         */
        $sql = "SELECT * from ms_bank where (upper(nama) like upper('%$lccr%') or kode like'%$lccr%') order by kode";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_ttd()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $cbidang = '2';
        $kriteria = $this->input->post('cari');
        $where = " kd_skpd='$kd_skpd' and kode in ('BK','BP','PPTK','PPK','PA','KPA')";
        $where1 = " kd_skpd='$kd_skpd' and kode in ('BK','BP','PPTK','PPK','PA','KPA')";

        if ($kriteria <> '') {
            $where = " kd_skpd='$kd_skpd' and (upper(nama) like upper('%$kriteria%') or nip like'%$kriteria%') and kode in ('BK','BP','PPTK','PPK','PA','KPA')";
            $where1 = " kd_skpd='$kd_skpd' and (upper(nama) like upper('%$kriteria%') or nip like'%$kriteria%') and kode in ('BK','BP','PPTK','PPK','PA','KPA')";
            $sql = "SELECT count(*) as tot from ms_ttd where $where ";
        } else {
            $sql = "SELECT count(*) as tot from ms_ttd where $where";
        }

        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT top $rows a.*,case when isnull(b.kd_bkeluar,'')='' then '0' else '1' end as kd_bkeluar  
                from ms_ttd a left join (select kd_bkeluar from trhspd group by kd_bkeluar) b  on a.nip=b.kd_bkeluar where  a.nip not in (SELECT TOP $offset  nip from  
                ms_ttd where $where1  order by nip,nama ) and $where order by a.nip,a.nama "; //limit $offset,$rows";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan'],
                'pangkat' => $resulte['pangkat'],
                'kd_skpd' => $resulte['kd_skpd'],
                'kode' => $resulte['kode'],
                'cek' => $resulte['kd_bkeluar']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }



    function load_perusahaan()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(nama) like upper('%$kriteria%') or upper(pimpinan) like upper('%$kriteria%')) ";
        }
        $nm_skpd = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $sql = "SELECT count(*) as total from ms_perusahaan WHERE kd_skpd='$skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows a.* from ms_perusahaan a where kode not in (SELECT TOP $offset kode from ms_perusahaan where kd_skpd='$skpd' $where) and  kd_skpd='$skpd' $where order by a.nama";

        $query1 = $this->db->query($sql);

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id'        => $ii,
                'kode'      => $resulte['kode'],
                'nama'      => $resulte['nama'],
                'bentuk'    => $resulte['bentuk'],
                'alamat'    => $resulte['alamat'],
                'pimpinan'  => $resulte['pimpinan'],
                'id_bank'   => $resulte['id_bank'],
                'bank'      => $resulte['bank'],
                'rekening'  => $resulte['rekening'],
                'npwp'      => $resulte['npwp'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'nm_skpd'   => $nm_skpd
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }


    function load_perusahaan_lama()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "";

        if ($kriteria <> '') {
            $where = "where kd_skpd='$kd_skpd' and (upper(nama) like upper('%$kriteria%') or pimpinan like'%$kriteria%')";
            $sql = "SELECT count(*) as tot from ms_perusahaan $where ";
        } else {
            $where = "where kd_skpd='$kd_skpd'";
            $sql = "SELECT count(*) as tot from ms_perusahaan $where";
        }

        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT top $rows a.* from ms_perusahaan a $where and kode not in (SELECT $offset kode from ms_perusahaan) order by a.nama ";


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama'],
                'bentuk' => $resulte['bentuk'],
                'alamat' => $resulte['alamat'],
                'pimpinan' => $resulte['pimpinan'],
                'id_bank' => $resulte['id_bank'],
                'bank' => $resulte['bank'],
                'rekening' => $resulte['rekening'],
                'npwp' => $resulte['npwp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $nm_skpd
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }
    function cek_kontrak()
    {
        $kontrak = $this->input->post('kontrak');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT *  from trhspp WHERE no_kontrak='$kontrak' and (sp2d_batal is null OR sp2d_batal<>'1') and kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
            exit();
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        }
    }


    function cek_simpan_ttd()
    {
        $nomor    = $this->input->post('no');
        $jabat    = $this->input->post('jabat');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' ");
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        }
    }

    function hapus_master_ttd()
    {
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $kode = $this->input->post('kode');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $csql = "delete from $ctabel where $cid = '$cnid' AND kd_skpd='$kd_skpd' ";

        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function load_tapd()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "where (upper(nama) like upper('%$kriteria%') or nip like'%$kriteria%')";
        }

        $sql = "SELECT count(*) as tot from tapd $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();



        $sql = "SELECT * from tapd $where order by nip "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan'],
                'kd_skpd' => $resulte['kd_skpd']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function simpan_master()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel WHERE $cid='$lcid' and kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                echo '2';
            } else {
                echo '0';
            }
        }
    }

    function simpan_master_perusahaan()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cno = $this->input->post('cno');
        $kode = $this->input->post('kode');

        $sql = "insert into $tabel $lckolom values $lcnilai";
        $asg = $this->db->query($sql);
        if ($asg) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function update_master_perusahaan()
    {
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function no_perusahaan()
    {
        $result = array();
        $sqlno = $this->db->query("SELECT max(kode) as kode from ms_perusahaan")->row();
        $nomor = $sqlno->kode + 1;
        $result = $nomor;
        echo json_encode($nomor);
    }


    function simpan_master_bank()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $sql = "select $cid from $tabel WHERE $cid='$lcid' ";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                echo '2';
            } else {
                echo '0';
            }
        }
    }
    function simpan_master_panjar()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');

        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                echo '2';
            } else {
                echo '0';
            }
        }
    }
    function simpan_tapd()
    {

        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cnip = $this->input->post('lcid');


        $sql = "delete from $tabel where nip='$cnip'";
        $asg = $this->db->query($sql);
        if ($asg) {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
        }
    }

    // function update_master(){
    //        $query = $this->input->post('st_query');
    //        $asg = $this->db->query($query);
    //
    //    }

    function update_master()
    {
        $query = $this->input->post('st_query');
        //$query1 = $this->input->post('st_query1');
        $asg = $this->db->query($query);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
        //$asg1 = $this->db->query($query1);

    }

    function update_master2()
    {
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }
    function hapus_master()
    {
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');

        $csql = "delete from $ctabel where $cid = '$cnid'";

        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function hapus_perusahaan()
    {
        //no:cnomor,skpd:cskpd
        // $ctabel = $this->input->post('tabel');
        $kd_skpd  = $this->session->userdata('kdskpd');
        // $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');

        $csql = "delete from ms_perusahaan where kode = '$cnid' and kd_skpd='$kd_skpd'";

        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function hapus_tapd()
    {
        //no:cnomor,skpd:cskpd
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');


        $csql = "delete from $ctabel where nip = '$cid'";

        //$sql = "delete from mbidang where bidang='$ckdbid'";
        $asg = $this->db->query($csql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function neraca_awal()
    {
        $data['page_title'] = 'NERACA AWAL';
        $this->template->set('title', 'NERACA AWAL');
        $this->template->load('template', 'akuntansi/neraca_awal', $data);
    }

    function load_neraca_awal()
    {


        $sql = "SELECT * from rg_neraca  order by seq";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'seq' => $resulte['seq'],
                'aset' => $resulte['aset'],
                'nilai_lalu' => number_format($resulte['nilai_lalu'])
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function lak_awal()
    {
        $data['page_title'] = 'LAK AWAL';
        $this->template->set('title', 'LAK AWAL');
        $this->template->load('template', 'akuntansi/lak_awal', $data);
    }

    function load_lak_awal()
    {


        $sql = "SELECT * from rg_lak  order by seq";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['nor'],
                'seq' => $resulte['seq'],
                'aset' => $resulte['uraian'],
                'nilai_lalu' => number_format($resulte['nilai_lalu'])
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function lpe_awal()
    {
        $data['page_title'] = 'LPE AWAL';
        $this->template->set('title', 'LPE AWAL');
        $this->template->load('template', 'akuntansi/lpe_awal', $data);
    }

    function load_lpe_awal()
    {


        $sql = "SELECT * from map_lpe  order by seq";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['nor'],
                'seq' => $resulte['seq'],
                'uraian' => $resulte['uraian'],
                'nilai_lalu' => number_format($resulte['thn_m1'])
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function lpsal_awal()
    {
        $data['page_title'] = 'LPSAL AWAL';
        $this->template->set('title', 'LPSAL AWAL');
        $this->template->load('template', 'akuntansi/lpsal_awal', $data);
    }

    function load_lpsal_awal()
    {


        $sql = "SELECT * from map_lpsal  order by seq";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['nor'],
                'seq' => $resulte['seq'],
                'uraian' => $resulte['uraian'],
                'nilai_lalu' => number_format($resulte['thn_m1'])
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function _mpdf($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = 12, $orientasi = '')
    {

        ini_set("memory_limit", "512M");
        $this->load->library('mpdf');

        /*
        $this->mpdf->progbar_altHTML = '<html><body>
	                                    <div style="margin-top: 5em; text-align: center; font-family: Verdana; font-size: 12px;"><img style="vertical-align: middle" src="'.base_url().'images/loading.gif" /> Creating PDF file. Please wait...</div>';        
        $this->mpdf->StartProgressBarOutput();
        */

        $this->mpdf->defaultheaderfontsize = 6;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6;    /* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;

        //$this->mpdf->SetHeader('SIMAKDA||');
        $jam = date("H:i:s");
        //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Simakda| Page {PAGENO} of {nb}');
        $this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');

        $this->mpdf->AddPage($orientasi);

        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);

        $this->mpdf->Output();
    }

    function ambil_rekening5_ar()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where left(kd_rek5,2)='52' and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) order by kd_rek5";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek5'],
                'nm_rek5' => $resulte['nm_rek5']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_daftar_harga_ar()
    {

        $result   = array();
        $row      = array();
        $page     = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows     = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset   = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';

        if ($kriteria <> '') {
            $where = "where (upper(nm_rek5) like upper('%$kriteria%') or kd_rek5 like'%$kriteria%')";
        }

        $sql    = "SELECT count(*) as tot from trhharga $where";
        $query1 = $this->db->query($sql);
        $total  = $query1->row();

        $sql    = "SELECT * from trhharga $where order by kd_rek5 "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek5'],
                'nm_rek5' => $resulte['nm_rek5']
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"]  = $row;
        echo json_encode($result);
    }


    function load_daftar_harga_detail_ar($norek = '')
    {

        $norek  = $this->input->post('rekening');

        $sql    = "SELECT * from trdharga where kd_rek5 = '$norek' order by no_urut ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'         => $ii,
                'no_urut'    => $resulte['no_urut'],
                'kd_rek5'    => $resulte['kd_rek5'],
                'uraian'     => $resulte['uraian'],
                'merk'       => $resulte['merk'],
                'satuan'     => $resulte['satuan'],
                //'harga'      => number_format($resulte['harga'],"2",".",","),
                'harga'      => $resulte['harga'],
                'keterangan' => $resulte['keterangan']
            );
            $ii++;
        }
        echo json_encode($result);
    }


    function update_master_ar()
    {

        $tabel   = $this->input->post('tabel');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');

        if ($lcid <> $lcid_h) {
            $sql     = "select $cid from $tabel where $cid='$lcid'";
            $res     = $this->db->query($sql);
            if ($res->num_rows() > 0) {
                echo '1';
                exit();
            }
        }

        $query   = $this->input->post('st_query');
        $asg     = $this->db->query($query);
        if ($asg > 0) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function hapus_detail_all()
    {

        $ctabel = $this->input->post('tabel');
        $cid    = $this->input->post('cid');
        $cnid   = $this->input->post('cnid');

        $csql   = "delete from $ctabel where $cid = '$cnid'";

        $asg = $this->db->query($csql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function hapus_detail()
    {

        $urut  =  $this->input->post('curut');
        $rek   =  $this->input->post('ckdrek');

        $csql  = "delete from trdharga where no_urut='$urut' and kd_rek5='$rek' ";
        $asg   = $this->db->query($csql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function simpan_detail_standar_harga()
    {

        $proses = $this->input->post('proses');
        if ($proses == 'detail') {

            $tabel_detail = $this->input->post('tabel_detail');
            $sql_detail   = $this->input->post('sql_detail');
            $nomor        = $this->input->post('nomor');

            $sql          = " delete from trdharga where kd_rek5='$nomor' ";
            $asg          = $this->db->query($sql);

            $sql          = " insert into trdharga (no_urut,kd_rek5,uraian,merk,satuan,harga,keterangan)  ";
            $asg_detail   = $this->db->query($sql . $sql_detail);

            if ($asg_getail > 0) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }

    function load_daftar_harga_detail($norek = '')
    {

        $norek  = $this->input->post('rekening');

        $sql    = "SELECT * from trdharga where kd_rek5 = '$norek' order by no_urut ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'         => $ii,
                'no_urut'    => $resulte['no_urut'],
                'kd_rek5'    => $resulte['kd_rek5'],
                'uraian'     => $resulte['uraian'],
                'merk'       => $resulte['merk'],
                'satuan'     => $resulte['satuan'],
                //'harga'      => number_format($resulte['harga'],"2",".",","),
                'harga'      => $resulte['harga'],
                'keterangan' => $resulte['keterangan']
            );
            $ii++;
        }
        echo json_encode($result);
    }


    function load_daftar_harga_detail_ck($norek = '')
    {
        $sql    = "SELECT *, kd_rek5 as ck from ms_harga order by kd_rek5 ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'         => $ii,
                //'no_urut'    => $resulte['no_urut'],
                'kd_rek5'    => $resulte['kd_rek5'],
                'uraian'     => $resulte['uraian'],
                // 'merk'       => $resulte['merk'],
                'satuan'     => $resulte['satuan'],
                //'harga'      => number_format($resulte['harga'],"2",".",","),
                'harga'      => $resulte['harga'],
                //'keterangan' => $resulte['keterangan'],
                'ck'         => $resulte['ck']

            );
            $ii++;
        }
        echo json_encode($result);
    }




    function ambil_rekening5_all_ar()
    {

        $lccr    = $this->input->post('q');
        $notin   = $this->input->post('reknotin');
        $jnskegi = $this->input->post('jns_kegi');

        if ($notin <> '') {
            $where = " and kd_rek5 not in ($notin) ";
        } else {
            $where = " ";
        }

        if ($jnskegi == '4') {
            $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,1)='4' )
                    and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";
        } elseif ($jnskegi == '51' or $jnskegi == '52') {
            if ($jnskegi == '51') {
                $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,2)='51')
                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";
            } else {
                $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,2)='52')
                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";
            }
        } else {
            $sql = "SELECT kd_rek5, nm_rek5 FROM ms_rek5 where ( left(kd_rek5,1)='6' or left(kd_rek5,1)='7' )
                        and (upper(kd_rek5) like upper('%$lccr%') or upper(nm_rek5) like upper('%$lccr%')) $where order by kd_rek5";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek5'],
                'nm_rek5' => $resulte['nm_rek5']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function load_dhukum()
    {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_hukum) like upper('%$kriteria%') or kd_hukum like'%$kriteria%')";
        }

        $sql    = "SELECT * from m_hukum $where order by kd_hukum";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_hukum' => $resulte['kd_hukum'],
                'nm_hukum' => $resulte['nm_hukum']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function sumberdana()
    {
        $data['page_title'] = 'Master FUNGSI';
        $this->template->set('title', 'Master Fungsi');
        $this->template->load('template', 'master/fungsi/mdana', $data);
    }

    function load_dana()
    {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';
        if ($kriteria <> '') {
            $where = "where (upper(nm_sdana) like upper('%$kriteria%') or kd_sdana like'%$kriteria%')";
        }

        $sql    = "SELECT * from ms_dana $where order by kd_sdana";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sdana' => $resulte['kd_sdana'],
                'nm_sdana' => $resulte['nm_sdana']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ganti_pass()
    {

        $data['page_title'] = 'GANTI USERNAME PASSWORD';
        $this->template->set('title', 'GANTI USERNAME PASSWORD');
        $this->template->load('template', 'master/referensi/ganti_pass', $data);
    }

    function data_opd()
    {

        $data['page_title'] = 'DATA OPD';
        $this->template->set('title', 'DATA OPD');
        $this->template->load('template', 'master/referensi/data_opd', $data);
    }

    function ambil_burusan()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_bidang_urusan, nm_bidang_urusan FROM ms_bidang_urusan where upper(kd_bidang_urusan) like upper('%$lccr%') or upper(nm_bidang_urusan) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_urusan' => $resulte['kd_bidang_urusan'],
                'nm_urusan' => $resulte['nm_bidang_urusan']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function simpan_password()
    {
        //nm_admin:nm_admin,email:email,password:password,reply_pass:reply_pass
        $id             = $this->input->post('id');
        $skpd           = $this->input->post('skpd');
        //$uskpd          = $this->input->post('uskpd');
        $nm_admin       = $this->input->post('nm_admin');
        //$email          = $this->input->post('email');
        $password       = $this->input->post('password');
        $reply_pass     = $this->input->post('reply_pass');
        // $username       = $this->input->post('username');

        $msg = array();
        $csql   = "UPDATE [user] SET password=LOWER(CONVERT(VARCHAR(32), HashBytes('MD5', '$password'), 2)),nama='$nm_admin' WHERE id_user='$id' AND kd_skpd='$skpd'";
        $query1 = $this->db->query($csql);

        if ($query1) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        }
    }
    //tarik standar harga
    function sipd2simakda()
    {
        $data['page_title'] = 'sipd2simakda';
        $this->template->set('title', 'sipd2simakda');
        $this->template->load('template', 'standar_harga', $data);
    }

    function transfersipd2simakda()
    {
        ini_set('max_execution_time', 0);
        $tipe = $this->input->post('jenis');


        // $id_sh=explode("=", $url);
        //$id_sh=$id_sh[1];

        if ($tipe == 1) {
            $jenis = "SSH";
        } else if ($tipe == 2) {
            $jenis = "HSPK";
        } else if ($tipe == 3) {
            $jenis = "ASB";
        } else if ($tipe == 4) {
            $jenis = "SBU";
        } else {
            $jenis = "error";
        }

        /*        $cookie=$this->input->post('cookie');
        $cookies=array('Cookie: '.$cookie.'');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $cookies);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        print_r(curl_error($ch));*/

        $url = 'assets/data' . "/" . $jenis . ".json";
        $url = file_get_contents($url);
        $response = json_decode($url);

        $total_sipd = $response->recordsTotal;
        $daftar_standar = $response->data;
        if (!$daftar_standar) {
            $row[] = array(
                'total_sipd' => '',
                'total_simakda' => '',
                'jenis' => 'ERROR'
            );
            die();
        }
        $ii = 1;
        $this->db->query("DELETE FROM ms_standar_harga where jenis='$jenis'");
        foreach ($daftar_standar as $okei) {
            $ii++;
            $this->db->query("

                INSERT INTO ms_standar_harga (
                        harga_satuan,
                        harga_2,
                        harga_3,
                        id_standar_harga,
                        kelompok,
                        ket_teks,
                        kode_kel_standar_harga,
                        kode_standar_harga,
                        nama_kel_standar_harga,
                        nama_standar_harga,
                        satuan,
                        spesifikasi,
                        updated_at,
                        jenis) values (
                        '" . $okei->harga . "',
                        '" . $okei->harga_2 . "',
                        '" . $okei->harga_3 . "',
                        '" . $okei->id_standar_harga . "',
                        '" . $okei->kelompok . "',
                        '" . $okei->ket_teks . "',
                        '" . $okei->kode_kel_standar_harga . "',
                        '" . $okei->kode_standar_harga . "',
                        '" . $okei->nama_kel_standar_harga . "',
                        '" . $okei->nama_standar_harga . "',
                        '" . $okei->satuan . "',
                        '" . $okei->spek . "',
                        getdate(),
                        '$jenis'
                        )
                ");
        }



        /*    $rinci=$this->db->query("SELECT * from standar_harga where jenis='$jenis'");
        $ii=1;
        $this->db->query("DELETE FROM rekening_standar_harga where jenis='$jenis'");
        foreach($rinci->result() as $jiwa){
            $id_standar_harga=$jiwa->id_standar_harga;
            $id_urut =$id_sh+$ii;
            $url_rincian="https://pontianak.sipd.kemendagri.go.id/daerah/main/budget/komponen/2021/{$tipe}/tampil-komponen-akun/270/0/{$id_standar_harga}?app=budget&_=$id_urut";
            $ch = curl_init($url_rincian);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $cookies);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            print_r(curl_error($ch));
            $response = json_decode($output);
            $rincian_sh = $response->data;
            foreach($rincian_sh as $mbuh){
                $this->db->query("
                    INSERT INTO rekening_standar_harga (id_akun, id_standar_harga, kd_rek6, kode_akun, nama_akun, updated_at, jenis)
                    values (
                    '".$mbuh->id_akun."',
                    '".$mbuh->id_standar_harga."',
                    replace('".$mbuh->kode_akun."','.',''),
                    '".$mbuh->kode_akun."',
                    '".$mbuh->nama_akun."',
                    getdate(),
                    '$jenis'
                    )
                    ");
            }

            $ii++;
        } *//*END rincian rekening standar harga*/



        $jumlah = $this->db->query("SELECT count(*) tot from ms_standar_harga where jenis='$jenis'")->row()->tot;
        $row[] = array(
            'total_sipd' => $total_sipd,
            'total_simakda' => $jumlah,
            'jenis' => $jenis
        );




        echo json_encode($row);
    }
    function simpan_set_skpd()
    {
        $user = $this->input->post('user');
        $skpd = $this->input->post('skpd');
        $nama = $this->input->post('nama');

        $this->db->query("UPDATE [user] set kd_skpd='$skpd', nama='$nama' where user_name='$user'");

        $pcthang = $this->session->userdata('pcThang');

        $query = $this->db->query("SELECT * FROM [USER] WHERE USER_NAME='$user'");
        foreach ($query->result() as $row) :
            $user_id = $row->id_user;
            $Cuser = $row->user_name;
            $client['password'] = $row->password;
            $otori = $row->type;
            $Display_name = $row->nama;
            $Skpd = $row->kd_skpd;
            $client['jenis'] = $row->jenis;
            $type = $row->type;
            $bidang = $row->bidang;
        endforeach;

        $time = date("j F Y, H:i:s");
        $userx =  array(
            'pcUser' => $user_id,
            'pcOtoriName' => $otori,
            'pcThang' => $pcthang,
            'pcNama' => $Cuser,
            'pcLoginTime' => $time,
            'Display_name' => $Display_name,
            'kdskpd' => $Skpd,
            'type' => $type,
            'bidang' => $bidang
        );

        $CI = &get_instance();
        $CI->session->set_userdata('logged', $user_id);
        $CI->session->set_userdata($userx);
        echo "1";
    }
}
