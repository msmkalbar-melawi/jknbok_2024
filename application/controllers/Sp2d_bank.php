<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Sp2d_bank extends CI_Controller {

	function __contruct()
	{	 
		parent::__construct();
    
	}  
  
    function get_token(){
		$a = $this->get_token_api();
		return $a;
	}
	
	function get_token_api(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://api.simakdasp2d.msmsystemlink.com/sppd/api/sppd/hh/auth",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>"{\n    \"key\" : \"1541652efe1a666840ecd1d648593d67\"\n}",
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json"
		  ),
		));

		$response = curl_exec($curl);
	
		curl_close($curl);
		$array = json_decode($response);
		$j=$array->data[0]->token;
		return $j;
	}
	function get_iv(){
		$a = $this->get_iv_api();
		$x=$a->data[0]->token;
		$y=$a->data[0]->passPhrase;
		$z=$a->data[0]->idKey;
		echo $x;
	}
	function get_iv_api(){
		$api_key = $this->get_token_api();
		$headers = array(
            'Authorization: Bearer '.$api_key
        );
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://api.simakdasp2d.msmsystemlink.com/sppd/api/sppd/hh/encrypt/key",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_HTTPHEADER => $headers,
		));

		$response = curl_exec($curl);
		curl_close($curl);
		
		$res = json_decode($response);
		$newData = (array) $res;
		$new0 = (array) $newData['data'][0];
		$new0['token'] = $api_key;
		$newData['data'][0] = (object) $new0;
		return (object)$newData;

	}
	function proses_kebank() {
        
      	$nouji    = $this->input->post('no_uji'); 
		
		$sqltot = "SELECT count(*) as tot from TRDUJI where no_uji='$nouji'" ;
        $query1 = $this->db->query($sqltot);
        $total = $query1->row();
		$tot= $total->tot;
		
		$sql = "SELECT no_sp2d from TRDUJI where no_uji='$nouji'";
		$dt_sp2d = $this->db->query($sql)->result_array();
		$sukses=$tot;//0;
		$gagal=0;
         foreach($dt_sp2d as $kk => $vv){
			$no=$vv['no_sp2d'];
			$kirim = $this->sp2d_kebank($no);
			/* if($kirim=='true'){
				$sukses=$sukses+1;
			}else{
				$gagal=$gagal+1;
			} */
		} 
		$msg = array('pesan'=>'1','total'=>$tot,'sukses'=>$sukses,'gagal'=>$gagal);
                        echo json_encode($msg);
	}
	function sp2d_kebank($no) {
	//function sp2d_kebank() { //untuk coba kirim
        $result = array();
        $row = array();
		
          $sql = "SELECT  a.no_spp,a.no_sp2d,a.no_spm,a.tgl_sp2d as tgl,a.tgl_spm,a.kd_skpd,bank,a.no_rek,npwp,a.nilai,ISNULL(b.potongan, 0) as potongan,
				(a.nilai-ISNULL(b.potongan, 0)) as netto ,a.keperluan,'PT. Bank Kalbar Cabang Utama Pontianak' as nm_bank_sumber,
				'100.100.283.0' as no_rek_sumber FROM trhsp2d a 
				left join(select no_spm,kd_skpd,sum(nilai) as potongan from trspmpot GROUP BY no_spm,kd_skpd) b 
				on a.no_spm=b.no_spm and a.kd_skpd=b.kd_skpd where a.no_sp2d='$no'"; 
		 /*$sql = "SELECT  a.no_spp,a.no_sp2d,a.no_spm,a.tgl_sp2d as tgl,a.tgl_spm,a.kd_skpd,bank,a.no_rek,npwp,a.nilai,ISNULL(b.potongan, 0) as potongan,
				(a.nilai-ISNULL(b.potongan, 0)) as netto ,a.keperluan,'PT. Bank Kalbar Cabang Utama Pontianak' as nm_bank_sumber,
				'100.100.283.0' as no_rek_sumber FROM trhsp2d a 
				left join(select no_spm,kd_skpd,sum(nilai) as potongan from trspmpot GROUP BY no_spm,kd_skpd) b 
				on a.no_spm=b.no_spm and a.kd_skpd=b.kd_skpd where a.no_sp2d='100/SP2D/UP/2.09.01/2020'";*/
		
     
		$dt_sp2d = $this->db->query($sql)->result_array();
        foreach($dt_sp2d as $kk => $vv){
			$sql_opd = "select kd_skpd,nm_skpd from ms_skpd where kd_skpd='".$vv['kd_skpd']."'";
			$sql_bank = "select kode as kdbank,bic,nama as nmbank from ms_bank where kode='".$vv['bank']."'";
        
            $data['no'] = $vv['no_sp2d'];
			$data['tgl'] = $vv['tgl_spm'];
				$dt_opd = $this->db->query($sql_opd)->result_array();
				foreach($dt_opd as $kkk => $vvv){                                                               
        						$data['opd']['code'] = $vvv['kd_skpd'];
                                $data['opd']['name'] = $vvv['nm_skpd'];
                }
            $data['dari'] ="Kuasa Bendahara Umum Daerah (Kuasa BUD)";
            $data['ta'] ="2020";
            $data['bank'] ="PT. Bank Kalbar Cabang Utama Pontianak";
            $data['akunAsal'] ="1001002201";
            $data['jumlahBayar'] =$vv['nilai'];
            $data['terbilang'] =$this->tukd_model->terbilang($vv['nilai']);
				$dt_bank = $this->db->query($sql_bank)->result_array();
				foreach($dt_bank as $kkkk => $vvvv){
                                $data['penerima']['kodeBank'] = $vvvv['bic'];
								$data['penerima']['namaBank'] = $vvvv['nmbank'];
								$data['penerima']['namaPenerima'] = 'GINAWAN';
								$data['penerima']['noAkun'] = '1025275884';//$vv['no_rek'];
                                $data['penerima']['npwp'] = '80.966.988.0-701.000';//$vv['npwp'];
				}
			$data['keperluan'] = $vv['keperluan'];
			$data['jumlahDiminta'] = $vv['nilai'];
            $data['jumlahPotongan'] = $vv['potongan'];	
            $data['jumlahDibayar'] = $vv['netto'];
            $data['jumlahTerbilang'] = $this->tukd_model->terbilang($vv['netto']);
            $sql_detail = "select isnull(kd_kegiatan,'-') as kd_kegiatan,kd_rek5,nm_rek5,nilai from trdspp  where no_spp='".$vv['no_spp']."'"; 
            $dt_detail = $this->db->query($sql_detail)->result_array();	
            foreach($dt_detail as $kkkkk => $vvvvv){
                $data['line'][$kkkkk]['rekening'] = $vvvvv['kd_kegiatan'].'.'.$this->tukd_model->dotrek($vvvvv['kd_rek5']);
                $data['line'][$kkkkk]['uraian'] = $vvvvv['nm_rek5'];
                $data['line'][$kkkkk]['jumlah'] = $vvvvv['nilai'];	
            }		
            $sql_jml_potongan ="select COUNT(*) as jumlah from (select kd_rek5,nm_rek5,nilai from trspmpot where no_spm='".$vv['no_spm']."' AND kd_rek5 IN('4140611','2110501','2110701','2110801','2110901','4140612')) a";
            $hasil1 = $this->db->query($sql_jml_potongan)->row();
            $jumlahbarispot = $hasil1->jumlah; 
            if($jumlahbarispot>=1){
                $sql_potongan = "select kd_rek5,nm_rek5,nilai from trspmpot where no_spm='".$vv['no_spm']."' AND kd_rek5 IN('4140611','2110501','2110701','2110801','2110901','4140612')";	
                $dt_potongan = $this->db->query($sql_potongan)->result_array();	
                foreach($dt_potongan as $kkkkkk => $vvvvvv){
                    $data['potongan'][$kkkkkk]['rekening'] =  $vvvvvv['kd_rek5']; 
                    $data['potongan'][$kkkkkk]['uraian'] = $vvvvvv['nm_rek5'];
                    $data['potongan'][$kkkkkk]['jumlah'] = $vvvvvv['nilai'];	
                    $data['potongan'][$kkkkkk]['keterangan'] = '';  
                }
                
            }else{
                $kkkkkk=0;
                $data['potongan'][$kkkkkk]['rekening'] =  ''; 
                $data['potongan'][$kkkkkk]['uraian'] = ''; 
                $data['potongan'][$kkkkkk]['jumlah'] = ''; 	
                $data['potongan'][$kkkkkk]['keterangan'] = ''; 
            }
            
            $sql_jml_informasi ="select COUNT(*) as jumlah from (select 1 urut, * from trspmpot where no_spm='".$vv['no_spm']."' AND kd_rek5 IN('2130301')
                                UNION ALL
                                select 2 urut, * from trspmpot where no_spm='".$vv['no_spm']."' AND kd_rek5 NOT IN('4140611','2130301','2110501','2110701','2110801','2110901','4140612')
                                ) a";
            $hasil2 = $this->db->query($sql_jml_informasi)->row();
            $jumlahbarisinf = $hasil2->jumlah; 
            if($jumlahbarisinf>=1){
                $sql_informasi = "select 1 urut, * from trspmpot where no_spm='".$vv['no_spm']."' AND kd_rek5 IN('2130301')
                UNION ALL
                select 2 urut, * from trspmpot where no_spm='".$vv['no_spm']."' AND kd_rek5 NOT IN('4140611','2130301','2110501','2110701','2110801','2110901','4140612')
                ORDER BY urut,kd_rek5";	
                $dt_informasi = $this->db->query($sql_informasi)->result_array();	
                foreach($dt_informasi as $kkkkkkk => $vvvvvvv){
                    $data['Informasi'][$kkkkkkk]['rekening'] =  $vvvvvvv['kd_rek5']; 
                    $data['Informasi'][$kkkkkkk]['uraian'] = $vvvvvvv['nm_rek5'];
                    $data['Informasi'][$kkkkkkk]['jumlah'] = $vvvvvvv['nilai'];	
                    $data['Informasi'][$kkkkkkk]['keterangan'] = '';  
                }
            }else{
                $kkkkkkk=0;
                $data['Informasi'][$kkkkkkk]['rekening'] =  ''; 
                $data['Informasi'][$kkkkkkk]['uraian'] = ''; 
                $data['Informasi'][$kkkkkkk]['jumlah'] = ''; 	
                $data['Informasi'][$kkkkkkk]['keterangan'] = '';
            }

		}
		
		$msg=json_encode($data);
		$a = $this->get_iv_api();
		$iv=$a->data[0]->ivKey;
		$key=$a->data[0]->passPhrase;
		$idKey=$a->data[0]->idKey;
		$token=$a->data[0]->token;
		
		$encrypt=$this->encrypt_mcrypt($msg,$key,$iv);
		$data1['data'] = $encrypt;
		$data1['idKey'] = $idKey;
		$datakirim = json_encode($data1);
		$this->kirim_sppd($datakirim,$token);
		   
	} 
	
	function encrypt_mcrypt($msg, $key, $iv = null) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		if (!$iv) {
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		}
		$pad = $iv_size - (strlen($msg) % $iv_size);
		$msg .= str_repeat(chr($pad), $pad);
		$encryptedMessage = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $msg, MCRYPT_MODE_CBC, $iv);
		return base64_encode($encryptedMessage);
	}
	function kirim_sppd($data,$token){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://api.simakdasp2d.msmsystemlink.com/sppd/api/sppd/sppd/save",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $data,
		  CURLOPT_HTTPHEADER => array(
			"Authorization: Bearer ".$token,
			"Content-Type: application/json"
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response; exit();
		
		$array = json_encode($response);
		echo $array ; exit();
		$b = explode('":',$array);
		$c = explode(',\"',$b[1]);
		return $c;
	}
	
	
	function create_penerima($kdbank,$nmbank,$nmpenerima,$noakun,$npwp){
		$data['kodeBank'] = $kdbank;
		$data['namaBank'] = $nmbank;
		$data['namaPenerima'] = $nmpenerima;
		$data['noAkun'] = $noakun;
		$data['npwp'] = $npwp;
		$datakirim = json_encode($data);
		
		$api_key = $this->get_token_api();
		$headers = array(
            'Authorization: Bearer '.$api_key,
			"Content-Type: application/json"
        );
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://api.simakdasp2d.msmsystemlink.com/sppd/api/master/penerima/save",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>$datakirim,
		  CURLOPT_HTTPHEADER => $headers,
		));

		$response = curl_exec($curl);

		curl_close($curl);
		
		$array = json_encode($response);
		$b = explode('":',$array);
		$c = explode(',\"',$b[1]);
		return $c;
	}
	
	function create_opd(){
		$kdopd='2.11.01.00';
		$nmopd='DINAS KOPERASI, USAHA MIKRO DAN PERDAGANGAN';
		$data['code'] = $kdopd;
		$data['name'] = $nmopd;
		
		$datakirim = json_encode($data);
		
		$api_key = $this->get_token_api();
		$headers = array(
            'Authorization: Bearer '.$api_key,
			"Content-Type: application/json"
        );
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://api.simakdasp2d.msmsystemlink.com/sppd/api/master/opd/save",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>$datakirim,
		  CURLOPT_HTTPHEADER => $headers,
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
	
	function mpenerima()
    {
        $data['page_title']= 'Master Rekening Penerima';
        $this->template->set('title', 'Master Penerima');   
        $this->template->load('template','master/rek_penerima/mpenerima',$data) ;
    }
	function load_penerima() {
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(namaPenerima) like upper('%$kriteria%') or noAkun like'%$kriteria%')";            
        }
        
        $sql = "SELECT * from ms_penerima $where order by kodeBank,noAkun";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kodeBank' => $resulte['kodeBank'],
						'namaBank' => $resulte['namaBank'],
                        'namaPenerima' => $resulte['namaPenerima'],
						'noAkun' => $resulte['noAkun'],
						'npwp' => $resulte['npwp'],						
                        );
                        $ii++;
        }
           
        echo json_encode($result);
    	   
	}
	
	function simpan_penerima(){
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
		
		$kdbank = $this->input->post('ckode');
		$nmbank = $this->input->post('cnama');
		$noakun = $this->input->post('cnorek');
		$nmpenerima = $this->input->post('cnmpenerima');
		$npwp = $this->input->post('cnpwp');
		
		$kirim = $this->create_penerima($kdbank,$nmbank,$nmpenerima,$noakun,$npwp);
		
        if($kirim[0]=='false'){
			echo '3'; 
		}else{
			$sql = "select $cid from $tabel where $cid='$noakun'";
			$res = $this->db->query($sql);
			if($res->num_rows()>0){
				echo '1';
			}else{
				$sql = "insert into $tabel $lckolom values $lcnilai";
				$asg = $this->db->query($sql);
				if($asg){
					echo '2';
				}else{
					echo '0';
				}
			}
		}
        
        
    }
	
}
