<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Spp extends CI_Controller
{
    public $org_keu = "";
    public $skpd_keu = "";

    // public $ppkd1 = "4.02.02.01";
    // public $ppkd2 = "4.02.02.02";

    public function __contruct()
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

    function sppup()
    {
        $data['page_title'] = 'SPP UP';
        $this->template->set('title', 'SPP UP');
        $this->template->load('template', 'tukd/spp/spp_up', $data);
    }
    // SPP GU NIHIL
    function sppgunihil()
    {
        $data['page_title'] = 'SPP GU NIHIL';
        $this->template->set('title', 'SPP GU NIHIL');
        $this->template->load('template', 'tukd/spp/tambah_spp_gu_nihil', $data);
    }

    function sppls()
    {
        $data['page_title'] = 'SPP LS';
        $this->template->set('title', 'SPP LS');
        // $this->template->load('template','tukd/spp/maintenance',$data) ;    
        $this->template->load('template', 'tukd/spp/spp_ls', $data);
    }

    function maintenance()
    {
        $data['page_title'] = 'SPP LS';
        $this->template->set('title', 'SPP LS');
        // $this->template->load('template','tukd/spp/maintenance',$data) ; 
        $this->template->load('template', 'tukd/spp/spp_ls', $data);
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
        $mpdf->showImageErrors = true;       

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

    function batal_spp()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $nospp      = $this->input->post('nospp');
        $ket      = $this->input->post('ket');
        $jns_spp      = $this->input->post('jns_spp');
        $usernm      = $this->session->userdata('pcNama');
        $last_update =  date('d-m-y H:i:s');

        $sql = "UPDATE trhspp set sp2d_batal='1',ket_batal='$ket',user_batal='$usernm',tgl_batal='$last_update' where no_spp='$nospp'";
        $asg = $this->db->query($sql);

        if ($jns_spp == '6') {
            $query1 = "SELECT ltrim(no_tagih) [no_tagih] from trhspp where no_spp='$nospp'";
            $hquery1 = $this->db->query($query1);
            $no_tagih = $hquery1->row('no_tagih');
            if ($no_tagih != '') {
                $sql1 = "UPDATE trhspp set no_tagih='',kontrak='',sts_tagih='0',nmrekan='',pimpinan='' where no_spp='$nospp'";
                $asg1 = $this->db->query($sql1);
                $sql2 = "UPDATE trhtagih set sts_tagih='0' where no_bukti='$no_tagih'";
                $asg2 = $this->db->query($sql2);
            }
        }

        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function config_spp($bulan_spp, $jenis_ls, $jns_spp)
    {
        $skpd     = $this->session->userdata('kdskpd');
        $bulan    = $bulan_spp;
        $where    = '';

        if ($jns_spp == 'LS') {
            $where    = "AND jns_spp IN ('5','4','6')";
        } else
        if ($jns_spp == 'UP') {
            $where    = "AND jns_spp IN ('1')";
        } else
        if ($jns_spp == 'TU') {
            $where    = "AND jns_spp IN ('3')";
        } else
        if ($jns_spp == 'GU') {
            $where    = "AND jns_spp IN ('2')";
        } else {
            $where    = "AND jns_spp IN ('7')";
        }

        $sql = "SELECT max(a.urut) as nilai FROM trhspp a WHERE a.kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);

        foreach ($query1->result_array() as $resulte) {
            $urut = $resulte['nilai'] + 1;
            $urut_max = str_pad($urut, 6, "0", STR_PAD_LEFT);
            $result = array(
                'nomor' => $urut_max,
                'bulan' => $bulan
            );
        }
        echo json_encode($result);
    }

    function config_sk_up()
    {
        $query1 = $this->tukd_model->getAllc('trkonfig', 'sk_up');

        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'sk_up' => $resulte['sk_up'],
                'tgl_up' => $resulte['tgl_up']
            );
        }
        echo json_encode($result);
    }

    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        // $skpd     =  $this->input->post('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
                ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' AND 
                tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
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

    function load_ttd($ttd)
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('$ttd')";

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

    function load_ttd_pakpa($pa, $kpa)
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('$pa','$kpa')";

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
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '5.02.0.00.0.00.02.0000' and kode in ('$ttd')";

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

    function spd1_up()
    {
        $result   = array(); {
            $result[] = array(
                'id'       => '0',
                'kdrek6'   => '11010301002',
                'nmrek6'   => 'Uang Persediaan'
            );
        }
        echo json_encode($result);
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


    function load_spp_up()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "and jns_spp='1'";
        if ($kriteria <> '') {
            $where = "and (upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by no_spp,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
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
                'npwp' => $resulte['npwp'],
                'status' => $resulte['status'],

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function spd1()
    {
        $skpd  = $this->session->userdata('kdskpd');
        $cjenis = $this->input->post('cjenis');
        $sql   = "SELECT no_spd, tgl_spd, total from trhspd where kd_skpd='$skpd' and status='1' ";

        if ($cjenis != '') {
            $sql   = " SELECT no_spd, tgl_spd, total from trhspd where kd_skpd='$skpd' and status='1' and jns_beban='$cjenis' ";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'tgl_spd' => $resulte['tgl_spd'],
                'nilai' => number_format($resulte['total'], 2),
                'cjenis' => $cjenis
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function spd11()
    {


        $skpd  = $this->session->userdata('kdskpd');
        $cjenis = $this->input->post('jenis');
        if ($cjenis = '5') {
            $sql   = "SELECT no_spd, tgl_spd from trhspd where left(kd_skpd,22)=left('$skpd',22) and status='1' and jns_beban ='5'";
        } else {
            //$sql   = " select no_spd, tgl_spd from trhspd where kd_skpd='$skpd' and status='1' and jns_beban='$cjenis'";            
            $sql   = "SELECT no_spd, tgl_spd from trhspd where left(kd_skpd,22)=left('$skpd',22) and status='1' and jns_beban ='5'";
        }
        //echo "$sqls";             

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $dk = $resulte['no_spd'];
            $sq = $this->db->query("select sum(nilai) as nilai from trdspd where no_spd='$dk'")->row();
            $sk = $sq->nilai;

            $parx = $resulte['tgl_spd'];
            $cpar = explode("-", $parx);
            $tgl = $cpar[2] . "-" . $cpar[1] . "-" . $cpar[0];

            $result[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'tgl_spd' => $resulte['tgl_spd'],
                'tgl_spd2' => $tgl,
                'nilai' => number_format($sk, 2)
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function pilih_ttd($dns = '')
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT nip,nama,jabatan,kd_skpd FROM ms_ttd where kd_skpd ='$dns' ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan'],
                'kd_skpd' => $resulte['kd_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sum_lpj_ag()
    {
        $skpd = $this->session->userdata('kdskpd');
        $xlpj = $this->input->post('lpj');
        $query1 = $this->db->query("SELECT SUM(a.nilai) AS jml FROM trlpj a WHERE no_lpj='$xlpj' AND kd_skpd='$skpd' ");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'cjumlah'  =>  $resulte['jml']
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
        // echo($spp);
        $sql = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,sumber,(SELECT nm_sumberdana from hsumber_dana a INNER JOIN dsumber_dana b ON b.kd_sumberdana=a.kd_sumberdana AND a.id=b.id where a.kd_sumberdana=trdspp.sumber AND b.kd_skpd='$kd_skpd')as nmsumber,
    nilai,no_bukti FROM trdspp WHERE no_spp='$spp' AND kd_skpd='$kd_skpd' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek6";

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
                // 'sisa'       => number_format($resulte['sisa']),
                // 'sis'        => $resulte['sisa'],
                'sumber'     => $resulte['sumber'],
                'nmsumber'     => $resulte['nmsumber'],

                'no_bukti'   => $resulte['no_bukti']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function ambil_nilai_up()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');

        $sql = "SELECT * from ms_up where kd_skpd='$kd_skpd'";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'nilai_up' => $resulte['nilai_up']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function hhapus_gu()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $nolpj   = $this->input->post('nolpj');
        $query = $this->db->query("delete from trdspp where no_spp='$nomor' AND kd_skpd='$kdskpd'");
        $query = $this->db->query("delete from trhspp where no_spp='$nomor' AND kd_skpd='$kdskpd'");
        $query = $this->db->query("UPDATE trhlpj SET status = '0' WHERE no_lpj = '$nolpj' AND kd_skpd='$kdskpd'");
    }

    function dsimpan_gu()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $no_spp = $this->input->post('no');
        $csql     = $this->input->post('sql');
        $sql = "DELETE from trdspp where no_spp='$no_spp' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd,no_spd,no_bukti,sumber)";
            $asg = $this->db->query($sql . $csql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                //   exit();
            } else {
                $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp'";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function load_spp_gu_nihil()
    {

        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = "and jns_spp='7' ";
        if ($kriteria <> '') {
            $where = "where and jns_spp='7' and ((upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                 upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%'))) ";
        }
        $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by no_spp,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_spp'    => $resulte['no_spp'],
                'tgl_spp'   => $resulte['tgl_spp'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'nm_skpd'   => $resulte['nm_skpd'],
                'jns_spp'   => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan'     => $resulte['bulan'],
                'no_spd'    => $resulte['no_spd'],
                'no_spd2'   => $resulte['no_spd2'],
                'no_spd3'   => $resulte['no_spd3'],
                'no_spd4'   => $resulte['no_spd4'],
                'bank'      => $resulte['bank'],
                'nmrekan'   => $resulte['nmrekan'],
                'no_rek'    => $resulte['no_rek'],
                'npwp'      => $resulte['npwp'],
                'status'    => $resulte['status'],
                'nilai'     => $resulte['nilai'],
                'no_bukti'  => $resulte['no_bukti'],
                'no_bukti2' => $resulte['no_bukti2'],
                'no_bukti3' => $resulte['no_bukti3'],
                'no_bukti4' => $resulte['no_bukti4'],
                'no_bukti5' => $resulte['no_bukti5'],
                'status' => $resulte['status'],
                'no_lpj' => $resulte['no_lpj'],
                'sp2d_batal' => $this->support->nvl($resulte['sp2d_batal'], ''),
                'ket_batal' => $this->support->nvl($resulte['ket_batal'], ''),
                'urut' => $resulte['urut']

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_tukd_spp()
    {
        $tabel   = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $lcnotagih = $this->input->post('tagih');
        $skpd  = $this->session->userdata('kdskpd');

        $sql = "select $cid from $tabel where $cid='$lcid'  ";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                if ($tabel == 'trhspm') {
                    $sql1 = " UPDATE trhspp SET status='1' where no_spp='$lcnotagih' AND kd_skpd='$skpd'";
                    $asg1 = $this->db->query($sql1);
                }
                echo '2';
            } else {
                echo '0';
            }
        }
        if ($tabel == 'trhspp') {
            $sql1 = " UPDATE trhtagih SET sts_tagih='1' where no_bukti='$lcnotagih' AND kd_skpd='$skpd'";
            $asg1 = $this->db->query($sql1);
        }
    }

    function dsimpan_up()
    {
        $no_spp      = trim($this->input->post('cno_spp'));
        $kd_skpd     = $this->input->post('cskpd');
        $kd_rek6     = $this->input->post('crek');
        $nm_rek6     = $this->input->post('nrek');
        $nilai       = $this->input->post('nilai');
        $sql = "DELETE from trdspp where no_spp='$no_spp' and kd_rek6='$kd_rek6' ";
        $asg = $this->db->query($sql);
        if ($asg > 0) {
            $query = "INSERT into trdspp(no_spp,kd_skpd,kd_rek6,nm_rek6,nilai) values('$no_spp','$kd_skpd','$kd_rek6','$nm_rek6','$nilai') ";
            $asg = $this->db->query($query);
        } else {
            echo '0';
            exit();
        }
        echo '1';
    }

    function dsimpan()

    {
        $no_spp      = trim($this->input->post('cno_spp'));
        $kd_skpd     = $this->input->post('cskpd');
        $kd_sub_kegiatan = $this->input->post('cgiat');
        $kd_rek6     = $this->input->post('crek');
        $nm_kegi = $this->input->post('ngiat');
        $nm_rek6     = $this->input->post('nrek');
        $nilai       = $this->input->post('nilai');
        $sis         = $this->input->post('sis');
        $kd          = $this->input->post('kd');
        $vno_bukti   = $this->input->post('no_bukti1');
        $nm_sub_kegiatan = $this->tukd_model->get_nama($kd_sub_kegiatan, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
        $sql = "delete from trdspp where no_spp='$no_spp' and kd_sub_kegiatan='$kd_sub_kegiatan' and kd_rek6='$kd_rek6' and no_bukti='$vno_bukti' ";

        $asg = $this->db->query($sql);
        if ($asg > 0) {
            $query = "insert into trdspp(no_spp,kd_skpd,kd_sub_kegiatan,kd_rek6,nm_sub_kegiatan,nm_rek6,nilai,sisa,kd,no_bukti) values('$no_spp','$kd_skpd','$kd_sub_kegiatan','$kd_rek6','$nm_sub_kegiatan','$nm_rek6','$nilai','0','$kd','$vno_bukti') ";
            $asg = $this->db->query($query);
        } else {
            echo '0';
            exit();
        }
        echo '1';
    }

    function dsimpan_up_edit()
    {
        $no_spp      = trim($this->input->post('cno_spp'));
        $no_hide      = trim($this->input->post('no_hide'));
        $kd_skpd     = $this->input->post('cskpd');
        $kd_rek6     = $this->input->post('crek');
        $nm_rek6     = $this->input->post('nrek');
        $nilai       = $this->input->post('nilai');
        $sql = "DELETE from trdspp where no_spp='$no_hide' and kd_rek6='$kd_rek6' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        if ($asg > 0) {
            $query = "INSERT into trdspp(no_spp,kd_skpd,kd_rek6,nm_rek6,nilai) values('$no_spp','$kd_skpd','$kd_rek6','$nm_rek6','$nilai') ";
            $asg = $this->db->query($query);
        } else {
            echo '0';
            exit();
        }
        echo '1';
    }

    function hhapus()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $notagih = $this->input->post('no_tagih');
        $query = $this->db->query("DELETE from trdspp where kd_skpd = '$kd_skpd' AND no_spp='$nomor'");
        $query = $this->db->query("DELETE from trhspp where kd_skpd = '$kd_skpd' AND no_spp='$nomor'");
        $query = $this->db->query("UPDATE trhtagih set sts_tagih=0 where kd_skpd = '$kd_skpd' AND no_bukti=rtrim('$notagih')");
        //$query->free_result();
    }

    function select_data_tran_4($no_bukti1 = '')
    {

        $no_bukti1 = $this->input->post('no_bukti1');

        $sql    = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai, no_bukti FROM trdtransout WHERE no_bukti='$no_bukti1' ORDER BY no_bukti, kd_sub_kegiatan, kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx' => $ii,
                'kdsubkegiatan' => $resulte['kd_sub_kegiatan'],
                'nmsubkegiatan' => $resulte['nm_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_re6'],
                'sumber'     => $resulte['sumber'],
                'nilai1'     => number_format($resulte['nilai']),
                'no_bukti'   => $resulte['no_bukti']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_sum_tran()
    {
        $skpd = $this->session->userdata('kdskpd');
        $id = $this->input->post('no_bukti');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdtransout where no_bukti='$id' AND kd_skpd='$skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'rektotal'  => $resulte['rektotal'],
                'rektotal1' => $resulte['rektotal']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sum_spp()
    {
        $xspp = $this->input->post('spp');
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT sum(nilai) as rektotal from trdspp where no_spp = '$xspp' AND kd_skpd='$skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'rektotal'  =>  $resulte['rektotal'],
                'rektotal1' => $resulte['rektotal']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function cetakspp1_1()
    {
        $spasi = $this->uri->segment(10);
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $client = $this->ClientModel->clientData('1');



        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->tukd_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->tukd_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd = '$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($PPTK == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode = 'PPTK' AND kd_skpd = '$kd'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                $jdl2 = 'MENGETAHUI :';
                $nip2 = 'NIP. ' . $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $tgl_spp = $this->tukd_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
        $no_spd = $this->tukd_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
        $tglspd = $this->tukd_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');

        $sqlgiat = "SELECT kd_sub_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_sub_kegiatan";
        $sqlgiat = $this->db->query($sqlgiat);
        foreach ($sqlgiat->result() as $rowgiat) {
            $giatspp = $rowgiat->kd_sub_kegiatan;
        }
        $giatspp = empty($giatspp) || $giatspp == '' ? '' : $giatspp;


        $nip2 = '';
        $nama2 = '';
        $jabatan2  = '';
        $pangkat2  = '';

        $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,
        b.urusan1 as kd_bidang_urusan,
(select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=b.urusan1)as nm_bidang_urusan,a.no_spd,a.nilai, 
        no_rek,
        a.npwp,
        (SELECT SUM(nilai) FROM trdspd WHERE no_spd=a.no_spd) AS spd,
        (SELECT SUM(nilai) FROM trhspp WHERE no_spd=a.no_spd AND kd_skpd = a.kd_skpd and no_spp <> a.no_spp)AS spp 
                FROM trhspp a INNER JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
        $query = $this->db->query($sql1);
        foreach ($query->result() as $row) {
            $kd_urusan = $row->kd_bidang_urusan;
            $nm_urusan = $row->nm_bidang_urusan;
            $kd_skpd = $row->kd_skpd;
            $nm_skpd = strtoupper($row->nm_skpd);
            $spd = $row->no_spd;
            $tgl = $row->tgl_spp;
            $no_rek = $row->no_rek;
            $npwp = $row->npwp;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $bln = $this->tukd_model->getBulan($row->bulan);
            $nilai = number_format($row->nilai, "2", ",", ".");
            $nilai1 = $row->nilai;
            $nspd = $row->spd;
            $spp = $row->spp;
            $sis = $nspd - $spp;
            $si = $nspd - $spp;
            if ($si < 0) {
                $x1 = "(";
                $si = $si * -1;
                $y1 = ")";
            } else {
                $x1 = "";
                $y1 = "";
            }
            $sisa = number_format($si, "2", ",", ".");
            $a = $this->tukd_model->terbilang($nilai1);
            $b = $this->tukd_model->terbilang($sis);
        }
        $kodebank = $this->tukd_model->get_nama($nomor, 'bank', 'trhspp', 'no_spp');
        $nama_bank = empty($kodebank) || $kodebank == '' ? '-' : $this->tukd_model->get_nama($kodebank, 'nama', 'ms_bank', 'kode');
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $nogub_susun  = $rowsc->nogub_susun;
            $nogub_perubahan  = $rowsc->nogub_perubahan;
        }
        // $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
        $data1 = $this->cek_anggaran_model->cek_anggaran($kd);
        if ($data1 == "U1") {
            $nogub = $nogub_perubahan;
        } else {
            $nogub = $nogub_susun;
        }

        $thn_ang       = $this->session->userdata('pcThang');
        if ($tanpa == 1) {
            $tanggal = $tanpa . "_______________________$thn_ang";
        }

        $unit = $this->right($kd, 2);
        if ($unit == '01' || $kd == '1.20.03.00') {
            $peng = 'Pengguna Anggaran';
        } else {
            $peng = 'Kuasa Pengguna Anggaran';
        }


        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"4\" align=\"right\">
                        <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                    </tr>";



        if (substr($kd, 0, 17) == $this->org_keu && $kd != $this->skpd_keu) {
            $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            $cRet .= "<tr>
                            <td align=\"center\" style=\"font-size:13px\">$nm_org</td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td>
                        </tr>";
        } else {
            $cRet .= "<tr>
                        <td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre>
                        </td>
                    </tr>
                    <tr>
                        <td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td>
                    </tr>";
        }

        $cRet .= "    

                    <tr>
                        <td align=\"right\">" . strtoupper($daerah) . "</td>  
                    </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong>SURAT PERMINTAAN PEMBAYARAN UANG PERSEDIAAN </strong></td></tr>
                    <tr><td align=\"center\"><strong>(SPP-UP)</strong></td></tr>                              
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"><strong>SURAT PENGANTAR</strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"left\">Kepada Yth.</td></tr>
                    <tr><td align=\"left\">$peng</td></tr>
                    <tr><td align=\"left\">OPD : </td></tr>
                    <tr><td align=\"left\">Di Tempat</td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"left\">Dengan memperhatikan Peraturan Bupati Kabupaten Melawi $nogub
                     tentang Penjabaran APBD Tahun Anggaran $thn_ang, bersama ini kami mengajukan Surat Permintaan Pembayaran UP sebagai berikut:</td></tr>
                    <tr><td align=\"center\"></td></tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">a.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Urusan Pemerintahan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_urusan - $nm_urusan</td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">b.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">OPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_skpd - $nm_skpd</td>
                                     
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">c.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Tahun Anggaran</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$thn_ang</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">d.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Dasar Pengeluaran SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$spd</td>
                                     
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">e.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Sisa Dana SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $x1$sisa$y1</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\"></td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\"></td>
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\"></td>
                                    <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"left\"><i>($b)</i></td>                                     
                                      
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">f.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Untuk Keperluan Bulan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$bln</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">g.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama " . ucwords($jabatan) . "</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">h.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Pembayaran yang Diminta</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $nilai</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\"></td>
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\"></td>
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\"></td>                    
                                    <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"left\" ><i>($a)</i> </td>                                     
                                 
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">i.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama dan Nomor Rekening Bank</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$nama_bank / $no_rek / $npwp</td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";

        $cRet .=       " </table>";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . " ,$tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
        if ($print == '1') {

            //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($print == '0') {
            echo $cRet;
        }
    }

    function cetakspp1_2()
    {

        $spasi = $this->uri->segment(10);
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $client = $this->ClientModel->clientData('1');



        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->tukd_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->tukd_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd = '$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($PPTK == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode = 'PPTK' AND kd_skpd = '$kd'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                $jdl2 = 'MENGETAHUI :';
                $nip2 = 'NIP. ' . $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $tgl_spp = $this->tukd_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
        $no_spd = $this->tukd_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
        $tglspd = $this->tukd_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');

        $sqlgiat = "SELECT kd_sub_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_sub_kegiatan";
        $sqlgiat = $this->db->query($sqlgiat);
        foreach ($sqlgiat->result() as $rowgiat) {
            $giatspp = $rowgiat->kd_sub_kegiatan;
        }
        $giatspp = empty($giatspp) || $giatspp == '' ? '' : $giatspp;



        $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan,
        b.urusan1 as kd_urusan,
        (select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=b.urusan1)as nm_urusan,
         a.bank,
                (SELECT rekening FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS no_rek,
                (SELECT npwp FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS npwp,
                a.no_spd,a.nilai,
                (SELECT SUM(a.nilai) FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.jns_beban = '5' and b.kd_skpd='$kd'
                    and b.tgl_spd <='$tglspd') AS spd,
                    (SELECT SUM(b.nilai) FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                    INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                    AND a.jns_spp IN ('1','2','3','6') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp') AS spp
                FROM trhspp a INNER JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

        $query = $this->db->query($sql1);

        foreach ($query->result() as $row) {
            $kd_urusan = $row->kd_bidang_urusan;
            $nm_urusan = $row->nm_bidang_urusan;
            $kd_skpd = $row->kd_skpd;
            $nm_skpd = $row->nm_skpd;
            $spd = $row->no_spd;
            $tgl = $row->tgl_spp;
            $no_rek = $row->no_rek;
            $npwp = $row->npwp;
            $rekan = $row->nmrekan;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $bln = $this->tukd_model->getBulan($row->bulan);
            $nilai = number_format($row->nilai, "2", ",", ".");
            $nilai1 = $row->nilai;
            $nspd = $row->spd;
            $spp = $row->spp;
            $sis = $nspd - $spp;
            $si = $nspd - $spp;
            if ($si < 0) {
                $x1 = "(";
                $si = $si * -1;
                $y1 = ")";
            } else {
                $x1 = "";
                $y1 = "";
            }
            $sisa = number_format($si, "2", ",", ".");
            $a = $this->tukd_model->terbilang($nilai1);
            $b = $this->tukd_model->terbilang($sis);
            //echo($a);
        }
        $kodebank = $this->tukd_model->get_nama($kd, 'bank', 'ms_skpd', 'kd_skpd');
        $nama_bank = empty($kodebank) || $kodebank == '' ? '-' : $this->tukd_model->get_nama($kodebank, 'nama', 'ms_bank', 'kode');


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $nogub_susun  = $rowsc->nogub_susun;
            $nogub_perubahan  = $rowsc->nogub_perubahan;
        }
        $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
        if ($stsubah == 1) {
            $nogub = $nogub_perubahan;
        } else {
            $nogub = $nogub_susun;
        }
        $unit = $this->right($kd, 2);
        if ($unit == '01' || $kd = '1.20.03.00') {
            $peng = 'Pengguna Anggaran';
        } else {
            $peng = 'Kuasa Pengguna Anggaran';
        }
        $thn_ang       = $this->session->userdata('pcThang');
        if ($tanpa == 1) {
            $tanggal = "______________________$thn_ang";
        }
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td  align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td></tr>";



        if (substr($kd, 0, 17) == $this->org_keu) {
            $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
        }

        $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>SURAT PERMINTAAN PEMBAYARAN GANTI UANG PERSEDIAAN </strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>(SPP - GU)</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>SURAT PENGANTAR</u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Kepada Yth:</td></tr>
                    <tr><td align=\"left\">$peng</td></tr>
                    <tr><td align=\"left\">OPD : $nm_skpd</td></tr>
                    <tr><td align=\"left\">Di <strong><u>" . strtoupper($daerah) . "</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"justify\">Dengan memperhatikan Peraturan Bupati Kabupaten Melawi $nogub, 
                    tentang Penjabaran APBD. Bersama ini kami mengajukan Surat Permintaan Pembayaran Ganti Uang Persediaan sebagai berikut:</td></tr>
                    <tr><td align=\"center\"></td></tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">a.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Urusan Pemerintahan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_urusan - $nm_urusan</td>
                                     
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">b.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">OPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_skpd - $nm_skpd</td>
                                     
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">c.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Tahun Anggaran</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$thn_ang</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">d.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Dasar Pengeluaran SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$spd</td>
                                     
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">e.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Sisa Dana SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $x1$sisa$y1</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($b) . ")</i></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">f.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Untuk Keperluan Bulan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$bln</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";

        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">g.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Pembayaran yang Diminta</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $nilai</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($a) . ")</i></td>
                                     </tr>
                                     ";

        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">h.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama " . ucwords($jabatan) . "</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama</td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">i.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama, Nomor Rekening Bank dan NPWP</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama_bank / $no_rek / $npwp</td>
                                     </tr>
                                     
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";

        $cRet .=       " </table>";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jdl2</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . " ,$tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

        $data['prev'] = $cRet;
        if ($print == '1') {

            //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($print == '0') {
            echo $cRet;
        }
    }

    function cetakspp1_3()
    {

        $spasi = $this->uri->segment(10);
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $client = $this->ClientModel->clientData('1');



        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->tukd_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->tukd_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd = '$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($PPTK == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode = 'PPTK' AND kd_skpd = '$kd'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                $jdl2 = 'MENGETAHUI :';
                $nip2 = 'NIP. ' . $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $tgl_spp = $this->tukd_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
        $no_spd = $this->tukd_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
        $tglspd = $this->tukd_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');

        $sqlgiat = "SELECT kd_sub_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_sub_kegiatan";
        $sqlgiat = $this->db->query($sqlgiat);
        foreach ($sqlgiat->result() as $rowgiat) {
            $giatspp = $rowgiat->kd_sub_kegiatan;
        }
        $giatspp = empty($giatspp) || $giatspp == '' ? '' : $giatspp;
        $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan,
        b.urusan1 as kd_bidang_urusan,
(select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=b.urusan1)as nm_bidang_urusan,
         a.bank,
                (SELECT rekening FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS no_rek,
                (SELECT npwp FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS npwp,
                a.no_spd,a.nilai,
                (SELECT SUM(a.nilai) FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.jns_beban = '5' and b.kd_skpd='$kd'
                    AND a.kd_sub_kegiatan='$giatspp' and b.tgl_spd <='$tglspd') AS spd,
                    (SELECT SUM(b.nilai) FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                    INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                    AND b.kd_sub_kegiatan='$giatspp'
                    AND a.jns_spp IN ('1','2','3','6') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp') AS spp
                FROM trhspp a INNER JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

        $query = $this->db->query($sql1);

        foreach ($query->result() as $row) {
            $kd_urusan = $row->kd_bidang_urusan;
            $nm_urusan = $row->nm_bidang_urusan;
            $kd_skpd = $row->kd_skpd;
            $nm_skpd = $row->nm_skpd;
            $spd = $row->no_spd;
            $tgl = $row->tgl_spp;
            $no_rek = $row->no_rek;
            $npwp = $row->npwp;
            $rekan = $row->nmrekan;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $bln = $this->tukd_model->getBulan($row->bulan);
            $nilai = number_format($row->nilai, "2", ",", ".");
            $nilai1 = $row->nilai;
            $nspd = $row->spd;
            $spp = $row->spp;
            $sis = $nspd - $spp;
            $si = $nspd - $spp;
            if ($si < 0) {
                $x1 = "(";
                $si = $si * -1;
                $y1 = ")";
            } else {
                $x1 = "";
                $y1 = "";
            }
            $sisa = number_format($si, "2", ",", ".");
            $a = $this->tukd_model->terbilang($nilai1);
            $b = $this->tukd_model->terbilang($sis);
            //echo($a);
        }
        $kodebank = $this->tukd_model->get_nama($kd, 'bank', 'ms_skpd', 'kd_skpd');
        $nama_bank = empty($kodebank) || $kodebank == '' ? '-' : $this->tukd_model->get_nama($kodebank, 'nama', 'ms_bank', 'kode');

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $nogub_susun  = $rowsc->nogub_susun;
            $nogub_perubahan  = $rowsc->nogub_perubahan;
        }
        $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
        if ($stsubah == 1) {
            $nogub = $nogub_perubahan;
        } else {
            $nogub = $nogub_susun;
        }

        $unit = $this->right($kd, 2);
        if ($unit == '01' || $kd = '1.20.03.00') {
            $peng = 'Pengguna Anggaran';
        } else {
            $peng = 'Kuasa Pengguna Anggaran';
        }

        $thn_ang       = $this->session->userdata('pcThang');
        if ($tanpa == 1) {
            $tanggal = "_______________________$thn_ang";
        }
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td></tr>";



        if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
            $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
        }

        $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>SURAT PERMINTAAN PEMBAYARAN TAMBAHAN UANG PERSEDIAAN</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>(SPP - TU)</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>SURAT PENGANTAR</u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Kepada Yth:</td></tr>
                    <tr><td align=\"left\">$peng</td></tr>
                    <tr><td align=\"left\">OPD : $nm_skpd</td></tr>
                    <tr><td align=\"left\">Di <strong><u>" . strtoupper($daerah) . "</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"justify\">Dengan memperhatikan Peraturan Bupati Kabupaten Melawi $nogub, 
                    tentang Penjabaran APBD. Bersama ini kami mengajukan Surat Permintaan Pembayaran Tambahan Uang Persediaan sebagai berikut:</td></tr>
                    <tr><td align=\"center\"></td></tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">a.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Urusan Pemerintahan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_urusan - $nm_urusan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">b.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">OPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_skpd - $nm_skpd</td>
                                     
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">c.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Tahun Anggaran</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$thn_ang</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">d.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Dasar Pengeluaran SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$spd</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">e.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Sisa Dana SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $x1$sisa$y1</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($b) . ")</i></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">f.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Untuk Keperluan Bulan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$bln</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";

        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">g.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Pembayaran yang Diminta</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $nilai</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($a) . ")</i></td>
                                     </tr>
                                     ";

        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">h.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama " . ucwords($jabatan) . "</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama</td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">i.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama, Nomor Rekening Bank dan NPWP</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama_bank / $no_rek / $npwp</td>
                                     </tr>
                                     
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";

        $cRet .=       " </table>";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . " ,$tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

        $data['prev'] = $cRet;
        if ($print == '1') {
            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($print == '0') {
            echo $cRet;
        }
    }

    ///////smua cetakan di bawah sini
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


    function cetakspp1_ls()
    {
        $client = $this->ClientModel->clientData('1');
        $spasi = $this->uri->segment(10);
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);

        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->rka_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd = '$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($PPTK == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode = 'PPTK' AND kd_skpd = '$kd'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                $jdl2 = 'MENGETAHUI :';
                $nip2 = 'NIP. ' . $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $tgl_spp = $this->rka_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
        $no_spd = $this->rka_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
        $tglspd = $this->rka_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');

        $sqlgiat = "SELECT kd_sub_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_sub_kegiatan";
        $sqlgiat = $this->db->query($sqlgiat);
        foreach ($sqlgiat->result() as $rowgiat) {
            $giatspp = $rowgiat->kd_sub_kegiatan;
        }
        $giatspp = empty($giatspp) || $giatspp == '' ? '' : $giatspp;

        if ($jns == 4) {

            $jns_bbn = $this->rka_model->get_nama($nomor, 'jns_beban', 'trhspp', 'no_spp');
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Gaji dan Tunjangan";
                    //$rekspd='510101';
                    break;
                case '2': //GU
                    $lcbeban = "Uang Kespeg";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                case '3': //TU
                    $lcbeban = "Uang Makan";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                case '4': //TU
                    $lcbeban = "Upah Pungut";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                case '5': //TU
                    $lcbeban = "Upah Pungut PBB";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                case '6': //TU
                    $lcbeban = "Upah Pungut PBB-KB PKB & BBN-KB ";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                case '7': //TU
                    $lcbeban = "Gaji & Tunjangan";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                case '8': //TU
                    $lcbeban = "Tunjangan Transport";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                case '9': //TU
                    $lcbeban = "Tunjangan Lainnya";
                    //$rekspd=$this->rka_model->get_nama($nomor,'kd_rek5','trdspp','no_spp');
                    break;
                default:
                    $lcbeban = "LS";
            }

            $sqlrek = "SELECT TOP 1 kd_rek6 FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd' ORDER BY kd_rek6";
            $sqlrek = $this->db->query($sqlrek);
            foreach ($sqlrek->result() as $rowrek) {
                $xrekspd     = $rowrek->kd_rek6;
            }
            $rekspd1 = $this->left($xrekspd, 6);
            if ($rekspd1 == '510101') {
                $rekspd = '510101';
            } else {
                $rekspd = $xrekspd;
            }


            if ($rekspd == '510101' || $rekspd = '510105') {
                $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank,
                no_rek AS no_rek,
                a.npwp AS npwp,
                    a.no_spd,
                    SUM(z.nilai) as nilai,
                    (SELECT SUM(a.nilai) FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.kd_skpd='$kd'
                    and b.tgl_spd <='$tglspd') AS spd,
                    (SELECT SUM(b.nilai) FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                    INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                    AND a.jns_spp='4' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp') AS spp 
                    FROM trhspp a INNER JOIN trdspp z on a.no_spp = z.no_spp and a.kd_skpd = z.kd_skpd
                    INNER JOIN ms_bidang_urusan b 
                    ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor'  AND a.kd_skpd='$kd'
                    GROUP BY a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan,a.no_rek,a.npwp,a.jns_beban,b.kd_bidang_urusan,b.nm_bidang_urusan,a.bank,a.no_spd";
            } else {
                $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank,
                    no_rek AS no_rek,
                    npwp AS npwp,
                    a.no_spd,
                    SUM(z.nilai) as nilai,
                    (SELECT SUM(a.nilai) FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.kd_skpd='$kd'
                    and b.tgl_spd <='$tglspd' AND a.kd_rek6='$rekspd') AS spd,
                    (SELECT SUM(b.nilai) FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                    INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                    AND a.jns_spp='4' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp' AND b.kd_rek6='$rekspd') AS spp 
                    FROM trhspp a INNER JOIN trdspp z on a.no_spp = z.no_spp and a.kd_skpd = z.kd_skpd
                    INNER JOIN ms_bidang_urusan b 
                    ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND z.kd_rek6='$rekspd' AND a.kd_skpd='$kd'
                    GROUP BY a.no_spp,
                    a.tgl_spp, a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan,a.no_rek,a.npwp,a.jns_beban,b.kd_bidang_urusan,b.nm_bidang_urusan,a.bank,a.no_spd";
            }
            $query = $this->db->query($sql1);
            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $bank = $row->bank;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $jns_bbn = $row->jns_beban;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal = $this->tanggal_format_indonesia($tgl);
                $bln = $this->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $nspd = $row->spd;
                $spp = $row->spp;
                $sis = $nspd - $spp;
                // echo $nspd.'-'.$spp.'-'.$sis;
                $si = $nspd - $spp;
                if ($si < 0) {
                    $x1 = "(";
                    $si = $si * -1;
                    $y1 = ")";
                } else {
                    $x1 = "";
                    $y1 = "";
                }
                $sisa = number_format($si, "2", ",", ".");
                $a = $this->tukd_model->terbilang($nilai1);
                $b = $this->tukd_model->terbilang($sis);
                //echo($a);
            }
            $kodebank = $bank;
            $nama_bank = empty($kodebank) || $kodebank == '' ? '-' : $this->rka_model->get_nama($kodebank, 'nama', 'ms_bank', 'kode');

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
                $nogub_susun  = $rowsc->nogub_susun;
                $nogub_perubahan  = $rowsc->nogub_perubahan;
            }
            $stsubah = $this->rka_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
            if ($stsubah == 1) {
                $nogub = $nogub_perubahan;
            } else {
                $nogub = $nogub_susun;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }

            $unit = $this->right($kd, 2);
            if ($unit == '01' || $kd = '1.20.03.00') {
                $peng = 'Pengguna Anggaran';
            } else {
                $peng = 'Kuasa Pengguna Anggaran';
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td align=\"center\" style=\"font-size:17px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\"><strong>" . strtoupper($nm_skpd) . "</strong></pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . $client->pem . " " . $client->nm_kab . "</td></tr>
                    </table>
                    <hr width=\"100%\"> 
                    ";


            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>SURAT PERMINTAAN PEMBAYARAN LANGSUNG GAJI DAN TUNJANGAN</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>(SPP - " . strtoupper($lcbeban) . ")</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>SURAT PENGANTAR</u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Kepada Yth:</td></tr>
                    <tr><td align=\"left\">$peng</td></tr>
                    <tr><td align=\"left\">OPD : $nm_skpd</td></tr>
                    <tr><td align=\"left\">Di <strong><u>" . $client->pem . " " . $client->nm_kab . "</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"justify\">Dengan memperhatikan Peraturan Bupati Kabupaten Melawi $nogub, 
                    tentang Penjabaran APBD. Bersama ini kami mengajukan Surat Permintaan Pembayaran Langsung Barang dan Jasa sebagai berikut:</td></tr>
                    <tr><td align=\"center\"></td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">a.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Urusan Pemerintahan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_urusan - $nm_urusan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">b.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">OPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_skpd - $nm_skpd</td>
                                     
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">c.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Tahun Anggaran</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$thn_ang</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">d.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Dasar Pengeluaran SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$spd</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">e.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Sisa Dana SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $x1$sisa$y1</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($b) . ")</i></td>
                                     </tr>
                                     ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">f.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Untuk Keperluan Bulan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$bln</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">g.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Pembayaran yang Diminta</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $nilai</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($a) . ")</i></td>
                                     </tr>
                                     ";

            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">h.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama " . ucwords($jabatan) . "</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">i.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama, Nomor Rekening Bank dan NPWP</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama_bank / $no_rek / $npwp</td>
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($print == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        } else {

            /*
        $sql1="SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,b.kd_bidang_urusan,b.nm_bidang_urusan,a.no_spd,a.nilai,(SELECT SUM(x.nilai) FROM trdspd x inner join trhspd w on x.no_spd = w.no_spd WHERE w.jns_beban='52')
                AS spd,(SELECT SUM(z.nilai) FROM trdspp z INNER JOIN trhspp y ON z.no_spp = y.no_spp where y.no_spd=a.no_spd and z.no_spp <> a.no_spp) AS spp FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor'";
        */

            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.jns_beban, a.no_rek as no_rek_rek,a.npwp as npwp_rek,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank, 
                no_rek AS no_rek,
                a.bank as kd_bank,
                a.npwp AS npwp,
                ( SELECT nama FROM ms_bank WHERE kode=a.bank ) AS nama_bank_rek, 
                a.no_spd,a.nilai,
                (SELECT SUM(a.nilai) FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.jns_beban = '5' and b.kd_skpd='$kd'
                and b.tgl_spd <='$tglspd' AND a.kd_sub_kegiatan='$giatspp') AS spd,
                (SELECT SUM(b.nilai) FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                AND b.kd_sub_kegiatan='$giatspp'
                AND a.jns_spp IN ('1','2','3','6') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp') AS spp 
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
            $query = $this->db->query($sql1);
            //$query = $this->skpd_model->getAllc();

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $kbank = $row->kd_bank;
                $nama_bank_rek = $row->nama_bank_rek;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $no_rek_rek = $row->no_rek_rek;
                $npwp_rek = $row->npwp_rek;
                $jns_bbn = $row->jns_beban;
                $rekan = $row->nmrekan;
                $tanggal = $this->tanggal_format_indonesia($tgl);
                $bln = $this->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $nspd = $row->spd;
                $spp = $row->spp;
                $sis = $nspd - $spp;
                $si = $nspd - $spp;
                if ($si < 0) {
                    $x1 = "(";
                    $si = $si * -1;
                    $y1 = ")";
                } else {
                    $x1 = "";
                    $y1 = "";
                }
                $sisa = number_format($si, "2", ",", ".");
                $a = $this->tukd_model->terbilang($nilai1);
                $b = $this->tukd_model->terbilang($sis);
                //echo($a);
            }
            $kodebank = $this->rka_model->get_nama($kbank, 'bank', 'trhspp', 'bank');
            $nama_bank = empty($kodebank) || $kodebank == '' ? '-' : $this->rka_model->get_nama($kodebank, 'nama', 'ms_bank', 'kode');

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
                $nogub_susun  = $rowsc->nogub_susun;
                $nogub_perubahan  = $rowsc->nogub_perubahan;
            }
            $stsubah = $this->rka_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
            if ($stsubah == 1) {
                $nogub = $nogub_perubahan;
            } else {
                $nogub = $nogub_susun;
            }
            $unit = $this->right($kd, 2);
            if ($unit == '01' || $kd = '1.20.03.00') {
                $peng = 'Pengguna Anggaran';
            } else {
                $peng = 'Kuasa Pengguna Anggaran';
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }

            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Barang dan Jasa PNS";
                    break;
                case '2': //GU
                    $lcbeban = "Barang dan Jasa Non PNS";
                    break;
                case '3': //TU
                    $lcbeban = "Barang dan Jasa";
                    break;
                default:
                    $lcbeban = "LS";
            }


            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . FCPATH . "/image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:20px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . FCPATH . "/image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\"><b>" . strtoupper($nm_skpd) . "</b></pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . ucwords($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            if ($kd == '1.03.01.01') {
                $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>SURAT PERMINTAAN PEMBAYARAN LANGSUNG BARANG DAN JASA</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>(SPP - LS " . strtoupper($lcbeban) . ")</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>SURAT PENGANTAR</u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Kepada Yth:</td></tr>
                    <tr><td align=\"left\">$peng</td></tr>
                    <tr><td align=\"left\">OPD : $nm_skpd</td></tr>
                    <tr><td align=\"left\">Di <strong><u>" . strtoupper($daerah) . "</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"justify\">Dengan memperhatikan Peraturan Bupati Kabupaten Melawi $nogub
                    tentang Perubahan Peraturan Bupati Kabupaten Melawi No. 84 Tahun 2015  tentang Penjabaran APBD Tahun Anggaran $thn_ang. Bersama ini kami mengajukan Surat Permintaan Pembayaran Langsung Barang dan Jasa sebagai berikut:</td></tr>
                    <tr><td align=\"center\"></td></tr>
                  </table>";
            } else {
                $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>SURAT PERMINTAAN PEMBAYARAN LANGSUNG BARANG DAN JASA</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong>(SPP - LS " . strtoupper($lcbeban) . ")</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>SURAT PENGANTAR</u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Kepada Yth:</td></tr>
                    <tr><td align=\"left\">$peng</td></tr>
                    <tr><td align=\"left\">OPD : $nm_skpd</td></tr>
                    <tr><td align=\"left\">Di <strong><u>" . strtoupper($daerah) . "</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"justify\">Dengan memperhatikan Peraturan Bupati " . ucwords(strtolower($client->nm_kab)) . " Peraturan Kepala Daerah Nomor 81 tanggal 28 Desember 2020 tentang Penjabaran APBD Tahun Anggaran $thn_ang</td></tr>
                    <tr><td align=\"center\"></td></tr>
                  </table>";
            }
            $cRet .= "<table style=\"border-collapse:collapse; font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">a.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Urusan Pemerintahan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_urusan - $nm_urusan</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">b.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">OPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$kd_skpd - $nm_skpd</td>
                                     
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">c.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Tahun Anggaran</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$thn_ang</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">d.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Dasar Pengeluaran SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$spd</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">e.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Sisa Dana SPD</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $x1$sisa$y1</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";

            $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($b) . ")</i></td>
                                     </tr>
                                     ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">f.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Untuk Keperluan Bulan</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">$bln</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">g.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Jumlah Pembayaran yang Diminta</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"18%\">Rp. $nilai</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"right\"></td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">(terbilang)</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\"><i>( " . ucwords($a) . ")</i></td>
                                     </tr>
                                     ";
            if ($jns_bbn == 3) {
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">h.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama Pihak Ketiga</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$rekan</td>
                                     </tr>
                                     ";
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">i.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama, Nomor Rekening Bank dan NPWP</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama_bank_rek s/ $no_rek_rek / $npwp_rek</td>
                                     </tr>
                                     ";
            } else {
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">h.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama Bendahara Pengeluaran</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama</td>
                                     </tr>
                                     ";
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">i.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"38%\">Nama, Nomor Rekening Bank</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\">:</td>
                                     <td colspan = \"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"58%\">$nama_bank / $no_rek</td>
                                     </tr>
                                     ";
            }
            $cRet .=  " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($print == 1) {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == 0) {
                echo $cRet;
            }
        }
    }


    function cetakspp2()
    {
        $client = $this->ClientModel->clientData('1');
        $spasi = $this->uri->segment(10);
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $kd1 = substr($kd, 0, 17);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $nm_skpd = strtoupper($this->tukd_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd'));
        $alamat_skpd = $this->tukd_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->tukd_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($PPTK == '-') {
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
            $jdl2 = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode ='PPTK'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                $nip2 = 'NIP. ' . $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
                $jdl2 = 'MENGETAHUI :';
            }
        }
        $sqlgiat = "SELECT kd_sub_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_sub_kegiatan";
        $sqlgiat = $this->db->query($sqlgiat);
        foreach ($sqlgiat->result() as $rowgiat) {
            $giatspp = $rowgiat->kd_sub_kegiatan;
        }

        // JENIS SPP UP

        if ($jns == 1) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,b.urusan1 as kd_urusan,(select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=b.urusan1)as nm_urusan,
        a.no_spd,a.nilai FROM trhspp a INNER JOIN ms_skpd b 
                ON a.kd_skpd=b.kd_skpd  where a.no_spp='$nomor'";

            $query = $this->db->query($sql1);
            foreach ($query->result() as $row) {

                $kd_skpd = $row->kd_skpd;
                $nm_skpd = strtoupper($row->nm_skpd);
                $nilai = number_format($row->nilai, "0", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                $tgl = $row->tgl_spp;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                //echo($a);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd = '$kd' ";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
                $nogub_susun  = $rowsc->nogub_susun;
                $nogub_perubahan  = $rowsc->nogub_perubahan;
            }
            // $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
            $data1 = $this->cek_anggaran_model->cek_anggaran($kd);
            if ($data1 = "U1") {
                $nogub = $nogub_perubahan;
            } else {
                $nogub = $nogub_susun;
            }

            if ($jns == '1') {
                $nogub = 'Nomor 900/11 TAHUN 2023';
                $nip2 = '';
                $nama2 = '';
                $jabatan2  = '';
                $pangkat2  = '';
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td  align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td></tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr><td align=\"center\"><strong>SURAT PERMINTAAN PEMBAYARAN UANG PERSEDIAAN </strong></td></tr>
                    <tr><td align=\"center\"><strong>(SPP-UP)</strong></td></tr>                              
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"><strong>RINGKASAN</strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"left\">Berdasarkan Keputusan Bupati $nogub
                     tentang Penetapan Jumlah Uang Persediaan untuk OPD $nm_skpd sejumlah Rp $nilai</td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"left\">Terbilang: (" . ucwords($a) . ")</td></tr>                    
                  </table>";


            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . " ,$tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
            // $data['prev'] = $cRet;
            // $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }

        if ($jns == 2) {
            $tgl_spp = $this->tukd_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl_spp);
            $no_spd = $this->tukd_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
            //$no_spd='232';
            $tglspd = $this->tukd_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');
            $nmskpd = $this->tukd_model->get_nama($kd, 'nm_skpd', 'trhspp', 'kd_skpd');
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd = '$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }
            $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
            $stssempurna = $this->tukd_model->get_nama($kd, 'status_sempurna', 'trhrka', 'kd_skpd');
            if (($stsubah == 0) && ($stssempurna == 0)) {
                $field = 'nilai';
                $nodpa = $this->tukd_model->get_nama($kd, 'no_dpa', 'trhrka', 'kd_skpd');
                $tgl_dpa = $this->tukd_model->get_nama($kd, 'tgl_dpa', 'trhrka', 'kd_skpd');
            } else if (($stsubah == 0) && ($stssempurna == 1)) {
                $nodpa = $this->tukd_model->get_nama($kd, 'no_dpa_sempurna', 'trhrka', 'kd_skpd');
                $tgl_dpa = $this->tukd_model->get_nama($kd, 'tgl_dpa_sempurna', 'trhrka', 'kd_skpd');
                $field = 'nilai_sempurna';
            } else {
                $nodpa = $this->tukd_model->get_nama($kd, 'no_dpa_ubah', 'trhrka', 'kd_skpd');
                $tgl_dpa = $this->tukd_model->get_nama($kd, 'tgl_dpa_ubah', 'trhrka', 'kd_skpd');
                $field = 'nilai_ubah';
            }

            $sqlang = "SELECT sum($field) as nilai FROM trdrka where substring(kd_rek6,1,2)='52' and kd_skpd='$kd' AND jns_ang='$data1'";
            $sqlangg = $this->db->query($sqlang);
            foreach ($sqlangg->result() as $rows) {
                $nilai_ang    = number_format($rows->nilai, "2", ".", ",");
                $nilai_angg    = $rows->nilai;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td></tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>SURAT PERMINTAAN PEMBAYARAN GANTI UANG PERSEDIAAN</b></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>(SPP - GU)</b></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>RINGKASAN</u></strong></td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN DPA/DPPA/DPPAL-OPD</b></td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Jumlah dana DPA/DPPA/DPPAL-OPD </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"center\">$nilai_ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(I)</td>
                                     </tr>";
            $cRet    .= "  <tr><td colspan=\"5\" style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN SPD</b></td></tr> ";
            $cRet    .= " <tr><td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"center\">No. Urut</td>                                     
                                     <td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"38%\" align=\"center\">Nomor SPD</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"32%\" align=\"center\">Tanggal SPD</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"center\">Jumlah Dana</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";

            $sql1 = "SELECT a.no_spd, b.tgl_spd, SUM(a.nilai) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.jns_beban = '52' and b.kd_skpd='$kd'
                            and b.tgl_spd <='$tglspd' GROUP BY a.no_spd, b.tgl_spd";
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                $totalspd = number_format($lntotal, "2", ".", ",");

                $no = $row->no_spd;
                $tgl = $row->tgl_spd;
                $tgl_spd = $this->tukd_model->tanggal_format_indonesia($tgl);
                $nilai = number_format($row->nilai, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"7%\" align=\"center\">$lcno</td>                                     
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"25%\">$no</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"3%\">$tgl_spd</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-right: none;\" width=\"3%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";
            }
            $sisaspd = $nilai_angg - $lntotal;
            $blmspd = number_format($sisaspd, "2", ".", ",");
            $sqlspptls = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                  AND a.jns_spp='6' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
            $sqlsppls = $this->db->query($sqlspptls);
            foreach ($sqlsppls->result() as $row) {
                $jns6     = $row->nilai;
                $jns6_    = number_format($jns6, "2", ".", ",");
            }
            $sqlspptup = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                  AND a.jns_spp IN ('1','2') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";

            $sqlsppup = $this->db->query($sqlspptup);
            foreach ($sqlsppup->result() as $row) {
                $jns1     = $row->nilai;
                $jns1_    = number_format($jns1, "2", ".", ",");
            }

            $sqlsppttu = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                  AND a.jns_spp='3' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";


            $sqlspptu = $this->db->query($sqlsppttu);
            foreach ($sqlspptu->result() as $row) {
                $jns3     = $row->nilai;
                $jns3_    = number_format($jns3, "2", ".", ",");
            }
            $jmlblj = $jns6 + $jns1 + $jns3;
            $totblj = number_format($jmlblj, "2", ".", ",");
            $sisa   = $lntotal - $jmlblj;
            $sisaspp   = number_format($sisa, "2", ".", ",");

            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\"> $totalspd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(II)</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>Sisa dana yang belum di SPD-kan (I-II)</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\"> $blmspd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN BELANJA</b></td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja UP/GU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns1_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja TU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns3_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS Pembayaran Gaji dan Tunjangan</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">" . number_format(0, "2", ".", ",") . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS Pengadaan Barang dan Jasa</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns6_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$totblj</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(III)</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"2%\" align=\"right\"><i>Sisa SPD yang telah diterbitkan, belum dibelanjakan (II-III)</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$sisaspp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jdl2</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }


        // --------TU
        if ($jns == 3) {
            $tgl_spp = $this->tukd_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl_spp);
            $no_spd = $this->tukd_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
            $tglspd = $this->tukd_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');
            $nmskpd = $this->tukd_model->get_nama($kd, 'nm_skpd', 'trhspp', 'kd_skpd');
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd = '$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }
            $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
            $stssempurna = $this->tukd_model->get_nama($kd, 'status_sempurna', 'trhrka', 'kd_skpd');
            if (($stsubah == 0) && ($stssempurna == 0)) {
                $field = 'nilai';
            } else if (($stsubah == 0) && ($stssempurna == 1)) {
                $field = 'nilai_sempurna';
            } else {
                $field = 'nilai_ubah';
            }

            $sqlang = "SELECT sum($field) as nilai FROM trdrka where kd_sub_kegiatan='$giatspp' and left(kd_skpd,17)=left('$kd',17)";
            $sqlangg = $this->db->query($sqlang);
            foreach ($sqlangg->result() as $rows) {
                $nilai_ang    = number_format($rows->nilai, "2", ".", ",");
                $nilai_angg    = $rows->nilai;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td></tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>SURAT PERMINTAAN PEMBAYARAN TAMBAHAN UANG PERSEDIAAN</b></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>(SPP - TU)</b></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>RINGKASAN</u></strong></td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN DPA/DPPA/DPPAL-OPD</b></td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Jumlah dana DPA/DPPA/DPPAL-OPD </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"center\">$nilai_ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(I)</td>
                                     </tr>";
            $cRet    .= "  <tr><td colspan=\"5\" style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN SPD</b></td></tr> ";
            $cRet    .= " <tr><td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"center\">No. Urut</td>                                     
                                     <td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"38%\" align=\"center\">Nomor SPD</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"32%\" align=\"center\">Tanggal SPD</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"center\">Jumlah Dana</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";

            $sql1 = "SELECT a.no_spd, a.kd_sub_kegiatan, b.tgl_spd, SUM(a.nilai) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE b.jns_beban = '5' 
                                AND a.kd_sub_kegiatan='$giatspp' and b.kd_skpd='$kd'
                            and b.tgl_spd <='$tglspd' GROUP BY a.no_spd, a.kd_sub_kegiatan, b.tgl_spd";
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                $totalspd = number_format($lntotal, "2", ".", ",");

                $no = $row->no_spd;
                $tgl = $row->tgl_spd;
                $tgl_spd = $this->tukd_model->tanggal_format_indonesia($tgl);
                $nilai = number_format($row->nilai, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"7%\" align=\"center\">$lcno</td>                                     
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"25%\">$no</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"3%\">$tgl_spd</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-right: none;\" width=\"3%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";
            }
            $sisaspd = $nilai_angg - $lntotal;
            $blmspd = number_format($sisaspd, "2", ".", ",");
            $sqlspptls = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                AND b.kd_sub_kegiatan='$giatspp'
                                AND a.jns_spp='6' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
            $sqlsppls = $this->db->query($sqlspptls);
            foreach ($sqlsppls->result() as $row) {
                $jns6     = $row->nilai;
                $jns6_    = number_format($jns6, "2", ".", ",");
            }
            $sqlspptup = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                AND b.kd_sub_kegiatan='$giatspp'
                                AND a.jns_spp IN ('1','2') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";


            $sqlsppup = $this->db->query($sqlspptup);
            foreach ($sqlsppup->result() as $row) {
                $jns1     = $row->nilai;
                $jns1_    = number_format($jns1, "2", ".", ",");
            }

            $sqlsppttu = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                AND b.kd_sub_kegiatan='$giatspp'
                                AND a.jns_spp='3' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
            $sqlspptu = $this->db->query($sqlsppttu);
            foreach ($sqlspptu->result() as $row) {
                $jns3     = $row->nilai;
                $jns3_    = number_format($jns3, "2", ".", ",");
            }
            $jmlblj = $jns6 + $jns1 + $jns3;
            $totblj = number_format($jmlblj, "2", ".", ",");
            $sisa   = $lntotal - $jmlblj;
            $sisaspp   = number_format($sisa, "2", ".", ",");

            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\"> $totalspd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(II)</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>Sisa dana yang belum di SPD-kan (I-II)</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\"> $blmspd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN BELANJA</b></td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja UP/GU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns1_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja TU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns3_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS Pembayaran Gaji dan Tunjangan</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">" . number_format(0, "2", ".", ",") . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS Pengadaan Barang dan Jasa</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns6_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$totblj</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(III)</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"2%\" align=\"right\"><i>Sisa SPD yang telah diterbitkan, belum dibelanjakan (II-III)</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$sisaspp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }
        if ($jns == 4) {
            $jns_bbn = $this->tukd_model->get_nama($nomor, 'jns_beban', 'trhspp', 'no_spp');
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Gaji dan Tunjangan";
                    //$rekspd='510101';
                    break;
                case '2': //GU
                    $lcbeban = "Uang Kespeg";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                case '3': //TU
                    $lcbeban = "Uang Makan";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                case '4': //TU
                    $lcbeban = "Upah Pungut";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                case '5': //TU
                    $lcbeban = "Upah Pungut PBB";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                case '6': //TU
                    $lcbeban = "Upah Pungut PBB-KB PKB & BBN-KB ";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                case '7': //TU
                    $lcbeban = "Gaji & Tunjangan";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                case '8': //TU
                    $lcbeban = "Tunjangan Transport";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                case '9': //TU
                    $lcbeban = "Tunjangan Lainnya";
                    //$rekspd=$this->tukd_model->get_nama($nomor,'kd_rek6','trdspp','no_spp');
                    break;
                default:
                    $lcbeban = "LS";
            }

            $sqlrek = "SELECT TOP 1 kd_rek6 FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd' ORDER BY kd_rek6";
            $sqlrek = $this->db->query($sqlrek);
            foreach ($sqlrek->result() as $rowrek) {
                $xrekspd     = $rowrek->kd_rek6;
            }
            $rekspd1 = $this->left($xrekspd, 6);
            if ($rekspd1 == '510101') {
                $rekspd = '510101';
            } else {
                $rekspd = $xrekspd;
            }

            $tgl_spp = $this->tukd_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl_spp);
            $no_spd = $this->tukd_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
            $tglspd = $this->tukd_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');
            $nmskpd = $this->tukd_model->get_nama($kd, 'nm_skpd', 'trhspp', 'kd_skpd');
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd = '$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }
            $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');

            $stssempurna = $this->tukd_model->get_nama($kd, 'status_sempurna', 'trhrka', 'kd_skpd');
            if (($stsubah == 0 || $stsubah == null || $stsubah == '') && ($stssempurna == 0 || $stssempurna == null || $stssempurna == '')) {
                $field = 'nilai';
            } else if (($stsubah == 0) && ($stssempurna == 1)) {
                $field = 'nilai_sempurna';
            } else {
                $field = 'nilai_ubah';
            }
            // if($rekspd=='510101'){
            // $sqlang="SELECT sum($field) as nilai FROM trdrka where substring(kd_rek6,1,6)='510101' and kd_skpd='$kd'";
            // } else {
            $sqlang = "SELECT sum($field) as nilai FROM trdrka where  kd_skpd='$kd'";
            // }
            $sqlangg = $this->db->query($sqlang);
            foreach ($sqlangg->result() as $rows) {
                $nilai_ang    = number_format($rows->nilai, "2", ".", ",");
                $nilai_angg    = $rows->nilai;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td></tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\">SURAT PERMINTAAN PEMBAYARAN LANGSUNG GAJI DAN TUNJANGAN</td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\">(SPP - " . strtoupper($lcbeban) . ")</td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:18px\"><strong><u>RINGKASAN</u></strong></td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\">RINGKASAN DPA/DPPA/DPPAL-OPD</td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Jumlah dana DPA/DPPA/DPPAL-OPD </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\">$nilai_ang</td></tr>";
            $cRet    .= "  <tr><td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\">RINGKASAN SPD</td></tr> ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"center\">No. Urut</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"38%\" align=\"center\">Nomor SPD</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"32%\" align=\"center\">Tanggal SPD</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"center\">Jumlah Dana</td></tr>";

            //$sql1="SELECT no_spd,tgl_spd,total as nilai from trhspd where jns_beban='51' and kd_skpd='$kd'";
            if ($rekspd1 == '510101') {
                $sql1 = "SELECT a.no_spd, b.tgl_spd, SUM(a.nilai) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE 
                    --substring(kd_rek6,1,6)='510101' and 
                    b.kd_skpd='$kd'
                            and b.tgl_spd <='$tglspd' GROUP BY a.no_spd, b.tgl_spd";
            } else {
                $sql1 = "SELECT a.no_spd, b.tgl_spd, SUM(a.nilai) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd WHERE kd_rek6='$rekspd' and b.kd_skpd='$kd'
                            and b.tgl_spd <='$tglspd' GROUP BY a.no_spd, b.tgl_spd";
            }
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                $totalspd = number_format($lntotal, "2", ".", ",");

                $no = $row->no_spd;
                $tgl = $row->tgl_spd;
                $tgl_spd = $this->tukd_model->tanggal_format_indonesia($tgl);
                $nilai = number_format($row->nilai, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"7%\" align=\"center\">$lcno</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"25%\">$no</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\">$tgl_spd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$nilai</td>
                                     </tr>
                                     ";
            }
            $sisaspd = $nilai_angg - $lntotal;
            $blmspd = number_format($sisaspd, "2", ".", ",");

            $sqlspptls = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                  AND a.jns_spp='4' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";

            $sqlsppls = $this->db->query($sqlspptls);
            foreach ($sqlsppls->result() as $row) {
                $jns4     = $row->nilai;
                $jns4_    = number_format($jns4, "2", ".", ",");
            }


            $sqlspptls = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                  AND a.jns_spp='6' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
            $sqlsppls = $this->db->query($sqlspptls);
            foreach ($sqlsppls->result() as $row) {
                $jns6     = $row->nilai;
                $jns6_    = number_format($jns6, "2", ".", ",");
            }
            $sqlspptup = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                  AND a.jns_spp IN ('1','2') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";

            $sqlsppup = $this->db->query($sqlspptup);
            foreach ($sqlsppup->result() as $row) {
                $jns1     = $row->nilai;
                $jns1_    = number_format($jns1, "2", ".", ",");
            }

            $sqlsppttu = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                  AND a.jns_spp='3' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";


            $sqlspptu = $this->db->query($sqlsppttu);
            foreach ($sqlspptu->result() as $row) {
                $jns3     = $row->nilai;
                $jns3_    = number_format($jns3, "2", ".", ",");
            }
            $jmlblj = $jns6 + $jns1 + $jns3 + $jns4;
            $totblj = number_format($jmlblj, "2", ".", ",");
            $sisa   = $lntotal - $jmlblj;
            $sisaspp   = number_format($sisa, "2", ".", ",");

            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\"> $totalspd</td></tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>Sisa dana yang belum di SPD-kan</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\"> $blmspd</td></tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\"></td>&nbsp;</tr>";
            $cRet    .= " <tr><td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\">RINGKASAN BELANJA</td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja UP/GU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\">" . number_format(0, "2", ".", ",") . "</td></tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja TU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\">" . number_format(0, "2", ".", ",") . "</td></tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS Pembayaran Gaji dan Tunjangan</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\">$jns4_</td></tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS Pengadaan Barang dan Jasa</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\">" . number_format(0, "2", ".", ",") . "</td></tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"18%\" align=\"right\">$totblj</td></tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"2%\" align=\"left\"><i>Sisa SPD yang telah, belum dibelanjakan</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"18%\" align=\"right\">$sisaspp</td></tr>";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }
        if ($jns == 6) {
            $tgl_spp = $this->tukd_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl_spp);
            $no_spd = $this->tukd_model->get_nama($nomor, 'no_spd', 'trhspp', 'no_spp');
            $tglspd = $this->tukd_model->get_nama($no_spd, 'tgl_spd', 'trhspd', 'no_spd');
            $nmskpd = $this->tukd_model->get_nama($kd, 'nm_skpd', 'trhspp', 'kd_skpd');
            $sqlhspp = "select a.*,(select nama as nama_bank from ms_bank where kode=a.bank ) as nama_bank from trhspp a where a.no_spp='$nomor'";
            $sqlspp = $this->db->query($sqlhspp);
            foreach ($sqlspp->result() as $rows) {
                $prog  = $rows->nm_program;
                $kdgiat  = $rows->kd_sub_kegiatan;
                $giat  = $rows->nm_sub_kegiatan;
                $rekan = $rows->nmrekan;
                $bentuk = substr($rekan, 0, 2);
                $dir   = $rows->pimpinan;
                $nmskpd = $rows->nm_skpd;
                $kontrak = $rows->kontrak;
                $alamat = $rows->alamat;
                $tgl_mulai = $rows->tgl_mulai;
                $tgl_akhir = $rows->tgl_akhir;
                $lanjut = $rows->lanjut;
                $kbank = $rows->bank;
                $rek    = $rows->no_rek;
                $ket    = ltrim($rows->keperluan);
                $nm_bank = $rows->nama_bank;
            }
            if ($lanjut == 1) {
                $lanjut = 'Iya';
            } else {
                $lanjut = 'Bukan';
            }

            if ($kontrak == '') {
                $lanjut = " ";
                $tgl_mulai = "-";
                $tgl_akhir = "-";
            } else {
                $tgl_mulai = $this->tukd_model->tanggal_format_indonesia($tgl_mulai);
                $tgl_akhir = $this->tukd_model->tanggal_format_indonesia($tgl_akhir);
            }

            //$sqlang="SELECT sum(nilai) as nilai,sum(nilai_ubah) as nilai_ubah FROM trdrka where kd_sub_kegiatan='$kdgiat'";
            $stsubah = $this->tukd_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
            $stssempurna = $this->tukd_model->get_nama($kd, 'status_sempurna', 'trhrka', 'kd_skpd');
            if (($stsubah == 0) && ($stssempurna == 0)) {
                $field = 'nilai';
                $nodpa = $this->tukd_model->get_nama($kd, 'no_dpa', 'trhrka', 'kd_skpd');
                $tgl_dpa = $this->tukd_model->get_nama($kd, 'tgl_dpa', 'trhrka', 'kd_skpd');
            } else if (($stsubah == 0) && ($stssempurna == 1)) {
                $nodpa = $this->tukd_model->get_nama($kd, 'no_dpa', 'trhrka', 'kd_skpd');
                $tgl_dpa = $this->tukd_model->get_nama($kd, 'tgl_dpa', 'trhrka', 'kd_skpd');
                $field = 'nilai_sempurna';
            } else {
                $nodpa = $this->tukd_model->get_nama($kd, 'no_dpa_ubah', 'trhrka', 'kd_skpd');
                $tgl_dpa = $this->tukd_model->get_nama($kd, 'tgl_dpa_ubah', 'trhrka', 'kd_skpd');
                $field = 'nilai_ubah';
            }
            $tgl_dpa = empty($tgl_dpa) || $tgl_dpa == '1900-01-01' || $tgl_dpa == '' ? '-' : $this->tukd_model->tanggal_format_indonesia($tgl_dpa);
            $sqlang = "SELECT sum($field) as nilai FROM trdrka where left(kd_rek6,1)='5' and left(kd_skpd,17)='$kd1'";
            $sqlangg = $this->db->query($sqlang);
            foreach ($sqlangg->result() as $rows) {
                $nilai_ang    = number_format($rows->nilai, "2", ".", ",");
                $nilai_angg    = $rows->nilai;
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd = '$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }

            $jns_bbn = $this->tukd_model->get_nama($nomor, 'jns_beban', 'trhspp', 'no_spp');
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "PNS";
                    break;
                case '2': //GU
                    $lcbeban = "Non PNS";
                    break;
                case '3': //TU
                    $lcbeban = "Barang dan Jasa";
                    break;
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td></tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\">SURAT PERMINTAAN PEMBAYARAN LANGSUNG BARANG DAN JASA</td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\">(SPP - " . strtoupper($lcbeban) . ")</td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:14px\"><strong><u>RINGKASAN</u></strong></td></tr>
                  </table>";
            if ($jns_bbn == 3) {
                $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\"> ";

                $cRet    .= " <tr><td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\">RINGKASAN KEGIATAN</td></tr> ";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >1. Program </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;font-size:12px\" width=\"18%\">: $prog</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >2. Kegiatan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black; \" width=\"18%\">: $giat </td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >3. Nomor dan Tanggal DPA/DPPA/DPPAL-OPD </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nodpa - $tgl_dpa</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >4. Nama Perusahaan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $rekan</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >5. Bentuk Perusahaan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $bentuk</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >6. Alamat Perusahaan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $alamat</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >7. Nama Pimpinan Perusahaan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $dir</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >8. Nama dan Nomor Rekening Bank </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nm_bank - $rek</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >9. Nomor Kontrak </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $kontrak</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >10. Kegiatan Lanjutan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $lanjut</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >11. Waktu Pelaksanaan Kegiatan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $tgl_mulai s/d $tgl_akhir</td></tr>";
                $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"2%\" align=\"left\" >12. Deskripsi Pekerjaan </td>
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;font-size:8 px;border-right: solid 1px black;\" width=\"18%\">: <pre>$ket</pre></td></tr>";
            } else {
                $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\"> ";
            }
            $cRet    .= " <tr><td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN DPA/DPPA/DPPAL-OPD</b></td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Jumlah dana DPA/DPPA/DPPAL-OPD </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"center\">$nilai_ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(I)</td>
                                     </tr>";
            $cRet    .= "  <tr><td colspan=\"5\" style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN SPD</b></td></tr> ";
            $cRet    .= " <tr><td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"center\">No. Urut</td>                                     
                                     <td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"38%\" align=\"center\">Nomor SPD</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-bottom: none;\" width=\"32%\" align=\"center\">Tanggal SPD</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"center\">Jumlah Dana</td>
                                     <td style=\"valign:center;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            //$sql1="SELECT a.no_spd,a.tgl_spd,b.nilai FROM trdspd b INNER JOIN trhspd a ON b.no_spd=a.no_spd
            //                           WHERE b.kd_sub_kegiatan='$kdgiat' AND kd_skpd='$kd'";
            $sql1 = "SELECT a.no_spd, b.tgl_spd, SUM(a.nilai) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd = b.no_spd 
                            WHERE b.jns_beban = '5' and left(b.kd_skpd,17)='$kd1'
                            AND a.kd_sub_kegiatan='$giatspp'
                            and b.tgl_spd <='$tglspd' GROUP BY a.no_spd, b.tgl_spd";
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                $totalspd = number_format($lntotal, "2", ".", ",");

                $no = $row->no_spd;
                $tgl = $row->tgl_spd;
                $tgl_spd = $this->tukd_model->tanggal_format_indonesia($tgl);
                $nilai = number_format($row->nilai, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"7%\" align=\"center\">$lcno</td>                                     
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"25%\">$no</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" width=\"3%\">$tgl_spd</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-right: none;\" width=\"3%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:center;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>
                                     ";
            }
            $sisaspd = $nilai_angg - $lntotal;
            $blmspd = number_format($sisaspd, "2", ".", ",");
            $sqlspptls = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                AND b.kd_sub_kegiatan='$giatspp'
                                AND a.jns_spp='6' AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
            $sqlsppls = $this->db->query($sqlspptls);
            foreach ($sqlsppls->result() as $row) {
                $jns6     = $row->nilai;
                $jns6_    = number_format($jns6, "2", ".", ",");
            }
            $sqlspptup = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                AND b.kd_sub_kegiatan='$giatspp'
                                AND a.jns_spp IN ('1','2') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";


            $sqlsppup = $this->db->query($sqlspptup);
            foreach ($sqlsppup->result() as $row) {
                $jns1     = $row->nilai;
                $jns1_    = number_format($jns1, "2", ".", ",");
            }
            $sqlsppttu = "SELECT SUM(b.nilai)AS nilai  FROM trdspp b INNER JOIN trhspp a ON b.no_spp=a.no_spp and b.kd_skpd = a.kd_skpd 
                                INNER JOIN trhsp2d c ON a.no_spp = c.no_spp WHERE a.kd_skpd='$kd' 
                                AND b.kd_sub_kegiatan='$giatspp'
                                AND a.jns_spp IN ('3') AND a.no_spp != '$nomor' AND c.tgl_sp2d <='$tgl_spp'";
            $sqlspptu = $this->db->query($sqlsppttu);
            foreach ($sqlspptu->result() as $row) {
                $jns3     = $row->nilai;
                $jns3_    = number_format($jns3, "2", ".", ",");
            }
            $jmlblj = $jns6 + $jns1 + $jns3;
            $totblj = number_format($jmlblj, "2", ".", ",");
            $sisa   = $lntotal - $jmlblj;
            $sisaspp   = number_format($sisa, "2", ".", ",");
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\"> $totalspd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(II)</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>Sisa dana yang belum di SPD-kan (I-II)</i> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\"> $blmspd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\"><b>RINGKASAN BELANJA</b></td></tr> ";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja UP/GU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns1_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja TU</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns3_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS GAJI</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">" . number_format(0, "2", ".", ",") . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"left\">Belanja LS Pengadaan Barang dan Jasa</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$jns6_</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\" align=\"right\"><i>JUMLAH</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$totblj</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">(III)</td>
                                     </tr>";
            $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"2%\" align=\"right\"><i>Sisa SPD yang telah diterbitkan, belum dibelanjakan (II-III)</i></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-right: none;\" width=\"18%\" align=\"right\">$sisaspp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-left: none;\" width=\"3%\" align=\"right\">&nbsp;</td>
                                     </tr>";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }
    }


    function cetakspp3()
    {
        $client = $this->ClientModel->clientData('1');
        $spasi   = $this->uri->segment(10);
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $jns = $this->uri->segment(6);
        // echo($jns);
        $tanpa   = $this->uri->segment(11);
        $nm_skpd = strtoupper($this->tukd_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd'));
        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->tukd_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->tukd_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($kd);
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $kdttd = '';
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $PPKD = str_replace('123456789', ' ', $this->uri->segment(9));
        $pakpa = str_replace('123456789', ' ', $this->uri->segment(11));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd='$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($PPTK == '-') {
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
            $jdl2 = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode = 'PPTK' AND kd_skpd='$kd'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                $nip2 = 'NIP. ' . $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
                $jdl2 = 'MENGETAHUI :';
            }
        }
        $sqlttd3 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPKD' and kode = 'BUD' ";
        $sqlttd3 = $this->db->query($sqlttd3);
        foreach ($sqlttd3->result() as $rowttd3) {
            $nip3   = $rowttd3->nip;
            $nama3  = $rowttd3->nm2;
            $jabatan3  = $rowttd3->jab;
            $pangkat3  = $rowttd3->pangkat;
        }

        $sqlgiat = "SELECT kd_sub_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_sub_kegiatan";
        $sqlgiat = $this->db->query($sqlgiat);
        foreach ($sqlgiat->result() as $rowgiat) {
            $giatspp = $rowgiat->kd_sub_kegiatan;
        }
        if ($jns == 2 || $jns == 7) {
            $pakpa = str_replace('123456789', ' ', $this->uri->segment(12));
        } else {
            $pakpa = str_replace('123456789', ' ', $this->uri->segment(11));
        }

        $aa = "SELECT LTRIM(RTRIM(kode)) as kode, nip, nama, pangkat from ms_ttd WHERE nip ='$pakpa'";
        $xttd = $this->db->query($aa)->row();
        $kdttd = $xttd->kode;
        $nipttd = $xttd->nip;
        $nmttd = $xttd->nama;
        $pangkatttd = $xttd->pangkat;
        $jbtttd = '';

        // echo $pangkatttd;die();

        if ($kdttd == 'PA') {
            $jbtttd = 'Pengguna Anggaran';
        } else {
            $jbtttd = 'Kuasa Pengguna Anggaran';
        }



        if ($jns == 1) {

            $sqltgl = "SELECT * FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
            $sqltgl = $this->db->query($sqltgl);
            foreach ($sqltgl->result() as $rowtg) {
                $tgl = $rowtg->tgl_spp;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($rowtg->bulan);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
               <tr>
                   <td rowspan=\"5\" align=\"right\">
                   <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"75\" height=\"100\" />
                   </td>
                   <td align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>";



            if (substr($kd, 0, 17) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
               <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
               <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
               <tr><td align=\"right\">" . strtoupper($daerah) . " &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;
               &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
               &nbsp;&nbsp; &nbsp;&nbsp;  &nbsp; &nbsp; &nbsp;  Kode Pos: $kodepos &nbsp; &nbsp;</td>  </tr>
               </table>
               <hr  width=\"100%\"> 
               ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
               <tr><td align=\"center\"><strong>SURAT PERMINTAAN PEMBAYARAN UANG PERSEDIAAN </strong></td></tr>
               <tr><td align=\"center\"><strong>(SPP-UP)</strong></td></tr>                              
               <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
               <tr><td align=\"center\"></td></tr>
               <tr><td align=\"center\"></td></tr>
               <tr><td align=\"center\"></td></tr>
               <tr><td align=\"center\"></td></tr>
               <tr><td align=\"center\"><strong>RINCIAN RENCANA PENGGUNA ANGGARAN</strong></td></tr>
               <tr><td align=\"center\"></td></tr>
               <tr><td align=\"center\"></td></tr>                    
               
             </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                <thead>                       
                   <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>No Urut</b></td>                            
                       <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>
                       <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>Uraian</b></td>
                       <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah</b></td>                                                    
                </thead> 
                      
                   ";
            $sql1 = "SELECT kd,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai from trdspp where no_spp='$nomor' order by kd";

            $query = $this->db->query($sql1);
            //$query = $this->skpd_model->getAllc();
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                //$no=$row->kd + 1;
                $nilai = number_format($row->nilai, "2", ",", ".");
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$lcno</td>                                     
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$kd</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$nm_skpd</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                </tr>
                                ";
            }

            $sqltp = "SELECT SUM(nilai) AS tot FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd'";
            $sqlp = $this->db->query($sqltp);
            foreach ($sqlp->result() as $rowp) {
                $totp = number_format($rowp->tot, "2", ",", ".");


                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"center\">Jumlah</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\">$totp</td>                                     
                                </tr>";
            }
            $cRet    .= " <tr><td colspan=\"4\" style=\"border-right:hidden;border-bottom:hidden;border-left:hidden;\" align=\"left\">
                   Terbilang : <b><i>" . ucwords($this->tukd_model->terbilang($rowp->tot)) . "</i></b></td>                                     
                                                                  
                                </tr>";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
               <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
               <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
               <tr><td align=\"center\" width=\"25%\"></td>                    
               <td align=\"center\" width=\"25%\">$daerah, $tanggal</td></tr>
               <tr><td align=\"center\" width=\"25%\"></td>                    
               <td align=\"center\" width=\"25%\">$jabatan</td></tr>
               <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
               <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
               <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
               <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
               <tr><td align=\"center\" width=\"25%\"><b><u></u></b><br>
                <br>
                </td>                    
               <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                $pangkat <br>
                NIP. $nip</td></tr>                              
               <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
               <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
             </table>";
            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }

        // SPP GU
        if ($jns == 2) {

            $sqltgl = "SELECT * FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
            $sqltgl = $this->db->query($sqltgl);
            foreach ($sqltgl->result() as $rowtg) {
                $nmskpd = $rowtg->nm_skpd;
                $tgl = $rowtg->tgl_spp;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($rowtg->bulan);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">" . strtoupper($nm_org) . "</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>SURAT PERMINTAAN PEMBAYARAN GANTI UANG PERSEDIAAN</b></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>(SPP - GU)</b></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong><u>RINCIAN</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"$spasi\" cellpadding=\"0\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>No Urut</b></td>                            
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>Uraian</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah</b></td>                                                    
                     </thead> 
                           
                        ";

            //$sql1="select kd,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai from trdspp where no_spp='$nomor' order by kd";
            $sql1 = "SELECT SUM(nilai) AS nilai FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd'";

            $query = $this->db->query($sql1);
            //$query = $this->skpd_model->getAllc();
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                //$no=$row->kd + 1;
                // $giat=$row->kd_sub_kegiatan;
                // $rek=$row->kd_rek6;
                //$reke=$this->dotrek($rek);                    
                //$uraian=$row->nm_rek6;
                $nilai = number_format($row->nilai, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$lcno</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$kd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$nm_skpd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     </tr>
                                     ";
            }

            $sqltp = "SELECT SUM(nilai) AS tot FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd'";
            $sqlp = $this->db->query($sqltp);
            foreach ($sqlp->result() as $rowp) {
                $totp = number_format($rowp->tot, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"center\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"></td>                                     
                                     </tr>";

                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"right\"><b>JUMLAH</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"><b>$totp</b></td>                                     
                                     </tr>";
            }
            $cRet    .= " <tr><td colspan=\"4\" style=\"border-right:hidden;border-bottom:hidden;border-left:hidden;\" align=\"left\">
                        Terbilang : <b><i>" . ucwords($this->tukd_model->terbilang($rowp->tot)) . "</i></b></td>                                     
                                                                       
                                     </tr>";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align=\"center\" width=\"25%\">Mengetahui,</td>                    
                        <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">$jbtttd</td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"><b><u>$nmttd</u></b><br>
                            $pangkatttd 
                        </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                            $pangkat <br>
                        </td>
                     </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">NIP. $nipttd</td>                    
                        <td align=\"center\" width=\"25%\">NIP. $nip</td>
                    </tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }

        if ($jns == 3) {

            $sqltgl = "SELECT * FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
            $sqltgl = $this->db->query($sqltgl);
            foreach ($sqltgl->result() as $rowtg) {
                $nmskpd = $rowtg->nm_skpd;
                $tgl = $rowtg->tgl_spp;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($rowtg->bulan);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 17) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"right\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\">SURAT PERMINTAAN PEMBAYARAN TAMBAHAN UANG PERSEDIAAN</td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\">(SPP - TU)</td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong><u>RINCIAN</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">RENCANA PENGGUNA ANGGARAN</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>No Urut</b></td>                            
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>Uraian</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah</b></td>                                                    
                     </thead> 
                           
                        ";

            // $sql1="select kd,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai from trdspp where no_spp='$nomor' order by kd";
            $sql1 = "SELECT 1 urut, LEFT(c.kd_sub_kegiatan,7) as kode, d.nm_program as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY LEFT(c.kd_sub_kegiatan,7), d.nm_program
                    UNION ALL
                    SELECT 2 urut, LEFT(c.kd_sub_kegiatan,12) as kode, d.nm_kegiatan as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY LEFT(c.kd_sub_kegiatan,12), d.nm_kegiatan
                    UNION ALL
                    SELECT 3 urut, c.kd_sub_kegiatan as kode, c.nm_sub_kegiatan as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,c.nm_sub_kegiatan
                    UNION ALL
                    SELECT 4 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,4) as kode, d.nm_rek3 as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd 
                    LEFT JOIN ms_rek3 d ON LEFT(c.kd_rek6,4)=d.kd_rek3 
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,4),d.nm_rek3
                    UNION ALL
                    SELECT 5 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,6) as kode, d.nm_rek4 as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    LEFT JOIN ms_rek4 d ON LEFT(c.kd_rek6,6)=d.kd_rek4
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,6),d.nm_rek4
                    UNION ALL
                    SELECT 6 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,8) as kode, d.nm_rek5 as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    LEFT JOIN ms_rek5 d ON LEFT(c.kd_rek6,8)=d.kd_rek5
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,8),d.nm_rek5
                    UNION ALL
                    SELECT 7 urut, c.kd_sub_kegiatan+'.'+c.kd_rek6 as kode, c.nm_rek6 as nama, c.nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    order by kode";
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {

                $kode = $row->kode;
                $uraian = $row->nama;
                $urut = $row->urut;
                $nilai = $row->nilai;
                if ($urut == 1) {
                    $lcno = $lcno + 1;
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$lcno</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\"><b>$kode</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\"><b>$uraian</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>" . number_format($nilai, "2", ".", ",") . "</b></td>
                                     </tr>
                                     ";
                } else if ($urut == 7) {
                    $lntotal = $lntotal + $row->nilai;
                    $rek = substr($kode, 16, 13);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $this->left($kode, 15) . "." . $this->dotrek($rek) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                     </tr>
                                     ";
                } else {
                    $rek = substr($kode, 16, 12);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $this->left($kode, 16) . "" . $this->dotrek($rek) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                     </tr>
                                     ";
                }
            }

            $totp = number_format($lntotal, "2", ".", ",");
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"RIGHT\"><b>JUMLAH</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"><b>$totp</b></td>                                     
                                     </tr>";


            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr><td>Terbilang :" . ucwords($this->tukd_model->terbilang($lntotal)) . " </td></tr>
                </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">MENYETUJUI :</td></tr>
                    <tr><td align=\"center\" width=\"25%\">Kuasa Bendahara Umum Daerah</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan3</td></tr> 
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr> 
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr> 
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr>                 
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama3</u></b><br>
                     $pangkat3 <br>
                     NIP. $nip3</td></tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }


        if ($jns == 4) {

            $sqltgl = "SELECT * FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
            $sqltgl = $this->db->query($sqltgl);
            foreach ($sqltgl->result() as $rowtg) {
                $nmskpd = $rowtg->nm_skpd;
                $tgl = $rowtg->tgl_spp;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($rowtg->bulan);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }

            $jns_bbn = $this->tukd_model->get_nama2($nomor, 'jns_beban', 'trhspp', 'no_spp', 'kd_skpd', $kd);
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Gaji dan Tunjangan";
                    break;
                case '2': //GU
                    $lcbeban = "Uang Kespeg";
                    break;
                case '3': //TU
                    $lcbeban = "Uang Makan";
                    break;
                case '4': //TU
                    $lcbeban = "Upah Pungut";
                    break;
                case '5': //TU
                    $lcbeban = "Upah Pungut PBB";
                    break;
                case '6': //TU
                    $lcbeban = "Upah Pungut PBB-KB PKB & BBN-KB ";
                    break;
                case '7': //TU
                    $lcbeban = "Gaji & Tunjangan";
                    break;
                case '8': //TU
                    $lcbeban = "Tunjangan Transport";
                    break;
                case '9': //TU
                    $lcbeban = "Tunjangan Lainnya";
                    break;
                default:
                    $lcbeban = "LS";
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\">SURAT PERMINTAAN PEMBAYARAN LANGSUNG GAJI DAN TUNJANGAN</td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\">(SPP - LS " . strtoupper($lcbeban) . ")</td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong><u>RINCIAN</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">RENCANA PENGGUNA ANGGARAN</td></tr>
                    <tr><td align=\"left\">BULAN : " . strtoupper($bln) . "</td></tr>

                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>No Urut</b></td>                            
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>Uraian</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah</b></td>                                                    
                     </thead> 
                           
                        ";

            $sql1 = "SELECT 1 urut, LEFT(c.kd_sub_kegiatan,18) as kode, d.nm_sub_kegiatan as nama,SUM(c.nilai) as nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    GROUP BY LEFT(c.kd_sub_kegiatan,18), d.nm_sub_kegiatan
                    UNION ALL
                    SELECT 5 urut, c.kd_rek6 as kode, c.nm_rek6 as nama, c.nilai
                    FROM trhspp b
                    INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                    WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                    order by kode";
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {

                $kode = $row->kode;
                $uraian = $row->nama;
                $urut = $row->urut;
                $nilai = $row->nilai;
                if ($urut == 1) {
                    $lcno = $lcno + 1;
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$lcno</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\"><b>$kode</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\"><b>$uraian</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>" . number_format($nilai, "2", ".", ",") . "</b></td>
                                     </tr>
                                     ";
                } else if ($urut == 5) {
                    $lntotal = $lntotal + $row->nilai;
                    $rek = substr($kode, 22, 7);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $kode . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                     </tr>
                                     ";
                } else {
                    $rek = substr($kode, 22, 7);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $this->left($kode, 22) . "" . $this->dotrek($rek) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                     </tr>
                                     ";
                }
            }

            $totp = number_format($lntotal, "2", ".", ",");
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"RIGHT\"><b>JUMLAH</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"><b>$totp</b></td>                                     
                                     </tr>";


            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr><td>Terbilang :" . ucwords($this->tukd_model->terbilang($lntotal)) . " </td></tr>
                </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align=\"center\" width=\"25%\">Mengetahui,</td>                    
                        <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">$jbtttd</td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"><b><u>$nmttd</u></b><br>
                            $pangkatttd 
                        </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                            $pangkat <br>
                        </td>
                     </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">NIP. $nipttd</td>                    
                        <td align=\"center\" width=\"25%\">NIP. $nip</td>
                    </tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }


        if ($jns == 5) {

            $sqltgl = "SELECT * FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
            $sqltgl = $this->db->query($sqltgl);
            foreach ($sqltgl->result() as $rowtg) {
                $nmskpd = $rowtg->nm_skpd;
                $tgl = $rowtg->tgl_spp;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($rowtg->bulan);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $path = ($cetak == 0) ? base_url() : FCPATH;
            $lcbeban = "LS Pihak Ketiga Lainnya";
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                            <tr>
                                <td rowspan=\"5\" align=\"left\" width=\"7%\">
                                <img src=\"" . $path . "/image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                                </td>
                                <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                                <tr>
                                <td align=\"center\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
                                <tr>
                                <td align=\"center\" style=\"font-size:14px\" ><strong>SKPD $nm_skpd </strong></td></tr>
                                <tr>
                                <td align=\"center\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN " . $this->session->userdata('pcThang') . "</strong></td></tr>
                                <tr>
                                <td align=\"center\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                                </table>
                         <hr  width=\"100%\"> 
                         ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr><td align=\"center\" style=\"font-size:16px\">SURAT PERNYATAAN PENGAJUAN SPP - " . strtoupper($lcbeban) . " </td></tr>
                        <tr><td align=\"center\" style=\"font-size:16px\">(SPP - " . strtoupper($lcbeban) . ")</td></tr>
                        <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                        <tr><td align=\"center\" style=\"font-size:16px\"><strong><u>RINCIAN</u></strong></td></tr>
                        <tr><td align=\"center\">&nbsp;</td></tr>
                        <tr><td align=\"left\">RENCANA PENGGUNA ANGGARAN</td></tr>
                      </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                         <thead>                       
                            <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>No Urut</b></td>                            
                                <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>Uraian</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah</b></td>                                                    
                         </thead> 
                               
                            ";

            // $sql1="select kd,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai from trdspp where no_spp='$nomor' order by kd";
            $sql1 = "SELECT 1 urut, LEFT(c.kd_sub_kegiatan,7) as kode, d.nm_program as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd' AND d.jns_ang='$jns_ang'
                        GROUP BY LEFT(c.kd_sub_kegiatan,7), d.nm_program
                        UNION ALL
                        SELECT 2 urut, LEFT(c.kd_sub_kegiatan,12) as kode, d.nm_kegiatan as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd' AND d.jns_ang='$jns_ang'
                        GROUP BY LEFT(c.kd_sub_kegiatan,12), d.nm_kegiatan
                        UNION ALL
                        SELECT 3 urut, c.kd_sub_kegiatan as kode, c.nm_sub_kegiatan as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd' AND d.jns_ang='$jns_ang'
                        GROUP BY c.kd_sub_kegiatan,c.nm_sub_kegiatan
                        UNION ALL
                        SELECT 4 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,4) as kode, d.nm_rek3 as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd 
                        LEFT JOIN ms_rek3 d ON LEFT(c.kd_rek6,4)=d.kd_rek3 
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                        GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,4),d.nm_rek3
                        UNION ALL
                        SELECT 5 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,6) as kode, d.nm_rek4 as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        LEFT JOIN ms_rek4 d ON LEFT(c.kd_rek6,6)=d.kd_rek4
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                        GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,6),d.nm_rek4
                        UNION ALL
                        SELECT 6 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,8) as kode, d.nm_rek5 as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        LEFT JOIN ms_rek5 d ON LEFT(c.kd_rek6,8)=d.kd_rek5
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                        GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,8),d.nm_rek5
                        UNION ALL
                        SELECT 7 urut, c.kd_sub_kegiatan+'.'+c.kd_rek6 as kode, c.nm_rek6 as nama, c.nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                        order by kode";
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {

                $kode = $row->kode;
                $uraian = $row->nama;
                $urut = $row->urut;
                $nilai = $row->nilai;
                if ($urut == 1) {
                    $lcno = $lcno + 1;
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$lcno</b></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\"><b>$kode</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\"><b>$uraian</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>" . number_format($nilai, "2", ".", ",") . "</b></td>
                                         </tr>
                                         ";
                } else if ($urut == 7) {
                    $lntotal = $lntotal + $row->nilai;
                    $rek = substr($kode, 16, 13);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $this->left($kode, 15) . "." . $this->dotrek($rek) . "</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                         </tr>
                                         ";
                } else {
                    $rek = substr($kode, 16, 12);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $this->left($kode, 16) . "" . $this->dotrek($rek) . "</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                         </tr>
                                         ";
                }
            }

            $totp = number_format($lntotal, "2", ".", ",");
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"RIGHT\"><b>JUMLAH</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"><b>$totp</b></td>                                     
                                         </tr>";


            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td>Terbilang :" . ucwords($this->tukd_model->terbilang($lntotal)) . " </td></tr>
                    </table>";

            if ($giatspp == '5.02.00.0.06.62') {
                $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">$daerah, $tanggal</td></tr>
                        <tr><td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\"><b><u></u></b><br>
                          <br>
                         </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                         $pangkat <br>
                         NIP. $nip</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                      </table>";
            } else {
                $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                        <td align=\"center\" width=\"25%\">$daerah, $tanggal</td></tr>
                        <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                         $pangkat2 <br>
                         $nip2</td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                         $pangkat <br>
                         NIP. $nip</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                      </table>";

                /*$cRet .="<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\">MENYETUJUI :</td></tr>
                        <tr><td align=\"center\" width=\"25%\">Kuasa Bendahara Umum Daerah</td></tr>
                        <tr><td align=\"center\" width=\"25%\">$jabatan3</td></tr> 
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr> 
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr> 
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr>                 
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\"><b><u>$nama3</u></b><br>
                         $pangkat3 <br>
                         NIP. $nip3</td></tr>
                      </table>"; */
            }

            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }


        if ($jns == 6) {

            $sqltgl = "SELECT nm_skpd,tgl_spp FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
            $sqltgl = $this->db->query($sqltgl);
            foreach ($sqltgl->result() as $rowtg) {
                $tgl = $rowtg->tgl_spp;
                $nmskpd = $rowtg->nm_skpd;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $jns_bbn = $this->support->get_nama2($nomor, 'jns_beban', 'trhspp', 'no_spp', 'kd_skpd', $kd);
            // echo($jns_bbn);
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "PNS";
                    break;
                case '2': //GU
                    $lcbeban = "Non PNS";
                    break;
                case '3': //TU
                    $lcbeban = "Barang dan Jasa";
                    break;
                default:
                    $lcbeban = "LS";
            }
            $path = ($cetak == 0) ? base_url() : FCPATH;
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                           <tr>
                               <td rowspan=\"5\" align=\"center\" width=\"7%\">
                               <img src=\"" . $path . "/image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                               </td>
                               <td align=\"center\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                               <tr>
                               <td align=\"center\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
                               <tr>
                               <td align=\"center\" style=\"font-size:14px\" ><strong>SKPD $nm_skpd </strong></td></tr>
                               <tr>
                               <td align=\"center\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN " . $this->session->userdata('pcThang') . "</strong></td></tr>
                               <tr>
                               <td align=\"center\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                               </table>
                        <hr  width=\"100%\"> 
                        ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr><td align=\"center\" style=\"font-size:16px\">SURAT PERMINTAAN PEMBAYARAN LANGSUNG BARANG DAN JASA</td></tr>
                        <tr><td align=\"center\" style=\"font-size:16px\">(SPP - LS " . strtoupper($lcbeban) . " )</td></tr>
                        <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                        <tr><td align=\"center\" style=\"font-size:16px\"><strong><u>RINCIAN</u></strong></td></tr>
                        <tr><td align=\"center\">&nbsp;</td></tr>
                        <tr><td align=\"left\">RENCANA PENGGUNA ANGGARAN</td></tr>
    
                      </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
                         <thead>                       
                            <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>No Urut</b></td> 
                                <td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\"><b>Kode Rekening</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>Uraian</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah</b></td>                                                    
                         </thead> 
                               
                            ";

            $sql1 = "SELECT 1 urut, LEFT(c.kd_sub_kegiatan,18) as kode, d.nm_program as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd' AND d.jns_ang='$jns_ang'
                        GROUP BY LEFT(c.kd_sub_kegiatan,18), d.nm_program
                        UNION ALL
                        SELECT 2 urut, c.kd_sub_kegiatan as kode, c.nm_sub_kegiatan as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        INNER JOIN trskpd d ON c.kd_sub_kegiatan=d.kd_sub_kegiatan  AND c.kd_skpd=d.kd_skpd
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd' AND d.jns_ang='$jns_ang'
                        GROUP BY c.kd_sub_kegiatan,c.nm_sub_kegiatan
                        UNION ALL
                        SELECT 3 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,4) as kode, d.nm_rek3 as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd 
                        LEFT JOIN ms_rek3 d ON LEFT(c.kd_rek6,4)=d.kd_rek3 
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                        GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,4),d.nm_rek3
                        UNION ALL
                        SELECT 4 urut, c.kd_sub_kegiatan+'.'+LEFT(c.kd_rek6,6) as kode, d.nm_rek4 as nama,SUM(c.nilai) as nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        LEFT JOIN ms_rek4 d ON LEFT(c.kd_rek6,6)=d.kd_rek4
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                        GROUP BY c.kd_sub_kegiatan,LEFT(c.kd_rek6,6),d.nm_rek4
                        UNION ALL
                        SELECT 5 urut, c.kd_sub_kegiatan+'.'+c.kd_rek6 as kode, c.nm_rek6 as nama, c.nilai
                        FROM trhspp b
                        INNER JOIN trdspp c ON b.no_spp=c.no_spp AND b.kd_skpd=c.kd_skpd
                        WHERE b.no_spp='$nomor' AND b.kd_skpd='$kd'
                        order by kode";
            $query = $this->db->query($sql1);
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {

                $kode = $row->kode;
                $uraian = $row->nama;
                $urut = $row->urut;
                $nilai = $row->nilai;
                if ($urut == 1) {
                    $lcno = $lcno + 1;
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$lcno</b></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\"><b>$kode</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\"><b>$uraian</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>" . number_format($nilai, "2", ".", ",") . "</b></td>
                                         </tr>
                                         ";
                } else if ($urut == 5) {
                    $lntotal = $lntotal + $row->nilai;
                    $rek = substr($kode, 22, 7);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $this->left($kode, 21) . "." . $this->dotrek($rek) . "</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                         </tr>
                                         ";
                } else {
                    $rek = substr($kode, 22, 7);
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">" . $this->left($kode, 22) . "" . $this->dotrek($rek) . "</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . number_format($nilai, "2", ".", ",") . "</td>
                                         </tr>
                                         ";
                }
            }

            $totp = number_format($lntotal, "2", ".", ",");
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"RIGHT\"><b>JUMLAH</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"><b>$totp</b></td>                                     
                                         </tr>";


            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td>Terbilang :" . ucwords($this->tukd_model->terbilang($lntotal)) . " </td></tr>
                    </table>";

            $sqlj = "SELECT count(*)jml FROM trhspp WHERE keperluan like '%Tambahan Penghasilan Pegawai%' and no_spp='$nomor'";
            $sqlsj = $this->db->query($sqlj);
            foreach ($sqlsj->result() as $rowj) {
                $tamsil     = $rowj->jml;
            }
            if ($tamsil > 0) {
                $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">$daerah, $tanggal</td></tr>
                        <tr><td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\"><b><u></u></b><br>
                         <br>
                         </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                         $pangkat <br>
                         NIP. $nip</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                      </table>";
            } else {
                $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                        <td align=\"center\" width=\"25%\">$daerah, $tanggal</td></tr>
                        <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                        <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                         $pangkat2 <br>
                         $nip2</td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                         $pangkat <br>
                         NIP. $nip</td></tr>                              
                        <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                      </table>";
            }


            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }
        if ($jns == 7) {

            $sqltgl = "SELECT * FROM trhspp where no_spp='$nomor' AND kd_skpd='$kd'";
            $sqltgl = $this->db->query($sqltgl);
            foreach ($sqltgl->result() as $rowtg) {
                $nmskpd = $rowtg->nm_skpd;
                $tgl = $rowtg->tgl_spp;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($rowtg->bulan);
            }
            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">" . strtoupper($nm_org) . "</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>SURAT PERMINTAAN PEMBAYARAN GANTI UANG PERSEDIAAN</b></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><b>(SPP - GU-NIHIL)</b></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\"><strong><u>RINCIAN</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"$spasi\" cellpadding=\"0\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>No Urut</b></td>                            
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>Uraian</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah</b></td>                                                    
                     </thead> 
                           
                        ";

            //$sql1="select kd,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai from trdspp where no_spp='$nomor' order by kd";
            $sql1 = "SELECT SUM(0) AS nilai FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd'";

            $query = $this->db->query($sql1);
            //$query = $this->skpd_model->getAllc();
            $lcno = 0;
            $lntotal = 0;
            foreach ($query->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                //$no=$row->kd + 1;
                // $giat=$row->kd_sub_kegiatan;
                // $rek=$row->kd_rek6;
                //$reke=$this->dotrek($rek);                    
                //$uraian=$row->nm_rek6;
                $nilai = number_format($row->nilai, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$lcno</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"center\">$kd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$nm_skpd</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     </tr>
                                     ";
            }

            $sqltp = "SELECT SUM(0) AS tot FROM trdspp WHERE no_spp='$nomor' AND kd_skpd='$kd'";
            $sqlp = $this->db->query($sqltp);
            foreach ($sqlp->result() as $rowp) {
                $totp = number_format($rowp->tot, "2", ".", ",");
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"center\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"></td>                                     
                                     </tr>";

                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"5%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"15%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"60%\" align=\"right\"><b>JUMLAH</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;;\" width=\"20%\" align=\"right\"><b>$totp</b></td>                                     
                                     </tr>";
            }
            $cRet    .= " <tr><td colspan=\"4\" style=\"border-right:hidden;border-bottom:hidden;border-left:hidden;\" align=\"left\">
                        Terbilang : <b><i>" . ucwords($this->tukd_model->terbilang($rowp->tot)) . "</i></b></td>                                     
                                                                       
                                     </tr>";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align=\"center\" width=\"25%\">Mengetahui,</td>                    
                        <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">$jbtttd</td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"><b><u>$nmttd</u></b><br>
                            $pangkatttd 
                        </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                            $pangkat <br>
                        </td>
                     </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">NIP. $nipttd</td>                    
                        <td align=\"center\" width=\"25%\">NIP. $nip</td>
                    </tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($cetak == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($cetak == '0') {
                echo $cRet;
            }
        }
    }

    function cetak_kelengkapan_spp()
    {
        $client = $this->ClientModel->clientData('1');
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(8);
        $spasi = $this->uri->segment(9);
        $ppk = $this->uri->segment(11);
        $ttdppk = str_replace('123456789', ' ', $this->uri->segment(12));
        $sqlttdppk = $this->db->query("SELECT * FROM ms_ttd WHERE nip = '" . $ttdppk . "'")->row();
        $nipppk = $sqlttdppk->nip;
        $nmppk = $sqlttdppk->nama;

        // echo $nipppk.$nmppk;die();


        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $nm_skpd = $this->tukd_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $alamat = $this->tukd_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');

        $pakpa = str_replace('123456789', ' ', $this->uri->segment(7));
        $aa = "SELECT LTRIM(RTRIM(kode)) as kode, nip, nama from ms_ttd WHERE nip ='" . $pakpa . "'";
        $xttd = $this->db->query($aa)->row();
        $kdttd = $xttd->kode;
        $nipttd = $xttd->nip;
        $nmttd = $xttd->nama;
        $jbtttd = '';
        $cRet = '';

        if ($kdttd == 'PA') {
            $jbtttd = 'Pengguna Anggaran';
        } else {
            $jbtttd = 'Kuasa Pengguna Anggaran';
        }

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\"><h1>" . $client->pem . " " . $client->nm_kab . "</h1><br><h2>" . strtoupper($nm_skpd) . "</h2><br>" . $alamat . "</td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>
                 </table>
                 <hr>";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td align=\"center\" style =\"font-size:16px\"><b><u>PENELITIAN KELENGKAPAN DOKUMENT SPP</u></b></td>
                    </tr>
                    <tr>
                        <td align=\"center\" style =\"font-size:12px\">No SPP : " . $nomor . "</td>
                    </tr>
                  </table>";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td align=\"center\" style =\"font-size:16px;height:80px;\">&nbsp;</td>
                    </tr>
                  </table>";

        $klp = "SELECT * from kelengkapan_spp";
        $queryspp = $this->db->query($klp)->result_array();

        $cRet .= "SPP  :";
        foreach ($queryspp as $sppklp) {

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td align=\"left\" style =\"font-size:12px;width:10%;height:35px;\">
                            <table style=\"border-collapse:collapse;font-family: Times New Roman;\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                                <tr>
                                    <td style =\"height:30px;\">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <td align=\"left\" style =\"font-size:12px;width:5%;\"></td>
                        <td align=\"left\" style =\"font-size:12px;width:85%;\">" . $sppklp['kelengkapan'] . "</td>
                    </tr>
                  </table>";
        }

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td align=\"center\" style =\"font-size:16px;height:30px;\">&nbsp;</td>
                    </tr>
                  </table>";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td align=\"center\" style =\"font-size:13px;\" colspan = \"3\"><u><b>PENELITIAN KELENGKAPAN DOKUMEN SPP<b/></u></td>
                    </tr>
                    <tr>
                        <td align=\"left\" style =\"font-size:12px;width:15%;\">Tanggal</td>
                        <td align=\"center\" style =\"font-size:12px;width:5%;\">:</td>
                        <td align=\"left\" style =\"font-size:12px;width:80%;\">" . date('d-m-Y') . "</td>
                    </tr>
                    <tr>
                        <td align=\"left\" style =\"font-size:12px;width:15%;\">Nama</td>
                        <td align=\"center\" style =\"font-size:12px;width:5%;\">:</td>
                        <td align=\"left\" style =\"font-size:12px;width:80%;\">" . $nmppk . "</td>
                    </tr>
                    <tr>
                        <td align=\"left\" style =\"font-size:12px;width:15%;\">NIP</td>
                        <td align=\"center\" style =\"font-size:12px;width:5%;\">:</td>
                        <td align=\"left\" style =\"font-size:12px;width:80%;\">" . $nipppk . "</td>
                    </tr>
                    <tr>
                        <td align=\"left\" style =\"font-size:12px;width:15%;\" valign=\"top\">Tanda Tangan</td>
                        <td align=\"center\" style =\"font-size:12px;width:5%;\" valign=\"top\">:</td>
                        <td align=\"left\" style =\"font-size:12px;width:80%;height:60px;\" valign=\"bottom\">....................</td>
                    </tr>
                    <tr>
                        <td align=\"left\" style =\"font-size:10px;width:15%;\">Lembar Asli<br>Salinan 1<br>Salinan 2<br>Salinan 3</td>
                        <td align=\"center\" style =\"font-size:10px;width:5%;\">:<br>:<br>:<br>:</td>
                        <td align=\"left\" style =\"font-size:10px;width:80%;height:60px;\">Untuk PA / KPA / PPK - SKPD<br>Untuk Kuasa BUD<br>Untuk Bendahara Pengeluaran / PPTK<br>Arsip Bendahara Pengeluaran / PPTK</td>
                    </tr>
                  </table>";

        $data['prev'] = $cRet;

        if ($print == 1) {
            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($print == 0) {
            echo $cRet;
        }
    }



    function cetakspp4()
    {
        $client = $this->ClientModel->clientData('1');
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $jns   = $this->uri->segment(6);
        // echo($jns);
        $tanpa   = $this->uri->segment(8);
        $spasi = $this->uri->segment(9);

        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->tukd_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->tukd_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $PA = str_replace('123456789', ' ', $this->uri->segment(7));

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$PA' and kode in ('PA','KPA') AND kd_skpd='$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        $pakpa = str_replace('123456789', ' ', $this->uri->segment(7));
        $aa = "SELECT LTRIM(RTRIM(kode)) as kode, nip, nama from ms_ttd WHERE nip ='" . $pakpa . "'";
        $xttd = $this->db->query($aa)->row();
        $kdttd = $xttd->kode;
        $nipttd = $xttd->nip;
        $nmttd = $xttd->nama;
        $jbtttd = '';

        if ($kdttd == 'PA') {
            $jbtttd = 'Pengguna Anggaran';
        } else {
            $jbtttd = 'Kuasa Pengguna Anggaran';
        }

        // SPP UP
        if ($jns == 1) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan, b.urusan1 as kd_bidang_urusan, (select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=b.urusan1)as nm_bidang_urusan, a.no_spd,a.nilai FROM trhspp a INNER JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = strtoupper($row->nm_skpd);
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 17) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td></tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - UP </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Uang Persediaan (SPP - UP) Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai ($a)</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran UP tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran UP tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran Langsung
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPM-UP OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($print == 1) {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == 0) {
                echo $cRet;
            }
            // $this->tukd_model->_mpdf('',$cRet,10,10,10,'0'); 
        }

        if ($jns == 2) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank,a.no_spd,a.nilai
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">" . strtoupper($nm_org) . "</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - GU) </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - GU) Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai (" . ucwords($a) . ")</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Ganti Uang (GU) tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Ganti Uang (GU)  tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran Langsung GU
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP-GU OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($print == '1') {

                //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }

        if ($jns == 3) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - TU) </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Tambahan Uang Persediaan (SPP - TU) Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai (" . ucwords($a) . ")</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Tambahan Uang Persediaan (TU) tersebut di atas akan dipergunakan untuk keperluan membayar kegiatan yang akan kami laksanan sesuai DPA-OPD
                                     dalam waktu 1 (satu) bulan sejak diterbitkan SP2D</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Apabila dana TU tersebut sampai batas 1 (satu) bulan tidak habis terpakai maka sisa dana TU akan kami setorkan ke Rekening Kas Umum Daerah Pemerintah
                                     Provinsi Kalimatan Barat.
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">3.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Tambahan Uang Persediaan (TU)  tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran Langsung TU
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP-TU OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($print == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }

        if ($jns == 4) {

            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $jns_bbn = $row->jns_beban;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';


            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Gaji dan Tunjangan";
                    break;
                case '2': //GU
                    $lcbeban = "Uang Kespeg";
                    break;
                case '3': //TU
                    $lcbeban = "Uang Makan";
                    break;
                case '4': //TU
                    $lcbeban = "Upah Pungut";
                    break;
                case '5': //TU
                    $lcbeban = "Upah Pungut PBB";
                    break;
                case '6': //TU
                    $lcbeban = "Upah Pungut PBB-KB PKB & BBN-KB ";
                    break;
                case '7': //TU
                    $lcbeban = "Gaji & Tunjangan";
                    break;
                case '8': //TU
                    $lcbeban = "Tunjangan Transport";
                    break;
                case '9': //TU
                    $lcbeban = "Tunjangan Lainnya";
                    break;
                default:
                    $lcbeban = "LS";
            }
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">" . strtoupper($nm_org) . "</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - LS " . strtoupper($lcbeban) . " </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - LS " . strtoupper($lcbeban) . ") Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai (" . ucwords($a) . ")</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Langsung (LS) $lcbeban  tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Langsung (LS) $lcbeban tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran Langsung LS-Gaji
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP-LS $lcbeban OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">Mengetahui,<br>$jbtttd</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">
                        </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nmttd</u></b><br>
                            $pangkat <br>
                        </td>
                     </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">NIP. $nipttd</td>
                    </tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($print == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }
        if ($jns == 5) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $lcbeban = "LS Pihak Ketiga Lainnya";

            if (substr($kd, 0, 17) == $this->org_keu && $kd != $this->skpd_keu) {
                $rowspn = '4';
            } else {
                $rowspn = '3';
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "/image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td  align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td>
                        <td rowspan=\"$rowspn\" align=\"right\">
                        </td>
                        </tr>";



            if (substr($kd, 0, 17) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">$nm_skpd</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr>
                    <td align=\"center\">" . strtoupper($daerah) . "</td>
                    <td align=\"center\">
                    Kode Pos: $kodepos
                    </td> 
                     </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - " . strtoupper($lcbeban) . "  </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - " . strtoupper($lcbeban) . ") Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai (" . ucwords($a) . ")</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Langsung (LS) $lcbeban tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Langsung (LS) $lcbeban tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran Langsung LS-Barang dan Jasa
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP-LS $lcbeban OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$daerah, $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($print == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }
        if ($jns == 6) {
            /*
              $sql1="SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,b.kd_bidang_urusan,b.nm_bidang_urusan,a.no_spd,a.nilai,(SELECT SUM(x.nilai) FROM trdspd x inner join trhspd w on x.no_spd = w.no_spd WHERE w.jns_beban='52')
                AS spd,(SELECT SUM(z.nilai) FROM trdspp z INNER JOIN trhspp y ON z.no_spp = y.no_spp where y.no_spd=a.no_spd and z.no_spp <> a.no_spp) AS spp FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor'";
            */

            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.jns_beban,a.npwp,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";



            $query = $this->db->query($sql1);
            //$query = $this->skpd_model->getAllc();

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $jns_bbn = $row->jns_beban;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Tambahan Penghasilan";
                    break;
                case '2': //GU
                    $lcbeban = "Operasional KDH/WKDH";
                    break;
                case '3': //TU
                    $lcbeban = " Operasional DPRD";
                    break;
                case '4': //TU
                    $lcbeban = "  Honor Kontrak";
                    break;
                case '5': //TU
                    $lcbeban = " Jasa Pelayanan Kesehatan";
                    break;
                case '6': //TU
                    $lcbeban = " Pihak ketiga";
                    break;
                case '7': //TU
                    $lcbeban = " PNS";
                    break;
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">" . strtoupper($nm_org) . "</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - " . strtoupper($lcbeban) . " </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - LS " . strtoupper($lcbeban) . ") Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai (" . ucwords($a) . ")</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Langsung (LS) $lcbeban tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Langsung (LS) $lcbeban tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran Langsung LS-Barang dan Jasa
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP-LS $lcbeban OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">Mengetahui,<br>$jbtttd</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">
                        </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nmttd</u></b><br>
                            $pangkat <br>
                        </td>
                     </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">NIP. $nipttd</td>
                    </tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($print == 1) {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == 0) {
                echo $cRet;
            }
        }
        if ($jns == 7) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank,a.no_spd,a.nilai
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->tukd_model->getBulan($row->bulan);
                $nilai = number_format(0, "2", ",", ".");
                $nilai1 = 0;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->tukd_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">" . strtoupper($nm_org) . "</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - GU-NIHIL) </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Langsung (SPP - GU-NIHIL) Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai (" . ucwords($a) . ")</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Ganti Uang (GU-NIHIL) tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran Ganti Uang (GU-NIHIL)  tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilaksanakan dengan Pembayaran Langsung GU
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPP -GU-NIHIL OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($print == '1') {

                //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }
    }


    //UP


    function cetakspp5()
    {
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $kd1 = substr($kd, 0, 17);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $spasi = $this->uri->segment(10);

        $nm_skpd = $this->rka_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $jns_bbn = $this->rka_model->get_nama($nomor, 'jns_beban', 'trhspp', 'no_spp');
        $kodepos = $this->rka_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd='$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode ='PPTK' AND kd_skpd='$kd'";
        $sqlttd2 = $this->db->query($sqlttd2);
        foreach ($sqlttd2->result() as $rowttd2) {
            $nip2 = $rowttd2->nip;
            $nama2 = $rowttd2->nm2;
            $jabatan2  = $rowttd2->jab;
            $pangkat2  = $rowttd2->pangkat;
        }

        if ($jns == 1) {
            $lcbeban = "Uang Persedian";
        }
        if ($jns == 2) {
            $lcbeban = "Ganti Uang Persedian";
        }
        if ($jns == 3) {
            $lcbeban = "Tambah Uang Persedian";
        }
        if ($jns == 4) {
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "LS - Gaji dan Tunjangan";
                    break;
                case '2': //GU
                    $lcbeban = "LS - Uang Kespeg";
                    break;
                case '3': //TU
                    $lcbeban = "LS - Uang Makan";
                    break;
                case '4': //TU
                    $lcbeban = "LS - Upah Pungut";
                    break;
                case '5': //TU
                    $lcbeban = "LS - Upah Pungut PBB";
                    break;
                case '6': //TU
                    $lcbeban = "LS - Upah Pungut PBB-KB PKB & BBN-KB ";
                    break;
                case '7': //TU
                    $lcbeban = "LS - Gaji & Tunjangan";
                    break;
                case '8': //TU
                    $lcbeban = "LS - Tunjangan Transport";
                    break;
                case '9': //TU
                    $lcbeban = "LS - Tunjangan Lainnya";
                    break;
                default:
                    $lcbeban = "LS";
            }
        }
        if ($jns == 6) {
            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "LS - Rutin PNS";
                    break;
                case '2': //GU
                    $lcbeban = "LS - Rutin Non PNS";
                    break;
                case '3': //TU
                    $lcbeban = "LS - Barang dan Jasa";
                    break;
            }
        }


        $tgl_spp = $this->rka_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
        $tanggal = $this->tanggal_format_indonesia($tgl_spp);
        $no_spd = $this->rka_model->get_nama2($nomor, 'no_spd', 'trhspp', 'no_spp', 'kd_skpd', $kd);
        $tglspd = $this->rka_model->get_nama2($no_spd, 'tgl_spd', 'trhspd', 'no_spd', 'left(kd_skpd,17)', $kd1);
        $nmskpd = $this->rka_model->get_nama($kd, 'nm_skpd', 'trhspp', 'kd_skpd');
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang,nogub_susun,nogub_perubahan FROM sclient WHERE kd_skpd='$kd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $nogub_susun  = $rowsc->nogub_susun;
            $nogub_perubahan  = $rowsc->nogub_perubahan;
        }



        $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.kd_program,a.nm_program,a.nm_sub_kegiatan,a.kd_sub_kegiatan,a.bulan,a.nmrekan, 
                a.no_rek as no_rek_rek, a.npwp as npwp_rek,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank, lanjut, kontrak, keperluan,pimpinan, alamat,
                ( SELECT nama FROM ms_bank WHERE  kode=a.bank ) AS nama_bank_rek, 
                ( SELECT rekening FROM ms_skpd WHERE  kd_skpd=a.kd_skpd ) AS no_rek, 
                ( SELECT npwp FROM ms_skpd WHERE  kd_skpd=a.kd_skpd ) AS npwp, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
        $query = $this->db->query($sql1);
        //$query = $this->skpd_model->getAllc();

        foreach ($query->result() as $row) {
            $kd_urusan = $row->kd_bidang_urusan;
            $nm_urusan = $row->nm_bidang_urusan;
            $kd_skpd = $row->kd_skpd;
            $nm_skpd = $row->nm_skpd;
            $spd = $row->no_spd;
            $tgl = $row->tgl_spp;
            $kd_prog = $row->kd_program;
            $nm_prog = $row->nm_program;
            $kd_kegiatan = $row->kd_sub_kegiatan;
            $kd_kegiatans = substr($row->kd_sub_kegiatan, 0, 12);
            $nm_kegiatan = $this->rka_model->get_nama($kd_kegiatans, 'nm_kegiatan', 'ms_kegiatan', 'kd_kegiatan');
            $nm_sub_kegiatan = $row->nm_sub_kegiatan;
            $nm_bank_rek = $row->nama_bank_rek;
            $lanjut = $row->lanjut;
            $kontrak = $row->kontrak;
            $no_rek_rek = $row->no_rek_rek;
            $npwp_rek = $row->npwp_rek;
            $no_rek = $row->no_rek;
            $npwp = $row->npwp;
            $ket = ltrim($row->keperluan);
            $rekan = $row->nmrekan;
            $dir = $row->pimpinan;
            $alamat = $row->alamat;
            $tanggal = $this->tanggal_format_indonesia($tgl);
            $bln = $this->getBulan($row->bulan);
            $nilai = number_format($row->nilai, "2", ",", ".");
            $nilai1 = $row->nilai;
            $a = $this->tukd_model->terbilang($nilai1);
            //echo($a);
        }
        $kodebank = $this->rka_model->get_nama($kd, 'bank', 'ms_skpd', 'kd_skpd');
        $nama_bank = empty($kodebank) || $kodebank == '' ? '-' : $this->rka_model->get_nama($kodebank, 'nama', 'ms_bank', 'kode');

        $stsubah = $this->rka_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
        $stssempurna = $this->rka_model->get_nama($kd, 'status_sempurna', 'trhrka', 'kd_skpd');
        if (($stsubah == 0) && ($stssempurna == 0)) {
            $field = 'nilai';
            $nodpa = $this->rka_model->get_nama($kd, 'no_dpa', 'trhrka', 'kd_skpd');
            $tgl_dpa = $this->rka_model->get_nama($kd, 'tgl_dpa', 'trhrka', 'kd_skpd');
            $nogub = $nogub_susun;
        } else if (($stsubah == 0) && ($stssempurna == 1)) {
            $nodpa = $this->rka_model->get_nama($kd, 'no_dpa_sempurna', 'trhrka', 'kd_skpd');
            $tgl_dpa = $this->rka_model->get_nama($kd, 'tgl_dpa_sempurna', 'trhrka', 'kd_skpd');
            $field = 'nilai_sempurna';
            $nogub = $nogub_susun;
        } else {
            $nodpa = $this->rka_model->get_nama($kd, 'no_dpa_ubah', 'trhrka', 'kd_skpd');
            $tgl_dpa = $this->rka_model->get_nama($kd, 'tgl_dpa_ubah', 'trhrka', 'kd_skpd');
            $field = 'nilai_ubah';
            $nogub = $nogub_perubahan;
        }

        //$sqlang="SELECT sum(nilai) as nilai,sum(nilai_ubah) as nilai_ubah FROM trdrka where kd_kegiatan='$kdgiat'";

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $thn_ang       = $this->session->userdata('pcThang');
        if ($tanpa == 1) {
            $tanggal = "_______________________$thn_ang";
        }
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $kab . " " . $daerah . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



        if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
            $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
        }

        $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\" style=\"font-size:16px\">SURAT PERMINTAAN PEMBAYARAN " . strtoupper($lcbeban) . "</td></tr>
                    <tr><td align=\"center\" style=\"font-size:16px\">(SPP - " . strtoupper($lcbeban) . ")</td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor</strong></td></tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\"> ";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >1. OPD </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;font-size:12px\" width=\"18%\">: $nm_skpd</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >2. Unit Kerja </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nm_skpd</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >3. Alamat </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $alamat_skpd</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >4. Nomor dan Tanggal DPA/DPPA/DPPAL-OPD  </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nodpa / $tgl_dpa</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >5. Tahun Anggaran </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $thn_ang </td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >6. Bulan </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $bln $thn_ang</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >7. Urusan Pemerintahan </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $kd_urusan $nm_urusan</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >8. Nama Program </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nm_prog</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >9. Nama Kegiatan </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nm_kegiatan</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >10. Nama Sub Kegiatan </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nm_sub_kegiatan</td></tr>";
        $cRet    .= " <tr><td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\"  align=\"center\">&nbsp;</td></tr> ";
        $cRet .=       " </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr><td align=\"center\">Kepada Yth: <br>
                        Pengguna Anggaran/Kuasa Pengguna Anggaran <br>
                        <br>
                        OPD $nm_skpd<br>
                        di $daerah<td></tr>
                    <tr><td align=\"justify\">Dengan memperhatikan Peraturan Bupati Kabupaten Melawi $nogub
                    tentang Perubahan Peraturan Bupati Kabupaten Melawi No. 55 tahun 2019  tentang Penjabaran APBD Tahun Anggaran $thn_ang,
                    bersama ini kami mengajukan Surat Permintaan Pembayaran Langsung Barang dan Jasa sebagai berikut:
                    <br>
                    <br>
                    </td></tr>
                    <tr><td align=\"center\"> &nbsp;<td></tr>
                  </table>";
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"> ";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >a. Jumlah Pembayaran Yang Diminta </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;font-size:12px\" width=\"18%\">: Rp. $nilai</td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"center\" >(terbilang) </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: <i>(" . ucwords($a) . ")</i></td></tr>";
        $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >b. Untuk Keperluan </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black; font-family:Tahoma; font-size:10px\" width=\"18%\"><pre>:$ket</pre></td></tr>";
        if (($jns == 6) && ($jns_bbn == 3)) {
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >c. Nama Pihak Ketiga</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $rekan</td></tr>";
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >d. Dasar Bendahara Pengeluaran </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $no_spd </td></tr>";
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >e. Alamat </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $alamat</td></tr>";
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >f. Nama dan Nomor Rekening</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nm_bank_rek / $no_rek_rek</td></tr>";
            $cRet    .= " <tr><td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\"  align=\"center\">&nbsp;</td></tr> ";
        } else {
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >c. Nama Bendahara</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nama</td></tr>";
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >d. Dasar Bendahara Pengeluaran </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $no_spd </td></tr>";
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >e. Alamat </td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $alamat_skpd</td></tr>";
            $cRet    .= " <tr><td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:solid 1px black;border-right: none;\" width=\"20%\" align=\"left\" >f. Nama dan Nomor Rekening</td>
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: none;border-bottom: none;horizontal-align:left;border-left:none;border-right: solid 1px black;\" width=\"18%\">: $nama_bank / $no_rek</td></tr>";
            $cRet    .= " <tr><td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\"  align=\"center\">&nbsp;</td></tr> ";
        }
        $cRet .=       " </table>";
        if ($jns == 4) {
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
        } else {
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\">MENGETAHUI :</td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\">$jabatan2</td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"><b><u>$nama2</u></b><br>
                     $pangkat2 <br>
                     NIP. $nip2</td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
        }
        $data['prev'] = $cRet;
        if ($cetak == '1') {
            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($cetak == '0') {
            echo $cRet;
        }
    }


    function cetakspp6()
    {
        $client = $this->ClientModel->clientData('1');
        $print = $this->uri->segment(3);
        $kd    = $this->uri->segment(5);
        $kd1    = substr($kd, 0, 17);
        $jns   = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(8);
        $spasi = $this->uri->segment(9);

        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $alamat_skpd = $this->rka_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->rka_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }
        $PA = str_replace('123456789', ' ', $this->uri->segment(7));

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$PA' and kode in ('PA','KPA') AND kd_skpd='$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $stsubah = $this->rka_model->get_nama($kd, 'status_ubah', 'trhrka', 'kd_skpd');
        if ($stsubah == 0) {
            $sqldpa = "SELECT no_dpa as no , tgl_dpa as tgl from trhrka where kd_skpd = '$kd'";
        } else {
            $sqldpa = "SELECT no_dpa_ubah as no, tgl_dpa_ubah as tgl from trhrka where kd_skpd = '$kd'";
        }
        $sqldpa = $this->db->query($sqldpa);
        foreach ($sqldpa->result() as $rowdpa) {
            $no_dpa = $rowdpa->no;
            if ($jns == 4) {
                $tgl_dpa = '';
            } else {
                $tgl_dpa = $this->tanggal_format_indonesia($rowdpa->tgl);
            }
        }

        // echo $tgl_dpa;

        $pakpa = str_replace('123456789', ' ', $this->uri->segment(7));
        $aa = "SELECT LTRIM(RTRIM(kode)) as kode, nip, nama from ms_ttd WHERE nip ='" . $pakpa . "'";
        $xttd = $this->db->query($aa)->row();
        $kdttd = $xttd->kode;
        $nipttd = $xttd->nip;
        $nmttd = $xttd->nama;
        $jbtttd = '';

        if ($kdttd == 'PA') {
            $jbtttd = 'Pengguna Anggaran';
        } else {
            $jbtttd = 'Kuasa Pengguna Anggaran';
        }



        if ($jns == 1) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,b.kd_bidang_urusan,b.nm_bidang_urusan,a.no_spd,a.nilai FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $tanggal1 = $this->tanggal_format_indonesia($tgl);
                $bln = $this->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }
            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . " </td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN PENGAJUAN SPP - UP </u></strong></td></tr>
                    <tr><td align=\"center\"><strong>Nomor :$nomor </strong></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\"></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Sehubungan dengan Surat Permintaan Pembayaran Uang Persediaan (SPP - UP) Nomor $nomor Tanggal $tanggal1 yang kami ajukan sebesar
                    $nilai ($a)</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Untuk Keperluan OPD : $nm_skpd Tahun Anggaran $thn_ang </td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Dengan ini menyatakan sebenarnya bahwa :</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran UP tersebut di atas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanan sesuai DPA-OPD</td>
                                     </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Jumlah Pembayaran UP tersebut tidak akan dipergunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku
                                     harus dilakasanakan dengan Pembayaran Langsung (UP/GU/TU/LS-Barang dan Jasa)
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian Surat pernyataan ini dibuat untuk melengkapi persyaratan pengajuan SPM-UP OPD kami</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";
            $data['prev'] = $cRet;
            $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
        }

        if ($jns == 2) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $tgl = $row->tgl_spp;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tanggal_format_indonesia($tgl);
                $bln = $this->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN TANGGUNG JAWABAN BELANJA</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">1. OPD </td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $kd - $nm_skpd</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">2. Satuan Kerja</td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $kd - $nm_skpd</td>
                                     </tr>";

            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">3. Tanggal/NO. DPA</td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $tgl_dpa dan $no_dpa</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">4. Tahun Anggaran</td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $thn_ang</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">5. Jumlah Belanja </td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    Rp. $nilai</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">&nbsp; </td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\"></td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    &nbsp;</td>
                                     </tr>";
            $cRet .=       " </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Yang bertanda tangan di bawah ini adalah $jabatan Satuan Kerja $nm_skpd Menyatakan bahwa saya bertanggung jawab penuh atas segala pengeluaran-pengeluaran
                    yang telah dibayar lunas oleh Bendahara Pengeluaran kepada yang berhak menerima, sebagaimana tertera dalam Laporan Pertanggung Jawaban Ganti Uang di sampaikan oleh Bendahara Pengeluaran
                    <br>
                    <br>
                    Bukti-bukti belanja tertera dalam Laporan Pertanggung Jawaban Ganti Uang disimpan sesuai ketentuan yang berlaku pada sistem Satuan Kerja $nm_skpd
                    untuk kelengkapan administrasi dan keperluan pemeriksaan aparat pengawasan Fungsional
                    <br>
                    <br>
                    Demikian Surat Pernyataan ini dibuat dengan sebenarnya</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($print == '1') {

                //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }


        if ($jns == 3) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp,b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $tgl = $row->tgl_spp;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tanggal_format_indonesia($tgl);
                $bln = $this->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN TANGGUNG JAWABAN BELANJA</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">
                     
                        ";


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">1. OPD</td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $kd - $nm_skpd</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">2. Satuan Kerja</td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $kd - $nm_skpd</td>
                                     </tr>";

            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">3. Tanggal/NO. DPA</td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $tgl_dpa dan $no_dpa</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">4. Tahun Anggaran</td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    $thn_ang</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">5. Jumlah Belanja </td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    Rp. $nilai</td>
                                     </tr>";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">&nbsp; </td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\"></td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    &nbsp;</td>
                                     </tr>";
            $cRet .=       " </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Yang bertanda tangan di bawah ini adalah $jabatan Satuan Kerja $nm_skpd Menyatakan bahwa saya bertanggung jawab penuh atas segala pengeluaran-pengeluaran
                    yang telah dibayar lunas oleh Bendahara Pengeluaran kepada yang berhak menerima, sebagaimana tertera dalam Laporan Pertanggung Jawaban Tambah Uang di sampaikan oleh Bendahara Pengeluaran
                    <br>
                    <br>
                    Bukti-bukti belanja tertera dalam Laporan Pertanggung Jawaban Tambah Uang disimpan sesuai ketentuan yang berlaku pada sistem Satuan Kerja $nm_skpd
                    untuk kelengkapan administrasi dan keperluan pemeriksaan aparat pengawasan Fungsional
                    <br>
                    <br>
                    Demikian Surat Pernyataan ini dibuat dengan sebenarnya</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td></tr>
                    <tr><td align=\"center\" width=\"25%\"></td>                    
                    <td align=\"center\" width=\"25%\">$jabatan</td></tr>
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                    <tr><td align=\"center\" width=\"25%\"> </td>                    
                    <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                     $pangkat <br>
                     NIP. $nip</td></tr>                              
                    <tr><td align=\"center\" width=\"25%\">&nbsp;</td>                    
                    <td align=\"center\" width=\"25%\">&nbsp;</td></tr>
                  </table>";

            $data['prev'] = $cRet;
            if ($print == '1') {

                //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }


        if ($jns == 4) {

            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $jns_bbn = $row->jns_beban;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';


            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Gaji dan Tunjangan";
                    break;
                case '2': //GU
                    $lcbeban = "Uang Kespeg";
                    break;
                case '3': //TU
                    $lcbeban = "Uang Makan";
                    break;
                case '4': //TU
                    $lcbeban = "Upah Pungut";
                    break;
                case '5': //TU
                    $lcbeban = "Upah Pungut PBB";
                    break;
                case '6': //TU
                    $lcbeban = "Upah Pungut PBB-KB PKB & BBN-KB ";
                    break;
                case '7': //TU
                    $lcbeban = "Gaji & Tunjangan";
                    break;
                case '8': //TU
                    $lcbeban = "Tunjangan Transport";
                    break;
                case '9': //TU
                    $lcbeban = "Tunjangan Lainnya";
                    break;
                default:
                    $lcbeban = "LS";
            }
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Yang Bertanda tangan di bawah ini:</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">Nama</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    $nama                                     
                                    </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">NIP</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    $nip                                     
                                    </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">Jabatan</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    $jabatan                                     
                                    </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    &nbsp;                                     
                                    </tr>
                                     ";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"left\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Perhitungan yang terdapat pada Daftar Perhitungan Tambahan Penghasilan bagi PNS di Lingkungan " . $client->pem . "
" . $client->nm_kab . "                                    (" . strtoupper($lcbeban) . ") bulan $bln $thn_ang bagi $nm_skpd telah dhitung dengan benar dan berdasarkan daftar hadir kerja Pegawai Negeri Sipil
                                    Daerah pada $nm_skpd                                    
                                    </td> </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"left\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Apabila dikemudian hari terdapat kelebihan atas pembayaran " . strtoupper($lcbeban) . " tersebut, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Daerah
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian  pernyataan ini kami buat dengan sebenar-benarnya.</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">Mengetahui,<br>$jbtttd</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">
                        </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nmttd</u></b><br>
                            $pangkat <br>
                        </td>
                     </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"></td>                    
                        <td align=\"center\" width=\"25%\">NIP. $nipttd</td>
                    </tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($print == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }
        if ($jns == 6) {
            $sql1 = "SELECT a.no_spp,a.tgl_spp,a.kd_skpd,a.nm_skpd,a.bulan,a.nmrekan, a.no_rek,a.npwp, a.jns_beban, b.kd_bidang_urusan, b.nm_bidang_urusan, a.bank
                , ( SELECT 
                            nama 
                        FROM
                            ms_bank
                        WHERE 
                            kode=a.bank
                ) AS nama_bank, 
                a.no_spd,a.nilai
                FROM trhspp a INNER JOIN ms_bidang_urusan b 
                ON SUBSTRING(a.kd_skpd,1,4)=b.kd_bidang_urusan  where a.no_spp='$nomor' AND a.kd_skpd='$kd'";
            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $kd_urusan = $row->kd_bidang_urusan;
                $nm_urusan = $row->nm_bidang_urusan;
                $kd_skpd = $row->kd_skpd;
                $nm_skpd = $row->nm_skpd;
                $spd = $row->no_spd;
                $tgl = $row->tgl_spp;
                $jns_bbn = $row->jns_beban;
                $nama_bank = $row->nama_bank;
                $no_rek = $row->no_rek;
                $npwp = $row->npwp;
                $rekan = $row->nmrekan;
                $tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl);
                $bln = $this->getBulan($row->bulan);
                $nilai = number_format($row->nilai, "2", ",", ".");
                $nilai1 = $row->nilai;
                $a = $this->tukd_model->terbilang($nilai1);
                //echo($a);
            }

            $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
            }

            $thn_ang       = $this->session->userdata('pcThang');
            if ($tanpa == 1) {
                $tanggal = "_______________________$thn_ang";
            } else {
                $tanggal = $tanggal1;
            }

            $cRet = '';


            switch ($jns_bbn) {
                case '1': //UP
                    $lcbeban = "Gaji dan Tunjangan";
                    break;
                case '2': //GU
                    $lcbeban = "Uang Kespeg";
                    break;
                case '3': //TU
                    $lcbeban = "Uang Makan";
                    break;
                case '4': //TU
                    $lcbeban = "Upah Pungut";
                    break;
                case '5': //TU
                    $lcbeban = "Upah Pungut PBB";
                    break;
                case '6': //TU
                    $lcbeban = "Upah Pungut PBB-KB PKB & BBN-KB ";
                    break;
                case '7': //TU
                    $lcbeban = "Gaji & Tunjangan";
                    break;
                case '8': //TU
                    $lcbeban = "Tunjangan Transport";
                    break;
                case '9': //TU
                    $lcbeban = "Tunjangan Lainnya";
                    break;
                default:
                    $lcbeban = "LS";
            }
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        <td rowspan=\"5\" align=\"center\">
                        <img src=\"" . base_url() . "image/no-image.png\"  width=\"75\" height=\"100\" />
                        </td>
                    </tr>";



            if (substr($kd, 0, 7) == $this->org_keu && $kd != $this->skpd_keu) {
                $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
                $cRet .= "<tr><td align=\"center\" style=\"font-size:13px\">$nm_org</tr>";
            }

            $cRet .= "    
                    <tr><td align=\"center\" style=\"font-size:13px\"><pre style=\"font-family: Times New Roman;\">" . strtoupper($nm_skpd) . "</pre></td></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">$alamat_skpd</td></tr>
                    <tr><td align=\"center\">" . strtoupper($daerah) . "</td>  </tr>
                    </table>
                    <hr  width=\"100%\"> 
                    ";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                    <tr><td align=\"left\">Yang Bertanda tangan di bawah ini:</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">Nama</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    $nama                                     
                                    </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">NIP</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    $nip                                     
                                    </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">Jabatan</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    $jabatan                                     
                                    </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                    &nbsp;                                     
                                    </tr>
                                     ";
            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"$spasi\">";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"left\">1.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Perhitungan yang terdapat pada Daftar Perhitungan Tambahan Penghasilan bagi PNS di Lingkungan " . $client->pem . "
