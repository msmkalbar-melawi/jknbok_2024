<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cmsc extends CI_Controller {

    function __construct(){    
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }

    function transout_bank() {
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI');   
        $this->template->load('template','tukd/cms/transout_cmsbank',$data) ; 
    } 

    function load_transout(){
        $kd_skpd     = $this->session->userdata('kdskpd');  
        $kd_id       = $this->session->userdata('pcNama');
        $bidang      = $this->session->userdata('bidang');  

        if($bidang=='5'){
            $init_user = "and a.kd_skpd='$kd_skpd'";
        }else{
            $init_user= "AND a.username='$kd_id'";  
        }
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where="AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_voucher = '$kriteria' or upper(a.ket) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT count(*) as total from trhtransout_cmsbank a where a.panjar = '0' AND a.kd_skpd='$kd_skpd' $init_user $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,a.status_upload ketup,
        a.status_validasi ketval FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' $init_user $where  and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' $init_user $where order by CAST (a.no_bukti as NUMERIC))  order by tgl_voucher,CAST (a.no_bukti as NUMERIC),kd_skpd ";
		
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


    function pot() {
        $lccr   = $this->input->post('q') ;
        echo $this->spm_model->pot($lccr);

    }

    function load_trskpd_sub() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');  
        $bid = $this->session->userdata('kdskpd');
              
        $lccr = $this->input->post('q');        
    
        $sql ="SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan, sum(a.nilai) as total from trdrka a  where kd_skpd='$cskpd'           
            AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))
            group by  a.kd_sub_kegiatan,a.nm_sub_kegiatan
            ";
    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }  

    function cari_rekening()
    {       
        $lccr =  $this->session->userdata('kdskpd');        ;

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

    function cari_rekening_tujuan($jenis='')
    {               
        $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $inskpd = substr($skpd,0,17);
        if($jenis==1){
            $jenis = "('1','2')";
        }else{
            $jenis = "('3')";
        }
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){            
            if($inskpd=='1.02.0.00.0.00.01'){
                $init_skpd = "a.kd_skpd='$skpd'";
            }else{
                $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
            }            
        }else{
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }
        
        $sql = "SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,
        a.keterangan,a.kd_skpd,a.jenis FROM ms_rekening_bank a where a.jenis in $jenis and $init_skpd 
        AND (UPPER(a.rekening) LIKE UPPER('%$lccr%') OR UPPER(a.nm_rekening) LIKE UPPER('%$lccr%'))
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

  function no_urut_cms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $kd_user = $this->session->userdata('pcNama');
    $tgl = date('Y-m-d');
    $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_voucher nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' and username='$kd_user' union
    select no_bukti nomor, 'Potongan Pajak Transaksi Non Tunai' ket, kd_skpd from trhtrmpot_cmsbank where kd_skpd = '$kd_skpd' and username='$kd_user') z WHERE KD_SKPD = '$kd_skpd'");
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

    function load_dtagih(){        
        $nomor = $this->input->post('no'); 
        $kd_skpd = $this->session->userdata('kdskpd');   
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtagih c LEFT JOIN trhtagih d ON c.no_bukti=d.no_bukti WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek6=b.kd_rek AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e 
                INNER JOIN trdspp f ON e.no_spp=f.no_spp 
                INNER JOIN trhspm g ON e.no_spp=g.no_spp 
                INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_sub_kegiatan=b.kd_sub_kegiatan AND f.kd_rek6=b.kd_rek6) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = b.kd_sub_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek6=b.kd_rek) AS anggaran FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' and a.kd_skpd='$kd_skpd' ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_subkegiatan'   => $resulte['kd_subkegiatan'],
                        'nm_subkegiatan'   => $resulte['nm_subkegiatan'],
                        'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek6'],
                        'kd_rek'        => $resulte['kd_rek'],
                        'nm_rek5'       => $resulte['nm_rek6'],
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

    function no_urut_tglcms(){
        $kd_skpd = $this->session->userdata('kdskpd');     
        date_default_timezone_set("Asia/Bangkok");
        $tgl = date('Y-m-d');
        $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
        select no_tgl nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' and tgl_voucher='$tgl') z WHERE KD_SKPD = '$kd_skpd'");
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


    function cek_status_ang(){
        echo $this->status_anggaran->cek_status_ang();
    }

    function load_sp2d_transout(){
       //$beban='',$giat=''
       $beban   = $this->input->post('jenis');
       $giat    = $this->input->post('giat');
       $kode    = $this->input->post('kd');
       $cari =$this->input->post('q');
       $bukti   = $this->input->post('bukti');
       $where = '';

       if (($beban != '' && $giat == '') || ($beban == '1')){
            $where = " and a.jns_spp IN ('1','2')"; 
       }
       if ($giat !='' && $beban != '1'){
            $where = " and a.jns_spp='$beban' and d.kd_sub_kegiatan='$giat'";
       }
       
        $kriteria = $this->input->post('q'); 
           $sql = "SELECT DISTINCT a.no_sp2d,a.tgl_sp2d,sum(a.nilai) as nilai,
                    0 as sisa                   
                    FROM trhsp2d a 
                    INNER JOIN trdspp d ON a.no_spp=d.no_spp AND a.kd_skpd=d.kd_skpd
                    WHERE LEFT(a.kd_skpd,17) = LEFT('$kode',17) AND a.status = 1  $where and a.no_sp2d like '%$kriteria%'
                    GROUP BY a.no_sp2d,a.tgl_sp2d
                    ORDER BY a.tgl_sp2d DESC, a.no_sp2d";

        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'no_sp2d' => $resulte['no_sp2d'],
                        'tgl_sp2d' => $resulte['tgl_sp2d'],
                        'nilai' => $resulte['nilai'],
                        'sisa' => $resulte['sisa']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek() {                      
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
            
        if ($rek !=''){        
            $notIn = " and kd_rek6 not in ($rek) " ;
        }else{
            $notIn  = "";
        }
        
        
            $field='nilai_ubah';
        
        
        if ($jenis=='1'){
            $sql = "SELECT a.kd_rek6,a.nm_rek6,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd AND c.username=d.username
                        WHERE
                        c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND left(d.kd_skpd,22) = left(a.kd_skpd,22)
                        AND c.kd_rek6 = a.kd_rek6
                        AND c.no_voucher <> '$nomor'
                        AND d.jns_spp='$jenis' AND d.status_validasi='0'
                        UNION ALL
                        SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                        c.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND left(d.kd_skpd,22) = left(a.kd_skpd,22)
                        AND c.kd_rek6 = a.kd_rek6 AND d.jns_spp='$jenis'
                        UNION ALL
                        SELECT SUM(x.nilai) as nilai FROM trdspp x
                        INNER JOIN trhspp y 
                        ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                        WHERE
                        x.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND left(x.kd_skpd,22) = left(a.kd_skpd,22)
                        AND x.kd_rek6 = a.kd_rek6
                        AND y.jns_spp IN ('3','4','5','6')
                        AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran,nilai_sempurna as nilai_sempurna, nilai_ubah AS nilai_ubah
                        FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat' AND a.kd_skpd = '$kode' $notIn  ";
                    
        } else {
           $sql = "SELECT b.kd_rek6,b.nm_rek6,
                    (SELECT SUM(c.nilai) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
                    WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
                    AND c.kd_rek6=b.kd_rek6 AND c.no_voucher <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran,
                    0 as nilai_sempurna,
                    0 as nilai_ubah
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_sub_kegiatan='$giat' $notIn ";
        }        
        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek5' => $resulte['kd_rek6'],  
                        'nm_rek5' => $resulte['nm_rek6'],
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

    function load_reksumber_dana() {                      
        $giat   = $this->input->post('giat');  
        $kode   = $this->input->post('kd');
        $rek    = $this->input->post('rek');        
        $lccr   = $this->input->post('q');
                
            $sql ="SELECT * from (
            select sumber1_ubah as sumber_dana,isnull(nilai_sumber,0) as nilai,isnull(nsumber1_su,0) as nilai_sempurna,isnull(nsumber1_ubah,0) as nilai_ubah from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,22)=left('$kode',22) 
            union ALL
            select sumber1_ubah as sumber_dana,isnull(nilai_sumber2,0) as nilai,isnull(nsumber2_su,0) as nilai_sempurna,isnull(nsumber2_ubah,0) as nilai_ubah from trdrka a where 
            a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and left(a.kd_skpd,22)=left('$kode',22) and nsumber2_ubah <> 0
            )z ";                

        //echo $sql;
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,        
                        'sumber_dana' => $resulte['sumber_dana'],  
                        'nilaidana' => $resulte['nilai'],
                        'nilaidana_semp' => $resulte['nilai_sempurna'],
                        'nilaidana_ubah' => $resulte['nilai_ubah']
                        );
                        $ii++;
        }                   
       echo json_encode($result);    
       $query1->free_result();             
    }

    function load_total_spd(){
       $kode    = $this->input->post('kode');
       $giat    = $this->input->post('giat');
       
            $sql = "SELECT SUM (a.nilai_final) AS total_spd FROM trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE left(b.kd_skpd,17) = left('$kode',17)
                    AND a.kd_subkegiatan = '$giat'
                    AND b.status = '1'";
       
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'total_spd' => number_format($resulte['total_spd'],2,'.',',') 
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_trans(){
       $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $no_bukti    = $this->input->post('no_simpan');
       $beban       = $this->input->post('beban');
       
       if($beban=="3"){
                    $sql = "SELECT sum(isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0)) total from trskpd a left join
                                    (           
                                            select g.kd_skpd, g.kd_sub_kegiatan,sum(g.lalu) spp from(
                                            SELECT b.kd_skpd, b.kd_sub_kegiatan,
                                            (SELECT isnull(SUM(c.nilai),0) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd AND c.username=d.username
                                            WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                                            d.kd_skpd=a.kd_skpd 
                                            AND c.kd_rek6=b.kd_rek6 AND c.no_voucher <> 'x' AND c.kd_sub_kegiatan='$kegiatan' and c.kd_skpd='$kdskpd') AS lalu,
                                            b.nilai AS sp2d
                                            FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                                            INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                                            INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                                            WHERE b.kd_sub_kegiatan='$kegiatan'
                                            )g group by g.kd_sub_kegiatan,g.kd_skpd
                                
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_skpd=d.kd_skpd
                                    left join 
                                    (
                                        
                                        select z.kd_skpd, z.kd_sub_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_skpd, f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
                                        where f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan,f.kd_skpd
                                        UNION ALL
                                        select f.kd_skpd, f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_sub_kegiatan,f.kd_skpd
                                        )z group by z.kd_sub_kegiatan, z.kd_skpd
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan and a.kd_skpd=g.kd_skpd
                                    left join 
                                    (
                                        SELECT t.kd_skpd, t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_sub_kegiatan,t.kd_skpd
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan and a.kd_skpd=z.kd_skpd
                                    where a.kd_sub_kegiatan='$kegiatan' and left(a.kd_skpd,17)=left('$kdskpd',17)"; 
       }else{
                $sql = "SELECT sum(isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0)) total from trskpd a left join
                                    (           
                                        select c.kd_skpd, c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_sub_kegiatan, c.kd_skpd
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_skpd = d.kd_skpd
                                    left join 
                                    (
                                        
                                        select z.kd_skpd, z.kd_sub_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_skpd, f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd and e.username=f.username
                                        where f.kd_sub_kegiatan='$kegiatan' and e.no_voucher<>'$no_bukti' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan, f.kd_skpd
                                        UNION ALL
                                        select f.kd_skpd, f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' group by f.kd_sub_kegiatan, f.kd_skpd
                                        )z group by z.kd_sub_kegiatan,z.kd_skpd
                                        
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan and a.kd_skpd = g.kd_skpd
                                    left join 
                                    (
                                        SELECT t.kd_skpd, t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,17)=left('$kdskpd',17) )
                                        GROUP BY t.kd_sub_kegiatan, t.kd_skpd
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan and a.kd_skpd = z.kd_skpd
                                    where a.kd_sub_kegiatan='$kegiatan' and left(a.kd_skpd,17)=left('$kdskpd',17)";     
       }
        
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

    function load_sisa_bank(){
        $kd_skpd = $this->session->userdata('kdskpd');                
            $query1 = $this->db->query("SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND kd_skpd='$kd_skpd' UNION ALL
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE kd_skpd='$kd_skpd' UNION ALL
            
           
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') AND bank='BNK' AND a.kd_skpd='$kd_skpd'
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
                    
                    UNION ALL   
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE kd_skpd='$kd_skpd' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and panjar not in ('3') UNION ALL
/*            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE kd_skpd='$kd_skpd' and pay='BANK' union ALL   */                               
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE kd_skpd='$kd_skpd' AND status_drop !='1' union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank WHERE kd_skpd='$kd_skpd'
            ) a
                where kode='$kd_skpd'");
        //}
                          
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                        'sisa' => number_format(($resulte['terima'] - $resulte['keluar']),2,'.',',')                      
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
           $query1->free_result();  
    }


    function cari_bank()
    {               
        $sql = "SELECT kode,nama FROM ms_bank";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte){ 
           
            $result[] = array(
                        'kode' => $resulte['kode'],     
                        'nama' => $resulte['nama']
                        );                        
        }
           
        echo json_encode($result);      
    }   

    function load_sisa_pot_ls(){
        $kd_skpd  = $this->session->userdata('kdskpd'); 
        $sp2d  = $this->input->post('sp2d');
        $query1 = $this->db->query("SELECT SUM(a.nilai) as total  FROM trspmpot a INNER JOIN trhsp2d b on b.no_spm = a.no_spm AND b.kd_skpd=a.kd_skpd
        where ((b.jns_spp = '4' AND b.jenis_beban != '1') or (b.jns_spp = '6' AND b.jenis_beban != '3'))
        and b.no_sp2d = '$sp2d' and b.kd_skpd= '$kd_skpd'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'sisa' => number_format($resulte['total'],2,'.',',')                      
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
            $query1->free_result(); 
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
            $sql = "DELETE from trhtransout_cmsbank where kd_skpd='$skpd' and no_voucher='$nomor' and username='$usernm'";
            $asg = $this->db->query($sql);
            
            if ($asg){
                $sql = "INSERT into trhtransout_cmsbank(no_voucher,tgl_voucher,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,no_tgl,ket_tujuan) 
                values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$nomor_tgl','$init_ket')";
				
                $asg = $this->db->query($sql);
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
            
        }elseif($tabel == 'trdtransout_cmsbank') {  
            // Simpan Detail //                                       

            $sql = "delete from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$skpd' and username='$usernm'";
            $asg = $this->db->query($sql);

            $sql = "delete from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$skpd' and username='$usernm'";
            $asg = $this->db->query($sql);

            if (!($asg)){
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }else{            
                $sql = "INSERT into trdtransout_cmsbank(no_voucher,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber,username)"; 
                $asg = $this->db->query($sql.$csql);

                $sql = "INSERT into trdtransout_transfercms(no_voucher,tgl_voucher,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai,username)"; 
                $asg = $this->db->query($sql.$csqlrek);                                       

                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                }
                else {                                                                        
                   $msg = array('pesan'=>'1');
                   echo json_encode($msg);
                }
            }
        }
    }


    function cek_simpan_user(){
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kd_user  = $this->session->userdata('pcNama'); 
        
        if ($field2==''){
        $hasil=$this->db->query(" SELECT count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' and username='$kd_user'");
        } else{
        $hasil=$this->db->query(" SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor' ");      
        }
        foreach ($hasil->result_array() as $row){
        $jumlah=$row['jumlah']; 
        }
        if($jumlah>0){
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        } else{
        $msg = array('pesan'=>'0');
        echo json_encode($msg);
        }
        
    }


    function simpan_potongan(){
        $tabel    = $this->input->post('tabel');        
        $nomor    = $this->input->post('no');
        $nomorvou = $this->input->post('novoucher');
        $tgl      = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');       
        $ket      = $this->input->post('ket');
        $total    = $this->input->post('total'); 
        $beban    = $this->input->post('beban');
        $npwp     = $this->input->post('npwp');      
        $kdrekbank= $this->input->post('kdbank');
        $nmrekbank= $this->input->post('nmbank');        
        $csql     = $this->input->post('sql');            
        $no_sp2d     = $this->input->post('no_sp2d');            
        $kd_giat     = $this->input->post('kd_giat');            
        $nm_giat     = $this->input->post('nm_giat');            
        $kd_rek     = $this->input->post('kd_rek');            
        $nm_rek     = $this->input->post('nm_rek');            
        $rekanan     = $this->input->post('rekanan');            
        $dir     = $this->input->post('dir');            
        $alamat     = $this->input->post('alamat');            
        $csql     = $this->input->post('sql');            
        $usernm   = $this->session->userdata('pcNama');
        $csqljur    = $this->input->post('sqljur');            
        $giatt      = "";
        $update     = date('Y-m-d H:i:s');      
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtrmpot_cmsbank') {
            $sql = "delete from trhtrmpot_cmsbank where kd_skpd='$skpd' and no_bukti='$nomor' and username='$usernm'";
            $asg = $this->db->query($sql);              

            if ($asg){
                
                $sql = "INSERT into trhtrmpot_cmsbank(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6,nm_rek6,nmrekan, pimpinan,alamat,no_voucher,rekening_tujuan,nm_rekening_tujuan,status_upload) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$nomorvou','$kdrekbank','$nmrekbank','0')";
						
                $asg = $this->db->query($sql);
                
                $sql = "UPDATE trhtransout_cmsbank set status_trmpot = '1' where kd_skpd='$skpd' and no_voucher='$nomorvou' and username='$usernm'";
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
            
        }elseif($tabel == 'trdtrmpot_cmsbank') {         
            
            // Simpan Detail //                       
                $sql = "DELETE from trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$skpd' and username='$usernm'";
                $asg = $this->db->query($sql);
                        
                if (!($asg)){
                    $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                    exit();
                }else{            
                    $sql = "INSERT into trdtrmpot_cmsbank(no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans,ebilling,username)"; 
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

   function hapus_transout(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $kd_id       = $this->session->userdata('pcNama');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "DELETE from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd' AND username='$kd_id'";
        $asg = $this->db->query($sql);

        if ($asg){
            $sql = "DELETE from trhtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd' AND username='$kd_id'";
            $asg = $this->db->query($sql);
            
            $sql = "DELETE from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$kd_skpd' AND username='$kd_id'";
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

    function load_dtransout(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_user = $this->session->userdata('pcNama');
        
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
                FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.no_voucher=b.no_voucher 
                AND a.kd_skpd=b.kd_skpd and a.username=b.username
                WHERE a.no_voucher='$nomor' AND a.kd_skpd='$skpd' and a.username='$kd_user'
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
                        'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek5'       => $resulte['kd_rek6'],
                        'nm_rek5'       => $resulte['nm_rek6'],
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

    function load_dtransout_transfercms(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_user = $this->session->userdata('pcNama');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,a.username,(select sum(nilai) from trdtransout_transfercms where no_voucher=b.no_voucher and kd_skpd=b.kd_skpd and username=a.username) as total
                FROM trhtransout_cmsbank a INNER JOIN trdtransout_transfercms b ON a.no_voucher=b.no_voucher and a.username=b.username
                AND a.kd_skpd=b.kd_skpd and a.username=b.username
                WHERE b.no_voucher='$nomor' AND b.kd_skpd='$skpd' AND a.username='$kd_user'
                group by b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,a.username
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

}