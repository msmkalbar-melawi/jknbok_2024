<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    class Cetak_dpa extends CI_Controller {

      public $keu1 = "5.02.0.00.0.00.02.0000";

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
            return  "Maret";
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

        function _mpdf_margin($judul='',$isi='',$lMargin,$rMargin,$font=10,$orientasi='',$hal='',$tMargin,$bMargin) {
                

        ini_set("memory_limit","-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        
        
        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6; /* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        $sa=1;
        $tes=0;
        if ($hal==''){
        $hal1=1;
        } 
        if($hal!==''){
        $hal1=$hal;
        }
        if($lMargin==''){
            $lMargin = 10;
        } 
        
        if($rMargin==''){
            $rMargin = 10;
        } 

        if($tMargin==''){
            $tMargin = 10;
        } 

        if($bMargin==''){
            $bMargin = 10;
        } 
        
        $this->mpdf = new mPDF('utf-8', array(215,330),$size,'',$lMargin,$rMargin,$tMargin,$bMargin); //folio
        $this->mpdf->AddPage($orientasi,'',$hal1,'1','off');
        $this->mpdf->SetFooter("Printed on SIMAKDA SKPD || Halaman {PAGENO}  ");
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output();
               
    }

    function dpa_pembiayaan_penetapan()
        {
            $data['page_title']= 'CETAK DPA PEMBIAYAAN';
            $this->template->set('title', 'CETAK DPA PEMBIAYAAN');   
            $this->template->load('template','anggaran/dpa/dpa_pembiayaan_penetapan',$data) ; 
        }

    function preview_dpa_pembiayaan_penetapan(){
            $id = $this->uri->segment(2);
            $cetak = $this->uri->segment(3);
            $kdbkad = '5.02.0.00.0.00.02.0000';
            $ttd1= $_REQUEST['ttd1'];
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
            if (strlen($id)==17){
                $a = 'left(';
                $skpd = 'kd_skpd';
                $b = ',17)';

                $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_org as kd_sk,a.nm_org as nm_sk FROM ms_organisasi a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_org=left('$id',17)";
                
            }else{
                $a = 'left(';
                $skpd = 'kd_skpd';
                $b = ',22)';
                $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                $sqldns1="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_org as kd_org,a.nm_org as nm_org FROM ms_organisasi a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_org=left('$id',17)";

                $sqlskpd1=$this->db->query($sqldns1);
                foreach ($sqlskpd1->result() as $rowdns)
                    {
                        $kd_org  = $rowdns->kd_org;
                        $nm_org = $rowdns->nm_org;
                    }
            }

                

      
          
                $sqlskpd=$this->db->query($sqldns);
                foreach ($sqlskpd->result() as $rowdns)
                    {
                       
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                    }

            $sqldns="SELECT * from ms_urusan WHERE kd_urusan=left('$kd_urusan',1)";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                       
                        $kd_urusan1=$rowdns->kd_urusan;                    
                        $nm_urusan1= $rowdns->nm_urusan;
                    }


            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$kdbkad'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = $this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
           
           if ($ttd1<>''){         
           $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE nip='$ttd1'  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $jdlnip1 = 'Mengetahui,';                    
                        $nip1=empty($rowttd->nip) ? '' : 'NIP.'.$rowttd->nip ;
                        $pangkat1=empty($rowttd->pangkat) ? '' : $rowttd->pangkat;
                        $nama1= empty($rowttd->nm) ? '' : $rowttd->nm;
                        $jabatan1  = empty($rowttd->jab) ? '': $rowttd->jab;
                    }
            }
            else{
                        $jdlnip1 = '';
                        $nip1='' ;
                        $pangkat1='';
                        $nama1= '';
                        $jabatan1  = '';
            }        
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:14px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr>  
                             <td width=\"80%\" align=\"center\"><strong>DOKUMEN PELAKSANAAN ANGGARAN <br /> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"2\" align=\"center\"><strong>FORMULIR DPA - <br />PEMBIAYAAN SKPD  </strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\">$kab <br /> Tahun Anggaran $thn </td>
                        </tr>

                      </table>";
            $sqldns="SELECT no_dpa,tgl_dpa from trhrka WHERE  kd_skpd='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdpa)
                    {
                        $no_dpa =$rowdpa->no_dpa;                    
                        $tgl_dpa= $rowdpa->tgl_dpa;
                    }


            if (substr($id,18,4)=='0000'){
                $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">

                        <tr>
                            <td colspan=\"2\"\ style=\" border-left: solid 1px black;border-right: solid 1px black;\" align=\"center\">Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah<br />
Satuan Kerja Perangkat Daerah</td>
                        </tr>

                        <tr>
                            <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">Nomor DPA</td>
                            
                            <td style=\"border-right: solid 1px black;border-top: solid 1px black;\">: $no_dpa</td>
                            
                        </tr>
                        <tr>
                            <td style=\"border-left: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black;\">Organisasi</td>
                            <td style=\"border-right: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black;\">: $kd_skpd - $nm_skpd</td>
                        </tr>
                        
                    </table>";
            }else{
                $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                         <tr>
                            "//<td style=\"border-left: solid 1px black;border-top: solid 1px black;\">Nomor DPA</td>
                            ."
                            "//<td style=\"border-right: solid 1px black;border-top: solid 1px black;\">: $no_dpa</td>
                            ."
                        </tr>
                        <tr>
                            <td width=\"20%\" style=\"border-left: solid 1px black;\">Organisasi </td>
                            <td width=\"80%\" style=\"border-right: solid 1px black;\">: $kd_org.0000 - ".$this->rka_model->get_nama($kd_org,'nm_org','ms_organisasi','left(kd_org,17)')."</td>
                        </tr>
                        <tr>
                            <td style=\" border-left: solid 1px black;border-bottom: solid 1px black;\">Sub Organisasi</td>
                            <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\">: $kd_skpd - $nm_skpd</td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ style=\" border-left: solid 1px black;border-right: solid 1px black;\" align=\"center\"><strong>Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah<br />
Satuan Kerja Perangkat Daerah</strong></td>
                        </tr>
                    </table>";
            }
                    
            $cRet .= "<table style=\"border-collapse:collapse; font-size:12;border-top: solid 1px black;\"  width=\"100%\" align=\"center\" border=\"1\"  cellspacing=\"0\" cellpadding=\"4\">
                         <thead>                       
                            <tr><td  width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>                            
                                <td  width=\"50%\" align=\"center\"><b>Uraian</b></td>
                                <td  width=\"40%\" align=\"center\"><b>Jumlah(Rp.)</b></td>
                            </tr>                            
                         </thead>
                        
                         
                            
                            ";

                            $sql1="SELECT * FROM(
                                SELECT LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE 
                                right(a.kd_sub_kegiatan,5)='06.61' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,1),b.nm_rek1 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,2) 
                                AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama,0 AS volume,' 'AS satuan,0 AS harga,SUM(a.nilai) AS nilai,'2' 
                                AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE right(a.kd_sub_kegiatan,5)='06.61' AND 
                                $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,2),b.nm_rek2 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS
                                rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka a INNER JOIN
                                ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE right(a.kd_sub_kegiatan,5)='06.61' AND $a a.$skpd$b='$id' 
                                GROUP BY LEFT(a.kd_rek6,4),b.nm_rek3
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 
                                WHERE right(a.kd_sub_kegiatan,5)='06.61' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,6),b.nm_rek4 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM 
                                trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE right(a.kd_sub_kegiatan,5)='06.61' AND $a a.$skpd$b='$id' GROUP BY 
                                LEFT(a.kd_rek6,8),b.nm_rek5 
                                 UNION ALL 
                                SELECT a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM 
                                trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE right(a.kd_sub_kegiatan,5)='06.61' AND $a a.$skpd$b='$id' GROUP BY 
                                a.kd_rek6,b.nm_rek6 
                                ) 
                            a ORDER BY a.rek1,a.id";                        


                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                    $totp = 0;  
                    foreach ($query->result() as $row)
                    {
                        $rek=$row->rek;
                        $reke=$this->dotrek($rek);
                        $uraian=$row->nama;
                        $sat=$row->satuan;
                        $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                        
                        
                        if ($reke<>''){
                            $volum='';                        
                        }
                        else{
                            $volum=$row->volume;
                        }
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nila= number_format($row->nilai,"2",",",".");
                         
                        $x = strlen($reke);
                        
                        if (strlen($reke)>8 || strlen($reke)==0){
                           $cRet    .= "<tr>
                                            <td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><FONT SIZE=2>$reke </FONT></td>                                     
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><FONT SIZE=2>$uraian</FONT></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><FONT SIZE=2>$nila</FONT></td>                                     
                                        </tr>";
                       }else{
                            if(strlen($reke)==1){
                                 $totp = $totp + $row->nilai;
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2></FONT></strong></td>
                                                </tr>";
                            }else{
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2>$nila</FONT></strong></td>
                                                </tr>";                            
                            }
                            
                       }                  
                    }
                        $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align=\"right\">&nbsp;</td>
                                 </tr>";

                        $totp=number_format($totp,"2",",",".");
                       
                        $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"40%\"><strong><FONT SIZE=2>JUMLAH PENERIMAAN</FONT></strong></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong><FONT SIZE=2>$totp</FONT></strong></td>
                                         </tr>";

                      $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  <td align=\"right\">&nbsp;</td>
                                 </tr>";
                       $sql1="SELECT * FROM(
                                SELECT LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE 
                                right(a.kd_sub_kegiatan,5)='06.62' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,1),b.nm_rek1 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,2) 
                                AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama,0 AS volume,' 'AS satuan,0 AS harga,SUM(a.nilai) AS nilai,'2' 
                                AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE right(a.kd_sub_kegiatan,5)='06.62' AND 
                                $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,2),b.nm_rek2 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS
                                rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka a INNER JOIN
                                ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE right(a.kd_sub_kegiatan,5)='06.62' AND $a a.$skpd$b='$id' 
                                GROUP BY LEFT(a.kd_rek6,4),b.nm_rek3
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 
                                WHERE right(a.kd_sub_kegiatan,5)='06.62' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,6),b.nm_rek4 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM 
                                trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE right(a.kd_sub_kegiatan,5)='06.62' AND $a a.$skpd$b='$id' GROUP BY 
                                LEFT(a.kd_rek6,8),b.nm_rek5 
                                 UNION ALL 
                                SELECT a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM 
                                trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE right(a.kd_sub_kegiatan,5)='06.62' AND $a a.$skpd$b='$id' GROUP BY 
                                a.kd_rek6,b.nm_rek6  
                                ) 
                            a ORDER BY a.rek1,a.id";                        


                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                    $totp = 0;  
                    foreach ($query->result() as $row)
                    {
                        $rek=$row->rek;
                        $reke=$this->dotrek($rek);
                        $uraian=$row->nama;
                        $sat=$row->satuan;
                        $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                        
                        
                        if ($reke<>''){
                            $volum='';                        
                        }
                        else{
                            $volum=$row->volume;
                        }
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nila= number_format($row->nilai,"2",",",".");
                         
                        $x = strlen($reke);
                        
                        if (strlen($reke)>8 || strlen($reke)==0){
                           $cRet    .= "<tr>
                                            <td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><FONT SIZE=2>$reke </FONT></td>                                     
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><FONT SIZE=2>$uraian</FONT></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><FONT SIZE=2>$nila</FONT></td>                                     
                                        </tr>";
                       }else{
                            if(strlen($reke)==1){
                                 $totp = $totp + $row->nilai;
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2></FONT></strong></td>
                                                </tr>";
                            }else{
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2>$nila</FONT></strong></td>
                                                </tr>";                            
                            }
                            
                       }                  
                    }
                        $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align=\"right\">&nbsp;</td>
                                 </tr>";

                        $totp=number_format($totp,"2",",",".");
                       
                        $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"40%\"><strong><FONT SIZE=2>JUMLAH PENGELUARAN</FONT></strong></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong><FONT SIZE=2>$totp</FONT></strong></td>
                                         </tr>";

                      $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  <td align=\"right\">&nbsp;</td>
                                 </tr>";

            $cRet    .= "</table>";

           $kd_ttd=substr($id,18,4);
                     $kd_kepala=substr($id,0,7);
                    if (($kd_ttd=='0000')){
                        $cRet .="<table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"2\" cellpadding=\"4\">
                                <tr>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Realisasi Penerimaan per Bulan</b></td>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Realisasi Pengeluaran per Bulan</b></td>
                                    <td rowspan=\"15\" width=\"40%\" align=\"center\">$daerah ,$tanggal_ttd <br />Pengguna Anggaran <br/>$jabatan1 <br/> <br><br /><br /><br /><br/>
                                             <br/>$nama1
                                    <br>$pangkat1
                                    <br>NIP. $nip1

                                    </td>
                                </tr>";
                               
                        $tot_keluar=0;
                        $tot_masuk=0;
                $sqltw="SELECT bulan,sum(nilai_susun)as nilai_susun,(select sum(nilai_susun) from trdskpd_ro a where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,2)='61' and z.bulan=a.bulan) as pendapatan from trdskpd_ro z where kd_skpd='$id' and left(kd_rek6,2)='62' group by kd_skpd,bulan order by bulan";
                        $sqltw=$this->db->query($sqltw);
                     foreach ($sqltw->result() as $rowtw)
                    {
                        $bulan=$this->rka_model->getBulan($rowtw->bulan);
                        $nilai_keluar=$rowtw->nilai_susun;
                        $tot_keluar=$tot_keluar+$nilai_keluar;
                        $pendapatan=$rowtw->pendapatan;
                        $tot_masuk=$tot_masuk+$pendapatan;
                         $cRet .="<tr>
                                    <td>&nbsp;$bulan</td>
                                    <td align=\"right\">Rp ".number_format($pendapatan,2,',','.')."</td>
                                    <td>$bulan</td>
                                    <td align=\"right\">Rp ".number_format($nilai_keluar,2,',','.')."</td>
                                    
                                </tr>";

                    }
                        $cRet .="<tr>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_masuk,2,',','.')."</td>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_keluar,2,',','.')."</td>
                                    
                                </tr>";

                                $cRet .="</table>";
                                 } else{

                                    $cRet .="<table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"2\" cellpadding=\"4\">
                                <tr>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Realisasi Penerimaan per Bulan</b></td>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Realisasi Pengeluaran per Bulan</b></td>
                                    <td rowspan=\"15\" width=\"40%\" align=\"center\">$daerah ,$tanggal_ttd <br />$jabatan1 <br/> <br><br /><br /><br /><br/>
                                             <br/>$nama1
                                    <br>$pangkat1 
                                    <br>NIP. $nip1

                                    </td>
                                </tr>";
                                
                        $tot_keluar=0;
                        $tot_masuk=0;
                $sqltw="SELECT bulan,sum(nilai_susun)as nilai_susun,(select sum(nilai_susun) from trdskpd_ro a where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,2)='61' and z.bulan=a.bulan) as pendapatan from trdskpd_ro z where kd_skpd='$id' and left(kd_rek6,2)='62' group by kd_skpd,bulan order by bulan";
                        $sqltw=$this->db->query($sqltw);
                     foreach ($sqltw->result() as $rowtw)
                    {
                        $bulan=$this->rka_model->getBulan($rowtw->bulan);
                        $nilai_keluar=$rowtw->nilai_susun;
                        $tot_keluar=$tot_keluar+$nilai_keluar;
                        $pendapatan=$rowtw->pendapatan;
                        $tot_masuk=$tot_masuk+$pendapatan;
                         $cRet .="<tr>
                                    <td>&nbsp;$bulan</td>
                                    <td align=\"right\">Rp ".number_format($pendapatan,2,',','.')."</td>
                                    <td>$bulan</td>
                                    <td align=\"right\">Rp ".number_format($nilai_keluar,2,',','.')."</td>
                                    
                                </tr>";

                    }
                        $cRet .="<tr>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_masuk,2,',','.')."</td>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_keluar,2,',','.')."</td>
                                    
                                </tr>";

                                $cRet .="</table>";
                                 
                                 }
                                 
            $data['prev']= $cRet;    
            switch($cetak) {
                case 0;  
                    echo ("<title>DPA PEMBIAYAAN</title>");
                    echo($cRet);
                break;
                case 1;
                    $this->support->_mpdf('',$cRet,10,10,10,'0','','DPA PEMBIAYAAN','DPA PEMBIAYAAN');
                        
                break;
            }
            //$this->template->load('template','master/fungsi/list_preview',$data);
            
                    
        }

        function preview_sdana_kosong($id,$csdana){
        $cRet='';
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td  align=\"center\"><b>Kode Kegiatan</b></td>                            
                            <td  align=\"center\"><b>Kode Rekening</b></td>
                        </tr>
                      </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>";

        foreach ($csdana->result() as $row){
            $kdkegiatan=$row->kd_kegiatan;
            $kdrek5=$row->kd_rek5;
        $cRet .= "<tr>
                    <td  align=\"center\"><b>$kdkegiatan</b></td>                            
                    <td  align=\"center\"><b>$kdrek5</b></td>
                  </tr>";

        }
         $cRet .="<tr>
                    <td colspan=\"2\" style=\"border-top: none;\">Silakan Isi Sumber Dana Kegiatan Diatas Terlebih dahulu.</td>
                  </tr></table>";

        echo ("<title>Sumber Dana Kosong</title>");
        echo($cRet);
        
    }
    function daftar_kegiatan_penetapan1($offset=0)
        {
            $id = $this->uri->segment(2);
            $data['page_title'] = "DAFTAR KEGIATAN";
            
            $total_rows = $this->rka_model->get_count($id);
      
            // pagination        
     
            $config['base_url']     = base_url("cetak_dpa/daftar_kegiatan_penetapan1/$id");
            $config['total_rows']   = $total_rows;
            $config['per_page']     = '10';
            $config['uri_segment']  = 3;
            $config['num_links']    = 5;
            $config['full_tag_open'] = '<ul class="page-navi">';
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="current">';
            $config['cur_tag_close'] = '</li>';
            $config['prev_link'] = '&lt;';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&gt;';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['last_link'] = 'Last';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['first_link'] = 'First';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $limit                  = $config['per_page'];  
            $offset                 = $this->uri->segment(3);  
            $offset                 = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
              
            if(empty($offset))  
            {  
                $offset=0;  
            }
        
            $data['list']       = $this->rka_model->getAll($limit, $offset,$id);
            $data['num']        = $offset;
            $data['total_rows'] = $total_rows;
            
                    $this->pagination->initialize($config);
            
            $this->template->set('title', 'Daftar Data kegiatan');
            $this->template->load('template', 'anggaran/rka/list_dpa', $data);
        }
    function caridpa221()
    {
        $lccr = $this->input->post('nm_skpd');
        $this->index('0','ms_skpd','kd_skpd','nm_skpd','RKA 221','rka221',$lccr); 
    }

    function dpa221()
        {   
         $this->index('0','ms_skpd','kd_skpd','nm_skpd','DPA 221','dpa221','');
        }

    function preview_dpa_rincian_belanja_skpd_penetapan(){

            $id = $this->uri->segment(2);
            $giat = $this->uri->segment(3);
            $cetak = $this->uri->segment(4);
            $atas = $this->uri->segment(5);
            $bawah = $this->uri->segment(6);
            $kiri = $this->uri->segment(7);
            $kanan = $this->uri->segment(8);
            
            
     

            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
           /* print_r($ttd2);
            exit();*/
     
            $csdana = $this->rka_model->qcekdanarka($id,'sumber','nilai_sumber','nilai');
            $csdana1 =  $csdana->num_rows();   
            
            /*if($csdana1>0){
                $this->preview_sdana_kosong($id,$csdana);
                exit();
            }*/
     
            $csrinci = $this->rka_model->qcekrincian($id,'nilai');
            $csrinci1 =  $csrinci->num_rows();
            if($csrinci1>0){
                $this->preview_srinci($id,$csrinci);
                exit();
            }
     
            $sqlsc="SELECT tgl_rka,provinsi,kabtitle,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        $tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kabtitle;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                        $thnl =$thn-1;
                        $thnd =$thn+1; 
                    }
            $sqlsc1="SELECT no_dpa FROM trhrka where kd_skpd='$id'";
                     $sqldpa=$this->db->query($sqlsc1);
                     foreach ($sqldpa->result() as $rowsc)
                    {
                       
                        $nodpa=$rowsc->no_dpa;
                        
                    }

           $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND(REPLACE(nip, ' ', 'a')='$ttd1' )  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip; 
                        $pangkat=$rowttd->pangkat;
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        //$jabatan  = str_replace('Kuasa Pengguna Anggaran','',$jabatan);
                        if($jabatan=='Kuasa Pengguna Anggaran'){
                            $kuasa="";
                        }else{
                            $kuasa="Kuasa Pengguna Anggaran";
                        }
                        
                        /* if($jabatan=='Pengguna Anggaran'){
                            $kuasa="";
                        }else{
                            $kuasa="Pengguna Anggaran";
                        } */
                    }
                  
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND(REPLACE(nip, ' ', 'a')='$ttd2')  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip;
                        $pangkat2=$rowttd2->pangkat;
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                        //$jabatan2  = str_replace('Pengguna Anggaran','',$jabatan2);
                        
                        /* if($jabatan2=='Kuasa Pengguna Anggaran'){
                            $kuasa2="";
                        }else{
                            $kuasa2="Kuasa Pengguna Anggaran";
                        } */
                        
                        if($jabatan2=='Pengguna Anggaran'){
                            $kuasa2="";
                        }else{
                            $kuasa2="Pengguna Anggaran";
                        }
                    }
            $sqlorg="SELECT * from header_rka_penetapan
                                    where left(kd_sub_kegiatan,12)='$giat' and kd_skpd='$id'
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
                        $tu_mas  = $roworg->tu_mas;
                        $tu_kel  = $roworg->tu_kel;
                        $tu_has  = $roworg->tu_has;
                        $tk_capai  = $roworg->tk_capai;
                        $tk_mas  = $roworg->tk_mas;
                        $tk_kel  = $roworg->tk_kel;
                        $tk_has  = $roworg->tk_has;
                        $sas_giat = $roworg->kel_sasaran_kegiatan;
                        $ang_lalu = $roworg->ang_lalu;
                    }
            $kd_urusan= empty($roworg->kd_urusan) || ($roworg->kd_urusan) == '' ? '' : ($roworg->kd_urusan);
            $nm_urusan= empty($roworg->nm_urusan) || ($roworg->nm_urusan) == '' ? '' : ($roworg->nm_urusan);
            $kd_bidang_urusan= empty($roworg->kd_bidang_urusan) || ($roworg->kd_bidang_urusan) == '' ? '' : ($roworg->kd_bidang_urusan);
            $nm_bidang_urusan= empty($roworg->nm_bidang_urusan) || ($roworg->nm_bidang_urusan) == '' ? '' : ($roworg->nm_bidang_urusan);
            $kd_skpd= empty($roworg->kd_skpd) || ($roworg->kd_skpd) == '' ? '' : ($roworg->kd_skpd);
            $nm_skpd= empty($roworg->nm_skpd) || ($roworg->nm_skpd) == '' ? '' : ($roworg->nm_skpd);
            $kd_prog= empty($roworg->kd_program) || ($roworg->kd_program) == '' ? '' : ($roworg->kd_program);
            $nm_prog= empty($roworg->nm_program) || ($roworg->nm_program) == '' ? '' : ($roworg->nm_program);
            $sasaran_prog= empty($roworg->sasaran_program) || ($roworg->sasaran_program) == '' ? '' : ($roworg->sasaran_program);
            $capaian_prog= empty($roworg->capaian_program) || ($roworg->capaian_program) == '' ? '' : ($roworg->capaian_program);
            $kd_giat= empty($roworg->kd_kegiatan) || ($roworg->kd_kegiatan) == '' ? '' : ($roworg->kd_kegiatan);
            $nm_giat= empty($roworg->nm_kegiatan) || ($roworg->nm_kegiatan) == '' ? '' : ($roworg->nm_kegiatan);
            $lokasi= empty($roworg->lokasi) || ($roworg->lokasi) == '' ? '' : ($roworg->lokasi);
            $tu_capai= empty($roworg->tu_capai) || ($roworg->tu_capai) == '' ? '' : ($roworg->tu_capai);
            $tu_mas= empty($roworg->tu_mas) || ($roworg->tu_mas) == '' ? '' : ($roworg->tu_mas);
            $tu_kel= empty($roworg->tu_kel) || ($roworg->tu_kel) == '' ? '' : ($roworg->tu_kel);
            $tu_has= empty($roworg->tu_has) || ($roworg->tu_has) == '' ? '' : ($roworg->tu_has);
            $tk_capai= empty($roworg->tk_capai) || ($roworg->tk_capai) == '' ? '' : ($roworg->tk_capai);
            $tk_mas= empty($roworg->tk_mas) || ($roworg->tk_mas) == '' ? '' : ($roworg->tk_mas);
            $tk_kel= empty($roworg->tk_kel) || ($roworg->tk_kel) == '' ? '' : ($roworg->tk_kel);
            $tk_has= empty($roworg->tk_has) || ($roworg->tk_has) == '' ? '' : ($roworg->tk_has);
            $sas_giat= empty($roworg->kel_sasaran_kegiatan) || ($roworg->kel_sasaran_kegiatan) == '' ? '' : ($roworg->kel_sasaran_kegiatan);
            $ang_lalu= empty($roworg->ang_lalu) || ($roworg->ang_lalu) == '' || ($roworg->ang_lalu) == 'Null' ? 0 : ($roworg->ang_lalu);

            $sqltp="SELECT SUM(nilai) AS totb FROM trdrka WHERE left(kd_sub_kegiatan,12)='$giat' AND kd_skpd='$id'";
                     $sqlb=$this->db->query($sqltp);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $totp  =number_format($rowb->totb,"2",",",".");
                       $totp1 =number_format($rowb->totb*1.1,"2",",",".");
                    }


            $sqltps="SELECT capaianteks,targetcapaianteks FROM sipd_capaian a inner join trskpd b on a.id_sub_skpd=b.id_skpd and 
                    b.id_sub_kegiatan=a.id_sub_giat WHERE left(kd_sub_kegiatan,12)='$giat' AND kd_skpd='$id' group by capaianteks,targetcapaianteks";
                     $sqlbs=$this->db->query($sqltps);
                     foreach ($sqlbs->result() as $rowbs)
                    {
                       $capaian  =$rowbs->capaianteks;
                       $targetcapaian =$rowbs->targetcapaianteks;
                    }

                    $hasilteks='';
                    $targethasilteks='';
                    $lokasi1  ='';

            $sqlh="SELECT hasilteks,targethasilteks FROM sipd_hasil a inner join trskpd b on a.id_sub_skpd=b.id_skpd and 
                    b.id_sub_kegiatan=a.id_sub_giat WHERE left(kd_sub_kegiatan,12)='$giat' AND kd_skpd='$id' group by hasilteks,targethasilteks";
                     $sqlh=$this->db->query($sqlh);
                     foreach ($sqlh->result() as $rowh)
                    {
                       $hasilteks  =$rowh->hasilteks;
                       $targethasilteks =$rowh->targethasilteks;
                    }

            $sqlo="SELECT outputteks,targetoutputteks FROM sipd_output_giat a inner join trskpd b on a.id_sub_skpd=b.id_skpd and 
                    b.id_sub_kegiatan=a.id_sub_giat WHERE left(kd_sub_kegiatan,12)='$giat' AND kd_skpd='$id' group by outputteks,targetoutputteks";
                     $sqlo=$this->db->query($sqlo);
                     foreach ($sqlo->result() as $rowo)
                    {
                       $outputteks  =$rowo->outputteks;
                       $targetoutputteks =$rowo->targetoutputteks;
                    }

            $sqlsasar="SELECT sasaran FROM sipd_subkegiatan a inner join trskpd b on a.id_unit=b.id_skpd and 
                    b.id_sub_kegiatan=a.id_subkegiatan WHERE left(kd_sub_kegiatan,12)='$giat' AND b.kd_skpd='$id' group by sasaran";
                     $sqlsasar=$this->db->query($sqlsasar);
                     foreach ($sqlsasar->result() as $rowsasar)
                    {
                       $sasaran  =$rowsasar->sasaran;
                    }

            
                    
            
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr> 
                             <td width=\"80%\" align=\"center\"><strong><b>DOKUMEN PELAKSANAAN ANGGARAN <br />SATUAN KERJA PERANGKAT DAERAH</b></strong></td>
                             <td width=\"20%\" style=\"vertical-align:top;\" rowspan=\"2\" align=\"center\"><strong><br /><br />FORMULIR <br /><b> DPA-RINCIAN
    BELANJA SKPD </b>   
      </strong></td>
                        </tr>
                        <tr>
                             <td style=\"vertical-align:top;\" align=\"center\">$kab <br/>Tahun Anggaran $thn</td>
                        </tr>

                      </table>";
$sqlsubs="SELECT capaianteks,targetcapaianteks FROM sipd_capaian a inner join trskpd b on a.id_sub_skpd=b.id_skpd and 
                    b.id_sub_kegiatan=a.id_sub_giat WHERE left(kd_sub_kegiatan,12)='$giat' AND kd_skpd='$id' group by capaianteks,targetcapaianteks";
                     $sqlbsubs=$this->db->query($sqlsubs);
                      $subkeluaran ="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                                    <tr>
                                        <td width=\"3%\" >&nbsp;</td>
                                        <td width=\"47%\" >&nbsp;(Indikator)</td>
                                        <td width=\"20%\" >&nbsp;&nbsp;</td>
                                        <td width=\"30%\" >&nbsp;(Target)</td>
                                    </tr>
                                    ";
                     foreach ($sqlbsubs->result() as $rowsubs)
                    {
                       $capaianteks          =   $rowsubs->capaianteks;
                       $targetcapaianteks   =   $rowsubs->targetcapaianteks;


                       $subkeluaran .= "

                                        <tr>
                                            <td width=\"3%\" >&nbsp;</td>
                                            <td>&nbsp;$capaianteks</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;$targetcapaianteks</td>
                                        <tr>
                       ";

                   }
                   $subkeluaran .="</table>"; 
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\"><b>&nbsp;Nomor DPA</b></td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\"><b>:&nbsp; $nodpa</b></td>
                                
                            </tr>
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Urusan Pemerintahan</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; $kd_urusan $nm_urusan</td>
                                
                            </tr>
                            
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Bidang Urusan</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; $kd_bidang_urusan $nm_bidang_urusan</td>
                                
                            </tr>

                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Program</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; $kd_prog $nm_prog</td>
                                
                            </tr>

                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Sasaran Program</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; $sasaran_prog</td>
                                
                            </tr>
                            
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Capaian Program</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp;&nbsp;&nbsp;$subkeluaran </td>
                                
                            </tr>

                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Kegiatan</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; $kd_giat $nm_giat</td>
                                
                            </tr>
                            
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Organisasi</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; ".substr($kd_skpd,0,17)." $nm_skpd</td>
                                
                            </tr>
                            
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Unit Organisasi</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; $kd_skpd $nm_skpd</td>
                                
                            </tr>
                            
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Alokasi Tahun $thnl</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; Rp. ".number_format($ang_lalu,"2",",",".")." (".$this->rka_model->terbilang($ang_lalu*1)." rupiah)</td>
                                
                            </tr>
                            
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Alokasi Tahun $thnl</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; Rp. $totp (".$this->rka_model->terbilang($rowb->totb*1)." rupiah)</td>
                                
                            </tr>
                            
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;Alokasi Tahun $thnd</td>
                                <td colspan='3' style=\"border-right: solid 1px black;border-top: solid 1px black;\">:&nbsp; Rp. $totp1 (".$this->rka_model->terbilang($rowb->totb*1.1)." rupiah)</td>
                                
                            </tr>
                            
                            
                            <tr>
                        <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&nbsp;</td>
                        <td colspan='4' style=\"border-right: solid 1px black;border-top: solid 1px black;\">&nbsp;&nbsp;</td>
                    </tr>
                        </table>    
                            
                        ";
            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td colspan=\"3\"  align=\"center\" ><b>Indikator & Tolak Ukur Kinerja Kegiatan</b></td>
                        </tr>";
            $cRet .="<tr>
                     <td width=\"20%\"  align=\"center\"><b>Indikator</b> </td>
                     <td width=\"40%\" align=\"center\"><b>Tolak Ukur Kerja</b> </td>
                     <td width=\"40%\" align=\"center\"><b>Target Kinerja</b> </td>
                    </tr>";          

            $cRet .=" <tr align=\"left\">
                        <td >&nbsp;Capaian Kegiatan </td>
                        <td>&nbsp;$capaian</td>
                        <td>&nbsp;$targetcapaian</td>
                     </tr>";
            $cRet .=" <tr align=\"left\">
                        <td>&nbsp;Masukan </td>
                        <td>&nbsp;Dana yang dibutuhkan</td>
                        <td>&nbsp;Rp. $totp</td>
                    </tr>";
            $cRet .=" <tr align=\"left\">
                        <td>&nbsp;Keluaran </td>
                        <td>&nbsp;$outputteks</td>
                        <td>&nbsp;$targetoutputteks</td>
                      </tr>";
            $cRet .=" <tr align=\"left\">
                        <td>&nbsp;Hasil </td>
                        <td>&nbsp;$hasilteks</td>
                        <td>&nbsp;$targethasilteks</td>
                      </tr>";
            $cRet .= "<tr>
                        <td colspan=\"3\"  width=\"100%\" align=\"left\">&nbsp;Kelompok Sasaran Kegiatan :&nbsp; $sasaran</td>
                    </tr>";
            $cRet .= "<tr>
                        <td colspan=\"3\" width=\"100%\" align=\"left\">&nbsp;</td>
                    </tr>"; 
                    $cRet .= "<tr>
                        <td colspan=\"3\"  width=\"100%\" align=\"left\">&nbsp;</td>
                    </tr>";                
            
            
                        
            $cRet .="</table>";
    //rincian sub kegiatan
                    

                   $sqlsub="SELECT a.kd_sub_kegiatan as kd_sub_kegiatan,b.nm_sub_kegiatan,b.sub_keluaran,b.lokasi,b.waktu_giat,b.waktu_giat2 FROM trdrka a
                    left join trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
                    WHERE left(a.kd_sub_kegiatan,12)='$giat' AND a.kd_skpd='$id'
                    group by a.kd_sub_kegiatan,b.nm_sub_kegiatan,b.sub_keluaran,b.lokasi,b.waktu_giat,b.waktu_giat2";
                     $sqlbsub=$this->db->query($sqlsub);
                     foreach ($sqlbsub->result() as $rowsub)
                    {
                       $sub         =$rowsub->kd_sub_kegiatan;
                       $nm_sub      =$rowsub->nm_sub_kegiatan;
                       $sub_keluaran=$rowsub->sub_keluaran;
                       $lokasi      =$rowsub->lokasi;
                       $waktu_giat  =$rowsub->waktu_giat;
                       $waktu_giat2  =$rowsub->waktu_giat2;
                       $keterangan  ='';



                    
                   

                   

                    $sqlsumber="SELECT kd_sumberdana,sumber,nm_sumberdana FROM v_sumber1 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber1  = $rowsumber->nm_sumberdana;
                        $kdsumber1  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber2 FROM v_sumber2 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber2  = $rowsumber->sumber2;
                        $kdsumber2  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber3 FROM v_sumber3 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber3  = $rowsumber->sumber3;
                        $kdsumber3  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber4 FROM v_sumber4 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber4  = $rowsumber->sumber4;
                        $kdsumber4  = $rowsumber->kd_sumberdana;
                        
                    }

                    if ($kdsumber2==''){
                        $kodesumberdana=$nmsumber1;
                    }else if ($kdsumber2==''){
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2;                    
                    }else if($kdsumber3==''){
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2.'<br />'.$nmsumber3;
                    }else{
                        $kodesumberdana=$nmsumber1.'<br />'.$nmsumber2.'<br />'.$nmsumber3.'<br />'.$nmsumber4;    
                    }

                    // $sqlo="SELECT outputteks,targetoutputteks FROM sipd_output a inner join trskpd b on a.id_sub_skpd=b.id_skpd and 
                    // b.id_sub_kegiatan=a.id_sub_giat WHERE kd_sub_kegiatan='$sub' AND kd_skpd='$id' group by outputteks,targetoutputteks";
                    //  $sqlo=$this->db->query($sqlo);
                    //  foreach ($sqlo->result() as $rowo)
                    // {
                    //    $outputteks1  =$rowo->outputteks;
                    //    $targetoutputteks1 =$rowo->targetoutputteks;
                    // }


                    $sqlsub="SELECT outputteks,targetoutputteks FROM sipd_output a inner join trskpd b on a.id_sub_skpd=b.id_skpd and 
                    b.id_sub_kegiatan=a.id_sub_giat WHERE kd_sub_kegiatan='$sub' AND kd_skpd='$id' group by outputteks,targetoutputteks";
                     $sqlbsub=$this->db->query($sqlsub);
                      $subkeluaran ="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                                    <tr>
                                        <td width=\"50%\" >Indikator</td>
                                        <td width=\"20%\" >&nbsp;&nbsp;</td>
                                        <td width=\"30%\" >Target</td>
                                    </tr>
                                    ";
                     foreach ($sqlbsub->result() as $rowsub)
                    {
                       $outputteks          =   $rowsub->outputteks;
                       $target_outputteks   =   $rowsub->targetoutputteks;


                       $subkeluaran .= "

                                        <tr>
                                            <td>$outputteks</td>
                                            <td>&nbsp;</td>
                                            <td>$target_outputteks</td>
                                        <tr>
                       ";

                   }

                    $subkeluaran .="</table>"; 
                     // echo $subkeluaran;

                    $sqllok="SELECT daerahteks FROM sipd_lokout a inner join trskpd b on a.id_sub_skpd=b.id_skpd and 
                    b.id_sub_kegiatan=a.id_sub_giat WHERE kd_sub_kegiatan='$sub' AND b.kd_skpd='$id' group by daerahteks";
                     $sqllok=$this->db->query($sqllok);
                     foreach ($sqllok->result() as $rowlok)
                    {

                       $lokasi1  =$rowlok->daerahteks;
                    }


                        $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-left: solid 1px black;\" align=\"left\">&nbsp;Sub Kegiatan</td>
                                <td width=\"5%\"  style=\"vertical-align:top;\" align=\"center\">:</td>
                                <td width=\"75%\" colspan=\"3\" style=\"vertical-align:top;border-right: solid 1px black;\" align=\"left\">$sub - $nm_sub</td>
                            </tr>
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-left: solid 1px black;\" align=\"left\">&nbsp;Sumber Pendanaan</td>
                                <td width=\"5%\"  style=\"vertical-align:top;\" align=\"center\">:</td>
                                <td width=\"75%\" colspan=\"3\" style=\"vertical-align:top;border-right: solid 1px black;\" align=\"left\">$kodesumberdana</td>
                            </tr>
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-left: solid 1px black;\" align=\"left\">&nbsp;Lokasi</td>
                                <td width=\"5%\"  style=\"vertical-align:top;\" align=\"center\">:</td>
                                <td width=\"75%\" colspan=\"3\" style=\"vertical-align:top;border-right: solid 1px black;\" align=\"left\">$lokasi1</td>
                            </tr>
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-left: solid 1px black;\" align=\"left\">&nbsp;Waktu Pelaksanaan</td>
                                <td width=\"5%\"  style=\"vertical-align:top;\" align=\"center\">:</td>
                                <td width=\"75%\" colspan=\"3\" style=\"vertical-align:top;border-right: solid 1px black;\" align=\"left\">".$this->getBulan($waktu_giat)." s/d ".$this->getBulan($waktu_giat2)."</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-left: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">&nbsp;Keluaran Sub Kegiatan</td>
                                <td align=\"center\" style=\"vertical-align:top;border-bottom: solid 1px black;\">:</td>
                                <td align=\"left\" colspan=\"3\" style=\"vertical-align:top;border-right: solid 1px black;border-bottom: solid 1px black;\">
                                        $subkeluaran
                                </td>
                            </tr>
                            </table>
                            
                        ";

                        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                              <thead>                 
                            <tr><td rowspan=\"2\"  width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>                            
                                <td rowspan=\"2\"  width=\"40%\" align=\"center\"><b>Uraian</b></td>
                                <td colspan=\"4\"  width=\"30%\" align=\"center\"><b>Rincian Perhitungan</b></td>
                                <td rowspan=\"2\"  width=\"20%\" align=\"center\"><b>Jumlah(Rp.)</b></td></tr>
                            <tr>
                                <td width=\"9%\"     align=\"center\"><b>Koefisien</b></td>
                                <td width=\"9%\"     align=\"center\"><b>Satuan</b></td>
                                <td width=\"9%\"    align=\"center\"><b>Harga</b></td>
                                <td width=\"3%\"     align=\"center\"><b>PPN</b></td>
                            </tr>    
                         
                        </thead> 
                         
                           
                            ";

                            $sql1="SELECT * FROM(SELECT 0 header,0 no_po, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama, '' spek, '' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE a.kd_sub_kegiatan='$sub' AND a.kd_skpd='$id' 
    GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
    UNION ALL 
    SELECT 0 header, 0 no_po,LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'2' AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
    UNION ALL  
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama, '' spek,'' as koefisien, 0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
    UNION ALL 
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
    UNION ALL 
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,8) AS rek1,RTRIM(LEFT(a.kd_rek6,8)) AS rek,b.nm_rek5 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5
    UNION ALL
    SELECT 0 header, 0 no_po, a.kd_rek6 AS rek1,RTRIM(a.kd_rek6) AS rek,b.nm_rek6 AS nama,'' spek,'' as koefisien,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY a.kd_rek6,b.nm_rek6
    UNION ALL

    -- SELECT * FROM (SELECT  b.header,b.no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,0 AS volume,' ' AS satuan,
    -- 0 AS harga,SUM(a.total) AS nilai,'7' AS id 
    -- FROM trdpo a
    -- LEFT JOIN trdpo b ON b.kode=a.kode AND b.header ='1' AND a.no_trdrka=b.no_trdrka 
    -- WHERE LEFT(a.no_trdrka,22)='$id' AND SUBSTRING(a.no_trdrka,24,15)='$sub'
    -- GROUP BY  RIGHT(a.no_trdrka,12),b.header,b.no_po,b.uraian)z WHERE header='1'

    SELECT * FROM (SELECT b.header,b.id as no_pos,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien,0 AS volume,
' ' AS satuan, 0 AS harga,SUM(a.total) AS nilai,'7' AS id FROM trdpo a LEFT JOIN trdpo b ON b.subs_bl_teks=a.uraian
AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE LEFT(a.no_trdrka,22)='$id' AND 
SUBSTRING(a.no_trdrka,24,15)='$sub' GROUP BY RIGHT(a.no_trdrka,12),b.header, b.id,b.uraian)z WHERE header='1' 
UNION ALL
SELECT * FROM (SELECT b.header,b.id as no_pos,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,b.uraian AS nama,'' spek,'' as koefisien, 0 AS volume,
' ' AS satuan, 0 AS harga,SUM(a.total) AS nilai,'7' AS id FROM trdpo a LEFT JOIN trdpo b ON b.uraian=a.ket_bl_teks 
AND b.header ='1' AND a.no_trdrka=b.no_trdrka WHERE LEFT(a.no_trdrka,22)='$id' AND 
SUBSTRING(a.no_trdrka,24,15)='$sub' GROUP BY RIGHT(a.no_trdrka,12),b.header, b.id,b.uraian)z WHERE header='1' 
    
    UNION ALL
    SELECT a. header,a.id as no_po,RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,a.uraian AS nama,spesifikasi as spek,koefisien,a.volume1 AS volume,a.satuan1 AS satuan,
    harga AS harga,a.total AS nilai,'8' AS id FROM trdpo a  WHERE LEFT(a.no_trdrka,22)='$id' AND SUBSTRING(no_trdrka,24,15)='$sub' AND (header='0' or header is null)
    ) a ORDER BY a.rek1,a.no_po

    ";
                     
                    $query = $this->db->query($sql1);
                    $nilangsub=0;

                            foreach ($query->result() as $row)
                            {
                                $rek=$row->rek;
                                $reke=$this->dotrek($rek);
                                $uraian=$row->nama;
                                $spek_komp=$row->spek;
                                $koefisien=$row->koefisien;

                            //    $volum=$row->volume;
                                $sat=$row->satuan;
                                $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                                $volum= empty($row->volume) || $row->volume == 0 ? '' :$row->volume;

                                //$hrg=number_format($row->harga,"2",".",",");
                                $nila= empty($row->nilai) || $row->nilai == 0 ? '' :number_format($row->nilai,2,',','.');

                                        
                                

                                if ($row->id<='7'){
                                    $ppn='';
                               
                                 $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\">&nbsp;<b>$reke</b></td>                                     
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"40%\">&nbsp;<b>$uraian</b></td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"9%\" align=\"right\">&nbsp;<b>$koefisien</b></td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"9%\" align=\"center\">&nbsp;<b>$sat</b></td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"9%\" align=\"right\">&nbsp;<b>$hrg</b></td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"3%\" align=\"center\">&nbsp;<b>$ppn</b></td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">&nbsp;<b>$nila</b></td></tr>
                                                 ";

                                             }else{
                                                $ppn=0;
                                                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\">&nbsp;$reke</td>                                     
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"40%\">&nbsp;$uraian<br /> &nbsp;&nbsp;&nbsp; $spek_komp</td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"9%\" align=\"right\">&nbsp;$koefisien&nbsp;&nbsp;</td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"9%\" align=\"center\">&nbsp;$sat&nbsp;&nbsp;</td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"9%\" align=\"right\">&nbsp;$hrg&nbsp;&nbsp;</td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"3%\" align=\"center\">&nbsp;<b>$ppn&nbsp;&nbsp;</b></td>
                                                 <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">&nbsp;$nila&nbsp;&nbsp;</td></tr>
                                                 ";
                                                 $nilangsub= $nilangsub+$row->nilai;        
                                             }
                                             
                            }

                            $cRet    .=" 
                                        <tr>                                    
                                         <td colspan=\"6\" align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\"><b>Jumlah Anggaran Sub Kegiatan</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>".number_format($nilangsub,2,',','.')."</b></td></tr>
                                         <tr>                                    
                                         <td colspan=\"7\"  align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"40%\">&nbsp;</td></tr>
                                         </table>";
                    }

                    


                            $cRet    .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"> 
                                        

                                         <tr>                                    
                                         <td colspan=\"5\" align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\"><b>Jumlah Anggaran Kegiatan</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$totp</b></td></tr>
                                         </table>";
            



            
                     
                        // $cRet .= "<tr>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td align=\"right\">&nbsp;</td>
                        //          </tr>";
                     
                       
                        // $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Belanja</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">&nbsp;</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$totp</td></tr>
                        //                  </table>";
                      
                             $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">";

                     $qtriw = "SELECT 'Januari' as bulan, isnull(sum(nilai),0)as nilai from trdskpd_ro where kd_skpd='$id' and bulan in ('1') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'Februari', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('2') and left(kd_sub_kegiatan,12)='$giat' 
UNION
select 'Maret', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('3') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'April', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('4') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'Mei', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('5') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'Juni', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('6') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'Juli', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('7') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'Agustus', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('8') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'September', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('9') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'Oktober', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('10') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'November', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('11') and left(kd_sub_kegiatan,12)='$giat'
UNION
select 'Desember', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('12') and left(kd_sub_kegiatan,12)='$giat'
 ";
                                
  $cRet  .=" 
                    <tr>
                                    <td width=\"30%\" align=\"center\" colspan=\"3\"><b>Rencana Penarikan Dana per Bulan</b></td>
                                    <td rowspan=\"15\" width=\"50%\" align=\"center\" colspan=\"1\">                          
                                    
                                    
                                    
                                    $daerah, $tanggal_ttd
                                    <br>Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama</u></b>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    
                                    </td>

                                 </tr>

                    
                            ";
        $totaltw=0;       
        $query = $this->db->query($qtriw);
            foreach ($query->result() as $triw){
                            $bulan=  $triw->bulan;
                            $nilai= empty($triw->nilai) ? 0 :$triw->nilai;
                            $totaltw = $totaltw+$nilai;

                            $cRet  .=" 
                            <tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">&emsp;$bulan</td>
                                <td colspan='2' align='right' style=\"border-right: solid 1px black;border-top: solid 1px black;\"> Rp. ".number_format($nilai,2,',','.')."</td>
                                
                            </tr>

                            
                              ";
                              

            }

                    

                    $cRet  .="<tr>
                                <td style=\"border-left: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black;\">&emsp;Jumlah</td>
                                <td colspan='2' align='right' style=\"border-right: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black;\">".number_format($totaltw,2,',','.')."</td>
                                
                            </tr>

                            ";
                                 
                      

            $cRet .=       " </table>";
            $data['prev']= $cRet;    
            $judul='RKA-rincian_belanja_'.$id.'';
            switch($cetak) { 
            case 1;

                 $this->_mpdf_margin('',$cRet,$kanan,$kiri,10,'1','',$atas,$bawah);
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0;  
             echo ("<title>RKA Rincian Belanja</title>");
                echo($cRet);
            break;
            }
        }

    function ctk_dpa22(){
        $data['page_title']= 'Cetak DPA 2.2';
        $this->template->set('title', 'Cetak DPA 2.2');   
        $this->template->load('template','anggaran/rka/ctk_dpa22',$data) ; 
    }

      function preview_rka_belanja_skpd_penetapan(){
            $id = $this->uri->segment(2);
            $cetak = $this->uri->segment(3);
            
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $ttd1 = str_replace('a',' ',$ttd1); 
            $ttd2 = str_replace('a',' ',$ttd2); 
            $keu = $this->keu1;
            
            $csdana = $this->rka_model->qcekdanarka($id,'sumber','nilai_sumber','nilai');
            $csdana1 =  $csdana->num_rows();   
            
            
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
         
            $sqlsc="SELECT tgl_rka,provinsi,kabtitle,daerah,thn_ang FROM sclient where kd_skpd='$keu'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = $this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kabtitle;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
            $sqlsc1="SELECT no_dpa FROM trhrka where kd_skpd='$id'";
                     $sqldpa=$this->db->query($sqlsc1);
                     foreach ($sqldpa->result() as $rowsc)
                    {
                       
                        $nodpa=$rowsc->no_dpa;
                        
                    }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') and nip='$ttd1'  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip;
                        $pangkat=$rowttd->pangkat;
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                    }
                    
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') and nip='$ttd2'  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip; 
                        $pangkat2=$rowttd2->pangkat;
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                    }
            
            
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:14px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr>
                             <td width=\"80%\" align=\"center\"><strong>DOKUMEN PELAKSANAAN ANGGARAN <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"4\" align=\"center\"><strong>FORMULIR<br>DPA - BELANJA SKPD   </strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\">$kab <br />TAHUN ANGGARAN $thn </td>
                        </tr>

                      </table>";

        
                 if (strlen($id)>17){
                        $sqldns="SELECT a.kd_urusan as kd_u,b.kd_urusan as header, LEFT(a.kd_skpd,17) as kd_org,c.nm_org as nm_org,b.kd_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a 
            INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan 
            INNER JOIN ms_organisasi c ON LEFT(a.kd_skpd,17)=c.kd_org
            WHERE kd_skpd='$id'";
                        $a = 'left(';
                        $skpd = 'kd_skpd';
                        $b = ',22)';             
                    }else{
                        $sqldns="SELECT a.kd_urusan as kd_u,b.kd_urusan as header, LEFT(a.kd_skpd,17) as kd_org,c.nm_org as nm_org,b.kd_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a 
            INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan 
            INNER JOIN ms_organisasi c ON LEFT(a.kd_skpd,17)=c.kd_org
            WHERE left(kd_skpd,17)='$id'";
                        $a = 'left(';
                        $skpd = 'kd_skpd';
                        $b = ',17)'; 
                    }
                             $sqlskpd=$this->db->query($sqldns);
                             foreach ($sqlskpd->result() as $rowdns)
                            {
                                $kd_urusan=$rowdns->kd_u;                    
                                $nm_urusan= $rowdns->nm_u;
                                $kd_skpd  = $rowdns->kd_sk;
                                $nm_skpd  = $rowdns->nm_sk;
                                $header  = $rowdns->header;
                                $kd_org = $rowdns->kd_org;
                                $nm_org = $rowdns->nm_org;
                            }


            if (strlen($id)==17){          
                $cRet.="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                       <tr>
                            <td  width=\"20%\" style=\"vertical-align:center;border-right: none;\"><strong>&emsp;Nomor DPA</strong></td>
                            <td colspan=\"2\" style=\"vertical-align:left;border-left: none;\"> <strong>: $nodpa</strong></td>
                        </tr>
                        <tr>
                            <td colspan=\"3\" width=\"1%\"  align=\"center\" text-rotate=\"90\"><b>&nbsp;</b></td>
                        </tr>
                        <tr>
                            <td style=\"border-top:solid 1px black;font-size:14px;\" colspan=\"3\" align=\"center\"><strong>REKAPITULASI DOKUMEN PELAKSANAAN ANGGARAN BELANJA
                    <br />BERDASARKAN PROGRAM DAN KEGIATAN </strong></td>
                        </tr>
                    </table>";
            }else{
                $cRet.="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                        
                      
                        <tr>
                            <td width=\"20%\" style=\"vertical-align:center;border-right: none;\"><strong>&emsp;Nomor DPA</strong></td>
                            <td colspan=\"2\" style=\"vertical-align:center;border-left: none;\"> <strong>: $nodpa</strong></td>
                        </tr> 
                        <tr>
                            <td width=\"20%\" style=\"vertical-align:center;border-right: none;\"><strong>&emsp;Unit Organisasi </strong></td>
                            <td colspan=\"2\"  style=\"vertical-align:center;border-left: none;\"><strong>: </strong></td>
                        </tr>
                        <tr>
                            <td colspan=\"3\" width=\"1%\"  align=\"center\" text-rotate=\"90\"><b>&nbsp;</b></td>
                        </tr>
                        <tr>
                            <td style=\"border-top:solid 1px black;font-size:14px;\" colspan=\"3\" align=\"center\"><strong>Rekapitulasi Anggaran Belanja Berdasarkan Program dan Kegiatan </strong></td>
                        </tr>
                    </table>";
            }

            // $aa = "<tr>
            //                 <td width=\"20%\" style=\"vertical-align:center;border-right: none;\"><strong>&emsp;Unit Organisasi </strong></td>
            //                 <td colspan=\"2\"  style=\"vertical-align:center;border-left: none;\"><strong>: $kd_skpd - $nm_skpd</strong></td>
            //             </tr>";

            $cRet .= "<table style=\"border-collapse:collapse;font-size:9px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
                         <thead>                       
                            <tr><td colspan=\"5\" rowspan =\"2\"  width=\"11%\" align=\"center\"><b>Kode</b></td>                            
                                <td rowspan=\"2\"  width=\"15%\" align=\"center\"><b>Uraian</b></td>
                                <td rowspan=\"2\"  width=\"5%\" align=\"center\"><b>Sumber<br>Dana</b></td>
                                <td rowspan=\"2\"  width=\"5%\" align=\"center\"><b>Lokasi</b></td>
                                <td rowspan=\"2\"  width=\"10%\" align=\"center\"><b>T - 1</b></td>
                                <td colspan=\"5\"  width=\"45%\" align=\"center\"><b>Tahun n</b></td>
                                <td rowspan=\"2\"  width=\"9%\" align=\"center\"><b>T+1</b></td>
                            </tr>
                            <tr>
                               
                                <td width=\"9%\"  align=\"center\"><b>Belanja Operasi</b></td>
                                <td width=\"9%\"  align=\"center\"><b>Belanja Modal</b></td>
                                <td width=\"9%\"  align=\"center\"><b>Belanja Tidak Terduga</b></td>
                                <td width=\"9%\"  align=\"center\"><b>Belanja Transfer</b></td>
                                <td width=\"9%\"  align=\"center\"><b>Jumlah</b></td>
                            </tr>    
                         </thead>

                        
                          
                            <tr>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\">1</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >2</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >3</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >4</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >5</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >6</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >7</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >8</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >9</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >10</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >11</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >12</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >13</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >14</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >15</td>
                            </tr>

                                <tfoot>
                                    

                           
                                </tfoot>
                            ";
                    $n_trdrka = 'trdrka';   
                    $n_trskpd = 'trskpd';
                    
                    $sql1="SELECT
                                * FROM cetak_dpa_belanja_penetapan where $a kd_skpd$b='$id'
                           ORDER BY ID
                            ";
                     
                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                                                      
                    foreach ($query->result() as $row)
                    {
                        $urusan=$row->urusan;
                        $subrsan=$row->sub_urusan;
                        $prog=$row->prog1;
                        $giat=$row->giat;
                        $subgiat=$row->sub_giat;
                        $uraian=$row->uraian;
                        $lokasi=$row->lokasi;
                        $target=$row->target;
                        $t1=$row->t1;
                        $opr=empty($row->bloperasi) || $row->bloperasi == 0 ? '' :number_format($row->bloperasi,2,',','.');
                        $mdl=empty($row->blmodal) || $row->blmodal == 0 ? '' :number_format($row->blmodal,2,',','.');
                        $taktdg=empty($row->bltaktdg) || $row->bltaktdg == 0 ? '' :number_format($row->bltaktdg,2,',','.');
                        $trfs=empty($row->bltrfs) || $row->bltrfs == 0 ? '' :number_format($row->bltrfs,2,',','.');
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nilai= number_format($row->jumlah,"2",",",".");


                        //ambil sumber dana
                        $sqlsumber      = "SELECT sumber as sd,(SELECT nm_sumber_dana1 from sumber_dana where kd_sumber_dana1=replace(sumber,'.',''))as nmsumber  FROM trdrka where $a kd_skpd$b='$id' group by sumber 
                                            UNION
                                            SELECT sumber2,(SELECT nm_sumber_dana1 from sumber_dana where kd_sumber_dana1=replace(sumber2,'.',''))as nmsumber FROM trdrka where $a kd_skpd$b='$id' group by sumber2
                                            UNION
                                            SELECT sumber3,(SELECT nm_sumber_dana1 from sumber_dana where kd_sumber_dana1=replace(sumber3,'.',''))as nmsumber FROM trdrka where $a kd_skpd$b='$id' group by sumber3
                                            UNION
                                            SELECT sumber4,(SELECT nm_sumber_dana1 from sumber_dana where kd_sumber_dana1=replace(sumber4,'.',''))as nmsumber FROM trdrka where $a kd_skpd$b='$id' group by sumber4
                                            ";
                     
                     $sumberdn  = $this->db->query($sqlsumber);
                     //$query = $this->skpd_model->getAllc();
                    $sumber_dana='';                                  
                    foreach ($sumberdn ->result() as $rows)
                    {   
                        $sumber_dana=$sumber_dana.'<br>'.$rows->nmsumber;

                    }  
                       
                         if($subrsan!='' || $urusan!=''){
                                         $cRet    .= " <tr><td style=\"vertical-align:center;border-bottom: solid 1px black;\"  align=\"center\">$urusan</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($subrsan,2,2)."&nbsp;&nbsp;</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($prog,5,2)."&nbsp;&nbsp;</td>                                     
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($giat,8,4)."</td>
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($subgiat,13,2)."&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" >$uraian</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" ></td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  >$lokasi</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\" >".$this->rka_model->angka($t1)."&nbsp;&nbsp;</td>
                                         
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$opr&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$mdl&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$taktdg&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$trfs&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$nilai&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">".$this->rka_model->angka($row->jumlah*1.1)."&nbsp;&nbsp;</td></tr>
                                         ";
                                    }else{
                                         $cRet    .= " <tr><td style=\"vertical-align:center;border-bottom: solid 1px black;\"  align=\"center\">$urusan</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($subrsan,2,2)."&nbsp;&nbsp;</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($prog,5,2)."&nbsp;&nbsp;</td>                                     
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($giat,8,4)."</td>
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($subgiat,13,2)."&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" >$uraian</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" >$sumber_dana</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  >$lokasi</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\" >".$this->rka_model->angka($t1)."&nbsp;&nbsp;</td>
                                         
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$opr&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$mdl&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$taktdg&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$trfs&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$nilai&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">".$this->rka_model->angka($row->jumlah*1.1)."&nbsp;&nbsp;</td></tr>
                                         ";
                                    } 
                    }
                            
                            $sql1="SELECT x.kd_skpd ,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='51' AND  $a a.kd_skpd$b='$id') AS bloperasi,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='52' AND  $a a.kd_skpd$b='$id')  AS blmodal,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='53' AND  $a a.kd_skpd$b='$id') AS bltaktdg,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd  AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='54' AND  $a a.kd_skpd$b='$id') AS bltrfs,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd  AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE $a x.kd_skpd$b='$id' and LEFT(kd_rek6,1)='5' ) AS jumlah FROM $n_trdrka x 
    inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd
    WHERE $a x.kd_skpd$b='$id' GROUP BY x.kd_skpd ";

                    
                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                                                     
                    foreach ($query->result() as $row)
                    {

                       
                        $opr=empty($row->bloperasi) || $row->bloperasi == 0 ? '' :number_format($row->bloperasi,2,',','.');
                        $mdl=empty($row->blmodal) || $row->blmodal == 0 ? '' :number_format($row->blmodal,2,',','.');
                        $taktdg=empty($row->bltaktdg) || $row->bltaktdg == 0 ? '' :number_format($row->bltaktdg,2,',','.');
                        $trfs=empty($row->bltrfs) || $row->bltrfs == 0 ? '' :number_format($row->bltrfs,2,',','.');
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nilai= number_format($row->jumlah,"2",",",".");
                       
                         $cRet    .= " <tr>
                                        
                                        <td colspan=\"9\" style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" align=\"right\"> JUMLAH</td>                                     
                                         
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$opr</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$mdl</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$taktdg</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$trfs</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$nilai</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\"></td>
                                         </tr>
                                         ";
                    }
                    $cRet    .= "</table>";
                    $kd_ttd=substr($id,19,4);
                     $kd_kepala=substr($id,0,7);
                    $cRet    .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">";
                    

                     
                    
        
        $qtriw = "SELECT 'Januari' as bulan, isnull(sum(nilai),0)as nilai from trdskpd_ro where kd_skpd='$id' and bulan in ('1')
