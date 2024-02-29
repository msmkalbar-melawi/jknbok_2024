<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Penagihan extends CI_Controller
{


    public $ppkd1 = "4.02.02.01";
    public $ppkd2 = "4.02.02.02";

    function __contruct()
    {
        parent::__construct();
        // $this->load->model('cek_anggaran_model');

    }

    //START PENAGIHAN
    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }

    function Penagihan_skpd()
    {
        $data['page_title'] = 'INPUT PENAGIHAN';
        $this->template->set('title', 'INPUT PENAGIHAN');
        $this->template->load('template', 'tukd/penagihan/penagihan', $data);
    }

    function load_penagihan()
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
            $where = "AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or upper(nm_skpd) like 
                    upper('%$kriteria%') or upper(ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhtagih WHERE kd_skpd='$skpd' and jns_spp='6' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT TOP $rows * from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' $where AND no_bukti not in (SELECT TOP $offset no_bukti from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' $where order by no_bukti) order by no_bukti,kd_skpd ";
        $query1 = $this->db->query($sql);

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['ket'],
                'username' => $resulte['username'],
                'tgl_update' => $resulte['tgl_update'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'total' => $resulte['total'],
                'no_tagih' => $resulte['no_tagih'],
                'sts_tagih' => $resulte['sts_tagih'],
                'tgl_tagih' => $resulte['tgl_tagih'],
                'jns_beban' => $resulte['jns_spp'],
                'status'    => $resulte['status'],
                'jenis'    => $resulte['jenis'],
                'kontrak'    => $resulte['kontrak']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
        ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' and 
        tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd)";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_ang' => $resulte['jns_ang']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function cek_status_angkas()
    {
        $tgl_spp = $this->input->post('tgl_cek');
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT TOP 1 * from (
select '1'as urut,'murni' as status,murni as nilai from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '2'as urut,'murni_geser1',murni_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '3'as urut,'murni_geser2',murni_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '4'as urut,'murni_geser3',murni_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '5'as urut,'murni_geser4',murni_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '6'as urut,'murni_geser5',murni_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '7'as urut,'sempurna1',sempurna1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '8'as urut,'sempurna1_geser1',sempurna1_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '9'as urut,'sempurna1_geser2',sempurna1_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '10'as urut,'sempurna1_geser3',sempurna1_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '11'as urut,'sempurna1_geser4',sempurna1_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '12'as urut,'sempurna1_geser5',sempurna1_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '13'as urut,'sempurna2',sempurna2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '14'as urut,'sempurna2_geser1',sempurna2_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '15'as urut,'sempurna2_geser2',sempurna2_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '16'as urut,'sempurna2_geser3',sempurna2_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '17'as urut,'sempurna2_geser4',sempurna2_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '18'as urut,'sempurna2_geser5',sempurna2_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '19'as urut,'sempurna3',sempurna3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '20'as urut,'sempurna3_geser1',sempurna3_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '21'as urut,'sempurna3_geser2',sempurna3_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '22'as urut,'sempurna3_geser3',sempurna3_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '23'as urut,'sempurna3_geser4',sempurna3_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '24'as urut,'sempurna3_geser5',sempurna3_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '25'as urut,'sempurna4',sempurna4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '26'as urut,'sempurna4_geser1',sempurna4_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '27'as urut,'sempurna4_geser2',sempurna4_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '28'as urut,'sempurna4_geser3',sempurna4_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '29'as urut,'sempurna4_geser4',sempurna4_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '30'as urut,'sempurna4_geser5',sempurna4_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '31'as urut,'sempurna5',sempurna5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '32'as urut,'sempurna5_geser1',sempurna5_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '33'as urut,'sempurna5_geser2',sempurna5_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '34'as urut,'sempurna5_geser3',sempurna5_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '35'as urut,'sempurna5_geser4',sempurna5_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '36'as urut,'sempurna5_geser5',sempurna5_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '37'as urut,'ubah',ubah from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '38'as urut,'ubah1',ubah1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '39'as urut,'ubah2',ubah2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '40'as urut,'ubah3',ubah3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '41'as urut,'ubah4',ubah4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '42'as urut,'ubah5',ubah5 from status_angkas where kd_skpd ='$skpd'
)zz where nilai='1' ORDER BY cast(urut as int) DESC";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'status' => $resulte['status']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function cek_status_sumber()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT status_sumber FROM ms_skpd WHERE kd_skpd='$skpd'";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'st_sumber' => $resulte['status_sumber']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function cek_status_ang()
    {
        $tgl_spp = $this->input->post('tgl_cek');
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang,(case when b.jns_ang='M' then 'Penetapan'
        when b.jns_ang='P1' then 'Penyempurnaan I'
        when b.jns_ang ='P2' then 'Penyempurnaan II'
        when b.jns_ang ='P3' then 'Penyempurnaan III'
        when b.jns_ang ='P4' then 'Penyempurnaan IV'
        when b.jns_ang ='P5' then 'Penyempurnaan V'
        when b.jns_ang='U1' then 'Perubahan I'
        when b.jns_ang='U2' then 'Perubahan II' end)as nm_ang FROM ms_skpd a LEFT JOIN trhrka b
                    ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' and 
                    tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd and status='1')";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_ang' => $resulte['jns_ang'],
                'nm_ang' => $resulte['nm_ang']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function kontrak()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        //$sql = "SELECT no_kontrak,nilai from ms_kontrak WHERE kd_skpd='$kd_skpd'  order by no_kontrak";
        $sql = "SELECT no_kontrak,nilai,SUM (lalu) AS lalu FROM (
            SELECT a.no_kontrak AS no_kontrak,a.nilai AS nilai,SUM (b.total) AS lalu FROM ms_kontrak a LEFT JOIN trhtagih b ON b.kd_skpd =a.kd_skpd AND b.kontrak =a.no_kontrak WHERE a.kd_skpd ='$kd_skpd' AND (a.no_kontrak like '%$lccr%') GROUP BY a.no_kontrak,a.nilai,b.total) oke GROUP BY no_kontrak,nilai ORDER BY no_kontrak";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_kontrak' => $resulte['no_kontrak'],
                'nilai' => $resulte['nilai'],
                'lalu' => $resulte['lalu']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function cek_nlkontrak1()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kontrak = "";
        $kontrak = $this->input->post('kontrak');
        $tgl = $this->input->post('tgl');

        $sql = $this->db->query("SELECT isnull(sum(total),0) total from trhtagih where kontrak='$kontrak' and kd_skpd = '$kd_skpd'")->row();
        $hasil = $sql->total;
        echo $hasil;
    }



    function cek_nlkontrak()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kontrak = "";
        $kontrak = $this->input->post('kontrak');
        $tgl = $this->input->post('tgl');

        $sql = "SELECT isnull(sum(total),0) total from trhtagih where kontrak='$kontrak' and kd_skpd = '$kd_skpd'";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'nilai' => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function cek_nlkontrak2()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kontrak = "";
        $kontrak = $this->input->post('kontrak');
        $tgl = $this->input->post('tgl');

        $sql = $this->db->query("SELECT isnull(nilai,0)as nilai from ms_kontrak where no_kontrak='$kontrak' and kd_skpd='$kd_skpd'")->row();
        $hasil = $sql->nilai;
        echo $hasil;
    }

    function cek_kontrak()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kontrak = "";
        $kontrak = $this->input->post('kontrak');


        if ($kontrak == "") {
            $where = "";
        } else {
            $where = "and no_kontrak='$kontrak'";
        }

        $sql = $this->db->query("SELECT count(*)jum from ms_kontrak WHERE kd_skpd='$kd_skpd' $where ")->row();
        $hasil = $sql->jum;
        if ($hasil > 0) {
            echo '2';
        } else {
            echo '1';
        }
    }

    function load_total_kontrak()
    {
        $kode    = $this->input->post('kode');
        $kontrak = "";
        $kontrak = $this->input->post('kontrak');
        //$giat    = $this->input->post('giat');

        $sql = "SELECT
                        SUM (nilai) AS total_kontrak
                    FROM
                        ms_kontrak
                    WHERE
                        kd_skpd = '$kode'
                    AND no_kontrak = '$kontrak'
                    ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total_kontrak' => number_format($resulte['total_kontrak'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function kontrak1()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT TOP 5 kontrak FROM trhtagih WHERE LEN(kontrak)>1 AND kd_skpd = '$kd_skpd'   
                    AND UPPER(kontrak) LIKE UPPER('%$lccr%')
                    GROUP BY kontrak
                ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kontrak' => $resulte['kontrak'],
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtagih()
    {
        $nomor = $this->input->post('no');
        $kdskpd = $this->input->post('skpd');
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtagih c LEFT JOIN trhtagih d ON c.no_bukti=d.no_bukti WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek6=b.kd_rek AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_sub_kegiatan=b.kd_sub_kegiatan AND f.kd_rek6=b.kd_rek6) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = b.kd_sub_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek6=b.kd_rek) AS anggaran FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti and a.kd_skpd = b.kd_skpd  WHERE a.no_bukti='$nomor' and b.kd_skpd = '$kdskpd' ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_bukti'      => $resulte['no_bukti'],
                'no_sp2d'       => $resulte['no_sp2d'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek6'       => $resulte['kd_rek6'],
                'kd_rek'        => $resulte['kd_rek'],
                'nm_rek6'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai'],
                'sumber'         => $resulte['sumber'],
                'lalu'          => $resulte['lalu'],
                'sp2d'          => $resulte['sp2d'],
                'anggaran'      => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_trskpd()
    {
        $jenis = $this->input->post('jenis');
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');

        $jns_beban = '';
        $cgiat = '';
        if ($jenis == 4) {
            $jns_beban = "and b.jns_kegiatan='5'";
        } else {
            $jns_beban = "and b.jns_kegiatan='5'";
        }
        if ($giat != '') {
            $cgiat = " and a.kd_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,(select nm_program from ms_program where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan1=b.kd_sub_kegiatan
                WHERE a.kd_skpd='$cskpd' AND a.status_keg='1' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'total'       => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function load_trskpd_giat()
    {
        $jenis      = $this->input->post('jenis');
        $giat       = $this->input->post('giat');
        $cskpd      = $this->input->post('kd');
        $jns_beban  = '';
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($cskpd);
        $cgiat      = '';
        if ($jenis == 4) {
            $jns_beban = "and b.jns_sub_kegiatan='5'";
        } else {
            $jns_beban = "and b.jns_sub_kegiatan='5'";
        }
        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');


        $sql = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,(select nm_program from ms_program where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                WHERE a.jns_ang='$jns_ang' AND a.kd_skpd='$cskpd' AND a.status_sub_kegiatan='1' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_program'        => $resulte['kd_program'],
                'nm_program'        => $resulte['nm_program'],
                'total'             => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_spd()
    {
        $kode    = $this->input->post('ckode');
        $giat    = $this->input->post('cgiat');
        $kd_rek    = $this->input->post('ckdrek6');

        $sql = "SELECT
                        SUM (a.nilai) AS total_spd
                    FROM
                        trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                        left(b.kd_skpd,22) = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$kd_rek'
                    AND b.status = '1'";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total_spd' => number_format($resulte['total_spd'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek_penagihan()
    {
        $kode   = $this->input->post('kd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);

        // echo($data);

        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        //$sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');

        if ($rek != '') {
            $notIn = " and a.kd_rek6 not in ($rek) ";
        } else {
            $notIn  = "";
        }
        $sql = "SELECT a.kd_rek6,a.nm_rek6,e.map_lo,
                      (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                            c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND d.kd_skpd = a.kd_skpd
                        AND c.kd_rek6 = a.kd_rek6
                        AND d.jns_spp='1'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                            x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND x.kd_skpd = a.kd_skpd
                        AND x.kd_rek6 = a.kd_rek6
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                    0 AS sp2d,a.nilai AS anggaran
                      FROM trdrka a LEFT JOIN ms_rek6 e ON a.kd_rek6=e.kd_rek6  WHERE a.kd_sub_kegiatan= '$giat' AND a.jns_ang='$data' AND a.kd_skpd = '$kode' 
                     $notIn ";
        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'        => $ii,
                'kd_rek6'   => $resulte['map_lo'],
                'kd_rek'    => $resulte['kd_rek6'],
                'nm_rek6'   => $resulte['nm_rek6'],
                'lalu'      => $resulte['lalu'],
                'sp2d'      => $resulte['sp2d'],
                'anggaran'  => $resulte['anggaran'],
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_trans_tagih()
    {
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');


        $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(tagih,0) from trskpd a left join
        (           
            select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
            where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
            and (sp2d_batal<>'1' or sp2d_batal is null ) 
            group by c.kd_sub_kegiatan
        ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
        left join 
        (
            SELECT z.kd_sub_kegiatan, SUM(z.transaksi) as transaksi FROM (
            select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
            from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
            where f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_sub_kegiatan
            UNION ALL
            SELECT c.kd_sub_kegiatan, SUM(c.nilai) as transaksi FROM  trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd
            WHERE jns_spp IN ('1') 
            AND c.kd_sub_kegiatan = '$kegiatan' 
            AND d.status_validasi='0'
            group by c.kd_sub_kegiatan) z
            group by z.kd_sub_kegiatan
        ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
        left join
        (
            select i.kd_sub_kegiatan,sum(i.nilai) [tagih] from trhtagih h join trdtagih i 
            on h.no_bukti=i.no_bukti and h.kd_skpd=i.kd_skpd
            where i.kd_sub_kegiatan='$kegiatan' and h.no_bukti<>'$no_bukti' 
            AND h.no_bukti NOT IN (select no_tagih FROM trhspp j INNER JOIN trdspp k ON j.no_spp=k.no_spp AND j.kd_skpd=k.kd_skpd
            where k.kd_sub_kegiatan='$kegiatan' and j.no_tagih<>'$no_bukti') group by i.kd_sub_kegiatan
        )l on a.kd_sub_kegiatan=l.kd_sub_kegiatan
        where a.kd_sub_kegiatan='$kegiatan'";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function load_sisa_sumberdana()
    {
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kode');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');
        $sql    = " SELECT 
                    ISNULL(pad_murni,0) pad_murni,
                    ISNULL(pad_semp,0) pad_semp,
                    ISNULL(pad_ubah,0) pad_ubah,
                    ISNULL(dak_murni,0) dak_murni,
                    ISNULL(dak_semp,0) dak_semp,
                    ISNULL(dak_ubah,0) dak_ubah,
                    ISNULL(daknf_murni,0) daknf_murni,
                    ISNULL(daknf_semp,0) daknf_semp,
                    ISNULL(daknf_ubah,0) daknf_ubah,
                    ISNULL(dau_murni,0) dau_murni,
                    ISNULL(dau_semp,0) dau_semp,
                    ISNULL(dau_ubah,0) dau_ubah,
                    ISNULL(dbhp_murni,0) dbhp_murni,
                    ISNULL(dbhp_semp,0) dbhp_semp,
                    ISNULL(dbhp_ubah,0) dbhp_ubah,
                    ISNULL(did_murni,0) did_murni,
                    ISNULL(did_semp,0) did_semp,
                    ISNULL(did_ubah,0) did_ubah,
                    ISNULL(hpp_murni,0) hpp_murni,
                    ISNULL(hpp_semp,0) hpp_semp,
                    ISNULL(hpp_ubah,0) hpp_ubah,
                    ISNULL(nil_pad,0) nil_pad,
                    ISNULL(nil_dak,0) nil_dak,
                    ISNULL(nil_daknf,0) nil_daknf,                    
                    ISNULL(nil_dau,0) nil_dau,
                    ISNULL(nil_dbhp,0) nil_dbhp,
                    ISNULL(nil_did,0) nil_did,
                    ISNULL(nil_hpp,0) nil_hpp
                    FROM (
                    select '1' nomor,
                    sum(pad_murni) [pad_murni],sum(dak_murni) [dak_murni],sum(daknf_murni) [daknf_murni],sum(dau_murni) [dau_murni],
                    sum(dbhp_murni) [dbhp_murni],sum(did_murni) [did_murni],sum(hpp_murni) [hpp_murni],
                    sum(pad_semp) [pad_semp],sum(dak_semp) [dak_semp],sum(daknf_semp) [daknf_semp],sum(dau_semp) [dau_semp],
                    sum(dbhp_semp) [dbhp_semp],sum(did_semp) [did_semp],sum(hpp_semp) [hpp_semp],
                    sum(pad_ubah) [pad_ubah],sum(dak_ubah) [dak_ubah],sum(daknf_ubah) [daknf_ubah],sum(dau_ubah) [dau_ubah],
                    sum(dbhp_ubah) [dbhp_ubah],sum(did_ubah) [did_ubah],sum(hpp_ubah) [hpp_ubah]
                     from(
                    select 
                    SUM (CASE WHEN sumber = 'PAD' THEN nilai_sumber ELSE 0 END) AS pad_murni,
                    SUM (CASE WHEN sumber = 'DAK FISIK' THEN nilai_sumber ELSE 0 END) AS dak_murni,
                    SUM (CASE WHEN sumber = 'DAK NON FISIK' THEN nilai_sumber ELSE 0 END) AS daknf_murni,
                    SUM (CASE WHEN sumber = 'DAU' THEN nilai_sumber ELSE 0 END) AS dau_murni,
                    SUM (CASE WHEN sumber = 'DBHP' THEN nilai_sumber ELSE 0 END) AS dbhp_murni,
                    SUM (CASE WHEN sumber = 'DID' THEN nilai_sumber ELSE 0 END) AS did_murni,
                    SUM (CASE WHEN sumber = 'HPP' THEN nilai_sumber ELSE 0 END) AS hpp_murni,
                    SUM (CASE WHEN sumber1_su = 'PAD' THEN nsumber1_su ELSE 0 END) AS pad_semp,
                    SUM (CASE WHEN sumber1_su = 'DAK FISIK' THEN nsumber1_su ELSE 0 END) AS dak_semp,
                    SUM (CASE WHEN sumber1_su = 'DAK NON FISIK' THEN nsumber1_su ELSE 0 END) AS daknf_semp,
                    SUM (CASE WHEN sumber1_su = 'DAU' THEN nsumber1_su ELSE 0 END) AS dau_semp,
                    SUM (CASE WHEN sumber1_su = 'DBHP' THEN nsumber1_su ELSE 0 END) AS dbhp_semp,
                    SUM (CASE WHEN sumber1_su = 'DID' THEN nsumber1_su ELSE 0 END) AS did_semp,
                    SUM (CASE WHEN sumber1_su = 'HPP' THEN nsumber1_su ELSE 0 END) AS hpp_semp,
                    SUM (CASE WHEN sumber1_ubah = 'PAD' THEN nsumber1_ubah ELSE 0 END) AS pad_ubah,
                    SUM (CASE WHEN sumber1_ubah = 'DAK FISIK' THEN nsumber1_ubah ELSE 0 END) AS dak_ubah,
                    SUM (CASE WHEN sumber1_ubah = 'DAK NON FISIK' THEN nsumber1_ubah ELSE 0 END) AS daknf_ubah,
                    SUM (CASE WHEN sumber1_ubah = 'DAU' THEN nsumber1_ubah ELSE 0 END) AS dau_ubah,
                    SUM (CASE WHEN sumber1_ubah = 'DBHP' THEN nsumber1_ubah ELSE 0 END) AS dbhp_ubah,
                    SUM (CASE WHEN sumber1_ubah = 'DID' THEN nsumber1_ubah ELSE 0 END) AS did_ubah,
                    SUM (CASE WHEN sumber1_ubah = 'HPP' THEN nsumber1_ubah ELSE 0 END) AS hpp_ubah
                    FROM trdrka 
                    WHERE kd_skpd='$kode'AND kd_kegiatan='$giat' AND kd_rek5='$rek'
                    UNION ALL
                    select 
                    SUM (CASE WHEN sumber2 = 'PAD' THEN nilai_sumber2 ELSE 0 END) AS pad_murni,
                    SUM (CASE WHEN sumber2 = 'DAK FISIK' THEN nilai_sumber2 ELSE 0 END) AS dak_murni,
                    SUM (CASE WHEN sumber2 = 'DAK NON FISIK' THEN nilai_sumber2 ELSE 0 END) AS daknf_murni,
                    SUM (CASE WHEN sumber2 = 'DAU' THEN nilai_sumber2 ELSE 0 END) AS dau_murni,
                    SUM (CASE WHEN sumber2 = 'DBHP' THEN nilai_sumber2 ELSE 0 END) AS dbhp_murni,
                    SUM (CASE WHEN sumber2 = 'DID' THEN nilai_sumber2 ELSE 0 END) AS did_murni,
                    SUM (CASE WHEN sumber2 = 'HPP' THEN nilai_sumber2 ELSE 0 END) AS hpp_murni,
                    SUM (CASE WHEN sumber2_su = 'PAD' THEN nsumber2_su ELSE 0 END) AS pad_semp,
                    SUM (CASE WHEN sumber2_su = 'DAK FISIK' THEN nsumber2_su ELSE 0 END) AS dak_semp,
                    SUM (CASE WHEN sumber2_su = 'DAK NON FISIK' THEN nsumber2_su ELSE 0 END) AS daknf_semp,
                    SUM (CASE WHEN sumber2_su = 'DAU' THEN nsumber2_su ELSE 0 END) AS dau_semp,
                    SUM (CASE WHEN sumber2_su = 'DBHP' THEN nsumber2_su ELSE 0 END) AS dbhp_semp,
                    SUM (CASE WHEN sumber2_su = 'DID' THEN nsumber2_su ELSE 0 END) AS did_semp,
                    SUM (CASE WHEN sumber2_su = 'HPP' THEN nsumber2_su ELSE 0 END) AS hpp_semp,                 
                    SUM (CASE WHEN sumber2_ubah = 'PAD' THEN nsumber2_ubah ELSE 0 END) AS pad_ubah,
                    SUM (CASE WHEN sumber2_ubah = 'DAK FISIK' THEN nsumber2_ubah ELSE 0 END) AS dak_ubah,
                    SUM (CASE WHEN sumber2_ubah = 'DAK NON FISIK' THEN nsumber2_ubah ELSE 0 END) AS daknf_ubah,
                    SUM (CASE WHEN sumber2_ubah = 'DAU' THEN nsumber2_ubah ELSE 0 END) AS dau_ubah,
                    SUM (CASE WHEN sumber2_ubah = 'DBHP' THEN nsumber2_ubah ELSE 0 END) AS dbhp_ubah,
                    SUM (CASE WHEN sumber2_ubah = 'DID' THEN nsumber2_ubah ELSE 0 END) AS did_ubah,
                    SUM (CASE WHEN sumber2_ubah = 'HPP' THEN nsumber2_ubah ELSE 0 END) AS hpp_ubah
                    FROM trdrka 
                    WHERE kd_skpd='$kode'AND kd_kegiatan='$giat' AND kd_rek5='$rek'
                    UNION ALL
                    select 
                    SUM (CASE WHEN sumber3 = 'PAD' THEN nilai_sumber3 ELSE 0 END) AS pad_murni,
                    SUM (CASE WHEN sumber3 = 'DAK FISIK' THEN nilai_sumber3 ELSE 0 END) AS dak_murni,
                    SUM (CASE WHEN sumber3 = 'DAK NON FISIK' THEN nilai_sumber3 ELSE 0 END) AS daknf_murni,
                    SUM (CASE WHEN sumber3 = 'DAU' THEN nilai_sumber3 ELSE 0 END) AS dau_murni,
                    SUM (CASE WHEN sumber3 = 'DBHP' THEN nilai_sumber3 ELSE 0 END) AS dbhp_murni,
                    SUM (CASE WHEN sumber3 = 'DID' THEN nilai_sumber3 ELSE 0 END) AS did_murni,             
                    SUM (CASE WHEN sumber3 = 'HPP' THEN nilai_sumber3 ELSE 0 END) AS hpp_murni,
                    SUM (CASE WHEN sumber3_su = 'PAD' THEN nsumber3_su ELSE 0 END) AS pad_semp,
                    SUM (CASE WHEN sumber3_su = 'DAK FISIK' THEN nsumber3_su ELSE 0 END) AS dak_semp,
                    SUM (CASE WHEN sumber3_su = 'DAK NON FISIK' THEN nsumber3_su ELSE 0 END) AS daknf_semp,
                    SUM (CASE WHEN sumber3_su = 'DAU' THEN nsumber3_su ELSE 0 END) AS dau_semp,
                    SUM (CASE WHEN sumber3_su = 'DBHP' THEN nsumber3_su ELSE 0 END) AS dbhp_semp,
                    SUM (CASE WHEN sumber3_su = 'DID' THEN nsumber3_su ELSE 0 END) AS did_semp,
                    SUM (CASE WHEN sumber3_su = 'HPP' THEN nsumber3_su ELSE 0 END) AS hpp_semp, 
                    SUM (CASE WHEN sumber3_ubah = 'PAD' THEN nsumber3_ubah ELSE 0 END) AS pad_ubah,
                    SUM (CASE WHEN sumber3_ubah = 'DAK FISIK' THEN nsumber3_ubah ELSE 0 END) AS dak_ubah,
                    SUM (CASE WHEN sumber3_ubah = 'DAK NON FISIK' THEN nsumber3_ubah ELSE 0 END) AS daknf_ubah,
                    SUM (CASE WHEN sumber3_ubah = 'DAU' THEN nsumber3_ubah ELSE 0 END) AS dau_ubah,
                    SUM (CASE WHEN sumber3_ubah = 'DBHP' THEN nsumber3_ubah ELSE 0 END) AS dbhp_ubah,
                    SUM (CASE WHEN sumber3_ubah = 'DID' THEN nsumber3_ubah ELSE 0 END) AS did_ubah,
                    SUM (CASE WHEN sumber3_ubah = 'HPP' THEN nsumber3_ubah ELSE 0 END) AS hpp_ubah
                    FROM trdrka 
                    WHERE kd_skpd='$kode'AND kd_kegiatan='$giat' AND kd_rek5='$rek'
                    UNION ALL
                    select 
                    SUM (CASE WHEN sumber4 = 'PAD' THEN nilai_sumber4 ELSE 0 END) AS pad_murni,
                    SUM (CASE WHEN sumber4 = 'DAK FISIK' THEN nilai_sumber4 ELSE 0 END) AS dak_murni,
                    SUM (CASE WHEN sumber4 = 'DAK NON FISIK' THEN nilai_sumber4 ELSE 0 END) AS daknf_murni,
                    SUM (CASE WHEN sumber4 = 'DAU' THEN nilai_sumber4 ELSE 0 END) AS dau_murni,
                    SUM (CASE WHEN sumber4 = 'DBHP' THEN nilai_sumber4 ELSE 0 END) AS dbhp_murni,
                    SUM (CASE WHEN sumber4 = 'DID' THEN nilai_sumber4 ELSE 0 END) AS did_murni,
                    SUM (CASE WHEN sumber4 = 'HPP' THEN nilai_sumber4 ELSE 0 END) AS hpp_murni,
                    SUM (CASE WHEN sumber4_su = 'PAD' THEN nsumber4_su ELSE 0 END) AS pad_semp,
                    SUM (CASE WHEN sumber4_su = 'DAK FISIK' THEN nsumber4_su ELSE 0 END) AS dak_semp,
                    SUM (CASE WHEN sumber4_su = 'DAK NON FISIK' THEN nsumber4_su ELSE 0 END) AS daknf_semp,
                    SUM (CASE WHEN sumber4_su = 'DAU' THEN nsumber4_su ELSE 0 END) AS dau_semp,
                    SUM (CASE WHEN sumber4_su = 'DBHP' THEN nsumber4_su ELSE 0 END) AS dbhp_semp,
                    SUM (CASE WHEN sumber4_su = 'DID' THEN nsumber4_su ELSE 0 END) AS did_semp,
                    SUM (CASE WHEN sumber4_su = 'HPP' THEN nsumber4_su ELSE 0 END) AS hpp_semp, 
                    SUM (CASE WHEN sumber4_ubah = 'PAD' THEN nsumber4_ubah ELSE 0 END) AS pad_ubah,
                    SUM (CASE WHEN sumber4_ubah = 'DAK FISIK' THEN nsumber4_ubah ELSE 0 END) AS dak_ubah,
                    SUM (CASE WHEN sumber4_ubah = 'DAK NON FISIK' THEN nsumber4_ubah ELSE 0 END) AS daknf_ubah,
                    SUM (CASE WHEN sumber4_ubah = 'DAU' THEN nsumber4_ubah ELSE 0 END) AS dau_ubah,
                    SUM (CASE WHEN sumber4_ubah = 'DBHP' THEN nsumber4_ubah ELSE 0 END) AS dbhp_ubah,
                    SUM (CASE WHEN sumber4_ubah = 'DID' THEN nsumber4_ubah ELSE 0 END) AS did_ubah,
                    SUM (CASE WHEN sumber4_ubah = 'HPP' THEN nsumber4_ubah ELSE 0 END) AS hpp_ubah
                    FROM trdrka 
                    WHERE kd_skpd='$kode'AND kd_kegiatan='$giat' AND kd_rek5='$rek') a
                    ) a

                    LEFT JOIN 
                    (
                    SELECT '1' as nomor, SUM(nil_pad) nil_pad, SUM(nil_dak) nil_dak,SUM(nil_daknf) nil_daknf, SUM(nil_dau) nil_dau, 
                    SUM(nil_dbhp) nil_dbhp,SUM(nil_did) nil_did,SUM(nil_hpp) nil_hpp
                    FROM (
                    SELECT
                    SUM (c.nil_pad) as nil_pad,
                    SUM (c.nil_dak) as nil_dak,
                    SUM (c.nil_daknf) as nil_daknf,
                    SUM (c.nil_dau) as nil_dau,
                    SUM (c.nil_dbhp) as nil_dbhp,
                    SUM (c.nil_did) as nil_did,
                    SUM (c.nil_hpp) as nil_hpp
                    FROM
                    trdtransout c
                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                    AND c.kd_skpd = d.kd_skpd
                    WHERE
                    c.kd_kegiatan = '$giat'
                    AND d.kd_skpd = '$kode'
                    AND c.kd_rek5 = '$rek'
                    AND d.jns_spp='1'
                    UNION ALL
                    SELECT 
                    SUM(x.nil_pad) as nil_pad, 
                    SUM(x.nil_dak) as nil_dak, 
                    SUM(x.nil_daknf) as nil_daknf, 
                    SUM(x.nil_dau) as nil_dau, 
                    SUM(x.nil_dbhp) as nil_dbhp, 
                    SUM(x.nil_did) as nil_did,
                    SUM(x.nil_hpp) as nil_hpp 
                    FROM trdspp x
                    INNER JOIN trhspp y 
                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                    WHERE
                    x.kd_kegiatan = '$giat'
                    AND x.kd_skpd = '$kode'
                    AND x.kd_rek5 = '$rek'
                    AND y.jns_spp IN ('3','4','5','6')
                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                    UNION ALL
                    SELECT 
                    SUM(nil_pad) as nil_pad, 
                    SUM(nil_dak) as nil_dak, 
                    SUM(nil_daknf) as nil_daknf, 
                    SUM(nil_dau) as nil_dau, 
                    SUM(nil_dbhp) as nil_dbhp, 
                    SUM(nil_did) as nil_did,
                    SUM(nil_hpp) as nil_hpp 
                    FROM trdtagih t 
                    INNER JOIN trhtagih u 
                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                    WHERE 
                    t.kd_kegiatan = '$giat'
                    AND u.kd_skpd = '$kode'
                    AND t.kd_rek = '$rek'
                    AND u.no_bukti 
                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode')
                    )a) b
                    ON a.nomor=b.nomor
                    ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'pad_murni' => $resulte['pad_murni'],
                'dak_murni' => $resulte['dak_murni'],
                'daknf_murni' => $resulte['daknf_murni'],
                'dau_murni' => $resulte['dau_murni'],
                'dbhp_murni' => $resulte['dbhp_murni'],
                'did_murni' => $resulte['did_murni'],
                'hpp_murni' => $resulte['hpp_murni'],
                'pad_semp' => $resulte['pad_semp'],
                'dak_semp' => $resulte['dak_semp'],
                'daknf_semp' => $resulte['daknf_semp'],
                'dau_semp' => $resulte['dau_semp'],
                'dbhp_semp' => $resulte['dbhp_semp'],
                'did_semp' => $resulte['did_semp'],
                'hpp_semp' => $resulte['hpp_semp'],
                'pad_ubah' => $resulte['pad_ubah'],
                'dak_ubah' => $resulte['dak_ubah'],
                'daknf_ubah' => $resulte['daknf_ubah'],
                'dau_ubah' => $resulte['dau_ubah'],
                'dbhp_ubah' => $resulte['dbhp_ubah'],
                'did_ubah' => $resulte['did_ubah'],
                'hpp_ubah' => $resulte['hpp_ubah'],
                'nil_pad' => $resulte['nil_pad'],
                'nil_dak' => $resulte['nil_dak'],
                'nil_daknf' => $resulte['nil_daknf'],
                'nil_dau' => $resulte['nil_dau'],
                'nil_dbhp' => $resulte['nil_dbhp'],
                'nil_did' => $resulte['nil_did'],
                'nil_hpp' => $resulte['nil_hpp'],
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }


    //END PENAGIHAN


    //PENAGIHAN KONSEP KOTA

    function penagihanskpd()
    {
        $data['page_title'] = 'INPUT PENAGIHAN';
        $this->template->set('title', 'INPUT PENAGIHAN');
        $this->template->load('template', 'tukd/penagihan/penagihan', $data);
    }

    function load_penagihanskpd()
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
            $where = "AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or upper(nm_skpd) like 
                    upper('%$kriteria%') or upper(ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhtagih WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT TOP $rows * from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where AND no_bukti not in (SELECT TOP $offset no_bukti from trhtagih  WHERE kd_skpd='$skpd' and jns_spp='6' and jns_trs='1' $where order by no_bukti) order by no_bukti,kd_skpd ";
        $query1 = $this->db->query($sql);

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['ket'],
                'ket_bast' => $resulte['ket_bast'],
                'username' => $resulte['username'],
                'tgl_update' => $resulte['tgl_update'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'total' => $resulte['total'],
                'no_tagih' => $resulte['no_tagih'],
                'sts_tagih' => $resulte['sts_tagih'],
                'tgl_tagih' => $resulte['tgl_tagih'],
                'jns_beban' => $resulte['jns_spp'],
                'status'    => $resulte['status'],
                'jenis'    => $resulte['jenis'],
                'kontrak'    => $resulte['kontrak']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }


    function load_total_trans()
    {
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $rek         = $this->input->post('rek');
        $no_bukti    = $this->input->post('no_simpan');
        $beban       = $this->input->post('beban');

        if ($beban == "3") {
            $sql = "SELECT SUM(nilai) total FROM 
                                    (
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND (left(d.kd_skpd,22) = '$kdskpd' or d.kd_skpd='$kdskpd')
                                    AND d.jns_spp in ('1') 

                                    UNION ALL

                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'AND left(d.kd_skpd,22) = '$kdskpd'
                                    AND d.jns_spp in ('1') AND d.status_validasi='0' 

                                    UNION ALL

                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y 
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = '$kegiatan'
                                    AND (--left(x.kd_skpd,22) = '$kdskpd' or 
                                    x.kd_skpd='$kdskpd')
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
                                    
                                    UNION ALL

                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND (--u.kd_skpd = '$kdskpd' or 
                                    u.kd_skpd='$kdskpd'
                                    ) AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE (--kd_skpd='$kdskpd' or 
                                    kd_skpd='$kdskpd'))
                                    )r";
        } else {
            $sql = "SELECT SUM(nilai) total FROM 
                                    (
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND (
                                        left(d.kd_skpd,17) = left('$kdskpd',17) or left(d.kd_skpd,17)=left('$kdskpd',17)
                                    )
                                    AND d.jns_spp in ('1') 
                                    AND c.kd_rek6 = '$rek'

                                    UNION ALL

                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'AND left(d.kd_skpd,17) = left('$kdskpd',17)
                                    AND d.jns_spp in ('1') AND d.status_validasi='0' 
                                    AND c.kd_rek6 = '$rek'

                                    UNION ALL

                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y 
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = '$kegiatan'
                                    AND (--left(x.kd_skpd,22) = '$kdskpd' or 
                                    left(x.kd_skpd,17)=left('$kdskpd',17)
                                    )
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
                                    AND x.kd_rek6 = '$rek'

                                    UNION ALL

                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND (--u.kd_skpd = '$kdskpd' or 
                                    left(u.kd_skpd,17)=left('$kdskpd',17)
                                    ) AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE (--kd_skpd='$kdskpd' or 
                                    left(kd_skpd,17)=left('$kdskpd',17)))
                                    AND t.kd_rek6 = '$rek'
                                    )r";
        }
        // echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_reksumber_dana()
    {
        $kode   = $this->input->post('kd');
        $jnsang   = $this->cek_anggaran_model->cek_anggaran($kode);

        $giat   = $this->input->post('giat');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');

        $sql = "SELECT sumber as sumber_dana,nm_sumber as nm_sumber_dana,sum(total) as nilai , (SELECT ISNULL(SUM(nilai),0) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
       u.kd_skpd = '$kode' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' ) and sumber=sumber)as lalu from trdpo where kd_sub_kegiatan = '$giat' and kd_rek6 = '$rek' and kd_skpd = '$kode' and jns_ang = '$jnsang' GROUP BY sumber, nm_sumber";

        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'sumber_dana'       => $resulte['sumber_dana'],
                'nilaidana'         => $resulte['nilai'],
                'nilaidana_lalu'    => $resulte['lalu']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');
        if ($field2 == '') {
            $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else {
            $hasil = $this->db->query(" select count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor' ");
        }
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

    function simpan_penagihan_ar()
    {
        $skpd  = $this->session->userdata('kdskpd');
        $proses = $this->input->post('proses');
        $sta_byr      = $this->input->post('status_byr');
        if ($proses == 'header') {

            $tabel        = $this->input->post('tabel');
            $lckolom      = $this->input->post('kolom');
            $lcnilai      = $this->input->post('nilai');
            $cid          = $this->input->post('cid');
            $sta_byr      = $this->input->post('status_byr');
            $lcid         = $this->input->post('lcid');


            $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd'";
            $res = $this->db->query($sql);
            if ($res->num_rows() > 0) {
                echo '1';
                exit();
            } else {

                $sql    = "insert into $tabel $lckolom values $lcnilai";
                $asg    = $this->db->query($sql);
                if ($asg) {
                    echo '2';
                } else {
                    echo '0';
                    exit();
                }
            }
        }


        if ($proses == 'detail') {
            $tabel_detail = $this->input->post('tabel_detail');
            $no_detail    = $this->input->post('no_detail');
            $sql_detail   = $this->input->post('sql_detail');
            $sta_byr      = $this->input->post('status_byr');
            $kd_kegiatan  = '';
            $nm_kegiatan  = '';
            $kdp          = '';
            $rek3         = '';
            $kdrek3       = '';
            $kd_aset      = '';
            $nm_aset      = '';
            $sql        = " INSERT into trdtagih(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,kd_rek,nm_rek6,nilai,kd_skpd,sumber) ";
            $asg_detail = $this->db->query($sql . $sql_detail);

            if ($asg_detail) {
                echo '4';
            } else {
                echo '5';
            }
        }
    }

    function load_tot_tagih()
    {
        $skpd = $this->session->userdata('kdskpd');
        $no = $this->input->post('no_tagih');
        $query1 = $this->db->query("SELECT sum(nilai) as rektagih from trhtagih a INNER join trdtagih b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                                where a.no_bukti='$no' AND a.kd_skpd='$skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['rektagih'], 2, '.', ',')
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function update_penagihan_header_ar()
    {
        $skpd  = $this->session->userdata('kdskpd');
        $tabel   = $this->input->post('tabel');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');

        if ($lcid <> $lcid_h) {
            $sql     = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd'";
            $res     = $this->db->query($sql);
            if ($res->num_rows() > 0) {
                echo '1';
                exit();
            }
        }

        $sqlcek = "select count(no_tagih) as total from trhspp where no_tagih ='$lcid'";
        $querycek = $this->db->query($sqlcek);
        $total = $querycek->row();
        $msg = array();
        if ($total->total == '0') {
            $query     = $this->input->post('st_query');
            $asg       = $this->db->query($query);

            if ($asg > 0) {
                echo '2';
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }

    function update_penagihan_detail_ar()
    {
        $skpd  = $this->session->userdata('kdskpd');
        $nomor   = trim($this->input->post('nomor'));
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');

        $sqlcek = "select count(no_tagih) as total from trhspp where no_tagih ='$nomor'";
        $querycek = $this->db->query($sqlcek);
        $total = $querycek->row();
        $msg = array();
        if ($total->total == '0') {

            $sql     = " delete from trdtagih where no_bukti='$nomor' AND kd_skpd='$skpd' ";
            $asg     = $this->db->query($sql);

            if ($asg > 0) {

                $tabel_detail = $this->input->post('tabel_detail');
                $no_detail    = $this->input->post('no_detail');
                $sql_detail   = $this->input->post('sql_detail');
                $sql          = " insert into trdtagih(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,kd_rek,nm_rek6,nilai,kd_skpd,sumber)";
                $asg_detail   = $this->db->query($sql . $sql_detail);

                echo '1';
                exit();
            }
        } else {
            echo '0';
            exit();
        }
    }

    //PENAGIHAN KONSEP KOTA

    //--------------------------------------------------------
    // PROV
    function total_angkas()
    {

        $ckdkegi  = $this->input->post('kegiatan');
        $ckdskpd  = $this->input->post('kd_skpd');
        $tglang  = $this->input->post('tglang');
        $kd_rek6   = $this->input->post('kdrek6');
        $sts_angkas = $this->input->post('sts_angkas');
        $ckdskpd1  = substr($ckdskpd, 0, 17);
        $statusang   = $this->input->post('statusang');

        $jns_ang = $this->cek_anggaran_model->cek_anggaran($ckdskpd);


        $artgl = explode("-", $tglang);
        $bulan = $artgl[1];

        if ($sts_angkas == 'murni') {
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
        } else if ($sts_angkas == 'sempurna1') {
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
        } else if ($sts_angkas == 'ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }


        $query1   = $this->db->query("SELECT a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            where a.kd_skpd = '$ckdskpd' and b.jns_ang='$jns_ang' and a.kd_sub_kegiatan = '$ckdkegi' and a.kd_rek6='$kd_rek6' and bulan <= '$bulan' GROUP BY a.kd_sub_kegiatan,a.kd_rek6");


        $result   = array();
        $ii       = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'             => $ii,
                'nilai'          => number_format($resulte['nilai'], 2, '.', ','),
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
}
