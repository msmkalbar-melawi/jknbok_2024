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

    function cari_rekening(){       
        $lccr =  $this->session->userdata('kdskpd');        
        
        $sql = "SELECT top 1 rekening FROM ms_skpd where kd_skpd='$lccr' order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rek_bend' => $resulte['rekening']
                        );                        
        }
        echo json_encode($result);                        
                        
    }

    function cari_rekening_tujuan($jenis='')
    {               
        $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');

        if($jenis==1){
            $jenis = "('1','2')";
        }else{
            $jenis = "('3')";
        }       
        
        $sql = "
        SELECT * FROM (
        SELECT a.rekening,a.nm_rekening,a.bank,(select nama from ms_bank where kode=a.bank) as nmbank,
        a.keterangan,a.kd_skpd,a.jenis FROM ms_rekening_bank a where kd_skpd='$skpd') a
        WHERE upper(rekening) like upper('%$lccr%') or upper(nm_rekening) like upper('%$lccr%') or upper(bank) like upper('%$lccr%') or upper(nmbank) like upper('%$lccr%')
         order by a.nm_rekening";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'rekening' => $resulte['rekening'],     
                        'nm_rekening' => $resulte['nm_rekening'],
                        'bank' => $resulte['bank'],     
                        'nmbank' => $resulte['nmbank'],     
                        'kd_skpd' => $resulte['kd_skpd'],
                        'jenis' => $resulte['jenis'],
                        'ket' => $resulte['keterangan']
                        );                        
        }
           
        echo json_encode($result);      
    }

    function cari_bank()
    {               
        $sql = "SELECT kode,nama FROM ms_bank";
        $query1 = $this->db->query($sql);  
        $result = array();
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'kode' => $resulte['kode'],     
                        'nama' => $resulte['nama']
                        );                        
        }
           
        echo json_encode($result);      
    } 

    function no_urut_cms(){
    $kd_skpd = $this->session->userdata('kdskpd'); 
    $tgl = date('Y-m-d');
    $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_voucher nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' union
    select no_bukti nomor, 'Potongan Pajak Transaksi Non Tunai' ket, kd_skpd from trhtrmpot_cmsbank where kd_skpd = '$kd_skpd' union
    select no_panjar nomor, 'Daftar Panjar' ket, kd_skpd from tr_panjar_cmsbank where kd_skpd = '$kd_skpd' 
    ) z WHERE KD_SKPD = '$kd_skpd'");
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'no_urut' => $resulte['nomor']
                        );
                        $ii++;
        }
        
        echo json_encode($result);
        $query1->free_result();   
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
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
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
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
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