" . $client->nm_kab . "                                    (" . strtoupper($lcbeban) . ") bulan $bln $thn_ang bagi $nm_skpd telah dhitung dengan benar dan berdasarkan daftar hadir kerja Pegawai Negeri Sipil
                                    Daerah pada $nm_skpd                                    
                                    </td> </tr>
                                     ";
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"left\">2.</td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"90%\" align=\"justify\">
                                     Apabila dikemudian hari terdapat kelebihan atas pembayaran " . strtoupper($lcbeban) . " tersebut, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Daerah
                                     </tr>
                                     ";

            $cRet .=       " </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    
                    <tr><td align=\"justify\">Demikian  pernyataan ini kami buat dengan sebenar-benarnya.</td></tr>
                    <tr><td align=\"left\">&nbsp;</td></tr>
                  </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td align=\"center\" width=\"25%\">Mengetahui,</td>                    
                        <td align=\"center\" width=\"25%\">" . $client->tetapkan . ", $tanggal</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">$jbtttd</td>                    
                        <td align=\"center\" width=\"25%\">$jabatan</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">&nbsp;</td>                    
                        <td align=\"center\" width=\"25%\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\"><b><u>$nmttd</u></b><br>
                            $pangkat 
                        </td>                    
                        <td align=\"center\" width=\"25%\"><b><u>$nama</u></b><br>
                            $pangkat <br>
                        </td>
                     </tr>

                    <tr>
                        <td align=\"center\" width=\"25%\">NIP. $nipttd</td>                    
                        <td align=\"center\" width=\"25%\">NIP. $nip</td>
                    </tr>
                  </table>";
            $data['prev'] = $cRet;
            if ($print == '1') {
                $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
            }
            if ($print == '0') {
                echo $cRet;
            }
        }
    }


    // GU

    function sppgu()
    {
        $data['page_title'] = 'SPP GU';
        $this->template->set('title', 'SPP GU');
        $this->template->load('template', 'tukd/spp/tambah_spp_gu_ar', $data);
    }

    function load_spp_gu()
    {

        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = "and jns_spp='2' ";
        if ($kriteria <> '') {
            $where = "where and jns_spp='2' and ((upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%'))) ";
        }

        $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by no_spp,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spp'    => $resulte['no_spp'],
                'tgl_spp'   => $resulte['tgl_spp'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'nm_skpd'   => $resulte['nm_skpd'],
                'jns_spp'   => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan'     => $resulte['bulan'],
                'no_spd'    => $resulte['no_spd'],
                'no_spd2'   => $resulte['no_spd2'],
                'no_spd3'   => $resulte['no_spd3'],
                'no_spd4'   => $resulte['no_spd4'],
                'bank'      => $resulte['bank'],
                'nmrekan'   => $resulte['nmrekan'],
                'no_rek'    => $resulte['no_rek'],
                'npwp'      => $resulte['npwp'],
                'status'    => $resulte['status'],
                'no_bukti'  => $resulte['no_bukti'],
                'no_bukti2' => $resulte['no_bukti2'],
                'no_bukti3' => $resulte['no_bukti3'],
                'no_bukti4' => $resulte['no_bukti4'],
                'no_bukti5' => $resulte['no_bukti5'],
                'status' => $resulte['status'],
                'no_lpj' => $resulte['no_lpj'],
                'sp2d_batal' => $this->support->nvl($resulte['sp2d_batal'], ''),
                'ket_batal' => $this->support->nvl($resulte['ket_batal'], ''),
                'urut' => $resulte['urut']

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function nlpj()
    {
        $skpd  = $this->session->userdata('kdskpd');
        /*$sql   = "select DISTINCT no_lpj,tgl_lpj FROM trlpj WHERE no_lpj NOT IN(
        SELECT no_lpj FROM trhspp WHERE no_lpj IN(SELECT no_lpj FROM trlpj)) and substr(kd_kegiatan,6,10) ='$skpd'  ";
        */

        // $sql = "SELECT DISTINCT no_lpj,tgl_lpj FROM trhlpj WHERE status='2' AND jenis = '1' AND kd_skpd = '$skpd' AND no_lpj 
        //         NOT IN(select ISNULL(no_lpj,'') FROM trhspp WHERE kd_skpd='$skpd' AND jns_spp = '2' and (sp2d_batal<>'1' or sp2d_batal is null))";
        
        $sql = "SELECT DISTINCT no_lpj,tgl_lpj FROM trhlpj WHERE jenis = '1' AND kd_skpd = '$skpd' and status='2' AND no_lpj 
                NOT IN(select ISNULL(no_lpj,'') FROM trhspp WHERE kd_skpd='$skpd' AND jns_spp IN('2','7') and (sp2d_batal<>'1' or sp2d_batal is null))";
        

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_lpj' => $resulte['no_lpj'],
                'tgl_lpj' => $resulte['tgl_lpj']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function select_data_tran_5($no_bukti1 = '')
    {
        $kd_skpd   = $this->session->userdata('kdskpd');
        $no_bukti1 = $this->input->post('no_bukti1');

        $lcfilt = $no_bukti1;
        $lc     = '';
        if ($lcfilt != '') {
            $lcfilt = str_replace('A', "'", $lcfilt);
            $lcfilt = str_replace('B', ",", $lcfilt);
            $lc = " and no_bukti not in ($lcfilt) ";
        }

        $sql    = " SELECT no_bukti FROM trhtransout where kd_skpd='$kd_skpd' $lc ORDER BY no_bukti ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx'      => $ii,
                'no_bukti' => $resulte['no_bukti']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_data_transaksi($kdskpd = '', $nolpj = '')
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nolpj = $this->input->post('nolpj');

        $sql = "SELECT a.kd_sub_kegiatan,a.kd_rek6, a.nm_rek6, a.nilai ,a.no_bukti,a.no_lpj, b.kd_skpd,(select TOP 1 sumber from trdtransout where no_bukti=a.no_bukti and kd_skpd=a.kd_skpd)as sumber FROM trlpj a INNER JOIN trhlpj b 
                    ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                WHERE b.kd_skpd='$kdskpd' and a.no_lpj='$nolpj' ORDER BY a.no_bukti, a.kd_sub_kegiatan, a.kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx' => $ii,
                'kdsubkegiatan' => $resulte['kd_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_rek6'],
                'nilai1'     => number_format($resulte['nilai'], 2, '.', ','),
                'no_bukti'   => $resulte['no_bukti'],
                'sumber'   => $resulte['sumber']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_sum_lpj_gu()
    {
        $skpd = $this->session->userdata('kdskpd');
        $xlpj = $this->input->post('lpj');
        $query1 = $this->db->query("SELECT SUM(a.nilai) AS jml FROM trlpj a WHERE no_lpj='$xlpj' AND kd_skpd='$skpd' ");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'cjumlah'  =>  $resulte['jml']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function ckunci_sementara()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd from ms_skpd where kunci=1 and kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);
        $ii = 0;
        $result = array('id' => $ii, 'kode' => '');
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kode' => $resulte['kd_skpd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
    // GU




    // LS START
    function perusahaan()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kd_skpdd = substr($kd_skpd, 0, 22);
        $sql = "SELECT kode,nama as nmrekan, pimpinan, npwp, alamat, rekening FROM ms_perusahaan WHERE kd_skpd = '$kd_skpd' AND UPPER(nama) LIKE UPPER('%$lccr%') GROUP BY kode, nama, pimpinan, npwp, alamat, rekening";
        // $sql = "
        //         SELECT z.* FROM (
        //         SELECT nama as nmrekan, pimpinan, npwp, alamat FROM ms_perusahaan WHERE left(kd_skpd,22) = '$kd_skpdd'   
        //             AND UPPER(nama) LIKE UPPER('%$lccr%')
        //             GROUP BY nama, pimpinan, npwp, alamat
        //         UNION ALL       
        //         SELECT nmrekan, pimpinan, npwp, alamat FROM trhspp WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
        //             AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
        //             GROUP BY nmrekan, pimpinan, npwp, alamat
        //         UNION ALL
        //         SELECT nmrekan, pimpinan, npwp, alamat FROM trhtrmpot_cmsbank WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
        //             AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
        //             GROUP BY nmrekan, pimpinan, npwp, alamat
        //         UNION ALL
        //         SELECT nmrekan, pimpinan, npwp, alamat FROM trhtrmpot WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
        //             AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
        //             GROUP BY nmrekan, pimpinan, npwp, alamat
        //        )z GROUP BY z.nmrekan, z.pimpinan, z.npwp, z.alamat
        //         ORDER BY z.nmrekan, z.pimpinan, z.npwp, z.alamat     
        //             ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nmrekan' => $resulte['nmrekan'],
                'pimpinan' => $resulte['pimpinan'],
                'npwp' => $resulte['npwp'],
                'alamat' => $resulte['alamat'],
                'rekening' => $resulte['rekening']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_spp()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " AND jns_spp <> '1' AND jns_spp <> '2'  AND jns_spp <> '3' ";
        if ($kriteria <> '') {
            $where = " AND jns_spp <> '1' AND jns_spp <> '2'  AND jns_spp <> '3'  AND (upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhspp WHERE kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        //alert('aaa');

        $sql = "SELECT TOP $rows * from trhspp WHERE kd_skpd = '$kd_skpd' $where and no_spp not in (SELECT TOP $offset no_spp from trhspp WHERE kd_skpd = '$kd_skpd' $where order by tgl_spp,no_spp) order by tgl_spp,no_spp";
        // $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by no_spp,kd_skpd";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'urut' => $resulte['urut'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'jns_beban' => $resulte['jns_beban'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'status' => $resulte['status'],
                'kd_kegiatan' => $resulte['kd_kegiatan'],
                'nm_kegiatan' => $resulte['nm_kegiatan'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'dir' => $resulte['pimpinan'],
                'no_tagih' => $resulte['no_tagih'],
                'tgl_tagih' => $resulte['tgl_tagih'],
                'alamat' => $resulte['alamat'],
                'lanjut' => $resulte['lanjut'],
                'kontrak' => $resulte['kontrak'],
                'tgl_mulai' => $resulte['tgl_mulai'],
                'tgl_akhir' => $resulte['tgl_akhir'],
                'sts_tagih' => $resulte['sts_tagih'],
                'tot_spp_' => $resulte['nilai'],
                'ket_batal' => $resulte['ket_batal'],
                'sp2d_batal' => $resulte['sp2d_batal'],
                'bidang' => $kd_skpd

            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
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
        }


        echo json_encode($result);
    }

    function load_ttd_cek($ttd)
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $cek = substr($kd_skpd, 8, 2);
        //echo $cek;
        $init = "kd_skpd = '$kd_skpd'";
        if ($cek != '00') {
            if ($ttd == 'BK') {
                $ttd = 'BPP';
            }

            if ($ttd == 'PA') {
                $init = "left(kd_skpd,22) = left('$kd_skpd',22)";
            }
        }


        //$kdskpd = substr($kd_skpd,0,7);
        $sql = "SELECT * FROM ms_ttd WHERE $init and kode='$ttd'";

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

    function load_no_penagihan()
    {
        $cskpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');

        $sql = "SELECT a.kd_skpd,a.no_bukti, tgl_bukti, a.ket,a.kontrak,kd_sub_kegiatan,SUM(b.nilai) as total 
                FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
                WHERE a.kd_skpd='$cskpd' and a.jns_trs='1' and (upper(a.kd_skpd) like upper('%$lccr%') or  
                upper(a.no_bukti) like upper('%$lccr%')) and a.no_bukti not in
                (SELECT isnull(no_tagih,'') no_tagih from trhspp WHERE kd_skpd = '$cskpd' and sts_tagih != '0' AND (sp2d_batal IS NULL OR sp2d_batal !=1) GROUP BY no_tagih)
                GROUP BY a.kd_skpd, a.no_bukti,tgl_bukti,a.ket,a.kontrak,kd_sub_kegiatan order by a.no_bukti";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_tagih' => $resulte['no_bukti'],
                'tgl_tagih' => $resulte['tgl_bukti'],
                'kd_skpd' => $resulte['kd_skpd'],
                'ket' => $resulte['ket'],
                // 'sumber' => $resulte['sumber'],
                'kegiatan' => $resulte['kd_sub_kegiatan'],
                'kontrak' => $resulte['kontrak'],
                'nila' => number_format($resulte['total'], 2, '.', ','),
                'nil' => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function config_npwp()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT npwp,rekening FROM ms_skpd a WHERE a.kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'npwp' => $resulte['npwp'],
                'rekening' => $resulte['rekening']
            );
        }
        echo json_encode($result);
    }

    function kegi()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $data1 = $this->cek_anggaran_model->cek_anggaran($skpd);
        $spd = $this->input->post('spd');
        $lccr = $this->input->post('q');
        $sql  = "SELECT DISTINCT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,b.nm_program,a.nilai,b.kd_skpd as bidang FROM trdspd a INNER JOIN trskpd b ON 
                a.kd_sub_kegiatan=b.kd_sub_kegiatan where a.no_spd='$spd' and b.jns_ang='$data1' AND b.status_sub_kegiatan='1' AND
                (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(b.nm_sub_kegiatan) like upper('%$lccr%')) order by  a.kd_sub_kegiatan ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                //                     'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                //                   'nm_sub_kegiatan' => $resulte['nm_subkegiatan'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'nilai_spd' => $resulte['nilai'],
                'kdbidang' => $resulte['bidang'],
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function select_data2()
    {
        $kegi = $this->input->post('giat');
        $sql = "SELECT a.kd_sub_kegiatan,c.nm_sub_kegiatan,a.kd_rek6,b.nm_rek6,a.nilai,(SELECT SUM(nilai) FROM trdspp WHERE kd_sub_kegiatan =a.kd_sub_kegiatan AND kd_rek6=a.kd_rek6)AS total 
                FROM trdrka a INNER JOIN ms_rek5 b ON a.kd_rek6=b.kd_rek6 INNER JOIN trskpd d ON a.kd_sub_kegiatan=d.kd_sub_kegiatan INNER JOIN m_sub_giat c ON d.kd_sub_kegiatan=c.kd_sub_kegiatan
                WHERE a.kd_sub_kegiatan ='$kegi' ORDER BY a.kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'nilai' => number_format($resulte['nilai'], "2", ",", "."),
                'total' => number_format($resulte['total'], "2", ",", "."),
                'a' => $resulte['nilai'],
                'b' => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function skpd_2()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where kd_skpd = '$kd_skpd' ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],

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
        when b.jns_ang='U1' then 'Perubahan'
        when b.jns_ang='U2' then 'Perubahan II' end)as nm_ang FROM ms_skpd a LEFT JOIN trhrka b
                    ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' and 
                    tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
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

    function cek_status_angkas()
    {
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
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'nama' => $resulte['status']
            );
        }
        echo json_encode($result);
        $query1->free_result();
    }


    function select_data_tagih($no = '')
    {
        $no_tagih = $this->input->post('no');
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM trdtagih WHERE no_bukti='$no_tagih' AND kd_skpd = '$kd_skpd' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'nmkegiatan' => $resulte['nm_sub_kegiatan'],
                'sumber'     => $resulte['sumber'],
                'nmsumber'   => $this->tukd_model->get_nama($resulte['sumber'], 'nm_sumber_dana1', 'sumber_dana', 'kd_sumber_dana1'),
                // 'nmsubkegiatan' => $resulte['nm_subkegiatan'],       
                'kdrek6'     => $resulte['kd_rek'],
                'nmrek6'     => $resulte['nm_rek6'],
                'nilai1'     => number_format($resulte['nilai'], "2", ".", ","),
                'nilai'      => number_format($resulte['nilai'])
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_trskpd_ar_2()
    {
        $cskpd  =  '';
        $cskpd  =  $this->input->post('kdskpd');
        $dcskpd  = substr($cskpd, 0, 22);
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($dcskpd);
        $sql    = "SELECT kd_sub_kegiatan, nm_sub_kegiatan FROM trskpd where left(kd_skpd,22) = '$dcskpd' AND status_sub_kegiatan='1' AND jns_ang='$jns_ang' order by kd_sub_kegiatan ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function load_trskpd_ar_tu()
    {
        $cskpd  =  '';
        $cskpd  =  $this->input->post('kdskpd');
        $dcskpd  = substr($cskpd, 0, 22);
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($dcskpd);
        $sql    = "SELECT kd_sub_kegiatan, nm_sub_kegiatan FROM trskpd where kd_skpd = '$cskpd' AND jns-ang='$jns_ang' order by kd_sub_kegiatan ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function cetakspp77_rinci()
    {
        $cetak = $this->uri->segment(3);
        // echo($cetak);
        $kd = $this->uri->segment(5);
        // echo($kd);
        $kd1 = substr($kd, 0, 17);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $spasi = $this->uri->segment(10);

        $nm_skpd = $this->rka_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        $tgl_spp = $this->rka_model->get_nama($nomor, 'tgl_spp', 'trhspp', 'no_spp');
        // $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');
        $jns_bbn = $this->rka_model->get_nama($nomor, 'jns_beban', 'trhspp', 'no_spp');
        $npwp = $this->rka_model->get_nama($kd, 'npwp', 'ms_skpd', 'kd_skpd');
        $BK = str_replace('123456789', ' ', $this->uri->segment(8));
        $PA = str_replace('123456789', ' ', $this->uri->segment(7));
        $thn_ang       = $this->session->userdata('pcThang');
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' AND kd_skpd='$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        $sqlttd3 = "SELECT nama as nm3,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PA'  AND kd_skpd='$kd'";
        $sqlttd3 = $this->db->query($sqlttd3);
        foreach ($sqlttd3->result() as $rowttd3) {
            $nip3       = $rowttd3->nip;
            $nama3      = $rowttd3->nm3;
            $jabatan3   = $rowttd3->jab;
            $pangkat3   = $rowttd3->pangkat;
        }

        $sqlgiat = "SELECT kd_sub_kegiatan FROM trdspp WHERE no_spp='$nomor' GROUP BY kd_sub_kegiatan";
        $sqlgiat = $this->db->query($sqlgiat);
        foreach ($sqlgiat->result() as $rowgiat) {
            $giatspp = $rowgiat->kd_sub_kegiatan;
        }

        if ($jns == 1) {
            $jenisspp = 'UANG PERSEDIAAN (SPP-UP)';
        } else if ($jns == 2) {
            $jenisspp = 'GANTI UANG PERSEDIAAN (SPP-GU)';
        } else if ($jns == 3) {
            $jenisspp = 'TAMBAHAN UANG PERSEDIAAN (SPP-TU)';
        } else if ($jns == 4) {
            $jenisspp = 'LANGSUNG (SPP-LS) GAJI DAN TUNJANGAN';
        } else if ($jns == 5) {
            $jenisspp = 'LANGSUNG (SPP-LS) PIHAK KETIGA LAINNYA';
        } else if ($jns == 6) {
            $jenisspp = 'LANGSUNG (SPP-LS) BARANG DAN JASA';
        }

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr>
                            <td align=\"center\" style=\"font-size:16px\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:16px\"><strong>SURAT PERMINTAAN PEMBAYARAN $jenisspp </strong></td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:14px\">Nomor : $nomor </td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:14px\">Tahun Anggaran : $thn_ang </td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:14px\">&nbsp; </td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:16px\"><b>RINCIAN RENCANA PENGGUNAAN</b></td>
                        </tr>

                        
                </table><br />";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"4\">
                    <tr>
                        <td width=\"5%\" align=\"center\"><b>No</b></td>
                        <td width=\"20%\" align=\"center\"><b>Kode Rekening</b></td>
                        <td width=\"50%\" align=\"center\"><b>Uraian</b></td>
                        <td width=\"25%\" align=\"center\"><b>Nilai Rupiah</b></td>
                    </tr>";

        $sqlspp = "SELECT a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan,sum(b.nilai)as nilaisub FROM trhspp a inner join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.no_spp='$nomor' AND b.kd_skpd='$kd' GROUP BY a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan";
        $nilaitotspp = 0;
        $sqlspps = $this->db->query($sqlspp);
        foreach ($sqlspps->result() as $rowspp) {
            $kd_sub_kegiatan    = $rowspp->kd_sub_kegiatan;
            $nm_sub_kegiatan    = $rowspp->nm_sub_kegiatan;
            $nilaisub           = $rowspp->nilaisub;
            $nm_kegiatan        = "UP";

            $cRet .= "<tr>
                    <td align=\"left\">01</td>
                    <td colspan=\"2 \"align=\"left\"> UP (Uang Persediaan)</td>
                    <td align=\"left\">Rp. " . number_format($nilaisub, "2", ".", ",") . "</td>
                    </tr>";

            $sqlspp_rinci = "SELECT a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan,kd_rek6,nm_rek6,sum(b.nilai)as nilaispp FROM trhspp a inner join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.no_spp='$nomor' AND b.kd_skpd='$kd' and b.kd_sub_kegiatan='$rowspp->kd_sub_kegiatan' GROUP BY a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan,kd_rek6,nm_rek6";
            $no = 0;

            $sqlspp_rincis = $this->db->query($sqlspp_rinci);
            foreach ($sqlspp_rincis->result() as $rowspp_rinci) {
                $kd_rek6    = $rowspp_rinci->kd_rek6;
                $nm_rek6    = $rowspp_rinci->nm_rek6;
                $nilairinci    = $rowspp_rinci->nilaispp;


                $no = $no + 1;
                $cRet .= "<tr>
                                        <td width=\"5%\" align=\"center\">$no</td>
                                        <td width=\"20%\" align=\"left\">$kd_rek6</td>
                                        <td width=\"50%\" align=\"left\">$nm_rek6</td>
                                        <td width=\"25%\" align=\"right\">" . number_format($nilairinci, "2", ".", ",") . "</td>
                                    </tr>";
            }
            $nilaitotspp = $nilaitotspp + $nilaisub;
        }







        $cRet .= "</table>";
        if ($giatspp == '5.02.00.0.06.62') {
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                        <td width=\"50%\" align=\"left\">&nbsp;</td>
                        <td width=\"50%\" align=\"right\">TOTAL Rp. " . number_format($nilaitotspp, "2", ".", ",") . "&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" align=\"left\"><i>Terbilang: " . $this->tukd_model->terbilang($nilaitotspp) . "</i></td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" align=\"left\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"left\">&nbsp;</td>
                        <td width=\"50%\" align=\"right\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"></td>
                        <td width=\"50%\" align=\"center\">Pontianak," . $this->tukd_model->tanggal_format_indonesia($tgl_spp) . "</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"><b></b></td>
                        <td width=\"50%\" align=\"center\"><b>$jabatan</b></td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"><u></u></td>
                        <td width=\"50%\" align=\"center\"><u>$nama</u></td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"></td>
                        <td width=\"50%\" align=\"center\">NIP.$nip</td>
                    </tr>



                </table>";
        } else {
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                        <td width=\"50%\" align=\"left\">&nbsp;</td>
                        <td width=\"50%\" align=\"right\">TOTAL Rp. " . number_format($nilaitotspp, "2", ".", ",") . "&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" align=\"left\"><i>Terbilang: " . $this->tukd_model->terbilang($nilaitotspp) . "</i></td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" align=\"left\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"left\">&nbsp;</td>
                        <td width=\"50%\" align=\"right\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">Mengetahui/Menyetujui:</td>
                        <td width=\"50%\" align=\"center\">Melawi," . $this->tukd_model->tanggal_format_indonesia($tgl_spp) . "</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"><b>$jabatan3</b></td>
                        <td width=\"50%\" align=\"center\"><b>$jabatan</b></td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"><u>$nama3</u></td>
                        <td width=\"50%\" align=\"center\"><u>$nama</u></td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">NIP. $nip3</td>
                        <td width=\"50%\" align=\"center\">NIP.$nip</td>
                    </tr>



                </table>";
        }



        if ($cetak == '1') {
            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($cetak == '0') {
            echo $cRet;
        }
    }

    function load_reksumber_dana()
    {
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $jnsang = $this->cek_anggaran_model->cek_anggaran($kode);
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');
        $sttang   = $this->input->post('sttang');

        $sql = "SELECT sumber as sumber_dana,nm_sumber as nm_sumber_dana,sum(total) as nilai , (SELECT ISNULL(SUM(nilai),0) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND u.kd_skpd = '$kode' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' ) and sumber=sumber)as lalu from trdpo where kd_sub_kegiatan = '$giat' and kd_rek6 = '$rek' and kd_skpd = '$kode' and jns_ang = '$jnsang' GROUP BY sumber, nm_sumber";

        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'sumber_dana'       => $resulte['sumber_dana'],
                'nm_sumber_dana'    => $this->tukd_model->get_nama($resulte['sumber_dana'], 'nm_sumber_dana1', 'sumber_dana', 'kd_sumber_dana1'),
                'nilaidana'         => $resulte['nilai'],
                'nilaidana_lalu'    => $resulte['lalu']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek_ar()
    {
        $kode  = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $skpd  = $this->session->userdata('kdskpd');
        $ckdkegi  = $this->input->post('kdkegiatan');
        // echo($ckdkegi);
        $ckdrek   = $this->input->post('kdrek');
        $bbn = $this->input->post('bbn');
        // $jns_ang = $this->input->post('jns_ang');
        if ($ckdrek != '') {
            $NotIn = " and kd_rek6 not in ($ckdrek) ";
        } else {
            $NotIn = " ";
        }

        $cek = substr($ckdkegi, 17, 6);
        $in = " ";
        /*if (  $cek != '00.002' ){
            $in = " and left(kd_rek6,2) not in ('51') " ;
        } else {
            $in = " " ;
        } */

        $sql      = "SELECT kd_rek6, nm_rek6 FROM trdrka where jns_ang='$data' AND kd_sub_kegiatan = '$ckdkegi' and kd_skpd='$skpd' $NotIn $in and status_aktif='1' order by kd_rek6 ";
        $query1   = $this->db->query($sql);
        $result   = array();
        $ii       = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'      => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function jumlah_ang_spp()
    {
        $kode  = $this->input->post('kd_skpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $ckdkegi  = $this->input->post('kegiatan');
        $ckdrek   = $this->input->post('kdrek6');

        $cnospp   = $this->input->post('no_spp');
        $dckdskpd  =  $kode;
        $query1   = $this->db->query("SELECT SUM(nilai) as rektotal,
        ( SELECT SUM(nilai) FROM 
                    (select sum(a.nilai) nilai 
                    from trdspp a 
                    inner join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
                    where a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and a.kd_skpd='$dckdskpd' and a.no_spp <> '$cnospp' 
                    AND b.jns_spp IN ('3','4','5','6')
                    and (b.sp2d_batal !='1' or b.sp2d_batal IS NULL)
                    UNION ALL
                    SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and a.kd_skpd='$dckdskpd' AND b.jns_spp IN ('1','2')
                    
                    UNION ALL                    
                    SELECT SUM(a.nilai) nilai FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and a.kd_skpd='$dckdskpd' AND b.jns_spp IN ('4','6') and panjar in ('3')                    

                    UNION ALL
                    SELECT SUM(a.nilai) nilai FROM trdtransout_cmsbank a INNER JOIN trhtransout_cmsbank b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_sub_kegiatan='$ckdkegi' and a.kd_rek6='$ckdrek' and a.kd_skpd='$dckdskpd' AND b.status_validasi = '0'
                    
                    )b)
          as rektotal_spp_lalu
          FROM trdrka WHERE kd_rek6='$ckdrek' and kd_sub_kegiatan='$ckdkegi' and kd_skpd='$dckdskpd' and jns_ang='$data' ");

        $result   = array();
        $ii       = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'             => $ii,
                'nilai'          => number_format($resulte['rektotal'], 2, '.', ','),
                'nilai_spp_lalu' => number_format($resulte['rektotal_spp_lalu'], 2, '.', ',')
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function get_angkas_selisih()
    {
        $result             = $this->get_angkas_slh();
        echo $result;
    }

    function cek_status_angkas1()
    {
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
        foreach ($query1->result() as $r_trhrka) {
            $n_status = $r_trhrka->status;
        }
        return $n_status;
    }

    function get_angkas_slh($status_angkas = '', $status_anggaran = '')
    {
        $skpd               = $this->session->userdata('kdskpd');
        $status_angkas      = $this->cek_status_angkas1();
        // echo ($status_angkas);
        $status_anggaran    = $this->cek_anggaran_model->cek_anggaran($skpd);
        $kolom              = $this->tukd_model->get_nama($status_angkas, 'kode', 'tb_status_angkas', 'status_kunci');
        $sql                = "SELECT count(*)as jumlah from (
                        select kd_skpd,kd_sub_kegiatan,kd_rek6,nilai as anggaran,
                        (select sum(nilai_$status_angkas) from trdskpd_ro where kd_skpd=z.kd_skpd and kd_sub_kegiatan=z.kd_sub_kegiatan and kd_rek6=z.kd_rek6)as angkas
                        from trdrka z where jns_ang='$status_anggaran')zz where anggaran-angkas<>0 and kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);
        $ii = 0;

        foreach ($query1->result() as $resulte) {

            $jumlah = $resulte->jumlah;
        }
        return $jumlah;
    }

    function load_total_trans_spd()
    { /*untuk spp dan penagihan konsep kota*/
        $kdskpd      = $this->input->post('ckode');
        $kegiatan    = $this->input->post('cgiat');
        $rek       = $this->input->post('ckdrek6');
        $smbr_dana = $this->input->post('csumber_dn');

        $sql = "SELECT SUM(nilai) as total, SUM(nilai_spp) as total_spp FROM 
        (
        --Table tampungan // tambahan tampungan
        SELECT SUM (isnull(c.nilai,0)) as nilai, 0 as nilai_spp
        FROM trdtransout c
        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
        AND c.kd_skpd = d.kd_skpd
        WHERE c.kd_sub_kegiatan = '$kegiatan'
        AND d.kd_skpd = '$kdskpd'
        AND c.kd_rek6 = '$rek'
        AND d.jns_spp in ('1') 
        AND c.sumber='$smbr_dana'
        -- transaksi UP/GU CMS BANK Belum Validasi
        UNION ALL
        SELECT SUM (isnull(c.nilai,0)) as nilai, 0 as nilai_spp
        FROM trdtransout_cmsbank c
        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
        AND c.kd_skpd = d.kd_skpd
        WHERE c.kd_sub_kegiatan ='$kegiatan'
        AND d.kd_skpd = '$kdskpd'
        AND c.kd_rek6='$rek'
        AND d.jns_spp in ('1')
        AND (d.status_validasi='0' OR d.status_validasi is null)
        AND c.sumber='$smbr_dana'
        -- transaksi SPP SELAIN UP/GU
        UNION ALL
        SELECT SUM(isnull(x.nilai,0)) as nilai, 0 as nilai_spp FROM trdspp x
        INNER JOIN trhspp y 
        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
        WHERE x.kd_sub_kegiatan = '$kegiatan'
        AND x.kd_skpd = '$kdskpd'
        AND x.kd_rek6 = '$rek'
        AND y.jns_spp IN ('3','4','5','6')
        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
        AND x.sumber='$smbr_dana'
        -- Penagihan yang belum jadi SPP
        UNION ALL
        SELECT SUM(isnull(t.nilai,0)) as nilai, 0 as nilai_spp FROM trdtagih t 
        INNER JOIN trhtagih u 
        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
        WHERE t.kd_sub_kegiatan ='$kegiatan' 
        AND t.kd_rek ='$rek' 
        AND u.kd_skpd = '$kdskpd' 
        AND u.no_bukti 
        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
        AND t.sumber='$smbr_dana'
        )r";



        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ','),
                'total_spp' => number_format($resulte['total_spp'], 2, '.', ','),
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_trans_spd_ls()
    { /*untuk spp dan penagihan konsep kota*/
        $kdskpd     = $this->input->post('kode');
        $kegiatan   = $this->input->post('giat');
        $no_bukti   = $this->input->post('no_simpan');
        $rek        = $this->input->post('kdrek6');
        $org        = substr($kdskpd, 0, 17);
        $no_tagih   = $this->input->post('no_tagih');
        $tgl        = $this->input->post('tgl');

        $sql = "SELECT SUM(nilai) as total FROM 
                                    (
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6 = '$rek'
                                    AND d.jns_spp in ('1')
                                    AND month(d.tgl_bukti)<=month('$tgl') 
                                    
                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6='$rek'
                                    AND d.jns_spp in ('1') 
                                    AND (d.status_validasi='0' OR d.status_validasi is null)
                                    AND month(d.tgl_voucher)<=month('$tgl')
                                    
                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y 
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = '$kegiatan'
                                    AND x.kd_skpd = '$kdskpd'
                                    AND x.kd_rek6 = '$rek'
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                                    AND month(y.tgl_spp)<=month('$tgl') 
                                    
                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND t.kd_rek ='$rek' 
                                    AND u.kd_skpd = '$kdskpd' 
                                    AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
                                    AND month(u.tgl_bukti)<=month('$tgl')
                                    )r";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ','),
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_trans_spd_lama()
    {
        $kdskpd      = $this->input->post('kode');
        $jns_ang     = $this->cek_anggaran_model->cek_anggaran($kdskpd);
        $org         = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');
        $beban       = $this->input->post('beban');

        if ($beban == "3") {
            $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                        select g.kd_sub_kegiatan,sum(g.lalu) spp from(
                                SELECT b.kd_sub_kegiatan,
                                (SELECT isnull(SUM(c.nilai),0) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
                                WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                                d.kd_skpd=a.kd_skpd 
                                AND c.kd_rek6=b.kd_rek6 AND c.no_voucher <> 'x' AND c.kd_sub_kegiatan='$kegiatan') AS lalu,
                                b.nilai AS sp2d
                                FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                                INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                                INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                                WHERE b.kd_sub_kegiatan='$kegiatan'
                                )g group by g.kd_sub_kegiatan
                                
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                    left join 
                                    (
                                        
                                        select z.kd_sub_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd
                                        where left(f.kd_skpd,22)='$org' and f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan
                                        UNION ALL
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where left(f.kd_skpd,22)='$org' and f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        )z group by z.kd_sub_kegiatan
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' 
                                        AND left(u.kd_skpd,22)='$org'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,22)='$org' )
                                        GROUP BY t.kd_sub_kegiatan
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                    where a.kd_sub_kegiatan='$kegiatan' AND a.jns_ang='$jns_ang'";
        } else {
            $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0) from trskpd a left join
                                    (           
                                        select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2')  and c.kd_skpd='$org'
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_sub_kegiatan
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                    left join 
                                    (
                                        
                                        select z.kd_sub_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$org' and f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan
                                        UNION ALL
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$org' and f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        )z group by z.kd_sub_kegiatan
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$org'
                                        AND u.no_bukti 
                                        IN (select no_tagih FROM trhspp WHERE kd_skpd='$org' )
                                        GROUP BY t.kd_sub_kegiatan
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                    where a.kd_sub_kegiatan='$kegiatan' AND a.jns_ang='$jns_ang'";
        }

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


    function load_total_trans_angkas()
    {
        $kdskpd      = $this->input->post('kode');
        $kode      = $this->input->post('kode');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $org         = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');
        $beban       = $this->input->post('beban');
        $kd_rek6       = $this->input->post('kdrek6');
        $spd       = $this->input->post('spd');
        $bulan = $this->input->post('kbthbulan');

        $sq = $this->db->query("select bulan_awal,bulan_akhir from trhspd where no_spd='$spd'")->row();
        $ba = 1;
        $be = $sq->bulan_akhir;



        if ($beban == "3") {
            $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trdrka a left join
                                    (           
                                        select g.kd_sub_kegiatan,g.kd_rek6,sum(g.lalu) spp from(
                                SELECT b.kd_sub_kegiatan,b.kd_rek6,
                                (SELECT isnull(SUM(c.nilai),0) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
                                WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                                d.kd_skpd=a.kd_skpd 
                                AND c.kd_rek6=b.kd_rek6 AND c.no_voucher <> 'x' AND c.kd_sub_kegiatan='$kegiatan') AS lalu,
                                b.nilai AS sp2d
                                FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                                INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                                INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                                WHERE b.kd_skpd='$kdskpd' and b.kd_sub_kegiatan='$kegiatan' and b.kd_rek6='$kd_rek6' and (month(d.tgl_sp2d) BETWEEN '$ba' and '$bulan')
                                )g group by g.kd_sub_kegiatan,g.kd_rek6
                                
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_rek6=d.kd_rek6
                                    left join 
                                    (
                                        
                                        select z.kd_sub_kegiatan,z.kd_rek6,sum(z.transaksi) transaksi from (
                                        select f.kd_sub_kegiatan,f.kd_rek6,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$kdskpd' and f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' 
                                                                                and f.kd_rek6='$kd_rek6' and (month(e.tgl_bukti) BETWEEN '$ba' and '$bulan') group by f.kd_sub_kegiatan,f.kd_rek6
                                        UNION ALL
                                        select f.kd_sub_kegiatan,f.kd_rek6,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$kdskpd' and f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' and f.kd_rek6='$kd_rek6' and (month(e.tgl_bukti) BETWEEN '$ba' and '$bulan') group by f.kd_sub_kegiatan,
                                        f.kd_rek6
                                        )z group by z.kd_sub_kegiatan,z.kd_rek6
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan and a.kd_rek6=g.kd_rek6
                                    left join 
                                    (
                                        SELECT t.kd_sub_kegiatan,t.kd_rek6, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE  t.kd_sub_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd' and t.kd_rek6='$kd_rek6'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' ) and (month(u.tgl_bukti) BETWEEN '$ba' and '$bulan')
                                        GROUP BY t.kd_sub_kegiatan,t.kd_rek6
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan and a.kd_rek6=z.kd_rek6
                                    where a.kd_sub_kegiatan='$kegiatan' and a.kd_rek6='$kd_rek6' and a.jns_ang='$data'";
        } else if ($beban == '4') {
            $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trdrka a left join
                                    (           
                                        select c.kd_sub_kegiatan,kd_rek6,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2')  and c.kd_skpd='$org' and kd_rek6='$kd_rek6'
                                        and (sp2d_batal<>'1' or sp2d_batal is null )  and (month(b.tgl_spp) BETWEEN '$ba' and '$bulan')
                                        group by c.kd_sub_kegiatan,kd_rek6
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_rek6=d.kd_rek6
                                    left join 
                                    (
                                        
                                       select z.kd_sub_kegiatan,z.kd_rek6,sum(z.transaksi) transaksi from (
                                        select f.kd_sub_kegiatan,f.kd_rek6,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$org' and f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'' and f.kd_rek6='$kd_rek6' and (month(e.tgl_bukti) BETWEEN '$ba' and '$bulan')
                                                                                and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan,f.kd_rek6
                                        UNION ALL
                                        select f.kd_sub_kegiatan,f.kd_rek6,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$org' and  f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' and f.kd_rek6='$kd_rek6'  and (month(e.tgl_bukti) BETWEEN '$ba' and '$bulan')
                                                                                group by f.kd_sub_kegiatan,f.kd_rek6
                                       )z group by z.kd_sub_kegiatan,z.kd_rek6
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan and a.kd_rek6=g.kd_rek6
                                    left join 
                                    (
                                        SELECT t.kd_sub_kegiatan,t.kd_rek6, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' and t.kd_rek6='$kd_rek6'
                                        AND u.kd_skpd='$org' and (month(u.tgl_bukti) BETWEEN '$ba' and '$bulan')
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$org' )
                                        GROUP BY t.kd_sub_kegiatan,t.kd_rek6
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan and  a.kd_rek6=z.kd_rek6
                                    where a.kd_sub_kegiatan='$kegiatan' and a.kd_rek6='$kd_rek6' and a.jns_ang='$data'";
        } else {
            $sql = "SELECT isnull( spp, 0 ) total, isnull( transaksi, 0 ) transaksi, isnull( penagihan, 0 ) penagihan from trdrka a left join
                                    (           
                                        select c.kd_sub_kegiatan,kd_rek6,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2')  and c.kd_skpd='$org' and kd_rek6='$kd_rek6'
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) and (month(b.tgl_spp) BETWEEN '$ba' and '$bulan')
                                        group by c.kd_sub_kegiatan,kd_rek6
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_rek6=d.kd_rek6
                                    left join 
                                    (
                                        
                                       select z.kd_sub_kegiatan,z.kd_rek6,sum(z.transaksi) transaksi from (
                                        select f.kd_sub_kegiatan,f.kd_rek6,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$org' and  f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'' and f.kd_rek6='$kd_rek6' and (month(e.tgl_bukti) BETWEEN '$ba' and '$bulan')
                                        and e.status_validasi='0' group by f.kd_sub_kegiatan,f.kd_rek6 
                                        UNION ALL
                                        select f.kd_sub_kegiatan,f.kd_rek6,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$org' and  f.kd_sub_kegiatan='$kegiatan' and f.kd_rek6='$kd_rek6' and (month(e.tgl_bukti) BETWEEN '$ba' and '$bulan')
                                        group by f.kd_sub_kegiatan,f.kd_rek6
                                       )z group by z.kd_sub_kegiatan,z.kd_rek6
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan and a.kd_rek6=g.kd_rek6
                                    left join 
                                    (
                                        SELECT t.kd_sub_kegiatan,t.kd_rek6, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' and t.kd_rek6='$kd_rek6'
                                        AND u.kd_skpd='$org'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$org' ) and (month(u.tgl_tagih) BETWEEN '$ba' and '$bulan')
                                        GROUP BY t.kd_sub_kegiatan,t.kd_rek6
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan and  a.kd_rek6=z.kd_rek6
                                    where a.kd_sub_kegiatan='$kegiatan' and a.kd_rek6='$kd_rek6' and a.jns_ang='$data' ";
        }

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

    function total_spd()
    {

        $giat  = $this->input->post('kegiatan');
        $kode  = $this->input->post('kd_skpd');
        $ckdskpd1  = substr($kode, 0, 17);
        $kode2    = $kode . '.0000';
        $tglspd   = $this->input->post('tglspd');
        $tglspp   = $this->input->post('tgl_spp');
        $beban   = $this->input->post('beban');
        $rek   = $this->input->post('kdrek6');

        // --------------------------------
        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22)  
                                and bulan_akhir='3' and tgl_spd<='$tglspp'";
        $q1     = $this->db->query($sql1);
        $tw1    = $q1->row();
        $rev1   = $tw1->revisi;
        // --------------------------------
        $sql2   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22)  
                                and bulan_akhir='6' and tgl_spd<='$tglspp'";
        $q2     = $this->db->query($sql2);
        $tw2    = $q2->row();
        $rev2   = $tw2->revisi;
        // --------------------------------
        $sql3   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22)  
                                and bulan_akhir='9' and tgl_spd<='$tglspp'";
        $q3     = $this->db->query($sql3);
        $tw3    = $q3->row();
        $rev3   = $tw3->revisi;
        // --------------------------------
        $sql4   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22)  
                                and bulan_akhir='12' and tgl_spd<='$tglspp'";
        $q4     = $this->db->query($sql4);
        $tw4    = $q4->row();
        $rev4   = $tw4->revisi;

        // if 

        $query1   = $this->db->query("SELECT sum(nilai)as total_spd from (
                    SELECT
                    'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='3'
                    and revisi_ke='$rev1'
                    and tgl_spd<='$tglspp'
                    and bulan_awal <= month('$tglspp')
                    UNION ALL
                    SELECT
                    'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='6'
                    and revisi_ke='$rev2'
                    and tgl_spd<='$tglspp'
                    and bulan_awal <= month('$tglspp')
                    UNION ALL
                    SELECT
                    'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='9'
                    and revisi_ke='$rev3'
                    and tgl_spd<='$tglspp'
                    and bulan_awal <= month('$tglspp')
                    UNION ALL
                    SELECT
                    'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='12'
                    and revisi_ke='$rev4'
                    and tgl_spd<='$tglspp'
                    and bulan_awal <= month('$tglspp')
                    )spd

                    ");

        // if($beban==4){
        //     $query1   = $this->db->query("SELECT  a.kd_sub_kegiatan, a.kd_rek6,SUM(a.nilai) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd=b.no_spd 
        //     where a.kd_unit = '$ckdskpd' and  a.kd_sub_kegiatan = '$ckdkegi' and a.kd_rek6='$kd_rek6'  and b.status='1' GROUP BY a.kd_sub_kegiatan,a.kd_rek6
        //     ");

        // } else{
        //     $query1   = $this->db->query("SELECT  a.kd_sub_kegiatan, SUM(a.nilai) as nilai FROM trdspd a INNER JOIN trhspd b ON a.no_spd=b.no_spd 
        //     where a.kd_unit = '$ckdskpd' and  a.kd_sub_kegiatan = '$ckdkegi' and a.kd_rek6='$kd_rek6' and tgl_spd<='$tglspp'  and b.status='1' GROUP BY a.kd_sub_kegiatan
        //     ");    
        // }

        $result   = array();
        $ii       = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'             => $ii,
                'nilai'          => number_format($resulte['total_spd'], 2, '.', ','),
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function total_angkas()
    {
        $ckdkegi  = $this->input->post('kegiatan');
        $kode  = $this->input->post('kd_skpd');
        $data1 = $this->cek_anggaran_model->cek_anggaran($kode);
        $ckdskpd  = $this->input->post('kd_skpd');
        $spd  = $this->input->post('spd');
        $ckdskpd    = $this->input->post('kd_skpd');
        $tglspp     = $this->input->post('tglspp');
        $kd_rek6    = $this->input->post('kdrek6');
        $beban      = $this->input->post('beban');
        $sts_angkas = $this->input->post('status_angkas');
        $artgl = explode("-", $tglspp);
        $bulan = $artgl[1];
        // echo($sts_angkas);

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



        $hasil = $this->db->query("SELECT count(*)spd from(
                                    select bulan_awal,bulan_akhir from trhspd where left(kd_skpd,22)=left('$ckdskpd',22) GROUP BY bulan_awal,bulan_akhir)z");

        foreach ($hasil->result_array() as $row) {
            $jumlahspd = $row['spd'];
        }


        if ($beban == '4' || substr($ckdkegi, 5, 10) == '01.1.02.01') {

            //khusus gaji jika SPD tw depan sudah aktif dan status angkas sudah disahkan bisa pakai angkas bulan depan
            // if (($jumlahspd==1 && $bulan<='3') || ($jumlahspd==2 && $bulan<='6' && $bulan>'3') || ($jumlahspd==3 && $bulan>'6' && $bulan<='9') || ($jumlahspd==4 && $bulan>'9' && $bulan<='12')){
            //     $bulan1=$bulan;
            // }else if (($jumlahspd==2 && $bulan<='3') || ($jumlahspd==3 && $bulan>'3' &&  $bulan<='6') || ($jumlahspd==4 && $bulan>'6' && $bulan<='9')){
            $bulan1 = $bulan + 1;
            // }else{
            //     $bulan1=$bulan;
            // }

            // $bulan=$bulan+1;

        } else {
            $bulan1 = $bulan;
        }




        $query1   = $this->db->query("SELECT a.kd_sub_kegiatan, kd_rek6, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            where a.kd_skpd = '$ckdskpd' AND b.jns_ang='$data1' and  a.kd_sub_kegiatan = '$ckdkegi' and a.kd_rek6='$kd_rek6' and bulan <='$bulan1' GROUP BY a.kd_sub_kegiatan,a.kd_rek6");

        // if($beban==4){

        //     $query1   = $this->db->query(" SELECT  a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan 
        //     where left(a.kd_skpd,17) = '$ckdskpd1' and  a.kd_sub_kegiatan = '$ckdkegi' and a.kd_rek6='$kd_rek6' and (bulan <='$bulan') GROUP BY a.kd_sub_kegiatan,a.kd_rek6
        //     "); 

        // }else{

        // }

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

    function tsimpan()
    {
        $skpd = $this->input->post('cskpd');
        $spp = $this->input->post('cspp');
        $rek = $this->input->post('crek');
        $giat = $this->input->post('cgiat');
        $nmrek = $this->input->post('cnmrek');
        $nmgiat = $this->input->post('cnmgiat');
        $sisa = $this->input->post('sspp');
        $giat_k = substr($giat, 0, 21);
        //$query = $this->db->query(" delete from trdspp where kd_skpd='$skpd' and no_spp='$spp' and kd_rek6='$rek' and kd_kegiatan='$giat' ");
        $query = $this->db->query("insert into trdspp(no_spp,kd_skpd,kd_sub_kegiatan,kd_rek6,nm_subkegiatan,nm_rek6,nilai,sisa,kd_kegiatan) values('$spp','$skpd','$giat','$rek','$nmgiat','$nmrek',0,'$sisa','$giat_k') ");

        $this->select_data1($spp);
    }


    function simpan_tukd_tu()
    {
        $tabel   = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');
        $skpd  = $this->session->userdata('kdskpd');

        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$skpd' ";
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

    function cek_simpan_spp()
    {
        $nomor     = $this->input->post('no');
        $tabel     = $this->input->post('tabel');
        $field     = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2    = $this->input->post('tabel2');
        $kd_skpd   = $this->session->userdata('kdskpd');
        $kd_skpds  = substr($kd_skpd, 0, 22);
        if ($field2 == '') {
            $hasil = $this->db->query("Select count(*) as jumlah FROM $tabel where $field='$nomor' ");
        } else {
            $hasil = $this->db->query(" SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE left(kd_skpd,22) = '$kd_skpds' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE left(kd_skpd,22) = '$kd_skpds')a WHERE a.nomor = '$nomor'");
        }
        $ttd = $this->db->query("SELECT COUNT(kd_skpd) as total FROM ms_ttd WHERE LEFT(kd_skpd,22)='$kd_skpds' AND kode='PPTK'")->row();
        $ttd1 = $ttd->total;

        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0 && $ttd1 == 0) {
            $msg = array('pesan' => '1', 'pesanttd' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0', 'pesanttd' => '0');
            echo json_encode($msg);
        }
    }


    function dsimpan_ag()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $no_spp = $this->input->post('no');
        $csql     = $this->input->post('sql');
        $sql = "DELETE from trdspp where no_spp='$no_spp' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,nm_skpd,kd_sub_kegiatan,no_spd,sumber,nm_sub_kegiatan,kd_bidang)";
            $asg = $this->db->query($sql . $csql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
            } else {
                $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp'";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function dsimpan_ag_ls()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $data1 = $this->cek_anggaran_model->cek_anggaran($kdskpd);
        $no_spp = $this->input->post('no');
        $csql     = $this->input->post('sql');
        $sql = "DELETE from trdspp where no_spp='$no_spp' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,no_spd,kd_bidang,sumber)";
            $asg = $this->db->query($sql . $csql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                //   exit();
            } else {
                $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp' AND b.jns_ang='$data1'";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }
    function update_tukd()
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

        $query   = $this->input->post('st_query');
        $asg     = $this->db->query($query);
        if ($asg > 0) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function dsimpan_spp()
    {
        $no_spp      = $this->input->post('cnospp');
        $kd_kegiatan = $this->input->post('ckdgiat');
        $kd_rek5     = $this->input->post('ckdrek');
        $vno_bukti   = $this->input->post('cnobukti');

        $sql = "delete from trdspp where no_spp='$no_spp' and kd_sub_kegiatan='$kd_kegiatan' and kd_rek6='$kd_rek5' and no_bukti='$vno_bukti' ";
        $asg = $this->db->query($sql);

        echo '1';
    }

    function dsimpan_gu_edit()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $no_spp = $this->input->post('no');
        $no_hide = $this->input->post('no_hide');
        $csql     = $this->input->post('sql');

        $sql = "delete from trdspp where no_spp='$no_hide' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd,no_spd,no_bukti)";
            $asg = $this->db->query($sql . $csql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                //   exit();
            } else {
                $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp'";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    //   exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function dsimpan_ag_edit_ls()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kdskpd);
        $no_spp = $this->input->post('no');
        $no_hide = $this->input->post('no_hide');
        $csql     = $this->input->post('sql');
        $sql = "DELETE from trdspp where no_spp='$no_hide' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,no_spd,kd_bidang)";
            $asg = $this->db->query($sql . $csql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                //   exit();
            } else {
                $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_sub_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                                WHERE no_spp='$no_spp' and b.jns_ang='$data'";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function dsimpan_hapus()
    {
        $no_spp  = trim($this->input->post('cno_spp'));
        $lcid    = $this->input->post('lcid');
        $lcid_h  = $this->input->post('lcid_h');

        if ($lcid <> $lcid_h) {
            $sql     = " delete from trdspp where no_spp='$no_spp' ";
            $asg     = $this->db->query($sql);
            if ($asg > 0) {
                echo '1';
                exit();
            } else {
                echo '0';
                exit();
            }
        }
    }

    function thapus($spp = '', $kegiatan = '', $rek = '')
    {

        $id = str_replace('123456789', '/', $spp);

        $query = $this->db->query(" delete from trdspp where no_spp='$id' and kd_sub_kegiatan='$kegiatan' and kd_rek5='$rek' ");
        $this->select_data1($id);
        // $query->free_result();
    }

    function hapus_spp3($spp = '', $skpd = '')
    {
        $spp = $this->input->post('no');
        $skpd = $this->session->userdata('kdskpd');
        $id = str_replace('######', '/', $spp);
        $query = $this->db->query("delete from trhspp where no_spp='$id' and kd_skpd='$skpd'");
        $query = $this->db->query("delete from trdspp where no_spp='$id' and kd_skpd='$skpd'");
        if ($query) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function spd1_ag($jenis = '', $tgl_spp = '', $bulan = '')
    {
        if ($jenis == '4') {
            $jenis = '(5)';
        } else if ($jenis == '5') {
            $jenis = "('5','6')";
        } else {
            $jenis = '(5)';
        }

        $tglspp = date("m", strtotime($tgl_spp));

        $skpd  = $this->session->userdata('kdskpd');
        if ($tgl_spp == '') {
            $sql   = " SELECT no_spd, tgl_spd from trhspd where left(kd_skpd,22)=left('$skpd',22) and status='1'";
        } else {
            $sql   = " SELECT no_spd, tgl_spd from trhspd where left(kd_skpd,22)=left('$skpd',22) and tgl_spd<='$tgl_spp' and status='1' and bulan_awal <= '$tglspp' and  jns_beban in $jenis";
        }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $dk = $resulte['no_spd'];
            $sq = $this->db->query("SELECT sum(nilai) as nilai from trdspd where no_spd='$dk'")->row();
            $sk = $sq->nilai;

            $parx = $resulte['tgl_spd'];
            $cpar = explode("-", $parx);
            $tgl = $cpar[2] . "-" . $cpar[1] . "-" . $cpar[0];

            $result[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'tgl_spd' => $resulte['tgl_spd'],
                'tgl_spd2' => $tgl,
                'nilai' => number_format($sk, 2)
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function kegiatan_spd()
    {
        $kode  = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $skpd  = $this->session->userdata('kdskpd');
        $spd = $this->input->post('spd');
        $lccr = $this->input->post('q');
        $xxsk = substr($skpd, 0, 4);
        $yysk = substr($skpd, 8, 2);

        if ($xxsk == "4.08" && $xxsk != "00") {
            $wherex = "and a.nm_sub_kegiatan like '%dau tambahan%'";
        } else {
            $wherex = '';
        }

        $cek = substr($skpd, 18, 4);
        if ($cek == "0000") {
            $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program, a.nm_program,c.status_keg, (
select distinct kd_skpd from trskpd where kd_sub_kegiatan=a.kd_sub_kegiatan and kd_skpd=b.kd_skpd AND jns_ang='$data')as bidang FROM trdspd a
inner join trhspd b on a.no_spd=b.no_spd
inner join trskpd c on a.kd_sub_kegiatan =c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd
where a.no_spd='$spd' AND (c.status_keg !='0' or c.status_keg is null) and c.jns_ang='$data' AND c.status_sub_kegiatan='1'
and (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%')) 
order by a.kd_sub_kegiatan";
        } else {
            $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program, a.nm_program,c.status_keg, c.kd_skpd as bidang FROM trdspd a
inner join trhspd b on a.no_spd=b.no_spd
inner join trskpd c on a.kd_sub_kegiatan =c.kd_sub_kegiatan and left(b.kd_skpd,22)=left(c.kd_skpd,22)
where a.no_spd='$spd' AND c.kd_skpd='$skpd' and c.jns_ang='$data' AND c.status_sub_kegiatan='1' AND (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%')) 
order by a.kd_sub_kegiatan ";
        }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $skpdd = $resulte['bidang'];
            if ($skpdd == "") {
                $skpdd = $skpd;
            }

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'kdbidang' => $skpdd
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function reg_spp()
    {
        $data['page_title'] = 'REGISTER S P P';
        $this->template->set('title', 'REGISTER S P P');
        $this->template->load('template', 'tukd/register/spp', $data);
    }




    function cetak_register_spp($kd_skpd = '', $ttd1 = '', $ttd2 = '', $tglttd = '', $ctk = '')
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $atas = $this->uri->segment(8);
        $bawah = $this->uri->segment(9);
        $kiri = $this->uri->segment(10);
        $kanan = $this->uri->segment(11);
        $lctgl1 = $_REQUEST['tgl1'];
        $lctgl2 = $_REQUEST['tgl2'];
        $lcperiode = $this->tukd_model->tanggal_format_indonesia($lctgl1) . "  S.D. " . $this->tukd_model->tanggal_format_indonesia($lctgl2);
        $lcperiode1 = "Tanggal " . $this->tukd_model->tanggal_format_indonesia($lctgl1);
        $lcperiode2 = "Tanggal " . $this->tukd_model->tanggal_format_indonesia($lctgl2);


        $kd = '';
        $a = '';
        $nama = '';
        $kd = $this->uri->segment(3);
        if ($kd <> '') {
            $a = 'SKPD :';
            $sqls = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd'";
            $sqls = $this->db->query($sqls);
            foreach ($sqls->result() as $row) {
                $nama     = $row->nm_skpd;
            }
        }

        $nippa = str_replace('123456789', ' ', $ttd1);
        $csql = "SELECT nip ,nama,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND kd_skpd = '$kd_skpd' AND kode in ('PA','KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $ttd2);
        $csql = "SELECT nip ,nama,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND kd_skpd = '$kd_skpd' AND kode='BK'";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $cRet = '';
        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">";
        $cRet .= "<thead>
        <tr>
            <td align=\"center\" style=\"font-size:14px;border: none;border-bottom:none;\" colspan=\"15\"><b> $kab</b></td>            
        </tr>
        <tr>            
            <td align=\"center\" style=\"font-size:14px;border: none;border-bottom:none;\" colspan=\"15\"><b>REGISTER SPP</b></td>
        </tr>
        <tr>            
            <td align=\"center\" style=\"font-size:14px;border: none;border-bottom:none;\" colspan=\"15\"><b>$a $nama</b><br>&nbsp;</td>
        </tr>
        <tr>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"3%\" rowspan=\"3\"><b>No.<br>Urut $atas</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\" rowspan=\"3\"><b>Tanggal</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"36%\" colspan=\"6\"><b>Nomor SPP</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"19%\" rowspan=\"3\"><b>Uraian</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"36%\" colspan=\"6\"><b>Jumlah SPP<br>(Rp)</b></td>
        </tr>  
        <tr>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\" rowspan=\"2\"><b>UP</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\" rowspan=\"2\"><b>GU</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\" rowspan=\"2\"><b>TU</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"18%\" colspan=\"3\"><b>LS</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\" rowspan=\"2\"><b>UP</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\" rowspan=\"2\"><b>GU</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\" rowspan=\"2\"><b>TU</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"18%\" colspan=\"3\"><b>LS</b></td>
          </tr>
          <tr>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\"><b>Gaji</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\"><b>Barang&<br>Jasa</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\"><b>PPKD</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\"><b>Gaji</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\"><b>Barang&<br>Jasa</b></td>
            <td style=\"font-size:10px\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"6%\"><b>PPKD</b></td>
          </tr>
          </thead>
          <tr>
            <td style=\"font-size:10px\" align=\"center\" width=\"3%\"><b>1</b></td>
            <td style=\"font-size:10px\" align=\"center\" width=\"6%\"><b>2</b></td>
            <td style=\"font-size:10px\" align=\"center\" width=\"36%\" colspan=\"6\"><b>3</b></td>
            <td style=\"font-size:10px\" align=\"center\" width=\"19%\"><b>4</b></td>
            <td style=\"font-size:10px\" align=\"center\" width=\"36%\" colspan=\"6\"><b>5</b></td>
          </tr>";
        //$skpd = $this->uri->segment(3); 
        $kriteria = '';
        $kriteria = $this->uri->segment(3);
        $where = "";
        if ($kriteria <> '') {
            $where = "AND a.kd_skpd ='$kriteria' ";
        }

        $sql = "SELECT 
                a.tgl_spp,a.no_spp,a.keperluan,a.jns_spp,SUM(b.nilai) nilai 
                FROM trhspp a 
                INNER JOIN trdspp b ON a.kd_skpd=b.kd_skpd AND a.no_spp=b.no_spp
                WHERE (a.sp2d_batal=0 OR a.sp2d_batal is NULL) and a.tgl_spp>='$lctgl1' and a.tgl_spp<='$lctgl2'
                $where
                GROUP BY a.tgl_spp,a.no_spp,a.keperluan,a.jns_spp
                ORDER BY a.tgl_spp,a.no_spp";
        $hasil = $this->db->query($sql);
        $lcno = 0;
        foreach ($hasil->result() as $row) {
            $lcno = $lcno + 1;
            switch ($row->jns_spp) {
                case '1': //UP
                    $cRet .=  "<tr>
                                <td align=\"center\" width=\"3%\" style=\"font-size:10px\">$lcno</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">" . $this->tanggal_indonesia($row->tgl_spp) . "</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">$row->no_spp</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"19%\" style=\"font-size:10px\">$row->keperluan</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->nilai, "2", ",", ".") . "</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                              </tr>  ";
                    break;
                case '2': //GU
                    $cRet .=  "<tr>
                                <td align=\"center\" width=\"3%\" style=\"font-size:10px\">$lcno</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">" . $this->tanggal_indonesia($row->tgl_spp) . "</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">$row->no_spp</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"19%\" style=\"font-size:10px\">$row->keperluan</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->nilai, "2", ",", ".") . "</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                              </tr>  ";
                    break;
                case '3': //TU
                    $cRet .=  "<tr>
                                <td align=\"center\" width=\"3%\" style=\"font-size:10px\">$lcno</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">" . $this->tanggal_indonesia($row->tgl_spp) . "</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">$row->no_spp</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"19%\" style=\"font-size:10px\">$row->keperluan</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->nilai, "2", ",", ".") . "</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                              </tr>  ";
                    break;
                case '4': //LS gaji
                    $cRet .=  "<tr>
                                <td align=\"center\" width=\"3%\" style=\"font-size:10px\">$lcno</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">" . $this->tanggal_indonesia($row->tgl_spp) . "</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">$row->no_spp</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"19%\" style=\"font-size:10px\">$row->keperluan</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->nilai, "2", ",", ".") . "</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                              </tr>  ";
                    break;
                case '5': //LS PPKD
                    $cRet .=  "<tr>
                                <td align=\"center\" width=\"3%\" style=\"font-size:10px\">$lcno</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">" . $this->tanggal_indonesia($row->tgl_spp) . "</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">$row->no_spp</td>
                                <td align=\"left\" width=\"19%\" style=\"font-size:10px\">$row->keperluan</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->nilai, "2", ",", ".") . "</td>
                              </tr>  ";
                    break;
                case '6': //LS barang dan jasa
                    $cRet .=  "<tr>
                                <td align=\"center\" width=\"3%\" style=\"font-size:10px\">$lcno</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">" . $this->tanggal_indonesia($row->tgl_spp) . "</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\">$row->no_spp</td>
                                <td align=\"left\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"left\" width=\"19%\" style=\"font-size:10px\">$row->keperluan</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->nilai, "2", ",", ".") . "</td>
                                <td align=\"right\" width=\"6%\" style=\"font-size:10px\"></td>
                              </tr>  ";
                    break;
            }
        }


        $sql = "SELECT 
                        SUM(CASE WHEN a.jns_spp='1' THEN b.nilai ELSE 0 END) as up, 
                        SUM(CASE WHEN a.jns_spp='2' THEN b.nilai ELSE 0 END) as gu,
                        SUM(CASE WHEN a.jns_spp='3' THEN b.nilai ELSE 0 END) as tu, 
                        SUM(CASE WHEN a.jns_spp='4' THEN b.nilai ELSE 0 END) as gj, 
                        SUM(CASE WHEN a.jns_spp='5' THEN b.nilai ELSE 0 END) as ppkd, 
                        SUM(CASE WHEN a.jns_spp='6' THEN b.nilai ELSE 0 END) as ls 
                        FROM trhspp a 
                        INNER JOIN trdspp b ON a.kd_skpd=b.kd_skpd AND a.no_spp=b.no_spp
                        WHERE (a.sp2d_batal=0 OR a.sp2d_batal is NULL) and a.tgl_spp>='$lctgl1' and a.tgl_spp<='$lctgl2'
                        $where
                         ";
        $hasil = $this->db->query($sql);
        $lcno = 0;
        foreach ($hasil->result() as $row) {
            $cRet .=  "<tr>
                    <td colspan=\"9\" align=\"center\" width=\"3%\" style=\"font-size:10px\">J U M L A H</td>
                    <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->up, "2", ",", ".") . "</td>
                    <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->gu, "2", ",", ".") . "</td>
                    <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->tu, "2", ",", ".") . "</td>
                    <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->gj, "2", ",", ".") . "</td>
                    <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->ls, "2", ",", ".") . "</td>
                    <td align=\"right\" width=\"6%\" style=\"font-size:10px\">" . number_format($row->ppkd, "2", ",", ".") . "</td>
                  </tr>  ";
        }
        $cRet .= "</table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
            <tr>
            <td align=\"center\" width=\"50%\">Mengetahui</td>
            <td align=\"center\" width=\"50%\">" . $daerah . ", " . $this->tanggal_format_indonesia($tglttd) . "</td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\">$trh2->jabatan</td>
            <td align=\"center\" width=\"50%\">$trh3->jabatan</td>
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
                    <td align=\"center\" width=\"50%\" style=\"font-size:10px;border: solid 1px white;\"><b><u>$trh2->nama</u></b><br>$trh2->pangkat</td>
                    <td align=\"center\" width=\"50%\" style=\"font-size:10;border: solid 1px white;\"><b><u>$trh3->nama</u></b><br>$trh3->pangkat</td>
                </tr>
                <tr>
                    <td align=\"center\" width=\"50%\" style=\"font-size:10;border: solid 1px white;\">NIP. $trh2->nip</td>
                     <td align=\"center\" width=\"50%\" style=\"font-size:10;border: solid 1px white;\">NIP. $trh3->nip</td>
                </tr>
                </table>";

        switch ($ctk) {
            case 1;
                $data['prev'] = $cRet;
                echo ("<title>Register SPP</title>");
                echo $cRet;
                break;
            case 0;
                //$this->_mpdf('',$cRet,10,10,10,0,1,'');
                $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
                break;
        }

        /* $data['prev']= $cRet;    
        echo ("<title>Register SPP</title>");
        echo $cRet; */
    }

    // LS FINISH



    // SPP NEW

    // -------------------
    public  function cetakspp77()
    {

        $client = $this->ClientModel->clientData('1');
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $kd1 = substr($kd, 0, 22);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $spasi = $this->uri->segment(10);

        $nm_skpd = $this->rka_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        // echo($nomor);
        // $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');
        $jns_bbn = $this->rka_model->get_nama($nomor, 'jns_beban', 'trhspp', 'no_spp');
        $npwp = $this->rka_model->get_nama($kd, 'npwp', 'ms_skpd', 'kd_skpd');
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $kab = "PEMERINTAH KABUPATEN MELAWI";
        $bln = explode('/', $nomor);
        // $bulan = $bln[6];
        $PA = str_replace('123456789', ' ', $this->uri->segment(11));
        // echo $bulan;die();
        $nama3 = '';
        if ($jns == '1' || $jns == '2' || $jns == '7') {
            $PA = str_replace('123456789', ' ', $this->uri->segment(12));
        } else {
            $PA = str_replace('123456789', ' ', $this->uri->segment(11));
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd='$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode ='PPTK' AND kd_skpd='$kd'";
        $sqlttd2 = $this->db->query($sqlttd2);
        foreach ($sqlttd2->result() as $rowttd2) {
            $nip2 = $rowttd2->nip;
            $nama2 = $rowttd2->nm2;
            $jabatan2  = $rowttd2->jab;
            $pangkat2  = $rowttd2->pangkat;
        }

        $sqlttd3 = "SELECT nama as nm3,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PA' and kode  in ('PA','KPA') AND kd_skpd='$kd'";
        $sqlttd3 = $this->db->query($sqlttd3);
        foreach ($sqlttd3->result() as $rowttd3) {
            $nip3       = $rowttd3->nip;
            $nama3      = $rowttd3->nm3;
            $jabatan3   = $rowttd3->jab;
            $pangkat3   = $rowttd3->pangkat;
        }

        if ($jns == 1) {
            $jenisspp = 'Uang Persediaan';
            $jenis_spp = 'SPP-UP';
        } else if ($jns == 2) {
            $jenisspp = 'Ganti Uang Persediaan';
            $jenis_spp = 'SPP-GU';
        } else if ($jns == 3) {
            $jenisspp = 'Tambahan Uang Persediaan';
            $jenis_spp = 'SPP-TU';
        } else if ($jns == 4) {
            $jenisspp = 'Langsung Gaji dan Tunjangan';
            $jenis_spp = 'SPP-LS';
        } else if ($jns == 5) {
            $jenisspp = 'Langsung Pihak Ketiga Lainnya';
            $jenis_spp = 'SPP-LS';
        } else if ($jns == 6) {
            $jenisspp = 'Langsung Barang dan Jasa';
            $jenis_spp = 'SPP-LS';
        } else if ($jns == 7) {
            $jenisspp = 'GU NIHIL';
            $jenis_spp = 'SPP-GU NIHIL';
        }


        $sqlspp = "SELECT a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,a.jns_beban,a.bank,no_rek,keperluan,a.no_spd,b.kd_sub_kegiatan,b.nm_sub_kegiatan,sum(b.nilai)as nilaispp FROM trhspp a inner join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.no_spp='$nomor' AND b.kd_skpd='$kd' GROUP BY a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,a.jns_beban,a.bank,no_rek,keperluan,a.no_spd,b.kd_sub_kegiatan,b.nm_sub_kegiatan";
        $sqlspps = $this->db->query($sqlspp);
        foreach ($sqlspps->result() as $rowspp) {
            $kd_sub_kegiatan    = $rowspp->kd_sub_kegiatan;
            $nm_sub_kegiatan    = $rowspp->nm_sub_kegiatan;
            $bank               = $this->rka_model->get_nama($rowspp->bank, 'nama', 'ms_bank', 'kode');
            $no_rek             = $rowspp->no_rek;
            $keperluan          = $rowspp->keperluan;
            $nospd              = $rowspp->no_spd;
            $tglspp             = $rowspp->tgl_spp;
            $tglspd             = $this->rka_model->get_nama($nospd, 'tgl_spd', 'trhspd', 'no_spd');
            $nilaispp           = $rowspp->nilaispp;
            $jns_beban           = $rowspp->jns_beban;
        }
        if ($jns != 7) {
            $nil_spp    = $this->rka_model->get_nama($nomor, 'nilai', 'trhspp', 'no_spp');
        } else {
            $nil_spp = 0;
        }

        if ($jns == 1 || $jns == 2 || $jns == 7) {
            $kd_sub_kegiatan1 = '';
            $nm_sub_kegiatan1 = '';
        } else {
            $kd_sub_kegiatan1 = $kd_sub_kegiatan;
            $nm_sub_kegiatan1 = $nm_sub_kegiatan;
        }

        $sqlrek = $this->db->query("SELECT * FROM trhspp WHERE no_spp = '$nomor'")->row();
        $rekbend = $sqlrek->no_rek;
        $nama_bankbend    = $this->rka_model->get_nama($sqlrek->bank, 'nama', 'ms_bank', 'kode');

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td align=\"center\" style=\"font-size:14px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                    </tr>
                    <tr>
                        <td align=\"center\" style=\"font-size:18px\"><strong>SURAT PERMINTAAN PEMBAYARAN (SPP) </strong></td>
                    </tr>
                    <tr>
                        <td align=\"center\" style=\"font-size:12px\">Nomor : $nomor </td>
                    </tr>
            </table><br />";


        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                        <td colspan=\"3\" align=\"center\"><b>$jenisspp</b></td>
                    </tr>
                    <tr>
                        <td  colspan=\"3\" align=\"center\"><b>$jenis_spp</b></td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"center\"> &nbsp;</td>
                        <td width=\"5%\" align=\"center\"> &nbsp;</td>
                        <td width=\"40%\" align=\"center\"> &nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;1.&nbsp;&nbsp;&nbsp;Nama SKPD/Unit Kerja</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> $nm_skpd</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;2.&nbsp;&nbsp;&nbsp;Kode dan Nama Sub Kegiatan</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> $kd_sub_kegiatan1 $nm_sub_kegiatan1</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;3.&nbsp;&nbsp;&nbsp;Nama Pengguna Anggaran/Kuasa Pengguna Anggaran</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;$nama3</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;4.&nbsp;&nbsp;&nbsp;Nama PPTK</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;$nama2</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;5.&nbsp;&nbsp;&nbsp;Nama Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;$nama</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;6.&nbsp;&nbsp;&nbsp;NPWP Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;$npwp</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;7.&nbsp;&nbsp;&nbsp;Nama Bank</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;$nama_bankbend</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;8.&nbsp;&nbsp;&nbsp;Nomor Rekening Bank</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;$rekbend</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;9.&nbsp;&nbsp;&nbsp;Untuk Keperluan</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;$keperluan</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;10.&nbsp;Dasar Pengeluaran</td>
                        <td width=\"5%\" align=\"center\"> :</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;SPD Nomor: $nospd tanggal $tglspd</td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;</td>
                        <td width=\"5%\" align=\"center\"> &nbsp;</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;Sebesar: Rp" . number_format($nil_spp, "2", ".", ",") . "<br>
                        <i>(" . $this->tukd_model->terbilang($nil_spp) . ")</i></td>
                    </tr>
                    <tr>
                        <td width=\"55%\" align=\"left\"> &nbsp;</td>
                        <td width=\"5%\" align=\"center\"> &nbsp;</td>
                        <td width=\"40%\" align=\"left\"> &nbsp;</td>
                    </tr>
                  </table>";


        $sqlspd = "SELECT no_spd,tgl_spd,total from trhspd where kd_skpd='$kd'";
        $sqlspds = $this->db->query($sqlspd);

        // if ($jns_beban == 1) {
        //     $sqlsp2d="SELECT no_sp2d,tgl_sp2d,nilai as total from trhsp2d where kd_skpd='$kd' and jns_spp='4' and bulan < $bulan";
        // } else {
        //     $sqlsp2d="SELECT no_sp2d,tgl_sp2d,nilai as total from trhsp2d where kd_skpd='$kd' and jns_spp='6' and bulan < $bulan";
        // }

        $sqlsp2d = "SELECT no_sp2d,tgl_sp2d,nilai as total from trhsp2d where kd_skpd='$kd' AND status_bud='1' AND jns_spp='4'";
        $sqlsp2ds = $this->db->query($sqlsp2d);


        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">

                    <tr>
                        <td width=\"3%\" align=\"center\">&nbsp;No</td>
                        <td colspan=\"3\" align=\"center\">&nbsp;Uraian</td>
                        
                    </tr>

                    <tr>
                        <td width=\"3%\" align=\"center\">&nbsp;</td>
                        <td width=\"55%\" align=\"left\">&nbsp;</td>
                        <td width=\"20%\" align=\"center\">&nbsp;</td>
                        <td width=\"22%\" align=\"left\">&nbsp;</td>
                    </tr>

                    <tr>
                        <td width=\"3%\" align=\"center\"><b>I</b></td>
                        <td colspan=\"2\" align=\"left\"> &nbsp; <b>SPD</b></td>
                        <td width=\"22%\" align=\"left\"> &nbsp;</td>
                    </tr>";
        foreach ($sqlspds->result() as $rowspd) {
            $no_spd             = $rowspd->no_spd;
            $tgl_spd            = $this->tukd_model->tanggal_format_indonesia($rowspd->tgl_spd);
            $nilai_spd           = number_format($rowspd->total, "2", ".", ",");
            $cRet   .= "

                            <tr>
                                <td width=\"3%\" align=\"center\"> &nbsp;</td>
                                <td width=\"55%\" align=\"left\">&nbsp;Tanggal :&nbsp;$tgl_spd</td>
                                <td width=\"20%\" align=\"center\"> &nbsp;$no_spd</td>
                                <td width=\"22%\" align=\"left\"> &nbsp;Rp$nilai_spd</td>
                            </tr>
                            ";
        }

        $cRet   .= "<tr>
                        <td width=\"3%\" align=\"center\"> &nbsp;</td>
                        <td width=\"55%\" align=\"left\"> &nbsp;</td>
                        <td width=\"20%\" align=\"center\"> &nbsp;</td>
                        <td width=\"22%\" align=\"left\"> &nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"3%\" align=\"center\"> <b>II</b></td>
                        <td colspan=\"2\" align=\"left\"> &nbsp; <b>SP2D Sebelumnya</b></td>
                        <td width=\"22%\" align=\"left\"> &nbsp;</td>
                    </tr>";
        foreach ($sqlsp2ds->result() as $rowsp2d) {
            $no_sp2d             = $rowsp2d->no_sp2d;
            $tgl_sp2d            = $this->tukd_model->tanggal_format_indonesia($rowsp2d->tgl_sp2d);
            $nilai_sp2d           = number_format($rowsp2d->total, "2", ".", ",");

            $cRet   .= "

                            <tr>
                                <td width=\"3%\" align=\"center\"> &nbsp;</td>
                                <td width=\"55%\" align=\"left\">Tanggal : &nbsp;$tgl_sp2d</td>
                                <td width=\"20%\" align=\"left\"> &nbsp;$no_sp2d</td>
                                <td width=\"22%\" align=\"left\"> &nbsp;Rp$nilai_sp2d</td>
                            </tr>

                            ";
        }
        if ($jns == 5) {
            $cRet   .= "<tr>
                        <td width=\"3%\" align=\"center\"> &nbsp;</td>
                        <td width=\"55%\" align=\"left\"> &nbsp;</td>
                        <td width=\"20%\" align=\"center\"> &nbsp;</td>
                        <td width=\"22%\" align=\"left\"> &nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=\"4\" align=\"center\"> &nbsp;Pada SPP ini ditetapkan lampiran-lampiran yang diperlukan sebagaimana tertera pada daftar kelengkapan dokumen SPP ini</td>
                        
                    </tr>

                    <tr>
                        <td colspan=\"4\" align=\"center\">
                            <table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                    <td width=\"50%\" align=\"center\">" . $client->tetapkan . "," . $this->tukd_model->tanggal_format_indonesia($tglspp) . "</td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\">Pejabat Pelaksana Teknis Kegiatan </td>
                                    <td width=\"50%\" align=\"center\">$jabatan</td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\"><u>($nama2)</u></td>
                                    <td width=\"50%\" align=\"center\"><u>($nama)</u></td>
                                </tr>
                                <tr>
                                    <td width=\"50%\" align=\"center\">NIP.$nip2</td>
                                    <td width=\"50%\" align=\"center\">NIP.$nip</td>
                                </tr>
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">*) <i>Coret yang tidak perlu.</i></td>
                                </tr>
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Lembar Asli</b> : Untuk Pengguna Anggaran/PPK-SKPD</td>
                                </tr>
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Salinan 1</b> : Untuk Kuasa BUD</td>
                                </tr>
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Salinan 2 </b>: Untuk Bendahara Pengeluaran/PPTK</td>
                                </tr>
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Salinan 3 </b>: Untuk Arsip Bendahara Pengeluaran/PPTK</td>
                                </tr>
                                <tr>
                                    <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">&nbsp;</td>
                                </tr>

                            </table>
                        </td>
                        
                    </tr>

                  </table>";
        } else {
            $cRet   .= "<tr>
            <td width=\"3%\" align=\"center\"> &nbsp;</td>
            <td width=\"55%\" align=\"left\"> &nbsp;</td>
            <td width=\"20%\" align=\"center\"> &nbsp;</td>
            <td width=\"22%\" align=\"left\"> &nbsp;</td>
        </tr>
        <tr>
            <td colspan=\"4\" align=\"center\"> &nbsp;Pada SPP ini ditetapkan lampiran-lampiran yang diperlukan sebagaimana tertera pada daftar kelengkapan dokumen SPP ini</td>
            
        </tr>

        <tr>
            <td colspan=\"4\" align=\"center\">
                <table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">" . $client->tetapkan . "," . $this->tukd_model->tanggal_format_indonesia($tglspp) . "</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">Pejabat Pelaksana Teknis Kegiatan</td>
                        <td width=\"50%\" align=\"center\">$jabatan</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"><u>($nama2)</u></td>
                        <td width=\"50%\" align=\"center\"><u>($nama)</u></td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">NIP.$nip2</td>
                        <td width=\"50%\" align=\"center\">NIP.$nip</td>
                    </tr>
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">*) <i>Coret yang tidak perlu.</i></td>
                    </tr>
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Lembar Asli</b> : Untuk Pengguna Anggaran/PPK-SKPD</td>
                    </tr>
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Salinan 1</b> : Untuk Kuasa BUD</td>
                    </tr>
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Salinan 2 </b>: Untuk Bendahara Pengeluaran/PPTK</td>
                    </tr>
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\"><b>Salinan 3 </b>: Untuk Arsip Bendahara Pengeluaran/PPTK</td>
                    </tr>
                    <tr>
                        <td style=\"font-size:10px\"  colspan=\"2\" align=\"left\">&nbsp;</td>
                    </tr>

                </table>
            </td>
            
        </tr>

      </table>";
        }


        if ($cetak == '1') {
            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($cetak == '0') {
            echo $cRet;
        }
    }

    // UP


    function cetakspp77_rincian()
    {
        $cetak = $this->uri->segment(3);
        $kd = $this->uri->segment(5);
        $kd1 = substr($kd, 0, 17);
        $jns = $this->uri->segment(6);
        $tanpa   = $this->uri->segment(11);
        $spasi = $this->uri->segment(10);

        $nm_skpd = $this->rka_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $nomor = str_replace('123456789', '/', $this->uri->segment(4));
        // $alamat_skpd = $this->rka_model->get_nama($kd,'alamat','ms_skpd','kd_skpd');
        $jns_bbn = $this->rka_model->get_nama($nomor, 'jns_beban', 'trhspp', 'no_spp');
        $npwp = $this->rka_model->get_nama($kd, 'npwp', 'ms_skpd', 'kd_skpd');
        $BK = str_replace('123456789', ' ', $this->uri->segment(7));
        $PPTK = str_replace('123456789', ' ', $this->uri->segment(8));
        $PA = str_replace('123456789', ' ', $this->uri->segment(12));
        $thn_ang       = $this->session->userdata('pcThang');
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$BK' and kode = 'BK' AND kd_skpd='$kd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PPTK' and kode ='PPTK' AND kd_skpd='$kd'";
        $sqlttd2 = $this->db->query($sqlttd2);
        foreach ($sqlttd2->result() as $rowttd2) {
            $nip2 = $rowttd2->nip;
            $nama2 = $rowttd2->nm2;
            $jabatan2  = $rowttd2->jab;
            $pangkat2  = $rowttd2->pangkat;
        }

        $sqlttd3 = "SELECT nama as nm3,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$PA' and kode  in ('PA','KPA') AND kd_skpd='$kd'";
        $sqlttd3 = $this->db->query($sqlttd3);
        foreach ($sqlttd3->result() as $rowttd3) {
            $nip3       = $rowttd3->nip;
            $nama3      = $rowttd3->nm3;
            $jabatan3   = $rowttd3->jab;
            $pangkat3   = $rowttd3->pangkat;
        }

        if ($jns == 1) {
            $jenisspp = 'UANG PERSEDIAAN (SPP-UP)';
        } else if ($jns == 2) {
            $jenisspp = 'GANTI UANG PERSEDIAAN (SPP-GU)';
        } else if ($jns == 3) {
            $jenisspp = 'TAMBAHAN UANG PERSEDIAAN (SPP-TU)';
        } else if ($jns == 4) {
            $jenisspp = 'LANGSUNG (SPP-LS) GAJI DAN TUNJANGAN';
        } else if ($jns == 5) {
            $jenisspp = 'LANGSUNG (SPP-LS) PIHAK KETIGA LAINNYA';
        } else if ($jns == 6) {
            $jenisspp = 'LANGSUNG (SPP-LS) BARANG DAN JASA';
        }

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr>
                            <td align=\"center\" style=\"font-size:16px\"><strong>" . $client->pem . " " . $client->nm_kab . "</strong></td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:16px\"><strong>SURAT PERMINTAAN PEMBAYARAN $jenisspp </strong></td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:14px\">Nomor : $nomor </td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:14px\">Tahun Anggaran : $thn_ang </td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:14px\">&nbsp; </td>
                        </tr>
                        <tr>
                            <td align=\"center\" style=\"font-size:16px\"><b>RINCIAN RENCANA PENGGUNAAN</b></td>
                        </tr>

                        
                </table><br />";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"4\">
                    <tr>
                        <td width=\"5%\" align=\"center\"><b>No</b></td>
                        <td width=\"20%\" align=\"center\"><b>Kode Rekening</b></td>
                        <td width=\"50%\" align=\"center\"><b>Uraian</b></td>
                        <td width=\"25%\" align=\"center\"><b>Nilai Rupiah</b></td>
                    </tr>";

        $sqlspp = "SELECT a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan,sum(b.nilai)as nilaispp FROM trhspp a inner join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.no_spp='$nomor' AND b.kd_skpd='$kd' GROUP BY a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan";
        $sqlspps = $this->db->query($sqlspp);
        foreach ($sqlspps->result() as $rowspp) {
            $kd_sub_kegiatan    = $rowspp->kd_sub_kegiatan;
            $nm_sub_kegiatan    = $rowspp->nm_sub_kegiatan;
            $nm_kegiatan        = $this->tukd_model->get_nama(substr($kd_sub_kegiatan, 0, 12), 'nm_kegiatan', 'ms_kegiatan', 'kd_kegiatan');

            $cRet .= "<tr>
                        <td colspan=\"4\" align=\"left\"> $nm_kegiatan/$nm_sub_kegiatan</td>
                    </tr>";

            $sqlspp_rinci = "SELECT a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan,kd_rek6,nm_rek6,sum(b.nilai)as nilaispp FROM trhspp a inner join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd where a.no_spp='$nomor' AND b.kd_skpd='$kd' and b.kd_sub_kegiatan='$kd_sub_kegiatan' GROUP BY a.no_spp,a.tgl_spp,b.kd_skpd,b.nm_skpd,b.kd_sub_kegiatan,b.nm_sub_kegiatan,kd_rek6,nm_rek6";
            $no = 0;
            $sqlspp_rincis = $this->db->query($sqlspp_rinci);
            foreach ($sqlspp_rincis->result() as $rowspp_rinci) {
                $kd_rek6    = $rowspp_rinci->kd_rek6;
                $nm_rek6    = $rowspp_rinci->nm_rek6;
                $nilaispp    = $rowspp_rinci->nilaispp;
                $no = $no + 1;
                $cRet .= "<tr>
                                        <td width=\"5%\" align=\"center\">$no</td>
                                        <td width=\"20%\" align=\"left\">$kd_rek6</td>
                                        <td width=\"50%\" align=\"left\">$nm_rek6</td>
                                        <td width=\"25%\" align=\"right\">" . number_format($nilaispp, "2", ".", ",") . "</td>
                                    </tr>";
            }
        }







        $cRet .= "</table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                        <td width=\"50%\" align=\"left\">&nbsp;</td>
                        <td width=\"50%\" align=\"right\">TOTAL Rp............&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" align=\"left\"><i>Terbilang: ## ##</i></td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" align=\"left\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"left\">&nbsp;</td>
                        <td width=\"50%\" align=\"right\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">Mengetahui/Menyetujui:</td>
                        <td width=\"50%\" align=\"center\">Pontianak,..........</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\"><b>Pengguna Anggaran</b></td>
                        <td width=\"50%\" align=\"center\"><b>Bendahara Pengeluaran</b></td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                        <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">()&nbsp;</td>
                        <td width=\"50%\" align=\"center\">()&nbsp;</td>
                    </tr>
                    <tr>
                        <td width=\"50%\" align=\"center\">NIP.&nbsp;</td>
                        <td width=\"50%\" align=\"center\">NIP.&nbsp;</td>
                    </tr>



                </table>";











        if ($cetak == '1') {
            $this->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($cetak == '0') {
            echo $cRet;
        }
    }
    // --------------------


    // TU

    function spptu()
    {
        $data['page_title'] = 'INPUT S P P';
        $this->template->set('title', 'INPUT SPP TU');
        $this->template->load('template', 'tukd/spp/spp_tu', $data);
    }

    function load_spp_tu()
    {

        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = "and jns_spp='3' ";
        if ($kriteria <> '') {
            $where = "AND (upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by no_spp,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spp'    => $resulte['no_spp'],
                'tgl_spp'   => $resulte['tgl_spp'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'nm_skpd'   => $resulte['nm_skpd'],
                'jns_spp'   => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan'     => $resulte['bulan'],
                'no_spd'    => $resulte['no_spd'],
                'no_spd2'   => $resulte['no_spd2'],
                'no_spd3'   => $resulte['no_spd3'],
                'no_spd4'   => $resulte['no_spd4'],
                'bank'      => $resulte['bank'],
                'nmrekan'   => $resulte['nmrekan'],
                'no_rek'    => $resulte['no_rek'],
                'npwp'      => $resulte['npwp'],
                'status'    => $resulte['status'],
                'no_bukti'  => $resulte['no_bukti'],
                'no_bukti2' => $resulte['no_bukti2'],
                'no_bukti3' => $resulte['no_bukti3'],
                'no_bukti4' => $resulte['no_bukti4'],
                'no_bukti5' => $resulte['no_bukti5'],
                'status' => $resulte['status'],
                'no_lpj' => $resulte['no_lpj'],
                'sts_setuju' => $resulte['sts_setuju'],
                'sp2d_batal' => $this->support->nvl($resulte['sp2d_batal'], ''),
                'ket_batal' => $this->support->nvl($resulte['ket_batal'], ''),
                'urut' => $resulte['urut'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function dsimpan_ag_edit()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kdskpd);
        $no_spp = $this->input->post('no');
        $no_hide = $this->input->post('no_hide');
        $csql     = $this->input->post('sql');
        $sql = "DELETE from trdspp where no_spp='$no_hide' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trdspp (no_spp,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan,no_spd,sumber,nm_sub_kegiatan)";
            $asg = $this->db->query($sql . $csql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                //   exit();
            } else {
                $sql = "UPDATE a 
                                SET a.nm_sub_kegiatan=b.nm_kegiatan
                                FROM trdspp  a
                                INNER JOIN trskpd b
                                ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd 
                                WHERE a.no_spp='$no_spp' and b.jns_ang='$data'";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }


    function update_tukd_spp()
    {
        $skpd  = $this->session->userdata('kdskpd');
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

    function kegiatan_spd_tu()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $dkd_skpd = substr($kd_skpd, 0, 17);
        $spd      = $this->input->post('spd');
        $tgl_spp      = $this->input->post('tgl_spp');
        $lccr     = $this->input->post('q');

        //$sqlproteksi = $this->db->query("select init from ms_skpd_tu where kd_skpd='$kd_skpd'")->row();
        $sqlproteksiinit = 2; //$sqlproteksi->init;

        if ($sqlproteksiinit == '1') {

            $sqlcekk = "SELECT DATEDIFF(day,'$tgl_spp',GETDATE()) as selisih from ms_skpd where kd_skpd='$kd_skpd'";

            $sqlcekkc = $this->db->query($sqlcekk);
            foreach ($sqlcekkc->result_array() as $resultecek) {
                $jumlah_hari = $resultecek['selisih'];
            }

            if ($jumlah_hari != 0) {
                $sql  = "SELECT '' kd_sub_kegiatan, 'LPJ TU Sebelumnya belum disahkan' nm_sub_kegiatan";
            } else {

                $sql7 =
                    "SELECT sum(selisih) as selisih, COUNT(no_sp2d) as jumlah FROM (
                                SELECT no_sp2d , tgl_sp2d , DATEDIFF(day,tgl_sp2d,GETDATE()) as selisih
                                FROM trhsp2d WHERE jns_spp='3' AND kd_skpd = '$kd_skpd' AND no_sp2d 
                                NOT IN (select no_sp2d FROM trhlpj WHERE kd_skpd='$kd_skpd' AND jenis='3' AND status='1'))a
                                ";

                $query7 = $this->db->query($sql7);
                foreach ($query7->result_array() as $resulte7) {
                    $jumlah = $resulte7['jumlah'];
                    $selisih = $resulte7['selisih'];
                }

                if ($selisih > 0) {

                    if ($jumlah > 0) {
                        $sql  = "SELECT '' kd_sub_kegiatan, 'LPJ TU Sebelumnya belum disahkan' nm_sub_kegiatan";
                    } else {

                        $cek = substr($kd_skpd, 18, 4);
                        if ($cek == "0000") {
                            $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdspd a 
                                                where a.no_spd='$spd' and substring(a.kd_sub_kegiatan,6,7) = '$dkd_skpd' AND (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%')) 
                                                 order by  a.kd_sub_kegiatan";
                        } else {
                            $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdspd a 
                                                 inner join trdrka c on c.kd_sub_kegiatan = a.kd_sub_kegiatan
                                                 where a.no_spd='$spd' and c.kd_skpd = '$kd_skpd'  AND (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%')) 
                                                  order by  a.kd_sub_kegiatan";
                        }
                    }
                } else {
                    $cek = substr($kd_skpd, 18, 4);
                    if ($cek == "0000") {


                        $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdspd a 
                                            where a.no_spd='$spd' and substring(a.kd_sub_kegiatan,6,7) = '$dkd_skpd'  AND (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%')) 
                                            order by  a.kd_sub_kegiatan";
                    } else {
                        $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdspd a 
                                         inner join trdrka c on c.kd_sub_kegiatan = a.kd_sub_kegiatan
                                         where a.no_spd='$spd' and c.kd_skpd = '$kd_skpd' AND (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%'))  order by  a.kd_sub_kegiatan";
                    }
                }
            }
        } else {

            $cek = substr($kd_skpd, 18, 4);
            if ($cek == "0000") {
                $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdspd a inner join trhspd b on a.no_spd=b.no_spd
                                where a.no_spd='$spd' and b.kd_skpd= '$kd_skpd' order by  a.kd_sub_kegiatan";
            } else {
                $sql = "SELECT DISTINCT a.kd_sub_kegiatan kd_sub_kegiatan,a.nm_sub_kegiatan nm_sub_kegiatan FROM trdspd a 
                               inner join trdrka c on c.kd_sub_kegiatan = a.kd_sub_kegiatan
                               where a.no_spd='$spd' and c.kd_skpd = '$kd_skpd' order by  a.kd_sub_kegiatan";
            }
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }




    // TU

}
