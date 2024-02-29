<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class cek_anggaran_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function cek_anggaran($kode)
	{
		$sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
                    ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$kode' and 
                    b.tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
	return $query1 = $this->db->query($sql)->row()->jns_ang;
	}
}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */