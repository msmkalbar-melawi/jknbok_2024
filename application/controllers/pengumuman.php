<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Pengumuman extends CI_Controller {

	function __contruct()
	{	
		parent::__construct();
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
	
    function tess(){	
	
		$cRet ='<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="21" align="center" >DAFTAR UNIT KERJA</TD>
					</TR>
					</TABLE>';
			
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No.</td>
                    <td width=\"80%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Nama</td>
				
                </tr>
				</thead>";
				
			
			$no=0;
			$sql = "Select * from nama_skpd_calk ";
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $no=$no+1;
					   $nama = $row->nama_skpd;
					
					 $cRet .='<tr>
							   <td align="center" valign="top" style="font-size:12px">'.$no.'</td> 
							   <td align="left"  valign="top" style="font-size:12px">'.$nama.'</td> 
							</tr>'; 
					}
			
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='Nama_skpd';
			
				echo ("<title>$judul</title>");
				echo $cRet;
				
		}

        function d_file(){
				$j = $this->uri->segment(3);
                $file = "".base_url()."download/DATA_DTH_2019_TINDAK_LANJUT_KPPN.rar".$j;
				$jdl = $j;
                header("Content-Disposition: attachment; filename=" . urlencode($jdl));   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Description: File Transfer");            
                header("Content-Length: " . filesize($file));
                flush(); // this doesn't really matter.
                $fp = fopen($file, "r");
                while (!feof($fp))
                {
                    echo fread($fp, 65536);
                    flush(); // this is essential for large downloads
                } 
                fclose($fp); 
        			
		}		
		
        function d_file1(){	
                $file = "".base_url()."download/";
				$jdl = 'Cara_Input_RKA.pdf';
                header("Content-Disposition: attachment; filename=" . urlencode($jdl));   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Description: File Transfer");            
                header("Content-Length: " . filesize($file));
                flush(); // this doesn't really matter.
                $fp = fopen($file, "r");
                while (!feof($fp))
                {
                    echo fread($fp, 65536);
                    flush(); // this is essential for large downloads
                } 
                fclose($fp); 
        			
		}

		function d_file_word(){
				$j = $this->uri->segment(3);
                $file = "".base_url()."download/".$j;
				$jdl = $j;
              
				header("Content-Disposition: attachment; filename=".urlencode($jdl));
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-word");

                flush(); // this doesn't really matter.
				
                $fp = fopen($file, "r");
                while (!feof($fp))
                {
                    echo fread($fp, 65536);
                    flush(); // this is essential for large downloads
                } 
                fclose($fp); 
        			
		}	
		
		
        function d_file2(){	
                $file = "".base_url()."download/";
				$jdl = 'Cara_Penginputan_Anggaran_Kas.pdf';
                header("Content-Disposition: attachment; filename=" . urlencode($jdl));   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Description: File Transfer");            
                header("Content-Length: " . filesize($file));
                flush(); // this doesn't really matter.
                $fp = fopen($file, "r");
                while (!feof($fp))
                {
                    echo fread($fp, 65536);
                    flush(); // this is essential for large downloads
                } 
                fclose($fp); 
        			
		}
		
		function d_file3(){	
                $file = "".base_url()."download/";
				$jdl = 'Surat_Semester_I.pdf';
                header("Content-Disposition: attachment; filename=" . urlencode($jdl));   
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Description: File Transfer");            
                header("Content-Length: " . filesize($file));
                flush(); // this doesn't really matter.
                $fp = fopen($file, "r");
                while (!feof($fp))
                {
                    echo fread($fp, 65536);
                    flush(); // this is essential for large downloads
                } 
                fclose($fp); 
        			
		}

    function msg1(){	
        $kd_skpd = $this->session->userdata('kdskpd');
		$cRet ='<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="4" align="center" >DAFTAR NO BKU</TD>
					</TR>
					</TABLE>';
			
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">No. Bukti</td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KD Kegiatan</td>
                    <td width=\"55%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KD REK5</td>
                    <td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Total</td>				
                </tr>
				</thead>";
				
			
			$no=0;
			$sql = "select b.no_bukti,c.kd_kegiatan,b.kd_rek5,c.nilai from(
                        select no_bukti,kd_skpd,kd_rek5,COUNT(cek) [cek] from (
                            select no_bukti,kd_skpd,kd_rek5,no_bukti+kd_kegiatan+kd_rek5 [cek] from trdtransout
                            where kd_skpd='$kd_skpd'
                        )as a group by cek,no_bukti,kd_skpd,kd_rek5
                      )as b join trdtransout c on c.no_bukti=b.no_bukti and b.kd_skpd=c.kd_skpd and c.kd_rek5=b.kd_rek5 
                      where cek>1 order by b.kd_skpd,b.no_bukti ";
                    $hasil = $this->db->query($sql);
                    $hnum = $hasil->num_rows();
                    if($hnum>0){
                        foreach ($hasil->result() as $row){
    					   $no = $row->no_bukti;
                           $kdgiat = $row->kd_kegiatan;
                           $kdrek5 = $row->kd_rek5;
    					   $nilai = number_format($row->nilai,"2",",",".");
                           
    					 $cRet .='<tr>
    							   <td align="center" valign="top" style="font-size:12px">'.$no.'</td> 
    							   <td align="left"  valign="top" style="font-size:12px">'.$kdgiat.'</td> 
    							   <td align="left"  valign="top" style="font-size:12px">'.$kdrek5.'</td>
                                   <td align="left"  valign="top" style="font-size:12px">'.$nilai.'</td>  
                                </tr>'; 
    					}
			         }
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='Daftar Transaksi';
			
				echo ("<title>$judul</title>");
				echo $cRet;
				
		}
		

	function msgsdana(){	
        $kd_skpd = $this->session->userdata('kdskpd');
		$cRet ='<TABLE style="border-collapse:collapse" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD colspan="4" align="center" >Daftar Kegiatan Belum Input Sumber Dana</TD>
					</TR>
					</TABLE>';
			
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KD Kegiatan</td>
                    <td width=\"70%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KD REK5</td>		
                </tr>
				</thead>";
				
			
			$no=0;
			$sql = "select kd_skpd,kd_kegiatan,kd_rek5 from trdrka where (sumber1_su='' or nsumber1_su=0) and nilai_sempurna <> 0 and kd_skpd='$kd_skpd'
					order by kd_skpd,kd_kegiatan,kd_rek5";
                    $hasil = $this->db->query($sql);
                    $hnum = $hasil->num_rows();
                    if($hnum>0){
                        foreach ($hasil->result() as $row){
                           $kdgiat = $row->kd_kegiatan;
                           $kdrek5 = $row->kd_rek5;
                           
    					 $cRet .='<tr>
    							   <td align="left"  valign="top" style="font-size:12px">'.$kdgiat.'</td> 
    							   <td align="left"  valign="top" style="font-size:12px">'.$kdrek5.'</td>
                                </tr>'; 
    					}
			         }
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='Daftar Kegiatan Belum Input Sumber Dana';
			
				echo ("<title>$judul</title>");
				echo $cRet;
				
		}

		
		function ctk_register_spj($bulan='',$ctk=''){
		$id   	= $this->session->userdata('kdskpd');	
		$cRet ="";	
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
           <tr>
                <td colspan=\"13\" align=\"center\" style=\"border: solid 1px white;\"><b>REGISTER SPJ<br>".strtoupper($this-> getBulan($bulan))." </b>
                </td>
            </tr>
			</table>";
			
		$cRet .="<table style=\"border-collapse:collapse; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
            <thead>
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"2%\" >No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\"  >Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Tanggal Terima</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Gaji</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >UP/GU/TU</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >LS</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >SPJ</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >BKU</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >Rek. Koran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >BP. Pajak</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >STS</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Keterangan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\"  >Check</td>
            </tr>
			
			</thead>
           ";
         
        
		$csql = "SELECT a.nm_skpd, b.kd_skpd
				,ISNULL(real_up,0) real_up
				,ISNULL(real_gj,0) real_gj
				,ISNULL(real_brg,0) real_brg
				,ISNULL(tgl_terima,'') tgl_terima
				,spj,bku,koran,pajak,sts,ket,cek
				 FROM ms_skpd a 
				LEFT JOIN trhspj_ppkd b ON a.kd_skpd=b.kd_skpd
				WHERE bulan='$bulan' AND b.kd_skpd='$id' ORDER BY kd_skpd
				";
		$no=0;
         $query = $this->db->query($csql);  
         foreach($query->result_array() as $res){
			 $no=$no+1;
			 $kd_skpd = $res['kd_skpd'];
			 $nm_skpd = $res['nm_skpd'];
			 $real_up = $res['real_up'];
			 $real_gj = $res['real_gj'];
			 $real_brg = $res['real_brg'];
			 $tgl_terima = $res['tgl_terima'];
			 $ket = $res['ket'];
			 /*$spj = $res['spj'];
			 $bku = $res['bku'];
			 $koran = $res['koran'];
			 $pajak = $res['pajak'];
			 $sts = $res['sts'];
			 $cek = $res['cek'];*/
			$tanggal = empty($tgl_terima) || $tgl_terima == '1900-01-01' ? '-' :$this->tukd_model->tanggal_ind($tgl_terima);
			$spj =$res['spj']=='1' ? '&#10003;' : '';
			$bku =$res['bku']=='1' ? '&#10003;' : '';
			$koran =$res['koran']=='1' ? '&#10003;' : '';
			$pajak =$res['pajak']=='1' ? '&#10003;' : '';
			$sts =$res['sts']=='1' ? '&#10003;' : '';
			$cek =$res['cek']=='1' ? '&#10003;' : '';

				   $cRet .="<tr>
							<td align='center' >$no</td>
							<td>$nm_skpd</td>
							<td align='center' >$tanggal</td>
							<td align='right' >".number_format($real_gj,"2",",",".")."</td>
							<td align='right' >".number_format($real_up,"2",",",".")."</td>
							<td align='right' >".number_format($real_brg,"2",",",".")."</td>
							<td align='center' >$spj</td>
							<td align='center' >$bku</td>
							<td align='center' >$koran</td>
							<td align='center' >$pajak</td>
							<td align='center' >$sts</td>
							<td>$ket</td>
							<td align='center' >$cek</td>
						</tr>";
            }
            
         $cRet .=" 
         </table>
         ";

			$data['prev']=$cRet; //'JURNAL UMUM';
			 $judul='Reg_SPJ';
			switch ($ctk){
				case 1;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 2;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
				case 3;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
	
	} 
    
    
    

		

}
