<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class cetak_spj extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('support');
    }

    function index()
    {
        $data['page_title'] = 'SPJ';
        $this->template->set('title', 'SPJ');
        $this->template->load('template', 'tukd/transaksi/spj', $data);
    }

    function anggaran()
    {
        $sql = "SELECT * from tb_status_anggaran where status_aktif='1' order by id ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama'],
            );
            $ii++;
        }
        echo json_encode($result);
    }
    function spj($lcskpd = '', $nbulan = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $ctk = '', $atas = '', $bawah = '', $kiri = '', $kanan = '', $jenis = '', $jns_bp, $jns_ang = '')
    {
        $thn_ang = $this->session->userdata('pcThang');
        $ttd1 = str_replace('123456789', ' ', $ttd1);
        $ttd2 = str_replace('123456789', ' ', $ttd2);
        $lcskpdd = substr($lcskpd, 0, 17) . ".0000";


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where left(kd_skpd,17)=left('$lcskpd',17) and (kode='PA' or kode='KPA') and nip='$ttd2'";
        $lcskpdd = $lcskpd;


        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama2 = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        if ($jns_bp == "bk") {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpdd' and kode='BK' and nip='$ttd1'";
        } else {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BPP' and nip='$ttd1'";
            $lcskpdd = $lcskpdd;
        }
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $sqlanggaran1 = "SELECT jns_ang as anggaran, (case when jns_ang='M' then 'Penetapan'
                when jns_ang='P1' then 'Penyempurnaan I'
                when jns_ang='P2' then 'Penyempurnaan II'
                when jns_ang='P3' then 'Penyempurnaan III'
                when jns_ang='U1' then 'Ubah I' 
                else 'Ubah II' end) as nm_ang from trhrka where kd_skpd='$lcskpd' AND tgl_dpa in (SELECT MAX(tgl_dpa) from trhrka where kd_skpd=trhrka.kd_skpd AND status='1')";
        $sqlanggaran = $this->db->query($sqlanggaran1);
        foreach ($sqlanggaran->result() as $rowttd) {
            $anggaran = $rowttd->anggaran;
        }

        $tanda_ang = 2;
        $thn_ang       = $this->session->userdata('pcThang');

        $skpd = $lcskpd;
        $nama =  $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $bulan = $this->tukd_model->getBulan($nbulan);
        $prv = $this->db->query("SELECT top 1 provinsi,daerah from sclient ");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;
        if ($jenis == '1') {
            $judul = 'SPJ FUNGSIONAL';
        } else if ($jenis == '2') {
            $judul = 'SPJ ADMINISTRATIF';
        } else {
            $judul = 'SPJ BELANJA';
        }

        if (substr($lcskpd, 18, 4) == '0000') {
            $namaskpd = strtoupper("SKPD $nama");
        } else {
            $namaskpd = strtoupper("$nama");
        }
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/logo_melawi.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH KABUPATEN MELAWI</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>$namaskpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN $thn_ang</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        </table>";

        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>";
        $cRet .= "
            
            <tr>
                <td align='center' style='font-size:14px;' colspan='2'>
                 <b> LAPORAN PERTANGGUNGJAWABAN BENDAHARA PENGELUARAN <BR></b>
                 <b>(" . $judul . ")<BR></b>
                 <b>Bulan: $bulan</b>
                </td>
            </tr>
           
            </table><br>";
        $ceksx = substr($skpd, 18, 4);

        // if($ceksx=='0000'){
        //  $cRet .="

        //  <tr>
        //     <td align='left' style='font-size:12px;' width='25%'>
        //       SKPD
        //     </td> 
        //     <td width='75%' style='font-size:12px;'>:$skpd - $nama
        //     </td>         
        // </tr>
        // <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Pengguna Anggaran
        //              </td> 
        //              <td style='font-size:12px;'>:$nama2
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Bendahara Pengeluaran
        //              </td> 
        //              <td style='font-size:12px;'>:$nama1
        //              </td>         
        //          </tr>";
        // }else{
        //  $cRet .="
        // <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Kuasa Pengguna Anggaran                  
        //              </td> 
        //              <td style='font-size:12px;'>:$nama2
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //               Bendahara Pengeluaran Pembantu
        //              </td> 
        //              <td style='font-size:12px;'>:$nama1
        //              </td>         
        //          </tr>";
        // }

        // $cRet .="
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Tahun Anggaran
        //              </td> 
        //              <td style='font-size:12px;'>:$thn_ang
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Bulan
        //              </td> 
        //              <td style='font-size:12px;'>:$bulan
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;' colspan='2'>
        //               &nbsp;
        //              </td> 
        //          </tr>

        //          </table>
        $cRet .= " <table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <thead>
            <tr>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Kode<br>Rekening</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Uraian</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah<br>Anggaran</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Gaji</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Barang & Jasa</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ UP/GU/TU</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah SPJ<br>(LS+UP/GU/TU)<br>s.d Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Sisa Pagu<br>Anggaran</b></td>
            </tr>
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
            </tr>                 
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>1</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>2</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>3</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>4</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>5</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>6</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>7</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>8</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>9</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>10</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>11</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>12</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>13</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>14</td>
            </tr> 
             </thead>
            <tr>
                <td align='center' style='font-size:12px'>&nbsp;</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
            </tr>";

        $att = "spj_skpd '$lcskpd','$nbulan','$jns_ang'";
        $hasil = $this->db->query($att);
        foreach ($hasil->result() as $trh1) {
            $bre                =   $trh1->kd_rek;
            $kode               =   $trh1->kode;
            $wok                =   $trh1->uraian;
            $nilai              =   $trh1->anggaran;
            $real_up_ini        =   $trh1->up_ini;
            $real_up_ll         =   $trh1->up_lalu;
            $real_gaji_ini      =   $trh1->gaji_ini;
            $real_gaji_ll       =   $trh1->gaji_lalu;
            $real_brg_js_ini    =   $trh1->brg_ini;
            $real_brg_js_ll     =   $trh1->brg_lalu;
            // $cp                 =   $trh1->cp;
            $total  = $real_gaji_ll + $real_gaji_ini + $real_brg_js_ll + $real_brg_js_ini + $real_up_ll + $real_up_ini;
            $sisa   = $nilai - $real_gaji_ll - $real_gaji_ini - $real_brg_js_ll - $real_brg_js_ini - $real_up_ll - $real_up_ini;
            $a = strlen($bre);
            if ($a == 7) {
                $cRet .= "
                   <tr>
                        <td   valign='top' width='5%' align='left' style='font-size:10px' ><b>" . $bre . "</b></td>
                        <td   valign='top' align='left' width='28%' style='font-size:10px'><b>" . $wok . "</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($nilai, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
            } else if ($a == 12 || $a == 15) {
                $cRet .= "
                   <tr>
                        <td valign='top' width='8%' align='left' style='font-size:10px' ><b>" . $bre . "</b></td>
                        <td valign='top' align='left' width='25%' style='font-size:10px'><b>" . $wok . "</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($nilai, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
            } else {
                $cRet .= "
                        <tr>
                        <td valign='top' width='8%' align='left' style='font-size:10px' >" . $kode . "</td>
                        <td valign='top' align='left' width='25%' style='font-size:10px'>" . $wok . "</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($nilai, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($total, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($sisa, "2", ",", ".") . "&nbsp;</td>
                    </tr>";
            }
        } /*end foreach*/
        $cRet .= "

        <tr>
            <td valign='top' align='center' style='font-size:10px' >&ensp;</td>
            <td align='left' style='font-size:10px' colspan='2'>Penerimaan :</td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td valign='top' align='center' style='font-size:10px'>&nbsp;</td>
        </tr>";

        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp ='4' AND a.status='1') AS sp2d_gj_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp ='4'  AND a.status='1') AS sp2d_gj_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp in ('5','6')  AND a.status='1') AS sp2d_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp in ('5','6') AND a.status='1') AS sp2d_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();
        $totalsp2d = $trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini + $trh1->sp2d_brjs_ll +
            $trh1->sp2d_brjs_ini + $trh1->sp2d_up_ll + $trh1->sp2d_up_ini;

        $cobacoba = $trh1->sp2d_gj_ll;



        $cRet .= "<tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2' >&ensp;&ensp;- SP2D</td>
            <td align='right' style='font-size:12px'>" . number_format($cobacoba, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll + $trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll + $trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalsp2d, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> ";

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Potongan Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $totalppn = $trh2->jppn_up_ini + $trh2->jppn_up_ll + $trh2->jppn_gaji_ini +
            $trh2->jppn_gaji_ll + $trh2->jppn_brjs_ini + $trh2->jppn_brjs_ll;


        $cRet .= " 
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll + $trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll + $trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll + $trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105010001'; // pph 21 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $totalpph21 = $trh3->jpph21_up_ini + $trh3->jpph21_up_ll + $trh3->jpph21_gaji_ini +
            $trh3->jpph21_gaji_ll + $trh3->jpph21_brjs_ini + $trh3->jpph21_brjs_ll;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll + $trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll + $trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll + $trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();
        $totalpph22 = $trh4->jpph22_up_ini + $trh4->jpph22_up_ll + $trh4->jpph22_gaji_ini +
            $trh4->jpph22_gaji_ll + $trh4->jpph22_brjs_ini + $trh4->jpph22_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll + $trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll + $trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll + $trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh5 = $hasil->row();
        $totalpph23 = $trh5->jpph23_up_ini + $trh5->jpph23_up_ll + $trh5->jpph23_gaji_ini +
            $trh5->jpph23_gaji_ll + $trh5->jpph23_brjs_ini + $trh5->jpph23_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll + $trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll + $trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll + $trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh70 = $hasil->row();
        $totaliwp = $trh70->up_iwp_sdini + $trh70->gj_iwp_sdini + $trh70->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh71 = $hasil->row();
        $totaltap = $trh71->up_tap_sdini + $trh71->gj_tap_sdini + $trh71->ls_tap_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210109010001'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh72 = $hasil->row();
        $totalpph4 = $trh72->up_pph4_sdini + $trh72->gj_pph4_sdini + $trh72->ls_pph4_sdini;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210102010001'; // PPnPn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh15 = $hasil->row();
        $totalppnpn = $trh15->ppnpn_up_ini + $trh15->ppnpn_up_ll + $trh15->ppnpn_gaji_ini +
            $trh15->ppnpn_gaji_ll + $trh15->ppnpn_brjs_ini + $trh15->ppnpn_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll + $trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll + $trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll + $trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        // lain terima

        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHINLAIN a WHERE pengurang_belanja !='1'
                AND a.kd_skpd='$lcskpd'
                -- UNION ALL
                -- SELECT 0 AS jlain_up_ll,
                -- 0 AS jlain_up_ini,
                -- 0 AS jlain_gaji_ll,
                -- 0 jlain_gaji_ini,
                -- 0 AS jlain_brjs_ll,
                -- 0 AS jlain_brjs_ini,
                -- SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai*-1 ELSE 0 END) AS uyhdini
                -- FROM TRHOUTLAIN a WHERE a.kd_skpd='$lcskpd' AND a.thnlalu ='1'
                -- UNION ALL
                -- SELECT 
                -- SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jlain_up_ll, '0' as jlain_up_ini, '0' as jlain_gaji_ll ,'0' as jlain_gaji_ini, '0' as jlain_brjs_ll, '0' as jlain_brjs_ini FROM ms_skpd where kd_skpd='$lcskpd'
                ) a ";

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql);
        $trh6 = $hasil->row();
        $totallain = $trh6->jlain_up_ini + $trh6->jlain_up_ll + $trh6->jlain_gaji_ini +
            $trh6->jlain_gaji_ll + $trh6->jlain_brjs_ini + $trh6->jlain_brjs_ll;


        //tambahan_pajak_tunai
        $sql_pajak_tunai = "SELECT 
        SUM(CASE WHEN MONTH(z.tgl)<'$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_lalu,
        SUM(CASE WHEN MONTH(z.tgl)='$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_ini,
        SUM(CASE WHEN MONTH(z.tgl)<='$nbulan' THEN z.nilai_pot ELSE 0 END) as sd_bln_ini
        from (
        select a.kd_skpd,c.tgl_bukti as tgl,b.kd_rek6 as rek,b.nilai as nilai_pot,b.no_sp2d from trdtransout a 
        LEFT JOIN trhtransout c on c.kd_skpd=a.kd_skpd and a.no_bukti=c.no_bukti
        LEFT JOIN (
        select b.tgl_bukti,a.kd_rek6,a.nilai,b.no_sp2d from trdstrpot a left join trhstrpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti  
        )b on b.no_sp2d=a.no_sp2d
        where right(a.kd_skpd,2)!='00' and c.jns_spp in ('4','5','6') and c.pay='TUNAI' 
        )z 
        where z.no_sp2d <> '' and kd_skpd not in 
        ('1.02.01.01','4.08.05.08','4.08.05.09','4.08.05.10','4.08.03.11','4.08.03.12',
        '4.08.05.07','4.08.03.13','4.08.03.14','4.08.06.06','4.08.06.07','4.08.06.08',
        '4.08.06.09','4.08.04.08','4.08.04.09','4.08.04.10','4.08.04.11','4.08.04.12',
        '4.08.03.10','4.08.02.08','4.08.02.09','4.08.02.10','4.08.02.11','4.08.03.15',
        '4.08.03.16','4.08.02.12','4.08.01.06','4.08.01.08','4.08.01.09','4.08.01.07')
        and kd_skpd='$lcskpd'";

        $sql_pajak_tunai_hasil = $this->db->query($sql_pajak_tunai);
        $trh_pajakTN = $sql_pajak_tunai_hasil->row();
        $tottrh_pajakTN = $trh_pajakTN->bln_lalu + $trh_pajakTN->bln_ini + $trh_pajakTN->sd_bln_ini;

        //Dropping Dana

        $sqldropin = "
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL            
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd='$lcskpd'           
        UNION ALL
        SELECT 
        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
        from tr_setorsimpanan WHERE kd_skpd='$lcskpd' and jenis='3'
        )x
        ";
        /*
        if(substr($lcskpd,8,2)=="00"){
           $sqldropin="
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
                    from tr_setorsimpanan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
                    from tr_ambilsimpanan
                    WHERE kd_skpd='$lcskpd')x
        "; 
        }else{
           $sqldropin="
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL            
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd='$lcskpd')x
        "; 
        }
        
        */


        $hasil = $this->db->query($sqldropin);
        $trhdropin = $hasil->row();
        $totaldropin = $trhdropin->bln_lalu + $trhdropin->bln_ini + $trhdropin->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarin = "
        SELECT SUM(x.jar_bln_lalu) jar_bln_lalu, SUM(x.jar_bln_ini) jar_bln_ini, SUM(x.jar_sd_bln_ini) jar_sd_bln_ini FROM(
        SELECT 
        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
        from 
        -- tr_jpanjar WHERE kd_skpd='$lcskpd' and jns='1'
        tr_jpanjar where jns=1 and kd_skpd='$lcskpd'
        )x";
        $hasil = $this->db->query($sqlpanjarin);
        $trhpanjarin = $hasil->row();
        $totalpanjarin = $trhpanjarin->jar_bln_lalu + $trhpanjarin->jar_bln_ini + $trhpanjarin->jar_sd_bln_ini;

        // //Penyesuaian            
        //          $sqlpenyesuian="
        //          SELECT 
        //          SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
        //          from trhtransout a 
        //          left join 
        //          (
        //          select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
        //          left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
        //          group by a.kd_skpd,a.no_sp2d
        //          ) b on left(b.kd_skpd,7)=left(a.kd_skpd,7) and a.no_sp2d=b.no_sp2d
        //          where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
        //          and a.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

        //          "; 
        //          $hasil_psy = $this->db->query($sqlpenyesuian);
        //          $trhasil_psy = $hasil_psy->row();
        //          $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;

        //-------- TOTAL PENERIMAAN
        $jmtrmgaji_ll =  $trh1->sp2d_gj_ll + $trh2->jppn_gaji_ll + $trh3->jpph21_gaji_ll +
            $trh4->jpph22_gaji_ll + $trh5->jpph23_gaji_ll + $trh6->jlain_gaji_ll + $trh15->ppnpn_gaji_ll +
            $trh70->gj_iwp_lalu + $trh71->gj_tap_lalu + $trh72->gj_pph4_lalu;

        $jmtrmgaji_ini =  $trh1->sp2d_gj_ini + $trh2->jppn_gaji_ini + $trh3->jpph21_gaji_ini +
            $trh4->jpph22_gaji_ini + $trh5->jpph23_gaji_ini + $trh6->jlain_gaji_ini + $trh15->ppnpn_gaji_ini +
            $trh70->gj_iwp_ini + $trh71->gj_tap_ini + $trh72->gj_pph4_ini;

        $jmtrmgaji_sd = $jmtrmgaji_ll + $jmtrmgaji_ini;


        $jmtrmbrjs_ll =  $trh1->sp2d_brjs_ll + $trh2->jppn_brjs_ll + $trh3->jpph21_brjs_ll +
            $trh4->jpph22_brjs_ll + $trh5->jpph23_brjs_ll + $trh6->jlain_brjs_ll + $trh15->ppnpn_brjs_ll +
            $trh70->ls_iwp_lalu + $trh71->ls_tap_lalu + $trh72->ls_pph4_lalu;

        $jmtrmbrjs_ini =  $trh1->sp2d_brjs_ini + $trh2->jppn_brjs_ini + $trh3->jpph21_brjs_ini +
            $trh4->jpph22_brjs_ini + $trh5->jpph23_brjs_ini + $trh6->jlain_brjs_ini + $trh15->ppnpn_brjs_ini +
            $trh70->ls_iwp_ini + $trh71->ls_tap_ini + $trh72->ls_pph4_ini;
        // 
        $jmtrmbrjs_sd = $jmtrmbrjs_ll + $jmtrmbrjs_ini;

        $jmtrmup_ll =  $trh1->sp2d_up_ll + $trh2->jppn_up_ll + $trh3->jpph21_up_ll +
            $trh4->jpph22_up_ll + $trh5->jpph23_up_ll + $trh6->jlain_up_ll + $trh15->ppnpn_up_ll +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_lalu + $trhdropin->bln_lalu + $trhpanjarin->jar_bln_lalu + $trh_pajakTN->bln_lalu;

        $jmtrmup_ini = $trh1->sp2d_up_ini + $trh2->jppn_up_ini + $trh3->jpph21_up_ini +
            $trh4->jpph22_up_ini + $trh5->jpph23_up_ini + $trh6->jlain_up_ini + $trh15->ppnpn_up_ini +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_ini + $trhdropin->bln_ini + $trhpanjarin->jar_bln_ini + $trh_pajakTN->bln_ini;

        $jmtrmup_sd = $jmtrmup_ll + $jmtrmup_ini;


        // <tr>
        //                <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_lalu_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //            </tr>

        $cRet .= "
                   
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain </td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll + $trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll + $trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ini + $tox, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll + $trh6->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>                       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Penerimaan :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini + $tox, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> 
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Pengeluaran :</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $csql = "SELECT sum(gaji_lalu) as spj_gaji_ll, sum(gaji_ini) as spj_gaji_ini, sum(brg_lalu) as spj_brjs_ll, sum(brg_ini) as spj_brjs_ini, sum(up_lalu) as spj_up_ll, sum(up_ini) as spj_up_ini from

        (
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' 
            and jns_spp in (1,2,3) and pay not in ('PANJAR') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' 
        --     and jns_spp in (1,2,3) 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai*-1,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)='$nbulan' and b.pengurang_belanja=1 
        union all

        select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (4) 
        union all

        -- select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (4,5)  and b.kd_satdik=''
        -- union all

        select a.kd_skpd, isnull(a.rupiah*-1,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (1) and b.pot_khusus=1 
        union all



        select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in ('5','6') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in ('6','7') 
        -- union all

        --  select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus<>0 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus=0
        union all

        select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus=2
        union all


        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (1,2,3) and pay not in ('PANJAR') 
        union all

        -- /select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (1,2,3) 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai*-1,0) as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)<'$nbulan' and b.pengurang_belanja=1 
        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (4) 
        union all

        --  select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (4,5)  and b.kd_satdik=''
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.rupiah*-1,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (1) and b.pot_khusus=1
        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in ('5','6') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in ('6','7') 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (2) and b.pot_khusus=2

        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (2) and b.pot_khusus=0
        ) a 
        WHERE a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh7 = $hasil->row();
        $totalspj = $trh7->spj_gaji_ll + $trh7->spj_gaji_ini + $trh7->spj_brjs_ll +
            $trh7->spj_brjs_ini + $trh7->spj_up_ll + $trh7->spj_up_ini;

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- SPJ(LS + UP/GU/TU)</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini + $trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini + $trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini + $trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalspj, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- Penyetoran Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh8 = $hasil->row();
        $totalppn = $trh8->jppn_up_ini + $trh8->jppn_up_ll + $trh8->jppn_gaji_ini +
            $trh8->jppn_gaji_ll + $trh8->jppn_brjs_ini + $trh8->jppn_brjs_ll;

        $cRet .= "
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll + $trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll + $trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll + $trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210105010001'; // pph 21 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh9 = $hasil->row();
        $totalpph21 = $trh9->jpph21_up_ini + $trh9->jpph21_up_ll + $trh9->jpph21_gaji_ini +
            $trh9->jpph21_gaji_ll + $trh9->jpph21_brjs_ini + $trh9->jpph21_brjs_ll;


        $cRet .= "
         <tr> <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll + $trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll + $trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll + $trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh10 = $hasil->row();
        $totalpph22 = $trh10->jpph22_up_ini + $trh10->jpph22_up_ll + $trh10->jpph22_gaji_ini +
            $trh10->jpph22_gaji_ll + $trh10->jpph22_brjs_ini + $trh10->jpph22_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll + $trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll + $trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll + $trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh11 = $hasil->row();
        $totalpph23 = $trh11->jpph23_up_ini + $trh11->jpph23_up_ll + $trh11->jpph23_gaji_ini +
            $trh11->jpph23_gaji_ll + $trh11->jpph23_brjs_ini + $trh11->jpph23_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll + $trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll + $trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll + $trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh73 = $hasil->row();
        $totaliwp_setor = $trh73->up_iwp_sdini + $trh73->gj_iwp_sdini + $trh73->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh74 = $hasil->row();
        $totaltap_setor = $trh74->up_tap_sdini + $trh74->gj_tap_sdini + $trh74->ls_tap_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210109010001'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh75 = $hasil->row();
        $totalpph4_setor = $trh75->up_pph4_sdini + $trh75->gj_pph4_sdini + $trh75->ls_pph4_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";






        $lcrek = '210102010001'; // PPnpn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh16 = $hasil->row();
        $totalppnpn = $trh16->ppnpn_up_ini + $trh16->ppnpn_up_ll + $trh16->ppnpn_gaji_ini +
            $trh16->ppnpn_gaji_ll + $trh16->ppnpn_brjs_ini + $trh16->ppnpn_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll + $trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll + $trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll + $trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        // // HKPG
        //          $csql = "SELECT 
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
        //  FROM trdkasin_pkd a 
        //  INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
        //  WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        //          $hasil = $this->db->query($csql);
        //          $trhxx = $hasil->row();
        //          $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;


        //          $cRet .="
        //           <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //              <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- HKPG</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($totalhkpg,"2",",",".")."&nbsp;</td>
        //  <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //          </tr>";

        // Potongan Penghasilan Lainnya
        $csql = "SELECT 
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_sdini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_sdini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_sdini
            FROM trdkasin_pkd a 
            INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        $hasil = $this->db->query($csql);
        $trhxy = $hasil->row();
        $totallain = $trhxy->up_lain_sdini + $trhxy->gj_lain_sdini + $trhxy->ls_lain_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Penghasilan Lainnya</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";
        // HKPG
        $csql = "SELECT 
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
            FROM trdkasin_pkd a 
            INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE a.kd_skpd = '$lcskpd' AND jns_trans='5' AND LEFT(kd_rek6,1)<>4";

        $hasil = $this->db->query($csql);
        $trhxx = $hasil->row();
        $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;


        $cRet .= "
         <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- HKPG</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($totalhkpg, "2", ",", ".") . "&nbsp;</td>
            <td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
        </tr>";

        // CONTRA POS
        $csql = "SELECT 
        SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ini,
        SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ll,
        SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ini,
        SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ll,
        SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ini,
        SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ll
        from (select rupiah as nilai,jns_trans,pot_khusus,jns_cp,d.tgl_sts ,d.kd_skpd from 
        trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd where d.kd_skpd ='$lcskpd' AND 
        ((jns_trans='5' AND pot_khusus in('0')) OR jns_trans='1')) z";

        $hasil = $this->db->query($csql);
        $trh_x = $hasil->row();
        $total_cp = $trh_x->cp_spj_up_ini + $trh_x->cp_spj_up_ll + $trh_x->cp_spj_gaji_ini +
            $trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_brjs_ini + $trh_x->cp_spj_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Contra Pos</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll + $trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll + $trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($total_cp, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        // lain lain setoran
        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp IN ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp IN ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHOUTLAIN a 
                WHERE a.kd_skpd='$lcskpd'
                ) a ";
        $hasil = $this->db->query($csql);
        $trh12 = $hasil->row();
        $totallain = $trh12->jlain_up_ini + $trh12->jlain_up_ll + $trh12->jlain_gaji_ini +
            $trh12->jlain_gaji_ll + $trh12->jlain_brjs_ini + $trh12->jlain_brjs_ll;

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'=$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ini = $hasil->row('jumlah');
        // echo  $tox_ini;
        $tox_ini = (empty($tox_ini) ? 0 : $tox_ini);

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'<$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ll = $hasil->row('jumlah');
        $tox_ll = (empty($tox_ll) ? 0 : $tox_ll);

        //          echo  '-'.$tox_ini;
        //          echo  '-'.$tox_ll;


        //Dropping Dana

        //if(substr($lcskpd,8,2)=="00"){
        $sqldropout = "SELECT SUM(z.bln_lalu) bln_lalu, SUM(z.bln_ini) bln_ini, SUM(z.sd_bln_ini) sd_bln_ini from(
                    select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd_sumber='$lcskpd'
                    UNION ALL
                    select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd_sumber='$lcskpd'
                    )z";

        $hasil = $this->db->query($sqldropout);
        $trhdropout = $hasil->row();
        $totaldropout = $trhdropout->bln_lalu + $trhdropout->bln_ini + $trhdropout->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarout = "SELECT 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
                    from tr_panjar 
                    WHERE kd_skpd='$lcskpd' and jns='1'";
        $hasil = $this->db->query($sqlpanjarout);
        $trhpanjarout = $hasil->row();
        $totalpanjarout = $trhpanjarout->jar_bln_lalu + $trhpanjarout->jar_bln_ini + $trhpanjarout->jar_sd_bln_ini;


        // //Penyesuaian            
        //          $sqlpenyesuian="
        //          SELECT 
        //          SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
        //          from trhtransout a 
        //          left join 
        //          (
        //          select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
        //          left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
        //          group by a.kd_skpd,a.no_sp2d
        //          ) b on left(b.kd_skpd,17)=left(a.kd_skpd,17) and a.no_sp2d=b.no_sp2d
        //          where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
        //          and b.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

        //          "; 
        //          $hasil_psy = $this->db->query($sqlpenyesuian);
        //          $trhasil_psy = $hasil_psy->row();
        //          $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;


        $jmsetgaji_ll =  $trh7->spj_gaji_ll + $trh8->jppn_gaji_ll + $trh9->jpph21_gaji_ll + $trh16->ppnpn_gaji_ll +
            $trh10->jpph22_gaji_ll + $trh11->jpph23_gaji_ll + $trh12->jlain_gaji_ll + $trh_x->cp_spj_gaji_ll +
            $trh73->gj_iwp_lalu + $trh74->gj_tap_lalu + $trh75->gj_pph4_lalu + $trhxx->gj_hkpg_lalu + $trhxy->gj_lain_lalu;

        $jmsetgaji_ini = $trh7->spj_gaji_ini + $trh8->jppn_gaji_ini + $trh9->jpph21_gaji_ini + $trh16->ppnpn_gaji_ini +
            $trh10->jpph22_gaji_ini + $trh11->jpph23_gaji_ini + $trh12->jlain_gaji_ini + $trh_x->cp_spj_gaji_ini +
            $trh73->gj_iwp_ini + $trh74->gj_tap_ini + $trh75->gj_pph4_ini + $trhxx->gj_hkpg_ini + $trhxy->gj_lain_ini;

        $jmsetgaji_sd = $jmsetgaji_ll + $jmsetgaji_ini;


        $jmsetbrjs_ll =  $trh7->spj_brjs_ll + $trh8->jppn_brjs_ll + $trh9->jpph21_brjs_ll + $trh16->ppnpn_brjs_ll +
            $trh10->jpph22_brjs_ll + $trh11->jpph23_brjs_ll + $trh12->jlain_brjs_ll + $trh_x->cp_spj_brjs_ll +
            $trh73->ls_iwp_lalu + $trh74->ls_tap_lalu + $trh75->ls_pph4_lalu + $trhxx->ls_hkpg_lalu + $trhxy->ls_lain_lalu;
        // $trh_x->cp_spj_brjs_ll 

        $jmsetbrjs_ini =  $trh7->spj_brjs_ini + $trh8->jppn_brjs_ini + $trh9->jpph21_brjs_ini + $trh16->ppnpn_brjs_ini +
            $trh10->jpph22_brjs_ini + $trh11->jpph23_brjs_ini + $trh12->jlain_brjs_ini  + $trh_x->cp_spj_brjs_ini +
            $trh73->ls_iwp_ini + $trh74->ls_tap_ini + $trh75->ls_pph4_ini + $trhxx->ls_hkpg_ini + $trhxy->ls_lain_ini;
        // $trh_x->cp_spj_brjs_ini

        $jmsetbrjs_sd = $jmsetbrjs_ll + $jmsetbrjs_ini;
        /* 
        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll +
                $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll; */

        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll + $trh16->ppnpn_up_ll +
            $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll + $trh_x->cp_spj_up_ll +
            $trh73->up_iwp_lalu + $trh74->up_tap_lalu + $trh75->up_pph4_lalu + $trhxx->up_hkpg_lalu + $trhxy->up_lain_lalu + $trhpanjarout->jar_bln_lalu;

        $jmsetup_ini =  $trh7->spj_up_ini + $trh8->jppn_up_ini + $trh9->jpph21_up_ini + $trh16->ppnpn_up_ini +
            $trh10->jpph22_up_ini + $trh11->jpph23_up_ini + $trh12->jlain_up_ini + $trh_x->cp_spj_up_ini +
            $trh73->up_iwp_ini + $trh74->up_tap_ini + $trh75->up_pph4_ini + $trhxx->up_hkpg_ini + $trhxy->up_lain_ini +
            $trhpanjarout->jar_bln_ini;

        $jmsetup_sd = $jmsetup_ll + $jmsetup_ini;

        // <tr>
        //             <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //             <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_lalu_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //         </tr>

        $cRet .= "
                   
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll + $trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll + $trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll + $trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
    
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>

        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        ";

        $cRet .= "
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Pengeluaran :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd + $jmsetbrjs_sd + $jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> 
                    
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Saldo Kas</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll - $jmsetgaji_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini - $jmsetgaji_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd - $jmsetgaji_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll - $jmsetbrjs_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini - $jmsetbrjs_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd - $jmsetbrjs_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll - $jmsetup_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini - $jmsetup_ini + $tox, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd - $jmsetup_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd - $jmsetgaji_sd - $jmsetbrjs_sd - $jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
       <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
       </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        </table>";
        if ($jenis == '1') {


            $cRet .= '<TABLE width="100%" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        } else if ($jenis == '2') {

            $cRet .= '<TABLE width="100%" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        }

        $data['prev'] = $cRet;
        if ($ctk == 0) {
            echo "<title>  SPJ $bulan</title>";
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, 'L', 0, '', $atas, $bawah, $kiri, $kanan);
        }
    }

    function spjadministrasi_melawi($lcskpd = '', $nbulan = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $ctk = '', $atas = '', $bawah = '', $kiri = '', $kanan = '', $jenis = '', $jns_bp, $jns_ang = '')
    {
        $ttd1 = str_replace('123456789', ' ', $ttd1);
        $ttd2 = str_replace('123456789', ' ', $ttd2);
        $lcskpdd = substr($lcskpd, 0, 17) . ".0000";


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where left(kd_skpd,17)=left('$lcskpd',17) and (kode='PA' or kode='KPA') and nip='$ttd2'";
        $lcskpdd = $lcskpd;


        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama2 = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        if ($jns_bp == "bk") {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpdd' and kode='BK' and nip='$ttd1'";
        } else {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BPP' and nip='$ttd1'";
            $lcskpdd = $lcskpdd;
        }
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $sqlanggaran1 = "SELECT jns_ang as anggaran, (case when jns_ang='M' then 'Penetapan'
                when jns_ang='P1' then 'Penyempurnaan I'
                when jns_ang='P2' then 'Penyempurnaan II'
                when jns_ang='P3' then 'Penyempurnaan III'
                when jns_ang='U1' then 'Ubah I' 
                else 'Ubah II' end) as nm_ang from trhrka where kd_skpd='$lcskpd' AND tgl_dpa in (SELECT MAX(tgl_dpa) from trhrka where kd_skpd=trhrka.kd_skpd AND status='1')";
        $sqlanggaran = $this->db->query($sqlanggaran1);
        foreach ($sqlanggaran->result() as $rowttd) {
            $anggaran = $rowttd->anggaran;
        }

        $tanda_ang = 2;
        $thn_ang       = $this->session->userdata('pcThang');

        $skpd = $lcskpd;
        $nama =  $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $bulan = $this->tukd_model->getBulan($nbulan);
        $prv = $this->db->query("SELECT top 1 provinsi,daerah from sclient ");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;
        if ($jenis == '1') {
            $judul = 'SPJ FUNGSIONAL';
        } else if ($jenis == '2') {
            $judul = 'SPJ ADMINISTRATIF';
        } else {
            $judul = 'SPJ BELANJA';
        }

        if (substr($lcskpd, 18, 4) == '0000') {
            $namaskpd = strtoupper("SKPD $nama");
        } else {
            $namaskpd = strtoupper("$nama");
        }
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/logo_melawi.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH KABUPATEN MELAWI</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>$namaskpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN 2022 </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        </table>";

        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>";
        $cRet .= "
            
            <tr>
                <td align='center' style='font-size:14px;' colspan='2'>
                 <b> LAPORAN PERTANGGUNGJAWABAN BENDAHARA PENGELUARAN <BR></b>
                 <b>(" . $judul . ")<BR></b>
                 <b>Bulan: $bulan</b>
                </td>
            </tr>
           
            </table><br>";
        $ceksx = substr($skpd, 18, 4);

        // if($ceksx=='0000'){
        //  $cRet .="

        //  <tr>
        //     <td align='left' style='font-size:12px;' width='25%'>
        //       SKPD
        //     </td> 
        //     <td width='75%' style='font-size:12px;'>:$skpd - $nama
        //     </td>         
        // </tr>
        // <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Pengguna Anggaran
        //              </td> 
        //              <td style='font-size:12px;'>:$nama2
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Bendahara Pengeluaran
        //              </td> 
        //              <td style='font-size:12px;'>:$nama1
        //              </td>         
        //          </tr>";
        // }else{
        //  $cRet .="
        // <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Kuasa Pengguna Anggaran                  
        //              </td> 
        //              <td style='font-size:12px;'>:$nama2
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //               Bendahara Pengeluaran Pembantu
        //              </td> 
        //              <td style='font-size:12px;'>:$nama1
        //              </td>         
        //          </tr>";
        // }

        // $cRet .="
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Tahun Anggaran
        //              </td> 
        //              <td style='font-size:12px;'>:$thn_ang
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Bulan
        //              </td> 
        //              <td style='font-size:12px;'>:$bulan
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;' colspan='2'>
        //               &nbsp;
        //              </td> 
        //          </tr>

        //          </table>
        $cRet .= " <table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <thead>
            <tr>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Kode<br>Rekening</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Uraian</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah<br>Anggaran</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Gaji</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Barang & Jasa</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ UP/GU/TU</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah SPJ<br>(LS+UP/GU/TU)<br>s.d Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Contra Post</td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Sisa Pagu<br>Anggaran</b></td>
            </tr>
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
            </tr>                 
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>1</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>2</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>3</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>4</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>5</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>6</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>7</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>8</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>9</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>10</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>11</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>12</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>13</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>14</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>15</td>
            </tr> 
             </thead>
            <tr>
                <td align='center' style='font-size:12px'>&nbsp;</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
            </tr>";

        $att = "spj_skpd_melawi '$lcskpd','$nbulan','$jns_ang'";
        $hasil = $this->db->query($att);
        foreach ($hasil->result() as $trh1) {
            $bre                =   $trh1->kd_rek;
            $kode               =   $trh1->kode;
            $wok                =   $trh1->uraian;
            $nilai              =   $trh1->anggaran;
            $real_up_ini        =   $trh1->up_ini;
            $real_up_ll         =   $trh1->up_lalu;
            $real_gaji_ini      =   $trh1->gaji_ini;
            $real_gaji_ll       =   $trh1->gaji_lalu;
            $real_brg_js_ini    =   $trh1->brg_ini;
            $real_brg_js_ll     =   $trh1->brg_lalu;
            $cp                 =   $trh1->cp;
            $total  = $real_gaji_ll + $real_gaji_ini + $real_brg_js_ll + $real_brg_js_ini + $real_up_ll + $real_up_ini;
            $sisa   = $nilai - $real_gaji_ll - $real_gaji_ini - $real_brg_js_ll - $real_brg_js_ini - $real_up_ll - $real_up_ini + $cp;
            $a = strlen($bre);
            if ($a == 7) {
                $cRet .= "
                   <tr>
                        <td   valign='top' width='5%' align='left' style='font-size:10px' ><b>" . $bre . "</b></td>
                        <td   valign='top' align='left' width='28%' style='font-size:10px'><b>" . $wok . "</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($nilai, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($cp, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
            } else if ($a == 12 || $a == 15) {
                $cRet .= "
                   <tr>
                        <td valign='top' width='8%' align='left' style='font-size:10px' ><b>" . $bre . "</b></td>
                        <td valign='top' align='left' width='25%' style='font-size:10px'><b>" . $wok . "</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($nilai, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($cp, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
            } else {
                $cRet .= "
                        <tr>
                        <td valign='top' width='8%' align='left' style='font-size:10px' >" . $kode . "</td>
                        <td valign='top' align='left' width='25%' style='font-size:10px'>" . $wok . "</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($nilai, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($total, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($cp, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($sisa, "2", ",", ".") . "&nbsp;</td>
                    </tr>";
            }
        } /*end foreach*/
        $cRet .= "

        <tr>
            <td valign='top' align='center' style='font-size:10px' >&ensp;</td>
            <td align='left' style='font-size:10px' colspan='2'>Penerimaan :</td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='top' colspan='2' align='center' style='font-size:10px'>&nbsp;</td>
        </tr>";

        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp ='4' AND a.status='1') AS sp2d_gj_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp ='4'  AND a.status='1') AS sp2d_gj_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp in ('5','6')  AND a.status='1') AS sp2d_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp in ('5','6') AND a.status='1') AS sp2d_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();
        $totalsp2d = $trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini + $trh1->sp2d_brjs_ll +
            $trh1->sp2d_brjs_ini + $trh1->sp2d_up_ll + $trh1->sp2d_up_ini;

        $cobacoba = $trh1->sp2d_gj_ll;



        $cRet .= "<tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2' >&ensp;&ensp;- SP2D</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll + $trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll + $trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalsp2d, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> ";

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Potongan Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $totalppn = $trh2->jppn_up_ini + $trh2->jppn_up_ll + $trh2->jppn_gaji_ini +
            $trh2->jppn_gaji_ll + $trh2->jppn_brjs_ini + $trh2->jppn_brjs_ll;


        $cRet .= " 
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll + $trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll + $trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll + $trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105010001'; // pph 21 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $totalpph21 = $trh3->jpph21_up_ini + $trh3->jpph21_up_ll + $trh3->jpph21_gaji_ini +
            $trh3->jpph21_gaji_ll + $trh3->jpph21_brjs_ini + $trh3->jpph21_brjs_ll;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll + $trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll + $trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll + $trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();
        $totalpph22 = $trh4->jpph22_up_ini + $trh4->jpph22_up_ll + $trh4->jpph22_gaji_ini +
            $trh4->jpph22_gaji_ll + $trh4->jpph22_brjs_ini + $trh4->jpph22_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll + $trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll + $trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll + $trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh5 = $hasil->row();
        $totalpph23 = $trh5->jpph23_up_ini + $trh5->jpph23_up_ll + $trh5->jpph23_gaji_ini +
            $trh5->jpph23_gaji_ll + $trh5->jpph23_brjs_ini + $trh5->jpph23_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll + $trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll + $trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll + $trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh70 = $hasil->row();
        $totaliwp = $trh70->up_iwp_sdini + $trh70->gj_iwp_sdini + $trh70->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh71 = $hasil->row();
        $totaltap = $trh71->up_tap_sdini + $trh71->gj_tap_sdini + $trh71->ls_tap_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210109010001'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh72 = $hasil->row();
        $totalpph4 = $trh72->up_pph4_sdini + $trh72->gj_pph4_sdini + $trh72->ls_pph4_sdini;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210102010001'; // PPnPn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh15 = $hasil->row();
        $totalppnpn = $trh15->ppnpn_up_ini + $trh15->ppnpn_up_ll + $trh15->ppnpn_gaji_ini +
            $trh15->ppnpn_gaji_ll + $trh15->ppnpn_brjs_ini + $trh15->ppnpn_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll + $trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll + $trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll + $trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        // lain terima

        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHINLAIN a WHERE pengurang_belanja !='1'
                AND a.kd_skpd='$lcskpd'
                -- union all
                -- SELECT 
                -- SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jlain_up_ll, '0' as jlain_up_ini, '0' as jlain_gaji_ll ,'0' as jlain_gaji_ini, '0' as jlain_brjs_ll, '0' as jlain_brjs_ini
                -- FROM ms_skpd where kd_skpd='$lcskpd'
                ) a ";

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql);
        $trh6 = $hasil->row();
        $totallain = $trh6->jlain_up_ini + $trh6->jlain_up_ll + $trh6->jlain_gaji_ini +
            $trh6->jlain_gaji_ll + $trh6->jlain_brjs_ini + $trh6->jlain_brjs_ll;


        //tambahan_pajak_tunai

        $sql_pajak_tunai = "SELECT 
        SUM(CASE WHEN MONTH(z.tgl)<'$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_lalu,
        SUM(CASE WHEN MONTH(z.tgl)='$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_ini,
        SUM(CASE WHEN MONTH(z.tgl)<='$nbulan' THEN z.nilai_pot ELSE 0 END) as sd_bln_ini
        from (
        select a.kd_skpd,c.tgl_bukti as tgl,b.kd_rek6 as rek,b.nilai as nilai_pot,b.no_sp2d from trdtransout a 
        LEFT JOIN trhtransout c on c.kd_skpd=a.kd_skpd and a.no_bukti=c.no_bukti
        LEFT JOIN (
        select b.tgl_bukti,a.kd_rek6,a.nilai,b.no_sp2d from trdstrpot a left join trhstrpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti  
        )b on b.no_sp2d=a.no_sp2d
        where right(a.kd_skpd,2)!='00' and c.jns_spp in ('4','5','6') and c.pay='TUNAI' 
        )z 
        where z.no_sp2d <> '' and kd_skpd not in 
        ('1.02.01.01','4.08.05.08','4.08.05.09','4.08.05.10','4.08.03.11','4.08.03.12',
        '4.08.05.07','4.08.03.13','4.08.03.14','4.08.06.06','4.08.06.07','4.08.06.08',
        '4.08.06.09','4.08.04.08','4.08.04.09','4.08.04.10','4.08.04.11','4.08.04.12',
        '4.08.03.10','4.08.02.08','4.08.02.09','4.08.02.10','4.08.02.11','4.08.03.15',
        '4.08.03.16','4.08.02.12','4.08.01.06','4.08.01.08','4.08.01.09','4.08.01.07')
        and kd_skpd='$lcskpd'";

        $sql_pajak_tunai_hasil = $this->db->query($sql_pajak_tunai);
        $trh_pajakTN = $sql_pajak_tunai_hasil->row();
        $tottrh_pajakTN = $trh_pajakTN->bln_lalu + $trh_pajakTN->bln_ini + $trh_pajakTN->sd_bln_ini;

        //Dropping Dana

        $sqldropin = "
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL            
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd='$lcskpd'           
        UNION ALL
        SELECT 
        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
        from tr_setorsimpanan WHERE kd_skpd='$lcskpd' and jenis='3'
        )x
        ";
        /*
        if(substr($lcskpd,8,2)=="00"){
           $sqldropin="
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
                    from tr_setorsimpanan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
                    from tr_ambilsimpanan
                    WHERE kd_skpd='$lcskpd')x
        "; 
        }else{
           $sqldropin="
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL            
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd='$lcskpd')x
        "; 
        }
        
        */


        $hasil = $this->db->query($sqldropin);
        $trhdropin = $hasil->row();
        $totaldropin = $trhdropin->bln_lalu + $trhdropin->bln_ini + $trhdropin->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarin = "
        SELECT SUM(x.jar_bln_lalu) jar_bln_lalu, SUM(x.jar_bln_ini) jar_bln_ini, SUM(x.jar_sd_bln_ini) jar_sd_bln_ini FROM(
        SELECT 
        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
        from 
        -- tr_jpanjar WHERE kd_skpd='$lcskpd' and jns='1'
        tr_jpanjar where jns=1 and kd_skpd='$lcskpd'
        )x";
        $hasil = $this->db->query($sqlpanjarin);
        $trhpanjarin = $hasil->row();
        $totalpanjarin = $trhpanjarin->jar_bln_lalu + $trhpanjarin->jar_bln_ini + $trhpanjarin->jar_sd_bln_ini;

        // //Penyesuaian            
        //          $sqlpenyesuian="
        //          SELECT 
        //          SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
        //          from trhtransout a 
        //          left join 
        //          (
        //          select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
        //          left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
        //          group by a.kd_skpd,a.no_sp2d
        //          ) b on left(b.kd_skpd,7)=left(a.kd_skpd,7) and a.no_sp2d=b.no_sp2d
        //          where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
        //          and a.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

        //          "; 
        //          $hasil_psy = $this->db->query($sqlpenyesuian);
        //          $trhasil_psy = $hasil_psy->row();
        //          $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;

        //-------- TOTAL PENERIMAAN
        $jmtrmgaji_ll =  $trh1->sp2d_gj_ll + $trh2->jppn_gaji_ll + $trh3->jpph21_gaji_ll +
            $trh4->jpph22_gaji_ll + $trh5->jpph23_gaji_ll + $trh6->jlain_gaji_ll + $trh15->ppnpn_gaji_ll +
            $trh70->gj_iwp_lalu + $trh71->gj_tap_lalu + $trh72->gj_pph4_lalu;

        $jmtrmgaji_ini =  $trh1->sp2d_gj_ini + $trh2->jppn_gaji_ini + $trh3->jpph21_gaji_ini +
            $trh4->jpph22_gaji_ini + $trh5->jpph23_gaji_ini + $trh6->jlain_gaji_ini + $trh15->ppnpn_gaji_ini +
            $trh70->gj_iwp_ini + $trh71->gj_tap_ini + $trh72->gj_pph4_ini;

        $jmtrmgaji_sd = $jmtrmgaji_ll + $jmtrmgaji_ini;


        $jmtrmbrjs_ll =  $trh1->sp2d_brjs_ll + $trh2->jppn_brjs_ll + $trh3->jpph21_brjs_ll +
            $trh4->jpph22_brjs_ll + $trh5->jpph23_brjs_ll + $trh6->jlain_brjs_ll + $trh15->ppnpn_brjs_ll +
            $trh70->ls_iwp_lalu + $trh71->ls_tap_lalu + $trh72->ls_pph4_lalu;

        $jmtrmbrjs_ini =  $trh1->sp2d_brjs_ini + $trh2->jppn_brjs_ini + $trh3->jpph21_brjs_ini +
            $trh4->jpph22_brjs_ini + $trh5->jpph23_brjs_ini + $trh6->jlain_brjs_ini + $trh15->ppnpn_brjs_ini +
            $trh70->ls_iwp_ini + $trh71->ls_tap_ini + $trh72->ls_pph4_ini;

        $jmtrmbrjs_sd = $jmtrmbrjs_ll + $jmtrmbrjs_ini;

        $jmtrmup_ll =  $trh1->sp2d_up_ll + $trh2->jppn_up_ll + $trh3->jpph21_up_ll +
            $trh4->jpph22_up_ll + $trh5->jpph23_up_ll + $trh6->jlain_up_ll + $tox + $trh15->ppnpn_up_ll +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_lalu + $trhdropin->bln_lalu + $trhpanjarin->jar_bln_lalu + $trh_pajakTN->bln_lalu;

        $jmtrmup_ini =  $trh1->sp2d_up_ini + $trh2->jppn_up_ini + $trh3->jpph21_up_ini +
            $trh4->jpph22_up_ini + $trh5->jpph23_up_ini + $trh6->jlain_up_ini + $trh15->ppnpn_up_ini +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_ini + $trhdropin->bln_ini + $trhpanjarin->jar_bln_ini + $trh_pajakTN->bln_ini;

        $jmtrmup_sd = $jmtrmup_ll + $jmtrmup_ini;


        // <tr>
        //                <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_lalu_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='left'  style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //            </tr>

        $cRet .= "
                   
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll + $trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll + $trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll + $trh6->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>                       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Penerimaan :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> 
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Pengeluaran :</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $csql = "SELECT sum(gaji_lalu) as spj_gaji_ll, sum(gaji_ini) as spj_gaji_ini, sum(brg_lalu) as spj_brjs_ll, sum(brg_ini) as spj_brjs_ini, sum(up_lalu) as spj_up_ll, sum(up_ini) as spj_up_ini from

        (
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' 
            and jns_spp in (1,2,3) and pay not in ('PANJAR') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' 
        --     and jns_spp in (1,2,3) 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai*-1,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)='$nbulan' and b.pengurang_belanja=1 
        union all

        select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (4) 
        union all

        -- select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (4,5)  and b.kd_satdik=''
        -- union all

        select a.kd_skpd, isnull(a.rupiah*-1,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (1) and b.pot_khusus=1 
        union all



        select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in ('5','6') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in ('6','7') 
        -- union all

        --  select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus<>0 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus=0
        union all

        select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus=2
        union all


        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (1,2,3) and pay not in ('PANJAR') 
        union all

        -- /select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (1,2,3) 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai*-1,0) as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)<'$nbulan' and b.pengurang_belanja=1 
        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (4) 
        union all

        --  select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (4,5)  and b.kd_satdik=''
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.rupiah*-1,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (1) and b.pot_khusus=1
        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in ('5','6') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in ('6','7') 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (2) and b.pot_khusus=2

        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (2) and b.pot_khusus=0



        
        ) a 
        WHERE a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh7 = $hasil->row();
        $totalspj = $trh7->spj_gaji_ll + $trh7->spj_gaji_ini + $trh7->spj_brjs_ll +
            $trh7->spj_brjs_ini + $trh7->spj_up_ll + $trh7->spj_up_ini;

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- SPJ(LS + UP/GU/TU)</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini + $trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini + $trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini + $trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalspj, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- Penyetoran Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh8 = $hasil->row();
        $totalppn = $trh8->jppn_up_ini + $trh8->jppn_up_ll + $trh8->jppn_gaji_ini +
            $trh8->jppn_gaji_ll + $trh8->jppn_brjs_ini + $trh8->jppn_brjs_ll;

        $cRet .= "
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll + $trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll + $trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll + $trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210105010001'; // pph 21 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh9 = $hasil->row();
        $totalpph21 = $trh9->jpph21_up_ini + $trh9->jpph21_up_ll + $trh9->jpph21_gaji_ini +
            $trh9->jpph21_gaji_ll + $trh9->jpph21_brjs_ini + $trh9->jpph21_brjs_ll;


        $cRet .= "
         <tr> <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll + $trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll + $trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll + $trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh10 = $hasil->row();
        $totalpph22 = $trh10->jpph22_up_ini + $trh10->jpph22_up_ll + $trh10->jpph22_gaji_ini +
            $trh10->jpph22_gaji_ll + $trh10->jpph22_brjs_ini + $trh10->jpph22_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll + $trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll + $trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll + $trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh11 = $hasil->row();
        $totalpph23 = $trh11->jpph23_up_ini + $trh11->jpph23_up_ll + $trh11->jpph23_gaji_ini +
            $trh11->jpph23_gaji_ll + $trh11->jpph23_brjs_ini + $trh11->jpph23_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll + $trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll + $trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll + $trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh73 = $hasil->row();
        $totaliwp_setor = $trh73->up_iwp_sdini + $trh73->gj_iwp_sdini + $trh73->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh74 = $hasil->row();
        $totaltap_setor = $trh74->up_tap_sdini + $trh74->gj_tap_sdini + $trh74->ls_tap_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210109010001'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh75 = $hasil->row();
        $totalpph4_setor = $trh75->up_pph4_sdini + $trh75->gj_pph4_sdini + $trh75->ls_pph4_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";






        $lcrek = '210102010001'; // PPnpn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh16 = $hasil->row();
        $totalppnpn = $trh16->ppnpn_up_ini + $trh16->ppnpn_up_ll + $trh16->ppnpn_gaji_ini +
            $trh16->ppnpn_gaji_ll + $trh16->ppnpn_brjs_ini + $trh16->ppnpn_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll + $trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll + $trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll + $trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        // // HKPG
        //          $csql = "SELECT 
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
        //  FROM trdkasin_pkd a 
        //  INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
        //  WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        //          $hasil = $this->db->query($csql);
        //          $trhxx = $hasil->row();
        //          $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;


        //          $cRet .="
        //           <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //              <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- HKPG</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($totalhkpg,"2",",",".")."&nbsp;</td>
        //  <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //          </tr>";

        // Potongan Penghasilan Lainnya
        $csql = "SELECT 
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_sdini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_sdini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_sdini
            FROM trdkasin_pkd a 
            INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        $hasil = $this->db->query($csql);
        $trhxy = $hasil->row();
        $totallain = $trhxy->up_lain_sdini + $trhxy->gj_lain_sdini + $trhxy->ls_lain_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Penghasilan Lainnya</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";
        // HKPG
        $csql = "SELECT 
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
            FROM trdkasin_pkd a 
            INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE a.kd_skpd = '$lcskpd' AND jns_trans='5' AND LEFT(kd_rek6,1)<>4";

        $hasil = $this->db->query($csql);
        $trhxx = $hasil->row();
        $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;


        $cRet .= "
         <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- HKPG</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($totalhkpg, "2", ",", ".") . "&nbsp;</td>
            <td align=\"left\" colspan='2' style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
        </tr>";

        // CONTRA POS
        $csql = "SELECT 
        SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ini,
        SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ll,
        SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ini,
        SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ll,
        SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ini,
        SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ll
        from (select rupiah as nilai,jns_trans,pot_khusus,jns_cp,d.tgl_sts ,d.kd_skpd from 
        trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd where d.kd_skpd ='$lcskpd' AND 
        ((jns_trans='5' AND pot_khusus in('0')) OR jns_trans='1')) z";

        $hasil = $this->db->query($csql);
        $trh_x = $hasil->row();
        $total_cp = $trh_x->cp_spj_up_ini + $trh_x->cp_spj_up_ll + $trh_x->cp_spj_gaji_ini +
            $trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_brjs_ini + $trh_x->cp_spj_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Contra Pos</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll + $trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll + $trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($total_cp, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        // lain lain setoran
        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp IN ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp IN ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHOUTLAIN a 
                WHERE a.kd_skpd='$lcskpd'
                ) a ";
        $hasil = $this->db->query($csql);
        $trh12 = $hasil->row();
        $totallain = $trh12->jlain_up_ini + $trh12->jlain_up_ll + $trh12->jlain_gaji_ini +
            $trh12->jlain_gaji_ll + $trh12->jlain_brjs_ini + $trh12->jlain_brjs_ll;

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'=$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ini = $hasil->row('jumlah');
        //          echo  $tox_ini;            
        $tox_ini = (empty($tox_ini) ? 0 : $tox_ini);

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'<$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ll = $hasil->row('jumlah');
        $tox_ll = (empty($tox_ll) ? 0 : $tox_ll);

        //          echo  '-'.$tox_ini;
        //          echo  '-'.$tox_ll;


        //Dropping Dana

        //if(substr($lcskpd,8,2)=="00"){
        $sqldropout = "SELECT SUM(z.bln_lalu) bln_lalu, SUM(z.bln_ini) bln_ini, SUM(z.sd_bln_ini) sd_bln_ini from(
                    select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd_sumber='$lcskpd'
                    UNION ALL
                    select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd_sumber='$lcskpd'
                    )z";

        $hasil = $this->db->query($sqldropout);
        $trhdropout = $hasil->row();
        $totaldropout = $trhdropout->bln_lalu + $trhdropout->bln_ini + $trhdropout->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarout = "SELECT 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
                    from tr_panjar 
                    WHERE kd_skpd='$lcskpd' and jns='1'";
        $hasil = $this->db->query($sqlpanjarout);
        $trhpanjarout = $hasil->row();
        $totalpanjarout = $trhpanjarout->jar_bln_lalu + $trhpanjarout->jar_bln_ini + $trhpanjarout->jar_sd_bln_ini;


        // //Penyesuaian            
        //          $sqlpenyesuian="
        //          SELECT 
        //          SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
        //          from trhtransout a 
        //          left join 
        //          (
        //          select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
        //          left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
        //          group by a.kd_skpd,a.no_sp2d
        //          ) b on left(b.kd_skpd,17)=left(a.kd_skpd,17) and a.no_sp2d=b.no_sp2d
        //          where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
        //          and b.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

        //          "; 
        //          $hasil_psy = $this->db->query($sqlpenyesuian);
        //          $trhasil_psy = $hasil_psy->row();
        //          $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;


        $jmsetgaji_ll =  $trh7->spj_gaji_ll + $trh8->jppn_gaji_ll + $trh9->jpph21_gaji_ll + $trh16->ppnpn_gaji_ll +
            $trh10->jpph22_gaji_ll + $trh11->jpph23_gaji_ll + $trh12->jlain_gaji_ll + $trh_x->cp_spj_gaji_ll +
            $trh73->gj_iwp_lalu + $trh74->gj_tap_lalu + $trh75->gj_pph4_lalu + $trhxx->gj_hkpg_lalu + $trhxy->gj_lain_lalu;

        $jmsetgaji_ini = $trh7->spj_gaji_ini + $trh8->jppn_gaji_ini + $trh9->jpph21_gaji_ini + $trh16->ppnpn_gaji_ini +
            $trh10->jpph22_gaji_ini + $trh11->jpph23_gaji_ini + $trh12->jlain_gaji_ini + $trh_x->cp_spj_gaji_ini +
            $trh73->gj_iwp_ini + $trh74->gj_tap_ini + $trh75->gj_pph4_ini + $trhxx->gj_hkpg_ini + $trhxy->gj_lain_ini;

        $jmsetgaji_sd = $jmsetgaji_ll + $jmsetgaji_ini;


        $jmsetbrjs_ll =  $trh7->spj_brjs_ll + $trh8->jppn_brjs_ll + $trh9->jpph21_brjs_ll + $trh16->ppnpn_brjs_ll +
            $trh10->jpph22_brjs_ll + $trh11->jpph23_brjs_ll + $trh12->jlain_brjs_ll + $trh_x->cp_spj_brjs_ll +
            $trh73->ls_iwp_lalu + $trh74->ls_tap_lalu + $trh75->ls_pph4_lalu + $trhxx->ls_hkpg_lalu + $trhxy->ls_lain_lalu;

        $jmsetbrjs_ini =  $trh7->spj_brjs_ini + $trh8->jppn_brjs_ini + $trh9->jpph21_brjs_ini + $trh16->ppnpn_brjs_ini +
            $trh10->jpph22_brjs_ini + $trh11->jpph23_brjs_ini + $trh12->jlain_brjs_ini + $trh_x->cp_spj_brjs_ini +
            $trh73->ls_iwp_ini + $trh74->ls_tap_ini + $trh75->ls_pph4_ini + $trhxx->ls_hkpg_ini + $trhxy->ls_lain_ini;

        $jmsetbrjs_sd = $jmsetbrjs_ll + $jmsetbrjs_ini;
        /* 
        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll +
                $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll; */

        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll + $trh16->ppnpn_up_ll +
            $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll + $tox_ll + $trh_x->cp_spj_up_ll +
            $trh73->up_iwp_lalu + $trh74->up_tap_lalu + $trh75->up_pph4_lalu + $trhxx->up_hkpg_lalu + $trhxy->up_lain_lalu + $trhpanjarout->jar_bln_lalu;

        $jmsetup_ini =  $trh7->spj_up_ini + $trh8->jppn_up_ini + $trh9->jpph21_up_ini + $trh16->ppnpn_up_ini +
            $trh10->jpph22_up_ini + $trh11->jpph23_up_ini + $trh12->jlain_up_ini + $tox_ini + $trh_x->cp_spj_up_ini +
            $trh73->up_iwp_ini + $trh74->up_tap_ini + $trh75->up_pph4_ini + $trhxx->up_hkpg_ini + $trhxy->up_lain_ini +
            $trhpanjarout->jar_bln_ini;

        $jmsetup_sd = $jmsetup_ll + $jmsetup_ini;

        // <tr>
        //             <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //             <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_lalu_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //         </tr>

        $cRet .= "
                   
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll + $trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll + $trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll + $trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
    
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>

        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        ";

        $cRet .= "
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Pengeluaran :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd + $jmsetbrjs_sd + $jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> 
                    
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Saldo Kas</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll - $jmsetgaji_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini - $jmsetgaji_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd - $jmsetgaji_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll - $jmsetbrjs_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini - $jmsetbrjs_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd - $jmsetbrjs_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll - $jmsetup_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini - $jmsetup_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd - $jmsetup_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd - $jmsetgaji_sd - $jmsetbrjs_sd - $jmsetup_sd, "2", ",", ".") . "</td>
       <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
       </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' colspan='2' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        </table>";
        if ($jenis == '1') {


            $cRet .= '<TABLE width="100%" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        } else if ($jenis == '2') {

            $cRet .= '<TABLE width="100%" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        }

        $data['prev'] = $cRet;
        if ($ctk == 0) {
            echo "<title>  SPJ $bulan</title>";
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, 'L', 0, '', $atas, $bawah, $kiri, $kanan);
        }
    }


    function spj_melawi($lcskpd = '', $nbulan = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $ctk = '', $atas = '', $bawah = '', $kiri = '', $kanan = '', $jenis = '', $jns_bp, $jns_ang = '')
    {
        $ttd1 = str_replace('123456789', ' ', $ttd1);
        $ttd2 = str_replace('123456789', ' ', $ttd2);
        $lcskpdd = substr($lcskpd, 0, 17) . ".0000";


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where left(kd_skpd,17)=left('$lcskpd',17) and (kode='PA' or kode='KPA') and nip='$ttd2'";
        $lcskpdd = $lcskpd;


        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama2 = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        if ($jns_bp == "bk") {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpdd' and kode='BK' and nip='$ttd1'";
        } else {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and kode='BPP' and nip='$ttd1'";
            $lcskpdd = $lcskpdd;
        }
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $sqlanggaran1 = "SELECT jns_ang as anggaran, (case when jns_ang='M' then 'Penetapan'
                when jns_ang='P1' then 'Penyempurnaan I'
                when jns_ang='P2' then 'Penyempurnaan II'
                when jns_ang='P3' then 'Penyempurnaan III'
                when jns_ang='U1' then 'Ubah I' 
                else 'Ubah II' end) as nm_ang from trhrka where kd_skpd='$lcskpd' AND tgl_dpa in (SELECT MAX(tgl_dpa) from trhrka where kd_skpd=trhrka.kd_skpd AND status='1')";
        $sqlanggaran = $this->db->query($sqlanggaran1);
        foreach ($sqlanggaran->result() as $rowttd) {
            $anggaran = $rowttd->anggaran;
        }

        $tanda_ang = 2;
        $thn_ang       = $this->session->userdata('pcThang');

        $skpd = $lcskpd;
        $nama =  $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $bulan = $this->tukd_model->getBulan($nbulan);
        $prv = $this->db->query("SELECT top 1 provinsi,daerah from sclient ");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;
        if ($jenis == '1') {
            $judul = 'SPJ FUNGSIONAL';
        } else if ($jenis == '2') {
            $judul = 'SPJ ADMINISTRATIF';
        } else {
            $judul = 'SPJ BELANJA';
        }

        if (substr($lcskpd, 18, 4) == '0000') {
            $namaskpd = strtoupper("SKPD $nama");
        } else {
            $namaskpd = strtoupper("$nama");
        }
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/logo_melawi.png\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH KABUPATEN MELAWI</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>$namaskpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN " . date("Y") . " </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        </table>";

        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>";
        $cRet .= "
            
            <tr>
                <td align='center' style='font-size:14px;' colspan='2'>
                 <b> LAPORAN PERTANGGUNGJAWABAN BENDAHARA PENGELUARAN <BR></b>
                 <b>(" . $judul . ")<BR></b>
                 <b>Bulan: $bulan</b>
                </td>
            </tr>
           
            </table><br>";
        $ceksx = substr($skpd, 18, 4);

        // if($ceksx=='0000'){
        //  $cRet .="

        //  <tr>
        //     <td align='left' style='font-size:12px;' width='25%'>
        //       SKPD
        //     </td> 
        //     <td width='75%' style='font-size:12px;'>:$skpd - $nama
        //     </td>         
        // </tr>
        // <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Pengguna Anggaran
        //              </td> 
        //              <td style='font-size:12px;'>:$nama2
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Bendahara Pengeluaran
        //              </td> 
        //              <td style='font-size:12px;'>:$nama1
        //              </td>         
        //          </tr>";
        // }else{
        //  $cRet .="
        // <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Kuasa Pengguna Anggaran                  
        //              </td> 
        //              <td style='font-size:12px;'>:$nama2
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //               Bendahara Pengeluaran Pembantu
        //              </td> 
        //              <td style='font-size:12px;'>:$nama1
        //              </td>         
        //          </tr>";
        // }

        // $cRet .="
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Tahun Anggaran
        //              </td> 
        //              <td style='font-size:12px;'>:$thn_ang
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;'>
        //                Bulan
        //              </td> 
        //              <td style='font-size:12px;'>:$bulan
        //              </td>         
        //          </tr>
        //          <tr>
        //              <td align='left' style='font-size:12px;' colspan='2'>
        //               &nbsp;
        //              </td> 
        //          </tr>

        //          </table>
        $cRet .= " <table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <thead>
            <tr>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Kode<br>Rekening</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Uraian</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah<br>Anggaran</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Gaji</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Barang & Jasa</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ UP/GU/TU</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah SPJ<br>(LS+UP/GU/TU)<br>s.d Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Contra Post</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Sisa Pagu<br>Anggaran</b></td>
            </tr>
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
            </tr>                 
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>1</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>2</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>3</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>4</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>5</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>6</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>7</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>8</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>9</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>10</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>11</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>12</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>13</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>14</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>15</td>
            </tr> 
             </thead>
            <tr>
                <td align='center' style='font-size:12px'>&nbsp;</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
            </tr>";

        $att = "spj_skpd_melawi '$lcskpd','$nbulan','$jns_ang'";
        $hasil = $this->db->query($att);
        foreach ($hasil->result() as $trh1) {
            $bre                =   $trh1->kd_rek;
            $kode               =   $trh1->kode;
            $wok                =   $trh1->uraian;
            $nilai              =   $trh1->anggaran;
            $real_up_ini        =   $trh1->up_ini;
            $real_up_ll         =   $trh1->up_lalu;
            $real_gaji_ini      =   $trh1->gaji_ini;
            $real_gaji_ll       =   $trh1->gaji_lalu;
            $real_brg_js_ini    =   $trh1->brg_ini;
            $real_brg_js_ll     =   $trh1->brg_lalu;
            $cp     =   $trh1->cp;
            $total  = $real_gaji_ll + $real_gaji_ini + $real_brg_js_ll + $real_brg_js_ini + $real_up_ll + $real_up_ini;
            $sisa   = $nilai - $real_gaji_ll - $real_gaji_ini - $real_brg_js_ll - $real_brg_js_ini - $real_up_ll - $real_up_ini + $cp;
            $a = strlen($bre);
            if ($a == 7) {
                $cRet .= "
                   <tr>
                        <td   valign='top' width='5%' align='left' style='font-size:10px' ><b>" . $bre . "</b></td>
                        <td   valign='top' align='left' width='28%' style='font-size:10px'><b>" . $wok . "</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($nilai, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($cp, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td   valign='top' align='right' style='font-size:10px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
            } else if ($a == 12 || $a == 15) {
                $cRet .= "
                   <tr>
                        <td valign='top' width='8%' align='left' style='font-size:10px' ><b>" . $bre . "</b></td>
                        <td valign='top' align='left' width='25%' style='font-size:10px'><b>" . $wok . "</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($nilai, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($cp, "2", ",", ".") . "</b>&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
            } else {
                $cRet .= "
                        <tr>
                        <td valign='top' width='8%' align='left' style='font-size:10px' >" . $kode . "</td>
                        <td valign='top' align='left' width='25%' style='font-size:10px'>" . $wok . "</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($nilai, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ll, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "&nbsp;</td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($total, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($cp, "2", ",", ".") . "&nbsp;</b></td>
                        <td valign='top' align='right' style='font-size:10px'>" . number_format($sisa, "2", ",", ".") . "&nbsp;</td>
                    </tr>";
            }
        } /*end foreach*/
        $cRet .= "

        <tr>
            <td valign='top' align='center' style='font-size:10px' >&ensp;</td>
            <td align='left' style='font-size:10px' colspan='2'>Penerimaan :</td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td align='center' style='font-size:10px'></td>
            <td valign='top' align='center' style='font-size:10px' colspan='2'>&nbsp;</td>
        </tr>";

        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp ='4' AND a.status='1') AS sp2d_gj_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp ='4'  AND a.status='1') AS sp2d_gj_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp in ('5','6')  AND a.status='1') AS sp2d_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp in ('5','6') AND a.status='1') AS sp2d_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();
        $totalsp2d = $trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini + $trh1->sp2d_brjs_ll +
            $trh1->sp2d_brjs_ini + $trh1->sp2d_up_ll + $trh1->sp2d_up_ini;

        $cobacoba = $trh1->sp2d_gj_ll;



        $cRet .= "<tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2' >&ensp;&ensp;- SP2D</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll + $trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll + $trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalsp2d, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr> ";

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Potongan Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $totalppn = $trh2->jppn_up_ini + $trh2->jppn_up_ll + $trh2->jppn_gaji_ini +
            $trh2->jppn_gaji_ll + $trh2->jppn_brjs_ini + $trh2->jppn_brjs_ll;


        $cRet .= " 
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll + $trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll + $trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll + $trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105010001'; // pph 21 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $totalpph21 = $trh3->jpph21_up_ini + $trh3->jpph21_up_ll + $trh3->jpph21_gaji_ini +
            $trh3->jpph21_gaji_ll + $trh3->jpph21_brjs_ini + $trh3->jpph21_brjs_ll;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll + $trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll + $trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll + $trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();
        $totalpph22 = $trh4->jpph22_up_ini + $trh4->jpph22_up_ll + $trh4->jpph22_gaji_ini +
            $trh4->jpph22_gaji_ll + $trh4->jpph22_brjs_ini + $trh4->jpph22_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll + $trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll + $trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll + $trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh5 = $hasil->row();
        $totalpph23 = $trh5->jpph23_up_ini + $trh5->jpph23_up_ll + $trh5->jpph23_gaji_ini +
            $trh5->jpph23_gaji_ll + $trh5->jpph23_brjs_ini + $trh5->jpph23_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll + $trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll + $trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll + $trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh70 = $hasil->row();
        $totaliwp = $trh70->up_iwp_sdini + $trh70->gj_iwp_sdini + $trh70->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh71 = $hasil->row();
        $totaltap = $trh71->up_tap_sdini + $trh71->gj_tap_sdini + $trh71->ls_tap_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210109010001'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh72 = $hasil->row();
        $totalpph4 = $trh72->up_pph4_sdini + $trh72->gj_pph4_sdini + $trh72->ls_pph4_sdini;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210102010001'; // PPnPn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh15 = $hasil->row();
        $totalppnpn = $trh15->ppnpn_up_ini + $trh15->ppnpn_up_ll + $trh15->ppnpn_gaji_ini +
            $trh15->ppnpn_gaji_ll + $trh15->ppnpn_brjs_ini + $trh15->ppnpn_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll + $trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll + $trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll + $trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        // lain terima

        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHINLAIN a WHERE pengurang_belanja !='1'
                AND a.kd_skpd='$lcskpd'
                union all
                SELECT 
                SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jlain_up_ll, '0' as jlain_up_ini, '0' as jlain_gaji_ll ,'0' as jlain_gaji_ini, '0' as jlain_brjs_ll, '0' as jlain_brjs_ini
                                
                            
                            FROM ms_skpd where kd_skpd='$lcskpd'

                ) a ";

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql);
        $trh6 = $hasil->row();
        $totallain = $trh6->jlain_up_ini + $trh6->jlain_up_ll + $trh6->jlain_gaji_ini +
            $trh6->jlain_gaji_ll + $trh6->jlain_brjs_ini + $trh6->jlain_brjs_ll;


        //tambahan_pajak_tunai

        $sql_pajak_tunai = "SELECT 
        SUM(CASE WHEN MONTH(z.tgl)<'$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_lalu,
        SUM(CASE WHEN MONTH(z.tgl)='$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_ini,
        SUM(CASE WHEN MONTH(z.tgl)<='$nbulan' THEN z.nilai_pot ELSE 0 END) as sd_bln_ini
        from (
        select a.kd_skpd,c.tgl_bukti as tgl,b.kd_rek6 as rek,b.nilai as nilai_pot,b.no_sp2d from trdtransout a 
        LEFT JOIN trhtransout c on c.kd_skpd=a.kd_skpd and a.no_bukti=c.no_bukti
        LEFT JOIN (
        select b.tgl_bukti,a.kd_rek6,a.nilai,b.no_sp2d from trdstrpot a left join trhstrpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti  
        )b on b.no_sp2d=a.no_sp2d
        where right(a.kd_skpd,2)!='00' and c.jns_spp in ('4','5','6') and c.pay='TUNAI' 
        )z 
        where z.no_sp2d <> '' and kd_skpd not in 
        ('1.02.01.01','4.08.05.08','4.08.05.09','4.08.05.10','4.08.03.11','4.08.03.12',
        '4.08.05.07','4.08.03.13','4.08.03.14','4.08.06.06','4.08.06.07','4.08.06.08',
        '4.08.06.09','4.08.04.08','4.08.04.09','4.08.04.10','4.08.04.11','4.08.04.12',
        '4.08.03.10','4.08.02.08','4.08.02.09','4.08.02.10','4.08.02.11','4.08.03.15',
        '4.08.03.16','4.08.02.12','4.08.01.06','4.08.01.08','4.08.01.09','4.08.01.07')
        and kd_skpd='$lcskpd'";

        $sql_pajak_tunai_hasil = $this->db->query($sql_pajak_tunai);
        $trh_pajakTN = $sql_pajak_tunai_hasil->row();
        $tottrh_pajakTN = $trh_pajakTN->bln_lalu + $trh_pajakTN->bln_ini + $trh_pajakTN->sd_bln_ini;

        //Dropping Dana

        $sqldropin = "
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL            
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd='$lcskpd'           
        UNION ALL
        SELECT 
        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
        from tr_setorsimpanan WHERE kd_skpd='$lcskpd' and jenis='3'
        )x
        ";
        /*
        if(substr($lcskpd,8,2)=="00"){
           $sqldropin="
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
                    from tr_setorsimpanan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
                    from tr_ambilsimpanan
                    WHERE kd_skpd='$lcskpd')x
        "; 
        }else{
           $sqldropin="
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL            
        select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd='$lcskpd')x
        "; 
        }
        
        */


        $hasil = $this->db->query($sqldropin);
        $trhdropin = $hasil->row();
        $totaldropin = $trhdropin->bln_lalu + $trhdropin->bln_ini + $trhdropin->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarin = "
        SELECT SUM(x.jar_bln_lalu) jar_bln_lalu, SUM(x.jar_bln_ini) jar_bln_ini, SUM(x.jar_sd_bln_ini) jar_sd_bln_ini FROM(
        SELECT 
        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
        from 
        -- tr_jpanjar WHERE kd_skpd='$lcskpd' and jns='1'
        tr_jpanjar where jns=1 and kd_skpd='$lcskpd'
        )x";
        $hasil = $this->db->query($sqlpanjarin);
        $trhpanjarin = $hasil->row();
        $totalpanjarin = $trhpanjarin->jar_bln_lalu + $trhpanjarin->jar_bln_ini + $trhpanjarin->jar_sd_bln_ini;

        // //Penyesuaian            
        //          $sqlpenyesuian="
        //          SELECT 
        //          SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
        //          from trhtransout a 
        //          left join 
        //          (
        //          select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
        //          left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
        //          group by a.kd_skpd,a.no_sp2d
        //          ) b on left(b.kd_skpd,7)=left(a.kd_skpd,7) and a.no_sp2d=b.no_sp2d
        //          where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
        //          and a.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

        //          "; 
        //          $hasil_psy = $this->db->query($sqlpenyesuian);
        //          $trhasil_psy = $hasil_psy->row();
        //          $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;

        //-------- TOTAL PENERIMAAN
        $jmtrmgaji_ll =  $trh1->sp2d_gj_ll + $trh2->jppn_gaji_ll + $trh3->jpph21_gaji_ll +
            $trh4->jpph22_gaji_ll + $trh5->jpph23_gaji_ll + $trh6->jlain_gaji_ll + $trh15->ppnpn_gaji_ll +
            $trh70->gj_iwp_lalu + $trh71->gj_tap_lalu + $trh72->gj_pph4_lalu;

        $jmtrmgaji_ini =  $trh1->sp2d_gj_ini + $trh2->jppn_gaji_ini + $trh3->jpph21_gaji_ini +
            $trh4->jpph22_gaji_ini + $trh5->jpph23_gaji_ini + $trh6->jlain_gaji_ini + $trh15->ppnpn_gaji_ini +
            $trh70->gj_iwp_ini + $trh71->gj_tap_ini + $trh72->gj_pph4_ini;

        $jmtrmgaji_sd = $jmtrmgaji_ll + $jmtrmgaji_ini;


        $jmtrmbrjs_ll =  $trh1->sp2d_brjs_ll + $trh2->jppn_brjs_ll + $trh3->jpph21_brjs_ll +
            $trh4->jpph22_brjs_ll + $trh5->jpph23_brjs_ll + $trh6->jlain_brjs_ll + $trh15->ppnpn_brjs_ll +
            $trh70->ls_iwp_lalu + $trh71->ls_tap_lalu + $trh72->ls_pph4_lalu;

        $jmtrmbrjs_ini =  $trh1->sp2d_brjs_ini + $trh2->jppn_brjs_ini + $trh3->jpph21_brjs_ini +
            $trh4->jpph22_brjs_ini + $trh5->jpph23_brjs_ini + $trh6->jlain_brjs_ini + $trh15->ppnpn_brjs_ini +
            $trh70->ls_iwp_ini + $trh71->ls_tap_ini + $trh72->ls_pph4_ini;

        $jmtrmbrjs_sd = $jmtrmbrjs_ll + $jmtrmbrjs_ini;

        $jmtrmup_ll =  $trh1->sp2d_up_ll + $trh2->jppn_up_ll + $trh3->jpph21_up_ll +
            $trh4->jpph22_up_ll + $trh5->jpph23_up_ll + $trh6->jlain_up_ll + $tox + $trh15->ppnpn_up_ll +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_lalu + $trhdropin->bln_lalu + $trhpanjarin->jar_bln_lalu + $trh_pajakTN->bln_lalu;

        $jmtrmup_ini =  $trh1->sp2d_up_ini + $trh2->jppn_up_ini + $trh3->jpph21_up_ini +
            $trh4->jpph22_up_ini + $trh5->jpph23_up_ini + $trh6->jlain_up_ini + $trh15->ppnpn_up_ini +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_ini + $trhdropin->bln_ini + $trhpanjarin->jar_bln_ini + $trh_pajakTN->bln_ini;

        $jmtrmup_sd = $jmtrmup_ll + $jmtrmup_ini;


        // <tr>
        //                <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_lalu_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //                <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //            </tr>

        $cRet .= "
                   
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll + $trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll + $trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll + $trh6->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        
       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>                       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Penerimaan :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr> 
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Pengeluaran :</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $csql = "SELECT sum(gaji_lalu) as spj_gaji_ll, sum(gaji_ini) as spj_gaji_ini, sum(brg_lalu) as spj_brjs_ll, sum(brg_ini) as spj_brjs_ini, sum(up_lalu) as spj_up_ll, sum(up_ini) as spj_up_ini from

        (
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' 
            and jns_spp in (1,2,3) and pay not in ('PANJAR') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' 
        --     and jns_spp in (1,2,3) 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai*-1,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)='$nbulan' and b.pengurang_belanja=1 
        union all

        select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (4) 
        union all

        -- select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (4,5)  and b.kd_satdik=''
        -- union all

        select a.kd_skpd, isnull(a.rupiah*-1,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (1) and b.pot_khusus=1 
        union all



        select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in ('5','6') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in ('6','7') 
        -- union all

        --  select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus<>0 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus=0
        union all

        select a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus=2
        union all


        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (1,2,3) and pay not in ('PANJAR') 
        union all

        -- /select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (1,2,3) 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai*-1,0) as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.TGL_BUKTI)<'$nbulan' and b.pengurang_belanja=1 
        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (4) 
        union all

        --  select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (4,5)  and b.kd_satdik=''
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.rupiah*-1,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (1) and b.pot_khusus=1
        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in ('5','6') 
        union all

        -- select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in ('6','7') 
        -- union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (2) and b.pot_khusus=2

        union all

        select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (2) and b.pot_khusus=0



        
        ) a 
        WHERE a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh7 = $hasil->row();
        $totalspj = $trh7->spj_gaji_ll + $trh7->spj_gaji_ini + $trh7->spj_brjs_ll +
            $trh7->spj_brjs_ini + $trh7->spj_up_ll + $trh7->spj_up_ini;

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- SPJ(LS + UP/GU/TU)</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini + $trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini + $trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini + $trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalspj, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- Penyetoran Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh8 = $hasil->row();
        $totalppn = $trh8->jppn_up_ini + $trh8->jppn_up_ll + $trh8->jppn_gaji_ini +
            $trh8->jppn_gaji_ll + $trh8->jppn_brjs_ini + $trh8->jppn_brjs_ll;

        $cRet .= "
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll + $trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll + $trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll + $trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210105010001'; // pph 21 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh9 = $hasil->row();
        $totalpph21 = $trh9->jpph21_up_ini + $trh9->jpph21_up_ll + $trh9->jpph21_gaji_ini +
            $trh9->jpph21_gaji_ll + $trh9->jpph21_brjs_ini + $trh9->jpph21_brjs_ll;


        $cRet .= "
         <tr> <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll + $trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll + $trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll + $trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh10 = $hasil->row();
        $totalpph22 = $trh10->jpph22_up_ini + $trh10->jpph22_up_ll + $trh10->jpph22_gaji_ini +
            $trh10->jpph22_gaji_ll + $trh10->jpph22_brjs_ini + $trh10->jpph22_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll + $trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll + $trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll + $trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh11 = $hasil->row();
        $totalpph23 = $trh11->jpph23_up_ini + $trh11->jpph23_up_ll + $trh11->jpph23_gaji_ini +
            $trh11->jpph23_gaji_ll + $trh11->jpph23_brjs_ini + $trh11->jpph23_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll + $trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll + $trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll + $trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh73 = $hasil->row();
        $totaliwp_setor = $trh73->up_iwp_sdini + $trh73->gj_iwp_sdini + $trh73->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh74 = $hasil->row();
        $totaltap_setor = $trh74->up_tap_sdini + $trh74->gj_tap_sdini + $trh74->ls_tap_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210109010001'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp in ('5','6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh75 = $hasil->row();
        $totalpph4_setor = $trh75->up_pph4_sdini + $trh75->gj_pph4_sdini + $trh75->ls_pph4_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4_setor, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";






        $lcrek = '210102010001'; // PPnpn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp in ('5','6')) AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh16 = $hasil->row();
        $totalppnpn = $trh16->ppnpn_up_ini + $trh16->ppnpn_up_ll + $trh16->ppnpn_gaji_ini +
            $trh16->ppnpn_gaji_ll + $trh16->ppnpn_brjs_ini + $trh16->ppnpn_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll + $trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll + $trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll + $trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";

        // // HKPG
        //          $csql = "SELECT 
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
        //  SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
        //  FROM trdkasin_pkd a 
        //  INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
        //  WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        //          $hasil = $this->db->query($csql);
        //          $trhxx = $hasil->row();
        //          $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;


        //          $cRet .="
        //           <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //              <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- HKPG</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->gj_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->ls_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_lalu,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_ini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($trhxx->up_hkpg_sdini,"2",",",".")."&nbsp;</td>
        //              <td align='right' style='font-size:12px'>".number_format($totalhkpg,"2",",",".")."&nbsp;</td>
        //  <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //          </tr>";

        // Potongan Penghasilan Lainnya
        $csql = "SELECT 
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_sdini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_sdini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_lalu,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_ini,
            SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_sdini
            FROM trdkasin_pkd a 
            INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        $hasil = $this->db->query($csql);
        $trhxy = $hasil->row();
        $totallain = $trhxy->up_lain_sdini + $trhxy->gj_lain_sdini + $trhxy->ls_lain_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Penghasilan Lainnya</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";
        // HKPG
        $csql = "SELECT 
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
            FROM trdkasin_pkd a 
            INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE a.kd_skpd = '$lcskpd' AND jns_trans='5' AND LEFT(kd_rek6,1)<>4";

        $hasil = $this->db->query($csql);
        $trhxx = $hasil->row();
        $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;


        $cRet .= "
         <tr><td align=\"left\" style=\"font-size:12px;border-top:hidden;\">&ensp;&ensp;</td>
            <td align=\"left\" style=\"font-size:12px\" colspan=\"2\">&ensp;&ensp;- HKPG</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->gj_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->ls_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($trhxx->up_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
            <td align=\"right\" style=\"font-size:12px\">" . number_format($totalhkpg, "2", ",", ".") . "&nbsp;</td>
            <td align=\"left\" style=\"font-size:12px;border-top:hidden;\" colspan=\"2\"'>&ensp;&ensp;</td>

        </tr>";

        // CONTRA POS
        $csql = "SELECT 
        SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ini,
        SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ll,
        SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ini,
        SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ll,
        SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ini,
        SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ll
        from (select rupiah as nilai,jns_trans,pot_khusus,jns_cp,d.tgl_sts ,d.kd_skpd from 
        trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd where d.kd_skpd ='$lcskpd' AND 
        ((jns_trans='5' AND pot_khusus in('0')) OR jns_trans='1')) z";

        $hasil = $this->db->query($csql);
        $trh_x = $hasil->row();
        $total_cp = $trh_x->cp_spj_up_ini + $trh_x->cp_spj_up_ll + $trh_x->cp_spj_gaji_ini +
            $trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_brjs_ini + $trh_x->cp_spj_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Contra Pos</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll + $trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll + $trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($total_cp, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>";


        // lain lain setoran
        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp IN ('5','6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp IN ('5','6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210106010001','210105020001','210105030001','210108010001','210107010001','210109010001','210102010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban in ('5','6') AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHOUTLAIN a 
                WHERE a.kd_skpd='$lcskpd'
                ) a ";
        $hasil = $this->db->query($csql);
        $trh12 = $hasil->row();
        $totallain = $trh12->jlain_up_ini + $trh12->jlain_up_ll + $trh12->jlain_gaji_ini +
            $trh12->jlain_gaji_ll + $trh12->jlain_brjs_ini + $trh12->jlain_brjs_ll;

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'=$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ini = $hasil->row('jumlah');
        //          echo  $tox_ini;            
        $tox_ini = (empty($tox_ini) ? 0 : $tox_ini);

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'<$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ll = $hasil->row('jumlah');
        $tox_ll = (empty($tox_ll) ? 0 : $tox_ll);

        //          echo  '-'.$tox_ini;
        //          echo  '-'.$tox_ll;


        //Dropping Dana

        //if(substr($lcskpd,8,2)=="00"){
        $sqldropout = "SELECT SUM(z.bln_lalu) bln_lalu, SUM(z.bln_ini) bln_ini, SUM(z.sd_bln_ini) sd_bln_ini from(
                    select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd_sumber='$lcskpd'
                    UNION ALL
                    select 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd_sumber='$lcskpd'
                    )z";

        $hasil = $this->db->query($sqldropout);
        $trhdropout = $hasil->row();
        $totaldropout = $trhdropout->bln_lalu + $trhdropout->bln_ini + $trhdropout->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarout = "SELECT 
                    SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
                    SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
                    SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
                    from tr_panjar 
                    WHERE kd_skpd='$lcskpd' and jns='1'";
        $hasil = $this->db->query($sqlpanjarout);
        $trhpanjarout = $hasil->row();
        $totalpanjarout = $trhpanjarout->jar_bln_lalu + $trhpanjarout->jar_bln_ini + $trhpanjarout->jar_sd_bln_ini;


        // //Penyesuaian            
        //          $sqlpenyesuian="
        //          SELECT 
        //          SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
        //          SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
        //          from trhtransout a 
        //          left join 
        //          (
        //          select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
        //          left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
        //          group by a.kd_skpd,a.no_sp2d
        //          ) b on left(b.kd_skpd,17)=left(a.kd_skpd,17) and a.no_sp2d=b.no_sp2d
        //          where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
        //          and b.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

        //          "; 
        //          $hasil_psy = $this->db->query($sqlpenyesuian);
        //          $trhasil_psy = $hasil_psy->row();
        //          $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;


        $jmsetgaji_ll =  $trh7->spj_gaji_ll + $trh8->jppn_gaji_ll + $trh9->jpph21_gaji_ll + $trh16->ppnpn_gaji_ll +
            $trh10->jpph22_gaji_ll + $trh11->jpph23_gaji_ll + $trh12->jlain_gaji_ll + $trh_x->cp_spj_gaji_ll +
            $trh73->gj_iwp_lalu + $trh74->gj_tap_lalu + $trh75->gj_pph4_lalu + $trhxx->gj_hkpg_lalu + $trhxy->gj_lain_lalu;

        $jmsetgaji_ini = $trh7->spj_gaji_ini + $trh8->jppn_gaji_ini + $trh9->jpph21_gaji_ini + $trh16->ppnpn_gaji_ini +
            $trh10->jpph22_gaji_ini + $trh11->jpph23_gaji_ini + $trh12->jlain_gaji_ini + $trh_x->cp_spj_gaji_ini +
            $trh73->gj_iwp_ini + $trh74->gj_tap_ini + $trh75->gj_pph4_ini + $trhxx->gj_hkpg_ini + $trhxy->gj_lain_ini;

        $jmsetgaji_sd = $jmsetgaji_ll + $jmsetgaji_ini;


        $jmsetbrjs_ll =  $trh7->spj_brjs_ll + $trh8->jppn_brjs_ll + $trh9->jpph21_brjs_ll + $trh16->ppnpn_brjs_ll +
            $trh10->jpph22_brjs_ll + $trh11->jpph23_brjs_ll + $trh12->jlain_brjs_ll + $trh_x->cp_spj_brjs_ll +
            $trh73->ls_iwp_lalu + $trh74->ls_tap_lalu + $trh75->ls_pph4_lalu + $trhxx->ls_hkpg_lalu + $trhxy->ls_lain_lalu;

        $jmsetbrjs_ini =  $trh7->spj_brjs_ini + $trh8->jppn_brjs_ini + $trh9->jpph21_brjs_ini + $trh16->ppnpn_brjs_ini +
            $trh10->jpph22_brjs_ini + $trh11->jpph23_brjs_ini + $trh12->jlain_brjs_ini + $trh_x->cp_spj_brjs_ini +
            $trh73->ls_iwp_ini + $trh74->ls_tap_ini + $trh75->ls_pph4_ini + $trhxx->ls_hkpg_ini + $trhxy->ls_lain_ini;

        $jmsetbrjs_sd = $jmsetbrjs_ll + $jmsetbrjs_ini;
        /* 
        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll +
                $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll; */

        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll + $trh16->ppnpn_up_ll +
            $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll + $tox_ll + $trh_x->cp_spj_up_ll +
            $trh73->up_iwp_lalu + $trh74->up_tap_lalu + $trh75->up_pph4_lalu + $trhxx->up_hkpg_lalu + $trhxy->up_lain_lalu + $trhpanjarout->jar_bln_lalu;

        $jmsetup_ini =  $trh7->spj_up_ini + $trh8->jppn_up_ini + $trh9->jpph21_up_ini + $trh16->ppnpn_up_ini +
            $trh10->jpph22_up_ini + $trh11->jpph23_up_ini + $trh12->jlain_up_ini + $tox_ini + $trh_x->cp_spj_up_ini +
            $trh73->up_iwp_ini + $trh74->up_tap_ini + $trh75->up_pph4_ini + $trhxx->up_hkpg_ini + $trhxy->up_lain_ini +
            $trhpanjarout->jar_bln_ini;

        $jmsetup_sd = $jmsetup_ll + $jmsetup_ini;

        // <tr>
        //             <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //             <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format(0,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_lalu_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='right' style='font-size:12px'>".number_format($trhasil_psy->sd_bln_ini_psy,"2",",",".")."&nbsp;</td>
        //             <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        //         </tr>

        $cRet .= "
                   
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll + $trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll + $trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll + $trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        
    
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>

        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        
        ";

        $cRet .= "
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Pengeluaran :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ll, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ini, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd + $jmsetbrjs_sd + $jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr> 
                    
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Saldo Kas</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll - $jmsetgaji_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini - $jmsetgaji_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd - $jmsetgaji_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll - $jmsetbrjs_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini - $jmsetbrjs_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd - $jmsetbrjs_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll - $jmsetup_ll, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini - $jmsetup_ini, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd - $jmsetup_sd, "2", ",", ".") . "</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd - $jmsetgaji_sd - $jmsetbrjs_sd - $jmsetup_sd, "2", ",", ".") . "</td>

       <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
       </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;' colspan='2'>&ensp;&ensp;</td>
        </tr>
        </table>";
        if ($jenis == '1') {


            $cRet .= '<TABLE width="100%" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        } else if ($jenis == '2') {

            $cRet .= '<TABLE width="100%" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        }

        $data['prev'] = $cRet;
        if ($ctk == 0) {
            echo "<title>  SPJ $bulan</title>";
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, 'L', 0, '', $atas, $bawah, $kiri, $kanan);
        }
    }

    function spj_lama_beud($lcskpd = '', $nbulan = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $ctk = '', $atas = '', $bawah = '', $kiri = '', $kanan = '', $jenis = '', $jns_bp, $jns_ang = '')
    {
        $ttd1 = str_replace('123456789', ' ', $ttd1);
        $ttd2 = str_replace('123456789', ' ', $ttd2);
        $lcskpdd = substr($lcskpd, 0, 17) . ".0000";


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where left(kd_skpd,17)=left('$lcskpd',17) and (kode='PA' or kode='KPA') and nip='$ttd2'";
        $lcskpdd = $lcskpd;


        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama2 = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        if ($jns_bp == "bk") {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpdd' and nip='$ttd1'";
        } else {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$lcskpd' and nip='$ttd1'";
            $lcskpdd = $lcskpdd;
        }
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $sqlanggaran1 = "SELECT jns_ang as anggaran, (case when jns_ang='M' then 'Penetapan'
                when jns_ang='P1' then 'Penyempurnaan I'
                when jns_ang='P2' then 'Penyempurnaan II'
                when jns_ang='P3' then 'Penyempurnaan III'
                when jns_ang='U1' then 'Ubah I' 
                else 'Ubah II' end) as nm_ang from trhrka where kd_skpd='$lcskpd' AND tgl_dpa in (SELECT MAX(tgl_dpa) from trhrka where kd_skpd=trhrka.kd_skpd)";
        $sqlanggaran = $this->db->query($sqlanggaran1);
        foreach ($sqlanggaran->result() as $rowttd) {
            $anggaran = $rowttd->anggaran;
        }

        $tanda_ang = 2;
        $thn_ang       = $this->session->userdata('pcThang');

        $skpd = $lcskpd;
        $nama =  $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $bulan = $this->tukd_model->getBulan($nbulan);
        $prv = $this->db->query("SELECT top 1 provinsi,daerah from sclient ");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;
        if ($jenis == '1') {
            $judul = 'SPJ FUNGSIONAL';
        } else if ($jenis == '2') {
            $judul = 'SPJ ADMINISTRATIF';
        } else {
            $judul = 'SPJ BELANJA';
        }
        $cRet = '';
        $cRet = "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>";
        $cRet .= "
            
            <tr>
                <td align='center' style='font-size:14px;' colspan='2'>
                 <b> $prov<BR></b>
                 <b> SURAT PENGESAHAN PERTANGGUNGJAWABAN BENDAHARA PENGELUARAN<BR></b>
                 <b>(" . $judul . ")<BR></b>&nbsp;
                </td>
            </tr>
            <tr>
                <td align='left' style='font-size:12px;' width='25%'>
                  SKPD
                </td> 
                <td width='75%' style='font-size:12px;'>:$skpd - $nama
                </td>         
            </tr>";
        $ceksx = substr($skpd, 18, 4);

        if ($ceksx == '0000') {
            $cRet .= "
			<tr>
                <td align='left' style='font-size:12px;'>
                  Pengguna Anggaran
                </td> 
                <td style='font-size:12px;'>:$nama2
                </td>         
            </tr>
            <tr>
                <td align='left' style='font-size:12px;'>
                  Bendahara Pengeluaran
                </td> 
                <td style='font-size:12px;'>:$nama1
                </td>         
            </tr>";
        } else {
            $cRet .= "
			<tr>
                <td align='left' style='font-size:12px;'>
                  Kuasa Pengguna Anggaran				  
                </td> 
                <td style='font-size:12px;'>:$nama2
                </td>         
            </tr>
            <tr>
                <td align='left' style='font-size:12px;'>
                 Bendahara Pengeluaran Pembantu
                </td> 
                <td style='font-size:12px;'>:$nama1
                </td>         
            </tr>";
        }

        $cRet .= "
            <tr>
                <td align='left' style='font-size:12px;'>
                  Tahun Anggaran
                </td> 
                <td style='font-size:12px;'>:$thn_ang
                </td>         
            </tr>
            <tr>
                <td align='left' style='font-size:12px;'>
                  Bulan
                </td> 
                <td style='font-size:12px;'>:$bulan
                </td>         
            </tr>
            <tr>
                <td align='left' style='font-size:12px;' colspan='2'>
                 &nbsp;
                </td> 
            </tr>
            
            </table>
            <table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
            <thead>
            <tr>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Kode<br>Rekening</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Uraian</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah<br>Anggaran</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Gaji</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Barang & Jasa</b></td>
                <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ UP/GU/TU</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah SPJ<br>(LS+UP/GU/TU)<br>s.d Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Sisa Pagu<br>Anggaran</b></td>
            </tr>
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
            </tr>                 
            <tr>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>1</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>2</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>3</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>4</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>5</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>6</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>7</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>8</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>9</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>10</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>11</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>12</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>13</td>
                <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>14</td>
            </tr> 
             </thead>
            <tr>
                <td align='center' style='font-size:12px'>&nbsp;</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
            </tr>";

        $att = "exec spj_skpd '$lcskpd','$nbulan'";
        $hasil = $this->db->query($att);
        foreach ($hasil->result() as $trh1) {
            $bre                =    $trh1->kd_rek;
            $kode                =    $trh1->kode;
            $wok                =    $trh1->uraian;
            $nilai                =    $trh1->anggaran;
            $real_up_ini        =    $trh1->up_ini;
            $real_up_ll            =    $trh1->up_lalu;
            $real_gaji_ini        =    $trh1->gaji_ini;
            $real_gaji_ll        =    $trh1->gaji_lalu;
            $real_brg_js_ini    =    $trh1->brg_ini;
            $real_brg_js_ll        =    $trh1->brg_lalu;
            $total    = $real_gaji_ll + $real_gaji_ini + $real_brg_js_ll + $real_brg_js_ini + $real_up_ll + $real_up_ini;
            $sisa    = $nilai - $real_gaji_ll - $real_gaji_ini - $real_brg_js_ll - $real_brg_js_ini - $real_up_ll - $real_up_ini;
            $a = strlen($bre);
            if ($a == 7) {
                $cRet .= "
			           <tr>
			                <td   valign='top' width='8%' align='left' style='font-size:12px' ><b>" . $bre . "</b></td>
			                <td   valign='top' align='left' width='25%' style='font-size:12px'><b>" . $wok . "</b></td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($nilai, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
			                <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
			            </tr>";
            } else if ($a == 12 || $a == 15) {
                $cRet .= "
			           <tr>
			                <td valign='top' width='8%' align='left' style='font-size:12px' ><b>" . $bre . "</b></td>
			                <td valign='top' align='left' width='25%' style='font-size:12px'><b>" . $wok . "</b></td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($nilai, "2", ",", ".") . "&nbsp;</b></td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</b></td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</b></td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b>&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b>&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b>&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($total, "2", ",", ".") . "</b>&nbsp;</b></td>
			                <td valign='top' align='right' style='font-size:12px'><b>" . number_format($sisa, "2", ",", ".") . "</b>&nbsp;</td>
			            </tr>";
            } else {
                $cRet .= "
							<tr>
			                <td valign='top' width='8%' align='left' style='font-size:12px' >" . $kode . "</td>
			                <td valign='top' align='left' width='25%' style='font-size:12px'>" . $wok . "</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($nilai, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_gaji_ll, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_brg_js_ll, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_up_ll, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_up_ini, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "&nbsp;</td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($total, "2", ",", ".") . "&nbsp;</b></td>
			                <td valign='top' align='right' style='font-size:12px'>" . number_format($sisa, "2", ",", ".") . "&nbsp;</td>
			            </tr>";
            }
        } /*end foreach*/
        $cRet .= "

            <tr>
                <td valign='top' align='center' style='font-size:12px' >&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Penerimaan :</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td valign='top' align='center' style='font-size:12px'>&nbsp;</td>
            </tr>";

        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ini,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ll,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp ='4' AND a.status='1') AS sp2d_gj_ini,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp ='4'  AND a.status='1') AS sp2d_gj_ll,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    MONTH(a.tgl_kas)='$nbulan' AND c.jns_spp ='6'  AND a.status='1') AS sp2d_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                    ON a.no_spp = b.no_spp INNER JOIN trhspp c
                    ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                    MONTH(a.tgl_kas)<'$nbulan' AND c.jns_spp ='6' AND a.status='1') AS sp2d_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();
        $totalsp2d = $trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini + $trh1->sp2d_brjs_ll +
            $trh1->sp2d_brjs_ini + $trh1->sp2d_up_ll + $trh1->sp2d_up_ini;

        $cobacoba = $trh1->sp2d_gj_ll;



        $cRet .= "   
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2' >&ensp;&ensp;- SP2D</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll + $trh1->sp2d_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll + $trh1->sp2d_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalsp2d, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr> ";

        $cRet .= "
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Potongan Pajak</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jppn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jppn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jppn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $totalppn = $trh2->jppn_up_ini + $trh2->jppn_up_ll + $trh2->jppn_gaji_ini +
            $trh2->jppn_gaji_ll + $trh2->jppn_brjs_ini + $trh2->jppn_brjs_ll;


        $cRet .= " 
            <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll + $trh2->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll + $trh2->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll + $trh2->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
			</tr>";

        $lcrek = '210105010001'; // pph 21 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jpph21_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jpph21_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jpph21_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $totalpph21 = $trh3->jpph21_up_ini + $trh3->jpph21_up_ll + $trh3->jpph21_gaji_ini +
            $trh3->jpph21_gaji_ll + $trh3->jpph21_brjs_ini + $trh3->jpph21_brjs_ll;

        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll + $trh3->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll + $trh3->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll + $trh3->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210105020001'; // pph 22 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jpph22_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jpph22_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jpph22_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();
        $totalpph22 = $trh4->jpph22_up_ini + $trh4->jpph22_up_ll + $trh4->jpph22_gaji_ini +
            $trh4->jpph22_gaji_ll + $trh4->jpph22_brjs_ini + $trh4->jpph22_brjs_ll;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll + $trh4->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll + $trh4->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll + $trh4->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210105030001'; // pph 23 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jpph23_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jpph23_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jpph23_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh5 = $hasil->row();
        $totalpph23 = $trh5->jpph23_up_ini + $trh5->jpph23_up_ll + $trh5->jpph23_gaji_ini +
            $trh5->jpph23_gaji_ll + $trh5->jpph23_brjs_ini + $trh5->jpph23_brjs_ll;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll + $trh5->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll + $trh5->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll + $trh5->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS gj_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS ls_iwp_sdini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh70 = $hasil->row();
        $totaliwp = $trh70->up_iwp_sdini + $trh70->gj_iwp_sdini + $trh70->ls_iwp_sdini;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totaliwp, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN a.nilai ELSE 0 END) AS up_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN a.nilai ELSE 0 END) AS gj_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh71 = $hasil->row();
        $totaltap = $trh71->up_tap_sdini + $trh71->gj_tap_sdini + $trh71->ls_tap_sdini;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totaltap, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210601050005'; // pph4
        $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh72 = $hasil->row();
        $totalpph4 = $trh72->up_pph4_sdini + $trh72->gj_pph4_sdini + $trh72->ls_pph4_sdini;

        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph4, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";


        $lcrek = '210102010001'; // PPnPn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh15 = $hasil->row();
        $totalppnpn = $trh15->ppnpn_up_ini + $trh15->ppnpn_up_ll + $trh15->ppnpn_gaji_ini +
            $trh15->ppnpn_gaji_ll + $trh15->ppnpn_brjs_ini + $trh15->ppnpn_brjs_ll;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll + $trh15->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll + $trh15->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll + $trh15->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        // lain terima
        $csql = "SELECT 
					SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
					SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
					SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
					 FROM(
					SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
					FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd='$lcskpd'
					UNION ALL
					SELECT 
					SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN a.jns_beban='6' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN a.jns_beban='6' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
					FROM TRHINLAIN a WHERE pengurang_belanja !='1'
					AND a.kd_skpd='$lcskpd'
					) a ";

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql);
        $trh6 = $hasil->row();
        $totallain = $trh6->jlain_up_ini + $trh6->jlain_up_ll + $trh6->jlain_gaji_ini +
            $trh6->jlain_gaji_ll + $trh6->jlain_brjs_ini + $trh6->jlain_brjs_ll;


        //tambahan_pajak_tunai

        $sql_pajak_tunai = "SELECT 
