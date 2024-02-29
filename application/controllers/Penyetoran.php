<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Penyetoran extends CI_Controller
{

    public $org_keu = "";
    public $skpd_keu = "";

    function __contruct()
    {
        parent::__construct();
    }

    function penyetoran1()
    {
        $data['page_title'] = 'INPUT PENERIMAAN PIUTANG';
        $this->template->set('title', 'INPUT PENERIMAAN PUTANG');
        $this->template->load('template', 'tukd/pendapatan/penerimaan_piutang', $data);
    }

    function penyetorans()
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

    function rp_minus($nilai)
    {
        if ($nilai < 0) {
            $nilai = $nilai * (-1);
            $nilai = '(' . number_format($nilai, "2", ",", ".") . ')';
        } else {
            $nilai = number_format($nilai, "2", ",", ".");
        }

        return $nilai;
    }

    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }


    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd,a.nm_skpd,b.status_rancang,b.status,b.status_sempurna,b.status_ubah FROM  ms_skpd a LEFT JOIN trhrka b ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'status' => $resulte['status'],
                'status_ubah' => $resulte['status_ubah'],
                'status_rancang' => $resulte['status_rancang'],
                'status_sempurna' => $resulte['status_sempurna']
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

        /*
        $sql = "SELECT ISNULL(MAX(tgl_terima),'2017-01-01') as tgl_terima FROM trhspj_terima_ppkd WHERE cek='1' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res)
        {
         $tgl_terima = $res['tgl_terima'];
        }
        */
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
                'id' => $ii,
                'no_sts' => $resulte['no_sts'],
                'tgl_sts' => $resulte['tgl_sts'],
                'kd_skpd' => $resulte['kd_skpd'],
                'keterangan' => $resulte['keterangan'],
                'total' =>  number_format($resulte['total'], 2, '.', ','),
                'kd_bank' => $resulte['kd_bank'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'jns_trans' => $resulte['jns_trans'],
                'rek_bank' => $resulte['rek_bank'],
                'no_kas' => $resulte['no_kas'],
                'tgl_kas' => $resulte['tgl_kas'],
                'no_cek' => $resulte['no_cek'],
                'status' => $resulte['status'],
                'sumber' => $resulte['sumber'],
                'no_terima' => $resulte['no_terima'],
                'nm_skpd' => $resulte['nm_skpd'],
                'spj' => $resulte['ketspj'],
                'user_nm' => $resulte['user_name'],
                'simbol' => $s
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_tetap_sts()
    {
        //$kriteria = '';
        //$kriteria = $this->input->post('cari');
        $skpd = $this->uri->segment(3);
        $kd_rek6 = $this->uri->segment(4);

        //$where ='';
        //        if ($kriteria <> ''){                               
        //            $where="where kd_skpd = '$skpd' and kd_rek5 = '$kd_rek5' and (upper(no_tetap) like upper('%$kriteria%') or
        //            upper(keterangan) like upper('%$kriteria%'))";            
        //        }
        //        
        $sql = "SELECT * from tr_terima where kd_skpd = '$skpd' and kd_rek6 = '$kd_rek6' and sts_tetap = '1' order by no_tetap";
        //echo $sql;

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_tetap' => $resulte['no_terima'],
                'tgl_tetap' => $resulte['tgl_terima'],
                'kd_skpd' => $resulte['kd_skpd'],
                'keterangan' => $resulte['keterangan'],
                'nilai' => number_format($resulte['nilai']),
                'kd_rek6' => $resulte['kd_rek6']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_rek_tetap()
    {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);

        $sql = "SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek4, a.kd_sub_kegiatan FROM 
        trdrka_pend a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek4 c on left(a.kd_rek6,6)=c.kd_rek4 
        where a.kd_skpd = '$lckdskpd' and left(a.kd_rek6,1)='4' and 
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
                'nm_rek4' => $resulte['nm_rek4'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function list_no_terima($tgl_terima = '')
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $lctgl = $this->uri->segment(3);

        $sql   = "select a.*,b.nm_pengirim from tr_terima a left join ms_pengirim b on a.sumber=b.kd_pengirim where a.kd_skpd='$kd_skpd' 
        AND a.no_terima NOT IN(select ISNULL(no_terima,'') no_terima from trdkasin_pkd where kd_skpd='$kd_skpd') AND 
        a.tgl_terima='$tgl_terima' AND (a.no_terima LIKE '%$lccr%' or a.nilai LIKE '%$lccr%' or b.nm_pengirim LIKE '%$lccr%') ORDER BY b.nm_pengirim,a.tgl_terima,a.kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_terima' => $resulte['no_terima'],
                'tgl_terima' => $resulte['tgl_terima'],
                'kd_rek6' => $resulte['kd_rek6'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nilai' => number_format($resulte['nilai'], 2, '.', ','),
                'keterangan' => $resulte['keterangan'],
                'sumber' => $resulte['sumber'],
                'nm_pengirim' => $resulte['nm_pengirim']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
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


        $sql = "SELECT a.kd_rek6,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=a.kd_rek6) AS nm_rek FROM 
            trdrka a where a.kd_skpd = '$lckdskpd' and a.kd_sub_kegiatan = '$lcgiat' and 
            upper(a.kd_rek6) like upper('%$lccr%') $lc";



        //echo $sql;

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

    function load_trskpd_pend()
    {
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $cskpd  = $this->input->post('kd');
        $data1   = $this->cek_anggaran_model->cek_anggaran($cskpd);

        $jns_beban = '';
        $cgiat = '';
        if ($jenis == 4) {
            $jns_beban = "and a.jns_kegiatan='4'";
        } else {
            $jns_beban = "and a.jns_kegiatan='52'";
        }
        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program as nm_program,a.total FROM trskpd a 
                WHERE a.jns_ang='$data1' AND a.kd_skpd='$cskpd' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";
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
        $data1    = $this->cek_anggaran_model->cek_anggaran($lcskpd);
        //$lcskpd = $this->uri->segment(4);
        $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trskpd a 
                    WHERE a.jns_ang='$data1' AND left(a.jns_kegiatan,$lcpj)='$lccr' and a.kd_skpd = '$lcskpd'";
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
                'nm_rek' => $resulte['nm_rek']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_dsts()
    {
        $lcskpd  = $this->session->userdata('kdskpd');
        $kriteria = $this->input->post('no');
        //$kriteria = $this->uri->segment(3);
        $sql = "SELECT a.*, (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek,b.nm_pengirim 
        from trdkasin_pkd a left join ms_pengirim b on a.sumber=b.kd_pengirim where a.no_sts = '$kriteria'  AND a.kd_skpd = '$lcskpd' and left(a.kd_rek6,1)='4' order by a.no_sts";
        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_sts' => $resulte['no_sts'],
                'kd_skpd' => $resulte['kd_skpd'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek' => $resulte['nm_rek'],
                'rupiah' =>  number_format($resulte['rupiah'], 2, '.', ','),
                'no_terima' => $resulte['no_terima'],
                'sumber' => $resulte['sumber'],
                'nm_pengirim' => $resulte['nm_pengirim']
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
            $lc = " and a.kd_rek6 not in ($lcfilt)";
        }

        /* $sql = "SELECT a.*, (SELECT nm_rek5 FROM ms_rek5 WHERE kd_rek5=a.kd_rek5) AS nm_rek5 FROM tr_terima a WHERE a.kd_skpd = '$lckdskpd' 
                AND a.no_terima NOT IN (SELECT ISNULL(no_terima,'') FROM trhkasin_pkd WHERE kd_skpd='$lckdskpd') AND
            upper(a.kd_rek5) like upper('%$lccr%') $lc"; */
        /*
            $sql = "SELECT a.kd_rek5,(SELECT nm_rek5 FROM ms_rek5 WHERE kd_rek5=a.kd_rek5) AS nm_rek FROM 
            trdrka a where a.kd_skpd = '$lckdskpd' and a.kd_kegiatan = '$lcgiat' and 
            upper(a.kd_rek5) like upper('%$lccr%') $lc";*/

        $sql = "select kd_rek6,nm_rek6 from trdrka_pend where kd_skpd='$lckdskpd' and kd_sub_kegiatan='$lcgiat'
                    and upper(kd_rek6) like upper('%$lccr%') order by kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                //'no_terima' => $resulte['no_terima'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek' => $resulte['nm_rek6'],
                //'nilai' => $resulte['nilai']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function ambil_rek1()
    {
        $lccr = $this->input->post('q');
        $lcfilt = $this->uri->segment(3);
        $lc = '';
        if ($lcfilt != '') {
            $lcfilt = str_replace('A', "'", $lcfilt);
            $lcfilt = str_replace('B', ",", $lcfilt);
            $lc = " and a.kd_rek6 not in ($lcfilt)";
        }


        $sql = "SELECT a.kd_rek6,a.nm_rek6 FROM ms_rek6 a
            where left(kd_rek6,3)='111' $lc";



        //echo $sql;

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6']
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

    //----------------------------------------------------- 10 September 2021 -------------------------------------------------------------------------------------------- 
    function simpan_sts_pendapatan()
    {

        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
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
        $no_terima   = $this->input->post('no_terima');
        $nmskpd      = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
        $last_update = date('d-m-y H:i:s');
        $msg = array();
        //--------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $tanggal1  = explode('-', $tgl);
        $bulan  = $tanggal1[1];


        //--------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $hasil = $this->db->query("select count(kd_skpd) [jumlah] from ms_skpd where jns='2' and kd_skpd = '$skpd' ");
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        //--------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
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
        //--------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
        $this->db->trans_start();
        if ($tabel == 'trhkasin_pkd') {

            $sql = "delete from trhkasin_pkd where kd_skpd='$skpd' and no_sts='$nomor'";
            $asg = $this->db->query($sql);
            //--------------------------------------------------------------------------------------------------------------------------------------------------------------------- 
            if ($asg) {
                if ($jnsrek == 5) {

                    $sql = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$sumber','1','$sp2d','$jns_cp')";
                    $asg = $this->db->query($sql);
                } else {
                    $sql = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima) 
                        values('$nomor','$skpd','$tgl','$ket','$total','$bank','$giat','$jnsrek','$rekbank','$pengirim','0','$sp2d','$jns_cp','$no_terima')";
                    $asg = $this->db->query($sql);
                }
                //---------------------------------------------------------------------------------------------------------------------------------------------------------------------                
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                }
                if ($asg) {
                    $sql = "update a set a.kunci=0 
                    from tr_terima a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_terima=b.no_terima and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    where a.kd_skpd='$skpd' and b.no_sts='$nomor'";
                    $asg = $this->db->query($sql);

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
                    }
                }
            }
            echo '2';
        }
        $this->db->trans_complete();
    }
    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------- 

    function update_sts_pendapatan_ag()
    {

        $tabel       = $this->input->post('tabel');
        $nomor       = $this->input->post('no');
        $nohide      = $this->input->post('nohide');
        //$nomor_kas   = $this->input->post('lckas');
        //$tgl_kas     = $this->input->post('tglkas');
        $bank        = $this->input->post('bank');
        $tgl         = $this->input->post('tgl');
        $skpd        = $this->input->post('skpd');
        $ket         = $this->input->post('ket');
        $jnsrek      = $this->input->post('jnsrek');
        $giat        = $this->input->post('giat');
        $rekbank     = $this->input->post('rekbank');
        $total       = $this->input->post('total');
        $lckdrek     = $this->input->post('kdrek');
        $lnil_rek    = $this->input->post('nilai');
        $lcnilaidet  = $this->input->post('value_det');
        $pengirim      = $this->input->post('pengirim');
        $sumber      = $this->input->post('sts');
        $sp2d        = $this->input->post('sp2d');
        $jns_cp      = $this->input->post('jns_cp');
        $no_terima   = $this->input->post('no_terima');
        $nmskpd      = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $usernm      = $this->session->userdata('pcNama');
        $last_update = date('d-m-y H:i:s');
        // $last_update = " ";
        $msg = array();

        $this->db->trans_start();
        $sql = "delete from trhkasin_pkd where kd_skpd='$skpd' and no_sts='$nohide'";
        $asg = $this->db->query($sql);


        $sql = "insert into trhkasin_pkd(no_sts,kd_skpd,tgl_sts,keterangan,total,kd_bank,kd_sub_kegiatan,
                        jns_trans,rek_bank,sumber,pot_khusus,no_sp2d,jns_cp,no_terima) 
                        values('$nomor','$skpd','$tgl','$ket','$total','','$giat','4','','$pengirim','0','','','$no_terima')";



        $asg = $this->db->query($sql);
        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        }
        if ($asg) {
            $sql = "delete from trdkasin_pkd where no_sts='$nohide' AND kd_skpd='$skpd' ";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into trdkasin_pkd(kd_skpd,no_sts,kd_rek6,rupiah,kd_sub_kegiatan,no_terima,sumber) values $lcnilaidet";
                $asg = $this->db->query($sql);
            }
        }
        $this->db->trans_complete();
        echo '2';
    }

    function ctk_str($dcetak = '', $dcetak2 = '', $skpd = '')
    {
        $lcskpd2 = $skpd;
        $spasi = 4;
        $csql11 = " select nm_skpd from ms_skpd where left(kd_skpd,len('$skpd')) = '$skpd'";
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper($trh1->nm_skpd);

        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpd2' ");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        $cRet = '';

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
             <tr>
                <td align=\"center\" colspan=\"11\" style=\"font-size:14px;border: solid 1px white;\"><b>$lcskpd<br>BUKU PENYETORAN <br> BENDAHARA PENERIMAAN</b></td>
            </tr>
           
            <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"3\" style=\"border: solid 1px white;\">OPD</td>
                <td align=\"left\" colspan=\"6\" style=\"border: solid 1px white;\">:&nbsp;$lcskpd</td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"3\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">PERIODE</td>
                <td align=\"left\" colspan=\"8\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">:&nbsp;" . $this->tukd_model->tanggal_format_indonesia($dcetak) . " S.D " . $this->tukd_model->tanggal_format_indonesia($dcetak2) . "</td>
            </tr>
            </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
            <thead>
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"8%\">No. STS</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">Tgl STS</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">Ket.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"5%\">Rek.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"22%\">Nama Rek.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"8%\">Nilai</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"12%\">No. Terima</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">Tgl Terima</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\">Sumber</td>
            </tr>
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\">1</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">2</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">3</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">4</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">5</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">6</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">7</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">8</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">9</td>
            </tr>
            </thead>
           ";

        $sql1 = "SELECT * FROM (
                        select 1 nomor, b.no_sts, a.tgl_sts, a.keterangan, '' kd_rek6, 
                        '' nm_rek6, a.total as rupiah, '' no_terima, '' tgl_terima, '' sumber, '' nm_sumber 
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd 
                        and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6 and  b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.no_terima=c.no_terima
                        where b.kd_skpd='$lcskpd2' AND a.jns_trans IN ('4','2') and a.tgl_sts BETWEEN '$dcetak' AND '$dcetak2' 
                        group by b.no_sts, a.tgl_sts, a.keterangan, a.total
                        UNION ALL
                        select 2 nomor, b.no_sts, a.tgl_sts, '' keterangan, b.kd_rek6, 
                        (select nm_rek6 from ms_rek6 where kd_rek6=b.kd_rek6) nm_rek6, b.rupiah, b.no_terima, c.tgl_terima, b.sumber, (select nm_pengirim from ms_pengirim where kd_pengirim=b.sumber) nm_sumber 
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd 
                        and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6 and  b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.no_terima=c.no_terima
                        where b.kd_skpd='$lcskpd2' AND a.jns_trans IN ('4','2') and a.tgl_sts BETWEEN '$dcetak' AND '$dcetak2') x
                        order by tgl_sts, no_sts, nomor, kd_rek6";

        $query = $this->db->query($sql1);
        $lcno = 0;
        $lnnilai = 0;
        $lntotal = 0;
        foreach ($query->result() as $row) {
            //$lcno = $lcno + 1;
            $nomor = $row->nomor;
            $no_sts = $row->no_sts;
            $tgl_sts = $row->tgl_sts;
            $keterangan = $row->keterangan;
            $kd_rek6 = $row->kd_rek6;
            $nm_rek6 = $row->nm_rek6;
            $rupiah = $row->rupiah;
            $no_terima = $row->no_terima;
            $tgl_terima = $row->tgl_terima;
            $sumber = $row->sumber;
            $nm_sumber = $row->nm_sumber;

            $nilai = number_format($row->rupiah, "2", ",", ".");

            /*  $status=$row->status;
                    if($status==1){
                        $s='&#10004';
                    }else{
                        $s='&#10008';
                    } */

            if ($tgl_sts == '') {
                $tgl_sts = '';
            } else {
                $tgl_sts = $this->tukd_model->tanggal_format_indonesia($tgl_sts);
            }

            if ($tgl_terima == '') {
                $tgl_terima = '';
            } else {
                $tgl_terima = $this->tukd_model->tanggal_format_indonesia($tgl_terima);
            }

            if ($nomor == '1') {
                $cRet    .= "<tr>
                                            <td align=\"left\"><b>" . $no_sts . "</b></td>
                                            <td align=\"center\"><b>" . $tgl_sts . "</b></td>
                                            <td align=\"left\"><b>" . $keterangan . "</b></td>
                                            <td align=\"center\"></td>
                                            <td align=\"center\"></td>
                                            <td align=\"right\"><b>" . $nilai . "</b></td>
                                            <td align=\"center\"></td>
                                            <td align=\"center\"></td>
                                            <td align=\"center\"></td>
                                      </tr>";
            } else {
                $cRet    .= "<tr>
                                            <td align=\"center\" style=\"border-top:hidden;\"></td>
                                            <td align=\"center\" style=\"border-top:hidden;\"></td>
                                            <td align=\"center\" style=\"border-top:hidden;\"></td>
                                            <td align=\"center\" >" . $kd_rek6 . "</td>
                                            <td align=\"left\" >" . $nm_rek6 . "</td>
                                            <td align=\"right\" >" . $nilai . "</td>
                                            <td align=\"left\" >" . $no_terima . "</td>
                                            <td align=\"center\" >" . $tgl_terima . "</td>
                                            <td align=\"left\" >" . $nm_sumber . "</td>
                                      </tr>";
            }
        }

        $sql_tot = "SELECT SUM(rupiah) total FROM (
                        select 1 nomor, b.no_sts, a.tgl_sts, a.keterangan, '' kd_rek6, 
                        '' nm_rek6, a.total as rupiah, '' no_terima, '' tgl_terima, '' sumber, '' nm_sumber 
                        from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd 
                        and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        LEFT JOIN tr_terima c on b.kd_skpd=c.kd_skpd and b.kd_rek6=c.kd_rek6 and  b.kd_sub_kegiatan=c.kd_sub_kegiatan and b.no_terima=c.no_terima
                        where b.kd_skpd='$lcskpd2' AND a.jns_trans IN ('4','2') and a.tgl_sts BETWEEN '$dcetak' AND '$dcetak2'
                        group by b.no_sts, a.tgl_sts, a.keterangan, a.total) x";
        $query = $this->db->query($sql_tot);
        foreach ($query->result() as $row) {
            $nilai = number_format($row->total, "2", ",", ".");


            $cRet    .= "<tr>
                                            <td align=\"center\" colspan=\"5\"><b>Total</b></td>
                                            
                                            <td align=\"left\" colspan=\"4\"><b>" . $nilai . "</b></td>
                                      </tr>";
        }

        $cRet    .= "</table>";


        $jns = 2;
        $data['prev'] = '';
        if ($jns == 1) {
            $this->support->_mpdf('', $cRet, 10, 10, 10, '1', 1, '');
        } else {
            echo ("<title>Cek Penerimaan</title>");
            echo $cRet;
        }
    }

    function cek_status_spj_terima()
    {
        $tgl = $this->input->post('tgl_cek');
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT ISNULL(RTRIM(cek),0) as cek FROM trhspj_terima_ppkd WHERE kd_skpd ='$skpd' AND bulan = MONTH('$tgl')";

        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'status_spj' => $resulte['cek']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
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



    /////////////////////

}
