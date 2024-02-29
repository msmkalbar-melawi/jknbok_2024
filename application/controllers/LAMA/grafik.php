<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grafik extends CI_Controller {
	function __construct(){
		parent::__construct();
		
	}
    
    function index()
	{
	   $this->load->model('grafik_model');
        $data['graph1'] = $this->grafik_model->creategraph();
        $this->load->view('tampilgrafik', $data);	
	}

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */