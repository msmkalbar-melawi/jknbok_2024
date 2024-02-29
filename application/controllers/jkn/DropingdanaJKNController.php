<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class DropingdanaJKNController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'INPUT DROPPING DANA JKN';
        $this->template->set('title', 'INPUT DROPPING DANA JKN');
        $this->template->load('template', 'jkn/droppingdana/index', $data);
    }


    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd FROM ms_skpd a WHERE a.kd_skpd ='1.02.0.00.0.00.01.0000'";
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

    public function sp2d_jkn()
    {
        $skpd     = $this->input->post('kd');
        $kriteria = $this->input->post('q');
        $giat = $this->input->post('giat');
        if ($kriteria <> '') {
            $where = " AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";
        }

        $sql = "SELECT c.kd_skpd, c.no_sp2d, c.tgl_sp2d, c.nilai, a.jns_spp FROM trhspp a INNER JOIN trdspp b ON b.kd_skpd=a.kd_skpd AND b.no_spp=a.no_spp INNER JOIN trhsp2d c ON c.kd_skpd=a.kd_skpd AND c.no_spp=a.no_spp WHERE c.kd_skpd='$skpd' AND b.kd_sub_kegiatan='$giat' GROUP BY c.kd_skpd, c.no_sp2d, c.tgl_sp2d, c.nilai, a.jns_spp";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'nilai' => $resulte['nilai']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
    public function load_rekeningbelanja_sp2d_jkn()
    {
        $kd = $this->input->post('kd');
        $no_sp2d = $this->input->post('no_sp2d');
        $sql = "SELECT b.kd_sub_kegiatan, b.kd_rek6, b.nm_rek6,b.nilai, a.jns_spp FROM trhspp a INNER JOIN trdspp b ON b.kd_skpd=a.kd_skpd AND b.no_spp=a.no_spp INNER JOIN trhsp2d c ON c.kd_skpd=a.kd_skpd AND c.no_spp=a.no_spp WHERE c.kd_skpd='$kd' AND c.no_sp2d='$no_sp2d' AND a.jns_spp IN ('5','6')";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'nilai' => $resulte['nilai'],

            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
    public function load_nilai_sp2d_jkn()
    {
        $no_sp2d = $this->input->post('nosp2d');
        $hrek = $this->input->post('hrek');
        $sql = "SELECT ISNULL(SUM(a.nilai),0) as nilai FROM jkn_trdrka a INNER JOIN jkn_trhrka b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.no_sp2d='$no_sp2d' AND a.kd_rek6='$hrek'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'nilai' => number_format($resulte['nilai'], 2, '.', ',')
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
    public function load_nilai_sp2d_all()
    {
        $skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT ISNULL(SUM(nilai),0) as nilai FROM tr_setorpelimpahan_bank_cms WHERE kd_skpd_sumber='$skpd' GROUP BY kd_sub_kegiatan";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'nilai' => number_format($resulte['nilai'], 2, '.', ',')
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
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

    public function loaddata()
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
            $where = "AND (upper(a.bukti) like upper('%$kriteria%')) or a.tgl_bukti like '%$kriteria%' ";
        }

        $sql = "SELECT count(*) as tot from jkn_trhrka a where a.kd_skpd_sumber='$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows a.no_bukti, a.tgl_bukti, a.kd_skpd, a.kd_skpd_sumber, a.total, a.Keterangan, a.status_ambil, a.no_sp2d, a.jenis, a.no_transaksi, (SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd=a.kd_skpd) as nm_skpd FROM jkn_trhrka a WHERE a.kd_skpd_sumber='$kd_skpd' AND a.jenis IN('2') AND a.no_bukti NOT IN(SELECT TOP $offset no_bukti FROM jkn_trhrka WHERE kd_skpd_sumber=a.kd_skpd_sumber) ORDER BY a.tgl_bukti, CAST(a.no_bukti as INT)";
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
                'tgl_bukti'     => $resulte['tgl_bukti'],
                'kd_skpd'     => $resulte['kd_skpd'],
                'nm_skpd'     => $resulte['nm_skpd'],
                'no_sp2d'      => $resulte['no_sp2d'],
                'total'       => $resulte['total'],
                'keterangan'  => $resulte['Keterangan'],
                'kd_skpd_sumber'    =>  $resulte['kd_skpd_sumber'],
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

    public function hapus_data()
    {
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $no_trans = $this->input->post('no_trans');
        $skpdjkn = $this->input->post('skpdjkn');
        $cjenis = $this->input->post('cjenis');
        $hasil1 = $this->db->query("SELECT count(*) as jumlah FROM jkn_trhrka WHERE no_bukti='$nomor' AND kd_skpd_sumber='$skpd' AND jenis='$cjenis' AND status_ambil='1'");
        foreach ($hasil1->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            echo '1';
        } else {
            $this->db->query("DELETE a from jkn_trdrka a INNER JOIN jkn_trhrka b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti AND a.jenis=b.jenis WHERE a.kd_skpd='$skpdjkn' AND a.no_bukti='$nomor' AND b.jenis='$cjenis' AND no_transaksi='$no_trans'");
            $this->db->query("DELETE from jkn_trhrka WHERE kd_skpd_sumber='$skpd' AND no_bukti='$nomor' AND jenis='$cjenis' AND no_transaksi='$no_trans' AND kd_skpd='$skpdjkn'");
            echo '0';
        }
    }

    public function simpan_data()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $username      = $this->session->userdata('pcNama');
        if (!empty($lcnilai)) {
            $sql = $this->db->query("INSERT INTO $tabel $lckolom values $lcnilai");
            $this->db->insert('jkn_trhrka', array(
                'no_bukti' => $this->input->post('cno'),
                'tgl_bukti' => $this->input->post('ctgl'),
                'total' => $this->input->post('total'),
                'Keterangan' => $this->input->post('cket'),
                'kd_skpd' => $this->input->post('skpdjkn'),
                'kd_skpd_sumber' => $this->input->post('cskpd'),
                'jenis' => $this->input->post('cjenis'),
                'no_transaksi' => $this->input->post('no_trans'),
                'no_sp2d' => $this->input->post('sp2d'),
                'user_name' => $username
            ));
            echo '1';
        } else {
            echo '0';
        }
    }

    public function no_urut()
    {
        $skpd     = $this->input->post('kdskpd');
        $query1 = $this->db->query("SELECT case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
            select no_bukti nomor,'Pelimpahan JKN' ket, kd_skpd as kd_skpd from jkn_trhrka where isnumeric(no_bukti)=1 AND jenis IN ('2')
            ) z WHERE kd_skpd = '$skpd'");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor']
            );
            $ii++;
        }
        // $query1->free_result();
        echo json_encode($result);
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
        $skpdjkn = $this->input->post('skpdjkn');
        $cjenis = $this->input->post('cjenis');
        $no_trans = $this->input->post('no_trans');
        if (!empty($lcnilai)) {
            $this->db->query("DELETE a from jkn_trdrka a INNER JOIN jkn_trhrka b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti AND a.jenis=b.jenis WHERE a.kd_skpd='$skpdjkn' AND a.no_bukti='$cno' AND a.jenis='$cjenis' AND b.no_transaksi='$no_trans'");
            $sql = $this->db->query("INSERT INTO $tabel $lckolom values $lcnilai");
            $this->db->query("UPDATE jkn_trhrka set tgl_bukti='$ctgl',Keterangan='$cket', jenis='$cjenis', no_transaksi='$no_trans', kd_skpd='$skpdjkn' WHERE no_bukti='$cno' AND kd_skpd_sumber='$cskpd' AND jenis='$cjenis' AND kd_skpd='$skpdjkn'");
            echo '1';
        } else {
            echo '0';
        }
    }

    public function cari_rekening_awal()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_perusahaan WHERE kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'          => $ii,
                'rekening'    => $resulte['rekening'],
                'nama'    => $resulte['nama']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }


    public function cari_rekening_tujuan()
    {
        $kd_skpd  = $this->input->post('ckode');
        $sql = "SELECT * FROM ms_perusahaan WHERE kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'          => $ii,
                'rekening'    => $resulte['rekening'],
                'nama'    => $resulte['nama'],
                'bank'    => $resulte['bank']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    public function load_sp2d()
    {
        $skpd  = $this->input->post('kd');
        $kd_sub_kegiatan  = $this->input->post('kd_sub_kegiatan');
    }
}
