<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class cetak_tukd extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function bku()
    {
        $skpd = $this->session->userdata('kdskpd');
        $cek = substr($skpd, 18, 4);
        $data['page_title'] = 'BKU';
        $this->template->set('title', 'BKU');
        if ($cek == "0000") {
            $this->template->load('template', 'tukd/transaksi/bku', $data);
        } else {
            $this->template->load('template', 'tukd/transaksi/bku_bpp', $data);
        }
    }

    function bku_global()
    {
        $data['page_title'] = 'BKU REKAP';
        $this->template->set('title', 'BKU REKAP');
        $this->template->load('template', 'tukd/transaksi/bku_global', $data);
    }

    // KAS_AHIR_BULAN
    function cetak_kas_akhir_unit($lcskpd = '', $nbulan = '', $ctk = '')
    {
        $spasi = $this->uri->segment(9);
        $nomor = str_replace('123456789', ' ', $this->uri->segment(6));
        $nip2 = str_replace('123456789', ' ', $this->uri->segment(7));
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(8));
        $skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        //$this->db->query("exec recall '$lcskpd'");
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$lcskpd' AND kode='PA'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip = '$nomor' AND kd_skpd='$lcskpd' AND kode='BK'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD align="center" ><b>' . $prov . ' </TD>
					</TR>
					<tr></tr>
                    <TR>
						<TD align="center" ><b>LAPORAN PENUTUPAN KAS BULANAN <br> BULAN ' . strtoupper($this->tukd_model->getBulan($nbulan)) . ' TAHUN ' . strtoupper($thn) . '</TD>
					</TR>
					</TABLE><br/>';

        $csql3 = "SELECT SUM(z.terima) AS terima,SUM(z.keluar) AS keluar , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
            '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
            UNION ALL
            SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
            b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
            trdrekal b INNER JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
            month(z.tgl_kas) < '$nbulan' and year(z.tgl_kas) = $thn AND z.kd_skpd = '$lcskpd'";
        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+isnull(sld_awal_bank,0)+sld_awalpajak) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');
        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();
        $saldoawal = $trh4->sel;
        $saldoawal = $saldoawal + $tox;



        $csql4 = "SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
            '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
            where month(a.tgl_kas) = '$nbulan' AND
            year(a.tgl_kas) = '$thn'and kd_skpd='$lcskpd')
              UNION ALL
             ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
              b.nm_rek6 AS uraian, 
              CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
              CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
              case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
              trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$nbulan' AND
              year(a.tgl_kas) = '$thn' and b.kd_skpd='$lcskpd'))z
              ORDER BY tgl_kas,kd_skpd,cast (no_kas as int),jns_trans,st,rekening";

        $hasil4 = $this->db->query($csql4);
        $lcxkeluar = 0;
        $lcxterima = 0;
        foreach ($hasil4->result() as $row) {
            $lcxkeluar = $lcxkeluar + $row->keluar;
            $lcxterima = $lcxterima + $row->terima;
        }

        $saldo_akhir = $saldoawal + $lcxterima - $lcxkeluar;
        $asql = "SELECT
			SUM(case when jns=1 then jumlah else 0 end) AS terima,
			SUM(case when jns=2 then jumlah else 0 end) AS keluar
			from (
			SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT '2022-01-01' AS tgl, null AS bku,
				'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
                UNION ALL
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='1' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
			select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] 
			from trhtrmpot a join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
            where a.kd_skpd='$lcskpd' and pay='BANK' group by  a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd  union all         
			select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
			union all
            SELECT tgl_kas AS tgl, no_kas AS bku, keterangan AS ket, nilai AS jumlah, '2' AS jns, kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
			select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE jns='1' and kd_skpd='$lcskpd' AND pay='BANK' union all
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
			join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) 
			c on b.no_spm=c.no_spm WHERE pay='BANK' union all 
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
            union all
			select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
            union all			
            select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a join 
            trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
            where a.kd_skpd='$lcskpd' and pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd					
					
			) a
			where month(tgl)<='$nbulan' and kode='$lcskpd'";

        //echo $asql;	
        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;


        $xterima_lalu = 0;
        $xkeluar_lalu = 0;
        $xhasil_lalu = 0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
        foreach ($sk_lalu->result() as $rowxll) {
            $xskpd = $rowxll->kd_skpd;

            $sqlitull = "kas_tunai_lalu '$xskpd','$nbulan'";

            $sqlituull = $this->db->query($sqlitull);
            $sqlituql = $sqlituull->row();
            $xterima_lalu = $xterima_lalu + $sqlituql->terima;
            $xkeluar_lalu = $xkeluar_lalu + $sqlituql->keluar;
        }
        $xhasil_lalu = ($xterima_lalu - $xkeluar_lalu);

        $xterima = 0;
        $xkeluar = 0;
        $xhasil_tunai = 0;
        $sk = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
        foreach ($sk->result() as $rowx) {
            $xskpd = $rowx->kd_skpd;

            $sqlitu = "kas_tunai '$xskpd','$nbulan'";

            $sqlituu = $this->db->query($sqlitu);
            $sqlituq = $sqlituu->row();
            $xterima = $xterima + $sqlituq->terima;
            $xkeluar = $xkeluar + $sqlituq->keluar;
        }
        $xhasil_tunai = ($xterima - $xkeluar) + $xhasil_lalu;


        $csql = "select sum(nilai) as total from trhsp2d where month(tgl_terima)='$nbulan' and kd_skpd = '$lcskpd' 
					and status_terima = '1' and month(tgl_kas) > '$nbulan'";
        $hasil_srt = $this->db->query($csql);
        $saldoberharga = $hasil_srt->row('total');

        $cRet .= "<TABLE width=\"100%\" style=\"font-size:12px\">
					 <TR>
						<TD align=\"left\" width=\"20%\" >Kepada Yth. <br> 
						Di tempat</TD>
					 </TR>
					 <TR>
						<TD align=\"left\">Dengan memperhatikan Peraturan Bupati Melawi No. 94 Tahun 2021 Tentang mekanisme pembayaran dan pertarnggungjawaban
						Penggunaan Dana Atas Beban APBD Kabupaten Melawi, Bersama ini kami sampaikan Laporan Kas Bulanan yang terdapat di Bendahara Pengeluaran 
						OPD $skpd adalah sejumlah Rp." . number_format($saldo_akhir, '2', ',', '.') . " (" . ucwords($this->tukd_model->terbilang($saldo_akhir)) . ") dengan rincian sebagai berikut:</TD>
					 </TR>
					 </TABLE>";
        $cRet .= "<TABLE width=\"100%\" style=\"font-size:12px\">
					 <TR>
						<TD align=\"left\" width=\"5%\" >A.</TD>
						<TD align=\"left\" width=\"60%\" >Kas Bendahara Pengeluaran : </TD>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
						</TR>
					 <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >A.1   Saldo Awal </TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format($saldoawal, '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					 <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >A.2   Jumlah Penerimaan </TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format($lcxterima, '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					  <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >A.3   Jumlah Pengeluaran </TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format($lcxkeluar, '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					  <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >A.4   Saldo Akhir Bulan </TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format($saldo_akhir, '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					 <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD colspan=\"4\" align=\"left\" width=\"40%\" ><br>
						Saldo Akhir Bulan Tanggal: $tanggal_ttd  Terdiri dari Saldo Kas Tunai sebesar Rp " . number_format($xhasil_tunai, '2', ',', '.') . " <br>
						Saldo di Bank sebesar Rp " . number_format($saldobank, '2', ',', '.') . " dan Saldo Surat Berharga sebesar Rp " . number_format($saldoberharga, '2', ',', '.') . " 
						<br></TD>
					 </TR>
					 </TABLE>";

        $cRet .= "<TABLE width=\"100%\" style=\"font-size:12px\">
			<TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >&nbsp; </TD>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
						</TR>
					 <TR>
					 <TR>
						<TD align=\"left\" width=\"5%\" >B.</TD>
						<TD align=\"left\" width=\"60%\" >Rekapitulasi Posisi Kas di Bendahara Pengeluaran : </TD>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
						</TR>
					 <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >B.1 Saldo di Kas Tunai </TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format($xhasil_tunai, '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					 <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >B.2 Saldo di Bank</TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format($saldobank, '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					  <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >B.3 Saldo Surat Berharga</TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format($saldoberharga, '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					  <TR>
						<TD align=\"left\" width=\"5%\" >&nbsp;</TD>
						<TD align=\"left\" width=\"60%\" >B.4   Saldo Total </TD>
						<TD align=\"left\" width=\"5%\" >Rp.</TD>
						<TD align=\"right\" width=\"15%\" >" . number_format(($xhasil_tunai + $saldobank + $saldoberharga), '2', ',', '.') . "</TD>
						<TD align=\"left\" width=\"15%\" >&nbsp;</TD>
					 </TR>
					 
					 </TABLE>";


        $cRet .= '<TABLE width="100%" style="font-size:12px" border="0">
					<TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >Melawi, ' . $tanggal_ttd . '</TD>
					</TR>
                    <TR>
						<TD align="center" >&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $jabatan1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><u><b>' . $nama1 . '<b></u><br>' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" >&nbsp;</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';

        $data['prev'] = 'DTH';
        switch ($ctk) {
            case 0;
                echo ("<title>LAPORAN KAS AKHIR BULAN</title>");
                echo $cRet;
                break;
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 0, '0');
                break;
        }
    }
    // END

    // konsep kota
    function cetak_kas_akhir_unitaa($lcskpd = '', $nbulan = '', $ctk = '')
    {
        $spasi = $this->uri->segment(9);
        $nomor = str_replace('123456789', ' ', $this->uri->segment(6));
        $nip2 = str_replace('123456789', ' ', $this->uri->segment(7));
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(8));
        $skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        //$this->db->query("exec recall '$lcskpd'");
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$lcskpd' AND kode='PA'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip = '$nomor' AND kd_skpd='$lcskpd' AND kode='BK'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $cRet = '<TABLE style="border-collapse:collapse;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                <TR>
                    <TD align="center" ><b>' . $prov . ' </TD>
                </TR>
                <tr></tr>
                <TR>
                    <TD align="center" ><b>LAPORAN PENUTUPAN KAS BULANAN <br> BULAN ' . strtoupper($this->tukd_model->getBulan($nbulan)) . ' TAHUN ' . strtoupper($thn) . '</TD>
                </TR>
                </TABLE><br/>';

        $csql3 = "SELECT SUM(z.terima) AS terima,SUM(z.keluar) AS keluar , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
        '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
        UNION ALL
        SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
        b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
        trdrekal b INNER JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
        month(z.tgl_kas) < '$nbulan' and year(z.tgl_kas) = $thn AND z.kd_skpd = '$lcskpd'";
        $tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)+sld_awalpajak) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');
        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();
        $saldoawal = $trh4->sel;
        $saldoawal = $saldoawal + $tox;



        // $csql4 = "SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
        // '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
        // where month(a.tgl_kas) = '$nbulan' AND
        // year(a.tgl_kas) = '$thn'and kd_skpd='$lcskpd')
        //   UNION ALL
        //  ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
        //   b.nm_rek6 AS uraian, 
        //   CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
        //   CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
        //   case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
        //   trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$nbulan' AND
        //   year(a.tgl_kas) = '$thn' and b.kd_skpd='$lcskpd'))z
        //   ORDER BY tgl_kas,kd_skpd,cast (no_kas as int),jns_trans,st,rekening";

        // $csql4="SELECT sum(terima)as terima,sum(keluar)as keluar,sum(terima)-sum(keluar) as sisa from trdrekal a INNER JOIN trhrekal b on a.no_kas=b.no_kas 
        // and a.kd_skpd=b.kd_skpd where a.kd_skpd='$lcskpd' and MONTH(tgl_kas)='$nbulan' AND year(tgl_kas)='$thn'";

        // $csql4="SELECT 
        // SUM(CASE WHEN jns=1 THEN nilai ELSE 0 END) as terima,
        // SUM(CASE WHEN jns=2 THEN nilai ELSE 0 END) as keluar
        // FROM (
        // -- Penerimaan + Pajak
        // SELECT '1' as jns, SUM(a.nilai) as nilai FROM trdspp a INNER JOIN trhspp b ON b.kd_skpd=a.kd_skpd AND a.no_spp=b.no_spp INNER JOIN trhspm c ON c.kd_skpd=b.kd_skpd AND c.no_spp=b.no_spp AND c.jns_spp=b.jns_spp INNER JOIN trhsp2d d ON d.kd_skpd=c.kd_skpd AND d.no_spm=c.no_spm WHERE d.kd_skpd='$lcskpd' AND MONTH(d.tgl_kas)<='$nbulan'
        // UNION
        // SELECT '1' as jns, SUM(a.nilai) as nilai FROM trdtrmpot a INNER JOIN trhtrmpot b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.kd_skpd='$lcskpd' AND MONTH(b.tgl_bukti)<='$nbulan'
        // UNION
        // SELECT '1' as jns, SUM(nilai) as nilai FROM
        //         tr_panjar 
        //     WHERE MONTH(tgl_panjar)<='$nbulan' AND YEAR(tgl_panjar)='$thn' AND
        //         kd_skpd = '$lcskpd' 
        //     AND jns = '1'
        // UNION
        // SELECT '1' as jns,ISNULL(SUM(nilai),0) as nilai FROM TRHINLAIN WHERE KD_SKPD='$lcskpd' AND MONTH(TGL_BUKTI)<='$nbulan'
        //     UNION

        // -- 	Pengeluaran + Pajak
        // SELECT '2' as jns, SUM(a.nilai) as nilai FROM trdtransout a INNER JOIN trhtransout b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.jns_spp IN(1,2,3,4,5,6) AND b.kd_skpd='$lcskpd' AND MONTH(b.tgl_bukti)<='$nbulan'
        // UNION
        // SELECT '2' as jns, ISNULL(SUM(a.rupiah*-1),0) as nilai FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON b.kd_skpd=a.kd_skpd AND b.no_sts=a.no_sts WHERE b.kd_skpd='$lcskpd' AND b.jns_cp IN(1,2,3) AND MONTH(b.tgl_sts)<='$nbulan'
        // UNION
        // SELECT '2' as jns, ISNULL(SUM(a.nilai*-1),0) FROM trdinlain a INNER JOIN TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)<='$nbulan' AND b.kd_skpd='$lcskpd' AND b.pengurang_belanja=1 
        // UNION
        // SELECT '2' as jns, ISNULL(SUM(a.nilai),0) FROM  trdstrpot a INNER JOIN trhstrpot b on b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.jns_spp IN(1,2,3,4,5,6) AND MONTH(b.tgl_bukti)<='$nbulan' AND b.kd_skpd='$lcskpd'
        // UNION 
        // SELECT '2' as jns,ISNULL(SUM(nilai),0) from tr_setorpelimpahan_bank WHERE kd_skpd_sumber='$lcskpd' AND MONTH(tgl_kas)<='$nbulan'
        // UNION
        // SELECT '2' as jns,ISNULL(SUM(nilai),0) from tr_setorpelimpahan WHERE kd_skpd_sumber='$lcskpd' AND MONTH(tgl_kas)<='$nbulan'
        // UNION
        // SELECT '2' as jns, SUM(nilai) as nilai FROM
        //         tr_panjar 
        //     WHERE MONTH(tgl_panjar)='$nbulan' AND YEAR(tgl_panjar)='$thn' AND
        //         kd_skpd = '$lcskpd' 
        //     AND jns = '1' 
        //     )a";

        $csql4 = "SELECT SUM(CASE WHEN jns=1 THEN nilai ELSE 0 END) as terima, SUM(CASE WHEN jns=2 THEN nilai ELSE 0 END) as keluar FROM ( 
            -- SALDO 
            SELECT '1' as jns, SUM(ISNULL(sld_awal,0)+ISNULL(sld_awal_bank,0)) as nilai FROM ms_skpd WHERE kd_skpd='$lcskpd'
            UNION
            SELECT '1' as jns, SUM(nilai*-1) as nilai FROM TRHOUTLAIN WHERE MONTH(TGL_BUKTI)<='$nbulan' AND KD_SKPD='$lcskpd'
            UNION
            -- Penerimaan + Pajak 
            SELECT '1' as jns, SUM(a.nilai) as nilai FROM trdspp a INNER JOIN trhspp b ON b.kd_skpd=a.kd_skpd AND a.no_spp=b.no_spp INNER JOIN trhspm c ON c.kd_skpd=b.kd_skpd AND c.no_spp=b.no_spp AND c.jns_spp=b.jns_spp INNER JOIN trhsp2d d ON d.kd_skpd=c.kd_skpd AND d.no_spm=c.no_spm WHERE d.kd_skpd='$lcskpd' AND MONTH(d.tgl_kas)<='$nbulan' 
            UNION
            SELECT '1' as jns, SUM(a.nilai) as nilai FROM trdtrmpot a INNER JOIN trhtrmpot b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.kd_skpd='$lcskpd' AND MONTH(b.tgl_bukti)<='$nbulan' 
            UNION 
            SELECT '1' as jns, SUM(nilai) as nilai FROM tr_panjar WHERE MONTH(tgl_panjar)<='$nbulan' AND YEAR(tgl_panjar)='$thn' AND kd_skpd = '$lcskpd' AND jns = '1' 
            UNION 
            SELECT '1' as jns, ISNULL(SUM(a.rupiah),0) as nilai FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON b.kd_skpd=a.kd_skpd AND b.no_sts=a.no_sts WHERE b.kd_skpd='$lcskpd' AND b.jns_cp IN(1,2,3) AND MONTH(b.tgl_sts)<='$nbulan' 
            UNION
            SELECT '1' as jns,ISNULL(SUM(nilai),0) as nilai FROM TRHINLAIN WHERE KD_SKPD='$lcskpd' AND MONTH(TGL_BUKTI)<='$nbulan' 
            UNION 
            -- UYHD
            SELECT '1' as jns,ISNULL(SUM(nilai),0) as nilai FROM TRHOUTLAIN WHERE KD_SKPD='$lcskpd' AND MONTH(TGL_BUKTI)<='$nbulan' AND thnlalu=1
            -- 
            UNION
            -- Pengeluaran + Pajak 
            SELECT '2' as jns, SUM(a.nilai) as nilai FROM trdtransout a INNER JOIN trhtransout b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.jns_spp IN(1,2,3,4,5,6) AND b.kd_skpd='$lcskpd' AND MONTH(b.tgl_bukti)<='$nbulan' 
            UNION 
            SELECT '2' as jns, ISNULL(SUM(a.rupiah*-1),0) as nilai FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON b.kd_skpd=a.kd_skpd AND b.no_sts=a.no_sts WHERE b.kd_skpd='$lcskpd' AND b.jns_cp IN(1,2,3) AND MONTH(b.tgl_sts)<='$nbulan' 
            UNION 
            SELECT '2' as jns, ISNULL(SUM(a.nilai*-1),0) FROM trdinlain a INNER JOIN TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)<='$nbulan' AND b.kd_skpd='$lcskpd' AND b.pengurang_belanja=1 
            UNION
            -- UYHD
            SELECT '1' as jns,ISNULL(SUM(nilai),0) as nilai FROM TRHOUTLAIN WHERE KD_SKPD='$lcskpd' AND MONTH(TGL_BUKTI)<='$nbulan' AND thnlalu=1
            -- END
            UNION 
            SELECT '2' as jns, ISNULL(SUM(a.nilai),0) FROM trdstrpot a INNER JOIN trhstrpot b on b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.jns_spp IN(1,2,3,4,5,6) AND MONTH(b.tgl_bukti)<='$nbulan' AND b.kd_skpd='$lcskpd' 
            UNION 
            SELECT '2' as jns,ISNULL(SUM(nilai),0) from tr_setorpelimpahan_bank WHERE kd_skpd_sumber='$lcskpd' AND MONTH(tgl_kas)<='$nbulan' 
            UNION 
            SELECT '2' as jns,ISNULL(SUM(nilai),0) from tr_setorpelimpahan WHERE kd_skpd_sumber='$lcskpd' AND MONTH(tgl_kas)<='$nbulan' 
            UNION 
            SELECT '2' as jns, SUM(nilai) as nilai FROM tr_panjar WHERE MONTH(tgl_panjar)='$nbulan' AND YEAR(tgl_panjar)='$thn' AND kd_skpd = '$lcskpd' AND jns = '2' )a";

        $hasil4 = $this->db->query($csql4);
        $lcxkeluar = 0;
        $lcxterima = 0;
        foreach ($hasil4->result() as $row) {
            $lcxkeluar = $row->keluar;
            $lcxterima = $row->terima;
        }

        $saldo_akhir = $lcxterima - $lcxkeluar;
        $asql = "SELECT
        SUM(case when jns=1 then jumlah else 0 end) AS terima,
        SUM(case when jns=2 then jumlah else 0 end) AS keluar
        from (
        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
        select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
        c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='1' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
        select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] 
        from trhtrmpot a join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
        where a.kd_skpd='$lcskpd' and pay='BANK' group by  a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd  union all         
        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
        from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
        where jns_trans NOT IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd'
        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
        union all
        SELECT tgl_kas AS tgl, no_kas AS bku, keterangan AS ket, nilai AS jumlah, '2' AS jns, kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank UNION ALL
        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union ALL
        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
        select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE jns='1' and kd_skpd='$lcskpd' AND pay='BANK' union all
        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
        join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) 
        c on b.no_spm=c.no_spm WHERE pay='BANK' union all 
        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
        from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
        where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
        union all
        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
        from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
        where jns_trans NOT IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd'
        GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
        union all			
        select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a join 
        trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
        where a.kd_skpd='$lcskpd' and pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd					
                
        ) a
        where month(tgl)<='$nbulan' and kode='$lcskpd'";

        //echo $asql;	
        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;


        $xterima_lalu = 0;
        $xkeluar_lalu = 0;
        $xhasil_lalu = 0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
        foreach ($sk_lalu->result() as $rowxll) {
            $xskpd = $rowxll->kd_skpd;

            $sqlitull = "kas_tunai_lalu '$xskpd','$nbulan'";

            $sqlituull = $this->db->query($sqlitull);
            $sqlituql = $sqlituull->row();
            $xterima_lalu = $xterima_lalu + $sqlituql->terima;
            $xkeluar_lalu = $xkeluar_lalu + $sqlituql->keluar;
        }
        $xhasil_lalu = ($xterima_lalu - $xkeluar_lalu);

        $xterima = 0;
        $xkeluar = 0;
        $xhasil_tunai = 0;
        $sk = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
        foreach ($sk->result() as $rowx) {
            $xskpd = $rowx->kd_skpd;

            $sqlitu = "kas_tunai '$xskpd','$nbulan'";

            $sqlituu = $this->db->query($sqlitu);
            $sqlituq = $sqlituu->row();
            $xterima = $xterima + $sqlituq->terima;
            $xkeluar = $xkeluar + $sqlituq->keluar;
        }
        $xhasil_tunai = ($xterima - $xkeluar) + $xhasil_lalu;


        $csql = "select sum(nilai) as total from trhsp2d where month(tgl_terima)='$nbulan' and kd_skpd = '$lcskpd' 
                and status_terima = '1' and month(tgl_kas) > '$nbulan'";
        $hasil_srt = $this->db->query($csql);
        $saldoberharga = $hasil_srt->row('total');

        $cRet .= "<TABLE width=\"100%\" style=\"font-size:12px\">
                 <TR>
                    <TD align=\"left\" width=\"20%\" >Kepada Yth. <br> 
                    Di tempat</TD>
                 </TR>
                 <TR>
                    <TD align=\"left\">Dengan memperhatikan Peraturan Bupati Kabupaten Melawi No... Tahun 2022 Tentang mekanisme pembayaran dan pertarnggungjawaban
                    Penggunaan Dana Atas Beban APBD Kabupaten Melawi, Bersama ini kami sampaikan Laporan Kas Bulanan yang terdapat di Bendahara Pengeluaran 
                    OPD $skpd adalah sejumlah Rp." . number_format($saldo_akhir, '2', ',', '.') . " (" . ucwords($this->tukd_model->terbilang($saldo_akhir)) . ") dengan rincian sebagai berikut:</TD>
                 </TR>
                 </TABLE>";
        $cRet .= "<TABLE width=\"100%\" style=\"font-size:12px\">
                 <TR>
                    <TD align=\"left\" width=\"5%\" >A.</TD>
                    <TD align=\"left\" width=\"60%\" >Kas Bendahara Pengeluaran : </TD>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                    </TR>
                 <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >A.1   Saldo Awal </TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format($saldoawal, '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                 <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >A.2   Jumlah Penerimaan </TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format($lcxterima, '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                  <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >A.3   Jumlah Pengeluaran </TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format($lcxkeluar, '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                  <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >A.4   Saldo Akhir Bulan </TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format($saldo_akhir, '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                 <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD colspan=\"4\" align=\"left\" width=\"40%\" ><br>
                    Saldo Akhir Bulan Tanggal: $tanggal_ttd  Terdiri dari Saldo Kas Tunai sebesar Rp " . number_format($xhasil_tunai, '2', ',', '.') . " <br>
                    Saldo di Bank sebesar Rp " . number_format($saldobank, '2', ',', '.') . " dan Saldo Surat Berharga sebesar Rp " . number_format($saldoberharga, '2', ',', '.') . " 
                    <br></TD>
                 </TR>
                 </TABLE>";

        $cRet .= "<TABLE width=\"100%\" style=\"font-size:12px\">
        <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >&nbsp; </TD>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                    </TR>
                 <TR>
                 <TR>
                    <TD align=\"left\" width=\"5%\" >B.</TD>
                    <TD align=\"left\" width=\"60%\" >Rekapitulasi Posisi Kas di Bendahara Pengeluaran : </TD>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                    </TR>
                 <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >B.1 Saldo di Kas Tunai </TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format($xhasil_tunai, '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                 <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >B.2 Saldo di Bank </TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format($saldobank, '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                  <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >B.3 Saldo Surat Berharga</TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format($saldoberharga, '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                  <TR>
                    <TD align=\"left\" width=\"5%\" >&nbsp;</TD>
                    <TD align=\"left\" width=\"60%\" >B.4   Saldo Total </TD>
                    <TD align=\"left\" width=\"5%\" >Rp.</TD>
                    <TD align=\"right\" width=\"15%\" >" . number_format(($xhasil_tunai + $saldobank + $saldoberharga), '2', ',', '.') . "</TD>
                    <TD align=\"left\" width=\"15%\" >&nbsp;</TD>
                 </TR>
                 
                 </TABLE>";


        $cRet .= '<TABLE width="100%" style="font-size:12px" border="0">
                <TR>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $tanggal_ttd . '</TD>
                </TR>
                <TR>
                    <TD align="center" >&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><u><b>' . $nama1 . '<b></u><br>' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';

        $data['prev'] = 'DTH';
        switch ($ctk) {
            case 0;
                echo ("<title>LAPORAN KAS AKHIR BULAN</title>");
                echo $cRet;
                break;
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 0, '0');
                break;
        }
    }

    // -----------------------------

    function cetak_bku_skpd_77()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);

        $this->db->query("recall_skpd '$lcskpd'");
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
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
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
             month(z.tgl_kas) <= '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        }

        $tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();

        $saldoawal = $trh4->sel;
        $saldoawal = $saldoawal + $tox;
        $lcskpdd = substr($lcskpd, 0, 17);
        $lcskpdd = $lcskpdd . ".0000";
        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpdd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        // QUERY SALDO BANK
        if ($pilih == 1) {
    //         $asql = "SELECT terima-keluar as sisa FROM(select
    //   SUM(case when jns=1 then jumlah else 0 end) AS terima,
    //   SUM(case when jns=2 then jumlah else 0 end) AS keluar
    //   from (
    //   SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
    //   SELECT '2022-01-01' AS tgl, null AS bku,
	//     'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
    //             union
    //   SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM TRHINLAIN WHERE pay='BANK' union
    //         select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
    //         c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
    //          select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
    //          join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
    //          where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
    //          union all
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
    //    SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
    //    a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
    //    from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
    //          left join
    //    (
    //     select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
    //     -- inner join trhstrpot g on d.no_bukti=g.no_terima and d.kd_skpd=g.kd_skpd 
    //     where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
    //    ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
    //           WHERE pay='BANK' and 
    //          (panjar not in ('1') or panjar is null) 
    //          union 
    //          select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
    //          join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
    //          where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
    //   UNION
    //         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union
    //   SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
    //   SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

    //         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

    //   SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
    //         left join 
    //         (
    //             select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
    //             where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
    //          ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd 
    //         where a.pay='BANK' and a.kd_skpd='$lcskpd'                  
    //         union all
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all           
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
    //         ) a
    //         where tgl<='$lctgl2' and kode='$lcskpd') a 
    //         ";

    $asql="SELECT terima-keluar as sisa FROM(select
    SUM(case when jns=1 then jumlah else 0 end) AS terima,
    SUM(case when jns=2 then jumlah else 0 end) AS keluar
    from (
    SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
    SELECT '2022-01-01' AS tgl, null AS bku,
      'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
        union
SELECT TGL_BUKTI AS tgl,NO_BUKTI AS bku,KET as ket, nilai AS jumlah,'1' AS jns_beban, KD_SKPD AS kode FROM TRHINLAIN WHERE KD_SKPD='$lcskpd' 
          union 
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
          where tgl<='$lctgl2' and kode='$lcskpd') a ";
          
            //QUERY SALDO TUNAI
        } else {
    //         $asql = "SELECT terima-keluar as sisa FROM(select
    //         SUM(case when jns=1 then jumlah else 0 end) AS terima,
    //         SUM(case when jns=2 then jumlah else 0 end) AS keluar
    //         from (

    //             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
    //             SELECT '2022-01-01' AS tgl, null AS bku,
	//     'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
    //     union 
    //             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
    //         select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
    //         c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
    //          -- select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
    //          -- join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
    //          -- where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
    //          -- union all
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
    //         union all

    //         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
    //         a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
    //         from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
    //          left join
    //         (
    //         select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
    //         where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay IN ('BANK') group by d.no_kas,d.kd_skpd
    //             ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
    //           WHERE pay='BANK' and 
    //          (panjar not in ('1') or panjar is null) 

    //          union 
    //          select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
    //          join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
    //          where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
    //   UNION
    //         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan 
    //         union
    //   SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
    //   SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

    //         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

    //   SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
    //         left join 
    //         (
    //             select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
    //             where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
    //          ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd 
    //         where a.pay='BANK' and a.kd_skpd='$lcskpd'                  
    //         union all
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all           
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
    //         ) a
    //   where month(tgl)<='$bulan' and kode='$lcskpd') a 
    //         ";

    $asql = "SELECT terima-keluar as sisa FROM(select
    SUM(case when jns=1 then jumlah else 0 end) AS terima,
    SUM(case when jns=2 then jumlah else 0 end) AS keluar
    from (

        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
        SELECT '2022-01-01' AS tgl, null AS bku,
'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
union 
--                 SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
SELECT TGL_BUKTI AS tgl,NO_BUKTI AS bku,KET as ket, nilai AS jumlah,'1' AS jns_beban, KD_SKPD AS kode FROM TRHINLAIN WHERE KD_SKPD='$lcskpd'
union
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
where month(tgl)<='$bulan' and kode='$lcskpd') a ";
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
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
        $sk = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
       
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b";
        } else {


            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b";
        }

        $hasil_pjk = $this->db->query($asql_pjk);
        $pjkk = $hasil_pjk->row();
        $sisa_pajakk = $pjkk->sisa;


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

        $lcskpdd = substr($lcskpd, 0, 17);

        $nippa = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $csql = "SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND left(kd_skpd,17) = '$lcskpdd' AND (kode='PA' OR kode='KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND left(kd_skpd,17) = '$lcskpdd' AND kode='BK'";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $csql = "SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";
        $hasil4 = $this->db->query($csql);
        $trh4 = $hasil4->row();
        $nipbpp = str_replace('123456789', ' ', $_REQUEST['ttd3']);
        $csql = "SELECT nip as nip_bk,nama as nm_bpp,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbpp' AND kd_skpd = '$lcskpd' AND kode='BPP'";
        $hasil5 = $this->db->query($csql);
        $trh5 = $hasil5->row();

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
                        <td align=\"left\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>SKPD ".strtoupper($trh4->nm_skpd)." </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>TAHUN ANGGARAN $thn_ang</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" >&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;</strong></td></tr>
                        </table>
                        ";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>BUKU KAS UMUM PENGELUARAN</b></td>
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
                
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">8</td>
            </tr>
            </thead>";

        if ($pilih == 1) {
            $sql = "SELECT * FROM ( SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z )okei
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        } else {

            $sql = "SELECT * FROM ( SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z ) OKE
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        }
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcterima = 0;
        $lckeluar = 0;
        $lcterima_pajak = 0;
        $lckeluar_pajak = 0;
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
        foreach ($hasil->result() as $row) {
            $cRet .= "<tr>";
            $lhasil = $lhasil + $row->terima - $row->keluar;
            $totkeluar = $totkeluar + $row->keluar;
            $totterima = $totterima + $row->terima;
            if (!empty($row->tanggal)) {
                $a = $row->tanggal;
                $jaka = $this->tukd_model->tanggal_ind($a);
                $lcno = $lcno + 1;
                $no_bku = $row->no_kas;

                $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$lcno</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$jaka</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$no_bku</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lcterima = $lcterima + $row->terima;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lckeluar = $lckeluar + $row->keluar;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            } else {
                $cRet .= " <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray\">&nbsp;</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">&nbsp;</td>
                                <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:dashed 1px gray\"></td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '3') {
                        $lcterima_pajak = $lcterima_pajak + $row->terima;
                    } else {
                        $lcterima = $lcterima + $row->terima;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '4') {
                        $lckeluar_pajak = $lckeluar_pajak + $row->keluar;
                    } else {
                        $lckeluar = $lckeluar + $row->keluar;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            }
            $cRet .= "</tr>";
        }

        $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\"  align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    
                 </tr>";

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
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
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
                     <td colspan=\"4\" valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">Total Penerimaan dan Pengeluaran</td>

                     <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak + $tox), "2", ",", ".") . "</td>
                     <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">" . number_format(($trh1->jmkeluar + $lckeluar + $lckeluar_pajak), "2", ",", ".") . "</td>
                     <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak + $tox) - ($trh1->jmkeluar + $lckeluar + $lckeluar_pajak), "2", ",", ".") . "</td>
                  </tr>";

        $cRet .= "
            </table>
            <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" >
            <tr>
                    <td colspan=\"15\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Saldo Kas di Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu $lcperiode2 </td>
                    
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                 </tr>

                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Rp " . number_format(($saldokasbung), "2", ",", ".") . " </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>

                 <tr>
                    <td colspan=\"15\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><i>(Terbilang : $terbilangsaldo)</i></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>      
            
        
        
                    <tr>
                    <td colspan=\"2\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan =\"14\"valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($xhasil_tunai), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldobank), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldoberharga), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($sisa_pajakk), "2", ",", ".") . "</td>
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
                 </tr>
                 <tr>";
        /*       
                 if($_REQUEST['ttd3']!=""){
                 
                    $cRet .="<td align=\"center\" colspan=\"6\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"8\" style=\"font-size:11px;border: solid 1px white;\">
                    <br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";                                      
                     $cRet .="<td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh5->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
                 }else{
                     $cRet .="<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>"; 
                 }
                    $cRet .="</tr>
        */

        if ($_REQUEST['ttd2'] != "") {

            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "
            <br>$trh3->jabatan<br>&nbsp;<br>&nbsp;<br>&nbsp;
            <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";
        }

        // else {
        //     $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
        //             Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
        //             <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
        //             " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
        //             <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";
        // }
        $cRet .= "</tr>
        </table>";

        $print = $this->uri->segment(3);
        if ($print == 0) {

            $data['prev'] = $cRet;
            echo ("<title>Buku Kas Umum</title>");
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
            //$this->_mpdf('',$cRet,10,10,10,'0',1,'');
        }
    }

    function cetak_bku_skpd()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);

        $this->db->query("recall_skpd '$lcskpd'");
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
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
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

        $tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();

        $saldoawal = $trh4->sel;
        $saldoawal = $saldoawal + $tox;
        $lcskpdd = substr($lcskpd, 0, 17);
        $lcskpdd = $lcskpdd . ".0000";
        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpdd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        if ($pilih == 1) {
    //         $asql = "SELECT terima-keluar as sisa FROM(select
    //   SUM(case when jns=1 then jumlah else 0 end) AS terima,
    //   SUM(case when jns=2 then jumlah else 0 end) AS keluar
    //   from (
    //   SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
    //   SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
    //         select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
    //         c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' union all
    //          select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
    //          join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
    //          where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
    //          union all
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
    //    SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
    //    a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
    //    from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
    //          left join
    //    (
    //     select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd
    //     -- inner join trhstrpot g on d.no_bukti=g.no_terima and d.kd_skpd=g.kd_skpd 
    //     where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
    //    ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
    //           WHERE pay='BANK' and 
    //          (panjar not in ('1') or panjar is null) 
    //          union 
    //          select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
    //          join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
    //          where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
    //   UNION
    //         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union
    //   SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union
    //   SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

    //         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

    //   SELECT a.tgl_kas AS tgl,a.no_panjar AS bku,a.keterangan as ket,a.nilai-isnull(b.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM tr_panjar a
    //         left join 
    //         (
    //             select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
    //             where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
    //          ) b on a.no_panjar=b.no_kas and a.kd_skpd=b.kd_skpd 
    //         where a.pay='BANK' and a.kd_skpd='$lcskpd'                  
    //         union all
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$lcskpd'
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd union all           
    //         select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
    //         from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
    //         where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
    //         GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
    //         ) a
    //         where tgl<='$lctgl2' and kode='$lcskpd') a 
    $asql="SELECT terima-keluar as sisa FROM(select
      SUM(case when jns=1 then jumlah else 0 end) AS terima,
      SUM(case when jns=2 then jumlah else 0 end) AS keluar
      from (
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
      SELECT '2022-01-01' AS tgl, null AS bku,
	    'Saldo Awal' AS ket, sld_awal_bank AS jumlah, '1' as jns, kd_skpd AS kode FROM ms_skpd WHERE kd_skpd = '$lcskpd'
                union
--       SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM TRHINLAIN WHERE pay='BANK' union
 SELECT TGL_BUKTI AS tgl,NO_BUKTI AS bku,KET as ket, nilai AS jumlah,'1' AS jns_beban, KD_SKPD AS kode FROM TRHINLAIN WHERE KD_SKPD='$lcskpd' 
            union 
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='1.02.0.00.0.00.01.0000' and  d.pay='BANK' union all
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
            $asql = "

            SELECT terima-keluar as sisa FROM(select
      SUM(case when jns=1 then jumlah else 0 end) AS terima,
      SUM(case when jns=2 then jumlah else 0 end) AS keluar
      from (
      
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan 

      union 
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' 
      union
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
      where month(tgl)<='$bulan' and kode='$lcskpd') a 
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
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
        $sk = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
       
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b";
        } else {


            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b";
        }

        $hasil_pjk = $this->db->query($asql_pjk);
        $pjkk = $hasil_pjk->row();
        $sisa_pajakk = $pjkk->sisa;


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

        $lcskpdd = substr($lcskpd, 0, 17);

        $nippa = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $csql = "SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND left(kd_skpd,17) = '$lcskpdd' AND (kode='PA' OR kode='KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND left(kd_skpd,17) = '$lcskpdd' AND kode='BK'";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $csql = "SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";
        $hasil4 = $this->db->query($csql);
        $trh4 = $hasil4->row();
        $nipbpp = str_replace('123456789', ' ', $_REQUEST['ttd3']);
        $csql = "SELECT nip as nip_bk,nama as nm_bpp,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbpp' AND kd_skpd = '$lcskpd' AND kode='BPP'";
        $hasil5 = $this->db->query($csql);
        $trh5 = $hasil5->row();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/kab-Melawi.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD $trh4->nm_skpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN 2022</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        </table>
                        ";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>BUKU KAS UMUM PENGELUARAN</b></td>
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
                
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">8</td>
            </tr>
            </thead>";

        if ($pilih == 1) {
            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z )okei
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        } else {

            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z ) OKE
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        }


        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcterima = 0;
        $lckeluar = 0;
        $lcterima_pajak = 0;
        $lckeluar_pajak = 0;
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
        foreach ($hasil->result() as $row) {
            $cRet .= "<tr>";
            $lhasil = $lhasil + $row->terima - $row->keluar;
            $totkeluar = $totkeluar + $row->keluar;
            $totterima = $totterima + $row->terima;
            if (!empty($row->tanggal)) {
                $a = $row->tanggal;
                $jaka = $this->tukd_model->tanggal_ind($a);
                $lcno = $lcno + 1;
                $no_bku = $row->no_kas;

                $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$lcno</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$jaka</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$no_bku</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lcterima = $lcterima + $row->terima;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lckeluar = $lckeluar + $row->keluar;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            } else {
                $cRet .= " <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray\">&nbsp;</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">&nbsp;</td>
                                <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:dashed 1px gray\"></td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '3') {
                        $lcterima_pajak = $lcterima_pajak + $row->terima;
                    } else {
                        $lcterima = $lcterima + $row->terima;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '4') {
                        $lckeluar_pajak = $lckeluar_pajak + $row->keluar;
                    } else {
                        $lckeluar = $lckeluar + $row->keluar;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            }
            $cRet .= "</tr>";
        }

        $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\"  align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    
                 </tr>";

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
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
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
                   <td  valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\"></td>
                   <td valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\"></td>
                   <td valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\"></td>
                   <td valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\"></td>
                   <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak + $tox), "2", ",", ".") . "</td>
                   <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">" . number_format(($trh1->jmkeluar + $lckeluar + $lckeluar_pajak), "2", ",", ".") . "</td>
                   <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\"></td>
                </tr>";

        $cRet .= "
            </table>
            <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
            <tr>
                    <td colspan=\"15\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Saldo Kas di Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu $lcperiode2 </td>
                    
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                 </tr>

                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Rp " . number_format(($saldokasbung), "2", ",", ".") . " </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>

                 <tr>
                    <td colspan=\"15\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><i>(Terbilang : $terbilangsaldo)</i></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>      
            
        
        
                    <tr>
                    <td colspan=\"2\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan =\"14\"valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($xhasil_tunai), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldobank), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldoberharga), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($sisa_pajakk), "2", ",", ".") . "</td>
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
                 </tr>
                 <tr>";
        /*       
                 if($_REQUEST['ttd3']!=""){
                 
                    $cRet .="<td align=\"center\" colspan=\"6\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"8\" style=\"font-size:11px;border: solid 1px white;\">
                    <br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";                                      
                     $cRet .="<td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh5->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
                 }else{
                     $cRet .="<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>"; 
                 }
                    $cRet .="</tr>
        */

        if ($_REQUEST['ttd3'] != "") {

            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh5->jabatan<br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
        } else {
            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";
        }
        $cRet .= "</tr>
        </table>";

        $print = $this->uri->segment(3);
        if ($print == 0) {

            $data['prev'] = $cRet;
            echo ("<title>Buku Kas Umum</title>");
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
            //$this->_mpdf('',$cRet,10,10,10,'0',1,'');
        }
    }

    function cetak_bku_skpd_77_new()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);

        $this->db->query("recall_skpd '$lcskpd'");
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
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
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

        $tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();

        $saldoawal = $trh4->sel;
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
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
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
        inner join trhstrpot g on d.no_bukti=g.no_terima and d.kd_skpd=g.kd_skpd 
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
            $asql = "

            SELECT terima-keluar as sisa FROM(select
      SUM(case when jns=1 then jumlah else 0 end) AS terima,
      SUM(case when jns=2 then jumlah else 0 end) AS keluar
      from (
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union all
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union all
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' 
            union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in ('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             
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
        inner join trhstrpot g on d.no_bukti=g.no_terima and d.kd_skpd=g.kd_skpd
        where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
       ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
              WHERE pay='BANK' and 
             (panjar not in ('1') or panjar is null) 
             union all 
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
             join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
      union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union all
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union all
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union all 

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union all 

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
      where month(tgl)<='$bulan' and kode='$lcskpd') a 
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
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
        $sk = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
       
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b";
        } else {



            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b";
        }

        $hasil_pjk = $this->db->query($asql_pjk);
        $pjkk = $hasil_pjk->row();
        $sisa_pajakk = $pjkk->sisa;


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

        $lcskpdd = substr($lcskpd, 0, 17);

        $nippa = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $csql = "SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND left(kd_skpd,17) = '$lcskpdd' AND (kode='PA' OR kode='KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND left(kd_skpd,17) = '$lcskpdd' AND kode='BK'";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $csql = "SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";
        $hasil4 = $this->db->query($csql);
        $trh4 = $hasil4->row();
        $nipbpp = str_replace('123456789', ' ', $_REQUEST['ttd3']);
        $csql = "SELECT nip as nip_bk,nama as nm_bpp,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbpp' AND kd_skpd = '$lcskpd' AND kode='BPP'";
        $hasil5 = $this->db->query($csql);
        $trh5 = $hasil5->row();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                     <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>$prov<br>BUKU KAS UMUM PENGELUARAN</b></td>
                 </tr>
                 <tr>
                     <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE " . strtoupper($lcperiode) . "</b></td>
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
                     <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                     <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;" . strtoupper($trh4->nm_skpd) . "</td>
                 </tr>
                 <tr>
                     <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">Pengguna Anggaran / Kuasa Pengguna Anggaran</td>
                     <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh2->nm_pa</td>
                 </tr>
                 <tr>
                     <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">Bendahara Pengeluaran</td>
                     <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh3->nm_bk</td>
                 </tr>
                        </table>
                        ";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
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
                
                <td align='center' bgcolor='#CCCCCC' colspan=10 width='20%' style='font-size:12px;font-weight:bold'>Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"22%\" style=\"font-size:12px;font-weight:bold\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Saldo</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"   colspan='10' style=\"font-size:12px;border-top:solid 1px black\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
            </tr>
            </thead>";

        if ($pilih == 1) {
            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z )okei
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        } else {

            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z ) OKE
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening ";
        }
        //echo $sql;

        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcterima = 0;
        $lckeluar = 0;
        $lcterima_pajak = 0;
        $lckeluar_pajak = 0;
        $lhasil = $saldoawal;
        $saldolalu = number_format($lhasil, "2", ",", ".");
        $cRet .= "<tr><td valign=\"top\" width=\"5%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\" width=\"10%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                
                                <td valign='top'  width='8%' colspan='10' align='center' style='font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black'></td>
                                <td valign=\"top\"  width=\"20%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">Saldo Lalu</td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">$saldolalu</td></tr>";

        $totterima = 0;
        $totkeluar = 0;
        foreach ($hasil->result() as $row) {
            $cRet .= "<tr>";
            $lhasil = $lhasil + $row->terima - $row->keluar;
            $totkeluar = $totkeluar + $row->keluar;
            $totterima = $totterima + $row->terima;
            if (!empty($row->tanggal)) {
                $a = $row->tanggal;
                $jaka = $this->tukd_model->tanggal_ind($a);
                $lcno = $lcno + 1;
                $no_bku = $row->no_kas;

                $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$lcno</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$jaka</td>
                                    <td valign='top' colspan='9' align='center' style='font-size:10px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>" . ($row->kegiatan) . "</td>
                                    <td valign='top' colspan='1'align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>" . ($row->rekening) . "</td>                   
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lcterima = $lcterima + $row->terima;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lckeluar = $lckeluar + $row->keluar;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            } else {
                $cRet .= " <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray\">&nbsp;</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">&nbsp;</td>
                                
                                <td valign='top' colspan='9' align='center' style='font-size:10px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>" . ($row->kegiatan) . "</td>
                                    <td valign='top' colspan='1'align='center' style='font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray'>" . ($row->rekening) . "</td>                 
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '3') {
                        $lcterima_pajak = $lcterima_pajak + $row->terima;
                    } else {
                        $lcterima = $lcterima + $row->terima;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '4') {
                        $lckeluar_pajak = $lckeluar_pajak + $row->keluar;
                    } else {
                        $lckeluar = $lckeluar + $row->keluar;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            }
            $cRet .= "</tr>";
        }

        $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign='top' colspan='9' align='center' style='font-size:12px;border-top:none'>&nbsp;</td>
                    <td valign=\"top\"  align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    
                 </tr>
                 <tr>
                    <td  colspan = '12'valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                 </tr>";

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
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        }


        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();
        if ($pilih == 1) {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas < '$lctgl1'  and a.kd_skpd = '$lcskpd'";
        } else {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) < '$bulan' and year(a.tgl_kas) = $thn_ang and a.kd_skpd = '$lcskpd' ";
        }




        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();

        $saldos = ($trh1->jmterima + $lcterima + $lcterima_pajak - $trh1->jmkeluar - $lckeluar - $lckeluar_pajak + $tox);
        if ($saldos != 0 || $saldos != '0') {
            $terbilangsaldo = $this->tukd_model->terbilang($saldos);
        } else {
            $terbilangsaldo = "Nol Rupiah";
        }

        $saldokasbung = ($xhasil_tunai + $saldobank +  $saldoberharga);
        if ($saldokasbung != 0 || $saldokasbung != '0') {
            $terbilangsaldo1 = $this->tukd_model->terbilang($saldokasbung);
        } else {
            $terbilangsaldo1 = "Nol Rupiah";
        }

        $cRet .= "
            </table>
            <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
            <tr>
                    <td colspan=\"15\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Saldo Kas di Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu $lcperiode2  
                    </td>
                    
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                 </tr>

                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Rp " . number_format(($saldokasbung), "2", ",", ".") . " </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>

                 <tr>
                    <td colspan=\"15\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><i>(Terbilang : $terbilangsaldo1)</i></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>      
            
        
        
                    <tr>
                    <td colspan=\"2\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan =\"14\"valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($xhasil_tunai), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldobank), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldoberharga), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($sisa_pajakk), "2", ",", ".") . "</td>
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
                 </tr>
                 <tr>";
        /*       
                 if($_REQUEST['ttd3']!=""){
                 
                    $cRet .="<td align=\"center\" colspan=\"6\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"8\" style=\"font-size:11px;border: solid 1px white;\">
                    <br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";                                      
                     $cRet .="<td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh5->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
                 }else{
                     $cRet .="<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>"; 
                 }
                    $cRet .="</tr>
        */

        if ($_REQUEST['ttd3'] != "") {

            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh5->jabatan<br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
        } else {
            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";
        }
        $cRet .= "</tr>
        </table>";

        $print = $this->uri->segment(3);
        if ($print == 0) {

            $data['prev'] = $cRet;
            echo ("<title>Buku Kas Umum</title>");
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
            //$this->_mpdf('',$cRet,10,10,10,'0',1,'');
        }
    }

    function cetak_bku_skpd_rsud()
    {
        $client = $this->ClientModel->clientData('1');
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);

        $this->db->query("recall_skpd '$lcskpd'");
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
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             z.tgl_kas < '$lctgl1' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        } else {
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        }

        $tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();

        $saldoawal = $trh4->sel;
        $saldoawal = $saldoawal + $tox;
        $lcskpdd = substr($lcskpd, 0, 17);
        $lcskpdd = $lcskpdd . ".0000";
        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpdd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        if ($pilih == 1) {
            $asql = "SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and (panjar <> '3' or panjar is null) UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan  union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
             ) a
            where tgl<='$lctgl2' and kode='$lcskpd'";
        } else {
            $asql = "SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
                        
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and (panjar <> '3' or panjar is null)
            UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where month(tgl)<='$bulan' and kode='$lcskpd'";
        }


        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;


        $xterima_lalu = 0;
        $xkeluar_lalu = 0;
        $xhasil_lalu = 0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
        $sk = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6, nm_rek6 nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('210106010001','210105020001 ','210105010001 ','210105030001'))a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6";
        } else {


            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6, nm_rek6 nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('210106010001','210105020001 ','210105010001 ','210105030001'))a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6";
        }

        $hasil_pjk = $this->db->query($asql_pjk);
        $pjkk = $hasil_pjk->row();
        $sisa_pajakk = $pjkk->sisa;


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

        $lcskpdd = substr($lcskpd, 0, 17);

        $nippa = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $csql = "SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND left(kd_skpd,17) = '$lcskpdd' AND (kode='PA' OR kode='KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND left(kd_skpd,17) = '$lcskpdd' AND kode='BK'";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $csql = "SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";
        $hasil4 = $this->db->query($csql);
        $trh4 = $hasil4->row();
        $nipbpp = str_replace('123456789', ' ', $_REQUEST['ttd3']);
        $csql = "SELECT nip as nip_bk,nama as nm_bpp,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbpp' AND kd_skpd = '$lcskpd' AND kode='BPP'";
        $hasil5 = $this->db->query($csql);
        $trh5 = $hasil5->row();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>" . $client->pem . " " . $client->nm_kab . "<br>BUKU KAS UMUM PENGELUARAN</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE " . strtoupper($lcperiode) . "</b></td>
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
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh4->nm_skpd</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">Pengguna Anggaran / Kuasa Pengguna Anggaran</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh2->nm_pa</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">Bendahara Pengeluaran</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh3->nm_bk</td>
            </tr>";
        if ($_REQUEST['ttd3'] != "") {
            $cRet .= "<tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">Bendahara Pengeluaran Pembantu</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh5->nm_bpp</td>
            </tr>";
        }
        $cRet .= "<tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\"></td>
            </tr>
            </table>
            <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
            <thead> 
            <tr>
     <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Tanggal</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=10 \"20%\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"22%\" style=\"font-size:12px;font-weight:bold\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Saldo</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"10\" style=\"font-size:12px;border-top:solid 1px black\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
            </tr>
            </thead>";

        if ($pilih == 1) {
            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z )okei
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        } else {

            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z ) OKE
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        }


        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcterima = 0;
        $lckeluar = 0;
        $lcterima_pajak = 0;
        $lckeluar_pajak = 0;
        $lhasil = $saldoawal;
        $saldolalu = number_format($lhasil, "2", ",", ".");
        $cRet .= "<tr><td valign=\"top\" width=\"5%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\" width=\"10%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                <td valign=\"top\"  width=\"13%\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                <td valign=\"top\"  width=\"8%\" colspan=\"1\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                <td valign=\"top\"  width=\"20%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">Saldo Lalu</td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">$saldolalu</td></tr>";
        foreach ($hasil->result() as $row) {
            $cRet .= "<tr>";
            $lhasil = $lhasil + $row->terima - $row->keluar;
            if (!empty($row->tanggal)) {
                $a = $row->tanggal;
                $jaka = $this->tukd_model->tanggal_ind($a);
                $lcno = $lcno + 1;
                $no_bku = $row->no_kas;

                $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$no_bku</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$jaka</td>
                                    <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:10px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . ($row->kegiatan) . "</td>
                                    <td valign=\"top\" colspan=\"1\"align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . ($row->rekening) . "</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lcterima = $lcterima + $row->terima;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lckeluar = $lckeluar + $row->keluar;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            } else {
                $cRet .= " <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray\">&nbsp;</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">&nbsp;</td>
                                <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . ($row->kegiatan) . "</td>
                                <td valign=\"top\" colspan=\"1\"align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . ($row->rekening) . "</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '3') {
                        $lcterima_pajak = $lcterima_pajak + $row->terima;
                    } else {
                        $lcterima = $lcterima + $row->terima;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '4') {
                        $lckeluar_pajak = $lckeluar_pajak + $row->keluar;
                    } else {
                        $lckeluar = $lckeluar + $row->keluar;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            }
            $cRet .= "</tr>";
        }

        $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                 </tr>";

        if ($pilih == 1) {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas < '$lctgl1'  and a.kd_skpd = '$lcskpd'";
        } else {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) < '$bulan' and year(a.tgl_kas) = $thn_ang and a.kd_skpd = '$lcskpd'";
        }

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();


        $cRet .= "<tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Kas di Bendahara Pengeluaran bulan $lcperiode2 </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak + $tox), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">" . number_format(($trh1->jmkeluar + $lckeluar + $lckeluar_pajak), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak - $trh1->jmkeluar - $lckeluar - $lckeluar_pajak + $tox), "2", ",", ".") . "</td>
                 </tr>
        
                    <tr>
                    <td colspan=\"2\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan =\"14\"valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($xhasil_tunai), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldobank), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldoberharga), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($sisa_pajakk), "2", ",", ".") . "</td>
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
                 </tr>
                 <tr>";
        /*       
                 if($_REQUEST['ttd3']!=""){
                 
                    $cRet .="<td align=\"center\" colspan=\"6\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"8\" style=\"font-size:11px;border: solid 1px white;\">
                    <br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";                                      
                     $cRet .="<td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh5->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
                 }else{
                     $cRet .="<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    ".$daerah.",&nbsp;".$this->tukd_model->tanggal_format_indonesia($tgl_ttd)."<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>"; 
                 }
                    $cRet .="</tr>
        */

        if ($_REQUEST['ttd3'] != "") {

            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh5->jabatan<br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
        } else {
            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";
        }
        $cRet .= "</tr>
        </table>";

        $print = $this->uri->segment(3);
        if ($print == 0) {

            $data['prev'] = $cRet;
            echo ("<title>Buku Kas Umum</title>");
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
            //$this->_mpdf('',$cRet,10,10,10,'0',1,'');
        }
    }

    //cetakan baru
    function cetak_bku_skpd_new()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);

        $this->db->query("recall_skpd '$lcskpd'");
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
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             z.tgl_kas < '$lctgl1' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        } else {
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek6 AS rekening,
             b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND z.kd_skpd = '$lcskpd'";
        }

        $tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();

        $saldoawal = $trh4->sel;
        $saldoawal = $saldoawal + $tox;
        $lcskpdd = substr($lcskpd, 0, 17);
        $lcskpdd = $lcskpdd . ".0000";
        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpdd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        if ($pilih == 1) {
            $asql = "SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and (panjar <> '3' or panjar is null) UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan  WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
             ) a
            where tgl<='$lctgl2' and kode='$lcskpd'";
        } else {
            $asql = "SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
                        
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and (panjar <> '3' or panjar is null)
            UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where month(tgl)<='$bulan' and kode='$lcskpd'";
        }


        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;


        $xterima_lalu = 0;
        $xkeluar_lalu = 0;
        $xhasil_lalu = 0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
        $sk = $this->db->query("select kd_skpd from ms_skpd where kd_skpd='$lcskpd'");
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
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6, nm_rek6 nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('210106010001','210105020001 ','210105010001 ','210105030001'))a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6";
        } else {
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(map_pot) as kd_rek6, nm_rek6 nm_rek6 FROM ms_pot WHERE kd_rek6 IN ('210106010001','210105020001 ','210105010001 ','210105030001'))a
        LEFT JOIN 
        (SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek6, b.nm_rek6,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE a.kd_skpd='$lcskpd'                   
        GROUP BY  b.kd_rek6, b.nm_rek6, a.kd_skpd)b ON a.kd_rek6=b.kd_rek6";
        }

        $hasil_pjk = $this->db->query($asql_pjk);
        $pjkk = $hasil_pjk->row();
        $sisa_pajakk = $pjkk->sisa;


        // SALDO SURAT BERHARGA (SP2D yang tanggal pencairannya beda dengan tanggal sp2dnya) 
        if ($pilih == 1) {
            $csql = "SELECT sum(nilai) as total from trhsp2d where (tgl_terima BETWEEN '$lctgl1' and '$lctgl2')  and kd_skpd = '$lcskpd' and status_terima = '1' and (tgl_kas > '$lctgl2' or no_kas is null or no_kas='')";
        } else {
            $csql = "SELECT sum(nilai) as total from trhsp2d where month(tgl_terima)='$bulan' and kd_skpd = '$lcskpd' and status_terima = '1' and (month(tgl_kas) > '$bulan' or no_kas is null or no_kas='')";
        }
        $hasil_srt = $this->db->query($csql);
        $saldoberharga = $hasil_srt->row('total');

        $lcskpdd = substr($lcskpd, 0, 17);

        $nippa = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $csql = "SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND left(kd_skpd,17) = '$lcskpdd' AND (kode='PA' OR kode='KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND left(kd_skpd,17) = '$lcskpdd' AND kode='BK'";
        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $csql = "SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";
        $hasil4 = $this->db->query($csql);
        $trh4 = $hasil4->row();
        $nipbpp = str_replace('123456789', ' ', $_REQUEST['ttd3']);
        $csql = "SELECT nip as nip_bk,nama as nm_bpp,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbpp' AND kd_skpd = '$lcskpd' AND kode='BPP'";
        $hasil5 = $this->db->query($csql);
        $trh5 = $hasil5->row();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH Kabupaten Melawi </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD $trh4->nm_skpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN 2022</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        ";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>BUKU KAS UMUM PENGELUARAN</b></td>
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
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=10 \"20%\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"22%\" style=\"font-size:12px;font-weight:bold\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Saldo</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"10\" style=\"font-size:12px;border-top:solid 1px black\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
            </tr>
            </thead>";

        if ($pilih == 1) {
            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z )okei
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        } else {

            $sql = "SELECT * FROM ( SELECT distinct z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_sub_kegiatan as kegiatan,b.kd_rek6 AS rekening,
               b.nm_rek6 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z ) OKE
               ORDER BY tgl_kas,CAST(no_kas AS INT),jns_trans,st,rekening";
        }


        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcterima = 0;
        $lckeluar = 0;
        $lcterima_pajak = 0;
        $lckeluar_pajak = 0;
        $lhasil = $saldoawal;
        $saldolalu = number_format($lhasil, "2", ",", ".");
        $cRet .= "<tr><td valign=\"top\" width=\"5%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\" width=\"10%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                <td valign=\"top\"  width=\"13%\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                <td valign=\"top\"  width=\"8%\" colspan=\"1\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                                <td valign=\"top\"  width=\"20%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">Saldo Lalu</td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\"  width=\"13%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">$saldolalu</td></tr>";
        foreach ($hasil->result() as $row) {
            $cRet .= "<tr>";
            $lhasil = $lhasil + $row->terima - $row->keluar;
            if (!empty($row->tanggal)) {
                $a = $row->tanggal;
                $jaka = $this->tukd_model->tanggal_ind($a);
                $lcno = $lcno + 1;
                $no_bku = $row->no_kas;

                $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$no_bku</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$jaka</td>
                                    <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:10px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . ($row->kegiatan) . "</td>
                                    <td valign=\"top\" colspan=\"1\"align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . ($row->rekening) . "</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lcterima = $lcterima + $row->terima;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $lckeluar = $lckeluar + $row->keluar;
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            } else {
                $cRet .= " <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray\">&nbsp;</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">&nbsp;</td>
                                <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . ($row->kegiatan) . "</td>
                                <td valign=\"top\" colspan=\"1\"align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . ($row->rekening) . "</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '3') {
                        $lcterima_pajak = $lcterima_pajak + $row->terima;
                    } else {
                        $lcterima = $lcterima + $row->terima;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '4') {
                        $lckeluar_pajak = $lckeluar_pajak + $row->keluar;
                    } else {
                        $lckeluar = $lckeluar + $row->keluar;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            }
            $cRet .= "</tr>";
        }

        $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                 </tr>";

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
        if ($saldos != 0 || $saldos != '0') {
            $terbilangsaldo = $this->tukd_model->terbilang($saldos);
        } else {
            $terbilangsaldo = "Nol Rupiah";
        }

        $cRet .= "<tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Saldo Kas di Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu bulan $lcperiode2 </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"></td>
                 </tr>

                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Rp" . number_format(($trh1->jmterima + $lcterima + $lcterima_pajak - $trh1->jmkeluar - $lckeluar - $lckeluar_pajak + $tox), "2", ",", ".") . " </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>

                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">($terbilangsaldo)</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
        
                    <tr>
                    <td colspan=\"2\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan =\"14\"valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($xhasil_tunai), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\"></td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldobank), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldoberharga), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($sisa_pajakk), "2", ",", ".") . "</td>
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
                 </tr>
                 <tr>";

        if ($_REQUEST['ttd3'] != "") {

            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Disetujui oleh,<br> Pengguna Anggaran / Kuasa Pengguna Anggaran <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh5->jabatan<br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh5->nm_bpp</b></u><br>$trh5->pangkat<br>$trh5->nip_bk</td>";
        } else {
            $cRet .= "<td align=\"center\" colspan=\"12\" style=\"font-size:11px;border: solid 1px white;\">
                    Disetujui oleh,<br> Pengguna Anggaran / Kuasa Pengguna Anggaran <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>";
        }
        $cRet .= "</tr>
        </table>";

        $print = $this->uri->segment(3);
        if ($print == 0) {

            $data['prev'] = $cRet;
            echo ("<title>Buku Kas Umum</title>");
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '', $atas, $bawah, $kiri, $kanan);
            //$this->_mpdf('',$cRet,10,10,10,'0',1,'');
        }
    }
    //end cetakan BKU baru

    function cetak_bku_global()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $lcskpd = $_REQUEST['kd_skpd'];
        $skpx = substr($lcskpd, 0, 17);
        $pilih = $_REQUEST['cpilih'];
        $atas = $this->uri->segment(4);
        $bawah = $this->uri->segment(5);
        $kiri = $this->uri->segment(6);
        $kanan = $this->uri->segment(7);

        /*$ckbpp = $this->db->query("select kd_skpd from ms_skpd where left(kd_skpd,17)=left('$lcskpd',17)");
        foreach($ckbpp->result_array() as $resulte)
        {        
        $skppd = $resulte['kd_skpd'];       
        
        $this->db->query("recall '$skppd'");             
        }*/
        $this->db->query("recall_global '$lcskpd'");
        $this->db->query("WITH a as
(
SELECT 
no_kas,kd_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,terima,keluar,jns_trans,
ROW_NUMBER() OVER(PARTITION by 
no_kas,kd_skpd,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,terima,keluar,jns_trans
 ORDER BY no_kas) 
AS duplicateRecCount
FROM trdrekal  
)
--Now Delete Duplicate Records
DELETE FROM a
WHERE duplicateRecCount > 1
");

        //$this->db->query("recall '$lcskpd'");
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
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek5 AS rekening,
             b.nm_rek5 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             z.tgl_kas < '$lctgl1' and year(z.tgl_kas) = $thn_ang AND LEFT(z.kd_skpd,17)=LEFT('$lcskpd',17)";
        } else {
            $csql3 = "SELECT SUM(z.terima) AS jmter,SUM(z.keluar) AS jm_kel , SUM(z.terima)-SUM(z.keluar) AS sel FROM (SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,
             '' AS rekening,uraian,0 AS terima,0 AS keluar , 0 AS st,jns_trans FROM trhrekal
             UNION ALL
             SELECT a.kd_skpd,a.tgl_kas,'' AS tanggal,b.no_kas,b.kd_rek5 AS rekening,
             b.nm_rek5 AS uraian, b.terima,b.keluar , case when b.terima<>0 then'1' else '2' end AS st, b.jns_trans FROM
             trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd)z WHERE
             month(z.tgl_kas) < '$bulan' and year(z.tgl_kas) = $thn_ang AND LEFT(z.kd_skpd,17)=LEFT('$lcskpd',17)";
        }

        $tox_awal = "SELECT SUM(isnull(sld_awal_bank,0)+ isnull(sld_awal,0)) AS jumlah FROM ms_skpd where LEFT(kd_skpd,17)=LEFT('$lcskpd',17)";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();

        $saldoawal = $trh4->sel;
        $saldoawal = $saldoawal + $tox;

        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$lcskpd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        if ($pilih == 1) {
            $asql = "SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan  WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK' 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
             ) a
            where tgl <= '$lctgl2' and left(kode,17)=left('$lcskpd',17)";
        } else {
            $asql = "select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2')  and bank='BNK' 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where month(tgl)<='$bulan' and left(kode,17)=left('$lcskpd',17)";
        }


        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;


        //saldo tunai
        /*
        
        //bank
            if ($pilih==1){
            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan  WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
             ) a
            where tgl<='$lctgl2' and LEFT(kode,17)=LEFT('$lcskpd',17)";       
            }else{
            $asql="select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
                        
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                    union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2') and pot_khusus in ('0','2') 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where month(tgl)<='$bulan' and LEFT(kode,17)=LEFT('$lcskpd',17)"; 
            }

                        
        $hasil=$this->db->query($asql);
        $bank=$hasil->row();
        $keluarbank=$bank->keluar;
        $terimabank=$bank->terima;
        $saldobank=$terimabank-$keluarbank;
        //tunai
        $esteh="SELECT 
                SUM(case when jns=1 then jumlah else 0 end ) AS terima,
                SUM(case when jns=2 then jumlah else 0 end) AS keluar
                FROM (
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALL
                select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar UNION ALL
                select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN ('4','2') and pot_khusus =0  
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd             
                UNION ALL
                SELECT  a.tgl_bukti AS tgl, a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
                                AND MONTH(a.tgl_bukti)<'$bulan' and a.kd_skpd='$lcskpd' 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)<'$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd')
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                SELECT  tgl_bukti AS tgl,   no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') and no_sp2d in 
                                (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)<'$bulan' and  no_kas not in
                                (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)<'$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd'
                
                UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' UNION ALL
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION ALL
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI'
                ) a 
                where month(a.tgl)<'$bulan' and left(kode,17)=left('$lcskpd',17)";
              $hasils = $this->db->query($esteh);               
               $okok = $hasils->row();  
               $terima = $okok->terima;
               $keluar = $okok->keluar;                  
               $saldotunai_skpd=($terima+$tox)-$keluar;
        
        //bulan ini
        
        $sqlini="SELECT SUM(a.masuk) as terima,
                SUM(a.keluar) as keluar FROM (
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS masuk,0 AS keluar,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
                        select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as masuk, 0 as keluar,kd_skpd as kode from tr_jpanjar where jns=2 UNION ALl
                        select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, 0 as masuk,nilai as keluar,kd_skpd as kode from tr_panjar UNION ALL
                        select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, 0 as masuk,SUM(b.rupiah) as keluar, a.kd_skpd as kode 
                                from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                                where jns_trans NOT IN ('4','2') and pot_khusus =0  
                                GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
                        UNION ALL
                        SELECT a.tgl_bukti AS tgl,a.no_bukti AS bku,a.ket AS ket,0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar,a.kd_skpd AS kode 
                                FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
                                LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
                                LEFT JOIN (SELECT no_spm, SUM (nilai) pot   FROM trspmpot GROUP BY no_spm) c
                                ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3')
                                AND MONTH(a.tgl_bukti)='$bulan' and a.kd_skpd='$lcskpd' 
                                AND a.no_bukti NOT IN(
                                select no_bukti from trhtransout 
                                where no_sp2d in 
                                (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
                                (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)='$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd')
                                GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
                        UNION ALL
                        select tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 AS masuk, ISNULL(total,0)  AS keluar,kd_skpd AS kode 
                                from trhtransout 
                                WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in 
                                (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$lcskpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1)
                                AND MONTH(tgl_bukti)='$bulan' and  no_kas not in
                                (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$lcskpd' 
                                AND MONTH(tgl_bukti)='$bulan'
                                GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1)
                                and jns_spp in (4,5,6) and kd_skpd='$lcskpd'

                        UNION ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,0 as masuk,nilai AS keluar,kd_skpd AS kode FROM trhoutlain WHERE pay='TUNAI' UNION ALL
                        SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' UNION  ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,keterangan AS ket, 0 as masuk,nilai AS keluar,kd_skpd_sumber AS kode FROM tr_setorpelimpahan UNION  ALL
                        SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai as masuk,0 AS keluar,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI')a
                        where month(a.tgl)='$bulan' and left(kode,17)=left('$lcskpd',17)";
                        $hasilini = $this->db->query($sqlini);  
                        $tunaiok = $hasilini->row(); 
                        $terimain = $tunaiok->terima;
                        $keluarin = $tunaiok->keluar;                    
                        $saldotunai_skpd_ini=($terimain+$saldotunai_skpd)-$keluarin;
                        */
        $xterima_lalu = 0;
        $xkeluar_lalu = 0;
        $xhasil_lalu = 0;
        $sk_lalu = $this->db->query("select kd_skpd from ms_skpd where left(kd_skpd,17)=left('$lcskpd',17)");
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
        $sk = $this->db->query("select kd_skpd from ms_skpd where left(kd_skpd,17)=left('$lcskpd',17)");
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
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(kd_rek5) as kd_rek5,nm_rek5 FROM ms_pot WHERE kd_rek5 IN ('2130101','2130201','2130301','2130401','2130501','4110707'))a
        LEFT JOIN 
        (SELECT b.kd_rek5, b.nm_rek5,a.kd_skpd,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                                   
        GROUP BY  b.kd_rek5, b.nm_rek5, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek5, b.nm_rek5,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN tgl_bukti<'$lctgl1' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN (tgl_bukti BETWEEN '$lctgl1' and '$lctgl2') THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN tgl_bukti<='$lctgl2' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                   
        GROUP BY  b.kd_rek5, b.nm_rek5, a.kd_skpd)b ON a.kd_rek5=b.kd_rek5";
        } else {
            $asql_pjk = "SELECT ISNULL(SUM(terima_lalu),0) as terima_lalu, ISNULL(SUM(terima_ini),0) as terima_ini, ISNULL(SUM(terima),0) as terima,
        ISNULL(SUM(setor_lalu),0) as setor_lalu, ISNULL(SUM(setor_ini),0) as setor_ini, ISNULL(SUM(setor),0) as setor, 
        ISNULL(SUM(terima)-SUM(setor),0) as sisa
        FROM
        (SELECT RTRIM(kd_rek5) as kd_rek5,nm_rek5 FROM ms_pot WHERE kd_rek5 IN ('2130101','2130201','2130301','2130401','2130501','4110707'))a
        LEFT JOIN 
        (SELECT b.kd_rek5, b.nm_rek5,a.kd_skpd,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS terima_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS terima_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS terima,
        0 as setor_lalu,
        0 as setor_ini,
        0 as setor
        FROM trhtrmpot a
        INNER JOIN trdtrmpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                                   
        GROUP BY  b.kd_rek5, b.nm_rek5, a.kd_skpd 

        UNION ALL

        SELECT b.kd_rek5, b.nm_rek5,a.kd_skpd,
        0 as terima_lalu,
        0 as terima_ini,
        0 as terima,
        SUM(CASE WHEN MONTH(tgl_bukti)<'$bulan' THEN b.nilai ELSE 0 END) AS setor_lalu,
        SUM(CASE WHEN MONTH(tgl_bukti)='$bulan' THEN b.nilai ELSE 0 END) AS setor_ini,
        SUM(CASE WHEN MONTH(tgl_bukti)<='$bulan' THEN b.nilai ELSE 0 END) AS setor
        FROM trhstrpot a
        INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trhsp2d c on a.kd_skpd=c.kd_skpd AND a.no_sp2d=c.no_sp2d
        WHERE left(a.kd_skpd,17)=left('$lcskpd',17)                   
        GROUP BY  b.kd_rek5, b.nm_rek5, a.kd_skpd)b ON a.kd_rek5=b.kd_rek5";
        }

        $hasil_pjk = $this->db->query($asql_pjk);
        $pjkk = $hasil_pjk->row();
        $sisa_pajakk = $pjkk->sisa;

        // SALDO SURAT BERHARGA (SP2D yang tanggal pencairannya beda dengan tanggal sp2dnya) 
        if ($pilih == 1) {
            $csql = "select sum(nilai) as total from trhsp2d where (tgl_terima BETWEEN '$lctgl1' and '$lctgl2')  and kd_skpd = '$lcskpd' and status_terima = '1' and (tgl_kas > '$lctgl2' or no_kas is null or no_kas='')";
        } else {
            $csql = "select sum(nilai) as total from trhsp2d where month(tgl_terima)='$bulan' and kd_skpd = '$lcskpd' and status_terima = '1' and (month(tgl_kas) > '$bulan' or no_kas is null or no_kas='')";
        }
        $hasil_srt = $this->db->query($csql);
        $saldoberharga = $hasil_srt->row('total');


        $nippa = str_replace('123456789', ' ', $_REQUEST['ttd']);
        $csql = "SELECT nip as nip_pa,nama as nm_pa,jabatan,pangkat FROM ms_ttd WHERE nip = '$nippa' AND kd_skpd = '$lcskpd' AND (kode='PA' or kode='KPA')";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $nipbk = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT nip as nip_bk,nama as nm_bk,jabatan,pangkat FROM ms_ttd WHERE nip = '$nipbk' AND left(kd_skpd,17) = left('$lcskpd',17) AND kode='BK'";

        $hasil3 = $this->db->query($csql);
        $trh3 = $hasil3->row();
        $csql = "SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = '$lcskpd' ";

        $hasil4 = $this->db->query($csql);
        $trh4 = $hasil4->row();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"17\" style=\"font-size:14px;border: solid 1px white;\"><b>$prov<br>BUKU KAS UMUM PENGELUARAN</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"17\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE " . strtoupper($lcperiode) . "</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"13\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"13\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"13\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh4->nm_skpd</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">Pengguna Anggaran / Kuasa Pengguna Anggaran</td>
                <td align=\"left\" colspan=\"13\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh2->nm_pa</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">Bendahara Pengeluaran</td>
                <td align=\"left\" colspan=\"13\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;$trh3->nm_bk</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"13\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\"></td>
            </tr>
            </table>
            <table style=\"border-collapse:collapse; border-color: black;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" > 
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;font-weight:bold;\">No.</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;font-weight:bold\">Tanggal</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;font-weight:bold\">Bidang</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"10\" style=\"font-size:12px;font-weight:bold\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;font-weight:bold\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;font-weight:bold\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;font-weight:bold\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;font-weight:bold\">Saldo</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"10\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">8</td>
            </tr>
            </thead>";

        if ($pilih == 1) {
            $sql = "SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2') AND
           year(a.tgl_kas) = '$thn_ang'and LEFT(kd_skpd,17)=LEFT('$lcskpd',17))
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_kegiatan as kegiatan,b.kd_rek5 AS rekening,
               b.nm_rek5 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where (a.tgl_kas BETWEEN '$lctgl1' AND '$lctgl2')
               AND year(a.tgl_kas) = '$thn_ang' and LEFT(b.kd_skpd,17)=LEFT('$lcskpd',17)))z
               ORDER BY tgl_kas,kd_skpd,cast (no_kas as int),jns_trans,st,rekening";
        } else {
            /*
           $sql = " SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and kd_skpd='$lcskpd')
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_kegiatan as kegiatan,b.kd_rek5 AS rekening,
               b.nm_rek5 AS uraian, b.terima,b.keluar , case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and b.kd_skpd='$lcskpd'))z
               ORDER BY tgl_kas,Cast(REPLACE(no_kas, 'A', '')  as int),jns_trans,st,rekening";
            */
            $sql = "SELECT z.* FROM ((SELECT kd_skpd,tgl_kas,tgl_kas AS tanggal,no_kas,'' AS kegiatan,
           '' AS rekening,uraian,0 AS terima,0 AS keluar , '' AS st,jns_trans FROM trhrekal a
           where month(a.tgl_kas) = '$bulan' AND
           year(a.tgl_kas) = '$thn_ang'and LEFT(kd_skpd,17)=LEFT('$lcskpd',17))
               UNION ALL
              ( SELECT a.kd_skpd,a.tgl_kas,NULL AS tanggal,b.no_kas,b.kd_kegiatan as kegiatan,b.kd_rek5 AS rekening,
               b.nm_rek5 AS uraian, 
               CASE WHEN b.keluar+b.terima<0 THEN (keluar*-1) ELSE terima END as terima,
               CASE WHEN b.keluar+b.terima<0 THEN (terima*-1) ELSE keluar END as keluar,
               case when b.terima<>0 then '1' else '2' end AS st, b.jns_trans FROM
               trdrekal b LEFT JOIN trhrekal a ON a.no_kas = b.no_kas and a.kd_skpd = b.kd_skpd where month(a.tgl_kas) ='$bulan' AND
               year(a.tgl_kas) = '$thn_ang' and LEFT(b.kd_skpd,17)=LEFT('$lcskpd',17)))z
               ORDER BY tgl_kas,kd_skpd,cast (no_kas as int),jns_trans,st,rekening";
        }


        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcterima = 0;
        $lcterima_pjk = 0;
        $lckeluar = 0;
        $lckeluar_pjk = 0;
        $lhasil = $saldoawal;
        $saldolalu = number_format($lhasil, "2", ",", ".");
        $cRet .= "<tr>
                                <td valign=\"top\" align=\"center\" width=\"5%\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">
                              </td>";
        $cRet .= "<td valign=\"top\" align=\"center\" width=\"9%\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                             <td valign=\"top\" colspan=\"1\" width=\"10%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                             <td valign=\"top\" colspan=\"9\" width=\"17%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>
                             <td valign=\"top\" align=\"left\" width=\"7%\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">Saldo Lalu</td>";
        $cRet .= "<td valign=\"top\" width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\" width=\"12%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\" width=\"12%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\"></td>";
        $cRet .= "<td valign=\"top\" width=\"12%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px gray;border-top:solid 1px black\">$saldolalu</td>
                                 </tr>";
        foreach ($hasil->result() as $row) {
            $cRet .= "<tr>";
            $lhasil = $lhasil + $row->terima - $row->keluar;
            if (!empty($row->tanggal)) {
                $a = $row->tanggal;
                $jaka = $this->tukd_model->tanggal_ind($a);
                $lcno = $lcno + 1;
                $no_bku = $row->no_kas;
                $bidang = $row->kd_skpd;
                $cRet .= "<td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:solid 1px gray\">$no_bku</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$jaka</td>
                                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$bidang</td>
                                    <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . ($row->kegiatan) . "</td>
                                    <td valign=\"top\" colspan=\"1\"align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . ($row->rekening) . "</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '3') {
                        $lcterima_pjk = $lcterima_pjk + $row->terima;
                    } else {
                        $lcterima = $lcterima + $row->terima;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }

                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '4') {
                        $lckeluar_pjk = $lckeluar_pjk + $row->keluar;
                    } else {
                        $lckeluar = $lckeluar + $row->keluar;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:solid 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            } else {
                $cRet .= " <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:none 1px gray;border-top:none 1px gray\">&nbsp;</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">&nbsp;</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">&nbsp;</td>
                                <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . ($row->kegiatan) . "</td>
                                <td valign=\"top\" colspan=\"1\"align=\"center\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . ($row->rekening) . "</td>                
                                <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">$row->uraian</td>
                                ";
                if (empty($row->terima) or ($row->terima) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '3') {
                        $lcterima_pjk = $lcterima_pjk + $row->terima;
                    } else {
                        $lcterima = $lcterima + $row->terima;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->terima, "2", ",", ".") . "</td>";
                }
                if (empty($row->keluar) or ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {

                    if ($row->jns_trans == '4') {
                        $lckeluar_pjk = $lckeluar_pjk + $row->keluar;
                    } else {
                        $lckeluar = $lckeluar + $row->keluar;
                    }

                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($row->keluar, "2", ",", ".") . "</td>";
                }
                if (empty($row->terima) and empty($row->keluar) or ($row->terima) == 0 and ($row->keluar) == 0) {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\"></td>";
                } else {
                    $cRet .= "<td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:dashed 1px gray;border-top:dashed 1px gray\">" . number_format($lhasil, "2", ",", ".") . "</td>";
                }
            }
            $cRet .= "</tr>";
        }

        $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" colspan=\"9\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-top:none\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;border-top:solid 1px black;\">&nbsp;</td>
                 </tr>";
        /* $cRet .="
                  <tr>
                    <td colspan=\"12\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Jumlah Periode $lcperiode </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">".number_format($lcterima+$tox,"2",",",".")."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">".number_format($lckeluar,"2",",",".")."</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">".number_format($lhasil,"2",",",".")."</td>
                 </tr> ";
                 */
        if ($pilih == 1) {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas < '$lctgl1'  and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
        } else {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) < '$bulan' and year(a.tgl_kas) = $thn_ang and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
        }

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();

        if ($pilih == 1) {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE a.tgl_kas = '$lctgl1'  and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
        } else {
            $csql = "SELECT SUM(b.terima) AS jmterima, SUM(b.keluar) AS jmkeluar FROM trdrekal b INNER JOIN 
                        trhrekal a ON a.no_kas=b.no_kas and a.kd_skpd = b.kd_skpd WHERE month(a.tgl_kas) = '$bulan' and year(a.tgl_kas) = $thn_ang and LEFT(a.kd_skpd,17) = LEFT('$lcskpd',17)";
        }

        $hasil_ini = $this->db->query($csql);
        $trh1_ini = $hasil_ini->row();

        /*$trh1->jmterima+$lcterima-$trh1->jmkeluar-$lckeluar+$tox-$saldobank-$saldoberharga*/

        $cRet .= "<tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">Kas di Bendahara Pengeluaran $lcperiode2 </td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pjk), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">" . number_format(($trh1->jmkeluar + $lckeluar + $lckeluar_pjk + $tox), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\">" . number_format(($trh1->jmterima + $lcterima + $lcterima_pjk - $trh1->jmkeluar - $lckeluar - $lckeluar_pjk + $tox), "2", ",", ".") . "</td>
                 </tr>
        
                    <tr>
                    <td colspan=\"3\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Terdiri dari :</u></b></td>
                    <td colspan =\"14\"valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr> 
                 <tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Saldo Tunai</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($xhasil_tunai), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>               
                 <tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Saldo Bank</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldobank), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Surat Berharga</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($saldoberharga), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. Saldo Pajak</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:12px;border: solid 1px white;\"><b>Rp  " . number_format(($sisa_pajakk), "2", ",", ".") . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td colspan=\"13\" valign=\"top\" align=\"left\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                 </tr>
                 <tr>
                    <td align=\"center\" colspan=\"13\" style=\"font-size:12px;border: solid 1px white;\">
                    Mengetahui,<br> $trh2->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<u><b>$trh2->nm_pa</b></u><br>$trh2->pangkat<br>$trh2->nip_pa</td>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">
                    " . $daerah . ",&nbsp;" . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "<br>$trh3->jabatan <br>&nbsp;<br>&nbsp;<br>&nbsp;
                    <br>&nbsp;<u><b>$trh3->nm_bk</b></u><br>$trh3->pangkat<br>$trh3->nip_bk</td>
            
        </table>";



        $print = $this->uri->segment(3);


        $data['prev'] = $cRet;
        switch ($print) {
            case 0;
                echo ("<title>BKU</title>");
                echo $cRet;
                break;
            case 1;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '0', 1, '');
                break;

            case 2;
                //$this->_mpdf('',$cRet,10,10,10,'1');
                $this->support->_mpdf_down2('BKU', $skpx, $cRet, 10, 10, 10, '1');
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= BKU" ./*$kd.*/ ".xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }




    ///// SPJ














}
