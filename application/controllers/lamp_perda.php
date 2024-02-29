<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class Lamp_perda extends CI_Controller
{

	function __contruct()
	{
		parent::__construct();
	}


	function nomor_pergub()
	{
		$data['page_title'] = 'INPUT NOMOR DAN TANGGAL LAMP';
		$this->template->set('title', 'INPUT NOMOR DAN TANGGAL LAMP');
		$this->template->load('template', 'lamp_perda/nomor_pergub', $data);
	}

	function get_nogub()
	{

		$sql = "SELECT * FROM config_nogub";

		$query1 = $this->db->query($sql);


		//$test = "hai";
		$test = $query1->num_rows();

		$ii = 0;
		foreach ($query1->result_array() as $resulte) {
			$result = array(
				'id' => $ii,
				'ket_perda' => $resulte['ket_perda'],
				'ket_perda_no' => $resulte['ket_perda_no'],
				'ket_perda_tentang' => $resulte['ket_perda_tentang'],
				'ket_pergub' => $resulte['ket_pergub'],
				'ket_pergub_no' => $resulte['ket_pergub_no'],
				'ket_pergub_tentang' => $resulte['ket_pergub_tentang']
			);
			$ii++;
		}

		if ($test === 0) {
			$result = array(
				'ket_perda' => '',
				'ket_perda_no' => '',
				'ket_perda_tentang' => '',
				'ket_pergub' => '',
				'ket_pergub_no' => '',
				'ket_pergub_tentang' => ''
			);
			$ii++;
		}


		echo json_encode($result);
		$query1->free_result();
	}

	function simpan_nogub()
	{
		$tabel               = $this->input->post('tabel');
		$cket_perda          = $this->input->post('cket_perda');
		$cket_perda_no       = $this->input->post('cket_perda_no');
		$cket_perda_tentang  = $this->input->post('cket_perda_tentang');
		$cket_pergub         = $this->input->post('cket_pergub');
		$cket_pergub_no      = $this->input->post('cket_pergub_no');
		$cket_pergub_tentang = $this->input->post('cket_pergub_tentang');

		$sql = "delete from config_nogub ";
		$asg = $this->db->query($sql);

		if ($asg) {
			$sql = "insert into config_nogub(ket_perda,ket_pergub,ket_perda_no,ket_pergub_no,ket_perda_tentang,ket_pergub_tentang) 
            values('$cket_perda','$cket_pergub','$cket_perda_no','$cket_pergub_no','$cket_perda_tentang','$cket_pergub_tentang')";
			$asg = $this->db->query($sql);
		}
	}


	function  tanggal_format_indonesia($tgl)
	{
		$tanggal  = explode('-', $tgl);
		$bulan  = $this->getBulan($tanggal[1]);
		$tahun  =  $tanggal[0];
		return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
	}

	function  tanggal_indonesia($tgl)
	{
		$tanggal  =  substr($tgl, 8, 2);
		$bulan  = substr($tgl, 5, 2);
		$tahun  =  substr($tgl, 0, 4);
		return  $tanggal . '-' . $bulan . '-' . $tahun;
	}
	function right($value, $count)
	{
		return substr($value, ($count * -1));
	}

	function left($string, $count)
	{
		return substr($string, 0, $count);
	}
	function dotrek($rek)
	{
		$nrek = strlen($rek);
		switch ($nrek) {
			case 1:
				$rek = $this->left($rek, 1);
				break;
			case 2:
				$rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1);
				break;
			case 3:
				$rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1);
				break;
			case 5:
				$rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 3, 2);
				break;
			case 7:
				$rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 3, 2) . '.' . substr($rek, 5, 2);
				break;
			default:
				$rek = "";
		}
		return $rek;
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
		}
	}

	function register_cp()
	{
		$data['page_title'] = 'CETAK REGISTER CP';
		$this->template->set('title', 'CETAK REGISTER CP');
		$this->template->load('template', 'lamp_perda/cetak_register_cp', $data);
	}

	function lamp_semester()
	{
		$data['page_title'] = 'CETAK LAPORAN SEMESTER';
		$this->template->set('title', 'CETAK LAPORAN SEMESTER');
		$this->template->load('template', 'lamp_perda/cetak_lamp_semester', $data);
	}

	function lamp_angreal_prog_giat()
	{
		$data['page_title'] = 'CETAK LAPORAN SEMESTER';
		$this->template->set('title', 'CETAK LAPORAN SEMESTER');
		$this->template->load('template', 'lamp_perda/cetak_angreal_prog_giat', $data);
	}

	function lamp_semester_rinci()
	{
		$data['page_title'] = 'CETAK LAPORAN SEMESTER';
		$this->template->set('title', 'CETAK LAPORAN SEMESTER');
		$this->template->load('template', 'lamp_perda/cetak_lamp_semester_rinci', $data);
	}

	function lamp_semester_rinci_periode()
	{
		$data['page_title'] = 'CETAK LAPORAN SEMESTER';
		$this->template->set('title', 'CETAK LAPORAN SEMESTER');
		$this->template->load('template', 'lamp_perda/cetak_lamp_semester_rinci_periode', $data);
	}

	function lamp_real_brg()
	{
		$data['page_title'] = 'CETAK REAL BARANG';
		$this->template->set('title', 'CETAK REAL BARANG');
		$this->template->load('template', 'lamp_perda/cetak_real_brg', $data);
	}

	function lamp_bps()
	{
		$data['page_title'] = 'CETAK LAMP BPS';
		$this->template->set('title', 'CETAK LAMP BPS');
		$this->template->load('template', 'lamp_perda/cetak_lamp_bps', $data);
	}

	function lamp_lra()
	{
		$data['page_title'] = 'CETAK LRA';
		$this->template->set('title', 'CETAK LRA');
		$this->template->load('template', 'lamp_perda/cetak_lamp_lra', $data);
	}

	function kertas_kerja_lra()
	{
		$data['page_title'] = 'CETAK KERTAS KERJA LRA';
		$this->template->set('title', 'CETAK KERTAS KERJA LRA');
		$this->template->load('template', 'lamp_perda/cetak_kertas_kerja_lra', $data);
	}

	function kertas_kerja_lo()
	{
		$data['page_title'] = 'CETAK KERTAS KERJA LO';
		$this->template->set('title', 'CETAK KERTAS KERJA LO');
		$this->template->load('template', 'lamp_perda/cetak_kertas_kerja_lo', $data);
	}

	function lamp_lo()
	{
		$data['page_title'] = 'CETAK LAMPIRAN LO';
		$this->template->set('title', 'CETAK LAMPIRAN LO');
		$this->template->load('template', 'lamp_perda/cetak_lamp_lo', $data);
	}

	function lamp_penyi()
	{
		$data['page_title'] = 'CETAK LAMPIRAN PENYISIHAN';
		$this->template->set('title', 'CETAK LAMPIRAN PENYISIHAN');
		$this->template->load('template', 'lamp_perda/cetak_lamp_penyi', $data);
	}



	function lamp_lpe()
	{
		$data['page_title'] = 'CETAK LAMPIRAN LPE';
		$this->template->set('title', 'CETAK LAMPIRAN LPE');
		$this->template->load('template', 'lamp_perda/cetak_lamp_lpe', $data);
	}

	function lamp_bbn_lo()
	{
		$data['page_title'] = 'CETAK LAMPIRAN LO';
		$this->template->set('title', 'CETAK LAMPIRAN LO');
		$this->template->load('template', 'lamp_perda/cetak_lamp_bbn_lo', $data);
	}

	function perda_lampI_1()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I.1';
		$this->template->set('title', 'CETAK PERDA LAMP. I.1');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampI_1', $data);
	}

	function perda_lampII_1()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.1';
		$this->template->set('title', 'CETAK PERDA LAMP. II.1');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_1', $data);
	}

	function perda_lampII_5()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.5';
		$this->template->set('title', 'CETAK PERDA LAMP. II.5');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_5', $data);
	}

	function perda_lampII_4()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.4';
		$this->template->set('title', 'CETAK PERDA LAMP. II.4');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_4', $data);
	}

	function perda_lampII_9()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.9';
		$this->template->set('title', 'CETAK PERDA LAMP. II.9');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_9', $data);
	}

	function perda_lampII_11()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.11';
		$this->template->set('title', 'CETAK PERDA LAMP. II.11');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_11', $data);
	}

	function perda_lampII_7()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.7';
		$this->template->set('title', 'CETAK PERDA LAMP. II.7');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_7', $data);
	}

	function perda_lampII_8()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.8';
		$this->template->set('title', 'CETAK PERDA LAMP. II.8');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_8', $data);
	}

	function perda_lampII_6()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.6';
		$this->template->set('title', 'CETAK PERDA LAMP. II.6');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_6', $data);
	}

	function perda_lampII_3()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.3';
		$this->template->set('title', 'CETAK PERDA LAMP. II.3');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_3', $data);
	}

	function perda_lampII_2()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.2';
		$this->template->set('title', 'CETAK PERDA LAMP. II.2');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_2', $data);
	}

	function perda_lampII_10()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. II.10';
		$this->template->set('title', 'CETAK PERDA LAMP. II.10');
		$this->template->load('template', 'lamp_perda/cetak_perda_lampII_10', $data);
	}


	function cetak_register_cp($nbulan = '', $ctk = '')
	{


		$cRet = '<TABLE width="100%" style="font-size:16px">
                    <TR>
						<TD align="center" ><b>REGISTER CP SKPD</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->getBulan($nbulan) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="2" cellpadding="0" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">KODE</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="30%">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="11" align="center" width="50%">CP</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD colspan="6" bgcolor="#CCCCCC" align="center">LS</TD>
					 <TD colspan="5" bgcolor="#CCCCCC" align="center">UP/GU/TU</TD>
					 </TR>
					 <TR>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Gaji</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Barang dan Jasa</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">UP</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">GU</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">TU</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">HKPG</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pot. Lain</TD>
					 <TD bgcolor="#CCCCCC" align="center">CP</TD>
					 <TD bgcolor="#CCCCCC" align="center">Peg.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Brng.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pegawai</TD>
					 <TD bgcolor="#CCCCCC" align="center">Barang</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal</TD>
					 </TR>
					 </THEAD>
					 ';



		$query = $this->db->query("SELECT a.kd_skpd,a.nm_skpd
						,ISNULL(hkpg,0) hkpg
						,ISNULL(pot_lain,0) pot_lain
						,ISNULL(cp,0) cp
						,ISNULL(ls_peg,0) ls_peg
						,ISNULL(ls_brng,0) ls_brng
						,ISNULL(ls_modal,0) ls_modal
						,ISNULL(gu,0) gu
						,ISNULL(up_gu_peg,0) up_gu_peg
						,ISNULL(up_gu_brng,0) up_gu_brng
						,ISNULL(up_gu_modal,0) up_gu_modal
						,ISNULL(hkpg+pot_lain+cp+ls_peg+ls_brng+ls_modal+gu+up_gu_peg+up_gu_brng+up_gu_modal,0) total
						FROM ms_skpd a LEFT JOIN 		
						(SELECT		a.kd_skpd,
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN b.rupiah	ELSE 0 END) AS hkpg,
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN b.rupiah	ELSE 0 END) AS pot_lain,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '1' THEN	b.rupiah ELSE 0 END) AS cp,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN	b.rupiah ELSE 0	END) AS ls_peg,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '522' THEN	b.rupiah ELSE 0	END) AS ls_brng,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '523' THEN	b.rupiah ELSE 0	END) AS ls_modal,
						SUM (  CASE     WHEN jns_trans = '1'    AND jns_cp = '3'
						AND LEFT(b.kd_rek5, 3) = '111' THEN b.rupiah ELSE 0 END) AS gu,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN b.rupiah	ELSE 0 END) AS up_gu_peg,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('522') THEN	b.rupiah	ELSE 0 END) AS up_gu_brng,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('523') THEN	b.rupiah	ELSE 0 END) AS up_gu_modal,
						SUM (b.rupiah) AS total
					FROM
						trhkasin_pkd a
					INNER JOIN trdkasin_pkd b ON a.no_sts = b.no_sts
					AND a.kd_skpd = b.kd_skpd
					WHERE
					MONTH(a.tgl_sts)='$nbulan' 
					AND jns_trans IN ('1', '5')
					AND a.kd_skpd !=''
					GROUP BY
						a.kd_skpd) b 
					ON a.kd_skpd=b.kd_skpd
					ORDER BY a.kd_skpd");

		$no = 0;
		$hkpg_t = 0;
		$pot_lain_t = 0;
		$cp_t = 0;
		$ls_peg_t = 0;
		$ls_brng_t = 0;
		$ls_modal_t = 0;
		$up_gu_peg_t = 0;
		$up_gu_brng_t = 0;
		$up_gu_modal_t = 0;
		$gu_t = 0;
		$total_t = 0;
		foreach ($query->result() as $row) {
			$kd_skpd = $row->kd_skpd;
			$nm_skpd = $row->nm_skpd;
			$hkpg = $row->hkpg;
			$pot_lain = $row->pot_lain;
			$cp = $row->cp;
			$ls_peg = $row->ls_peg;
			$ls_brng = $row->ls_brng;
			$ls_modal = $row->ls_modal;
			$up_gu_peg = $row->up_gu_peg;
			$up_gu_brng = $row->up_gu_brng;
			$up_gu_modal = $row->up_gu_modal;
			$gu = $row->gu;
			$total = $row->total;
			$hkpg_t = $hkpg_t + $hkpg;
			$pot_lain_t = $pot_lain_t + $pot_lain;
			$cp_t = $cp_t + $cp;
			$ls_peg_t = $ls_peg_t + $ls_peg;
			$ls_brng_t = $ls_brng_t + $ls_brng;
			$ls_modal_t = $ls_modal_t + $ls_modal;
			$up_gu_peg_t = $up_gu_peg_t + $up_gu_peg;
			$up_gu_brng_t = $up_gu_brng_t + $up_gu_brng;
			$up_gu_modal_t = $up_gu_modal_t + $up_gu_modal;
			$total_t = $total_t + $total;
			$gu_t = $gu_t + $gu;
			$no = $no + 1;
			$cRet .= '<TR>
								<TD align="left" >' . $kd_skpd . '</TD>
								<TD align="left" >' . $nm_skpd . '</TD>								
								<TD align="right" >' . number_format($hkpg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total, "2", ",", ".") . '</TD>
							 </TR>';
		}

		$cRet .= '<TR>
								<TD colspan="2" align="right" >Jumlah bulan ' . $this->tukd_model->getBulan($nbulan) . '</TD>
								<TD align="right" >' . number_format($hkpg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total_t, "2", ",", ".") . '</TD>
							 </TR>';
		$nbulan_l = $nbulan - 1;

		$query = $this->db->query("select 
							ISNULL(a.hkpg_l,0) hkpg_l,
							ISNULL(a.pot_lain_l,0) pot_lain_l,
							ISNULL(a.cp_l,0) cp_l,
							ISNULL(a.ls_peg_l,0) ls_peg_l,
							ISNULL(a.ls_brng_l,0) ls_brng_l,
							ISNULL(a.ls_modal_l,0) ls_modal_l,
							ISNULL(a.gu_l,0) gu_l,
							ISNULL(a.up_gu_peg_l,0) up_gu_peg_l,
							ISNULL(a.up_gu_brng_l,0) up_gu_brng_l,
							ISNULL(a.up_gu_modal_l,0) up_gu_modal_l,
							ISNULL(hkpg_l+pot_lain_l+cp_l+ls_peg_l+ls_brng_l+ls_modal_l+gu_l+up_gu_peg_l+up_gu_brng_l+up_gu_modal_l,0) total_l 
							from (	
						SELECT
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN b.rupiah	ELSE 0 END) AS hkpg_l,
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN b.rupiah	ELSE 0 END) AS pot_lain_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '1' THEN	b.rupiah ELSE 0 END) AS cp_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN	b.rupiah ELSE 0	END) AS ls_peg_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '522' THEN	b.rupiah ELSE 0	END) AS ls_brng_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '523' THEN	b.rupiah ELSE 0	END) AS ls_modal_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) = '111' THEN	b.rupiah ELSE 0	END) AS gu_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN b.rupiah	ELSE 0 END) AS up_gu_peg_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('522') THEN	b.rupiah	ELSE 0 END) AS up_gu_brng_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('523') THEN	b.rupiah	ELSE 0 END) AS up_gu_modal_l,
						SUM (b.rupiah) AS total_l
					FROM
						trhkasin_pkd a
					INNER JOIN trdkasin_pkd b ON a.no_sts = b.no_sts
					AND a.kd_skpd = b.kd_skpd
					WHERE
					MONTH(a.tgl_sts)<='$nbulan_l' 
					AND jns_trans IN ('1', '5')
					AND a.kd_skpd !=''
					) a
					");
		foreach ($query->result() as $row) {
			$hkpg_l = $row->hkpg_l;
			$pot_lain_l = $row->pot_lain_l;
			$cp_l = $row->cp_l;
			$ls_peg_l = $row->ls_peg_l;
			$ls_brng_l = $row->ls_brng_l;
			$ls_modal_l = $row->ls_modal_l;
			$up_gu_peg_l = $row->up_gu_peg_l;
			$up_gu_brng_l = $row->up_gu_brng_l;
			$up_gu_modal_l = $row->up_gu_modal_l;
			$gu_l = $row->gu_l;
			$total_l = $row->total_l;
			$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai bulan Sebelumnya</TD>
						<TD align="right" >' . number_format($hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($total_l, "2", ",", ".") . '</TD>
					 </TR>';
		}
		$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai bulan ' . $this->tukd_model->getBulan($nbulan) . '</TD>
						<TD align="right" >' . number_format($hkpg_t + $hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_t + $pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_t + $cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_t + $ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_t + $ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_t + $ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_t + $gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_t + $up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_t + $up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_t + $up_gu_modal_l, "2", ",", ".") . ' </TD>
						<TD align="right" >' . number_format($total_t + $total_l, "2", ",", ".") . '</TD>
					 </TR>';

		$cRet .= '</TABLE>';



		$data['prev'] = $cRet;
		switch ($ctk) {
			case 0;
				echo ("<title> Register CP SPKD</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= register_cp.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_register_cp_kasda($nbulan = '', $ctk = '')
	{


		$cRet = '<TABLE width="100%" style="font-size:16px">
                    <TR>
						<TD align="center" ><b>REGISTER CP KASDA</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->getBulan($nbulan) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="2" cellpadding="0" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">KODE</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="30%">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="11" align="center" width="50%">CP</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD colspan="6" bgcolor="#CCCCCC" align="center">LS</TD>
					 <TD colspan="5" bgcolor="#CCCCCC" align="center">UP/GU/TU</TD>
					 </TR>
					 <TR>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Gaji</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Barang dan Jasa</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">UP</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">GU</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">TU</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">HKPG</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pot. Lain</TD>
					 <TD bgcolor="#CCCCCC" align="center">CP</TD>
					 <TD bgcolor="#CCCCCC" align="center">Peg.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Brng.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pegawai</TD>
					 <TD bgcolor="#CCCCCC" align="center">Barang</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal</TD>
					 </TR>
					 </THEAD>
					 ';



		$query = $this->db->query("SELECT a.kd_skpd,a.nm_skpd
						,ISNULL(hkpg,0) hkpg
						,ISNULL(pot_lain,0) pot_lain
						,ISNULL(cp,0) cp
						,ISNULL(ls_peg,0) ls_peg
						,ISNULL(ls_brng,0) ls_brng
						,ISNULL(ls_modal,0) ls_modal
						,ISNULL(gu, 0) gu
						,ISNULL(up_gu_peg,0) up_gu_peg
						,ISNULL(up_gu_brng,0) up_gu_brng
						,ISNULL(up_gu_modal,0) up_gu_modal
						,ISNULL(hkpg+pot_lain+cp+ls_peg+ls_brng+ls_modal+gu+up_gu_peg+up_gu_brng+up_gu_modal,0) total
						FROM ms_skpd a LEFT JOIN 
					(SELECT kd_skpd
					,SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN nilai	ELSE 0 END) AS hkpg
					,SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN nilai	ELSE 0 END) AS pot_lain
					,SUM(CASE	WHEN jns_trans in ('1','5') and jns_cp = '1' and pot_khusus = '0' THEN	nilai ELSE 0 END) AS cp
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) IN ('511','521') THEN	nilai ELSE 0	END) AS ls_peg
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '522' THEN	nilai ELSE 0	END) AS ls_brng
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '523' THEN	nilai ELSE 0	END) AS ls_modal
					,SUM (CASE   WHEN jns_trans = '1'    AND jns_cp =   '3' AND LEFT (kd_rek, 3) = '111' THEN  nilai ELSE
				    0    END) AS gu
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('511','521') THEN nilai	ELSE 0 END) AS up_gu_peg
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('522') THEN	nilai	ELSE 0 END) AS up_gu_brng
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('523') THEN	nilai	ELSE 0 END) AS up_gu_modal
					,SUM (nilai) AS total
					FROM
					(
					SELECT a.no_kas,a.tgl_kas, a.kd_skpd,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
					FROM (
					SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, LEFT(kd_rek5,3) as kd_rek, SUM(rupiah) as nilai FROM trhkasin_ppkd a 
					INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
					WHERE jns_trans IN ('5','1')
					GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,LEFT(kd_rek5,3)) a
					LEFT JOIN 
					(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a 
					INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
					WHERE jns_trans IN ('1', '5')
					AND a.kd_skpd !=''
					GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
					ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
					WHERE MONTH(tgl_kas)='$nbulan'
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					");

		$no = 0;
		$hkpg_t = 0;
		$pot_lain_t = 0;
		$cp_t = 0;
		$ls_peg_t = 0;
		$ls_brng_t = 0;
		$ls_modal_t = 0;
		$up_gu_peg_t = 0;
		$up_gu_brng_t = 0;
		$up_gu_modal_t = 0;
		$gu_t = 0;
		$total_t = 0;
		foreach ($query->result() as $row) {
			$kd_skpd = $row->kd_skpd;
			$nm_skpd = $row->nm_skpd;
			$hkpg = $row->hkpg;
			$pot_lain = $row->pot_lain;
			$cp = $row->cp;
			$ls_peg = $row->ls_peg;
			$ls_brng = $row->ls_brng;
			$ls_modal = $row->ls_modal;
			$up_gu_peg = $row->up_gu_peg;
			$up_gu_brng = $row->up_gu_brng;
			$up_gu_modal = $row->up_gu_modal;
			$gu = $row->gu;
			$total = $row->total;
			$hkpg_t = $hkpg_t + $hkpg;
			$pot_lain_t = $pot_lain_t + $pot_lain;
			$cp_t = $cp_t + $cp;
			$ls_peg_t = $ls_peg_t + $ls_peg;
			$ls_brng_t = $ls_brng_t + $ls_brng;
			$ls_modal_t = $ls_modal_t + $ls_modal;
			$up_gu_peg_t = $up_gu_peg_t + $up_gu_peg;
			$up_gu_brng_t = $up_gu_brng_t + $up_gu_brng;
			$up_gu_modal_t = $up_gu_modal_t + $up_gu_modal;
			$total_t = $total_t + $total;
			$gu_t = $gu_t + $gu;
			$no = $no + 1;
			$cRet .= '<TR>
								<TD align="left" >' . $kd_skpd . '</TD>
								<TD align="left" >' . $nm_skpd . '</TD>								
								<TD align="right" >' . number_format($hkpg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total, "2", ",", ".") . '</TD>
							 </TR>';
		}

		$cRet .= '<TR>
								<TD colspan="2" align="right" >Jumlah bulan ' . $this->tukd_model->getBulan($nbulan) . '</TD>
								<TD align="right" >' . number_format($hkpg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total_t, "2", ",", ".") . '</TD>
							 </TR>';
		$nbulan_l = $nbulan - 1;

		$query = $this->db->query("select 
							ISNULL(a.hkpg_l,0) hkpg_l,
							ISNULL(a.pot_lain_l,0) pot_lain_l,
							ISNULL(a.cp_l,0) cp_l,
							ISNULL(a.ls_peg_l,0) ls_peg_l,
							ISNULL(a.ls_brng_l,0) ls_brng_l,
							ISNULL(a.ls_modal_l,0) ls_modal_l,
							ISNULL(a.gu_l,0) gu_l,
							ISNULL(a.up_gu_peg_l,0) up_gu_peg_l,
							ISNULL(a.up_gu_brng_l,0) up_gu_brng_l,
							ISNULL(a.up_gu_modal_l,0) up_gu_modal_l,
							ISNULL(hkpg_l+pot_lain_l+cp_l+ls_peg_l+ls_brng_l+ls_modal_l+gu_l+up_gu_peg_l+up_gu_brng_l+up_gu_modal_l,0) total_l 
							from (
					SELECT 
					SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN nilai	ELSE 0 END) AS hkpg_l
					,SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN nilai	ELSE 0 END) AS pot_lain_l
					,SUM(CASE	WHEN jns_trans in ('1','5') and jns_cp = '1' and pot_khusus = '0' THEN	nilai ELSE 0 END) AS cp_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) IN ('511','521') THEN	nilai ELSE 0	END) AS ls_peg_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '522' THEN	nilai ELSE 0	END) AS ls_brng_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '523' THEN	nilai ELSE 0	END) AS ls_modal_l
					,SUM (CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) = '111' THEN	nilai ELSE 0	END) AS gu_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('511','521') THEN nilai	ELSE 0 END) AS up_gu_peg_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('522') THEN	nilai	ELSE 0 END) AS up_gu_brng_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('523') THEN	nilai	ELSE 0 END) AS up_gu_modal_l
					,SUM (nilai) AS total_l
					FROM
					(
					SELECT a.no_kas,a.tgl_kas, a.kd_skpd,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
					FROM (
					SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, LEFT(kd_rek5,3) as kd_rek, SUM(rupiah) as nilai FROM trhkasin_ppkd a 
					INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
					WHERE jns_trans IN ('5','1')
					GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,LEFT(kd_rek5,3)) a
					LEFT JOIN 
					(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a 
					INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
					WHERE jns_trans IN ('1', '5')
					AND a.kd_skpd !=''
					GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
					ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
					WHERE MONTH(tgl_kas)<='$nbulan_l' ) a
					");
		foreach ($query->result() as $row) {
			$hkpg_l = $row->hkpg_l;
			$pot_lain_l = $row->pot_lain_l;
			$cp_l = $row->cp_l;
			$ls_peg_l = $row->ls_peg_l;
			$ls_brng_l = $row->ls_brng_l;
			$ls_modal_l = $row->ls_modal_l;
			$up_gu_peg_l = $row->up_gu_peg_l;
			$up_gu_brng_l = $row->up_gu_brng_l;
			$up_gu_modal_l = $row->up_gu_modal_l;
			$gu_l = $row->gu_l;
			$total_l = $row->total_l;
			$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai bulan Sebelumnya</TD>
						<TD align="right" >' . number_format($hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($total_l, "2", ",", ".") . '</TD>
					 </TR>';
		}
		$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai bulan ' . $this->tukd_model->getBulan($nbulan) . '</TD>
						<TD align="right" >' . number_format($hkpg_t + $hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_t + $pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_t + $cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_t + $ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_t + $ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_t + $ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_t + $gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_t + $up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_t + $up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_t + $up_gu_modal_l, "2", ",", ".") . ' </TD>
						<TD align="right" >' . number_format($total_t + $total_l, "2", ",", ".") . '</TD>
					 </TR>';

		$cRet .= '</TABLE>';



		$data['prev'] = $cRet;
		switch ($ctk) {
			case 0;
				echo ("<title> Register CP Kasda</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= register_cp.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_register_cp_periode($tgl1 = '', $ctk = '', $tgl2 = '')
	{


		$cRet = '<TABLE width="100%" style="font-size:16px">
                    <TR>
						<TD align="center" ><b>REGISTER CP SKPD</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left">Periode </TD>
						<TD align="left">: ' . $this->tukd_model->tanggal_format_indonesia($tgl1) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($tgl2) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="2" cellpadding="0" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">KODE</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="30%">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="11" align="center" width="50%">CP</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD colspan="6" bgcolor="#CCCCCC" align="center">LS</TD>
					 <TD colspan="5" bgcolor="#CCCCCC" align="center">UP/GU/TU</TD>
					 </TR>
					 <TR>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Gaji</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Barang dan Jasa</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">UP</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">GU</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">TU</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">HKPG</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pot. Lain</TD>
					 <TD bgcolor="#CCCCCC" align="center">CP</TD>
					 <TD bgcolor="#CCCCCC" align="center">Peg.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Brng.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pegawai</TD>
					 <TD bgcolor="#CCCCCC" align="center">Barang</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal</TD>
					 </TR>
					 </THEAD>
					 ';



		$query = $this->db->query("SELECT a.kd_skpd,a.nm_skpd
						,ISNULL(hkpg,0) hkpg
						,ISNULL(pot_lain,0) pot_lain
						,ISNULL(cp,0) cp
						,ISNULL(ls_peg,0) ls_peg
						,ISNULL(ls_brng,0) ls_brng
						,ISNULL(ls_modal,0) ls_modal
						,ISNULL(gu,0) gu
						,ISNULL(up_gu_peg,0) up_gu_peg
						,ISNULL(up_gu_brng,0) up_gu_brng
						,ISNULL(up_gu_modal,0) up_gu_modal
						,ISNULL(hkpg+pot_lain+cp+ls_peg+ls_brng+ls_modal+gu+up_gu_peg+up_gu_brng+up_gu_modal,0) total
						FROM ms_skpd a LEFT JOIN 		
						(SELECT		a.kd_skpd,
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN b.rupiah	ELSE 0 END) AS hkpg,
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN b.rupiah	ELSE 0 END) AS pot_lain,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '1' THEN	b.rupiah ELSE 0 END) AS cp,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN	b.rupiah ELSE 0	END) AS ls_peg,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '522' THEN	b.rupiah ELSE 0	END) AS ls_brng,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '523' THEN	b.rupiah ELSE 0	END) AS ls_modal,
						SUM (  CASE     WHEN jns_trans = '1'    AND jns_cp = '3' AND LEFT(b.kd_rek5,3) = '111' THEN b.rupiah ELSE 0 END) AS gu,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN b.rupiah	ELSE 0 END) AS up_gu_peg,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('522') THEN	b.rupiah	ELSE 0 END) AS up_gu_brng,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('523') THEN	b.rupiah	ELSE 0 END) AS up_gu_modal,
						SUM (b.rupiah) AS total
					FROM
						trhkasin_pkd a
					INNER JOIN trdkasin_pkd b ON a.no_sts = b.no_sts
					AND a.kd_skpd = b.kd_skpd
					WHERE
					a.tgl_sts BETWEEN '$tgl1' AND '$tgl2'
					AND jns_trans IN ('1', '5') and pot_khusus !='3' AND LEFT(kd_rek5,1)<>4
					AND a.kd_skpd !=''
					GROUP BY
						a.kd_skpd) b 
					ON a.kd_skpd=b.kd_skpd
					ORDER BY a.kd_skpd");

		$no = 0;
		$hkpg_t = 0;
		$pot_lain_t = 0;
		$cp_t = 0;
		$ls_peg_t = 0;
		$ls_brng_t = 0;
		$ls_modal_t = 0;
		$up_gu_peg_t = 0;
		$up_gu_brng_t = 0;
		$up_gu_modal_t = 0;
		$gu_t = 0;
		$total_t = 0;
		foreach ($query->result() as $row) {
			$kd_skpd = $row->kd_skpd;
			$nm_skpd = $row->nm_skpd;
			$hkpg = $row->hkpg;
			$pot_lain = $row->pot_lain;
			$cp = $row->cp;
			$ls_peg = $row->ls_peg;
			$ls_brng = $row->ls_brng;
			$ls_modal = $row->ls_modal;
			$up_gu_peg = $row->up_gu_peg;
			$up_gu_brng = $row->up_gu_brng;
			$up_gu_modal = $row->up_gu_modal;
			$gu = $row->gu;
			$total = $row->total;
			$hkpg_t = $hkpg_t + $hkpg;
			$pot_lain_t = $pot_lain_t + $pot_lain;
			$cp_t = $cp_t + $cp;
			$ls_peg_t = $ls_peg_t + $ls_peg;
			$ls_brng_t = $ls_brng_t + $ls_brng;
			$ls_modal_t = $ls_modal_t + $ls_modal;
			$up_gu_peg_t = $up_gu_peg_t + $up_gu_peg;
			$up_gu_brng_t = $up_gu_brng_t + $up_gu_brng;
			$up_gu_modal_t = $up_gu_modal_t + $up_gu_modal;
			$total_t = $total_t + $total;
			$gu_t = $gu_t + $gu;
			$no = $no + 1;
			$cRet .= '<TR>
								<TD align="left" >' . $kd_skpd . '</TD>
								<TD align="left" >' . $nm_skpd . '</TD>								
								<TD align="right" >' . number_format($hkpg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total, "2", ",", ".") . '</TD>
							 </TR>';
		}

		$cRet .= '<TR>
								<TD colspan="2" align="right" >Jumlah Periode ' . $this->tukd_model->tanggal_format_indonesia($tgl1) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($tgl2) . '</TD>
								<TD align="right" >' . number_format($hkpg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total_t, "2", ",", ".") . '</TD>
							 </TR>';
		//$nbulan_l=$nbulan-1;

		$query = $this->db->query("select 
							ISNULL(a.hkpg_l,0) hkpg_l,
							ISNULL(a.pot_lain_l,0) pot_lain_l,
							ISNULL(a.cp_l,0) cp_l,
							ISNULL(a.ls_peg_l,0) ls_peg_l,
							ISNULL(a.ls_brng_l,0) ls_brng_l,
							ISNULL(a.ls_modal_l,0) ls_modal_l,
							ISNULL(a.gu_l,0) gu_l,
							ISNULL(a.up_gu_peg_l,0) up_gu_peg_l,
							ISNULL(a.up_gu_brng_l,0) up_gu_brng_l,
							ISNULL(a.up_gu_modal_l,0) up_gu_modal_l,
							ISNULL(hkpg_l+pot_lain_l+cp_l+ls_peg_l+ls_brng_l+ls_modal_l+gu_l+up_gu_peg_l+up_gu_brng_l+up_gu_modal_l,0) total_l 
							from (		
						SELECT
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN b.rupiah	ELSE 0 END) AS hkpg_l,
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN b.rupiah	ELSE 0 END) AS pot_lain_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '1' THEN	b.rupiah ELSE 0 END) AS cp_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN	b.rupiah ELSE 0	END) AS ls_peg_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '522' THEN	b.rupiah ELSE 0	END) AS ls_brng_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '523' THEN	b.rupiah ELSE 0	END) AS ls_modal_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) = '111' THEN	b.rupiah ELSE 0	END) AS gu_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN b.rupiah	ELSE 0 END) AS up_gu_peg_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('522') THEN	b.rupiah	ELSE 0 END) AS up_gu_brng_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('523') THEN	b.rupiah	ELSE 0 END) AS up_gu_modal_l,
						SUM (b.rupiah) AS total_l
					FROM
						trhkasin_pkd a
					INNER JOIN trdkasin_pkd b ON a.no_sts = b.no_sts
					AND a.kd_skpd = b.kd_skpd
					AND a.kd_skpd !=''
					WHERE
					a.tgl_sts <'$tgl1' 
					AND jns_trans IN ('1', '5') and pot_khusus !='3'
					) a
					");
		foreach ($query->result() as $row) {
			$hkpg_l = $row->hkpg_l;
			$pot_lain_l = $row->pot_lain_l;
			$cp_l = $row->cp_l;
			$ls_peg_l = $row->ls_peg_l;
			$ls_brng_l = $row->ls_brng_l;
			$ls_modal_l = $row->ls_modal_l;
			$up_gu_peg_l = $row->up_gu_peg_l;
			$up_gu_brng_l = $row->up_gu_brng_l;
			$up_gu_modal_l = $row->up_gu_modal_l;
			$gu_l = $row->gu_l;
			$total_l = $row->total_l;
			$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai Periode Sebelumnya</TD>
						<TD align="right" >' . number_format($hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($total_l, "2", ",", ".") . '</TD>
					 </TR>';
		}
		$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai  ' . $this->tukd_model->tanggal_format_indonesia($tgl2) . '</TD>
						<TD align="right" >' . number_format($hkpg_t + $hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_t + $pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_t + $cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_t + $ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_t + $ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_t + $ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_t + $gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_t + $up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_t + $up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_t + $up_gu_modal_l, "2", ",", ".") . ' </TD>
						<TD align="right" >' . number_format($total_t + $total_l, "2", ",", ".") . '</TD>
					 </TR>';

		$cRet .= '</TABLE>';



		$data['prev'] = $cRet;
		switch ($ctk) {
			case 0;
				echo ("<title> Register CP SPKD</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= register_cp.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_register_cp_kasda_periode($tgl1 = '', $ctk = '', $tgl2 = '')
	{


		$cRet = '<TABLE width="100%" style="font-size:16px">
                    <TR>
						<TD align="center" ><b>REGISTER CP KASDA</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= '<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: ' . $this->tukd_model->tanggal_format_indonesia($tgl1) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($tgl2) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="2" cellpadding="0" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">KODE</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="30%">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="11" align="center" width="50%">CP</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center" width="10%">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD colspan="6" bgcolor="#CCCCCC" align="center">LS</TD>
					 <TD colspan="5" bgcolor="#CCCCCC" align="center">UP/GU/TU</TD>
					 </TR>
					 <TR>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Gaji</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Barang dan Jasa</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">UP</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">GU</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">TU</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">HKPG</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pot. Lain</TD>
					 <TD bgcolor="#CCCCCC" align="center">CP</TD>
					 <TD bgcolor="#CCCCCC" align="center">Peg.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Brng.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pegawai</TD>
					 <TD bgcolor="#CCCCCC" align="center">Barang</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal</TD>
					 </TR>
					 </THEAD>
					 ';



		$query = $this->db->query("SELECT a.kd_skpd,a.nm_skpd
						,ISNULL(hkpg,0) hkpg
						,ISNULL(pot_lain,0) pot_lain
						,ISNULL(cp,0) cp
						,ISNULL(ls_peg,0) ls_peg
						,ISNULL(ls_brng,0) ls_brng
						,ISNULL(ls_modal,0) ls_modal
						,ISNULL(gu, 0) gu
						,ISNULL(up_gu_peg,0) up_gu_peg
						,ISNULL(up_gu_brng,0) up_gu_brng
						,ISNULL(up_gu_modal,0) up_gu_modal
						,ISNULL(hkpg+pot_lain+cp+ls_peg+ls_brng+ls_modal+gu+up_gu_peg+up_gu_brng+up_gu_modal,0) total
						FROM ms_skpd a LEFT JOIN 
					(SELECT kd_skpd
					,SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN nilai	ELSE 0 END) AS hkpg
					,SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN nilai	ELSE 0 END) AS pot_lain
					,SUM(CASE	WHEN jns_trans in ('1','5') and jns_cp = '1' and pot_khusus = '0' THEN	nilai ELSE 0 END) AS cp
					,SUM(CASE	WHEN jns_trans IN  ('1','5')	AND jns_cp = '2' AND LEFT(kd_rek,3) IN ('511','521') THEN	nilai ELSE 0	END) AS ls_peg
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '522' THEN	nilai ELSE 0	END) AS ls_brng
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '523' THEN	nilai ELSE 0	END) AS ls_modal
					,SUM (CASE   WHEN jns_trans = '1'   AND jns_cp =   '3' AND LEFT(kd_rek,3) = '111' THEN    nilai ELSE
				    0    END) AS gu
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('511','521') THEN nilai	ELSE 0 END) AS up_gu_peg
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('522') THEN	nilai	ELSE 0 END) AS up_gu_brng
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('523') THEN	nilai	ELSE 0 END) AS up_gu_modal
					,SUM (nilai) AS total
					FROM
					(
					SELECT a.no_kas,a.tgl_kas, a.kd_skpd,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
					FROM (
					SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, LEFT(kd_rek5,3) as kd_rek, SUM(rupiah) as nilai FROM trhkasin_ppkd a 
					INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
					WHERE jns_trans IN ('5','1') AND pot_khusus !='3'
					GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,LEFT(kd_rek5,3)) a
					LEFT JOIN 
					(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a 
					INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
					WHERE jns_trans IN ('5','1')  AND pot_khusus !='3'
					GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
					ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
					WHERE tgl_kas BETWEEN '$tgl1' AND '$tgl2'
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd ORDER BY a.kd_skpd
					");

		$no = 0;
		$hkpg_t = 0;
		$pot_lain_t = 0;
		$cp_t = 0;
		$ls_peg_t = 0;
		$ls_brng_t = 0;
		$ls_modal_t = 0;
		$up_gu_peg_t = 0;
		$up_gu_brng_t = 0;
		$up_gu_modal_t = 0;
		$gu_t = 0;
		$total_t = 0;
		foreach ($query->result() as $row) {
			$kd_skpd = $row->kd_skpd;
			$nm_skpd = $row->nm_skpd;
			$hkpg = $row->hkpg;
			$pot_lain = $row->pot_lain;
			$cp = $row->cp;
			$ls_peg = $row->ls_peg;
			$ls_brng = $row->ls_brng;
			$ls_modal = $row->ls_modal;
			$up_gu_peg = $row->up_gu_peg;
			$up_gu_brng = $row->up_gu_brng;
			$up_gu_modal = $row->up_gu_modal;
			$gu = $row->gu;
			$total = $row->total;
			$hkpg_t = $hkpg_t + $hkpg;
			$pot_lain_t = $pot_lain_t + $pot_lain;
			$cp_t = $cp_t + $cp;
			$ls_peg_t = $ls_peg_t + $ls_peg;
			$ls_brng_t = $ls_brng_t + $ls_brng;
			$ls_modal_t = $ls_modal_t + $ls_modal;
			$up_gu_peg_t = $up_gu_peg_t + $up_gu_peg;
			$up_gu_brng_t = $up_gu_brng_t + $up_gu_brng;
			$up_gu_modal_t = $up_gu_modal_t + $up_gu_modal;
			$total_t = $total_t + $total;
			$gu_t = $gu_t + $gu;
			$no = $no + 1;
			$cRet .= '<TR>
								<TD align="left" >' . $kd_skpd . '</TD>
								<TD align="left" >' . $nm_skpd . '</TD>								
								<TD align="right" >' . number_format($hkpg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total, "2", ",", ".") . '</TD>
							 </TR>';
		}

		$cRet .= '<TR>
								<TD colspan="2" align="right" >Jumlah periode ' . $this->tukd_model->tanggal_format_indonesia($tgl1) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($tgl2) . '</TD>
								<TD align="right" >' . number_format($hkpg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($pot_lain_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($cp_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($ls_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($gu_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_peg_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_brng_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($up_gu_modal_t, "2", ",", ".") . '</TD>
								<TD align="right" >' . number_format($total_t, "2", ",", ".") . '</TD>
							 </TR>';
		//$nbulan_l=$nbulan-1;

		$query = $this->db->query("select 
							ISNULL(a.hkpg_l,0) hkpg_l,
							ISNULL(a.pot_lain_l,0) pot_lain_l,
							ISNULL(a.cp_l,0) cp_l,
							ISNULL(a.ls_peg_l,0) ls_peg_l,
							ISNULL(a.ls_brng_l,0) ls_brng_l,
							ISNULL(a.ls_modal_l,0) ls_modal_l,
							ISNULL(a.gu_l,0) gu_l,
							ISNULL(a.up_gu_peg_l,0) up_gu_peg_l,
							ISNULL(a.up_gu_brng_l,0) up_gu_brng_l,
							ISNULL(a.up_gu_modal_l,0) up_gu_modal_l,
							ISNULL(hkpg_l+pot_lain_l+cp_l+ls_peg_l+ls_brng_l+ls_modal_l+gu_l+up_gu_peg_l+up_gu_brng_l+up_gu_modal_l,0) total_l 
							from (
					SELECT 
					SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN nilai	ELSE 0 END) AS hkpg_l
					,SUM(CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN nilai	ELSE 0 END) AS pot_lain_l
					,SUM(CASE	WHEN jns_trans in ('1','5') and jns_cp = '1' and pot_khusus = '0' THEN	nilai ELSE 0 END) AS cp_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) IN ('511','521') THEN	nilai ELSE 0	END) AS ls_peg_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '522' THEN	nilai ELSE 0	END) AS ls_brng_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '2' AND LEFT(kd_rek,3) = '523' THEN	nilai ELSE 0	END) AS ls_modal_l
					,SUM (CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) = '111' THEN	nilai ELSE 0	END) AS gu_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('511','521') THEN nilai	ELSE 0 END) AS up_gu_peg_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('522') THEN	nilai	ELSE 0 END) AS up_gu_brng_l
					,SUM(CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(kd_rek,3) IN ('523') THEN	nilai	ELSE 0 END) AS up_gu_modal_l
					,SUM (nilai) AS total_l
					FROM
					(
					SELECT a.no_kas,a.tgl_kas, a.kd_skpd,kd_rek, nilai, jns_trans,jns_cp,pot_khusus
					FROM (
					SELECT a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd, LEFT(kd_rek5,3) as kd_rek, SUM(rupiah) as nilai FROM trhkasin_ppkd a 
					INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_kas=b.no_kas
					WHERE jns_trans IN ('5','1')  AND pot_khusus !='3'
					GROUP BY a.no_kas,a.no_sts,a.tgl_kas,a.kd_skpd,LEFT(kd_rek5,3)) a
					LEFT JOIN 
					(SELECT a.no_sts , a.kd_skpd , a.jns_trans, a.jns_cp, pot_khusus FROM trhkasin_pkd a 
					INNER JOIN trdkasin_pkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
					WHERE jns_trans IN ('5','1')  AND pot_khusus !='3'
					GROUP BY a.no_sts, a.kd_skpd, a.jns_trans, a.jns_cp, pot_khusus ) b
					ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd)a
					WHERE tgl_kas<'$tgl1') a
					");
		foreach ($query->result() as $row) {
			$hkpg_l = $row->hkpg_l;
			$pot_lain_l = $row->pot_lain_l;
			$cp_l = $row->cp_l;
			$ls_peg_l = $row->ls_peg_l;
			$ls_brng_l = $row->ls_brng_l;
			$ls_modal_l = $row->ls_modal_l;
			$up_gu_peg_l = $row->up_gu_peg_l;
			$up_gu_brng_l = $row->up_gu_brng_l;
			$up_gu_modal_l = $row->up_gu_modal_l;
			$gu_l = $row->gu_l;
			$total_l = $row->total_l;
			$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai periode Sebelumnya</TD>
						<TD align="right" >' . number_format($hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($total_l, "2", ",", ".") . '</TD>
					 </TR>';
		}
		$cRet .= '<TR>
						<TD colspan="2" align="right" >Jumlah sampai ' . $this->tukd_model->tanggal_format_indonesia($tgl2) . '</TD>
						<TD align="right" >' . number_format($hkpg_t + $hkpg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($pot_lain_t + $pot_lain_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($cp_t + $cp_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_peg_t + $ls_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_brng_t + $ls_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($ls_modal_t + $ls_modal_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format(0, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($gu_t + $gu_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_peg_t + $up_gu_peg_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_brng_t + $up_gu_brng_l, "2", ",", ".") . '</TD>
						<TD align="right" >' . number_format($up_gu_modal_t + $up_gu_modal_l, "2", ",", ".") . ' </TD>
						<TD align="right" >' . number_format($total_t + $total_l, "2", ",", ".") . '</TD>
					 </TR>';

		$cRet .= '</TABLE>';



		$data['prev'] = $cRet;
		switch ($ctk) {
			case 0;
				echo ("<title> Register CP Kasda</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= register_cp.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_lamp_lpe($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>RINGKASAN LAPORAN PERUBAHAN EKUITAS <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Kode</b></td>
                    <td width=\"60%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Ekuitas Awal</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Ekuitas Akhir</b></td>
					</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td>
				</tr>
				</thead>";

		$sql = "SELECT a.kd_skpd, a.nm_skpd, ISNULL(Lalu,0) as lalu, ISNULL(Sekarang,0) as sekarang
					FROM ms_skpd a LEFT JOIN (
					SELECT kd_skpd, SUM(delapan_lalu-sembilan_lalu+eku_lalu) as Lalu,
					SUM(delapan-sembilan+eku) as Sekarang FROM
					(
					select b.kd_skpd
					,SUM(CASE WHEN LEFT(a.kd_rek5,1) ='8' AND YEAR(b.tgl_voucher)<'2015' THEN kredit-debet ELSE 0 END) AS delapan_lalu
					,SUM(CASE WHEN LEFT(a.kd_rek5,1) ='9' AND YEAR(b.tgl_voucher)<'2015' THEN debet-kredit ELSE 0 END) AS sembilan_lalu
					,SUM(CASE WHEN tabel ='1' AND kd_rek5='3110101' AND YEAR(b.tgl_voucher)<'2015' THEN kredit-debet ELSE 0 END) AS eku_lalu
					,SUM(CASE WHEN LEFT(a.kd_rek5,1) ='8' AND YEAR(b.tgl_voucher)<='2015' THEN kredit-debet ELSE 0 END) AS delapan
					,SUM(CASE WHEN LEFT(a.kd_rek5,1) ='9' AND YEAR(b.tgl_voucher)<='2015' THEN debet-kredit ELSE 0 END) AS sembilan
					,SUM(CASE WHEN tabel ='1' AND kd_rek5='3110101' AND YEAR(b.tgl_voucher)<='2015' THEN kredit-debet ELSE 0 END) AS eku
					from trdju_pkd a INNER JOIN trhju_pkd b ON a.kd_unit=b.kd_skpd AND a.no_voucher = b.no_voucher
					GROUP BY kd_skpd
					)a GROUP BY kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kd_skpd;
			$nama = $row->nm_skpd;
			$lalu = $row->lalu;
			$sekarang = $row->sekarang;
			if ($sekarang < 0) {
				$sekarang = $sekarang * -1;
				$a = '(';
				$b = ')';
			} else {
				$sekarang = $sekarang;
				$a = '';
				$b = '';
			}
			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . $a . ' ' . number_format($sekarang, "2", ",", ".") . ' ' . $b . '</td> 
							</tr>';
		}



		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Rincian_LPE';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}




	function cetak_lamp_beban_lo($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>RINGKASAN LAPORAN OPERASIONAL MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI<BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Kode</b></td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Urusan Pemerintahan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Pendapatan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Beban Pegawai</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Beban Barang dan Jasa</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Beban Bunga</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Beban Subsidi</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Beban Hibah</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Beban Bantuan Sosial</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Beban Penyusuhan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Lain Lain</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah</b></td>
					</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td>
				</tr>
				</thead>";

		$sql = "SELECT a.kd_urusan1 as kode ,a.nm_urusan1 as nama, bel_pend,
					 bel_peg, bel_brjs, bel_bunga, bel_subsidi, bel_hibah, 
					bel_bansos, bel_penyusutan, lain
					FROM 
					ms_urusan1 a
					LEFT JOIN
					(SELECT a.kode
					,SUM(CASE WHEN LEFT(a.kd_rek,1) = '8' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '911' THEN a.nilai ELSE 0 END) AS bel_peg
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '912' THEN a.nilai ELSE 0 END) AS bel_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '913' THEN a.nilai ELSE 0 END) AS bel_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '914' THEN a.nilai ELSE 0 END) AS bel_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '915' THEN a.nilai ELSE 0 END) AS bel_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '916' THEN a.nilai ELSE 0 END) AS bel_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '917' THEN a.nilai ELSE 0 END) AS bel_penyusutan
					,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('918','919') THEN a.nilai ELSE 0 END) AS lain
					FROM
					(SELECT LEFT(kd_skpd,1) as kode,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
					SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
					WHERE LEFT(b.kd_rek5,2) IN ('91') OR  LEFT(b.kd_rek5,1) ='8') a GROUP BY LEFT(a.kd_skpd,1),LEFT(kd_rek5,3))a
					GROUP BY kode) b
					ON a.kd_urusan1=b.kode	
					UNION ALL
					SELECT a.kd_urusan as kode ,a.nm_urusan as nama,bel_pend,
					 bel_peg, bel_brjs, bel_bunga, bel_subsidi, bel_hibah, 
					bel_bansos, bel_penyusutan, lain
					FROM 
					ms_urusan a
					LEFT JOIN		
					(SELECT a.kode
					,SUM(CASE WHEN LEFT(a.kd_rek,1) = '8' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '911' THEN a.nilai ELSE 0 END) AS bel_peg
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '912' THEN a.nilai ELSE 0 END) AS bel_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '913' THEN a.nilai ELSE 0 END) AS bel_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '914' THEN a.nilai ELSE 0 END) AS bel_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '915' THEN a.nilai ELSE 0 END) AS bel_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '916' THEN a.nilai ELSE 0 END) AS bel_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '917' THEN a.nilai ELSE 0 END) AS bel_penyusutan
					,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('918','919') THEN a.nilai ELSE 0 END) AS lain
					FROM
					(SELECT LEFT(kd_skpd,4) as kode,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
					SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
					WHERE LEFT(b.kd_rek5,2) IN ('91') OR  LEFT(b.kd_rek5,1) ='8') a GROUP BY LEFT(a.kd_skpd,4),LEFT(kd_rek5,3))a
					GROUP BY kode) b
					ON a.kd_urusan=b.kode	
					UNION ALL
					SELECT a.kd_org as kode ,a.nm_org as nama,bel_pend,
					 bel_peg, bel_brjs, bel_bunga, bel_subsidi, bel_hibah, 
					bel_bansos, bel_penyusutan, lain
					FROM 
					ms_organisasi a
					LEFT JOIN		
					(SELECT a.kode
					,SUM(CASE WHEN LEFT(a.kd_rek,1) = '8' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '911' THEN a.nilai ELSE 0 END) AS bel_peg
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '912' THEN a.nilai ELSE 0 END) AS bel_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '913' THEN a.nilai ELSE 0 END) AS bel_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '914' THEN a.nilai ELSE 0 END) AS bel_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '915' THEN a.nilai ELSE 0 END) AS bel_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '916' THEN a.nilai ELSE 0 END) AS bel_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '917' THEN a.nilai ELSE 0 END) AS bel_penyusutan
					,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('918','919') THEN a.nilai ELSE 0 END) AS lain
					FROM
					(SELECT LEFT(kd_skpd,7) as kode,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
					SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
					WHERE LEFT(b.kd_rek5,2) IN ('91') OR  LEFT(b.kd_rek5,1) ='8') a GROUP BY LEFT(a.kd_skpd,7),LEFT(kd_rek5,3))a
					GROUP BY kode) b
					ON a.kd_org=b.kode		
					UNION ALL
					SELECT a.kd_skpd as kode ,a.nm_skpd as nama,bel_pend,
					 bel_peg, bel_brjs, bel_bunga, bel_subsidi, bel_hibah, 
					bel_bansos, bel_penyusutan, lain
					FROM 
					ms_skpd a
					LEFT JOIN		
					(SELECT a.kd_skpd
					,SUM(CASE WHEN LEFT(a.kd_rek,1) = '8' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '911' THEN a.nilai ELSE 0 END) AS bel_peg
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '912' THEN a.nilai ELSE 0 END) AS bel_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '913' THEN a.nilai ELSE 0 END) AS bel_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '914' THEN a.nilai ELSE 0 END) AS bel_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '915' THEN a.nilai ELSE 0 END) AS bel_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '916' THEN a.nilai ELSE 0 END) AS bel_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '917' THEN a.nilai ELSE 0 END) AS bel_penyusutan
					,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('918','919') THEN a.nilai ELSE 0 END) AS lain
					FROM
					(SELECT kd_skpd,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
					SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
					WHERE LEFT(b.kd_rek5,2) IN ('91') OR  LEFT(b.kd_rek5,1) ='8') a GROUP BY kd_skpd,LEFT(kd_rek5,3))a
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					ORDER BY kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nama = $row->nama;
			$bel_pend = $row->bel_pend;
			$bel_peg = $row->bel_peg;
			$bel_brjs = $row->bel_brjs;
			$bel_bunga = $row->bel_bunga;
			$bel_subsidi = $row->bel_subsidi;
			$bel_hibah = $row->bel_hibah;
			$bel_bansos = $row->bel_bansos;
			$bel_penyusutan = $row->bel_penyusutan;
			$lain = $row->lain;
			$jum = $bel_peg + $bel_brjs + $bel_bunga + $bel_subsidi + $bel_hibah + $bel_bansos + $bel_penyusutan + $lain;
			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_brjs, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_bunga, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_subsidi, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_hibah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_bansos, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_penyusutan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($lain, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum, "2", ",", ".") . '</td> 
							</tr>';
		}



		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Rincian_LO';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_lo($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR RINCIAN LO <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kode</td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Nama SKPD</td>
					<td colspan=\"4\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pendapatan Asli Daerah</td>
					<td colspan=\"5\" width=\"50%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Transfer Pemerintah Pusat - Dana Perimbangan</td>
					<td colspan=\"4\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Transfer Pemerintah Pusat Lainnya</td>
					<td colspan=\"3\" width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Lain-Lain Pendapatan Yang Sah</td>
					<td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pendapatan Pajak Daerah</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pendapatan Retribusi Daerah</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pendapatan Hasil Pengelolaan Kekayaan Daerah Yang Dipisahkan</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah Pendapatan Asli Daerah</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Dana Bagi Hasil Pajak</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Dana Bagi Hasil Sumber Daya Alam</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Dana Alokasi Umum</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Dana Alokasi Khusus</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah Pendapatan Transfer Dana Perimbangan</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Dana Otonomi Khusu</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Dana Penyesuaian</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah Pendapatan Transfer Lainnya</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah Pendapatan Transfer</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pendapatan Hibah</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pendapatan Lainnya</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah Lain-lain Pendapatan yang Sah</td> 
				</tr>
				
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
				</tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT a.kd_org as kode ,a.nm_org as nama
					, ISNULL(pajak,0) as pajak
					, ISNULL(retribusi,0) as retribusi
					, ISNULL(kekayaan,0) as kekayaan
					, ISNULL(bg_hasil,0) as bg_hasil
					, ISNULL(sumber_daya,0) as sumber_daya
					, ISNULL(dau,0) as dau
					, ISNULL(dak,0) as dak
					, ISNULL(dok,0) as dok
					, ISNULL(penyesuaian,0) as penyesuaian
					, ISNULL(transfer,0) as transfer
					, ISNULL(hibah,0) as hibah
					, ISNULL(lainnya,0) as lainnya
					FROM ms_organisasi a 
					LEFT JOIN
					(SELECT kode
					,SUM(CASE WHEN kd_rek ='811' THEN nilai ELSE 0 END) AS pajak
					,SUM(CASE WHEN kd_rek ='812' THEN nilai ELSE 0 END) AS retribusi
					,SUM(CASE WHEN kd_rek ='813' THEN nilai ELSE 0 END) AS kekayaan
					,SUM(CASE WHEN kd_rek ='82101' THEN nilai ELSE 0 END) AS bg_hasil
					,SUM(CASE WHEN kd_rek ='82102' THEN nilai ELSE 0 END) AS sumber_daya
					,SUM(CASE WHEN kd_rek ='82103' THEN nilai ELSE 0 END) AS dau
					,SUM(CASE WHEN kd_rek ='82104' THEN nilai ELSE 0 END) AS dak
					,SUM(CASE WHEN kd_rek ='82201' THEN nilai ELSE 0 END) AS dok
					,SUM(CASE WHEN kd_rek ='82203' THEN nilai ELSE 0 END) AS penyesuaian
					,SUM(CASE WHEN kd_rek ='' THEN nilai ELSE 0 END) AS transfer 
					,SUM(CASE WHEN kd_rek ='831' THEN nilai ELSE 0 END) AS hibah
					,SUM(CASE WHEN kd_rek IN ('832','833') THEN nilai ELSE 0 END) AS lainnya
					FROM
					(
					SELECT LEFT(b.kd_skpd,7) as kode, LEFT(a.kd_rek5,5) as kd_rek, SUM(kredit-debet) as nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher
					WHERE LEFT(a.kd_rek5,1)='8'
					GROUP BY LEFT(b.kd_skpd,7), LEFT(a.kd_rek5,5)
					UNION ALL
					SELECT LEFT(b.kd_skpd,7) as kode, LEFT(a.kd_rek5,3) as kd_rek, SUM(kredit-debet) as nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher
					WHERE LEFT(a.kd_rek5,3) IN ('811','812','813','831','832','833')
					GROUP BY LEFT(b.kd_skpd,7), LEFT(a.kd_rek5,3)
					) a GROUP BY kode)b
					ON a.kd_org=b.kode
					UNION ALL
					SELECT a.kd_skpd as kode ,a.nm_skpd as nama
					, ISNULL(pajak,0) as pajak
					, ISNULL(retribusi,0) as retribusi
					, ISNULL(kekayaan,0) as kekayaan
					, ISNULL(bg_hasil,0) as bg_hasil
					, ISNULL(sumber_daya,0) as sumber_daya
					, ISNULL(dau,0) as dau
					, ISNULL(dak,0) as dak
					, ISNULL(dok,0) as dok
					, ISNULL(penyesuaian,0) as penyesuaian
					, ISNULL(transfer,0) as transfer
					, ISNULL(hibah,0) as hibah
					, ISNULL(lainnya,0) as lainnya
					FROM ms_skpd a 
					LEFT JOIN
					(SELECT kd_skpd
					,SUM(CASE WHEN kd_rek ='811' THEN nilai ELSE 0 END) AS pajak
					,SUM(CASE WHEN kd_rek ='812' THEN nilai ELSE 0 END) AS retribusi
					,SUM(CASE WHEN kd_rek ='813' THEN nilai ELSE 0 END) AS kekayaan
					,SUM(CASE WHEN kd_rek ='82101' THEN nilai ELSE 0 END) AS bg_hasil
					,SUM(CASE WHEN kd_rek ='82102' THEN nilai ELSE 0 END) AS sumber_daya
					,SUM(CASE WHEN kd_rek ='82103' THEN nilai ELSE 0 END) AS dau
					,SUM(CASE WHEN kd_rek ='82104' THEN nilai ELSE 0 END) AS dak
					,SUM(CASE WHEN kd_rek ='82201' THEN nilai ELSE 0 END) AS dok
					,SUM(CASE WHEN kd_rek ='82203' THEN nilai ELSE 0 END) AS penyesuaian
					,SUM(CASE WHEN kd_rek ='' THEN nilai ELSE 0 END) AS transfer 
					,SUM(CASE WHEN kd_rek ='831' THEN nilai ELSE 0 END) AS hibah
					,SUM(CASE WHEN kd_rek IN ('832','833') THEN nilai ELSE 0 END) AS lainnya
					FROM
					(
					SELECT b.kd_skpd,LEFT(a.kd_rek5,5) as kd_rek, SUM(kredit-debet) as nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher
					WHERE LEFT(a.kd_rek5,1)='8'
					GROUP BY b.kd_skpd, LEFT(a.kd_rek5,5)
					UNION ALL
					SELECT b.kd_skpd,LEFT(a.kd_rek5,3) as kd_rek, SUM(kredit-debet) as nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.kd_unit=b.kd_skpd AND a.no_voucher=b.no_voucher
					WHERE LEFT(a.kd_rek5,3) IN ('811','812','813','831','832','833')
					GROUP BY b.kd_skpd, LEFT(a.kd_rek5,3)
					) a GROUP BY kd_skpd)b
					ON a.kd_skpd=b.kd_skpd
					ORDER BY kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kode;
			$nama = $row->nama;
			$pajak = $row->pajak;
			$retribusi = $row->retribusi;
			$kekayaan = $row->kekayaan;
			$bg_hasil = $row->bg_hasil;
			$sumber_daya = $row->sumber_daya;
			$dau = $row->dau;
			$dak = $row->dak;
			$dok = $row->dok;
			$penyesuaian = $row->penyesuaian;
			$transfer = $row->transfer;
			$hibah = $row->hibah;
			$lainnya = $row->lainnya;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($pajak, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($retribusi, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kekayaan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(0, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bg_hasil, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sumber_daya, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($dau, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($dak, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(0, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($dok, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($penyesuaian, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($transfer, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(0, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($hibah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($lainnya, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(0, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(0, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'lamp_lo';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampII_1($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		/* $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){
			 $no_perda="";
			 $tgl_perda="";
			 $no_pergub="";
			 $tgl_pergub="";
		 } */

		$no_perda = "";
		$tgl_perda = "";
		$no_pergub = "";
		$tgl_pergub = "";

		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.1 : </TD>
						<TD width="30%"  align="left" >Daftar Piutang Daerah<br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . '</TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR PIUTANG DAERAH <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No.</td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian Rincian Piutang</td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Tahun Pengakuan Piutang</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Saldo Awal Piutang</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Penambahan Piutang</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pengurangan Piutang</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Saldo Akhir Piutang</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
				</tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT b.nm_rek4_64 as nama, tahun, ISNULL(saldo_awal,0) saldo_awal, ISNULL(penambahan,0) penambahan, ISNULL(pengurangan,0) pengurangan
					FROM (
					select LEFT(kd_rek5,5) as kode, tahun, SUM(sal_awal+tahun_n) as saldo_awal, SUM(tambah) as penambahan, 
					SUM(kurang) as pengurangan FROM lamp_aset WHERE kd_rek3 IN ('113','114')
					GROUP BY LEFT(kd_rek5,5), tahun) a
					LEFT JOIN 
					ms_rek4_64 b ON  a.kode=b.kd_rek4_64
					ORDER BY tahun
					";
		$tot_sal_awal = 0;
		$tot_penambahan = 0;
		$tot_pengurangan = 0;
		$total = 0;

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nama;
			$saldo_awal = $row->saldo_awal;
			$penambahan = $row->penambahan;
			$pengurangan = $row->pengurangan;
			$tahun = $row->tahun;
			$sal_akhir = $saldo_awal + $penambahan - $pengurangan;
			$tot_sal_awal = $tot_sal_awal + $saldo_awal;
			$tot_penambahan = $tot_penambahan + $penambahan;
			$tot_pengurangan = $tot_pengurangan + $pengurangan;
			$total = $total + $sal_akhir;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right"  valign="top" style="font-size:12px">' . $tahun . '&nbsp; &nbsp;</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_awal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($penambahan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($pengurangan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_akhir, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= '<tr>
							   <td colspan = "3" align="center" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_sal_awal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_penambahan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_pengurangan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($total, "2", ",", ".") . '</td> 
							</tr>';
		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_piutang_daerah';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampII_5($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		/* $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
					$rancang    = "";
                 }
		 if ($test===0){ */
		$no_perda = "";
		$tgl_perda = "";
		$no_pergub = "&nbsp;&nbsp;&nbsp;&nbsp;";
		$tgl_pergub = "";
		$rancang = "Rancangan ";


		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="5%" valign="top" align="right" > Lampiran I.8 :</TD>
						<TD width="95%"  align="left" >' . $rancang . ' Peraturan Daerah Provinsi Kalimantan Barat<br>Nomor : <u>' . $no_pergub . '</u> Tahun 2018 <br>Tentang: Pertanggungjawaban Pelaksanaan Anggaran Pendapatan dan Belanja Daerah Tahun Anggaran ' . $lntahunang . '</TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR REALISASI PENAMBAHAN DAN PENGURANGAN ASET TETAP DAERAH <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Awal</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Penambahan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Pengurangan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Akhir</b></td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
				   </tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT uraian, seq, bold,parent as par,normal, ISNULL(rek,'XXX') as rek FROM map_real_aset ORDER BY kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->uraian;
			$seq = $row->seq;
			$bold = $row->bold;
			$parent = $row->par;
			$normal = $row->normal;
			$rek = $row->rek;
			if ($rek == '') {
				$rek = 'XXX';
			}
			$sql = "SELECT SUM(sal_awal) as saldo_awal, SUM(tambah+tahun_n) as penambahan, 
					SUM(kurang) as pengurangan FROM lamp_aset WHERE kd_rek5 like '$rek%' 
					";
			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$saldo_awal = $row->saldo_awal;
				$penambahan = $row->penambahan;
				$pengurangan = $row->pengurangan;
			}

			switch ($bold) {
				case 2:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;</td> 
							    <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>' . $nama . '</b></td> 
							    <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_awal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($penambahan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($pengurangan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_awal + $penambahan - $pengurangan, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>' . $nama . '</b></td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_awal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($penambahan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($pengurangan, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_awal + $penambahan - $pengurangan, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_real_Aset';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampII_4($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		/* $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){ */
		$no_perda = "";
		$tgl_perda = "";
		$no_pergub = "";
		$tgl_pergub = "";


		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.4 : </TD>
						<TD width="30%"  align="left" >Daftar Penyertaan Modal (Investasi) Daerah<br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR PENYERTAAN MODAL (INVESTASI) DAERAH<BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>No</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>sd/Tahun Penyertaan Modal</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Nama/Badan/Lembaga/Pihak Ketiga</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Dasar Hukum Penyertaan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Bentuk Penyertaan Modal (Investasi) Daerah</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Penyertaan Modal Daerah</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Modal yang Telah disertakan s/d Awal Tahun</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Penyertaan Modal Tahun Ini</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Modal yang Telah disertakan s/d Akhir tahun Ini </b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Sisa Modal Yang Belum disertakan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Hasil Penyertaan Modal Daerah Tahun Ini</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Modal (Investasi) Yang diterima kembali tahun ini</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Sisa Modal (Investasi) Yang disertakan s/d Tahun ini</b></td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9=7+8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10=6-9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13=9-12</td> 
				   </tr>
				</thead>";

		$jum_sal_awal = 0;
		$jum_sal_awal_1 = 0;
		$jum_tahun_n = 0;
		$jum_tahun_n_1 = 0;
		$jum_terima_lagi = 0;
		$no = 0;
		$sql = "SELECT tahun, keterangan, hukum, '' as bentuk, sal_awal, sal_awal+tambah-kurang as sal_awal_1, tahun_n, tahun_n+tambah-kurang as tahun_n_1, 0 as terima_lagi FROM lamp_aset WHERE LEFT(kd_rek5,5)='12201'
					order by tahun, keterangan
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$tahun = $row->tahun;
			$keterangan = $row->keterangan;
			$hukum = $row->hukum;
			$bentuk = $row->bentuk;
			$sal_awal = $row->sal_awal;
			$sal_awal_1 = $row->sal_awal_1;
			$tahun_n = $row->tahun_n;
			$tahun_n_1 = $row->tahun_n_1;
			$terima_lagi = $row->terima_lagi;
			$jum_sal_awal = $jum_sal_awal + $sal_awal;
			$jum_sal_awal_1 = $jum_sal_awal_1 + $sal_awal_1;
			$jum_tahun_n = $jum_tahun_n + $tahun_n;
			$jum_tahun_n_1 = $jum_tahun_n_1 + $tahun_n_1;
			$jum_terima_lagi = $jum_terima_lagi + $jum_terima_lagi;



			$cRet .= '<tr>
							   <td align="center"  valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $tahun . '</td> 
							   <td align="left" valign="top" style="font-size:12px">' . $keterangan . '</td> 
							   <td align="left" valign="top" style="font-size:12px">' . $hukum . '</td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_awal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_awal_1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tahun_n, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tahun_n + $sal_awal_1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_awal - ($tahun_n + $sal_awal_1), "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(0, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($terima_lagi, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(($tahun_n + $sal_awal_1) - $terima_lagi, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan="5" align="center"  valign="top" style="font-size:12px"> JUMLAH</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_sal_awal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_sal_awal_1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_tahun_n, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_tahun_n + $jum_sal_awal_1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_sal_awal - ($jum_tahun_n + $jum_sal_awal_1), "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(0, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_terima_lagi, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format(($jum_tahun_n + $jum_sal_awal_1) - $jum_terima_lagi, "2", ",", ".") . '</td> 
							</tr>';
		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_penyertaan_modal';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampII_9($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		/* $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){ */
		$no_perda = "";
		$tgl_perda = "";
		$no_pergub = "";
		$tgl_pergub = "";


		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.9 : </TD>
						<TD width="30%"  align="left" >Daftar Dana Cadangan Daerah<br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR DANA CADANGAN<BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>No</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Tujuan Pembentukan Dana Cadangan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Dasar Hukum Pembentukan Dana Cadangan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Dana Cadangan Yang direncanakan</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Awal</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Transfer Dari Kas Daerah</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Transfer ke Kas Daerah</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Akhir</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Sisa Dana yang Belum dicadangkan</b></td>
					</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
				  </tr>
				</thead>";



		$cRet .= '<tr>
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							  </tr>';


		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_penyertaan_modal';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampII_11($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		/* $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){ */
		$no_perda = "";
		$tgl_perda = "";
		$no_pergub = "";
		$tgl_pergub = "";


		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.11 : </TD>
						<TD width="30%"  align="left" >Daftar Pinjaman Dan Obligasi Daerah<br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR PINJAMAN DAN OBLIGASI DAERAH<BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>No</b></td>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Sumber Pinjaman Daerah</b></td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Dasar Hukum Pinjaman/Obligasi</b></td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Tanggal/Tahun Perjanjian Pinjaman / Obligasi</b></td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Pinjaman/Nilai Nominal Obligasi</b></td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jangka Waktu Pinjaman</b></td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Persentase Bunga Pinjaman %</b></td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Tujuan Penggunaan Pinjaman</b></td>
					<td colspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Realisasi Pembayaran Tahun Ini</b></td>
					<td colspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Sisa Pembayaran</b></td>
					</tr>
				<tr>
					<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Pokok Pinjaman Daerah</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Bunga</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Pokok Pinjamab Daerah</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Bunga</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
				   </tr>
				</thead>";



		$cRet .= '<tr>
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							  </tr>';


		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_pinjaman_obligasi';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampII_7($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_1 = $lntahunang - 1;
		$lntahunang_2 = $lntahunang - 2;

		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlnogub = "SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$no_perda     = $rowsc->no_perda;
			$tgl_perda     = $rowsc->tgl_perda;
			$no_pergub     = $rowsc->no_pergub;
			$tgl_pergub    = $rowsc->tgl_pergub;
		}
		if ($test === 0) {
			$no_perda = "";
			$tgl_perda = "";
			$no_pergub = "";
			$tgl_pergub = "";
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.7 : </TD>
						<TD width="30%"  align="left" >Daftar Kontruksi Dalam Pengerjaan<br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR KONTRUKSI DALAM PENGERJAAN<BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>No</b></td>
                    <td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian Kegiatan</b></td>
					<td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Lokasi Kegiatan</b></td>
					<td colspan=\"3\" width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Realisasi <br> Tahun N-2, Tahun N-1, Tahun N </b></td>
					<td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Akumulasi Realisasi S/D Akhir Tahun</b></td>
					<td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Yang Dianggarkan Dalam APBD Tahun Berikutnya</b></td>
				</tr>
				<tr>
					<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Realisasi Tahun N-2</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Realisasi Tahun N-1</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Realisasi Tahun N</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7=4+5+6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
				</tr>
				</thead>";

		$jum_sal_2 = 0;
		$jum_sal_1 = 0;
		$jum_sal_n = 0;
		$no = 0;
		$sql = "SELECT hukum  as ket,lokasi,
			SUM(CASE WHEN tahun ='" . $lntahunang_2 . "' THEN (sal_awal+tambah-kurang+tahun_n) ELSE 0 END) AS sal_2,
			SUM(CASE WHEN tahun ='" . $lntahunang_1 . "' THEN (sal_awal+tambah-kurang+tahun_n) ELSE 0 END) AS sal_1,
			SUM(CASE WHEN tahun ='" . $lntahunang . "' THEN (sal_awal+tambah-kurang+tahun_n) ELSE 0 END) AS sal_n
			FROM (SELECT hukum, lokasi, tahun, sal_awal,tambah,kurang,tahun_n  FROM lamp_aset WHERE LEFT(kd_rek5,3)='136'
			GROUP BY hukum, lokasi, tahun, sal_awal,tambah,kurang,tahun_n) a 
			GROUP BY hukum,lokasi
			ORDER BY ket,lokasi
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$ket = $row->ket;
			$lokasi = $row->lokasi;
			$sal_2 = $row->sal_2;
			$sal_1 = $row->sal_1;
			$sal_n = $row->sal_n;
			$jum_sal_2 = $jum_sal_2 + $sal_2;
			$jum_sal_1 = $jum_sal_1 + $sal_1;
			$jum_sal_n = $jum_sal_n + $sal_n;


			$cRet .= '<tr>
							   <td align="center"  valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $ket . '</td> 
							   <td align="left" valign="top" style="font-size:12px">' . $lokasi . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_2, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_n, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($sal_1 + $sal_2 + $sal_n, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan="3" align="center"  valign="top" style="font-size:12px"> JUMLAH</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_sal_2, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_sal_1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_sal_n, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_sal_1 + $jum_sal_2 + $jum_sal_n, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							</tr>';
		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_kdp';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampII_8($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		/* $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
					$rancang    = "";
                 }
		 if ($test===0){ */
		$no_perda = "";
		$tgl_perda = "";
		$no_pergub = "&nbsp;&nbsp;&nbsp;&nbsp;";
		$tgl_pergub = "";
		$rancang = "Rancangan ";




		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="5%" valign="top" align="right" > Lampiran I.9 :</TD>
						<TD width="95%"  align="left" >' . $rancang . ' Peraturan Daerah Provinsi Kalimantan Barat<br>Nomor : <u>' . $no_pergub . '</u> Tahun 2018 <br>Tentang: Pertanggungjawaban Pelaksanaan Anggaran Pendapatan dan Belanja Daerah Tahun Anggaran ' . $lntahunang . '</TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR REALISASI PENAMBAHAN DAN PENGURANGAN ASET LAINNYA <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Awal</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Penambahan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Pengurangan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Akhir</b></td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
				   </tr>
				</thead>";

		$jum_saldo_awal = 0;
		$jum_penambahan = 0;
		$jum_pengurangan = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT uraian, seq, bold,parent as par,normal, ISNULL(rek1,'XXX') as rek1, ISNULL(rek2,'XXX') as rek2 FROM map_real_aset_2 ORDER BY kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->uraian;
			$seq = $row->seq;
			$bold = $row->bold;
			$parent = $row->par;
			$normal = $row->normal;
			$rek1 = $row->rek1;
			$rek2 = $row->rek2;
			if ($rek1 == '') {
				$rek1 = 'XXX';
			}
			if ($rek2 == '') {
				$rek2 = 'XXX';
			}

			$len1 = strlen($rek1);
			$len2 = strlen($rek2);

			$sql = "SELECT ISNULL(SUM(debet-kredit),0) as nilai, (SELECT ISNULL(SUM(debet-kredit),0) as nilai FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
							WHERE (LEFT(kd_rek5,$len1)= '$rek1' or LEFT(kd_rek5,$len2)= '$rek2') AND YEAR(tgl_voucher)<'$lntahunang')  as nilai_lalu 
							FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
							WHERE (LEFT(kd_rek5,$len1)= '$rek1' or LEFT(kd_rek5,$len2)= '$rek2') AND YEAR(tgl_voucher)='$lntahunang' 
					";




			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nilai = $row->nilai;
				$nilai_lalu = $row->nilai_lalu;
			}
			if ($nilai < 0) {
				$tambah = 0;
				$kurang = $nilai * -1;
			} else {
				$kurang = 0;
				$tambah = $nilai;
			}

			if ($bold == 4) {
				//$jum_saldo_awal = $jum_saldo_awal+$nilai_lalu;
				//$jum_penambahan = $jum_penambahan+$penambahan;
				//$jum_pengurangan = $jum_pengurangan+$pengurangan;
			}

			switch ($bold) {
				case 2:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;</td> 
							    <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>' . $nama . '</b></td> 
							    <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $nilai, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah * -1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang * -1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $nilai, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>' . $nama . '</b></td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $nilai, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}
		$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;</b></td> 
							    <td align="right" valign="top" style="font-size:12px"></td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							</tr>';

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_real_Aset2';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampII_3($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$sqlnogub = "SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$no_perda     = $rowsc->no_perda;
			$tgl_perda     = $rowsc->tgl_perda;
			$no_pergub     = $rowsc->no_pergub;
			$tgl_pergub    = $rowsc->tgl_pergub;
		}
		if ($test === 0) {
			$no_perda = "";
			$tgl_perda = "";
			$no_pergub = "";
			$tgl_pergub = "";
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.3 : </TD>
						<TD width="30%"  align="left" >Daftar Dana Bergulir dan Penyisihan Dana Bergulir<brNomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . '</TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR DANA BERGULIR DAN PENYISIHAN DANA BERGULIR<BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>No</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Jumlah Dana Bergulir</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Piutang Dana Bergulir</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Penyisihan Piutang Dana Bergulir</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Piutang Netto Dana Bergulir</b></td>
					</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td>
				</tr>
				</thead>";



		$cRet .= '<tr>
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							   <td align="center"  valign="top" style="font-size:12px">&nbsp;</td> 
							  </tr>';


		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_dana_bergulir';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampII_6($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$sqlnogub = "SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$no_perda     = $rowsc->no_perda;
			$tgl_perda     = $rowsc->tgl_perda;
			$no_pergub     = $rowsc->no_pergub;
			$tgl_pergub    = $rowsc->tgl_pergub;
		}
		if ($test === 0) {
			$no_perda = "";
			$tgl_perda = "";
			$no_pergub = "";
			$tgl_pergub = "";
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.6 : </TD>
						<TD width="30%"  align="left" >Daftar Realisasi Penambahan dan Pengurangan Aset Tak Berwujud dan Amortisasi<br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR REALISASI PENAMBAHAN DAN PENGURANGAN ASET TAK BERWUJUD DAN AMORTISASI <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Awal</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Penambahan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Pengurangan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Akhir</b></td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
				   </tr>
				</thead>";

		$jum_saldo_awal = 0;
		$jum_penambahan = 0;
		$jum_pengurangan = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT uraian, seq, bold,parent as par,normal, ISNULL(rek1,'XXX') as rek1, ISNULL(rek2,'XXX') as rek2 FROM map_real_aset_2 WHERE kode BETWEEN '17' AND '25' ORDER BY kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->uraian;
			$seq = $row->seq;
			$bold = $row->bold;
			$parent = $row->par;
			$normal = $row->normal;
			$rek1 = $row->rek1;
			$rek2 = $row->rek2;
			if ($rek1 == '') {
				$rek1 = 'XXX';
			}
			if ($rek2 == '') {
				$rek2 = 'XXX';
			}

			$len1 = strlen($rek1);
			$len2 = strlen($rek2);
			$sql = "SELECT ISNULL(SUM(debet-kredit),0) as nilai, (SELECT ISNULL(SUM(debet-kredit),0) as nilai FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
							WHERE (LEFT(kd_rek5,$len1)= '$rek1' or LEFT(kd_rek5,$len2)= '$rek2') AND YEAR(tgl_voucher)<'$lntahunang')  as nilai_lalu 
							FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
							WHERE (LEFT(kd_rek5,$len1)= '$rek1' or LEFT(kd_rek5,$len2)= '$rek2') AND YEAR(tgl_voucher)='$lntahunang' 
					";




			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nilai = $row->nilai;
				$nilai_lalu = $row->nilai_lalu;
			}
			if ($nilai < 0) {
				$tambah = 0;
				$kurang = $nilai * -1;
			} else {
				$kurang = 0;
				$tambah = $nilai;
			}

			if ($bold == 4) {
				//$jum_saldo_awal = $jum_saldo_awal+$nilai_lalu;
				//$jum_penambahan = $jum_penambahan+$penambahan;
				//$jum_pengurangan = $jum_pengurangan+$pengurangan;
			}

			switch ($bold) {

				case 4:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $nilai, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah * -1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang * -1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $nilai, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>' . $nama . '</b></td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $nilai, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}
		$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;</b></td> 
							    <td align="right" valign="top" style="font-size:12px"></td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							   <td align="right" valign="top" style="font-size:12px"></td> 
							</tr>';

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_real_Aset2';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampII_2($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$cthnsbl = $lntahunang - 1;
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$sqlnogub = "SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$no_perda     = $rowsc->no_perda;
			$tgl_perda     = $rowsc->tgl_perda;
			$no_pergub     = $rowsc->no_pergub;
			$tgl_pergub    = $rowsc->tgl_pergub;
		}
		if ($test === 0) {
			$no_perda = "";
			$tgl_perda = "";
			$no_pergub = "";
			$tgl_pergub = "";
		}

		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.2 : </TD>
						<TD width="30%"  align="left" >Daftar Penyisihan Piutang Daerah <br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR PENYISIHAN PIUTANG DAERAH <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No.</td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Saldo Piutang</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Penyisihan Piutang</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Piutang (Netto)</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5 = (3-4)</td> 
				</tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		/*
			$sql = "select kode1, kode, nama, tahun, bulan, jumlah,  sal_awal,  kurang,  
						tambah, tahun_n, keterangan, umur_t, isnull(umur_b,1) umur_b, 
						case when left(kode1,5)=11301 and umur_t<=1 then 
						cast((sal_awal-kurang+tambah+tahun_n) * 0.005 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>1 and umur_t<=2 then 
						cast((sal_awal-kurang+tambah+tahun_n) * 0.1 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>2 and umur_t<=5 then
						cast((sal_awal-kurang+tambah+tahun_n)* 0.5 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>5 then
						cast((sal_awal-kurang+tambah+tahun_n) * 1 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)<=1 then
						cast((sal_awal-kurang+tambah+tahun_n) * 0.005 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
						cast((sal_awal-kurang+tambah+tahun_n) * 0.1 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
						cast((sal_awal-kurang+tambah+tahun_n)* 0.5 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t>=1 then 
						cast((sal_awal-kurang+tambah+tahun_n) * 1 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)<1 then 
						cast((sal_awal-kurang+tambah+tahun_n) * 0.005 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=1 then 
						cast((sal_awal-kurang+tambah+tahun_n) * 0.1 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)>2 and isnull(umur_b,1)<=2 then 
						cast((sal_awal-kurang+tambah+tahun_n)* 0.5 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t>=0 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=3 then 
						cast((sal_awal-kurang+tambah+tahun_n) * 1 as decimal(20,2))
						else 0 end as penyi_piu 
						FROM
						(SELECT kd_rek5 as kode1, kd_rek5 as kode, nm_rek5 as nama, tahun, isnull(bulan,1) bulan, jumlah,   
												sal_awal,  kurang,  tambah, tahun_n, keterangan, case when 13-isnull(bulan,1)=12 then 2015-tahun+1 else 2015-tahun end 
												as umur_t, case when 13-isnull(bulan,1)=12 then 0 else 13-isnull(bulan,1) end as umur_b FROM lamp_aset where kd_rek3 
												in (113,114) and tahun<= 2015)a
					 order by kode,tahun";
					 */
		$sql = "SELECT kode1, nm_rek5, kd_piutang
							,SUM(CASE WHEN a.tahun1 ='$cthnsbl' THEN a.saldo ELSE 0 END) AS saldo_2016
							,SUM(CASE WHEN a.tahun1 ='$cthnsbl' THEN b.nilai ELSE 0 END) AS susut_2016
							,SUM(CASE WHEN a.tahun1 ='$lntahunang' THEN a.saldo ELSE 0 END) AS saldo_2017
							,SUM(CASE WHEN a.tahun1 ='$lntahunang' THEN b.nilai ELSE 0 END) AS susut_2017
							 FROM (
							SELECT kd_skpd, kode1, nm_rek5,tahun1, kd_piutang, saldo FROM
							(
							SELECT kd_skpd, kode1, nm_rek5,tahun1, SUM(saldo) as saldo FROM 
							(
							SELECT kd_skpd,kd_rek5 as kode1,nm_rek5, '$cthnsbl' as tahun1, sal_awal as saldo
							FROM lamp_aset where kd_rek3 in (113,114) AND sal_awal>0
							UNION ALL
							SELECT kd_skpd,kd_rek5 as kode1,nm_rek5, '$lntahunang' as tahun1, sal_awal-kurang+tambah+tahun_n as saldo
							FROM lamp_aset where kd_rek3 in (113,114)
							) a GROUP BY kd_skpd,kode1, tahun1,nm_rek5
							) a LEFT JOIN ms_piutang b ON LEFT(a.kode1,5)=b.kd_rek4
							) a LEFT JOIN 
							(SELECT b.kd_unit, b.kd_rek5, YEAR(tgl_voucher) tgl, ISNULL(SUM(kredit-debet),0) as nilai FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
														WHERE (LEFT(kd_rek5,3)= '115')  GROUP BY b.kd_unit, b.kd_rek5,YEAR(tgl_voucher))b
							ON a.kd_skpd=b.kd_unit AND a.kd_piutang=b.kd_rek5 AND a.tahun1=b.tgl
							WHERE LEFT(kd_piutang,5)='11501'
							GROUP BY kode1,nm_rek5,kd_piutang";
		$tot_saldo_2015 = 0;
		$tot_susut = 0;
		$total = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nm_rek5;
			//$tahun = $row->tahun;
			$saldo_2014 = $row->saldo_2016;
			$saldo_2015 = $row->saldo_2017;
			$susut_2014 = $row->susut_2016;
			$susut_2015 = $row->susut_2017;
			$susut_nil = $susut_2014 + $susut_2015;
			$saldo_akhir = $saldo_2015 - $susut_nil;
			$tot_saldo_2015 = $tot_saldo_2015 + $saldo_2015;
			$tot_susut = $tot_susut + $susut_nil;
			$total = $total + $saldo_akhir;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_2015, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($susut_nil, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_2015 - $susut_nil, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan = "2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_saldo_2015, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_susut, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($total, "2", ",", ".") . '</td> 
							</tr>';

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_penyisihan_piutang_daerah';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampII_10($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_lalu = $lntahunang - 1;
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$sqlnogub = "SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$no_perda     = $rowsc->no_perda;
			$tgl_perda     = $rowsc->tgl_perda;
			$no_pergub     = $rowsc->no_pergub;
			$tgl_pergub    = $rowsc->tgl_pergub;
		}
		if ($test === 0) {
			$no_perda = "";
			$tgl_perda = "";
			$no_pergub = "";
			$tgl_pergub = "";
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran II.10 : </TD>
						<TD width="30%"  align="left" >Daftar Kewajiban Jangka Pendek<br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>DAFTAR KEWAJIBAN JANGKA PENDEK <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Awal</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Penambahan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Pengurangan</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Saldo Akhir</b></td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
				   </tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT uraian, seq, bold,parent as par,normal, ISNULL(rek,'XXX') as rek FROM map_utang ORDER BY kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->uraian;
			$seq = $row->seq;
			$bold = $row->bold;
			$parent = $row->par;
			$normal = $row->normal;
			$rek = $row->rek;
			if ($rek == '') {
				$rek = 'XXX';
			}
			$len = strlen($rek);
			$sql = "SELECT ISNULL(SUM(kredit-debet),0) as nilai, (SELECT ISNULL(SUM(kredit-debet),0) as nilai FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
							WHERE LEFT(b.kd_rek5,$len)='$rek' AND YEAR(tgl_voucher)<'$lntahunang')  as nilai_lalu 
							FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
							WHERE LEFT(b.kd_rek5,$len)='$rek' AND YEAR(tgl_voucher)='$lntahunang' 
					";
			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nilai = $row->nilai;
				$nilai_lalu = $row->nilai_lalu;
			}
			if ($nilai < 0) {
				$tambah = 0;
				$kurang = $nilai * -1;
			} else {
				$kurang = 0;
				$tambah = $nilai;
			}

			switch ($bold) {
				case 2:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;</td> 
							    <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>' . $nama . '</b></td> 
							    <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							   <td align="right" valign="top" style="font-size:12px"> </td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $tambah - $kurang, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:12px">&nbsp;&nbsp;<b>' . $nama . '</b></td> 
							    <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tambah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($kurang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($nilai_lalu + $tambah - $kurang, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_real_Aset';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_penyi($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$cthnsbl = $lntahunang - 1;
		$lcskpd = $kdskpd;
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR PENYISIHAN PIUTANG DAERAH <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No.</td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Piutang $cthnsbl</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Piutang $lntahunang</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Penyisihan Piutang $cthnsbl</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Penyisihan Piutang $lntahunang</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
				</tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		/*$sql = "	SELECT a.kd_skpd, a.nm_skpd, ISNULL(saldo_2014,0) as saldo_2014, ISNULL(saldo_2015,0) as saldo_2015,
						ISNULL(penyi_2014,0) as penyi_2014, ISNULL(penyi_2015,0) as penyi_2015
						FROM
						ms_skpd a 
						LEFT JOIN (
						SELECT kd_skpd, ISNULL(SUM(saldo_2014),0) as saldo_2014,ISNULL(SUM(saldo_2015),0) as saldo_2015,
						ISNULL(SUM(penyi_2014),0) as penyi_2014,ISNULL(SUM(penyi_2015),0) as penyi_2015
						FROM(select kd_skpd,saldo_2014, saldo_2015, case when left(kode1,5)=11301 and umur_t<=1 then 
						cast((saldo_2014) * 0.005 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>1 and umur_t<=2 then 
						cast((saldo_2014) * 0.1 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>2 and umur_t<=5 then
						cast((saldo_2014)* 0.5 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>5 then
						cast((saldo_2014) * 1 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)<=1 then
						cast((saldo_2014) * 0.005 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
						cast((saldo_2014) * 0.1 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
						cast((saldo_2014)* 0.5 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t>=1 then 
						cast((saldo_2014) * 1 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)<1 then 
						cast((saldo_2014) * 0.005 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=1 then 
						cast((saldo_2014) * 0.1 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)>2 and isnull(umur_b,1)<=2 then 
						cast((saldo_2014)* 0.5 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t>=0 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=3 then 
						cast((saldo_2014) * 1 as decimal(20,2))
						else 0 end as penyi_2014 ,
						case when left(kode1,5)=11301 and umur_t<=1 then 
						cast((saldo_2015) * 0.005 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>1 and umur_t<=2 then 
						cast((saldo_2015) * 0.1 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>2 and umur_t<=5 then
						cast((saldo_2015)* 0.5 as decimal(20,2))
						when left(kode1,5)=11301 and umur_t>5 then
						cast((saldo_2015) * 1 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)<=1 then
						cast((saldo_2015) * 0.005 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=3 then 
						cast((saldo_2015) * 0.1 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t<1 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=12 then 
						cast((saldo_2015)* 0.5 as decimal(20,2))
						when left(kode1,5)=11302 and umur_t>=1 then 
						cast((saldo_2015) * 1 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)<1 then 
						cast((saldo_2015) * 0.005 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)>1 and isnull(umur_b,1)<=1 then 
						cast((saldo_2015) * 0.1 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t<1 and isnull(umur_b,1)>2 and isnull(umur_b,1)<=2 then 
						cast((saldo_2015)* 0.5 as decimal(20,2))
						when left(kode1,5) not in (11301,11302) and umur_t>=0 and isnull(umur_b,1)>3 and isnull(umur_b,1)<=3 then 
						cast((saldo_2015) * 1 as decimal(20,2))
						else 0 end as penyi_2015
						FROM
						(
						SELECT kd_skpd,kd_rek5 as kode1,tahun, isnull(bulan,1) bulan, jumlah,   
						case when sal_awal>0 then sal_awal else 0 end as saldo_2014, 
						sal_awal-kurang+tambah+tahun_n as saldo_2015, case when 13-isnull(bulan,1)=12 then 2015-tahun+1 else 2015-tahun end 
																		as umur_t, case when 13-isnull(bulan,1)=12 then 0 else 13-isnull(bulan,1) end as umur_b FROM lamp_aset where kd_rek3 
																		in (113,114))a) b
						 GROUP BY kd_skpd ) b
						ON a.kd_skpd=b.kd_skpd";
						
					*/
		$sql = "SELECT a.kd_skpd, a.nm_skpd, ISNULL(saldo_2014,0) as saldo_2014, ISNULL(saldo_2015,0) as saldo_2015,
						ISNULL(susut_2014,0) as penyi_2014, ISNULL(susut_2015,0) as penyi_2015
						FROM
						ms_skpd a 
						LEFT JOIN (
						SELECT a.kd_skpd
						,SUM(CASE WHEN a.tahun1 ='$cthnsbl' THEN a.saldo ELSE 0 END) AS saldo_2014
						,SUM(CASE WHEN a.tahun1 ='$cthnsbl' THEN b.nilai ELSE 0 END) AS susut_2014
						,SUM(CASE WHEN a.tahun1 ='$lntahunang' THEN a.saldo ELSE 0 END) AS saldo_2015
						,SUM(CASE WHEN a.tahun1 ='$lntahunang' THEN b.nilai ELSE 0 END) AS susut_2015
						 FROM (
						SELECT kd_skpd, kode1, nm_rek5,tahun1, kd_piutang, saldo FROM
						(
						SELECT kd_skpd, kode1, nm_rek5,tahun1, SUM(saldo) as saldo FROM 
						(
						SELECT kd_skpd,kd_rek5 as kode1,nm_rek5, '$cthnsbl' as tahun1, sal_awal as saldo
						FROM lamp_aset where kd_rek3 in (113,114) AND sal_awal>0
						UNION ALL
						SELECT kd_skpd,kd_rek5 as kode1,nm_rek5, '$lntahunang' as tahun1, sal_awal-kurang+tambah+tahun_n as saldo
						FROM lamp_aset where kd_rek3 in (113,114)
						) a GROUP BY kd_skpd,kode1, tahun1,nm_rek5
						) a LEFT JOIN ms_piutang b ON LEFT(a.kode1,5)=b.kd_rek4
						) a LEFT JOIN 
						(SELECT b.kd_unit, b.kd_rek5, YEAR(tgl_voucher) tgl, ISNULL(SUM(debet-kredit),0) as nilai FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
													WHERE (LEFT(kd_rek5,3)= '115')  GROUP BY b.kd_unit, b.kd_rek5,YEAR(tgl_voucher))b
						ON a.kd_skpd=b.kd_unit AND a.kd_piutang=b.kd_rek5 AND a.tahun1=b.tgl
						WHERE LEFT(kd_piutang,5)='11501'
						GROUP BY kd_skpd) b 
						ON a.kd_skpd=b.kd_skpd
						ORDER BY a.kd_skpd";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kd_skpd;
			$nama = $row->nm_skpd;
			$saldo_2014 = $row->saldo_2014;
			$saldo_2015 = $row->saldo_2015;
			$penyi_2014 = $row->penyi_2014;
			$penyi_2015 = $row->penyi_2015;
			$penyi_nil = $penyi_2014 + $penyi_2015;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_2014, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($saldo_2015, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($penyi_2014, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($penyi_nil, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_penyisihan_piutang_daerah';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_kertas_kerja_tes($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>LAPORAN REALISASI ANGGARAN PER-SKPD <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>";
		for ($j = 1; $j <= 3; $j++) {
			"<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR</b>";
			"</td>";
		}
		"</tr>";
		"</thead>";


		/* echo "<table border=1>";
				for($i=1; $i <=5; $i++){
					echo "<tr>";
					for($j=1; $j<=7; $j++){
				echo "<td>";
				echo $i.$j;
				echo "</td>";
					}
				}
			
			echo "</table>";  */

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_real_Aset';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_kertas_kerja_lra($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>LAPORAN REALISASI ANGGARAN PER-SKPD <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR (UNIT PENGEMBANGAN DAN LATIHAN KEGIATAN BELAJAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR (UNIT PENELITIAN DAN PENGEMBANGAN TEKNOLOGI PENDIDIKAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR ( UNIT TAMAN BUDAYA PROVINSI KALBAR )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR ( UNIT MUSEUM PROVINSI KALBAR )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (AKPER SINTANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UNIT PELATIHAN KESEHATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UNIT LABORATORIUM KESEHATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UNIT PENGOBATAN PARU PARU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (POLIKLINIK)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UPT PELAYANAN KESEHATAN PARU PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UPT KESEHATAN KERJA DAN OLAHRAGA MASYARAKAT PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>RUMAH SAKIT UMUM DAERAH Dr. SOEDARSO PONTIANAK</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>RUMAH SAKIT JIWA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>RUMAH SAKIT JIWA DAERAH SUNGAI BANGKONG PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM PROVINSI KALBAR (UNIT PENGUJIAN MUTU DAN PEMBINAAN JASA KONSTRUKSI PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH I</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH II</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH III</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH IV</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH V</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR (UNIT PENGUJIAN MUTU DAN PEMBINAAN JASA KONSTRUKSI PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR  (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH I PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR  (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH II PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH III PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR   (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH IV PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR   (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH V PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERUMAHAN RAKYAT, KAWASAN PERMUKIMAN DAN LINGKUNGAN HIDUP PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERENCANAAN PEMBANGUNAN DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERENCANAAN PEMBANGUNAN DAERAH PROVINSI KALBAR (UNIT DATA DAN STATISTIK PEMBANGUNAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KANTOR PENELITIAN DAN PENGEMBANGAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENELITIAN DAN PENGEMBANGAN PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI DAN INFORMATIKA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI DAN INFORMATIKA PROVINSI KALBAR (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH I)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI DAN INFORMATIKA PROVINSI KALBAR (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH II)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI & INFORMATIKA PROV.KALBAR (UNIT PERBAIKAN DAN PEMELIHARAAN KENDARAAN BERMOTOR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGOLAHAN DATA ELEKTRONIK DINAS PERHUBUNGAN, KOMUNIKASI DAN INFORMATIKA PROVINSI KALIMANTAN BARAT</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR  (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH I PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR  (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH II  PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR (UNIT PERBAIKAN DAN PEMELIHARAAN KENDARAAN BERMOTOR  PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN LINGKUNGAN HIDUP PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK DAN KELUARGA BERENCANA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEMBERDAYAAN PEREMPUAN DAN  PERLINDUNGAN ANAK  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UNIT PELAYANAN DAN REHABILITASI SOSIAL)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UPT PANTI SOSIAL DAN REHABILITASI LANJUT USIA MUSTIKA DHARMA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UPT PANTI SOSIAL LANJUT USIA MULIA DHARMA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UPT PANTI SOSIAL ANAK PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT PENGEMBANGAN PRODUKTIVITAS DAERAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT PELAYANAN HIPERKES DAN KESELAMATAN KERJA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT LATIHAN KERJA INDUSTRI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT LATIHAN KERJA INDUSTRI ENTIKONG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT PELATIHAN TRANSMIGRASI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UPT PENGAWASAN KETENAGAKERJAAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UPT LABORATORIUM KESEHATAN KERJA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI DAN UMKM PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI DAN UMKM PROVINSI KALBAR (BALAI PELATIHAN KOPERASI, USAHA KECIL, MIKRO DAN MENENGAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI, USAHA KECIL DAN MENENGAH  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI, USAHA KECIL DAN MENENGAH  PROV. KALBAR  (BALAI PELATIHAN KOPERASI , USAHA MIKRO, KECIL DAN MENENGAH PROV.  KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI, USAHA KECIL DAN MENENGAH  PROV. KALBAR(UPT PELATIHAN KOPERASI PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEMUDA DAN OLAH RAGA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEPEMUDAAN,  OLAH RAGA DAN PARIWISATA  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KESATUAN BANGSA DAN POLITIK PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENANGGULANGAN BENCANA DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SATUAN POLISI PAMONG PRAJA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DEWAN PERWAKILAN RAKYAT DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KEPALA DAERAH DAN WAKIL KEPALA DAERAH</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO UMUM)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO HUMAS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PEMERINTAHAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO KEPENDUDUKAN DAN PENCATATAN SIPIL)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO HUKUM)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PEREKONOMIAN DAN PEMBANGUNAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO KESEJAHTERAAN SOSIAL)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO ORGANISASI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO HUBUNGAN MASYARAKAT  DAN PROTOKOL SETDA PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PEREKONOMIAN SETDA PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO KESEJAHTERAAN RAKYAT SETDA PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO ADMINISTRASI PEMBANGUNAN DAN PENGADAAN  BARANG/JASA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PENGELOLAAN ASET)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DPRD PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KEPEGAWAIAN DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENDIDIKAN DAN PELATIHAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>INSPEKTORAT PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD PONTIANAK WILAYAH I)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD PONTIANAK WILAYAH II )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SINGKAWANG )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD MEMPAWAH )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SAMBAS )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SANGGAU )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SINTANG )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD PUTUSSIBAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD KETAPANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD NGABANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD BENGKAYANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD KUBU RAYA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD SEKADAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD MELAWI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD KAYONG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KANTOR PERWAKILAN PROVINSI KALBAR DI JAKARTA</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT KORPRI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PEMBANGUNAN PERBATASAN DAN DAERAH TERTINGGAL PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGELOLA POS PEMERIKSAAN LINTAS BATAS ENTIKONG PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGELOLA POS PEMERIKSAAN LINTAS BATAS ARUK PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGELOLA POS PEMERIKSAAN LINTAS BATAS BADAU PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN ASSET DAERAH</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN ASSET DAERAH / PPKD</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KANTOR LAYANAN PENGADAAN BARANG / JASA PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGEMBANGAN  SUMBER DAYA MANUSIA PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH WILAYAH I)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH WILAYAH II)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SINGKAWANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR  (UNIT PELAYANAN PENDAPATAN DAERAH MEMPAWAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SAMBAS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SANGGAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SINTANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH PUTUSSIBAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH KETAPANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH NGABANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH BENGKAYANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH KUBU RAYA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SEKADAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH MELAWI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH KAYONG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR / PPKD</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGHUBUNG PROV. KALBAR DI JAKARTA</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KETAHANAN PANGAN DAN PENYULUHAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KETAHANAN PANGAN DAN PENYULUHAN PROVINSI KALBAR (BPLP3K ANJUNGAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR (BALAI PENDIDIKAN DAN LATIHAN PENYULUH PERTANIAN,PERIKANAN DAN KEHUTANAN PROV.KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR  (UNIT LABORATORIUM KESEHATAN HEWAN DAN KESEHATAN MASYARAKAT VETERINER)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR (UNIT PEMBIBITAN TERNAK DAN PAKAN TERNAK PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PEMBERDAYAAN MASYARAKAT DAN PEMERINTAHAN DESA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PEMBERDAYAAN MASYARAKAT DAN DESA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT KOMISI PENYIARAN INDONESIA DAERAH PROVINSI KALIMANTAN BARAT</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOMUNIKASI DAN INFORMATIKA PROV. KALBAR.</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOMUNIKASI DAN INFORMATIKA PROV. KALBAR  (SEKRETARIAT KOMISI PENYIARAN INDONESIA DAERAH PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOMUNIKASI DAN INFORMATIKA PROV. KALBAR  (UNIT PENGOLAHAN DATA ELEKTRONIK PROVINSI KALIMANTAN BARAT)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERPUSTAKAAN, KEARSIPAN DAN DOKUMENTASI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERPUSTAKAAN, KEARSIPAN DAN DOKUMENTASI PROVINSI KALBAR (UNIT PELAYANAN PERPUSTAKAAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PERPUSTAKAAN DAN  KEARSIPAN PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PERPUSTAKAAN DAN  KEARSIPAN PROV. KALBAR  (UNIT PELAYANAN PERPUSTAKAAN PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TANAMAN PANGAN DAN HORTIKULTURA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (SPP-SPMA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PENGAWASAN DAN SERTIFIKASI BENIH TPH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PROTEKSI TPH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PENGEMBANGAN BENIH TPH KAKAP)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PEMBENIHAN INDUK TPH ANJUNGAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PENGELOLA TERMINAL AGRIBISNIS TERPADU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT PEMBENIHAN TANAMAN PANGAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT BALAI BENIH INDUK HORTIKULTURA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT DIKLAT PERTANIAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT PERLINDUNGAN TANAMAN PANGAN DAN HORTIKULTURA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT PENGAWASAN DAN SERTIFIKASI BENIH PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERKEBUNAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERKEBUNAN PROVINSI KALBAR (BALAI PERBENIHAN TANAMAN PERKEBUNAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERKEBUNAN PROVINSI KALBAR (UPT PENGAWASAN DAN SERTIFIKASI BENIH PERKEBUNAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PETERNAKAN DAN KESEHATAN HEWAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PETERNAKAN DAN KESEHATAN HEWAN PROVINSI KALBAR (UNIT LABKESWAN DAN KESMAVET)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PETERNAKAN DAN KESEHATAN HEWAN PROV. KALBAR ( UNIT PEMBIBITAN DAN PAKAN TERNAK )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT INVENTARISASI DAN PEMETAAN HUTAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PENANGGULANGAN KEBAKARAN HUTAN DAN LAHAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SAMBAS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN BENGKAYANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN MEMPAWAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN LANDAK)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KUBU RAYA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN MELAWI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAYONG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KETAPANG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KETAPANG SELATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SEKADAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SANGGAU BARAT)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SANGGAU TIMUR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SINTANG TIMUR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SINTANG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAPUAS HULU UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAPUAS HULU TIMUR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAPUAS HULU SELATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN EKOSISTEM LAHAN BASAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS TAMAN HUTAN RAYA PANDAN PULOH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS MUSEUM KEHUTANAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTAMBANGAN DAN ENERGI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  ENERGI DAN SUMBER DAYA MINERAL  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PARIWISATA DAN EKONOMI KREATIF PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPTD-BBIS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UNIT LPPMHP)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UNIT UPPP)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPT PELABUHAN PERIKANAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPT PENERAPAN MUTU HASIL PERIKANAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPT PERIKANAN BUDIDAYA AIR PAYAU DAN LAUT PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UNIT PENGAWASAN DAN SERTIFIKASI MUTU BARANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UNIT PELATIHAN INDUSTRI KECIL MENENGAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UPT PENGUJIAN DAN SERTIFIKASI MUTU BARANG PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UPT INDUSTRI PANGAN OLAHAN DAN KEMASAN PROVINSI KALBAR)</b></td>
                </tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT uraian, seq, cetak, bold,parent as par, isnull(kode_1,'-') as rek, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3 FROM map_lra_prov ORDER BY seq
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->uraian;
			$seq = $row->seq;
			$cetak = $row->cetak;
			$bold = $row->bold;
			$parent = $row->par;
			$n = $row->rek;
			$n = ($n == "-" ? "'-'" : $n);
			$n2 = $row->kode_2;
			$n2 = ($n2 == "-" ? "'-'" : $n2);
			$n3 = $row->kode_3;
			$n3 = ($n3 == "-" ? "'-'" : $n3);
			$sql = "SELECT 
					     SUM(CASE WHEN a.kd_unit='1.01.01.01' THEN ($cetak) ELSE 0 END) AS a1010101
						,SUM(CASE WHEN a.kd_unit='1.01.01.02' THEN ($cetak) ELSE 0 END) AS a1010102
						,SUM(CASE WHEN a.kd_unit='1.01.01.03' THEN ($cetak) ELSE 0 END) AS a1010103
						,SUM(CASE WHEN a.kd_unit='1.01.01.04' THEN ($cetak) ELSE 0 END) AS a1010104
						,SUM(CASE WHEN a.kd_unit='1.01.01.05' THEN ($cetak) ELSE 0 END) AS a1010105
						,SUM(CASE WHEN a.kd_unit='1.02.01.01' THEN ($cetak) ELSE 0 END) AS a1020101
						,SUM(CASE WHEN a.kd_unit='1.02.01.02' THEN ($cetak) ELSE 0 END) AS a1020102
						,SUM(CASE WHEN a.kd_unit='1.02.01.03' THEN ($cetak) ELSE 0 END) AS a1020103
						,SUM(CASE WHEN a.kd_unit='1.02.01.04' THEN ($cetak) ELSE 0 END) AS a1020104
						,SUM(CASE WHEN a.kd_unit='1.02.01.05' THEN ($cetak) ELSE 0 END) AS a1020105
						,SUM(CASE WHEN a.kd_unit='1.02.01.06' THEN ($cetak) ELSE 0 END) AS a1020106
						,SUM(CASE WHEN a.kd_unit='1.02.01.07' THEN ($cetak) ELSE 0 END) AS a1020107
						,SUM(CASE WHEN a.kd_unit='1.02.01.08' THEN ($cetak) ELSE 0 END) AS a1020108
						,SUM(CASE WHEN a.kd_unit='1.02.02.01' THEN ($cetak) ELSE 0 END) AS a1020201
						,SUM(CASE WHEN a.kd_unit='1.02.03.01' THEN ($cetak) ELSE 0 END) AS a1020301
						,SUM(CASE WHEN a.kd_unit='1.02.04.01' THEN ($cetak) ELSE 0 END) AS a1020401
						,SUM(CASE WHEN a.kd_unit='1.03.01.01' THEN ($cetak) ELSE 0 END) AS a1030101
						,SUM(CASE WHEN a.kd_unit='1.03.01.02' THEN ($cetak) ELSE 0 END) AS a1030102
						,SUM(CASE WHEN a.kd_unit='1.03.01.03' THEN ($cetak) ELSE 0 END) AS a1030103
						,SUM(CASE WHEN a.kd_unit='1.03.01.04' THEN ($cetak) ELSE 0 END) AS a1030104
						,SUM(CASE WHEN a.kd_unit='1.03.01.05' THEN ($cetak) ELSE 0 END) AS a1030105
						,SUM(CASE WHEN a.kd_unit='1.03.01.06' THEN ($cetak) ELSE 0 END) AS a1030106
						,SUM(CASE WHEN a.kd_unit='1.03.01.07' THEN ($cetak) ELSE 0 END) AS a1030107
						,SUM(CASE WHEN a.kd_unit='1.03.02.01' THEN ($cetak) ELSE 0 END) AS a1030201
						,SUM(CASE WHEN a.kd_unit='1.03.02.02' THEN ($cetak) ELSE 0 END) AS a1030202
						,SUM(CASE WHEN a.kd_unit='1.03.02.03' THEN ($cetak) ELSE 0 END) AS a1030203
						,SUM(CASE WHEN a.kd_unit='1.03.02.04' THEN ($cetak) ELSE 0 END) AS a1030204
						,SUM(CASE WHEN a.kd_unit='1.03.02.05' THEN ($cetak) ELSE 0 END) AS a1030205
						,SUM(CASE WHEN a.kd_unit='1.03.02.06' THEN ($cetak) ELSE 0 END) AS a1030206
						,SUM(CASE WHEN a.kd_unit='1.03.02.07' THEN ($cetak) ELSE 0 END) AS a1030207
						,SUM(CASE WHEN a.kd_unit='1.04.01.01' THEN ($cetak) ELSE 0 END) AS a1040101
						,SUM(CASE WHEN a.kd_unit='1.06.01.01' THEN ($cetak) ELSE 0 END) AS a1060101
						,SUM(CASE WHEN a.kd_unit='1.06.01.02' THEN ($cetak) ELSE 0 END) AS a1060102
						,SUM(CASE WHEN a.kd_unit='1.06.02.01' THEN ($cetak) ELSE 0 END) AS a1060201
						,SUM(CASE WHEN a.kd_unit='1.06.03.01' THEN ($cetak) ELSE 0 END) AS a1060301
						,SUM(CASE WHEN a.kd_unit='1.07.01.01' THEN ($cetak) ELSE 0 END) AS a1070101
						,SUM(CASE WHEN a.kd_unit='1.07.01.02' THEN ($cetak) ELSE 0 END) AS a1070102
						,SUM(CASE WHEN a.kd_unit='1.07.01.03' THEN ($cetak) ELSE 0 END) AS a1070103
						,SUM(CASE WHEN a.kd_unit='1.07.01.04' THEN ($cetak) ELSE 0 END) AS a1070104
						,SUM(CASE WHEN a.kd_unit='1.07.01.05' THEN ($cetak) ELSE 0 END) AS a1070105
						,SUM(CASE WHEN a.kd_unit='1.07.02.01' THEN ($cetak) ELSE 0 END) AS a1070201
						,SUM(CASE WHEN a.kd_unit='1.07.02.02' THEN ($cetak) ELSE 0 END) AS a1070202
						,SUM(CASE WHEN a.kd_unit='1.07.02.03' THEN ($cetak) ELSE 0 END) AS a1070203
						,SUM(CASE WHEN a.kd_unit='1.07.02.04' THEN ($cetak) ELSE 0 END) AS a1070204
						,SUM(CASE WHEN a.kd_unit='1.08.01.01' THEN ($cetak) ELSE 0 END) AS a1080101
						,SUM(CASE WHEN a.kd_unit='1.10.01.01' THEN ($cetak) ELSE 0 END) AS a1100101
						,SUM(CASE WHEN a.kd_unit='1.11.01.01' THEN ($cetak) ELSE 0 END) AS a1110101
						,SUM(CASE WHEN a.kd_unit='1.11.02.01' THEN ($cetak) ELSE 0 END) AS a1110201
						,SUM(CASE WHEN a.kd_unit='1.13.01.01' THEN ($cetak) ELSE 0 END) AS a1130101               
						,SUM(CASE WHEN a.kd_unit='1.13.01.02' THEN ($cetak) ELSE 0 END) AS a1130102
						,SUM(CASE WHEN a.kd_unit='1.13.01.03' THEN ($cetak) ELSE 0 END) AS a1130103
						,SUM(CASE WHEN a.kd_unit='1.13.01.04' THEN ($cetak) ELSE 0 END) AS a1130104
						,SUM(CASE WHEN a.kd_unit='1.13.01.05' THEN ($cetak) ELSE 0 END) AS a1130105
						,SUM(CASE WHEN a.kd_unit='1.14.01.01' THEN ($cetak) ELSE 0 END) AS a1140101
						,SUM(CASE WHEN a.kd_unit='1.14.01.02' THEN ($cetak) ELSE 0 END) AS a1140102
						,SUM(CASE WHEN a.kd_unit='1.14.01.03' THEN ($cetak) ELSE 0 END) AS a1140103
						,SUM(CASE WHEN a.kd_unit='1.14.01.04' THEN ($cetak) ELSE 0 END) AS a1140104
						,SUM(CASE WHEN a.kd_unit='1.14.01.05' THEN ($cetak) ELSE 0 END) AS a1140105
						,SUM(CASE WHEN a.kd_unit='1.14.01.06' THEN ($cetak) ELSE 0 END) AS a1140106
						,SUM(CASE WHEN a.kd_unit='1.14.01.07' THEN ($cetak) ELSE 0 END) AS a1140107
						,SUM(CASE WHEN a.kd_unit='1.14.01.08' THEN ($cetak) ELSE 0 END) AS a1140108
						,SUM(CASE WHEN a.kd_unit='1.15.01.01' THEN ($cetak) ELSE 0 END) AS a1150101
						,SUM(CASE WHEN a.kd_unit='1.15.01.02' THEN ($cetak) ELSE 0 END) AS a1150102
						,SUM(CASE WHEN a.kd_unit='1.15.01.03' THEN ($cetak) ELSE 0 END) AS a1150103
						,SUM(CASE WHEN a.kd_unit='1.15.02.01' THEN ($cetak) ELSE 0 END) AS a1150201
						,SUM(CASE WHEN a.kd_unit='1.15.02.02' THEN ($cetak) ELSE 0 END) AS a1150202
						,SUM(CASE WHEN a.kd_unit='1.16.01.01' THEN ($cetak) ELSE 0 END) AS a1160101
						,SUM(CASE WHEN a.kd_unit='1.16.02.01' THEN ($cetak) ELSE 0 END) AS a1160201
						,SUM(CASE WHEN a.kd_unit='1.18.01.01' THEN ($cetak) ELSE 0 END) AS a1180101
						,SUM(CASE WHEN a.kd_unit='1.18.02.01' THEN ($cetak) ELSE 0 END) AS a1180201
						,SUM(CASE WHEN a.kd_unit='1.19.01.01' THEN ($cetak) ELSE 0 END) AS a1190101
						,SUM(CASE WHEN a.kd_unit='1.19.02.01' THEN ($cetak) ELSE 0 END) AS a1190201
						,SUM(CASE WHEN a.kd_unit='1.19.03.01' THEN ($cetak) ELSE 0 END) AS a1190301
						,SUM(CASE WHEN a.kd_unit='1.20.01.01' THEN ($cetak) ELSE 0 END) AS a1200101
						,SUM(CASE WHEN a.kd_unit='1.20.02.01' THEN ($cetak) ELSE 0 END) AS a1200201
						,SUM(CASE WHEN a.kd_unit='1.20.03.00' THEN ($cetak) ELSE 0 END) AS a1200300
						,SUM(CASE WHEN a.kd_unit='1.20.03.01' THEN ($cetak) ELSE 0 END) AS a1200301
						,SUM(CASE WHEN a.kd_unit='1.20.03.02' THEN ($cetak) ELSE 0 END) AS a1200302
						,SUM(CASE WHEN a.kd_unit='1.20.03.03' THEN ($cetak) ELSE 0 END) AS a1200303
						,SUM(CASE WHEN a.kd_unit='1.20.03.04' THEN ($cetak) ELSE 0 END) AS a1200304
						,SUM(CASE WHEN a.kd_unit='1.20.03.05' THEN ($cetak) ELSE 0 END) AS a1200305
						,SUM(CASE WHEN a.kd_unit='1.20.03.06' THEN ($cetak) ELSE 0 END) AS a1200306
						,SUM(CASE WHEN a.kd_unit='1.20.03.07' THEN ($cetak) ELSE 0 END) AS a1200307
						,SUM(CASE WHEN a.kd_unit='1.20.03.08' THEN ($cetak) ELSE 0 END) AS a1200308
						,SUM(CASE WHEN a.kd_unit='1.20.03.09' THEN ($cetak) ELSE 0 END) AS a1200309
						,SUM(CASE WHEN a.kd_unit='1.20.03.10' THEN ($cetak) ELSE 0 END) AS a1200310
						,SUM(CASE WHEN a.kd_unit='1.20.03.11' THEN ($cetak) ELSE 0 END) AS a1200311
						,SUM(CASE WHEN a.kd_unit='1.20.03.12' THEN ($cetak) ELSE 0 END) AS a1200312
						,SUM(CASE WHEN a.kd_unit='1.20.03.13' THEN ($cetak) ELSE 0 END) AS a1200313
						,SUM(CASE WHEN a.kd_unit='1.20.04.01' THEN ($cetak) ELSE 0 END) AS a1200401               
						,SUM(CASE WHEN a.kd_unit='1.20.05.01' THEN ($cetak) ELSE 0 END) AS a1200501
						,SUM(CASE WHEN a.kd_unit='1.20.06.01' THEN ($cetak) ELSE 0 END) AS a1200601
						,SUM(CASE WHEN a.kd_unit='1.20.07.01' THEN ($cetak) ELSE 0 END) AS a1200701
						,SUM(CASE WHEN a.kd_unit='3.13.01.01' THEN ($cetak) ELSE 0 END) AS a1200801
						,SUM(CASE WHEN a.kd_unit='3.13.01.02' THEN ($cetak) ELSE 0 END) AS a1200802
						,SUM(CASE WHEN a.kd_unit='3.13.01.03' THEN ($cetak) ELSE 0 END) AS a1200803
						,SUM(CASE WHEN a.kd_unit='3.13.01.04' THEN ($cetak) ELSE 0 END) AS a1200804
						,SUM(CASE WHEN a.kd_unit='3.13.01.05' THEN ($cetak) ELSE 0 END) AS a1200805
						,SUM(CASE WHEN a.kd_unit='3.13.01.06' THEN ($cetak) ELSE 0 END) AS a1200806
						,SUM(CASE WHEN a.kd_unit='3.13.01.07' THEN ($cetak) ELSE 0 END) AS a1200807
						,SUM(CASE WHEN a.kd_unit='3.13.01.08' THEN ($cetak) ELSE 0 END) AS a1200808
						,SUM(CASE WHEN a.kd_unit='3.13.01.09' THEN ($cetak) ELSE 0 END) AS a1200809
						,SUM(CASE WHEN a.kd_unit='3.13.01.10' THEN ($cetak) ELSE 0 END) AS a1200810
						,SUM(CASE WHEN a.kd_unit='3.13.01.11' THEN ($cetak) ELSE 0 END) AS a1200811
						,SUM(CASE WHEN a.kd_unit='3.13.01.12' THEN ($cetak) ELSE 0 END) AS a1200812
						,SUM(CASE WHEN a.kd_unit='3.13.01.13' THEN ($cetak) ELSE 0 END) AS a1200813
						,SUM(CASE WHEN a.kd_unit='3.13.01.14' THEN ($cetak) ELSE 0 END) AS a1200814
						,SUM(CASE WHEN a.kd_unit='3.13.01.15' THEN ($cetak) ELSE 0 END) AS a1200815
						,SUM(CASE WHEN a.kd_unit='3.13.01.16' THEN ($cetak) ELSE 0 END) AS a1200816
						,SUM(CASE WHEN a.kd_unit='3.13.01.17' THEN ($cetak) ELSE 0 END) AS a1200817
						,SUM(CASE WHEN a.kd_unit='1.20.09.01' THEN ($cetak) ELSE 0 END) AS a1200901
						,SUM(CASE WHEN a.kd_unit='1.20.10.01' THEN ($cetak) ELSE 0 END) AS a1201001
						,SUM(CASE WHEN a.kd_unit='1.20.11.01' THEN ($cetak) ELSE 0 END) AS a1201101
						,SUM(CASE WHEN a.kd_unit='1.20.11.02' THEN ($cetak) ELSE 0 END) AS a1201102
						,SUM(CASE WHEN a.kd_unit='1.20.11.03' THEN ($cetak) ELSE 0 END) AS a1201103
						,SUM(CASE WHEN a.kd_unit='1.20.11.04' THEN ($cetak) ELSE 0 END) AS a1201104
						,SUM(CASE WHEN a.kd_unit='1.20.12.01' THEN ($cetak) ELSE 0 END) AS a1201201
						,SUM(CASE WHEN a.kd_unit='1.20.12.02' THEN ($cetak) ELSE 0 END) AS a1201202
						,SUM(CASE WHEN a.kd_unit='1.20.13.01' THEN ($cetak) ELSE 0 END) AS a1201301
						,SUM(CASE WHEN a.kd_unit='1.20.14.01' THEN ($cetak) ELSE 0 END) AS a1201401
						,SUM(CASE WHEN a.kd_unit='1.20.15.01' THEN ($cetak) ELSE 0 END) AS a1201501
						,SUM(CASE WHEN a.kd_unit='1.20.15.02' THEN ($cetak) ELSE 0 END) AS a1201502
						,SUM(CASE WHEN a.kd_unit='1.20.15.03' THEN ($cetak) ELSE 0 END) AS a1201503
						,SUM(CASE WHEN a.kd_unit='1.20.15.04' THEN ($cetak) ELSE 0 END) AS a1201504
						,SUM(CASE WHEN a.kd_unit='1.20.15.05' THEN ($cetak) ELSE 0 END) AS a1201505
						,SUM(CASE WHEN a.kd_unit='1.20.15.06' THEN ($cetak) ELSE 0 END) AS a1201506
						,SUM(CASE WHEN a.kd_unit='1.20.15.07' THEN ($cetak) ELSE 0 END) AS a1201507
						,SUM(CASE WHEN a.kd_unit='1.20.15.08' THEN ($cetak) ELSE 0 END) AS a1201508
						,SUM(CASE WHEN a.kd_unit='1.20.15.09' THEN ($cetak) ELSE 0 END) AS a1201509
						,SUM(CASE WHEN a.kd_unit='1.20.15.10' THEN ($cetak) ELSE 0 END) AS a1201510
						,SUM(CASE WHEN a.kd_unit='1.20.15.11' THEN ($cetak) ELSE 0 END) AS a1201511
						,SUM(CASE WHEN a.kd_unit='1.20.15.12' THEN ($cetak) ELSE 0 END) AS a1201512
						,SUM(CASE WHEN a.kd_unit='1.20.15.13' THEN ($cetak) ELSE 0 END) AS a1201513
						,SUM(CASE WHEN a.kd_unit='1.20.15.14' THEN ($cetak) ELSE 0 END) AS a1201514
						,SUM(CASE WHEN a.kd_unit='1.20.15.15' THEN ($cetak) ELSE 0 END) AS a1201515
						,SUM(CASE WHEN a.kd_unit='1.20.15.16' THEN ($cetak) ELSE 0 END) AS a1201516
						,SUM(CASE WHEN a.kd_unit='1.20.15.17' THEN ($cetak) ELSE 0 END) AS a1201517
						,SUM(CASE WHEN a.kd_unit='1.20.16.01' THEN ($cetak) ELSE 0 END) AS a1201601
						,SUM(CASE WHEN a.kd_unit='1.21.01.01' THEN ($cetak) ELSE 0 END) AS a1210101
						,SUM(CASE WHEN a.kd_unit='1.21.01.02' THEN ($cetak) ELSE 0 END) AS a1210102
						,SUM(CASE WHEN a.kd_unit='1.21.02.01' THEN ($cetak) ELSE 0 END) AS a1210201
						,SUM(CASE WHEN a.kd_unit='1.21.02.02' THEN ($cetak) ELSE 0 END) AS a1210202
						,SUM(CASE WHEN a.kd_unit='1.21.02.03' THEN ($cetak) ELSE 0 END) AS a1210203
						,SUM(CASE WHEN a.kd_unit='1.21.02.04' THEN ($cetak) ELSE 0 END) AS a1210204
						,SUM(CASE WHEN a.kd_unit='1.22.01.01' THEN ($cetak) ELSE 0 END) AS a1220101
						,SUM(CASE WHEN a.kd_unit='1.22.02.01' THEN ($cetak) ELSE 0 END) AS a1220201
						,SUM(CASE WHEN a.kd_unit='1.25.01.01' THEN ($cetak) ELSE 0 END) AS a1250101
						,SUM(CASE WHEN a.kd_unit='1.25.02.01' THEN ($cetak) ELSE 0 END) AS a1250201
						,SUM(CASE WHEN a.kd_unit='1.25.02.02' THEN ($cetak) ELSE 0 END) AS a1250202
						,SUM(CASE WHEN a.kd_unit='1.25.02.03' THEN ($cetak) ELSE 0 END) AS a1250203
						,SUM(CASE WHEN a.kd_unit='1.26.01.01' THEN ($cetak) ELSE 0 END) AS a1260101
						,SUM(CASE WHEN a.kd_unit='1.26.01.02' THEN ($cetak) ELSE 0 END) AS a1260102
						,SUM(CASE WHEN a.kd_unit='1.26.02.01' THEN ($cetak) ELSE 0 END) AS a1260201
						,SUM(CASE WHEN a.kd_unit='1.26.02.02' THEN ($cetak) ELSE 0 END) AS a1260202
						,SUM(CASE WHEN a.kd_unit='2.01.01.01' THEN ($cetak) ELSE 0 END) AS a2010101
						,SUM(CASE WHEN a.kd_unit='2.01.01.02' THEN ($cetak) ELSE 0 END) AS a2010102
						,SUM(CASE WHEN a.kd_unit='2.01.01.03' THEN ($cetak) ELSE 0 END) AS a2010103
						,SUM(CASE WHEN a.kd_unit='2.01.01.04' THEN ($cetak) ELSE 0 END) AS a2010104
						,SUM(CASE WHEN a.kd_unit='2.01.01.05' THEN ($cetak) ELSE 0 END) AS a2010105
						,SUM(CASE WHEN a.kd_unit='2.01.01.06' THEN ($cetak) ELSE 0 END) AS a2010106
						,SUM(CASE WHEN a.kd_unit='2.01.01.07' THEN ($cetak) ELSE 0 END) AS a2010107
						,SUM(CASE WHEN a.kd_unit='2.01.01.08' THEN ($cetak) ELSE 0 END) AS a2010108
						,SUM(CASE WHEN a.kd_unit='2.01.01.09' THEN ($cetak) ELSE 0 END) AS a2010109
						,SUM(CASE WHEN a.kd_unit='2.01.01.10' THEN ($cetak) ELSE 0 END) AS a2010110
						,SUM(CASE WHEN a.kd_unit='2.01.01.11' THEN ($cetak) ELSE 0 END) AS a2010111
						,SUM(CASE WHEN a.kd_unit='2.01.01.12' THEN ($cetak) ELSE 0 END) AS a2010112
						,SUM(CASE WHEN a.kd_unit='2.01.02.01' THEN ($cetak) ELSE 0 END) AS a2010201
						,SUM(CASE WHEN a.kd_unit='2.01.02.02' THEN ($cetak) ELSE 0 END) AS a2010202
						,SUM(CASE WHEN a.kd_unit='2.01.02.03' THEN ($cetak) ELSE 0 END) AS a2010203
						,SUM(CASE WHEN a.kd_unit='2.01.03.01' THEN ($cetak) ELSE 0 END) AS a2010301
						,SUM(CASE WHEN a.kd_unit='2.01.03.02' THEN ($cetak) ELSE 0 END) AS a2010302
						,SUM(CASE WHEN a.kd_unit='2.01.03.03' THEN ($cetak) ELSE 0 END) AS a2010303
						,SUM(CASE WHEN a.kd_unit='2.02.01.01' THEN ($cetak) ELSE 0 END) AS a2020101
						,SUM(CASE WHEN a.kd_unit='2.02.01.02' THEN ($cetak) ELSE 0 END) AS a2020102
						,SUM(CASE WHEN a.kd_unit='2.02.01.03' THEN ($cetak) ELSE 0 END) AS a2020103
						,SUM(CASE WHEN a.kd_unit='2.02.01.04' THEN ($cetak) ELSE 0 END) AS a2020104
						,SUM(CASE WHEN a.kd_unit='2.02.01.05' THEN ($cetak) ELSE 0 END) AS a2020105
						,SUM(CASE WHEN a.kd_unit='2.02.01.06' THEN ($cetak) ELSE 0 END) AS a2020106
						,SUM(CASE WHEN a.kd_unit='2.02.01.07' THEN ($cetak) ELSE 0 END) AS a2020107
						,SUM(CASE WHEN a.kd_unit='2.02.01.08' THEN ($cetak) ELSE 0 END) AS a2020108
						,SUM(CASE WHEN a.kd_unit='2.02.01.09' THEN ($cetak) ELSE 0 END) AS a2020109
						,SUM(CASE WHEN a.kd_unit='2.02.01.10' THEN ($cetak) ELSE 0 END) AS a2020110
						,SUM(CASE WHEN a.kd_unit='2.02.01.11' THEN ($cetak) ELSE 0 END) AS a2020111
						,SUM(CASE WHEN a.kd_unit='2.02.01.12' THEN ($cetak) ELSE 0 END) AS a2020112
						,SUM(CASE WHEN a.kd_unit='2.02.01.13' THEN ($cetak) ELSE 0 END) AS a2020113
						,SUM(CASE WHEN a.kd_unit='2.02.01.14' THEN ($cetak) ELSE 0 END) AS a2020114
						,SUM(CASE WHEN a.kd_unit='2.02.01.15' THEN ($cetak) ELSE 0 END) AS a2020115
						,SUM(CASE WHEN a.kd_unit='2.02.01.16' THEN ($cetak) ELSE 0 END) AS a2020116
						,SUM(CASE WHEN a.kd_unit='2.02.01.17' THEN ($cetak) ELSE 0 END) AS a2020117
						,SUM(CASE WHEN a.kd_unit='2.02.01.18' THEN ($cetak) ELSE 0 END) AS a2020118
						,SUM(CASE WHEN a.kd_unit='2.02.01.19' THEN ($cetak) ELSE 0 END) AS a2020119
						,SUM(CASE WHEN a.kd_unit='2.02.01.20' THEN ($cetak) ELSE 0 END) AS a2020120
						,SUM(CASE WHEN a.kd_unit='2.02.01.21' THEN ($cetak) ELSE 0 END) AS a2020121
						,SUM(CASE WHEN a.kd_unit='2.02.01.22' THEN ($cetak) ELSE 0 END) AS a2020122
						,SUM(CASE WHEN a.kd_unit='2.02.01.23' THEN ($cetak) ELSE 0 END) AS a2020123
						,SUM(CASE WHEN a.kd_unit='2.03.01.01' THEN ($cetak) ELSE 0 END) AS a2030101
						,SUM(CASE WHEN a.kd_unit='2.03.02.01' THEN ($cetak) ELSE 0 END) AS a2030201
						,SUM(CASE WHEN a.kd_unit='2.04.01.01' THEN ($cetak) ELSE 0 END) AS a2040101
						,SUM(CASE WHEN a.kd_unit='2.05.01.01' THEN ($cetak) ELSE 0 END) AS a2050101
						,SUM(CASE WHEN a.kd_unit='2.05.01.02' THEN ($cetak) ELSE 0 END) AS a2050102
						,SUM(CASE WHEN a.kd_unit='2.05.01.03' THEN ($cetak) ELSE 0 END) AS a2050103
						,SUM(CASE WHEN a.kd_unit='2.05.01.04' THEN ($cetak) ELSE 0 END) AS a2050104
						,SUM(CASE WHEN a.kd_unit='2.05.01.05' THEN ($cetak) ELSE 0 END) AS a2050105
						,SUM(CASE WHEN a.kd_unit='2.05.01.06' THEN ($cetak) ELSE 0 END) AS a2050106
						,SUM(CASE WHEN a.kd_unit='2.05.01.07' THEN ($cetak) ELSE 0 END) AS a2050107
						,SUM(CASE WHEN a.kd_unit='2.06.01.01' THEN ($cetak) ELSE 0 END) AS a2060101
						,SUM(CASE WHEN a.kd_unit='2.06.01.02' THEN ($cetak) ELSE 0 END) AS a2060102
						,SUM(CASE WHEN a.kd_unit='2.06.01.03' THEN ($cetak) ELSE 0 END) AS a2060103
						,SUM(CASE WHEN a.kd_unit='2.06.01.04' THEN ($cetak) ELSE 0 END) AS a2060104
						,SUM(CASE WHEN a.kd_unit='2.06.01.05' THEN ($cetak) ELSE 0 END) AS a2060105
						FROM trdju_calk a INNER JOIN trhju_calk b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd 
						WHERE (left(kd_rek5,3) in ($n) or left(kd_rek5,5) in ($n2) or left(kd_rek5,7) in ($n3)) AND YEAR(b.tgl_voucher)='$lntahunang'
						";
			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$a1010101 = $row->a1010101;
				$a1010102 = $row->a1010102;
				$a1010103 = $row->a1010103;
				$a1010104 = $row->a1010104;
				$a1010105 = $row->a1010105;
				$a1020101 = $row->a1020101;
				$a1020102 = $row->a1020102;
				$a1020103 = $row->a1020103;
				$a1020104 = $row->a1020104;
				$a1020105 = $row->a1020105;
				$a1020106 = $row->a1020106;
				$a1020107 = $row->a1020107;
				$a1020108 = $row->a1020108;
				$a1020201 = $row->a1020201;
				$a1020301 = $row->a1020301;
				$a1020401 = $row->a1020401;
				$a1030101 = $row->a1030101;
				$a1030102 = $row->a1030102;
				$a1030103 = $row->a1030103;
				$a1030104 = $row->a1030104;
				$a1030105 = $row->a1030105;
				$a1030106 = $row->a1030106;
				$a1030107 = $row->a1030107;
				$a1030201 = $row->a1030201;
				$a1030202 = $row->a1030202;
				$a1030203 = $row->a1030203;
				$a1030204 = $row->a1030204;
				$a1030205 = $row->a1030205;
				$a1030206 = $row->a1030206;
				$a1030207 = $row->a1030207;
				$a1040101 = $row->a1040101;
				$a1060101 = $row->a1060101;
				$a1060102 = $row->a1060102;
				$a1060201 = $row->a1060201;
				$a1060301 = $row->a1060301;
				$a1070101 = $row->a1070101;
				$a1070102 = $row->a1070102;
				$a1070103 = $row->a1070103;
				$a1070104 = $row->a1070104;
				$a1070105 = $row->a1070105;
				$a1070201 = $row->a1070201;
				$a1070202 = $row->a1070202;
				$a1070203 = $row->a1070203;
				$a1070204 = $row->a1070204;
				$a1080101 = $row->a1080101;
				$a1100101 = $row->a1100101;
				$a1110101 = $row->a1110101;
				$a1110201 = $row->a1110201;
				$a1130101 = $row->a1130101;
				$a1130102 = $row->a1130102;
				$a1130103 = $row->a1130103;
				$a1130104 = $row->a1130104;
				$a1130105 = $row->a1130105;
				$a1140101 = $row->a1140101;
				$a1140102 = $row->a1140102;
				$a1140103 = $row->a1140103;
				$a1140104 = $row->a1140104;
				$a1140105 = $row->a1140105;
				$a1140106 = $row->a1140106;
				$a1140107 = $row->a1140107;
				$a1140108 = $row->a1140108;
				$a1150101 = $row->a1150101;
				$a1150102 = $row->a1150102;
				$a1150103 = $row->a1150103;
				$a1150201 = $row->a1150201;
				$a1150202 = $row->a1150202;
				$a1160101 = $row->a1160101;
				$a1160201 = $row->a1160201;
				$a1180101 = $row->a1180101;
				$a1180201 = $row->a1180201;
				$a1190101 = $row->a1190101;
				$a1190201 = $row->a1190201;
				$a1190301 = $row->a1190301;
				$a1200101 = $row->a1200101;
				$a1200201 = $row->a1200201;
				$a1200300 = $row->a1200300;
				$a1200301 = $row->a1200301;
				$a1200302 = $row->a1200302;
				$a1200303 = $row->a1200303;
				$a1200304 = $row->a1200304;
				$a1200305 = $row->a1200305;
				$a1200306 = $row->a1200306;
				$a1200307 = $row->a1200307;
				$a1200308 = $row->a1200308;
				$a1200309 = $row->a1200309;
				$a1200310 = $row->a1200310;
				$a1200311 = $row->a1200311;
				$a1200312 = $row->a1200312;
				$a1200313 = $row->a1200313;
				$a1200401 = $row->a1200401;
				$a1200501 = $row->a1200501;
				$a1200601 = $row->a1200601;
				$a1200701 = $row->a1200701;
				$a1200801 = $row->a1200801;
				$a1200802 = $row->a1200802;
				$a1200803 = $row->a1200803;
				$a1200804 = $row->a1200804;
				$a1200805 = $row->a1200805;
				$a1200806 = $row->a1200806;
				$a1200807 = $row->a1200807;
				$a1200808 = $row->a1200808;
				$a1200809 = $row->a1200809;
				$a1200810 = $row->a1200810;
				$a1200811 = $row->a1200811;
				$a1200812 = $row->a1200812;
				$a1200813 = $row->a1200813;
				$a1200814 = $row->a1200814;
				$a1200815 = $row->a1200815;
				$a1200816 = $row->a1200816;
				$a1200817 = $row->a1200817;
				$a1200901 = $row->a1200901;
				$a1201001 = $row->a1201001;
				$a1201101 = $row->a1201101;
				$a1201102 = $row->a1201102;
				$a1201103 = $row->a1201103;
				$a1201104 = $row->a1201104;
				$a1201201 = $row->a1201201;
				$a1201202 = $row->a1201202;
				$a1201301 = $row->a1201301;
				$a1201401 = $row->a1201401;
				$a1201501 = $row->a1201501;
				$a1201502 = $row->a1201502;
				$a1201503 = $row->a1201503;
				$a1201504 = $row->a1201504;
				$a1201505 = $row->a1201505;
				$a1201506 = $row->a1201506;
				$a1201507 = $row->a1201507;
				$a1201508 = $row->a1201508;
				$a1201509 = $row->a1201509;
				$a1201510 = $row->a1201510;
				$a1201511 = $row->a1201511;
				$a1201512 = $row->a1201512;
				$a1201513 = $row->a1201513;
				$a1201514 = $row->a1201514;
				$a1201515 = $row->a1201515;
				$a1201516 = $row->a1201516;
				$a1201517 = $row->a1201517;
				$a1201601 = $row->a1201601;
				$a1210101 = $row->a1210101;
				$a1210102 = $row->a1210102;
				$a1210201 = $row->a1210201;
				$a1210202 = $row->a1210202;
				$a1210203 = $row->a1210203;
				$a1210204 = $row->a1210204;
				$a1220101 = $row->a1220101;
				$a1220201 = $row->a1220201;
				$a1250101 = $row->a1250101;
				$a1250201 = $row->a1250201;
				$a1250202 = $row->a1250202;
				$a1250203 = $row->a1250203;
				$a1260101 = $row->a1260101;
				$a1260102 = $row->a1260102;
				$a1260201 = $row->a1260201;
				$a1260202 = $row->a1260202;
				$a2010101 = $row->a2010101;
				$a2010102 = $row->a2010102;
				$a2010103 = $row->a2010103;
				$a2010104 = $row->a2010104;
				$a2010105 = $row->a2010105;
				$a2010106 = $row->a2010106;
				$a2010107 = $row->a2010107;
				$a2010108 = $row->a2010108;
				$a2010109 = $row->a2010109;
				$a2010110 = $row->a2010110;
				$a2010111 = $row->a2010111;
				$a2010112 = $row->a2010112;
				$a2010201 = $row->a2010201;
				$a2010202 = $row->a2010202;
				$a2010203 = $row->a2010203;
				$a2010301 = $row->a2010301;
				$a2010302 = $row->a2010302;
				$a2010303 = $row->a2010303;
				$a2020101 = $row->a2020101;
				$a2020102 = $row->a2020102;
				$a2020103 = $row->a2020103;
				$a2020104 = $row->a2020104;
				$a2020105 = $row->a2020105;
				$a2020106 = $row->a2020106;
				$a2020107 = $row->a2020107;
				$a2020108 = $row->a2020108;
				$a2020109 = $row->a2020109;
				$a2020110 = $row->a2020110;
				$a2020111 = $row->a2020111;
				$a2020112 = $row->a2020112;
				$a2020113 = $row->a2020113;
				$a2020114 = $row->a2020114;
				$a2020115 = $row->a2020115;
				$a2020116 = $row->a2020116;
				$a2020117 = $row->a2020117;
				$a2020118 = $row->a2020118;
				$a2020119 = $row->a2020119;
				$a2020120 = $row->a2020120;
				$a2020121 = $row->a2020121;
				$a2020122 = $row->a2020122;
				$a2020123 = $row->a2020123;
				$a2030101 = $row->a2030101;
				$a2030201 = $row->a2030201;
				$a2040101 = $row->a2040101;
				$a2050101 = $row->a2050101;
				$a2050102 = $row->a2050102;
				$a2050103 = $row->a2050103;
				$a2050104 = $row->a2050104;
				$a2050105 = $row->a2050105;
				$a2050106 = $row->a2050106;
				$a2050107 = $row->a2050107;
				$a2060101 = $row->a2060101;
				$a2060102 = $row->a2060102;
				$a2060103 = $row->a2060103;
				$a2060104 = $row->a2060104;
				$a2060105 = $row->a2060105;
			}

			$cRet .= '<tr>
									<td align="left" valign="top" style="font-size:12px">' . $nama . '</td> 
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020401, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030204, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030205, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030206, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030207, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1040101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070204, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1080101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1100101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1110101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1110201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1160101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1160201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1180101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1180201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1190101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1190201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1190301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200300, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200302, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200303, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200304, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200305, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200306, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200307, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200308, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200309, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200310, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200311, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200312, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200313, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200401, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200501, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200601, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200701, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200801, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200802, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200803, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200804, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200805, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200806, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200807, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200808, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200809, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200810, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200811, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200812, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200813, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200814, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200815, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200816, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200817, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200901, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201001, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201401, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201501, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201502, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201503, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201504, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201505, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201506, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201507, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201508, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201509, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201510, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201511, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201512, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201513, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201514, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201515, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201516, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201517, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201601, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210204, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1220101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1220201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010109, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010110, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010111, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010112, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010302, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010303, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020109, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020110, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020111, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020112, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020113, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020114, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020115, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020116, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020117, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020118, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020119, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020120, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020121, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020122, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020123, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2030101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2030201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2040101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060105, "2", ",", ".") . '</td>
								</tr>';
		}

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_real_Aset';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}



	function cetak_kertas_kerja_lo($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="5" align="center" > <B>LAPORAN OPERASIONAL PER-SKPD <BR> TAHUN ANGGARAN ' . $lntahunang . ' </B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR (UNIT PENGEMBANGAN DAN LATIHAN KEGIATAN BELAJAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR (UNIT PENELITIAN DAN PENGEMBANGAN TEKNOLOGI PENDIDIKAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR ( UNIT TAMAN BUDAYA PROVINSI KALBAR )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI KALBAR ( UNIT MUSEUM PROVINSI KALBAR )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (AKPER SINTANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UNIT PELATIHAN KESEHATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UNIT LABORATORIUM KESEHATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UNIT PENGOBATAN PARU PARU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (POLIKLINIK)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UPT PELAYANAN KESEHATAN PARU PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KESEHATAN PROVINSI KALBAR (UPT KESEHATAN KERJA DAN OLAHRAGA MASYARAKAT PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>RUMAH SAKIT UMUM DAERAH Dr. SOEDARSO PONTIANAK</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>RUMAH SAKIT JIWA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>RUMAH SAKIT JIWA DAERAH SUNGAI BANGKONG PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM PROVINSI KALBAR (UNIT PENGUJIAN MUTU DAN PEMBINAAN JASA KONSTRUKSI PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH I</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH II</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH III</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH IV</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH V</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR (UNIT PENGUJIAN MUTU DAN PEMBINAAN JASA KONSTRUKSI PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR  (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH I PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR  (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH II PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH III PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR   (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH IV PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG PROV. KALBAR   (UNIT PEMELIHARAAN JALAN DAN JEMBATAN WILAYAH V PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERUMAHAN RAKYAT, KAWASAN PERMUKIMAN DAN LINGKUNGAN HIDUP PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERENCANAAN PEMBANGUNAN DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERENCANAAN PEMBANGUNAN DAERAH PROVINSI KALBAR (UNIT DATA DAN STATISTIK PEMBANGUNAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KANTOR PENELITIAN DAN PENGEMBANGAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENELITIAN DAN PENGEMBANGAN PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI DAN INFORMATIKA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI DAN INFORMATIKA PROVINSI KALBAR (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH I)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI DAN INFORMATIKA PROVINSI KALBAR (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH II)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN KOMUNIKASI & INFORMATIKA PROV.KALBAR (UNIT PERBAIKAN DAN PEMELIHARAAN KENDARAAN BERMOTOR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGOLAHAN DATA ELEKTRONIK DINAS PERHUBUNGAN, KOMUNIKASI DAN INFORMATIKA PROVINSI KALIMANTAN BARAT</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR  (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH I PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR  (UNIT PELAYANAN LALU LINTAS DAN ANGKUTAN WILAYAH II  PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERHUBUNGAN  PROV. KALBAR (UNIT PERBAIKAN DAN PEMELIHARAAN KENDARAAN BERMOTOR  PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN LINGKUNGAN HIDUP PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK DAN KELUARGA BERENCANA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEMBERDAYAAN PEREMPUAN DAN  PERLINDUNGAN ANAK  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UNIT PELAYANAN DAN REHABILITASI SOSIAL)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UPT PANTI SOSIAL DAN REHABILITASI LANJUT USIA MUSTIKA DHARMA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UPT PANTI SOSIAL LANJUT USIA MULIA DHARMA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS SOSIAL PROVINSI KALBAR (UPT PANTI SOSIAL ANAK PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT PENGEMBANGAN PRODUKTIVITAS DAERAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT PELAYANAN HIPERKES DAN KESELAMATAN KERJA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT LATIHAN KERJA INDUSTRI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT LATIHAN KERJA INDUSTRI ENTIKONG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UNIT PELATIHAN TRANSMIGRASI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UPT PENGAWASAN KETENAGAKERJAAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS TENAGA KERJA DAN TRANSMIGRASI PROVINSI KALBAR (UPT LABORATORIUM KESEHATAN KERJA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI DAN UMKM PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI DAN UMKM PROVINSI KALBAR (BALAI PELATIHAN KOPERASI, USAHA KECIL, MIKRO DAN MENENGAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI, USAHA KECIL DAN MENENGAH  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI, USAHA KECIL DAN MENENGAH  PROV. KALBAR  (BALAI PELATIHAN KOPERASI , USAHA MIKRO, KECIL DAN MENENGAH PROV.  KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOPERASI, USAHA KECIL DAN MENENGAH  PROV. KALBAR(UPT PELATIHAN KOPERASI PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PEMUDA DAN OLAH RAGA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEPEMUDAAN,  OLAH RAGA DAN PARIWISATA  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KESATUAN BANGSA DAN POLITIK PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENANGGULANGAN BENCANA DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SATUAN POLISI PAMONG PRAJA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DEWAN PERWAKILAN RAKYAT DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KEPALA DAERAH DAN WAKIL KEPALA DAERAH</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO UMUM)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO HUMAS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PEMERINTAHAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO KEPENDUDUKAN DAN PENCATATAN SIPIL)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO HUKUM)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PEREKONOMIAN DAN PEMBANGUNAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO KESEJAHTERAAN SOSIAL)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO ORGANISASI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO HUBUNGAN MASYARAKAT  DAN PROTOKOL SETDA PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PEREKONOMIAN SETDA PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO KESEJAHTERAAN RAKYAT SETDA PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO ADMINISTRASI PEMBANGUNAN DAN PENGADAAN  BARANG/JASA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DAERAH PROVINSI KALBAR (BIRO PENGELOLAAN ASET)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT DPRD PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KEPEGAWAIAN DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENDIDIKAN DAN PELATIHAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>INSPEKTORAT PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD PONTIANAK WILAYAH I)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD PONTIANAK WILAYAH II )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SINGKAWANG )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD MEMPAWAH )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SAMBAS )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SANGGAU )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR ( UPPD SINTANG )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD PUTUSSIBAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD KETAPANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD NGABANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD BENGKAYANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD KUBU RAYA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD SEKADAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD MELAWI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PENDAPATAN DAERAH PROVINSI KALBAR (UPPD KAYONG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KANTOR PERWAKILAN PROVINSI KALBAR DI JAKARTA</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT KORPRI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PEMBANGUNAN PERBATASAN DAN DAERAH TERTINGGAL PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGELOLA POS PEMERIKSAAN LINTAS BATAS ENTIKONG PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGELOLA POS PEMERIKSAAN LINTAS BATAS ARUK PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>UNIT PENGELOLA POS PEMERIKSAAN LINTAS BATAS BADAU PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN ASSET DAERAH</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN ASSET DAERAH / PPKD</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KANTOR LAYANAN PENGADAAN BARANG / JASA PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGEMBANGAN  SUMBER DAYA MANUSIA PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH WILAYAH I)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH WILAYAH II)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SINGKAWANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR  (UNIT PELAYANAN PENDAPATAN DAERAH MEMPAWAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SAMBAS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SANGGAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SINTANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH PUTUSSIBAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH KETAPANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH NGABANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH BENGKAYANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH KUBU RAYA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH SEKADAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH MELAWI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR (UNIT PELAYANAN PENDAPATAN DAERAH KAYONG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN  DAERAH PROV. KALBAR / PPKD</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PENGHUBUNG PROV. KALBAR DI JAKARTA</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KETAHANAN PANGAN DAN PENYULUHAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN KETAHANAN PANGAN DAN PENYULUHAN PROVINSI KALBAR (BPLP3K ANJUNGAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR (BALAI PENDIDIKAN DAN LATIHAN PENYULUH PERTANIAN,PERIKANAN DAN KEHUTANAN PROV.KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR  (UNIT LABORATORIUM KESEHATAN HEWAN DAN KESEHATAN MASYARAKAT VETERINER)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PANGAN, PETERNAKAN DAN KESEHATAN HEWAN  PROV. KALBAR (UNIT PEMBIBITAN TERNAK DAN PAKAN TERNAK PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PEMBERDAYAAN MASYARAKAT DAN PEMERINTAHAN DESA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PEMBERDAYAAN MASYARAKAT DAN DESA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>SEKRETARIAT KOMISI PENYIARAN INDONESIA DAERAH PROVINSI KALIMANTAN BARAT</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOMUNIKASI DAN INFORMATIKA PROV. KALBAR.</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOMUNIKASI DAN INFORMATIKA PROV. KALBAR  (SEKRETARIAT KOMISI PENYIARAN INDONESIA DAERAH PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KOMUNIKASI DAN INFORMATIKA PROV. KALBAR  (UNIT PENGOLAHAN DATA ELEKTRONIK PROVINSI KALIMANTAN BARAT)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERPUSTAKAAN, KEARSIPAN DAN DOKUMENTASI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BADAN PERPUSTAKAAN, KEARSIPAN DAN DOKUMENTASI PROVINSI KALBAR (UNIT PELAYANAN PERPUSTAKAAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PERPUSTAKAAN DAN  KEARSIPAN PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  PERPUSTAKAAN DAN  KEARSIPAN PROV. KALBAR  (UNIT PELAYANAN PERPUSTAKAAN PROV. KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TANAMAN PANGAN DAN HORTIKULTURA PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (SPP-SPMA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PENGAWASAN DAN SERTIFIKASI BENIH TPH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PROTEKSI TPH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PENGEMBANGAN BENIH TPH KAKAP)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PEMBENIHAN INDUK TPH ANJUNGAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UNIT PENGELOLA TERMINAL AGRIBISNIS TERPADU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT PEMBENIHAN TANAMAN PANGAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT BALAI BENIH INDUK HORTIKULTURA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT DIKLAT PERTANIAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT PERLINDUNGAN TANAMAN PANGAN DAN HORTIKULTURA PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTANIAN TPH PROV. KALBAR (UPT PENGAWASAN DAN SERTIFIKASI BENIH PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERKEBUNAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERKEBUNAN PROVINSI KALBAR (BALAI PERBENIHAN TANAMAN PERKEBUNAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERKEBUNAN PROVINSI KALBAR (UPT PENGAWASAN DAN SERTIFIKASI BENIH PERKEBUNAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PETERNAKAN DAN KESEHATAN HEWAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PETERNAKAN DAN KESEHATAN HEWAN PROVINSI KALBAR (UNIT LABKESWAN DAN KESMAVET)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PETERNAKAN DAN KESEHATAN HEWAN PROV. KALBAR ( UNIT PEMBIBITAN DAN PAKAN TERNAK )</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT INVENTARISASI DAN PEMETAAN HUTAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PENANGGULANGAN KEBAKARAN HUTAN DAN LAHAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SAMBAS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN BENGKAYANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN MEMPAWAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN LANDAK)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KUBU RAYA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN MELAWI)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAYONG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KETAPANG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KETAPANG SELATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SEKADAU)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SANGGAU BARAT)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SANGGAU TIMUR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SINTANG TIMUR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN SINTANG UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAPUAS HULU UTARA)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAPUAS HULU TIMUR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN HUTAN KAPUAS HULU SELATAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS PENGELOLAAN EKOSISTEM LAHAN BASAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS TAMAN HUTAN RAYA PANDAN PULOH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KEHUTANAN PROVINSI KALBAR (UNIT PELAKSANA TEKNIS MUSEUM KEHUTANAN)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERTAMBANGAN DAN ENERGI PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS  ENERGI DAN SUMBER DAYA MINERAL  PROV. KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PARIWISATA DAN EKONOMI KREATIF PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPTD-BBIS)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UNIT LPPMHP)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UNIT UPPP)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPT PELABUHAN PERIKANAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPT PENERAPAN MUTU HASIL PERIKANAN PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS KELAUTAN DAN PERIKANAN PROVINSI KALBAR (UPT PERIKANAN BUDIDAYA AIR PAYAU DAN LAUT PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UNIT PENGAWASAN DAN SERTIFIKASI MUTU BARANG)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UNIT PELATIHAN INDUSTRI KECIL MENENGAH)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UPT PENGUJIAN DAN SERTIFIKASI MUTU BARANG PROVINSI KALBAR)</b></td>
<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>DINAS PERINDUSTRIAN DAN PERDAGANGAN PROVINSI KALBAR (UPT INDUSTRI PANGAN OLAHAN DAN KEMASAN PROVINSI KALBAR)</b></td>
                </tr>
				</thead>";

		$jum_real_sedia = 0;
		$jum_real_tetap = 0;
		$jum_real_lain = 0;
		$total = 0;
		$no = 0;
		$sql = "SELECT uraian, seq, ISNULL(cetak,'debet') as cetak, bold,parent as par, isnull(kode_1,'-') as rek, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3 FROM map_lo_prov ORDER BY kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->uraian;
			$seq = $row->seq;
			$cetak = $row->cetak;
			$bold = $row->bold;
			$parent = $row->par;
			$n = $row->rek;
			$n = ($n == "-" ? "'-'" : $n);
			$n2 = $row->kode_2;
			$n2 = ($n2 == "-" ? "'-'" : $n2);
			$n3 = $row->kode_3;
			$n3 = ($n3 == "-" ? "'-'" : $n3);
			$sql = "SELECT 
					     SUM(CASE WHEN a.kd_unit='1.01.01.01' THEN ($cetak) ELSE 0 END) AS a1010101
						,SUM(CASE WHEN a.kd_unit='1.01.01.02' THEN ($cetak) ELSE 0 END) AS a1010102
						,SUM(CASE WHEN a.kd_unit='1.01.01.03' THEN ($cetak) ELSE 0 END) AS a1010103
						,SUM(CASE WHEN a.kd_unit='1.01.01.04' THEN ($cetak) ELSE 0 END) AS a1010104
						,SUM(CASE WHEN a.kd_unit='1.01.01.05' THEN ($cetak) ELSE 0 END) AS a1010105
						,SUM(CASE WHEN a.kd_unit='1.02.01.01' THEN ($cetak) ELSE 0 END) AS a1020101
						,SUM(CASE WHEN a.kd_unit='1.02.01.02' THEN ($cetak) ELSE 0 END) AS a1020102
						,SUM(CASE WHEN a.kd_unit='1.02.01.03' THEN ($cetak) ELSE 0 END) AS a1020103
						,SUM(CASE WHEN a.kd_unit='1.02.01.04' THEN ($cetak) ELSE 0 END) AS a1020104
						,SUM(CASE WHEN a.kd_unit='1.02.01.05' THEN ($cetak) ELSE 0 END) AS a1020105
						,SUM(CASE WHEN a.kd_unit='1.02.01.06' THEN ($cetak) ELSE 0 END) AS a1020106
						,SUM(CASE WHEN a.kd_unit='1.02.01.07' THEN ($cetak) ELSE 0 END) AS a1020107
						,SUM(CASE WHEN a.kd_unit='1.02.01.08' THEN ($cetak) ELSE 0 END) AS a1020108
						,SUM(CASE WHEN a.kd_unit='1.02.02.01' THEN ($cetak) ELSE 0 END) AS a1020201
						,SUM(CASE WHEN a.kd_unit='1.02.03.01' THEN ($cetak) ELSE 0 END) AS a1020301
						,SUM(CASE WHEN a.kd_unit='1.02.04.01' THEN ($cetak) ELSE 0 END) AS a1020401
						,SUM(CASE WHEN a.kd_unit='1.03.01.01' THEN ($cetak) ELSE 0 END) AS a1030101
						,SUM(CASE WHEN a.kd_unit='1.03.01.02' THEN ($cetak) ELSE 0 END) AS a1030102
						,SUM(CASE WHEN a.kd_unit='1.03.01.03' THEN ($cetak) ELSE 0 END) AS a1030103
						,SUM(CASE WHEN a.kd_unit='1.03.01.04' THEN ($cetak) ELSE 0 END) AS a1030104
						,SUM(CASE WHEN a.kd_unit='1.03.01.05' THEN ($cetak) ELSE 0 END) AS a1030105
						,SUM(CASE WHEN a.kd_unit='1.03.01.06' THEN ($cetak) ELSE 0 END) AS a1030106
						,SUM(CASE WHEN a.kd_unit='1.03.01.07' THEN ($cetak) ELSE 0 END) AS a1030107
						,SUM(CASE WHEN a.kd_unit='1.03.02.01' THEN ($cetak) ELSE 0 END) AS a1030201
						,SUM(CASE WHEN a.kd_unit='1.03.02.02' THEN ($cetak) ELSE 0 END) AS a1030202
						,SUM(CASE WHEN a.kd_unit='1.03.02.03' THEN ($cetak) ELSE 0 END) AS a1030203
						,SUM(CASE WHEN a.kd_unit='1.03.02.04' THEN ($cetak) ELSE 0 END) AS a1030204
						,SUM(CASE WHEN a.kd_unit='1.03.02.05' THEN ($cetak) ELSE 0 END) AS a1030205
						,SUM(CASE WHEN a.kd_unit='1.03.02.06' THEN ($cetak) ELSE 0 END) AS a1030206
						,SUM(CASE WHEN a.kd_unit='1.03.02.07' THEN ($cetak) ELSE 0 END) AS a1030207
						,SUM(CASE WHEN a.kd_unit='1.04.01.01' THEN ($cetak) ELSE 0 END) AS a1040101
						,SUM(CASE WHEN a.kd_unit='1.06.01.01' THEN ($cetak) ELSE 0 END) AS a1060101
						,SUM(CASE WHEN a.kd_unit='1.06.01.02' THEN ($cetak) ELSE 0 END) AS a1060102
						,SUM(CASE WHEN a.kd_unit='1.06.02.01' THEN ($cetak) ELSE 0 END) AS a1060201
						,SUM(CASE WHEN a.kd_unit='1.06.03.01' THEN ($cetak) ELSE 0 END) AS a1060301
						,SUM(CASE WHEN a.kd_unit='1.07.01.01' THEN ($cetak) ELSE 0 END) AS a1070101
						,SUM(CASE WHEN a.kd_unit='1.07.01.02' THEN ($cetak) ELSE 0 END) AS a1070102
						,SUM(CASE WHEN a.kd_unit='1.07.01.03' THEN ($cetak) ELSE 0 END) AS a1070103
						,SUM(CASE WHEN a.kd_unit='1.07.01.04' THEN ($cetak) ELSE 0 END) AS a1070104
						,SUM(CASE WHEN a.kd_unit='1.07.01.05' THEN ($cetak) ELSE 0 END) AS a1070105
						,SUM(CASE WHEN a.kd_unit='1.07.02.01' THEN ($cetak) ELSE 0 END) AS a1070201
						,SUM(CASE WHEN a.kd_unit='1.07.02.02' THEN ($cetak) ELSE 0 END) AS a1070202
						,SUM(CASE WHEN a.kd_unit='1.07.02.03' THEN ($cetak) ELSE 0 END) AS a1070203
						,SUM(CASE WHEN a.kd_unit='1.07.02.04' THEN ($cetak) ELSE 0 END) AS a1070204
						,SUM(CASE WHEN a.kd_unit='1.08.01.01' THEN ($cetak) ELSE 0 END) AS a1080101
						,SUM(CASE WHEN a.kd_unit='1.10.01.01' THEN ($cetak) ELSE 0 END) AS a1100101
						,SUM(CASE WHEN a.kd_unit='1.11.01.01' THEN ($cetak) ELSE 0 END) AS a1110101
						,SUM(CASE WHEN a.kd_unit='1.11.02.01' THEN ($cetak) ELSE 0 END) AS a1110201
						,SUM(CASE WHEN a.kd_unit='1.13.01.01' THEN ($cetak) ELSE 0 END) AS a1130101               
						,SUM(CASE WHEN a.kd_unit='1.13.01.02' THEN ($cetak) ELSE 0 END) AS a1130102
						,SUM(CASE WHEN a.kd_unit='1.13.01.03' THEN ($cetak) ELSE 0 END) AS a1130103
						,SUM(CASE WHEN a.kd_unit='1.13.01.04' THEN ($cetak) ELSE 0 END) AS a1130104
						,SUM(CASE WHEN a.kd_unit='1.13.01.05' THEN ($cetak) ELSE 0 END) AS a1130105
						,SUM(CASE WHEN a.kd_unit='1.14.01.01' THEN ($cetak) ELSE 0 END) AS a1140101
						,SUM(CASE WHEN a.kd_unit='1.14.01.02' THEN ($cetak) ELSE 0 END) AS a1140102
						,SUM(CASE WHEN a.kd_unit='1.14.01.03' THEN ($cetak) ELSE 0 END) AS a1140103
						,SUM(CASE WHEN a.kd_unit='1.14.01.04' THEN ($cetak) ELSE 0 END) AS a1140104
						,SUM(CASE WHEN a.kd_unit='1.14.01.05' THEN ($cetak) ELSE 0 END) AS a1140105
						,SUM(CASE WHEN a.kd_unit='1.14.01.06' THEN ($cetak) ELSE 0 END) AS a1140106
						,SUM(CASE WHEN a.kd_unit='1.14.01.07' THEN ($cetak) ELSE 0 END) AS a1140107
						,SUM(CASE WHEN a.kd_unit='1.14.01.08' THEN ($cetak) ELSE 0 END) AS a1140108
						,SUM(CASE WHEN a.kd_unit='1.15.01.01' THEN ($cetak) ELSE 0 END) AS a1150101
						,SUM(CASE WHEN a.kd_unit='1.15.01.02' THEN ($cetak) ELSE 0 END) AS a1150102
						,SUM(CASE WHEN a.kd_unit='1.15.01.03' THEN ($cetak) ELSE 0 END) AS a1150103
						,SUM(CASE WHEN a.kd_unit='1.15.02.01' THEN ($cetak) ELSE 0 END) AS a1150201
						,SUM(CASE WHEN a.kd_unit='1.15.02.02' THEN ($cetak) ELSE 0 END) AS a1150202
						,SUM(CASE WHEN a.kd_unit='1.16.01.01' THEN ($cetak) ELSE 0 END) AS a1160101
						,SUM(CASE WHEN a.kd_unit='1.16.02.01' THEN ($cetak) ELSE 0 END) AS a1160201
						,SUM(CASE WHEN a.kd_unit='1.18.01.01' THEN ($cetak) ELSE 0 END) AS a1180101
						,SUM(CASE WHEN a.kd_unit='1.18.02.01' THEN ($cetak) ELSE 0 END) AS a1180201
						,SUM(CASE WHEN a.kd_unit='1.19.01.01' THEN ($cetak) ELSE 0 END) AS a1190101
						,SUM(CASE WHEN a.kd_unit='1.19.02.01' THEN ($cetak) ELSE 0 END) AS a1190201
						,SUM(CASE WHEN a.kd_unit='1.19.03.01' THEN ($cetak) ELSE 0 END) AS a1190301
						,SUM(CASE WHEN a.kd_unit='1.20.01.01' THEN ($cetak) ELSE 0 END) AS a1200101
						,SUM(CASE WHEN a.kd_unit='1.20.02.01' THEN ($cetak) ELSE 0 END) AS a1200201
						,SUM(CASE WHEN a.kd_unit='1.20.03.00' THEN ($cetak) ELSE 0 END) AS a1200300
						,SUM(CASE WHEN a.kd_unit='1.20.03.01' THEN ($cetak) ELSE 0 END) AS a1200301
						,SUM(CASE WHEN a.kd_unit='1.20.03.02' THEN ($cetak) ELSE 0 END) AS a1200302
						,SUM(CASE WHEN a.kd_unit='1.20.03.03' THEN ($cetak) ELSE 0 END) AS a1200303
						,SUM(CASE WHEN a.kd_unit='1.20.03.04' THEN ($cetak) ELSE 0 END) AS a1200304
						,SUM(CASE WHEN a.kd_unit='1.20.03.05' THEN ($cetak) ELSE 0 END) AS a1200305
						,SUM(CASE WHEN a.kd_unit='1.20.03.06' THEN ($cetak) ELSE 0 END) AS a1200306
						,SUM(CASE WHEN a.kd_unit='1.20.03.07' THEN ($cetak) ELSE 0 END) AS a1200307
						,SUM(CASE WHEN a.kd_unit='1.20.03.08' THEN ($cetak) ELSE 0 END) AS a1200308
						,SUM(CASE WHEN a.kd_unit='1.20.03.09' THEN ($cetak) ELSE 0 END) AS a1200309
						,SUM(CASE WHEN a.kd_unit='1.20.03.10' THEN ($cetak) ELSE 0 END) AS a1200310
						,SUM(CASE WHEN a.kd_unit='1.20.03.11' THEN ($cetak) ELSE 0 END) AS a1200311
						,SUM(CASE WHEN a.kd_unit='1.20.03.12' THEN ($cetak) ELSE 0 END) AS a1200312
						,SUM(CASE WHEN a.kd_unit='1.20.03.13' THEN ($cetak) ELSE 0 END) AS a1200313
						,SUM(CASE WHEN a.kd_unit='1.20.04.01' THEN ($cetak) ELSE 0 END) AS a1200401               
						,SUM(CASE WHEN a.kd_unit='1.20.05.01' THEN ($cetak) ELSE 0 END) AS a1200501
						,SUM(CASE WHEN a.kd_unit='1.20.06.01' THEN ($cetak) ELSE 0 END) AS a1200601
						,SUM(CASE WHEN a.kd_unit='1.20.07.01' THEN ($cetak) ELSE 0 END) AS a1200701
						,SUM(CASE WHEN a.kd_unit='3.13.01.01' THEN ($cetak) ELSE 0 END) AS a1200801
						,SUM(CASE WHEN a.kd_unit='3.13.01.02' THEN ($cetak) ELSE 0 END) AS a1200802
						,SUM(CASE WHEN a.kd_unit='3.13.01.03' THEN ($cetak) ELSE 0 END) AS a1200803
						,SUM(CASE WHEN a.kd_unit='3.13.01.04' THEN ($cetak) ELSE 0 END) AS a1200804
						,SUM(CASE WHEN a.kd_unit='3.13.01.05' THEN ($cetak) ELSE 0 END) AS a1200805
						,SUM(CASE WHEN a.kd_unit='3.13.01.06' THEN ($cetak) ELSE 0 END) AS a1200806
						,SUM(CASE WHEN a.kd_unit='3.13.01.07' THEN ($cetak) ELSE 0 END) AS a1200807
						,SUM(CASE WHEN a.kd_unit='3.13.01.08' THEN ($cetak) ELSE 0 END) AS a1200808
						,SUM(CASE WHEN a.kd_unit='3.13.01.09' THEN ($cetak) ELSE 0 END) AS a1200809
						,SUM(CASE WHEN a.kd_unit='3.13.01.10' THEN ($cetak) ELSE 0 END) AS a1200810
						,SUM(CASE WHEN a.kd_unit='3.13.01.11' THEN ($cetak) ELSE 0 END) AS a1200811
						,SUM(CASE WHEN a.kd_unit='3.13.01.12' THEN ($cetak) ELSE 0 END) AS a1200812
						,SUM(CASE WHEN a.kd_unit='3.13.01.13' THEN ($cetak) ELSE 0 END) AS a1200813
						,SUM(CASE WHEN a.kd_unit='3.13.01.14' THEN ($cetak) ELSE 0 END) AS a1200814
						,SUM(CASE WHEN a.kd_unit='3.13.01.15' THEN ($cetak) ELSE 0 END) AS a1200815
						,SUM(CASE WHEN a.kd_unit='3.13.01.16' THEN ($cetak) ELSE 0 END) AS a1200816
						,SUM(CASE WHEN a.kd_unit='3.13.01.17' THEN ($cetak) ELSE 0 END) AS a1200817
						,SUM(CASE WHEN a.kd_unit='1.20.09.01' THEN ($cetak) ELSE 0 END) AS a1200901
						,SUM(CASE WHEN a.kd_unit='1.20.10.01' THEN ($cetak) ELSE 0 END) AS a1201001
						,SUM(CASE WHEN a.kd_unit='1.20.11.01' THEN ($cetak) ELSE 0 END) AS a1201101
						,SUM(CASE WHEN a.kd_unit='1.20.11.02' THEN ($cetak) ELSE 0 END) AS a1201102
						,SUM(CASE WHEN a.kd_unit='1.20.11.03' THEN ($cetak) ELSE 0 END) AS a1201103
						,SUM(CASE WHEN a.kd_unit='1.20.11.04' THEN ($cetak) ELSE 0 END) AS a1201104
						,SUM(CASE WHEN a.kd_unit='1.20.12.01' THEN ($cetak) ELSE 0 END) AS a1201201
						,SUM(CASE WHEN a.kd_unit='1.20.12.02' THEN ($cetak) ELSE 0 END) AS a1201202
						,SUM(CASE WHEN a.kd_unit='1.20.13.01' THEN ($cetak) ELSE 0 END) AS a1201301
						,SUM(CASE WHEN a.kd_unit='1.20.14.01' THEN ($cetak) ELSE 0 END) AS a1201401
						,SUM(CASE WHEN a.kd_unit='1.20.15.01' THEN ($cetak) ELSE 0 END) AS a1201501
						,SUM(CASE WHEN a.kd_unit='1.20.15.02' THEN ($cetak) ELSE 0 END) AS a1201502
						,SUM(CASE WHEN a.kd_unit='1.20.15.03' THEN ($cetak) ELSE 0 END) AS a1201503
						,SUM(CASE WHEN a.kd_unit='1.20.15.04' THEN ($cetak) ELSE 0 END) AS a1201504
						,SUM(CASE WHEN a.kd_unit='1.20.15.05' THEN ($cetak) ELSE 0 END) AS a1201505
						,SUM(CASE WHEN a.kd_unit='1.20.15.06' THEN ($cetak) ELSE 0 END) AS a1201506
						,SUM(CASE WHEN a.kd_unit='1.20.15.07' THEN ($cetak) ELSE 0 END) AS a1201507
						,SUM(CASE WHEN a.kd_unit='1.20.15.08' THEN ($cetak) ELSE 0 END) AS a1201508
						,SUM(CASE WHEN a.kd_unit='1.20.15.09' THEN ($cetak) ELSE 0 END) AS a1201509
						,SUM(CASE WHEN a.kd_unit='1.20.15.10' THEN ($cetak) ELSE 0 END) AS a1201510
						,SUM(CASE WHEN a.kd_unit='1.20.15.11' THEN ($cetak) ELSE 0 END) AS a1201511
						,SUM(CASE WHEN a.kd_unit='1.20.15.12' THEN ($cetak) ELSE 0 END) AS a1201512
						,SUM(CASE WHEN a.kd_unit='1.20.15.13' THEN ($cetak) ELSE 0 END) AS a1201513
						,SUM(CASE WHEN a.kd_unit='1.20.15.14' THEN ($cetak) ELSE 0 END) AS a1201514
						,SUM(CASE WHEN a.kd_unit='1.20.15.15' THEN ($cetak) ELSE 0 END) AS a1201515
						,SUM(CASE WHEN a.kd_unit='1.20.15.16' THEN ($cetak) ELSE 0 END) AS a1201516
						,SUM(CASE WHEN a.kd_unit='1.20.15.17' THEN ($cetak) ELSE 0 END) AS a1201517
						,SUM(CASE WHEN a.kd_unit='1.20.16.01' THEN ($cetak) ELSE 0 END) AS a1201601
						,SUM(CASE WHEN a.kd_unit='1.21.01.01' THEN ($cetak) ELSE 0 END) AS a1210101
						,SUM(CASE WHEN a.kd_unit='1.21.01.02' THEN ($cetak) ELSE 0 END) AS a1210102
						,SUM(CASE WHEN a.kd_unit='1.21.02.01' THEN ($cetak) ELSE 0 END) AS a1210201
						,SUM(CASE WHEN a.kd_unit='1.21.02.02' THEN ($cetak) ELSE 0 END) AS a1210202
						,SUM(CASE WHEN a.kd_unit='1.21.02.03' THEN ($cetak) ELSE 0 END) AS a1210203
						,SUM(CASE WHEN a.kd_unit='1.21.02.04' THEN ($cetak) ELSE 0 END) AS a1210204
						,SUM(CASE WHEN a.kd_unit='1.22.01.01' THEN ($cetak) ELSE 0 END) AS a1220101
						,SUM(CASE WHEN a.kd_unit='1.22.02.01' THEN ($cetak) ELSE 0 END) AS a1220201
						,SUM(CASE WHEN a.kd_unit='1.25.01.01' THEN ($cetak) ELSE 0 END) AS a1250101
						,SUM(CASE WHEN a.kd_unit='1.25.02.01' THEN ($cetak) ELSE 0 END) AS a1250201
						,SUM(CASE WHEN a.kd_unit='1.25.02.02' THEN ($cetak) ELSE 0 END) AS a1250202
						,SUM(CASE WHEN a.kd_unit='1.25.02.03' THEN ($cetak) ELSE 0 END) AS a1250203
						,SUM(CASE WHEN a.kd_unit='1.26.01.01' THEN ($cetak) ELSE 0 END) AS a1260101
						,SUM(CASE WHEN a.kd_unit='1.26.01.02' THEN ($cetak) ELSE 0 END) AS a1260102
						,SUM(CASE WHEN a.kd_unit='1.26.02.01' THEN ($cetak) ELSE 0 END) AS a1260201
						,SUM(CASE WHEN a.kd_unit='1.26.02.02' THEN ($cetak) ELSE 0 END) AS a1260202
						,SUM(CASE WHEN a.kd_unit='2.01.01.01' THEN ($cetak) ELSE 0 END) AS a2010101
						,SUM(CASE WHEN a.kd_unit='2.01.01.02' THEN ($cetak) ELSE 0 END) AS a2010102
						,SUM(CASE WHEN a.kd_unit='2.01.01.03' THEN ($cetak) ELSE 0 END) AS a2010103
						,SUM(CASE WHEN a.kd_unit='2.01.01.04' THEN ($cetak) ELSE 0 END) AS a2010104
						,SUM(CASE WHEN a.kd_unit='2.01.01.05' THEN ($cetak) ELSE 0 END) AS a2010105
						,SUM(CASE WHEN a.kd_unit='2.01.01.06' THEN ($cetak) ELSE 0 END) AS a2010106
						,SUM(CASE WHEN a.kd_unit='2.01.01.07' THEN ($cetak) ELSE 0 END) AS a2010107
						,SUM(CASE WHEN a.kd_unit='2.01.01.08' THEN ($cetak) ELSE 0 END) AS a2010108
						,SUM(CASE WHEN a.kd_unit='2.01.01.09' THEN ($cetak) ELSE 0 END) AS a2010109
						,SUM(CASE WHEN a.kd_unit='2.01.01.10' THEN ($cetak) ELSE 0 END) AS a2010110
						,SUM(CASE WHEN a.kd_unit='2.01.01.11' THEN ($cetak) ELSE 0 END) AS a2010111
						,SUM(CASE WHEN a.kd_unit='2.01.01.12' THEN ($cetak) ELSE 0 END) AS a2010112
						,SUM(CASE WHEN a.kd_unit='2.01.02.01' THEN ($cetak) ELSE 0 END) AS a2010201
						,SUM(CASE WHEN a.kd_unit='2.01.02.02' THEN ($cetak) ELSE 0 END) AS a2010202
						,SUM(CASE WHEN a.kd_unit='2.01.02.03' THEN ($cetak) ELSE 0 END) AS a2010203
						,SUM(CASE WHEN a.kd_unit='2.01.03.01' THEN ($cetak) ELSE 0 END) AS a2010301
						,SUM(CASE WHEN a.kd_unit='2.01.03.02' THEN ($cetak) ELSE 0 END) AS a2010302
						,SUM(CASE WHEN a.kd_unit='2.01.03.03' THEN ($cetak) ELSE 0 END) AS a2010303
						,SUM(CASE WHEN a.kd_unit='2.02.01.01' THEN ($cetak) ELSE 0 END) AS a2020101
						,SUM(CASE WHEN a.kd_unit='2.02.01.02' THEN ($cetak) ELSE 0 END) AS a2020102
						,SUM(CASE WHEN a.kd_unit='2.02.01.03' THEN ($cetak) ELSE 0 END) AS a2020103
						,SUM(CASE WHEN a.kd_unit='2.02.01.04' THEN ($cetak) ELSE 0 END) AS a2020104
						,SUM(CASE WHEN a.kd_unit='2.02.01.05' THEN ($cetak) ELSE 0 END) AS a2020105
						,SUM(CASE WHEN a.kd_unit='2.02.01.06' THEN ($cetak) ELSE 0 END) AS a2020106
						,SUM(CASE WHEN a.kd_unit='2.02.01.07' THEN ($cetak) ELSE 0 END) AS a2020107
						,SUM(CASE WHEN a.kd_unit='2.02.01.08' THEN ($cetak) ELSE 0 END) AS a2020108
						,SUM(CASE WHEN a.kd_unit='2.02.01.09' THEN ($cetak) ELSE 0 END) AS a2020109
						,SUM(CASE WHEN a.kd_unit='2.02.01.10' THEN ($cetak) ELSE 0 END) AS a2020110
						,SUM(CASE WHEN a.kd_unit='2.02.01.11' THEN ($cetak) ELSE 0 END) AS a2020111
						,SUM(CASE WHEN a.kd_unit='2.02.01.12' THEN ($cetak) ELSE 0 END) AS a2020112
						,SUM(CASE WHEN a.kd_unit='2.02.01.13' THEN ($cetak) ELSE 0 END) AS a2020113
						,SUM(CASE WHEN a.kd_unit='2.02.01.14' THEN ($cetak) ELSE 0 END) AS a2020114
						,SUM(CASE WHEN a.kd_unit='2.02.01.15' THEN ($cetak) ELSE 0 END) AS a2020115
						,SUM(CASE WHEN a.kd_unit='2.02.01.16' THEN ($cetak) ELSE 0 END) AS a2020116
						,SUM(CASE WHEN a.kd_unit='2.02.01.17' THEN ($cetak) ELSE 0 END) AS a2020117
						,SUM(CASE WHEN a.kd_unit='2.02.01.18' THEN ($cetak) ELSE 0 END) AS a2020118
						,SUM(CASE WHEN a.kd_unit='2.02.01.19' THEN ($cetak) ELSE 0 END) AS a2020119
						,SUM(CASE WHEN a.kd_unit='2.02.01.20' THEN ($cetak) ELSE 0 END) AS a2020120
						,SUM(CASE WHEN a.kd_unit='2.02.01.21' THEN ($cetak) ELSE 0 END) AS a2020121
						,SUM(CASE WHEN a.kd_unit='2.02.01.22' THEN ($cetak) ELSE 0 END) AS a2020122
						,SUM(CASE WHEN a.kd_unit='2.02.01.23' THEN ($cetak) ELSE 0 END) AS a2020123
						,SUM(CASE WHEN a.kd_unit='2.03.01.01' THEN ($cetak) ELSE 0 END) AS a2030101
						,SUM(CASE WHEN a.kd_unit='2.03.02.01' THEN ($cetak) ELSE 0 END) AS a2030201
						,SUM(CASE WHEN a.kd_unit='2.04.01.01' THEN ($cetak) ELSE 0 END) AS a2040101
						,SUM(CASE WHEN a.kd_unit='2.05.01.01' THEN ($cetak) ELSE 0 END) AS a2050101
						,SUM(CASE WHEN a.kd_unit='2.05.01.02' THEN ($cetak) ELSE 0 END) AS a2050102
						,SUM(CASE WHEN a.kd_unit='2.05.01.03' THEN ($cetak) ELSE 0 END) AS a2050103
						,SUM(CASE WHEN a.kd_unit='2.05.01.04' THEN ($cetak) ELSE 0 END) AS a2050104
						,SUM(CASE WHEN a.kd_unit='2.05.01.05' THEN ($cetak) ELSE 0 END) AS a2050105
						,SUM(CASE WHEN a.kd_unit='2.05.01.06' THEN ($cetak) ELSE 0 END) AS a2050106
						,SUM(CASE WHEN a.kd_unit='2.05.01.07' THEN ($cetak) ELSE 0 END) AS a2050107
						,SUM(CASE WHEN a.kd_unit='2.06.01.01' THEN ($cetak) ELSE 0 END) AS a2060101
						,SUM(CASE WHEN a.kd_unit='2.06.01.02' THEN ($cetak) ELSE 0 END) AS a2060102
						,SUM(CASE WHEN a.kd_unit='2.06.01.03' THEN ($cetak) ELSE 0 END) AS a2060103
						,SUM(CASE WHEN a.kd_unit='2.06.01.04' THEN ($cetak) ELSE 0 END) AS a2060104
						,SUM(CASE WHEN a.kd_unit='2.06.01.05' THEN ($cetak) ELSE 0 END) AS a2060105
						FROM trdju_calk a INNER JOIN trhju_calk b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd 
						WHERE (left(kd_rek5,3) in ($n) or left(kd_rek5,5) in ($n2) or left(kd_rek5,7) in ($n3)) AND YEAR(b.tgl_voucher)='$lntahunang'
						";
			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$a1010101 = $row->a1010101;
				$a1010102 = $row->a1010102;
				$a1010103 = $row->a1010103;
				$a1010104 = $row->a1010104;
				$a1010105 = $row->a1010105;
				$a1020101 = $row->a1020101;
				$a1020102 = $row->a1020102;
				$a1020103 = $row->a1020103;
				$a1020104 = $row->a1020104;
				$a1020105 = $row->a1020105;
				$a1020106 = $row->a1020106;
				$a1020107 = $row->a1020107;
				$a1020108 = $row->a1020108;
				$a1020201 = $row->a1020201;
				$a1020301 = $row->a1020301;
				$a1020401 = $row->a1020401;
				$a1030101 = $row->a1030101;
				$a1030102 = $row->a1030102;
				$a1030103 = $row->a1030103;
				$a1030104 = $row->a1030104;
				$a1030105 = $row->a1030105;
				$a1030106 = $row->a1030106;
				$a1030107 = $row->a1030107;
				$a1030201 = $row->a1030201;
				$a1030202 = $row->a1030202;
				$a1030203 = $row->a1030203;
				$a1030204 = $row->a1030204;
				$a1030205 = $row->a1030205;
				$a1030206 = $row->a1030206;
				$a1030207 = $row->a1030207;
				$a1040101 = $row->a1040101;
				$a1060101 = $row->a1060101;
				$a1060102 = $row->a1060102;
				$a1060201 = $row->a1060201;
				$a1060301 = $row->a1060301;
				$a1070101 = $row->a1070101;
				$a1070102 = $row->a1070102;
				$a1070103 = $row->a1070103;
				$a1070104 = $row->a1070104;
				$a1070105 = $row->a1070105;
				$a1070201 = $row->a1070201;
				$a1070202 = $row->a1070202;
				$a1070203 = $row->a1070203;
				$a1070204 = $row->a1070204;
				$a1080101 = $row->a1080101;
				$a1100101 = $row->a1100101;
				$a1110101 = $row->a1110101;
				$a1110201 = $row->a1110201;
				$a1130101 = $row->a1130101;
				$a1130102 = $row->a1130102;
				$a1130103 = $row->a1130103;
				$a1130104 = $row->a1130104;
				$a1130105 = $row->a1130105;
				$a1140101 = $row->a1140101;
				$a1140102 = $row->a1140102;
				$a1140103 = $row->a1140103;
				$a1140104 = $row->a1140104;
				$a1140105 = $row->a1140105;
				$a1140106 = $row->a1140106;
				$a1140107 = $row->a1140107;
				$a1140108 = $row->a1140108;
				$a1150101 = $row->a1150101;
				$a1150102 = $row->a1150102;
				$a1150103 = $row->a1150103;
				$a1150201 = $row->a1150201;
				$a1150202 = $row->a1150202;
				$a1160101 = $row->a1160101;
				$a1160201 = $row->a1160201;
				$a1180101 = $row->a1180101;
				$a1180201 = $row->a1180201;
				$a1190101 = $row->a1190101;
				$a1190201 = $row->a1190201;
				$a1190301 = $row->a1190301;
				$a1200101 = $row->a1200101;
				$a1200201 = $row->a1200201;
				$a1200300 = $row->a1200300;
				$a1200301 = $row->a1200301;
				$a1200302 = $row->a1200302;
				$a1200303 = $row->a1200303;
				$a1200304 = $row->a1200304;
				$a1200305 = $row->a1200305;
				$a1200306 = $row->a1200306;
				$a1200307 = $row->a1200307;
				$a1200308 = $row->a1200308;
				$a1200309 = $row->a1200309;
				$a1200310 = $row->a1200310;
				$a1200311 = $row->a1200311;
				$a1200312 = $row->a1200312;
				$a1200313 = $row->a1200313;
				$a1200401 = $row->a1200401;
				$a1200501 = $row->a1200501;
				$a1200601 = $row->a1200601;
				$a1200701 = $row->a1200701;
				$a1200801 = $row->a1200801;
				$a1200802 = $row->a1200802;
				$a1200803 = $row->a1200803;
				$a1200804 = $row->a1200804;
				$a1200805 = $row->a1200805;
				$a1200806 = $row->a1200806;
				$a1200807 = $row->a1200807;
				$a1200808 = $row->a1200808;
				$a1200809 = $row->a1200809;
				$a1200810 = $row->a1200810;
				$a1200811 = $row->a1200811;
				$a1200812 = $row->a1200812;
				$a1200813 = $row->a1200813;
				$a1200814 = $row->a1200814;
				$a1200815 = $row->a1200815;
				$a1200816 = $row->a1200816;
				$a1200817 = $row->a1200817;
				$a1200901 = $row->a1200901;
				$a1201001 = $row->a1201001;
				$a1201101 = $row->a1201101;
				$a1201102 = $row->a1201102;
				$a1201103 = $row->a1201103;
				$a1201104 = $row->a1201104;
				$a1201201 = $row->a1201201;
				$a1201202 = $row->a1201202;
				$a1201301 = $row->a1201301;
				$a1201401 = $row->a1201401;
				$a1201501 = $row->a1201501;
				$a1201502 = $row->a1201502;
				$a1201503 = $row->a1201503;
				$a1201504 = $row->a1201504;
				$a1201505 = $row->a1201505;
				$a1201506 = $row->a1201506;
				$a1201507 = $row->a1201507;
				$a1201508 = $row->a1201508;
				$a1201509 = $row->a1201509;
				$a1201510 = $row->a1201510;
				$a1201511 = $row->a1201511;
				$a1201512 = $row->a1201512;
				$a1201513 = $row->a1201513;
				$a1201514 = $row->a1201514;
				$a1201515 = $row->a1201515;
				$a1201516 = $row->a1201516;
				$a1201517 = $row->a1201517;
				$a1201601 = $row->a1201601;
				$a1210101 = $row->a1210101;
				$a1210102 = $row->a1210102;
				$a1210201 = $row->a1210201;
				$a1210202 = $row->a1210202;
				$a1210203 = $row->a1210203;
				$a1210204 = $row->a1210204;
				$a1220101 = $row->a1220101;
				$a1220201 = $row->a1220201;
				$a1250101 = $row->a1250101;
				$a1250201 = $row->a1250201;
				$a1250202 = $row->a1250202;
				$a1250203 = $row->a1250203;
				$a1260101 = $row->a1260101;
				$a1260102 = $row->a1260102;
				$a1260201 = $row->a1260201;
				$a1260202 = $row->a1260202;
				$a2010101 = $row->a2010101;
				$a2010102 = $row->a2010102;
				$a2010103 = $row->a2010103;
				$a2010104 = $row->a2010104;
				$a2010105 = $row->a2010105;
				$a2010106 = $row->a2010106;
				$a2010107 = $row->a2010107;
				$a2010108 = $row->a2010108;
				$a2010109 = $row->a2010109;
				$a2010110 = $row->a2010110;
				$a2010111 = $row->a2010111;
				$a2010112 = $row->a2010112;
				$a2010201 = $row->a2010201;
				$a2010202 = $row->a2010202;
				$a2010203 = $row->a2010203;
				$a2010301 = $row->a2010301;
				$a2010302 = $row->a2010302;
				$a2010303 = $row->a2010303;
				$a2020101 = $row->a2020101;
				$a2020102 = $row->a2020102;
				$a2020103 = $row->a2020103;
				$a2020104 = $row->a2020104;
				$a2020105 = $row->a2020105;
				$a2020106 = $row->a2020106;
				$a2020107 = $row->a2020107;
				$a2020108 = $row->a2020108;
				$a2020109 = $row->a2020109;
				$a2020110 = $row->a2020110;
				$a2020111 = $row->a2020111;
				$a2020112 = $row->a2020112;
				$a2020113 = $row->a2020113;
				$a2020114 = $row->a2020114;
				$a2020115 = $row->a2020115;
				$a2020116 = $row->a2020116;
				$a2020117 = $row->a2020117;
				$a2020118 = $row->a2020118;
				$a2020119 = $row->a2020119;
				$a2020120 = $row->a2020120;
				$a2020121 = $row->a2020121;
				$a2020122 = $row->a2020122;
				$a2020123 = $row->a2020123;
				$a2030101 = $row->a2030101;
				$a2030201 = $row->a2030201;
				$a2040101 = $row->a2040101;
				$a2050101 = $row->a2050101;
				$a2050102 = $row->a2050102;
				$a2050103 = $row->a2050103;
				$a2050104 = $row->a2050104;
				$a2050105 = $row->a2050105;
				$a2050106 = $row->a2050106;
				$a2050107 = $row->a2050107;
				$a2060101 = $row->a2060101;
				$a2060102 = $row->a2060102;
				$a2060103 = $row->a2060103;
				$a2060104 = $row->a2060104;
				$a2060105 = $row->a2060105;
			}

			$cRet .= '<tr>
									<td align="left" valign="top" style="font-size:12px">' . $nama . '</td> 
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1010105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1020401, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030204, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030205, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030206, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1030207, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1040101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1060301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1070204, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1080101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1100101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1110101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1110201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1130105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1140108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1150202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1160101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1160201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1180101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1180201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1190101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1190201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1190301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200300, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200302, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200303, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200304, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200305, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200306, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200307, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200308, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200309, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200310, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200311, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200312, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200313, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200401, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200501, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200601, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200701, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200801, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200802, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200803, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200804, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200805, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200806, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200807, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200808, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200809, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200810, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200811, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200812, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200813, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200814, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200815, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200816, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200817, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1200901, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201001, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201401, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201501, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201502, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201503, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201504, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201505, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201506, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201507, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201508, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201509, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201510, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201511, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201512, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201513, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201514, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201515, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201516, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201517, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1201601, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1210204, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1220101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1220201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1250203, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a1260202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010109, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010110, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010111, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010112, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010202, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010301, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010302, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2010303, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020108, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020109, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020110, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020111, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020112, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020113, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020114, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020115, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020116, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020117, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020118, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020119, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020120, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020121, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020122, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2020123, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2030101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2030201, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2040101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050105, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050106, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2050107, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060101, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060102, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060103, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060104, "2", ",", ".") . '</td>
									<td align="right" valign="top" style="font-size:12px">' . number_format($a2060105, "2", ",", ".") . '</td>
								</tr>';
		}

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_real_Aset';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}



	function lamp_susut()
	{
		$data['page_title'] = 'CETAK LAMPIRAN AKUMULASI PENYUSUTAN';
		$this->template->set('title', 'CETAK LAMPIRAN AKUMULASI PENYUSUTAN');
		$this->template->load('template', 'lamp_perda/cetak_akum_susut', $data);
	}


	function cetak_akum_susut($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR AKUMULASI PENYUSUTAN PERALATAN DAN MESIN <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No.</td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Akumulasi Penyusutan 2015</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Akumulasi Penyusutan 2014</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Beban Akumulasi Penyusutan</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
				</tr>
				</thead>";

		$jum_ini = 0;
		$jum_lalu = 0;
		$jum_beban = 0;
		$total = 0;
		$no = 0;

		$sql = "SELECT a.kd_skpd, nm_skpd, ISNULL(akumulasi_ini,0) akumulasi_ini,
							ISNULL(akumulasi_lalu,0) akumulasi_lalu,
							ISNULL(beban,0) beban
							FROM ms_skpd a 
							LEFT JOIN 
							 (SELECT a.kd_skpd
							,SUM(CASE WHEN LEFT(kd_rek5,5)='13701' AND YEAR(a.tgl_voucher)<='2015' THEN debet-kredit ELSE 0 END) AS akumulasi_ini
							,SUM(CASE WHEN LEFT(kd_rek5,5)='13701' AND YEAR(a.tgl_voucher)<='2014' THEN debet-kredit ELSE 0 END) AS akumulasi_lalu
							,SUM(CASE WHEN LEFT(kd_rek5,5)='91701' AND YEAR(a.tgl_voucher)<='2015' THEN debet-kredit ELSE 0 END) AS beban
							 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
							and b.kd_unit=a.kd_skpd where  LEFT(kd_rek5,5) IN ('13701','91701') GROUP BY a.kd_skpd
							) b
							ON a.kd_skpd=b.kd_skpd";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nm_skpd;
			//$tahun = $row->tahun;
			$akumulasi_lalu = $row->akumulasi_lalu;
			$akumulasi_ini = $row->akumulasi_ini;
			$beban = $row->beban;
			$jum_ini = $jum_ini + $akumulasi_ini;
			$jum_lalu = $jum_lalu + $akumulasi_lalu;
			$jum_beban = $jum_beban + $beban;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($akumulasi_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($akumulasi_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($beban, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan = "2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_beban, "2", ",", ".") . '</td> 
							</tr>';

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_akumulasi_penyusutan_mesin';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_akum_susut_gdng($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR AKUMULASI PENYUSUTAN GEDUNG DAN BANGUNAN <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No.</td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Akumulasi Penyusutan 2015</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Akumulasi Penyusutan 2014</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Beban Akumulasi Penyusutan</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
				</tr>
				</thead>";

		$jum_ini = 0;
		$jum_lalu = 0;
		$jum_beban = 0;
		$total = 0;
		$no = 0;

		$sql = "SELECT a.kd_skpd, nm_skpd, ISNULL(akumulasi_ini,0) akumulasi_ini,
							ISNULL(akumulasi_lalu,0) akumulasi_lalu,
							ISNULL(beban,0) beban
							FROM ms_skpd a 
							LEFT JOIN 
							 (SELECT a.kd_skpd
							,SUM(CASE WHEN LEFT(kd_rek5,5)='13702' AND YEAR(a.tgl_voucher)<='2015' THEN debet-kredit ELSE 0 END) AS akumulasi_ini
							,SUM(CASE WHEN LEFT(kd_rek5,5)='13702' AND YEAR(a.tgl_voucher)<='2014' THEN debet-kredit ELSE 0 END) AS akumulasi_lalu
							,SUM(CASE WHEN LEFT(kd_rek5,5)='91702' AND YEAR(a.tgl_voucher)<='2015' THEN debet-kredit ELSE 0 END) AS beban
							 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
							and b.kd_unit=a.kd_skpd where  LEFT(kd_rek5,5) IN ('13702','91702') GROUP BY a.kd_skpd
							) b
							ON a.kd_skpd=b.kd_skpd";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nm_skpd;
			//$tahun = $row->tahun;
			$akumulasi_lalu = $row->akumulasi_lalu;
			$akumulasi_ini = $row->akumulasi_ini;
			$beban = $row->beban;
			$jum_ini = $jum_ini + $akumulasi_ini;
			$jum_lalu = $jum_lalu + $akumulasi_lalu;
			$jum_beban = $jum_beban + $beban;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($akumulasi_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($akumulasi_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($beban, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan = "2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_beban, "2", ",", ".") . '</td> 
							</tr>';

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_akumulasi_penyusutan_gedung';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_akum_susut_jij($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR AKUMULASI PENYUSUTAN JALAN, IRIGASI DAN JARINGAN <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No.</td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Akumulasi Penyusutan 2015</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Akumulasi Penyusutan 2014</td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Beban Akumulasi Penyusutan</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
				</tr>
				</thead>";

		$jum_ini = 0;
		$jum_lalu = 0;
		$jum_beban = 0;
		$total = 0;
		$no = 0;

		$sql = "SELECT a.kd_skpd, nm_skpd, ISNULL(akumulasi_ini,0) akumulasi_ini,
							ISNULL(akumulasi_lalu,0) akumulasi_lalu,
							ISNULL(beban,0) beban
							FROM ms_skpd a 
							LEFT JOIN 
							 (SELECT a.kd_skpd
							,SUM(CASE WHEN LEFT(kd_rek5,5)='13703' AND YEAR(a.tgl_voucher)<='2015' THEN debet-kredit ELSE 0 END) AS akumulasi_ini
							,SUM(CASE WHEN LEFT(kd_rek5,5)='13703' AND YEAR(a.tgl_voucher)<='2014' THEN debet-kredit ELSE 0 END) AS akumulasi_lalu
							,SUM(CASE WHEN LEFT(kd_rek5,5)='91703' AND YEAR(a.tgl_voucher)<='2015' THEN debet-kredit ELSE 0 END) AS beban
							 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
							and b.kd_unit=a.kd_skpd where  LEFT(kd_rek5,5) IN ('13703','91703') GROUP BY a.kd_skpd
							) b
							ON a.kd_skpd=b.kd_skpd";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nm_skpd;
			//$tahun = $row->tahun;
			$akumulasi_lalu = $row->akumulasi_lalu;
			$akumulasi_ini = $row->akumulasi_ini;
			$beban = $row->beban;
			$jum_ini = $jum_ini + $akumulasi_ini;
			$jum_lalu = $jum_lalu + $akumulasi_lalu;
			$jum_beban = $jum_beban + $beban;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($akumulasi_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($akumulasi_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($beban, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan = "2" align="center" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_lalu, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum_beban, "2", ",", ".") . '</td> 
							</tr>';

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lap_akumulasi_penyusutan_gedung';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_lamp_bps($bulan = '', $ctk = '', $jenis = '')
	{
		$lntahunang = $this->session->userdata('pcThang');


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="7" align="center" > <B>Tabel 1. Realisasi Daya Serap Realisasi Dinas / Badan / Kantor 
						<BR>Pemerintah Provinsi Kalimantan Barat Tahun ' . $lntahunang . '
						<BR>(Januari s/d ' . $this->getBulan($bulan) . ' ' . $lntahunang . ')</B></TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>No</b></td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Total Belanja Satker APBD</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Belanja Pegawai</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Belanja Barang</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Belanja Modal</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Bantuan Sosial</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Total</b></td>
					</tr>
				</thead>";

		if ($jenis == 1) {
			$sql = "SELECT bulan,
					SUM(CASE WHEN a.kd_rek='511' THEN a.nilai ELSE 0 END) AS bel_peg,
					SUM(CASE WHEN a.kd_rek='512' THEN a.nilai ELSE 0 END) AS bel_brg,
					SUM(CASE WHEN LEFT(a.kd_rek,2)='52' THEN a.nilai ELSE 0 END) AS bel_mod,
					SUM(CASE WHEN a.kd_rek='516' THEN a.nilai ELSE 0 END) AS bansos
					FROM(
					SELECT MONTH(tgl_voucher) as bulan, LEFT(kd_rek5,3) as kd_rek, SUM(debet-kredit) as nilai FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
					WHERE LEFT(kd_rek5,1)='5' AND MONTH(tgl_voucher)<='$bulan' GROUP BY MONTH(tgl_voucher),LEFT(kd_rek5,3))a
					GROUP BY bulan
					ORDER BY bulan
					";
		} else if ($jenis == 2) {
			$sql = "SELECT bulan,
					SUM(CASE WHEN a.kd_rek IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg,
					SUM(CASE WHEN a.kd_rek='522' THEN a.nilai ELSE 0 END) AS bel_brg,
					SUM(CASE WHEN a.kd_rek='523' THEN a.nilai ELSE 0 END) AS bel_mod,
					SUM(CASE WHEN a.kd_rek='515' THEN a.nilai ELSE 0 END) AS bansos
					FROM(
					SELECT MONTH(tgl_sp2d) as bulan,LEFT(d.kd_rek5,3) as kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
					trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
					trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
					trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
					WHERE MONTH(tgl_sp2d)<=$bulan AND LEFT(d.kd_rek5,1) IN ('5')
					AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
					GROUP BY MONTH(tgl_sp2d),LEFT(d.kd_rek5,3)
					)a
					GROUP BY bulan
					ORDER BY bulan
					";
		} else if ($jenis == 3) {
			$sql = "SELECT bulan,
					SUM(CASE WHEN a.kd_rek IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg,
					SUM(CASE WHEN a.kd_rek='522' THEN a.nilai ELSE 0 END) AS bel_brg,
					SUM(CASE WHEN a.kd_rek='523' THEN a.nilai ELSE 0 END) AS bel_mod,
					SUM(CASE WHEN a.kd_rek='515' THEN a.nilai ELSE 0 END) AS bansos
					FROM(
					SELECT MONTH(tgl_kas_bud) as bulan,LEFT(d.kd_rek5,3) as kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
					trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
					trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
					trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
					WHERE a.status_bud='1' AND MONTH(tgl_kas_bud)<=$bulan AND LEFT(d.kd_rek5,1) IN ('5','6','7')
					AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
					GROUP BY MONTH(tgl_kas_bud),LEFT(d.kd_rek5,3)
					)a
					GROUP BY bulan
					ORDER BY bulan
					";
		} else if ($jenis == 4) {
			$sql = "SELECT bulan,
					SUM(CASE WHEN a.kd_rek IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg,
					SUM(CASE WHEN a.kd_rek='522' THEN a.nilai ELSE 0 END) AS bel_brg,
					SUM(CASE WHEN a.kd_rek='523' THEN a.nilai ELSE 0 END) AS bel_mod,
					SUM(CASE WHEN a.kd_rek='515' THEN a.nilai ELSE 0 END) AS bansos
					FROM(
					SELECT MONTH(tgl_sp2d) as bulan,LEFT(d.kd_rek5,3) as kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
					trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
					trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
					trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
					WHERE MONTH(tgl_sp2d)<=$bulan 
					AND no_sp2d in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji)
					AND LEFT(d.kd_rek5,1) IN ('5')
					AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
					GROUP BY MONTH(tgl_sp2d),LEFT(d.kd_rek5,3)
					)a
					GROUP BY bulan
					ORDER BY bulan
					";
		}
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$bln = $row->bulan;
			$bel_peg = $row->bel_peg;
			$bel_brg = $row->bel_brg;
			$bel_mod = $row->bel_mod;
			$bansos = $row->bansos;
			$tot_peg = $tot_peg + $bel_peg;
			$tot_brg = $tot_brg + $bel_brg;
			$tot_mod = $tot_mod + $bel_mod;
			$tot_bansos = $tot_bansos + $bansos;

			$jum = $bel_peg + $bel_brg + $bel_mod + $bansos;
			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $no . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $this->getBulan($bln) . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_brg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bansos, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jum, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan="2" align="center" valign="top" style="font-size:12px">Total s/d ' . $this->getBulan($bulan) . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_brg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_bansos, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_peg + $tot_mod + $tot_brg + $tot_bansos, "2", ",", ".") . '</td> 
							</tr>';


		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'Lamp_BPS';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_lra_sap_spj($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		$sqlnogub = "SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I");
			$ket_perda         = strtoupper($rowsc->ket_perda);
			$ket_perda_no      = strtoupper($rowsc->ket_perda_no);
			$ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >' . $ket_lampiran . '</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >' . $ket_perda . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_no . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_tentang . '</TD>
					</TR>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_sap ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$jenis = $row->jenis;
			$spasi = $row->spasi;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}



		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_SAP ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_lra_sap_sp2d($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($jenis == 2) {
			$tabel = 'data_sp2d_n';
		} else if ($jenis == 3) {
			$tabel = 'data_sp2d_lunas_n';
		} else if ($jenis == 4) {
			$tabel = 'data_sp2d_advice_n';
		}

		$sqlnogub = "SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I");
			$ket_perda         = strtoupper($rowsc->ket_perda);
			$ket_perda_no      = strtoupper($rowsc->ket_perda_no);
			$ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
		}


		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >' . $ket_lampiran . '</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >' . $ket_perda . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_no . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_tentang . '</TD>
					</TR>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_sap ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			//$jenis = $row->jenis;
			$spasi = $row->spasi;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}



		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_SAP ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_lra_permen_spj($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		$sqlnogub = "SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$no_perda     = $rowsc->no_perda;
			$tgl_perda     = $rowsc->tgl_perda;
			$no_pergub     = $rowsc->no_pergub;
			$tgl_pergub    = $rowsc->tgl_pergub;
		}
		if ($test === 0) {
			$no_perda = "";
			$tgl_perda = "";
			$no_pergub = "";
			$tgl_pergub = "";
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
						<TD width="40%"  align="left" >Peraturan Gubernur Kalimantan Barat <br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_permen ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$jenis = $row->jenis;
			$spasi = $row->spasi;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}



		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_lra_permen_sp2d($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($jenis == 2) {
			$tabel = 'data_sp2d_n';
		} else if ($jenis == 3) {
			$tabel = 'data_sp2d_lunas_n';
		} else if ($jenis == 4) {
			$tabel = 'data_sp2d_advice_n';
		}

		$sqlnogub = "SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I");
			$ket_perda         = strtoupper($rowsc->ket_perda);
			$ket_perda_no      = strtoupper($rowsc->ket_perda_no);
			$ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >' . $ket_lampiran . '</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >' . $ket_perda . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_no . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_tentang . '</TD>
					</TR>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62')  $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_permen ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			//$jenis = $row->jenis;
			$spasi = $row->spasi;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4))  $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}



		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_lra_permen39($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		$sqlnogub = "SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$no_perda     = $rowsc->no_perda;
			$tgl_perda     = $rowsc->tgl_perda;
			$no_pergub     = $rowsc->no_pergub;
			$tgl_pergub    = $rowsc->tgl_pergub;
		}
		if ($test === 0) {
			$no_perda = "";
			$tgl_perda = "";
			$no_pergub = "";
			$tgl_pergub = "";
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
						<TD width="40%"  align="left" >Peraturan Gubernur Kalimantan Barat <br>Nomor : ' . $no_pergub . '<br>Tanggal: ' . $tgl_pergub . ' </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}

		$sql = "SELECT SUM(nilai) as nil_ang, SUM(debet-kredit) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,5) 
					NOT IN (52223)) AND LEFT(kd_rek5,3)='522';
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_hibah = $row->nil_ang;
			$nil_hibah = $row->nilai;
		}
		$sisa_hibah = $ang_hibah - $nil_hibah;
		if (($ang_hibah == 0) || ($ang_hibah == '')) {
			$persen_hibah = 0;
		} else {
			$persen_hibah = $nil_hibah / $ang_surplus;
		}
		$hasil->free_result();



		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_permen39 ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$jenis = $row->jenis;
			$spasi = $row->spasi;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($ang_hibah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nil_hibah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sisa_hibah, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen_hibah, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}



		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN39 ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_real_brg($kdskpd = '', $rek3 = '', $ctk = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lcskpd = $kdskpd;


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >LAPORAN REALISASI BELANJA BARANG <BR> TAHUN ANGGARAN ' . $lntahunang . '</TD>
					</TR>
					</TABLE>';

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kode</td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Nama SKPD</td>
					<td colspan=\"4\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran Belanja Barang</td>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah</td>
					<td colspan=\"4\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi Belanja Barang</td>
					<td rowspan=\"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah</td>
                </tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Belanja Barang & Jasa</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Belanja Pemeliharaan</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Perjalanan Dinas</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Honorarium</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Belanja Barang & Jasa</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Belanja Pemeliharaan</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Perjalanan Dinas</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Honorarium</td>
				</tr>
				
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
				</tr>
				</thead>";

		$tot_ang_brng = 0;
		$tot_ang_pelihara = 0;
		$tot_ang_dinas = 0;
		$tot_ang_honor = 0;
		$total_ang = 0;
		$tot_real_brng = 0;
		$tot_real_pelihara = 0;
		$tot_real_dinas = 0;
		$tot_real_honor = 0;
		$total_real = 0;
		$no = 0;
		$sql = "SELECT a.kd_skpd, nm_skpd 
					, ISNULL(ang_brng,0) ang_brng
					, ISNULL(ang_pelihara,0) ang_pelihara
					, ISNULL(ang_dinas,0) ang_dinas
					, ISNULL(ang_honor,0) ang_honor
					, ISNULL(real_brng,0) real_brng
					, ISNULL(real_pelihara,0) real_pelihara
					, ISNULL(real_dinas,0) real_dinas
					, ISNULL(real_honor,0) real_honor
					FROM ms_skpd a 
					LEFT JOIN 
					(
					SELECT kd_skpd
					, ISNULL(ang_brng,0) ang_brng
					, ISNULL(ang_pelihara,0) ang_pelihara
					, ISNULL(ang_dinas,0) ang_dinas
					, ISNULL(ang_honor,0) ang_honor
					, ISNULL(b.real_brng,0) real_brng
					, ISNULL(b.real_pelihara,0) real_pelihara
					, ISNULL(b.real_dinas,0) real_dinas
					, ISNULL(b.real_honor,0) real_honor
					FROM
					(SELECT kd_skpd
					,SUM(CASE WHEN LEFT(kd_rek5,5) IN ('52104','52105','52201','52203','52204','52205','52206','52207','52208','52209','52210','52211',
'52212','52213','52214','52216','52217','52218','52219','52221','52222','52223','52224','52225'
,'52227') THEN nilai_ubah ELSE 0 END) as ang_brng
					,SUM(CASE WHEN LEFT(kd_rek5,5) = '52220' THEN nilai_ubah ELSE 0 END) as ang_pelihara
					,SUM(CASE WHEN LEFT(kd_rek5,5) = '52215' THEN nilai_ubah ELSE 0 END) as ang_dinas
					,SUM(CASE WHEN LEFT(kd_rek5,5) IN ('52226') THEN nilai_ubah ELSE 0 END) as ang_honor
					FROM trdrka 
					WHERE LEFT(kd_rek5,2)='52'
					GROUP BY kd_skpd
					) a 
					LEFT JOIN
					(
					SELECT a.kd_unit
					,SUM(CASE WHEN LEFT(a.map_real,5) IN ('52104','52105','52201','52203','52204','52205','52206','52207','52208','52209','52210','52211',
'52212','52213','52214','52216','52217','52218','52219','52221','52222','52223','52224','52225'
,'52227') THEN (debet-kredit) ELSE 0 END) as real_brng
					,SUM(CASE WHEN LEFT(a.map_real,5) = '52220' THEN (debet-kredit) ELSE 0 END) as real_pelihara
					,SUM(CASE WHEN LEFT(a.map_real,5) = '52215' THEN (debet-kredit) ELSE 0 END) as real_dinas
					,SUM(CASE WHEN LEFT(a.map_real,5) IN ('52226') THEN (debet-kredit) ELSE 0 END) as real_honor
					FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
					WHERE LEFT(a.map_real,2)='52'
					GROUP BY a.kd_unit)b
					ON a.kd_skpd=b.kd_unit
					) b 
					ON a.kd_skpd=b.kd_skpd
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kd_skpd;
			$nama = $row->nm_skpd;
			$ang_brng = $row->ang_brng;
			$ang_pelihara = $row->ang_pelihara;
			$ang_dinas = $row->ang_dinas;
			$ang_honor = $row->ang_honor;
			$real_brng = $row->real_brng;
			$real_pelihara = $row->real_pelihara;
			$real_dinas = $row->real_dinas;
			$real_honor = $row->real_honor;
			$tot_ang = $ang_brng + $ang_pelihara + $ang_dinas + $ang_honor;
			$tot_real = $real_brng + $real_pelihara + $real_dinas + $real_honor;

			$tot_ang_brng = $tot_ang_brng + $ang_brng;
			$tot_ang_pelihara = $tot_ang_pelihara + $ang_pelihara;
			$tot_ang_dinas = $tot_ang_brng + $ang_dinas;
			$tot_ang_honor = $tot_ang_honor + $ang_honor;
			$total_ang = $total_ang + $tot_ang;
			$tot_real_brng = $tot_real_brng + $real_brng;
			$tot_real_pelihara = $tot_real_pelihara + $real_pelihara;
			$tot_real_dinas = $tot_real_dinas + $real_dinas;
			$tot_real_honor = $tot_real_honor + $real_honor;
			$total_real = $total_real + $tot_real;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nama . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_pelihara, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_dinas, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_honor, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_pelihara, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_dinas, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_honor, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= '<tr>
							   <td colspan="2" align="center" valign="top" style="font-size:12px">JUMLAH</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang_pelihara, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang_dinas, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang_honor, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($total_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real_pelihara, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real_dinas, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real_honor, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($total_real, "2", ",", ".") . '</td> 
							</tr>';

		$cRet .= "</table>";
		$data['prev'] = $cRet;
		$judul = 'realisasi_barang';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_lamp_semester_sap_spj_periode($tgl1 = '', $tgl2 = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $tglttd = '', $ttd = '', $format = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lctgl1 = $tgl1;
		$lctgl2 = $tgl2;
		$lcperiode = $this->tukd_model->tanggal_format_indonesia($lctgl1) . "  S.D. " . $this->tukd_model->tanggal_format_indonesia($lctgl2);
		$tanggal  = explode('-', $lctgl2);
		$bulan 	  = $tanggal[1];


		if ($anggaran == 11) {
			$sumber_data1 = "_sempurna1";
		} else if ($anggaran == 12) {
			$sumber_data1 = "_sempurna2";
		} else if ($anggaran == 13) {
			$sumber_data1 = "_sempurna3";
		} else if ($anggaran == 14) {
			$sumber_data1 = "_sempurna4";
		} else {
			$sumber_data1 = "";
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($format == 1) {
			$map_lra = "map_lra_sap_baru";
		} else {
			$map_lra = "map_lra_sap_konversi";
		}

		if ($kd_skpd == '-') {
			$where = "";
			$panjang = 0;
		} else if (strlen($kd_skpd) == '1.20.03') {
			$where = "AND LEFT(kd_skpd,7)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
			$panjang = 7;
		} else if (strlen($kd_skpd) == '7') {
			$where = "AND kd_org='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
			$panjang = 7;
		} else {
			$panjang = strlen($kd_skpd);
			$where = "AND LEFT(kd_skpd,$panjang)='$kd_skpd'";
		}

		$sql = $this->db->query("select top 1 * from sclient");
		foreach ($sql->result() as $row) {
			$daerah = $row->daerah;
			$pemerintahan = $row->kab_kota;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='BUP'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" ></TD>
						<TD width="40%"  align="left" ></TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$pemerintahan </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $lcperiode APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		if ($panjang == 17) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_bidang_urusan', 'ms_bidang_urusan', 'kd_bidang_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
                    </TABLE>";
		}
		if ($panjang == 22) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_bidang_urusan', 'ms_bidang_urusan', 'kd_bidang_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 22) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 22), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
                    </TABLE>";
		}
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $lcperiode</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek6,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n_periode$sumber_data1('$tgl1','$tgl2',$anggaran,$lntahunang) WHERE LEFT(kd_rek6,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek6,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}

		$sisa_surplus = $nil_surplus - $ang_surplus;

		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();

		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek6,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n_periode$sumber_data1('$tgl1','$tgl2',$anggaran,$lntahunang) WHERE LEFT(kd_rek6,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek6,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $nil_netto - $ang_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $nil_silpa - $ang_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4,kode5, jenis, spasi, jns_rek FROM $map_lra ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$jenis = $row->jenis;
			$spasi = $row->spasi;
			$jns_rek = $row->jns_rek;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXXx'";
			}
			if ($kode4 == '') {
				$kode4 = "'xXXXXX'";
			}
			if ($kode5 == '') {
				$kode5 = "'xxxXXXXX'";
			}
			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n_periode$sumber_data1('$tgl1','$tgl2',$anggaran,$lntahunang) WHERE (
							LEFT(kd_rek6,1) IN ($kode1) or 
							LEFT(kd_rek6,2) IN ($kode2) or 
							LEFT(kd_rek6,4) IN ($kode3) or 
							LEFT(kd_rek6,6) IN ($kode4) or
							LEFT(kd_rek6,8) IN ($kode5)
						) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}

			//coba
			if ($jns_rek == '1') {
				$sel = $nil_ang - $nilai;
			} else {
				$sel = $nilai - $nil_ang;
			}

			if ($sel < 0) {
				$x = "(";
				$y = ")";
				$sel1 = $sel * -1;
			} else {
				$x = "";
				$y = "";
				$sel1 = $sel;
			}


			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '' . $y . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($persen_surplus, "2", ",", ".") . '' . $f . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($persen_netto, "2", ",", ".") . '' . $l . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($persen_silpa, "2", ",", ".") . '' . $r . '</b></td> 
							</tr>';
					break;
			}
		}
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_SAP ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_lamp_semester_sap_sp2d($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $tglttd = '', $ttd = '', $format = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}
		if ($kd_skpd == '-') {
			$where = "";
			$panjang = 0;
		} else {
			$panjang = strlen($kd_skpd);
			$where = "AND LEFT(kd_skpd,$panjang)='$kd_skpd'";
		}

		if ($jenis == 2) {
			$tabel = 'data_sp2d_n';
		} else if ($jenis == 3) {
			$tabel = 'data_sp2d_lunas_n';
		} else if ($jenis == 4) {
			$tabel = 'data_sp2d_advice_n';
		}

		if ($format == 1) {
			$map_lra = "map_lra_sap";
		} else {
			$map_lra = "map_lra_sap_konversi";
		}

		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		if ($panjang == 7) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
                    </TABLE>";
		}
		if ($panjang == 10) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 10) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 10), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
                    </TABLE>";
		}
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM $map_lra ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			//$jenis = $row->jenis;
			$spasi = $row->spasi;
			$jns_rek = $row->jns_rek;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			//coba
			if ($jns_rek == '1') {
				$sel = $nil_ang - $nilai;
			} else {
				$sel = $nilai - $nil_ang;
			}

			if ($sel < 0) {
				$x = "(";
				$y = ")";
				$sel1 = $sel * -1;
			} else {
				$x = "";
				$y = "";
				$sel1 = $sel;
			}

			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_SAP ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_semester_permen_spj_periode($tgl1 = '', $tgl2 = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $tglttd = '', $ttd = '', $format = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lctgl1 = $tgl1;
		$lctgl2 = $tgl2;
		$lcperiode = $this->tukd_model->tanggal_format_indonesia($lctgl1) . "  S.D. " . $this->tukd_model->tanggal_format_indonesia($lctgl2);

		$tanggal  = explode('-', $lctgl2);
		$bulan 	  = $tanggal[1];

		if ($anggaran == 11) {
			$sumber_data1 = "_sempurna1";
		} else if ($anggaran == 12) {
			$sumber_data1 = "_sempurna2";
		} else if ($anggaran == 13) {
			$sumber_data1 = "_sempurna3";
		} else if ($anggaran == 14) {
			$sumber_data1 = "_sempurna4";
		} else {
			$sumber_data1 = "";
		}


		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($format == 1) {
			$map_lra = "map_lra_permen";
		} else {
			$map_lra = "map_lra_permen_konversi";
		}

		if ($kd_skpd == '-') {
			$where = "";
			$panjang = 0;
		} else if (strlen($kd_skpd) == '1.20.03') {
			$where = "AND LEFT(kd_skpd,7)='$kd_skpd'";
			$panjang = 7;
		} else if (strlen($kd_skpd) == '7') {
			$where = "AND kd_org='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
			$panjang = 7;
		} else {
			$panjang = strlen($kd_skpd);
			$where = "AND LEFT(kd_skpd,$panjang)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
		}

		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >   </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logo-kabupaten.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $lcperiode APBD <BR> DAN PROGNOSIS $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		if ($panjang == 7) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
                    </TABLE>";
		}
		if ($panjang == 10) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 10) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 10), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
                    </TABLE>";
		}
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $lcperiode</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n_periode$sumber_data1('$tgl1','$tgl2',$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $nil_surplus - $ang_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n_periode$sumber_data1('$tgl1','$tgl2',$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $nil_netto - $ang_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $nil_silpa - $ang_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM $map_lra ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$jenis = $row->jenis;
			$spasi = $row->spasi;
			$jns_rek = $row->jns_rek;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n_periode$sumber_data1('$tgl1','$tgl2',$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}

			//coba
			if ($jns_rek == '1') {
				$sel = $nil_ang - $nilai;
			} else {
				$sel = $nilai - $nil_ang;
			}

			if ($sel < 0) {
				$x = "(";
				$y = ")";
				$sel1 = $sel * -1;
			} else {
				$x = "";
				$y = "";
				$sel1 = $sel;
			}

			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
                                <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td>
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . ' </b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_semester_permen_sp2d($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		if ($kd_skpd == '-') {
			$where = "";
			$panjang = 0;
		} else {
			$panjang = strlen($kd_skpd);
			$where = "AND LEFT(kd_skpd,$panjang)='$kd_skpd'";
		}

		if ($jenis == 2) {
			$tabel = 'data_sp2d_n';
		} else if ($jenis == 3) {
			$tabel = 'data_sp2d_lunas_n';
		} else if ($jenis == 4) {
			$tabel = 'data_sp2d_advice_n';
		}


		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';

		$bulan2 = 12 - $bulan;
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		if ($panjang == 7) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
                    </TABLE>";
		}
		if ($panjang == 10) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 10) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 10), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
                    </TABLE>";
		}
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $nil_surplus - $ang_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62')  $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $nil_netto - $ang_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $nil_silpa - $ang_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM map_lra_permen ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			//$jenis = $row->jenis;
			$spasi = $row->spasi;
			$jns_rek = $row->jns_rek;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4))  $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			//coba
			if ($jns_rek == '1') {
				$sel = $nilai - $nil_ang;
			} else {
				$sel = $nil_ang - $nilai;
			}

			if ($sel < 0) {
				$x = "(";
				$y = ")";
				$sel1 = $sel * -1;
			} else {
				$x = "";
				$y = "";
				$sel1 = $sel;
			}

			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top">' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($sel1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_1($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		if ($anggaran == '1') {
			$ang = 'nilai';
		}
		if ($anggaran == '2') {
			$ang = 'nilai_sempurna';
		} else {
			$ang = 'nilai_ubah';
		}


		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}


		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="70%" valign="top" align="right" > Lampiran I.1 : </TD>
						<TD width="30%"  align="left" >Rancangan Peraturan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"3\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"4\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PENDAPATAN</td>
                    <td colspan = \"6\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA</td>
                </tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN SETELAH PERUBAHAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI</td> 
                   <td colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BERTAMBAH/KURANG</td> 
                   <td rowspan =\"2\" align=\"LEFT\" bgcolor=\"#CCCCCC\" style=\"font-size:9px\"> 7. BLJ PEGAWAI<BR>
																									 8. BLJ BARANG JASA<BR>
																									 9. BLJ MODAL<BR>
																									 10.BLJ BUNGA<BR>
																									 11.BLJ SUBSIDI<BR>
																									 12.BLJ HIBAH<BR>
																									 13.BLJ BANSOS<BR>
																									 14.BLJ BAGI HASIL<BR>
																									 15.BLJ BANTUAN KEU.<BR>
																									 16.BLJ TDK TERDUGA
																									 </td> 
                   <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH BELANJA</td> 
				   <td rowspan =\"2\" align=\"LEFT\" bgcolor=\"#CCCCCC\" style=\"font-size:9px\">18.BLJ PEGAWAI<BR>
																									 19.BLJ BARANG JASA<BR>
																									 20.BLJ MODAL<BR>
																									 21.BLJ BUNGA<BR>
																									 22.BLJ SUBSIDI<BR>
																									 23.BLJ HIBAH<BR>
																									 24.BLJ BANSOS<BR>
																									 25.BLJ BAGI HASIL<BR>
																									 26.BLJ BANTUAN KEU.<BR>
																									 27.BLJ TDK TERDUGA
																									 </td> 
                   <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH BELANJA</td> 
				   <td colspan=\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BERTAMBAH/KURANG</td> 
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td> 
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5(4-3)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17=(7+sd+16)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">28=(18+sd+27)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">29=28-17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">30</td> 
				</tr>
				</thead>";

		$tot_ang_brng = 0;
		$tot_ang_pelihara = 0;
		$tot_ang_dinas = 0;
		$tot_ang_honor = 0;
		$total_ang = 0;
		$tot_real_brng = 0;
		$tot_real_pelihara = 0;
		$tot_real_dinas = 0;
		$tot_real_honor = 0;
		$total_real = 0;
		$no = 0;
		$sql = "
SELECT a.kd_urusan1 as kode ,a.nm_urusan1 as nama,
isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
ISNULL(bel_pend,0) as bel_pend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

FROM ms_urusan1  a 
LEFT JOIN
(
SELECT a.kode, 
ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
FROM (
SELECT LEFT(a.kd_skpd,1) as kode 
,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
FROM trdrka a GROUP BY LEFT(a.kd_skpd,1) ) a
LEFT JOIN 
(SELECT a.kode as kode 
,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
FROM
(SELECT LEFT(a.kd_skpd,1) as kode ,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan') a GROUP BY LEFT(a.kd_skpd,1) ,LEFT(kd_rek5,3))a
GROUP BY a.kode) b
ON a.kode=b.kode
) b ON a.kd_urusan1=b.kode
UNION ALL
SELECT a.kd_urusan as kode ,a.nm_urusan as nama,
isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
ISNULL(bel_pend,0) as bel_bend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

FROM ms_urusan  a 
LEFT JOIN
(
SELECT a.kode, 
ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
FROM (
SELECT LEFT(a.kd_skpd,4) as kode 
,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
FROM trdrka a GROUP BY LEFT(a.kd_skpd,4) ) a
LEFT JOIN 
(SELECT a.kode as kode 
,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
FROM
(SELECT LEFT(a.kd_skpd,4) as kode ,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan') a GROUP BY LEFT(a.kd_skpd,4) ,LEFT(kd_rek5,3))a
GROUP BY a.kode) b
ON a.kode=b.kode
) b ON a.kd_urusan=b.kode
			UNION ALL
			SELECT a.kd_org as kode ,a.nm_org as nama,
			isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
			ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
			ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
			ISNULL(bel_pend,0) as bel_bend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
			ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
			ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

			FROM ms_organisasi a 
			LEFT JOIN
			(
			SELECT a.kode, 
			ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
			bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
			FROM (
			SELECT LEFT(a.kd_skpd,7) as kode 
			,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
			FROM trdrka a GROUP BY LEFT(a.kd_skpd,7) ) a
			LEFT JOIN 
			(SELECT a.kode as kode 
			,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
			,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
			FROM
			(SELECT LEFT(a.kd_skpd,7) as kode ,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
			SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
			WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' ) a GROUP BY LEFT(a.kd_skpd,7) ,LEFT(kd_rek5,3))a
			GROUP BY a.kode) b
			ON a.kode=b.kode
			) b ON a.kd_org=b.kode
			
					UNION ALL
					SELECT a.kd_skpd as kode ,a.nm_skpd as nama,
					ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
					bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga

					FROM 
					ms_skpd a
					LEFT JOIN
					(
					SELECT a.kd_skpd, 
					ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
					bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
					FROM (
					SELECT a.kd_skpd
					,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
					FROM trdrka a GROUP BY a.kd_skpd) a
					LEFT JOIN 
					(SELECT a.kd_skpd
					,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
					,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
					FROM
					(SELECT kd_skpd,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
					SELECT a.kd_skpd,b.kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
					WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' ) a GROUP BY kd_skpd,LEFT(kd_rek5,3))a
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					) b ON a.kd_skpd=b.kd_skpd
					order by kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kode;
			$nama = $row->nama;
			$ang_pend = $row->ang_pend;
			$ang_peg = $row->ang_peg;
			$ang_brjs = $row->ang_brjs;
			$ang_modal = $row->ang_modal;
			$ang_bunga = $row->ang_bunga;
			$ang_subsidi = $row->ang_subsidi;
			$ang_hibah = $row->ang_hibah;
			$ang_bansos = $row->ang_bansos;
			$ang_bghasil = $row->ang_bghasil;
			$ang_bantuan  = $row->ang_bantuan;
			$ang_takterduga = $row->ang_takterduga;
			$bel_pend = $row->bel_pend;
			$bel_peg = $row->bel_peg;
			$bel_brjs = $row->bel_brjs;
			$bel_modal = $row->bel_modal;
			$bel_bunga = $row->bel_bunga;
			$bel_subsidi = $row->bel_subsidi;
			$bel_hibah = $row->bel_hibah;
			$bel_bansos = $row->bel_bansos;
			$bel_bghasil = $row->bel_bghasil;
			$bel_bantuan = $row->bel_bantuan;
			$bel_takterduga = $row->bel_takterduga;

			$tot_ang = $ang_peg + $ang_brjs + $ang_modal + $ang_bunga + $ang_subsidi + $ang_hibah + $ang_bansos + $ang_bghasil + $ang_bantuan + $ang_takterduga;
			$tot_bel = $bel_peg + $bel_brjs + $bel_modal + $bel_bunga + $bel_subsidi + $bel_hibah + $bel_bansos + $bel_bghasil + $bel_bantuan + $bel_takterduga;
			$per_pend  = $ang_pend == 0 || $ang_peg == '' ? 0 : $bel_pend / $ang_pend;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_pend - $bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($per_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '<br>
																					' . number_format($ang_brjs, "2", ",", ".") . '<br>
																					' . number_format($ang_modal, "2", ",", ".") . '<br>
																					' . number_format($ang_bunga, "2", ",", ".") . '<br>
																					' . number_format($ang_subsidi, "2", ",", ".") . '<br>
																					' . number_format($ang_hibah, "2", ",", ".") . '<br>
																					' . number_format($ang_bansos, "2", ",", ".") . '<br>
																					' . number_format($ang_bghasil, "2", ",", ".") . '<br>
																					' . number_format($ang_bantuan, "2", ",", ".") . '<br>
																					' . number_format($ang_takterduga, "2", ",", ".") . '
							   </td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
								 <td align="right" valign="top" style="font-size:12px">' . number_format($bel_peg, "2", ",", ".") . '<br>
																					' . number_format($bel_brjs, "2", ",", ".") . '<br>
																					' . number_format($bel_modal, "2", ",", ".") . '<br>
																					' . number_format($bel_bunga, "2", ",", ".") . '<br>
																					' . number_format($bel_subsidi, "2", ",", ".") . '<br>
																					' . number_format($bel_hibah, "2", ",", ".") . '<br>
																					' . number_format($bel_bansos, "2", ",", ".") . '<br>
																					' . number_format($bel_bghasil, "2", ",", ".") . '<br>
																					' . number_format($bel_bantuan, "2", ",", ".") . '<br>
																					' . number_format($bel_takterduga, "2", ",", ".") . '
							   </td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_semester_rinci_unit_spj($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $spj = '', $format = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($anggaran == 11) {
			$sumber_data1 = "_sempurna1";
		} else if ($anggaran == 12) {
			$sumber_data1 = "_sempurna2";
		} else if ($anggaran == 13) {
			$sumber_data1 = "_sempurna3";
		} else if ($anggaran == 14) {
			$sumber_data1 = "_sempurna4";
		} else {
			$sumber_data1 = "";
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($format == 1) {
			$conv = "";
		} else {
			/*$conv= "_conv";*/
			$conv = "_conv1";
		}

		$sql = $this->db->query("SELECT top 1 daerah,kab_kota from sclient");
		$row = $sql->row();
		$daerah = $row->daerah;
		$kab_kota = $row->kab_kota;

		$where = "WHERE kd_skpd='$kd_skpd'";
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$where1 = "AND LEN(kd_rek)<='$jenis'";
		if ($jenis == '5') {
			$wherejns = "AND kd_rek not like '%.%'";
		} else {
			$wherejns = "";
		}
		$nm_skpd = $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$angkabulan2 = $this->tukd_model->terbilang_angka($bulan2);
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab_kota </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;text-transform:uppercase;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN <BR> PROGNOSIS $bulan2 ($angkabulan2) BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
					$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					
                    </TABLE>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT (case when LEN(kd_kegiatan)>12 then RIGHT(kd_kegiatan,5) else RIGHT(kd_kegiatan,2) end) kd_kegiatan,
		kd_rek,nm_rek,SUM(anggaran) anggaran, SUM(sd_bulan_ini) sd_bulan_ini 
		FROM realisasi_jurnal_pend($bulan,$anggaran) $where $where1  
		GROUP BY kd_kegiatan,kd_rek,nm_rek
		ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);

			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 7:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend($bulan,$anggaran) $where AND LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $this->tukd_model->rp_minus($sisa) . '</b></td> 
							   <td align="right" valign="top"><b>' . $this->tukd_model->rp_minus($sisa) . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where AND LEN(kd_rek)='$jenis'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $nil_ang1 - $sd_bulan_ini1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa11 < 0 ? '(' : '';
			$b = $sisa11 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$sql = "SELECT kd_kegiatan,kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
		FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where $where1
		AND SUBSTRING(kd_kegiatan,6,2)='00'
		GROUP BY kd_kegiatan,kd_rek,nm_rek
		ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$urut = $row->urut;
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);

			switch ($leng) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();


		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';

		$sql = "SELECT '1' kd_rek,'BELANJA LANGSUNG' nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
		FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where 
		  AND kd_rek IN ('5201','5202','5203')
		  UNION ALL
		  SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
		  FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where 
		  AND kd_rek IN ('5201','5202','5203')
		  GROUP BY kd_rek,nm_rek
		  ORDER BY kd_rek	";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;

			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT  kd_kegiatan,kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
		FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where $where1
		AND SUBSTRING(kd_kegiatan,6,2)!='00'
		GROUP BY kd_kegiatan,kd_rek,nm_rek
		ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$urut = $row->urut;
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);

			switch ($leng) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER_UNIT ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_lamp_semester_rinci_unit_sp2d($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $spj = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($spj == 2) {
			$tabel = 'data_sp2d_rinci_n';
		} else if ($spj == 3) {
			$tabel = 'data_sp2d_lunas_rinci_n';
		} else if ($spj == 4) {
			$tabel = 'data_sp2d_advice_rinci_n';
		}
		$where = "WHERE kd_skpd='$kd_skpd'";
		$where1 = "AND LEN(kd_rek)<='$jenis'";
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$nm_skpd = $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARATsd </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 10) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 10), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
                    </TABLE>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where $where1 ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);
			switch ($leng) {
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 7:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(nil_ang) anggaran ,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) $where AND  LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $nil_ang1 - $sd_bulan_ini;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$sql = "SELECT kd_kegiatan,kd_rek,nm_rek,nil_ang anggaran,nilai sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) $where $where1 
						 AND SUBSTRING(kd_kegiatan,17,5)='00.51' ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			switch ($leng) {
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 7:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';

		$sql = "SELECT '1' kd_rek,'BELANJA LANGSUNG' nm_rek,SUM(nil_ang) anggaran,SUM(nilai) sd_bulan_ini
						  FROM $tabel($bulan,$anggaran,$lntahunang) $where 
							AND kd_rek IN('521','522','523')
							UNION ALL
							SELECT kd_rek,nm_rek,SUM(nil_ang) anggaran,SUM(nilai) sd_bulan_ini
						  FROM $tabel($bulan,$anggaran,$lntahunang) $where 
							AND kd_rek IN('521','522','523')
							GROUP BY kd_rek,nm_rek
							ORDER BY kd_rek	";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';

		$sql = "SELECT kd_kegiatan,kd_rek,nm_rek,nil_ang anggaran,nilai sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) $where $where1 
						 AND SUBSTRING(kd_kegiatan,17,2)!='00' ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			switch ($leng) {
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 7:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}


		$hasil->free_result();
		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_angreal_prog_giat_unit_sp2d($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $spj = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($spj == 2) {
			$tabel = 'data_sp2d_rinci_n';
		} else if ($spj == 3) {
			$tabel = 'data_sp2d_lunas_rinci_n';
		} else if ($spj == 4) {
			$tabel = 'data_sp2d_advice_rinci_n';
		}
		$where = "WHERE kd_skpd='$kd_skpd'";

		$cRet = "";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 10) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 10), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
                    </TABLE>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD SKPD</b></td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NM SKPD</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >8</td> 
				</tr>
				</thead>";

		$sql = "SELECT urut, kd_skpd, (select nm_skpd from ms_skpd where kd_skpd=g.kd_skpd) nm_skpd, kd_kegiatan kd_rek, nm_rek, nil_ang, nilai as real  FROM (
						----BTL
						SELECT 3 urut, * FROM (
						-- Per Program
						SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,LEFT(a.kd_kegiatan,18) kd_kegiatan,'' kd_rek,b.nm_program as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,LEFT(a.kd_kegiatan,18),b.nm_program) a
						LEFT JOIN
						(SELECT a.kd_skpd,LEFT(d.kd_kegiatan,18) kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,LEFT(d.kd_kegiatan,18))b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan

						UNION ALL

						--Per Kegiatan
						SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek,b.nm_kegiatan as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,a.kd_kegiatan,b.nm_kegiatan) a
						LEFT JOIN
						(SELECT a.kd_skpd,d.kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,d.kd_kegiatan)b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan) z
						where SUBSTRING(kd_kegiatan,17,5)='00.51' AND nil_ang<>0 

						UNION ALL

						--TOTAL BL
						-- Per Program
						SELECT 1 urut, x.kd_skpd, LEFT(x.kd_skpd,4)+'.'+x.kd_skpd+'.'+'00.52' kd_kegiatan, '' kd_rek, 'Belanja Langsung' nm_rek, x.anggaran nil_ang, y.realisasi nilai FROM (
						SELECT a.kd_skpd, SUM(a.nilai_sempurna) as anggaran
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						 where LEFT(a.kd_rek5,3) IN ('521','522','523')
						GROUP BY a.kd_skpd) x
						LEFT JOIN (
						SELECT d.kd_skpd, SUM(d.nilai) as realisasi FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,3) IN ('521','522','523')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						group by d.kd_skpd) y
						on x.kd_skpd=y.kd_skpd
						where x.anggaran<>0

						UNION ALL
						--BL
						SELECT * FROM (
						-- Per Program
						SELECT 3 urut, a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,LEFT(a.kd_kegiatan,18) kd_kegiatan,'' kd_rek,b.nm_program as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,LEFT(a.kd_kegiatan,18),b.nm_program) a
						LEFT JOIN
						(SELECT a.kd_skpd,LEFT(d.kd_kegiatan,18) kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,LEFT(d.kd_kegiatan,18))b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan

						UNION ALL

						--Per Kegiatan
						SELECT 2 urut, a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek,b.nm_kegiatan as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,a.kd_kegiatan,b.nm_kegiatan) a
						LEFT JOIN
						(SELECT a.kd_skpd,d.kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,d.kd_kegiatan)b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan) z
						where SUBSTRING(kd_kegiatan,17,5)<>'00.51' AND SUBSTRING(kd_kegiatan,17,5)<>'00.04' AND nm_rek<>'NON PROGRAM' AND nil_ang<>0) g
						where kd_skpd='$kd_skpd'
						order by kd_skpd, kd_kegiatan";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$urut      = $row->urut;
			$kd_skpd   = $row->kd_skpd;
			$nm_skpd   = $row->nm_skpd;
			$kd_rek    = $row->kd_rek;
			$uraian    = $row->nm_rek;
			$anggaran  = $row->nil_ang;
			$realisasi = $row->real;
			$sisa      = $anggaran - $realisasi;
			$persen    = empty($anggaran) || $anggaran == 0 ? 0 : $realisasi / $anggaran * 100;
			$sisa1     = $sisa < 0 ? $sisa * -1 : $sisa;
			$a         = $sisa < 0 ? '(' : '';
			$b         = $sisa < 0 ? ')' : '';

			switch ($urut) {
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_skpd . '</td> 
							   <td align="left"  valign="top">' . $nm_skpd . '</td> 
							   <td align="left" valign="top">' . $kd_rek . '</td> 
							   <td align="left" valign="top">' . $uraian . '</td> 
							   <td align="right" valign="top">' . number_format($anggaran, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($realisasi, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_skpd . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_skpd . '</b></td> 
							   <td align="left" valign="top"><b>' . $kd_rek . '</b></td> 
							   <td align="left" valign="top"><b>' . $uraian . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($realisasi, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_skpd . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_skpd . '</b></td> 
							   <td align="left" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>' . $uraian . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($realisasi, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();


		$sql2 = "SELECT SUM(x.anggaran) anggaran, SUM(z.realisasi) realisasi FROM (
							SELECT a.kd_skpd, SUM(a.nilai_sempurna) as anggaran
							 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
							 where LEFT(a.kd_rek5,2) IN ('51','52') and a.kd_skpd='$kd_skpd' 
							GROUP BY a.kd_skpd) x
							LEFT JOIN
							(
							SELECT d.kd_skpd, SUM(d.nilai) as realisasi FROM trhsp2d a INNER JOIN 
							trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
							trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
							trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
							WHERE d.kd_skpd='$kd_skpd' AND MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,2) IN ('51','52')
							AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
							group by d.kd_skpd
							) z
							on x.kd_skpd=z.kd_skpd";
		$hasil2 = $this->db->query($sql2);

		foreach ($hasil2->result() as $row) {
			$anggaran  = $row->anggaran;
			$realisasi = $row->realisasi;
			$persen    = empty($anggaran) || $anggaran == 0 ? 0 : $realisasi / $anggaran * 100;
			$sisa1     = $sisa < 0 ? $sisa * -1 : $sisa;
			$a         = $sisa < 0 ? '(' : '';
			$b         = $sisa < 0 ? ')' : '';


			$cRet .= '<tr>
							   <td align="center" valign="top" colspan="4"><b>Total</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($realisasi, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil2->free_result();

		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_angreal_prog_giat_all_sp2d($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $spj = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		if ($spj == 2) {
			$tabel = 'data_sp2d_rinci_n';
		} else if ($spj == 3) {
			$tabel = 'data_sp2d_lunas_rinci_n';
		} else if ($spj == 4) {
			$tabel = 'data_sp2d_advice_rinci_n';
		}
		$where = "WHERE kd_skpd='$kd_skpd'";

		$cRet = "";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD SKPD</b></td>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NM SKPD</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >8</td> 
				</tr>
				</thead>";

		$sql = "SELECT urut, kd_skpd, (select nm_skpd from ms_skpd where kd_skpd=g.kd_skpd) nm_skpd, kd_kegiatan kd_rek, nm_rek, nil_ang, nilai as real  FROM (
						----BTL
						SELECT 3 urut, * FROM (
						-- Per Program
						SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,LEFT(a.kd_kegiatan,18) kd_kegiatan,'' kd_rek,b.nm_program as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,LEFT(a.kd_kegiatan,18),b.nm_program) a
						LEFT JOIN
						(SELECT a.kd_skpd,LEFT(d.kd_kegiatan,18) kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,LEFT(d.kd_kegiatan,18))b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan

						UNION ALL

						--Per Kegiatan
						SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek,b.nm_kegiatan as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,a.kd_kegiatan,b.nm_kegiatan) a
						LEFT JOIN
						(SELECT a.kd_skpd,d.kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,d.kd_kegiatan)b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan) z
						where SUBSTRING(kd_kegiatan,17,5)='00.51' AND nil_ang<>0 

						UNION ALL

						--TOTAL BL
						-- Per Program
						SELECT 1 urut, x.kd_skpd, LEFT(x.kd_skpd,4)+'.'+x.kd_skpd+'.'+'00.52' kd_kegiatan, '' kd_rek, 'Belanja Langsung' nm_rek, x.anggaran nil_ang, y.realisasi nilai FROM (
						SELECT a.kd_skpd, SUM(a.nilai_sempurna) as anggaran
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						 where LEFT(a.kd_rek5,3) IN ('521','522','523')
						GROUP BY a.kd_skpd) x
						LEFT JOIN (
						SELECT d.kd_skpd, SUM(d.nilai) as realisasi FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,3) IN ('521','522','523')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						group by d.kd_skpd) y
						on x.kd_skpd=y.kd_skpd
						where x.anggaran<>0

						UNION ALL
						--BL
						SELECT * FROM (
						-- Per Program
						SELECT 3 urut, a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,LEFT(a.kd_kegiatan,18) kd_kegiatan,'' kd_rek,b.nm_program as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,LEFT(a.kd_kegiatan,18),b.nm_program) a
						LEFT JOIN
						(SELECT a.kd_skpd,LEFT(d.kd_kegiatan,18) kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,LEFT(d.kd_kegiatan,18))b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan

						UNION ALL

						--Per Kegiatan
						SELECT 2 urut, a.kd_skpd,a.kd_kegiatan,'' kd_rek, nm_rek, a.nilai nil_ang, ISNULL(b.nilai,0) as nilai
						 FROM (SELECT a.kd_skpd,a.kd_kegiatan,'' kd_rek,b.nm_kegiatan as nm_rek,
						case when ($anggaran=1) then sum(a.nilai) when ($anggaran=2) THEN SUM(a.nilai_sempurna) ELSE sum(a.nilai_ubah) end as nilai
						 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
						GROUP BY a.kd_skpd,a.kd_kegiatan,b.nm_kegiatan) a
						LEFT JOIN
						(SELECT a.kd_skpd,d.kd_kegiatan,'' kd_rek, SUM(d.nilai) as nilai FROM trhsp2d a INNER JOIN 
						trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
						trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
						trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
						WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,1) IN ('5')
						AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
						GROUP BY a.kd_skpd,d.kd_kegiatan)b
						ON  a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan) z
						where SUBSTRING(kd_kegiatan,17,5)<>'00.51' AND SUBSTRING(kd_kegiatan,17,5)<>'00.04' AND nm_rek<>'NON PROGRAM' AND nil_ang<>0) g
						order by kd_skpd, kd_kegiatan";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$urut      = $row->urut;
			$kd_skpd   = $row->kd_skpd;
			$nm_skpd   = $row->nm_skpd;
			$kd_rek    = $row->kd_rek;
			$uraian    = $row->nm_rek;
			$anggaran  = $row->nil_ang;
			$realisasi = $row->real;
			$sisa      = $anggaran - $realisasi;
			$persen    = empty($anggaran) || $anggaran == 0 ? 0 : $realisasi / $anggaran * 100;
			$sisa1     = $sisa < 0 ? $sisa * -1 : $sisa;
			$a         = $sisa < 0 ? '(' : '';
			$b         = $sisa < 0 ? ')' : '';

			switch ($urut) {
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_skpd . '</td> 
							   <td align="left"  valign="top">' . $nm_skpd . '</td> 
							   <td align="left" valign="top">' . $kd_rek . '</td> 
							   <td align="left" valign="top">' . $uraian . '</td> 
							   <td align="right" valign="top">' . number_format($anggaran, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($realisasi, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_skpd . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_skpd . '</b></td> 
							   <td align="left" valign="top"><b>' . $kd_rek . '</b></td> 
							   <td align="left" valign="top"><b>' . $uraian . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($realisasi, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_skpd . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_skpd . '</b></td> 
							   <td align="left" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>' . $uraian . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($realisasi, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();


		$sql2 = "SELECT SUM(x.anggaran) anggaran, SUM(z.realisasi) realisasi FROM (
							SELECT a.kd_skpd, SUM(a.nilai_sempurna) as anggaran
							 FROM trdrka a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
							 where LEFT(a.kd_rek5,2) IN ('51','52') 
							GROUP BY a.kd_skpd) x
							LEFT JOIN
							(
							SELECT d.kd_skpd, SUM(d.nilai) as realisasi FROM trhsp2d a INNER JOIN 
							trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm INNER JOIN
							trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp INNER JOIN
							trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp 
							WHERE MONTH(tgl_sp2d)<=$bulan AND YEAR(tgl_sp2d)=$lntahunang AND LEFT(d.kd_rek5,2) IN ('51','52')
							AND (c.sp2d_batal=0 OR c.sp2d_batal is NULL)
							group by d.kd_skpd
							) z
							on x.kd_skpd=z.kd_skpd";
		$hasil2 = $this->db->query($sql2);

		foreach ($hasil2->result() as $row) {
			$anggaran  = $row->anggaran;
			$realisasi = $row->realisasi;
			$persen    = empty($anggaran) || $anggaran == 0 ? 0 : $realisasi / $anggaran * 100;
			$sisa1     = $sisa < 0 ? $sisa * -1 : $sisa;
			$a         = $sisa < 0 ? '(' : '';
			$b         = $sisa < 0 ? ')' : '';


			$cRet .= '<tr>
							   <td align="center" valign="top" colspan="4"><b>Total</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($realisasi, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil2->free_result();

		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_lamp_semester_rinci_org_sp2d($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $spj = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}
		if ($spj == 2) {
			$tabel = 'data_sp2d_rinci_n';
		} else if ($spj == 3) {
			$tabel = 'data_sp2d_lunas_rinci_n';
		} else if ($spj == 4) {
			$tabel = 'data_sp2d_advice_rinci_n';
		}
		$where = "WHERE LEFT(kd_skpd,7)='$kd_skpd'";
		$where1 = "AND LEN(kd_rek)<='$jenis'";
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan1', 'ms_urusan1', 'kd_urusan1') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 4) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 7) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
                    </TABLE>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT RIGHT(kd_kegiatan,5) kd_kegiatan,kd_rek,nm_rek, SUM(anggaran) anggaran, SUM(sd_bulan_ini) sd_bulan_ini 
					  FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where $where1 
					  GROUP BY RIGHT(kd_kegiatan,5) ,kd_rek,nm_rek
					  ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);
			switch ($leng) {
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 7:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';

		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(nil_ang) anggaran ,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) $where AND  LEN(kd_rek)='$jenis' 
						  AND RIGHT(kd_kegiatan,5) !='00.04' AND RIGHT(kd_kegiatan,2) !='00'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $nil_ang1 - $sd_bulan_ini;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();




		$sql = "SELECT SUBSTRING(kd_kegiatan,17,5) kd_kegiatan,kd_rek,nm_rek,SUM(nil_ang) anggaran,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) $where $where1 
						  AND RIGHT(kd_kegiatan,5) ='00.51' AND RIGHT(kd_kegiatan,2) !='00'
						  GROUP BY SUBSTRING(kd_kegiatan,17,5),kd_rek,nm_rek
						  ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			switch ($leng) {
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 7:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';

		$sql = "SELECT '1' kd_rek,'BELANJA LANGSUNG' nm_rek,SUM(nil_ang) anggaran,SUM(nilai) sd_bulan_ini
						  FROM $tabel($bulan,$anggaran,$lntahunang) $where 
							AND kd_rek IN('521','522','523')
							UNION ALL
							SELECT kd_rek,nm_rek,SUM(nil_ang) anggaran,SUM(nilai) sd_bulan_ini
						  FROM $tabel($bulan,$anggaran,$lntahunang) $where 
							AND kd_rek IN('521','522','523')
							GROUP BY kd_rek,nm_rek
							ORDER BY kd_rek	";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';

		$sql = "SELECT SUBSTRING(kd_kegiatan,17,5) kd_kegiatan,kd_rek,nm_rek,SUM(nil_ang) anggaran,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) $where $where1 
						  AND RIGHT(kd_kegiatan,5) !='00.51' AND RIGHT(kd_kegiatan,5) !='00.04' AND RIGHT(kd_kegiatan,2) !='00'
						  GROUP BY SUBSTRING(kd_kegiatan,17,5),kd_rek,nm_rek
						  ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			switch ($leng) {
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 7:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}




		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_semester_rinci_all_sp2d($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $spj = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}
		if ($spj == 2) {
			$tabel = 'data_sp2d_rinci_all_n';
		} else if ($spj == 3) {
			$tabel = 'data_sp2d_lunas_rinci_all_n';
		} else if ($spj == 4) {
			$tabel = 'data_sp2d_advice_rinci_all_n';
		}
		//$where= "WHERE LEFT(kd_skpd,7)='$kd_skpd'";	
		$where1 = "WHERE LEN(kd_rek)<='$jenis'";
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		/* $cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,1)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,1),'nm_urusan1','ms_urusan1','kd_urusan1')." </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,4)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,4),'nm_urusan','ms_urusan','kd_urusan')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,7)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>
                    </TABLE>";	 */
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran, SUM(sd_bulan_ini) sd_bulan_ini 
					 FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis'
					 GROUP BY kd_rek,nm_rek
					 ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : ($sd_bulan_ini / $nil_ang) * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);


			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';



		$sql = "SELECT SUM(nil_ang) anggaran ,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' 
						  AND LEFT(kd_rek,1)='5' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini - $nil_ang1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"><b>5</b></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$sql = "SELECT kd_rek,nm_rek, SUM(nil_ang) anggaran, SUM(nilai) sd_bulan_ini 
					  FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
					  AND LEFT(kd_rek,1)='5'  
					  GROUP BY kd_rek,nm_rek
					  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);

			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . ' </b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . ' </b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}
		$hasil->free_result();

		$sql = "SELECT SUM(nil_ang) anggaran ,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' 
						  AND LEFT(kd_rek,1)='5' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini - $nil_ang1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$sql = "select sum(case when kd_rek='4' then anggaran else 0 END) as anggaran1,
							sum(case when kd_rek='4' then sd_bulan_ini else 0 END) as sd_bulan_ini1,
							sum(case when kd_rek='5' then anggaran else 0 END) as anggaran2,
							sum(case when kd_rek='5' then sd_bulan_ini else 0 END) as sd_bulan_ini2
							 from (
							SELECT '4' kd_rek, SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)<>'6'
							UNION ALL
							SELECT '5' kd_rek, SUM(nil_ang) anggaran ,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)='5') a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran1 = $row->anggaran1;
			$sd_bulan_ini1 = $row->sd_bulan_ini1;
			$anggaran2 = $row->anggaran2;
			$sd_bulan_ini2 = $row->sd_bulan_ini2;

			$totang_suplus = $anggaran1 - $anggaran2;
			$totreal_suplus = $sd_bulan_ini1 - $sd_bulan_ini2;

			if ($totang_suplus < 0) {
				$totang_suplus1 = $totang_suplus * -1;
				$x = '(';
				$y = ')';
			} else {
				$totang_suplus1 = $totang_suplus;
				$x = '';
				$y = '';
			}

			if ($totreal_suplus < 0) {
				$totreal_suplus1 = $totreal_suplus * -1;
				$m = '(';
				$n = ')';
			} else {
				$totreal_suplus1 = $totreal_suplus;
				$m = '';
				$n = '';
			}



			$sisa_ang = $anggaran1 - $sd_bulan_ini1;
			$sisa_real = $anggaran2 - $sd_bulan_ini2;

			$tot_sisa = $totreal_suplus - $totang_suplus;

			$tot_sisa1 = $tot_sisa < 0 ? $tot_sisa * -1 : $tot_sisa;
			$a = $tot_sisa < 0 ? '(' : '';
			$b = $tot_sisa < 0 ? ')' : '';

			$persen_suplus = empty($totang_suplus) || $totang_suplus == 0 ? 0 : $totreal_suplus / $totang_suplus * 100;
		}

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SURPLUS/(DEFISIT)</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($totang_suplus1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $m . '' . number_format($totreal_suplus1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_suplus, "2", ",", ".") . '</b></td> 
							</tr>';

		$hasil->free_result();

		$sql = "SELECT kd_rek,nm_rek, SUM(nil_ang) anggaran, SUM(nilai) sd_bulan_ini 
					  FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis'
					  AND LEFT(kd_rek,2)='61' 
					  GROUP BY kd_rek,nm_rek
					  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);

			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b> JUMLAH PENERIMAAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';

		$sql = "SELECT kd_rek,nm_rek, SUM(nil_ang) anggaran, SUM(nilai) sd_bulan_ini 
					  FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
					  AND LEFT(kd_rek,3)='622'  
					  GROUP BY kd_rek,nm_rek
					  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);

			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b> JUMLAH PENGELUARAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';

		$sql = "select sum(case when kd_rek='61' then anggaran else 0 END) as anggaran1,
							sum(case when kd_rek='61' then sd_bulan_ini else 0 END) as sd_bulan_ini1,
							sum(case when kd_rek='622' then anggaran else 0 END) as anggaran2,
							sum(case when kd_rek='622' then sd_bulan_ini else 0 END) as sd_bulan_ini2
							 from (
							SELECT '61' kd_rek, SUM(nil_ang) anggaran ,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='3' AND LEFT(kd_rek,2)='61'
							UNION ALL
							SELECT '622' kd_rek, SUM(nil_ang) anggaran ,SUM(nilai) sd_bulan_ini FROM $tabel($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='3' AND LEFT(kd_rek,3)='622') a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran1 = $row->anggaran1;
			$sd_bulan_ini1 = $row->sd_bulan_ini1;
			$anggaran2 = $row->anggaran2;
			$sd_bulan_ini2 = $row->sd_bulan_ini2;

			$totang_netto = $anggaran1 - $anggaran2;

			if ($totang_netto < 0) {
				$totang_netto1 = $totang_netto * -1;
				$x = "(";
				$y = ")";
			} else {
				$totang_netto1 = $totang_netto;
				$x = "";
				$y = "";
			}

			$totreal_netto = $sd_bulan_ini1 - $sd_bulan_ini2;

			if ($totreal_netto < 0) {
				$totreal_netto1 = $totreal_netto * -1;
				$m = "(";
				$n = ")";
			} else {
				$totreal_netto1 = $totreal_netto;
				$m = "";
				$n = "";
			}

			$sisa_ang = $anggaran1 - $sd_bulan_ini1;
			$sisa_real = $anggaran2 - $sd_bulan_ini2;

			$tot_sisa = $sisa_real - $sisa_ang;

			$tot_sisa1_netto = $tot_sisa < 0 ? $tot_sisa * -1 : $tot_sisa;
			$a = $tot_sisa < 0 ? '(' : '';
			$b = $tot_sisa < 0 ? ')' : '';

			$persen_netto = empty($totang_netto) || $totang_netto == 0 ? 0 : $totreal_netto / $totang_netto * 100;
		}

		$ang_silpa = $totang_suplus + $totang_netto;

		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$x = "(";
			$y = ")";
		} else {
			$ang_silpa1 = $ang_silpa;
			$x = "";
			$y = "";
		}

		$real_silpa = $totreal_suplus + $totreal_netto;

		if ($real_silpa < 0) {
			$real_silpa1 = $real_silpa * -1;
			$x = "(";
			$y = ")";
		} else {
			$real_silpa1 = $real_silpa;
			$x = "";
			$y = "";
		}

		$sisa_silpa = $real_silpa - $ang_silpa;

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>PEMBIAYAAN NETTO</b></td> 
							   <td align="right" valign="top"><b>' . $x . ' ' . number_format($totang_netto1, "2", ",", ".") . ' ' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $m . '' . number_format($totreal_netto1, "2", ",", ".") . ' ' . $n . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format(0, "2", ",", ".") . '</b></td> 
							</tr>';



		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SISA LEBIH PEMBIYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</b></td> 
							   <td align="right" valign="top"><b>' . number_format($ang_silpa1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($real_silpa1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sisa_silpa, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sisa_silpa, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format(0, "2", ",", ".") . '</b></td> 
							</tr>';

		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER_ALL_SP2D ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_semester_rinci_all_spj($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $spj = '', $jenis2 = '', $tw = '', $format = '')
	{
		$lntahunang = $this->session->userdata('pcThang');

		if ($jenis2 == 1) {
			$isi = "sd_bulan_ini";
			$pilih = "S/D";
			switch ($bulan) {
				case  1:
					$judul = "JANUARI";
					break;
				case  2:
					$judul = "FEBRUARI";
					break;
				case  3:
					if ($tw == '1') {
						$judul = "TRIWULAN I";
					} else {
						$judul = "MARET";
					}

					break;
				case  4:
					$judul = "APRIL";
					break;
				case  5:
					$judul = "MEI";
					break;
				case  6:
					if ($tw == '1') {
						$judul = "TRIWULAN II";
					} else {
						$judul = "SEMESTER PERTAMA";
					}
					break;
				case  7:
					$judul = "JULI";
					break;
				case  8:
					$judul = "AGUSTUS";
					break;
				case  9:
					if ($tw == '1') {
						$judul = "TRIWULAN III";
					} else {
						$judul = "SEPTEMBER";
					}
					break;
				case  10:
					$judul = "OKTOBER";
					break;
				case  11:
					$judul = "NOVEMBER";
					break;
				case  12:
					if ($tw == '1') {
						$judul = "TRIWULAN IV";
					} else {
						$judul = "SEMESTER KEDUA";
					}
					break;
			}
		} else {
			$isi = "bulan_ini";
			$pilih = "BULAN";
			switch ($bulan) {
				case  1:
					$judul = "JANUARI";
					break;
				case  2:
					$judul = "FEBRUARI";
					break;
				case  3:
					$judul = "MARET";
					break;
				case  4:
					$judul = "APRIL";
					break;
				case  5:
					$judul = "MEI";
					break;
				case  6:
					$judul = "JUNI";
					break;
				case  7:
					$judul = "JULI";
					break;
				case  8:
					$judul = "AGUSTUS";
					break;
				case  9:
					$judul = "SEPTEMBER";
					break;
				case  10:
					$judul = "OKTOBER";
					break;
				case  11:
					$judul = "NOVEMBER";
					break;
				case  12:
					$judul = "DESEMBER";
					break;
			}
		}

		if ($anggaran == 11) {
			$sumber_data1 = "_sempurna1";
		} else if ($anggaran == 12) {
			$sumber_data1 = "_sempurna2";
		} else if ($anggaran == 13) {
			$sumber_data1 = "_sempurna3";
		} else if ($anggaran == 14) {
			$sumber_data1 = "_sempurna4";
		} else {
			$sumber_data1 = "";
		}



		if ($tw == '1') {
			$triw = "_tw";
		} else {
			$triw = "";
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		//parameter konversi
		if ($format == 1) {
			$conv = "";
		} else {
			$conv = "_conv1";
		}

		$where = "";
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$where1 = "WHERE LEN(kd_rek)<='$jenis'";

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';

		$bulan2 = 12 - $bulan;
		$angkabulan2 = $this->tukd_model->terbilang_angka($bulan2);

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;text-transform:uppercase;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN <BR> PROGNOSIS $bulan2 ($angkabulan2) BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		/* $cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,1)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,1),'nm_urusan1','ms_urusan1','kd_urusan1')." </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,4)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,4),'nm_urusan','ms_urusan','kd_urusan')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,7)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>
					
                    </TABLE>"; */
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>$pilih<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran, SUM($isi) sd_bulan_ini 
					 FROM realisasi_jurnal_pend_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
					 GROUP BY kd_rek,nm_rek
					 ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : ($sd_bulan_ini / $nil_ang) * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);



			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM($isi) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) $where WHERE LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM($isi) sd_bulan_ini FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) $where WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)<>'6' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $nil_ang1 - $sd_bulan_ini1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa11 < 0 ? '(' : '';
			$b = $sisa11 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"><b>5</b></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';



		if ($format == 1) {
			$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM($isi) sd_bulan_ini
					      FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
						  AND LEFT(kd_rek,1)<>'6'
						  GROUP BY kd_rek,nm_rek
						  ORDER BY kd_rek";
		} else {
			$sql = "SELECT 
				    case when left(kd_rek,5)='52223' then '51411' 
						 when left(kd_rek,5)='52227' then '51412' 
else kd_rek end kd_rek1,  kd_rek,nm_rek,SUM(anggaran) anggaran,SUM($isi) sd_bulan_ini FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw$conv($bulan,$anggaran,$lntahunang) 
WHERE LEN(kd_rek)<='$jenis' AND LEFT(kd_rek,1)<>'6' GROUP BY kd_rek,nm_rek 
ORDER BY kd_rek1, kd_rek";
		}
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			//echo (strlen($kd_rek));
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			//if($kd_rek==)
			$leng = strlen($kd_rek);
			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}


		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';

		$sql = "SELECT sum(case when kd_rek='4' then anggaran else 0 END) as anggaran1,
							sum(case when kd_rek='4' then sd_bulan_ini else 0 END) as sd_bulan_ini1,
							sum(case when kd_rek='5' then anggaran else 0 END) as anggaran2,
							sum(case when kd_rek='5' then sd_bulan_ini else 0 END) as sd_bulan_ini2
							 from (
							SELECT '4' kd_rek, SUM(anggaran) anggaran ,SUM($isi) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)<>'6'
							UNION ALL
							SELECT '5' kd_rek, SUM(anggaran) anggaran ,SUM($isi) sd_bulan_ini FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)<>'6') a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran1 = $row->anggaran1;
			$sd_bulan_ini1 = $row->sd_bulan_ini1;
			$anggaran2 = $row->anggaran2;
			$sd_bulan_ini2 = $row->sd_bulan_ini2;

			$totang_suplus = $anggaran1 - $anggaran2;
			$totreal_suplus = $sd_bulan_ini1 - $sd_bulan_ini2;

			if ($totang_suplus < 0) {
				$totang_suplus1 = $totang_suplus * -1;
				$x = '(';
				$y = ')';
			} else {
				$totang_suplus1 = $totang_suplus;
				$x = '';
				$y = '';
			}

			if ($totreal_suplus < 0) {
				$totreal_suplus1 = $totreal_suplus * -1;
				$o = '(';
				$p = ')';
			} else {
				$totreal_suplus1 = $totreal_suplus;
				$o = '';
				$p = '';
			}

			$sisa_ang = $sd_bulan_ini1 - $anggaran1;
			$sisa_real = $sd_bulan_ini2 - $anggaran2;

			$tot_sisa = $totreal_suplus - $totang_suplus;

			if ($tot_sisa < 0) {
				$tot_sisa1 = $tot_sisa * -1;
				$a = '(';
				$b = ')';
			} else {
				$tot_sisa1 = $tot_sisa;
				$a = '';
				$b = '';
			}
			$persen_suplus = empty($totang_suplus) || $totang_suplus == 0 ? 0 : $totreal_suplus / $totang_suplus * 100;
			if ($persen_suplus < 0) {
				$persen_suplus1 = $persen_suplus * -1;
				$m = '(';
				$n = ')';
			} else {
				$persen_suplus1 = $persen_suplus;
				$m = '';
				$n = '';
			}

			$sisasurplus = $totang_suplus - $totreal_suplus;
			if ($sisasurplus < 0) {
				$sisasurplus1 = $sisasurplus * -1;
				$q = '(';
				$r = ')';
			} else {
				$sisasurplus1 = $sisasurplus;
				$q = '';
				$r = '';
			}
		}



		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SURPLUS/(DEFISIT)</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($totang_suplus1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $o . '' . number_format($totreal_suplus1, "2", ",", ".") . '' . $p . '</b></td>
							   <td align="right" valign="top"><b>' . $q . '' . number_format($sisasurplus1, "2", ",", ".") . '' . $r . '</b></td> 							   
							   <td align="right" valign="top"><b>' . $m . ' ' . number_format($sisasurplus1, "2", ",", ".") . ' ' . $n . '</b></td> 
							   <td align="right" valign="top"><b>' . $m . '' . number_format($persen_suplus1, "2", ",", ".") . '' . $n . '</b></td> 
							</tr>';

		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM($isi) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
						  AND LEFT(kd_rek,2)='61'
						  GROUP BY kd_rek,nm_rek
						  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b> JUMLAH PENERIMAAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';

		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM($isi) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
						  AND LEFT(kd_rek,2)='62'
						  GROUP BY kd_rek,nm_rek
						  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}

		$sql = "SELECT SUM(anggaran) anggaran,SUM($isi) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='2'
						  AND LEFT(kd_rek,2)='62'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran62 = $row->anggaran;
			$sd_bulan_ini62 = $row->sd_bulan_ini;

			$sisa_ang = $sd_bulan_ini62 - $anggaran62;

			$persen = empty($anggaran62) || $anggaran62 == 0 ? 0 : $sd_bulan_ini62 / $anggaran62 * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b> JUMLAH PENGELUARAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran62, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini62, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sisa_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sisa_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}

		$sql = "SELECT sum(case when kd_rek='61' then anggaran else 0 END) as anggaran1,
							sum(case when kd_rek='61' then sd_bulan_ini else 0 END) as sd_bulan_ini1,
							sum(case when kd_rek='622' then anggaran else 0 END) as anggaran2,
							sum(case when kd_rek='622' then sd_bulan_ini else 0 END) as sd_bulan_ini2
							 from (
							SELECT '61' kd_rek, SUM(anggaran) anggaran ,SUM($isi) sd_bulan_ini FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='3' AND LEFT(kd_rek,2)='61'
							UNION ALL
							SELECT '622' kd_rek, SUM(anggaran) anggaran ,SUM($isi) sd_bulan_ini FROM realisasi_jurnal_rinci_all_n$sumber_data1$triw($bulan,$anggaran,$lntahunang) WHERE LEN(kd_rek)='3' AND LEFT(kd_rek,3)='622') a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran1 = $row->anggaran1;
			$sd_bulan_ini1 = $row->sd_bulan_ini1;
			$anggaran2 = $row->anggaran2;
			$sd_bulan_ini2 = $row->sd_bulan_ini2;

			$totang_netto = $anggaran1 - $anggaran2;

			if ($totang_netto < 0) {
				$totang_netto1 = $totang_netto * -1;
				$f = '(';
				$t = ')';
			} else {
				$totang_netto1 = $totang_netto;
				$f = '';
				$t = '';
			}

			$totreal_netto = $sd_bulan_ini1 - $sd_bulan_ini2;

			$sisa_ang = $sd_bulan_ini1 - $anggaran1;
			$sisa_real = $sd_bulan_ini2 - $anggaran2;

			$tot_sisa = $sisa_real - $sisa_ang;

			$tot_sisa1_netto = $tot_sisa < 0 ? $tot_sisa * -1 : $tot_sisa;
			$a = $tot_sisa < 0 ? '(' : '';
			$b = $tot_sisa < 0 ? ')' : '';

			$persen_netto = empty($totang_netto) || $totang_netto == 0 ? 0 : $totreal_netto / $totang_netto * 100;

			$sisasilpa  = ($totang_suplus + $totang_netto) - ($totreal_suplus + $totreal_netto);
			if ($sisasilpa < 0) {
				$sisasilpa1 = $sisasilpa * -1;
				$m = '(';
				$n = ')';
			} else {
				$sisasilpa1 = $sisasilpa;
				$m = '';
				$n = '';
			}
		}




		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>PEMBIAYAAN NETTO</b></td> 
							   <td align="right" valign="top"><b>' . $f . ' ' . number_format($totang_netto1, "2", ",", ".") . ' ' . $t . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($totreal_netto, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';



		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SISA LEBIH PEMBIYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</b></td> 
							   <td align="right" valign="top"><b>' . number_format($totang_suplus + $totang_netto, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($totreal_suplus + $totreal_netto, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $m . ' ' . number_format($sisasilpa1, "2", ",", ".") . ' ' . $n . '</b></td> 
							   <td align="right" valign="top"><b>' . $m . ' ' . number_format($sisasilpa1, "2", ",", ".") . ' ' . $n . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format(0, "2", ",", ".") . '</b></td> 
							</tr>';


		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul2 = 'LAPORAN ' . $judul;
		switch ($ctk) {
			case 0;
				echo ("<title>$judul2</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lamp_semester_rinci_all_spj_periode($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $spj = '', $bulan1 = '')
	{
		$lntahunang = $this->session->userdata('pcThang');

		switch ($bulan1) {
			case  1:
				$judul1 = "JANUARI";
				break;
			case  2:
				$judul1 = "FEBRUARI";
				break;
			case  3:
				$judul1 = "TRIWULAN I";
				break;
			case  4:
				$judul1 = "APRIL";
				break;
			case  5:
				$judul1 = "MEI";
				break;
			case  6:
				$judul1 = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul1 = "JULI";
				break;
			case  8:
				$judul1 = "AGUSTUS";
				break;
			case  9:
				$judul1 = "TRIWULAN III";
				break;
			case  10:
				$judul1 = "OKTOBER";
				break;
			case  11:
				$judul1 = "NOVEMBER";
				break;
			case  12:
				$judul1 = "SEMESTER KEDUA";
				break;
		}

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($bulan == 12) {
			$sumber_data = "_at";
		} else {
			$sumber_data = "";
		}

		$where = "";
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$where1 = "WHERE LEN(kd_rek)<='$jenis'";
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$angkabulan2 = $this->tukd_model->terbilang_angka($bulan2);
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;text-transform:uppercase;border-top:hidden\"><b>LAPORAN REALISASI $judul1 SAMPAI DENGAN $judul APBD DAN <BR> PROGNOSIS $bulan2 ($angkabulan2) BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		/* $cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,1)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,1),'nm_urusan1','ms_urusan1','kd_urusan1')." </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,4)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,4),'nm_urusan','ms_urusan','kd_urusan')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,7)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>
					
                    </TABLE>"; */
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran, SUM(sd_bulan_ini) sd_bulan_ini 
					 FROM realisasi_jurnal_pend_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
					 GROUP BY kd_rek,nm_rek
					 ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : ($sd_bulan_ini / $nil_ang) * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);



			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) $where WHERE LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) $where WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)<>'6' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini1 - $nil_ang1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa11 < 0 ? '(' : '';
			$b = $sisa11 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"><b>5</b></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
						  AND LEFT(kd_rek,1)<>'6'
						  GROUP BY kd_rek,nm_rek
						  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}


		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';

		$sql = "select sum(case when kd_rek='4' then anggaran else 0 END) as anggaran1,
							sum(case when kd_rek='4' then sd_bulan_ini else 0 END) as sd_bulan_ini1,
							sum(case when kd_rek='5' then anggaran else 0 END) as anggaran2,
							sum(case when kd_rek='5' then sd_bulan_ini else 0 END) as sd_bulan_ini2
							 from (
							SELECT '4' kd_rek, SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)<>'6'
							UNION ALL
							SELECT '5' kd_rek, SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)='$jenis' AND LEFT(kd_rek,1)<>'6') a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran1 = $row->anggaran1;
			$sd_bulan_ini1 = $row->sd_bulan_ini1;
			$anggaran2 = $row->anggaran2;
			$sd_bulan_ini2 = $row->sd_bulan_ini2;

			$totang_suplus = $anggaran1 - $anggaran2;
			$totreal_suplus = $sd_bulan_ini1 - $sd_bulan_ini2;

			if ($totang_suplus < 0) {
				$totang_suplus1 = $totang_suplus * -1;
				$x = '(';
				$y = ')';
			} else {
				$totang_suplus1 = $totang_suplus;
				$x = '';
				$y = '';
			}

			if ($totreal_suplus < 0) {
				$totreal_suplus1 = $totreal_suplus * -1;
				$m = '(';
				$n = ')';
			} else {
				$totreal_suplus1 = $totreal_suplus;
				$m = '';
				$n = '';
			}

			$sisa_ang = $sd_bulan_ini1 - $anggaran1;
			$sisa_real = $sd_bulan_ini2 - $anggaran2;

			$tot_sisa = $totreal_suplus - $totang_suplus;

			if ($tot_sisa < 0) {
				$tot_sisa1 = $tot_sisa * -1;
				$a = '(';
				$b = ')';
			} else {
				$tot_sisa1 = $tot_sisa;
				$a = '';
				$b = '';
			}
			$persen_suplus = empty($totang_suplus) || $totang_suplus == 0 ? 0 : $totreal_suplus / $totang_suplus * 100;
		}



		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SURPLUS/(DEFISIT)</b></td> 
							   <td align="right" valign="top"><b>' . $x . '' . number_format($totang_suplus1, "2", ",", ".") . '' . $y . '</b></td> 
							   <td align="right" valign="top"><b>' . $m . '' . number_format($totreal_suplus1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_suplus, "2", ",", ".") . '</b></td> 
							</tr>';

		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
						  AND LEFT(kd_rek,2)='61'
						  GROUP BY kd_rek,nm_rek
						  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b> JUMLAH PENERIMAAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';

		$sql = "SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)<='$jenis' 
						  AND LEFT(kd_rek,2)='62'
						  GROUP BY kd_rek,nm_rek
						  ORDER BY kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			//$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			if ($jenis == '3' && $leng <= '2') {

				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '5' && $leng <= '3') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else if ($jenis == '7' && $leng <= '5') {
				$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</b></td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
			}
		}

		$sql = "SELECT SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)='2'
						  AND LEFT(kd_rek,2)='62'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran62 = $row->anggaran;
			$sd_bulan_ini62 = $row->sd_bulan_ini;

			$sisa_ang = $sd_bulan_ini62 - $anggaran62;

			$persen = empty($anggaran62) || $anggaran62 == 0 ? 0 : $sd_bulan_ini62 / $anggaran62 * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b> JUMLAH PENGELUARAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($anggaran62, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini62, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sisa_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sisa_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}

		$sql = "select sum(case when kd_rek='61' then anggaran else 0 END) as anggaran1,
							sum(case when kd_rek='61' then sd_bulan_ini else 0 END) as sd_bulan_ini1,
							sum(case when kd_rek='622' then anggaran else 0 END) as anggaran2,
							sum(case when kd_rek='622' then sd_bulan_ini else 0 END) as sd_bulan_ini2
							 from (
							SELECT '61' kd_rek, SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)='3' AND LEFT(kd_rek,2)='61'
							UNION ALL
							SELECT '622' kd_rek, SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci_all_periode_n$sumber_data($bulan,$bulan1,$anggaran,$lntahunang) WHERE LEN(kd_rek)='3' AND LEFT(kd_rek,3)='622') a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$anggaran1 = $row->anggaran1;
			$sd_bulan_ini1 = $row->sd_bulan_ini1;
			$anggaran2 = $row->anggaran2;
			$sd_bulan_ini2 = $row->sd_bulan_ini2;

			$totang_netto = $anggaran1 - $anggaran2;

			if ($totang_netto < 0) {
				$totang_netto1 = $totang_netto * -1;
			} else {
				$totang_netto1 = $totang_netto;
			}

			$totreal_netto = $sd_bulan_ini1 - $sd_bulan_ini2;

			$sisa_ang = $sd_bulan_ini1 - $anggaran1;
			$sisa_real = $sd_bulan_ini2 - $anggaran2;

			$tot_sisa = $sisa_real - $sisa_ang;

			$tot_sisa1_netto = $tot_sisa < 0 ? $tot_sisa * -1 : $tot_sisa;
			$a = $tot_sisa < 0 ? '(' : '';
			$b = $tot_sisa < 0 ? ')' : '';

			$persen_netto = empty($totang_netto) || $totang_netto == 0 ? 0 : $totreal_netto / $totang_netto * 100;
		}



		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>PEMBIAYAAN NETTO</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($totang_netto1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($totreal_netto, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';



		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SISA LEBIH PEMBIYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</b></td> 
							   <td align="right" valign="top"><b>' . number_format($totang_suplus + $totang_netto, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($totreal_suplus + $totreal_netto, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1 + $tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($tot_sisa1 + $tot_sisa1_netto, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format(0, "2", ",", ".") . '</b></td> 
							</tr>';


		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function load_ttd_pa()
	{
		$lccr = '';
		$lccr = $this->input->post('q');
		$sql = "SELECT * FROM ms_ttd WHERE kode='PA' and kd_skpd= '5.02.0.00.0.00.02.0000' AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";

		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'nip' => $resulte['nip'],
				'nama' => $resulte['nama']
			);
			$ii++;
		}

		echo json_encode($result);
		$query1->free_result();
	}

	// =============================================== Laporan Triwulan
	function cetak_lamp_semester_sap_spj($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		if ($kd_skpd == '-') {
			$where = "";
			$panjang = 0;
		} else {
			$panjang = strlen($kd_skpd);
			$where = "AND LEFT(kd_skpd,$panjang)='$kd_skpd'";
		}


		$sclient = $this->akuntansi_model->get_sclient();
		$tanggal = $tglttd == '-' ? '' : $sclient->daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<tr>
						<TD  width="60%" valign="top" align="right" ></TD>
						<TD width="40%"  align="left" ></TD>
					</tr>
					<tr>
				</TABLE>
				<br/>';
		$bulan2 = 12 - $bulan;


		$cRet .= "	<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
							<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        		<img src=\"" . base_url() . "/image/logo_sanggau.png\"  width=\"75\" height=\"100\" />
                        	</td>
							<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
								<strong>" . $sclient->kab_kota . " </strong>
							</td>
						</tr>
                    	<tr>
							<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
								<b>LAPORAN REALISASI $judul APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b>
							</td>
						</tr>
						<tr>
							<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
						</TABLE>";

		// organisasi
		if ($panjang == 17) {
			$cRet .= "
					<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
							<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
								&nbsp;&nbsp; Bidang Pemerintahan 
							</td>
							<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\">
								: " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "
							</td>
						</tr>
						<tr>
							<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
								&nbsp;&nbsp; Unit Organisasi 
							</td>
							<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\">
								: " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "
							</td>
						</tr>
                    </TABLE>";
		}

		//skpd
		if ($panjang == 22) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">>
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
							&nbsp;&nbsp; Bidang Pemerintahan 
						</td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\">
						 	: " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 4), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "
						</td>
					</tr>
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
							&nbsp;&nbsp; Unit Organisasi 
						</td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> 
							: " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 7), 'nm_org', 'ms_organisasi', 'kd_org') . "
						</td>
					</tr>
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">
							&nbsp;&nbsp; Sub Unit Organisasi 
						</td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> 
							: " . $this->left($kd_skpd, 10) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 10), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "
						</td>
					</tr>
                    </TABLE>";
		}


		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nilai_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek in ('5','6') THEN (nilai_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek in ('5','6') THEN (real_spj) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek6,1) as kd_rek, SUM(nilai_ang) as nilai_ang, SUM(real_spj) as real_spj 
					FROM Data_realisasi_keg4($bulan,$anggaran, $lntahunang) WHERE LEFT(kd_rek6,1) IN ('4','5','6') $where
					GROUP BY LEFT(kd_rek6,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();

		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='71' THEN (nilai_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='72' THEN (nilai_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='71' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='72' THEN (real_spj) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek6,2) as kd_rek, SUM(nilai_ang) as nilai_ang, SUM(real_spj) as real_spj  
					FROM Data_realisasi_keg4($bulan,$anggaran, $lntahunang) WHERE LEFT(kd_rek6,2) IN ('71','72') $where
					GROUP BY LEFT(kd_rek6,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, spasi FROM map_lra_sap ORDER BY seq";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$spasi = $row->spasi;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}

			$sql = "SELECT SUM(nilai_ang) as nil_ang, SUM(real_spj) as nilai 
					FROM Data_realisasi_keg4($bulan,$anggaran, $lntahunang) 
					WHERE (LEFT(kd_rek6,1) IN ($kode1) or LEFT(kd_rek6,2) IN ($kode2) or LEFT(kd_rek6,4) IN ($kode3) or LEFT(kd_rek6,6) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($persen_surplus, "2", ",", ".") . '' . $f . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($persen_netto, "2", ",", ".") . '' . $l . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($persen_silpa, "2", ",", ".") . '' . $r . '</b></td> 
							</tr>';
					break;
			}
		}
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_SAP ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_lamp_semester_permen_spj($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}
		if ($kd_skpd == '-') {
			$where = "";
			$panjang = 0;
		} else {
			$panjang = strlen($kd_skpd);
			$where = "AND LEFT(kd_skpd,$panjang)='$kd_skpd'";
		}


		$sclient = $this->akuntansi_model->get_sclient();

		$tanggal = $tglttd == '-' ? '' : $sclient->daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >   </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
						<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        	<img src=\"" . base_url() . "/image/logo_sanggau.png\"  width=\"75\" height=\"100\" />
                    	</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
							<strong>" . $sclient->kab_kota . " </strong>
						</td>
					</tr>
                    <tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
							<b>LAPORAN REALISASI $judul APBD <BR> DAN PROGNOSIS $bulan2 BULAN BERIKUTNYA </b>
						</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>TAHUN ANGGARAN $lntahunang</b>
						</td>
					</tr>
					</TABLE>";
		if ($panjang == 17) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
							&nbsp;&nbsp; Bidang Pemerintahan 
						</td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> 
							: " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "
						</td>
					</tr>
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
							&nbsp;&nbsp; Unit Organisasi </td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> 
							: " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "
						</td>
					</tr>
                    </TABLE>";
		}
		if ($panjang == 22) {
			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
							&nbsp;&nbsp; Bidang Pemerintahan 
						</td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> 
							: " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "
						</td>
					</tr>
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> 
							&nbsp;&nbsp; Unit Organisasi 
						</td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> 
							: " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "
						</td>
					</tr>
					<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">
							&nbsp;&nbsp; Sub Unit Organisasi 
						</td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> 
							: " . $this->left($kd_skpd, 22) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 10), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "
						</td>
					</tr>
                    </TABLE>";
		}
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (real_spj) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_ang,1) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM Data_realisasi_keg4($bulan,$anggaran, $lntahunang) WHERE LEFT(kd_ang,1) IN ('4','5') $where
					GROUP BY LEFT(kd_ang,1)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_ang,2) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM Data_realisasi_keg4($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_ang,2) IN ('61','62') $where
					GROUP BY LEFT(kd_ang,2)) a;
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, spasi FROM map_lra_permen ORDER BY seq
					";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$seq = $row->seq;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$spasi = $row->spasi;

			if ($kode1 == '') {
				$kode1 = "'X'";
			}
			if ($kode2 == '') {
				$kode2 = "'XX'";
			}
			if ($kode3 == '') {
				$kode3 = "'XXX'";
			}
			if ($kode4 == '') {
				$kode4 = "'XXXXX'";
			}
			$sql = "SELECT SUM(nilai_ang) as nil_ang, SUM(real_spj) as nilai 
					FROM Data_realisasi_keg4($bulan,$anggaran, $lntahunang) 
					WHERE (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,3) IN ($kode3) or LEFT(kd_ang,5) IN($kode4)) $where
					";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
                                <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . ' </b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . ' </b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	// =============================================== End Laporan Triwulan

	function load_ttd_perda()
	{
		$lccr = '';
		$lccr = $this->input->post('q');
		$sql = "SELECT nip,nama,jabatan,pangkat FROM ms_ttd WHERE (kode ='agr' or kode='wk' or kode='PPKD' or kode='BUP' or kode='SETDA' or kode='BUPATI') order by nip";

		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'nip' => $resulte['nip'],
				'nama' => $resulte['nama'],
				'jabatan' => $resulte['jabatan'],
				'pangkat' => $resulte['pangkat']
			);
			$ii++;
		}

		echo json_encode($result);
		$query1->free_result();
	}

	// =============================================== Laporan Triwulan Rinci

	function cetak_lamp_semester_rinci_org_spj($bulan = '', $ctk = '1', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $spj = '',$format = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$id = $this->session->userdata('kdskpd');
		$kd_skpd     = $this->session->userdata('kdskpd');
		$lcskpd = substr($kd_skpd,0,17); 
		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER PERTAMA";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER KEDUA";
				break;
		}

		$sql = $this->db->query("SELECT top 1 daerah,kab_kota from sclient");
		$row = $sql->row();
		$daerah = $row->daerah;
		$kab_kota = $row->kab_kota;
		
		$where = "WHERE LEFT(kd_skpd,17)='$lcskpd'";
		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}

		$where1 = "AND LEN(kd_rek)<='$jenis'";
		// if ($jenis == '5') {
		// 	$wherejns = "AND kd_rek not like '%.%'";
		// } else {
		// 	$wherejns = "";
		// }
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
		$bulan2 = 12 - $bulan;
		$angkabulan2 = $this->tukd_model->terbilang_angka($bulan2);
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab_kota</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;text-transform:uppercase;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN <BR> PROGNOSIS $bulan2 ($angkabulan2) BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . "</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					
                    </TABLE>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI <br>S/D<br> $judul</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>
                    <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PROGNOSIS</b></td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";


		$sql = "SELECT (case when LEN(kd_kegiatan)>12 then RIGHT(kd_kegiatan,5) else RIGHT(kd_kegiatan,2) end) kd_kegiatan,
					kd_rek,nm_rek,SUM(anggaran) anggaran, SUM(sd_bulan_ini) sd_bulan_ini 
					FROM realisasi_jurnal_pend($bulan,$anggaran) $where $where1  
					GROUP BY kd_kegiatan,kd_rek,nm_rek
					ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : ($sd_bulan_ini / $nil_ang) * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '  </td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . ' .</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend($bulan,$anggaran) $where AND LEN(kd_rek)='$jenis' ";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';


		$sql = "SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where AND LEN(kd_rek)='$jenis'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $nil_ang1 - $sd_bulan_ini1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa11 < 0 ? '(' : '';
			$b = $sisa11 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();


		$sql = "SELECT kd_kegiatan,kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where $where1
						  AND SUBSTRING(kd_kegiatan,6,2)='00'
						  GROUP BY kd_kegiatan,kd_rek,nm_rek
						  ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}



		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
		$hasil->free_result();

		$sql = "SELECT '1' kd_rek,'BELANJA LANGSUNG' nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
						  FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where 
							AND kd_rek IN ('5201','5202','5203')
							UNION ALL
							SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
							FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where 
							AND kd_rek IN ('5201','5202','5203')
							GROUP BY kd_rek,nm_rek
							ORDER BY kd_rek	";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= '<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';

		$sql = "SELECT  kd_kegiatan,kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci($bulan,$anggaran, $lntahunang) $where $where1
						  AND SUBSTRING(kd_kegiatan,6,2)!='00'
						  GROUP BY kd_kegiatan,kd_rek,nm_rek
						  ORDER BY kd_kegiatan,kd_rek";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $nil_ang - $sd_bulan_ini;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kd_kegiatan . '.' . $this->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
			}
		}

		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							</tr>';
		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
		$data['prev'] = $cRet;
		$judul = 'LAPORAN_SEMESTER ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	// =============================================== End Laporan Triwulan Rinci
}
