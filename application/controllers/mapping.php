<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Mapping extends CI_Controller {

    function __contruct()
    {   
        parent::__construct();
    }
    
    function test()
    {

    $this->load->dbutil();
    $query = $this->db->query("SELECT * FROM ms_bank");
    $config = array (
                  'root'    => 'root',
                  'element' => 'element',
                  'newline' => "\n",
                  'tab'    => "\t"
                );

    echo $this->dbutil->xml_from_result($query, $config); 

    }

    /// MAPPING URUSAN
    
 

    function ambil_urusan()
    {
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        
       
        $sql = "SELECT a.kd_urusan,a.nm_urusan FROM tsimdal a where a.kd_urusan NOT IN  
                    (SELECT b.kd_urusan FROM mapping_urusan b WHERE b.kd_urusan=a.kd_urusan) AND (upper(kd_urusan) like upper('%$lccr%') or upper(nm_urusan) like upper('%$lccr%')) 
                     group by kd_urusan,nm_urusan
                     order by kd_Urusan";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_urusan' => $resulte['kd_urusan'], 
                'nm_urusan' => $resulte['nm_urusan']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



    function ambil_urusan90(){
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_bidang_urusan,a.nm_bidang_urusan FROM ms_bidang_urusan a where  (upper(kd_bidang_urusan) like upper('%$lccr%') or upper(nm_bidang_urusan) like upper('%$lccr%')) 
                     order by kd_Urusan";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_bidang_urusan' => $resulte['kd_bidang_urusan'], 
                'nm_bidang_urusan' => $resulte['nm_bidang_urusan']
                 );
            $ii++;
        }

        echo json_encode($result);
    }




   


    function ambil_program()
    {
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        $where="";
        if ($lccr!=''){
            $where="AND (upper(kd_urusan) like upper('%$lccr%') or upper(nm_urusan) like upper('%$lccr%')) ";
        }
       
        $sql = "SELECT * from ambil_program where(upper(kd_program) like upper('%$lccr%') 
                or upper(nm_program) like upper('%$lccr%')) order by kd_program";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_program' => $resulte['kd_program'], 
                'nm_program' => $resulte['nm_program']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function ambil_program90(){
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $urusan         = substr($this->input->post('kode'),0,4);
        $kd_urusan      = str_replace('-','.',$urusan);
        $lccr = $this->input->post('q');
        $sql = "SELECT * from ambil_program90  where left(kd_program,4) in ('$kd_urusan','X.XX') AND (upper(kd_program) like upper('%$lccr%') or upper(nm_program) like upper('%$lccr%')) 
                     order by kd_program";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_program' => $resulte['kd_program'], 
                'nm_program' => $resulte['nm_program']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



    function load_detail_program()
    {
        $nomor = $this->input->post('no');
        $sql = "SELECT * FROM detail_program where kode='$nomor'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array(
                'kd_program1'    => $resulte['kode'], 
                'nm_program1'    => $resulte['nama'],
                'kd_program90'   => $resulte['kd_program90'], 
                'nm_program90'   => $resulte['nm_program90'],
                'jml'           => $resulte['jml']
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
    

  


    // MAPPING KEGIATAN ->> SUBKEGIATAN
    
    function map_kegiatan()
    {
        $data['page_title'] = 'INPUT MAPPING KEGIATAN';
        $this->template->set('title', 'INPUT MAPPING KEGIATAN');
        $this->template->load('template', 'mapping/map_kegiatan', $data);
    }


    function load_kegiatan()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where="";
        if ($kriteria <> '')
        {
            $where = "AND (upper(kd_kegiatan) like upper('%$kriteria%') or nm_kegiatan like upper('%$kriteria%'))";
        }

        $sql = "SELECT count(*) as total from load_kegiatan";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();
        $sql = " SELECT TOP $rows * from load_kegiatan where kd_kegiatan not in (SELECT TOP  $offset kd_kegiatan from load_kegiatan) $where  order by kd_kegiatan";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_skpd'     => $resulte['kd_skpd'], 
                'nm_skpd'     => $resulte['nm_skpd'],
                'waktu_giat'   => $resulte['waktu_giat'],
                'sasaran_giat'   => $resulte['sasaran_giat'],
                'tu_capai'   => $resulte['tu_capai'],
                'tu_mas'   => $resulte['tu_mas'],
                'tu_kel'   => $resulte['tu_kel'],
                'tu_has'   => $resulte['tu_has'],
                'tk_capai'   => $resulte['tk_capai'],
                'tk_kel'   => $resulte['tk_kel'],
                'tk_has'   => $resulte['tk_has'],
                'lokasi'   => $resulte['lokasi'],
                'kd_kegiatan'     => $resulte['kd_kegiatan'], 
                'nm_kegiatan'     => $resulte['nm_kegiatan'],
                'jml'           => $resulte['jml'],
                'pagu1'           => $resulte['pagu'],
                'pagu'           => $this->angka($resulte['pagu'])
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }



    function ambil_skpd_mapping()
    {
        $kode  = $this->session->userdata('kdskpd'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        $where="";
        if ($lccr!=''){
            $where="AND (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) ";
        }

        $sqlskpd="SELECT * FROM mapping_skpd where kode90='$kode'";
        $queryskpd = $this->db->query($sqlskpd);
        foreach ($queryskpd->result_array() as $rows)
        {

            $kd_skpd        = $rows['kd_skpd'];
            $nm_skpd        = $rows['nm_skpd'];
        }
       
        $sql = "SELECT a.* from ambil_skpd_mapping a inner join ms_skpd b on a.kd_skpd90=b.kd_skpd 
                where (validasi_kegiatan ='' OR validasi_kegiatan IS NULL)  and a.kd_skpd='$kd_skpd'
                AND  (upper(a.kd_skpd) like upper('%$lccr%') 
                or upper(a.nm_skpd) like upper('%$lccr%')) order by kd_skpd";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_skpd'       => $resulte['kd_skpd'], 
                'nm_skpd'       => $resulte['nm_skpd'],
                'kd_skpd90'     => $resulte['kd_skpd90'], 
                'nm_skpd90'     => $resulte['nm_skpd90']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function ambil_kegiatan()
    {
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        $skpd = $this->input->post('skpd');
        $where="";
        if ($lccr!=''){
            $where="AND (upper(kd_kegiatan) like upper('%$lccr%') or upper(nm_kegiatan) like upper('%$lccr%')) ";
        }
       
        $sql = "SELECT * from ambil_kegiatan where kd_skpd='$skpd' $where order by kd_kegiatan";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_kegiatan'       => $resulte['kd_kegiatan'], 
                'nm_kegiatan'       => $resulte['nm_kegiatan'],
                'waktu_giat'        => $resulte['waktu_giat'],
                'sasaran_giat'      => $resulte['sasaran_giat'],
                'indikator'         => $resulte['indikator'],
                 'tu_capai'          => $resulte['tu_capai'],
                'tu_mas'            => $resulte['tu_mas'],
                'tu_kel'            => $resulte['tu_kel'],
                'tu_has'            => $resulte['tu_has'],
                'tk_capai'          => $resulte['tk_capai'],
                'tk_kel'            => $resulte['tk_kel'],
                'tk_has'            => $resulte['tk_has'],
                'lokasi'            => $resulte['lokasi'],
                'pagu'              => $resulte['pagu'],
                'angg_lalu'         => $resulte['lalu'],
                'angg_lalu1'        => $this->angka($resulte['lalu']),
                'pagu1'             => $this->angka($resulte['pagu'])
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function ambil_sub_kegiatan90(){
        $id         = $this->session->userdata('pcUser'); 
        $usernm     = $this->session->userdata('pcNama');
        $skpd       = $this->input->post('skpd');
        
        if ($skpd=='1-03.0-00.0-00.01.01' || $skpd=='1-04.0-00.0-00.01.01'){
            $kd_urusan1 ='1.03';
            $kd_urusan2 ='1.04';
            $kd_urusan3 ='0.00';
        }else{
            $urusan1     = substr($this->input->post('skpd'),0,4);
            $urusan2     = substr($this->input->post('skpd'),5,4);
            $urusan3     = substr($this->input->post('skpd'),10,4);
            $kd_urusan1  = str_replace('-','.',$urusan1);
            $kd_urusan2  = str_replace('-','.',$urusan2);
            $kd_urusan3  = str_replace('-','.',$urusan3);   
        }

        
        $lccr       = $this->input->post('q');



        $sql = "SELECT * from ambil_sub_kegiatan90 where left(kd_kegiatan,4)in ('$kd_urusan1','$kd_urusan2','$kd_urusan3') and  (upper(kd_sub_kegiatan) like upper('%$lccr%') or nm_sub_kegiatan like '%$lccr%') 
                     order by kd_sub_kegiatan";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'], 
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_kegiatan90'   => $resulte['kd_kegiatan'],
                'kd_program90'    => $resulte['kd_program']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function ambil_kegiatan90(){
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        $urusan         = substr($this->input->post('kode'),0,4);
        $kd_urusan      = str_replace('-','.',$urusan);
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT * from ambil_kegiatan90 where left(kd_kegiatan,4) in ('$kd_urusan','X.XX') and  (upper(kd_kegiatan) like upper('%$lccr%') or upper(nm_kegiatan) like upper('%$lccr%')) 
                     order by kd_kegiatan";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_kegiatan' => $resulte['kd_kegiatan'], 
                'nm_kegiatan' => $resulte['nm_kegiatan'],
                'kd_program90'    => $resulte['kd_program']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function ambil_programp90(){
        $id         = $this->session->userdata('pcUser'); 
        $usernm     = $this->session->userdata('pcNama');
        $kegiatan   = $this->input->post('kode');
        $urusan     = substr($this->input->post('kode'),0,4);
        $kd_urusan  = str_replace('-','.',$urusan);
        $lccr = $this->input->post('q');

        $sql = "SELECT * from ms_program where left(kd_program,4) IN ('$kd_urusan','X.XX') and  (upper(kd_program) like upper('%$lccr%') or upper(nm_program) like upper('%$lccr%')) 
                     order by kd_program";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_program' => $resulte['kd_program'], 
                'nm_program' => $resulte['nm_program']
                 );
            $ii++;
        }

        echo json_encode($result);
    }

    function simpan_kegiatan()
    {
        $tabel  = $this->input->post('tabel');
        $kode   = $this->input->post('kode');
        $csql = $this->input->post('sql');
        $csqltrskpd = $this->input->post('sqltrskpd');

        $usernm = $this->session->userdata('pcNama');
        $update = date('y-m-d H:i:s');
        $msg = array();

        if ($tabel == 'mapping_kegiatan')
        {

                // Simpan Detail //
                $sql = "delete from mapping_kegiatan where kd_kegiatan='$kode'";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    //simpan ke tabel mapping kegiatan
                    $sql = "insert into mapping_kegiatan(kd_kegiatan,nm_kegiatan,nilai,kd_kegiatan90,nm_kegiatan90,nilai90,nilailalu,nilailalu90)";
                    //simpan ke tabel trskpd
                    $asg = $this->db->query($sql . $csql);
                  
                    //  

                    $sqltrskpd = "INSERT INTO trskpd(kd_gabungan,kd_kegiatan,kd_program,kd_urusan,kd_skpd,nm_skpd,kd_kegiatan1,nm_kegiatan,jns_kegiatan,kd_program1,nm_program,
                    waktu_giat,sasaran_giat,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_kel,tk_has,ang_lalu,nilai_kua,lokasi)"; 

                    

                    $asgtrskpd = $this->db->query($sqltrskpd . $csqltrskpd);
                    if (!($asg && $asgtrskpd))
                    {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                    } else
                    {
                        $msg = array('pesan' => '1');
                        echo json_encode($msg);
                    }
                }
            }
    }



    function load_detail_kegiatan()
    {
        $nomor = $this->input->post('no');
        $sql = "SELECT * FROM detail_kegiatan where kode='$nomor'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array(
                'kd_kegiatan1'    => $resulte['kode'], 
                'nm_kegiatan1'    => $resulte['nama'],
                'kd_kegiatan90'   => $resulte['kd_kegiatan90'], 
                'nm_kegiatan90'   => $resulte['nm_kegiatan90'],
                'nilai'           => $resulte['nilai'],
                'nilai90'         => $resulte['nilai90'],
                'nilailalu'       => $resulte['nilailalu'],
                'nilailalu90'     => $resulte['nilailalu90'],
                'jml'             => $resulte['jml']
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
    

    function hapus_mapping_kegiatan()
    {
        $kode = $this->input->post('kode');
        $msg = array();
        $sql = "delete from imapping where kd_kegiatan='$kode'";
        $asg = $this->db->query($sql);
        if (!($asg))
        {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
        }else{
            $msg = array('pesan' => '1');
            echo json_encode($msg);    
        }
        
    }

    function angka($nilai){

    if($nilai<0){
        $lc = '('.number_format(abs($nilai),2,'.',',').')';
    }else{
        if($nilai==0){
            $lc =0;
        }else{
            $lc = number_format($nilai,2,'.',',');
        }
    }

    return $lc;
}


function cetak_kegiatan($jenis ='')
    {

        
        $cRet = "<br><br><table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">

                <tr>
                    <td colspan=\"9\" align=\"center\" style=\"border-top: solid 1px white;border-right: solid 1px white;border-left: solid 1px white;\"><b>HASIL PEMETAAN (MAPPING) NOMENKLATUR KEGIATAN</b>
                </tr>
            </table><br><br><br>";



        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">

             <thead>
            <tr>
                <td bgcolor=\"#c9c9c9\" align=\"center\" rowspan=\"2\"><b>No.</b></td>
                <td bgcolor=\"#c9c9c9\" colspan=\"4\" align=\"center\" ><b>RPJMD</b></td>
                <td bgcolor=\"#c9c9c9\" colspan=\"4\" align=\"center\"><b>Permendagri 90 </b></td>
            </tr>
            <tr>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Kode Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Nama Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Anggaran Lalu</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Pagu</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Kode Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Nama Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Anggaran Lalu</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Pagu</b></td>
            </tr>
            <tr>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"5%\">1</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"13%\">2</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">3</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"12%\">4</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">5</td>

                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"13%\">6</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">7</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"12%\">8</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">9</td>
            </tr>
            </thead>
           ";

        $csql = "SELECT kd_kegiatan,nm_kegiatan,sum(angg_lalu)as lalu,sum(pagu)as pagu
                FROM tsimdal 
                group by kd_kegiatan,nm_kegiatan ";
        $query = $this->db->query($csql);
        $csql1 = '';
        $lcno = 0;
        foreach ($query->result_array() as $res)
        {
            $lcno = $lcno+1;
            $kd_kegiatan    = $res['kd_kegiatan'];
            $nm_kegiatan    = $res['nm_kegiatan'];
            $lalu           = $this->angka($res['lalu']);
            $pagu           = $this->angka($res['pagu']);
            // $kd_urusan90  = $res['kd_urusan90'];
            // $nm_urusan90  = $res['nm_urusan90'];

            

            $sqlj       = "SELECT COUNT(*)as total from mapping_kegiatan where kd_kegiatan='$kd_kegiatan'";
            $queryj     = $this->db->query($sqlj);
            $jml        = $queryj->row();
            $jmlh        = $jml->total+1;


            

            
            if ($jmlh>'2'){
                $rowspan="rowspan=\"$jmlh\"";
                $sqlk       = "SELECT kd_kegiatan90,nm_kegiatan90,nilailalu90,nilai90 from mapping_kegiatan where kd_kegiatan='$kd_kegiatan'";
                $queryk     = $this->db->query($sqlk);
                $jmlk        = $queryk->row();
                $kode        = $jmlk->kd_kegiatan90;
                $nama        = $jmlk->nm_kegiatan90;
                $pagu90        = $this->angka($jmlk->nilai90);
                $angg90        = $this->angka($jmlk->nilailalu90);
            }else if ($jmlh=='2'){
                $rowspan="";
                $sqlk       = "SELECT kd_kegiatan90,nm_kegiatan90,nilailalu90,nilai90 from mapping_kegiatan where kd_kegiatan='$kd_kegiatan'";
                $queryk     = $this->db->query($sqlk);
                $jmlk        = $queryk->row();
                $kode        = $jmlk->kd_kegiatan90;
                $nama        = $jmlk->nm_kegiatan90;
                $pagu90        = $this->angka($jmlk->nilai90);
                $angg90        = $this->angka($jmlk->nilailalu90);
            }else{
                $rowspan="";
                $kode='';
                $nama='';
                $pagu90        = '';
                $angg90        = '';
            }

            $cRet .= "<tr>
                      <td $rowspan align='center' style=\"font-size:12px\">$lcno</td>";

            if ($jmlh=='' || $jmlh==1){
                 $cRet .= "
                    <td align='center' style=\"font-size:12px\">$kd_kegiatan</td>
                    <td style=\"font-size:12px\" >$nm_kegiatan</td>
                    <td align='right' style=\"font-size:12px\" >$lalu</td>
                    <td align='right' style=\"font-size:12px\" >$pagu</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    </tr>";
                }else if ($jmlh==2){
                    $cRet .= "
                                <td align='center' $rowspan style=\"font-size:12px\">$kd_kegiatan</td>
                                <td $rowspan style=\"font-size:12px\">$nm_kegiatan</td>
                                <td align='right' $rowspan style=\"font-size:12px\">$lalu</td>
                                <td align='right' style=\"font-size:12px\">$pagu</td>

                                <td align='center' $rowspan style=\"font-size:12px\">$kode</td>
                                <td style=\"font-size:12px\">$nama</td>
                                <td align='right' $rowspan style=\"font-size:12px\">$angg90</td>
                                <td align='right' style=\"font-size:12px\">$pagu90</td>
                        </tr>";
                }else if ($jmlh>2){


                    $cRet .= "      
                                    <td align='center' $rowspan style=\"font-size:12px\">$kd_kegiatan</td>
                                    <td $rowspan style=\"font-size:12px\">$nm_kegiatan</td>
                                    <td $rowspan align='right' style=\"font-size:12px\">$lalu</td>
                                    <td $rowspan align='right' style=\"font-size:12px\">$pagu</td>
                                </tr>";

                $csql1 = "SELECT * FROM mapping_kegiatan where kd_kegiatan='$kd_kegiatan'";
                $query1 = $this->db->query($csql1);
                $lcno = 0;
                foreach ($query1->result_array() as $res1)
                    {                                
                    
                    $kd_kegiatan90  = $res1['kd_kegiatan90'];
                    $nm_kegiatan90  = $res1['nm_kegiatan90'];
                    $pagu90        = $this->angka($res1['nilai90']);
                    $angg90    = $this->angka($res1['nilailalu90']);

                $cRet .= " 
                                <tr>
                                    <td align='center' style=\"font-size:12px\">$kd_kegiatan90</td>
                                    <td style=\"font-size:12px\">$nm_kegiatan90</td>  
                                    <td align='right' style=\"font-size:12px\">$angg90</td>  
                                    <td align='right' style=\"font-size:12px\">$pagu90</td>  
                                </tr>
                        ";
                    }
                } 
                
                }              

           

       

        $cRet .= "
                   
         </table>
         ";

         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = ("Mapping Kode program");
        $this->template->set('title', 'Mapping Kode program');  
         switch ($jenis)
        {
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '1');
                echo $cRet;
                break;
            case 1;
                echo "<title>Mapping Kode program</title>";
                echo $cRet;
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
        }

    }


    //SKPD
    function map_skpd()
    {
        $data['page_title'] = 'INPUT MAPPING SKPD';
        $this->template->set('title', 'INPUT MAPPING SKPD');
        $this->template->load('template', 'mapping/map_skpd', $data);
    }


     function load_skpd()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where="";
        if ($kriteria <> '')
        {
            $where = "and (upper(kd_skpd) like upper('%$kriteria%') or nm_skpd like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from load_skpd";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " 
            SELECT TOP $rows * from load_skpd where kd_skpd not in (SELECT TOP  $offset kd_skpd from load_skpd) $where  order by kd_skpd
        ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_skpd'       => $resulte['kd_skpd'], 
                'nm_skpd'       => $resulte['nm_skpd'],
                'kd_u1'       => $resulte['kd_u1'],
                'kd_u2'       => $resulte['kd_u2'],
                'kd_u3'       => $resulte['kd_u3'],
                'kd_u4'       => $resulte['kd_u4'],
                'kd_u5'       => $resulte['kd_u5'],
                'kode90'        => $resulte['kode90'], 
                'nama90'        => $resulte['nama90'],
                'jml'           => $resulte['jml']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }





    function ambil_skpd()
    {
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        
       
        $sql = "SELECT a.kd_skpd,a.nm_skpd FROM tsimdal a where a.kd_skpd NOT IN  
                    (SELECT b.kd_skpd FROM mapping_skpd b WHERE b.kd_skpd=a.kd_skpd) AND (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) 
                     group by kd_skpd,nm_skpd
                     order by kd_skpd";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_skpd' => $resulte['kd_skpd'], 
                'nm_skpd' => $resulte['nm_skpd']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



    function ambil_skpd90(){
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_bidang_skpd,a.nm_bidang_skpd FROM ms_bidang_skpd a where  (upper(kd_bidang_skpd) like upper('%$lccr%') or upper(nm_bidang_skpd) like upper('%$lccr%')) 
                     order by kd_skpd";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_bidang_skpd' => $resulte['kd_bidang_skpd'], 
                'nm_bidang_skpd' => $resulte['nm_bidang_skpd']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



    function simpan_skpd(){
        
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $kd_skpd  = $this->session->userdata('kdskpd');
        
        $sql = "select $cid from $tabel where $cid='$lcid'";
        $res = $this->db->query($sql);
        if($res->num_rows()>0){
            echo '1';
        }else{
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);
            if($asg){
                echo '2';
            }else{
                echo '0';
            }
        }
    }


    function simpan_skpddouble(){
        
        $tabel      = $this->input->post('tabel');
        $lckolom    = $this->input->post('kolom');
        $lcnilai    = $this->input->post('nilai');
        $cid        = $this->input->post('cid');
        $lcid       = $this->input->post('lcid');
        //----------------------------------
        $tabel2      = $this->input->post('tabel2');
        $lckolom2    = $this->input->post('kolom2');
        $lcnilai2    = $this->input->post('nilai2');
        $cid2        = $this->input->post('cid2');
        $lcid2       = $this->input->post('lcid2');

        $kd_skpd    = $this->session->userdata('kdskpd');
        
        $sql = "select $cid from $tabel where $cid='$lcid'";
        $res = $this->db->query($sql);

        $sql2 = "select $cid2 from $tabel2 where $cid2='$lcid2'";
        $res2 = $this->db->query($sql2);


        if($res->num_rows()>0 && $res2->num_rows()>0){
            echo '1';
        }else{
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);

            $sql2 = "insert into $tabel2 $lckolom2 values $lcnilai2";
            $asg2 = $this->db->query($sql2);
            if($asg && $asg2){
                echo '2';
            }else{
                echo '0';
            }
        }
    }



     function load_detail_skpd()
    {
        $nomor = $this->input->post('no');
        $sql = "SELECT * FROM detail_skpd where kode='$nomor'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array(
                'kd_skpd1'    => $resulte['kode'], 
                'nm_skpd1'    => $resulte['nama'],
                'kd_skpd90'   => $resulte['kd_skpd90'], 
                'nm_skpd90'   => $resulte['nm_skpd90'],
                'jml'           => $resulte['jml']
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }


    function update_skpd(){
        $query = $this->input->post('st_query');
        $asg = $this->db->query($query);
        if($asg){
            echo '1';
        }else{
            echo '0';
        }
    }
    
function hapus_skpd(){
        $ctabel = $this->input->post('tabel');
        $cid = $this->input->post('cid');
        $cnid = $this->input->post('cnid');
        $cbidang1 = '1';
        
        $csql = "delete from $ctabel where $cid = '$cnid'";
                
        $asg = $this->db->query($csql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    } 



    function imapping()
    {
        $data['page_title'] = 'INPUT MAPPING';
        $this->template->set('title', 'INPUT MAPPING');
        $this->template->load('template', 'mapping/imapping', $data);
    }


    function load_mapping(){
        $kode  = $this->session->userdata('kdskpd'); 

        $sqlskpd="SELECT * FROM mapping_skpd where kode90='$kode'";
        $queryskpd = $this->db->query($sqlskpd);
        foreach ($queryskpd->result_array() as $rows)
        {

            $kd_skpd        = $rows['kd_skpd'];
            $nm_skpd        = $rows['nm_skpd'];
        }


        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');

        

        $where="";
        if ($kriteria <> '')
        {
            $where = "AND (upper(kd_kegiatan) like upper('%$kriteria%') or nm_kegiatan like upper('%$kriteria%'))";
        }

        $sql = "SELECT count(*) as total from load_mapping where kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " SELECT TOP $rows * from load_mapping where kd_skpd='$kd_skpd' and kd_kegiatan not in (SELECT TOP  $offset kd_kegiatan from load_mapping) $where  order by kd_kegiatan";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_skpd'     => $resulte['kd_skpd'], 
                'nm_skpd'     => $resulte['nm_skpd'],
                'indikator'   => $resulte['indikator'],
                'kd_kegiatan'       => $resulte['kd_kegiatan'], 
                'nm_kegiatan'       => $resulte['nm_kegiatan'],
                'kd_sub_kegiatan90' => $resulte['kd_sub_kegiatan90'], 
                'nm_sub_kegiatan90' => $resulte['nm_sub_kegiatan90'],
                'kd_kegiatan90'     => $resulte['kd_kegiatan90'], 
                'nm_kegiatan90'     => $resulte['nm_kegiatan90'],
                'kd_program90'      => $resulte['kd_program90'], 
                'nm_program90'      => $resulte['nm_program90'],
                'status'            => $resulte['status'],
                'jml'               => $resulte['jml']
                // 'pagu1'           => $resulte['pagu'],
                // 'pagu'           => $this->angka($resulte['pagu'])
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }



    function simpan_mapping()
    {
        $tabel  = $this->input->post('tabel');
        $kode   = $this->input->post('kode');
        $csql = $this->input->post('sql');
        $csqltrskpd = $this->input->post('sqltrskpd');

        $usernm = $this->session->userdata('pcNama');
        $update = date('y-m-d H:i:s');
        $msg = array();

        if ($tabel == 'imapping')
        {

                // Simpan Detail //
                $sql = "delete from imapping where kd_kegiatan='$kode'";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    //simpan ke tabel mapping kegiatan
                    $sql = "insert into imapping(kd_kegiatan,nm_kegiatan,kd_sub_kegiatan90,nm_sub_kegiatan90,kd_kegiatan90,nm_kegiatan90,kd_program90,nm_program90,kd_skpd,nm_skpd,kd_skpd90,nm_skpd90,indikator)";
                    //simpan ke tabel trskpd
                    $asg = $this->db->query($sql . $csql);
                  
                    //  

                    // $sqltrskpd = "INSERT INTO trskpd(kd_gabungan,kd_kegiatan,kd_program,kd_urusan,kd_skpd,nm_skpd,kd_kegiatan1,nm_kegiatan,jns_kegiatan,kd_program1,nm_program,
                    // waktu_giat,sasaran_giat,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_kel,tk_has,ang_lalu,nilai_kua,lokasi)"; 

                    

                    // $asgtrskpd = $this->db->query($sqltrskpd . $csqltrskpd);
                    if (!($asg))
                    {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                    } else
                    {
                        $msg = array('pesan' => '1');
                        echo json_encode($msg);
                    }
                }
            }
    }



    function load_detail_mapping()
    {
        $nomor = $this->input->post('no');
        $sql = "SELECT * FROM detail_mapping where kd_kegiatan='$nomor'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array(
                'kd_kegiatan1'      => $resulte['kd_kegiatan'], 
                'nm_kegiatan1'      => $resulte['nm_kegiatan'],
                'kd_sub_kegiatan90' => $resulte['kd_sub_kegiatan90'], 
                'nm_sub_kegiatan90' => $resulte['nm_sub_kegiatan90'],
                'kd_kegiatan90'     => $resulte['kd_kegiatan90'], 
                'nm_kegiatan90'     => $resulte['nm_kegiatan90'],
                'kd_program90'      => $resulte['kd_program90'], 
                'nm_program90'      => $resulte['nm_program90'],
                'status'            => $resulte['status'],
                'jml'               => $resulte['jml']
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function cetak_kertas_kerja(){
        $skpd  = $this->session->userdata('kdskpd'); 
        $jenis = $this->uri->segment(3);
         
        $cRet = "<br><br><table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">

                <tr>
                    <td colspan=\"9\" align=\"center\" style=\"border-top: solid 1px white;border-right: solid 1px white;border-left: solid 1px white;\"><b>TABEL KERJA PEMETAAN PERMENDAGRI 90
            </table><br>";



        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">

             <thead>
            <tr>
                
                <td bgcolor=\"#c9c9c9\" colspan=\"4\" align=\"center\" ><b>PERMENDAGRI 13/RPJMD/RENSTRA/RKPD/RENJA</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" rowspan=\"2\"><b>OPD</b></td>
                <td bgcolor=\"#c9c9c9\" colspan=\"6\" align=\"center\"><b>PERMENDAGRI 90 TAHUN 2019</b></td>
            </tr>
            <tr>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Kode</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Urusan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Program</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Kode Sub Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Nama Sub Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Kode Kegiatan </b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Nama Kegiatan</b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Kode Program </b></td>
                <td bgcolor=\"#c9c9c9\" align=\"center\"><b>Nama Program</b></td>
            </tr>
            <tr>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"5%\">1</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"13%\">2</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">3</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"12%\">4</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">5</td>

                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"13%\">6</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">7</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"12%\">8</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">9</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"12%\">10</td>
                <td bgcolor=\"#c9c9c9\" align=\"center\" width=\"15%\">11</td>
            </tr>
            </thead>
           ";

           $tipe = $this->uri->segment(4);
           if ($tipe=='all'){
                $where1="";
                $where="";
           }else{
                $where="and a.kd_skpd90='$skpd'";
                $where1="and kd_skpd90='$skpd'";
           }
           

$sqlurusan="SELECT b.kd_urusan,b.nm_urusan,'','','','','','','','','','','','','' from imapping a
                LEFT JOIN 
                tsimdal b on a.kd_kegiatan=b.kd_kegiatan WHERE  b.kd_urusan is NOT NULL $where
                GROUP BY b.kd_urusan,b.nm_urusan
                ";
    $queryurusan = $this->db->query($sqlurusan);
        foreach ($queryurusan->result_array() as $rows)
        {

            $kd_urusan    = $rows['kd_urusan'];
            $urusan       = $rows['nm_urusan'];

            $sql = "SELECT count(*)+1+(select count(kd_program) as total from(
SELECT b.kd_program from imapping a
                        INNER JOIN 
                        tsimdal b on a.kd_kegiatan=b.kd_kegiatan where b.kd_urusan ='$kd_urusan' and  b.kd_urusan is NOT NULL $where
                        GROUP BY b.kd_program,b.nm_program)zz) as total from imapping where left(kd_kegiatan,4)='$kd_urusan' $where1 ";
            $query1 = $this->db->query($sql);
            $total = $query1->row();
            $result["total"] = $total->total;
            $query1->free_result();

            $rowspan="rowspan='$total->total'";
            
                $cRet .= "<tr>
                         <td align='left'   style=\"font-size:12px\">$kd_urusan</td>
                          <td align='left' valign='top' $rowspan style=\"font-size:12px\">$urusan</td>
                           <td align='left' style=\"font-size:12px\"></td>
                            <td align='left' style=\"font-size:12px\"></td>
                             <td align='left' style=\"font-size:12px\"></td>
                              <td align='left' style=\"font-size:12px\"></td>
                                  <td align='left' style=\"font-size:12px\"></td>
                                    <td align='left' style=\"font-size:12px\"></td>
                                      <td align='left' style=\"font-size:12px\"></td>
                                        <td align='left' style=\"font-size:12px\"></td>
                                          <td align='left' style=\"font-size:12px\"></td>
                                            
                                            </tr>";


            $sqlprogram="SELECT b.kd_program,'',b.nm_program,'','','','','','','','','','','','' from imapping a
                        INNER JOIN 
                        tsimdal b on a.kd_kegiatan=b.kd_kegiatan where b.kd_urusan ='$kd_urusan' $where
                        GROUP BY b.kd_program,b.nm_program
                ";
    $queryprog = $this->db->query($sqlprogram);
        foreach ($queryprog->result_array() as $row)
        {

            $kd_program    = $row['kd_program'];
            $program       = $row['nm_program'];

             $sqlt = "SELECT count(*)+1 as total from imapping where left(kd_kegiatan,13)='$kd_program' $where1";
            $query1t = $this->db->query($sqlt);
            $totalt = $query1t->row();
            $result["total"] = $totalt->total;
            $query1->free_result();


            $rowspan2="rowspan='$totalt->total'";

             $cRet .= "<tr>
                         <td align='left' style=\"font-size:12px\">$kd_program</td>
                            <td $rowspan2 align='left' valign='top' style=\"font-size:12px\">$program</td>
                            <td align='left' style=\"font-size:12px\"></td>
                            <td align='left' style=\"font-size:12px\"></td>
                              <td align='left' style=\"font-size:12px\"></td>
                                  <td align='left' style=\"font-size:12px\"></td>
                                    <td align='left' style=\"font-size:12px\"></td>
                                      <td align='left' style=\"font-size:12px\"></td>
                                        <td align='left' style=\"font-size:12px\"></td>
                                          <td align='left' style=\"font-size:12px\"></td>
                                            
                                            </tr>";

$csql = "SELECT a.kd_kegiatan as kode,a.kd_kegiatan,''as urusan,''as program,a.nm_kegiatan,a.kd_sub_kegiatan90,a.nm_sub_kegiatan90,a.kd_kegiatan90,a.nm_kegiatan90,a.kd_program90,
a.nm_program90,a.status,a.kd_skpd,a.nm_skpd,a.kd_skpd90,a.nm_skpd90 from imapping a INNER JOIN tsimdal b on a.kd_kegiatan=b.kd_kegiatan where b.kd_program = '$kd_program' $where
";
        $query = $this->db->query($csql);
        $csql1 = '';
        $lcno = 0;
        foreach ($query->result_array() as $res)
        {
            $lcno = $lcno+1;
            $kd_kegiatan        = $res['kd_kegiatan'];
            $kegiatan           = $res['nm_kegiatan'];
            $program            = $res['program'];
            $kegiatan90         = $res['nm_kegiatan90'];
            $program90          = $res['nm_program90'];
            $sub_kegiatan90     = $res['nm_sub_kegiatan90'];
            $status             = $res['status'];
            $kd_skpd            = $res['kd_skpd'];
            $nm_skpd            = $res['nm_skpd'];
            $kd_sub_kegiatan90  = $res['kd_sub_kegiatan90'];
            $kd_kegiatan90      = $res['kd_kegiatan90'];
            $kd_program90       = $res['kd_program90'];
            $kode               = $res['kode'];

            

            // $sqlj       = "SELECT COUNT(*)as total from imapping where kd_kegiatan='$kode' and $where2";
            // $queryj     = $this->db->query($sqlj);
            // $jml        = $queryj->row();
            // $jmlh        = $jml->total+2;
            // $jmlhs        = $jml->total+1;
            // $rowspan="rowspan='$jmlh'";
            // $rowspanp="rowspan='$jmlhs'";

            
                
                        $cRet .= "<tr>
                                        <td align='left' style=\"font-size:12px\">$kd_kegiatan</td>
                                        <td align='left' style=\"font-size:12px\">$kegiatan</td>
                                        <td align='left' style=\"font-size:12px\">$nm_skpd</td>
                                        <td align='left' style=\"font-size:12px\">$kd_sub_kegiatan90</td>
                                        <td align='left' style=\"font-size:12px\">$sub_kegiatan90</td>
                                        <td align='left' style=\"font-size:12px\">$kd_kegiatan90</td>
                                        <td align='left' style=\"font-size:12px\">$kegiatan90</td>
                                        <td align='left' style=\"font-size:12px\">$kd_program90</td>
                                        <td align='left' style=\"font-size:12px\">$program90</td>
                                            </tr>";
                
                
                
                } 

            }
        }

        $cRet .= "
                   
         </table>
         ";

         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = ("Mapping Kode program");
        $this->template->set('title', 'Mapping Kode program');  
         switch ($jenis)
        {
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '1');
                echo $cRet;
                break;
            case 1;
                echo "<title>Mapping Kode program</title>";
                echo $cRet;
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
        }

    }

    //tambah indikator
    function input_indikator()
    {
        $data['page_title'] = 'INPUT INDIKATOR KEGIATAN';
        $this->template->set('title', 'INPUT INDIKATOR KEGIATAN');
        $this->template->load('template', 'mapping/input_indikator', $data);
    }

  function skpd_mapping()
    {
        $id  = $this->session->userdata('pcUser'); 
        $kodeskpd      = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $where="";
        if ($lccr<>''){
            $where="AND (upper(a.kd_skpd90) like upper('%$lccr%') or upper(a.nm_skpd90) like upper('%$lccr%')) ";
        }
        
       
        $sql = "SELECT a.kd_skpd90 as kd_skpd,a.nm_skpd90 as nm_skpd,validasi_kegiatan
                FROM imapping a inner join ms_skpd b on a.kd_skpd90=b.kd_skpd where (validasi_kegiatan ='' OR validasi_kegiatan IS NULL) and kd_skpd90='$kodeskpd'  $where
                     group by kd_skpd90,nm_skpd90,validasi_kegiatan
                     order by kd_skpd90";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_skpd' => $resulte['kd_skpd'], 
                'nm_skpd' => $resulte['nm_skpd']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function get_kegiatan90(){
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        $skpd           = $this->input->post('kode');
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT kd_kegiatan90,nm_kegiatan90 from imapping where kd_skpd90 ='$skpd' and kd_kegiatan90 not in (select kd_kegiatan from trskpd where kd_skpd='$skpd')  and   (upper(kd_kegiatan90) like upper('%$lccr%') or upper(nm_kegiatan90) like upper('%$lccr%')) 
                    GROUP BY kd_kegiatan90,nm_kegiatan90
                     order by kd_kegiatan90";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_kegiatan'       => $resulte['kd_kegiatan90'], 
                'nm_kegiatan'       => $resulte['nm_kegiatan90']
                 );
            $ii++;
        }

        echo json_encode($result);
    }

