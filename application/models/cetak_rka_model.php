<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */ 
 
 
class cetak_rka_model extends CI_Model {
public $ppkd = "4.02.02";
public $ppkd1 = "4.02.02.02";
public $keu1 = "4.02.02.01";
 
public $kdbkad="5-02.0-00.0-00.02.01";
   
public $ppkd_lama = "4.02.02";
public $ppkd1_lama = "4.02.02.02"; 
    function __construct(){ 
        parent::__construct(); 
    }  

    function preview_rka_skpd_penetapan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji){
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
        $sqlsclient=$this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc){
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
        }

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
        if($doc=='RKA'){
            $rka="RENCANA KERJA DAN ANGGARAN";
            $judul="Ringkasan Anggaran Pendapatan dan Belanja
<br> Satuan Kerja Perangkat Daerah";
            $tambahan="";
        }else{
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $rka="DOKUMEN PELAKSANAAN ANGGARAN";
            $judul="Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah
<br> Satuan Kerja Perangkat Daerah";
            $tambahan="<tr>
                        <td style='border-right:none'> No DPA</td>
                        <td style='border-left:none'>: $nodpa</td>
                    </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr> 
                         <td colspan='1' width='80%' align='center'><strong>$rka <br> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td colspan='1' width='20%' rowspan='4' align='center'><strong>$doc - SKPD</strong></td>
                    </tr>
                    <tr>
                         <td colspan='1' align='center'><strong>$kab <br>TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1' cellpadding='5px'>
                    $tambahan
                    <tr>
                        <td style='border-right:none'> Organisasi</td>
                        <td style='border-left:none'>: $kd_skpd - $nm_skpd</td>
                    </tr>
                    <tr>
                        <td colspan='2' bgcolor='#CCCCCC'> &nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='2' align='center'><strong>$judul </strong></td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='5px'>
                     <thead>                       
                        <tr><td bgcolor='#CCCCCC' width='10%' align='center'><b>KODE REKENING</b></td>                            
                            <td bgcolor='#CCCCCC' width='70%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' width='20%' align='center'><b>JUMLAH(Rp.)</b></td></tr>
                     </thead>
                     
                        <tr><td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='70%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>3</td>
                        </tr>
                ";

        if($detail=='detail'){
            $rincian="  UNION ALL "."
                        SELECT a.kd_rek4 AS kd_rek,a.nm_rek4 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek4 a INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17)  
                        GROUP BY a.kd_rek4, a.nm_rek4  
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.nm_rek5 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek5 a INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.nm_rek6 AS nm_rek ,
                        SUM(b.nilai) AS nilai FROM ms_rek6 a INNER JOIN trdrka b ON a.kd_rek6=LEFT(b.kd_rek6,(len(a.kd_rek6)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}
        
        $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
                INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,1)='4' 
                and left(b.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek1, a.nm_rek1 
                UNION ALL 
                SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b 
                ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                GROUP BY a.kd_rek2,a.nm_rek2 
                UNION ALL 
                SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek, SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
                where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                GROUP BY a.kd_rek3, a.nm_rek3 
                $rincian
                ORDER BY kd_rek";
                 
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0){                                  
            foreach ($query->result() as $row){
                    $coba1=$this->support->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"2",",",".");
                   
                    $cRet.= " <tr>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>&nbsp;$coba1</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>&nbsp;$coba2</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>&nbsp;$coba3</td>
                             </tr>";                     
            }
        }else{
                $cRet .= " <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>4</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>PENDAPATAN</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format(0,"2",",",".")."</td>
                          </tr>";
                    
                
        }                                 
                
        $sqltp="SELECT SUM(nilai) AS totp FROM trdrka WHERE LEFT(kd_rek6,1)='4' and left(kd_skpd,17)=left('$id',17)";
        $sqlp=$this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp){

            $coba4=number_format($rowp->totp,"2",",",".");
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
            $rincian="  UNION ALL "." 
                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek4, a.nm_rek4 
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}     
                $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
                        INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek1, a.nm_rek1 
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->support->dotrek($row1->rek);
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"2",",",".");
                   
                     $cRet    .= " <tr><td style='vertical-align:top;' width='10%' align='left'>&nbsp;$coba5</td>                                     
                                     <td style='vertical-align:top;' width='70%'>&nbsp;$coba6</td>
                                     <td style='vertical-align:top;' width='20%' align='right'>&nbsp;$coba7</td></tr>";
                }

                if($gaji==1){
                    $aktifkanGaji="and right(kd_sub_kegiatan,10) <> '01.2.02.01' ";
                }else{
                    $aktifkanGaji="";
                }     

                $sqltb="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='5' and left(kd_skpd,17)=left('$id',17) $aktifkanGaji";
                $sqlb=$this->db->query($sqltb);
                foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,"2",",",".");
                    $cob=$rowb->totb;
                    $cRet    .= " <tr><td style='vertical-align:top;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;' width='70%' align='right'>Jumlah Belanja</td>
                                     <td style='vertical-align:top;' width='20%' align='right'>$coba8</td></tr>";
                 }
                    $cRet    .= " <tr><td style='vertical-align:top;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;' width='20%' align='right'>&nbsp;</td></tr>";

                  
                  $surplus=$cob1-$cob; 
                    $cRet    .= " <tr>   
                                    <td></td>                                 
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>Surplus/Defisit</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($surplus)."</td></tr>"; 

                    
                $sqltpm="SELECT isnull(SUM(nilai),0) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='6' and left(kd_skpd,17)=left('$id',17)";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                   $coba12=number_format($rowtpm->totb,"2",",",".");
                    $cobtpm=$rowtpm->totb;
                    if($cobtpm>0){
                    $cRet    .= " <tr><td style='vertical-align:top;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;' width='70%' align='right'></td>
                                     <td style='vertical-align:top;' width='20%' align='right'>&nbsp;</td></tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;' width='10%' align='left'>6</td>                                     
                                         <td style='vertical-align:top;' width='70%'>Pembiayaan</td>
                                         <td style='vertical-align:top;' width='20%' align='right'>$coba12</td>
                                    </tr>";
                        if($detail=='detail'){
                            $rincian="  UNION ALL "." 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            $coba10=$rowpm->nm_rek;
                            $coba11= number_format($rowpm->nilai,"2",",",".");
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba11</td></tr>";
                        } 


                        $sqltpm="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='61' and left(kd_skpd,17)=left('$id',17)";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                           $coba12=number_format($rowtpm->totb,"2",",",".");
                                            $cobtpm=$rowtpm->totb;
                                            $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba12</td></tr>";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL "." 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            $coba10=$rowpk->nm_rek;
                            $coba11= number_format($rowpk->nilai,"2",",",".");
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba11</td></tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='62' and left(kd_skpd,17)=left('$id',17)";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                   $cobatpk=number_format($rowtpk->totb,"2",",",".");
                    $cobtpk=$rowtpk->totb;
                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$cobatpk</td></tr>";
                 }
    
                $pnetto=$cobtpm-$cobtpk;
                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' width='70%'>Pembiayaan Netto</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".$this->rka_model->angka($pnetto)."</td></tr></table>";                                                      
                    

                    } /*end if pembiayaan 0*/

                } 
               
                $cRet    .= "</table>";

                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,1)='4' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();

               
  if($ttd1!='tanpa'){ 
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
              
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u>
                        
                                </td>";
              
        }else{
            $tambahan="";
        }

                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Penerimaan Per Bulan</td>
                                <td colspan='2' align='center' width='30%'>Rencana Penarikan Dana Per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas4->jan,'2',',','.')."</td> 
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas4->feb,'2',',','.')."</td> 
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas4->mar,'2',',','.')."</td> 
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas4->apr,'2',',','.')."</td> 
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas4->mei,'2',',','.')."</td> 
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas4->jun,'2',',','.')."</td> 
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas4->jul,'2',',','.')."</td> 
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas4->ags,'2',',','.')."</td> 
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas4->sept,'2',',','.')."</td> 
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas4->nov,'2',',','.')."</td> 
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas4->des,'2',',','.')."</td> 
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                        </table>";
              
        
       
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

    function preview_pendapatan_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc,$status_anggaran1, $status_anggaran2){
        
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                }
        $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,22) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
                }


        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }

                $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
                $doc="DPA";
                switch ($status_anggaran1) {
                    case 'nilai':
                        $status_anggaran1="";
                        $status_anggaran1z="";
                        $status_volume1="";
                        $status_satuan1="";         
                        $status_harga1="";
                        break;
                    
                    case 'nilai_sempurna':
                        $status_anggaran1="_sempurna";
                        $status_anggaran1z="_sempurna";
                        $status_harga1="_sempurna1";
                        $status_satuan1="_sempurna1";
                        $status_volume1="_sempurna1";   
                        break;

                     case 'sempurna2':
                        $status_anggaran1="sempurna2";
                        $status_anggaran1z="_sempurna2";
                        $status_harga1="_sempurna2";
                        $status_satuan1="_sempurna2";
                        $status_volume1="_sempurna21";   
                        break;

                     case 'sempurna3':
                        $status_anggaran1="sempurna3";
                        $status_harga1="_sempurna3";
                        $status_anggaran1z="_sempurna3";
                        $status_satuan1="_sempurna3";
                        $status_volume1="_sempurna31";   
                        break;

                     case 'sempurna4':
                        $status_anggaran1="sempurna4";
                        $status_anggaran1z="_sempurna4";
                        $status_harga1="_sempurna4";
                        $status_satuan1="_sempurna4";
                        $status_volume1="_sempurna41";   
                        break;

                     case 'sempurna5':
                        $status_anggaran1="sempurna4";
                        $status_anggaran1z="_sempurna4";
                        $status_harga1="_sempurna4";
                        $status_satuan1="_sempurna4";
                        $status_volume1="_sempurna41";  
                        break;
                    default:
                        $status_anggaran1="_ubah";
                        $status_anggaran1z="_ubah";
                        $status_harga1="_ubah";
                        $status_satuan1="_ubah1";
                        $status_volume1="_ubah1";
                        break;
                }

                switch ($status_anggaran2) {
                    case 'nilai':
                        $status_anggaran2="";
                        $status_anggaran2z="";
                        $status_volume2="";
                        $status_satuan2="";         
                        $status_harga2="";
                        break;
                    
                    case 'nilai_sempurna':
                       $status_anggaran2="_sempurna";
                        $status_anggaran2z="_sempurna";
                        $status_harga2="_sempurna1";
                        $status_satuan2="_sempurna1";
                        $status_volume2="_sempurna1";   
                        break;
 
                     case 'sempurna2':
                        $status_anggaran2="sempurna2";
                        $status_anggaran2z="_sempurna2";
                        $status_harga2="_sempurna2";
                        $status_satuan2="_sempurna2";
                        $status_volume2="_sempurna21";   
                        break;

                     case 'sempurna3':
                        $status_anggaran2="sempurna3";
                        $status_anggaran2z="_sempurna3";
                        $status_harga2="_sempurna3";
                        $status_satuan2="_sempurna3";
                        $status_volume2="_sempurna31";   
                        break;

                     case 'sempurna4':
                        $status_anggaran2="sempurna4";
                        $status_anggaran2z="_sempurna4";
                        $status_harga2="_sempurna4";
                        $status_satuan2="_sempurna4";
                        $status_volume2="_sempurna41";   
                        break;

                     case 'sempurna5':
                        $status_anggaran2="sempurna4";
                        $status_anggaran2z="_sempurna4";
                        $status_harga2="_sempurna4";
                        $status_satuan2="_sempurna4";
                        $status_volume2="_sempurna41";      
                        break;
						
					
                    default:
                       $status_anggaran2="_ubah";
                        $status_anggaran2z="_ubah";
                        $status_harga2="_ubah";
                        $status_satuan2="_ubah1";
                        $status_volume2="_ubah1";
                        $dokumen="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                        $doc="DPPA";
                        break;
                }




        
        if($doc=='RKA'){
            $dokumen="RENCANA KERJA DAN ANGGARAN";
            $tabeldpa="";
        }else{
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tabeldpa="<tr>
                        <td width='20%' style='border-right:none'>No $doc </td>
                        <td width='80%' style='border-left:none'>: $nodpa</td>
                    </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>  
                         <td width='80%' align='center'><strong>$dokumen <br /> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td width='20%' rowspan='2' align='center'><strong>$doc - <br />PENDAPATAN SKPD  </strong></td>
                    </tr>
                    <tr>
                         <td align='center'><strong>$kab <br /> TAHUN ANGGARAN $thn</strong> </td>
                    </tr>

                  </table>";
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1', cellpadding='5px'>
                    $tabeldpa
                    <tr>
                        <td width='20%' style='border-right:none'>Organisasi </td>
                        <td width='80%' style='border-left:none'>: $kd_org -".$this->rka_model->get_nama($id,'nm_skpd','ms_skpd','kd_skpd')."</td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' colspan='2'>&nbsp;</td>
                       
                    </tr>
                    <tr>
                        <td colspan='2' align='center'><strong>Ringkasan Anggaran Pendapatan Satuan Kerja Perangkat Daerah </strong></td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='4'>
                     <thead>                       
                        <tr><td rowspan='3' bgcolor='#CCCCCC' width='10%' align='center'><b>Kode Rekening</b></td>                            
                            <td rowspan='3' bgcolor='#CCCCCC' width='15%' align='center'><b>Uraian</b></td>
                            <td colspan='4' bgcolor='#CCCCCC' width='28%' align='center'><b>Sebelum Perubahan</b></td>
                            <td colspan='4' bgcolor='#CCCCCC' width='28%' align='center'><b>Setelah Perubahan</b></td>
                            <td colspan='2' bgcolor='#CCCCCC' width='20%' align='center'><b>Bertambah/(Berkurang)</b></td>
                        </tr>
                        <tr>
                            <td align='center' width='21%' bgcolor='#CCCCCC' colspan='3'><b>Rincian Perhitungan</td>
                            <td align='center' width='12%' bgcolor='#CCCCCC' rowspan='2'><b>Jumlah</td>
                            <td align='center' width='21%' bgcolor='#CCCCCC' colspan='3'><b>Rincian Perhitungan</td>
                            <td align='center' width='11%' bgcolor='#CCCCCC' rowspan='2'><b>Jumlah</td>
                            <td align='center' width='10%' bgcolor='#CCCCCC' rowspan='2'><b>Rp.</td>
                            <td align='center' width='5%' bgcolor='#CCCCCC' rowspan='2'><b>%</td>
                        </tr>
                        <tr>
                            <td bgcolor='#CCCCCC' align='center'>Volume</td>
                            <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                            <td bgcolor='#CCCCCC' align='center'>Tarif/harga</td>

                            <td bgcolor='#CCCCCC' align='center'>Tarif/harga</td>
                            <td bgcolor='#CCCCCC' align='center'>Volume</td>
                            <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                        </tr>    
                     </thead>
                        ";

                  $sql1="SELECT * FROM(
                        SELECT '' header, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume2,' 'AS satuan2, 0 AS harga2,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'1' AS id FROM trdrka a 
                        INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, 0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'2' AS id 
                        FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'3' AS id 
                        FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'4' AS id 
                        FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'5' AS id 
                        FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5 
                        UNION ALL 
                        SELECT '' header, a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'6' AS id 
                        FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek6,b.nm_rek6 
                        UNION ALL 
                        SELECT cast(header as varchar) header, kd_rek6 AS rek1,' 'AS rek,a.uraian AS nama,a.volume$status_volume1 AS volume,a.satuan$status_satuan1 AS satuan, a.harga$status_harga1 AS harga, a.volume$status_volume2 AS volume2,a.satuan$status_satuan2 AS satuan2, a.harga$status_harga2 AS harga2, a.total$status_anggaran1z AS nilai, a.total$status_anggaran2z AS nilai, '7' AS id 
                        FROM trdpo a WHERE left(kd_skpd,17)=left('$id',17)
                        AND left(kd_rek6,1)='4'
                        ) a ORDER BY a.rek1,a.id";
						
                $query = $this->db->query($sql1);
                  if ($query->num_rows() > 0){                                                                                   
                        foreach ($query->result() as $row)
                        {
                            $rek=$row->rek;
                            $rek1=$row->rek1;
                            $reke=$this->support->dotrek($rek);
                            $uraian=$row->nama;
                            $volum=$row->volume;
                            $volum2=$row->volume2;
                            $header=$row->header;
                            $sat=$row->satuan;
                            $sat2=$row->satuan2;

                            $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,0,',','.');
                            $hrg2= empty($row->harga) || $row->harga2 == 0 ? '' :number_format($row->harga2,0,',','.');
                            $nila= number_format($row->nilai,0,',','.');
                            $nila2= number_format($row->nilai2,0,',','.');
                            $selisih= $this->support->rp_minus($row->nilai2-$row->nilai);
                            if($row->nilai==0){
                                $persen=number_format(0,0,',','.');
                            }else{
                                $persen=$this->support->rp_minus((($row->nilai2-$row->nilai)/$row->nilai)*100);
                            }
                            
                           
                                
                            if($reke!=' '){
                                $volum = '';
                            }
                            
                            if((strlen($rek1)< 14 || $header== '1') && $header!= '0'){
                                if($header== '1'){
                                 $cRet    .= " <tr>
                                                 <td style='vertical-align:top;' align='left'>$reke</td>                                     
                                                 <td colspan='12' style='vertical-align:top;'>:: $uraian</td>
                                                </tr>
                                             ";
                                }else{
                                 $cRet    .= " <tr>
                                                 <td style='vertical-align:top;'  align='left'>$reke</td>                                     
                                                 <td colspan='4' style='vertical-align:top;' >$uraian</td>
                                                 <td style='vertical-align:top;'  align='right'>$nila</td>
                                                 <td colspan='3' style='vertical-align:top;' ></td>
                                                 <td style='vertical-align:top;'  align='right'>$nila2</td>
                                                 <td style='vertical-align:top;'  align='right'>$selisih</td>
                                                 <td style='vertical-align:top;'  align='right'>$persen</td>
                                               </tr>
                                             ";                                    
                                }
                            }else{
                                 $cRet    .= " <tr><td style='vertical-align:top;'  align='left'>$reke</td>                                     
                                                 <td style='vertical-align:top;' >$uraian</td>
                                                 <td style='vertical-align:top;'>$volum</td>
                                                 <td style='vertical-align:top;'>$sat</td>
                                                 <td style='vertical-align:top;' align='right'>$hrg</td>
                                                 <td style='vertical-align:top;' align='right'>$nila</td>
                                                 <td style='vertical-align:top;'>$volum2</td>
                                                 <td style='vertical-align:top;'>$sat2</td>
                                                 <td style='vertical-align:top;' align='right'>$hrg2</td>
                                                 <td style='vertical-align:top;' align='right'>$nila2</td>
                                                 <td style='vertical-align:top;' align='right'>$selisih</td>
                                                 <td style='vertical-align:top;' align='right'>$persen</td>
                                                </tr>
                                             ";                                
                            }
                        

                  
                        } /*endforeach*/
                }else{
                     $cRet    .= " <tr><td style='vertical-align:top;' width='20%' align='left'>4</td>                                     
                                     <td style='vertical-align:top;' >PENDAPATAN</td>
                                     <td style='vertical-align:top;' ></td>
                                     <td style='vertical-align:top;' ></td>
                                     <td style='vertical-align:top;' align='right'></td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,0,',','.')."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,0,',','.')."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,0,',','.')."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,0,',','.')."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,0,',','.')."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,0,',','.')."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,0,',','.')."</td>
                                    </tr>
                                     ";
                    
                } /*endif*/


                   $cRet .= "<tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                             </tr>";
                 $sqltp="SELECT SUM(nilai$status_anggaran1) AS totp, SUM(nilai$status_anggaran2) AS totp2  FROM trdrka WHERE LEFT(kd_rek6,1)='4' AND left(kd_skpd,22)=left('$id',22)";
                    $sqlp=$this->db->query($sqltp);
                 foreach ($sqlp->result() as $rowp)
                {
                   $totp=number_format($rowp->totp,0,',','.');
                   $totp2=number_format($rowp->totp2,0,',','.');
                   $selisih=$this->support->rp_minus($rowp->totp2-$rowp->totp);
                   if($rowp->totp==0){
                        $persen=$this->support->rp_minus(0);
                   }else{
                        $persen=$this->support->rp_minus((($rowp->totp2-$rowp->totp)/$rowp->totp)*100);
                   }
                   
                   
                    $cRet    .=" <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'>&nbsp;</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' colspan='4' >Jumlah Pendapatan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$totp</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right' colspan='3'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$totp2</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$persen</td>
                                </tr>";
                 }
            
        $cRet    .= "</table>";

       
        if($doc=='RKA'){
         if($ttd1!='tanpa'){ /*end tanpa tanda tangan*/
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd WHERE kd_skpd= '$id' AND kode in ('PA','KPA') AND id_ttd='$ttd1'  ";
                 $sqlttd1=$this->db->query($sqlttd1);
                 foreach ($sqlttd1->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
                
        $cRet .="<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='50%' align='center' style='border-right: none' ><br>

                        </td>
                        <td align='center' style='border-left: none'><br>
                            $daerah ,$tanggal_ttd<br>
                            $jabatan, <br>
                            <br><br>
                            <br><br>
                            <br><br>
                            <b>$nama</b><br>
                            $pangkat<br>
                            NIP. $nip 
                        </td>
                    </tr>
                   </table>";
         $cRet .= "<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'><tr>
                                <td width='100%' align='left' colspan='6'>Keterangan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                 <td width='100%' align='left' colspan='6'>Tanggal Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>Catatan Hasil Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>1.</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>2.</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>Dst</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='center' colspan='6'>Tim Anggaran Pemerintah Daerah</td>
                            </tr>";
                            $cRet    .= "</table>";
                  $cRet    .="<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                         <td width='10%' align='center'>No </td>
                         <td width='30%'  align='center'>Nama</td>
                         <td width='20%'  align='center'>NIP</td>
                         <td width='20%'  align='center'>Jabatan</td>
                         <td width='20%'  align='center'>Tandatangan</td>
                    </tr>";
                    $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
                     $sqltapd = $this->db->query($sqltim);
                  if ($sqltapd->num_rows() > 0){
                    
                        $no=1;
                        foreach ($sqltapd->result() as $rowtim)
                        {
                            $no=$no;                    
                            $nama= $rowtim->nama;
                            $nip= $rowtim->nip;
                            $jabatan  = $rowtim->jab;
                            $cRet .="<tr>
                             <td width='5%' align='left'>$no </td>
                             <td width='20%'  align='left'>$nama</td>
                             <td width='20%'  align='left'>$nip</td>
                             <td width='35%'  align='left'>$jabatan</td>
                             <td width='20%'  align='left'></td>
                        </tr>"; 
                        $no=$no+1;              
                      }
                    }else{
                        $cRet .="<tr>
                         <td width='5%' align='left'> &nbsp; </td>
                         <td width='20%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                         <td width='35%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                        </tr>"; 
                    }

        $cRet .=       " </table>";           
        }/*end tanpa tanda tangan*/
                         
        



        } else{ /*tipe dokumen*/
if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u></td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='4' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();
                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:10px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Realisasi Penerimaan Per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas4->jan,0,',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas4->feb,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas4->mar,0,',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas4->apr,0,',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas4->mei,0,',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas4->jun,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas4->jul,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas4->ags,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas4->sept,0,',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas4->okt,0,',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas4->nov,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas4->des,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%' align='right'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,0,',','.')."</td>                               
                            </tr>

                        </table>";
        } /*end else tipe dokumen*/




        $data['prev']= $cRet;
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

    function preview_pendapatan_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc,$status_anggaran1, $status_anggaran2){
        
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                }
        $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,22) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
                }


        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
                if($status_anggaran1=='nilai'){
                    $status_anggaran1="";
                    $status_volume1="";
                    $status_satuan1="";         
                    $status_harga1="";
                }else if($status_anggaran1=='nilai_sempurna'){
                    $status_anggaran1="_sempurna";
                    $status_harga1="_sempurna1";
                    $status_satuan1="_sempurna1";
                    $status_volume1="_sempurna1";
                }else{
                    $status_anggaran1="_ubah";
                    $status_harga1="_ubah";
                    $status_satuan1="_ubah1";
                    $status_volume1="_ubah1";
                }

                if($status_anggaran2=='nilai'){
                    $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
                    $status_anggaran2="";
                    $doc="DPA";
                    $status_volume2="";
                    $status_satuan2="";         
                    $status_harga2="";
                }else if($status_anggaran2=='nilai_sempurna'){
                    $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
                    $doc="DPA";
                    $status_anggaran2="_sempurna";
                    $status_harga2="_sempurna1";
                    $status_satuan2="_sempurna1";
                    $status_volume2="_sempurna1";
                }else{
                    $dokumen="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                    $doc="DPPA";
                    $status_anggaran2="_ubah";
                    $status_harga2="_ubah";
                    $status_satuan2="_ubah1";
                    $status_volume2="_ubah1";
                }    

        
        if($doc=='RKA'){
            $dokumen="RENCANA KERJA DAN ANGGARAN";
            $tabeldpa="";
        }else{
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tabeldpa="<tr>
                        <td width='20%' style='border-right:none'>No $doc </td>
                        <td width='80%' style='border-left:none'>: $nodpa</td>
                    </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>  
                         <td width='80%' align='center'><strong>$dokumen <br /> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td width='20%' rowspan='2' align='center'><strong>$doc - <br />PENDAPATAN SKPD  </strong></td>
                    </tr>
                    <tr>
                         <td align='center'><strong>$kab <br /> TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                  </table>";
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1', cellpadding='5px'>
                    $tabeldpa
                    <tr>
                        <td width='20%' style='border-right:none'>Organisasi </td>
                        <td width='80%' style='border-left:none'>: $kd_org -".$this->rka_model->get_nama($id,'nm_skpd','ms_skpd','kd_skpd')."</td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' colspan='2'>&nbsp;</td>
                       
                    </tr>
                    <tr>
                        <td colspan='2' align='center'><strong>Ringkasan Anggaran Pendapatan Satuan Kerja Perangkat Daerah </strong></td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='4'>
                     <thead>                       
                        <tr><td rowspan='3' bgcolor='#CCCCCC' width='10%' align='center'><b>Kode Rekening</b></td>                            
                            <td rowspan='3' bgcolor='#CCCCCC' width='15%' align='center'><b>Uraian</b></td>
                            <td colspan='4' bgcolor='#CCCCCC' width='28%' align='center'><b>Sebelum Pergeseran</b></td>
                            <td colspan='4' bgcolor='#CCCCCC' width='28%' align='center'><b>Setelah Pergeseran</b></td>
                            <td colspan='2' bgcolor='#CCCCCC' width='20%' align='center'><b>Bertambah/(Berkurang)</b></td>
                        </tr>
                        <tr>
                            <td align='center' width='21%' bgcolor='#CCCCCC' colspan='3'><b>Rincian Perhitungan</td>
                            <td align='center' width='12%' bgcolor='#CCCCCC' rowspan='2'><b>Jumlah</td>
                            <td align='center' width='21%' bgcolor='#CCCCCC' colspan='3'><b>Rincian Perhitungan</td>
                            <td align='center' width='11%' bgcolor='#CCCCCC' rowspan='2'><b>Jumlah</td>
                            <td align='center' width='10%' bgcolor='#CCCCCC' rowspan='2'><b>Rp.</td>
                            <td align='center' width='5%' bgcolor='#CCCCCC' rowspan='2'><b>%</td>
                        </tr>
                        <tr>
                            <td bgcolor='#CCCCCC' align='center'>Volume</td>
                            <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                            <td bgcolor='#CCCCCC' align='center'>Tarif/harga</td>
                            <td bgcolor='#CCCCCC' align='center'>Tarif/harga</td>
                            <td bgcolor='#CCCCCC' align='center'>Volume</td>
                            <td bgcolor='#CCCCCC' align='center'>Satuan</td>
                        </tr>    
                     </thead>
                        ";



                

                


                  $sql1="SELECT * FROM(
                        SELECT '' header, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume2,' 'AS satuan2, 0 AS harga2,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'1' AS id FROM trdrka a 
                        INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, 0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'2' AS id 
                        FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'3' AS id 
                        FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'4' AS id 
                        FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'5' AS id 
                        FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5 
                        UNION ALL 
                        SELECT '' header, a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai$status_anggaran1) AS nilai, SUM(a.nilai$status_anggaran2) AS nilai2,'6' AS id 
                        FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek6,b.nm_rek6 
                        UNION ALL 
                        SELECT cast(header as varchar) header, kd_rek6 AS rek1,' 'AS rek,a.uraian AS nama,a.volume$status_volume1 AS volume,a.satuan$status_satuan1 AS satuan, a.harga$status_harga1 AS harga, a.volume$status_volume2 AS volume2,a.satuan$status_satuan2 AS satuan2, a.harga$status_harga2 AS harga2, a.total$status_anggaran1 AS nilai, a.total_sempurna1 AS nilai, '7' AS id 
                        FROM trdpo a WHERE left(no_trdrka,17)=left('$id',17)
                        AND left(kd_rek6,1)='4'
                        ) a ORDER BY a.rek1,a.id";
                 
                $query = $this->db->query($sql1);
                  if ($query->num_rows() > 0){                                                                                   
                        foreach ($query->result() as $row)
                        {
                            $rek=$row->rek;
                            $rek1=$row->rek1;
                            $reke=$this->support->dotrek($rek);
                            $uraian=$row->nama;
                            $volum=$row->volume;
                            //$volum2=$row->volume2;
                            $header=$row->header;
                            $sat=$row->satuan;
                            $sat2=$row->satuan2;

                             //$volum2= empty($row->volume2) || $row->volume2 == 0 ? '' :$row->volume2;
                             $volum2= empty($row->volume2) || $row->volume2 == 0 ? '' :number_format($row->volume2,2,',','.');
                            $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                            $hrg2= empty($row->harga) || $row->harga2 == 0 ? '' :number_format($row->harga2,2,',','.');
                            $nila= number_format($row->nilai,"2",",",".");
                            $nila2= number_format($row->nilai2,"2",",",".");
                            $selisih= $this->support->rp_minus($row->nilai2-$row->nilai);
                            if($row->nilai2==0){
                                $persen=number_format(0,"2",",",".");
                            }else{
                                $persen=$this->support->rp_minus((($row->nilai2-$row->nilai)/$row->nilai)*100);
                            }
                            
                           
                                
                            if($reke!=' '){
                                $volum = '';
                            }
                            
                            if((strlen($rek1)< 14 || $header== '1') && $header!= '0'){
                                if($header== '1'){
                                 $cRet    .= " <tr>
                                                 <td style='vertical-align:top;' align='left'>$reke</td>                                     
                                                 <td colspan='12' style='vertical-align:top;'>:: $uraian</td>
                                                </tr>
                                             ";
                                }else{
                                 $cRet    .= " <tr>
                                                 <td style='vertical-align:top;'  align='left'>$reke</td>                                     
                                                 <td colspan='4' style='vertical-align:top;' >$uraian</td>
                                                 <td style='vertical-align:top;'  align='right'>$nila</td>
                                                 <td colspan='3' style='vertical-align:top;' ></td>
                                                 <td style='vertical-align:top;'  align='right'>$nila2</td>
                                                 <td style='vertical-align:top;'  align='right'>$selisih</td>
                                                 <td style='vertical-align:top;'  align='right'>$persen</td>
                                               </tr>
                                             ";                                    
                                }
                            }else{
                                 $cRet    .= " <tr><td style='vertical-align:top;'  align='left'>$reke</td>                                     
                                                 <td style='vertical-align:top;' >$uraian</td>
                                                 <td style='vertical-align:top;'>$volum</td>
                                                 <td style='vertical-align:top;'>$sat</td>
                                                 <td style='vertical-align:top;' align='right'>$hrg</td>
                                                 <td style='vertical-align:top;' align='right'>$nila</td>
                                                 <td style='vertical-align:top;'>$volum2</td>
                                                 <td style='vertical-align:top;'>$sat2</td>
                                                 <td style='vertical-align:top;' align='right'>$hrg2</td>
                                                 <td style='vertical-align:top;' align='right'>$nila2</td>
                                                 <td style='vertical-align:top;' align='right'>$selisih</td>
                                                 <td style='vertical-align:top;' align='right'>$persen</td>
                                                </tr>
                                             ";                                
                            }
                        

                  
                        } /*endforeach*/
                }else{
                     $cRet    .= " <tr><td style='vertical-align:top;' width='20%' align='left'>4</td>                                     
                                     <td style='vertical-align:top;' >PENDAPATAN</td>
                                     <td style='vertical-align:top;' ></td>
                                     <td style='vertical-align:top;' ></td>
                                     <td style='vertical-align:top;' align='right'></td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,"2",",",".")."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,"2",",",".")."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,"2",",",".")."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,"2",",",".")."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,"2",",",".")."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,"2",",",".")."</td>
                                     <td style='vertical-align:top;' align='right'>".number_format(0,"2",",",".")."</td>
                                    </tr>
                                     ";
                    
                } /*endif*/


                   $cRet .= "<tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                             </tr>";
                 $sqltp="SELECT SUM(nilai$status_anggaran1) AS totp, SUM(nilai$status_anggaran2) AS totp2  FROM trdrka WHERE LEFT(kd_rek6,1)='4' AND left(kd_skpd,22)=left('$id',22)";
                    $sqlp=$this->db->query($sqltp);
                 foreach ($sqlp->result() as $rowp)
                {
                   $totp=number_format($rowp->totp,"2",",",".");
                   $totp2=number_format($rowp->totp2,"2",",",".");
                   $selisih=$this->support->rp_minus($rowp->totp2-$rowp->totp);
                   if($rowp->totp==0){
                      $persen=0 ;     
                   }else{
                        $persen=$this->support->rp_minus((($rowp->totp2-$rowp->totp)/$rowp->totp)*100);
                   
                   }
                   
                    $cRet    .=" <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'>&nbsp;</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' colspan='4' >Jumlah Pendapatan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$totp</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right' colspan='3'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$totp2</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>$persen</td>
                                </tr>";
                 }
            
        $cRet    .= "</table>";

       
        if($doc=='RKA'){
         if($ttd1!='tanpa'){ /*end tanpa tanda tangan*/
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd WHERE kd_skpd= '$id' AND kode in ('PA','KPA') AND id_ttd='$ttd1'  ";
                 $sqlttd1=$this->db->query($sqlttd1);
                 foreach ($sqlttd1->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
                
        $cRet .="<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='50%' align='center' style='border-right: none' ><br>
                        </td>
                        <td align='center' style='border-left: none'><br>
                            $daerah ,$tanggal_ttd<br>
                            $jabatan, <br>
                            <br><br>
                            <br><br>
                            <br><br>
                            <b>$nama</b><br>
                            $pangkat<br>
                            NIP. $nip 
                        </td>
                    </tr>
                   </table>";
         $cRet .= "<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'><tr>
                                <td width='100%' align='left' colspan='6'>Keterangan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                 <td width='100%' align='left' colspan='6'>Tanggal Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>Catatan Hasil Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>1.</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>2.</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>Dst</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='center' colspan='6'>Tim Anggaran Pemerintah Daerah</td>
                            </tr>";
                            $cRet    .= "</table>";
                  $cRet    .="<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                         <td width='10%' align='center'>No </td>
                         <td width='30%'  align='center'>Nama</td>
                         <td width='20%'  align='center'>NIP</td>
                         <td width='20%'  align='center'>Jabatan</td>
                         <td width='20%'  align='center'>Tandatangan</td>
                    </tr>";
                    $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
                     $sqltapd = $this->db->query($sqltim);
                  if ($sqltapd->num_rows() > 0){
                    
                        $no=1;
                        foreach ($sqltapd->result() as $rowtim)
                        {
                            $no=$no;                    
                            $nama= $rowtim->nama;
                            $nip= $rowtim->nip;
                            $jabatan  = $rowtim->jab;
                            $cRet .="<tr>
                             <td width='5%' align='left'>$no </td>
                             <td width='20%'  align='left'>$nama</td>
                             <td width='20%'  align='left'>$nip</td>
                             <td width='35%'  align='left'>$jabatan</td>
                             <td width='20%'  align='left'></td>
                        </tr>"; 
                        $no=$no+1;              
                      }
                    }else{
                        $cRet .="<tr>
                         <td width='5%' align='left'> &nbsp; </td>
                         <td width='20%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                         <td width='35%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                        </tr>"; 
                    }

        $cRet .=       " </table>";           
        }/*end tanpa tanda tangan*/
                         
        



        } else{ /*tipe dokumen*/
if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama</u><br>
                                $nip
                                <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                            SILVESTRA DAYANA SIMBOLON, SE.,MM</u><br/>
                                            NIP. 19671126 199503 2 004
                        
                                </td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='4' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();
                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:10px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Realisasi Penerimaan Per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas4->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas4->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas4->mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas4->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas4->mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas4->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas4->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas4->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas4->sept,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas4->okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas4->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas4->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%' align='right'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";
        } /*end else tipe dokumen*/




        $data['prev']= $cRet;
        switch($cetak) { 
        case 1;
             $this->support->_mpdf('',$cRet,10,10,10,'1');
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

    function preview_belanja_penyusunan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc){
        
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
                }
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
        if($doc=='RKA'){
            $dokumen="RENCANA KERJA DAN ANGGARAN";
            $tabeldpa="";
        }else{
            $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tabeldpa="<tr>
                        <td width='20%' align='left' style='border-right:none'> No DPA</td>
                        <td width='80%' align='left' style='border-left:none'>: $nodpa</td>
                    </tr>";
        }

        $ctk ="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='5px'>
                <tr>
                    <td width='80%' align='center'><b> $dokumen <br> SATUAN KERJA PERANGKAT DAERAH</td>
                    <td rowspan='2' width='20%' align='center'><b>$doc - BELANJA SKPD</td>
                </tr>
                <tr>
                    <td width='80%' align='center'><b> Kota $daerah <br> Tahun Anggaran $thn</td>
                </tr>
              </table>";

        $ctk .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='2'>
                $tabeldpa
                <tr>
                    <td width='20%' align='left' style='border-right:none'> Organisasi</td>
                    <td width='80%' align='left' style='border-left:none'>: $kd_skpd - $nm_skpd</td>
                </tr>
                <tr>
                    <td width='100%' colspan='2' bgcolor='#cccccc' align='left'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='2' align='center'><b>Rekapitulasi Anggaran Belanja Berdasarkan Program dan Kegiatan</td>
                </tr>
              </table>";

        $ctk .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                <thead>
                <tr>
                    <td align='center' colspan='5'><b>Kode</td>
                    <td align='center' rowspan='3'><b>Uraian</td>
                    <td align='center' rowspan='3'><b>Sumber Dana</td>
                    <td align='center' rowspan='3'><b>Lokasi</td>
                    <td align='center' colspan='7'><b>Jumlah</td>                
                </tr>
                <tr>
                    <td align='center' rowspan='2'><b>Urusan</td>
                    <td align='center' rowspan='2'><b>Sub Urusan</td>
                    <td align='center' rowspan='2'><b>Program</td>
                    <td align='center' rowspan='2'><b>Kegiatan</td>
                    <td align='center' rowspan='2'><b>Sub Kegiatan</td>
                    <td align='center' rowspan='2'><b>T-1</td>
                    <td align='center' colspan='5'><b>T</td>
                    <td align='center' rowspan='2'><b>T+1</td>
                </tr>
                <tr>
                    <td align='center'><b>Blj. Operasi</td>
                    <td align='center'><b>Blj. Modal</td>
                    <td align='center'><b>Blj. Tak Terduga</td>
                    <td align='center'><b>Blj. Transfer</td>
                    <td align='center'><b>Jumlah</td>
                </tr>
                <tr bgcolor='#cccccc'>
                    <td align='center'><b>1</td>
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
                </thead>
                <tr>
                    <td colspan='15' bgcolor='#cccccc'>&nbsp;</td>
                </tr>";
            $tot51=0;
            $tot52=0;
            $tot53=0;
            $tot54=0;
            $total=0;
        $sumber="";
        $sql=$this->db->query("SELECT urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber, lokasi, sum(operasi) operasi, sum(modal) modal, sum(duga) duga, sum(trans) trans from v_cetak_belanja where left(kd_skpd,17)=left('$id',17)
GROUP BY left(kd_skpd,17),urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber, lokasi, urut
 ORDER BY urut
           ");
        foreach($sql->result() as $a){
            $urusan =$a->urusan;
            $bid_urusan =$a->bid_urusan;
            $program =$a->program;
            $giat =$a->kegiatan;
            $subgiat =$a->subgiat;
            $nama =$a->nama;
            $sumber =$a->sumber;
            $lokasi =$a->lokasi;
            $operasi =$a->operasi;
            $modal =$a->modal;
            $terduga =$a->duga;
            $transfer =$a->trans;
            $Jumlah=$operasi+$modal+$terduga+$transfer;
            if($subgiat!=''){
                $tot51=0+$tot51+$operasi;
                $tot52=0+$tot52+$modal;
                $tot53=0+$tot53+$terduga;
                $tot54=0+$tot54+$transfer;
                $total=0+$total+$Jumlah;                
            }


        $ctk .="<tr>
                    <td align='center'>$urusan</td>
                    <td align='center'>$bid_urusan</td>
                    <td align='center'>$program</td>
                    <td align='center'>$giat</td>
                    <td align='center'>$subgiat</td>
                    <td align='left'>$nama</td>
                    <td align='left'>$sumber</td>
                    <td align='left'>$lokasi</td>
                    <td align='left'></td>
                    <td align='right'>&nbsp;".number_format($operasi,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($modal,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($terduga,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($transfer,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($Jumlah,2,',','.')."</td>
                    <td align='left'></td>
                </tr>";
        }
        $ctk .="<tr>
                    <td align='right' colspan='8'> &nbsp; TOTAL &nbsp;</td>
                    <td align='left'></td>
                    <td align='right'>".number_format($tot51,2,',','.')."</td>
                    <td align='right'>".number_format($tot52,2,',','.')."</td>
                    <td align='right'>".number_format($tot53,2,',','.')."</td>
                    <td align='right'>".number_format($tot54,2,',','.')."</td>
                    <td align='right'>".number_format($total,2,',','.')."</td>
                    <td align='left'></td>
                </tr>";
            $ctk .=  "</table>";
       
if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u></td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,1)='4' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();
                $ctk .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";





        switch($cetak) { 
        case 1;
             $this->master_pdf->_mpdf('',$ctk,10,10,10,'0');
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
        echo($ctk);
        break;
        }     
    }

    function preview_rka_pembiayaan_penetapan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc){

       
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
        $sqlsclient=$this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc){
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
        }


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
        if($doc=='RKA'){
            $rka="RENCANA KERJA DAN ANGGARAN";
            $tambahan="";
        }else{
            $rka="DOKUMEN PELAKSANAAN ANGGARAN";
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tambahan=" <tr>
                            <td> No DPA</td>
                            <td>$nodpa</td>
                        </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr> 
                         <td width='80%' align='center'><strong>$rka <br> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td width='20%' rowspan='4' align='center'><strong> <br>$doc - PEMBIAYAAN SKPD</strong></td>
                    </tr>
                    <tr>
                         <td align='center'><strong>$kab <br>TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                    <tr>
                        <td colspan='2' align='center'><strong>RINCIAN ANGGARAN PEMBIAYAAN DAERAH</strong></td>
                    </tr> 
                    $tambahan                  
                    <tr>
                        <td> Organisasi</td>
                        <td>$kd_skpd - $nm_skpd</td>
                    </tr>
                    
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                     <thead>                       
                        <tr><td bgcolor='#CCCCCC' width='10%' align='center'><b>KODE REKENING</b></td>                            
                            <td bgcolor='#CCCCCC' width='70%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' width='20%' align='center'><b>JUMLAH(Rp.)</b></td></tr>
                     </thead>
                     
                        <tr><td style='vertical-align:top;border-top: none;border-bottom: none;' width='10%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='70%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='20%' align='center'>3</td>
                        </tr>
                ";

                $sqltpm="SELECT isnull(SUM(nilai),0) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='6' and left(kd_skpd,20)=left('$id',20)";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                   $coba12=number_format($rowtpm->totb,"2",",",".");
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
                                        SELECT '' header, a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT '' header, a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT '' header, a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 
                                union all
                                     select * from (
                                       SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,
                                    SUM(a.total) AS nilai FROM trdpo a LEFT JOIN trdpo b ON b.ket_bl_teks=a.uraian
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id'  
                                     GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                    UNION ALL
                                    SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,SUM(a.total) AS nilai FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id'  
                                    GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                        
                                        UNION ALL
                                        SELECT a. header, a.kd_rek6 AS rek1,''AS rek,a.uraian AS nama, a.total AS nilai FROM trdpo a  WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id' AND  (header='0' or header is null)
                                    ) okeii ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT '' header, a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT '' header, a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
    
                        ORDER BY kd_rek,header
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            if($coba9==''){
                                $coba10="<b>::</b> ".$rowpm->nm_rek;
                            }else{
                                $coba10=$rowpm->nm_rek;
                            }
                            if($rowpm->header==0 and $coba9==''){
                                $coba11= "";
                            }else{
                                $coba11= number_format($rowpm->nilai,"2",",",".");
                            }
                            
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba11</td></tr>";
                        } 

                                            $kosong    = " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>&nbsp;</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>&nbsp;</td></tr>";
                        $sqltpm="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='61' and left(kd_skpd,20)=left('$id',20)";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                           $coba12=number_format($rowtpm->totb,"2",",",".");
                                            $cobtpm=$rowtpm->totb;
                                            $cRet    .= " $kosong <tr><td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='10%' align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='70%' align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$coba12</td>
                                                             </tr>$kosong";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL 
                                        SELECT '' header,  a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT '' header,  a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT '' header,  a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 
                                    union all
                                     select * from (
                                       SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,
                                    SUM(a.total) AS nilai FROM trdpo a LEFT JOIN trdpo b ON b.ket_bl_teks=a.uraian
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id'  
                                     GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                    UNION ALL
                                    SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,SUM(a.total) AS nilai FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id'  
                                    GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                        
                                        UNION ALL
                                        SELECT a. header, a.kd_rek6 AS rek1,''AS rek,a.uraian AS nama, a.total AS nilai FROM trdpo a  WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id' AND  (header='0' or header is null)
                                    ) okeii ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT '' header,  a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT '' header,  a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek, header";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            if($coba9==''){
                                $coba10="<b>::</b> ".$rowpk->nm_rek;
                            }else{
                                $coba10=$rowpk->nm_rek;
                            }
                            if($rowpk->header==0 and $coba9==''){
                                $coba11= "";
                            }else{
                                $coba11= number_format($rowpk->nilai,"2",",",".");
                            }
                            
                            
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='70%'>$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$coba11</td></tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='62' and left(kd_skpd,20)=left('$id',20)";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                   $cobatpk=number_format($rowtpk->totb,"2",",",".");
                    $cobtpk=$rowtpk->totb;
                   
                    $cRet    .= "$kosong <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$cobatpk</td>
                                </tr>$kosong";
                 }
                                                    
                          $netto=$this->support->rp_minus($cobtpm-$cobtpk);
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='70%' align='right'>Pembiayaan Netto:</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$netto</td></tr>";
                                        

                    } /*end if pembiayaan 0*/

                } 
              
                $cRet    .= "</table>";
        if($doc=='RKA'){

        
        if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    

            $cRet.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td align='center'>
                            </td>
                            <td align='center'>
                                <br>$daerah, $tanggal_ttd <br>
                                Mengetahui, <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u>
                            </td>
                        </tr>
                    </table>";

        $cRet    .="<br><table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                         <td colspan='5' align='center'><strong>Tim Anggaran Pemerintah Daerah</strong> </td>
                         
                    </tr>
                    <tr>
                         <td width='10%' align='center'><strong>No</strong> </td>
                         <td width='30%'  align='center'><strong>Nama</strong></td>
                         <td width='20%'  align='center'><strong>NIP</strong></td>
                         <td width='20%'  align='center'><strong>Jabatan</strong></td>
                         <td width='20%'  align='center'><strong>Tanda Tangan</strong></td>
                    </tr>";
                    $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd order by no";
                    $sqltapd=$this->db->query($sqltim);
                    $no=1;
                    foreach ($sqltapd->result() as $rowtim)
                    {
                        $no=$no;                    
                        $nama= $rowtim->nama;
                        $nip= $rowtim->nip;
                        $jabatan  = $rowtim->jab;
                        $cRet .="<tr>
                                 <td width='5%' align='center'>$no </td>
                                 <td width='20%'  align='left'>$nama</td>
                                 <td width='20%'  align='left'>$nip</td>
                                 <td width='35%'  align='left'>$jabatan</td>
                                 <td width='20%'  align='left'></td>
                            </tr>"; 
                    $no=$no+1;              
                    }
                    
                    if($no<=4){ /*jika orangnya kurang dari 4 maka tambah kolom kosong*/
                        for ($i = $no; $i <= 4; $i++){
                            $cRet .="<tr>
                                         <td width='5%' align='center'>$i </td>
                                         <td width='20%'  align='left'>&nbsp; </td>
                                         <td width='20%'  align='left'>&nbsp; </td>
                                         <td width='35%'  align='left'>&nbsp; </td>
                                         <td width='20%'  align='left'></td>
                                    </tr>";     
                            }                                                   
                    } 

        $cRet    .= "</table>";                       
        }
    } else{ /*if else tipe dokumen*/

    


                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,2)='61' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd 
                                                 union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,2)='62' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();

               
  if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u></td>";
              
        }else{
            $tambahan="";
        }

                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Penerimaan per
Bulan</td>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Pengeluaran per
Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas4->jan,'2',',','.')."</td> 
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas4->feb,'2',',','.')."</td> 
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas4->mar,'2',',','.')."</td> 
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas4->apr,'2',',','.')."</td> 
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas4->mei,'2',',','.')."</td> 
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas4->jun,'2',',','.')."</td> 
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas4->jul,'2',',','.')."</td> 
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas4->ags,'2',',','.')."</td> 
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas4->sept,'2',',','.')."</td> 
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas4->nov,'2',',','.')."</td> 
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas4->des,'2',',','.')."</td> 
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                        </table>";  
        } /*end tipe doc*/   
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

     function preview_rka221_penyusunan($id,$giat,$cetak,$atas,$bawah,$kiri,$kanan,$tgl_ttd,$ttd1,$ttd2, $tanggal_ttd,$jns_an){


 

 
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $tanggal = '';
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                    $thn_lalu     = $rowsc->thn_ang-1;
                    $thn_depan     = $rowsc->thn_ang+1;
                }
        $sqlttd1="SELECT isnull(nama,'') as nm,isnull(nip,'') as nip,isnull(jabatan,'') as jab, isnull(pangkat,'') as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1'   ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip; 
                    $pangkat=$rowttd->pangkat;
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $kuasa="";
                }
              
         $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' ";
                 $sqlttd2=$this->db->query($sqlttd2);
                 foreach ($sqlttd2->result() as $rowttd2)
                {
                    $nip2=$rowttd2->nip;
                    $pangkat2=$rowttd2->pangkat;
                    $nama2= $rowttd2->nm;
                    $jabatan2  = $rowttd2->jab;
                    
                }
        $sqlorg="SELECT *, left(kd_bidang_urusan,1) kd_urusan, (select nm_urusan from ms_urusan where kd_urusan=left(kd_bidang_urusan,1))  nm_urusan,
            (select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=trskpd.kd_bidang_urusan) nm_bidang_urusan
          from trskpd where left(kd_sub_kegiatan,12)='$giat' and kd_skpd='$id'
                ";
                 $sqlorg1=$this->db->query($sqlorg);
                 foreach ($sqlorg1->result() as $roworg)
                {
                    $kd_urusan=$roworg->kd_urusan;                    
                    $nm_urusan= $roworg->nm_urusan;
                    $kd_bidang_urusan=$roworg->kd_bidang_urusan;                    
                    $nm_bidang_urusan= $roworg->nm_bidang_urusan;
                    $kd_skpd  = $roworg->kd_skpd;
                    $nm_skpd  = $roworg->nm_skpd;
                    $kd_prog  = $roworg->kd_program;
                    $nm_prog  = $roworg->nm_program;
                    $sasaran_prog  = $roworg->sasaran_program;
                    $capaian_prog  = $roworg->capaian_program;
                    $kd_giat  = $roworg->kd_kegiatan;
                    $nm_giat  = $roworg->nm_kegiatan;
                    $lokasi  = $roworg->lokasi;
                    $tu_capai  = $roworg->tu_capai;
                    $tu_capai_p  = $roworg->tu_capai_p;
                    $tu_mas  = $roworg->tu_mas;
                    $tu_mas_p  = $roworg->tu_mas_p;
                    $tu_kel  = $roworg->tu_kel;
                    $tu_kel_p  = $roworg->tu_kel_p;
                    $tu_has  = $roworg->tu_has;
                    $tu_has_p  = $roworg->tu_has_p;
                    $tk_capai  = $roworg->tk_capai;
                    $tk_mas  = $roworg->tk_mas;
                    $tk_kel  = $roworg->tk_kel;
                    $tk_has  = $roworg->tk_has;
                    $tk_capai_p  = $roworg->tk_capai_p;
                    $tk_mas_p  = $roworg->tk_mas_p;
                    $tk_kel_p  = $roworg->tk_kel_p;
                    $tk_has_p  = $roworg->tk_has_p;
                    $sas_giat = $roworg->sasaran_giat;
                    $ang_lalu = $roworg->ang_lalu;
                }


        $sqltp="SELECT SUM(nilai) AS totb FROM trdrka WHERE left(kd_rek6,1)='5' and left(kd_sub_kegiatan,12)='$giat' AND kd_skpd='$id'";
                 $sqlb=$this->db->query($sqltp);
                 foreach ($sqlb->result() as $rowb)
                {
                   $totp  =number_format($rowb->totb,"2",",",".");
                   $totp1 =number_format($rowb->totb*1.1,"2",",",".");
                }
        if($jns_an=="RKA"){
            $jenis_ang="RKA - RINCIAN BELANJA SKPD";
            $isi_="RENCANA KERJA DAN ANGGARAN <BR> SATUAN KERJA PERANGKAT DAERAH";
        }else{
            $jenis_ang="DPA - RINCIAN BELANJA SKPD";
            $isi_="DOKUMEN PELAKSANAAN ANGGARAN <BR> SATUAN KERJA PERANGKAT DAERAH";            
        }      
     
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td align='center'><strong>$isi_ </strong></td>
                        <td align='center' width='20%' rowspan='2'><strong>$jenis_ang</strong></td>
                    </tr>
                    <tr>
                        <td align='center' ><strong>$kab <BR> TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                  </table>";
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                        <tr>
                            <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Urusan Pemerintahan</td>
                            <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                            <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_urusan</td>
                            <td width='60%' style='vertical-align:top;border-left: none;' align='left'>$nm_urusan</td>
                        </tr>
                        <tr>
                            <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Bidang Urusan</td>
                            <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                            <td width='15%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>$kd_bidang_urusan </td>
                            <td width='60%' style='vertical-align:top;border-left: none;' align='left'> $nm_bidang_urusan</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Program</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_prog</td>
                            <td align='left' style='vertical-align:top;border-left: none;'>$nm_prog</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Sasaran Program</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$sasaran_prog</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Capaian Program</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>$capaian_prog</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Kegiatan</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_giat</td>
                            <td align='left' style='vertical-align:top;border-left: none;'>$nm_giat</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Organisasi</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>".substr($kd_skpd,0,17)."</td>
                            <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Unit Organisasi</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td align='left' style='vertical-align:top;border-left: none;border-right: none;'>$kd_skpd</td>
                            <td align='left' style='vertical-align:top;border-left: none;'>$nm_skpd</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thn_lalu</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td colspan ='2'  align='left' style='vertical-align:top;border-left: none;'>Rp. ".number_format($ang_lalu,"2",",",".")." (<i>".$this->rka_model->terbilang($ang_lalu*1)." rupiah)</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp (<i>".$this->rka_model->terbilang($rowb->totb*1)." rupiah)</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Alokasi Tahun $thn_depan</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td colspan ='2' align='left' style='vertical-align:top;border-left: none;'>Rp. $totp1 (<i>".$this->rka_model->terbilang($rowb->totb*1.1)." rupiah)</td>
                        </tr>
                        <tr>
                    <td colspan='4' bgcolor='#CCCCCC' width='100%' align='left'>&nbsp;</td>
                </tr>
                    </table>    
                        
                    ";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                    <tr>
                        <td colspan='3'  align='center' >Indikator & Tolak Ukur Kinerja Kegiatan</td>
                    </tr>";
        $cRet .="<tr>
                 <td width='20%'  align='center'>Indikator </td>
                 <td width='40%'  align='center'>Tolak Ukur Kerja </td>
                 <td width='40%'  align='center'>Target Kinerja </td>
                </tr>";             

        $cRet .=" <tr align='center' >
                    <td  style='white-space: pre-line;'>Capaian Kegiatan </td>
                    <td style='white-space: pre-line;'>".nl2br($tu_capai)."</td>
                    <td style='white-space: pre-line;'>".nl2br($tk_capai)."</td>
                 </tr>";
        $cRet .=" <tr align='center'>
                    <td style='white-space: pre-line;'>Masukan </td>
                    <td style='white-space: pre-line;'>".nl2br($tu_mas)."</td>
                    <td style='white-space: pre-line;'>Rp. $totp</td>
                </tr>";
        $cRet .=" <tr align='center'>
                    <td style='white-space: pre-line;'>Keluaran </td>
                    <td style='white-space: pre-line;'>".nl2br($tu_kel)."</td>
                    <td style='white-space: pre-line;'>".nl2br($tk_kel)."</td>
                  </tr>";
        $cRet .=" <tr align='center'>
                    <td style='white-space: pre-line;'>Hasil </td>
                    <td style='white-space: pre-line;'>".nl2br($tu_has)."</td>
                    <td style='white-space: pre-line;'>".nl2br($tk_has)."</td>
                  </tr>";
        $cRet .= "<tr>
                    <td colspan='3'   align='left'>Kelompok Sasaran Kegiatan : $sas_giat</td>
                </tr>";
        $cRet .= "<tr>
                    <td colspan='3'  align='left'>&nbsp;</td>
                </tr>"; 
                $cRet .= "<tr>
                    <td colspan='3' bgcolor='#CCCCCC'  align='left'>&nbsp;</td>
                </tr>";                
        
        $cRet .= "<tr>
                        <td colspan='5' align='center'>RINCIAN ANGGARAN BELANJA KEGIATAN SATUAN KERJA PERANGKAT DAERAH</td>
                  </tr>";
                    
        $cRet .="</table>";
