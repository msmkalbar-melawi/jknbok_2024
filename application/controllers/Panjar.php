<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Panjar extends CI_Controller {

public $ppkd = "4.02.01";
public $ppkd1 = "4.02.01.00";


public $org_keu = "";
public $skpd_keu = "";
 
	function __construct()	{	  
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
            redirect('welcome');
        } 
	}



function transaksi_panjar()
    {
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI PANJAR';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI PANJAR');   
        $this->template->load('template','tukd/transaksi/transaksi_panjar',$data) ; 
    }

function load_transout_panjar(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        $spjbulan = $this->tukd_model->cek_status_spj($kd_skpd);
        
        if ($kriteria <> ''){                               
            $where=" AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '1' AND a.kd_skpd='$kd_skpd' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,
                (SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
                (CASE WHEN month(a.tgl_bukti)<='$spjbulan' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
                WHERE  a.panjar = '1' AND a.kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
                WHERE  a.panjar = '1' AND a.kd_skpd='$kd_skpd' $where order by a.no_bukti)  order by a.no_bukti,kd_skpd";
        $query1 = $this->db->query($sql); 
        $ii = 0;

        
        foreach($query1->result_array() as $resulte){ 
            if ($resulte['ketspj']=='1'){
                    $s1='&#10004';
                }else{
                    $s1='&#10008';          
                }
            
        if($resulte['ketlpj']=='1' || $resulte['ketlpj']=='2'){
            $ketlpj = '1';
        }else{
            $ketlpj = '0';
        }
            
        if ($ketlpj=='1'){
                $s2='&#10004';
            }else{
                $s2='&#10008';          
            }

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
                        'no_panjar' => $resulte['no_panjar'],                                                                                                           
                        'ketlpj' => $ketlpj,                                                                                            
                        'ketspj' => $resulte['ketspj'],                                                                                           
                        'simbolspj' => $s1,                                                                                            
                        'simbollpj' => $s2                      
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
    }

 function pot() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $spm=$this->input->post('spm');
        $sql = "SELECT * FROM trspmpot where no_spm='$spm' AND kd_skpd='$kd_skpd' order by kd_rek6 ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'kd_trans' => $resulte['kd_trans'],  
                        'nm_rek6' => $resulte['nm_rek6'],  
                        'pot' => $resulte['pot'],
                        'nilai' => $resulte['nilai']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
         //$query1->free_result();   
    }


function simpan_transout_panjar(){

        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas      = $this->input->post('tglkas');
        $nokaspot    = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $nopanjar = $this->input->post('nopanjar');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql'); 
        $csqlpanjar     = $this->input->post('sqlpanjar');      
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "DELETE from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);
            
            if ($asg){
                
                $sql = "INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','1','$nopanjar','')";
                $asg = $this->db->query($sql);

            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }elseif($tabel == 'trdtransout') {
            
            // Simpan Detail //                       
                $sql = "DELETE from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql); 

                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT into trdtransout(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber)"; 
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

function load_nopanjar_trans() {        
        $kd_skpd     = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');        
        $sql = "SELECT a.no_panjar_lalu, ISNULL(nilai,0) nilai, ISNULL(kembali,0) as kembali
                 FROM (
                SELECT no_panjar_lalu, SUM(nilai) as nilai 
                FROM tr_panjar WHERE no_panjar_lalu IN 
                (select no_panjar 
                From tr_panjar WHERE kd_skpd='$kd_skpd'
                AND jns='1' AND status='1')
                AND kd_skpd='$kd_skpd'
                GROUP BY no_panjar_lalu) a
                LEFT JOIN(
                SELECT no_panjar, SUM(nilai) as kembali
                 FROM tr_jpanjar WHERE kd_skpd = '$kd_skpd'
                AND jns='2'
                GROUP BY no_panjar) b 
                ON a.no_panjar_lalu=b.no_panjar
                WHERE (UPPER(no_panjar_lalu) LIKE UPPER('%$lccr%'))
                ORDER BY no_panjar_lalu
                ";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,
                        'no_panjar_lalu' => $resulte['no_panjar_lalu'],
                        'nilai' => $resulte['nilai'],
                        'kembali' => $resulte['kembali']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }

    function load_giat_panjar(){
        $kode     = $this->session->userdata('kdskpd');
        $data1 = $this->cek_anggaran_model->cek_anggaran($kode);
        $nomor = $this->input->post('nomor');
        //$id=str_replace('123456789','/',$spp);
        $query1 = $this->db->query("SELECT a.kd_sub_kegiatan,(SELECT nm_sub_kegiatan FROM trskpd WHERE kd_sub_kegiatan=a.kd_sub_kegiatan AND kd_skpd='$kode' AND jns_ang='$data1') as nm_sub_kegiatan  FROM tr_panjar a WHERE  a.no_panjar='$nomor' AND a.kd_skpd='$kode'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],                       
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']                      
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
           $query1->free_result();  
    }


    function load_sp2d_panjar() {                      
        $kode     = $this->session->userdata('kdskpd');
        $giat   = $this->input->post('giat');  
        $jns   = $this->input->post('jns');  
        $lccr   = $this->input->post('q');        
        
        if($jns == '1'){
        
        $sql = "SELECT
                    a.no_sp2d AS no_sp2d,
                    a.tgl_sp2d AS tgl_sp2d
                FROM
                    trhsp2d a
                INNER JOIN trhspp b ON a.no_spp = b.no_spp AND a.kd_skpd = b.kd_skpd
                INNER JOIN trdspp c ON c.no_spp = b.no_spp AND c.kd_skpd = b.kd_skpd
                WHERE
                    left(a.kd_skpd,17) = left('$kode',17) AND a.jns_spp IN ('1', '2')
                GROUP BY 
                a.no_sp2d,
                a.tgl_sp2d
                ";
            } else {
        $sql = "SELECT a.no_sp2d as no_sp2d, a.tgl_sp2d as tgl_sp2d FROM trhsp2d a INNER JOIN trhspp b on a.no_spp = b.no_spp 
                AND a.kd_skpd=b.kd_skpd 
                INNER JOIN (SELECT no_spp,kd_skpd, kd_sub_kegiatan FROM trdspp WHERE kd_skpd = '$kode'
                AND kd_sub_kegiatan = '$giat' GROUP BY no_spp,kd_skpd,kd_sub_kegiatan) c
                ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
                where c.kd_sub_kegiatan = '$giat' AND a.kd_skpd = '$kode' and a.jns_spp = '$jns' "; 
            }
            // AND c.kd_sub_kegiatan IN (SELECT kd_kegiatan FROM tr_panjar WHERE kd_skpd='$kode')
            // b.kd_kegiatan = '$giat' AND     
            // echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'nosp2d' => $resulte['no_sp2d'],  
                        'tglsp2d' => $resulte['tgl_sp2d']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }

    function simpan_transout_panjar_edit(){

        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $no_bku    = $this->input->post('no_bku');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas     = $this->input->post('tglkas');
        $nokaspot   = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');      
        $csql     = $this->input->post('sql');  
        $nopanjar = $this->input->post('nopanjar');
        $csqlpanjar = $this->input->post('sqlpanjar');            
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "DELETE from trhtransout where kd_skpd='$skpd' and no_bukti='$no_bku'";
            $asg = $this->db->query($sql);
            if ($asg){
                $sql = "INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_panjar) 
                        values('$nomor','$tgl','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','1','$nopanjar')";
                $asg = $this->db->query($sql);

                    if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan'=>'1');
                    echo json_encode($msg);
                }             
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
        }
        else if($tabel == 'trdtransout') {
            // Simpan Detail //                       
                $sql = "DELETE from trdtransout where no_bukti='$no_bku' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);
                
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT into trdtransout(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber)"; 
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


    function hapus_transout_panjar(){
         $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "DELETE from trdtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);
        if ($asg){
            $sql = "DELETE from trhtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
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

    function load_rek_panjar() {                      
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $data   = $this->cek_anggaran_model->cek_anggaran($kode);
        $nomor  = $this->input->post('no');
        $panjar  = $this->input->post('panjar');
        $jenis  = $this->input->post('jns');
        $nosp2d  = $this->input->post('nosp2d');
        $lccr   = $this->input->post('q');
        $field='nilai_ubah';
       if($jenis == '1'){
            $sql = "SELECT a.kd_rek6,a.nm_rek6,
                        (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd 
                        WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=a.kd_skpd  AND c.no_bukti <> '$nomor' AND d.panjar = '1' 
                        AND d.no_panjar = '$panjar') AS panjar_lalu,                
                        (SELECT SUM(nilai) FROM 
                        (SELECT SUM (c.nilai) as nilai
                        FROM trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND d.kd_skpd = a.kd_skpd
                        AND c.kd_rek6 = a.kd_rek6
                        AND c.no_bukti <> '$nomor'
                        AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND x.kd_skpd = a.kd_skpd
                        AND x.kd_rek6 = a.kd_rek6
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) as lalu,0 as sp2d,
                        nilai as ang_murni
                        FROM trdrka a WHERE a.jns_ang='$data' AND a.kd_sub_kegiatan= '$giat' AND a.kd_skpd = '$kode' ";
       } else {
           $sql = "SELECT b.kd_rek6,b.nm_rek6,
                (SELECT ISNULL(SUM(c.nilai),0) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd 
                WHERE c.kd_sub_kegiatan = '$giat' AND d.kd_skpd='$kode'  AND c.no_bukti <> '$nomor' AND d.panjar = '1' 
                AND d.no_panjar = '$panjar') AS panjar_lalu,
                    (SELECT ISNULL(SUM(c.nilai),0) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd 
                    WHERE c.kd_sub_kegiatan = '$giat' AND 
                    d.kd_skpd='$kode'
                    AND c.kd_rek6=b.kd_rek6 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$nosp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS ang_murni
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$nosp2d' and b.kd_sub_kegiatan='$giat'
                ";
       }
        
                    
                
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6'],
                        'panjar_lalu' => $resulte['panjar_lalu'],
                        'lalu' => $resulte['lalu'],
                        'sp2d' => $resulte['sp2d'],
                        'ang_murni' => $resulte['ang_murni']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }
 function rek_pot() {
        $lccr   = $this->input->post('q') ;
        $sql    = " SELECT kd_rek6,nm_rek6 FROM ms_pot where ( upper(kd_rek6) like upper('%$lccr%')
                    OR upper(nm_rek6) like upper('%$lccr%') )  ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();       
    }

    function cek_status_ang(){
        $tgl_spp = $this->input->post('tgl_cek');
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT case when status=1 and status_sempurna=1 and status_ubah=1 and '$tgl_spp'>=tgl_dpa_ubah then 'Perubahan' 
                when status=1 and status_sempurna=1 and status_ubah=1 and '$tgl_spp'>=tgl_dpa_sempurna and '$tgl_spp'<tgl_dpa_ubah then 'Penyempurnaan'
                when status=1 and status_sempurna=1 and status_ubah=1 and '$tgl_spp'<tgl_dpa_sempurna then 'Penyusunan'
                when status=1 and status_sempurna=1 and status_ubah=0 and '$tgl_spp'>=tgl_dpa_sempurna then 'Penyempurnaan' 
                when status=1 and status_sempurna=1 and status_ubah=0 and '$tgl_spp'<tgl_dpa_sempurna then 'Penyusunan'
                when status=1 and status_sempurna=0 and status_ubah=0 and '$tgl_spp'>=tgl_dpa then 'Penyusunan'
                else 'Penyusunan' end as anggaran from trhrka where kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'status_ang' => $resulte['anggaran']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();   
    }

function cek_status_sumber(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT status_sumber FROM ms_skpd WHERE kd_skpd='$skpd'";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'st_sumber' => $resulte['status_sumber']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();   
    }

    function load_trskpd() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');
        
        $jns_beban='';
        $cgiat = '';
        if ($jenis ==4){
            $jns_beban = "and b.jns_sub_kegiatan='5'";
        }
        else{
            $jns_beban = "and b.jns_sub_kegiatan='5'";
        }
        if ($giat !=''){                               
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        
        $sql = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,(select nm_program from ms_program where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                WHERE a.kd_skpd='$cskpd' AND a.status_sub_kegiatan='1' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                        'kd_program' => $resulte['kd_program'],  
                        'nm_program' => $resulte['nm_program'],
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }

    function load_dtransout(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
                FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti 
                AND a.kd_skpd=b.kd_skpd 
                WHERE a.no_bukti='$nomor' AND a.kd_skpd='$kd_skpd'
                ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek6'       => $resulte['kd_rek6'],
                        'nm_rek6'       => $resulte['nm_rek6'],
                        'nilai'         => $resulte['nilai'],
                        'lalu'          => $resulte['lalu'],
                        'sp2d'          => $resulte['sp2d'],
                        'sumber'          => $resulte['sumber'], 
                        'anggaran'      => $resulte['anggaran']                                                                                                                                                          
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }
function load_dtagih(){        
    $cskpd = $this->session->userdata('kdskpd');
    $data  = $this->cek_anggaran_model->cek_anggaran($cskpd);
        $nomor = $this->input->post('no');    
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtagih c LEFT JOIN trhtagih d ON c.no_bukti=d.no_bukti WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek6=b.kd_rek AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_sub_kegiatan=b.kd_sub_kegiatan AND f.kd_rek6=b.kd_rek6) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE jns_ang='$data' AND kd_sub_kegiatan = b.kd_sub_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek6=b.kd_rek) AS anggaran
                 FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek6'       => $resulte['kd_rek6'],
                        'kd_rek'        => $resulte['kd_rek'],
                        'nm_rek6'       => $resulte['nm_rek6'],
                        'sumber'       => $resulte['sumber'],
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
                        'kd_rek6'       => $resulte['kd_rek6'],
                        'nm_rek6'       => $resulte['nm_rek6'],
                        'nilai'         => $resulte['nilai']                                                                                                                                                         
                        );
                        $ii++;
        }           
        echo json_encode($result);
        $query1->free_result();
    }

    function dsimpan_pot_delete_ar()
    {
           $skpd    = $this->input->post('cskpd');
           $spm     = $this->input->post('spm');
           $kd_rek5 = $this->input->post('kd_rek5');
   
           $sql = "delete from trspmpot where kd_skpd='$skpd' and no_spm='$spm' and kd_rek5='$kd_rek5'";
           $asg = $this->db->query($sql);
            if ($asg > 0) { 
                 echo '1' ;
                 exit();
            } else {
                 echo '0' ;
                 exit();
            }
    }

function load_no_penagihan() { 
        $cskpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        
        $sql = "SELECT a.kd_skpd,a.no_bukti, tgl_bukti, a.ket,a.kontrak,kd_sub_kegiatan,SUM(b.nilai) as total 
                FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti
                WHERE a.kd_skpd='$cskpd' and (upper(a.kd_skpd) like upper('%$lccr%') or  
                upper(a.no_bukti) like upper('%$lccr%')) and a.no_bukti not in
                (SELECT isnull(no_tagih,'') no_tagih from trhspp WHERE kd_skpd = '$cskpd' 
                and (sp2d_batal is null or sp2d_batal<>'1') GROUP BY no_tagih)
                GROUP BY a.kd_skpd, a.no_bukti,tgl_bukti,a.ket,a.kontrak,kd_sub_kegiatan order by a.no_bukti";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_tagih' => $resulte['no_bukti'],
                        'tgl_tagih' => $resulte['tgl_bukti'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'ket' => $resulte['ket'],
                        'kegiatan' => $resulte['kd_sub_kegiatan'],
                        'kontrak' => $resulte['kontrak'],
                        'nila' => number_format($resulte['total'],2,'.',','),
                        'nil' => $resulte['total']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    } 




}
?>