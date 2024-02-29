<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Koreksi extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }

    function transout_koreksi()
    {
        $data['page_title'] = 'INPUT KOREKSI TRANSAKSI';
        $this->template->set('title', 'INPUT KOREKSI TRANSAKSI');
        $this->template->load('template', 'tukd/transaksi2/transout_koreksi', $data);
    }
    function cetak_jurnal_k()
    {
        $data['page_title'] = 'CETAK JURNAL KOREKSI';
        $this->template->set('title', 'CETAK JURNAL KOREKSI');
        $this->template->load('template', 'tukd/transaksi2/ctk_jurnal_koreksi', $data);
    }
    function cetak_jurnal_k2()
    {
        $data['page_title'] = 'CETAK JURNAL KOREKSI';
        $this->template->set('title', 'CETAK JURNAL KOREKSI');
        $this->template->load('template', 'tukd/transaksi2/ctk_jurnal_koreksi2', $data);
    }
    function load_trskpd_koreksi()
    {
        $jenis = $this->input->post('jenis');
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');

        $jns_beban = '';
        $cgiat = '';

        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        $sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trdtransout a 
                WHERE a.kd_skpd='$cskpd' $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%')) group by a.kd_sub_kegiatan,a.nm_sub_kegiatan";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sp2d_koreksi()
    {
        //$beban='',$giat=''
        $beban   = $this->input->post('jenis');
        $giat    = $this->input->post('giat');
        $kode    = $this->input->post('kd');
        $bukti   = $this->input->post('bukti');
        $where = '';

        $kriteria = $this->input->post('q');
        $sql = "SELECT DISTINCT a.no_sp2d
                    FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                    WHERE a.kd_skpd = '$kode' and b.jns_spp='$beban' and a.kd_sub_kegiatan='$giat' ORDER BY a.no_sp2d";
        //and UPPER(no_sp2d) LIKE '%$kriteria%'  
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek_koreksi()
    {
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');
        if ($rek != '') {
            $notIn = " and kd_rek6 not in ($rek) ";
        } else {
            $notIn  = "";
        }
        $sql = "SELECT a.no_bukti, a.kd_rek6, a.nm_rek6,nilai,sumber
                FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
				WHERE a.kd_skpd='$kode' AND  b.no_sp2d = '$sp2d' and a.kd_sub_kegiatan='$giat' AND b.jns_spp = '$jenis' $notIn ORDER BY a.no_bukti";


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_bku' => $resulte['no_bukti'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'nilai' => $resulte['nilai'],
                'sumber' => $resulte['sumber']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_trskpd()
    {
        $jenis = $this->input->post('jenis');
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($cskpd);

        $jns_beban = '';
        $cgiat = '';
        $jns_beban = "and c.jns_sub_kegiatan='5'";
        if ($giat != '') {
            $cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.total FROM trskpd a 
        		INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
        		INNER JOIN ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan
                WHERE a.kd_skpd='$cskpd' AND a.status_sub_kegiatan='1'AND a.jns_ang='$jns_ang' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'total'       => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek()
    {
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($kode);
        if ($rek != '') {
            $notIn = " and kd_rek6 not in ($rek) ";
        } else {
            $notIn  = "";
        }

        if ($jenis == '1') {
            $sql = "SELECT a.kd_rek6,a.nm_rek6,
                    (SELECT SUM(nilai) FROM 
						(SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout c
						LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
						AND c.kd_skpd = d.kd_skpd
						WHERE
							c.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND d.kd_skpd = a.kd_skpd
						AND c.kd_rek6 = a.kd_rek6
						AND c.no_bukti <> '$nomor'
						AND d.jns_spp='$jenis'
						UNION ALL
						SELECT
							SUM (c.nilai) as nilai
						FROM
							trdtransout_cmsbank c
						LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
						AND c.kd_skpd = d.kd_skpd
						WHERE
							c.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND d.kd_skpd = a.kd_skpd
						AND c.kd_rek6 = a.kd_rek6
						AND d.jns_spp='$jenis'
						AND d.status_validasi<>'1'
						UNION ALL
						SELECT SUM(x.nilai) as nilai FROM trdspp x
						INNER JOIN trhspp y 
						ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
						WHERE
							x.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND x.kd_skpd = a.kd_skpd
						AND x.kd_rek6 = a.kd_rek6
						AND y.jns_spp IN ('3','4','5','6')
						AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0')
						UNION ALL
						SELECT SUM(nilai) as nilai FROM trdtagih t 
						INNER JOIN trhtagih u 
						ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
						WHERE 
						t.kd_sub_kegiatan = a.kd_sub_kegiatan
						AND u.kd_skpd = a.kd_skpd
						AND t.kd_rek = a.kd_rek6
						AND u.no_bukti 
						NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kode' )
						)r) AS lalu,
						0 AS sp2d,a.nilai AS anggaran
						FROM trdrka a WHERE a.kd_sub_kegiatan= '$giat' AND a.kd_skpd = '$kode' AND a.jns_ang='$jns_ang' $notIn  ";
        } else {
            $sql = "SELECT b.kd_rek6,b.nm_rek6,
                    (SELECT SUM(c.nilai) FROM trdtransout c LEFT JOIN trhtransout d ON c.no_bukti=d.no_bukti AND c.kd_skpd=d.kd_skpd 
					WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                    d.kd_skpd=a.kd_skpd 
					AND c.kd_rek6=b.kd_rek6 AND c.no_bukti <> '$nomor' AND d.jns_spp = '$jenis' and c.no_sp2d = '$sp2d'
					) AS lalu,
                    b.nilai AS sp2d,
                    0 AS anggaran
                    FROM trhspp a INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd = b.kd_skpd 
					INNER JOIN trhspm c ON b.no_spp=c.no_spp AND b.kd_skpd = c.kd_skpd 
					INNER JOIN trhsp2d d ON c.no_spm=d.no_Spm AND c.kd_skpd=d.kd_skpd
                    WHERE d.no_sp2d = '$sp2d' and b.kd_sub_kegiatan='$giat' $notIn ";
        }
        //echo $sql;
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'lalu' => $resulte['lalu'],
                'sp2d' => $resulte['sp2d'],
                'anggaran' => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_sdana()
    {
        $sumber = $this->input->post('sumber');
        $giat   = $this->input->post('giat');
        $rek    = $this->input->post('kode_rek');
        $skpd   = $this->input->post('kode');
        $nobkuk   = $this->input->post('nobkuk');
        $query1 = $this->tukd_model->qtrans_sdana($sumber, $giat, $rek, $skpd, $nobkuk);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total_trans_sumber' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }
    function ambil_sdana()
    {

        $lccr      = ''; //$this->input->post('q');
        $tgl     = $this->input->post('tgl');
        $skpd     = $this->input->post('skpd');
        $giat     = $this->input->post('giat');
        $kdrek5 = $this->input->post('kdrek5');
        $nosp2d = $this->input->post('nosp2d');
        $jnsbeban = $this->input->post('jnsbeban');
        $status_anggaran = $this->cek_anggaran_model->cek_anggaran($skpd);

        //  $query1 = $this->tukd_model->qangg_sdana($tgl,$skpd,$giat,$kdrek5);
        //$query1 = $this->db->query("select kd_sdana, nm_sdana from ms_dana") ;
        if ($jnsbeban == '1') {
            $sql = "SELECT a.kd_sub_kegiatan,a.kd_rek6, b.sumber as sumber_dana, b.total as  nilai_sumber, (SELECT nm_sumber_dana1 FROM sumber_dana WHERE kd_sumber_dana1=b.sumber)as nmsumber from trdrka a INNER JOIN trdpo b on b.kd_skpd=a.kd_skpd AND b.jns_ang=a.jns_ang AND b.kd_sub_kegiatan=a.kd_sub_kegiatan AND b.kd_rek6=a.kd_rek6 where a.kd_skpd='$skpd' and a.kd_sub_kegiatan='$giat' and a.kd_rek6='$kdrek5' and a.jns_ang='$status_anggaran'";
        } else {
            $sql = "SELECT b.kd_sub_kegiatan, b.kd_rek6, b.sumber as sumber_dana,sum(b.nilai)as nilai_sumber, (SELECT nm_sumber_dana1 FROM sumber_dana WHERE kd_sumber_dana1=b.sumber)as nmsumber from trhspp a inner join trdspp b 
                        on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                        inner join trhsp2d c on a.no_spp=c.no_spp and a.kd_skpd=c.kd_skpd
                        where c.no_sp2d='$nosp2d' and a.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and kd_rek6='$kdrek5'
                        group by b.sumber,b.kd_sub_kegiatan, b.kd_rek6";
        }
        $query1 = $this->db->query($sql);

        $ii     = 0;
        $result = array();
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id'       => '$ii',
                'sumber' => $resulte['sumber_dana'],
                'nmsumber' => $resulte['nmsumber'],
                'anggaran' => number_format($resulte['nilai_sumber'], 2, '.', ','),
                'kegiatan' => $resulte['kd_sub_kegiatan'],
                'kd_rek6' => $resulte['kd_rek6']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_dtransout()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_user = $this->session->userdata('pcNama');

        $nomor = $this->input->post('no');
        $skpd  = $kd_skpd;
        $sql = "SELECT b.*,
                0 AS lalu,
                0 AS sp2d,
                0 AS anggaran 
                FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti 
                AND a.kd_skpd=b.kd_skpd
                WHERE a.no_bukti='$nomor' AND a.kd_skpd='$skpd'
                ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_bukti'    => $resulte['no_bukti'],
                'no_sp2d'       => $resulte['no_sp2d'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek6'       => $resulte['kd_rek6'],
                'nm_rek6'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai'],
                'nilai_nformat' => number_format($resulte['nilai'], 2),
                'sumber'        => $resulte['sumber'],
                'lalu'          => $resulte['lalu'],
                'sp2d'          => $resulte['sp2d'],
                'anggaran'      => $resulte['anggaran'],
                'anggaran'      => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_sdana()
    {
        $skpd = $this->input->post('skpd');
        $sp2d = $this->input->post('nosp2d');
        $giat = $this->input->post('giat');
        $rek  = $this->input->post('rek');
        $bku  = $this->input->post('nobkus');

        $lccr = $this->input->post('q');
        $sql  = "SELECT * from (
        SELECT sumber,(select nm_sumber_dana1 from sumber_dana where kd_sumber_dana1=b.sumber) as nmsumber,
        		sum(nilai) as nilai FROM trhtransout a INNER JOIN trdtransout b ON 
                a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
                where b.no_bukti='$bku' and a.no_sp2d='$sp2d' and  b.kd_skpd='$skpd' and b.kd_sub_kegiatan='$giat' and b.kd_rek6='$rek'
                GROUP BY sumber)zz where
                upper(sumber) like upper('%$lccr%') or upper(nmsumber) like upper('%$lccr%') order by  sumber ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'sumber'         => $resulte['sumber'],
                'nmsumber'         => $resulte['nmsumber'],
                'nilai'         => $resulte['nilai']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_transout_koreksi()
    {
        $tabel    = $this->input->post('tabel');
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $tgl_koreksi = $this->input->post('ctgl_koreksi');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "DELETE from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);

            if ($asg) {
                $sql = "INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','3','')";
                $asg = $this->db->query($sql);
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } elseif ($tabel == 'trdtransout') {
            // Simpan Detail //                       
            $sql = "DELETE from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "INSERT into trdtransout(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,
                            sumber)";
                $asg = $this->db->query($sql . $csql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    //   exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }



    function simpan_transout_koreksi2()
    {
        $tabel    = $this->input->post('tabel');
        $nomor    = $this->input->post('no');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $tgl_koreksi = $this->input->post('ctgl_koreksi');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $this->input->post('skpd');
        $nmskpd   = $this->input->post('nmskpd');
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "DELETE from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);

            if ($asg) {
                $sql = "INSERT into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgl_koreksi','$beban','$xpay','$nokaspot','5','')";
                $asg = $this->db->query($sql);
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } elseif ($tabel == 'trdtransout') {
            // Simpan Detail //                       
            $sql = "DELETE from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "INSERT into trdtransout(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,
                            sumber)";
                $asg = $this->db->query($sql . $csql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    //   exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }





    // ---------------------------------

    function transout_koreksi2()
    {
        $data['page_title'] = 'INPUT KOREKSI TRANSAKSI NOMINAL';
        $this->template->set('title', 'INPUT KOREKSI TRANSAKSI NOMINAL');
        $this->template->load('template', 'tukd/transaksi2/transout_koreksi2', $data);
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
            $hasil = $this->db->query(" SELECT count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else {
            $hasil = $this->db->query(" SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
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

    function load_transout_koreksi()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = " AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";
        }
        $sql = "SELECT ISNULL(MAX(tgl_terima),'2016-01-01') as tgl_terima FROM trhspj_ppkd WHERE cek='1' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res) {
            $tgl_terima = $res['tgl_terima'];
        }

        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '3' AND a.kd_skpd='$kd_skpd' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
		(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '3' AND a.kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '3' AND a.kd_skpd='$kd_skpd' $where order by a.no_bukti)  order by a.no_bukti,kd_skpd";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
		*/
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'no_kas' => $resulte['no_kas'],
                'tgl_kas' => $resulte['tgl_kas'],
                'ket' => $resulte['ket'],
                'username' => $resulte['username'],
                'tgl_update' => $resulte['tgl_update'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'total' => $resulte['total'],
                'no_tagih' => $resulte['no_tagih'],
                'sts_tagih' => $resulte['sts_tagih'],
                'tgl_tagih' => $resulte['tgl_tagih'],
                'jns_beban' => $resulte['jns_spp'],
                'pay' => $resulte['pay'],
                'no_kas_pot' => $resulte['no_kas_pot'],
                'tgl_pot' =>  $resulte['tgl_pot'],
                'ketpot' => $resulte['kete'],
                'ketlpj' => $resulte['ketlpj'],
                'ketspj' => $resulte['ketspj'],
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_transout_koreksi2()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = " AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.ket) like upper('%$kriteria%')) ";
        }
        $sql = "SELECT ISNULL(MAX(tgl_terima),'2016-01-01') as tgl_terima FROM trhspj_ppkd WHERE cek='1' AND kd_skpd='$kd_skpd'";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res) {
            $tgl_terima = $res['tgl_terima'];
        }

        $sql = "SELECT count(*) as total from trhtransout a where a.panjar = '5' AND a.kd_skpd='$kd_skpd' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z where z.no_bukti = a.no_bukti and z.kd_skpd = a.kd_skpd) ketlpj,
		(CASE WHEN a.tgl_bukti<'$tgl_terima' THEN 1 ELSE 0 END ) ketspj FROM trhtransout a  
        WHERE  a.panjar = '5' AND a.kd_skpd='$kd_skpd' $where and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE  a.panjar = '5' AND a.kd_skpd='$kd_skpd' $where order by a.no_bukti)  order by a.no_bukti,kd_skpd";

        /*$sql = "SELECT TOP 70 PERCENT a.*,b.no_bukti AS nokas_pot,b.tgl_bukti AS tgl_pot,b.ket AS kete FROM trhtransout a LEFT JOIN trhtrmpot b ON  a.no_kas_pot=b.no_bukti 
        WHERE  a.kd_skpd='$kd_skpd' $where order by tgl_bukti,no_bukti,kd_skpd ";//limit $offset,$rows";
		*/
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_bukti' => $resulte['no_bukti'],
                'tgl_bukti' => $resulte['tgl_bukti'],
                'no_kas' => $resulte['no_kas'],
                'tgl_kas' => $resulte['tgl_kas'],
                'ket' => $resulte['ket'],
                'username' => $resulte['username'],
                'tgl_update' => $resulte['tgl_update'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'total' => $resulte['total'],
                'no_tagih' => $resulte['no_tagih'],
                'sts_tagih' => $resulte['sts_tagih'],
                'tgl_tagih' => $resulte['tgl_tagih'],
                'jns_beban' => $resulte['jns_spp'],
                'pay' => $resulte['pay'],
                'no_kas_pot' => $resulte['no_kas_pot'],
                'tgl_pot' =>  $resulte['tgl_pot'],
                'ketpot' => $resulte['kete'],
                'ketlpj' => $resulte['ketlpj'],
                'ketspj' => $resulte['ketspj'],
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_rek_koreksi2()
    {
        $jenis  = $this->input->post('jenis');
        $giat   = $this->input->post('giat');
        $kode   = $this->input->post('kd');
        $nomor  = $this->input->post('no');
        $sp2d   = $this->input->post('sp2d');
        $rek    = $this->input->post('rek');
        $lccr   = $this->input->post('q');
        if ($rek != '') {
            $notIn = " and kd_rek6 not in ($rek) ";
        } else {
            $notIn  = "";
        }
        $sql = "SELECT a.no_bukti, a.kd_rek6, a.nm_rek6,nilai,a.sumber
                FROM trdtransout a INNER JOIN trhtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
				WHERE a.kd_skpd='$kode' AND  b.no_sp2d = '$sp2d' and a.kd_sub_kegiatan='$giat' AND b.jns_spp = '$jenis' ORDER BY a.no_bukti";


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_bku' => $resulte['no_bukti'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'sumber' => $resulte['sumber'],
                'nilai' => $resulte['nilai']

            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }


    function ctk_jurnal_koreksi($dcetak = '', $dcetak2 = '', $skpd = '', $tgl_ttd = '', $ttd1 = '', $ttd2 = '', $spasi = '', $ctk = '')
    {
        $csql11 = " select nm_skpd from ms_skpd where kd_skpd = '$skpd'";
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper($trh1->nm_skpd);
        $tgl = $this->tukd_model->tanggal_format_indonesia($dcetak);
        $tgl2 = $this->tukd_model->tanggal_format_indonesia($dcetak2);
        $ttd1 = str_replace('123456789', ' ', $ttd1);
        $ttd2 = str_replace('123456789', ' ', $ttd2);
        $sqlsc = "SELECT kab_kota,daerah FROM sclient WHERE kd_skpd='$skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd1' and kode='PPK'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd2' and kode in ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }
        $cRet = "";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD $lcskpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN 2021</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        </table>
                        ";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
            
             <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>JURNAL KOREKSI</b>
                </td>
            </tr>
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">PERIODE $tgl  s/d  $tgl2
                </td>
            </tr>
            </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
            <thead>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Tanggal</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Nomor<br>Bukti</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Sub Kegiatan</b></td>
                <td colspan=\"6\" bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Rekening</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Uraian</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>ref</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"2\"><b>Jumlah Rp</b></td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Debit</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Kredit</b></td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">2</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"10%\">3</td>
                <td colspan=\"6\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"35%\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\"></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">7</td>
            </tr>
            </thead>
           ";
        /* 
         $csql1 = "select count(*) as tot FROM 
                 trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher and a.kd_unit=b.kd_skpd 
                 where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'"; 
         $rs = $this->db->query($csql1);
         $trh = $rs->row();
         
            
        $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek6,(c.nm_rek64 + case when (pos='0') then '' else ''end) AS nm_rek6,a.debet,a.kredit FROM 
                  trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher join (SELECT kd_rek64,nm_Rek64 from ms_rek5 group by kd_rek64,nm_Rek64) c on a.kd_rek6=c.kd_rek64
                  where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
                  ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek6";   
*/
        $csql = "SELECT a.tgl_bukti,a.no_bukti,b.nilai,b.kd_sub_kegiatan,kd_rek6,nm_rek6 from trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
                WHERE a.kd_skpd='$skpd' AND panjar='3' AND (tgl_bukti BETWEEN '$dcetak' AND '$dcetak2')
                 ORDER BY a.tgl_bukti ASC,a.no_bukti ASC, b.nilai DESC";

        $query = $this->db->query($csql);
        $cnovoc = '';
        $lcno = 0;
        foreach ($query->result_array() as $res) {
            $lcno = $lcno + 1;

            if ($cnovoc == $res['no_bukti']) {
                $cRet .= "<tr>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">" . $res['kd_sub_kegiatan'] . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 4, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 6, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 8, 4) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                                <td style=\"border-bottom:none;\"></td>";
                if ($res['nilai'] < 0) {
                    $cRet .= " <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'] * -1, "2", ",", ".") . "</td>";
                } else {
                    $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'], "2", ",", ".") . "</td>
                                               <td style=\"border-bottom:none;\"></td>";
                }

                $cRet .= "</tr>";
            } else {
                $cRet .= "<tr>
                                <td style=\"border-bottom:none\" align=\"center\">" . $this->tukd_model->tanggal_ind($res['tgl_bukti']) . "</td>
                                <td style=\"border-bottom:none\" align=\"center\">" . $res['no_bukti'] . "</td>
                                <td style=\"border-bottom:none;\">" . $res['kd_sub_kegiatan'] . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 4, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 6, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 8, 4) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                                <td style=\"border-bottom:none;\"></td>";
                if ($res['nilai'] < 0) {
                    $cRet .= " <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'] * -1, "2", ",", ".") . "</td>";
                } else {
                    $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'], "2", ",", ".") . "</td>
                                               <td style=\"border-bottom:none;\"></td>";
                }

                $cRet .= "</tr>";
            }
            $cnovoc = $res['no_bukti'];
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
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                    </tr>  
         </table> ";
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">Mengetahui,</td>
                <td align=\"center\" width=\"50%\">$daerah, " . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">$jabatan1</td>
                <td align=\"center\" width=\"50%\">$jabatan</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\"><u><b>$nama1</b></u><br>$pangkat1<br>NIP.$nip1</td>
                <td align=\"center\" width=\"50%\"><u><b>$nama</b></u><br>$pangkat<br>NIP.$nip</td>
            </tr>
            </table>
       ";
        $data['prev'] = $cRet; //'JURNAL UMUM';
        if ($ctk == '0') {
            echo ("<title>Jurna Koreksi $skpd</title>");
            echo $cRet;
        } else {
            $this->tukd_model->_mpdf('', $cRet, 5, 5, 5, '1');
        }

        //$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');    

    }


    function ctk_jurnal_koreksi2($dcetak = '', $dcetak2 = '', $skpd = '', $tgl_ttd = '', $ttd1 = '', $ttd2 = '', $spasi = '', $ctk = '')
    {
        $csql11 = " select nm_skpd from ms_skpd where kd_skpd = '$skpd'";
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper($trh1->nm_skpd);
        $tgl = $this->tukd_model->tanggal_format_indonesia($dcetak);
        $tgl2 = $this->tukd_model->tanggal_format_indonesia($dcetak2);
        $ttd1 = str_replace('123456789', ' ', $ttd1);
        $ttd2 = str_replace('123456789', ' ', $ttd2);
        $sqlsc = "SELECT kab_kota,daerah FROM sclient WHERE kd_skpd='$skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd1' and kode='PPK'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$skpd' and nip='$ttd2' and kode in ('PA','KPA')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip1 = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan1  = $rowttd->jab;
            $pangkat1  = $rowttd->pangkat;
        }
        $cRet = "";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"5\" align=\"left\" width=\"7%\">
                        <img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD $lcskpd </strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN 2021</strong></td></tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                        </table>
                        ";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
          
             <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\"><b>JURNAL KOREKSI</b>
                </td>
            </tr>
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">PERIODE $tgl  s/d  $tgl2
                </td>
            </tr>
            </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$spasi\">
            <thead>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Tanggal</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Nomor<br>Bukti</b></td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Sub Kegiatan</b></td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\"><b>Kode<br>Rekening</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>Uraian</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" rowspan=\"2\"><b>ref</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" colspan=\"2\"><b>Jumlah Rp</b></td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Debit</b></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"><b>Kredit</b></td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">2</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\" width=\"5%\">3</td>
                <td colspan=\"5\" bgcolor=\"#CCCCCC\" align=\"center\" width=\"15%\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"42%\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\"></td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\">7</td>
            </tr>
            </thead>
           ";

        /*$csql1 = "select count(*) as tot FROM 
                 trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher and a.kd_unit=b.kd_skpd 
                 where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'"; 
         $rs = $this->db->query($csql1);
         $trh = $rs->row();
         
            
        $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek6,(c.nm_rek64 + case when (pos='0') then '' else ''end) AS nm_rek6,a.debet,a.kredit FROM 
                  trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher join (SELECT kd_rek64,nm_Rek64 from ms_rek5 group by kd_rek64,nm_Rek64) c on a.kd_rek6=c.kd_rek64
                  where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
                  ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek6";   
*/
        $csql = "select a.tgl_bukti,a.no_bukti,b.nilai,b.kd_sub_kegiatan,kd_rek6,nm_rek6,
                (SELECT SUM (nilai) FROM trdtransout WHERE kd_skpd = '$skpd' AND panjar = '5' AND no_bukti=a.no_bukti) as total
                FROM trhtransout a INNER JOIN trdtransout b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd 
                WHERE a.kd_skpd='$skpd' AND panjar='5' AND (tgl_bukti BETWEEN '$dcetak' AND '$dcetak2')
                 ORDER BY a.tgl_bukti ASC,a.no_bukti ASC, b.nilai DESC";

        $query = $this->db->query($csql);
        $cnovoc = '';
        $lcno = 0;
        foreach ($query->result_array() as $res) {
            $lcno = $lcno + 1;
            if ($res['total'] > 0) {
                if ($res['nilai'] < 0) {
                    $cRet .= "<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">03</td>
                                <td style=\"border-bottom:none;\">01</td>
                                <td style=\"border-bottom:none;\">Kas di Bendahara Pengeluaran</td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['total'], "2", ",", ".") . "</td>
                                </tr>
                                <tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">" . $res['kd_sub_kegiatan'] . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 4, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 6, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 8, 4) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'] * -1, "2", ",", ".") . "</td> </tr>
                                ";
                } else {
                    $cRet .= "<tr>
                                <td style=\"border-bottom:none;border-top:none;\">" . $this->tukd_model->tanggal_ind($res['tgl_bukti']) . "</td>
                                <td style=\"border-bottom:none;border-top:none;\">" . $res['no_bukti'] . "</td>
                                <td style=\"border-bottom:none;\">" . $res['kd_sub_kegiatan'] . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 4, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 6, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 8, 4) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'], "2", ",", ".") . "</td>
                                <td style=\"border-bottom:none;\"></td></tr>
                                ";
                }
                $cnovoc = $res['no_bukti'];
            } else {
                //if($cnovoc==$res['no_bukti']){
                if ($res['nilai'] < 0) {
                    $cRet .= "<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">" . $res['kd_sub_kegiatan'] . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 4, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 6, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 8, 4) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'] * -1, "2", ",", ".") . "</td> </tr>
                                ";
                } else {
                    $cRet .= "<tr>
                                <td style=\"border-bottom:none;border-top:none;\">" . $this->tukd_model->tanggal_ind($res['tgl_bukti']) . "</td>
                                <td style=\"border-bottom:none;border-top:none;\">" . $res['no_bukti'] . "</td>
                                <td style=\"border-bottom:none;\">" . $res['kd_sub_kegiatan'] . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 4, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 6, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 8, 4) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['nilai'], "2", ",", ".") . "</td>
                                <td style=\"border-bottom:none;\"></td></tr>
                                <tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">1</td>
                                <td style=\"border-bottom:none;\">03</td>
                                <td style=\"border-bottom:none;\">01</td>
                                <td style=\"border-bottom:none;\">Kas di Bendahara Pengeluaran</td>
                                <td style=\"border-bottom:none;\"></td>
                                <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['total'] * -1, "2", ",", ".") . "</td>
                                <td style=\"border-bottom:none;\"></td></tr>
                                ";
                }
                $cnovoc = $res['no_bukti'];
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
                        <td style=\"border-top:none\"></td>
                    </tr>  
         </table> ";
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">Mengetahui,</td>
                <td align=\"center\" width=\"50%\">$daerah, " . $this->tukd_model->tanggal_format_indonesia($tgl_ttd) . "</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">$jabatan1</td>
                <td align=\"center\" width=\"50%\">$jabatan</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\">&nbsp;</td>
                <td align=\"center\" width=\"50%\"></td>
            </tr>
            <tr>
                <td align=\"center\" width=\"50%\"><u><b>$nama1</b></u><br>$pangkat1<br>NIP.$nip1</td>
                <td align=\"center\" width=\"50%\"><u><b>$nama</b></u><br>$pangkat<br>NIP.$nip</td>
            </tr>
            </table>
       ";
        $data['prev'] = $cRet; //'JURNAL UMUM';
        if ($ctk == '0') {
            echo ("<title>Jurna Koreksi $skpd</title>");
            echo $cRet;
        } else {
            $this->tukd_model->_mpdf('', $cRet, 5, 5, 5, '0');
        }

        //$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');    

    }
    // ------------------------
}
