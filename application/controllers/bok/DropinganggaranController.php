<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class DropinganggaranController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'DROPING ANGGARAN BOK';
        $this->template->set('title', 'DROPING ANGGARAN BOK');
        $this->template->load('template', 'bok/droppinganggaran/index', $data);
    }

    public function loaddata()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $sql = "SELECT TOP $rows a.no_kas, a.tgl_kas, a.no_bukti,a.tgl_bukti,a.kd_skpd, (SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd=a.kd_skpd)as nm_skpd, a.Keterangan as keterangan, a.jenis, SUM(b.nilai) as nilai, a.no_transaksi FROM bok_trhrka a INNER JOIN bok_trdrka b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti AND b.jenis=a.jenis WHERE a.kd_skpd='$kd_skpd' AND a.jenis IN('3') AND a.no_bukti NOT IN (SELECT TOP $offset a.no_bukti FROM bok_trhrka a INNER JOIN bok_trdrka b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$kd_skpd' AND a.jenis IN('3') GROUP BY a.no_kas, a.tgl_kas, a.no_bukti,a.tgl_bukti,a.kd_skpd, a.Keterangan,a.jenis) GROUP BY a.no_kas, a.tgl_kas, a.no_bukti,a.tgl_bukti,a.kd_skpd, a.Keterangan,a.jenis,a.no_transaksi
        ORDER BY a.tgl_kas, CAST(a.no_bukti AS INT)";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'no_kas'    => $resulte['no_kas'],
                'tgl_kas'    => $resulte['tgl_kas'],
                'no_bukti'    => $resulte['no_bukti'],
                'tgl_bukti'    => $resulte['tgl_bukti'],
                'kd_skpd'    => $resulte['kd_skpd'],
                'nm_skpd'    => $resulte['nm_skpd'],
                'keterangan'    => $resulte['keterangan'],
                'nilai'    => $resulte['nilai'],
                'jenis' => $resulte['jenis'],
                'no_transaksi' => $resulte['no_transaksi'],
            );
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function no_urut()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
            SELECT no_kas nomor,'Droping Anggaran BOK' ket,kd_skpd as kd_skpd from bok_trhrka where isnumeric(no_kas)=1 AND jenis IN ('3')
            UNION ALL
            SELECT no_kas nomor, 'Transaksi BOK' ket, kd_skpd from bok_trhtransout where isnumeric(no_kas)=1 AND jns_spp IN('3')
            UNION ALL
            SELECT no_bukti nomor, 'Terima Potongan BOK' ket, kd_skpd from bok_trhtrmpot where isnumeric(no_bukti)=1 AND jns_spp IN('3')
            UNION ALL
            SELECT no_bukti nomor, 'Setor Potongan BOK' ket, kd_skpd from bok_trhstrpot where isnumeric(no_bukti)=1 AND jns_spp IN('3')
            ) z WHERE kd_skpd = '$skpd'");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd FROM ms_skpd_jkn a WHERE a.kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);

        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }
        $query1->free_result();
        echo json_encode($result);
    }

    public function loadingdata()
    {

        $no_bukti = $this->input->post('no_bukti');
        $kd_skpd = $this->input->post('kd_skpd');
        $jenis = $this->input->post('jenis');
        $no_transaksi = $this->input->post('no_transaksi');
        // Query
        $sql = "SELECT a.no_bukti, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6, a.nm_rek6, a.nilai FROM bok_trdrka a INNER JOIN bok_trhrka b ON b.no_bukti=a.no_bukti AND b.kd_skpd=a.kd_skpd AND a.jenis=b.jenis WHERE a.no_bukti='$no_bukti' AND b.kd_skpd='$kd_skpd' AND b.jenis IN('$jenis') AND b.no_transaksi='$no_transaksi'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;


        $total = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'no_bukti'      => $resulte['no_bukti'],
                'kd_sub_kegiatan'     => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'     => $resulte['nm_sub_kegiatan'],
                'kd_rek6'     => $resulte['kd_rek6'],
                'nm_rek6'     => $resulte['nm_rek6'],
                'nilai'     => $resulte['nilai'],
            );
            $ii++;
        }
        $query1->free_result();
        echo json_encode($result);
    }

    public function hapus_data()
    {
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $no_trans = $this->input->post('no_trans');
        $cjenis = $this->input->post('cjenis');
        $hasil12 = $this->db->query("SELECT count(*) as jumlah FROM bok_trdtransout WHERE kd_skpd='$skpd' AND no_sp2d='$no_trans'");
        foreach ($hasil12->result_array() as $row) {
            $jumlah12 = $row['jumlah'];
        }
        if ($jumlah12 == 0) {
            $this->db->query("DELETE a from bok_trdrka a INNER JOIN bok_trhrka b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti AND a.jenis=b.jenis WHERE a.kd_skpd='$skpd' AND a.no_bukti='$nomor' AND a.jenis='$cjenis' AND no_transaksi='$no_trans'");
            $this->db->query("DELETE from bok_trhrka WHERE kd_skpd='$skpd' AND no_bukti='$nomor' AND jenis='$cjenis' AND no_transaksi='$no_trans' AND kd_skpd='$skpd'");
            echo '0';
        } else if ($jumlah12 > 0) {
            echo '1';
        }
    }

    public function load_subkegiatan()
    {
        $kd_skpd = $this->input->post('kd');
        $lccr = $this->input->post('q');
        // $kd_sub_kegiatan = '1.02.02.2.02.32';
        $anggaran = $this->db->query("SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd, b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
        ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$kd_skpd' and 
        b.tgl_dpa in(SELECT MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')");
        $data = $anggaran->row();
        $jns_ang = $data->jns_ang;
        $result = array();
        $sql = $this->db->query("SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan FROM trskpd a WHERE a.jns_ang='$jns_ang' AND a.kd_skpd='$kd_skpd' AND
        (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%'))");
        foreach ($sql->result_array() as $resulte) {
            $result[] = array(
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
            );
        }
        echo json_encode($result);
    }

    public function rekening_belanja()
    {
        $kd_skpd = $this->input->post('kd');
        $kd_sub_kegiatan = $this->input->post('kd_sub_kegiatan');
        $lccr = $this->input->post('q');
        $anggaran = $this->db->query("SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
        ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$kd_skpd' and 
        b.tgl_dpa in(SELECT MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')");
        $data = $anggaran->row();
        $jns_ang = $data->jns_ang;
        $result = array();
        $sql = $this->db->query("SELECT b.kd_sub_kegiatan, b.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6 FROM trskpd a INNER JOIN trdrka b ON b.kd_skpd=a.kd_skpd AND b.jns_ang=a.jns_ang AND a.kd_sub_kegiatan=b.kd_sub_kegiatan WHERE a.jns_ang='$jns_ang' AND a.kd_skpd='$kd_skpd' AND a.kd_sub_kegiatan='$kd_sub_kegiatan' AND
        (upper(b.kd_rek6) like upper('%$lccr%') or upper(b.kd_rek6) like upper('%$lccr%'))");
        foreach ($sql->result_array() as $resulte) {
            $result[] = array(
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6']
            );
        }
        echo json_encode($result);
    }

    public function simpan_data()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $username      = $this->session->userdata('pcNama');
        if (!empty($lcnilai)) {
            $sql = $this->db->query("INSERT INTO $tabel $lckolom values $lcnilai");
            $this->db->insert('bok_trhrka', array(
                'no_bukti' => $this->input->post('cno'),
                'tgl_bukti' => $this->input->post('ctgl'),
                'no_kas' => $this->input->post('cno'),
                'tgl_kas' => $this->input->post('ctgl'),
                'total' => $this->input->post('total'),
                'Keterangan' => $this->input->post('cket'),
                'kd_skpd' => $this->input->post('cskpd'),
                'jenis' => $this->input->post('cjenis'),
                'no_transaksi' => $this->input->post('no_trans'),
                'user_name' => $username
            ));
            echo '1';
        } else {
            echo '0';
        }
    }

    public function update_data()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $username = $this->session->userdata('pcNama');
        $ctgl = $this->input->post('ctgl');
        $cket = $this->input->post('cket');
        $cno = $this->input->post('cno');
        $cskpd = $this->input->post('cskpd');
        $cjenis = $this->input->post('cjenis');
        $no_trans = $this->input->post('no_trans');
        $no_trans_hidden = $this->input->post('no_trans_hidden');
        $hasil12 = $this->db->query("SELECT count(*) as jumlah FROM jkn_trdtransout WHERE kd_skpd='$cskpd' AND no_sp2d='$no_trans_hidden'");
        foreach ($hasil12->result_array() as $row) {
            $jumlah12 = $row['jumlah'];
        }
        if ($jumlah12 == 0) {
            if (!empty($lcnilai)) {
                $this->db->query("DELETE a from bok_trdrka a INNER JOIN bok_trhrka b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti AND a.jenis=b.jenis WHERE a.kd_skpd='$cskpd' AND a.no_bukti='$cno' AND a.jenis='$cjenis' AND b.no_transaksi='$no_trans_hidden'");
                $sql = $this->db->query("INSERT INTO $tabel $lckolom values $lcnilai");
                $this->db->query("UPDATE bok_trhrka set tgl_kas='$ctgl', tgl_bukti='$ctgl', no_kas='$cno',Keterangan='$cket', no_transaksi='$no_trans' WHERE no_bukti='$cno' AND kd_skpd='$cskpd' AND jenis='$cjenis'");
                echo '1';
            }
        } else if ($jumlah12 > 0) {
            echo '0';
        }
    }
}
