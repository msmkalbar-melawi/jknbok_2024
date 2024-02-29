<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Tukd_cms 
 *  
 * @package 
 * @author Boomer 
 * @copyright 2016
 * @version $Id$ 
 * @access public
 */
class setor extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('pcNama') == '') {
            redirect('welcome');
        }
    }

    function strpot()
    {
        $data['page_title'] = 'P O T O N G A N';
        $this->template->set('title', 'PENYETORAN POTONGAN');
        $this->template->load('template', 'tukd/transaksi/strpot', $data);
    }

    function trmpot_()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $sql = "SELECT a.* FROM trhtrmpot a
				WHERE a.kd_skpd = '$kd_skpd'  AND status='0'
				AND upper(a.no_bukti) like upper('%$lccr%') order by a.no_bukti";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['ket'],
                'no_sp2d' => $resulte['no_sp2d'],
                'npwp' => $resulte['npwp'],
                'jns_spp' => $resulte['jns_spp'],
                'kd_giat' => $resulte['kd_sub_kegiatan'],
                'nm_giat' => $resulte['nm_sub_kegiatan'],
                'kd_rek' => $resulte['kd_rek6'],
                'nm_rek' => $resulte['nm_rek6'],
                'alamat' => $resulte['alamat'],
                'rekanan' => $resulte['nmrekan'],
                'dir' => $resulte['pimpinan'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sp2d_trimpot()
    {

        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr   = $this->input->post('q');
        $sql = "
		SELECT b.no_sp2d,a.jns_spp FROM trhtransout a INNER JOIN trdtransout b ON a.kd_skpd=b.kd_skpd AND a.no_bukti = b.no_bukti
		WHERE upper(b.no_sp2d) like upper('%$lccr%') AND b.kd_skpd='$kd_skpd'
		GROUP BY b.no_sp2d,jns_spp order by no_sp2d";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $cksp2d = $resulte['no_sp2d'];
            $sp2 = explode("/", $cksp2d);
            $sp2d = $sp2[2];

            if ($sp2d == "GU") {
                $nsp2d = '2';
            } else {
                $nsp2d = $resulte['jns_spp'];
            }

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'jns_spp' => $nsp2d
            );
            $ii++;
        }

        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function load_kegiatan_pot()
    {
        $sp2d = str_replace('123456789', '/', $this->uri->segment(3));
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdtransout a
			INNER JOIN trhtransout c ON a.no_bukti = c.no_bukti
			AND a.kd_skpd = c.kd_skpd
			WHERE a.no_sp2d = '$sp2d'
			AND a.kd_skpd='$skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_giat' => $resulte['kd_sub_kegiatan'],
                'nm_giat' => $resulte['nm_sub_kegiatan'],
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek_pot()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $sp2d = str_replace('123456789', '/', $this->uri->segment(3));
        $kd_giat_pot = $this->uri->segment(4);
        $query1 = $this->db->query("SELECT a.kd_rek6, a.nm_rek6 FROM trdtransout a
			INNER JOIN trhtransout b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd WHERE
			 a.no_sp2d = '$sp2d' AND a.kd_sub_kegiatan = '$kd_giat_pot' and a.kd_skpd='$kd_skpd' group by a.kd_rek6, a.nm_rek6");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_rek' => $resulte['kd_rek6'],
                'nm_rek' => $resulte['nm_rek6'],
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function trmpot__()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $sql = "SELECT a.* FROM trhtrmpot a
				WHERE a.kd_skpd = '$kd_skpd'  AND status='0'
				AND upper(a.no_bukti) like upper('%$lccr%') order by a.no_bukti";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'ket' => $resulte['ket'],
                'no_sp2d' => $resulte['no_sp2d'],
                'npwp' => $resulte['npwp'],
                'jns_spp' => $resulte['jns_spp'],
                'kd_giat' => $resulte['kd_sub_kegiatan'],
                'nm_giat' => $resulte['nm_sub_kegiatan'],
                'kd_rek' => $resulte['kd_rek6'],
                'nm_rek' => $resulte['nm_rek6'],
                'alamat' => $resulte['alamat'],
                'rekanan' => $resulte['nmrekan'],
                'dir' => $resulte['pimpinan'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function pot_in()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        $sql = "SELECT * FROM trdtrmpot where no_bukti='$bukti' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'idx' => $resulte['id'],
                'kd_trans' => $resulte['kd_rek_trans'],
                'kd_rek5' => $resulte['kd_rek6'],
                'nm_rek5' => $resulte['nm_rek6'],
                'rekanan' => $resulte['rekanan'],
                'npwp' => $resulte['npwp'],
                'nilai' => $resulte['nilai'],
                'ntpn' => $resulte['ntpn'],
                'ebilling' => $resulte['ebilling']
            );
            $ii++;
        }

        echo json_encode($result);
        //$query1->free_result();   
    }

    function simpan_strpot()
    {
        $no_bukti = $this->input->post('no_bukti');
        $no_ntpn = $this->input->post('no_ntpn');
        $tgl_bukti = $this->input->post('tgl_bukti');
        $no_terima = $this->input->post('no_terima');
        $jns_spp = $this->input->post('jns_spp');
        $ket = $this->input->post('ket');
        $kd_skpd = $this->input->post('kd_skpd');
        $nm_skpd = $this->input->post('nm_skpd');
        $npwp = $this->input->post('npwp');
        $nilai = $this->input->post('nilai');
        $no_sp2d = $this->input->post('no_sp2d');
        $kd_giat = $this->input->post('kd_giat');
        $nm_giat = $this->input->post('nm_giat');
        $kd_rek = $this->input->post('kd_rek');
        $nm_rek = $this->input->post('nm_rek');
        $rekanan = $this->input->post('rekanan');
        $dir = $this->input->post('dir');
        $alamat = $this->input->post('alamat');
        $usernm = $this->session->userdata('pcNama');
        $cpay = $this->input->post('pay');
        $ebilling = $this->input->post('ebilling');
        $ntpn = $this->input->post('ntpn');
        //date_default_timezone_set('Asia/Bangkok');
        //$last_update=  date('d-m-y H:i:s');


        $sql = "delete from trhstrpot where no_bukti='$no_bukti' AND kd_skpd='$kd_skpd' ";
        $asg = $this->db->query($sql);
        $query2 = "insert into trhstrpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,no_terima,npwp,jns_spp,nilai,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nmrekan,pimpinan,alamat,no_ntpn,pay) 
                    values('$no_bukti','$tgl_bukti','$ket','$usernm','','$kd_skpd','$nm_skpd','$no_terima','$npwp','$jns_spp','$nilai','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$ntpn','$cpay') ";
        $asg2 = $this->db->query($query2);

        $query3 = "UPDATE trhtrmpot SET status = '1' WHERE no_bukti = '$no_terima' AND kd_skpd='$kd_skpd'";
        $asg3 = $this->db->query($query3);

        $query3 = "DELETE FROM trdstrpot WHERE no_bukti = '$no_bukti' AND kd_skpd='$kd_skpd'";
        $asg3 = $this->db->query($query3);

        $query3 = "INSERT INTO trdstrpot (no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans,ntpn,rekanan,npwp,ebilling) 
                        SELECT $no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans,ntpn,rekanan,npwp,ebilling FROM trdtrmpot WHERE no_bukti = '$no_terima' AND kd_skpd='$kd_skpd'";
        $asg3 = $this->db->query($query3);

        echo '1';
        //$asg->free_result();
        //          $query1->free_result();  
    }

    function simpan_strpot_update_ntpn()
    {
        $kd_skpd = $this->input->post('kd_skpd');
        $ntpn = $this->input->post('ntpn');
        $no_bukti = $this->input->post('no_bukti');
        $kd_rek5 = $this->input->post('kd_rek5');
        if ($ntpn == 'Klik 2x edit NTPN') {
            $ntpn = '';
        }
        $oke = "UPDATE trdstrpot set ntpn='$ntpn' where kd_skpd='$kd_skpd' and no_bukti='$no_bukti' and kd_rek6='$kd_rek5' ";
        $this->db->query($oke);
    }

    function simpan_strpot_edit()
    {
        $no_bku = $this->input->post('no_bku');
        $no_ntpn = $this->input->post('no_ntpn');
        $trmpot_lama = $this->input->post('trmpot_lama');
        $no_bukti = $this->input->post('no_bukti');
        $tgl_bukti = $this->input->post('tgl_bukti');
        $no_terima = $this->input->post('no_terima');
        $jns_spp = $this->input->post('jns_spp');
        $ket = $this->input->post('ket');
        $kd_skpd = $this->input->post('kd_skpd');
        $nm_skpd = $this->input->post('nm_skpd');
        $npwp = $this->input->post('npwp');
        $nilai = $this->input->post('nilai');
        $no_sp2d = $this->input->post('no_sp2d');
        $kd_giat = $this->input->post('kd_giat');
        $nm_giat = $this->input->post('nm_giat');
        $kd_rek = $this->input->post('kd_rek');
        $nm_rek = $this->input->post('nm_rek');
        $rekanan = $this->input->post('rekanan');
        $dir = $this->input->post('dir');
        $alamat = $this->input->post('alamat');
        $usernm = $this->session->userdata('pcNama');
        $cpay = $this->input->post('pay');
        $ebilling = $this->input->post('ebilling');
        $ntpn = $this->input->post('ntpn');
        //date_default_timezone_set('Asia/Bangkok');
        //$last_update=  date('d-m-y H:i:s');


        $sql = "delete from trhstrpot where no_bukti='$no_bku' AND kd_skpd='$kd_skpd' ";
        $asg = $this->db->query($sql);
        $query2 = "insert into trhstrpot(no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,no_terima,npwp,jns_spp,nilai,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nmrekan,pimpinan,alamat,no_ntpn,pay) 
                    values('$no_bukti','$tgl_bukti','$ket','$usernm','','$kd_skpd','$nm_skpd','$no_terima','$npwp','$jns_spp','$nilai','$no_sp2d','$kd_giat','$nm_giat','$kd_rek','$nm_rek','$rekanan','$dir','$alamat','$ntpn','$cpay') ";
        $asg2 = $this->db->query($query2);

        $query3 = "UPDATE trhtrmpot SET status = '0' WHERE no_bukti = '$trmpot_lama' AND kd_skpd='$kd_skpd'";
        $asg3 = $this->db->query($query3);

        $query3 = "UPDATE trhtrmpot SET status = '1' WHERE no_bukti = '$no_terima' AND kd_skpd='$kd_skpd'";
        $asg3 = $this->db->query($query3);

        $query3 = "DELETE FROM trdstrpot WHERE no_bukti = '$no_bku' AND kd_skpd='$kd_skpd'";
        $asg3 = $this->db->query($query3);

        $query3 = "INSERT INTO trdstrpot (no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans,ntpn,ebilling, map_pot) 
                        SELECT $no_bukti,kd_rek6,nm_rek6,nilai,kd_skpd,kd_rek_trans,ntpn,ebilling, map_pot FROM trdtrmpot WHERE no_bukti = '$no_terima' AND kd_skpd='$kd_skpd' ";
        $asg3 = $this->db->query($query3);

        echo '1';
        //$asg->free_result();
        //          $query1->free_result();  
    }


    function edit_strpot()
    {
        $idx = $this->input->post('idx');
        $no_terima = $this->input->post('no_terima');
        $no_bukti = $this->input->post('no_bukti');
        $no_bku = $this->input->post('no_bku');
        $rek = $this->input->post('rek');
        $skpd = $this->session->userdata('kdskpd'); //$this->input->post('skpd');                         
        $ntpn = $this->input->post('ntpn');
        $nilei = $this->input->post('nilai');

        $ebilling = $this->input->post('ebilling');
        $query2 = "SELECT map_pot FROM ms_pot WHERE kd_rek6='$rek'";
        $asg2 = $this->db->query($query2);
        $trh = $asg2->row();
        $hasil = $trh->map_pot;

        $query3 = "UPDATE trdstrpot set ntpn='$ntpn',ebilling='$ebilling', map_pot='$hasil' where no_bukti='$no_bukti' and kd_skpd='$skpd' and kd_rek6='$rek' and nilai='$nilei' AND kd_rek_trans='$idx'";
        $asg3 = $this->db->query($query3);

        $query3 = "UPDATE trdtrmpot set ntpn='$ntpn',ebilling='$ebilling', map_pot='$hasil' where no_bukti='$no_terima' and kd_skpd='$skpd' and kd_rek6='$rek' and nilai='$nilei' AND kd_rek_trans='$idx'";
        $asg3 = $this->db->query($query3);
        echo '1';
    }

    function hapus_strpot()
    {
        $nom = $this->input->post('no');
        $no_terima = $this->input->post('no_terima');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $msg = array();
        $sql = "DELETE from trhstrpot where no_bukti='$nom' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        $sql = "DELETE from trdstrpot where no_bukti='$nom' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        $query3 = "UPDATE trhtrmpot SET status = '0' WHERE no_bukti = '$no_terima' AND kd_skpd='$kd_skpd'";
        $asg3 = $this->db->query($query3);

        $msg = array('pesan' => '1');
        echo json_encode($msg);
    }

    function pot_setor()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        $sql = "SELECT *, case when ntpn is null or ntpn='' or ntpn='-' then  'Klik 2x edit NTPN' else ntpn end as oke FROM trdstrpot where no_bukti='$bukti' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'idx' => $resulte['id'],
                'kd_trans' => $resulte['kd_rek_trans'],
                'kd_rek5' => $resulte['kd_rek6'],
                'nm_rek5' => $resulte['nm_rek6'],
                'nilai' => $resulte['nilai'],
                'ntpn' => $resulte['oke'],
                'ebilling' => $resulte['ebilling']
            );
            $ii++;
        }

        echo json_encode($result);
        //$query1->free_result();   
    }


    function psimpan_str()
    {

        $bukti = $this->input->post('bukti');
        $kd_rek5 = $this->input->post('kd_rek5');
        $nm_rek5 = $this->input->post('nm_rek5');
        $nilai = $this->input->post('nilai');

        $query1 = $this->db->query(" delete from trdstrpot where no_bukti='$bukti' and kd_rek6='$kd_rek5'");
        $query = $this->db->query(" insert into trdstrpot(no_bukti,kd_rek6,nm_rek6,nilai) values('$bukti','$kd_rek5','$nm_rek5','$nilai') ");

        //$this->select_data($spd);

    }


    function load_pot_out()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%') ";
        }
        $sql = "SELECT count(*) as total from trhstrpot where kd_skpd='$kd_skpd' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows no_bukti,no_ntpn,tgl_bukti,no_terima,kd_skpd,no_sp2d,RTRIM(jns_spp) as jns_spp,
                nm_skpd,nm_sub_kegiatan,kd_sub_kegiatan,nmrekan,pimpinan, alamat,npwp,ket,nilai,pay
        from trhstrpot where kd_skpd='$kd_skpd' $where AND no_bukti not in (SELECT top $offset no_bukti FROM trhstrpot where kd_skpd='$kd_skpd' $where order by no_bukti)  order by no_bukti,kd_skpd";
        $query1 = $this->db->query($sql);
        //$result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'no_ntpn' => $resulte['no_ntpn'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'no_terima' => $resulte['no_terima'],
                'kd_skpd' => $resulte['kd_skpd'],
                'no_sp2d' => $resulte['no_sp2d'],
                'jns_spp' => $resulte['jns_spp'],
                'nm_skpd' => $resulte['nm_skpd'],
                'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nmrekan' => $resulte['nmrekan'],
                'pimpinan' => $resulte['pimpinan'],
                'alamat' => $resulte['alamat'],
                'ket' => $resulte['ket'],
                'nilai' => $resulte['nilai'],
                'npwp' => $resulte['npwp'],
                'pay' => $resulte['pay'],
                'no_ntpn' => $resulte['no_ntpn']
            );
            $ii++;
        }

        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_pot_outa()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_bukti) like upper('%$kriteria%') or tgl_bukti like '%$kriteria%' or no_sp2d like '%$kriteria%') ";
        }
        $sql = "SELECT count(*) as total from trhstrpot where kd_skpd='$kd_skpd' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows kd_rek6, nm_rek6, no_bukti,tgl_bukti,no_terima,kd_skpd,no_sp2d,RTRIM(jns_spp) as jns_spp,
				nm_skpd,nm_sub_kegiatan,kd_sub_kegiatan,nmrekan,pimpinan, alamat,npwp,ket,nilai,no_ntpn
				from trhstrpot where kd_skpd='$kd_skpd' $where AND no_bukti not in (SELECT top $offset no_bukti FROM trhstrpot where 
				kd_skpd='$kd_skpd' $where order by CAST(no_bukti AS INT))  order by tgl_bukti,CAST(no_bukti AS INT),kd_skpd";
        $query1 = $this->db->query($sql);
        //$result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'no_terima' => $resulte['no_terima'],
                'kd_skpd' => $resulte['kd_skpd'],
                'no_sp2d' => $resulte['no_sp2d'],
                'jns_spp' => $resulte['jns_spp'],
                'nm_skpd' => $resulte['nm_skpd'],
                'nm_rek6' => $resulte['nm_rek6'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nmrekan' => $resulte['nmrekan'],
                'pimpinan' => $resulte['pimpinan'],
                'alamat' => $resulte['alamat'],
                'ket' => $resulte['ket'],
                'nilai' => $resulte['nilai'],
                'npwp' => $resulte['npwp'],
                'no_ntpn' => $resulte['no_ntpn']

            );
            $ii++;
        }

        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function pilih_trmpot()
    {
        $lccr = $this->input->post('q');
        $kd_skpd  = $this->session->userdata('kdskpd');
        $sql = "SELECT no_bukti,kd_skpd,tgl_bukti,no_sp2d FROM trhtrmpot where kd_skpd='$kd_skpd' AND upper(no_bukti) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%')";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'kd_skpd' => $resulte['kd_skpd'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'no_sp2d' => $resulte['no_sp2d']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $field2    = $this->input->post('field2');
        $tabel2   = $this->input->post('tabel2');
        $kd_skpd  = $this->session->userdata('kdskpd');
        if ($field2 == '') {
            $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else {
            $hasil = $this->db->query(" select count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor' ");
        }
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        }
    }


    function load_trm_pot()
    {
        $skpd = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdtrmpot where no_bukti='$bukti' AND kd_skpd='$skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'rektotal' => number_format($resulte['rektotal'], "2", ",", "."),
                'rektotal1' => $resulte['rektotal']
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_str_pot()
    {
        $skpd = $this->session->userdata('kdskpd');
        $bukti = $this->input->post('bukti');
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdstrpot where no_bukti='$bukti' AND kd_skpd='$skpd'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'rektotal' => number_format($resulte['rektotal'], "2", ",", "."),
                'rektotal1' => $resulte['rektotal']
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function tambah_strpot()
    {
        $idx = $this->input->post('idx');
        $no_terima = $this->input->post('no_terima');
        $no_bku = $this->input->post('no_bku');
        $rek = $this->input->post('rek');
        $skpd = $this->input->post('skpd');
        $ntpn = $this->input->post('ntpn');
        $ebilling = $this->input->post('ebilling');
        $nilai = $this->input->post('nilai');

        $query2 = "SELECT map_pot FROM ms_pot WHERE kd_rek6='$rek'";
        $asg2 = $this->db->query($query2);
        $trh = $asg2->row();
        $hasil = $trh->map_pot;
        $query3 = $this->db->query("UPDATE trdtrmpot set ntpn='$ntpn',ebilling='$ebilling', map_pot='$hasil' where no_bukti='$no_terima' and kd_skpd='$skpd' and kd_rek6='$rek' AND kd_rek_trans='$idx' AND nilai='$nilai'");
        echo '1';
    }
}
