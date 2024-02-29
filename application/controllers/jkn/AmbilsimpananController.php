<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class AmbilsimpananController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'INPUT AMBIL DANA JKN';
        $this->template->set('title', 'INPUT AMBIL DANA JKN');
        $this->template->load('template', 'jkn/ambilsimpanan/index', $data);
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
        $query1->free_result();
    }

    function config_skpd_jkn1()
    {
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd FROM ms_skpd_jkn a";
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
        $query1->free_result();
    }

    public function config_skpd_jkn()
    {
        $sql = "SELECT * FROM ms_skpd_jkn";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd'   => $resulte['kd_skpd'],
                'nm_skpd'   => $resulte['nm_skpd'],
            );
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

    public function loaddata(Type $var = null)
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $bid = $kd_skpd;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(a.kas) like upper('%$kriteria%')) or a.tgl_kas like '%$kriteria%' ";
        }

        $sql = "SELECT count(*) as tot from jkn_trhrka a where a.kd_skpd='$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows a.no_kas, a.no_bukti, a.tgl_kas, a.kd_skpd, a.kd_skpd_sumber,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd_sumber) as nm_skpd_sumber, a.total, a.Keterangan_terima, a.status_ambil, a.no_sp2d, a.jenis, a.no_transaksi, (SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd=a.kd_skpd) as nm_skpd FROM jkn_trhrka a WHERE a.kd_skpd='$kd_skpd' AND a.jenis IN('2') AND a.no_kas NOT IN(SELECT TOP $offset no_kas FROM jkn_trhrka WHERE kd_skpd=a.kd_skpd) ORDER BY a.tgl_bukti, CAST(a.no_kas as INT)";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;



        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_ambil'] == '1') {
                $s = '&#10004';
            } else {
                $s = '&#10008';
            }
            $row[] = array(
                'id'          => $ii,
                'no_bukti'      => $resulte['no_bukti'],
                'no_kas'      => $resulte['no_kas'],
                'tgl_kas'     => $resulte['tgl_kas'],
                'kd_skpd'     => $resulte['kd_skpd'],
                'nm_skpd'     => $resulte['nm_skpd'],
                'no_sp2d'      => $resulte['no_sp2d'],
                'total'       => $resulte['total'],
                'keterangan'  => $resulte['Keterangan_terima'],
                'kd_skpd_sumber'    =>  $resulte['kd_skpd_sumber'],
                'nm_skpd_sumber'    =>  $resulte['nm_skpd_sumber'],
                'jenis'      => $resulte['jenis'],
                'status' => $s,
                'status_ambil' => $resulte['status_ambil'],
                'no_transaksi' => $resulte['no_transaksi']
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    public function hapus_data()
    {
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $sql = "DELETE FROM jkn_tr_ambilsimpanan WHERE kd_skpd='$skpd' AND no_kas='$nomor'";
        $asg = $this->db->query($sql);
        echo '1';
    }

    public function simpan_data()
    {
        $no_kas_sp2d = $this->input->post('no_kas_sp2d');
        $cno = $this->input->post('cno');
        $cskpd = $this->input->post('cskpd');
        $ctgl = $this->input->post('ctgl');
        $lnnilai = $this->input->post('lnnilai');
        $lcket = $this->input->post('lcket');
        $this->db->trans_start();
        $hasil1 = $this->db->query("SELECT count(*) as jumlah FROM jkn_tr_ambilsimpanan WHERE no_kas='$cno' AND kd_skpd = '$cskpd'");
        foreach ($hasil1->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            echo '1';
        }
        $simpan = $this->db->insert('jkn_tr_ambilsimpanan', array(
            'no_kas'   => $cno,
            'tgl_kas'  => $ctgl,
            'no_bukti' => $no_kas_sp2d,
            'tgl_bukti' => $ctgl,
            'kd_skpd'  => $cskpd,
            'nilai'  => $lnnilai,
            'keterangan' => $lcket,
        ));
        if ($simpan) {
            $sql2 = "UPDATE a SET a.status_ambil='1' FROM jkn_tr_setorpelimpahan_bank_cms a WHERE a.no_bukti='$no_kas_sp2d' and a.kd_skpd='$cskpd'";
            $asg2 = $this->db->query($sql2);
            echo '2';
        } else {
            echo '0';
        }
        $this->db->trans_complete();
    }

    public function update_data()
    {
        $cno = $this->input->post('cno');
        $cjenis = $this->input->post('cjenis');
        $cket = $this->input->post('cket');
        $ctgl = $this->input->post('ctgl');
        $cskpd = $this->input->post('cskpd');
        $no_trans = $this->input->post('no_trans');
        $skpdjkn = $this->input->post('skpdjkn');
        $status = $this->input->post('status');
        if ($status == 'Batal Ambil') {
            $asg =  $this->db->query("UPDATE jkn_trhrka set tgl_kas='$ctgl', no_kas= NULL,Keterangan_terima=NULL, status_ambil='0' WHERE no_transaksi='$no_trans' AND kd_skpd_sumber='$cskpd' AND jenis='$cjenis' AND kd_skpd='$skpdjkn'");
        } else {
            $asg =  $this->db->query("UPDATE jkn_trhrka set tgl_kas='$ctgl', no_kas='$cno',Keterangan_terima='$cket', status_ambil='1' WHERE no_transaksi='$no_trans' AND kd_skpd_sumber='$cskpd' AND jenis='$cjenis' AND kd_skpd='$skpdjkn'");
        }
        if (!$asg) {
            echo "0";
        } else {
            echo "1";
        }
        $this->db->trans_complete();
    }

    public function drop_dana()
    {
        $lccr = $this->input->post('q');
        $skpd     = $this->session->userdata('kdskpd');
        $where = '';
        if ($lccr <> '') {
            $where = "AND (upper(tgl_bukti) like upper('%$lccr%') or keterangan like '%$lccr%')";
        }
        $sql = "SELECT *, (SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd=jkn_tr_setorpelimpahan_bank_cms.kd_skpd) as nm_skpd from jkn_tr_setorpelimpahan_bank_cms where kd_skpd='$skpd' $where";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'          => $ii,
                'no_kas'      => $resulte['no_kas'],
                'tgl_kas'     => $resulte['tgl_kas'],
                'no_bukti'      => $resulte['no_bukti'],
                'tgl_bukti'     => $resulte['tgl_bukti'],
                'kd_skpd'     => $resulte['kd_skpd'],
                'nm_skpd'     => $resulte['nm_skpd'],
                'no_sp2d'      => $resulte['no_sp2d'],
                'nilai'       => number_format($resulte['nilai']),
                'nilai2'       => $resulte['nilai'],
                'keterangan'  => $resulte['keterangan'],
                'kd_skpd_sumber'    => $resulte['kd_skpd_sumber'],
                'jenis_spp'      => $resulte['jenis_spp'],
                'ket_tujuan'      => $resulte['ket_tujuan'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'status_validasi' => $resulte['status_validasi'],
                'status_upload' => $resulte['status_upload']
            );
            $ii++;
        }
        $query1->free_result();
        echo json_encode($result);
    }

    public function no_urut()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
            SELECT no_kas nomor,'Droping Anggaran JKN' ket,kd_skpd as kd_skpd from jkn_trhrka where isnumeric(no_kas)=1 AND jenis IN('1','2')
            UNION ALL
            SELECT no_kas nomor, 'Transaksi JKN' ket, kd_skpd from jkn_trhtransout where isnumeric(no_kas)=1 AND jns_spp IN('1','2')
            UNION ALL
            SELECT no_bukti nomor, 'Terima Potongan JKN' ket, kd_skpd from jkn_trhtrmpot where isnumeric(no_bukti)=1 AND jns_spp IN('1','2')
            UNION ALL
            SELECT no_bukti nomor, 'Setor Potongan JKN' ket, kd_skpd from jkn_trhstrpot where isnumeric(no_bukti)=1 AND jns_spp IN('1','2')
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

    public function loadingdata()
    {

        $no_bukti = $this->input->post('no_bukti');
        $kd_skpd = $this->input->post('kd_skpd');
        $kd_skpd_sumber = $this->input->post('kd_skpd_sumber');
        $jenis = $this->input->post('jenis');
        // Query
        $sql = "SELECT a.no_bukti, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6, a.nm_rek6, a.nilai FROM jkn_trdrka a INNER JOIN jkn_trhrka b ON b.no_bukti=a.no_bukti AND b.kd_skpd=a.kd_skpd AND b.jenis=a.jenis WHERE a.no_bukti='$no_bukti' AND b.kd_skpd='$kd_skpd' AND b.kd_skpd_sumber='$kd_skpd_sumber' AND b.jenis IN('$jenis')";
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
}
