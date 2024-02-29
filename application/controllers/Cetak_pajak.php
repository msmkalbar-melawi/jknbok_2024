<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class cetak_pajak extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data['page_title'] = 'PAJAK';
		$this->template->set('title', 'PAJAK');
		$this->template->load('template', 'tukd/transaksi/pajak', $data);
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


	function load_pasal_pajak()
	{
		$kd_skpd = $this->session->userdata('kdskpd');
		$sql = "SELECT a.kd_rek6, b.nm_rek6 FROM trdtrmpot a 
				INNER JOIN ms_pot b ON a.kd_rek6=b.kd_rek6
				GROUP BY a.kd_rek6,b.nm_rek6";

		$mas = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($mas->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kd_rek6' => $resulte['kd_rek6'],
				'nm_rek6' => $resulte['nm_rek6']
			);
			$ii++;
		}

		echo json_encode($result);
		$mas->free_result();
	}

	function cetak_pajak1($lcskpd = '', $nbulan = '', $ctk = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $jns = '')
	{
		$spasi = $this->uri->segment(10);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$lcskpd' and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}
		$querypaj = "SELECT 
					sum(case when jns=1 then terima else 0 end) as debet,
					sum(case when jns=2 then keluar else 0 end ) as kredit
					FROM(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+no_sp2d) AS ket,SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+no_terima) AS ket,'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_terima, a.kd_skpd) a WHERE MONTH(tgl)<'$nbulan' AND kd_skpd='$lcskpd'";
		$querypjk = $this->db->query($querypaj);
		foreach ($querypjk->result() as $rowpjk) {
			$debet = $rowpjk->debet;
			$kredit = $rowpjk->kredit;
			$saldopjk = $debet - $kredit;
		}
		$cRet = '<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b>' . $prov . ' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>BUKU PAJAK </TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >OPD</TD>
						<TD align="left" width="100%" >: ' . $lcskpd . ' ' . $skpd . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala OPD</TD>
						<TD align="left">: ' . $nama . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: ' . $nama1 . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->getBulan($nbulan) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="0" cellpadding="' . $spasi . '" width="100%" >
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
						<TD width="120" align="right" >' . number_format($saldopjk) . '</TD>
					 </TR>';



		$query = $this->db->query("SELECT * FROM(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+no_sp2d) AS ket,SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+no_terima) AS ket,'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_terima, a.kd_skpd ) a 
				    WHERE MONTH(tgl)='$nbulan' AND kd_skpd='$lcskpd' ORDER BY tgl,Cast(bku as decimal) ");

		$saldo = $saldopjk;
		$jumlahin = 0;
		$jumlahout = 0;
		foreach ($query->result() as $row) {
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
								<TD width="150" align="right" >' . number_format($jumlahin - $jumlahout + $saldopjk, "2", ",", ".") . '</TD>
							 </TR>';


		$cRet .= '</TABLE>';

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
						<TD align="center" >' . $daerah . ', ' . $this->support->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>' . $nama . ' </u> <br> ' . $pangkat . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><u>' . $nama1 . '</u> <br> ' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $nip . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';

		$data['prev'] = 'BUKU PAJAK PPN/PPh';
		switch ($ctk) {
			case 0;
				echo ("<title> BP Pajak</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
				break;
		}
	}


	function cetak_pajak2($lcskpd = '', $nbulan = '', $ctk = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $jns = '', $rinci = '')	{

		$spasi = $this->uri->segment(11);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}
		if ($jns == '1') {
			$cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" ><b>' . $prov . ' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>BUKU PAJAK PPN/PPh (SPJ UP/GU/TU)</TD>
					</TR>
					</TABLE><br/>';
		} else {
			$cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" ><b>' . $prov . ' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>BUKU PAJAK PPN/PPh (SP2D LS)</TD>
					</TR>
					</TABLE><br/>';
		}
		$cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="20%" >OPD</TD>
						<TD align="left" width="100%" >: ' . $lcskpd . ' ' . $skpd . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala OPD</TD>
						<TD align="left">: ' . $nama . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: ' . $nama1 . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->getBulan($nbulan) . '</TD>
					 </TR>
					 </TABLE>';

		if (($jns == '1') and ($rinci == '0')) {
			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="' . $spasi . '" align=center>
					 <THEAD>
					 <TR>
						<TD rowspan="2" width="5" align="center" >NO</TD>
                        <TD rowspan="2" width="10" align="center" >Tanggal</TD>
						<TD rowspan="2" width="20" align="center" >Uraian</TD>						
						<TD colspan="6" width="20" align="center" >Potongan Belanja Barang dan Modal</TD>
						<TD rowspan="2" width="15" align="center" >Pemotongan (Rp)</TD>
						<TD rowspan="2" width="15" align="center" >Penyetoran (Rp)</TD>
						<TD rowspan="2" width="15" align="center" >Saldo</TD>
					 </TR>
					 <TR>
						<TD width="15" align="center" >PPh Pasal 21</TD>
                        <TD width="15" align="center" >PPh Pasal 22</TD>
						<TD width="15" align="center" >PPh Pasal 23</TD>
						<TD width="15" align="center" >PPh Pasal 4</TD>						
						<TD width="15" align="center" >PPn</TD>
						<TD width="15" align="center" >Lain-Lain</TD>

					 </TR>
					 </THEAD>
					 <TR>
						<TD align="center" >1</TD>
                        <TD align="center" >2</TD>
						<TD align="center" >3</TD>						
						<TD align="center" >4</TD>
						<TD align="center" >5</TD>
						<TD align="center" >6</TD>
						<TD align="center" >7</TD>
						<TD align="center" >8</TD>
						<TD align="center" >9</TD>
						<TD align="center" >10=(4+5+6+7+8)</TD>
						<TD align="center" >11</TD>
						<TD align="center" >12=(9-10)</TD>
					 </TR>';



			$query = $this->db->query("SELECT a.bulan, ISNULL(SUM(pph21),0) as pph21, ISNULL(SUM(pph22),0) as pph22, ISNULL(SUM(pph23),0) as pph23, ISNULL(SUM(pphpasal4),0) as pphpasal4,
			ISNULL(SUM(pphn),0) as pphn, ISNULL(SUM(lain),0) as lain, ISNULL(SUM(pot),0) as pot, ISNULL(SUM(setor),0) as setor,
			ISNULL(SUM(pot)-SUM(setor),0) as saldo
			FROM
			(SELECT 1 as bulan UNION ALL
			SELECT 2 as bulan UNION ALL
			SELECT 3 as bulan UNION ALL
			SELECT 4 as bulan UNION ALL
			SELECT 5 as bulan UNION ALL
			SELECT 6 as bulan UNION ALL
			SELECT 7 as bulan UNION ALL
			SELECT 8 as bulan UNION ALL
			SELECT 9 as bulan UNION ALL
			SELECT 10 as bulan UNION ALL
			SELECT 11 as bulan UNION ALL
			SELECT 12 as bulan)a
			LEFT JOIN 
			(SELECT MONTH(tgl_bukti) as bulan, 
			SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai ELSE 0 END) AS pph21,
			SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai ELSE 0 END) AS pph22,
			SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai ELSE 0 END) AS pph23,
			SUM(CASE WHEN a.kd_rek6='210601050005' THEN a.nilai ELSE 0 END) AS pphpasal4,
			SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai ELSE 0 END) AS pphn,
			SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210601050005','210106010001') THEN a.nilai ELSE 0 END) AS lain,
			SUM(a.nilai) as pot,
			0 as setor
			FROM trdtrmpot a INNER JOIN trhtrmpot b on a.no_bukti=b.no_bukti 
			AND a.kd_skpd=b.kd_skpd
			WHERE jns_spp in('1','2','3')  AND a.kd_skpd='$lcskpd'
			GROUP BY month(tgl_bukti)
			UNION ALL
			SELECT MONTH(tgl_bukti) as bulan, 
			0 AS pph21,
			0 AS pph22,
			0 AS pph23,
			0 AS pphpasal4,
			0 AS pphn,
			0 AS lain,
			0 as pot,
			SUM(a.nilai) as setor
			FROM trdstrpot a INNER JOIN trhstrpot b on a.no_bukti=b.no_bukti 
			AND a.kd_skpd=b.kd_skpd
			WHERE jns_spp in('1','2','3') AND a.kd_skpd='$lcskpd'
			GROUP BY month(tgl_bukti)
			) b
			ON a.bulan=b.bulan
			WHERE a.bulan<='$nbulan'
			GROUP BY a.bulan
			ORDER BY a.bulan");

			$jum_pph21 = 0;
			$jum_pph22 = 0;
			$jum_pph23 = 0;
			$jum_pphpasal4 = 0;
			$jum_pphn = 0;
			$jum_lain = 0;
			$jum_pot = 0;
			$jum_setor = 0;
			$jum_saldo = 0;
			$ii = 0;
			foreach ($query->result() as $row) {
				$bulan = $row->bulan;
				$pph21 = $row->pph21;
				$pph22 = $row->pph22;
				$pph23 = $row->pph23;
				$pphpasal4 = $row->pphpasal4;
				$pphn = $row->pphn;
				$lain = $row->lain;
				$pot = $row->pot;
				$setor = $row->setor;
				$saldo = $row->saldo;
				$ii = $ii + 1;
				$pph21_1 = empty($pph21) || $pph21 == 0 ? number_format(0, "2", ",", ".") : number_format($pph21, "2", ",", ".");
				$pph22_1 = empty($pph22) || $pph22 == 0 ? number_format(0, "2", ",", ".") : number_format($pph22, "2", ",", ".");
				$pph23_1 = empty($pph23) || $pph23 == 0 ? number_format(0, "2", ",", ".") : number_format($pph23, "2", ",", ".");
				$pphpasal4_1 = empty($pphpasal4) || $pphpasal4 == 0 ? number_format(0, "2", ",", ".") : number_format($pphpasal4, "2", ",", ".");
				$pphn_1 = empty($pphn) || $pphn == 0 ? number_format(0, "2", ",", ".") : number_format($pphn, "2", ",", ".");
				$lain_1 = empty($lain) || $lain == 0 ? number_format(0, "2", ",", ".") : number_format($lain, "2", ",", ".");
				$pot_1 = empty($pot) || $pot == 0 ? number_format(0, "2", ",", ".") : number_format($pot, "2", ",", ".");
				$setor_1 = empty($setor) || $setor == 0 ? number_format(0, "2", ",", ".") : number_format($setor, "2", ",", ".");
				$saldo_1 = empty($saldo) || $saldo == 0 ? number_format(0, "2", ",", ".") : number_format($saldo, "2", ",", ".");
				$jum_pph21 = $jum_pph21 + $pph21;
				$jum_pph22 = $jum_pph22 + $pph22;
				$jum_pph23 = $jum_pph23 + $pph23;
				$jum_pphpasal4 = $jum_pphpasal4 + $pphpasal4;
				$jum_pphn = $jum_pphn + $pphn;
				$jum_lain = $jum_lain + $lain;
				$jum_pot = $jum_pot + $pot;
				$jum_setor = $jum_setor + $setor;
				$jum_saldo = $jum_saldo + $saldo;

				$cRet .= ' <TR>
							<TD align="center" >' . $ii . '</TD>
							<TD align="center" ></TD>
							<TD align="left" >' . $this->tukd_model->getBulan($bulan) . '</TD>						
							<TD align="right" >' . $pph21_1 . '</TD>
							<TD align="right" >' . $pph22_1 . '</TD>
							<TD align="right" >' . $pph23_1 . '</TD>
							<TD align="right" >' . $pphpasal4_1 . '</TD>
							<TD align="right" >' . $pphn_1 . '</TD>
							<TD align="right" >' . $lain_1 . '</TD>
							<TD align="right" >' . $pot_1 . '</TD>
							<TD align="right" >' . $setor_1 . '</TD>
							<TD align="right" >' . $saldo_1 . '</TD>
							 </TR>';
			}
			$cRet .= ' <TR>
			<TD colspan="3" align="center" >JUMLAH</TD>
			<TD align="right" >' . number_format($jum_pph21, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph22, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph23, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pphpasal4, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pphn, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_lain, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pot, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_setor, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_saldo, "2", ",", ".") . '</TD>
							 </TR>';

			$cRet .= '</TABLE>';
		}
		if (($jns == '1') and ($rinci == '1')) {
			$querypaj = "SELECT  SUM(pph21) as pph21, SUM(pph22) as pph22, SUM(pph23) as pph23, SUM(pphpasal4) as pphpasal4,
			SUM(pphn) as pphn, SUM(lain) as lain, SUM(pot) as pot, SUM(setor) as setor,
			SUM(pot)-SUM(setor) as saldo FROM 
			(SELECT a.no_bukti,tgl_bukti, ket,
			SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai ELSE 0 END) AS pph21,
			SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai ELSE 0 END) AS pph22,
			SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai ELSE 0 END) AS pph23,
			SUM(CASE WHEN a.kd_rek6='210601050005' THEN a.nilai ELSE 0 END) AS pphpasal4,
			SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai ELSE 0 END) AS pphn,
			SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210601050005','210106010001') THEN a.nilai ELSE 0 END) AS lain,
			SUM(a.nilai) as pot,
			0 as setor,
			1 as urut
			FROM trdtrmpot a INNER JOIN trhtrmpot b on a.no_bukti=b.no_bukti 
			AND a.kd_skpd=b.kd_skpd
			WHERE jns_spp in('1','2','3')  AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)<='$nbulan'
			GROUP BY a.no_bukti,tgl_bukti,ket
			UNION ALL
			SELECT a.no_bukti, tgl_bukti, ket, 
			SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai*-1 ELSE 0 END) AS pph21,
			SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai*-1 ELSE 0 END) AS pph22,
			SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai*-1 ELSE 0 END) AS pph23,
			SUM(CASE WHEN a.kd_rek6='210601050005' THEN a.nilai*-1 ELSE 0 END) AS pphpasal4,
			SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai*-1 ELSE 0 END) AS pphn,
			SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210601050005','210106010001') THEN a.nilai*-1 ELSE 0 END) AS lain,
			0 as pot,
			SUM(a.nilai) as setor,
			2 as urut
			FROM trdstrpot a INNER JOIN trhstrpot b on a.no_bukti=b.no_bukti 
			AND a.kd_skpd=b.kd_skpd
			WHERE jns_spp in('1','2','3') AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)<='$nbulan'
			GROUP BY a.no_bukti,tgl_bukti, ket)z";
			$querypjk = $this->db->query($querypaj);
			foreach ($querypjk->result() as $rowpjk) {
				$salpph21 = $rowpjk->pph21;
				$salpph22 = $rowpjk->pph22;
				$salpph23 = $rowpjk->pph23;
				$salpphpasal4 = $rowpjk->pphpasal4;
				$salpphn = $rowpjk->pphn;
				$sallain = $rowpjk->lain;
				$salpot = $rowpjk->pot;
				$salset = $rowpjk->setor;
				$saldopjk = $rowpjk->saldo;
			}
			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="' . $spasi . '" align=center>
					 <THEAD>
					 <TR>
						<TD rowspan="2" width="5" align="center" >NO</TD>
                        <TD rowspan="2" width="10" align="center" >Tanggal</TD>
						<TD rowspan="2" width="20" align="center" >Uraian</TD>						
						<TD colspan="6" width="20" align="center" >Potongan Belanja Barang dan Modal</TD>
						<TD rowspan="2" width="15" align="center" >Pemotongan (Rp)</TD>
						<TD rowspan="2" width="15" align="center" >Penyetoran (Rp)</TD>
						<TD rowspan="2" width="15" align="center" >Saldo</TD>
					 </TR>
					 <TR>
						<TD width="15" align="center" >PPh Pasal 21</TD>
                        <TD width="15" align="center" >PPh Pasal 22</TD>
						<TD width="15" align="center" >PPh Pasal 23</TD>
						<TD width="15" align="center" >PPh Pasal 4</TD>						
						<TD width="15" align="center" >PPn</TD>
						<TD width="15" align="center" >Lain-Lain</TD>
					 </TR>
					 <TR>
						<TD align="center" >1</TD>
                        <TD align="center" >2</TD>
						<TD align="center" >3</TD>						
						<TD align="center" >4</TD>
						<TD align="center" >5</TD>
						<TD align="center" >6</TD>
						<TD align="center" >7</TD>
						<TD align="center" >8</TD>
						<TD align="center" >9</TD>
						<TD align="center" >10=(4+5+6+7+8)</TD>
						<TD align="center" >11</TD>
						<TD align="center" >12=(9-10)</TD>
					 </TR>
					 
					 </THEAD>
					 ';

			$cRet .= ' <TR>
							<TD align="center" ></TD>
							<TD align="center" ></TD>
							<TD align="center" >Saldo s/d Bulan Lalu</TD>
							<TD align="right" >' . number_format($salpph21, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpph22, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpph23, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpphpasal4, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpphn, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($sallain, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpot, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salset, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($saldopjk, "2", ",", ".") . '</TD>
					 </TR>';
					 

			$query = $this->db->query("SELECT * FROM (SELECT a.no_bukti bku,tgl_bukti, ket,
			SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai ELSE 0 END) AS pph21,
			SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai ELSE 0 END) AS pph22,
			SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai ELSE 0 END) AS pph23,
			SUM(CASE WHEN a.kd_rek6='210601050005' THEN a.nilai ELSE 0 END) AS pphpasal4,
			SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai ELSE 0 END) AS pphn,
			SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210601050005','210106010001') THEN a.nilai ELSE 0 END) AS lain,
			SUM(a.nilai) as pot,
			0 as setor,
			1 as urut
			FROM trdtrmpot a INNER JOIN trhtrmpot b on a.no_bukti=b.no_bukti 
			AND a.kd_skpd=b.kd_skpd
			WHERE jns_spp in('1','2','3')  AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)='$nbulan'
			GROUP BY a.no_bukti,tgl_bukti,ket
			UNION ALL
			SELECT a.no_bukti bku, tgl_bukti, ket, 
			0 AS pph21,
			0 AS pph22,
			0 AS pph23,
			0 AS pphpasal4,
			0 AS pphn,
			0 AS lain,
			0 as pot,
			SUM(a.nilai) as setor,
			2 as urut
			FROM trdstrpot a INNER JOIN trhstrpot b on a.no_bukti=b.no_bukti 
			AND a.kd_skpd=b.kd_skpd
			WHERE jns_spp in('1','2','3') AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)='$nbulan'
			GROUP BY a.no_bukti,tgl_bukti, ket)a
			ORDER BY tgl_bukti, Cast(bku as decimal), urut");

			$jum_pph21 = 0;
			$jum_pph22 = 0;
			$jum_pph23 = 0;
			$jum_pphpasal4 = 0;
			$jum_pphn = 0;
			$jum_lain = 0;
			$jum_pot = 0;
			$jum_setor = 0;
			$jum_saldo = 0;
			$saldo = $saldopjk;
			foreach ($query->result() as $row) {
				$no_bukti = $row->bku;
				$tgl_bukti = $row->tgl_bukti;
				$ket = $row->ket;
				$pph21 = $row->pph21;
				$pph22 = $row->pph22;
				$pph23 = $row->pph23;
				$pphpasal4 = $row->pphpasal4;
				$pphn = $row->pphn;
				$lain = $row->lain;
				$pot = $row->pot;
				$setor = $row->setor;
				$saldo = $saldo + $pot - $setor;
				$pph21_1 = empty($pph21) || $pph21 == 0 ? number_format(0, "2", ",", ".") : number_format($pph21, "2", ",", ".");
				$pph22_1 = empty($pph22) || $pph22 == 0 ? number_format(0, "2", ",", ".") : number_format($pph22, "2", ",", ".");
				$pph23_1 = empty($pph23) || $pph23 == 0 ? number_format(0, "2", ",", ".") : number_format($pph23, "2", ",", ".");
				$pphpasal4_1 = empty($pphpasal4) || $pphpasal4 == 0 ? number_format(0, "2", ",", ".") : number_format($pphpasal4, "2", ",", ".");
				$pphn_1 = empty($pphn) || $pphn == 0 ? number_format(0, "2", ",", ".") : number_format($pphn, "2", ",", ".");
				$lain_1 = empty($lain) || $lain == 0 ? number_format(0, "2", ",", ".") : number_format($lain, "2", ",", ".");
				$pot_1 = empty($pot) || $pot == 0 ? number_format(0, "2", ",", ".") : number_format($pot, "2", ",", ".");
				$setor_1 = empty($setor) || $setor == 0 ? number_format(0, "2", ",", ".") : number_format($setor, "2", ",", ".");
				$saldo_1 = empty($saldo) || $saldo == 0 ? number_format(0, "2", ",", ".") : number_format($saldo, "2", ",", ".");
				$jum_pph21 = $jum_pph21 + $pph21;
				$jum_pph22 = $jum_pph22 + $pph22;
				$jum_pph23 = $jum_pph23 + $pph23;
				$jum_pphpasal4 = $jum_pphpasal4 + $pphpasal4;
				$jum_pphn = $jum_pphn + $pphn;
				$jum_lain = $jum_lain + $lain;
				$jum_pot = $jum_pot + $pot;
				$jum_setor = $jum_setor + $setor;
				$jum_saldo = $jum_saldo + $saldo;

				$cRet .= ' <TR>
							<TD align="center" >' . $no_bukti . '</TD>
							<TD align="center" >' . $this->support->tanggal_format_indonesia($tgl_bukti) . '</TD>
							<TD align="left" >' . $ket . '</TD>						
							<TD align="right" >' . $pph21_1 . '</TD>
							<TD align="right" >' . $pph22_1 . '</TD>
							<TD align="right" >' . $pph23_1 . '</TD>
							<TD align="right" >' . $pphpasal4_1 . '</TD>
							<TD align="right" >' . $pphn_1 . '</TD>
							<TD align="right" >' . $lain_1 . '</TD>
							<TD align="right" >' . $pot_1 . '</TD>
							<TD align="right" >' . $setor_1 . '</TD>
							<TD align="right" >' . $saldo_1 . '</TD>
							 </TR>';
			}
			$cRet .= ' <TR>
			<TD colspan="3" align="center" >JUMLAH</TD>
			<TD align="right" >' . number_format($jum_pph21, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph22, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph23, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pphpasal4, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pphn, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_lain, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pot, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_setor, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($saldo, "2", ",", ".") . '</TD>
							 </TR>';

			$cRet .= '</TABLE>';
		}
		if (($jns == '4') and ($rinci == '0')) {
			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="' . $spasi . '" align=center>
					 <THEAD>
					 <TR>
						<TD rowspan="2" width="5" align="center" >NO</TD>
                        <TD rowspan="2" width="5" align="center" >Tanggal</TD>
						<TD rowspan="2" width="5" align="center" >Uraian</TD>						
						<TD rowspan="2" width="5" align="center" >PPh Pasal 21</TD>						
						<TD rowspan="2" width="5" align="center" >PPh Pasal 22</TD>						
						<TD rowspan="2" width="5" align="center" >PPh.Pasal.23</TD>						
						<TD rowspan="2" width="5" align="center" >PPN</TD>						
						<TD rowspan="2" width="5" align="center" >Lain-Lain</TD>						
						<TD colspan="3" width="5" align="center" >Potongan Belanja Pegawai</TD>
						<TD rowspan="2" width="5" align="center" >Pemotongan (Rp)</TD>
						<TD rowspan="2" width="5" align="center" >Penyetoran (Rp)</TD>
						<TD rowspan="2" width="5" align="center" >Saldo</TD>
					 </TR>
					 <TR>
						<TD width="5" align="center" >IWP</TD>
                        <TD width="5" align="center" >TAPERUM</TD>
						<TD width="5" align="center" >HKGP</TD>						
					 </TR>
					 <TR>
						<TD align="center" >1</TD>
                        <TD align="center" >2</TD>
						<TD align="center" >3</TD>						
						<TD align="center" >4</TD>
						<TD align="center" >5</TD>
						<TD align="center" >6</TD>
						<TD align="center" >7</TD>
						<TD align="center" >8</TD>
						<TD align="center" >9</TD>
						<TD align="center" >10</TD>
						<TD align="center" >11</TD>
						<TD align="center" >12</TD>
						<TD align="center" >13</TD>
						<TD align="center" >14</TD>
					 </TR>
					 </THEAD>
					 ';



			$query = $this->db->query("
								SELECT a.bulan, ISNULL(SUM(pph21),0) as pph21, ISNULL(SUM(pph22),0) as pph22, ISNULL(SUM(pph23),0) as pph23,
								ISNULL(SUM(pphn),0) as pphn, ISNULL(SUM(lain),0) as lain, ISNULL(SUM(iwp),0) as iwp, ISNULL(SUM(taperum),0) as taperum, 
								ISNULL(SUM(hkpg),0) as hkpg, ISNULL(SUM(pot),0) as pot, ISNULL(SUM(setor),0) as setor,
								ISNULL(SUM(pot)-SUM(setor),0) as saldo
								FROM
								(SELECT 1 as bulan UNION ALL
								SELECT 2 as bulan UNION ALL
								SELECT 3 as bulan UNION ALL
								SELECT 4 as bulan UNION ALL
								SELECT 5 as bulan UNION ALL
								SELECT 6 as bulan UNION ALL
								SELECT 7 as bulan UNION ALL
								SELECT 8 as bulan UNION ALL
								SELECT 9 as bulan UNION ALL
								SELECT 10 as bulan UNION ALL
								SELECT 11 as bulan UNION ALL
								SELECT 12 as bulan)a
								LEFT JOIN 
								(SELECT MONTH(tgl_bukti) as bulan, 
								SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai ELSE 0 END) AS pph21,
								SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai ELSE 0 END) AS pph22,
								SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai ELSE 0 END) AS pph23,
								SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai ELSE 0 END) AS pphn,
								SUM(CASE WHEN a.kd_rek6 in ('2110701','2110702','2110703') THEN a.nilai ELSE 0 END) AS iwp,
								SUM(CASE WHEN a.kd_rek6='2110501' THEN a.nilai ELSE 0 END) AS taperum,
								SUM(CASE WHEN a.kd_rek6='2110801' THEN a.nilai ELSE 0 END) AS hkpg,
								SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','2110701','2110702','2110703','2110501','2110801') THEN a.nilai ELSE 0 END) AS lain,
								SUM(a.nilai) as pot,
								0 as setor
								FROM trdtrmpot a INNER JOIN trhtrmpot b on a.no_bukti=b.no_bukti 
								AND a.kd_skpd=b.kd_skpd
								WHERE jns_spp in('4','6','5')  AND a.kd_skpd='$lcskpd'
								GROUP BY month(tgl_bukti)
								UNION ALL
								SELECT MONTH(tgl_bukti) as bulan, 
								0 AS pph21,
								0 AS pph22,
								0 AS pph23,
								0 AS pphn,
								0 AS iwp,
								0 AS taperum,
								0 AS hkpg,
								0 AS lain,
								0 as pot,
								SUM(a.nilai) as setor
								FROM trdstrpot a INNER JOIN trhstrpot b on a.no_bukti=b.no_bukti 
								AND a.kd_skpd=b.kd_skpd
								WHERE jns_spp in('4','6','5') AND a.kd_skpd='$lcskpd'
								GROUP BY month(tgl_bukti)
								) b
								ON a.bulan=b.bulan
								WHERE a.bulan<='$nbulan'
								GROUP BY a.bulan
								ORDER BY a.bulan");

			$jum_pph21 = 0;
			$jum_pph22 = 0;
			$jum_pph23 = 0;
			$jum_pphn = 0;
			$jum_lain = 0;
			$jum_pot = 0;
			$jum_setor = 0;
			$jum_saldo = 0;
			$jum_iwp = 0;
			$jum_taperum = 0;
			$jum_hkpg = 0;
			$ii = 0;
			foreach ($query->result() as $row) {
				$bulan = $row->bulan;
				$pph21 = $row->pph21;
				$pph22 = $row->pph22;
				$pph23 = $row->pph23;
				$pphn = $row->pphn;
				$lain = $row->lain;
				$iwp = $row->iwp;
				$taperum = $row->taperum;
				$hkpg = $row->hkpg;
				$pot = $row->pot;
				$setor = $row->setor;
				$saldo = $row->saldo;
				$ii = $ii + 1;
				$pph21_1 = empty($pph21) || $pph21 == 0 ? number_format(0, "2", ",", ".") : number_format($pph21, "2", ",", ".");
				$pph22_1 = empty($pph22) || $pph22 == 0 ? number_format(0, "2", ",", ".") : number_format($pph22, "2", ",", ".");
				$pph23_1 = empty($pph23) || $pph23 == 0 ? number_format(0, "2", ",", ".") : number_format($pph23, "2", ",", ".");
				$pphn_1 = empty($pphn) || $pphn == 0 ? number_format(0, "2", ",", ".") : number_format($pphn, "2", ",", ".");
				$lain_1 = empty($lain) || $lain == 0 ? number_format(0, "2", ",", ".") : number_format($lain, "2", ",", ".");
				$iwp_1 = empty($iwp) || $iwp == 0 ? number_format(0, "2", ",", ".") : number_format($iwp, "2", ",", ".");
				$taperum_1 = empty($taperum) || $taperum == 0 ? number_format(0, "2", ",", ".") : number_format($taperum, "2", ",", ".");
				$hkpg_1 = empty($hkpg) || $hkpg == 0 ? number_format(0, "2", ",", ".") : number_format($hkpg, "2", ",", ".");
				$pot_1 = empty($pot) || $pot == 0 ? number_format(0, "2", ",", ".") : number_format($pot, "2", ",", ".");
				$setor_1 = empty($setor) || $setor == 0 ? number_format(0, "2", ",", ".") : number_format($setor, "2", ",", ".");
				$saldo_1 = empty($saldo) || $saldo == 0 ? number_format(0, "2", ",", ".") : number_format($saldo, "2", ",", ".");
				$jum_pph21 = $jum_pph21 + $pph21;
				$jum_pph22 = $jum_pph22 + $pph22;
				$jum_pph23 = $jum_pph23 + $pph23;
				$jum_pphn = $jum_pphn + $pphn;
				$jum_lain = $jum_lain + $lain;
				$jum_iwp = $jum_iwp + $iwp;
				$jum_taperum = $jum_taperum + $taperum;
				$jum_hkpg = $jum_hkpg + $hkpg;
				$jum_pot = $jum_pot + $pot;
				$jum_setor = $jum_setor + $setor;
				$jum_saldo = $jum_saldo + $saldo;

				$cRet .= ' <TR>
							<TD align="center" >' . $ii . '</TD>
							<TD align="center" ></TD>
							<TD align="left" >' . $this->tukd_model->getBulan($bulan) . '</TD>						
							<TD align="right" >' . $pph21_1 . '</TD>
							<TD align="right" >' . $pph22_1 . '</TD>
							<TD align="right" >' . $pph23_1 . '</TD>
							<TD align="right" >' . $pphn_1 . '</TD>
							<TD align="right" >' . $lain_1 . '</TD>
							<TD align="right" >' . $iwp_1 . '</TD>
							<TD align="right" >' . $taperum_1 . '</TD>
							<TD align="right" >' . $hkpg_1 . '</TD>
							<TD align="right" >' . $pot_1 . '</TD>
							<TD align="right" >' . $setor_1 . '</TD>
							<TD align="right" >' . $saldo_1 . '</TD>
							 </TR>';
			}
			$cRet .= ' <TR>
			<TD colspan="3" align="center" >JUMLAH</TD>
			<TD align="right" >' . number_format($jum_pph21, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph22, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph23, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pphn, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_lain, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_iwp, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_taperum, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_hkpg, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pot, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_setor, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_saldo, "2", ",", ".") . '</TD>
							 </TR>';
			$cRet .= '</TABLE>';
		}
		if (($jns == '4') and ($rinci == '1')) {
			$querypaj = "SELECT  SUM(pph21) as pph21, SUM(pph22) as pph22, SUM(pph23) as pph23,
					SUM(pphn) as pphn, SUM(ppnpn) as ppnpn, SUM(lain) as lain, ISNULL(SUM(iwp),0) as iwp, ISNULL(SUM(taperum),0) as taperum, 
					ISNULL(SUM(hkpg),0) as hkpg, ISNULL(SUM(pot),0) as pot, ISNULL(SUM(setor),0) as setor,
					ISNULL(SUM(pot)-SUM(setor),0) as saldo FROM 
					(SELECT a.no_bukti,tgl_bukti, ket,
					SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai ELSE 0 END) AS pph21,
					SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai ELSE 0 END) AS pph22,
					SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai ELSE 0 END) AS pph23,
					SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai ELSE 0 END) AS pphn,
					SUM(CASE WHEN a.kd_rek6='2110901' THEN a.nilai ELSE 0 END) AS ppnpn,
					SUM(CASE WHEN a.kd_rek6 in ('2110701','2110702','2110703') THEN a.nilai ELSE 0 END) AS iwp,
					SUM(CASE WHEN a.kd_rek6='2110501' THEN a.nilai ELSE 0 END) AS taperum,
					SUM(CASE WHEN a.kd_rek6='2110801' THEN a.nilai ELSE 0 END) AS hkpg,
					SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','2110701','2110702','2110703','2110501','2110801','2110901') THEN a.nilai ELSE 0 END) AS lain,
					SUM(a.nilai) as pot,
					0 as setor,
					1 as urut
					FROM trdtrmpot a INNER JOIN trhtrmpot b on a.no_bukti=b.no_bukti 
					AND a.kd_skpd=b.kd_skpd
					WHERE jns_spp in('4','6','5')  AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)<'$nbulan'
					GROUP BY a.no_bukti,tgl_bukti,ket
					UNION ALL
					SELECT a.no_bukti, tgl_bukti, ket, 
					SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai*-1 ELSE 0 END) AS pph21,
					SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai*-1 ELSE 0 END) AS pph22,
					SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai*-1 ELSE 0 END) AS pph23,
					SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai*-1 ELSE 0 END) AS pphn,
					SUM(CASE WHEN a.kd_rek6='2110901' THEN a.nilai*-1 ELSE 0 END) AS ppnpn,
					SUM(CASE WHEN a.kd_rek6 in ('2110701','2110702','2110703') THEN a.nilai*-1 ELSE 0 END) AS iwp,
					SUM(CASE WHEN a.kd_rek6='2110501' THEN a.nilai*-1 ELSE 0 END) AS taperum,
					SUM(CASE WHEN a.kd_rek6='2110801' THEN a.nilai*-1 ELSE 0 END) AS hkpg,
					SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','2110701','2110702','2110703','2110501','2110801','2110901') THEN a.nilai*-1 ELSE 0 END) AS lain,
					0 as pot,
					SUM(a.nilai) as setor,
					2 as urut
					FROM trdstrpot a INNER JOIN trhstrpot b on a.no_bukti=b.no_bukti 
					AND a.kd_skpd=b.kd_skpd
					WHERE jns_spp in('4','6','5') AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)<'$nbulan'
					GROUP BY a.no_bukti,tgl_bukti, ket)z";
			$querypjk = $this->db->query($querypaj);
			foreach ($querypjk->result() as $rowpjk) {
				$salpph21 = $rowpjk->pph21;
				$salpph22 = $rowpjk->pph22;
				$salpph23 = $rowpjk->pph23;
				$salpphn = $rowpjk->pphn;
				$salppnpn = $rowpjk->ppnpn;
				$sallain = $rowpjk->lain;
				$saliwp = $rowpjk->iwp;
				$saltaperum = $rowpjk->taperum;
				$salhkpg = $rowpjk->hkpg;
				$salpot = $rowpjk->pot;
				$salset = $rowpjk->setor;
				$saldopjk = $rowpjk->saldo;
			}
			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="' . $spasi . '" align=center>
					 <THEAD>
					  <TR>
						<TD rowspan="2" width="5" align="center" >NO</TD>
                        <TD rowspan="2" width="10" align="center" >Tanggal</TD>
						<TD rowspan="2" width="20" align="center" >Uraian</TD>						
						<TD rowspan="2" width="15" align="center" >PPh Pasal 21</TD>						
						<TD rowspan="2" width="15" align="center" >PPh Pasal 22</TD>						
						<TD rowspan="2" width="15" align="center" >PPh Pasal 23</TD>						
						<TD rowspan="2" width="15" align="center" >PPN</TD>						
						<TD rowspan="2" width="15" align="center" >PPNPN</TD>						
						<TD rowspan="2" width="15" align="center" >Lain-Lain</TD>						
						<TD colspan="3" width="20" align="center" >Potongan Belanja Pegawai</TD>
						<TD rowspan="2" width="15" align="center" >Pemotongan (Rp)</TD>
						<TD rowspan="2" width="15" align="center" >Penyetoran (Rp)</TD>
						<TD rowspan="2" width="15" align="center" >Saldo</TD>
					 </TR>
					 <TR>
						<TD width="15" align="center" >IWP</TD>
                        <TD width="15" align="center" >TAPERUM</TD>
						<TD width="15" align="center" >HKGP</TD>						
					 </TR>
					 <TR>
						<TD align="center" >1</TD>
                        <TD align="center" >2</TD>
						<TD align="center" >3</TD>						
						<TD align="center" >4</TD>
						<TD align="center" >5</TD>
						<TD align="center" >6</TD>
						<TD align="center" >7</TD>
						<TD align="center" >8</TD>
						<TD align="center" >9</TD>
						<TD align="center" >10</TD>
						<TD align="center" >11</TD>
						<TD align="center" >12</TD>
						<TD align="center" >13</TD>
						<TD align="center" >14</TD>
						<TD align="center" >15</TD>
					 </TR>
					 <TR>
						<TD align="center" >&nbsp;</TD>
                        <TD align="center" ></TD>
						<TD align="center" ></TD>						
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
					 </TR>
					 </THEAD>
					 ';

			$cRet .= ' <TR>
							<TD align="center" ></TD>
							<TD align="center" ></TD>
							<TD align="center" >Saldo s/d Bulan Lalu</TD>
							<TD align="right" >' . number_format($salpph21, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpph22, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpph23, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpphn, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salppnpn, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($sallain, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($saliwp, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($saltaperum, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salhkpg, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salpot, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($salset, "2", ",", ".") . '</TD>
							<TD align="right" >' . number_format($saldopjk, "2", ",", ".") . '</TD>
					 </TR>
					  <TR>
						<TD align="center" >&nbsp;</TD>
                        <TD align="center" ></TD>
						<TD align="center" ></TD>						
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
						<TD align="center" ></TD>
					 </TR>';

			$query = $this->db->query(" SELECT * FROM(
								SELECT a.no_bukti bku,tgl_bukti, ket,
								SUM(CASE WHEN a.kd_rek6='210105010001' THEN a.nilai ELSE 0 END) AS pph21,
								SUM(CASE WHEN a.kd_rek6='210105020001' THEN a.nilai ELSE 0 END) AS pph22,
								SUM(CASE WHEN a.kd_rek6='210105030001' THEN a.nilai ELSE 0 END) AS pph23,
								SUM(CASE WHEN a.kd_rek6='210106010001' THEN a.nilai ELSE 0 END) AS pphn,
								SUM(CASE WHEN a.kd_rek6='2110901' THEN a.nilai ELSE 0 END) AS ppnpn,
								SUM(CASE WHEN a.kd_rek6 in ('2110701','2110702','2110703') THEN a.nilai ELSE 0 END) AS iwp,
								SUM(CASE WHEN a.kd_rek6='2110501' THEN a.nilai ELSE 0 END) AS taperum,
								SUM(CASE WHEN a.kd_rek6='2110801' THEN a.nilai ELSE 0 END) AS hkpg,
								SUM(CASE WHEN a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','2110701','2110702','2110703','2110501','2110801','2110901') THEN a.nilai ELSE 0 END) AS lain,
								SUM(a.nilai) as pot,
								0 as setor,
								1 as urut
								FROM trdtrmpot a INNER JOIN trhtrmpot b on a.no_bukti=b.no_bukti 
								AND a.kd_skpd=b.kd_skpd
								WHERE jns_spp in('4','6','5')  AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)='$nbulan'
								GROUP BY a.no_bukti,tgl_bukti,ket
								UNION ALL
								SELECT a.no_bukti bku, tgl_bukti, ket, 
								0 AS pph21,
								0 AS pph22,
								0 AS pph23,
								0 AS pphn,
								0 AS ppnpn,
								0 AS iwp,
								0 AS taperum,
								0 AS hkpg,
								0 AS lain,
								0 as pot,
								SUM(a.nilai) as setor,
								2 as urut
								FROM trdstrpot a INNER JOIN trhstrpot b on a.no_bukti=b.no_bukti 
								AND a.kd_skpd=b.kd_skpd
								WHERE jns_spp in('4','6','5') AND a.kd_skpd='$lcskpd' AND MONTH(tgl_bukti)='$nbulan'
								GROUP BY a.no_bukti,tgl_bukti, ket) a
								ORDER BY tgl_bukti,CAST(bku as numeric), urut ");

			$jum_pph21 = 0;
			$jum_pph22 = 0;
			$jum_pph23 = 0;
			$jum_pphn = 0;
			$jum_ppnpn = 0;
			$jum_lain = 0;
			$jum_pot = 0;
			$jum_setor = 0;
			$jum_saldo = 0;
			$jum_iwp = 0;
			$jum_taperum = 0;
			$jum_hkpg = 0;
			$saldo = $saldopjk;
			foreach ($query->result() as $row) {
				$no_bukti = $row->bku;
				$tgl_bukti = $row->tgl_bukti;
				$ket = $row->ket;
				$pph21 = $row->pph21;
				$pph22 = $row->pph22;
				$pph23 = $row->pph23;
				$pphn = $row->pphn;
				$ppnpn = $row->ppnpn;
				$lain = $row->lain;
				$iwp = $row->iwp;
				$taperum = $row->taperum;
				$hkpg = $row->hkpg;
				$pot = $row->pot;
				$setor = $row->setor;
				$saldo = $saldo + $pot - $setor;
				$pph21_1 = empty($pph21) || $pph21 == 0 ? number_format(0, "2", ",", ".") : number_format($pph21, "2", ",", ".");
				$pph22_1 = empty($pph22) || $pph22 == 0 ? number_format(0, "2", ",", ".") : number_format($pph22, "2", ",", ".");
				$pph23_1 = empty($pph23) || $pph23 == 0 ? number_format(0, "2", ",", ".") : number_format($pph23, "2", ",", ".");
				$pphn_1 = empty($pphn) || $pphn == 0 ? number_format(0, "2", ",", ".") : number_format($pphn, "2", ",", ".");
				$ppnpn_1 = empty($ppnpn) || $ppnpn == 0 ? number_format(0, "2", ",", ".") : number_format($ppnpn, "2", ",", ".");
				$lain_1 = empty($lain) || $lain == 0 ? number_format(0, "2", ",", ".") : number_format($lain, "2", ",", ".");
				$iwp_1 = empty($iwp) || $iwp == 0 ? number_format(0, "2", ",", ".") : number_format($iwp, "2", ",", ".");
				$taperum_1 = empty($taperum) || $taperum == 0 ? number_format(0, "2", ",", ".") : number_format($taperum, "2", ",", ".");
				$hkpg_1 = empty($hkpg) || $hkpg == 0 ? number_format(0, "2", ",", ".") : number_format($hkpg, "2", ",", ".");
				$pot_1 = empty($pot) || $pot == 0 ? number_format(0, "2", ",", ".") : number_format($pot, "2", ",", ".");
				$setor_1 = empty($setor) || $setor == 0 ? number_format(0, "2", ",", ".") : number_format($setor, "2", ",", ".");
				$saldo_1 = empty($saldo) || $saldo == 0 ? number_format(0, "2", ",", ".") : number_format($saldo, "2", ",", ".");
				$jum_pph21 = $jum_pph21 + $pph21;
				$jum_pph22 = $jum_pph22 + $pph22;
				$jum_pph23 = $jum_pph23 + $pph23;
				$jum_pphn = $jum_pphn + $pphn;
				$jum_ppnpn = $jum_ppnpn + $ppnpn;
				$jum_lain = $jum_lain + $lain;
				$jum_iwp = $jum_iwp + $iwp;
				$jum_taperum = $jum_taperum + $taperum;
				$jum_hkpg = $jum_hkpg + $hkpg;
				$jum_pot = $jum_pot + $pot;
				$jum_setor = $jum_setor + $setor;
				$jum_saldo = $jum_saldo + $saldo;

				$cRet .= ' <TR>
							<TD align="center" >' . $no_bukti . '</TD>
							<TD align="center" >' . $this->support->tanggal_format_indonesia($tgl_bukti) . '</TD>
							<TD align="left" >' . $ket . '</TD>						
							<TD align="right" >' . $pph21_1 . '</TD>
							<TD align="right" >' . $pph22_1 . '</TD>
							<TD align="right" >' . $pph23_1 . '</TD>
							<TD align="right" >' . $pphn_1 . '</TD>
							<TD align="right" >' . $ppnpn_1 . '</TD>
							<TD align="right" >' . $lain_1 . '</TD>
							<TD align="right" >' . $iwp_1 . '</TD>
							<TD align="right" >' . $taperum_1 . '</TD>
							<TD align="right" >' . $hkpg_1 . '</TD>
							<TD align="right" >' . $pot_1 . '</TD>
							<TD align="right" >' . $setor_1 . '</TD>
							<TD align="right" >' . $saldo_1 . '</TD>
							 </TR>';
			}
			$cRet .= ' <TR>
			<TD colspan="3" align="center" >JUMLAH</TD>
			<TD align="right" >' . number_format($jum_pph21, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph22, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pph23, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pphn, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_ppnpn, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_lain, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_iwp, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_taperum, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_hkpg, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_pot, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($jum_setor, "2", ",", ".") . '</TD>
			<TD align="right" >' . number_format($saldo, "2", ",", ".") . '</TD>
							 </TR>';

			$cRet .= '</TABLE>';
		}
		$cRet .= '<TABLE width="100%" style="border-collapse:collapse; font-size:12px">
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $daerah . ', ' . $this->support->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>' . $nama . ' </u><br> ' . $pangkat . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><u>' . $nama1 . ' </u><br> ' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $nip . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';

		$data['prev'] = 'BUKU PAJAK PPN/PPh';
		switch ($ctk) {
			case 0;
				echo ("<title> BP Pajak</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, 'L', 0, '');
				break;
		}
	}

	function cetak_pajak3($lcskpd = '', $nbulan = '', $ctk = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $jns = '')
	{
		$spasi = $this->uri->segment(11);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}
		if ($jns == '2') {
			$querypaj = "SELECT SUM(terima) as terima, SUM(keluar) as keluar  FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL)	AND a.kd_skpd='$lcskpd'		
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL)	AND a.kd_skpd='$lcskpd'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)<'$nbulan'";
		} else {
			$querypaj = "SELECT SUM(terima) as terima, SUM(keluar) as keluar  FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek !='' AND nocek IS NOT NULL)	AND a.kd_skpd='$lcskpd'		
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek !='' AND nocek IS NOT NULL)	AND a.kd_skpd='$lcskpd'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)<'$nbulan'";
		}
		$querypjk = $this->db->query($querypaj);
		foreach ($querypjk->result() as $rowpjk) {
			$terima = $rowpjk->terima;
			$keluar = $rowpjk->keluar;
			$saldopjk = $terima - $keluar;
		}
		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%">
					<TR>
						<TD align="center" ><b>' . $prov . ' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>BUKU PAJAK </TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:14px" width="100%">
					 <TR>
						<TD align="left" width="20%" >OPD</TD>
						<TD align="left" width="100%" >: ' . $lcskpd . ' ' . $skpd . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala OPD</TD>
						<TD align="left">: ' . $nama . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: ' . $nama1 . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->getBulan($nbulan) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="0" cellpadding="' . $spasi . '" align=center>
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
						<TD width="120" align="right" >' . number_format($saldopjk) . '</TD>
					 </TR>';


		if ($jns == '2') {
			$query = $this->db->query("SELECT * FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL)	AND a.kd_skpd='$lcskpd'		
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL) AND a.kd_skpd='$lcskpd'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)='$nbulan' 
					ORDER BY tgl, Cast(bku as decimal)");
		} else {
			$query = $this->db->query("SELECT * FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek != '' AND nocek IS NOT NULL) AND a.kd_skpd='$lcskpd'		
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek != '' AND nocek IS NOT NULL) AND a.kd_skpd='$lcskpd'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)='$nbulan' 
					ORDER BY tgl, Cast(bku as decimal)");
		}
		$saldo = $saldopjk;
		$jumlahin = 0;
		$jumlahout = 0;
		foreach ($query->result() as $row) {
			$bukti = $row->bku;
			$tanggal = $row->tgl;
			$ket = $row->ket;
			$in = $row->terima;
			$out = $row->keluar;
			$saldo = $saldo + $row->terima - $row->keluar;
			$sal = empty($saldo) || $saldo == 0 ? '' : number_format($saldo, "2", ",", ".");
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
								<TD width="150" align="right" >' . number_format($jumlahin - $jumlahout + $saldopjk, "2", ",", ".") . '</TD>
							 </TR>';


		$cRet .= '</TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" width="100%">
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $daerah . ', ' . $this->support->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>' . $nama . '</u><br> ' . $pangkat . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><u>' . $nama1 . '</u><br> ' . $pangkat1 . ' </TD>
					</TR>
                    <TR>
						<TD align="center" >' . $nip . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';

		$data['prev'] = 'BUKU PAJAK PPN/PPh';
		switch ($ctk) {
			case 0;
				echo ("<title> BP Pajak</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
				break;
		}
	}

	function cetak_pajak4($lcskpd = '', $nbulan = '', $ctk = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $jns = '')
	{
		$spasi = $this->uri->segment(11);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat  FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}

		$cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" ><b>' . $prov . ' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>BUKU PAJAK </TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="20%" >OPD</TD>
						<TD align="left" width="100%" >: ' . $lcskpd . ' ' . $skpd . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala OPD</TD>
						<TD align="left">: ' . $nama . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: ' . $nama1 . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->getBulan($nbulan) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="' . $spasi . '" align=center>
					<THEAD>
					<TR>
						<TD rowspan="2" align="center" >NO</TD>
						<TD rowspan="2" align="center" >URAIAN</TD>						
						<TD colspan="3" align="center" >PENERIMAAN</TD>
						<TD colspan="3" align="center" >PENYETORAN</TD>
						<TD rowspan="2" align="center" >SISA BELUM DISETOR</TD>
					 </TR>
					 <TR>
						<TD  align="center" >S/D BULAN LALU</TD>
                        <TD  align="center" >BULAN INI</TD>
						<TD  align="center" >S/D BULAN INI</TD>						
						<TD  align="center" >S/D BULAN LALU</TD>
						<TD  align="center" >BULAN INI</TD>
						<TD  align="center" >S/D BULAN INI</TD>
					 </TR>
					  <TR>
						<TD  align="center" >1</TD>
                        <TD  align="center" >2</TD>
						<TD  align="center" >3</TD>						
						<TD  align="center" >4</TD>
						<TD  align="center" >5</TD>
						<TD  align="center" >6</TD>
						<TD  align="center" >7</TD>
						<TD  align="center" >8</TD>
						<TD  align="center" >9</TD>
					 </TR>
					 </THEAD>
					<TR>
						<TD  align="center" >&nbsp;</TD>
                        <TD  align="center" ></TD>
						<TD  align="center" ></TD>						
						<TD  align="center" ></TD>
						<TD  align="center" ></TD>
						<TD  align="center" ></TD>
						<TD  align="center" ></TD>
						<TD  align="center" ></TD>
						<TD  align="center" ></TD>
					 </TR>
					 ';


		if ($jns == '2') {
			$query = $this->db->query("	SELECT a.kd_rek6, a.nm_rek6, ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
											ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
											ISNULL(SUM(terima)-SUM(setor),0) as sisa
											FROM
											(SELECT RTRIM(kd_rek6) as kd_rek6,nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('2110501','2110701','2110702','2110703','2110901','210105010001','210105020001','210106010001','210105030001','2130501'))a
											LEFT JOIN 
											(SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
											SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
											SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN b.nilai ELSE 0 END) AS terima_ini,
											SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN b.nilai ELSE 0 END) AS terima,
											0 as setor_lalu,
											0 as setor_ini,
											0 as setor
											FROM trhtrmpot a
											INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
											LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
											WHERE (nocek = '' OR nocek IS NULL)
											AND	a.kd_skpd='$lcskpd'									
											GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

											UNION ALL

											SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
											0 as terima_lalu,
											0 as terima_ini,
											0 as terima,
											SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
											SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN b.nilai ELSE 0 END) AS setor_ini,
											SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN b.nilai ELSE 0 END) AS setor
											FROM trhstrpot a
											INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
											LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
											WHERE (nocek = '' OR nocek IS NULL)	
											AND	a.kd_skpd='$lcskpd'					
											GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b
											ON a.kd_rek6=b.kd_rek6
											GROUP BY a.kd_rek6, a.nm_rek6
											ORDER BY kd_rek6");
		} else if ($jns == '3') {
			$query = $this->db->query(" SELECT a.kd_rek6, a.nm_rek6, ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
											ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor,
											ISNULL(SUM(terima)-SUM(setor),0) as sisa
											FROM
											(SELECT RTRIM(kd_rek6) as kd_rek6,nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('2110501','2110701','2110702','2110703','2110901','210105010001','210105020001','210106010001','210105030001','2130501'))a
											LEFT JOIN 
											(SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
											SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
											SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN b.nilai ELSE 0 END) AS terima_ini,
											SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN b.nilai ELSE 0 END) AS terima,
											0 as setor_lalu,
											0 as setor_ini,
											0 as setor
											FROM trhtrmpot a
											INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
											LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
											WHERE (nocek !='' AND nocek IS NOT NULL)
											AND	a.kd_skpd='$lcskpd'									
											GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

											UNION ALL

											SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
											0 as terima_lalu,
											0 as terima_ini,
											0 as terima,
											SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
											SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN b.nilai ELSE 0 END) AS setor_ini,
											SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN b.nilai ELSE 0 END) AS setor
											FROM trhstrpot a
											INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
											LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
											WHERE (nocek !='' AND nocek IS NOT NULL)	
											AND	a.kd_skpd='$lcskpd'					
											GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b
											ON a.kd_rek6=b.kd_rek6
											GROUP BY a.kd_rek6, a.nm_rek6
											ORDER BY kd_rek6
											");
		} else {
			$query = $this->db->query("	SELECT a.kd_rek6, a.nm_rek6, ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
											ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
											ISNULL(SUM(terima)-SUM(setor),0) as sisa
											FROM
											(SELECT RTRIM(kd_rek6) as kd_rek6,nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('2110501','2110701','2110702','2110703','2110901','210105010001','210105020001','210106010001','210105030001','2130501'))a
											LEFT JOIN 
											(SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
											SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
											SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN b.nilai ELSE 0 END) AS terima_ini,
											SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN b.nilai ELSE 0 END) AS terima,
											0 as setor_lalu,
											0 as setor_ini,
											0 as setor
											FROM trhtrmpot a
											INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
											LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
											WHERE a.kd_skpd='$lcskpd'									
											GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

											UNION ALL

											SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
											0 as terima_lalu,
											0 as terima_ini,
											0 as terima,
											SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
											SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN b.nilai ELSE 0 END) AS setor_ini,
											SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN b.nilai ELSE 0 END) AS setor
											FROM trhstrpot a
											INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
											LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
											WHERE a.kd_skpd='$lcskpd'					
											GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b
											ON a.kd_rek6=b.kd_rek6
											GROUP BY a.kd_rek6, a.nm_rek6
											ORDER BY kd_rek6");
		}
		$i = 0;
		$jum_terima_lalu = 0;
		$jum_terima_ini = 0;
		$jum_terima = 0;
		$jum_setor_lalu = 0;
		$jum_setor_ini = 0;
		$jum_setor = 0;
		foreach ($query->result() as $row) {
			$i = $i + 1;
			$uraian = $row->nm_rek6;
			$terima_lalu = $row->terima_lalu;
			$terima_ini = $row->terima_ini;
			$terima = $row->terima;
			$setor_lalu = $row->setor_lalu;
			$setor_ini = $row->setor_ini;
			$setor = $row->setor;
			$sisa = $row->sisa;
			$jum_terima_lalu = $jum_terima_lalu + $terima_lalu;
			$jum_terima_ini = $jum_terima_ini + $terima_ini;
			$jum_terima = $jum_terima + $terima;
			$jum_setor_lalu = $jum_setor_lalu + $setor_lalu;
			$jum_setor_ini = $jum_setor_ini + $setor_ini;
			$jum_setor = $jum_setor + $setor;
			$cRet .= '<TR>
								<TD  align="left" >' . $i . '</TD>
                                <TD  align="left" >' . $uraian . '</TD>
								<TD  align="right" >' . number_format($terima_lalu, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($terima_ini, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($terima, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($setor_lalu, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($setor_ini, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($setor, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($sisa, "2", ",", ".") . '</TD>
							 </TR>';
		}

		$cRet .= '<TR>
								<TD colspan ="2"  align="center" >JUMLAH</TD>
								<TD  align="right" >' . number_format($jum_terima_lalu, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($jum_terima_ini, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($jum_terima, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($jum_setor_lalu, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($jum_setor_ini, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($jum_setor, "2", ",", ".") . '</TD>
								<TD  align="right" >' . number_format($jum_terima - $jum_setor, "2", ",", ".") . '</TD>
							 </TR>';


		$cRet .= '</TABLE>';

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
						<TD align="center" >' . $daerah . ', ' . $this->support->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>' . $nama . ' <br> ' . $pangkat . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><u>' . $nama1 . ' <br> ' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $nip . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';

		$data['prev'] = 'BUKU PAJAK PPN/PPh';
		switch ($ctk) {
			case 0;
				echo ("<title> BP Pajak</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, 'L', 0, '');
				break;
		}
	}


	function cetak_pajak5($lcskpd = '', $nbulan = '', $ctk = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $jns = '', $rinci = '', $pasal = '')
	{
		$spasi = $this->uri->segment(12);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}
		if ($jns == '2') {
			$querypaj = "SELECT SUM(terima) as terima, SUM(keluar) as keluar  FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL)	AND a.kd_skpd='$lcskpd'	
					AND b.kd_rek6='$pasal'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL)	AND a.kd_skpd='$lcskpd'
					AND b.kd_rek6='$pasal'					
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)<'$nbulan'";
		} else if ($jns == '2') {
			$querypaj = "SELECT SUM(terima) as terima, SUM(keluar) as keluar  FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek !='' AND nocek IS NOT NULL) AND a.kd_skpd='$lcskpd'
					AND b.kd_rek6='$pasal'
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek !='' AND nocek IS NOT NULL) AND a.kd_skpd='$lcskpd'	
					AND b.kd_rek6='$pasal'
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)<'$nbulan'";
		} else {
			$querypaj = "SELECT SUM(terima) as terima, SUM(keluar) as keluar  FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE  a.kd_skpd='$lcskpd'
					AND b.kd_rek6='$pasal'
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE  a.kd_skpd='$lcskpd'	
					AND b.kd_rek6='$pasal'
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)<'$nbulan'";
		}
		$querypjk = $this->db->query($querypaj);
		foreach ($querypjk->result() as $rowpjk) {
			$terima = $rowpjk->terima;
			$keluar = $rowpjk->keluar;
			$saldopjk = $terima - $keluar;
		}
		$cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" ><b>' . $prov . ' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>BUKU PAJAK </TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="20%" >OPD</TD>
						<TD align="left" width="100%" >: ' . $lcskpd . ' ' . $skpd . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala OPD</TD>
						<TD align="left">: ' . $nama . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: ' . $nama1 . '</TD>
					 </TR>
					 <TR>
						<TD align="left">Kode/Nama Rekening </TD>
						<TD align="left">: ' . $pasal . ' / ' . $this->tukd_model->get_nama($pasal, 'nm_rek6', 'ms_pot', 'kd_rek6') . ' </TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->getBulan($nbulan) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="' . $spasi . '" align=center>
					<THEAD>
					<TR>
						<TD width="20" align="center" >No Urut</TD>
                        <TD width="90" align="center" >Tanggal</TD>
						<TD width="50" align="center" >No. Buku Kas</TD>						
						<TD width="400" align="center" >Uraian</TD>						
						<TD width="150" align="center" >Pomotongan (Rp)</TD>
						<TD width="150" align="center" >Penyetoran (Rp)</TD>
					 </TR>
					 <TR>
						<TD align="center" >1</TD>
                        <TD align="center" >2</TD>
                        <TD align="center" >3</TD>
						<TD align="center" >4</TD>						
						<TD align="center" >5</TD>
						<TD align="center" >6</TD>
					 </TR>
					 </THEAD>
					 ';


		if ($jns == '2') {
			$query = $this->db->query("SELECT * FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL)	AND a.kd_skpd='$lcskpd'	
					AND b.kd_rek6='$pasal'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek = '' OR nocek IS NULL)	AND a.kd_skpd='$lcskpd'
					AND b.kd_rek6='$pasal'
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)='$nbulan' 
					ORDER BY tgl, Cast(bku as decimal)");
		} elseif ($jns == '3') {
			$query = $this->db->query("SELECT * FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek != '' AND nocek IS NOT NULL) AND a.kd_skpd='$lcskpd'
					AND b.kd_rek6='$pasal'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE (nocek != '' AND nocek IS NOT NULL) AND a.kd_skpd='$lcskpd'	
					AND b.kd_rek6='$pasal'
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)='$nbulan' 
					ORDER BY tgl, Cast(bku as decimal)");
		} else {
			$query = $this->db->query("SELECT * FROM
					(
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  no sp2d:'+a.no_sp2d) AS ket,
					SUM(b.nilai) AS terima,'0' AS keluar,'1' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhtrmpot a
					INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE a.kd_skpd='$lcskpd'
					AND b.kd_rek6='$pasal'	
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, c.no_sp2d, nocek
					UNION ALL
					SELECT a.no_bukti AS bku,a.tgl_bukti AS tgl,(ket+'  terima:'+a.no_terima) AS ket,
					'0' AS terima,SUM(b.nilai) AS keluar,'2' as jns,a.kd_skpd, c.no_sp2d, c.nocek
					FROM trhstrpot a
					INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
					WHERE a.kd_skpd='$lcskpd'	
					AND b.kd_rek6='$pasal'
					GROUP BY a.no_bukti, a.tgl_bukti, a.ket, a.no_sp2d, a.kd_skpd, a.no_terima, c.no_sp2d, nocek
					) z WHERE MONTH(tgl)='$nbulan' 
					ORDER BY tgl, Cast(bku as decimal)");
		}
		$saldo = $saldopjk;
		$jumlahin = 0;
		$jumlahout = 0;
		$i = 0;
		foreach ($query->result() as $row) {
			$i = $i + 1;
			$bukti = $row->bku;
			$tanggal = $row->tgl;
			$ket = $row->ket;
			$in = $row->terima;
			$out = $row->keluar;
			$saldo = $saldo + $row->terima - $row->keluar;
			$sal = empty($saldo) || $saldo == 0 ? '' : number_format($saldo, "2", ",", ".");
			$jumlahin = $jumlahin + $in;
			$jumlahout = $jumlahout + $out;
			$cRet .= '<TR>
								<TD align="left" >' . $i . '</TD>
                                <TD align="left" >' . $this->support->tanggal_format_indonesia($tanggal) . '</TD>
								<TD align="left" >' . $bukti . '</TD>								
								<TD align="left" >' . $ket . '</TD>								
								<TD align="right" >' . number_format($in, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($out, "2", ",", ".") . '</TD>
							 </TR>';
		}

		$cRet .= '<TR>
							<TD colspan ="4" align="left" >JUMLAH BULAN INI<br> JUMLAH S/D BULAN LALU <br>JUMLAH SELURUHNYA</TD>
							<TD align="right" >' . number_format($jumlahin, "2", ",", ".") . ' <br> ' . number_format($terima, "2", ",", ".") . ' 
							<br> ' . number_format($terima + $jumlahin, "2", ",", ".") . '  </TD>
							<TD align="right" >' . number_format($jumlahout, "2", ",", ".") . '<br> ' . number_format($keluar, "2", ",", ".") . '
							<br> ' . number_format($keluar + $jumlahout, "2", ",", ".") . '  </TD>

						 </TR>
						 <TR>
							<TD colspan ="4" align="left" >SISA YANG BELUM DISETOR</TD>
							<TD colspan ="2" align="right" >' . number_format(($terima + $jumlahin) - ($keluar + $jumlahout), "2", ",", ".") . '</TD>
						 </TR>	 ';


		$cRet .= '</TABLE>';

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
						<TD align="center" >' . $daerah . ', ' . $this->support->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>' . $nama . '<br>' . $pangkat . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><u>' . $nama1 . '<br>' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $nip . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';

		$data['prev'] = 'BUKU PAJAK PPN/PPh';
		switch ($ctk) {
			case 0;
				echo ("<title> BP Pajak</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
				break;
		}
	}


	////////////////////
}
