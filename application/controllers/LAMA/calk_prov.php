<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Calk_prov extends CI_Controller {

	function __contruct(){	
		parent::__construct();
  
	}

	function get_status($tgl,$skpd){
        $n_status = '';
        $tanggal = $tgl;
        $sql = "select case when '$tanggal'>=tgl_dpa_ubah then 'nilai_ubah' 
                    when '$tanggal'>=tgl_dpa_sempurna then 'nilai_sempurna' 
                    when '$tanggal'<=tgl_dpa 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd' ";
        
        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;                         
    }

    function rp_minus($nilai){
        if($nilai<0){
            $nilai = $nilai * (-1);
            $nilai = '('.number_format($nilai,"2",",",".").')';    
        }else{
            $nilai = number_format($nilai,"2",",","."); 
        }
        
        return $nilai;
    }  	

    function persen($nilai,$nilai2){
            if($nilai != 0){
                $persen = $this->rp_minus(($nilai2/$nilai)*100);
            }else{
                if($nilai2 == 0){
                    $persen = $this->rp_minus(0);
                }else{
                    $persen = $this->rp_minus(100);
                }
            } 
          return $persen;  
	 }
	
	function lampiran_calk_prov(){
        $data['page_title']= 'LAMPIRAN CALK';
        $this->template->set('title', 'LAMPIRAN CALK');   
        $this->template->load('template','calk/lampiran_calk_prov',$data) ;	
	}
	
	function cetak_babv($dcetak='',$ttd='',$kd_skpd='',$jnsctk=''){
        $spasi = "line-height: 1.5em;";
		$thn = $this->session->userdata('pcThang');
        $cthnsbl = $thn-1;
		
		$ang = 2;
		
		$sumber_data= "_at";
		$trdju = "trdju_calk";
        $trhju = "trhju_calk";
		
		
		/* $sqlanggaran1="select case when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan>=month(tgl_dpa_ubah) then 2 
					   when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan>=month(tgl_dpa_sempurna) and $cbulan<month(tgl_dpa_ubah) then 1
					   when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan<month(tgl_dpa_sempurna) then 0
					   when statu=1 and status_sempurna=1 and status_ubah=0 and $cbulan>=month(tgl_dpa_sempurna) then 1 
					   when statu=1 and status_sempurna=1 and status_ubah=0 and $cbulan<month(tgl_dpa_sempurna) then 0
					   when statu=1 and status_sempurna=0 and status_ubah=0 and $cbulan>=month(tgl_dpa) then 0
					   else 1 end as anggaran from trhrka where kd_skpd='$kd_skpd'";
		
		$sqlanggaran=$this->db->query($sqlanggaran1);
		foreach ($sqlanggaran->result() as $rowttd)
			{
				$anggaran=$rowttd->anggaran;
			}
		
		$ang = $anggaran; */
		
		/* if($jnsctk==1){
				$bg_color = 'bgcolor="Yellow"';
				$tombol_babiv = "<form name=\"patient\" action=\"/simakdaskpd_2017/calk/calk_babiv\" methode=\"get\" target=\"blank\">
									<input type=\"submit\" value=\"Edit \">&nbsp;&nbsp;
									<input type=\"button\" value=\"Refresh\" onClick=\"window.location.reload()\">
							     </form>";
			}else{
				$bg_color = "";
				$tombol_babiv = "";
				
			}	 */
			
			
			$cRet ='<TABLE style="border-collapse:collapse;'.$spasi.'" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
						<TD align="center" ><b>BAB V<br> PENJELASAN POS - POS LAPORAN KEUANGAN TAHUN ANGGARAN  '.$thn.'</TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE style="border-collapse:collapse;'.$spasi.'" width="100%" border="0" cellspacing="0" cellpadding="0" align=center> 
						<TR>
							<TD align="justify">Penyajian laporan keuangan Pemerintah Provinsi Kalimantan Barat  Tahun Anggaran '.$thn.' mengacu pada Peraturan Pemerintah Nomor 24 Tahun 2005 yang telah diubah dengan Peraturan Pemerintah Nomor 71 Tahun 2010 tentang Standar Akuntansi Pemerintahan, Peraturan Pemerintah Nomor 58 Tahun  2005 tentang Pengelolaan Keuangan Daerah dan Permendagri Nomor 13 Tahun 2006 tentang Pedoman Pengelolaan Keuangan Daerah sebagaimana telah diubah beberapa kali dan terakhir dengan Peraturan Menteri Dalam Negeri Nomor 21 Tahun 2011, Permendagri Nomor 64 Tahun 2013  tentang Penerapan Standar Akuntansi Pemerintahan Berbasis Akrual.  Sedangkan tehnik penyusunan laporan keuangan sepenuhnya melalui proses konsolidasi laporan keuangan dari laporan keuangan seluruh SKPD dan laporan keuangan PPKD menjadi laporan keuangan Pemerintah Provinsi Kalimantan Barat. Penjelasan pos-pos laporan keuangan dalam Catatan atas Laporan Keuangan (CaLK) sepenuhnya dapat disajikan mengacu pada Standar Akuntansi Pemerintahan (SAP).
							</TD>
						</TR>
						<TR><TD>&nbsp;</TD></TR></TABLE>';
			
			$cRet .='<TABLE style="border-collapse:collapse;'.$spasi.'" width="100%" border="0" cellspacing="0" cellpadding="0" align=center> 
						<TR>
							<TD align="left" width="5%"><b>5.1</b></TD>
							<TD align="left" width="95%" colspan="2"><b>Penjelasan atas Laporan Realisasi Anggaran</b></TD>
						</TR>
						<TR>
							<TD align="left" width="5%">&nbsp;</TD>
							<TD align="left" width="3%">&nbsp;</TD>
							<TD align="left" width="87%"><b>PENDAPATAN - LRA</b></TD>
						</TR>
						<TR>
							<TD align="left" width="5%">&nbsp;</TD>
							<TD align="left" width="3%">&nbsp;</TD>
							<TD align="left" width="87%">Pendapatan Daerah adalah hak Pemerintah Daerah yang diakui sebagai penambah nilai kekayaan bersih dalam periode yang bersangkutan. </TD>
						</TR>
						<TR>
							<TD align="left" width="5%">&nbsp;</TD>
							<TD align="left" width="3%">&nbsp;</TD>
							<TD align="justify" width="87%">Pada hakekatnya setiap penempatan beban kepada masyarakat termasuk dalam hal ini, perpajakan sebagai salah satu perwujudan kewajiban kenegaraan, harus ditetapkan dengan undang-undang. Dengan demikian, hal tersebut berlaku pula dalam hal pemungutan pajak daerah dan retribusi daerah. Keduanya harus didasarkan pada aturan hukum yang jelas. Sebagai sebuah sistem, kebijakan perpajakan yang pada dasarnya merupakan beban masyarakat selalu perlu dijaga agar kebijakan tersebut dapat memberikan beban yang adil. Sejalan dengan sistem perpajakan nasional, pembinaan pajak daerah dilakukan secara terpadu dengan pajak nasional. Pembinaan ini perlu dilakukan secara terus menerus, terutama mengenai objek dan tarif pajak, sehingga antara pajak pusat dan pajak daerah saling melengkapi. Oleh karena itu, agar dapat dipahami sebuah kerangka sistem perpajakan dan retribusi daerah, Undang-Undang Nomor 28 tahun 2009 tentang Pajak Daerah dan Retribusi Daerah telah memberikan peran dan kewenangan yang besar kepada daerah dalam hal pemungutan pajak dan retribusi daerah. Hal itu dapat dilihat seperti kewenangan dalam bidang perpajakan, yaitu pemberian kewenangan dalam memungut pendapatan daerah yang telah memberikan sumbangan yang cukup berarti bagi pembiayaan daerah.  Pada tahun anggaran '.$thn.' terdapat beberapa jenis pendapatan yang mencapai target, bahkan juga ada yang tidak mencapai target yang telah ditetapkan. Hal ini tentu akan menjadi bahan evaluasi pada tahun anggaran yang akan datang. Pendapatan daerah Provinsi Kalimantan Barat pada Tahun Anggaran '.$thn.' dapat dilihat pada penjelasan-penjelasan dibawah ini.</TD>
						</TR>
						<TR>
							<TD align="left" width="5%">&nbsp;</TD>
							<TD align="left" width="3%">&nbsp;</TD>
							<TD align="justify" width="87%">&nbsp;</TD>
						</TR>
					</TABLE>';

			//pendapatan
			$cRet .= "<table style=\"".$spasi."\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
					<tr>
						 <td align=\"left\" width=\"2%\" rowspan=\"2\"><strong>&nbsp;</strong></td>                         
						 <td style=\"border-top:solid;;border-bottom:solid;\" align=\"center\" width=\"10%\" rowspan=\"2\"><strong>Reff</strong></td>                         
						 <td style=\"border-top:solid;;border-bottom:solid;\" align=\"center\" width=\"30%\" rowspan=\"2\"><strong>Uraian</strong></td>
						 <td style=\"border-top:solid;\" align=\"center\" width=\"15%\"><strong>Anggaran $thn</strong></td>
						 <td style=\"border-top:solid;\" align=\"center\" width=\"15%\"><strong>Realisasi $thn</strong></td>
						 <td style=\"border-top:solid;\" align=\"center\" width=\"10%\"><strong>Lebih / (Kurang)</strong></td>   						  
						 <td style=\"border-top:solid;border-bottom:solid;\" align=\"center\" width=\"5%\" rowspan=\"2\"><strong>%</strong></td>
						 <td style=\"border-top:solid;\" align=\"center\" width=\"13%\"><strong>Realisasi $cthnsbl</strong></td>
					</tr>
					<tr>
						 <td align=\"center\" style=\"border-bottom:solid;\"><strong>(Rp)</strong></td>
						 <td align=\"center\" style=\"border-bottom:solid;\"><strong>(Rp)</strong></td>
						 <td align=\"center\" style=\"border-bottom:solid;\"><strong>(Rp)</strong></td>                                               
						 <td align=\"center\" style=\"border-bottom:solid;\"><strong>(Rp)</strong></td>                                               
					</tr>
					<tr>
						 <td align=\"left\"><strong>&nbsp;</strong></td>                         
						 <td align=\"center\"><strong>&nbsp;</strong></td>                         
						 <td align=\"center\"><strong>&nbsp;</strong></td>
						 <td align=\"center\"><strong>&nbsp;</strong></td>
						 <td align=\"center\"><strong>&nbsp;</strong></td>
						 <td align=\"center\"><strong></strong>&nbsp;</td>   						  
						 <td align=\"center\"><strong></strong>&nbsp;</td>
						 <td align=\"center\"><strong></strong>&nbsp;</td>
					</tr>";
					
			//4
			$sql_4 = "SELECT kd_rek5, nm_rek5, ISNULL(SUM(anggaran),0) as anggaran, ISNULL(SUM(realisasi),0) realisasi, ISNULL(SUM(real_tlalu),0) real_tlalu FROM(SELECT x.kd_rek5, x.nm_rek5, x.anggaran, x.realisasi, y.real_tlalu FROM (
					Select  LEFT(kd_rek5,1) kd_rek5,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(kd_rek5,1)) nm_rek5, sum(anggaran) anggaran, sum(real_spj) realisasi
					FROM (
					select a.kd_rek5 kd_ang, a.kd_rek64 kd_rek5, a.anggaran, isnull(b.real_spj,0) as real_spj from
					(select a.kd_rek5, b.kd_rek64,
					case when ($ang=0) then sum(a.nilai) when ($ang=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
					from 
					(select kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
					a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
					group by a.kd_rek5, b.kd_rek64) a
					LEFT JOIN
					(select a.kd_rek5, a.map_real, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)=4 and YEAR(b.tgl_voucher)='$thn' 
					group by  a.kd_rek5, a.map_real)
					b on a.kd_rek5=b.map_real and a.kd_rek64=b.kd_rek5 ) z
					WHERE LEFT(kd_rek5,1)='4'
					GROUP BY LEFT(kd_rek5,1) ) x
					LEFT JOIN
					(select LEFT(a.kd_rek5,1) kd_rek5, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_tlalu 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)='4' and YEAR(b.tgl_voucher)='$cthnsbl'
					group by LEFT(a.kd_rek5,1)) y
					on x.kd_rek5=y.kd_rek5
					UNION ALL
					SELECT x.kd_rek5, x.nm_rek5, x.anggaran, x.realisasi, y.real_tlalu FROM (
					Select LEFT(kd_rek5,2) kd_rek5,(select nm_rek2 from ms_rek2_64 where kd_rek2=LEFT(kd_rek5,2)) nm_rek5, sum(anggaran) anggaran, sum(real_spj) realisasi
					FROM (
					select a.kd_rek5 kd_ang, a.kd_rek64 kd_rek5, a.anggaran, isnull(b.real_spj,0) as real_spj from
					(select a.kd_rek5, b.kd_rek64,
					case when ($ang=0) then sum(a.nilai) when ($ang=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
					from 
					(select kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
					a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
					group by a.kd_rek5, b.kd_rek64) a
					LEFT JOIN
					(select a.kd_rek5, a.map_real, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)=4 and YEAR(b.tgl_voucher)='$thn' 
					group by a.kd_rek5, a.map_real)
					b on a.kd_rek5=b.map_real and a.kd_rek64=b.kd_rek5 ) z
					WHERE LEFT(kd_rek5,1)='4'
					GROUP BY LEFT(kd_rek5,2)) x
					LEFT JOIN
					(select LEFT(a.kd_rek5,2) kd_rek5, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_tlalu 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)='4' and YEAR(b.tgl_voucher)='$cthnsbl'
					group by LEFT(a.kd_rek5,2)) y
					on x.kd_rek5=y.kd_rek5) p
					group by kd_rek5, nm_rek5
					order by kd_rek5";
        $sql_44=$this->db->query($sql_4);
		
		foreach ($sql_44->result() as $rowsc){
			$kd_rek5        = $rowsc->kd_rek5;
			$nm_rek5        = $rowsc->nm_rek5;
			$anggaran_pend  = $rowsc->anggaran;
			$realisasi_pend = $rowsc->realisasi;
			$realisasi_tlalu = $rowsc->real_tlalu;
			$lebih_kurang   = $realisasi_pend-$anggaran_pend;
			$banding         = $realisasi_pend - $realisasi_tlalu;
			$persen_pen     = ($anggaran_pend!=0)?($realisasi_pend / $anggaran_pend ) * 100:0; 
			
			$ang_pen  = empty($anggaran_pend) || $anggaran_pend == 0 ? number_format(0,"2",",",".") :number_format($anggaran_pend,"2",",",".");
			$real_pen = empty($realisasi_pend) || $realisasi_pend == 0 ? number_format(0,"2",",",".") :number_format($realisasi_pend,"2",",",".");
			$real_tlalu = empty($realisasi_tlalu) || $realisasi_tlalu == 0 ? number_format(0,"2",",",".") :number_format($realisasi_tlalu,"2",",",".");
			$per_pen  = empty($persen_pen) || $persen_pen == 0 ? number_format(0,"2",",",".") :number_format($persen_pen,"2",",",".");
			
			$panjang = strlen($kd_rek5);
			
			if($banding<0){
					$c = "(";
					$d = ")";
					$ban_bel = $banding*-1;
					$banding_bel = empty($ban_bel) || $ban_bel == 0 ? number_format(0,"2",",",".") :number_format($ban_bel,"2",",",".");
				}else{
					$c = "";
					$d = "";
					$banding_bel = empty($banding) || $banding == 0 ? number_format(0,"2",",",".") :number_format($banding,"2",",",".");
				} 
			
			if($lebih_kurang<0){
					$naik_turun = "selisih";
					$a = "(";
					$b = ")";
					$hitung_lo = $lebih_kurang*-1;
					$leb_kur  = empty($hitung_lo) || $hitung_lo == 0 ? number_format(0,"2",",",".") :number_format($hitung_lo,"2",",",".");
				}else{
					$naik_turun = "kenaikan";
					$a = "";
					$b = "";
					$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
				} 
	
			
			if($panjang=='1'){
				$cRet .= "<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>                         
							 <td align=\"left\"><strong>".$kd_rek5."</strong></td>                         
							 <td align=\"left\"><strong>".$nm_rek5."</strong></td>
							 <td align=\"right\"><strong>".$ang_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_pen."</strong></td>
							 <td align=\"right\"><strong>".$a."".$leb_kur."".$b."</strong></td>   						  
							 <td align=\"center\"><strong>".$per_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_tlalu."</strong></td>
						</tr>";
			}else{
				$cRet .= "<tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td align=\"right\">&nbsp;</td>
							 <td align=\"left\">".$this->dotrek($kd_rek5)." ".$nm_rek5."</td>
							 <td align=\"right\"><strong>".$ang_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_pen."</strong></td>
							 <td align=\"right\"><strong>".$leb_kur."</strong></td>   						  
							 <td align=\"center\"><strong>".$per_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_tlalu."</strong></td>
						  </tr>";
			}

		}
		
		
		//header 4
		$sql_444 = "SELECT kd_rek5, nm_rek5, ISNULL(SUM(anggaran),0) as anggaran, ISNULL(SUM(realisasi),0) realisasi, ISNULL(SUM(real_tlalu),0) real_tlalu FROM(SELECT x.kd_rek5, x.nm_rek5, x.anggaran, x.realisasi, y.real_tlalu FROM (
					Select LEFT(kd_rek5,1) kd_rek5,(select nm_rek1 from ms_rek1 where kd_rek1=LEFT(kd_rek5,1)) nm_rek5, sum(anggaran) anggaran, sum(real_spj) realisasi
					FROM (
					select a.kd_rek5 kd_ang, a.kd_rek64 kd_rek5, a.anggaran, isnull(b.real_spj,0) as real_spj from
					(select a.kd_rek5, b.kd_rek64,
					case when ($ang=0) then sum(a.nilai) when ($ang=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
					from 
					(select kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
					a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
					group by a.kd_rek5, b.kd_rek64) a
					LEFT JOIN
					(select a.kd_rek5, a.map_real, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)=4 and YEAR(b.tgl_voucher)='$thn' 
					group by a.kd_rek5, a.map_real)
					b on a.kd_rek5=b.map_real and a.kd_rek64=b.kd_rek5 ) z
					WHERE LEFT(kd_rek5,1)='4'
					GROUP BY LEFT(kd_rek5,1) ) x
					LEFT JOIN
					(select LEFT(a.kd_rek5,1) kd_rek5, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_tlalu 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)='4' and YEAR(b.tgl_voucher)='$cthnsbl'
					group by LEFT(a.kd_rek5,1)) y
					on x.kd_rek5=y.kd_rek5) p
					group by kd_rek5, nm_rek5
					order by kd_rek5";
		$sql_4444=$this->db->query($sql_444);
		
		foreach ($sql_4444->result() as $rowsc){
			$kd_rek5        = $rowsc->kd_rek5;
			$nm_rek5        = $rowsc->nm_rek5;
			$anggaran_pend  = $rowsc->anggaran;
			$realisasi_pend = $rowsc->realisasi;
			$realisasi_tlalu = $rowsc->real_tlalu;
			$lebih_kurang   = $realisasi_pend-$anggaran_pend;
			$banding         = $realisasi_pend - $realisasi_tlalu;
			$persen_pen     = ($anggaran_pend!=0)?($realisasi_pend / $anggaran_pend ) * 100:0; 
			
			$ang_pen  = empty($anggaran_pend) || $anggaran_pend == 0 ? number_format(0,"2",",",".") :number_format($anggaran_pend,"2",",",".");
			$real_pen = empty($realisasi_pend) || $realisasi_pend == 0 ? number_format(0,"2",",",".") :number_format($realisasi_pend,"2",",",".");
			$real_tlalu = empty($realisasi_tlalu) || $realisasi_tlalu == 0 ? number_format(0,"2",",",".") :number_format($realisasi_tlalu,"2",",",".");
			$per_pen  = empty($persen_pen) || $persen_pen == 0 ? number_format(0,"2",",",".") :number_format($persen_pen,"2",",",".");
			
			$panjang = strlen($kd_rek5);
			
			$hasil_real        = $realisasi_pend-$realisasi_tlalu;
			$hasil_real_num    = empty($hasil_real) || $hasil_real == 0 ? number_format(0,"2",",",".") :number_format($hasil_real,"2",",",".");
			$persen_hasil_real = ($realisasi_pend!=0)?( $realisasi_tlalu / $realisasi_pend ) * 100:0; 
			$per_hasil_real  = empty($persen_hasil_real) || $persen_pen == 0 ? number_format(0,"2",",",".") :number_format($persen_hasil_real,"2",",",".");
			
			
			
			if($banding<0){
					$c = "(";
					$d = ")";
					$ban_bel = $banding*-1;
					$banding_bel = empty($ban_bel) || $ban_bel == 0 ? number_format(0,"2",",",".") :number_format($ban_bel,"2",",",".");
				}else{
					$c = "";
					$d = "";
					$banding_bel = empty($banding) || $banding == 0 ? number_format(0,"2",",",".") :number_format($banding,"2",",",".");
				} 
			
			$prv_ang = $this->db->query("SELECT ISNULL(SUM(CASE WHEN kd_rek64='41' then anggaran END),0) as ang_pad,
										   ISNULL(SUM(CASE WHEN kd_rek64='42' then anggaran END),0) as ang_pend_transfer,
										   ISNULL(SUM(CASE WHEN kd_rek64='43' then anggaran END),0) as ang_lain_pad
										FROM(
										select LEFT(b.kd_rek64,2) kd_rek64,
										case when ($ang=0) then sum(a.nilai) when ($ang=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
										from 
										(select kd_skpd, kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
										a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
										group by LEFT(b.kd_rek64,2)) z");
			$prvn_ang              = $prv_ang->row();          
			$pad_ang           = $prvn_ang->ang_pad;  
			$pend_transfer_ang = $prvn_ang->ang_pend_transfer;  
			$lain_pad_ang      = $prvn_ang->ang_lain_pad;
				
			$prv = $this->db->query("SELECT SUM(pad) pad, SUM(pend_transfer) pend_transfer, SUM(lain_pad) lain_pad FROM(
										select ISNULL(SUM(CASE WHEN LEFT(b.kd_rek5,2)='41' then b.kredit-b.debet END),0) as pad, 
											   ISNULL(SUM(CASE WHEN LEFT(b.kd_rek5,2)='42' then b.kredit-b.debet END),0) as pend_transfer,
											   ISNULL(SUM(CASE WHEN LEFT(b.kd_rek5,2)='43' then b.kredit-b.debet END),0) as lain_pad
										from $trhju a inner join $trdju b on a.kd_skpd=b.kd_unit and a.no_voucher=b.no_voucher
										where LEFT(b.kd_rek5,2) IN ('41','42','43') AND YEAR(a.tgl_voucher)='$thn'
										group by LEFT(b.kd_rek5,2)) x");
			$prvn              = $prv->row();          
			$pad_det           = $prvn->pad;  
			$pend_transfer_det = $prvn->pend_transfer;  
			$lain_pad_det      = $prvn->lain_pad;  
			
			$persen_pad = ($pad_ang!=0)?($pad_det / $pad_ang ) * 100:0;
			$per_pad  = empty($persen_pad) || $persen_pad == 0 ? number_format(0,"2",",",".") :number_format($persen_pad,"2",",",".");
			
			$persen_trf = ($pend_transfer_ang!=0)?($pend_transfer_det / $pend_transfer_ang ) * 100:0;
			$per_trf  = empty($persen_trf) || $persen_trf == 0 ? number_format(0,"2",",",".") :number_format($persen_trf,"2",",",".");
			
			$persen_lain = ($lain_pad_ang!=0)?($lain_pad_det / $lain_pad_ang ) * 100:0;
			$per_lain  = empty($persen_lain) || $persen_lain == 0 ? number_format(0,"2",",",".") :number_format($persen_lain,"2",",",".");
			
			
			
			if($lebih_kurang<0){
					$naik_turun = "terjadi penurunan";
					$a = "(";
					$b = ")";
					$hitung_lo = $lebih_kurang*-1;
					$leb_kur  = empty($hitung_lo) || $hitung_lo == 0 ? number_format(0,"2",",",".") :number_format($hitung_lo,"2",",",".");
				}else{
					$naik_turun = "terjadi peningkatan";
					$a = "";
					$b = "";
					$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
				} 
			
			$cRet .= "	<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"justify\" colspan=\"7\">Realisasi Pendapatan Daerah Provinsi Kalimantan Barat  Tahun Anggaran  ".$thn."  dapat dirinci secara garis besar sebagai berikut : Pendapatan Daerah Tahun Anggaran ".$thn." ditargetkan setelah ditetapkannya perubahan anggaran tahun  ".$thn." sebesar Rp. ".$ang_pen."  terealisasi sebesar  Rp. ".$real_pen." atau ".$per_pen."%.</td>                         
						</tr>
						<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"justify\" colspan=\"7\">Realisasi Pendapatan Daerah tersebut bersumber dari Pendapatan Asli Daerah (PAD) sebesar  Rp. ".number_format($pad_det,"2",",",".")." atau ".$per_pad."%. Pendapatan Transfer realisasinya tercatat sebesar  Rp. ".number_format($pend_transfer_det,"2",",",".")." atau ".$per_trf."%. Sedangkan Lain-lain Pendapatan yang Sah sebesar Rp. ".number_format($lain_pad_det,"2",",",".")." atau ".$per_lain."%.</td>                         
						</tr>
						<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"justify\" colspan=\"7\">Apabila realisasi Pendapatan Daerah Tahun Anggaran ".$thn." sebesar  ".$real_pen."  dibandingkan dengan realisasi Pendapatan Daerah Tahun Anggaran ".$cthnsbl." yang tercatat sebesar  ".$real_tlalu.", maka realisasi Pendapatan Daerah di tahun ".$cthnsbl." terlihat ".$naik_turun." sebesar ".$hasil_real_num." - ".$per_hasil_real."</td>                         
						</tr>
						<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"justify\" colspan=\"7\">Rekap Pendapatan per ".$this->tukd_model->tanggal_format_indonesia($dcetak)." disajikan pada Lampiran I</td>                         
						</tr>
						<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"justify\" colspan=\"7\">&nbsp;</td>                         
						</tr>";
		}
		
		//41
		$sql_41 = "SELECT x.kd_rek5, x.nm_rek5, x.anggaran, x.realisasi, y.real_tlalu FROM (
					Select LEFT(kd_rek5,2) kd_rek5,(select nm_rek2 from ms_rek2 where kd_rek2=LEFT(kd_rek5,2)) nm_rek5, sum(anggaran) anggaran, sum(real_spj) realisasi
					FROM (
					select a.kd_rek5 kd_ang, a.kd_rek64 kd_rek5, a.anggaran, isnull(b.real_spj,0) as real_spj from
					(select a.kd_rek5, b.kd_rek64,
					case when ($ang=0) then sum(a.nilai) when ($ang=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
					from 
					(select kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
					a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
					group by a.kd_rek5, b.kd_rek64) a
					LEFT JOIN
					(select a.kd_rek5, a.map_real, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)=4 and YEAR(b.tgl_voucher)=$thn
					group by a.kd_rek5, a.map_real)
					b on a.kd_rek5=b.map_real and a.kd_rek64=b.kd_rek5 ) z
					WHERE LEFT(kd_rek5,2)='41'
					GROUP BY LEFT(kd_rek5,2))x
					LEFT JOIN
					(select LEFT(a.kd_rek5,2) kd_rek5, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_tlalu 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,2)='41' and YEAR(b.tgl_voucher)='$cthnsbl'
					group by LEFT(a.kd_rek5,2)) y
					on x.kd_rek5=y.kd_rek5
											
					UNION ALL
					SELECT x.kd_rek5, x.nm_rek5, x.anggaran, x.realisasi, y.real_tlalu FROM (
					Select LEFT(kd_rek5,3) kd_rek5,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek5,3)) nm_rek5, sum(anggaran) anggaran, sum(real_spj) realisasi
					FROM (
					select a.kd_rek5 kd_ang, a.kd_rek64 kd_rek5, a.anggaran, isnull(b.real_spj,0) as real_spj from
					(select a.kd_rek5, b.kd_rek64,
					case when (2=0) then sum(a.nilai) when (2=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
					from 
					(select kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
					a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
					group by a.kd_rek5, b.kd_rek64) a
					LEFT JOIN
					(select a.kd_rek5, a.map_real, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)=4 and YEAR(b.tgl_voucher)=$thn
					group by a.kd_rek5, a.map_real)
					b on a.kd_rek5=b.map_real and a.kd_rek64=b.kd_rek5 ) z
					WHERE LEFT(kd_rek5,2)='41'
					GROUP BY LEFT(kd_rek5,3)) x
					LEFT JOIN
					(select LEFT(a.kd_rek5,3) kd_rek5, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_tlalu 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,2)='41' and YEAR(b.tgl_voucher)='$cthnsbl'
					group by LEFT(a.kd_rek5,3)) y
					on x.kd_rek5=y.kd_rek5";
        $sql_441=$this->db->query($sql_41);
		
		foreach ($sql_441->result() as $rowsc){
			$kd_rek5        = $rowsc->kd_rek5;
			$nm_rek5        = $rowsc->nm_rek5;
			$anggaran_pend  = $rowsc->anggaran;
			$realisasi_pend = $rowsc->realisasi;
			$realisasi_tlalu = $rowsc->real_tlalu;
			$lebih_kurang   = $realisasi_pend-$anggaran_pend;
			$banding         = $realisasi_pend - $realisasi_tlalu;
			$persen_pen     = ($anggaran_pend!=0)?($realisasi_pend / $anggaran_pend ) * 100:0; 
			
			$ang_pen  = empty($anggaran_pend) || $anggaran_pend == 0 ? number_format(0,"2",",",".") :number_format($anggaran_pend,"2",",",".");
			$real_pen = empty($realisasi_pend) || $realisasi_pend == 0 ? number_format(0,"2",",",".") :number_format($realisasi_pend,"2",",",".");
			$real_tlalu = empty($realisasi_tlalu) || $realisasi_tlalu == 0 ? number_format(0,"2",",",".") :number_format($realisasi_tlalu,"2",",",".");
			$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
			
			if($banding<0){
					$c = "(";
					$d = ")";
					$ban_pen = $banding*-1;
					$banding_pen = empty($ban_pen) || $ban_pen == 0 ? number_format(0,"2",",",".") :number_format($ban_pen,"2",",",".");
				}else{
					$c = "";
					$d = "";
					$banding_bel = empty($banding) || $banding == 0 ? number_format(0,"2",",",".") :number_format($banding,"2",",",".");
				} 
			
			if($lebih_kurang<0){
					$naik_turun = "terjadi penurunan";
					$a = "(";
					$b = ")";
					$hitung_lo = $lebih_kurang*-1;
					$leb_kur  = empty($hitung_lo) || $hitung_lo == 0 ? number_format(0,"2",",",".") :number_format($hitung_lo,"2",",",".");
				}else{
					$naik_turun = "terjadi peningkatan";
					$a = "";
					$b = "";
					$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
				} 
			
			$per_pen  = empty($persen_pen) || $persen_pen == 0 ? number_format(0,"2",",",".") :number_format($persen_pen,"2",",",".");
			
			$panjang = strlen($kd_rek5);
			
			if($lebih_kurang<0){
				$naik_turun = "selisih";
			}else{
				$naik_turun = "kenaikan";
			} 
			
			if($panjang=='2'){
				$cRet .= "<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>                         
							 <td align=\"left\"><strong>".$this->dotrek($kd_rek5)."</strong></td>                         
							 <td align=\"left\"><strong>".$nm_rek5."</strong></td>
							 <td align=\"right\"><strong>".$ang_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_pen."</strong></td>
							 <td align=\"right\"><strong>".$leb_kur."</strong></td>   						  
							 <td align=\"center\"><strong>".$per_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_tlalu."</strong></td>
						</tr>
						<tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td align=\"right\">&nbsp;</td>
							 <td align=\"justify\" colspan=\"6\">
							 Pajak daerah adalah kontribusi wajib pajak kepada daerah yang terutang oleh orang pribadi atau badan yang bersifat memaksa berdasarkan Undang-Undang dengan tidak mendapatkan imbalan secara langsung dan digunakan untuk keperluan daerah bagi sebesar-besarnya kemakmuran rakyat. Meskipun demikian untuk menjamin kelangsungan pungutan pajak, pemerintah daerah harus memberikan manfaat kepada pembayar pajak seperti pelayanan dan pembangunan serta lain sebagainya. Pada akhirnya masyarakat merasakan manfaat membayar pajak.<br> 
							Dalam rangka melaksanakan hak pemerintah daerah seiring dengan dilaksanakannya kebijakan otonomi daerah, dimana pemerintah telah memberikan kewenangan yang seluas-luasnya kepada daerah yang pengaturannya dilakukan melalui Undang-Undang No. 33 Tahun 2004  dan Undang-Undang No. 23 Tahun 2014 tentang Pemerintahan Daerah dan tentang Perimbangan Keuangan antara Pemerintahan Pusat dan Pemerintahan Daerah.<br>
							Pemberian kewenangan tersebut berupa pemberian sumber-sumber pembiayaan melalui UU No. 28 Tahun 2009 tentang Pajak Daerah dan Retribusi Daerah. Adapun  jenis pajak daerah yang dapat dikelola oleh Pemerintah Provinsi meliputi :<br>
							1. Pajak Kendaraan Bermotor (termasuk didalamnya Pajak Kendaraan diatas Air/PKA);<br>
							2. Bea Balik Nama Kendaraan Bermotor ( termasuk didalamnya BBNKA);<br>
							3. Pajak Bahan Bakar Kendaraan Bermotor;<br>
							4. Pajak Air Permukaan; dan<br>
							5. Pajak  Rokok.<br>
							Pengelolaan terhadap sumber-sumber pajak dimaksud, dalam pelaksanaannya menganut prinsip pajak yang baik yaitu bahwa pengelolaan terhadap pajak tersebut tidak menyebabkan ekonomi biaya tinggi dan/atau menghambat mobilitas penduduk, lalu lintas barang dan jasa antar daerah dan kegiatan ekspor impor.<br>
							Terhadap jenis-jenis pajak yang menjadi kewenangan Pemerintah Provinsi, maka dalam rangka optimalisasi penerimaan pajak daerah diTahun Anggaran ".$thn.", pengelolaan terhadap sumber pajak dimaksud yang dilakukan melalui kegiatan intensifikasi,  yaitu melakukan penguatan dan perluasan terhadap pelaksanaan pemungutan pajak-pajak daerah yang dalam pelaksanaannya sesungguhnya sudah berjalan dengan baik, seperti Pajak Kendaraan Bermotor (PKB), Pajak Kendaraan diatas Air, Bea Balik Nama Kendaraan Bermotor (BBNKB), Pajak Bahan Bakar Kendaraan Bermotor (PBBKB), Pajak Air Pemukaan (PAP) dan Pajak Rokok melalui kegiatan :
							</td>
						  </tr>
						<tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">1</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan razia kendaraan bermotor bagi yang belum membayar pajak kendaraan dengan bekerjasama dengan kepolisian, polisi militer TNI, PT. Jasa Raharja Dinas Perhubungan dan Satpol PP;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">2</td>
							 <td align=\"justify\" colspan=\"6\">Menerbitkan Surat Keputusan Gubernur Kalimantan Barat Nomor 544/DISPENDA/2016 tentang Pemberian Keringanan Pajak Kendaraan Bermotor dan Bea Balik Nama Kendaraan Bermotor Provinsi Kalimantan Barat Tahun 2016;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">3</td>
							 <td align=\"justify\" colspan=\"5\">Memperluas cakupan pembayaran pajak kendaraan bermotor melalui pelayanan gerai samsat yang bekerjasama dengan Bank Kalbar disetiap kantor cabang pembantu Bank Kalbar;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">4</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan kegiatan penyuluhan pajak kepada masyarakat Kabupaten / Kota melalui koordinasi dengan Unit Pelayanan Pendapatan Daerah (UPPD) setempat;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">5</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan penerimaan pembayaran pajak kendaraan bermotor diluar jam kerja melalui pelayanan Samsat Corner yang terdapat dipusat perbelanjaan, pelayanan gerai samsat dan samsat keliling disetiap kabupaten/kota;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">6</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan sistem online pembayaran pajak kendaraan bermotor, sehingga wajib pajak dapat melakukan pembayaran pajak tidak berdasarkan domisili;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">7</td>
							 <td align=\"justify\" colspan=\"6\">Koordinasi dan konsultasi dengan Kementrian Dalam Negeri dan Kementrian Keuangan Republik Indonesia mengenai hal-hal yang terkait dengan kebijakan di bidang perpajakan;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">8</td>
							 <td align=\"justify\" colspan=\"6\">Sinkronisasi dan menyusun pedoman Nilai Jual Kendaraan Bermotor berdasarkan Keputusan Mendagri, sebagai dasar pengenaan Pajak Kendaraan Bermotor;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">9</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan rapat evaluasi pelayanan SAMSAT bersama mitra kerja yaitu Kepolisian, PT. Jasa Raharja dan Bank Kalbar se Kalimantan Barat;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">10</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan Koordinasi dan Konsultasi Pajak Bahan Bakar Kendaraan Bermotor (PBB-KB) dengan PT. Pertamina, Badan Pengawas Minyak dan Migas di Jakarta, sebagai upaya rekonsiliasi terhadap penerimaan pajak daerah yang bersumber dari Bahan Bakar Kendaraan Bermotor;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">11</td>
							 <td align=\"justify\" colspan=\"6\">Melakukan pembinaan pelayanan SAMSAT guna meningkatkan pelayanan di 15 Unit Pelayanan Pendapatan Daerah (UPPD) Dispenda Prov. Kalbar;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">12</td>
							 <td align=\"justify\" colspan=\"5\">Melaksanakan verifikasi dan rekonsiliasi penerimaan pajak daerah di 15 Unit Pelayanan Pendapatan Daerah (UPPD) dengan kas daerah;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">13</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan koordinasi dan konsultasi pajak rokok dengan Kementrian Dalam Negeri dan Kementrian Keuangan Republik Indonesia.</td>
						  </tr>
						   <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\"></td>
							 <td align=\"justify\" colspan=\"6\">Sementara kegiatan Ekstensifikasi dilakukan dengan memperluas basis pajak yang sudah ada, ekstensifikasi ini dilakukan melalui kegiatan antara lain :</td>
						  </tr>
						   <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">1</td>
							 <td align=\"justify\" colspan=\"6\">Melaksanakan rapat koordinasi dengan Instansi terkait di lingkungan Pemerintah Provinsi, Kabupaten dan Kota serta Instansi terkait lainnya dalam rangka peningkatan pajak daerah melalui penjaringan terhadap alat-alat berat/besar;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">2</td>
							 <td align=\"justify\" colspan=\"6\">Melakukan pendataan terhadap perusahaan pengguna alat-alat berat dan jumlah alat berat yang dipergunakan;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">3</td>
							 <td align=\"justify\" colspan=\"6\">Melakukan pendataan terhadap perusahaan yang menggunakan air permukaan dalam produksinya;</td>
						  </tr>
						  <tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td valign=\"top\" align=\"right\">4</td>
							 <td align=\"justify\" colspan=\"6\">Koordinasi dan konsultasi dengan Pemerintah Provinsi lain, dalam upaya pengembangan model pemungutan pajak terhadap kendaraan alat-alat berat/besar di Kalimantan Barat.</td>
						  </tr>
						";
			}else{
				$cRet .= "<tr>
							 <td align=\"left\">&nbsp;</td> 
							 <td align=\"right\">&nbsp;</td>
							 <td align=\"left\">".$this->dotrek($kd_rek5)." ".$nm_rek5."</td>
							 <td align=\"right\"><strong>".$ang_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_pen."</strong></td>
							 <td align=\"right\"><strong>".$leb_kur."</strong></td>   						  
							 <td align=\"center\"><strong>".$per_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_tlalu."</strong></td>
						  </tr>";
			} 
		
		}
		
		//411
		$sql_411 = "SELECT x.kd_rek5, x.nm_rek5, x.anggaran, x.realisasi, y.real_tlalu FROM (
					Select LEFT(kd_rek5,3) kd_rek5,(select nm_rek3 from ms_rek3 where kd_rek3=LEFT(kd_rek5,3)) nm_rek5, sum(anggaran) anggaran, sum(real_spj) realisasi
					FROM (
					select a.kd_rek5 kd_ang, a.kd_rek64 kd_rek5, a.anggaran, isnull(b.real_spj,0) as real_spj from
					(select a.kd_rek5, b.kd_rek64,
					case when ($ang=0) then sum(a.nilai) when ($ang=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
					from 
					(select kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
					a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
					group by a.kd_rek5, b.kd_rek64) a
					LEFT JOIN
					(select a.kd_rek5, a.map_real, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,1)=4 and YEAR(b.tgl_voucher)=$thn
					group by a.kd_rek5, a.map_real)
					b on a.kd_rek5=b.map_real and a.kd_rek64=b.kd_rek5 ) z
					WHERE LEFT(kd_rek5,3)='411'
					GROUP BY LEFT(kd_rek5,3)) x
					LEFT JOIN
					(select LEFT(a.kd_rek5,3) kd_rek5, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_tlalu 
					from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
					where left(a.kd_rek5,3)='411' and YEAR(b.tgl_voucher)=$cthnsbl
					group by LEFT(a.kd_rek5,3)) y
					on x.kd_rek5=y.kd_rek5";
        $sql_4411=$this->db->query($sql_411);
		
		foreach ($sql_4411->result() as $rowsc){
			$kd_rek5        = $rowsc->kd_rek5;
			$nm_rek5        = $rowsc->nm_rek5;
			$anggaran_pend  = $rowsc->anggaran;
			$realisasi_pend = $rowsc->realisasi;
			$realisasi_tlalu = $rowsc->real_tlalu;
			$lebih_kurang   = $realisasi_pend-$anggaran_pend;
			$banding         = $realisasi_pend - $realisasi_tlalu;
			$persen_pen     = ($anggaran_pend!=0)?($realisasi_pend / $anggaran_pend ) * 100:0; 
			
			$ang_pen  = empty($anggaran_pend) || $anggaran_pend == 0 ? number_format(0,"2",",",".") :number_format($anggaran_pend,"2",",",".");
			$real_pen = empty($realisasi_pend) || $realisasi_pend == 0 ? number_format(0,"2",",",".") :number_format($realisasi_pend,"2",",",".");
			$real_tlalu = empty($realisasi_tlalu) || $realisasi_tlalu == 0 ? number_format(0,"2",",",".") :number_format($realisasi_tlalu,"2",",",".");
			$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
			
			if($banding<0){
					$c = "(";
					$d = ")";
					$ban_pen = $banding*-1;
					$banding_pen = empty($ban_pen) || $ban_pen == 0 ? number_format(0,"2",",",".") :number_format($ban_pen,"2",",",".");
				}else{
					$c = "";
					$d = "";
					$banding_bel = empty($banding) || $banding == 0 ? number_format(0,"2",",",".") :number_format($banding,"2",",",".");
				} 
			
			if($lebih_kurang<0){
					$naik_turun = "terjadi penurunan";
					$a = "(";
					$b = ")";
					$hitung_lo = $lebih_kurang*-1;
					$leb_kur  = empty($hitung_lo) || $hitung_lo == 0 ? number_format(0,"2",",",".") :number_format($hitung_lo,"2",",",".");
				}else{
					$naik_turun = "terjadi peningkatan";
					$a = "";
					$b = "";
					$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
				} 
			
			$per_pen  = empty($persen_pen) || $persen_pen == 0 ? number_format(0,"2",",",".") :number_format($persen_pen,"2",",",".");
			
			$panjang = strlen($kd_rek5);
			
			if($lebih_kurang<0){
				$naik_turun = "selisih";
			}else{
				$naik_turun = "kenaikan";
			} 
			
				$cRet .= "<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>                         
							 <td align=\"left\"><strong>".$this->dotrek($kd_rek5)."</strong></td>                         
							 <td align=\"left\"><strong>".$nm_rek5."</strong></td>
							 <td align=\"right\"><strong>".$ang_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_pen."</strong></td>
							 <td align=\"right\"><strong>".$a."".$leb_kur."".$b."</strong></td>   						  
							 <td align=\"center\"><strong>".$per_pen."</strong></td>
							 <td align=\"right\"><strong>".$real_tlalu."</strong></td>
						</tr>";
		}
		
		//411_det
		$sql_411_det = "SELECT ROW_NUMBER() OVER (ORDER BY x.kd_rek5 ASC) AS nomor,x.kd_rek5, x.nm_rek5, x.anggaran, x.realisasi, y.real_tlalu FROM (
						Select LEFT(kd_rek5,5) kd_rek5,(select nm_rek4 from ms_rek4 where kd_rek4=LEFT(kd_rek5,5)) nm_rek5, sum(anggaran) anggaran, sum(real_spj) realisasi
						FROM (
						select a.kd_rek5 kd_ang, a.kd_rek64 kd_rek5, a.anggaran, isnull(b.real_spj,0) as real_spj from
						(select a.kd_rek5, b.kd_rek64,
						case when ($ang=0) then sum(a.nilai) when ($ang=1) then sum(a.nilai_sempurna) else sum(a.nilai_ubah) end as anggaran
						from 
						(select kd_rek5, kd_kegiatan, nilai, nilai_sempurna, nilai_ubah from trdrka_pend where left(kd_rek5,1)=4)
						a inner join ms_rek5 b on a.kd_rek5=b.kd_rek5
						group by a.kd_rek5, b.kd_rek64) a
						LEFT JOIN
						(select a.kd_rek5, a.map_real, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_spj 
						from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
						where left(a.kd_rek5,1)=4 and YEAR(b.tgl_voucher)=$thn
						group by a.kd_rek5, a.map_real)
						b on a.kd_rek5=b.map_real and a.kd_rek64=b.kd_rek5 ) z
						WHERE LEFT(kd_rek5,3)='411'
						GROUP BY LEFT(kd_rek5,5)) x
						LEFT JOIN
						(select LEFT(a.kd_rek5,5) kd_rek5, sum(isnull(a.kredit,0)-isnull(a.debet,0)) as real_tlalu 
						from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
						where left(a.kd_rek5,3)='411' and YEAR(b.tgl_voucher)=$cthnsbl
						group by LEFT(a.kd_rek5,5)) y
						on x.kd_rek5=y.kd_rek5";
        $sql_4411_det=$this->db->query($sql_411_det);
		foreach ($sql_4411_det->result() as $rowsc){
			$nomor        = $rowsc->nomor;
			$kd_rek5        = $rowsc->kd_rek5;
			$nm_rek5        = $rowsc->nm_rek5;
			$anggaran_pend  = $rowsc->anggaran;
			$realisasi_pend = $rowsc->realisasi;
			$realisasi_tlalu = $rowsc->real_tlalu;
			$lebih_kurang   = $realisasi_pend-$anggaran_pend;
			$banding         = $realisasi_pend - $realisasi_tlalu;
			$persen_pen     = ($anggaran_pend!=0)?($realisasi_pend / $anggaran_pend ) * 100:0; 
			
			$ang_pen  = empty($anggaran_pend) || $anggaran_pend == 0 ? number_format(0,"2",",",".") :number_format($anggaran_pend,"2",",",".");
			$real_pen = empty($realisasi_pend) || $realisasi_pend == 0 ? number_format(0,"2",",",".") :number_format($realisasi_pend,"2",",",".");
			$real_tlalu = empty($realisasi_tlalu) || $realisasi_tlalu == 0 ? number_format(0,"2",",",".") :number_format($realisasi_tlalu,"2",",",".");
			$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
			
			if($banding<0){
					$c = "(";
					$d = ")";
					$ban_pen = $banding*-1;
					$banding_pen = empty($ban_pen) || $ban_pen == 0 ? number_format(0,"2",",",".") :number_format($ban_pen,"2",",",".");
				}else{
					$c = "";
					$d = "";
					$banding_bel = empty($banding) || $banding == 0 ? number_format(0,"2",",",".") :number_format($banding,"2",",",".");
				} 
			
			if($lebih_kurang<0){
					$naik_turun = "terjadi penurunan";
					$a = "(";
					$b = ")";
					$hitung_lo = $lebih_kurang*-1;
					$leb_kur  = empty($hitung_lo) || $hitung_lo == 0 ? number_format(0,"2",",",".") :number_format($hitung_lo,"2",",",".");
				}else{
					$naik_turun = "terjadi peningkatan";
					$a = "";
					$b = "";
					$leb_kur  = empty($lebih_kurang) || $lebih_kurang == 0 ? number_format(0,"2",",",".") :number_format($lebih_kurang,"2",",",".");
				} 
			
			$per_pen  = empty($persen_pen) || $persen_pen == 0 ? number_format(0,"2",",",".") :number_format($persen_pen,"2",",",".");
			
			$panjang = strlen($kd_rek5);
			
			/* if($lebih_kurang<0){
				$naik_turun = "selisih";
			}else{
				$naik_turun = "kenaikan";
			}  */
			
				$cRet .= "<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>                         
							 <td align=\"right\">".$nomor."</td>                         
							 <td align=\"left\">".$nm_rek5."</td>
							 <td align=\"right\">".$ang_pen."</td>
							 <td align=\"right\">".$real_pen."</td>
							 <td align=\"right\">".$a."".$leb_kur."".$b."</td>   						  
							 <td align=\"center\">".$per_pen."</td>
							 <td align=\"right\">".$real_tlalu."</td>
						</tr>
						<tr>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"left\"><strong>&nbsp;</strong></td>
							 <td align=\"justify\" colspan=\"7\">Target ".$nm_rek5." pada tahun ".$thn." sebesar Rp. ".$ang_pen." dan yang dapat terealisasi sebesar Rp. ".$real_pen." atau ".$per_pen." terjadi ".$naik_turun." sebesar Rp. ".$leb_kur."</td>                         
						</tr>";
		}
		
		
			$ctk=0;
			$data['prev']= 'BAB V';
             switch ($ctk){
				case 0;
				echo ("<title>BAB V</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
			}
		}
	
	
	function  dotrek($rek){
				$nrek=strlen($rek);
				switch ($nrek) {
                case 1:
				$rek = $this->left($rek,1);								
       			 break;
    			case 2:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1);								
       			 break;
    			case 3:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1);								
       			 break;
    			case 5:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2);								
        		break;
    			case 7:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2).'.'.substr($rek,5,2);								
        		break;
    			default:
				$rek = "";	
				}
				return $rek;
    }
    
    function right($value, $count){
    return substr($value, ($count*-1));
    }

    function left($string, $count){
    return substr($string, 0, $count);
    }    
	
}