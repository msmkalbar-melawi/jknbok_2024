<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
/**
* 
*/
class  status_anggarans{		

    public function __construct(){
            // Assign the CodeIgniter super-object
            //$this->CI =& get_instance();
    }

    function status_anggarancombo($status){
        if($status=='1'){
            return 'nilai';
        }else if($status=='2'){
            return 'nilai_sempurna';
        }else if($status=='3'){
            return 'nilai_ubah';
        }
    }

    function status_namaanggaran($status){
        if($status=='1'){
            return 'Penyusunan';
        }else if($status=='2'){
            return 'Pergeseran';
        }else if($status=='3'){
            return 'Perubahan';
        }
    }    

    function status_anggaranangka($status){
        if($status=='nilai'){
            return '1';
        }else if($status=='nilai_sempurna'){
            return '2';
        }else if($status=='nilai_ubah'){
            return '3';
        }
    }  

    function get_status_top($tgl){
        $CI =& get_instance();
        $CI->load->database();
        $n_status = '';
        $tanggal = $tgl;
        $sql = "select top 1 case when '$tanggal'>=tgl_dpa_ubah then 'nilai_ubah' 
                    when '$tanggal'>=tgl_dpa_sempurna then 'nilai_sempurna' 
                    when '$tanggal'<=tgl_dpa 
                    then 'nilai' else 'nilai' end as anggaran from trhrka ";
        
        $q_trhrka = $CI->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;                         
    }
}
?>