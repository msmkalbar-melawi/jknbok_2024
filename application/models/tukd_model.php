<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class Tukd_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Tampilkan semua master data fungsi
    //function getAll($limit, $offset)
    function get_status($tgl, $skpd)
    {
        $n_status = '';
        $tanggal = $tgl;
        $sql = "select case when '$tanggal'>=tgl_dpa_ubah and status_ubah='1' then 'nilai_ubah' 
                    when '$tanggal'>=tgl_dpa_sempurna and status_sempurna='1' then 'nilai_sempurna' 
                    when '$tanggal'<=tgl_dpa and status='1' 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd' ";

        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();

        foreach ($q_trhrka->result() as $r_trhrka) {
            $n_status = $r_trhrka->anggaran;
        }
        return $n_status;
    }


    function cek_sisa_spd_lpj($skpd, $jns, $nospp, $no_bukti, $tgl, $jnsspp = '')
    {
        $hasil  = 0;

        $csql = "SELECT spd,keluar1 = keluar-terima,keluarspp  from(
					select sum(spd) as spd,sum(terima) as terima,sum(keluar) as keluar,sum(keluarspp) as keluarspp from(";

        //--------------------------------------Hitung Nilai SPD
        if ($tgl != '') {
            $csql1 = "	SELECT 'SPD' as ket,isnull(sum(nilai),0) as spd,0 as terima,0 as keluar,0 as keluarspp from trhspd d join trdspd e on d.no_spd=e.no_spd 
						where d.kd_skpd='$skpd' and d.status='1' and d.jns_beban='$jns' and d.tgl_spd<='$tgl'";
        } else {
            $csql1 = "	SELECT 'SPD' as ket,isnull(sum(nilai),0) as spd,0 as terima,0 as keluar,0 as keluarspp from trhspd d join trdspd e on d.no_spd=e.no_spd 
						where d.kd_skpd='$skpd' and d.status='1' and d.jns_beban='$jns'";
        }
        //-------------------------------------Realisasi SPJ 
        $csql2 = "		UNION ALL
						SELECT 'SPP' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd 
						where LEFT(kd_rek6,1)='$jns' and b.jns_spp in ('3','4','5','6') and left(a.kd_skpd,17)=left('$skpd',17) and b.no_spp<>'$nospp' and (sp2d_batal is null or sp2d_batal <>'1')
						union all
						select 'Trans UP/GU' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
						where LEFT(kd_rek6,1)='$jns' and b.jns_spp in ('1','2') and left(a.kd_skpd,17)=left('$skpd',17) and a.no_bukti<>'$no_bukti'
						union all
						select 'Trans UP/GU CMS' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdtransout_cmsbank a join trhtransout_cmsbank b on a.no_voucher=b.no_voucher 
						and a.kd_skpd=b.kd_skpd where LEFT(kd_rek6,1)='$jns' and b.jns_spp in ('1','2') and left(a.kd_skpd,17)=left('$skpd',17) and status_validasi<>'1'";
        //-------------------------------------Realisasi SPJ Tambahan Untuk BL
        $csql3 = "		union all
						select 'Panjar' as ket,0 as spd,0 as terima,ISNULL(sum(nilai),0) as keluar,0 as keluarspp from tr_panjar where jns='1' and left(kd_skpd,17)=left('$skpd',17) and no_kas<>'$no_bukti'
						union all
						select 'T/P Panjar' as ket,0 as spd,ISNULL(sum(nilai),0) as terima,0 as keluar,0 as keluarspp from tr_jpanjar where left(kd_skpd,17)=left('$skpd',17) and no_kas<>'$no_bukti'";

        //------------------------------------Realisasi Berdasarkan SPP	BL	UP/GU/TU/LS	
        $csql4 = "		union all
						select 'SPP' as ket,0 as spd,0 as terima,0 as keluar,isnull(sum(a.nilai),0) [keluarspp] from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd 
						where b.jns_spp in ('1','2','3','6') and left(a.kd_skpd,17)=left('$skpd',17) and b.no_spp<>'$nospp' and (sp2d_batal is null or sp2d_batal <>'1')";

        //------------------------------------Realisasi Berdasarkan SPP	BTL					
        $csql5 = "		union all
						select 'SPP' as ket,0 as spd,0 as terima,0 as keluar,isnull(sum(a.nilai),0) [keluarspp] from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd 
						where b.jns_spp in ('4') and left(a.kd_skpd,17)=left('$skpd',17) and b.no_spp<>'$nospp' and (sp2d_batal is null or sp2d_batal <>'1')";


        $csql6 = "	)as f
				)as g";

        if ($jnsspp == 'GU') {
            //-------------------------Proteksi LPJ & SPP GU 
            $hasil = $this->db->query($csql . $csql1 . $csql4 . $csql6);
        } else {
            //---------------------------Proteksi di Transaksi Tunai/CMS/ Pemindahbukuan
            if ($jnsspp == 'trans') {
                if ($jns == '5')
                    $hasil = $this->db->query($csql . $csql1 . $csql2 . $csql3 . $csql6);
                else {
                    $hasil = $this->db->query($csql . $csql1 . $csql6);
                }
            } else {
                //-------------------------Proteksi SPP Selain GU
                if ($jns == '5')
                    $hasil = $this->db->query($csql . $csql1 . $csql2 . $csql3 . $csql4 . $csql6);
                else {
                    $hasil = $this->db->query($csql . $csql1 . $csql5 . $csql6);
                }
            }
            //--------------------------------------------------------------------		   
        }
        return $hasil;
    }

    function cek_sisa_spd($skpd, $jns, $nospp, $no_bukti, $tgl, $jnsspp = '')
    {
        $hasil  = 0;

        $csql = "select spd,keluar1 = keluar-terima,keluarspp  from(
					select sum(spd) as spd,sum(terima) as terima,sum(keluar) as keluar,sum(keluarspp) as keluarspp from(";

        //--------------------------------------Hitung Nilai SPD
        if ($tgl != '') {
            $csql1 = "	select 'SPD' as ket,isnull(sum(nilai),0) as spd,0 as terima,0 as keluar,0 as keluarspp from trhspd d join trdspd e on d.no_spd=e.no_spd 
						where d.kd_skpd='$skpd' and d.status='1' and d.jns_beban='$jns' and d.tgl_spd<='$tgl'";
        } else {
            $csql1 = "	select 'SPD' as ket,isnull(sum(nilai),0) as spd,0 as terima,0 as keluar,0 as keluarspp from trhspd d join trdspd e on d.no_spd=e.no_spd 
						where d.kd_skpd='$skpd' and d.status='1' and d.jns_beban='$jns'";
        }
        //-------------------------------------Realisasi SPJ 
        $csql2 = "		union all
						select 'SPP' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd 
						where LEFT(kd_rek6,2)='$jns' and b.jns_spp in ('3','4','5','6') and a.kd_skpd='$skpd' and b.no_spp<>'$nospp' and (sp2d_batal is null or sp2d_batal <>'1')
						union all
						select 'Trans UP/GU' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
						where LEFT(kd_rek6,2)='$jns' and b.jns_spp in ('1','2') and a.kd_skpd='$skpd' and a.no_bukti<>'$no_bukti'
						union all
						select 'Trans UP/GU CMS' as ket,0 as spd,0 as terima,isnull(sum(a.nilai),0) [keluar],0 as keluarspp from trdtransout_cmsbank a join trhtransout_cmsbank b on a.no_voucher=b.no_voucher 
						and a.kd_skpd=b.kd_skpd where LEFT(kd_rek6,2)='$jns' and b.jns_spp in ('1','2') and a.kd_skpd='$skpd' and status_validasi<>'1'";
        //-------------------------------------Realisasi SPJ Tambahan Untuk BL
        $csql3 = "		union all
						select 'Panjar' as ket,0 as spd,0 as terima,ISNULL(sum(nilai),0) as keluar,0 as keluarspp from tr_panjar where jns='1' and kd_skpd='$skpd' and no_kas<>'$no_bukti'
						union all
						select 'T/P Panjar' as ket,0 as spd,ISNULL(sum(nilai),0) as terima,0 as keluar,0 as keluarspp from tr_jpanjar where kd_skpd='$skpd' and no_kas<>'$no_bukti'";

        //------------------------------------Realisasi Berdasarkan SPP	BL	UP/GU/TU/LS	
        $csql4 = "		union all
						select 'SPP' as ket,0 as spd,0 as terima,0 as keluar,isnull(sum(a.nilai),0) [keluarspp] from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd 
						where b.jns_spp in ('1','2','3','6') and a.kd_skpd='$skpd' and b.no_spp<>'$nospp' and (sp2d_batal is null or sp2d_batal <>'1')";

        //------------------------------------Realisasi Berdasarkan SPP	BTL					
        $csql5 = "		union all
						select 'SPP' as ket,0 as spd,0 as terima,0 as keluar,isnull(sum(a.nilai),0) [keluarspp] from trdspp a join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd 
						where b.jns_spp in ('4') and a.kd_skpd='$skpd' and b.no_spp<>'$nospp' and (sp2d_batal is null or sp2d_batal <>'1')";


        $csql6 = "	)as f
				)as g";

        if ($jnsspp == 'GU') {
            //-------------------------Proteksi LPJ & SPP GU 
            $hasil = $this->db->query($csql . $csql1 . $csql4 . $csql6);
        } else {
            //---------------------------Proteksi di Transaksi Tunai/CMS/ Pemindahbukuan
            if ($jnsspp == 'trans') {
                if ($jns == '52')
                    $hasil = $this->db->query($csql . $csql1 . $csql2 . $csql3 . $csql6);
                else {
                    $hasil = $this->db->query($csql . $csql1 . $csql6);
                }
            } else {
                //-------------------------Proteksi SPP Selain GU
                if ($jns == '52')
                    $hasil = $this->db->query($csql . $csql1 . $csql2 . $csql3 . $csql4 . $csql6);
                else {
                    $hasil = $this->db->query($csql . $csql1 . $csql5 . $csql6);
                }
            }
            //--------------------------------------------------------------------		   
        }
        return $hasil;
    }


    function getAll($tabel, $field1, $limit, $offset)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->order_by($field1, 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get();
    }
    function getcari($tabel, $field, $field1, $limit, $offset, $lccari)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field, $lccari);
        $this->db->or_like($field1, $lccari);
        $this->db->order_by($field, 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get();
    }
    function get_nama2($kode, $hasil, $tabel, $field, $field2, $kode2)
    {
        $this->db->select($hasil);
        $this->db->where($field, $kode);
        $this->db->where($field2, $kode2);
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data[0][$hasil];
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

    function cek_status_spj($kd_skpd)
    {
        $hasil = '0';
        $sql = "select top 1 Cast([bulan] as INT) [bulan] from trhspj_ppkd where kd_skpd='$kd_skpd' and cek='1' order by Cast([bulan] as INT) desc";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res) {
            $hasil = $res['bulan'];
        }
        return $hasil;
    }

    function cek_status_spj_pend($kd_skpd)
    {
        $hasil = '0';
        $sql = "select top 1 Cast([bulan] as INT) [bulan] from trhspj_terima_ppkd where kd_skpd='$kd_skpd' and cek='1' order by Cast([bulan] as INT) desc";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res) {
            $hasil = $res['bulan'];
        }
        return $hasil;
    }

    function getAllc($tabel, $field1)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->order_by($field1, 'asc');
        //$this->db->limit($limit,$offset);
        return $this->db->get();
    }

    // Total jumlah data
    function get_count($tabel)
    {
        return $this->db->get($tabel)->num_rows();
    }

    function get_count_cari($tabel, $field1, $field2, $data)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field1, $data);
        $this->db->or_like($field2, $data);
        $this->db->order_by($field1, 'asc');
        return $this->db->get()->num_rows();
        //return $this->db->get('ms_fungsi')->num_rows();
    }
    function get_count_teang($tabel, $field, $field1, $lccari)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field, $lccari);
        $this->db->or_like($field1, $lccari);
        $this->db->order_by($field, 'asc');
        return $this->db->get()->num_rows();
        //return $this->db->get('ms_fungsi')->num_rows();
    }
    // Ambil by ID
    function get_by_id($tabel, $field1, $id)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($field1, $id);
        return $this->db->get();
    }
    //cari
    function cari($tabel, $field1, $field2, $limit, $offset, $data)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field2, $data);
        $this->db->or_like($field1, $data);
        $this->db->order_by($field1, 'asc');
        return $this->db->get();
    }
    // Simpan data
    function save($tabel, $data)
    {
        $this->db->insert($tabel, $data);
    }

    // Update data
    function update($tabel, $field1, $id, $data)
    {
        $this->db->where($field1, $id);
        $this->db->update($tabel, $data);
    }

    // Hapus data
    function delete($tabel, $field1, $id)
    {
        $this->db->where($field1, $id);
        $this->db->delete($tabel);
    }

    function terbilang_lama($number)
    {

        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'minus ';
        $decimal     = ' koma ';
        $dictionary  = array(
            0 => 'nol', 1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat', 5 => 'lima', 6 => 'enam', 7 => 'tujuh',
            8 => 'delapan', 9 => 'sembilan', 10 => 'sepuluh', 11  => 'sebelas', 12 => 'dua belas', 13 => 'tiga belas', 14 => 'empat belas',
            15 => 'lima belas', 16 => 'enam belas', 17 => 'tujuh belas', 18 => 'delapan belas', 19 => 'sembilan belas', 20 => 'dua puluh',
            30 => 'tiga puluh', 40 => 'empat puluh', 50 => 'lima puluh', 60 => 'enam puluh', 70 => 'tujuh puluh', 80 => 'delapan puluh',
            90 => 'sembilan puluh', 100 => 'ratus', 1000 => 'ribu', 1000000 => 'juta', 1000000000 => 'milyar', 1000000000000 => 'triliun',
        );

        if (!is_numeric($number)) {
            return false;
        }
        /*
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'terbilang only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }
*/
        if ($number < 0) {
            return $negative . $this->terbilang(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->terbilang($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->terbilang($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->terbilang($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }




    function depan($number)
    {
        $number = abs($number);
        $nomor_depan = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $depans = "";

        if ($number < 12) {
            $depans = " " . $nomor_depan[$number];
        } else if ($number < 20) {
            $depans = $this->depan($number - 10) . " belas";
        } else if ($number < 100) {
            $depans = $this->depan($number / 10) . " puluh " . $this->depan(fmod($number, 10));
        } else if ($number < 200) {
            $depans = "seratus " . $this->depan($number - 100);
        } else if ($number < 1000) {
            $depans = $this->depan($number / 100) . " ratus " . $this->depan(fmod($number, 100));
            //$depans = $this->depan($number/100)." Ratus ".$this->depan($number%100);
        } else if ($number < 2000) {
            $depans = "seribu " . $this->depan($number - 1000);
        } else if ($number < 1000000) {
            $depans = $this->depan($number / 1000) . " ribu " . $this->depan(fmod($number, 1000));
        } else if ($number < 1000000000) {
            $depans = $this->depan($number / 1000000) . " juta " . $this->depan(fmod($number, 1000000));
        } else if ($number < 1000000000000) {
            $depans = $this->depan($number / 1000000000) . " milyar " . $this->depan(fmod($number, 1000000000));
            //$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

        } else if ($number < 1000000000000000) {
            $depans = $this->depan($number / 1000000000000) . " triliun " . $this->depan(fmod($number, 1000000000000));
            //$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

        } else {
            $depans = "Undefined";
        }
        return $depans;
    }

    function belakang($number)
    {
        $number = abs($number);
        $number = stristr($number, ".");
        $nomor_belakang = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");

        $belakangs = "";
        $length = strlen($number);
        $i = 1;
        while ($i < $length) {
            $get = substr($number, $i, 1);
            $i++;
            $belakangs .= " " . $nomor_belakang[$get];
        }
        return $belakangs;
    }

    function terbilang($number)
    {
        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            $hasil = "Minus " . trim($this->depan($number));
            $poin = trim($this->belakang($number));
        } else {
            $poin = trim($this->belakang($number));
            $hasil = trim($this->depan($number));
        }

        if ($poin) {
            $hasil = $hasil . " koma " . $poin . " Rupiah";
        } else {
            $hasil = $hasil . " Rupiah";
        }
        return $hasil;
    }

    function terbilang_angka($number)
    {
        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            $hasil = "Minus " . trim($this->depan($number));
            $poin = trim($this->belakang($number));
        } else {
            $poin = trim($this->belakang($number));
            $hasil = trim($this->depan($number));
        }

        if ($poin) {
            $hasil = $hasil . " koma " . $poin;
        } else {
            $hasil = $hasil;
        }
        return $hasil;
    }



    function _mpdf($judul = '', $isi = '', $lMargin = '', $rMargin = '', $font = 0, $orientasi = '')
    {

        ini_set("memory_limit", "512M");
        $this->load->library('mpdf');

        /*
        $this->mpdf->progbar_altHTML = '<html><body>
	                                    <div style="margin-top: 5em; text-align: center; font-family: Verdana; font-size: 12px;"><img style="vertical-align: middle" src="'.base_url().'images/loading.gif" /> Creating PDF file. Please wait...</div>';        
        $this->mpdf->StartProgressBarOutput();
        */

        $this->mpdf->defaultheaderfontsize = 6;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6;    /* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $this->mpdf->SetLeftMargin = $lMargin;
        $this->mpdf->SetRightMargin = $rMargin;
        //$this->mpdf->SetHeader('SIMAKDA||');
        $jam = date("H:i:s");
        //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Simakda| Page {PAGENO} of {nb}');
        $this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');

        $this->mpdf->AddPage($orientasi, '', '', '', '', $lMargin, $rMargin);

        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }

    /*    function  tanggal_jawa($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = $this-> getBulan(substr($tgl,5,2));
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.' '.$bulan.' '.$tahun;

   }
*/
    function  tanggal_format_indonesia($tgl)
    {

        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
    }

    function  tanggal_ind($tgl)
    {

        $tanggal  = explode('-', $tgl);
        $bulan  = $tanggal[1];
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . '-' . $bulan . '-' . $tahun;
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
            case 12:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 4);
                break;
            case 29:
                $rek = $this->left($rek, 21) . '.' . substr($rek, 23, 1) . '.' . substr($rek, 24, 1) . '.' . substr($rek, 25, 1) . '.' . substr($rek, 26, 2) . '.' . substr($rek, 28, 2);
                break;
            default:
                $rek = "";
        }
        return $rek;
    }


    //wahyu tambah ----------------------------------------	
    function  rev_date($tgl)
    {
        $t = explode("-", $tgl);
        $tanggal  =  $t[2];
        $bulan    =  $t[1];
        $tahun    =  $t[0];
        return  $tanggal . '-' . $bulan . '-' . $tahun;
    }

    function get_sclient($hasil, $tabel)
    {
        $this->db->select($hasil);
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data[0][$hasil];
    }

    function get_nama($kode, $hasil, $tabel, $field)
    {
        $this->db->select($hasil);
        $this->db->where($field, $kode);
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data[0][$hasil];
    }

    function get_nama3($kode, $hasil, $tabel, $field)
    {
        $this->db->select($hasil);
        $this->db->where($field, $kode);
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data;
    }
    // -----------------------------------------------------


    function combo_beban($id = '', $script = '')
    {
        $cRet    = '';
        $cRet    = "<select name=\"$id\" id=\"$id\" $script >";
        $cRet   .= "<option value=''>Pilih Beban</option>";
        $cRet   .= "<option value='1'>UP/GU</option>";
        $cRet   .= "<option value='3'>TU</option>";
        $cRet   .= "<option value='4'>GAJI</option>";
        $cRet   .= "<option value='6'>Barang & Jasa</option>";
        $cRet   .= "</select>";
        return $cRet;
    }

    function spj_trmpajak_rek($lcskpd = '', $lcrek = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_trmpajak_rek2($lcskpd = '', $lcrek = '', $lcrek2 = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_strpajak_rek($lcskpd = '', $lcrek = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_strpajak_rek2($lcskpd = '', $lcrek = '', $lcrek2 = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_tahunlalu($lcskpd = '', $nbulan)
    {
        $hasil = '';
        $csql = "SELECT SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                 SUM(ISNULL(jlain_up_pjkll,0)) jlain_up_pjkll, SUM(ISNULL(jlain_up_pjkini,0)) jlain_up_pjkini FROM(   
                    SELECT 
				    SUM(CASE WHEN a.jns_beban ='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN a.jns_beban ='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
				    SUM(CASE WHEN a.jns_beban ='7' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_pjkll,
					SUM(CASE WHEN a.jns_beban ='7' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_pjkini
					FROM TRHOUTLAIN a 
					WHERE a.kd_skpd='$lcskpd' and thnlalu=1
				) a ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }


    // -----------------------------------------------------	
    function qangg_sdana($tgl, $skpd, $giat, $kdrek5)
    {
        // $status = $this->get_status($tgl,$skpd);

        $data   = $this->cek_anggaran_model->cek_anggaran($skpd);

        $giat   = $this->input->post('giat');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');

        $sql = "
             SELECT * from (
            select sumber1 as sumber_dana,isnull(nsumber1,0) as nilai,
                (SELECT SUM(nilai) as nilai FROM trdtagih t INNER JOIN trhtagih u ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE t.kd_sub_kegiatan = '$giat' AND 
                u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber1)as lalu
            from trdrka a  where a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek'  and a.kd_skpd='$skpd' and a.jns_ang='$data'
            union ALL 
            select sumber2 as sumber_dana, isnull(nsumber2,0) as nilai,
                (SELECT SUM(nilai) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
                u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber2)as lalu
            from trdrka a where a.kd_sub_kegiatan='$giat'  and a.kd_rek6='$rek' and a.kd_skpd='$skpd' and a.jns_ang='$data'
            union ALL 
            select sumber3 as sumber_dana,isnull(nsumber3,0) as nilai,
                (SELECT SUM(nilai) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
                u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber3)as lalu
            from trdrka a where a.kd_sub_kegiatan='$giat' and a.kd_rek6='$rek' and a.kd_skpd='$skpd' and a.jns_ang='$data' 
            union ALL 
            select sumber4 as sumber_dana,isnull(nsumber4,0) as nilai,
            (SELECT SUM(nilai) as nilai FROM trdtagih t  INNER JOIN trhtagih u  ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd WHERE  t.kd_sub_kegiatan = '$giat' AND 
            u.kd_skpd = '$skpd' AND t.kd_rek = '$rek' AND u.no_bukti  NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$skpd' ) and sumber=sumber4)as lalu
            from trdrka a where a.kd_sub_kegiatan='$giat'  and a.kd_rek6='$rek' and a.kd_skpd='$skpd' and a.jns_ang='$data')z where z.nilai<>0

            ";

        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'sumber_dana'       => $resulte['sumber_dana'],
                'nilaidana'         => $resulte['nilai'],
                'nilaidana_lalu'    => $resulte['lalu']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    // -----------------------------------------------------	
    function check_sdana($sumber)
    {
        $sumber1 = $sumber;
        if ($sumber == 'DAK FISIK') {
            $sumber1 = 'dak';
        } else if ($sumber == 'DAK NON FISIK') {
            $sumber1 = 'daknf';
        }
        return $sumber1;
    }

    function qtrans_sdana($sumber, $giat, $rek, $skpd, $nobkuk)
    {
        $sumber = $this->check_sdana($sumber);
        // $nilai  = 'nil_'.$sumber;
        $hasil  = 0;
        $csql = "select sum(nilai) [total] from (
                    select 'spp' [jdl],sum(isnull(b.nilai,0)) [nilai] from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                    where b.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and b.kd_rek6='$rek'  and jns_spp not in ('1','2') AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL) and b.sumber='$sumber'
                    union all
                    select 'tagih' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai] from trhtagih a join trdtagih b on a.no_bukti=b.no_bukti 
                    and a.kd_skpd=b.kd_skpd 
                    where b.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and b.kd_rek='$rek' and b.no_bukti not in (select no_tagih from trhspp where kd_skpd='$skpd') and b.sumber='$sumber'
                    union all
                    select 'trans' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]  from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
					where b.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and b.kd_rek6='$rek' and jns_spp  in ('1') and a.no_bukti not in('$nobkuk') and b.sumber='$sumber'
                ) as gabung ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }





    function pot($kd_skpd = '', $spm = '')
    {
        $sql = "SELECT * FROM trspmpot where no_spm='$spm' AND kd_skpd='$kd_skpd' order by kd_rek6 ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek6'],
                'kd_trans' => $resulte['kd_trans'],
                'nm_rek5' => $resulte['nm_rek6'],
                'pot' => $resulte['pot'],
                'nilai' => $resulte['nilai']
            );
            $ii++;
        }
        return json_encode($result);
    }

    function qtrans_sdana2($sumber, $giat, $rek, $skpd, $nobkuk)
    {
        $sumber = $this->check_sdana($sumber);
        //$nilai  = 'nil_'.$sumber;
        $hasil  = 0;
        $csql = "SELECT ISNULL(sum(nilai),0) [total] from (
                    select 'spp' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai] from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                    where b.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and b.kd_rek6='$rek'  and jns_spp not in ('1','2') and b.sumber='$sumber' AND (a.sp2d_batal<>1 OR a.sp2d_batal IS NULL)
                    union all
                    select 'tagih' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai] from trhtagih a join trdtagih b on a.no_bukti=b.no_bukti 
                    and a.kd_skpd=b.kd_skpd
                    where b.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and b.kd_rek='$rek' and b.no_bukti not in 
                    (select no_tagih from trhspp where kd_skpd='$skpd') and b.sumber='$sumber'
                    union all
                    select 'trans' [jdl],isnull(sum(isnull(b.nilai,0)),0) [nilai]  from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
					where b.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and b.kd_rek6='$rek' and jns_spp  in ('1') and a.no_bukti not in('$nobkuk') and b.sumber='$sumber'
                ) as gabung ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }
}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */