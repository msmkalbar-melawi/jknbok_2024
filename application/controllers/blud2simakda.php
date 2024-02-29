<?php  

class blud2simakda extends CI_Controller {

    function __construct() {
        parent::__construct();
    }


	function sp3b_blud(){
 		$result=json_decode($_POST['trhsp3b_blud'],true);
 		$result=json_encode($result);
 		$result1= json_decode($result);

 		/*data trsp3b_blud*/
 		$result=json_decode($_POST['trsp3b_blud'],true);
 		$result=json_encode($result);
 		$result2= json_decode($result);


 		$bulan=$_POST['bulan'];

		/*INSERT TRHSP3B ================================*/


		$this->db->query("DELETE trhsp3b_blud where bulan='$bulan'");
		foreach($result1 as $value){
			    $no_sp3b= $value->no_sp3b;
	            $no_sp3b= $value->no_sp3b;
	            $kd_skpd= $value->kd_skpd;
	            $keterangan= $value->keterangan;
	            $tgl_sp3b= $value->tgl_sp3b;
	            $status= $value->status;
	            $tgl_awal= $value->tgl_awal;
	            $tgl_akhir= $value->tgl_akhir; 
	            $no_lpj= $value->no_lpj;
	            $total= $value->total;
	            $skpd= $value->skpd;
	            $bulan= $value->bulan;
	            $tgl_update= $value->tgl_update;
	            $username= $value->username;
	            $status_bud= $value->status_bud;
	            $no_sp2b= $value->no_sp2b;
	            $tgl_sp2b= $value->tgl_sp2b;
	            $number_sp2b= $value->number_sp2b;

			$this->db->query("INSERT into
			 trhsp3b_blud (no_sp3b, kd_skpd, keterangan, tgl_sp3b, status, tgl_awal,tgl_akhir,
							no_lpj, total, skpd, bulan, tgl_update, username, status_bud, no_sp2b, tgl_sp2b, number_sp2b)

				values ('$no_sp3b','$kd_skpd','$keterangan','$tgl_sp3b','$status','$tgl_awal','$tgl_akhir','$no_lpj',
				    '$total','$skpd','$bulan','$tgl_update','$username','$status_bud','$no_sp2b','$tgl_sp2b','$number_sp2b')");
		}


		/*INSERT TRSP3B ================================*/

        
		$this->db->query("DELETE trsp3b_blud where month(tgl_sp3b)='$bulan'");

		foreach($result2 as $value){
	            $no_sp3b= $value->no_sp3b;
	            $no_bukti= $value->no_bukti;
	            $keterangan= $value->keterangan;
	            $tgl_sp3b= $value->tgl_sp3b;
	            $kd_rek5= $value->kd_rek5;
	            $nm_rek5= $value->nm_rek5;
	            $nilai= $value->nilai;
	            $kd_skpd= $value->kd_skpd;
	            $kd_kegiatan= $value->kd_kegiatan;
	            $no_lpj= $value->no_lpj;

			$this->db->query("INSERT into
			 trsp3b_blud (no_sp3b, no_bukti, keterangan, tgl_sp3b, kd_rek6, nm_rek6,nilai,
							kd_skpd, kd_sub_kegiatan, no_lpj)

				values ('$no_sp3b','$no_bukti','$keterangan','$tgl_sp3b','$kd_rek5','$nm_rek5','$nilai','$kd_skpd',
				    '$kd_kegiatan','$no_lpj')");
		}

		echo json_encode(1);

	} /*end function*/


	function data_anggaran_pukesmas($kd_skpd=''){
		if($kd_skpd==''){
			$filter="";
		}else{
			$filter="and kd_skpd='$kd_skpd'";
		}

			$sql="SELECT left(no_trdrka,22) kd_skpd, kd_sub_kegiatan, kd_rek6, nm_rek6,
sum(nilai) nilai, sum(nilai_sempurna) geser, sum(nilai_ubah) ubah from trdrka WHERE left(kd_skpd,4)='1.02' and right(kd_rek6,4)='9999'
$filter
GROUP BY kd_sub_kegiatan, kd_rek6,left(no_trdrka,22),nm_rek6
ORDER BY kd_skpd";
			$exe=$this->db->query($sql);

	        $data1 = array();
	        $ii = 0;
	        foreach($exe->result_array() as $a)
	        { 	           
	            $data1[] = array(
	                        'id' => $ii, 
	                        'kd_skpd' => $a['kd_skpd'],
	                        'kegiatan' => $a['kd_sub_kegiatan'],
	                        'kd_rek5'=> $a['kd_rek6'],
	                        'nm_rek5'=> $a['nm_rek6'],
	                        'nilai'  => $a['nilai'],
	                        'nilai_sempurna'=> $a['geser'],
	                        'nilai_ubah' => $a['ubah']
	                        );
	                        $ii++;
	        }


	    echo json_encode($data1);
	

	}

	function jurnal_blud(){
 		$result=json_decode($_POST['data_header'],true);
 		$result=json_encode($result);
 		$data_h= json_decode($result);

 		/*data trsp3b_blud*/
 		$result=json_decode($_POST['data_detail'],true);
 		$result=json_encode($result);
 		$data_d= json_decode($result);


 		$bulan=$_POST['bulan'];

		/*INSERT header ju ================================*/

		$tgl_update=date("Y-m-d h:i:s");
		$this->db->query("DELETE trhju_pkd where tabel='99' and reev='99' and month(tgl_voucher)='$bulan'");
		foreach($data_h as $value){
			    $no_voucher= $value->no_voucher;
	            $tgl_voucher= $value->tgl_voucher;
	            $kd_skpd= $value->kd_skpd;
	            $nm_skpd= $value->nm_skpd;
	            $debet= $value->debet;
	            $kredit= $value->kredit;
	            $username= $value->username;
	            $keterangan= $value->keterangan;
	            $map_real= $value->map_real;
	            $tabel= $value->tabel;
	            $reev= $value->reev;

			$this->db->query("INSERT into
			 trhju_pkd (no_voucher, tgl_voucher, kd_skpd, nm_skpd, ket, tgl_update, username, kd_unit, map_real, total_d, total_k, tabel, reev)

				values ('$no_voucher','$tgl_voucher', '$kd_skpd', '$nm_skpd', '$keterangan', '$tgl_update', '$username', '$kd_skpd', '$map_real', '$debet', '$kredit', '$tabel','$reev' )");
		}


		/*INSERT detail ju ================================*/

        
		$this->db->query("DELETE trdju_pkd where map_real='99' and kd_subkegiatan='$bulan'");

		foreach($data_d as $value){
				$no_voucher 	= $value->no_voucher;
				$kd_kegiatan 	= $value->kd_kegiatan;
				$kd_skpd		= $value->kd_skpd;
				$rek			= $value->rek;
				$rk 			= $value->rk;
				$debet 			= $value->debet;
				$kredit			= $value->kredit;
				$map_real		= $value->map_real;
				$bulan			= $value->bulan;

			$this->db->query("INSERT into
			 trdju_pkd (no_voucher, kd_sub_kegiatan, kd_unit, kd_rek6, rk, debet, kredit, map_real, kd_subkegiatan)

				values ('$no_voucher', '$kd_kegiatan', '$kd_skpd', '$rek', '$rk', '$debet', '$kredit', '$map_real', '$bulan')");
		}
			$this->db->query("UPDATE a set
				a.nm_rek6=b.nm_rek6
				from trdju_pkd a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6
				where map_real='99' and kd_subkegiatan='$bulan'
				");

			$this->db->query("UPDATE a set
				a.nm_sub_kegiatan=b.nm_sub_kegiatan
				from trdju_pkd a inner join ms_sub_kegiatan b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
				where map_real='99' and kd_subkegiatan='$bulan'
				");
		echo json_encode(1);

	} /*end function*/



} /*end of end*/