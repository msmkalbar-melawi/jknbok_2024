<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class SP3BController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
        $this->load->model('support');
    }
    public function index()
    {
        $data['page_title'] = 'SP3B BOK';
        $this->template->set('title', 'SP3B BOK');
        $this->template->load('template', 'bok/sp3b/index', $data);
    }

    public function cek_data()
    {
        $no_lpj = $this->input->post('nomer');
        $kode = $this->input->post('kode');
        $data = $this->db->query("SELECT [status] as status FROM bok_trhlpj WHERE no_lpj='$no_lpj' AND kd_skpd='$kode'");
        $hasil = $data->row();
        $status = $hasil->status;
        if ($status == '1') {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function load_data_lpj()
    {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
        $kdskpd = $this->input->post('kdskpd');

        $sql = "SELECT kd_skpd, tgl_bukti, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6,nm_rek6,no_bukti,nilai,kd_skpd1 from (
            SELECT b.kd_skpd AS kd_skpd,
            b.tgl_bukti AS tgl_bukti,
            a.kd_sub_kegiatan AS kd_sub_kegiatan,
            a.nm_sub_kegiatan AS nm_sub_kegiatan,
            a.kd_rek6 AS kd_rek6,
            a.nm_rek6 AS nm_rek6,
            a.no_bukti AS no_bukti,
            a.nilai AS nilai,
            a.kd_skpd AS kd_skpd1 
        FROM
        bok_trdtransout a
            INNER JOIN bok_trhtransout b ON a.no_bukti= b.no_bukti 
            AND a.kd_skpd = b.kd_skpd 
        WHERE
            ( a.no_bukti + a.kd_sub_kegiatan + a.kd_rek6 + a.kd_skpd ) NOT IN ( SELECT ( no_bukti + kd_sub_kegiatan + kd_rek6 + kd_skpd ) FROM bok_trlpj ) 
            AND b.tgl_bukti >= '$dtgl1' 
            AND b.tgl_bukti <= '$dtgl2' 
            AND b.jns_spp IN ('3') 
            AND b.kd_skpd= '$kdskpd' 
            UNION ALL
            SELECT a.kd_skpd AS kd_skpd, a.tgl_terima AS tgl_bukti, a.kd_sub_kegiatan AS kd_sub_kegiatan, '' AS nm_sub_kegiatan, a.kd_rek6 AS kd_rek6, (SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6=a.kd_rek6) AS nm_rek6, a.no_terima AS no_bukti, a.nilai AS nilai, a.kd_skpd AS kd_skpd FROM bok_tr_terima a WHERE ( a.no_terima + a.kd_sub_kegiatan + a.kd_rek6 + a.kd_skpd ) NOT IN ( SELECT ( no_bukti + kd_sub_kegiatan + kd_rek6 + kd_skpd ) FROM bok_trlpj ) AND a.tgl_terima >= '$dtgl1' AND a.tgl_terima <= '$dtgl2' AND a.kd_skpd= '$kdskpd'			
            )x ORDER BY x.kd_skpd,x.tgl_bukti,x.kd_sub_kegiatan, x.kd_rek6, x.no_bukti 					
        ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        $total = 0;
        foreach ($query1->result_array() as $resulte) {
            $total += $resulte['nilai'];
            $result[] = array(
                'idx' => $ii,
                'kdskpd'    => $resulte['kd_skpd'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_rek6'     => $resulte['kd_rek6'],
                'nm_rek6'     => $resulte['nm_rek6'],
                'nilai'     => $resulte['nilai'],
                'kd_bp_skpd' => $resulte['kd_skpd1'],
                'no_bukti'   => $resulte['no_bukti'],
                'tgl_bukti'   => $resulte['tgl_bukti'],
                'total' => $total
            );
            $ii++;
        }
        echo json_encode($result);
    }

    public function simpan_data()
    {
        $kolom  = $this->input->post('kolom');
        $nilai  = $this->input->post('nilai');
        $skpd  = $this->input->post('skpd');
        $tgllpj  = $this->input->post('tgllpj');
        $tgl1  = $this->input->post('tgl1');
        $tgl2  = $this->input->post('tgl2');
        $nolpj  = $this->input->post('nolpj');
        $keterangan  = $this->input->post('keterangan');
        $total  = $this->input->post('total');
        $cek = $this->db->query("SELECT no_lpj FROM bok_trhlpj WHERE kd_skpd='$skpd' AND no_lpj='$nolpj'");
        $hasil = $cek->row();
        if (!empty($hasil)) {
            echo '2';
        }
        $sql = "insert into bok_trlpj $kolom $nilai";
        $asg = $this->db->query($sql);
        if ($sql) {
            $this->db->insert('bok_trhlpj', array(
                'no_lpj' => $this->input->post('nolpj'),
                'kd_skpd' => $this->input->post('skpd'),
                'keterangan' => $this->input->post('keterangan'),
                'tgl_lpj' => $this->input->post('tgllpj'),
                'tgl_awal' => $this->input->post('tgl1'),
                'tgl_akhir' => $this->input->post('tgl2'),
                'jenis' => '1',
                'no_sp3b' => $this->input->post('no_sp3b')
            ));
        }
        echo ('1');
    }

    function load_data()
    {
        $row = array();
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

        $sql = "SELECT count(*) as tot from bok_trhlpj WHERE  kd_skpd = '$kd_skpd' AND jenis = '1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd_jkn a where a.kd_skpd = '$kd_skpd') as nm_skpd FROM bok_trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where 
                AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM bok_trhlpj WHERE kd_skpd = '$kd_skpd' AND jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";

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
                'no_sp3b'   => $resulte['no_sp3b'],
                'tgl_lpj'      => $resulte['tgl_lpj'],
                'status'      => $resulte['status'],
                'tgl_awal'      => $resulte['tgl_awal'],
                'tgl_akhir'      => $resulte['tgl_akhir'],
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    public function select_lpj()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $lpj = $this->input->post('lpj');
        $sql = "SELECT (select d.tgl_bukti from bok_trhtransout d left join bok_trdtransout c on c.no_bukti=d.no_bukti and c.kd_skpd=d.kd_skpd where c.no_bukti=a.no_bukti and c.kd_skpd=a.kd_bp_skpd and c.kd_sub_kegiatan=a.kd_sub_kegiatan and c.kd_rek6=a.kd_rek6) as tgl_bukti,
         a.kd_skpd, a.no_lpj,a.no_bukti,a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6,a.nilai,kd_bp_skpd FROM bok_trlpj a INNER JOIN bok_trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
				WHERE a.no_lpj='$lpj' AND a.kd_skpd='$kd_skpd' order by tgl_bukti";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        $total = 0;
        foreach ($query1->result_array() as $resulte) {
            $total += $resulte['nilai'];
            $result[] = array(
                'idx'        => $ii,
                'no_bukti'   => $resulte['no_bukti'],
                'kd_skpd'   => $resulte['kd_skpd'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'kd_rek6'     => $resulte['kd_rek6'],
                'nm_rek6'     => $resulte['nm_rek6'],
                'kd_bp_skpd' => $resulte['kd_bp_skpd'],
                'nilai'      => $resulte['nilai'],
                'tgl_bukti'   => $resulte['tgl_bukti'],
                'total' => $total

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    public function update_data()
    {
        $kolom  = $this->input->post('kolom');
        $nilai  = $this->input->post('nilai');
        $skpd  = $this->input->post('skpd');
        $tgllpj  = $this->input->post('tgllpj');
        $tgl1  = $this->input->post('tgl1');
        $tgl2  = $this->input->post('tgl2');
        $nolpj  = $this->input->post('nolpj');
        $keterangan  = $this->input->post('keterangan');
        $total  = $this->input->post('total');
        $no_simpan  = $this->input->post('no_simpan');

        $cek = $this->db->query("SELECT no_lpj FROM bok_trhlpj WHERE kd_skpd='$skpd' AND no_lpj='$no_simpan' AND [status]='1'");
        $hasil = $cek->row();
        if (!empty($hasil)) {
            echo '2';
            return;
        }
        // return;
        $sql1 = $this->db->query("DELETE FROM bok_trlpj WHERE kd_skpd='$skpd' AND no_lpj='$no_simpan'");
        $sql2 = $this->db->query("DELETE FROM bok_trhlpj WHERE kd_skpd='$skpd' AND no_lpj='$no_simpan'");
        $sql = "insert into bok_trlpj $kolom $nilai";
        $asg = $this->db->query($sql);
        if ($asg) {
            $this->db->insert('bok_trhlpj', array(
                'no_lpj' => $this->input->post('nolpj'),
                'kd_skpd' => $this->input->post('skpd'),
                'keterangan' => $this->input->post('keterangan'),
                'tgl_lpj' => $this->input->post('tgllpj'),
                'tgl_awal' => $this->input->post('tgl1'),
                'tgl_akhir' => $this->input->post('tgl2'),
                'jenis' => '1',
                'no_sp3b' => $this->input->post('no_sp3b')
            ));
        }
        echo ('1');
    }

    public function hapus_data()
    {
        $kd_skpd  = $this->input->post('skpd');
        $nomolpj = $this->input->post('nomolpj');
        $cek = $this->db->query("SELECT [status] from bok_trhlpj where no_lpj='$nomolpj' AND kd_skpd='$kd_skpd'")->row();
        $cek1 = $cek->status;
        if ($cek1 == "1") {
            $msg = array('pesan' => '2');
            echo json_encode($msg);
            return;
        } else {
            $this->db->query("DELETE from bok_trlpj where no_lpj='$nomolpj' AND kd_skpd='$kd_skpd'");
            $this->db->query("DELETE from bok_trhlpj where no_lpj='$nomolpj' AND kd_skpd='$kd_skpd'");
            $msg = array('pesan' => '1');
            echo json_encode($msg);
            return;
        }
    }


    function load_ttd($ttd)
    {
        $kd_skpd = substr($this->session->userdata('kdskpd'), 0, 17) . '.0000';
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode='$ttd'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        // print_r($mas->result_array());
        // exit;   
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

    // andika
    public function laporanlpjbok()
    {
        $lcskpd = $_REQUEST['kdskpd'];
        $jeniscetakan = $_REQUEST['jnsspp'];
        $nomerspp = $_REQUEST['nomerspp'];
        $tgl1 = $_REQUEST['tgl1'];
        $tgl2 = $_REQUEST['tgl2'];
        $no_lpj = $_REQUEST['cspp'];
        $tglttd = $_REQUEST['tglttd'];
        //TTD
        $ttd1 = str_replace(' ', '%20', $_REQUEST['ttd']);
        $ttd2 = str_replace(' ', '%20', $_REQUEST['ttd_2']);
        $datalpj = $this->db->query("SELECT * FROM bok_trhlpj WHERE kd_skpd='$lcskpd' AND no_lpj='$no_lpj'")->row();
        if ($jeniscetakan == '0') {
            $judul = 'Surat Permintaan Pengesahan Pendapatan dan Belanja (SP3B) BOK';
            $judul1 = $datalpj->no_sp3b;
        } else if ($jeniscetakan == '1') {
            $judul = 'Laporan Pertanggungjawaban (LPJ) BOK';
            $judul1 = $datalpj->no_lpj;
        }

        //echo $tgl1;
        $pendapatan = 0;
        $totpendapatan = 0;
        $totpengeluaran = 0;

        //untuk cetak layar dan pdf
        $print = $this->uri->segment(4);
        $thn_ang = $this->session->userdata('pcThang');
        // $lcskpd = $_REQUEST['kd_skpd'];
        // $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(5);
        $bawah = $this->uri->segment(6);
        $kiri = $this->uri->segment(7);
        $kanan = $this->uri->segment(8);

        // $this->db->query("recall_skpd_bok '$lcskpd'");

        $datalpj = $this->db->query("SELECT * FROM bok_trhlpj WHERE kd_skpd='$lcskpd' AND no_lpj='$no_lpj'")->row();

        $nilaisebelumnya = $this->db->query("SELECT SUM(c.nil_pend) as penerimaan, SUM(c.nil_peng) as pengeluaran,SUM(c.nil_saldoawal) as nil_saldoawal,c.kd_skpd FROM( 
            SELECT x.kd_skpd, (CASE x.jenis WHEN '1' THEN SUM(x.nilai) ELSE 0 END) AS nil_pend,(CASE x.jenis WHEN '2' THEN SUM(x.nilai) ELSE 0 END) AS nil_peng,(CASE x.jenis WHEN '3' THEN SUM(x.nilai) ELSE 0 END) AS nil_saldoawal FROM ( 
            SELECT '1' as jenis, ISNULL(SUM(nilai),0) as nilai, tgl_terima as tanggal, kd_skpd FROM bok_tr_terima WHERE tgl_terima<'$datalpj->tgl_awal' GROUP BY tgl_terima, kd_skpd 
            UNION ALL 
            SELECT '2' as jenis, ISNULL(SUM(a.nilai),0) as nilai, b.tgl_bukti as tanggal, b.kd_skpd FROM bok_trdtransout a INNER JOIN bok_trhtransout b ON b.no_bukti=a.no_bukti AND b.kd_skpd=a.kd_skpd AND b.no_sp2d=a.no_sp2d WHERE b.tgl_bukti<'$datalpj->tgl_awal' GROUP BY b.tgl_bukti,b.kd_skpd
            UNION ALL 
            SELECT '3' as jenis, ISNULL(SUM(a.nilai),0) as nilai, '' as tanggal, a.kd_skpd FROM bok_saldo_awal a GROUP BY a.kd_skpd
            -- UNION ALL 
            -- SELECT '3' as jenis, ISNULL(SUM(a.nilai),0) as nilai, b.tgl_bukti as tanggal, b.kd_skpd FROM bok_trdstrpot a INNER JOIN bok_trhstrpot b ON b.no_bukti=a.no_bukti AND b.kd_skpd=a.kd_skpd WHERE b.tgl_bukti<'$datalpj->tgl_awal' GROUP BY b.tgl_bukti,b.kd_skpd
            )x GROUP BY x.jenis,x.kd_skpd) c WHERE c.kd_skpd='$lcskpd' GROUP BY c.kd_skpd");
        $ha1 = 0;
        foreach ($nilaisebelumnya->result_array() as $row) {
            $ha1 = $row['penerimaan'] + $row['nil_saldoawal'] - $row['pengeluaran']; /*- $row['nil_potongan']*/
        }

        $data2 = " SELECT kd_skpd,nm_skpd from ms_skpd_jkn
        WHERE kd_skpd = '$lcskpd'";
        $hasi = $this->db->query($data2);

        $nialipend = $this->db->query("SELECT SUM(x.nilai_pendapatan) as nilai_pendapatan,SUM(x.nilai_pengeluaran) as nilai_pengeluaran FROM(SELECT (CASE LEFT(b.kd_rek6,1) WHEN '4' THEN SUM(b.nilai) ELSE 0 END) as nilai_pendapatan, (CASE LEFT(b.kd_rek6,1) WHEN '5' THEN SUM(b.nilai) ELSE 0 END) as nilai_pengeluaran FROM bok_trhlpj a INNER JOIN bok_trlpj b ON b.kd_skpd=a.kd_skpd AND b.no_lpj=a.no_lpj WHERE a.kd_skpd='$lcskpd' AND a.no_lpj='$no_lpj' GROUP BY b.kd_rek6)x")->row();
        $h = 0;
        foreach ($hasi->result_array() as $row) {
            $h++;
            $kodee = $row['kd_skpd'];
            $namaa = $row['nm_skpd'];

            // $cekkkk = result_array

            //  print_r($hasi->result_array());
            // exit;



            $cRet = '';
            $cRet = '<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="0" cellpadding="1" align=center>
        
        <TR>
            <TD align="center" style="border-right:none;">
                <img src="' . base_url() . '/image/melawi_transparant.png"   width="75" height="100" align="left" style="margin:5px"/>
            </TD>
            <TD style="border-left:none;text-align: center;">
                   <align="center"><b>PEMERINTAH KABUPATEN MELAWI</b><br>
                    <b>' . $judul . '<b><br>
                    Nomor : ' . $judul1 . '</align>                                                               
         </TD>
        </TR>                   
        </TABLE>';

            $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        <TR>
            <TD align="left" >Nama Puskesmas :  ' . ($kodee) . ' - ' . ($namaa) . ' </TD>                     
        </TR>                   
        </TABLE>';

            $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        <TR>
            <TD align="left" >Periode : ' . $this->support->tanggal_format_indonesia($tgl1) . ' s/d ' . $this->support->tanggal_format_indonesia($tgl2) . '</TD>                       
        </TR>                    
        </TABLE>';

            $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        <TR>
            <TD align="left" >Saldo Awal&nbsp;&nbsp;: Rp. ' . number_format($ha1, 2, ",", ".") . ' </TD>                       
        </TR>                    
        </TABLE>';
            $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        <TR>
            <TD align="left" >Pendapatan&nbsp;&nbsp;: Rp. ' . number_format(($nialipend->nilai_pendapatan), 2, ",", ".") . '</TD>                       
        </TR>                    
        </TABLE>';

            $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        <TR>
            <TD align="left" >Belanja &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. ' . number_format(($nialipend->nilai_pengeluaran), 2, ",", ".") . '</TD>                       
        </TR>                    
        </TABLE>';

            $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        <TR>
            <TD align="left" >Saldo Akhir : Rp. ' . number_format(($ha1 + $nialipend->nilai_pendapatan) - $nialipend->nilai_pengeluaran, 2, ",", ".") . ' <TD>                       
        </TR>                    
        </TABLE>';




            $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        <TR>
            <TD align="center" colspan="3" width="50%" style="border-collapse:collapse; border-right:solid 1px black"><b>PENDAPATAN<b>
            </TD>  
         <TD align="center" colspan="3" width="50%" style="border-collapse:collapse; border-right:solid 1px black;"><b>BELANJA</b></TD>                                          
        </TR>

        <TR>
        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
        <b>Kode Sub Kegiatan</b>
        </TD>
        
        <TD align="center"  style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
        <b>Kode Rekening</b>
        </TD>                       
        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="10%">
        <b>Jumlah</b>
        </TD>
               
     
        <TD align="center"  style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
        <b>Kode Sub Kegiatan</b>                        
        </TD>  
                
        <TD align="center"  style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
        <b>Kode Rekening</b>                        
        </TD>

        <TD align="center"  style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="10%">
        <b>Jumlah</b>                        
        </TD>

    </TR>';


            $data4 = " SELECT
	'4' AS kode,
	a.no_lpj AS no_lpj,
	a.kd_sub_kegiatan AS kd_sub_kegiatan,
	a.kd_rek6 AS kd_rek6,
	( SELECT nm_rek6 FROM ms_rek6 WHERE kd_rek6 = a.kd_rek6 ) AS nm_rek6,
	 sum(nilai) AS jumlah
    --  nilai AS jumlah 
    FROM
	bok_trlpj a
	JOIN bok_trhlpj b ON a.no_lpj = b.no_lpj 
    WHERE
	a.kd_skpd = '$lcskpd' 
	AND b.no_lpj = '$no_lpj' 
	AND LEFT ( a.kd_rek6, 1 ) = '4'
    group by a.no_lpj,a.kd_sub_kegiatan,a.kd_rek6 
    ";

            // $data4 = "SELECT 
            //         '4' as kode,
            //         a.no_lpj as no_lpj,
            //         a.kd_sub_kegiatan as kd_sub_kegiatan,
            //         a.kd_rek6 as kd_rek6,
            //         (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek,
            //         SUM ( nilai ) AS jumlah 
            //     FROM
            //         bok_trlpj a
            //         JOIN bok_trhlpj b ON a.no_lpj = b.no_lpj
            //         where a.kd_skpd = '$lcskpd' and b.no_lpj = '$no_lpj' and left(a.kd_rek6, 1) = '4'
            //     GROUP BY
            //         a.kd_sub_kegiatan,
            //         a.kd_rek6,
            //         a.no_lpj"; 


            $data5 = "SELECT
            '5' as kode_5,
            a.no_lpj as no_lpj,
            a.kd_sub_kegiatan as kd_sub_kegiatan,
            a.kd_rek6 as kd_rek6,
            (select nm_rek6 from ms_rek6 where kd_rek6 = a.kd_rek6) as nm_rek6,
            sum(nilai) AS jumlah
            -- nilai AS jumlah  
        FROM
            bok_trlpj a
            JOIN bok_trhlpj b ON a.no_lpj = b.no_lpj
            where a.kd_skpd = '$lcskpd' and b.no_lpj = '$no_lpj' 
            and left(a.kd_rek6, 1) = '5'
            group by a.no_lpj,a.kd_sub_kegiatan,a.kd_rek6
            ";


            // $data4 = " SELECT left (b.kd_rek5,16) as kode1,b.nm_rek5 as nama1, sum(b.nilai) AS pendapatan 
            //         from trhsp3b_blud a INNER JOIN trsp3b_blud b
            //         on a.no_sp3b=b.no_sp3b AND a.kd_skpd=b.kd_skpd
            //         WHERE a.tgl_sp3b BETWEEN '$dcetak' and '$dcetak2' AND left (b.kd_rek5,1) ='4' and b.kd_skpd='$skpd'
            //         GROUP BY left (b.kd_rek5,16),b.nm_rek5";

            // //$data = "SELECT left a.kd_sub_kegiatan as kode1,a.kd_rek as kd_rek"

            // $data5= "SELECT left (b.kd_rek5,16) as kode2,b.nm_rek5 as nama2, SUM (b.nilai) as belanja
            //         from trhsp3b_blud a INNER JOIN trsp3b_blud b 
            //         on a.no_sp3b=b.no_sp3b AND a.kd_skpd=b.kd_skpd
            //         WHERE a.tgl_sp3b BETWEEN '$dcetak' and '$dcetak2' AND left (b.kd_rek5,1) ='5' and b.kd_skpd='$skpd'
            //         GROUP BY left (b.kd_rek5,16),b.nm_rek5";



            $hasil = $this->db->query($data4);
            $hasill = $this->db->query($data5);


            $total = 0;
            $total1 = 0;
            $data4 = 0;
            $data5 = 0;
            $cRetpendapatan[] = "";
            // $hasil = $this->db->query($data4);
            $i = 0;

            $numrow_belanja = $hasill->num_rows();
            $numrow_pendapatan = $hasil->num_rows();
            foreach ($hasil->result_array() as $row) {
                $i++;
                $kode1 = $row['kd_sub_kegiatan'];
                $nama1 = $row['kd_rek6'];
                $namarek1 = $row['nm_rek6'];
                $pendapatan = $row['jumlah'];
                $total = $total + $pendapatan;

                $cRetpendapatan[$i] = "<td width='15%' align='center' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" ><font face=\"Calibri\">" . ($kode1) . "</font></td>
            <td width='18%' align='center' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" ><font face=\"Calibri\">   " . ($nama1) . " - " . ($namarek1) . "</font></td>
            <td width='22,2%' align='left' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" ><font face=\"Calibri\">Rp&nbsp;" . number_format(($pendapatan), 2, ",", ".") . "</font></td>";
            }

            $j = 0;
            foreach ($hasill->result_array() as $row) {
                // $kode = $row->kode;
                $j++;
                $kode2 = $row['kd_sub_kegiatan'];
                $nama2 = $row['kd_rek6'];
                $namarek = $row['nm_rek6'];
                $belanja = $row['jumlah'];
                $total1 = $total1 + $belanja;
                // if ($kode1==''){

                // }else{$belanja=number_format($belanja, "2", ".", ",");

                // }
                // $cRet .= "<tr>";
                // if ($kode == '4') {
                //     $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$row->kd_sub_kegiatan</td>";
                //         $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$row->kd_rek6</td>";
                //         $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$row->jumlah</td>";
                // } else {
                //     $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$row->kd_sub_kegiatan</td>";
                //         $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$row->kd_rek6</td>";
                //         $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$row->jumlah</td>";
                // }


                if (isset($cRetpendapatan[$j])) {
                    if ($i == $j) {
                        $cRet .= "<tr>
                            " . $cRetpendapatan[$i] . "
                            <td width='15%' align='center' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\"><font face=\"Calibri\">" . ($kode2) . "</font></td>
                            <td width='18%' align='center' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\"><font face=\"Calibri\">" . ($nama2) . " - " . ($namarek) . "</font></td>
                            <td width='22,2%' align='left' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\"><font face=\"Calibri\">Rp&nbsp;" . number_format(($belanja), 2, ",", ".") . "</font></td>
                            </tr>";
                    } else {
                        $cRet .= "<tr>
                            <td width='22,2%' align='center' ><font face=\"Calibri\"></font></td>
                            <td width='22,2%' align='center' ><font face=\"Calibri\"></font></td>
                            <td width='22,2%' align='center' ><font face=\"Calibri\"></font></td>
                            <td width='15%' align='center' ><font face=\"Calibri\">" . ($kode2) . "</font></td>
                            <td width='18%' align='center' ><font face=\"Calibri\">" . ($nama2) . " - " . ($namarek) . "</font></td>
                            <td width='22,2%' align='left' ><font face=\"Calibri\">Rp&nbsp;" . number_format(($belanja), 2, ",", ".") . "</font></td>
                            </tr>";
                    }
                } else {
                    $cRet .= "<tr>
                            <td width='22,2%' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" align='center' ><font face=\"Calibri\"></font></td>
                            <td width='22,2%' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" align='center' ><font face=\"Calibri\"></font></td>
                            <td width='22,2%' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" align='center' ><font face=\"Calibri\"></font></td>
                            <td width='15%' align='center' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" ><font face=\"Calibri\">" . ($kode2) . "</font></td>
                            <td width='18%' align='center' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" ><font face=\"Calibri\">" . ($nama2) . " - " . ($namarek) . "</font></td>
                            <td width='22,2%' align='left' style=\"border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;\" ><font face=\"Calibri\">Rp&nbsp;" . number_format(($belanja), 2, ",", ".") . "</font></td>
                            </tr>";
                }
            }
            if ($j == 0 && $i != 0) {
                for ($k = 1; $k <= $i; $k++) {
                    $cRet .= "<tr>
                " . $cRetpendapatan[$k] . "
                <td width='15%' align='center' ><font face=\"Calibri\"></font></td>
                <td width='18%' align='left' ><font face=\"Calibri\"></font></td>
                <td width='22,2%' align='right' ><font face=\"Calibri\"></font></td>
                </tr>";
                }
            }

            if ($j != 0 && $i != 0) {
                for ($l = $j; $l < $i; $l++) {
                    $cRet .= "<tr>
                " . $cRetpendapatan[$l] . "
                <td width='15%' align='center' ><font face=\"Calibri\"></font></td>
                <td width='18%' align='left' ><font face=\"Calibri\"></font></td>
                <td width='17%' align='right' ><font face=\"Calibri\"></font></td>
                </tr>";
                }
            }
            $cRet .= "<tr>
        <td align='center' colspan=\"2\" ><font face=\"Calibri\"><b>Total Pendapatan :</b></font></td>
        <td align='right' ><b><font face=\"Calibri\">Rp&nbsp;" . number_format(($total), 2, ",", ".") . " </font></b></td>
        <td align='center' colspan=\"2\" style=\"border-collapse:collapse; border-left:solid 1px black;\"><font face=\"Calibri\"><b>Total Belanja :</b></font></td>
        <td align='right' ><b><font face=\"Calibri\">Rp&nbsp;" . number_format(($total1), 2, ",", ".") . " </font></b></td>
        </tr>";
            $cRet .= "</table>";

            $isian = "Bukti-bukti pendapatan dan/atau belanja di atas disimpan sesuai ketentuan yang berlaku untuk kelengkapan administrasi dan keperluan pemeriksaan aparat pengawas.<br>
Apabila di kemudian hari terjadi kerugian daerah, saya bersedia bertanggung jawab sepenuhnya atas kerugian daerah dimaksud dan dapat dituntut penggantian sesuai dengan ketentuan peraturan perundang-undangan.<br>
Demikian Surat Pernyataan ini dibuat dengan sebenarnya";
            $datadinkes = $this->db->query("SELECT nip, nama, jabatan, pangkat FROM ms_ttd WHERE kd_skpd='1.02.0.00.0.00.01.0000' AND nip='$ttd1'")->row();
            $datapuskesmas = $this->db->query("SELECT nip, nama, jabatan, pangkat FROM ms_ttd WHERE kd_skpd='$lcskpd' AND nip='$ttd2'")->row();

            $cRet .= '<table width="100%" style="font-size:12px;font face="Calibri"">
            <tr>
            <td style="text-align: justify;">' . $isian . '</td>
                    </table>';
            if ($jeniscetakan == '0') {
                $cRet .= '<br>
    <TABLE width="100%" style="font-size:12px;">        
    <TR>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><font face=\"Calibri\">Melawi, ' . $this->support->tanggal_format_indonesia($tglttd) . ' </font></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>

    </TR>
    <TR>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><font face=\"Calibri\">' . $datadinkes->jabatan . '</font></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        </TR>
    <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
    <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
        <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
        <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
        <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
    <TR>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><b><font face=\"Calibri\"><u>' . $datadinkes->nama . '</u></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
    </TR>
    
    <TR>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><font face=\"Calibri\">Nip : ' . $datadinkes->nip . '</font></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
    </TR> </table>';
            } else if ($jeniscetakan == '1') {
                $cRet .= '
    <br>
    <TABLE width="100%" style="font-size:12px;">        
    <TR>
        
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><font face=\"Calibri\">Melawi, ' . $this->support->tanggal_format_indonesia($tglttd) . ' </font></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>

    </TR>
    <TR>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><font face=\"Calibri\">' . $datapuskesmas->jabatan . '</font></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        </TR>
    <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
    <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
        <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
        <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
        <TR>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
        <TD align="right" ><b>&nbsp;</TD>
    </TR>
    <TR>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><b><font face=\"Calibri\"><u>' . $datapuskesmas->nama . '</u></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
    </TR>
    
    <TR>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
        <td align="center" ><font face=\"Calibri\">Nip : ' . $datapuskesmas->nip . '</font></td>
        <td align=\"center\" ><b><font face=\"Calibri\"></font></b></td>
    </TR> </table>';
            }


            if ($print == 0) {
                $data['prev'] = $cRet;
                echo ("<title>Laporan</title>");
                echo $cRet;
            } else {
                $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
            }
        }
    }
}
