<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class Akuntansi_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
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
}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */