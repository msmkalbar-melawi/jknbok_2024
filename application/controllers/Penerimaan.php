<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Penerimaan extends CI_Controller
{


    public $ppkd1 = "4.02.02.01";
    public $ppkd2 = "4.02.02.02";

    function __contruct()
    {
        parent::__construct();
    }

    //START PENERIMAAN
    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }

    function penerimaan_piutang()
    {
        $data['page_title'] = 'INPUT PENERIMAAN PIUTANG';
        $this->template->set('title', 'INPUT PENERIMAAN PUTANG');
        $this->template->load('template', 'tukd/pendapatan/penerimaan_piutang', $data);
    }

    function penerimaan_skpd()
    {
        $data['page_title'] = 'INPUT PENERIMAAN';
        $this->template->set('title', 'INPUT PENERIMAAN');
        $this->template->load('template', 'tukd/pendapatan/penerimaan', $data);
    }

    function penyetoran()
    {
        $data['page_title'] = 'INPUT STS';
        $this->template->set('title', 'INPUT STS');
        $this->template->load('template', 'tukd/pendapatan/sts', $data);
    }

    function penyetoran_piutang()
    {
        $data['page_title'] = 'INPUT STS';
        $this->template->set('title', 'INPUT STS');
        $this->template->load('template', 'tukd/pendapatan/sts_tlalu', $data);
    }

    function load_terima_tl()
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
            $where = " AND no_terima LIKE '%$kriteria%' OR tgl_terima LIKE '%$kriteria%'";
        }

        $sql = "SELECT count(*) as total from tr_terima WHERE kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";


        $sql = "
        SELECT top $rows no_terima,no_tetap,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket,nilai, kd_rek6,kd_rek_lo,kd_sub_kegiatan,sts_tetap from tr_terima WHERE kd_skpd='$skpd' AND jenis='2' 
        $where AND no_terima NOT IN (SELECT TOP $offset no_terima FROM tr_terima WHERE kd_skpd='$skpd' $where ORDER BY tgl_terima,no_terima ) ORDER BY tgl_terima,no_terima ";

        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_terima' => $resulte['no_terima'],
                'no_tetap' => $resulte['no_tetap'],
                'tgl_terima' => $resulte['tgl_terima'],
                'kd_skpd' => $resulte['kd_skpd'],
                'keterangan' => $resulte['ket'],
                'nilai' => number_format($resulte['nilai']),
                'kd_rek6' => $resulte['kd_rek6'],
                'kd_rek' => $resulte['kd_rek_lo'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'tgl_tetap' => $resulte['tgl_tetap'],
                'sts_tetap' => $resulte['sts_tetap']
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


    function ambil_rek_tetap()
    {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($lckdskpd);
        $sql = "SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM 
        trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 
        where a.kd_skpd = '$lckdskpd' and left(a.kd_rek6,1)='4' AND a.jns_ang='$jns_ang' and 
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

    function ambil_rek()
    {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
        $lcgiat = $this->uri->segment(4);
        $lcfilt = $this->uri->segment(5);
        $lc = '';
        if ($lcfilt != '') {
            $lcfilt = str_replace('A', "'", $lcfilt);
            $lcfilt = str_replace('B', ",", $lcfilt);
            $lc = " and a.kd_rek6 not in ($lcfilt)";
        }


        echo  $sql = "SELECT a.kd_rek6,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=a.kd_rek6) AS nm_rek FROM 
            trdrka a where a.kd_skpd = '$lckdskpd' and a.kd_sub_kegiatan = '$lcgiat' and 
            upper(a.kd_rek6) like upper('%$lccr%') $lc";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek' => $resulte['nm_rek']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_no_tetap()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "where kd_skpd='$kd_skpd' ";
        if ($kriteria <> '') {
            $where = "where kd_skpd='$kd_skpd' AND (upper(no_tetap) like upper('%$kriteria%') or tgl_tetap like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%')) and ";
        }

        // $where2 ='AND no_tetap not in(select isnull(no_tetap,'') from tr_terima)';

        //$sql = "SELECT * from tr_tetap $where order by no_tetap";
        $sql = "SELECT TOP 500 no_tetap, jenis,tgl_tetap, kd_skpd, keterangan, nilai, kd_rek6, kd_rek_lo,
                
                (SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek FROM tr_tetap $where 
                AND no_tetap not in(select isnull(no_tetap,'') from tr_terima)
                UNION ALL
                SELECT no_tetap,jenis,tgl_tetap,kd_skpd,keterangan,ISNULL(nilai,0)-ISNULL(nilai_terima,0) as nilai,kd_rek6,kd_rek_lo,a.nm_rek 
                FROM 
                (SELECT *,(SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek FROM tr_tetap $where )a
                LEFT JOIN
                (SELECT no_tetap as tetap,ISNULL(SUM(nilai),0) as nilai_terima from tr_terima $where GROUP BY no_tetap)b
                ON a.no_tetap=b.tetap
                WHERE nilai !=nilai_terima
                order by no_tetap";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id'            => $ii,
                'no_tetap'      => $resulte['no_tetap'],
                'tgl_tetap'     => $resulte['tgl_tetap'],
                'kd_skpd'       => $resulte['kd_skpd'],
                'keterangan'    => $resulte['keterangan'],
                'nilai'         => $resulte['nilai'],
                'kd_rek6'       => $resulte['kd_rek6'],
                'jenis'         => $resulte['jenis'],
                'nm_rek6'       => $resulte['nm_rek'],
                'kd_rek_lo'     => $resulte['kd_rek_lo']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function cek_simpan()
    {
        $nomor      = $this->input->post('no');
        $tabel      = $this->input->post('tabel');
        $field      = $this->input->post('field');
        $field2     = $this->input->post('field2');
        $tabel2     = $this->input->post('tabel2');
        $kd_skpd    = $this->session->userdata('kdskpd');
        if ($field2 == '') {
            $hasil = $this->db->query("SELECT count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else {
            $hasil = $this->db->query("SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
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

    //------------------------------------------ 10 September 2021 ---------------------------------------------------------------
    function simpan_terima_ag()
    {
        $this->db->trans_start();
        $no_terima = $this->input->post('no_terima');
        $kd_skpd   = $this->input->post('kd_skpd');
        //----------------------------------------------------------------------------------------------------------------------------
        $this->db->insert('tr_terima', array(
            'no_terima' => $this->input->post('no_terima'),
            'tgl_terima' => $this->input->post('tgl_terima'),
            'no_tetap' => $this->input->post('no_tetap'),
            'tgl_tetap' => $this->input->post('tgl_tetap'),
            'sts_tetap' => $this->input->post('sts_tetap'),
            'kd_skpd' => $this->input->post('kd_skpd'),
            'kd_sub_kegiatan' => $this->input->post('kd_sub_kegiatan'),
            'kd_rek6' => $this->input->post('kd_rek6'),
            'kd_rek_lo' => $this->input->post('kd_rek_lo'),
            'nilai' => $this->input->post('nilai'),
            'keterangan' => $this->input->post('keterangan'),
            'jenis' => $this->input->post('jenis'),
            'sumber' => $this->input->post('sumber'),
            'status_setor' => $this->input->post('status_setorr'),
            'jns_pembayaran' => $this->input->post('jns_pem'),
            'jns_pajak' => $this->input->post('pajakkk'),
        ));
        //----------------------------------------------------------------------------------------------------------------------------
        if ($this->input->post('status_setorr') == 'Tanpa Setor') {
            $no_kas_terakhir = $this->db->query("SELECT CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor FROM (
                SELECT no_kas nomor,'Terima STS' ket FROM trhkasin_pkd WHERE ISNUMERIC(no_kas)=1
                UNION ALL
                SELECT no_kas nomor,'Terima STS' ket FROM trhrestitusi WHERE ISNUMERIC(no_kas)=1
                UNION ALL
                SELECT nomor,'Terima non SP2D' ket FROM penerimaan_non_sp2d WHERE ISNUMERIC(nomor)=1
                UNION ALL
                SELECT nomor,'keluar non SP2D' ket FROM pengeluaran_non_sp2d WHERE ISNUMERIC(nomor)=1
                UNION ALL
                SELECT no,'koreksi' ket FROM trkasout_ppkd WHERE ISNUMERIC(no)=1
                ) z")->row()->nomor;
            //----------------------------------------------------------------------------------------------------------------------------
            $this->db->insert('trhkasin_pkd', array(
                //
                'no_sts'            => $no_terima,
                'kd_skpd'           => $this->input->post('kd_skpd'),
                'tgl_sts'           => $this->input->post('tgl_terima'),
                'keterangan'        => $this->input->post('keterangan'),
                'total'             => $this->input->post('nilai'),
                'kd_sub_kegiatan'   => $this->input->post('kd_sub_kegiatan'),
                'jns_trans'         => '4',
                'pot_khusus'        => '0',
            ));
            //----------------------------------------------------------------------------------------------------------------------------
            $this->db->insert('trdkasin_pkd', array(
                //
                'no_sts'            => $no_terima,
                'kd_skpd'           => $this->input->post('kd_skpd'),
                'kd_rek6'           => $this->input->post('kd_rek6'),
                'rupiah'            => $this->input->post('nilai'),
                'kd_sub_kegiatan'   => $this->input->post('kd_sub_kegiatan'),
                'no_terima'         => $this->input->post('no_terima'),
                'sumber'            => $this->input->post('sumber'),
            ));
            //----------------------------------------------------------------------------------------------------------------------------
            $sql = "update tr_terima set kunci=1 where no_terima='$no_terima' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);
        }
        //----------------------------------------------------------------------------------------------------------------------------
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $is_success = false;
            $message = 'Tidak dapat menambahkan data penerimaan';
        } else {
            $is_success = true;
            $message = 'Penerimaan telah berhasil disimpan';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'is_success' => $is_success,
                'message' => $message,
            )));
    }
    //----------------------------------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------------------------------
    function simpan_terima_ag_lama()
    {
        $kd_skpd        = $this->input->post('kd_skpd');
        $no_terima      = $this->input->post('no_terima');
        $tabel          = $this->input->post('tabel');
        $lckolom        = $this->input->post('kolom');
        $lcnilai        = $this->input->post('nilai1');
        $cid            = $this->input->post('cid');
        $lcid           = $this->input->post('lcid');
        //-----------------------------------------------------------------------------------------------------------------------------
        $sql            = "insert into $tabel $lckolom values $lcnilai";
        $asg            = $this->db->query($sql);

        if ($this->input->post('status_setorr') == 'Tanpa Setor') {
            // $no_kas_terakhir = $this->db->query("SELECT CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor FROM (
            //     SELECT no_kas nomor,'Terima STS' ket FROM trhkasin_ppkd WHERE ISNUMERIC(no_kas)=1
            //     UNION ALL
            //     SELECT no_kas nomor,'Terima STS' ket FROM trhrestitusi WHERE ISNUMERIC(no_kas)=1
            //     UNION ALL
            //     SELECT nomor,'Terima non SP2D' ket FROM penerimaan_non_sp2d WHERE ISNUMERIC(nomor)=1
            //     UNION ALL
            //     SELECT nomor,'keluar non SP2D' ket FROM pengeluaran_non_sp2d WHERE ISNUMERIC(nomor)=1
            //     UNION ALL
            //     SELECT no,'koreksi' ket FROM trkasout_ppkd WHERE ISNUMERIC(no)=1
            //     ) z")->row()->nomor;
            //----------------------------------------------------------------------------------------------------------------------------
            $this->db->insert('trhkasin_pkd', array(
                //
                'no_sts'            => $no_terima,
                'kd_skpd'           => $this->input->post('kd_skpd'),
                'tgl_sts'           => $this->input->post('tgl_terima'),
                'keterangan'        => $this->input->post('keterangan'),
                'total'             => $this->input->post('nilai'),
                'kd_sub_kegiatan'   => $this->input->post('kd_sub_kegiatan'),
                'no_terima'         => $this->input->post('no_terima'),
                'jns_trans'         => '4',
                'pot_khusus'        => '0',
            ));
            //----------------------------------------------------------------------------------------------------------------------------
            $this->db->insert('trdkasin_pkd', array(
                //
                'no_sts'            => $no_terima,
                'kd_skpd'           => $this->input->post('kd_skpd'),
                'kd_rek6'           => $this->input->post('kd_rek6'),
                'rupiah'            => $this->input->post('nilai'),
                'kd_sub_kegiatan'   => $this->input->post('kd_sub_kegiatan'),
                'no_terima'         => $this->input->post('no_terima'),
                'sumber'            => $this->input->post('sumber'),
            ));
            //----------------------------------------------------------------------------------------------------------------------------
            $sql = "update tr_terima set kunci=1 where no_terima='$no_terima' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);
        }
        if ($asg > 0) {
            echo '2';
        } else {
            echo '0';
            exit();
        }
    }

    function update_terima_ag()
    {
        $no_terima = $this->input->post('no_terima');
        $nohide         = $this->input->post('no_hide');
        $skpd           = $this->session->userdata('kdskpd');
        $tgl_terima     = $this->input->post('tgl_terima');
        $no_tetap       = $this->input->post('no_tetap');
        $tgl_tetap      = $this->input->post('tgl_tetap');

        $sts_tetap = $this->input->post('sts_tetap');
        // $skpd = $this->input->post('kd_skpd');
        $sub_kegiatan   = $this->input->post('kd_sub_kegiatan');
        $rek6           = $this->input->post('kd_rek6');
        $rek_lo         = $this->input->post('kd_rek_lo');
        $nilai          = $this->input->post('nilai');
        $ket            = $this->input->post('keterangan');
        $jns            = $this->input->post('jenis');
        $sumber         = $this->input->post('sumber');
        $status         = $this->input->post('status_setorr');
        $jns_pem        = $this->input->post('jns_pem');
        $pajak          = $this->input->post('pajakkk');

        $sql = "UPDATE tr_terima set no_terima='$no_terima',
                        tgl_terima='$tgl_terima',
                        no_tetap ='$no_tetap',
                        tgl_tetap ='$tgl_tetap',
                        kd_skpd ='$skpd',
                        kd_sub_kegiatan='$sub_kegiatan',
                        kd_rek6 ='$rek6',
                        kd_rek_lo='$rek_lo',
                        nilai='$nilai',
                        keterangan='$ket',
                        sts_tetap='$sts_tetap',
                        jenis='$jns',
                        status_setor='$status',
                        jns_pembayaran='$jns_pem',
                        sumber ='$sumber',
                        jns_pajak='$pajak' WHERE kd_skpd='$skpd' AND no_terima='$nohide'";
        $asg = $this->db->query($sql);
        echo json_encode($asg);

        //----------------------------------------------------------------------------------------------------------------------------
        if ($this->input->post('status_setorr') == 'Tanpa Setor') {
            // $no_kas_terakhir = $this->db->query("SELECT CASE WHEN MAX(nomor+1) IS NULL THEN 1 ELSE MAX(nomor+1) END AS nomor FROM (
            //     SELECT no_kas nomor,'Terima STS' ket FROM trhkasin_pkd WHERE ISNUMERIC(no_kas)=1
            //     UNION ALL
            //     SELECT no_kas nomor,'Terima STS' ket FROM trhrestitusi WHERE ISNUMERIC(no_kas)=1
            //     UNION ALL
            //     SELECT nomor,'Terima non SP2D' ket FROM penerimaan_non_sp2d WHERE ISNUMERIC(nomor)=1
            //     UNION ALL
            //     SELECT nomor,'keluar non SP2D' ket FROM pengeluaran_non_sp2d WHERE ISNUMERIC(nomor)=1
            //     UNION ALL
            //     SELECT no,'koreksi' ket FROM trkasout_ppkd WHERE ISNUMERIC(no)=1
            //     ) z")->row()->nomor;
            //----------------------------------------------------------------------------------------------------------------------------
            $this->db->insert('trhkasin_pkd', array(
                //
                'no_sts'            => $no_terima,
                'kd_skpd'           => $this->input->post('kd_skpd'),
                'tgl_sts'           => $this->input->post('tgl_terima'),
                'keterangan'        => $this->input->post('keterangan'),
                'total'             => $this->input->post('nilai'),
                'kd_sub_kegiatan'   => $this->input->post('kd_sub_kegiatan'),
                'jns_trans'         => '4',
                'pot_khusus'        => '0',
            ));
            //----------------------------------------------------------------------------------------------------------------------------
            $this->db->insert('trdkasin_pkd', array(
                //
                'no_sts'            => $no_terima,
                'kd_skpd'           => $this->input->post('kd_skpd'),
                'kd_rek6'           => $this->input->post('kd_rek6'),
                'rupiah'            => $this->input->post('nilai'),
                'kd_sub_kegiatan'   => $this->input->post('kd_sub_kegiatan'),
                'no_terima'         => $this->input->post('no_terima'),
                'sumber'            => $this->input->post('sumber'),
            ));
            //----------------------------------------------------------------------------------------------------------------------------
            $sql = "update tr_terima set kunci=1 where no_terima='$no_terima' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);
        }
        //----------------------------------------------------------------------------------------------------------------------------
    }



    function load_terima()
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
            $where = " AND (no_terima LIKE '%$kriteria%' OR tgl_terima LIKE '%$kriteria%' OR keterangan LIKE '%$kriteria%' AND kd_skpd='$skpd') ";
        }
        $sql = "SELECT count(*) as total from tr_terima WHERE kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        $spjbulan = $this->tukd_model->cek_status_spj_pend($skpd);

        $sql = " SELECT top $rows no_terima,no_tetap,status_setor,jns_pajak,
        (select TOP 1 jenis from tr_tetap a where no_tetap=a.no_tetap and kd_rek6=a.kd_rek6 and a.kd_skpd=kd_skpd)as jenis
        ,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket, sumber, jns_pembayaran,
        nilai, kd_rek6,kd_rek_lo,kd_sub_kegiatan,sts_tetap,(CASE WHEN month(tgl_terima)<='$spjbulan' THEN 1 ELSE 0 END) ketspj,user_name,kunci FROM tr_terima
        WHERE kd_skpd='$skpd' AND (jenis= '1' or jenis is null)       
        $where AND no_terima NOT IN (SELECT TOP $offset no_terima 
        FROM tr_terima WHERE kd_skpd='$skpd' $where ORDER BY tgl_terima,no_terima) 
        ORDER BY tgl_terima,no_terima ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['ketspj'] == '1') {
                $s = '&#10004';
            } else {
                $s = '&#10008';
            }


            $row[] = array(
                'id'                => $ii,
                'no_terima'         => $resulte['no_terima'],
                'no_tetap'          => $resulte['no_tetap'],
                'tgl_terima'        => $resulte['tgl_terima'],
                'kd_skpd'           => $resulte['kd_skpd'],
                'keterangan'        => $resulte['ket'],
                'nilai'             => number_format($resulte['nilai'], 2, '.', ','),
                'kd_rek6'           => $resulte['kd_rek6'],
                'kd_rek'            => $resulte['kd_rek_lo'],
                'jenis'            => $resulte['jenis'],
                // Kam di Eleng2 kamm !!!!!! [1]
                'status_setor'      => $resulte['status_setor'],
                'jns_pajak'         => $resulte['jns_pajak'],
                'sumber'            => $resulte['sumber'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'tgl_tetap'         => $resulte['tgl_tetap'],
                'sts_tetap'         => $resulte['sts_tetap'],
                'spj'               => $resulte['ketspj'],
                'user_nm'           => $resulte['user_name'],
                'kunci'             => $resulte['kunci'],
                'jns_pembayaran'    => $resulte['jns_pembayaran'],
                'simbol'            => $s
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    //Start
    function load_terima_lalu()
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
            $where = " AND (no_terima LIKE '%$kriteria%' OR tgl_terima LIKE '%$kriteria%' OR keterangan LIKE '%$kriteria%' AND kd_skpd='$skpd') ";
        }
        $sql = "SELECT count(*) as total from tr_terima WHERE kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        $spjbulan = $this->tukd_model->cek_status_spj_pend($skpd);

        $sql = " SELECT top $rows no_terima,no_tetap,status_setor,
        (select TOP 1 jenis from tr_tetap a where no_tetap=a.no_tetap and kd_rek6=a.kd_rek6 and a.kd_skpd=kd_skpd)as jenis
        ,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket, sumber, jns_pembayaran,
        nilai, kd_rek6,kd_rek_lo,kd_sub_kegiatan,sts_tetap,(CASE WHEN month(tgl_terima)<='$spjbulan' THEN 1 ELSE 0 END) ketspj,user_name,kunci FROM tr_terima
        WHERE kd_skpd='$skpd' AND (jenis <> '1' or jenis is null)       
        $where AND no_terima NOT IN (SELECT TOP $offset no_terima 
        FROM tr_terima WHERE kd_skpd='$skpd' $where ORDER BY tgl_terima,no_terima) 
        ORDER BY tgl_terima,no_terima ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['ketspj'] == '1') {
                $s = '&#10004';
            } else {
                $s = '&#10008';
            }


            $row[] = array(
                'id'                => $ii,
                'no_terima'         => $resulte['no_terima'],
                'no_tetap'          => $resulte['no_tetap'],
                'tgl_terima'        => $resulte['tgl_terima'],
                'kd_skpd'           => $resulte['kd_skpd'],
                'keterangan'        => $resulte['ket'],
                'nilai'             => number_format($resulte['nilai'], 2, '.', ','),
                'kd_rek6'           => $resulte['kd_rek6'],
                'kd_rek'            => $resulte['kd_rek_lo'],
                'jenis'            => $resulte['jenis'],
                'status_setor'      => $resulte['status_setor'],
                'sumber'            => $resulte['sumber'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'tgl_tetap'         => $resulte['tgl_tetap'],
                'sts_tetap'         => $resulte['sts_tetap'],
                'spj'               => $resulte['ketspj'],
                'user_nm'           => $resulte['user_name'],
                'kunci'             => $resulte['kunci'],
                'jns_pembayaran'    => $resulte['jns_pembayaran'],
                'simbol'            => $s
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }
    //End


    function load_pengirim()
    {
        $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');

        // if(substr($skpd,0,7)=='3.13.01'){
        //     $where = "kd_skpd='$skpd'";
        // }else{
        $where = "LEFT(kd_skpd,17)=LEFT('$skpd',17)";
        // }

        $sql = "SELECT * from ms_pengirim WHERE $where 
                AND (UPPER(kd_pengirim) LIKE UPPER('%$lccr%') OR UPPER(nm_pengirim) LIKE UPPER('%$lccr%')) 
                order by cast(kd_pengirim as int)";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'kd_pengirim'   => $resulte['kd_pengirim'],
                'nm_pengirim'   => $resulte['nm_pengirim'],
                'kd_skpd'       => $resulte['kd_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function hapus_terima()
    {
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');

        $sql    = "delete from tr_terima where no_terima='$nomor' and kd_skpd = '$skpd'";
        $asg    = $this->db->query($sql);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }


    //END PENERIMAAN

    //PENYETORAN

    function load_sts_tl()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = " and (upper(a.no_sts) like upper('%$kriteria%') or a.tgl_sts like '%$kriteria%' or a.kd_skpd like'%$kriteria%' or
            upper(a.keterangan) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='2' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";

        $spjbulan = $this->tukd_model->cek_status_spj_pend($kd_skpd);

        $sql = "SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd from trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='2' 
        $where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd where kd_skpd='$kd_skpd' and jns_trans='2' 
        ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
        ";

        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_sts'            => $resulte['no_sts'],
                'tgl_sts'           => $resulte['tgl_sts'],
                'kd_skpd'           => $resulte['kd_skpd'],
                'keterangan'        => $resulte['keterangan'],
                'total'             =>  number_format($resulte['total']),
                'kd_bank'           => $resulte['kd_bank'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'jns_trans'         => $resulte['jns_trans'],
                'rek_bank'          => $resulte['rek_bank'],
                'no_kas'            => $resulte['no_kas'],
                'tgl_kas'           => $resulte['tgl_kas'],
                'no_cek'            => $resulte['no_cek'],
                'status'            => $resulte['status'],
                'sumber'            => $resulte['sumber'],
                'no_terima'         => $resulte['no_terima'],
                'nm_skpd'           => $resulte['nm_skpd']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_tetap_sts()
    {
        $skpd       = $this->uri->segment(3);
        $kd_rek6    = $this->uri->segment(4);
        $sql        = "SELECT * from tr_terima where kd_skpd = '$skpd' and kd_rek6 = '$kd_rek6' and sts_tetap = '1' order by no_tetap";
        //echo $sql;

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id'         => $ii,
                'no_tetap'   => $resulte['no_terima'],
                'tgl_tetap'  => $resulte['tgl_terima'],
                'kd_skpd'    => $resulte['kd_skpd'],
                'keterangan' => $resulte['keterangan'],
                'nilai'      => number_format($resulte['nilai']),
                'kd_rek6'    => $resulte['kd_rek6']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_sts()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = " and (upper(a.no_sts) like upper('%$kriteria%') or a.tgl_sts like '%$kriteria%' or a.kd_skpd like'%$kriteria%' or
            upper(a.keterangan) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT COUNT(*) as total FROM trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='4' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();
        $spjbulan = $this->tukd_model->cek_status_spj_pend($kd_skpd);
        $sql = "
        SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = a.kd_skpd) AS nm_skpd,
        (CASE WHEN month(a.tgl_sts)<='$spjbulan' THEN 1 ELSE 0 END) ketspj,a.user_name
        FROM trhkasin_pkd a where a.kd_skpd='$kd_skpd' and a.jns_trans='4' 
        $where  AND a.no_sts NOT IN (SELECT top $offset no_sts FROM trhkasin_pkd where kd_skpd='$kd_skpd' and jns_trans='4' ORDER BY tgl_sts, no_sts)order by a.tgl_sts, a.no_sts
        ";

        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['ketspj'] == '1') {
                $s = '&#10004';
            } else {
                $s = '&#10008';
            }
            $row[] = array(
                'id'                => $ii,
                'no_sts'            => $resulte['no_sts'],
                'tgl_sts'           => $resulte['tgl_sts'],
                'kd_skpd'           => $resulte['kd_skpd'],
                'keterangan'        => $resulte['keterangan'],
                'total'             =>  number_format($resulte['total'], 2, '.', ','),
                'kd_bank'           => $resulte['kd_bank'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'jns_trans'         => $resulte['jns_trans'],
                'rek_bank'          => $resulte['rek_bank'],
                'no_kas'            => $resulte['no_kas'],
                'tgl_kas'           => $resulte['tgl_kas'],
                'no_cek'            => $resulte['no_cek'],
                'status'            => $resulte['status'],
                'sumber'            => $resulte['sumber'],
                'no_terima'         => $resulte['no_terima'],
                'nm_skpd'           => $resulte['nm_skpd'],
                'spj'               => $resulte['ketspj'],
                'user_nm'           => $resulte['user_name'],
                'simbol'            => $s
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }
    function load_trskpd1_pend($cskpd = '')
    {
        $lccr = '';
        $lccr = $this->uri->segment(3);
        if (strlen($lccr) == 1) {
            $lcpj = 1;
        } else {
            $lcpj = 2;
        }
        $lcskpd  = $this->session->userdata('kdskpd');
        //$lcskpd = $this->uri->segment(4);
        $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trskpd a 
                    WHERE left(a.jns_kegiatan,$lcpj)='$lccr' and a.kd_skpd = '$lcskpd'";
        //echo $sql;    
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
    }

    function load_trskpd_pend()
    {
        $jenis = $this->input->post('jenis');
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');

        $jns_beban = '';
        $cgiat = '';
        if ($jenis == 4) {
            $jns_beban = "and a.jns_sub_kegiatan='4'";
        } else {
            $jns_beban = "and a.jns_sub_kegiatan='5'";
        }
        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program as nm_program,a.total FROM trskpd_pend a 
                WHERE a.kd_skpd='$cskpd' $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id'          => $ii,
                'kd_kegiatan' => $resulte['kd_kegiatan'],
                'nm_kegiatan' => $resulte['nm_kegiatan'],
                'kd_program'  => $resulte['kd_program'],
                'nm_program'  => $resulte['nm_program'],
                'total'       => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function ambil_rek_t_sts()
    {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);

        $sql = "SELECT DISTINCT a.kd_rek6, (SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6 = a.kd_rek6) AS nm_rek FROM tr_terima a
                WHERE kd_skpd = '$lckdskpd' and upper(a.kd_rek6) like upper('%$lccr%')";


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek'  => $resulte['nm_rek']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_rek_ag2()
    {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
        $lcgiat = $this->uri->segment(4);
        $lcfilt = $this->uri->segment(5);
        $lc = '';
        if ($lcfilt != '') {
            $lcfilt = str_replace('A', "'", $lcfilt);
            $lcfilt = str_replace('B', ",", $lcfilt);
            $lc = " and a.kd_rek5 not in ($lcfilt)";
        }


        $sql = "select kd_rek6,nm_rek6 from trdrka where kd_skpd='$lckdskpd' and kd_sub_kegiatan='$lcgiat'
                    and upper(kd_rek6) like upper('%$lccr%') order by kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                //'no_terima' => $resulte['no_terima'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek'  => $resulte['nm_rek6'],
                //'nilai' => $resulte['nilai']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function hapus_sts()
    {
        $nomor = $this->input->post('no');
        $kd_skpd = $this->session->userdata('kdskpd');

        $sql = "update a set a.kunci=0 
                from tr_terima a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_terima=b.no_terima and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                where a.kd_skpd='$kd_skpd' and b.no_sts='$nomor'";
        $asg = $this->db->query($sql);


        $sql = "delete from trhkasin_pkd where no_sts='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        $sql = "delete from trdkasin_pkd where no_sts='$nomor'  AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        echo '1';
    }


    function simpan_sts_pendapatan_tlalu()
    {

        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        //$nomor_kas       = $this->input->post('lckas');
        //$tgl_kas       = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
        $pengirim    = $this->input->post('pengirim');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
        $sumber      = $this->input->post('sts');
        $sp2d        = $this->input->post('sp2d');
        $jns_cp        = $this->input->post('jns_cp');
        //$no_terima   = $this->input->post('no_terima');  

        $nmskpd      = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
        $last_update = date('d-m-y H:i:s');
        // $last_update = " ";
        $msg = array();

        $tanggal1  = explode('-', $tgl);
        $bulan  = $tanggal1[1];

        $hasil = $this->db->query("SELECT count(kd_skpd) [jumlah] from ms_skpd where jns='2' and kd_skpd = '$skpd' ");
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }

        $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
                                select no_kas nomor,'Terima STS' ket from trhkasin_ppkd where isnumeric(no_kas)=1
                                            UNION ALL
                                            select no_kas nomor,'Terima STS' ket from trhrestitusi where isnumeric(no_kas)=1
                                            UNION ALL
                                            select nomor,'Terima non SP2D' ket from penerimaan_non_sp2d where isnumeric(nomor)=1
                                            UNION ALL
                                            select nomor,'keluar non SP2D' ket from pengeluaran_non_sp2d where isnumeric(nomor)=1
                                            UNION ALL
                                            select no,'koreksi' ket from trkasout_ppkd where isnumeric(no)=1
                                ) z");
        $prvn = $query1->row();
        $num = $prvn->nomor;

        if ($tabel == 'trhkasin_pkd') {

            $sql = "delete from trhkasin_pkd where kd_skpd='$skpd' and no_sts='$nomor'";
            $asg = $this->db->query($sql);


            if ($asg) {
                if ($jnsrek == 5) {
                    $sql = "insert into trhkasin_pkd(no_kas,no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','1','$sp2d','$jns_cp')";

                    //  if($jumlah==0 && $skpd<>'1.02.0.00.0.00.02.0000'){
                    //      $sql = "insert into trhkasin_ppkd(no_kas,no_sts,kd_skpd,tgl_sts,tgl_kas,keterangan,total,kd_bank,kd_sub_kegiatan,
                    //         jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp) 
                    //         values('$num','$nomor','$skpd','$tgl','$tgl_kas','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','1','$sp2d','$jns_cp')";
                    //     $asg = $this->db->query($sql);

                    //     $sql = "update trhkasin_pkd set no_cek=1, status=1 where kd_skpd='$skpd' and no_sts='$nomor'";
                    //     $asg = $this->db->query($sql);
                    //  }
                } else {
                    $sql = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$pengirim','0','$sp2d','$jns_cp')";
                    $asg = $this->db->query($sql);

                    //   if($jumlah==0 && $skpd<>'1.02.0.00.0.00.02.0000'){
                    //      $sql = "insert into trhkasin_ppkd(no_kas,no_sts,kd_skpd,tgl_sts,tgl_kas,keterangan,total,kd_bank,kd_sub_kegiatan,
                    //         jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp) 
                    //         values('$num','$nomor','$skpd','$tgl','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$pengirim','0','$sp2d','$jns_cp')";
                    //     $asg = $this->db->query($sql);

                    //     $sql = "update trhkasin_pkd set no_cek=1, status=1 where kd_skpd='$skpd' and no_sts='$nomor'";
                    //     $asg = $this->db->query($sql);

                    // }

                }




                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg) {
                    $sql = "delete from trdkasin_pkd where no_sts='$nomor' AND kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);
                    if (!($asg)) {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                    } else {
                        $sql = "insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan,no_terima,sumber) values $lcnilaidet";
                        $asg = $this->db->query($sql);

                        $sql = "update a set a.kunci=1 
                                from tr_terima a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_terima=b.no_terima and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                where a.kd_skpd='$skpd' and b.no_sts='$nomor'";
                        $asg = $this->db->query($sql);

                        // if($jumlah==0 && $skpd<>'1.02.0.00.0.00.02.0000'){
                        //     $sql = "insert into trdkasin_ppkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan,no_kas,sumber) values $lcnilaidet";
                        //     $asg = $this->db->query($sql);

                        //     $sql = "update a set a.no_kas=$num 
                        //         from trdkasin_ppkd a inner join trhkasin_ppkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        //         where a.kd_skpd='$skpd' and b.no_sts='$nomor'";
                        //     $asg = $this->db->query($sql);

                        // }

                    }
                }
            }
            echo '2';
        }
    }


    function cek_skpdsamsat()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');

        $hasil = $this->db->query("select count(kd_skpd) [jumlah] from ms_skpd where jns='2' and kd_skpd = '$kd_skpd' ");
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

    //END PENYETORAN
}
