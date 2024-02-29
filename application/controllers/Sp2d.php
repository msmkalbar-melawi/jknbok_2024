<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Sp2d extends CI_Controller
{
    public $org_keu = "";
    public $skpd_keu = "";

    // public $ppkd1 = "4.02.02.01";
    // public $ppkd2 = "4.02.02.02";

    function __contruct()
    {
        parent::__construct();
    }
    function right($value, $count)
    {
        return substr($value, ($count * -1));
    }

    function left($string, $count)
    {
        return substr($string, 0, $count);
    }

    //START PENAGIHAN
    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }

    function config_bank2()
    {
        $lccr   = $this->input->post('q');
        $sql    = "SELECT kode, nama FROM ms_bank where upper(kode) like '%$lccr%' or upper(nama) like '%$lccr%' order by kode ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_bank' => $resulte['kode'],
                'nama_bank' => $resulte['nama']
            );
            $ii++;
        }


        echo json_encode($result);
        $query1->free_result();
    }

    function  tanggal_format_indonesia($tgl)
    {
        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
    }

    function  ambil_bulan($tgl)
    {
        $tanggal  = explode('-', $tgl);
        return  $tanggal[1];
    }

    function  tanggal_indonesia($tgl)
    {
        $tanggal  =  substr($tgl, 8, 2);
        $bulan  = substr($tgl, 5, 2);
        $tahun  =  substr($tgl, 0, 4);
        return  $tanggal . '-' . $bulan . '-' . $tahun;
    }

    function  getBulan($bln)
    {
        switch ($bln) {
            case  1:
                return  "Januari";
                break;
            case  2:
                return  "Februari";
                break;
            case  3:
                return  "Maret";
                break;
            case  4:
                return  "April";
                break;
            case  5:
                return  "Mei";
                break;
            case  6:
                return  "Juni";
                break;
            case  7:
                return  "Juli";
                break;
            case  8:
                return  "Agustus";
                break;
            case  9:
                return  "September";
                break;
            case  10:
                return  "Oktober";
                break;
            case  11:
                return  "November";
                break;
            case  12:
                return  "Desember";
                break;
            case  0:
                return  "-";
                break;
        }
    }

    function  dotrek($rek)
    {
        $nrek = strlen($rek);
        switch ($nrek) {
            case 1:
                $rek = $this->left($rek, 1);
                break;
            case 2:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1);
                break;
            case 4:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2);
                break;
            case 6:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2);
                break;
            case 8:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2);
                break;
            case 12:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 4);
                break;
            default:
                $rek = "";
        }
        return $rek;
    }


    function _mpdf($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '')
    {
        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');


        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $sa = 1;
        $tes = 0;
        if ($hal == '') {
            $hal1 = 1;
        }
        if ($hal !== '') {
            $hal1 = $hal;
        }
        if ($fonsize == '') {
            $size = 12;
        } else {
            $size = $fonsize;
        }

        $this->mpdf = new mPDF('utf-8', array(215, 330), $size); //folio
        //$this->mpdf->useOddEven = 1;                      

        $this->mpdf->AddPage($orientasi, '', $hal, '1', 'off', 10, 10, 3, 10);
        if ($hal == '') {
            $this->mpdf->SetFooter("Printed on Simakda SKPD ||  ");
        } else {
            $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }

    function config_spp($jns2)
    {
        $skpd     = $this->session->userdata('kdskpd');
        if ($jns2 == 'BL') {
            $where = "and jns_spp in ('1','2','3','6')";
        } else {
            $where = "and jns_spp in ('4')";
        }
        $sql = "SELECT max(a.urut) as nilai FROM trhspp a WHERE a.kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);

        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'nomor' => $resulte['nilai'] + 1
            );
        }
        echo json_encode($result);
    }

    function config_sk_up()
    {
        $query1 = $this->tukd_model->getAllc('trkonfig', 'sk_up');

        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'sk_up' => $resulte['sk_up']
            );
        }
        echo json_encode($result);
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
                'status' => $resulte['jns_ang']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_ttd($ttd)
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('$ttd','KPA')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }

    function load_ttdppkd($ttd)
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '5.02.0.00.0.00.02.0000' and kode in ('$ttd','KPA')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }

    function load_ttd3($ttd)
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kode='$ttd'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }

    function sp2dskpd()
    {
        $data['page_title'] = 'INPUT S P 2 D';
        $this->template->set('title', 'INPUT S P 2 D');
        $this->template->load('template', 'tukd/sp2d/sp2d', $data);
    }


    function terima_sp2d()
    {
        $data['page_title'] = 'PENERIMAAN S P 2 D';
        $this->template->set('title', 'PENERIMAAN S P 2 D');
        $this->template->load('template', 'tukd/sp2d/terima_sp2d', $data);
    }

    function sp2d_cair()
    {
        $data['page_title'] = 'PENCAIRAN S P 2 D';
        $this->template->set('title', 'PENCAIRAN S P 2 D');
        $this->template->load('template', 'tukd/sp2d_cair/sp2d_cair', $data);
    }

    function pilih_sp2d()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT no_sp2d,kd_skpd,no_spm FROM trhsp2d where kd_skpd='$kd_skpd' AND upper(no_sp2d) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'kd_skpd' => $resulte['kd_skpd'],
                'no_spm' => $resulte['no_spm']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_jenis_beban($jenis = '')
    {
        if ($jenis == 3) {
            $result = array((array(
                    "id"   => 1,
                    "text" => " TU",
                    "selected" => true
                )
                )
            );
        } else if ($jenis == 4) {
            $result = array((array(
                    "id"   => 1,
                    "text" => " Gaji & Tunjangan"
                )
                ),
                (array(
                    "id"   => 2,
                    "text" => " Kespeg"
                )
                ),
                (array(
                    "id"   => 3,
                    "text" => " Uang Makan"
                )
                ),
                (array(
                    "id"   => 4,
                    "text" => " Upah Pungut"
                )
                ),
                (array(
                    "id"   => 5,
                    "text" => " Upah Pungut PBB"
                )
                ),
                (array(
                    "id"   => 6,
                    "text" => " Upah Pungut PBB-KB PKB & BBN-KB"
                )
                ),
                (array(
                    "id"   => 7,
                    "text" => " Tambahan/Kekurangan Gaji & Tunjangan"
                )
                ),
                (array(
                    "id"   => 8,
                    "text" => " Tunjangan Transport"
                )
                ),
                (array(
                    "id"   => 9,
                    "text" => " Tunjangan Lainnya"
                )
                ),
                (array(
                    "id"   => 10,
                    "text" => " Gaji Anggota DPRD"
                )
                )
            );
        } else if ($jenis == 5) {
            $result = array((array(
                    "id"   => 1,
                    "text" => "Hibah berupa uang"
                )
                ),
                (array(
                    "id"   => 2,
                    "text" => " Bantuan Sosial berupa uang"
                )
                ),
                (array(
                    "id"   => 3,
                    "text" => " Bantuan Keuangan"
                )
                ),
                (array(
                    "id"   => 4,
                    "text" => " Subsidi"
                )
                ),
                (array(
                    "id"   => 5,
                    "text" => " Bagi Hasil"
                )
                ),
                (array(
                    "id"   => 6,
                    "text" => " Belanja Tidak Terduga"
                )
                ),
                (array(
                    "id"   => 7,
                    "text" => " Pembayaran kewajiban pemda atas putusan pengadilan, dan
    rekomendasi APIP dan/atau rekomendasi BPK"
                )
                ),
                (array(
                    "id"   => 8,
                    "text" => " Pengeluaran Pembiayaan"
                )
                ),
                (array(
                    "id" => 9,
                    "text" => "Barang yang diserahkan ke masyarakat"
                )
                )

            );
        } else if ($jenis == 6) {
            $result = array((array(
                    "id"   => 1,
                    "text" => " Tambahan Penghasilan"
                )
                ),
                (array(
                    "id"   => 2,
                    "text" => " Operasional KDH/WKDH"
                )
                ),
                (array(
                    "id"   => 3,
                    "text" => " Operasional DPRD"
                )
                ),
                (array(
                    "id"   => 4,
                    "text" => " Honor Kontrak"
                )
                ),
                (array(
                    "id"   => 5,
                    "text" => " Jasa Pelayanan Kesehatan"
                )
                ),
                (array(
                    "id"   => 6,
                    "text" => " Pihak ketiga"
                )
                ),
                (array(
                    "id"   => 7,
                    "text" => " Rutin (PNS)"
                )
                )

            );
        } else if ($jenis == 1) {
            $result = array((array(
                    "id"   => '1',
                    "text" => " UP"
                )
                )
            );
        } else if ($jenis == 2) {
            $result = array((array(
                    "id"   => '1',
                    "text" => " GU"
                )
                )
            );
        }
        echo json_encode($result);
    }



    function load_sp2d()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhsp2d WHERE  kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows * from trhsp2d WHERE  kd_skpd = '$kd_skpd' $where and no_sp2d not in (
                    SELECT TOP $offset no_sp2d from trhsp2d WHERE  kd_skpd = '$kd_skpd' $where order by no_sp2d) order by no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;


        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah Diterima';
            } else {
                $s = 'Belum Diterima';
            }
            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'jns_beban' => $resulte['jenis_beban'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'status' => $s
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }


    function nospm()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM trhspm where status = '0' AND kd_skpd = '$kd_skpd' AND (upper(no_spm) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function nospm1()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM trhspm";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'jns_beban' => $resulte['jenis_beban'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function select_data1($spp = '')
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $spp = $this->input->post('spp');
        $sql = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,sisa,no_bukti,sumber FROM trdspp WHERE no_spp='$spp' AND kd_skpd='$kd_skpd' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'kdsubkegiatan' => $resulte['kd_sub_kegiatan'],
                'nmsubkegiatan' => $resulte['nm_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_rek6'],
                'nilai1'     => number_format($resulte['nilai'], "2", ".", ","),
                'nilai'      => number_format($resulte['nilai']),
                'sisa'       => number_format($resulte['sisa']),
                'sis'        => $resulte['sisa'],
                'nilai2'   => $resulte['nilai'],
                'no_bukti'   => $resulte['no_bukti'],
                'sumber'   => $resulte['sumber']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function pot()
    {
        $kd_skpd    = $this->session->userdata('kdskpd');
        $spm        = $this->input->post('spm');
        $sql        = "SELECT * FROM trspmpot where no_spm='$spm' AND kd_skpd='$kd_skpd' order by kd_rek6 ";
        $query1     = $this->db->query($sql);
        $result     = array();
        $ii         = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6'   => $resulte['kd_rek6'],
                'kd_trans'  => $resulte['kd_trans'],
                'nm_rek6'   => $resulte['nm_rek6'],
                'pot'       => $resulte['pot'],
                'nilai'     => $resulte['nilai']
            );
            $ii++;
        }

        echo json_encode($result);
        //$query1->free_result();   
    }



    function load_terima_sp2d()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhsp2d WHERE  kd_skpd = '$kd_skpd' AND status_bud='1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows * from trhsp2d WHERE  kd_skpd = '$kd_skpd' $where  AND status_bud='1' and no_sp2d not in (
                SELECT TOP $offset no_sp2d from trhsp2d WHERE  kd_skpd = '$kd_skpd' $where  AND status_bud='1' order by no_sp2d) order by no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah diterima';
            } else {
                $s = 'Belum diterima';
            }

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'nilai' => $resulte['nilai'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'nokas' => $resulte['no_kas'],
                'no_terima' => $resulte['no_terima'],
                'dterima' => $resulte['tgl_terima'],
                'dkas' => $resulte['tgl_kas'],
                'dkasda' => $resulte['tgl_kas_bud'],
                'nocek' => $resulte['nocek'],
                'status' => $s,
                'status_trm' => $resulte['status_terima'],
                'status_cair' => $resulte['status']
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }


    function no_urut()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("select case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
    select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
    select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
    select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
    select no_panjar nomor, 'Pemberian Panjar' ket,kd_skpd from tr_panjar where  isnumeric(no_panjar)=1  union ALL
    select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
    select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
    select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
    select no_sts nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
    select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
    select no_bukti+1 nomor, 'Ambil SImpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 union ALL
    select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
    select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
    select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1   ) z WHERE KD_SKPD = '$kd_skpd'");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor'] + 1
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    //cairkan sp2d
    function simpan_terima_sp2d()
    {
        $this->db->trans_start();
        $no_sp2d    = $this->input->post('nsp2d');
        $nokas      = $this->input->post('nkas');
        $tglkas     = $this->input->post('tcair');
        $nocek      = $this->input->post('ncek');
        $total      = $this->input->post('tot');
        $cskpd      = $this->input->post('skpd');
        $cket       = $this->input->post('ket');
        $beban      = $this->input->post('beban');
        $usernm     = $this->session->userdata('pcNama');
        //$last_update=  date('d-m-y H:i:s');
        $currentdate = date("Y-m-d H:i:s");

        $sql = " update trhsp2d set status_terima='1',no_terima='$nokas',tgl_terima='$tglkas', 
                update_cair='$currentdate',
                user_cair='$usernm'
                where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);

        $buktitrm = $nokas + 1;
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
        INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
        WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612')";
        $query7 = $this->db->query($sql7);
        foreach ($query7->result_array() as $resulte7) {
            $jumlah = $resulte7['jumlah'];
            if ($jumlah > 0) {

                $sql9 = "SELECT a.*,b.jns_spp FROM trspmpot a
                        INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd
                        WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612') AND b.kd_skpd='$cskpd'";
                $query9 = $this->db->query($sql9);
                foreach ($query9->result_array() as $resulte9) {
                    $sub_kegiatan = $resulte9['kd_sub_kegiatan'];
                    $kdrekening = $resulte9['kd_rek6'];
                    $nmrekening = $resulte9['nm_rek6'];
                    $nilai = $resulte9['nilai'];
                    $jenis_spp = $resulte9['jns_spp'];
                    $kd_trans = $resulte9['kd_trans'];

                    $this->db->query("insert into trdtrmpot(no_bukti, kd_sub_kegiatan, kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans) 
                                                  values('$buktitrm','$sub_kegiatan','$kdrekening','$nmrekening','$nilai','$cskpd','$kd_trans')");
                }

                $sql8 = "SELECT SUM(a.nilai) as nilai_pot,b.keperluan, b.npwp,b.jns_spp, b.nm_skpd, c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat 
                        FROM trspmpot a INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd 
                        inner join trhspp c on b.no_spp = c.no_spp AND a.kd_skpd=b.kd_skpd 
                        WHERE b.no_sp2d = '$no_sp2d' AND b.kd_skpd='$cskpd'
                    GROUP BY no_sp2d,b.keperluan, b.npwp,b.jns_spp,b.nm_skpd,c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat";
                $query8 = $this->db->query($sql8);
                foreach ($query8->result_array() as $resulte8) {
                    $keperluan = $resulte8['keperluan'];
                    $nilai = $resulte8['nilai_pot'];
                    $npwp = $resulte8['npwp'];
                    $jenis = $resulte8['jns_spp'];
                    $nmskpd = $resulte8['nm_skpd'];
                    $kd_sub_kegiatan = $resulte8['kd_sub_kegiatan'];
                    $nm_sub_kegiatan = $resulte8['nm_sub_kegiatan'];
                    $nmrekan = $resulte8['nmrekan'];
                    $pimpinan = $resulte8['pimpinan'];
                    $alamat = $resulte8['alamat'];

                    $this->db->query("insert into trhtrmpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,no_sp2d,nilai,npwp,jns_spp,status,kd_sub_kegiatan,nm_sub_kegiatan,nmrekan,pimpinan,alamat) 
                                              values('$buktitrm','$tglkas','Terima pajak nomor SP2D $no_sp2d','$usernm','','$cskpd','$nmskpd','$no_sp2d','$nilai','$npwp','$jenis','1','$kd_sub_kegiatan','$nm_sub_kegiatan','$nmrekan','$pimpinan','$alamat')");
                }
            }
        }
        $this->db->trans_complete();
        echo '1';
    }


    function batal_terima()
    {
        $skpd       = $this->session->userdata('kdskpd');
        $no_sp2d    = $this->input->post('nsp2d');
        $nokas      = $this->input->post('nkas');
        $tglkas     = $this->input->post('tcair');
        $nocek      = $this->input->post('ncek');
        $total      = $this->input->post('tot');
        $buktitrm   = $nokas + 1;
        $sql = " update trhsp2d set status_terima='0',no_terima='',tgl_terima='' where no_sp2d='$no_sp2d'  AND kd_skpd='$skpd'";
        $asg = $this->db->query($sql);

        $sql = " delete from trdtrmpot where no_bukti='$buktitrm' and kd_skpd='$skpd' ";
        $asg = $this->db->query($sql);

        $sql = " delete from trhtrmpot where no_bukti='$buktitrm' and kd_skpd='$skpd' ";
        $asg = $this->db->query($sql);


        if ($asg > 0) {
            echo '1';
        }
    }



    function load_sp2d_cair()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhsp2d WHERE status_terima = '1' AND kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows * from trhsp2d WHERE status_terima = '1' AND kd_skpd = '$kd_skpd' $where and no_sp2d not in (
                SELECT TOP $offset no_sp2d from trhsp2d WHERE status_terima = '1' AND kd_skpd = '$kd_skpd' $where order by no_sp2d) order by no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status'] == '1') {
                $s = 'Sudah Cair';
            } else {
                $s = 'Belum Cair';
            }

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'tgl_terima' => $resulte['tgl_terima'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'nokas' => $resulte['no_kas'],
                'dkas' => $resulte['tgl_kas'],
                'nocek' => $resulte['nocek'],
                // 'nocek'=>$this->tukd_model->get_nama($resulte['no_spp'],'kontrak','trhspp' ,'no_spp'),
                'status' => $s
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    // SIMPAN CAIR
    function simpan_cair()
    {
        $no_sp2d    = $this->input->post('nsp2d');
        $nokas      = $this->input->post('nkas');
        $tglkas     = $this->input->post('tcair');
        $nocek      = $this->input->post('ncek');
        $total      = $this->input->post('tot');
        $cskpd      = $this->input->post('skpd');
        $cket       = $this->input->post('ket');
        $beban      = $this->input->post('beban');
        $jns_bbn    = $this->input->post('jenis');
        $potongan   = $this->input->post('tot_pot');
        $npwp       = $this->input->post('npwp');
        $usernm     = $this->session->userdata('pcNama');
        //$last_update=  date('y-m-d');
        $last_update =  "";

        $nospp      = $this->tukd_model->get_nama($no_sp2d, 'no_spp', 'trhsp2d', 'no_sp2d');

        $kontrak    = $this->tukd_model->get_nama($nospp, 'kontrak', 'trhspp', 'no_spp');

        $total = str_replace(",", "", $total);
        $nmskpd = $this->tukd_model->get_nama($cskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $currentdate = date("Y-m-d H:i:s");

        $sql = " update trhsp2d set status='1',no_kas='$nokas',tgl_kas='$tglkas',nocek='$nocek',
                update_cair='$currentdate',
                user_cair='$usernm'
                 where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);

        //Pengambilan Nomor Bukti Potongan bila tak ada potongan maka nomor Bukti tetap sama dengan nomor Kas   
        $buktistr = $nokas;
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
                INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
                WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612')";

        $query7 = $this->db->query($sql7);
        foreach ($query7->result_array() as $resulte7) {
            $jumlah = $resulte7['jumlah'];
            if ($jumlah > 0) {
                $buktistr = $nokas + 1;
                $buktitrms = $this->tukd_model->get_nama($no_sp2d, 'no_bukti', 'trhtrmpot', 'no_sp2d');
                echo $buktitrms;
                if ($buktitrms == null) {
                    $buktitrm = "";
                } else {
                    $buktitrm = $buktitrms;
                }
                $sql9 = "SELECT a.*,b.jns_spp FROM trspmpot a
                        INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
                        WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612')";
                $query9 = $this->db->query($sql9);

                foreach ($query9->result_array() as $resulte9) {
                    $kd_sub_kegiatan = $resulte9['kd_sub_kegiatan'];
                    $kdrekening = $resulte9['kd_rek6'];
                    $nmrekening = $resulte9['nm_rek6'];
                    $nilai = $resulte9['nilai'];
                    $jenis_spp = $resulte9['jns_spp'];
                    $kd_trans = $resulte9['kd_trans'];
                    $this->db->query("INSERT into trdstrpot(no_bukti,kd_sub_kegiatan, kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans) 
                                                  values('$buktistr','$kd_sub_kegiatan','$kdrekening','$nmrekening','$nilai','$cskpd','$kd_trans')");
                }

                $sql8 = "SELECT SUM(a.nilai) as nilai_pot,b.keperluan, b.npwp,b.jns_spp, b.nm_skpd, c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat FROM trspmpot a INNER JOIN trhsp2d b ON a.no_spm = b.no_spm inner join trhspp c on b.no_spp = c.no_spp WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612') 
                    GROUP BY no_sp2d,b.keperluan, b.npwp,b.jns_spp,b.nm_skpd,c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat";
                $query8 = $this->db->query($sql8);
                foreach ($query8->result_array() as $resulte8) {
                    $keperluan = $resulte8['keperluan'];
                    $nilai = $resulte8['nilai_pot'];
                    $npwp = $resulte8['npwp'];
                    $jenis = $resulte8['jns_spp'];
                    $nmskpd = $resulte8['nm_skpd'];
                    $kd_kegiatan = $resulte8['kd_sub_kegiatan'];
                    $nm_kegiatan = $resulte8['nm_sub_kegiatan'];
                    $nmrekan = $resulte8['nmrekan'];
                    $pimpinan = $resulte8['pimpinan'];
                    $alamat = $resulte8['alamat'];
                    $this->db->query("INSERT into trhstrpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,no_terima,nilai,npwp,jns_spp,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,nmrekan,pimpinan,alamat) 
                                              values('$buktistr','$tglkas','Setor pajak nomor SP2D $no_sp2d','$usernm','$last_update','$cskpd','$nmskpd','$buktitrm','$nilai','$npwp','$jenis','$no_sp2d','$kd_kegiatan','$nm_kegiatan','$nmrekan','$pimpinan','$alamat')");
                }
            }
        }
        //Pengambilan Nomor STS bila tak ada HKPG maka nomor Bukti tetap sama dengan nomor Setor/nomor kas
        //HKPG dan Penghasilan tidak pernah diinput bersamaan
        $no_sts = $buktistr;
        $no_setor = $no_sts + 1;
        $sql9 = "SELECT COUNT(*) as jumlah FROM trspmpot a
            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
            WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6='2110801' and a.status_potongan = '1' ";

        $query9 = $this->db->query($sql9);
        foreach ($query9->result_array() as $resulte9) {
            $jumlah = $resulte9['jumlah'];
            if ($jumlah > 0) {
                $no_sts = $buktistr + 1;
                $no_setor = $no_sts + 2;
                $sql10 = "SELECT a.no_spm, a.kd_rek6, a.nm_rek6, a.nilai, a.kd_skpd, a.pot, a.kd_trans, c.kd_sub_kegiatan FROM trspmpot a
                        LEFT JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
                        INNER JOIN trdspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
                        WHERE b.no_sp2d = '$no_sp2d' AND a.status_potongan = '1'
                        GROUP BY a.no_spm, a.kd_rek6,a.nm_rek6,a.nilai, a.kd_skpd, a.pot, a.kd_trans,c.kd_sub_kegiatan";
                $query10 = $this->db->query($sql10);
                foreach ($query10->result_array() as $resulte10) {
                    $kdrekening = $resulte10['kd_rek6'];
                    $nmrekening = $resulte10['nm_rek6'];
                    $nilai = $resulte10['nilai'];
                    $kd_kegiatan = $resulte10['kd_sub_kegiatan'];
                    $kd_trans = $resulte10['kd_trans'];
                    $this->db->query("insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan) 
                                                  values('$cskpd','$no_sts','$kd_trans','$nilai','$kd_kegiatan')");
                }

                if ($kdrekening == '2110801') {
                    $this->db->query("insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,no_kas,tgl_kas,sumber,jns_cp,pot_khusus,no_sp2d) 
                                              values('$no_sts','$cskpd','$tglkas','$nmrekening atas SP2D $no_sp2d','$nilai','$kd_kegiatan','5','$no_sts','$tglkas','0','1','1','$no_sp2d')");
                } else {
                    $this->db->query("insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,no_kas,tgl_kas,sumber,jns_cp,pot_khusus,no_sp2d) 
                                              values('$no_sts','$cskpd','$tglkas','$nmrekening atas SP2D $no_sp2d','$nilai','$kd_kegiatan','5','$no_sts','$tglkas','0','1','2','$no_sp2d')");
                }
            }
        }


        if (($beban < 5) or ($beban == 6 && $jns_bbn != 6)) {
            //$no_setor = $no_sts+1;
            $this->db->query(" insert into tr_setorsimpanan(no_kas,tgl_kas,no_bukti,tgl_bukti,kd_skpd,nilai,keterangan,jenis) 
                         values('$no_setor','$tglkas','$no_setor','$tglkas','$cskpd',$total-$potongan,'PU BANK atas SP2D $no_sp2d','1')");
        }

        $no_trans = $no_setor + 1;


        if (($beban == '4') && ($jns_bbn == '1' || $jns_bbn == '10')) {
            $sql2 = " insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay)
                  values('$no_trans','$tglkas','$no_trans','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','BANK') ";
            $asg2 = $this->db->query($sql2);
        }
        if ($beban == '5') {
            $sql2 = " insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay)
                      values('$nokas','$tglkas','$nokas','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','LS') ";
            $asg2 = $this->db->query($sql2);
        }
        if ((($beban == '6') && ($kontrak <> '' || $jns_bbn == '6'))) {
            $sql2 = " insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay)
                      values('$nokas','$tglkas','$nokas','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','LS') ";
            $asg2 = $this->db->query($sql2);
        }
        if (($beban == '6' && $jns_bbn == '5')) {
            $sql2 = " insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay)
                      values('$no_trans','$tglkas','$no_trans','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','BANK') ";
            $asg2 = $this->db->query($sql2);
        }


        $sql = " SELECT a.no_spp,a.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6,a.nilai,b.bulan,c.no_spm,d.no_sp2d,b.sts_tagih,a.sumber FROM trdspp a 
                 LEFT JOIN trhspp b ON a.no_spp=b.no_spp
                 LEFT JOIN trhspm c ON c.no_spp=b.no_spp
                 LEFT JOIN trhsp2d d ON d.no_spm=c.no_spm
                 WHERE d.no_sp2d='$no_sp2d' ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        $jum = 0;
        foreach ($query1->result_array() as $resulte) {

            $sp2d = $no_sp2d;
            $jns = $beban;
            $skpd = $resulte['kd_skpd'];
            $giat = $resulte['kd_sub_kegiatan'];
            $rek6 = $resulte['kd_rek6'];
            $nilai = $resulte['nilai'];
            $sumber = $resulte['sumber'];

            $nmskpd = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            //$nmgiat=$this->tukd_model->get_nama($giat,'nm_kegiatan','trskpd','kd_kegiatan');
            //$nmrek5=$this->tukd_model->get_nama($rek5,'nm_rek5','ms_rek5','kd_rek5');
            $nmgiat = empty($giat) || $giat == '' || $giat == null ? '' : $this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');

            if ($beban == '1') {
                $nmrek6 = "Uang Persediaan";
            } else {
                $nmrek6 = empty($rek6) || $rek6 == '' ? '' : $this->tukd_model->get_nama($rek6, 'nm_rek6', 'ms_rek6', 'kd_rek6');
            }


            if (($beban == '4') && ($jns_bbn == '1' || $jns_bbn == '10')) {
                $this->db->query("insert trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,sumber) 
                                  values('$no_trans','$giat','$nmgiat','$rek6','$nmrek6',$nilai,'$sp2d','$skpd','$sumber') ");
            }
            if (($beban == '5')) {
                $this->db->query("insert trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,sumber) 
                                  values('$nokas','$giat','$nmgiat','$rek6','$nmrek6',$nilai,'$sp2d','$skpd','$sumber') ");
            }
            if ((($beban == '6') && ($kontrak <> '' || $jns_bbn == '6'))) {
                $this->db->query("insert trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,sumber) 
                                  values('$nokas','$giat','$nmgiat','$rek6','$nmrek6',$nilai,'$sp2d','$skpd','$sumber') ");
            }
            if (($beban == '6' && $jns_bbn == '5')) {
                $this->db->query("insert trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,sumber) 
                                  values('$no_trans','$giat','$nmgiat','$rek6','$nmrek6',$nilai,'$sp2d','$skpd','$sumber') ");
            }
        }
        echo '1';
    }
    // END

    function simpan_cair_orafix()
    {
        $this->db->trans_start();
        $no_sp2d    = $this->input->post('nsp2d');
        $nokas      = $this->input->post('nkas');
        $tglkas     = $this->input->post('tcair');
        $nocek      = $this->input->post('ncek');
        $total      = $this->input->post('tot');
        $cskpd      = $this->input->post('skpd');
        $cket       = $this->input->post('ket');
        $beban      = $this->input->post('beban');
        $jns_bbn    = $this->input->post('jenis');
        // echo ($beban);
        // echo "<br>";
        // echo($beban);
        //  echo "<br>";
        //  echo ($jns_bbn);
        //  echo "<br>";
        // return;
        $potongan   = $this->input->post('tot_pot');
        $npwp       = $this->input->post('npwp');
        $usernm     = $this->session->userdata('pcNama');
        $last_update =  "";
        $nospp      = $this->tukd_model->get_nama($no_sp2d, 'no_spp', 'trhsp2d', 'no_sp2d');
        $kontrak    = $this->tukd_model->get_nama($nospp, 'kontrak', 'trhspp', 'no_spp');
        $total      = str_replace(",", "", $total);
        $nmskpd     = $this->tukd_model->get_nama($cskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $currentdate = date("Y-m-d H:i:s");

        $sql = "UPDATE trhsp2d set status='1',no_kas='$nokas',tgl_kas='$tglkas',nocek='$nocek',
                update_cair='$currentdate',
                user_cair='$usernm'
                 where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);

        //Pengambilan Nomor Bukti Potongan bila tak ada potongan maka nomor Bukti tetap sama dengan nomor Kas   
        $buktistr = $nokas;
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
            WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612')";

        $query7 = $this->db->query($sql7);
        foreach ($query7->result_array() as $resulte7) {
            $jumlah = $resulte7['jumlah'];
            if ($jumlah > 0) {
                $buktistr = $nokas + 1;
                $buktitrms = $this->tukd_model->get_nama($no_sp2d, 'no_bukti', 'trhtrmpot', 'no_sp2d');
                echo $buktitrms;
                if ($buktitrms == null) {
                    $buktitrm = "";
                } else {
                    $buktitrm = $buktitrms;
                }
                $sql9 = "SELECT a.*,b.jns_spp FROM trspmpot a
                        INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
                        WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612')";
                $query9 = $this->db->query($sql9);

                foreach ($query9->result_array() as $resulte9) {
                    $kdrekening = $resulte9['kd_rek6'];
                    $nmrekening = $resulte9['nm_rek6'];
                    $nilai = $resulte9['nilai'];
                    $jenis_spp = $resulte9['jns_spp'];
                    $kd_trans = $resulte9['kd_trans'];
                    $this->db->query("INSERT into trdstrpot(no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans) 
                                                  values('$buktistr','$kdrekening','$nmrekening','$nilai','$cskpd','$kd_trans')");
                }

                $sql8 = "SELECT SUM(a.nilai) as nilai_pot,b.keperluan, b.npwp,b.jns_spp, b.nm_skpd, c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat FROM trspmpot a INNER JOIN trhsp2d b ON a.no_spm = b.no_spm inner join trhspp c on b.no_spp = c.no_spp WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6 NOT IN ('2110801','4140612') 
                    GROUP BY no_sp2d,b.keperluan, b.npwp,b.jns_spp,b.nm_skpd,c.kd_sub_kegiatan, c.nm_sub_kegiatan,c.nmrekan,c.pimpinan,c.alamat";
                $query8 = $this->db->query($sql8);
                foreach ($query8->result_array() as $resulte8) {
                    $keperluan = $resulte8['keperluan'];
                    $nilai = $resulte8['nilai_pot'];
                    $npwp = $resulte8['npwp'];
                    $jenis = $resulte8['jns_spp'];
                    $nmskpd = $resulte8['nm_skpd'];
                    $kd_kegiatan = $resulte8['kd_sub_kegiatan'];
                    $nm_kegiatan = $resulte8['nm_sub_kegiatan'];
                    $nmrekan = $resulte8['nmrekan'];
                    $pimpinan = $resulte8['pimpinan'];
                    $alamat = $resulte8['alamat'];
                    $this->db->query("INSERT into trhstrpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,no_terima,nilai,npwp,jns_spp,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,nmrekan,pimpinan,alamat) 
                                              values('$buktistr','$tglkas','Setor pajak nomor SP2D $no_sp2d','$usernm','$last_update','$cskpd','$nmskpd','$buktitrm','$nilai','$npwp','$jenis','$no_sp2d','$kd_kegiatan','$nm_kegiatan','$nmrekan','$pimpinan','$alamat')");
                }
            }
        }
        //Pengambilan Nomor STS bila tak ada HKPG maka nomor Bukti tetap sama dengan nomor Setor/nomor kas
        //HKPG dan Penghasilan tidak pernah diinput bersamaan
        $no_sts = $buktistr;
        $no_setor = $no_sts + 1;
        $sql9 = "SELECT COUNT(*) as jumlah FROM trspmpot a
        INNER JOIN trhsp2d b ON a.no_spm = b.no_spm
        WHERE b.no_sp2d = '$no_sp2d' AND a.kd_rek6='2110801' and a.status_potongan = '1' ";

        $query9 = $this->db->query($sql9);
        foreach ($query9->result_array() as $resulte9) {
            $jumlah = $resulte9['jumlah'];
            if ($jumlah > 0) {
                $no_sts = $buktistr + 1;
                $no_setor = $no_sts + 2;
                $sql10 = "SELECT a.no_spm, a.kd_rek6, a.nm_rek6, a.nilai, a.kd_skpd, a.pot, a.kd_trans, c.kd_sub_kegiatan FROM trspmpot a
                        LEFT JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd = b.kd_skpd
                        INNER JOIN trdspp c ON b.no_spp = c.no_spp AND b.kd_skpd = c.kd_skpd
                        WHERE b.no_sp2d = '$no_sp2d' AND a.status_potongan = '1'
                        GROUP BY a.no_spm, a.kd_rek6,a.nm_rek6,a.nilai, a.kd_skpd, a.pot, a.kd_trans,c.kd_sub_kegiatan";
                $query10 = $this->db->query($sql10);
                foreach ($query10->result_array() as $resulte10) {
                    $kdrekening = $resulte10['kd_rek6'];
                    $nmrekening = $resulte10['nm_rek6'];
                    $nilai = $resulte10['nilai'];
                    $kd_kegiatan = $resulte10['kd_sub_kegiatan'];
                    $kd_trans = $resulte10['kd_trans'];
                    $this->db->query("insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan) 
                                                  values('$cskpd','$no_sts','$kd_trans','$nilai','$kd_kegiatan')");
                }

                if ($kdrekening == '2110801') {
                    $this->db->query("insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,no_kas,tgl_kas,sumber,jns_cp,pot_khusus,no_sp2d) 
                                              values('$no_sts','$cskpd','$tglkas','$nmrekening atas SP2D $no_sp2d','$nilai','$kd_kegiatan','5','$no_sts','$tglkas','0','1','1','$no_sp2d')");
                } else {
                    $this->db->query("insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,no_kas,tgl_kas,sumber,jns_cp,pot_khusus,no_sp2d) 
                                              values('$no_sts','$cskpd','$tglkas','$nmrekening atas SP2D $no_sp2d','$nilai','$kd_kegiatan','5','$no_sts','$tglkas','0','1','2','$no_sp2d')");
                }
            }
        }


        if (($beban < 5) or ($beban == 6 && $kontrak == '')) {
            //$no_setor = $no_sts+1;
            $this->db->query(" INSERT into tr_setorsimpanan(no_kas,tgl_kas,no_bukti,tgl_bukti,kd_skpd,nilai,keterangan,jenis,no_sp2d) 
                             values('$no_setor','$tglkas','$no_setor','$tglkas','$cskpd',$total-$potongan,'PU BANK atas SP2D $no_sp2d','1','$no_sp2d')");
        }

        $no_trans = $no_setor + 1;

        //  $sql1 = " insert into trhkasout_pkd(no_kas,tgl_kas,no_sp2d,no_cek,kd_skpd,ket,nm_skpd,jns_beban,username,nilai,tgl_update) 
        //            values('$nokas','$tglkas','$no_sp2d','$nocek','$cskpd','$cket','$nmskpd','$beban','$usernm',$total,'$last_update') ";
        //   $asg1 = $this->db->query($sql1);
        if (($beban == '4') && ($jns_bbn == '1' || $jns_bbn == '10')) {
            $sql2 = " INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay)
                      values('$no_trans','$tglkas','$no_trans','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','BANK') ";
            $asg2 = $this->db->query($sql2);
        }
        if ($beban == '5') {
            $sql2 = " insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay)
                      values('$nokas','$tglkas','$nokas','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','LS') ";
            $asg2 = $this->db->query($sql2);
        }
        if (($beban == '6')) {
            $sql2 = " insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,no_sp2d,kd_skpd,nm_skpd,total,ket,jns_spp,username,tgl_update,pay)
                      values('$no_trans','$tglkas','$no_trans','$tglkas','$no_sp2d','$cskpd','$nmskpd',$total,'$cket','$beban','$usernm','$last_update','LS') ";
            $asg2 = $this->db->query($sql2);
        }




        $sql = " SELECT a.no_spp,a.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6,a.nilai,b.bulan,c.no_spm,d.no_sp2d,b.sts_tagih,a.sumber FROM trdspp a 
                 LEFT JOIN trhspp b ON a.no_spp=b.no_spp
                 LEFT JOIN trhspm c ON c.no_spp=b.no_spp
                 LEFT JOIN trhsp2d d ON d.no_spm=c.no_spm
                 WHERE d.no_sp2d='$no_sp2d' ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        $jum = 0;
        foreach ($query1->result_array() as $resulte) {

            $sp2d = $no_sp2d;
            $jns = $beban;
            $skpd = $resulte['kd_skpd'];
            $giat = $resulte['kd_sub_kegiatan'];
            $rek6 = $resulte['kd_rek6'];
            $nilai = $resulte['nilai'];
            $sumber = $resulte['sumber'];

            $nmskpd = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            //$nmgiat=$this->tukd_model->get_nama($giat,'nm_kegiatan','trskpd','kd_kegiatan');
            //$nmrek5=$this->tukd_model->get_nama($rek5,'nm_rek5','ms_rek5','kd_rek5');
            $nmgiat = empty($giat) || $giat == '' || $giat == null ? '' : $this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');

            if ($beban == '1') {
                $nmrek6 = "Uang Persediaan";
            } else {
                $nmrek6 = empty($rek6) || $rek6 == '' ? '' : $this->tukd_model->get_nama($rek6, 'nm_rek6', 'ms_rek6', 'kd_rek6');
            }

            // if(($beban =='4')&& ($jns_bbn=='1' || $jns_bbn=='10')){
            //     $this->db->query("insert trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,sumber) 
            //                       values('$no_trans','$giat','$nmgiat','$rek6','$nmrek6',$nilai,'$sp2d','$skpd','$sumber') ");
            //     }
            //     if(($beban == 6)){
            //     $this->db->query("insert trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,sumber) 
            //                       values('$no_trans','$giat','$nmgiat','$rek6','$nmrek6',$nilai,'$sp2d','$skpd','$sumber') ");
            //     }if(($beban == 5)){
            //     $this->db->query("insert trdtransout(no_bukti,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,no_sp2d,kd_skpd,sumber) 
            //                       values('$nokas','$giat','$nmgiat','$rek6','$nmrek6',$nilai,'$sp2d','$skpd','$sumber') ");
            //     }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo '0';
        } else {
            echo '1';
        }
    }

    function batal_cair()
    {
        $skpd       = $this->session->userdata('kdskpd');
        $no_sp2d    = $this->input->post('nsp2d');
        $nokas      = $this->input->post('nkas');
        $beban      = $this->input->post('beban');
        $jns_bbn    = $this->input->post('jenis');
        $tglkas     = $this->input->post('tcair');
        $nocek      = $this->input->post('ncek');
        $total      = $this->input->post('tot');
        $nospp      = $this->tukd_model->get_nama($no_sp2d, 'no_spp', 'trhsp2d', 'no_sp2d');
        $kontrak    = $this->tukd_model->get_nama($nospp, 'kontrak', 'trhspp', 'no_spp');

        $buktistr = $nokas;
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd
            WHERE b.no_sp2d = '$no_sp2d' AND b.kd_skpd='$skpd' ---AND a.kd_rek6 NOT IN ('2110801','4140612') ";
        $query7 = $this->db->query($sql7);
        foreach ($query7->result_array() as $resulte7) {
            $jumlah = $resulte7['jumlah'];
            if ($jumlah > 0) {
                $buktistr = $nokas + 1;
            }
        }

        $no_sts = $buktistr;
        $no_setor = $no_sts + 1;


        if (($beban < 5) or ($beban == 6 && $jns_bbn != 6)) {
            $sql1 = " DELETE from tr_setorsimpanan where no_kas='$no_setor' and kd_skpd ='$skpd' ";
            $asg1 = $this->db->query($sql1);
        }

        $no_trans = $no_setor + 1;


        $sql = " UPDATE trhsp2d set status='0',no_kas='',tgl_kas=tgl_terima where no_sp2d='$no_sp2d' and kd_skpd = '$skpd' ";
        $asg = $this->db->query($sql);

        if (($beban == '4') && ($jns_bbn == '1' || $jns_bbn == '10')) {
            $sql1 = " DELETE from trhtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }
        if (($beban == '5')) {
            $sql1 = " DELETE from trhtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }
        if ((($beban == '6') && ($kontrak <> '' || $jns_bbn == '6'))) {
            $sql1 = " DELETE from trhtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }
        if (($beban == '6' && $jns_bbn == '5')) {
            $sql1 = " DELETE from trhtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }

        $sql1 = " DELETE from trhstrpot where no_bukti='$buktistr' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
        $asg1 = $this->db->query($sql1);

        $sql1 = " DELETE from trdstrpot where no_bukti='$buktistr' and kd_skpd = '$skpd'";
        $asg1 = $this->db->query($sql1);

        $sql1 = " DELETE from trhkasin_pkd where no_sts='$no_sts' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d' ";
        $asg1 = $this->db->query($sql1);
        $sql1 = " DELETE from trdkasin_pkd where no_sts='$no_sts' and kd_skpd = '$skpd' and  no_sts in (select isnull(no_sp2d,'') from trhsp2d where no_sp2d='$no_sp2d') ";
        $asg1 = $this->db->query($sql1);


        echo  $this->db->trans_complete();
    }

    function batal_cairoraifx()
    {
        $this->db->trans_start();
        $skpd     = $this->session->userdata('kdskpd');
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $beban = $this->input->post('beban');
        $jns_bbn = $this->input->post('jenis');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $nospp = $this->tukd_model->get_nama($no_sp2d, 'no_spp', 'trhsp2d', 'no_sp2d');
        $kontrak = $this->tukd_model->get_nama($nospp, 'kontrak', 'trhspp', 'no_spp');

        $buktistr = $nokas;
        $sql7 = "SELECT COUNT(*) as jumlah FROM trspmpot a
            INNER JOIN trhsp2d b ON a.no_spm = b.no_spm AND a.kd_skpd=b.kd_skpd
            WHERE b.no_sp2d = '$no_sp2d' AND b.kd_skpd='$skpd' ---AND a.kd_rek6 NOT IN ('2110801','4140612') ";
        $query7 = $this->db->query($sql7);
        foreach ($query7->result_array() as $resulte7) {
            $jumlah = $resulte7['jumlah'];
            if ($jumlah > 0) {
                $buktistr = $nokas + 1;
            }
        }

        $no_sts = $buktistr;
        $no_setor = $no_sts + 1;


        if (($beban < 5) or ($beban == 6 && $jns_bbn == 1) or ($beban == 6 && $jns_bbn == 4) or ($beban == 4 && $jns_bbn != 9)) {
            $sql1 = " DELETE from tr_setorsimpanan where no_kas='$no_setor' and kd_skpd = '$skpd' ";
            $asg1 = $this->db->query($sql1);
        }

        $no_trans = $no_setor + 1;


        $sql = " UPDATE trhsp2d set status='0',no_kas='',tgl_kas=tgl_terima where no_sp2d='$no_sp2d' and kd_skpd = '$skpd' ";
        $asg = $this->db->query($sql);



        if (($beban == 4 && $jns_bbn == 1)) {
            $sql1 = " DELETE from trhtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d' ";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }

        if (($beban == 6 && $jns_bbn == 4)) {
            $sql1 = " DELETE from trhtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$no_trans' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }

        if (($beban == 6 && $jns_bbn != 1)) {
            $sql1 = " DELETE from trhtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }
        if (($beban == '6')) {
            $sql1 = " DELETE from trhtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }

        if (($beban == 4 && $jns_bbn == 9)) {
            $sql1 = " DELETE from trhtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
            $sql1 = " DELETE from trdtransout where no_bukti='$nokas' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
            $asg1 = $this->db->query($sql1);
        }

        $sql1 = " DELETE from trhstrpot where no_bukti='$buktistr' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d'";
        $asg1 = $this->db->query($sql1);

        $sql1 = " DELETE from trdstrpot where no_bukti='$buktistr' and kd_skpd = '$skpd'";
        $asg1 = $this->db->query($sql1);

        $sql1 = " DELETE from trhkasin_pkd where no_sts='$no_sts' and kd_skpd = '$skpd' and no_sp2d='$no_sp2d' ";
        $asg1 = $this->db->query($sql1);
        $sql1 = " DELETE from trdkasin_pkd where no_sts='$no_sts' and kd_skpd = '$skpd' and  no_sts in (select isnull(no_sp2d,'') from trhsp2d where no_sp2d='$no_sp2d') ";
        $asg1 = $this->db->query($sql1);


        echo  $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            echo '0';
        } else {
            echo '1';
        }
    }

    // ---------------------------------------------------------------------
    function kartu_kendali()
    {
        $data['page_title'] = 'KARTU KENDALI KEGIATAN ';
        $this->template->set('title', 'KARTU KENDALI KEGIATAN');
        $this->template->load('template', 'tukd/transaksi/kartu_kendali', $data);
    }

    function load_giat_trans()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdtransout a inner join trhtransout b ON a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                AND b.kd_skpd='$kd_skpd' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan order by a.kd_sub_kegiatan ";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }

    function cetak_kartu_kendali($lcskpd = '', $giat = '', $ctk = '')
    {
        $spasi = $this->uri->segment(9);
        $nomor = str_replace('123456789', ' ', $this->uri->segment(6));
        $nip2 = str_replace('123456789', ' ', $this->uri->segment(7));
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(8));
        $nbulan = $this->ambil_bulan($this->uri->segment(8));
        $skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$lcskpd' AND kode in ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip = '$nomor' AND kd_skpd='$lcskpd' AND kode='PPTK'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>' . $prov . ' </TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>KARTU KENDALI SUB KEGIATAN </b></TD>
                    </TR>
                    </TABLE><br />';

        $cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" width="90%" border="0" cellspacing="0" cellpadding="0" align=center>
                    <TR>
                        <TD align="left" width="15%"><b>OPD</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $lcskpd . ' - ' . $skpd . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="15%"><b>NAMA PROGRAM</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $this->left($giat, 7) . ' - ' . $this->tukd_model->get_nama($this->left($giat, 7), 'nm_program', 'ms_program', 'kd_program') . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="15%"><b>NAMA KEGIATAN</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $this->left($giat, 12) . ' - ' . strtoupper($this->tukd_model->get_nama($this->left($giat, 12), 'nm_kegiatan', 'ms_kegiatan', 'kd_kegiatan')) . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="15%"><b>NAMA SUB KEGIATAN</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $giat . ' - ' . strtoupper($this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'ms_sub_kegiatan', 'kd_sub_kegiatan')) . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="10%"><b>NAMA PPTK</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $nip1 . ' - ' . $nama1 . '</b> </TD>
                    </TR>
                    </TABLE> <p/>';
        $cRet .= "<table style=\"border-collapse:collapse; font-size:12px\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
            <thead>
            <tr>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>No Urut</b></td>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>KODE REKENING</b></td>
                <td colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>PAGU ANGGARAN</b></td>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>URAIAN</b></td>
                <td colspan =\"3\" align=\"center\" bgcolor=\"#CCCCCC\"><b>REALISASI KEGIATAN</b></td>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>SISA PAGU</b></td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>MURNI</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>UBAH</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>LS</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>UP/GU</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>TU</b></td>
            </tr>
            </thead>
           ";
        $query = $this->db->query("exec cetak_kartu_kendali '$lcskpd',$nbulan,'$giat'");
        $no = 0;
        foreach ($query->result() as $row) {
            $no = $no + 1;
            $kd_rek6 = $row->kd_rek6;
            $nilai = $row->nilai;
            $nilai_ubah = $row->nilai_ubah;
            $uraian = $row->uraian;
            $real_ls = $row->real_ls;
            $real_up = $row->real_up;
            $real_tu = $row->real_tu;
            $sisa = $row->sisa;

            $nilai1  = empty($nilai) || $nilai == 0 ? '' : number_format($nilai, "2", ",", ".");
            $nilai_ubah1  = empty($nilai_ubah) || $nilai_ubah == 0 ? '' : number_format($nilai_ubah, "2", ",", ".");
            $real_ls1  = empty($real_ls) || $real_ls == 0 ? number_format(0, "2", ",", ".") : number_format($real_ls, "2", ",", ".");
            $real_up1  = empty($real_up) || $real_up == 0 ? number_format(0, "2", ",", ".") : number_format($real_up, "2", ",", ".");
            $real_tu1  = empty($real_tu) || $real_tu == 0 ? number_format(0, "2", ",", ".") : number_format($real_tu, "2", ",", ".");
            $sisa1  = empty($sisa) || $sisa == 0 ? number_format(0, "2", ",", ".") : number_format($sisa, "2", ",", ".");
            $cRet .= "
                <tr>
                <td align=\"center\" >$no</td>
                <td align=\"left\" >$kd_rek6</td>
                <td align=\"right\" >$nilai1</td>
                <td align=\"right\" >$nilai_ubah1</td>
                <td align=\"left\" >$uraian</td>
                <td align=\"right\" >$real_ls1</td>
                <td align=\"right\" >$real_up1</td>
                <td align=\"right\" >$real_tu1</td>
                <td align=\"right\" >$sisa1</td>
                </tr>
                ";
        }

        $cRet .= "</table>";
        $cRet .= '<TABLE width="100%" style="font-size:12px" border="0" cellspacing="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" >Mengetahui,</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >' . $daerah . ', ' . $tanggal_ttd . '</TD>
                    </TR>
                    <TR>
                        <TD align="center" >' . $jabatan . ';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >' . $jabatan1 . '</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><u><b>' . $nama . ' </b><br></u> ' . $pangkat . ';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><u><b>' . $nama1 . ' </b><br></u> ' . $pangkat1 . '</TD>
                    </TR>
                    <TR>
                        <TD align="center" >' . $nip . ';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >' . $nip1 . '</TD>
                    </TR>
                    </TABLE><br/>';

        $data['prev'] = 'DTH';
        switch ($ctk) {
            case 0;
                echo ("<title>KARTU KENDALI</title>");
                echo $cRet;
                break;
            case 1;
                $this->_mpdf('', $cRet, 10, 10, 10, 'L', 0, '');
                break;
        }
    }
    // ----------------------------------------------------------------------
}
