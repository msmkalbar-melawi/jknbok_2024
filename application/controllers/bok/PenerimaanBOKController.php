<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class PenerimaanBOKController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'INPUT PENERIMAAN BOK';
        $this->template->set('title', 'INPUT PENERIMAAN BOK');
        $this->template->load('template', 'bok/penerimaan/index', $data);
    }


    public function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $hasil1 = $this->db->query("SELECT count(*) as jumlah FROM bok_tr_terima where no_terima='$nomor' and kd_skpd = '$kd_skpd' AND kunci='1'");
        foreach ($hasil1->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
        } else {
            $msg = array('pesan' => '0');
        }
        echo json_encode($msg);
    }

    public function simpan_data()
    {
        $this->db->trans_start();
        $no_terima = $this->input->post('no_terima');
        $kd_skpd   = $this->input->post('kd_skpd');
        $usernama      = $this->session->userdata('pcNama');
        //----------------------------------------------------------------------------------------------------------------------------
        $data = $this->db->insert('bok_tr_terima', array(
            'no_terima' => $this->input->post('no_terima'),
            'tgl_terima' => $this->input->post('tgl_terima'),
            'no_tetap' => $this->input->post('no_tetap'),
            'tgl_tetap' => $this->input->post('tgl_tetap'),
            'sts_tetap' => '0',
            'kd_skpd' => $this->input->post('kd_skpd'),
            'kd_sub_kegiatan' => $this->input->post('kd_sub_kegiatan'),
            'kd_rek6' => $this->input->post('kd_rek6'),
            'kd_rek_lo' => $this->input->post('kd_rek_lo'),
            'nilai' => $this->input->post('nilai'),
            'keterangan' => $this->input->post('keterangan'),
            'jenis' => $this->input->post('jenis'),
            'sumber' => $this->input->post('sumber'),
            'user_name' => $usernama,
            'no_kas' => $this->input->post('cno_nokas')
        ));
        $sql2 = "UPDATE a SET a.status='1' FROM bok_tr_tetap a WHERE a.no_tetap='$no_terima' and a.kd_skpd='$kd_skpd'";
        $asg2 = $this->db->query($sql2);
        $this->db->trans_complete();
        if ($data && $asg2) {
            $msg = array('pesan' => '1');
        }
        echo json_encode($msg);
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
    public function loaddata(Type $var = null)
    {
        $skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        // echo ($kriteria);
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (a.no_tetap LIKE '%$kriteria%' OR a.tgl_tetap LIKE '%$kriteria%' OR a.keterangan LIKE '%$kriteria%') ";
        }

        $sql = "SELECT count(*) as total from bok_tr_terima a WHERE a.kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();
        $sql = "SELECT top $rows a.*, (SELECT b.nm_rek6 FROM ms_rek6 b WHERE a.kd_rek6=b.kd_rek6) as nm_rek6, a.sumber FROM bok_tr_terima a 
                LEFT JOIN bok_tr_tetap b on a.no_tetap=b.no_tetap and a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$skpd'
                $where AND a.no_terima NOT IN (SELECT TOP $offset a.no_terima FROM bok_tr_terima a WHERE a.kd_skpd='$skpd' $where 
                ORDER BY a.tgl_tetap,a.no_tetap ) ORDER BY tgl_terima,no_terima";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_terima'          => $resulte['no_terima'],
                'tgl_terima'         => $resulte['tgl_terima'],
                'no_tetap'          => $resulte['no_tetap'],
                'tgl_tetap'         => $resulte['tgl_tetap'],
                'kd_skpd'           => $resulte['kd_skpd'],
                'keterangan'        => $resulte['keterangan'],
                'nilai'             => $resulte['nilai'],
                'kd_rek6'           => $resulte['kd_rek6'],
                'jenis'             => $resulte['jenis'],
                'nm_rek6'           => $resulte['nm_rek6'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'kd_rek'            => $resulte['kd_rek_lo'],
                'sumber'            => $resulte['sumber'],
                'user_name'         => $resulte['user_name'],
                'sts_tetap' => $resulte['sts_tetap'],
                'no_kas'            => $resulte['no_kas'],
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    public function load_no_tetap()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "where kd_skpd='$kd_skpd' ";
        if ($kriteria <> '') {
            $where = "where kd_skpd='$kd_skpd' AND (upper(no_tetap) like upper('%$kriteria%') or tgl_tetap like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%')) and ";
        }

        $sql = "SELECT no_tetap, jenis,tgl_tetap, kd_skpd, keterangan, nilai, kd_rek6, kd_rek_lo, kd_sub_kegiatan,
                (SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=bok_tr_tetap.kd_rek6) as nm_rek FROM bok_tr_tetap $where 
                AND no_tetap not in(select isnull(no_tetap,'') from bok_tr_terima)
                UNION ALL
                SELECT no_tetap,jenis,tgl_tetap,kd_skpd,keterangan,ISNULL(nilai,0)-ISNULL(nilai_terima,0) as nilai,kd_rek6,kd_rek_lo,a.kd_sub_kegiatan,a.nm_rek 
                FROM 
                (SELECT *,(SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=bok_tr_tetap.kd_rek6) as nm_rek FROM bok_tr_tetap $where )a
                LEFT JOIN
                (SELECT no_tetap as tetap,ISNULL(SUM(nilai),0) as nilai_terima from bok_tr_terima $where GROUP BY no_tetap)b
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
                'kd_rek_lo'     => $resulte['kd_rek_lo'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function no_urut()
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

    public function ambil_rek_tetap()
    {
        // $lckdskpd = $this->uri->segment(3);
        $lccr = $this->input->post('q');
        $sql = "SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM 
        trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 
        where a.kd_skpd = '5.02.0.00.0.00.02.0000' AND a.kd_rek6 IN('420101040011','420101040013','420101040021')/*and left(a.kd_rek6,1)='4'*/ and 
        (upper(a.kd_rek6) like upper('%$lccr%') or b.nm_rek6 like '%$lccr%') order by kd_rek6";


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'kd_rek_lo' => $resulte['kd_rek'],
                'nm_rek' => $resulte['nm_rek'],
                'nm_rek5' => $resulte['nm_rek5'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function skpd()
    {
        $lccr = $this->input->post('q');
        $jenis = $this->input->post('jenis');
        if ($jenis == 'skpd') {
            $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') order by kd_skpd ";
        } else if ($jenis == 'jknbok') {
            $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd_jkn where upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') order by kd_skpd ";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }
    public function delete_data()
    {
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $sql1 = "DELETE from bok_tr_terima where no_terima='$nomor' AND kd_skpd='$skpd'";
        $sql2 = "UPDATE a SET a.status='0' FROM bok_tr_tetap a WHERE a.no_tetap='$nomor' and a.kd_skpd='$skpd'";
        $asg2 = $this->db->query($sql2);
        $asg = $this->db->query($sql1);
        if ($asg || $asg2) {
            echo '1';
        } else {
            echo '0';
        }
    }

    // Laporan

    public function laporanindex()
    {
        $data['page_title'] = 'BKU PENERIMAAN BOK';
        $this->template->set('title', 'BKU PENERIMAAN BOK');
        $this->template->load('template', 'bok/penerimaan/laporanindex', $data);
    }

    public function load_ttd()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('PA','KPA')";
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

    public function load_ttd2($ttd)
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('$ttd')";
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
        // $mas->free_result();
    }

    public function laporanpenerimaanjkn($dcetak = '', $dcetak2 = '', $skpd = '', $jns = '', $spasi = '')
    {
        // echo $jns;
        // return;
        $thn_ang       = $this->session->userdata('pcThang');
        $tgl_ttd = $_REQUEST['tgl_ttd'];
        $ttd1 = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $ttd2 = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql11 = " select nm_skpd from ms_skpd_jkn where left(kd_skpd,len('$skpd')) = '$skpd'";
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper($trh1->nm_skpd);
        $cRet = '';

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
             <tr>
                <td align=\"center\" colspan=\"11\" style=\"font-size:14px;border: solid 1px white;\"><b>$lcskpd<br>BUKU PENETAPAN DAN PENERIMAAN <br></b><b>TAHUN $thn_ang</b></td>
            </tr>
           
            <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"3\" style=\"border: solid 1px white;\">SKPD</td>
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
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\">NO</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" colspan=\"5\">PENETAPAN</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" colspan=\"4\">PENERIMAAN</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\">Ket. Penerimaan</td>
            </tr>
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\">Tgl</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">No Bukti</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">Kode Rekening</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">Uraian</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">Jumlah</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">Tgl</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">No Bukti</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">Kode Rekening</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">Jumlah</td>
            </tr>
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"3%\">1</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"7%\">2</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">3</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"8%\">4</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"9%\">5</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">6</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"9%\">7</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">8</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">9</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">10</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"9%\">11</td>
            </tr>
			</thead>
           ";
        // $sql1 = "SELECT x.kd_skpd,
        // (CASE WHEN x.tgl_tetap BETWEEN '$dcetak' AND '$dcetak2' THEN x.tgl_tetap ELSE '' END) as tgl_tetap,
        // (CASE WHEN x.tgl_tetap BETWEEN '$dcetak' AND '$dcetak2' THEN x.no_tetap ELSE '' END) as no_tetap,
        // (CASE WHEN x.tgl_tetap BETWEEN '$dcetak' AND '$dcetak2' THEN x.kd_rek6 ELSE '' END) as kd_rek6,
        // (CASE WHEN x.tgl_tetap BETWEEN '$dcetak' AND '$dcetak2' THEN x.nm_rek6 ELSE '' END) as nm_rek6,
        // (CASE WHEN x.tgl_tetap BETWEEN '$dcetak' AND '$dcetak2' THEN x.nil_penetapan ELSE 0 END) as nil_penetapan,
        // (CASE WHEN x.tgl_terima BETWEEN '$dcetak' AND '$dcetak2' THEN x.tgl_terima ELSE '' END) as tgl_terima,
        // (CASE WHEN x.tgl_terima BETWEEN '$dcetak' AND '$dcetak2' THEN x.no_terima ELSE '' END) as no_terima,
        // (CASE WHEN x.tgl_terima BETWEEN '$dcetak' AND '$dcetak2' THEN x.nil_penerimaan ELSE 0 END) as nil_penerimaan,
        // (CASE WHEN x.tgl_terima BETWEEN '$dcetak' AND '$dcetak2' THEN x.keterangan ELSE '' END) as keterangan
        // FROM (SELECT a.kd_skpd, a.tgl_terima, a.no_terima, a.kd_rek6, b.nm_rek6, ISNULL(c.nilai,0) as nil_penetapan, c.tgl_tetap, c.no_tetap, ISNULL(a.nilai,0) as nil_penerimaan, a.keterangan FROM bok_tr_terima a LEFT JOIN ms_rek6 b ON b.kd_rek6=a.kd_rek6
        // LEFT JOIN (SELECT d.tgl_tetap, d.no_tetap ,d.kd_skpd,d.kd_rek6, d.nilai, d.keterangan FROM bok_tr_tetap d) c ON c.kd_skpd=a.kd_skpd AND c.kd_rek6=a.kd_rek6 AND c.no_tetap=a.no_tetap)x WHERE x.kd_skpd='$skpd' ORDER BY x.tgl_tetap";

        $sql1 = "SELECT b.tgl_tetap,b.no_tetap,b.kd_rek6 as kd_rek6penetapan,b.nilai as nil_tetap, b.keterangan as ket_tetap, a.tgl_terima,a.no_terima,a.kd_skpd,a.kd_sub_kegiatan,a.kd_rek6 as kd_rek6penerimaan ,a.nm_rek6, a.nilai as nil_terima,a.keterangan as ket_terima FROM(SELECT kd_skpd, kd_sub_kegiatan, kd_rek6,(SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=bok_tr_terima.kd_rek6) as nm_rek6, no_terima, no_tetap, tgl_terima, nilai,keterangan FROM bok_tr_terima)a LEFT JOIN (SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, no_tetap,tgl_tetap, nilai, keterangan FROM bok_tr_tetap) b ON b.kd_skpd=a.kd_skpd AND b.kd_sub_kegiatan=a.kd_sub_kegiatan AND b.no_tetap=a.no_tetap WHERE a.tgl_terima BETWEEN '$dcetak' AND '$dcetak2' AND a.kd_skpd='$skpd' ORDER BY b.tgl_tetap, a.tgl_terima";
        $query = $this->db->query($sql1);
        $lcno = 0;
        $lnnilai = 0;
        $lntotal = 0;
        $jmlpenetapan = 0;
        $jmlpenerimaan = 0;
        foreach ($query->result() as $row) {
            $lcno = $lcno + 1;
            // $tgl_tetap = $row->tgl_tetap;
            // $tgl_terima = $row->tgl_terima;
            if ($row->tgl_tetap == '1900-01-01') {
                $tgl_tetap = '';
            } else if ($row->tgl_terima == '1900-01-01') {
                $tgl_terima = '';
            } else {
                $tgl_tetap = $row->tgl_tetap;
                $tgl_terima = $row->tgl_terima;
            }
            $no_tetap = $row->no_tetap;
            $kd_penetapan = $row->kd_rek6penetapan;
            $kd_penerimaan = $row->kd_rek6penerimaan;
            $nm_rek6 = $row->nm_rek6;
            $nil_penetapan = $row->nil_tetap;
            $jmlpenetapan += $row->nil_tetap;
            $jmlpenerimaan += $row->nil_terima;
            $no_terima = $row->no_terima;
            $nil_penerimaan = $row->nil_terima;
            $keterangan = $row->ket_terima;
            $cRet    .= " <tr>
            <td align=\"left\" >$lcno</td>
            <td align=\"left\" >$row->tgl_tetap</td>
            <td align=\"left\" >$row->no_tetap</td>
            <td align=\"left\" >$row->kd_rek6penetapan</td>
            <td align=\"left\" ></td>
            <td align=\"left\" >$row->nil_tetap</td>
            <td align=\"left\" >" . $this->tukd_model->tanggal_format_indonesia($row->tgl_terima) . "</td>
            <td align=\"left\" >" . $row->no_terima . "</td>
            <td align=\"left\" >" . $row->kd_rek6penerimaan . " - " . $row->nm_rek6 . "</td>
            <td align=\"left\" >" . number_format($row->nil_terima, "2", ",", ".") . "</td>
            <td align=\"left\" >$row->ket_terima</td>
                                     </tr>
                                     ";
        }

        $cRet    .= " <tr><td colspan=\"5\" align=\"center\"><b>Jumlah</b></td>
								<td align=\"right\" ><b>" . number_format($jmlpenetapan, "2", ",", ".") . "</b></td>
								<td align=\"center\" colspan='3' ><b>Jumlah</b></td>
								<td align=\"right\" ><b>" . number_format($jmlpenerimaan, "2", ",", ".") . "</b></td>
								<td align=\"left\"></td>
							 </tr>
							 </table>";

        $csql = "SELECT nip,nama,jabatan,pangkat FROM ms_ttd WHERE nip = '$ttd1' AND kd_skpd = '$skpd' AND kode in ('PA','KPA')";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $nmPA = $trh3->nama;
        $nipPA = $trh3->nip;
        $pangkatPA = $trh3->pangkat;
        $jbtanPA = $trh3->jabatan;
        $csql = "SELECT nip,nama,jabatan,pangkat FROM ms_ttd WHERE nip = '$ttd2' AND kd_skpd = '$skpd' AND kode in ('BK')";
        $hasil3 = $this->db->query($csql);
        $trh4 = $hasil3->row();
        $nmBK = $trh4->nama;
        $nipBK = $trh4->nip;
        $pangkatBK = $trh4->pangkat;
        $jbtanBK = $trh4->jabatan;

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
			<tr>
			<td align=\"center\" width=\"50%\">Mengetahui</td>
			<td align=\"center\" width=\"50%\">Melawi, " . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "</td>
			</tr>
			<tr>
			<td align=\"center\" width=\"50%\"> $jbtanPA</td>
			<td align=\"center\" width=\"50%\">$jbtanBK</td>
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
                    <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\"><b><u>$nmPA</u></b><br>$pangkatPA</td>
                    <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\"><b><u>$nmBK</u></b><br>$pangkatBK</td>
                </tr>
                <tr>
                    <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\">NIP. $nipPA</td>
                     <td align=\"center\" width=\"50%\" style=\"font-size:14px;border: solid 1px white;\">NIP. $nipBK</td>
                </tr>
				</table>";
        if ($jns == '0') {
            echo $cRet;
        } else if ($jns == '1') {
            $this->_mpdf('', $cRet, 10, 10, 10, '1', 1, '');
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= BKU Penerimaan dan Pengeluaran.xls");
            echo $cRet;
        }
    }

    public function update_data()
    {
        $no_terima = $this->input->post('no_terima');
        $no_hide = $this->input->post('no_hide');
        $kd_skpd   = $this->input->post('kd_skpd');
        $usernama      = $this->session->userdata('pcNama');
        $this->db->trans_start();
        $sql            = "DELETE from bok_tr_terima where kd_skpd='$kd_skpd' AND no_terima='$no_hide'";
        $this->db->query($sql);
        //----------------------------------------------------------------------------------------------------------------------------
        $data = $this->db->insert('bok_tr_terima', array(
            'no_terima' => $this->input->post('no_terima'),
            'tgl_terima' => $this->input->post('tgl_terima'),
            'no_tetap' => $this->input->post('no_tetap'),
            'tgl_tetap' => $this->input->post('tgl_tetap'),
            'sts_tetap' => '0',
            'kd_skpd' => $this->input->post('kd_skpd'),
            'kd_sub_kegiatan' => $this->input->post('kd_sub_kegiatan'),
            'kd_rek6' => $this->input->post('kd_rek6'),
            'kd_rek_lo' => $this->input->post('kd_rek_lo'),
            'nilai' => $this->input->post('nilai'),
            'keterangan' => $this->input->post('keterangan'),
            'jenis' => $this->input->post('jenis'),
            'sumber' => $this->input->post('sumber'),
            'user_name' => $usernama
        ));
        $this->db->trans_complete();
        if ($data) {
            $msg = array('pesan' => '1');
        }
        echo json_encode($msg);
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

        $this->mpdf->defaultfooterfontsize = 3;    /* in pts */
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
}
