<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Cetak_buku_tunai extends CI_Controller {
  
    function __construct() 
    {   
        parent::__construct();
    }




function index()
    {
        $data['page_title']= 'Buku Kas Tunai';
        $this->template->set('title', 'Buku Kas Tunai');   
        $this->template->load('template','tukd/transaksi/kas_tunai',$data) ; 
    }


     function  tanggal_format_indonesia($tgl){
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;
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
		case  0:
        return  "-";
        break;
		
    }
    }
    
    function cetak_kas_tunai(){
			$print = $this->uri->segment(3);
			$thn_ang = $this->session->userdata('pcThang');
			$kd_skpd  = $this->session->userdata('kdskpd');
			$bulan= $_REQUEST['tgl1'];
			$spasi= $_REQUEST['spasi'];
			$adinas=$this->db->query("select * from sclient WHERE kd_skpd='$kd_skpd'");
			$dinas=$adinas->row();
			$prov=$dinas->provinsi;
			$daerah=$dinas->daerah;
			$hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
			$trsk = $hasil->row();          
			$nm_skpd = $trsk->nm_skpd;
			
            $lcperiode = $this->tukd_model->getBulan($bulan);
       
			$tgl_ttd= $_REQUEST['tgl_ttd'];
         
			$ttd1 = str_replace('123456789',' ',$_REQUEST['ttd1']);
			$ttd2 = str_replace('123456789',' ',$_REQUEST['ttd2']);
			$csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE kode = 'BK' AND a.kd_skpd = '$kd_skpd' and nip='$ttd1'";
			$hasil = $this->db->query($csql);
			$trh2 = $hasil->row();          
			$lcNmBP = $trh2->nama;
			$lcNipBP = $trh2->nip;
			$lcJabBP = $trh2->jabatan;
			$lcPangkatBP = $trh2->pangkat;
			$csql="SELECT a.nama, a.nip,a.jabatan,a.pangkat FROM ms_ttd a WHERE kode in ('PA','KPA') AND a.kd_skpd = '$kd_skpd' and nip='$ttd2'";
			$hasil = $this->db->query($csql);
			$trh2 = $hasil->row();          
			$lcNmPA = $trh2->nama;
			$lcNipPA = $trh2->nip; 			
			$lcJabPA = $trh2->jabatan; 			
			$lcPangkatPA = $trh2->pangkat; 			
		
		$esteh="SELECT 
				SUM(case when jns=1 then jumlah else 0 end ) AS terima,
				SUM(case when jns=2 then jumlah else 0 end) AS keluar
				FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
				select f.tgl_kas as tgl,f.no_kas as bku,f.keterangan as ket, f.nilai as jumlah, '1' as jns,f.kd_skpd as kode from tr_jpanjar f join tr_panjar g 
                on f.no_panjar_lalu=g.no_panjar and f.kd_skpd=g.kd_skpd where f.jns=2 and g.pay='TUNAI' UNION ALL
				select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai [jumlah],'1' [jns],kd_skpd [kode] from trhtrmpot a 
				where kd_skpd='$kd_skpd' and (pay='' OR pay='TUNAI') and jns_spp in ('1','2','3') union all
				select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar where pay='TUNAI'  UNION ALL
				select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
					from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
					where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TNK'
					GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd				
				UNION ALL
				SELECT	a.tgl_bukti AS tgl,	a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
								FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
								LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
								LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c
								ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
								AND MONTH(a.tgl_bukti)<'$bulan' and a.kd_skpd='$kd_skpd' 
								AND a.no_bukti NOT IN(
								select no_bukti from trhtransout 
								where no_sp2d in 
								(SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
								(SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)<'$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
								GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
						UNION ALL
				SELECT	tgl_bukti AS tgl,	no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
								from trhtransout 
								WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') and no_sp2d in 
								(SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
								(SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)<'$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'
				
				UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' UNION ALL
				SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION ALL
				SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI' union all
				select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],a.nilai [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
				where a.kd_skpd='$kd_skpd' and (pay='' OR pay='TUNAI') and jns_spp in ('1','2','3')
				) a 
				where month(a.tgl)<'$bulan' and kode='$kd_skpd'";
		$hasil = $this->db->query($esteh);
				
		$okok = $hasil->row();  
		 $tox_awal="SELECT isnull(sld_awal,0) AS jumlah FROM ms_skpd where kd_skpd='$kd_skpd'";
		// $tox_awal="SELECT CASE WHEN kd_bayar<>1 THEN isnull(sld_awal,0)+sld_awalpajak ELSE 0 END AS jumlah 
		// 			FROM ms_skpd where kd_skpd='$kd_skpd'";
					 $hasil = $this->db->query($tox_awal);					 
					 $tox = $hasil->row('jumlah');
					 $terima = $okok->terima;
					 $keluar = $okok->keluar;					 
					 $saldotunai=($terima+$tox)-$keluar;
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"6\" style=\"font-size:14px;border: solid 1px white;\"><b>$prov<br>BUKU KAS TUNAI<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;\"></td>
            </tr>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;\"></td>
            </tr>            
            <tr>
                <td align=\"left\" colspan=\"0\" style=\"font-size:12px;\">OPD</td>
                <td align=\"left\" colspan=\"0\" style=\"font-size:12px;\">: $kd_skpd &nbsp; $nm_skpd</td> 
            </tr>
            <tr>
                <td align=\"left\" colspan=\"0\" style=\"font-size:12px;\">PERIODE</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;\">: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;\">&nbsp;</td>
            </tr>
            
           
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;\">&nbsp;</td>
            </tr>
			</table>";

             $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"$spasi\">
            <thead>
			<tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\" style=\"font-size:12px;\" >Tanggal.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"5%\" style=\"font-size:12px;\">No. BKU</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"30%\" style=\"font-size:12px;\">Uraian</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;\">Penerimaan</td> 
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;\">Pengeluaran</td>  
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;\">Saldo</td>            
            </tr> 
			</thead>
			<tr>
			
                <td align=\"center\" style=\"font-size:12px;\" ></td>
                <td align=\"center\" style=\"font-size:12px;\"></td>
                <td align=\"right\" style=\"font-size:12px;\">Saldo Lalu</td>
                <td align=\"center\" style=\"font-size:12px;\"></td> 
                <td align=\"center\" tyle=\"font-size:12px;\"></td>  
				<td align=\"right\" style=\"font-size:12px;\">".number_format($saldotunai,"2",",",".")."</td>            
            </tr> ";
			

				$sql="SELECT * FROM (
						SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
        				select f.tgl_kas as tgl,f.no_kas as bku,f.keterangan as ket, f.nilai as masuk, 0 as keluar,f.kd_skpd as kode from tr_jpanjar f join tr_panjar g 
                        on f.no_panjar_lalu=g.no_panjar and f.kd_skpd=g.kd_skpd where f.jns=2 and g.pay='TUNAI' UNION ALL
						select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai AS masuk,0 AS keluar,kd_skpd [kode] from trhtrmpot a 
						where kd_skpd='$kd_skpd' and (pay='' OR pay='TUNAI') and jns_spp in('1','2','3') union all
						select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, 0 as masuk,nilai as keluar,kd_skpd as kode from tr_panjar where pay='TUNAI' UNION ALL
						select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
								from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
								where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TNK'
								GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd	
						UNION ALL
						SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
								FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
								LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
								LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c
								ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
								AND MONTH(a.tgl_bukti)='$bulan' and a.kd_skpd='$kd_skpd' 
								AND a.no_bukti NOT IN(
								select no_bukti from trhtransout 
								where no_sp2d in 
								(SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
								(SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)='$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
								GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
						UNION ALL
						select tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 AS masuk, ISNULL(total,0)  AS keluar,kd_skpd AS kode 
								from trhtransout 
								WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in 
								(SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
								AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
								(SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
								AND MONTH(tgl_bukti)='$bulan'
								GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
								and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'

						UNION ALL
						SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
						SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
						SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI' union all
						select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],0 as masuk,nilai AS keluar,a.kd_skpd [kode] from trhstrpot a 
						where a.kd_skpd='$kd_skpd' and (pay='' OR pay='TUNAI') and jns_spp in ('1','2','3')
						)a
						where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,CAST(bku AS int)";
                        $hasil = $this->db->query($sql);       
                        $saldo=$saldotunai;
						$total_terima=0;
						$total_keluar=0;
                        foreach ($hasil->result() as $row){
                           $bku =$row->bku ;
                           $tgl =$row->tgl;
                           $uraian  =$row->ket; 
                           $terimatunai   =$row->masuk;
                           $keluartunai   =$row->keluar;
                           $tgl = $this->tukd_model->tanggal_ind($tgl);
                           if ($keluartunai==1){ 
                           $saldo=$saldo+$terimatunai-$keluartunai;
						   $total_terima=$total_terima+$terimatunai;
						   $total_keluar=$total_keluar+$keluartunai;
                           
                            $cRet .="<tr>
							<td valign=\"top\" align=\"center\" style=\"font-size:12px;\">$tgl</td>
							<td valign=\"top\" align=\"center\" style=\"font-size:12px;\">$bku</td>
							<td valign=\"top\" align=\"left\" style=\"font-size:12px;\">$uraian</td>
							<td valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($terimatunai,"2",",",".")."</td>
							<td valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($keluartunai,"2",",",".")."</td>
							<td valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($saldo,"2",",",".")."</td>
                                      </tr>";                     
                           }else{
                           $saldo=$saldo+$terimatunai-$keluartunai;
						   $total_keluar=$total_keluar+$keluartunai;
						   $total_terima=$total_terima+$terimatunai;
                           $cRet .="<tr>
						<td valign=\"top\" align=\"center\" style=\"font-size:12px;\">$tgl</td>
						<td valign=\"top\" align=\"left\" style=\"font-size:12px;\">$bku</td>
						<td valign=\"top\" align=\"left\" style=\"font-size:12px;\">$uraian</td>
						<td valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($terimatunai,"2",",",".")."</td>
						<td valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($keluartunai,"2",",",".")."</td>
						<td valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($saldo,"2",",",".")."</td>
													  </tr>"; 
                                       
                           }           
                        }
		$cRet .="<tr>
        <td bgcolor=\"#CCCCCC\" colspan=\"3\" valign=\"top\" align=\"center\" style=\"font-size:12px;\">JUMLAH</td>
        <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($total_terima,"2",",",".")."</td>
        <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($total_keluar,"2",",",".")."</td>
        <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;\">".number_format($total_terima-$total_keluar+$saldotunai,"2",",",".")."</td>
                                      </tr>"; 				
                 $cRet .="</table>";
      
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
		 <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">Mengetahui:</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$daerah, ".$this->tanggal_format_indonesia($tgl_ttd)."</td>                                                                                                                                                                                
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$lcJabPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"> NIP. $lcNipPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";        
                                  //oke vin
                
        $cRet .='</table>';
         if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Kas Tunai </title>");
			 echo $cRet;
			 }
		 else{
			$this->support->_mpdf('',$cRet,10,10,10,'0',0,'');
			}
    }



}
?>
