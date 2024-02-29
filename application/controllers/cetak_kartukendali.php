<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Cetak_kartukendali extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $data['page_title'] = 'KARTU KENDALI SUB KEGIATAN ';
        $this->template->set('title', 'KARTU KENDALI SUB KEGIATAN');
        $this->template->load('template', 'tukd/transaksi/kartu_kendali', $data);
    }

    function cetak_kartu_kendali($lcskpd = '', $giat = '', $ctk = '')
    {
        $spasi = $this->uri->segment(9);
        $nomor = str_replace('123456789', ' ', $this->uri->segment(6));
        $nip2 = str_replace('123456789', ' ', $this->uri->segment(7));
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(8));
        $nbulan = $this->ambil_bulan($this->uri->segment(8));
        $skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov    = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$lcskpd' AND kode in ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip = '$nomor' AND kd_skpd='$lcskpd' AND kode='PPTK'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $cRet = '<TABLE style="border-collapse:collapse;font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>' . $prov . ' </TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>KARTU KENDALI SUB KEGIATAN </b></TD>
                    </TR>
                    </TABLE><br />';

        $cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" width="90%" border="0" cellspacing="0" cellpadding="0" align=center>
                    <TR>
                        <TD align="left" width="15%"><b>OPD</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $lcskpd . ' - ' . $skpd . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="15%"><b>NAMA PROGRAM</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $this->left($giat, 7) . ' - ' . $this->tukd_model->get_nama($this->left($giat, 7), 'nm_program', 'ms_program', 'kd_program') . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="15%"><b>NAMA KEGIATAN</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $this->left($giat, 12) . ' - ' . strtoupper($this->tukd_model->get_nama($this->left($giat, 12), 'nm_kegiatan', 'ms_kegiatan', 'kd_kegiatan')) . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="15%"><b>NAMA SUB KEGIATAN</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $giat . ' - ' . strtoupper($this->tukd_model->get_nama($giat, 'nm_sub_kegiatan', 'ms_sub_kegiatan', 'kd_sub_kegiatan')) . '</b> </TD>
                    </TR>
                    <TR>
                        <TD align="left" width="10%"><b>NAMA PPTK</b> </TD>
                        <TD align="left" width="2%"><b>:</b> </TD>
                        <TD align="left" width="83%"><b>' . $nip1 . ' - ' . $nama1 . '</b> </TD>
                    </TR>
                    </TABLE> <p/><br />';
        $cRet .= "<table style=\"border-collapse:collapse; font-size:12px\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
            <thead>
            <tr>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>NO URUT</b></td>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>KODE REKENING</b></td>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>URAIAN</b></td>
                <td colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>PAGU ANGGARAN</b></td>
                <td colspan =\"3\" align=\"center\" bgcolor=\"#CCCCCC\"><b>REALISASI SUB KEGIATAN</b></td>
                <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>SISA PAGU</b></td>

                </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>MURNI</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>UBAH</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>LS</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>UP/GU</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>TU</b></td>
            </tr>
            </thead>
           ";
        $query = $this->db->query("exec cetak_kartu_kendali '$lcskpd','$nbulan','$giat'");
        
        $no = 0;
        $nilai12 = 0;
        $nilai_ubah12 = 0;
        $real_ls1 = 0;
        $real_up1 = 0;
        $real_tu1 = 0;
        $sisa1 = 0;
        $real_ls11 = 0;
        $real_up11 = 0;
        $real_tu11 = 0;
        $sisa11 = 0;

        foreach ($query->result() as $row) {
            $no = $no + 1;
            $kd_rek6 = $row->kd_rek6;
            $nilai = $row->nilai;
            $nilai_ubah = $row->nilai_ubah;
            $uraian = $row->uraian;
            $real_ls = $row->real_ls;
            $real_up = $row->real_up;
            $real_tu = $row->real_tu;
            $sisa = $row->sisa;

            $nilai1  = empty($nilai) || $nilai == 0 ? '0,00' : number_format($nilai, "2", ",", ".");
            $nilai_ubah1  = empty($nilai_ubah) || $nilai_ubah == 0 ? number_format(0, "2", ",", ".") : number_format($nilai_ubah, "2", ",", ".");
            $real_ls1  = empty($real_ls) || $real_ls == 0 ? number_format(0, "2", ",", ".") : number_format($real_ls, "2", ",", ".");
            $real_up1  = empty($real_up) || $real_up == 0 ? number_format(0, "2", ",", ".") : number_format($real_up, "2", ",", ".");
            $real_tu1  = empty($real_tu) || $real_tu == 0 ? number_format(0, "2", ",", ".") : number_format($real_tu, "2", ",", ".");
            $sisa1  = empty($sisa) || $sisa == 0 ? number_format(0, "2", ",", ".") : number_format($sisa, "2", ",", ".");
            $cRet .= "
                <tr>
                <td align=\"center\" >$no</td>
                <td align=\"left\" >$kd_rek6</td>
                <td align=\"left\" >$uraian</td>
                <td align=\"right\" >$nilai1</td>
                <td align=\"right\" >$nilai_ubah1</td>
                <td align=\"right\" >$real_ls1</td>
                <td align=\"right\" >$real_up1</td>
                <td align=\"right\" >$real_tu1</td>
                <td align=\"right\" >$sisa1</td>
                </tr>
                ";

            $nilai12 = $nilai12 + $nilai;
            $nilai_ubah12 = $nilai_ubah12 + $nilai_ubah;
            $real_ls11 = $real_ls11 + $real_ls;
            $real_up11 = $real_up11 + $real_up;
            $real_tu11 = $real_tu11 + $real_tu;
            $sisa11 = $sisa11 + $sisa;
        }

        $cRet .= "
                <tr>
                <td colspan=\"3\" align=\"center\" >TOTAL</td>
                <td align=\"right\" >" . number_format($nilai12, "2", ",", ".") . "</td>
                <td align=\"right\" >" . number_format($nilai_ubah12, "2", ",", ".") . "</td>
                <td align=\"right\" >" . number_format($real_ls11, "2", ",", ".") . "</td>
                <td align=\"right\" >" . number_format($real_up11, "2", ",", ".") . "</td>
                <td align=\"right\" >" . number_format($real_tu11, "2", ",", ".") . "</td>
                <td align=\"right\" >" . number_format($sisa11, "2", ",", ".") . "</td>
                </tr>
                ";


        $cRet .= "</table>";
        $cRet .= '<TABLE width="100%" style="font-size:12px" border="0" cellspacing="0">
                    <TR>
                        <TD align="center" width="50%"><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD align="center" >Mengetahui,</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >' . $daerah . ', ' . $tanggal_ttd . '</TD>
                    </TR>
                    <TR>
                        <TD align="center" >' . $jabatan . ';</TD>
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
                        <TD align="center" ><u><b>' . $nama . ' </b><br></u> ' . $pangkat . ';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><u><b>' . $nama1 . ' </b><br></u> ' . $pangkat1 . '</TD>
                    </TR>
                    <TR>
                        <TD align="center" >' . $nip . ';</TD>
                        <TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" >' . $nip1 . '</TD>
                    </TR>
                    </TABLE><br/>';

        $data['prev'] = 'DTH';
        switch ($ctk) {
            case 0;
                echo ("<title>KARTU KENDALI</title>");
                echo $cRet;
                break;
            case 1;
                $this->support->_mpdf('', $cRet, 10, 10, 10, 'L', 0, '');
                break;
        }
    }


    function tanggal_format_indonesia($tgl)
    {
        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
    }

    function ambil_bulan($tgl)
    {
        $tanggal  = explode('-', $tgl);
        return  $tanggal[1];
    }

    function tanggal_indonesia($tgl)
    {
        $tanggal  =  substr($tgl, 8, 2);
        $bulan  = substr($tgl, 5, 2);
        $tahun  =  substr($tgl, 0, 4);
        return  $tanggal . '-' . $bulan . '-' . $tahun;
    }

    function getBulan($bln)
    {
        switch ($bln) {
            case  1:
                return  "Januari";
                break;
            case  2:
                return  "Februari";
                break;
            case  3:
                return  "Maret";
                break;
            case  4:
                return  "April";
                break;
            case  5:
                return  "Mei";
                break;
            case  6:
                return  "Juni";
                break;
            case  7:
                return  "Juli";
                break;
            case  8:
                return  "Agustus";
                break;
            case  9:
                return  "September";
                break;
            case  10:
                return  "Oktober";
                break;
            case  11:
                return  "November";
                break;
            case  12:
                return  "Desember";
                break;
            case  0:
                return  "-";
                break;
        }
    }

    function right($value, $count)
    {
        return substr($value, ($count * -1));
    }

    function left($string, $count)
    {
        return substr($string, 0, $count);
    }

    function  dotrek($rek)
    {
        $nrek = strlen($rek);
        switch ($nrek) {
            case 1:
                $rek = $this->left($rek, 1);
                break;
            case 2:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1);
                break;
            case 4:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2);
                break;
            case 6:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2);
                break;
            case 8:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2);
                break;
            case 12:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 4);
                break;
            default:
                $rek = "";
        }
        return $rek;
    }
}
