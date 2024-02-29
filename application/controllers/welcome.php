<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('login_model');
		$this->client_logon = $this->session->userdata('logged');
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function index()
	{
	$this->template->set('title', 'SIMAKDA SKPD');
	if ($this->client_logon){
        $user              = $this->session->userdata('pcUser');
		$otoriname         = $this->session->userdata('pcOtoriName');
		$logintime         = $this->session->userdata('pcLoginTime');
		//$userlevel	  = $$this->session->userdata('pcUserlevel');
		$thn_ang	   = $this->session->userdata('pcThang');	
		//$jaka = $this->session->userdata('pcJaka');
       
		$this->template->load('template', 'home',$otoriname);
	       
        }
	 else
        {
	//$this->welcome->login();
           //redirect('login');
	   redirect('/welcome/login');
	}
	}
	
	Public function login()
	{
	
		if($_POST)
		{
		
		$user = $this->login_model->validate($_POST['username'], $_POST['password'],$_POST['pcthang']);
		$name = $_POST['username'];
		$pass = $_POST['password'];
		$passmd5 = md5($pass);
		$cing		=	"SELECT status,kunci FROM [user] WHERE user_name = '$name' AND password = '$passmd5'";
		$cek_bar	=	$this->db->query($cing);
			if($cek_bar->num_rows()>0){
				foreach ($cek_bar->result() as $row){
					$jr     = $row->status;
					$kunci = rtrim($row->kunci);
				}
			}else{
				$jr = '';
				$kunci = '';
			}
		
            if($user == TRUE && $kunci == '1'){
				$this->template->set('title', 'SIMAKDA SKPD');
			
				$data['pesan'] = 'Username sementara dikunci!. Silakan hubungi admin';
				$this->template->load('template_login', 'login',$data);				
			}else{ 	
    			if($user == TRUE)
    			{	
    				$session_id = $this->session->userdata('session_id');
    				$query = $this->db->query("UPDATE [user] SET status = '1', session_id = '$session_id' WHERE user_name = '$name' AND password ='$passmd5'");
    				redirect('welcome');
    			}
    			else{
    			$this->template->set('title', 'SIMAKDA SKPD');
    			
    			$data['pesan'] = 'Username dan Password Salah !!';
    			$this->template->load('template_login', 'login',$data);	
    			}
			}
		}
		else
		{
		
		$this->template->set('title', 'SIMAKDA SKPD');
		$this->template->load('template_login', 'login');
		}
	}
 
	 Public function logout()
	{
		$this->login_model->logout();
		   redirect('/welcome/login');;
	}
 

	public function ceklogin(){
		/*$user=$this->session->userdata('pcNama');
		if ($user==''){
			echo '1';
		}else{
			echo '0';
		}*/
		
		$user=$this->session->userdata('pcNama');
		$cing		=	"SELECT status,kunci,session_id FROM [user] WHERE user_name = '$user'";
		$cek_bar	=	$this->db->query($cing);
			if($cek_bar->num_rows()>0){
				foreach ($cek_bar->result() as $row){
					$kunci     = $row->kunci;
					$session_id = $row->session_id;
				}
			}else{
				$kunci = '';
			}
		
		if ($kunci=='1'){
			$this->login_model->logout();
			//$this->template->set('title', 'SIMAKDA SKPD');
			//$data['pesan'] = 'Username dan Password Salah !!';
			//$this->template->load('template_login', 'login',$data);
		   echo '1';
		} else if ($session_id != $this->session->userdata('session_id')
			&& $this->session->userdata('pcNama') != 'adminopd') {
			$this->login_model->logout();
			echo '1';
		} else{
			echo '0';
		}	
	}

}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */