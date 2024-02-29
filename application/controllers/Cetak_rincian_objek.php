<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class cetak_rincian_objek extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}


	function index()
	{
		$data['page_title'] = 'RINCIAN PEROBJEK';
		$this->template->set('title', 'RINCIAN PEROBJEK');
		$this->template->load('template', 'tukd/transaksi/rincian_objek', $data);
		// $this->template->load('template','tukd/spp/maintenance',$data) ;
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

	function load_ttd2($ttd)
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

	function ld_giat_rinci_objek($skpd = '')
	{
		$skpd = $this->session->userdata('kdskpd');
		$lccr = $this->input->post('q');
		$sql = "SELECT DISTINCT kd_sub_kegiatan,nm_sub_kegiatan FROM trdrka where kd_skpd='$skpd' and (upper(kd_sub_kegiatan) like upper('%$lccr%') or upper(nm_sub_kegiatan) like upper('%$lccr%')) order by kd_sub_kegiatan ";
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

	function ld_rek_rinci_objek($giat = '')
	{
		$lccr = $this->input->post('q');
		$sql = " SELECT DISTINCT kd_rek6, nm_rek6 FROM trdrka where kd_sub_kegiatan='$giat' and (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) order by kd_rek6 ";
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
		$query1->free_result();
	}

	function ctk_rincian_objek($dcetak = '', $ttd1 = '', $skpd = '', $rek5 = '', $dcetak2 = '', $giat = '', $tgl_ctk = '', $ttd2 = '', $ctk = '')
	{
		$spasi = $this->uri->segment(12);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$skpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$nm_prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$skpd' and kode in ('PA','KPA') and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where  kode='BK' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}
		if ($giat <> '') {
			$keg = '1';
			$giat = $giat;
			$nm_giat = $this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
			//$nm_prov=$this->tukd_model->get_sclient('provinsi','sclient');
		} else {
			$keg = '0';
			$giat = '';
			$nm_giat = 'KESELURUHAN';
		}

		//echo $dcetak .'/'. $ttd.'/'.$skpd.'/'.$rek5.'/'.$dcetak2.'/'.$giat.'/'.$keg;


		$cRet = '<TABLE width="100%">
					 <TR>                        
						<TD colspan="2" align="center" ><b>' . $nm_prov . '</TD>					
					 </TR>
					 <TR>                        
						<TD colspan="2" align="center" ><b>BUKU PEMBANTU RINCIAN OBJEK </TD>					
					 </TR>
					 </TABLE>
					 <TABLE style="font-size:12px" width="100%">
 					 <TR>                        
						<TD colspan="2" align="center" >&nbsp; </TD>					
					 </TR>
 					 <TR>                        
						<TD colspan="2" align="center" >&nbsp;</TD>					
					 </TR>
                     <TR>                        
						<TD align="left" width="15%" >OPD </TD>
						<TD align="left" width="85%" >: ' . $skpd . ' ' . $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
                     <TR>
						<TD align="left" width="15%" >Sub Kegiatan</TD>
						<TD align="left" width="85%" >: ' . $giat . ' ' . $nm_giat . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="15%" >Rekening </TD>
						<TD align="left" width="85%" >: ' . $rek5 . ' ' . $this->tukd_model->get_nama($rek5, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="15%" >Periode</TD>
						<TD align="left" width="85%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
                     <TR>                        
						<TD colspan="2" align="center" >&nbsp;</TD>					
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" border="1" cellspacing="0" cellpadding="' . $spasi . '" width="100%" >
					<THEAD>
					 <TR>
						<TD width="40%" rowspan="2" colspan="2"  align="center" ><b>Nomor dan Tanggal BKU</b></TD>
						<TD width="60%" colspan="4"  align="center" ><b>Pengeluaran (Rp)</b></TD>					
					 </TR>
                     <TR>
 		                <TD width="15%" align="center"><b>LS</b></TD>
                        <TD width="15%"  align="center"><b>UP/GU</b></TD>
                        <TD width="15%"  align="center"><b>TU</b></TD>
                        <TD width="15%"  align="center"><b>JUMLAH</b></TD>
                     </TR>
					 </THEAD>';

		$query = $this->db->query("SELECT ISNULL(a.no_bukti,'') as no_bukti
												,b.tgl_bukti
												,ISNULL(a.no_sp2d,'') as no_sp2d
												,SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS ls
												FROM trdtransout a 
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan='$giat' 
												and a.kd_rek6='$rek5' 
												AND b.kd_skpd='$skpd' 
												and b.tgl_bukti>='$dcetak' 
												and b.tgl_bukti<='$dcetak2' 
												GROUP BY a.no_bukti, b.tgl_bukti,a.no_sp2d
												ORDER BY b.tgl_bukti,a.no_bukti
												");
		$i = 0;
		$jumls = 0;
		$jumup = 0;
		$jumgu = 0;
		$jml = 0;
		foreach ($query->result_array() as $res) {
			$cetak[1] = empty($res['no_bukti']) || $res['no_bukti'] == null ? '&nbsp;' : $res['no_bukti'];
			$cetak[2] = empty($res['no_sp2d']) || $res['no_sp2d'] == null ? '&nbsp;' : $res['no_sp2d'];
			$cetak[3] = empty($res['ls']) || $res['ls'] == null ? '&nbsp;' : $res['ls'];
			$cetak[4] = empty($res['up']) || $res['up'] == null ? 0 : $res['up'];
			$cetak[5] = empty($res['gu']) || $res['gu'] == null ? 0 : $res['gu'];
			$cetak[6] = empty($res['tgl_bukti']) || $res['tgl_bukti'] == null ? 0 : $res['tgl_bukti'];
			$cRet .= '<tr>
    								<td style="border-bottom:hidden;border-right:hidden;" align="left" ><b>&nbsp;' . $cetak[1] . '</b> </td>
    								<td style="border-bottom:hidden;border-left:hidden;" align="right" >' . $this->tukd_model->tanggal_format_indonesia($cetak[6]) . '&nbsp;</td>
									<td rowspan="2" align="right" >' . number_format($cetak[3], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[4], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[5], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[3] + $cetak[4] + $cetak[5], "2", ",", ".") . '</td></tr>
								 <tr>
									<td colspan="2" align="left" ><i>&nbsp;SP2D: ' . $cetak[2] . '</i> </td>
    								
    							 </tr>';

			$jumls = $jumls + $cetak[3];
			$jumup = $jumup + $cetak[4];
			$jumgu = $jumgu + $cetak[5];
			$jml = $jml + $cetak[3] + $cetak[4] + $cetak[5];
		}


		$cRet .= '<TR>				
					<TD colspan="2" align="left" ><i><b>Jumlah</i></b></TD>
					<TD align="right" ><b>' . number_format($jumls, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jumup, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jumgu, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jml, "2", ",", ".") . '</b></TD>					
				 </TR>';

		$query = $this->db->query("SELECT SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS lalu_up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS lalu_gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS lalu_ls
												FROM trdtransout a 
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan='$giat' 
												and a.kd_rek6='$rek5' 
												AND b.kd_skpd='$skpd' 
												and b.tgl_bukti<'$dcetak' 
												");
		foreach ($query->result_array() as $res) {
			$lalu_up = $res['lalu_up'];
			$lalu_gu = $res['lalu_gu'];
			$lalu_ls = $res['lalu_ls'];
		}
		$jml_lalu = $lalu_up + $lalu_gu + $lalu_ls;
		$tot = $jumup + $lalu_up;
		$tot1 = $jumgu + $lalu_gu;
		$tot2 = $jumls + $lalu_ls;
		$total = $tot + $tot1 + $tot2;
		$cRet .= '<TR>				
					<TD colspan="2" align="left" ><i><b>Jumlah s/d periode lalu </i></b></TD>
					<TD align="right" ><b>' . number_format($lalu_ls, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($lalu_up, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($lalu_gu, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jml_lalu, "2", ",", ".") . '</b></TD>					
				 </TR>';
		$cRet .= '<TR>				
					<TD colspan="2" align="left" ><b><i>Jumlah s/d periode ini<i></b></TD>
					<TD align="right" ><b>' . number_format($tot2, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($tot, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($tot1, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($total, "2", ",", ".") . '</b></TD>					
				 </TR>';
		$cRet .= '</TABLE>';
		$cRet .= '<TABLE style="font-size:12px" width="100%" border="0">
					<TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">Mengetahui,</TD>
						<TD align="center" width="50%">' . $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">' . $jabatan . '</TD>
						<TD align="center" width="50%">' . $jabatan1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><u><b>' . $nama . '</b></u><br>' . $pangkat . '</TD>
						<TD align="center" width="50%"><u><b>' . $nama1 . '</b></u><br>' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">' . $nip . '</TD>
						<TD align="center" width="50%">' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';
		$data['prev'] = 'RINCIAN OBJEK';
		switch ($ctk) {
			case 0;
				echo ("<title> BP RINCIAN OBJEK</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf_margin('', $cRet, 10, 10, 10, 'P', 0, '', 15, 15, 15, 15);
				break;
		}
	}


	function cetak_rincian_objek_kegiatan($dcetak = '', $ttd1 = '', $skpd = '', $dcetak2 = '', $giat = '', $tgl_ctk = '', $ttd2 = '', $ctk = '')
	{
		//$this->load->library('mpdf');
		//$this->mpdf = new mPDF('utf-8', array(215,330),12); //folio

		$spasi = $this->uri->segment(11);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$skpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$nm_prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$skpd' and kode in ('PA','KPA') and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$skpd' and kode='BK' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}
		$cRet = '<TABLE style="font-size:16px" width="100%" border="0">
					<TR>
						<TD align="center" width="100%"><BR><BR><BR><b>CETAK BUKU RINCIAN OBJEK<BR>
						KEGIATAN ' . strtoupper($this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan')) . '<BR>
						' . strtoupper($this->tukd_model->tanggal_format_indonesia($dcetak)) . ' s/d ' . strtoupper($this->tukd_model->tanggal_format_indonesia($dcetak2)) . '</TD>
					</TR>
				</TABLE>';

		$sqlww = $this->db->query("SELECT b.kd_rek6 FROM trhtransout a LEFT JOIN trdtransout b ON a.no_bukti=b.no_bukti
			AND a.kd_skpd = b.kd_skpd
			WHERE a.tgl_bukti<='$dcetak2' 
			AND a.kd_skpd='$skpd' 
			AND b.kd_sub_kegiatan='$giat' 
			GROUP BY b.kd_rek6 ORDER BY b.kd_rek6");
		foreach ($sqlww->result() as $row) {
			$rek5 = $row->kd_rek6;
			$nm_giat = $this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
			$nm_giat = $this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
			//$nm_prov=$this->tukd_model->get_sclient('provinsi','sclient');


			//echo $rek5;

			$cRet .= '		
		<pagebreak type="NEXT-ODD" resetpagenum="1" pagenumstyle="1" suppress="off" />
		<TABLE width="100%">
					 <TR>                        
						<TD colspan="2" align="center" ><b>' . $nm_prov . '</TD>					
					 </TR>
					 <TR>                        
						<TD colspan="2" align="center" ><b>BUKU PEMBANTU RINCIAN OBJEK </TD>					
					 </TR>
					 </TABLE>
					 <TABLE style="font-size:12px" width="100%">
 					 <TR>                        
						<TD colspan="2" align="center" >&nbsp; </TD>					
					 </TR>
 					 <TR>                        
						<TD colspan="2" align="center" >&nbsp;</TD>					
					 </TR>
                     <TR>                        
						<TD align="left" width="15%" >OPD </TD>
						<TD align="left" width="85%" >: ' . $skpd . ' ' . $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
                     <TR>
						<TD align="left" width="15%" >Sub Kegiatan</TD>
						<TD align="left" width="85%" >: ' . $giat . ' ' . $nm_giat . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="15%" >Rekening </TD>
						<TD align="left" width="85%" >: ' . $rek5 . ' ' . $this->tukd_model->get_nama($rek5, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="15%" >Periode</TD>
						<TD align="left" width="85%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
                     <TR>                        
						<TD colspan="2" align="center" >&nbsp;</TD>					
					 </TR>
					 </TABLE>';
			$cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" border="1" cellspacing="0" cellpadding="' . $spasi . '" width="100%" >
					<THEAD>
					 <TR>
						<TD width="40%" rowspan="2" colspan="2"  align="center" ><b>Nomor dan Tanggal BKU</b></TD>
						<TD width="60%" colspan="4"  align="center" ><b>Pengeluaran (Rp)</b></TD>					
					 </TR>
                     <TR>
 		                <TD width="15%" align="center"><b>LS</b></TD>
                        <TD width="15%"  align="center"><b>UP/GU</b></TD>
                        <TD width="15%"  align="center"><b>TU</b></TD>
                        <TD width="15%"  align="center"><b>JUMLAH</b></TD>
                     </TR>
					 </THEAD>';

			$query = $this->db->query("SELECT ISNULL(a.no_bukti,'') as no_bukti
												,b.tgl_bukti
												,ISNULL(a.no_sp2d,'') as no_sp2d
												,SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS ls
												FROM trdtransout a 
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan='$giat' 
												and a.kd_rek6='$rek5' 
												AND b.kd_skpd='$skpd' 
												and b.tgl_bukti>='$dcetak' 
												and b.tgl_bukti<='$dcetak2' 
												GROUP BY a.no_bukti, b.tgl_bukti,a.no_sp2d
												ORDER BY b.tgl_bukti,a.no_bukti
												");
			$i = 0;
			$jumls = 0;
			$jumup = 0;
			$jumgu = 0;
			$jml = 0;
			foreach ($query->result_array() as $res) {
				$cetak[1] = empty($res['no_bukti']) || $res['no_bukti'] == null ? '&nbsp;' : $res['no_bukti'];
				$cetak[2] = empty($res['no_sp2d']) || $res['no_sp2d'] == null ? '&nbsp;' : $res['no_sp2d'];
				$cetak[3] = empty($res['ls']) || $res['ls'] == null ? '&nbsp;' : $res['ls'];
				$cetak[4] = empty($res['up']) || $res['up'] == null ? 0 : $res['up'];
				$cetak[5] = empty($res['gu']) || $res['gu'] == null ? 0 : $res['gu'];
				$cetak[6] = empty($res['tgl_bukti']) || $res['tgl_bukti'] == null ? 0 : $res['tgl_bukti'];
				$cRet .= '<tr>
    								<td style="border-bottom:hidden;border-right:hidden;" align="left" ><b>&nbsp;' . $cetak[1] . '</b> </td>
    								<td style="border-bottom:hidden;border-left:hidden;" align="right" >' . $this->tukd_model->tanggal_format_indonesia($cetak[6]) . '&nbsp;</td>
									<td rowspan="2" align="right" >' . number_format($cetak[3], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[4], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[5], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[3] + $cetak[4] + $cetak[5], "2", ",", ".") . '</td></tr>
								 <tr>
									<td colspan="2" align="left" ><i>&nbsp;SP2D: ' . $cetak[2] . '</i> </td>
    								
    							 </tr>';

				$jumls = $jumls + $cetak[3];
				$jumup = $jumup + $cetak[4];
				$jumgu = $jumgu + $cetak[5];
				$jml = $jml + $cetak[3] + $cetak[4] + $cetak[5];
			}


			$cRet .= '<TR>				
					<TD colspan="2" align="left" ><i><b>Jumlah</i></b></TD>
					<TD align="right" ><b>' . number_format($jumls, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jumup, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jumgu, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jml, "2", ",", ".") . '</b></TD>					
				 </TR>';

			$query = $this->db->query("SELECT SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS lalu_up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS lalu_gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS lalu_ls
												FROM trdtransout a 
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan='$giat' 
												and a.kd_rek6='$rek5' 
												AND b.kd_skpd='$skpd' 
												and b.tgl_bukti<'$dcetak' 
												");
			foreach ($query->result_array() as $res) {
				$lalu_up = $res['lalu_up'];
				$lalu_gu = $res['lalu_gu'];
				$lalu_ls = $res['lalu_ls'];
			}
			$jml_lalu = $lalu_up + $lalu_gu + $lalu_ls;
			$tot = $jumup + $lalu_up;
			$tot1 = $jumgu + $lalu_gu;
			$tot2 = $jumls + $lalu_ls;
			$total = $tot + $tot1 + $tot2;
			$cRet .= '<TR>				
					<TD colspan="2" align="left" ><i><b>Jumlah s/d periode lalu </i></b></TD>
					<TD align="right" ><b>' . number_format($lalu_ls, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($lalu_up, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($lalu_gu, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jml_lalu, "2", ",", ".") . '</b></TD>					
				 </TR>';
			$cRet .= '<TR>				
					<TD colspan="2" align="left" ><b><i>Jumlah s/d periode ini<i></b></TD>
					<TD align="right" ><b>' . number_format($tot2, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($tot, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($tot1, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($total, "2", ",", ".") . '</b></TD>					
				 </TR>';
			$cRet .= '</TABLE>';
			$cRet .= '<TABLE style="font-size:12px" width="100%" border="0">
					<TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">Mengetahui,</TD>
						<TD align="center" width="50%">' . $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">' . $jabatan . '</TD>
						<TD align="center" width="50%">' . $jabatan1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><u><b>' . $nama . '</b></u><br>' . $pangkat . '</TD>
						<TD align="center" width="50%"><u><b>' . $nama1 . '</b></u><br>' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">' . $nip . '</TD>
						<TD align="center" width="50%">' . $nip1 . '</TD>
					</TR>
					</TABLE></pagebreak>';
			//tambahkan bila tak ingin menggunakan mpdf
			//$this->mpdf->AddPage('P','',1,'1','off');
			//$this->mpdf->writeHTML($cRet);         

		}

		$data['prev'] = 'RINCIAN OBJEK';
		//tambahkan bila tak ingin mpdf
		//$this->mpdf->Output();
		switch ($ctk) {
			case 0;
				echo ("<title> BP RINCIAN OBJEK</title>");
				echo $cRet;
				break;
			case 1;
				//$this->support->_mpdf('',$cRet,10,10,10,'0',0,'');
				$this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
				break;
		}
	}



	function cetak_rincian_objek_all($dcetak = '', $ttd1 = '', $skpd = '', $dcetak2 = '', $tgl_ctk = '', $ttd2 = '', $ctk = '')
	{
		//$this->load->library('mpdf');
		//$this->mpdf = new mPDF('utf-8', array(215,330),12); //folio

		$spasi = $this->uri->segment(10);
		$ttd1 = str_replace('123456789', ' ', $ttd1);
		$ttd2 = str_replace('123456789', ' ', $ttd2);
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$skpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$nm_prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$skpd' and kode in ('PA','KPA') and nip='$ttd2'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$skpd' and kode='BK' and nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip1 = $rowttd->nip;
			$nama1 = $rowttd->nm;
			$jabatan1  = $rowttd->jab;
			$pangkat1  = $rowttd->pangkat;
		}
		$cRet = '<TABLE style="font-size:16px" width="100%" border="0">
					<TR>
						<TD align="center" width="100%"><BR><BR><BR><b>CETAK BUKU RINCIAN OBJEK<BR>
						' . strtoupper($this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd')) . '<BR>
						' . strtoupper($this->tukd_model->tanggal_format_indonesia($dcetak)) . ' s/d ' . strtoupper($this->tukd_model->tanggal_format_indonesia($dcetak2)) . '</TD>
					</TR>
				</TABLE>';

		$sqlww = $this->db->query("SELECT b.kd_sub_kegiatan,b.kd_rek6 FROM trhtransout a LEFT JOIN trdtransout b ON a.no_bukti=b.no_bukti
			AND a.kd_skpd = b.kd_skpd
			WHERE a.tgl_bukti<='$dcetak2' 
			AND a.kd_skpd='$skpd' 
			GROUP BY b.kd_sub_kegiatan,b.kd_rek6 ORDER BY b.kd_sub_kegiatan,b.kd_rek6");
		foreach ($sqlww->result() as $row) {
			$giat = $row->kd_sub_kegiatan;
			$rek5 = $row->kd_rek6;
			$nm_giat = $this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
			$nm_giat = $this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
			//$nm_prov=$this->tukd_model->get_sclient('provinsi','sclient');


			//echo $rek5;

			$cRet .= '		
		<pagebreak type="NEXT-ODD" resetpagenum="1" pagenumstyle="1" suppress="off" />
		<TABLE width="100%">
					 <TR>                        
						<TD colspan="2" align="center" ><b>' . $nm_prov . '</TD>					
					 </TR>
					 <TR>                        
						<TD colspan="2" align="center" ><b>BUKU PEMBANTU RINCIAN OBJEK </TD>					
					 </TR>
					 </TABLE>
					 <TABLE style="font-size:12px" width="100%">
 					 <TR>                        
						<TD colspan="2" align="center" >&nbsp; </TD>					
					 </TR>
 					 <TR>                        
						<TD colspan="2" align="center" >&nbsp;</TD>					
					 </TR>
                     <TR>                        
						<TD align="left" width="15%" >OPD </TD>
						<TD align="left" width="85%" >: ' . $skpd . ' ' . $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
                     <TR>
						<TD align="left" width="15%" >Sub Kegiatan</TD>
						<TD align="left" width="85%" >: ' . $giat . ' ' . $nm_giat . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="15%" >Rekening </TD>
						<TD align="left" width="85%" >: ' . $rek5 . ' ' . $this->tukd_model->get_nama($rek5, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="15%" >Periode</TD>
						<TD align="left" width="85%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
                     <TR>                        
						<TD colspan="2" align="center" >&nbsp;</TD>					
					 </TR>
					 </TABLE>';
			$cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" border="1" cellspacing="0" cellpadding="' . $spasi . '" width="100%" >
					<THEAD>
					 <TR>
						<TD width="40%" rowspan="2" colspan="2"  align="center" ><b>Nomor dan Tanggal BKU</b></TD>
						<TD width="60%" colspan="4"  align="center" ><b>Pengeluaran (Rp)</b></TD>					
					 </TR>
                     <TR>
 		                <TD width="15%" align="center"><b>LS</b></TD>
                        <TD width="15%"  align="center"><b>UP/GU</b></TD>
                        <TD width="15%"  align="center"><b>TU</b></TD>
                        <TD width="15%"  align="center"><b>JUMLAH</b></TD>
                     </TR>
					 </THEAD>';

			$query = $this->db->query("SELECT ISNULL(a.no_bukti,'') as no_bukti
												,b.tgl_bukti
												,ISNULL(a.no_sp2d,'') as no_sp2d
												,SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS ls
												FROM trdtransout a 
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan='$giat' 
												and a.kd_rek6='$rek5' 
												AND b.kd_skpd='$skpd' 
												and b.tgl_bukti>='$dcetak' 
												and b.tgl_bukti<='$dcetak2' 
												GROUP BY a.no_bukti, b.tgl_bukti,a.no_sp2d
												ORDER BY b.tgl_bukti,a.no_bukti
												");
			$i = 0;
			$jumls = 0;
			$jumup = 0;
			$jumgu = 0;
			$jml = 0;
			foreach ($query->result_array() as $res) {
				$cetak[1] = empty($res['no_bukti']) || $res['no_bukti'] == null ? '&nbsp;' : $res['no_bukti'];
				$cetak[2] = empty($res['no_sp2d']) || $res['no_sp2d'] == null ? '&nbsp;' : $res['no_sp2d'];
				$cetak[3] = empty($res['ls']) || $res['ls'] == null ? '&nbsp;' : $res['ls'];
				$cetak[4] = empty($res['up']) || $res['up'] == null ? 0 : $res['up'];
				$cetak[5] = empty($res['gu']) || $res['gu'] == null ? 0 : $res['gu'];
				$cetak[6] = empty($res['tgl_bukti']) || $res['tgl_bukti'] == null ? 0 : $res['tgl_bukti'];
				$cRet .= '<tr>
    								<td style="border-bottom:hidden;border-right:hidden;" align="left" ><b>&nbsp;' . $cetak[1] . '</b> </td>
    								<td style="border-bottom:hidden;border-left:hidden;" align="right" >' . $this->tukd_model->tanggal_format_indonesia($cetak[6]) . '&nbsp;</td>
									<td rowspan="2" align="right" >' . number_format($cetak[3], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[4], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[5], "2", ",", ".") . '</td>
    								<td rowspan="2" align="right" >' . number_format($cetak[3] + $cetak[4] + $cetak[5], "2", ",", ".") . '</td></tr>
								 <tr>
									<td colspan="2" align="left" ><i>&nbsp;SP2D: ' . $cetak[2] . '</i> </td>
    								
    							 </tr>';

				$jumls = $jumls + $cetak[3];
				$jumup = $jumup + $cetak[4];
				$jumgu = $jumgu + $cetak[5];
				$jml = $jml + $cetak[3] + $cetak[4] + $cetak[5];
			}


			$cRet .= '<TR>				
					<TD colspan="2" align="left" ><i><b>Jumlah</i></b></TD>
					<TD align="right" ><b>' . number_format($jumls, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jumup, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jumgu, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jml, "2", ",", ".") . '</b></TD>					
				 </TR>';

			$query = $this->db->query("SELECT SUM(CASE WHEN jns_spp IN ('1','2') THEN a.nilai ELSE 0 END) AS lalu_up
												,SUM(CASE WHEN jns_spp IN ('3') THEN a.nilai ELSE 0 END) AS lalu_gu
												,SUM(CASE WHEN jns_spp IN ('4','5','6') THEN a.nilai ELSE 0 END) AS lalu_ls
												FROM trdtransout a 
												LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd = b.kd_skpd
												WHERE a.kd_sub_kegiatan='$giat' 
												and a.kd_rek6='$rek5' 
												AND b.kd_skpd='$skpd' 
												and b.tgl_bukti<'$dcetak' 
												");
			foreach ($query->result_array() as $res) {
				$lalu_up = $res['lalu_up'];
				$lalu_gu = $res['lalu_gu'];
				$lalu_ls = $res['lalu_ls'];
			}
			$jml_lalu = $lalu_up + $lalu_gu + $lalu_ls;
			$tot = $jumup + $lalu_up;
			$tot1 = $jumgu + $lalu_gu;
			$tot2 = $jumls + $lalu_ls;
			$total = $tot + $tot1 + $tot2;
			$cRet .= '<TR>				
					<TD colspan="2" align="left" ><i><b>Jumlah s/d periode lalu </i></b></TD>
					<TD align="right" ><b>' . number_format($lalu_ls, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($lalu_up, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($lalu_gu, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($jml_lalu, "2", ",", ".") . '</b></TD>					
				 </TR>';
			$cRet .= '<TR>				
					<TD colspan="2" align="left" ><b><i>Jumlah s/d periode ini<i></b></TD>
					<TD align="right" ><b>' . number_format($tot2, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($tot, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($tot1, "2", ",", ".") . '</b></TD>
					<TD align="right" ><b>' . number_format($total, "2", ",", ".") . '</b></TD>					
				 </TR>';
			$cRet .= '</TABLE>';
			$cRet .= '<TABLE style="font-size:12px" width="100%" border="0">
					<TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">Mengetahui,</TD>
						<TD align="center" width="50%">' . $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">' . $jabatan . '</TD>
						<TD align="center" width="50%">' . $jabatan1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"><u><b>' . $nama . '</b></u><br>' . $pangkat . '</TD>
						<TD align="center" width="50%"><u><b>' . $nama1 . '</b></u><br>' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%">' . $nip . '</TD>
						<TD align="center" width="50%">' . $nip1 . '</TD>
					</TR>
					</TABLE></pagebreak>';
			//tambahkan bila tak ingin menggunakan mpdf
			//$this->mpdf->AddPage('P','',1,'1','off');
			//$this->mpdf->writeHTML($cRet);         

		}

		$data['prev'] = 'RINCIAN OBJEK';
		//tambahkan bila tak ingin mpdf
		//$this->mpdf->Output();
		switch ($ctk) {
			case 0;
				echo ("<title> BP RINCIAN OBJEK</title>");
				echo $cRet;
				break;
			case 1;
				//$this->support->_mpdf('',$cRet,10,10,10,'0',0,'');
				$this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
				break;
		}
	}

	function  tanggal_format_indonesia($tgl)
	{
		$tanggal  = explode('-', $tgl);
		$bulan  = $this->support->getBulan($tanggal[1]);
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

	function cetak_cek_rekening($skpd = '', $giat = '', $kdrek = '', $ctk = '', $anggaran = '')
	{

		$this->load->library('status_anggarans');



		$cRet = '';
		$cRet .= '<TABLE style="font-size:16px" width="100%" border="0">
					<TR>
						<TD align="center" width="100%"><BR><BR><BR><b>CETAK BUKU SUB RINCIAN OBJEK<BR>
						KEGIATAN ' . strtoupper($this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan')) . '<BR>
                        REKENING ' . strtoupper($this->tukd_model->get_nama($kdrek, 'nm_rek6', 'ms_rek6', 'kd_rek6')) . '
						</TD>
					</TR>
				    </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" border="1" cellspacing="0" cellpadding="1" width="100%" >
					<THEAD>
					 <TR>
						<TD align="center" ><b>No SP2D/ No SPP /No Tagih/ No Bukti</b></TD>
						<TD align="center" ><b>Tanggal</b></TD>
						<TD align="center" ><b>Ket</b></TD>
						<TD align="center" ><b>Nilai</b></TD>		
					 </TR>
					 </THEAD>';


		$sqlww = $this->db->query("SELECT 1 [no],b.no_sp2d [no1],b.tgl_sp2d [tgl],b.keperluan [ket],a.nilai [nilai] 
        from trdspp a join trhsp2d b on a.no_spp=b.no_spp where b.jns_spp not  in ('1','2')
        and a.kd_sub_kegiatan='$giat' and a.kd_rek6='$kdrek' and b.kd_skpd='$skpd' and (b.sp2d_batal is null or b.sp2d_batal<>'1')
        union all        
        select 2 [no],b.no_spp [no1],b.tgl_spp [tgl],b.keperluan [ket],a.nilai [nilai] 
        from trdspp a join trhspp b on a.no_spp=b.no_spp where b.jns_spp not  in ('1','2')
        and a.no_spp  not in(select no_spp from trhsp2d where kd_skpd='$skpd')
        and a.kd_sub_kegiatan='$giat' and a.kd_rek6='$kdrek' and b.kd_skpd='$skpd' and (b.sp2d_batal is null or b.sp2d_batal<>'1')
        union all
        select  3 [no],b.no_bukti [no1],a.tgl_bukti [tgl],a.ket [ket],b.nilai [nilai] from trhtagih a join trdtagih b on a.kd_skpd=b.kd_skpd and a.no_bukti=b.no_bukti 
        where b.kd_sub_kegiatan='$giat' and kd_rek='$kdrek' and a.kd_skpd='$skpd' and a.no_bukti not in(select no_tagih from trhspp where kd_skpd='$skpd') 
        union all
        select 4 [no],a.no_bukti [no1],b.tgl_bukti [tgl],b.ket,a.nilai from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
        where b.jns_spp in ('1','2') and a.kd_sub_kegiatan='$giat' and b.kd_skpd='$skpd' and a.kd_rek6='$kdrek' order by [no],tgl,no1");
		$tnilai = 0;
		foreach ($sqlww->result() as $row) {
			$no1    = $row->no1;
			$tgl    = $row->tgl;
			$ket    = $row->ket;
			$nilai  = $row->nilai;
			$tnilai = $tnilai + $nilai;

			$cRet .= ' <TR>
						<TD align="left" >' . $no1 . '</TD>
						<TD align="left" >' . $tgl . '</TD>
						<TD align="left" >' . $ket . '</TD>
						<TD align="right" >' . number_format($nilai, "2", ",", ".") . '</TD>		
					 </TR>';
		}

		$sqlww2 = $this->db->query("SELECT nilai from trdrka where kd_sub_kegiatan='$giat' and kd_rek6='$kdrek' AND jns_ang='$anggaran' AND kd_skpd='$skpd'");
		foreach ($sqlww2->result() as $row1) {
			$angg = $row1->nilai;
		}
		$sisa = $angg - $tnilai;
		$cRet .= ' <TR>
						<TD >Anggaran</TD>
						<TD align="right" >' . number_format($angg, "2", ",", ".") . '</TD>	
						<TD colspan="2"></TD>
							
					 </TR>
					 <TR>
						<TD >Total Inputan</TD>
						<TD align="right" >' . number_format($tnilai, "2", ",", ".") . '</TD>
						<TD colspan="2"></TD>
								
					 </TR>
					 <TR>
						<TD >Sisa</TD>
						<TD align="right" >' . number_format($sisa, "2", ",", ".") . '</TD>	
						<TD colspan="2"></TD>
							
					 </TR>
					 ';
		$cRet .= '</TABLE>';


		$data['prev'] = 'CEK ANGGARAN';
		switch ($ctk) {
			case 0;
				echo ("<title> CEK ANGGARAN</title>");
				echo $cRet;
				break;
			case 1;
				///$this->support->_mpdf('',$cRet,10,10,10,'0',0,'');
				//$this->support->_mpdf('',$cRet,10,10,10,'0',1,'');
				$this->support->_mpdf('', $cRet, 10, 10, 10, 'L', 0, '');
				break;
		}
	}

	//////////////////////////////////////////////        
}
