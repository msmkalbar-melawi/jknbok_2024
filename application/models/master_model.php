<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class Master_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	// Tampilkan semua master data fungsi
	//function getAll($limit, $offset)
	function getAll($tabel, $field1, $limit, $offset)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->order_by($field1, 'asc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}

	function getAll2($tabel, $field1, $limit, $offset)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where('status', '1');
		$this->db->order_by($field1, 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}

	function getSkpd($tabel, $field1, $limit, $offset)
	{
		$skpd = $this->session->userdata('kdskpd');
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where('kd_skpd', $skpd);
		$this->db->order_by($field1, 'asc');
		//$this->db->limit($limit,$offset);
		return $this->db->get();
	}

	function skpduser($lccr = '')
	{

		$id    = $this->session->userdata('kdskpd');
		$type  = $this->session->userdata('type');
		if ($type == '1') {
			$sql = "SELECT kd_skpd, nm_skpd, jns FROM ms_skpd and kd_skpd not in (select kd_skpd from kegiatan_bp) and (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) order by kd_skpd ";
		} else {
			// $sort= substr($id,0,4)=='1.02' || substr($id,0,4)=='7.01' ? "left(kd_skpd,17)=left('$id',17)" : "kd_skpd='$id'";
			$sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
			ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$id' and 
			tgl_dpa in(SELECT MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
			$query1 = $this->db->query($sql);

			$test = $query1->num_rows();

			$query1->free_result();
		}

		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kd_skpd' => $resulte['kd_skpd'],
				'nm_skpd' => $resulte['nm_skpd'],
				'jns_ang' => $resulte['jns_ang']
			);
			$ii++;
		}

		return $result;
	}

	function getAll3($tabel, $field1, $limit, $offset)
	{
		$id  = $this->session->userdata('pcUser');
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where('id_user', $id);
		$this->db->order_by($field1, 'asc');
		//$this->db->limit($limit,$offset);
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

	function getSome($tabel, $field1, $field2, $x)
	{
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where($field2, $x);
		$this->db->order_by($field1, 'asc');
		return $this->db->get();
	}
	function load_jang()
	{
		$sql = "SELECT * FROM tb_status_anggaran WHERE status_aktif='1' ";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kode' => $resulte['kode'],
				'nama' => $resulte['nama']
			);
			$ii++;
		}
		return json_encode($result);
	}
	function load_jangkas($jang = '')
	{
		// echo($jang);

		// if ($jang=='S' || $jang=='M'){
		// 	$where="AND (kode='susun' OR kode='murni')";
		// }else if ($jang=='P1'){
		// 	$where="AND (kode='sempurna' OR kode='sempurna11' OR kode='sempurna12' OR kode='sempurna13')";
		// } else{
		// 	$where="AND kode='ubah' OR kode='ubah1' ";
		// }   

		$sql = "SELECT * FROM tb_status_angkas WHERE status='1' order by id DESC";

		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kode' => $resulte['kode'],
				'nama' => $resulte['nama']
			);
			$ii++;
		}
		return json_encode($result);
	}
}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */