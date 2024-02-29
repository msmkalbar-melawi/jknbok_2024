<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ctransout_cms extends CI_Controller {

	public $org_keu = "";
	public $skpd_keu = "";
	
	function __contruct()
	{	
		parent::__construct();
	}

    function index(){
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI');   
        $this->template->load('template','tukd/cms/transout_cmsbank',$data) ; 
    }	

	function load_list_dtransout_transfercms(){ 
		$kd_skpd = $this->session->userdata('kdskpd');
		$bulan   = $this->input->post('bln1');
		$jnsbeban   = $this->input->post('jnsbeban');
		$kdgiat   = $this->input->post('kdgiat');
		
		if($jnsbeban=='4'){
			$keg = "AND RIGHT(kd_sub_kegiatan,10)='01.1.02.01'";
		}else{
			$keg = "AND LEFT(b.kd_rek6,3)='521' AND b.kd_sub_kegiatan='$kdgiat'";
		}
		
        $sql = "SELECT *, (SELECT SUM(nilai) total FROM trdtransout_transfercms WHERE kd_skpd='$kd_skpd' AND MONTH(tgl_voucher)='$bulan' 
				AND no_voucher IN 
				( SELECT TOP 1 b.no_voucher FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.kd_skpd=b.kd_skpd AND a.no_voucher=b.no_voucher 
				  WHERE b.kd_skpd='$kd_skpd' AND MONTH(a.tgl_voucher)='$bulan' $keg
				  GROUP BY b.no_voucher)) total 
				FROM (
				SELECT * FROM trdtransout_transfercms 
				WHERE kd_skpd='$kd_skpd' AND MONTH(tgl_voucher)='$bulan' 
				AND no_voucher IN 
				( SELECT TOP 1 b.no_voucher FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.kd_skpd=b.kd_skpd AND a.no_voucher=b.no_voucher 
				  WHERE b.kd_skpd='$kd_skpd' AND MONTH(a.tgl_voucher)='$bulan' $keg 
				  GROUP BY b.no_voucher) )p
				ORDER BY nm_rekening_tujuan";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'                => $ii,        
                        'no_voucher'        => $resulte['no_voucher'],
                        'tgl_voucher'       => $resulte['tgl_voucher'],
                        'rekening_awal'     => $resulte['rekening_awal'],
                        'nm_rekening_tujuan'=> $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan'   => $resulte['rekening_tujuan'],
                        'bank_tujuan'       => $resulte['bank_tujuan'],
                        'nilai'             => number_format($resulte['nilai'],2),
                        'total'             => number_format($resulte['total'],2),
                        'kd_skpd'           => $resulte['kd_skpd']                                                                                                                                                                             
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    

    function load_transout(){
        $kd_skpd     = $this->session->userdata('kdskpd');        
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_voucher like '%$kriteria%' or upper(a.ket) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a where a.panjar = '0' AND kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
		$sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,a.status_upload ketup,
		a.status_validasi ketval FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND kd_skpd='$kd_skpd' $where order by CAST (a.no_bukti as NUMERIC))  order by tgl_voucher,CAST (a.no_bukti as NUMERIC),kd_skpd ";

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
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_tgl' => $resulte['no_tgl'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'no_sp2d' => $resulte['no_sp2d'],    
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
                        'ketup' => $resulte['ketup'],                                                                                            
                        'ketval' => $resulte['ketval'], 
                        'stpot' => $resulte['status_trmpot'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                              	                              
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']                                                                                                                   
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }
 	
	function edit_transout(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $skpd     = $skpd = $this->session->userdata('kdskpd');
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
       
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        $sql = "update trhtransout_cmsbank set ket='$ket' where kd_skpd='$skpd' and no_voucher='$nokas'";
		$asg = $this->db->query($sql);
				
		if (!($asg)){
			$msg = array('pesan'=>'0');
			echo json_encode($msg);
		}else {                                                                        
            $msg = array('pesan'=>'1');
            echo json_encode($msg);
        }
		
       
    }

	
    function load_tgltransout(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        
        $result = array();
        $row = array();
      	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	    $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND a.tgl_voucher = '$kriteria'";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a where a.panjar = '0' AND kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
       	$result["total"] = $total->total; 
        $query1->free_result();        
        
		$sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,a.status_upload ketup,
		a.status_validasi ketval FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND kd_skpd='$kd_skpd' $where order by CAST (a.no_bukti as NUMERIC))  order by CAST (a.no_bukti as NUMERIC),kd_skpd ";

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
                        'no_voucher' => $resulte['no_voucher'],
                        'tgl_voucher' => $resulte['tgl_voucher'],
                        'no_tgl' => $resulte['no_tgl'],
                        'ket' => $resulte['ket'],
                        'username' => $resulte['username'],    
                        'no_sp2d' => $resulte['no_sp2d'],    
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
                        'ketup' => $resulte['ketup'],                                                                                            
                        'ketval' => $resulte['ketval'], 
                        'stpot' => $resulte['status_trmpot'],
                        'rekening_awal' => $resulte['rekening_awal'],                                                                                            
                        'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'], 
                        'rekening_tujuan' => $resulte['rekening_tujuan'],                                                                                            
                        'bank_tujuan' => $resulte['bank_tujuan'],
                        'ket_tujuan' => $resulte['ket_tujuan']                                                                                                                   
                        );
                        $ii++;
        }
       	$result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtransout(){ 
		$kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
				FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.no_voucher=b.no_voucher 
				AND a.kd_skpd=b.kd_skpd 
				WHERE a.no_voucher='$nomor' AND a.kd_skpd='$kd_skpd'
				ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_voucher'    => $resulte['no_voucher'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek6'       => $resulte['kd_rek6'],
                        'nm_rek6'       => $resulte['nm_rek6'],
                        'nilai'         => $resulte['nilai'],
                        'nilai_nformat' => number_format($resulte['nilai'],2),
                        'sumber'        => $resulte['sumber'],
                        'lalu'          => $resulte['lalu'],
                        'sp2d'          => $resulte['sp2d'],   
                        'anggaran'      => $resulte['anggaran']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dpot(){        
        $nomor = $this->input->post('no');
        $sql = "select * from trdtrmpot where no_bukti='$nomor' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'kd_rek5'       => $resulte['kd_rek6'],
                        'nm_rek5'       => $resulte['nm_rek6'],
                        'nilai'         => $resulte['nilai']                                                                                                                                                         
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

	
	
	
    function load_dtransout_transfercms(){ 
		$kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,(select sum(nilai) from trdtransout_transfercms where no_voucher=b.no_voucher and kd_skpd=b.kd_skpd) as total
				FROM trhtransout_cmsbank a INNER JOIN trdtransout_transfercms b ON a.no_voucher=b.no_voucher
				AND a.kd_skpd=b.kd_skpd 
				WHERE b.no_voucher='$nomor' AND b.kd_skpd='$skpd'
                group by b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai
				";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'                => $ii,        
                        'no_voucher'        => $resulte['no_voucher'],
                        'tgl_voucher'       => $resulte['tgl_voucher'],
                        'rekening_awal'     => $resulte['rekening_awal'],
                        'nm_rekening_tujuan'=> $resulte['nm_rekening_tujuan'],
                        'rekening_tujuan'   => $resulte['rekening_tujuan'],
                        'bank_tujuan'       => $resulte['bank_tujuan'],
                        'nilai'             => number_format($resulte['nilai'],2),
                        'total'             => number_format($resulte['total'],2),
                        'kd_skpd'           => $resulte['kd_skpd']                                                                                                                                                                             
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
    
	//PINDAH
	function hapus_transout_cms(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

		if ($asg){
            $sql = "delete from trhtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);

			 $sql = "delete from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$kd_skpd'";
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
 
        function simpan_transout(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomor_tgl= $this->input->post('notgl');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $skpd = $this->session->userdata('kdskpd'); //$this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql'); 
        $csqlrek     = $this->input->post('sqlrek');           
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nosp2d   = $this->input->post('nosp2d2');  
        $xrek     = $this->input->post('xrek');     
        
        $rek_awal = trim($this->input->post('rek_awal'));            
        $anrekawal= $this->input->post('anrek_awal'); 
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');     
        $init_ket = $this->input->post('cinit_ket');
        $stt_val  = 0;
        $stt_up   = 0;
       
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

		// Simpan Header //
        if ($tabel == 'trhtransout_cmsbank') {
            $sql = "delete from trhtransout_cmsbank where kd_skpd='$skpd' and no_voucher='$nomor'";
			$asg = $this->db->query($sql);
			
            if ($asg){
				$sql = "insert into trhtransout_cmsbank(no_voucher,tgl_voucher,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,no_tgl,ket_tujuan) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$nomor_tgl','$init_ket')";
                $asg = $this->db->query($sql);
				} else {
					$msg = array('pesan'=>'0');
					echo json_encode($msg);
					exit();
				}
            
        }elseif($tabel == 'trdtransout_cmsbank') {
            // Simpan Detail //                                       
                
                $sql = "delete from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                $sql = "delete from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
				if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "insert into trdtransout_cmsbank(no_voucher,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber)"; 
                    $asg = $this->db->query($sql.$csql);
                    
                    $sql = "insert into trdtransout_transfercms(no_voucher,tgl_voucher,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)"; 
                    $asg = $this->db->query($sql.$csqlrek);                                       
                       
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


    function cetak_listtransaksi(){
        $this->load->library('tanggal_indonesia');
        $kd_skpd = $this->session->userdata('kdskpd');
        $skpd_keu = $this->skpd_keu;
        $thn     = $this->session->userdata('pcThang');
        $tgl     = $this->uri->segment(4);
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
       
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>".$kab."<br>LIST TRANSAKSI</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE ".strtoupper($this->tanggal_indonesia->tanggal_format_indonesia($tgl))."</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"14\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;".strtoupper($this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd'))."</td>
            </tr>
            </table>";
            
            
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr> 
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold;\">SKPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"32%\" style=\"font-size:12px;font-weight:bold;\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"4%\" style=\"font-size:12px;font-weight:bold;\">ST</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;border-top:solid 1px black;\">7</td>
            </tr>
            </thead>";
                      
           $no=0;
           $tot_terima=0;
           $tot_keluar=0;
           $sql = "select z.* from (
            select '1' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,a.no_sp2d kegiatan,'' rekening, a.ket, 0 terima, 0 keluar, a.jns_spp, a.status_upload
            from trhtransout_cmsbank a where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'
            UNION
            select '2' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,b.kd_sub_kegiatan kegiatan,b.kd_rek6 rekening, b.nm_kegiatan+', '+b.nm_rek5, 0 terima, b.nilai keluar, a.jns_spp, '' status_upload
            from trhtransout_cmsbank a 
            left join trdtransout_cmsbank b on b.no_voucher=a.no_voucher and b.kd_skpd=a.kd_skpd
            where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'
            UNION
            select '3' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,'Rek. Tujuan :' kegiatan,'' rekening, RTRIM(a.rekening_tujuan)+' , AN : '+RTRIM(a.nm_rekening_tujuan), 0 terima, a.nilai keluar, '' jns_spp, '' status_upload
            from trdtransout_transfercms a where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'          
            UNION
            select '4' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,b.kd_sub_kegiatan kegiatan,c.kd_rek6 rekening, 'Terima '+c.nm_rek5, c.nilai terima, 0 keluar, '' jns_spp, '' status_upload
            from trhtransout_cmsbank a 
            inner join trhtrmpot_cmsbank b on b.no_voucher=a.no_voucher and b.kd_skpd=a.kd_skpd
            inner join trdtrmpot_cmsbank c on b.no_bukti=c.no_bukti and b.kd_skpd=c.kd_skpd
            where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'
            )z order by z.kd_skpd,z.tgl_voucher,cast (z.no_voucher as int), z.urut";               
           $hasil = $this->db->query($sql);    
           foreach ($hasil->result() as $row)
                    {
                        $no=$no++;     
                        
            if($row->urut=='1'){                            
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:none;\">".$row->no_voucher."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kd_skpd."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kegiatan.".".$row->rekening."</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->ket."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->status_upload."</td>                                       
                 </tr>";
                 }else if($row->urut=='3'){                            
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->kegiatan."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".$row->ket."&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">".number_format($row->keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\"></td>                                       
                 </tr>";
                 }else{
            $cRet .="<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".$row->kegiatan.".".$row->rekening."</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".$row->ket."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".number_format($row->terima,2)."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">".number_format($row->keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>                                        
                 </tr>";
                 }
                 
                 if($row->urut!='3'){
                    $tot_terima=$tot_terima+$row->terima; 
                    $tot_keluar=$tot_keluar+$row->keluar;  
                 }                 
                                  
             }
            

            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='1' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
            select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai [jumlah],'1' [jns],kd_skpd [kode] from trhtrmpot 
            where kd_skpd='$kd_skpd' and pay='BANK' union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE jns='1' and kd_skpd='$kd_skpd' AND pay='BANK' union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
            join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) 
            c on b.no_spm=c.no_spm WHERE pay='BANK' union all 
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$kd_skpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
            union all           
             select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai [jumlah],'2' [jns],kd_skpd [kode] from trhstrpot  
             where kd_skpd='$kd_skpd' and pay='BANK'
                    
            ) a
            where tgl<='$tgl' and kode='$kd_skpd'";  
    
        $hasil=$this->db->query($asql);
        $bank=$hasil->row();
        $keluarbank=$bank->keluar;
        $terimabank=$bank->terima;
        $saldobank=$terimabank-$keluarbank;     
        
        $saldoakhirbank = (($saldobank+$tot_terima)-$tot_keluar);
            
            $cRet .="
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border-top:1px solid black;\">JUMLAH</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">".number_format($tot_terima,2)."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">".number_format($tot_keluar,2)."</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"9\" style=\"font-size:11px;border:none;\"><br/></td>                                                   
                 </tr> 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Saldo Sampai Dengan Tanggal ".$this->tanggal_indonesia->tanggal_format_indonesia($tgl).", </td>                                                   
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($saldobank,2)."</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Terima</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($tot_terima,2)."</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Keluar</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($tot_keluar,2)."</td>                                                   
                 </tr>                                 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\"><hr/></td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Perkiraan Akhir Saldo, </td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. ".number_format($saldoakhirbank,2)."</td>                                                   
                 </tr>                                 
                                                  
            </table>";    
            
        $data['prev']= $cRet;    
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         
                
    }
 
	
}