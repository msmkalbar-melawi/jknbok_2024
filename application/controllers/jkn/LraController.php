<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class LraController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
        
    }
    public function index()
    {
        $data['anggaran'] = $this->db->query("SELECT 
            a.jns_ang AS kode,
            (SELECT b.nama FROM tb_status_anggaran AS b WHERE b.kode = a.jns_ang ) AS nama
            FROM bok_trdrka AS a GROUP BY jns_ang
            ORDER BY kode
        ")->result();

        $data['page_title'] = 'LRA JKN BOK';
        $this->template->set('title', 'LRA JKN BOK');
        $this->template->load('template', 'jkn/lra/index', $data);
    }
    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd FROM ms_skpd_jkn a WHERE a.kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);
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
    public function ttd()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('JKNBOK-KPA','JKNBOK-PA')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'id_ttd' => $resulte['id'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }

    public function laporan()
    {
        $this->load->model('laporan','lra');
        $kd_skpd = $this->session->userdata('kdskpd');
        $tahun  = $this->session->userdata('pcThang');
        $print = $_GET['ctk'];
        $periode1 = $_GET['periode1'];
        $periode2 = $_GET['periode2'];
        $tgl_ttd = $_GET['tgl_ttd'];
        $ttd = $_GET['ttd'];
        $jenis = $_GET['jenis'];
        $dataisian = true;
        $datattd = $this->db->query("SELECT * FROM ms_ttd WHERE kd_skpd='$kd_skpd' AND id='$ttd'")->row();
        $anggaran = $_GET['anggaran'];
        // echo ($periode2);


        if ($jenis == 'jkn') {
            $judul = 'KAPITASI JKN';
            $dataisian = $this->lra
                ->skpd($kd_skpd)
                ->periode([$periode1,$periode2])
                ->jenisAnggaran($anggaran)
                ->rekening(6)
                ->union()
                ->rekening(5)
                ->union()
                ->rekening(4)
                ->union()
                ->rekening(3)
                ->union()
                ->rekening(2)
                ->get();
            $nm_skpd = $this->db->query("SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd='$kd_skpd'")->row();
        } else if ($jenis == 'bok') {
            $judul = 'BOK';
            $dataisian = $this->lra
                ->type('bok')
                ->skpd($kd_skpd)
                ->periode([$periode1,$periode2])
                ->jenisAnggaran($anggaran)
                ->rekening(6)
                ->union()
                ->rekening(5)
                ->union()
                ->rekening(4)
                ->union()
                ->rekening(3)
                ->union()
                ->rekening(2)
                ->get();
            $nm_skpd = $this->db->query("SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd='$kd_skpd'")->row();
        }

        $cRet = '';

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr>
            <td align=\"center\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td>
            </tr>
            <tr>
            <td align=\"center\" style=\"font-size:14px\" width=\"93%\">&nbsp;&nbsp;&nbsp;&nbsp;<strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
            <tr>
            <td align=\"center\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>PERIODE ".strtoupper($this->tukd_model->tanggal_format_indonesia($periode1)) ." S.D ".strtoupper($this->tukd_model->tanggal_format_indonesia($periode2)) ."<br>TAHUN ANGGARAN $tahun</strong></td></tr>
            <tr>
            <td align=\"center\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;</strong></td></tr>
            </table>
            ";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
        <tr>
            <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>LAPORAN REALISASI DANA $judul PADA FKTP " . strtoupper($nm_skpd->nm_skpd) . "</b></td>
        </tr>
        <tr>
            <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
        </tr>
        <tr>
            <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
        </tr>
        </table>
        <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
        <thead> 
        <tr>
        <td align=\"center\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Sub Kegiatan</td>
            <td align=\"center\" width=\"10%\" style=\"font-size:12px;font-weight:bold;\">Kode Rekening</td>
            <td align=\"center\" width=\"28%\" style=\"font-size:12px;font-weight:bold\">Nama Rekening</td>

            <td align=\"center\" width=\"12%\" style=\"font-size:12px;font-weight:bold\">Jumlah Anggaran(Rp)</td>
            <td align=\"center\" width=\"12%\" style=\"font-size:12px;font-weight:bold\">Jumlah Realisasi(Rp)</td>
            <td align=\"center\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Selisih/Kurang</td>
            <td align=\"center\" width=\"5%\" style=\"font-size:12px;font-weight:bold\">%</td>
        </tr>
        <tr>
        <td align=\"center\"  style=\"font-size:12px;border-top:solid 1px black\">1</td>
        <td align=\"center\"  style=\"font-size:12px;border-top:solid 1px black\">1</td>
        <td align=\"center\"  style=\"font-size:12px;border-top:solid 1px black\">2</td>
        <td align=\"center\"  style=\"font-size:12px;border-top:solid 1px black\">3</td>
        <td align=\"center\"  style=\"font-size:12px;border-top:solid 1px black\">4</td>
        <td align=\"center\"  style=\"font-size:12px;border-top:solid 1px black\">5</td>
        <td align=\"center\"  style=\"font-size:12px;border-top:solid 1px black\">6</td>
        </tr>
        </thead>";
        $persen = 0;
        $totalanggaran = 0;
        $totalrealisasi = 0;
        $persentot = 0;
        // $hasill=0;
        // $dataaaaaaa=0;
        foreach ($dataisian->result_array() as $resulte) {
            //$persen = $resulte['realisasi'] / $resulte['anggaran'] * 100 ;
            if ($resulte['realisasi'] == 0) {
                $persen = 0;
            } else if ($resulte['anggaran'] == 0) {
                $persen = 100;
            } else {
                $persen = ($resulte['realisasi'] / $resulte['anggaran']) * 100;
            }

            $hasill = ($resulte['anggaran'] < $resulte['realisasi']) ?  '(' . number_format($resulte['realisasi'], 2, ",", ".") . ')' : number_format($resulte['realisasi'], 2, ",", ".");
            // " . number_format($resulte['anggaran'] - $resulte['realisasi'], 2, ",", ".") . "
            $hasill1 = ($resulte['anggaran'] < $resulte['realisasi']) ?  '(' . number_format($resulte['anggaran'] - $resulte['realisasi'], 2, ",", ".") . ')' : number_format($resulte['anggaran'] - $resulte['realisasi'], 2, ",", ".");
            $hasil = ($resulte['urut'] == '1') ? $resulte['kd_sub_kegiatan'] . '<br>' . $resulte['nm_sub_kegiatan'] : null;
            if ($resulte['urut'] == '1' && substr($resulte['kd_rek6'], 0, 1) == '5') {
                $totalanggaran += $resulte['anggaran'];
                $totalrealisasi += $resulte['realisasi'];
                if($totalrealisasi == 0) {
                    $persentot = 0;
                } else {
                    $persentot = $totalrealisasi / $totalanggaran * 100;
                }
            }
            $cRet .= "<tr>
            <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\"> $hasil</td>
            <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . $resulte['kd_rek6'] . "</td>
            <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . $resulte['nm_rek6'] . "</td>
            <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . number_format($resulte['anggaran'], 2, ",", ".") . "</td>
            <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">$hasill</td>
            <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">$hasill1</td>
            <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . number_format($persen, 2, ",", ".") . "</td>
            </tr>";
        }
        $cRet .= "<tr>
        <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border-top:solid 1px black\">Total</td>
        <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . number_format($totalanggaran, 2, ",", ".") . "</td>
        <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . number_format($totalrealisasi, 2, ",", ".") . "</td>
        <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . number_format($totalanggaran - $totalrealisasi, 2, ",", ".") . "</td>
        <td align=\"left\"  style=\"font-size:12px;border-top:solid 1px black\">" . number_format($persentot, 2, ",", ".") . "</td>
        </tr>";
        $cRet .= " </table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
        <tr>
        <td align=\"center\" width=\"50%\"></td>
        <td align=\"center\" width=\"50%\">Nanga Pinoh, " . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "</td>
        </tr>
        <tr>
        <td align=\"center\" width=\"50%\"></td>
        <td align=\"center\" width=\"50%\">$datattd->jabatan</td>
        </tr>
        <tr>
        <td align=\"center\" width=\"50%\">&nbsp;</td>
        <td align=\"center\" width=\"50%\"></td>
        </tr>
        <tr>
        <td align=\"center\" width=\"50%\">&nbsp;</td>
        <td align=\"center\" width=\"50%\"></td>
        </tr>

        <tr>
                <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\"><b><u></u></b><br></td>
                <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\"><b><u>$datattd->nama</u></b><br></td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\"></td>
                 <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\">NIP.$datattd->nip</td>
            </tr>
            </table>";

        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>LRA</title>");
            echo $cRet;
        } else if ($print == '1') {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0');
        } else if ($print == '2') {
            echo 'Sedang Perbaikan';
        } else {
        }
    }
}
