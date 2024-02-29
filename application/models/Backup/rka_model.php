<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 
 */

class Rka_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	// Tampilkan semua master data kegiatan
	function getAll($limit, $offset,$id)
	{
		$this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
		$this->db->from('trskpd');
        $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
       	$this->db->where('trskpd.kd_skpd', $id);
        $this->db->where('trskpd.jns_kegiatan','52');
        $this->db->order_by('trskpd.kd_skpd');
		$this->db->order_by('trskpd.kd_kegiatan', 'asc');
		$this->db->limit($limit,$offset);
		return $this->db->get();
	}
    
	
	function combo_ttd($kode='') 
    {
               
        $kd_skpd = $this->session->userdata('kdskpd');      
               
        $csql    = "SELECT * from ms_ttd where kd_skpd = '$kd_skpd' order by nama ";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"ttd\" id=\"ttd\" style=\"height:28px;width:200px\">";
        $cRet   .= "<option value=\"\">Pilih Penanda Tangan</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['nip']==$kode) ? " selected" : "";
           if (!empty($row['nip'])) 
                $cRet .= "<option value='".$row['nip']."'".$selected.">".$row['nama']."</option>";
        
        }
        $cRet .= "</select>";
        
        return $cRet;
    }
    
    function getAllc()
	{
		$this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
		$this->db->from('trskpd');
        $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
        $this->db->order_by('trskpd.kd_skpd');
		$this->db->order_by('trskpd.kd_kegiatan', 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}
    
    	function get_count_cari($data)
	{
        $this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
		$this->db->from('trskpd');
        $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
        $this->db->order_by('trskpd.kd_skpd');
		$this->db->order_by('trskpd.kd_kegiatan', 'asc');
		return $this->db->get()->num_rows();
		//return $this->db->get('ms_fungsi')->num_rows();
	}
    
    //cari
    function cari($limit, $offset,$data)
	{
		$this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
		$this->db->from('trskpd');
        $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
        $this->db->or_like('nm_kegiatan', $data);  
        $this->db->or_like('trskpd.kd_kegiatan', $data);      
		$this->db->order_by('trskpd.kd_kegiatan', 'asc');
		return $this->db->get();
	}
	
	// Total jumlah data
	function get_count($id)
	{
        $this->db->select('*');
		$this->db->from('trskpd');
		$this->db->where('kd_skpd', $id);
        $this->db->where('trskpd.jns_kegiatan','52');
		return $this->db->get()->num_rows();
	}
	
	// Ambil by ID
	function get_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('trskpd');
		$this->db->where('kd_kegiatan', $id);
		return $this->db->get();
	}
    
    //get nama dari master
    function get_nama($kode,$hasil,$tabel,$field)
	{
        $this->db->select($hasil);
		$this->db->where($field, $kode);
		$q = $this->db->get($tabel);
		$data  = $q->result_array();
		$baris = $q->num_rows();
		return $data[0][$hasil];
	}
    
    function get_program($id)
	{
		$this->db->select('*');
		$this->db->from('m_prog');
		$this->db->order_by('kd_program',$id);
        $this->db->limit($limit,$offset);
		return $this->db->get();
	}
    
    function combo_urus($skpd='',$tahun='') 
    {
               
        $csql    = "SELECT * from ms_urusan order by kd_urusan ";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"urusan\" id=\"urusan\">";
        $cRet   .= "<option value=\"\">--Pilih Kode Urusan--</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['kd_urusan']==$skpd) ? " selected" : "";
           if (!empty($row['kd_urusan'])) 
                $cRet .= "<option value='".$row['kd_urusan']."'".$selected.">".$row['kd_urusan']." | ".$row['nm_urusan']."</option>";
        
        }
        $cRet .= "</select>";
        
        return $cRet;
    }
    
    function combo_skpd($skpd='',$tahun='') 
    {
               
        $csql    = "SELECT * from ms_skpd order by kd_skpd ";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"skpd\" id=\"skpd\" >";
        $cRet   .= "<option value=\"\">--Pilih Kode SKPD--</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['kd_skpd']==$skpd) ? " selected" : "";
           if (!empty($row['kd_skpd'])) 
                $cRet .= "<option value='".$row['kd_skpd']."'".$selected.">".$row['kd_skpd']." | ".$row['nm_skpd']."</option>";
        
        }
        $cRet .= "</select>";
        
        return $cRet;
    }
    function combo_bln($skpd='',$tahun='') 
    {        
        $csql    = "SELECT * from ms_bln ";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"bln\" id=\"bln\">";
        $cRet   .= "<option value=\"\">--Pilih Blan--</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['kd']==$skpd) ? " selected" : "";
           if (!empty($row['kd'])) 
                $cRet .= "<option value='".$row['kd']."'".$selected.">".$row['kd']." | ".$row['nm']."</option>";
        
        }
        $cRet .= "</select>";
        
        return $cRet;
    }
    function combo_bank($bank='') 
    {        
        $csql    = "SELECT * from ms_bank order by kode ";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"bank\" id=\"bank\">";
        $cRet   .= "<option value=\"\">--Pilih Bank--</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['kode']==$bank) ? " selected" : "";
           if (!empty($row['kode'])) 
                $cRet .= "<option value='".$row['kode']."'".$selected.">".$row['kode']." | ".$row['nama']."</option>";
        
        }
        $cRet .= "</select>";
        
        return $cRet;
    }
    
    function combo_skpd1($skpd='',$tahun='') 
    {
               
        $csql    = "SELECT * from ms_skpd order by kd_skpd ";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"skpd\" id=\"skpd\" onchange=\"javascript:validate_combo();\">";
        $cRet   .= "<option value=\"\">--Pilih Kode SKPD--</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['kd_skpd']==$skpd) ? " selected" : "";
           if (!empty($row['kd_skpd'])) 
                $cRet .= "<option value='".$row['kd_skpd']."'".$selected.">".$row['kd_skpd']." | ".$row['nm_skpd']."</option>";
        
        }
        $cRet .= "</select>";
        
        return $cRet;
    }
    
    function combo_giat1($giat='',$tahun='') 
    {
               
        $csql    = "SELECT * from ms_kegiatan order by kd_kegiatan ";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"giat\" id=\"giat\" onchange=\"javascript:validate_combo();\">"; 
        $cRet   .= "<option value=\"\">--Pilih Kode Kegiatan--</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['kd_kegiatan']==$giat) ? " selected" : "";
           if (!empty($row['kd_kegiatan'])) 
                $cRet .= "<option value='".$row['kd_kegiatan']."'".$selected.">".$row['kd_kegiatan']." | ".$row['nm_kegiatan']."</option>";
        
        }
        $cRet .= "</select>";
        
        return $cRet;
    }
	
    function combo_giat($giat='',$skpd=''){
               
        $csql    = "SELECT a.kd_kegiatan1,a.kd_kegiatan,b.nm_kegiatan FROM trskpd a LEFT JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan where a.kd_skpd like '%$skpd%' ORDER BY b.kd_kegiatan";
        $query   = $this->db->query($csql);
         
        $cRet    = "<select name=\"giat\" id=\"giat\" onchange=\"javascript:validate_combo();\">"; 
        $cRet   .= "<option value=\"\">--Pilih Kode Kegiatan--</option>";
        foreach ($query->result_array() as $row)
        {
           $selected = ($row['kd_kegiatan']==$giat) ? " selected" : "";
           if (!empty($row['kd_kegiatan'])) 
                $cRet .= "<option value='".$row['kd_kegiatan']."' ".$selected.">".$row['kd_kegiatan']." | ".$row['nm_kegiatan']."</option>";
     
        }
        $cRet .= "</select>";        
        return $cRet;
    }
    
    function combo_bulan($id='',$script=''){
        $cRet    = '';                        
        $cRet    = "<select name=\"$id\" id=\"$id\" $script >";
        $cRet   .= "<option value='0'>Pilih Bulan</option>"; 
        $cRet   .= "<option value='1'>Januari</option>";
        $cRet   .= "<option value='2'>Februari</option>";
        $cRet   .= "<option value='3'>Maret</option>";
        $cRet   .= "<option value='4'>April</option>";
        $cRet   .= "<option value='5'>Mei</option>";
        $cRet   .= "<option value='6'>Juni</option>";
        $cRet   .= "<option value='7'>Juli</option>";
        $cRet   .= "<option value='8'>Agustus</option>";
        $cRet   .= "<option value='9'>September</option>";
        $cRet   .= "<option value='10'>Oktober</option>";
        $cRet   .= "<option value='11'>November</option>";
        $cRet   .= "<option value='12'>Desember</option>";
        $cRet   .= "</select>";        
        return $cRet;
    }
    
    function combo_beban($id='',$script=''){
        $cRet    = '';                        
        $cRet    = "<select name=\"$id\" id=\"$id\" $script >";
        $cRet   .= "<option value='5'>Keseluruhan</option>";                 
        $cRet   .= "<option value='52'>Belanja Langsung</option>";
        $cRet   .= "<option value='51'>Belanja Tidak Langsung</option>"; 
        $cRet   .= "<option value='62'>Pembiayaan</option>";       
        $cRet   .= "</select>";        
        return $cRet;
    }
    
    //function terbilang($number) {
