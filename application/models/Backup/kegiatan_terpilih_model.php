<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 
 */

class Kegiatan_terpilih_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	// Tampilkan semua master data kegiatan
	function getAll($limit, $offset)
	{
		$this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
		$this->db->from('trskpd');
        $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
        $this->db->order_by('trskpd.kd_skpd');
		$this->db->order_by('trskpd.kd_kegiatan', 'asc');
		$this->db->limit($limit,$offset);
		return $this->db->get();
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
	function get_count()
	{
		return $this->db->get('trskpd')->num_rows();
	}
	
	// Ambil by ID
	function get_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('trskpd');
		$this->db->where('kd_kegiatan', $id);
		return $this->db->get();
	}
    
    function get_program($id)
	{
		$this->db->select('*');
		$this->db->from('m_prog');
		$this->db->order_by('kd_program',$id);
        $this->db->limit($limit,$offset);
		return $this->db->get();
	}
	
	// Simpan data
	function save($data)
	{
		$this->db->insert('trskpd', $data);
	}
	
	// Update data
	function update($id, $data)
	{
		$this->db->where('kd_kegiatan', $id);
		$this->db->update('trskpd', $data); 	
	}
	
	// Hapus data
	function delete($id)
	{
		$this->db->where('kd_kegiatan', $id);
		$this->db->delete('trskpd');
	}

}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */