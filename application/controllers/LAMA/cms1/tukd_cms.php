<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class tukd_cms extends CI_Controller {

	public $org_keu = "3.13.01";
	public $skpd_keu = "3.13.01.01";
	
	function __contruct(){	
		parent::__construct();
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
    

		
   function no_urut_cms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
	$tgl = date('Y-m-d');
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
	select no_voucher nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' union
    select no_bukti nomor, 'Potongan Pajak Transaksi Non Tunai' ket, kd_skpd from trhtrmpot_cmsbank where kd_skpd = '$kd_skpd' union
    select no_panjar nomor, 'Daftar Panjar' ket, kd_skpd from tr_panjar_cmsbank where kd_skpd = '$kd_skpd' 
    ) z WHERE KD_SKPD = '$kd_skpd'");
	    $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $resulte['nomor']
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }
    
    function no_urut_tglcms(){
    $kd_skpd = $this->session->userdata('kdskpd');     
    date_default_timezone_set("Asia/Bangkok");
    $tgl = date('Y-m-d');
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
	select no_tgl nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where isnumeric(no_panjar)=1 and kd_skpd = '$kd_skpd' and tgl_voucher='$tgl') z WHERE KD_SKPD = '$kd_skpd'");
	    $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $resulte['nomor']
                        );
                        $ii++;
        }
		
        echo json_encode($result);
    	$query1->free_result();   
    }
    
    function cari_rekening(){		
		$lccr =  $this->session->userdata('kdskpd');        
        
        $sql = "SELECT top 1 rekening FROM ms_skpd where kd_skpd='$lccr' order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rek_bend' => $resulte['rekening']
                        );                        
        }
        echo json_encode($result);                        
                        
	}

    function cari_bank()
	{				
        $sql = "SELECT kode,nama FROM ms_bank";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'kode' => $resulte['kode'],     
                        'nama' => $resulte['nama']
                        );                        
        }
           
        echo json_encode($result);    	
	}                                            
    
    function cari_rekening_tujuan($jenis='')
	{				
	    $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');

        if($jenis==1){
            $jenis = "('1','2')";
        }else{
            $jenis = "('3')";
        }		
        
        $sql = "
		SELECT * FROM (
		SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,
        a.keterangan,a.kd_skpd,a.jenis FROM ms_rekening_bank a where a.jenis in $jenis and kd_skpd='$skpd') a
		WHERE upper(rekening) like upper('%$lccr%') or upper(nm_rekening) like upper('%$lccr%') or upper(bank) like upper('%$lccr%') or upper(nmbank) like upper('%$lccr%')
         order by a.nm_rekening";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rekening' => $resulte['rekening'],     
                        'nm_rekening' => $resulte['nm_rekening'],
                        'bank' => $resulte['bank'],     
                        'nmbank' => $resulte['nmbank'],     
                        'kd_skpd' => $resulte['kd_skpd'],
                        'jenis' => $resulte['jenis'],
                        'ket' => $resulte['keterangan']
                        );                        
        }
           
        echo json_encode($result);    	
	}

    function load_rek() {                      
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
						AND d.jns_spp='$jenis'
						UNION ALL
					SELECT SUM(nilai) FROM 
						(SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout_cmsbank c
						LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
						AND c.kd_skpd = d.kd_skpd
						WHERE
							c.kd_kegiatan = a.kd_kegiatan
						AND d.kd_skpd = a.kd_skpd
						AND c.kd_rek5 = a.kd_rek5
						AND c.no_voucher <> '$nomor'
						AND d.jns_spp='$jenis'
						AND d.status_validasi<>'1'
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
						)r) r) AS lalu,
						0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, nilai_ubah AS nilai_ubah
						FROM trdrka a WHERE a.kd_kegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn  order by a.kd_rek5";
                    
        } else {
            $sql = "SELECT b.kd_rek5,b.nm_rek5,
                    (SELECT SUM(c.nilai) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
					WHERE c.kd_kegiatan = b.kd_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
					AND c.kd_rek5=b.kd_rek5 AND c.no_voucher <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
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
    
    
    function csv_cmsbank($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
        
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='OB';                 
        //and a.tgl_upload='$tgl'
        $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
        b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,SUBSTRING(b.ket_tujuan+'/'+RIGHT(e.kd_kegiatan,5)+'/'+c.nm_rekening_tujuan,0,30) ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank a 
        left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher
        left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
		left join trdtransout_cmsbank e on b.kd_skpd=e.kd_skpd AND b.no_voucher=e.no_voucher
        where $init_skpd and a.no_upload='$nomor' and d.bank='12'");
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
           	$nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($resulte['rekening_awal'])).";".rtrim($resulte['nm_rekening_tujuan']).";".str_replace(" ","",rtrim($resulte['rekening_tujuan'])).";".$nilai.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }
	
	function csv_cmsbank_lain($nomor=''){
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $init_skpd = "a.kd_skpd='$skpd'";
        
        $obskpd = $this->tukd_model->get_nama($skpd,'obskpd','ms_skpd','kd_skpd');
        
        $cRet ='';
        $data='';
        $jdul='SKN';                 
        //and a.tgl_upload='$tgl'
        $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=b.kd_skpd) as nm_skpd,
        b.rekening_awal,c.nm_rekening_tujuan,c.rekening_tujuan,c.nilai,SUBSTRING(b.ket_tujuan+'/'+RIGHT(f.kd_kegiatan,5)+'/'+c.nm_rekening_tujuan,0,30) ket_tujuan,b.no_upload_tgl,e.bic FROM trhupload_cmsbank a 
        left join trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        left join trdtransout_transfercms c on b.kd_skpd=c.kd_skpd and c.no_voucher=b.no_voucher and c.tgl_voucher=b.tgl_voucher
        left join ms_rekening_bank d on RTRIM(d.rekening)=RTRIM(c.rekening_tujuan) and d.kd_skpd=b.kd_bp
        left join ms_bank e on e.kode=d.bank
		left join trdtransout_cmsbank f on b.kd_skpd=f.kd_skpd AND b.no_voucher=f.no_voucher
        where $init_skpd and a.no_upload='$nomor' and d.bank<>'12'");
        
        foreach($sqlquery->result_array() as $resulte)
        {            
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
           	$nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00","",$nilai);
            $idr = "IDR";
            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'].";".str_replace(" ","",rtrim($resulte['rekening_awal'])).";".rtrim($resulte['nm_rekening_tujuan']).";".rtrim($resulte['bic']).";".str_replace(" ","",rtrim($resulte['rekening_tujuan'])).";".$nilai.";".$idr.";".$resulte['ket_tujuan']."\n";             
            
        
        $init_tgl=explode("-",$tglupload);
        $tglupl=$init_tgl[2].$init_tgl[1].$init_tgl[0];       
        $filenamee = $jdul."_".$obskpd."_".$tglupl."_".$tglnoupload;
                
        echo $data;
        header("Cache-Control: no-cache, no-store"); 
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="'.$filenamee.'.csv"');        
        } 
        
    }

	
}