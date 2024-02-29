<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class csamsat extends CI_Controller {

	function __contruct(){	
		parent::__construct();
	}
    
	function transfer_samsat(){
        $data['page_title']= 'Pengambilan Data SAMSAT';
        $this->template->set('title', 'Pengambilan Data SAMSAT');   
        $this->template->load('template','samsat/transfer_samsat',$data) ; 
    }

    function load_samsat($tgl,$skpd,$tgl1,$tgl2){
        $tahun=$this->session->userdata('pcThang');
        $skpd_samsat = $this->tukd_model->get_nama3($skpd,'kd_samsat','map_samsat','kd_skpd');
        $array = array();
        foreach ($skpd_samsat as $skpd_samsat2) {
          $url = "http://36.66.239.162:8181/simakda/smdp3.php?username=simakda&password=5a24e942bcffd&tgl=".$tgl."&kddati2=".$skpd_samsat2['kd_samsat'];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $data2 = json_decode(trim($data), TRUE);
        if(is_array($data2)){
         $array = array_merge($array, $data2['data']);     
        } 
        }
        
        //$url = "http://36.66.239.162:8181/simakda/smdp.php?username=simakda&password=5a24e942bcffd&tgl=".$tgl."&kdupt=".$skpd;
        //$url = "http://36.66.239.162:8181/simakda/smdp2.php?username=simakda&password=5a24e942bcffd&tgl=11012018&kddati2=01";
        
        
        $content =  $array;
        
        
        $row_num = count($content);
        $dsql='';
        $ii = 0;
         if($row_num>0){
            foreach($content as $resulte){
                
                  if($resulte['kode']==4110601){
                   $resulte['kode']=4110201;
                } else if ($resulte['kode']==4110602){
                   $resulte['kode']=4110202;
                } else if ($resulte['kode']==4110603){
                   $resulte['kode']=4110203;
                } else if ($resulte['kode']==4110604){
                   $resulte['kode']=4110204;
                } else if ($resulte['kode']==4110605){
                   $resulte['kode']=4110205;
                } else if ($resulte['kode']==4110606){
                   $resulte['kode']=4110206;
                } else if ($resulte['kode']==4110607){
                   $resulte['kode']=4110207;
                } else if ($resulte['kode']==4110608){
                   $resulte['kode']=4110208;
                } else if ($resulte['kode']==4110609){
                   $resulte['kode']=4110209;
                } else if ($resulte['kode']==4110610){
                   $resulte['kode']=4110210;
                } else if ($resulte['kode']==4110611){
                   $resulte['kode']=4110211;
                } else if ($resulte['kode']==4110612){
                   $resulte['kode']=4110212;
                } else if ($resulte['kode']==4110613){
                   $resulte['kode']=4110213;
                } else {
                   $resulte['kode'];
                }  
                
                if($ii==0){
                    $dsql = $dsql."('".$tgl1."','".$resulte['no_rek']."','".$resulte['kode']."',".$resulte['jml_pener'].",'".$resulte['kd_uptbyr']."','".$skpd."',null)";    
                }else{
                    $dsql = $dsql.",('".$tgl1."','".$resulte['no_rek']."','".$resulte['kode']."',".$resulte['jml_pener'].",'".$resulte['kd_uptbyr']."','".$skpd."',null)";  
                }
            $ii++;    
            }
            
            $hsl =  $this->samsat_model->savesamsat('tsamsat',$dsql,$tgl1,$skpd);
            $sql1 = "update tsamsat set no_rek=REPLACE(no_rek,'41106','41102') where kd_upt='$skpd'";
            $asg1 = $this->db->query($sql1); 
            
            if($hsl){
                $query1 = $this->samsat_model->tampil_samsat($tgl1,$skpd,$tgl2);
                $result = array();
                $ii = 0;
                foreach($query1->result_array() as $resulte)
                {                               
                    $result[] = array(
                                'id' => $ii,        
                                'no_tetap' => $resulte['no_tetap'],
                                'no_terima' => $resulte['no_terima'],
                                'no_sts' => $resulte['no_sts'],
                                'tgl_samsat' => $resulte['tgl_samsat'],
                                'kd_skpd' => $resulte['kd_skpd'],
                                'no_rek' => $resulte['no_rek'],
                                'nm_rek5' => $resulte['nm_rek5'],
                                'jenis' => $resulte['jenis'],
                                'kd_pengirim' => $resulte['kd_uptbyr'],
                                'nm_pengirim' => $resulte['nm_pengirim'],
                                'nilai' => number_format($resulte['nilai'],2,'.',','),
                                'kd_rek_lo' => $resulte['kd_rek_lo'],
                                'keterangan' => $resulte['keterangan'],
                                'kd_kegiatan' => $resulte['kd_kegiatan'],
                                );
                                $ii++;
                }
                   
            }   
            echo json_encode($result);
            $query1->free_result();
        }else{
                    $ii = 0;
                    $result[] = array(
                                'id' => $ii,        
                                'no_tetap' => '',
                                'no_terima' => '',
                                'no_sts' => '',
                                'tgl_samsat' => '',
                                'kd_skpd' => '',
                                'no_rek' => '',
                                'jenis' => '',
                                'kd_pengirim' => '',
                                'nilai' => '',
                                'kd_rek_lo' => '',
                                'keterangan' => '',
                                'kd_kegiatan' => ''
                                );
                                $ii++;

            echo json_encode($result);
            
            
        }
    }
    
	function dsimpan_samsat(){
    
        $sqltetap    = $this->input->post('sqltetap');        
        $sqlterima    = $this->input->post('sqlterima');
        $sqlkasin      = $this->input->post('sqlkasin');
        $sqlkasin2     = $this->input->post('sqlkasin2');            
		$kd_skpd  = $this->session->userdata('kdskpd');
        $tgl  = $this->input->post('dtgl');
		$tgl2  = $this->input->post('dtgl2');
        //$update     = date('y-m-d H:i:s');      
        $data        = array();

		// Simpan Header //
            
            // Simpan Detail //                       
                /*
                $sql = "delete from tr_tetap where tgl_tetap='$tgl' AND kd_skpd='$kd_skpd'
                        delete from tr_terima where tgl_terima='$tgl' AND kd_skpd='$kd_skpd'
                        delete a from trdkasin_pkd a join trhkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts where b.jns_trans='4' 
                        and b.tgl_sts='$tgl' AND a.kd_skpd='$kd_skpd'
                        delete from trhkasin_pkd where tgl_sts='$tgl' AND kd_skpd='$kd_skpd' and jns_trans='4'
                        ";
                $asg = $this->db->query($sql);
                */
					/* LM
					$sql3 = "delete a from trdkasin_pkd a join tr_terima b on a.kd_skpd=b.kd_skpd and a.no_terima=b.no_terima where  
                             b.tgl_terima='$tgl' AND a.kd_skpd='$kd_skpd' and b.user_name='samsat'"; 
                    $asg3 = $this->db->query($sql3);

					$sql4 = "delete a from trhkasin_pkd a join tr_terima b on a.kd_skpd=b.kd_skpd and a.no_terima=b.no_terima where 
                             b.tgl_terima='$tgl' AND a.kd_skpd='$kd_skpd' and b.user_name='samsat'"; 
                    $asg4 = $this->db->query($sql4);
                    LM
					*/
  					//'
					
					/*
                    $sql3 = "delete a from trhkasin_pkd a 
                            join trdkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
                            join tr_terima c on a.kd_skpd=c.kd_skpd and b.no_terima=c.no_terima where 
                            c.tgl_terima='$tgl' AND a.kd_skpd='$kd_skpd' and c.user_name='samsat'"; 
                    $asg3 = $this->db->query($sql3);

                    $sql4 = "delete a from trdkasin_pkd a join tr_terima b on a.kd_skpd=b.kd_skpd and a.no_terima=b.no_terima where  
                             b.tgl_terima='$tgl' AND a.kd_skpd='$kd_skpd' and b.user_name='samsat'"; 
                    $asg4 = $this->db->query($sql4);
					*/
					
                    $sql1 = "delete from tr_tetap where tgl_tetap='$tgl' AND kd_skpd='$kd_skpd' and user_name='samsat'"; 
                    $asg1 = $this->db->query($sql1);				         
                    $sql1 = "insert into tr_tetap "; 
                    $asg1 = $this->db->query($sql1.$sqltetap);

                    $sql2 = "delete from tr_terima where tgl_terima='$tgl' AND kd_skpd='$kd_skpd' and user_name='samsat'"; 
                    $asg2 = $this->db->query($sql2);
                    $sql2 = "insert into tr_terima "; 
                    $asg2 = $this->db->query($sql2.$sqlterima);
                    
                    
                    /*
                   
                    $sql3 = "insert into trhkasin_pkd (no_sts,kd_skpd,tgl_sts,keterangan,total,kd_kegiatan,jns_trans,pot_khusus,sumber,no_terima,user_name) "; 
                    $asg3 = $this->db->query($sql3.$sqlkasin);
                    
                    
                    $sql4 = "insert into trdkasin_pkd  "; 
                    $asg4 = $this->db->query($sql4.$sqlkasin2);*/

/*                  
                    $sql2 = "insert into trhkasin  "; 
                    $asg2 = $this->db->query($sql2.$sqlkasin);

                    $sql3 = "insert into trdkasin  "; 
                    $asg3 = $this->db->query($sql3.$sqlkasin2);
*/                   
					if (!($asg1)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                     //   exit();
                    }  else {
                       $msg = array('pesan'=>'1');
                        echo json_encode($msg);
                    }
                
        // $this->samsat_model->tampil_samsat($tgl,$kd_skpd);      		
    }
	
	
    function hapus_setor_samsat(){    
		$tgl    = $this->input->post('dtgl'); 
		$kd_skpd  = $this->session->userdata('kdskpd');
        $sql3 = "delete a from trdkasin_pkd a join trhkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts where b.jns_trans='4' 
                and b.tgl_sts='$tgl' AND a.kd_skpd='$kd_skpd'"; 
        $asg3 = $this->db->query($sql3);
                    
                    
        $sql4 = "delete trhkasin_pkd where tgl_sts='$tgl' AND kd_skpd='$kd_skpd' and jns_trans='4' "; 
        $asg4 = $this->db->query($sql4);
		
		if (!($asg3 && $asg4)){
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
        //   exit();
        }  else {
            $msg = array('pesan'=>'1');
            echo json_encode($msg);
        }		
		
	}


}