<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function jns_ang_jkn($skpd)
{
    //  echo 'DIKA';
    $ci =& get_instance();
    $data = "SELECT jns_ang FROM jkn_trhrka a WHERE a.kd_skpd='$skpd' AND a.tgl_dpa IN(SELECT MAX(tgl_dpa) FROM jkn_trhrka c WHERE c.kd_skpd=a.kd_skpd AND status='1')";
    return $ci->db->query($data)->row()->jns_ang;
}   

function jns_ang_bok($skpd)
{
    //  echo 'DIKA';
    $ci =& get_instance();
    $data = "SELECT jns_ang FROM bok_trhrka a WHERE a.kd_skpd='$skpd' AND a.tgl_dpa IN(SELECT MAX(tgl_dpa) FROM bok_trhrka c WHERE c.kd_skpd=a.kd_skpd AND status='1')";
    return $ci->db->query($data)->row()->jns_ang;
}

function getBulan($tanggal) {
    $pecah = explode('-', $tanggal);
    return $pecah[1];
}

?>