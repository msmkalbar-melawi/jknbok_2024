<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class cetak_bukubank extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }



    function index()
    {
        $data['page_title'] = 'Buku Simpanan Bank';
        $this->template->set('title', 'Buku Simpanan Bank');
        $this->template->load('template', 'tukd/transaksi/kas_bank', $data);
    }

    function load_ttd($ttd)
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('$ttd','KPA')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }


    function cetak_simpanan_bank_kota()
    {
        $print = $this->uri->segment(3);
        $thn_ang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nm_skpd = $this->session->userdata('nm_skpd');
        $bulan = $_REQUEST['tgl1'];
        $lcperiode = $this->tukd_model->getBulan($bulan);
        $tgl_ttd = $_REQUEST['tgl_ttd'];
        $spasi = $_REQUEST['spasi'];
        $ttd1 = str_replace('123456789', ' ', $_REQUEST['ttd1']);
        $ttd2 = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $inskpd = substr($kd_skpd, 18, 4);
        if ($inskpd == "0000") {
            $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE kode = 'BK' AND a.kd_skpd = '$kd_skpd' and nip='$ttd1'";
            $csqls = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE (kode = 'PA' OR kode='KPA') AND a.kd_skpd = '$kd_skpd' and nip='$ttd2'";
        } else {
            $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE kode = 'BPP' AND a.kd_skpd = '$kd_skpd' and nip='$ttd1'";
            $csqls = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE (kode = 'PA' OR kode='KPA') AND left(a.kd_skpd,17) = left('$kd_skpd',17) and nip='$ttd2'";
        }
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $lcNmBP = $trh2->nama;
        $lcNipBP = $trh2->nip;
        $lcJabBP = $trh2->jabatan;
        $lcPangkatBP = $trh2->pangkat;
        //PA
        $hasil = $this->db->query($csqls);
        $trh2 = $hasil->row();
        $lcNmPA = $trh2->nama;
        $lcNipPA = $trh2->nip;
        $lcJabPA = $trh2->jabatan;
        $lcPangkatPA = $trh2->pangkat;

        $hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
        $trsk = $hasil->row();
        $nm_skpd = $trsk->nm_skpd;

        $skpdbp  = substr($kd_skpd, 0, 17) . ".0000";

        $prv = $this->db->query("SELECT provinsi,daerah from sclient WHERE kd_skpd='$skpdbp'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;


        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "kode='$kd_skpd'";
        } else {
            $init_skpd = "kode='$kd_skpd'";
        }

        $asql = "SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union ALL
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK'
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            ) a
            where month(tgl)<'$bulan' and $init_skpd";

        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;

        $cRet = '';
        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <tr>
                <td align='center' colspan='6' style='font-size:14px;border: solid 1px white;'><b>$prov<br>BUKU PEMBANTU SIMPANAN BANK<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
              <tr>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
            </tr>
            
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>SKPD</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td align='left' colspan='0' style='font-size:12px;border: solid 1px white;'>PERIODE</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;'>: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align='left' colspan='2' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
                <td align='left' colspan='4' style='font-size:12px;border: solid 1px white;border-bottom:solid 1px white;'>&nbsp;</td>
            </tr>
            </table>";

        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='1' cellspacing='1' cellpadding='$spasi'>
        <thead>        
            <tr>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' >Tanggal.</td>
                <td bgcolor='#CCCCCC' align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>No. BKU</td>
                <td bgcolor='#CCCCCC' align='center' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Uraian</td>
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Penerimaan</td> 
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Pengeluaran</td>  
                <td bgcolor='#CCCCCC' align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo</td>            
            </tr> 
        </thead>
            <tr>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;' ></td>
                <td align='center' width='10%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>
                <td align='right' width='35%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>Saldo Lalu</td>
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td> 
                <td align='center' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'></td>  
                <td align='right' width='15%' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;'>" . number_format($saldobank, "2", ",", ".") . "</td>            
            </tr>";


        $sql = "SELECT * FROM (
             select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar UNION ALL
             select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' AS jns,kd_skpd as kode from tr_jpanjar where jns='2' UNION ALL
            
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan UNION ALL
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union ALL
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join
             (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') UNION ALL
             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' union all
             select a.tgl_sts as tgl,a.no_sts as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and bank='BNK' 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
            
                        
                    ) a
             where month(a.tgl)='$bulan' and $init_skpd ORDER BY a.tgl,Cast(bku  as int), jns";


        $hasil = $this->db->query($sql);
        $saldo = $saldobank;
        $total_terima = 0;
        $total_keluar = 0;
        foreach ($hasil->result() as $row) {
            $bku   = $row->bku;
            $tgl     = $row->tgl;
            $uraian  = $row->ket;
            $nilai   = $row->jumlah;
            $jns     = $row->jns;

            if ($jns == 1) {
                $saldo = $saldo + $nilai;
                $total_terima = $total_terima + $nilai;
                $cRet .= "<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            } else {
                $saldo = $saldo - $nilai;
                $total_keluar = $total_keluar + $nilai;
                $cRet .= "<tr>
                                  <td valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$tgl</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$bku</td>
                                  <td valign='top' align='left' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>$uraian</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'></td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            }
        }
        $cRet .= "<tr>
                                  <td bgcolor='#CCCCCC' colspan='3' valign='top' align='center' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>JUMLAH</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>" . number_format($total_terima + $saldobank, "2", ",", ".") . "</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>" . number_format($total_keluar, "2", ",", ".") . "</td>
                                  <td bgcolor='#CCCCCC' valign='top' align='right' style='font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black'>" . number_format($total_terima - $total_keluar + $saldobank, "2", ",", ".") . "</td>
                                  </tr>";
        $cRet .= "<tr>
                    <td align='left' colspan='6' style='font-size:12px;border:solid 1px white'>&nbsp;</td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>                
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>&nbsp;</td>                    
                </tr>               
                <tr>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>Mengetahui:</td>
                    <td align='center' colspan='3' style='font-size:13px;border: solid 1px white;'>$daerah, " . $this->support->tanggal_format_indonesia($tgl_ttd) . "</td>                                                                                                                                                                                
                </tr>
               <tr>                
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'>&nbsp;</td>
                    <td align='left' colspan='3' style='font-size:12px;border: solid 1px white;'></td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'> NIP. $lcNipPA</td>
                    <td align='center' colspan='3' style='font-size:12px;border: solid 1px white;'>NIP. $lcNipBP</td>
                </tr>";
        $cRet .= '</table>';
        $data['prev'] = $cRet;
        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>Simpanan Bank </title>");
            echo $cRet;
        } else {
            $this->master_pdf->_mpdf('', $cRet, 10, 10, 10, '0', 0, '');
        }
    }

    function cetak_simpanan_bank2()
    {
        $print = $this->uri->segment(3);
        $thn_ang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nm_skpd = $this->session->userdata('nm_skpd');
        $bulan = $_REQUEST['tgl1'];
        $lcperiode = $this->tukd_model->getBulan($bulan);
        $tgl_ttd = $_REQUEST['tgl_ttd'];
        $spasi = $_REQUEST['spasi'];
        $ttd1 = str_replace('123456789', ' ', $_REQUEST['ttd1']);
        $ttd2 = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE kode = 'BK' AND a.kd_skpd = '$kd_skpd' and nip='$ttd1'";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $lcNmBP = $trh2->nama;
        $lcNipBP = $trh2->nip;
        $lcJabBP = $trh2->jabatan;
        $lcPangkatBP = $trh2->pangkat;
        $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE kode in ('PA','KPA') AND a.kd_skpd = '$kd_skpd' and nip='$ttd2'";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $lcNmPA = $trh2->nama;
        $lcNipPA = $trh2->nip;
        $lcJabPA = $trh2->jabatan;
        $lcPangkatPA = $trh2->pangkat;

        $hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
        $trsk = $hasil->row();
        $nm_skpd = $trsk->nm_skpd;

        $prv = $this->db->query("SELECT * from sclient WHERE kd_skpd='$kd_skpd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        $sal_ll = $this->db->query("SELECT CASE WHEN kd_bayar=1 THEN isnull(sld_awal,0)+sld_awalpajak ELSE 0 END AS sal_lalu 
										FROM ms_skpd where kd_skpd='$kd_skpd'");
        $sal_lal = $sal_ll->row();
        $sal_llu = $sal_lal->sal_lalu;

        $asql = "SELECT terima-keluar as sisa FROM(select
			SUM(case when jns=1 then jumlah else 0 end) AS terima,
			SUM(case when jns=2 then jumlah else 0 end) AS keluar
			from (
			SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
			SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
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
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            ) a
			where month(tgl)<'$bulan' and kode='$kd_skpd') a ";

        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $sisa = $bank->sisa;
        $saldobank = $sisa + $sal_llu;

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"6\" style=\"font-size:14px;border: solid 1px white;\"><b>$prov<br>BUKU PEMBANTU SIMPANAN BANK<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"0\" style=\"font-size:12px;border: solid 1px white;\">OPD</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"0\" style=\"font-size:12px;border: solid 1px white;\">PERIODE</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px white;\">&nbsp;</td>
            </tr>
			</table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"$spasi\">
		<thead>		   
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >Tanggal.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">No. BKU</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"35%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan</td> 
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran</td>  
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Saldo</td>            
            </tr> 
		</thead>
            <tr>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" ></td>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td>
                <td align=\"right\" width=\"35%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Saldo Lalu</td>
                <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td> 
                <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td>  
                <td align=\"right\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">" . number_format($saldobank, "2", ",", ".") . "</td>            
            </tr>";


        /*              $sql = "SELECT * FROM (
				SELECT tgl_kas AS tgl,no_kas AS bku,no_sp2d AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhsp2d where jns_spp in('1','2','3','4') 
				union
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhtransout WHERE jns_spp='4' 
				UNION
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan ) a 
				where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,bku, jns";
 */
        $sql = "SELECT * FROM (
	         SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
	         SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar  and c.kd_skpd=d.kd_skpd  where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku,'Terima'+ a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
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
            select a.tgl_sts as tgl,a.no_sts+1 as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  

			) a
             where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,Cast(bku  as int), jns";

        $hasil = $this->db->query($sql);
        $saldo = $saldobank;
        $total_terima = 0;
        $total_keluar = 0;
        foreach ($hasil->result() as $row) {
            $bku   = $row->bku;
            $tgl     = $row->tgl;
            $uraian  = $row->ket;
            $nilai   = $row->jumlah;
            $jns     = $row->jns;

            if ($jns == 1) {
                $saldo = $saldo + $nilai;
                $total_terima = $total_terima + $nilai;
                $cRet .= "<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$tgl</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$bku</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            } else {
                $saldo = $saldo - $nilai;
                $total_keluar = $total_keluar + $nilai;
                $cRet .= "<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$tgl</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$bku</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            }
        }
        $cRet .= "<tr>
                                  <td bgcolor=\"#CCCCCC\" colspan=\"3\" valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">JUMLAH</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($total_terima, "2", ",", ".") . "</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($total_keluar, "2", ",", ".") . "</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($total_terima - $total_keluar + $saldobank, "2", ",", ".") . "</td>
                                  </tr>";
        $cRet .= "<tr>
                    <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border:solid 1px white\">&nbsp;</td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">&nbsp;</td>                    
                </tr>				
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">Mengetahui:</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">$daerah, " . $this->support->tanggal_format_indonesia($tgl_ttd) . "</td>                                                                                                                                                                                
                </tr>
               <tr>                
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$lcJabPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"> NIP. $lcNipPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";
        $cRet .= '</table>';
        $data['prev'] = $cRet;
        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>Simpanan Bank </title>");
            echo $cRet;
        } else if ($print == 1) {
            $this->support->_mpdf('', $cRet, 10, 10, 10, '0', 0, '');
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=simpan_bank.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        }
    }



    function cetak_simpanan_bank_new()
    {
        $print = $this->uri->segment(3);
        $thn_ang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nm_skpd = $this->session->userdata('nm_skpd');
        $bulan = $_REQUEST['tgl1'];
        $lcperiode = $this->tukd_model->getBulan($bulan);
        $tgl_ttd = $_REQUEST['tgl_ttd'];
        $spasi = $_REQUEST['spasi'];
        $ttd1 = str_replace('123456789', ' ', $_REQUEST['ttd1']);
        $ttd2 = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE kode = 'BK' AND a.kd_skpd = '$kd_skpd' and nip='$ttd1'";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $lcNmBP = $trh2->nama;
        $lcNipBP = $trh2->nip;
        $lcJabBP = $trh2->jabatan;
        $lcPangkatBP = $trh2->pangkat;
        $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE kode in ('PA','KPA') AND a.kd_skpd = '$kd_skpd' and nip='$ttd2'";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $lcNmPA = $trh2->nama;
        $lcNipPA = $trh2->nip;
        $lcJabPA = $trh2->jabatan;
        $lcPangkatPA = $trh2->pangkat;

        $hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
        $trsk = $hasil->row();
        $nm_skpd = $trsk->nm_skpd;

        $prv = $this->db->query("SELECT * from sclient WHERE kd_skpd='$kd_skpd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        $sal_ll = $this->db->query("SELECT isnull(sld_awal_bank,0)+sld_awalpajak AS sal_lalu 
                    FROM ms_skpd where kd_skpd='$kd_skpd'");
        $sal_lal = $sal_ll->row();
        $sal_llu = $sal_lal->sal_lalu;

        $asql = "SELECT terima-keluar as sisa FROM(select
      SUM(case when jns=1 then jumlah else 0 end) AS terima,
      SUM(case when jns=2 then jumlah else 0 end) AS keluar
      from (
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan
        UNION
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
       SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
       a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
       from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
             left join
       (
        select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
        -- inner join trhstrpot g on d.no_bukti=g.no_terima and d.kd_skpd=g.kd_skpd
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
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

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
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            ) a
      where month(tgl)<'$bulan' and kode='$kd_skpd') a";

        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $sisa = $bank->sisa;
        $saldobank = $sisa + $sal_llu;

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD $nm_skpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN 2022</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"6\" style=\"font-size:14px;border: solid 1px white;\">BUKU PEMBANTU BANK <br /> Periode : $lcperiode</td>
            </tr>
           
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px white;\">&nbsp;</td>
            </tr>
      </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"$spasi\">
    <thead>      
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"5%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >No.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >Tanggal.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">No. Bukti</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"30%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan</td> 
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran</td>  
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Saldo</td>            
            </tr> 
    </thead>
            <tr>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" ></td>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" ></td>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td>
                <td align=\"right\" width=\"35%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Saldo Lalu</td>
                <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td> 
                <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td>  
                <td align=\"right\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">" . number_format($saldobank, "2", ",", ".") . "</td>            
            </tr>";


        $sql = "SELECT * FROM (
           SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan
           UNION
           SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar  and c.kd_skpd=d.kd_skpd  where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku,'Terima'+ a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout 
       a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
       from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
             left join
       (
        select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd 
        inner join trhstrpot g on d.no_bukti=g.no_terima and d.kd_skpd=g.kd_skpd
        where e.kd_skpd='$kd_skpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
       ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd 
              WHERE pay='BANK' and 
             (panjar not in ('1') or panjar is null) 
             union 
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
             join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union  
select a.tgl_kas as tgl,a.no_kas as bku, a.keterangan as ket, SUM(nilai) as jumlah, '2' as jns, a.kd_skpd_sumber as kode 
from tr_setorpelimpahan_bank a where a.kd_skpd_sumber='$kd_skpd' 
GROUP BY a.tgl_kas,a.no_kas, a.keterangan,a.kd_skpd_sumber
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
            select a.tgl_sts as tgl,a.no_sts+1 as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  

      ) a
             where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,Cast(bku  as int), jns";

        $hasil = $this->db->query($sql);
        $saldo = $saldobank;
        $total_terima = 0;
        $total_keluar = 0;
        $nom = 0;
        foreach ($hasil->result() as $row) {
            $bku   = $row->bku;
            $tgl     = $row->tgl;
            $uraian  = $row->ket;
            $nilai   = $row->jumlah;
            $jns     = $row->jns;
            $nom = $nom + 1;
            if ($jns == 1) {
                $saldo = $saldo + $nilai;
                $total_terima = $total_terima + $nilai;
                $cRet .= "<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$nom</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$tgl</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$bku</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            } else {
                $saldo = $saldo - $nilai;
                $total_keluar = $total_keluar + $nilai;
                $cRet .= "<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$nom</td>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$tgl</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$bku</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            }
        }
        $cRet .= "<tr>
                                  <td bgcolor=\"#CCCCCC\" colspan=\"4\" valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">JUMLAH</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($total_terima + $saldobank, "2", ",", ".") . "</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($total_keluar, "2", ",", ".") . "</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format(($total_terima + $saldobank) - ($total_keluar), "2", ",", ".") . "</td>
                                  </tr>";
        $cRet .= "<tr>
                    <td align=\"left\" colspan=\"7\" style=\"font-size:12px;border:solid 1px white\">&nbsp;</td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"4\" style=\"font-size:13px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">&nbsp;</td>                    
                </tr>       
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:13px;border: solid 1px white;\">Disetujui oleh:</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">$daerah, " . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "</td>                                                                                                                                                                                
                </tr>
               <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">Pengguna Anggaran / Kuasa Pengguna Anggaran</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> NIP. $lcNipPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";
        $cRet .= '</table>';
        $data['prev'] = $cRet;
        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>Simpanan Bank </title>");
            echo $cRet;
        } else if ($print == 1) {
            $this->support->_mpdf('', $cRet, 10, 10, 10, '0', 0, '');
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=simpan_bank.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        }
    }





    function cetak_simpanan_bank()
    {
        $print = $this->uri->segment(3);
        $thn_ang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $nm_skpd = $this->session->userdata('nm_skpd');
        $bulan = $_REQUEST['tgl1'];
        $lcperiode = $this->tukd_model->getBulan($bulan);
        $tgl_ttd = $_REQUEST['tgl_ttd'];
        $spasi = $_REQUEST['spasi'];
        $ttd1 = str_replace('123456789', ' ', $_REQUEST['ttd1']);
        $ttd2 = str_replace('123456789', ' ', $_REQUEST['ttd2']);
        $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE a.kd_skpd = '$kd_skpd' and nip='$ttd1'";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $lcNmBP = $trh2->nama;
        $lcNipBP = $trh2->nip;
        $lcJabBP = $trh2->jabatan;
        $lcPangkatBP = $trh2->pangkat;
        $csql = "SELECT a.nama, a.nip, jabatan, pangkat FROM ms_ttd a WHERE kode in ('PA','KPA') AND a.kd_skpd = '$kd_skpd' and nip='$ttd2'";
        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $lcNmPA = $trh2->nama;
        $lcNipPA = $trh2->nip;
        $lcJabPA = $trh2->jabatan;
        $lcPangkatPA = $trh2->pangkat;

        $hasil = $this->db->query("SELECT * from ms_skpd where kd_skpd = '$kd_skpd'");
        $trsk = $hasil->row();
        $nm_skpd = $trsk->nm_skpd;

        $prv = $this->db->query("SELECT * from sclient WHERE kd_skpd='$kd_skpd'");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;

        $sal_ll = $this->db->query("SELECT isnull(sld_awal_bank,0)+sld_awalpajak AS sal_lalu 
                    FROM ms_skpd where kd_skpd='$kd_skpd'");
        $sal_lal = $sal_ll->row();
        $sal_llu = $sal_lal->sal_lalu;

        $asql = "SELECT terima-keluar as sisa FROM(select
      SUM(case when jns=1 then jumlah else 0 end) AS terima,
      SUM(case when jns=2 then jumlah else 0 end) AS keluar
      from (
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
      SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
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
      SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank union 

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union 

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
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            ) a
      where month(tgl)<'$bulan' and kode='$kd_skpd') a ";

        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $sisa = $bank->sisa;
        $saldobank = $sisa + $sal_llu;

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"6\" style=\"font-size:14px;border: solid 1px white;\"><b>PEMERINTAH KABUPATEN MELAWI<br>BUKU PEMBANTU SIMPANAN BANK<br>BENDAHARA PENGELUARAN</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            
            <tr>
                <td align=\"left\" colspan=\"0\" style=\"font-size:12px;border: solid 1px white;\">OPD</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">: $kd_skpd - $nm_skpd</td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"0\" style=\"font-size:12px;border: solid 1px white;\">PERIODE</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">: $lcperiode</td>
            </tr>
            
           
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px white;\">&nbsp;</td>
            </tr>
      </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"$spasi\">
    <thead>      
            <tr>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >Tanggal.</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">No. BKU</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"35%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan</td> 
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran</td>  
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Saldo</td>            
            </tr> 
    </thead>
            <tr>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" ></td>
                <td align=\"center\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td>
                <td align=\"right\" width=\"35%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Saldo Lalu</td>
                <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td> 
                <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\"></td>  
                <td align=\"right\" width=\"15%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">" . number_format($saldobank, "2", ",", ".") . "</td>            
            </tr>";


        /*              $sql = "SELECT * FROM (
        SELECT tgl_kas AS tgl,no_kas AS bku,no_sp2d AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhsp2d where jns_spp in('1','2','3','4') 
        union
                SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhtransout WHERE jns_spp='4' 
        UNION
                SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan ) a 
        where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,bku, jns";
 */
        $sql = "SELECT * FROM (
           SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union
           SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar  and c.kd_skpd=d.kd_skpd  where c.jns='2' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
             select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
             join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
             where a.kd_skpd='$kd_skpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
             union all
            select a.tgl_sts as tgl,a.no_sts as bku,'Terima'+ a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  union all
             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah, '2' AS jns,a.kd_skpd AS kode FROM trhtransout 
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
            select a.tgl_sts as tgl,a.no_sts+1 as bku, 'Setor '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd' 
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
            UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank WHERE kd_skpd_sumber='$kd_skpd' GROUP BY tgl_kas,no_kas,keterangan,nilai,kd_skpd_sumber

      ) a
             where month(a.tgl)='$bulan' and kode='$kd_skpd' ORDER BY a.tgl,Cast(bku  as int), jns";

        $hasil = $this->db->query($sql);
        $saldo = $saldobank;
        $total_terima = 0;
        $total_keluar = 0;
        foreach ($hasil->result() as $row) {
            $bku   = $row->bku;
            $tgl     = $row->tgl;
            $uraian  = $row->ket;
            $nilai   = $row->jumlah;
            $jns     = $row->jns;

            if ($jns == 1) {
                $saldo = $saldo + $nilai;
                $total_terima = $total_terima + $nilai;
                $cRet .= "<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$tgl</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$bku</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            } else {
                $saldo = $saldo - $nilai;
                $total_keluar = $total_keluar + $nilai;
                $cRet .= "<tr>
                                  <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$tgl</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$bku</td>
                                  <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$uraian</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"></td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($nilai, "2", ",", ".") . "</td>
                                  <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($saldo, "2", ",", ".") . "</td>
                                  </tr>";
            }
        }
        $cRet .= "<tr>
                                  <td bgcolor=\"#CCCCCC\" colspan=\"3\" valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">JUMLAH</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($total_terima + $saldobank, "2", ",", ".") . "</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format($total_keluar, "2", ",", ".") . "</td>
                                  <td bgcolor=\"#CCCCCC\" valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">" . number_format(($total_terima + $saldobank) - ($total_keluar), "2", ",", ".") . "</td>
                                  </tr>";
        $cRet .= "<tr>
                    <td align=\"left\" colspan=\"6\" style=\"font-size:12px;border:solid 1px white\">&nbsp;</td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>                
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">&nbsp;</td>                    
                </tr>       
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">Mengetahui:</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:13px;border: solid 1px white;\">$daerah, " . $this->support->tanggal_format_indonesia($tgl_ttd) . "</td>                                                                                                                                                                                
                </tr>
               <tr>                
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$lcJabPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">$lcJabBP</td>                    
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                    <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"></td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmPA</u></b><br>$lcPangkatPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"><b><u>$lcNmBP</u></b><br>$lcPangkatBP</td>
                </tr>
                <tr>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\"> NIP. $lcNipPA</td>
                    <td align=\"center\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;\">NIP. $lcNipBP</td>
      
                </tr>";
        $cRet .= '</table>';
        $data['prev'] = $cRet;
        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>Simpanan Bank </title>");
            echo $cRet;
        } else if ($print == 1) {
            $this->support->_mpdf('', $cRet, 10, 10, 10, '0', 0, '');
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=simpan_bank.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        }
    }
    //////////////////

}
