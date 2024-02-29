<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
1. tanggal_format_indonesia(a)
2. getBulan(a)
3. dotrek(a)
*/

class support extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function  tanggal_format_indonesia($tgl)
    {

        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
    }
    function rp_minus($nilai)
    {
        if ($nilai < 0) {
            $nilai = $nilai * (-1);
            $nilai = '(' . number_format($nilai, "2", ",", ".") . ')';
        } else {
            $nilai = number_format($nilai, "2", ",", ".");
        }

        return $nilai;
    }

    function format_bulat($nilai)
    {
        if ($nilai < 0) {
            $nilai = $nilai * (-1);
            $nilai = '(' . number_format($nilai, "0", ",", ".") . ')';
        } else {
            $nilai = number_format($nilai, "0", ",", ".");
        }

        return $nilai;
    }

    function nvl($val, $replace)
    {
        if (is_null($val) || $val === '')
            return $replace;
        else
            return $val;
    }

    function get_tgldpa(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT 
                tgl_dpa
                from trhrka a where kd_skpd ='$skpd' AND tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
        $query1 = $this->db->query($sql);  
        $ii = 0;

        foreach($query1->result() as $resulte)
        { 

            $tgldpa = $resulte->tgl_dpa; 
        }
        return $tgldpa; 
    }

    function get_nodpa(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT 
                no_dpa
                from trhrka a where kd_skpd ='$skpd' AND tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
        $query1 = $this->db->query($sql);  
        $ii = 0;

        foreach($query1->result() as $resulte)
        { 

            $nodpa = $resulte->no_dpa; 
        }
        return $nodpa; 
    }

    function  getBulan($bln)
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
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2);
                break;
            case 11:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 12);;
                break;
            case 12:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 4);;
                break;
            default:
                $rek = "";
        }
        return $rek;
    }

    function auto_cek_status($skpd)
    {
        $tgl_spp = $this->input->post('tgl_cek');
        $sql = "SELECT top 1 
                case 
                when statu=1 and status_sempurna=1 and status_ubah=1  then 'ubah'
                when statu=1 and status_sempurna=1 and status_ubah=0  then 'geser' 
                when statu=1 and status_sempurna=0 and status_ubah=0  then 'murni' 
                when statu=1 and status_sempurna=0 and status_ubah=1  then 'murni'
                else 'murni' end as anggaran from trhrka where left(kd_skpd,17) =left('$skpd',17)";
        //  echo "$sql";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result() as $resulte) {
            $status_ang = $resulte->anggaran;
        }
        return $status_ang;
    }

    function sort($id = '', $tbl = '')
    {
        if ($tbl == '') {
            $tabel = '';
        } else {
            $tabel = "$tbl" . ".";
        }
        return $sort = substr($id, 0, 4) == '1.02' || substr($id, 0, 4) == '7.01' ? "{$tabel}kd_skpd='$id'" : "left({$tabel}kd_skpd,17)=left('$id',17)";
    }

    function _mpdf_down($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '', $name = '')
    {


        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');


        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = 'BI';   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6; /* in pts */
        $this->mpdf->defaultfooterfontstyle = 'BI';   /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $sa = 1;
        $tes = 0;
        if ($hal == '') {
            $hal1 = 1;
        }
        if ($hal !== '') {
            $hal1 = $hal;
        }
        if ($fonsize == '') {
            $size = 12;
        } else {
            $size = $fonsize;
        }

        $this->mpdf = new mPDF('utf-8', array(215, 330), $size); //folio
        $this->mpdf->AddPage($orientasi, '', $hal1, '1', 'off');
        $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output($name, 'D');
    }


    function _mpdf1($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '')
    {


        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');


        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $sa = 1;
        $tes = 0;
        if ($hal == '') {
            $hal1 = 1;
        }
        if ($hal !== '') {
            $hal1 = $hal;
        }
        if ($fonsize == '') {
            $size = 12;
        } else {
            $size = $fonsize;
        }

        $this->mpdf = new mPDF('utf-8', array(215, 330), $size); //folio
        //$this->mpdf->useOddEven = 1;                      

        $this->mpdf->AddPage($orientasi, '', $hal, '1', 'off');
        if ($hal == '') {
            $this->mpdf->SetFooter("");
        } else {
            $this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }

    function _mpdf($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '')
    {


        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');

        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $sa = 1;
        $tes = 0;
        if ($hal == '') {
            $hal1 = 1;
        }
        if ($hal !== '') {
            $hal1 = $hal;
        }
        if ($fonsize == '') {
            $size = 12;
        } else {
            $size = $fonsize;
        }

        $this->mpdf = new mPDF('utf-8', array(215, 330), $size); //folio                

        $this->mpdf->AddPage($orientasi, '', $hal, '1', 'off');
        if ($hal == '') {
            $this->mpdf->SetFooter("");
        } else {
            $this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML(mb_convert_encoding($judul, 'utf-8', 'utf-8'));
        $this->mpdf->writeHTML(utf8_encode($isi));
        $this->mpdf->Output();
    }


    function _mpdf_margin($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '', $atas = '', $bawah = '', $kiri = '', $kanan = '')
    {

        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');
        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $sa = 1;
        $tes = 0;
        if ($hal == '') {
            $hal1 = 1;
        }
        if ($hal !== '') {
            $hal1 = $hal;
        }
        if ($fonsize == '') {
            $size = 12;
        } else {
            $size = $fonsize;
        }

        $this->mpdf = new mPDF('utf-8', array(215, 330), $size); //folio
        $this->mpdf->AddPage($orientasi, '', $hal, '1', 'off', $kiri, $kanan, $atas, $bawah);
        if ($hal == '') {
            $this->mpdf->SetFooter("");
        } else {
            $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }

    function get_nama2($kode,$hasil,$tabel,$field,$field2,$kode2)
	{
        $this->db->select($hasil);
		$this->db->where($field, $kode);
		$this->db->where($field2, $kode2);
		$q = $this->db->get($tabel);
		$data  = $q->result_array();
		$baris = $q->num_rows();
		return $data[0][$hasil];
	}

    function cek_status_ang_new(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT 
               nilai as anggaran 
                from trhrka where kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);  
        $ii = 0;

        foreach($query1->result() as $resulte)
        { 

            $field = $resulte->anggaran; 
        }
        return $field; 
    }

    function get_nama($kode,$hasil,$tabel,$field)
    {
        $this->db->select($hasil);
        $this->db->where($field, $kode);
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data[0][$hasil];
    }

    function get_sclient()
    {
        $this->db->select('top 1 daerah, kab_kota');
        $this->db->from('sclient');
        $sql = $this->db->get();
        return $sql->row();
    }
}