//rincian sub kegiatan
                

               $sqlsub="SELECT a.kd_sub_kegiatan as kd_sub_kegiatan,b.nm_sub_kegiatan,b.sub_keluaran,b.lokasi,b.waktu_giat,b.waktu_giat2,b.keterangan FROM trdrka a
                left join trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                WHERE left(a.kd_sub_kegiatan,12)='$giat' AND a.kd_skpd='$id' AND b.kd_skpd='$id'
                group by a.kd_sub_kegiatan,b.nm_sub_kegiatan,b.sub_keluaran,b.lokasi,b.waktu_giat,b.waktu_giat2,b.keterangan order by a.kd_sub_kegiatan";
                 $sqlbsub=$this->db->query($sqlsub);
                 foreach ($sqlbsub->result() as $rowsub)
                {
                   $sub         =$rowsub->kd_sub_kegiatan;
                   $nm_sub      =$rowsub->nm_sub_kegiatan;
                   $sub_keluaran=$rowsub->sub_keluaran;
                   $lokasi      =$rowsub->lokasi;
                   $waktu_giat  =$rowsub->waktu_giat;
                   $waktu_giat2  =$rowsub->waktu_giat2;
                   $keterangan  =$rowsub->keterangan;


                    $sumber=$this->db->query("SELECT top 1 sumber+' '++isnull(sumber2,'')+' '++isnull(sumber3,'')+' '++isnull(sumber4,'') sumber from trdrka where kd_sub_kegiatan='$sub' and kd_skpd='$id'")->row();

                    /*untuk indikator sub Kegiatan*/
                    $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                        <tr>
                            <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Sub Kegiatan</td>
                            <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                            <td width='75%' colspan='3' style='vertical-align:top;border-left: none;' align='left'><b><i>$sub - $nm_sub</td>
                        </tr>
                        <tr>
                            <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Sumber Dana</td>
                            <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                            <td width='75%' colspan='3' style='vertical-align:top;border-left: none;' align='left'>$sumber->sumber</td>
                        </tr>
                        <tr>
                            <td width='20%' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Lokasi</td>
                            <td width='5%'  style='vertical-align:top;border-left: none;border-right: none;' align='center'>:</td>
                            <td width='75%' colspan='3' style='vertical-align:top;border-left: none;' align='left'>$lokasi</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Keluaran Sub Kegiatan</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td align='left' colspan='3' style='vertical-align:top;border-left: none;'>$sub_keluaran</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Waktu Pelaksanaan</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td width='35%' style='vertical-align:top;border-left: none;border-right: none;' align='left'>Mulai:&nbsp;$waktu_giat</td>
                            <td width='10%' style='vertical-align:top;border-right: none;border-left: none;' align='left'>Sampai</td>
                            <td width='35%' style='vertical-align:top;border-left: none;' align='left'>:&nbsp;$waktu_giat2</td>
                        </tr>
                        <tr>
                            <td align='left' style='vertical-align:top;border-right: none;' align='left'>&nbsp;Keterangan</td>
                            <td align='center' style='vertical-align:top;border-left: none;border-right: none;'>:</td>
                            <td align='left' colspan='3' style='vertical-align:top;border-left: none;'>$keterangan</td>
                        </tr>
                    </table>    
                        
                    ";
/*untuk isi subkegiatan*/

                        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                                
                            <tr><td rowspan='2' bgcolor='#CCCCCC' width='10%' align='center'><b>Kode Rekening</b></td>                            
                                <td rowspan='2' bgcolor='#CCCCCC' width='40%' align='center'><b>Uraian</b></td>
                                <td colspan='4' bgcolor='#CCCCCC' width='30%' align='center'><b>Rincian Perhitungan</b></td>
                                <td rowspan='2' bgcolor='#CCCCCC' width='20%' align='center'><b>Jumlah(Rp.)</b></td></tr>
                            <tr>
                                <td width='8%' bgcolor='#CCCCCC' align='center'>Volume</td>
                                <td width='8%' bgcolor='#CCCCCC' align='center'>Satuan</td>
                                <td width='10%' bgcolor='#CCCCCC' align='center'>harga</td>
                                <td width='4%' bgcolor='#CCCCCC' align='center'>PPN</td>
                            </tr>    
                         
                         
                           <tr>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='10%'>&nbsp;1</td>                            
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='40%'>&nbsp;2</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='8%'>&nbsp;3</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='8%'>&nbsp;4</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='10%'>&nbsp;5</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='4%'>&nbsp;6</td>
                                <td style='vertical-align:top;border-top: none;border-bottom: solid 1px black;' align='center' width='20%'>&nbsp;7</td>
                            </tr>
                            ";

                            $sql1="SELECT * FROM(SELECT 0 header,0 no_po, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan,
                            0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE a.kd_sub_kegiatan='$sub' AND a.kd_skpd='$id' 
                             GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
                            UNION ALL 
                            SELECT 0 header, 0 no_po,LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama,0 AS volume,' 'AS satuan,
                            0 AS harga,SUM(a.nilai) AS nilai,'2' AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE a.kd_sub_kegiatan='$sub'
                            AND a.kd_skpd='$id' GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
                            UNION ALL  
                            SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan,
                            0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE a.kd_sub_kegiatan='$sub'
                            AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
                            UNION ALL 
                            SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan,
                            0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE a.kd_sub_kegiatan='$sub'
                            AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
                            UNION ALL 
                            SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,8) AS rek1,RTRIM(LEFT(a.kd_rek6,8)) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan,
                            0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE a.kd_sub_kegiatan='$sub'
                            AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5
                            UNION ALL
                            SELECT 0 header, 0 no_po, a.kd_rek6 AS rek1,RTRIM(a.kd_rek6) AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan,
                            0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE a.kd_sub_kegiatan='$sub'
                            AND a.kd_skpd='$id'  GROUP BY a.kd_rek6,b.nm_rek6
                            UNION all
                            SELECT * FROM (SELECT  b.header,b.no_po,a.kd_rek6 AS rek1,' 'AS rek,b.uraian AS nama,0 AS volume,' ' AS satuan,
                            0 AS harga,SUM(a.total) AS nilai,'7' AS id 
                            FROM trdpo a
                            LEFT JOIN trdpo b ON b.kode=a.kode AND b.header ='1' AND a.no_trdrka=b.no_trdrka 
                            WHERE a.kd_skpd='$id' AND a.kd_sub_kegiatan='$sub'
                            GROUP BY  a.kd_skpd,b.header,b.no_po,b.uraian,a.kd_rek6)z WHERE header='1'
                            UNION ALL
                            SELECT a. header,a.no_po,a.kd_rek6 AS rek1,' 'AS rek,a.uraian AS nama,a.volume AS volume,a.satuan AS satuan,
                            a.harga AS harga,a.total AS nilai,'8' AS id FROM trdpo a  WHERE a.kd_skpd='$id' AND a.kd_sub_kegiatan='$sub' AND (header='0' or header is null)
                            ) a ORDER BY a.rek1,a.no_po
                            ";
                     
                    $query = $this->db->query($sql1);

                            $nilai_sub=0;
                            foreach ($query->result() as $row)
                            {
                                $rekx=$row->rek;
                                $rekex=$this->support->dotrek($rekx);
                                $uraianx=$row->nama;
                                $satx=$row->satuan;
                                $hrgx= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                                $volumx= empty($row->volume) || $row->volume == 0 ? '' :$row->volume;
                                $nilax= empty($row->nilai) || $row->nilai == 0 ? '' :number_format($row->nilai,2,',','.');

                               if($row->id=='6'){
                                     $nilai_sub= $nilai_sub+$row->nilai;
                               }
                                 $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$rekex</td>                                     
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='40%'>$uraianx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='8%' align='right'>$volumx &nbsp;&nbsp;&nbsp;</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='8%' align='center'>$satx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='14%' align='right'>$hrgx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$nilax</td></tr>
                                                 ";
                            if ($row->id<'7'){
                               
                                 $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$rekex</td>                                     
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='40%'>$uraianx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='8%' align='right'>$volumx&nbsp;&nbsp;&nbsp;</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='8%' align='center'>$satx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='14%' align='right'>$hrgx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'></td></tr>
                                                 ";

                                             }else{
                                                $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;' width='10%' align='left'>$rekex</td>                                     
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='40%'>&nbsp;&nbsp;$uraianx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='8%' align='right'>$volumx&nbsp;&nbsp;&nbsp;</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='8%' align='center'>$satx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='14%' align='right'>$hrgx</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;'  align='right'></td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>$nilax</td></tr>
                                                 ";
                                                 //$nilangsub= $nilangsub+$row->nilai;        
                                             }

                            }

                                             
                            
              /*                   $cRet    .= " <tr>
                                                 <td colspan='6' style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah SubKegiatan</td>
                                                 <td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='right'>".number_format($nilai_sub,2,',','.')."</td></tr>
                                                 ";*/
                            $cRet .="</table>";
