<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Tunai extends CI_Controller
{

    public $org_keu = "";
    public $skpd_keu = "";

    function __contruct()
    {
        parent::__construct();
    }
    /////////////////////////



    function ambil_simpanan_ar()
    {
        $data['page_title'] = 'INPUT AMBIL SIMPANAN';
        $this->template->set('title', 'INPUT AMBIL SIMPANAN');
        $this->template->load('template', 'tukd/transaksi/ambil_simpanan_ar', $data);
    }



    function load_ambilsimpanan()
    {

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd = $this->session->userdata('kdskpd');
        $bid = $kd_skpd;
        $dkd_skpd = substr($kd_skpd, 0, 17);
        $dbidang = substr($bid, 18, 4);
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_kas) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from tr_ambilsimpanan WHERE  kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows * from tr_ambilsimpanan WHERE  kd_skpd = '$kd_skpd' $where and no_kas not in (
                SELECT TOP $offset no_kas from tr_ambilsimpanan WHERE  kd_skpd = '$kd_skpd' $where order by no_kas) order by kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;

        foreach ($query1->result_array() as $resulte) {
            $bank = $resulte['bank'];

            $sql = $this->db->query("select count(nama) as cekk from ms_bank where kode='$bank'")->row();
            $sqlcekk = $sql->cekk;

            if ($sqlcekk == 0) {
                $nmbank = "";
            } else {
                $sql = $this->db->query("select nama from ms_bank where kode='$bank'")->row();
                $nmbank = $sql->nama;
            }

            //$kaskas = $resulte['no_kas'];
            //$sql = $this->db->query("select jns_spp from trhsp2d where no_kas='$kaskas'")->row();
            //$jns_spp = $sql->jns_spp;  

            $row[] = array(
                'id'          => $ii,
                'no_kas'      => $resulte['no_kas'],
                'no_bukti'      => $resulte['no_bukti'],
                //'tgl_kas'     => $this->tukd_model->rev_date($resulte['tgl_kas']),
                'tgl_kas'     => $resulte['tgl_kas'],
                'tgl_bukti'     => $resulte['tgl_bukti'],
                'kd_skpd'     => $resulte['kd_skpd'],
                'nilai'       => number_format($resulte['nilai']),
                'nilai2'       => $resulte['nilai'],
                'bank'        => $bank,
                'nmbank'        => $nmbank,
                'nm_rekening' => $resulte['nm_rekening'],
                'keterangan'  => $resulte['keterangan'],
                'status'    => $resulte['status_drop'],
                'jns_spp'   => '',
                'kd_bid'    => $dbidang
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function cek_status_ang()
    {
        // $kode = $this->session->userdata('kdskpd');
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang,(case when b.jns_ang='M' then 'Penetapan'
    when b.jns_ang='P1' then 'Penyempurnaan I'
    when b.jns_ang ='P2' then 'Penyempurnaan II'
    when b.jns_ang ='P3' then 'Penyempurnaan III'
    when b.jns_ang ='P4' then 'Penyempurnaan IV'
    when b.jns_ang ='P5' then 'Penyempurnaan V'
    when b.jns_ang='U1' then 'Perubahan'
    when b.jns_ang='U2' then 'Perubahan II'
    when b.jns_ang='U3' then 'Perubahan III' end)as nm_ang FROM ms_skpd a LEFT JOIN trhrka b
                ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' and 
                tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_ang' => $resulte['jns_ang'],
                'nm_ang' => $resulte['nm_ang']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function skpd_2()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where kd_skpd = '$kd_skpd' ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function config_bank_simpanan()
    {
        $lccr   = $this->input->post('q');
        $sql    = "SELECT kode, nama FROM ms_bank where upper(kode) like '%$lccr%' or upper(nama) like '%$lccr%' order by kode ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        echo json_encode($result);
    }


    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }


    function cari_ambilsimpanan()
    {

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';

        $kd_skpd  = $this->session->userdata('kdskpd');

        if ($kriteria <> '') {
            $where = "where ( upper(no_kas) like upper('%$kriteria%') ) and kd_skpd='$kd_skpd'";
        }

        $sql    = "SELECT * from tr_ambilsimpanan $where order by no_kas";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'          => $ii,
                'no_kas'      => $resulte['no_kas'],
                'tgl_kas'     => $this->tukd_model->rev_date($resulte['tgl_kas']),
                'kd_skpd'     => $resulte['kd_skpd'],
                'nilai'       => number_format($resulte['nilai']),
                'bank'        => $resulte['bank'],
                'nm_rekening' => $resulte['nm_rekening'],
                'keterangan'  => $resulte['keterangan']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function load_sisa_tunai()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');

        $query1 = $this->db->query("SELECT SUM(a.masuk) as terima,
        SUM(a.keluar) as keluar FROM (
        
        -- Saldo Awal
        SELECT '2021-01-01' AS tgl, null AS bku,
        'Saldo Awal' AS ket, sld_awal AS masuk, 0 AS keluar, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd='$kd_skpd'
        
        UNION ALL
                
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                select f.tgl_kas as tgl,f.no_kas as bku,f.keterangan as ket, f.nilai as masuk, 0 as keluar,f.kd_skpd as kode from tr_jpanjar f join tr_panjar g 
                on f.no_panjar_lalu=g.no_panjar and f.kd_skpd=g.kd_skpd where f.jns=2 and g.pay='TUNAI' UNION ALL
                select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai AS masuk,0 AS keluar,kd_skpd [kode] from trhtrmpot a 
                where kd_skpd='$kd_skpd' and (pay='' OR pay='TUNAI')and jns_spp in('1','2','3') union all
                select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, 0 as masuk,nilai as keluar,kd_skpd as kode from tr_panjar where pay='TUNAI' UNION ALL
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
                        from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                        where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TNK'
                        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd	
                UNION ALL
                SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
                        FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                        LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                        LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c
                        ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
                        and a.kd_skpd='$kd_skpd' 
                        AND a.no_bukti NOT IN(
                        select no_bukti from trhtransout 
                        where no_sp2d in 
                        (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                        and  no_kas not in
                        (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
                        GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                        and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
                        GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                UNION ALL
                select tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 AS masuk, ISNULL(total,0)  AS keluar,kd_skpd AS kode 
                        from trhtransout 
                        WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in 
                        (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                         and  no_kas not in
                        (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' 
                        GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                        and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'

                UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI' union all
                select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],0 as masuk,nilai AS keluar,a.kd_skpd [kode] from trhstrpot a 
                where a.kd_skpd='$kd_skpd' and (a.pay='' OR a.pay='TUNAI') and jns_spp in ('1','2','3')
                
                
                )a
                where kode='$kd_skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'sisa' => number_format(($resulte['terima'] - $resulte['keluar']), 2, '.', ','),
                'keluar' => number_format($resulte['keluar'], 0),
                'terima' => number_format($resulte['terima'], 0)
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sisa_bank()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');

        $query1 = $this->db->query("SELECT
			SUM(case when jns=1 then jumlah else 0 end) AS terima,
			SUM(case when jns=2 then jumlah else 0 end) AS keluar
			from (SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
			 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
			 a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
			 from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
             left join
			 (
				select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
				where e.kd_skpd='$kd_skpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
			 ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
              WHERE pay='BANK' and 
             (panjar not in ('1') or panjar is null) 
             union 
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
             join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
			UNION
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
			SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
            left join 
            (
                select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
                where e.kd_skpd='$kd_skpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
             ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd 
            where a.pay='BANK' and a.kd_skpd='$kd_skpd'                  
            union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$kd_skpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all           
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd             
			
            ) a where kode='$kd_skpd'");

        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                'sisa' => number_format(($resulte['terima'] - $resulte['keluar']), 2, '.', ',')
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }



    function simpan_ambil_simpanan()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                echo '2';
            } else {
                echo '0';
            }
        }
    }


    function update_ambilsimpanan()
    {
        $query  = $this->input->post('st_query');
        $query1 = $this->input->post('st_query1');
        $asg    = $this->db->query($query);
        $asg1   = $this->db->query($query1);
    }


    function hapus_ambilsimpanan()
    {
        $no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $query = $this->db->query("delete from tr_ambilsimpanan where no_kas='$no' and kd_skpd='$skpd' ");
        // $query->free_result();
    }

    function hapus_transout_tunai()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        if ($asg) {
            $sql = "delete from trhtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd' AND pay='TUNAI'";
            $asg = $this->db->query($sql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan' => '1');
        echo json_encode($msg);
    }


    function no_urut()
    {
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
    select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='3' union ALL
    select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
    select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE KD_SKPD = '$kd_skpd'");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }



    function transout()
    {
        $data['page_title'] = 'INPUT PEMBAYARAN TRANSAKSI TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI TUNAI');
        $this->template->load('template', 'tukd/tunai/transout_tunai', $data);
    }


    function load_transout_tunai()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $tipe       = $this->session->userdata('type');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd' ")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        } else {
            if (substr($kd_skpd, 8, 2) == '00') {
                $init_skpd = "left(a.kd_skpd,22)=left('$kd_skpd',22)";
            } else {
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }
        }

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = " AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '0' AND a.pay='TUNAI' AND $init_skpd $where ";

        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z 
        join trhlpj v on v.no_lpj = z.no_lpj
        where v.jenis=a.jns_spp and z.no_bukti = a.no_bukti and z.kd_bp_skpd = a.kd_skpd) ketlpj,
        (CASE WHEN a.tgl_bukti<'2018-01-01' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '0' AND a.pay='TUNAI'  AND $init_skpd $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '0' AND a.pay='TUNAI'  AND $init_skpd $where order by a.no_bukti)  order by a.no_bukti,kd_skpd ";

        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

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

    function pot()
    {
        $lccr   = $this->input->post('q');
        echo $this->tukd_model->pot($lccr);
    }


    function load_trskpd_sub_tunai()
    {
        $jenis = $this->input->post('jenis');
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');

        $kode = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);

        $cgiat = '';

        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');



        $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan,sum(a.nilai) as total from trdrka a INNER JOIN trskpd b ON b.kd_skpd=a.kd_skpd AND b.kd_sub_kegiatan=a.kd_sub_kegiatan AND b.jns_ang=a.jns_ang
    where a.kd_skpd='$cskpd' $cgiat  and a.jns_ang='$data' and left(kd_rek6,1)='5' AND b.status_sub_kegiatan='1' AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))
    group by  a.kd_sub_kegiatan,a.nm_sub_kegiatan
    order by  a.kd_sub_kegiatan,a.nm_sub_kegiatan
    ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

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


    function load_dtagih()
    {
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
        foreach ($query1->result_array() as $resulte) {
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



    function rek_pot()
    {
        $lccr   = $this->input->post('q');
        $sql    = " SELECT kd_rek6,nm_rek6 FROM ms_pot where ( upper(kd_rek6) like upper('%$lccr%')
                    OR upper(nm_rek6) like upper('%$lccr%') ) order by kd_rek6 ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

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

    function load_no_penagihan_trs()
    {
        $cskpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');

        $sql = "SELECT a.kd_skpd,a.no_bukti, tgl_bukti, a.ket,a.kontrak,kd_subkegiatan,SUM(b.nilai) as total 
                FROM trhtagih a INNER JOIN trdtagih b ON a.no_bukti=b.no_bukti
                WHERE a.kd_skpd='$cskpd' and a.jns_trs='2' and (upper(a.kd_skpd) like upper('%$lccr%') or  
                upper(a.no_bukti) like upper('%$lccr%')) and a.no_bukti not in
                (SELECT isnull(no_tagih,'') no_tagih from trhspp WHERE kd_skpd = '$cskpd' GROUP BY no_tagih)
                GROUP BY a.kd_skpd, a.no_bukti,tgl_bukti,a.ket,a.kontrak,kd_subkegiatan order by a.no_bukti";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_tagih' => $resulte['no_bukti'],
                'tgl_tagih' => $resulte['tgl_bukti'],
                'kd_skpd' => $resulte['kd_skpd'],
                'ket' => $resulte['ket'],
                'subkegiatan' => $resulte['kd_subkegiatan'],
                'kontrak' => $resulte['kontrak'],
                'nila' => number_format($resulte['total'], 2, '.', ','),
                'nil' => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
    }


    function no_urut_tglcms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        date_default_timezone_set("Asia/Bangkok");
        $tgl = date('Y-m-d');
        $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
        select no_tgl nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' and tgl_voucher='$tgl') z WHERE KD_SKPD = '$kd_skpd'");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sp2d_transout()
    {
        //$beban='',$giat=''
        $beban   = $this->input->post('jenis');
        $giat    = $this->input->post('giat');
        $kode    = $this->input->post('kd');
        $cari = $this->input->post('q');
        $bukti   = $this->input->post('bukti');
        $where = '';

        if (($beban != '' && $giat == '') || ($beban == '1')) {
            $where = " and a.jns_spp IN ('1','2')";
        }
        if ($giat != '' && $beban != '1') {
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
        foreach ($query1->result_array() as $resulte) {
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



    function load_reksumber_dana()
    {
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $kode   = $this->input->post('kd');
        $jnsang = $this->cek_anggaran_model->cek_anggaran($kode);
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');

        $sql = "SELECT oke.sumber as sumber_dana,SUM (nilai) AS nilai,SUM (sd) AS sd FROM 
            (
                SELECT * FROM (SELECT sumber,sum(total) as nilai, 0 as sd from trdpo where kd_sub_kegiatan = '$giat' and kd_rek6 = '$rek' and kd_skpd = '$kode' and jns_ang = '$jnsang' GROUP BY sumber, nm_sumber)z    
                UNION ALL
                SELECT sumber,nilai,sd FROM (
                SELECT c.sumber,0 AS nilai,SUM (c.nilai) AS sd FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher =d.no_voucher AND c.kd_skpd =d.kd_skpd WHERE c.kd_sub_kegiatan ='$giat' AND LEFT (d.kd_skpd,22)=LEFT ('$kode',22) AND c.kd_rek6 ='$rek' AND d.status_validasi='0' GROUP BY c.sumber UNION ALL
                SELECT c.sumber,0 AS nilai,SUM (c.nilai) AS sd FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti =d.no_bukti AND c.kd_skpd =d.kd_skpd WHERE c.kd_sub_kegiatan ='$giat' AND LEFT (d.kd_skpd,22)=LEFT ('$kode',22) AND c.kd_rek6 ='$rek'
                                    AND d.jns_spp in ('1')  GROUP BY c.sumber UNION ALL
                SELECT x.sumber,0 AS nilai,SUM (x.nilai) AS sd FROM trdspp x INNER JOIN trhspp y ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd WHERE x.kd_sub_kegiatan ='$giat' AND LEFT (x.kd_skpd,22)=LEFT ('$kode',22) AND x.kd_rek6 ='$rek' AND y.jns_spp IN ('3','4','5','6') AND (sp2d_batal IS NULL OR sp2d_batal='' OR sp2d_batal='0') GROUP BY x.sumber UNION ALL
                SELECT t.sumber,0 AS nilai,SUM (t.nilai) AS sd FROM trdtagih t INNER JOIN trhtagih u ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE t.kd_sub_kegiatan ='$giat' AND u.kd_skpd ='$kode' AND t.kd_rek ='$rek' AND u.no_bukti NOT IN (
                SELECT no_tagih FROM trhspp WHERE kd_skpd='$kode') GROUP BY t.sumber) r
                ) oke GROUP BY oke.sumber";

        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'sumber_dana' => $resulte['sumber_dana'],
                'nilaidana' => $resulte['nilai']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function load_total_trans()
    {
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');
        $beban       = $this->input->post('beban');

        if ($beban == "3") {
            $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                            select g.kd_skpd, g.kd_sub_kegiatan,sum(g.lalu) spp from(
                                            SELECT b.kd_skpd, b.kd_sub_kegiatan,
                                            (SELECT isnull(SUM(c.nilai),0) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
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
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd 
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
                                    where a.kd_sub_kegiatan='$kegiatan' and a.kd_skpd='$kdskpd'";
        } else {
            $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
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
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd 
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
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_sub_kegiatan, t.kd_skpd
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan and a.kd_skpd = z.kd_skpd
                                    where a.kd_sub_kegiatan='$kegiatan' and a.kd_skpd='$kdskpd'";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function load_sisa_pot_ls()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sp2d  = $this->input->post('sp2d');
        $query1 = $this->db->query("SELECT SUM(a.nilai) as total  FROM trspmpot a INNER JOIN trhsp2d b on b.no_spm = a.no_spm AND b.kd_skpd=a.kd_skpd
        where ((b.jns_spp = '4' AND b.jenis_beban != '1') or (b.jns_spp = '6' AND b.jenis_beban != '3'))
        and b.no_sp2d = '$sp2d' and b.kd_skpd= '$kd_skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'sisa' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_tgltransout()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        } else {
            if (substr($kd_skpd, 18, 4) == '0000') {
                $init_skpd = "left(a.kd_skpd,17)=left('$kd_skpd',17)";
            } else {
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }
        }

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND a.tgl_voucher = '$kriteria'";
        }

        $sql = "SELECT count(*) as total from trhtransout_cmsbank a where a.panjar = '0' AND $init_skpd $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,a.status_upload ketup,
        a.status_validasi ketval FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND $init_skpd $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND $init_skpd $where order by CAST (a.no_bukti as NUMERIC))  order by CAST (a.no_bukti as NUMERIC),kd_skpd ";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
        */
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

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


    function simpan_potongan()
    {
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
        $kdrekbank = $this->input->post('kdbank');
        $nmrekbank = $this->input->post('nmbank');
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

            if ($asg) {

                $sql = "insert into trhtrmpot_cmsbank(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,nilai,npwp,jns_spp,status,no_sp2d,kd_kegiatan, nm_kegiatan, kd_rek5,nm_rek5,nmrekan, pimpinan,alamat,no_voucher,rekening_tujuan,nm_rekening_tujuan,status_upload) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$npwp','$beban','0','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$nomorvou','$kdrekbank','$nmrekbank','0')";
                $asg = $this->db->query($sql);

                $sql = "update trhtransout_cmsbank set status_trmpot = '1' where kd_skpd='$skpd' and no_voucher='$nomorvou' and username='$usernm'";
                $asg = $this->db->query($sql);

                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } elseif ($tabel == 'trdtrmpot_cmsbank') {

            // Simpan Detail //                       
            $sql = "delete from trdtrmpot_cmsbank where no_bukti='$nomor' AND kd_skpd='$skpd' and username='$usernm'";
            $asg = $this->db->query($sql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into trdtrmpot_cmsbank(no_bukti,kd_rek5,nm_rek5,nilai,kd_skpd,kd_rek_trans,ebilling,username)";
                $asg = $this->db->query($sql . $csql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    //   exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }


    function load_rek_tunai()
    {
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);

        if ($rek != '') {
            $notIn = " and kd_rek6 not in ($rek) ";
        } else {
            $notIn  = "";
        }


        $field = 'nilai_ubah';


        if ($jenis == '1') {

            if ($giat == '1.01.1.01.01.00.22.002') {
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
                        0 AS sp2d,nilai AS anggaran
                        FROM trdrka a WHERE a.jns_ang='$data' AND a.kd_sub_kegiatan= '$giat' AND a.kd_rek6 in ('5221104') AND a.kd_skpd = '$kode' $notIn  ";
            } else if ($giat == '4.08.4.08.01.00.01.351') {
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
                        0 AS sp2d,nilai AS anggaran
                        FROM trdrka a WHERE a.jns_ang='$data' AND a.kd_sub_kegiatan= '$giat'  AND a.kd_skpd = '$kode' $notIn  ";
            } else {
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
                        0 AS sp2d,nilai AS anggaran
                        FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat' AND a.jns_ang='$data' 
                        AND a.kd_skpd = '$kode' $notIn  ";
            }
        } else {
            $sql = "SELECT b.kd_rek6,b.nm_rek6,
                    (SELECT SUM(c.nilai) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
                    WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
                    AND c.kd_rek6=b.kd_rek6 AND c.no_voucher <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_sub_kegiatan='$giat' $notIn ";
        }
        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek6'],
                'nm_rek5' => $resulte['nm_rek6'],
                'lalu' => $resulte['lalu'],
                'sp2d' => $resulte['sp2d'],
                'anggaran' => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }


    function realisasi_sumberdana()
    {
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $sumber    = $this->input->post('sumber');
        // echo ($sumber);

        if ($rek != '') {
            $notIn = " and kd_rek6 not in ($rek) ";
        } else {
            $notIn  = "";
        }


        $field = 'nilai_ubah';


        if ($jenis == '1') {

            if ($giat == '1.01.1.01.01.00.22.002') {
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
                        AND c.sumber='$sumber'
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
                        AND c.sumber='$sumber'
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
                        trdspp
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.sumber='$sumber' 
                        AND t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran
                        FROM trdrka a WHERE a.jns_ang='$data' AND a.kd_sub_kegiatan= '$giat' AND a.kd_rek6 in ('5221104') AND a.kd_skpd = '$kode' $notIn  ";
            } else if ($giat == '4.08.4.08.01.00.01.351') {
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
                        AND c.sumber='$sumber'
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
                        AND c.sumber='$sumber'
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
                        AND x.sumber='$sumber'
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.sumber='$sumber'
                        AND
                        t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran
                        FROM trdrka a WHERE a.jns_ang='$data' AND a.kd_sub_kegiatan= '$giat'  AND a.kd_skpd = '$kode' $notIn  ";
            } else {
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
                        AND c.sumber='$sumber'
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
                        AND c.sumber='$sumber'
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
                        AND x.sumber='$sumber'
                        UNION ALL
                        SELECT SUM(nilai) as nilai FROM trdtagih t 
                        INNER JOIN trhtagih u 
                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                        WHERE 
                        t.sumber='$sumber'
                        AND
                        t.kd_sub_kegiatan = a.kd_sub_kegiatan
                        AND u.kd_skpd = a.kd_skpd
                        AND t.kd_rek = a.kd_rek6
                        AND u.no_bukti 
                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
                        )r) AS lalu,
                        0 AS sp2d,nilai AS anggaran
                        FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat' AND a.jns_ang='$data' 
                        AND a.kd_skpd = '$kode' $notIn  ";
            }
        } else {
            $sql = "SELECT b.kd_rek6,b.nm_rek6,
                    (SELECT SUM(c.nilai) FROM trdtransout_cmsbank c LEFT JOIN trhtransout_cmsbank d ON c.no_voucher=d.no_voucher AND c.kd_skpd=d.kd_skpd 
                    WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
                    AND c.kd_rek6=b.kd_rek6 AND c.no_voucher <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d'
                    AND c.sumber='$sumber') AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
                    INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
                    INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE b.sumber='$sumber' AND d.no_sp2d = '$sp2d' and b.kd_sub_kegiatan='$giat' $notIn ";
        }
        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek6'],
                'nm_rek5' => $resulte['nm_rek6'],
                'lalu' => $resulte['lalu'],
                'sp2d' => $resulte['sp2d'],
                'anggaran' => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }




    function load_total_trans_spd()
    { /*untuk transaksi konsep kota*/
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');
        $rek         = $this->input->post('kdrek6');
        $sp2d        = $this->input->post('sp2d');
        $spp = "";
        $org         = substr($kdskpd, 0, 17);
        $beban       = $this->input->post('beban');
        $sumber      = $this->input->post('sumber');

        if ($beban == '1') {
            $sql = "SELECT SUM(nilai) total FROM 
            (
            -- transaksi UP/GU
            SELECT SUM (isnull(c.nilai,0)) as nilai
            FROM trdtransout c
            LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
            AND c.kd_skpd = d.kd_skpd
            WHERE c.kd_sub_kegiatan = '$kegiatan'
            AND d.kd_skpd = '$kdskpd'
            AND c.kd_rek6 = '$rek'
            AND d.jns_spp in ('1') 
            AND c.sumber='$sumber'
            
            UNION ALL
            -- transaksi UP/GU CMS BANK Belum Validasi
            SELECT SUM (isnull(c.nilai,0)) as nilai
            FROM trdtransout_cmsbank c
            LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
            AND c.kd_skpd = d.kd_skpd
            WHERE c.kd_sub_kegiatan ='$kegiatan'
            AND d.kd_skpd = '$kdskpd'
            AND c.kd_rek6='$rek'
            AND d.jns_spp in ('1') 
            AND (d.status_validasi='0' OR d.status_validasi is null)
            AND c.sumber='$sumber'
            
            UNION ALL
            -- transaksi SPP SELAIN UP/GU
            SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
            INNER JOIN trhspp y 
            ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
            WHERE x.kd_sub_kegiatan = '$kegiatan'
            AND x.kd_skpd = '$kdskpd'
            AND x.kd_rek6 = '$rek'
            AND y.jns_spp IN ('3','4','5','6')
            AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
            AND x.sumber='$sumber' 
            
            UNION ALL
            -- Penagihan yang belum jadi SPP
            SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
            INNER JOIN trhtagih u 
            ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
            WHERE t.kd_sub_kegiatan ='$kegiatan' 
            AND t.kd_rek ='$rek' 
            AND u.kd_skpd = '$kdskpd' 
            AND t.sumber='$sumber'
            AND u.no_bukti 
            NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
            )r";
        } else {
            // $spp         = $this->tukd_model->get_nama($this->input->post('nosp2d'),'no_spp','trhsp2d','no_sp2d');
            $sqlsc = "SELECT no_spp from trhsp2d where no_sp2d='$sp2d'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $spp     = $rowsc->no_spp;
            }

            $sql = "SELECT SUM(nilai) total FROM 
                                    (
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6 = '$rek'
                                    AND d.jns_spp in ('1') 
                                    AND c.sumber='$sumber'
                                    
                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6='$rek'
                                    AND c.sumber='$sumber'
                                    AND d.jns_spp in ('1') 
                                    AND (d.status_validasi='0' OR d.status_validasi is null)
                                    
                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y 
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = '$kegiatan'
                                    AND x.kd_skpd = '$kdskpd'
                                    AND x.kd_rek6 = '$rek'
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND y.no_spp<>'$spp'
                                    AND x.sumber='$sumber'
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
                                    
                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND t.kd_rek ='$rek' 
                                    AND u.kd_skpd = '$kdskpd' 
                                    AND t.sumber='$sumber'
                                    AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
                                    )r";
        }



        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ','),
                'totals' => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');
        if ($field2 == '') {
            $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else {
            $hasil = $this->db->query(" select count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor' ");
        }
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        }
    }


    function simpan_transout_tunai()
    {
        $tabel    = $this->input->post('tabel');
        $nomor    = $this->input->post('no');
        $nomor_tgl = $this->input->post('notgl');
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

            if ($asg) {
                $sql = "insert into trhtransout (no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d')";
                $asg = $this->db->query($sql);
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } elseif ($tabel == 'trdtransout') {
            // Simpan Detail //                                       

            $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into trdtransout (no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber)";
                $asg = $this->db->query($sql . $csql);

                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    //   exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }


    function load_dtransout_tunai()
    {
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
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_bukti'    => $resulte['no_bukti'],
                'no_sp2d'       => $resulte['no_sp2d'],
                'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek5'       => $resulte['kd_rek6'],
                'nm_rek5'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai'],
                'nilai_nformat' => number_format($resulte['nilai'], 2),
                'sumber'        => $resulte['sumber'],
                'lalu'          => $resulte['lalu'],
                'sp2d'          => $resulte['sp2d'],
                'anggaran'      => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
    }


    function load_dpot()
    {
        $nomor = $this->input->post('no');
        $kd_skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT * from trdtrmpot where no_bukti='$nomor' and kd_skpd ='$kd_skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
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


    function config_npwp()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT npwp,rekening FROM ms_skpd a WHERE a.kd_skpd='$skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'npwp' => $resulte['npwp'],
                'rekening' => $resulte['rekening']
            );
        }
        echo json_encode($result);
    }

    function perusahaan()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kd_skpdd = substr($kd_skpd, 0, 17);
        $sql = "
                SELECT z.* FROM (
                SELECT nama as nmrekan, pimpinan, npwp, alamat FROM ms_perusahaan WHERE left(kd_skpd,17) = '$kd_skpdd'   
                    AND UPPER(nama) LIKE UPPER('%$lccr%')
                    GROUP BY nama, pimpinan, npwp, alamat
                UNION ALL       
                SELECT nmrekan, pimpinan, npwp, alamat FROM trhspp WHERE LEN(nmrekan)>1 AND left(kd_skpd,17) = '$kd_skpdd'   
                    AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
                    GROUP BY nmrekan, pimpinan, npwp, alamat
                UNION ALL
                SELECT nmrekan, pimpinan, npwp, alamat FROM trhtrmpot WHERE LEN(nmrekan)>1 AND left(kd_skpd,17) = '$kd_skpdd'   
                    AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
                    GROUP BY nmrekan, pimpinan, npwp, alamat
                UNION ALL
                SELECT nmrekan, pimpinan, npwp, alamat FROM trhtrmpot_cmsbank WHERE LEN(nmrekan)>1 AND kd_skpd = '$kd_skpd'   
                    AND UPPER(nmrekan) LIKE UPPER('%$lccr%')
                    GROUP BY nmrekan, pimpinan, npwp, alamat
               )z GROUP BY z.nmrekan, z.pimpinan, z.npwp, z.alamat
                ORDER BY z.nmrekan, z.pimpinan, z.npwp, z.alamat     
                    ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'nmrekan' => $resulte['nmrekan'],
                'pimpinan' => $resulte['pimpinan'],
                'npwp' => $resulte['npwp'],
                'alamat' => $resulte['alamat'],
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function rek_pots()
    {
        $lccr   = $this->input->post('q');
        $sql    = " SELECT * from (SELECT '1' urut, kd_rek6 kd_rek6, nm_rek6 nm_rek6 FROM ms_pot where map_pot<>'' 
            union all
            select '2' urut,kd_rek6, nm_rek6 from ms_rek6 where left(kd_rek6,1)=2 and kd_rek6 not in(select map_pot from ms_pot) ) okeii where 
          ( upper(kd_rek6) like upper('%$lccr%')
                    OR upper(nm_rek6) like upper('%$lccr%') ) order by urut, kd_rek6 ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],

            );
            $ii++;
        }

        echo json_encode($result);
    }


    /////////////////////////
}
