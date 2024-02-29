<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class SPDController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['page_title'] = 'Register SPD';
        $this->template->set('title', 'Register SPD');
        $this->template->load('template', 'spd/index', $data);
    }

    public function cetakregister_spd()
    {
        $jns = $this->uri->segment(3);
        $skpd = $this->uri->segment(4);
        $tgl1 = $this->uri->segment(5);
        $tgl2 = $this->uri->segment(6);
        $tgl_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(7));
        $nip_ttd = $this->uri->segment(8);
        $nip_ttd = str_replace('%20', ' ', $nip_ttd);
        $tgl11 = $this->tukd_model->tanggal_format_indonesia($tgl1);
        $tgl12 = $this->tukd_model->tanggal_format_indonesia($tgl2);

        $cRet = '';
        $tot = 0;

        $sqldns = "SELECT c.kd_urusan as kd_u1,c.nm_urusan as nm_u1,a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk,d.nm_org,d.kd_org 
        FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan 
        INNER JOIN ms_urusan c ON left(a.kd_urusan,1)=c.kd_urusan 
        inner join ms_organisasi d on left(rtrim(a.kd_skpd),17)=rtrim(d.kd_org)
        WHERE kd_skpd='$skpd' ";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns) {

            $kd_urusan1 = $rowdns->kd_u1;
            $nm_urusan1 = $rowdns->nm_u1;
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd  = $rowdns->kd_sk;
            $nm_skpd  = $rowdns->nm_sk;
            $kd_org  = $rowdns->kd_org;
            $nm_org  = $rowdns->nm_org;
        }

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }

        $sqlsc = "SELECT nama,jabatan FROM ms_ttd where nip='$nip_ttd' AND kode IN('PA','KPA')";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowttd) {
            $nama_ttd = $rowttd->nama;
            $jab_ttd     = $rowttd->jabatan;
        }

        $cRet .= "<table style=\"border-collapse:collapse;font-family: arial; font-size:16px;font-weight:bold;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">  
        <tr><td align=\"center\" colspan=\"4\" >PEMERINTAH KABUPATEN MELAWI<td></tr>
        <tr>
            <td align=\"center\" colspan=\"4\" >REGISTER SPD<td>
        <tr>
        <tr><td align=\"center\" colspan=\"4\" >TAHUN ANGGARAN " . $this->session->userdata('pcThang') . "<td></tr>
        <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
        <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
      </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family: arial; font-size:12px;font-weight:normal;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">   
                    <tr>
                       
                       <td width=\"12%\">KODE / NAMA SKPD</td>
                       <td width=\"1%\">:</td>
                       <td width=\"80%\">$kd_skpd / $nm_skpd </td>
                    </tr> 
                   
                    <tr>
                        <td>PADA TANGGAL</td>
                        <td>:</td>
                        <td>$tgl11 s/d $tgl12</td>
                    </tr>
                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
                     <thead>                       
                        <tr><td width=\"15%\" align=\"center\"><b>No SPD/ Keperluan </b></td>                            
                            <td width=\"6%\" align=\"center\"><b>Tgl SPD</b></td>
                            <td width=\"40%\" align=\"center\"><b>Kode/ Nama SKPD</b></td>
                            <td width=\"12%\" align=\"center\"><b>Nilai (Rp)</b></td>
                            <td width=\"14%\" align=\"center\"><b>Penanda Tangan</b></td>
                            <td width=\"14%\" align=\"center\"><b>NIP</b></td>
                        </tr>
                     </thead>";

        $sql1 = "SELECT a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,b.kd_bkeluar,c.nama,sum(a.nilai) [nilai] from trdspd a join trhspd b on a.no_spd=b.no_spd left join ms_ttd c on b.kd_bkeluar=c.nip where b.tgl_spd BETWEEN '$tgl1' AND '$tgl2' AND b.kd_skpd='$skpd' 
        group by a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama,b.kd_bkeluar
        order by b.tgl_spd,a.no_spd,b.kd_skpd";
        $query = $this->db->query($sql1);
        foreach ($query->result() as $row) {
            $nospd = $row->no_spd;
            $tglspd = $row->tgl_spd;
            $kd_skpd = $row->kd_skpd;
            $nm_skpd = $row->nm_skpd;
            $nm = $row->nama;
            $nip = $row->kd_bkeluar;
            $nilai = $row->nilai;
            $tot += $nilai;
            $tglspd = date("d-m-Y", strtotime($tglspd));
            $nilai = number_format($nilai, "2", ",", ".");
            $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\">$nospd</td>                                     
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" >$tglspd</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\">$kd_skpd - $nm_skpd</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$nilai</td>
                 <td style=\"vertical-align:top;\" align=\"right\">$nm</td>
                 <td style=\"vertical-align:top;\" align=\"right\">$nip</td>
                 </tr>";
        }

        $tot = number_format($tot, "2", ",", ".");
        $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">Jumlah Total</td>                                     
         <td style=\"font-weight:bold;font-size:12px; text-indent:50px;\" colspan=\"3\" align=\"left\">$tot</td>
         </tr>";


        $cRet .= "<tr>
                                <td width=\"100%\" align=\"center\" colspan=\"6\">
                                <table width=\"100%\" border=\"0\">
                                <tr>
                                <td width=\"70%\" align=\"left\" >&nbsp;<br>&nbsp;
                                <br>&nbsp;
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>  
                                </td>
                                <td width=\"30%\" align=\"center\" >$daerah, $tgl_ttd                    
                                <br>$jab_ttd
                                <p>&nbsp;</p>
                                <br>
                                <br>
                                <br>
                                <br>$nama_ttd
                                </td></tr></table></td>
                             </tr>";
        $cRet    .= "</table>";

        if ($jns == 0) {
            echo ("<title>Resgister SPD</title>");
            echo $cRet;
        } else if ($jns == 1) {
            $this->support->_mpdf('', $cRet, 10, 10, 10, 1);
        }
    }
}
