    <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    class Dpa_penetapan extends CI_Controller {

        public $ppkd_lama   = "";
        public $ppkd1_lama  = "";
        public $keu1        = "5-02.0-00.0-00.01.01";


        function __contruct()
        {   
            parent::__construct();
        }

function pengesahan_dpa()
    {
        $data['page_title']= 'Pengesahan DPA & DPPA';
        $this->template->set('title', 'Pengesahan DPA & DPPA');   
        $this->template->load('template','anggaran/dpa/pengesahan_dpa',$data) ; 
    }

function skpd() {
        
        $skpd     = $this->session->userdata('kdskpd');
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

function load_pengesahan_dpa(){
        $result = array();
        $row = array();
        $id  = $this->session->userdata('pcUser');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="where kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";
        if ($kriteria <> ''){                               
            $where="where (upper(kd_skpd) like upper('%$kriteria%') or no_dpa like'%$kriteria%') and kd_skpd IN 
                    (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";            
        }
        
        $sql = "SELECT count(*) as tot from trhrka $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT * from trhrka $where order by kd_skpd ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,
                        'kd_skpd' => $resulte['kd_skpd'],        
                        'statu' => $resulte['status'],
                        'status_sempurna' => $resulte['status_sempurna'],
                        'status_ubah' => $resulte['status_ubah'],
                        'no_dpa' => $resulte['no_dpa'],        
                        'tgl_dpa' => $resulte['tgl_dpa'],
                        'no_dpa_ubah' => $resulte['no_dpa_ubah'],
                        'tgl_dpa_ubah' => $resulte['tgl_dpa_ubah'],
                        //'status_sempurna' => $resulte['status_sempurna'],
                        'no_dpa_sempurna' => $resulte['no_dpa_sempurna'],
                        'tgl_dpa_sempurna' => $resulte['tgl_dpa_sempurna'],
                        'status_rancang' => $resulte['status_rancang'],
                        'tgl_dpa_rancang' => $resulte['tgl_dpa_rancang'],
                        'status_sempurna2' => $resulte['status_sempurna2'],
                        'status_sempurna3' => $resulte['status_sempurna3'],
                        'status_sempurna4' => $resulte['status_sempurna4'],
                        'status_sempurna5' => $resulte['status_sempurna5'],
                        'status_sempurna6' => $resulte['status_sempurna6'],
                        'tgldpa_sempurna2' => $resulte['tgldpa_sempurna2'],
                        'tgldpa_sempurna3' => $resulte['tgldpa_sempurna3'],
                        'tgldpa_sempurna4' => $resulte['tgldpa_sempurna4'],
                        'tgldpa_sempurna5' => $resulte['tgldpa_sempurna5'],
                        'tgldpa_sempurna6' => $resulte['tgldpa_sempurna6']
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);     
    }

function simpan_pengesahan(){
        $kdskpd         = $this->input->post('kdskpd');
        $sdpa           = $this->input->post('stdpa');
        $sdppa          = $this->input->post('stdppa');
        $nodpa          = $this->input->post('no');
        $tanggal1       = $this->input->post('tgl');
        $nodppa         = $this->input->post('no2');
        $tanggal2       = $this->input->post('tgl2');
        $sdpasempurna   = $this->input->post('stsempurna');
        $nosempurna     = $this->input->post('no3');
        $tanggal3       = $this->input->post('tgl3');
        
        if ($tanggal1==""){
            $tanggal1='null';
        }else{
            $tanggal1 = "'".$this->input->post('tgl')."'";
        }
        if ($tanggal2==""){
            $tanggal2='null';
        }else{
            $tanggal2 = "'".$this->input->post('tgl2')."'";
        }
        if ($tanggal3==""){
            $tanggal3='null';   
        }else{
            $tanggal3 = "'".$this->input->post('tgl3')."'";
        }
        
         $sql2 = "UPDATE trhrka  set status='$sdpa',status_sempurna='$sdpasempurna',status_ubah='$sdppa',no_dpa='$nodpa',tgl_dpa=$tanggal1,
no_dpa_ubah='$nodppa',tgl_dpa_ubah=$tanggal2,no_dpa_sempurna='$nosempurna',tgl_dpa_sempurna=$tanggal3 where kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);             

        $sql2 = "update [user] set kunci='1' where bidang='4' and kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);             
     }

     function ctk_dpa_skpd(){
        $data['page_title']= 'Cetak DPA SKPD';
        $this->template->set('title', 'Cetak DPA SKPD');   
        $this->template->load('template','anggaran/dpa/ctk_dpa_skpd',$data) ; 
    }

    function load_ttd_set_ppkd() {
        $lccr='';        
        $lccr = $this->input->post('q');        
        $sql = "SELECT * FROM ms_ttd WHERE (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%')) and (kode='PPKD' or kode='SETDA') and bidang='1' ";   

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


    function load_ttd_set_pa($ckdskpd1='') {
        $lccr='';        
        $lccr = $this->input->post('q');        
        $sql = "SELECT * FROM ms_ttd WHERE  (kode='PA' or kode='KPA')  and left(kd_skpd,17)='$ckdskpd1'";   

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

    function skpd_trdrka() {
        $lccr = $this->input->post('q');
        $sql = "select * from(
                    SELECT a.kd_skpd,a.nm_skpd,sum(nilai) [nilai] FROM ms_skpd a join trdrka b on a.kd_skpd=b.kd_skpd  
                    group by a.kd_skpd,a.nm_skpd  
                ) as c where nilai>0  order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
            $query1->free_result();
    }



     function preview_dpa_skpd(){
        $id     = $this->uri->segment(2);
        $cetak  = $this->uri->segment(3);
        $rinci  = $this->uri->segment(4);
        //$keu1 = $this->keu1;
        
        $ttd2= $_REQUEST['ttd2'];
        

        if (strlen($id)>17){
            $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
            $a = 'left(';
            $skpd = 'kd_skpd';
            $b = ',20)';             
        }else{
            $sqldns="SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE left(kd_skpd,17)='$id'";
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
        }
            
            $sqlorg="SELECT * FROM ms_organisasi WHERE kd_org=left('$id',17)";
                 $sqlorg1=$this->db->query($sqlorg);
                 foreach ($sqlorg1->result() as $rowdns)
                {
                   
                    $kd_org=$rowdns->kd_org;                    
                    $nm_org= $rowdns->nm_org;
                }


        $sqlurusan1="SELECT * FROM ms_urusan WHERE kd_urusan=left('$kd_urusan',1)";
                 $sqlskpd=$this->db->query($sqlurusan1);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                   
                    $kd_urusan1=$rowdns->kd_urusan;                    
                    $nm_urusan1= $rowdns->nm_urusan;
                }
                       
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where $a $skpd$b ='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    //$tanggal = $this->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }


       if ($ttd2<>''){         
       $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(nip, ' ', '')='$ttd2')  ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $jdlnip1 = 'Menyetujui,';                    
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
     $cRet .="<table style=\"border-collapse:collapse;font-size:16;border-top: solid 1px black;border-right: solid 1px black;border-left: solid 1px black;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr> <td style=\"border-collapse:collapse;border-bottom: solid 1px black\"  width=\"15%\" rowspan=\"2\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"100\" height=\"110\" /></td>
                         <td width=\"65%\" align=\"center\"><strong>RINGKASAN DOKUMEN PELAKSANAAN ANGGARAN</strong></td>
                         <td style=\"border-collapse:collapse;font-size:18;border-left: solid 1px black\" width=\"20%\" rowspan=\"4\" align=\"center\"><strong>FORMULIR <br>DPA - SKPD<br></strong></td>
                    </tr>

                    <tr>
                         <td  style=\"border-collapse:collapse;border-bottom: solid 1px black\" align=\"center\"  ><strong>SATUAN KERJA PERANGKAT DAERAH</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\" colspan=\"2\" width=\"100%\" ><strong>$kab</strong> </td>
                    </tr>
                    <tr>
                         <td align=\"center\" colspan=\"2\" width=\"100%\" ><strong>TAHUN ANGGARAN $thn</strong></td>
                    </tr>

                  </table>";        
                  
       if (strlen($id)==7){          
            $cRet .="<table style=\"border-collapse:collapse;font-size:12;border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black; none;font-size:12;\" width=\"100%\" align=\"left\" border=\"0\">
                    <tr>
                        <td width=\"25%\"><strong>&nbsp;&nbsp;Urusan Pemerintahan</stong></td>
                        <td width=\"80%\"><strong>: $kd_urusan1 - $nm_urusan1</stong></td>
                    </tr>
                    <tr>
                        <td width=\"25%\"><strong>&nbsp;&nbsp;Bidang Pemerintahan</stong></td>
                        <td width=\"80%\"><strong>: $kd_urusan - $nm_urusan</stong></td>
                    </tr>
                    <tr>
                        <td><strong>&nbsp;&nbsp;Unit Organisasi</stong></td>
                        <td><strong>: $kd_org - $nm_org</stong></td>
                    </tr>
                </table>";
        }ELSE{
            $cRet .="<table style=\"border-collapse:collapse;font-size:12;border-left:solid 1px black;border-top:solid 1px black;border-right:solid 1px black; none;font-size:12;\" width=\"100%\" align=\"left\" border=\"0\">
                    <tr>
                        <td width=\"22%\"><strong>&emsp;Urusan Pemerintahan</stong></td>
                        <td width=\"3%\"><strong>: </stong></td>
                        <td width=\"80%\"><strong> $kd_urusan1 - $nm_urusan1</stong></td>
                    </tr>
                    <tr>
                        <td style=\"vertical-align:top;\" width=\"22%\"><strong>&emsp;Bidang Pemerintahan</stong></td>
                        <td style=\"vertical-align:top;\" width=\"3%\"><strong>: </stong></td>
                        <td style=\"vertical-align:top;\" width=\"80%\"><strong> $kd_urusan - $nm_urusan</stong></td>
                    </tr>
                    <tr>
                        <td style=\"vertical-align:top;\"><strong>&emsp;Unit Organisasi </stong></td>
                        <td style=\"vertical-align:top;\"><strong>: </stong></td>
                        <td style=\"vertical-align:top;\"><strong> $kd_org - $nm_org</stong></td>
                    </tr>
                    <tr>
                        <td style=\"vertical-align:top;\"><strong>&emsp;Sub Unit Organisasi </stong></td>
                        <td style=\"vertical-align:top;\"><strong >: </stong></td>
                        <td style=\"vertical-align:top;\"><strong> $kd_skpd - $nm_skpd</stong></td>
                    </tr>
                </table>";            
        }        
          
         
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td width=\"10%\" align=\"center\"><b>KODE REKENING</b></td>                            
                            <td width=\"70%\" align=\"center\"><b>URAIAN</b></td>
                            <td width=\"20%\" align=\"center\"><b>JUMLAH(Rp.)</b></td></tr>
                            
                         <tr><td style=\"vertical-align:top;border-top: none;border-bottom: solid 2px black;\" width=\"10%\" align=\"center\"><strong>1</strong></td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: solid 2px black;\" width=\"70%\" align=\"center\"><strong>2</strong></td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: solid 2px black;\" width=\"20%\" align=\"center\"><strong>3</strong></td></tr>
                     </thead>
                     
                    <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                         
                         </tr>
                     </tfoot>   
                        ";


                 $sql1="SELECT a.kd_rek1 AS kd_rek,a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                       ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(b.kd_rek6,2)='41' and $a b.$skpd$b='$id' GROUP BY a.kd_rek1, a.nm_rek1
                       UNION ALL 
                       SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                       ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(b.kd_rek6,2)='41' and $a b.$skpd$b='$id' GROUP BY a.kd_rek2,a.nm_rek2 
                       UNION ALL 
                       SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                       ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(b.kd_rek6,2)='41' and $a b.$skpd$b='$id' GROUP BY a.kd_rek3, a.nm_rek3 ";
                 if ($rinci == 1){
                    $sql2 = "union all
                            SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a INNER JOIN trdrka b
                            ON a.kd_rek4=LEFT(b.kd_rek6,(LEN(a.kd_rek4))) where left(b.kd_rek6,2)='41' and $a b.$skpd$b='$id' GROUP BY a.kd_rek4, a.nm_rek4 
                            union all

                            SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a INNER JOIN trdrka b
                            ON a.kd_rek5=LEFT(b.kd_rek6,(LEN(a.kd_rek5))) where left(b.kd_rek6,2)='41' and $a b.$skpd$b='$id' GROUP BY a.kd_rek5, a.nm_rek5 
                            union all
                            SELECT a.kd_rek6 AS kd_rek,a.kd_rek6+'.'+SUBSTRING(a.kd_rek6,4,2)+'.'+SUBSTRING(a.kd_rek6,6,2) AS rek,a.nm_rek6 AS nm_rek 
                            ,SUM(b.nilai) AS nilai FROM ms_rek6 a INNER JOIN trdrka b
                            ON a.kd_rek6=LEFT(b.kd_rek6,(LEN(a.kd_rek6))) where left(b.kd_rek6,2)='41' and $a b.$skpd$b='$id' GROUP BY 
                            a.kd_rek6, a.nm_rek6 ";
                 }else{
                    $sql2 = "";
                 }
                 $sql3 = "ORDER BY kd_rek";
                 
                 $query = $this->db->query($sql1.$sql2.$sql3);
                 //$query = $this->skpd_model->getAllc();
                                                  
                foreach ($query->result() as $row)
                {
                    $coba1=$this->rka_model->dotrek(rtrim($row->kd_rek));
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"2",",",".");
                    
                    if(strlen($coba1)>3){                                   
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"left\">$coba1 </td>                                     
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"70%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td></tr>";
                    }else{
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\"><strong>$coba1</strong></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"70%\"><strong>$coba2</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong>$coba3</strong></td></tr>";                        
                    }
                }
                $sqltp="SELECT SUM(nilai) AS totp FROM trdrka WHERE LEFT(kd_rek6,2)='41' and $a$skpd$b='$id'";
                 $sqlp=$this->db->query($sqltp);
                 foreach ($sqlp->result() as $rowp)
                {
                   $coba4=number_format($rowp->totp,"2",",",".");
                    $cob1=$rowp->totp;
                    
                    if ($cob1!=0){                   
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;text-transform:uppercase;\" width=\"70%\"><strong>Jumlah Pendapatan</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><strong>$coba4</strong></td></tr>";
  
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                    } 
                 }     

                 
         
         
                $sql1="SELECT a.kd_rek1 AS kd_rek,a.kd_rek1 AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                       ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) WHERE (LEFT(b.kd_rek6,1)='5') AND $a b.$skpd$b='$id' 
                       GROUP BY a.kd_rek1, a.nm_rek1
                       UNION ALL 
                       SELECT a.kd_rek2 AS kd_rek,a.kd_rek2 AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                       ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) WHERE (LEFT(b.kd_rek6,2)='51' OR LEFT(b.kd_rek6,2)='52') AND $a b.$skpd$b='$id' GROUP BY a.kd_rek2,a.nm_rek2 
                       UNION ALL 
                       SELECT a.kd_rek3 AS kd_rek,a.kd_rek3 AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                       ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) WHERE (LEFT(b.kd_rek6,2)='51' OR LEFT(b.kd_rek6,2)='52') AND $a b.$skpd$b='$id' GROUP BY a.kd_rek3, a.nm_rek3 
                       ";
                 if($rinci==1){
                    $sql2=" UNION ALL 
                            SELECT a.kd_rek4 AS kd_rek,a.kd_rek4 AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a INNER JOIN trdrka b
                            ON a.kd_rek4=LEFT(b.kd_rek6,(LEN(a.kd_rek4))) WHERE (LEFT(b.kd_rek6,2)='51' OR LEFT(b.kd_rek6,2)='52') AND $a b.$skpd$b='$id' 
                            GROUP BY a.kd_rek4, a.nm_rek4
                            union all
                            SELECT a.kd_rek5 AS kd_rek,a.kd_rek5 AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek5 a INNER JOIN trdrka b
                            ON a.kd_rek4=LEFT(b.kd_rek6,(LEN(a.kd_rek5))) WHERE (LEFT(b.kd_rek6,2)='51' OR LEFT(b.kd_rek6,2)='52') AND $a b.$skpd$b='$id' 
                            GROUP BY a.kd_rek5, a.nm_rek5
                            union all
                            SELECT a.kd_rek6 AS kd_rek,a.kd_rek6+'.'+SUBSTRING(a.kd_rek6,4,2)+'.'+SUBSTRING(a.kd_rek6,6,2) AS rek,a.nm_rek6 AS nm_rek ,
                            SUM(b.nilai) AS nilai FROM ms_rek6 a INNER JOIN trdrka b
                            ON a.kd_rek6=LEFT(b.kd_rek6,(LEN(a.kd_rek6))) WHERE (LEFT(b.kd_rek6,2)='51' OR LEFT(b.kd_rek6,2)='52') AND $a b.$skpd$b='$id' 
                            GROUP BY a.kd_rek6, a.nm_rek6
                            ";
                 }else{
                    $sql2="";
                 }
                 $sql3 = " ORDER BY kd_rek";
                 
                 $query1 = $this->db->query($sql1.$sql2.$sql3);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->rka_model->dotrek(rtrim($row1->kd_rek));
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"2",",",".");
                    
                    if(strlen($coba5)>3){
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                     <td style=\"vertical-align:top;border-top: none ;border-bottom: none;\" width=\"70%\">$coba6</td>
                                     <td style=\"vertical-align:top;border-top: none ;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";
                    }else{
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\"><strong>$coba5</strong></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"70%\"><strong>$coba6</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong>$coba7</strong></td></tr>";                                                    
                    }
                }
                
                    $sqltb="SELECT SUM(nilai) AS totb FROM trdrka WHERE (LEFT(kd_rek6,2)='51' or LEFT(kd_rek6,2)='52') and $a$skpd$b='$id'";
                    $sqlb=$this->db->query($sqltb);
                 foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,"2",",",".");
                    $cob=$rowb->totb;
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;text-transform:uppercase;\" width=\"70%\"><strong>Jumlah Belanja</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><strong>$coba8</strong></td></tr>";
                 } 
                  
                  $suplus=$cob1-$cob; 
                  if ($suplus < 0){
                    $x1="(";
                    $suplus=$suplus*-1;
                    $y1=")";}
                  else {
                    $x1="";
                    $y1="";}
                 $surp=number_format($suplus,2,',','.');  
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;text-transform:uppercase;\" width=\"70%\"><strong>Surplus/Defisit</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><strong>$x1$surp$y1</strong></td></tr>";
                                     
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";

                    if ($id == $this->ppkd_lama || $id == $this->ppkd1_lama){
                        //PENERIMAAN PEMBIAYAAN
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><strong>PEMBIAYAAN</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td></tr>";
        
                         $sql1="SELECT a.kd_rek1 AS kd_rek,a.kelompok AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                           ON a.kd_rek1=LEFT(b.kd_rek5,(LEN(a.kd_rek1))) WHERE (right(b.kd_kegiatan,5)='00.61') AND $a b.$skpd$b='$id' 
                           GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                           UNION ALL 
                           SELECT a.kd_rek2 AS kd_rek,a.kelompok AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                           ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) WHERE (right(b.kd_kegiatan,5)='00.61') AND $a b.$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                           UNION ALL 
                           SELECT a.kd_rek3 AS kd_rek,a.kelompok AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                           ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) WHERE (right(b.kd_kegiatan,5)='00.61') AND $a b.$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 
                           ";
                         if($rinci==1){
                            $sql2=" UNION ALL 
                                SELECT a.kd_rek4 AS kd_rek,a.kelompok AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a INNER JOIN trdrka b
                                ON a.kd_rek4=LEFT(b.kd_rek5,(LEN(a.kd_rek4))) WHERE (right(b.kd_kegiatan,5)='00.61') AND $a b.$skpd$b='$id' 
                                GROUP BY a.kd_rek4,a.kelompok, a.nm_rek4
                                union all
                                SELECT a.kd_rek5 AS kd_rek,a.kelompok+'.'+SUBSTRING(a.kd_rek5,4,2)+'.'+SUBSTRING(a.kd_rek5,6,2) AS rek,a.nm_rek5 AS nm_rek ,
                                SUM(b.nilai) AS nilai FROM ms_rek5 a INNER JOIN trdrka b
                                ON a.kd_rek5=LEFT(b.kd_rek5,(LEN(a.kd_rek5))) WHERE (right(b.kd_kegiatan,5)='00.61') AND $a b.$skpd$b='$id' 
                                GROUP BY a.kd_rek5,a.kelompok, a.nm_rek5
                                ";
                        }else{
                            $sql2="";
                        }
                        $sql3 = " ORDER BY kd_rek";
                     
                        $query1 = $this->db->query($sql1.$sql2.$sql3);
                        foreach ($query1->result() as $row1){
                            $coba5=$this->rka_model->dotrek(rtrim($row1->kd_rek));
                            $coba6=$row1->nm_rek;
                            $coba7= number_format($row1->nilai,"2",",",".");
                            
                            if (strlen($coba5)==1){
                                $totpen = $row1->nilai;
                            }
                        
                            if(strlen($coba5)>3){
                                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                             <td style=\"vertical-align:top;border-top: none ;border-bottom: none;\" width=\"70%\">$coba6</td>
                                             <td style=\"vertical-align:top;border-top: none ;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";
                            }else{
                                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\"><strong>$coba5</strong></td>                                     
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"70%\"><strong>$coba6</strong></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong>$coba7</strong></td></tr>";                                                    
                            }
                        }
                    
                        $totpen1=number_format($totpen,"2",",",".");
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><strong>JUMLAH PENERIMAAN DAERAH</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><strong>$totpen1</strong></td></tr>";
                     
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";                    

                        //PENGELUARAN PEMBIAYAAN
        
                         $sql1="SELECT a.kd_rek1 AS kd_rek,a.kelompok AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                           ON a.kd_rek1=LEFT(b.kd_rek5,(LEN(a.kd_rek1))) WHERE (right(b.kd_kegiatan,5)='00.62') AND $a b.$skpd$b='$id' 
                           GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                           UNION ALL 
                           SELECT a.kd_rek2 AS kd_rek,a.kelompok AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                           ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) WHERE (right(b.kd_kegiatan,5)='00.62') AND $a b.$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                           UNION ALL 
                           SELECT a.kd_rek3 AS kd_rek,a.kelompok AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                           ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) WHERE (right(b.kd_kegiatan,5)='00.62') AND $a b.$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 
                           ";
                         if($rinci==1){
                            $sql2=" UNION ALL 
                                SELECT a.kd_rek4 AS kd_rek,a.kelompok AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek4 a INNER JOIN trdrka b
                                ON a.kd_rek4=LEFT(b.kd_rek5,(LEN(a.kd_rek4))) WHERE (right(b.kd_kegiatan,5)='00.62') AND $a b.$skpd$b='$id' 
                                GROUP BY a.kd_rek4,a.kelompok, a.nm_rek4
                                union all
                                SELECT a.kd_rek5 AS kd_rek,a.kelompok+'.'+SUBSTRING(a.kd_rek5,4,2)+'.'+SUBSTRING(a.kd_rek5,6,2) AS rek,a.nm_rek5 AS nm_rek ,
                                SUM(b.nilai) AS nilai FROM ms_rek5 a INNER JOIN trdrka b
                                ON a.kd_rek5=LEFT(b.kd_rek5,(LEN(a.kd_rek5))) WHERE (right(b.kd_kegiatan,5)='00.62') AND $a b.$skpd$b='$id' 
                                GROUP BY a.kd_rek5,a.kelompok, a.nm_rek5
                                ";
                        }else{
                            $sql2="";
                        }
                        $sql3 = " ORDER BY kd_rek";
                     
                        $query1 = $this->db->query($sql1.$sql2.$sql3);
                        foreach ($query1->result() as $row1){
                            $coba5=$this->rka_model->dotrek(rtrim($row1->kd_rek));
                            $coba6=$row1->nm_rek;
                            $coba7= number_format($row1->nilai,"2",",",".");
                            
                            if (strlen($coba5)==1){
                                $totpeng = $row1->nilai;
                            }
                        
                            if(strlen($coba5)>3){
                                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                             <td style=\"vertical-align:top;border-top: none ;border-bottom: none;\" width=\"70%\">$coba6</td>
                                             <td style=\"vertical-align:top;border-top: none ;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";
                            }else{
                                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\"><strong>$coba5</strong></td>                                     
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"70%\"><strong>$coba6</strong></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><strong>$coba7</strong></td></tr>";                                                    
                            }
                        }
                        
                        $jbiaya = $totpen - $totpeng;
                        
                        $jbiaya = $this->rp_minus($jbiaya);
                        
                        $totpeng=number_format($totpeng,"2",",",".");
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><strong>JUMLAH PENGELUARAN DAERAH</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><strong>$totpeng</strong></td></tr>";
                     
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"> </td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                                                         
                       $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\"><strong>JUMLAH PEMBIAYAAN</strong></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><strong>$jbiaya</strong></td></tr>";


                    }
                    
  
   
                    
                    $cRet    .= "</table>"; 
                    $cRet .="<table style=\"border-collapse:collapse;font-size:14\" width=\"130%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">
                            
                            <tr >
                                <td colspan=\"7\" style=\"text-align: center;\" ><strong>Rencana Pelaksanaan Anggaran <br> Satuan Kerja Perangkat Daerah per Triwulan</strong></td>
                            </tr>
                            <tr>
                                <td rowspan=\"2\" ><strong>NO.</strong></td>
                                <td width=\"18%\"  rowspan=\"2\" style=\"text-align: center;\" ><strong>Uraian</strong> </td>
                                <td colspan=\"5\" style=\"text-align: center;\" ><strong>Triwulan</strong></td>
                            </tr>";
                    
                    $cRet .="<tr>
                                <td style=\"text-align: center;\"><strong>I </strong></td>
                                <td style=\"text-align: center;\"><strong>II</strong></td>
                                <td style=\"text-align: center;\"><strong>III</strong></td>
                                <td style=\"text-align: center;\"><strong>IV</strong></td>
                                <td style=\"text-align: center;\"><strong>JUMLAH</strong> </td>
                            </tr>";


                    $cRet .="<tr >
                                <td style=\"text-align: center;\"><strong>1</strong> </td>
                                <td style=\"text-align: center;\" ><strong>2</strong> </td>
                                <td style=\"text-align: center;\"><strong>3</strong></td>
                                <td style=\"text-align: center;\"><strong>4</strong></td>
                                <td style=\"text-align: center;\"><strong>5</strong></td>
                                <td style=\"text-align: center;\"><strong>6</strong></td>
                                <td style=\"text-align: center;\"><strong>7=3+4+5+6 </strong></td>
                            </tr>";
                                                        
  
                    //Pendapatan
                    $qtriw = " SELECT isnull( a.triw1,0) AS triw1,isnull( a.triw2,0) AS triw2,isnull( a.triw3,0) 
                                AS triw3,isnull( a.triw4,0) AS triw4  FROM trskpd a join (select kd_sub_kegiatan from trdrka a 
                                where $a a.$skpd$b='$id' group by kd_sub_kegiatan) b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                WHERE RIGHT (a.kd_sub_kegiatan,5)='00.04' AND $a a.$skpd$b='$id'";
                    $qtriwl = $this->db->query($qtriw);
                    $num_rows = $qtriwl->num_rows();
                    if ($num_rows!=0){
                        foreach ($qtriwl->result() as $triw){
                            $triw1=  empty($triw->triw1) ? 0 : $triw->triw1;
                            $triw2= empty($triw->triw2) ? 0 :$triw->triw2;
                            $triw3= empty($triw->triw3)  ? 0 :$triw->triw3;
                            $triw4= empty($triw->triw4) ? 0 :$triw->triw4;
                            $total = $triw1 + $triw2 + $triw3 + $triw4;
                        }
                    }else{
                        $triw1=  0;
                        $triw2= 0;
                        $triw3= 0;
                        $triw4= 0;
                        $total = $triw1 + $triw2 + $triw3 + $triw4;                            
                    }
                    
                    
                    $triw1 = number_format($triw1,2,',','.');
                    $triw2= number_format($triw2,2,',','.');
                    $triw3= number_format($triw3,2,',','.');
                    $triw4= number_format($triw4,2,',','.');
                    $total = number_format($total,2,',','.');
     
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">1</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">Pendapatan</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw1</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw2</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw3</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw4</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$total</td></tr>";                   
                    
                    //BTL
                    $qtriw = " SELECT isnull( a.triw1,0) AS triw1,isnull( a.triw2,0) AS triw2,isnull( a.triw3,0) AS triw3,isnull( a.triw4,0) AS triw4 FROM trskpd a
                                join (select kd_sub_kegiatan from trdrka a 
                                where $a a.$skpd$b='$id' group by kd_sub_kegiatan) b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                WHERE RIGHT (a.kd_sub_kegiatan,5)='00.51' AND $a a.$skpd$b='$id'";
                    $qtriwl = $this->db->query($qtriw);
                    $num_rows = $qtriwl->num_rows();
                    if ($num_rows!=0){
                        foreach ($qtriwl->result() as $triw){
                            $triw1=  empty($triw->triw1) ? 0 : $triw->triw1;
                            $triw2= empty($triw->triw2) ? 0 :$triw->triw2;
                            $triw3= empty($triw->triw3)  ? 0 :$triw->triw3;
                            $triw4= empty($triw->triw4) ? 0 :$triw->triw4;
                            $total = $triw1 + $triw2 + $triw3 + $triw4;
                        }
                    }else{
                        $triw1=  0;
                        $triw2= 0;
                        $triw3= 0;
                        $triw4= 0;
                        $total = $triw1 + $triw2 + $triw3 + $triw4;                            
                    }
                                        
                    $triw1 = number_format($triw1,2,',','.');
                    $triw2= number_format($triw2,2,',','.');
                    $triw3= number_format($triw3,2,',','.');
                    $triw4= number_format($triw4,2,',','.');
                    $total = number_format($total,2,',','.');
       
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">2.1</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">Belanja Tidak Langsung</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw1</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw2</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw3</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw4</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$total</td></tr>";                   

                    //BL
                    $qtriw = " SELECT  isnull(sum(a.triw1),0) AS triw1,isnull(sum(a.triw2),0) AS triw2,isnull(sum(a.triw3),0) 
                                AS triw3,isnull(sum(a.triw4),0) AS triw4 FROM trskpd a
                                join (select kd_sub_kegiatan from trdrka a 
                                where $a a.$skpd$b='$id' group by kd_sub_kegiatan) b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                WHERE RIGHT (a.kd_program,2)<>'00' AND $a a.$skpd$b='$id'";
                    $qtriwl = $this->db->query($qtriw);
                    $num_rows = $qtriwl->num_rows();
                    if ($num_rows!=0){
                        foreach ($qtriwl->result() as $triw){
                            $triw1=  empty($triw->triw1) ? 0 : $triw->triw1;
                            $triw2= empty($triw->triw2) ? 0 :$triw->triw2;
                            $triw3= empty($triw->triw3)  ? 0 :$triw->triw3;
                            $triw4= empty($triw->triw4) ? 0 :$triw->triw4;
                            $total = $triw1 + $triw2 + $triw3 + $triw4;
                        }
                    }else{
                        $triw1=  0;
                        $triw2= 0;
                        $triw3= 0;
                        $triw4= 0;
                        $total = $triw1 + $triw2 + $triw3 + $triw4;                            
                    }
                    
                    $triw1 = number_format($triw1,2,',','.');
                    $triw2= number_format($triw2,2,',','.');
                    $triw3= number_format($triw3,2,',','.');
                    $triw4= number_format($triw4,2,',','.');
                    $total = number_format($total,2,',','.');
        
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">2.2</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">Belanja Langsung</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw1</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw2</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw3</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw4</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$total</td></tr>";                   
 
 
                    //Penerimaan Pembiayaan 
                    $qtriw = "  SELECT isnull( a.triw1,0) AS triw1,isnull( a.triw2,0) AS triw2,isnull( a.triw3,0) 
                                AS triw3,isnull( a.triw4,0) AS triw4 FROM trskpd a join (select kd_sub_kegiatan from trdrka a 
                                where $a a.$skpd$b='$id' group by kd_sub_kegiatan) b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                WHERE RIGHT (a.kd_sub_kegiatan,5)='00.61' AND $a a.$skpd$b='$id'";
                    
                    
                    $qtriwl = $this->db->query($qtriw);
                    $num_rows = $qtriwl->num_rows();
                    if ($num_rows!=0){
                        foreach ($qtriwl->result() as $triw){
                            $triw1=  empty($triw->triw1) ? 0 : $triw->triw1;
                            $triw2= empty($triw->triw2) ? 0 :$triw->triw2;
                            $triw3= empty($triw->triw3)  ? 0 :$triw->triw3;
                            $triw4= empty($triw->triw4) ? 0 :$triw->triw4;
                            $total = $triw1 + $triw2 + $triw3 + $triw4;
                        }
                    }else{
                        $triw1=  0;
                        $triw2= 0;
                        $triw3= 0;
                        $triw4= 0;
                        $total = $triw1 + $triw2 + $triw3 + $triw4;                            
                    }
                    
                    $triw1 = number_format($triw1,2,',','.');
                    $triw2= number_format($triw2,2,',','.');
                    $triw3= number_format($triw3,2,',','.');
                    $triw4= number_format($triw4,2,',','.');
                    $total = number_format($total,2,',','.');
                    
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">3.1</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">Penerimaan Pembiayaan</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw3</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$total</td></tr>";                   

                    //Pengeluaran Pembiayaan
                    $qtriw = "  SELECT isnull( a.triw1,0) AS triw1,isnull( a.triw2,0) AS triw2,isnull( a.triw3,0) 
                                AS triw3,isnull( a.triw4,0) AS triw4 FROM trskpd a join (select kd_sub_kegiatan from trdrka a 
                                where $a a.$skpd$b='$id' group by kd_sub_kegiatan) b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                                WHERE RIGHT (a.kd_sub_kegiatan,5)='00.62' AND $a a.$skpd$b='$id'";
                    $qtriwl = $this->db->query($qtriw);
                    $num_rows = $qtriwl->num_rows();
                    if ($num_rows!=0){
                        foreach ($qtriwl->result() as $triw){
                            $triw1=  empty($triw->triw1) ? 0 : $triw->triw1;
                            $triw2= empty($triw->triw2) ? 0 :$triw->triw2;
                            $triw3= empty($triw->triw3)  ? 0 :$triw->triw3;
                            $triw4= empty($triw->triw4) ? 0 :$triw->triw4;
                            $total = $triw1 + $triw2 + $triw3 + $triw4;
                        }
                    }else{
                        $triw1=  0;
                        $triw2= 0;
                        $triw3= 0;
                        $triw4= 0;
                        $total = $triw1 + $triw2 + $triw3 + $triw4;                            
                    }
                    
                    
                    $triw1 = number_format($triw1,2,',','.');
                    $triw2= number_format($triw2,2,',','.');
                    $triw3= number_format($triw3,2,',','.');
                    $triw4= number_format($triw4,2,',','.');
                    $total = number_format($total,2,',','.');
                        
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">3.2</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\">Pengeluaran Pembiayaan</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw3</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$triw4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\">$total</td></tr>";                   

                   
                    $cRet .="<tr>
                                <td width=\"100%\" align=\"left\" colspan=\"7\">
                                <table border=\"0\" width=\"100%\">
                                <tr>
                                <td width=\"70%\" align=\"left\">&nbsp;<br>&nbsp;
                                <br>&nbsp;
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;  
                                </td>
                                <td width=\"30%\" align=\"center\">$daerah ,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <br>$jdlnip1
                                <br>$jabatan1
                                <p>&nbsp;</p>
                                &nbsp;<br>
                                &nbsp;<br>
                                <br><strong><ins>$nama1</ins></strong>
                                <br>$pangkat1
                                <br>$nip1 
                                </td></tr></table></td>
                             </tr>";
        
              
        $cRet    .= "</table>";
        $data['prev']= $cRet;    
        //$this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        switch($cetak) {
        case 0;
               echo ("<title>Report DPA-0</title>");
                echo($cRet);
  
 //           $this->template->load('template','anggaran/rka/perkadaII',$data);
        break;
        case 1;
             $this->_mpdf('',$cRet,10,10,10,'0');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= DPA0.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 3;        
            $this->_mpdf_down('',$cRet,10,10,10,'0');
        break;       

        }    
    }

    function _mpdf_down($judul='',$isi='',$lMargin='',$rMargin='',$font=10,$orientasi='',$hal='',$tab='',$jdlsave='',$tMargin='') {
                

        ini_set("memory_limit","-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        
        
        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 1; /* in pts */
        $this->mpdf->defaultfooterfontstyle = blank;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 0; 
        $sa=1;
        $tes=0;
        if ($hal==''){
        $hal1=1;
        } 
        if($hal!==''){
        $hal1=$hal;
        }
        
        if ($tMargin=='' ){
            $tMargin=16;
        }
        
        if($lMargin==''){
            $lMargin=15;
        }

        if($rMargin==''){
            $rMargin=15;
        }
        
        
        $this->mpdf = new mPDF('utf-8', array(215,330),$size,'',$lMargin,$rMargin,$tMargin); //folio
        
        $mpdf->cacheTables = true;
        $mpdf->packTableData=true;
        $mpdf->simpleTables=true;
        $this->mpdf->AddPage($orientasi,'',$hal1,'1','off');
        if (!empty($tab)) $this->mpdf->SetTitle($tab); 
        if ($hal != 'no'){
            $this->mpdf->SetFooter("Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        //$this->mpdf->simpleTables= true;     
        $this->mpdf->writeHTML($isi);         
        //$this->mpdf->Output('');
        $this->mpdf->Output($judul,'D');
    }

    //////////////////////// MPDF
        function _mpdf($judul='',$isi='',$lMargin='',$rMargin='',$font=10,$orientasi='',$hal='',$tab='',$jdlsave='',$tMargin='') {
                

        ini_set("memory_limit","-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        

        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 1; /* in pts */
        $this->mpdf->defaultfooterfontstyle = blank;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 0; 
        $sa=1;
        $tes=0;
        if ($hal==''){
        $hal1=1;
        } 
        if($hal!==''){
        $hal1=$hal;
        }
        
        if ($tMargin=='' ){
            $tMargin=16;
        }
        
        if($lMargin==''){
            $lMargin=15;
        }

        if($rMargin==''){
            $rMargin=15;
        }
        
        
        $this->mpdf = new mPDF('utf-8', array(215,330),$size,'',$lMargin,$rMargin,$tMargin); //folio
        
        $mpdf->cacheTables = true;
        $mpdf->packTableData=true;
        $mpdf->simpleTables=true;
        $this->mpdf->AddPage($orientasi,'',$hal1,'1','off');
        if (!empty($tab)) $this->mpdf->SetTitle($tab); 
        if ($hal != 'no'){
            $this->mpdf->SetFooter("Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        //$this->mpdf->simpleTables= true;     
        $this->mpdf->writeHTML($isi);         
        //$this->mpdf->Output('');
        $this->mpdf->Output($judul,'I');
    }
    ////////////////////////MPDF


    function preview_cover_dpa_skpd(){
        $id = $this->uri->segment(2);
        $cetak = $this->uri->segment(3);
        $keu1 = $this->keu1;
        $sqlorg="SELECT kd_org,nm_org FROM ms_organisasi WHERE kd_org=LEFT('$id',17)";
                 $sqlorg1=$this->db->query($sqlorg);
                 foreach ($sqlorg1->result() as $rowdns)
                {
                   
                    $kd_org=$rowdns->kd_org;                    
                    $nm_org= $rowdns->nm_org;
                }
                
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$keu1'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                   // $tanggal = $this->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
                                
        $cRet='';
        $cRet .="<table style=\"border-collapse:collapse;font-size:12; margin-top: 200px;\" width=\"80%\"  align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tbody>
                    <tr> 
                        <td style=\"border-collapse:collapse;\"  width=\"60%\" align=\"center\"><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"100\" height=\"100\" /></td>                   
                    </tr>
                    <tr>
                        <td width=\"60%\" align=\"center\"><strong>$kab</strong></td>
                    </tr>
                    <tr>
                        <td width=\"60%\" align=\"center\"><strong>DOKUMEN PELAKSANAAN ANGGARAN</strong></td>
                    </tr>
                    <tr>
                        <td style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\"><strong>SATUAN KERJA PERANGKAT DAERAH (DPA SKPD)</strong></td>                    
                    </tr>
                     <tr>
                        <td style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\"><strong>$nm_org</strong></td>                    
                    </tr>
                     <tr>
                        <td style=\"border-collapse:collapse\" width=\"60%\" align=\"center\"><strong>TAHUN ANGGARAN $thn</strong></td>                    
                    </tr>
 
                      <tr>
                        <td style=\"border-collapse:collapse\" width=\"60%\" align=\"center\"><strong>&nbsp;</strong></td>                    
                    </tr>
                    </tbody>
                </table>";

        $cRet .="<table style=\"border-collapse:collapse;font-size:12;margin: 0 auto;\" width=\"80%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                    <thead>
                    <tr>
                        <td width=\"20%\" align=\"center\" style=\"font-weight: bold;\">Kode</td> 
                        <td width=\"60%\" align=\"center\" style=\"font-weight: bold;\">Nama Formulir</td>
                    </tr>
                    </thead>
                    <tbody align=\"left\">
                    <tr>
                        <td width=\"20%\" ><strong>DPA - SKPD</strong></td> 
                        <td width=\"60%\" >Ringkasan Dokumen Pelaksanaan Anggaran Satuan Kerja Perangkat Daerah</td>                    
                    </tr>
                    <tr>
                        <td width=\"20%\" ><strong>DPA - SKPD 1</strong></td> 
                        <td width=\"60%\" >Rincian Dokumen Pelaksanaan Anggaran Pendapatan Satuan Kerja Perangkat Daerah </td>                    
                    </tr>
                     <tr>
                        <td width=\"20%\" ><strong>DPA - SKPD 2.1</strong></td> 
                        <td width=\"60%\" >Rincian Dokumen Pelaksanaan Anggaran Belanja Tidak Langsung Satuan Kerja Perangkat Daerah </td>                    
                    </tr>
                     <tr>
                        <td width=\"20%\" ><strong>DPA - SKPD 2.2</strong></td> 
                        <td width=\"60%\" >Rekapitulasi Belanja Langsung menurut Program dan Kegiatan Satuan Kerja Perangkat Daerah</td>                    
                    </tr>
                     <tr>
                        <td width=\"20%\" ><strong>DPA - SKPD 2.2.1</strong></td> 
                        <td width=\"60%\" >Rincian Dokumen Pelaksanaan Anggaran Belanja Langsung Program dan Per Kegiatan Satuan Kerja Perangkat Daerah </td>                    
                    </tr> 
                     <tr>
                        <td width=\"20%\" ><strong>DPA - SKPD 3.1</strong></td> 
                        <td width=\"60%\" >Rincian Penerimaan Pembiayaan Daerah</td>                    
                    </tr>                                        
                      <tr>
                        <td width=\"20%\" ><strong>DPA - SKPD 3.2</strong></td> 
                        <td width=\"60%\" >Rincian Pengeluaran Pembiayaan Daerah</td>                    
                    </tr>                                                                                                   
                    </tbody>
                </table>";
        
        $data['prev']= $cRet;    
        //$this->_mpdf('',$cRet,10,10,10,0);
        //$this->template->load('template','master/fungsi/list_preview',$data);
        switch($cetak) {
        case 0;
               echo ("<title>Report Cover DPA-0</title>");
                echo($cRet);
  
 //           $this->template->load('template','anggaran/rka/perkadaII',$data);
        break;
        case 1;
             $this->_mpdf('',$cRet,10,10,10,'0','no','','',50);
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= DPA0.xls");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        }   
     }     

        ///batas akhir

    }
    ?>