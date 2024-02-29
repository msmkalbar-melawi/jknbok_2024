<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Cek_data extends CI_Controller {

	function __contruct()
	{	
		parent::__construct();
	}
    
   function cek_spp(){
	
       $cRet ="";
		$cRet .=" TRHSPP yang TIDAK ada di TRDSPP
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spp,tgl_spp, kd_skpd from trhspp a where no_spp not in(select no_spp from trdspp WHERE kd_skpd=a.kd_skpd)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $tgl_spp  = $rowsql1->tgl_spp;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$tgl_spp</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
				
				$cRet .=" TRDSPP yang TIDAK ada di TRHSPP
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spp, kd_skpd from trdspp a where no_spp not in(select no_spp from trhspp WHERE kd_skpd=a.kd_skpd)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
		
		
		$cRet .=" Nilai TRHSPP yang TIDAK SAMA dengan TRDSPP
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>Nilai</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>Nilai</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select * from(
				select a.no_spp,a.n,b.nilai,a.kd_skpd from(
				select sum(nilai) [n],no_spp,kd_skpd from trdspp group by no_spp,kd_skpd
				) as a join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
				) as c where n<>nilai";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $nilai  = $rowsql1->nilai;
                    $n  = $rowsql1->n;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$nilai</b></td>
									<td valign='top' align='left' ><b>$n</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
		
		
		$cRet .=" TRHSPP Nilainya NULL
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spp,tgl_spp, kd_skpd from trhspp WHERE nilai is null";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $tgl_spp  = $rowsql1->tgl_spp;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$tgl_spp</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
				
				$cRet .=" TRDSPP yang Nilainya NULL
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spp, kd_skpd from trdspp where nilai is null";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
		
		
		$cRet .=" TRDSPP Kode Kegiatannya tak ada di RKA
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spp, kd_skpd from trdspp where kd_kegiatan not in (select kd_kegiatan from trdrka)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
		
		$cRet .=" TRHSPP yang Nama dan Kode SKPD kosong
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spp,tgl_spp, kd_skpd from trhspp where nm_skpd='' or kd_skpd=''";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $tgl_spp  = $rowsql1->tgl_spp;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$tgl_spp</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
		
		$cRet .=" TRHSPP yang No SPD-nya tidak ada 
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spp,tgl_spp, kd_skpd from trhspp a where no_spd not in (select no_spd from trhspd WHERE kd_skpd=a.kd_skpd)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spp     = $rowsql1->no_spp;
                    $tgl_spp  = $rowsql1->tgl_spp;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$tgl_spp</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
		
		
			   echo ("<title> Cek SPP</title>");
				echo $cRet;		
				
	}
    

	 function cek_spm(){
	
       $cRet ="";
		$cRet .=" SPM yang no SPP TIDAK COCOK
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spm,tgl_spm, kd_skpd from trhspm a where no_spp not in(select no_spp from trdspp WHERE kd_skpd=a.kd_skpd)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spm   = $rowsql1->no_spm;
                    $tgl_spm = $rowsql1->tgl_spm;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spm</b></td>
									<td valign='top' align='left' ><b>$tgl_spm</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
				
				$cRet .=" Potongan SPM yang salah Nomor
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spm, kd_skpd from trspmpot a where no_spm not in(select no_spm from trhspm WHERE kd_skpd=a.kd_skpd)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spm   = $rowsql1->no_spm;
                    $tgl_spm = $rowsql1->tgl_spm;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spm</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
				
		$cRet .=" TRHSPM yang nilainya NULL
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spm,tgl_spm, kd_skpd from trhspm where nilai is null";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spm   = $rowsql1->no_spm;
                    $tgl_spm = $rowsql1->tgl_spm;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spm</b></td>
									<td valign='top' align='left' ><b>$tgl_spm</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 		
				
			$cRet .=" Potongan SPM yang Nilainya NULL
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select no_spm, kd_skpd from trspmpot where nilai is null";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spm   = $rowsql1->no_spm;
                    $tgl_spm = $rowsql1->tgl_spm;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spm</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 

	$cRet .=" TRHSPM yang tidak sama dengan rincian
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>Nilai SPP</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>Nilai SPM</b></td>
					</tr>";
        $sql1 = "select a.no_spm,a.no_spp,a.nilai [spm],b.nilai from trhspm a join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
where a.nilai<>b.nilai ";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spm   = $rowsql1->no_spm;
                    $no_spp = $rowsql1->no_spp;
                    $spm  = $rowsql1->spm;
                    $nilai  = $rowsql1->nilai;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spm</b></td>
									<td valign='top' align='left' ><b>$no_spp</b></td>
									<td valign='top' align='left' ><b>$spm></td>
									<td valign='top' align='left' ><b>$nilai></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 	

$cRet .=" SPM Kode atau Nama SKPD tidak ada
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SPM</b></td>
					</tr>";
        $sql1 = "select no_spm,tgl_spm, kd_skpd from trhspm where nm_skpd='' or kd_skpd=''";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_spm   = $rowsql1->no_spm;
                    $tgl_spm = $rowsql1->tgl_spm;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_spm</b></td>
									<td valign='top' align='left' ><b>$tgl_spm</b></td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 


				
				echo ("<title> Cek SPM</title>");
				echo $cRet;	
	 }
	 
	 
	 
	 
	 function cek_trans(){
	
       $cRet ="";
		$cRet .=" Kode Rekening Kosong
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>No BKU</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select b.kd_rek5,a.no_bukti,a.kd_skpd from trdtransout a join mrek5 b on a.nm_rek5=b.nm_rek5 where a.kd_rek5='' order by a.kd_skpd";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_bku   = $rowsql1->no_bukti;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_bku</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				 $cRet .="</table> <br/><br/><br/>"; 
		
		
		$cRet .=" TRHTRANSOUT tidak ada di TRDTRANSOUT
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>No BKU</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select a.no_bukti, a.kd_skpd from trhtransout a where no_bukti not in (select no_bukti from trdtransout WHERE kd_skpd=a.kd_skpd)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_bku   = $rowsql1->no_bukti;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_bku</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				 $cRet .="</table> <br/><br/><br/>"; 
				 
				 
		$cRet .=" TRDTRANSOUT tidak ada di TRHTRANSOUT
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>No BKU</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select a.no_bukti, a.kd_skpd from trdtransout a where no_bukti not in (select no_bukti from trhtransout WHERE kd_skpd=a.kd_skpd)";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_bku   = $rowsql1->no_bukti;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_bku</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				 $cRet .="</table> <br/><br/><br/>"; 
				 
				 echo ("<title> Cek Transaksi</title>");
				echo $cRet;	
	 }

	 
	 function cek_rek(){
	
       $cRet ="";
		$cRet .=" Rekening di Transaksi yang tak ada anggaran / Salah Rekening
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>No BKU</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD KEGIATAN</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD REK5</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "select c.*,d.nm_skpd from (
				select a.no_bukti,a.kd_kegiatan,a.kd_rek5,b.kd_rek5 [rka],a.kd_skpd 
				from trdtransout a left join trdrka b on a.kd_kegiatan=b.kd_kegiatan
				and a.kd_rek5=b.kd_rek5
				) c join ms_skpd d on c.kd_skpd=d.kd_skpd 
				where rka is null order by c.kd_skpd
				";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_bku   = $rowsql1->no_bukti;
                    $kd_kegiatan   = $rowsql1->kd_kegiatan;
                    $kd_rek5   = $rowsql1->kd_rek5;
                    $rka   = $rowsql1->rka;
                    $kd_skpd   = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_bku</b></td>
									<td valign='top' align='left' ><b>$kd_kegiatan</b></td>
									<td valign='top' align='left' ><b>$kd_rek5</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				 $cRet .="</table> <br/><br/><br/>"; 
		
		
		
				 
				 echo ("<title> Cek Rekening</title>");
				echo $cRet;	
	 }
	 
	 function list_skpd(){
	
       $cRet ="";
		$cRet .=" 
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"4\" cellpadding=\"4\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>Kode SKPD</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>Nama SKPD</b></td>
					</tr>";
        $sql1 = "Select kd_skpd,nm_skpd FROm ms_skpd Order by kd_skpd
				";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $kd_skpd   = $rowsql1->kd_skpd;
                    $nm_skpd   = $rowsql1->nm_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$kd_skpd</b></td>
									<td valign='top' align='left' ><b>$nm_skpd</b></td>
								</tr>";
					
                 }
				 $cRet .="</table> <br/><br/><br/>"; 
		
		
		
				 
				 echo ("<title> List SKPD</title>");
				echo $cRet;	
	 }
	 
	 
	function cek_sp2d(){
	
       $cRet ="";
		$cRet .=" SP2D dengan SPM Double
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SP2D</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SP2D</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
					</tr>";
        $sql1 = "SELECT no_sp2d, tgl_sp2d, kd_skpd, no_spm FROM trhsp2d WHERE no_spm IN (select no_spm from (
					select no_spm,count(no_spm) [no] from trhsp2d group by no_spm
					) as a where no>1
					)
					";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_sp2d   = $rowsql1->no_sp2d;
                    $no_spm   = $rowsql1->no_spm;
                    $tgl_sp2d = $rowsql1->tgl_sp2d;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_sp2d</b></td>
									<td valign='top' align='left' ><b>$no_spm</b></td>
									<td valign='top' align='left' ><b>$tgl_sp2d</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
				
				
				$cRet .=" SP2D dengan SPM salah
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SP2D</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SP2D</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>KD SKPD</b></td>
					</tr>";
        $sql1 = "SELECT no_sp2d, tgl_sp2d, kd_skpd FROM trhsp2d a  where no_spm not in (select no_spm from trhspm WHERE kd_skpd=a.kd_skpd)
					";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_sp2d   = $rowsql1->no_sp2d;
                    $tgl_sp2d = $rowsql1->tgl_sp2d;
                    $kd_skpd  = $rowsql1->kd_skpd;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_sp2d</b></td>
									<td valign='top' align='left' ><b>$tgl_sp2d</b></td>
									<td valign='top' align='left' ><b>$kd_skpd</td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
				
		
		$cRet .=" SP2D dengan Kode dan Nama SKPD kosong
			<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
					<tr>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SP2D</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>TGL SP2D</b></td>
						<td bgcolor='#CCCCCC' align='center' ><b>NO SPM</b></td>
					</tr>";
        $sql1 = "SELECT no_sp2d, tgl_sp2d, no_spm FROM trhsp2d a  where nm_skpd='' or kd_skpd=''
					";
                 $sql1=$this->db->query($sql1);
                 foreach ($sql1->result() as $rowsql1)
                 {
                    $no_sp2d   = $rowsql1->no_sp2d;
                    $tgl_sp2d = $rowsql1->tgl_sp2d;
                    $no_spm  = $rowsql1->no_spm;
					$cRet .="<tr>
									<td valign='top' align='left' ><b>$no_sp2d</b></td>
									<td valign='top' align='left' ><b>$tgl_sp2d</b></td>
									<td valign='top' align='left' ><b>$no_spm</td>
								</tr>";
					
                 }
				$cRet .="</table> <br/><br/><br/>"; 
				
				
				echo ("<title> Cek SP2D</title>");
				echo $cRet;	
	 }
	 
	  
	 
	 
	 

}
