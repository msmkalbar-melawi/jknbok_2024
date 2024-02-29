<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class BOKTransController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function boktrans()
    {
        $data['page_title'] = 'INPUT TRANSAKSI BOK';
        $this->template->set('title', 'INPUT TRANSAKSI BOK');
        $this->template->load('template', 'bok/transaksi/boktrans', $data);
    }

    public function no_urut()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
            SELECT no_kas nomor, 'Penerimaan BOK' ket, kd_skpd from bok_tr_terima where isnumeric(no_kas)=1
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
    public function kode_sub()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = $this->db->query("SELECT a.kd_sub_kegiatan as kd_sub_kegiatan, a.nm_sub_kegiatan as nm_sub_kegiatan FROM bok_trdrka a WHERE a.kd_skpd='$skpd' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan");
        $result = array();
        $ii = 0;
        foreach ($sql->result_array() as $resulte) {
            $result[] = array(
                'id'          => $ii,
                'kd_sub_kegiatan'    => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'    => $resulte['nm_sub_kegiatan']
            );
            $ii++;
        }
        echo json_encode($result);
        $sql->free_result();
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

    // public function sp2d()
    // {
    //     $kd_sub_kegiatan = $this->input->post('kd_sub_kegiatan');
    //     $jnsbeban = $this->input->post('jnsbeban');
    //     // echo ($jnsbeban);
    //     $skpd = $this->input->post('cskpd');
    //     if ($jnsbeban == '1') {
    //         $sql = "SELECT a.no_transaksi, a.tgl_kas, a.jenis FROM jkn_trhrka a INNER JOIN jkn_trdrka b ON b.no_bukti=a.no_bukti AND a.kd_skpd=b.kd_skpd AND a.jenis=b.jenis WHERE a.kd_skpd='$skpd' AND a.jenis IN('1') AND b.kd_sub_kegiatan='$kd_sub_kegiatan' GROUP BY b.kd_sub_kegiatan,a.no_transaksi, a.tgl_kas, a.jenis";
    //     } else if ($jnsbeban == '2') {
    //         $sql = "SELECT a.no_transaksi, a.tgl_kas, a.jenis FROM jkn_trhrka a WHERE a.kd_skpd='$skpd' AND a.jenis IN('2') AND a.status_ambil='1'";
    //     } else if ($jnsbeban == '3') {
    //         $sql = "SELECT a.no_transaksi, a.tgl_kas, a.jenis FROM bok_trhrka a WHERE a.kd_skpd='$skpd' AND a.jenis IN('3')";
    //     }

    //     $query1 = $this->db->query($sql);
    //     $result = array();
    //     $ii = 0;
    //     foreach ($query1->result_array() as $resulte) {
    //         $result[] = array(
    //             'id'          => $ii,
    //             'no_transaksi' => $resulte['no_transaksi'],
    //             'tgl_kas' => $resulte['tgl_kas'],
    //             'jenis' => $resulte['jenis'],

    //         );
    //         $ii++;
    //     }
    //     echo json_encode($result);
    //     $query1->free_result();
    // }
    public function rekening_sp2d()
    {
        $skpd = $this->input->post('cskpd');
        $this->load->helper('custom');
        $jns_ang = jns_ang_bok($skpd);
        //$cno_sp2d = $this->input->post('cno_sp2d');
        $jnsbeban = $this->input->post('jnsbeban');
        // echo $jnsbeban;
        // return;
        $sql;
        if ($jnsbeban == '3') {
            $sql = "SELECT b.kd_sub_kegiatan,b.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6, b.nilai FROM bok_trhrka a INNER JOIN bok_trdrka b ON b.kd_skpd=a.kd_skpd AND b.jns_ang=a.jns_ang WHERE a.kd_skpd='$skpd' AND a.jns_ang='$jns_ang'";
        }
        // } else if ($jnsbeban == '2') {
        //     $sql = "SELECT b.kd_sub_kegiatan,b.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6, b.nilai FROM jkn_trhrka a INNER JOIN jkn_trdrka b ON b.kd_skpd=a.kd_skpd AND b.jns_ang=a.jns_ang WHERE a.kd_skpd='$skpd' AND a.jns_ang='$jns_ang'";
        // }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'          => $ii,
                'kd_sub_kegiatan'    => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'    => $resulte['nm_sub_kegiatan'],
                'kd_rek6'    => $resulte['kd_rek6'],
                'nm_rek6'    => $resulte['nm_rek6'],
                'nilai'    => $resulte['nilai']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    public function load_realisasi_rekeningsp2d()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $cnosp2d = $this->input->post('cnosp2d');
        $ckd_rek6 = $this->input->post('ckd_rek6');
        $ckd_sub_kegiatan = $this->input->post('ckd_sub_kegiatan');
        $sql = "SELECT sum(a.nilai) as nilai FROM bok_trdtransout a INNER JOIN bok_trhtransout b ON b.no_bukti=a.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$kd_skpd' AND a.kd_rek6='$ckd_rek6' AND a.kd_sub_kegiatan='$ckd_sub_kegiatan'";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'nilai'    => $resulte['nilai']
            );
        }
        echo json_encode($result);
        $query1->free_result();
    }

    public function simpan_data()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $nilaipot = $this->input->post('nilaipot');
        $lckolompot = $this->input->post('kolompot');
        $no_potongan  = $this->input->post('no_potongan');
        $username      = $this->session->userdata('pcNama');
        $sql = "insert into $tabel $lckolom values $lcnilai";
        $asg = $this->db->query($sql);
        if ($asg) {
            $this->db->insert('bok_trhtransout', array(
                'no_kas' => $this->input->post('cno'),
                'tgl_kas' => $this->input->post('ctgl'),
                'no_bukti' => $this->input->post('cno'),
                'tgl_bukti' => $this->input->post('ctgl'),
                'kd_skpd' => $this->input->post('cskpd'),
                'ket' => $this->input->post('cket'),
                'jns_spp' => $this->input->post('cjenis'),
                'total' => $this->input->post('total'),
                'no_kas_pot' => $this->input->post('no_potongan'),
                'kd_skpd_sumber' => '1.02.0.00.0.00.01.0000',
                'username' => $username
            ));
            if (!empty($nilaipot)) {
                $sql = "insert into bok_trdtrmpot $lckolompot $nilaipot";
                $asg = $this->db->query($sql);
                $this->db->insert('bok_trhtrmpot', array(
                    'no_bukti' => $this->input->post('no_potongan'),
                    'tgl_bukti' => $this->input->post('ctgl'),
                    'kd_skpd' => $this->input->post('cskpd'),
                    'jns_spp' => $this->input->post('cjenis'),
                    'ket' => 'Terima Potongan',
                    'status' => 0,
                    'nilai' => $this->input->post('total_potongan'),
                    'no_kas' => $this->input->post('cno'),
                    'username' => $username
                ));
            }
            echo '1';
        } else {
            echo '0';
        }
    }

    public function loaddata()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(a.no_kas) like upper('%$kriteria%') or a.tgl_kas like '%$kriteria%') ";
        }

        $sql = "SELECT count(*) as tot from bok_trhtransout a WHERE a.kd_skpd = '$kd_skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows a.no_kas_pot, a.no_kas, a.tgl_kas, a.no_bukti,a.tgl_bukti,a.kd_skpd, a.no_sp2d, a.ket as keterangan, b.kd_sub_kegiatan, b.kd_rek6, b.nm_rek6, SUM(b.nilai) as nilai , a.jns_spp, (SELECT CASE WHEN no_kas=a.no_bukti THEN '1' ELSE 0 END FROM bok_trlpj WHERE bok_trlpj.no_bukti=a.no_bukti AND bok_trlpj.kd_skpd=a.kd_skpd ) as [status] FROM bok_trhtransout a INNER JOIN bok_trdtransout b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$kd_skpd' AND
        a.jns_spp IN('3') $where AND a.no_kas NOT IN (SELECT TOP $offset a.no_kas FROM bok_trhtransout a INNER JOIN bok_trdtransout b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$kd_skpd' $where ORDER BY a.tgl_kas,TRY_CAST(a.no_kas AS INT) ) GROUP BY a.no_kas, a.tgl_kas, a.tgl_bukti, a.tgl_bukti,a.kd_skpd, a.no_sp2d, a.ket, b.kd_sub_kegiatan, b.kd_rek6, b.nm_rek6, a.no_bukti,a.jns_spp,a.no_kas_pot ORDER BY a.tgl_kas,TRY_CAST(a.no_kas AS INT)";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status'] == '1') {
                $status = '&#8744;';
            } else {
                $status = '&#8855;';
            }
            $row[] = array(
                'no_kas'    => $resulte['no_kas'],
                'tgl_kas'    => $resulte['tgl_kas'],
                'no_bukti'    => $resulte['no_bukti'],
                'tgl_bukti'    => $resulte['tgl_bukti'],
                'kd_skpd'    => $resulte['kd_skpd'],
                'no_sp2d'    => $resulte['no_sp2d'],
                'keterangan'    => $resulte['keterangan'],
                'kd_sub_kegiatan'    => $resulte['kd_sub_kegiatan'],
                'kd_rek6'    => $resulte['kd_rek6'],
                'nm_rek6'    => $resulte['nm_rek6'],
                'nilai'    => $resulte['nilai'],
                'jns_spp' => $resulte['jns_spp'],
                'no_kas_pot' => $resulte['no_kas_pot'],
                'status' => $status,
                'status1' => $resulte['status']

            );
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    public function loadingdata()
    {
        $no  = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $no_sp2d  = $this->input->post('no_sp2d');
        $sql = "SELECT a.no_kas, a.tgl_kas, a.no_bukti,a.tgl_bukti,a.kd_skpd, a.ket as keterangan, b.kd_sub_kegiatan, b.kd_rek6, b.nm_rek6, SUM(b.nilai) as nilai , a.jns_spp FROM bok_trhtransout a INNER JOIN bok_trdtransout b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$skpd' AND a.no_bukti='$no' GROUP BY a.no_kas, a.tgl_kas, a.tgl_bukti, a.tgl_bukti,a.kd_skpd, a.ket, b.kd_sub_kegiatan, b.kd_rek6, b.nm_rek6, a.no_bukti,a.jns_spp";
        $query1 = $this->db->query($sql);
        $result = array();
        $total = 0;
        foreach ($query1->result_array() as $resulte) {
            $total += $resulte['nilai'];
            $result[] = array(
                'no_kas'    => $resulte['no_kas'],
                'tgl_kas'    => $resulte['tgl_kas'],
                'no_bukti'    => $resulte['no_bukti'],
                'tgl_bukti'    => $resulte['tgl_bukti'],
                'kd_skpd'    => $resulte['kd_skpd'],
                'keterangan'    => $resulte['keterangan'],
                'kd_sub_kegiatan'    => $resulte['kd_sub_kegiatan'],
                'kd_rek6'    => $resulte['kd_rek6'],
                'nm_rek6'    => $resulte['nm_rek6'],
                'nilai'    => $resulte['nilai'],
                'jns_spp' => $resulte['jns_spp'],
                'total' => $total
            );
        }
        echo json_encode($result);
        $query1->free_result();
    }
    public function loadingdata_potongan()
    {
        $no  = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $no_sp2d  = $this->input->post('no_sp2d');
        $no_kas_pot  = $this->input->post('no_kas_pot');
        $sql = "SELECT a.*, b.ket as keteranganpot, b.no_sp2d, b.tgl_bukti FROM bok_trdtrmpot a INNER JOIN bok_trhtrmpot b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$skpd' AND b.no_kas='$no' AND a.no_bukti='$no_kas_pot'";
        $query1 = $this->db->query($sql);
        $result = array();
        $total = 0;
        foreach ($query1->result_array() as $resulte) {
            $total += $resulte['nilai'];
            $result[] = array(
                'no_bukti'    => $resulte['no_bukti'],
                'tgl_bukti'    => $resulte['tgl_bukti'],
                'kd_skpd'    => $resulte['kd_skpd'],
                'keteranganpot'    => $resulte['keteranganpot'],
                'kd_sub_kegiatan'    => $resulte['kd_sub_kegiatan'],
                'kd_rek6'    => $resulte['kd_rek6'],
                'nm_rek6'    => $resulte['nm_rek6'],
                'map_pot'    => $resulte['map_pot'],
                'ebilling'    => $resulte['ebilling'],
                'kd_rek_trans'    => $resulte['kd_rek_trans'],
                'nilai'    => $resulte['nilai'],
                'total' => $total
            );
        }
        echo json_encode($result);
        $query1->free_result();
    }

    public function update_data()
    {

        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $skpd = $this->input->post('cskpd');
        $no = $this->input->post('cno');
        
        $no_potongan  = $this->input->post('cno_potongan');
        $nilaipot = $this->input->post('nilaipot');
        $lckolompot = $this->input->post('kolompot');
        $username      = $this->session->userdata('pcNama');
        $sql3 = $this->db->query("DELETE FROM bok_trdtrmpot WHERE no_bukti='$no_potongan' AND kd_skpd='$skpd'");
        // return;
        $sql1 = $this->db->query("DELETE FROM bok_trhtransout WHERE kd_skpd='$skpd' AND no_bukti='$no'");
        $sql2 = $this->db->query("DELETE FROM bok_trdtransout WHERE kd_skpd='$skpd' AND no_bukti='$no'");
        $sql3 = $this->db->query("DELETE FROM bok_trdtrmpot WHERE kd_skpd='$skpd' AND no_bukti='$no_potongan' AND kd_skpd='$skpd'");
        $sql4 = $this->db->query("DELETE FROM bok_trhtrmpot WHERE kd_skpd='$skpd' AND no_bukti='$no_potongan' AND kd_skpd='$skpd'");
        $sql2 = $this->db->query("DELETE FROM bok_trdtransout WHERE kd_skpd='$skpd' AND no_bukti='$no'");

        if ($sql1 && $sql2 && $sql3 && $sql4) {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            $this->db->insert('bok_trhtransout', array(
                'no_kas' => $this->input->post('cno'),
                'tgl_kas' => $this->input->post('ctgl'),
                'no_bukti' => $this->input->post('cno'),
                'tgl_bukti' => $this->input->post('ctgl'),
                'kd_skpd' => $this->input->post('cskpd'),
                'ket' => $this->input->post('cket'),
                'jns_spp' => $this->input->post('cjenis'),
                'total' => $this->input->post('total'),
                'no_kas_pot' => $this->input->post('cno_potongan'),
                'kd_skpd_sumber' => '1.02.0.00.0.00.01.0000',
                'username' => $username
            ));
            if (!empty($lckolompot)) {
                // Potongan
                $sql = "INSERT into bok_trdtrmpot $lckolompot $nilaipot";
                $asg = $this->db->query($sql);
                $this->db->insert('bok_trhtrmpot', array(
                    'no_bukti' => $this->input->post('cno_potongan'),
                    'tgl_bukti' => $this->input->post('ctgl'),
                    'kd_skpd' => $this->input->post('cskpd'),
                    'ket' => 'Terima Potongan',
                    'jns_spp' => $this->input->post('cjenis'),
                    'nilai' => $this->input->post('total_potongan'),
                    'no_kas' => $this->input->post('cno'),
                    'status' => 0,
                    'username' => $username
                ));
            }
            echo '1';
        } else {
            echo '0';
        }
    }

    public function map_pot()
    {
        $kriteria = $this->input->post('q');
        $where = '';
        if ($kriteria <> '') {
            $where = "WHERE (upper(nm_rek6) like upper('%$kriteria%')) ";
        }
        $sql = "SELECT * FROM ms_pot $where";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'map_pot' => $resulte['map_pot']
            );
        }
        echo json_encode($result);
        $query1->free_result();
    }

    public function hapus_data()
    {
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $data = $this->db->query("SELECT no_kas_pot FROM bok_trhtransout WHERE kd_skpd='$skpd' AND no_bukti='$nomor'");
        $hasil = $data->row();
        $no_pot = $hasil->no_kas_pot;
        // Proteksi sudah disetor atau blm
        $data1 = $this->db->query("SELECT [status] FROM bok_trhtrmpot WHERE kd_skpd='$skpd' AND no_bukti='$no_pot'");
        $hasil1 = $data1->row();
        $status = $hasil1->status;
        if ($status == '1') {
            echo '0';
            return;
        }
        // 
        $trd = $this->db->query("DELETE FROM bok_trdtrmpot WHERE kd_skpd='$skpd' AND no_bukti='$no_pot'");
        $trh = $this->db->query("DELETE FROM bok_trhtrmpot WHERE kd_skpd='$skpd' AND no_bukti='$no_pot'");
        if ($trd && $trh) {
            $this->db->query("DELETE FROM bok_trdtransout WHERE kd_skpd='$skpd' AND no_bukti='$nomor'");
            $this->db->query("DELETE FROM bok_trhtransout WHERE kd_skpd='$skpd' AND no_bukti='$nomor'");
        }
        echo '1';
    }
    
    
}
