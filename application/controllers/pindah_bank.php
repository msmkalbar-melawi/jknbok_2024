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
class pindah_bank extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('pcNama') == '') {
            redirect('welcome');
        }
    }

    function transout()
    {
        $data['page_title'] = 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI';
        $this->template->set('title', 'INPUT PEMBAYARAN TRANSAKSI NON TUNAI');
        $this->template->load('template', 'tukd/cms/transout_pndhbank', $data);
    }

    function load_dtagih()
    {
        $nomor = $this->input->post('no');
        $kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT b.*,
                (SELECT SUM(c.nilai) FROM trdtagih c LEFT JOIN trhtagih d ON c.no_bukti=d.no_bukti WHERE c.kd_sub_kegiatan = b.kd_sub_kegiatan AND 
                d.kd_skpd=a.kd_skpd AND c.kd_rek6=b.kd_rek AND c.no_bukti <> a.no_bukti AND d.jns_spp = a.jns_spp ) AS lalu,
                (SELECT e.nilai FROM trhspp e INNER JOIN trdspp f ON e.no_spp=f.no_spp INNER JOIN trhspm g ON e.no_spp=g.no_spp INNER JOIN trhsp2d h ON g.no_spm=h.no_spm
                WHERE h.no_sp2d = b.no_sp2d AND f.kd_sub_kegiatan=b.kd_sub_kegiatan AND f.kd_rek6=b.kd_rek6) AS sp2d,
                (SELECT SUM(nilai) FROM trdrka WHERE kd_sub_kegiatan = b.kd_sub_kegiatan AND kd_skpd=a.kd_skpd AND kd_rek6=b.kd_rek) AS anggaran FROM trhtagih a INNER JOIN
                trdtagih b ON a.no_bukti=b.no_bukti WHERE a.no_bukti='$nomor' and a.kd_skpd='$kd_skpd' ORDER BY b.kd_sub_kegiatan,b.kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_bukti'      => $resulte['no_bukti'],
                'no_sp2d'       => $resulte['no_sp2d'],
                'kd_subkegiatan'   => $resulte['kd_subkegiatan'],
                'nm_subkegiatan'   => $resulte['nm_subkegiatan'],
                'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek5'       => $resulte['kd_rek6'],
                'kd_rek'        => $resulte['kd_rek'],
                'nm_rek5'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai'],
                'lalu'          => $resulte['lalu'],
                'sp2d'          => $resulte['sp2d'],
                'anggaran'      => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function no_urut_tglcms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        date_default_timezone_set("Asia/Bangkok");
        $tgl = date('Y-m-d');
        $query1 = $this->db->query("SELECT case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
	select no_tgl nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' and tgl_voucher='$tgl') z WHERE KD_SKPD = '$kd_skpd'");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_trans_spd()
    { /*untuk transaksi konsep kota*/
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');
        $rek         = $this->input->post('kdrek6');
        $sp2d        = $this->input->post('sp2d');
        $spp = "";
        $org         = substr($kdskpd, 0, 17);
        $beban       = $this->input->post('beban');

        if ($beban == '1') {
            $sql = "SELECT SUM(nilai) total FROM 
                                    (
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6 = '$rek'
                                    AND d.jns_spp in ('1') 
                                    
                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6='$rek'
                                    AND d.jns_spp in ('1') 
                                    AND (d.status_validasi='0' OR d.status_validasi is null)
                                    
                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y 
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = '$kegiatan'
                                    AND x.kd_skpd = '$kdskpd'
                                    AND x.kd_rek6 = '$rek'
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
                                    
                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND t.kd_rek6 ='$rek' 
                                    AND u.kd_skpd = '$kdskpd' 
                                    AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
                                    )r";
        } else {
            // $spp         = $this->tukd_model->get_nama($this->input->post('nosp2d'),'no_spp','trhsp2d','no_sp2d');
            $sqlsc = "SELECT no_spp from trhsp2d where no_sp2d='$sp2d'";
            $sqlsclient = $this->db->query($sqlsc);
            foreach ($sqlsclient->result() as $rowsc) {
                $spp     = $rowsc->no_spp;
            }

            $sql = "SELECT SUM(nilai) total FROM 
                                    (
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6 = '$rek'
                                    AND d.jns_spp in ('1') 
                                    
                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6='$rek'
                                    AND d.jns_spp in ('1') 
                                    AND (d.status_validasi='0' OR d.status_validasi is null)
                                    
                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y 
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = '$kegiatan'
                                    AND x.kd_skpd = '$kdskpd'
                                    AND x.kd_rek6 = '$rek'
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND y.no_spp<>'$spp'
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
                                    
                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND t.kd_rek6 ='$rek' 
                                    AND u.kd_skpd = '$kdskpd' 
                                    AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
                                    )r";
        }



        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ','),
                'totals' => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_trans_bnk()
    {
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');

        $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                    (           
                                        select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                        where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') 
                                        and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                        group by c.kd_sub_kegiatan
                                    ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                    left join 
                                    (
                                        SELECT z.kd_sub_kegiatan, SUM(z.transaksi) as transaksi FROM (
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where left(f.kd_skpd,17)='$kdskpd' and f.kd_sub_kegiatan='$kegiatan' and e.no_bukti<>'$no_bukti' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        UNION ALL
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd
                                        where left(f.kd_skpd,17)='$kdskpd' and f.kd_sub_kegiatan='$kegiatan' and e.no_bukti<>'$no_bukti' and e.jns_spp ='1'
                                        and e.status_validasi='0'
                                        group by f.kd_sub_kegiatan) z
                                        group by z.kd_sub_kegiatan
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                    left join 
                                    (
                                        SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                        INNER JOIN trhtagih u 
                                        ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                        WHERE t.kd_sub_kegiatan = '$kegiatan' 
                                        AND u.kd_skpd='$kdskpd'
                                        AND u.no_bukti 
                                        NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                        GROUP BY t.kd_sub_kegiatan
                                    ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                    where a.kd_sub_kegiatan='$kegiatan'";



        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_total_trans_bnk_lama()
    {
        $kdskpd      = $this->input->post('kode');
        $kegiatan    = $this->input->post('giat');
        $no_bukti    = $this->input->post('no_simpan');

        $sql = "SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
									(           
										select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
										where c.kd_sub_kegiatan='$kegiatan' and b.jns_spp not in ('1','2') and left(b.kd_skpd,17)=left('$kdskpd',17) 
										and (sp2d_batal<>'1' or sp2d_batal is null ) 
										group by c.kd_sub_kegiatan
									) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
									left join 
									(
										select z.kd_sub_kegiatan,sum(z.transaksi) transaksi from (
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout_cmsbank e join trdtransout_cmsbank f on e.no_voucher=f.no_voucher and e.kd_skpd=f.kd_skpd 
                                        where left(f.kd_skpd,17)=left('$kdskpd',17) and f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' and e.status_validasi='0' group by f.kd_sub_kegiatan
                                        UNION ALL
                                        select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                        from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                        where left(f.kd_skpd,17)=left('$kdskpd',17) and f.kd_sub_kegiatan='$kegiatan' and e.jns_spp ='1' and e.no_bukti<>'$no_bukti' group by f.kd_sub_kegiatan
                                        )z group by z.kd_sub_kegiatan
                                    ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
									left join 
									(
										SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
										INNER JOIN trhtagih u 
										ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
										WHERE t.kd_sub_kegiatan = '$kegiatan' 
										AND left(u.kd_skpd,17)=left('$kdskpd',17)
										AND u.no_bukti 
										NOT IN (select no_tagih FROM trhspp WHERE left(kd_skpd,17)=left('$kdskpd',17) )
										GROUP BY t.kd_sub_kegiatan
									) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
									where a.kd_sub_kegiatan='$kegiatan'";


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'total' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function simpan_transout_bnk()
    {
        $tabel    = $this->input->post('tabel');
        $nomor    = $this->input->post('no');
        $nomor_tgl = $this->input->post('notgl');
        $tgl      = $this->input->post('tgl');
        $nokas    = $this->input->post('nokas');
        $tglkas   = $this->input->post('tglkas');
        $nokaspot = $this->input->post('nokas_pot');
        $skpd     = $skpd = $this->session->userdata('kdskpd');
        $nmskpd   = $this->input->post('nmskpd');
        $beban    = trim($this->input->post('beban'));
        $ket      = $this->input->post('ket');
        $status   = $this->input->post('status');
        $notagih  = $this->input->post('notagih');
        $tgltagih = $this->input->post('tgltagih');
        $total    = $this->input->post('total');
        $csql     = $this->input->post('sql');
        $csqlrek     = $this->input->post('sqlrek');
        $usernm   = $this->session->userdata('pcNama');
        $xpay     = $this->input->post('cpay');
        $nosp2d   = $this->input->post('nosp2d2');
        $xrek     = $this->input->post('xrek');

        $rek_awal = trim($this->input->post('rek_awal'));
        $anrekawal = $this->input->post('anrek_awal');
        $rek_tjn  = $this->input->post('rek_tjn');
        $rek_bnk  = $this->input->post('rek_bnk');
        $init_ket = $this->input->post('cinit_ket');
        $stt_val  = 0;
        $stt_up   = 0;

        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        // Simpan Header //
        if ($tabel == 'trhtransout') {
            $sql = "delete from trhtransout where kd_skpd='$skpd' and no_bukti='$nomor'";
            $asg = $this->db->query($sql);

            if ($asg) {
                $sql = "insert into trhtransout(no_kas,tgl_kas,no_bukti,tgl_bukti,ket,username,tgl_update,kd_skpd,nm_skpd,total,no_tagih,sts_tagih,tgl_tagih,jns_spp,pay,no_kas_pot,panjar,no_sp2d) 
                        values('$nokas','$tglkas','$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total','$notagih','$status','$tgltagih','$beban','$xpay','$nokaspot','0','$nosp2d')";
                $asg = $this->db->query($sql);
            } else {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } elseif ($tabel == 'trdtransout') {
            // Simpan Detail //                                       

            $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);



            $sql = "delete from trdtransout_transfer where no_bukti='$nomor' AND kd_skpd='$skpd'";
            $asg = $this->db->query($sql);

            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into trdtransout(no_bukti,no_sp2d,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,kd_skpd,sumber)";
                $asg = $this->db->query($sql . $csql);

                if ($csqlrek != '') {
                    $sql = "insert into trdtransout_transfer(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)";
                    $asg = $this->db->query($sql . $csqlrek);
                }



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


    function load_transout_bnk()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $cek_skpd1     = explode('.', $kd_skpd);

        if ($cek_skpd1[7] == '0000') {
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        } else {
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        }

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 30;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(a.no_bukti) like upper('%$kriteria%') or a.tgl_bukti like '%$kriteria%' or upper(a.ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhtransout a where a.pay='BANK' and a.panjar = '0' AND $init_skpd  $where";

        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,(select count(*) from trhtrmpot where no_kas=a.no_bukti and kd_skpd=a.kd_skpd) AS kete,(SELECT COUNT(*) from trlpj z 
        join trhlpj v on v.no_lpj = z.no_lpj
        where v.jenis=a.jns_spp and z.no_bukti = a.no_bukti and z.kd_bp_skpd = a.kd_skpd) ketlpj,
		0 ketspj,(select rekening from ms_skpd where kd_skpd='$kd_skpd') as rekening_awal FROM trhtransout a  
        WHERE a.panjar = '0' AND $init_skpd $where and a.pay='BANK' and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE a.panjar = '0' AND $init_skpd $where order by a.no_bukti) 
         order by a.no_bukti,kd_skpd ";

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
                'rekening_awal' => $resulte['rekening_awal']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtransout_bnk()
    {
        $kd_skpd = $this->session->userdata('kdskpd');

        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
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
                'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek5'       => $resulte['kd_rek6'],
                'nm_rek5'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai'],
                'nilai_nformat' => number_format($resulte['nilai'], 2),
                'sumber'        => $resulte['sumber'],
                'lalu'          => $resulte['lalu'],
                'sp2d'          => $resulte['sp2d'],
                'anggaran'      => $resulte['anggaran']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dtransout_transfer_bnk()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_bukti,b.tgl_bukti,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,(select sum(nilai) from trdtransout_transfer where no_bukti=b.no_bukti and kd_skpd=b.kd_skpd) as total
				FROM trhtransout a INNER JOIN trdtransout_transfer b ON a.no_bukti=b.no_bukti
				AND a.kd_skpd=b.kd_skpd 
				WHERE b.no_bukti='$nomor' AND b.kd_skpd='$skpd'
                group by b.no_bukti,b.tgl_bukti,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai
				";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'                => $ii,
                'no_bukti'        => $resulte['no_bukti'],
                'tgl_bukti'       => $resulte['tgl_bukti'],
                'rekening_awal'     => $resulte['rekening_awal'],
                'nm_rekening_tujuan' => $resulte['nm_rekening_tujuan'],
                'rekening_tujuan'   => $resulte['rekening_tujuan'],
                'bank_tujuan'       => $resulte['bank_tujuan'],
                'nilai'             => number_format($resulte['nilai'], 2),
                'total'             => number_format($resulte['total'], 2),
                'kd_skpd'           => $resulte['kd_skpd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dpot()
    {
        $nomor = $this->input->post('no');
        $sql = "SELECT * from trdtrmpot where no_bukti='$nomor' ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id'            => $ii,
                'no_bukti'      => $resulte['no_bukti'],
                'kd_rek5'       => $resulte['kd_rek6'],
                'nm_rek5'       => $resulte['nm_rek6'],
                'nilai'         => $resulte['nilai']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_tgltransout_bnk()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $cek_skpd1     = explode('.', $kd_skpd);

        if ($cek_skpd1[7] == '0000') {
            $init_skpd = "a.kd_skpd='$kd_skpd'";
        } else {
            if ($cek_skpd1[7] == '0000') {
                $init_skpd = "left(a.kd_skpd,7)=left('$kd_skpd',7)";
            } else {
                $init_skpd = "a.kd_skpd='$kd_skpd'";
            }
        }

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND a.tgl_bukti='$kriteria'";
        }

        $sql = "SELECT count(*) as total from trhtransout a where a.pay='BANK' and a.panjar = '0' AND $init_skpd $where 
        and a.no_bukti not in (select no_bukti from trhtransout_cmsbank a WHERE  a.panjar = '0' AND $init_skpd $where)";

        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT top $rows  a.*,'' AS nokas_pot,'' AS tgl_pot,'' AS kete,(SELECT COUNT(*) from trlpj z 
        join trhlpj v on v.no_lpj = z.no_lpj
        where v.jenis=a.jns_spp and z.no_bukti = a.no_bukti and z.kd_bp_skpd = a.kd_skpd) ketlpj,
		0 ketspj FROM trhtransout a  
        WHERE  a.panjar = '0' AND $init_skpd $where and a.pay='BANK' and a.no_bukti not in (SELECT top $offset a.no_bukti FROM trhtransout a  
        WHERE a.panjar = '0' AND a.pay='BANK' and $init_skpd $where order by CAST (a.no_bukti as NUMERIC)) and 
        a.no_bukti not in (select no_bukti from trhtransout_cmsbank a WHERE a.panjar = '0' and a.pay='BANK' AND $init_skpd $where)
         order by CAST (a.no_bukti as NUMERIC),kd_skpd ";

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

    function hapus_transout_bnk()
    {
        $kd_skpd     = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
        $asg = $this->db->query($sql);

        if ($asg) {

            $sql = "delete from trhtransout where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
            $asg = $this->db->query($sql);

            $sql = "delete from trdtransout_transfer where no_bukti='$nomor' AND kd_skpd='$kd_skpd'";
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
}
