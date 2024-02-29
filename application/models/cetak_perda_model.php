<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model 
 style='font-family:\"Open Sans\",-apple-system,BlinkMacSystemFont,\"Segoe UI\",sans-serif; border-collapse:collapse'
 */ 
 

class cetak_perda_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
        error_reporting(0);
    } 
 
function cetak_perda_murni($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji){

        $thn=$this->session->userdata('pcThang');
        $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
                a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
                 ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
        $sqlskpd=$this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns){
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
        } 
        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA";
            $judul="RINGKASAN PENJABARAN APBD YANG DIKLASIFIKASI <br> MENURUT KELOMPOK, JENIS, OBJEK, RINCIAN OBJEK <br> PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perwa";
        }else{
            $lampiran="PERATURAN DAERAH";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT KELOMPOK <br>DAN JENIS PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perda";
        }
        $cRet='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $cRet .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> LAMPIRAN I<br> $lampiran KOTA PONTIANAK <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                     <thead>                       
                        <tr><td bgcolor='#CCCCCC' width='10%' align='center'><b>KODE</b></td>                            
                            <td bgcolor='#CCCCCC' width='70%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' width='20%' align='center'><b>JUMLAH (Rp)</b></td></tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='70%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>3</td>
                        </tr>
                ";

        if($detail=='detail'){
            $rincian="  UNION ALL 

                        SELECT a.kd_rek4 AS kd_rek,a.nm_rek4 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek4 a INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4)))
                        where left(b.kd_rek6,1)='4'  
                        GROUP BY a.kd_rek4, a.nm_rek4  
                        UNION ALL 

                        SELECT a.kd_rek5 AS kd_rek,a.nm_rek5 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek5 a INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5)))
                        where left(b.kd_rek6,1)='4' 
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 

                        SELECT a.kd_rek6 AS kd_rek,a.nm_rek6 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek6 a INNER JOIN trdrka b ON a.kd_rek6=LEFT(b.kd_rek6,(len(a.kd_rek6)))
                        where left(b.kd_rek6,1)='4' 
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}
        
        $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
                INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,1)='4' 
                 GROUP BY a.kd_rek1, a.nm_rek1 

                UNION ALL 

                SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b 
                ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek2,a.nm_rek2 

                UNION ALL 

                SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek ,
                SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
                where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek3, a.nm_rek3 
                $rincian
                ORDER BY kd_rek";
                 
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0){                                  
            foreach ($query->result() as $row){
                    $coba1=$this->support->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"0",",",".");
                   
                    $cRet.= " <tr>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>$coba1</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>$coba2</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba3</td>
                             </tr>";                     
            }
        }else{
                $cRet .= " <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>4</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>PENDAPATAN</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format(0,"0",",",".")."</td>
                          </tr>";
                    
                
        }                                 
                
        $sqltp="SELECT SUM(nilai) AS totp FROM trdrka WHERE LEFT(kd_rek6,1)='4' ";
        $sqlp=$this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp){

            $coba4=number_format($rowp->totp,"0",",",".");
            $cob1=$rowp->totp;
                   
            $cRet    .= "<tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'>Jumlah Pendapatan</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba4</td>
                        </tr>
                        <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>&nbsp;</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'></td>
                        </tr>";
        }

        if($gaji==1){
            $aktifkanGaji="and right(b.kd_sub_kegiatan,10) <> '01.2.02.01' ";
        }else{
            $aktifkanGaji="";
        }

        if($detail=='detail'){
            $rincian="  UNION ALL 
                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek4, a.nm_rek4 
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}     
                $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
                        INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek1, a.nm_rek1 
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->support->dotrek($row1->rek);
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"0",",",".");
                   
                     $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>$coba5</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>$coba6</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba7</td></tr>";
                }

                if($gaji==1){
                    $aktifkanGaji="and right(kd_sub_kegiatan,10) <> '01.2.02.01' ";
                }else{
                    $aktifkanGaji="";
                }     

                $sqltb="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='5' $aktifkanGaji";
                $sqlb=$this->db->query($sqltb);
                foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,"0",",",".");
                    $cob=$rowb->totb;
                    $cRet    .= " <tr>                                   
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='80%' align='right'>Jumlah Belanja</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba8</td></tr>";
                 }
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td></tr>";

                  
                  $surplus=$cob1-$cob; 
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Surplus/Defisit</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($surplus)."</td></tr>"; 

                    
                $sqltpm="SELECT isnull(SUM(nilai),0) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='6' ";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                   $coba12=number_format($rowtpm->totb,"0",",",".");
                    $cobtpm=$rowtpm->totb;
                    if($cobtpm>0){
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td></tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>6</td>                                     
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>Pembiayaan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba12</td>
                                    </tr>";

                        if($detail=='detail'){
                            $rincian="  UNION ALL 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            $coba10=$rowpm->nm_rek;
                            $coba11= number_format($rowpm->nilai,"0",",",".");
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba11</td></tr>";
                        } 


                        $sqltpm="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='61' ";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                           $coba12=number_format($rowtpm->totb,"0",",",".");
                                            $cobtpm=$rowtpm->totb;
                                            $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba12</td></tr>";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            $coba10=$rowpk->nm_rek;
                            $coba11= number_format($rowpk->nilai,"0",",",".");
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba11</td></tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='62'";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                   $cobatpk=number_format($rowtpk->totb,"0",",",".");
                    $cobtpk=$rowtpk->totb;

                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$cobatpk</td></tr>";
                 }
    
                $pnetto=$cobtpm-$cobtpk;
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'></td></tr>";                                                      

                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Pembiayaan Netto</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($pnetto)."</td></tr>";                                                      
                    

                    } /*end if pembiayaan 0*/
                $silpa=($cobtpm-$cobtpk)+($surplus);
    
                $pnetto=$cobtpm-$cobtpk;
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'></td></tr>";     
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'> SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($silpa)."</td></tr></table>";                                                      
                    
                } 
                  
                $cRet    .= "</table>";
        if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    

            $cRet.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        }
       
        $data['prev']= $cRet;    
        $judul         = 'RKA SKPD';
        switch($cetak) { 
        case 1;
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'0');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            //$this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 3;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 0;
        echo ("<title>RKA SKPD</title>");
        echo($cRet);
        break;
        }
                
    } 
    function cetak_perda_murni_ak($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji){

        $thn=$this->session->userdata('pcThang');
        $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
                a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
                 ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
        $sqlskpd=$this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns){
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
        } 
        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA";
            $judul="RINGKASAN PENJABARAN APBD YANG DIKLASIFIKASI <br> MENURUT KELOMPOK, JENIS, OBJEK, RINCIAN OBJEK <br> PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perwa";
        }else{
            $lampiran="PERATURAN DAERAH";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT KELOMPOK <br>DAN JENIS PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perda";
        }
        $cRet='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $cRet .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> LAMPIRAN I<br> $lampiran KOTA PONTIANAK <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                     <thead>                       
                        <tr><td bgcolor='#CCCCCC' width='10%' align='center'><b>KODE</b></td>                            
                            <td bgcolor='#CCCCCC' width='10%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' width='10%' align='center'><b>JUMLAH ANGGARAN(Rp)</b></td>
							<td bgcolor='#CCCCCC' width='10%' align='center'><b>JUMLAH REALISASI(Rp)</b></td>
							<td bgcolor='#CCCCCC' width='5%' align='center'><b>Bertambah/berkurang</b></td>
							<td bgcolor='#CCCCCC' width='5%' align='center'><b>Persentase(%)</b></td></tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>3</td>
							<td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>4</td>
							<td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>5</td>
							<td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>6</td>
                        </tr>
                ";

        if($detail=='detail'){
            $rincian="  UNION ALL 

                        SELECT a.kd_rek4 AS kd_rek,a.nm_rek4 AS nm_rek ,
                        SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek4 a INNER JOIN data_realisasi_pemkot b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4)))
                        where left(b.kd_rek6,1)='4'  
                        GROUP BY a.kd_rek4, a.nm_rek4  
                        UNION ALL 

                        SELECT a.kd_rek5 AS kd_rek,a.nm_rek5 AS nm_rek ,
                        SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek5 a INNER JOIN data_realisasi_pemkot b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5)))
                        where left(b.kd_rek6,1)='4'  
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 

                        SELECT a.kd_rek6 AS kd_rek,a.nm_rek6 AS nm_rek ,
                        SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek6 a INNER JOIN data_realisasi_pemkot b ON a.kd_rek6=LEFT(b.kd_rek6,(len(a.kd_rek6)))
                        where left(b.kd_rek6,1)='4' 
                        GROUP BY a.kd_rek6, a.nm_rek6
						
						
						
";
        }else{ $rincian='';}
        
        $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai_ang)  AS nilai,SUM ( b.real_spj ) AS nilai_sempurna FROM ms_rek1 a 
                INNER JOIN data_realisasi_pemkot b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,1)='4' 
                 GROUP BY a.kd_rek1, a.nm_rek1 

                UNION ALL 

                SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai_ang)  AS nilai,SUM ( b.real_spj ) AS nilai_sempurna FROM ms_rek2 a INNER JOIN data_realisasi_pemkot b 
                ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek2,a.nm_rek2 

                UNION ALL 

                SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek ,
                SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_sempurna FROM ms_rek3 a INNER JOIN data_realisasi_pemkot b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
                where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek3, a.nm_rek3 
                $rincian
                ORDER BY kd_rek";
        $realisasi=0;
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0){                                  
            foreach ($query->result() as $row){
                    $coba1=$this->support->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= $row->nilai;
					$realisasi= $row->nilai_sempurna;
					$tamkur=$coba3-$realisasi;
					
					$persentase=0;
					 if ($realisasi==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($coba3-$tamkur)/$tamkur)*100;
						   
					   }
                   
                    $cRet.= " <tr>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>$coba1</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>$coba2</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($coba3,"0",",",".")."</td>
								<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($realisasi,"0",",",".")."</td>
								<td align='right'>".number_format($tamkur,"0",",",".")."</td>
								<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='5%' align='right'>".number_format($persentase,"0",",",".")."</td>
                             </tr>";                     
            }
        }else{
                $cRet .= " <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>4</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>PENDAPATAN</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format(0,"0",",",".")."</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format(0,"0",",",".")."</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format(0,"0",",",".")."</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format(0,"0",",",".")."</td>
                          </tr>";
                    
                
        }                                 
                
        $sqltp="SELECT SUM(nilai_ang) AS totp,sum(real_spj) as datarealisasi from data_realisasi_pemkot where LEFT(kd_rek6,1)='4'";
        $sqlp=$this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp){
			
			$datareall_1=$rowp->datarealisasi;
            $coba4=$rowp->totp;
			$cob1=$rowp->totp;
			$coba455=$rowp->datarealisasi;
			$tamkur2=$coba4-$coba455;
					
					$persentase=0;
					 if ($tamkur2==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($coba4-$tamkur2)/$tamkur2)*100;
						   
					   }
			
                   
            $cRet    .= "<tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'>Jumlah Pendapatan</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($coba4,"0",",",".")."</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($coba455,"0",",",".")."</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($tamkur2,"0",",",".")."</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($persentase,"0",",",".")."</td>
                        </tr>
                        <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>&nbsp;</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>&nbsp;</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>&nbsp;</td>
							<td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>&nbsp;</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'></td>
                        </tr>";
        }

        if($gaji==1){
            $aktifkanGaji="and right(b.kd_sub_kegiatan,10) <> '01.2.02.01' ";
        }else{
            $aktifkanGaji="";
        }

        if($detail=='detail'){
            $rincian="  UNION ALL 
                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek4 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek4, a.nm_rek4 
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek5 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek6 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}     
                $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek1 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek1, a.nm_rek1 
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek2 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM ( b.real_spj ) AS nilai_realisasi FROM ms_rek3 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->support->dotrek($row1->rek);
                    $coba6=$row1->nm_rek;
                    $coba7= $row1->nilai;
					$coba755=$row1->nilai_realisasi;
					$datareal2=$row1->nilai_realisasi;
					$tamkur3=$coba7-$coba755;
					
					$persentase=0;
					 if ($tamkur3==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($coba7-$tamkur3)/$tamkur3)*100;
						   
					   }
			
                   
                     $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>$coba5</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>$coba6</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($coba7,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($coba755,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($tamkur3,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($persentase,"0",",",".")."</td></tr>";
                }

                if($gaji==1){
                    $aktifkanGaji="and right(kd_sub_kegiatan,10) <> '01.2.02.01' ";
                }else{
                    $aktifkanGaji="";
                }     

                $sqltb="SELECT SUM(nilai_ang) AS totb,sum(real_spj) as nilai_realisasi FROM data_realisasi_pemkot WHERE LEFT(kd_rek6,1)='5' $aktifkanGaji";
                $sqlb=$this->db->query($sqltb);
                foreach ($sqlb->result() as $rowb)
                {
                   $coba8=$rowb->totb;
                    $cob=$rowb->totb;
					$coba855=$rowb->nilai_realisasi;
                    $datareal3=$rowb->nilai_realisasi;
					$tamkur4=$coba8-$coba855;
					
					$persentase=0;
					 if ($tamkur4==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($coba8-$tamkur4)/$tamkur4)*100;
						   
					   }
                    $cRet    .= " <tr>                                   
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='80%' align='right'>Jumlah Belanja</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($coba8,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($coba855,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($tamkur4,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($persentase,"0",",",".")."</td></tr>";
                 }
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td></tr>";

                  
                  $surplus=$cob1-$cob; 
				 $surplus2=$datareall_1-$datareal2;
				 $tamkur5=$surplus-$surplus2;
					
					$persentase=0;
					 if ($tamkur5==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($surplus-$tamkur5)/$tamkur5)*100;
						   
					   }
				 
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Surplus/Defisit</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($surplus)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($surplus2)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($tamkur5)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($persentase)."</td></tr>"; 

                    
                $sqltpm="SELECT isnull(SUM(nilai_ang),0) AS totb,isnull(SUM(real_spj),0) as nilai_realisasi FROM data_realisasi_pemkot WHERE LEFT(kd_rek6,1)='6' ";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                   $coba12=number_format($rowtpm->totb,"0",",",".");
                    $cobtpm=$rowtpm->totb;
					$coba1255=number_format($rowtpm->nilai_realisasi,"0",",",".");
                    $datareal3=$rowtpm->nilai_realisasi;
                    if($cobtpm>0){
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td>
									 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;</td></tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>6</td>                                     
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>Pembiayaan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba12</td>
										 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba1255</td>
										 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba12</td>
										 <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba12</td>
                                    </tr>";

                        if($detail=='detail'){
                            $rincian="  UNION ALL 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM(b.real_spj) AS nilai_realisasi FROM ms_rek4 a 
                                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM(b.real_spj) AS nilai_realisasi FROM ms_rek5 a 
                                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM(b.real_spj) AS nilai_realisasi FROM ms_rek6 a 
                                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM(b.real_spj) AS nilai_realisasi FROM ms_rek2 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai_ang) AS nilai,SUM(b.real_spj) AS nilai_realisasi FROM ms_rek3 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            $coba10=$rowpm->nm_rek;
                            $coba11= $rowpm->nilai;
							$coba1155= $rowpm->nilai_realisasi;
                            $tamkur6=$coba11-$coba1155;
					
					$persentase=0;
					 if ($tamkur6==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($coba11-$tamkur6)/$tamkur6)*100;
						   
					   }
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($coba11,"0",",",".")."</td>
											 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($coba1155,"0",",",".")."</td>
											 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($tamkur6,"0",",",".")."</td>
											 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($persentase,"0",",",".")."</td></tr>";
                        } 


                        $sqltpm="SELECT SUM(nilai_ang) AS totb,sum(real_spj) as nilai_realisasi FROM data_realisasi_pemkot WHERE LEFT(kd_rek6,2)='61' ";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                           $coba12=$rowtpm->totb;
                                            $cobtpm=$rowtpm->totb;
											$coba1255=$rowtpm->nilai_realisasi;
                                            $cobtpm5=$rowtpm->nilai_realisasi;
											$tamkur10=$coba12-$coba1255;
					
					$persentase=0;
					 if ($tamkur10==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($coba12-$tamkur10)/$tamkur10)*100;
						   
					   }
                                            $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($coba12,"0",",",".")."</td>
															 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($coba1255,"0",",",".")."</td>
															 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($tamkur10,"0",",",".")."</td>
															 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($persentase,"0",",",".")."</td></tr>";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai_ang) AS nilai,sum(b.real_spj) as nilai_realisasi FROM ms_rek4 a 
                                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai_ang) AS nilai,sum(b.real_spj) as nilai_realisasi FROM ms_rek5 a 
                                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai_ang) AS nilai,sum(b.real_spj) as nilai_realisasi FROM ms_rek6 a 
                                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai_ang) AS nilai,sum(b.real_spj) as nilai_realisasi FROM ms_rek2 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai_ang) AS nilai,sum(b.real_spj) as nilai_realisasi FROM ms_rek3 a 
                        INNER JOIN data_realisasi_pemkot b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            $coba10=$rowpk->nm_rek;
                            $coba11= $rowpk->nilai;
							$coba1155= $rowpk->nilai_realisasi;
							$tamkur7=$coba11-$coba1155;
					
					$persentase=0;
					 if ($coba1155==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($coba11-$tamkur7)/$tamkur7)*100;
						   
					   }
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($coba11,"0",",",".")."</td>
											 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($coba1155,"0",",",".")."</td>
											 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($tamkur7,"0",",",".")."</td>
											 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($persentase,"0",",",".")."</td></tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai_ang) AS totb,sum(real_spj) as nilai_realisasi FROM data_realisasi_pemkot WHERE LEFT(kd_rek6,2)='62'";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                   $cobatpk=$rowtpk->totb;
                    $cobtpk=$rowtpk->totb;
					$cobatp5=$rowtpk->nilai_realisasi;
                    $cobtpreal=$rowtpk->nilai_realisasi;
					$tamkur8=$cobatpk-$cobtpreal;
					$persentase=0;
					 if ($coba1155==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($cobatpk-$tamkur8)/$tamkur8)*100;
						   
					   }

                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($cobatpk,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($cobtpreal,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($tamkur8,"0",",",".")."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($persentase,"0",",",".")."</td></tr>";
                 }
    
                $pnetto=$cobtpm-$cobtpk;
				$pnetto2=$cobtpm5-$cobtpreal;
				$tamkur9=$pnetto-$pnetto2;
					$persentase=0;
					 if ($pnetto2==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($pnetto-$tamkur9)/$tamkur9)*100;
						   
					   }
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
									 <td colspan='1' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
									 <td colspan='1' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
									 <td colspan='1' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'></td></tr>";                                                      

                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Pembiayaan Netto</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($pnetto)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($pnetto2)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($tamkur9)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($persentase)."</td></tr>";                                                      
                    

                    } /*end if pembiayaan 0*/
                $silpa=($cobtpm-$cobtpk)+($surplus);
				$silpa2=($cobtpm5-$cobatp5)+($surplus2);
                $tamkur10=$silpa-$silpa2;
				$persentase=0;
					 if ($silpa2==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($silpa-$tamkur10)/$tamkur10)*100;
						   
					   }
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
									 <td colspan='1' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
									 <td colspan='1' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
									 <td colspan='1' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'></td></tr>";     
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'> SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($silpa)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($silpa2)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($tamkur10)."</td>
									 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($persentase)."</td></tr></table>";                                                      
                    
                } 
                  
                $cRet    .= "</table>";
        if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    

            $cRet.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        }
       
        $data['prev']= $cRet;    
        $judul         = 'RKA SKPD';
        switch($cetak) { 
        case 1;
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'0');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            //$this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 3;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 0;
        echo ("<title>RKA SKPD</title>");
        echo($cRet);
        break;
        }
                
    } 

    function cetak_perda_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji,$status1,$status2){

        $thn=$this->session->userdata('pcThang');
        $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
                a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
                 ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
        $sqlskpd=$this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns){
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
        } 
        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA";
            $judul="RINGKASAN PENJABARAN APBD YANG DIKLASIFIKASI <br> MENURUT KELOMPOK, JENIS, OBJEK, RINCIAN OBJEK <br> PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perwa";
        }else{
            $lampiran="PERATURAN DAERAH";
            $judul="RINGKASAN PERUBAHAN APBD";
            $lam="perda";
        }

        $jenis_anggaran="1";
        if($status1=="nilai"){
            $status_anggaran1="";
        }else if($status1=="nilai_ubah"){
            $status_anggaran1="_ubah";
            $jenis_anggaran="3";
        }else{
            $status_anggaran1=$status1;
            $jenis_anggaran="2";
        }
        $order="desc";
        if($status2=="nilai"){
            $status_anggaran2="";
        }else if($status2=="nilai_ubah"){
            $status_anggaran2="_ubah";
            $jenis_anggaran="3";
        }else if($status2=="sempurna2"){
            $status_anggaran2=$status2;
            $jenis_anggaran="2";
            $order="";
        }else{
            $status_anggaran2=$status2;
            $jenis_anggaran="2";

        }

        $cRet='';
        $nomor=""; $tgl_lam=""; $isi='';
        $exc=$this->db->query("SELECT top 1 nomor, isi, tanggal from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='$lam' order by no_konfig $order");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi    =$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $cRet .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> LAMPIRAN I<br> $lampiran KABUPATEN SANGGAU <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KABUPATEN SANGGAU <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                     <thead>                       
                        <tr>
                            <td bgcolor='#CCCCCC' rowspan='2' width='10%' align='center'><b>KODE</b></td>                            
                            <td bgcolor='#CCCCCC' rowspan='2' width='35%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>JUMLAH (Rp)</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='25%' align='center'><b>BERKURANG/ BERTAMBAH</b></td>
                        </tr>
                        <tr>
                            <td  bgcolor='#CCCCCC' width='15%' align='center'><b>SEBELUM PERUBAHAN</td>                            
                            <td  bgcolor='#CCCCCC' width='15%' align='center'><b>SETELAH PERUBAHAN</td>
                            <td  bgcolor='#CCCCCC' width='15%' align='center'><b>Rp</td>
                            <td  bgcolor='#CCCCCC' width='10%' align='center'><b>%</td>   
                        </tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='35%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>3</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>4</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>5</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>6</td>
                        </tr>
                ";


        if($detail=='detail'){
            $rincian="  UNION ALL "."

                        SELECT a.kd_rek4 AS kd_rek,a.nm_rek4 AS nm_rek ,
                        SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek4 a INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4)))
                        where left(b.kd_rek6,1)='4'  
                        GROUP BY a.kd_rek4, a.nm_rek4  
                        UNION ALL 

                        SELECT a.kd_rek5 AS kd_rek,a.nm_rek5 AS nm_rek ,
                        SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek5 a INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5)))
                        where left(b.kd_rek6,1)='4' 
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 

                        SELECT a.kd_rek6 AS kd_rek,a.nm_rek6 AS nm_rek ,
                        SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek6 a INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6
                        where left(b.kd_rek6,1)='4' 
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}
        
        $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek1 a 
                INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,1)='4' 
                 GROUP BY a.kd_rek1, a.nm_rek1 

                UNION ALL 

                SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek2 a INNER JOIN trdrka b 
                ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek2,a.nm_rek2 

                UNION ALL 

                SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek ,
                SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
                where left(b.kd_rek6,1)='4'  
                GROUP BY a.kd_rek3, a.nm_rek3 
                $rincian
                ORDER BY kd_rek";
                 
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0){                                  
            foreach ($query->result() as $row){
                    $coba1=$this->support->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"0",",",".");
                    $coba32= number_format($row->nilai_sempurna,"0",",",".");
                    $selisih=$this->support->format_bulat($row->nilai_sempurna-$row->nilai);

                    if($row->nilai==0){
                        $persen=$this->support->format_bulat(0);
                    }else{
                        $persen=$this->support->format_bulat((($row->nilai_sempurna-$row->nilai)/$row->nilai)*100);
                    }

                   
                    $cRet.= " <tr>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>$coba1</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >$coba2</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba3</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba32</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                             </tr>";                     
            }
        }else{
                $cRet .= " <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>4</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >PENDAPATAN</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"0",",",".")."</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"0",",",".")."</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"0",",",".")."</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"0",",",".")."</td>
                          </tr>";
                    
                
        }                                 
                
        $sqltp="SELECT SUM(nilai$status_anggaran1) AS totp, SUM(nilai$status_anggaran2) AS totp2 FROM trdrka WHERE LEFT(kd_rek6,1)='4' ";
        $sqlp=$this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp){

            $coba4=number_format($rowp->totp,"0",",",".");
            $coba42=number_format($rowp->totp2,"0",",",".");
            $cob1=$rowp->totp;
            $cob12=$rowp->totp2;

                    $selisih=$this->support->format_bulat($rowp->totp2-$rowp->totp);

                    if($rowp->totp==0){
                        $persen=$this->support->format_bulat(0);
                    }else{
                        $persen=$this->support->format_bulat((($rowp->totp2-$rowp->totp)/$rowp->totp)*100);
                    }

                   
            $cRet    .= "<tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>Jumlah Pendapatan</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba4</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba42</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih </td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                        </tr>
                        <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >&nbsp;</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>                            
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >&nbsp;</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>
                        </tr>";
        }

        if($gaji==1){
            $aktifkanGaji="and right(b.kd_sub_kegiatan,10) <> '01.2.02.01' ";
        }else{
            $aktifkanGaji="";
        }

        if($detail=='detail'){
            $rincian="  UNION ALL "."
                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek4 a 
                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek4, a.nm_rek4 
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek5 a 
                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek6 a 
                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}     
                $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek1 a 
                        INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek1, a.nm_rek1 
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5'  $aktifkanGaji
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->support->dotrek($row1->rek);
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"0",",",".");
                    $coba72= number_format($row1->nilai_sempurna,"0",",",".");

                    $selisih=$this->support->format_bulat($row1->nilai_sempurna-$row1->nilai);

                    if($row1->nilai==0){
                        $persen=$this->support->format_bulat(0);
                    }else{
                        $persen=$this->support->format_bulat((($row1->nilai_sempurna-$row1->nilai)/$row1->nilai)*100);
                    }

                     $cRet    .= " <tr>
                                     <td  align='left'>$coba5</td>                                     
                                     <td >$coba6</td>
                                     <td  align='right'>$coba7</td>
                                     <td  align='right'>$coba72</td>                                     
                                     <td  align='right'>$selisih</td>
                                     <td  align='right'>$persen</td>
                                    </tr>";
                }

                if($gaji==1){
                    $aktifkanGaji="and right(kd_sub_kegiatan,10) <> '01.2.02.01' ";
                }else{
                    $aktifkanGaji="";
                }     

                $sqltb="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,1)='5' $aktifkanGaji";
                $sqlb=$this->db->query($sqltb);
                foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,"0",",",".");
                   $coba82=number_format($rowb->totb2,"0",",",".");
                    $cob=$rowb->totb;
                    $cob2=$rowb->totb2;
                    $selisih=$this->support->format_bulat($rowb->totb2-$rowb->totb);

                    if($rowb->totb==0){
                        $persen=$this->support->format_bulat(0);
                    }else{
                        $persen=$this->support->format_bulat((($rowb->totb2-$rowb->totb)/$rowb->totb)*100);
                    }

                    $cRet    .= " <tr>                                   
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>Jumlah Belanja</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$coba8</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$coba82</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'> $selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'> $persen</td>
                                </tr>";
                 }
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>&nbsp;</td>
                                </tr>";

                  
                   $surplus=$cob1-$cob;
                   $surplus2=$cob12-$cob2; 
                    $selisih=$this->support->format_bulat($surplus2-$surplus);

                    if($surplus==0){
                        $persen=$this->support->format_bulat(0);
                    }else{
                        $persen=$this->support->format_bulat((($surplus2-$surplus)/$surplus)*100);
                    } 
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Surplus/Defisit</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->support->format_bulat($surplus)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>".$this->support->format_bulat($surplus2)."</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$persen</td>
                                    </tr>"; 

                    
                $sqltpm="SELECT isnull(SUM(nilai$status_anggaran1),0) AS totb,  isnull(SUM(nilai$status_anggaran2),0) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,1)='6' ";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                    $coba12=number_format($rowtpm->totb,"0",",",".");
                    $coba122=number_format($rowtpm->totb2,"0",",",".");
                    $cobtpm=$rowtpm->totb;
                    $cobtpm2=$rowtpm->totb2;
                    $selisih=$this->support->format_bulat($cobtpm2-$cobtpm);

                    if($cobtpm==0){
                        $persen=$this->support->format_bulat(0);
                    }else{
                        $persen=$this->support->format_bulat((($cobtpm2-$cobtpm)/$cobtpm)*100);
                    } 

                    if($cobtpm>0){
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>&nbsp;</td>
                                 </tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='left'>6</td>                                     
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'>Pembiayaan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$coba12</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$coba122</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' align='right'>$persen</td>
                                    </tr>";

                        if($detail=='detail'){
                            $rincian="  UNION ALL "."
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61'  
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            $coba10=$rowpm->nm_rek;
                            $coba11= number_format($rowpm->nilai,"0",",",".");
                            $coba112= number_format($rowpm->nilai_sempurna,"0",",",".");

                            $selisih=$this->support->format_bulat($rowpm->nilai_sempurna-$rowpm->nilai);

                            if($rowpm->nilai==0){
                                $persen=$this->support->format_bulat(0);
                            }else{
                                $persen=$this->support->format_bulat((($rowpm->nilai_sempurna-$rowpm->nilai)/$rowpm->nilai)*100);
                            }
                             $cRet    .= " <tr>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba112</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                            </tr>";
                        } 


                        $sqltpm="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='61' ";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                           $coba12=number_format($rowtpm->totb,"0",",",".");
                                           $coba122=number_format($rowtpm->totb2,"0",",",".");
                                            $cobtpm=$rowtpm->totb;
                                            $cobtpm2=$rowtpm->totb2;

                                            $selisih=$this->support->format_bulat($cobtpm2-$cobtpm);

                                            if($cobtpm==0){
                                                $persen=$this->support->format_bulat(0);
                                            }else{
                                                $persen=$this->support->format_bulat((($cobtpm2-$cobtpm)/$cobtpm)*100);
                                            }

                                            $cRet    .= " <tr>
                                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba12</td>
                                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba122</td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'> $selisih</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                                         </tr>";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL "."
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62'  
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62'  GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai_sempurna FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62'  
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            $coba10=$rowpk->nm_rek;
                            $coba11= number_format($rowpk->nilai,"0",",",".");
                            $coba112= number_format($rowpk->nilai_sempurna,"0",",",".");
                                            $selisih=$this->support->format_bulat($rowpk->nilai_sempurna-$rowpk->nilai);

                                            if($rowpk->nilai==0){
                                                $persen=$this->support->format_bulat(0);
                                            }else{
                                                $persen=$this->support->format_bulat((($rowpk->nilai_sempurna-$rowpk->nilai)/$rowpk->nilai)*100);
                                            }
                             $cRet    .= " <tr>
                                             <td style='vertical-align:top;border-top: solid 1px black;' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$coba112</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$persen</td>
                                            </tr>";
                        } 


                    $sqltpk="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='62'";
                    $sqltpk=$this->db->query($sqltpk);

                 foreach ($sqltpk->result() as $rowtpk)
                {
                    $cobatpk=number_format($rowtpk->totb,"0",",",".");
                    $cobatpk2=number_format($rowtpk->totb2,"0",",",".");
                    $cobtpk=$rowtpk->totb;
                    $cobtpk2=$rowtpk->totb2;

                    $selisih=$this->support->format_bulat($cobtpk2-$cobtpk);

                    if($cobtpk==0){
                        $persen=$this->support->format_bulat(0);
                    }else{
                        $persen=$this->support->format_bulat((($cobtpk2-$cobtpk)/$cobtpk)*100);
                    }

                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$cobatpk</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$cobatpk2</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                 </tr>";
                 }
    
                $pnetto =$cobtpm-$cobtpk;
                $pnetto2=$cobtpm2-$cobtpk2;
                $selisih=$this->support->format_bulat($pnetto2-$pnetto);

                if($pnetto==0){
                    $persen=$this->support->format_bulat(0);
                }else{
                    $persen=$this->support->format_bulat((($pnetto2-$pnetto)/$pnetto)*100);
                }

                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' >&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                 </tr>";                                                      

                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' >Pembiayaan Netto</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->support->format_bulat($pnetto)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->support->format_bulat($pnetto2)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                 </tr>";                                                      
                    

                    } /*end if pembiayaan 0*/
                $silpa =($cobtpm-$cobtpk)  +($surplus);
                $silpa2=($cobtpm2-$cobtpk2)+($surplus2);
                $selisih=$this->support->format_bulat($silpa-$silpa2);

                if($silpa2==0){
                    $persen=$this->support->format_bulat(0);
                }else{
                    $persen=$this->support->format_bulat((($silpa2-$silpa)/$silpa)*100);
                }
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' >&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                </tr>";     
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' > SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->support->format_bulat($silpa)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->support->format_bulat($silpa2)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                </tr>

                                </table>";                                                      
                    
                } 
                  
                $cRet    .= "</table>";
        if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    

            $cRet.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        }
       
        $data['prev']= $cRet;    
        $judul         = 'RKA SKPD';
        switch($cetak) { 
        case 1;
             $this->master_pdf->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            //$this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 3;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= RINGKASAN APBD YANG DIKLASIFIKASI.xls");
            echo $cRet;
        break;
        case 0;
        echo ("<title>RKA SKPD</title>");
        echo($cRet);
        break;
        }
                
    } 
    function lampiran2_pergeseran($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2){

        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA";
            $judul   ="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam     ="perwa";
        }else{
            $lampiran="PERATURAN DAERAH";
            $judul   ="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam     ="perda";
        }

        $tbl='';
        $nomor="";
        $tgl_lam="";
        $isi="";

        if($status_anggaran2=='nilai'){
            $jenis_anggaran='1';
        }else if($status_anggaran2=='nilai_sempurna'){
            $jenis_anggaran='2';
        }else{
            $jenis_anggaran='3';
        }

        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='$lam'");
        
        foreach($exc->result() as $abc ){
            $nomor  =$abc->nomor;
            $isi    =$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

   $tbl .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> LAMPIRAN II<br> $lampiran KOTA PONTIANAK <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='4' colspan='3' align='center' ><b>Kode</td>
                    <td rowspan='4' align='center' ><b>Urusan Pemerintah Daerah</td>
                    <td colspan='4' align='center' ><b>Pendapatan</td>
                    <td colspan='12' align='center' ><b>Belanja</td>
               </tr>
               <tr>
                    <td rowspan='2' align='center'><b>Sebelum Perubahan</td>
                    <td rowspan='2' align='center'><b>Setelah Perubahan</td>
                    <td rowspan='2' colspan='2' align='center'><b>Bertambah/(Berkurang)</td>
                    <td colspan='5' align='center'><b>Sebelum Perubahan</td>
                    <td colspan='5' align='center'><b>Setelah Perubahan</td>
                    <td rowspan='2' colspan='2' align='center'><b>Bertambah/(Berkurang)</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center'><b>Jumlah Belanja</td>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center'><b>Jumlah Belanja</td>
               </tr>
               <tr>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>%</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>Rp</td>
                    <td align='center'><b>%</td>
               </tr>
                </thead>
               <tr>
                    <td align='center' colspan='3'>1</td>
                    <td align='center'>2</td>
                    <td align='center'>3</td>
                    <td align='center'>4</td>
                    <td align='center'>5</td>
                    <td align='center'>6</td>
                    <td align='center'>7</td>
                    <td align='center'>8</td>
                    <td align='center'>9</td>
                    <td align='center'>10</td>
                    <td align='center'>11</td>
                    <td align='center'>12</td>
                    <td align='center'>13</td>
                    <td align='center'>14</td>
                    <td align='center'>15</td>
                    <td align='center'>16</td>
                    <td align='center'>17</td>
                    <td align='center'>18</td>
               </tr>


               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $tot4_sempurna=0; $tot51_sempurna=0; $tot52_sempurna=0; $tot53_sempurna=0; $tot54_sempurna=0; $tottot_sempurna=0;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* from 
        (
        select * from (
        select '1' urut,'' kd_skpd, (select nm_urusan from ms_urusan WHERE kd_urusan=left(kd_sub_kegiatan,1)) bidurusan, left(kd_sub_kegiatan,1) kd, 
        isnull(sum(case when left(kd_rek6,1)=4 then $status_anggaran1 else 0 end),0) pen,
        isnull(sum(case when left(kd_rek6,2)=51 then $status_anggaran1 else 0 end),0) b51,
        isnull(sum(case when left(kd_rek6,2)=52 then $status_anggaran1 else 0 end),0) b52,
        isnull(sum(case when left(kd_rek6,2)=53 then $status_anggaran1 else 0 end),0) b53,
        isnull(sum(case when left(kd_rek6,2)=54 then $status_anggaran1 else 0 end),0) b54,
        isnull(sum(case when left(kd_rek6,1)=5 then $status_anggaran1 else 0 end),0) tot,
        isnull(sum(case when left(kd_rek6,1)=4 then $status_anggaran2 else 0 end),0) pen_sempurna,
        isnull(sum(case when left(kd_rek6,2)=51 then $status_anggaran2 else 0 end),0) b51_sempurna,
        isnull(sum(case when left(kd_rek6,2)=52 then $status_anggaran2 else 0 end),0) b52_sempurna,
        isnull(sum(case when left(kd_rek6,2)=53 then $status_anggaran2 else 0 end),0) b53_sempurna,
        isnull(sum(case when left(kd_rek6,2)=54 then $status_anggaran2 else 0 end),0) b54_sempurna,
        isnull(sum(case when left(kd_rek6,1)=5 then $status_anggaran2 else 0 end),0) tot_sempurna
        from trdrka 
        GROUP BY left(kd_sub_kegiatan,1) 
        UNION all
        select '2' urut,'' kd_skpd, (select nm_bidang_urusan from ms_bidang_urusan WHERE kd_bidang_urusan=left(kd_sub_kegiatan,4)) bidurusan, left(kd_sub_kegiatan,4) kd, 
        isnull(sum(case when left(kd_rek6,1)=4 then $status_anggaran1 else 0 end),0) pen,
        isnull(sum(case when left(kd_rek6,2)=51 then $status_anggaran1 else 0 end),0) b51,
        isnull(sum(case when left(kd_rek6,2)=52 then $status_anggaran1 else 0 end),0) b52,
        isnull(sum(case when left(kd_rek6,2)=53 then $status_anggaran1 else 0 end),0) b53,
        isnull(sum(case when left(kd_rek6,2)=54 then $status_anggaran1 else 0 end),0) b54,
        isnull(sum(case when left(kd_rek6,1)=5 then $status_anggaran1 else 0 end),0) tot,
        isnull(sum(case when left(kd_rek6,1)=4 then $status_anggaran2 else 0 end),0) pen_sempurna,
        isnull(sum(case when left(kd_rek6,2)=51 then $status_anggaran2 else 0 end),0) b51_sempurna,
        isnull(sum(case when left(kd_rek6,2)=52 then $status_anggaran2 else 0 end),0) b52_sempurna,
        isnull(sum(case when left(kd_rek6,2)=53 then $status_anggaran2 else 0 end),0) b53_sempurna,
        isnull(sum(case when left(kd_rek6,2)=54 then $status_anggaran2 else 0 end),0) b54_sempurna,
        isnull(sum(case when left(kd_rek6,1)=5 then $status_anggaran2 else 0 end),0) tot_sempurna
        from trdrka 
        GROUP BY left(kd_sub_kegiatan,4) 

        UNION all

        select  '3' urut, left(kd_skpd,17) kd_skpd, (select nm_skpd from ms_skpd where kd_skpd=left(trdrka.kd_skpd,17)+'.0000') kd_skpd, left(kd_sub_kegiatan,4)kd,
        isnull(sum(case when left(kd_rek6,1)=4 then $status_anggaran1 else 0 end),0) pen,
        isnull(sum(case when left(kd_rek6,2)=51 then $status_anggaran1 else 0 end),0) b51,
        isnull(sum(case when left(kd_rek6,2)=52 then $status_anggaran1 else 0 end),0) b52,
        isnull(sum(case when left(kd_rek6,2)=53 then $status_anggaran1 else 0 end),0) b53,
        isnull(sum(case when left(kd_rek6,2)=54 then $status_anggaran1 else 0 end),0) b54,
        isnull(sum(case when left(kd_rek6,1)=5 then $status_anggaran1 else 0 end),0) tot,
        isnull(sum(case when left(kd_rek6,1)=4 then $status_anggaran2 else 0 end),0) pen_sempurna,
        isnull(sum(case when left(kd_rek6,2)=51 then $status_anggaran2 else 0 end),0) b51_sempurna,
        isnull(sum(case when left(kd_rek6,2)=52 then $status_anggaran2 else 0 end),0) b52_sempurna,
        isnull(sum(case when left(kd_rek6,2)=53 then $status_anggaran2 else 0 end),0) b53_sempurna,
        isnull(sum(case when left(kd_rek6,2)=54 then $status_anggaran2 else 0 end),0) b54_sempurna,
        isnull(sum(case when left(kd_rek6,1)=5 then $status_anggaran2 else 0 end),0) tot_sempurna
        from trdrka 
        GROUP BY left(kd_sub_kegiatan,4),left(kd_skpd,17) 


    ) cc
    ) ok
    ORDER BY kd , urut";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;


                $pend =$ab->pen;
                $b51  =$ab->b51;
                $b52  =$ab->b52;
                $b53  =$ab->b53;
                $b54  =$ab->b54;
                $tot  =$ab->tot;

                $pend_sempurna =$ab->pen_sempurna;
                $b51_sempurna  =$ab->b51_sempurna;
                $b52_sempurna  =$ab->b52_sempurna;
                $b53_sempurna  =$ab->b53_sempurna;
                $b54_sempurna  =$ab->b54_sempurna;
                $tot_sempurna  =$ab->tot_sempurna;
 


            $selisih_pendapatan=$this->support->format_bulat($pend_sempurna-$pend);
            $selisih_belanja=$this->support->format_bulat($tot_sempurna-$tot);

            if($pend==0){
                $persen_pendapatan=$this->support->format_bulat(0);
            }else{
                $persen_pendapatan=$this->support->format_bulat((($pend_sempurna-$pend)/$pend)*100);
            }

            $persen_belanja=$this->support->format_bulat((($tot_sempurna-$tot)/$tot)*100);

            if($skpd!=''){
                $tot4  =$tot4+$pend;
                $tot51 =$tot51+$b51;
                $tot52 =$tot52+$b52;
                $tot53 =$tot53+$b53;
                $tot54 =$tot54+$b54;
                $tottot=$tottot+$tot;
                $tot4_sempurna  =$tot4_sempurna+$pend_sempurna;
                $tot51_sempurna =$tot51_sempurna+$b51_sempurna;
                $tot52_sempurna =$tot52_sempurna+$b52_sempurna;
                $tot53_sempurna =$tot53_sempurna+$b53_sempurna;
                $tot54_sempurna =$tot54_sempurna+$b54_sempurna;
                $tottot_sempurna=$tottot_sempurna+$tot_sempurna;

                $selisih_total_pendapatan=$this->support->format_bulat($tot4_sempurna-$tot4);
                $selisih_total_belanja=$this->support->format_bulat($tottot_sempurna-$tottot);

                if($tot4==0){
                    $persen_total_pendapatan=$this->support->format_bulat(0);
                }else{
                    $persen_total_pendapatan=$this->support->format_bulat((($tot4_sempurna-$tot4)/$tot4)*100);
                }

                $persen_total_belanja=$this->support->format_bulat((($tottot_sempurna-$tottot)/$tottot)*100);
            }

            $tbl.="<tr>
                        <td align='center'>$kode1</td>
                        <td align='center'>$kode2</td>
                        <td align='center' >$skpd</td>
                        <td align='left' >$urai</td>
                        <td align='right'>".number_format($pend,"0",",",".")."</td>
                        <td align='right'>".number_format($pend_sempurna,"0",",",".")."</td>
                        <td align='right'>$selisih_pendapatan</td>
                        <td align='right'>$persen_pendapatan</td>
                        <td align='right'>".number_format($b51,"0",",",".")."</td>
                        <td align='right'>".number_format($b52,"0",",",".")."</td>
                        <td align='right'>".number_format($b53,"0",",",".")."</td>
                        <td align='right'>".number_format($b54,"0",",",".")."</td>
                        <td align='right'>".number_format($tot,"0",",",".")."</td>
                        <td align='right'>".number_format($b51_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($b52_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($b53_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($b54_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tot_sempurna,"0",",",".")."</td>
                        <td align='right'>$selisih_belanja</td>
                        <td align='right'>$persen_belanja</td>                          
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='4'>Jumlah</td>
                        <td align='right'>".number_format($tot4,"0",",",".")."</td>
                        <td align='right'>".number_format($tot4_sempurna,"0",",",".")."</td>
                        <td align='right'>$selisih_total_pendapatan</td>
                        <td align='right'>$persen_total_pendapatan</td>
                        <td align='right'>".number_format($tot51,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53,"0",",",".")."</td>
                        <td align='right'>".number_format($tot54,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot,"0",",",".")."</td> 
                        <td align='right'>".number_format($tot51_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tot54_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot_sempurna,"0",",",".")."</td> 
                        <td align='right'>$selisih_total_belanja</td>  
                        <td align='right'>$persen_total_belanja</td> 

                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else if($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI.xls");
            echo $tbl;
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }
    }

    function lampiran2_murni($tgl,$doc,$pdf){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA";
            $judul   ="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam     ="perwa";
        }else{
            $lampiran="PERATURAN DAERAH";
            $judul   ="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam     ="perda";
        }

        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        
        foreach($exc->result() as $abc ){
            $nomor  =$abc->nomor;
            $isi    =$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

   $tbl .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> LAMPIRAN II<br> $lampiran KOTA PONTIANAK <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='2' colspan='3' align='center' width='20%'><b>Kode</td>
                    <td rowspan='2' align='center' width='20%'><b>Urusan Pemerintah Daerah</td>
                    <td rowspan='2' align='center' width='10%'><b>Pendapatan</td>
                    <td colspan='5' align='center' width='40%'><b>Belanja</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center'><b>Jumlah Belanja</td>
					
               </tr>
                </thead>
               <tr>
                    <td align='center' colspan='3'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>
                    <td align='center'><b>8</td>                    
               </tr>

               <tr>
                    <td align='center' colspan='3'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>                    
               </tr>

               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* from v_lampiran2_murni ORDER BY kd , urut";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
            $tot  =$ab->tot;

            if($skpd!=''){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
            }

            $tbl.="<tr>
                        <td align='center' width='5%'>$kode1</td>
                        <td align='center' width='5%'>$kode2</td>
                        <td align='center' width='10%'>$skpd</td>
                        <td align='left' width='20%'>$urai</td>
                        <td align='right' width='10%'>".number_format($pend,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b51,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b52,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b53,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b54,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($tot,"0",",",".")."</td>                    
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='4'>Jumlah</td>
                        <td align='right'>".number_format($tot4,"0",",",".")."</td>
                        <td align='right'>".number_format($tot51,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53,"0",",",".")."</td>
                        <td align='right'>".number_format($tot54,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot,"0",",",".")."</td>                    
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else if($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI.xls");
            echo $tbl;
        }else{
             $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }
    }
	function lampiran2_murni_ak($tgl,$doc,$pdf){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA";
            $judul   ="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam     ="perwa";
        }else{
            $lampiran="PERATURAN DAERAH";
            $judul   ="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam     ="perda";
        }

        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        
        foreach($exc->result() as $abc ){
            $nomor  =$abc->nomor;
            $isi    =$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

   $tbl .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td rowspan='2' width='60%' style='border-right:none'> <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" /></td>
                        <td width='40%' align='left' style='border:none'> LAMPIRAN II<br> $lampiran KOTA PONTIANAK <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='2' colspan='3' align='center' width='20%'><b>Kode</td>
                    <td rowspan='2' align='center' width='50%'><b>Urusan Pemerintah Daerah</td>
                    <td rowspan='2' align='center' width='10%'><b>Pendapatan</td>
                    <td colspan='5' align='center' width='10%'><b>Belanja Anggaran</td>
					<td colspan='5' align='center' width='10%'><b>Belanja Realisasi</td>
					<td align='center' width='10%'><b>Bertambah/Berkurang</td>
					<td align='center' width='10%'><b>Persentase</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center'><b>Jumlah </td>
					<td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center'><b>Jumlah </td>
					<td align='center'><b></td>
                    <td align='center'><b></td>
					
               </tr>
			   
			   
                </thead>
               <tr>
                    <td align='center' colspan='3'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>
                    <td align='center'><b>8</td> 
					<td align='center'><b>9</td> 
					<td align='center'><b>10</td> 
					<td align='center'><b>11</td> 
					<td align='center'><b>12</td> 
					<td align='center'><b>13</td>
					<td align='center'><b>14</td> 
					<td align='center'><b>15</td>
					
					
               </tr>
				
			  
               <tr>
                    <td align='center' colspan='3'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
					<td align='center'><b></td>
					<td align='center'><b></td>
					<td align='center'><b></td>
					<td align='center'><b></td>
					<td align='center'><b></td>
					<td align='center'><b></td>
					<td align='center'><b></td>
					
               </tr>

               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0; $tamkur=0; $persentase=0; $tot51_ubah=0; $tot52_ubah=0; $tot53_ubah=0; $tot54_ubah=0; $tottot2=0; $total_baru=0 ;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* from v_lampiran2_murni_ak ORDER BY kd , urut";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
			$tot  =$ab->tot;
			//perubahan 29 maret 2021 bagian realisasi belanja
			$pend_ubah=$ab->pen_ubah;
			$b51_ubah  =$ab->b51_ubah;
			$b52_ubah  =$ab->b52_ubah;
			$b53_ubah  =$ab->b53_ubah;
			$b54_ubah  =$ab->b54_ubah;
            $tot_ubah  =$ab->tot_ubah;
			
			$tamkur=$tot-$tot_ubah;
					
					$persentase=0;
					 if ($tot_ubah==0) {
						   $persentase=0;
						   
					   }
					   else {
						   $persentase=(($tot-$tot_ubah)/$tot_ubah)*100;
						   
					   }
			

            if($skpd!=''){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
				$tot51_ubah=$tot51_ubah+$b51_ubah;
                $tot52_ubah=$tot52_ubah+$b52_ubah;
                $tot53_ubah=$tot53_ubah+$b53_ubah;
                $tot54_ubah=$tot54_ubah+$b54_ubah;
                $tottot2=$tottot2+$tot_ubah;
				
				$total_baru=$total_baru+$tamkur;
					
					$persentase2=0;
					 if ($tot_ubah==0) {
						   $persentase2=0;
						   
					   }
					   else {
						   $persentase2=(($tot-$tot_ubah)/$tot_ubah)*100;
						   
					   }
            }

            $tbl.="<tr>
                        <td align='center' width='5%'>$kode1</td>
                        <td align='center' width='5%'>$kode2</td>
                        <td align='center' width='10%'>$skpd</td>
                        <td align='left' width='20%'>$urai</td>
                        <td align='right' width='10%'>".number_format($pend,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b51,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b52,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b53,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b54,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($tot,"0",",",".")."</td>
						<td align='right' width='10%'>".number_format($b51_ubah,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b52_ubah,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b53_ubah,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b54_ubah,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($tot_ubah,"0",",",".")."</td>
						<td align='right' width='10%'>".number_format($tamkur,"0",",",".")."</td>
						<td align='right' width='10%'>".number_format($persentase,"0",",",".")."</td>
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='4'>Jumlah</td>
                        <td align='right'>".number_format($tot4,"0",",",".")."</td>
                        <td align='right'>".number_format($tot51,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53,"0",",",".")."</td>
                        <td align='right'>".number_format($tot54,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot,"0",",",".")."</td>
						<td align='right'>".number_format($tot54_ubah,"0",",",".")."</td>
                        <td align='right'>".number_format($tot51_ubah,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52_ubah,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53_ubah,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot2,"0",",",".")."</td>
                        <td align='right'>".number_format($total_baru,"0",",",".")."</td>
						<td align='right'>".number_format($persentase2,"0",",",".")."</td> 
						
						
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else if ($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Untitled.xls");
            echo $tbl;
            
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }
    }

    function lampiran3_murni_perwa($tgl,$doc,$pdf,$skpd,$urusan){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');
        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA KOTA PONTIANAK";
            $judul="RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN, SUB KEGIATAN,<br>
KELOMPOK, JENIS, OBJEK, DAN RINCIAN OBJEK PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perwa";
        }else{
            $lampiran="PERATURAN DAERAH KOTA PONTIANAK";
            $judul="RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN, SUB KEGIATAN,<br>
KELOMPOK, JENIS, OBJEK, DAN RINCIAN OBJEK PENDAPATAN, BELANJA, DAN PEMBIAYAAN";
            $lam="perda";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tbl .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> LAMPIRAN III<br> $lampiran <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";

        $nmskpd=$this->db->query("SELECT nm_skpd nama from ms_skpd where kd_skpd='$skpd'")->row()->nama; 
        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0' cellpadding='2'>
                    <tr>
                        <td align='left' width='10%'>ORGANISASI</td>
                        <td align='left' width='2%'>:</td>
                        <td align='left' width='15%'>$skpd</td>
                        <td align='left' width='68%'>$nmskpd</td>
                    </tr>
                </table>";
        $cell=1;
        $tbl .= "<table style='border-collapse:collapse;font-family: Bookman Old Style; font-size:12;border-bottom: none;' width='100%' align='center' border='1' cellspacing='2' cellpadding='$cell'>
                     <thead >                       
                        <tr><td width='15%' align='center'><b>KODE REKENING</b></td>                            
                            <td width='25%' align='center'><b>URAIAN</b></td>
                            <td width='15%' align='center'><b>JUMLAH</b></td>
                            <td colspan='4' width='25%' align='center'><b>PENJELASAN</b></td>
                            <td width='10%' align='center'><b>KETERANGAN</b></td>
                        </tr>                            
                        <tr><td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;font-weight:bold;'  align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;font-weight:bold;'  align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;font-weight:bold;'  align='center'>3</td>
                            <td colspan='4' style='vertical-align:top;border-top: none;border-bottom: solid 1px black;font-weight:bold;'  align='center'>4</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;font-weight:bold;'  align='center'>5</td>
                        </tr>                     
                    </thead>
                    <tfoot>
                        <tr>
                            <td style='border-top: solid 1px black; border-bottom: none;border-right: none;border-left: none;' colspan='8'></hr></td>
                         </tr>
                     </tfoot>                       
                        ";


            

            /*PENDAPATAN*/
                $sql1="SELECT * from v_lampiran3_murni_perwa WHERE kd_skpd='$skpd' ORDER BY kd_skpd,kd_sub_kegiatan,rek,cast(no_po as int), uraian";

 
                $totbl = 0;
                $query = $this->db->query($sql1);

                foreach ($query->result() as $row) {
                    $kd_kegiatan=$row->kd_sub_kegiatan;
                    $rek=$row->kode;
                    $reke=$this->support->dotrek($rek);
                    $uraian=$row->nama;
                    $uraian2=$row->uraian;
                    $anggaran=$row->anggaran;
                    $tot=$row->total;
                    $sat=$row->satuan;
                    $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,"0",",",".");
                    $volum= empty($row->volume) || $row->volume == 0 ? '' :number_format($row->volume,"0",",",".");

                    if($tot!=0){
                        $nilakh =number_format($tot,"0",",",".");
                        $dgn = '=';
                    }else{
                        $nilakh='';
                        $dgn = '';
                    }
                    
                    $leng= strlen($rek);
                    switch ($leng){
                    case 0;
                     $tbl    .= " <tr>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' align='left'>&nbsp;$kd_kegiatan</td>                                     
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' >$uraian</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' align='right'>".number_format($anggaran,"0",",",".")."</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none' align='left'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td></tr>
                                 
                                 ";
                    break;

                    case 1;
                     $tbl    .= " <tr>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' align='left'>&nbsp;$reke</td>                                     
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' >$uraian</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' align='right'>".number_format($anggaran,"0",",",".")."</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none' align='left'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td></tr>
                                 ";
                    break;
                    case 12; /* rekening 6*/
                     $tbl    .= " <tr>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;'  align='left'>&nbsp;$reke</td>                                     
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' >&nbsp;$uraian</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' align='right'>".number_format($anggaran,"0",",",".")."</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;' align='left'></td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'> </td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td></tr>
                                 ";
                    break;
                    case 13;
                     $tbl    .= " <tr>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;'  align='left'></td>                                     
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' >&nbsp;</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' align='right'>&nbsp;</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;' align='left'>$uraian2</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'>$volum</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'>$hrg $dgn</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-left: none' align='right'>$nilakh</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td></tr>
                                 ";
                    break;
                    case 14; /*penjelasan*/
                     $tbl    .= " <tr>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;'  align='left'></td>                                     
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' >&nbsp;</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' align='right'>&nbsp;</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;' align='left'>$uraian2</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'>$volum</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'>$hrg =</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-left: none' align='right'>".number_format($tot,"0",",",".")."</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td></tr>
                                 ";
                    break;
                    case 15; /*penjelasan*/
                     $tbl    .= " <tr>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;'  align='left'></td>                                     
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' >&nbsp;</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;' align='right'>&nbsp;</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;' align='left'>$uraian2</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'>$volum</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'>$hrg =</td>
                                 <td style='vertical-align:top; border-bottom: none;border-top: none;border-left: none' align='right'>".number_format($tot,"0",",",".")."</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td></tr>
                                 ";
                    break;
                    default;
                     $tbl    .= " <tr>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' align='left'>&nbsp;$reke</td>                                     
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' >&nbsp;$uraian</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;' align='right'>".number_format($anggaran,"0",",",".")."</td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none' align='left'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-right: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td>
                                 <td style='vertical-align:top;font-weight:bold; border-bottom: none;border-top: none;border-left: none' align='right'></td></tr>
                                 ";
                    break;
                    }


                        
                    
                }
            $tbl .="</table>";
                        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else if($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI.xls");
            echo $tbl;
 
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }


    }
	



    function lampiran3_murni($tgll,$doc,$pdf,$skpd,$urusan){
        $tgl=$this->support->tanggal_format_indonesia($tgll);
        $thn=$this->session->userdata('pcThang');
        if($doc=='PERWA_MURNI'){
           /* $this->lampiran3_murni_perwa($tgll,$doc,$pdf,$skpd,$urusan);
            die();*/
            $isiquery="v_lampiran2_perwa_murni"; /*lampiran perwa 1 murni*/
            $lampiran="LAMPIRAN II <br>PERATURAN WALIKOTA KOTA PONTIANAK";
            $judul="RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PENDAPATAN, BELANJA DAN PEMBIAYAAN";
            $lam="perwa";
            $tambahantabel="<td></td>";
            $tambahantabel2="<td><b>KETERANGAN</td>";
            $judultabel="<b>PENJELASAN";
            $kolom="11";
        }else{
            $isiquery="v_lampiran3_murni";     /*lampiran perda 3 murni*/
            $lampiran="LAMPIRAN III  <br>PERATURAN DAERAH KOTA PONTIANAK";
            $judul="RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PENDAPATAN, BELANJA DAN PEMBIAYAAN";
            $lam="perda";
            $tambahantabel="";
            $tambahantabel2="";
            $judultabel="<b>DASAR HUKUM";
            $kolom="10";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";




        $nmskpd=$this->db->query("SELECT nm_skpd nama from ms_skpd where kd_skpd='$skpd'")->row()->nama;

        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tbl .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> $lampiran <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>"; 
            $kskpd=explode(".",$skpd);
            $urusan1="".$kskpd[0].".".$kskpd[1]."";

            $data= array();
            $sql="SELECT * from ms_bidang_urusan where kd_bidang_urusan in ('$urusan1')";
            $kecap=$this->db->query($sql)->row();
        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0' cellpadding='2'>
                    <tr>
                    <tr>
                        <td align='left' width='10%'>URUSAN PEMERINTAH</td>
                        <td align='left' width='2%'>:</td>
                        <td align='left' width='15%'>$kecap->kd_bidang_urusan</td>
                        <td align='left' width='68%'>$kecap->nm_bidang_urusan</td>
                    </tr>
                    <tr>
                        <td align='left' width='10%'>ORGANISASI</td>
                        <td align='left' width='2%'>:</td>
                        <td align='left' width='15%'>$skpd</td>
                        <td align='left' width='68%'>$nmskpd</td>
                    </tr>
                </table>";
        $tbl.="<table style='border-collapse:collapse;font-size:12px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td colspan='7' align='center'><b>KODE REKENING</td>
                    <td align='center'><b>URAIAN</td>
                    <td align='center'><b>JUMLAH</td>
                    <td align='center' width='5%'>$judultabel</td>
                    $tambahantabel2

               </tr>
               </thead>
               <tr>
                    <td colspan='7' align='center'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    $tambahantabel
               </tr>
               <tr>
                    <td colspan='$kolom' bgcolor='#cccccc' align='center'><b>&nbsp;</td>
               </tr>


               ";
        $keluar=0; $belanja=0; $pendapatan=0; $terima=0;
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT * from $isiquery where left(sk,17)=left('$skpd',17) ORDER BY uruta, urut,rek";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1 =$ab->urusan;
            $kode2 =$ab->bid_urus;
            $kode3 =$ab->sk;
            $kode4 =$ab->program;
            $kode5 =$ab->giat;
            $kode6 =$ab->subgiat;
            $kode7 =$ab->rek;
            $nilai =$ab->nilai;
            $urai  =$ab->urai;
            if($kode4=='00' && $kode5!=''){
                $kode5="0.00";
            }
            if($kode4=='00' && $kode6!=''){
                $kode6="00";
            }

            switch (strlen($kode7)) {
                case '7':

                        switch (substr($kode7,0,3)) {
                            case '4xx':
                                        $pendapatan=$nilai;
                                break;
                            case '5xx':
                                        $belanja=$nilai;
                                break;
                            case '61x':
                                        $terima=$nilai;
                                break;                            
                            case '62x':
                                        $keluar=$nilai;
                                break; 

                        }
                        $surplus=$pendapatan-$belanja;
                        if($kode7=='6xxxxxx'){
                                $tbl.="<tr>
                                            <td align='right' width='35%' colspan='8'>Total Surplus/(Defisit)</td>
                                            <td align='right' width='20%'>".$this->support->format_bulat($surplus)."</td>
                                            <td align='center' width='5%'>&nbsp;</td>
                                            $tambahantabel
                                       </tr>";
                        }else{
                           $tbl.="<tr>
                                            <td align='right' width='35%' colspan='8'>$urai</td>
                                            <td align='right' width='20%'>".number_format($nilai,"0",",",".")."</td>
                                            <td align='center' width='5%'>&nbsp;</td>
                                            $tambahantabel
                                       </tr>";
                        }
     
                    break;
                
                default:
                        $tbl.="<tr>
                                    <td align='center' width='5%'>$kode1</td>
                                    <td align='center' width='5%'>$kode2</td>
                                    <td align='center' width='10%'>$kode3</td>
                                    <td align='center' width='5%'>$kode4</td>
                                    <td align='center' width='5%'>$kode5</td>
                                    <td align='center' width='5%'>$kode6</td>
                                    <td align='left' width='5%'>$kode7</td>
                                    <td align='left' width='35%'>$urai</td>
                                    <td align='right' width='20%'>".number_format($nilai,"0",",",".")."</td>
                                    <td align='center' width='5%'>&nbsp;</td>
                                    $tambahantabel
                               </tr>";
                                break;
            }

        }

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>"; 
        if($pdf==0){
                echo $tbl;   
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }

    }

    function lampiran3_pergeseran($tgll,$doc,$pdf,$skpd,$status_anggaran1,$status_anggaran2,$urusan){ /*lampiran perwa 2 dan perda 3 pergeseran*/
        $tgl=$this->support->tanggal_format_indonesia($tgll);
        $thn=$this->session->userdata('pcThang');
        if($doc=='PERWA_MURNI'){
            $isiquery="v_lampiran2_perwa_murni"; /*lampiran perwa 2 murni*/
            $lampiran="LAMPIRAN II <br>PERATURAN WALIKOTA KOTA PONTIANAK";
            $judul="RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PENDAPATAN, BELANJA DAN PEMBIAYAAN";
            $lam="perwa";
            $tambahantabel="<td></td>";
            $tambahantabel2="<td rowspan='2'><b>KETERANGAN</td>";
            $judultabel="<b>PENJELASAN";
            $kolom="14";
        }else{
            $isiquery="v_lampiran3_murni";     /*lampiran perda 3 murni*/
            $lampiran="LAMPIRAN III  <br>PERATURAN DAERAH KOTA PONTIANAK";
            $judul="RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PENDAPATAN, BELANJA DAN PEMBIAYAAN";
            $lam="perda";
            $tambahantabel="";
            $tambahantabel2="";
            $judultabel="<b>DASAR HUKUM";
            $kolom="13";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $order="desc";
        if($status_anggaran2=='nilai_ubah'){
            $jns_ang='3';
        } else if($status_anggaran2=='nilai_sempurna'){
             $jns_ang='2';
        } else if($status_anggaran2=='nilai_sempurna1'){
             $jns_ang='2';
             $order="";
        } else if($status_anggaran2=='nilai'){
             $jns_ang='1';
        } else{
             $jns_ang='2';
        }

        $nmskpd=$this->db->query("SELECT nm_skpd nama from ms_skpd where kd_skpd='$skpd'")->row()->nama;

        $exc=$this->db->query("SELECT top 1 * from trkonfig_anggaran where jenis_anggaran='$jns_ang' and lampiran='$lam' order by no_konfig $order");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tbl .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td width='40%' align='left' style='border:none'> $lampiran <br>NOMOR $nomor<br>$isi</td>
                      
                    </tr>
                   
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>"; 
            $kskpd=explode(".",$skpd);
            $urusan1="".$kskpd[0].".".$kskpd[1]."";

            $data= array();
            $sql="SELECT * from ms_bidang_urusan where kd_bidang_urusan in ('$urusan1')";
            $kecap=$this->db->query($sql)->row();
        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='0' cellpadding='2'>
                    <tr>
                    <tr>
                        <td align='left' width='10%'>URUSAN PEMERINTAH</td>
                        <td align='left' width='2%'>:</td>
                        <td align='left' width='15%'>$kecap->kd_bidang_urusan</td>
                        <td align='left' width='68%'>$kecap->nm_bidang_urusan</td>
                    </tr>
                    <tr>
                        <td align='left' width='10%'>ORGANISASI</td>
                        <td align='left' width='2%'>:</td>
                        <td align='left' width='15%'>$skpd</td>
                        <td align='left' width='68%'>$nmskpd</td>
                    </tr>
                </table>";
        $tbl.="<table style='border-collapse:collapse;font-size:11px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='2' width='34%' colspan='7' align='center'><b>KODE REKENING</td>
                    <td rowspan='2' width='20%' align='center'><b>URAIAN</td>
                    <td colspan='2' width='22%' align='center'><b>JUMLAH</td>
                    <td colspan='2' width='14%' align='center'><b>BERTAMBAH/ (BERKURANG)</td>
                    <td rowspan='2' align='center'>$judultabel</td>
                    $tambahantabel2
                    
               </tr>
    
                <tr>
                    <td align='center' width='10%'><b>SEBELUM PERUBAHAN</td>
                    <td align='center' width='10%'><b>SETELAH PERUBAHAN</td>
                    <td align='center' width='10%'><b>Rp.</td>
                    <td align='center' width='4%'><b>%</td>
                  
         
               </tr>
               </thead>
               <tr>
                    <td colspan='7' align='center'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>
                    $tambahantabel
               </tr>
               <tr>
                    <td colspan='$kolom' bgcolor='#cccccc' align='center'><b>&nbsp;</td>
               </tr>


               ";

        $keluar=0; $belanja=0; $pendapatan=0; $terima=0; $keluar2=0; $belanja2=0; $pendapatan2=0; $terima2=0;
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT * from $isiquery where left(sk,17)=left('$skpd',17) ORDER BY uruta, urut,rek";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1 =$ab->urusan;
            $kode2 =$ab->bid_urus;
            $kode3 =$ab->sk;
            $kode4 =$ab->program;
            $kode5 =$ab->giat;
            $kode6 =$ab->subgiat;
            $kode7 =$ab->rek;
            $urai  =$ab->urai;

            switch ($status_anggaran1) {
                case 'nilai':
                    $nilai =$ab->nilai;
                    break;
                case 'nilai_sempurna':
                    $nilai =$ab->nilai_sempurna;
                    break;
                case 'nilai_sempurna1':
                    $nilai =$ab->nilai_sempurna1;
                    break;
                case 'nilai_sempurna2':
                    $nilai =$ab->nilai_sempurna2;
                    break;
                case 'nilai_sempurna3':
                    $nilai =$ab->nilai_sempurna3;
                    break;
                case 'nilai_sempurna4':
                    $nilai =$ab->nilai_sempurna4;
                    break;
                case 'nilai_ubah':
                    $nilai =$ab->nilai_ubah;
                    break;                
                default:
                    $nilai =$ab->nilai_ubah;
                    break;
            }

            switch ($status_anggaran2) {
                case 'nilai':
                    $nilai2 =$ab->nilai;
                    break;
                case 'nilai_sempurna':
                    $nilai2 =$ab->nilai_sempurna;
                    break;
                case 'nilai_sempurna1':
                    $nilai2 =$ab->nilai_sempurna1;
                    break;
                case 'nilai_sempurna2':
                    $nilai2 =$ab->nilai_sempurna2;
                    break;
                case 'nilai_sempurna3':
                    $nilai2 =$ab->nilai_sempurna3;
                    break;
                case 'nilai_sempurna4':
                    $nilai2 =$ab->nilai_sempurna4;
                    break;
                case 'nilai_ubah':
                    $nilai2 =$ab->nilai_ubah;
                    break;                
                default:
                    $nilai2 =$ab->nilai_ubah;
                    break;
            }


            if($kode4=='00' && $kode5!=''){
                $kode5="0.00";
            }
            if($kode4=='00' && $kode6!=''){
                $kode6="00";
            }

            $selisih=$this->support->format_bulat($nilai2-$nilai);
            if($nilai==0){
                $persen=$this->support->format_bulat(0);
            }else{
                $persen=$this->support->format_bulat((($nilai2-$nilai)/$nilai)*100);                
            }


            switch (strlen($kode7)) {
                case '7':

                        switch (substr($kode7,0,3)) {
                            case '4xx':
                                        $pendapatan=$nilai; $pendapatan2=$nilai2;
                                break;
                            case '5xx':
                                        $belanja=$nilai; $belanja2=$nilai2;
                                break;
                            case '61x':
                                        $terima=$nilai; $terima2=$nilai2;
                                break;                            
                            case '62x':
                                        $keluar=$nilai; $keluar2=$nilai2;
                                break; 

                        }
                        $surplus=$pendapatan-$belanja;
                        $surplus2=$pendapatan2-$belanja2;
                        $selisih=$surplus2-$surplus;

                        if($surplus==0){
                            $persen=$this->support->format_bulat(0);
                        }else{
                            $persen=$this->support->format_bulat((($selisih)/$surplus)*100);                
                        }

                        if($kode7=='6xxxxxx'){
                                $tbl.="<tr>
                                            <td align='right'  colspan='8'>Total Surplus/(Defisit)</td>
                                            <td align='right' >".$this->support->format_bulat($surplus)."</td>
                                            <td align='right' >".$this->support->format_bulat($surplus2)."</td>
                                            <td align='right' >".$this->support->format_bulat($selisih)."</td>
                                            <td align='right' >".$persen."</td>
                                            <td align='center' >&nbsp;</td>
                                            $tambahantabel
                                       </tr>";
                        }else{
                           $tbl.="<tr>
                                            <td align='right'  colspan='8'>$urai</td>
                                            <td align='right' >".number_format($nilai,"0",",",".")."</td>
                                            <td align='right' >".number_format($nilai2,"0",",",".")."</td>
                                            <td align='right' >".number_format($selisih,"0",",",".")."</td>
                                            <td align='right' >".$persen."</td>
                                            <td align='center' >&nbsp;</td>
                                            $tambahantabel
                                       </tr>";
                        }
     
                    break;
                
                default:
                        $tbl.="<tr>
                                    <td align='center' >$kode1</td>
                                    <td align='center' >$kode2</td>
                                    <td align='center' >$kode3</td>
                                    <td align='center' >$kode4</td>
                                    <td align='center' >$kode5</td>
                                    <td align='center' >$kode6</td>
                                    <td align='left'   >$kode7</td>
                                    <td align='left'   >$urai</td>
                                    <td align='right' >".number_format($nilai,"0",",",".")."</td>
                                    <td align='right' >".number_format($nilai2,"0",",",".")."</td>
                                    <td align='right' >".$selisih."</td>
                                    <td align='right' >".$persen."</td>
                                    <td align='center' >&nbsp;</td>
                                    $tambahantabel
                               </tr>";
                                break;
            }

        }

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>"; 
        if($pdf==0){
                echo $tbl;   
        }else if($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH.xls");
            echo $tbl; 
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }

    }

    function lampiran4_murnid($tgl,$doc,$pdf){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PEARTURAN WALIKOTA KOTA PONTIANAK";
            $judul="REKAPITULASI BELANJA <br>
MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN BESERTA HASIL  <br>
DAN SUB KEGIATAN BESERTA KELUARAN
";
            $lam="perwa";
        }else{
            $lampiran="PEARTURAN DAERAH KOTA PONTIANAK ";
            $judul="REKAPITULASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM DAN KEGIATAN BESERTA HASIL DAN SUB KEGIATAN BESERTA KELUARAN
";
            $lam="perda";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tbl ="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN IV <br>$lampiran<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='2' colspan='6' align='center' width='20%'><b>Kode</td>
                    <td rowspan='2' align='center' width='20%'><b>Urusan Pemerintah Daerah</td>
                    <td colspan='5' align='center' width='40%'><b>Belanja</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center' ><b>Jumlah Belanja</td>
               </tr>
                </thead>
               <tr>
                    <td align='center' colspan='6'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>                                
               </tr>

               <tr>
                    <td align='center' colspan='6'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>                    
               </tr>

               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2, SUBSTRING(kd,6,2) kd3, SUBSTRING(kd,9,4) kd4, SUBSTRING(kd,14,2) kd5,* from v_lampiran4_murnip order by kd,kd+kd_skpd,urut";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode3=$ab->kd3;
            $kode4=$ab->kd4;
            $kode5=$ab->kd5;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
            $tot  =$ab->tot;
            $urut =$ab->urut;

            if($urut=='1'){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
            }

            if($urut=='6' || $urut=='8'){
                $kode1="";
                $kode2="";
                $kode3="";
                $kode ="";
                $skpd="";
                $kode4="";
                $kode5="";
                if($urut=='6'){
                    $urai="<i>Hasil </i>: $urai";
                }else if($urut=='8'){
                    $urai="<i>Keluaran </i>: $urai";
                }
            }
            if($urut=='1' || $urut=='2'){
                    $skpd="";
            }
            $tbl.="<tr>
                        <td align='center' width='5%'>$kode1</td>
                        <td align='center' width='5%'>$kode2</td>
                        <td align='center' width='10%'>$skpd</td>
                        <td align='center' width='5%'>$kode3</td>
                        <td align='center' width='5%'>$kode4</td>
                        <td align='center' width='5%'>$kode5</td>
                        <td align='left'   width='15%'>$urai</td>
                        <td align='right' width='10%'>".number_format($b51,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b52,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b53,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($b54,"0",",",".")."</td>
                        <td align='right' width='10%'>".number_format($tot,"0",",",".")."</td>                    
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='7'>Jumlah</td>
                        <td align='right'>".number_format($tot51,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53,"0",",",".")."</td>
                        <td align='right'>".number_format($tot54,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot,"0",",",".")."</td>                    
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }
    }

    function perda8($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2){

        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PEARTURAN WALIKOTA KOTA PONTIANAK";
            $judul="REKAPITULASI BELANJA <br>
MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN BESERTA HASIL  <br>
DAN SUB KEGIATAN BESERTA KELUARAN
";
            $lam="perwa";
        }else{
            $lampiran="PEARTURAN DAERAH KOTA PONTIANAK ";
            $judul="REKAPITULASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM DAN KEGIATAN BESERTA HASIL DAN SUB KEGIATAN BESERTA KELUARAN
";
            $lam="perda";
        }

        if($status_anggaran2=='nilai'){
            $jenis_anggaran='1';
        }else if($status_anggaran2=='nilai_sempurna'){
            $jenis_anggaran='2';
        }else{
            $jenis_anggaran='3';
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $isi='';
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tbl ="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN VIII <br>$lampiran<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            SINKRONISASI PROGRAM, KEGIATAN DAN SUB KEGIATAN PADA RKPD DAN PPAS<br>
DENGAN PERATURAN DAERAH TENTANG APBD <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
            
                </thead>
               <tr>
                    <td align='center' colspan='4'><b>Kode</td>
                    <td align='center'><b>Uraian</td>
                    <td align='center'><b>RKPD (Rp)</td>
                    <td align='center'><b>PPAS (Rp)</td>
                    <td align='center'><b>APBD <br> Penyusunan(Rp)</td> 
                    <td align='center'><b>APBD <br> Perubahan(Rp)</td>                               
               </tr>

               <tr>
                    <td align='center' colspan='4'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>               
               </tr>

               ";


        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $tot4_sempurna=0; $tot51_sempurna=0; $tot52_sempurna=0; $tot53_sempurna=0; $tot54_sempurna=0; $tottot_sempurna=0;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2, SUBSTRING(kd,6,2) kd3, SUBSTRING(kd,9,4) kd4, SUBSTRING(kd,14,2) kd5,* from (

select * from (


select  '3' urut, left(kd_skpd,17)+'.0000' kd_skpd, (select nm_skpd from ms_skpd where kd_skpd=left(a.kd_skpd,17)+'.0000') bidurusan, left(kd_sub_kegiatan,4)kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,4), left(kd_skpd,17) 

union ALL
-- program
select  '4' urut, left(kd_skpd,17)+'.0000', (select nm_program from ms_program where kd_program=left(a.kd_sub_kegiatan,7)) nama, left(kd_sub_kegiatan,7)kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,7),left(kd_skpd,17) 

union ALL
--kegiatan
select  '5' urut, left(kd_skpd,17)+'.0000', (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(a.kd_sub_kegiatan,12)) nama, left(kd_sub_kegiatan,12)kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,12),left(kd_skpd,17) 

