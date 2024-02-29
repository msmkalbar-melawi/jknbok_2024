
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


use mikehaertl\wkhtmlto\Pdf;

require_once('application/3rdparty/wkhtmltopdf/Pdf.php');

class JurnalUmumControllerBok extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    //================================================= Input Jurnal Umum
    function index()
    {
        $data['page_title'] = 'INPUT JURNAL UMUM';
        $this->template->set('title', 'INPUT JURNAL UMUM');
        $this->template->load('template', 'bok/jurnalumum/index', $data);
    }

    function config_skpd(){
        $skpd = $this->session->userdata('kdskpd');

        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd_jkn WHERE kd_skpd = ? ";
        $data = $this->db->query($sql,[$skpd])->row();  

        // $test = $query1->num_rows();

        // $ii = 0;
        // foreach($query1->result_array() as $resulte)
        // { 
        //     $result = array(
        //                 'id' => $ii,        
        //                 'kd_skpd' => $resulte['kd_skpd'],
        //                 'nm_skpd' => $resulte['nm_skpd']
        //                 );
        //                 $ii++;
        // }
        echo json_encode($data);
        // $query1->free_result();   
    }


    function load_ju()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = "AND tabel='1'";
        if ($kriteria <> '') {
            $where = "AND tabel='1' and (upper(no_voucher) like upper('%$kriteria%') or tgl_voucher like '%$kriteria%' or upper(ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhju_pkd WHERE kd_skpd = '$skpd' $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " SELECT top $rows * from trhju_pkd  WHERE kd_skpd = '$skpd' $where and no_voucher not in (select top $offset no_voucher from trhju_pkd  WHERE kd_skpd = '$skpd' $where order by tgl_voucher,no_voucher,kd_skpd) order by tgl_voucher,no_voucher,kd_skpd "; //limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'no_voucher'      => $resulte['no_voucher'],
                'tgl_voucher'     => $resulte['tgl_voucher'],
                'kd_skpd'         => $resulte['kd_skpd'],
                'nm_skpd'         => $resulte['nm_skpd'],
                'ket'             => trim($resulte['ket']),
                'reev'            => trim($resulte['reev']),
                'tgl_real'        => trim($resulte['tgl_real']),
                'total_d'         => $resulte['total_d'],
                'total_k'         => $resulte['total_k'],
                'kd_skpd_mutasi'  => $resulte['kd_skpd_mutasi'],
                'nm_skpd_mutasi'  => $resulte['nm_skpd_mutasi']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function skpd()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],

            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_ju_trskpd()
    {
        $jenis = $this->input->post('jenis');
        $len = strlen($jenis);
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');

        $jns_beban = '';
        $cgiat = '';
        if ($jenis != '') {
            $jns_beban = "and left(a.kd_rek6,$len)='$jenis'";
        }
        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        // $sql = "SELECT distinct a.kd_sub_kegiatan,a.nm_sub_kegiatan,'' kd_program, '' as nm_program, 0 total FROM bok_trdrka a
        //         WHERE a.kd_skpd='$cskpd' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%')))";

        $sql = "SELECT distinct a.kd_sub_kegiatan,a.nm_sub_kegiatan,'' kd_program, '' as nm_program, 0 total FROM bok_trdrka a WHERE a.kd_skpd='$cskpd' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%%'))";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'total'       => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }
    function load_ju_rek()
    {
        //$jenis = $this->uri->segment(3);
        $jenis = $this->input->post('jenis');
        $len = strlen($jenis);
        $giat = $this->input->post('giat');
        $kode = $this->input->post('kd');
        $rek = $this->input->post('rek');
        $lccr = $this->input->post('q');


        if ($rek != '') {
            //if($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){
            //$notIn = " and kd_rek4 not in ($rek) " ;
            //}else{
            $notIn = " and a.kd_rek6 not in ('$rek') ";
            //}
        } else {
            $notIn = "";
        }
        //echo $jenis;

        if ($jenis == '4' || $jenis == '5' || $jenis == '6' || $jenis == '7') {
            $sql = "SELECT (select kd_rek6 from ms_rek6 where kd_rek6=a.kd_rek6) as kd_rek6,a.nm_rek6 FROM bok_trdrka a WHERE a.kd_sub_kegiatan= '$giat' AND kd_skpd = '$kode' $notIn AND ( upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%'))
            group by kd_rek6,nm_rek6 order by kd_rek6";
        } else {
            //if ($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){

            //$sql = "SELECT  kd_rek5,nm_rek4 as nm_rek5 FROM ms_rek4 where left(kd_rek4,$len)='$jenis' $notIn";
            //}else{

            $sql = "SELECT kd_rek6 as kd_rek6,a.nm_rek6 FROM ms_rek6 a where left(a.kd_rek6,$len)='$jenis' $notIn AND ( upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%'))
            group by kd_rek6,nm_rek6 order by kd_rek6";

            //}
        }
        $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'kd_rek6' => $resulte['kd_rek6'],
                'kd_rek_ang' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_ju_rek_lama()
    {
        //$jenis = $this->uri->segment(3);
        $jenis  = $this->input->post('jenis');
        $len    = strlen($jenis);
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');



        if ($rek != '') {
            //if($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){
            //$notIn = " and kd_rek4 not in ($rek) " ;
            //}else{
            $notIn = " and a.kd_rek6 not in ('$rek') ";
            //}
        } else {
            $notIn  = "";
        }
        //echo $jenis;

        if ($jenis == '5'  ||  $jenis == '7') {
            $sql = "SELECT a.kd_rek6,a.nm_rek6 FROM trdrka a 
			INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
			WHERE a.kd_sub_kegiatan= '$giat' 
			AND a.kd_skpd = '$kode' $notIn AND ( upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) order by kd_rek6";
        } else if ($jenis == '4') {
            $sql = "SELECT a.kd_rek6,a.nm_rek6 FROM trdrka_pend a 
			INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
			WHERE a.kd_sub_kegiatan= '$giat' 
			AND a.kd_skpd = '$kode' $notIn AND ( upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) order by kd_rek6";
        } else if ($jenis == '0') {
            $sql = "SELECT top 1 '000000000000' as kd_rek6,'Perubahan SAL' as nm_rek6 FROM ms_rek6 a 
					where  ( upper(kd_rek6) like upper('%%') or upper(nm_rek6) like upper('%%')) order by kd_rek6";
        } else {
            //if ($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){

            //$sql = "SELECT  kd_rek6,nm_rek4 as nm_rek6 FROM ms_rek4 where left(kd_rek4,$len)='$jenis' $notIn";  
            //}else{

            $sql = "SELECT kd_rek6,nm_rek6 FROM ms_rek6 a where left(kd_rek6,$len)='$jenis' $notIn AND ( upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) order by kd_rek6";
            //}
        }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'kd_rek6' => $resulte['kd_rek6'],
                'kd_rek_ang' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dju()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $sql = "SELECT a.no_voucher,b.kd_sub_kegiatan,b.nm_sub_kegiatan,b.kd_rek6,b.map_real,case when rk='D' then b.nm_rek6 else SPACE(4)+b.nm_rek6 end AS nm_rek6,b.debet,b.kredit,b.rk,b.jns,b.pos FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
		WHERE a.no_voucher='$nomor' AND a.kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'no_voucher'  => $resulte['no_voucher'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_rek6'     => $resulte['kd_rek6'],
                'kd_rek_ang'     => $resulte['map_real'],
                'nm_rek6'     => $resulte['nm_rek6'],
                'debet'       => $resulte['debet'],
                'kredit'      => $resulte['kredit'],
                'rk'          => $resulte['rk'],
                'jns'         => $resulte['jns'],
                'post'         => $resulte['pos']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function hapus_ju()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdju_pkd where no_voucher='$nomor' AND kd_unit='$skpd'";
        $asg = $this->db->query($sql);
        if ($asg) {
            $sql = "delete from trhju_pkd where no_voucher='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan' => '1');
        echo json_encode($msg);
    }

    function simpan_ju()
    {
        $tabel      = $this->input->post('tabel');
        $nomor      = $this->input->post('no');
        $tgl        = $this->input->post('tgl');
        $skpd       = $this->input->post('skpd');
        $nmskpd     = $this->input->post('nmskpd');
        $ket        = $this->input->post('ket');
        $reev       = $this->input->post('reev');
        $tgl_real   = $this->input->post('tgl_real');
        $total_d     = $this->input->post('total_d');
        $total_k     = $this->input->post('total_k');
        $csql        = $this->input->post('sql');
        $ket_mutasi1 = $this->input->post('ket_mutasi1');
        $ket_mutasi2 = $this->input->post('ket_mutasi2');
        $skpd_mutasi  = $this->input->post('skpd_mutasi');
        $nmskpd_mutasi = $this->input->post('nmskpd_mutasi');

        $usernm     = $this->session->userdata('pcNama');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();
        if ($tabel == 'trhju_pkd') {
            $sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            $sql = "delete from trdju_pkd where no_voucher='$nomor' and kd_unit='$skpd'";
            $asg = $this->db->query($sql);
            if ($asg) {
                $sql = "INSERT into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel,reev,tgl_real,kd_skpd_mutasi,nm_skpd_mutasi) 
				values('$nomor','$tgl','$ket_mutasi1'+'$nmskpd_mutasi'+'$ket_mutasi2'+'$ket','$usernm','$update','$skpd','$nmskpd','$total_d','$total_k','1','$reev','$tgl_real','$skpd_mutasi','$nmskpd_mutasi')";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } else if ($tabel == 'trdju_pkd') {

            // Simpan Detail //                       
            $sql = "delete from trdju_pkd where no_voucher='$nomor' and kd_unit='$skpd'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,kd_unit,pos,urut,map_real)";

                $asg = $this->db->query($sql . $csql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function simpan_ju_edit()
    {
        $tabel  = $this->input->post('tabel');
        $nomor  = $this->input->post('no');
        $no_bku  = $this->input->post('no_bku');
        $tgl    = $this->input->post('tgl');
        $skpd   = $this->input->post('skpd');
        $nmskpd = $this->input->post('nmskpd');
        $ket    = $this->input->post('ket');
        $reev   = $this->input->post('reev');
        $tgl_real   = $this->input->post('tgl_real');
        $total_d = $this->input->post('total_d');
        $total_k = $this->input->post('total_k');
        $csql    = $this->input->post('sql');
        $skpd_mutasi  = $this->input->post('skpd_mutasi');
        $nmskpd_mutasi = $this->input->post('nmskpd_mutasi');


        $usernm     = $this->session->userdata('pcNama');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        if ($tabel == 'trhju_pkd') {
            $sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$no_bku'";
            $asg = $this->db->query($sql);
            $sql = "delete from trdju_pkd where no_voucher='$no_bku' and kd_unit='$skpd'";
            $asg = $this->db->query($sql);
            if ($asg) {
                $sql = "INSERT into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel,reev,tgl_real,kd_skpd_mutasi,nm_skpd_mutasi) 
									values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total_d','$total_k','1','$reev','$tgl_real','$skpd_mutasi',
											'$nmskpd_mutasi')";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } else if ($tabel == 'trdju_pkd') {

            // Simpan Detail //                       
            $sql = "delete from trdju_pkd where no_voucher='$no_bku' and kd_unit='$skpd'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,kd_unit,pos,urut,map_real)";

                $asg = $this->db->query($sql . $csql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    //================================================= End Input Jurnal Umum

    //================================================= Cetak Jurnal Umum
    function jur_umum()
    {
        $data['page_title'] = 'JURNAL UMUM';
        $this->template->set('title', 'JURNAL UMUM');
        $this->template->load('template', 'akuntansi/jur_umum', $data);
    }

    function ctk_jurum($dcetak = '', $dcetak2 = '', $skpd = '')
    {
        $csql11 = " SELECT nm_skpd from ms_skpd where kd_skpd = '$skpd'";
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper($trh1->nm_skpd);
        $tgl = $this->tukd_model->tanggal_format_indonesia($dcetak);
        $tgl2 = $this->tukd_model->tanggal_format_indonesia($dcetak2);

        $cRet = "";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
     <tr>
         <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>$lcskpd
         </td>
     </tr>
      <tr>
         <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>JURNAL UMUM
         </td>
     </tr>
     <tr>
         <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">PERIODE $tgl S.D $tgl2
         </td>
     </tr>
     </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"90%\" align=\"center\" border=\"2\" cellspacing=\"0\" cellpadding=\"4\">
     <thead>
     <tr>
         <td align=\"center\"bgcolor=\"#CCCCCC\" rowspan=\"2\">Tanggal</td>
         <td align=\"center\" bgcolor=\"#CCCCCC\"rowspan=\"2\">Nomor<br>Bukti</td>
         <td colspan=\"5\"bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\">Kode<br>Rekening</td>
         <td align=\"center\"bgcolor=\"#CCCCCC\" rowspan=\"2\">Uraian</td>
         <td align=\"center\"bgcolor=\"#CCCCCC\" rowspan=\"2\">ref</td>
         <td align=\"center\"bgcolor=\"#CCCCCC\" colspan=\"2\">Jumlah Rp</td>
     </tr>
     <tr>
         <td align=\"center\" bgcolor=\"#CCCCCC\">Debit</td>
         <td align=\"center\"bgcolor=\"#CCCCCC\">Kredit</td>
     </tr>
     <tr>
         <td align=\"center\" width=\"15%\";border-bottom:solid 1px red;\">1</td>
         <td align=\"center\" width=\"10%\";border-bottom:solid 1px blue;\">2</td>
         <td colspan=\"5\" align=\"center\" width=\"15%\">3</td>
         <td align=\"center\" width=\"42%\">4</td>
         <td align=\"center\" width=\"3%\"></td>
         <td align=\"center\" width=\"10%\">5</td>
         <td align=\"center\" width=\"10%\">6</td>
     </tr>
     </thead>
    ";

        $csql1 = "SELECT count(*) as tot FROM 
          trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher and a.kd_unit=b.kd_skpd 
          where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'";
        $rs = $this->db->query($csql1);
        $trh = $rs->row();


        /*         $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek5,(c.nm_rek64 + case when (pos='0') then '' else ''end) AS nm_rek5,a.debet,a.kredit FROM 
           trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher join (SELECT kd_rek64,nm_Rek64 from ms_rek5 group by kd_rek64,nm_Rek64) c on a.kd_rek5=c.kd_rek64
           where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
           ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek5";   
        */
        $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek6, nm_rek6,a.debet,a.kredit,a.rk from trdju_pkd a join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
         where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
           ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek6";

        $query = $this->db->query($csql);
        $cnovoc = '';
        $lcno = 0;
        foreach ($query->result_array() as $res) {
            $lcno = $lcno + 1;
            if ($lcno == $trh->tot) {
                $cRet .= "<tr>
                         <td style=\"border-bottom:none;border-top:none;\"></td>
                         <td style=\"border-bottom:none;border-top:none;\"></td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 3, 2) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 5, 2) . "</td>
                         <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                         <td style=\"border-bottom:none;\"></td>";
                if ($res['rk'] == 'K') {
                    $cRet .= " <td style=\"border-bottom:none;\"></td>
                                     <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['kredit'], "2", ",", ".") . "</td>";
                } else {
                    $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet'], "2", ",", ".") . "</td>
                                        <td style=\"border-bottom:none;\"></td>";
                }

                $cRet .= "</tr>";
            } else {
                if ($cnovoc == $res['no_voucher']) {
                    $cRet .= "<tr>
                         <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                         <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 3, 2) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 5, 2) . "</td>
                         <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                         <td style=\"border-bottom:none;\"></td>";
                    if ($res['rk'] == 'K') {
                        $cRet .= " <td style=\"border-bottom:none;\"></td>
                                     <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['kredit'], "2", ",", ".") . "</td>";
                    } else {
                        $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet'], "2", ",", ".") . "</td>
                                        <td style=\"border-bottom:none;\"></td>";
                    }

                    $cRet .= "</tr>";
                } else {
                    $cRet .= "<tr>
                         <td style=\"border-bottom:none\">" . $this->tukd_model->tanggal_ind($res['tgl_voucher']) . "</td>
                         <td style=\"border-bottom:none\">" . $res['no_voucher'] . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 1) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 3, 2) . "</td>
                         <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 5, 2) . "</td>
                         <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                         <td style=\"border-bottom:none;\"></td>";
                    if ($res['rk'] == 'K') {
                        $cRet .= " <td style=\"border-bottom:none;\"></td>
                                     <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['kredit'], "2", ",", ".") . "</td>";
                    } else {
                        $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet'], "2", ",", ".") . "</td>
                                        <td style=\"border-bottom:none;\"></td>";
                    }

                    $cRet .= "</tr>";
                }
                $cnovoc = $res['no_voucher'];
            }
        }

        $cRet .= " <tr>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
                 <td style=\"border-top:none\"></td>
             </tr>  
            </table>";

        $data['prev'] = $cRet; //'JURNAL UMUM';
        echo $cRet;
        //$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	

    }


    //================================================= Cetak Jurnal Umum

    //================================================= Buku Besar
    function bukubesar()
    {

        $data['page_title'] = 'CETAK BUKU BESAR';
        $this->template->set('title', 'BUKU BESAR');
        $this->template->load('template', 'akuntansi/bukubesar', $data);
    }

    function cetakbb($dcetak = '', $ttd = '', $skpd = '', $rek6 = '', $dcetak2 = '', $jenis = '')
    { //ANgoez
        $thn_ang = $this->session->userdata('pcThang');
        $cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" ><B>BUKU BESAR </B></TD>
					</TR>
					</TABLE>';

        $cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="80%" >: ' . $skpd . ' ' . $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Rekening</TD>
						<TD align="left" width="80%" >: ' . $rek6 . ' ' . $this->tukd_model->get_nama($rek6, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Periode</TD>
						<TD align="left" width="80%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
					 </TABLE>';

        $cRet .= '<TABLE style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
					 <THEAD>
					 <TR>
						<TD width="15%"  bgcolor="#CCCCCC" align="center" >TANGGAL</TD>
						<TD width="35%" bgcolor="#CCCCCC" align="center" >URAIAN</TD>
						<TD width="5%" bgcolor="#CCCCCC" align="center" >REF</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >DEBET</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >KREDIT</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >SALDO</TD>
					 </TR>
					 </THEAD>';
        if ((substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '7') or (substr($rek6, 0, 1) == '4') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 1) == '6') or ($rek6 == '310101010001')) {
            $csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher < '$dcetak'   AND YEAR(b.tgl_voucher)='$thn_ang'";
        } else {
            $csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher < '$dcetak'   ";
        }

        $hasil = $this->db->query($csql3);
        $trh4 = $hasil->row();
        $awaldebet = $trh4->debet;
        $awalkredit = $trh4->kredit;
        if ((substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 1) == '1')) {
            $saldo = $awaldebet - $awalkredit;
        } else {
            $saldo = $awalkredit - $awaldebet;
        }
        if ($saldo < 0) {
            $a = '(';
            $b = ')';
        } else {
            $a = '';
            $b = '';
        }
        $cRet .= '<TR>
								<TD width="15%" align="left" ></TD>
								<TD width="35%" align="left" >saldo awal</TD>
								<TD width="5%" align="left" ></TD>
								<TD width="15%" align="right" ></TD>
								<TD width="15%" align="right" ></TD>
								<TD width="15%" align="right" >' . $a . '' . number_format($saldo, "2", ",", ".") . '' . $b . '</TD>
							 </TR>';

        $idx = 1;

        $query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2'  ORDER BY b.tgl_voucher, 
										   case when left('$rek6',1) in (1,5,8) then kredit-debet else debet-kredit end");

        /*$query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2'  ORDER BY b.tgl_voucher, 
										   case when left('$rek6',1) in (1,5,6,9) then kredit-debet else debet-kredit end");*/

        //$query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher>='$dcetak' and b.tgl_voucher<='$dcetak2' and a.pos='1' ORDER by b.tgl_voucher, convert(b.no_voucher,unsigned)");  
        if ($query->num_rows() > 0) {
            $jdebet = 0;
            $jkredit = 0;
            foreach ($query->result_array() as $res) {

                $tgl_voucher = $res['tgl_voucher'];
                $ket = $res['ket'];
                $ref = $res['no_voucher'];
                $debet = $res['debet'];
                $kredit = $res['kredit'];
                $idx++;
                if ($debet < 0) {
                    $debet1 = $debet * -1;
                    $c = '(';
                    $d = ')';
                } else {
                    $c = '';
                    $d = '';
                    $debet1 = $debet;
                }
                if ($kredit < 0) {
                    $kredit1 = $kredit * -1;
                    $e = '(';
                    $f = ')';
                } else {
                    $e = '';
                    $f = '';
                    $kredit1 = $kredit;
                }
                $saldo = $saldo;
                if ((substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 1) == '1')) {
                    $saldo = $saldo + $debet - $kredit;
                } else {
                    $saldo = $saldo + $kredit - $debet;
                }
                if ($saldo < 0) {
                    $saldo1 = $saldo * -1;
                    $i = '(';
                    $j = ')';
                } else {
                    $saldo1 = $saldo;
                    $i = '';
                    $j = '';
                }
                $cRet .= '<TR>
								<TD width="15%" align="left" >' . $this->tukd_model->tanggal_format_indonesia($tgl_voucher) . '</TD>
								<TD width="35%" align="left" >' . $ket . '</TD>
								<TD width="5%" align="left" >' . $ref . '</TD>
								<TD width="15%" align="right" >' . $c . '' . number_format($debet1, "2", ",", ".") . '' . $d . '</TD>
								<TD width="15%" align="right" >' . $e . '' . number_format($kredit1, "2", ",", ".") . '' . $f . '</TD>
								<TD width="15%" align="right" >' . $i . '' . number_format($saldo1, "2", ",", ".") . '' . $j . '</TD>
							 </TR>';

                $jdebet = $jdebet + $debet;
                $jkredit = $jkredit + $kredit;
            }
            if ($jdebet < 0) {
                $jdebet1 = $jdebet * -1;
                $k = '(';
                $l = ')';
            } else {
                $jdebet1 = $jdebet;
                $k = '';
                $l = '';
            }
            if ($jkredit < 0) {
                $jkredit1 = $jkredit * -1;
                $m = '(';
                $n = ')';
            } else {
                $jkredit1 = $jkredit;
                $m = '';
                $n = '';
            }

            $cRet .= '<TR>
					<TD width="15%" align="left" ></TD>
					<TD width="35%" align="left" >JUMLAH</TD>
					<TD width="5%" align="left" ></TD>
					<TD width="15%" align="right" >' . $k . '' . number_format($jdebet1, "2", ",", ".") . '' . $l . '</TD>
					<TD width="15%" align="right" >' . $m . '' . number_format($jkredit1, "2", ",", ".") . '' . $n . '</TD>
					<TD width="15%" align="right" >' . $i . '' . number_format($saldo1, "2", ",", ".") . '' . $j . '</TD>
				 </TR>';
            $cRet .= '</TABLE>';
        } else {

            $cRet .= '</TABLE>';
        }

        if ($jenis == 1) {
            echo '<title> Buku Besar </title>';
            echo $cRet;
        }
        if ($jenis == 2) {
            $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
        }
    }

    function rekening()
    {
        $lccr = $this->input->post('q');
        $skpd     = $this->session->userdata('kdskpd');
        //        $sql = " SELECT kd_rek5,nm_rek5 FROM ms_rek5 where kd_rek5 like '$lccr%' limit 20";
        $sql = " SELECT DISTINCT isnull(kd_rek6,'') kd_rek6 , isnull(nm_rek6,'') nm_rek6 FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
				 where kd_skpd='$skpd' and (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) group by kd_rek6,nm_rek6 order by kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
            );
            $ii++;
        }

        echo json_encode($result);
    }
    //================================================= Buku Besar

    //================================================= LRA
    function cetak_lra2()
    {
        $data['page_title'] = 'LRA SKPD';
        $this->template->set('title', 'LRA SKPD');
        $this->template->load('template', 'akuntansi/cetak_lra', $data);
    }

    function cetak_lra($bulan = '', $ctk = '')
    {
        $lntahunang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $ttd1 = str_replace('a', ' ', $this->uri->segment(5));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(6));
        $tanggal_ttd =    $this->uri->segment(7);
        $anggaran =  $this->uri->segment(9);

        $modtahun = $lntahunang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        if ($kd_skpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kd_skpd'";
        }
        //=========
        $initang = "nilai_ang";

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id='$ttd1' ";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($ttd1 == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                //$jdl2 = 'MENGETAHUI :';
                $nip2 = $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);
        $sclient = $this->akuntansi_model->get_sclient();
        $cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"5\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
                        <tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>" . $nmskpd . "</b></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] TAHUN $lntahunang</b></td></tr>
						</TABLE>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
					<tr>
						<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
						<td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
						<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
					</tr>
					<tr>
						<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
						<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
						<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
						<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						</tr>
						<tr>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
					</tr>
					</thead> ";

        $sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where jns_ang='$anggaran' and bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_surplus = $row->ang_surplus;
            $nil_surplus = $row->nil_surplus;
        }
        $sisa_surplus = $ang_surplus - $nil_surplus;
        if (($ang_surplus == 0) || ($ang_surplus == '')) {
            $persen_surplus = 0;
        } else {
            $persen_surplus = $nil_surplus / $ang_surplus * 100;
        }
        $hasil->free_result();
        if ($ang_surplus < 0) {
            $ang_surplus1 = $ang_surplus * -1;
            $a = '(';
            $b = ')';
        } else {
            $ang_surplus1 = $ang_surplus;
            $a = '';
            $b = '';
        }
        if ($nil_surplus < 0) {
            $nil_surplus1 = $nil_surplus * -1;
            $c = '(';
            $d = ')';
        } else {
            $nil_surplus1 = $nil_surplus;
            $c = '';
            $d = '';
        }
        if ($sisa_surplus < 0) {
            $sisa_surplus1 = $sisa_surplus * -1;
            $e = '(';
            $f = ')';
        } else {
            $sisa_surplus1 = $sisa_surplus;
            $e = '';
            $f = '';
        }

        $sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where jns_ang='$anggaran' and bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_netto = $row->ang_netto;
            $nil_netto = $row->nil_netto;
        }
        $sisa_netto = $ang_netto - $nil_netto;
        if (($ang_netto == 0) || ($ang_netto == '')) {
            $persen_netto = 0;
        } else {
            $persen_netto = $nil_netto / $ang_netto * 100;
        }
        $hasil->free_result();
        if ($ang_netto < 0) {
            $ang_netto1 = $ang_netto * -1;
            $g = '(';
            $h = ')';
        } else {
            $ang_netto1 = $ang_netto;
            $g = '';
            $h = '';
        }
        if ($nil_netto < 0) {
            $nil_netto1 = $nil_netto * -1;
            $i = '(';
            $j = ')';
        } else {
            $nil_netto1 = $nil_netto;
            $i = '';
            $j = '';
        }
        if ($sisa_netto < 0) {
            $sisa_netto1 = $sisa_netto * -1;
            $k = '(';
            $l = ')';
        } else {
            $sisa_netto1 = $sisa_netto;
            $k = '';
            $l = '';
        }

        $ang_silpa = $ang_surplus + $ang_netto;
        $nil_silpa = $nil_surplus + $nil_netto;
        $sisa_silpa = $ang_silpa - $nil_silpa;
        if ($ang_silpa == 0) {
            $persen_silpa = 0;
        } else {
            $persen_silpa = $nil_silpa / $ang_silpa * 100;
        }
        if ($ang_silpa < 0) {
            $ang_silpa1 = $ang_silpa * -1;
            $m = '(';
            $n = ')';
        } else {
            $ang_silpa1 = $ang_silpa;
            $m = '';
            $n = '';
        }
        if ($nil_silpa < 0) {
            $nil_silpa1 = $nil_silpa * -1;
            $o = '(';
            $p = ')';
        } else {
            $nil_silpa1 = $nil_silpa;
            $o = '';
            $p = '';
        }
        if ($sisa_silpa < 0) {
            $sisa_silpa1 = $sisa_silpa * -1;
            $q = '(';
            $r = ')';
        } else {
            $sisa_silpa1 = $sisa_silpa;
            $q = '';
            $r = '';
        }
        $sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_pemkot ORDER BY urut
						";
        $no = 0;
        $tot_peg = 0;
        $tot_brg = 0;
        $tot_mod = 0;
        $tot_bansos = 0;
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $no = $no + 1;
            $urut = $row->urut;
            $kode = $row->kd_rek;
            $nama = $row->uraian;
            $kode1 = $row->kode1;
            $kode2 = $row->kode2;
            $kode3 = $row->kode3;
            $kode4 = $row->kode4;
            $kode5 = $row->kode5;
            $spasi = $row->spasi;

            $sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where jns_ang='$anggaran' and bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

            $hasil = $this->db->query($sql);
            foreach ($hasil->result() as $row) {
                $nil_ang = $row->nil_ang;
                $nilai = $row->nilai;
            }
            $sel = $nil_ang - $nilai;
            if (($nil_ang == 0) || ($nil_ang == '')) {
                $persen = 0;
            } else {
                $persen = $nilai / $nil_ang * 100;
            }
            switch ($spasi) {
                case 1:
                    $cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
                    break;
                case 2:
                    $cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 3:
                    $cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
                    break;
                case 4:
                    $cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 5:
                    $cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 6;
                    $cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 7;
                    $cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;

                default:

                    $cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
                    break;
            }
        }



        $cRet .= "</table>";


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        if ($ttd1 != '1') {
            $xx = "<u>";
            $xy = "</u>";
            $nipxx = $nip;
            $nipx = "NIP.";
        } else {
            $xx = "";
            $xy = "";
            $nipxx = "";
            $nipx = "";
        }
        if ($tanggal_ttd == 1) {
            $tgltd = '';
        } else {
            $tgltd = $this->support->tanggal_format_indonesia($tanggal_ttd);
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> Mengetahui,</td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $tgltd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"> $jabatan </td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> $namax</td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> NIP :$nip</td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $judul = 'LRA SKPD 6 ';
        switch ($ctk) {
            case 0;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 1;
                echo ("<title>$judul</title>");
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    function cetak_lra_baru($bulan = '', $ctk = '')
    {
        $lntahunang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $ttd1 = str_replace('a', ' ', $this->uri->segment(5));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(6));
        $tanggal_ttd =    $this->uri->segment(7);
        $anggaran =  $this->uri->segment(9);

        $modtahun = $lntahunang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        if ($kd_skpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kd_skpd'";
        }

        $initang = "nilai_ang";


        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id='$ttd1' ";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($ttd1 == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                //$jdl2 = 'MENGETAHUI :';
                $nip2 = $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);
        $sclient = $this->akuntansi_model->get_sclient();
        $cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"5\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
                        <tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>" . $nmskpd . "</b></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] TAHUN $lntahunang</b></td></tr>
						</TABLE>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
					<tr>
						<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
						<td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
						<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
					</tr>
					<tr>
						<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
						<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
						<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
						<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						</tr>
						<tr>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
					</tr>
					</thead> ";

        $sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_surplus = $row->ang_surplus;
            $nil_surplus = $row->nil_surplus;
        }
        $sisa_surplus = $ang_surplus - $nil_surplus;
        if (($ang_surplus == 0) || ($ang_surplus == '')) {
            $persen_surplus = 0;
        } else {
            $persen_surplus = $nil_surplus / $ang_surplus * 100;
        }
        $hasil->free_result();
        if ($ang_surplus < 0) {
            $ang_surplus1 = $ang_surplus * -1;
            $a = '(';
            $b = ')';
        } else {
            $ang_surplus1 = $ang_surplus;
            $a = '';
            $b = '';
        }
        if ($nil_surplus < 0) {
            $nil_surplus1 = $nil_surplus * -1;
            $c = '(';
            $d = ')';
        } else {
            $nil_surplus1 = $nil_surplus;
            $c = '';
            $d = '';
        }
        if ($sisa_surplus < 0) {
            $sisa_surplus1 = $sisa_surplus * -1;
            $e = '(';
            $f = ')';
        } else {
            $sisa_surplus1 = $sisa_surplus;
            $e = '';
            $f = '';
        }

        $sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_netto = $row->ang_netto;
            $nil_netto = $row->nil_netto;
        }
        $sisa_netto = $ang_netto - $nil_netto;
        if (($ang_netto == 0) || ($ang_netto == '')) {
            $persen_netto = 0;
        } else {
            $persen_netto = $nil_netto / $ang_netto * 100;
        }
        $hasil->free_result();
        if ($ang_netto < 0) {
            $ang_netto1 = $ang_netto * -1;
            $g = '(';
            $h = ')';
        } else {
            $ang_netto1 = $ang_netto;
            $g = '';
            $h = '';
        }
        if ($nil_netto < 0) {
            $nil_netto1 = $nil_netto * -1;
            $i = '(';
            $j = ')';
        } else {
            $nil_netto1 = $nil_netto;
            $i = '';
            $j = '';
        }
        if ($sisa_netto < 0) {
            $sisa_netto1 = $sisa_netto * -1;
            $k = '(';
            $l = ')';
        } else {
            $sisa_netto1 = $sisa_netto;
            $k = '';
            $l = '';
        }

        $ang_silpa = $ang_surplus + $ang_netto;
        $nil_silpa = $nil_surplus + $nil_netto;
        $sisa_silpa = $ang_silpa - $nil_silpa;
        if ($ang_silpa == 0) {
            $persen_silpa = 0;
        } else {
            $persen_silpa = $nil_silpa / $ang_silpa * 100;
        }
        if ($ang_silpa < 0) {
            $ang_silpa1 = $ang_silpa * -1;
            $m = '(';
            $n = ')';
        } else {
            $ang_silpa1 = $ang_silpa;
            $m = '';
            $n = '';
        }
        if ($nil_silpa < 0) {
            $nil_silpa1 = $nil_silpa * -1;
            $o = '(';
            $p = ')';
        } else {
            $nil_silpa1 = $nil_silpa;
            $o = '';
            $p = '';
        }
        if ($sisa_silpa < 0) {
            $sisa_silpa1 = $sisa_silpa * -1;
            $q = '(';
            $r = ')';
        } else {
            $sisa_silpa1 = $sisa_silpa;
            $q = '';
            $r = '';
        }
        $sql = "SELECT kode, nama, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_sap_baru";
        $no = 0;
        $tot_peg = 0;
        $tot_brg = 0;
        $tot_mod = 0;
        $tot_bansos = 0;
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $no = $no + 1;
            $kode = $row->kode;
            $nama = $row->nama;
            $kode1 = $row->kode1;
            $kode2 = $row->kode2;
            $kode3 = $row->kode3;
            $kode4 = $row->kode4;
            $kode5 = $row->kode5;
            $spasi = $row->spasi;

            $sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

            $hasil = $this->db->query($sql);
            foreach ($hasil->result() as $row) {
                $nil_ang = $row->nil_ang;
                $nilai = $row->nilai;
            }
            $sel = $nil_ang - $nilai;
            if (($nil_ang == 0) || ($nil_ang == '')) {
                $persen = 0;
            } else {
                $persen = $nilai / $nil_ang * 100;
            }
            switch ($spasi) {
                case 1:
                    $cRet .= '<tr>
								   <td align="center" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
                    break;
                case 2:
                    $cRet .= '<tr>
								   <td align="center" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 3:
                    $cRet .= '<tr>
								   <td align="center" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
                    break;
                case 4:
                    $cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 5:
                    $cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 6;
                    $cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 7;
                    $cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;

                default:

                    $cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
                    break;
            }
        }



        $cRet .= "</table>";


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        if ($ttd1 != '1') {
            $xx = "<u>";
            $xy = "</u>";
            $nipxx = $nip;
            $nipx = "NIP.";
        } else {
            $xx = "";
            $xy = "";
            $nipxx = "";
            $nipx = "";
        }
        if ($tanggal_ttd == 1) {
            $tgltd = '';
        } else {
            $tgltd = $this->support->tanggal_format_indonesia($tanggal_ttd);
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> Mengetahui,</td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $tgltd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"> $jabatan </td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> $namax</td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> NIP :$nip</td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $judul = 'LRA';
        switch ($ctk) {
            case 0;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 1;
                echo ("<title>$judul</title>");
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    //ambil map lra pemkot
    function cetak_lra_77_($bulan = '', $ctk = '')
    {
        $lntahunang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $ttd1 = str_replace('a', ' ', $this->uri->segment(5));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(6));
        $tanggal_ttd =    $this->uri->segment(7);


        $agg =    $this->uri->segment(8);
        if ($agg == '3') {
            $ag_tox = '3';
        } else if ($agg == '2') {
            $ag_tox = '2';
        } else {
            $ag_tox = '1';
        }

        $modtahun = $lntahunang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        if ($kd_skpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kd_skpd'";
        }

        if ($agg == 1) {
            $initang = "nilai_ang";
        } else {
            $initang = "nilai_ang_ubah";
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$ttd1' ";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($ttd1 == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id_ttd='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                //$jdl2 = 'MENGETAHUI :';
                $nip2 = $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);
        $sclient = $this->akuntansi_model->get_sclient();
        $cRet = "<TABLE style=\"border-collapse:collapse;font-size:15px; Style\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
                        <strong>" . $sclient->kab_kota . " </strong>
                        </td>
                        </tr>
                        
                        <tr>
                        <td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
                        <strong><b>" . $nmskpd . "</b></strong>
                        </td>
                        </tr>

						<tr>
                        <td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
                        <strong><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></strong>
                        </td>
                        </tr>

						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><strong><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] TAHUN $lntahunang</strong></b></td></tr>
                        <tr>
             <td align=\"center\">&nbsp;</td>
        </tr>
						</TABLE>";

        $cRet .= "<table style=\"font-size:12px; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <thead>                       
                           <tr>
                                                        
                               <td bgcolor=\"#CCCCCC\" width=\"4%\" align=\"center\"><b>NO</b></td>                            
                               <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                               <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>ANGGARAN</b></td>
                               <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>REALISASI</b></td>
                               <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>LEBIH</br>(KURANG)</b></td>
                               <td  bgcolor=\"#CCCCCC\" width=\"6%\" align=\"center\" ><b>%</b></td>   
                           </tr>
                           
                        </thead>
                        <tfoot>
                           <tr>
                               
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>                           
                            </tr>
                        </tfoot>
                      
                        <tr>   
                               
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"4%\" align=\"center\">&nbsp;</td>                            
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"37%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"6%\">&nbsp;</td>
                               
                           </tr>";

        $sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_surplus = $row->ang_surplus;
            $nil_surplus = $row->nil_surplus;
        }
        $sisa_surplus = $ang_surplus - $nil_surplus;
        if (($ang_surplus == 0) || ($ang_surplus == '')) {
            $persen_surplus = 0;
        } else {
            $persen_surplus = $nil_surplus / $ang_surplus * 100;
        }
        $hasil->free_result();
        if ($ang_surplus < 0) {
            $ang_surplus1 = $ang_surplus * -1;
            $a = '(';
            $b = ')';
        } else {
            $ang_surplus1 = $ang_surplus;
            $a = '';
            $b = '';
        }
        if ($nil_surplus < 0) {
            $nil_surplus1 = $nil_surplus * -1;
            $c = '(';
            $d = ')';
        } else {
            $nil_surplus1 = $nil_surplus;
            $c = '';
            $d = '';
        }
        if ($sisa_surplus < 0) {
            $sisa_surplus1 = $sisa_surplus * -1;
            $e = '(';
            $f = ')';
        } else {
            $sisa_surplus1 = $sisa_surplus;
            $e = '';
            $f = '';
        }

        $sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
                    
						";

        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_netto = $row->ang_netto;
            $nil_netto = $row->nil_netto;
        }
        $sisa_netto = $ang_netto - $nil_netto;
        if (($ang_netto == 0) || ($ang_netto == '')) {
            $persen_netto = 0;
        } else {
            $persen_netto = $nil_netto / $ang_netto * 100;
        }
        $hasil->free_result();
        if ($ang_netto < 0) {
            $ang_netto1 = $ang_netto * -1;
            $g = '(';
            $h = ')';
        } else {
            $ang_netto1 = $ang_netto;
            $g = '';
            $h = '';
        }
        if ($nil_netto < 0) {
            $nil_netto1 = $nil_netto * -1;
            $i = '(';
            $j = ')';
        } else {
            $nil_netto1 = $nil_netto;
            $i = '';
            $j = '';
        }
        if ($sisa_netto < 0) {
            $sisa_netto1 = $sisa_netto * -1;
            $k = '(';
            $l = ')';
        } else {
            $sisa_netto1 = $sisa_netto;
            $k = '';
            $l = '';
        }

        $ang_silpa = $ang_surplus + $ang_netto;
        $nil_silpa = $nil_surplus + $nil_netto;
        $sisa_silpa = $ang_silpa - $nil_silpa;
        if ($ang_silpa == 0) {
            $persen_silpa = 0;
        } else {
            $persen_silpa = $nil_silpa / $ang_silpa * 100;
        }
        if ($ang_silpa < 0) {
            $ang_silpa1 = $ang_silpa * -1;
            $m = '(';
            $n = ')';
        } else {
            $ang_silpa1 = $ang_silpa;
            $m = '';
            $n = '';
        }
        if ($nil_silpa < 0) {
            $nil_silpa1 = $nil_silpa * -1;
            $o = '(';
            $p = ')';
        } else {
            $nil_silpa1 = $nil_silpa;
            $o = '';
            $p = '';
        }
        if ($sisa_silpa < 0) {
            $sisa_silpa1 = $sisa_silpa * -1;
            $q = '(';
            $r = ')';
        } else {
            $sisa_silpa1 = $sisa_silpa;
            $q = '';
            $r = '';
        }
        $sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_pemkot ORDER BY urut
						";
        $no = 0;
        $tot_peg = 0;
        $tot_brg = 0;
        $tot_mod = 0;
        $tot_bansos = 0;
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $no = $no + 1;
            $urut = $row->urut;
            $kode = $row->kd_rek;
            $nama = $row->uraian;
            $kode1 = $row->kode1;
            $kode2 = $row->kode2;
            $kode3 = $row->kode3;
            $kode4 = $row->kode4;
            $kode5 = $row->kode5;
            $spasi = $row->spasi;

            $sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

            $hasil = $this->db->query($sql);
            foreach ($hasil->result() as $row) {
                $nil_ang = $row->nil_ang;
                $nilai = $row->nilai;
            }
            $sel = $nil_ang - $nilai;
            if (($nil_ang == 0) || ($nil_ang == '')) {
                $persen = 0;
            } else {
                $persen = $nilai / $nil_ang * 100;
            }
            switch ($spasi) {
                case 1:
                    $cRet .= '<tr>
								   <td align="center" valign="top">' . $no . '</td> 
                                   
								   <td align="left"  valign="top">' . $nama . '</td> 
								   <td align="right" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								   <td align="right" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								   <td align="right" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								   <td align="right" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								</tr>';
                    break;
                case 2:
                    $cRet .= '<tr>
								   <td align="center" valign="top">' . $no . '</td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
                    break;
                case 3:
                    $cRet .= '<tr>
								   <td align="center" valign="top">' . $no . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
                    break;
                case 4:
                    $cRet .= '<tr>
								   <td align="center" valign="top" >' . $no . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
                    break;
                case 5:
                    $cRet .= '<tr>
								   <td align="center" valign="top" >' . $no . '</b></td> 
								   <td align="right"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</td> 
								   <td align="right" valign="top">' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</td> 
								   <td align="right" valign="top">' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</td> 
								   <td align="right" valign="top">' . number_format($persen_surplus, "2", ",", ".") . '</td> 
								</tr>';
                    break;
                case 6;
                    $cRet .= '<tr>
								   <td align="center" valign="top" >' . $no . '</td> 
								   <td align="right"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top" >' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</td> 
								   <td align="right" valign="top" >' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</td> 
								   <td align="right" valign="top" >' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</td> 
								   <td align="right" valign="top" >' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
                    break;
                case 7;
                    $cRet .= '<tr>
								   <td align="center" valign="top" >' . $no . '</b></td> 
								   <td align="right"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top" >' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</td> 
								   <td align="right" valign="top" >' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</td> 
								   <td align="right" valign="top" >' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</td> 
								   <td align="right" valign="top" >' . number_format($persen_silpa, "2", ",", ".") . '</td> 
								</tr>';
                    break;

                default:

                    $cRet .= '<tr>
								   <td align="center" valign="top" >' . $no . '</b></td> 
								   <td align="right"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
								</tr>';
                    break;
            }
        }

        $cRet .= "</table>";


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        if ($ttd1 != '1') {
            $xx = "<u>";
            $xy = "</u>";
            $nipxx = $nip;
            $nipx = "NIP.";
        } else {
            $xx = "";
            $xy = "";
            $nipxx = "";
            $nipx = "";
        }
        if ($tanggal_ttd == 1) {
            $tgltd = '';
        } else {
            $tgltd = $this->support->tanggal_format_indonesia($tanggal_ttd);
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> Mengetahui,</td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $tgltd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"> $jabatan </td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> $namax</td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> NIP :$nip</td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $judul = 'LRA SKPD 6 ';
        switch ($ctk) {
            case 0;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 1;
                echo ("<title>$judul</title>");
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    function cetak_lra_sub_ro_($cbulan = "", $pilih = 1)
    {
        $id  = $this->session->userdata('kdskpd');
        $thn = $this->session->userdata('pcThang');

        $agg =    $this->uri->segment(8);
        if ($agg == '3') {
            $ag_tox = '3';
        } else if ($agg == '2') {
            $ag_tox = '2';
        } else {
            $ag_tox = '1';
        }

        $tgl =    $this->uri->segment(7);
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tgl);
        $ttd1 = str_replace('a', ' ', $this->uri->segment(5));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(6));
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id_ttd='$ttd1' ";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($ttd1 == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id_ttd='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                //$jdl2 = 'MENGETAHUI :';
                $nip2 = $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $nmskpd = $this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'")->row()->nm_skpd;


        $nm_skpd    = strtoupper($nmskpd);
        $jk = $this->rka_model->combo_skpd();

        $cRet = '';

        $modtahun = $thn % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $sclient = $this->akuntansi_model->get_sclient();

        $cRet .= "<table style=\"font-size:13px; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
					<tr>
                         <td align=\"center\"><strong>$nm_skpd</strong></td>                         
                    </tr>					
                    <tr>
                         <td align=\"center\"><strong>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";


        $cRet .= "<table style=\"font-size:12px; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>                             
                            <td bgcolor=\"#CCCCCC\" width=\"4%\" align=\"center\"><b>NO</b></td>                            
                            <td colspan=\"6\" bgcolor=\"#CCCCCC\" width=\"37%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>ANGGARAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>REALISASI</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>LEBIH</br>(KURANG)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"6%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td colspan=\"6\" style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr>   
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"4%\" align=\"center\">&nbsp;</td>                            
                            <td colspan=\"6\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"37%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"6%\">&nbsp;</td>
                        </tr>";
        $total_belanja   = $this->db->query("SELECT SUM(b.nilai_ang) as anggaran,SUM(b.real_spj) as nilai 
                    FROM data_realisasi_keg4( $cbulan,$ag_tox, $thn) b 
                    WHERE left(kd_skpd,17)=left('$id',17) and left(b.kd_rek6,1) in ('5')")->row();
        $total_pendapatan   = $this->db->query("SELECT SUM(b.nilai_ang) as anggaran,SUM(b.real_spj) as nilai 
                    FROM data_realisasi_keg4( $cbulan,$ag_tox, $thn) b 
                    WHERE left(kd_skpd,17)=left('$id',17) and left(b.kd_rek6,1) in ('4')")->row();

        $surplus_anggaran = $total_pendapatan->anggaran - $total_belanja->anggaran;
        $surplus_realisasi = $total_pendapatan->nilai - $total_belanja->nilai;
        $selisih_surplus = $surplus_anggaran - $surplus_realisasi;
        if ($surplus_realisasi == 0) {
            $persen_surplus = $this->support->rp_minus(0);
        } else {
            $persen_surplus = $this->support->rp_minus($surplus_realisasi / $selisih_surplus * 100);
        }

        $sql4 = "SELECT a.bold,a.nor,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,thn_m1 AS lalu FROM map_lra_skpd a 
				   GROUP BY a.bold,a.nor,a.uraian,isnull(a.kode_1,'-'),isnull(a.kode_2,'-'),isnull(a.kode_3,'-'),thn_m1 ORDER BY nor";

        $query4 = $this->db->query($sql4);
        $no     = 0;

        foreach ($query4->result() as $row4) {

            $nama      = $row4->uraian;
            $real_lalu = number_format($row4->lalu, "2", ",", ".");
            $n         = $row4->kode_1;
            $n           = ($n == "-" ? "'-'" : $n);
            $n2         = $row4->kode_2;
            $n2           = ($n2 == "-" ? "'-'" : $n2);
            $n3         = $row4->kode_3;
            $n3           = ($n3 == "-" ? "'-'" : $n3);

            $sql5   = "SELECT SUM(b.nilai_ang) as anggaran,SUM(b.real_spj) as nilai 
                    FROM data_realisasi_keg4( $cbulan,$ag_tox, $thn) b 
                    WHERE left(kd_skpd,17)=left('$id',17) and left(b.kd_rek6,4) in ($n)";

            $query5 = $this->db->query($sql5);
            $trh    = $query5->row();
            $nil    = $trh->nilai;
            $angnil = $trh->anggaran;

            $selisih = $this->support->rp_minus($trh->anggaran - $trh->nilai);

            if ($trh->nilai == 0) {
                $persen = $this->support->rp_minus(0);
            } else {
                $persen = $this->support->rp_minus($trh->nilai / $trh->anggaran * 100);
            }


            $nilai    = number_format($trh->nilai, "2", ",", ".");
            $angnilai = number_format($trh->anggaran, "2", ",", ".");
            $no       = $no + 1;

            switch ($row4->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td colspan=\"6\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">&nbsp;</td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td colspan=\"6\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right:none;\" width=\"2%\"></td>
                                     <td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left:none;\" width=\"35%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                case 3:
                    /*SURPLUS DEFISIT*/
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>    
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right:none;\" width=\"2%\"></td>                                 
                                     <td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left:none;\" width=\"35%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . $this->support->rp_minus($surplus_anggaran) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . $this->support->rp_minus($surplus_realisasi) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">" . $this->support->rp_minus($selisih_surplus) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen_surplus</td>
                                 </tr>";
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>  
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right:none;\" width=\"2%\"></td>                                   
                                     <td colspan=\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left:none;\" width=\"35%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                case 9:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>   
                                     <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right:none;\" width=\"3%\"></td>                    
                                     <td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left:none;\" width=\"34%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";


                    $sql = $this->db->query("SELECT left(b.kd_rek6,6) as kd_rek, c.nm_rek4 as nm_rek, SUM(b.nilai_ang) as anggaran,SUM(b.real_spj) as nilai 
                                                    FROM data_realisasi_keg4( $cbulan,$ag_tox, $thn) b 
                                                    join ms_rek4 c on  left(b.kd_rek6,6)=c.kd_rek4
                                                    WHERE left(b.kd_skpd,17)=left('$id',17) and left(b.kd_rek6,4) in ($n)
                                                    group by left(b.kd_rek6,6),c.nm_rek4
                                                    union all
                                                    SELECT left(b.kd_rek6,8) as kd_rek, c.nm_rek5 as nm_rek, SUM(b.nilai_ang) as anggaran,SUM(b.real_spj) as nilai 
                                                    FROM data_realisasi_keg4( $cbulan,$ag_tox, $thn) b 
                                                    join ms_rek5 c on  left(b.kd_rek6,8)=c.kd_rek5
                                                    WHERE left(b.kd_skpd,17)=left('$id',17) and left(b.kd_rek6,4) in ($n)
                                                    group by left(b.kd_rek6,8),c.nm_rek5
                                                    union all
                                                    SELECT b.kd_rek6 as kd_rek,  b.nm_rek, SUM(b.nilai_ang) as anggaran,SUM(b.real_spj) as nilai 
                                                    FROM data_realisasi_keg4( $cbulan,$ag_tox, $thn) b 
                                                    WHERE left(b.kd_skpd,17)=left('$id',17) and left(b.kd_rek6,4) in ($n)
                                                    group by b.kd_rek6,nm_rek
                                                    order by kd_rek");
                    foreach ($sql->result() as $row) {

                        $no = $no + 1;
                        $nil    = $row->nilai;
                        $angnil = $row->anggaran;
                        $nama = $row->nm_rek;

                        $selisih = $this->support->rp_minus($angnil - $nil);

                        if ($nil == 0 or $angnil == 0) {
                            $persen = $this->support->rp_minus(0);
                        } else {
                            $persen = $this->support->rp_minus($nil / $angnil * 100);
                        }

                        $nilai    = number_format($nil, "2", ",", ".");
                        $angnilai = number_format($angnil, "2", ",", ".");

                        switch (strlen($row->kd_rek)) {
                            case 6:
                                $cRet    .= "<tr>
                                                        <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>    
                                                        <td colspan=\"3\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right:none;\" width=\"4%\"></td>                                    
                                                        <td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left:none;\" width=\"33%\">$nama</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                                    </tr>";
                                break;
                            case 8:
                                $cRet    .= "<tr>
                                                        <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>
                                                        <td colspan=\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right:none;\" width=\"5%\"></td>                                      
                                                        <td colspan=\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left:none;\" width=\"32%\">$nama</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                                    </tr>";
                                break;
                            default:
                                $cRet    .= "<tr>
                                                        <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>   
                                                        <td colspan=\"5\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right:none;\" width=\"6%\"></td>                                   
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left:none;\" width=\"31%\">$nama</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                                    </tr>";
                                break;
                        }
                    }
                    break;
                default:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>   
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"2%\"></td>                                  
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"35%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
            }
        }
        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $cRet         .= "</table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> Mengetahui,</td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $ctgl_ttd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"> $jabatan </td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> $namax</td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> NIP :$nip</td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LRA SKPD $id / $cbulan");
        $this->template->set('title', 'LRA SKPD $id / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LRA SKPD $cbulan</title>");
                echo $cRet;
                break;
            case 0;
                $pdf = new Pdf(array(
                    'binary' => $this->config->item('wkhtmltopdf_path'),
                    'orientation' => 'Portrait',
                    'title' => $judul,
                    'footer-center' => 'Halaman [page] / [topage]',
                    'footer-left' => 'Printed on @ [date] [time]',
                    'footer-font-size' => 6,
                ));
                $pdf->addPage($cRet);
                $pdf->send();
                break;
            case 2;
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                echo $cRet;
                break;
            case 3;
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                echo $cRet;
                break;
        }
    }
    //end ambil

    //--------------------------------------------------------------------------------------------------------------

    //mapingan ambil map_lra_skpd
    function cetak_lra_77($bulan = '', $pilih = '')
    {
        $lntahunang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $ttd1 = str_replace('a', ' ', $this->uri->segment(5));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(6));
        $tanggal_ttd =    $this->uri->segment(7);
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggal_ttd);
        $agg =    $this->uri->segment(9);


        $modtahun = $lntahunang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        if ($kd_skpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kd_skpd'";
        }

        $initang = "nilai_ang";

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id='$ttd1' ";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($ttd1 == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                //$jdl2 = 'MENGETAHUI :';
                $nip2 = $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);
        $sclient = $this->akuntansi_model->get_sclient();
        $cRet = "<TABLE style=\"border-collapse:collapse;font-size:15px; Style\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
                        <strong>" . $sclient->kab_kota . " </strong>
                        </td>
                        </tr>
                        
                        <tr>
                        <td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
                        <strong><b>" . $nmskpd . "</b></strong>
                        </td>
                        </tr>

						<tr>
                        <td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
                        <strong><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></strong>
                        </td>
                        </tr>

						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><strong><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] TAHUN $lntahunang</strong></b></td></tr>
                        <tr>
             <td align=\"center\">&nbsp;</td>
        </tr>
						</TABLE>";

        $cRet .= "<table style=\"font-size:12px; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <thead>                       
                           <tr>
                               <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>                             
                               <td bgcolor=\"#CCCCCC\" width=\"4%\" align=\"center\"><b>NO</b></td>                            
                               <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                               <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>ANGGARAN</b></td>
                               <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>REALISASI</b></td>
                               <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>LEBIH</br>(KURANG)</b></td>
                               <td  bgcolor=\"#CCCCCC\" width=\"6%\" align=\"center\" ><b>%</b></td>   
                           </tr>
                           
                        </thead>
                        <tfoot>
                           <tr>
                               <td style=\"border: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>                           
                            </tr>
                        </tfoot>
                      
                        <tr>   
                               <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"4%\" align=\"center\">&nbsp;</td>                            
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"37%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"6%\">&nbsp;</td>
                           </tr>";
        $total_belanja   = $this->db->query("SELECT SUM(b.$initang) as anggaran,SUM(b.real_spj) as nilai 
                    FROM data_realisasi_keg4( $bulan, $lntahunang) b 
                    WHERE b.jns_ang='$agg' and left(kd_skpd,17)=left('$kd_skpd',17) and left(b.kd_rek6,1) in ('5')")->row();
        $total_pendapatan   = $this->db->query("SELECT SUM(b.$initang) as anggaran,SUM(b.real_spj) as nilai 
                    FROM data_realisasi_keg4( $bulan, $lntahunang) b 
                    WHERE b.jns_ang='$agg' and left(kd_skpd,17)=left('$kd_skpd',17) and left(b.kd_rek6,1) in ('4')")->row();

        $surplus_anggaran = $total_pendapatan->anggaran - $total_belanja->anggaran;
        $surplus_realisasi = $total_pendapatan->nilai - $total_belanja->nilai;
        $selisih_surplus = $surplus_anggaran - $surplus_realisasi;
        if ($surplus_realisasi == 0) {
            $persen_surplus = $this->support->rp_minus(0);
        } else {
            $persen_surplus = $this->support->rp_minus($surplus_realisasi / $selisih_surplus * 100);
        }

        $sql4 = "SELECT a.bold,a.nor,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,thn_m1 AS lalu FROM map_lra_skpd a 
				   GROUP BY a.bold,a.nor,a.uraian,isnull(a.kode_1,'-'),isnull(a.kode_2,'-'),isnull(a.kode_3,'-'),thn_m1 ORDER BY nor";

        $query4 = $this->db->query($sql4);
        $no     = 0;

        foreach ($query4->result() as $row4) {

            $nama      = $row4->uraian;
            $real_lalu = number_format($row4->lalu, "2", ",", ".");
            $n         = $row4->kode_1;
            $n           = ($n == "-" ? "'-'" : $n);
            $n2         = $row4->kode_2;
            $n2           = ($n2 == "-" ? "'-'" : $n2);
            $n3         = $row4->kode_3;
            $n3           = ($n3 == "-" ? "'-'" : $n3);

            $sql5   = "SELECT SUM(b.$initang) as anggaran,SUM(b.real_spj) as nilai 
                    FROM data_realisasi_keg4( $bulan,$lntahunang) b 
                    WHERE b.jns_ang='$agg' and left(kd_skpd,17)=left('$kd_skpd',17) and left(b.kd_rek6,4) in ($n)";



            $query5 = $this->db->query($sql5);
            $trh    = $query5->row();
            $nil    = $trh->nilai;
            $angnil = $trh->anggaran;

            $selisih = $this->support->rp_minus($trh->anggaran - $trh->nilai);

            if ($trh->nilai == 0) {
                $persen = $this->support->rp_minus(0);
            } else {
                $persen = $this->support->rp_minus($trh->nilai / $trh->anggaran * 100);
            }


            $nilai    = number_format($trh->nilai, "2", ",", ".");
            $angnilai = number_format($trh->anggaran, "2", ",", ".");
            $no       = $no + 1;

            switch ($row4->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">&nbsp;</td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                case 3:
                    /*SURPLUS DEFISIT*/
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . $this->support->rp_minus($surplus_anggaran) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . $this->support->rp_minus($surplus_realisasi) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">" . $this->support->rp_minus($selisih_surplus) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen_surplus</td>
                                 </tr>";
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                default:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
            }
        }
        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $cRet         .= "</table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> Mengetahui,</td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $ctgl_ttd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"> $jabatan </td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> $namax</td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> NIP :$nip</td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LRA SKPD $kd_skpd / $bulan");
        $this->template->set('title', 'LRA SKPD $id / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LRA SKPD $bulan</title>");
                echo $cRet;
                break;
            case 0;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 2;
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                echo $cRet;
                break;
        }
    }
    //end ambil

    function cetak_lra_sub_ro($bulan = '', $ctk = '')
    {
        $lntahunang = $this->session->userdata('pcThang');
        $kd_skpd  = $this->session->userdata('kdskpd');


        $ttd1 = str_replace('a', ' ', $this->uri->segment(5));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(6));
        $tanggal_ttd =    $this->uri->segment(7);
        $anggaran =    $this->uri->segment(9);
        // echo ($anggaran);
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where id='$ttd1' ";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($ttd1 == '-') {
            $jdl2 = '';
            $nip2 = '';
            $nama2 = '';
            $jabatan2  = '';
            $pangkat2  = '';
        } else {
            $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where id='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd2);
            foreach ($sqlttd2->result() as $rowttd2) {
                //$jdl2 = 'MENGETAHUI :';
                $nip2 = $rowttd2->nip;
                $nama2 = $rowttd2->nm2;
                $jabatan2  = $rowttd2->jab;
                $pangkat2  = $rowttd2->pangkat;
            }
        }

        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);

        $modtahun = $lntahunang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }
        $arraybulan = explode(".", $nilaibulan);

        if ($kd_skpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kd_skpd'";
        }
        // -----------
        $initang = "nilai_ang";

        $sql = $this->db->query("SELECT top 1 kab_kota from sclient");
        $query = $sql->row();
        $kab_kota = $query->kab_kota;

        $cRet = "	<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
						</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
							<strong>$kab_kota</strong>
						</td>
					</tr>
                    <tr>	
                        <td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
                            <b>$nmskpd</b>
                        </td>
                    </tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
							<b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b>
						</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] TAHUN $lntahunang</b>
                        </td>
					</tr>

				</TABLE>";

        $cRet .= "	<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
						<tr>
							<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
							<td rowspan=\"2\" colspan=\"6\" width=\"33%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
							<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
							<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
						</tr>
						<tr>
							<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
							<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
							<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
							<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						</tr>
						<tr>
							<td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
							<td colspan=\"6\" align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
						</tr>
					</thead>";

        $sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
					FROM
					(
						SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot 
						where jns_ang='$anggaran' and bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a";
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_surplus = $row->ang_surplus;
            $nil_surplus = $row->nil_surplus;
        }

        $sisa_surplus = $ang_surplus - $nil_surplus;
        if (($ang_surplus == 0) || ($ang_surplus == '')) {
            $persen_surplus = 0;
        } else {
            $persen_surplus = $nil_surplus / $ang_surplus * 100;
        }

        $hasil->free_result();
        if ($ang_surplus < 0) {
            $ang_surplus1 = $ang_surplus * -1;
            $a = '(';
            $b = ')';
        } else {
            $ang_surplus1 = $ang_surplus;
            $a = '';
            $b = '';
        }

        if ($nil_surplus < 0) {
            $nil_surplus1 = $nil_surplus * -1;
            $c = '(';
            $d = ')';
        } else {
            $nil_surplus1 = $nil_surplus;
            $c = '';
            $d = '';
        }

        if ($sisa_surplus < 0) {
            $sisa_surplus1 = $sisa_surplus * -1;
            $e = '(';
            $f = ')';
        } else {
            $sisa_surplus1 = $sisa_surplus;
            $e = '';
            $f = '';
        }

        $sql = "SELECT 
				SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
				SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
				FROM
				(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot 
				where jns_ang='$anggaran' and bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
				GROUP BY LEFT(kd_ang,2)) a";


        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_netto = $row->ang_netto;
            $nil_netto = $row->nil_netto;
        }

        $sisa_netto = $ang_netto - $nil_netto;
        if (($ang_netto == 0) || ($ang_netto == '')) {
            $persen_netto = 0;
        } else {
            $persen_netto = $nil_netto / $ang_netto * 100;
        }

        $hasil->free_result();
        if ($ang_netto < 0) {
            $ang_netto1 = $ang_netto * -1;
            $g = '(';
            $h = ')';
        } else {
            $ang_netto1 = $ang_netto;
            $g = '';
            $h = '';
        }

        if ($nil_netto < 0) {
            $nil_netto1 = $nil_netto * -1;
            $i = '(';
            $j = ')';
        } else {
            $nil_netto1 = $nil_netto;
            $i = '';
            $j = '';
        }

        if ($sisa_netto < 0) {
            $sisa_netto1 = $sisa_netto * -1;
            $k = '(';
            $l = ')';
        } else {
            $sisa_netto1 = $sisa_netto;
            $k = '';
            $l = '';
        }

        $ang_silpa = $ang_surplus + $ang_netto;
        $nil_silpa = $nil_surplus + $nil_netto;
        $sisa_silpa = $ang_silpa - $nil_silpa;
        if ($ang_silpa == 0) {
            $persen_silpa = 0;
        } else {
            $persen_silpa = $nil_silpa / $ang_silpa * 100;
        }

        if ($ang_silpa < 0) {
            $ang_silpa1 = $ang_silpa * -1;
            $m = '(';
            $n = ')';
        } else {
            $ang_silpa1 = $ang_silpa;
            $m = '';
            $n = '';
        }

        if ($nil_silpa < 0) {
            $nil_silpa1 = $nil_silpa * -1;
            $o = '(';
            $p = ')';
        } else {
            $nil_silpa1 = $nil_silpa;
            $o = '';
            $p = '';
        }

        if ($sisa_silpa < 0) {
            $sisa_silpa1 = $sisa_silpa * -1;
            $q = '(';
            $r = ')';
        } else {
            $sisa_silpa1 = $sisa_silpa;
            $q = '';
            $r = '';
        }

        $sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_pemkot ORDER BY urut";
        $no = 0;
        $tot_peg = 0;
        $tot_brg = 0;
        $tot_mod = 0;
        $tot_bansos = 0;
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $no = $no + 1;
            $urut = $row->urut;
            $kode = $row->kd_rek;
            $nama = $row->uraian;
            $kode1 = $row->kode1;
            $kode2 = $row->kode2;
            $kode3 = $row->kode3;
            $kode4 = $row->kode4;
            $kode5 = $row->kode5;
            $spasi = $row->spasi;

            $sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM 
					data_realisasi_pemkot where jns_ang='$anggaran' and bulan='$bulan' and (LEFT(kd_ang,1) 
					IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) 
					or LEFT(kd_ang,4) IN ($kode3) 
					or LEFT(kd_ang,6) IN ($kode4) 
					or LEFT(kd_ang,8) IN ($kode5)) $where";

            $hasil = $this->db->query($sql);
            foreach ($hasil->result() as $row) {
                $nil_ang = $row->nil_ang;
                $nilai = $row->nilai;
            }

            $sel = $nil_ang - $nilai;
            if (($nil_ang == 0) || ($nil_ang == '')) {
                $persen = 0;
            } else {
                $persen = $nilai / $nil_ang * 100;
            }
            switch ($spasi) {
                case 1:
                    $cRet .= '<tr>
								<td align="left" valign="top"><b>' . $kode . '</b></td> 
								<td colspan="6" align="left"  valign="top"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
							</tr>';
                    break;
                case 2:
                    $cRet .= '<tr>
								<td align="left"  valign="top"><b>' . $kode . '</b></td> 
								<td align="left"  width="1%" valign="top" style="border-right:none"></td> 
								<td colspan="5" width="32%" align="left"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
                    break;
                case 3:
                    $cRet .= '<tr>
								<td align="left" valign="top">' . $kode . '</b></td> 
								<td colspan="2" align="left"  width="2%" valign="top" style="border-right:none"></td> 
								<td colspan="4" align="left"  width="31%" valign="top" style="border-left:none">' . $nama . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';

                    // if kode=X then kode is not exist		
                    $is_kode3 = strtoupper(substr(str_replace("'", "", $kode3), 0, 1));
                    $is_kode4 = strtoupper(substr(str_replace("'", "", $kode4), 0, 1));
                    $is_kode5 = strtoupper(substr(str_replace("'", "", $kode5), 0, 1));

                    if ($is_kode3 != 'X') {
                        $sql = "SELECT '8' as spasi, b.kd_rek4 as kd_rek,b.nm_rek4 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek4 b on left(a.kd_rek6,6)=b.kd_rek4 where a.jns_ang='$anggaran' and a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
								group by b.kd_rek4,b.nm_rek4
								union all
								SELECT '9' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.jns_ang='$anggaran' and a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
								group by b.kd_rek5,b.nm_rek5
								union all
								SELECT '10' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								where a.jns_ang='$anggaran' and a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
								group by a.kd_rek6,a.nm_rek6 order by kd_rek";
                    } else {
                        if ($is_kode4 != 'X') {
                            $sql = "SELECT '8' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.jns_ang='$anggaran' and a.bulan='$bulan' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
									group by b.kd_rek5,b.nm_rek5
									union all
									SELECT '9' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									where a.jns_ang='$anggaran' and a.bulan='$bulan' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
									group by a.kd_rek6,a.nm_rek6 order by kd_rek";
                        } else {
                            if ($is_kode5 != 'X') {
                                $sql = "SELECT '8' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
										where a.jns_ang='$anggaran' and a.bulan='$bulan' and 
										(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
										group by a.kd_rek6,a.nm_rek6 order by kd_rek";
                            }
                        }
                    }

                    if ($sql) {
                        $result = $this->db->query($sql);
                        foreach ($result->result() as $row1) {
                            $spasi3 = $row1->spasi;
                            $nilai_anggaran = $row1->nil_ang;
                            $nilai_realisasi = $row1->nilai;

                            $selisih = $nilai_anggaran - $nilai_realisasi;
                            if (($nilai_anggaran == 0) || ($nilai_anggaran == '')) {
                                $persen = 0;
                            } else {
                                $persen = $nilai_realisasi / $nilai_anggaran * 100;
                            }

                            switch ($spasi3) {
                                case 8:
                                    $cRet .= '<tr>
												<td align="left" valign="top">' . $this->support->dotrek($row1->kd_rek) . '</b></td> 
												<td colspan="3" align="left"  width="3%" valign="top" style="border-right:none">&nbsp;</td>
												<td colspan="3" align="left"  width="30%" valign="top" style="border-left:none">' . $row1->nm_rek . '</td> 
												<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
											</tr>';
                                    break;
                                case 9:
                                    $cRet .= '<tr>
													<td align="left" valign="top">' . $this->support->dotrek($row1->kd_rek) . '</b></td> 
													<td colspan="4" align="left"  width="4%" valign="top" style="border-right:none"></td>
													<td colspan="2" align="left"  width="29%" valign="top" style="border-left:none">' . $row1->nm_rek . '</td> 
													<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
												</tr>';
                                    break;
                                case 10:
                                    $cRet .= '<tr>
														<td align="left" valign="top">' . $this->support->dotrek($row1->kd_rek) . '</b></td> 
														<td colspan="5" align="left"  width=\"5%\" valign="top" style="border-right:none"></td>
														<td align="left"  valign="top" width="28%" style="border-left:none">' . $row1->nm_rek . '</td> 
														<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
													</tr>';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        }
                    }
                    $sql = false;

                    break;
                case 4:
                    $cRet .= '<tr>
								<td align="left" valign="top" ><b>' . $kode . '</b></td> 
								<td width="1%" align="left" valign="top" style="border-right:none"></td>
								<td colspan="5" align="left"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
                    break;
                case 5:
                    $cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							<td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							<td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							<td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
						</tr>';
                    break;
                case 6;
                    $cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							<td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							<td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							<td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
						</tr>';
                    break;
                case 7;
                    $cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							<td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							<td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							<td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
						</tr>';
                    break;
                default:
                    $cRet .= '<tr>
						<td align="left" valign="top" ><b>' . $kode . '</b></td> 
						<td colspan="6" align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
					</tr>';
                    break;
            }
        }

        $cRet .= "</table>";


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' 
					and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        if ($ttd1 != '1') {
            $xx = "<u>";
            $xy = "</u>";
            $nipxx = $nip;
            $nipx = "NIP.";
        } else {
            $xx = "";
            $xy = "";
            $nipxx = "";
            $nipx = "";
        }

        if ($tanggal_ttd == 1) {
            $tgltd = '';
        } else {
            $tgltd = $this->support->tanggal_format_indonesia($tanggal_ttd);
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> Mengetahui,</td>
            <td align=\"center\" width=\"50%\"> " . $daerah . ", $tgltd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"> $jabatan </td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> $namax</td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> NIP :$nip</td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";


        $data['prev'] = $cRet;
        $judul = 'LRA PERMEN 90 SUB RO';
        switch ($ctk) {
            case 1;
                echo ("<title>LRA SKPD $bulan</title>");
                echo $cRet;
                break;
            case 0;
                // $pdf = new Pdf(array(
                //     'binary' => $this->config->item('wkhtmltopdf_path'),
                //     'orientation' => 'Portrait',
                //     'title' => $judul,
                //     'footer-center' => 'Halaman [page] / [topage]',
                //     'footer-left' => 'Printed on @ [date] [time]',
                //     'footer-font-size' => 6,
                // ));
                // $pdf->addPage($cRet);
                // $pdf->send();
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                break;
            case 2;
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                echo $cRet;
                break;
            case 3;
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                echo $cRet;
                break;
        }
    }


    //================================================= End LRA

    //================================================= LO
    function cetak_lra_lo_v()
    {
        $data['page_title'] = 'LO SKPD';
        $this->template->set('title', 'LO SKPD');
        $this->template->load('template', 'akuntansi/cetak_lra_lo', $data);
    }

    function cetak_lra_lo($cbulan = "", $pilih = 1, $ttd = "", $tgl_ttd = "")
    {

        $cetak = '2'; //$ctk;
        $kd_skpd   = $this->session->userdata('kdskpd');
        $id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;

        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tgl_ttd);

        $nip2 = str_replace('123456789', ' ', $ttd);

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$id' AND kode IN ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama_ttd = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }

        //$laporan=$lap; 

        // if ($cetak == '1') {
        //           $skpd = '';
        //           $skpd1 = '';           
        //       } else {             
        $skpd = "AND kd_skpd='$kd_skpd'";
        $skpd1 = "AND b.kd_skpd='$kd_skpd'";
        //}  

        if ($cbulan == 12) {
            $sumber_jurnal = "ju_calk";
        } else {
            $sumber_jurnal = "ju";
        }

        $y123 = ")";
        $x123 = "(";
        $sqlsc = "SELECT nm_org FROM ms_organisasi where kd_org=left('$id',17)";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_org;
        }
        $nm_skpd = strtoupper($nmskpd);
        // INSERT DATA

        /*$sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE left(kd_skpd,7)=left('$kd_skpd',7)";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                } 
		*/
        // created by henri_tb
        $trhju = 'trhju_pkd';
        $trdju = 'trdju_pkd';
        $sqllo1 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73') and kd_skpd='$kd_skpd'";
        $querylo1 = $this->db->query($sqllo1);
        $penlo = $querylo1->row();
        $pen_lo = $penlo->nilai;
        $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

        $sqllo2 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and kd_skpd='$kd_skpd'";
        $querylo2 = $this->db->query($sqllo2);
        $penlo2 = $querylo2->row();
        $pen_lo_lalu = $penlo2->nilai;
        $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

        $sqllo3 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82') and kd_skpd='$kd_skpd'";
        $querylo3 = $this->db->query($sqllo3);
        $bello = $querylo3->row();
        $bel_lo = $bello->nilai;
        $bel_lo1 = number_format($bello->nilai, "2", ",", ".");

        $sqllo4 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82') and kd_skpd='$kd_skpd'";
        $querylo4 = $this->db->query($sqllo4);
        $bello2 = $querylo4->row();
        $bel_lo_lalu = $bello2->nilai;
        $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

        $surplus_lo = $pen_lo - $bel_lo;
        if ($surplus_lo < 0) {
            $lo1 = "(";
            $surplus_lox = $surplus_lo * -1;
            $lo2 = ")";
        } else {
            $lo1 = "";
            $surplus_lox = $surplus_lo;
            $lo2 = "";
        }
        $surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

        $surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
        if ($surplus_lo_lalu < 0) {
            $lo3 = "(";
            $surplus_lo_lalux = $surplus_lo_lalu * -1;
            $lo4 = ")";
        } else {
            $lo3 = "";
            $surplus_lo_lalux = $surplus_lo_lalu;
            $lo4 = "";
        }
        $surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

        $selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
        if ($selisih_surplus_lo < 0) {
            $lo5 = "(";
            $selisih_surplus_lox = $selisih_surplus_lo * -1;
            $lo6 = ")";
        } else {
            $lo5 = "";
            $selisih_surplus_lox = $selisih_surplus_lo;
            $lo6 = "";
        }
        $selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

        if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
            $persen2 = '0,00';
        } else {
            $persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
            $persen2 = number_format($persen2, "2", ",", ".");
        }

        $sqllo5 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74') and kd_skpd='$kd_skpd'";
        $querylo5 = $this->db->query($sqllo5);
        $penlo3 = $querylo5->row();
        $pen_lo3 = $penlo3->nilai;
        $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

        $sqllo6 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74') and kd_skpd='$kd_skpd'";
        $querylo6 = $this->db->query($sqllo6);
        $penlo4 = $querylo6->row();
        $pen_lo_lalu4 = $penlo4->nilai;
        $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

        $sqllo7 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
        $querylo7 = $this->db->query($sqllo7);
        $bello5 = $querylo7->row();
        $bel_lo5 = $bello5->nilai;
        $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

        $sqllo8 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
        $querylo8 = $this->db->query($sqllo8);
        $bello6 = $querylo8->row();
        $bel_lo_lalu6 = $bello6->nilai;
        $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

        $surplus_lo2 = $pen_lo3 - $bel_lo5;
        if ($surplus_lo2 < 0) {
            $lo7 = "(";
            $surplus_lo2x = $surplus_lo2 * -1;
            $lo8 = ")";
        } else {
            $lo7 = "";
            $surplus_lo2x = $surplus_lo2;
            $lo8 = "";
        }
        $surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

        $surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
        if ($surplus_lo_lalu2 < 0) {
            $lo9 = "(";
            $surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
            $lo10 = ")";
        } else {
            $lo9 = "";
            $surplus_lo_lalu2x = $surplus_lo_lalu2;
            $lo10 = "";
        }
        $surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

        $selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
        if ($selisih_surplus_lo2 < 0) {
            $lo11 = "(";
            $selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
            $lo12 = ")";
        } else {
            $lo11 = "";
            $selisih_surplus_lo2x = $selisih_surplus_lo2;
            $lo12 = "";
        }
        $selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

        if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
            $persen3 = '0,00';
        } else {
            $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
            $persen3 = number_format($persen3, "2", ",", ".");
        }

        $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;
        if ($surplus_lo3 < 0) {
            $lo13 = "(";
            $surplus_lo3x = $surplus_lo3 * -1;
            $lo14 = ")";
        } else {
            $lo13 = "";
            $surplus_lo3x = $surplus_lo3;
            $lo14 = "";
        }
        $surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
        if ($surplus_lo_lalu3 < 0) {
            $lo15 = "(";
            $surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
            $lo16 = ")";
        } else {
            $lo15 = "";
            $surplus_lo_lalu3x = $surplus_lo_lalu3;
            $lo16 = "";
        }
        $surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
        if ($selisih_surplus_lo3 < 0) {
            $lo17 = "(";
            $selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
            $lo18 = ")";
        } else {
            $lo17 = "";
            $selisih_surplus_lo3x = $selisih_surplus_lo3;
            $lo18 = "";
        }
        $selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

        if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
            $persen4 = '0,00';
        } else {
            $persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
            $persen4 = number_format($persen4, "2", ",", ".");
        }


        $sqllo13 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74') and kd_skpd='$kd_skpd'";
        $querylo13 = $this->db->query($sqllo13);
        $penlo11 = $querylo13->row();
        $pen_lo11 = $penlo11->nilai;
        $pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

        $sqllo14 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74') and kd_skpd='$kd_skpd'";
        $querylo14 = $this->db->query($sqllo14);
        $penlo12 = $querylo14->row();
        $pen_lo_lalu12 = $penlo12->nilai;
        $pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

        $sqllo15 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('83') and kd_skpd='$kd_skpd'";
        $querylo15 = $this->db->query($sqllo15);
        $bello13 = $querylo15->row();
        $bel_lo13 = $bello13->nilai;
        $bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

        $sqllo16 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83') and kd_skpd='$kd_skpd'";
        $querylo16 = $this->db->query($sqllo16);
        $bello14 = $querylo16->row();
        $bel_lo_lalu14 = $bello14->nilai;
        $bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

        $surplus_lo4 = $pen_lo11 - $bel_lo13;
        if ($surplus_lo4 < 0) {
            $lo19 = "(";
            $surplus_lo4x = $surplus_lo4 * -1;
            $lo20 = ")";
        } else {
            $lo19 = "";
            $surplus_lo4x = $surplus_lo4;
            $lo20 = "";
        }
        $surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

        $surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
        if ($surplus_lo_lalu4 < 0) {
            $lo21 = "(";
            $surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
            $lo22 = ")";
        } else {
            $lo21 = "";
            $surplus_lo_lalu4x = $surplus_lo_lalu4;
            $lo22 = "";
        }
        $surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

        $selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
        if ($selisih_surplus_lo4 < 0) {
            $lo23 = "(";
            $selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
            $lo24 = ")";
        } else {
            $lo23 = "";
            $selisih_surplus_lo4x = $selisih_surplus_lo4;
            $lo24 = "";
        }
        $selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

        if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
            $persen5 = '0,00';
        } else {
            $persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
            $persen5 = number_format($persen5, "2", ",", ".");
        }


        $sqllo17 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75') and kd_skpd='$kd_skpd'";
        $querylo17 = $this->db->query($sqllo17);
        $penlo15 = $querylo17->row();
        $pen_lo15 = $penlo15->nilai;
        $pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

        $sqllo18 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75') and kd_skpd='$kd_skpd'";
        $querylo18 = $this->db->query($sqllo18);
        $penlo16 = $querylo18->row();
        $pen_lo_lalu16 = $penlo16->nilai;
        $pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

        $sqllo19 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84') and kd_skpd='$kd_skpd'";
        $querylo19 = $this->db->query($sqllo19);
        $bello17 = $querylo19->row();
        $bel_lo17 = $bello17->nilai;
        $bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

        $sqllo20 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and kd_skpd='$kd_skpd'";
        $querylo20 = $this->db->query($sqllo20);
        $bello18 = $querylo20->row();
        $bel_lo_lalu18 = $bello18->nilai;
        $bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

        $surplus_lo5 = $pen_lo15 - $bel_lo17;
        if ($surplus_lo5 < 0) {
            $lo25 = "(";
            $surplus_lo5x = $surplus_lo5 * -1;
            $lo26 = ")";
        } else {
            $lo25 = "";
            $surplus_lo5x = $surplus_lo5;
            $lo26 = "";
        }
        $surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

        $surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
        if ($surplus_lo_lalu5 < 0) {
            $lo27 = "(";
            $surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
            $lo28 = ")";
        } else {
            $lo27 = "";
            $surplus_lo_lalu5x = $surplus_lo_lalu5;
            $lo28 = "";
        }
        $surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

        $selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
        if ($selisih_surplus_lo5 < 0) {
            $lo29 = "(";
            $selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
            $lo30 = ")";
        } else {
            $lo29 = "";
            $selisih_surplus_lo5x = $selisih_surplus_lo5;
            $lo30 = "";
        }
        $selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

        if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
            $persen6 = '0,00';
        } else {
            $persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
            $persen6 = number_format($persen6, "2", ",", ".");
        }


        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $cRet = '';

        $sclient = $this->akuntansi_model->get_sclient();

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
					<tr>
						<td align=\"center\"><strong>$nm_skpd</strong></td>
					</tr>	
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo = "SELECT seq, nor, uraian, isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak FROM map_lo_prov_permen_77 
                   GROUP BY seq, nor, uraian, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet') ORDER BY seq";

        $querymaplo = $this->db->query($sqlmaplo);
        $no     = 0;

        foreach ($querymaplo->result() as $loquery) {

            $nama      = $loquery->uraian;
            $n         = $loquery->kode_1;
            $n           = ($n == "-" ? "'-'" : $n);
            $n2        = $loquery->kode_2;
            $n2           = ($n2 == "-" ? "'-'" : $n2);
            $n3        = $loquery->kode_3;
            $n3           = ($n3 == "-" ? "'-'" : $n3);
            $normal    = $loquery->cetak;

            $quelo01   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and kd_skpd='$kd_skpd'";
            $quelo02 = $this->db->query($quelo01);
            $quelo03 = $quelo02->row();
            $nil     = $quelo03->nilai;
            $nilai    = number_format($quelo03->nilai, "2", ",", ".");

            $quelo04   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'";
            $quelo05 = $this->db->query($quelo04);
            $quelo06 = $quelo05->row();
            $nil_lalu     = $quelo06->nilai;
            $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

            $real_nilai = $nil - $nil_lalu;
            if ($real_nilai < 0) {
                $lo0 = "(";
                $real_nilaix = $real_nilai * -1;
                $lo00 = ")";
            } else {
                $lo0 = "";
                $real_nilaix = $real_nilai;
                $lo00 = "";
            }
            $real_nilai1 = number_format($real_nilaix, "2", ",", ".");

            if ($nil_lalu == '' or $nil_lalu == 0) {
                $persen1 = '0,00';
            } else {
                $persen1 = ($nil / $nil_lalu) * 100;
                $persen1 = number_format($persen1, "2", ",", ".");
            }
            $no       = $no + 1;
            switch ($loquery->seq) {
                case 5:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 10:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 40:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 45:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 50:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 80:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 85:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 110:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 115:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 140:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 145:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 150:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 182:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 183:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 200:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 205:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 210:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 250:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 255:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo1$surplus_lo1$lo2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo3$surplus_lo_lalu1$lo4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo5$selisih_surplus_lo1$lo6</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen2</td>
                                 </tr>";
                    break;
                case 260:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 265:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 270:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 295:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 300:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 325:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo19$surplus_lo41$lo20</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo21$surplus_lo_lalu41$lo22</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo23$selisih_surplus_lo41$lo24</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen5</td>
                                 </tr>";
                    break;


                case 330:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 335:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo7$surplus_lo21$lo8</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo9$surplus_lo_lalu21$lo10</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo11$selisih_surplus_lo21$lo12</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen3</td>
                                 </tr>";
                    break;
                case 340:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 345:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 350:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 365:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 370:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 390:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 395:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo13$surplus_lo31$lo14</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo15$surplus_lo_lalu31$lo16</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo17$selisih_surplus_lo31$lo18</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen4</td>
                                 </tr>";
                    break;
                default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";
            }
        }

        // $cRet         .= "</table>";
        //        $data['prev']  = $cRet;
        //        $data['sikap'] = 'preview';
        //        
        //        
        //        
        //        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
        //        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
        //$this->template->load('template','anggaran/rka/perkadaII',$data); 

        $cRet .=       " </table>";

        $cRet .= '<TABLE width="100%" style="border-collapse:collapse;" border="0" cellspacing="0">
					<TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"></TD>
						<TD align="center" width="30%">' . $daerah . ', ' . $tanggal_ttd . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $jabatan . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%"><u><b>' . $nama_ttd . ' </b><br></u> ' . $pangkat . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $nip . '</TD>
					</TR>
					</TABLE><br/>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO SKPD $kd_skpd / $cbulan");
        $this->template->set('title', 'LO SKPD $kd_skpd / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LO SKPD $cbulan</title>");
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
            case 4;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
        }
    }

    function cetak_lra_lo_unit($cbulan = "", $kd_skpd = "", $pilih = 1, $ttd = "", $tgl_ttd = "")
    {
        //$id = $skpd;
        $cetak = '2'; //$ctk; 
        $id     = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;

        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tgl_ttd);

        $nip2 = str_replace('123456789', ' ', $ttd);

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$id' AND kode IN ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama_ttd = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }

        //$laporan=$lap; 

        // if ($cetak == '1') {
        //           $skpd = '';
        //           $skpd1 = '';           
        //       } else {             
        $skpd = "AND kd_skpd='$kd_skpd'";
        $skpd1 = "AND b.kd_skpd='$kd_skpd'";
        //}  

        if ($cbulan == 12) {
            $sumber_jurnal = "ju_calk";
        } else {
            $sumber_jurnal = "ju";
        }

        $y123 = ")";
        $x123 = "(";
        $sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_skpd;
        }
        $nm_skpd = strtoupper($nmskpd);
        // INSERT DATA

        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$kd_skpd'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns) {
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd  = $rowdns->kd_sk;
            $nm_skpd  = $rowdns->nm_sk;
        }

        // created by henri_tb
        $trhju = 'trhju_pkd';
        $trdju = 'trdju_pkd';
        $sqllo1 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73') and kd_skpd='$kd_skpd'";
        $querylo1 = $this->db->query($sqllo1);
        $penlo = $querylo1->row();
        $pen_lo = $penlo->nilai;
        $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

        $sqllo2 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and kd_skpd='$kd_skpd'";
        $querylo2 = $this->db->query($sqllo2);
        $penlo2 = $querylo2->row();
        $pen_lo_lalu = $penlo2->nilai;
        $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

        $sqllo3 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82') and kd_skpd='$kd_skpd'";
        $querylo3 = $this->db->query($sqllo3);
        $bello = $querylo3->row();
        $bel_lo = $bello->nilai;
        $bel_lo1 = number_format($bello->nilai, "2", ",", ".");

        $sqllo4 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82') and kd_skpd='$kd_skpd'";
        $querylo4 = $this->db->query($sqllo4);
        $bello2 = $querylo4->row();
        $bel_lo_lalu = $bello2->nilai;
        $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

        $surplus_lo = $pen_lo - $bel_lo;
        if ($surplus_lo < 0) {
            $lo1 = "(";
            $surplus_lox = $surplus_lo * -1;
            $lo2 = ")";
        } else {
            $lo1 = "";
            $surplus_lox = $surplus_lo;
            $lo2 = "";
        }
        $surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

        $surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
        if ($surplus_lo_lalu < 0) {
            $lo3 = "(";
            $surplus_lo_lalux = $surplus_lo_lalu * -1;
            $lo4 = ")";
        } else {
            $lo3 = "";
            $surplus_lo_lalux = $surplus_lo_lalu;
            $lo4 = "";
        }
        $surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

        $selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
        if ($selisih_surplus_lo < 0) {
            $lo5 = "(";
            $selisih_surplus_lox = $selisih_surplus_lo * -1;
            $lo6 = ")";
        } else {
            $lo5 = "";
            $selisih_surplus_lox = $selisih_surplus_lo;
            $lo6 = "";
        }
        $selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

        if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
            $persen2 = '0,00';
        } else {
            $persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
            $persen2 = number_format($persen2, "2", ",", ".");
        }

        $sqllo5 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74') and kd_skpd='$kd_skpd'";
        $querylo5 = $this->db->query($sqllo5);
        $penlo3 = $querylo5->row();
        $pen_lo3 = $penlo3->nilai;
        $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

        $sqllo6 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74') and kd_skpd='$kd_skpd'";
        $querylo6 = $this->db->query($sqllo6);
        $penlo4 = $querylo6->row();
        $pen_lo_lalu4 = $penlo4->nilai;
        $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

        $sqllo7 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
        $querylo7 = $this->db->query($sqllo7);
        $bello5 = $querylo7->row();
        $bel_lo5 = $bello5->nilai;
        $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

        $sqllo8 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
        $querylo8 = $this->db->query($sqllo8);
        $bello6 = $querylo8->row();
        $bel_lo_lalu6 = $bello6->nilai;
        $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

        $surplus_lo2 = $pen_lo3 - $bel_lo5;
        if ($surplus_lo2 < 0) {
            $lo7 = "(";
            $surplus_lo2x = $surplus_lo2 * -1;
            $lo8 = ")";
        } else {
            $lo7 = "";
            $surplus_lo2x = $surplus_lo2;
            $lo8 = "";
        }
        $surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

        $surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
        if ($surplus_lo_lalu2 < 0) {
            $lo9 = "(";
            $surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
            $lo10 = ")";
        } else {
            $lo9 = "";
            $surplus_lo_lalu2x = $surplus_lo_lalu2;
            $lo10 = "";
        }
        $surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

        $selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
        if ($selisih_surplus_lo2 < 0) {
            $lo11 = "(";
            $selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
            $lo12 = ")";
        } else {
            $lo11 = "";
            $selisih_surplus_lo2x = $selisih_surplus_lo2;
            $lo12 = "";
        }
        $selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

        if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
            $persen3 = '0,00';
        } else {
            $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
            $persen3 = number_format($persen3, "2", ",", ".");
        }

        $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;
        if ($surplus_lo3 < 0) {
            $lo13 = "(";
            $surplus_lo3x = $surplus_lo3 * -1;
            $lo14 = ")";
        } else {
            $lo13 = "";
            $surplus_lo3x = $surplus_lo3;
            $lo14 = "";
        }
        $surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
        if ($surplus_lo_lalu3 < 0) {
            $lo15 = "(";
            $surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
            $lo16 = ")";
        } else {
            $lo15 = "";
            $surplus_lo_lalu3x = $surplus_lo_lalu3;
            $lo16 = "";
        }
        $surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
        if ($selisih_surplus_lo3 < 0) {
            $lo17 = "(";
            $selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
            $lo18 = ")";
        } else {
            $lo17 = "";
            $selisih_surplus_lo3x = $selisih_surplus_lo3;
            $lo18 = "";
        }
        $selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

        if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
            $persen4 = '0,00';
        } else {
            $persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
            $persen4 = number_format($persen4, "2", ",", ".");
        }


        $sqllo13 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74') and kd_skpd='$kd_skpd'";
        $querylo13 = $this->db->query($sqllo13);
        $penlo11 = $querylo13->row();
        $pen_lo11 = $penlo11->nilai;
        $pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

        $sqllo14 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74') and kd_skpd='$kd_skpd'";
        $querylo14 = $this->db->query($sqllo14);
        $penlo12 = $querylo14->row();
        $pen_lo_lalu12 = $penlo12->nilai;
        $pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

        $sqllo15 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('83') and kd_skpd='$kd_skpd'";
        $querylo15 = $this->db->query($sqllo15);
        $bello13 = $querylo15->row();
        $bel_lo13 = $bello13->nilai;
        $bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

        $sqllo16 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83') and kd_skpd='$kd_skpd'";
        $querylo16 = $this->db->query($sqllo16);
        $bello14 = $querylo16->row();
        $bel_lo_lalu14 = $bello14->nilai;
        $bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

        $surplus_lo4 = $pen_lo11 - $bel_lo13;
        if ($surplus_lo4 < 0) {
            $lo19 = "(";
            $surplus_lo4x = $surplus_lo4 * -1;
            $lo20 = ")";
        } else {
            $lo19 = "";
            $surplus_lo4x = $surplus_lo4;
            $lo20 = "";
        }
        $surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

        $surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
        if ($surplus_lo_lalu4 < 0) {
            $lo21 = "(";
            $surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
            $lo22 = ")";
        } else {
            $lo21 = "";
            $surplus_lo_lalu4x = $surplus_lo_lalu4;
            $lo22 = "";
        }
        $surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

        $selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
        if ($selisih_surplus_lo4 < 0) {
            $lo23 = "(";
            $selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
            $lo24 = ")";
        } else {
            $lo23 = "";
            $selisih_surplus_lo4x = $selisih_surplus_lo4;
            $lo24 = "";
        }
        $selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

        if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
            $persen5 = '0,00';
        } else {
            $persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
            $persen5 = number_format($persen5, "2", ",", ".");
        }


        $sqllo17 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75') and kd_skpd='$kd_skpd'";
        $querylo17 = $this->db->query($sqllo17);
        $penlo15 = $querylo17->row();
        $pen_lo15 = $penlo15->nilai;
        $pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

        $sqllo18 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75') and kd_skpd='$kd_skpd'";
        $querylo18 = $this->db->query($sqllo18);
        $penlo16 = $querylo18->row();
        $pen_lo_lalu16 = $penlo16->nilai;
        $pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

        $sqllo19 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84') and kd_skpd='$kd_skpd'";
        $querylo19 = $this->db->query($sqllo19);
        $bello17 = $querylo19->row();
        $bel_lo17 = $bello17->nilai;
        $bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

        $sqllo20 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and kd_skpd='$kd_skpd'";
        $querylo20 = $this->db->query($sqllo20);
        $bello18 = $querylo20->row();
        $bel_lo_lalu18 = $bello18->nilai;
        $bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

        $surplus_lo5 = $pen_lo15 - $bel_lo17;
        if ($surplus_lo5 < 0) {
            $lo25 = "(";
            $surplus_lo5x = $surplus_lo5 * -1;
            $lo26 = ")";
        } else {
            $lo25 = "";
            $surplus_lo5x = $surplus_lo5;
            $lo26 = "";
        }
        $surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

        $surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
        if ($surplus_lo_lalu5 < 0) {
            $lo27 = "(";
            $surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
            $lo28 = ")";
        } else {
            $lo27 = "";
            $surplus_lo_lalu5x = $surplus_lo_lalu5;
            $lo28 = "";
        }
        $surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

        $selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
        if ($selisih_surplus_lo5 < 0) {
            $lo29 = "(";
            $selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
            $lo30 = ")";
        } else {
            $lo29 = "";
            $selisih_surplus_lo5x = $selisih_surplus_lo5;
            $lo30 = "";
        }
        $selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

        if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
            $persen6 = '0,00';
        } else {
            $persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
            $persen6 = number_format($persen6, "2", ",", ".");
        }


        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $cRet = '';

        $sclient = $this->akuntansi_model->get_sclient();

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
					<tr>
						<td align=\"center\"><strong>$nm_skpd</strong></td>
					</tr>	
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo = "SELECT seq, nor, uraian, isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak FROM map_lo_prov_permen_77 
                   GROUP BY seq, nor, uraian, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet') ORDER BY seq";

        $querymaplo = $this->db->query($sqlmaplo);
        $no     = 0;

        foreach ($querymaplo->result() as $loquery) {

            $nama      = $loquery->uraian;
            $n         = $loquery->kode_1;
            $n           = ($n == "-" ? "'-'" : $n);
            $n2        = $loquery->kode_2;
            $n2           = ($n2 == "-" ? "'-'" : $n2);
            $n3        = $loquery->kode_3;
            $n3           = ($n3 == "-" ? "'-'" : $n3);
            $normal    = $loquery->cetak;

            $quelo01   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and kd_skpd='$kd_skpd'";
            $quelo02 = $this->db->query($quelo01);
            $quelo03 = $quelo02->row();
            $nil     = $quelo03->nilai;
            $nilai    = number_format($quelo03->nilai, "2", ",", ".");

            $quelo04   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'";
            $quelo05 = $this->db->query($quelo04);
            $quelo06 = $quelo05->row();
            $nil_lalu     = $quelo06->nilai;
            $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

            $real_nilai = $nil - $nil_lalu;
            if ($real_nilai < 0) {
                $lo0 = "(";
                $real_nilaix = $real_nilai * -1;
                $lo00 = ")";
            } else {
                $lo0 = "";
                $real_nilaix = $real_nilai;
                $lo00 = "";
            }
            $real_nilai1 = number_format($real_nilaix, "2", ",", ".");

            if ($nil_lalu == '' or $nil_lalu == 0) {
                $persen1 = '0,00';
            } else {
                $persen1 = ($nil / $nil_lalu) * 100;
                $persen1 = number_format($persen1, "2", ",", ".");
            }
            $no       = $no + 1;
            switch ($loquery->seq) {
                case 5:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 10:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 40:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 45:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 50:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 80:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 85:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 110:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 115:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 140:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 145:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 150:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 182:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 183:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 200:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 205:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 210:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 250:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 255:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo1$surplus_lo1$lo2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo3$surplus_lo_lalu1$lo4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo5$selisih_surplus_lo1$lo6</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen2</td>
                                 </tr>";
                    break;
                case 260:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 265:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 270:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 295:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 300:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 325:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo19$surplus_lo41$lo20</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo21$surplus_lo_lalu41$lo22</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo23$selisih_surplus_lo41$lo24</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen5</td>
                                 </tr>";
                    break;


                case 330:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 335:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo7$surplus_lo21$lo8</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo9$surplus_lo_lalu21$lo10</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo11$selisih_surplus_lo21$lo12</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen3</td>
                                 </tr>";
                    break;
                case 340:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 345:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 350:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 365:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 370:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 390:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 395:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo13$surplus_lo31$lo14</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo15$surplus_lo_lalu31$lo16</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo17$selisih_surplus_lo31$lo18</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen4</td>
                                 </tr>";
                    break;
                default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";
            }
        }


        // $cRet         .= "</table>";
        //        $data['prev']  = $cRet;
        //        $data['sikap'] = 'preview';
        //        
        //        
        //        
        //        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
        //        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
        //$this->template->load('template','anggaran/rka/perkadaII',$data); 

        $cRet .=       " </table>";

        $cRet .= '<TABLE width="100%" style="border-collapse:collapse;" border="0" cellspacing="0">
					<TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"></TD>
						<TD align="center" width="30%">' . $daerah . ', ' . $tanggal_ttd . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $jabatan . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%"><u><b>' . $nama_ttd . ' </b><br></u> ' . $pangkat . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $nip . '</TD>
					</TR>
					</TABLE><br/>';


        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO UNIT $cbulan");
        $this->template->set('title', 'LO UNIT $cbulan');
        switch ($pilih) {
            case 1;
                // $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
                echo "<title>LO UNIT $cbulan</title>";
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
            case 4;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
        }
    }


    //================================================= END LO

    //================================================= Neraca
    function cetak_neraca()
    {
        $data['page_title'] = 'LAPORAN NERACA SKPD';
        $this->template->set('title', 'LAPORAN NERACA SKPD');
        $this->template->load('template', 'akuntansi/cetak_neraca', $data);
    }

    function rpt_neraca($cbulan = "", $pilih = 1)
    {
        $id       = $this->session->userdata('kdskpd');
        $thn_ang    = $this->session->userdata('pcThang');
        $thn_ang_1    = $thn_ang - 1;
        $kd_skpd    = $id;
        $bulan     = $cbulan;
        $cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

        $sqlsc = "SELECT nm_org FROM ms_organisasi where kd_org=left('$kd_skpd',17) ";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {

            $nmskpd  = $rowsc->nm_org;
        }

        $nm_skpd  = strtoupper($nmskpd);

        /*		       $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
             $sqlsclient=$this->db->query($sqlsc);
             foreach ($sqlsclient->result() as $rowsc)
            {
               
                $tgl=$rowsc->tgl_rka;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
                $thn     = $rowsc->thn_ang;
            } 

        $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE a.kd_skpd='$skpd'  ";
             $sqlskpd=$this->db->query($sqldns);
             foreach ($sqlskpd->result() as $rowdns)
            {
                $kd_urusan=$rowdns->kd_u;                    
                $nm_urusan= $rowdns->nm_u;
                $kd_skpd  = $rowdns->kd_sk;
                $nm_skpd  = $rowdns->nm_sk;
            } 
*/
        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $cRet = '';

        $sclient = $this->akuntansi_model->get_sclient();
        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                <tr>
                     <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                </tr>
                <tr>
                     <td align=\"center\"><strong>$nm_skpd</strong></td>                         
                </tr>
                <TR>
                    <td align=\"center\"><strong>NERACA</strong></td>
                </TR>
                <TR>
                    <td align=\"center\"><strong>PER $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1 </strong></td>
                </TR>
                </TABLE><br>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                 <thead>                       
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"55%\" align=\"center\"><b>URAIAN</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>                            
                    </tr>
                    
                 </thead>
                 <tfoot>
                    <tr>
                        <td style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>                                             
                     </tr>
                 </tfoot>
               
                 <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                       
                    </tr>";


        //level 1

        // Created by Henri_TB
        $trhju = 'trhju_pkd';
        $trdju = 'trdju_pkd';
        $ekuitas = '310101010001';
        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
  ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
  and b.tabel=1 and reev=0 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'");
        foreach ($query3->result_array() as $res2) {
            $debet3 = $res2['debet'];
            $kredit3 = $res2['kredit'];
        }

        $real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

        //    created by henri_tb
        $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02' ";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

        $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

        $sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;

        $sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlutang_lalu);
        foreach ($hasil->result() as $row) {
            $nilaiutang_lalu  = $row->thn_m1;
        }

        $sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlkas_lalu);
        foreach ($hasil->result() as $row) {
            $rk_ppkd_lalu  = $row->thn_m1;
        }

        $sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlskpd_lalu);
        foreach ($hasil->result() as $row) {
            $rk_skpd_lalu  = $row->thn_m1;
        }

        $sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllcr_lalu);
        foreach ($hasil->result() as $row) {
            $lcrx_lalu  = $row->thn_m1;
        }

        $sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlast_lalu);
        foreach ($hasil->result() as $row) {
            $astx_lalu  = $row->thn_m1;
        }

        $lcr_lalu   = $lcrx_lalu - $rk_skpd_lalu;
        $ast_lalu   = $astx_lalu - $rk_skpd_lalu;
        $eku_lalu     = $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
        $eku_tang_lalu  = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

        $sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher
  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlutang);
        foreach ($hasil->result() as $row) {
            $nilaiutang  = $row->thn_m1;
        }

        $sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlkas);
        foreach ($hasil->result() as $row) {
            $rk_ppkd  = $row->thn_m1;
        }

        $sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlskpd);
        foreach ($hasil->result() as $row) {
            $rk_skpd  = $row->thn_m1;
        }

        $sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllcr);
        foreach ($hasil->result() as $row) {
            $lcrx = $row->thn_m1;
        }

        $sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlast);
        foreach ($hasil->result() as $row) {
            $astx  = $row->thn_m1;
        }

        $lcr      = $lcrx - $rk_skpd;
        $ast      = $astx - $rk_skpd;
        $eku      = $sal_akhir - $rk_ppkd + $rk_skpd;
        $eku_tang     = $sal_akhir + $nilaiutang - $rk_ppkd + $rk_skpd;

        if ($sal_akhir < 0) {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else {
            $c = "";
            $sal_akhir;
            $d = "";
        }

        $sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

        if ($sal_awal < 0) {
            $c1 = "(";
            $sal_awal = $sal_awal * -1;
            $d1 = ")";
        } else {
            $c1 = "";
            $sal_awal;
            $d1 = "";
        }

        $sal_awal1 = number_format($sal_awal, "2", ",", ".");


        if ($eku_lalu < 0) {
            $min001 = "(";
            $eku_lalu = $eku_lalu * -1;
            $min002 = ")";
        } else {
            $min001 = "";
            $eku_lalu;
            $min002 = "";
        }

        $eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

        if ($eku < 0) {
            $min003 = "(";
            $eku = $eku * -1;
            $min004 = ")";
        } else {
            $min003 = "";
            $eku;
            $min004 = "";
        }

        $eku1 = number_format($eku, "2", ",", ".");

        if ($eku_tang_lalu < 0) {
            $min005 = "(";
            $eku_tang_lalu = $eku_tang_lalu * -1;
            $min006 = ")";
        } else {
            $min005 = "";
            $eku_tang_lalu;
            $min006 = "";
        }

        $eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

        if ($eku_tang < 0) {
            $min007 = "(";
            $eku_tang = $eku_tang * -1;
            $min008 = ")";
        } else {
            $min007 = "";
            $eku_tang;
            $min008 = "";
        }

        $eku_tang1 = number_format($eku_tang, "2", ",", ".");

        if ($rk_ppkd_lalu < 0) {
            $min009 = "(";
            $rk_ppkd_lalu = $rk_ppkd_lalu * -1;
            $min010 = ")";
        } else {
            $min009 = "";
            $rk_ppkd_lalu;
            $min010 = "";
        }

        $rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

        if ($rk_ppkd < 0) {
            $min013 = "(";
            $rk_ppkd = $rk_ppkd * -1;
            $min014 = ")";
        } else {
            $min013 = "";
            $rk_ppkd;
            $min014 = "";
        }

        $rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

        if ($lcr < 0) {
            $min015 = "(";
            $lcr = $lcr * -1;
            $min016 = ")";
        } else {
            $min015 = "";
            $lcr;
            $min016 = "";
        }

        $lcr1 = number_format($lcr, "2", ",", ".");

        if ($lcr_lalu < 0) {
            $min017 = "(";
            $lcr_lalu = $lcr_lalu * -1;
            $min018 = ")";
        } else {
            $min017 = "";
            $lcr_lalu;
            $min018 = "";
        }

        $lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

        if ($ast < 0) {
            $min019 = "(";
            $ast = $ast * -1;
            $min020 = ")";
        } else {
            $min019 = "";
            $ast;
            $min020 = "";
        }

        $ast1 = number_format($ast, "2", ",", ".");

        if ($ast_lalu < 0) {
            $min021 = "(";
            $ast_lalu = $ast_lalu * -1;
            $min022 = ")";
        } else {
            $min021 = "";
            $ast_lalu;
            $min022 = "";
        }

        $ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

        $queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
                isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
                isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
                FROM map_neraca_permen_77 ORDER BY seq ";

        $query10 = $this->db->query($queryneraca);

        $no     = 0;

        foreach ($query10->result_array() as $res) {
            $uraian = $res['uraian'];
            $normal = $res['normal'];

            $kode_1 = trim($res['kode_1']);
            $kode_2 = trim($res['kode_2']);
            $kode_3 = trim($res['kode_3']);
            $kode_4 = trim($res['kode_4']);
            $kode_5 = trim($res['kode_5']);
            $kode_6 = trim($res['kode_6']);
            $kode_7 = trim($res['kode_7']);
            $kode_8 = trim($res['kode_8']);
            $kode_9 = trim($res['kode_9']);
            $kode_10 = trim($res['kode_10']);
            $kode_11 = trim($res['kode_11']);
            $kode_12 = trim($res['kode_12']);
            $kode_13 = trim($res['kode_13']);
            $kode_14 = trim($res['kode_14']);
            $kode_15 = trim($res['kode_15']);


            $q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
              and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02' and
                (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                kd_rek6 like '$kode_15%') ");

            foreach ($q->result_array() as $r) {
                $debet = $r['debet'];
                $kredit = $r['kredit'];
            }

            if ($debet == '') $debet = 0;
            if ($kredit == '') $kredit = 0;

            if ($normal == 1) {
                $nl = $debet - $kredit;
            } else {
                $nl = $kredit - $debet;
            }
            if ($nl == '') $nl = 0;

            // Jurnal Tahun lalu
            $q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
              and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02' and
                (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                kd_rek6 like '$kode_15%') ");

            foreach ($q->result_array() as $rx) {
                $debet_lalu = $rx['debet'];
                $kredit_lalu = $rx['kredit'];
            }

            if ($debet_lalu == '') $debet_lalu = 0;
            if ($kredit_lalu == '') $kredit_lalu = 0;

            if ($normal == 1) {
                $sblm = $debet_lalu - $kredit_lalu;
            } else {
                $sblm = $kredit_lalu - $debet_lalu;
            }
            if ($sblm == '') $sblm = 0;

            if ($nl < 0) {
                $nl001 = "(";
                $nl = $nl * -1;
                $ln001 = ")";
            } else {
                $nl001 = "";
                $ln001 = "";
            }
            if ($sblm < 0) {
                $sblm001 = "(";
                $sblm = $sblm * -1;
                $mlbs001 = ")";
            } else {
                $sblm001 = "";
                $mlbs001 = "";
            }
            $nl1 = number_format($nl, "2", ",", ".");
            $sblm1 = number_format($sblm, "2", ",", ".");

            $no       = $no + 1;

            switch ($res['seq']) {
                case 5:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
                             </tr>";
                    break;
                case 10:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min015$lcr1$min016</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
                             </tr>";
                    break;
                case 15:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 60:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 65:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 100:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 105:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                    /* case 90:
                      $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>"; 
                              break;        
      case 100:
                     $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;*/

                case 110:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 115:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 120:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 125:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                case 155:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 170:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 175:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                             </tr>";
                    break;

                case 180:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 225:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 240:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 245:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                    /*case 250: //di 2020 , ekuitas dan kewajiban

                     $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                             </tr>";
                    break;*/



                case 260:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 265:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 270:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 275:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 280:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                case 285:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 290:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
                             </tr>";
                    break;



                case 295:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;


                case 335:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                case 365:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
                             </tr>";
                    break;
                case 400:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                             </tr>";
                    break;
                default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
            }
        }


        $cRet .= '</table>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("NERACA SKPD $id / $cbulan");
        $this->template->set('title', ("NERACA SKPD $id / $cbulan"));
        switch ($pilih) {
            case 1;
                echo ("<title>NERACA SKPD $cbulan</title>");
                echo $cRet;
                break;
            case 4;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
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

    // Neraca SKPD
    function rpt_neraca_unit($cbulan = "", $kd_skpd = "", $pilih = 1, $ttd = "", $tgl_ttd = "")
    {
        $id           = $this->session->userdata('kdskpd');
        $thn_ang    = $this->session->userdata('pcThang');
        $thn_ang_1    = $thn_ang - 1;
        $bulan     = $cbulan;
        $cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tgl_ttd);

        $nip2 = str_replace('123456789', ' ', $ttd);

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$id' AND kode IN ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama_ttd = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }


        $sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {

            $nmskpd  = $rowsc->nm_skpd;
        }

        $nm_skpd    = strtoupper($nmskpd);

        /*		       $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
             $sqlsclient=$this->db->query($sqlsc);
             foreach ($sqlsclient->result() as $rowsc)
            {
               
                $tgl=$rowsc->tgl_rka;
                $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                $kab     = $rowsc->kab_kota;
                $daerah  = $rowsc->daerah;
                $thn     = $rowsc->thn_ang;
            } 

        $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE a.kd_skpd='$skpd'  ";
             $sqlskpd=$this->db->query($sqldns);
             foreach ($sqlskpd->result() as $rowdns)
            {
                $kd_urusan=$rowdns->kd_u;                    
                $nm_urusan= $rowdns->nm_u;
                $kd_skpd  = $rowdns->kd_sk;
                $nm_skpd  = $rowdns->nm_sk;
            } 
        */
        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $cRet = '';
        $sclient = $this->akuntansi_model->get_sclient();

        $cRet = "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                <tr>
                    <td rowspan=\"4\" align=\"left\" width=\"2%\">
                    <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
					</td>
                     <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                </tr>
                <tr>
                     <td align=\"center\"><strong>$nm_skpd</strong></td>                         
                </tr>
                <TR>
                    <td align=\"center\"><strong>NERACA</strong></td>
                </TR>
                <TR>
                    <td align=\"center\"><strong>PER $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1 </strong></td>
                </TR>
                </TABLE><br>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                 <thead>                       
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"55%\" align=\"center\"><b>URAIAN</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>                            
                    </tr>
                    
                 </thead>
                 <tfoot>
                    <tr>
                        <td style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>                                             
                     </tr>
                 </tfoot>
               
                 <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                       
                    </tr>";


        //level 1

        // Created by Henri_TB
        $trhju = 'trhju_pkd';
        $trdju = 'trdju_pkd';
        $ekuitas = '310101010001';
        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and kd_skpd='$kd_skpd'";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
        ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
        and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");
        foreach ($query3->result_array() as $res2) {
            $debet3 = $res2['debet'];
            $kredit3 = $res2['kredit'];
        }

        $real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

        //    created by henri_tb
        $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd' ";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

        $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

        $sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
      inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sqleku = "SELECT  7 nor,'EKUITAS' uraian,0 parent,35 seq,'3101'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan  and left(kd_skpd,22)=left('$kd_skpd',22)"; //Henri_TB

        $hasil = $this->db->query($sqleku);
        $nawal = 0;
        foreach ($hasil->result() as $row004) {
            $kd_rek   = $row004->nor;
            $parent   = $row004->parent;
            $nama     = $row004->uraian;
            $nilaiEKU  = $row004->thn_m1;
        }

        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $nilaiEKU;

        $sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlutang_lalu);
        foreach ($hasil->result() as $row) {
            $nilaiutang_lalu  = $row->thn_m1;
        }

        $sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
     and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlkas_lalu);
        foreach ($hasil->result() as $row) {
            $rk_ppkd_lalu  = $row->thn_m1;
        }

        $sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlskpd_lalu);
        foreach ($hasil->result() as $row) {
            $rk_skpd_lalu  = $row->thn_m1;
        }

        $sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllcr_lalu);
        foreach ($hasil->result() as $row) {
            $lcrx_lalu  = $row->thn_m1;
        }

        $sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlast_lalu);
        foreach ($hasil->result() as $row) {
            $astx_lalu  = $row->thn_m1;
        }

        $lcr_lalu   = $lcrx_lalu - $rk_skpd_lalu;
        $ast_lalu   = $astx_lalu - $rk_skpd_lalu;
        $eku_lalu     = $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
        $eku_tang_lalu  = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

        $sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher
        and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlutang);
        foreach ($hasil->result() as $row) {
            $nilaiutang  = $row->thn_m1;
        }

        $sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlkas);
        foreach ($hasil->result() as $row) {
            $rk_ppkd  = $row->thn_m1;
        }

        $sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlskpd);
        foreach ($hasil->result() as $row) {
            $rk_skpd  = $row->thn_m1;
        }

        $sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllcr);
        foreach ($hasil->result() as $row) {
            $lcrx = $row->thn_m1;
        }

        $sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlast);
        foreach ($hasil->result() as $row) {
            $astx  = $row->thn_m1;
        }

        // $lcr      = $lcrx - $rk_skpd;
        // $ast      = $astx - $rk_skpd;
        // $eku      = $sal_akhir + $rk_ppkd;
        // $eku_tang     = $sal_akhir + $nilaiutang - $rk_ppkd + $rk_skpd;
        $lcr    = $lcrx - $rk_skpd;
        $ast    = $astx - $rk_skpd;
        $eku    = $sal_akhir + $rk_ppkd;
        $eku_tang   = $sal_akhir + $nilaiutang + $rk_ppkd;

        if ($sal_akhir < 0) {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else {
            $c = "";
            $sal_akhir;
            $d = "";
        }

        $sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

        if ($sal_awal < 0) {
            $c1 = "(";
            $sal_awal = $sal_awal * -1;
            $d1 = ")";
        } else {
            $c1 = "";
            $sal_awal;
            $d1 = "";
        }

        $sal_awal1 = number_format($sal_awal, "2", ",", ".");


        if ($eku_lalu < 0) {
            $min001 = "(";
            $eku_lalu = $eku_lalu * -1;
            $min002 = ")";
        } else {
            $min001 = "";
            $eku_lalu;
            $min002 = "";
        }

        $eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

        if ($eku < 0) {
            $min003 = "(";
            $eku = $eku * -1;
            $min004 = ")";
        } else {
            $min003 = "";
            $eku;
            $min004 = "";
        }

        $eku1 = number_format($eku, "2", ",", ".");

        if ($eku_tang_lalu < 0) {
            $min005 = "(";
            $eku_tang_lalu = $eku_tang_lalu * -1;
            $min006 = ")";
        } else {
            $min005 = "";
            $eku_tang_lalu;
            $min006 = "";
        }

        $eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

        if ($eku_tang < 0) {
            $min007 = "(";
            $eku_tang = $eku_tang * -1;
            $min008 = ")";
        } else {
            $min007 = "";
            $eku_tang;
            $min008 = "";
        }

        $eku_tang1 = number_format($eku_tang, "2", ",", ".");

        if ($rk_ppkd_lalu < 0) {
            $min009 = "(";
            $rk_ppkd_lalu = $rk_ppkd_lalu * -1;
            $min010 = ")";
        } else {
            $min009 = "";
            $rk_ppkd_lalu;
            $min010 = "";
        }

        $rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

        if ($rk_ppkd < 0) {
            $min013 = "(";
            $rk_ppkd = $rk_ppkd * -1;
            $min014 = ")";
        } else {
            $min013 = "";
            $rk_ppkd;
            $min014 = "";
        }

        $rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

        if ($lcr < 0) {
            $min015 = "(";
            $lcr = $lcr * -1;
            $min016 = ")";
        } else {
            $min015 = "";
            $lcr;
            $min016 = "";
        }

        $lcr1 = number_format($lcr, "2", ",", ".");

        if ($lcr_lalu < 0) {
            $min017 = "(";
            $lcr_lalu = $lcr_lalu * -1;
            $min018 = ")";
        } else {
            $min017 = "";
            $lcr_lalu;
            $min018 = "";
        }

        $lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

        if ($ast < 0) {
            $min019 = "(";
            $ast = $ast * -1;
            $min020 = ")";
        } else {
            $min019 = "";
            $ast;
            $min020 = "";
        }

        $ast1 = number_format($ast, "2", ",", ".");

        if ($ast_lalu < 0) {
            $min021 = "(";
            $ast_lalu = $ast_lalu * -1;
            $min022 = ")";
        } else {
            $min021 = "";
            $ast_lalu;
            $min022 = "";
        }

        $ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

        $queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
                isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
                isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
                FROM map_neraca_permen_77 ORDER BY seq ";

        $query10 = $this->db->query($queryneraca);

        $no     = 0;

        foreach ($query10->result_array() as $res) {
            $uraian = $res['uraian'];
            $normal = $res['normal'];

            $kode_1 = trim($res['kode_1']);
            $kode_2 = trim($res['kode_2']);
            $kode_3 = trim($res['kode_3']);
            $kode_4 = trim($res['kode_4']);
            $kode_5 = trim($res['kode_5']);
            $kode_6 = trim($res['kode_6']);
            $kode_7 = trim($res['kode_7']);
            $kode_8 = trim($res['kode_8']);
            $kode_9 = trim($res['kode_9']);
            $kode_10 = trim($res['kode_10']);
            $kode_11 = trim($res['kode_11']);
            $kode_12 = trim($res['kode_12']);
            $kode_13 = trim($res['kode_13']);
            $kode_14 = trim($res['kode_14']);
            $kode_15 = trim($res['kode_15']);


            $q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
              and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd' and
                (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                kd_rek6 like '$kode_15%') ");

            foreach ($q->result_array() as $r) {
                $debet = $r['debet'];
                $kredit = $r['kredit'];
            }

            if ($debet == '') $debet = 0;
            if ($kredit == '') $kredit = 0;

            if ($normal == 1) {
                $nl = $debet - $kredit;
            } else {
                $nl = $kredit - $debet;
            }
            if ($nl == '') $nl = 0;

            // Jurnal Tahun lalu
            $q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
              and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd' and
                (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                kd_rek6 like '$kode_15%') ");

            foreach ($q->result_array() as $rx) {
                $debet_lalu = $rx['debet'];
                $kredit_lalu = $rx['kredit'];
            }

            if ($debet_lalu == '') $debet_lalu = 0;
            if ($kredit_lalu == '') $kredit_lalu = 0;

            if ($normal == 1) {
                $sblm = $debet_lalu - $kredit_lalu;
            } else {
                $sblm = $kredit_lalu - $debet_lalu;
            }
            if ($sblm == '') $sblm = 0;

            if ($nl < 0) {
                $nl001 = "(";
                $nl = $nl * -1;
                $ln001 = ")";
            } else {
                $nl001 = "";
                $ln001 = "";
            }
            if ($sblm < 0) {
                $sblm001 = "(";
                $sblm = $sblm * -1;
                $mlbs001 = ")";
            } else {
                $sblm001 = "";
                $mlbs001 = "";
            }
            $nl1 = number_format($nl, "2", ",", ".");
            $sblm1 = number_format($sblm, "2", ",", ".");

            $no       = $no + 1;

            switch ($res['seq']) {
                case 5:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
                             </tr>";
                    break;
                case 10:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min015$lcr1$min016</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
                             </tr>";
                    break;
                case 15:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 60:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 65:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 100:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 105:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                    /* case 90:
                      $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>"; 
                              break;        
      case 100:
                     $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;*/

                case 110:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 115:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 120:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 125:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                case 155:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 170:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 175:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                             </tr>";
                    break;

                case 180:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 220:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 225:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 235:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 240:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 245:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                    /*case 250: //di 2020 , ekuitas dan kewajiban

                     $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                  <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                             </tr>";
                    break;*/



                case 260:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 265:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 270:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 275:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
                case 280:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                case 285:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 286:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min019$eku_tang1$min020</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min021$eku_tang_lalu1$min022</td>
                            </tr>";
                    break;
                case 290:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;



                case 295:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;


                case 335:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;

                case 365:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
                             </tr>";
                    break;
                case 400:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                             </tr>";
                    break;
                default:
                    $cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
            }
        }


        $cRet .= '</table>';

        $cRet .= '<TABLE width="100%" style="border-collapse:collapse;font-size:12px;font-family:Arial" border="0" cellspacing="0">
                <TR>
                    <TD align="center" width="50%"><b>&nbsp;</TD>
                    <TD align="center" width="50%"><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" width="50%"></TD>
                    <TD align="center" width="30%">Melawi, ' . $tanggal_ttd . '</TD>
                </TR>
                <TR>
                    <TD align="center" width="30%"></TD>
                    <TD align="center" width="30%">' . $jabatan . '</TD>
                </TR>
                <TR>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                    <TD align="center" width="30%"><b>&nbsp;</TD>
                </TR>
                <TR>
                    <TD align="center" width="30%"></TD>
                    <TD align="center" width="30%"><u><b>' . $nama_ttd . ' </b><br></u> ' . $pangkat . '</TD>
                </TR>
                <TR>
                    <TD align="center" width="30%"></TD>
                    <TD align="center" width="30%">' . $nip . '</TD>
                </TR>
                </TABLE><br/>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("NERACA UNIT $kd_skpd / $cbulan");
        $this->template->set('title', ("NERACA UNIT $kd_skpd / $cbulan"));
        switch ($pilih) {
            case 1;
                echo ("<title>NERACA UNIT $cbulan</title>");
                echo $cRet;
                break;
            case 4;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
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


    //================================================= End Neraca

    //================================================= Cetak LPE
    function cetak_lpe()
    {
        $data['page_title'] = 'LPE SKPD';
        $this->template->set('title', 'LPE SKPD');
        $this->template->load('template', 'akuntansi/cetak_lpe', $data);
    }

    function ctk_lpe($cbulan = "", $kd = "", $pilih = 1, $ttd = "", $tgl_ttd = "")
    {
        $id = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
        $kd_skpd     = $this->session->userdata('kdskpd');
        //$nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        //$nm_skpd =strtoupper($nmskpd);

        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tgl_ttd);

        $nip2 = str_replace('123456789', ' ', $ttd);

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$id' AND kode IN ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama_ttd = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }

        $thn_ang_1 = $thn_ang - 1;
        $cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;
        $bulan = $cbulan;

        $sqlsc = "SELECT case when left(kd_skpd,17) in ('1.20.02','1.20.03') then 'SEKRETARIAT DAERAH PROVINSI KALBAR'
				when left(kd_skpd,17) in ('1.20.01','1.20.04') then 'SEKRETARIAT DPRD PROVINSI KALBAR'
				else nm_skpd end nm_skpd FROM ms_skpd where kd_skpd=left('$kd_skpd',17)+'.0000'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_skpd;
        }
        $nm_skpd = strtoupper($nmskpd);

        /*$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
		*/
        $skpd = "AND kd_skpd='$kd_skpd'";
        $skpd1 = "AND b.kd_skpd='$kd_skpd'";

        // UPDATE LPE TAHUN LALU

        $sqllo10 = "select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_org=left('$kd_skpd',17)";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        // UPDATE LPE TAHUN LALU

        $sqllo10 = "select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM trdju a INNER JOIN trhju b 
      ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND YEAR(b.tgl_voucher)<'$thn_ang'
      and b.tabel=1 and reev=0 and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'");
        foreach ($query3->result_array() as $res2) {
            $debet = $res2['debet'];
            $kredit = $res2['kredit'];
        }

        $real = $kredit - $debet + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;
        //        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$real' WHERE nor = '1' ");
        //          }
        /*        
      
      $query3 = $this->db->query(" SELECT
                    SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
                  FROM
                    trdju_pkd a
                  INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
                  WHERE
                    b.kd_skpd = '$kd_skpd'
                  AND left(a.kd_rek6,1) = '9'
                  AND YEAR (b.tgl_voucher) < '$thn'");  
          foreach($query3->result_array() as $res21){
         $debet9=$res21['debet'];
         $kredit9=$res21['kredit'];
                         
       }
       
    $query3 = $this->db->query(" SELECT
                    SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
                  FROM
                    trdju_pkd a
                  INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
                  WHERE
                    b.kd_skpd = '$kd_skpd'
                  AND left(a.kd_rek6,1) = '8'
                  AND YEAR (b.tgl_voucher) < '$thn'");  
          foreach($query3->result_array() as $res22){
         $debet8=$res22['debet'];
         $kredit8=$res22['kredit'];
                         
       }   
       
    $surplus1_1=($kredit8-$debet8)-($debet9-$kredit9);
    $surplus1=number_format($surplus1_1, "2", ".", "");
*/
        //        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$surplus1' WHERE nor = '2'");

        //    $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$akhir' WHERE nor = '7'");


        // end tahun lalu     

        /*        $sqlsawal = "SELECT * FROM map_lpe_skpd where nor='7'";
        $queryawal = $this->db->query($sqlsawal);
        $jmlsaldo = $queryawal->row();
        $jmlsal = $jmlsaldo->thn_m1;
        
        $sql41 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,1)='8' $skpd";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");
*/
        //    created by henri_tb
        $sqllo9 = "select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;


        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;
        /*    
        $sql51 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,1)='9'";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $sql523 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,3)='923'";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,2)='71'";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $sql62 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,2)='72'";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $surplus = $jmlpendapatan - $jmlbelanja;
*/
        $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
          inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org=left('$kd_skpd',17) AND kd_skpd<>'5.02.0.00.0.00.02.0000'"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        /*        $biaya_net = $jmlpmasuk - $jmlpkeluar;        
        $silpa = ($jmlpendapatan + $jmlpmasuk) - ($jmlbelanja + $jmlpkeluar);
        if ($silpa < 0)
        {
            $a = "(";
            $silpa1 = $silpa * -1;
            $b = ")";
        } else
        {
            $a = "";
            $silpa1 = $silpa;
            $b = "";
        }
*/
        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;

        if ($surplus_lo_lalu3 < 0) {
            $lo15 = "(";
            $surplus_lo_lalu3 = $surplus_lo_lalu3 * -1;
            $lo16 = ")";
        } else {
            $lo15 = "";
            $lo16 = "";
        }
        $surplus_lo_lalu31 = number_format($surplus_lo_lalu3, "2", ",", ".");

        if ($selisih_surplus_lo3 < 0) {
            $lo17 = "(";
            $selisih_surplus_lo3 = $selisih_surplus_lo3 * -1;
            $lo18 = ")";
        } else {
            $lo17 = "";
            $lo18 = "";
        }
        $selisih_surplus_lo31 = number_format($selisih_surplus_lo3, "2", ",", ".");

        if ($lpe_lalu1 < 0) {
            $lalu1 = "(";
            $lpe_lalu1 = $lpe_lalu1 * -1;
            $lalu2 = ")";
        } else {
            $lalu1 = "";
            $lpe_lalu1;
            $lalu2 = "";
        }

        if ($lpe_lalu2 < 0) {
            $lalu3 = "(";
            $lpe_lalu2 = $lpe_lalu2 * -1;
            $lalu4 = ")";
        } else {
            $lalu3 = "";
            $lpe_lalu2;
            $lalu4 = "";
        }

        if ($lpe_lalu3 < 0) {
            $lalu5 = "(";
            $lpe_lalu3 = $lpe_lalu3 * -1;
            $lalu6 = ")";
        } else {
            $lalu5 = "";
            $lpe_lalu3;
            $lalu6 = "";
        }

        if ($nilaiDR < 0) {
            $l000 = "(";
            $nilaiDR = $nilaiDR * -1;
            $p000 = ")";
        } else {
            $l000 = "";
            $nilaiDR;
            $p000 = "";
        }

        if ($nilailpe1 < 0) {
            $l001 = "(";
            $nilailpe1 = $nilailpe1 * -1;
            $p001 = ")";
        } else {
            $l001 = "";
            $nilailpe1;
            $p001 = "";
        }

        if ($nilailpe2 < 0) {
            $l002 = "(";
            $nilailpe2 = $nilailpe2 * -1;
            $p002 = ")";
        } else {
            $l002 = "";
            $nilailpe2;
            $p002 = "";
        }

        if ($surplus_lo3 < 0) {
            $lo13 = "(";
            $surplus_lo3 = $surplus_lo3 * -1;
            $lo14 = ")";
        } else {
            $lo13 = "";
            $lo14 = "";
        }
        $surplus_lo31 = number_format($surplus_lo3, "2", ",", ".");

        if ($sal_akhir < 0) {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else {
            $c = "";
            $sal_akhir;
            $d = "";
        }

        if ($sal_awal < 0) {
            $c1 = "(";
            $sal_awal = $sal_awal * -1;
            $d1 = ")";
        } else {
            $c1 = "";
            $sal_awal;
            $d1 = "";
        }

        if ($real < 0) {
            $cx = "(";
            $real = $real * -1;
            $dx = ")";
        } else {
            $cx = "";
            $real;
            $dx = "";
        }

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);


        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>PEMERINTAH KABUPATEN MELAWI</strong></td>                         
                    </tr>
					<tr>
                         <td align=\"center\"><strong>$nm_skpd</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN PERUBAHAN EKUITAS</strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
					<tr>
                         <td align=\"center\"><strong>&nbsp;</strong></td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                        
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                        </tr>";

        $sql = "SELECT * FROM map_lpe_skpd  ORDER BY seq";

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row) {

            $kd_rek   = $row->nor;
            $parent   = $row->parent;
            $nama     = $row->uraian;
            $nilai_1    = $row->thn_m1;


            /*        if ($nilai_1 < 0)
        {
            $tx = "(";
            $nilai_1 = $nilai_1 * -1;
            $ty = ")";
        } else
        {
            $tx = "";
            $ty = "";
        }
*/
            switch ($kd_rek) {
                case 1:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$cx" . number_format($real, "2", ",", ".") . "$dx</td>
                                                     </tr>";

                    break;
                case 2:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $lo13" . number_format($surplus_lo3, "2", ",", ".") . "$lo14</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lo15" . number_format($surplus_lo_lalu3, "2", ",", ".") . "$lo16</td>
                                                     </tr>";

                    break;
                case 3:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";

                    break;
                case 4:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l001" . number_format($nilailpe1, "2", ",", ".") . "$p001</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu1" . number_format($lpe_lalu2, "2", ",", ".") . "$lalu2</td>
                                                     </tr>";
                    break;
                case 5:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l000" . number_format($nilaiDR, "2", ",", ".") . "$p000</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu3" . number_format($lpe_lalu1, "2", ",", ".") . "$lalu4</td>
                                                     </tr>";
                    break;
                case 6:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l002" . number_format($nilailpe2, "2", ",", ".") . "$p002</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu5" . number_format($lpe_lalu3, "2", ",", ".") . "$lalu6</td>
                                                     </tr>";
                    break;
                case 7:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c" . number_format($sal_akhir, "2", ",", ".") . "$d</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
                                                     </tr>";
            }
        }


        $cRet .= '</table>';

        $cRet .= '<TABLE width="100%" style="border-collapse:collapse;" border="0" cellspacing="0">
					<TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"></TD>
						<TD align="center" width="30%">' . $daerah . ', ' . $tanggal_ttd . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $jabatan . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%"><u><b>' . $nama_ttd . ' </b><br></u> ' . $pangkat . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $nip . '</TD>
					</TR>
					</TABLE><br/>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("LPE SKPD $kd_skpd / $cbulan");
        $this->template->set('title', 'LPE SKPD $kd_skpd / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LPE SKPD $cbulan</title>");
                echo $cRet;
                break;
            case 4;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
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


    function ctk_lpe_unit($cbulan = "", $kd_skpd = "", $cetak = 1, $ttd = "", $tgl_ttd = "")
    {
        $id = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
        $bulan     = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $nm_skpd = strtoupper($nmskpd);
        $thn_ang_1 = $thn_ang - 1;

        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tgl_ttd);

        $nip2 = str_replace('123456789', ' ', $ttd);

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where nip='$nip2' AND kd_skpd='$id' AND kode IN ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama_ttd = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }


        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $prov     = $rowsc->provinsi;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }

        $skpd = "AND kd_skpd='$id1'";
        $skpd1 = "AND b.kd_skpd='$id1'";

        // UPDATE LPE TAHUN LALU
        $trhju = 'trhju_pkd';
        $trdju = 'trdju_pkd';
        $ekuitas = '310101010001';
        $sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
			ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
			and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");
        foreach ($query3->result_array() as $res2) {
            $debet = $res2['debet'];
            $kredit = $res2['kredit'];
        }

        $real = $kredit - $debet + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

        //		created by henri_tb
        $sqllo9 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;


        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

        $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;

        if ($surplus_lo_lalu3 < 0) {
            $lo15 = "(";
            $surplus_lo_lalu3 = $surplus_lo_lalu3 * -1;
            $lo16 = ")";
        } else {
            $lo15 = "";
            $lo16 = "";
        }
        $surplus_lo_lalu31 = number_format($surplus_lo_lalu3, "2", ",", ".");

        if ($selisih_surplus_lo3 < 0) {
            $lo17 = "(";
            $selisih_surplus_lo3 = $selisih_surplus_lo3 * -1;
            $lo18 = ")";
        } else {
            $lo17 = "";
            $lo18 = "";
        }
        $selisih_surplus_lo31 = number_format($selisih_surplus_lo3, "2", ",", ".");

        if ($lpe_lalu1 < 0) {
            $lalu1 = "(";
            $lpe_lalu1 = $lpe_lalu1 * -1;
            $lalu2 = ")";
        } else {
            $lalu1 = "";
            $lpe_lalu1;
            $lalu2 = "";
        }

        if ($lpe_lalu2 < 0) {
            $lalu3 = "(";
            $lpe_lalu2 = $lpe_lalu2 * -1;
            $lalu4 = ")";
        } else {
            $lalu3 = "";
            $lpe_lalu2;
            $lalu4 = "";
        }

        if ($lpe_lalu3 < 0) {
            $lalu5 = "(";
            $lpe_lalu3 = $lpe_lalu3 * -1;
            $lalu6 = ")";
        } else {
            $lalu5 = "";
            $lpe_lalu3;
            $lalu6 = "";
        }

        if ($nilaiDR < 0) {
            $l000 = "(";
            $nilaiDR = $nilaiDR * -1;
            $p000 = ")";
        } else {
            $l000 = "";
            $nilaiDR;
            $p000 = "";
        }

        if ($nilailpe1 < 0) {
            $l001 = "(";
            $nilailpe1 = $nilailpe1 * -1;
            $p001 = ")";
        } else {
            $l001 = "";
            $nilailpe1;
            $p001 = "";
        }

        if ($nilailpe2 < 0) {
            $l002 = "(";
            $nilailpe2 = $nilailpe2 * -1;
            $p002 = ")";
        } else {
            $l002 = "";
            $nilailpe2;
            $p002 = "";
        }

        if ($surplus_lo3 < 0) {
            $lo13 = "(";
            $surplus_lo3 = $surplus_lo3 * -1;
            $lo14 = ")";
        } else {
            $lo13 = "";
            $lo14 = "";
        }
        $surplus_lo31 = number_format($surplus_lo3, "2", ",", ".");

        if ($sal_akhir < 0) {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else {
            $c = "";
            $sal_akhir;
            $d = "";
        }

        if ($sal_awal < 0) {
            $c1 = "(";
            $sal_awal = $sal_awal * -1;
            $d1 = ")";
        } else {
            $c1 = "";
            $sal_awal;
            $d1 = "";
        }

        if ($real < 0) {
            $cx = "(";
            $real = $real * -1;
            $dx = ")";
        } else {
            $cx = "";
            $real;
            $dx = "";
        }

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $sclient = $this->akuntansi_model->get_sclient();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
					<tr>
                         <td align=\"center\"><strong>$nm_skpd</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN PERUBAHAN EKUITAS</strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
					<tr>
                         <td align=\"center\"><strong>&nbsp;</strong></td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                        
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                        </tr>";

        $sql = "SELECT * FROM map_lpe_permen77_SKPD  ORDER BY seq";

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row) {

            $kd_rek   = $row->nor;
            $parent   = $row->parent;
            $nama     = $row->uraian;
            $nilai_1    = $row->thn_m1;

            switch ($kd_rek) {
                case 1:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$cx" . number_format($real, "2", ",", ".") . "$dx</td>
                                                     </tr>";

                    break;
                case 2:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $lo13" . number_format($surplus_lo3, "2", ",", ".") . "$lo14</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lo15" . number_format($surplus_lo_lalu3, "2", ",", ".") . "$lo16</td>
                                                     </tr>";

                    break;
                case 3:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";

                    break;
                case 4:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l001" . number_format($nilailpe1, "2", ",", ".") . "$p001</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu1" . number_format($lpe_lalu2, "2", ",", ".") . "$lalu2</td>
                                                     </tr>";
                    break;
                case 5:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l000" . number_format($nilaiDR, "2", ",", ".") . "$p000</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu3" . number_format($lpe_lalu1, "2", ",", ".") . "$lalu4</td>
                                                     </tr>";
                    break;
                case 6:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l002" . number_format($nilailpe2, "2", ",", ".") . "$p002</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu5" . number_format($lpe_lalu3, "2", ",", ".") . "$lalu6</td>
                                                     </tr>";
                    break;
                case 7:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c" . number_format($sal_akhir, "2", ",", ".") . "$d</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
                                                     </tr>";
            }
        }

        $cRet .= '</table>';

        $cRet .= '<TABLE width="100%" style="border-collapse:collapse;" border="0" cellspacing="0">
					<TR>
						<TD align="center" width="50%"><b>&nbsp;</TD>
						<TD align="center" width="50%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="50%"></TD>
						<TD align="center" width="30%">' . $daerah . ', ' . $tanggal_ttd . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $jabatan . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD align="center" width="30%"><b>&nbsp;</TD>
						<TD align="center" width="30%"><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%"><u><b>' . $nama_ttd . ' </b><br></u> ' . $pangkat . '</TD>
					</TR>
                    <TR>
						<TD align="center" width="30%"></TD>
						<TD align="center" width="30%">' . $nip . '</TD>
					</TR>
					</TABLE><br/>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("LPE UNIT $cbulan");
        $this->template->set('title', 'LPE UNIT $cbulan');
        switch ($cetak) {
            case 4;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 1;
                echo "<title>LPE UNIT $cbulan</title>";
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



    //================================================= End Cetak LPE
}
