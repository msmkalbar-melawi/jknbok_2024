<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Lamp_perda extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();

    }

	
	
    function  tanggal_format_indonesia($tgl){
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;
        }
         
        function  tanggal_indonesia($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = substr($tgl,5,2);
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.'-'.$bulan.'-'.$tahun;

        }
        function right($value, $count){
    return substr($value, ($count*-1));
    }

    function left($string, $count){
    return substr($string, 0, $count);
    }  
	function dotrek($rek){
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
	
        function  getBulan($bln){
        switch  ($bln){
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
	function lamp_semester(){
        $data['page_title']= 'CETAK LAPORAN SEMESTER';
        $this->template->set('title', 'CETAK LAPORAN SEMESTER');   
        $this->template->load('template','lamp_perda/cetak_lamp_semester',$data) ;	
	}
	
	function lamp_semester_rinci(){
        $data['page_title']= 'CETAK LAPORAN SEMESTER';
        $this->template->set('title', 'CETAK LAPORAN SEMESTER');   
        $this->template->load('template','lamp_perda/cetak_lamp_semester_rinci',$data) ;	
	}


	function cetak_lamp_semester_sap_spj($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER PERTAMA";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER KEDUA";
        break;
    }
	
	if ($kd_skpd=='-'){                               
            $kd_skpd = $this->left($this->session->userdata('kdskpd'),7);
        } 
			$panjang=strlen($kd_skpd);
			$where="AND LEFT(kd_skpd,$panjang)='$kd_skpd'";
		
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" ></TD>
						<TD width="40%"  align="left" ></TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$bulan2=12-$bulan;
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD DAN PROGNOSIS<BR> $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
	if($panjang==7){
					$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
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
                    </TABLE>";		
	}
	if($panjang==10){
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
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
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>
                    </TABLE>";	
	}
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
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
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal($bulan,$anggaran) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $ang_surplus-$nil_surplus;
					if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal($bulan,$anggaran) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $ang_netto-$nil_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $ang_silpa-$nil_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_sap ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $no=$no+1;
					   $seq = $row->seq;
					   $kode = $row->kode;
                       $nama = $row->nama;
                       $kode1 = $row->kode1;
                       $kode2 = $row->kode2;
                       $kode3 = $row->kode3;
                       $kode4 = $row->kode4;
                       $jenis = $row->jenis;
                       $spasi = $row->spasi;
					   
					   if($kode1==''){
						$kode1="'X'";
						}
						if($kode2==''){
							$kode2="'XX'";
						}
						if($kode3==''){
							$kode3="'XXX'";
						}
						if($kode4==''){
							$kode4="'XXXXX'";
						}
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal($bulan,$anggaran) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					$sel = $nil_ang-$nilai;
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sel, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sel, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($persen_surplus, "2", ",", ".").''.$f.'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($persen_netto, "2", ",", ".").''.$l.'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($persen_silpa, "2", ",", ".").''.$r.'</b></td> 
							</tr>';
                        break;
					}
					}
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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
			$data['prev']= $cRet;    
            $judul='LRA_SAP ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'P');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
		
	function cetak_lamp_semester_permen_spj($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER PERTAMA";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER KEDUA";
        break;
    }
		if ($kd_skpd=='-'){                               
            $kd_skpd = $this->left($this->session->userdata('kdskpd'),7);
        } 
			$panjang=strlen($kd_skpd);
			$where="AND LEFT(kd_skpd,$panjang)='$kd_skpd'";
		
		
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >   </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$bulan2=12-$bulan;
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI $judul APBD <BR> DAN PROGNOSIS $bulan2 BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		if($panjang==7){
					$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
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
                    </TABLE>";		
	}
	if($panjang==10){
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
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
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>
                    </TABLE>";	
	}	
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
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
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal($bulan,$anggaran) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $ang_surplus-$nil_surplus;
						if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
										$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal($bulan,$anggaran) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $ang_netto-$nil_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $ang_silpa-$nil_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_permen ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $no=$no+1;
					   $seq = $row->seq;
					   $kode = $row->kode;
                       $nama = $row->nama;
                       $kode1 = $row->kode1;
                       $kode2 = $row->kode2;
                       $kode3 = $row->kode3;
                       $kode4 = $row->kode4;
                       $jenis = $row->jenis;
                       $spasi = $row->spasi;
					   
					   if($kode1==''){
						$kode1="'X'";
						}
						if($kode2==''){
							$kode2="'XX'";
						}
						if($kode3==''){
							$kode3="'XXX'";
						}
						if($kode4==''){
							$kode4="'XXXXX'";
						}
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal($bulan,$anggaran) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					$sel = $nil_ang-$nilai;
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$this->tukd_model->rp_minus($sel).'</b></td> 
                                <td align="right" valign="top"><b>'.$this->tukd_model->rp_minus($sel).'</b></td>
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").' </b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$this->tukd_model->rp_minus($sel).'</b></td> 
							   <td align="right" valign="top"><b>'.$this->tukd_model->rp_minus($sel).'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$this->tukd_model->rp_minus($sel).'</td> 
							   <td align="right" valign="top">'.$this->tukd_model->rp_minus($sel).'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$this->tukd_model->rp_minus($sel).'</b></td> 
							   <td align="right" valign="top"><b>'.$this->tukd_model->rp_minus($sel).'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.$this->tukd_model->rp_minus($sisa_surplus1).''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.$this->tukd_model->rp_minus($sisa_surplus1).''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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
			$data['prev']= $cRet;    
            $judul='LRA_PERMEN ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'P');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
		
	
	
	function cetak_lamp_semester_rinci_unit_spj($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd='',$spj=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER PERTAMA";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER KEDUA";
        break;
    }
		$where= "WHERE kd_skpd='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
	$where1 = "AND LEN(kd_rek)<='$jenis'";	
	$nm_skpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
	$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$bulan2=12-$bulan;
	$angkabulan2 = $this->tukd_model->terbilang_angka($bulan2);
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden;text-transform:uppercase;\"><b>LAPORAN REALISASI $judul APBD DAN <BR> PROGNOSIS $bulan2 ($angkabulan2) BULAN BERIKUTNYA</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
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
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>
                    </TABLE>";	
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
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
				
				$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend($bulan,$anggaran) $where $where1  ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';
					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					    case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					    case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result();  
					
					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
							
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend($bulan,$anggaran) $where AND LEN(kd_rek)='$jenis' ";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
							
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek)='$jenis'  ";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang1 = $row->anggaran;
                       $sd_bulan_ini1 = $row->sd_bulan_ini;
					   $sisa1=$nil_ang1-$sd_bulan_ini1;
					   $persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 :$sd_bulan_ini1/$nil_ang1*100;
					   $sisa11 = $sisa1<0 ? $sisa1*-1 :$sisa1;
					   $a = $sisa1<0 ? '(' :'';
					   $b = $sisa1<0 ? ')' :'';
//'.$this->tukd_model->rp_minus($sel).'
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$this->tukd_model->rp_minus($sisa11).'</b></td> 
							   <td align="right" valign="top"><b> '.$this->tukd_model->rp_minus($sisa11).'</b> </td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where $where1 
						  AND SUBSTRING(kd_kegiatan,17,2)='00' ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row){
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result(); 

					
					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
					
					$sql="SELECT '1' kd_rek,'BELANJA LANGSUNG' nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
						  FROM realisasi_jurnal_rinci($bulan,$anggaran) $where 
							AND kd_rek IN('521','522','523')
							UNION ALL
							SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
							FROM realisasi_jurnal_rinci($bulan,$anggaran) $where 
							AND kd_rek IN('521','522','523')
							GROUP BY kd_rek,nm_rek
							ORDER BY kd_rek	";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row){
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $leng=strlen($kd_rek);
					  
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					  
					}
					$hasil->free_result(); 

					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
					
					
					$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where $where1 
						  AND SUBSTRING(kd_kegiatan,17,2)!='00' ORDER BY kd_kegiatan,kd_rek ";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row){
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result(); 
					
					$cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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
			$data['prev']= $cRet;    
            $judul='LAPORAN_SEMESTER ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
	
	function cetak_lamp_semester_rinci_org_spj($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd='',$spj=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER PERTAMA";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER KEDUA";
        break;
    }
		$kd_skpd = $this->left($this->session->userdata('kdskpd'),7);
		$where= "WHERE LEFT(kd_skpd,7)='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
	$where1 = "AND LEN(kd_rek)<='$jenis'";	
	$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" >  </TD>
						<TD width="40%"  align="left" > </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$bulan2=12-$bulan;
	$angkabulan2 = $this->tukd_model->terbilang_angka($bulan2);
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden;text-transform:uppercase;\"><b>LAPORAN REALISASI $judul APBD DAN <BR> PROGNOSIS $bulan2 ($angkabulan2) BULAN BERIKUTNYA </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
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
					
                    </TABLE>";	
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
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
				
				$sql="SELECT RIGHT(kd_kegiatan,5) kd_kegiatan,kd_rek,nm_rek,SUM(anggaran) anggaran, SUM(sd_bulan_ini) sd_bulan_ini 
					 FROM realisasi_jurnal_pend($bulan,$anggaran) $where $where1  
					 GROUP BY RIGHT(kd_kegiatan,5),kd_rek,nm_rek
					 ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';
					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					    case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					    case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result();  
					
					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
							
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend($bulan,$anggaran) $where AND LEN(kd_rek)='$jenis' ";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
							
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek)='$jenis' ";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang1 = $row->anggaran;
                       $sd_bulan_ini1 = $row->sd_bulan_ini;
					   $sisa1=$nil_ang1-$sd_bulan_ini1;
					   $persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 :$sd_bulan_ini1/$nil_ang1*100;
					   $sisa11 = $sisa1<0 ? $sisa1*-1 :$sisa1;
					   $a = $sisa1<0 ? '(' :'';
					   $b = $sisa1<0 ? ')' :'';

					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$sql="SELECT SUBSTRING(kd_kegiatan,17,5) kd_kegiatan,kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci($bulan,$anggaran) $where $where1
						  AND SUBSTRING(kd_kegiatan,17,2)='00'
						  GROUP BY SUBSTRING(kd_kegiatan,17,5),kd_rek,nm_rek
						  ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   }
					}
					
					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
										$hasil->free_result();  

					$sql="SELECT '1' kd_rek,'BELANJA LANGSUNG' nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
						  FROM realisasi_jurnal_rinci($bulan,$anggaran) $where 
							AND kd_rek IN('521','522','523')
							UNION ALL
							SELECT kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini
							FROM realisasi_jurnal_rinci($bulan,$anggaran) $where 
							AND kd_rek IN('521','522','523')
							GROUP BY kd_rek,nm_rek
							ORDER BY kd_rek	";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row){
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $leng=strlen($kd_rek);
					  
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					  
					}
					$hasil->free_result(); 

					$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;</td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							   <td align="right" valign="top"> </td> 
							</tr>';
					
					$sql="SELECT SUBSTRING(kd_kegiatan,17,5) kd_kegiatan,kd_rek,nm_rek,SUM(anggaran) anggaran,SUM(sd_bulan_ini) sd_bulan_ini 
					      FROM realisasi_jurnal_rinci($bulan,$anggaran) $where $where1
						  AND SUBSTRING(kd_kegiatan,17,2)!='00'
						  GROUP BY SUBSTRING(kd_kegiatan,17,5),kd_rek,nm_rek
						  ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   }
					}
					
					
					
					
					$cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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
			$data['prev']= $cRet;    
            $judul='LAPORAN_SEMESTER ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
			
}