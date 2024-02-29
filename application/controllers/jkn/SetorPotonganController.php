<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class SetorPotonganController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'SETOR POTONGAN JKN';
        $this->template->set('title', 'SETOR POTONGAN JKN');
        $this->template->load('template', 'jkn/setorpotongan/index', $data);
    }

    public function no_terima()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');
        $sql = "SELECT a.* FROM jkn_trhtrmpot a
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
                'no_sp2d' => $resulte['no_sp2d'],
                'jns_spp' => $resulte['jns_spp'],
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    public function no_urut()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT case when max(nomor) is null then 1 else max(nomor+1) end as nomor from (
            SELECT no_kas nomor, 'Penerimaan JKN' ket, kd_skpd from jkn_tr_terima where isnumeric(no_kas)=1
            UNION ALL
            SELECT no_kas nomor, 'Transaksi JKN' ket, kd_skpd from jkn_trhtransout where isnumeric(no_kas)=1 AND jns_spp IN('1','2')
            UNION ALL
            SELECT no_bukti nomor, 'Terima Potongan JKN' ket, kd_skpd from jkn_trhtrmpot where isnumeric(no_bukti)=1 AND jns_spp IN('1','2')
            UNION ALL
            SELECT no_bukti nomor, 'Setor Potongan JKN' ket, kd_skpd from jkn_trhstrpot where isnumeric(no_bukti)=1 AND jns_spp IN('1','2')
            ) z WHERE kd_skpd = '$skpd'");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor']
            );
            $ii++;
        }
        echo json_encode($result);
    }

    public function loaddata_trmpot()
    {
        $no_terima = $this->input->post('no_terima');
        $skpd = $this->input->post('skpd');
        $sql = "SELECT a.* FROM jkn_trdtrmpot a
				WHERE a.kd_skpd = '$skpd' AND a.no_bukti='$no_terima'";
        $query1 = $this->db->query($sql);
        $result = array();
        $id = 0;
        $total = 0;
        foreach ($query1->result_array() as $resulte) {
            $total += $resulte['nilai'];
            $result[] = array(
                'id' =>  $id,
                'idx' => $resulte['id'],
                'no_bukti' => $resulte['no_bukti'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'nilai' => $resulte['nilai'],
                'kd_skpd' => $resulte['kd_skpd'],
                'kd_rek_trans' => $resulte['kd_rek_trans'],
                'ebilling' => $resulte['ebilling'],
                'map_pot' => $resulte['map_pot'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'ntpn' => $resulte['ntpn'],
                'total' => $total,
            );
            $id++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    public function update_potongan()
    {
        $no_terima = $this->input->post('no_terima');
        $ntpn = $this->input->post('ntpn');
        $ebilling = $this->input->post('ebilling');
        $skpd = $this->input->post('skpd');
        $idx = $this->input->post('idx');
        $kd_rek = $this->input->post('kd_rek');
        $query3 = $this->db->query("UPDATE jkn_trdtrmpot set ntpn='$ntpn',ebilling='$ebilling' WHERE no_bukti='$no_terima' and kd_skpd='$skpd' and kd_rek6='$kd_rek'");
        echo '1';
    }

    public function simpan_data()
    {
        $lckolompot = $this->input->post('kolompot');
        $nilaipot = $this->input->post('nilaipot');
        $cno  = $this->input->post('cno');
        $cket  = $this->input->post('cket');
        $cjenis  = $this->input->post('cjenis');
        $cskpd  = $this->input->post('cskpd');
        $ctgl  = $this->input->post('ctgl');
        $sp2d  = $this->input->post('sp2d');
        $npwp  = $this->input->post('npwp');
        $no_terima  = $this->input->post('no_terima');
        $total_potongan  = $this->input->post('total_potongan');
        $username      = $this->session->userdata('pcNama');
        $sql = "INSERT into jkn_trdstrpot $lckolompot values $nilaipot";
        $asg = $this->db->query($sql);
        if ($asg) {
            $this->db->insert('jkn_trhstrpot', array(
                'no_bukti' => $this->input->post('cno'),
                'tgl_bukti' => $this->input->post('ctgl'),
                'ket' => $this->input->post('cket'),
                'kd_skpd' => $this->input->post('cskpd'),
                'nilai' => $this->input->post('total_potongan'),
                'npwp' => $this->input->post('npwp'),
                'jns_spp' => $this->input->post('cjenis'),
                'no_sp2d' => $this->input->post('sp2d'),
                'no_terima' =>  $this->input->post('no_terima'),
                'username' => $username
            ));
            $this->db->query("UPDATE jkn_trhtrmpot set [status]='1' WHERE no_bukti='$no_terima' and kd_skpd='$cskpd'");
            echo '1';
        } else {
            echo '0';
        }
    }

    function loaddata()
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
        $sql = "SELECT count(*) as total from jkn_trhstrpot where kd_skpd='$kd_skpd' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
      
        $sql = "SELECT top $rows no_bukti,tgl_bukti,no_terima,kd_skpd,no_sp2d,RTRIM(jns_spp) as jns_spp,npwp,ket,nilai
				from jkn_trhstrpot where kd_skpd='$kd_skpd' $where AND no_bukti not in (SELECT top $offset no_bukti FROM jkn_trhstrpot where 
				kd_skpd='$kd_skpd' $where order by CAST(no_bukti AS INT))  order by tgl_bukti,CAST(no_bukti AS INT),kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'no_terima' => $resulte['no_terima'],
                'npwp' => $resulte['npwp'],
                'jns_spp' => $resulte['jns_spp'],
                'no_sp2d' => $resulte['no_sp2d'],
                'ket' => $resulte['ket'],
                'kd_skpd' => $resulte['kd_skpd']
            );
            $ii++;
        }
        $result["total"] = $total->total;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    public function update_data()
    {
        $cno = $this->input->post('cno');
        $cket = $this->input->post('cket');
        $cjenis = $this->input->post('cjenis');
        $ctgl = $this->input->post('ctgl');
        $cskpd  = $this->input->post('cskpd');
        $data = $this->db->query("UPDATE jkn_trhstrpot set ket='$cket', tgl_bukti=' $ctgl' WHERE no_bukti='$cno' and kd_skpd='$cskpd'");
        if ($data) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function hapus_data()
    {
        $nomor = $this->input->post('cno');
        $skpd = $this->input->post('cskpd');
        $no_terima = $this->input->post('no_terima');

        $this->db->query("DELETE FROM jkn_trdstrpot WHERE kd_skpd='$skpd' AND no_bukti='$nomor'");
        $this->db->query("DELETE FROM jkn_trhstrpot WHERE kd_skpd='$skpd' AND no_bukti='$nomor'");
        $data = $this->db->query("UPDATE jkn_trhtrmpot set [status]='0' WHERE no_bukti='$no_terima' and kd_skpd='$skpd'");
        if ($data) {
            echo '1';
        } else
            echo '0';
    }
}