union ALL


--sub kegiatan
select  '7' urut, left(kd_skpd,17)+'.0000', 
(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_sub_kegiatan) nama, 
kd_sub_kegiatan kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY a.kd_sub_kegiatan,left(kd_skpd,17) 



) cc



    ) ohyes order by kd,kd+kd_skpd,urut";
	
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode3=$ab->kd3;
            $kode4=$ab->kd4;
            $kode5=$ab->kd5;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $urut =$ab->urut;

            
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
            $tot  =$ab->tot;
            

            
            $pend_sempurna =$ab->pen_sempurna;
            $b51_sempurna  =$ab->b51_sempurna;
            $b52_sempurna  =$ab->b52_sempurna;
            $b53_sempurna  =$ab->b53_sempurna;
            $b54_sempurna  =$ab->b54_sempurna;
            $tot_sempurna  =$ab->tot_sempurna;
            

            $selisih=$this->support->format_bulat($tot_sempurna-$tot);

            if($tot==0){
                $persen=$this->support->format_bulat(0);
            }else{
                $persen=$this->support->format_bulat((($tot_sempurna-$tot)/$tot)*100);                
            }


            if($urut=='1'){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
                $tot4_sempurna=$tot4_sempurna+$pend_sempurna;
                $tot51_sempurna=$tot51_sempurna+$b51_sempurna;
                $tot52_sempurna=$tot52_sempurna+$b52_sempurna;
                $tot53_sempurna=$tot53_sempurna+$b53_sempurna;
                $tot54_sempurna=$tot54_sempurna+$b54_sempurna;
                $tottot_sempurna=$tottot_sempurna+$tot_sempurna;
                $selisih_total=$this->support->format_bulat($tottot_sempurna-$tottot);

                if($tottot==0){
                    $persen_total=$this->support->format_bulat(0);
                }else{
                    $persen_total=$this->support->format_bulat((($tottot_sempurna-$tottot)/$tottot)*100);                
                }
            }

 

            if($urut=='1' || $urut=='2'){
                    $skpd="";
            }

            $tbl.="<tr>
                        <td align='center' >$skpd</td>
                        <td align='center' >$kode3</td>
                        <td align='center' >$kode4</td>
                        <td align='center' >$kode5</td>
                        <td align='left'   >$urai</td>
                        <td align='right' >".number_format($tot,"0",",",".")."</td>
                        <td align='right' >".number_format($tot,"0",",",".")."</td>
                        <td align='right' >".number_format($tot,"0",",",".")."</td>
                        <td align='right' >".number_format($tot_sempurna,"0",",",".")."</td>                 
                   </tr>";
        }


        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else if($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda IV.xls");
            echo $tbl;
        }else{
            $this->master_pdf->_mpdf_down('Lampiran Perda',' IV',$tbl,10,10,10,'1');
        }
    }

    function lampiran5_murni($tgl,$doc,$pdf){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA KOTA PONTIANAK";
        $judul="REKAPITULASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN <br>URUSAN PEMERINTAHAN DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA";
            $lam="perwa";
        }else{
            $lampiran="PERATURAN DAERAH KOTA PONTIANAK";
        $judul="REKAPITULASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN <br>URUSAN PEMERINTAHAN DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA";
            $lam="perda";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tabel="";
        $tabel .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN V <br>$lampiran<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";

        $tabel .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";
        $tabel.="
        <table width='100%' border='1' style='border-collapse:collapse;font-size:12px' cellspacing='0' cellpadding='7'>
            <thead>
            <tr>
                <td width='16%' colspan='4' rowspan='3' align='center'><b>KODE</td>
                <td width='34%' align='center' rowspan='3' ><b>URAIAN</td>
                <td width='45%' colspan='4' align='center'><b> KELOMPOK BELANJA</td>
                <td width='15%' rowspan='3' align='center'><b>JUMLAH</td>
            <tr>
            <tr>
                <td width='15%' align='center'><b>Operasi</td>
                <td width='15%' align='center'><b>Modal</td>
                <td width='15%' align='center'><b>Tak Terduga</td>
                <td width='15%' align='center'><b>Transfer</td>
            </tr>
            </thead>
            <tr>
                <td width='16%' align='center' colspan='4'><b>1</td>
                <td width='34%' align='center'><b>2</td>
                <td width='15%' align='center'><b>3</td>
                <td width='15%' align='center'><b>4</td>
                <td width='15%' align='center'><b>5</td>
                <td width='15%' align='center'><b>6</td>
                <td width='15%' align='center'><b>7</td>
            </tr>";
        $jumlah=0; $total=0; $tb51=0; $tb52=0; $tb53=0; $tb54=0;
        $que=$this->db->query("SELECT * from v_lampiran5_murni order by urus");
        foreach($que->result() as $ri){
            $kode=$ri->urus;
            $nama=$ri->nama;
            $b51 =$ri->b51;
            $b52 =$ri->b52;
            $b53 =$ri->b53;
            $b54 =$ri->b54;
            $jumlah=$b51+$b52+$b53+$b54;

            if(strlen($kode)==1){
                $total=$total+$jumlah;
                $tb51=$tb51+$b51;
                $tb52=$tb52+$b52;
                $tb53=$tb53+$b53;
                $tb54=$tb54+$b54;
            }

            $tabel.="<tr>
                        <td width='4%' align='center'>".substr($kode,0,1)."</td>
                        <td width='4%' align='center'>".substr($kode,2,2)."</td>
                        <td width='4%' align='center'>".substr($kode,5,1)."</td>
                        <td width='4%' align='center'>".substr($kode,7,2)."</td>
                        <td width='34%' align='left'>$nama</td>
                        <td width='15%' align='right'>".number_format($b51,"0",",",".")."</td>
                        <td width='15%' align='right'>".number_format($b52,"0",",",".")."</td>
                        <td width='15%' align='right'>".number_format($b53,"0",",",".")."</td>
                        <td width='15%' align='right'>".number_format($b54,"0",",",".")."</td>
                        <td width='15%' align='right'>".number_format($jumlah,"0",",",".")."</td>
                    </tr>";

        }
        $tabel.="<tr>
                    <td colspan='5' align='center'>JUMLAH</td>
                    <td align='right'>".number_format($tb51,"0",",",".")."</td>
                    <td align='right'>".number_format($tb52,"0",",",".")."</td>
                    <td align='right'>".number_format($tb53,"0",",",".")."</td>
                    <td align='right'>".number_format($tb54,"0",",",".")."</td>
                    <td align='right'>".number_format($total,"0",",",".")."</td>
                </tr>";
        $tabel.="</table>";

            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tabel.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    

        if($pdf==0){
            echo $tabel;
        }else{
            $this->master_pdf->_mpdf('',$tabel,10,10,10,'1');
        }
    }

    function lampiran5_pergeseran($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2){
        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PERATURAN WALIKOTA KOTA PONTIANAK";
        $judul="REKAPITULASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN <br>URUSAN PEMERINTAHAN DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA";
            $lam="perwa";
        }else{
            $lampiran="PERATURAN DAERAH KOTA PONTIANAK";
        $judul="REKAPITULASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN <br>URUSAN PEMERINTAHAN DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA";
            $lam="perda";
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $isi="";
        if($status_anggaran2=='nilai'){
            $jenis_anggaran='1';
        }else if($status_anggaran2=='nilai_sempurna'){
            $jenis_anggaran='2';
        }else{
            $jenis_anggaran='3';
        }
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tabel="";
        $tabel .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN V <br>$lampiran<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";

        $tabel .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";
        $tabel.="
        <table width='100%' border='1' style='border-collapse:collapse;font-size:12px' cellspacing='0' cellpadding='7'>
            <thead>
            <tr>
                <td colspan='4' rowspan='4' align='center'><b>Kode</td>
                <td align='center' rowspan='4' ><b>Uraian</td>
                <td colspan='5' align='center'><b> Sebelum Perubahan</td>
                <td colspan='5' align='center'><b> Setelah Perubahan</td>
                <td colspan='3'  align='center'><b>Bertambah/ (Berkurang)</td>
            <tr>
            <tr>
                <td colspan='4' align='center'><b>Kelompok Belanja</td>
                <td rowspan='2' align='center'><b>Jumlah</td>
                <td colspan='4' align='center'><b>Kelompok Belanja</td>
                <td rowspan='2' align='center'><b>Jumlah</td>
                <td rowspan='2' align='center'><b>Rp</td>
                <td rowspan='2' align='center'><b>%</td>
            </tr>
            <tr>
                <td align='center'><b>Operasi</td>
                <td align='center'><b>Modal</td>
                <td align='center'><b>Tak Terduga</td>
                <td align='center'><b>Transfer</td>
                <td align='center'><b>Operasi</td>
                <td align='center'><b>Modal</td>
                <td align='center'><b>Tak Terduga</td>
                <td align='center'><b>Transfer</td>
            </tr>
            </thead>
            <tr>
                <td align='center' colspan='4'><b>1</td>
                <td align='center'><b>2</td>
                <td align='center'><b>3</td>
                <td align='center'><b>4</td>
                <td align='center'><b>5</td>
                <td align='center'><b>6</td>
                <td align='center'><b>7</td>
                <td align='center'><b>8</td>
                <td align='center'><b>9</td>
                <td align='center'><b>10</td>
                <td align='center'><b>11</td>
                <td align='center'><b>12</td>
                <td align='center'><b>13</td>
                <td align='center'><b>14</td>
            </tr>";
        $jumlah=0; $total=0; $tb51=0; $tb52=0; $tb53=0; $tb54=0;
        $jumlah_sempurna=0; $total_sempurna=0; $tb51_sempurna=0; $tb52_sempurna=0; $tb53_sempurna=0; $tb54_sempurna=0;
        $que=$this->db->query("

            SELECT *, (select nm_bidang_urusan from ms_bidang_urusan WHERE kd_bidang_urusan=right(urus,4)) nama
            from (
            SELECT kd_fungsi+'.'+left(kd_sub_kegiatan,4) urus, 
            sum(b51) b51, sum(b52) b52,sum(b53) b53,sum(b54) b54,
            sum(b51_sempurna) b51_sempurna, sum(b52_sempurna) b52_sempurna,sum(b53_sempurna) b53_sempurna,sum(b54_sempurna) b54_sempurna
            from (
            select kd_sub_kegiatan,
            isnull(sum(case WHEN left(kd_rek6,2)='51' then $status_anggaran1 else 0 end),0) b51,
            isnull(sum(case WHEN left(kd_rek6,2)='52' then $status_anggaran1 else 0 end),0) b52,
            isnull(sum(case WHEN left(kd_rek6,2)='53' then $status_anggaran1 else 0 end),0) b53,
            isnull(sum(case WHEN left(kd_rek6,2)='54' then $status_anggaran1 else 0 end),0) b54,
            isnull(sum(case WHEN left(kd_rek6,2)='51' then $status_anggaran2 else 0 end),0) b51_sempurna,
            isnull(sum(case WHEN left(kd_rek6,2)='52' then $status_anggaran2 else 0 end),0) b52_sempurna,
            isnull(sum(case WHEN left(kd_rek6,2)='53' then $status_anggaran2 else 0 end),0) b53_sempurna,
            isnull(sum(case WHEN left(kd_rek6,2)='54' then $status_anggaran2 else 0 end),0) b54_sempurna
            from trdrka WHERE left(kd_rek6,1)='5' GROUP BY kd_sub_kegiatan ) oke inner join ms_bidang_urusan punya
            on left(kd_sub_kegiatan,4)=punya.kd_bidang_urusan
            GROUP BY left(kd_sub_kegiatan,4),kd_fungsi) mantap
            UNION all


            -------kd_fungsi
            select *, (select nm_fungsi from ms_fungsi WHERE kd_fungsi=urus) nama from (
            select left(kd_fungsi,1) urus, 
            sum(b51) b51, sum(b52) b52,sum(b53) b53,sum(b54) b54,
            sum(b51_sempurna) b51_sempurna, sum(b52_sempurna) b52_sempurna,sum(b53_sempurna) b53_sempurna,sum(b54_sempurna) b54_sempurna
            from(
            SELECT kd_fungsi,left(kd_sub_kegiatan,4) urus, 
            sum(b51) b51, sum(b52) b52,sum(b53) b53,sum(b54) b54,
            sum(b51_sempurna) b51_sempurna, sum(b52_sempurna) b52_sempurna,sum(b53_sempurna) b53_sempurna,sum(b54_sempurna) b54_sempurna
            from (
            select kd_sub_kegiatan,
            isnull(sum(case WHEN left(kd_rek6,2)='51' then $status_anggaran1 else 0 end),0) b51,
            isnull(sum(case WHEN left(kd_rek6,2)='52' then $status_anggaran1 else 0 end),0) b52,
            isnull(sum(case WHEN left(kd_rek6,2)='53' then $status_anggaran1 else 0 end),0) b53,
            isnull(sum(case WHEN left(kd_rek6,2)='54' then $status_anggaran1 else 0 end),0) b54,
            isnull(sum(case WHEN left(kd_rek6,2)='51' then $status_anggaran2 else 0 end),0) b51_sempurna,
            isnull(sum(case WHEN left(kd_rek6,2)='52' then $status_anggaran2 else 0 end),0) b52_sempurna,
            isnull(sum(case WHEN left(kd_rek6,2)='53' then $status_anggaran2 else 0 end),0) b53_sempurna,
            isnull(sum(case WHEN left(kd_rek6,2)='54' then $status_anggaran2 else 0 end),0) b54_sempurna
            from trdrka WHERE left(kd_rek6,1)='5' GROUP BY kd_sub_kegiatan ) oke inner join ms_bidang_urusan punya
            on left(kd_sub_kegiatan,4)=punya.kd_bidang_urusan
            GROUP BY left(kd_sub_kegiatan,4),kd_fungsi)yes GROUP BY left(kd_fungsi,1)) jiwa
            order by urus

            ");
        foreach($que->result() as $ri){
            $kode=$ri->urus;
            $nama=$ri->nama;

  
                $b51 =$ri->b51;
                $b52 =$ri->b52;
                $b53 =$ri->b53;
                $b54 =$ri->b54;
                $jumlah=$b51+$b52+$b53+$b54;
     



                $b51_sempurna =$ri->b51_sempurna;
                $b52_sempurna =$ri->b52_sempurna;
                $b53_sempurna =$ri->b53_sempurna;
                $b54_sempurna =$ri->b54_sempurna;
                $jumlah_sempurna=$b51_sempurna+$b52_sempurna+$b53_sempurna+$b54_sempurna;
   

            $selisih=$this->support->format_bulat($jumlah_sempurna-$jumlah);
            if($jumlah==0){
                $persen=$this->support->format_bulat(0);
            }else{
                $persen=$this->support->format_bulat((($jumlah_sempurna-$jumlah)/$jumlah)*100);
            }

            if(strlen($kode)==1){
                $total=$total+$jumlah;
                $tb51=$tb51+$b51;
                $tb52=$tb52+$b52;
                $tb53=$tb53+$b53;
                $tb54=$tb54+$b54;
                $total_sempurna=$total_sempurna+$jumlah_sempurna;
                $tb51_sempurna=$tb51_sempurna+$b51_sempurna;
                $tb52_sempurna=$tb52_sempurna+$b52_sempurna;
                $tb53_sempurna=$tb53_sempurna+$b53_sempurna;
                $tb54_sempurna=$tb54_sempurna+$b54_sempurna;
                $selisih_total=$this->support->format_bulat($total_sempurna-$total);
                if($jumlah==0){
                    $persen_total=$this->support->format_bulat(0);
                }else{
                    $persen_total=$this->support->format_bulat((($total_sempurna-$total)/$total)*100);
                }

            }

            $tabel.="<tr>
                        <td align='center'>".substr($kode,0,1)."</td>
                        <td align='center'>".substr($kode,2,2)."</td>
                        <td align='center'>".substr($kode,5,1)."</td>
                        <td align='center'>".substr($kode,7,2)."</td>
                        <td  align='left'>$nama</td>
                        <td align='right'>".number_format($b51,"0",",",".")."</td>
                        <td align='right'>".number_format($b52,"0",",",".")."</td>
                        <td align='right'>".number_format($b53,"0",",",".")."</td>
                        <td align='right'>".number_format($b54,"0",",",".")."</td>
                        <td align='right'>".number_format($jumlah,"0",",",".")."</td>
                        <td align='right'>".number_format($b51_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($b52_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($b53_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($b54_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($jumlah_sempurna,"0",",",".")."</td>
                        <td align='right'>$selisih</td>
                        <td align='right'>$persen</td>
                    </tr>";

        }
        $tabel.="<tr>
                    <td colspan='5' align='center'>JUMLAH</td>
                    <td align='right'>".number_format($tb51,"0",",",".")."</td>
                    <td align='right'>".number_format($tb52,"0",",",".")."</td>
                    <td align='right'>".number_format($tb53,"0",",",".")."</td>
                    <td align='right'>".number_format($tb54,"0",",",".")."</td>
                    <td align='right'>".number_format($total,"0",",",".")."</td>
                    <td align='right'>".number_format($tb51_sempurna,"0",",",".")."</td>
                    <td align='right'>".number_format($tb52_sempurna,"0",",",".")."</td>
                    <td align='right'>".number_format($tb53_sempurna,"0",",",".")."</td>
                    <td align='right'>".number_format($tb54_sempurna,"0",",",".")."</td>
                    <td align='right'>".number_format($total_sempurna,"0",",",".")."</td>
                    <td align='right'>$selisih_total</td>
                    <td align='right'>$persen_total</td>
                </tr>";
        $tabel.="</table>";

            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tabel.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    

        if($pdf==0){
            echo $tabel;
        }else if($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda V.xls");
            echo $tabel;
        }else{
            $this->master_pdf->_mpdf_down('Lampiran Perda',' V',$tabel,10,10,10,'1');
        }
    }

    function perda6($tgl_ttd='',$perda='',$dowload='',$jenis_anggaran='',$judul=''){
        $tahun=$this->session->userdata('pcThang');

        if($perda=='PERWA_MURNI'){
            $lam="perwa";
        }else{
            $lam="perda";
        }

        $tbl='';
        $nomor="";
        $tgl_lam="";
        $isi="";
        $anggaran=$jenis_anggaran;
        $jenis_anggaran='nilai_ubah';
        if($jenis_anggaran=='nilai'){
            $jenis_anggaran='1';
            $ubah='murni';
        }else if($jenis_anggaran=='nilai_sempurna'){
            $jenis_anggaran='2';
            $ubah='geser';
        }else{
            $jenis_anggaran='3';
            $ubah='ubah';
        }



        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='perda'");

        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $cetak="";
        $cetak .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN VI <br>PERATURAN DAERAH<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";

        $cetak .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            REKAPITULASI BELANJA UNTUK PEMENUHAN SPM
 <br>
                            TAHUN ANGGARAN $tahun
                            </td>
                    </tr>
                </table>";

        $cetak.="<table style='border-collapse:collapse; font-size:12px' width='100%' border='1' cellpadding='10px'>
                    <tr>
                        <td align='center'><b>NO</td>
                        <td align='center'><b>Jenis Pelayanan Dasar</td>
                        <td align='center'><b>Kegiatan</td>
                        <td align='center'><b>Sub Kegiatan</td>
                        <td align='center'><b>Alokasi Anggaran Penyusunan</td>
                        <td align='center'><b>Alokasi Anggaran Perubahan</td>
                    </tr>";  


        $sql=$this->db->query("SELECT * FROM (SELECT '0' urut, left(kode,1) kode, '' kodegiat, '' kodesub, 0 jumlah, 0 jumlah_ubah, bidang_spm nama from spm_pelayanan_dasar GROUP BY left(kode,1),bidang_spm
                UNION all
                select '1' urut, kode, '', '', 0,0, jns_pelayanan_dasar from spm_pelayanan_dasar
                UNION all
                select '2' urut,b.kode, b.kd_kegiatan, left(kd_sub_kegiatan,12) giat, sum(nilai),sum($anggaran), (select nm_kegiatan from ms_kegiatan where kd_kegiatan=b.kd_kegiatan) from trdrka a inner join list_spm_dasar b on b.kd_kegiatan=left(a.kd_sub_kegiatan,12)
                GROUP BY left(kd_sub_kegiatan,12),b.kode, b.kd_kegiatan

                UNION all

                select '3' urut, b.kode, b.kd_kegiatan, kd_sub_kegiatan, sum(nilai),sum($anggaran), nm_sub_kegiatan from trdrka a inner join list_spm_dasar b on b.kd_kegiatan=left(a.kd_sub_kegiatan,12)
                GROUP BY kd_sub_kegiatan,b.kode, b.kd_kegiatan, nm_sub_kegiatan

                UNION all

                select '4' urut, b.kode, b.kd_kegiatan, '9.99.99.99', sum(nilai),sum($anggaran), (select nm_kegiatan from ms_kegiatan where kd_kegiatan=b.kd_kegiatan) from trdrka a inner join list_spm_dasar b on b.kd_kegiatan=left(a.kd_sub_kegiatan,12)
                GROUP BY left(a.kd_sub_kegiatan,12),b.kode, b.kd_kegiatan

                UNION all
                select '5' urut, c.kode, '9.99', '9.99.99.99', sum(nilai),sum($anggaran), c.jns_pelayanan_dasar from trdrka a inner join list_spm_dasar b on b.kd_kegiatan=left(a.kd_sub_kegiatan,12) inner join spm_pelayanan_dasar c on c.kode=b.kode
                GROUP BY c.jns_pelayanan_dasar,c.kode

                UNION all
                select '6' urut, left(b.kode,1)+'99', '9.99', '9.99.99.99', sum(nilai),sum($anggaran), (SELECT top 1 bidang_spm  from spm_pelayanan_dasar
                 where left(kode,1)=left(b.kode,1)) from trdrka a inner join list_spm_dasar b on b.kd_kegiatan=left(a.kd_sub_kegiatan,12)
                GROUP BY left(b.kode,1)

                ) OKE order by kode, urut, kodesub
                ");

        foreach ($sql->result() as $oke) {

            switch ($oke->urut) {
                case '0':
                    $cetak.="<tr>
                                    <td align='center'></td>
                                    <td colspan='5'><b>$oke->nama</td>
                                </tr>";  
                    break;
                case '1':
                    $cetak.="<tr>
                                    <td align='center'></td>
                                    <td colspan='5'><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$oke->nama</td>
                                </tr>";  
                    break;
                case '2':
                    $cetak.="<tr>
                                    <td align='center'></td>
                                    <td align='center'></td>
                                    <td colspan='4'><b>$oke->nama</td>
                                </tr>";  
                    break;
                case '3':
                    $cetak.="<tr>
                                    <td align='left'></td>
                                    <td align='left'></td>
                                    <td align='left'></td>
                                    <td align='left'>$oke->nama</td>
                                    <td align='right'>".number_format($oke->jumlah,0,',','.')."</td>
                                    <td align='right'>".number_format($oke->jumlah_ubah,0,',','.')."</td>
                                </tr>";  
                    break;    
                case '4':
                    $cetak.="<tr>
                                    <td align='left'></td>
                                    <td align='right' colspan='3'><b>Jumlah $oke->nama</td>
                                    <td align='right'><b>".number_format($oke->jumlah,0,',','.')."</td>
                                    <td align='right'><b>".number_format($oke->jumlah_ubah,0,',','.')."</td>
                                </tr>";   
                    break;   
                case '5':
                    $cetak.="<tr>
                                    <td align='left'></td>
                                    <td align='right' colspan='3'><b>Jumlah $oke->nama</td>
                                    <td align='right'><b>".number_format($oke->jumlah,0,',','.')."</td>
                                    <td align='right'><b>".number_format($oke->jumlah_ubah,0,',','.')."</td>
                                </tr>";  
                    break;   
                case '6':
                    $cetak.="<tr>
                                    <td align='left'></td>
                                    <td align='right' colspan='3'><b>Jumlah $oke->nama</td>
                                    <td align='right'><b>".number_format($oke->jumlah,0,',','.')."</td>
                                    <td align='right'><b>".number_format($oke->jumlah_ubah,0,',','.')."</td>
                                </tr>";  
                    break;   
                default:
                    # code...
                    break;
            }

        }

         $cetak.="</table>";  
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $cetak.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";  

       $cetak.="<table>";

        if($dowload==0){
            echo $cetak;
        }else if($dowload==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda VII.xls");
            echo $cetak;
        }else{
            $this->master_pdf->_mpdf_down('Lampiran Perda',' VII',$cetak,10,10,10,'1');
        } 
    }


    function perda7($tgl_ttd='',$perda='',$dowload='',$jenis_anggaran='',$judul=''){
        $tahun=$this->session->userdata('pcThang');

        if($perda=='PERWA_MURNI'){
            $lam="perwa";
        }else{
            $lam="perda";
        }

        $tbl='';
        $nomor="";
        $tgl_lam="";
        $isi="";

        if($jenis_anggaran=='nilai'){
            $jenis_anggaran='1';
            $ubah='murni';
        }else if($jenis_anggaran=='nilai_sempurna'){
            $jenis_anggaran='2';
            $ubah='geser';
        }else{
            $jenis_anggaran='3';
            $ubah='ubah';
        }



        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='$lam'");

        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }
        $cetak="";
     
        
        $cetak .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN VII <br>PERATURAN DAERAH<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";

        $cetak .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            SINKRONISASI PROGRAM PADA RPJMD DENGAN APBD <br>
                            TAHUN ANGGARAN $tahun
                            </td>
                    </tr>
                </table>";

        $cetak.="<table  width='100%' border='1' style='border-collapse: collapse; font-size:12px' cellpadding='5px'>
                   <thead>
                    <tr>
                        <td align='center' colspan='3'><b>KODE</td>
                        <td align='center'><b>URAIAN</td>
                        <td align='center'><b>RPJMD (Rp) </td>
                        <td align='center'><b>APBD <br>PENYUSUNAN(Rp) </td>
                        <td align='center'><b>APBD <br>PERUBAHAN (Rp) </td>
                    </tr>
                    </thead>";        



        $sql=$this->db->query("SELECT *, left(kode,1) urus, right(left(kode,4),2) bid_urus, right(kode,2) program  FROM V_perda7 order by kode");
        foreach($sql->result() as $oke){

        $kode = explode(".",$oke->kode);

        if($jenis_anggaran=='1'){
            $ubah=$oke->murni;
        }else if($jenis_anggaran=='2'){
            $ubah=$oke->geser;
        }else{
            $ubah=$oke->ubah;
        }

        $cetak.="<tr>
                        <td align='center'>".$kode[0]."</td>
                        <td align='center'>".$kode[1]."</td>
                        <td align='center'>".$kode[2]."</td>
                        <td align='left'>$oke->nama</td>
                        <td align='right'>".number_format($oke->murni,0,',','.')."</td>
                        <td align='right'>".number_format($oke->murni,0,',','.')."</td>
                        <td align='right'>".number_format($ubah,0,',','.')."</td>
                    </tr>
               ";  

        }
        $cetak.="</table> ";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $cetak.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";  

        

        if($dowload==0){
            echo $cetak;
        }else if($dowload==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda VII.xls");
            echo $cetak;
        }else{
 
           $this->master_pdf->_mpdf('',$cetak,10,10,10,'0');
        } 

    }

     function perda9($jns,$anggaran,$tgl,$ss,$judul){
        if($anggaran=='nilai'){
            $jenis_anggaran='1';
        }if ($anggaran=='nilai_sempurna'){
            $jenis_anggaran='2';
        }else{
            $jenis_anggaran='3';
        }

        

        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='perda'");

        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $cetak="";
        $cetak .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN IX <br>PERATURAN DAERAH<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";
        $cetak.="<table style='border-collapse:collapse; font-size:12px; ' width='100%' align='center' border='0' cellspacing='0' cellpadding='5'>
                    <tr>
                        <td align='center'>KOTA PONTIANAK<BR> SIKRONISASI PROGRAM PRIORITAS NASIONAL DENGAN PROGRAM PRIORITAS DAERAH<br>TAHUN ANGGARAN $tahun</td>
                    </tr>
                </table>";
         $cetak.="<table style='border-collapse:collapse; font-size:10px; ' width='100%' align='center' border='1' cellspacing='0' cellpadding='5'>
          <thead>

                    <tr>
                        <td align='center' rowspan='2'><b>No</td>
                        <td align='center' rowspan='2'><b>Prioritas Pembangunan Nasional</td>
                        <td align='center' rowspan='2'><b>Program</td>
                        <td align='center' rowspan='2'><b>SKPD</td>
                        <td align='center' colspan='4'><b>Alokasi Anggaran Belanja Dalam Rancangan APBD</td>
                        <td align='center' rowspan='2'><b>Jumlah</td>
                    </tr>
                    <tr>
                        <td align='center'><b>Belanja Operasi</td>
                        <td align='center'><b>Belanja Modal</td>
                        <td align='center'><b>Belanja Tak Terduga</td>
                        <td align='center'><b>Belanja Transfer</td>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td align='center' colspan='9' style='border-bottom:none; border-left:none; border-right:none'></td>
                    </tr>
                </tfoot>

                    ";


            $sql="SELECT a.jns_prioritas, a.nm_prioritas, left(cc.kd_program,7) prog, (select nm_program from ms_program WHERE kd_program=left(cc.kd_program,7)) nm_prog, isnull(sum(operasi),0) opera, isnull(sum(modal),0) moda, isnull(sum(terduga),0) duga, isnull(sum(transfer),0) trans, (select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_skpd from(
                select left(kd_sub_kegiatan,7) kd_program, left(kd_skpd,17) kd_skpd,
                sum(case when left(kd_rek6,2)=51 then $anggaran else 0 end) as operasi,
                sum(case when left(kd_rek6,2)=52 then $anggaran else 0 end) as modal,
                sum(case when left(kd_rek6,2)=53 then $anggaran else 0 end) as terduga,
                sum(case when left(kd_rek6,2)=54 then $anggaran else 0 end) as transfer
                from trdrka WHERE left(kd_rek6,1)=5 GROUP by left(kd_sub_kegiatan,7),left(kd_skpd,17) )cc 
                inner join mapping_sinkron_anggaran a on cc.kd_program=a.kd_program and cc.kd_skpd=left(a.kd_skpd,17)
                GROUP BY left(cc.kd_program,7),a.jns_prioritas,a.nm_prioritas, a.kd_skpd  order by a.jns_prioritas, left(cc.kd_program,7)
                ";
            $exe=$this->db->query( $sql);
            $no=1;
             $oke=0;
            $ttrans=0; $topera=0; $tduga=0; $ttrans=0; $tmodal=0; $tjumlah=0;
            foreach($exe->result() as  $a){

                $jns_prioritas=$a->jns_prioritas;
                $prio = $a->nm_prioritas;
                $prog = $a->prog;
                $nm_prog=$a->nm_prog;
                $nm_skpd=$a->nm_skpd;
                $opera =$a->opera;
                $modal =$a->moda;
                $duga =$a->duga;
                $trans =$a->trans;

                $topera=$topera+$opera;
                $tmodal=$tmodal+$modal;
                $tduga=$tduga+$duga;
                $ttrans=$ttrans+$trans;
                $jumlah=$opera+$modal+$duga+$trans;
                $tjumlah=$tjumlah+$jumlah;


                if($jns_prioritas!=$oke){



                $cetak.="<tr>
                            <td  align='center'  style='border-bottom:none;'>$jns_prioritas</td>
                            <td  align='left' style='border-bottom:none;'>$prio</td>
                            <td  align='left'>$nm_prog</td>
                            <td  align='left'>$nm_skpd</td>
                            <td  align='right'>".number_format($opera,"0",",",".")."</td>
                            <td  align='right'>".number_format($modal,"0",",",".")."</td>
                            <td  align='right'>".number_format($duga,"0",",",".")."</td>
                            <td  align='right'>".number_format($trans,"0",",",".")."</td>
                            <td  align='right'>".number_format($jumlah,"0",",",".")."</td>
                        </tr>";
                        $oke=$jns_prioritas;

                }else{
                    $cetak.="<tr>
                                <td  align='center' style='border-bottom:none; border-top:none;'></td>
                                <td  align='left' style='border-bottom:none; border-top:none;' ></td>
                                <td  align='left'>$nm_prog</td>
                                <td  align='left'>$nm_skpd</td>
                                <td  align='right'>".number_format($opera,"0",",",".")."</td>
                                <td  align='right'>".number_format($modal,"0",",",".")."</td>
                                <td  align='right'>".number_format($duga,"0",",",".")."</td>
                                <td  align='right'>".number_format($trans,"0",",",".")."</td>
                                <td  align='right'>".number_format($jumlah,"0",",",".")."</td>
                            </tr>";
                            $oke=$jns_prioritas;

                }
/*
                $cetak.="<tr>
                            <td  align='center'>$no</td>
                            <td  align='left'  >$prio</td>
                            <td  align='left'>$nm_prog</td>
                            <td  align='left'>$nm_skpd</td>
                            <td  align='right'>".number_format($opera,"0",",",".")."</td>
                            <td  align='right'>".number_format($modal,"0",",",".")."</td>
                            <td  align='right'>".number_format($duga,"0",",",".")."</td>
                            <td  align='right'>".number_format($trans,"0",",",".")."</td>
                            <td  align='right'>".number_format($jumlah,"0",",",".")."</td>
                        </tr>";*/

                $no++;
            }
                $cetak.="<tr>
                            <td  align='center' colspan='4'>TOTAL</td>
                            <td  align='right'>".number_format($topera,"0",",",".")."</td>
                            <td  align='right'>".number_format($tmodal,"0",",",".")."</td>
                            <td  align='right'>".number_format($tduga,"0",",",".")."</td>
                            <td  align='right'>".number_format($ttrans,"0",",",".")."</td>
                            <td  align='right'>".number_format($tjumlah,"0",",",".")."</td>
                        </tr></table>";
                $ttd_sin= $this->db->query("SELECT * from ms_ttd where nip='1'")->row();
                $cetak.="<table width='100%' border='0'><tr>
                            <td  align='center' colspan='5' style='border: none'></td>
                            <td  align='center' colspan='6' style='font-size:12px;border: none'>
                                <br>
                                ".$ttd_sin->jabatan."<br>
                                 <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <b>".$ttd_sin->nama."</b>
                                <br>

                            </td>
                        </tr>";
             $cetak.= "</table>";
            switch ($jns) {
                case '1':
                    echo $cetak;
                    break;
                
                case '2':
                   $this->tukd_model->_mpdf('',$cetak,10,10,10,'L');
                    break;

                case '3':
                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename= Sinkronisasi.xls");
                    echo $cetak;
                    break;
            }


     }   

    function perda10(){
        $tahun=$this->session->userdata('pcThang');
        $cetak="<table  width='100%' border='0' style='border-collapse: collapse;' cellpadding='10px'>
                    <tr>
                        <td align='center'>KOTA PONTIANAK<BR>DAFTAR JUMLAH PEGAWAI PER GOLONGAN DAN PER JABATAN<br>TAHUN ANGGARAN $tahun</td>
                    </tr>
                <table>";

          $cetak.="<table  width='100%' border='1' style='border-collapse: collapse;'>
                    <tr>
                        <td align='center' >GOLONGAN/ RUANG</td>
                        <td align='center' colspan='5'>ESELON</td>
                        <td align='center' colspan='2'>NON ESELON</td>
                        <td align='center' rowspan='2' >JUMLAH</td>
                    </tr>
                    <tr>
                        <td align='center' ></td>
                        <td align='center' >I</td>
                        <td align='center' >II</td>
                        <td align='center' >III</td>
                        <td align='center' >IV</td>
                        <td align='center' >V</td>
                        <td align='center' >TENAGA FUNGSIONAL</td>
                        <td align='center' >STAF</td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan IV/e</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan IV/d</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan IV/c</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan IV/b</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan IV/a</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>JUMLAH GOLONGAN IV</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>&nbsp;</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan III/e</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan III/d</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan III/c</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan III/b</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan III/a</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>JUMLAH GOLONGAN III</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>&nbsp;</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan II/e</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan II/d</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan II/c</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan II/b</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan II/a</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>JUMLAH GOLONGAN II</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>&nbsp;</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan I/e</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan I/d</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan I/c</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan I/b</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>Golongan I/a</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>JUMLAH GOLONGAN I</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>&nbsp;</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    <tr>
                        <td align='center'>TOTAL</td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                        <td align='center'></td>
                    </tr>
                    </table>"; 

           $cetak .="</table>
                <table align='center' width='100%'>
                        <tr>
                        <td colspan='7' border='0'>&nbsp;</td>
                        <td colspan='5' align='center' border='0'>
                            ......., tanggal ......... <br>
                            &nbsp;
                            Wali Kota
                            <br><br><br><br><br>
                            (tanda tangan)<br>(nama lengkap)

                        </td>
                    </tr>   
                </table>
                ";

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda 11.xls");
            echo $cetak;              
    }

    function perda11(){

        $tahun=$this->session->userdata('pcThang');
        $cetak="<table  width='100%' border='0' style='border-collapse: collapse;' cellpadding='10px'>
                    <tr>
                        <td align='center'>KOTA PONTIANAK<BR>DAFTAR PIUTANG DAERAH<br>TAHUN ANGGARAN $tahun</td>
                    </tr>
                <table>";

        $cetak.="<table  width='100%' border='1' style='border-collapse: collapse;'>
                    <tr>
                        <td align='center' >No</td>
                        <td align='center' >Uraian Rincian Piutang</td>
                        <td align='center' >Tahun Pengakuan piutang</td>
                        <td align='center' >Jumlah piutang sampai dengan tahun N-2</td>
                        <td align='center' >Perkiraan Penambahan Tahun N-1</td>
                        <td align='center' >Perkiraan Pengurangan tahun N-1</td>
                        <td align='center' >Perkiraan Saldo Akhir tahun N-1</td>
                    </tr>
                    <tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                        <td align='center'>5</td>
                        <td align='center'>6</td>
                        <td align='center'>7 = 4+5-6</td>
                    </tr>";

            for ($i=0; $i < 7 ; $i++) { 
            $cetak .="<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>";
            }

           $cetak .="</table>
                <table align='center' width='100%'>
                        <tr>
                        <td colspan='7' border='0'>&nbsp;</td>
                        <td colspan='5' align='center' border='0'>
                            ......., tanggal ......... <br>
                            &nbsp;
                            Wali Kota
                            <br><br><br><br><br>
                            (tanda tangan)<br>(nama lengkap)

                        </td>
                    </tr>   
                </table>
                ";

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda 11.xls");
            echo $cetak;
    }


    function perda12(){

        $tahun=$this->session->userdata('pcThang');
        $cetak="<table  width='100%' border='0' style='border-collapse: collapse;' cellpadding='10px'>
                    <tr>
                        <td align='center'>KOTA PONTIANAK<BR>DAFTAR PENYERTAAN MODAL DAERAH DAN INVESTASI DAERAH LAINNYA<br>TAHUN ANGGARAN $tahun</td>
                    </tr>
                <table>";

        $cetak.="<table  width='100%' border='1' style='border-collapse: collapse;'>
                    <tr>
                        <td align='center' >No</td>
                        <td align='center' >Tahun Penyertaan Modal</td>
                        <td align='center' >Nama Badan/ lembaga/Pihak Ketiga</td>
                        <td align='center' >Dasar Hukum Penyertaan Modal (Investasi Daerah)</td>
                        <td align='center' >Bentuk Penyertaan Modal (Investasi Daerah)</td>
                        <td align='center' >Jumlah Penyertaan Modal Investasi Daerah</td>
                        <td align='center' >Jumlah Modal Yang telah di sertakan sampai tahun Anggaran </td>
                        <td align='center' >Penyertaan Modal Tahun ini</td>
                        <td align='center' >Jumlah modal yang telah disertakan sampai dengan tahun ini</td>
                        <td align='center' >Sisa modal yang belum disertakan</td>
                        <td align='center' >Hasil penyertaan modal (investasi) daerah tahun ini</td>
                        <td align='center' >Jumlah modal (investasi) yang akan diterima kembali tahun ini</td>
                        <td align='center' >Jumlah sisa modal (investasi) yang di sertakan sampai dengan tahun ini</td>
                    </tr>
                    <tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                        <td align='center'>5</td>
                        <td align='center'>6</td>
                        <td align='center'>7</td>
                        <td align='center'>8</td>
                        <td align='center'>9</td>
                        <td align='center'>10</td>
                        <td align='center'>11</td>
                        <td align='center'>12</td>
                        <td align='center'>13</td>
                    </tr>";

            for ($i=0; $i < 7 ; $i++) { 
            $cetak .="<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>";
            }

           $cetak .="</table>
                <table align='center' width='100%'>
                        <tr>
                        <td colspan='7' border='0'>&nbsp;</td>
                        <td colspan='5' align='center' border='0'>
                            ......., tanggal ......... <br>
                            &nbsp;
                            Wali Kota
                            <br><br><br><br><br>
                            (tanda tangan)<br>(nama lengkap)

                        </td>
                    </tr>   
                </table>
                ";

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda 12.xls");
            echo $cetak;
    }

    function perda13(){
        echo "Not Found";
    }

    function perda14(){

        $tahun=$this->session->userdata('pcThang');
        $cetak="<table  width='100%' border='0' style='border-collapse: collapse;' cellpadding='10px'>
                    <tr>
                        <td align='center'>KOTA PONTIANAK<BR>DAFTAR PINJAMAN DAERAH<br>TAHUN ANGGARAN $tahun</td>
                    </tr>
                <table>";

        $cetak.="<table  width='100%' border='1' style='border-collapse: collapse;'>
                    <tr>
                        <td align='center' rowspan='2'>No</td>
                        <td align='center' rowspan='2'>Nama SKPD</td>
                        <td align='center' rowspan='2'>Nama Sub Kegiatan</td>
                        <td align='center' rowspan='2'>Lokasi Sub Kegiatan</td>

                        <td align='center' colspan='2'>Jumlah Tahun Awal Penganggaran (Rp)</td>
                        <td align='center' rowspan='2'>Jumlah Realisasi sd Akhir TA T-2 (Rp)</td>

                        <td align='center' colspan='2'>Jumlah Anggaran Tahun T-1 (Rp)</td>
                        <td align='center' rowspan='2'>Jumlah Realisasi sd Akhir TA T-1 (Rp)</td>

                        <td align='center' colspan='2'>Jumlah Sisa Anggaran yang Dianggarkan Dalam Tahun Ini (Rp)</td>
                    </tr>
                    <tr>
                        <td align='center'>APBD TA T-2</td>
                        <td align='center'>APBD TA T-2</td>
                        <td align='center'>APBD TA T-1</td>
                        <td align='center'>APBD TA T-1</td>
                        <td align='center'>APBD TAT</td>
                        <td align='center'>APBD TAT</td>
                    </tr>
                    <tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                        <td align='center'>5</td>
                        <td align='center'>6</td>
                        <td align='center'>7</td>
                        <td align='center'>8</td>
                        <td align='center'>9</td>
                        <td align='center'>10</td>
                        <td align='center'>11</td>
                        <td align='center'>12</td>
                    </tr>";

            for ($i=0; $i < 7 ; $i++) { 
            $cetak .="<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>";
            }

           $cetak .="</table>
                <table align='center' width='100%'>
                        <tr>
                        <td colspan='7' border='0'>&nbsp;</td>
                        <td colspan='5' align='center' border='0'>
                            ......., tanggal ......... <br>
                            &nbsp;
                            Wali Kota
                            <br><br><br><br><br>
                            (tanda tangan)<br>(nama lengkap)

                        </td>
                    </tr>   
                </table>
                ";

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda 14.xls");
            echo $cetak;
    }


    function perda15(){

        $tahun=$this->session->userdata('pcThang');
        $cetak="<table  width='100%' border='0' style='border-collapse: collapse;' cellpadding='10px'>
                    <tr>
                        <td align='center'>KOTA PONTIANAK<BR>DAFTAR DANA CADANGAN<br>TAHUN ANGGARAN $tahun</td>
                    </tr>
                <table>";

        $cetak.="<table  width='100%' border='1' style='border-collapse: collapse;'>
                    <tr>
                        <td align='center' >No</td>
                        <td align='center' >Tujuan pembentukan dana cadangan</td>
                        <td align='center' >Dasar hukum pembentukan dana cadangan</td>
                        <td align='center' >Jumlah dana cadangan yang direncanakan (Rp)</td>
                        <td align='center' >Saldo Awal (Rp)</td>
                        <td align='center' >Transfer dari Kas daerah (Rp)</td>
                        <td align='center' >Transfer ke kas daerah (Rp)</td>
                        <td align='center' >Saldo akhir (Rp)</td>
                        <td align='center' >Sisa dana yang Belum dicadangkan (Rp)</td>
                    </tr>
                    <tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                        <td align='center'>5</td>
                        <td align='center'>6</td>
                        <td align='center'>7</td>
                        <td align='center'>8</td>
                        <td align='center'>9</td>
                    </tr>";

            for ($i=0; $i < 7 ; $i++) { 
            $cetak .="<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>";
            }

           $cetak .="</table>
                <table align='center' width='100%'>
                        <tr>
                        <td colspan='7' border='0'>&nbsp;</td>
                        <td colspan='5' align='center' border='0'>
                            ......., tanggal ......... <br>
                            &nbsp;
                            Wali Kota
                            <br><br><br><br><br>
                            (tanda tangan)<br>(nama lengkap)

                        </td>
                    </tr>   
                </table>
                ";

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda 15.xls");
            echo $cetak;
    }

    function perda16(){

        $tahun=$this->session->userdata('pcThang');
        $cetak="<table  width='100%' border='0' style='border-collapse: collapse;' cellpadding='10px'>
                    <tr>
                        <td align='center'>KOTA PONTIANAK<BR>DAFTAR PINJAMAN DAERAH<br>TAHUN ANGGARAN $tahun</td>
                    </tr>
                <table>";

        $cetak.="<table  width='100%' border='1' style='border-collapse: collapse;'>
                    <tr>
                        <td align='center' rowspan='2'>No</td>
                        <td align='center' rowspan='2'>Sumber Pinjaman/ Obligasi Daerah</td>
                        <td align='center' rowspan='2'>Dasar Hukum Pinjaman/ Obligasi</td>
                        <td align='center' rowspan='2'>Tanggal/ Tahun Perjanjian Pinjaman/Obligasi</td>
                        <td align='center' rowspan='2'>Jumlah Pinjaman/Nilai Nominal Obligasi (Rp)</td>
                        <td align='center' rowspan='2'>Jangka Waktu Pinjaman (tahun)</td>
                        <td align='center' rowspan='2'>Persentase bunga pinjaman %</td>
                        <td align='center' rowspan='2'>Tujuan Penggunaan Pinjaman</td>
                        <td align='center' colspan='2'>Jumlah Pembayaran tahun ini (Rp)</td>
                        <td align='center' colspan='2'>Jumlah Sisa Pembayaran</td>
                    </tr>
                    <tr>
                        <td align='center'>Pokok Pinjaman Daerah</td>
                        <td align='center'>Bunga</td>
                        <td align='center'>Pokok Pinjaman Daerah</td>
                        <td align='center'>Bunga</td>
                    </tr>
                    <tr>
                        <td align='center'>1</td>
                        <td align='center'>2</td>
                        <td align='center'>3</td>
                        <td align='center'>4</td>
                        <td align='center'>5</td>
                        <td align='center'>6</td>
                        <td align='center'>7</td>
                        <td align='center'>8</td>
                        <td align='center'>9</td>
                        <td align='center'>10</td>
                        <td align='center'>11</td>
                        <td align='center'>12</td>
                    </tr>";

            for ($i=0; $i < 7 ; $i++) { 
            $cetak .="<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>";
            }

           $cetak .="</table>
                <table align='center' width='100%'>
                        <tr>
                        <td colspan='7' border='0'>&nbsp;</td>
                        <td colspan='5' align='center' border='0'>
                            ......., tanggal ......... <br>
                            &nbsp;
                            Wali Kota
                            <br><br><br><br><br>
                            (tanda tangan)<br>(nama lengkap)

                        </td>
                    </tr>   
                </table>
                ";

            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda 16.xls");
            echo $cetak;
    }


    function lampiran4_pergeseran($tgl,$doc,$pdf,$status_anggaran1,$status_anggaran2){

        $tgl=$this->support->tanggal_format_indonesia($tgl);
        $thn=$this->session->userdata('pcThang');

        if($doc=='PERWA_MURNI'){
            $lampiran="PEARTURAN WALIKOTA KOTA PONTIANAK";
            $judul="REKAPITULASI BELANJA <br>
MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN BESERTA HASIL  <br>
DAN SUB KEGIATAN BESERTA KELUARAN
";
            $lam="perwa";
        }else{
            $lampiran="PEARTURAN DAERAH KOTA PONTIANAK ";
            $judul="REKAPITULASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM DAN KEGIATAN BESERTA HASIL DAN SUB KEGIATAN BESERTA KELUARAN
";
            $lam="perda";
        }

        if($status_anggaran2=='nilai'){
            $jenis_anggaran='1';
        }else if($status_anggaran2=='nilai_sempurna'){
            $jenis_anggaran='2';
        }else{
            $jenis_anggaran='3';
        }
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $isi='';
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='$jenis_anggaran' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            $isi=$abc->isi;
            $tgl_lam=$abc->tanggal;
        }

        $tbl ="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='60%' style='border-right:none'></td>
                        <td colspan='2' width='40%' align='left' style='border:none'> LAMPIRAN IV <br>$lampiran<br> NOMOR $nomor <br> $isi</td>
                
                    </tr>
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'>PEMERINTAH KOTA PONTIANAK <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='3' colspan='6' align='center' ><b>Kode</td>
                    <td rowspan='3' align='center' ><b>Urusan Pemerintah Daerah</td>
                    <td colspan='5' align='center' ><b>Sebelum Perubahan</td>
                    <td colspan='5' align='center' ><b>Setelah Perubahan</td>
                    <td colspan='2' align='center' ><b>Bertambah/ (Berkurang)</td>
               </tr>
               <tr>
                    <td colspan='4' align='center'><b>Kelompok Belanja</td>
                    <td rowspan='2' align='center'><b>Jumlah</td>
                    <td colspan='4' align='center'><b>Kelompok Belanja</td>
                    <td rowspan='2' align='center'><b>Jumlah</td>
                    <td rowspan='2' align='center' ><b>Rp</td>
                    <td rowspan='2' align='center' ><b>%</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
               </tr>
                </thead>
               <tr>
                    <td align='center' colspan='6'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>
                    <td align='center'><b>8</td>
                    <td align='center'><b>9</td>
                    <td align='center'><b>10</td>
                    <td align='center'><b>11</td>
                    <td align='center'><b>12</td>
                    <td align='center'><b>13</td>
                    <td align='center'><b>14</td>                                    
               </tr>

               <tr>
                    <td align='center' colspan='6'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>                     
               </tr>

               ";


        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $tot4_sempurna=0; $tot51_sempurna=0; $tot52_sempurna=0; $tot53_sempurna=0; $tot54_sempurna=0; $tottot_sempurna=0;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2, SUBSTRING(kd,6,2) kd3, SUBSTRING(kd,9,4) kd4, SUBSTRING(kd,14,2) kd5,* from (

select * from (
select '1' urut, left(kd_sub_kegiatan,1) kd_skpd, (select nm_urusan from ms_urusan WHERE kd_urusan=left(kd_sub_kegiatan,1)) bidurusan, left(kd_sub_kegiatan,1) kd, 
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,1)
UNION all
select '2' urut,left(left(kd_sub_kegiatan,4),1)   kd_skpd, (select nm_bidang_urusan from ms_bidang_urusan WHERE kd_bidang_urusan=left(kd_sub_kegiatan,4)) bidurusan, left(kd_sub_kegiatan,4) kd, 
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,4)

