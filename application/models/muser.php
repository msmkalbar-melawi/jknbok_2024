<?php

class Muser extends CI_Model {
    function Muser(){
        parent::__construct();
    }
    function cek_user($username,$password){
        $this->db->where('user_name',$password);
        $this->db->where('password',$password);
        $query=$this->db->get('user');
        if($query->num_rows()>0){
            return true;
        }
        else{
           return FALSE;
        }
    }
}



?>