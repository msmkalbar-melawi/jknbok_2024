<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */ 

class status_anggaran extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	


    function cek_status_ang(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT 
                case 
                when status=1 and status_sempurna=1 and status_ubah=1  then 'Perubahan'
                when status=1 and status_sempurna=1 and status_ubah=0  then 'Penyempurnaan' 
				when status=1 and status_sempurna=0 and status_ubah=0  then 'Penyusunan' 
				when status=0 and status_sempurna=0 and status_ubah=0  then 'Penyusunan'
				when status=0 and status_sempurna=1 and status_ubah=0  then 'Penyusunan'
				when status=0 and status_sempurna=1 and status_ubah=1  then 'Penyusunan'
				when status=1 and status_sempurna=0 and status_ubah=1  then 'Perubahan'
				else 'Penyusunan' end as anggaran from trhrka where kd_skpd ='$skpd'";
        $query1 = $this->db->query($sql);  
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'status_ang' => $resulte['anggaran']
                        );
                        $ii++;
        }
        return json_encode($result);
    }
}