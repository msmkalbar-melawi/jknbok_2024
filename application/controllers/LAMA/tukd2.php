<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tukd2 extends CI_Controller {

	function __contruct(){	
		parent::__construct();
	}
	
	
	function spj_periode(){
        $data['page_title']= 'INPUT KOREKSI TRANSAKSI';
        $this->template->set('title', 'INPUT KOREKSI TRANSAKSI');   
        $this->template->load('template','tukd/transaksi2/spj_periode',$data) ; 
    }
	
	function transout_koreksi(){
        $data['page_title']= 'INPUT KOREKSI TRANSAKSI';
        $this->template->set('title', 'INPUT KOREKSI TRANSAKSI');   
        $this->template->load('template','tukd/transaksi2/transout_koreksi',$data) ; 
    }
	
	function transout_koreksi2(){
        $data['page_title']= 'INPUT KOREKSI TRANSAKSI';
        $this->template->set('title', 'INPUT KOREKSI TRANSAKSI');   
        $this->template->load('template','tukd/transaksi2/transout_koreksi2',$data) ; 
    }
	
	function transout_lalu(){
        $data['page_title']= 'INPUT TRANSAKSI TAHUN LALU';
        $this->template->set('title', 'INPUT TRANSAKSI TAHUN LALU');   
        $this->template->load('template','tukd/transaksi2/transout_lalu',$data) ; 
    }
	
	function cetak_jurnal_k(){
        $data['page_title']= 'CETAK JURNAL KOREKSI';
        $this->template->set('title', 'CETAK JURNAL KOREKSI');   
        $this->template->load('template','tukd/transaksi2/ctk_jurnal_koreksi',$data) ; 
    }
	
	function cetak_jurnal_k2(){
        $data['page_title']= 'CETAK JURNAL KOREKSI';
        $this->template->set('title', 'CETAK JURNAL KOREKSI');   
        $this->template->load('template','tukd/transaksi2/ctk_jurnal_koreksi2',$data) ; 
    }
	
	function cetak_pajak(){
        $data['page_title']= 'CETAK PAJAK (FORMAT AKUNTANSI)';
        $this->template->set('title', 'CETAK PAJAK (FORMAT AKUNTANSI)');   
        $this->template->load('template','tukd/transaksi2/pajak',$data) ; 
    }
	
	function reg_cp(){
        $data['page_title']= 'CETAK REGISTER CP (FORMAT AKUNTANSI)';
        $this->template->set('title', 'CETAK REGISTER CP (FORMAT AKUNTANSI)');   
        $this->template->load('template','tukd/transaksi2/register_cp',$data) ; 
    }
	
	function cetak_uyhd_pajak_tlalu(){
        $data['page_title']= 'CETAK UYHD PAJAK TAHUN LALU (FORMAT AKUNTANSI)';
        $this->template->set('title', 'CETAK UYHD PAJAK TAHUN LALU (FORMAT AKUNTANSI)');   
        $this->template->load('template','tukd/transaksi2/uyhd_pajak_tlalu',$data) ; 
    }
	
	 function load_transout_koreksi(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
        $sql = "SELECT ISNULL(MAX(tgl_terima),'2016-01-01') as tgl_terima FROM trhspj_ppkd WHERE cek='1' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res)
        {
         $tgl_terima = $res['tgl_terima'];
		}
	   
        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '3' AND a.kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        
		$sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
		(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '3' AND a.kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '3' AND a.kd_skpd='$kd_skpd' $where order by a.no_bukti)  order by a.no_bukti,kd_skpd";

		/*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
		*/
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketlpj' => $resulte['ketlpj'],                                                                                            
                        'ketspj' => $resulte['ketspj'],                                                                                            
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
	
	function load_transout_koreksi2(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
        $sql = "SELECT ISNULL(MAX(tgl_terima),'2016-01-01') as tgl_terima FROM trhspj_ppkd WHERE cek='1' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res)
        {
         $tgl_terima = $res['tgl_terima'];
		}
	   
        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '5' AND a.kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        
		$sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
		(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '5' AND a.kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '5' AND a.kd_skpd='$kd_skpd' $where order by a.no_bukti)  order by a.no_bukti,kd_skpd";

		/*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
		*/
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketlpj' => $resulte['ketlpj'],                                                                                            
                        'ketspj' => $resulte['ketspj'],                                                                                            
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }

	function load_total_trans_tagih(){
	   $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $no_bukti    = $this->input->post('no_simpan');
       

		$sql = "select total=isnull(spp,0)+isnull(transaksi,0)+isnull(tagih,0) from trskpd a left join
									(           
										select c.kd_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
										where c.kd_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
										and (sp2d_batal<>'1' or sp2d_batal is null ) 
										group by c.kd_kegiatan
									) as d on a.kd_kegiatan=d.kd_kegiatan
									left join 
									(
										SELECT z.kd_kegiatan, SUM(z.transaksi) as transaksi FROM (
										select f.kd_kegiatan,sum(f.nilai) [transaksi]
										from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
										where f.kd_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_kegiatan
										UNION ALL
										SELECT c.kd_kegiatan, SUM(c.nilai) as transaksi FROM  trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd
										WHERE jns_spp IN ('1') 
										AND c.kd_kegiatan = '$kegiatan' 
										AND d.status_validasi='0'
										group by c.kd_kegiatan) z
										group by z.kd_kegiatan
									) g on a.kd_kegiatan=g.kd_kegiatan
									left join
									(
										select i.kd_kegiatan,sum(i.nilai) [tagih] from trhtagih h join trdtagih i 
										on h.no_bukti=i.no_bukti and h.kd_skpd=i.kd_skpd
										where i.kd_kegiatan='$kegiatan' and h.no_bukti<>'$no_bukti' 
										AND h.no_bukti NOT IN (select no_tagih FROM trhspp j INNER JOIN trdspp k ON j.no_spp=k.no_spp AND j.kd_skpd=k.kd_skpd
										where k.kd_kegiatan='$kegiatan' and j.no_tagih<>'$no_bukti') group by i.kd_kegiatan
									)l on a.kd_kegiatan=l.kd_kegiatan
									where a.kd_kegiatan='$kegiatan'";
		
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'total' => number_format($resulte['total'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
	
	function cetak_denda_keterlambatan(){
        $data['page_title']= 'CETAK DENDA KETERLAMBATAN (FORMAT AKUNTANSI)';
        $this->template->set('title', 'CETAK DENDA KETERLAMBATAN (FORMAT AKUNTANSI)');   
        $this->template->load('template','tukd/transaksi2/denda_keterlambatan',$data) ; 
    }
	
	function register_uyhd_pajak_tlalu($lcskpd='',$nbulan='',$ctk='',$ttd1='',$tgl_ctk='',$ttd2='', $spasi=''){
        $ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		
			$cRet ='<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>REGISTER UYHD DAN PAJAK TAHUN LALU</TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala SKPD</TD>
						<TD align="left">: '.$nama.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: '.$nama1.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: '.$this->tukd_model->getBulan($nbulan).'</TD>
					 </TR>
					 </TABLE>';

			$cRet .='<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="'.$spasi.'" cellpadding="'.$spasi.'" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">NO</TD>
                        <TD bgcolor="#CCCCCC" rowspan="2" align="center">Tanggal</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="2" align="center">Penerimaan</TD>
						<TD bgcolor="#CCCCCC" colspan="2" align="center">Penyetoran</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">UYHD Tahun Lalu</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pajak Tahun Lalu</TD>
					  <TD bgcolor="#CCCCCC" align="center">UYHD Tahun Lalu</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pajak Tahun Lalu</TD>
					 </TR>
					 </THEAD>
					 ';
			
				$nbulan_l=$nbulan-1;
			$query = $this->db->query("SELECT sum(trm_uyhd) trm_uyhd, sum(trm_pajak) trm_pajak, sum(str_uyhd) str_uyhd, sum(str_pajak) str_pajak FROM (
							select 1 as nomor, kd_skpd, '$thn-01-02' tanggal, 'Terima UYHD' uraian, sld_awal as trm_uyhd, 0 trm_pajak, 0 as str_uyhd, 0 as str_pajak from ms_skpd where kd_skpd='$lcskpd'
							UNION ALL
							select 2 as nomor, kd_skpd, '$thn-01-02' tanggal, 'Terima Pajak' uraian, 0 as trm_uyhd, sld_awalpajak as  trm_pajak, 0 as str_uyhd, 0 as str_pajak  from ms_skpd where kd_skpd='$lcskpd'
							UNION ALL
							select NO_BUKTI as nomor, KD_SKPD, TGL_BUKTI, KET, 0 as trm_uyhd, 0 as trm_pajak,  sum(nilai) as str_uyhd, 0 as str_pajak from TRHOUTLAIN where kd_skpd='$lcskpd' and jns_beban<>7 
							group by NO_BUKTI, KD_SKPD, TGL_BUKTI, KET
							UNION ALL
							select NO_BUKTI as nomor, KD_SKPD, TGL_BUKTI, KET, 0 as trm_uyhd, 0 as trm_pajak, 0 as str_uyhd, sum(nilai) as str_pajak from TRHOUTLAIN where kd_skpd='$lcskpd' and jns_beban=7
							group by NO_BUKTI, KD_SKPD, TGL_BUKTI, KET ) a
							where month(a.tanggal)<='$nbulan_l'");  	
			
				
				foreach ($query->result() as $row) {
                    $trm_uyhd_l =$row->trm_uyhd;
                    $trm_pajak_l =$row->trm_pajak;
                    $str_uyhd_l =$row->str_uyhd;
                    $str_pajak_l =$row->str_pajak;
					$jumlah_l=$trm_uyhd_l+$trm_pajak_l-$str_uyhd_l-$str_pajak_l;
					$jumlaha_l=$trm_uyhd_l+$trm_pajak_l-$str_uyhd_l-$str_pajak_l;;
					
				$cRet .='<TR>
								<TD colspan="3" align="right" >Saldo Lalu</TD>
                                <TD align="left" ></TD>
								<TD align="left" ></TD>								
								<TD align="right" ></TD>
								<TD align="right" ></TD>
								<TD align="right" >'.number_format($jumlaha_l,"2",",",".").'</TD>
							 </TR>';	
				
				}	
				
			$query = $this->db->query("SELECT * FROM (
							select 1 as nomor, kd_skpd, '$thn-01-02' tanggal, 'Terima UYHD' uraian, sld_awal as trm_uyhd, 0 trm_pajak, 0 as str_uyhd, 0 as str_pajak from ms_skpd where kd_skpd='$lcskpd'
							UNION ALL
							select 2 as nomor, kd_skpd, '$thn-01-02' tanggal, 'Terima Pajak' uraian, 0 as trm_uyhd, sld_awalpajak as  trm_pajak, 0 as str_uyhd, 0 as str_pajak  from ms_skpd where kd_skpd='$lcskpd'
							UNION ALL
							select NO_BUKTI as nomor, KD_SKPD, TGL_BUKTI, KET, 0 as trm_uyhd, 0 as trm_pajak,  sum(nilai) as str_uyhd, 0 as str_pajak from TRHOUTLAIN where kd_skpd='$lcskpd' and jns_beban<>7 
							group by NO_BUKTI, KD_SKPD, TGL_BUKTI, KET
							UNION ALL
							select NO_BUKTI as nomor, KD_SKPD, TGL_BUKTI, KET, 0 as trm_uyhd, 0 as trm_pajak, 0 as str_uyhd, sum(nilai) as str_pajak from TRHOUTLAIN where kd_skpd='$lcskpd' and jns_beban=7
							group by NO_BUKTI, KD_SKPD, TGL_BUKTI, KET ) a
							where month(a.tanggal)='$nbulan'
							order by CAST(nomor as int)");  	
			
				$no = 1;
				$jumlaha=$jumlaha_l;
				$trm_uyhd_t=0;
				$trm_pajak_t=0;
				$str_uyhd_t=0;
				$str_pajak_t=0;
			
			
				foreach ($query->result() as $row) {
                    $nomor = $row->nomor; 
                    $kd_skpd = $row->kd_skpd;                   
                    $tanggal = $row->tanggal;
                    $uraian =$row->uraian;
                    $trm_uyhd =$row->trm_uyhd;
                    $trm_pajak =$row->trm_pajak;
                    $str_uyhd =$row->str_uyhd;
                    $str_pajak =$row->str_pajak;
					$jumlah=$trm_uyhd+$trm_pajak-$str_uyhd-$str_pajak;
					$jumlaha=$jumlaha+$jumlah;
					
					$trm_uyhd_t=$trm_uyhd_t+$trm_uyhd;
					$trm_pajak_t=$trm_pajak_t+$trm_pajak;
					$str_uyhd_t=$str_uyhd_t+$str_uyhd;
					$str_pajak_t=$str_pajak_t+$str_pajak;
					
					//$nomor=$no++;
				$cRet .='<TR>
								<TD align="center" >'.$nomor.'</TD>
                                <TD align="left" >'.$this->tukd_model->tanggal_ind($tanggal).'</TD>
								<TD align="left" >'.$uraian.'</TD>								
								<TD align="right" >'.number_format($trm_uyhd,"2",",",".").'</TD>
								<TD align="right" >'.number_format($trm_pajak,"2",",",".").'</TD>
								<TD align="right" >'.number_format($str_uyhd,"2",",",".").'</TD>
								<TD align="right" >'.number_format($str_pajak,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jumlaha,"2",",",".").'</TD>
							 </TR>';	
				
				}
			
			$cRet .='<TR>
								<TD colspan="3" align="right" >Jumlah bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
                                <TD align="right" >'.number_format($trm_uyhd_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($trm_pajak_t,"2",",",".").'</TD>								
								<TD align="right" >'.number_format($str_uyhd_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($str_pajak_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($trm_uyhd_t+$trm_pajak_t-$str_uyhd_t-$str_pajak_t,"2",",",".").'</TD>
							 </TR>';	
							 
			$cRet .='<TR>
								<TD colspan="3" align="right" >Jumlah sampai bulan Sebelumnya</TD>
                                <TD align="right" >'.number_format($trm_uyhd_l,"2",",",".").'</TD>
								<TD align="right" >'.number_format($trm_pajak_l,"2",",",".").'</TD>								
								<TD align="right" >'.number_format($str_uyhd_l,"2",",",".").'</TD>
								<TD align="right" >'.number_format($str_pajak_l,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jumlah_l,"2",",",".").'</TD>
							 </TR>';

			$cRet .='<TR>
								<TD colspan="3" align="right" >Jumlah sampai bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
                                <TD align="right" >'.number_format($trm_uyhd_l+$trm_uyhd_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($trm_pajak_l+$trm_pajak_t,"2",",",".").'</TD>								
								<TD align="right" >'.number_format($str_uyhd_l+$str_uyhd_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($str_pajak_l+$str_pajak_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jumlah_l+$trm_uyhd_t+$trm_pajak_t-$str_uyhd_t-$str_pajak_t,"2",",",".").'</TD>
							 </TR>';	
							 
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$jabatan.'</TD>
						<TD align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>'.$nama.' </u> <br> '.$pangkat.'</TD>
						<TD align="center" ><u>'.$nama1.'</u> <br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD align="center" >NIP. '.$nip.'</TD>
						<TD align="center" >NIP. '.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= 'Register uyhd dan pajak tahun lalu';
	switch ($ctk)
        {
            case 0;
			 echo ("<title> Register Uyhd dan Pajak Tahun Lalu</title>");
				echo $cRet;
				break;
            case 1;
			$this->_mpdf('',$cRet,10,10,10,'L',1,'');
               break;
		}
	}

	
	function load_trskpd_koreksi() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        
        $jns_beban='';
        $cgiat = '';
        if ($jenis ==4){
            $jns_beban = "and a.jns_kegiatan='51'";
        }
		else{
			$jns_beban = "and a.jns_kegiatan='52'";
		}
        if ($giat !=''){                               
            $cgiat = " and a.kd_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        $sql = "SELECT DISTINCT a.kd_kegiatan,a.nm_kegiatan FROM trdtransout a 
                WHERE a.kd_skpd='$cskpd' $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_kegiatan) LIKE UPPER('%$lccr%'))";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_kegiatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}
	
	function load_sp2d_koreksi(){
       //$beban='',$giat=''
       $beban   = $this->input->post('jenis');
       $giat    = $this->input->post('giat');
       $kode    = $this->input->post('kd');
       $bukti   = $this->input->post('bukti');
       $where = '';
      
        $kriteria = $this->input->post('q');
            $sql = "SELECT DISTINCT a.no_sp2d
                    FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_skpd = '$kode' and b.jns_spp='$beban' and a.kd_kegiatan='$giat' ORDER BY a.no_sp2d";
       //and UPPER(no_sp2d) LIKE '%$kriteria%'  
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'no_sp2d' => $resulte['no_sp2d']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }
	
	function load_rek2() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
            
       $stsubah =$this->rka_model->get_nama($kode,'status_ubah','trhrka','kd_skpd');
		$stssempurna =$this->rka_model->get_nama($kode,'status_sempurna','trhrka','kd_skpd');
       

        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
		
		
		 /*if (($stsubah==0) && ($stssempurna==0)){
			$field='nilai';		
		}else if (($stsubah==0) && ($stssempurna==1)){
			$field='nilai_sempurna';				
		} else{
			$field='nilai_ubah';
		}*/
			$field='nilai_ubah';
		
        
        if ($jenis=='1'){
            $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd WHERE c.kd_kegiatan = a.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd  AND c.kd_rek5=a.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis') AS lalu,
                    0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, nilai_ubah AS nilai_ubah
                    FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn ";
                    
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd 
					WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
					AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran,
                    0 as nilai_sempurna,
                    0 as nilai_ubah
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
					INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
					INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_kegiatan='$giat' $notIn ";
        }        
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran'],
                        'anggaran_semp' => $resulte['nilai_sempurna'],
                        'anggaran_ubah' => $resulte['nilai_ubah']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}
    
	function load_rek_koreksi() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
		if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
		}		
        $sql = "SELECT a.no_bukti, a.kd_rek5, a.nm_rek5,nilai,isnull(nil_pad,0) [nil_pad],isnull(nil_dak,0) [nil_dak],nil_daknf,
                isnull(nil_dau,0) [nil_dau],isnull(nil_dbhp,0) [nil_dbhp],nil_did 
                FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
				WHERE a.kd_skpd='$kode' AND  b.no_sp2d = '$sp2d' and a.kd_kegiatan='$giat' AND b.jns_spp = '$jenis' $notIn ORDER BY a.no_bukti";
        
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'no_bku' => $resulte['no_bukti'],  
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
						'nilai' =>$resulte['nilai'],
                        'nil_pad' => $resulte['nil_pad'],
                        'nil_dak' => $resulte['nil_dak'],
                        'nil_daknf' => $resulte['nil_daknf'],
                        'nil_dau' => $resulte['nil_dau'],
                        'nil_dbhp' => $resulte['nil_dbhp'],
                        'nil_did' => $resulte['nil_did']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}

	
	function load_rek_koreksi2() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
		if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
		}		
        $sql = "SELECT a.no_bukti, a.kd_rek5, a.nm_rek5,nilai,isnull(nil_pad,0) [nil_pad],isnull(nil_dak,0) [nil_dak],nil_daknf,
                isnull(nil_dau,0) [nil_dau],isnull(nil_dbhp,0) [nil_dbhp],nil_did 
                FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
				WHERE a.kd_skpd='$kode' AND  b.no_sp2d = '$sp2d' and a.kd_kegiatan='$giat' AND b.jns_spp = '$jenis' ORDER BY a.no_bukti";
        
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'no_bku' => $resulte['no_bukti'],  
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
						'nilai' =>$resulte['nilai'],
                        'nil_pad' => $resulte['nil_pad'],
                        'nil_dak' => $resulte['nil_dak'],
                        'nil_daknf' => $resulte['nil_daknf'],
                        'nil_dau' => $resulte['nil_dau'],
                        'nil_dbhp' => $resulte['nil_dbhp'],
                        'nil_did' => $resulte['nil_did']

                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}
    


	function load_rek_koreksi3() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');   
		$nobkuk    = $this->input->post('nobkuk'); 		
        $lccr   = $this->input->post('q');
		if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
		}	
		if($jenis=='1' || $jenis=='3'){
        /*$sql = "SELECT a.kd_rek5, a.nm_rek5 FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
				WHERE a.kd_skpd='$kode' AND  b.no_sp2d = '$sp2d' and a.kd_kegiatan='$giat' AND b.jns_spp = '$jenis' 
				GROUP BY kd_rek5, nm_rek5 
				ORDER BY kd_rek5";
        */
            $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(nilai) FROM 
						(SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout c
						LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
						AND c.kd_skpd = d.kd_skpd
						WHERE
							c.kd_kegiatan = a.kd_kegiatan
						AND d.kd_skpd = a.kd_skpd
						AND c.kd_rek5 = a.kd_rek5
						AND c.no_bukti not in ('$nomor','$nobkuk')
						AND d.jns_spp='$jenis'
						UNION ALL
						SELECT SUM(x.nilai) as nilai FROM trdspp x
						INNER JOIN trhspp y 
						ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
						WHERE
							x.kd_kegiatan = a.kd_kegiatan
						AND x.kd_skpd = a.kd_skpd
						AND x.kd_rek5 = a.kd_rek5
						AND y.jns_spp IN ('3','4','5','6')
						AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
						UNION ALL
						SELECT SUM(nilai) as nilai FROM trdtagih t 
						INNER JOIN trhtagih u 
						ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
						WHERE 
						t.kd_kegiatan = a.kd_kegiatan
						AND u.kd_skpd = a.kd_skpd
						AND t.kd_rek = a.kd_rek5
						AND u.no_bukti 
						NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
						)r) AS lalu,
						0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, nilai_ubah AS nilai_ubah
						FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_skpd = '$kode' ";

        }else{
		/*$sql = "SELECT b.kd_rek5, b.nm_rek5 
				FROM trhsp2d a INNER JOIN trdspp b ON a.kd_skpd=b.kd_skpd AND a.no_spp=b.no_spp
				WHERE a.kd_skpd='$kode' AND  a.no_sp2d = '$sp2d' and b.kd_kegiatan='$giat' AND a.jns_spp = '$jenis' 
				GROUP BY kd_rek5, nm_rek5 
				ORDER BY kd_rek5";
		*/
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd 
					WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
					AND c.kd_rek5=b.kd_rek5 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran,
                    0 as nilai_sempurna,
                    0 as nilai_ubah
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
					INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
					INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_kegiatan='$giat' $notIn ";

        }
		
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        //'no_bku' => $resulte['no_bukti'],  
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
						//'nilai' =>$resulte['nilai']
                         'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran'],
                        'anggaran_semp' => $resulte['nilai_sempurna'],
                        'anggaran_ubah' => $resulte['nilai_ubah']

                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}
    
    

	
    function simpan_transout_koreksi(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $tgl_koreksi = $this->input->post('ctgl_koreksi');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
			$asg = $this->db->query($sql);
			
            if ($asg){
				$sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'
                        insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','3','')";
                $asg = $this->db->query($sql);
				} else {
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
				}
            
        }elseif($tabel == 'trdtransout') {
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'
                            insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,
                            nil_pad,nil_dak,nil_daknf,nil_dau,nil_dbhp,nil_did)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }

	
    function simpan_transout_koreksi_edit(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku   = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $tgl_koreksi   = $this->input->post('ctgl_koreksi');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
           
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$no_bku'";
			$asg = $this->db->query($sql);
			

            if ($asg){
                
				$sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$no_bku'
                        insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','3','')";
                $asg = $this->db->query($sql);

				             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }else if($tabel == 'trdtransout') {
           
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$no_bku' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "delete from trdtransout where no_bukti='$no_bku' AND kd_skpd='$skpd'
                            insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,
                            nil_pad,nil_dak,nil_daknf,nil_dau,nil_dbhp,nil_did)";  
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }


	function simpan_transout_koreksi2(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $tgl_koreksi   = $this->input->post('ctgl_koreksi');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
			$asg = $this->db->query($sql);
			
            if ($asg){
				$sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','5','')";
                $asg = $this->db->query($sql);
				} else {
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
				}
            
        }elseif($tabel == 'trdtransout') {
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,
                            nil_pad,nil_dak,nil_daknf,nil_dau,nil_dbhp,nil_did)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
	
	
    function simpan_transout_koreksi2_edit(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku   = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $tgl_koreksi   = $this->input->post('ctgl_koreksi');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
           
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$no_bku'";
			$asg = $this->db->query($sql);
			

            if ($asg){
                
				$sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','5','')";
                $asg = $this->db->query($sql);

				             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }else if($tabel == 'trdtransout') {
           
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$no_bku' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd,
                            nil_pad,nil_dak,nil_daknf,nil_dau,nil_dbhp,nil_did)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
	


	
	function ctk_jurnal_koreksi($dcetak='',$dcetak2='',$skpd='',$tgl_ttd='',$ttd1='',$ttd2='',$spasi='',$ctk=''){
   	    $csql11 = " select nm_skpd from ms_skpd where kd_skpd = '$skpd'"; 
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper ($trh1->nm_skpd);
        $tgl=$this->tukd_model->tanggal_format_indonesia($dcetak);
        $tgl2=$this->tukd_model->tanggal_format_indonesia($dcetak2);
		$ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
         $sqlsc = "SELECT kab_kota,daerah FROM sclient WHERE kd_skpd='$skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                 }
		$sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd1' and kode='PPK'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd2' and kode in ('PA','KPA')";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }    
		$cRet ="";	
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>$lcskpd
                </td>
            </tr>
             <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>JURNAL KOREKSI</b>
                </td>
            </tr>
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">PERIODE $tgl  s/d  $tgl2
                </td>
            </tr>
			</table>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
            <thead>
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Tanggal</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Nomor<br>Bukti</b></td>
                <td colspan=\"2\" bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Kegiatan</b></td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Rekening</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Uraian</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>ref</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"2\"><b>Jumlah Rp</b></td>
            </tr>
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Debit</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Kredit</b></td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">2</td>
                <td colspan=\"2\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">3</td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"35%\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\"></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">7</td>
            </tr>
			</thead>
           ";
        /* 
         $csql1 = "select count(*) as tot FROM 
                 trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher and a.kd_unit=b.kd_skpd 
                 where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'"; 
         $rs = $this->db->query($csql1);
         $trh = $rs->row();
         
            
        $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek5,(c.nm_rek64 + case when (pos='0') then '' else ''end) AS nm_rek5,a.debet,a.kredit FROM 
                  trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher join (SELECT kd_rek64,nm_Rek64 from ms_rek5 group by kd_rek64,nm_Rek64) c on a.kd_rek5=c.kd_rek64
                  where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
                  ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek5";   
*/
		$csql = "select a.tgl_bukti,a.no_bukti,b.nilai,b.kd_kegiatan,kd_rek5,nm_rek5 from trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
				WHERE a.kd_skpd='$skpd' AND panjar='3' AND (tgl_bukti BETWEEN '$dcetak' AND '$dcetak2')
				 ORDER BY a.tgl_bukti ASC,a.no_bukti ASC, b.nilai DESC";
				  
         $query = $this->db->query($csql);  
         $cnovoc = '';
         $lcno = 0;
         foreach($query->result_array() as $res){
                $lcno = $lcno + 1;
               
                        if($cnovoc==$res['no_bukti']){
                            $cRet .="<tr>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],16,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],19,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>";
                                if($res['nilai']<0){
                                    $cRet .=" <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai']*-1,"2",",",".")."</td>";
                                }else{$cRet .="<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai'],"2",",",".")."</td>
                                               <td style=\"border-bottom:none;\"></td>";                                    
                                }
                       
                       $cRet .="</tr>";                    
                        }else{
                        $cRet .="<tr>
                                <td style=\"border-bottom:none\">".$this->tukd_model->tanggal_ind($res['tgl_bukti'])."</td>
                                <td style=\"border-bottom:none\">".$res['no_bukti']."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],16,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],19,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>";
                                if($res['nilai']<0){
                                    $cRet .=" <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai']*-1,"2",",",".")."</td>";
                                }else{$cRet .="<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai'],"2",",",".")."</td>
                                               <td style=\"border-bottom:none;\"></td>";                                    
                                }
                       
                       $cRet .="</tr>";
                        }
                        $cnovoc=$res['no_bukti'];
                }
                
            
            
         $cRet .=" <tr>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                    </tr>  
         </table> ";
$cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">Mengetahui,</td>
                <td align=\"center\" width=\"50%\">$daerah, ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">$jabatan1</td>
                <td align=\"center\" width=\"50%\">$jabatan</td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\"><u><b>$nama1</b></u><br>$pangkat1<br>NIP.$nip1</td>
                <td align=\"center\" width=\"50%\"><u><b>$nama</b></u><br>$pangkat<br>NIP.$nip</td>
            </tr>
			</table>
	   ";
			$data['prev']=$cRet; //'JURNAL UMUM';
			if($ctk=='0'){
				echo ("<title>Jurna Koreksi $skpd</title>");
				echo $cRet;
			}else{
			 $this->tukd_model->_mpdf('', $cRet, 5, 5, 5, '0');
			}
			 
            //$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
	
	} 
    
	function load_transout_lalu(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
        $sql = "SELECT ISNULL(MAX(tgl_terima),'2016-01-01') as tgl_terima FROM trhspj_ppkd WHERE cek='1' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res)
        {
         $tgl_terima = $res['tgl_terima'];
		}
	   
        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '4' AND a.kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        
		$sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
		(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '4' AND a.kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '4' AND a.kd_skpd='$kd_skpd' $where order by a.no_bukti)  order by a.no_bukti,kd_skpd";

		/*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
		*/
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketlpj' => $resulte['ketlpj'],                                                                                            
                        'ketspj' => $resulte['ketspj'],                                                                                            
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
	
	function simpan_transout_lalu(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     =date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
			$asg = $this->db->query($sql);
			
            if ($asg){
				$sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','4','')";
                $asg = $this->db->query($sql);
				} else {
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
				}
            
        }elseif($tabel == 'trdtransout') {
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
	
    function simpan_transout_lalu_edit(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku   = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
           
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$no_bku'";
			$asg = $this->db->query($sql);
			

            if ($asg){
                
				$sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','4','')";
                $asg = $this->db->query($sql);

				             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }else if($tabel == 'trdtransout') {
           
            // Simpan Detail //                       
                $sql = "delete from trdtransout where no_bukti='$no_bku' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
	
	function ctk_jurnal_koreksi2($dcetak='',$dcetak2='',$skpd='',$tgl_ttd='',$ttd1='',$ttd2='',$spasi='',$ctk=''){
   	    $csql11 = " select nm_skpd from ms_skpd where kd_skpd = '$skpd'"; 
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper ($trh1->nm_skpd);
        $tgl=$this->tukd_model->tanggal_format_indonesia($dcetak);
        $tgl2=$this->tukd_model->tanggal_format_indonesia($dcetak2);
		$ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
         $sqlsc = "SELECT kab_kota,daerah FROM sclient WHERE kd_skpd='$skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                 }
		$sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd1' and kode='PPK'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd2' and kode in ('PA','KPA')";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }    
		$cRet ="";	
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>$lcskpd
                </td>
            </tr>
             <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>JURNAL KOREKSI</b>
                </td>
            </tr>
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">PERIODE $tgl  s/d  $tgl2
                </td>
            </tr>
			</table>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
            <thead>
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Tanggal</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Nomor<br>Bukti</b></td>
                <td colspan=\"2\" bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Kegiatan</b></td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Rekening</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Uraian</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>ref</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"2\"><b>Jumlah Rp</b></td>
            </tr>
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Debit</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Kredit</b></td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">2</td>
                <td colspan=\"2\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"5%\">3</td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"42%\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\"></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">7</td>
            </tr>
			</thead>
           ";
         
         /*$csql1 = "select count(*) as tot FROM 
                 trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher and a.kd_unit=b.kd_skpd 
                 where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'"; 
         $rs = $this->db->query($csql1);
         $trh = $rs->row();
         
            
        $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek5,(c.nm_rek64 + case when (pos='0') then '' else ''end) AS nm_rek5,a.debet,a.kredit FROM 
                  trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher join (SELECT kd_rek64,nm_Rek64 from ms_rek5 group by kd_rek64,nm_Rek64) c on a.kd_rek5=c.kd_rek64
                  where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
                  ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek5";   
*/
		$csql = "select a.tgl_bukti,a.no_bukti,b.nilai,b.kd_kegiatan,kd_rek5,nm_rek5,
				(SELECT SUM (nilai) FROM trdtransout WHERE kd_skpd = '$skpd' AND panjar = '5' AND no_bukti=a.no_bukti) as total
				FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
				WHERE a.kd_skpd='$skpd' AND panjar='5' AND (tgl_bukti BETWEEN '$dcetak' AND '$dcetak2')
				 ORDER BY a.tgl_bukti ASC,a.no_bukti ASC, b.nilai DESC";
				  
         $query = $this->db->query($csql);  
         $cnovoc = '';
         $lcno = 0;
         foreach($query->result_array() as $res){
                $lcno = $lcno + 1;
				if($res['total']>0){
                        if($res['nilai']<0){
                            $cRet .="<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">03</td>
                                <td style=\"border-bottom:none;\">01</td>
                                <td style=\"border-bottom:none;\">Kas di Bendahara Pengeluaran</td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
								<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['total'],"2",",",".")."</td>
								</tr>
								<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],16,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],19,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>
								<td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai']*-1,"2",",",".")."</td> </tr>
								";								
								}else{
								 $cRet .="<tr>
                                <td style=\"border-bottom:none;border-top:none;\">".$this->tukd_model->tanggal_ind($res['tgl_bukti'])."</td>
                                <td style=\"border-bottom:none;border-top:none;\">".$res['no_bukti']."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],16,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],19,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>
								<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai'],"2",",",".")."</td>
                                <td style=\"border-bottom:none;\"></td></tr>
								";                                    
                                }
                        $cnovoc=$res['no_bukti'];
				} else{
					//if($cnovoc==$res['no_bukti']){
						if($res['nilai']<0){
                            $cRet .="<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],16,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],19,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>
								<td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai']*-1,"2",",",".")."</td> </tr>
								";								
								}else{
								 $cRet .="<tr>
                                <td style=\"border-bottom:none;border-top:none;\">".$this->tukd_model->tanggal_ind($res['tgl_bukti'])."</td>
                                <td style=\"border-bottom:none;border-top:none;\">".$res['no_bukti']."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],16,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_kegiatan'],19,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>
								<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['nilai'],"2",",",".")."</td>
                                <td style=\"border-bottom:none;\"></td></tr>
								<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">03</td>
                                <td style=\"border-bottom:none;\">01</td>
                                <td style=\"border-bottom:none;\">Kas di Bendahara Pengeluaran</td>
                                <td style=\"border-bottom:none;\"></td>
								<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['total']*-1,"2",",",".")."</td>
                                <td style=\"border-bottom:none;\"></td></tr>
								";                                    
                                }
                        $cnovoc=$res['no_bukti'];
				}
                }
                
            
            
         $cRet .=" <tr>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                    </tr>  
         </table> ";
$cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">Mengetahui,</td>
                <td align=\"center\" width=\"50%\">$daerah, ".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."</td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">$jabatan1</td>
                <td align=\"center\" width=\"50%\">$jabatan</td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
			<tr>
                <td align=\"center\" width=\"50%\"><u><b>$nama1</b></u><br>$pangkat1<br>NIP.$nip1</td>
                <td align=\"center\" width=\"50%\"><u><b>$nama</b></u><br>$pangkat<br>NIP.$nip</td>
            </tr>
			</table>
	   ";
			$data['prev']=$cRet; //'JURNAL UMUM';
			if($ctk=='0'){
				echo ("<title>Jurna Koreksi $skpd</title>");
				echo $cRet;
			}else{
			 $this->tukd_model->_mpdf('', $cRet, 5, 5, 5, '0');
			}
			 
            //$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
	
	} 
    
	function register_pajak($lcskpd='',$nbulan='',$ctk='',$ttd1='',$tgl_ctk='',$ttd2='', $jns='',$spasi=''){
        $ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		if($jns=='1'){
			$judul ="UP/GU/TU";
			$and = "AND jns_spp IN ('1','2','3')";
		} else{
			$judul ='LS';
			$and = "AND jns_spp IN ('4','5','6')";
		}
			$cRet ='<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>REGISTER PAJAK '.$judul.' </TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala SKPD</TD>
						<TD align="left">: '.$nama.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: '.$nama1.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: '.$this->tukd_model->getBulan($nbulan).'</TD>
					 </TR>
					 </TABLE>';

			$cRet .='<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="'.$spasi.'" cellpadding="'.$spasi.'" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">NO</TD>
                        <TD bgcolor="#CCCCCC" rowspan="2" align="center">Tanggal</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="5" align="center">Pajak '.$judul.'</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Pemotongan</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Penyetoran</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">PPN</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH21</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH22</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH23</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH4</TD>
					 </TR>
					 </THEAD>
					 ';
			
				$nbulan_l=$nbulan-1;
				$query = $this->db->query("	SELECT 
					SUM(ppn) as ppn_l
					,SUM(pph21) as pph21_l
					,SUM(pph22) as pph22_l
					,SUM(pph23) as pph23_l
					,SUM(pph4) as pph4_l
					,SUM(terima) as terima_l
					,SUM(setor) as setor_l FROM(
					SELECT 
					SUM(CASE WHEN b.kd_rek5='2130301' THEN b.nilai ELSE 0 END) AS ppn
					,SUM(CASE WHEN b.kd_rek5='2130101' THEN b.nilai ELSE 0 END) AS pph21
					,SUM(CASE WHEN b.kd_rek5='2130201' THEN b.nilai ELSE 0 END) AS pph22
					,SUM(CASE WHEN b.kd_rek5='2130401' THEN b.nilai ELSE 0 END) AS pph23
					,SUM(CASE WHEN b.kd_rek5='2130501' THEN b.nilai ELSE 0 END) AS pph4,
					SUM(b.nilai) as terima,
					0 as setor
					FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
					AND MONTH(a.tgl_bukti)<='$nbulan_l' $and AND b.kd_rek5 IN('2130301','2130101','2130201','2130401','2130501')
					UNION ALL
					SELECT 
					0 AS ppn
					,0 AS pph21
					,0 AS pph22
					,0 AS pph23
					,0 AS pph4,
					0 as terima,
					SUM(b.nilai) as setor
					FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd'
					AND MONTH(a.tgl_bukti)<='$nbulan_l' $and  AND b.kd_rek5 IN('2130301','2130101','2130201','2130401','2130501')) a
					 "); 
					 
				foreach ($query->result() as $row) {	
				$ppn_l =$row->ppn_l;
				$pph21_l =$row->pph21_l;
				$pph22_l =$row->pph22_l;
				$pph23_l =$row->pph23_l;
				$pph4_l =$row->pph4_l;
				$terima_l=$row->terima_l;                    
				$setor_l=$row->setor_l;                    
				$jumlah_l=$terima_l-$setor_l;
				$jumlah_lalu=$terima_l-$setor_l;
				
				$cRet .='<TR>
					<TD colspan="3" align="right" >Saldo Lalu</TD>
					<TD align="right" ></TD>
					<TD align="right" ></TD>
					<TD align="right" ></TD>
					<TD align="right" ></TD>
					<TD align="right" ></TD>
					<TD align="right" ></TD>
					<TD align="right" ></TD>
					<TD align="right" >'.number_format($jumlah_lalu,"2",",",".").'</TD>
				 </TR>';
				
			}		 
			
				$query = $this->db->query("SELECT * FROM(
					SELECT 
					a.no_bukti,tgl_bukti, ket
					,CASE WHEN b.kd_rek5='2130301' THEN b.nilai ELSE 0 END AS ppn
					,CASE WHEN b.kd_rek5='2130101' THEN b.nilai ELSE 0 END AS pph21
					,CASE WHEN b.kd_rek5='2130201' THEN b.nilai ELSE 0 END AS pph22
					,CASE WHEN b.kd_rek5='2130401' THEN b.nilai ELSE 0 END AS pph23
					,CASE WHEN b.kd_rek5='2130501' THEN b.nilai ELSE 0 END AS pph4,
					b.nilai as terima,
					0 as setor
					FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
					AND MONTH(a.tgl_bukti)='$nbulan' $and AND b.kd_rek5 IN('2130301','2130101','2130201','2130401','2130501')
					UNION ALL
					SELECT 
					a.no_bukti,tgl_bukti, ket
					,0 AS ppn
					,0 AS pph21
					,0 AS pph22
					,0 AS pph23
					,0 AS pph4,
					0 as terima,
					b.nilai as setor
					FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd'
					AND MONTH(a.tgl_bukti)='$nbulan' $and  AND b.kd_rek5 IN('2130301','2130101','2130201','2130401','2130501')) a
					ORDER BY CAST(a.no_bukti as int) ");  
				
				$jumlah=$jumlah_lalu;
				$ppn_t=0;
				$pph21_t=0;
				$pph22_t=0;
				$pph23_t=0;
				$pph4_t=0;
				$terima_t=0;
				$setor_t=0;
				foreach ($query->result() as $row) {
                    $bukti = $row->no_bukti; 
                    $tanggal = $row->tgl_bukti;                   
                    $ket = $row->ket;
                    $ppn =$row->ppn;
                    $pph21 =$row->pph21;
                    $pph22 =$row->pph22;
                    $pph23 =$row->pph23;
                    $pph4 =$row->pph4;
                    $terima=$row->terima;                    
                    $setor=$row->setor;                    
					$jumlah=$jumlah+$terima-$setor;
					$ppn_t=$ppn_t+$ppn;
					$pph21_t=$pph21_t+$pph21;
					$pph22_t=$pph22_t+$pph22;
					$pph23_t=$pph23_t+$pph23;
					$pph4_t=$pph4_t+$pph4;
					$terima_t=$terima_t+$terima;
					$setor_t=$setor_t+$setor;
					$cRet .='<TR>
								<TD align="left" >'.$bukti.'</TD>
                                <TD align="left" >'.$this->tukd_model->tanggal_ind($tanggal).'</TD>
								<TD align="left" >'.$ket.'</TD>								
								<TD align="right" >'.number_format($ppn,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph21,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph22,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph23,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph4,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jumlah,"2",",",".").'</TD>
							 </TR>';					
			
				}
				
				
				
				
				
				$cRet .='<TR>
								<TD colspan="3" align="right" >Jumlah bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
								<TD align="right" >'.number_format($ppn_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph21_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph22_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph23_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph4_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima_t-$setor_t,"2",",",".").'</TD>
							 </TR>';
			
				$cRet .='<TR>
										<TD colspan="3" align="right" >Jumlah sampai bulan Sebelumnya</TD>
										<TD align="right" >'.number_format($ppn_l,"2",",",".").'</TD>
										<TD align="right" >'.number_format($pph21_l,"2",",",".").'</TD>
										<TD align="right" >'.number_format($pph22_l,"2",",",".").'</TD>
										<TD align="right" >'.number_format($pph23_l,"2",",",".").'</TD>
										<TD align="right" >'.number_format($pph4_l,"2",",",".").'</TD>
										<TD align="right" >'.number_format($terima_l,"2",",",".").'</TD>
										<TD align="right" >'.number_format($setor_l,"2",",",".").'</TD>
										<TD align="right" >'.number_format($jumlah_l,"2",",",".").'</TD>
									 </TR>';

			
				$cRet .='<TR>
						<TD colspan="3" align="right" >Jumlah sampai bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
						<TD align="right" >'.number_format($ppn_l+$ppn_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($pph21_l+$pph21_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($pph22_l+$pph22_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($pph23_l+$pph23_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($pph4_l+$pph4_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($terima_l+$terima_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($setor_l+$setor_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jumlah_l+$terima_t-$setor_t,"2",",",".").'</TD>
					 </TR>';			
				
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$jabatan.'</TD>
						<TD align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>'.$nama.' </u> <br> '.$pangkat.'</TD>
						<TD align="center" ><u>'.$nama1.'</u> <br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD align="center" >NIP. '.$nip.'</TD>
						<TD align="center" >NIP. '.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= 'Register Pajak';
	switch ($ctk)
        {
            case 0;
			 echo ("<title> Register Pajak $judul</title>");
				echo $cRet;
				break;
            case 1;
			$this->_mpdf('',$cRet,10,10,10,'L',1,'');
               break;
		}
	}

	function register_pajak2($lcskpd='',$nbulan='',$ctk='',$ttd1='',$tgl_ctk='',$ttd2='', $jns='',$spasi=''){
        $ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		
			$cRet ='<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>REGISTER POTONGAN LAINNYA </TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala SKPD</TD>
						<TD align="left">: '.$nama.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: '.$nama1.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: '.$this->tukd_model->getBulan($nbulan).'</TD>
					 </TR>
					 </TABLE>';

			$cRet .='<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="'.$spasi.'" cellpadding="'.$spasi.'" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">NO</TD>
                        <TD bgcolor="#CCCCCC" rowspan="2" align="center">Tanggal</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="7" align="center">Potongan Lainnya</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Pemotongan</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Penyetoran</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">IWP</TD>
					 <TD bgcolor="#CCCCCC" align="center">TAPERUM</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPNPN 2%</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPNPN 3%</TD>
					 <TD bgcolor="#CCCCCC" align="center">JKK</TD>
					 <TD bgcolor="#CCCCCC" align="center">JKM</TD>
					 <TD bgcolor="#CCCCCC" align="center">BPJS</TD>
					 </TR>
					 </THEAD>
					 ';
			

			
				$query = $this->db->query("SELECT * FROM(
						SELECT 
						a.no_bukti,tgl_bukti, ket
						,CASE WHEN b.kd_rek5 in ('2110701','2110702','2110703') THEN b.nilai ELSE 0 END AS iwp
						,CASE WHEN b.kd_rek5='2110501' THEN b.nilai ELSE 0 END AS taperum
						,CASE WHEN b.kd_rek5='2111001' THEN b.nilai ELSE 0 END AS ppnpn2persen
						,CASE WHEN b.kd_rek5='2111101' THEN b.nilai ELSE 0 END AS ppnpn3persen
						,CASE WHEN b.kd_rek5='2111201' THEN b.nilai ELSE 0 END AS jkk
						,CASE WHEN b.kd_rek5='2111301' THEN b.nilai ELSE 0 END AS jkm
						,CASE WHEN b.kd_rek5='2111401' THEN b.nilai ELSE 0 END AS bpjs,
						b.nilai as terima,
						0 as setor
						FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
						AND MONTH(a.tgl_bukti)='$nbulan'  AND b.kd_rek5 IN ('2110701','2110702','2110703','2110501','2111001','2111101','2111201','2111301','2111401')
						UNION ALL
						SELECT 
						a.no_bukti,tgl_bukti, ket
						,0 AS iwp
						,0 AS taperum
						,0 AS ppnpn2persen
						,0 AS ppnpn3persen
						,0 AS jkk
						,0 AS jkm
						,0 AS bpjs
						,0 as terima,
						b.nilai as setor
						FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
						AND MONTH(a.tgl_bukti)='$nbulan' AND b.kd_rek5 IN ('2110701','2110702','2110703','2110501','2111001','2111101','2111201','2111301','2111401')) a
						ORDER BY CAST(a.no_bukti as int)");  
				
				$jumlah=0;
				$iwp_t=0;
				$taperum_t=0;
				$ppnpn2persen_t=0;
				$ppnpn3persen_t=0;
				$jkk_t=0;
				$jkm_t=0;
				$bpjs_t=0;
				$terima_t=0;
				$setor_t=0;
				foreach ($query->result() as $row) {
                     $bukti = $row->no_bukti; 
                    $tanggal = $row->tgl_bukti;                   
                    $ket = $row->ket;
                    $iwp =$row->iwp;
                    $taperum =$row->taperum;
                    $ppnpn2persen =$row->ppnpn2persen;
                    $ppnpn3persen =$row->ppnpn3persen;
                    $jkk =$row->jkk;
                    $jkm =$row->jkm;
                    $bpjs =$row->bpjs;
                    $terima=$row->terima;                    
                    $setor=$row->setor;                    
					$jumlah=$jumlah+$terima-$setor;
					$iwp_t=$iwp_t+$iwp;
					$taperum_t=$taperum_t+$taperum;
					$ppnpn2persen_t=$ppnpn2persen_t+$ppnpn2persen;
					$ppnpn3persen_t=$ppnpn3persen_t+$ppnpn3persen;
					$jkk_t=$jkk_t+$jkk;
					$jkm_t=$jkm_t+$jkm;
					$bpjs_t=$bpjs_t+$bpjs;
					$terima_t=$terima_t+$terima;
					$setor_t=$setor_t+$setor;
					$cRet .='<TR>
								<TD align="left" >'.$bukti.'</TD>
                                <TD align="left" >'.$this->tukd_model->tanggal_ind($tanggal).'</TD>
								<TD align="left" >'.$ket.'</TD>								
								<TD align="right" >'.number_format($iwp,"2",",",".").'</TD>
								<TD align="right" >'.number_format($taperum,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn2persen,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn3persen,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkk,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkm,"2",",",".").'</TD>
								<TD align="right" >'.number_format($bpjs,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jumlah,"2",",",".").'</TD>
							 </TR>';					
			
				}
				$cRet .='<TR>
								<TD colspan="3" align="right" >Jumlah bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
								
								<TD align="right" >'.number_format($iwp_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($taperum_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn2persen_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn3persen_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkk_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkm_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($bpjs_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima_t-$setor_t,"2",",",".").'</TD>
							 </TR>';
			$nbulan_l=$nbulan-1;
			
			$query = $this->db->query("	SELECT 
					SUM(iwp) as iwp_l
					,SUM(taperum) as taperum_l
					,SUM(ppnpn2persen) as ppnpn2persen_l
					,SUM(ppnpn3persen) as ppnpn3persen_l
					,SUM(jkk) as jkk_l
					,SUM(jkm) as jkm_l
					,SUM(bpjs) as bpjs_l
					,SUM(terima) as terima_l
					,SUM(setor) as setor_l FROM(
					SELECT 
					SUM(CASE WHEN b.kd_rek5 in ('2110701','2110702','2110703') THEN b.nilai ELSE 0 END) AS iwp
					,SUM(CASE WHEN b.kd_rek5='2110501' THEN b.nilai ELSE 0 END) AS taperum
					,SUM(CASE WHEN b.kd_rek5='2111001' THEN b.nilai ELSE 0 END) AS ppnpn2persen
					,SUM(CASE WHEN b.kd_rek5='2111101' THEN b.nilai ELSE 0 END) AS ppnpn3persen
					,SUM(CASE WHEN b.kd_rek5='2111201' THEN b.nilai ELSE 0 END) AS jkk
					,SUM(CASE WHEN b.kd_rek5='2111301' THEN b.nilai ELSE 0 END) AS jkm
					,SUM(CASE WHEN b.kd_rek5='2111401' THEN b.nilai ELSE 0 END) AS bpjs
					,SUM(b.nilai) as terima,
					0 as setor
					FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
					AND MONTH(a.tgl_bukti)<='$nbulan_l'  AND b.kd_rek5 IN ('2110701','2110702','2110703','2110501','2111001','2111101','2111201','2111301','2111401')
					UNION ALL
					SELECT 
						0 AS iwp
						,0 AS taperum
						,0 AS ppnpn2persen
						,0 AS ppnpn3persen
						,0 AS jkk
						,0 AS jkm
						,0 AS bpjs
						,0 as terima,
					SUM(b.nilai) as setor
					FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd'
					AND MONTH(a.tgl_bukti)<='$nbulan_l'   AND b.kd_rek5 IN ('2110701','2110702','2110703','2110501','2111001','2111101','2111201','2111301','2111401')) a
					 "); 
			foreach ($query->result() as $row) {	
				$iwp_l =$row->iwp_l;
				$taperum_l =$row->taperum_l;
				$ppnpn2persen_l =$row->ppnpn2persen_l;
				$ppnpn3persen_l =$row->ppnpn3persen_l;
				$jkk_l =$row->jkk_l;
				$jkm_l =$row->jkm_l;
				$bpjs_l =$row->bpjs_l;
				$terima_l=$row->terima_l;                    
				$setor_l=$row->setor_l;                    
				$jumlah_l=$terima_l-$setor_l;
				$cRet .='<TR>
						<TD colspan="3" align="right" >Jumlah sampai bulan Sebelumnya</TD>
						<TD align="right" >'.number_format($iwp_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($taperum_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ppnpn2persen_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ppnpn3persen_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jkk_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jkm_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($bpjs_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($terima_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($setor_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jumlah_l,"2",",",".").'</TD>
					 </TR>';
			}		
				$cRet .='<TR>
						<TD colspan="3" align="right" >Jumlah sampai bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
						<TD align="right" >'.number_format($iwp_l+$iwp_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($taperum_l+$taperum_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ppnpn2persen_l+$ppnpn2persen_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ppnpn3persen_l+$ppnpn3persen_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jkk_l+$jkk_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jkm_l+$jkm_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($bpjs_l+$bpjs_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($terima_l+$terima_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($setor_l+$setor_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jumlah_l+$terima_t-$setor_t,"2",",",".").'</TD>
					 </TR>';			
			
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$jabatan.'</TD>
						<TD align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>'.$nama.' </u> <br> '.$pangkat.'</TD>
						<TD align="center" ><u>'.$nama1.'</u> <br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD align="center" >NIP. '.$nip.'</TD>
						<TD align="center" >NIP. '.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= 'Register Pajak';
	switch ($ctk)
        {
            case 0;
			 echo ("<title> Register Pajak Lainnya</title>");
				echo $cRet;
				break;
            case 1;
			$this->_mpdf('',$cRet,10,10,10,'L',1,'');
               break;
		}
	}
	
	function register_denda_keterlambatan($lcskpd='',$nbulan='',$ctk='',$ttd1='',$tgl_ctk='',$ttd2='', $jns='',$spasi=''){
        $ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		
			$cRet ='<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>REGISTER DENDA KETERLAMBATAN PEKERJAAN </TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala SKPD</TD>
						<TD align="left">: '.$nama.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: '.$nama1.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: '.$this->tukd_model->getBulan($nbulan).'</TD>
					 </TR>
					 </TABLE>';

			$cRet .='<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="'.$spasi.'" cellpadding="'.$spasi.'" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">NO</TD>
                        <TD bgcolor="#CCCCCC" rowspan="2" align="center">Tanggal</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Uraian</TD>						
						
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Pemotongan</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Penyetoran</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Jumlah</TD>
					 </TR>
					 </THEAD>
					 ';
			

			
				$query = $this->db->query("SELECT * FROM(
						SELECT 
						a.no_bukti,tgl_bukti, ket
						,b.nilai as terima,
						0 as setor
						FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
						AND MONTH(a.tgl_bukti)='$nbulan'  AND b.kd_rek5 IN ('4140611')
						UNION ALL
						SELECT 
						a.no_bukti,tgl_bukti, ket
						,0 as terima,
						b.nilai as setor
						FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
						AND MONTH(a.tgl_bukti)='$nbulan' AND b.kd_rek5 IN ('4140611')) a
						ORDER BY CAST(a.no_bukti as int)");  
				
				$jumlah=0;
				
				$terima_t=0;
				$setor_t=0;
				foreach ($query->result() as $row) {
                     $bukti = $row->no_bukti; 
                    $tanggal = $row->tgl_bukti;                   
                    $ket = $row->ket;
                   
                   
                    $terima=$row->terima;                    
                    $setor=$row->setor;                    
					$jumlah=$jumlah+$terima-$setor;
					
					$terima_t=$terima_t+$terima;
					$setor_t=$setor_t+$setor;
					$cRet .='<TR>
								<TD align="left" >'.$bukti.'</TD>
                                <TD align="left" >'.$this->tukd_model->tanggal_ind($tanggal).'</TD>
								<TD align="left" >'.$ket.'</TD>								
								<TD align="right" >'.number_format($terima,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jumlah,"2",",",".").'</TD>
							 </TR>';					
			
				}
				$cRet .='<TR>
								<TD colspan="3" align="right" >Jumlah bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
								
								<TD align="right" >'.number_format($terima_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima_t-$setor_t,"2",",",".").'</TD>
							 </TR>';
			$nbulan_l=$nbulan-1;
			
			$query = $this->db->query("	SELECT 
					
					SUM(terima) as terima_l
					,SUM(setor) as setor_l FROM(
					SELECT 
					
					SUM(b.nilai) as terima,
					0 as setor
					FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
					AND MONTH(a.tgl_bukti)<='$nbulan_l'  AND b.kd_rek5 IN ('4140611')
					UNION ALL
					SELECT 
					
					0 as terima,
					SUM(b.nilai) as setor
					FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd'
					AND MONTH(a.tgl_bukti)<='$nbulan_l'   AND b.kd_rek5 IN ('4140611')) a
					 "); 
			foreach ($query->result() as $row) {	
				
				$terima_l=$row->terima_l;                    
				$setor_l=$row->setor_l;                    
				$jumlah_l=$terima_l-$setor_l;
				$cRet .='<TR>
						<TD colspan="3" align="right" >Jumlah sampai bulan Sebelumnya</TD>
						
						<TD align="right" >'.number_format($terima_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($setor_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jumlah_l,"2",",",".").'</TD>
					 </TR>';
			}		
				$cRet .='<TR>
						<TD colspan="3" align="right" >Jumlah sampai bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
						<TD align="right" >'.number_format($terima_l+$terima_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($setor_l+$setor_t,"2",",",".").'</TD>
						<TD align="right" >'.number_format($jumlah_l+$terima_t-$setor_t,"2",",",".").'</TD>
					 </TR>';			
			
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$jabatan.'</TD>
						<TD align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>'.$nama.' </u> <br> '.$pangkat.'</TD>
						<TD align="center" ><u>'.$nama1.'</u> <br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD align="center" >NIP. '.$nip.'</TD>
						<TD align="center" >NIP. '.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= 'Denda Keterlambatan';
	switch ($ctk)
        {
            case 0;
			 echo ("<title> Register Denda Keterlambatan</title>");
				echo $cRet;
				break;
            case 1;
			$this->_mpdf('',$cRet,10,10,10,'L',1,'');
               break;
		}
	}
	
	
	function register_pajak3($lcskpd='',$ctk='',$ttd1='',$tgl_ctk='',$ttd2='', $spasi=''){
        $ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		
			$cRet ='<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>REKAP REGISTER PAJAK </TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala SKPD</TD>
						<TD align="left">: '.$nama.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: '.$nama1.'</TD>
					 </TR>
					 
					 </TABLE>';

			$cRet .='<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="'.$spasi.'" cellpadding="'.$spasi.'" width="150%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">NO</TD>
                        <TD bgcolor="#CCCCCC" rowspan="2" align="center">Bulan</TD>
						<TD bgcolor="#CCCCCC" colspan="5" align="center">Pajak UP/GU/TU</TD>
						<TD bgcolor="#CCCCCC" colspan="5" align="center">Pajak LS</TD>
						<TD bgcolor="#CCCCCC" colspan="7" align="center">Potongan Lainnya</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Pemotongan</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Penyetoran</TD>
						<TD bgcolor="#CCCCCC" rowspan="2" align="center">Saldo</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">PPN</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH21</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH22</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH23</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH4</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPN</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH21</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH22</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH23</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPH4</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPNPN 2%</TD>
					 <TD bgcolor="#CCCCCC" align="center">PPNPN 3%</TD>
					 <TD bgcolor="#CCCCCC" align="center">IWP</TD>
					 <TD bgcolor="#CCCCCC" align="center">TAPERUM</TD>
					 <TD bgcolor="#CCCCCC" align="center">JKK</TD>
					 <TD bgcolor="#CCCCCC" align="center">JKM</TD>
					 <TD bgcolor="#CCCCCC" align="center">BPJS</TD>                                          
					 </TR>
					 </THEAD>
					 ';
			

			
				$query = $this->db->query("SELECT a.bulan
											,ISNULL(ppn_up,0) AS ppn_up
											,ISNULL(pph21_up,0) AS pph21_up
											,ISNULL(pph22_up,0) AS pph22_up
											,ISNULL(pph23_up,0) AS pph23_up
											,ISNULL(pph4_up,0) AS pph4_up
											,ISNULL(ppn_ls,0) AS ppn_ls
											,ISNULL(pph21_ls,0) AS pph21_ls
											,ISNULL(pph22_ls,0) AS pph22_ls
											,ISNULL(pph23_ls,0) AS pph23_ls
											,ISNULL(pph4_ls,0) AS pph4_ls
											,ISNULL(ppnpn2,0) AS ppnpn2
											,ISNULL(ppnpn3,0) AS ppnpn3
                                            ,ISNULL(iwp,0) AS iwp
											,ISNULL(taperum,0) AS taperum
                                            ,ISNULL(jkk,0) AS jkk
                                            ,ISNULL(jkm,0) AS jkm
                                            ,ISNULL(bpjs,0) AS bpjs
											,ISNULL(terima,0) as terima
											,ISNULL(setor,0) as setor
											FROM (
											SELECT 1 as bulan UNION ALL
											SELECT 2 as bulan UNION ALL
											SELECT 3 as bulan UNION ALL
											SELECT 4 as bulan UNION ALL
											SELECT 5 as bulan UNION ALL
											SELECT 6 as bulan UNION ALL
											SELECT 7 as bulan UNION ALL
											SELECT 8 as bulan UNION ALL
											SELECT 9 as bulan UNION ALL
											SELECT 10 as bulan UNION ALL
											SELECT 11 as bulan UNION ALL
											SELECT 12 as bulan) a LEFT JOIN 
											(
											SELECT bulan
											,SUM(ppn_up) AS ppn_up
											,SUM(pph21_up) AS pph21_up
											,SUM(pph22_up) AS pph22_up
											,SUM(pph23_up) AS pph23_up
											,SUM(pph4_up) AS pph4_up
											,SUM(ppn_ls) AS ppn_ls
											,SUM(pph21_ls) AS pph21_ls
											,SUM(pph22_ls) AS pph22_ls
											,SUM(pph23_ls) AS pph23_ls
											,SUM(pph4_ls) AS pph4_ls
											,SUM(ppnpn2) AS ppnpn2
											,SUM(ppnpn3) AS ppnpn3
                                            ,SUM(iwp) AS iwp
											,SUM(taperum) AS taperum
                                            ,SUM(jkk) AS jkk
                                            ,SUM(jkm) AS jkm
                                            ,SUM(bpjs) AS bpjs
											,SUM(terima) as terima
											,SUM(setor) as setor
											  FROM 
											(
											SELECT MONTH(a.tgl_bukti) as bulan
											,SUM(CASE WHEN b.kd_rek5='2130301' AND a.jns_spp IN('1','2','3') THEN b.nilai ELSE 0 END) AS ppn_up
											,SUM(CASE WHEN b.kd_rek5='2130101' AND a.jns_spp IN('1','2','3') THEN b.nilai ELSE 0 END) AS pph21_up
											,SUM(CASE WHEN b.kd_rek5='2130201' AND a.jns_spp IN('1','2','3') THEN b.nilai ELSE 0 END) AS pph22_up
											,SUM(CASE WHEN b.kd_rek5='2130401' AND a.jns_spp IN('1','2','3') THEN b.nilai ELSE 0 END) AS pph23_up
											,SUM(CASE WHEN b.kd_rek5='2130501' AND a.jns_spp IN('1','2','3') THEN b.nilai ELSE 0 END) AS pph4_up
											,SUM(CASE WHEN b.kd_rek5='2130301' AND a.jns_spp IN('4','5','6') THEN b.nilai ELSE 0 END) AS ppn_ls
											,SUM(CASE WHEN b.kd_rek5='2130101' AND a.jns_spp IN('4','5','6') THEN b.nilai ELSE 0 END) AS pph21_ls
											,SUM(CASE WHEN b.kd_rek5='2130201' AND a.jns_spp IN('4','5','6') THEN b.nilai ELSE 0 END) AS pph22_ls
											,SUM(CASE WHEN b.kd_rek5='2130401' AND a.jns_spp IN('4','5','6') THEN b.nilai ELSE 0 END) AS pph23_ls
											,SUM(CASE WHEN b.kd_rek5='2130501' AND a.jns_spp IN('4','5','6') THEN b.nilai ELSE 0 END) AS pph4_ls
											,0 ppnpn2,0 ppnpn3
											,0 iwp
											,0 taperum,0 jkk, 0 jkm, 0 bpjs
											,SUM(b.nilai) as terima
											,0 as setor
											FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
											AND b.kd_rek5 IN('2130301','2130101','2130201','2130401','2130501')
											GROUP BY MONTH(a.tgl_bukti)

											UNION ALL

											SELECT MONTH(a.tgl_bukti) as bulan
											,0 AS ppn_up
											,0 AS pph21_up
											,0 AS pph22_up
											,0 AS pph23_up
											,0 AS pph4_up
											,0 AS ppn_ls
											,0 AS pph21_ls
											,0 AS pph22_ls
											,0 AS pph23_ls
											,0 AS pph4_ls
											,SUM(CASE WHEN b.kd_rek5='2111001' THEN b.nilai ELSE 0 END) AS ppnpn2
											,SUM(CASE WHEN b.kd_rek5='2111101' THEN b.nilai ELSE 0 END) AS ppnpn3
											,SUM(CASE WHEN b.kd_rek5 in ('2110701','2110702','2110703') THEN b.nilai ELSE 0 END) AS iwp
											,SUM(CASE WHEN b.kd_rek5='2110501' THEN b.nilai ELSE 0 END) AS taperum
                                            ,SUM(CASE WHEN b.kd_rek5='2111201' THEN b.nilai ELSE 0 END) AS jkk
                                            ,SUM(CASE WHEN b.kd_rek5='2111301' THEN b.nilai ELSE 0 END) AS jkm
                                            ,SUM(CASE WHEN b.kd_rek5='2111401' THEN b.nilai ELSE 0 END) AS bpjs
											,SUM(b.nilai) as terima
											,0 as setor
											FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
											AND b.kd_rek5 IN ('2111001','2111101','2110701','2110702','2110703','2110501','2111201','2111301','2111401')
											GROUP BY  MONTH(a.tgl_bukti)

											UNION ALL

											SELECT MONTH(a.tgl_bukti) as bulan
											,0 AS ppn_up
											,0 AS pph21_up
											,0 AS pph22_up
											,0 AS pph23_up
											,0 AS pph4_up
											,0 AS ppn_ls
											,0 AS pph21_ls
											,0 AS pph22_ls
											,0 AS pph23_ls
											,0 AS pph4_ls
											,0 ppnpn2
                                            ,0 ppnpn3
											,0 iwp
											,0 taperum
                                            ,0 jkk
                                            ,0 jkm
                                            ,0 bpjs
											,0 terima
											,SUM(b.nilai) as setor
											FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd WHERE a.kd_skpd='$lcskpd' 
											AND b.kd_rek5 IN('2130301','2130101','2130201','2130401','2130501','2111001','2111101','2110701','2110702','2110703','2110501','2111201','2111301','2111401')
											GROUP BY MONTH(a.tgl_bukti)
											)a
											GROUP BY bulan) b
											ON a.bulan=b.bulan ORDER BY a.bulan
											");  
				
				$jumlah=0;
				$ppn_up_t=0;
				$pph21_up_t=0;
				$pph22_up_t=0;
				$pph23_up_t=0;
				$pph4_up_t=0;
				$ppn_ls_t=0;
				$pph21_ls_t=0;
				$pph22_ls_t=0;
				$pph23_ls_t=0;
				$pph4_ls_t=0;
				$ppnpn2_t=0;
                $ppnpn3_t=0;
				$iwp_t=0;
				$taperum_t=0;
                $jkk_t=0;
                $jkm_t=0;
                $bpjs_t=0;
				$terima_t=0;
				$setor_t=0;
				$no=0;
				foreach ($query->result() as $row) {
                    $bulan = $row->bulan;                   
                    $ppn_up = $row->ppn_up;
                    $pph21_up =$row->pph21_up;
                    $pph22_up =$row->pph22_up;
                    $pph23_up =$row->pph23_up;
                    $pph4_up=$row->pph4_up;                    
                    $ppn_ls=$row->ppn_ls;                    
                    $pph21_ls=$row->pph21_ls;                    
                    $pph22_ls=$row->pph22_ls;                    
                    $pph23_ls=$row->pph23_ls;                    
                    $pph4_ls=$row->pph4_ls;                    
                    $ppnpn2=$row->ppnpn2;                    
                    $ppnpn3=$row->ppnpn3;
                    $iwp=$row->iwp;                    
                    $taperum=$row->taperum; 
                    $jkk=$row->jkk; 
                    $jkm=$row->jkm;                  
                    $bpjs=$row->bpjs;
                    $terima=$row->terima;                    
                    $setor=$row->setor;                    
					$jumlah=$jumlah+$terima-$setor;
					$ppn_up_t=$ppn_up_t+$ppn_up;
					$pph21_up_t=$pph21_up_t+$pph21_up;
					$pph22_up_t=$pph22_up_t+$pph22_up;
					$pph23_up_t=$pph23_up_t+$pph23_up;
					$pph4_up_t=$pph4_up_t+$pph4_up;
					$ppn_ls_t=$ppn_ls_t+$ppn_ls;
					$pph21_ls_t=$pph21_ls_t+$pph21_ls;
					$pph22_ls_t=$pph22_ls_t+$pph22_ls;
					$pph23_ls_t=$pph23_ls_t+$pph23_ls;
					$pph4_ls_t=$pph4_ls_t+$pph4_ls;
					$ppnpn2_t=$ppnpn2_t+$ppnpn2;
                    $ppnpn3_t=$ppnpn3_t+$ppnpn3;
					$iwp_t=$iwp_t+$iwp;
					$taperum_t=$taperum_t+$taperum;
                    $jkk_t=$jkk_t+$jkk;
                    $jkm_t=$jkm_t+$jkm;
                    $bpjs_t=$bpjs_t+$bpjs;
					$terima_t=$terima_t+$terima;
					$setor_t=$setor_t+$setor;
					$no=$no+1;
					$cRet .='<TR>
								<TD align="left" >'.$no.'</TD>
								<TD align="left" >'.$this->tukd_model->getBulan($bulan).'</TD>								
								<TD align="right" >'.number_format($ppn_up,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph21_up,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph22_up,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph23_up,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph4_up,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppn_ls,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph21_ls,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph22_ls,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph23_ls,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph4_ls,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn2,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn3,"2",",",".").'</TD>
								<TD align="right" >'.number_format($iwp,"2",",",".").'</TD>
								<TD align="right" >'.number_format($taperum,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkk,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkm,"2",",",".").'</TD>
								<TD align="right" >'.number_format($bpjs,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jumlah,"2",",",".").'</TD>
							 </TR>';					
			
				}
				
				$cRet .='<TR>
								<TD colspan="2" align="right" >Jumlah </TD>
								<TD align="right" >'.number_format($ppn_up_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph21_up_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph22_up_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph23_up_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph4_up_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppn_ls_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph21_ls_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph22_ls_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph23_ls_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pph4_ls_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn2_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ppnpn3_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($iwp_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($taperum_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkk_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($jkm_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($bpjs_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($setor_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($terima_t-$setor_t,"2",",",".").'</TD>
							 </TR>';
			
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE width="150%" style="font-size:24px;">
					<TR>
						<TD width="75%" align="center" ><b>&nbsp;</TD>
						<TD width="75%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$jabatan.'</TD>
						<TD align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>'.$nama.' </u> <br> '.$pangkat.'</TD>
						<TD align="center" ><u>'.$nama1.'</u> <br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD align="center" >NIP. '.$nip.'</TD>
						<TD align="center" >NIP. '.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= 'Register Pajak';
	switch ($ctk)
        {
            case 0;
			 echo ("<title> Register Pajak Lainnya</title>");
				echo $cRet;
				break;
            case 1;
			$this->_mpdf('',$cRet,10,10,10,'L',1,'');
               break;
		}
	}
	
	
	
function register_cp($lcskpd='',$nbulan='',$ctk='',$ttd1='',$tgl_ctk='',$ttd2='', $spasi=''){
        $ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2);
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
		
			$cRet ='<TABLE width="100%" style="font-size:16px">
					<TR>
						<TD align="center" ><b>'.$prov.' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>REGISTER CP</TD>
					</TR>
					</TABLE><br/>';

			$cRet .='<TABLE width="100%" style="font-size:14px">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="100%" >: '.$lcskpd.' '.$skpd.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Kepala SKPD</TD>
						<TD align="left">: '.$nama.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bendahara </TD>
						<TD align="left">: '.$nama1.'</TD>
					 </TR>
					 <TR>
						<TD align="left">Bulan </TD>
						<TD align="left">: '.$this->tukd_model->getBulan($nbulan).'</TD>
					 </TR>
					 </TABLE>';

			$cRet .='<TABLE style="border-collapse:collapse;font-size:14px" border="1" cellspacing="'.$spasi.'" cellpadding="'.$spasi.'" width="100%" >
					 <THEAD>
					 <TR>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center">NO</TD>
                        <TD bgcolor="#CCCCCC" rowspan="4" align="center">Tanggal CP</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center">No STS</TD>						
						<TD bgcolor="#CCCCCC" rowspan="4" align="center">No SP2D</TD>						
						<TD bgcolor="#CCCCCC" rowspan="4" align="center">Uraian</TD>						
						<TD bgcolor="#CCCCCC" colspan="10" align="center">CP</TD>
						<TD bgcolor="#CCCCCC" rowspan="4" align="center">Jumlah</TD>
					 </TR>
					 <TR>
					 <TD colspan="5" bgcolor="#CCCCCC" align="center">LS</TD>
					 <TD colspan="5" bgcolor="#CCCCCC" align="center">UP/GU/TU</TD>
					 </TR>
					 <TR>
					 <TD colspan="2" bgcolor="#CCCCCC" align="center">Gaji</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">Barang dan Jasa</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">UP</TD>
					 <TD rowspan="2" bgcolor="#CCCCCC" align="center">GU</TD>
					 <TD colspan="3" bgcolor="#CCCCCC" align="center">TU</TD>
					 </TR>
					 <TR>
					 <TD bgcolor="#CCCCCC" align="center">HKPG</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pot. Lain</TD>
					 
					 <TD bgcolor="#CCCCCC" align="center">Peg.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Brng.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal.</TD>
					 <TD bgcolor="#CCCCCC" align="center">Pegawai</TD>
					 <TD bgcolor="#CCCCCC" align="center">Barang</TD>
					 <TD bgcolor="#CCCCCC" align="center">Modal</TD>
					 </TR>
					 </THEAD>
					 ';
			

			
				$query = $this->db->query("SELECT
						a.tgl_sts,
						b.no_sts,
						a.no_sp2d,
						keterangan,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '1' AND pot_khusus = '1' THEN b.rupiah	ELSE 0 END) AS hkpg,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '1' AND pot_khusus = '2' THEN b.rupiah	ELSE 0 END) AS pot_lain,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '1' AND pot_khusus NOT IN ('1','2') THEN	b.rupiah ELSE 0 END) AS cp,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN	b.rupiah ELSE 0	END) AS ls_peg,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '522' THEN	b.rupiah ELSE 0	END) AS ls_brng,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '523' THEN	b.rupiah ELSE 0	END) AS ls_modal,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) = '111' THEN	b.rupiah ELSE 0	END) AS gu,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN b.rupiah	ELSE 0 END) AS up_gu_peg,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('522') THEN	b.rupiah	ELSE 0 END) AS up_gu_brng,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '3' AND LEFT(b.kd_rek5,3) IN ('523') THEN	b.rupiah	ELSE 0 END) AS up_gu_modal,
						SUM (b.rupiah) AS total
					FROM
						trhkasin_pkd a
					INNER JOIN trdkasin_pkd b ON a.no_sts = b.no_sts
					AND a.kd_skpd = b.kd_skpd
					WHERE
						a.kd_skpd = '$lcskpd'
					AND MONTH(a.tgl_sts)='$nbulan' AND pot_khusus !='3'
					AND jns_trans IN ('1', '5') AND LEFT(b.kd_rek5,1)<>4
					GROUP BY
						a.tgl_sts,
						b.no_sts,
						a.no_sp2d,
						keterangan");  
				
				$no=0;
				$hkpg_t=0;
				$pot_lain_t=0;
				$cp_t=0;
				$ls_peg_t=0;
				$ls_brng_t=0;
				$ls_modal_t=0;
				$up_gu_peg_t=0;
				$up_gu_brng_t=0;
				$up_gu_modal_t=0;
				$gu_t=0;
				$total_t=0;
				foreach ($query->result() as $row) {
                    $bukti = $row->no_sts; 
                    $tanggal = $row->tgl_sts;                   
                    $ket = $row->keterangan;
                    $no_sts =$row->no_sts;
                    $no_sp2d =$row->no_sp2d;
                    $hkpg =$row->hkpg;
                    $pot_lain =$row->pot_lain;
                    $cp=$row->cp;                    
                    $ls_peg=$row->ls_peg;                    
                    $ls_brng=$row->ls_brng;                    
                    $ls_modal=$row->ls_modal;                    
                    $up_gu_peg=$row->up_gu_peg;                    
                    $up_gu_brng=$row->up_gu_brng;                    
                    $up_gu_modal=$row->up_gu_modal;   
                    $gu=$row->gu;   
                    $total=$row->total;
					$hkpg_t=$hkpg_t+$hkpg;
					$pot_lain_t=$pot_lain_t+$pot_lain;
					$cp_t=$cp_t+$cp;
					$ls_peg_t=$ls_peg_t+$ls_peg;
					$ls_brng_t=$ls_brng_t+$ls_brng;
					$ls_modal_t=$ls_modal_t+$ls_modal;
					$up_gu_peg_t=$up_gu_peg_t+$up_gu_peg;
					$up_gu_brng_t=$up_gu_brng_t+$up_gu_brng;
					$up_gu_modal_t=$up_gu_modal_t+$up_gu_modal;
					$total_t=$total_t+$total;
					$gu_t=$gu_t+$gu;
					$no=$no+1;
					$cRet .='<TR>
								<TD align="left" >'.$no.'</TD>
                                <TD align="left" >'.$this->tukd_model->tanggal_ind($tanggal).'</TD>
								<TD align="left" >'.$no_sts.'</TD>								
								<TD align="left" >'.$no_sp2d.'</TD>								
								<TD align="left" >'.$ket.'</TD>								
								<TD align="right" >'.number_format($hkpg,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pot_lain,"2",",",".").'</TD>
								
								<TD align="right" >'.number_format($ls_peg,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ls_brng,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ls_modal,"2",",",".").'</TD>
								<TD align="right" >'.number_format(0,"2",",",".").'</TD>
								<TD align="right" >'.number_format($gu,"2",",",".").'</TD>
								<TD align="right" >'.number_format($up_gu_peg,"2",",",".").'</TD>
								<TD align="right" >'.number_format($up_gu_brng,"2",",",".").'</TD>
								<TD align="right" >'.number_format($up_gu_modal,"2",",",".").'</TD>
								<TD align="right" >'.number_format($total,"2",",",".").'</TD>
							 </TR>';					
			
				}
				
				$cRet .='<TR>
								<TD colspan="5" align="right" >Jumlah bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
								<TD align="right" >'.number_format($hkpg_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($pot_lain_t,"2",",",".").'</TD>
								
								<TD align="right" >'.number_format($ls_peg_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ls_brng_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($ls_modal_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format(0,"2",",",".").'</TD>
								<TD align="right" >'.number_format($gu_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($up_gu_peg_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($up_gu_brng_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($up_gu_modal_t,"2",",",".").'</TD>
								<TD align="right" >'.number_format($total_t,"2",",",".").'</TD>
							 </TR>';
			$nbulan_l=$nbulan-1;
			
			$query = $this->db->query("	SELECT
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '1' THEN b.rupiah	ELSE 0 END) AS hkpg_l,
						SUM (	CASE	WHEN jns_trans = '5'	AND jns_cp = '1' AND pot_khusus = '2' THEN b.rupiah	ELSE 0 END) AS pot_lain_l,
						SUM (	CASE	WHEN jns_trans = '1'	AND jns_cp = '1' THEN	b.rupiah ELSE 0 END) AS cp_l,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) IN ('511','521') THEN	b.rupiah ELSE 0	END) AS ls_peg_l,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '522' THEN	b.rupiah ELSE 0	END) AS ls_brng_l,
						SUM (	CASE	WHEN jns_trans IN ('1','5')	AND jns_cp = '2' AND LEFT(b.kd_rek5,3) = '523' THEN	b.rupiah ELSE 0	END) AS ls_modal_l,
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
						a.kd_skpd = '$lcskpd'
					AND MONTH(a.tgl_sts)<='$nbulan_l' AND pot_khusus !='3'
					AND jns_trans IN ('1', '5') AND LEFT(b.kd_rek5,1)<>4
					"); 
			foreach ($query->result() as $row) {	
				$hkpg_l =$row->hkpg_l;
				$pot_lain_l =$row->pot_lain_l;
				$cp_l =$row->cp_l;
				$ls_peg_l =$row->ls_peg_l;
				$ls_brng_l =$row->ls_brng_l;
				$ls_modal_l =$row->ls_modal_l;
				$up_gu_peg_l =$row->up_gu_peg_l;
				$up_gu_brng_l =$row->up_gu_brng_l;
				$up_gu_modal_l =$row->up_gu_modal_l;
				$gu_l =$row->gu_l;
				$total_l =$row->total_l;
				$cRet .='<TR>
						<TD colspan="5" align="right" >Jumlah sampai bulan Sebelumnya</TD>
						<TD align="right" >'.number_format($hkpg_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($pot_lain_l,"2",",",".").'</TD>
						
						<TD align="right" >'.number_format($ls_peg_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ls_brng_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ls_modal_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format(0,"2",",",".").'</TD>
						<TD align="right" >'.number_format($gu_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($up_gu_peg_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($up_gu_brng_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($up_gu_modal_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($total_l,"2",",",".").'</TD>
					 </TR>';
			}		
				$cRet .='<TR>
						<TD colspan="5" align="right" >Jumlah sampai bulan '.$this->tukd_model->getBulan($nbulan).'</TD>
						<TD align="right" >'.number_format($hkpg_t+$hkpg_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($pot_lain_t+$pot_lain_l,"2",",",".").'</TD>
						
						<TD align="right" >'.number_format($ls_peg_t+$ls_peg_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ls_brng_t+$ls_brng_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($ls_modal_t+$ls_modal_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format(0,"2",",",".").'</TD>
						<TD align="right" >'.number_format($gu_t+$gu_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($up_gu_peg_t+$up_gu_peg_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($up_gu_brng_t+$up_gu_brng_l,"2",",",".").'</TD>
						<TD align="right" >'.number_format($up_gu_modal_t+$up_gu_modal_l,"2",",",".").' </TD>
						<TD align="right" >'.number_format($total_t+$total_l,"2",",",".").'</TD>
					 </TR>';			
			
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$jabatan.'</TD>
						<TD align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><u>'.$nama.' </u> <br> '.$pangkat.'</TD>
						<TD align="center" ><u>'.$nama1.'</u> <br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD align="center" >NIP. '.$nip.'</TD>
						<TD align="center" >NIP. '.$nip1.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= $cRet;
			$judul  = "CP";
			
	switch ($ctk)
        {
            case 0;
			 echo ("<title> Register Pajak Lainnya</title>");
				echo $cRet;
				break;
            case 1;
			$this->_mpdf('',$cRet,10,10,10,'L',1,'');
               break;
			case 2:
			header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
			
			$this->load->view('anggaran/rka/perkadaII', $data);
			break;
		}
	}
		
	function cetak_spj_periode($lcskpd='',$tgl1='',$tgl2='',$ttd1='',$tgl_ctk='',$ttd2='',$ctk='',$atas='', $bawah='', $kiri='', $kanan='', $jenis=''){
		$ttd1 = str_replace('123456789',' ',$ttd1);
		$ttd2 = str_replace('123456789',' ',$ttd2); 
		$sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode in ('PA','KPA') and nip='$ttd2'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama2= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                }
				
		$tanda_ang=2;
        $thn_ang	   = $this->session->userdata('pcThang');
        
        $skpd = $lcskpd;
        $nama=  $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
		$prv = $this->db->query("SELECT * from sclient WHERE kd_skpd='$lcskpd'");
		$prvn = $prv->row();          
		$prov = $prvn->provinsi;         
		$daerah = $prvn->daerah;
		if($jenis=='1'){
			$judul='SPJ FUNGSIONAL';
		} else if($jenis=='2'){
			$judul='SPJ ADMINISTRATIF';
		} else {
			$judul='SPJ BELANJA';
		}
        $cRet = '';
        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
        $cRet .="
            
            <tr>
                <td align=\"center\" style=\"font-size:14px;\" colspan=\"2\">
                 <b> $prov<BR></b>
                 <b> SURAT PENGESAHAN PERTANGGUNGJAWABAN BENDAHARA PENGELUARAN<BR></b>
                 <b>(".$judul.")<BR></b>&nbsp;
                </td>
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px;\" width=\"25%\">
                  SKPD
                </td> 
                <td width=\"75%\" style=\"font-size:12px;\">:$skpd - $nama
                </td>         
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px;\">
                  Pengguna Anggaran/Kuasa Pengguna Anggaran
                </td> 
                <td style=\"font-size:12px;\">:$nama2
                </td>         
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px;\">
                  Bendahara Pengeluaran
                </td> 
                <td style=\"font-size:12px;\">:$nama1
                </td>         
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px;\">
                  Tahun Anggaran
                </td> 
                <td style=\"font-size:12px;\">:$thn_ang
                </td>         
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px;\">
                  Periode
                </td> 
                <td style=\"font-size:12px;\">:".$this->tukd_model->tanggal_format_indonesia($tgl1)." - ".$this->tukd_model->tanggal_format_indonesia($tgl2)."
                </td>         
            </tr>
            <tr>
                <td align=\"left\" style=\"font-size:12px;\" colspan=\"2\">
                 &nbsp;
                </td> 
            </tr>
            
            </table>
            <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\" style=\"font-size:12px\"><b>Kode<br>Rekening</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\" style=\"font-size:12px\"><b>Uraian</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\" style=\"font-size:12px\"><b>Jumlah<br>Anggaran</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" colspan=\"3\" style=\"font-size:12px\"><b>SPJ-LS Gaji</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" colspan=\"3\" style=\"font-size:12px\"><b>SPJ-LS Barang & Jasa</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" colspan=\"3\" style=\"font-size:12px\"><b>SPJ UP/GU/TU</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\" style=\"font-size:12px\"><b>Jumlah SPJ<br>(LS+UP/GU/TU)<br>s.d Bulan Ini</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\" style=\"font-size:12px\"><b>Sisa Pagu<br>Anggaran</b></td>
            </tr>
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>Bulan Ini</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>Bulan Ini</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>Bulan Ini</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\"><b>s.d<br>Bulan Ini</b></td>
            </tr>                 
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">1</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">2</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">3</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">4</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">5</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">6</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">7</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">8</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">9</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">10</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">11</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">12</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">13</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" style=\"font-size:12px\">14</td>
            </tr> 
             </thead>
            <tr>
                <td align=\"center\" style=\"font-size:12px\">&nbsp;</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
            </tr>";
			
			
				$att="exec spj_skpd_periode '$lcskpd','$tgl1','$tgl2'";
				$hasil=$this->db->query($att);
				foreach ($hasil->result() as $trh1){
				$bre				=	$trh1->kd_rek;
				$wok				=	$trh1->uraian;
				$nilai				=	$trh1->anggaran;
				$real_up_ini		=	$trh1->up_ini;
				$real_up_ll			=	$trh1->up_lalu;
				$real_gaji_ini		=	$trh1->gaji_ini;
				$real_gaji_ll		=	$trh1->gaji_lalu;
				$real_brg_js_ini	=	$trh1->brg_ini;
				$real_brg_js_ll		=	$trh1->brg_lalu;
				$total	= $real_gaji_ll+$real_gaji_ini+$real_brg_js_ll+$real_brg_js_ini+$real_up_ll+$real_up_ini;
				$sisa	= $nilai-$real_gaji_ll-$real_gaji_ini-$real_brg_js_ll-$real_brg_js_ini-$real_up_ll-$real_up_ini;
				$a=strlen($bre);
			if($a==18){
			$cRet .="
           <tr>
                <td   valign=\"top\" width=\"8%\" align=\"left\" style=\"font-size:12px\" ><b>".$bre."</b></td>
                <td   valign=\"top\" align=\"left\" width=\"25%\" style=\"font-size:12px\"><b>".$wok."</b></td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($nilai,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_gaji_ll,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_gaji_ini,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_gaji_ll+$real_gaji_ini,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_brg_js_ll,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_brg_js_ini,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_brg_js_ll+$real_brg_js_ini,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_up_ll,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_up_ini,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_up_ll+$real_up_ini,"2",",",".")."</b>&nbsp;</td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($total,"2",",",".")."</b>&nbsp;</b></td>
                <td   valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($sisa,"2",",",".")."</b>&nbsp;</td>
            </tr>";
			}else if($a==21){
			$cRet .="
           <tr>
                <td valign=\"top\" width=\"8%\" align=\"left\" style=\"font-size:12px\" ><b>".$bre."</b></td>
                <td valign=\"top\" align=\"left\" width=\"25%\" style=\"font-size:12px\"><b>".$wok."</b></td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($nilai,"2",",",".")."&nbsp;</b></td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_gaji_ll,"2",",",".")."&nbsp;</b></td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_gaji_ini,"2",",",".")."&nbsp;</b></td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_gaji_ll+$real_gaji_ini,"2",",",".")."</b>&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_brg_js_ll,"2",",",".")."</b>&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_brg_js_ini,"2",",",".")."</b>&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_brg_js_ll+$real_brg_js_ini,"2",",",".")."</b>&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_up_ll,"2",",",".")."</b>&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_up_ini,"2",",",".")."</b>&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($real_up_ll+$real_up_ini,"2",",",".")."</b>&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($total,"2",",",".")."</b>&nbsp;</b></td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\"><b>".number_format($sisa,"2",",",".")."</b>&nbsp;</td>
            </tr>";
			} else{
			$cRet .="
				<tr>
                <td valign=\"top\" width=\"8%\" align=\"left\" style=\"font-size:12px\" >".substr($bre,22,7)."</td>
                <td valign=\"top\" align=\"left\" width=\"25%\" style=\"font-size:12px\">".$wok."</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($nilai,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_gaji_ll,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_gaji_ini,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_gaji_ll+$real_gaji_ini,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_brg_js_ll,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_brg_js_ini,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_brg_js_ll+$real_brg_js_ini,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_up_ll,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_up_ini,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($real_up_ll+$real_up_ini,"2",",",".")."&nbsp;</td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($total,"2",",",".")."&nbsp;</b></td>
                <td valign=\"top\" align=\"right\" style=\"font-size:12px\">".number_format($sisa,"2",",",".")."&nbsp;</td>
            </tr>";
			}
			}
			$cRet .="

            <tr>
                <td valign=\"top\" align=\"center\" style=\"font-size:12px\" >&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">Penerimaan :</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td valign=\"top\" align=\"center\" style=\"font-size:12px\">&nbsp;</td>
            </tr>";
            
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    (a.tgl_kas) BETWEEN '$tgl1' AND '$tgl2' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ini,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    (a.tgl_kas)<'$tgl1' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ll,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    (a.tgl_kas) BETWEEN '$tgl1' AND '$tgl2' AND c.jns_spp ='4' AND a.status='1') AS sp2d_gj_ini,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    (a.tgl_kas)<'$tgl1' AND c.jns_spp ='4'  AND a.status='1') AS sp2d_gj_ll,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    (a.tgl_kas) BETWEEN '$tgl1' AND '$tgl2' AND c.jns_spp ='6'  AND a.status='1') AS sp2d_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    (a.tgl_kas)<'$tgl1' AND c.jns_spp ='6' AND a.status='1') AS sp2d_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh1 = $hasil->row(); 
            $totalsp2d = $trh1->sp2d_gj_ll+$trh1->sp2d_gj_ini+$trh1->sp2d_brjs_ll+
                         $trh1->sp2d_brjs_ini+$trh1->sp2d_up_ll+$trh1->sp2d_up_ini;
                         
            $cobacoba = $trh1->sp2d_gj_ll;
            
            
            
            $cRet .="   
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\" >&ensp;&ensp;- SP2D</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_gj_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_gj_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_brjs_ll + $trh1->sp2d_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh1->sp2d_up_ll + $trh1->sp2d_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalsp2d,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr> ";           
            
			$csqlsp2d = "SELECT a.no_sp2d,
						SUM(CASE WHEN a.jns_spp IN ('4') AND (a.tgl_kas)<'$tgl1' THEN b.nilai ELSE 0 END) AS gj_ll,
						SUM(CASE WHEN a.jns_spp IN ('4') AND (a.tgl_kas) BETWEEN '$tgl1' AND '$tgl2' THEN b.nilai ELSE 0 END) AS gj_ini,
						SUM(CASE WHEN a.jns_spp IN ('4') AND (a.tgl_kas)<='$tgl2' THEN b.nilai ELSE 0 END) AS gj_sdini,
						SUM(CASE WHEN a.jns_spp IN ('6') AND (a.tgl_kas)<'$tgl1' THEN b.nilai ELSE 0 END) AS br_ll,
						SUM(CASE WHEN a.jns_spp IN ('6') AND (a.tgl_kas) BETWEEN '$tgl1' AND '$tgl2' THEN b.nilai ELSE 0 END) AS br_ini,
						SUM(CASE WHEN a.jns_spp IN ('6') AND (a.tgl_kas)<='$tgl2' THEN b.nilai ELSE 0 END) AS br_sdini,
						SUM(CASE WHEN a.jns_spp IN ('1','2','3') AND (a.tgl_kas)<'$tgl1' THEN b.nilai ELSE 0 END) AS up_ll,
						SUM(CASE WHEN a.jns_spp IN ('1','2','3') AND (a.tgl_kas) BETWEEN '$tgl1' AND '$tgl2' THEN b.nilai ELSE 0 END) AS up_ini,
						SUM(CASE WHEN a.jns_spp IN ('1','2','3') AND (a.tgl_kas)<='$tgl2' THEN b.nilai ELSE 0 END) AS up_sdini
						FROM trhsp2d a INNER JOIN trdspp b 
						ON a.no_spp = b.no_spp INNER JOIN trhspp c
						ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd'
						AND a.status='1' AND (a.tgl_kas) BETWEEN '$tgl1' AND '$tgl2'
						GROUP BY a.no_sp2d";
			
			$hasilsp2d=$this->db->query($csqlsp2d);
				foreach ($hasilsp2d->result() as $trsp2d){
				$xno_sp2d	=	$trsp2d->no_sp2d;
				$xup_ll		=	$trsp2d->up_ll;
				$xup_ini	=	$trsp2d->up_ini;
				$xup_sdini	=	$trsp2d->up_sdini;
				$xbr_ll		=	$trsp2d->br_ll;
				$xbr_ini	=	$trsp2d->br_ini;
				$xbr_sdini	=	$trsp2d->br_sdini;
				$xgj_ll		=	$trsp2d->gj_ll;
				$xgj_ini	=	$trsp2d->gj_ini;
				$xgj_sdini	=	$trsp2d->gj_sdini;
			
			
			$cRet .="   
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp; $xno_sp2d</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xgj_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xgj_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xgj_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xbr_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xbr_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xbr_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xup_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xup_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xup_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($xgj_sdini+$xbr_sdini+$xup_sdini,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
		   </tr> "; 
				}
			$cRet .="
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Potongan Pajak</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
            $lcrek = '2130301';//'2110401'; // ppn terima
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2' AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2' AND 
                    a.jns_spp ='4') AS jppn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jppn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jppn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jppn_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh2 = $hasil->row();
            $totalppn = $trh2->jppn_up_ini + $trh2->jppn_up_ll + $trh2->jppn_gaji_ini + 
                        $trh2->jppn_gaji_ll + $trh2->jppn_brjs_ini + $trh2->jppn_brjs_ll;
            
            
            $cRet .=" 
            <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;a. PPN</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_gaji_ll + $trh2->jppn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_brjs_ll + $trh2->jppn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh2->jppn_up_ll + $trh2->jppn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalppn,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
			</tr>";
            
            $lcrek = '2130101'; // pph 21 terima
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS jpph21_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jpph21_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jpph21_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jpph21_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh3 = $hasil->row();
            $totalpph21 = $trh3->jpph21_up_ini + $trh3->jpph21_up_ll + $trh3->jpph21_gaji_ini + 
                        $trh3->jpph21_gaji_ll + $trh3->jpph21_brjs_ini + $trh3->jpph21_brjs_ll;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_gaji_ll + $trh3->jpph21_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_brjs_ll + $trh3->jpph21_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh3->jpph21_up_ll + $trh3->jpph21_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph21,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
            $lcrek = '2130201'; // pph 22 terima
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS jpph22_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jpph22_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jpph22_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jpph22_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh4 = $hasil->row();
            $totalpph22 = $trh4->jpph22_up_ini + $trh4->jpph22_up_ll + $trh4->jpph22_gaji_ini + 
                        $trh4->jpph22_gaji_ll + $trh4->jpph22_brjs_ini + $trh4->jpph22_brjs_ll;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_gaji_ll + $trh4->jpph22_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_brjs_ll + $trh4->jpph22_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh4->jpph22_up_ll + $trh4->jpph22_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph22,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
			$lcrek = '2130401'; // pph 23 terima
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS jpph23_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jpph23_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jpph23_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jpph23_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh5 = $hasil->row();
            $totalpph23 = $trh5->jpph23_up_ini + $trh5->jpph23_up_ll + $trh5->jpph23_gaji_ini + 
                        $trh5->jpph23_gaji_ll + $trh5->jpph23_brjs_ini + $trh5->jpph23_brjs_ll;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_gaji_ll + $trh5->jpph23_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_brjs_ll + $trh5->jpph23_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh5->jpph23_up_ll + $trh5->jpph23_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph23,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			
           
			
            
			$lcrek = "('2110701','2110702','2110703')"; // IWP
            $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' THEN a.nilai ELSE 0 END) AS up_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN a.nilai ELSE 0 END) AS up_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<='$tgl2'  THEN a.nilai ELSE 0 END) AS up_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' THEN a.nilai ELSE 0 END) AS gj_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN a.nilai ELSE 0 END) AS gj_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<= '$tgl2'  THEN a.nilai ELSE 0 END) AS gj_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' THEN a.nilai ELSE 0 END) AS ls_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN a.nilai ELSE 0 END) AS ls_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<='$tgl2'  THEN a.nilai ELSE 0 END) AS ls_iwp_sdini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek5 in $lcrek AND a.kd_skpd='$lcskpd'";
            
            $hasil = $this->db->query($csql);
            $trh70 = $hasil->row();
            $totaliwp = $trh70->up_iwp_sdini + $trh70->gj_iwp_sdini + $trh70->ls_iwp_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. IWP</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->gj_iwp_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->gj_iwp_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->gj_iwp_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->ls_iwp_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->ls_iwp_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->ls_iwp_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->up_iwp_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->up_iwp_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh70->up_iwp_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totaliwp,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			$lcrek = '2110501'; // TAPERUM
            $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' THEN a.nilai ELSE 0 END) AS up_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN a.nilai ELSE 0 END) AS up_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<= '$tgl2'  THEN a.nilai ELSE 0 END) AS up_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' THEN a.nilai ELSE 0 END) AS gj_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek5='$lcrek' AND a.kd_skpd='$lcskpd'";
            
            $hasil = $this->db->query($csql);
            $trh71 = $hasil->row();
            $totaltap = $trh71->up_tap_sdini + $trh71->gj_tap_sdini + $trh71->ls_tap_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. Taperum</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->gj_tap_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->gj_tap_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->gj_tap_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->ls_tap_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->ls_tap_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->ls_tap_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->up_tap_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->up_tap_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh71->up_tap_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totaltap,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			
			$lcrek = '2130501'; // pph4
            $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<='$tgl2'  THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek5='$lcrek' AND a.kd_skpd='$lcskpd'";
            
            $hasil = $this->db->query($csql);
            $trh72 = $hasil->row();
            $totalpph4 = $trh72->up_pph4_sdini + $trh72->gj_pph4_sdini + $trh72->ls_pph4_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. PPh Pasal 4</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->gj_pph4_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->gj_pph4_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->gj_pph4_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->ls_pph4_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->ls_pph4_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->ls_pph4_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->up_pph4_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->up_pph4_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh72->up_pph4_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph4,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			
			$lcrek = '2110901'; // PPnPn
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh15 = $hasil->row();
            $totalppnpn = $trh15->ppnpn_up_ini + $trh15->ppnpn_up_ll + $trh15->ppnpn_gaji_ini + 
                        $trh15->ppnpn_gaji_ll + $trh15->ppnpn_brjs_ini + $trh15->ppnpn_brjs_ll;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. Iuran Wajib PPNPN</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_gaji_ll + $trh15->ppnpn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_brjs_ll + $trh15->ppnpn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh15->ppnpn_up_ll + $trh15->ppnpn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalppnpn,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			
			
			
			
			
			
			
			
			
			
            // lain terima
            $csql = "SELECT 
					SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
					SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
					SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
					 FROM(
					SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd='$lcskpd'
					UNION ALL
					SELECT 
					SUM(CASE WHEN a.jns_beban='1' AND (a.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN a.jns_beban='1' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN a.jns_beban='4' AND (a.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN a.jns_beban='4' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN a.jns_beban='6' AND (a.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN a.jns_beban='6' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
					FROM TRHINLAIN a WHERE pengurang_belanja !='1'
					AND a.kd_skpd='$lcskpd'
					) a ";

			
			
					
					
					
            $tox_awal="SELECT isnull(sld_awal,0)+sld_awalpajak AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
            $hasil = $this->db->query($tox_awal);
            $tox = $hasil->row('jumlah');
            
            $hasil = $this->db->query($csql);
            $trh6 = $hasil->row();
            $totallain = $trh6->jlain_up_ini + $trh6->jlain_up_ll + $trh6->jlain_gaji_ini + 
                         $trh6->jlain_gaji_ll + $trh6->jlain_brjs_ini + $trh6->jlain_brjs_ll;
            
            //-------- TOTAL PENERIMAAN
            $jmtrmgaji_ll =  $trh1->sp2d_gj_ll+ $trh2->jppn_gaji_ll + $trh3->jpph21_gaji_ll +
                             $trh4->jpph22_gaji_ll + $trh5->jpph23_gaji_ll + $trh6->jlain_gaji_ll+ $trh15->ppnpn_gaji_ll+
							 $trh70->gj_iwp_lalu + $trh71->gj_tap_lalu+$trh72->gj_pph4_lalu;
            
            $jmtrmgaji_ini =  $trh1->sp2d_gj_ini + $trh2->jppn_gaji_ini + $trh3->jpph21_gaji_ini +
                             $trh4->jpph22_gaji_ini + $trh5->jpph23_gaji_ini + $trh6->jlain_gaji_ini+ $trh15->ppnpn_gaji_ini+
							 $trh70->gj_iwp_ini + $trh71->gj_tap_ini+$trh72->gj_pph4_ini;
                             
            $jmtrmgaji_sd = $jmtrmgaji_ll + $jmtrmgaji_ini;
            
            
            $jmtrmbrjs_ll =  $trh1->sp2d_brjs_ll + $trh2->jppn_brjs_ll + $trh3->jpph21_brjs_ll +
                             $trh4->jpph22_brjs_ll + $trh5->jpph23_brjs_ll + $trh6->jlain_brjs_ll + $trh15->ppnpn_brjs_ll+
							 $trh70->ls_iwp_lalu + $trh71->ls_tap_lalu+$trh72->ls_pph4_lalu;
                                                                                                 
            $jmtrmbrjs_ini =  $trh1->sp2d_brjs_ini + $trh2->jppn_brjs_ini + $trh3->jpph21_brjs_ini +
                             $trh4->jpph22_brjs_ini + $trh5->jpph23_brjs_ini + $trh6->jlain_brjs_ini + $trh15->ppnpn_brjs_ini +
							 $trh70->ls_iwp_ini + $trh71->ls_tap_ini + $trh72->ls_pph4_ini;
                             
            $jmtrmbrjs_sd = $jmtrmbrjs_ll + $jmtrmbrjs_ini;
            
            $jmtrmup_ll =  $trh1->sp2d_up_ll + $trh2->jppn_up_ll + $trh3->jpph21_up_ll +
                             $trh4->jpph22_up_ll + $trh5->jpph23_up_ll + $trh6->jlain_up_ll+$tox + $trh15->ppnpn_up_ll+
							 $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_lalu;                           
            
            $jmtrmup_ini =  $trh1->sp2d_up_ini + $trh2->jppn_up_ini + $trh3->jpph21_up_ini +
                             $trh4->jpph22_up_ini + $trh5->jpph23_up_ini + $trh6->jlain_up_ini + $trh15->ppnpn_up_ini+
							 $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_ini;
            
            $jmtrmup_sd = $jmtrmup_ll + $jmtrmup_ini;
            
            
            $cRet .="
                       
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Lain-lain</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_gaji_ll + $trh6->jlain_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_brjs_ll + $trh6->jlain_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh6->jlain_up_ll + $trh6->jlain_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totallain,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>
            
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">Jumlah Penerimaan :</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmbrjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmbrjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmbrjs_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmup_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmup_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmup_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr> 
           
           
            
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"center\" style=\"font-size:12px\" colspan=\"2\">&nbsp;</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>
            
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">Pengeluaran :</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
           $csql = "select sum(gaji_lalu) as spj_gaji_ll, sum(gaji_ini) as spj_gaji_ini, sum(brg_lalu) as spj_brjs_ll, 
				sum(brg_ini) as spj_brjs_ini, sum(up_lalu) as spj_up_ll, sum(up_ini) as spj_up_ini from
				(select  a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  and jns_spp in (1,2,3) 
				union all
				select  a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.TGL_BUKTI) BETWEEN '$tgl1' AND '$tgl2'  and b.pengurang_belanja=1
				union all
				select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  and jns_spp in (4)
				union all
				select  a.kd_skpd, isnull(a.rupiah*-1,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where (b.tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  and b.jns_cp in (1) and b.pot_khusus<>0
				union all
				select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  and jns_spp in (6)
				union all
				select  a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where (b.tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  and b.jns_cp in (2) and b.pot_khusus<>0
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.tgl_bukti)<'$tgl1' and jns_spp in (1,2,3)
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai*-1,0) as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.TGL_BUKTI)<= '$tgl2'  and b.pengurang_belanja=1
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.tgl_bukti)<'$tgl1' and jns_spp in (4)
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.rupiah*-1,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where (b.tgl_sts)<'$tgl1' and b.jns_cp in (1) and b.pot_khusus<>0
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where (b.tgl_bukti)<'$tgl1' and jns_spp in (6)
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where (b.tgl_sts)<'$tgl1' and b.jns_cp in (2) and b.pot_khusus<>0) a 
				WHERE a.kd_skpd='$lcskpd' ";

           
           $hasil = $this->db->query($csql);
           $trh7 = $hasil->row(); 
           $totalspj = $trh7->spj_gaji_ll + $trh7->spj_gaji_ini + $trh7->spj_brjs_ll + 
                       $trh7->spj_brjs_ini + $trh7->spj_up_ll + $trh7->spj_up_ini;
                                                            
           $cRet .="
            <tr>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;- SPJ(LS + UP/GU/TU)</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_gaji_ini + $trh7->spj_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_brjs_ini + $trh7->spj_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh7->spj_up_ini + $trh7->spj_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalspj,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>
            <tr>
			<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;- Penyetoran Pajak</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
           $lcrek = '2130301';//'2110401'; // ppn setor
           $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS jppn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jppn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jppn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jppn_brjs_ll";
            
           $hasil = $this->db->query($csql);
           $trh8 = $hasil->row();
           $totalppn = $trh8->jppn_up_ini + $trh8->jppn_up_ll + $trh8->jppn_gaji_ini + 
                        $trh8->jppn_gaji_ll + $trh8->jppn_brjs_ini + $trh8->jppn_brjs_ll;
            
            $cRet .="
            <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;a. PPN</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_gaji_ll + $trh8->jppn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_brjs_ll + $trh8->jppn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh8->jppn_up_ll + $trh8->jppn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalppn,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
            
            $lcrek = '2130101'; // pph 21 setor
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS jpph21_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jpph21_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jpph21_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jpph21_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh9 = $hasil->row();
            $totalpph21 = $trh9->jpph21_up_ini + $trh9->jpph21_up_ll + $trh9->jpph21_gaji_ini + 
                          $trh9->jpph21_gaji_ll + $trh9->jpph21_brjs_ini + $trh9->jpph21_brjs_ll;
            
            
            $cRet .="
             <tr> <td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_gaji_ll + $trh9->jpph21_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_brjs_ll + $trh9->jpph21_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh9->jpph21_up_ll + $trh9->jpph21_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph21,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
            $lcrek = '2130201'; // pph 22 setor
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS jpph22_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jpph22_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jpph22_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jpph22_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh10 = $hasil->row();
            $totalpph22 = $trh10->jpph22_up_ini + $trh10->jpph22_up_ll + $trh10->jpph22_gaji_ini + 
                        $trh10->jpph22_gaji_ll + $trh10->jpph22_brjs_ini + $trh10->jpph22_brjs_ll;
            
            
            $cRet .="
             <tr>
			 <td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_gaji_ll + $trh10->jpph22_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_brjs_ll + $trh10->jpph22_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh10->jpph22_up_ll + $trh10->jpph22_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph22,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
            $lcrek = '2130401'; // pph 23 setor
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS jpph23_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS jpph23_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS jpph23_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS jpph23_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh11 = $hasil->row();
            $totalpph23 = $trh11->jpph23_up_ini + $trh11->jpph23_up_ll + $trh11->jpph23_gaji_ini + 
                        $trh11->jpph23_gaji_ll + $trh11->jpph23_brjs_ini + $trh11->jpph23_brjs_ll;
            
            
            $cRet .="
             <tr>
			 <td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_gaji_ll + $trh11->jpph23_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_brjs_ll + $trh11->jpph23_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh11->jpph23_up_ll + $trh11->jpph23_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph23,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			$lcrek = "('2110701','2110702','2110703')"; // IWP
            $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS up_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS up_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<='$tgl2'  THEN  a.nilai ELSE 0 END) AS up_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS gj_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<='$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS ls_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_iwp_sdini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek5 in $lcrek AND a.kd_skpd='$lcskpd'";
            
            $hasil = $this->db->query($csql);
            $trh73 = $hasil->row();
            $totaliwp_setor = $trh73->up_iwp_sdini + $trh73->gj_iwp_sdini + $trh73->ls_iwp_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. IWP</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->gj_iwp_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->gj_iwp_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->gj_iwp_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->ls_iwp_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->ls_iwp_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->ls_iwp_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->up_iwp_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->up_iwp_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh73->up_iwp_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totaliwp_setor,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			$lcrek = '2110501'; // TAPERUM
            $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS up_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS up_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS up_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS gj_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<='$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek5='$lcrek' AND a.kd_skpd='$lcskpd'";
            
            $hasil = $this->db->query($csql);
            $trh74 = $hasil->row();
            $totaltap_setor = $trh74->up_tap_sdini + $trh74->gj_tap_sdini + $trh74->ls_tap_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. Taperum</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->gj_tap_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->gj_tap_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->gj_tap_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->ls_tap_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->ls_tap_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->ls_tap_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->up_tap_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->up_tap_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh74->up_tap_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totaltap_setor,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			
			$lcrek = '2130501'; // pph4
            $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<= '$tgl2'  THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek5='$lcrek' AND a.kd_skpd='$lcskpd'";
            
            $hasil = $this->db->query($csql);
            $trh75 = $hasil->row();
            $totalpph4_setor = $trh75->up_pph4_sdini + $trh75->gj_pph4_sdini + $trh75->ls_pph4_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. PPh Pasal 4</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->gj_pph4_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->gj_pph4_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->gj_pph4_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->ls_pph4_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->ls_pph4_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->ls_pph4_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->up_pph4_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->up_pph4_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh75->up_pph4_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalpph4_setor,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			
			
			
			
			
			 $lcrek = '2110901'; // PPnpn
            $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek5 = '$lcrek' AND (a.tgl_bukti)<'$tgl1' AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ll";
            
            $hasil = $this->db->query($csql);
            $trh16 = $hasil->row();
            $totalppnpn = $trh16->ppnpn_up_ini + $trh16->ppnpn_up_ll + $trh16->ppnpn_gaji_ini + 
                        $trh16->ppnpn_gaji_ll + $trh16->ppnpn_brjs_ini + $trh16->ppnpn_brjs_ll;
            
            
            $cRet .="
             <tr>
			 <td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. Iuran Wajib PPNPN</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_gaji_ll + $trh16->ppnpn_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_brjs_ll + $trh16->ppnpn_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh16->ppnpn_up_ll + $trh16->ppnpn_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalppnpn,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			// HKPG
            $csql = "SELECT 
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and (tgl_sts)<'$tgl1' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  then a.rupiah else 0 end),0)) AS up_hkpg_ini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and (tgl_sts)<= '$tgl2'  then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and (tgl_sts)<'$tgl1' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and (tgl_sts)<='$tgl2'  then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and (tgl_sts)<'$tgl1' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and (tgl_sts)<= '$tgl2'  then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
				FROM trdkasin_pkd a 
				INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";
							
            $hasil = $this->db->query($csql);
            $trhxx = $hasil->row();
            $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- HKPG</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->gj_hkpg_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->gj_hkpg_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->gj_hkpg_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->ls_hkpg_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->ls_hkpg_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->ls_hkpg_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->up_hkpg_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->up_hkpg_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxx->up_hkpg_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totalhkpg,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
			// Potongan Penghasilan Lainnya
            $csql = "SELECT 
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and (tgl_sts)<'$tgl1'  then a.rupiah else 0 end),0)) AS up_lain_lalu,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'   then a.rupiah else 0 end),0)) AS up_lain_ini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and (tgl_sts)<= '$tgl2'   then a.rupiah else 0 end),0)) AS up_lain_sdini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and (tgl_sts)<'$tgl1'  then a.rupiah else 0 end),0)) AS ls_lain_lalu,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'   then a.rupiah else 0 end),0)) AS ls_lain_ini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and (tgl_sts)<= '$tgl2'   then a.rupiah else 0 end),0)) AS ls_lain_sdini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and (tgl_sts)<'$tgl1'  then a.rupiah else 0 end),0)) AS gj_lain_lalu,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'   then a.rupiah else 0 end),0)) AS gj_lain_ini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and (tgl_sts)<= '$tgl2'   then a.rupiah else 0 end),0)) AS gj_lain_sdini
				FROM trdkasin_pkd a 
				INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";
							
            $hasil = $this->db->query($csql);
            $trhxy = $hasil->row();
            $totallain = $trhxy->up_lain_sdini + $trhxy->gj_lain_sdini + $trhxy->ls_lain_sdini;
            
            
            $cRet .="
             <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Pot. Penghasilan Lainnya</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->gj_lain_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->gj_lain_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->gj_lain_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->ls_lain_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->ls_lain_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->ls_lain_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->up_lain_lalu,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->up_lain_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trhxy->up_lain_sdini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totallain,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
			
            // CONTRA POS
            $csql = "select 
			SUM(isnull((case when rtrim(jns_cp)= '3' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  then z.nilai else 0 end),0)) AS cp_spj_up_ini,
			SUM(isnull((case when rtrim(jns_cp)= '3' and (tgl_sts)<'$tgl1' then z.nilai else 0 end),0)) AS cp_spj_up_ll,
			SUM(isnull((case when rtrim(jns_cp)= '1' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  then z.nilai else 0 end),0)) AS cp_spj_gaji_ini,
			SUM(isnull((case when rtrim(jns_cp)= '1' and (tgl_sts)<'$tgl1' then z.nilai else 0 end),0)) AS cp_spj_gaji_ll,
			SUM(isnull((case when rtrim(jns_cp)= '2' and (tgl_sts) BETWEEN '$tgl1' AND '$tgl2'  then z.nilai else 0 end),0)) AS cp_spj_brjs_ini,
			SUM(isnull((case when rtrim(jns_cp)= '2' and (tgl_sts)<'$tgl1' then z.nilai else 0 end),0)) AS cp_spj_brjs_ll
			from (select rupiah as nilai,jns_trans,pot_khusus,jns_cp,d.tgl_sts ,d.kd_skpd from 
			trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd where d.kd_skpd ='$lcskpd' AND 
			((jns_trans='5' AND pot_khusus='0') OR jns_trans='1')) z";
            
            $hasil = $this->db->query($csql);
            $trh_x = $hasil->row();
            $total_cp = $trh_x->cp_spj_up_ini + $trh_x->cp_spj_up_ll + $trh_x->cp_spj_gaji_ini + 
                        $trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_brjs_ini + $trh_x->cp_spj_brjs_ll;
            
            
            $cRet .="
             <tr>
			 <td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Contra Pos</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_brjs_ll + $trh_x->cp_spj_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_up_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh_x->cp_spj_up_ll + $trh_x->cp_spj_up_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($total_cp,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";
            
            
            // lain lain setoran
          $csql = "SELECT 
					SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
					SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
					SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
					 FROM(
					SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti)<'$tgl1' AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti)<'$tgl1' AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN b.jns_spp IN ('4') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti)<'$tgl1' AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN b.jns_spp IN ('6') AND (b.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  AND a.kd_rek5 NOT IN ('2130101','2130201','2130401','2130301','2110901','2110701','2110702','2110703','2110501','2130501') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd='$lcskpd'
					UNION ALL
					SELECT 
					SUM(CASE WHEN a.jns_beban='1' AND (a.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN a.jns_beban='1' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN a.jns_beban='4' AND (a.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN a.jns_beban='4' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2'  THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN a.jns_beban='6' AND (a.tgl_bukti)<'$tgl1' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN a.jns_beban='6' AND (a.tgl_bukti) BETWEEN '$tgl1' AND '$tgl2' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
					FROM TRHOUTLAIN a 
					WHERE a.kd_skpd='$lcskpd'
					) a ";
            $hasil = $this->db->query($csql);
            $trh12 = $hasil->row();
            $totallain = $trh12->jlain_up_ini + $trh12->jlain_up_ll + $trh12->jlain_gaji_ini + 
                         $trh12->jlain_gaji_ll + $trh12->jlain_brjs_ini + $trh12->jlain_brjs_ll;
            
            $tox_awal="SELECT isnull(sld_awal,0)+sld_awalpajak AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'= MONTH($tgl1)";
            $hasil = $this->db->query($tox_awal);
            $tox_ini = $hasil->row('jumlah');
//			echo  $tox_ini;			   
            $tox_ini=(empty($tox_ini)?0:$tox_ini);

            $tox_awal="SELECT isnull(sld_awal,0)+sld_awalpajak AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'< MONTH($tgl1)";
            $hasil = $this->db->query($tox_awal);
            $tox_ll = $hasil->row('jumlah');
            $tox_ll=(empty($tox_ll)?0:$tox_ll);

//			echo  '-'.$tox_ini;
//			echo  '-'.$tox_ll;
			
            $jmsetgaji_ll =  $trh7->spj_gaji_ll+ $trh8->jppn_gaji_ll + $trh9->jpph21_gaji_ll + $trh16->ppnpn_gaji_ll +
                             $trh10->jpph22_gaji_ll + $trh11->jpph23_gaji_ll + $trh12->jlain_gaji_ll+ $trh_x->cp_spj_gaji_ll+
							 $trh73->gj_iwp_lalu + $trh74->gj_tap_lalu+$trh75->gj_pph4_lalu+$trhxx->gj_hkpg_lalu+$trhxy->gj_lain_lalu;
            
            $jmsetgaji_ini = $trh7->spj_gaji_ini + $trh8->jppn_gaji_ini + $trh9->jpph21_gaji_ini + $trh16->ppnpn_gaji_ini +
                             $trh10->jpph22_gaji_ini + $trh11->jpph23_gaji_ini + $trh12->jlain_gaji_ini+$trh_x->cp_spj_gaji_ini+
							 $trh73->gj_iwp_ini + $trh74->gj_tap_ini+$trh75->gj_pph4_ini+$trhxx->gj_hkpg_ini+$trhxy->gj_lain_ini;
                             
            $jmsetgaji_sd = $jmsetgaji_ll + $jmsetgaji_ini;
            
            
            $jmsetbrjs_ll =  $trh7->spj_brjs_ll + $trh8->jppn_brjs_ll + $trh9->jpph21_brjs_ll + $trh16->ppnpn_brjs_ll +
                             $trh10->jpph22_brjs_ll + $trh11->jpph23_brjs_ll + $trh12->jlain_brjs_ll+$trh_x->cp_spj_brjs_ll+
							 $trh73->ls_iwp_lalu + $trh74->ls_tap_lalu+$trh75->ls_pph4_lalu+$trhxx->ls_hkpg_lalu+$trhxy->ls_lain_lalu;
                                                                                                 
            $jmsetbrjs_ini =  $trh7->spj_brjs_ini + $trh8->jppn_brjs_ini + $trh9->jpph21_brjs_ini + $trh16->ppnpn_brjs_ini +
                             $trh10->jpph22_brjs_ini + $trh11->jpph23_brjs_ini + $trh12->jlain_brjs_ini+$trh_x->cp_spj_brjs_ini+
							 $trh73->ls_iwp_ini + $trh74->ls_tap_ini+$trh75->ls_pph4_ini+$trhxx->ls_hkpg_ini+$trhxy->ls_lain_ini;
                             
            $jmsetbrjs_sd = $jmsetbrjs_ll + $jmsetbrjs_ini;
            /* 
            $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll +
                             $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll; */
            
            $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll + $trh16->ppnpn_up_ll +
                             $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll + $tox_ll+$trh_x->cp_spj_up_ll+
							 $trh73->up_iwp_lalu + $trh74->up_tap_lalu+$trh75->up_pph4_lalu+$trhxx->up_hkpg_lalu+$trhxy->up_lain_lalu;                             
            
            $jmsetup_ini =  $trh7->spj_up_ini + $trh8->jppn_up_ini + $trh9->jpph21_up_ini + $trh16->ppnpn_up_ini +
                             $trh10->jpph22_up_ini + $trh11->jpph23_up_ini + $trh12->jlain_up_ini+$tox_ini+$trh_x->cp_spj_up_ini+
							 $trh73->up_iwp_ini + $trh74->up_tap_ini+$trh75->up_pph4_ini+$trhxx->up_hkpg_ini+$trhxy->up_lain_ini;
            
            $jmsetup_sd = $jmsetup_ll + $jmsetup_ini;
            
            
            $cRet .="
                       
            <tr>
			<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- Lain-lain</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_gaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_gaji_ll + $trh12->jlain_gaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_brjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_brjs_ll + $trh12->jlain_brjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_up_ll+$tox_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_up_ini+$tox_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($trh12->jlain_up_ll + $trh12->jlain_up_ini+$tox_ll+$tox_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($totallain,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>";

            $cRet .="
            <tr>
			<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">Jumlah Pengeluaran :</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetgaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetgaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetgaji_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetbrjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetbrjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetbrjs_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetup_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetup_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetup_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmsetgaji_sd + $jmsetbrjs_sd + $jmsetup_sd,"2",",",".")."&nbsp;</td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr> 
                        
            <tr>
			<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"center\" style=\"font-size:12px\" colspan=\"2\">&nbsp;</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>
            <tr>
			<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">Saldo Kas</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_ll - $jmsetgaji_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_ini - $jmsetgaji_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_sd - $jmsetgaji_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmbrjs_ll - $jmsetbrjs_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmbrjs_ini - $jmsetbrjs_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmbrjs_sd - $jmsetbrjs_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmup_ll - $jmsetup_ll,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmup_ini - $jmsetup_ini,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmup_sd - $jmsetup_sd,"2",",",".")."&nbsp;</td>
                <td align=\"right\" style=\"font-size:12px\">".number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd - $jmsetgaji_sd - $jmsetbrjs_sd - $jmsetup_sd,"2",",",".")."&nbsp;</td>
           <td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
		   </tr>
            <tr>
			<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
                <td align=\"center\" style=\"font-size:12px\" colspan=\"2\">&nbsp;</td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
                <td align=\"center\" style=\"font-size:12px\"></td>
				<td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            </tr>
            </table>";
       if($jenis=='1'){
		    $cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$jabatan.'</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b><u>'.$nama2.'</u></b> <br> '.$pangkat.' </TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b><u>'.$nama1.'</u></b><br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD align="center" >'.$nip.'</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$nip1.'</TD>
					</TR>
					</TABLE><br/>';
	   } else if ($jenis=='2'){
		    $cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$jabatan1.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" > </TD>
						<TD width="50%" align="center" ><b><u>'.$nama1.'</u></b><br> '.$pangkat1.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >NIP. '.$nip1.'</TD>
					</TR>
					</TABLE><br/>';
	   } else {
		    $cRet .='<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$daerah.', '.$this->tukd_model->tanggal_format_indonesia($tgl_ctk).'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >'.$jabatan.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" > </TD>
						<TD width="50%" align="center" ><b><u>'.$nama2.'</u></b><br> '.$pangkat.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ></TD>
						<TD width="50%" align="center" >NIP. '.$nip.'</TD>
					</TR>
					</TABLE><br/>';
	   }
        
	$data['prev']= $cRet;
	if($ctk==0){
	echo "<title>  SPJ </title>" ; 
	echo $cRet; 
	} else{
	    $this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
	}	
    }
    
	//BLUD
	function transout_blud(){
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI');   
        $this->template->load('template','tukd/transaksi2/transout_blud',$data) ; 
    }
	function load_rek_blud() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        //$sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
            
       //$stsubah =$this->rka_model->get_nama($kode,'status_ubah','trhrka','kd_skpd');
		//$stssempurna =$this->rka_model->get_nama($kode,'status_sempurna','trhrka','kd_skpd');
       

        if ($rek !=''){        
            $notIn = " and kd_rek5 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
		
		
		 /*if (($stsubah==0) && ($stssempurna==0)){
			$field='nilai';		
		}else if (($stsubah==0) && ($stssempurna==1)){
			$field='nilai_sempurna';				
		} else{
			$field='nilai_ubah';
		}*/
			$field='nilai_ubah';
		
        
            $sql = "SELECT a.kd_rek5,a.nm_rek5,
                    (SELECT SUM(nilai) FROM 
						(SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout_blud c
						LEFT JOIN trhtransout_blud d ON c.no_bukti = d.no_bukti
						AND c.kd_skpd = d.kd_skpd
						WHERE
							c.kd_kegiatan = a.kd_kegiatan
						AND d.kd_skpd = a.kd_skpd
						AND c.kd_rek5 = a.kd_rek5
						AND c.no_bukti <> '$nomor'
						AND d.jns_spp='$jenis'
						UNION ALL
						SELECT SUM(x.nilai) as nilai FROM trdspp x
						INNER JOIN trhspp y 
						ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
						WHERE
							x.kd_kegiatan = a.kd_kegiatan
						AND x.kd_skpd = a.kd_skpd
						AND x.kd_rek5 = a.kd_rek5
						AND y.jns_spp IN ('3','4','5','6'))r) AS lalu,
                    0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, nilai_ubah AS nilai_ubah
                    FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn ";
                    
           
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek5'],  
                        'nm_rek5' => $resulte['nm_rek5'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'anggaran' => $resulte['anggaran'],
                        'anggaran_semp' => $resulte['nilai_sempurna'],
                        'anggaran_ubah' => $resulte['nilai_ubah']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();       	   
	}

	function load_sisa_tunai_blud(){
		$kd_skpd  = $this->session->userdata('kdskpd');        
        $query1 = $this->db->query("SELECT SUM(nilai) as nilai FROM tr_terima WHERE kd_skpd='$kd_skpd' AND kd_rek5='4141501'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'sisa' => number_format($resulte['nilai'],2,'.',',')                      
                        );
                        $ii++;
        }
           
           //return $result;
		   echo json_encode($result);
            $query1->free_result();	
	}
	
	function load_trskpd_blud() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        
        $jns_beban='';
        $cgiat = '';
        if ($jenis ==4){
            $jns_beban = "and a.jns_kegiatan='51'";
        }
		else{
			$jns_beban = "and a.jns_kegiatan='52'";
		}
        if ($giat !=''){                               
            $cgiat = " and a.kd_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        $sql = "SELECT a.kd_kegiatan,b.nm_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan
                WHERE a.kd_skpd='$cskpd' AND a.status_keg='1' $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_kegiatan) LIKE UPPER('%$lccr%'))";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_kegiatan'],
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program'],
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();    	   
	}
    
    function load_transout_blud(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
        $sql = "SELECT ISNULL(MAX(tgl_terima),'2016-01-01') as tgl_terima FROM trhspj_ppkd WHERE cek='1' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res)
        {
         $tgl_terima = $res['tgl_terima'];
		}
	   
        $sql = "SELECT count(*) as total from trhtransout_blud a where  a.kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
        
		$sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
		(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END ) ketspj FROM trhtransout_blud a  
        WHERE   a.kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_blud a  
        WHERE   a.kd_skpd='$kd_skpd' $where order by a.no_bukti)  order by a.no_bukti,kd_skpd";

		/*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
		*/
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_bukti' => $resulte['no_bukti'],
                        'tgl_bukti' => $resulte['tgl_bukti'],
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'tgl_update' => $resulte['tgl_update'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'total' => $resulte['total'],
                        'no_tagih' => $resulte['no_tagih'],
                        'sts_tagih' => $resulte['sts_tagih'],
                        'tgl_tagih' => $resulte['tgl_tagih'],                       
                        'jns_beban' => $resulte['jns_spp'],
                        'pay' => $resulte['pay'],
                        'no_kas_pot' => $resulte['no_kas_pot'],
                        'tgl_pot' =>  $resulte['tgl_pot'],
                        'ketpot' => $resulte['kete'],                                                                                            
                        'ketlpj' => $resulte['ketlpj'],                                                                                            
                        'ketspj' => $resulte['ketspj'],                                                                                            
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
	
	function simpan_transout_blud(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $tgl_koreksi = $this->input->post('ctgl_koreksi');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout_blud') {
            $sql = "delete from trhtransout_blud where kd_skpd='$skpd' and no_bukti='$nomor'";
			$asg = $this->db->query($sql);
			
            if ($asg){
				$sql = "insert into trhtransout_blud(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','3','')";
                $asg = $this->db->query($sql);
				} else {
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
				}
            
        }elseif($tabel == 'trdtransout_blud') {
            // Simpan Detail //                       
                $sql = "delete from trdtransout_blud where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout_blud(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
	
    function simpan_transout_blud_edit(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku   = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $tgl_koreksi   = $this->input->post('ctgl_koreksi');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
           
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout_blud') {
            $sql = "delete from trhtransout_blud where kd_skpd='$skpd' and no_bukti='$no_bku'";
			$asg = $this->db->query($sql);
			

            if ($asg){
                
				$sql = "insert into trhtransout_blud(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','3','')";
                $asg = $this->db->query($sql);

				             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }else if($tabel == 'trdtransout_blud') {
           
            // Simpan Detail //                       
                $sql = "delete from trdtransout_blud where no_bukti='$no_bku' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
				
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout_blud(no_bukti,no_sp2d,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,nilai,kd_skpd)"; 
                    $asg = $this->db->query($sql.$csql);
					if (!($asg)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                }
        }
    }
	
	
	function load_dtransout_blud(){ 
		$kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
				FROM trhtransout_blud a INNER JOIN trdtransout_blud b ON a.no_bukti=b.no_bukti 
				AND a.kd_skpd=b.kd_skpd 
				WHERE a.no_bukti='$nomor' AND a.kd_skpd='$kd_skpd'
				ORDER BY b.kd_kegiatan,b.kd_rek5";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_kegiatan'   => $resulte['kd_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek5'],
                        'nm_rek5'       => $resulte['nm_rek5'],
                        'nilai'         => $resulte['nilai'],
                        'lalu'          => $resulte['lalu'],
                        'sp2d'          => $resulte['sp2d'],   
                        'anggaran'      => $resulte['anggaran']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
	function hapus_transout_blud(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout_blud where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

		if ($asg){
            $sql = "delete from trhtransout_blud where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);

          
			if (!($asg)){
              $msg = array('pesan'=>'0');
              echo json_encode($msg);
               exit();
            } 
        } else {
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
    }
    	
	function bku_blud(){
        $data['page_title']= 'BKU BLUD';
        $this->template->set('title', 'BKU BLUD');   
        $this->template->load('template','tukd/transaksi2/bku_blud',$data) ; 
    }
	
	function _mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='') {
                

        ini_set("memory_limit","-1M");
        ini_set("MAX_EXECUTION_TIME","-1");
        $this->load->library('mpdf');
		//$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        
        
        $this->mpdf->defaultheaderfontsize = 10;	/* in pts */
        $this->mpdf->defaultheaderfontstyle = I;	/* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3;	/* in pts */
        $this->mpdf->defaultfooterfontstyle = I;	/* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
		$sa=1;
		$tes=0;
		if ($hal==''){
		$hal1=1;
		} 
		if($hal!==''){
		$hal1=$hal;
		}
		if ($fonsize==''){
		$size=12;
		}else{
		$size=$fonsize;
		} 
		
		$this->mpdf = new mPDF('utf-8', array(215,330),$size); //folio
							//$this->mpdf->useOddEven = 1;						

        $this->mpdf->AddPage($orientasi,'',$hal,'1','off');
		if ($hal==''){
			$this->mpdf->SetFooter("Printed on Simakda SKPD ||  ");
		}
		else{
			$this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
		}
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output();
               
    }

	
	
}