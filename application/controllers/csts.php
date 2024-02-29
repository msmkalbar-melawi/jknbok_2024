<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class csts extends CI_Controller {

    public $org_keu = "";
    public $skpd_keu = "";
    
    function __contruct(){   
        parent::__construct();
    }


   function validasi_sts_bp(){
        $skpd     = $this->input->post('cskpd');
        $tglvalid1     = $this->input->post('tglvalid1');
        $tglvalid2     = $this->input->post('tglvalid2');
        
		$msg        = array();

		$tanggal1  = explode('-',$tglvalid1); 
        $bulan1  = $tanggal1[1];
		
		$tanggal2  = explode('-',$tglvalid2); 
        $bulan2  = $tanggal2[1];
		
		$query1 = $this->db->query("select case when max(nomor) is null then 0 else max(nomor) end as nomor from (
											select no_kas nomor,'Terima STS' ket from trhkasin_ppkd where isnumeric(no_kas)=1
											UNION ALL
											select no_kas nomor,'Terima STS' ket from trhrestitusi where isnumeric(no_kas)=1
											UNION ALL
											select nomor,'Terima non SP2D' ket from penerimaan_non_sp2d where isnumeric(nomor)=1
											UNION ALL
											select nomor,'keluar non SP2D' ket from pengeluaran_non_sp2d where isnumeric(nomor)=1
											UNION ALL
											select no,'koreksi' ket from trkasout_ppkd where isnumeric(no)=1
											) z");
		$prvn = $query1->row();          
		$num = $prvn->nomor;
		
			$sql = "update trhkasin_pkd set status='1' where kd_skpd='$skpd' and (tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2')";
			$asg = $this->db->query($sql);
			
			$sql2 = "delete b from trhkasin_ppkd a inner join trdkasin_ppkd b on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas
					 where a.jns_trans IN ('4','2') and a.kd_bank<>'1' and b.kd_skpd='$skpd' and (a.tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2')
					 AND b.no_sts+b.kd_skpd NOT IN (select b.no_sts+b.kd_skpd from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
					 where a.jns_trans IN ('4','2') and a.status='1' and b.kd_skpd='$skpd' and (a.tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2')) ";
			$asg = $this->db->query($sql2);
			
			$sql1 = "delete from trhkasin_ppkd 
					 where jns_trans IN ('4','2') and kd_bank<>'1' and kd_skpd='$skpd' and (tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2')
					 AND no_sts+kd_skpd NOT IN (select no_sts+kd_skpd from trhkasin_pkd 
					 where jns_trans IN ('4','2') and status='1' and kd_skpd='$skpd' and (tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2'))";
			$asg = $this->db->query($sql1);
			
			$sql3 = "insert into trdkasin_ppkd
						SELECT kd_skpd, no_sts, kd_rek6, rupiah, kd_sub_kegiatan, no_kas, sumber FROM (
						select b.kd_skpd, b.no_sts, b.kd_rek6, b.rupiah, b.kd_sub_kegiatan,'' no_kas, b.sumber 
						from trdkasin_pkd b inner join trhkasin_pkd a on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						where a.jns_trans IN ('4','2') AND (a.tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2') 
						AND LEFT(b.kd_skpd,17) IN ('5.02.0.00.0.00.01') 
						AND b.kd_skpd NOT IN ('5.02.0.00.0.00.01.0000','5.02.0.00.0.00.02.0000')
						AND a.status=1) x 
						WHERE kd_skpd = '$skpd' AND no_sts+kd_skpd not in (select b.no_sts+b.kd_skpd from trhkasin_ppkd b where (tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2') AND b.kd_skpd='$skpd' AND jns_trans IN ('4','2'))";
			$asg = $this->db->query($sql3);
			
			$sql4 = "insert into trhkasin_ppkd(no_kas,tgl_kas,no_sts,kd_skpd,tgl_sts,keterangan,total,kd_sub_kegiatan,jns_trans,sumber) 
						select $num+ROW_NUMBER() OVER (ORDER BY tgl_kas) AS no_kas, tgl_sts, no_sts, kd_skpd, tgl_sts, keterangan, total, kd_sub_kegiatan, jns_trans, sumber 
						FROM (
						SELECT a.*,(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_skpd 
										FROM trhkasin_pkd a  
										WHERE jns_trans IN ('4','2') AND (a.tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2') 
											  AND LEFT(kd_skpd,17) IN ('5.02.0.00.0.00.01') 
											  AND kd_skpd NOT IN ('5.02.0.00.0.00.01.0000','5.02.0.00.0.00.02.0000')
											  AND status=1
											  AND no_sts+kd_skpd not in (select no_sts+kd_skpd from trhkasin_ppkd WHERE jns_trans IN ('4','2') AND (a.tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2') AND LEFT(kd_skpd,17) IN ('5.02.0.00.0.00.01') 
											  AND kd_skpd NOT IN ('5.02.0.00.0.00.01.0000','5.02.0.00.0.00.02.0000')))x WHERE kd_skpd = '$skpd'";
			$asg = $this->db->query($sql4);
			
			$sql5 = "update b set b.no_kas=a.no_kas
								from trdkasin_ppkd b inner join trhkasin_ppkd a 
								on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
								where LEFT(b.kd_rek6,1)=4 AND b.kd_skpd = '$skpd' AND (a.tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2') AND a.jns_trans in ('4','2')";
			$asg = $this->db->query($sql5);
			
			$sql = "update a set a.no_cek=1
								from trhkasin_ppkd b inner join trhkasin_pkd a 
								on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts and a.kd_sub_kegiatan=b.kd_sub_kegiatan
								where a.jns_trans in ('4','2') AND a.kd_skpd = '$skpd' AND (a.tgl_sts BETWEEN '$tglvalid1' and '$tglvalid2')";
						$asg = $this->db->query($sql);
			
			if($asg){
				$msg = array('pesan'=>'1');
				echo json_encode($msg);
			} else {
				$msg = array('pesan'=>'0');
				echo json_encode($msg);
			}
		
    }

}