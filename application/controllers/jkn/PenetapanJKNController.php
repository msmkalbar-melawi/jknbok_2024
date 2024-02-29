<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class PenetapanJKNController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'INPUT PENETAPAN JKN';
        $this->template->set('title', 'INPUT PENETAPAN JKN');
        $this->template->load('template', 'jkn/penetapan/index', $data);
    }


    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd FROM ms_skpd_jkn a WHERE a.kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);

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
        // $query1->free_result();
    }

    public function ambil_rek_tetap()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM 
        trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 
        where a.kd_skpd = '1.02.0.00.0.00.01.0000' and left(a.kd_rek6,1)='4' and 
        (upper(a.kd_rek6) like upper('%$lccr%') or b.nm_rek6 like '%$lccr%') order by kd_rek6";


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'kd_rek' => $resulte['kd_rek'],
                'nm_rek' => $resulte['nm_rek'],
                'nm_rek5' => $resulte['nm_rek5'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    public function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $no_hide    = $this->input->post('no_hide');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $hasil1 = $this->db->query("SELECT count(*) as jumlah FROM jkn_tr_tetap where no_tetap='$nomor' and kd_skpd = '$kd_skpd'");
        $hasil2 = $this->db->query("SELECT count(*) as jumlah FROM jkn_tr_tetap where no_tetap='$no_hide' and kd_skpd = '$kd_skpd' AND [status]='1'");
        foreach ($hasil1->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        foreach ($hasil2->result_array() as $row) {
            $jumlah1 = $row['jumlah'];
        }

        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
        } else if ($jumlah1 > 0) {
            $msg = array('pesan' => '2');
        } else {
            $msg = array('pesan' => '0');
        }
        echo json_encode($msg);
    }

    public function simpan_data()
    {
        $tabel          = $this->input->post('tabel');
        $lckolom        = $this->input->post('kolom');
        $lcnilai        = $this->input->post('nilai');
        $cid            = $this->input->post('cid');
        $sql            = "insert into $tabel $lckolom values $lcnilai";
        $asg            = $this->db->query($sql);
        if ($asg) {
            $msg = array('pesan' => '1');
        } else {
            $msg = array('pesan' => '0');
        }
        echo json_encode($msg);
    }

    public function loaddata(Type $var = null)
    {
        $skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        // echo ($kriteria);
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (a.no_tetap LIKE '%$kriteria%' OR a.tgl_tetap LIKE '%$kriteria%' OR a.keterangan LIKE '%$kriteria%') ";
        }

        $sql = "SELECT count(*) as total from tr_tetap a WHERE a.kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();
        $sql = "SELECT top $rows a.*, (SELECT b.nm_rek6 FROM ms_rek6 b WHERE a.kd_rek6=b.kd_rek6) as nm_rek6, b.sumber FROM jkn_tr_tetap a 
                left join jkn_tr_terima b on a.no_tetap=b.no_tetap and a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$skpd'
                $where AND a.no_tetap NOT IN (SELECT TOP $offset a.no_tetap FROM jkn_tr_tetap a WHERE a.kd_skpd='$skpd' $where 
                ORDER BY a.tgl_tetap,a.no_tetap ) ORDER BY tgl_tetap,no_tetap";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_tetap'          => $resulte['no_tetap'],
                'tgl_tetap'         => $resulte['tgl_tetap'],
                'kd_skpd'           => $resulte['kd_skpd'],
                'keterangan'        => $resulte['keterangan'],
                'nilai'             => number_format($resulte['nilai']),
                'kd_rek6'           => $resulte['kd_rek6'],
                'jenis'             => $resulte['jenis'],
                'nm_rek6'           => $resulte['nm_rek6'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'kd_rek'            => $resulte['kd_rek_lo'],
                'sumber'            => $resulte['sumber'],
                'user_name'         => $resulte['user_name']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    public function update_data()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $lckolom        = $this->input->post('kolom');
        $lcnilai        = $this->input->post('nilai');
        $no_hide            = $this->input->post('no_hide');
        $sql            = "delete from jkn_tr_tetap where kd_skpd='$skpd' and no_tetap='$no_hide'";
        $asg            = $this->db->query($sql);
        if ($asg) {
            $sql            = "insert into jkn_tr_tetap $lckolom values $lcnilai";
            $asg            = $this->db->query($sql);
            $msg = array('pesan' => '1');
        } else {
            $msg = array('pesan' => '0');
        }
        echo json_encode($msg);
    }

    public function hapus_data()
    {
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $hasil1 = $this->db->query("SELECT count(*) as jumlah FROM jkn_tr_terima where no_tetap='$nomor' and kd_skpd = '$skpd'");
        foreach ($hasil1->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        $msg = [];
        if ($jumlah > 0) {
            echo '1';
        } else {
            $sql = "DELETE from jkn_tr_tetap where kd_skpd='$skpd' AND no_tetap='$nomor'";
            $asg = $this->db->query($sql);
            echo '0';
        }
        // echo json_encode($msg);
    }
}
