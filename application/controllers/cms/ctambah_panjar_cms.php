<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ctambah_panjar_cms extends CI_Controller {

    public $org_keu = "";
    public $skpd_keu = "";
    
    function __contruct()
    {   
        parent::__construct();
    }

    function index(){
        $data['page_title']= 'INPUT TAMBAH PANJAR NON TUNAI';
        $this->template->set('title', 'INPUT TAMBAH PANJAR NON TUNAI');   
        $this->template->load('template','tukd/cms/tambahpanjar_cms',$data) ; 
    }   

    function hapus_panjar_cmsbank(){
        //no:cnomor,skpd:cskpd
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql = "delete from tr_panjar_cmsbank where no_panjar='$nomor' and kd_skpd = '$skpd' and jns='2'";
        $asg = $this->db->query($sql);
        
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }

    function load_tpanjar_tgl() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        
        if ($kriteria <> ''){                               
            $where="and tgl_kas='$kriteria'";            
        }
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='2' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='2' $where order by no_panjar) and jns='2'  order by no_panjar";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],        
                        'no_panjar' => $resulte['no_panjar'],
                        'tgl_panjar' => $resulte['tgl_panjar'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai']),
                        'pay' => $resulte['pay'],
                        'status' => $resulte['status'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'lcrekening_awal' => $resulte['rekening_awal'],
                        'ketup' => $resulte['status_upload']                        
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
    }

    function load_tpanjar() {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        
        if ($kriteria <> ''){                               
            $where="and (upper(no_panjar) like upper('%$kriteria%') or tgl_panjar like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%'))";            
        }
        
        $sql = "SELECT count(*) as total from tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='2' $where " ;
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='2'  $where order by no_panjar) and jns='2' order by no_panjar";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'no_kas' => $resulte['no_kas'],
                        'tgl_kas' => $resulte['tgl_kas'],        
                        'no_panjar' => $resulte['no_panjar'],
                        'tgl_panjar' => $resulte['tgl_panjar'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'nilai' => number_format($resulte['nilai'],2),
                        'pay' => $resulte['pay'],
                        'status' => $resulte['status'],
                        'kd_kegiatan' => $resulte['kd_kegiatan'],
                        'lcrekening_awal' => $resulte['rekening_awal'],
                        'ketup' => $resulte['status_upload'],
						'ketval' => $resulte['status_validasi']                       

                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
    }


    function simpan_master_tpanjar(){
        $kd_skpd  = $this->session->userdata('kdskpd'); 
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $sqlrek = $this->input->post('sqlrek');

        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            echo '1';
        }else{
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);

            $sqlss = "insert into tr_panjar_transfercms(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)"; 
            $asg = $this->db->query($sqlss.$sqlrek); 

            if($asg){
                echo '2';
            }else{
                echo '0';
            }
        }
    }


}