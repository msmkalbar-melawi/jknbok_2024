<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class BKUController extends CI_Controller{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'LAPORAN BKU JKN';
        $this->template->set('title', 'LAPORAN BKU JKN');
        $this->template->load('template', 'jkn/laporanbku/index', $data);
    }

    function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd FROM ms_skpd_jkn a WHERE a.kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    public function laporanbkujkn()
    {
        $print = $this->uri->segment(4);
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(5);
        $bawah = $this->uri->segment(6);
        $kiri = $this->uri->segment(7);
        $kanan = $this->uri->segment(8);

        $this->db->query("recall_skpd_jkn '$lcskpd'");
        //$daerah=$this->tukd_model->get_nama($lcskpd,'daerah','sclient','kd_skpd');
        if ($pilih == 1) {
            $lctgl1 = $_REQUEST['tgl1'];
            $lctgl2 = $_REQUEST['tgl2'];
            $lcperiode = $this->tukd_model->tanggal_format_indonesia($lctgl1) . "  S.D. " . $this->tukd_model->tanggal_format_indonesia($lctgl2);
            $lcperiode1 = "Tanggal " . $this->tukd_model->tanggal_format_indonesia($lctgl1);
            $lcperiode2 = "Tanggal " . $this->tukd_model->tanggal_format_indonesia($lctgl2);
        } else {
            $bulan = $_REQUEST['bulan'];

            $lcperiode = $this->tukd_model->getBulan($bulan);
            if ($bulan == 1) {
                $lcperiode1 = "Bulan Sebelumnya";
            } else {
                $lcperiode1 = "Bulan " . $this->tukd_model->getBulan($bulan - 1);
            }
            $lcperiode2 = "Bulan " . $this->tukd_model->getBulan($bulan);;
        }

        $tgl_ttd = $_REQUEST['tgl_ttd'];


        if ($pilih == 1) {
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (
                SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             z.tgl_kas < '$lctgl1' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        } else {
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (

                SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) < '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) <'$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z 


             )z WHERE
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        }
        $tox = 0;
        //$tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)) AS jumlah FROM ms_skpd_jkn where kd_skpd='$lcskpd'";
        $tox_awal = "SELECT SUM(ISNULL(nilai,0)) AS jumlah FROM jkn_saldo_awal where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        // if (isset($bulan)) {
        //     if ($bulan == '1') {
        //         $tox = $hasil->row('jumlah');
        //     }
        // }

        $tox = $hasil->row('jumlah');
        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();

        $saldoawal = $trh4->sel;
        //$aa = $trh4->sel;
        $saldoawal = $saldoawal + $tox;
        $lcskpdd = substr($lcskpd, 0, 17);
        $lcskpdd = $lcskpdd . ".0000";
        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpdd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        if ($pilih == 1) {
            $asql = "SELECT terima-keluar as sisa FROM(select
      SUM(case when jns=1 then jumlah else 0 end) AS terima,
      SUM(case when jns=2 then jumlah else 0 end) AS keluar
      from (
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
      SELECT '2022-01-01' AS tgl, null AS bku,
	    'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
                union
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM TRHINLAIN WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
       SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
       a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
       from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
             left join
       (
        select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
        -- inner join trhstrpot g on d.no_bukti=g.no_terima and d.kd_skpd=g.kd_skpd 
        where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
       ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
              WHERE pay='BANK' and 
             (panjar not in ('1') or panjar is null) 
             union 
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
             join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
      UNION
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

      SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
            left join 
            (
                select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
                where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
             ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd 
            where a.pay='BANK' and a.kd_skpd='$lcskpd'                  
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all           
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            ) a
            where tgl<='$lctgl2' and kode='$lcskpd') a 
            ";
        } else {
            $asql = "SELECT terima-keluar as sisa FROM(select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (

                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
        --         SELECT '2022-01-01' AS tgl, null AS bku,
	    -- 'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
        -- union 
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
             -- select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             -- join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             -- where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             -- union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            union all

            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
            a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
            from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
             left join
            (
            select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
            where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay IN ('BANK') group by d.no_kas,d.kd_skpd
                ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
              WHERE pay='BANK' and 
             (panjar not in ('1') or panjar is null) 

             union 
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
             join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
      UNION
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan 
            union
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

      SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
            left join 
            (
                select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
                where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
             ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd 
            where a.pay='BANK' and a.kd_skpd='$lcskpd'                  
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all           
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            ) a
      where month(tgl)<'$bulan' and kode='$lcskpd') a 
            ";
        }


        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        // $keluarbank=$bank->keluar;
        // $terimabank=$bank->terima;
        // $saldobank=$terimabank-$keluarbank;
        $sisa = $bank->sisa;
        $saldobank = $sisa;


        $xterima_lalu = 0;
        $xkeluar_lalu = 0;
        $xhasil_lalu = 0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd_jkn where kd_skpd='$lcskpd'");
        foreach ($sk_lalu->result() as $rowxll) {
            $xskpd = $rowxll->kd_skpd;

            if ($pilih == 1) {
                $sqlitull = "kas_tunai_tgl_lalu '$xskpd','$lctgl1'";
            } else {
                $sqlitull = "kas_tunai_lalu '$xskpd','$bulan'";
            }

            $sqlituull = $this->db->query($sqlitull);
            $sqlituql = $sqlituull->row();
            $xterima_lalu = $xterima_lalu + $sqlituql->terima;
            $xkeluar_lalu = $xkeluar_lalu + $sqlituql->keluar;
        }
        $xhasil_lalu = ($xterima_lalu - $xkeluar_lalu);

        $xterima = 0;
        $xkeluar = 0;
        $xhasil_tunai = 0;
        $sk = $this->db->query("select kd_skpd from ms_skpd_jkn where kd_skpd='$lcskpd'");
        foreach ($sk->result() as $rowx) {
            $xskpd = $rowx->kd_skpd;

            if ($pilih == 1) {
                $sqlitu = "kas_tunai_tgl '$xskpd','$lctgl1','$lctgl2'";
            } else {
                $sqlitu = "kas_tunai '$xskpd','$bulan'";
            }

            $sqlituu = $this->db->query($sqlitu);
            $sqlituq = $sqlituu->row();
            $xterima = $xterima + $sqlituq->terima;
            $xkeluar = $xkeluar + $sqlituq->keluar;
        }
        $xhasil_tunai = ($xterima - $xkeluar) + $xhasil_lalu;

        //

        //saldo pajak

        if ($pilih == 1) {
            $asql_pjk = "SELECT x.kd_skpd,ISNULL(SUM(x.nil_trmpot - x.nil_strpot),0) as total FROM(
                SELECT a.kd_skpd, SUM(a.nilai) as nil_trmpot, 0 as nil_strpot FROM jkn_trdtrmpot a 
                INNER JOIN jkn_trhtrmpot b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.tgl_bukti 
                BETWEEN '$lctgl1' AND '$lctgl2' GROUP BY a.kd_skpd 
                UNION ALL
                SELECT a.kd_skpd, 0 as nil_trmpot, SUM(a.nilai)  as nil_strpot FROM jkn_trdstrpot a 
                INNER JOIN jkn_trhstrpot b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.tgl_bukti 
                BETWEEN '$lctgl1' AND '$lctgl2'  GROUP BY a.kd_skpd ) x WHERE x.kd_skpd='$lcskpd' 
                GROUP BY x.kd_skpd";
        } else {


            $asql_pjk = "SELECT x.kd_skpd,ISNULL(SUM(x.nil_trmpot - x.nil_strpot),0) as total FROM(
                SELECT a.kd_skpd, SUM(a.nilai) as nil_trmpot, 0 as nil_strpot FROM jkn_trdtrmpot a 
                INNER JOIN jkn_trhtrmpot b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti 
                WHERE MONTH(b.tgl_bukti)<='$bulan' GROUP BY a.kd_skpd 
                UNION ALL
                SELECT a.kd_skpd, 0 as nil_trmpot, SUM(a.nilai)  as nil_strpot FROM jkn_trdstrpot a 
                INNER JOIN jkn_trhstrpot b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti 
                WHERE MONTH(b.tgl_bukti)<='$bulan' GROUP BY a.kd_skpd ) x WHERE x.kd_skpd='$lcskpd' GROUP BY x.kd_skpd";
        }

        $hasil_pjk = $this->db->query($asql_pjk);
        $pjkk = $hasil_pjk->row();
        // if(!empty($pjkk->total)) {
        //     $sld_pajakk = $pjkk->total;
        //       }else{ 
        //         $sld_pajakk =0; 
        //     }
        $sld_pajakk = !empty($pjkk->total) ? $pjkk->total : 0;

     


        /*
        $esteh="SELECT 
                SUM(case when jns=1 then jumlah else 0 end ) AS terima,
                SUM(case when jns=2 then jumlah else 0 end) AS keluar
                FROM (
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI' UNION ALL
                select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar UNION ALL
                select tgl_sts as tgl,no_sts as bku, keterangan as ket, total as jumlah, '2' as jns, kd_skpd as kode from trhkasin_pkd where jns_trans<>4 and pot_khusus =0 union
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a left join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay = 'TUNAI' and panjar<>1 UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI') a 
                where month(a.tgl)<='$bulan' and kode='$lcskpd'";
        
                     $hasil = $this->db->query($esteh);
                     $okok = $hasil->row();
        $tox_awal="SELECT isnull(sld_awal,0) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";

                     $hasil = $this->db->query($tox_awal);
                     $tox = $hasil->row('jumlah');
                     $terima = $okok->terima;
                     $keluar = $okok->keluar;

            $querypaj="SELECT
                     sum(case when jns=1 then terima else 0 end) as debet,
                     sum(case when jns=2 then keluar else 0 end ) as kredit
                     FROM(
                     SELECT no_bukti AS bku,tgl_bukti AS tgl,(ket+'  no sp2d:'+no_sp2d) AS ket,nilai AS terima,'0' AS keluar,'1' as jns,kd_skpd FROM trhtrmpot UNION ALL
                     SELECT no_bukti AS bku,tgl_bukti AS tgl,(ket+'  terima:'+no_terima) AS ket,'0' AS terima,nilai AS keluar,'2' as jns,kd_skpd FROM trhstrpot ) a WHERE MONTH(tgl)<='$bulan' AND kd_skpd='$lcskpd'";
                     $querypjk=$this->db->query($querypaj);

                     $debet=$querypjk->row('debet');
                     $kredit=$querypjk->row('kredit');
                     $saldopjk=$debet-$kredit;
                     
                     $saldotunai=($terima+$tox+$saldopjk)-$keluar;
            */
        // SALDO SURAT BERHARGA (SP2D yang tanggal pencairannya beda dengan tanggal sp2dnya) 
        if ($pilih == 1) {
            $csql = "SELECT sum(nilai) as total from trhsp2d where (tgl_terima BETWEEN '$lctgl1' and '$lctgl2')  and kd_skpd = '$lcskpd' and status_terima = '1' and (tgl_kas > '$lctgl2' or no_kas is null or no_kas='')";
        } else {
            $csql = "SELECT sum(nilai) as total from trhsp2d where month(tgl_terima)='$bulan' and kd_skpd = '$lcskpd' and status_terima = '1' and (month(tgl_kas) > '$bulan' or no_kas is null or no_kas='')";
        }
        $hasil_srt = $this->db->query($csql);
        $saldoberharga = $hasil_srt->row('total');

        $lcskpdd = substr($lcskpd, 0, 22);

        $nippa = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $csql = "SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND left(kd_skpd,22) = '$lcskpdd' AND (kode='JKNBOK-PA' OR kode='JKNBOK-KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND left(kd_skpd,22) = '$lcskpdd' AND kode='JKNBOK-BK'";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $csql = "SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd = '$lcskpd' ";
        $hasil4 = $this->db->query($csql);
        $trh4 = $hasil4->row();
        // $nipbpp = str_replace('123456789', ' ', $_REQUEST['ttd3']);
        // $csql = "SELECT nip as nip_bk,nama as nm_bpp,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbpp' AND kd_skpd = '$lcskpd' AND kode='BPP'";
        // $hasil5 = $this->db->query($csql);
        // $trh5 = $hasil5->row();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/melawi.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td>
                        </tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;&nbsp;&nbsp;&nbsp;<strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>SKPD " . strtoupper($trh4->nm_skpd) . " </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>TAHUN ANGGARAN $thn_ang</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;</strong></td></tr>
                        </table>
                        ";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>BUKU KAS UMUM</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>Periode : " . $lcperiode . "</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            </table>
            <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
            <thead> 
            <tr>
     <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Tanggal</td>

                <td align=\"center\" bgcolor=\"#CCCCCC\"  width=\"10%\" style=\"font-size:12px;font-weight:bold\">No. Bukti</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"22%\" style=\"font-size:12px;font-weight:bold\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Saldo</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">2</td>
                
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
            </tr>
            </thead>";

        if ($pilih == 1) {
            $sql = "SELECT * FROM ( SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,CAST(no_kas AS INT) AS no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,CAST(b.no_kas AS INT) AS no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z )okei
               ORDER BY no_kas,st";
        } else {

            $sql = "SELECT * FROM ( SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,CAST(no_kas AS INT) AS no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,CAST(b.no_kas AS INT) AS no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z ) OKE
               ORDER BY no_kas, st";
        }
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcterima = 0;
        $lckeluar = 0;
        $lcterima_pajak = 0;
        $lckeluar_pajak = 0;
        $lhaasil = 0;
        // echo ($lhaasil);
        // return;
        $lhasil = $saldoawal;
        $saldolalu = number_format($lhasil, "2", ",", ".");
        $cRet .= "<tr><td valign=\"top\" width=\"5%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\" width=\"10%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                <td valign=\"top\"  width=\"13%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                
                                <td valign=\"top\"  width=\"20%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">Saldo Lalu</td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">$saldolalu</td></tr>";

        $totterima = 0;
        $totkeluar = 0;
        $source = $hasil->result();
        foreach ($source as $key => $row) {
            $cRet .= "<tr>";
            $lhasil = $lhasil + $row->terima - $row->keluar;
            // return;
            $totkeluar = $totkeluar + $row->keluar;
            $totterima = $totterima + $row->terima;
            if (!empty($row->tanggal)) {
                $a = $row->tanggal;
                $jaka = $this->tukd_model->tanggal_ind($a);
                $lcno = $lcno + 1;
                $no_bku = $row->no_kas;
            }

            $terima = $row->terima > 0 ? number_format($row->terima,2,',','.')  : null;
            $keluar = $row->keluar > 0 ? number_format($row->keluar,2,',','.')  : null;

            
            if ($key > 0 && $key < sizeof($source) ) {
                if ($source[$key-1]->no_kas == $row->no_kas) {
                    $cRet .= "
                        <td style='text-align:center; padding-left: 10px; font-size: 12px; border-top: 1px solid #FFF'></td>
                        <td style='text-align:center; padding-left: 10px; font-size: 12px; border-top: 1px dashed gray'></td>
                        <td style='text-align:center; padding-left: 10px; font-size: 12px; border-top: 1px dashed gray'></td>
                        <td style='padding-left: 10px; font-size: 12px; border-top: 1px dashed gray'>$row->uraian</td>
                        <td style='text-align: right; font-size: 12px; border-top: 1px dashed gray; padding-right: 10px'>$terima</td>
                        <td style='text-align: right; font-size: 12px; border-top: 1px dashed gray; padding-right: 10px'>$keluar</td>
                        <td style='text-align: right; font-size: 12px; border-top: 1px dashed gray; padding-right: 10px'>" . number_format($lhasil, "2", ",", ".") . "</td>
                    ";
                } else {
                    $cRet .= "<td style='text-align:center; padding-left: 10px; font-size: 12px'>$lcno</td>
                    <td style='text-align:center; padding-left: 10px; font-size: 12px'>$jaka</td>
                    <td style='text-align:center; padding-left: 10px; font-size: 12px'>$no_bku</td>
                    <td style='padding-left: 10px; font-size: 12px'>$row->uraian</td>
                    <td></td>
                    <td></td>
                    <td></td>";
                }
            }  else {
                $cRet .= "<td style='text-align:center; padding-left: 10px; font-size: 12px'>$lcno</td>
                    <td style='text-align:center; padding-left: 10px; font-size: 12px'>$jaka</td>
                    <td style='text-align:center; padding-left: 10px; font-size: 12px'>$no_bku</td>
                    <td style='padding-left: 10px; font-size: 12px'>$row->uraian</td>
                    <td></td>
                    <td></td>
                    <td></td>";
            }
            $cRet .= "</tr>";
        }
        $cRet .= "
            <tr>
                <td colspan='7' style='padding-top: 20px; border-bottom: 1px solid white;border-left: 1px solid white;border-right: 1px solid white'></td>
            </tr>
        ";

        if ($pilih == 1) {
            $csql = "SELECT SUM(z.terima) AS jmterima,SUM(z.keluar) AS jmkeluar , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             z.tgl_kas < '$lctgl1' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        } else {
            $csql = "SELECT SUM(z.terima) AS jmterima,SUM(z.keluar) AS jmkeluar , SUM(z.terima)-SUM(z.keluar) AS sel FROM (
                SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) < '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) <'$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z 


             )z WHERE
             month(z.tgl_kas) <='$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        }

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();
        if ($pilih == 1) {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas < '$lctgl1'  and a.kd_skpd = '$lcskpd'";
        } else {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) < '$bulan' and year(a.tgl_kas) = $thn_ang and a.kd_skpd = '$lcskpd'";
        }

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();

        $saldos = ($trh1->jmterima + $lcterima + $lcterima_pajak - $trh1->jmkeluar - $lckeluar - $lckeluar_pajak + $tox);
        $saldokasbung = ($xhasil_tunai + $saldobank +  $saldoberharga);
        if ($saldokasbung != 0 || $saldokasbung != '0') {
            $terbilangsaldo = $this->tukd_model->terbilang($saldokasbung);
        } else {
            $terbilangsaldo = "Nol Rupiah";
        }

        $cRet .= "
                  <tr>
                     <td colspan=\"4\" valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px white;\">Total Penerimaan dan Pengeluaran</td>
                     <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px white;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak + $tox), "2", ",", ".") . "</td>
                     <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px white;\">" . number_format(($trh1->jmkeluar + $lckeluar + $lckeluar_pajak), "2", ",", ".") . "</td>
                     <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px white;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak + $tox) - ($trh1->jmkeluar + $lckeluar + $lckeluar_pajak) - ($sld_pajakk), "2", ",", ".") . "</td>
                  </tr>";

        $cRet .= "
            </table>
            <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" >
            
            <tr>
            <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Saldo Pajak</td>
            <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($sld_pajakk), "2", ",", ".") . "</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
         </tr>
         <tr>
            <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
         </tr>
         <tr>
            <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
         </tr>";
        if ($_REQUEST['ttd2'] != "") {

            $cRet .= "<tr>
            <br>
            <br>
            <td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "
            <br>$trh3->jabatan<br>&nbsp;<br>&nbsp;<br>&nbsp;
            <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";
        }
        $cRet .= "</tr>
        </table>";

        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>Buku Kas Umum</title>");
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
        }
    }
}
