<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class lpj extends CI_Controller
{

    public $ppkd = "4.02.01";
    public $ppkd1 = "4.02.01.00";


    public $org_keu = "";
    public $skpd_keu = "";

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('pcNama') == '') {
            redirect('welcome');
        }
    }
    //halaman baru 
    // per skpd
    function up()
    {
        $data['page_title'] = 'INPUT LPJ UP';
        $this->template->set('title', 'INPUT LPJ UP');
        $this->template->load('template', 'tukd/lpj/tambah_lpj_up_skpd', $data);
    }

    function up_gab()
    {
        $data['page_title'] = 'INPUT LPJ UP';
        $this->template->set('title', 'INPUT LPJ UP');
        $this->template->load('template', 'tukd/lpj/tambah_lpj_up_gab', $data);
    }

    function up_unit()
    {
        $data['page_title'] = 'INPUT LPJ UP';
        $this->template->set('title', 'INPUT LPJ UP UNIT/SKPD');
        $this->template->load('template', 'tukd/lpj/tambah_lpj_up_unit', $data);
    }

    function validasi_up_unit()
    {
        $data['page_title'] = 'Validasi  LPJ UP/GU';
        $this->template->set('title', 'Validasi LPJ UP/GU');
        $this->template->load('template', 'tukd/lpj/validasi_lpj_up_unit', $data);
    }

    function up_gabungan()
    {
        $data['page_title'] = 'INPUT LPJ UP GABUNGAN';
        $this->template->set('title', 'INPUT LPJ UP');
        $this->template->load('template', 'tukd/lpj/tambah_lpj_up_gab', $data);
    }

    // function up()
    // {
    //     $data['page_title']= 'INPUT LPJ UP';
    //     $this->template->set('title', 'INPUT LPJ UP');   
    //     // $this->template->load('template','tukd/transaksi/tambah_lpj',$data) ; 
    //     $this->template->load('template','tukd/spp/maintenance',$data) ;
    // }


    function maintenance()
    {
        $data['page_title'] = 'SPP LS';
        $this->template->set('title', 'SPP LS');
        // $this->template->load('template','tukd/spp/maintenance',$data) ; 
        $this->template->load('template', 'tukd/transaksi/tambah_lpj', $data);
    }



    function tu()
    {
        $data['page_title'] = 'INPUT LPJ TU';
        $this->template->set('title', 'INPUT LPJ TU');
        $this->template->load('template', 'tukd/lpj/tambah_lpj_tu', $data);
    }

    function config_skpd()
    {
        $skpd = $this->session->userdata('kdskpd');
        $skp = $this->db->query("SELECT * from ms_skpd where kd_skpd='$skpd'");
        $result = array();
        $ii = 0;
        foreach ($skp->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function config_up()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT SUM(nilai_up) as nilai FROM ms_up WHERE kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'nilai_up' => $resulte['nilai']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function config_up_unit()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT SUM(nilai_up_unit) as nilai FROM ms_up WHERE kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'nilai_up' => $resulte['nilai']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function simpan_temp()
    {
        $id                 = $this->session->userdata('pcNama');
        $nolpjunit          = $this->input->post('nolpjunit');
        $kd_unit            = $this->input->post('kd_unit');
        $xnilai             = $this->input->post('xnilai');
        $kdskpd             = $this->session->userdata('kdskpd');
        $no_lpj             = $this->input->post('no_lpj');


        $sql = "INSERT into trlpj_unit_temp(no_lpj,kd_skpd,nilai,kd_bp_skpd,no_lpj_global)
        values ('$nolpjunit','$kd_unit','$xnilai','$kdskpd','$no_lpj')";
        $query1 = $this->db->query($sql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function simpan_lpj_global()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $csql     = $this->input->post('sql');

        $sql = "delete from trlpj where no_lpj='$nlpj' AND kd_bp_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trlpj 
                    (no_lpj_unit,no_bukti,tgl_lpj,keterangan,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai,username,tgl_update,kd_skpd,kd_subkegiatan,kd_bidsubkegiatan,kd_bp_skpd,no_lpj)
                    (select *,'$kdskpd','$nlpj' from trlpj_unit where 
                    left(kd_skpd,17)=left('$kdskpd',17) 
                    and no_lpj in (select no_lpj from trlpj_unit_temp where no_lpj_global='$nlpj')  )
                            ";
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

    function kosong_temp()
    {;
        $kd_unit            = $this->session->userdata('kdskpd');
        $sql = "DELETE FROM trlpj_unit_temp where kd_bp_skpd='$kd_unit' and (status_save<>'1' OR status_save is null)";
        $query1 = $this->db->query($sql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }


    function load_lpj()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> '') {
            $where = " and (upper(no_lpj) like upper('%$kriteria%') or tgl_lpj like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhlpj WHERE  kd_skpd = '$kd_skpd' AND jenis = '1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd = '$kd_skpd') as nm_skpd FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;

        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'kd_skpd'    => $resulte['kd_skpd'],
                'nm_skpd'    => $resulte['nm_skpd'],
                'ket'   => $resulte['keterangan'],
                'no_lpj'   => $resulte['no_lpj'],
                'tgl_lpj'      => $resulte['tgl_lpj'],
                'status'      => $resulte['status'],
                'tgl_awal'      => $resulte['tgl_awal'],
                'tgl_akhir'      => $resulte['tgl_akhir']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function load_lpj_unit()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> '') {
            $where = " and (upper(no_lpj) like upper('%$kriteria%') or tgl_lpj like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhlpj_unit WHERE  kd_skpd = '$kd_skpd' AND jenis = '1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd = '$kd_skpd') as nm_skpd FROM trhlpj_unit WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM trhlpj_unit WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;

        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'kd_skpd'   => $resulte['kd_skpd'],
                'nm_skpd'   => $resulte['nm_skpd'],
                'ket'       => $resulte['keterangan'],
                'no_lpj'    => $resulte['no_lpj'],
                'tgl_lpj'   => $resulte['tgl_lpj'],
                'status'    => $resulte['status'],
                'tgl_awal'  => $resulte['tgl_awal'],
                'tgl_akhir' => $resulte['tgl_akhir']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function load_lpjunit()
    {

        $lcskpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT z.*,(
                    select sum(nilai) from trlpj_unit where  no_lpj=z.no_lpj)as nilai  FROM trhlpj_unit z WHERE z.status='2' and jenis='1' and left(z.kd_skpd,17) = left('$lcskpd',17) and z.no_lpj NOT IN (SELECT no_lpj FROM trlpj_unit_temp where kd_bp_skpd='$lcskpd') ";

        //echo $sql;    
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_lpjunit' => $resulte['no_lpj'],
                'nilai' => number_format($resulte['nilai']),
                'kd_unit' => $resulte['kd_skpd'],
                'nm_unit' => $this->rka_model->get_nama($resulte['kd_skpd'], 'nm_skpd', 'ms_skpd', 'kd_skpd')

            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_lpj_unit_validasi()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> '') {
            $where = " and (upper(no_lpj) like upper('%$kriteria%') or tgl_lpj like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhlpj_unit WHERE  left(kd_skpd,17) = left('$kd_skpd',17) AND jenis = '1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd = '$kd_skpd') as nm_skpd
        -- ,(SELECT count(*) FROM trhspp g where left(g.kd_skpd,17) = left('$kd_skpd',17) and ) as nm_skpd
         FROM trhlpj_unit WHERE left(kd_skpd,17) = left('$kd_skpd',17) AND jenis = '1' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM trhlpj_unit WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;

        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'kd_skpd'   => $resulte['kd_skpd'],
                'nm_skpd'   => $resulte['nm_skpd'],
                'ket'       => $resulte['keterangan'],
                'no_lpj'    => $resulte['no_lpj'],
                'tgl_lpj'   => $resulte['tgl_lpj'],
                'status'    => $resulte['status'],
                'tgl_awal'  => $resulte['tgl_awal'],
                'tgl_akhir' => $resulte['tgl_akhir']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }
    function load_sum_lpj_unit_validasi()
    {
        $xlpj = $this->input->post('lpj');
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT SUM(a.nilai)AS jml FROM trlpj_unit a INNER JOIN trhlpj_unit b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                  WHERE b.no_lpj='$xlpj'  ");
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
    function select_data1_lpj_ag_unit($lpj = '')
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT (select d.tgl_bukti from trhtransout d left join trdtransout c on c.no_bukti=d.no_bukti and c.kd_skpd=d.kd_skpd where c.no_bukti=a.no_bukti and c.kd_skpd=a.kd_skpd and c.kd_sub_kegiatan=a.kd_sub_kegiatan and c.kd_rek6=a.kd_rek6) as tgl_bukti,
         a.kd_skpd, a.no_lpj,a.no_bukti,a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai FROM trlpj_unit a INNER JOIN trhlpj_unit b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                WHERE a.no_lpj='$lpj' AND a.kd_skpd='$kd_skpd' order by tgl_bukti";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'no_bukti'   => $resulte['no_bukti'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_rek6'],
                'nilai1'      => number_format($resulte['nilai']),
                'tgl_bukti'   => $resulte['tgl_bukti']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function select_data1_lpj_ag_unit_validasi($lpj = '')
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT (select d.tgl_bukti from trhtransout d left join trdtransout c on c.no_bukti=d.no_bukti and c.kd_skpd=d.kd_skpd where c.no_bukti=a.no_bukti and c.kd_skpd=a.kd_skpd and c.kd_sub_kegiatan=a.kd_sub_kegiatan and c.kd_rek6=a.kd_rek6) as tgl_bukti,
         a.kd_skpd, a.no_lpj,a.no_bukti,a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai FROM trlpj_unit a INNER JOIN trhlpj_unit b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                WHERE a.no_lpj='$lpj'  order by tgl_bukti";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'no_bukti'   => $resulte['no_bukti'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_rek6'],
                'nilai1'      => number_format($resulte['nilai']),
                'tgl_bukti'   => $resulte['tgl_bukti']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }

    function setuju_lpj()
    {
        $lpj = $this->input->post('no_lpj');
        $kdskpd = $this->input->post('kd_skpd');
        $sql = "UPDATE trhlpj_unit SET status='2' WHERE no_lpj='$lpj' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if ($asg > 0) {
            echo '2';
            exit();
        } else {
            echo '0';
            exit();
        }
    }

    function cek_sisa_spd()
    {
        $skpd = $this->session->userdata('kdskpd');
        $spp    = $this->input->post('spp');
        $jns    = $this->input->post('jns');
        $nobukti    = $this->input->post('nobukti');
        $jnsspp = $this->input->post('jnsspp');
        $nospd = $this->input->post('nospd');
        if ($nospd == '') {
            $tgl = '';
        } else {
            $tgl    = $this->tukd_model->get_nama($nospd, 'tgl_spd', 'trhspd', 'no_spd');
        }

        $nosp2d = $this->input->post('nosp2d');
        if ($nosp2d != '') {
            $spp    = $this->tukd_model->get_nama($nosp2d, 'no_spp', 'trhsp2d', 'no_sp2d');
        }


        $query = $this->tukd_model->cek_sisa_spd_lpj($skpd, $jns, $spp, $nobukti, $tgl);
        $result = array();
        $ii = 0;
        foreach ($query->result_array() as $row) {
            $result[] = array(
                'id' => $ii,
                'keluar'  =>  $row['keluar1'],
                'spd'  =>  $row['spd'],
                'keluarspp'  =>  $row['keluarspp']
            );
            $ii++;
        }
        echo json_encode($result);
        $query->free_result();
    }

    function tambah_tanggal()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT DATEADD(DAY,1,MAX(tgl_akhir)) as tanggal_awal FROM trhlpj WHERE jenis='1' AND kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'tgl_awal' => $resulte['tanggal_awal']

            );
            $ii++;
        }
        echo json_encode($result);
    }

    function tambah_tanggal_unit()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT DATEADD(DAY,1,MAX(tgl_akhir)) as tanggal_awal FROM trhlpj_unit WHERE jenis='1' AND kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'tgl_awal' => $resulte['tanggal_awal']

            );
            $ii++;
        }
        echo json_encode($result);
    }

    function select_data1_lpj($lpj = '')
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT a.tgl_bukti,a.kd_skpd,a.kd_skpd as kd_bp_skpd,a.nm_skpd,a.no_bukti,b.kd_sub_kegiatan,b.kd_rek6,b.nm_rek6,b.nilai,c.no_lpj,c.tgl_lpj FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti 
				AND a.kd_skpd=b.kd_skpd INNER JOIN trlpj c ON b.no_bukti=c.no_bukti AND b.kd_skpd=c.kd_skpd WHERE no_lpj='$lpj' AND a.kd_skpd='$kd_skpd' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'no_bukti'   => $resulte['no_bukti'],
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'kdrek5'     => $resulte['kd_rek6'],
                'nmrek5'     => $resulte['nm_rek6'],
                'nilai1'     => number_format($resulte['nilai']),
                'tgl_bukti'  => $resulte['tgl_bukti'],
                'kd_bp_skpd' => $resulte['kd_bp_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_data_transaksi_lpj($dtgl1 = '', $dtgl2 = '', $kdskpd = '')
    {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
        $kdskpd = $this->input->post('kdskpd');
        $skpdd = substr($kdskpd, 0, 7);

        // $sql    = "SELECT b.kd_skpd,b.tgl_bukti,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.no_bukti,a.nilai,a.kd_skpd as kd_skpd1 FROM trdtransout a inner join trhtransout b on 
        //                  a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd WHERE (a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_bp_skpd) FROM trlpj) AND b.panjar not in ('3','5') AND b.tgl_bukti >= '$dtgl1' and b.tgl_bukti <= '$dtgl2' and b.jns_spp='1' and left(b.kd_skpd,17)=left('$kdskpd',17) 
        //                  ORDER BY  b.kd_skpd,b.tgl_bukti,a.kd_sub_kegiatan, a.kd_rek6, cast(a.no_bukti as int)"; 	

        $sql = "SELECT kd_skpd, tgl_bukti, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6,nm_rek6,no_bukti,nilai,kd_skpd1 from (
            select b.kd_skpd AS kd_skpd,
            b.tgl_bukti AS tgl_bukti,
            a.kd_sub_kegiatan AS kd_sub_kegiatan,
            a.nm_sub_kegiatan AS nm_sub_kegiatan,
            a.kd_rek6 AS kd_rek6,
            a.nm_rek6 AS nm_rek6,
            a.no_bukti AS no_bukti,
            a.nilai AS nilai,
            a.kd_skpd AS kd_skpd1 
        FROM
            trdtransout a
            INNER JOIN trhtransout b ON a.no_bukti= b.no_bukti 
            AND a.kd_skpd = b.kd_skpd 
        WHERE
            ( a.no_bukti+ a.kd_sub_kegiatan+ a.kd_rek6+ a.kd_skpd ) NOT IN ( SELECT ( no_bukti + kd_sub_kegiatan + kd_rek6 + kd_skpd ) FROM trlpj ) 
            AND b.panjar NOT IN ( '3', '5' ) 
            AND b.tgl_bukti >= '$dtgl1' 
            AND b.tgl_bukti <= '$dtgl2' 
            AND b.jns_spp= '1' 
            AND b.kd_skpd= '$kdskpd' 
             
            UNION all
        
        SELECT
            c.kd_skpd AS kd_skpd,
            d.TGL_BUKTI AS tgl_bukti,
            d.kd_sub_kegiatan AS kd_sub_kegiatan,
            f.nm_sub_kegiatan AS nm_sub_kegiatan,
            c.kd_rek6 AS kd_rek6,
            e.nm_rek6 AS nm_rek6,
            c.no_bukti AS no_bukti,
            '-1' * c.nilai AS nilai,
            c.kd_skpd AS kd_skpd1 
        FROM
            trdinlain c
            INNER JOIN trhinlain d ON c.no_bukti= d.no_bukti 
            AND c.kd_skpd = d.KD_SKPD 
            INNER JOIN ms_rek6 e on c.kd_rek6 = e.kd_rek6
            INNER JOIN ms_sub_kegiatan f on d.kd_sub_kegiatan = f.kd_sub_kegiatan
        WHERE
            c.kd_skpd = '$kdskpd' 
            AND d.TGL_BUKTI >= '$dtgl1' 
            AND d.tgl_bukti <= '$dtgl2'
            and d.pay = 'TUNAI')x ORDER BY x.kd_skpd,x.tgl_bukti,x.kd_sub_kegiatan, x.kd_rek6, cast(x.no_bukti as int)
        ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx' => $ii,
                'kdskpd'    => $resulte['kd_skpd'],
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'nmkegiatan' => $resulte['nm_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_rek6'],
                'nilai1'     => number_format($resulte['nilai'], 2),
                'kd_bp_skpd' => $resulte['kd_skpd1'],
                'no_bukti'   => $resulte['no_bukti'],
                'tgl_bukti'   => $resulte['tgl_bukti']
            );
            $ii++;
        }
        echo json_encode($result);
    }



    function load_sum_lpj()
    {
        $xlpj = $this->input->post('lpj');
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT SUM(a.nilai)AS jml FROM trlpj a INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_bp_skpd=b.kd_skpd
                  WHERE b.no_lpj='$xlpj' AND a.kd_bp_skpd='$skpd' ");
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

    function load_sum_data_transaksi_lpj($dtgl1 = '', $dtgl2 = '')
    {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
        $kdskpd  = $this->session->userdata('kdskpd');
        $skpdd = substr($kdskpd, 0, 7);

        // $sql    = "SELECT SUM(a.nilai) as jumlah FROM trdtransout a inner join trhtransout b on 
        //            a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE (a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_bp_skpd) FROM trlpj) AND b.tgl_bukti >= '$dtgl1' and b.tgl_bukti <= '$dtgl2' and b.jns_spp='1' and left(b.kd_skpd,17)=left('$kdskpd',17)
        //            ";

        $sql = "SELECT (sum(nilaiA)-sum(nilaiB)) jumlah from (
            select
            sum(a.nilai) AS nilaiA,
            0 as nilaiB
        FROM
            trdtransout a
            INNER JOIN trhtransout b ON a.no_bukti= b.no_bukti 
            AND a.kd_skpd = b.kd_skpd 
        WHERE
            ( a.no_bukti+ a.kd_sub_kegiatan+ a.kd_rek6+ a.kd_skpd ) NOT IN ( SELECT ( no_bukti + kd_sub_kegiatan + kd_rek6 + kd_skpd ) 
            FROM trlpj ) 
            AND b.panjar NOT IN ( '3', '5' ) 
            AND b.tgl_bukti >= '$dtgl1' 
            AND b.tgl_bukti <= '$dtgl2' 
            AND b.jns_spp= '1' 
            AND b.kd_skpd= '$kdskpd' 
             
            UNION all
        SELECT
        0 as nilaiA,
             sum(nilai) AS nilaiB
        FROM
            TRHINLAIN
        WHERE
            kd_skpd = '$kdskpd' 
            AND TGL_BUKTI >= '$dtgl1' 
            AND tgl_bukti <= '$dtgl2'
            and pay = 'TUNAI')x ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx' => $ii,
                'jumlah' => $resulte['jumlah']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
    function load_sum_data_transaksi_lpjunit($dtgl1 = '', $dtgl2 = '')
    {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
        $kdskpd  = $this->session->userdata('kdskpd');
        $skpdd = substr($kdskpd, 0, 7);

        $sql    = "SELECT SUM(a.nilai) as jumlah FROM trdtransout a inner join trhtransout b on 
                   a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE NOT EXISTS (SELECT * FROM trlpj_unit g where g.kd_skpd=a.kd_skpd and g.kd_sub_kegiatan=a.kd_sub_kegiatan and g.kd_rek6=a.kd_rek6 and g.no_bukti=a.no_bukti) AND b.tgl_bukti >= '$dtgl1' and b.tgl_bukti <= '$dtgl2' and b.jns_spp='1' and b.kd_skpd='$kdskpd'
                   ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx' => $ii,
                'jumlah' => $resulte['jumlah']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_data_transaksi_lpj_unit($dtgl1 = '', $dtgl2 = '', $kdskpd = '')
    {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
        $kdskpd = $this->input->post('kdskpd');
        $skpdd = substr($kdskpd, 0, 7);

        $sql    = "SELECT * FROM (SELECT b.kd_skpd,b.tgl_bukti,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.no_bukti,a.nilai,a.kd_skpd as kd_skpd1 FROM trdtransout a inner join trhtransout b on 
                   a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd WHERE (a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_skpd) FROM trlpj_unit) AND b.panjar not in ('3','5') AND b.tgl_bukti >= '$dtgl1' and b.tgl_bukti <= '$dtgl2' and b.jns_spp='1' and b.kd_skpd='$kdskpd'
                   
                   UNION ALL 
                   
                   SELECT b.kd_skpd,b.tgl_bukti,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.no_bukti,a.nilai,a.kd_skpd as kd_skpd1 FROM trdtransout a inner join trhtransout b on 
                   a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd WHERE (a.no_bukti+a.kd_sub_kegiatan+a.kd_rek6+a.kd_skpd) NOT IN(SELECT (no_bukti+kd_sub_kegiatan+kd_rek6+kd_skpd) FROM trlpj_unit) AND b.panjar in ('3','5') AND b.tgl_bukti >= '$dtgl1' and b.tgl_bukti <= '$dtgl2' and b.jns_spp='1' and b.kd_skpd='$kdskpd'
                   )z
                   ORDER BY  kd_skpd,tgl_bukti,kd_sub_kegiatan, kd_rek6, cast(no_bukti as int)";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx' => $ii,
                'kdskpd'    => $resulte['kd_skpd'],
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'nmkegiatan' => $resulte['nm_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_rek6'],
                'nilai1'     => number_format($resulte['nilai']),
                'kd_bp_skpd' => $resulte['kd_skpd1'],
                'no_bukti'   => $resulte['no_bukti'],
                'tgl_bukti'   => $resulte['tgl_bukti']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function simpan_lpj()
    {

        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $csql     = $this->input->post('sql');

        $sql = "delete from trlpj where no_lpj='$nlpj' AND kd_bp_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trlpj (no_lpj,kd_skpd,no_bukti,tgl_lpj,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_bp_skpd)";
            $asg = $this->db->query($sql . $csql);


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

    function simpan_lpj_unit()
    {

        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $csql     = $this->input->post('sql');

        $sql = "delete from trlpj_unit where no_lpj='$nlpj' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trlpj_unit (no_lpj,kd_skpd,no_bukti,tgl_lpj,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai)";
            $asg = $this->db->query($sql . $csql);


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

    function cek_simpan_unit()
    {
        $nomor      = $this->input->post('no');
        $tabel      = $this->input->post('tabel');
        $field      = $this->input->post('field');
        $field2     = $this->input->post('field2');
        $tabel2     = $this->input->post('tabel2');
        $kd_skpd    = $this->session->userdata('kdskpd');
        if ($field2 == '') {
            $hasil = $this->db->query(" SELECT count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else {
            $hasil = $this->db->query(" SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
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

    function simpan_hlpj()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');

        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1')";
        $query1 = $this->db->query($csql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function simpan_hlpj_unit()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');

        $csql = "INSERT INTO trhlpj_unit (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1')";
        $query1 = $this->db->query($csql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function update_hlpj_up()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');

        $csql = "delete from trhlpj where no_lpj= '$no_simpan'  and kd_skpd='$kdskpd'";
        $query1 = $this->db->query($csql);
        $csql = "delete from trlpj where no_lpj= '$no_simpan' and kd_skpd='$kdskpd' ";
        $query1 = $this->db->query($csql);
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1')";
        $query1 = $this->db->query($csql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function update_hlpj_up_unit()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');
        $cket = $this->input->post('ket');

        $csql = "delete from trhlpj_unit where no_lpj= '$no_simpan'  and kd_skpd='$kdskpd'";
        $query1 = $this->db->query($csql);
        $csql = "delete from trlpj_unit where no_lpj= '$no_simpan' and kd_skpd='$kdskpd' ";
        $query1 = $this->db->query($csql);
        $csql = "INSERT INTO trhlpj_unit (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,tgl_akhir,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_awal','$tgl_akhir','1')";
        $query1 = $this->db->query($csql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function simpan_lpj_update()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $csql     = $this->input->post('sql');

        $sql = "DELETE from trlpj where no_lpj='$no_simpan' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trlpj (no_lpj,kd_skpd,no_bukti,tgl_lpj,keterangan,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_bp_skpd)";
            $asg = $this->db->query($sql . $csql);


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

    function simpan_lpj_update_unit()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $csql     = $this->input->post('sql');

        $sql = "DELETE from trlpj_unit where no_lpj='$no_simpan' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        } else {
            $sql = "INSERT INTO trlpj_unit(no_lpj,kd_skpd,no_bukti,tgl_lpj,keterangan,kd_sub_kegiatan,kd_rek6,nm_rek6,nilai)";
            $asg = $this->db->query($sql . $csql);


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

    function select_data1_lpj_ag_global($lpj = '')
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT a.* FROM trlpj_unit_temp a INNER JOIN trhlpj b ON a.no_lpj_global=b.no_lpj AND a.kd_bp_skpd=b.kd_skpd
                WHERE a.no_lpj_global='$lpj' AND a.kd_bp_skpd='$kd_skpd' order by no_lpj";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'           => $ii,
                'nolpj'         => $lpj,
                'kd_skpd'       => $kd_skpd,
                'kdunit'        => $resulte['kd_skpd'],
                'nmunit'        => $this->rka_model->get_nama($resulte['kd_skpd'], 'nm_skpd', 'ms_skpd', 'kd_skpd'),
                'nolpjunit'     => $resulte['no_lpj'],
                'nilai1'        => number_format($resulte['nilai'])
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function sisa_spd_global()
    {
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT ISNULL(nilai_spd,0) spd, ISNULL(transaksi,0) transaksi, isnull(nilai_spd,0)-isnull(transaksi,0) sisa_spd FROM(
                                    select 1 as nomor, SUM(nilai) as nilai_spd from trhspd a 
                                    INNER JOIN trdspd b ON a.no_spd=b.no_spd
                                    WHERE kd_skpd = '$skpd' AND (RIGHT(kd_sub_kegiatan,10) !='01.1.02.01' OR kd_sub_kegiatan !='4.01.01.1.11.01') AND status='1'
                                    ) a LEFT JOIN (
                                    SELECT 1 as nomor, SUM(b.nilai) as transaksi FROM trhspp a 
                                    INNER JOIN trdspp b ON a.kd_skpd=b.kd_skpd AND a.no_spp=b.no_spp
                                    WHERE a.kd_skpd = '$skpd' AND (RIGHT(b.kd_sub_kegiatan,10) !='01.1.02.01' OR b.kd_sub_kegiatan !='4.01.01.1.11.01') and (sp2d_batal is null or sp2d_batal<>'1') 
                                    ) b ON a.nomor=b.nomor");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'spd'  => $resulte['spd'],
                'transaksi' => $resulte['transaksi'],
                'sisa_spd' => $resulte['sisa_spd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function select_data1_lpj_ag($lpj = '')
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT (select top 1 d.tgl_bukti from trhtransout d left join trdtransout c on c.no_bukti=d.no_bukti and c.kd_skpd=d.kd_skpd where c.no_bukti=a.no_bukti and c.kd_skpd=a.kd_bp_skpd and c.kd_sub_kegiatan=a.kd_sub_kegiatan and c.kd_rek6=a.kd_rek6) as tgl_bukti,
         a.kd_skpd, a.no_lpj,a.no_bukti,a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai,kd_bp_skpd FROM trlpj a INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
				WHERE a.no_lpj='$lpj' AND a.kd_skpd='$kd_skpd' order by tgl_bukti";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'no_bukti'   => $resulte['no_bukti'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'kdrek6'     => $resulte['kd_rek6'],
                'nmrek6'     => $resulte['nm_rek6'],
                'kd_bp_skpd' => $resulte['kd_bp_skpd'],
                'nilai1'      => number_format($resulte['nilai'], 2),
                'tgl_bukti'   => $resulte['tgl_bukti']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_giat_lpj()
    {
        $kode     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('lpj');
        $query1 = $this->db->query("
		SELECT a.kd_sub_kegiatan, c.nm_sub_kegiatan
		from trlpj a 
		INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
		LEFT JOIN trskpd c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan
		WHERE a.no_lpj = '$nomor' AND a.kd_skpd='$kode'
		GROUP BY a.kd_sub_kegiatan,c.nm_sub_kegiatan
		ORDER BY a.kd_sub_kegiatan");
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

        //return $result;
        echo json_encode($result);
    }

    function load_giat_lpj_unit()
    {
        $kode     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('lpj');
        $query1 = $this->db->query("
        SELECT a.kd_sub_kegiatan, c.nm_sub_kegiatan
        from trlpj_unit a 
        INNER JOIN trhlpj_unit b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trskpd c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan
        WHERE a.no_lpj = '$nomor' AND a.kd_skpd='$kode'
        GROUP BY a.kd_sub_kegiatan,c.nm_sub_kegiatan
        ORDER BY a.kd_sub_kegiatan");
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

        //return $result;
        echo json_encode($result);
    }

    function load_ttd()
    {
        $kode     = $this->session->userdata('kdskpd');
        $cari = $this->input->post('q');
        echo $this->master_ttd->load_bendahara_p($kode, $cari);
    }
    function load_tanda_tangan()
    {
        $kode     = $this->session->userdata('kdskpd');
        $cari = $this->input->post('q');
        echo $this->master_ttd->load_tanda_tangan($kode, $cari);
    }
    function load_sp2d_lpj_tu()
    {

        $lcskpd  = $this->session->userdata('kdskpd');
        //$lcskpd = $this->uri->segment(4);

        $sql = "SELECT no_sp2d,tgl_sp2d FROM trhsp2d WHERE jns_spp = '3' and status='1' and left(kd_skpd,22) = left('$lcskpd',22) and no_sp2d NOT IN (SELECT ISNULL(no_sp2d,'') FROM trhlpj)";

        //echo $sql;    
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_cair' => $resulte['tgl_sp2d']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_data_transaksi_lpj_tu()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $no_sp2d  = $this->input->post('no_sp2d');
        $cek = substr($kdskpd, 8, 2);
        if ($cek == "00") {
            $hasil = "left(b.kd_skpd,7)=left('$kdskpd',7)";
        } else {
            $hasil = "b.kd_skpd='$kdskpd'";
        }

        $sql    = "SELECT a.kd_sub_kegiatan,a.nm_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai, a.no_bukti,a.kd_skpd as kd_skpd1 FROM trdtransout a inner join trhtransout b on 
                   a.no_bukti=b.no_bukti and a.kd_skpd = b.kd_skpd WHERE a.no_sp2d = '$no_sp2d' and $hasil 
                   ORDER BY a.no_bukti, a.kd_sub_kegiatan, a.kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'idx' => $ii,
                'kd_bp_skpd'   => $resulte['kd_skpd1'],
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'nmkegiatan' => $resulte['nm_kegiatan'],
                'kdrek5'     => $resulte['kd_rek6'],
                'nmrek5'     => $resulte['nm_rek6'],
                'nilai1'     => number_format($resulte['nilai']),
                'no_bukti'   => $resulte['no_bukti']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_hlpj_tu()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_sp2d = $this->input->post('tgl_sp2d');
        $sp2d = $this->input->post('sp2d');
        $cket = $this->input->post('ket');

        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,no_sp2d,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_sp2d','$sp2d','3')";
        $query1 = $this->db->query($csql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function load_lpj_tu()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " ";
        if ($kriteria <> '') {
            $where = " and (upper(no_lpj) like upper('%$kriteria%') or tgl_lpj like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trhlpj WHERE  kd_skpd = '$kd_skpd' AND jenis = '3' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows kd_skpd,keterangan,no_lpj,tgl_lpj,ISNULL(status,0) as status, tgl_awal,no_sp2d,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd = '$kd_skpd') as nm_skpd FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '3' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '3' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";

        $query1 = $this->db->query($sql);
        $result = array();
        $row = array();
        $ii = 0;

        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id'      => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'ket'     => $resulte['keterangan'],
                'no_lpj'  => $resulte['no_lpj'],
                'tgl_lpj' => $resulte['tgl_lpj'],
                'status'  => $resulte['status'],
                'tgl_sp2d' => $resulte['tgl_awal'],
                'sp2d'    => $resulte['no_sp2d']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function update_hlpj_tu()
    {
        $kdskpd  = $this->session->userdata('kdskpd');
        $nlpj = $this->input->post('nlpj');
        $no_simpan = $this->input->post('no_simpan');
        $ntgllpj = $this->input->post('tgllpj');
        $tgl_sp2d = $this->input->post('tgl_sp2d');
        $sp2d = $this->input->post('sp2d');
        $cket = $this->input->post('ket');
        $csql = "delete from trhlpj where no_lpj= '$no_simpan' AND kd_skpd='$kdskpd' ";
        $query1 = $this->db->query($csql);
        $csql = "INSERT INTO trhlpj (no_lpj,kd_skpd,keterangan,tgl_lpj,status,tgl_awal,no_sp2d,jenis) values ('$nlpj','$kdskpd','$cket','$ntgllpj','0','$tgl_sp2d','$sp2d','3')";
        $query1 = $this->db->query($csql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function hhapuslpj()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $query = $this->db->query("DELETE from trlpj where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
        $query = $this->db->query("DELETE from trhlpj where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
    }

    function hhapuslpj_unit()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $query = $this->db->query("DELETE from trlpj_unit where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
        $query = $this->db->query("DELETE from trhlpj_unit where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
    }


    function hhapuslpj_skpd()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $cek = $this->db->query("SELECT [status] from trhlpj where no_lpj='$nomor' AND kd_skpd='$kd_skpd'")->row();
        $cek1 = $cek->status;
        if ($cek1 == 2) {
            $msg = array('pesan' => '2');
            echo json_encode($msg);
            return;
        } else {
            $query = $this->db->query("DELETE from trlpj where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
            $query = $this->db->query("DELETE from trhlpj where no_lpj='$nomor' AND kd_skpd='$kd_skpd'");
            $msg = array('pesan' => '1');
            echo json_encode($msg);
            return;
        }
    }




    function cetaksptb_lpj()
    {
        $client = $this->ClientModel->clientData('1');
        $print = $this->uri->segment(3);
        $nomor   = str_replace('abcdefghij', '/', $this->uri->segment(4));
        $nomor   = str_replace('123456789', ' ', $nomor);
        $jns   = $this->uri->segment(5);
        $kd    = $this->uri->segment(6);
        $PA = str_replace('a', ' ', $this->uri->segment(7));


        $alamat_skpd = $this->rka_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->rka_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        $nm_skpd = $this->rka_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd');

        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$PA' and kode in ('PA','KPA') AND kd_skpd='$kd' ";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        $sqldpa = "SELECT no_dpa as no, tgl_dpa as tgl from trhrka a where a.kd_skpd = '$kd' and 
        a.tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd)";
        $sqldpa = $this->db->query($sqldpa);
        foreach ($sqldpa->result() as $rowdpa) {
            $no_dpa = $rowdpa->no;
            $tgl_dpa = $this->support->tanggal_format_indonesia($rowdpa->tgl);
        }

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $thn_ang = $rowsc->thn_ang;
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
                    
                                    
                <table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN TANGGUNG JAWABAN BELANJA</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                  </table>

                <table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">";

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

        if ($jns == 1) { //SPTB LPJ UP
            $sql1 = "SELECT sum(nilai) [nilai],b.tgl_lpj from trlpj a join trhlpj b on a.no_lpj=b.no_lpj and a.kd_bp_skpd=b.kd_skpd 
                where a.no_lpj='$nomor' and b.jenis='$jns'  and  left(a.kd_skpd,17)=left('$kd',17) group by b.tgl_lpj ";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $tgl = $row->tgl_lpj;
                $tanggal = $this->support->tanggal_format_indonesia($tgl);
                $nilai = number_format($row->nilai, "2", ",", ".");
                //echo($a);
            }


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">5. Jumlah Belanja </td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    Rp. $nilai</td>
                                     </tr>";
        }


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

            //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

            $this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($print == '0') {
            echo $cRet;
        }
    }

    function cetaksptb_lpj_unit()
    {
        $print = $this->uri->segment(3);
        $nomor   = str_replace('abcdefghij', '/', $this->uri->segment(4));
        $nomor   = str_replace('123456789', ' ', $nomor);
        $jns   = $this->uri->segment(5);
        $kd    = $this->uri->segment(6);
        $PA = str_replace('a', ' ', $this->uri->segment(7));


        $alamat_skpd = $this->rka_model->get_nama($kd, 'alamat', 'ms_skpd', 'kd_skpd');
        $kodepos = $this->rka_model->get_nama($kd, 'kodepos', 'ms_skpd', 'kd_skpd');
        $nm_skpd = $this->rka_model->get_nama($kd, 'nm_skpd', 'ms_skpd', 'kd_skpd');

        if ($kodepos == '') {
            $kodepos = "-------";
        } else {
            $kodepos = "$kodepos";
        }


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$PA' and kode in ('PA','KPA') AND kd_skpd='$kd' ";
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
            $tgl_dpa = $this->support->tanggal_format_indonesia($rowdpa->tgl);
        }

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $thn_ang = $rowsc->thn_ang;
        }

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"right\">
                        <img src=\"" . base_url() . "/image/logo_sanggau.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td colspan=\"2\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH KABUPATEN SANGGAU </strong></td></tr>";



        if (substr($kd, 0, 17) == $this->org_keu && $kd != $this->skpd_keu) {
            $nm_org = $this->rka_model->get_nama($this->skpd_keu, 'nm_skpd', 'ms_skpd', 'kd_skpd');
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
                    
                                    
                <table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr><td align=\"center\"><strong><u>SURAT PERNYATAAN TANGGUNG JAWABAN BELANJA</u></strong></td></tr>
                    <tr><td align=\"center\">&nbsp;</td></tr>
                  </table>

                <table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">";

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

        if ($jns == 1) { //SPTB LPJ UP
            $sql1 = "SELECT sum(nilai) [nilai],b.tgl_lpj from trlpj_unit a join trhlpj_unit b on a.no_lpj=b.no_lpj and a.kd_skpd=b.kd_skpd 
                where a.no_lpj='$nomor' and b.jenis='$jns'  and  a.kd_skpd='$kd' group by b.tgl_lpj ";

            $query = $this->db->query($sql1);

            foreach ($query->result() as $row) {
                $tgl = $row->tgl_lpj;
                $tanggal = $this->support->tanggal_format_indonesia($tgl);
                $nilai = number_format($row->nilai, "2", ",", ".");
                //echo($a);
            }


            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"left\">5. Jumlah Belanja </td> 
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"2%\" align=\"center\">:</td>                                     
                                    <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"75%\" align=\"justify\">
                                    Rp. $nilai</td>
                                     </tr>";
        }


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

            //_mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='')

            $this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
        }
        if ($print == '0') {
            echo $cRet;
        }
    }

    function cetaklpjup_ag_unit()
    {
        ini_set("memory_limit", "-1");
        $cskpd  = $this->uri->segment(4);
        $ttd1   = str_replace('a', ' ', $this->uri->segment(3));
        $ttd2   = str_replace('a', ' ', $this->uri->segment(6));
        $ctk =   $this->uri->segment(5);
        $nomor1   = str_replace('abcdefghij', '/', $this->uri->segment(7));
        $nomor   = str_replace('123456789', ' ', $nomor1);
        $jns =   $this->uri->segment(8);
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);
        $lctgl1 = $this->rka_model->get_nama2($nomor, 'tgl_awal', 'trhlpj_unit', 'no_lpj', 'kd_skpd', $cskpd);
        $lctgl2 = $this->rka_model->get_nama2($nomor, 'tgl_akhir', 'trhlpj_unit', 'no_lpj', 'kd_skpd', $cskpd);
        $lctglspp = $this->rka_model->get_nama2($nomor, 'tgl_lpj', 'trhlpj_unit', 'no_lpj', 'kd_skpd', $cskpd);




        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode in ('PA','KPA') and nip='$ttd2'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = 'Nip. ' . $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = 'Nip. ' . $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }
        $cRet  = " <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>" . strtoupper($jabatan1) . "</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .= " <table border='0' style='font-size:12px' width='100%'>
                        <tr>
                            <td align='left' width='10%' valign=\"top\"> OPD&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='2%' valign=\"top\">:</td>
                            <td align='left' valign=\"top\"> " . $cskpd . " " . $this->tukd_model->get_nama($cskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . " </td>
                        </tr>
                        <tr>
                            <td align='left' width='10%' valign=\"top\"> Periode&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='2%' valign=\"top\">:</td>
                            <td align='left' valign=\"top\"> $lctgl1 s/d  $lctgl2</td>
                        </tr>
                       
                   </table>             
                ";

        $cRet .= " <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>KODE REKENING</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>URAIAN</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>4</b></td>
                    </tr>
                    </THEAD>
                ";

        if ($jns == '0') {
            $sql = "SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
                        FROM trlpj_unit a LEFT JOIN (SELECT DISTINCT kd_program,nm_program,kd_skpd FROM trskpd GROUP BY kd_program,nm_program,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
                        UNION ALL

                        SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj_unit a LEFT JOIN (SELECT DISTINCT kd_kegiatan,nm_kegiatan,kd_skpd FROM trskpd GROUP BY kd_kegiatan,nm_kegiatan,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,12) =b.kd_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,12), b.nm_kegiatan

                        UNION ALL
                        SELECT 2 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj_unit a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        UNION ALL
                        SELECT 3 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, b.nm_rek2 as uraian, SUM(nilai) as nilai FROM trlpj_unit a
                        INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), b.nm_rek2
                        UNION ALL
                        SELECT 4 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,4) as kode, b.nm_rek3 as uraian, SUM(nilai) as nilai FROM trlpj_unit a
                        INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,4), b.nm_rek3
                        UNION ALL
                        
                        SELECT 5 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,6) as kode, b.nm_rek4 as uraian, SUM(nilai) as nilai FROM trlpj_unit a
                        INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,6), b.nm_rek4
                        UNION ALL

                        SELECT 6 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,8) as kode, b.nm_rek5 as uraian, SUM(nilai) as nilai FROM trlpj_unit a
                        INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,8), b.nm_rek5
                        UNION ALL
                        SELECT 7 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, nm_rek6 as uraian, SUM(nilai) as nilai FROM trlpj_unit
                        WHERE no_lpj='$nomor' AND kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
                        ORDER BY kode";
            $query1 = $this->db->query($sql);
            $total = 0;
            $i = 0;
            foreach ($query1->result() as $row) {
                $kode = $row->kode;
                $urut = $row->urut;
                $uraian = $row->uraian;
                $nilai  = $row->nilai;

                if ($urut == 1) {
                    $i = $i + 1;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><i><b>$i</b></i></td>
                                    <td valign='top' align='left' ><i><b>$kode</b></i></td>
                                    <td valign='top' align='left' ><i><b>$uraian</b></i></td>
                                    <td valign='top' align='right'><i><b>" . number_format($nilai, "2", ",", ".") . "</b></i></td>
                                </tr>";
                } else if ($urut == 2) {
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
                                </tr>";
                } else if ($urut == 7) {
                    $total = $total + $nilai;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right'>" . number_format($nilai, "2", ",", ".") . "</td>
                                </tr>";
                } else {
                    $cRet .= "<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >" . number_format($nilai, "2", ",", ".") . "</td>
                                </tr>";
                }
            }
        } else {
            $sql = "SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_program,nm_program,kd_skpd FROM trskpd GROUP BY kd_program,nm_program,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_bp_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
                        UNION ALL
                        SELECT 2 as urut, LEFT(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj_unit a LEFT JOIN (SELECT DISTINCT kd_kegiatan,nm_kegiatan,kd_skpd FROM trskpd GROUP BY kd_kegiatan,nm_kegiatan,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,12) =b.kd_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,12), b.nm_kegiatan
                        UNION ALL
                        SELECT 3 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj_unit a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE kd_skpd='$cskpd'
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        ORDER BY kode";
            $query1 = $this->db->query($sql);
            $total = 0;
            $i = 0;
            foreach ($query1->result() as $row) {
                $kode = $row->kode;
                $urut = $row->urut;
                $uraian = $row->uraian;
                $nilai  = $row->nilai;

                if ($urut == 1) {
                    $i = $i + 1;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><b>$i</b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
                                </tr>";
                } else if ($urut == 2) {
                    $i = $i + 1;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
                                </tr>";
                } else {
                    $total = $total + $nilai;
                    $cRet .= "<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >" . number_format($nilai, "2", ",", ".") . "</td>
                                </tr>";
                }
            }
        }

        // if (substr($cskpd, 18,4)!='0000'){
        //     $sqlp = " SELECT SUM(a.nilai)-(SELECT SUM(a.nilai) AS nilai FROM tr_setorpelimpahan_bank a 
        //           WHERE left(a.kd_skpd,17)=left('$cskpd',17)) AS nilai FROM trhsp2d a 
        //           WHERE a.kd_skpd='$cskpd' and jns_spp='1' ";

        // }else{
        $sqlp = " SELECT SUM(a.nilai_up_unit) AS nilai FROM ms_up a 
                          WHERE a.kd_skpd='$cskpd' ";

        // }
        $queryp = $this->db->query($sqlp);
        foreach ($queryp->result_array() as $nlx) {
            $persediaan = $nlx["nilai"];
        }

        $cRet .= "
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>" . number_format($total, "2", ",", ".") . "</b></td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Awal Periode</b></td>
                            <td align='right' ><b>" . number_format($persediaan, "2", ",", ".") . "</b></td>
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Akhir Periode</b></td>
                            <td align='right' ><b>" . number_format($persediaan - $total, "2", ",", ".") . "</b></td>
                        </tr>
                        </tr>
                        ";


        $cRet .= "</table><p>";
        //.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .= " <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Mengetahui <br> $jabatan    </td>
                        <td valign='top' align='center' width='50%'>$daerah, " . $this->tukd_model->tanggal_format_indonesia($lctglspp) . " <br> $jabatan1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'><b><u>$nama1</u></b><br>$pangkat1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$nip</td>
                        <td align='center' width='50%'>$nip1</td>
                    </tr>
                  </table>
                ";

        $data['prev'] = $cRet;

        switch ($ctk) {
            case 0;
                echo ("<title> LPJ UP</title>");
                echo $cRet;
                break;
            case 1;
                $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
                //$this->_mpdf('',$cRet,10,10,10,'0',0,'');
                break;
        }
    }



    function cetaklpjup_ag()
    {
        ini_set("memory_limit", "-1");
        $cskpd  = $this->uri->segment(4);
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($cskpd);
        $ttd1   = str_replace('a', ' ', $this->uri->segment(3));
        $ttd2   = str_replace('a', ' ', $this->uri->segment(6));
        $ctk =   $this->uri->segment(5);
        $nomor1   = str_replace('abcdefghij', '/', $this->uri->segment(7));
        $nomor   = str_replace('123456789', ' ', $nomor1);
        $jns =   $this->uri->segment(8);
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);
        $baris = $this->uri->segment(13);
        $lctgl1 = $this->rka_model->get_nama2($nomor, 'tgl_awal', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);
        $lctgl2 = $this->rka_model->get_nama2($nomor, 'tgl_akhir', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);
        $lctglspp = $this->rka_model->get_nama2($nomor, 'tgl_lpj', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);




        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode in ('PA','KPA') and nip='$ttd2'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = 'Nip. ' . $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = 'Nip. ' . $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }
        $cRet  = " <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"$baris\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>" . strtoupper($jabatan1) . "</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .= " <table border='0' style='font-size:12px' width='100%'>
                        <tr>
                            <td align='left' width='10%' valign=\"top\"> OPD&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='2%' valign=\"top\">:</td>
                            <td align='left' valign=\"top\"> " . $cskpd . " " . $this->tukd_model->get_nama($cskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . " </td>
                        </tr>
                        <tr>
                            <td align='left' valign=\"top\">PERIODE&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' valign=\"top\">:</td>
                            <td align='left' valign=\"top\">" . $this->tukd_model->tanggal_format_indonesia($lctgl1) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($lctgl2) . "</td>
                        </tr>
                   </table>             
                ";

        $cRet .= " <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>KODE REKENING</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>URAIAN</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>4</b></td>
                    </tr>
                    </THEAD>
                ";

        if ($jns == '0') {
            $sql = "SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_program,nm_program,kd_skpd FROM trskpd WHERE jns_ang='$jns_ang' GROUP BY kd_program,nm_program,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
                        UNION ALL

                        SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_kegiatan,nm_kegiatan,kd_skpd FROM trskpd WHERE jns_ang='$jns_ang' GROUP BY kd_kegiatan,nm_kegiatan,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,12) =b.kd_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,12), b.nm_kegiatan

                        UNION ALL
                        SELECT 2 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd' AND b.jns_ang='$jns_ang'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        UNION ALL
                        SELECT 3 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, b.nm_rek2 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
                        WHERE no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), b.nm_rek2
                        UNION ALL
                        SELECT 4 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,4) as kode, b.nm_rek3 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3
                        WHERE no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,4), b.nm_rek3
                        UNION ALL
                        
                        SELECT 5 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,6) as kode, b.nm_rek4 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4
                        WHERE no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,6), b.nm_rek4
                        UNION ALL

                        SELECT 6 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,8) as kode, b.nm_rek5 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5
                        WHERE no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,8), b.nm_rek5
                        UNION ALL
                        SELECT 7 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, nm_rek6 as uraian, SUM(nilai) as nilai FROM trlpj
                        WHERE no_lpj='$nomor' AND kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
                        ORDER BY kode";
            $query1 = $this->db->query($sql);
            $total = 0;
            $i = 0;
            foreach ($query1->result() as $row) {
                $kode = $row->kode;
                $urut = $row->urut;
                $uraian = $row->uraian;
                $nilai  = $row->nilai;

                if ($urut == 1) {
                    $i = $i + 1;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><i><b>$i</b></i></td>
                                    <td valign='top' align='left' ><i><b>$kode</b></i></td>
                                    <td valign='top' align='left' ><i><b>$uraian</b></i></td>
                                    <td valign='top' align='right'><i><b>" . number_format($nilai, "2", ",", ".") . "</b></i></td>
                                </tr>";
                } else if ($urut == 2) {
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
                                </tr>";
                } else if ($urut == 7) {
                    $total = $total + $nilai;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right'>" . number_format($nilai, "2", ",", ".") . "</td>
                                </tr>";
                } else {
                    $cRet .= "<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >" . number_format($nilai, "2", ",", ".") . "</td>
                                </tr>";
                }
            }
        } else {
            $sql = "SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_program,nm_program,kd_skpd FROM trskpd WHERE jns_ang='$jns_ang' GROUP BY kd_program,nm_program,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
                        UNION ALL
                        SELECT 2 as urut, LEFT(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_kegiatan,nm_kegiatan,kd_skpd FROM trskpd WHERE jns_ang='$jns_ang' GROUP BY kd_kegiatan,nm_kegiatan,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,12) =b.kd_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,12), b.nm_kegiatan
                        UNION ALL
                        SELECT 3 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE no_lpj='$nomor' AND a.kd_bp_skpd='$cskpd' AND b.jns_ang='$jns_ang'
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        ORDER BY kode";
            $query1 = $this->db->query($sql);
            $total = 0;
            $i = 0;
            foreach ($query1->result() as $row) {
                $kode = $row->kode;
                $urut = $row->urut;
                $uraian = $row->uraian;
                $nilai  = $row->nilai;

                if ($urut == 1) {
                    $total = $total + $nilai;
                    $i = $i + 1;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><b>$i</b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
                                </tr>";
                } else if ($urut == 2) {
                    $i = $i + 1;
                    $cRet .= "<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
                                </tr>";
                } else {

                    $cRet .= "<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >" . number_format($nilai, "2", ",", ".") . "</td>
                                </tr>";
                }
            }
        }


        $sqlp = " SELECT SUM(a.nilai) AS nilai FROM trdspp a LEFT JOIN trhsp2d b ON b.no_spp=a.no_spp  
                          WHERE b.kd_skpd='$cskpd' AND (b.jns_spp=1)";
        $queryp = $this->db->query($sqlp);
        foreach ($queryp->result_array() as $nlx) {
            $persediaan = $nlx["nilai"];
        }

        $cRet .= "
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>" . number_format($total, "2", ",", ".") . "</b></td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Awal Periode</b></td>
                            <td align='right' ><b>" . number_format($persediaan, "2", ",", ".") . "</b></td>
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Akhir Periode</b></td>
                            <td align='right' ><b>" . number_format($persediaan - $total, "2", ",", ".") . "</b></td>
                        </tr>
                        </tr>
                        ";


        $cRet .= "</table><p>";
        //.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .= " <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Mengetahui <br> $jabatan    </td>
                        <td valign='top' align='center' width='50%'>$daerah, " . $this->tukd_model->tanggal_format_indonesia($lctglspp) . " <br> $jabatan1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'><b><u>$nama1</u></b><br>$pangkat1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$nip</td>
                        <td align='center' width='50%'>$nip1</td>
                    </tr>
                  </table>
                ";

        $data['prev'] = $cRet;

        switch ($ctk) {
            case 0;
                echo ("<title> LPJ UP</title>");
                echo $cRet;
                break;
            case 1;
                $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
                //$this->_mpdf('',$cRet,10,10,10,'0',0,'');
                break;
        }
    }


    function cetaklpjup_ag_rinci()
    {

        $cskpd  = $this->uri->segment(4);
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($cskpd);
        $ttd1   = str_replace('a', ' ', $this->uri->segment(3));
        $ttd2   = str_replace('a', ' ', $this->uri->segment(6));
        $ctk =   $this->uri->segment(5);
        $nomor   = str_replace('abcdefghij', '/', $this->uri->segment(7));
        $nomor   = str_replace('123456789', ' ', $nomor);
        $kegiatan =   $this->uri->segment(8);
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);

        $lctgl1 = $this->rka_model->get_nama2($nomor, 'tgl_awal', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);
        $lctgl2 = $this->rka_model->get_nama2($nomor, 'tgl_akhir', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);
        $lctglspp = $this->rka_model->get_nama2($nomor, 'tgl_lpj', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);
        /*
        */

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode in ('PA','KPA') and nip='$ttd2'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = 'Nip. ' . $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = 'Nip. ' . $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }
        $cRet  = " <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>" . strtoupper($jabatan1) . "</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .= " <table border='0' style='font-size:12px' width='100%'>
                        <tr>
                            <td align='left' width='15%'>OPD&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='3%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' width='82%'> " . $cskpd . " " . $this->tukd_model->get_nama($cskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . " </td>
                        </tr>
                        <tr>
                            <td align='left' >Periode&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' >:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' >" . $this->tukd_model->tanggal_format_indonesia($lctgl1) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($lctgl2) . "</td>
                        </tr>
                        <tr>
                            <td align='left' >Sub Kegiatan&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' >:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' >$kegiatan - " . $this->tukd_model->get_nama($kegiatan, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan') . "</td>
                        </tr>
                        <tr>
                            <td align='left' >&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' >&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' ></td>
                        </tr>
                   </table>             
                ";

        $cRet .= " <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='28%'><b>KODE REKENING</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='49%'><b>URAIAN</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='18%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' ><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' ><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' ><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' ><b>4</b></td>
                    </tr>
                    </THEAD>
                ";


        $sql = "SELECT 1 as urut, a.kd_sub_kegiatan as kode, a.kd_sub_kegiatan as rek, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        ,'' [tgl_bukti],0 [no_bukti]
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND b.jns_ang='$jns_ang'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY a.kd_sub_kegiatan, b.nm_kegiatan
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, LEFT(a.kd_rek6,2) as rek,  nm_rek2 as uraian, SUM(nilai) as nilai,
                        '' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), nm_rek2
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,4) as kode, LEFT(a.kd_rek6,4) as rek,  nm_rek3 as uraian, SUM(nilai) as nilai,
                        '' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,4), nm_rek3
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,6) as kode, LEFT(a.kd_rek6,6) as rek,  nm_rek4 as uraian, SUM(nilai) as nilai 
                        ,'' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,6), nm_rek4
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,8) as kode, LEFT(a.kd_rek6,8) as rek,  nm_rek5 as uraian, SUM(nilai) as nilai 
                        ,'' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,8), nm_rek5
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, kd_rek6 as rek,  nm_rek6 as uraian, SUM(nilai) as nilai
                        ,'' [tgl_bukti],0 [no_bukti]
                        FROM trlpj a
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
                        UNION ALL
                        SELECT 3 as urut, a.kd_sub_kegiatan+'.'+a.kd_rek6+'.1' as kode,'' as rek, c.ket+' \\ No BKU: '+a.no_bukti as uraian, sum(a.nilai) as nilai,
                        c.tgl_bukti,a.no_bukti 
                        FROM trlpj a 
                        INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE a.no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY a.kd_sub_kegiatan, a.kd_rek6,nm_rek6,a.no_bukti, ket,tgl_bukti
                        ORDER BY kode,tgl_bukti,no_bukti    ";
        $query1 = $this->db->query($sql);
        $total = 0;
        $i = 0;
        foreach ($query1->result() as $row) {
            $kode = $row->kode;
            $rek = $row->rek;
            $urut = $row->urut;
            $uraian = $row->uraian;
            $nilai  = $row->nilai;

            if ($urut == 1) {
                $i = $i + 1;
                $cRet .= "<tr>
                                    <td valign='top' align='center' ><i><b>$i</b></i></td>
                                    <td valign='top' align='left' ><i><b>$kode</b></i></td>
                                    <td valign='top' align='left' ><i><b>$uraian</b></i></td>
                                    <td valign='top' align='right'><i><b>" . number_format($nilai, "2", ",", ".") . "</b></i></td>
                                </tr>";
            } else if ($urut == 2) {
                $cRet .= "<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
                                </tr>";
            } else {
                $total = $total + $nilai;
                $cRet .= "<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$rek</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >" . $this->support->rp_minus($nilai) . "</td>
                                </tr>";
            }
        }


        $cRet .= "
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>" . number_format($total, "2", ",", ".") . "</b></td>
                        </tr>                   
                        
                        ";


        $cRet .= "</table><p>";
        //.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .= " <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Mengetahui <br> $jabatan    </td>
                        <td valign='top' align='center' width='50%'>$daerah, " . $this->tukd_model->tanggal_format_indonesia($lctglspp) . " <br> $jabatan1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'><b><u>$nama1</u></b><br>$pangkat1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$nip</td>
                        <td align='center' width='50%'>$nip1</td>
                    </tr>
                  </table>
                ";

        $data['prev'] = $cRet;

        switch ($ctk) {
            case 0;
                echo ("<title> LPJ UP</title>");
                echo $cRet;
                break;
            case 1;
                $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
                //$this->_mpdf('',$cRet,10,10,10,'0',0,'');
                break;
        }
    }


    //REKAP PER UNIT


    function cetaklpjup_rekap()
    {

        $cskpd  = $this->uri->segment(4);
        $ttd1   = str_replace('a', ' ', $this->uri->segment(3));
        $ttd2   = str_replace('a', ' ', $this->uri->segment(6));
        $ctk =   $this->uri->segment(5);
        $nomor1   = str_replace('abcdefghij', '/', $this->uri->segment(7));
        $nomor   = str_replace('123456789', ' ', $nomor1);
        $jns =   $this->uri->segment(8);
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);
        $lctgl1 = $this->rka_model->get_nama2($nomor, 'tgl_awal', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);
        $lctgl2 = $this->rka_model->get_nama2($nomor, 'tgl_akhir', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);
        $lctglspp = $this->rka_model->get_nama2($nomor, 'tgl_lpj', 'trhlpj', 'no_lpj', 'kd_skpd', $cskpd);




        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode in ('PA','KPA') and nip='$ttd2'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = 'Nip. ' . $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = 'Nip. ' . $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }
        $cRet  = " <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>" . strtoupper($jabatan1) . "</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .= " <table border='0' style='font-size:12px' width='100%'>
                        <tr>
                            <td align='left' width='10%' valign=\"top\"> SKPD&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='2%' valign=\"top\">:</td>
                            <td align='left' valign=\"top\"> " . $cskpd . " " . $this->tukd_model->get_nama($cskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . " </td>
                        </tr>
                        <tr>
                            <td align='left' valign=\"top\">PERIODE&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' valign=\"top\">:</td>
                            <td align='left' valign=\"top\">" . $this->tukd_model->tanggal_format_indonesia($lctgl1) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($lctgl2) . "</td>
                        </tr>
                   </table>             
                ";

        $cRet .= " <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='10%'><b>KODE SKPD/UNIT</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='60%'><b>NAMA SKPD/UNIT</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='25%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='60%'><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='25%'><b>4</b></td>
                    </tr>
                    </THEAD>
                ";


        $sql = "SELECT kd_skpd as kode, (select nm_skpd from ms_skpd z where z.kd_skpd=a.kd_skpd )as nama, SUM(a.nilai) as nilai FROM trlpj a  WHERE a.no_lpj='$nomor' and kd_bp_skpd='$cskpd' GROUP BY kd_skpd ORDER BY kode";
        $query1 = $this->db->query($sql);
        $total = 0;
        $i = 0;
        foreach ($query1->result() as $row) {
            $kode = $row->kode;
            $uraian = $row->nama;
            $nilai  = $row->nilai;
            $i = $i + 1;

            $total = $total + $nilai;
            $cRet .= "<tr>
                                    <td valign='top' align='center' >$i</td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >" . number_format($nilai, "2", ",", ".") . "</td>
                                </tr>";
        }



        $sqlp = " SELECT SUM(a.nilai) AS nilai FROM trdspp a LEFT JOIN trhsp2d b ON b.no_spp=a.no_spp  
                          WHERE b.kd_skpd='$cskpd' AND (b.jns_spp=1)";
        $queryp = $this->db->query($sqlp);
        foreach ($queryp->result_array() as $nlx) {
            $persediaan = $nlx["nilai"];
        }

        $cRet .= "
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>" . number_format($total, "2", ",", ".") . "</b></td>
                        </tr>
                        ";


        $cRet .= "</table><p>";
        //.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .= " <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Mengetahui <br> $jabatan    </td>
                        <td valign='top' align='center' width='50%'>$daerah, " . $this->tukd_model->tanggal_format_indonesia($lctglspp) . " <br> $jabatan1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'><b><u>$nama1</u></b><br>$pangkat1</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$nip</td>
                        <td align='center' width='50%'>$nip1</td>
                    </tr>
                  </table>
                ";

        $data['prev'] = $cRet;

        switch ($ctk) {
            case 0;
                echo ("<title> LPJ UP</title>");
                echo $cRet;
                break;
            case 1;
                $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
                //$this->_mpdf('',$cRet,10,10,10,'0',0,'');
                break;
        }
    }
}