/*end untuk isi kegiatan*/

                  
                       
            
                    } /*end groupSubKeluaran*/
                            $totsubKeluar=$this->db->query("SELECT sum(total) jum from trdpo where LEFT(no_trdrka,22)='$id' AND SUBSTRING(no_trdrka,24,15)='$sub'")->row();

                            $cRet    .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>  
                                        <tr>                                    
                                         <td colspan='6' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'><i>Jumlah Anggaran Sub Kegiatan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>".number_format($totsubKeluar->jum,2,',','.')."</td></tr>
                                         <tr>                                    
                                         <td colspan='7'  align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;' width='40%'>&nbsp;</td></tr>
                                         </table>";
           

                


                        $cRet    .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'> 
                                    
                                     <tr>                                    
                                     <td colspan='5' align='right' style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='40%'>Jumlah Anggaran Kegiatan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' width='20%' align='right'>$totp</td></tr>
                                     </table>";
        

                         $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>";

                 $kd_ttd=substr($id,18,2);
                 $kd_kepala=substr($id,0,7);
                 if ((($kd_ttd=='01') && ($kd_kepala!='1.20.03'))){
                             $cRet .="<tr>
                                <td width='100' align='center' colspan='6'>                           
                                <table border='0'>
                                    <tr>
                                    
                                        <td width='40%' align='center'>
       
                                        </td>
                                        <td width='20%' align='left'>&nbsp;<br>&nbsp;
                                            <br>&nbsp; 
                                            &nbsp;<br>
                                            &nbsp;<br>
                                            &nbsp;<br>
                                            &nbsp;  
                                            </td>
                                            <td width='40%' align='center'>$daerah,&nbsp;&nbsp;$tanggal_ttd
                                            <br>$jabatan2
                                            <br><br><br><br>
                                            <br><b><u>$nama2</u></b>
                                            <br>NIP. $nip2 
                                        </td>
                                    </tr>
                                </table>
                                </td>
                             </tr>";
                             } else {
                             $cRet .="<tr>
                                <td width='100' align='center' colspan='6'>                           
                                <table border='0'>
                                    <tr>
                                    
                                        <td width='40%' align='center'>
       
                                        </td>
                                        <td width='20%' align='left'>&nbsp;<br>&nbsp;
                                            <br>&nbsp; 
                                            &nbsp;<br>
                                            &nbsp;<br>
                                            &nbsp;<br>
                                            &nbsp;  
                                            </td>
                                            <td width='40%' align='center'>$daerah,&nbsp;&nbsp;$tanggal_ttd
                                            <br>$jabatan2
                                            <br><br><br><br>
                                            <br><b><u>$nama2</u></b>
                                            <br>NIP. $nip2 
                                        </td>
                                    </tr>
                                </table>
                                </td>
                             </tr>";
                             
                             }
                             
        if($jns_an=='DPA'){
            $hid="hidden";
        }else{
            $hid="";
        }
                  $cRet .= "<tr $hid>
                                <td width='100%' align='left' colspan='6' style='vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;'>Keterangan :</td>
                            </tr>";
                  $cRet .= "<tr $hid>
                                 <td width='100%' align='left' colspan='6' style='vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;'>Tanggal Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr $hid>
                                <td width='100%' align='left' colspan='6' style='vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;'>Catatan Hasil Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr $hid>
                                <td width='100%' align='left' colspan='6' style='vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;'>1.</td>
                            </tr>";
                  $cRet .= "<tr $hid>
                                <td width='100%' align='left' colspan='6' style='vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;'>2.</td>
                            </tr>";
                  $cRet .= "<tr $hid>
                                <td width='100%' align='left' colspan='6' style='vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;'>Dst</td>
                            </tr>";
                  $cRet .= "<tr $hid>
                                <td width='100%' align='center' colspan='6' style='vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;border-top: solid 1px black;'>Tim Anggaran Pemerintah Daerah</td>
                            </tr>";
                  
                            
                 
                 
        
              
        $cRet    .= "</table>";
        if($jns_an=='DPA'){
            $hid="hidden";
        }else{
            $hid="";
        }
         $cRet    .="<table $hid style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                         <td width='10%' align='center'>No </td>
                         <td width='30%'  align='center'>Nama</td>
                         <td width='20%'  align='center'>NIP</td>
                         <td width='20%'  align='center'>Jabatan</td>
                         <td width='20%'  align='center'>Tandatangan</td>
                    </tr>";
                    $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
                     $sqltapd = $this->db->query($sqltim);
                  if ($sqltapd->num_rows() > 0){
                    
                    $no=1;
                    foreach ($sqltapd->result() as $rowtim)
                    {
                        $no=$no;                    
                        $nama= $rowtim->nama;
                        $nip= $rowtim->nip;
                        $jabatan  = $rowtim->jab;
                        $cRet .="<tr>
                         <td width='5%' align='left'>$no </td>
                         <td width='20%'  align='left'>$nama</td>
                         <td width='20%'  align='left'>$nip</td>
                         <td width='35%'  align='left'>$jabatan</td>
                         <td width='20%'  align='left'></td>
                    </tr>"; 
                    $no=$no+1;              
                  }}
                    else{
                        $cRet .="<tr>
                         <td width='5%' align='left'> 1. </td>
                         <td width='20%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                         <td width='35%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                        </tr>
                        <tr>
                         <td width='5%' align='left'> 2. </td>
                         <td width='20%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                         <td width='35%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                        </tr>
                        <tr>
                         <td width='5%' align='left'> 3. </td>
                         <td width='20%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                         <td width='35%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                        </tr>
                        <tr>
                         <td width='5%' align='left'> 4. </td>
                         <td width='20%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                         <td width='35%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                        </tr>"; 
                    }

        $cRet .=       " </table>";
        $data['prev']= $cRet;    
        $judul='RKA-rincian_belanja_'.$id.'';
        switch($cetak) { 
        case 1; 

             $this->master_pdf->_mpdf_margin('',$cRet,$kanan,$kiri,10,'1','yes',$atas,$bawah);
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            echo $cRet;
        break;
        case 3;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            echo $cRet;
        break;
        case 0;  
            echo ("<title>RKA Rincian Belanja</title>");
            echo($cRet);
        break;
        }
    }

    function preview_rka_skpd_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji, $status1, $status2){
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
        $sqlsclient=$this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc){
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
        }

        $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
                a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
                 ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
        $sqlskpd=$this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns){
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header   = $rowdns->header;
                    $kd_org   = $rowdns->kd_org;
        } 

        switch ($status1) {
            case 'nilai':
                $status_anggaran1="";
                break;

            case 'nilai_sempurna':
                $status_anggaran1="_sempurna";
                break;

            case 'sempurna1':
                $status_anggaran1="sempurna1";
                break;
            case 'sempurna2':
                $status_anggaran1="sempurna2";
                break;
            case 'sempurna3':
                $status_anggaran1="sempurna3";
                break;
            case 'sempurna4':
                $status_anggaran1="sempurna4";
                break;
            case 'sempurna5':
                $status_anggaran1="sempurna5";
                break;
            case 'nilai_ubah':
                $status_anggaran1="_ubah";
                break;
            default:
                $status_anggaran1="_ubah";
                break;
        }

        $doc='DPA';
        $rka="DOKUMEN PELAKSANAAN ANGGARAN";
        switch ($status2) {
            case 'nilai':
                $status_anggaran2="";
                break;

            case 'nilai_sempurna':
                $status_anggaran2="_sempurna";
                break;

            case 'sempurna1':
                $status_anggaran2="sempurna1";
                break;
            case 'sempurna2':
                $status_anggaran2="sempurna2";
                break;
            case 'sempurna3':
                $status_anggaran2="sempurna3";
                break;
            case 'sempurna4':
                $status_anggaran2="sempurna4";
                break;
            case 'sempurna5':
                $status_anggaran2="sempurna5";
                break;
            case 'nilai_ubah':
                $status_anggaran2="_ubah";
                $status_anggaran2="_ubah";
                $rka="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                $doc='DPPA';
                break;
            default:
                $status_anggaran2="_ubah";
                $rka="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                $doc='DPPA';
                break;
        }
       


        if($doc=='RKA'){
            $rka="RENCANA KERJA DAN ANGGARAN";
            $judul="Ringkasan Anggaran Pendapatan dan Belanja
                    <br> Satuan Kerja Perangkat Daerah";
            $tambahan="";
        }else{

            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $judul="Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah
                    <br> Satuan Kerja Perangkat Daerah";
            $tambahan="<tr>
                        <td style='border-right:none'> No DPA</td>
                        <td style='border-left:none'>: $nodpa</td>
                    </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr> 
                         <td colspan='1' width='80%' align='center'><strong>$rka <br> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td colspan='1' width='20%' rowspan='4' align='center'><strong>$doc - SKPD</strong></td>
                    </tr>
                    <tr>
                         <td colspan='1' align='center'><strong>$kab <br>TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1' cellpadding='5px'>
                    $tambahan
                    <tr>
                        <td style='border-right:none'> Organisasi</td>
                        <td style='border-left:none'>: $kd_skpd - $nm_skpd</td>
                    </tr>
                    <tr>
                        <td colspan='2' bgcolor='#CCCCCC'> &nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='2' align='center'><strong>$judul </strong></td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='5px'>
                     <thead>                       
                        <tr>
                            <td bgcolor='#CCCCCC' rowspan='2' width='15%' align='center'><b>KODE REKENING</b></td>                            
                            <td bgcolor='#CCCCCC' rowspan='2' width='25%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>JUMLAH(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>BERTAMBAH/BERKURANG </b></td>                            
                        </tr>
                        <tr>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SEBELUM PERUBAHAN</b></td>                            
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SESUDAH PERUBAHAN</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>%</b></td>                            
                        </tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='25%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>3</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>4</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>5</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>6</td>
                        </tr>

                ";



        if($detail=='detail'){
            $rincian="  UNION ALL "."

                        SELECT a.kd_rek4 AS kd_rek,a.nm_rek4 AS nm_rek ,
                        SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek4 a INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17)  
                        GROUP BY a.kd_rek4, a.nm_rek4  
                        UNION ALL 

                        SELECT a.kd_rek5 AS kd_rek,a.nm_rek5 AS nm_rek ,
                        SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek5 a INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 

                        SELECT a.kd_rek6 AS kd_rek,a.nm_rek6 AS nm_rek ,
                        SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek6 a INNER JOIN trdrka b ON a.kd_rek6=LEFT(b.kd_rek6,(len(a.kd_rek6)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}
        
        $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek1 a 
                INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,1)='4' 
                and left(b.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek1, a.nm_rek1 

                UNION ALL 

                SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b 
                ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                GROUP BY a.kd_rek2,a.nm_rek2 

                UNION ALL 

                SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek, SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
                where left(b.kd_rek6,1)='4' and left(b.kd_skpd,17)=left('$id',17) 
                GROUP BY a.kd_rek3, a.nm_rek3 
                $rincian
                ORDER BY kd_rek";
                 
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0){                                  
            foreach ($query->result() as $row){
                    $coba1=$this->support->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,0,',','.');
                    $nilai4= number_format($row->nilai2,0,',','.');
                    $selisih=$this->support->rp_minus($row->nilai2-$row->nilai);
                    if($row->nilai==0){
                         $persen=$this->support->rp_minus(0);
                    }else{
                        $persen=$this->support->rp_minus((($row->nilai2-$row->nilai)/$row->nilai)*100);
                    }

                    $cRet.= " <tr>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>&nbsp;$coba1</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >&nbsp;$coba2</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$coba3</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$nilai4</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$selisih</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$persen</td>
                             </tr>";                     
            }
        }else{
                $cRet .= " <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>4</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' > PENDAPATAN </td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,0,',','.')."</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,0,',','.')."</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,0,',','.')."</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,0,',','.')."</td>
                          </tr>";
                    
                
        }                                 
                
        $sqltp="SELECT SUM(nilai$status_anggaran1) AS totp, SUM(nilai$status_anggaran2) AS totp1 FROM trdrka WHERE LEFT(kd_rek6,1)='4' and left(kd_skpd,17)=left('$id',17)";
        $sqlp=$this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp){

            $coba4=number_format($rowp->totp,0,',','.');
            $coba42=number_format($rowp->totp1,0,',','.');
            $selisih=$this->support->rp_minus($rowp->totp1-$rowp->totp);
            if($rowp->totp1==0){
                $persen=number_format($rowp->totp,0,',','.');
            }else{
                $persen=$this->support->rp_minus((($rowp->totp1-$rowp->totp)/$rowp->totp)*100);
            }

            $cob1=$rowp->totp;
            $total_pendapatan=$rowp->totp1;
                   
            $cRet    .= "<tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>Jumlah Pendapatan</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba4</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba42</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                        </tr>
                        <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >&nbsp;</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>                                     
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
                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, sum(b.nilai$status_anggaran2) as nilai2 FROM ms_rek4 a 
                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek4, a.nm_rek4 
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, sum(b.nilai$status_anggaran2) as nilai2 FROM ms_rek5 a 
                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, sum(b.nilai$status_anggaran2) as nilai2 FROM ms_rek6 a 
                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}     
                $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, sum(b.nilai$status_anggaran2) as nilai2 FROM ms_rek1 a 
                        INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek1, a.nm_rek1 
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, sum(b.nilai$status_anggaran2) as nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, sum(b.nilai$status_anggaran2) as nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,17)=left('$id',17) $aktifkanGaji
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->support->dotrek($row1->rek);
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,0,',','.');
                    $nilai5= number_format($row1->nilai2,0,',','.');
                    $selisih=$this->support->rp_minus($row1->nilai2-$row1->nilai);
                    if($row1->nilai==0){
                        $persen=$this->support->rp_minus(0);
                    }else{
                        $persen=$this->support->rp_minus((($row1->nilai2-$row1->nilai)/$row1->nilai)*100);
                    }

                   
                     $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'>&nbsp;$coba5</td>                                     
                                     <td style='vertical-align:top;' >&nbsp;$coba6</td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$coba7</td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$nilai5</td>                                     
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$selisih</td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$persen</td>
                                    </tr>";
                }

                if($gaji==1){
                    $aktifkanGaji="and right(kd_sub_kegiatan,10) <> '01.2.02.01' ";
                }else{
                    $aktifkanGaji="";
                }     

                $sqltb="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb1 FROM trdrka WHERE LEFT(kd_rek6,1)='5' and left(kd_skpd,17)=left('$id',17) $aktifkanGaji";
                $sqlb=$this->db->query($sqltb);
                foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,0,',','.');
                   $coba81=number_format($rowb->totb1,0,',','.');
                    $cob=$rowb->totb;
                    $selisih=$this->support->rp_minus($rowb->totb1-$rowb->totb);
                    $persen=$this->support->rp_minus((($rowb->totb1-$rowb->totb)/$rowb->totb)*100);
                    if($rowb->totb==0){
                        $persen=$this->support->rp_minus(0);
                    }else{
                        $persen=$this->support->rp_minus((($rowb->totb1-$rowb->totb)/$rowb->totb)*100);
                    }
                    $total_belanja=$rowb->totb1;
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'>Jumlah Belanja</td>
                                     <td style='vertical-align:top;'  align='right'>$coba8</td>
                                     <td style='vertical-align:top;'  align='right'> $coba81</td>                                     
                                     <td style='vertical-align:top;'  align='right'> $selisih</td>
                                     <td style='vertical-align:top;'  align='right'>$persen</td>
                                </tr>";
                 }
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                  </tr>";
                 
                  $surplus=$cob1-$cob;
                  $surplus2=$total_pendapatan-$total_belanja; 
                    
                    $cRet .= " <tr>   
                                    <td></td>                                 
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>Surplus/Defisit</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($surplus)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>".$this->rka_model->angka($surplus2)."</td>                                 
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>".$this->support->rp_minus($surplus-$surplus2)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->support->rp_minus((($surplus-$surplus2)/$surplus2)*100)."</td>
                            </tr>"; 

                    
                $sqltpm="SELECT isnull(SUM(nilai$status_anggaran1),0) AS totb, isnull(SUM(nilai$status_anggaran2),0) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,1)='6' and left(kd_skpd,17)=left('$id',17)";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                    $coba12=number_format($rowtpm->totb,0,',','.');
                    $coba12x=number_format($rowtpm->totb2,0,',','.');
                    $selisih=$this->support->rp_minus($rowtpm->totb2-$rowtpm->totb);
                    
                    $cobtpm=$rowtpm->totb;
                    if($cobtpm>0){

                        
                        $persen=$this->support->rp_minus((($rowtpm->totb2-$rowtpm->totb)/$rowtpm->totb)*100);
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                    </tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;'  align='left'>6</td>                                     
                                         <td style='vertical-align:top;' >Pembiayaan</td>
                                         <td style='vertical-align:top;'  align='right'>$coba12
                                     <td style='vertical-align:top;'  align='right'>$coba12x</td>                                     
                                     <td style='vertical-align:top;'  align='right'>$selisih</td>
                                     <td style='vertical-align:top;'  align='right'> $persen</td>
                                    </td>
                                    </tr>";
                        if($detail=='detail'){
                            $rincian="  UNION ALL "." 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            $coba10=$rowpm->nm_rek;
                            $coba11= number_format($rowpm->nilai,0,',','.');
                            $nilai2= number_format($rowpm->nilai2,0,',','.');
                            $selisih=$this->support->rp_minus($rowpm->nilai2-$rowpm->nilai);
                            $persen=$this->support->rp_minus((($rowpm->nilai2-$rowpm->nilai)/$rowpm->nilai)*100);
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba11</td>
                                                <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                            </tr>";
                        } 


                        $sqltpm="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='61' and left(kd_skpd,17)=left('$id',17)";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                            $coba12=number_format($rowtpm->totb,0,',','.');
                                            $nilai2=number_format($rowtpm->totb2,0,',','.');
                                            $selisih=$this->support->rp_minus($rowtpm->totb2-$rowtpm->totb);
                                            $persen=$this->support->rp_minus((($rowtpm->totb2-$rowtpm->totb)/$rowtpm->totb)*100);
                                            $cobtpm=$rowtpm->totb;
                                            $cobtpm2=$rowtpm->totb2;
                                            $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba12</td>
                                                    <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                                        </tr>";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL "." 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,17)=left('$id',17) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            $coba10=$rowpk->nm_rek;
                            $coba11= number_format($rowpk->nilai,0,',','.');
                            $nilai2= number_format($rowpk->nilai2,0,',','.');
                            $selisih=$this->support->rp_minus($rowpk->nilai2-$rowpk->nilai);
                            $persen=$this->support->rp_minus((($rowpk->nilai2-$rowpk->nilai)/$rowpk->nilai)*100);
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='62' and left(kd_skpd,17)=left('$id',17)";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                   $cobatpk=number_format($rowtpk->totb,0,',','.');
                    $cobtpk=$rowtpk->totb;
                    $cobtpk2=$rowtpk->totb2;
                    $nilai2= number_format($rowtpk->totb2,0,',','.');
                    $selisih=$this->support->rp_minus($rowtpk->totb2-$rowtpk->totb);
                    $persen=$this->support->rp_minus((($rowtpk->totb2-$rowtpk->totb)/$rowtpk->totb)*100);

                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$cobatpk</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr>";
                 }
    
                $pnetto=$cobtpm-$cobtpk;
                $pnetto2=$cobtpm2-$cobtpk2;
                $selisih=$pnetto2-$pnetto;
                $persen=$this->support->rp_minus(($selisih/$pnetto2)*100);

                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' >Pembiayaan Netto</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($pnetto)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($pnetto2)."</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($selisih)."</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr></table>";                                                      
                    

                    } /*end if pembiayaan 0*/

                } 
               
                $cRet    .= "</table>";

                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='4' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();

               
  if($ttd1!='tanpa'){ 
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
              
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u>
                        


                                </td>";
              
        }else{
            $tambahan="";
        }

                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Penerimaan Per Bulan</td>
                                <td colspan='2' align='center' width='30%'>Rencana Penarikan Dana Per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas4->jan,0,',','.')."</td> 
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas5->jan,0,',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas4->feb,0,',','.')."</td> 
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas5->feb,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas4->mar,0,',','.')."</td> 
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas5->mar,0,',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas4->apr,0,',','.')."</td> 
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas5->apr,0,',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas4->mei,0,',','.')."</td> 
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas5->mei,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas4->jun,0,',','.')."</td> 
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas5->jun,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas4->jul,0,',','.')."</td> 
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas5->jul,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas4->ags,0,',','.')."</td> 
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas5->ags,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas4->sept,0,',','.')."</td> 
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas5->sept,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas4->okt,0,',','.')."</td> 
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas5->okt,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas4->nov,0,',','.')."</td> 
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas5->nov,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas4->des,0,',','.')."</td> 
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas5->des,0,',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,0,',','.')."</td> 
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,0,',','.')."</td>                                 
                            </tr>

                        </table>";
              
        
       
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

     function preview_rka_skpd_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$gaji, $status1, $status2){
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
        $sqlsclient=$this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc){
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
        }

        $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
                a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
                 ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
        $sqlskpd=$this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns){
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header   = $rowdns->header;
                    $kd_org   = $rowdns->kd_org;
        } 

        if($status1=='nilai'){
            $status_anggaran1="";
        } else if($status1=='nilai_sempurna'){
            $status_anggaran1="_sempurna";
        } else{
            $status_anggaran1="_ubah";
        }

        if($status2=='nilai'){
            $status_anggaran2="";
            $doc='DPA';
            $rka="DOKUMEN PELAKSANAAN ANGGARAN";
        } else if($status2=='nilai_sempurna'){
            $rka="DOKUMEN PELAKSANAAN ANGGARAN";
            $status_anggaran2="_sempurna";
            $doc='DPA';
        } else{
            $status_anggaran2="_ubah";
            $rka="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
            $doc='DPPA';
        }


        if($doc=='RKA'){
            $rka="RENCANA KERJA DAN ANGGARAN";
            $judul="Ringkasan Anggaran Pendapatan dan Belanja
                    <br> Satuan Kerja Perangkat Daerah";
            $tambahan="";
        }else{

            $nodpa=$this->db->query("SELECT no_dpa_sempurna from trhrka where kd_skpd='$id'")->row()->no_dpa_sempurna;
            $judul="Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah
                    <br> Satuan Kerja Perangkat Daerah";
            $tambahan="<tr>
                        <td style='border-right:none'> No DPA</td>
                        <td style='border-left:none'>: $nodpa</td>
                    </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr> 
                         <td colspan='1' width='80%' align='center'><strong>$rka <br> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td colspan='1' width='20%' rowspan='4' align='center'><strong>$doc - SKPD</strong></td>
                    </tr>
                    <tr>
                         <td colspan='1' align='center'><strong>$kab <br>TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1' cellpadding='5px'>
                    $tambahan
                    <tr>
                        <td style='border-right:none'> Organisasi</td>
                        <td style='border-left:none'>: $kd_skpd - $nm_skpd</td>
                    </tr>
                    <tr>
                        <td colspan='2' bgcolor='#CCCCCC'> &nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='2' align='center'><strong>$judul </strong></td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='5px'>
                     <thead>                       
                        <tr>
                            <td bgcolor='#CCCCCC' rowspan='2' width='15%' align='center'><b>KODE REKENING</b></td>                            
                            <td bgcolor='#CCCCCC' rowspan='2' width='25%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>JUMLAH(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>BERTAMBAH/BERKURANG </b></td>                            
                        </tr>
                        <tr>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SEBELUM PERGESERAN</b></td>                            
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SESUDAH PERGESERAN</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>%</b></td>                            
                        </tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='25%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>3</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>4</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>5</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>6</td>
                        </tr>
                ";



        if($detail=='detail'){
            $rincian="  UNION ALL "."
                        SELECT a.kd_rek4 AS kd_rek,a.nm_rek4 AS nm_rek ,
                        SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek4 a INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,22)=left('$id',22)  
                        GROUP BY a.kd_rek4, a.nm_rek4  
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.nm_rek5 AS nm_rek ,
                        SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek5 a INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,22)=left('$id',22) 
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.nm_rek6 AS nm_rek ,
                        SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek6 a INNER JOIN trdrka b ON a.kd_rek6=LEFT(b.kd_rek6,(len(a.kd_rek6)))
                        where left(b.kd_rek6,1)='4' and left(b.kd_skpd,22)=left('$id',22) 
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}
        
        $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek1 a 
                INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,1)='4' 
                and left(b.kd_skpd,22)=left('$id',22) GROUP BY a.kd_rek1, a.nm_rek1 
                UNION ALL 
                SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b 
                ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,1)='4' and left(b.kd_skpd,22)=left('$id',22) 
                GROUP BY a.kd_rek2,a.nm_rek2 
                UNION ALL 
                SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek, SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
                where left(b.kd_rek6,1)='4' and left(b.kd_skpd,22)=left('$id',22) 
                GROUP BY a.kd_rek3, a.nm_rek3 
                $rincian
                ORDER BY kd_rek";
                 
        $query = $this->db->query($sql1);
        if ($query->num_rows() > 0){                                  
            foreach ($query->result() as $row){
                    $coba1=$this->support->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"2",",",".");
                    $nilai4= number_format($row->nilai2,"2",",",".");
                    $selisih=$this->support->rp_minus($row->nilai-$row->nilai2);
                    $persen=$this->support->rp_minus((($row->nilai-$row->nilai2)/$row->nilai2)*100);
                   
                    $cRet.= " <tr>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>&nbsp;$coba1</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >&nbsp;$coba2</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$coba3</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$nilai4</td>                                     
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$selisih</td>
                                <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;$persen</td>
                             </tr>";                     
            }
        }else{
                $cRet .= " <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>4</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >PENDAPATAN</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"2",",",".")."</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"2",",",".")."</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"2",",",".")."</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>".number_format(0,"2",",",".")."</td>
                          </tr>";
                    
                
        }                                 
                
        $sqltp="SELECT SUM(nilaisempurna1) AS totp, SUM(nilaisempurna2) AS totp1 FROM trdrka WHERE LEFT(kd_rek6,1)='4' and left(kd_skpd,22)=left('$id',22)";
        $sqlp=$this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp){

            $coba4=number_format($rowp->totp,"2",",",".");
            $coba42=number_format($rowp->totp1,"2",",",".");
            $selisih=$this->support->rp_minus($rowp->totp-$rowp->totp1);
            if($rowp->totp1==0){
                $persen=number_format($rowp->totp1,"2",",",".");
            }else{
                $persen=$this->support->rp_minus((($rowp->totp-$rowp->totp1)/$rowp->totp1)*100);
            }

            $cob1=$rowp->totp;
            $total_pendapatan=$rowp->totp1;
                   
            $cRet    .= "<tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>Jumlah Pendapatan</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba4</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba42</td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                        </tr>
                        <tr>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >&nbsp;</td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>
                            <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>                                     
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
                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, sum(b.nilaisempurna2) as nilai2 FROM ms_rek4 a 
                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,22)=left('$id',22) $aktifkanGaji
                        GROUP BY a.kd_rek4, a.nm_rek4 
                        UNION ALL 
                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, sum(b.nilaisempurna2) as nilai2 FROM ms_rek5 a 
                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,22)=left('$id',22) $aktifkanGaji
                        GROUP BY a.kd_rek5, a.nm_rek5 
                        UNION ALL 
                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, sum(b.nilaisempurna2) as nilai2 FROM ms_rek6 a 
                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,1)='5' AND left(b.kd_skpd,22)=left('$id',22) $aktifkanGaji
                        GROUP BY a.kd_rek6, a.nm_rek6";
        }else{ $rincian='';}     
                $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, sum(b.nilaisempurna2) as nilai2 FROM ms_rek1 a 
                        INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,22)=left('$id',22) $aktifkanGaji
                        GROUP BY a.kd_rek1, a.nm_rek1 
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, sum(b.nilaisempurna2) as nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,22)=left('$id',22) $aktifkanGaji
                        GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, sum(b.nilaisempurna2) as nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5' AND left(b.kd_skpd,22)=left('$id',22) $aktifkanGaji
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->support->dotrek($row1->rek);
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"2",",",".");
                    $nilai5= number_format($row1->nilai2,"2",",",".");
                    $selisih=$this->support->rp_minus($row1->nilai-$row1->nilai2);
                    $persen=$this->support->rp_minus((($row1->nilai-$row1->nilai2)/$row1->nilai2)*100);
                   
                    /*$selisih= $this->support->rp_minus($row->nilai-$row->nilai2);
                                if($row->nilai==0){
                                    $persen=0;
                                }else{
                                    $persen= $this->support->rp_minus((($row->nilai-$row->nilai2)/$row->nilai)*100);
                                }*/
                     $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'>&nbsp;$coba5</td>                                     
                                     <td style='vertical-align:top;' >&nbsp;$coba6</td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$coba7</td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$nilai5</td>                                     
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$selisih</td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;$persen</td>
                                    </tr>";
                }

                if($gaji==1){
                    $aktifkanGaji="and right(kd_sub_kegiatan,10) <> '01.2.02.01' ";
                }else{
                    $aktifkanGaji="";
                }     

                $sqltb="SELECT SUM(nilaisempurna1) AS totb, SUM(nilaisempurna2) AS totb1 FROM trdrka WHERE LEFT(kd_rek6,1)='5' and left(kd_skpd,22)=left('$id',22) $aktifkanGaji";
                $sqlb=$this->db->query($sqltb);
                foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,"2",",",".");
                   $coba81=number_format($rowb->totb1,"2",",",".");
                    $cob=$rowb->totb;
                    $selisih=$this->support->rp_minus($rowb->totb-$rowb->totb1);
                    $persen=$this->support->rp_minus((($rowb->totb-$rowb->totb1)/$rowb->totb1)*100);
                    $total_belanja=$rowb->totb1;
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'>Jumlah Belanja</td>
                                     <td style='vertical-align:top;'  align='right'>$coba8</td>
                                     <td style='vertical-align:top;'  align='right'> $coba81</td>                                     
                                     <td style='vertical-align:top;'  align='right'> $selisih</td>
                                     <td style='vertical-align:top;'  align='right'>$persen</td>
                                </tr>";
                 }
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                  </tr>";
                  
                  $surplus=$cob1-$cob;
                  $surplus2=$total_pendapatan-$total_belanja; 
                    $cRet    .= " <tr>   
                                    <td></td>                                 
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>Surplus/Defisit</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($surplus)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>".$this->rka_model->angka($surplus2)."</td>                                 
                                     <td style='vertical-align:top;border-top: solid 1px black;' align='right'>".$this->support->rp_minus($surplus-$surplus2)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->support->rp_minus((($surplus-$surplus2)/$surplus2)*100)."</td>
                            </tr>"; 

                    
                $sqltpm="SELECT isnull(SUM(nilai$status_anggaran1),0) AS totb, isnull(SUM(nilai$status_anggaran2),0) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,1)='6' and left(kd_skpd,22)=left('$id',22)";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                    $coba12=number_format($rowtpm->totb,"2",",",".");
                    $coba12x=number_format($rowtpm->totb2,"2",",",".");
                    $selisih=$this->support->rp_minus($rowtpm->totb-$rowtpm->totb2);
                    
                    $cobtpm=$rowtpm->totb;
                    if($cobtpm>0){
                        $persen=$this->support->rp_minus((($rowtpm->totb-$rowtpm->totb2)/$rowtpm->totb2)*100);
                    $cRet    .= " <tr>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;'  align='left'></td>                                     
                                     <td style='vertical-align:top;'  align='right'></td>
                                     <td style='vertical-align:top;'  align='right'>&nbsp;</td>
                                    </tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;'  align='left'>6</td>                                     
                                         <td style='vertical-align:top;' >Pembiayaan</td>
                                         <td style='vertical-align:top;'  align='right'>$coba12
                                     <td style='vertical-align:top;'  align='right'>$coba12x</td>                                     
                                     <td style='vertical-align:top;'  align='right'>$selisih</td>
                                     <td style='vertical-align:top;'  align='right'> $persen</td>
                                    </td>
                                    </tr>";
                        if($detail=='detail'){
                            $rincian="  UNION ALL "." 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,22)=left('$id',22) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,22)=left('$id',22) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61' AND left(b.kd_skpd,22)=left('$id',22) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,22)=left('$id',22) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,22)=left('$id',22) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            $coba10=$rowpm->nm_rek;
                            $coba11= number_format($rowpm->nilai,"2",",",".");
                            $nilai2= number_format($rowpm->nilai2,"2",",",".");
                            $selisih=$this->support->rp_minus($coba11-$nilai2);
                            $persen=$this->support->rp_minus((($coba11-$nilai2)/$nilai2)*100);
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba11</td>
                                                <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                            </tr>";
                        } 


                        $sqltpm="SELECT SUM(nilaisempurna1) AS totb, SUM(nilaisempurna2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='61' and left(kd_skpd,22)=left('$id',22)";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                            $coba12=number_format($rowtpm->totb,"2",",",".");
                                            $nilai2=number_format($rowtpm->totb2,"2",",",".");
                                            $selisih=$this->support->rp_minus($rowtpm->totb-$rowtpm->totb2);
                                            $persen=$this->support->rp_minus((($rowtpm->totb-$rowtpm->totb2)/$rowtpm->totb2)*100);
                                            $cobtpm=$rowtpm->totb;
                                            $cobtpm2=$rowtpm->totb2;
                                            $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba12</td>
                                                    <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                                        </tr>";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL "." 
                                        SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,22)=left('$id',22) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,22)=left('$id',22) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62' AND left(b.kd_skpd,22)=left('$id',22) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,22)=left('$id',22) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilaisempurna1) AS nilai, SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,22)=left('$id',22) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            $coba10=$rowpk->nm_rek;
                            $coba11= number_format($rowpk->nilai,"2",",",".");
                            $nilai2= number_format($rowpk->nilai2,"2",",",".");
                            $selisih=$this->support->rp_minus($rowpk->nilai-$rowpk->nilai2);
                            $persen=$this->support->rp_minus((($rowpk->nilai-$rowpk->nilai2)/$rowpk->nilai2)*100);
                           
                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr>";
                        } 


                        $sqltpk="SELECT SUM(nilaisempurna1) AS totb, SUM(nilaisempurna2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='62' and left(kd_skpd,22)=left('$id',22)";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                   $cobatpk=number_format($rowtpk->totb,"2",",",".");
                    $cobtpk=$rowtpk->totb;
                    $cobtpk2=$rowtpk->totb2;
                    $nilai2= number_format($rowtpk->totb2,"2",",",".");
                    $selisih=$this->support->rp_minus($rowtpk->totb-$rowtpk->totb2);
                    $persen=$this->support->rp_minus((($rowtpk->totb-$rowtpk->totb2)/$rowtpk->totb2)*100);

                    $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$cobatpk</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr>";
                 }
    
                $pnetto=$cobtpm-$cobtpk;
                $pnetto2=$cobtpm2-$cobtpk2;
                $selisih=$pnetto-$pnetto2;
                $persen=$this->support->rp_minus(($selisih/$pnetto2)*100);

                    $cRet    .= " <tr>                                     
                                     <td colspan='2' style='vertical-align:top;border-top: solid 1px black;' align='right' >Pembiayaan Netto</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($pnetto)."</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($pnetto2)."</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>".$this->rka_model->angka($selisih)."</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr></table>";                                                      
                    

                    } /*end if pembiayaan 0*/

                } 
               
                $cRet    .= "</table>";

                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd, sum(nilai_sempurna2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd , sum(nilai_sempurna2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='4' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();

               
  if($ttd1!='tanpa'){ 
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
              
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama</u><br>
                                $nip
                                 <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                             SILVESTRA DAYANA SIMBOLON, SE.,MM</u><br/>
                                             NIP. 19671126 199503 2 004
                        
                                </td>";
              
        }else{
            $tambahan="";
        }

                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Penerimaan Per Bulan</td>
                                <td colspan='2' align='center' width='30%'>Rencana Penarikan Dana Per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas4->jan,'2',',','.')."</td> 
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas4->feb,'2',',','.')."</td> 
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas4->mar,'2',',','.')."</td> 
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas4->apr,'2',',','.')."</td> 
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas4->mei,'2',',','.')."</td> 
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas4->jun,'2',',','.')."</td> 
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas4->jul,'2',',','.')."</td> 
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas4->ags,'2',',','.')."</td> 
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas4->sept,'2',',','.')."</td> 
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas4->nov,'2',',','.')."</td> 
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas4->des,'2',',','.')."</td> 
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                        </table>";
              
        
       
        $data['prev']= $cRet;    
        $judul         = 'RKA SKPD';
        switch($cetak) { 
        case 1;
             $this->support->_mpdf('',$cRet,10,10,10,'1');
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


    function preview_pendapatan_penyusunan($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc){
        
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                }
        $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,22) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header  = $rowdns->header;
                    $kd_org = $rowdns->kd_org;
                }


        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }

        
        if($doc=='RKA'){
            $dokumen="RENCANA KERJA DAN ANGGARAN";
            $tabeldpa="";
        }else{
            $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tabeldpa="<tr>
                        <td width='20%' style='border-right:none'>No DPA </td>
                        <td width='80%' style='border-left:none'>: $nodpa</td>
                    </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>  
                         <td width='80%' align='center'><strong>$dokumen <br /> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td width='20%' rowspan='2' align='center'><strong>$doc - <br />PENDAPATAN SKPD  </strong></td>
                    </tr>
                    <tr>
                         <td align='center'><strong>$kab <br /> TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                  </table>";
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1', cellpadding='5px'>
                    $tabeldpa
                    <tr>
                        <td width='20%' style='border-right:none'>Organisasi </td>
                        <td width='80%' style='border-left:none'>: $kd_org -".$this->rka_model->get_nama($id,'nm_skpd','ms_skpd','kd_skpd')."</td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' colspan='2'>&nbsp;</td>
                       
                    </tr>
                    <tr>
                        <td colspan='2' align='center'><strong>Ringkasan Anggaran Pendapatan Satuan Kerja Perangkat Daerah </strong></td>
                    </tr>
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='4'>
                     <thead>                       
                        <tr><td rowspan='2' bgcolor='#CCCCCC' width='20%' align='center'><b>Kode Rekening</b></td>                            
                            <td rowspan='2' bgcolor='#CCCCCC' width='20%' align='center'><b>Uraian</b></td>
                            <td colspan='3' bgcolor='#CCCCCC' width='30%' align='center'><b>Rincian Perhitungan</b></td>
                            <td rowspan='2' bgcolor='#CCCCCC' width='30%' align='center'><b>Jumlah(Rp.)</b></td></tr>
                        <tr>
                            <td width='8%' bgcolor='#CCCCCC' align='center'>Volume</td>
                            <td width='8%' bgcolor='#CCCCCC' align='center'>Satuan</td>
                            <td width='14%' bgcolor='#CCCCCC' align='center'>Tarif/harga</td>
                        </tr>    
                     </thead>
                        ";
                  $sql1="SELECT * FROM(
                        SELECT '' header, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka a 
                        INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, 0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'2' AS id 
                        FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'3' AS id 
                        FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'4' AS id 
                        FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
                        UNION ALL 
                        SELECT '' header, LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'5' AS id 
                        FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5 
                        UNION ALL 
                        SELECT '' header, a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'6' AS id 
                        FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)=left('$id',17) GROUP BY a.kd_rek6,b.nm_rek6 
                        UNION ALL 
                        SELECT cast(header as varchar) header, kd_rek6 AS rek1,' 'AS rek,a.uraian AS nama,a.volume AS volume,a.satuan AS satuan, a.harga AS harga,a.total AS nilai,'7' AS id 
                        FROM trdpo a WHERE left(kd_skpd,17)=left('$id',17)
                        AND left(kd_rek6,1)='4'
                        ) a ORDER BY a.rek1,a.id";
                 
                $query = $this->db->query($sql1);
                  if ($query->num_rows() > 0){                                                                                   
                        foreach ($query->result() as $row)
                        {
                            $rek=$row->rek;
                            $rek1=$row->rek1;
                            $reke=$this->support->dotrek($rek);
                            $uraian=$row->nama;
                            $volum=$row->volume;
                            $header=$row->header;
                            $sat=$row->satuan;

                            $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                            $nila= number_format($row->nilai,"2",",",".");
                           
                                
                            if($reke!=' '){
                                $volum = '';
                            }
                            
                            if((strlen($rek1)< 14 || $header== '1') && $header!= '0'){
                                if($header== '1'){
                                 $cRet    .= " <tr><td style='vertical-align:top;' width='20%' align='left'>$reke</td>                                     
                                                 <td colspan='5' style='vertical-align:top;' width='20%'>:: $uraian</td>
                                                 </tr>
                                             ";
                                }else{
                                 $cRet    .= " <tr><td style='vertical-align:top;' width='20%' align='left'>$reke</td>                                     
                                                 <td colspan='4' style='vertical-align:top;' width='20%'>$uraian</td>
                                                 <td style='vertical-align:top;' width='30%' align='right'>$nila</td></tr>
                                             ";                                    
                                }
                            }else{
                                 $cRet    .= " <tr><td style='vertical-align:top;' width='20%' align='left'>$reke</td>                                     
                                                 <td style='vertical-align:top;' width='20%'>$uraian</td>
                                                 <td style='vertical-align:top;' width='8%'>$volum</td>
                                                 <td style='vertical-align:top;' width='8%'>$sat</td>
                                                 <td style='vertical-align:top;' width='14%' align='right'>$hrg</td>
                                                 <td style='vertical-align:top;' width='30%' align='right'>$nila</td></tr>
                                             ";                                
                            }
                        

                  
                        } /*endforeach*/
                }else{
                     $cRet    .= " <tr><td style='vertical-align:top;' width='20%' align='left'>4</td>                                     
                                     <td style='vertical-align:top;' width='20%'>PENDAPATAN</td>
                                     <td style='vertical-align:top;' width='8%'></td>
                                     <td style='vertical-align:top;' width='8%'></td>
                                     <td style='vertical-align:top;' width='14%' align='right'></td>
                                     <td style='vertical-align:top;' width='30%' align='right'>".number_format(0,"2",",",".")."</td></tr>
                                     ";
                    
                } /*endif*/


                   $cRet .= "<tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align='right'>&nbsp;</td>
                             </tr>";
                 $sqltp="SELECT SUM(nilai) AS totp FROM trdrka WHERE LEFT(kd_rek6,1)='4' AND left(kd_skpd,22)=left('$id',22)";
                    $sqlp=$this->db->query($sqltp);
                 foreach ($sqlp->result() as $rowp)
                {
                   $totp=number_format($rowp->totp,"2",",",".");
                   
                    $cRet    .=" <tr><td style='vertical-align:top;border-top: solid 1px black;' width='20%' align='left'>&nbsp;</td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='20%'>Jumlah Pendapatan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='8%'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='8%'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='14%' align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;' width='30%' align='right'>$totp</td></tr>";
                 }
            
        $cRet    .= "</table>";

       
        if($doc=='RKA'){
         if($ttd1!='tanpa'){ /*end tanpa tanda tangan*/
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd WHERE kd_skpd= '$id' AND kode in ('PA','KPA') AND id_ttd='$ttd1'  ";
                 $sqlttd1=$this->db->query($sqlttd1);
                 foreach ($sqlttd1->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
                
        $cRet .="<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                        <td width='50%' align='center' style='border-right: none' ><br>
                        </td>
                        <td align='center' style='border-left: none'><br>
                            $daerah ,$tanggal_ttd<br>
                            $jabatan, <br>
                            <br><br>
                            <br><br>
                            <br><br>
                            <b>$nama</b><br>
                            $pangkat<br>
                            NIP. $nip 
                        </td>
                    </tr>
                   </table>";
         $cRet .= "<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'><tr>
                                <td width='100%' align='left' colspan='6'>Keterangan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                 <td width='100%' align='left' colspan='6'>Tanggal Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>Catatan Hasil Pembahasan :</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>1.</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>2.</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='left' colspan='6'>Dst</td>
                            </tr>";
                  $cRet .= "<tr>
                                <td width='100%' align='center' colspan='6'>Tim Anggaran Pemerintah Daerah</td>
                            </tr>";
                            $cRet    .= "</table>";
                  $cRet    .="<table style='border-collapse:collapse; font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                         <td width='10%' align='center'>No </td>
                         <td width='30%'  align='center'>Nama</td>
                         <td width='20%'  align='center'>NIP</td>
                         <td width='20%'  align='center'>Jabatan</td>
                         <td width='20%'  align='center'>Tandatangan</td>
                    </tr>";
                    $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
                     $sqltapd = $this->db->query($sqltim);
                  if ($sqltapd->num_rows() > 0){
                    
                        $no=1;
                        foreach ($sqltapd->result() as $rowtim)
                        {
                            $no=$no;                    
                            $nama= $rowtim->nama;
                            $nip= $rowtim->nip;
                            $jabatan  = $rowtim->jab;
                            $cRet .="<tr>
                             <td width='5%' align='left'>$no </td>
                             <td width='20%'  align='left'>$nama</td>
                             <td width='20%'  align='left'>$nip</td>
                             <td width='35%'  align='left'>$jabatan</td>
                             <td width='20%'  align='left'></td>
                        </tr>"; 
                        $no=$no+1;              
                      }
                    }else{
                        $cRet .="<tr>
                         <td width='5%' align='left'> &nbsp; </td>
                         <td width='20%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                         <td width='35%'  align='left'></td>
                         <td width='20%'  align='left'></td>
                        </tr>"; 
                    }

        $cRet .=       " </table>";           
        }/*end tanpa tanda tangan*/
                         
        



        } else{ /*tipe dokumen*/
if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u></td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai) nilai from trdskpd_ro WHERE left(kd_rek6,1)='4' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();
                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:10px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Realisasi Penerimaan Per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas4->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas4->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas4->mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas4->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas4->mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas4->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas4->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas4->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas4->sept,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas4->okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas4->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas4->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";
        } /*end else tipe dokumen*/




        $data['prev']= $cRet;
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

    function preview_rka_pembiayaan_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$status_anggaran1,$status_anggaran2){

       
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
        $sqlsclient=$this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc){
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
        }


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

                $rka="DOKUMEN PELAKSANAAN ANGGARAN";
                if($status_anggaran1=='nilai'){
                    $status_anggaran1="";
                }else if($status_anggaran1=='nilai_sempurna'){
                    $status_anggaran1="_sempurna";
                }else{
                    $status_anggaran1="_ubah";
                    $rka="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                }

                $rka="DOKUMEN PELAKSANAAN ANGGARAN";
                if($status_anggaran2=='nilai'){
                    $status_anggaran2="";
                }else if($status_anggaran2=='nilai_sempurna'){
                    $status_anggaran2="_sempurna";
                }else{
                    $status_anggaran2="_ubah";
                    $rka="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                }


        if($doc=='RKA'){
            $rka="RENCANA KERJA DAN ANGGARAN";
            $tambahan="";
        }else{
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tambahan=" <tr>
                            <td> No $doc</td>
                            <td>$nodpa</td>
                        </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr> 
                         <td width='80%' align='center'><strong>$rka <br> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td width='20%' rowspan='4' align='center'><strong> <br>$doc - PEMBIAYAAN SKPD</strong></td>
                    </tr>
                    <tr>
                         <td align='center'><strong>$kab <br>TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                    <tr>
                        <td colspan='2' align='center'><strong>RINCIAN ANGGARAN PEMBIAYAAN DAERAH</strong></td>
                    </tr> 
                    $tambahan                  
                    <tr>
                        <td> Organisasi</td>
                        <td>$kd_skpd - $nm_skpd</td>
                    </tr>
                    
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'>
                     <thead>                       
                        <tr>
                            <td bgcolor='#CCCCCC' rowspan='2' width='15%' align='center'><b>KODE REKENING</b></td>                            
                            <td bgcolor='#CCCCCC' rowspan='2' width='25%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>JUMLAH(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>BERTAMBAH/(BERKURANG)</b></td>
                        </tr>
                        <tr>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SEBELUM PERGESERAN</b></td>                            
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SESUDAH PERGESERAN</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>%</b></td>
                        </tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='25%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>3</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>4</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>5</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>6</td>
                        </tr>
                ";



                $sqltpm="SELECT isnull(SUM(nilai$status_anggaran1),0) AS totb, isnull(SUM(nilai$status_anggaran2),0) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,1)='6' and left(kd_skpd,20)=left('$id',20)";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                   $coba12=number_format($rowtpm->totb,"2",",",".");
                   $nilai2=number_format($rowtpm->totb2,"2",",",".");
                    $cobtpm=$rowtpm->totb;
                    $cobtpm2=$rowtpm->totb2;


                    if($cobtpm>0){
                        $selisih=$this->support->rp_minus($rowtpm->totb2-$rowtpm->totb);
                        $persen =$this->support->rp_minus((($rowtpm->totb2-$rowtpm->totb)/$rowtpm->totb)*100);

                    $cRet    .= " <tr>
                                    <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                    </tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>6</td>                                     
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >Pembiayaan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba12</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$nilai2</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                                    </tr>";
                        if($detail=='detail'){
                            $rincian="  UNION ALL "."
                                        SELECT '' header, a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT '' header, a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT '' header, a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 
                                union all
                                     select * from (
                                       SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,
                                    SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.ket_bl_teks=a.uraian
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id'  
                                     GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                    UNION ALL
                                    SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id'  
                                    GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                        
                                        UNION ALL
                                        SELECT a. header, a.kd_rek6 AS rek1,''AS rek,a.uraian AS nama, a.total$status_anggaran1 AS nilai, a.total$status_anggaran2 AS nilai2 FROM trdpo a  WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id' AND  (header='0' or header is null)
                                    ) okeii ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT '' header, a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT '' header, a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
    
                        ORDER BY kd_rek,header
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            if($coba9==''){
                                $coba10="<b>::</b> ".$rowpm->nm_rek;
                            }else{
                                $coba10=$rowpm->nm_rek;
                            }
                            if($rowpm->header==0 and $coba9==''){
                                $coba11= "";
                                $nilai2= "";
                                $selisih="";
                                $persen="";
                            }else{
                                $coba11= number_format($rowpm->nilai,"2",",",".");
                                $nilai2= number_format($rowpm->nilai2,"2",",",".");
                                $selisih= $this->support->rp_minus($rowpm->nilai2-$rowpm->nilai);
                                $persen=  $this->support->rp_minus((($rowpm->nilai2-$rowpm->nilai)/$rowpm->nilai)*100);
                            }
                            
                           
                             $cRet    .= " <tr>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$nilai2</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                                            </tr>";
                        } 

                        $kosong    = " <tr>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                     </tr>";
                        $sqltpm="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2  FROM trdrka WHERE LEFT(kd_rek6,2)='61' and left(kd_skpd,20)=left('$id',20)";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                            $coba12=number_format($rowtpm->totb,"2",",",".");
                                            $nilai2=number_format($rowtpm->totb2,"2",",",".");
                                            $cobtpm=$rowtpm->totb;
                                            $cobtpm2=$rowtpm->totb2;
                                            $selisih=$this->support->rp_minus($cobtpm2-$cobtpm);
                                            $persen =$this->support->rp_minus((($cobtpm2-$cobtpm)/$cobtpm)*100);
                                            $cRet    .= " $kosong 
                                                        <tr>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba12</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$nilai2</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                                                        </tr>$kosong";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL "."
                                        SELECT '' header,  a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT '' header,  a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT '' header,  a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 
                                    union all
                                     select * from (
                                       SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,
                                    SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.ket_bl_teks=a.uraian
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id'  
                                     GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                    UNION ALL
                                    SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id'  
                                    GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                        
                                        UNION ALL
                                        SELECT a. header, a.kd_rek6 AS rek1,''AS rek,a.uraian AS nama, a.total$status_anggaran1 AS nilai, a.total$status_anggaran2 AS nilai2 FROM trdpo a  WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id' AND  (header='0' or header is null)
                                    ) okeii ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT '' header,  a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT '' header,  a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek, header";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            if($coba9==''){
                                $coba10="<b>::</b> ".$rowpk->nm_rek;
                            }else{
                                $coba10=$rowpk->nm_rek;
                            }
                            if($rowpk->header==0 and $coba9==''){
                                $coba11= "";
                            }else{
                                $coba11= number_format($rowpk->nilai,"2",",",".");
                                $nilai2= number_format($rowpk->nilai2,"2",",",".");
                                $selisih=$this->support->rp_minus($rowpk->nilai2-$rowpk->nilai);

                                if($rowpk->nilai==0){
                                    $persen=0;
                                }else{
                                    $persen =$this->support->rp_minus((($rowpk->nilai2-$rowpk->nilai)/$rowpk->nilai)*100);                                    
                                }

                            }
                            
                            
                           
                             $cRet    .= " <tr>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                            </tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='62' and left(kd_skpd,20)=left('$id',20)";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                    $cobatpk=number_format($rowtpk->totb,"2",",",".");
                    $nilai2 =number_format($rowtpk->totb2,"2",",",".");
                    $selisih =$this->support->rp_minus($rowtpk->totb2-$rowtpk->totb);
                    $persen  =$this->support->rp_minus((($rowtpk->totb2-$rowtpk->totb)/$rowtpk->totb)*100);
                    $cobtpk=$rowtpk->totb;
                    $cobtpk2=$rowtpk->totb2;
                   
                    $cRet    .= "$kosong <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$cobatpk</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                </tr>$kosong";
                 }
                                                    
                          $netto=$this->support->rp_minus($cobtpm-$cobtpk);
                          $netto1=$this->support->rp_minus($cobtpm2-$cobtpk2);
                          $sel=($cobtpm2-$cobtpk2)-($cobtpm-$cobtpk);
                          $selisih=$this->support->rp_minus($sel);
                          $persen=$this->support->rp_minus(($sel/($cobtpm-$cobtpk))*100);

                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Pembiayaan Netto:</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$netto</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$netto1</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr>";
                                        

                    } /*end if pembiayaan 0*/

                } 
              
                $cRet    .= "</table>";
        if($doc=='RKA'){

        
        if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    

            $cRet.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td align='center'>
                            </td>
                            <td align='center'>
                                <br>$daerah, $tanggal_ttd <br>
                                Mengetahui, <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama<u><br>
                                $nip
                            <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                            Drs. ALFIAN, MM</u><br/>
                                            NIP. 196602101986031011
                        
                                </td>
                        </tr>
                    </table>";

        $cRet    .="<br><table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                         <td colspan='5' align='center'><strong>Tim Anggaran Pemerintah Daerah</strong> </td>
                         
                    </tr>
                    <tr>
                         <td width='10%' align='center'><strong>No</strong> </td>
                         <td width='30%'  align='center'><strong>Nama</strong></td>
                         <td width='20%'  align='center'><strong>NIP</strong></td>
                         <td width='20%'  align='center'><strong>Jabatan</strong></td>
                         <td width='20%'  align='center'><strong>Tanda Tangan</strong></td>
                    </tr>";
                    $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd order by no";
                    $sqltapd=$this->db->query($sqltim);
                    $no=1;
                    foreach ($sqltapd->result() as $rowtim)
                    {
                        $no=$no;                    
                        $nama= $rowtim->nama;
                        $nip= $rowtim->nip;
                        $jabatan  = $rowtim->jab;
                        $cRet .="<tr>
                                 <td width='5%' align='center'>$no </td>
                                 <td width='20%'  align='left'>$nama</td>
                                 <td width='20%'  align='left'>$nip</td>
                                 <td width='35%'  align='left'>$jabatan</td>
                                 <td width='20%'  align='left'></td>
                            </tr>"; 
                    $no=$no+1;              
                    }
                    
                    if($no<=4){ /*jika orangnya kurang dari 4 maka tambah kolom kosong*/
                        for ($i = $no; $i <= 4; $i++){
                            $cRet .="<tr>
                                         <td width='5%' align='center'>$i </td>
                                         <td width='20%'  align='left'>&nbsp; </td>
                                         <td width='20%'  align='left'>&nbsp; </td>
                                         <td width='35%'  align='left'>&nbsp; </td>
                                         <td width='20%'  align='left'></td>
                                    </tr>";     
                            }                                                   
                    } 

        $cRet    .= "</table>";                       
        }
    } else{ /*if else tipe dokumen*/

    


                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd, sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,2)='61' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd 
                                                 union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_anggaran2) nilai from trdskpd_ro WHERE left(kd_rek6,2)='62' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();

               
  if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama</u><br>
                                $nip
                                <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                            Drs. ALFIAN, MM</u><br/>
                                            NIP. 196602101986031011
                        
                                </td>";
              
        }else{
            $tambahan="";
        }

                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Penerimaan per