UNION all

select  '3' urut, left(kd_skpd,17)+'.0000', (select nm_skpd from ms_skpd where kd_skpd=left(a.kd_skpd,17)+'.0000') nama, left(kd_sub_kegiatan,4)kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,4), left(kd_skpd,17) 

union ALL
-- program
select  '4' urut, left(kd_skpd,17)+'.0000', (select nm_program from ms_program where kd_program=left(a.kd_sub_kegiatan,7)) nama, left(kd_sub_kegiatan,7)kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,7),left(kd_skpd,17) 

union ALL
--kegiatan
select  '5' urut, left(kd_skpd,17)+'.0000', (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(a.kd_sub_kegiatan,12)) nama, left(kd_sub_kegiatan,12)kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY left(kd_sub_kegiatan,12),left(kd_skpd,17) 

union ALL


--sub kegiatan
select  '7' urut, left(kd_skpd,17)+'.0000', 
(select nm_sub_kegiatan from ms_sub_kegiatan where kd_sub_kegiatan=a.kd_sub_kegiatan) nama, 
kd_sub_kegiatan kd,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran1 else 0 end),0) pen,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran1 else 0 end),0) b51,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran1 else 0 end),0) b52,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran1 else 0 end),0) b53,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran1 else 0 end),0) b54,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran1 else 0 end),0) tot,
isnull(sum(case when left(kd_rek6,1)=4 then a.$status_anggaran2 else 0 end),0) pen_sempurna,
isnull(sum(case when left(kd_rek6,2)=51 then a.$status_anggaran2 else 0 end),0) b51_sempurna,
isnull(sum(case when left(kd_rek6,2)=52 then a.$status_anggaran2 else 0 end),0) b52_sempurna,
isnull(sum(case when left(kd_rek6,2)=53 then a.$status_anggaran2 else 0 end),0) b53_sempurna,
isnull(sum(case when left(kd_rek6,2)=54 then a.$status_anggaran2 else 0 end),0) b54_sempurna,
isnull(sum(case when left(kd_rek6,1)=5 then a.$status_anggaran2 else 0 end),0) tot_sempurna
from trdrka a WHERE left(kd_rek6,1)=5
GROUP BY a.kd_sub_kegiatan,left(kd_skpd,17) 



) cc



    ) ohyes order by kd,kd+kd_skpd,urut";
    
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode3=$ab->kd3;
            $kode4=$ab->kd4;
            $kode5=$ab->kd5;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $urut =$ab->urut;

            
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
            $tot  =$ab->tot;
            

            
            $pend_sempurna =$ab->pen_sempurna;
            $b51_sempurna  =$ab->b51_sempurna;
            $b52_sempurna  =$ab->b52_sempurna;
            $b53_sempurna  =$ab->b53_sempurna;
            $b54_sempurna  =$ab->b54_sempurna;
            $tot_sempurna  =$ab->tot_sempurna;
            

            $selisih=$this->support->format_bulat($tot_sempurna-$tot);

            if($tot==0){
                $persen=$this->support->format_bulat(0);
            }else{
                $persen=$this->support->format_bulat((($tot_sempurna-$tot)/$tot)*100);                
            }


            if($urut=='1'){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
                $tot4_sempurna=$tot4_sempurna+$pend_sempurna;
                $tot51_sempurna=$tot51_sempurna+$b51_sempurna;
                $tot52_sempurna=$tot52_sempurna+$b52_sempurna;
                $tot53_sempurna=$tot53_sempurna+$b53_sempurna;
                $tot54_sempurna=$tot54_sempurna+$b54_sempurna;
                $tottot_sempurna=$tottot_sempurna+$tot_sempurna;
                $selisih_total=$this->support->format_bulat($tottot_sempurna-$tottot);

                if($tottot==0){
                    $persen_total=$this->support->format_bulat(0);
                }else{
                    $persen_total=$this->support->format_bulat((($tottot_sempurna-$tottot)/$tottot)*100);                
                }
            }

 

            if($urut=='1' || $urut=='2'){
                    $skpd="";
            }

            $tbl.="<tr>
                        <td align='center' >$kode1</td>
                        <td align='center' >$kode2</td>
                        <td align='center' >$skpd</td>
                        <td align='center' >$kode3</td>
                        <td align='center' >$kode4</td>
                        <td align='center' >$kode5</td>
                        <td align='left'   >$urai</td>
                        <td align='right' >".number_format($b51,"0",",",".")."</td>
                        <td align='right' >".number_format($b52,"0",",",".")."</td>
                        <td align='right' >".number_format($b53,"0",",",".")."</td>
                        <td align='right' >".number_format($b54,"0",",",".")."</td>
                        <td align='right' >".number_format($tot,"0",",",".")."</td>
                        <td align='right' >".number_format($b51_sempurna,"0",",",".")."</td>
                        <td align='right' >".number_format($b52_sempurna,"0",",",".")."</td>
                        <td align='right' >".number_format($b53_sempurna,"0",",",".")."</td>
                        <td align='right' >".number_format($b54_sempurna,"0",",",".")."</td>
                        <td align='right' >".number_format($tot_sempurna,"0",",",".")."</td>
                        <td align='right' >$selisih</td>
                        <td align='right' >$persen</td>                  
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='7'>Jumlah</td>
                        <td align='right'>".number_format($tot51,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53,"0",",",".")."</td>
                        <td align='right'>".number_format($tot54,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot,"0",",",".")."</td>
                        <td align='right'>".number_format($tot51_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tot52_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tot53_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tot54_sempurna,"0",",",".")."</td>
                        <td align='right'>".number_format($tottot_sempurna,"0",",",".")."</td>
                        <td align='right'>$selisih_total</td>
                        <td align='right'>$persen_total</td>                        
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  nip='1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                            <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                $nama<br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else if($pdf==3){
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= Lampiran Perda IV.xls");
            echo $tbl;
        }else{
            $this->master_pdf->_mpdf_down('Lampiran Perda',' IV',$tbl,10,10,10,'1');
        }
    }
}