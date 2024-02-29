<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class BukuRincianController extends CI_Controller
{
    public $org_keu = "";
    public $skpd_keu = "";

    // public $ppkd1 = "4.02.02.01";
    // public $ppkd2 = "4.02.02.02";

    public function __contruct()
    {
        parent::__construct();
    }

    function index(){
        $data['page_title']= 'BUKU RINCIAN KARTU KENDALI';

        $this->template->set('title', 'BUKU RINCIAN KARTU KENDALI');   
        $this->template->load('template','bukurincian/bukurincian',$data) ;
    }


    function subkegiatan(){
       $kd_skpd = $this->session->userdata('kdskpd');
      // $skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $sql = $this->db->query("SELECT kd_sub_kegiatan, nm_sub_kegiatan FROM trdtransout WHERE kd_skpd='$kd_skpd' GROUP BY kd_sub_kegiatan, nm_sub_kegiatan");
        $data = $sql->result();  
       echo json_encode($data);
    }

    function subkoderekbelanja(){
        $kd_skpd = $this->session->userdata('kdskpd');
       // $skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $kd_sub_kegiatan = $this->input->post('kd_sub_kegiatan');
        $sql = $this->db->query("SELECT kd_rek6, nm_rek6 FROM trdtransout WHERE kd_skpd='$kd_skpd' AND kd_sub_kegiatan='$kd_sub_kegiatan' GROUP BY kd_rek6, nm_rek6");
        $data = $sql->result();  
        echo json_encode($data);
    }

    function Cetaklaporan($kd_skpd = '', $kd_rek6= '', $jns_ang='', $kd_sub_kegiatan='', $pptk='', $pengguna='', $periode1='', $periode2='' ){

        $kd_skpd= $this->input->get('skpd');
        $nm_skpd = $this->input->get('nmskpd');
        //$skpd = $this->tukd_model->get_nama($lcskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $jnscetak = $this->input->get('jnscetak');
        $kd_sub_kegiatan = $this->input->get('kd_sub_kegiatan');
        $kd_rek6 = $this->input->get('kd_rek6');
        $nm_rek6= $this->input->get('nm_rek6');
        $jns_ang = $this->input->get('jnsang');
        $pptk = $this->input->get('pptk');
        $pengguna = $this->input->get('pengguna');
        $periode1 = $this->input->get('tgl1');
        $periode2 = $this->input->get('tgl2');

        $sql= $this->db->query("SELECT x.jns_ang, x.urut,x.tgl_bukti, x.kd_skpd,x.no_sp2d,x.kd_sub_kegiatan,x.nm_sub_kegiatan,x.kd_rek6,x.nm_rek6,x.keterangan,x.anggaran ,x.realisasi FROM(
        SELECT '1' as urut,'' as jns_ang, a.kd_skpd, b.tgl_bukti, a.no_bukti,a.no_sp2d, a.kd_sub_kegiatan, a.nm_sub_kegiatan, '' kd_rek6, '' nm_rek6,  0 as anggaran,SUM(a.nilai) as realisasi, b.ket as keterangan FROM trdtransout a INNER JOIN trhtransout b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti WHERE b.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND a.kd_skpd='$kd_skpd' AND a.kd_sub_kegiatan='$kd_sub_kegiatan' AND a.kd_rek6='$kd_rek6' GROUP BY b.tgl_bukti, a.no_bukti,a.no_sp2d, a.kd_sub_kegiatan, a.nm_sub_kegiatan,a.kd_skpd,b.ket
        UNION ALL
        SELECT '2' as urut,b.jns_ang,b.kd_skpd,''as tgl_bukti,'' as no_bukti, c.no_sp2d, b.kd_sub_kegiatan, b.nm_sub_kegiatan, b.kd_rek6, b.nm_rek6, b.anggaran, c.realisasi, '' as keterangan FROM (
        SELECT a.jns_ang, a.kd_skpd, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6, a.nm_rek6, a.nilai as anggaran FROM trdrka a INNER JOIN trhrka b ON b.kd_skpd=a.kd_skpd AND b.jns_ang=a.jns_ang ) b LEFT JOIN (
        SELECT a.kd_skpd, b.tgl_bukti, a.no_bukti,a.no_sp2d, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_rek6 as kd_rek6, a.nm_rek6 as nm_rek6,a.nilai as realisasi, b.ket as keterangan FROM trdtransout a INNER JOIN trhtransout b ON b.kd_skpd=a.kd_skpd AND b.no_bukti=a.no_bukti) c ON c.kd_skpd=b.kd_skpd AND c.kd_sub_kegiatan=b.kd_sub_kegiatan AND c.kd_rek6=b.kd_rek6 WHERE b.jns_ang='$jns_ang' AND c.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_skpd='$kd_skpd' AND b.kd_sub_kegiatan='$kd_sub_kegiatan' AND b.kd_rek6='$kd_rek6') x ORDER BY x.no_sp2d,x.urut,x.kd_skpd ASC");

    $cRet = '<TABLE style="border-collapse:collapse;font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        <TR>
            <TD align="center" ><b> </TD>
        </TR>
        <TR>
            <TD align="center" ><b>RINCIAN KARTU KENDALI </b></TD>
        </TR>
    </TABLE><br />';

    $cRet .= '<TABLE style="border-collapse:collapse;font-size:12px" width="90%" border="0" cellspacing="0" cellpadding="0" align=center>
    <TR>
        <TD align="left" width="15%"><b>OPD</b> </TD>
        <TD align="left" width="2%"><b>:</b> </TD>
        <TD align="left" width="83%"><b>'.$kd_skpd.' - '.$nm_skpd.'</b> </TD>
    </TR>
    <TR>
        <TD align="left" width="15%"><b>NAMA SUB KEGIATAN</b> </TD>
        <TD align="left" width="2%"><b>:</b> </TD>
        <TD align="left" width="83%"><b></b> </TD>
    </TR>
    <TR>
        <TD align="left" width="10%"><b>NAMA PPTK</b> </TD>
        <TD align="left" width="2%"><b>:</b> </TD>
        <TD align="left" width="83%"><b></b> </TD>
    </TR>
    </TABLE> <p/><br />';
    $cRet .= "<table style=\"border-collapse:collapse; font-size:12px\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"\">
    <thead>
    <tr>
    <td rowspan =\"1\" align=\"center\" bgcolor=\"#CCCCCC\"><b>NO URUT</b></td>
    <td rowspan =\"3\" align=\"center\" bgcolor=\"#CCCCCC\"><b>SP2D</b></td>
    <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>KODE REKENING</b></td>
    <td colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>NAMA REKENING</b></td>
    <td colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>KETERANGAN</b></td>
    <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>ANGGARAN</b></td>
    <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>REALISASI</b></td>
    <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\"><b>SISA PAGU</b></td>
    </tr>
    </thead>";

    $cRet .= "
    <tr>
    <td align=\"center\" ></td>
    <td align=\"left\" ></td>
    <td align=\"left\" >$kd_rek6</td>
    <td colspan=\"2\" align=\"right\" >$nm_rek6</td>
    <td colspan=\"2\" align=\"right\" ></td>
    <td align=\"right\" ></td>
    <td align=\"right\" ></td>
    <td align=\"right\" ></td>
    
    </tr>
    ";

    
    $cRet .= "
        <tr>
        <td colspan=\"7\" align=\"center\" >TOTAL</td>
        <td align=\"right\" ></td>
        <td align=\"right\" ></td>
        <td align=\"right\" ></td>
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
                <TD align="center" ></TD>
            </TR>
            <TR>
                <TD align="center" ></TD>
                <TD align="center" ><b>&nbsp;</TD>
                <TD align="center" ></TD>
            </TR>
            <TR>
                <TD align="center" ><b>&nbsp;</TD>
            </TR>
            <TR>
                <TD align="center" ><b>&nbsp;</TD>
            </TR>
            <TR>
                <TD align="center" ><u><b> </b><br></u> </TD>
                <TD align="center" ><b>&nbsp;</TD>
                <TD align="center" ><u><b> </b><br></u></TD>
            </TR>
            <TR>
                <TD align="center" ></TD>
                <TD align="center" ><b>&nbsp;</TD>
                <TD align="center" ></TD>
            </TR>
            </TABLE><br/>';

    echo $cRet;
    }
    

}