Bulan</td>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Pengeluaran per
Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas4->jan,'2',',','.')."</td> 
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas4->feb,'2',',','.')."</td> 
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas4->mar,'2',',','.')."</td> 
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas4->apr,'2',',','.')."</td> 
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas4->mei,'2',',','.')."</td> 
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas4->jun,'2',',','.')."</td> 
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas4->jul,'2',',','.')."</td> 
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas4->ags,'2',',','.')."</td> 
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas4->sept,'2',',','.')."</td> 
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas4->nov,'2',',','.')."</td> 
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas4->des,'2',',','.')."</td> 
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                        </table>";  
        } /*end tipe doc*/   
        $data['prev']= $cRet;    
        $judul         = 'RKA SKPD';
        switch($cetak) { 
        case 1;
             $this->support->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
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

    function preview_rka_pembiayaan_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$detail,$tanggal_ttd,$doc,$status_anggaran1,$status_anggaran2){

       
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
        $sqlsclient=$this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc){
                    $tgl=$rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
        }


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

                $rka="DOKUMEN PELAKSANAAN ANGGARAN";
                if($status_anggaran1=='nilaisempurna1'){
                    $status_anggaran1="sempurna1";
                }else if($status_anggaran1=='nilaisempurna2'){
                    $status_anggaran1="sempurna2";
                }else{
                    $status_anggaran1="_ubah";
                    $rka="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                }

                $rka="DOKUMEN PELAKSANAAN ANGGARAN";
                if($status_anggaran2=='nilaisempurna1'){
                    $status_anggaran2="sempurna1";
                }else if($status_anggaran2=='nilaisempurna2'){
                    $status_anggaran2="sempurna2";
                }else{
                    $status_anggaran2="_ubah";
                    $rka="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                }


        if($doc=='RKA'){
            $rka="RENCANA KERJA DAN ANGGARAN";
            $tambahan="";
        }else{
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tambahan=" <tr>
                            <td> No $doc</td>
                            <td>$nodpa</td>
                        </tr>";
        }
        $cRet='';
        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='0'>
                    <tr> 
                         <td width='80%' align='center'><strong>$rka <br> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                         <td width='20%' rowspan='4' align='center'><strong> <br>$doc - PEMBIAYAAN SKPD</strong></td>
                    </tr>
                    <tr>
                         <td align='center'><strong>$kab <br>TAHUN ANGGARAN $thn</strong> </td>
                    </tr>
                </table>";

        $cRet .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='left' border='1'>
                    <tr>
                        <td colspan='2' align='center'><strong>RINCIAN ANGGARAN PEMBIAYAAN DAERAH</strong></td>
                    </tr> 
                    $tambahan                  
                    <tr>
                        <td> Organisasi</td>
                        <td>$kd_skpd - $nm_skpd</td>
                    </tr>
                    
                </table>";
        $cRet .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='5'>
                     <thead>                       
                        <tr>
                            <td bgcolor='#CCCCCC' rowspan='2' width='15%' align='center'><b>KODE REKENING</b></td>                            
                            <td bgcolor='#CCCCCC' rowspan='2' width='25%' align='center'><b>URAIAN</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>JUMLAH(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' colspan='2' width='30%' align='center'><b>BERTAMBAH/(BERKURANG)</b></td>
                        </tr>
                        <tr>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SEBELUM PERGESERAN</b></td>                            
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>SESUDAH PERGESERAN</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>(Rp.)</b></td>
                            <td bgcolor='#CCCCCC' width='15%' align='center'><b>%</b></td>
                        </tr>
                     </thead>
                     
                        <tr>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>1</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='25%' align='center'>2</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>3</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>4</td>                            
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>5</td>
                            <td style='vertical-align:top;border-top: none;border-bottom: none;' width='15%' align='center'>6</td>
                        </tr>
                ";



                $sqltpm="SELECT isnull(SUM(nilai$status_anggaran1),0) AS totb, isnull(SUM(nilai$status_anggaran2),0) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,1)='6' and left(kd_skpd,20)=left('$id',20)";
                $sqltpm=$this->db->query($sqltpm);
                foreach ($sqltpm->result() as $rowtpm)
                {
                   $coba12=number_format($rowtpm->totb,"2",",",".");
                   $nilai2=number_format($rowtpm->totb2,"2",",",".");
                    $cobtpm=$rowtpm->totb;
                    $cobtpm2=$rowtpm->totb2;


                    if($cobtpm>0){
                        $selisih=$this->support->rp_minus($rowtpm->totb2-$rowtpm->totb);
                        $persen =$this->support->rp_minus((($rowtpm->totb2-$rowtpm->totb)/$rowtpm->totb)*100);

                    $cRet    .= " <tr>
                                    <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'></td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>&nbsp;</td>
                                    </tr>";

                        $cRet    .= "<tr>
                                        <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>6</td>                                     
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >Pembiayaan</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba12</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$nilai2</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                                         <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                                    </tr>";
                        if($detail=='detail'){
                            $rincian="  UNION ALL "."
                                        SELECT '' header, a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT '' header, a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT '' header, a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 
                                union all
                                     select * from (
                                       SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,
                                    SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.ket_bl_teks=a.uraian
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id'  
                                     GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                    UNION ALL
                                    SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id'  
                                    GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                        
                                        UNION ALL
                                        SELECT a. header, a.kd_rek6 AS rek1,''AS rek,a.uraian AS nama, a.total$status_anggaran1 AS nilai, a.total$status_anggaran2 AS nilai2 FROM trdpo a  WHERE left(a.kd_rek6,2)='61' and LEFT(a.no_trdrka,22)='$id' AND  (header='0' or header is null)
                                    ) okeii ";
                        }else{$rincian='';}

                        $sqlpm="
                        SELECT '' header, a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT '' header, a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND left(b.kd_skpd,20)=left('$id',20) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
    
                        ORDER BY kd_rek,header
                        ";
                 
                         $querypm = $this->db->query($sqlpm);
                         foreach ($querypm->result() as $rowpm)
                        {
                            $coba9=$this->support->dotrek($rowpm->rek);
                            if($coba9==''){
                                $coba10="<b>::</b> ".$rowpm->nm_rek;
                            }else{
                                $coba10=$rowpm->nm_rek;
                            }
                            if($rowpm->header==0 and $coba9==''){
                                $coba11= "";
                                $nilai2= "";
                                $selisih="";
                                $persen="";
                            }else{
                                $coba11= number_format($rowpm->nilai,"2",",",".");
                                $nilai2= number_format($rowpm->nilai2,"2",",",".");
                                $selisih= $this->support->rp_minus($rowpm->nilai2-$rowpm->nilai);
                                $persen=  $this->support->rp_minus((($rowpm->nilai2-$rowpm->nilai)/$rowpm->nilai)*100);
                            }
                            
                           
                             $cRet    .= " <tr>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$nilai2</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                                            </tr>";
                        } 

                        $kosong    = " <tr>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                            <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>&nbsp;</td>
                                     </tr>";
                        $sqltpm="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2  FROM trdrka WHERE LEFT(kd_rek6,2)='61' and left(kd_skpd,20)=left('$id',20)";
                                            $sqltpm=$this->db->query($sqltpm);
                                         foreach ($sqltpm->result() as $rowtpm)
                                        {
                                            $coba12=number_format($rowtpm->totb,"2",",",".");
                                            $nilai2=number_format($rowtpm->totb2,"2",",",".");
                                            $cobtpm=$rowtpm->totb;
                                            $cobtpm2=$rowtpm->totb2;
                                            $selisih=$this->support->rp_minus($cobtpm2-$cobtpm);
                                            $persen =$this->support->rp_minus((($cobtpm2-$cobtpm)/$cobtpm)*100);
                                            $cRet    .= " $kosong 
                                                        <tr>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='left'></td>                                     
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>Jumlah Penerimaan Pembiayaan</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$coba12</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$nilai2</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$selisih</td>
                                                             <td style='vertical-align:top;border-top: solid 1px black;border-bottom: none;'  align='right'>$persen</td>
                                                        </tr>$kosong";
                                         } 

                        if($detail=='detail'){
                            $rincian="  UNION ALL "."
                                        SELECT '' header,  a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek4 a 
                                        INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek6,(len(a.kd_rek4))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek4, a.nm_rek4 
                                        UNION ALL 
                                        SELECT '' header,  a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek5 a 
                                        INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek6,(len(a.kd_rek5))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek5, a.nm_rek5 
                                        UNION ALL 
                                        SELECT '' header,  a.kd_rek6 AS kd_rek,a.kd_rek6 AS rek,a.nm_rek6 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek6 a 
                                        INNER JOIN trdrka b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(b.kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                                        GROUP BY a.kd_rek6, a.nm_rek6 
                                    union all
                                     select * from (
                                       SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,
                                    SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.ket_bl_teks=a.uraian
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id'  
                                     GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                    UNION ALL
                                    SELECT * FROM (SELECT b.header,a.kd_rek6 AS rek1,''AS rek,b.uraian AS nama,SUM(a.total$status_anggaran1) AS nilai, SUM(a.total$status_anggaran2) AS nilai2 FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
                                    AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id'  
                                    GROUP BY a.kd_rek6,b.header, b.uraian)z WHERE header='1' 
                                        
                                        UNION ALL
                                        SELECT a. header, a.kd_rek6 AS rek1,''AS rek,a.uraian AS nama, a.total$status_anggaran1 AS nilai, a.total$status_anggaran2 AS nilai2 FROM trdpo a  WHERE left(a.kd_rek6,2)='62' and LEFT(a.no_trdrka,22)='$id' AND  (header='0' or header is null)
                                    ) okeii ";
                        }else{$rincian='';}

                        $sqlpk="
                        SELECT '' header,  a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek2 a 
                        INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) GROUP BY a.kd_rek2,a.nm_rek2 
                        UNION ALL 
                        SELECT '' header,  a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai$status_anggaran1) AS nilai, SUM(b.nilai$status_anggaran2) AS nilai2 FROM ms_rek3 a 
                        INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND left(b.kd_skpd,20)=left('$id',20) 
                        GROUP BY a.kd_rek3, a.nm_rek3 
                        $rincian
                        ORDER BY kd_rek, header";
                 
                         $querypk= $this->db->query($sqlpk);
                         foreach ($querypk->result() as $rowpk){
                            $coba9=$this->support->dotrek($rowpk->rek);
                            if($coba9==''){
                                $coba10="<b>::</b> ".$rowpk->nm_rek;
                            }else{
                                $coba10=$rowpk->nm_rek;
                            }
                            if($rowpk->header==0 and $coba9==''){
                                $coba11= "";
                            }else{
                                $coba11= number_format($rowpk->nilai,"2",",",".");
                                $nilai2= number_format($rowpk->nilai2,"2",",",".");
                                $selisih=$this->support->rp_minus($rowpk->nilai2-$rowpk->nilai);

                                if($rowpk->nilai==0){
                                    $persen=0;
                                }else{
                                    $persen =$this->support->rp_minus((($rowpk->nilai2-$rowpk->nilai)/$rowpk->nilai)*100);                                    
                                }

                            }
                            
                            
                           
                             $cRet    .= " <tr>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='left'>$coba9</td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;' >$coba10</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$coba11</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                            </tr>";
                        } 


                        $sqltpk="SELECT SUM(nilai$status_anggaran1) AS totb, SUM(nilai$status_anggaran2) AS totb2 FROM trdrka WHERE LEFT(kd_rek6,2)='62' and left(kd_skpd,20)=left('$id',20)";
                    $sqltpk=$this->db->query($sqltpk);
                 foreach ($sqltpk->result() as $rowtpk)
                {
                    $cobatpk=number_format($rowtpk->totb,"2",",",".");
                    $nilai2 =number_format($rowtpk->totb2,"2",",",".");
                    $selisih =$this->support->rp_minus($rowtpk->totb2-$rowtpk->totb);
                    $persen  =$this->support->rp_minus((($rowtpk->totb2-$rowtpk->totb)/$rowtpk->totb)*100);
                    $cobtpk=$rowtpk->totb;
                    $cobtpk2=$rowtpk->totb2;
                   
                    $cRet    .= "$kosong <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Jumlah Pengeluaran Pembiayaan</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$cobatpk</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$nilai2</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                     <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                </tr>$kosong";
                 }
                                                    
                          $netto=$this->support->rp_minus($cobtpm-$cobtpk);
                          $netto1=$this->support->rp_minus($cobtpm2-$cobtpk2);
                          $sel=($cobtpm2-$cobtpk2)-($cobtpm-$cobtpk);
                          $selisih=$this->support->rp_minus($sel);
                          $persen=$this->support->rp_minus(($sel/($cobtpm-$cobtpk))*100);

                             $cRet    .= " <tr><td style='vertical-align:top;border-top: solid 1px black;'  align='left'></td>                                     
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>Pembiayaan Netto:</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$netto</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$netto1</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$selisih</td>
                                             <td style='vertical-align:top;border-top: solid 1px black;'  align='right'>$persen</td>
                                             </tr>";
                                        

                    } /*end if pembiayaan 0*/

                } 
              
                $cRet    .= "</table>";
        if($doc=='RKA'){

        
        if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    

            $cRet.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td align='center'>
                            </td>
                            <td align='center'>
                                <br>$daerah, $tanggal_ttd <br>
                                Mengetahui, <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama<u><br>
                                $nip
                            <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                            Drs. ALFIAN, MM</u><br/>
                                            NIP. 196602101986031011
                        
                                </td>
                        </tr>
                    </table>";

        $cRet    .="<br><table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                    <tr>
                         <td colspan='5' align='center'><strong>Tim Anggaran Pemerintah Daerah</strong> </td>
                         
                    </tr>
                    <tr>
                         <td width='10%' align='center'><strong>No</strong> </td>
                         <td width='30%'  align='center'><strong>Nama</strong></td>
                         <td width='20%'  align='center'><strong>NIP</strong></td>
                         <td width='20%'  align='center'><strong>Jabatan</strong></td>
                         <td width='20%'  align='center'><strong>Tanda Tangan</strong></td>
                    </tr>";
                    $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd order by no";
                    $sqltapd=$this->db->query($sqltim);
                    $no=1;
                    foreach ($sqltapd->result() as $rowtim)
                    {
                        $no=$no;                    
                        $nama= $rowtim->nama;
                        $nip= $rowtim->nip;
                        $jabatan  = $rowtim->jab;
                        $cRet .="<tr>
                                 <td width='5%' align='center'>$no </td>
                                 <td width='20%'  align='left'>$nama</td>
                                 <td width='20%'  align='left'>$nip</td>
                                 <td width='35%'  align='left'>$jabatan</td>
                                 <td width='20%'  align='left'></td>
                            </tr>"; 
                    $no=$no+1;              
                    }
                    
                    if($no<=4){ /*jika orangnya kurang dari 4 maka tambah kolom kosong*/
                        for ($i = $no; $i <= 4; $i++){
                            $cRet .="<tr>
                                         <td width='5%' align='center'>$i </td>
                                         <td width='20%'  align='left'>&nbsp; </td>
                                         <td width='20%'  align='left'>&nbsp; </td>
                                         <td width='35%'  align='left'>&nbsp; </td>
                                         <td width='20%'  align='left'></td>
                                    </tr>";     
                            }                                                   
                    } 

        $cRet    .= "</table>";                       
        }
    } else{ /*if else tipe dokumen*/

    


                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd, sum(nilai_sempurna2) nilai from trdskpd_ro WHERE left(kd_rek6,2)='61' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd 
                                                 union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0")->row();
                $angkas4=$this->db->query(" 
                                                SELECT isnull(kd_skpd,'$id') kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai_sempurna2) nilai from trdskpd_ro WHERE left(kd_rek6,2)='62' GROUP BY bulan, left(kd_skpd,17)
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd
                                                union all 
                                                select '$id' kd_skpd, 0,0,0,0,0,0,0,0,0,0,0,0
                                                 ")->row();

               
  if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama</u><br>
                                $nip
                                <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                            Drs. ALFIAN, MM</u><br/>
                                            NIP. 196602101986031011
                        
                                </td>";
              
        }else{
            $tambahan="";
        }

                $cRet .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Penerimaan per
Bulan</td>
                                <td colspan='2' align='center' width='30%'>Rencana Realisasi Pengeluaran per
Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas4->jan,'2',',','.')."</td> 
                                <td width='8%'>Januari</td>
                                <td width='7%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas4->feb,'2',',','.')."</td> 
                                <td width='8%'>Februari</td>
                                <td width='7%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas4->mar,'2',',','.')."</td> 
                                <td width='8%'>Maret</td>
                                <td width='7%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas4->apr,'2',',','.')."</td> 
                                <td width='8%'>April</td>
                                <td width='7%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas4->mei,'2',',','.')."</td> 
                                <td width='8%'>Mei</td>
                                <td width='7%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas4->jun,'2',',','.')."</td> 
                                <td width='8%'>Juni</td>
                                <td width='7%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas4->jul,'2',',','.')."</td> 
                                <td width='8%'>Juli</td>
                                <td width='7%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas4->ags,'2',',','.')."</td> 
                                <td width='8%'>Agustus</td>
                                <td width='7%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas4->sept,'2',',','.')."</td> 
                                <td width='8%'>September</td>
                                <td width='7%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Oktober</td>
                                <td width='7%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas4->nov,'2',',','.')."</td> 
                                <td width='8%'>November</td>
                                <td width='7%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas4->des,'2',',','.')."</td> 
                                <td width='8%'>Desember</td>
                                <td width='7%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas4->des+$angkas4->nov+$angkas4->jan+$angkas4->feb+$angkas4->mar+$angkas4->apr+$angkas4->mei+$angkas4->jun+$angkas4->jul+$angkas4->ags+$angkas4->sept+$angkas4->okt,'2',',','.')."</td> 
                                <td width='8%'>Jumlah</td>
                                <td width='7%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                                 
                            </tr>
                        </table>";  
        } /*end tipe doc*/   
        $data['prev']= $cRet;    
        $judul         = 'RKA SKPD';
        switch($cetak) { 
        case 1;
             $this->support->_mpdf('',$cRet,10,10,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
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
//cetak belanja

function preview_belanja_pergeseran($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc,$status1,$status2){
        
    $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
    $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
             $sqlskpd=$this->db->query($sqldns);
             foreach ($sqlskpd->result() as $rowdns)
            {
                $kd_urusan= $rowdns->kd_u;                    
                $nm_urusan= $rowdns->nm_u;
                $kd_skpd  = $rowdns->kd_sk;
                $nm_skpd  = $rowdns->nm_sk;
                $header   = $rowdns->header;
                $kd_org   = $rowdns->kd_org;
            }
    $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
             $sqlsclient=$this->db->query($sqlsc);
             foreach ($sqlsclient->result() as $rowsc)
            {
               
                $tgl     = $rowsc->tgl_rka;
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
                $thn     = $rowsc->thn_ang;
            }

        $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
        $nama_tabel="Pergeseran";

        switch ($status1) {
            case 'nilai':
                $status_anggaran1="";
                $status_angkas1="";
                break;
            
            case 'nilai_sempurna':
                $status_anggaran1="2";
                $status_angkas1="_sempurna";
                break;

            case 'sempurna2':
                $status_anggaran1="sempurna2";
                $status_angkas1="sempurna2";
                break;

            case 'sempurna3':
                $status_anggaran1="sempurna3";
                $status_angkas1="sempurna3";
                break;

            case 'sempurna4':
                $status_anggaran1="sempurna4";
                $status_angkas1="sempurna4";
                break;

            case 'sempurna5':
                $status_anggaran1="sempurna5";
                $status_angkas1="sempurna5";
                break;
            case 'nilai_ubah':
                $status_anggaran1="3";
                $status_angkas1="_ubah";
                break;

            default:
                $status_anggaran1="3";
                $status_angkas1="_ubah";
                break;
        }

        switch ($status2) {
            case 'nilai':
                $status_anggaran2="";
                $status_angkas2="";
                break;
            
            case 'nilai_sempurna':
                $status_anggaran2="2";
                $status_angkas2="_sempurna";
                break;

            case 'sempurna2':
                $status_anggaran2="sempurna2";
                $status_angkas2="sempurna2";
                break;

            case 'sempurna3':
                $status_anggaran2="sempurna3";
                $status_angkas2="sempurna3";
                break;

            case 'sempurna4':
                $status_anggaran2="sempurna4";
                $status_angkas2="sempurna4";
                break;

            case 'sempurna5':
                $status_anggaran2="sempurna5";
                $status_angkas2="sempurna5";
                break;
            case 'nilai_ubah':
                $status_anggaran2="3";
                $status_angkas2="_ubah";
                $nama_tabel="Perubahan"; $doc="DPPA";
                $dokumen="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                break;

            default:
                $status_anggaran2="3";
                $status_angkas2="_ubah";
                $nama_tabel="Perubahan"; $doc="DPPA";
                $dokumen="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                break;
        }



    if($doc=='RKA'){
        $dokumen="RENCANA KERJA DAN ANGGARAN";
        $tabeldpa="";
    }else{
        
        $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
        $tabeldpa="<tr>
                    <td width='20%' align='left' style='border-right:none'> No $doc</td>
                    <td width='80%' align='left' style='border-left:none'>: $nodpa</td>
                </tr>";
    }

    $ctk ="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='5px'>
            <tr>
                <td width='80%' align='center'><b> $dokumen <br> SATUAN KERJA PERANGKAT DAERAH</td>
                <td rowspan='2' width='20%' align='center'><b>$doc - BELANJA SKPD</td>
            </tr>
            <tr>
                <td width='80%' align='center'><b> Kota $daerah <br> Tahun Anggaran $thn</td>
            </tr>
          </table>";

    $ctk .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='2'>
            $tabeldpa
            <tr>
                <td width='20%' align='left' style='border-right:none'> Organisasi</td>
                <td width='80%' align='left' style='border-left:none'>: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td width='100%' colspan='2' bgcolor='#cccccc' align='left'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan='2' align='center'><b>Rekapitulasi Anggaran Belanja Berdasarkan Program dan Kegiatan</td>
            </tr>
          </table>";

    $ctk .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
            <thead>
            <tr>
                <td align='center' colspan='5'><b>Kode</td>
                <td align='center' rowspan='3'><b>Uraian</td>
                <td align='center' rowspan='3'><b>Sumber Dana</td>
                <td align='center' rowspan='3'><b>Lokasi</td>
                <td align='center' colspan='7'><b>Sebelum $nama_tabel</td>
                <td align='center' colspan='7'><b>Setelah $nama_tabel</td>                 
            </tr>
            <tr>
                <td align='center' rowspan='2'><b>Urusan</td>
                <td align='center' rowspan='2'><b>Sub Urusan</td>
                <td align='center' rowspan='2'><b>Program</td>
                <td align='center' rowspan='2'><b>Kegiatan</td>
                <td align='center' rowspan='2'><b>Sub Kegiatan</td>
                <td align='center' rowspan='2'><b>T-1</td>
                <td align='center' colspan='5'><b>T</td>
                <td align='center' rowspan='2'><b>T+1</td>
                <td align='center' rowspan='2'><b>T-1</td>
                <td align='center' colspan='5'><b>T</td>
                <td align='center' rowspan='2'><b>T+1</td>
            </tr>
            <tr>
                <td align='center'><b>Blj. Operasi</td>
                <td align='center'><b>Blj. Modal</td>
                <td align='center'><b>Blj. Tak Terduga</td>
                <td align='center'><b>Blj. Transfer</td>
                <td align='center'><b>Jumlah</td>
                <td align='center'><b>Blj. Operasi</td>
                <td align='center'><b>Blj. Modal</td>
                <td align='center'><b>Blj. Tak Terduga</td>
                <td align='center'><b>Blj. Transfer</td>
                <td align='center'><b>Jumlah</td>
            </tr>
            <tr bgcolor='#cccccc'>
                <td align='center'><b>1</td>
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
                <td align='center'><b>16</td>
                <td align='center'><b>17</td>
                <td align='center'><b>18</td>
                <td align='center'><b>19</td>
                <td align='center'><b>20</td>
                <td align='center'><b>21</td>
                <td align='center'><b>22</td>
            </tr>
            </thead>
            <tr>
                <td colspan='22' bgcolor='#cccccc'>&nbsp;</td>
            </tr>";
        $tot51=0;               $tot51_2=0;
        $tot52=0;               $tot52_2=0;
        $tot53=0;               $tot53_2=0;
        $tot54=0;               $tot54_2=0;
        $total=0;               $total_2=0;



    $sumber="";
    $sql=$this->db->query("SELECT urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber, lokasi, sum(operasi$status_anggaran1) operasi, sum(modal$status_anggaran1) modal, sum(duga$status_anggaran1) duga, sum(trans$status_anggaran1) trans, sum(operasi$status_anggaran2) operasi2, sum(modal$status_anggaran2) modal2, sum(duga$status_anggaran2) duga2, sum(trans$status_anggaran2) trans2 from v_cetak_belanja where left(kd_skpd,17)=left('$id',17)
GROUP BY left(kd_skpd,17),urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber, lokasi, urut
ORDER BY urut
       ");
    foreach($sql->result() as $a){
        $urusan =$a->urusan;
        $bid_urusan =$a->bid_urusan;
        $program =$a->program;
        $giat =$a->kegiatan;
        $subgiat =$a->subgiat;
        $nama =$a->nama;
        $sumber =$a->sumber;
        $lokasi =$a->lokasi;
        $operasi =$a->operasi;
        $modal =$a->modal;
        $terduga =$a->duga;
        $transfer =$a->trans;
        $operasi2 =$a->operasi2;
        $modal2 =$a->modal2;
        $terduga2 =$a->duga2;
        $transfer2 =$a->trans2;
        $Jumlah=$operasi+$modal+$terduga+$transfer;
        $Jumlah2=$operasi2+$modal2+$terduga2+$transfer2;
        if($subgiat!=''){
            $tot51=0+$tot51+$operasi;
            $tot52=0+$tot52+$modal;
            $tot53=0+$tot53+$terduga;
            $tot54=0+$tot54+$transfer;
            $total=0+$total+$Jumlah;  

            $tot51_2=0+$tot51_2+$operasi2;
            $tot52_2=0+$tot52_2+$modal2;
            $tot53_2=0+$tot53_2+$terduga2;
            $tot54_2=0+$tot54_2+$transfer2;
            $total_2=0+$total_2+$Jumlah2;                
        }


    $ctk .="<tr>
                <td align='center'>$urusan</td>
                <td align='center'>$bid_urusan</td>
                <td align='center'>$program</td>
                <td align='center'>$giat</td>
                <td align='center'>$subgiat</td>
                <td align='left'>$nama</td>
                <td align='left'>$sumber</td>
                <td align='left'>$lokasi</td>
                <td align='left'></td>
                <td align='right'>&nbsp;".number_format($operasi,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($modal,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($terduga,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($transfer,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($Jumlah,0,',','.')."</td>
                <td align='left'></td>
                <td align='left'></td>
                <td align='right'>&nbsp;".number_format($operasi2,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($modal2,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($terduga2,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($transfer2,0,',','.')."</td>
                <td align='right'>&nbsp;".number_format($Jumlah2,0,',','.')."</td>
                <td align='left'></td>
            </tr>";
    }
    $ctk .="<tr>
                <td align='right' colspan='8'> &nbsp; TOTAL &nbsp;</td>
                <td align='left'></td>
                <td align='right'>".number_format($tot51,0,',','.')."</td>
                <td align='right'>".number_format($tot52,0,',','.')."</td>
                <td align='right'>".number_format($tot53,0,',','.')."</td>
                <td align='right'>".number_format($tot54,0,',','.')."</td>
                <td align='right'>".number_format($total,0,',','.')."</td>
                <td align='left'></td>
                <td align='left'></td>
                <td align='right'>".number_format($tot51_2,0,',','.')."</td>
                <td align='right'>".number_format($tot52_2,0,',','.')."</td>
                <td align='right'>".number_format($tot53_2,0,',','.')."</td>
                <td align='right'>".number_format($tot54_2,0,',','.')."</td>
                <td align='right'>".number_format($total_2,0,',','.')."</td>
                <td align='left'></td>
            </tr>";
        $ctk .=  "</table>";
   
if($ttd1!='tanpa'){
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' ";
        $sqlttd=$this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd){
                    $nip=$rowttd->nip;  
                    $pangkat=$rowttd->pangkat;  
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
        }
                
        $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                            $jabatan 
                            <br><br>
                            <br><br>
                            <br><br>
                            <b>$nama</b><br>
                            <u>$nip</u></td>";
          
    }else{
        $tambahan="";
    }
            $angkas5=$this->db->query("SELECT  kd_skpd, 
                                            isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                            isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                            isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                            isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                            isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                            isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                            isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                            isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                            isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                            isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                            isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                            isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                            select bulan, left(kd_skpd,17)+'.0000' kd_skpd , sum(nilai$status_angkas2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, left(kd_skpd,17)
                                            ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();

            $ctk .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                        <tr>
                            <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                            $tambahan
                        </tr>
                        <tr>
                            <td width='30%'>Januari</td>
                            <td width='30%' align='right'>".number_format($angkas5->jan,0,',','.')."</td>                                
                        </tr>
                        <tr>
                            <td width='30%'>Februari</td>
                            <td width='30%' align='right'>".number_format($angkas5->feb,0,',','.')."</td>                                 
                        </tr>
                        <tr>
                            <td width='30%'>Maret</td>
                            <td width='30%' align='right'>".number_format($angkas5->mar,0,',','.')."</td>                              
                        </tr>
                        <tr>
                            <td width='30%'>April</td>
                            <td width='30%' align='right'>".number_format($angkas5->apr,0,',','.')."</td>                                
                        </tr>
                        <tr>
                            <td width='30%'>Mei</td>
                            <td width='30%' align='right'>".number_format($angkas5->mei,0,',','.')."</td>                            
                        </tr>
                        <tr>
                            <td width='30%'>Juni</td>
                            <td width='30%' align='right'>".number_format($angkas5->jun,0,',','.')."</td>                                 
                        </tr>
                        <tr>
                            <td width='30%'>Juli</td>
                            <td width='30%' align='right'>".number_format($angkas5->jul,0,',','.')."</td>                                 
                        </tr>
                        <tr>
                            <td width='30%'>Agustus</td>
                            <td width='30%' align='right'>".number_format($angkas5->ags,0,',','.')."</td>                                 
                        </tr>
                        <tr>
                            <td width='30%'>September</td>
                            <td width='30%' align='right'>".number_format($angkas5->sept,0,',','.')."</td>                                  
                        </tr>
                        <tr>
                            <td width='30%'>Oktober</td>
                            <td width='30%' align='right'>".number_format($angkas5->okt,0,',','.')."</td>                                  
                        </tr>
                        <tr>
                            <td width='30%'>November</td>
                            <td width='30%' align='right'>".number_format($angkas5->nov,0,',','.')."</td>                                 
                        </tr>
                        <tr>
                            <td width='30%'>Desember</td>
                            <td width='30%' align='right'>".number_format($angkas5->des,0,',','.')."</td>                                 
                        </tr>
                        <tr>
                            <td width='30%' align='right'>Jumlah</td>
                            <td width='30%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,0,',','.')."</td>                               
                        </tr>

                    </table>";





    switch($cetak) { 
    case 1;
         $this->master_pdf->_mpdf('',$ctk,10,10,10,'1');
    break;
    case 2;        
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename= $judul.xls");
        echo($ctk);
    break;
    case 3;     
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Content-Type: application/vnd.ms-word");
        header("Content-Disposition: attachment; filename= $judul.doc");
        echo($ctk);
    break;
    case 0;
    echo ("<title>RKA SKPD</title>");
    echo($ctk);
    break;
    }     
}


    function preview_belanja_pergeseran2($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc,$status1,$status2){
        
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan= $rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header   = $rowdns->header;
                    $kd_org   = $rowdns->kd_org;
                }
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl     = $rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }

            $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
            $nama_tabel="Pergeseran";

            switch ($status1) {
                case 'nilai':
                    $status_anggaran1="";
                    $status_angkas1="";
                    break;
                
                case 'nilai_sempurna':
                    $status_anggaran1="2";
                    $status_angkas1="_sempurna";
                    break;

                case 'sempurna2':
                    $status_anggaran1="sempurna2";
                    $status_angkas1="sempurna2";
                    break;

                case 'sempurna3':
                    $status_anggaran1="sempurna3";
                    $status_angkas1="sempurna3";
                    break;

                case 'sempurna4':
                    $status_anggaran1="sempurna4";
                    $status_angkas1="sempurna4";
                    break;

                case 'sempurna5':
                    $status_anggaran1="sempurna5";
                    $status_angkas1="sempurna5";
                    break;
                case 'nilai_ubah':
                    $status_anggaran1="3";
                    $status_angkas1="_ubah";
                    break;

                default:
                    $status_anggaran1="3";
                    $status_angkas1="_ubah";
                    break;
            }

            switch ($status2) {
                case 'nilai':
                    $status_anggaran2="";
                    $status_angkas2="";
                    break;
                
                case 'nilai_sempurna':
                    $status_anggaran2="2";
                    $status_angkas2="_sempurna";
                    break;

                case 'sempurna2':
                    $status_anggaran2="sempurna2";
                    $status_angkas2="sempurna2";
                    break;

                case 'sempurna3':
                    $status_anggaran2="sempurna3";
                    $status_angkas2="sempurna3";
                    break;

                case 'sempurna4':
                    $status_anggaran2="sempurna4";
                    $status_angkas2="sempurna4";
                    break;

                case 'sempurna5':
                    $status_anggaran2="sempurna5";
                    $status_angkas2="sempurna5";
                    break;
                case 'nilai_ubah':
                    $status_anggaran2="3";
                    $status_angkas2="_ubah";
                    $nama_tabel="Perubahan"; $doc="DPPA";
                    $dokumen="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                    break;

                default:
                    $status_anggaran2="3";
                    $status_angkas2="_ubah";
                    $nama_tabel="Perubahan"; $doc="DPPA";
                    $dokumen="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
                    break;
            }

        if($doc=='RKA'){
            $dokumen="RENCANA KERJA DAN ANGGARAN";
            $tabeldpa="";
        }else{
            
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tabeldpa="<tr>
                        <td width='20%' align='left' style='border-right:none'> No $doc</td>
                        <td width='80%' align='left' style='border-left:none'>: $nodpa</td>
                    </tr>";
        }

        $ctk ="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='5px'>
                <tr>
                    <td width='80%' align='center'><b> $dokumen <br> SATUAN KERJA PERANGKAT DAERAH</td>
                    <td rowspan='2' width='20%' align='center'><b>$doc - BELANJA SKPD</td>
                </tr>
                <tr>
                    <td width='80%' align='center'><b> PEMERINTAH PROVINSI KALIMANTAN BARAT <br> TAHUN ANGGARAN $thn</td>
                </tr>
              </table>";

        $ctk .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='2'>
                $tabeldpa
                <tr>
                    <td width='20%' align='left' style='border-right:none'> Organisasi</td>
                    <td width='80%' align='left' style='border-left:none'>: $kd_skpd - $nm_skpd</td>
                </tr>
                <tr>
                    <td width='100%' colspan='2' bgcolor='#cccccc' align='left'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='2' align='center'><b>Rekapitulasi Anggaran Belanja Berdasarkan Program dan Kegiatan</td>
                </tr>
              </table>";

        $ctk .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                <thead>
                <tr>
                    <td align='center' colspan='5'><b>Kode</td>
                    <td align='center' rowspan='3'><b>Uraian</td>
                    <td align='center' rowspan='3'><b>Sumber Dana</td>
                    <td align='center' rowspan='3'><b>Lokasi</td>
                    <td align='center' colspan='7'><b>Sebelum $nama_tabel</td>
                    <td align='center' colspan='7'><b>Setelah $nama_tabel</td> 
                    <td align='center' rowspan='3'><b>BERTAMBAH/BERKURANG</td>   
                                  
                </tr>
                <tr>
                    <td align='center' rowspan='2'><b>Urusan</td>
                    <td align='center' rowspan='2'><b>Sub Urusan</td>
                    <td align='center' rowspan='2'><b>Program</td>
                    <td align='center' rowspan='2'><b>Kegiatan</td>
                    <td align='center' rowspan='2'><b>Sub Kegiatan</td>
                    <td align='center' rowspan='2'><b>T-1</td>
                    <td align='center' colspan='5'><b>T</td>
                    <td align='center' rowspan='2'><b>T+1</td>
                    <td align='center' rowspan='2'><b>T-1</td>
                    <td align='center' colspan='5'><b>T</td>
                    <td align='center' rowspan='2'><b>T+1</td>
                </tr>
                <tr>
                    <td align='center'><b>Blj. Operasi</td>
                    <td align='center'><b>Blj. Modal</td>
                    <td align='center'><b>Blj. Tak Terduga</td>
                    <td align='center'><b>Blj. Transfer</td>
                    <td align='center'><b>Jumlah</td>
                    <td align='center'><b>Blj. Operasi</td>
                    <td align='center'><b>Blj. Modal</td>
                    <td align='center'><b>Blj. Tak Terduga</td>
                    <td align='center'><b>Blj. Transfer</td>
                    <td align='center'><b>Jumlah</td>
                </tr>
                <tr bgcolor='#cccccc'>
                    <td align='center'><b>1</td>
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
                    <td align='center'><b>16</td>
                    <td align='center'><b>17</td>
                    <td align='center'><b>18</td>
                    <td align='center'><b>19</td>
                    <td align='center'><b>20</td>
                    <td align='center'><b>21</td>
                    <td align='center'><b>22</td>
                    <td align='center'><b>23</td>
                </tr>
                </thead>
                <tr>
                    <td colspan='23' bgcolor='#cccccc'>&nbsp;</td>
                </tr>";
            $tot51=0;               $tot51_2=0;
            $tot52=0;               $tot52_2=0;
            $tot53=0;               $tot53_2=0;
            $tot54=0;               $tot54_2=0;
            $total=0;               $total_2=0;



        $sumber="";
        $sql=$this->db->query("SELECT urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber,nmsumber1,nmsumber2, lokasi, sum(operasi$status_anggaran1) operasi, sum(modal$status_anggaran1) modal, sum(duga$status_anggaran1) duga, sum(trans$status_anggaran1) trans, sum(operasi$status_anggaran2) operasi2, sum(modal$status_anggaran2) modal2, sum(duga$status_anggaran2) duga2, sum(trans$status_anggaran2) trans2 from v_cetak_belanja2 where left(kd_skpd,22)=left('$id',22)
GROUP BY left(kd_skpd,22),urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber,nmsumber1,nmsumber2, lokasi, urut
 ORDER BY urut
           ");
        foreach($sql->result() as $a){
            $urusan =$a->urusan;
            $bid_urusan =$a->bid_urusan;
            $program =$a->program;
            $giat =$a->kegiatan;
            $subgiat =$a->subgiat;
            $nama =$a->nama;
            $sumber =$a->sumber;
            $nmsumber1 =$a->nmsumber1;
            $nmsumber2 =$a->nmsumber2;
            $lokasi =$a->lokasi;
            $operasi =$a->operasi;
            $modal =$a->modal;
            $terduga =$a->duga;
            $transfer =$a->trans;
            $operasi2 =$a->operasi2;
            $modal2 =$a->modal2;
            $terduga2 =$a->duga2;
            $transfer2 =$a->trans2;
            $Jumlah=$operasi+$modal+$terduga+$transfer;
            $Jumlah2=$operasi2+$modal2+$terduga2+$transfer2;


            $selisih=$this->support->rp_minus($Jumlah2-$Jumlah);


            if($subgiat!=''){
                $tot51=0+$tot51+$operasi;
                $tot52=0+$tot52+$modal;
                $tot53=0+$tot53+$terduga;
                $tot54=0+$tot54+$transfer;
                $total=0+$total+$Jumlah;  

                $tot51_2=0+$tot51_2+$operasi2;
                $tot52_2=0+$tot52_2+$modal2;
                $tot53_2=0+$tot53_2+$terduga2;
                $tot54_2=0+$tot54_2+$transfer2;
                $total_2=0+$total_2+$Jumlah2; 
                $selisihtot=$this->support->rp_minus($total_2-$total);
            }


        $ctk .="<tr>
                    <td align='center'>$urusan</td>
                    <td align='center'>$bid_urusan</td>
                    <td align='center'>$program</td>
                    <td align='center'>$giat</td>
                    <td align='center'>$subgiat</td>
                    <td align='left'>$nama</td>
                    <td align='left'>$nmsumber1 <br>$nmsumber2</td>
                    <td align='left'>$lokasi</td>
                    <td align='left'></td>
                    <td align='right'>&nbsp;".number_format($operasi,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($modal,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($terduga,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($transfer,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($Jumlah,2,',','.')."</td>
                    <td align='left'></td>
                    <td align='left'></td>
                    <td align='right'>&nbsp;".number_format($operasi2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($modal2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($terduga2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($transfer2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($Jumlah2,2,',','.')."</td>
                    <td align='left'></td>
                    <td align='right'>&nbsp;$selisih</td>
                </tr>";
        }
        $ctk .="<tr>
                    <td align='right' colspan='8'> &nbsp; TOTAL &nbsp;</td>
                    <td align='left'></td>
                    <td align='right'>".number_format($tot51,2,',','.')."</td>
                    <td align='right'>".number_format($tot52,2,',','.')."</td>
                    <td align='right'>".number_format($tot53,2,',','.')."</td>
                    <td align='right'>".number_format($tot54,2,',','.')."</td>
                    <td align='right'>".number_format($total,2,',','.')."</td>
                    <td align='left'></td>
                    <td align='left'></td>
                    <td align='right'>".number_format($tot51_2,2,',','.')."</td>
                    <td align='right'>".number_format($tot52_2,2,',','.')."</td>
                    <td align='right'>".number_format($tot53_2,2,',','.')."</td>
                    <td align='right'>".number_format($tot54_2,2,',','.')."</td>
                    <td align='right'>".number_format($total_2,2,',','.')."</td>
                    <td align='left'></td>
                    <td align='right'>$selisihtot</td>
                </tr>";
            $ctk .=  "</table>";
       
if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <u>$nama</u><br>
                                $nip
                                <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/><u>
                                            Drs. ALFIAN, MM</u><br/>
                                            NIP. 196602101986031011
                        
                                </td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd, sum(nilai_sempurna2) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan,kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();

                $ctk .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%' align='right'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";





        switch($cetak) { 
        case 1;
             $this->support->_mpdf('',$ctk,10,10,10,'1');
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
        echo($ctk);
        break;
        }     
    }

    function preview_belanja_pergeseran_lamaa($tgl_ttd,$ttd1,$ttd2,$id,$cetak,$doc,$status1,$status2){
        
        $tanggal_ttd = $this->support->tanggal_format_indonesia($tgl_ttd);
        $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,20) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan= $rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $header   = $rowdns->header;
                    $kd_org   = $rowdns->kd_org;
                }
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl     = $rowsc->tgl_rka;
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }

            $dokumen="DOKUMEN PELAKSANAAN ANGGARAN";
            $nama_tabel="Pergeseran";

            if($status1=='nilai'){
                $status_anggaran1="";
                $status_angkas1="";
            }else if($status1=='nilai_sempurna'){
                $status_anggaran1="2";
                $status_angkas1="_sempurna";
            }else {
                $status_anggaran1="3";
                $status_angkas1="_ubah";
            }

            if($status2=='nilai'){
                $status_anggaran2="";
                $status_angkas2="";
            }else if($status2=='nilai_sempurna'){
                $status_anggaran2="2";
                $status_angkas2="_sempurna";
            }else {
                $status_anggaran2="3";
                $status_angkas2="_ubah";
                $nama_tabel="Perubahan";
                $dokumen="DOKUMEN PELAKSANAAN PERUBAHAN ANGGARAN";
            }

        if($doc=='RKA'){
            $dokumen="RENCANA KERJA DAN ANGGARAN";
            $tabeldpa="";
        }else{
            
            $nodpa=$this->db->query("SELECT * from trhrka where kd_skpd='$id'")->row()->no_dpa;
            $tabeldpa="<tr>
                        <td width='20%' align='left' style='border-right:none'> No $doc</td>
                        <td width='80%' align='left' style='border-left:none'>: $nodpa</td>
                    </tr>";
        }

        $ctk ="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='5px'>
                <tr>
                    <td width='80%' align='center'><b> $dokumen <br> SATUAN KERJA PERANGKAT DAERAH</td>
                    <td rowspan='2' width='20%' align='center'><b>$doc - BELANJA SKPD</td>
                </tr>
                <tr>
                    <td width='80%' align='center'><b> Kota $daerah <br> Tahun Anggaran $thn</td>
                </tr>
              </table>";

        $ctk .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='2' cellpadding='2'>
                $tabeldpa
                <tr>
                    <td width='20%' align='left' style='border-right:none'> Organisasi</td>
                    <td width='80%' align='left' style='border-left:none'>: $kd_skpd - $nm_skpd</td>
                </tr>
                <tr>
                    <td width='100%' colspan='2' bgcolor='#cccccc' align='left'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='2' align='center'><b>Rekapitulasi Anggaran Belanja Berdasarkan Program dan Kegiatan</td>
                </tr>
              </table>";

        $ctk .="<table style='border-collapse:collapse;font-size:10px' width='100%' align='center' border='1' cellspacing='0' cellpadding='4'>
                <thead>
                <tr>
                    <td align='center' colspan='5'><b>Kode</td>
                    <td align='center' rowspan='3'><b>Uraian</td>
                    <td align='center' rowspan='3'><b>Sumber Dana</td>
                    <td align='center' rowspan='3'><b>Lokasi</td>
                    <td align='center' colspan='7'><b>Sebelum $nama_tabel</td>
                    <td align='center' colspan='7'><b>Setelah $nama_tabel</td>                 
                </tr>
                <tr>
                    <td align='center' rowspan='2'><b>Urusan</td>
                    <td align='center' rowspan='2'><b>Sub Urusan</td>
                    <td align='center' rowspan='2'><b>Program</td>
                    <td align='center' rowspan='2'><b>Kegiatan</td>
                    <td align='center' rowspan='2'><b>Sub Kegiatan</td>
                    <td align='center' rowspan='2'><b>T-1</td>
                    <td align='center' colspan='5'><b>T</td>
                    <td align='center' rowspan='2'><b>T+1</td>
                    <td align='center' rowspan='2'><b>T-1</td>
                    <td align='center' colspan='5'><b>T</td>
                    <td align='center' rowspan='2'><b>T+1</td>
                </tr>
                <tr>
                    <td align='center'><b>Blj. Operasi</td>
                    <td align='center'><b>Blj. Modal</td>
                    <td align='center'><b>Blj. Tak Terduga</td>
                    <td align='center'><b>Blj. Transfer</td>
                    <td align='center'><b>Jumlah</td>
                    <td align='center'><b>Blj. Operasi</td>
                    <td align='center'><b>Blj. Modal</td>
                    <td align='center'><b>Blj. Tak Terduga</td>
                    <td align='center'><b>Blj. Transfer</td>
                    <td align='center'><b>Jumlah</td>
                </tr>
                <tr bgcolor='#cccccc'>
                    <td align='center'><b>1</td>
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
                    <td align='center'><b>16</td>
                    <td align='center'><b>17</td>
                    <td align='center'><b>18</td>
                    <td align='center'><b>19</td>
                    <td align='center'><b>20</td>
                    <td align='center'><b>21</td>
                    <td align='center'><b>22</td>
                </tr>
                </thead>
                <tr>
                    <td colspan='22' bgcolor='#cccccc'>&nbsp;</td>
                </tr>";
            $tot51=0;               $tot51_2=0;
            $tot52=0;               $tot52_2=0;
            $tot53=0;               $tot53_2=0;
            $tot54=0;               $tot54_2=0;
            $total=0;               $total_2=0;



        $sumber="";
        $sql=$this->db->query("SELECT urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber,nmsumber1,nmsumber2, lokasi, sum(operasi$status_anggaran1) operasi, sum(modal$status_anggaran1) modal, sum(duga$status_anggaran1) duga, sum(trans$status_anggaran1) trans, sum(operasi$status_anggaran2) operasi2, sum(modal$status_anggaran2) modal2, sum(duga$status_anggaran2) duga2, sum(trans$status_anggaran2) trans2 from v_cetak_belanja where left(kd_skpd,22)=left('$id',22)
GROUP BY left(kd_skpd,22),urusan, bid_urusan, program, kegiatan, subgiat, nama, sumber,nmsumber1,nmsumber2, lokasi, urut
 ORDER BY urut
           ");
        foreach($sql->result() as $a){
            $urusan =$a->urusan;
            $bid_urusan =$a->bid_urusan;
            $program =$a->program;
            $giat =$a->kegiatan;
            $subgiat =$a->subgiat;
            $nama =$a->nama;
            $sumber =$a->sumber;
            $nmsumber1 =$a->nmsumber1;
            $nmsumber2 =$a->nmsumber2;
            $lokasi =$a->lokasi;
            $operasi =$a->operasi;
            $modal =$a->modal;
            $terduga =$a->duga;
            $transfer =$a->trans;
            $operasi2 =$a->operasi2;
            $modal2 =$a->modal2;
            $terduga2 =$a->duga2;
            $transfer2 =$a->trans2;
            $Jumlah=$operasi+$modal+$terduga+$transfer;
            $Jumlah2=$operasi2+$modal2+$terduga2+$transfer2;
            if($subgiat!=''){
                $tot51=0+$tot51+$operasi;
                $tot52=0+$tot52+$modal;
                $tot53=0+$tot53+$terduga;
                $tot54=0+$tot54+$transfer;
                $total=0+$total+$Jumlah;  

                $tot51_2=0+$tot51_2+$operasi2;
                $tot52_2=0+$tot52_2+$modal2;
                $tot53_2=0+$tot53_2+$terduga2;
                $tot54_2=0+$tot54_2+$transfer2;
                $total_2=0+$total_2+$Jumlah2;                
            }


        $ctk .="<tr>
                    <td align='center'>$urusan</td>
                    <td align='center'>$bid_urusan</td>
                    <td align='center'>$program</td>
                    <td align='center'>$giat</td>
                    <td align='center'>$subgiat</td>
                    <td align='left'>$nama</td>
                    <td align='left'>$nmsumber1 <br>$nmsumber2</td>
                    <td align='left'>$lokasi</td>
                    <td align='left'></td>
                    <td align='right'>&nbsp;".number_format($operasi,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($modal,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($terduga,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($transfer,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($Jumlah,2,',','.')."</td>
                    <td align='left'></td>
                    <td align='left'></td>
                    <td align='right'>&nbsp;".number_format($operasi2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($modal2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($terduga2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($transfer2,2,',','.')."</td>
                    <td align='right'>&nbsp;".number_format($Jumlah2,2,',','.')."</td>
                    <td align='left'></td>
                </tr>";
        }
        $ctk .="<tr>
                    <td align='right' colspan='8'> &nbsp; TOTAL &nbsp;</td>
                    <td align='left'></td>
                    <td align='right'>".number_format($tot51,2,',','.')."</td>
                    <td align='right'>".number_format($tot52,2,',','.')."</td>
                    <td align='right'>".number_format($tot53,2,',','.')."</td>
                    <td align='right'>".number_format($tot54,2,',','.')."</td>
                    <td align='right'>".number_format($total,2,',','.')."</td>
                    <td align='left'></td>
                    <td align='left'></td>
                    <td align='right'>".number_format($tot51_2,2,',','.')."</td>
                    <td align='right'>".number_format($tot52_2,2,',','.')."</td>
                    <td align='right'>".number_format($tot53_2,2,',','.')."</td>
                    <td align='right'>".number_format($tot54_2,2,',','.')."</td>
                    <td align='right'>".number_format($total_2,2,',','.')."</td>
                    <td align='left'></td>
                </tr>";
            $ctk .=  "</table>";
       
if($ttd1!='tanpa'){
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  id_ttd='$ttd1' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
                    
            $tambahan="<td rowspan='14' align='center' width='40%'>                                <br>$daerah, $tanggal_ttd <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                                <u>$nip</u></td>";
              
        }else{
            $tambahan="";
        }
                $angkas5=$this->db->query("SELECT  kd_skpd, 
                                                isnull(sum(case WHEN bulan=1 then nilai else 0 end ),0) as jan,
                                                isnull(sum(case WHEN bulan=2 then nilai else 0 end ),0) as feb,
                                                isnull(sum(case WHEN bulan=3 then nilai else 0 end ),0) as mar,
                                                isnull(sum(case WHEN bulan=4 then nilai else 0 end ),0) as apr,
                                                isnull(sum(case WHEN bulan=5 then nilai else 0 end ),0) as mei,
                                                isnull(sum(case WHEN bulan=6 then nilai else 0 end ),0) as jun,
                                                isnull(sum(case WHEN bulan=7 then nilai else 0 end ),0) as jul,
                                                isnull(sum(case WHEN bulan=8 then nilai else 0 end ),0) as ags,
                                                isnull(sum(case WHEN bulan=9 then nilai else 0 end ),0) as sept,
                                                isnull(sum(case WHEN bulan=10 then nilai else 0 end ),0) as okt,
                                                isnull(sum(case WHEN bulan=11 then nilai else 0 end ),0) as nov,
                                                isnull(sum(case WHEN bulan=12 then nilai else 0 end ),0) as des from (
                                                select bulan, kd_skpd kd_skpd , sum(nilai_sempurna15) nilai from trdskpd_ro WHERE left(kd_rek6,1)='5' GROUP BY bulan, kd_skpd
                                                ) okey where kd_skpd='$id' GROUP BY kd_skpd ")->row();

                $ctk .="<table border='1' width='100%' cellpadding='5' cellspacing='5' style='border-collapse: collapse; font-size:12px'>
                            <tr>
                                <td colspan='2' align='center' width='60%'>Rencana Penarikan Dana per Bulan</td>
                                $tambahan
                            </tr>
                            <tr>
                                <td width='30%'>Januari</td>
                                <td width='30%' align='right'>".number_format($angkas5->jan,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Februari</td>
                                <td width='30%' align='right'>".number_format($angkas5->feb,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Maret</td>
                                <td width='30%' align='right'>".number_format($angkas5->mar,'2',',','.')."</td>                              
                            </tr>
                            <tr>
                                <td width='30%'>April</td>
                                <td width='30%' align='right'>".number_format($angkas5->apr,'2',',','.')."</td>                                
                            </tr>
                            <tr>
                                <td width='30%'>Mei</td>
                                <td width='30%' align='right'>".number_format($angkas5->mei,'2',',','.')."</td>                            
                            </tr>
                            <tr>
                                <td width='30%'>Juni</td>
                                <td width='30%' align='right'>".number_format($angkas5->jun,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Juli</td>
                                <td width='30%' align='right'>".number_format($angkas5->jul,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Agustus</td>
                                <td width='30%' align='right'>".number_format($angkas5->ags,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>September</td>
                                <td width='30%' align='right'>".number_format($angkas5->sept,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>Oktober</td>
                                <td width='30%' align='right'>".number_format($angkas5->okt,'2',',','.')."</td>                                  
                            </tr>
                            <tr>
                                <td width='30%'>November</td>
                                <td width='30%' align='right'>".number_format($angkas5->nov,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%'>Desember</td>
                                <td width='30%' align='right'>".number_format($angkas5->des,'2',',','.')."</td>                                 
                            </tr>
                            <tr>
                                <td width='30%' align='right'>Jumlah</td>
                                <td width='30%' align='right'>".number_format($angkas5->des+$angkas5->nov+$angkas5->jan+$angkas5->feb+$angkas5->mar+$angkas5->apr+$angkas5->mei+$angkas5->jun+$angkas5->jul+$angkas5->ags+$angkas5->sept+$angkas5->okt,'2',',','.')."</td>                               
                            </tr>
                        </table>";





        switch($cetak) { 
        case 1;
             $this->support->_mpdf('',$ctk,10,10,10,'1');
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
        echo($ctk);
        break;
        }     
    }


}