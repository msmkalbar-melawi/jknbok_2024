<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */
class  custom
{
    public function __construct()
    {
        // Assign the CodeIgniter super-object
        //$this->CI =& get_instance();
    }

    function  tanggal_format_indonesia($tgl)
    {
        setlocale(LC_ALL, 'Indonesian');
        return strftime('%d %B %Y', strtotime($tgl));
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

    function  tanggal_indonesia($tgl)
    {
        $tanggal  =  substr($tgl, 8, 2);
        $bulan  = substr($tgl, 5, 2);
        $tahun  =  substr($tgl, 0, 4);
        return  $tanggal . '-' . $bulan . '-' . $tahun;
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

    function left($string, $count)
    {
        return substr($string, 0, $count);
    }

    function dotrek($rek)
    {
        $nrek = strlen($rek);
        switch ($nrek) {
            case 1:
                $rek = $this->left($rek, 1);
                break;
            case 2:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1);
                break;
            case 3:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1);
                break;
            case 4:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2);
                break;
            case 5:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 3, 2);
                break;
            case 6:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2);
                break;
            case 7:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 1) . '.' . substr($rek, 3, 2) . '.' . substr($rek, 5, 2);
                break;
            case 8:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2);
                break;
            case 11:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 2) . '.' . substr($rek, 10, 1);
                break;
            case 12:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 2) . '.' . substr($rek, 10, 2);
                break;
            default:
                $rek = "";
        }
        return $rek;
    }

    function  english_date($tgl)
    {
        setlocale(LC_ALL, 'English_United_States');
        return strftime('%Y-%m-%d', strtotime($tgl));
    }

    function persen($nilai, $nilai2)
    {
        if ($nilai != 0) {
            $persen = $this->rp_minus((($nilai2 - $nilai) / $nilai) * 100);
        } else {
            if ($nilai2 == 0) {
                $persen = $this->rp_minus(0);
            } else {
                $persen = $this->rp_minus(100);
            }
        }
        return $persen;
    }
}
