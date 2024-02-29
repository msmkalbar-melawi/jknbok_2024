<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** 
 * Fungsi Model
 */ 
  
class anggaran_murni_model extends CI_Model {

    function __construct()
    { 
        parent::__construct();
    }  
 
    /*menyimpan rekening rka murni trdrka*/

    function tsimpan_ar_rancang($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4){ 
             
        $nmskpd = $this->rka_model->get_nama($kdskpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmkegi = $this->rka_model->get_nama($kdkegi,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');
        $nmrek  = $this->rka_model->get_nama($kdrek,'nm_rek6','ms_rek6','kd_rek6');
        
        $notrdrka  = $kdskpd.'.'.$kdkegi.'.'.$kdrek ;

        $cek_rekening=$this->db->query("SELECT count(no_trdrka) hitung from trdrka where no_trdrka='$notrdrka'")->row()->hitung;

        if($cek_rekening==0){
           
                $query_ins = $this->db->query("
                    INSERT into trdrka(no_trdrka,kd_skpd,nm_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,
                                      nilai,nilai_sempurna,nilai_ubah,
                                       sumber,sumber2,sumber3,sumber4,
                                       nilai_sumber,nilai_sumber2,nilai_sumber3,nilai_sumber4,
                                       sumber1_su,sumber2_su,sumber3_su,sumber4_su,
                                       nsumber1_su,nsumber2_su,nsumber3_su,nsumber4_su,     
                                       sumber1_ubah,sumber2_ubah,sumber3_ubah,sumber4_ubah,
                                       nsumber1_ubah,nsumber2_ubah,nsumber3_ubah,nsumber4_ubah
                                       ) values('$notrdrka','$kdskpd','$nmskpd','$kdkegi','$nmkegi','$kdrek','$nmrek',
                                       $nilai, '$nilai','$nilai',
                                       '$sdana1','$sdana2','$sdana3','$sdana4',
                                       $ndana1,$ndana2,$ndana3,$ndana4, 
                                       '$sdana1','$sdana2','$sdana3','$sdana4',
                                       $ndana1,$ndana2,$ndana3,$ndana4,       
                                       '$sdana1','$sdana2','$sdana3','$sdana4',
                                       $ndana1,$ndana2,$ndana3,$ndana4)"); 
                
                $this->db->query("UPDATE a SET
                a.kd_skpd=b.kd_subskpd
                from trdrka a inner join (
                select * from kegiatan_bp where kd_skpd='$kdskpd') b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                where a.kd_skpd='$kdskpd'");

                                        return "1" ; 
        }else{
            $sql="UPDATE trdrka set 
            sumber='$sdana1',
            sumber2='$sdana2',
            sumber3='$sdana3',
            sumber4='$sdana4',
            nilai_sumber='$ndana1',
            nilai_sumber2='$ndana2',
            nilai_sumber3='$ndana3',
            nilai_sumber4='$ndana4',
            nsumber1_su='$ndana1',
            nsumber2_su='$ndana2',
            nsumber3_su='$ndana3',
            nsumber4_su='$ndana4',
            nsumber1_ubah='$ndana1',
            nsumber2_ubah='$ndana2',
            nsumber3_ubah='$ndana3',
            nsumber4_ubah='$ndana4',
            sumber1_su='$sdana1',
            sumber2_su='$sdana2',
            sumber3_su='$sdana3',
            sumber4_su='$sdana4',
            sumber1_ubah='$sdana1',
            sumber2_ubah='$sdana2',
            sumber3_ubah='$sdana3',
            sumber4_ubah='$sdana4'     
            where no_trdrka='$notrdrka'";
            echo $query_ins=$this->db->query($sql);

        }

    
    }
   
    function tsimpan_ar_geser($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4){ 
             
        $nmskpd = $this->rka_model->get_nama($kdskpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmkegi = $this ->rka_model->get_nama($kdkegi,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');
        $nmrek  = $this->rka_model->get_nama($kdrek,'nm_rek6','ms_rek6','kd_rek6');
        
        $notrdrka  = $kdskpd.'.'.$kdkegi.'.'.$kdrek ;

        $cek_rekening=$this->db->query("SELECT count(no_trdrka) hitung from trdrka where no_trdrka='$notrdrka'")->row()->hitung;

        if($cek_rekening==0){
           
                $query_ins = $this->db->query("
                    INSERT into trdrka(no_trdrka,kd_skpd,nm_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,
                                      nilai,nilai_sempurna,nilai_ubah,
                                       sumber,sumber2,sumber3,sumber4,
                                       nilai_sumber,nilai_sumber2,nilai_sumber3,nilai_sumber4,
                                       sumber1_su,sumber2_su,sumber3_su,sumber4_su,
                                       nsumber1_su,nsumber2_su,nsumber3_su,nsumber4_su,     
                                       sumber1_ubah,sumber2_ubah,sumber3_ubah,sumber4_ubah,
                                       nsumber1_ubah,nsumber2_ubah,nsumber3_ubah,nsumber4_ubah
                                       ) values('$notrdrka','$kdskpd','$nmskpd','$kdkegi','$nmkegi','$kdrek','$nmrek',
                                       0, '$nilai','$nilai',
                                       '','','','',
                                       0,0,0,0, 
                                       '$sdana1','$sdana2','$sdana3','$sdana4',
                                       $ndana1,$ndana2,$ndana3,$ndana4,       
                                       '$sdana1','$sdana2','$sdana3','$sdana4',
                                       $ndana1,$ndana2,$ndana3,$ndana4)"); 
                
                $this->db->query("UPDATE a SET
                a.kd_skpd=b.kd_subskpd
                from trdrka a inner join (
                select * from kegiatan_bp where kd_skpd='$kdskpd') b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                where a.kd_skpd='$kdskpd'");

                                        return "1" ; 
        }else{
            $sql="UPDATE trdrka set 
            nsumber1_su='$ndana1',
            nsumber2_su='$ndana2',
            nsumber3_su='$ndana3',
            nsumber4_su='$ndana4',
            nsumber1_ubah='$ndana1',
            nsumber2_ubah='$ndana2',
            nsumber3_ubah='$ndana3',
            nsumber4_ubah='$ndana4',
            sumber1_su='$sdana1',
            sumber2_su='$sdana2',
            sumber3_su='$sdana3',
            sumber4_su='$sdana4',
            sumber1_ubah='$sdana1',
            sumber2_ubah='$sdana2',
            sumber3_ubah='$sdana3',
            sumber4_ubah='$sdana4'   
            where no_trdrka='$notrdrka'";
            $query_ins=$this->db->query($sql);
            if ($query_ins) {
                return "1" ;
            } else {
                return "0" ;
            }  
        }

    
    }

    function tsimpan_ar_ubah($kdskpd,$kdkegi,$kdrek,$nilai,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4){ 
             
        $nmskpd = $this->rka_model->get_nama($kdskpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmkegi = $this->rka_model->get_nama($kdkegi,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');
        $nmrek  = $this->rka_model->get_nama($kdrek,'nm_rek6','ms_rek6','kd_rek6');
        
        $notrdrka  = $kdskpd.'.'.$kdkegi.'.'.$kdrek ;

        $cek_rekening=$this->db->query("SELECT count(no_trdrka) hitung from trdrka where no_trdrka='$notrdrka'")->row()->hitung;

        if($cek_rekening==0){
           
                $query_ins = $this->db->query("
                    INSERT into trdrka(no_trdrka,kd_skpd,nm_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,
                                      nilai,nilai_sempurna,nilai_ubah,
                                       sumber,sumber2,sumber3,sumber4,
                                       nilai_sumber,nilai_sumber2,nilai_sumber3,nilai_sumber4,
                                       sumber1_su,sumber2_su,sumber3_su,sumber4_su,
                                       nsumber1_su,nsumber2_su,nsumber3_su,nsumber4_su,     
                                       sumber1_ubah,sumber2_ubah,sumber3_ubah,sumber4_ubah,
                                       nsumber1_ubah,nsumber2_ubah,nsumber3_ubah,nsumber4_ubah
                                       ) values('$notrdrka','$kdskpd','$nmskpd','$kdkegi','$nmkegi','$kdrek','$nmrek',
                                       0, 0,'$nilai',
                                       '','','','',
                                       0,0,0,0, 
                                       '','','','',
                                       0,0,0,0,       
                                       '$sdana1','$sdana2','$sdana3','$sdana4',
                                       $ndana1,$ndana2,$ndana3,$ndana4)"); 
                
                // $this->db->query("UPDATE a SET
                // a.kd_skpd=b.kd_subskpd
                // from trdrka a inner join b on a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan = b.kd_sub_kegiatan
                // where a.kd_skpd='$kdskpd'");

                                        return "1" ; 
        }else{
            $sql="UPDATE trdrka set 
            nsumber1_ubah='$ndana1',
            nsumber2_ubah='$ndana2',
            nsumber3_ubah='$ndana3',
            nsumber4_ubah='$ndana4',
            sumber1_ubah='$sdana1',
            sumber2_ubah='$sdana2',
            sumber3_ubah='$sdana3',
            sumber4_ubah='$sdana4'   
            where no_trdrka='$notrdrka'";
            $query_ins=$this->db->query($sql);
            if ($query_ins) {
                return "1" ;
            } else {
                return "0" ;
            }  
        }

    
    }

    function pgiat_rancang($lccr,$cskpd) {
        
        /*untuk kunci gaji*/
        $tipe = $this->session->userdata('type');
        if($tipe==1){
            $status="";
        }else{
            $status="and status_keg='1'";
        }
        
       
        $sql  = " SELECT a.kd_skpd, a.nm_skpd,a.kd_sub_kegiatan,b.nm_sub_kegiatan,b.jns_sub_kegiatan,status_keg FROM trskpd a INNER JOIN  ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                where left(a.kd_gabungan,22)='$cskpd' and status_sub_kegiatan='1' and (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%') ) order by a.kd_kegiatan";
               // echo $sql;
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan'  => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan'  => $resulte['nm_sub_kegiatan'],
                        'jns_kegiatan' => $resulte['jns_sub_kegiatan'],
                        'status_keg'   => $resulte['status_keg'],
                        'kd_subskpd'   => $resulte['kd_skpd'],
                        'nm_subskpd'   => $resulte['nm_skpd'],
                        );
                        $ii++;
        }
        return json_encode($result);
           
    } 

    function ambil_rekening5_all_ar($kd_skpd,$lccr,$notin,$jnskegi) {
        
        if ( $jnskegi =='4' ) {
            $sql = "SELECT top 20 a.kd_rek6,a.nm_rek6 from ms_rek6 a
                    where left(a.kd_rek6,1)='4'
                    and ( upper(a.kd_rek6) like upper('%$lccr%')  or upper(a.nm_rek6) like upper('%$lccr%'))
                    group by a.kd_rek6,a.nm_rek6 order by a.kd_rek6";
        } else if($jnskegi=='61'){
                        $sql = "SELECT top 20 a.kd_rek6,a.nm_rek6 from ms_rek6 a
                    where left(a.kd_rek6,2)='61'
                    and ( upper(a.kd_rek6) like upper('%$lccr%')  or upper(a.nm_rek6) like upper('%$lccr%'))
                    group by a.kd_rek6,a.nm_rek6 order by a.kd_rek6";  
        } else if($jnskegi=='6'){
                        $sql = "SELECT top 20 a.kd_rek6,a.nm_rek6 from ms_rek6 a
                    where left(a.kd_rek6,1)='6'
                    and ( upper(a.kd_rek6) like upper('%$lccr%')  or upper(a.nm_rek6) like upper('%$lccr%'))
                    group by a.kd_rek6,a.nm_rek6 order by a.kd_rek6";                                       
        }else if($jnskegi=='62'){
                $sql = "SELECT top 20 a.kd_rek6,a.nm_rek6 from ms_rek6 a
                    where left(a.kd_rek6,2)='62' and ( upper(a.kd_rek6) like upper('%$lccr%')  or upper(a.nm_rek6) like upper('%$lccr%')) group by a.kd_rek6,a.nm_rek6 order by a.kd_rek6";                                        
        }else if($jnskegi='5'){
                $sql = "SELECT top 20 a.kd_rek6,a.nm_rek6 from ms_rek6 a
                    where left(a.kd_rek6,1)='5' and ( upper(a.kd_rek6) like upper('%$lccr%')  or upper(a.nm_rek6) like upper('%$lccr%')) group by a.kd_rek6,a.nm_rek6 order by a.kd_rek6";
        }else{
                $sql = "SELECT top 20 a.kd_rek6,a.nm_rek6 from ms_rek6 a
                    where left(a.kd_rek6,1)='5' and (upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) group by a.kd_rek6,a.nm_rek6 order by a.kd_rek6";
        }
       
        $query1 = $this->db->query($sql); 
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6']
                        );
                        $ii++;
        }
        return json_encode($result);
    }

    function load_nilai_kua_rancang($cskpd){ 
                
        $query1 = $this->db->query("SELECT a.nilai_kua, 
                                (SELECT SUM(nilai) FROM trdrka WHERE LEFT(kd_rek6,1)='5' AND left(kd_skpd,20) = left(a.kd_skpd,20)) as nilai_ang,
                                (SELECT SUM(nilai_sempurna) FROM trdrka WHERE LEFT(kd_rek6,1)='5' AND left(kd_skpd,20) =left(a.kd_skpd,20)) as nilai_angg_sempurna,
                                (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek6,1)='5' AND left(kd_skpd,20) = a.kd_skpd) as nilai_angg_ubah
                                FROM ms_skpd a where a.kd_skpd='$cskpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte) { 
            $result[] = array(
                        'id' => $ii,        
                        'nilai' => number_format($resulte['nilai_kua'],2,'.',','),                      
                        'kua_terpakai' => number_format($resulte['nilai_ang'],2,'.',','),
                        'kua_terpakai_sempurna' => number_format($resulte['nilai_angg_sempurna'],2,'.',','),                       
                        'kua_terpakai_ubah' => number_format($resulte['nilai_angg_ubah'],2,'.',',')  
                        );
                        $ii++;
        }  
        return json_encode($result);

    }

    function config_skpd2($skpd){
        $skpd     =  $this->input->post('kdskpd');
        $sql = "SELECT a.kd_skpd,a.nm_skpd,b.status,b.status_sempurna,b.status_ubah,b.status_rancang FROM  ms_skpd a LEFT JOIN trhrka b ON 
                a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' ";
        $query1 = $this->db->query($sql);  
        
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'status' => $resulte['status'],
                        'status_sem' => $resulte['status_sempurna'],
                        'status_ubah' => $resulte['status_ubah'],
                        'status_rancang' => $resulte['status_rancang']
                        );
                        $ii++;
        }
        

        
        
        echo json_encode($result);
        $query1->free_result(); 
    }


    function ambil_sdana($skpd,$kd_skpd,$lccr){
        
        $query1 = $this->db->query("SELECT top 100 *, 0 sisa from(
                                select kd_sumber_dana6 kd, nm_sumber_dana6 sum from ms_sumber_dana6
                                union all
                                select kd_sumber_dana5, nm_sumber_dana_5 from ms_sumber_dana5
                                union all
                                select kd_sumber_dana4, nm_sumber_dana4 from ms_sumber_dana4
                                union all
                                select kd_sumber_dana3, nm_sumber_dana3 from ms_sumber_dana3
                                union all
                                select kd_sumber_dana2, nm_sumber_dana2 from ms_sumber_dana2
                                union all
                                select kd_sumber_dana1, nm_sumber_dana1 from ms_sumber_dana1
                                ) sss where sum like '%$lccr%' ORDER BY kd") ;
        
        $ii     = 0;
        $result = array();
        foreach ($query1->result_array() as $resulte) {
            
            $result[] = array(
                'id'        => $ii,
                'kd_sdana'  => $resulte['kd'],
                'nm_sdana'  => $resulte['sum'],
                'nilai'     => $resulte['sisa']
                );
                $ii++;    
        }
        
        return json_encode($result) ;
    }  

    function thapus_rancang($skpd,$kegiatan,$rek) {
        
        $notrdrka=$skpd.'.'.$kegiatan.'.'.$rek;
        $query = $this->db->query(" DELETE from trdskpd_ro where left(kd_gabungan,22)=left('$skpd',22) and kd_sub_kegiatan='$kegiatan' and kd_rek6='$rek' ");
        $query = $this->db->query(" DELETE from trdrka where left(no_trdrka,22)=left('$skpd',22) and kd_sub_kegiatan='$kegiatan' and kd_rek6='$rek' ");
        $query = $this->db->query(" DELETE from trdpo where no_trdrka='$notrdrka' ");
        $query = $this->db->query(" UPDATE trskpd set total=( select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kegiatan' and left(kd_skpd,22)=left('$skpd',22) ) where kd_sub_kegiatan='$kegiatan' and left(kd_gabungan,22)='$skpd' ");   
        return 1;
    }

    function select_rka_rancang($kegiatan,$skpd) {

        $sql = "SELECT c.jns_kegiatan,b.nm_rek6, a.* from trdrka a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6 join 
                trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_skpd=c.kd_skpd
                where a.kd_sub_kegiatan='$kegiatan' and left(a.no_trdrka,22)='$skpd' order by a.kd_rek6 ";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],
                        'jenis_kegiatan' => $resulte['jns_kegiatan'],    
                        'nm_rek5' => $resulte['nm_rek6'],  
                        'nilai' => number_format($resulte['nilai'],"2",".",","),
                        'nilai_sempurna' => number_format($resulte['nilai_sempurna'],"2",".",","),
                        'nilai_ubah' => number_format($resulte['nilai_ubah'],"2",".",","),                             
                        'sumber' => $resulte['sumber'],
                        'sumber2' => $resulte['sumber2'],
                        'sumber3' => $resulte['sumber3'],
                        'sumber4' => $resulte['sumber4'],
                        'sumber1_su' => $resulte['sumber1_su'],
                        'sumber2_su' => $resulte['sumber2_su'],
                        'sumber3_su' => $resulte['sumber3_su'],
                        'sumber4_su' => $resulte['sumber4_su'],
                        'sumber1_ubah' => $resulte['sumber1_ubah'],
                        'sumber2_ubah' => $resulte['sumber2_ubah'],
                        'sumber3_ubah' => $resulte['sumber3_ubah'],
                        'sumber4_ubah' => $resulte['sumber4_ubah'],                                 
                        'nilai_sumber' => number_format($resulte['nilai_sumber'],"2",".",","), 
                        'nilai_sumber2' => number_format($resulte['nilai_sumber2'],"2",".",","), 
                        'nilai_sumber3' => number_format($resulte['nilai_sumber3'],"2",".",","),
                        'nilai_sumber4' => number_format($resulte['nilai_sumber4'],"2",".",","),
                        'nsumber1_su' => number_format($resulte['nsumber1_su'],"2",".",","), 
                        'nsumber2_su' => number_format($resulte['nsumber2_su'],"2",".",","), 
                        'nsumber3_su' => number_format($resulte['nsumber3_su'],"2",".",","),
                        'nsumber4_su' => number_format($resulte['nsumber4_su'],"2",".",","),                                    
                        'nsumber1_ubah' => number_format($resulte['nsumber1_ubah'],"2",".",","), 
                        'nsumber2_ubah' => number_format($resulte['nsumber2_ubah'],"2",".",","), 
                        'nsumber3_ubah' => number_format($resulte['nsumber3_ubah'],"2",".",","),
                        'nsumber4_ubah' => number_format($resulte['nsumber4_ubah'],"2",".",",")    
                        );
                        $ii++;
        }        
        return ($result);

    }  

    function cek_transaksi($skpd,$kegiatan,$rek){
        $query    = $this->db->query("SELECT kd_skpd, kd_sub_kegiatan, kd_rek6 from trdspp where LEFT(kd_skpd,20)=left('$skpd',20) and kd_sub_kegiatan='$kegiatan' and kd_rek6='$rek' group by kd_skpd, kd_sub_kegiatan, kd_rek6 ")->num_rows();
        return ($query);
    }

    function load_sum_rek_rancang($kdskpd,$sub_kegiatan){
        $sort= substr($kdskpd,0,4)=='1.02' || substr($kdskpd,0,4)=='7.01' ? "kd_skpd='$kdskpd'" : "left(kd_skpd,17)=left('$kdskpd',17)";     
        $query1 = $this->db->query(" SELECT sum(nilai) as rektotal,sum(nilai_sempurna) as rektotal_sempurna,sum(nilai_ubah) as rektotal_ubah from 
                                     trdrka where $sort and kd_sub_kegiatan='$sub_kegiatan'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'rektotal' => number_format($resulte['rektotal'],"2",".",","),  
                        'rektotal_sempurna' => number_format($resulte['rektotal_sempurna'],"2",".",","),
                        'rektotal_ubah' => number_format($resulte['rektotal_ubah'],"2",".",",")  
                        );
                        $ii++;
        }
        return $result;   
    }

    function load_sum_rek_rinci_rancang($kdskpd,$kegiatan,$rek){

        $norka=$kdskpd.'.'.$kegiatan.'.'.$rek;
        $query1 = $this->db->query("SELECT sum(total) as rektotal_rinci, sum(total_sempurna1) as rektotal_rinci_sempurna, sum(total_ubah) as rektotal_rinci_ubah from trdpo where no_trdrka='$norka'"); 
         
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' => $ii,        
                        'rektotal_rinci' => number_format($resulte['rektotal_rinci'],"2",".",","),
                        'rektotal_rinci_geser' => number_format($resulte['rektotal_rinci_sempurna'],"2",".",","),    
                        'rektotal_rinci_ubah' => number_format($resulte['rektotal_rinci_ubah'],"2",".",",")  
                        );
                        $ii++;
        }
        return $result;   
    }

    function rka_rinci_rancang($skpd,$kegiatan,$rekening,$norka,$idlokasi) {

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;

             
        $sql = "SELECT count(no_trdrka) as tot from trdpo where no_trdrka='$norka'" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        

        $sql    = "SELECT TOP $rows * from trdpo where no_trdrka='$norka' 
        and id not in (select TOP $offset id from trdpo where no_trdrka='$norka' order by no_po, id)
        order by no_po, id";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $row = array();
        $ii     = 0;

        foreach($query1->result_array() as $resulte)
        { 

            $volume1 = $resulte['volume1'];
            $volume2 = $resulte['volume2'];
            $volume3 = $resulte['volume3'];
            $volume4 = $resulte['volume4'];
            $volume_sempurna1 = $resulte['volume_sempurna1'];
            $volume_sempurna2 = $resulte['volume_sempurna2'];
            $volume_sempurna3 = $resulte['volume_sempurna3'];
            $volume_sempurna4 = $resulte['volume_sempurna4'];
            $volume_ubah1 = $resulte['volume_ubah1'];
            $volume_ubah2 = $resulte['volume_ubah2'];
            $volume_ubah3 = $resulte['volume_ubah3'];
            $volume_ubah4 = $resulte['volume_ubah4'];

            if($resulte['satuan1']==''){
                $volume1=null;
            }

            if($resulte['satuan2']==''){
                $volume2=null;
            }

            if($resulte['satuan3']==''){
                $volume3=null;
            }

            if($resulte['satuan4']==''){
                $volume4=null;
            }

            
            if($resulte['satuan_sempurna1']==''){
                $volume_sempurna1=null;
            }

            if($resulte['satuan_sempurna2']==''){
                $volume_sempurna2=null;
            }

            if($resulte['satuan_sempurna3']==''){
                $volume_sempurna3=null;
            }

            if($resulte['satuan_sempurna4']==''){
                $volume_sempurna4=null;
            }


            if($resulte['satuan_ubah1']==''){
                $volume_ubah1=null;
            }

            if($resulte['satuan_ubah2']==''){
                $volume_ubah2=null;
            }

            if($resulte['satuan_ubah3']==''){
                $volume_ubah3=null;
            }

            if($resulte['satuan_ubah4']==''){
                $volume_ubah4=null;
            }

            $row[] = array(
                        'id'      => $ii,   
                        'header'  => $resulte['header'],  
                        'kode'    => $resulte['ket_bl_teks'],
                        'id_lokasi'    => '', 
                        'unik'    => $resulte['id'],  
                        'kd_barang'    => $resulte['kd_barang'],
                        'id_standar_harga'  => $resulte['id_standar_harga'],   
                        'no_po'   => $resulte['no_po'],  
                        'uraian'  => $resulte['uraian'],

                        'spesifikasi'           => $resulte['spesifikasi'], 
                        'spesifikasi_sempurna'  => $resulte['spesifikasi_sempurna'],
                        'spesifikasi_ubah'      => $resulte['spesifikasi_ubah'], 

                        'volume1' => $volume1,  
                        'volume2' => $volume2,  
                        'volume3' => $volume3,
                        'volume4' => $volume4,

                        'satuan1' => $resulte['satuan1'],  
                        'satuan2' => $resulte['satuan2'],  
                        'satuan3' => $resulte['satuan3'],
                        'satuan4' => $resulte['satuan4'],
                        'volume'  => 0,  

                        'volume_sempurna1' => $volume_sempurna1,
                        'volume_sempurna2' => $volume_sempurna2,
                        'volume_sempurna3' => $volume_sempurna3,
                        'volume_sempurna4' => $volume_sempurna4,

                        'volume_ubah1' => $volume_ubah1,
                        'volume_ubah2' => $volume_ubah2,
                        'volume_ubah3' => $volume_ubah3,
                        'volume_ubah4' => $volume_ubah4,
                            
                        'satuan_sempurna1' => $resulte['satuan_sempurna1'], 
                        'satuan_sempurna2' => $resulte['satuan_sempurna2'], 
                        'satuan_sempurna3' => $resulte['satuan_sempurna3'], 
                        'satuan_sempurna4' => $resulte['satuan_sempurna4'], 

                        'satuan_ubah1' => $resulte['satuan_ubah1'], 
                        'satuan_ubah2' => $resulte['satuan_ubah2'], 
                        'satuan_ubah3' => $resulte['satuan_ubah3'], 
                        'satuan_ubah4' => $resulte['satuan_ubah4'], 

                        'pajak'          => $resulte['pajak'], 
                        'pajak_sempurna' => $resulte['pajak_sempurna'], 
                        'pajak_ubah'     => $resulte['pajak_ubah'], 


                        'koefisien'          => $resulte['koefisien'], 
                        'koefisien_sempurna' => $resulte['koefisien_sempurna'], 
                        'koefisien_ubah'     => $resulte['koefisien_ubah'],
                        'harga'             => number_format($resulte['harga'],"2",".",","),  
                        'harga_ubah'        => number_format($resulte['harga_ubah'],"2",".",","),
                        'harga_sempurna'    => number_format($resulte['harga_sempurna1'],"2",".",","),
                        
                        'total'             => number_format($resulte['total'],"2",".",","),
                        'total_sempurna1'    => number_format($resulte['total_sempurna1'],"2",".",","),
                        'total_ubah'        => number_format($resulte['total_ubah'],"2",".",",")
                        );
                        $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        return ($result);
    }

    /*menyimpan rincian detail rekening tdpo murni*/
    function tsimpan_rinci_jk_rancang($norka,$csql,$cskpd,$kegiatan,$rekening,$id,$sdana1,$sdana2,$sdana3,$sdana4,$ndana1,$ndana2,$ndana3,$ndana4){
        
                              
        $sql       = "DELETE from trdpo where  no_trdrka='$norka'";
        $asg       = $this->db->query($sql);
        
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    return json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT into trdpo(no_po,header,kode,kd_barang,no_trdrka,uraian,volume1,satuan1
                            ,harga1,total,volume_ubah1,satuan_ubah1,harga_ubah1,
                            total_ubah,volume2,satuan2,volume_ubah2,satuan_ubah2,volume3,satuan3,volume_ubah3
                            ,satuan_ubah3,tvolume,tvolume_ubah,
                            volume_sempurna1,volume_sempurna2,volume_sempurna3,tvolume_sempurna,satuan_sempurna1
                            ,satuan_sempurna2,satuan_sempurna3,
                            harga_sempurna1,total_sempurna)"; 
                    $asg = $this->db->query($sql.$csql);

                    $query1 = $this->db->query(" UPDATE trdrka set nilai= (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
                                                nilai_sempurna= (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
                                                nilai_ubah=(select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
                                                nilai_akhir_sempurna=(select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka)
                                                ,username='$id',last_update=getdate(),
                                                sumber='$sdana1',sumber2='$sdana2',sumber3='$sdana3',sumber4='$sdana4',nilai_sumber='$ndana1',
                                                nilai_sumber2=$ndana2,nilai_sumber3=$ndana3,nilai_sumber4=$ndana4,      
                                                sumber1_su='$sdana1',sumber2_su='$sdana2',sumber3_su='$sdana3',sumber4_su='$sdana4',nsumber1_su=$ndana1,
                                                nsumber2_su=$ndana2,nsumber3_su=$ndana3,nsumber4_su=$ndana4,        
                                                sumber1_ubah='$sdana1',sumber2_ubah='$sdana2',sumber3_ubah='$sdana3',sumber4_ubah='$sdana4',nsumber1_ubah=$ndana1,
                                                nsumber2_ubah=$ndana2,nsumber3_ubah=$ndana3,nsumber4_ubah=$ndana4       
                                                where no_trdrka='$norka' ");  
                    $query1 = $this->db->query("UPDATE trskpd set total= (select sum(nilai) as jum from trdrka where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ),
                                                total_sempurna= (select sum(nilai) as jum from trdrka where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                                total_ubah= (select sum(nilai) as jum from trdrka where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                                username='$id',last_update=getdate()
                                                where kd_kegiatan='$kegiatan' and left(kd_skpd,20)=left('$cskpd',20) ");  

                    if (!($asg)){
                       $msg = array('pesan'=>'0');
                        return json_encode($msg);
                    }  else {
                       $msg = array('pesan'=>'1');
                        return json_encode($msg);
                    }
                }

    }

    function rka_rinci($skpd,$kegiatan,$rekening) {
        $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
        $sql    = "select * from trdpo where no_trdrka='$norka' order by no_po";                   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;

        foreach($query1->result_array() as $resulte){            
            $result[] = array(
                        'id'      => $ii,   
                        'header'  => $resulte['header'],  
                        'kode'    => $resulte['kode'],  
                        'no_po'   => $resulte['no_po'],  
                        'uraian'  => $resulte['uraian'],  
                        'volume1' => $resulte['volume1'],  
                        'volume2' => $resulte['volume2'],  
                        'volume3' => $resulte['volume3'],  
                        'satuan1' => $resulte['satuan1'],  
                        'satuan2' => $resulte['satuan2'],  
                        'satuan3' => $resulte['satuan3'],
                        'volume'  => $resulte['tvolume'],  
                        'harga1'  => number_format($resulte['harga1'],"2",".",","),  
                        'hargap'  => number_format($resulte['harga1'],"2",".",","),                             
                        'harga2'  => number_format($resulte['harga2'],"2",".",","),                             
                        'harga3'  => number_format($resulte['harga3'],"2",".",","),
                        'totalp'  => number_format($resulte['total'],"2",".",",") ,                            
                        'total'   => number_format($resulte['total'],"2",".",","),
                        'volume_sempurna1' => $resulte['volume_sempurna1'],
                        'tvolume_sempurna' => $resulte['tvolume_sempurna'],                            
                        'satuan_sempurna1' => $resulte['satuan_sempurna1'],
                        'harga_sempurna1'  => number_format($resulte['harga_sempurna1'],"2",".",","),
                        'total_sempurna'  => number_format($resulte['total_sempurna'],"2",".",","),
                        'volume_ubah1' => $resulte['volume_ubah1'],
                        'tvolume_ubah' => $resulte['tvolume_ubah'],                            
                        'satuan_ubah1' => $resulte['satuan_ubah1'],
                        'harga_ubah1'  => number_format($resulte['harga_ubah1'],"2",".",","),
                        'total_ubah'  => number_format($resulte['total_ubah'],"2",".",","),
                        'lsusun'  => $resulte['lsusun'], 
                        'lsempurna'  => $resulte['lsempurna'], 
                        'lubh'  => $resulte['lubh']
                        );
                        $ii++;
        }       
        return json_encode($result);
    }

    function thapus_rinci_ar_all_rancang($norka,$rek,$skpd,$giat){
        $query = $this->db->query("DELETE from trdskpd_ro where kd_kegiatan='$giat' and left(kd_skpd,20)=left('$skpd',20) and kd_rek6='$rek'");
        $query = $this->db->query("DELETE from trdpo where no_trdrka='$norka'");
        $query = $this->db->query("UPDATE trdrka set nilai='0', nilai_sempurna='0', nilai_ubah='0' where no_trdrka='$norka'");
        if ( $query > 0 ){
            return '1' ;
        } else {
            return '0' ;
        }        
    }

    /*menyimpan indikator perkegiatan murni trskpd*/
    function simpan_det_keg_rancang($ttd, $ang_lalu, $skpd,$giat,$lokasi,$keterangan,$waktu_giat,$waktu_giat2,$sub_keluaran,$sas_prog,  
        $cap_prog,$tu_capai,$tk_capai,$tu_capai_p,$tk_capai_p,$tu_mas,$tk_mas,$tu_mas_p, 
        $tk_mas_p,$tu_kel,$tk_kel,$tu_kel_p,$tk_kel_p,$tu_has,$tk_has,$tu_has_p,$tk_has_p,$kel_sa,$ttd ,$ang_lalu){

        $this->db->query(" UPDATE trskpd set 
                                            lokasi='$lokasi',
                                            keterangan='$keterangan',
                                            waktu_giat='$waktu_giat',
                                            waktu_giat2='$waktu_giat2',
                                            sub_keluaran='$sub_keluaran',
                                            kd_pptk='$ttd',
                                            ang_lalu='$ang_lalu',
                                            sasaran_giat='$kel_sa',
                                            sasaran_program='$sas_prog',
                                            capaian_program='$cap_prog'  
        where left(kd_skpd,20)=left('$skpd',20) and kd_sub_kegiatan='$giat'"); 

        $this->db->query(" UPDATE trskpd set 
                                            tu_capai  ='$tu_capai',  
                                            tk_capai  ='$tk_capai',  
                                            tu_capai_p='$tu_capai_p',
                                            tk_capai_p='$tk_capai_p',
                                            tu_mas ='$tu_mas', 
                                            tk_mas ='$tk_mas', 
                                            tu_mas_p ='$tu_mas_p', 
                                            tk_mas_p ='$tk_mas_p', 
                                            tu_kel ='$tu_kel',
                                            tk_kel ='$tk_kel', 
                                            tu_kel_p ='$tu_kel_p', 
                                            tk_kel_p ='$tk_kel_p', 
                                            tu_has ='$tu_has', 
                                            tk_has ='$tk_has', 
                                            tu_has_p ='$tu_has_p', 
                                            tk_has_p   ='$tk_has_p',

                                            tu_capai_sempurna  ='$tu_capai',  
                                            tk_capai_sempurna  ='$tk_capai',  
                                            tu_capai_p_sempurna='$tu_capai_p',
                                            tk_capai_p_sempurna='$tk_capai_p',
                                            tu_mas_sempurna ='$tu_mas', 
                                            tk_mas_sempurna ='$tk_mas', 
                                            tu_mas_p_sempurna ='$tu_mas_p', 
                                            tk_mas_p_sempurna ='$tk_mas_p', 
                                            tu_kel_sempurna ='$tu_kel',
                                            tk_kel_sempurna ='$tk_kel', 
                                            tu_kel_p_sempurna ='$tu_kel_p', 
                                            tk_kel_p_sempurna ='$tk_kel_p', 
                                            tu_has_sempurna ='$tu_has', 
                                            tk_has_sempurna ='$tk_has', 
                                            tu_has_p_sempurna ='$tu_has_p', 
                                            tk_has_p_sempurna   ='$tk_has_p', 
                                            
                                            tu_capai_ubah  ='$tu_capai',  
                                            tk_capai_ubah  ='$tk_capai',  
                                            tu_capai_p_ubah='$tu_capai_p',
                                            tk_capai_p_ubah='$tk_capai_p',
                                            tu_mas_ubah ='$tu_mas', 
                                            tk_mas_ubah ='$tk_mas', 
                                            tu_mas_p_ubah ='$tu_mas_p', 
                                            tk_mas_p_ubah ='$tk_mas_p', 
                                            tu_kel_ubah ='$tu_kel',
                                            tk_kel_ubah ='$tk_kel', 
                                            tu_kel_p_ubah ='$tu_kel_p', 
                                            tk_kel_p_ubah ='$tk_kel_p', 
                                            tu_has_ubah ='$tu_has', 
                                            tk_has_ubah ='$tk_has', 
                                            tu_has_p_ubah ='$tu_has_p', 
                                            tk_has_p_ubah   ='$tk_has_p',                                                                                         
                                            sasaran_giat='$kel_sa',
                                            sasaran_program='$sas_prog',
                                            capaian_program='$cap_prog'  
        where left(kd_skpd,20)=left('$skpd',20) and left(kd_sub_kegiatan,12)=left('$giat',12)"); 
    return '1';
 
    }

    function load_det_keg_rancang($kdskpd,$kegiatan){

        $query1 = $this->db->query("SELECT * from trskpd where left(kd_skpd,20)=LEFT('$kdskpd',20) and kd_sub_kegiatan='$kegiatan'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'lokasi'        => $resulte['lokasi'],  
                        'sasaran_program'       => $resulte['sasaran_program'],  
                        'capaian_program'       => $resulte['capaian_program'],  
                        'waktu_giat'    => $resulte['waktu_giat'],
                        'waktu_giat2'   => $resulte['waktu_giat2'],  
                        'ttd'           => $resulte['kd_pptk'],
                        'tu_capai'      => $resulte['tu_capai'],
                        'tu_capai_p'    => $resulte['tu_capai_p'],
                        'tu_mas'        => $resulte['tu_mas'],
                        'tu_mas_p'      => $resulte['tu_mas_p'],
                        'tu_kel'        => $resulte['tu_kel'],
                        'tu_kel_p'      => $resulte['tu_kel_p'],
                        'tu_has'          => $resulte['tu_has'],
                        'tu_has_p'          => $resulte['tu_has_p'],
                        'tk_capai'         => $resulte['tk_capai'],
                        'tk_capai_p'         => $resulte['tk_capai_p'],
                        'tk_mas'          => $resulte['tk_mas'],
                        'tk_mas_p'          => $resulte['tk_mas_p'],
                        'tk_kel'          => $resulte['tk_kel'],
                        'tk_kel_p'          => $resulte['tk_kel_p'],
                        'tk_has'          => $resulte['tk_has'],
                        'tk_has_p'          => $resulte['tk_has_p'],
                        'kel_sasaran_kegiatan'          => $resulte['sasaran_giat'],
                        'sub_keluaran'          => $resulte['sub_keluaran'],
                        'keterangan'          => $resulte['keterangan'],
                        'ang_lalu' => number_format($resulte['ang_lalu']),
                        );
                        $ii++;
        }
        return ($result);   
    }

    function load_pengesahan_dpa($kriteria=''){
        $where ='';
        if ($kriteria <> ''){                               
            $where="where (upper(a.kd_skpd) like upper('%$kriteria%') or b.nm_skpd like'%$kriteria%')";            
        }
        
        $sql = "SELECT count(a.kd_skpd) as tot from trhrka a where right(a.kd_skpd,4)='0000'" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT a.*,b.nm_skpd,
                case when status_rancang = 1 THEN '<input type=\"checkbox\" checked>' else '-' end as status_rancangx,
                case when statu = 1 THEN '<input type=\"checkbox\" checked>' else '-' end as statusx,
                case when status_sempurna = 1 THEN '<input type=\"checkbox\" checked> Ke-'+geser_ke else '-' end as status_sempurnax,
                case when status_ubah = 1 THEN '<input type=\"checkbox\" checked>' else '-' end as status_ubahx

                from trhrka a inner join ms_skpd b on a.kd_skpd =b.kd_skpd where right(b.kd_skpd,4)='0000' $where  order by kd_skpd ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],        
                        'statu' => $resulte['statu'],
                        'status_rancang' => $resulte['status_rancang'],
                        'status_sempurna' => $resulte['status_sempurna'],
                        'status_ubah' => $resulte['status_ubah'],
                        'statusx' => $resulte['statusx'],
                        'status_rancangx' => $resulte['status_rancangx'],
                        'status_sempurnax' => $resulte['status_sempurnax'],
                        'status_ubahx' => $resulte['status_ubahx'],
                        'no_dpa' => $resulte['no_dpa'],
                        'tgl_dpa_rancang' => $resulte['tgl_dpa_rancang'],       
                        'tgl_dpa' => $resulte['tgl_dpa'],
                        'no_dpa_ubah' => $resulte['no_dpa_ubah'],
                        'tgl_dpa_ubah' => $resulte['tgl_dpa_ubah'],
                        'no_dpa_sempurna' => $resulte['no_dpa_sempurna'],
                        'geser_ke' => $resulte['geser_ke'],
                        'tgl_dpa_sempurna' => $resulte['tgl_dpa_sempurna']

                        );
                        $ii++;
        }
           
        return json_encode($result);     
    }

    function simpan_pengesahan($kdskpd,$sdpa,$srka,$sdppa,$nodpa,$tanggal1,$nodppa,$tanggal2,$sdpasempurna,$nosempurna,$tanggal3,$sdpasempurnax,$nosempurnax,$tanggal3x,$norka,$tanggal4,$last_update, $geserke){ 

        if($sdpasempurna==1){
            $sql2 = "UPDATE trhrka set 
                    status_sempurna = '$sdpasempurna',
                    statu = '$sdpa',
                    status_rancang = '$srka',
                    status_ubah= '$sdppa',
                    no_dpa = '$nodpa',
                    no_dpa_ubah = '$nodppa',
                    no_dpa_sempurna = '$nosempurna',
                    tgl_dpa_ubah = '$tanggal2',
                    tgl_dpa_sempurna ='$tanggal3',
                    tgl_dpa ='$tanggal1',
                    geser_ke='$geserke'
                    where left(kd_skpd,17)=left('$kdskpd',17)";

            switch ($geserke) {

                /*pergeseran ke satu masuk ke kolom pergeseran DUA karna sebagian kolom pergeseran SATU kurang lengkap*/
                case '1':
                    $update_geser1="UPDATE trdrka set 
                                    nilaisempurna2=nilai_sempurna,
                                    nsumber1_su2=nsumber1_su,
                                    nsumber2_su2=nsumber2_su,
                                    nsumber3_su2=nsumber3_su,
                                    nsumber4_su2=nsumber4_su,

                                    sumber1_su2=sumber1_su,
                                    sumber2_su2=sumber2_su,
                                    sumber3_su2=sumber3_su,
                                    sumber4_su2=sumber4_su
                                    where left(kd_skpd,17)=left('$kdskpd',17)";
                    $this->db->query($update_geser1);

                    $update_geser2="UPDATE trdpo set 
                                    volume_sempurna21=volume_sempurna1,
                                    volume_sempurna22=volume_sempurna2,
                                    volume_sempurna23=volume_sempurna3,
                                    volume_sempurna24=volume_sempurna4,

                                    satuan_sempurna21=satuan_sempurna1,
                                    satuan_sempurna22=satuan_sempurna2,
                                    satuan_sempurna23=satuan_sempurna3,
                                    satuan_sempurna24=satuan_sempurna4,
                                    pajak_sempurna2=pajak_sempurna,
                                    koefisien_sempurna2=koefisien_sempurna,
                                    spesifikasi_sempurna2=spesifikasi_sempurna,

                                    harga_sempurna2=harga_sempurna1,
                                    total_sempurna2=total_sempurna
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);

                     $update_geser2="UPDATE trdskpd_ro set 
                                    nilaisempurna2=nilai_sempurna  
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);

                    break;
                case '2':
                    $update_geser1="UPDATE trdrka set 
                                    nilaisempurna3=nilai_sempurna,
                                    nsumber1_su3=nsumber1_su,
                                    nsumber2_su3=nsumber2_su,
                                    nsumber3_su3=nsumber3_su,
                                    nsumber4_su3=nsumber4_su,

                                    sumber1_su3=sumber1_su,
                                    sumber2_su3=sumber2_su,
                                    sumber3_su3=sumber3_su,
                                    sumber4_su3=sumber4_su
                                    where left(kd_skpd,17)=left('$kdskpd',17)";
                    $this->db->query($update_geser1);

                    $update_geser2="UPDATE trdpo set 
                                    volume_sempurna31=volume_sempurna1,
                                    volume_sempurna32=volume_sempurna2,
                                    volume_sempurna33=volume_sempurna3,
                                    volume_sempurna34=volume_sempurna4,

                                    satuan_sempurna31=satuan_sempurna1,
                                    satuan_sempurna32=satuan_sempurna2,
                                    satuan_sempurna33=satuan_sempurna3,
                                    satuan_sempurna34=satuan_sempurna4,
                                    pajak_sempurna3=pajak_sempurna,
                                    koefisien_sempurna3=koefisien_sempurna,
                                    spesifikasi_sempurna3=spesifikasi_sempurna,
                                    harga_sempurna3=harga_sempurna1,
                                    total_sempurna3=total_sempurna
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);

                     $update_geser2="UPDATE trdskpd_ro set 
                                    nilaisempurna3=nilai_sempurna  
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);
                    break;

                case '3':
                    $update_geser1="UPDATE trdrka set 
                                    nilaisempurna4=nilai_sempurna,
                                    nsumber1_su4=nsumber1_su,
                                    nsumber2_su4=nsumber2_su,
                                    nsumber3_su4=nsumber3_su,
                                    nsumber4_su4=nsumber4_su,

                                    sumber1_su4=sumber1_su,
                                    sumber2_su4=sumber2_su,
                                    sumber3_su4=sumber3_su,
                                    sumber4_su4=sumber4_su
                                    where left(kd_skpd,17)=left('$kdskpd',17)";
                    $this->db->query($update_geser1);

                    $update_geser2="UPDATE trdpo set 
                                    volume_sempurna41=volume_sempurna1,
                                    volume_sempurna42=volume_sempurna2,
                                    volume_sempurna43=volume_sempurna3,
                                    volume_sempurna44=volume_sempurna4,

                                    satuan_sempurna41=satuan_sempurna1,
                                    satuan_sempurna42=satuan_sempurna2,
                                    satuan_sempurna43=satuan_sempurna3,
                                    satuan_sempurna44=satuan_sempurna4,
                                    pajak_sempurna4=pajak_sempurna,
                                    koefisien_sempurna4=koefisien_sempurna,
                                    spesifikasi_sempurna4=spesifikasi_sempurna,

                                    harga_sempurna4=harga_sempurna1,
                                    total_sempurna4=total_sempurna
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);
                    break;

                    $update_geser2="UPDATE trdskpd_ro set 
                                    nilaisempurna4=nilai_sempurna  
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);
                case '4':
                    $update_geser1="UPDATE trdrka set 
                                    nilaisempurna5=nilai_sempurna,
                                    nsumber1_su5=nsumber1_su,
                                    nsumber2_su5=nsumber2_su,
                                    nsumber3_su5=nsumber3_su,
                                    nsumber4_su5=nsumber4_su,

                                    sumber1_su5=sumber1_su,
                                    sumber2_su5=sumber2_su,
                                    sumber3_su5=sumber3_su,
                                    sumber4_su5=sumber4_su
                                    where left(kd_skpd,17)=left('$kdskpd',17)";
                    $this->db->query($update_geser1);

                   $update_geser2="UPDATE trdpo set 
                                    volume_sempurna51=volume_sempurna1,
                                    volume_sempurna52=volume_sempurna2,
                                    volume_sempurna53=volume_sempurna3,
                                    volume_sempurna54=volume_sempurna4,

                                    satuan_sempurna51=satuan_sempurna1,
                                    satuan_sempurna52=satuan_sempurna2,
                                    satuan_sempurna53=satuan_sempurna3,
                                    satuan_sempurna54=satuan_sempurna4,
                                    pajak_sempurna5=pajak_sempurna,
                                    koefisien_sempurna5=koefisien_sempurna,
                                    spesifikasi_sempurna5=spesifikasi_sempurna,

                                    harga_sempurna5=harga_sempurna1,
                                    total_sempurna5=total_sempurna
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);

                     $update_geser2="UPDATE trdskpd_ro set 
                                    nilaisempurna5=nilai_sempurna  
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);

                    break;

                case '5':
                    $update_geser1="UPDATE trdrka set 
                                    nilaisempurna6=nilai_sempurna,
                                    nsumber1_su6=nsumber1_su,
                                    nsumber2_su6=nsumber2_su,
                                    nsumber3_su6=nsumber3_su,
                                    nsumber4_su6=nsumber4_su,

                                    sumber1_su6=sumber1_su,
                                    sumber2_su6=sumber2_su,
                                    sumber3_su6=sumber3_su,
                                    sumber4_su6=sumber4_su
                                    where left(kd_skpd,17)=left('$kdskpd',17)";
                    $this->db->query($update_geser1);

                    $update_geser2="UPDATE trdpo set 
                                    volume_sempurna61=volume_sempurna1,
                                    volume_sempurna62=volume_sempurna2,
                                    volume_sempurna63=volume_sempurna3,
                                    volume_sempurna64=volume_sempurna4,

                                    satuan_sempurna61=satuan_sempurna1,
                                    satuan_sempurna62=satuan_sempurna2,
                                    satuan_sempurna63=satuan_sempurna3,
                                    satuan_sempurna64=satuan_sempurna4,
                                    pajak_sempurna6=pajak_sempurna,
                                    koefisien_sempurna6=koefisien_sempurna,
                                    spesifikasi_sempurna6=spesifikasi_sempurna,

                                    harga_sempurna6=harga_sempurna1,
                                    total_sempurna6=total_sempurna
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);

                     $update_geser2="UPDATE trdskpd_ro set 
                                    nilaisempurna6=nilai_sempurna  
                                    where left(kd_skpd,17)=left('$kdskpd',17) ";
                     $this->db->query($update_geser2);
                    break;


                case '6':
                    # code...
                    break;
                default:
                    # code...
                    break;
            }




        }else{
            $sql2 = "UPDATE trhrka set 
                    status_sempurna = '$sdpasempurna',
                    statu = '$sdpa',
                    status_rancang = '$srka',
                    status_ubah= '$sdppa',
                    no_dpa = '$nodpa',
                    no_dpa_ubah = '$nodppa',
                    no_dpa_sempurna = '$nosempurna',
                    tgl_dpa_ubah = '$tanggal2',
                    tgl_dpa_sempurna ='$tanggal3',
                    tgl_dpa ='$tanggal1'
                    where left(kd_skpd,17)=left('$kdskpd',17)";
        }

        return $asg = $this->db->query($sql2);
                
    }
}