<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class tunai extends CI_Controller {

    function __construct(){    
        parent::__construct();
        if($this->session->userdata('pcNama')==''){
        	redirect('welcome');
        }
    }

    function ambil_simpanan_ar(){
        $data['page_title']= 'INPUT AMBIL SIMPANAN';
        $this->template->set('title', 'INPUT AMBIL SIMPANAN');   
        $this->template->load('template','tukd/transaksi/ambil_simpanan_ar',$data) ; 
    }   
    
   function transout()
    {
        $data['page_title']= 'INPUT PEMBAYARAN TRANSAKSI TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI TUNAI'); 
        
        $kd_skpd  = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $hasil_skpd = $cek_skpd->hasil;
        $hslskpd = substr($kd_skpd,0,7);
        if($hasil_skpd==1){
            if($kd_skpd=='1.02.01.00'){
                $this->template->load('template','tukd/tunai/transout_tunai',$data); 
            }else{
                if($hslskpd=='1.02.01'){
                    $this->template->load('template','tukd/tunai/transout_tunai_pusk',$data);
                }else{
                    $this->template->load('template','tukd/tunai/transout_tunai',$data);    
                }                
            }            
        }else{
           $this->template->load('template','tukd/tunai/transout_tunai',$data); 
        }           
    }

    function load_transout_tunai(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $tipe       = $this->session->userdata('type');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if($cek_skpd1==1){
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        }else{
            if(substr($kd_skpd,8,2)=='00'){
                $init_skpd = "left(a.kd_skpd,22)=left('$kd_skpd',22)";
            }else{
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }            
        }        
        
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
       
        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '0' AND a.pay='TUNAI' AND $init_skpd $where " ;

        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();        
        
        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z 
        join trhlpj v on v.no_lpj = z.no_lpj
        where v.jenis=a.jns_spp and z.no_bukti = a.no_bukti and z.kd_bp_skpd = a.kd_skpd) ketlpj,
        (CASE WHEN a.tgl_bukti<'2018-01-01' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '0' AND a.pay='TUNAI' AND $init_skpd $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '0' AND a.pay='TUNAI' AND $init_skpd $where order by CAST (a.no_bukti as NUMERIC))  order by CAST (a.no_bukti as NUMERIC),kd_skpd ";
        
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
    function pot() {
        $lccr   = $this->input->post('q') ;
        echo $this->spm_model->pot($lccr);
    }

    function load_trskpd_sub_tunai() {        
        $jenis =$this->input->post('jenis');
        $giat =$this->input->post('giat');
        $cskpd = $this->input->post('kd');

        $bid = $this->session->userdata('kdskpd');
        $cgiat = '';

        if ($giat !=''){                               
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }                
        $lccr = $this->input->post('q');        



        $sql ="SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan,sum(a.nilai) as total from trdrka a 
    where a.kd_skpd='$cskpd' $cgiat and left(kd_rek6,1)='5' AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_sub_kegiatan,a.nm_sub_kegiatan
    order by  a.kd_sub_kegiatan,a.nm_sub_kegiatan
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
                        'kd_program' => '',  
                        'nm_program' => '',
                        'total'       => $resulte['total']        
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();        
    }

    function no_urut(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
    select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
    select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
    select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
    select no_panjar nomor, 'Pemberian Panjar CMS' ket,kd_skpd from tr_panjar_cmsbank where  isnumeric(no_panjar)=1  union ALL
    select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
    select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
    select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
    select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
    select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
    select no_bukti+1 nomor, 'Ambil Simpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop !='1' union ALL
    select no_bukti nomor, 'Ambil Drop Dana' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop ='1' union ALL
    select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
    select no_kas nomor, 'Setor Simpanan CMS' ket,kd_skpd_sumber kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_bukti)=1 union all
    select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
    select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='3' 
    UNION ALL
    select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE KD_SKPD = '$kd_skpd'");
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
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_sub_kegiatan=b.kd_sub_kegiatan AND f.kd_rek6=b.kd_rek6) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = b.kd_sub_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek6=b.kd_rek) AS anggaran FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' and a.kd_skpd='$kd_skpd' ORDER BY b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'      => $resulte['no_bukti'],
                        'no_sp2d'       => $resulte['no_sp2d'],
                        'kd_subkegiatan'   => $resulte['kd_sub_kegiatan'],
                        'nm_subkegiatan'   => $resulte['nm_sub_kegiatan'],
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


    function load_rek_tunai() {                      
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
            
            if($giat=='1.01.1.01.01.00.22.002'){
                $sql = "SELECT a.kd_rek6,a.nm_rek6,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
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
                        FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat' AND a.kd_rek6 in ('5221104') AND a.kd_skpd = '$kode' $notIn  ";
                
            }else if($giat=='4.08.4.08.01.00.01.351'){
                $sql = "SELECT a.kd_rek6,a.nm_rek6,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
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
                        FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat'  AND a.kd_skpd = '$kode' $notIn  ";
                
            }else{
                $sql = "SELECT a.kd_rek6,a.nm_rek6,
                    (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
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
                        FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat' 
                        AND a.kd_skpd = '$kode' $notIn  ";
                
            }
                    
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

    function load_sisa_tunai(){
        $kd_skpd  = $this->session->userdata('kdskpd');
        $skpdbp = explode('.',$kd_skpd);
        

        
        if($skpdbp[7]=='0000'){
                $init_skpd = "a.kd_skpd='$kd_skpd'";
                $init_skpd2 = "kd_skpd='$kd_skpd'";
                $init_skpd3 = "kd_skpd_sumber='$kd_skpd'";
                $init_skpd4 = "kode='$kd_skpd'";  
                  
        }else{
            $init_skpd = "left(a.kd_skpd,22)=left('$kd_skpd',22)";
            $init_skpd2 = "left(kd_skpd,22)=left('$kd_skpd',22)";
            $init_skpd3 = "left(kd_skpd_sumber,22)=left('$kd_skpd',22)";
            $init_skpd4 = "left(kode,22)=left('$kd_skpd',22)";
        }
                        
        $query1 = $this->db->query("
                SELECT 
                SUM(case when jns=1 then jumlah else 0 end ) AS terima,
                SUM(case when jns=2 then jumlah else 0 end) AS keluar
                FROM (
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE $init_skpd2 UNION ALL
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2')  AND $init_skpd and bank='TN'
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd             
                UNION ALL
                SELECT  a.tgl_bukti AS tgl, a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar <> 1
                                AND $init_skpd 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where $init_skpd2 GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                 and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and $init_skpd2 
                                
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and $init_skpd2)
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                SELECT  tgl_bukti AS tgl,   no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar <> 1 and no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where $init_skpd2 GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND   no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and $init_skpd2 
                            
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and $init_skpd2              
                UNION ALL
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' AND $init_skpd2 UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan WHERE $init_skpd3
                ) a 
                where $init_skpd4");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'sisa' => number_format(($resulte['terima'] - $resulte['keluar']),2,'.',','),                      
                        'keluar' => number_format($resulte['keluar'],0),
                        'terima' => number_format($resulte['terima'],0)
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result(); 
    }

    function simpan_transout_tunai(){
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
        
        $stt_val  = 0;
        $stt_up   = 0;
       
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor' and pay='TUNAI'";
            $asg = $this->db->query($sql);
            
            if ($asg){
                $sql = "insert into trhtransout (no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d')";
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
                    $sql = "insert into trdtransout (no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber)"; 
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


function cek_simpan(){
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');        
        if ($field2==''){
        $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else{
        $hasil=$this->db->query(" select count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
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

    function load_dtransout_tunai(){ 
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
                FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti 
                AND a.kd_skpd=b.kd_skpd 
                WHERE a.no_bukti='$nomor' AND a.kd_skpd='$skpd' AND a.pay='TUNAI'
                ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id'            => $ii,        
                        'no_bukti'    => $resulte['no_bukti'],
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
    }


    function load_dpot(){        
        $nomor = $this->input->post('no');
        $sql = "SELECT * from trdtrmpot where no_bukti='$nomor' ";
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
    
    function hapus_transout_tunai(){
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        if ($asg){
            $sql = "delete from trhtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd' AND pay='TUNAI'";
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




}