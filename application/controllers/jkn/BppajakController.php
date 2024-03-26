<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class BppajakController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'LAPORAN BKU JKN';
        $this->template->set('title', 'LAPORAN BKU JKN');
        $this->template->load('template', 'jkn/bppajak/index', $data);
    }

    public function Configskpd()
    {
        $skpd = $this->session->userdata('kdskpd');
        $sql = $this->db->query("SELECT kd_skpd, nm_skpd FROM ms_skpd_jkn WHERE kd_skpd = '$skpd'");
        $result = [];
        foreach ($sql->result_array() as $data) {
            $result = array(
                'kd_skpd' => $data['kd_skpd'],
                'nm_skpd' => $data['nm_skpd']
            );
        }
        echo json_encode($result);
    }

    public function ttd()
    {
        $skpd = $this->session->userdata('kdskpd');
        $sql = $this->db->query("SELECT * FROM ms_ttd WHERE kd_skpd = '$skpd' AND kode IN ('JKNBOK-BK')");
        // $result = array();
        foreach ($sql->result_array() as $data) {
            $result[] = array(
                'nama' => $data['nama'],
                'nip' => $data['nip']
            );
        }
        echo json_encode($result);
    }

    public function ttdPA()
    {
        $skpd = $this->session->userdata('kdskpd');
        $sql = $this->db->query("SELECT * FROM ms_ttd WHERE kd_skpd = '$skpd' AND kode IN ('JKNBOK-PA','JKNBOK-KPA')");
        // $result = array();
        foreach ($sql->result_array() as $data) {
            $result[] = array(
                'nama' => $data['nama'],
                'nip' => $data['nip']
            );
        }
        echo json_encode($result);
    }

    public function laporanbppajak()
    {
        $skpd = $this->session->userdata('kdskpd');
        $periode1 = $_REQUEST['periode1'];
        $periode2 = $_REQUEST['periode2'];
        $ttd1 = str_replace('%20', ' ', $_REQUEST['ttd1']);
        $ttd2 = str_replace('%20', ' ', $_REQUEST['ttd2']);
        $tglttd = $_REQUEST['tanggalttd'];
        // echo $ttd1;
        // return;
        $jenis = $_REQUEST['jenis'];
        $jnscetak = $_REQUEST['jnscetak'];

        if ($jenis == 'jkn') {
            $judul = 'JKN';
            $querypaj = $this->db->query("SELECT 
            sum(case when jns=1 then terima else 0 end) as debet,
            sum(case when jns=2 then keluar else 0 end ) as kredit
            FROM(
            SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  No :'+no_kas) AS ket,SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd FROM jkn_trhtrmpot a
            INNER JOIN jkn_trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_kas, a.kd_skpd
            UNION ALL
            SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+no_terima) AS ket,'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd FROM jkn_trhstrpot a
            INNER JOIN jkn_trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_terima, a.kd_skpd) a WHERE tgl<'$periode1' AND kd_skpd='$skpd'");
            //
            $sql = $this->db->query("SELECT * FROM(
                SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  No: '+no_kas) AS ket,SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd FROM jkn_trhtrmpot a
                INNER JOIN jkn_trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_kas, a.kd_skpd
                UNION ALL
                SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+no_terima) AS ket,'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd FROM jkn_trhstrpot a
                INNER JOIN jkn_trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_terima, a.kd_skpd ) a 
                WHERE tgl BETWEEN '$periode1' AND '$periode2' AND kd_skpd='$skpd' ORDER BY tgl,Cast(bku as decimal)");
        } else if ($jenis == 'bok') {
            $judul = 'BOK';
            $querypaj = $this->db->query("SELECT 
            sum(case when jns=1 then terima else 0 end) as debet,
            sum(case when jns=2 then keluar else 0 end ) as kredit
            FROM(
            SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  No : '+no_kas) AS ket,SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd FROM bok_trhtrmpot a
            INNER JOIN bok_trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_kas, a.kd_skpd
            UNION ALL
            SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+no_terima) AS ket,'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd FROM bok_trhstrpot a
            INNER JOIN bok_trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
            GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_terima, a.kd_skpd) a WHERE tgl <'$periode1' AND kd_skpd='$skpd'");
            //
            $sql = $this->db->query("SELECT * FROM(
                SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  No :'+no_kas) AS ket,SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd FROM bok_trhtrmpot a
                INNER JOIN bok_trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_kas, a.kd_skpd
                UNION ALL
                SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+no_terima) AS ket,'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd FROM bok_trhstrpot a
                INNER JOIN bok_trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_terima, a.kd_skpd ) a 
                WHERE tgl BETWEEN '$periode1' AND '$periode2' AND kd_skpd='$skpd' ORDER BY tgl,Cast(bku as decimal)");
        }
        // $saldopjk = 0;
        foreach ($querypaj->result() as $rowpjk) {
            $debet = $rowpjk->debet;
            $kredit = $rowpjk->kredit;
            $saldopjk = $debet - $kredit;
        }


        $cRet = "";
        $cRet = '<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b> </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>BUKU PAJAK ' . $judul . ' </TD>
					</TR>
					</TABLE><br/>';
        $nmpuskes = $this->db->query("SELECT * FROM ms_skpd_jkn WHERE kd_skpd = '$skpd'")->row();
        $cRet .= '<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >Puskesmas</TD>
						<TD align="left" width="100%" >: ' . $nmpuskes->nm_skpd . '  </TD>
					 </TR>
				
					 <TR>
						<TD align="left">Periode </TD>
						<TD align="left">: ' . $this->support->tanggal_format_indonesia($periode1) . ' s.d ' . $this->support->tanggal_format_indonesia($periode2) . ' </TD>
					 </TR>
					 </TABLE>';

        $cRet .= '<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="0" cellpadding="" width="100%" >
					 <THEAD>
					 <TR>
						<TD width="80" align="center" >NO</TD>
                        <TD width="90" align="center" >Tanggal</TD>
						<TD width="400" align="center" >Uraian</TD>						
						<TD width="150" align="center" >Pomotongan (Rp)</TD>
						<TD width="150" align="center" >Penyetoran (Rp)</TD>
						<TD width="150" align="center" >Saldo</TD>
					 </TR>
					 </THEAD>
					 <TR>
						<TD width="80" align="center" ></TD>
                        <TD width="90" align="center" ></TD>
						<TD width="350" align="left" >Saldo Lalu</TD>						
						<TD width="100" align="center" ></TD>
						<TD width="100" align="center" ></TD>
						<TD width="120" align="right" >' . number_format($saldopjk, "2", ",", ".") . ' </TD>
					 </TR>';

        $jumlahin = 0;
        $jumlahout = 0;
        $saldo = $saldopjk;
        foreach ($sql->result() as $row) {
            $bukti = $row->bku;
            $tanggal = $row->tgl;
            $ket = $row->ket;
            $in = $row->terima;
            $out = $row->keluar;
            if ($row->jns == '1') {
                $saldo = $saldo + $row->terima;
                $sal = empty($saldo) || $saldo == 0 ? '' : number_format($saldo, "2", ",", ".");
            } else {
                $saldo = $saldo - $row->keluar;
                $sal = empty($saldo) || $saldo == 0 ? '' : number_format($saldo, "2", ",", ".");
            }
            $jumlahin = $jumlahin + $in;
            $jumlahout = $jumlahout + $out;
            $cRet .= '<TR>
								<TD width="80" align="left" >' . $bukti . '</TD>
                                <TD width="90" align="left" >' . $this->support->tanggal_format_indonesia($tanggal) . '</TD>
								<TD width="400" align="left" >' . $ket . '</TD>								
								<TD width="150" align="right" >' . number_format($in, "2", ",", ".") . '</TD>
								<TD width="150" align="right" >' . number_format($out, "2", ",", ".") . '</TD>
								<TD width="150" align="right" >' . number_format($saldo, "2", ",", ".") . '</TD>
							 </TR>';
        }

        $cRet .= '<TR>
								<TD colspan ="3" width="80" align="center" >JUMLAH</TD>
								<TD width="150" align="right" >' . number_format($jumlahin, "2", ",", ".") . '</TD>
								<TD width="150" align="right" >' . number_format($jumlahout, "2", ",", ".") . '</TD>
								<TD width="150" align="right" >' . number_format(($saldopjk + $jumlahin - $jumlahout), "2", ",", ".") . '</TD>
							 </TR>';
        $cRet .= '</TABLE>';

        $datattd1 = $this->db->query("SELECT * FROM ms_ttd WHERE kd_skpd='$skpd' AND nip='$ttd1' AND kode IN ('JKNBOK-BK')")->row();
        $datattd2 = $this->db->query("SELECT * FROM ms_ttd WHERE kd_skpd='$skpd' AND nip='$ttd2' AND kode IN ('JKNBOK-PA','JKNBOK-KPA')")->row();
        $cRet .= '<TABLE width="100%" style="font-size:12px">
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" >Mengetahui,</TD>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" >Nanga Pinoh, ' . $this->support->tanggal_format_indonesia($tglttd) . '</TD>
        </TR>
        <TR>
            <TD align="center" >' . $datattd2->jabatan . '</TD>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" >' . $datattd1->jabatan . '</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD align="center" ><u>' . $datattd2->nama . '</u> <br> </TD>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" ><u>' . $datattd1->nama . '</u> <br> </TD>
        </TR>
        <TR>
            <TD align="center" >NIP : ' . $datattd2->nip . '</TD>
            <TD align="center" ><b>&nbsp;</TD>
            <TD align="center" >NIP : ' . $datattd1->nip . '</TD>
        </TR>
        </TABLE><br/>';

        $data['prev'] = 'BUKU PAJAK';
        switch ($jnscetak) {
            case 0;
                echo ("<title> BP Pajak</title>");
                echo $cRet;
                break;
            case 1;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
                break;
        }
    }
}
