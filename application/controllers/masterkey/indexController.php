<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class indexController extends CI_Controller {

	public $org_keu = "";
	public $skpd_keu = "";
	
	function __contruct()
	{	
		parent::__construct();
        
	}

public function firstpage()
{
    $data['page_title']= 'HALAMAN UTAMA';
    // $this->template->set('title', 'HALAMAN UTAMA');   
    $this->load->view('masterkey/index',$data) ; 
}

}





