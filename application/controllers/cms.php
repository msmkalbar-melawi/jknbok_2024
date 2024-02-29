<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Cms extends CI_Controller
{

    public $org_keu = "";
    public $skpd_keu = "";

    function __contruct()
    {
        parent::__construct();
    }

    function index()
    {
        $data['page_title'] = 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI');
        $this->template->load('template', 'tukd/cms/transout_cmsbank', $data);
    }

    function cari_rekening()
    {
        $lccr =  $this->session->userdata('kdskpd');

        $sql = "SELECT top 1 rekening FROM ms_skpd where kd_skpd='$lccr' order by kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'rek_bend' => $resulte['rekening']
            );
        }
        echo json_encode($result);
    }

    function load_no_penagihan()
    { //wahyu
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
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_tagih' => $resulte['no_bukti'],
                'tgl_tagih' => $resulte['tgl_bukti'],
                'kd_skpd' => $resulte['kd_skpd'],
                'ket' => $resulte['ket'],
                'kegiatan' => $resulte['kd_sub_kegiatan'],
                'kontrak' => $resulte['kontrak'],
                'nila' => number_format($resulte['total'], 2, '.', ','),
                'nil' => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function cetak_listtransaksi()
    {
        $this->load->library('tanggal_indonesia');
        $kd_skpd = $this->session->userdata('kdskpd');
        $skpd_keu = $this->skpd_keu;
        $thn     = $this->session->userdata('pcThang');
        $tgl     = $this->uri->segment(3);
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>" . $kab . "<br>LIST TRANSAKSI</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE " . strtoupper($this->tanggal_indonesia->tanggal_format_indonesia($tgl)) . "</b></td>
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
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"14\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;" . strtoupper($this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd')) . "</td>
            </tr>
            </table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr> 
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold;\">SKPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"32%\" style=\"font-size:12px;font-weight:bold;\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"4%\" style=\"font-size:12px;font-weight:bold;\">ST</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;border-top:solid 1px black;\">7</td>
            </tr>
            </thead>";

        $no = 0;
        $tot_terima = 0;
        $tot_keluar = 0;
        $sql = "SELECT z.* from (
            select '1' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,a.no_sp2d kegiatan,'' rekening, a.ket, 0 terima, 0 keluar, a.jns_spp, a.status_upload
            from trhtransout_cmsbank a where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'
            UNION
            select '2' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,b.kd_sub_kegiatan kegiatan,b.kd_rek6 rekening, b.nm_sub_kegiatan+', '+b.nm_rek6, 0 terima, b.nilai keluar, a.jns_spp, '' status_upload
            from trhtransout_cmsbank a 
            left join trdtransout_cmsbank b on b.no_voucher=a.no_voucher and b.kd_skpd=a.kd_skpd
            where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'
            UNION
            select '3' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,'Rek. Tujuan :' kegiatan,'' rekening, RTRIM(a.rekening_tujuan)+' , AN : '+RTRIM(a.nm_rekening_tujuan), 0 terima, a.nilai keluar, '' jns_spp, '' status_upload
            from trdtransout_transfercms a where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'          
            UNION
            select '4' urut,a.kd_skpd,a.tgl_voucher,a.no_voucher,b.kd_sub_kegiatan kegiatan,c.kd_rek6 rekening, 'Terima '+c.nm_rek6, c.nilai terima, 0 keluar, '' jns_spp, '' status_upload
            from trhtransout_cmsbank a 
            inner join trhtrmpot_cmsbank b on b.no_voucher=a.no_voucher and b.kd_skpd=a.kd_skpd
            inner join trdtrmpot_cmsbank c on b.no_bukti=c.no_bukti and b.kd_skpd=c.kd_skpd
            where year(a.tgl_voucher) = '$thn' and a.tgl_voucher='$tgl' and a.kd_skpd='$kd_skpd'
            )z order by z.kd_skpd,z.tgl_voucher,cast (z.no_voucher as int), z.urut";
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $no = $no++;

            if ($row->urut == '1') {
                $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:none;\">" . $row->no_voucher . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->kd_skpd . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->kegiatan . "." . $row->rekening . "</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->ket . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->status_upload . "</td>                                       
                 </tr>";
            } else if ($row->urut == '3') {
                $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->kegiatan . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->ket . "&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . number_format($row->keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\"></td>                                       
                 </tr>";
            } else {
                $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . $row->kegiatan . "." . $row->rekening . "</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . $row->ket . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . number_format($row->terima, 2) . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . number_format($row->keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>                                        
                 </tr>";
            }

            if ($row->urut != '3') {
                $tot_terima = $tot_terima + $row->terima;
                $tot_keluar = $tot_keluar + $row->keluar;
            }
        }


        $asql = "select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
            c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='1' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
            select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai [jumlah],'1' [jns],kd_skpd [kode] from trhtrmpot 
            where kd_skpd='$kd_skpd' and pay='BANK' union all
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE jns='1' and kd_skpd='$kd_skpd' AND pay='BANK' union all
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
            join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) 
            c on b.no_spm=c.no_spm WHERE pay='BANK' union all 
            select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
            from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
            where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$kd_skpd'
            GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
            union all           
             select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai [jumlah],'2' [jns],kd_skpd [kode] from trhstrpot  
             where kd_skpd='$kd_skpd' and pay='BANK'
                    
            ) a
            where tgl<='$tgl' and kode='$kd_skpd'";

        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;

        $saldoakhirbank = (($saldobank + $tot_terima) - $tot_keluar);

        $cRet .= "
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border-top:1px solid black;\">JUMLAH</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">" . number_format($tot_terima, 2) . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">" . number_format($tot_keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"9\" style=\"font-size:11px;border:none;\"><br/></td>                                                   
                 </tr> 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Saldo Sampai Dengan Tanggal " . $this->tanggal_indonesia->tanggal_format_indonesia($tgl) . ", </td>                                                   
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($saldobank, 2) . "</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Terima</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($tot_terima, 2) . "</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Keluar</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($tot_keluar, 2) . "</td>                                                   
                 </tr>                                 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\"><hr/></td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Perkiraan Akhir Saldo, </td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($saldoakhirbank, 2) . "</td>                                                   
                 </tr>                                 
                                                  
            </table>";

        $data['prev'] = $cRet;
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         

    }

    function load_list_dtransout_transfercms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $bulan   = $this->input->post('bln1');
        $jnsbeban   = $this->input->post('jnsbeban');
        $kdgiat   = $this->input->post('kdgiat');

        if ($jnsbeban == '4') {
            $keg = "AND RIGHT(kd_sub_kegiatan,10)='01.1.02.01'";
        } else {
            $keg = "AND LEFT(b.kd_rek6,3)='521' AND b.kd_sub_kegiatan='$kdgiat'";
        }

        $sql = "SELECT *, (SELECT SUM(nilai) total FROM trdtransout_transfercms WHERE kd_skpd='$kd_skpd' AND MONTH(tgl_voucher)='$bulan' 
                AND no_voucher IN 
                ( SELECT TOP 1 b.no_voucher FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.kd_skpd=b.kd_skpd AND a.no_voucher=b.no_voucher 
                  WHERE b.kd_skpd='$kd_skpd' AND MONTH(a.tgl_voucher)='$bulan' $keg
                  GROUP BY b.no_voucher)) total 
                FROM (
                SELECT * FROM trdtransout_transfercms 
                WHERE kd_skpd='$kd_skpd' AND MONTH(tgl_voucher)='$bulan' 
                AND no_voucher IN 
                ( SELECT TOP 1 b.no_voucher FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.kd_skpd=b.kd_skpd AND a.no_voucher=b.no_voucher 
                  WHERE b.kd_skpd='$kd_skpd' AND MONTH(a.tgl_voucher)='$bulan' $keg 
                  GROUP BY b.no_voucher) )p
                ORDER BY nm_rekening_tujuan";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'                => $ii,
                'no_voucher'        => $resulte['no_voucher'],
                'tgl_voucher'       => $resulte['tgl_voucher'],
                'rekening_awal'     => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan'   => $resulte['rekening_tujuan'],
                'bank_tujuan'       => $resulte['bank_tujuan'],
                'nilai'             => number_format($resulte['nilai'], 2),
                'total'             => number_format($resulte['total'], 2),
                'kd_skpd'           => $resulte['kd_skpd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function hapus_transout_cms()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        if ($asg) {
            $sql = "delete from trhtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);

            $sql = "delete from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$kd_skpd'";
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

    function edit_transout()
    {
        $tabel    = $this->input->post('tabel');
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $skpd     = $skpd = $this->session->userdata('kdskpd');
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');

        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        $sql = "update trhtransout_cmsbank set ket='$ket' where kd_skpd='$skpd' and no_voucher='$nokas'";
        $asg = $this->db->query($sql);

        if (!($asg)) {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        }
    }

    function load_dtransout()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_user = $this->session->userdata('pcNama');

        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
                FROM trhtransout_cmsbank a INNER JOIN trdtransout_cmsbank b ON a.no_voucher=b.no_voucher 
                AND a.kd_skpd=b.kd_skpd
                WHERE a.no_voucher='$nomor' AND a.kd_skpd='$skpd'
                ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_voucher'    => $resulte['no_voucher'],
                'no_sp2d'       => $resulte['no_sp2d'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek6'       => $resulte['kd_rek6'],
                'nm_rek6'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai'],
                'nilai_nformat' => number_format($resulte['nilai'], 2),
                'sumber'        => $resulte['sumber'],
                'lalu'          => $resulte['lalu'],
                'sp2d'          => $resulte['sp2d'],
                'anggaran'      => $resulte['anggaran'],
                'anggaran'      => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtransout_transfercms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,(select sum(nilai) from trdtransout_transfercms where no_voucher=b.no_voucher and kd_skpd=b.kd_skpd) as total
                FROM trhtransout_cmsbank a INNER JOIN trdtransout_transfercms b ON a.no_voucher=b.no_voucher
                AND a.kd_skpd=b.kd_skpd 
                WHERE b.no_voucher='$nomor' AND b.kd_skpd='$skpd'
                group by b.no_voucher,b.tgl_voucher,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai
                ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'                => $ii,
                'no_voucher'        => $resulte['no_voucher'],
                'tgl_voucher'       => $resulte['tgl_voucher'],
                'rekening_awal'     => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan'   => $resulte['rekening_tujuan'],
                'bank_tujuan'       => $resulte['bank_tujuan'],
                'nilai'             => number_format($resulte['nilai'], 2),
                'total'             => number_format($resulte['total'], 2),
                'kd_skpd'           => $resulte['kd_skpd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function cari_rekening_tujuan($jenis = '')
    {
        $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');

        if ($jenis == 1) {
            $jenis = "('1','2')";
        } else {
            $jenis = "('3')";
        }

        $sql = "
		SELECT * FROM (
		SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,
        a.keterangan,a.kd_skpd,a.jenis FROM ms_rekening_bank a where kd_skpd='$skpd') a
		WHERE upper(rekening) like upper('%$lccr%') or upper(nm_rekening) like upper('%$lccr%') or upper(bank) like upper('%$lccr%') or upper(nmbank) like upper('%$lccr%')
         order by a.nm_rekening";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {

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

    function cari_bank()
    {
        $sql = "SELECT kode,nama FROM ms_bank";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama']
            );
        }

        echo json_encode($result);
    }

    function rek_pot()
    {
        $lccr   = $this->input->post('q');
        $sql    = " SELECT kd_rek6,nm_rek6 FROM ms_pot where ( upper(kd_rek6) like upper('%$lccr%')
                    OR upper(nm_rek6) like upper('%$lccr%') )  ";
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

    function skpd_3()
    {
        $lccr = $this->input->post('q');
        $kd_skpd = $this->session->userdata('kdskpd');
        $dkd_skpd = substr($kd_skpd, 0, 17);
        $sql = "SELECT substring(kd_skpd,1,4)+substring(kd_skpd,15,8) as kd_ringkas,kd_skpd,nm_skpd FROM ms_skpd where left(kd_skpd,17)='$dkd_skpd' and kd_skpd not in ('$kd_skpd') order by kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'kd_skpd_sumber' => $kd_skpd,
                'kd_ringkas' => $resulte['kd_ringkas']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }



    function load_dpot()
    {
        $nomor = $this->input->post('no');
        $sql = "select * from trdtrmpot where no_bukti='$nomor' ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
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

    function load_trskpd()
    {
        $jenis = $this->input->post('jenis');
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');
        $data = $this->cek_anggaran_model->cek_anggaran($cskpd);

        $jns_beban = '';
        $cgiat = '';
        if ($jenis == 4) {
            $jns_beban = "and b.jns_sub_kegiatan='5'";
        } else {
            $jns_beban = "and b.jns_sub_kegiatan='5'";
        }
        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,(select nm_program from ms_program where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                WHERE a.jns_ang='$data' and a.kd_skpd='$cskpd' AND a.status_sub_kegiatan='1'  $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

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


    function load_sp2d_transout()
    {
        //$beban='',$giat=''
        $beban   = $this->input->post('jenis');
        $giat    = $this->input->post('giat');
        $tgl     = $this->input->post('tgl');
        $kode    = substr($this->input->post('kd'), 0, 17);
        $bukti   = $this->input->post('bukti');
        $where   = '';
        if ($beban == '1') {
            $sisa = "c.nilai + (SELECT SUM(ISNULL (v.nilai,0)) FROM trhspp z INNER JOIN trhspm s ON z.no_spp=s.no_spp AND z.kd_skpd=s.kd_skpd
                INNER JOIN trhsp2d v ON s.no_spm=v.no_spm AND s.kd_skpd=v.kd_skpd WHERE z.jns_spp IN ('1','2') AND z.kd_skpd=c.kd_skpd )
                -(SELECT SUM(ISNULL (nilai,0)) FROM trdtransout WHERE no_sp2d = c.no_sp2d and no_bukti <> '$bukti') AS sisa";
        } else {
            $sisa = "c.nilai -(SELECT SUM(ISNULL (nilai,0))FROM trdtransout WHERE no_sp2d = c.no_sp2d and no_bukti <> '$bukti') AS sisa";
        }
        if (($beban != '' && $giat == '') || ($beban == '1')) {
            $where = " and a.jns_spp IN ('1','2')";
        }
        if ($giat != '' && $beban != '1') {
            $where = " and a.jns_spp='$beban' and d.kd_sub_kegiatan='$giat'";
        }
        $kriteria = $this->input->post('q');
        if ($beban == '3') {
            $sql = "SELECT DISTINCT c.no_sp2d,c.tgl_sp2d,c.nilai,
                    0 as sisa                   
                    FROM trhspp a 
                    INNER JOIN trhspm b ON a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
                    INNER JOIN trhsp2d c ON b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trdspp d ON a.no_spp=d.no_spp AND a.kd_skpd=d.kd_skpd
                    WHERE left(c.kd_skpd,17) = '$kode' AND c.status = 1 $where 
                    AND c.no_sp2d 
                    NOT IN (SELECT no_sp2d FROM trhlpj WHERE left(kd_skpd,17)='$kode')
                    AND c.no_sp2d_tu_nihil IS NULL
                    ORDER BY c.tgl_sp2d DESC, c.no_sp2d";
        } else if ($beban == '6') {
            $sql = "SELECT DISTINCT c.no_sp2d,c.tgl_sp2d,c.nilai,
                    0 as sisa                   
                    FROM trhspp a 
                    INNER JOIN trhspm b ON a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
                    INNER JOIN trhsp2d c ON b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trdspp d ON a.no_spp=d.no_spp AND a.kd_skpd=d.kd_skpd
                    WHERE left(c.kd_skpd,17) = '$kode' AND c.status = 1 $where ORDER BY c.tgl_sp2d DESC, c.no_sp2d";
        } else {
            $sql = "SELECT DISTINCT c.no_sp2d,c.tgl_sp2d,c.nilai,
                    0 as sisa                   
                    FROM trhspp a 
                    INNER JOIN trhspm b ON a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
                    INNER JOIN trhsp2d c ON b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd
                    INNER JOIN trdspp d ON a.no_spp=d.no_spp AND a.kd_skpd=d.kd_skpd
                    WHERE left(c.kd_skpd,17) = '$kode' AND c.status = 1 $where ORDER BY c.tgl_sp2d DESC, c.no_sp2d";
        }

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

    function load_rek()
    {
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');


        $jns_ang = $this->cek_anggaran_model->cek_anggaran($kode);



        if ($rek != '') {
            $notIn = " and kd_rek6 not in ($rek) ";
        } else {
            $notIn  = "";
        }

        if ($jenis == '1') {
            $sql = "SELECT a.kd_rek6,a.nm_rek6,
                    (SELECT SUM(nilai) FROM 
						(SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout c
						LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
						AND c.kd_skpd = d.kd_skpd
						WHERE
							c.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND d.kd_skpd = a.kd_skpd
						AND c.kd_rek6 = a.kd_rek6
						AND d.jns_spp='$jenis'
						UNION ALL
					SELECT SUM(nilai) FROM 
						(SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout_cmsbank c
						LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
						AND c.kd_skpd = d.kd_skpd
						WHERE
							c.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND d.kd_skpd = a.kd_skpd
						AND c.kd_rek6 = a.kd_rek6
						AND c.no_voucher <> '$nomor'
						AND d.jns_spp='$jenis'
						AND d.status_validasi<>'1'
						UNION ALL
						SELECT SUM(x.nilai) as nilai FROM trdspp x
						INNER JOIN trhspp y 
						ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
						WHERE
							x.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND x.kd_skpd = a.kd_skpd
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
						)r) r) AS lalu,
						0 AS sp2d,nilai AS anggaran
						FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat' 
                        AND a.kd_skpd = '$kode'
                        --  $notIn 
                        and a.status_aktif='1' 
                        and jns_ang='$jns_ang' 
                        -- and left(kd_rek6,2)<>'52'
                        order by a.kd_rek6";
        } else {
            $sql = "SELECT kd_rek6, nm_rek6,
            

            (SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout c
                        LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                            c.kd_sub_kegiatan = x.kd_sub_kegiatan
                        AND d.kd_skpd = x.kd_skpd
                        AND c.kd_rek6 = x.kd_rek6
                        AND d.jns_spp='$jenis'
                        and d.no_sp2d = '$sp2d'
                        UNION ALL
                        
                        SELECT SUM(nilai) FROM 
                        (SELECT
                            SUM (c.nilai) as nilai
                        FROM
                            trdtransout_cmsbank c
                        LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                        AND c.kd_skpd = d.kd_skpd
                        WHERE
                            c.kd_sub_kegiatan = x.kd_sub_kegiatan
                        AND d.kd_skpd = x.kd_skpd
                        AND c.kd_rek6 = x.kd_rek6
                        AND c.no_voucher <> '$nomor'
                        AND d.jns_spp='$jenis'
                        AND d.status_validasi<>'1'
                        and d.no_sp2d = '$sp2d'
                        )r

                        ) r) AS lalu,

            sp2d, 0 AS anggaran  from(               
            SELECT b.kd_skpd,b.kd_sub_kegiatan,b.kd_rek6,b.nm_rek6, sum(b.nilai) AS sp2d, 0 AS anggaran
            FROM trhspp a 
            INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
            INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
            INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd 
            WHERE d.no_sp2d = '$sp2d' and b.kd_sub_kegiatan='$giat' 
            group by b.kd_skpd,b.kd_sub_kegiatan,b.kd_rek6,b.nm_rek6
            )x ";
        }
        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'lalu' => $resulte['lalu'],
                'sp2d' => $resulte['sp2d'],
                'anggaran' => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function ambil_sdana()
    {

        $lccr  = ''; //$this->input->post('q');
        $tgl = $this->input->post('tgl');
        $skpd = $this->input->post('skpd');
        $giat = $this->input->post('giat');
        $kdrek5 = $this->input->post('kdrek5');
        $jns_ang   = $this->cek_anggaran_model->cek_anggaran($skpd);

        $giat   = $this->input->post('giat');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');

        $sql = "SELECT * from ( select a.sumber as sumber_dana,isnull(SUM(a.total),0) as nilai, a.kd_rek6 as kd_rek6, (SELECT SUM(nilai) as nilai FROM trdtagih t INNER JOIN trhtagih u ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE t.kd_sub_kegiatan = '$giat' AND u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber)as lalu from trdpo a where a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and a.kd_skpd='$skpd' and a.jns_ang='$jns_ang' GROUP BY a.sumber, a.kd_rek6)z where z.nilai<>0";
        // echo $sql = "
        //      SELECT * from (
        //     select sumber1 as sumber_dana,isnull(nsumber1,0) as nilai, a.kd_rek6 as kd_rek6,
        //         (SELECT SUM(nilai) as nilai FROM trdtagih t INNER JOIN trhtagih u ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE t.kd_sub_kegiatan = '$giat' AND 
        //         u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber1)as lalu
        //     from trdrka a  where a.kd_sub_kegiatan='$giat' and a.kd_rek6='$kdrek5'  and a.kd_skpd='$skpd' and a.jns_ang='$data'
        //     union ALL 
        //     select sumber2 as sumber_dana, isnull(nsumber2,0) as nilai, a.kd_rek6 as kd_rek6,
        //         (SELECT SUM(nilai) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
        //         u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber2)as lalu
        //     from trdrka a where a.kd_sub_kegiatan='$giat'  and a.kd_rek6='$kdrek5' and a.kd_skpd='$skpd' and a.jns_ang='$data'
        //     union ALL 
        //     select sumber3 as sumber_dana,isnull(nsumber3,0) as nilai, a.kd_rek6 as kd_rek6,
        //         (SELECT SUM(nilai) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
        //         u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber3)as lalu
        //     from trdrka a where a.kd_sub_kegiatan='$giat' and a.kd_rek6='$kdrek5' and a.kd_skpd='$skpd' and a.jns_ang='$data' 
        //     union ALL 
        //     select sumber4 as sumber_dana,isnull(nsumber4,0) as nilai, a.kd_rek6 as kd_rek6,
        //     (SELECT SUM(nilai) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
        //     u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber4)as lalu
        //     from trdrka a where a.kd_sub_kegiatan='$giat' and a.kd_rek6='$kdrek5' and a.kd_skpd='$skpd' and a.jns_ang='$data')z where z.nilai<>0

        //     ";

        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'sumber_dana'       => $resulte['sumber_dana'],
                'nmsumber'      => $this->tukd_model->get_nama($resulte['sumber_dana'], 'nm_sumber_dana1', 'sumber_dana', 'kd_sumber_dana1'),
                'nilaidana'         => $resulte['nilai'],
                'rek6'      => $resulte['kd_rek6']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtagih()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kd_skpd);
        $nomor = $this->input->post('no');
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtagih c LEFT JOIN trhtagih d ON c.no_bukti=d.no_bukti WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek6=b.kd_rek AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_sub_kegiatan=b.kd_sub_kegiatan AND f.kd_rek6=b.kd_rek6) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE jns_ang='$data' AND kd_sub_kegiatan = b.kd_sub_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek6=b.kd_rek) AS anggaran FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_bukti'      => $resulte['no_bukti'],
                'no_sp2d'       => $resulte['no_sp2d'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek6'       => $resulte['kd_rek6'],
                'kd_rek'        => $resulte['kd_rek'],
                'nm_rek6'       => $resulte['nm_rek6'],
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

    function no_urut_cms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $tgl = date('Y-m-d');
        $query1 = $this->db->query("SELECT case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
	select no_voucher nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' union
    select no_bukti nomor, 'Potongan Pajak Transaksi Non Tunai' ket, kd_skpd from trhtrmpot_cmsbank where kd_skpd = '$kd_skpd' union
    select no_panjar nomor, 'Daftar Panjar' ket, kd_skpd from tr_panjar_cmsbank where kd_skpd = '$kd_skpd' 
    ) z WHERE KD_SKPD = '$kd_skpd'");
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


    function no_urut_tglcms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        date_default_timezone_set("Asia/Bangkok");
        $tgl = date('Y-m-d');
        $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
	select no_tgl nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where isnumeric(no_panjar)=1 and kd_skpd = '$kd_skpd' and tgl_voucher='$tgl') z WHERE KD_SKPD = '$kd_skpd'");
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

    function load_sisa_bank()
    {


        $lcskpd  = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT
        SUM(case when jns=1 then jumlah else 0 end) AS terima,
        SUM(case when jns=2 then jumlah else 0 end) AS keluar
        from (
              SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan 
              union all
              SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' 
              union all
              select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='2' and c.kd_skpd='$lcskpd' and  d.pay='BANK' 
              union all
              select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] from trhtrmpot a 
              join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
              where a.kd_skpd='$lcskpd' and a.pay='BANK' and jns_spp not in('1','2','3') group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
              union all
              select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
              from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
              where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
              GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
              union all
              SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0)-isnull(f.pot2,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot 
                  from trspmpot group by no_spm) c on b.no_spm=c.no_spm 
                  left join
                      (select d.no_kas,sum(e.nilai) [pot2],d.kd_skpd from trhtrmpot d join trdtrmpot e on d.no_bukti=e.no_bukti and d.kd_skpd=e.kd_skpd where e.kd_skpd='$lcskpd' and d.no_kas<>'' and d.pay='BANK' group by d.no_kas,d.kd_skpd
                      ) f on f.no_kas=a.no_bukti and f.kd_skpd=a.kd_skpd WHERE pay='BANK' and (panjar not in ('1') or panjar is null) 
               union all
              select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
              join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
              where a.kd_skpd='$lcskpd' and a.pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd
              UNION ALL
              SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan 
              union all
              SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK' 
              union all
              SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank 
              union all
              SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' 
              union all
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
              GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
              union all           
              select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
              from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
              where jns_trans IN ('5') and bank='BNK' and a.kd_skpd='$lcskpd' 
              GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd  
              ) a
          where  kode='$lcskpd'");

        //query new
        //             $query1 = $this->db->query("SELECT
        //             SUM(case when jns=1 then jumlah else 0 end) AS terima,
        //             SUM(case when jns=2 then jumlah else 0 end) AS keluar
        //             from (
        //             select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND kd_skpd='$kd_skpd' UNION ALL
        //             select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE kd_skpd='$kd_skpd' UNION ALL


        //             select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
        //                     from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
        //                     where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') AND bank='BNK' AND a.kd_skpd='$kd_skpd'
        //                     GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 

        //                     UNION ALL   
        //             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE kd_skpd='$kd_skpd' union ALL
        //             SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and panjar not in ('3') UNION ALL
        // /*            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE kd_skpd='$kd_skpd' and pay='BANK' union ALL   */                               
        //             SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' UNION ALL
        //             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE kd_skpd='$kd_skpd' AND status_drop !='1' union all
        //             SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank WHERE kd_skpd='$kd_skpd'
        //             ) a
        //                 where kode='$kd_skpd'");


        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'sisa' => number_format(($resulte['terima'] - $resulte['keluar']), 2, '.', ',')
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }


    function load_transout()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_voucher like '%$kriteria%' or upper(a.ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhtransout_cmsbank a where a.panjar = '0' AND kd_skpd='$kd_skpd' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,a.status_upload ketup,
		a.status_validasi ketval FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout_cmsbank a  
        WHERE  a.panjar = '0' AND kd_skpd='$kd_skpd' $where order by CAST (a.no_bukti as NUMERIC))  order by tgl_voucher,CAST (a.no_bukti as NUMERIC),kd_skpd ";

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


    function pot()
    {
        $kd_skpd    = $this->session->userdata('kdskpd');
        $spm        = $this->input->post('spm');
        $sql        = "SELECT * FROM trspmpot where no_spm='$spm' AND kd_skpd='$kd_skpd' order by kd_rek6 ";
        $query1     = $this->db->query($sql);
        $result     = array();
        $ii         = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6'   => $resulte['kd_rek6'],
                'kd_trans'  => $resulte['kd_trans'],
                'nm_rek6'   => $resulte['nm_rek6'],
                'pot'       => $resulte['pot'],
                'nilai'     => $resulte['nilai']
            );
            $ii++;
        }

        echo json_encode($result);
        //$query1->free_result();   
    }


    // ---------------------------------------------------


    // DROPPING DANA

    //setor dana bank
    function setor_simpanan_bidang()
    {
        $data['page_title'] = 'INPUT SETOR NON TUNAI';
        $this->template->set('title', 'INPUT SETOR NON TUNAI');
        $this->template->load('template', 'tukd/cms/bnk_setor_simpanan_bidang', $data);
    }

    function upload_setor_simpanan_bidang()
    {
        $data['page_title'] = 'UPLOAD SETOR NON TUNAI';
        $this->template->set('title', 'UPLOAD SETOR NON TUNAI');
        $this->template->load('template', 'tukd/cms/bnk_upload_bidang', $data);
    }

    function validasi_setor_simpanan_bidang()
    {
        $data['page_title'] = 'VALIDASI SETOR NON TUNAI';
        $this->template->set('title', 'VALIDASI SETOR NON TUNAI');
        $this->template->load('template', 'tukd/cms/bnk_validasi_bidang', $data);
    }

    function load_setorbidang_bnk()
    {
        $result = array();
        $row = array();
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

        $sql = "SELECT count(*) as tot from tr_setorpelimpahan_bank_cms where kd_skpd_sumber='$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows * from tr_setorpelimpahan_bank_cms where kd_skpd_sumber='$kd_skpd' $where and no_kas not in (
                SELECT TOP $offset no_kas from tr_setorpelimpahan_bank_cms WHERE kd_skpd_sumber='$kd_skpd' $where order by tgl_kas,cast(no_kas as int)) order by tgl_kas,cast(no_kas as int),kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;



        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id'          => $ii,
                'no_kas'      => $resulte['no_kas'],
                'tgl_kas'     => $resulte['tgl_kas'],
                'kd_skpd'     => $resulte['kd_skpd'],
                'nilai'       => number_format($resulte['nilai']),
                'nilai2'       => $resulte['nilai'],
                'keterangan'  => $resulte['keterangan'],
                'kd_skpd_sumber'    => $kd_skpd,
                'jenis_spp'      => $resulte['jenis_spp'],
                'ket_tujuan'      => $resulte['ket_tujuan'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'status_validasi' => $resulte['status_validasi'],
                'status_upload' => $resulte['status_upload']
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }



    function cari_rekening_pend()
    {
        $lccr =  $this->session->userdata('kdskpd');
        $sql = "SELECT top 1 rekening_pend FROM ms_skpd where left(kd_skpd,17)=left('$lccr',17) order by kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'rek_bend' => $resulte['rekening_pend']
            );
        }
        echo json_encode($result);
    }





    function cari_rekening_tujuan_kasda($jenis)
    {
        $skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,a.kd_skpd,a.jenis FROM ms_rekening_bank a where a.jenis='$jenis'";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'rekening' => $resulte['rekening'],
                'nm_rekening' => $resulte['nm_rekening'],
                'bank' => $resulte['bank'],
                'nmbank' => $resulte['nmbank'],
                'kd_skpd' => $resulte['kd_skpd'],
                'jenis' => $resulte['jenis']
            );
        }

        echo json_encode($result);
    }


    function load_jns_spp_drop()
    {

        $result = array((array(
                "id"   => 1,
                "jns" => " UP/GU"
            )
            ),
            (array(
                "id"   => 3,
                "jns" => " TU"
            )
            ),
            (array(
                "id"   => 4,
                "jns" => " LS GAJI"
            )
            ),
            (array(
                "id"   => 6,
                "jns" => " LS Barang Jasa"
            )
            )
        );

        echo json_encode($result);
    }

    function load_jns_setor_drop()
    {

        $result = array((array(
                "id"   => 4,
                "jns" => "Setor Ke Kas BP"
            )
            ),
            (array(
                "id"   => 5,
                "jns" => "Setor Ke Kas Daerah"
            )
            )
        );

        echo json_encode($result);
    }


    function load_sisa_bank_transcms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $skpdbp = substr($kd_skpd, 18, 4);
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "kode='$kd_skpd'";
            if ($skpdbp == "0000") {
                $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
            } else {
                $init_skpd = "kode='$kd_skpd'";
            }
        } else {
            $init_skpd = "left(kode,17)=left('$kd_skpd',17)";
        }

        $query1 = $this->db->query("SELECT sum(b.terima) terima,sum(b.keluar) keluar,sum(b.terima-b.keluar) saldo from(
SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' AS jns,kd_skpd as kode from tr_panjar where left(kd_skpd,17)=left('$kd_skpd',17)   UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '1' as jns,kd_skpd as kode from tr_jpanjar where jns=2 AND left(kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            select tgl_kas as tgl,no_kas as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd_sumber as kode from tr_setorpelimpahan_bank_cms where kd_skpd_sumber='$kd_skpd' and status_validasi=0  UNION ALL

            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd_sumber AS kode FROM tr_setorpelimpahan_bank where left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' and panjar not in ('3') and left(a.kd_skpd,17)=left('$kd_skpd',17) UNION ALL
            SELECT tgl_voucher AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout_cmsbank a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, kd_skpd, sum(nilai)pot from trspmpot group by no_spm,kd_skpd) c on b.no_spm=c.no_spm AND b.kd_skpd=c.kd_skpd WHERE pay='BANK' and status_validasi='0' and left(a.kd_skpd,17)=left('$kd_skpd',17)  UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' and left(kd_skpd,17)=left('$kd_skpd',17) union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' and left(kd_skpd,17)=left('$kd_skpd',17)  union all
            select a.tgl_sts as tgl,a.no_sts as bku, 'CP '+a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
                    from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
                    where jns_trans NOT IN('4','2','5') and pot_khusus in ('0','2') and left(a.kd_skpd,17)=left('$kd_skpd',17) 
                    GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd
                     ) a
            where $init_skpd)b");
        //}

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



    function simpan_ambil_simpanan_bidang_bnk()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd_sumber='$kd_skpd'";
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

    function update_ambilsimpanan_bnk()
    {
        $query  = $this->input->post('st_query');
        $asg    = $this->db->query($query);
        if (!$asg) {
            echo "0";
        } else {
            echo "1";
        }
    }

    function update_ambilsimpanan()
    {
        $query  = $this->input->post('st_query');
        $query1 = $this->input->post('st_query1');
        $asg    = $this->db->query($query);
        $asg1   = $this->db->query($query1);
    }

    function hapus_ambilsimpanan_bidang_bnk()
    {
        $no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $tabel = $this->input->post('tabel');
        $query = $this->db->query("delete from $tabel where no_kas='$no' and kd_skpd='$skpd' ");
        // $query->free_result();
    }



    function cetak_listsimpanan_bank()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $thn     = $this->session->userdata('pcThang');
        $tgl     = $this->uri->segment(3);
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd=left('$kd_skpd',17)+'.0000'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>" . $kab . "<br>LIST PENYETORAN DANA SIMPANAN BANK</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE " . strtoupper($this->tukd_model->tanggal_format_indonesia($tgl)) . "</b></td>
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
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"14\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;" . strtoupper($this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd')) . "</td>
            </tr>
            </table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr> 
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold;\">SKPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Rekening Tujuan </td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"32%\" style=\"font-size:12px;font-weight:bold;\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"4%\" style=\"font-size:12px;font-weight:bold;\">ST</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;border-top:solid 1px black;\">7</td>
            </tr>
            </thead>";

        $no = 0;
        $tot_terima = 0;
        $tot_keluar = 0;
        $sql = "SELECT  z.* from (
            select '1' urut,a.kd_skpd,a.tgl_bukti,a.no_bukti, a.rekening_tujuan+', an. '+a.nm_rekening_tujuan [tujuan], a.keterangan, a.nilai keluar, a.status_upload
            from tr_setorpelimpahan_bank_cms a 
            where year(a.tgl_bukti) = '$thn' and a.tgl_bukti='$tgl' and LEFT(a.kd_skpd,17)=LEFT('$kd_skpd',17)             
            )z order by z.kd_skpd,z.tgl_bukti,z.no_bukti,z.urut";
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $no = $no++;

            if ($row->urut == '1') {
                $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->no_bukti . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->kd_skpd . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->tujuan . "</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->keterangan . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . number_format($row->keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->status_upload . "</td>                                       
                 </tr>";
            }

            $tot_keluar = $tot_keluar + $row->keluar;
        }
        $asql = "SELECT 
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan WHERE status_drop!='1' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK') a
            where tgl<='$tgl' and left(kode,17)=left('$kd_skpd',17)";

        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;

        $saldoakhirbank = (($saldobank + $tot_terima) - $tot_keluar);

        $cRet .= "
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border-top:1px solid black;\">JUMLAH</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">" . number_format($tot_keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"9\" style=\"font-size:11px;border:none;\"><br/></td>                                                   
                 </tr>                                                  
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\"><hr/></td>                                                   
                 </tr>                                                        
            </table>";

        $data['prev'] = $cRet;
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         

    }


    function no_urut()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        if ($kd_skpd == '1.01.2.22.0.00.01.0000' || $kd_skpd == '4.01.0.00.0.00.01.0003' || $kd_skpd == '1.02.0.00.0.00.02.0000') {
            $query1 = $this->db->query("SELECT  case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
        
        -- select no_bukti nomor, 'Transaksi BOS' ket, kd_skpd from trhtransout_blud where  isnumeric(no_bukti)=1 AND panjar ='3' and kd_satdik is not null 
        -- union ALL
        -- select no_bukti nomor, 'SPB HIBAH' ket, kd_skpd from trhspb_hibah_skpd where  isnumeric(no_bukti)=1  
    
        -- union ALL
        -- select no_bukti nomor, 'SPB HIBAH' ket, kd_skpd from trhsp2b where  isnumeric(no_bukti)=1  
    
        -- union ALL
        select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
        select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
        select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
        select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
        select no_panjar nomor, 'Pemberian Panjar' ket,kd_skpd from tr_panjar where  isnumeric(no_panjar)=1  union ALL
        select no_panjar nomor, 'Pemberian Panjar CMS' ket,kd_skpd from tr_panjar_cmsbank where  isnumeric(no_panjar)=1  union ALL
        select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
        select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
        select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
        select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
        select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
        select no_bukti+1 nomor, 'Ambil Simpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND (status_drop !='1' OR status_drop is null) union ALL
        select no_bukti nomor, 'Ambil Drop Dana' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop ='1' union ALL
        select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
        select no_kas nomor, 'Setor Simpanan CMS' ket,kd_skpd_sumber kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_bukti)=1 union all
        select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
        select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='3' union ALL
        select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
        select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
        select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_kas)=1 union all
    select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE KD_SKPD = '$kd_skpd'");
        } else {
            $query1 = $this->db->query("SELECT  case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
                select no_kas nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas)=1 and status=1 union ALL
                select no_terima nomor,'Penerimaan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_terima)=1 and status_terima=1 union ALL
                select no_bukti nomor, 'Pembayaran Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND (panjar !='3' OR panjar IS NULL) union ALL
                select no_bukti nomor, 'Koreksi Transaksi' ket, kd_skpd from trhtransout where  isnumeric(no_bukti)=1 AND panjar ='3' union ALL
                select no_panjar nomor, 'Pemberian Panjar' ket,kd_skpd from tr_panjar where  isnumeric(no_panjar)=1  union ALL
                select no_panjar nomor, 'Pemberian Panjar CMS' ket,kd_skpd from tr_panjar_cmsbank where  isnumeric(no_panjar)=1  union ALL
                select no_kas nomor, 'Pertanggungjawaban Panjar' ket, kd_skpd from tr_jpanjar where  isnumeric(no_kas)=1 union ALL
                select no_bukti nomor, 'Penerimaan Potongan' ket,kd_skpd from trhtrmpot where  isnumeric(no_bukti)=1  union ALL
                select no_bukti nomor, 'Penyetoran Potongan' ket,kd_skpd from trhstrpot where  isnumeric(no_bukti)=1 union ALL
                select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 union ALL
                select no_sts+1 nomor, 'Setor Sisa Kas' ket,kd_skpd from trhkasin_pkd where  isnumeric(no_sts)=1 and jns_trans<>4 and pot_khusus=1 union ALL
                select no_bukti+1 nomor, 'Ambil Simpanan' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND (status_drop !='1' OR status_drop is null) union ALL
                select no_bukti nomor, 'Ambil Drop Dana' ket,kd_skpd from tr_ambilsimpanan where  isnumeric(no_bukti)=1 AND status_drop ='1' union ALL
                select no_kas nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 union all
                select no_kas nomor, 'Setor Simpanan CMS' ket,kd_skpd_sumber kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_bukti)=1 union all
                select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='2' union ALL
                select no_kas+1 nomor, 'Setor Simpanan' ket,kd_skpd from tr_setorsimpanan where  isnumeric(no_bukti)=1 and jenis='3' union ALL
                select NO_BUKTI nomor, 'Terima lain-lain' ket,KD_SKPD as kd_skpd from TRHINLAIN where  isnumeric(NO_BUKTI)=1 union ALL
                select NO_BUKTI nomor, 'Keluar lain-lain' ket,KD_SKPD as kd_skpd from TRHOUTLAIN where  isnumeric(NO_BUKTI)=1 union ALL
                select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan_bank_cms where  isnumeric(no_kas)=1  
                union all
            select no_kas nomor, 'Drop Uang ke Bidang' ket,kd_skpd_sumber as kd_skpd from tr_setorpelimpahan where  isnumeric(no_kas)=1) z WHERE KD_SKPD = '$kd_skpd'");
        }

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


    function load_listsetor_upload_cms()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_bukti='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');

        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT top $rows a.* FROM tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and 
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' $and order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_upload'] == 1) {
                $stt = "&#10004";
            } else {
                $stt = "X";
            }

            if ($resulte['status_validasi'] == 1) {
                $stt_val = "&#10004";
            } else {
                $stt_val = "X";
            }

            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'], 'nm_skpd', 'ms_skpd', 'kd_skpd');

            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $nmskpd,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['keterangan'],
                'total' => number_format($resulte['nilai'], 2),
                'status_upload' => $stt,
                'status_uploadx' => $resulte['status_upload'],
                'tgl_upload' => $resulte['tgl_upload'],
                'status_validasi' => $stt_val,
                'status_validasix' => $resulte['status_validasi'],
                'tgl_validasi' => $resulte['tgl_validasi'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan']

            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_hdraf_upload_bidang()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_upload='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');

        $sql = "SELECT count(*) as total from trhupload_cmsbank_bidang a
        where a.kd_skpd='$skpd' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT a.* FROM trhupload_cmsbank_bidang a               
        where a.kd_skpd='$skpd' $and 
        and a.no_upload+a.no_upload_tgl in (SELECT a.no_upload+a.no_upload_tgl FROM trdupload_cmsbank_bidang a  
        WHERE a.kd_bp='$skpd')
        order by cast(a.no_upload as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'no_upload' => $resulte['no_upload'],
                'no_upload_tgl' => $resulte['no_upload_tgl'],
                'tgl_upload' => $resulte['tgl_upload'],
                'total' => number_format($resulte['total'], 2)
            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_list_telahupload()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $kriteria = $this->input->post('pcNama');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_upload='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');
        $user = $this->session->userdata('pcNama');
        $cek_skpd = $this->db->query("SELECT count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "a.kd_skpd='$skpd'";
        } else {
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }

        $sql = "SELECT c.no_upload,count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher 
        where $init_skpd and a.status_upload='1' and a.status_validasi='0'  $and group by c.no_upload";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan,b.nm_sub_kegiatan,c.no_upload,c.no_upload_tgl,a.username FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on c.kd_skpd=a.kd_skpd and a.no_voucher=c.no_voucher 
        where $init_skpd and a.status_upload='1' and a.status_validasi='0'  $and 
group by 
a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,b.kd_sub_kegiatan,b.nm_sub_kegiatan,c.no_upload,c.no_upload_tgl,a.username       
        order by cast(c.no_upload as int),cast(a.no_voucher as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_upload'] == 1) {
                $stt = "&#10004";
            } else {
                $stt = "X";
            }

            if ($resulte['status_validasi'] == 1) {
                $stt_val = "&#10004";
            } else {
                $stt_val = "X";
            }

            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'no_tgl' => $resulte['no_tgl'],
                'no_upload' => $resulte['no_upload'],
                'no_upload_tgl' => $resulte['no_upload_tgl'],
                'no_voucher' => $resulte['no_voucher'],
                'tgl_voucher' => $resulte['tgl_voucher'],
                'no_sp2d' => $resulte['no_sp2d'],
                'ket' => $resulte['ket'],
                'total' => number_format($resulte['total'], 2),
                'status_upload' => $stt,
                'status_uploadx' => $resulte['status_upload'],
                'tgl_upload' => $resulte['tgl_upload'],
                'status_validasi' => $stt_val,
                'status_validasix' => $resulte['status_validasi'],
                'tgl_validasi' => $resulte['tgl_validasi'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan'],
                'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_kegiatan' => $resulte['nm_sub_kegiatan']

            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function no_urut_uploadcms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $user = $this->session->userdata('pcNama');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = "1"; //$cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "KD_SKPD = '$kd_skpd'";
        } else {
            if (substr($kd_skpd, 18, 4) == '0000') {
                $init_skpd = "left(kd_skpd,17) = left('$kd_skpd',17)";
            } else {
                $init_skpd = "KD_SKPD = '$kd_skpd'";
            }
        }

        $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_upload nomor, 'Urut Upload Pengeluaran cms' ket, kd_skpd, username from trdupload_cmsbank where $init_skpd 
    union all
    select no_upload nomor, 'Urut Upload Setor Dana Bank cms' ket, kd_skpd, username from trhupload_cmsbank_bidang where $init_skpd     
    union all
    select no_upload nomor, 'Urut Upload Panjar Bank cms' ket, kd_skpd, username from trhupload_cmsbank_panjar where $init_skpd     
    union all
    select no_upload nomor, 'Urut Upload Penerimaan cms' ket, kd_skpd, username from trhupload_sts_cmsbank where $init_skpd
    ) 
    z WHERE $init_skpd and username='$user'");
        $ii = 0;
        $nomor = 0;
        foreach ($query1->result_array() as $resulte) {

            $nomor = $resulte['nomor'];

            $result = array(
                'id' => $ii,
                'no_urut' => $nomor,
                'user_name' => $user
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function no_urut_uploadcmsharian()
    {
        $kd_skpd = $this->session->userdata('kdskpd');

        $cek_skpd = $this->db->query("SELECT count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "a.kd_skpd = '$kd_skpd'";
            $init_skpd2 = "kd_skpd = '$kd_skpd'";
        } else {
            if (substr($kd_skpd, 18, 4) == '0000') {
                $init_skpd = "left(a.kd_skpd,17) = left('$kd_skpd',17)";
                $init_skpd2 = "left(kd_skpd,17) = left('$kd_skpd',17)";
            } else {
                $init_skpd = "a.KD_SKPD = '$kd_skpd'";
                $init_skpd2 = "KD_SKPD = '$kd_skpd'";
            }
        }

        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date("Y-m-d");

        /*
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Pengeluaran cms' ket, a.kd_skpd from trdupload_cmsbank a
    left join trhupload_cmsbank b on b.kd_skpd=a.kd_bp and b.no_upload=a.no_upload
    where $init_skpd
    
    */

        $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
        select a.no_upload_tgl nomor, a.tgl_upload tanggal,'Urut Upload Pengeluaran cms' ket, a.kd_skpd from trhupload_cmsbank a        
    where $init_skpd
        union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Setor Dropping Bank cms' ket, a.kd_skpd from trdupload_cmsbank_bidang a
        left join trhupload_cmsbank_bidang b on b.kd_skpd=a.kd_skpd and b.no_upload=a.no_upload
    where $init_skpd
        union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Panjar Bank cms' ket, a.kd_skpd from trdupload_cmsbank_panjar a
        left join trhupload_cmsbank_panjar b on b.kd_skpd=a.kd_skpd and b.no_upload=a.no_upload
    where $init_skpd
        union all
    select a.no_upload_tgl nomor, b.tgl_upload tanggal,'Urut Upload Penerimaan cms' ket, a.kd_skpd from trdupload_sts_cmsbank a
        left join trhupload_sts_cmsbank b on b.kd_skpd=a.kd_skpd and b.no_upload=a.no_upload
    where $init_skpd
    ) 
    z WHERE $init_skpd2 AND tanggal='$tanggal'");


        $ii = 0;
        $nomor = 0;
        foreach ($query1->result_array() as $resulte) {

            if (strlen($resulte['nomor']) == 1) {
                $nomor = "00" . $resulte['nomor'];
            } else if (strlen($resulte['nomor']) == 2) {
                $nomor = "0" . $resulte['nomor'];
            } else if (strlen($resulte['nomor']) == 3) {
                $nomor = $resulte['nomor'];
            }

            $result = array(
                'id' => $ii,
                'no_urut' => $nomor
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_uploadcms_setorbidang()
    {
        $tabel    = $this->input->post('tabel');
        $nomor    = $this->input->post('no');
        $skpd     = $this->input->post('skpd');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');
        $urut_tgl = $this->input->post('urut_tglupload');
        $usern    = $this->session->userdata('pcNama');

        date_default_timezone_set('Asia/Jakarta');
        $update     = date('Y-m-d');
        $msg        = array();

        if ($tabel == 'trdupload_cmsbank_bidang') {
            // Simpan Detail //                       
            $sql = "DELETE from trhupload_cmsbank_bidang where no_upload='$nomor' AND kd_skpd='$skpd' and username='$usern'";
            $asg = $this->db->query($sql);
            $sql = "DELETE from trdupload_cmsbank_bidang where no_upload='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "INSERT into trdupload_cmsbank_bidang(no_bukti,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,no_upload_tgl)";
                $asg = $this->db->query($sql . $csql);

                $skpd = $this->session->userdata('kdskpd');
                $sql = "INSERT into trhupload_cmsbank_bidang(no_upload,tgl_upload,kd_skpd,total,no_upload_tgl,username) values ('$nomor','$update','$skpd','$total','$urut_tgl','$usern')";
                $asg = $this->db->query($sql);

                $sql = "UPDATE
                            tr_setorpelimpahan_bank_cms
                            SET tr_setorpelimpahan_bank_cms.status_upload = Table_B.status_upload,
                                 tr_setorpelimpahan_bank_cms.tgl_upload = Table_B.tgl_upload
                        FROM tr_setorpelimpahan_bank_cms     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_bukti,b.kd_bp from trhupload_cmsbank_bidang a left join 
                        trdupload_cmsbank_bidang b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_bp='$skpd' and a.no_upload='$nomor') AS Table_B ON tr_setorpelimpahan_bank_cms.no_bukti = Table_B.no_bukti AND tr_setorpelimpahan_bank_cms.kd_skpd = Table_B.kd_skpd
                        where left(tr_setorpelimpahan_bank_cms.kd_skpd,17)=left('$skpd',17)
                        ";
                $asg = $this->db->query($sql);

                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function load_draf_upload_bidang()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.no_upload='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');
        if (substr($skpd, 8, 2) == '00') {
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        } else {
            $init_skpd = "a.kd_skpd='$skpd'";
        }

        $sql = "SELECT count(*) as total from trhupload_cmsbank_bidang a left join trdupload_cmsbank_bidang b on b.kd_skpd=a.kd_skpd and a.no_upload=b.no_upload 
        where $init_skpd $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT top $rows a.*,b.* FROM trhupload_cmsbank_bidang a left join trdupload_cmsbank_bidang b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        where $init_skpd $and 
        and a.no_upload not in (SELECT top $offset a.no_upload FROM trhupload_cmsbank_bidang a  
        WHERE $init_skpd $and order by cast(a.no_upload as int))
        order by cast(a.no_upload as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_upload'] == 1) {
                $stt = "&#10004";
            } else {
                $stt = "X";
            }

            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'no_upload' => $resulte['no_upload'],
                'tgl_upload' => $resulte['tgl_upload'],
                'total' => number_format($resulte['total'], 2),
                'viewtotal' => number_format($resulte['nilai'], 2),
                'nilai' => number_format($resulte['nilai'], 2),
                'status_upload' => $stt,
                'status_uploadx' => $resulte['status_upload'],
                'tgl_upload' => $resulte['tgl_upload'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan']

            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_dtransout_trdmpot()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_user = $this->session->userdata('pcNama');
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        $sql = "SELECT a.* from trdtrmpot_cmsbank a left join trhtrmpot_cmsbank b on b.no_bukti=a.no_bukti and a.kd_skpd=b.kd_skpd  where b.no_voucher='$nomor' and b.kd_skpd='$skpd' a.username='$kd_user'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_bukti'      => $resulte['no_bukti'],
                'kd_rek5'       => $resulte['kd_rek6'],
                'nm_rek5'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai'],
                'nilai_nformat' => number_format($resulte['nilai'])
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_bataluploadcms()
    {
        $tabel    = $this->input->post('tabel');
        $nomor    = $this->input->post('no');
        $nomor_up = $this->input->post('noup');
        $skpd     = $this->input->post('skpd');
        $update   = date('Y-m-d');
        $msg      = array();
        $usern    = $this->session->userdata('pcNama');

        if ($tabel == 'trdupload_cmsbank') {
            // Simpan Detail //               
            $sql_h = "select count(*) as jum from trdupload_cmsbank where no_upload='$nomor_up' AND kd_skpd='$skpd'";
            $asg_h = $this->db->query($sql_h)->row();
            $inith = $asg_h->jum;

            if ($inith > 1) {
                $sql = "delete from trdupload_cmsbank where no_voucher='$nomor' and no_upload='$nomor_up' AND kd_skpd='$skpd' ";
                $asg = $this->db->query($sql);


                $sql = "UPDATE
                            trhupload_cmsbank
                            SET trhupload_cmsbank.total = Table_B.total                              
                        FROM trhupload_cmsbank     
                        INNER JOIN (select a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp,a.username,sum(b.nilai) as total from trhupload_cmsbank a left join 
                        trdupload_cmsbank b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
                        where b.kd_bp='$skpd' and a.no_upload='$nomor_up' and a.username='$usern'
                        group by a.no_upload,b.kd_skpd,a.tgl_upload,b.status_upload,b.no_voucher,b.kd_bp,a.username) AS Table_B ON trhupload_cmsbank.no_upload = Table_B.no_upload AND trhupload_cmsbank.kd_skpd = Table_B.kd_skpd  
                        where left(trhupload_cmsbank.kd_skpd,17)=left('$skpd',17)
                        ";
                $asg = $this->db->query($sql);
            } else {
                $sql = "delete from trdupload_cmsbank where no_voucher='$nomor' and no_upload='$nomor_up' AND kd_skpd='$skpd'";
                $asg = $this->db->query($sql);

                $sql = "delete from trhupload_cmsbank where no_upload='$nomor_up' AND kd_skpd='$skpd' ";
                $asg = $this->db->query($sql);
            }

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "update trhtransout_cmsbank set status_upload='0', tgl_upload='' where no_voucher='$nomor' AND kd_skpd='$skpd' ";
                $asg = $this->db->query($sql);

                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function csv_cmsbank_setorbidang($nomor = '')
    {
        ob_start();
        $skpd = $this->session->userdata('kdskpd');
        $usern = $this->session->userdata('pcNama');
        $obskpd = $this->tukd_model->get_nama($skpd, 'obskpd', 'ms_skpd', 'kd_skpd');

        $cRet = '';
        $data = '';
        $jdul = 'OB';

        $sqlquery = $this->db->query("SELECT a.tgl_upload,a.kd_skpd,(SELECT obskpd from ms_skpd where kd_skpd=a.kd_skpd) as nm_skpd,
        b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,b.nilai,b.ket_tujuan,b.no_upload_tgl FROM trhupload_cmsbank_bidang a left join trdupload_cmsbank_bidang b on b.kd_bp=a.kd_skpd and a.no_upload=b.no_upload 
        where left(a.kd_skpd,17)=left('$skpd',17) and a.no_upload='$nomor' and b.kd_bp='$skpd'");

        foreach ($sqlquery->result_array() as $resulte) {
            $tglupload = $resulte['tgl_upload'];
            $tglnoupload = $resulte['no_upload_tgl'];
            $nilai  = strval($resulte['nilai']);
            $nilai  = str_replace(".00", "", $nilai);
            $rrekawal = $resulte['rekening_awal'];
            $rrektujuan = $resulte['rekening_tujuan'];

            //$data = $resulte['nm_skpd'].",".$resulte['rekening_awal'].",".$resulte['nm_rekening_tujuan'].",".$resulte['rekening_tujuan'].",".$resulte['nilai'].",".$resulte['ket_tujuan']."\n";    
            $data = $resulte['nm_skpd'] . ";" . str_replace(" ", "", rtrim($rrekawal)) . ";" . rtrim($resulte['nm_rekening_tujuan']) . ";" . str_replace(" ", "", rtrim($rrektujuan)) . ";" . $nilai . ";" . $resulte['ket_tujuan'] . "\n";


            $init_tgl = explode("-", $tglupload);
            $tglupl = $init_tgl[2] . $init_tgl[1] . $init_tgl[0];
            $filenamee = $jdul . "_" . $obskpd . "_" . $tglupl . "_" . $tglnoupload;

            echo $data;
            header("Cache-Control: no-cache, no-store");
            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="' . $filenamee . '.csv"');
        }
    }



    function load_total_upload_bidang($tgl = '')
    {
        $kode    = $this->session->userdata('kdskpd');
        //$tgl     = $this->input->post('cari');

        $sql = "SELECT
                        SUM (a.nilai) AS total_upload
                    FROM
                        tr_setorpelimpahan_bank_cms a                   
                    WHERE
                        a.kd_skpd_sumber = '$kode'
                    AND a.status_upload = '1' AND a.tgl_upload='$tgl'";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'xtotal_upload' => number_format($resulte['total_upload'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_listsetor_belum_upload_cms()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_bukti='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');

        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and and a.status_upload='0'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT top $rows a.* FROM tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' $and and a.status_upload='0'
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' $and and a.status_upload='0' order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_upload'] == 1) {
                $stt = "&#10004";
            } else {
                $stt = "X";
            }

            if ($resulte['status_validasi'] == 1) {
                $stt_val = "&#10004";
            } else {
                $stt_val = "X";
            }

            $nmskpd = $this->tukd_model->get_nama($resulte['kd_skpd'], 'nm_skpd', 'ms_skpd', 'kd_skpd');

            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $nmskpd,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['keterangan'],
                'total' => number_format($resulte['nilai'], 2),
                'status_upload' => $stt,
                'status_uploadx' => $resulte['status_upload'],
                'tgl_upload' => $resulte['tgl_upload'],
                'status_validasi' => $stt_val,
                'status_validasix' => $resulte['status_validasi'],
                'tgl_validasi' => $resulte['tgl_validasi'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => trim($resulte['rekening_tujuan']),
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan']

            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_list_belum_validasi_perbidang()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_upload='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');

        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a 
        where a.kd_skpd_sumber='$skpd' and status_upload='1' and status_validasi='0' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM tr_setorpelimpahan_bank_cms a 
        left join trdupload_cmsbank_bidang c on a.no_bukti = c.no_bukti and a.kd_skpd = c.kd_skpd
        where a.kd_skpd_sumber='$skpd' and a.status_upload='1' and status_validasi='0' $and 
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' and a.status_upload='1' and status_validasi='0' $and order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_validasi'] == 1) {
                $stt_val = "&#10004";
            } else {
                $stt_val = "X";
            }

            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'no_bukti' => $resulte['no_bukti'],
                'no_upload' => $resulte['no_upload'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['keterangan'],
                'total' => number_format($resulte['nilai'], 2),
                'status_upload' => $resulte['status_upload'],
                'status_validasix' => $resulte['status_validasi'],
                'tgl_upload' => $resulte['tgl_upload'],
                'status_validasi' => $stt_val,
                'tgl_validasi' => $resulte['tgl_validasi'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan']
            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }


    function load_list_validasi_perbidang()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_upload='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');

        $sql = "SELECT count(*) as total from tr_setorpelimpahan_bank_cms a where a.kd_skpd_sumber='$skpd' and status_upload='1' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM tr_setorpelimpahan_bank_cms a 
        left join trdupload_cmsbank_bidang c on a.no_bukti = c.no_bukti and a.kd_skpd = c.kd_skpd
        where a.kd_skpd_sumber='$skpd' and a.status_upload='1' $and 
        and a.no_bukti not in (SELECT top $offset a.no_bukti FROM tr_setorpelimpahan_bank_cms a  
        WHERE a.kd_skpd_sumber='$skpd' and a.status_upload='1' $and order by cast(a.no_bukti as int))
        order by cast(a.no_bukti as int),a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_validasi'] == 1) {
                $stt_val = "&#10004";
            } else {
                $stt_val = "X";
            }

            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'no_bukti' => $resulte['no_bukti'],
                'no_upload' => $resulte['no_upload'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['keterangan'],
                'total' => number_format($resulte['nilai'], 2),
                'status_upload' => $resulte['status_upload'],
                'status_validasix' => $resulte['status_validasi'],
                'tgl_upload' => $resulte['tgl_upload'],
                'status_validasi' => $stt_val,
                'tgl_validasi' => $resulte['tgl_validasi'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan']
            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function simpan_validasicms_bidang()
    {
        $tabel    = $this->input->post('tabel');
        $skpd     = $this->input->post('skpd');
        $csql     = $this->input->post('sql');
        $nval     = $this->input->post('no');

        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

        if ($tabel == 'trvalidasi_cmsbank_bidang') {

            $sql = "delete from trvalidasi_cmsbank_bidang where kd_bp='$skpd_ss' and no_validasi='$nval'";
            $asg = $this->db->query($sql);

            $sql = "insert into trvalidasi_cmsbank_bidang(no_bukti,tgl_bukti,no_upload,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,nilai,kd_skpd,kd_bp,status_upload,tgl_validasi,status_validasi,no_validasi)";
            $asg = $this->db->query($sql . $csql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
            } else {
                $sql = "UPDATE
                            tr_setorpelimpahan_bank_cms
                            SET tr_setorpelimpahan_bank_cms.status_validasi = Table_B.status_validasi,
                                tr_setorpelimpahan_bank_cms.tgl_validasi = Table_B.tgl_validasi                                
                        FROM tr_setorpelimpahan_bank_cms     
                        INNER JOIN (select a.no_bukti,a.kd_skpd,a.kd_bp,a.tgl_validasi,a.status_validasi from trvalidasi_cmsbank_bidang a
                        where a.kd_bp='$skpd_ss' and no_validasi='$nval') AS Table_B ON tr_setorpelimpahan_bank_cms.no_bukti = Table_B.no_bukti AND tr_setorpelimpahan_bank_cms.kd_skpd = Table_B.kd_skpd
                        where left(tr_setorpelimpahan_bank_cms.kd_skpd,17)=left('$skpd_ss',17)
                        ";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {

                    $sql = "INSERT INTO tr_setorpelimpahan_bank (no_kas, tgl_kas, no_bukti, tgl_bukti, kd_skpd, nilai, jenis_spp, keterangan, kd_skpd_sumber)
                                    SELECT a.no_kas, a.tgl_kas, a.no_bukti, a.tgl_bukti, a.kd_skpd, a.nilai, a.jenis_spp, a.keterangan, a.kd_skpd_sumber
                                    FROM tr_setorpelimpahan_bank_cms a left join trvalidasi_cmsbank_bidang b on b.no_bukti=a.no_bukti and a.kd_skpd_sumber=b.kd_bp
                                    WHERE b.no_validasi='$nval' and b.kd_bp='$skpd_ss'";
                    $asg = $this->db->query($sql);

                    if (!($asg)) {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                    } else {
                        $msg = array('pesan' => '1');
                        echo json_encode($msg);
                    }
                }
            }
        }
    }

    function no_urut_validasicms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');

        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$kd_skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "kd_skpd = '$kd_skpd'";
        } else {
            if (substr($kd_skpd, 18, 4) == '0000') {
                $init_skpd = "left(kd_skpd,17) = left('$kd_skpd',17)";
            } else {
                $init_skpd = "KD_SKPD = '$kd_skpd'";
            }
        }

        $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_validasi nomor, 'Urut Validasi cms' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank where kd_skpd = '$kd_skpd' 
    union all
    select no_validasi nomor, 'Urut Validasi cms Perbidang' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank_bidang where kd_skpd = '$kd_skpd'
    union all
    select no_validasi nomor, 'Urut Validasi cms Panjar' ket, kd_skpd as kd_skpd from trvalidasi_cmsbank_panjar where kd_skpd = '$kd_skpd'
    ) 
    z WHERE $init_skpd ");
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

    function load_list_telahvalidasi()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_validasi='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "a.kd_skpd='$skpd'";
        } else {
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }

        $sql = "SELECT a.no_bukti,count(*) as total from trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        where $init_skpd and status_upload='1' $and group by a.no_bukti";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT a.kd_skpd,a.no_voucher,a.tgl_voucher,a.ket,a.total,a.status_upload,a.status_validasi,
        a.tgl_upload,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,
        a.ket_tujuan,a.status_trmpot,c.no_upload,d.no_bukti FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        left join trvalidasi_cmsbank d on d.no_voucher = c.no_voucher and d.kd_bp = c.kd_bp
        where $init_skpd and a.status_upload='1' and a.status_validasi='1' $and 
        group by 
        a.kd_skpd,a.no_voucher,a.tgl_voucher,a.ket,a.total,a.status_upload,a.status_validasi,
        a.tgl_upload,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,a.bank_tujuan,
        a.ket_tujuan,a.status_trmpot,c.no_upload,d.no_bukti
        order by cast(d.no_bukti as int),a.tgl_validasi,a.kd_skpd");
        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_validasi'] == 1) {
                $stt_val = "&#10004";
            } else {
                $stt_val = "X";
            }

            $row[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'no_voucher' => $resulte['no_voucher'],
                'no_bku' => $resulte['no_bukti'],
                'no_upload' => $resulte['no_upload'],
                'tgl_voucher' => $resulte['tgl_voucher'],
                'ket' => $resulte['ket'],
                'total' => number_format($resulte['total'], 2),
                'status_upload' => $resulte['status_upload'],
                'status_validasix' => $resulte['status_validasi'],
                'tgl_upload' => $resulte['tgl_upload'],
                'status_validasi' => $stt_val,
                'tgl_validasi' => $resulte['tgl_validasi'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan'],
                'status_pot' => $resulte['status_trmpot']
            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }

    function load_list_validasi()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = $this->input->post('cari');
        $and = '';
        if ($kriteria <> '') {
            $and = " and a.tgl_upload='$kriteria'";
        }

        $skpd = $this->session->userdata('kdskpd');
        $cek_skpd = $this->db->query("select count(*) as hasil from ms_skpd where kd_skpd='$skpd'")->row();
        $cek_skpd1 = $cek_skpd->hasil;
        if ($cek_skpd1 == 1) {
            $init_skpd = "a.kd_skpd='$skpd'";
        } else {
            $init_skpd = "left(a.kd_skpd,17)=left('$skpd',17)";
        }

        $sql = "SELECT count(*) as total from trhtransout_cmsbank a 
        where $init_skpd and a.status_upload='1' $and ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $query1 = $this->db->query("SELECT a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload FROM trhtransout_cmsbank a 
        left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher and a.username=b.username
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd and c.username=a.username
        where $init_skpd and a.status_upload='1' $and         
        group by 
        a.username,a.kd_skpd,a.nm_skpd,a.no_tgl,a.no_voucher,a.tgl_voucher,a.no_sp2d,a.ket,a.total,a.status_upload,
a.tgl_upload,a.status_validasi,a.tgl_validasi,a.rekening_awal,a.nm_rekening_tujuan,a.rekening_tujuan,
a.bank_tujuan,a.ket_tujuan,a.status_trmpot,c.no_upload
        order by cast(a.no_voucher as int),a.kd_skpd");


        /*
        $query1 = $this->db->query("SELECT top $rows a.*,c.no_upload FROM trhtransout_cmsbank a left join trdtransout_cmsbank b on b.kd_skpd=a.kd_skpd and a.no_voucher=b.no_voucher 
        left join trdupload_cmsbank c on a.no_voucher = c.no_voucher and a.kd_skpd = c.kd_skpd
        where left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' $and 
        and a.no_voucher not in (SELECT top $offset a.no_voucher FROM trhtransout_cmsbank a  
        WHERE left(a.kd_skpd,7)=left('$skpd',7) and a.status_upload='1' $and order by cast(a.no_voucher as int))
        order by cast(a.no_voucher as int),a.kd_skpd"); 
        */

        $result = array();
        $ii     = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($resulte['status_validasi'] == 1) {
                $stt_val = "&#10004";
            } else {
                $stt_val = "X";
            }

            $row[] = array(
                'id' => $ii,
                'username' => $resulte['username'],
                'kd_skpd' => $resulte['kd_skpd'],
                'no_voucher' => $resulte['no_voucher'],
                'no_upload' => $resulte['no_upload'],
                'tgl_voucher' => $resulte['tgl_voucher'],
                'ket' => $resulte['ket'],
                'total' => number_format($resulte['total'], 2),
                'status_upload' => $resulte['status_upload'],
                'status_validasix' => $resulte['status_validasi'],
                'tgl_upload' => $resulte['tgl_upload'],
                'status_validasi' => $stt_val,
                'tgl_validasi' => $resulte['tgl_validasi'],
                'rekening_awal' => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan' => $resulte['rekening_tujuan'],
                'bank_tujuan' => $resulte['bank_tujuan'],
                'ket_tujuan' => $resulte['ket_tujuan'],
                'status_pot' => $resulte['status_trmpot']
            );
            $ii++;
        }

        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
    }


    function batal_validasicms()
    {
        $tabel    = $this->input->post('tabel');
        $skpd     = $this->input->post('skpd');
        $nbku     = $this->input->post('nobukti');
        $nbku_i   = strval($nbku) + 1;
        $nval     = $this->input->post('novoucher');
        $tglbku   = $this->input->post('tglvalid');
        $msg      = array();
        $skpd_ss  = $this->session->userdata('kdskpd');

        if ($tabel == 'trvalidasi_cmsbank') {

            //hapus Htrans   
            $sql = "delete from trhtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
            $asg = $this->db->query($sql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
            } else {

                $sql = "delete from trdtransout where no_bukti='$nbku' and kd_skpd='$skpd'";
                $asg = $this->db->query($sql);

                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                } else {

                    $sql = "delete from trvalidasi_cmsbank where no_bukti='$nbku' and no_voucher='$nval' and kd_skpd='$skpd'";
                    $asg = $this->db->query($sql);

                    if (!($asg)) {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                    } else {

                        $sql = "update trhtransout_cmsbank set status_validasi='0', tgl_validasi='' where no_voucher='$nval' and kd_skpd='$skpd'";
                        $asg = $this->db->query($sql);

                        if (!($asg)) {
                            $msg = array('pesan' => '0');
                            echo json_encode($msg);
                        } else {
                            //Hpotongan
                            $sql = "select count(*) as jml from trhtransout_cmsbank where no_voucher='$nval' and kd_skpd='$skpd' and status_trmpot='1'";
                            $asg = $this->db->query($sql)->row();
                            $initjml = $asg->jml;

                            if ($initjml == '1') {

                                $sql = "delete trhtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                                $asg = $this->db->query($sql);

                                if (!($asg)) {
                                    $msg = array('pesan' => '0');
                                    echo json_encode($msg);
                                } else {

                                    $sql = "delete trdtrmpot where no_bukti='$nbku_i' and kd_skpd='$skpd'";
                                    $asg = $this->db->query($sql);

                                    if (!($asg)) {
                                        $msg = array('pesan' => '0');
                                        echo json_encode($msg);
                                    } else {

                                        $sql = "delete trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
                                        $asg = $this->db->query($sql);

                                        if (!($asg)) {
                                            $msg = array('pesan' => '0');
                                            echo json_encode($msg);
                                        } else {
                                            $msg = array('pesan' => '1');
                                            echo json_encode($msg);
                                        }
                                    }
                                }
                            } else {
                                $sql = "delete trdtransout_transfer where no_bukti='$nbku' and kd_skpd='$skpd'";
                                $asg = $this->db->query($sql);

                                if (!($asg)) {
                                    $msg = array('pesan' => '0');
                                    echo json_encode($msg);
                                } else {
                                    $msg = array('pesan' => '1');
                                    echo json_encode($msg);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function ambil_bank_bidang()
    {
        $data['page_title'] = 'AMBIL SETORAN BANK BIDANG';
        $this->template->set('title', 'AMBIL SETORAN BANK BIDANG');
        $this->template->load('template', 'tukd/cms/bnk_ambil_simpanan_kebidang', $data);
    }

    function load_terima_bank_perbidang()
    {
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd = $this->session->userdata('kdskpd');

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_kas) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from tr_setorsimpanan WHERE kd_skpd = '$kd_skpd' AND status_drop='1' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows * from tr_setorsimpanan WHERE kd_skpd = '$kd_skpd' AND status_drop='1' $where and no_kas not in (
                SELECT TOP $offset no_kas from tr_setorsimpanan WHERE  kd_skpd = '$kd_skpd' AND status_drop='1' $where order by cast(no_kas as int)) order by cast(no_kas as int),kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;

        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id'          => $ii,
                'no_kas'      => $resulte['no_kas'],
                'no_bukti'      => $resulte['no_bukti'],
                'kd_link_drop'      => $resulte['kd_link_drop'],
                'tgl_kas'     => $resulte['tgl_kas'],
                'tgl_bukti'     => $resulte['tgl_bukti'],
                'kd_skpd'     => $resulte['kd_skpd'],
                'nilai'       => number_format($resulte['nilai']),
                'nilai2'       => $resulte['nilai'],
                'keterangan'  => $resulte['keterangan'],
                'jenis'  => $resulte['jenis']
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }


    function loadketdrop_bp_bnk()
    {
        $skpd = $this->session->userdata('kdskpd');

        $sql = "SELECT no_bukti,tgl_bukti,nilai,keterangan,kd_skpd_sumber from tr_setorpelimpahan_bank where kd_skpd='$skpd' and 
        status_ambil is null";
        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'kd_skpd_sumber' => $resulte['kd_skpd_sumber'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'nilai' =>  number_format($resulte['nilai'], 2, '.', ','),
                'keterangan' => $resulte['keterangan']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_sisa_dana_kembali_bidang_bnk()
    {
        $kd_skpd = $this->session->userdata('kdskpd');

        $query1 = $this->db->query("SELECT
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorpelimpahan_bank WHERE kd_skpd='$kd_skpd' union
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE kd_skpd='$kd_skpd' AND status_drop='1' ) a
                where  kode='$kd_skpd'");
        //}

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


    function cari_ambilsimpanan()
    {

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = '';

        $kd_skpd  = $this->session->userdata('kdskpd');

        if ($kriteria <> '') {
            $where = "and ( upper(no_kas) like upper('%$kriteria%') ) ";
        }

        $sql    = "SELECT * from tr_setorsimpanan where kd_skpd='$kd_skpd' and status_drop='1' $where order by no_kas";
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
                'keterangan'  => $resulte['keterangan']
            );
            $ii++;
        }
        echo json_encode($result);
    }


    function simpan_ambil_simpanan_bp_bnk()
    {
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $cno_asli = $this->input->post('cno_asli');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "INSERT into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if ($asg) {
                $sql = "UPDATE tr_setorpelimpahan_bank set status_ambil='1' where no_bukti='$cno_asli' and kd_skpd='$kd_skpd'";
                $asg = $this->db->query($sql);
                echo '2';
            } else {
                echo '0';
            }
        }
    }

    function hapus_ambilsimpanan_bp_bnk()
    {
        $no    = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $nobukti_asli = $this->input->post('nobukti_asli');
        $query = $this->db->query("DELETE from tr_setorsimpanan where no_kas='$no' and kd_skpd='$skpd' ");
        $sql = "UPDATE tr_setorpelimpahan_bank set status_ambil=null where no_bukti='$nobukti_asli' and kd_skpd='$skpd'";
        $asg = $this->db->query($sql);

        // $query->free_result();
    }

    public function getTriwulan($bulan)
    {
        if ($bulan >= 1 && $bulan <= 3) {
            $triwulan = array('bulan_awal' => 1, 'bulan_akhir' => 3);
        } else if ($bulan >= 4 && $bulan <= 6) {
            $triwulan = array('bulan_awal' => 4, 'bulan_akhir' => 6);
        } else if ($bulan >= 7 && $bulan <= 9) {
            $triwulan = array('bulan_awal' => 7, 'bulan_akhir' => 9);
        } else if ($bulan >= 10 && $bulan <= 12) {
            $triwulan = array('bulan_awal' => 10, 'bulan_akhir' => 12);
        } else {
            throw new \Exception('Invalid month');
        }
        return $triwulan;
    }


    function load_total_spd()
    {
        $kode    = $this->input->post('kode');
        $kode1   = substr($kode, 0, 17);
        $giat    = $this->input->post('giat');
        $rek  = $this->input->post('kdrek6');
        //    echo($rek);

        // --------------------------------
        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22) 
                                and bulan_akhir='3'";
        $q1     = $this->db->query($sql1);
        $tw1    = $q1->row();
        $rev1   = $tw1->revisi;
        // --------------------------------
        $sql2   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22)  
                                and bulan_akhir='6'";
        $q2     = $this->db->query($sql2);
        $tw2    = $q2->row();
        $rev2   = $tw2->revisi;
        // --------------------------------
        $sql3   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22)  
                                and bulan_akhir='9'";
        $q3     = $this->db->query($sql3);
        $tw3    = $q3->row();
        $rev3   = $tw3->revisi;
        // --------------------------------
        $sql4   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                left(kd_skpd,22)=left('$kode',22)  
                                and bulan_akhir='12'";
        $q4     = $this->db->query($sql4);
        $tw4    = $q4->row();
        $rev4   = $tw4->revisi;


        $sql = "SELECT sum(nilai)as total_spd from (
                    SELECT
                    'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='3'
                    and revisi_ke='$rev1'
                    UNION ALL
                    SELECT
                    'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='6'
                    and revisi_ke='$rev2'
                    UNION ALL
                    SELECT
                    'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='9'
                    and revisi_ke='$rev3'
                    UNION ALL
                    SELECT
                    'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    a.kd_unit = '$kode'
                    AND a.kd_sub_kegiatan = '$giat'
                    AND a.kd_rek6 = '$rek'
                    AND b.status = '1'
                    and bulan_akhir='12'
                    and revisi_ke='$rev4')spd

                    ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total_spd' => number_format($resulte['total_spd'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function total_angkas()
    {

        $ckdkegi  = $this->input->post('kegiatan');
        $ckdskpd  = $this->input->post('kd_skpd');
        $ctgl       = $this->input->post('tgl');
        $jnsbeban       = $this->input->post('jnsbeban');
        $sts_angkas       = $this->input->post('sts_angkas');
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($ckdskpd);
        // $spd  = $this->tukd_model->get_nama($this->input->post('sp2d'),'no_spd','trhsp2d','no_sp2d');

        $ckdskpd1  = substr($ckdskpd, 0, 17);
        // $tglspd   = $spd  = $this->tukd_model->get_nama($this->input->post('sp2d'),'no_spd','tr','no_sp2d');
        $kd_rek6   = $this->input->post('kdrek6');

        $artgl = explode("-", $ctgl);
        $bulan = $artgl[1];
        $bulan1 = 0;
        if ($sts_angkas == 'murni') {
            $field_angkas = 'nilai_susun';
        } else if ($sts_angkas == 'murni_geser1') {
            $field_angkas = 'nilai_susun1';
        } else if ($sts_angkas == 'murni_geser2') {
            $field_angkas = 'nilai_susun2';
        } else if ($sts_angkas == 'murni_geser3') {
            $field_angkas = 'nilai_susun3';
        } else if ($sts_angkas == 'murni_geser4') {
            $field_angkas = 'nilai_susun4';
        } else if ($sts_angkas == 'murni_geser5') {
            $field_angkas = 'nilai_susun5';
        } else if ($sts_angkas == 'sempurna1') {
            $field_angkas = 'nilai_sempurna';
        } else if ($sts_angkas == 'sempurna1_geser1') {
            $field_angkas = 'nilai_sempurna11';
        } else if ($sts_angkas == 'sempurna1_geser2') {
            $field_angkas = 'nilai_sempurna12';
        } else if ($sts_angkas == 'sempurna1_geser3') {
            $field_angkas = 'nilai_sempurna13';
        } else if ($sts_angkas == 'sempurna1_geser4') {
            $field_angkas = 'nilai_sempurna14';
        } else if ($sts_angkas == 'sempurna1_geser5') {
            $field_angkas = 'nilai_sempurna15';
        } else if ($sts_angkas == 'sempurna2') {
            $field_angkas = 'nilai_sempurna2';
        } else if ($sts_angkas == 'sempurna2_geser1') {
            $field_angkas = 'nilai_sempurna21';
        } else if ($sts_angkas == 'sempurna2_geser2') {
            $field_angkas = 'nilai_sempurna22';
        } else if ($sts_angkas == 'sempurna2_geser3') {
            $field_angkas = 'nilai_sempurna23';
        } else if ($sts_angkas == 'sempurna2_geser4') {
            $field_angkas = 'nilai_sempurna24';
        } else if ($sts_angkas == 'sempurna2_geser5') {
            $field_angkas = 'nilai_sempurna25';
        } else if ($sts_angkas == 'sempurna3') {
            $field_angkas = 'nilai_sempurna3';
        } else if ($sts_angkas == 'sempurna3_geser1') {
            $field_angkas = 'nilai_sempurna31';
        } else if ($sts_angkas == 'sempurna3_geser2') {
            $field_angkas = 'nilai_sempurna32';
        } else if ($sts_angkas == 'sempurna3_geser3') {
            $field_angkas = 'nilai_sempurna33';
        } else if ($sts_angkas == 'sempurna3_geser4') {
            $field_angkas = 'nilai_sempurna34';
        } else if ($sts_angkas == 'sempurna3_geser5') {
            $field_angkas = 'nilai_sempurna35';
        } else if ($sts_angkas == 'sempurna4') {
            $field_angkas = 'nilai_sempurna4';
        } else if ($sts_angkas == 'sempurna4_geser1') {
            $field_angkas = 'nilai_sempurna41';
        } else if ($sts_angkas == 'sempurna4_geser2') {
            $field_angkas = 'nilai_sempurna42';
        } else if ($sts_angkas == 'sempurna4_geser3') {
            $field_angkas = 'nilai_sempurna43';
        } else if ($sts_angkas == 'sempurna4_geser4') {
            $field_angkas = 'nilai_sempurna44';
        } else if ($sts_angkas == 'sempurna4_geser5') {
            $field_angkas = 'nilai_sempurna45';
        } else if ($sts_angkas == 'sempurna5') {
            $field_angkas = 'nilai_sempurna5';
        } else if ($sts_angkas == 'sempurna5_geser1') {
            $field_angkas = 'nilai_sempurna51';
        } else if ($sts_angkas == 'sempurna5_geser2') {
            $field_angkas = 'nilai_sempurna52';
        } else if ($sts_angkas == 'sempurna5_geser3') {
            $field_angkas = 'nilai_sempurna53';
        } else if ($sts_angkas == 'sempurna5_geser4') {
            $field_angkas = 'nilai_sempurna1';
        } else if ($sts_angkas == 'sempurna5_geser5') {
            $field_angkas = 'nilai_sempurna55';
        } else if ($sts_angkas == 'ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($sts_angkas == 'ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }


        if ($jnsbeban == 4 || substr($ckdkegi, 5, 10) == '01.1.02.01') {
            $bulan1 = $bulan + 1;
            $query1   = $this->db->query(" SELECT  a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            where b.jns_ang='$jns_ang' AND a.kd_skpd = '$ckdskpd' and  a.kd_sub_kegiatan = '$ckdkegi' and a.kd_rek6='$kd_rek6' and (bulan <='$bulan1') GROUP BY a.kd_sub_kegiatan,a.kd_rek6
            ");
        } else {
            $query1   = $this->db->query("SELECT  a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            where b.jns_ang='$jns_ang' AND a.kd_skpd = '$ckdskpd' and  a.kd_sub_kegiatan = '$ckdkegi' and a.kd_rek6='$kd_rek6' and (bulan <='$bulan') GROUP BY a.kd_sub_kegiatan,a.kd_rek6
            ");
        }

        $result   = array();
        $ii       = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'             => $ii,
                'nilai'          => number_format($resulte['nilai'], 2, '.', ','),
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function cek_status_angkas()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT TOP 1 * from (
        select '1'as urut,'murni' as ket, 'nilai_susun' as status,murni as nilai from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '2'as urut,'murni geser 1' as ket, 'murni_susun1',murni_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '3'as urut,'murni geser 2' as ket, 'murni_susun2',murni_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '4'as urut,'murni geser 3' as ket, 'murni_susun3',murni_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '5'as urut,'murni geser 4' as ket, 'murni_susun4',murni_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '6'as urut,'murni geser 5' as ket, 'murni_susun5',murni_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '7'as urut,'penyempurna 1' as ket, 'nilai_sempurna',sempurna1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '8'as urut,'penyempurna 1 geser 1' as ket, 'nilai_sempurna11',sempurna1_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '9'as urut,'penyempurna 1 geser 2' as ket, 'nilai_sempurna12',sempurna1_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '10'as urut,'penyempurna 1 geser 3' as ket, 'nilai_sempurna13',sempurna1_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '11'as urut,'penyempurna 1 geser 4' as ket, 'nilai_sempurna14',sempurna1_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '12'as urut,'penyempurna 1 geser 5' as ket, 'nilai_sempurna15',sempurna1_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '13'as urut,'penyempurna 2' as ket, 'nilai_sempurna2',sempurna2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '14'as urut,'penyempurna 2 geser 1' as ket, 'nilai_sempurna21',sempurna2_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '15'as urut,'penyempurna 2 geser 2' as ket, 'nilai_sempurna22',sempurna2_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '16'as urut,'penyempurna 2 geser 3' as ket, 'nilai_sempurna23',sempurna2_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '17'as urut,'penyempurna 2 geser 4' as ket, 'nilai_sempurna24',sempurna2_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '18'as urut,'penyempurna 2 geser 5' as ket, 'nilai_sempurna25',sempurna2_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '19'as urut,'penyempurna 3' as ket, 'nilai_sempurna3',sempurna3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '20'as urut,'penyempurna 3 geser 1' as ket, 'nilai_sempurna31',sempurna3_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '21'as urut,'penyempurna 3 geser 2' as ket, 'nilai_sempurna32',sempurna3_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '22'as urut,'penyempurna 3 geser 3' as ket, 'nilai_sempurna33',sempurna3_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '23'as urut,'penyempurna 3 geser 4' as ket, 'nilai_sempurna34',sempurna3_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '24'as urut,'penyempurna 3 geser 5' as ket, 'nilai_sempurna35',sempurna3_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '25'as urut,'penyempurnaan 4' as ket, 'nilai_sempurna4',sempurna4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '26'as urut,'penyempurnaan 4 geser 1' as ket, 'nilai_sempurna41',sempurna4_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '27'as urut,'penyempurnaan 4 geser 2' as ket, 'nilai_sempurna42',sempurna4_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '28'as urut,'penyempurnaan 4 geser 3' as ket, 'nilai_sempurna43',sempurna4_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '29'as urut,'penyempurnaan 4 geser 4' as ket, 'nilai_sempurna44',sempurna4_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '30'as urut,'penyempurnaan 4 geser 5' as ket, 'nilai_sempurna45',sempurna4_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '31'as urut,'Penyempurnaan 5 ' as ket, 'nilai_sempurna5',sempurna5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '32'as urut,'Penyempurnaan 5 geser 1' as ket, 'nilai_sempurna51',sempurna5_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '33'as urut,'Penyempurnaan 5 geser 2' as ket, 'nilai_sempurna52',sempurna5_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '34'as urut,'Penyempurnaan 5 geser 3' as ket, 'nilai_sempurna53',sempurna5_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '35'as urut,'Penyempurnaan 5 geser 4' as ket, 'nilai_sempurna54',sempurna5_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '36'as urut,'Penyempurnaan 5 geser 5' as ket, 'nilai_sempurna55',sempurna5_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '37'as urut,'Perubahan' as ket, 'nilai_ubah',ubah from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '38'as urut,'Perubahan 1 Geser 1' as ket, 'nilai_ubah1',ubah1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '39'as urut,'Perubahan 1 Geser 2' as ket, 'nilai_ubah2',ubah2 from status_angkas where kd_skpd ='$skpd'
        )zz where nilai='1' ORDER BY cast(urut as int) DESC";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'status' => $resulte['status'],
                'keterangan' => $resulte['ket']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_angkas()
    {
        $kode    = $this->input->post('kode');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $kode1   = substr($kode, 0, 17);
        $giat    = $this->input->post('giat');
        $rek     = $this->input->post('koderek');
        $tgl     = $this->input->post('tgl');
        $sts_angkas =  $this->input->post('stt');

        if ($sts_angkas == 'murni') {
            $field_angkas = 'nilai_susun';
        } else if ($sts_angkas == 'murni_geser1') {
            $field_angkas = 'nilai_susun1';
        } else if ($sts_angkas == 'murni_geser2') {
            $field_angkas = 'nilai_susun2';
        } else if ($sts_angkas == 'murni_geser3') {
            $field_angkas = 'nilai_susun3';
        } else if ($sts_angkas == 'murni_geser4') {
            $field_angkas = 'nilai_susun4';
        } else if ($sts_angkas == 'murni_geser5') {
            $field_angkas = 'nilai_susun5';
        } else if ($sts_angkas == 'sempurna1') {
            $field_angkas = 'nilai_sempurna';
        } else if ($sts_angkas == 'sempurna1_geser1') {
            $field_angkas = 'nilai_sempurna11';
        } else if ($sts_angkas == 'sempurna1_geser2') {
            $field_angkas = 'nilai_sempurna12';
        } else if ($sts_angkas == 'sempurna1_geser3') {
            $field_angkas = 'nilai_sempurna13';
        } else if ($sts_angkas == 'sempurna1_geser4') {
            $field_angkas = 'nilai_sempurna14';
        } else if ($sts_angkas == 'sempurna1_geser5') {
            $field_angkas = 'nilai_sempurna15';
        } else if ($sts_angkas == 'sempurna2') {
            $field_angkas = 'nilai_sempurna2';
        } else if ($sts_angkas == 'sempurna2_geser1') {
            $field_angkas = 'nilai_sempurna21';
        } else if ($sts_angkas == 'sempurna2_geser2') {
            $field_angkas = 'nilai_sempurna22';
        } else if ($sts_angkas == 'sempurna2_geser3') {
            $field_angkas = 'nilai_sempurna23';
        } else if ($sts_angkas == 'sempurna2_geser4') {
            $field_angkas = 'nilai_sempurna24';
        } else if ($sts_angkas == 'sempurna2_geser5') {
            $field_angkas = 'nilai_sempurna25';
        } else if ($sts_angkas == 'sempurna3') {
            $field_angkas = 'nilai_sempurna3';
        } else if ($sts_angkas == 'sempurna3_geser1') {
            $field_angkas = 'nilai_sempurna31';
        } else if ($sts_angkas == 'sempurna3_geser2') {
            $field_angkas = 'nilai_sempurna32';
        } else if ($sts_angkas == 'sempurna3_geser3') {
            $field_angkas = 'nilai_sempurna33';
        } else if ($sts_angkas == 'sempurna3_geser4') {
            $field_angkas = 'nilai_sempurna34';
        } else if ($sts_angkas == 'sempurna3_geser5') {
            $field_angkas = 'nilai_sempurna35';
        } else if ($sts_angkas == 'sempurna4') {
            $field_angkas = 'nilai_sempurna4';
        } else if ($sts_angkas == 'sempurna4_geser1') {
            $field_angkas = 'nilai_sempurna41';
        } else if ($sts_angkas == 'sempurna4_geser2') {
            $field_angkas = 'nilai_sempurna42';
        } else if ($sts_angkas == 'sempurna4_geser3') {
            $field_angkas = 'nilai_sempurna43';
        } else if ($sts_angkas == 'sempurna4_geser4') {
            $field_angkas = 'nilai_sempurna44';
        } else if ($sts_angkas == 'sempurna4_geser5') {
            $field_angkas = 'nilai_sempurna45';
        } else if ($sts_angkas == 'sempurna5') {
            $field_angkas = 'nilai_sempurna5';
        } else if ($sts_angkas == 'sempurna5_geser1') {
            $field_angkas = 'nilai_sempurna51';
        } else if ($sts_angkas == 'sempurna5_geser2') {
            $field_angkas = 'nilai_sempurna52';
        } else if ($sts_angkas == 'sempurna5_geser3') {
            $field_angkas = 'nilai_sempurna53';
        } else if ($sts_angkas == 'sempurna5_geser4') {
            $field_angkas = 'nilai_sempurna1';
        } else if ($sts_angkas == 'sempurna5_geser5') {
            $field_angkas = 'nilai_sempurna55';
        } else if ($sts_angkas == 'ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($sts_angkas == 'ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }

        $sql = "SELECT  a.kd_sub_kegiatan, SUM(a.$field_angkas) as nilai FROM trdskpd_ro a INNER JOIN trskpd b ON a.kd_skpd=b.kd_skpd and a.kd_sub_kegiatan=b.kd_sub_kegiatan 
            where a.kd_skpd = '$kode' AND b.jns_ang='$data' and  a.kd_sub_kegiatan = '$giat' and a.kd_rek6='$rek' and bulan <= month('$tgl') GROUP BY a.kd_sub_kegiatan,a.kd_rek6
            ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total_angkas' => number_format($resulte['nilai'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    // Create By hakam
    function load_total_trans_spd_new()
    { /*untuk transaksi konsep kota*/
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');
        $rek         = $this->input->post('kdrek6');
        $sp2d        = $this->input->post('sp2d');
        $spp = "";
        $org         = substr($kdskpd, 0, 17);
        $beban       = $this->input->post('beban');
        $smbr_dana       = $this->input->post('csumber_dn');
        $ctgl       = $this->input->post('ctgl');

        if ($beban == '1') {
            $sql = "SELECT SUM(nilai) total, SUM(nilai_lalu) total_lalu FROM 
            (
            -- transaksi UP/GU
            SELECT SUM (isnull(c.nilai,0)) as nilai, SUM(isnull(c.nilai,0)) as nilai_lalu
            FROM trdtransout c
            LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
            AND c.kd_skpd = d.kd_skpd
            WHERE c.kd_sub_kegiatan = '$kegiatan'
            AND d.kd_skpd = '$kdskpd'
            AND c.kd_rek6 = '$rek'
            AND d.jns_spp in ('1') 
            AND c.sumber='$smbr_dana'
            AND d.tgl_bukti<='$ctgl'
            
            UNION ALL
            -- transaksi UP/GU CMS BANK Belum Validasi
            SELECT SUM (isnull(c.nilai,0)) as nilai, SUM(isnull(c.nilai,0)) as nilai_lalu
            FROM trdtransout_cmsbank c
            LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
            AND c.kd_skpd = d.kd_skpd
            WHERE c.kd_sub_kegiatan ='$kegiatan'
            AND d.kd_skpd = '$kdskpd'
            AND c.kd_rek6='$rek'
            AND d.jns_spp in ('1') 
            AND (d.status_validasi='0' OR d.status_validasi is null)
            AND c.sumber='$smbr_dana'
            AND d.tgl_bukti<='$ctgl'
            
            UNION ALL
            -- transaksi SPP SELAIN UP/GU
            SELECT SUM(isnull(x.nilai,0)) as nilai, SUM(isnull(x.nilai,0)) as nilai_lalu FROM trdspp x
            INNER JOIN trhspp y 
            ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
            WHERE x.kd_sub_kegiatan = '$kegiatan'
            AND x.kd_skpd = '$kdskpd'
            AND x.kd_rek6 = '$rek'
            AND y.jns_spp IN ('3','4','5','6')
            AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
            AND x.sumber='$smbr_dana'
            AND y.tgl_spp<='$ctgl'
            
            UNION ALL
            --Penagihan yang belum jadi SPP
            SELECT SUM(isnull(nilai,0)) as nilai, SUM(isnull(nilai,0)) as nilai_lalu FROM trdtagih t 
            INNER JOIN trhtagih u 
            ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
            WHERE t.kd_sub_kegiatan ='$kegiatan' 
            AND t.kd_rek ='$rek' 
            AND u.kd_skpd = '$kdskpd' 
            AND u.no_bukti 
            NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
            AND t.sumber='$smbr_dana'
            AND U.tgl_bukti<='$ctgl'
            )r";
        } else {
            // $spp         = $this->tukd_model->get_nama($this->input->post('nosp2d'),'no_spp','trhsp2d','no_sp2d');
            // $sqlsc = "SELECT no_spp from trhsp2d where no_sp2d='$sp2d'";
            // $sqlsclient = $this->db->query($sqlsc);
            // foreach ($sqlsclient->result() as $rowsc) {
            //     $spp     = $rowsc->no_spp;
            // }

            $sql = "SELECT SUM(nilai) total, SUM(nilai_lalu) total_lalu FROM 
                                    (
                                    -- transaksi UP/GU
                                    -- SELECT SUM(isnull(c.nilai,0)) as nilai, SUM(isnull(c.nilai,0)) as nilai_lalu
                                    -- FROM trdtransout c
                                    -- LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    -- AND c.kd_skpd = d.kd_skpd
                                    -- WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    -- AND d.kd_skpd = '$kdskpd'
                                    -- AND c.kd_rek6 = '$rek'
                                    -- AND d.jns_spp in ('1')
                                    -- AND c.sumber='$smbr_dana'
                                    -- TRANSAKSI LS YG BELUM DIPINDAHBUKUAN
                                    -- UNION ALL
                                    SELECT SUM(isnull(c.nilai,0)) as nilai, SUM(isnull(c.nilai,0)) as nilai_lalu
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti AND c.no_sp2d=d.no_sp2d
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6 = '$rek'
                                    AND d.jns_spp in ('3','4','5','6')
                                    AND c.sumber='$smbr_dana'
                                    AND d.no_sp2d='$sp2d'
                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM(isnull(c.nilai,0)) as nilai, SUM(isnull(c.nilai,0)) as nilai_lalu
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6='$rek'
                                    AND d.jns_spp in ('1') 
                                    AND (d.status_validasi='0' OR d.status_validasi is null)
                                    AND c.sumber='$smbr_dana'
                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai, SUM(isnull(nilai,0)) as nilai_lalu FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND t.kd_rek ='$rek' 
                                    AND u.kd_skpd = '$kdskpd' 
                                    AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
                                    AND t.sumber='$smbr_dana'
                                    )r";
        }



        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ','),
                'total_lalu' => number_format($resulte['total_lalu'], 2, '.', ','),
                'totals' => $resulte['total']
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
            
            UNION ALL
            -- Penagihan yang belum jadi SPP
            SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
            INNER JOIN trhtagih u 
            ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
            WHERE t.kd_sub_kegiatan ='$kegiatan' 
            AND t.kd_rek6 ='$rek' 
            AND u.kd_skpd = '$kdskpd' 
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
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
                                    
                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND t.kd_rek6 ='$rek' 
                                    AND u.kd_skpd = '$kdskpd' 
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

    function load_total_trans_lama()
    {
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');

        $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                        select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') AND c.kd_skpd = '$kdskpd' and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_sub_kegiatan
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                    left join 
                                    (
                                        SELECT z.kd_sub_kegiatan, SUM(z.transaksi) as transaksi FROM (
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$kdskpd' and f.kd_sub_kegiatan='$kegiatan' and e.no_bukti<>'$no_bukti' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        UNION ALL
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd
                                        where f.kd_skpd='$kdskpd' and f.kd_sub_kegiatan='$kegiatan' and e.no_bukti<>'$no_bukti' and e.jns_spp ='1'
                                        and e.status_validasi='0'
                                        group by f.kd_sub_kegiatan) z
                                        group by z.kd_sub_kegiatan
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_sub_kegiatan
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                    where a.kd_sub_kegiatan='$kegiatan'";



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



    function simpan_transout()
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
        $xrek     = $this->input->post('xrek');

        $rek_awal = trim($this->input->post('rek_awal'));
        $anrekawal = $this->input->post('anrek_awal');
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');
        $init_ket = $this->input->post('cinit_ket');
        $stt_val  = 0;
        $stt_up   = 0;

        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout_cmsbank') {
            $sql = "delete from trhtransout_cmsbank where kd_skpd='$skpd' and no_voucher='$nomor'";
            $asg = $this->db->query($sql);

            if ($asg) {
                $sql = "insert into trhtransout_cmsbank(no_voucher,tgl_voucher,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,status_validasi,status_upload,no_tgl,ket_tujuan) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d','$rek_awal','$anrekawal','$rek_tjn','$rek_bnk','$stt_val','$stt_up','$nomor_tgl','$init_ket')";
                $asg = $this->db->query($sql);
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } elseif ($tabel == 'trdtransout_cmsbank') {
            // Simpan Detail //                                       

            $sql = "delete from trdtransout_cmsbank where no_voucher='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);

            $sql = "delete from trdtransout_transfercms where no_voucher='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into trdtransout_cmsbank(no_voucher,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber)";
                $asg = $this->db->query($sql . $csql);

                $sql = "insert into trdtransout_transfercms(no_voucher,tgl_voucher,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)";
                $asg = $this->db->query($sql . $csqlrek);

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



    function load_trskpd_sub()
    {
        $kode = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $jenis = $this->input->post('jenis');
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');
        $bid = $this->session->userdata('kdskpd');

        $lccr = $this->input->post('q');

        $sql = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan, sum(a.nilai) as total from trdrka a INNER JOIN trskpd b ON b.kd_skpd=a.kd_skpd AND b.kd_sub_kegiatan=a.kd_sub_kegiatan AND b.jns_ang=a.jns_ang WHERE a.kd_skpd='$cskpd' AND b.status_sub_kegiatan='1' AND a.jns_ang='$data'
            AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))
            group by  a.kd_sub_kegiatan,a.nm_sub_kegiatan
            ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

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



    function load_reksumber_dana()
    {
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $jnsang = $this->cek_anggaran_model->cek_anggaran($kode);
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');

        $sql = "SELECT sumber as sumber_dana,nm_sumber as nm_sumber_dana,sum(total) as nilai , (SELECT ISNULL(SUM(nilai),0) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
        u.kd_skpd = '$kode' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' ) and sumber=sumber)as lalu from trdpo where kd_sub_kegiatan = '$giat' and kd_rek6 = '$rek' and kd_skpd = '$kode' and jns_ang = '$jnsang' GROUP BY sumber, nm_sumber";

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






    ////////////////////
}
