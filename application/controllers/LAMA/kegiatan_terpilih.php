<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Kegiatan_terpilih extends CI_Controller {

	function __contruct()
	{	
		parent::__construct();
  
	}
	
	function index($offset=0)
	{
		$data['page_title'] = "DETAIL KEGIATAN";
		
		$total_rows = $this->kegiatan_terpilih_model->get_count();
  
		// pagination        
 
		$config['base_url']		= site_url("kegiatan_terpilih/index");
		$config['total_rows'] 	= $total_rows;
		$config['per_page'] 	= '10';
		$config['uri_segment'] 	= 3;
		$config['num_links'] 	= 5;
		$config['full_tag_open'] = '<ul class="page-navi">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="current">';
		$config['cur_tag_close'] = '</li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$limit            		= $config['per_page'];  
		$offset         		= $this->uri->segment(3);  
		$offset         		= ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
		  
		if(empty($offset))  
		{  
			$offset=0;  
		}
	
		$data['list'] 		= $this->kegiatan_terpilih_model->getAll($limit, $offset);
		$data['num']		= $offset;
		$data['total_rows'] = $total_rows;
		
				$this->pagination->initialize($config);
		
		$this->template->set('title', 'Master Data kegiatan');
		$this->template->load('template', 'anggaran/kegiatan_terpilih/list', $data);
	}
    
    function cari()
	{
		
		$data['page_title'] = "DETAIL KEGIATAN";
		
		$data1 =  $this->input->post('nm_kegiatan');
		$total_rows = $this->kegiatan_terpilih_model->get_count_cari($data1);
  
		// pagination        
 
		$config['base_url']		= site_url("kegiatan_terpilih/cari");
		$config['total_rows'] 	= $total_rows;
		$config['per_page'] 	= '10';
		$config['uri_segment'] 	= 3;
		$config['num_links'] 	= 5;
		$config['full_tag_open'] = '<ul class="page-navi">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="current">';
		$config['cur_tag_close'] = '</li>';
		$config['prev_link'] = '&lt;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$limit            		= $config['per_page'];  
		$offset         		= $this->uri->segment(3);  
		$offset         		= ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
		  
		if(empty($offset))  
		{  
			$offset=0;  
		}
        			
        
		$data['list'] 		= $this->kegiatan_terpilih_model->cari($limit, $offset,$data1);
		$data['num']		= $offset;
		$data['total_rows'] = $total_rows;
		
				$this->pagination->initialize($config);
		
		$this->template->set('title', 'DETAIL KEGIATAN');
		$this->template->load('template', 'anggaran/kegiatan_terpilih/list_cari', $data);
	}
	
	// Tamba data
	function tambah()
	{
		
		$config = array(
               array(
                     'field'   => 'kd_skpd',
                     'label'   => 'skpd',
                     'rules'   => 'trim|required'
                    ),
                
               array(
                     'field'   => 'gt',
                     'label'   => 'Kegiatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_urusan1',
                     'label'   => 'urusan',
                     'rules'   => 'trim|required'
                  )
                  
            );
			
		$this->form_validation->set_message('required', '%s harus diisi !');
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "DETAIL KEGIATAN &raquo; Tambah";           
		    //$lc = "select kd_program,nm_program from m_prog order by kd_program";
            //$query = $this->db->query($lc);
            //$data["program"]=$query->result();
            //$data["jumrow"]=$this->db->get('m_prog')->num_rows();
		}
		else
		{
								
			$data = array(
                        'kd_gabungan'=>$this->input->post('kd_skpd').'.'.$this->input->post('kd_urusan1').'.'.$this->input->post('kd_skpd').'.'.substr($this->input->post('kd_kegiatan'),11,5),
						'kd_urusan' => $this->input->post('kd_urusan1'),
						'kd_skpd' => $this->input->post('kd_skpd'),
                        'kd_kegiatan1' => $this->input->post('gt'),
                        'kd_program1' =>substr($this->input->post('gt'),0,13),
                        'kd_program'=>$this->input->post('kd_urusan1').'.'.$this->input->post('kd_skpd').'.'.substr($this->input->post('gt'),11,2),
                        'kd_kegiatan'=>$this->input->post('kd_urusan1').'.'.$this->input->post('kd_skpd').'.'.substr($this->input->post('gt'),11,5)  
						);
			//echo $data['kd_kegiatan'];			
			$this->kegiatan_terpilih_model->save($data);
						
			$this->session->set_flashdata('notify', 'Data Berita berhasil disimpan !');
			
			redirect('kegiatan_terpilih');

		}
		
		$this->template->set('title', 'DETAIL KEGIATAN &raquo; Tambah Data');
		$this->template->load('template', 'anggaran/kegiatan_terpilih/tambah', $data);
	}
	
	// Ubah data
	function edit()
	{
		$id = $this->uri->segment(3);
		
		if ( ( $id == "" ) || ( $this->kegiatan_terpilih_model->get_by_id($id)->num_rows() <= 0 ) ) :
		
			redirect('kegiatan_terpilih');
		
		endif;
		
		$config = array(
              array(
                     'field'   => 'kd_skpd',
                     'label'   => 'skpd',
                     'rules'   => 'trim|required'
                    ),
                
               array(
                     'field'   => 'kd_kegiatan',
                     'label'   => 'Kegiatan',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'kd_urusan1',
                     'label'   => 'urusan',
                     'rules'   => 'trim|required'
                  )
            );
			
		$this->form_validation->set_message('required', '%s harus diisi !');
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<div class="single_error">', '</div>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = "DETAIL KEGIATAN &raquo; Ubah Data";
			$data['kegiatan'] = $this->kegiatan_terpilih_model->get_by_id($id)->row();
            //$lc = "select kd_program,nm_program from m_prog order by kd_program";
            //$query = $this->db->query($lc);
            //$data["program"]=$query->result();
            //$data["jumrow"]=$this->db->get('m_prog')->num_rows();
  
            
            
   		}
		else
		{
								
			$data = array(
						'kd_gabungan'=>$this->input->post('kd_skpd').'.'.$this->input->post('kd_urusan1').'.'.$this->input->post('kd_skpd').'.'.substr($this->input->post('kd_kegiatan'),11,5),
						'kd_urusan' => $this->input->post('kd_urusan1'),
						'kd_skpd' => $this->input->post('kd_skpd'),
                        'kd_kegiatan1' => $this->input->post('kd_kegiatan'),
                        'kd_program1' =>substr($this->input->post('kd_kegiatan'),0,13),
                        'kd_program'=>$this->input->post('kd_urusan1').'.'.$this->input->post('kd_skpd').'.'.substr($this->input->post('kd_kegiatan'),11,2),
                        'kd_kegiatan'=>$this->input->post('kd_urusan1').'.'.$this->input->post('kd_skpd').'.'.substr($this->input->post('kd_kegiatan'),11,5)
						);
						
			$this->kegiatan_terpilih_model->update($id, $data);
						
			$this->session->set_flashdata('notify', 'Data Berita berhasil diupdate !');
			
			redirect('kegiatan_terpilih');

		}
		
		$this->template->set('title', 'DETAIL KEGIATAN &raquo; Ubah Data');
		$this->template->load('template', 'anggaran/kegiatan_terpilih/edit', $data);
	}
	
	// hapus data
	function hapus()
	{
		$id = $this->uri->segment(3);
		
		if ( ( $id == "" ) || ( $this->kegiatan_terpilih_model->get_by_id($id)->num_rows() <= 0 ) ) :
		
			redirect('kegiatan_terpilih');
		
		else:
						
			$this->kegiatan_terpilih_model->delete($id);
						
			$this->session->set_flashdata('notify', 'Data berhasil dihapus !');
			
			redirect('kegiatan_terpilih');
			
		endif;
	}
    
    function preview(){
        $cRet='';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>
                        <tr><td colspan=\"4\" style=\"text-align:center;border: solid 1px white;border-bottom:solid 1px black;\">MASTER KEGIATAN</td></tr> 
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE SKPD</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE URUSAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KODE KEGIATAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NAMA KEGIATAN</b></td></tr>
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                         </tr>
                     </tfoot>
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"60%\">&nbsp;</td></tr>
                        ";
                 
                 //$query = $this->db->query('SELECT kd_fungsi,nm_fungsi FROM ms_fungsi');
                 $query = $this->kegiatan_terpilih_model->getAllc();

                foreach ($query->result() as $row)
                {
                    $coba1=$row->kd_skpd;
                    $coba2=$row->kd_urusan;
                    $coba3=$row->giat;
                    $coba4=$row->nm_kegiatan;
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$coba1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\">$coba3</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$coba4</td></tr>";
                }
              
        $cRet    .= "</table>";
        $data['prev']= $cRet;    
        $this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        
                
    }
    
    function _mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font=12,$orientasi='') {
        
        ini_set("memory_limit","512M");
        $this->load->library('mpdf');
        
        /*
        $this->mpdf->progbar_altHTML = '<html><body>
	                                    <div style="margin-top: 5em; text-align: center; font-family: Verdana; font-size: 12px;"><img style="vertical-align: middle" src="'.base_url().'images/loading.gif" /> Creating PDF file. Please wait...</div>';        
        $this->mpdf->StartProgressBarOutput();
        */
        
        $this->mpdf->defaultheaderfontsize = 6;	/* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6;	/* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;	/* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1; 
        
        //$this->mpdf->SetHeader('SIMAKDA||');
        $jam = date("H:i:s");
        //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Simakda| Page {PAGENO} of {nb}');
        $this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');
        
        $this->mpdf->AddPage($orientasi);
        
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
         
        $this->mpdf->Output();
    }

}