//   
//        $hyphen      = ' ';
//        $conjunction = ' ';
//        $separator   = ' ';
//        $negative    = 'minus ';
//        $decimal     = ' koma ';
//        $dictionary  = array(0 => 'nol',1 => 'satu',2 => 'dua',3 => 'tiga',4 => 'empat',5 => 'lima',6 => 'enam',7 => 'tujuh',
//            8 => 'delapan',9 => 'sembilan',10 => 'sepuluh',11  => 'sebelas',12 => 'dua belas',13 => 'tiga belas',14 => 'empat belas',
//            15 => 'lima belas',16 => 'enam belas',17 => 'tujuh belas',18 => 'delapan belas',19 => 'sembilan belas',20 => 'dua puluh',
//            30 => 'tiga puluh',40 => 'empat puluh',50 => 'lima puluh',60 => 'enam puluh',70 => 'tujuh puluh',80 => 'delapan puluh',
//            90 => 'sembilan puluh',100 => 'ratus',1000 => 'ribu',1000000 => 'juta',1000000000 => 'milyar',1000000000000 => 'triliun',
//        );
//       
//        if (!is_numeric($number)) {
//            return false;
//        }
//       
//        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
//            // overflow
//            trigger_error(
//                'terbilang only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
//                E_USER_WARNING
//            );
//            return false;
//        }
//    
//        if ($number < 0) {
//            return $negative . $this->terbilang(abs($number));
//        }
//       
//        $string = $fraction = null;
//       
//        if (strpos($number, '.') !== false) {
//            list($number, $fraction) = explode('.', $number);
//        }
//       
//        switch (true) {
//            case $number < 21:
//                $string = $dictionary[$number];
//                break;
//            case $number < 100:
//                $tens   = ((int) ($number / 10)) * 10;
//                $units  = $number % 10;
//                $string = $dictionary[$tens];
//                if ($units) {
//                    $string .= $hyphen . $dictionary[$units];
//                }
//                break;
//            case $number < 1000:
//                $hundreds  = $number / 100;
//                $remainder = $number % 100;
//                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
//                if ($remainder) {
//                    $string .= $conjunction . $this->terbilang($remainder);
//                }
//                break;
//            default:
//                $baseUnit = pow(1000, floor(log($number, 1000)));
//                $numBaseUnits = (int) ($number / $baseUnit);
//                $remainder = $number % $baseUnit;
//                $string = $this->terbilang($numBaseUnits) . ' ' . $dictionary[$baseUnit];
//                if ($remainder) {
//                    $string .= $remainder < 100 ? $conjunction : $separator;
//                    $string .= $this->terbilang($remainder);
//                }
//                break;
//        }
//       
//        if (null !== $fraction && is_numeric($fraction)) {
//            $string .= $decimal;
//            $words = array();
//            foreach (str_split((string) $fraction) as $number) {
//                $words[] = $dictionary[$number];
//            }
//            $string .= implode(' ', $words);
//        }
//       
//        return $string;
//    }
      function terbilang_get_valid($str,$from,$to,$min=1,$max=9){
        	$val=false;
        	$from=($from<0)?0:$from;
        	for ($i=$from;$i<$to;$i++){
        		if (((int) $str{$i}>=$min)&&((int) $str{$i}<=$max)) $val=true;
        	}
        	return $val;
        }
        function terbilang_get_str($i,$str,$len){
        	$numA=array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan");
        	$numB=array("","se","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
        	$numC=array("","satu ","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
        	$numD=array(0=>"puluh",1=>"belas",2=>"ratus",4=>"ribu", 7=>"juta", 10=>"milyar", 13=>"triliun");
        	$buf="";
        	$pos=$len-$i;
        	switch($pos){
        		case 1:
        				if (!$this->terbilang_get_valid($str,$i-1,$i,1,1))
        					$buf=$numA[(int) $str{$i}];
        			break;
        		case 2:	case 5: case 8: case 11: case 14:
        				if ((int) $str{$i}==1){
        					if ((int) $str{$i+1}==0)
        						$buf=($numB[(int) $str{$i}]).($numD[0]);
        					else
        						$buf=($numB[(int) $str{$i+1}]).($numD[1]);
        				}
        				else if ((int) $str{$i}>1){
        						$buf=($numB[(int) $str{$i}]).($numD[0]);
        				}				
        			break;
        		case 3: case 6: case 9: case 12: case 15:
        				if ((int) $str{$i}>0){
        						$buf=($numB[(int) $str{$i}]).($numD[2]);
        				}
        			break;
        		case 4: case 7: case 10: case 13:
        				if ($this->terbilang_get_valid($str,$i-2,$i)){
        					if (!$this->terbilang_get_valid($str,$i-1,$i,1,1))
        						$buf=$numC[(int) $str{$i}].($numD[$pos]);
        					else
        						$buf=$numD[$pos];
        				}
        				else if((int) $str{$i}>0){
        					if ($pos==4)
        						$buf=($numB[(int) $str{$i}]).($numD[$pos]);
        					else
        						$buf=($numC[(int) $str{$i}]).($numD[$pos]);
        				}
        			break;
        	}
        	return $buf;
        }
        function terbilang($nominal){
        	$buf="";
        	$str=$nominal."";
        	$len=strlen($str);
        	for ($i=0;$i<$len;$i++){
        		$buf=trim($buf)." ".$this->terbilang_get_str($i,$str,$len);
        	}
        	return trim($buf);
        }
    
      function _mpdf($judul='',$isi='',$lMargin='',$rMargin='',$font=0,$orientasi='') {
        
        ini_set("memory_limit","-1");
        $this->load->library('mpdf');
        
        /*
        $this->mpdf->progbar_altHTML = '<html><body>
	                                    <div style="margin-top: 5em; text-align: center; font-family: Verdana; font-size: 12px;"><img style="vertical-align: middle" src="'.base_url().'images/loading.gif" /> Creating PDF file. Please wait...</div>';        
        $this->mpdf->StartProgressBarOutput();
        */
        
        $this->mpdf->defaultheaderfontsize = 6;	/* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6;	/* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        $this->mpdf->SetLeftMargin = $lMargin;
        $this->mpdf->SetRightMargin = $rMargin;
        //$this->mpdf->SetHeader('SIMAKDA||');
        //$jam = date("H:i:s");
        //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Simakda| Page {PAGENO} of {nb}');
        //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');
        
        $this->mpdf->AddPage($orientasi,'','','','',$lMargin,$rMargin);
        
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);         
        $this->mpdf->Output();
               
    }
    
    function  getBulan($bln){
        switch  ($bln){
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
        return  "Maret";
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
    
    function get_ang($field,$rek,$skpd){
		$hasil='';
		$csql = " SELECT SUM($field) AS nilai FROM trdrka WHERE LEFT(kd_rek5,1)='$rek' AND kd_skpd='$skpd'";
		$query1 = $this->db->query($csql);  
        foreach($query1->result_array() as $resulte)
        {	
			$hasil=$resulte[nilai];
		}	
		return $hasil;
	}
    function get_ang1($field,$rek){
		$hasil='';
		$csql = " SELECT SUM($field) AS nilai FROM trdrka WHERE LEFT(kd_rek5,1)='$rek' ";
		$query1 = $this->db->query($csql);  
        foreach($query1->result_array() as $resulte)
        {	
			$hasil=$resulte[nilai];
		}	
		return $hasil;
	}
    
    function get_ang2($field,$rek){
		$hasil='';
		$csql = " SELECT SUM($field) AS nilai FROM trdrka WHERE LEFT(kd_rek5,2)='$rek'";
		$query1 = $this->db->query($csql);  
        foreach($query1->result_array() as $resulte)
        {	
			$hasil=$resulte[nilai];
		}	
		return $hasil;
	}

}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */