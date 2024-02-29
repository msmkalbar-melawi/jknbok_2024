    <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    class Rka_rancang extends CI_Controller {

    public $ppkd = "4.02.02";
    public $ppkd1 = "4.02.02.02";
    public $keu1 = "4.02.02.01";

    public $kdbkad="5-02.0-00.0-00.02.01";


    public $ppkd_lama = "4.02.02";
    public $ppkd1_lama = "4.02.02.02";

        function __contruct()
        {   
            parent::__construct();
        }

        function index($offset=0,$lctabel,$field,$field1,$judul,$list,$lccari)
        {
            $data['page_title'] = "CETAK $judul";
            
            //$total_rows = $this->master_model->get_count($lctabel);
            if(empty($lccari)){
                $total_rows = $this->master_model->get_count($lctabel);
                $lc = "/.$lccari";
            }else{
                $total_rows = $this->master_model->get_count_teang($lctabel,$field,$field1,$lccari);
                $lc = "";
            }
            // pagination        
            $config['base_url']     = site_url("rka/".$list);
            $config['total_rows']   = $total_rows;
            $config['per_page']     = '10';
            $config['uri_segment']  = 3;
            $config['num_links']    = 5;
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
            $limit                  = $config['per_page'];  
            $offset                 = $this->uri->segment(3);  
            $offset                 = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
              
            if(empty($offset))  
            {  
                $offset=0;  
            }
                   
            //$data['list']         = $this->master_model->getAll($lctabel,$field,$limit, $offset);
             if(empty($lccari)){     
            $data['list']       = $this->master_model->getSkpd($lctabel,$field,$limit, $offset);
            }else {
                $data['list']       = $this->master_model->getCari($lctabel,$field,$field1,$limit, $offset,$lccari);
            }
            $data['num']        = $offset;
            $data['total_rows'] = $total_rows;
            
                    $this->pagination->initialize($config);
            $a=$judul;
            $data['sikap'] = 'list';
            $this->template->set('title', 'CETAK '.$judul);
            $this->template->load('template', "anggaran/rka/".$list, $data);
        }

    function tambah_rka_penyusunan()
        {
              $jk   = $this->rka_model->combo_skpd();
            $ry   =  $this->rka_model->combo_giat();
            $cRet = '';
            
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
                       <tr >                       
                            <td>Input Anggaran Penyusunan $jk</td>
                            <td>$ry</td>
                            </tr>
                      ";
             
            $cRet .="</table>";
            $data['prev']= $cRet;
            $data['page_title']= 'Input RKA Penyusunan';
            $this->template->set('title', 'Input RKA Penyusunan');   
             $sql = "SELECT a.kd_rek6,b.nm_rek6,a.nilai,a.nilai as total from trdrka_rancang a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6";                   
            
            $query1 = $this->db->query($sql);  
            $results = array();
            $i = 1;
            foreach($query1->result_array() as $resulte)
            { 
                $results[] = array(
                           'id' => $i,
                            'kd_rek5' => $resulte['kd_rek6'],  
                            'nm_rek5' => $resulte['nm_rek6'],  
                            'nilai' => $resulte['nilai'] ,
                            'total' => $resulte['total']                            
                            );
                            $i++;
            }
            $this->template->load('template','anggaran/rka/penyusunan/tambah_rka_penyusunan',$data) ; 
            $query1->free_result();
       }

    function select_rka_rancang($kegiatan='',$skpd='') {

            $sql = "SELECT DISTINCT a.kd_rek6,b.nm_rek6,a.nilai,a.nilai_sempurna,a.nilai_ubah,a.sumber,a.sumber2,a.sumber3,a.sumber4,a.nilai_sumber,a.nilai_sumber2
                    ,a.nilai_sumber3,a.nilai_sumber4 from trdrka_rancang a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6 join 
                    trskpd_rancang c on a.kd_sub_kegiatan=c.kd_sub_kegiatan
                    where a.kd_sub_kegiatan='$kegiatan' and a.kd_skpd='$skpd' /*and left(a.kd_rek6,4) 
                    not in ('5101') */ order by a.kd_rek6 ";                   
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_rek5' => $resulte['kd_rek6'],  
                            'nm_rek5' => $resulte['nm_rek6'],  
                            'nilai' => number_format($resulte['nilai'],"2",".",","),
                            'nilai_sempurna' => number_format($resulte['nilai_sempurna'],"2",".",","),
                            'nilai_ubah' => number_format($resulte['nilai_ubah'],"2",".",","),                             
                            'sumber' => $resulte['sumber'],
                            'sumber2' => $resulte['sumber2'],
                            'sumber3' => $resulte['sumber3'],
                            'sumber4' => $resulte['sumber4'],                                
                            'nilai_sumber' => number_format($resulte['nilai_sumber'],"2",".",","), 
                            'nilai_sumber2' => number_format($resulte['nilai_sumber2'],"2",".",","), 
                            'nilai_sumber3' => number_format($resulte['nilai_sumber3'],"2",".",","),
                            'nilai_sumber4' => number_format($resulte['nilai_sumber4'],"2",".",",")                                

                            );
                            $ii++;
            }
               
               echo json_encode($result);
                $query1->free_result();
        }

       function load_daftar_harga_detail_ck() {
           
            $cari       = $this->input->post('cari') ;
            $rekening   = $this->input->post('rekening');

            $rektetap   = substr($rekening,0,2);

            $reklancar   = substr($rekening,0,4);

          
                $sql    = "SELECT * from ms_standar_harga where (left(kd_rek6,2)='$rektetap' OR kd_rek6='' OR kd_rek6='$rekening') and (upper(uraian) like upper('%$cari%') or upper(merk) like upper('%$cari%')) order by id ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii     = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                            'no_urut'         => $ii,        
                            'id'         => $resulte['id'],
                            'kd_barang'  => $resulte['kd_barang'],
                            'kd_rek6'    => $resulte['kd_rek6'],
                            'uraian'     => $resulte['uraian'],
                            'merk'       => $resulte['merk'],
                            'satuan'     => $resulte['satuan'],
                            //'harga'      => number_format($resulte['harga'],"2",".",","),
                            'harga'      => $resulte['harga'],
                            'keterangan' => $resulte['keterangan'],
                            'ck'         => $resulte['kd_barang']

                            );
                            $ii++;
            }
            echo json_encode($result);
        }

     function ambil_sdana(){

            $skpd     = $this->session->userdata('kdskpd');
            $kd_skpd  = $this->input->post('kdskpd');
            $lccr  = '';//$this->input->post('q');
            // $query1 = $this->db->query("select * from ms_dana where upper(kd_sdana) like upper('%$lccr%') or upper(nm_sdana) like upper('%$lccr%') ") ;
            $query1 = $this->db->query("SELECT a.kd_sumberdana,a.nm_sumberdana,(nilai+nilaisilpa) -
                                        (select 
                                        (select isnull(sum(nilai_sumber),0)as k from trdrka_rancang 
                                        where sumber=a.nm_sumberdana and left(kd_rek6,1) <> '4' and left(kd_rek6,2) <>'61' /* and left(kd_rek6,4) <>'5101' */)+
                                        (select isnull(sum(nilai_sumber2),0)as m from trdrka_rancang 
                                        where sumber2=a.nm_sumberdana and left(kd_rek6,1) <> '4' and left(kd_rek6,2) <>'61' /* and left(kd_rek6,4) <>'5101' */)+
                                        (select isnull(sum(nilai_sumber3),0)as n from trdrka_rancang 
                                        where sumber3=a.nm_sumberdana and left(kd_rek6,1) <> '4' and left(kd_rek6,2) <>'61' /* and left(kd_rek6,4) <>'5101' */)+
                                        (select isnull(sum(nilai_sumber4),0)as v from trdrka_rancang 
                                        where sumber4=a.nm_sumberdana and left(kd_rek6,1) <> '4' and left(kd_rek6,2) <>'61' /* and left(kd_rek6,4) <>'5101' */) )as sisa
                                        from hsumber_dana a inner join dsumber_dana b on a.kd_sumberdana =b.kd_sumberdana where kd_skpd in ('$kd_skpd','all') 
                                        and (upper(a.kd_sumberdana) like upper('%%') or upper(a.nm_sumberdana) like upper('%%'))") ;
            //$query1 = $this->db->query("select kd_sdana, nm_sdana from ms_dana") ;
            
            
            $ii     = 0;
            $result = array();
            foreach ($query1->result_array() as $resulte) {
                
                $result[] = array(
                    'id'        => '$ii',
                    'kd_sdana'  => $resulte['kd_sumberdana'],
                    'nm_sdana'  => $resulte['nm_sumberdana'],
                    'nilai'     => $resulte['sisa']
                    );
                    $ii++;    
            }
            
            echo json_encode($result) ;
        }


        function config_skpd(){
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd,a.nm_skpd,b.status_rancang,b.status,b.status_sempurna,b.status_ubah FROM  ms_skpd a LEFT JOIN trhrka b ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);  
        $test = $query1->num_rows();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'statu' => $resulte['status']
                        );
                        $ii++;
        }

        echo json_encode($result);
        $query1->free_result();   
    }



        function skpduser() {
            $lccr = $this->input->post('q');
            $id  = $this->session->userdata('kdskpd');
            $sql = "SELECT a.kd_skpd,a.nm_skpd,a.jns FROM  ms_skpd a LEFT JOIN trhrka b ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$id'";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],  
                            'nm_skpd' => $resulte['nm_skpd'],
                            'jns' => $resulte['jns']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }



        function load_sum_rek_rinci_rka_rancang(){

            $kdskpd = $this->input->post('skpd');
            $kegiatan = $this->input->post('keg');
            $rek = $this->input->post('rek');
            $norka=$kdskpd.'.'.$kegiatan.'.'.$rek;

            $query1 = $this->db->query("SELECT nilai, nilai_sempurna, nilai_ubah FROM trdrka_rancang where no_trdrka='$norka' ");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'rektotal_rka' => number_format($resulte['nilai'],"2",".",","),
                            'rektotal_rka_sempurna' => number_format($resulte['nilai_sempurna'],"2",".",","),
                            'rektotal_rka_ubah' => number_format($resulte['nilai_ubah'],"2",".",","),
                            
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);
               
             }



             function config_skpd2(){
            $skpd     =  $this->input->post('kdskpd');
            $sql = "SELECT a.kd_skpd,a.nm_skpd,b.status,b.status_sempurna,b.status_ubah,b.status_rancang FROM  ms_skpd a LEFT JOIN trhrka b ON 
                    a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' ";
            $query1 = $this->db->query($sql);  
            
            $test = $query1->num_rows();
            
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],
                            'nm_skpd' => $resulte['nm_skpd'],
                            'statu' => $resulte['statu'],
                            'status_sem' => $resulte['status_sempurna'],
                            'status_ubah' => $resulte['status_ubah'],
                            'status_rancang' => $resulte['status_rancang']
                            );
                            $ii++;
            }
            

            
            
            echo json_encode($result);
            $query1->free_result();   
        }


        function load_nilai_kua_rancang($cskpd=''){
                    
            $query1 = $this->db->query("SELECT a.nilai_kua, 
    (SELECT SUM(nilai) FROM trdrka_rancang WHERE LEFT(kd_rek6,1)='5' /* and left(kd_rek6,4)<>'5101' */ AND kd_skpd = a.kd_skpd) as nilai_ang,
    (SELECT SUM(nilai_sempurna) FROM trdrka_rancang WHERE LEFT(kd_rek6,1)='5' /* and left(kd_rek6,4)<>'5101' */ AND kd_skpd = a.kd_skpd) as nilai_angg_sempurna,
    (SELECT SUM(nilai_ubah) FROM trdrka_rancang WHERE LEFT(kd_rek6,1)='5' /* and left(kd_rek6,4)<>'5101' */ AND kd_skpd = a.kd_skpd) as nilai_angg_ubah
    FROM ms_skpd a where a.kd_skpd='$cskpd'");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                            'id' => $ii,        
                            //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                            'nilai' => number_format($resulte['nilai_kua'],2,'.',','),                      
                            'kua_terpakai' => number_format($resulte['nilai_ang'],2,'.',','),
                            'kua_terpakai_sempurna' => number_format($resulte['nilai_angg_sempurna'],2,'.',','),                       
                            'kua_terpakai_ubah' => number_format($resulte['nilai_angg_ubah'],2,'.',',')  
                            );
                            $ii++;
            }
           
               echo json_encode($result);
               $query1->free_result();  
        }



         function cek_kas(){
            
            $skpd     = $this->input->post('skpd');
            $kegiatan = $this->input->post('kegiatan');
            
            $result   = array();
            $query    = $this->db->query("SELECT * from trdskpd where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan'");
            //$query    = $this->db->query("select * from trdskpd where kd_skpd='1.03.01.00' and kd_kegiatan='1.03.1.03.01.00.18.04' ");
            $ii       = 0;
            
            foreach ( $query->result_array() as $row ){
                
                $result[] = array(
                    'id'    =>  '$ii',
                    'bulan' =>  $row['bulan'],
                    'nilai' =>  $row['nilai']
                );
                $ii++;
            }
            echo json_encode($result);
        }


        function cek_header(){
            
            $no_trdrka     = $this->input->post('notrdrka');
            $result   = array();
            $query    = $this->db->query("SELECT a.no_trdrka,CONVERT(DECIMAL,isnull(sum(a.nilai)-(select sum(b.total) from trdpo_temp b where a.no_trdrka=b.no_trdrka),0),2) as selisih 
                                        from trdrka a where a.no_trdrka='$no_trdrka'
                                        GROUP BY a.no_trdrka");
            $ii       = 0;
            foreach ( $query->result_array() as $row ){
                
                $result[] = array(
                    'id'    =>  '$ii',
                    'selisih' =>  $row['selisih']
                );
                $ii++;
            }
            echo json_encode($result);
        }


           function pgiat_rancang($cskpd='') {
            
            $lccr = $this->input->post('q');
            $sql  = " SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,b.jns_sub_kegiatan,status_keg FROM trskpd_rancang a INNER JOIN     ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    where a.kd_skpd='$cskpd' and status_sub_kegiatan='1' and ( upper(a.kd_kegiatan) like upper('%$lccr%') or upper(a.nm_kegiatan) like upper('%$lccr%') ) order by a.kd_kegiatan";
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii     = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                            'id' => $ii,        
                            'kd_kegiatan'  => $resulte['kd_sub_kegiatan'],  
                            'nm_kegiatan'  => $resulte['nm_sub_kegiatan'],
                            'jns_kegiatan' => $resulte['jns_sub_kegiatan'],
                            'status_keg'   => $resulte['status_keg']
                            );
                            $ii++;
            }
            echo json_encode($result);
               
        }



        function ambil_rekening5_all_ar() {
            $kd_skpd  = $this->session->userdata('kdskpd');
            $lccr    = $this->input->post('q');
            $notin   = $this->input->post('reknotin');
            $jnskegi = $this->input->post('jns_kegi');
            
            if ( $notin <> ''){
                $where = " and kd_rek5 not in ($notin) ";
            } else {
                $where = " ";
            }
            
            if ( $jnskegi =='4' ) {
                $sql = "SELECT a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 from ms_rek6 a
                        left join ms_rekening b on a.kd_rek6=b.kd_rek6
                        where left(a.kd_rek6,1)='4'
                        and (upper(b.kd_rek13) like upper('%$lccr%') or upper(a.kd_rek6) like upper('%$lccr%') or upper(b.nm_rek13) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%'))
                        group by a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 order by a.kd_rek6";
            } else if($jnskegi=='61'){
                            $sql = "SELECT a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 from ms_rek6 a
                        left join ms_rekening b on a.kd_rek6=b.kd_rek6
                        where left(a.kd_rek6,2)='61'
                        and (upper(b.kd_rek13) like upper('%$lccr%') or upper(a.kd_rek6) like upper('%$lccr%') or upper(b.nm_rek13) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%'))
                        group by a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 order by a.kd_rek6";                    
            }else if($jnskegi=='62'){
                    $sql = "SELECT a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 from ms_rek6 a
                        left join ms_rekening b on a.kd_rek6=b.kd_rek6
                        where left(a.kd_rek6,2)='62' and (upper(b.kd_rek13) like upper('%$lccr%') or upper(a.kd_rek6) like upper('%$lccr%') or upper(b.nm_rek13) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) group by a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 order by a.kd_rek6";                                        
            }else if($jnskegi='5' and $kd_skpd='$kdbkad'){
                    $sql = "SELECT a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 from ms_rek6 a
                        left join ms_rekening b on a.kd_rek6=b.kd_rek6
                        where left(a.kd_rek6,1)='5' and (upper(b.kd_rek13) like upper('%$lccr%') or upper(a.kd_rek6) like upper('%$lccr%') or upper(b.nm_rek13) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) group by a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 order by a.kd_rek6";
            }else{
                    $sql = "SELECT a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 from ms_rek6 a
                        left join ms_rekening b on a.kd_rek6=b.kd_rek6
                        where /* left(a.kd_rek6,4)<>'5101' and */ left(a.kd_rek6,1)='5' and (upper(b.kd_rek13) like upper('%$lccr%') or upper(a.kd_rek6) like upper('%$lccr%') or upper(b.nm_rek13) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) group by a.kd_rek6,a.nm_rek6,b.kd_rek13,b.nm_rek13 order by a.kd_rek6";
            }
            
            $query1 = $this->db->query($sql); 
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                            'id' => $ii,        
                            'kd_rek5' => $resulte['kd_rek13'],  
                            'nm_rek5' => $resulte['nm_rek13'],
                            'kd_rek6' => $resulte['kd_rek6'],  
                            'nm_rek6' => $resulte['nm_rek6']
                            );
                            $ii++;
            }
            echo json_encode($result);
        }


        function tsimpan_rancang($skpd='',$kegiatan='',$rekbaru='',$reklama='',$nilai=0,$sdana='') {
           
            if (trim($reklama)==''){
                $reklama=$rekbaru;
            }

            $nmskpd=$this->rka_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
            $nmgiat=$this->rka_model->get_nama($kegiatan,'nm_kegiatan','trskpd','kd_kegiatan');
            $nmrek5=$this->rka_model->get_nama($rekbaru,'nm_rek5','ms_rek5','kd_rek5');

            $notrdrka=$skpd.'.'.$kegiatan.'.'.$rekbaru;
            $query = $this->db->query(" delete from trdrka_rancang where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan' and kd_rek6='$reklama' ");
            $query = $this->db->query(" insert into trdrka_rancang(no_trdrka,kd_skpd,kd_sub_kegiatan,kd_rek6,nilai,nilai_ubah,sumber,nm_skpd,nm_rek5,nm_sub_kegiatan) values('$notrdrka','$skpd','$kegiatan','$rekbaru',$nilai,$nilai,'$sdana','$nmskpd','$nmrek5','$nmgiat') ");   
            $query = $this->db->query(" update trskpd_rancang set total=( select sum(nilai) as jum from trdrka_rancang where 
                                        kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ),TK_MAS=( select sum(nilai) as jum from trdrka_rancang 
                                        where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ),TU_MAS='Dana' where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' "); 

            $query = $this->db->query(" delete from trdrka where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan' and kd_rek5='$reklama' ");
            $query = $this->db->query(" insert into trdrka(no_trdrka,kd_skpd,kd_sub_kegiatan,kd_rek5,nilai,nilai_sempurna,nilai_akhir_sempurna,nilai_ubah,sumber,
                                        nm_skpd,nm_rek5,nm_sub_kegiatan) values('$notrdrka','$skpd','$kegiatan','$rekbaru',$nilai,$nilai,$nilai,$nilai,'$sdana'
                                        ,'$nmskpd','$nmrek5','$nmgiat') "); 
            $query = $this->db->query(" update trskpd set total=( select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ),
                                        TK_MAS=( select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ),TU_MAS='Dana' 
                                        where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ");   
            if($skpd==$this->ppkd1_lama){
                $this->select_rka_alokasidana_penetapan($kegiatan);
            }else{
              $this->select_rka_rancang_penetapan($kegiatan);
            }
        }   


        function ld_rek_rancang($giat='',$rek='') {
            if ($rek==''){
                $dan='';
            }else{
                $dan="and a.kd_rek5='$rek'";
            }
            $sql = " SELECT kd_rek5,nm_rek5 FROM (SELECT kd_rek5,nm_rek5 FROM ms_rek5 WHERE kd_rek5 NOT IN (SELECT kd_rek5 FROM trdrka_rancang WHERE kd_kegiatan='$giat'  ) AND
                     (kd_rek5 LIKE '4%' OR kd_rek5 LIKE '5%' OR kd_rek5 LIKE '6%'))a,
                     (SELECT @kd:=b.jns_kegiatan, @pj:=len(b.jns_kegiatan) FROM trskpd_rancang a INNER JOIN m_giat b ON 
                     a.kd_kegiatan1=b.kd_kegiatan WHERE a.kd_kegiatan='$giat' )t 
                     WHERE LEFT(a.kd_rek5,@pj)=@kd $dan ORDER BY a.kd_rek5 ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_rek5' => $resulte['kd_rek5'],  
                            'nm_rek5' => $resulte['nm_rek5']
                           
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }

    function thapus_rancang($skpd='',$kegiatan='',$rek='') {
            
            $notrdrka=$skpd.'.'.$kegiatan.'.'.$rek;
            $query = $this->db->query(" delete from trdrka_rancang where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan' and kd_rek6='$rek' ");
            $query = $this->db->query(" delete from trdpo_rancang where no_trdrka='$notrdrka' ");
            $query = $this->db->query(" update trskpd_rancang set total=( select sum(nilai) as jum from trdrka_rancang where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ) where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ");   


            $query = $this->db->query(" delete from trdrka where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan' and kd_rek6='$rek' ");
            $query = $this->db->query(" delete from trdpo where no_trdrka='$notrdrka' ");
            $query = $this->db->query(" update trskpd set total=( select sum(nilai) as jum from trdrka where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ) where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' ");   
            $this->select_rka_rancang($kegiatan);
        }


    function tsimpan_rinci_rancang(){

            $skpd    = $this->input->post('skpd');
            $kegiatan    = $this->input->post('giat');
            $rekening    = $this->input->post('rek');
            $index    = $this->input->post('id');
            $uraian    = $this->input->post('uraian');
            $volume1    = $this->input->post('volum1');
            $satuan1    = $this->input->post('satuan1');
            $harga1    = $this->input->post('harga1');
            $volume2    = $this->input->post('volum2');
            $satuan2    = $this->input->post('satuan2');
            $volume3    = $this->input->post('volum3');
            $satuan3    = $this->input->post('satuan3');
            
            $satuan1 = str_replace("12345678987654321","",$satuan1);
            $satuan1 = str_replace("undefined","",$satuan1);

            $satuan2 = str_replace("12345678987654321","",$satuan2);
            $satuan2 = str_replace("undefined","",$satuan2);

            $satuan3 = str_replace("12345678987654321","",$satuan3);
            $satuan3 = str_replace("undefined","",$satuan3);

            $uraian = str_replace("%20"," ",$uraian);
            $uraian = str_replace("%60"," ",$uraian);

            $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
            $vol1=$volume1;
            $vol2=$volume2;
            $vol3=$volume3;
            if($volume1==0){$volume1=1;$vol1='';}
            if($volume2==0){$volume2=1;$vol2='';}
            if($volume3==0){$volume3=1;$vol3='';}
            
            $total   = $volume1*$volume2*$volume3*$harga1;


            $query1 = $this->db->query(" delete from trdpo_rancang where no_po='$index' and no_trdrka='$norka' ");  
            $query1 = $this->db->query(" insert into trdpo_rancang(no_po,no_trdrka,uraian,volume1,satuan1,harga1,total,volume_ubah1,satuan_ubah1,harga_ubah1,
                                         total_ubah,volume2,satuan2,volume_ubah2,satuan_ubah2,volume3,satuan3,volume_ubah3,satuan_ubah3) 
                                         values('$index','$norka','$uraian','$vol1','$satuan1',$harga1,$total,'$vol1','$satuan1',$harga1,$total,'$vol2',
                                         '$satuan2','$vol2','$satuan2','$vol3','$satuan3','$vol3','$satuan3') ");  
            $query1 = $this->db->query(" update trdrka_rancang set nilai= (select sum(total) as nl from trdpo_rancang where no_trdrka=trdrka_rancang.no_trdrka),
                                        nilai_ubah=(select sum(total) as nl from trdpo_rancang where no_trdrka=trdrka_rancang.no_trdrka) where no_trdrka='$norka' ");  
            $query1 = $this->db->query(" update trskpd set total= (select sum(nilai) as jum from trdrka_rancang where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ) 
                                        where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ");   


            $query1 = $this->db->query(" delete from trdpo where no_po='$index' and no_trdrka='$norka' ");  
            $query1 = $this->db->query(" insert into trdpo(no_po,no_trdrka,uraian,volume1,satuan1,harga1,total,volume_ubah1,satuan_ubah1,harga_ubah1,total_ubah,volume2,satuan2,volume_ubah2,satuan_ubah2,volume3,satuan3,volume_ubah3,satuan_ubah3) 
                                         values('$index','$norka','$uraian','$vol1','$satuan1',$harga1,$total,'$vol1','$satuan1',$harga1,$total,'$vol2','$satuan2','$vol2','$satuan2','$vol3','$satuan3','$vol3','$satuan3') ");  
            $query1 = $this->db->query(" update trdrka set nilai= (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),nilai_ubah=(select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka) where no_trdrka='$norka' ");  
            $query1 = $this->db->query(" update trskpd set total= (select sum(nilai) as jum from trdrka where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ) where kd_kegiatan='$kegiatan' and kd_skpd='$skpd' ");   
            $this->rka_rinci_rancang($skpd,$kegiatan,$rekening);
        }

    function sumber(){
            $tabel  = $this->input->post('tabel');
            $field  = $this->input->post('field');

            $cgiat  = $this->input->post('cgiat');
            $crek   = $this->input->post('crek');
            $cskpd  = $this->input->post('cskpd');

            $sdana  = $this->input->post('sdana');
            $ndana  = $this->input->post('ndana');
            $hasil=0;
            $sql        = "SELECT
                                sumber,isnull(nilai_sumber,0)as nilai_sumber
                                from trdrka_rancang 
                                where kd_skpd='$cskpd' 
                                and kd_sub_kegiatan='$cgiat' 
                                and kd_rek6='$crek'";

            $querylalu   = $this->db->query($sql);
            $lalu        = $querylalu->row();
            $nilai_lalu  = $lalu->nilai_sumber;



            $sqlsumber      = "SELECT isnull(sisa,0)as sisa FROM $tabel where $field='$sdana'";
            $querysumber    = $this->db->query($sqlsumber);
            $sumber         = $querysumber->row();
            $nilai_sumber   = $sumber->sisa;

            $hasil = (($nilai_lalu)+($nilai_sumber))-($ndana);
            
            if ( $hasil >= 0 ){
               $msg = array('pesan'=>'1');
                            
            } else {
                $msg = array('pesan'=>'0');
            }


            echo json_encode($msg);
            
        }

        function sumber2(){
            $tabel  = $this->input->post('tabel');
            $field  = $this->input->post('field');

            $cgiat  = $this->input->post('cgiat');
            $crek   = $this->input->post('crek');
            $cskpd  = $this->input->post('cskpd');

            $sdana  = $this->input->post('sdana');
            $ndana  = $this->input->post('ndana');
            $hasil=0;
            $sql        = "SELECT
                                sumber2,isnull(nilai_sumber2,0)as nilai_sumber
                                from trdrka_rancang 
                                where kd_skpd='$cskpd' 
                                and kd_sub_kegiatan='$cgiat' 
                                and kd_rek6='$crek'";

            $querylalu   = $this->db->query($sql);
            $lalu        = $querylalu->row();
            $nilai_lalu  = $lalu->nilai_sumber;



            $sqlsumber      = "SELECT isnull(sum(sisa),0)as sisa FROM $tabel where $field='$sdana'";
            $querysumber    = $this->db->query($sqlsumber);
            $sumber         = $querysumber->row();
            $nilai_sumber   = $sumber->sisa;

            $hasil = (($nilai_lalu)+($nilai_sumber))-($ndana);
            
            if ( $hasil >= 0 ){
               $msg = array('pesan'=>'1');
                            
            } else {
                $msg = array('pesan'=>'0');
            }


            echo json_encode($msg);
            
        }

        function sumber3(){
            $tabel  = $this->input->post('tabel');
            $field  = $this->input->post('field');

            $cgiat  = $this->input->post('cgiat');
            $crek   = $this->input->post('crek');
            $cskpd  = $this->input->post('cskpd');

            $sdana  = $this->input->post('sdana');
            $ndana  = $this->input->post('ndana');
            $hasil=0;
            $sql        = "SELECT
                                sumber3,isnull(nilai_sumber3,0)as nilai_sumber
                                from trdrka_rancang 
                                where kd_skpd='$cskpd' 
                                and kd_sub_kegiatan='$cgiat' 
                                and kd_rek6='$crek'";

            $querylalu   = $this->db->query($sql);
            $lalu        = $querylalu->row();
            $nilai_lalu  = $lalu->nilai_sumber;



            $sqlsumber      = "SELECT isnull(sisa,0)as sisa FROM $tabel where $field='$sdana'";
            $querysumber    = $this->db->query($sqlsumber);
            $sumber         = $querysumber->row();
            $nilai_sumber   = $sumber->sisa;

            $hasil = (($nilai_lalu)+($nilai_sumber))-($ndana);
            
            if ( $hasil >= 0 ){
               $msg = array('pesan'=>'1');
                            
            } else {
                $msg = array('pesan'=>'0');
            }


            echo json_encode($msg);
            
        }

        function sumber4(){
            $tabel  = $this->input->post('tabel');
            $field  = $this->input->post('field');

            $cgiat  = $this->input->post('cgiat');
            $crek   = $this->input->post('crek');
            $cskpd  = $this->input->post('cskpd');

            $sdana  = $this->input->post('sdana');
            $ndana  = $this->input->post('ndana');
            $hasil=0;
            $sql        = "SELECT
                                sumber4,isnull(nilai_sumber4,0)as nilai_sumber
                                from trdrka_rancang 
                                where kd_skpd='$cskpd' 
                                and kd_sub_kegiatan='$cgiat' 
                                and kd_rek6='$crek'";

            $querylalu   = $this->db->query($sql);
            $lalu        = $querylalu->row();
            $nilai_lalu  = $lalu->nilai_sumber;



            $sqlsumber      = "SELECT isnull(sisa,0)as sisa FROM $tabel where $field='$sdana'";
            $querysumber    = $this->db->query($sqlsumber);
            $sumber         = $querysumber->row();
            $nilai_sumber   = $sumber->sisa;

            $hasil = (($nilai_lalu)+($nilai_sumber))-($ndana);
            
            if ( $hasil >= 0 ){
               $msg = array('pesan'=>'1');
                            
            } else {
                $msg = array('pesan'=>'0');
            }


            echo json_encode($msg);
            
        }

    function thapus_rinci_ar_all_rancang(){
            $norka = $this->input->post('vnorka');
            $query = $this->db->query("delete from trdpo_rancang where no_trdrka='$norka'");
            $query = $this->db->query("delete from trdpo where no_trdrka='$norka'");
     
            if ( $query > 0 ){
                echo '1' ;
            } else {
                echo '0' ;
            }
        }


    function tsimpan_rinci_jk_rancang(){
            
            $norka     = $this->input->post('no');
            $csql      = $this->input->post('sql');
            $cskpd     = $this->input->post('skpd');
            $kegiatan  = $this->input->post('giat'); 
            $rekening  = $this->input->post('rek');
            $id        = $this->session->userdata('pcNama');
            $sdana1 = $this->input->post('dana1');
            $sdana2 = $this->input->post('dana2');
            $sdana3 = $this->input->post('dana3');
            $sdana4 = $this->input->post('dana4');
            $ndana1 = $this->input->post('vdana1');
            $ndana2 = $this->input->post('vdana2');
            $ndana3 = $this->input->post('vdana3');
            $ndana4 = $this->input->post('vdana4');
                                  
            $sql       = "delete from trdpo where  no_trdrka='$norka'";
            $asg       = $this->db->query($sql);

            $sql       = "delete from trdpo_rancang where  no_trdrka='$norka'";
            $asg2       = $this->db->query($sql);
            
                    if (!($asg) && !($asg2)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    }else{            
                        $sql1 = "insert into trdpo_rancang 
                                select * from trdpo_rancang_temp where no_trdrka='$norka'"; 
                        $asg1 = $this->db->query($sql1);


                        $sql = "insert into trdpo 
                                select * from trdpo_temp where no_trdrka='$norka'"; 
                        $asg = $this->db->query($sql);

                        if (!($asg1)){
                           $msg = array('pesan'=>'0');
                            echo json_encode($msg);
                        }  else {
                           $msg = array('pesan'=>'1');
                            echo json_encode($msg);
                        }

                        if (!($asg)){
                           $msg = array('pesan'=>'0');
                            echo json_encode($msg);
                        }  else {
                           $msg = array('pesan'=>'1');
                            echo json_encode($msg);
                        }
                    }
          
            
            $query1 = $this->db->query(" update trdrka_rancang set nilai= (select sum(total) as nl from trdpo_rancang where no_trdrka=trdrka_rancang.no_trdrka),
                                        nilai_sempurna= (select sum(total) as nl from trdpo_rancang where no_trdrka=trdrka_rancang.no_trdrka),
                                        nilai_ubah=(select sum(total) as nl from trdpo_rancang where no_trdrka=trdrka_rancang.no_trdrka),username='$id',
                                        last_update=getdate(),sumber='$sdana1',sumber2='$sdana2',sumber3='$sdana3',sumber4='$sdana4',nilai_sumber=$ndana1,
                                        nilai_sumber2=$ndana2,nilai_sumber3=$ndana3,nilai_sumber4=$ndana4
                                        where no_trdrka='$norka' ");  
            $query1 = $this->db->query("update trskpd_rancang set total= (select sum(nilai) as jum from trdrka_rancang where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ),
                                        total_sempurna= (select sum(nilai) as jum from trdrka_rancang where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                        total_ubah= (select sum(nilai) as jum from trdrka_rancang where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                        username='$id',last_update=getdate()
                                        where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ");  


            $query1 = $this->db->query(" update trdrka set nilai= (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
                                        nilai_sempurna= (select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
                                        nilai_ubah=(select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka),
                                        nilai_akhir_sempurna=(select sum(total) as nl from trdpo where no_trdrka=trdrka.no_trdrka)
                                        ,username='$id',last_update=getdate(),
                                        sumber='$sdana1',sumber2='$sdana2',sumber3='$sdana3',sumber4='$sdana4',nilai_sumber='$ndana1',
                                        nilai_sumber2=$ndana2,nilai_sumber3=$ndana3,nilai_sumber4=$ndana4,      
                                        sumber1_su='$sdana1',sumber2_su='$sdana2',sumber3_su='$sdana3',sumber4_su='$sdana4',nsumber1_su=$ndana1,
                                        nsumber2_su=$ndana2,nsumber3_su=$ndana3,nsumber4_su=$ndana4,        
                                        sumber1_ubah='$sdana1',sumber2_ubah='$sdana2',sumber3_ubah='$sdana3',sumber4_ubah='$sdana4',nsumber1_ubah=$ndana1,
                                        nsumber2_ubah=$ndana2,nsumber3_ubah=$ndana3,nsumber4_ubah=$ndana4       
                                        where no_trdrka='$norka' ");  
            $query1 = $this->db->query("update trskpd set total= (select sum(nilai) as jum from trdrka where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ),
                                        total_sempurna= (select sum(nilai) as jum from trdrka where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                        total_ubah= (select sum(nilai) as jum from trdrka where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ), 
                                        username='$id',last_update=getdate()
                                        where kd_kegiatan='$kegiatan' and kd_skpd='$cskpd' ");  
            $this->rka_rinci($cskpd,$kegiatan,$rekening);
        
        }

        function rka_rinci($skpd='',$kegiatan='',$rekening='') {
            
            $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
            $sql    = "select * from trdpo where no_trdrka='$norka' order by no_po";                   
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii     = 0;

            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id'      => $ii,   
                            'header'  => $resulte['header'],  
                            'kode'    => $resulte['kode'],  
                            'no_po'   => $resulte['no_po'],  
                            'uraian'  => $resulte['uraian'],  
                            'volume1' => $resulte['volume1'],  
                            'volume2' => $resulte['volume2'],  
                            'volume3' => $resulte['volume3'],  
                            'satuan1' => $resulte['satuan1'],  
                            'satuan2' => $resulte['satuan2'],  
                            'satuan3' => $resulte['satuan3'],
                            'volume'  => $resulte['tvolume'],  
                            'harga1'  => number_format($resulte['harga1'],"2",".",","),  
                            'hargap'  => number_format($resulte['harga1'],"2",".",","),                             
                            'harga2'  => number_format($resulte['harga2'],"2",".",","),                             
                            'harga3'  => number_format($resulte['harga3'],"2",".",","),
                            'totalp'  => number_format($resulte['total'],"2",".",",") ,                            
                            'total'   => number_format($resulte['total'],"2",".",","),
                            'volume_sempurna1' => $resulte['volume_sempurna1'],
                            'tvolume_sempurna' => $resulte['tvolume_sempurna'],                            
                            'satuan_sempurna1' => $resulte['satuan_sempurna1'],
                            'harga_sempurna1'  => number_format($resulte['harga_sempurna1'],"2",".",","),
                            'total_sempurna'  => number_format($resulte['total_sempurna'],"2",".",","),
                            'volume_ubah1' => $resulte['volume_ubah1'],
                            'tvolume_ubah' => $resulte['tvolume_ubah'],                            
                            'satuan_ubah1' => $resulte['satuan_ubah1'],
                            'harga_ubah1'  => number_format($resulte['harga_ubah1'],"2",".",","),
                            'total_ubah'  => number_format($resulte['total_ubah'],"2",".",","),
                            'lsusun'  => $resulte['lsusun'], 
                            'lsempurna'  => $resulte['lsempurna'], 
                            'lubh'  => $resulte['lubh']
                            );
                            $ii++;
            }
               
               echo json_encode($result);
        }

        function rka_rinci_rancang($skpd='',$kegiatan='',$rekening='') {
        $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;        
        $result = $this->rka_model->rka_rinci_rancang($skpd,$kegiatan,$rekening,$norka);
        echo json_encode($result);
    }


        //  function rka_rinci_rancang($skpd='',$kegiatan='',$rekening='') {
            
        //     $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
        //     $sql    = "select * from trdpo_rancang where no_trdrka='$norka' order by no_po";                   
            
        //     $query1 = $this->db->query($sql);  
        //     $result = array();
        //     $ii     = 0;

        //     foreach($query1->result_array() as $resulte)
        //     { 
               
        //         $result[] = array(
        //                     'id'      => $ii,   
        //                     'header'  => $resulte['header'],  
        //                     'kode'    => $resulte['kode'],  
        //                     'kd_barang'    => $resulte['kd_barang'],  
        //                     'no_po'   => $resulte['no_po'],  
        //                     'uraian'  => $resulte['uraian'],  
        //                     'volume1' => $resulte['volume1'],  
        //                     'volume2' => $resulte['volume2'],  
        //                     'volume3' => $resulte['volume3'],  
        //                     'satuan1' => $resulte['satuan1'],  
        //                     'satuan2' => $resulte['satuan2'],  
        //                     'satuan3' => $resulte['satuan3'],
        //                     'volume'  => $resulte['tvolume'],  
        //                     'harga1'  => number_format($resulte['harga1'],"2",".",","),  
        //                     'hargap'  => number_format($resulte['harga1'],"2",".",","),                             
        //                     'harga2'  => number_format($resulte['harga2'],"2",".",","),                             
        //                     'harga3'  => number_format($resulte['harga3'],"2",".",","),
        //                     'totalp'  => number_format($resulte['total'],"2",".",",") ,                            
        //                     'total'   => number_format($resulte['total'],"2",".",","),
        //                     'volume_sempurna1' => $resulte['volume_sempurna1'],
        //                     'tvolume_sempurna' => $resulte['tvolume_sempurna'],                            
        //                     'satuan_sempurna1' => $resulte['satuan_sempurna1'],
        //                     'harga_sempurna1'  => number_format($resulte['harga_sempurna1'],"2",".",","),
        //                     'total_sempurna'  => number_format($resulte['total_sempurna'],"2",".",","),
        //                     'volume_ubah1' => $resulte['volume_ubah1'],
        //                     'tvolume_ubah' => $resulte['tvolume_ubah'],                            
        //                     'satuan_ubah1' => $resulte['satuan_ubah1'],
        //                     'harga_ubah1'  => number_format($resulte['harga_ubah1'],"2",".",","),
        //                     'total_ubah'  => number_format($resulte['total_ubah'],"2",".",",")
        //                     );
        //                     $ii++;
        //     }
               
        //        echo json_encode($result);
        // }


    function load_sum_rek_rancang(){

            $kdskpd = $this->input->post('skpd');
            $sub_kegiatan = $this->input->post('keg');

            $query1 = $this->db->query(" SELECT sum(nilai) as rektotal,sum(nilai_sempurna) as rektotal_sempurna,sum(nilai_ubah) as rektotal_ubah from 
                                         trdrka_rancang where kd_skpd='$kdskpd' /* and left(kd_rek6,4) not in ('5101') */ and kd_sub_kegiatan='$sub_kegiatan'");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'rektotal' => number_format($resulte['rektotal'],"2",".",","),  
                            'rektotal_sempurna' => number_format($resulte['rektotal_sempurna'],"2",".",","),
                            'rektotal_ubah' => number_format($resulte['rektotal_ubah'],"2",".",",")  
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);   
        }

    function load_sum_rek_rinci_rancang(){

            $kdskpd = $this->input->post('skpd');
            $kegiatan = $this->input->post('keg');
            $rek = $this->input->post('rek');
            $norka=$kdskpd.'.'.$kegiatan.'.'.$rek;

            $query1 = $this->db->query(" select sum(total) as rektotal_rinci,sum(total_ubah) as rektotal_rinci_ubah from trdpo_rancang_temp where no_trdrka='$norka' ");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'rektotal_rinci' => number_format($resulte['rektotal_rinci'],"2",".",","),  
                            'rektotal_rinci_ubah' => number_format($resulte['rektotal_rinci_ubah'],"2",".",",")  
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);   
        }


    function tsimpan_ar_rancang(){
            
            $kdskpd = $this->input->post('kd_skpd');
            $kdkegi = $this->input->post('kd_kegiatan');
            $kdrek  = $this->input->post('kd_rek5');
            $nilai  = $this->input->post('nilai');
            $sdana1 = $this->input->post('dana1');
            $sdana2 = $this->input->post('dana2');
            $sdana3 = $this->input->post('dana3');
            $sdana4 = $this->input->post('dana4');
            $ndana1 = $this->input->post('vdana1');
            $ndana2 = $this->input->post('vdana2');
            $ndana3 = $this->input->post('vdana3');
            $ndana4 = $this->input->post('vdana4');

                    
            $nmskpd = $this->rka_model->get_nama($kdskpd,'nm_skpd','ms_skpd','kd_skpd');
            $nmkegi = $this->rka_model->get_nama($kdkegi,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan');
            $nmrek  = $this->rka_model->get_nama($kdrek,'nm_rek6','ms_rek6','kd_rek6');
            
            $notrdrka  = $kdskpd.'.'.$kdkegi.'.'.$kdrek ;

            $query_del = $this->db->query("delete from trdrka_rancang where kd_skpd='$kdskpd' and kd_sub_kegiatan='$kdkegi' and kd_rek6='$kdrek' ");
            $query_ins = $this->db->query("insert into trdrka_rancang(no_trdrka,kd_skpd,nm_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,nilai_sempurna,nilai_ubah,
                                           sumber,sumber2,sumber3,sumber4,nilai_sumber,nilai_sumber2,nilai_sumber3,nilai_sumber4) values('$notrdrka','$kdskpd'
                                           ,'$nmskpd','$kdkegi','$nmkegi','$kdrek','$nmrek','$nilai','$nilai','$nilai','$sdana1','$sdana2','$sdana3','$sdana4',
                                           '$ndana1','$ndana2','$ndana3','$ndana4')");  

                                           
            $query_del = $this->db->query("delete from trdrka where kd_skpd='$kdskpd' and kd_sub_kegiatan='$kdkegi' and kd_rek6='$kdrek' ");
            $query_ins = $this->db->query("insert into trdrka(no_trdrka,kd_skpd,nm_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,nilai_sempurna,nilai_ubah,
                                           sumber,sumber2,sumber3,sumber4,nilai_sumber,nilai_sumber2,nilai_sumber3,nilai_sumber4,
                                           sumber1_su,sumber2_su,sumber3_su,sumber4_su,nsumber1_su,nsumber2_su,nsumber3_su,nsumber4_su,     
                                           sumber1_ubah,sumber2_ubah,sumber3_ubah,sumber4_ubah,nsumber1_ubah,nsumber2_ubah,nsumber3_ubah,nsumber4_ubah,nilai_akhir_sempurna
                                           ) values('$notrdrka','$kdskpd'
                                           ,'$nmskpd','$kdkegi','$nmkegi','$kdrek','$nmrek','$nilai','$nilai','$nilai','$sdana1','$sdana2','$sdana3','$sdana4',
                                           '$ndana1',$ndana2,$ndana3,$ndana4,'$sdana1','$sdana2','$sdana3','$sdana4',$ndana1,$ndana2,$ndana3,$ndana4,       
                                           '$sdana1','$sdana2','$sdana3','$sdana4',$ndana1,$ndana2,$ndana3,$ndana4,'$nilai')");        
            
            if ( $query_ins > 0 and $query_del > 0 ) {
                echo "1" ;
            } else {
                echo "0" ;
            }
            
        }


     function simpan_det_keg_rancang(){
            
            $skpd                   = $this->input->post('skpd');
            $giat                   = $this->input->post('giat');
            $lokasi                 = $this->input->post('lokasi');      
            $keterangan             = $this->input->post('keterangan');      
            $waktu_giat             = $this->input->post('waktu_giat');      
            $waktu_giat2            = $this->input->post('waktu_giat2');
            $sub_keluaran           = $this->input->post('sub_keluaran');      
            $tu_capai               = $this->input->post('tu_capai');
            $tu_capai_p             = $this->input->post('tu_capai_p');
            $tk_capai               = $this->input->post('tk_capai');
            $tk_capai_p             = $this->input->post('tk_capai_p');
            $tu_mas                 = $this->input->post('tu_mas');
            $tu_mas_p               = $this->input->post('tu_mas_p');
            $tk_mas                 = $this->input->post('tk_mas');
            $tk_mas_p               = $this->input->post('tk_mas_p');
            $tu_kel                 = $this->input->post('tu_kel');
            $tu_kel_p               = $this->input->post('tu_kel_p');
            $tk_kel                 = $this->input->post('tk_kel');
            $tk_kel_p               = $this->input->post('tk_kel_p');
            $tu_has                 = $this->input->post('tu_has');
            $tu_has_p               = $this->input->post('tu_has_p');
            $tk_has                 = $this->input->post('tk_has');
            $tk_has_p               = $this->input->post('tk_has_p');
            $ttd                    = $this->input->post('ttd');      
            $ang_lalu               = $this->input->post('lalu');
            $sasaran_program        = $this->input->post('sasaran_program');
            $capaian_program        = $this->input->post('capaian_program');
            $kel_sasaran_kegiatan   = $this->input->post('kel_sasaran_kegiatan');


            //UPDATE PADA SUB KEGIATAN
            
            $sql1 =" UPDATE trskpd_rancang set 
                                                 lokasi='$lokasi',
                                                 keterangan='$keterangan',
                                                 waktu_giat='$waktu_giat',
                                                 waktu_giat2='$waktu_giat2',
                                                 sub_keluaran='$sub_keluaran',
                                                 kd_pptk='$ttd',
                                                 ang_lalu='$ang_lalu'
            where kd_skpd='$skpd' and kd_sub_kegiatan='$giat'  ";
            $asg1 = $this->db->query($sql1); 

            if (!($asg1)){
               $msg = array('pesan'=>'0');
                echo json_encode($msg);
            }  else {
                 $sql2 =" UPDATE trskpd set 
                                                 lokasi='$lokasi',
                                                 keterangan='$keterangan',
                                                 waktu_giat='$waktu_giat',
                                                 waktu_giat2='$waktu_giat2',
                                                 sub_keluaran='$sub_keluaran',
                                                 kd_pptk='$ttd',
                                                 ang_lalu='$ang_lalu'
            where kd_skpd='$skpd' and kd_sub_kegiatan='$giat'";
            $asg2 = $this->db->query($sql2);

                if (!($asg2)){
                   $msg = array('pesan'=>'0');
                    echo json_encode($msg);
                }  else {
                    //UPDATE PADA KEGIATAN
            $sql3 ="UPDATE trskpd_rancang set
                                                    tu_capai='$tu_capai',
                                                    tu_capai_p='$tu_capai_p',
                                                    tk_capai='$tk_capai',
                                                    tk_capai_p='$tk_capai_p',
                                                    tu_mas='$tu_mas',
                                                    tu_mas_p='$tu_mas_p',
                                                    tk_mas='$tk_mas',
                                                    tk_mas_p='$tk_mas_p',
                                                    tu_kel='$tu_kel',
                                                    tu_kel_p='$tu_kel_p',
                                                    tk_kel='$tk_kel',
                                                    tk_kel_p='$tk_kel_p',
                                                    tu_has='$tu_has',
                                                    tu_has_p='$tu_has_p',
                                                    tk_has='$tk_has',
                                                    tk_has_p='$tk_has_p',
                                                    kel_sasaran_kegiatan='$kel_sasaran_kegiatan'
                            where kd_skpd='$skpd' and kd_kegiatan=left('$giat',12) ";
            $asg3 = $this->db->query($sql3);

                    if (!($asg3)){
                       $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                    }  else {
                        $sql4 ="UPDATE trskpd set
                                                    tu_capai='$tu_capai',
                                                    tu_capai_p='$tu_capai_p',
                                                    tk_capai='$tk_capai',
                                                    tk_capai_p='$tk_capai_p',
                                                    tu_mas='$tu_mas',
                                                    tu_mas_p='$tu_mas_p',
                                                    tk_mas='$tk_mas',
                                                    tk_mas_p='$tk_mas_p',
                                                    tu_kel='$tu_kel',
                                                    tu_kel_p='$tu_kel_p',
                                                    tk_kel='$tk_kel',
                                                    tk_kel_p='$tk_kel_p',
                                                    tu_has='$tu_has',
                                                    tu_has_p='$tu_has_p',
                                                    tk_has='$tk_has',
                                                    tk_has_p='$tk_has_p',
                                                    kel_sasaran_kegiatan='$kel_sasaran_kegiatan'
                            where kd_skpd='$skpd' and kd_kegiatan=left('$giat',12) ";
            $asg4 = $this->db->query($sql4);

                        if (!($asg4)){
                           $msg = array('pesan'=>'0');
                            echo json_encode($msg);
                        }  else {
                            //UPDATE PADA PROGRAM
            $sql5 ="UPDATE trskpd_rancang set
                                                    sasaran_program='$sasaran_program',
                                                    capaian_program='$capaian_program'
                            where kd_skpd='$skpd' and kd_program=left('$giat',7)";
            $asg5 = $this->db->query($sql5);
                            if (!($asg5)){
                               $msg = array('pesan'=>'0');
                                echo json_encode($msg);
                            }  else {
                                $sql6 ="UPDATE trskpd set
                                                    sasaran_program='$sasaran_program',
                                                    capaian_program='$capaian_program'
                            where kd_skpd='$skpd' and kd_program=left('$giat',7)";
                $asg6 = $this->db->query($sql6);
                                if (!($asg6)){
                                   $msg = array('pesan'=>'0');
                                    echo json_encode($msg);
                                }  else {
                                   $msg = array('pesan'=>'1');
                                    echo json_encode($msg);
                                }
                            }
                        }
                    }
                }
            }


        }


    function load_det_keg_rancang(){

            $kdskpd = $this->input->post('skpd');
            $kegiatan = $this->input->post('keg');

            $query1 = $this->db->query(" select * from trskpd_rancang where kd_skpd='$kdskpd' and kd_sub_kegiatan='$kegiatan'");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'lokasi'        => $resulte['lokasi'],  
                            'sasaran_program'       => $resulte['sasaran_program'],  
                            'capaian_program'       => $resulte['capaian_program'],  
                            'waktu_giat'    => $resulte['waktu_giat'],
                            'waktu_giat2'   => $resulte['waktu_giat2'],  
                            'ttd'           => $resulte['kd_pptk'],
                            'tu_capai'      => $resulte['tu_capai'],
                            'tu_capai_p'    => $resulte['tu_capai_p'],
                            'tu_mas'        => $resulte['tu_mas'],
                            'tu_mas_p'      => $resulte['tu_mas_p'],
                            'tu_kel'        => $resulte['tu_kel'],
                            'tu_kel_p'      => $resulte['tu_kel_p'],
                            'tu_has'          => $resulte['tu_has'],
                            'tu_has_p'          => $resulte['tu_has_p'],
                            'tk_capai'         => $resulte['tk_capai'],
                            'tk_capai_p'         => $resulte['tk_capai_p'],
                            'tk_mas'          => $resulte['tk_mas'],
                            'tk_mas_p'          => $resulte['tk_mas_p'],
                            'tk_kel'          => $resulte['tk_kel'],
                            'tk_kel_p'          => $resulte['tk_kel_p'],
                            'tk_has'          => $resulte['tk_has'],
                            'tk_has_p'          => $resulte['tk_has_p'],
                            'kel_sasaran_kegiatan'          => $resulte['kel_sasaran_kegiatan'],
                            'sub_keluaran'          => $resulte['sub_keluaran'],
                            'keterangan'          => $resulte['keterangan'],
                            'ang_lalu' => number_format($resulte['ang_lalu']),
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);   

        }


        function rka_hukum($skpd='',$kegiatan='',$kd_rek5) {

            $sql = " SELECT *,(SELECT dhukum FROM d_hukum WHERE kd_skpd='$skpd' AND kd_kegiatan='$kegiatan' AND kd_rek5='$kd_rek5' AND dhukum=m_hukum.kd_hukum) AS ck FROM m_hukum ";                   
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_hukum' => $resulte['kd_hukum'],  
                            'nm_hukum' => $resulte['nm_hukum'],  
                            'ck'   => $resulte['ck']                          
                            );
                            $ii++;
            }
               
               echo json_encode($result);
        }

    function simpan_dhukum(){
            
            $skpd=$this->input->post('skpd');
            $giat=$this->input->post('giat');
            $isi=$this->input->post('cisi');  
            $kdrek5=$this->input->post('rek5');      
            $pecah=explode('||',$isi);
            $pj=count($pecah);

            $this->db->query(" delete from d_hukum where kd_skpd='$skpd' and kd_kegiatan='$giat' ");        
        
            for($i=0;$i<$pj;$i++){
                if (trim($pecah[$i])!=''){
                    $this->db->query(" insert into d_hukum(kd_skpd,kd_kegiatan,dhukum,kd_rek5) values('$skpd','$giat','".$pecah[$i]."','$kdrek5') ");       
                }
            }
        }

    function daftar_kegiatan_penyusunan($offset=0)
        {
            $id = $this->uri->segment(2);
            $data['page_title'] = "DAFTAR KEGIATAN";
            
            $total_rows = $this->rka_model->get_count($id);
      
            // pagination        
     
            $config['base_url']         = base_url("rka_rancang/daftar_kegiatan_penyusunan/$id");
            $config['total_rows']       = $total_rows;
            $config['per_page']         = '10';
            $config['uri_segment']      = 3;
            $config['num_links']        = 5;
            $config['full_tag_open']    = '<ul class="page-navi">';
            $config['full_tag_close']   = '</ul>';
            $config['num_tag_open']     = '<li>';
            $config['num_tag_close']    = '</li>';
            $config['cur_tag_open']     = '<li class="current">';
            $config['cur_tag_close']    = '</li>';
            $config['prev_link']        = '&lt;';
            $config['prev_tag_open']    = '<li>';
            $config['prev_tag_close']   = '</li>';
            $config['next_link']        = '&gt;';
            $config['next_tag_open']    = '<li>';
            $config['next_tag_close']   = '</li>';
            $config['last_link']        = 'Last';
            $config['last_tag_open']    = '<li>';
            $config['last_tag_close']   = '</li>';
            $config['first_link']       = 'First';
            $config['first_tag_open']   = '<li>';
            $config['first_tag_close']  = '</li>';
            $limit                      = $config['per_page'];  
            $offset                     = $this->uri->segment(3);  
            $offset                     = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
              
            if(empty($offset))  
            {  
                $offset=0;  
            }
        
            $data['list']       = $this->rka_model->getAll($limit, $offset,$id);
            $data['num']        = $offset;
            $data['total_rows'] = $total_rows;
            
                    $this->pagination->initialize($config);
            
            $this->template->set('title', 'Master Data kegiatan');
            $this->template->load('template', 'anggaran/rka/penyusunan/list_penyusunan', $data);
        }

    function rka22_penyusunan()
        {
            $data['page_title']= 'CETAK';
            $this->template->set('title', 'Cetak RKA Belanja SKPD Penyusunan');   
            $this->template->load('template','anggaran/rka/penyusunan/rka22_penyusunan',$data) ; 
        }

         function preview_rka22_penyusunan(){
            $id = $this->uri->segment(2);
            $cetak = $this->uri->segment(3);
            
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $ttd1 = str_replace('a',' ',$ttd1); 
            $ttd2 = str_replace('a',' ',$ttd2); 
            $keu = $this->keu1;
            
            $csdana = $this->rka_model->qcekdanarka($id,'sumber','nilai_sumber','nilai');
            $csdana1 =  $csdana->num_rows();   
            
            if($csdana1>0){
                $this->preview_sdana_kosong($id,$csdana);
                exit();
            }
            
            $csrinci = $this->rka_model->qcekrincian($id,'nilai');
            $csrinci1 =  $csrinci->num_rows();
            if($csrinci1>0){
                $this->preview_srinci($id,$csrinci);
                exit();
            }
            
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
         
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$keu'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = $this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') and nip='$ttd1'  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip;
                        $pangkat=$rowttd->pangkat;
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                    }
                    
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') and nip='$ttd2'  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip; 
                        $pangkat2=$rowttd2->pangkat;
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                    }
            
            
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr><td width=\"20%\" rowspan=\"4\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" /></td>
                             <td width=\"80%\" align=\"center\"><strong>RENCANA KERJA DAN ANGGARAN </strong></td>
                             <td width=\"20%\" rowspan=\"4\" align=\"center\"><strong>FORMULIR<br>RKA - BELANJA SKPD   </strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>SATUAN KERJA PERANGKAT DAERAH </strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>$kab</strong> </td>
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>TAHUN ANGGARAN $thn</strong></td>
                        </tr>

                      </table>";
        
                 if (strlen($id)>17){
                        $sqldns="SELECT a.kd_urusan as kd_u,b.kd_urusan as header, LEFT(a.kd_skpd,17) as kd_org,c.nm_org as nm_org,b.kd_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a 
            INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan 
            INNER JOIN ms_organisasi c ON LEFT(a.kd_skpd,17)=c.kd_org
            WHERE kd_skpd='$id'";
                        $a = 'left(';
                        $skpd = 'kd_skpd';
                        $b = ',20)';             
                    }else{
                        $sqldns="SELECT a.kd_urusan as kd_u,b.kd_urusan as header, LEFT(a.kd_skpd,17) as kd_org,c.nm_org as nm_org,b.kd_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a 
            INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan 
            INNER JOIN ms_organisasi c ON LEFT(a.kd_skpd,17)=c.kd_org
            WHERE left(kd_skpd,17)='$id'";
                        $a = 'left(';
                        $skpd = 'kd_skpd';
                        $b = ',17)'; 
                    }
                             $sqlskpd=$this->db->query($sqldns);
                             foreach ($sqlskpd->result() as $rowdns)
                            {
                                $kd_urusan=$rowdns->kd_u;                    
                                $nm_urusan= $rowdns->nm_u;
                                $kd_skpd  = $rowdns->kd_sk;
                                $nm_skpd  = $rowdns->nm_sk;
                                $header  = $rowdns->header;
                                $kd_org = $rowdns->kd_org;
                                $nm_org = $rowdns->nm_org;
                            }


            if (strlen($id)==17){          
                $cRet.="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                       <tr>
                            <td  width=\"20%\" style=\"vertical-align:center;border-right: none;\"><strong>&emsp;Organisasi</strong></td>
                            <td colspan=\"2\" style=\"vertical-align:left;border-left: none;\"> <strong>: $kd_org - $nm_org</strong></td>
                        </tr>
                        <tr>
                            <td colspan=\"3\" width=\"1%\" bgcolor=\"#CCCCCC\" align=\"center\" text-rotate=\"90\"><b>&nbsp;</b></td>
                        </tr>
                        <tr>
                            <td style=\"border-top:solid 1px black;font-size:14px;\" colspan=\"3\" align=\"center\"><strong>Rekapitulasi Anggaran Belanja Berdasarkan Program dan Kegiatan </strong></td>
                        </tr>
                    </table>";
            }else{
                $cRet.="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                        
                      
                        <tr>
                            <td width=\"20%\" style=\"vertical-align:center;border-right: none;\"><strong>&emsp;Organisasi</strong></td>
                            <td colspan=\"2\" style=\"vertical-align:center;border-left: none;\"> <strong>$kd_org - $nm_org</strong></td>
                        </tr> 
                        <tr>
                            <td width=\"20%\" style=\"vertical-align:center;border-right: none;\"><strong>&emsp;Unit Organisasi </strong></td>
                            <td colspan=\"2\"  style=\"vertical-align:center;border-left: none;\"><strong>: $kd_skpd - $nm_skpd</strong></td>
                        </tr>
                        <tr>
                            <td colspan=\"3\" width=\"1%\" bgcolor=\"#CCCCCC\" align=\"center\" text-rotate=\"90\"><b>&nbsp;</b></td>
                        </tr>
                        <tr>
                            <td style=\"border-top:solid 1px black;font-size:14px;\" colspan=\"3\" align=\"center\"><strong>Rekapitulasi Anggaran Belanja Berdasarkan Program dan Kegiatan </strong></td>
                        </tr>
                    </table>";
            }

            $cRet .= "<table style=\"border-collapse:collapse;font-size:10px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
                         <thead>                       
                            <tr><td colspan=\"5\" bgcolor=\"#CCCCCC\" width=\"11%\" align=\"center\"><b>Kode</b></td>                            
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Uraian</b></td>
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>Sumber<br>Dana</b></td>
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>Lokasi</b></td>
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>T - 1</b></td>
                                <td colspan=\"5\" bgcolor=\"#CCCCCC\" width=\"45%\" align=\"center\"><b>Tahun n</b></td>
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"9%\" align=\"center\"><b>T+1</b></td>
                            </tr>
                            <tr>
                                <td width=\"1%\" bgcolor=\"#CCCCCC\" align=\"center\" text-rotate=\"90\"><b>Urusan</b></td>
                                <td width=\"2%\" bgcolor=\"#CCCCCC\" align=\"center\" text-rotate=\"90\"><b>Sub Urusan</b></td>
                                <td width=\"2%\" bgcolor=\"#CCCCCC\" align=\"center\" text-rotate=\"90\"><b>Program</b></td>
                                <td width=\"2%\" bgcolor=\"#CCCCCC\" align=\"center\" text-rotate=\"90\"><b>Kegiatan</b></td>
                                <td width=\"2%\" bgcolor=\"#CCCCCC\" align=\"center\" text-rotate=\"90\"><b>Sub Kegiatan&nbsp;&nbsp;</b></td>
                                <td width=\"9%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Belanja Operasi</b></td>
                                <td width=\"9%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Belanja Modal</b></td>
                                <td width=\"9%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Belanja Tidak Terduga</b></td>
                                <td width=\"9%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Belanja Transfer</b></td>
                                <td width=\"9%\" bgcolor=\"#CCCCCC\" align=\"center\"><b>Jumlah</b></td>
                            </tr>    
                         </thead>

                        
                         
                            <tr>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\">1</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >2</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >3</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >4</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >5</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >6</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >7</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >8</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >9</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >10</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >11</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >12</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >13</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >14</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" align=\"center\" >15</td>
                            </tr>

                                <tfoot>
                                    

                           
                                </tfoot>
                            ";
                    $n_trdrka = 'trdrka_rancang';   
                    $n_trskpd = 'trskpd_rancang';
                    
                    $sql1="SELECT
                                * FROM cetak_rka22 where $a kd_skpd$b='$id'
                           ORDER BY ID
                            ";
                     
                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                                                      
                    foreach ($query->result() as $row)
                    {
                        $urusan=$row->urusan;
                        $subrsan=$row->sub_urusan;
                        $prog=$row->prog1;
                        $giat=$row->giat;
                        $subgiat=$row->sub_giat;
                        $uraian=$row->uraian;
                        $lokasi=$row->lokasi;
                        $target=$row->target;
                        $t1=$row->t1;
                        $opr=empty($row->bloperasi) || $row->bloperasi == 0 ? '' :number_format($row->bloperasi,2,',','.');
                        $mdl=empty($row->blmodal) || $row->blmodal == 0 ? '' :number_format($row->blmodal,2,',','.');
                        $taktdg=empty($row->bltaktdg) || $row->bltaktdg == 0 ? '' :number_format($row->bltaktdg,2,',','.');
                        $trfs=empty($row->bltrfs) || $row->bltrfs == 0 ? '' :number_format($row->bltrfs,2,',','.');
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nilai= number_format($row->jumlah,"2",",",".");

                        //ambel sumber dana
                        $sqlsumber      = "SELECT * from (
                                            SELECT sumber as sd FROM trdrka_rancang where $a kd_skpd$b='$id' and sumber is not null group by sumber 
                                            UNION
                                            SELECT sumber2 FROM trdrka_rancang where $a kd_skpd$b='$id' and sumber2 is not null group by sumber2
                                            UNION
                                            SELECT sumber3 FROM trdrka_rancang where $a kd_skpd$b='$id' and sumber3 is not null group by sumber3
                                            UNION
                                            SELECT sumber4 FROM trdrka_rancang where $a kd_skpd$b='$id' and sumber4 is not null group by sumber4
                                            )z where sd<>''
                                            ";
                     
                                    $sumberdn  = $this->db->query($sqlsumber);
                                    $sumber_dana='';                                  
                                    foreach ($sumberdn ->result() as $rows)
                                    {   
                                        $sumber_dana=$sumber_dana.'<br />- '.$rows->sd;

                                    }
                                    if($subrsan!='' || $urusan!=''){
                                         $cRet    .= " <tr><td style=\"vertical-align:center;border-bottom: solid 1px black;\"  align=\"center\">$urusan</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($subrsan,2,2)."&nbsp;&nbsp;</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($prog,5,2)."&nbsp;&nbsp;</td>                                     
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($giat,8,4)."</td>
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($subgiat,13,2)."&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" >$uraian</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" ></td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  >$lokasi</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\" >".$this->rka_model->angka($t1)."&nbsp;&nbsp;</td>
                                         
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$opr&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$mdl&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$taktdg&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$trfs&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$nilai&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">".$this->rka_model->angka($row->jumlah*1.1)."&nbsp;&nbsp;</td></tr>
                                         ";
                                    }else{
                                         $cRet    .= " <tr><td style=\"vertical-align:center;border-bottom: solid 1px black;\"  align=\"center\">$urusan</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($subrsan,2,2)."&nbsp;&nbsp;</td>
                                        <td style=\"vertical-align:center;border-bottom: solid 1px black;\"     align=\"center\">".substr($prog,5,2)."&nbsp;&nbsp;</td>                                     
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($giat,8,4)."</td>
                                         <td style=\"vertical-align:center;border-bottom: solid 1px black;\" align=\"center\">".substr($subgiat,13,2)."&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" >$uraian</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\" >$sumber_dana</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  >$lokasi</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\" >".$this->rka_model->angka($t1)."&nbsp;&nbsp;</td>
                                         
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$opr&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$mdl&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$taktdg&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$trfs&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">$nilai&nbsp;&nbsp;</td>
                                         <td style=\"vertical-align:top;border-bottom: solid 1px black;\"  align=\"right\">".$this->rka_model->angka($row->jumlah*1.1)."&nbsp;&nbsp;</td></tr>
                                         ";
                                    } 
                       
                        
                    }
                            
                            $sql1="SELECT x.kd_skpd ,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='51' AND  $a a.kd_skpd$b='$id') AS bloperasi,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='52' AND  $a a.kd_skpd$b='$id')  AS blmodal,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='53' AND  $a a.kd_skpd$b='$id') AS bltaktdg,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd  AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE LEFT(kd_rek6,2)='54' AND  $a a.kd_skpd$b='$id') AS bltrfs,
    (SELECT SUM(nilai) AS nilai FROM $n_trdrka x inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd  AND x.kd_sub_kegiatan=a.kd_sub_kegiatan
    WHERE $a x.kd_skpd$b='$id' and LEFT(kd_rek6,1)='5' ) AS jumlah FROM $n_trdrka x 
    inner JOIN $n_trskpd a ON a.kd_skpd=x.kd_skpd
    WHERE $a x.kd_skpd$b='$id' GROUP BY x.kd_skpd ";

                    
                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                                                     
                    foreach ($query->result() as $row)
                    {

                       
                        $opr=empty($row->bloperasi) || $row->bloperasi == 0 ? '' :number_format($row->bloperasi,2,',','.');
                        $mdl=empty($row->blmodal) || $row->blmodal == 0 ? '' :number_format($row->blmodal,2,',','.');
                        $taktdg=empty($row->bltaktdg) || $row->bltaktdg == 0 ? '' :number_format($row->bltaktdg,2,',','.');
                        $trfs=empty($row->bltrfs) || $row->bltrfs == 0 ? '' :number_format($row->bltrfs,2,',','.');
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nilai= number_format($row->jumlah,"2",",",".");
                       
                         $cRet    .= " <tr>
                                        
                                        <td colspan=\"9\" style=\"vertical-align:center;border-top: solid 1px black;border-bottom: none;\" align=\"right\"> JUMLAH</td>                                     
                                         
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$opr</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$mdl</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$taktdg</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$trfs</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$nilai</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\"></td>
                                         </tr>
                                         ";
                    }
                    $cRet    .= "</table>";
                    $kd_ttd=substr($id,18,2);
                     $kd_kepala=substr($id,0,7);
                    $cRet    .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">";

                    if (($kd_ttd=='01') ){
                        $cRet .="<tr>
                                    <td width=\"100%\" align=\"right\" colspan=\"10\">
                                    <table border=\"0\"  align=\"right\">
                                    <tr>
                                    <td width=\"35%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"5%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"50%\" align=\"center\">$daerah,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.
                                    <br> Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><u><b>$nama</b></u>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    <td width=\"10%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td></td>
                                    </tr></table></td>
                                 </tr>";
                                 } else {
                                 $cRet .="<tr>
                                    <td width=\"100\" align=\"center\" colspan=\"10\">                          
                                    <table border=\"0\"  align=\"right\">
                                    <tr>
                                    
                                    <td width=\"40%\" align=\"center\">Mengetahui,
                                    <br>Pengguna Anggaran
                                    <br>$jabatan2,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama2</u></b>
                                    <br>$pangkat2 
                                    <br>NIP. $nip2 
                                    </td>
                                    <td width=\"20%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"40%\" align=\"center\">$daerah,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.
                                    <br>Kuasa Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama</u></b>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    </td></tr></table></td>
                                 </tr>";
                                 } 
                    
            
               
                                 
                     
            $cRet    .= "</table>";
            $data['prev']= $cRet; 
            $judul ='RKA-22 Penyusunan'.$id.'';
          switch($cetak) { 
            case 1;
                 $this->_mpdf('',$cRet,10,10,5,'5');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0;     
            echo ("<title>RKA 22 Penyusunan</title>");
            echo($cRet);
            break;
            }
            
                    
        }
            
                    
      

    function rka221_penyusunan()
        {   
         $this->index('0','ms_skpd','kd_skpd','nm_skpd','RKA Rincian Belanja Penyusunan','penyusunan/rka221_penyusunan','');
        }


         function preview_rka221_penyusunan(){
            $id = $this->uri->segment(2);
            $giat = $this->uri->segment(3);
            $cetak = $this->uri->segment(4);
            $atas = $this->uri->segment(5);
            $bawah = $this->uri->segment(6);
            $kiri = $this->uri->segment(7);
            $kanan = $this->uri->segment(8);
     

            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
     
            $csdana = $this->rka_model->qcekdanarka($id,'sumber','nilai_sumber','nilai');
            $csdana1 =  $csdana->num_rows();   
            
            if($csdana1>0){
                $this->preview_sdana_kosong($id,$csdana);
                exit();
            }
     
            $csrinci = $this->rka_model->qcekrincian($id,'nilai');
            $csrinci1 =  $csrinci->num_rows();
            if($csrinci1>0){
                $this->preview_srinci($id,$csrinci);
                exit();
            }
     
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        $tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
           $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kd_skpd= '$id' AND kode in ('PA','KPA') AND(REPLACE(nip, ' ', 'a')='$ttd1' )  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip; 
                        $pangkat=$rowttd->pangkat;
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        //$jabatan  = str_replace('Kuasa Pengguna Anggaran','',$jabatan);
                        if($jabatan=='Kuasa Pengguna Anggaran'){
                            $kuasa="";
                        }else{
                            $kuasa="Kuasa Pengguna Anggaran";
                        }
                        
                        /* if($jabatan=='Pengguna Anggaran'){
                            $kuasa="";
                        }else{
                            $kuasa="Pengguna Anggaran";
                        } */
                    }
                  
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND(REPLACE(nip, ' ', 'a')='$ttd2')  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip;
                        $pangkat2=$rowttd2->pangkat;
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                        //$jabatan2  = str_replace('Pengguna Anggaran','',$jabatan2);
                        
                        /* if($jabatan2=='Kuasa Pengguna Anggaran'){
                            $kuasa2="";
                        }else{
                            $kuasa2="Kuasa Pengguna Anggaran";
                        } */
                        
                        if($jabatan2=='Pengguna Anggaran'){
                            $kuasa2="";
                        }else{
                            $kuasa2="Pengguna Anggaran";
                        }
                    }
            $sqlorg="SELECT * from header_rka_penyusunan
                                    where left(kd_sub_kegiatan,12)='$giat'
                    ";
                     $sqlorg1=$this->db->query($sqlorg);
                     foreach ($sqlorg1->result() as $roworg)
                    {
                        $kd_urusan=$roworg->kd_urusan;                    
                        $nm_urusan= $roworg->nm_urusan;
                        $kd_bidang_urusan=$roworg->kd_bidang_urusan;                    
                        $nm_bidang_urusan= $roworg->nm_bidang_urusan;
                        $kd_skpd  = $roworg->kd_skpd;
                        $nm_skpd  = $roworg->nm_skpd;
                        $kd_prog  = $roworg->kd_program;
                        $nm_prog  = $roworg->nm_program;
                        $sasaran_prog  = $roworg->sasaran_program;
                        $capaian_prog  = $roworg->capaian_program;
                        $kd_giat  = $roworg->kd_kegiatan;
                        $nm_giat  = $roworg->nm_kegiatan;
                        $lokasi  = $roworg->lokasi;
                        $tu_capai  = $roworg->tu_capai;
                        $tu_capai_p  = $roworg->tu_capai_p;
                        $tu_mas  = $roworg->tu_mas;
                        $tu_mas_p  = $roworg->tu_mas_p;
                        $tu_kel  = $roworg->tu_kel;
                        $tu_kel_p  = $roworg->tu_kel_p;
                        $tu_has  = $roworg->tu_has;
                        $tu_has_p  = $roworg->tu_has_p;
                        $tk_capai  = $roworg->tk_capai;
                        $tk_mas  = $roworg->tk_mas;
                        $tk_kel  = $roworg->tk_kel;
                        $tk_has  = $roworg->tk_has;
                        $tk_capai_p  = $roworg->tk_capai_p;
                        $tk_mas_p  = $roworg->tk_mas_p;
                        $tk_kel_p  = $roworg->tk_kel_p;
                        $tk_has_p  = $roworg->tk_has_p;
                        $sas_giat = $roworg->kel_sasaran_kegiatan;
                        $ang_lalu = $roworg->ang_lalu;
                    }
            $kd_urusan= empty($roworg->kd_urusan) || ($roworg->kd_urusan) == '' ? '' : ($roworg->kd_urusan);
            $nm_urusan= empty($roworg->nm_urusan) || ($roworg->nm_urusan) == '' ? '' : ($roworg->nm_urusan);
            $kd_bidang_urusan= empty($roworg->kd_bidang_urusan) || ($roworg->kd_bidang_urusan) == '' ? '' : ($roworg->kd_bidang_urusan);
            $nm_bidang_urusan= empty($roworg->nm_bidang_urusan) || ($roworg->nm_bidang_urusan) == '' ? '' : ($roworg->nm_bidang_urusan);
            $kd_skpd= empty($roworg->kd_skpd) || ($roworg->kd_skpd) == '' ? '' : ($roworg->kd_skpd);
            $nm_skpd= empty($roworg->nm_skpd) || ($roworg->nm_skpd) == '' ? '' : ($roworg->nm_skpd);
            $kd_prog= empty($roworg->kd_program) || ($roworg->kd_program) == '' ? '' : ($roworg->kd_program);
            $nm_prog= empty($roworg->nm_program) || ($roworg->nm_program) == '' ? '' : ($roworg->nm_program);
            $sasaran_prog= empty($roworg->sasaran_program) || ($roworg->sasaran_program) == '' ? '' : ($roworg->sasaran_program);
            $capaian_prog= empty($roworg->capaian_program) || ($roworg->capaian_program) == '' ? '' : ($roworg->capaian_program);
            $kd_giat= empty($roworg->kd_kegiatan) || ($roworg->kd_kegiatan) == '' ? '' : ($roworg->kd_kegiatan);
            $nm_giat= empty($roworg->nm_kegiatan) || ($roworg->nm_kegiatan) == '' ? '' : ($roworg->nm_kegiatan);
            $lokasi= empty($roworg->lokasi) || ($roworg->lokasi) == '' ? '' : ($roworg->lokasi);
            $tu_capai= empty($roworg->tu_capai) || ($roworg->tu_capai) == '' ? '' : ($roworg->tu_capai);
            $tu_mas= empty($roworg->tu_mas) || ($roworg->tu_mas) == '' ? '' : ($roworg->tu_mas);
            $tu_kel= empty($roworg->tu_kel) || ($roworg->tu_kel) == '' ? '' : ($roworg->tu_kel);
            $tu_has= empty($roworg->tu_has) || ($roworg->tu_has) == '' ? '' : ($roworg->tu_has);
            $tk_capai= empty($roworg->tk_capai) || ($roworg->tk_capai) == '' ? '' : ($roworg->tk_capai);
            $tk_mas= empty($roworg->tk_mas) || ($roworg->tk_mas) == '' ? '' : ($roworg->tk_mas);
            $tk_kel= empty($roworg->tk_kel) || ($roworg->tk_kel) == '' ? '' : ($roworg->tk_kel);
            $tk_has= empty($roworg->tk_has) || ($roworg->tk_has) == '' ? '' : ($roworg->tk_has);

            $tu_capai_p= empty($roworg->tu_capai_p) || ($roworg->tu_capai_p) == '' ? '' : ($roworg->tu_capai_p);
            $tu_mas_p= empty($roworg->tu_mas_p) || ($roworg->tu_mas_p) == '' ? '' : ($roworg->tu_mas_p);
            $tu_kel_p= empty($roworg->tu_kel_p) || ($roworg->tu_kel_p) == '' ? '' : ($roworg->tu_kel_p);
            $tu_has_p= empty($roworg->tu_has_p) || ($roworg->tu_has_p) == '' ? '' : ($roworg->tu_has_p);
            $tk_capai_p= empty($roworg->tk_capai_p) || ($roworg->tk_capai_p) == '' ? '' : ($roworg->tk_capai_p);
            $tk_mas_p= empty($roworg->tk_mas_p) || ($roworg->tk_mas_p) == '' ? '' : ($roworg->tk_mas_p);
            $tk_kel_p= empty($roworg->tk_kel_p) || ($roworg->tk_kel_p) == '' ? '' : ($roworg->tk_kel_p);
            $tk_has_p= empty($roworg->tk_has_p) || ($roworg->tk_has_p) == '' ? '' : ($roworg->tk_has_p);
            $sas_giat= empty($roworg->kel_sasaran_kegiatan) || ($roworg->kel_sasaran_kegiatan) == '' ? '' : ($roworg->kel_sasaran_kegiatan);
            $ang_lalu= empty($roworg->ang_lalu) || ($roworg->ang_lalu) == '' || ($roworg->ang_lalu) == 'Null' ? 0 : ($roworg->ang_lalu);

            $sqltp="SELECT SUM(nilai) AS totb FROM trdrka_rancang WHERE left(kd_sub_kegiatan,12)='$giat' AND kd_skpd='$id'";
                     $sqlb=$this->db->query($sqltp);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $totp  =number_format($rowb->totb,"2",",",".");
                       $totp1 =number_format($rowb->totb*1.1,"2",",",".");
                    }
                    
            
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr> <td width=\"20%\" style=\"vertical-align:top;border-bottom: none;\"  rowspan=\"4\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" /></td>
                             <td width=\"70%\" align=\"center\"><strong>RENCANA KERJA DAN ANGGARAN </strong></td>
                             <td width=\"10%\" style=\"vertical-align:top;border-bottom: none;\" rowspan=\"4\" align=\"center\"><strong><br /><br />FORMULIR RKA <br /> RKA-RINCIAN <br />
    BELANJA SKPD    
      </strong></td>
                        </tr>
                        <tr>
                             <td  align=\"center\"><strong>SATUAN KERJA PERANGKAT DAERAH </strong></td>
                        </tr>
                        <tr>
                             <td style=\"vertical-align:top;border-bottom: none;\" align=\"center\"><strong>$kab</strong> </td>
                        </tr>
                        <tr>
                             <td style=\"vertical-align:top;border-bottom: none;\" align=\"center\"><strong>TAHUN ANGGARAN $thn</strong></td>
                        </tr>

                      </table>";
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Urusan Pemerintahan</td>
                                <td width=\"5%\"  style=\"vertical-align:top;border-left: none;border-right: none;\" align=\"center\">:</td>
                                <td width=\"15%\" style=\"vertical-align:top;border-left: none;border-right: none;\" align=\"left\">$kd_urusan</td>
                                <td width=\"60%\" style=\"vertical-align:top;border-left: none;\" align=\"left\">$nm_urusan</td>
                            </tr>
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Bidang Urusan</td>
                                <td width=\"5%\"  style=\"vertical-align:top;border-left: none;border-right: none;\" align=\"center\">:</td>
                                <td width=\"15%\" style=\"vertical-align:top;border-left: none;border-right: none;\" align=\"left\">$kd_bidang_urusan </td>
                                <td width=\"60%\" style=\"vertical-align:top;border-left: none;\" align=\"left\"> $nm_bidang_urusan</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Program</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;border-right: none;\">$kd_prog</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;\">$nm_prog</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Sasaran Program</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td colspan =\"2\" align=\"left\" style=\"vertical-align:top;border-left: none;\">$sasaran_prog</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Capaian Program</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td colspan =\"2\" align=\"left\" style=\"vertical-align:top;border-left: none;\">$capaian_prog</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Kegiatan</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;border-right: none;\">$kd_giat</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;\">$nm_giat</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Organisasi</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;border-right: none;\">".substr($kd_skpd,0,17)."</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;\">$nm_skpd</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Unit Organisasi</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;border-right: none;\">$kd_skpd</td>
                                <td align=\"left\" style=\"vertical-align:top;border-left: none;\">$nm_skpd</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Alokasi Tahun n - 1</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td colspan =\"2\"  align=\"left\" style=\"vertical-align:top;border-left: none;\">Rp. ".number_format($ang_lalu,"2",",",".")." (".$this->rka_model->terbilang($ang_lalu*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Alokasi Tahun</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td colspan =\"2\" align=\"left\" style=\"vertical-align:top;border-left: none;\">Rp. $totp (".$this->rka_model->terbilang($rowb->totb*1)." rupiah)</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Alokasi Tahun n + 1</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td colspan =\"2\" align=\"left\" style=\"vertical-align:top;border-left: none;\">Rp. $totp1 (".$this->rka_model->terbilang($rowb->totb*1.1)." rupiah)</td>
                            </tr>
                            <tr>
                        <td colspan=\"4\" bgcolor=\"#CCCCCC\" width=\"100%\" align=\"left\">&nbsp;</td>
                    </tr>
                        </table>    
                            
                        ";
            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td colspan=\"5\"  align=\"center\" >Indikator & Tolak Ukur Kinerja Kegiatan</td>
                        </tr>";
            $cRet .="<tr>
                     <td width=\"20%\" rowspan=\"2\" align=\"center\">Indikator </td>
                     <td width=\"40%\" colspan=\"2\" align=\"center\">Tolak Ukur Kerja </td>
                     <td width=\"40%\" colspan=\"2\" align=\"center\">Target Kinerja </td>
                    </tr>";       
            $cRet .="<tr>
                     <td width=\"20%\"  align=\"center\">Utama </td>
                     <td width=\"20%\"  align=\"center\">Penunjang</td>
                     <td width=\"20%\"  align=\"center\">Utama </td>
                     <td width=\"20%\"  align=\"center\">Penunjang</td>
                    </tr>";       

            $cRet .=" <tr align=\"center\">
                        <td >Capaian Kegiatan </td>
                        <td>$tu_capai</td>
                        <td>$tu_capai_p</td>
                        <td>$tk_capai</td>
                        <td>$tk_capai_p</td>
                     </tr>";
            $cRet .=" <tr align=\"center\">
                        <td>Masukan </td>
                        <td>$tu_mas</td>
                        <td>$tu_mas_p</td>
                        <td>$tk_mas</td>
                        <td>$tk_mas_p</td>
                    </tr>";
            $cRet .=" <tr align=\"center\">
                        <td>Keluaran </td>
                        <td>$tu_kel</td>
                        <td>$tu_kel_p</td>
                        <td>$tk_kel</td>
                        <td>$tk_kel_p</td>
                      </tr>";
            $cRet .=" <tr align=\"center\">
                        <td>Hasil </td>
                        <td>$tu_has</td>
                        <td>$tu_has_p</td>
                        <td>$tk_has</td>
                        <td>$tk_has_p</td>
                      </tr>";
            $cRet .= "<tr>
                        <td colspan=\"5\"  width=\"100%\" align=\"left\">Kelompok Sasaran Kegiatan : $sas_giat</td>
                    </tr>";
            $cRet .= "<tr>
                        <td colspan=\"5\" width=\"100%\" align=\"left\">&nbsp;</td>
                    </tr>"; 
                    $cRet .= "<tr>
                        <td colspan=\"5\" bgcolor=\"#CCCCCC\" width=\"100%\" align=\"left\">&nbsp;</td>
                    </tr>";                
            
            $cRet .= "<tr>
                            <td colspan=\"5\" align=\"center\">RINCIAN ANGGARAN BELANJA KEGIATAN SATUAN KERJA PERANGKAT DAERAH</td>
                      </tr>";
                        
            $cRet .="</table>";
    //rincian sub kegiatan
                    

                   $sqlsub="SELECT a.kd_sub_kegiatan as kd_sub_kegiatan,b.nm_sub_kegiatan,b.sub_keluaran,b.lokasi,b.waktu_giat,b.waktu_giat2,b.keterangan FROM trdrka_rancang a
                    left join trskpd_rancang b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    WHERE left(a.kd_sub_kegiatan,12)='$giat' AND a.kd_skpd='$id'
                    group by a.kd_sub_kegiatan,b.nm_sub_kegiatan,b.sub_keluaran,b.lokasi,b.waktu_giat,b.waktu_giat2,b.keterangan";
                     $sqlbsub=$this->db->query($sqlsub);
                     foreach ($sqlbsub->result() as $rowsub)
                    {
                       $sub         =$rowsub->kd_sub_kegiatan;
                       $nm_sub      =$rowsub->nm_sub_kegiatan;
                       $sub_keluaran=$rowsub->sub_keluaran;
                       $lokasi      =$rowsub->lokasi;
                       $waktu_giat  =$rowsub->waktu_giat;
                       $waktu_giat2  =$rowsub->waktu_giat2;
                       $keterangan  =$rowsub->keterangan;


                    $sqlsumber="SELECT kd_sumberdana,sumber FROM v_sumber1 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber1  = $rowsumber->sumber;
                        $kdsumber1  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber2 FROM v_sumber2 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber2  = $rowsumber->sumber2;
                        $kdsumber2  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber3 FROM v_sumber3 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber3  = $rowsumber->sumber3;
                        $kdsumber3  = $rowsumber->kd_sumberdana;
                        
                    }

                    $sqlsumber="SELECT kd_sumberdana,sumber4 FROM v_sumber4 where kd_skpd='$id' and kd_sub_kegiatan='$sub'";
                     $csqlsumber=$this->db->query($sqlsumber);
                     foreach ($csqlsumber->result() as $rowsumber)
                    {
                       
                        $nmsumber4  = $rowsumber->sumber4;
                        $kdsumber4  = $rowsumber->kd_sumberdana;
                        
                    }

                    if ($kdsumber2==''){
                        $kodesumberdana=$kdsumber1.' '.$nmsumber1;
                    }else if ($kdsumber2==''){
                        $kodesumberdana=$kdsumber1.' '.$nmsumber1.'<br />'.$kdsumber2.' '.$nmsumber2;                    
                    }else if($kdsumber3==''){
                        $kodesumberdana=$kdsumber1.' '.$nmsumber1.'<br />'.$kdsumber2.' '.$nmsumber2.'<br />'.$kdsumber3.' '.$nmsumber3;
                    }else{
                        $kodesumberdana=$kdsumber1.' '.$nmsumber1.'<br />'.$kdsumber2.' '.$nmsumber2.'<br />'.$kdsumber3.' '.$nmsumber3.'<br />'.$kdsumber4.' '.$nmsumber4;    
                    }


                    


                        $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Sub Kegiatan</td>
                                <td width=\"5%\"  style=\"vertical-align:top;border-left: none;border-right: none;\" align=\"center\">:</td>
                                <td width=\"75%\" colspan=\"3\" style=\"vertical-align:top;border-left: none;\" align=\"left\">$sub - $nm_sub</td>
                            </tr>
                            <tr>
                                <td width=\"20%\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Sumber Dana</td>
                                <td width=\"5%\"  style=\"vertical-align:top;border-left: none;border-right: none;\" align=\"center\">:</td>
                                <td width=\"35%\" style=\"vertical-align:top;border-left: none;\" align=\"left\">$kodesumberdana</td>
                                <td width=\"10%\" style=\"vertical-align:top;border-right: none;\" align=\"left\">Lokasi</td>
                                <td width=\"35%\" style=\"vertical-align:top;border-left: none;\" align=\"left\">:&nbsp;$lokasi</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Sub Keluaran</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td align=\"left\" colspan=\"3\" style=\"vertical-align:top;border-left: none;\">$sub_keluaran</td>
                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Waktu Pelaksanaan</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td width=\"35%\" style=\"vertical-align:top;border-left: none;border-right: none;\" align=\"left\">Mulai:&nbsp;$waktu_giat</td>
                                <td width=\"10%\" style=\"vertical-align:top;border-right: none;border-left: none;\" align=\"left\">Sampai</td>
                                <td width=\"35%\" style=\"vertical-align:top;border-left: none;\" align=\"left\">:&nbsp;$waktu_giat2</td>

                            </tr>
                            <tr>
                                <td align=\"left\" style=\"vertical-align:top;border-right: none;\" align=\"left\">&nbsp;Keterangan</td>
                                <td align=\"center\" style=\"vertical-align:top;border-left: none;border-right: none;\">:</td>
                                <td align=\"left\" colspan=\"3\" style=\"vertical-align:top;border-left: none;\">$keterangan</td>
                            </tr>
                        </table>    
                            
                        ";

                        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                              <thead>                 
                            <tr><td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>                            
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>Uraian</b></td>
                                <td colspan=\"3\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>Rincian Perhitungan</b></td>
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah(Rp.)</b></td></tr>
                            <tr>
                                <td width=\"8%\" bgcolor=\"#CCCCCC\" align=\"center\">Volume</td>
                                <td width=\"8%\" bgcolor=\"#CCCCCC\" align=\"center\">Satuan</td>
                                <td width=\"14%\" bgcolor=\"#CCCCCC\" align=\"center\">harga</td>
                            </tr>    
                         
                        </thead> 
                         
                           <tr>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" align=\"center\" width=\"10%\">&nbsp;1</td>                            
                                <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" align=\"center\" width=\"40%\">&nbsp;2</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" align=\"center\" width=\"8%\">&nbsp;3</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" align=\"center\" width=\"8%\">&nbsp;4</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" align=\"center\" width=\"14%\">&nbsp;5</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: solid 1px black;\" align=\"center\" width=\"20%\">&nbsp;6</td>
                            </tr>
                            ";

                            $sql1="SELECT * FROM(SELECT 0 header,0 no_po, LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka_rancang a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE a.kd_sub_kegiatan='$sub' AND a.kd_skpd='$id' 
    GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
    UNION ALL 
    SELECT 0 header, 0 no_po,LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'2' AS id FROM trdrka_rancang a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
    UNION ALL  
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka_rancang a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
    UNION ALL 
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka_rancang a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
    UNION ALL 
    SELECT 0 header, 0 no_po, LEFT(a.kd_rek6,8) AS rek1,RTRIM(LEFT(a.kd_rek6,8)) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM trdrka_rancang a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5
    UNION ALL
    SELECT 0 header, 0 no_po, a.kd_rek6 AS rek1,RTRIM(a.kd_rek6) AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan,
    0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM trdrka_rancang a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE a.kd_sub_kegiatan='$sub'
    AND a.kd_skpd='$id'  GROUP BY a.kd_rek6,b.nm_rek6
    UNION ALL 
    SELECT * FROM (SELECT  b.header,b.no_po,RIGHT(a.no_trdrka,11) AS rek1,' 'AS rek,b.uraian AS nama,0 AS volume,' ' AS satuan,
    0 AS harga,SUM(a.total) AS nilai,'7' AS id 
    FROM trdpo_rancang a
    LEFT JOIN trdpo_rancang b ON b.kode=a.kode AND b.header ='1' AND a.no_trdrka=b.no_trdrka 
    WHERE LEFT(a.no_trdrka,20)='$id' AND SUBSTRING(a.no_trdrka,22,15)='$sub'
    GROUP BY  RIGHT(a.no_trdrka,11),b.header,b.no_po,b.uraian)z WHERE header='1'
    UNION ALL
    SELECT a. header,a.no_po,RIGHT(a.no_trdrka,11) AS rek1,' 'AS rek,a.kd_barang+' '+a.uraian AS nama,a.volume1 AS volume,a.satuan1 AS satuan,
    a.harga1 AS harga,a.total AS nilai,'8' AS id FROM trdpo_rancang a  WHERE LEFT(a.no_trdrka,20)='$id' AND SUBSTRING(no_trdrka,22,15)='$sub' AND (header='0' or header is null)
    ) a ORDER BY a.rek1,a.no_po

    ";
                     
                    $query = $this->db->query($sql1);
                    $nilangsub=0;

                            foreach ($query->result() as $row)
                            {
                                $rek=$row->rek;
                                $reke=$this->dotrek($rek);
                                $uraian=$row->nama;
                            //    $volum=$row->volume;
                                $sat=$row->satuan;
                                $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                                $volum= empty($row->volume) || $row->volume == 0 ? '' :$row->volume;

                                //$hrg=number_format($row->harga,"2",".",",");
                                $nila= empty($row->nilai) || $row->nilai == 0 ? '' :number_format($row->nilai,2,',','.');

                                        
                                

                                if ($row->id<'7'){
                               
                                 $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$reke</td>                                     
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$uraian</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\" align=\"right\">$volum&nbsp;&nbsp;&nbsp;</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\" align=\"center\">$sat</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">$hrg</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nila</td></tr>
                                                 ";

                                             }else{
                                                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$reke</td>                                     
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;$uraian</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\" align=\"right\">$volum&nbsp;&nbsp;&nbsp;</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\" align=\"center\">$sat</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">$hrg</td>
                                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nila</td></tr>
                                                 ";
                                                 $nilangsub= $nilangsub+$row->nilai;        
                                             }
                                             
                            }

                            $cRet    .=" 
                                        <tr>                                    
                                         <td colspan=\"5\" align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Anggaran Sub Kegiatan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".number_format($nilangsub,2,',','.')."</td></tr>
                                         <tr>                                    
                                         <td colspan=\"6\"  align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"40%\">&nbsp;</td></tr>
                                         </table>";
                    }

                    


                            $cRet    .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\"> 
                                        

                                         <tr>                                    
                                         <td colspan=\"5\" align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Anggaran Kegiatan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$totp</td></tr>
                                         </table>";
            



            
                     
                        // $cRet .= "<tr>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td>&nbsp;</td>
                        //             <td align=\"right\">&nbsp;</td>
                        //          </tr>";
                     
                       
                        // $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Belanja</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">&nbsp;</td>
                        //                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$totp</td></tr>
                        //                  </table>";
                      
                             $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">";

                     $kd_ttd=substr($id,18,2);
                     $kd_kepala=substr($id,0,7);
                     if ((($kd_ttd=='01') && ($kd_kepala!='1.20.03'))){
                        $cRet .="<tr>
                                    <td width=\"100%\" align=\"right\" colspan=\"6\">
                                    <table border=\"0\">
                                    <tr>
                                    <td width=\"40%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    
                                    <td width=\"60%\" align=\"center\">$daerah,&nbsp;&nbsp;$tanggal_ttd
                                    <br> $kuasa
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><u><b>$nama</b></u>
                                    <br>$pangkat 
                                    <br>NIP.$nip 
                                    </td>
                                    </tr></table></td>
                                 </tr>";
                                 } else {
                                 $cRet .="<tr>
                                    <td width=\"100\" align=\"center\" colspan=\"6\">                           
                                    <table border=\"0\">
                                    <tr>
                                    
                                    <td width=\"40%\" align=\"center\">
                                    <br>$kuasa2
                                    <br>$jabatan2,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama2</u></b>
                                    <br>$pangkat2 
                                    <br>NIP. $nip2 
                                    </td>
                                    <td width=\"20%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"40%\" align=\"center\">$daerah,&nbsp;&nbsp;$tanggal_ttd
                                    <br>$kuasa
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama</u></b>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    </td></tr></table>
                                    </td>
                                 </tr>";
                                 
                                 }
                                 
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\" style=\"vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;\">Keterangan :</td>
                                </tr>";
                      $cRet .= "<tr>
                                     <td width=\"100%\" align=\"left\" colspan=\"6\" style=\"vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;\">Tanggal Pembahasan :</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\" style=\"vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;\">Catatan Hasil Pembahasan :</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\" style=\"vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;\">1.</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\" style=\"vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;\">2.</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\" style=\"vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;\">Dst</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"center\" colspan=\"6\" style=\"vertical-align:top;border-right: solid 1px black;border-left: solid 1px black;border-top: solid 1px black;\">Tim Anggaran Pemerintah Daerah</td>
                                </tr>";
                      
                                
                     
                     
            
                  
            $cRet    .= "</table>";
             $cRet    .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr>
                             <td width=\"10%\" align=\"center\">No </td>
                             <td width=\"30%\"  align=\"center\">Nama</td>
                             <td width=\"20%\"  align=\"center\">NIP</td>
                             <td width=\"20%\"  align=\"center\">Jabatan</td>
                             <td width=\"20%\"  align=\"center\">Tandatangan</td>
                        </tr>";
                        $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
                         $sqltapd = $this->db->query($sqltim);
                      if ($sqltapd->num_rows() > 0){
                        
                        $no=1;
                        foreach ($sqltapd->result() as $rowtim)
                        {
                            $no=$no;                    
                            $nama= $rowtim->nama;
                            $nip= $rowtim->nip;
                            $jabatan  = $rowtim->jab;
                            $cRet .="<tr>
                             <td width=\"5%\" align=\"left\">$no </td>
                             <td width=\"20%\"  align=\"left\">$nama</td>
                             <td width=\"20%\"  align=\"left\">$nip</td>
                             <td width=\"35%\"  align=\"left\">$jabatan</td>
                             <td width=\"20%\"  align=\"left\"></td>
                        </tr>"; 
                        $no=$no+1;              
                      }}
                        else{
                            $cRet .="<tr>
                             <td width=\"5%\" align=\"left\"> 1. </td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"35%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                            </tr>
                            <tr>
                             <td width=\"5%\" align=\"left\"> 2. </td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"35%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                            </tr>
                            <tr>
                             <td width=\"5%\" align=\"left\"> 3. </td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"35%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                            </tr>
                            <tr>
                             <td width=\"5%\" align=\"left\"> 4. </td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"35%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                            </tr>"; 
                        }

            $cRet .=       " </table>";
            $data['prev']= $cRet;    
            $judul='RKA-rincian_belanja_'.$id.'';
            switch($cetak) { 
            case 1;

                 $this->_mpdf_margin('',$cRet,$kanan,$kiri,10,'1','',$atas,$bawah);
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0;  
             echo ("<title>RKA Rincian Belanja</title>");
                echo($cRet);
            break;
            }
        }

        function _mpdf($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='') {
                    

            ini_set("memory_limit","-1M");
            ini_set("MAX_EXECUTION_TIME","-1");
            $this->load->library('mpdf');
            //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
            
            
            $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
            $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
            $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

            $this->mpdf->defaultfooterfontsize = 3; /* in pts */
            $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
            $this->mpdf->defaultfooterline = 1; 
            $sa=1;
            $tes=0;
            if ($hal==''){
            $hal1=1;
            } 
            if($hal!==''){
            $hal1=$hal;
            }
            if ($fonsize==''){
            $size=12;
            }else{
            $size=$fonsize;
            } 
            
            $this->mpdf = new mPDF('utf-8', array(215,330),$size); //folio
                                //$this->mpdf->useOddEven = 1;                      

            $this->mpdf->AddPage($orientasi,'',$hal,'1','off');
            if ($hal==''){
                $this->mpdf->SetFooter("");
            }
            else{
                $this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
            }
            if (!empty($judul)) $this->mpdf->writeHTML($judul);
            $this->mpdf->writeHTML($isi);         
            $this->mpdf->Output();
        }


         function _mpdf_margin($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='',$atas='', $bawah='', $kiri='', $kanan='') {
                    

            ini_set("memory_limit","-1M");
            ini_set("MAX_EXECUTION_TIME","-1");
            $this->load->library('mpdf');
            //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
            
            
            $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
            $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
            $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

            $this->mpdf->defaultfooterfontsize = 3; /* in pts */
            $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
            $this->mpdf->defaultfooterline = 1; 
            $sa=1;
            $tes=0;
            if ($hal==''){
            $hal1=1;
            } 
            if($hal!==''){
            $hal1=$hal;
            }
            if ($fonsize==''){
            $size=12;
            }else{
            $size=$fonsize;
            } 
            
            $this->mpdf = new mPDF('utf-8', array(215,330),$size); //folio
            $this->mpdf->AddPage($orientasi,'',$hal,'1','off',$kiri,$kanan,$atas,$bawah);
            if ($hal==''){
                $this->mpdf->SetFooter("");
            }
            else{
                $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
            }
            if (!empty($judul)) $this->mpdf->writeHTML($judul);
            $this->mpdf->writeHTML($isi);         
            $this->mpdf->Output();
                   
        }

         function right($value, $count){
        return substr($value, ($count*-1));
        }

        function left($string, $count){
        return substr($string, 0, $count);
        }

        function  dotrek($rek){
                    $nrek=strlen($rek);
                    switch ($nrek) {
                    case 1:
                    $rek = $this->left($rek,1);                             
                     break;
                    case 2:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1);                                
                     break;
                    case 4:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2);                               
                     break;
                    case 6:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2);                              
                    break;
                    case 8:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2);                             
                    break;
                    case 11:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2).'.'.substr($rek,8,3); ;                             
                    break;
                    default:
                    $rek = "";  
                    }
                    return $rek;
        }


        function load_tanda_tangan($skpd='') {
            $kd_skpd = $this->session->userdata('kdskpd'); 
            $kd_skpd2= $this->left($kd_skpd,17);
            $lccr='';        
            $lccr = $this->input->post('q');        
            $sql = "SELECT * FROM ms_ttd WHERE (left(kd_skpd,17)= '$kd_skpd2' AND kode in ('PA','KPA'))  AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";   

             
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;        
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'nip' => $resulte['nip'],  
                            'nama' => $resulte['nama']      
                            );
                            $ii++;
            }           
               
            echo json_encode($result);
            $query1->free_result();
               
        }

        function load_tanda_tangan2() {
            $kd_skpd = $this->session->userdata('kdskpd'); 
            $kd_skpd2= $this->left($kd_skpd,17);
            $lccr='';        
            $lccr = $this->input->post('q');        
            $sql = "SELECT * FROM ms_ttd where kode in ('PA','KPA') and  left(kd_skpd,17)= '$kd_skpd2'";

             /*"WHERE (kd_skpd= '$kd_skpd' or kd_skpd=left('$kd_skpd',7)+'.01' or kd_skpd=left('$kd_skpd',7)+'.00') AND kode in ('PA','KPA')  AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";    
            */
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;        
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'nip' => $resulte['nip'],  
                            'nama' => $resulte['nama']      
                            );
                            $ii++;
            }           
               
            echo json_encode($result);
            $query1->free_result();
               
        }


        function  tanggal_format_indonesia($tgl){
                
            $tanggal  = explode('-',$tgl); 
            $bulan  = $this-> getBulan($tanggal[1]);
            $tahun  =  $tanggal[0];
            return  $tanggal[2].' '.$bulan.' '.$tahun;

        }
        

     
     
        function  getBulan($bln){
            switch  ($bln){
            case  1:
            return  "Januari";
            break;
            case  2:
            return  "Februari";
            break;
            case  3:
            return  "Maret";
            break;
            case  4:
            return  "Maret";
            break;
            case  5:
            return  "Mei";
            break;
            case  6:
            return  "Juni";
            break;
            case  7:
            return  "Juli";
            break;
            case  8:
            return  "Agustus";
            break;
            case  9:
            return  "September";
            break;
            case  10:
            return  "Oktober";
            break;
            case  11:
            return  "November";
            break;
            case  12:
            return  "Desember";
            break;
        }
        }

         function rka0_penyusunan()
        {
            $data['page_title']= 'CETAK';
            $this->template->set('title', 'Cetak RKA 0 Penyusunan');   
            $this->template->load('template','anggaran/rka/penyusunan/rka0_penyusunan',$data) ; 
        }


        

        function preview_rka0_penyusunan(){
            $id = $this->uri->segment(2);
            $cetak = $this->uri->segment(3);
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $ttd1 = str_replace('a',' ',$ttd1); 
            $ttd2 = str_replace('a',' ',$ttd2); 
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
           
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND nip='$ttd1' ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                    }
                    
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') and nip='$ttd2' ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip; 
                        $pangkat2=$rowttd2->pangkat;  
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                    }
           $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,17) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
    a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
     ON a.kd_urusan=b.kd_bidang_urusan WHERE  kd_skpd='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                        $header  = $rowdns->header;
                        $kd_org = $rowdns->kd_org;
                    }
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr> <td width=\"20%\" rowspan=\"4\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" /></td>
                             <td width=\"80%\" align=\"center\"><strong>RENCANA KERJA DAN ANGGARAN <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"4\" align=\"center\"><strong>FORMULIR <br>RKA - SKPD</strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>$kab <br >TAHUN ANGGARAN $thn</strong> </td>
                        </tr>

                      </table>";
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td width=\"20%\">Urusan Pemerintahan </td>
                            <td width=\"80%\">$kd_urusan - $nm_urusan</td>
                        </tr>
                        <tr>
                            <td width=\"20%\">Organisasi </td>
                            <td width=\"80%\">$kd_org - ".$this->rka_model->get_nama($kd_org,'nm_org','ms_organisasi','left(kd_org,17)')."</td>
                        </tr>
                        <tr>
                            <td>Unit Organisasi</td>
                            <td>$kd_skpd - $nm_skpd</td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ align=\"center\"><strong>Ringkasan Anggaran Pendapatan dan Belanja<br>Satuan Kerja Perangkat Daerah </strong></td>
                        </tr>
                    </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                         <thead>                       
                            <tr>
                                <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE REKENING</b></td>                            
                                <td bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>JUMLAH(Rp.)</b></td>
                            </tr>
                         </thead>
                         
                            <tr>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"70%\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            </tr>
                            ";
                     $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,2)='41' 
    and b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 

    UNION ALL 

    SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka_rancang b 
    ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,2)='41' and b.kd_skpd='$id' 
    GROUP BY a.kd_rek2,a.nm_rek2 

    UNION ALL 

    SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek ,
    SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
     where left(b.kd_rek6,2)='41' and b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                     if ($query->num_rows() > 0){                                  
                    foreach ($query->result() as $row)
                    {
                        $coba1=$this->dotrek($row->kd_rek);
                        $coba2=$row->nm_rek;
                        $coba3= number_format($row->nilai,"2",",",".");
                       
                         $cRet    .= " <tr>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba1</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba2</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td>
                                        </tr>";
                                        
                    }
                    }else{
                        $cRet    .= " <tr>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">4</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">PENDAPATAN</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".number_format(0,"2",",",".")."</td>
                                    </tr>";
                        
                    
                    }                                 
                    
                    $sqltp="SELECT SUM(nilai) AS totp FROM trdrka_rancang WHERE LEFT(kd_rek6,2)='41' and kd_skpd='$id'";
                     $sqlp=$this->db->query($sqltp);
                     foreach ($sqlp->result() as $rowp)
                    {
                       $coba4=number_format($rowp->totp,"2",",",".");
                        $cob1=$rowp->totp;
                       
                        $cRet    .= " <tr>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Pendapatan</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba4</td></tr>
                                      <tr>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                      </tr>";
                     }     
                    $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5' AND b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $query1 = $this->db->query($sql2);
                     foreach ($query1->result() as $row1)
                    {
                        $coba5=$this->dotrek($row1->rek);
                        $coba6=$row1->nm_rek;
                        $coba7= number_format($row1->nilai,"2",",",".");
                       
                         $cRet    .= " <tr>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba6</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td>
                                       </tr>";
                    }
                    
                        $sqltb="SELECT SUM(nilai) AS totb FROM trdrka_rancang WHERE LEFT(kd_rek6,1)='5' and kd_skpd='$id'";
                        $sqlb=$this->db->query($sqltb);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $coba8=number_format($rowb->totb,"2",",",".");
                        $cob=$rowb->totb;
                        $cRet    .= " <tr>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Belanja</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba8</td>
                                    </tr>";
                     }


                      
                      $surplus=$cob1-$cob; 
                        $cRet    .= " <tr>
                                        <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\" width=\"70%\">Surplus/Defisit</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".$this->rka_model->angka($surplus)."</td>
                                    </tr>"; 
                        
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">6</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">PEMBIAYAAN</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td></tr>";
    //pembiayaan
    $sqlpm="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $querypm = $this->db->query($sqlpm);
                     foreach ($querypm->result() as $rowpm)
                    {
                        $coba9=$this->dotrek($rowpm->rek);
                        $coba10=$rowpm->nm_rek;
                        $coba11= number_format($rowpm->nilai,"2",",",".");
                
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                    } 


    $sqltpm="SELECT SUM(nilai) AS totb FROM trdrka_rancang WHERE LEFT(kd_rek6,2)='61' and kd_skpd='$id'";
                        $sqltpm=$this->db->query($sqltpm);
                     foreach ($sqltpm->result() as $rowtpm)
                    {
                       $coba12=number_format($rowtpm->totb,"2",",",".");
                        $cobtpm=$rowtpm->totb;
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Penerimaan Pembiayaan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba12</td></tr>";
                     } 

                       


    //pembiayaan
    $sqlpk="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND b.kd_skpd='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $querypk= $this->db->query($sqlpk);
                     foreach ($querypk->result() as $rowpk)
                    {
                        $coba9=$this->dotrek($rowpk->rek);
                        $coba10=$rowpk->nm_rek;
                        $coba11= number_format($rowpk->nilai,"2",",",".");
                       
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                    } 


    $sqltpk="SELECT SUM(nilai) AS totb FROM trdrka_rancang WHERE LEFT(kd_rek6,2)='62' and kd_skpd='$id'";
                        $sqltpk=$this->db->query($sqltpk);
                     foreach ($sqltpk->result() as $rowtpk)
                    {
                       $cobatpk=number_format($rowtpk->totb,"2",",",".");
                        $cobtpk=$rowtpk->totb;
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Pengeluaran Pembiayaan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba12</td></tr>";
                     }
        
          $pnetto=$cobtpm-$cobtpk;
                        $cRet    .= " <tr>                                     
                                         <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\" width=\"70%\">Pembiayaan Netto</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".$this->rka_model->angka($pnetto)."</td></tr></table>";                                                      


                        $kd_ttd=substr($id,18,2);
                     $kd_kepala=substr($id,0,7);
                    if ((($kd_ttd=='01') && ($kd_kepala!='1.20.03'))|| ($id=='1.20.03.00')){
                        $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\"><tr>
                                    <td width=\"100%\" align=\"right\" colspan=\"6\">
                                    <table border=\"0\"  align=\"right\">
                                    <tr>
                                    <td width=\"35%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"5%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"40%\" align=\"center\">$daerah ,$tanggal_ttd
                                    <br> Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><u><b>$nama</b></u>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    <td width=\"10%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td></td>
                                    </tr></table></td>
                                 </tr>";
                                 } else{
                                 $cRet .="<tr>
                                    <td width=\"100\" align=\"center\" colspan=\"6\">                           
                                    <table border=\"0\"  align=\"right\">
                                    <tr>
                                    <td width=\"10%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp;
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;
                                    </td>
                                    <td width=\"40%\" align=\"center\">Mengetahui,
                                    <br>$jabatan2,
                                    <p>&nbsp;</p>
                                    <br><b>$nama2</b>
                                    <br>$pangkat2
                                    <br>NIP. $nip2 
                                    </td>
                                    
                                    <td colspan=\"2\" width=\"50%\" align=\"center\">$daerah ,$tanggal_ttd
                                    <br>Kuasa Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama</u></b>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    </td></tr></table></td>
                                 </tr>";
                                 
                                 }
                                 
                         $cRet    .= "</table>";
            // $cRet    .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
            //             <tr>
            //                  <td width=\"10%\" align=\"center\">No </td>
            //                  <td width=\"30%\"  align=\"center\">Nama</td>
            //                  <td width=\"20%\"  align=\"center\">NIP</td>
            //                  <td width=\"20%\"  align=\"center\">Jabatan</td>
            //                  <td width=\"20%\"  align=\"center\">Tandatangan</td>
            //             </tr>";
            //             $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
            //              $sqltapd = $this->db->query($sqltim);
            //           if ($sqltapd->num_rows() > 0){
                        
            //             $no=1;
            //             foreach ($sqltapd->result() as $rowtim)
            //             {
            //                 $no=$no;                    
            //                 $nama= $rowtim->nama;
            //                 $nip= $rowtim->nip;
            //                 $jabatan  = $rowtim->jab;
            //                 $cRet .="<tr>
            //                  <td width=\"5%\" align=\"left\">$no </td>
            //                  <td width=\"20%\"  align=\"left\">$nama</td>
            //                  <td width=\"20%\"  align=\"left\">$nip</td>
            //                  <td width=\"35%\"  align=\"left\">$jabatan</td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //             </tr>"; 
            //             $no=$no+1;              
            //           }}
            //             else{
            //                 $cRet .="<tr>
            //                  <td width=\"5%\" align=\"left\"> &nbsp; </td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //                  <td width=\"35%\"  align=\"left\"></td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //                 </tr>"; 
            //             } 

            
                  
            // $cRet    .= "</table>";
            $data['prev']= $cRet;    
            //$this->_mpdf('',$cRet,10,10,10,0);
            $judul         = 'RKA SKPD';
            //$this->template->load('template','master/fungsi/list_preview',$data);
            switch($cetak) { 
            case 1;
                 $this->_mpdf('',$cRet,10,10,10,'0');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                //$this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0;
            echo ("<title>RKA SKPD</title>");
            echo($cRet);
            break;
            }
                    
        } 


         function preview_rka0_penyusunan_org(){
            $ids = $this->uri->segment(2);
            $id = substr($this->uri->segment(2),0,17);
            $cetak = $this->uri->segment(3);
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $ttd1 = str_replace('a',' ',$ttd1); 
            $ttd2 = str_replace('a',' ',$ttd2); 
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
           
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$ids'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = '';//$this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND nip='$ttd1' ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                    }
                    
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE kode in ('PA','KPA') and nip='$ttd2' ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip; 
                        $pangkat2=$rowttd2->pangkat;  
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                    }
           $sqldns="SELECT a.kd_urusan as kd_u,left(b.kd_bidang_urusan,1) as header, LEFT(a.kd_skpd,17) as kd_org,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,
    a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b
     ON a.kd_urusan=b.kd_bidang_urusan WHERE  LEFT(a.kd_skpd,17)='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                        $header  = $rowdns->header;
                        $kd_org = $rowdns->kd_org;
                    }
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr> <td width=\"20%\" rowspan=\"4\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" /></td>
                             <td width=\"80%\" align=\"center\"><strong>RENCANA KERJA DAN ANGGARAN <br />SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"4\" align=\"center\"><strong>FORMULIR <br>RKA - SKPD</strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>$kab <br />TAHUN ANGGARAN $thn</strong> </td>
                        </tr>

                      </table>";
                      // <tr>
                      //       <td width=\"20%\">Urusan Pemerintahan </td>
                      //       <td width=\"80%\">$kd_urusan - $nm_urusan</td>
                      //   </tr>

                      //   <tr>
                      //       <td>Unit Organisasi</td>
                      //       <td>$kd_skpd - $nm_skpd</td>
                      //   </tr>
                      //   <tr>

            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                        
                        <tr>
                            <td width=\"20%\">Organisasi </td>
                            <td width=\"80%\">$kd_org - ".$this->rka_model->get_nama($kd_org,'nm_org','ms_organisasi','left(kd_org,17)')."</td>
                        </tr>
                        <tr>
                           <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ align=\"center\"><strong>Ringkasan Anggaran Pendapatan dan Belanja<br>Satuan Kerja Perangkat Daerah </strong></td>
                        </tr>
                    </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                         <thead>                       
                            <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE REKENING</b></td>                            
                                <td bgcolor=\"#CCCCCC\" width=\"70%\" align=\"center\"><b>URAIAN</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>JUMLAH(Rp.)</b></td></tr>
                         </thead>
                         
                            <tr>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">1</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"70%\" align=\"center\">2</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">3</td>
                            </tr>
                            ";
                     $sql1="SELECT a.kd_rek1 AS kd_rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) where left(b.kd_rek6,2)='41' 
    and LEFT(b.kd_skpd,17)='$id' GROUP BY a.kd_rek1, a.nm_rek1 

    UNION ALL 

    SELECT a.kd_rek2 AS kd_rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka_rancang b 
    ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) where left(b.kd_rek6,2)='41' and LEFT(b.kd_skpd,17)='$id' 
    GROUP BY a.kd_rek2,a.nm_rek2 

    UNION ALL 

    SELECT a.kd_rek3 AS kd_rek,a.nm_rek3 AS nm_rek ,
    SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3)))
     where left(b.kd_rek6,2)='41' and LEFT(b.kd_skpd,17)='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                     if ($query->num_rows() > 0){                                  
                    foreach ($query->result() as $row)
                    {
                        $coba1=$this->dotrek($row->kd_rek);
                        $coba2=$row->nm_rek;
                        $coba3= number_format($row->nilai,"2",",",".");
                       
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba1 </td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba2</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td></tr>";
                                        
                    }
                    }else{
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">4</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">PENDAPATAN</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".number_format(0,"2",",",".")."</td></tr>";
                        
                    
                    }                                 
                    
                    $sqltp="SELECT SUM(nilai) AS totp FROM trdrka_rancang WHERE LEFT(kd_rek6,2)='41' and LEFT(kd_skpd,17)='$id'";
                     $sqlp=$this->db->query($sqltp);
                     foreach ($sqlp->result() as $rowp)
                    {
                       $coba4=number_format($rowp->totp,"2",",",".");
                        $cob1=$rowp->totp;
                       
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Pendapatan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba4</td></tr>
                                      <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td></tr>";
                     }     
                    $sql2="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,1)='5' AND LEFT(b.kd_skpd,17)='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,1)='5' AND LEFT(b.kd_skpd,17)='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,1)='5' AND LEFT(b.kd_skpd,17)='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $query1 = $this->db->query($sql2);
                     foreach ($query1->result() as $row1)
                    {
                        $coba5=$this->dotrek($row1->rek);
                        $coba6=$row1->nm_rek;
                        $coba7= number_format($row1->nilai,"2",",",".");
                       
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba6</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";
                    }
                    
                        $sqltb="SELECT SUM(nilai) AS totb FROM trdrka_rancang WHERE LEFT(kd_rek6,1)='5' and LEFT(kd_skpd,17)='$id'";
                        $sqlb=$this->db->query($sqltb);
                     foreach ($sqlb->result() as $rowb)
                    {
                       $coba8=number_format($rowb->totb,"2",",",".");
                        $cob=$rowb->totb;
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Belanja</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba8</td></tr>";
                     }


                      
                      $surplus=$cob1-$cob; 
                        $cRet    .= " <tr>                                     
                                         <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\" width=\"70%\">Surplus/Defisit</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".$this->rka_model->angka($surplus)."</td></tr>"; 
                        
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">6</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">PEMBIAYAAN</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td></tr>";
    //pembiayaan
    $sqlpm="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,2)='61' AND LEFT(b.kd_skpd,17)='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='61' AND LEFT(b.kd_skpd,17)='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='61' AND LEFT(b.kd_skpd,17)='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $querypm = $this->db->query($sqlpm);
                     foreach ($querypm->result() as $rowpm)
                    {
                        $coba9=$this->dotrek($rowpm->rek);
                        $coba10=$rowpm->nm_rek;
                        $coba11= number_format($rowpm->nilai,"2",",",".");
                
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                    } 


    $sqltpm="SELECT SUM(nilai) AS totb FROM trdrka_rancang WHERE LEFT(kd_rek6,2)='61' and LEFT(kd_skpd,17)='$id'";
                        $sqltpm=$this->db->query($sqltpm);
                     foreach ($sqltpm->result() as $rowtpm)
                    {
                       $coba12=number_format($rowtpm->totb,"2",",",".");
                        $cobtpm=$rowtpm->totb;
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Penerimaan Pembiayaan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba12</td></tr>";
                     } 

                       


    //pembiayaan
    $sqlpk="SELECT a.kd_rek1 AS kd_rek, a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek1=LEFT(b.kd_rek6,(len(a.kd_rek1))) WHERE LEFT(kd_rek6,2)='62' AND LEFT(b.kd_skpd,17)='$id' GROUP BY a.kd_rek1, a.nm_rek1 
    UNION ALL 
    SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek2=LEFT(b.kd_rek6,(len(a.kd_rek2))) WHERE LEFT(kd_rek6,2)='62' AND LEFT(b.kd_skpd,17)='$id' GROUP BY a.kd_rek2,a.nm_rek2 
    UNION ALL 
    SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a 
    INNER JOIN trdrka_rancang b ON a.kd_rek3=LEFT(b.kd_rek6,(len(a.kd_rek3))) WHERE LEFT(kd_rek6,2)='62' AND LEFT(b.kd_skpd,17)='$id' 
    GROUP BY a.kd_rek3, a.nm_rek3 ORDER BY kd_rek";
                     
                     $querypk= $this->db->query($sqlpk);
                     foreach ($querypk->result() as $rowpk)
                    {
                        $coba9=$this->dotrek($rowpk->rek);
                        $coba10=$rowpk->nm_rek;
                        $coba11= number_format($rowpk->nilai,"2",",",".");
                       
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                    } 


    $sqltpk="SELECT SUM(nilai) AS totb FROM trdrka_rancang WHERE LEFT(kd_rek6,2)='62' and LEFT(kd_skpd,17)='$id'";
                        $sqltpk=$this->db->query($sqltpk);
                     foreach ($sqltpk->result() as $rowtpk)
                    {
                       $cobatpk=number_format($rowtpk->totb,"2",",",".");
                        $cobtpk=$rowtpk->totb;
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">Jumlah Pengeluaran Pembiayaan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba12</td></tr>";
                     }
        
          $pnetto=$cobtpm-$cobtpk;
                        $cRet    .= " <tr>                                     
                                         <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\" width=\"70%\">Pembiayaan Netto</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".$this->rka_model->angka($pnetto)."</td></tr></table>";                                                      


                        $kd_ttd=substr($ids,18,2);
                     $kd_kepala=substr($id,0,7);
                    if ((($kd_ttd=='01') && ($kd_kepala!='1.20.03'))|| ($id=='1.20.03.00')){
                        $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                                    <tr>
                                    <td width=\"100%\" align=\"right\" colspan=\"6\">
                                    <table border=\"0\"  align=\"right\">
                                    <tr>
                                    <td width=\"35%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"5%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"40%\" align=\"center\">$daerah ,$tanggal_ttd
                                    <br> Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><u><b>$nama</b></u>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    <td width=\"10%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp; 
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td></td>
                                    </tr></table></td>
                                 </tr>";
                                 } else{
                                 $cRet .="<tr>
                                    <td width=\"100\" align=\"center\" colspan=\"6\">                           
                                    <table border=\"0\"  align=\"right\">
                                    <tr>
                                    <td width=\"10%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp;
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;
                                    </td>
                                    <td width=\"40%\" align=\"center\">Mengetahui,
                                    <br>$jabatan2,
                                    <p>&nbsp;</p>
                                    <br><b>$nama2</b>
                                    <br>$pangkat2
                                    <br>NIP. $nip2 
                                    </td>
                                    
                                    <td colspan=\"2\" width=\"50%\" align=\"center\">$daerah ,$tanggal_ttd
                                    <br>Kuasa Pengguna Anggaran
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama</u></b>
                                    <br>$pangkat 
                                    <br>NIP. $nip 
                                    </td></tr></table></td>
                                 </tr>";
                                 
                                 }
                                 
                         $cRet    .= "</table>";
            // $cRet    .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
            //             <tr>
            //                  <td width=\"10%\" align=\"center\">No </td>
            //                  <td width=\"30%\"  align=\"center\">Nama</td>
            //                  <td width=\"20%\"  align=\"center\">NIP</td>
            //                  <td width=\"20%\"  align=\"center\">Jabatan</td>
            //                  <td width=\"20%\"  align=\"center\">Tandatangan</td>
            //             </tr>";
            //             $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
            //              $sqltapd = $this->db->query($sqltim);
            //           if ($sqltapd->num_rows() > 0){
                        
            //             $no=1;
            //             foreach ($sqltapd->result() as $rowtim)
            //             {
            //                 $no=$no;                    
            //                 $nama= $rowtim->nama;
            //                 $nip= $rowtim->nip;
            //                 $jabatan  = $rowtim->jab;
            //                 $cRet .="<tr>
            //                  <td width=\"5%\" align=\"left\">$no </td>
            //                  <td width=\"20%\"  align=\"left\">$nama</td>
            //                  <td width=\"20%\"  align=\"left\">$nip</td>
            //                  <td width=\"35%\"  align=\"left\">$jabatan</td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //             </tr>"; 
            //             $no=$no+1;              
            //           }}
            //             else{
            //                 $cRet .="<tr>
            //                  <td width=\"5%\" align=\"left\"> &nbsp; </td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //                  <td width=\"35%\"  align=\"left\"></td>
            //                  <td width=\"20%\"  align=\"left\"></td>
            //                 </tr>"; 
            //             } 

            
                  
            // $cRet    .= "</table>";
            $data['prev']= $cRet;    
            //$this->_mpdf('',$cRet,10,10,10,0);
            $judul         = 'RKA SKPD';
            //$this->template->load('template','master/fungsi/list_preview',$data);
            switch($cetak) { 
            case 1;
                 $this->_mpdf('',$cRet,10,10,10,'0');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                //$this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0;
            echo ("<title>RKA SKPD</title>");
            echo($cRet);
            break;
            }
                    
        } 



        function urusan() {
            $lccr = $this->input->post('q');
            $skpd = $this->input->post('skpd');
            $kd_skpd=str_replace($skpd,'-','.');
            $urusan1 =substr($kd_skpd,0,4);
            $urusan2 =substr($kd_skpd,4,4);
            $urusan3 =substr($kd_skpd,8,4);
            $sql = "SELECT kd_bidang_urusan,nm_bidang_urusan FROM ms_bidang_urusan where kd_bidang_urusan in ('$kd_skpd','$urusan1','$urusan2','$urusan3') and  upper(kd_bidang_urusan) like upper('%$lccr%') or upper(nm_bidang_urusan) like upper('%$lccr%') ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_urusan' => $resulte['kd_bidang_urusan'],  
                            'nm_urusan' => $resulte['nm_bidang_urusan'],  
                           
                            );
                            $ii++;
            }
               
            echo json_encode($result);
               
        } 



        function urusan1(){
            $lccr = $this->input->post('q');
            $skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_bidang_urusan,nm_bidang_urusan FROM ms_bidang_urusan where kd_bidang_urusan in 
(select urusan from get_urusan_kd_skpd where kd_skpd='$skpd') ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_urusan' => $resulte['kd_bidang_urusan'],  
                            'nm_urusan' => $resulte['nm_bidang_urusan'],  
                           
                            );
                            $ii++;
            }
               
            echo json_encode($result);
               
        }


                    function ld_giat_rancang($skpd='',$urusan='') { 
                        $lccr   = $this->input->post('q');
                            
                            $sql    = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,jns_sub_kegiatan FROM ms_sub_kegiatan 
                            where left(kd_sub_kegiatan,4) in (SELECT urusan from get_urusan_kd_skpd where kd_skpd='$skpd') and (kd_sub_kegiatan
                            like '%$lccr%' or nm_sub_kegiatan like '%$lccr%') and kd_sub_kegiatan not in(select kd_sub_kegiatan 
                            from trskpd_rancang where kd_skpd='$skpd') order by kd_sub_kegiatan  ";
                        

                        $query1 = $this->db->query($sql);  
                        $result = array();
                        $ii     = 0;
                        foreach($query1->result_array() as $resulte)
                        { 

                        $result[] = array(
                                'id' => $ii,        
                                'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                                'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                                'jns_kegiatan' => $resulte['jns_sub_kegiatan'],
                                'lanjut' => 'Tidak'

                        );
                        $ii++;
                        }
                        echo json_encode($result);
                    }

function select_giat_rancang($skpd='') {    
            $sql = "select a.kd_sub_kegiatan as kd_kegiatan,b.nm_sub_kegiatan,a.jns_kegiatan,a.lanjut from trskpd_rancang a inner join ms_sub_kegiatan b on b.kd_sub_kegiatan=a.kd_sub_kegiatan where a.kd_skpd='$skpd' order by a.kd_sub_kegiatan";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            {            
                $result[] = array(
                            'id' => $ii,
                            'kd_kegiatan' => $resulte['kd_kegiatan'],                         
                            'nm_kegiatan' => $resulte['nm_sub_kegiatan'],  
                            'jns_kegiatan' => $resulte['jns_kegiatan'],                           
                            'lanjut' => $resulte['lanjut']                           
                            );
                            $ii++;
            }
            echo json_encode($result);
        }


        function ld_jns() {    
            $sql = "SELECT jns_sub_kegiatan FROM ms_sub_kegiatan group by jns_sub_kegiatan";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'jns_kegiatan' => $resulte['jns_sub_kegiatan']                                               
                            );
                            $ii++;
            }
               
            echo json_encode($result);
               
        }

        function ld_lanjut() {
            $result[] = array('lanjut' => 'Ya');
            $result[] = array('lanjut' => 'Tidak');                       
            echo json_encode($result);
        }                                                 


         function psimpan_rancang() {        
        $subgiat    =$this->input->post('reklama'); 
        $skpd       =$this->input->post('skpd');
        $urusan     =$this->rka_model->get_urusan_skpd($skpd,'urusan','get_urusan_kd_skpd','kd_skpd');
        $giat       =$this->left($this->input->post('reklama'),12);
        $prog       =$this->left($this->input->post('reklama'),7); 
        $jns        =$this->rka_model->get_nama($subgiat,'jns_sub_kegiatan','ms_sub_kegiatan','kd_sub_kegiatan');
        $lanjut     =$this->input->post('lanjut');
        $gabung     =$skpd.'.'.$subgiat;

        $nmskpd     =$this->rka_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmgiat     =$this->rka_model->get_nama($giat,'nm_kegiatan','ms_kegiatan','kd_kegiatan');
        $nmsubgiat  =$this->rka_model->get_nama($subgiat,'nm_sub_kegiatan','ms_sub_kegiatan','kd_sub_kegiatan');
        $nmprog     =$this->rka_model->get_nama($prog,'nm_program','ms_program','kd_program');

        $query = $this->db->query("delete from trskpd_rancang where kd_skpd='$skpd' and kd_sub_kegiatan='$subgiat'");
        $query = $this->db->query("insert into trskpd_rancang(kd_gabungan,kd_sub_kegiatan,kd_kegiatan,kd_program,kd_bidang_urusan,kd_skpd,jns_kegiatan,nm_skpd,nm_sub_kegiatan,nm_kegiatan,nm_program,lanjut,status_sub_kegiatan) 
                                    values('$gabung','$subgiat','$giat','$prog','$urusan','$skpd','$jns','$nmskpd','$nmsubgiat','$nmgiat','$nmprog','$lanjut','1')");   


        $query = $this->db->query("delete from trskpd where kd_skpd='$skpd' and kd_sub_kegiatan='$subgiat'");
        $query = $this->db->query("insert into trskpd(kd_gabungan,kd_sub_kegiatan,kd_kegiatan,kd_program,kd_bidang_urusan,kd_skpd,jns_kegiatan,nm_skpd,nm_sub_kegiatan,nm_kegiatan,nm_program,lanjut,status_sub_kegiatan) 
                                    values('$gabung','$subgiat','$giat','$prog','$urusan','$skpd','$jns','$nmskpd','$nmsubgiat','$nmgiat','$nmprog','$lanjut','1')");   
        $this->select_giat_rancang($skpd);
        }



      function tambah_giat_penyusunan()
        {
            $wy=$this->rka_model->combo_urus();
            $jk=$this->rka_model->combo_skpd1();         
            $cRet='';
            
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                       <tr>
                            <td>Kode Urusan</td>
                            <td>:</td>
                            <td>$wy</td>
                            </tr>
                      ";
             
             $cRet .="<tr>
                            <td>Kode SKPD</td>
                            <td>:</td>
                            <td>$jk</td>
                            </tr>
                      </table>";
            $data['prev']= $cRet;
            $data['page_title']= 'Pilih Kegiatan penyusunan';
            $this->template->set('title', 'Detail Kegiatan penyusunan');          
            $this->template->load('template','anggaran/rka/penyusunan/pilih_giat_penyusunan',$data) ; 
            //$this->load->view('anggaran/rka/tambah_rka',$data) ;
       }

       // function psimpan_rancang($skpd='',$urusan='',$rekbaru='',$reklama='',$jns='',$lanjut='Tidak') {         

       //  $kd=$this->right($rekbaru,5);
       //  $prog1=$this->left($rekbaru,13);    
       //  $giat=$urusan.'.'.$skpd.'.'.$kd;
       //  $prog=$this->left($giat,18); 
       //  $gabung=$skpd.'.'.$giat;
       //  $nmskpd=$this->rka_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
       //  $nmgiat=$this->rka_model->get_nama($rekbaru,'nm_sub_kegiatan','ms_kegiatan','kd_sub_kegiatan');
       //  $nmprog=$this->rka_model->get_nama($prog1,'nm_program','ms_program','kd_program');

       //  $query = $this->db->query("delete from trskpd_rancang where kd_skpd='$skpd' and kd_kegiatan='$reklama'");
       //  $query = $this->db->query("insert into trskpd_rancang(kd_gabungan,kd_kegiatan,kd_program,kd_urusan,kd_skpd,kd_kegiatan,kd_program,jns_kegiatan,nm_skpd,nm_kegiatan,nm_program,lanjut) 
       //                              values('$gabung','$giat','$prog','$urusan','$skpd','$rekbaru','$prog1','$jns','$nmskpd','$nmgiat','$nmprog','$lanjut')");   


       //  $query = $this->db->query("delete from trskpd where kd_skpd='$skpd' and kd_kegiatan='$reklama'");
       //  $query = $this->db->query("insert into trskpd(kd_gabungan,kd_kegiatan,kd_program,kd_urusan,kd_skpd,kd_kegiatan1,kd_program1,jns_kegiatan,nm_skpd,nm_kegiatan,nm_program,lanjut) 
       //                              values('$gabung','$giat','$prog','$urusan','$skpd','$rekbaru','$prog1','$jns','$nmskpd','$nmgiat','$nmprog','$lanjut')");   
       //  $this->select_giat_rancang($skpd);
       //  }

       function skpd() {
            $skpd     = $this->session->userdata('kdskpd');
            $lccr = $this->input->post('q');
            $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where kd_skpd='$skpd' order by kd_skpd ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],  
                            'nm_skpd' => $resulte['nm_skpd'],
                            'jns' => $resulte['jns']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }

         function ghapus_rancang($skpd='',$kegiatan) {
            $query = $this->db->query("delete from trskpd_rancang where kd_skpd='$skpd' and kd_kegiatan='$kegiatan' and  kd_kegiatan not in(SELECT DISTINCT kd_kegiatan FROM trdrka_rancang) ");
            $query = $this->db->query("delete from trskpd where kd_skpd='$skpd' and kd_kegiatan='$kegiatan' and  kd_kegiatan not in(SELECT DISTINCT kd_kegiatan FROM trdrka)  ");
            $this->select_giat_rancang($skpd);
        }

        /// RKA PENDAPATAN

        function rka_pendapatan_penyusunan()
        {
            $data['page_title']= 'CETAK';
            $this->template->set('title', 'Cetak RKA 1 Penyusunan');   
            $this->template->load('template','anggaran/rka/penyusunan/rka_pendapatan_penyusunan',$data) ; 
        }

    function preview_pendapatan_penyusunan(){
            $id = $this->uri->segment(2);
            $tgl_ttd= $_REQUEST['tgl_ttd'];
            $ttd1= $_REQUEST['ttd1'];
            $ttd2= $_REQUEST['ttd2'];
            $ttd1 = str_replace('a',' ',$ttd1); 
            $ttd2 = str_replace('a',' ',$ttd2); 
            
            $tanggal_ttd = $this->tanggal_format_indonesia($tgl_ttd);
            $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                    }
            $sqldns="SELECT a.kd_urusan as kd_u,'' as header, LEFT(a.kd_skpd,17) as kd_org,b.nm_bidang_urusan as nm_u, a.kd_skpd as kd_sk,a.nm_skpd as nm_sk  FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                        $header  = $rowdns->header;
                        $kd_org = $rowdns->kd_org;
                    }


            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd= '$id'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = $this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd WHERE kd_skpd= '$id' AND kode in ('PA','KPA') AND nip='$ttd1'  ";
                     $sqlttd1=$this->db->query($sqlttd1);
                     foreach ($sqlttd1->result() as $rowttd)
                    {
                        $nip=$rowttd->nip;                    
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        $pangkat  = $rowttd->pangkat;
                    }
                    
            $sqlttd2="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd WHERE kode in ('PA','KPA') AND nip='$ttd2'  ";
                     $sqlttd2=$this->db->query($sqlttd2);
                     foreach ($sqlttd2->result() as $rowttd2)
                    {
                        $nip2=$rowttd2->nip;                    
                        $nama2= $rowttd2->nm;
                        $jabatan2  = $rowttd2->jab;
                        $pangkat2  = $rowttd2->pangkat;
                    }
            
            
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:14px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr>  <td style=\"border-collapse:collapse;border-right: solid 1px black;border-bottom: solid 1px black\"  width=\"15%\" rowspan=\"2\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"100\" height=\"100\" /></td>
                             <td width=\"80%\" align=\"center\"><strong>RENCANA KERJA DAN ANGGARAN <br /> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"2\" align=\"center\"><strong>FORMULIR RKA - <br />PENDAPATAN SKPD  </strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>$kab <br /> TAHUN ANGGARAN $thn</strong> </td>
                        </tr>

                      </table>";
            $cRet .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td width=\"20%\">Organisasi </td>
                            <td width=\"80%\">$kd_org -".$this->rka_model->get_nama($kd_org,'nm_org','ms_organisasi','kd_org')."</td>
                        </tr>
                        <tr>
                            <td bgcolor=\"#CCCCCC\" colspan=\"2\">&nbsp;</td>
                           
                        </tr>
                        <tr>
                            <td colspan=\"2\"\ align=\"center\"><strong>Ringkasan Anggaran Pendapatan dan Belanja Satuan Kerja Perangkat Daerah </strong></td>
                        </tr>
                    </table>";
            $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                         <thead>                       
                            <tr><td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>                            
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>Uraian</b></td>
                                <td colspan=\"3\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>Rincian Perhitungan</b></td>
                                <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah(Rp.)</b></td></tr>
                            <tr>
                                <td width=\"8%\" bgcolor=\"#CCCCCC\" align=\"center\">Volume</td>
                                <td width=\"8%\" bgcolor=\"#CCCCCC\" align=\"center\">Satuan</td>
                                <td width=\"14%\" bgcolor=\"#CCCCCC\" align=\"center\">Tarif/harga</td>
                            </tr>    
                         </thead>
                        
                         
                            <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"14%\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td></tr>
                            ";
                     $sql1="SELECT * FROM(
    SELECT LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka_rancang a 
    INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE LEFT(a.kd_rek6,2)='41' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,1),nm_rek1 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,2) AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama, 0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'2' AS id FROM trdrka_rancang a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE LEFT(a.kd_rek6,2)='41' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,2),nm_rek2 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka_rancang a INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE LEFT(a.kd_rek6,2)='41' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,4),nm_rek3 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka_rancang a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 WHERE LEFT(a.kd_rek6,2)='41' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,6),nm_rek4 
    UNION ALL 
    SELECT LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM trdrka_rancang a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE LEFT(a.kd_rek6,2)='41' AND left(a.kd_skpd,17)='$kd_org' GROUP BY LEFT(a.kd_rek6,8),b.nm_rek5 
    UNION ALL 
    SELECT a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM trdrka_rancang a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE LEFT(a.kd_rek6,2)='41' AND left(a.kd_skpd,17)='$kd_org' GROUP BY a.kd_rek6,b.nm_rek6 
    UNION ALL 
    SELECT RIGHT(a.no_trdrka,11) AS rek1,' 'AS rek,a.uraian AS nama,a.volume1 AS volume,a.satuan1 AS satuan, a.harga1 AS harga,a.total AS nilai,'7' AS id FROM trdpo_rancang a WHERE LEFT(a.no_trdrka,20)='$id' 
    AND SUBSTRING(no_trdrka,38,2)='41' 
    ) a ORDER BY a.rek1,a.id";
                     
                    $query = $this->db->query($sql1);
                      if ($query->num_rows() > 0){                                  
                   
                                                     
                    foreach ($query->result() as $row)
                    {
                        $rek=$row->rek;
                        $reke=$this->dotrek($rek);
                        $uraian=$row->nama;
                        $volum=$row->volume;
                        $sat=$row->satuan;
                        $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                        $nila= number_format($row->nilai,"2",",",".");
                       
                            
                        if($reke!=' '){
                            $volum = '';
                        }
                        
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$reke</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">$volum</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">$sat</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">$hrg</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nila</td></tr>
                                         ";
                    }
                          }else{
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">4</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">PENDAPATAN</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">".number_format(0,"2",",",".")."</td></tr>
                                         ";
                        
                    }


                       $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align=\"right\">&nbsp;</td>
                                 </tr>";
                     $sqltp="SELECT SUM(nilai) AS totp FROM trdrka_rancang WHERE LEFT(kd_rek6,2)='41' AND left(kd_skpd,17)='$kd_org'";
                        $sqlp=$this->db->query($sqltp);
                     foreach ($sqlp->result() as $rowp)
                    {
                       $totp=number_format($rowp->totp,"2",",",".");
                       
                        $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pendapatan</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"14%\" align=\"right\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$totp</td></tr>";
                     }
                     $kd_ttd=substr($id,18,2);
                     $kd_kepala=substr($id,0,7);
                     if ((($kd_ttd=='01') && ($kd_kepala!='1.20.03'))|| ($id=='1.20.03.00')){
                        $cRet .="<tr>
                                    <td width=\"100%\" align=\"center\" colspan=\"6\">
                                    <table border=\"0\">
                                    <tr>
                                    <td width=\"70%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp;
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"30%\" align=\"center\">$daerah ,$tanggal_ttd
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><u><b>$nama</b></u>
                                    <br>$pangkat
                                    <br>NIP. $nip 
                                    </td></tr></table></td>
                                 </tr>";
                                 } else {
                                 $cRet .="<tr>
                                    <td width=\"100%\" align=\"center\" colspan=\"6\">
                                    <table border=\"0\">
                                    <tr>
                                    <td width=\"70%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp;
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"30%\" align=\"center\">Mengetahui,
                                    <br>$jabatan2,
                                    <p>&nbsp;</p>
                                    <br><b>$nama2</b>
                                    <br>$pangkat2
                                    <br>NIP. $nip2 
                                    </td>
                                    <td width=\"70%\" align=\"left\">&nbsp;<br>&nbsp;
                                    <br>&nbsp;
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;<br>
                                    &nbsp;  
                                    </td>
                                    <td width=\"30%\" align=\"center\">$daerah ,$tanggal_ttd
                                    <br>$jabatan,
                                    <p>&nbsp;</p>
                                    <br><b><u>$nama</u></b>
                                    <br>$pangkat
                                    <br>NIP. $nip 
                                    </td></tr></table></td>
                                 </tr>";
                                 
                                 }



                                 
                      // $cRet .= "<tr>
                      //               <td width=\"100%\" align=\"left\" colspan=\"6\">Keterangan :</td>
                      //           </tr>";
                      // $cRet .= "<tr>
                      //                <td width=\"100%\" align=\"left\" colspan=\"6\">Tanggal Pembahasan :</td>
                      //           </tr>";
                      // $cRet .= "<tr>
                      //               <td width=\"100%\" align=\"left\" colspan=\"6\">Catatan Hasil Pembahasan :</td>
                      //           </tr>";
                      // $cRet .= "<tr>
                      //               <td width=\"100%\" align=\"left\" colspan=\"6\">1.</td>
                      //           </tr>";
                      // $cRet .= "<tr>
                      //               <td width=\"100%\" align=\"left\" colspan=\"6\">2.</td>
                      //           </tr>";
                      // $cRet .= "<tr>
                      //               <td width=\"100%\" align=\"left\" colspan=\"6\">Dst</td>
                      //           </tr>";
                      // $cRet .= "<tr>
                      //               <td width=\"100%\" align=\"center\" colspan=\"6\">Tim Anggaran Pemerintah Daerah</td>
                      //           </tr>";
                      
                                
                     
                     
            
                  
            $cRet    .= "</table>";
             $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\"><tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\">Keterangan :</td>
                                </tr>";
                      $cRet .= "<tr>
                                     <td width=\"100%\" align=\"left\" colspan=\"6\">Tanggal Pembahasan :</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\">Catatan Hasil Pembahasan :</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\">1.</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\">2.</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"6\">Dst</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"center\" colspan=\"6\">Tim Anggaran Pemerintah Daerah</td>
                                </tr>";
                      
                                
                     
                     
            
                  
            $cRet    .= "</table>";
           $cRet    .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr>
                             <td width=\"10%\" align=\"center\">No </td>
                             <td width=\"30%\"  align=\"center\">Nama</td>
                             <td width=\"20%\"  align=\"center\">NIP</td>
                             <td width=\"20%\"  align=\"center\">Jabatan</td>
                             <td width=\"20%\"  align=\"center\">Tandatangan</td>
                        </tr>";
                        $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd where kd_skpd='$id' order by no";
                         $sqltapd = $this->db->query($sqltim);
                      if ($sqltapd->num_rows() > 0){
                        
                        $no=1;
                        foreach ($sqltapd->result() as $rowtim)
                        {
                            $no=$no;                    
                            $nama= $rowtim->nama;
                            $nip= $rowtim->nip;
                            $jabatan  = $rowtim->jab;
                            $cRet .="<tr>
                             <td width=\"5%\" align=\"left\">$no </td>
                             <td width=\"20%\"  align=\"left\">$nama</td>
                             <td width=\"20%\"  align=\"left\">$nip</td>
                             <td width=\"35%\"  align=\"left\">$jabatan</td>
                             <td width=\"20%\"  align=\"left\"></td>
                        </tr>"; 
                        $no=$no+1;              
                      }}
                        else{
                            $cRet .="<tr>
                             <td width=\"5%\" align=\"left\"> &nbsp; </td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                             <td width=\"35%\"  align=\"left\"></td>
                             <td width=\"20%\"  align=\"left\"></td>
                            </tr>"; 
                        }

            $cRet .=       " </table>";
            $data['prev']= $cRet;
            $cetak = $this->uri->segment(3);
            switch($cetak) { 
            case 1;
                 $this->_mpdf('',$cRet,10,10,10,'0');
            break;
            case 2;        
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                //$this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 3;     
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
            break;
            case 0;
            echo ("<title>RKA SKPD</title>");
            echo($cRet);
            break;
            }    
            // echo $cRet;
            //$this->_mpdf('',$cRet,10,10,10,0);        
        }

    function skpdpembiayaan() {
            $skpd     = $this->session->userdata('kdskpd');
            $lccr = $this->input->post('q');
            $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where kd_skpd='$skpd' AND (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') )order by kd_skpd ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],  
                            'nm_skpd' => $resulte['nm_skpd'],
                            'jns' => $resulte['jns']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }

        function load_ttd_unit($skpd='') {
            $kd_skpd = $skpd; 
            $kd_skpd2= $this->left($kd_skpd,10);
            $lccr='';        
            $lccr = $this->input->post('q');        
            $sql = "SELECT * FROM ms_ttd WHERE left(kd_skpd,10)= '$kd_skpd2' AND kode in ('PA','KPA')  AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";   

             
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;        
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'nip' => $resulte['nip'],  
                            'nama' => $resulte['nama']      
                            );
                            $ii++;
            }           
               
            echo json_encode($result);
            $query1->free_result();        
        }

    function rka_pembiayaan_penyusunan()
        {
            $data['page_title']= 'CETAK RKA 31';
            $this->template->set('title', 'CETAK RKA 31');   
            $this->template->load('template','anggaran/rka/penyusunan/rka_pembiayaan_penyusunan',$data) ; 
        }

       function preview_rka_pembiayaan_penyusunan(){
            $id = $this->uri->segment(2);
            $cetak = $this->uri->segment(3);
            $kdbkad = $this->kdbkad;
            $ttd1= $_REQUEST['ttd1'];
            
            if (strlen($id)==17){
                $a = 'left(';
                $skpd = 'kd_skpd';
                $b = ',17)';

                $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_org as kd_sk,a.nm_org as nm_sk FROM ms_organisasi a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_org=left('$id',17)";
                
            }else{
                $a = 'left(';
                $skpd = 'kd_skpd';
                $b = ',20)';
                $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
                $sqldns1="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_org as kd_org,a.nm_org as nm_org FROM ms_organisasi a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_org=left('$id',17)";

                $sqlskpd1=$this->db->query($sqldns1);
                foreach ($sqlskpd1->result() as $rowdns)
                    {
                        $kd_org  = $rowdns->kd_org;
                        $nm_org = $rowdns->nm_org;
                    }
            }

                

      
          
                $sqlskpd=$this->db->query($sqldns);
                foreach ($sqlskpd->result() as $rowdns)
                    {
                       
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                    }

            $sqldns="SELECT * from ms_urusan WHERE kd_urusan=left('$kd_urusan',1)";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                       
                        $kd_urusan1=$rowdns->kd_urusan;                    
                        $nm_urusan1= $rowdns->nm_urusan;
                    }


            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$kdbkad'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        //$tanggal = $this->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    }
           
           if ($ttd1<>''){         
           $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(nip, ' ', '')='$ttd1')  ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $jdlnip1 = 'Mengetahui,';                    
                        $nip1=empty($rowttd->nip) ? '' : 'NIP.'.$rowttd->nip ;
                        $pangkat1=empty($rowttd->pangkat) ? '' : $rowttd->pangkat;
                        $nama1= empty($rowttd->nm) ? '' : $rowttd->nm;
                        $jabatan1  = empty($rowttd->jab) ? '': $rowttd->jab;
                    }
            }
            else{
                        $jdlnip1 = '';
                        $nip1='' ;
                        $pangkat1='';
                        $nama1= '';
                        $jabatan1  = '';
            }        
            $cRet='';
            $cRet .="<table style=\"border-collapse:collapse;font-size:14px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr>  <td style=\"border-collapse:collapse;border-right: solid 1px black;border-bottom: solid 1px black\"  width=\"15%\" rowspan=\"2\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"100\" height=\"100\" /></td>
                             <td width=\"80%\" align=\"center\"><strong>RENCANA KERJA DAN ANGGARAN <br /> SATUAN KERJA PERANGKAT DAERAH</strong></td>
                             <td width=\"20%\" rowspan=\"2\" align=\"center\"><strong>FORMULIR RKA - <br />PEMBIAYAAN SKPD  </strong></td>
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>$kab <br /> TAHUN ANGGARAN $thn</strong> </td>
                        </tr>

                      </table>";
            if (strlen($id)==17){          
                $cRet .="<table style=\"border-collapse:collapse;font-size:12;\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td colspan=\"3\"\ align=\"center\">RINCIAN ANGGARAN PEMBIAYAAN</td>
                        </tr>
                         <tr>
                            <td><strong>Organisasi</stong></td>
                            <td colspan=\"2\"><strong>$kd_skpd - $nm_skpd</stong></td>
                        </tr>
                        <tr>
                            <td colspan=\"3\"\ align=\"center\">&nbsp;</td>
                        </tr>
                        
                    </table>";
            }ELSE{
                $cRet .="<table style=\"border-collapse:collapse;font-size:12;\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td colspan=\"3\"\ width=\"100%\" align=\"center\">RINCIAN ANGGARAN PEMBIAYAAN</td>
                        </tr>

                        <tr>
                            <td><strong>Organisasi</stong></td>
                            <td><strong>$kd_org - $nm_org</stong></td>
                        </tr>
                        <tr>
                            <td><strong>Unit Organisasi</stong></td>
                            <td><strong>$kd_skpd - $nm_skpd</stong></td>
                        </tr>
                        <tr>
                            <td colspan=\"3\"\ align=\"center\">&nbsp;</td>
                        </tr>
                    </table>";            
            }        
                    
            $cRet .= "<table style=\"border-collapse:collapse; font-size:12;border-top: solid 1px black;\"  width=\"100%\" align=\"center\" border=\"1\"  cellspacing=\"0\" cellpadding=\"4\">
                         <thead>                       
                            <tr><td  width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>                            
                                <td  width=\"50%\" align=\"center\"><b>Uraian</b></td>
                                <td  width=\"40%\" align=\"center\"><b>Jumlah(Rp.)</b></td>
                            </tr>                            
                         </thead>
                        
                         
                            <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\"  align=\"center\"><strong>1</strong></td>                            
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"50%\"  align=\"center\"><strong>2</strong></td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\"  align=\"center\"><strong>3</strong></td>
                            </tr>
                            ";

                            $sql1="SELECT * FROM(
                                SELECT LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka_rancang a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE 
                                right(a.kd_sub_kegiatan,5)='00.61' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,1),b.nm_rek1 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,2) 
                                AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama,0 AS volume,' 'AS satuan,0 AS harga,SUM(a.nilai) AS nilai,'2' 
                                AS id FROM trdrka_rancang a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE right(a.kd_sub_kegiatan,5)='00.61' AND 
                                $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,2),b.nm_rek2 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS
                                rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka_rancang a INNER JOIN
                                ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE right(a.kd_sub_kegiatan,5)='00.61' AND $a a.$skpd$b='$id' 
                                GROUP BY LEFT(a.kd_rek6,4),b.nm_rek3
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka_rancang a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 
                                WHERE right(a.kd_sub_kegiatan,5)='00.61' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,6),b.nm_rek4 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM 
                                trdrka_rancang a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE right(a.kd_sub_kegiatan,5)='00.61' AND $a a.$skpd$b='$id' GROUP BY 
                                LEFT(a.kd_rek6,8),b.nm_rek5 
                                 UNION ALL 
                                SELECT a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM 
                                trdrka_rancang a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE right(a.kd_sub_kegiatan,5)='00.61' AND $a a.$skpd$b='$id' GROUP BY 
                                a.kd_rek6,b.nm_rek6 
                                ) 
                            a ORDER BY a.rek1,a.id";                        


                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                    $totp = 0;  
                    foreach ($query->result() as $row)
                    {
                        $rek=$row->rek;
                        $reke=$this->dotrek($rek);
                        $uraian=$row->nama;
                        $sat=$row->satuan;
                        $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                        
                        
                        if ($reke<>''){
                            $volum='';                        
                        }
                        else{
                            $volum=$row->volume;
                        }
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nila= number_format($row->nilai,"2",",",".");
                         
                        $x = strlen($reke);
                        
                        if (strlen($reke)>8 || strlen($reke)==0){
                           $cRet    .= "<tr>
                                            <td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><FONT SIZE=2>$reke </FONT></td>                                     
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><FONT SIZE=2>$uraian</FONT></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><FONT SIZE=2>$nila</FONT></td>                                     
                                        </tr>";
                       }else{
                            if(strlen($reke)==1){
                                 $totp = $totp + $row->nilai;
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2></FONT></strong></td>
                                                </tr>";
                            }else{
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2>$nila</FONT></strong></td>
                                                </tr>";                            
                            }
                            
                       }                  
                    }
                        $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align=\"right\">&nbsp;</td>
                                 </tr>";

                        $totp=number_format($totp,"2",",",".");
                       
                        $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"40%\"><strong><FONT SIZE=2>JUMLAH PENERIMAAN</FONT></strong></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong><FONT SIZE=2>$totp</FONT></strong></td>
                                         </tr>";

                      $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  <td align=\"right\">&nbsp;</td>
                                 </tr>";
                       $sql1="SELECT * FROM(
                                SELECT LEFT(a.kd_rek6,1)AS rek1,LEFT(a.kd_rek6,1)AS rek,b.nm_rek1 AS nama ,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'1' AS id FROM trdrka_rancang a INNER JOIN ms_rek1 b ON LEFT(a.kd_rek6,1)=b.kd_rek1 WHERE 
                                right(a.kd_sub_kegiatan,5)='00.62' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,1),b.nm_rek1 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,2) 
                                AS rek1,LEFT(a.kd_rek6,2) AS rek,b.nm_rek2 AS nama,0 AS volume,' 'AS satuan,0 AS harga,SUM(a.nilai) AS nilai,'2' 
                                AS id FROM trdrka_rancang a INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2 WHERE right(a.kd_sub_kegiatan,5)='00.62' AND 
                                $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,2),b.nm_rek2 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,4) AS rek1,LEFT(a.kd_rek6,4) AS
                                rek,b.nm_rek3 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'3' AS id FROM trdrka_rancang a INNER JOIN
                                ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3 WHERE right(a.kd_sub_kegiatan,5)='00.62' AND $a a.$skpd$b='$id' 
                                GROUP BY LEFT(a.kd_rek6,4),b.nm_rek3
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,6) AS rek1,LEFT(a.kd_rek6,6) AS rek,b.nm_rek4 AS nama,0 AS volume,' 'AS satuan, 
                                0 AS harga,SUM(a.nilai) AS nilai,'4' AS id FROM trdrka_rancang a INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4 
                                WHERE right(a.kd_sub_kegiatan,5)='00.62' AND $a a.$skpd$b='$id' GROUP BY LEFT(a.kd_rek6,6),b.nm_rek4 
                                UNION ALL 
                                SELECT LEFT(a.kd_rek6,8) AS rek1,LEFT(a.kd_rek6,8) AS rek,b.nm_rek5 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'5' AS id FROM 
                                trdrka_rancang a INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5 WHERE right(a.kd_sub_kegiatan,5)='00.62' AND $a a.$skpd$b='$id' GROUP BY 
                                LEFT(a.kd_rek6,8),b.nm_rek5 
                                 UNION ALL 
                                SELECT a.kd_rek6 AS rek1,a.kd_rek6 AS rek,b.nm_rek6 AS nama,0 AS volume,' 'AS satuan, 0 AS harga,SUM(a.nilai) AS nilai,'6' AS id FROM 
                                trdrka_rancang a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 WHERE right(a.kd_sub_kegiatan,5)='00.62' AND $a a.$skpd$b='$id' GROUP BY 
                                a.kd_rek6,b.nm_rek6  
                                ) 
                            a ORDER BY a.rek1,a.id";                        


                     $query = $this->db->query($sql1);
                     //$query = $this->skpd_model->getAllc();
                    $totp = 0;  
                    foreach ($query->result() as $row)
                    {
                        $rek=$row->rek;
                        $reke=$this->dotrek($rek);
                        $uraian=$row->nama;
                        $sat=$row->satuan;
                        $hrg= empty($row->harga) || $row->harga == 0 ? '' :number_format($row->harga,2,',','.');
                        
                        
                        if ($reke<>''){
                            $volum='';                        
                        }
                        else{
                            $volum=$row->volume;
                        }
                        //$hrg=number_format($row->harga,"2",".",",");
                        $nila= number_format($row->nilai,"2",",",".");
                         
                        $x = strlen($reke);
                        
                        if (strlen($reke)>8 || strlen($reke)==0){
                           $cRet    .= "<tr>
                                            <td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><FONT SIZE=2>$reke </FONT></td>                                     
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><FONT SIZE=2>$uraian</FONT></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><FONT SIZE=2>$nila</FONT></td>                                     
                                        </tr>";
                       }else{
                            if(strlen($reke)==1){
                                 $totp = $totp + $row->nilai;
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2></FONT></strong></td>
                                                </tr>";
                            }else{
                                 $cRet    .= "  <tr><td style=\"vertical-align:top; border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"><strong><FONT SIZE=2>$reke</FONT></strong></td>                                     
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"50%\"><strong><FONT SIZE=2>$uraian</FONT></strong></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\" align=\"right\"><strong><FONT SIZE=2>$nila</FONT></strong></td>
                                                </tr>";                            
                            }
                            
                       }                  
                    }
                        $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td align=\"right\">&nbsp;</td>
                                 </tr>";

                        $totp=number_format($totp,"2",",",".");
                       
                        $cRet    .=" <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"40%\"><strong><FONT SIZE=2>JUMLAH PENGELUARAN</FONT></strong></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong><FONT SIZE=2>$totp</FONT></strong></td>
                                         </tr>";

                      $cRet .= "<tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  <td align=\"right\">&nbsp;</td>
                                 </tr>";

            $cRet    .= "</table>";

            $cRet    .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">";

                            $cRet .="<tr>
                                        <td width=\"100\" align=\"right\" colspan=\"3\">                            
                                            <table border=\"0\">
                                                <tr>                                
                                                    <td width=\"60%\" align=\"left\">&nbsp;<br>&nbsp;
                                                        <br>&nbsp;&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;  
                                                    </td>
                                                    <td style=\"font-family:Times New Roman; font-size:10;\" width=\"40%\" align=\"center\">
                                                        $daerah,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.
                                                        <br>$jabatan1,
                                                        <p>&nbsp;</p>
                                                        <br><b><u>$nama1</u></b>
                                                        <br>$pangkat1
                                                        <br>$nip1 
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                 </tr>";
                                    
                            
     

            $cRet    .= "</table>";
            $cRet    .="<table style=\"border-collapse:collapse;font-size:12;border-left: solid 1px black;border-right: solid 1px black;border-bottom: solid 1px black; \" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">";

                     $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"7\">Keterangan </td>
                                </tr>";
                                                            
                      $cRet .= "<tr>
                                     <td width=\"100%\" align=\"left\" colspan=\"7\">Tanggal Pembahasan :</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"7\">Catatan Hasil Pembahasan : </td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"7\">1.</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"7\">2.</td>
                                </tr>";
                      $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"7\">Dst.</td>
                                </tr>";
             
                      
            $cRet    .= "</table>";

            $cRet    .="<table style=\"border-collapse:collapse;font-size:12 \" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">";
                        $cRet .= "<tr>
                                    <td width=\"100%\" align=\"left\" colspan=\"7\">&nbsp; </td>
                                </tr>";
           $cRet    .= "</table>";
                                            
            $cRet    .="<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <tr>
                             <td colspan=\"5\" align=\"center\"><strong>Tim Anggaran Pemerintah Daerah</strong> </td>
                             
                        </tr>
                        <tr>
                             <td width=\"10%\" align=\"center\"><strong>No</strong> </td>
                             <td width=\"30%\"  align=\"center\"><strong>Nama</strong></td>
                             <td width=\"20%\"  align=\"center\"><strong>NIP</strong></td>
                             <td width=\"20%\"  align=\"center\"><strong>Jabatan</strong></td>
                             <td width=\"20%\"  align=\"center\"><strong>Tanda Tangan</strong></td>
                        </tr>";
                        $sqltim="SELECT nama as nama,nip as nip,jabatan as jab FROM tapd order by no";
                        $sqltapd=$this->db->query($sqltim);
                        $no=1;
                        foreach ($sqltapd->result() as $rowtim)
                        {
                            $no=$no;                    
                            $nama= $rowtim->nama;
                            $nip= $rowtim->nip;
                            $jabatan  = $rowtim->jab;
             $cRet .="<tr>
                             <td width=\"5%\" align=\"center\">$no </td>
                             <td width=\"20%\"  align=\"left\">$nama</td>
                             <td width=\"20%\"  align=\"left\">$nip</td>
                             <td width=\"35%\"  align=\"left\">$jabatan</td>
                             <td width=\"20%\"  align=\"left\"></td>
                        </tr>"; 
                        $no=$no+1;              
                        }
                        
                        if($no<=4)
                                                   {
                                                     for ($i = $no; $i <= 4; $i++) 
                                                      {
                                                        $cRet .="<tr>
                             <td width=\"5%\" align=\"center\">$i </td>
                             <td width=\"20%\"  align=\"left\">&nbsp; </td>
                             <td width=\"20%\"  align=\"left\">&nbsp; </td>
                             <td width=\"35%\"  align=\"left\">&nbsp; </td>
                             <td width=\"20%\"  align=\"left\"></td>
                        </tr>";     
                                                      }                                                   
                                                   } 

            
                  
            $cRet    .= "</table>";
            $data['prev']= $cRet;    
            switch($cetak) {
                case 0;  
                    echo ("<title>RKA 31</title>");
                    echo($cRet);
                break;
                case 1;
                    $this->_mpdf('',$cRet,10,10,10,'0','','RKA 31','RKA 31');
                        
                break;
            }
            //$this->template->load('template','master/fungsi/list_preview',$data);
            
                    
        }
