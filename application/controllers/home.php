<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Web extends CI_Controller {
 
function __construct()
{
parent::__construct();
$this->load->model('login_model');
$this->client_logon = $this->session->userdata('logged');
}
 
public function index()
{
if($this->client_logon)
{
$data['pesan'] = 'Anda sudah berhasil login! Klik di sini untuk <a href="http://localhost/codeigniter/logout">LOGOUT</a>.';
 
$this->load->view('vhome', $data);
}
else
{
redirect('login');
}
}
 
public function login()
{
if($_POST)
{
$user = $this->login_model->validate($_POST['username'], $_POST['password']);
 
if($user == TRUE)
{
redirect('index');
}
else
{
$data['pesan'] = 'Username atau password salah!';
$this->load->view('vlogin', $data);
}
 
}
else
{
$this->load->view('vlogin');
}
}
 
public function logout()
{
$this->login_model->logout();
redirect('login');
}
 
}