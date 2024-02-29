<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ClientModel extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	public function clientData($id){
		$this->db->select('*');
		$this->db->from('config_client');
		$this->db->where('id', $id);
		$data = $this->db->get();
		return $data->row();
	}
}