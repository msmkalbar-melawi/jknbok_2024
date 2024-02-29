<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class cpanjar_cms extends CI_Controller
{

    public $org_keu = "";
    public $skpd_keu = "";

    function __contruct()
    {
        parent::__construct();
    }

    function index()
    {
        $data['page_title'] = 'INPUT PEMBERIAN PANJAR NON TUNAI';
        $this->template->set('title', 'INPUT PEMBERIAN PANJAR NON TUNAI');
        $this->template->load('template', 'tukd/cms/panjar_cmsbank', $data);
    }


    function cetak_list_panjarcms()
    {
        $this->load->library('tanggal_indonesia');
        $kd_skpd = $this->session->userdata('kdskpd');
        $thn     = $this->session->userdata('pcThang');
        $tgl     = $this->uri->segment(3);
        // echo ($tgl);
        // return;
        //$a = strtoupper($this->tanggal_indonesia->tanggal_format_indonesia($tgl));
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$kd_skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
        }

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>" . $kab . "<br>LIST TRANSAKSI </b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE " . $this->tanggal_indonesia->tanggal_format_indonesia($tgl) . " </b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"2\" style=\"font-size:12px;border: solid 1px white;\">SKPD</td>
                <td align=\"left\" colspan=\"14\" style=\"font-size:12px;border: solid 1px white;\">:&nbsp;" . strtoupper($this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd')) . "</td>
            </tr>
            </table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead>
            <tr> 
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold;\">SKPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold;\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"32%\" style=\"font-size:12px;font-weight:bold;\">Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Penerimaan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold;\">Pengeluaran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"4%\" style=\"font-size:12px;font-weight:bold;\">ST</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">2</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black;\">6</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;border-top:solid 1px black;\">7</td>
            </tr>
            </thead>";


        $init_skpd = "a.kd_skpd='$kd_skpd'";
        $init_skpd2 = "kode='$kd_skpd'";


        $no = 0;
        $tot_terima = 0;
        $tot_keluar = 0;
        $sql = "select z.* from (
            select '1' urut,a.kd_skpd,a.tgl_kas,a.no_kas,'' kegiatan,'' rekening, a.keterangan ket, 0 terima, 0 keluar, '' jns_spp, a.status_upload
            from tr_panjar_cmsbank a where year(a.tgl_kas) = '$thn' and a.tgl_kas='$tgl' and $init_skpd
            UNION
            select '2' urut,a.kd_skpd,a.tgl_kas,a.no_kas,a.kd_sub_kegiatan kegiatan,'' rekening, '' ket, 0 terima, a.nilai keluar, '' jns_spp, '' status_upload
            from tr_panjar_cmsbank a where year(a.tgl_kas) = '$thn' and a.tgl_kas='$tgl' and $init_skpd
            )z order by z.kd_skpd,z.tgl_kas,cast (z.no_kas as int), z.urut";
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $no = $no++;

            if ($row->urut == '1') {
                $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:none;\">" . $row->no_kas . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->kd_skpd . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->kegiatan . "." . $row->rekening . "</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->ket . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->status_upload . "</td>                                       
                 </tr>";
            } else if ($row->urut == '3') {
                $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->kegiatan . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . $row->ket . "&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">&nbsp;</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\">" . number_format($row->keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;border-bottom:solid 1px gray;\"></td>                                       
                 </tr>";
            } else {
                $cRet .= "<tr>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . $row->kegiatan . "." . $row->rekening . "</td>
                    <td valign=\"top\" align=\"left\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . $row->ket . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . number_format($row->terima, 2) . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:none;border-bottom:none;\">" . number_format($row->keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:none;border-bottom:none;\">&nbsp;</td>                                        
                 </tr>";
            }

            if ($row->urut != '3') {
                $tot_terima = $tot_terima + $row->terima;
                $tot_keluar = $tot_keluar + $row->keluar;
            }
        }

        $asql = "select
            SUM(case when jns=1 then jumlah else 0 end) AS terima,
            SUM(case when jns=2 then jumlah else 0 end) AS keluar
            from (
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) c on b.no_spm=c.no_spm WHERE pay='BANK' UNION ALL
            SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union ALL
            SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain WHERE pay='BANK') a
            where tgl<='$tgl' and $init_skpd2";

        $hasil = $this->db->query($asql);
        $bank = $hasil->row();
        $keluarbank = $bank->keluar;
        $terimabank = $bank->terima;
        $saldobank = $terimabank - $keluarbank;

        $saldoakhirbank = (($saldobank + $tot_terima) - $tot_keluar);

        $cRet .= "
                <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"4\" style=\"font-size:11px;border-top:1px solid black;\">JUMLAH</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">" . number_format($tot_terima, 2) . "</td>
                    <td valign=\"top\" align=\"right\" style=\"font-size:11px;border-top:1px solid black;\">" . number_format($tot_keluar, 2) . "</td>
                    <td valign=\"top\" align=\"center\" style=\"font-size:11px;border-top:1px solid black;\">&nbsp;</td>                                        
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"center\" colspan=\"9\" style=\"font-size:11px;border:none;\"><br/></td>                                                   
                 </tr> 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Saldo Sampai Dengan Tanggal " . $this->tanggal_indonesia->tanggal_format_indonesia($tgl) . ", </td>                                                   
                 </tr>  
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($saldobank, 2) . "</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Terima</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($tot_terima, 2) . "</td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Jumlah Keluar</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($tot_keluar, 2) . "</td>                                                   
                 </tr>                                 
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\"><hr/></td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"9\" style=\"font-size:11px;border:none;\">Perkiraan Akhir Saldo, </td>                                                   
                 </tr>
                 <tr>
                    <td valign=\"top\" align=\"left\" colspan=\"2\" style=\"font-size:11px;border:none;\">- Saldo Bank</td> 
                    <td valign=\"top\" align=\"left\" colspan=\"7\" style=\"font-size:11px;border:none;\">: Rp. " . number_format($saldoakhirbank, 2) . "</td>                                                   
                 </tr>                                 
                                                  
            </table>";

        $data['prev'] = $cRet;
        echo $cRet;
        //$this->_mpdf_margin('',$cRet,10,10,10,'0',1,'',3);                         

    }

    function cari_rekening()
    {
        $lccr =  $this->session->userdata('kdskpd');

        $sql = "SELECT top 1 rekening FROM ms_skpd where kd_skpd='$lccr' order by kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'rek_bend' => $resulte['rekening']
            );
        }
        echo json_encode($result);
    }

    function cari_rekening_tujuan($jenis = '')
    {
        $skpd = $this->session->userdata('kdskpd');
        $lccr = $this->input->post('q');

        if ($jenis == 1) {
            $jenis = "('1','2')";
        } else {
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
        foreach ($query1->result_array() as $resulte) {

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
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama']
            );
        }

        echo json_encode($result);
    }

    function no_urut_cms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $tgl = date('Y-m-d');
        $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_voucher nomor, 'Daftar Transaksi Non Tunai' ket, kd_skpd from trhtransout_cmsbank where kd_skpd = '$kd_skpd' union
    select no_bukti nomor, 'Potongan Pajak Transaksi Non Tunai' ket, kd_skpd from trhtrmpot_cmsbank where kd_skpd = '$kd_skpd' union
    select no_panjar nomor, 'Daftar Panjar' ket, kd_skpd from tr_panjar_cmsbank where kd_skpd = '$kd_skpd' 
    ) z WHERE KD_SKPD = '$kd_skpd'");
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


    function load_panjar()
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
            $where = "and (upper(no_panjar) like upper('%$kriteria%') or tgl_panjar like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%'))";
        }

        $sql = "SELECT count(*) as total from tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='1' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='1' $where order by no_panjar) and jns='1' order by no_panjar";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_kas' => $resulte['no_kas'],
                'tgl_kas' => $resulte['tgl_kas'],
                'no_panjar' => $resulte['no_panjar'],
                'tgl_panjar' => $resulte['tgl_panjar'],
                'kd_skpd' => $resulte['kd_skpd'],
                'keterangan' => $resulte['keterangan'],
                'nilai' => number_format($resulte['nilai'], 2),
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

    function load_dtrpanjar_transfercms()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $nomor = $this->input->post('no');
        $skpd  = $this->input->post('skpd');
        $sql = "SELECT b.no_bukti,b.tgl_bukti,b.rekening_awal,b.nm_rekening_tujuan,b.rekening_tujuan,
                b.bank_tujuan,b.kd_skpd,b.nilai,(select sum(nilai) from tr_panjar_transfercms where no_bukti=b.no_bukti and kd_skpd=b.kd_skpd and tgl_bukti=b.tgl_bukti) as total
                FROM tr_panjar_cmsbank a INNER JOIN tr_panjar_transfercms b ON a.no_kas=b.no_bukti
                AND a.kd_skpd=b.kd_skpd and a.tgl_kas=b.tgl_bukti
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

    function load_panjar_tgl()
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
            $where = "and tgl_kas='$kriteria'";
        }

        $sql = "SELECT count(*) as total from tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='1' $where ";
        //$sql = "SELECT count(*) as total from trhtransout a where a.kd_skpd='$kd_skpd' and a.jns_spp in ('1','2','3') $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();


        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        $sql = "SELECT top $rows * from tr_panjar_cmsbank where kd_skpd='$kd_skpd' $where and no_panjar not in (SELECT top $offset no_panjar FROM tr_panjar_cmsbank  where kd_skpd='$kd_skpd' and jns='1' $where order by no_panjar) and jns='1'  order by no_panjar";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

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

    function simpan_master_panjar()
    {
        $kd_skpd  = $this->session->userdata('kdskpd');
        $tabel  = $this->input->post('tabel');
        $lckolom = $this->input->post('kolom');
        $lcnilai = $this->input->post('nilai');
        $cid = $this->input->post('cid');
        $lcid = $this->input->post('lcid');
        $sqlrek = $this->input->post('sqlrek');

        $sql = "select $cid from $tabel where $cid='$lcid' AND kd_skpd='$kd_skpd'";
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            echo '1';
        } else {
            $sql = "insert into $tabel $lckolom values $lcnilai";
            $asg = $this->db->query($sql);

            $sqlss = "insert into tr_panjar_transfercms(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)";
            $asg = $this->db->query($sqlss . $sqlrek);

            if ($asg) {
                echo '2';
            } else {
                echo '0';
            }
        }
    }

    function update_master2()
    {
        $query = $this->input->post('st_query');
        $query2 = $this->input->post('sqlrek');
        $query3 = $this->input->post('lcid');
        $query4 = $this->input->post('xskpd');

        $sql = "delete from tr_panjar_transfercms where kd_skpd='$query4' and no_bukti='$query3'";
        $asg = $this->db->query($sql);

        $sql = "insert into tr_panjar_transfercms(no_bukti,tgl_bukti,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,kd_skpd,nilai)";
        $asg = $this->db->query($sql . $query2);

        $asg = $this->db->query($query);
        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }

    function hapus_panjar_cmsbank()
    {
        //no:cnomor,skpd:cskpd
        $nomor = $this->input->post('no');
        $skpd  = $this->session->userdata('kdskpd');

        $sql = "delete from tr_panjar_cmsbank where no_panjar='$nomor' and kd_skpd = '$skpd' and jns='1'";
        $asg = $this->db->query($sql);

        $sql = "delete from tr_panjar_transfercms where no_bukti='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);

        if ($asg) {
            echo '1';
        } else {
            echo '0';
        }
    }
}
