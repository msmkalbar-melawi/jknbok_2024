<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tukd_pusk extends CI_Controller {

    function __contruct()
    {   
        parent::__construct();
    }
        
    function laporan_sp3b_blud()
    {
        $data['page_title']= 'CETAK SP3B (BLUD)';
        $this->template->set('title', 'CETAK SP3B (BLUD)');   
        $this->template->load('template','/tukd/transaksi_pusk/cetak_sp3b_blud',$data) ; 
    }

    function load_sp3b_blud() {
        $kd_skpd     = $this->session->userdata('kdskpd');         
        $par = "a.skpd='$kd_skpd'";
        $par2 = "skpd='$kd_skpd'";        
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and (upper(a.no_sp3b) like upper('%$kriteria%') or a.tgl_sp3b like '%$kriteria%' or a.kd_skpd like'%$kriteria%' or
            upper(a.keterangan) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhsp3b_blud a where $par $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
       echo $sql = "
        SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd_blud WHERE kd_skpd = a.kd_skpd) AS nm_skpd,(SELECT nm_skpd FROM ms_skpd_blud WHERE kd_skpd = a.kd_skpd) AS nm_skpd2 from trhsp3b_blud a where $par 
        $where  AND a.no_sp3b NOT IN (SELECT top $offset no_sp3b FROM trhsp3b_blud where $par2 ORDER BY tgl_sp3b, no_sp3b)order by a.tgl_sp3b, a.no_sp3b
        ";
        
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                                
            $row[] = array( 
                        'id' => $ii,        
                        'no_sp3b' => $resulte['no_sp3b'],
                        'tgl_sp3b' => $resulte['tgl_sp3b'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),                        
                        'status' => $resulte['status'],                     
                        'no_lpj' => $resulte['no_lpj'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'nm_skpd2' => $resulte['nm_skpd2'],
                        'bulan' => intval($resulte['bulan']),
                        'status_bud' => $resulte['status_bud']
                        );
                        $ii++;
                }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
           
    } 
    function load_dtrsp3b_blud() {           
        $kriteria = $this->input->post('no');        
        $skpd = $this->input->post('skpd');
        
        $sql = "SELECT a.*, a.kd_rek6 as rek, '' as nm_rek
        from trsp3b_blud a where a.no_sp3b = '$kriteria' and left(a.kd_skpd,22)=left('$skpd',22) order by a.no_sp3b";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_sp3b' => $resulte['no_sp3b'],
                        'no_lpj' => $resulte['no_lpj'],
                        'no_bukti' => $resulte['no_bukti'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_rek5' => $resulte['rek'],
                        'kd_rek7' => $resulte['kd_rek6'],
                        'nm_rek7' => $resulte['nm_rek6'],
                        'nm_rek' => $resulte['nm_rek'],
                        'nilai' => $resulte['nilai'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    } 
    function skpd_3(){
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_skpdd = substr($kd_skpd,0,7);
        ECHO $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd_blud where left(kd_skpd,4) = left('$kd_skpdd',4) and kd_skpd <> ('$kd_skpd')";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();      
    }

    function cetak_sp3b_blud($lcskpd='',$nbulan='',$ctk=''){
        $pusk = $this->uri->segment(6);
        $nip2 = str_replace('123456789',' ',$this->uri->segment(6));
        $ketsaldo = ''; 
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(7));
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);   
        $nosp3b = str_replace('hhh','/',$this->uri->segment(13));
        $sp3b = $this->uri->segment(14);
        $nilai_saldo = 0;        
        //echo $nosp3b;
        $nilai_saldo = 0;        
        $saldo=0;
        $n_saldo=0;
        
            
            $n = $this->db->query("SELECT sum(sld_awal) sld_awal from (
                SELECT ISNULL(saldo_lalu,0) as sld_awal from ms_skpd_blud where kd_skpd='$lcskpd'
                union all
                select 0 ) okei
                ")->row();            
            $saldo = $n->sld_awal;
        
             $sql1=$this->db->query(" SELECT sum(isnull(c.terima,0))-sum(isnull(c.keluar,0)) nilai from(
                SELECT
                case when left(a.kd_rek6,1)=4 then SUM (isnull(a.nilai,0)) end as terima,
                case when left(a.kd_rek6,1)=5 then SUM (isnull(a.nilai,0)) end as keluar
                FROM
                trsp3b_blud a 
                WHERE a.kd_skpd = '$lcskpd'
                AND month(a.tgl_sp3b) < '$nbulan'
                group by left(a.kd_rek6,1))c
                    ")->row();    
                
            $nilai_saldo = $sql1->nilai;
            $n_saldo = $sql1->nilai;    
                
        
        if($nbulan==1){
                $nilai_saldo = $saldo; 
                $ketsaldo = "Awal";
        }else{
                $nilai_saldo = $saldo+$n_saldo; 
                $ketsaldo = "Lalu";    
        }
            
        
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        //$nmpusk = $this->tukd_model->get_nama($nmskpd,'nm_skpd','ms_skpd','kd_skpd');
        // $cekno_lpjj = $this->db->query("select * from trhsp3b_blud where no_sp3b='$nosp3b'")->row();
        // $no_lpjj = $cekno_lpjj->no_lpj;

        $cek = "SELECT
            a.no_sp3b as no_sp3b, 
            a.tgl_sp3b as tgl_sp3b,
            b.nm_skpd as nm_skpd
        FROM
            trhsp3b_blud a inner join 
            ms_skpd_blud b on a.kd_skpd = b.kd_skpd 
        WHERE
            a.kd_skpd = '$lcskpd' 
            AND MONTH ( a.tgl_sp3b ) = '$nbulan'";

        $sqlcek=$this->db->query($cek);
        foreach ($sqlcek->result() as $rowcek)
        {
            $nosp3b     = $rowcek->no_sp3b;
            $tglsp3b    = $rowcek->tgl_sp3b;
            $nmskpd     = $rowcek->nm_skpd ;
        }


        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                $sqlsclient=$this->db->query($sqlsc);
                foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$nip2'";
                $sqlttd=$this->db->query($sqlttd1);
                foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }       
        
            $cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.$prov.' <br>
                                            <b>DINAS KESEHATAN</b> <br>
                                                SURAT PERMINTAAN PENGESAHAN PENDAPATAN DAN BELANJA (SP3B) BLUD</b><br>
                                            Tanggal : '.$this->tukd_model->tanggal_format_indonesia($tglsp3b).' &nbsp;&nbsp; Nomor : '.$nosp3b.'     
                                                  
                        </TD>
                    </TR>                   
                    </TABLE>';          
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" >Kepala SKPD Dinas Kesehatan Kabupaten Sanggau memohon kepada : </TD>                     
                    </TR>                   
                    </TABLE>';
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" >Bendahara Umum Daerah Selaku PPKD </TD>                       
                    </TR>                   
                    </TABLE>';                    
    /*            untuk saldo awal*/
    $sql="SELECT sum(isnull(c.terima,0)) terima,sum(isnull(c.keluar,0)) keluar from(
                SELECT
                case when left(kd_rek6,1)=4 then SUM (isnull(nilai,0)) end as terima,
                case when left(kd_rek6,1)=5 then SUM (isnull(nilai,0)) end as keluar
                FROM
                trsp3b_blud
                WHERE kd_skpd = '$lcskpd'
                /*AND no_sp3b = '$nosp3b'*/ and month(tgl_sp3b) <= '$nbulan'
                group by left(kd_rek6,1))c";
            $exe=$this->db->query($sql)->row();
            $pendapatan=$exe->terima;
            $belanja=$exe->keluar;
            $saldox=$saldo+$pendapatan-$belanja;

            /*untuk header*/
    $sqlxx="SELECT sum(isnull(c.terima,0)) terima,sum(isnull(c.keluar,0)) keluar from(
                SELECT
                case when left(kd_rek6,1)=4 then SUM (isnull(nilai,0)) end as terima,
                case when left(kd_rek6,1)=5 then SUM (isnull(nilai,0)) end as keluar
                FROM
                trsp3b_blud
                WHERE kd_skpd = '$lcskpd'
                /*AND no_sp3b = '$nosp3b'*/
                and month(tgl_sp3b) <= '$nbulan'
                group by left(kd_rek6,1))c";
            $exex=$this->db->query($sqlxx)->row();
            $pendapatan2=$exex->terima;
            $belanja2=$exex->keluar;
            
                            
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" width="100%" colspan="3">Agar mengesahkan dan membukukan pendapatan dan belanja dana BLUD sejumlah :</TD>                      
                    </TR>   
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">1. &nbsp; Saldo '.$ketsaldo.'</TD>                     
                        <TD align="left" width="65%">Rp. '.number_format($nilai_saldo,'2',',','.').'</TD>                       
                    </TR>
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">2. &nbsp; Pendapatan</TD>                      
                        <TD align="left" width="65%">Rp. '.number_format($pendapatan2,'2',',','.').'</TD>                     
                    </TR>   
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">3. &nbsp; Belanja</TD>                     
                        <TD align="left" width="65%">Rp. '.number_format($belanja2,'2',',','.').'</TD>                     
                    </TR>   
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">4. &nbsp; Saldo Akhir</TD>                     
                        <TD align="left" width="65%">Rp. '.number_format($saldox,'2',',','.').'</TD>                      
                    </TR>   
                    </TABLE>';  
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" >Untuk bulan : <b>'.strtoupper($this->tukd_model->getBulan($nbulan)).'</b></TD>                        
                        <TD align="left" >Tahun Anggaran : <b>'.$thn.'</b></TD>                     
                    </TR>                   
                    </TABLE>';  
                    
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" width="18%">Dasar Pengesahan</TD>                      
                        <TD align="left" width="17%">Urusan</TD>                    
                        <TD align="left" width="28%">Organisasi</TD>                        
                        <TD align="center" width="37%">Nama</TD>                           
                    </TR>
                    <TR>
                        <TD align="left" ></TD>                     
                        <TD align="left" >1.02 Kesehatan</TD>                   
                        <TD align="left" >1.02.1.02.01 Dinas Kesehatan</TD>                     
                        <TD align="center" rowspan="2"><b>'.$nmskpd.'</b></TD>                          
                    </TR>   
                    <TR>
                        <TD align="left" ></TD>                     
                        <TD align="left" >Upaya Kesehatan Masyarakat</TD>                 
                        <TD align="left" >Penyediaan Biaya Operasional dan Pemeliharaan</TD>                                                                       
                    </TR>   
                    </TABLE>';
            
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="center" colspan="2" width="50%" style="border-collapse:collapse; border-right:solid 1px black;"><b>PENDAPATAN</b></TD>                       
                        <TD align="center" colspan="3" width="50%"><b>BELANJA</b></TD>                                          
                    </TR>
                    <TR>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="30%">
                        <b>Kode Rekening</b>                        
                        </TD>                       
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
                        <b>Jumlah</b>
                        </TD>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="30%">
                        <b>Kode Rekening</b>
                        </TD>                       
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
                        <b>Jumlah</b>
                        </TD>
                    </TR>';
                    
            
            
            $sql2="SELECT * from ( 
    SELECT 
    case when left(c.kd_rek6,1)=4 then kd_rek6 end as kd_pen,
    case when left(c.kd_rek6,1)=4 then nm_rek6 end as nm_pen,
    case when left(c.kd_rek6,1)=4 then nilai end as real_pen,

    case when left(c.kd_rek6,1)=5 then kd_rek6 end as kd_bel,
    case when left(c.kd_rek6,1)=5 then nm_rek6 end as nm_bel,
    case when left(c.kd_rek6,1)=5 then nilai end as real_bel
    from(


    select kd_rek6, nilai, nm_rek6 from trsp3b_blud a INNER JOIN trhsp3b_blud b ON a.no_sp3b=b.no_sp3b and a.kd_skpd=b.kd_skpd
    WHERE a.no_sp3b='$nosp3b' AND a.kd_skpd='$lcskpd'


    )c ) xxx WHERE real_pen <> 0 or real_bel <> 0
    order by kd_pen desc

                    ";  //echo "$sql2";
                $jum_bel4=0; $jum_bel5=0;
                $sql2=$this->db->query($sql2);
                foreach ($sql2->result() as $row)
                {
                    $kd_rek4  = $row->kd_pen;
                    $nm_rek4  = $row->nm_pen;                    
                    $nilai4  = $row->real_pen; 
                    $kd_rek5  = $row->kd_bel;
                    $nm_rek5  = $row->nm_bel;                    
                    $nilai5  = $row->real_bel;                  
                    $jum_bel4=$jum_bel4+$nilai4;
                    $jum_bel5=$jum_bel5+$nilai5;


            
            $cRet .='
            <TR>
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="10%">
                            '.$kd_rek4.'
                        </TD>                       
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="25%"> 
                            '.$nm_rek4.'                     
                        </TD>
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">                      
                        '.number_format($nilai4,'2',',','.').'
                        </TD>
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="10%">                 
                        '.$kd_rek5.'
                        </TD>   
                        <TD align="left" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="25%">                       
                        '.$nm_rek5.'
                        </TD>                   
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">                      
                        '.number_format($nilai5,'2',',','.').'
                        </TD>
                    </TR>';               
                }                          
            $cRet .='<TR>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="35%">
                        <b>Jumlah Pendapatan</b>
                        </TD>                       
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">
                        '.number_format($jum_bel4 ,'2',',','.').'
                        </TD>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="35%">
                        <b>Jumlah Belanja</b>
                        </TD>                       
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">
                        '.number_format($jum_bel5,'2',',','.').'
                        </TD>
                    </TR></TABLE>';         
                                                
            $cRet .='<TABLE style="font-size:13px;" width="100%" align="center">
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" >Sanggau, '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" >Kepala Dinas Kesehatan<br>Kabupaten Sanggau</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" >'.$nip.'</TD>
                    </TR>
                    </TABLE><br/>';

            $data['prev']= 'SP3B';
            switch ($ctk)
        {
            case 0;
            echo ("<title>SURAT SP3B</title>");
                echo $cRet;
                break;
            case 1;
                //$this->_mpdf('',$cRet,10,10,10,10,1,'');
                $this->support->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
            //$this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
            break;
        }
    }
}