UNION
select 'Februari', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('2')
UNION
select 'Maret', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('3')
UNION
select 'April', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('4')
UNION
select 'Mei', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('5')
UNION
select 'Juni', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('6')
UNION
select 'Juli', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('7')
UNION
select 'Agustus', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('8')
UNION
select 'September', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('9')
UNION
select 'Oktober', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('10')
UNION
select 'November', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('11')
UNION
select 'Desember', isnull(sum(nilai),0) from trdskpd_ro where kd_skpd='$id' and bulan in ('12')
 ";
                                
  $cRet  .=" 
                   <tr>
                                    <td width=\"30%\" align=\"center\" colspan=\"3\"><b>Rencana Penarikan Dana per Bulan</b></td>
                                    <td rowspan=\"15\" width=\"50%\" align=\"center\" colspan=\"1\">                          
                                    
                                    
                                    
                                    $daerah, $tanggal_ttd
                                    <br>Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama</u></b>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    
                                    </td>

                                 </tr>

                    
                            ";
        $totaltw=0;       
        $query = $this->db->query($qtriw);
            foreach ($query->result() as $triw){
                            $bulan=  $triw->bulan;
                            $nilai= empty($triw->nilai) ? 0 :$triw->nilai;
                            $totaltw = $totaltw+$nilai;

                            $cRet  .=" 
                             <tr>
                                    <td width=\"20%\" style=\"border-left: 1px solid black;\" align=\"left\">&emsp;$bulan</td>
                                    <td width=\"5%\" style=\"border-right: none;\" align=\"left\">&emsp;Rp</td>                      
                                    <td  width=\"25%\" style=\"border-left: none;border-right: solid 1px black;\" align=\"right\">&emsp;".number_format($nilai,2,',','.')."</td>
                                     
                                

                            </tr>
                              ";
                              

            }

                    

                    $cRet  .="<tr>
                                    <td align=\"right\" style=\"border-left: 1px solid black;border-bottom: 1px solid black;\">&emsp;Jumlah</td>
                                    <td align=\"left\" style=\"border-right: none;border-bottom: 1px solid black;\">&emsp;Rp</td>                        
                                    <td align=\"right\" style=\"border-left: none;border-bottom: solid 1px black;border-right: solid 1px black;\">&emsp;".number_format($totaltw,2,',','.')."</td>
                                    

                                </tr>                           
                            ";   
               
                                 
                     
            $cRet    .= "</table>";
            $data['prev']= $cRet; 
            $judul ='RKA-Belanja-SKPD Penetapan'.$id.'';
          switch($cetak) { 
            case 1;
                 $this->support->_mpdf('',$cRet,10,10,5,'5');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0;     
            echo ("<title>RKA Belanja SKPD Penetapan</title>");
            echo($cRet);
            break;
            }
            
                    
        }

         function right($value, $count){
        return substr($value, ($count*-1));
        }

        function left($string, $count){
        return substr($string, 0, $count);
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
                    case 4:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2);                               
                     break;
                    case 6:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2);                              
                    break;
                    case 8:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2);                             
                    break;
                    case 12:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2).'.'.substr($rek,8,4);                             
                    break;
                    default:
                    $rek = "";  
                    }
                    return $rek;
        }


        // DPA SKPD
        function dpa_skpd_penetapan(){
            $data['page_title']= 'CETAK';
            $this->template->set('title', 'Cetak RKA SKPD Penetapan');   
            $this->template->load('template','anggaran/dpa/ctk_dpa_skpd',$data) ; 
        }

        function preview_dpa_skpd_penetapan(){
            $id = $this->uri->segment(2);
            $cetak = $this->uri->segment(3);
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= '';
            $ttd1 = str_replace('a',' ',$ttd1); 
            $ttd2 = str_replace('a',' ',$ttd2); 
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
           
            $sqlsc="SELECT tgl_rka,provinsi,kabtitle,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kabtitle;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND nip='$ttd1' ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                    }
                    
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') and nip='$ttd2' ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip; 
                        $pangkat2=$rowttd2->pangkat;  
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                    }
           $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,17) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
    a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
     ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
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
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr> 
                             <td width=\"80%\" align=\"center\"><strong>DOKUMEN PELAKSANAAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"2\" align=\"center\"><strong>DPA <br />
REKAPITULASI<br />
SKPD</strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\">$kab <br>Tahun Anggaran $thn </td>
                        </tr>
                      </table>";

                       // <tr>
                       //      <td width=\"20%\">Urusan Pemerintahan </td>
                       //      <td width=\"80%\">$kd_urusan - $nm_urusan</td>
                       //  </tr>

//NO DPA
                      $sqldns="SELECT no_dpa,tgl_dpa from trhrka WHERE  kd_skpd='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdpa)
                    {
                        $no_dpa =$rowdpa->no_dpa;                    
                        $tgl_dpa= $rowdpa->tgl_dpa;
                    }


            if (substr($id,18,4)=='0000'){
                $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                        <tr>
                            <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">Nomor DPA</td>
                            <td style=\"border-right: solid 1px black;border-top: solid 1px black;\">: $no_dpa</td>
                        </tr>
                        <tr>
                            <td style=\"border-left: solid 1px black;border-bottom: solid 1px black;\">Organisasi</td>
                            <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\">: $kd_skpd - $nm_skpd</td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ style=\"border-left: solid 1px black;border-right: solid 1px black;\" align=\"center\">Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah<br />
Satuan Kerja Perangkat Daerah</strong></td>
                        </tr>
                    </table>";
            }else{
                $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                         <tr>
                            <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">Nomor DPA</td>
                            <td style=\"border-right: solid 1px black;border-top: solid 1px black;\">: $no_dpa</td>
                        </tr>
                        <tr>
                            <td width=\"20%\" style=\"border-left: solid 1px black;\">Organisasi </td>
                            <td width=\"80%\" style=\"border-right: solid 1px black;\">: $kd_org.0000 - ".$this->rka_model->get_nama($kd_org,'nm_org','ms_organisasi','left(kd_org,17)')."</td>
                        </tr>
                        <tr>
                            <td style=\" border-left: solid 1px black;border-bottom: solid 1px black;\">Sub Organisasi</td>
                            <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\">: $kd_skpd - $nm_skpd</td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ align=\"center\">Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah<br />
Satuan Kerja Perangkat Daerah</td>
                        </tr>
                    </table>";
            }

            
            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                         <thead>                       
                            <tr><td colspan=\"3\"  width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>                            
                                <td  width=\"70%\" align=\"center\"><b>Uraian</b></td>
                                <td  width=\"20%\" align=\"center\"><b>Jumlah</b></td></tr>
                         </thead>
                         
                            <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"3%\" align=\"center\">1</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"3%\" align=\"center\">2</td>  
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"4%\" align=\"center\">3</td>                              
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"70%\" align=\"center\">4</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">5</td></tr>
                            ";
                     $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,2)='41' 
    and b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 

    UNION ALL 

    SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b 
    ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,2)='41' and b.kd_skpd='$id' 
    GROUP BY a.kd_rek2,a.nm_rek2 

    UNION ALL 

    SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek ,
    SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
     where left(b.kd_rek6,2)='41' and b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                     if ($query->num_rows() > 0){                                  
                    foreach ($query->result() as $row)
                    {
                        $coba1=$this->dotrek($row->kd_rek);
                        $coba2=$row->nm_rek;
                        $coba3= number_format($row->nilai,"2",",",".");


                        if (strlen($row->kd_rek)=='1'){

                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba1,0,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba1,2,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\"><b>".substr($coba1,4,2)."</b></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><b>&nbsp;$coba2</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td></tr>";

                        }else if (strlen($row->kd_rek)=='2'){

                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba1,0,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba1,2,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\"><b>".substr($coba1,4,2)."</b></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$coba2</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$coba3</b></td></tr>";

                        }else{

                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\">".substr($coba1,0,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\">".substr($coba1,2,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">".substr($coba1,4,2)."</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$coba2</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td></tr>";

                        }
                       
                        
                                        
                    }
                    }else{
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>4</b></td>
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\"></td>
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><b>&nbsp;PENDAPATAN</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>".number_format(0,"2",",",".")."</b></td></tr>";
                        
                    
                    }                                 
                    
                    $sqltp="SELECT SUM(nilai) AS totp FROM trdrka WHERE LEFT(kd_rek6,2)='41' and kd_skpd='$id'";
                     $sqlp=$this->db->query($sqltp);
                     foreach ($sqlp->result() as $rowp)
                    {
                       $coba4=number_format($rowp->totp,"2",",",".");
                        $cob1=$rowp->totp;
                       
                        $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\" align=\"right\"><b >Jumlah Pendapatan</b>&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$coba4</b></td></tr>";
                     }     
                    $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5' AND b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $query1 = $this->db->query($sql2);
                     foreach ($query1->result() as $row1)
                    {
                        $coba5=$this->dotrek($row1->rek);
                        $coba6=$row1->nm_rek;
                        $coba7= number_format($row1->nilai,"2",",",".");

                        if (strlen($row1->rek)=='1'){
                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba5,0,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba5,2,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\"><b>".substr($coba5,4,2)."</b></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;<b>$coba6</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b></b></td></tr>";

                        }else if (strlen($row1->rek)=='2'){
                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba5,0,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>".substr($coba5,2,1)."</b></td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\"><b>".substr($coba5,4,2)."</b></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$coba6</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$coba7</b></td></tr>";

                        }else {

                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\">".substr($coba5,0,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\">".substr($coba5,2,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">".substr($coba5,4,2)."</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$coba6</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";
                            
                        }
                       
                        
                    }
                    
                        $sqltb="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,1)='5' and kd_skpd='$id'";
                        $sqlb=$this->db->query($sqltb);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $coba8=number_format($rowb->totb,"2",",",".");
                        $cob=$rowb->totb;
                        $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\" align=\"right\"><b>Jumlah Belanja</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$coba8</b></td></tr>";
                     }


                      
                      $surplus=$cob1-$cob; 
                        $cRet    .= " <tr>                                     
                                         <td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\" width=\"70%\"><b>Surplus/Defisit</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".$this->rka_model->angka($surplus)."</td></tr>"; 
                        
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"center\"><b>6</b></td>  
        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\"></td>  
        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;<b>PEMBIAYAAN</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td></tr>";
    //pembiayaan
    $sqlpm="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $querypm = $this->db->query($sqlpm);
                     foreach ($querypm->result() as $rowpm)
                    {
                        $coba9=$this->dotrek($rowpm->rek);
                        $coba10=$rowpm->nm_rek;
                        $coba11= number_format($rowpm->nilai,"2",",",".");

                        if(strlen($rowpm->rek)=='1'){
                            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\">".substr($coba9,0,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\">".substr($coba9,2,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"left\">".substr($coba9,4,2)."</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                        }else if(strlen($rowpm->rek)=='2') {
                            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\">".substr($coba9,0,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\">".substr($coba9,2,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"left\">".substr($coba9,4,2)."</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                        }else{
                            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\">".substr($coba9,0,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\">".substr($coba9,2,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"left\">".substr($coba9,4,2)."</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                        }
                       
                         
                    } 


    $sqltpm="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='61' and kd_skpd='$id'";
                        $sqltpm=$this->db->query($sqltpm);
                     foreach ($sqltpm->result() as $rowtpm)
                    {
                       $coba12=number_format($rowtpm->totb,"2",",",".");
                        $cobtpm=$rowtpm->totb;
                        $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\" align=\"right\"><b>Jumlah Penerimaan Pembiayaan</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$coba12</b></td></tr>";
                     } 

                       


    //pembiayaan
    $sqlpk="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $querypk= $this->db->query($sqlpk);
                     foreach ($querypk->result() as $rowpk)
                    {
                        $coba9=$this->dotrek($rowpk->rek);
                        $coba10=$rowpk->nm_rek;
                        $coba11= number_format($rowpk->nilai,"2",",",".");
                       
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"left\">".substr($coba9,0,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"left\">".substr($coba9,2,1)."</td>
                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"left\">".substr($coba9,4,2)."</td>                                    
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                    } 


    $sqltpk="SELECT SUM(nilai) AS totb FROM trdrka WHERE LEFT(kd_rek6,2)='62' and kd_skpd='$id'";
                        $sqltpk=$this->db->query($sqltpk);
                     foreach ($sqltpk->result() as $rowtpk)
                    {
                       $cobatpk=number_format($rowtpk->totb,"2",",",".");
                        $cobtpk=$rowtpk->totb;
                        $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\" align=\"right\"><b>Jumlah Pengeluaran Pembiayaan</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$coba12</b></td></tr>";
                     }
        
          $pnetto=$cobtpm-$cobtpk;
                        $cRet    .= " <tr>                                     
                                         <td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\" width=\"70%\"><b>Pembiayaan Netto</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>".$this->rka_model->angka($pnetto)."</b></td></tr></table>";                                                      


                        $kd_ttd=substr($id,18,4);
                     $kd_kepala=substr($id,0,7);
                     $kepala_bpkad = $this->master_ttd->kepalaBpkad();
                    if (($kd_ttd=='0000')){
                        $cRet .="<table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"2\" cellpadding=\"4\">
                                <tr>
                                    <td width=\"30%\" align=\"center\" colspan=\"2\"><b>Rencana Realisasi Penerimaan per Bulan</b></td>
                                    <td width=\"30%\" align=\"center\" colspan=\"2\"><b>Rencana Penarikan Dana per Bulan</b></td>
                                    <td width=\"40%\" rowspan=\"15\" width=\"40%\" align=\"center\">$daerah ,$tanggal_ttd <br />Pengguna Anggaran <br/>$jabatan <br/> <br><br /><br /><br /><br/>
                                             <br/>$nama
                                    <br>$pangkat 
                                    <br>NIP. $nip
<br/><br/>
                                    <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                            {$kepala_bpkad[0]->nama}<br/>
                                            NIP. {$kepala_bpkad[0]->nip}

                                    </td>
                                </tr>";
                               
                        $tot_keluar=0;
                        $tot_masuk=0;
                $sqltw="SELECT bulan,sum(nilai_susun)as nilai_susun,(select sum(nilai_susun) from trdskpd_ro a where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,1)='4' and z.bulan=a.bulan) as pendapatan from trdskpd_ro z where kd_skpd='$id' and left(kd_rek6,1)='5' group by kd_skpd,bulan order by bulan";
                        $sqltw=$this->db->query($sqltw);
                     foreach ($sqltw->result() as $rowtw)
                    {
                        $bulan=$this->rka_model->getBulan($rowtw->bulan);
                        $nilai_keluar=$rowtw->nilai_susun;
                        $tot_keluar=$tot_keluar+$nilai_keluar;
                        $pendapatan=$rowtw->pendapatan;
                        $tot_masuk=$tot_masuk+$pendapatan;
                         $cRet .="<tr>
                                    <td width=\"10%\" >&nbsp;$bulan</td>
                                    <td width=\"20%\" align=\"right\">Rp".number_format($pendapatan,2,',','.')."</td>
                                    <td width=\"10%\">$bulan</td>
                                    <td width=\"20%\" align=\"right\">Rp".number_format($nilai_keluar,2,',','.')."</td>
                                    
                                </tr>";

                    }
                        $cRet .="<tr>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_masuk,2,',','.')."</td>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_keluar,2,',','.')."</td>
                                    
                                </tr>";

                                $cRet .="</table></td>
                                 </tr>";
                                 } else{$cRet .="<table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"2\" cellpadding=\"4\">
                                <tr>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Realisasi Penerimaan per Bulan</b></td>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Penarikan Dana per Bulan</b></td>
                                    <td rowspan=\"15\" width=\"40%\" align=\"center\">$daerah ,$tanggal_ttd <br />$jabatan <br/> <br><br /><br /><br /><br/>
                                             <br/>$nama
                                    <br>$pangkat 
                                    <br>NIP. $nip
<br/><br/>
                                    <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                            Drs. ALFIAN, MM<br/>
                                            NIP. 196602101986031011

                                    </td>
                                </tr>";
                                
                        $tot_keluar=0;
                        $tot_masuk=0;
                $sqltw="SELECT bulan,sum(nilai_susun)as nilai_susun,(select sum(nilai_susun) from trdskpd_ro a where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,1)='4' and z.bulan=a.bulan) as pendapatan from trdskpd_ro z where kd_skpd='$id' and left(kd_rek6,1)='5' group by kd_skpd,bulan order by bulan";
                        $sqltw=$this->db->query($sqltw);
                     foreach ($sqltw->result() as $rowtw)
                    {
                        $bulan=$this->rka_model->getBulan($rowtw->bulan);
                        $nilai_keluar=$rowtw->nilai_susun;
                        $tot_keluar=$tot_keluar+$nilai_keluar;
                        $pendapatan=$rowtw->pendapatan;
                        $tot_masuk=$tot_masuk+$pendapatan;
                         $cRet .="<tr>
                                    <td>&nbsp;$bulan</td>
                                    <td align=\"right\">Rp ".number_format($pendapatan,2,',','.')."</td>
                                    <td>$bulan</td>
                                    <td align=\"right\">Rp ".number_format($nilai_keluar,2,',','.')."</td>
                                    
                                </tr>";

                    }
                        $cRet .="<tr>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_masuk,2,',','.')."</td>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_keluar,2,',','.')."</td>
                                    
                                </tr>";

                                $cRet .="</table></td>
                                 </tr>";
                                 
                                 }
                                 
                         $cRet    .= "</table>";
            

            
                  
            // $cRet    .= "</table>";
            $data['prev']= $cRet;    
            //$this->_mpdf('',$cRet,10,10,10,0);
            $judul         = 'DPA SKPD';
            //$this->template->load('template','master/fungsi/list_preview',$data);
            switch($cetak) { 
            case 1;
                 $this->support->_mpdf1('',$cRet,5,5,5,'0');
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
            echo ("<title>DPA SKPD</title>");
            echo($cRet);
            break;
            }
                    
        }

        //Pengesahan DPA
        function pengesahan_dpa()
    {
        $data['page_title']= 'Pengesahan DPA & DPPA';
        $this->template->set('title', 'Pengesahan DPA & DPPA');   
        $this->template->load('template','anggaran/dpa/pengesahan_dpa',$data) ; 
    }
function load_pengesahan_dpa(){
        $result = array();
        $row = array();
        $id  = $this->session->userdata('pcUser');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="where kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";
        if ($kriteria <> ''){                               
            $where="where (upper(kd_skpd) like upper('%$kriteria%') or no_dpa like'%$kriteria%') and kd_skpd IN 
                    (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";            
        }
        
        $sql = "SELECT count(*) as tot from trhrka $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT * from trhrka $where order by kd_skpd ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,
                        'kd_skpd' => $resulte['kd_skpd'],        
                        'statu' => $resulte['status'],
                        'status_sempurna' => $resulte['status_sempurna'],
                        'status_ubah' => $resulte['status_ubah'],
                        'no_dpa' => $resulte['no_dpa'],        
                        'tgl_dpa' => $resulte['tgl_dpa'],
                        'no_dpa_ubah' => $resulte['no_dpa_ubah'],
                        'tgl_dpa_ubah' => $resulte['tgl_dpa_ubah'],
                        //'status_sempurna' => $resulte['status_sempurna'],
                        'no_dpa_sempurna' => $resulte['no_dpa_sempurna'],
                        'tgl_dpa_sempurna' => $resulte['tgl_dpa_sempurna'],
                        'status_rancang' => $resulte['status_rancang'],
                        'tgl_dpa_rancang' => $resulte['tgl_dpa_rancang'],
                        'status_sempurna2' => $resulte['status_sempurna2'],
                        'status_sempurna3' => $resulte['status_sempurna3'],
                        'status_sempurna4' => $resulte['status_sempurna4'],
                        'status_sempurna5' => $resulte['status_sempurna5'],
                        'status_sempurna6' => $resulte['status_sempurna6'],
                        'tgldpa_sempurna2' => $resulte['tgldpa_sempurna2'],
                        'tgldpa_sempurna3' => $resulte['tgldpa_sempurna3'],
                        'tgldpa_sempurna4' => $resulte['tgldpa_sempurna4'],
                        'tgldpa_sempurna5' => $resulte['tgldpa_sempurna5'],
                        'tgldpa_sempurna6' => $resulte['tgldpa_sempurna6']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);     
    }

    function simpan_pengesahan(){
        $kdskpd = $this->input->post('kdskpd');
        $sdpa = $this->input->post('stdpa');
        $sdppa = $this->input->post('stdppa');
        $nodpa = $this->input->post('no');
        $tanggal1 = $this->input->post('tgl');
        $nodppa = $this->input->post('no2');
        $tanggal2 = $this->input->post('tgl2');
        $sdpasempurna = $this->input->post('stsempurna');
        $nosempurna = $this->input->post('no3');
        $tanggal3 = $this->input->post('tgl3');
        
        
        $sql2 = "UPDATE trhrka  set status='$sdpa',status_sempurna='$sdpasempurna',status_ubah='$sdppa',no_dpa='$nodpa',tgl_dpa='$tanggal1',
no_dpa_ubah='$nodppa',tgl_dpa_ubah='$tanggal2',no_dpa_sempurna='$nosempurna',tgl_dpa_sempurna='$tanggal3' where kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);             

        $sql2 = "update [user] set kunci='1' where bidang='4' and kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);             
     }



     function dpa_pendapatan_penetapan()
        {
            $data['page_title']= 'CETAK';
            $this->template->set('title', 'Cetak DPA Pendapatan Penyusunan');   
            $this->template->load('template','anggaran/dpa/dpa_pendapatan_penetapan',$data) ; 
        }


        function preview_pendapatan_penetapan(){
            $id = $this->uri->segment(2);
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttds1= $_REQUEST['ttd1'];
            $ttds2= $_REQUEST['ttd2'];
            $ttd1 = str_replace('a',' ',$ttds1); 
            $ttd2 = str_replace('a',' ',$ttds2); 
            
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
            $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                    }
            $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,17) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
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


            $sqlsc="SELECT tgl_rka,provinsi,kabtitle,daerah,thn_ang FROM sclient where kd_skpd= '$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = $this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kabtitle;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND nip='$ttd1'  ";
                     $sqlttd1=$this->db->query($sqlttd1);
                     foreach ($sqlttd1->result() as $rowttd)
                    {
                        $nip=$rowttd->nip;                    
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        $pangkat  = $rowttd->pangkat;
                    }
                    
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND nip='$ttd2'  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip;                    
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                        $pangkat2  = $rowttd2->pangkat;
                    }
            
            
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:14px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr> 
                             <td width=\"80%\" align=\"center\"><strong>DOKUMEN PELAKSANAAN ANGGARAN <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"2\" align=\"center\"><strong>Formulir
<br>DPA-PENDAPATAN
<br>SKPD  </strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\">$kab <br />Tahun Anggaran $thn </td>
                        </tr>

                      </table>";
            $sqldns="SELECT no_dpa,tgl_dpa from trhrka WHERE  kd_skpd='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdpa)
                    {
                        $no_dpa =$rowdpa->no_dpa;                    
                        $tgl_dpa= $rowdpa->tgl_dpa;
                    }


            if (substr($id,18,4)=='0000'){
                $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                        <tr>
                            <td style=\"border-left: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black;\">Nomor DPA</td>
                            <td style=\"border-right: solid 1px black;border-top: solid 1px black;border-bottom: solid 1px black;\">: $no_dpa</td>
                        </tr>
                        <tr>
                            <td style=\"border-left: solid 1px black;border-bottom: solid 1px black;\">Organisasi</td>
                            <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\">: $kd_skpd - $nm_skpd</td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ align=\"center\" style=\"border-right: solid 1px black;border-left: solid 1px black;\">Rincian Kerja Anggaran Pendapatan Satuan Kerja Perangkat Daerah</td>
                        </tr>
                    </table>";
            }else{
                $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"0\">
                         <tr>
                            <td style=\"border-left: solid 1px black;border-top: solid 1px black;\">Nomor DPA</td>
                            <td style=\"border-right: solid 1px black;border-top: solid 1px black;\">: $no_dpa</td>
                        </tr>
                        <tr>
                            <td width=\"20%\" style=\"border-left: solid 1px black;\">Organisasi </td>
                            <td width=\"80%\" style=\"border-right: solid 1px black;\">: $kd_org.0000 - ".$this->rka_model->get_nama($kd_org,'nm_org','ms_organisasi','left(kd_org,17)')."</td>
                        </tr>
                        <tr>
                            <td style=\" border-left: solid 1px black;border-bottom: solid 1px black;\">Sub Organisasi</td>
                            <td style=\"border-right: solid 1px black;border-bottom: solid 1px black;\">: $kd_skpd - $nm_skpd</td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ align=\"center\" style=\"border-right: solid 1px black;border-left: solid 1px black;\">Ringkasan Dokumen Pelaksanaan Anggaran Pendapatan dan Belanja Daerah<br />
Satuan Kerja Perangkat Daerah</td>
                        </tr>
                    </table>";
            }
            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                         <thead>                       
                            <tr><td rowspan=\"2\"  width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>                            
                                <td rowspan=\"2\"  width=\"40%\" align=\"center\"><b>Uraian</b></td>
                                <td colspan=\"3\"  width=\"30%\" align=\"center\"><b>Rincian Perhitungan</b></td>
                                <td rowspan=\"2\"  width=\"20%\" align=\"center\"><b>Jumlah(Rp.)</b></td></tr>
                            <tr>
                                <td width=\"8%\"  align=\"center\">Volume</td>
                                <td width=\"8%\"  align=\"center\">Satuan</td>
                                <td width=\"14%\"  align=\"center\">Harga</td>
                            </tr>    
                         </thead>
                            ";
                     $sql1="SELECT * FROM(
    SELECT LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,'0' AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka a 
    INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, '0' AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'2' AS id FROM trdrka a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama,'0' AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,'0' AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,'0' AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM trdrka a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5 
    UNION ALL 
    SELECT a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,'0' AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(a.kd_rek6,1)='4' AND left(a.kd_skpd,17)='$kd_org' GROUP BY a.kd_rek6,b.nm_rek6 
    UNION ALL 
    -- SELECT RIGHT(a.no_trdrka,12) AS rek1,' 'AS rek,a.uraian AS nama,a.volume1 AS volume,a.satuan1 AS satuan, a.harga AS harga,a.total AS nilai,'7' AS id FROM trdpo a WHERE LEFT(a.no_trdrka,22)='$id' 
    -- AND left(kd_rek6,1)='4'
    SELECT RIGHT(a.no_trdrka,12)+ket_bl_teks AS rek1,' 'AS rek,a.uraian AS nama,a.koefisien AS volume,a.satuan1 AS satuan, a.harga AS harga,
    a.total AS nilai,'7' AS id FROM trdpo a WHERE LEFT(a.no_trdrka,22)='$id' 
    AND left(kd_rek6,1)='4'  and header='1' and ket_bl_teks <>uraian
    UNION
    SELECT RIGHT(a.no_trdrka,12)+ket_bl_teks AS rek1,' 'AS rek,a.uraian AS nama,a.koefisien AS volume,a.satuan1 AS satuan, a.harga AS harga,
    a.total AS nilai,'8' AS id FROM trdpo a WHERE LEFT(a.no_trdrka,22)='$id' 
    AND left(kd_rek6,1)='4'  and header='1' and ket_bl_teks = uraian
    UNION
    SELECT RIGHT(a.no_trdrka,12)+ket_bl_teks AS rek1,' 'AS rek,a.uraian AS nama,a.koefisien AS volume,a.satuan1 AS satuan, a.harga AS harga,
    a.total AS nilai,'9' AS id FROM trdpo a WHERE LEFT(a.no_trdrka,22)='$id' 
    AND left(kd_rek6,1)='4' and header='0'  
    ) a ORDER BY a.rek1,a.id";
                     
                    $query = $this->db->query($sql1);
                      if ($query->num_rows() > 0){                                  
                   
                                                     
                    foreach ($query->result() as $row)
                    {
                        $rek=$row->rek;
                        $reke=$this->dotrek($rek);
                        $uraian=$row->nama;
                        $volum=$row->volume;
                        $sat=$row->satuan;
                        $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                        $nila= number_format($row->nilai,"2",",",".");
                       
                            
                        if($reke!=' '){
                            $volum = '';
                        }

                        if ($row->id<7){
                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><b>$reke</b></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\"><b>$uraian</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\"><b>$volum</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\"><b>$sat</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\"><b>$hrg</b></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nila</b></td></tr>
                                         ";

                        }else if ($row->id==7){
                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><b>$reke</b></td>                                     
                                         <td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\"><b>:: $uraian</b></td>
                                         </tr>
                                         ";

                        }else if ($row->id==8){

                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><b>$reke</b></td>                                     
                                         <td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\"><b>::: $uraian</b></td>
                                         </tr>
                                         ";

                        }else{

                             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$reke</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">$volum</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">$sat</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">$hrg</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nila</td></tr>
                                         ";

                        }

                            
                        
                        
                    }
                          }else{
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">4</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">PENDAPATAN</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".number_format(0,"2",",",".")."</td></tr>
                                         ";
                        
                    }


                       $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align=\"right\">&nbsp;</td>
                                 </tr>";
                     $sqltp="SELECT SUM(nilai) AS totp FROM trdrka WHERE LEFT(kd_rek6,1)='4' AND left(kd_skpd,17)='$kd_org'";
                        $sqlp=$this->db->query($sqltp);
                     foreach ($sqlp->result() as $rowp)
                    {
                       $totp=number_format($rowp->totp,"2",",",".");
                       
                        $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pendapatan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$totp</td></tr>";
                     }
                    $kd_ttd=substr($id,18,4);
                     $kd_kepala=substr($id,0,7);
                    if (($kd_ttd=='0000')){
                        $cRet .="
                        <tr>
                        <td colspan=\"6\">
                        <table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"2\" cellpadding=\"4\">
                                <tr>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Realisasi Penerimaan per Bulan</b></td>
                                    <td rowspan=\"15\" width=\"70%\" align=\"center\">$daerah ,$tanggal_ttd 
                                    <br />Pengguna Anggaran <br/> 
                                    $jabatan <br/> <br><br /><br /><br /><br/>
                                             <br/>$nama
                                    <br>$pangkat 
                                    <br>NIP. $nip
<br/><br/>
                                    <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                            Drs. ALFIAN, MM<br/>
                                            NIP. 196602101986031011

                                    </td>
                                </tr>";
                                
                        $tot_keluar=0;
                        $tot_masuk=0;
                $sqltw="SELECT bulan,sum(nilai_susun)as nilai_susun,(select sum(nilai_susun) from trdskpd_ro a where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,1)='4' and z.bulan=a.bulan) as pendapatan from trdskpd_ro z where kd_skpd='$id' and left(kd_rek6,1)='5' group by kd_skpd,bulan order by bulan";
                        $sqltw=$this->db->query($sqltw);
                     foreach ($sqltw->result() as $rowtw)
                    {
                        $bulan=$this->rka_model->getBulan($rowtw->bulan);
                        $nilai_keluar=$rowtw->nilai_susun;
                        $tot_keluar=$tot_keluar+$nilai_keluar;
                        $pendapatan=$rowtw->pendapatan;
                        $tot_masuk=$tot_masuk+$pendapatan;
                         $cRet .="<tr>
                                    <td>&nbsp;$bulan</td>
                                    <td align=\"right\">Rp ".number_format($pendapatan,2,',','.')."</td>
                                    
                                    
                                </tr>";

                    }
                        $cRet .="<tr>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_masuk,2,',','.')."</td>
                                    
                                </tr>";

                                $cRet .="</table></td></tr>";
                                 } else{

                                    $cRet .="<tr>
                        <td colspan=\"6\"><table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"left\" border=\"1\" cellspacing=\"2\" cellpadding=\"4\">
                                <tr>
                                    <td align=\"center\" colspan=\"2\"><b>Rencana Realisasi Penerimaan per Bulan</b></td>
                                    <td rowspan=\"15\" width=\"40%\" align=\"center\">$daerah ,$tanggal_ttd <br />$jabatan <br/> <br><br /><br /><br /><br/>
                                             <br/>$nama
                                    <br>$pangkat 
                                    <br>NIP. $nip
<br/><br/>
                                    <br />Mengesahkan,<br>
                                            PPKD
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                             <br/>
                                            Drs. ALFIAN, MM<br/>
                                            NIP. 196602101986031011

                                    </td>
                                </tr>";
                                
                        $tot_keluar=0;
                        $tot_masuk=0;
                $sqltw="SELECT bulan,sum(nilai_susun)as nilai_susun,(select sum(nilai_susun) from trdskpd_ro a where z.kd_skpd=a.kd_skpd and left(a.kd_rek6,1)='4' and z.bulan=a.bulan) as pendapatan from trdskpd_ro z where kd_skpd='$id' and left(kd_rek6,1)='5' group by kd_skpd,bulan order by bulan";
                        $sqltw=$this->db->query($sqltw);
                     foreach ($sqltw->result() as $rowtw)
                    {
                        $bulan=$this->rka_model->getBulan($rowtw->bulan);
                        $nilai_keluar=$rowtw->nilai_susun;
                        $tot_keluar=$tot_keluar+$nilai_keluar;
                        $pendapatan=$rowtw->pendapatan;
                        $tot_masuk=$tot_masuk+$pendapatan;
                         $cRet .="<tr>
                                    <td>&nbsp;$bulan</td>
                                    <td align=\"right\">Rp ".number_format($pendapatan,2,',','.')."</td>
                                    
                                </tr>";

                    }
                        $cRet .="<tr>
                                    <td align=\"right\">&nbsp;Jumlah</td>
                                    <td align=\"right\">Rp ".number_format($tot_masuk,2,',','.')."</td>
                                    
                                </tr>";

                                $cRet .="</table> </td></tr>";
                                 
                                 }
                                 $cRet    .= "</table>";
            $data['prev']= $cRet;
            $cetak = $this->uri->segment(3);
            switch($cetak) { 
            case 1;
                 $this->support->_mpdf1('',$cRet,10,10,10,'0');
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
            // echo $cRet;
            //$this->_mpdf('',$cRet,10,10,10,0);        
        }

     // ----------------------------
    }