//TEMP

        function tsimpan_rinci_jk_rancang_temp(){
        $id                 = $this->session->userdata('pcNama');
        $header             = $this->input->post('header');
        $kode               = $this->input->post('kode');
        $kd_kegiatan        = $this->input->post('kd_kegiatan');      
        $kd_rek5            = $this->input->post('kd_rek');      
        $no_po              = $this->input->post('no_po');      
        $no_trdrka          = $this->input->post('no_trdrka');
        $kd_barang          = $this->input->post('kd_barang');      
        $uraian             = $this->input->post('uraian');      
        $volume1            = $this->input->post('volume1');      
        $volume2            = $this->input->post('volume1');      
        $volume3            = $this->input->post('volume1');      
        $volume1_sempurna1  = $this->input->post('volume1_sempurna1');
        $volume1_sempurna2  = $this->input->post('volume1_sempurna1');
        $volume1_sempurna3  = $this->input->post('volume1_sempurna1');      
        $volume_ubah1       = $this->input->post('volume_ubah1');      
        $volume_ubah2       = $this->input->post('volume_ubah1');      
        $volume_ubah3       = $this->input->post('volume_ubah1');      
        $satuan1            = $this->input->post('satuan1');
        $satuan2            = $this->input->post('satuan1');
        $satuan3            = $this->input->post('satuan1');      
        $satuan_sempurna1   = $this->input->post('satuan_sempurna1');      
        $satuan_sempurna2   = $this->input->post('satuan_sempurna1');      
        $satuan_sempurna3   = $this->input->post('satuan_sempurna1');      
        $satuan_ubah1       = $this->input->post('satuan_ubah1');
        $satuan_ubah2       = $this->input->post('satuan_ubah1');
        $satuan_ubah3       = $this->input->post('satuan_ubah1');      
        $harga1             = $this->input->post('harga1');
        $harga_sempurna1    = $this->input->post('harga_sempurna1'); 
        $harga_ubah1        = $this->input->post('harga_ubah1');      
        $total              = $this->input->post('total');      
        $total_sempurna     = $this->input->post('total_sempurna');
        $total_ubah         = $this->input->post('total_ubah'); 
        $kode_unik          = $this->input->post('unik');
        $sdana1             = $this->input->post('sdana1');  

        /*untuk menyimpan nilai no_po*/
        if($no_po==''){
            $unik=$this->db->query("SELECT isnull(max(cast(no_po as int)),0)+200 as nopo from trdpo_temp where no_trdrka='$no_trdrka'")->row();            
            $no_po=$unik->nopo;
        }else{
            $no_po=$no_po-1;  /*nomer no_po ketika di sisipkan*/        
        }


        $unik=$this->db->query("SELECT isnull(max(cast(unik as int)),0)+1 as oke from trdpo_temp")->row();
        $kd_sisb=$unik->oke;

        $sql ="INSERT into trdpo_temp(header,kode,kd_barang,kd_rek6,no_po,no_trdrka,uraian,volume1,volume2,volume3,volume_sempurna1,volume_sempurna2,volume_sempurna3,volume_ubah1,volume_ubah2,volume_ubah3,satuan1,satuan2,satuan3,satuan_sempurna1,satuan_sempurna2,satuan_sempurna3,satuan_ubah1,satuan_ubah2,satuan_ubah3,harga1,harga_sempurna1,harga_ubah1,total,total_sempurna,total_ubah,unik)
        values ('$header','$kode','$kd_barang','$kd_rek5','$no_po','$no_trdrka','$uraian','$volume1','$volume2','$volume3','$volume1_sempurna1','$volume1_sempurna2','$volume1_sempurna3','$volume_ubah1','$volume_ubah2','$volume_ubah3','$satuan1','$satuan2','$satuan3','$satuan_sempurna1','$satuan_sempurna2','$satuan_sempurna3','$satuan_ubah1','$satuan_ubah2','$satuan_ubah3','$harga1','$harga_sempurna1','$harga_ubah1','$total','$total_sempurna','$total_ubah','$kd_sisb')";
        $this->db->query($sql);

        $sql1 ="INSERT into trdpo_rancang_temp(header,kode,kd_barang,kd_rek6,no_po,no_trdrka,uraian,volume1,volume2,volume3,volume_sempurna1,volume_sempurna2,volume_sempurna3,volume_ubah1,volume_ubah2,volume_ubah3,satuan1,satuan2,satuan3,satuan_sempurna1,satuan_sempurna2,satuan_sempurna3,satuan_ubah1,satuan_ubah2,satuan_ubah3,harga1,harga_sempurna1,harga_ubah1,total,total_sempurna,total_ubah,unik)
        values ('$header','$kode','$kd_barang','$kd_rek5','$no_po','$no_trdrka','$uraian','$volume1','$volume2','$volume3','$volume1_sempurna1','$volume1_sempurna2','$volume1_sempurna3','$volume_ubah1','$volume_ubah2','$volume_ubah3','$satuan1','$satuan2','$satuan3','$satuan_sempurna1','$satuan_sempurna2','$satuan_sempurna3','$satuan_ubah1','$satuan_ubah2','$satuan_ubah3','$harga1','$harga_sempurna1','$harga_ubah1','$total','$total_sempurna','$total_ubah','$kd_sisb')";
        $this->db->query($sql1);
        }
    function tsimpan_rinci_jk_rancang_temp2(){
        $id                 = $this->session->userdata('pcNama');
        $header             = $this->input->post('header');
        $kode               = $this->input->post('kode');
        $kd_kegiatan        = $this->input->post('kd_kegiatan');      
        $kd_rek5            = $this->input->post('kd_rek');      
        $no_po              = $this->input->post('no_po');      
        $no_trdrka          = $this->input->post('no_trdrka');
        $kd_barang          = $this->input->post('kd_barang');      
        $uraian             = $this->input->post('uraian');
        $spesifikasi        = $this->input->post('spesifikasi');      
        $volume1            = $this->input->post('volume1');      
        $volume2            = $this->input->post('volume1');      
        $volume3            = $this->input->post('volume1');      
        $volume1_sempurna1  = $this->input->post('volume1_sempurna1');
        $volume1_sempurna2  = $this->input->post('volume1_sempurna1');
        $volume1_sempurna3  = $this->input->post('volume1_sempurna1');      
        $volume_ubah1       = $this->input->post('volume_ubah1');      
        $volume_ubah2       = $this->input->post('volume_ubah1');      
        $volume_ubah3       = $this->input->post('volume_ubah1');      
        $satuan1            = $this->input->post('satuan1');
        $satuan2            = $this->input->post('satuan1');
        $satuan3            = $this->input->post('satuan1');      
        $satuan_sempurna1   = $this->input->post('satuan_sempurna1');      
        $satuan_sempurna2   = $this->input->post('satuan_sempurna1');      
        $satuan_sempurna3   = $this->input->post('satuan_sempurna1');      
        $satuan_ubah1       = $this->input->post('satuan_ubah1');
        $satuan_ubah2       = $this->input->post('satuan_ubah1');
        $satuan_ubah3       = $this->input->post('satuan_ubah1');      
        $harga1             = $this->input->post('harga1');
        $harga_sempurna1    = $this->input->post('harga_sempurna1'); 
        $harga_ubah1        = $this->input->post('harga_ubah1');      
        $total              = $this->input->post('total');      
        $total_sempurna     = $this->input->post('total_sempurna');
        $total_ubah         = $this->input->post('total_ubah'); 
        $kode_unik          = $this->input->post('unik');
        $sdana1             = $this->input->post('sdana1');
        $spesifikasi             = $this->input->post('spesifikasi');  

        /*untuk menyimpan nilai no_po*/
       /* if($no_po==''){
            $unik=$this->db->query("SELECT isnull(max(cast(no_po as int)),0)+200 as nopo from trdpo_temp where no_trdrka='$no_trdrka'")->row();            
            $no_po=$unik->nopo;
        }else{
            $no_po=$no_po-1;  
                  
        }*/
        /*nomer no_po ketika di sisipkan*/  

        if($no_po==-1){
            $unik=$this->db->query("SELECT isnull(max(cast(no_po as int)),0)+1 as nopo from trdpo_temp where no_trdrka='$no_trdrka'")->row();            
            $no_po=$unik->nopo;
        }

        $unik=$this->db->query("SELECT isnull(max(cast(id as int)),0)+1 as oke from trdpo_temp")->row();
        $kd_sisb=$unik->oke;


        $this->db->query("UPDATE trdpo_temp set no_po=no_po+1 where no_trdrka='$no_trdrka' and no_po>='$no_po'");

        $sql ="INSERT into trdpo_temp(header,kode,kd_barang,kd_rek6,no_po,no_trdrka,uraian,volume1,volume2,volume3,volume_sempurna21,volume_sempurna22,volume_sempurna23,satuan1,satuan2,satuan3,satuan_sempurna21,satuan_sempurna22,satuan_sempurna23,harga,harga_sempurna2,harga_ubah,total,total_sempurna2,total_ubah,spesifikasi)
        values ('$header','$kode','$kd_barang','$kd_rek5','$no_po','$no_trdrka','$uraian','$volume1','$volume2','$volume3','$volume1_sempurna1','$volume1_sempurna2','$volume1_sempurna3','$satuan1','$satuan2','$satuan3','$satuan_sempurna1','$satuan_sempurna2','$satuan_sempurna3','$harga1','$harga_sempurna1','$harga_ubah1',0,'$total_sempurna','$total_ubah','$spesifikasi')";
        $this->db->query($sql);
        } 


function hapus_rincian_temp(){
        $id         = $this->session->userdata('pcNama');        
        $kode_unik  = $this->input->post('kode_unik');
        $skpd       = $this->input->post('skpd');
        $kd_kegiatan= $this->input->post('giat');
        $norka      = $this->input->post('norka');

        $sql="DELETE trdpo_temp where unik='$kode_unik' and no_trdrka='$norka'";
        $this->db->query($sql);

        $sql="DELETE trdpo where unik='$kode_unik' and no_trdrka='$norka'";
        $this->db->query($sql);

        $sql="DELETE trdpo_rancang_temp where unik='$kode_unik' and no_trdrka='$norka'";
        $this->db->query($sql);

        $sql="DELETE trdpo_rancang where unik='$kode_unik' and no_trdrka='$norka'";
        $this->db->query($sql);

    }

    function update_rincian_temp2(){

        $id                = $this->session->userdata('pcNama');
        $header            = $this->input->post('header');
        $kode              = $this->input->post('kode');
        $kd_barang         = ''; //diisi      
        $kd_rek6           = $this->input->post('kd_rek6');      
        $no_po             = $this->input->post('no_po');      
        $no_trdrka         = $this->input->post('no_trdrka');      
        $uraian            = $this->input->post('uraian'); 
        
        
        $volume1_sempurna1 = $this->input->post('volume1_sempurna1');
        $volume2_sempurna2 = $this->input->post('volume2_sempurna2');
        $volume3_sempurna3 = $this->input->post('volume3_sempurna3');
        $volume4_sempurna4 = $this->input->post('volume4_sempurna4');
        
        $satuan_sempurna1  = $this->input->post('satuan_sempurna1');
        $satuan_sempurna2  = $this->input->post('satuan_sempurna2');
        $satuan_sempurna3  = $this->input->post('satuan_sempurna3');
        $satuan_sempurna4  = $this->input->post('satuan_sempurna4');
        
        $satuan            = $this->input->post('satuan_sempurna');      
        $harga             = $this->input->post('harga_sempurna1');      
        $tvol_edit         = $this->input->post('tvol_edit'); 


        $total             = $this->input->post('total'); 
        $unik              = $this->input->post('unik');  
        $spesifikasi              = $this->input->post('spesifikasi');

        $volume_sempurna = $volume1_sempurna1;
$koefisien = $volume1_sempurna1.' '.$satuan_sempurna1;
if ( (double)$volume2_sempurna2) {
    $volume_sempurna *= $volume2_sempurna2;
    $koefisien .= ' x '.$volume2_sempurna2.' '.$satuan_sempurna2;
}
if ( (double)$volume3_sempurna3) {
    $volume_sempurna *= $volume3_sempurna3;
    $koefisien .= ' x '.$volume3_sempurna3.' '.$satuan_sempurna3;
}
if ( (double)$volume4_sempurna4) {
    $volume_sempurna *= $volume4_sempurna4;
    $koefisien .= ' x '.$volume4_sempurna4.' '.$satuan_sempurna4;
}

 $totalharga=$volume_sempurna*$harga;
        
           /* $a=$volume1_sempurna1;
        


        if ($volume2_sempurna2==0){
            $b='';
            $x2='';
            $y2='';
        }else {
            $x2='*';
            $b=$volume2_sempurna2;
            $y2='x';
        }



        if ($volume3_sempurna3==0){
            $c='0';
            $x3='*';
            $y3='';
        }else {
            $x3='*';
            $c=$volume3_sempurna3;
            $y3='x';
        }


        if ($volume4_sempurna4==0){
            $d='0';
            $x4='*';
            $y4='';
        }else {
            $x4='*';
            $d=$volume4_sempurna4;
            $y4='x';
        }*/

        

          $sql="UPDATE trdpo_temp set 
            header          ='$header',
            kode            ='$kode',
            uraian          ='$uraian',
            spesifikasi     ='$spesifikasi',
            volume_sempurna21='$volume1_sempurna1',
            volume_sempurna22='$volume2_sempurna2',
            volume_sempurna23='$volume3_sempurna3',
            volume_sempurna24='$volume4_sempurna4',

            satuan_sempurna21='$satuan_sempurna1',
            satuan_sempurna22='$satuan_sempurna2',
            satuan_sempurna23='$satuan_sempurna3',
            satuan_sempurna24='$satuan_sempurna4',

            tsatuan_sempurna2 ='$satuan',

            harga_sempurna2 ='$harga',
            harga_ubah     ='$harga',
            
            tvolume_sempurna2  =$volume_sempurna,


            total_sempurna2  =$totalharga,
            total_ubah      =$totalharga,
            tkoefisien_sempurna2 ='$koefisien'

            where id='$unik' and no_trdrka='$no_trdrka' ";
         $this->db->query($sql);

         
    }

        function update_rincian_temp(){

        $id             = $this->session->userdata('pcNama');
        $header         = $this->input->post('header');
        $kode           = $this->input->post('kode');
        $kd_barang      = ''; //diisi      
        $kd_rek6        = $this->input->post('kd_rek6');      
        $no_po          = $this->input->post('no_po');      
        $no_trdrka      = $this->input->post('no_trdrka');      
        $uraian         = $this->input->post('uraian');        
        $volume1        = $this->input->post('volume1');
        $volume2        = $this->input->post('volume1');
        $volume3        = $this->input->post('volume1');
        $satuan         = $this->input->post('satuan1');      
        $harga          = $this->input->post('harga1');      
        $total          = $this->input->post('total'); 
        $unik           = $this->input->post('unik');  

        $sql="UPDATE trdpo_rancang_temp set 
            header          ='$header',
            kode            ='$kode',
            uraian          ='$uraian',
            volume1         ='$volume1',
            volume2         ='$volume2',
            volume3         ='$volume3',
            volume_sempurna1='$volume1',
            volume_sempurna2='$volume2',
            volume_sempurna3='$volume3',
            volume_ubah1    ='$volume1',
            volume_ubah2    ='$volume2',
            volume_ubah3    ='$volume3',
            satuan1         ='$satuan',
            satuan2         ='$satuan',
            satuan3         ='$satuan',
            satuan_sempurna1='$satuan',
            satuan_sempurna2='$satuan',
            satuan_sempurna3='$satuan',
            satuan_ubah1    ='$satuan',
            satuan_ubah2    ='$satuan',
            satuan_ubah3    ='$satuan',
            harga1          ='$harga',
            harga_sempurna1 ='$harga',
            harga_ubah1     ='$harga',
            total           ='$total',
            total_sempurna  ='$total',
            total_ubah      ='$total'
            where unik='$unik' and no_trdrka='$no_trdrka'";
         $this->db->query($sql);

         $sql="UPDATE trdpo_temp set 
            header          ='$header',
            kode            ='$kode',
            uraian          ='$uraian',
            volume1         ='$volume1',
            volume2         ='$volume2',
            volume3         ='$volume3',
            volume_sempurna1='$volume1',
            volume_sempurna2='$volume2',
            volume_sempurna3='$volume3',
            volume_ubah1    ='$volume1',
            volume_ubah2    ='$volume2',
            volume_ubah3    ='$volume3',
            satuan1         ='$satuan',
            satuan2         ='$satuan',
            satuan3         ='$satuan',
            satuan_sempurna1='$satuan',
            satuan_sempurna2='$satuan',
            satuan_sempurna3='$satuan',
            satuan_ubah1    ='$satuan',
            satuan_ubah2    ='$satuan',
            satuan_ubah3    ='$satuan',
            harga1          ='$harga',
            harga_sempurna1 ='$harga',
            harga_ubah1     ='$harga',
            total           ='$total',
            total_sempurna  ='$total',
            total_ubah      ='$total'
            where unik='$unik' and no_trdrka='$no_trdrka'";
         $this->db->query($sql);
    }




        /////////////BATAS/////////////
    }

    ?>