//TUKAR AJA SESUAI KEBUTUHAN (ATAS UNTUK LANGSUNG DR MAPPING BAWAH MANUAL INPUT SUB KEGIATAN KE TRSKPD)

    function get_kegiatan90_(){
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        $skpd           = $this->input->post('kode');
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT kd_kegiatan,nm_kegiatan from trskpd where kd_skpd ='$skpd' /* and status_sub_kegiatan <>'1' */ and   (upper(kd_kegiatan) like upper('%$lccr%') or upper(nm_kegiatan) like upper('%$lccr%')) 
                    GROUP BY kd_kegiatan,nm_kegiatan
                     order by kd_kegiatan";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_kegiatan'       => $resulte['kd_kegiatan'], 
                'nm_kegiatan'       => $resulte['nm_kegiatan']
                 );
            $ii++;
        }

        echo json_encode($result);
    }

    function get_program90(){
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        // $kegiatan           = $this->input->post('kode');
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT kd_program,nm_program from ms_program where  (upper(kd_program) like upper('%$lccr%') or upper(nm_program) like upper('%$lccr%')) 
                    GROUP BY kd_program,nm_program
                     order by kd_program";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_program'       => $resulte['kd_program'], 
                'nm_program'       => $resulte['nm_program']
                 );
            $ii++;
        }

        echo json_encode($result);
    }

    function get_sub_kegiatan90(){ //IMAPPING
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        $kegiatan           = $this->input->post('kode');
        $skpd           = $this->input->post('skpd');
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT kd_sub_kegiatan90,nm_sub_kegiatan90 from imapping where kd_kegiatan90 ='$kegiatan' and kd_skpd90='$skpd' AND kd_sub_kegiatan90 not in (select kd_sub_kegiatan from trskpd where kd_skpd='$skpd')  and  (upper(kd_sub_kegiatan90) like upper('%$lccr%') or upper(nm_sub_kegiatan90) like upper('%$lccr%')) 
                    GROUP BY kd_sub_kegiatan90,nm_sub_kegiatan90
                     order by kd_sub_kegiatan90";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_sub_kegiatan'       => $resulte['kd_sub_kegiatan90'], 
                'nm_sub_kegiatan'       => $resulte['nm_sub_kegiatan90']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function get_sub_kegiatan90_trskpd(){ //trskpd
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        $kegiatan           = $this->input->post('kode');
        $skpd           = $this->input->post('skpd');
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT kd_sub_kegiatan,nm_sub_kegiatan from trskpd where kd_kegiatan ='$kegiatan' and kd_skpd='$skpd' and  (upper(kd_sub_kegiatan) like upper('%$lccr%') or upper(nm_sub_kegiatan) like upper('%$lccr%')) 
                    GROUP BY kd_sub_kegiatan,nm_sub_kegiatan
                     order by kd_sub_kegiatan";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_sub_kegiatan'       => $resulte['kd_sub_kegiatan'], 
                'nm_sub_kegiatan'       => $resulte['nm_sub_kegiatan']
                 );
            $ii++;
        }

        echo json_encode($result);
    }