SUM(CASE WHEN MONTH(z.tgl)<'$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_lalu,
SUM(CASE WHEN MONTH(z.tgl)='$nbulan' THEN z.nilai_pot ELSE 0 END) as bln_ini,
SUM(CASE WHEN MONTH(z.tgl)<='$nbulan' THEN z.nilai_pot ELSE 0 END) as sd_bln_ini
from (
select a.kd_skpd,c.tgl_bukti as tgl,b.kd_rek6 as rek,b.nilai as nilai_pot,b.no_sp2d from trdtransout a 
LEFT JOIN trhtransout c on c.kd_skpd=a.kd_skpd and a.no_bukti=c.no_bukti
LEFT JOIN (
select b.tgl_bukti,a.kd_rek6,a.nilai,b.no_sp2d from trdstrpot a left join trhstrpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti  
)b on b.no_sp2d=a.no_sp2d
 where right(a.kd_skpd,2)!='00' and c.jns_spp in ('4','6') and c.pay='TUNAI' 
)z 
where z.no_sp2d <> '' and kd_skpd not in 
('1.02.01.01','4.08.05.08','4.08.05.09','4.08.05.10','4.08.03.11','4.08.03.12',
'4.08.05.07','4.08.03.13','4.08.03.14','4.08.06.06','4.08.06.07','4.08.06.08',
'4.08.06.09','4.08.04.08','4.08.04.09','4.08.04.10','4.08.04.11','4.08.04.12',
'4.08.03.10','4.08.02.08','4.08.02.09','4.08.02.10','4.08.02.11','4.08.03.15',
'4.08.03.16','4.08.02.12','4.08.01.06','4.08.01.08','4.08.01.09','4.08.01.07')
and kd_skpd='$lcskpd'";

        $sql_pajak_tunai_hasil = $this->db->query($sql_pajak_tunai);
        $trh_pajakTN = $sql_pajak_tunai_hasil->row();
        $tottrh_pajakTN = $trh_pajakTN->bln_lalu + $trh_pajakTN->bln_ini + $trh_pajakTN->sd_bln_ini;

        //Dropping Dana

        $sqldropin = "
            SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
            select 
						SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
						SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
						SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
						from tr_setorpelimpahan
						WHERE kd_skpd='$lcskpd'
            UNION ALL            
            select 
						SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
						SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
						SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
						from tr_setorpelimpahan_bank
						WHERE kd_skpd='$lcskpd'           
            UNION ALL
            SELECT 
            SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
            SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
            SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
            from tr_setorsimpanan WHERE kd_skpd='$lcskpd' and jenis='3'
            )x
            ";
        /*
            if(substr($lcskpd,8,2)=="00"){
               $sqldropin="
            SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
            select 
						SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
						SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
						SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
						from tr_setorsimpanan
						WHERE kd_skpd='$lcskpd'
            UNION ALL
            select 
						SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as bln_lalu,
						SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as bln_ini,
						SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as sd_bln_ini
						from tr_ambilsimpanan
						WHERE kd_skpd='$lcskpd')x
            "; 
            }else{
               $sqldropin="
            SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
            select 
						SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
						SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
						SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
						from tr_setorpelimpahan
						WHERE kd_skpd='$lcskpd'
            UNION ALL            
            select 
						SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
						SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
						SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
						from tr_setorpelimpahan_bank
						WHERE kd_skpd='$lcskpd')x
            "; 
            }
            
            */


        $hasil = $this->db->query($sqldropin);
        $trhdropin = $hasil->row();
        $totaldropin = $trhdropin->bln_lalu + $trhdropin->bln_ini + $trhdropin->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarin = "
            SELECT SUM(x.jar_bln_lalu) jar_bln_lalu, SUM(x.jar_bln_ini) jar_bln_ini, SUM(x.jar_sd_bln_ini) jar_sd_bln_ini FROM(
            SELECT 
            SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN 0 ELSE 0 END) as jar_bln_lalu,
            SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN 0 ELSE 0 END) as jar_bln_ini,
            SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN 0 ELSE 0 END) as jar_sd_bln_ini
            from tr_jpanjar WHERE kd_skpd='$lcskpd' and jns='1'
            )x";
        $hasil = $this->db->query($sqlpanjarin);
        $trhpanjarin = $hasil->row();
        $totalpanjarin = $trhpanjarin->jar_bln_lalu + $trhpanjarin->jar_bln_ini + $trhpanjarin->jar_sd_bln_ini;

        //Penyesuaian            
        $sqlpenyesuian = "
            SELECT 
            SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
            SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
            SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
            from trhtransout a 
            left join 
            (
            select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
            left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
            group by a.kd_skpd,a.no_sp2d
            ) b on left(b.kd_skpd,7)=left(a.kd_skpd,7) and a.no_sp2d=b.no_sp2d
            where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
            and a.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

            ";
        $hasil_psy = $this->db->query($sqlpenyesuian);
        $trhasil_psy = $hasil_psy->row();
        $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;

        //-------- TOTAL PENERIMAAN
        $jmtrmgaji_ll =  $trh1->sp2d_gj_ll + $trh2->jppn_gaji_ll + $trh3->jpph21_gaji_ll +
            $trh4->jpph22_gaji_ll + $trh5->jpph23_gaji_ll + $trh6->jlain_gaji_ll + $trh15->ppnpn_gaji_ll +
            $trh70->gj_iwp_lalu + $trh71->gj_tap_lalu + $trh72->gj_pph4_lalu;

        $jmtrmgaji_ini =  $trh1->sp2d_gj_ini + $trh2->jppn_gaji_ini + $trh3->jpph21_gaji_ini +
            $trh4->jpph22_gaji_ini + $trh5->jpph23_gaji_ini + $trh6->jlain_gaji_ini + $trh15->ppnpn_gaji_ini +
            $trh70->gj_iwp_ini + $trh71->gj_tap_ini + $trh72->gj_pph4_ini;

        $jmtrmgaji_sd = $jmtrmgaji_ll + $jmtrmgaji_ini;


        $jmtrmbrjs_ll =  $trh1->sp2d_brjs_ll + $trh2->jppn_brjs_ll + $trh3->jpph21_brjs_ll +
            $trh4->jpph22_brjs_ll + $trh5->jpph23_brjs_ll + $trh6->jlain_brjs_ll + $trh15->ppnpn_brjs_ll +
            $trh70->ls_iwp_lalu + $trh71->ls_tap_lalu + $trh72->ls_pph4_lalu;

        $jmtrmbrjs_ini =  $trh1->sp2d_brjs_ini + $trh2->jppn_brjs_ini + $trh3->jpph21_brjs_ini +
            $trh4->jpph22_brjs_ini + $trh5->jpph23_brjs_ini + $trh6->jlain_brjs_ini + $trh15->ppnpn_brjs_ini +
            $trh70->ls_iwp_ini + $trh71->ls_tap_ini + $trh72->ls_pph4_ini;

        $jmtrmbrjs_sd = $jmtrmbrjs_ll + $jmtrmbrjs_ini;

        $jmtrmup_ll =  $trh1->sp2d_up_ll + $trh2->jppn_up_ll + $trh3->jpph21_up_ll +
            $trh4->jpph22_up_ll + $trh5->jpph23_up_ll + $trh6->jlain_up_ll + $tox + $trh15->ppnpn_up_ll +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_lalu + $trhdropin->bln_lalu + $trhpanjarin->jar_bln_lalu + $trh_pajakTN->bln_lalu + $trhasil_psy->bln_lalu_psy;

        $jmtrmup_ini =  $trh1->sp2d_up_ini + $trh2->jppn_up_ini + $trh3->jpph21_up_ini +
            $trh4->jpph22_up_ini + $trh5->jpph23_up_ini + $trh6->jlain_up_ini + $trh15->ppnpn_up_ini +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_ini + $trhdropin->bln_ini + $trhpanjarin->jar_bln_ini + $trh_pajakTN->bln_ini + $trhasil_psy->bln_ini_psy;

        $jmtrmup_sd = $jmtrmup_ll + $jmtrmup_ini;


        $cRet .= "
                       
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll + $trh6->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll + $trh6->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll + $trh6->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
            
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->bln_lalu_psy, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->bln_ini_psy, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->sd_bln_ini_psy, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->sd_bln_ini_psy, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
            
			<tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
			
			<tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>						
			
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Jumlah Penerimaan :</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr> 
            
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
            
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Pengeluaran :</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $csql = "SELECT sum(gaji_lalu) as spj_gaji_ll, sum(gaji_ini) as spj_gaji_ini, sum(brg_lalu) as spj_brjs_ll, 
				sum(brg_ini) as spj_brjs_ini, sum(up_lalu) as spj_up_ll, sum(up_ini) as spj_up_ini from
				(select  a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (1,2,3) and pay not in ('PANJAR') 
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai*-1,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.TGL_BUKTI)='$nbulan' and b.pengurang_belanja=1
				union all
				select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (4)
				union all
				select  a.kd_skpd, isnull(a.rupiah*-1,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (1) and b.pot_khusus<>0
				union all
				select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_bukti)='$nbulan' and jns_spp in (6)
				union all
				select  a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_sts)='$nbulan' and b.jns_cp in (2) and b.pot_khusus<>0
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (1,2,3) and pay not in ('PANJAR')
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai*-1,0) as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.TGL_BUKTI)<'$nbulan' and b.pengurang_belanja=1
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (4)
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.rupiah*-1,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (1) and b.pot_khusus<>0
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_bukti)<'$nbulan' and jns_spp in (6)
				union all
				select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
				where MONTH(b.tgl_sts)<'$nbulan' and b.jns_cp in (2) and b.pot_khusus<>0) a 
				WHERE a.kd_skpd='$lcskpd' ";


        $hasil = $this->db->query($csql);
        $trh7 = $hasil->row();
        $totalspj = $trh7->spj_gaji_ll + $trh7->spj_gaji_ini + $trh7->spj_brjs_ll +
            $trh7->spj_brjs_ini + $trh7->spj_up_ll + $trh7->spj_up_ini;

        $cRet .= "
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;- SPJ(LS + UP/GU/TU)</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini + $trh7->spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini + $trh7->spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini + $trh7->spj_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalspj, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
            <tr>
			<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;- Penyetoran Pajak</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jppn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jppn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jppn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh8 = $hasil->row();
        $totalppn = $trh8->jppn_up_ini + $trh8->jppn_up_ll + $trh8->jppn_gaji_ini +
            $trh8->jppn_gaji_ll + $trh8->jppn_brjs_ini + $trh8->jppn_brjs_ll;

        $cRet .= "
            <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll + $trh8->jppn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll + $trh8->jppn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll + $trh8->jppn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";


        $lcrek = '210105010001'; // pph 21 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jpph21_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jpph21_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jpph21_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh9 = $hasil->row();
        $totalpph21 = $trh9->jpph21_up_ini + $trh9->jpph21_up_ll + $trh9->jpph21_gaji_ini +
            $trh9->jpph21_gaji_ll + $trh9->jpph21_brjs_ini + $trh9->jpph21_brjs_ll;


        $cRet .= "
             <tr> <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll + $trh9->jpph21_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll + $trh9->jpph21_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll + $trh9->jpph21_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210105020001'; // pph 22 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jpph22_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jpph22_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jpph22_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh10 = $hasil->row();
        $totalpph22 = $trh10->jpph22_up_ini + $trh10->jpph22_up_ll + $trh10->jpph22_gaji_ini +
            $trh10->jpph22_gaji_ll + $trh10->jpph22_brjs_ini + $trh10->jpph22_brjs_ll;


        $cRet .= "
             <tr>
			 <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll + $trh10->jpph22_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll + $trh10->jpph22_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll + $trh10->jpph22_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210105030001'; // pph 23 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS jpph23_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS jpph23_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS jpph23_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh11 = $hasil->row();
        $totalpph23 = $trh11->jpph23_up_ini + $trh11->jpph23_up_ll + $trh11->jpph23_gaji_ini +
            $trh11->jpph23_gaji_ll + $trh11->jpph23_brjs_ini + $trh11->jpph23_brjs_ll;


        $cRet .= "
             <tr>
			 <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll + $trh11->jpph23_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll + $trh11->jpph23_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll + $trh11->jpph23_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_iwp_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_iwp_sdini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh73 = $hasil->row();
        $totaliwp_setor = $trh73->up_iwp_sdini + $trh73->gj_iwp_sdini + $trh73->ls_iwp_sdini;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totaliwp_setor, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh74 = $hasil->row();
        $totaltap_setor = $trh74->up_tap_sdini + $trh74->gj_tap_sdini + $trh74->ls_tap_sdini;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totaltap_setor, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";


        $lcrek = '210601050005'; // pph4
        $csql = "SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<='$nbulan' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh75 = $hasil->row();
        $totalpph4_setor = $trh75->up_pph4_sdini + $trh75->gj_pph4_sdini + $trh75->ls_pph4_sdini;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalpph4_setor, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";






        $lcrek = '210102010001'; // PPnpn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='4') AS ppnpn_gaji_ll,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ini,
                    (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                    ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd = '$lcskpd' AND 
                    b.kd_rek6 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                    a.jns_spp ='6') AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh16 = $hasil->row();
        $totalppnpn = $trh16->ppnpn_up_ini + $trh16->ppnpn_up_ll + $trh16->ppnpn_gaji_ini +
            $trh16->ppnpn_gaji_ll + $trh16->ppnpn_brjs_ini + $trh16->ppnpn_brjs_ll;


        $cRet .= "
             <tr>
			 <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll + $trh16->ppnpn_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll + $trh16->ppnpn_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll + $trh16->ppnpn_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        // HKPG
        $csql = "SELECT 
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
				SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
				FROM trdkasin_pkd a 
				INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        $hasil = $this->db->query($csql);
        $trhxx = $hasil->row();
        $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- HKPG</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->gj_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->gj_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->gj_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->ls_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->ls_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->ls_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->up_hkpg_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->up_hkpg_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxx->up_hkpg_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totalhkpg, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        // Potongan Penghasilan Lainnya
        $csql = "SELECT 
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_lalu,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_ini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS up_lain_sdini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_lalu,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_ini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS ls_lain_sdini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_lalu,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_ini,
				SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and MONTH(tgl_sts)<='$nbulan'  then a.rupiah else 0 end),0)) AS gj_lain_sdini
				FROM trdkasin_pkd a 
				INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        $hasil = $this->db->query($csql);
        $trhxy = $hasil->row();
        $totallain = $trhxy->up_lain_sdini + $trhxy->gj_lain_sdini + $trhxy->ls_lain_sdini;


        $cRet .= "
             <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Penghasilan Lainnya</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_sdini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";

        // CONTRA POS
        $csql = "SELECT 
			SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ini,
			SUM(isnull((case when rtrim(jns_cp)= '3' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_up_ll,
			SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ini,
			SUM(isnull((case when rtrim(jns_cp)= '1' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_gaji_ll,
			SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)='$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ini,
			SUM(isnull((case when rtrim(jns_cp)= '2' and MONTH(tgl_sts)<'$nbulan' then z.nilai else 0 end),0)) AS cp_spj_brjs_ll
			from (select rupiah as nilai,jns_trans,pot_khusus,jns_cp,d.tgl_sts ,d.kd_skpd from 
			trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd where d.kd_skpd ='$lcskpd' AND 
			((jns_trans='5' AND pot_khusus='0') OR jns_trans='1')) z";

        $hasil = $this->db->query($csql);
        $trh_x = $hasil->row();
        $total_cp = $trh_x->cp_spj_up_ini + $trh_x->cp_spj_up_ll + $trh_x->cp_spj_gaji_ini +
            $trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_brjs_ini + $trh_x->cp_spj_brjs_ll;


        $cRet .= "
             <tr>
			 <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Contra Pos</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll + $trh_x->cp_spj_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll + $trh_x->cp_spj_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($total_cp, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>";


        // lain lain setoran
        $csql = "SELECT 
					SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
					SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
					SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
					 FROM(
					SELECT 
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN b.jns_spp IN ('4') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)<'$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN b.jns_spp IN ('6') AND MONTH(b.tgl_bukti)='$nbulan' AND a.kd_rek6 NOT IN ('210105010001','210105020001','210105030001','210106010001','210102010001','210108010001','210107010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
					FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
					WHERE a.kd_skpd='$lcskpd'
					UNION ALL
					SELECT 
					SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN a.jns_beban='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
					SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
					SUM(CASE WHEN a.jns_beban='4' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
					SUM(CASE WHEN a.jns_beban='6' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
					SUM(CASE WHEN a.jns_beban='6' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
					FROM TRHOUTLAIN a 
					WHERE a.kd_skpd='$lcskpd'
					) a";
        $hasil = $this->db->query($csql);
        $trh12 = $hasil->row();
        $totallain = $trh12->jlain_up_ini + $trh12->jlain_up_ll + $trh12->jlain_gaji_ini +
            $trh12->jlain_gaji_ll + $trh12->jlain_brjs_ini + $trh12->jlain_brjs_ll;

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'=$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ini = $hasil->row('jumlah');
        // echo  $tox_ini;
        $tox_ini = (empty($tox_ini) ? 0 : $tox_ini);

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '1'<$nbulan";
        $hasil = $this->db->query($tox_awal);
        $tox_ll = $hasil->row('jumlah');
        $tox_ll = (empty($tox_ll) ? 0 : $tox_ll);

        //			echo  '-'.$tox_ini;
        //			echo  '-'.$tox_ll;


        //Dropping Dana

        //if(substr($lcskpd,8,2)=="00"){
        $sqldropout = "SELECT SUM(z.bln_lalu) bln_lalu, SUM(z.bln_ini) bln_ini, SUM(z.sd_bln_ini) sd_bln_ini from(
                        select 
                        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                        from tr_setorpelimpahan_bank
                        WHERE kd_skpd_sumber='$lcskpd'
                        UNION ALL
                        select 
                        SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu,
                        SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as bln_ini,
                        SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini
                        from tr_setorpelimpahan
                        WHERE kd_skpd_sumber='$lcskpd'
                        )z";

        $hasil = $this->db->query($sqldropout);
        $trhdropout = $hasil->row();
        $totaldropout = $trhdropout->bln_lalu + $trhdropout->bln_ini + $trhdropout->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarout = "SELECT 
						SUM(CASE WHEN MONTH(tgl_kas)<'$nbulan' THEN nilai ELSE 0 END) as jar_bln_lalu,
						SUM(CASE WHEN MONTH(tgl_kas)='$nbulan' THEN nilai ELSE 0 END) as jar_bln_ini,
						SUM(CASE WHEN MONTH(tgl_kas)<='$nbulan' THEN nilai ELSE 0 END) as jar_sd_bln_ini
						from tr_panjar
						WHERE kd_skpd='$lcskpd'";
        $hasil = $this->db->query($sqlpanjarout);
        $trhpanjarout = $hasil->row();
        $totalpanjarout = $trhpanjarout->jar_bln_lalu + $trhpanjarout->jar_bln_ini + $trhpanjarout->jar_sd_bln_ini;


        //Penyesuaian            
        $sqlpenyesuian = "
            SELECT 
            SUM(CASE WHEN MONTH(tgl_bukti)<'$nbulan' THEN nilai ELSE 0 END) as bln_lalu_psy,
            SUM(CASE WHEN MONTH(tgl_bukti)='$nbulan' THEN nilai ELSE 0 END) as bln_ini_psy,
            SUM(CASE WHEN MONTH(tgl_bukti)<='$nbulan' THEN nilai ELSE 0 END) as sd_bln_ini_psy
            from trhtransout a 
            left join 
            (
            select a.kd_skpd,a.no_sp2d,sum(a.nilai) as nilai from trhtrmpot a 
            left join trdtrmpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti
            group by a.kd_skpd,a.no_sp2d
            ) b on left(b.kd_skpd,17)=left(a.kd_skpd,17) and a.no_sp2d=b.no_sp2d
            where a.jns_spp in ('4','6') and a.pay='BANK' and right(a.kd_skpd,2)<>'00' 
            and b.kd_skpd='$lcskpd' and left(a.kd_skpd,7) not in ('1.02.01') 

            ";
        $hasil_psy = $this->db->query($sqlpenyesuian);
        $trhasil_psy = $hasil_psy->row();
        $totalhasil_psy = $trhasil_psy->bln_lalu_psy + $trhasil_psy->bln_ini_psy + $trhasil_psy->sd_bln_ini_psy;


        $jmsetgaji_ll =  $trh7->spj_gaji_ll + $trh8->jppn_gaji_ll + $trh9->jpph21_gaji_ll + $trh16->ppnpn_gaji_ll +
            $trh10->jpph22_gaji_ll + $trh11->jpph23_gaji_ll + $trh12->jlain_gaji_ll + $trh_x->cp_spj_gaji_ll +
            $trh73->gj_iwp_lalu + $trh74->gj_tap_lalu + $trh75->gj_pph4_lalu + $trhxx->gj_hkpg_lalu + $trhxy->gj_lain_lalu;

        $jmsetgaji_ini = $trh7->spj_gaji_ini + $trh8->jppn_gaji_ini + $trh9->jpph21_gaji_ini + $trh16->ppnpn_gaji_ini +
            $trh10->jpph22_gaji_ini + $trh11->jpph23_gaji_ini + $trh12->jlain_gaji_ini + $trh_x->cp_spj_gaji_ini +
            $trh73->gj_iwp_ini + $trh74->gj_tap_ini + $trh75->gj_pph4_ini + $trhxx->gj_hkpg_ini + $trhxy->gj_lain_ini;

        $jmsetgaji_sd = $jmsetgaji_ll + $jmsetgaji_ini;


        $jmsetbrjs_ll =  $trh7->spj_brjs_ll + $trh8->jppn_brjs_ll + $trh9->jpph21_brjs_ll + $trh16->ppnpn_brjs_ll +
            $trh10->jpph22_brjs_ll + $trh11->jpph23_brjs_ll + $trh12->jlain_brjs_ll + $trh_x->cp_spj_brjs_ll +
            $trh73->ls_iwp_lalu + $trh74->ls_tap_lalu + $trh75->ls_pph4_lalu + $trhxx->ls_hkpg_lalu + $trhxy->ls_lain_lalu;

        $jmsetbrjs_ini =  $trh7->spj_brjs_ini + $trh8->jppn_brjs_ini + $trh9->jpph21_brjs_ini + $trh16->ppnpn_brjs_ini +
            $trh10->jpph22_brjs_ini + $trh11->jpph23_brjs_ini + $trh12->jlain_brjs_ini + $trh_x->cp_spj_brjs_ini +
            $trh73->ls_iwp_ini + $trh74->ls_tap_ini + $trh75->ls_pph4_ini + $trhxx->ls_hkpg_ini + $trhxy->ls_lain_ini;

        $jmsetbrjs_sd = $jmsetbrjs_ll + $jmsetbrjs_ini;
        /* 
            $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll +
                             $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll; */

        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll + $trh16->ppnpn_up_ll +
            $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll + $tox_ll + $trh_x->cp_spj_up_ll +
            $trh73->up_iwp_lalu + $trh74->up_tap_lalu + $trh75->up_pph4_lalu + $trhxx->up_hkpg_lalu + $trhxy->up_lain_lalu +
            $trhdropout->bln_lalu + $trhpanjarout->jar_bln_lalu + $trhasil_psy->bln_lalu_psy;

        $jmsetup_ini =  $trh7->spj_up_ini + $trh8->jppn_up_ini + $trh9->jpph21_up_ini + $trh16->ppnpn_up_ini +
            $trh10->jpph22_up_ini + $trh11->jpph23_up_ini + $trh12->jlain_up_ini + $tox_ini + $trh_x->cp_spj_up_ini +
            $trh73->up_iwp_ini + $trh74->up_tap_ini + $trh75->up_pph4_ini + $trhxx->up_hkpg_ini + $trhxy->up_lain_ini +
            $trhdropout->bln_ini + $trhpanjarout->jar_bln_ini + $trhasil_psy->bln_ini_psy;

        $jmsetup_sd = $jmsetup_ll + $jmsetup_ini;


        $cRet .= "
                       
            <tr>
			<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll + $trh12->jlain_gaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll + $trh12->jlain_brjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll + $trh12->jlain_up_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
			
            <tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Penyesuaian</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->bln_lalu_psy, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->bln_ini_psy, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->sd_bln_ini_psy, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhasil_psy->sd_bln_ini_psy, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
			
			<tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>

			<tr>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_lalu, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
            
			";

        $cRet .= "
            <tr>
			<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Jumlah Pengeluaran :</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetup_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetup_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd + $jmsetbrjs_sd + $jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr> 
                        
            <tr>
			<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
            <tr>
			<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='left' style='font-size:12px' colspan='2'>Saldo Kas</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll - $jmsetgaji_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini - $jmsetgaji_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd - $jmsetgaji_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll - $jmsetbrjs_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini - $jmsetbrjs_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd - $jmsetbrjs_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll - $jmsetup_ll, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini - $jmsetup_ini, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd - $jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
                <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd - $jmsetgaji_sd - $jmsetbrjs_sd - $jmsetup_sd, "2", ",", ".") . "&nbsp;</td>
           <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
		   </tr>
            <tr>
			<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
                <td align='center' style='font-size:12px' colspan='2'>&nbsp;</td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
                <td align='center' style='font-size:12px'></td>
				<td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            </tr>
            </table>";
        if ($jenis == '1') {


            $cRet .= '<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $jabatan . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $jabatan1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
     				   <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					    <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
						<TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $nip . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';
        } else if ($jenis == '2') {

            $cRet .= '<TABLE width="100%" style="font-size:12px">
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" ><b>&nbsp;</TD>
                        <TD align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" >Mengetahui,</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $jabatan . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $jabatan1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
     				   <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
						<TD align="center" ><b>&nbsp;</TD>
					    <TD align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
						<TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
					</TR>
                    <TR>
						<TD align="center" >' . $nip . '</TD>
						<TD align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip1 . '</TD>
					</TR>
					</TABLE><br/>';
        }

        $data['prev'] = $cRet;
        if ($ctk == 0) {
            echo "<title>  SPJ $bulan</title>";
            echo $cRet;
        } else {
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, 'L', 0, '', $atas, $bawah, $kiri, $kanan);
        }
    }


    function cetak_spj_priode($lcskpd = '', $ntgl1 = '', $ntgl2 = '', $ttd1 = '', $tgl_ctk = '', $ttd2 = '', $ctk = '', $atas = '', $bawah = '', $kiri = '', $kanan = '', $jenis = '', $jns_bp, $jns_ang = '')
    {
        $ttd1 = str_replace('123456789', ' ', $ttd1);
        $ttd2 = str_replace('123456789', ' ', $ttd2);
        $lcskpdd = substr($lcskpd, 0, 7) . ".00";


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where left(kd_skpd,17)=left('$lcskpd',17) and (kode='PA' or kode='KPA') and nip='$ttd2'";
        $lcskpdd = $lcskpd;

        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama2 = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        if ($jns_bp == "bk") {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$ttd1'";
        } else {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$ttd1'";
            $lcskpdd = $lcskpdd;
        }
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }

        $sqlanggaran1 = "SELECT jns_ang as anggaran, (case when jns_ang='M' then 'Penetapan'
            when jns_ang='P1' then 'Penyempurnaan I'
            when jns_ang='P2' then 'Penyempurnaan II'
            when jns_ang='P3' then 'Penyempurnaan III'
            when jns_ang='U1' then 'Ubah I' 
            else 'Ubah II' end) as nm_ang from trhrka where kd_skpd='$lcskpd' AND tgl_dpa in (SELECT MAX(tgl_dpa) from trhrka where kd_skpd=trhrka.kd_skpd)";
        $sqlanggaran = $this->db->query($sqlanggaran1);
        foreach ($sqlanggaran->result() as $rowttd) {
            $anggaran = $rowttd->anggaran;
        }

        $tanda_ang = 2;
        $thn_ang       = $this->session->userdata('pcThang');

        $skpd = $lcskpd;
        $nama =  $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        //$bulan= $this->tukd_model->getBulan($nbulan); 
        $prv = $this->db->query("SELECT top 1 provinsi,daerah from sclient ");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;
        if ($jenis == '1') {
            $judul = 'SPJ FUNGSIONAL';
        } else if ($jenis == '2') {
            $judul = 'SPJ ADMINISTRATIF';
        } else {
            $judul = 'SPJ BELANJA';
        }
        $cRet = '';
        $cRet = "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>";
        $cRet .= "
    
        <tr>
        <td rowspan=\"5\" align=\"left\" width=\"7%\">
        <img src=\"" . base_url() . "/image/logo_melawi.png\"  width=\"75\" height=\"100\" />
        </td>
        </tr>
        
        <tr>
        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><br><strong>PEMERINTAH KABUPATEN MELAWI </strong></td>
        </tr>
        
        <tr>
        <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD " . strtoupper($nama) . " </strong></td>
        </tr>        
        <tr>
        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN " . date("Y") . "</strong>
        </td>
        </tr>
        <tr>
        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td>
        </tr>
        </table>
        ";
        $cRet .= "<table style='border-collapse:collapse;' width='100%' align='center' border='0' cellspacing='1' cellpadding='1'>";
        $cRet .= "
            
            <tr>
                <td align='center' style='font-size:14px;' colspan='2'>
                 <b> LAPORAN PERTANGGUNGJAWABAN BENDAHARA PENGELUARAN <BR></b>
                 <b>(" . $judul . ")<BR>
                 " . $this->support->tanggal_format_indonesia($ntgl1) . " s/d " . $this->support->tanggal_format_indonesia($ntgl2) . "</b>
                </td>
            </tr>
           
            </table><br>";
        $ceksx = substr($skpd, 18, 4);

        //if($ceksx=='0000'){
        //     $cRet .="
        // <tr>
        //     <td align='left' style='font-size:12px;'>
        //       Pengguna Anggaran
        //     </td> 
        //     <td style='font-size:12px;'>: $nama2
        //     </td>         
        // </tr>
        // <tr>
        //     <td align='left' style='font-size:12px;'>
        //       Bendahara Pengeluaran
        //     </td> 
        //     <td style='font-size:12px;'>: $nama1
        //     </td>         
        // </tr>";
        // }else{
        //     $cRet .="
        // <tr>
        //     <td align='left' style='font-size:12px;'>
        //       Kuasa Pengguna Anggaran                 
        //     </td> 
        //     <td style='font-size:12px;'>: $nama2
        //     </td>         
        // </tr>
        // <tr>
        //     <td align='left' style='font-size:12px;'>
        //      Bendahara Pengeluaran Pembantu
        //     </td> 
        //     <td style='font-size:12px;'>: $nama1
        //     </td>         
        // </tr>";
        // }

        $cRet .= "
        <table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='1' cellpadding='1'>
        <thead>
        <tr>
            <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Kode<br>Rekening</b></td>
            <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Uraian</b></td>
            <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah<br>Anggaran</b></td>
            <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Gaji</b></td>
            <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ-LS Barang & Jasa</b></td>
            <td bgcolor='#CCCCCC' align='center' colspan='3' style='font-size:12px'><b>SPJ UP/GU/TU</b></td>
            <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Jumlah SPJ<br>(LS+UP/GU/TU)<br>s.d Bulan Ini</b></td>
            <td bgcolor='#CCCCCC' align='center' rowspan='2' style='font-size:12px'><b>Sisa Pagu<br>Anggaran</b></td>
        </tr>
        <tr>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan<br>lalu</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>Bulan Ini</b></td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'><b>s.d<br>Bulan Ini</b></td>
        </tr>                 
        <tr>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>1</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>2</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>3</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>4</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>5</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>6</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>7</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>8</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>9</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>10</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>11</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>12</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>13</td>
            <td bgcolor='#CCCCCC' align='center' style='font-size:12px'>14</td>
        </tr> 
         </thead>
        <tr>
            <td align='center' style='font-size:12px'>&nbsp;</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
        </tr>";

        $att = "spj_skpd_tgl '$lcskpd','$ntgl1','$ntgl2', '$jns_ang'";
        $hasil = $this->db->query($att);
        foreach ($hasil->result() as $trh1) {
            $bre                =   $trh1->kd_rek;
            $wok                =   $trh1->uraian;
            $kode               =   $trh1->kode;
            $nilai              =   $trh1->anggaran;
            $real_up_ini        =   $trh1->up_ini;
            $real_up_ll         =   $trh1->up_lalu;
            $real_gaji_ini      =   $trh1->gaji_ini;
            $real_gaji_ll       =   $trh1->gaji_lalu;
            $real_brg_js_ini    =   $trh1->brg_ini;
            $real_brg_js_ll     =   $trh1->brg_lalu;
            $total  = $real_gaji_ll + $real_gaji_ini + $real_brg_js_ll + $real_brg_js_ini + $real_up_ll + $real_up_ini;
            $sisa   = $nilai - $real_gaji_ll - $real_gaji_ini - $real_brg_js_ll - $real_brg_js_ini - $real_up_ll - $real_up_ini;
            $a = strlen($bre);
            if ($a == 7) {
                $cRet .= "
       <tr>
            <td   valign='top' width='8%' align='left' style='font-size:12px' ><b>" . $bre . "</b></td>
            <td   valign='top' align='left' width='25%' style='font-size:12px'><b>" . $wok . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($total, "2", ",", ".") . "</b></b></td>
            <td   valign='top' align='right' style='font-size:12px'><b>" . number_format($sisa, "2", ",", ".") . "</b></td>
        </tr>";
            } else if ($a == 15 || $a == 12) {
                $cRet .= "
       <tr>
            <td valign='top' width='8%' align='left' style='font-size:12px' ><b>" . $bre . "</b></td>
            <td valign='top' align='left' width='25%' style='font-size:12px'><b>" . $wok . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($nilai, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ini, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ini, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ini, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($total, "2", ",", ".") . "</b></b></td>
            <td valign='top' align='right' style='font-size:12px'><b>" . number_format($sisa, "2", ",", ".") . "</b></td>
        </tr>";
            } else {
                $cRet .= "
            <tr>
            <td valign='top' width='8%' align='left' style='font-size:12px' >" . $kode . "</td>
            <td valign='top' align='left' width='25%' style='font-size:12px'>" . $wok . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($nilai, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_gaji_ll, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_gaji_ini, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_gaji_ll + $real_gaji_ini, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_brg_js_ll, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_brg_js_ini, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_brg_js_ll + $real_brg_js_ini, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_up_ll, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_up_ini, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($real_up_ll + $real_up_ini, "2", ",", ".") . "</td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($total, "2", ",", ".") . "</b></td>
            <td valign='top' align='right' style='font-size:12px'>" . number_format($sisa, "2", ",", ".") . "</td>
        </tr>";
            }
        }
        $cRet .= "

        <tr>
            <td valign='top' align='center' style='font-size:12px' >&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Penerimaan :</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td valign='top' align='center' style='font-size:12px'></td>
        </tr>";

        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                a.tgl_kas>='$ntgl1' AND a.tgl_kas<='$ntgl2' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                a.tgl_kas<'$ntgl1' AND c.jns_spp IN ('1','2','3') AND a.status='1') AS sp2d_up_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                a.tgl_kas>='$ntgl1' AND a.tgl_kas<='$ntgl2' AND c.jns_spp ='4' AND a.status='1') AS sp2d_gj_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                a.tgl_kas<'$ntgl1' AND c.jns_spp ='4'  AND a.status='1') AS sp2d_gj_ll,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                a.tgl_kas>='$ntgl1' AND a.tgl_kas<='$ntgl2' AND c.jns_spp ='6'  AND a.status='1') AS sp2d_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhsp2d a INNER JOIN trdspp b 
                ON a.no_spp = b.no_spp INNER JOIN trhspp c
                ON a.no_spp = c.no_spp WHERE a.kd_skpd = '$lcskpd' AND 
                a.tgl_kas<'$ntgl1' AND c.jns_spp ='6' AND a.status='1') AS sp2d_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh1 = $hasil->row();
        $totalsp2d = $trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini + $trh1->sp2d_brjs_ll +
            $trh1->sp2d_brjs_ini + $trh1->sp2d_up_ll + $trh1->sp2d_up_ini;

        $cobacoba = $trh1->sp2d_gj_ll;



        $cRet .= "   
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2' >&ensp;&ensp;- SP2D</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_gj_ll + $trh1->sp2d_gj_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_brjs_ll + $trh1->sp2d_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh1->sp2d_up_ll + $trh1->sp2d_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalsp2d, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> ";

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Potongan Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2'  AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh2 = $hasil->row();
        $totalppn = $trh2->jppn_up_ini + $trh2->jppn_up_ll + $trh2->jppn_gaji_ini +
            $trh2->jppn_gaji_ll + $trh2->jppn_brjs_ini + $trh2->jppn_brjs_ll;


        $cRet .= " 
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_gaji_ll + $trh2->jppn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_brjs_ll + $trh2->jppn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh2->jppn_up_ll + $trh2->jppn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105010001'; // pph 21 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $totalpph21 = $trh3->jpph21_up_ini + $trh3->jpph21_up_ll + $trh3->jpph21_gaji_ini +
            $trh3->jpph21_gaji_ll + $trh3->jpph21_brjs_ini + $trh3->jpph21_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_gaji_ll + $trh3->jpph21_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_brjs_ll + $trh3->jpph21_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh3->jpph21_up_ll + $trh3->jpph21_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();
        $totalpph22 = $trh4->jpph22_up_ini + $trh4->jpph22_up_ll + $trh4->jpph22_gaji_ini +
            $trh4->jpph22_gaji_ll + $trh4->jpph22_brjs_ini + $trh4->jpph22_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_gaji_ll + $trh4->jpph22_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_brjs_ll + $trh4->jpph22_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh4->jpph22_up_ll + $trh4->jpph22_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 terima
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh5 = $hasil->row();
        $totalpph23 = $trh5->jpph23_up_ini + $trh5->jpph23_up_ll + $trh5->jpph23_gaji_ini +
            $trh5->jpph23_gaji_ll + $trh5->jpph23_brjs_ini + $trh5->jpph23_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_gaji_ll + $trh5->jpph23_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_brjs_ll + $trh5->jpph23_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh5->jpph23_up_ll + $trh5->jpph23_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";





        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' THEN a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' THEN a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' THEN a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh70 = $hasil->row();
        $totaliwp = $trh70->up_iwp_sdini + $trh70->gj_iwp_sdini + $trh70->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->gj_iwp_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->ls_iwp_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh70->up_iwp_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' THEN a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<='$ntgl2' THEN a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' THEN a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh71 = $hasil->row();
        $totaltap = $trh71->up_tap_sdini + $trh71->gj_tap_sdini + $trh71->ls_tap_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->gj_tap_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->ls_tap_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh71->up_tap_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210601050005'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh72 = $hasil->row();
        $totalpph4 = $trh72->up_pph4_sdini + $trh72->gj_pph4_sdini + $trh72->ls_pph4_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->gj_pph4_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->ls_pph4_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh72->up_pph4_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210102010001'; // PPnPn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh15 = $hasil->row();
        $totalppnpn = $trh15->ppnpn_up_ini + $trh15->ppnpn_up_ll + $trh15->ppnpn_gaji_ini +
            $trh15->ppnpn_gaji_ll + $trh15->ppnpn_brjs_ini + $trh15->ppnpn_brjs_ll;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_gaji_ll + $trh15->ppnpn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_brjs_ll + $trh15->ppnpn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh15->ppnpn_up_ll + $trh15->ppnpn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        // lain terima
        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdtrmpot a INNER JOIN trhtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND a.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND a.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban='6' AND a.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban='6' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHINLAIN a WHERE pengurang_belanja !='1'
                AND a.kd_skpd='$lcskpd'
                ) a ";

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd'";
        $hasil = $this->db->query($tox_awal);
        $tox = $hasil->row('jumlah');

        $hasil = $this->db->query($csql);
        $trh6 = $hasil->row();
        $totallain = $trh6->jlain_up_ini + $trh6->jlain_up_ll + $trh6->jlain_gaji_ini +
            $trh6->jlain_gaji_ll + $trh6->jlain_brjs_ini + $trh6->jlain_brjs_ll;


        //tambahan_pajak_tunai

        $sql_pajak_tunai = "SELECT 
SUM(CASE WHEN z.tgl<'$ntgl1' THEN z.nilai_pot ELSE 0 END) as bln_lalu,
SUM(CASE WHEN z.tgl>='$ntgl1' AND z.tgl<='$ntgl2' THEN z.nilai_pot ELSE 0 END) as bln_ini,
SUM(CASE WHEN z.tgl<='$ntgl2' THEN z.nilai_pot ELSE 0 END) as sd_bln_ini
from (
select a.kd_skpd,c.tgl_bukti as tgl,b.kd_rek6 as rek,b.nilai as nilai_pot,b.no_sp2d from trdtransout a 
LEFT JOIN trhtransout c on c.kd_skpd=a.kd_skpd and a.no_bukti=c.no_bukti
LEFT JOIN (
select b.tgl_bukti,a.kd_rek6,a.nilai,b.no_sp2d from trdstrpot a left join trhstrpot b on b.kd_skpd=a.kd_skpd and b.no_bukti=a.no_bukti  
)b on b.no_sp2d=a.no_sp2d
where right(a.kd_skpd,2)!='00' and c.jns_spp in ('4','6') and c.pay='TUNAI' 
)z 
where z.no_sp2d <> '' and kd_skpd not in 
('1.02.01.01','4.08.05.08','4.08.05.09','4.08.05.10','4.08.03.11','4.08.03.12',
'4.08.05.07','4.08.03.13','4.08.03.14','4.08.06.06','4.08.06.07','4.08.06.08',
'4.08.06.09','4.08.04.08','4.08.04.09','4.08.04.10','4.08.04.11','4.08.04.12',
'4.08.03.10','4.08.02.08','4.08.02.09','4.08.02.10','4.08.02.11','4.08.03.15',
'4.08.03.16','4.08.02.12','4.08.01.06','4.08.01.08','4.08.01.09','4.08.01.07')
and kd_skpd='$lcskpd'";

        $sql_pajak_tunai_hasil = $this->db->query($sql_pajak_tunai);
        $trh_pajakTN = $sql_pajak_tunai_hasil->row();
        $tottrh_pajakTN = $trh_pajakTN->bln_lalu + $trh_pajakTN->bln_ini + $trh_pajakTN->sd_bln_ini;

        //Dropping Dana


        $sqldropin = "
        SELECT sum(x.bln_lalu) bln_lalu,sum(x.bln_ini) bln_ini,sum(x.sd_bln_ini) sd_bln_ini from(
        select 
                    SUM(CASE WHEN tgl_kas<'$ntgl1' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN tgl_kas>='$ntgl1' AND tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd='$lcskpd'
        UNION ALL            
        select 
                    SUM(CASE WHEN tgl_kas<'$ntgl1' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN tgl_kas>='$ntgl1' AND tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd='$lcskpd')x
        ";

        $hasil = $this->db->query($sqldropin);
        $trhdropin = $hasil->row();
        $totaldropin = $trhdropin->bln_lalu + $trhdropin->bln_ini + $trhdropin->sd_bln_ini;

        //Panjar Dana

        $sqlpanjarin = "
        SELECT SUM(x.jar_bln_lalu) jar_bln_lalu, SUM(x.jar_bln_ini) jar_bln_ini, SUM(x.jar_sd_bln_ini) jar_sd_bln_ini FROM(
        SELECT 
        SUM(CASE WHEN tgl_kas<'$ntgl1' THEN 0 ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN tgl_kas>='$ntgl1' AND tgl_kas<='$ntgl2' THEN 0 ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN tgl_kas<='$ntgl2' THEN 0 ELSE 0 END) as jar_sd_bln_ini
        from tr_jpanjar WHERE kd_skpd='$lcskpd' and jns='1'
        UNION ALL
        SELECT 
        SUM(CASE WHEN tgl_kas<'$ntgl1' THEN nilai ELSE 0 END) as jar_bln_lalu,
        SUM(CASE WHEN tgl_kas>='$ntgl1' AND tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as jar_bln_ini,
        SUM(CASE WHEN tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as jar_sd_bln_ini
        from tr_setorsimpanan WHERE kd_skpd='$lcskpd' and jenis='3')x";
        $hasil = $this->db->query($sqlpanjarin);
        $trhpanjarin = $hasil->row();
        $totalpanjarin = $trhpanjarin->jar_bln_lalu + $trhpanjarin->jar_bln_ini + $trhpanjarin->jar_sd_bln_ini;


        //-------- TOTAL PENERIMAAN
        $jmtrmgaji_ll =  $trh1->sp2d_gj_ll + $trh2->jppn_gaji_ll + $trh3->jpph21_gaji_ll +
            $trh4->jpph22_gaji_ll + $trh5->jpph23_gaji_ll + $trh6->jlain_gaji_ll + $trh15->ppnpn_gaji_ll +
            $trh70->gj_iwp_lalu + $trh71->gj_tap_lalu + $trh72->gj_pph4_lalu;

        $jmtrmgaji_ini =  $trh1->sp2d_gj_ini + $trh2->jppn_gaji_ini + $trh3->jpph21_gaji_ini +
            $trh4->jpph22_gaji_ini + $trh5->jpph23_gaji_ini + $trh6->jlain_gaji_ini + $trh15->ppnpn_gaji_ini +
            $trh70->gj_iwp_ini + $trh71->gj_tap_ini + $trh72->gj_pph4_ini;

        $jmtrmgaji_sd = $jmtrmgaji_ll + $jmtrmgaji_ini;


        $jmtrmbrjs_ll =  $trh1->sp2d_brjs_ll + $trh2->jppn_brjs_ll + $trh3->jpph21_brjs_ll +
            $trh4->jpph22_brjs_ll + $trh5->jpph23_brjs_ll + $trh6->jlain_brjs_ll + $trh15->ppnpn_brjs_ll +
            $trh70->ls_iwp_lalu + $trh71->ls_tap_lalu + $trh72->ls_pph4_lalu;

        $jmtrmbrjs_ini =  $trh1->sp2d_brjs_ini + $trh2->jppn_brjs_ini + $trh3->jpph21_brjs_ini +
            $trh4->jpph22_brjs_ini + $trh5->jpph23_brjs_ini + $trh6->jlain_brjs_ini + $trh15->ppnpn_brjs_ini +
            $trh70->ls_iwp_ini + $trh71->ls_tap_ini + $trh72->ls_pph4_ini;

        $jmtrmbrjs_sd = $jmtrmbrjs_ll + $jmtrmbrjs_ini;

        $jmtrmup_ll =  $trh1->sp2d_up_ll + $trh2->jppn_up_ll + $trh3->jpph21_up_ll +
            $trh4->jpph22_up_ll + $trh5->jpph23_up_ll + $trh6->jlain_up_ll + $tox + $trh15->ppnpn_up_ll +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_lalu + $trhdropin->bln_lalu + $trhpanjarin->jar_bln_lalu + $trh_pajakTN->bln_lalu;

        $jmtrmup_ini =  $trh1->sp2d_up_ini + $trh2->jppn_up_ini + $trh3->jpph21_up_ini +
            $trh4->jpph22_up_ini + $trh5->jpph23_up_ini + $trh6->jlain_up_ini + $trh15->ppnpn_up_ini +
            $trh70->up_iwp_ini + $trh71->up_tap_ini + $trh72->up_pph4_ini + $trhdropin->bln_ini + $trhpanjarin->jar_bln_ini + $trh_pajakTN->bln_ini;

        $jmtrmup_sd = $jmtrmup_ll + $jmtrmup_ini;



        $cRet .= "
                   
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_gaji_ll + $trh6->jlain_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_brjs_ll + $trh6->jlain_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh6->jlain_up_ll + $trh6->jlain_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropin->sd_bln_ini, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarin->jar_sd_bln_ini, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>                       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Penerimaan :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> 
       
       
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Pengeluaran :</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $csql = "SELECT sum(gaji_lalu) as spj_gaji_ll, sum(gaji_ini) as spj_gaji_ini, sum(brg_lalu) as spj_brjs_ll, 
            sum(brg_ini) as spj_brjs_ini, sum(up_lalu) as spj_up_ll, sum(up_ini) as spj_up_ini from
            (select  a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.tgl_bukti>='$ntgl1' and b.tgl_bukti<='$ntgl2' and jns_spp in (1,2,3) 
            union all
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, isnull(a.nilai*-1,0) as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.TGL_BUKTI>='$ntgl1' and b.TGL_BUKTI<='$ntgl2' and b.pengurang_belanja=1
            union all
            select a.kd_skpd, isnull(a.nilai,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.tgl_bukti>='$ntgl1' and b.tgl_bukti<='$ntgl2' and jns_spp in (4)
            union all
            select  a.kd_skpd, isnull(a.rupiah*-1,0) as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
            where b.tgl_sts>='$ntgl1' and b.tgl_sts<='$ntgl2' and b.jns_cp in (1) and b.pot_khusus<>0
            union all
            select a.kd_skpd, 0 as gaji_ini, isnull(a.nilai,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.tgl_bukti>='$ntgl1' and b.tgl_bukti<='$ntgl2' and jns_spp in (6)
            union all
            select  a.kd_skpd, 0 as gaji_ini, isnull(a.rupiah*-1,0) as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
            where b.tgl_sts>='$ntgl1' and b.tgl_sts<='$ntgl2' and b.jns_cp in (2) and b.pot_khusus<>0
            union all
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai,0) as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.tgl_bukti<'$ntgl1' and jns_spp in (1,2,3)
            union all
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, 0 as brg_lalu, isnull(a.nilai*-1,0) as up_lalu from trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.TGL_BUKTI<'$ntgl2' and b.pengurang_belanja=1
            union all
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.nilai,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.tgl_bukti<'$ntgl1' and jns_spp in (4)
            union all
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, isnull(a.rupiah*-1,0) as gaji_lalu, 0 as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
            where b.tgl_sts<'$ntgl1' and b.jns_cp in (1) and b.pot_khusus<>0
            union all
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.nilai,0) as brg_lalu, 0 as up_lalu from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
            where b.tgl_bukti<'$ntgl1' and jns_spp in (6)
            union all
            select a.kd_skpd, 0 as gaji_ini, 0 as brg_ini, 0 as up_ini, 0 as gaji_lalu, isnull(a.rupiah*-1,0) as brg_lalu, 0 as up_lalu from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
            where b.tgl_sts<'$ntgl1' and b.jns_cp in (2) and b.pot_khusus<>0) a 
            WHERE a.kd_skpd='$lcskpd' ";


        $hasil = $this->db->query($csql);
        $trh7 = $hasil->row();
        $totalspj = $trh7->spj_gaji_ll + $trh7->spj_gaji_ini + $trh7->spj_brjs_ll +
            $trh7->spj_brjs_ini + $trh7->spj_up_ll + $trh7->spj_up_ini;

        $cRet .= "
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- SPJ(LS + UP/GU/TU)</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_gaji_ini + $trh7->spj_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_brjs_ini + $trh7->spj_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh7->spj_up_ini + $trh7->spj_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalspj, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;- Penyetoran Pajak</td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210106010001'; //'2110401'; // ppn setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jppn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jppn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jppn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jppn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jppn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh8 = $hasil->row();
        $totalppn = $trh8->jppn_up_ini + $trh8->jppn_up_ll + $trh8->jppn_gaji_ini +
            $trh8->jppn_gaji_ll + $trh8->jppn_brjs_ini + $trh8->jppn_brjs_ll;

        $cRet .= "
        <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;a. PPN Pusat</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_gaji_ll + $trh8->jppn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_brjs_ll + $trh8->jppn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh8->jppn_up_ll + $trh8->jppn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppn, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210105010001'; // pph 21 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jpph21_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jpph21_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jpph21_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jpph21_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jpph21_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh9 = $hasil->row();
        $totalpph21 = $trh9->jpph21_up_ini + $trh9->jpph21_up_ll + $trh9->jpph21_gaji_ini +
            $trh9->jpph21_gaji_ll + $trh9->jpph21_brjs_ini + $trh9->jpph21_brjs_ll;


        $cRet .= "
         <tr> <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;b. PPH 21</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_gaji_ll + $trh9->jpph21_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_brjs_ll + $trh9->jpph21_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh9->jpph21_up_ll + $trh9->jpph21_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph21, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105020001'; // pph 22 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jpph22_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jpph22_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jpph22_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jpph22_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jpph22_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh10 = $hasil->row();
        $totalpph22 = $trh10->jpph22_up_ini + $trh10->jpph22_up_ll + $trh10->jpph22_gaji_ini +
            $trh10->jpph22_gaji_ll + $trh10->jpph22_brjs_ini + $trh10->jpph22_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;c. PPH 22</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_gaji_ll + $trh10->jpph22_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_brjs_ll + $trh10->jpph22_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh10->jpph22_up_ll + $trh10->jpph22_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph22, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210105030001'; // pph 23 setor
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS jpph23_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS jpph23_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS jpph23_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS jpph23_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS jpph23_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh11 = $hasil->row();
        $totalpph23 = $trh11->jpph23_up_ini + $trh11->jpph23_up_ll + $trh11->jpph23_gaji_ini +
            $trh11->jpph23_gaji_ll + $trh11->jpph23_brjs_ini + $trh11->jpph23_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;&ensp;&ensp;d. PPH 23</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_gaji_ll + $trh11->jpph23_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_brjs_ll + $trh11->jpph23_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh11->jpph23_up_ll + $trh11->jpph23_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph23, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210108010001'; // IWP
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS up_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS gj_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_iwp_sdini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS ls_iwp_lalu,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_iwp_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_iwp_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh73 = $hasil->row();
        $totaliwp_setor = $trh73->up_iwp_sdini + $trh73->gj_iwp_sdini + $trh73->ls_iwp_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. IWP</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->gj_iwp_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->ls_iwp_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh73->up_iwp_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totaliwp_setor, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";

        $lcrek = '210107010001'; // TAPERUM
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS up_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS gj_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_tap_sdini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS ls_tap_lalu,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_tap_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_tap_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh74 = $hasil->row();
        $totaltap_setor = $trh74->up_tap_sdini + $trh74->gj_tap_sdini + $trh74->ls_tap_sdini;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Taperum</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->gj_tap_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->ls_tap_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh74->up_tap_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totaltap_setor, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        $lcrek = '210601050005'; // pph4
        $csql = "SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS up_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS up_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS gj_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS gj_pph4_sdini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS ls_pph4_lalu,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_pph4_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS ls_pph4_sdini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_rek6='$lcrek' AND a.kd_skpd='$lcskpd'";

        $hasil = $this->db->query($csql);
        $trh75 = $hasil->row();
        $totalpph4_setor = $trh75->up_pph4_sdini + $trh75->gj_pph4_sdini + $trh75->ls_pph4_sdini;


        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. PPh Pasal 4</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->gj_pph4_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->ls_pph4_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh75->up_pph4_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalpph4_setor, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";






        $lcrek = '210102010001'; // PPnpn
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp IN('1','2','3')) AS ppnpn_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='4') AS ppnpn_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' AND 
                a.jns_spp ='6') AS ppnpn_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek6 = '$lcrek' AND a.tgl_bukti<'$ntgl1' AND 
                a.jns_spp ='6') AS ppnpn_brjs_ll";

        $hasil = $this->db->query($csql);
        $trh16 = $hasil->row();
        $totalppnpn = $trh16->ppnpn_up_ini + $trh16->ppnpn_up_ll + $trh16->ppnpn_gaji_ini +
            $trh16->ppnpn_gaji_ll + $trh16->ppnpn_brjs_ini + $trh16->ppnpn_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Jaminan Kesehatan</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_gaji_ll + $trh16->ppnpn_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_brjs_ll + $trh16->ppnpn_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh16->ppnpn_up_ll + $trh16->ppnpn_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalppnpn, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";
        // Potongan Penghasilan Lainnya
        $csql = "SELECT 
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and tgl_sts<'$ntgl1'  then a.rupiah else 0 end),0)) AS up_lain_lalu,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2'  then a.rupiah else 0 end),0)) AS up_lain_ini,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '3' and tgl_sts<='$ntgl2'  then a.rupiah else 0 end),0)) AS up_lain_sdini,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and tgl_sts<'$ntgl1'  then a.rupiah else 0 end),0)) AS ls_lain_lalu,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2'  then a.rupiah else 0 end),0)) AS ls_lain_ini,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '2' and tgl_sts<='$ntgl2'  then a.rupiah else 0 end),0)) AS ls_lain_sdini,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and tgl_sts<'$ntgl1'  then a.rupiah else 0 end),0)) AS gj_lain_lalu,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2'  then a.rupiah else 0 end),0)) AS gj_lain_ini,
         SUM(isnull((case when pot_khusus='2' AND rtrim(jns_cp)= '1' and tgl_sts<='$ntgl2'  then a.rupiah else 0 end),0)) AS gj_lain_sdini
         FROM trdkasin_pkd a 
         INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
         WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        $hasil = $this->db->query($csql);
        $trhxy = $hasil->row();
        $totallain = $trhxy->up_lain_sdini + $trhxy->gj_lain_sdini + $trhxy->ls_lain_sdini;

        $cRet .= "
      <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
         <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Pot. Penghasilan Lainnya</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_lalu, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_ini, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->gj_lain_sdini, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_lalu, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_ini, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->ls_lain_sdini, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_lalu, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_ini, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($trhxy->up_lain_sdini, "2", ",", ".") . "</td>
         <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "</td>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
     </tr>";

        // HKPG
        $csql = "SELECT 
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and tgl_sts<'$ntgl1' then a.rupiah else 0 end),0)) AS up_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2' then a.rupiah else 0 end),0)) AS up_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '3' and tgl_sts<='$ntgl2' then a.rupiah else 0 end),0)) AS up_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and tgl_sts<'$ntgl1' then a.rupiah else 0 end),0)) AS ls_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2' then a.rupiah else 0 end),0)) AS ls_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '2' and tgl_sts<='$ntgl2' then a.rupiah else 0 end),0)) AS ls_hkpg_sdini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and tgl_sts<'$ntgl1' then a.rupiah else 0 end),0)) AS gj_hkpg_lalu,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2' then a.rupiah else 0 end),0)) AS gj_hkpg_ini,
            SUM(isnull((case when pot_khusus='1' AND rtrim(jns_cp)= '1' and tgl_sts<='$ntgl2' then a.rupiah else 0 end),0)) AS gj_hkpg_sdini
            FROM trdkasin_pkd a 
            INNER JOIN trhkasin_pkd b on a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd
            WHERE a.kd_skpd ='$lcskpd' AND jns_trans='5'";

        $hasil = $this->db->query($csql);
        $trhxx = $hasil->row();
        $totalhkpg = $trhxx->up_hkpg_sdini + $trhxx->gj_hkpg_sdini + $trhxx->ls_hkpg_sdini;

        $cRet .= "
         <tr><td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- HKPG</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->gj_hkpg_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->gj_hkpg_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->gj_hkpg_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->ls_hkpg_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->ls_hkpg_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->ls_hkpg_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->up_hkpg_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->up_hkpg_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhxx->up_hkpg_sdini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totalhkpg, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";



        // CONTRA POS
        $csql = "SELECT 
        SUM(isnull((case when rtrim(jns_cp)= '3' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2' then z.nilai else 0 end),0)) AS cp_spj_up_ini,
        SUM(isnull((case when rtrim(jns_cp)= '3' and tgl_sts<'$ntgl1' then z.nilai else 0 end),0)) AS cp_spj_up_ll,
        SUM(isnull((case when rtrim(jns_cp)= '1' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2' then z.nilai else 0 end),0)) AS cp_spj_gaji_ini,
        SUM(isnull((case when rtrim(jns_cp)= '1' and tgl_sts<'$ntgl1' then z.nilai else 0 end),0)) AS cp_spj_gaji_ll,
        SUM(isnull((case when rtrim(jns_cp)= '2' and tgl_sts>='$ntgl1' AND tgl_sts<='$ntgl2' then z.nilai else 0 end),0)) AS cp_spj_brjs_ini,
        SUM(isnull((case when rtrim(jns_cp)= '2' and tgl_sts<'$ntgl1' then z.nilai else 0 end),0)) AS cp_spj_brjs_ll
        from (select rupiah as nilai,jns_trans,pot_khusus,jns_cp,d.tgl_sts ,d.kd_skpd from 
        trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd where d.kd_skpd ='$lcskpd' AND 
        ((jns_trans='5' AND pot_khusus='0') OR jns_trans='1')) z";

        $hasil = $this->db->query($csql);
        $trh_x = $hasil->row();
        $total_cp = $trh_x->cp_spj_up_ini + $trh_x->cp_spj_up_ll + $trh_x->cp_spj_gaji_ini +
            $trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_brjs_ini + $trh_x->cp_spj_brjs_ll;


        $cRet .= "
         <tr>
         <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Contra Pos</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_gaji_ll + $trh_x->cp_spj_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_brjs_ll + $trh_x->cp_spj_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh_x->cp_spj_up_ll + $trh_x->cp_spj_up_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($total_cp, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>";


        // lain lain setoran
        $csql = "SELECT 
                SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                SUM(ISNULL(jlain_gaji_ll,0)) jlain_gaji_ll, SUM(ISNULL(jlain_gaji_ini,0)) jlain_gaji_ini, 
                SUM(ISNULL(jlain_brjs_ll,0)) jlain_brjs_ll, SUM(ISNULL(jlain_brjs_ini,0)) jlain_brjs_ini
                 FROM(
                SELECT 
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti<'$ntgl1' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN b.jns_spp IN ('1','2','3') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti<'$ntgl1' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN b.jns_spp IN ('4') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti<'$ntgl1' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN b.jns_spp IN ('6') AND b.tgl_bukti>='$ntgl1' AND b.tgl_bukti<='$ntgl2' AND a.kd_rek6 NOT IN ('210105010001','210105030001','210108010001','210601050005','210107010001','210105020001','210106010001') THEN  ISNULL(a.nilai,0) ELSE 0 END) AS jlain_brjs_ini
                FROM trdstrpot a INNER JOIN trhstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                WHERE a.kd_skpd='$lcskpd'
                UNION ALL
                SELECT 
                SUM(CASE WHEN a.jns_beban='1' AND a.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
                SUM(CASE WHEN a.jns_beban='1' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
                SUM(CASE WHEN a.jns_beban='4' AND a.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ll,
                SUM(CASE WHEN a.jns_beban='4' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS jlain_gaji_ini,
                SUM(CASE WHEN a.jns_beban='6' AND a.tgl_bukti<'$ntgl1' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ll,
                SUM(CASE WHEN a.jns_beban='6' AND a.tgl_bukti>='$ntgl1' AND a.tgl_bukti<='$ntgl2' THEN  a.nilai ELSE 0 END) AS jlain_brjs_ini
                FROM TRHOUTLAIN a 
                WHERE a.kd_skpd='$lcskpd'
                ) a ";
        $hasil = $this->db->query($csql);
        $trh12 = $hasil->row();
        $totallain = $trh12->jlain_up_ini + $trh12->jlain_up_ll + $trh12->jlain_gaji_ini +
            $trh12->jlain_gaji_ll + $trh12->jlain_brjs_ini + $trh12->jlain_brjs_ll;

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '2017-01-01'>='$ntgl1' and '2017-01-31'<='$ntgl2'";
        $hasil = $this->db->query($tox_awal);
        $tox_ini = $hasil->row('jumlah');
        //          echo  $tox_ini;            
        $tox_ini = (empty($tox_ini) ? 0 : $tox_ini);

        $tox_awal = "SELECT SUM(isnull(sld_awal,0)+ isnull(sld_awal_bank,0)) AS jumlah FROM ms_skpd where kd_skpd='$lcskpd' and '2017-01-31'<'$ntgl1'";
        $hasil = $this->db->query($tox_awal);
        $tox_ll = $hasil->row('jumlah');
        $tox_ll = (empty($tox_ll) ? 0 : $tox_ll);

        //Dropping Dana


        $sqldropout = "SELECT SUM(z.bln_lalu) bln_lalu, SUM(z.bln_ini) bln_ini, SUM(z.sd_bln_ini) sd_bln_ini from(
                    select 
                    SUM(CASE WHEN tgl_kas <'$ntgl1' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN (tgl_kas between '$ntgl1' and '$ntgl2') THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN tgl_kas <='$ntgl2' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan_bank
                    WHERE kd_skpd_sumber='$lcskpd'
                    UNION ALL
                    select 
                    SUM(CASE WHEN tgl_kas <'$ntgl1' THEN nilai ELSE 0 END) as bln_lalu,
                    SUM(CASE WHEN (tgl_kas between '$ntgl1' and '$ntgl2') THEN nilai ELSE 0 END) as bln_ini,
                    SUM(CASE WHEN tgl_kas <='$ntgl2' THEN nilai ELSE 0 END) as sd_bln_ini
                    from tr_setorpelimpahan
                    WHERE kd_skpd_sumber='$lcskpd'
                    )z";
        $hasil = $this->db->query($sqldropout);
        $trhdropout = $hasil->row();
        $totaldropout = $trhdropout->bln_lalu + $trhdropout->bln_ini + $trhdropout->sd_bln_ini;


        //Panjar Dana

        $sqlpanjarout = "SELECT 
                    SUM(CASE WHEN tgl_kas<'$ntgl1' THEN nilai ELSE 0 END) as jar_bln_lalu,
                    SUM(CASE WHEN tgl_kas>='$ntgl1' and tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as jar_bln_ini,
                    SUM(CASE WHEN tgl_kas<='$ntgl2' THEN nilai ELSE 0 END) as jar_sd_bln_ini
                    from tr_panjar
                    WHERE kd_skpd='$lcskpd'";
        $hasil = $this->db->query($sqlpanjarout);
        $trhpanjarout = $hasil->row();
        $totalpanjarout = $trhpanjarout->jar_bln_lalu + $trhpanjarout->jar_bln_ini + $trhpanjarout->jar_sd_bln_ini;


        $jmsetgaji_ll =  $trh7->spj_gaji_ll + $trh8->jppn_gaji_ll + $trh9->jpph21_gaji_ll + $trh16->ppnpn_gaji_ll +
            $trh10->jpph22_gaji_ll + $trh11->jpph23_gaji_ll + $trh12->jlain_gaji_ll + $trh_x->cp_spj_gaji_ll +
            $trh73->gj_iwp_lalu + $trh74->gj_tap_lalu + $trh75->gj_pph4_lalu + $trhxx->gj_hkpg_lalu + $trhxy->gj_lain_lalu;

        $jmsetgaji_ini = $trh7->spj_gaji_ini + $trh8->jppn_gaji_ini + $trh9->jpph21_gaji_ini + $trh16->ppnpn_gaji_ini +
            $trh10->jpph22_gaji_ini + $trh11->jpph23_gaji_ini + $trh12->jlain_gaji_ini + $trh_x->cp_spj_gaji_ini +
            $trh73->gj_iwp_ini + $trh74->gj_tap_ini + $trh75->gj_pph4_ini + $trhxx->gj_hkpg_ini + $trhxy->gj_lain_ini;

        $jmsetgaji_sd = $jmsetgaji_ll + $jmsetgaji_ini;


        $jmsetbrjs_ll =  $trh7->spj_brjs_ll + $trh8->jppn_brjs_ll + $trh9->jpph21_brjs_ll + $trh16->ppnpn_brjs_ll +
            $trh10->jpph22_brjs_ll + $trh11->jpph23_brjs_ll + $trh12->jlain_brjs_ll + $trh_x->cp_spj_brjs_ll +
            $trh73->ls_iwp_lalu + $trh74->ls_tap_lalu + $trh75->ls_pph4_lalu + $trhxx->ls_hkpg_lalu + $trhxy->ls_lain_lalu;

        $jmsetbrjs_ini =  $trh7->spj_brjs_ini + $trh8->jppn_brjs_ini + $trh9->jpph21_brjs_ini + $trh16->ppnpn_brjs_ini +
            $trh10->jpph22_brjs_ini + $trh11->jpph23_brjs_ini + $trh12->jlain_brjs_ini + $trh_x->cp_spj_brjs_ini +
            $trh73->ls_iwp_ini + $trh74->ls_tap_ini + $trh75->ls_pph4_ini + $trhxx->ls_hkpg_ini + $trhxy->ls_lain_ini;

        $jmsetbrjs_sd = $jmsetbrjs_ll + $jmsetbrjs_ini;
        /* 
        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll +
                         $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll; */

        $jmsetup_ll =  $trh7->spj_up_ll + $trh8->jppn_up_ll + $trh9->jpph21_up_ll + $trh16->ppnpn_up_ll +
            $trh10->jpph22_up_ll + $trh11->jpph23_up_ll + $trh12->jlain_up_ll + $tox_ll + $trh_x->cp_spj_up_ll +
            $trh73->up_iwp_lalu + $trh74->up_tap_lalu + $trh75->up_pph4_lalu + $trhxx->up_hkpg_lalu + $trhxy->up_lain_lalu +
            $trhdropout->bln_lalu + $trhpanjarout->jar_bln_lalu;

        $jmsetup_ini =  $trh7->spj_up_ini + $trh8->jppn_up_ini + $trh9->jpph21_up_ini + $trh16->ppnpn_up_ini +
            $trh10->jpph22_up_ini + $trh11->jpph23_up_ini + $trh12->jlain_up_ini + $tox_ini + $trh_x->cp_spj_up_ini +
            $trh73->up_iwp_ini + $trh74->up_tap_ini + $trh75->up_pph4_ini + $trhxx->up_hkpg_ini + $trhxy->up_lain_ini +
            $trhdropout->bln_ini + $trhpanjarout->jar_bln_ini;

        $jmsetup_sd = $jmsetup_ll + $jmsetup_ini;


        $cRet .= "
                   
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Lain-lain</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_gaji_ll + $trh12->jlain_gaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_brjs_ll + $trh12->jlain_brjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll + $tox_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ini + $tox_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trh12->jlain_up_ll + $trh12->jlain_up_ini + $tox_ll + $tox_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($totallain, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        
        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Dropping Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhdropout->sd_bln_ini, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>

        <tr>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>&ensp;&ensp;- Panjar Dana</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format(0, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_lalu, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($trhpanjarout->jar_sd_bln_ini, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        
        ";

        $cRet .= "
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Jumlah Pengeluaran :</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetbrjs_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetup_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmsetgaji_sd + $jmsetbrjs_sd + $jmsetup_sd, "2", ",", ".") . "</td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr> 
                    
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='left' style='font-size:12px' colspan='2'>Saldo Kas</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ll - $jmsetgaji_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_ini - $jmsetgaji_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd - $jmsetgaji_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ll - $jmsetbrjs_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_ini - $jmsetbrjs_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmbrjs_sd - $jmsetbrjs_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ll - $jmsetup_ll, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_ini - $jmsetup_ini, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmup_sd - $jmsetup_sd, "2", ",", ".") . "</td>
            <td align='right' style='font-size:12px'>" . number_format($jmtrmgaji_sd + $jmtrmbrjs_sd + $jmtrmup_sd - $jmsetgaji_sd - $jmsetbrjs_sd - $jmsetup_sd, "2", ",", ".") . "</td>
       <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
       </tr>
        <tr>
        <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
            <td align='center' style='font-size:12px' colspan='2'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='center' style='font-size:12px'></td>
            <td align='left' style='font-size:12px;border-top:hidden;'>&ensp;&ensp;</td>
        </tr>
        </table>";
        if ($jenis == '1') {


            $cRet .= '<TABLE width="100%" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b></TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b></TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        } else if ($jenis == '2') {

            $cRet .= '<TABLE width="100%" border="1" style="font-size:12px">
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" >Mengetahui,</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" >Melawi ' . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $jabatan . '</TD>
                    <TD align="center" ><b></TD>
                    <TD align="center" >' . $jabatan1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                   <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b>&nbsp;</TD>
                    <TD align="center" ><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" ><b><u>' . $nama2 . '</u></b> <br> ' . $pangkat . ' </TD>
                    <TD align="center" ><b></TD>
                    <TD align="center" ><b><u>' . $nama1 . '</u></b><br> ' . $pangkat1 . '</TD>
                </TR>
                <TR>
                    <TD align="center" >' . $nip . '</TD>
                    <TD align="center" ><b></TD>
                    <TD align="center" >' . $nip1 . '</TD>
                </TR>
                </TABLE><br/>';
        }
        $cRet .= "</table>";
        $data['prev'] = $cRet;
        if ($ctk == 0) {
            echo "<title>  SPJ " . $this->tukd_model->tanggal_format_indonesia($ntgl1) . " s/d " . $this->tukd_model->tanggal_format_indonesia($ntgl2) . " </title>";
            echo $cRet;
        } else if ($ctk == 1) {
            //$this->master_pdf->_mpdf_down('SPJ',$bulan,$cRet,10,10,10,'1');
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, 'L', 0, '', $atas, $bawah, $kiri, $kanan);
            echo $cRet;
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= SPJ Periode $ntgl1-$ntgl2.xls");
            echo $cRet;
        }
    }
}