function get_sub_kegiatan90all(){
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        $kegiatan           = $this->input->post('kode');
        $skpd           = $this->input->post('skpd');
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT kd_sub_kegiatan90,nm_sub_kegiatan90 from imapping where kd_kegiatan90 ='$kegiatan' and status='1' and kd_skpd90='$skpd' and  (upper(kd_sub_kegiatan90) like upper('%$lccr%') or upper(nm_sub_kegiatan90) like upper('%$lccr%')) 
                    GROUP BY kd_sub_kegiatan90,nm_sub_kegiatan90
                     order by kd_sub_kegiatan90";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_sub_kegiatan'       => $resulte['kd_sub_kegiatan90'], 
                'nm_sub_kegiatan'       => $resulte['nm_sub_kegiatan90']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



    function load_trskpd()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where="";
        if ($kriteria <> '')
        {
            $where = "where (upper(kd_skpd) like upper('%$kriteria%') or nm_skpd like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trskpd";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " SELECT TOP $rows * from trskpd where kd_gabungan not in (SELECT TOP  $offset kd_gabungan from trskpd) $where  order by kd_gabungan";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_skpd'           => $resulte['kd_skpd'], 
                'nm_skpd'           => $resulte['nm_skpd'],
                'kd_kegiatan'       => $resulte['kd_kegiatan'],
                'nm_kegiatan'       => $resulte['nm_kegiatan'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'tu_capaian'          => $resulte['tu_capai'],
                'tu_mas'            => $resulte['tu_mas'],
                'tu_kel'            => $resulte['tu_kel'],
                'tu_has'            => $resulte['tu_has'],
                'tk_capaian'          => $resulte['tk_capai'],
                'tk_kel'            => $resulte['tk_kel'],
                'tk_mas'            => $resulte['tk_mas'],
                'tk_has'            => $resulte['tk_has'],
                'lokasi'            => $resulte['lokasi'],
                'ang_lalu'          => $resulte['ang_lalu'],
                'ang_lalu1'         => $this->angka($resulte['ang_lalu']),
                'pagu1'             => $resulte['nilai_kua'],
                'pagu'              => $this->angka($resulte['nilai_kua'])
                
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

//validasi kegiatan
    function validasi_kegiatan()
    {
        $data['page_title'] = 'INPUT VALIDASI KEGIATAN';
        $this->template->set('title', 'INPUT VALIDASI KEGIATAN');
        $this->template->load('template', 'mapping/validasi', $data);
    }



     function load_validasi()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where="";
        if ($kriteria <> '')
        {
            $where = "and (upper(kd_skpd) like upper('%$kriteria%') or nm_skpd like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from ms_skpd";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " SELECT TOP $rows *, Case when validasi_kegiatan='1' then 'Sudah validasi' else 'Belum Validasi' end as status from ms_skpd where kd_skpd not in (SELECT TOP  $offset kd_skpd from ms_skpd) $where  order by kd_skpd";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_skpd'           => $resulte['kd_skpd'], 
                'nm_skpd'           => $resulte['nm_skpd'],
                'status'            => $resulte['status'],
                'validasi_kegiatan' => $resulte['validasi_kegiatan']
                
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function get_skpd()
    {
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');
        
       
        $sql = "SELECT kd_skpd,nm_skpd,validasi_kegiatan 
                FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) 
                     order by kd_skpd";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_skpd'           => $resulte['kd_skpd'], 
                'nm_skpd'           => $resulte['nm_skpd'],
                'validasi_kegiatan' => $resulte['validasi_kegiatan']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



     function simpan_indikator()
    {
        $tabel  = $this->input->post('tabel');
        $kode1   = $this->input->post('kode1');
        $kode2   = $this->input->post('kode2');
        $csql = $this->input->post('sql');

        $usernm = $this->session->userdata('pcNama');
        $update = date('y-m-d H:i:s');
        $msg = array();

        if ($tabel == 'trskpd')
        {

                // Simpan Detail //
                $sql = "delete from trskpd where kd_skpd='$kode1' and kd_kegiatan='$kode2' ";
                $asg = $this->db->query($sql);

                $sqldr = "delete from trskpd_rancang where kd_skpd='$kode1' and kd_kegiatan='$kode2' ";
                $asgdr = $this->db->query($sqldr);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    $sql = "insert into trskpd(kd_gabungan,kd_kegiatan,kd_program,kd_bidang_urusan,kd_skpd,nm_skpd,nm_kegiatan,nm_program,lokasi,sasaran_program,capaian_program,waktu_giat,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,ang_lalu,ang_ini,ang_depan,kd_sub_kegiatan,nm_sub_kegiatan,kel_sasaran_kegiatan,sub_keluaran,keterangan,tu_capai_p,tu_mas_p,tu_kel_p,tu_has_p,tk_capai_p,tk_mas_p,tk_kel_p,tk_has_p,status_sub_kegiatan,waktu_giat2)";

                    $asg = $this->db->query($sql . $csql);
                    if (!($asg))
                    {

                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                    } else
                    {
                         $sqlr = "insert into trskpd_rancang(kd_gabungan,kd_kegiatan,kd_program,kd_bidang_urusan,kd_skpd,nm_skpd,nm_kegiatan,nm_program,lokasi,sasaran_program,capaian_program,waktu_giat,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,ang_lalu,ang_ini,ang_depan,kd_sub_kegiatan,nm_sub_kegiatan,kel_sasaran_kegiatan,sub_keluaran,keterangan,tu_capai_p,tu_mas_p,tu_kel_p,tu_has_p,tk_capai_p,tk_mas_p,tk_kel_p,tk_has_p,status_sub_kegiatan,waktu_giat2)";

                    $asgr = $this->db->query($sqlr . $csql);

                        $sqlu = "UPDATE imapping SET status='1' where kd_skpd90='$kode1' and kd_sub_kegiatan90 in (SELECT kd_sub_kegiatan from trskpd where kd_skpd='$kode1') ";
                        $asgu = $this->db->query($sqlu);

                        $msg = array('pesan' => '1');
                        echo json_encode($msg);
                    }
                }
            }
    }



    function load_indikator()
    {
        $kodeskpd = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where="";
        if ($kriteria <> '')
        {
            $where = "and (upper(kd_kegiatan) like upper('%$kriteria%') or nm_kegiatan like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from load_indikator where kd_skpd='$kodeskpd'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " SELECT TOP $rows * from load_indikator where kd_kegiatan not in (SELECT TOP  $offset kd_kegiatan from load_indikator) and  kd_skpd='$kodeskpd' $where   order by kd_skpd,kd_kegiatan";


        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_skpd'           => $resulte['kd_skpd'], 
                'nm_skpd'           => $resulte['nm_skpd'],
                'kd_kegiatan'       => $resulte['kd_kegiatan'], 
                'nm_kegiatan'       => $resulte['nm_kegiatan'],
                'pagu'              => $resulte['pagu'],
                'kd_program'        => $resulte['kd_program'], 
                'kd_urusan'         => $resulte['kd_bidang_urusan'],
                'sasaran_program'   => $resulte['sasaran_program'],
                'capaian_program'   => $resulte['capaian_program'],
                
                'tu_capaian'        => $resulte['tu_capai'],
                'tu_mas'            => $resulte['tu_mas'],
                'tu_kel'            => $resulte['tu_kel'],
                'tu_has'            => $resulte['tu_has'],

                'tu_capaian_p'        => $resulte['tu_capai_p'],
                'tu_mas_p'            => $resulte['tu_mas_p'],
                'tu_kel_p'            => $resulte['tu_kel_p'],
                'tu_has_p'    => $resulte['tu_has_p'],
                
                'tk_capaian'=> $resulte['tk_capai'],
                'tk_mas'=> $resulte['tk_mas'],
                'tk_kel'=> $resulte['tk_kel'],
                'tk_has'=> $resulte['tk_has'],

                'tk_capaian_p'=> $resulte['tk_capai_p'],
                'tk_mas_p'=> $resulte['tk_mas_p'],
                'tk_kel_p'=> $resulte['tk_kel_p'],
                'tk_has_p'=> $resulte['tk_has_p'],
                
                'kel_sasaran_kegiatan'=> $resulte['kel_sasaran_kegiatan']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }




    function load_detail_indikator()
    {
        $nomor = $this->input->post('no');
        $nomor1 = $this->input->post('kode');
        $sql = "SELECT * FROM load_detail_indikator where kd_kegiatan='$nomor' and kd_skpd='$nomor1'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array(
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'], 
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'lokasi'            => $resulte['lokasi'], 
                'waktu'             => $resulte['waktu_giat'],
                'waktu_giat2'             => $resulte['waktu_giat2'],
                'sub_keluaran'      => $resulte['sub_keluaran'],
                'keterangan'        => $resulte['keterangan'],

                'ang_lalu'          => $resulte['ang_lalu'],
                'ang_ini'           => $resulte['ang_ini'],
                'ang_depan'         => $resulte['ang_depan'],
                'jml'               => $resulte['jml'],
                'statussub'         => $resulte['status_sub_kegiatan']
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function hapus_indikator()
    {
        $kode = $this->input->post('kode');
        $kode2 = $this->input->post('kode2');
        $msg = array();


        $sql2 = "SELECT count(*) as total from trskpd where kd_kegiatan='$kode' and status_sub_kegiatan='1'";
        $query2 = $this->db->query($sql2);
        $total2 = $query2->row();
        $result["total2"] = $total2->total;


        if ($result["total2"]==0){
            $sql = "delete from trskpd where kd_kegiatan='$kode' and kd_skpd='$kode2'";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                }else{
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);    
                }
        }else{

            $msg = array('pesan' => '2');
                        echo json_encode($msg);
                        exit();

        }


        
        
    }


    function hapus_rinci_indikator()
    {
        $kode = $this->input->post('kode');
        $kode2 = $this->input->post('kode2');
        $msg = array();


        $sql2 = "SELECT status_sub_kegiatan from trskpd where kd_sub_kegiatan='$kode' and kd_skpd='$kode2'";
        $query2 = $this->db->query($sql2);
        $total2 = $query2->row();
        $result["total2"] = $total2->status_sub_kegiatan;


        if ($result["total2"]==0 || $result["total2"]==''){

            $msg = array('pesan' => '1');
            echo json_encode($msg);
            exit();
            
        }else{

            $msg = array('pesan' => '2');
                        echo json_encode($msg);
                        exit();

        }


        
        
    }

    //validasi indikator

function validasi_indikator()
    {
        $data['page_title'] = 'INPUT VALIDASI KEGIATAN DAN INDIKATOR';
        $this->template->set('title', 'INPUT VALIDASI KEGIATAN DAN INDIKATOR');
        $this->template->load('template', 'mapping/validasi_indikator', $data);
    }


    function ambil_indikator()
    {
        $nomor = $this->input->post('no');
        $kodeskpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,ISNULL(kd_kegiatan, 0)as kd_kegiatan,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,tu_capai_p,
                tu_mas_p,tu_kel_p,tu_has_p,tk_capai_p,tk_mas_p,tk_kel_p,tk_has_p,kel_sasaran_kegiatan from (
                select a.kd_skpd,z.kd_kegiatan,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,tu_capai_p,
                tu_mas_p,tu_kel_p,tu_has_p,tk_capai_p,tk_mas_p,tk_kel_p,tk_has_p,kel_sasaran_kegiatan
                from ms_skpd a 
                LEFT JOIN 
                (SELECT kd_skpd,kd_kegiatan,nm_kegiatan,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,tu_capai_p,
                tu_mas_p,tu_kel_p,tu_has_p,tk_capai_p,tk_mas_p,tk_kel_p,tk_has_p,kel_sasaran_kegiatan FROM trskpd where kd_kegiatan='$nomor' 
                group by kd_skpd,kd_kegiatan,nm_kegiatan,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,
                tu_capai_p,tu_mas_p,tu_kel_p,tu_has_p,tk_capai_p,tk_mas_p,tk_kel_p,tk_has_p,kel_sasaran_kegiatan)z on z.kd_skpd=a.kd_skpd
                where a.kd_skpd='$kodeskpd'

                )zz";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array(
                'kd_kegiatan'       => $resulte['kd_kegiatan'], 

                'tu_capaian'        => $resulte['tu_capai'],
                'tu_mas'            => $resulte['tu_mas'],
                'tu_kel'            => $resulte['tu_kel'],
                'tu_has'            => $resulte['tu_has'],

                'tu_capaian_p'      => $resulte['tu_capai_p'],
                'tu_mas_p'          => $resulte['tu_mas_p'],
                'tu_kel_p'          => $resulte['tu_kel_p'],
                'tu_has_p'          => $resulte['tu_has_p'],
                
                'tk_capaian'        => $resulte['tk_capai'],
                'tk_mas'            => $resulte['tk_mas'],
                'tk_kel'            => $resulte['tk_kel'],
                'tk_has'            => $resulte['tk_has'],

                'tk_capaian_p'      => $resulte['tk_capai_p'],
                'tk_mas_p'          => $resulte['tk_mas_p'],
                'tk_kel_p'          => $resulte['tk_kel_p'],
                'tk_has_p'          => $resulte['tk_has_p'],
                
                'kel_sasaran_kegiatan'=> $resulte['kel_sasaran_kegiatan']
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function ambil_indikator_program()
    {
        $nomor = $this->input->post('no');
        $kodeskpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,ISNULL(kd_program, 0)as kd_program,capaian_program,sasaran_program from (
                select a.kd_skpd,z.kd_program,z.capaian_program,z.sasaran_program
                from ms_skpd a 
                LEFT JOIN 
                (SELECT 
                kd_skpd,kd_program,capaian_program,sasaran_program
                FROM trskpd 
                where kd_program='$nomor' group by kd_skpd,kd_program,capaian_program,sasaran_program)z on z.kd_skpd=a.kd_skpd
                where a.kd_skpd='$kodeskpd' )zz";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array(
                'kd_program'       => $resulte['kd_program'], 

                'capaian_program'        => $resulte['capaian_program'],
                'sasaran_program'            => $resulte['sasaran_program'],
                
                );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }



    function load_indikator_v()
    {
        $kodeskpd = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where="";
        if ($kriteria <> '')
        {
            $where = "where (upper(kd_kegiatan) like upper('%$kriteria%') or nm_kegiatan like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from load_indikator_v where kd_skpd='$kodeskpd'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " SELECT TOP $rows * from load_indikator_v where kd_kegiatan not in (SELECT TOP  $offset kd_kegiatan from load_indikator_v) $where  order by kd_skpd,kd_kegiatan";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_skpd'           => $resulte['kd_skpd'], 
                'nm_skpd'           => $resulte['nm_skpd'],
                'kd_kegiatan'       => $resulte['kd_kegiatan'], 
                'nm_kegiatan'       => $resulte['nm_kegiatan'],
                'pagu'              => $resulte['pagu'],
                'kd_program'        => $resulte['kd_program'], 
                'kd_urusan'         => $resulte['kd_bidang_urusan'],
                'sasaran_program'   => $resulte['sasaran_program'],
                'capaian_program'   => $resulte['capaian_program'],
                
                'tu_capaian'        => $resulte['tu_capai'],
                'tu_mas'            => $resulte['tu_mas'],
                'tu_kel'            => $resulte['tu_kel'],
                'tu_has'            => $resulte['tu_has'],

                'tu_capaian_p'        => $resulte['tu_capai_p'],
                'tu_mas_p'            => $resulte['tu_mas_p'],
                'tu_kel_p'            => $resulte['tu_kel_p'],
                'tu_has_p'    => $resulte['tu_has_p'],
                
                'tk_capaian'=> $resulte['tk_capai'],
                'tk_mas'=> $resulte['tk_mas'],
                'tk_kel'=> $resulte['tk_kel'],
                'tk_has'=> $resulte['tk_has'],

                'tk_capaian_p'=> $resulte['tk_capai_p'],
                'tk_mas_p'=> $resulte['tk_mas_p'],
                'tk_kel_p'=> $resulte['tk_kel_p'],
                'tk_has_p'=> $resulte['tk_has_p'],
                
                'statussub'=> $resulte['status_sub_kegiatan'],
                'kel_sasaran_kegiatan'=> $resulte['kel_sasaran_kegiatan']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }


     function skpd_mapping_v()
    {
        $id  = $this->session->userdata('pcUser'); 
        $kodeskpd      = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $where="";
        if ($lccr<>''){
            $where="AND (upper(a.kd_skpd90) like upper('%$lccr%') or upper(a.nm_skpd90) like upper('%$lccr%')) ";
        }
        
       
        $sql = "SELECT a.kd_skpd90 as kd_skpd,a.nm_skpd90 as nm_skpd,validasi_kegiatan
                FROM imapping a inner join ms_skpd b on a.kd_skpd90=b.kd_skpd where (validasi_kegiatan ='' OR validasi_kegiatan IS NULL)   $where
                     group by kd_skpd90,nm_skpd90,validasi_kegiatan
                     order by kd_skpd90";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_skpd' => $resulte['kd_skpd'], 
                'nm_skpd' => $resulte['nm_skpd']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



    function get_kegiatan90_v(){
        $id             = $this->session->userdata('pcUser'); 
        $usernm         = $this->session->userdata('pcNama');
        $skpd           = $this->input->post('kode');
        $lccr           = $this->input->post('q'); 

        $sql = "SELECT kd_kegiatan90,nm_kegiatan90 from imapping where kd_skpd90 ='$skpd' and kd_kegiatan90 in (select kd_kegiatan from trskpd where kd_skpd='$skpd' and status_sub_kegiatan in ('0','1')) and  (upper(kd_kegiatan90) like upper('%$lccr%') or upper(nm_kegiatan90) like upper('%$lccr%')) 
                    GROUP BY kd_kegiatan90,nm_kegiatan90
                     order by kd_kegiatan90";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_kegiatan'       => $resulte['kd_kegiatan90'], 
                'nm_kegiatan'       => $resulte['nm_kegiatan90']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


    function terima_tolak(){

        $usernm      = $this->session->userdata('pcNama');
        
        $tabel   = $this->input->post('tabel');
        $st   = $this->input->post('st');
        
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');

        $cid2     = $this->input->post('cid2');
        $lcid2    = $this->input->post('lcid2');

        $cid3     = $this->input->post('cid3');
        $lcid3    = $this->input->post('lcid3');

            
           $sql     = "select $cid from $tabel where $cid='$lcid' AND $cid2='$lcid2' AND $cid3='$lcid3'";
           $res     = $this->db->query($sql);
           if ( $res->num_rows()==0 ) {
                echo '1';
                exit();
           }else{

                if ($st=='terima'){
                    $query   = "UPDATE trskpd SET status_sub_kegiatan='1' , user_setuju='$usernm' where kd_skpd='$lcid' AND kd_kegiatan='$lcid2' AND kd_program='$lcid3'";
                            $asg     = $this->db->query($query);

                            $queryr   = "UPDATE trskpd_rancang SET status_sub_kegiatan='1' , user_setuju='$usernm' where kd_skpd='$lcid' AND kd_kegiatan='$lcid2' AND kd_program='$lcid3'";
                            $asgr     = $this->db->query($queryr);
                            if ( $asg > 0 ){
                                echo '2';
                            } else {
                                echo '0';
                            }
                }else{

                    $query   = "UPDATE trskpd SET status_sub_kegiatan='0' , user_setuju='$usernm' where kd_skpd='$lcid' AND kd_kegiatan='$lcid2' AND kd_program='$lcid3'";
                            $asg     = $this->db->query($query);

                            $queryr   = "UPDATE trskpd SET status_sub_kegiatan='0' , user_setuju='$usernm' where kd_skpd='$lcid' AND kd_kegiatan='$lcid2' AND kd_program='$lcid3'";
                            $asgr     = $this->db->query($queryr);
                            if ( $asg > 0 ){
                                echo '2';
                            } else {
                                echo '0';
                            }

                }

                

           } 
        
        
        
    
    }

    function terima_tolak1(){

        $usernm      = $this->session->userdata('pcNama');
        
        $tabel   = $this->input->post('tabel');
        $st   = $this->input->post('st');
        
        $cid     = $this->input->post('cid');
        $lcid    = $this->input->post('lcid');

        $cid2     = $this->input->post('cid2');
        $lcid2    = $this->input->post('lcid2');

        $cid3     = $this->input->post('cid3');
        $lcid3    = $this->input->post('lcid3');

        $cid4     = $this->input->post('cid4');
        $lcid4    = $this->input->post('lcid4');

            
           $sql     = "select $cid from $tabel where $cid='$lcid' AND $cid2='$lcid2' AND $cid3='$lcid3' AND $cid4='$lcid4'";
           $res     = $this->db->query($sql);
           if ( $res->num_rows()==0 ) {
                echo '1';
                exit();
           }else{

                if ($st=='terima'){
                    $query   = "UPDATE trskpd SET status_sub_kegiatan='1' , user_setuju='$usernm' where kd_skpd='$lcid' AND kd_kegiatan='$lcid2' AND kd_program='$lcid3' AND kd_sub_kegiatan='$lcid4'";
                            $asg     = $this->db->query($query);
                            if ( $asg > 0 ){
                                echo '2';
                            } else {
                                echo '0';
                            }
                }else{

                    $query   = "UPDATE trskpd SET status_sub_kegiatan='0' , user_setuju='$usernm' where kd_skpd='$lcid' AND kd_kegiatan='$lcid2' AND kd_program='$lcid3' AND kd_sub_kegiatan='$lcid4'";
                            $asg     = $this->db->query($query);
                            if ( $asg > 0 ){
                                echo '2';
                            } else {
                                echo '0';
                            }

                }

                

           } 
        
        
        
    
    }

    ////mapping rekening
    function map_rekening()
    {
        $data['page_title'] = 'INPUT MAPPING REKENING';
        $this->template->set('title', 'INPUT MAPPING REKENING');
        $this->template->load('template', 'mapping/map_rekening', $data);
    }



    function get_rekening13()
    {
         if ($this->input->post('q')==''){
            $lccr = 'xxxxxx';
        }else{
            $lccr = $this->input->post('q');    
        }

        $sql = "SELECT * FROM ambil_rekening13 where left(kd_rek13,1) in ('4','5','6') and (upper(kd_rek13) like upper('%$lccr%') or upper(nm_rek13) like upper('%$lccr%')) order by kd_rek13";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_rek13' => $resulte['kd_rek13'], 
                'nm_rek13' => $resulte['nm_rek13'],
                'kd_rek64' => $resulte['kd_rek64'],
                'nm_rek64' => $resulte['nm_rek64']
                 );
            $ii++;
        }

        echo json_encode($result);
    }




    function get_rekening90()
    {
        // if ($this->input->post('q')==''){
        //     $lccr = 'xxxxxx';
        // }else{
            $lccr = $this->input->post('q'); 
            $kode = $this->input->post('kode');    
        // }

        $sql = "SELECT * FROM ambil_rekening90 where left(kd_rek6,1) = LEFT($kode,1) and (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) order by kd_rek6";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_rek6' => $resulte['kd_rek6'], 
                'nm_rek6' => $resulte['nm_rek6']
                 );
            $ii++;
        }

        echo json_encode($result);
    }



     function get_rekening90lo()
    {
        // if ($this->input->post('q')==''){
        //     $lccr = 'xxxxxx';
        // }else{
            $lccr = $this->input->post('q'); 
            $kode = $this->input->post('kode');    
        // }

        $sql = "SELECT * FROM ambil_rekening90 where left(kd_rek6,1) in ('8','9') and (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) order by kd_rek6";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_rek6' => $resulte['kd_rek6'], 
                'nm_rek6' => $resulte['nm_rek6']
                 );
            $ii++;
        }

        echo json_encode($result);
    }


     function get_rekening90piu()
    {
        // if ($this->input->post('q')==''){
        //     $lccr = 'xxxxxx';
        // }else{
            $lccr = $this->input->post('q'); 
            $kode = $this->input->post('kode');    
        // }

        $sql = "SELECT * FROM ambil_rekening90 where left(kd_rek6,1) in ('2','1') AND nm_rek6 like ('%utang%') and (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) order by kd_rek6";
       
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_rek6' => $resulte['kd_rek6'], 
                'nm_rek6' => $resulte['nm_rek6']
                 );
            $ii++;
        }

        echo json_encode($result);
    }
function load_mapping_rekening()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where="";
        if ($kriteria <> '')
        {
            $where = "and (upper(kd_rek13) like upper('%$kriteria%') or nm_rek13 like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from load_skpd";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " 
            SELECT TOP $rows * from load_mapping_rekening where kd_rek13 not in (SELECT TOP  $offset kd_rek13 from load_mapping_rekening) $where  order by kd_rek13
        ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array(
                'kd_rek13'   => $resulte['kd_rek13'], 
                'nm_rek13'   => $resulte['nm_rek13'],
                'kd_rek6'    => $resulte['kd_rek6'],
                'nm_rek6'    => $resulte['nm_rek6']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }


    function simpan_map_rekening()
    {
        $tabel  = $this->input->post('tabel');
        $csql = $this->input->post('sql');
        $csql2 = $this->input->post('sql2');
        $usernm = $this->session->userdata('pcNama');
        $update = date('y-m-d H:i:s');
        $msg = array();

        if ($tabel == 'ms_rekening')
        {

                // Simpan Detail //
                $sql = "delete from ms_rekening where $csql2";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    $sql = "insert into ms_rekening";

                    $asg = $this->db->query($sql . $csql);
                    if (!($asg))
                    {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                    } else
                    {

                        $msg = array('pesan' => '1');
                        echo json_encode($msg);
                    }
                }
            }
    }
      

      function load_detail_mapping_rekening() {
       
        $norek  = $this->input->post('rekening') ;

        $sql    = "SELECT * from load_detail_mapping_rekening where kd_rek13='$norek'";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id'            => $ii,        
                        'kd_rek13'       => $resulte['kd_rek13'],
                        'nm_rek13'       => $resulte['nm_rek13'],
                        'kd_rek64'       => $resulte['kd_rek64'],
                        'nm_rek64'       => $resulte['nm_rek64'],
                        'kd_rek90'       => $resulte['kd_rek6'],
                        'nm_rek90'       => $resulte['nm_rek6'],
                        'kd_rek90_lo'    => $resulte['map_lo'],
                        'kd_rek90_piu'   => $resulte['piutang_utang'],
                        
                        );
                        $ii++;
        }
        echo json_encode($result);
    }


}
