<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    /**
     
     */

    class Rka_model extends CI_Model {

        function __construct()
        {
            parent::__construct();
        }
        
     

        // Tampilkan semua master data kegiatan
        // function getAll($limit, $offset,$id)
        // {
        //  $this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
        //  $this->db->from('trskpd');
     //        $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
     //         $this->db->where('trskpd.kd_skpd', $id);
     //        $this->db->where('trskpd.jns_kegiatan','52');
     //        $this->db->order_by('trskpd.kd_skpd');
        //  $this->db->order_by('trskpd.kd_kegiatan', 'asc');
        //  $this->db->limit($limit,$offset);
        //  return $this->db->get();
        // }


            // Tampilkan semua master data kegiatan 2021
        function getAll($limit, $offset,$id)
        {
            $this->db->select("a.kd_bidang_urusan,a.kd_skpd,a.kd_kegiatan as giat,b.nm_kegiatan");
            $this->db->from("trskpd a");
            $this->db->join("ms_kegiatan b","a.kd_kegiatan=b.kd_kegiatan");
            $this->db->where("a.kd_skpd", $id);
            // $this->db->where("a.jns_kegiatan","");
            $this->db->group_by("a.kd_bidang_urusan,a.kd_skpd,a.kd_kegiatan, b.nm_kegiatan");
            $this->db->order_by("a.kd_skpd");
            $this->db->order_by("a.kd_kegiatan", "asc");
            $this->db->limit($limit,$offset);
            return $this->db->get();
        }

        function angka($nilai){

        if($nilai<0){
            $lc = '('.number_format(abs($nilai),2,',','.').')';
        }else{
            if($nilai==0){
                $lc ='0,00';
            }else{
                $lc = number_format($nilai,2,',','.');
            }
        }

        return $lc;
    }

        function rp_minus($nilai){
            if($nilai<0){
                $nilai = $nilai * (-1);
                $nilai = '('.number_format($nilai,"2",",",".").')';    
            }else{
                $nilai = number_format($nilai,"2",",","."); 
            }
            
            return $nilai;
        } 

        function get_ms_organisasi($skpd){
            $hasil = '';
            $sql = "select a.kd_org,a.nm_org,b.nm_skpd from ms_organisasi a join ms_skpd b on a.kd_org=b.kd_org where b.kd_skpd='$skpd'";
            $hasil = $this->db->query($sql);        
            return $hasil;                         

        }

        function rka_rinci_rancang($skpd,$kegiatan,$rekening,$norka) {
        
        $sql    = "SELECT * from trdpo_rancang_temp where no_trdrka='$norka' order by no_po";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;

        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'      => $ii,   
                        'header'  => $resulte['header'],  
                        'kode'    => $resulte['kode'],
                        'unik'    => $resulte['unik'],  
                        'kd_barang'    => $resulte['kd_barang'],  
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
                        'total_ubah'  => number_format($resulte['total_ubah'],"2",".",",")
                        );
                        $ii++;
        }
           
        return ($result);
    }


    function rka_rinci_penetapan($skpd,$kegiatan,$rekening,$norka) {
        
        $sql    = "SELECT * from trdpo where no_trdrka='$norka' order by no_po";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;

        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'      => $resulte['id'],   
                        'header'  => $resulte['header'],  
                        'kode'    => $resulte['kode'],
                        // 'unik'    => $resulte['unik'],  
                        'kd_barang'    => $resulte['kd_barang'],  
                        'no_po'   => $resulte['no_po'],  
                        'uraian'  => $resulte['uraian'],
                        'spesifikasi'  => $resulte['spesifikasi'],   
                        'volume' => $resulte['volume'],
                        'koefisien' => $resulte['koefisien'],
                        'volume1' => $resulte['volume1'],  
                        'volume2' => $resulte['volume2'],  
                        'volume3' => $resulte['volume3'],
                        'volume4' => $resulte['volume4'],
                        'satuan' => $resulte['satuan'],  
                        'satuan1' => $resulte['satuan1'],  
                        'satuan2' => $resulte['satuan2'],  
                        'satuan3' => $resulte['satuan3'],
                        'satuan4' => $resulte['satuan4'],  
                        'harga1'  => number_format($resulte['harga'],"2",".",","),  
                        'total'   => number_format($resulte['total'],"2",".",",")
                        );
                        $ii++;
        }
           
        return ($result);
    }

    function rka_rinci_penyempurnaan2($skpd,$kegiatan,$rekening,$norka) {
        
        $sql    = "SELECT * from trdpo_temp where no_trdrka='$norka' order by no_po";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;

        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'      => $resulte['id'],   
                        'header'  => $resulte['header'],  
                        'kode'    => $resulte['kode'],
                        // 'unik'    => $resulte['unik'],  
                        'kd_barang'    => $resulte['kd_barang'],  
                        'no_po'   => $resulte['no_po'],  
                        'uraian'  => $resulte['uraian'],
                        'spesifikasi'  => $resulte['spesifikasi'], 
                          
                        'volume'    => doubleval($resulte['tvolume_sempurna2']),
                        'koefisien' => $resulte['tkoefisien_sempurna2'],

                        'volume1' => doubleval($resulte['volume_sempurna21']),  
                        'volume2' => doubleval($resulte['volume_sempurna22']),  
                        'volume3' => doubleval($resulte['volume_sempurna23']),
                        'volume4' => doubleval($resulte['volume_sempurna24']),

                        'satuan' => $resulte['tsatuan_sempurna2'],  
                        'satuan1' => $resulte['satuan_sempurna21'],  
                        'satuan2' => $resulte['satuan_sempurna22'],  
                        'satuan3' => $resulte['satuan_sempurna23'],
                        'satuan4' => $resulte['satuan_sempurna24'],  
                        'harga1'  => number_format($resulte['harga_sempurna2'],"2",".",","),  
                        'total'   => number_format($resulte['total_sempurna2'],"2",".",",")
                        );
                        $ii++;
        }
           
        return ($result);
    }

        function get_nopergub($jns){
            $hasil = '';
            $sql = "SELECT judul,nomor,tanggal FROM trkonfig_anggaran where jenis_anggaran='$jns' and lampiran='pergub'";
            $hasil = $this->db->query($sql);        
            return $hasil;                         

        }

        function get_penjabaran_apbd($n2,$whare1,$whare2,$whare3,$whare4,$whare5,$whare1_a,$whare2_a,$whare3_a,$whare4_a,$whare5_a){
            $hasil = '';
            $sql1="SELECT a.kd_rek1 AS kd_rek,rtrim(a.lra) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                SUM(CASE WHEN $whare1 $whare1_a THEN b.$n2 ELSE 0 END) AS nilai2
                FROM ms_rek1 a INNER JOIN trdrka b ON a.kd_rek1=LEFT(b.kd_rek5,(LEN(a.kd_rek1))) 
                where $whare1 $whare1_a GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                UNION ALL 
                SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                SUM(CASE WHEN $whare2 $whare2_a THEN b.$n2 ELSE 0 END) AS nilai2
                FROM ms_rek2 a INNER JOIN trdrka b ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) 
                where $whare2 $whare2_a GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                UNION ALL 
                SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                SUM(CASE WHEN $whare3 $whare3_a THEN b.$n2 ELSE 0 END) AS nilai2
                FROM ms_rek3 a INNER JOIN trdrka b ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) 
                where $whare3 $whare3_a GROUP BY a.kd_rek3,a.lra, a.nm_rek3
                UNION ALL 
                SELECT a.kd_rek4 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek4 AS nm_rek ,SUM(b.nilai) AS nilai,
                SUM(CASE WHEN $whare4 $whare4_a THEN b.$n2 ELSE 0 END) AS nilai2
                FROM ms_rek4 a INNER JOIN trdrka b ON a.kd_rek4=LEFT(b.kd_rek5,(LEN(a.kd_rek4))) 
                where $whare4 $whare4_a GROUP BY a.kd_rek4,a.lra, a.nm_rek4
                UNION ALL 
                SELECT a.kd_rek5 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek5 AS nm_rek ,SUM(b.nilai) AS nilai,
                SUM(CASE WHEN $whare5 $whare5_a THEN b.$n2 ELSE 0 END) AS nilai2 
                FROM ms_rek5 a INNER JOIN trdrka b ON a.kd_rek5=LEFT(b.kd_rek5,(LEN(a.kd_rek5))) 
                where $whare5 $whare5_a GROUP BY a.kd_rek5,a.lra, a.nm_rek5 ORDER BY kd_rek ";
            $hasil = $this->db->query($sql1);        
            return $hasil;                         
        }
            
        function get_status($tgl,$skpd){
            $n_status = '';
            $tanggal = $tgl;
            $sql = "select case when '$tanggal'>=tgl_dpa_ubah then 'nilai_ubah' 
                        when '$tanggal'>=tgl_dpa_sempurna then 'nilai_sempurna' 
                        when '$tanggal'<=tgl_dpa 
                        then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd' ";
            
            $q_trhrka = $this->db->query($sql);
            $num_rows = $q_trhrka->num_rows();
            
            foreach ($q_trhrka->result() as $r_trhrka){
                 $n_status = $r_trhrka->anggaran;                   
            }    
            return $n_status;                         
        }
        
        function combo_ttd($kode='') 
        {
                   
            $kd_skpd = $this->session->userdata('kdskpd');      
                   
            $csql    = "SELECT * from ms_ttd where kd_skpd = '$kd_skpd' order by nama ";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"ttd\" id=\"ttd\" style=\"height:28px;width:200px\">";
            $cRet   .= "<option value=\"\">Pilih Penanda Tangan</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['nip']==$kode) ? " selected" : "";
               if (!empty($row['nip'])) 
                    $cRet .= "<option value='".$row['nip']."'".$selected.">".$row['nama']."</option>";
            
            }
            $cRet .= "</select>";
            
            return $cRet;
        }
        
        function getAllc()
        {
            $this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
            $this->db->from('trskpd');
            $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
            $this->db->order_by('trskpd.kd_skpd');
            $this->db->order_by('trskpd.kd_kegiatan', 'asc');
            //$this->db->limit($limit,$offset);
            return $this->db->get();
        }
        
            function get_count_cari($data)
        {
            $this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
            $this->db->from('trskpd');
            $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
            $this->db->order_by('trskpd.kd_skpd');
            $this->db->order_by('trskpd.kd_kegiatan', 'asc');
            return $this->db->get()->num_rows();
            //return $this->db->get('ms_fungsi')->num_rows();
        }
        
        //cari
        function cari($limit, $offset,$data)
        {
            $this->db->select('trskpd.kd_urusan,trskpd.kd_skpd,trskpd.kd_kegiatan as giat,m_giat.nm_kegiatan');
            $this->db->from('trskpd');
            $this->db->join('m_giat','m_giat.kd_kegiatan=trskpd.kd_kegiatan1');
            $this->db->or_like('nm_kegiatan', $data);  
            $this->db->or_like('trskpd.kd_kegiatan', $data);      
            $this->db->order_by('trskpd.kd_kegiatan', 'asc');
            return $this->db->get();
        }
        
        // Total jumlah data
        function get_count($id)
        {
            $this->db->select('*');
            $this->db->from('trskpd');
            $this->db->where('kd_skpd', $id);
            $this->db->where('trskpd.jns_kegiatan','52');
            return $this->db->get()->num_rows();
        }
        
        // Ambil by ID
        function get_by_id($id)
        {
            $this->db->select('*');
            $this->db->from('trskpd');
            $this->db->where('kd_kegiatan', $id);
            return $this->db->get();
        }

        function get_by_id2($id,$tabel,$field1,$field2,$where,$order){
            $this->db->select($field1);
            $this->db->select($field2);
            $this->db->from($tabel);
            $this->db->where($where, $id);
            $this->db->order_by($order);
            return $this->db->get();
        }    

        function get_by_id3($id,$tabel,$field1,$field2,$field3,$where,$order){
            $this->db->select($field1);
            $this->db->select($field2);
            $this->db->select($field3);
            $this->db->from($tabel);
            $this->db->where($where, $id);
            $this->db->order_by($order);
            return $this->db->get();
        }   

        function get_by_id_where3_top1($tabel,$field1,$where1,$where2,$where3,$id1,$id2,$id3){
            $this->db->select('top 1'.$field1);
            $this->db->from($tabel);
            $this->db->where($where1, $id1);
            $this->db->where($where2, $id2);
            $this->db->where($where3, $id3);
            $this->db->limit('1');
            return $this->db->get();
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
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2).'.'.substr($rek,8,3);                          
                    break;
                    case 15:
                        $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2).'.'.substr($rek,6,2).'.'.substr($rek,8,3);                          
                    break;
                    default:
                    $rek = "";  
                    }
                    return $rek;
        } 
        
        //get nama dari master
        function get_nama($kode,$hasil,$tabel,$field)
        {
            $this->db->select($hasil);
            $this->db->where($field, $kode);
            $q = $this->db->get($tabel);
            $data  = $q->result_array();
            $baris = $q->num_rows();
            return $data[0][$hasil];
        }

         function satuan($id='',$script=''){

            $cRet="";
            $csql    = "SELECT * from (
                SELECT satuan from ms_standar_harga GROUP BY satuan 
--                         union all
--                         SELECT satuan_ubah1 from trdpo group by satuan_ubah1
--                         union all
--                         SELECT satuan_sempurna1 from trdpo group by satuan_sempurna1
                union all
                SELECT satuan1 from trdpo group by satuan1) okei group by satuan


    ORDER BY satuan desc";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"$id\" id=\"$id\" $script >";
            $cRet   .= "<option value=''>Pilih $id</option>"; 
            foreach ($query->result_array() as $row)
            {
               if (!empty($row['satuan'])) 
                    $cRet .= "<option value='".$row['satuan']."'>".$row['satuan']."</option>";
            
            }
            $cRet .= "</select>";
          
            return $cRet;
        }
        function get_urusan_skpd($kode,$hasil,$tabel,$field)
        {
            $this->db->select('TOP 1 '.$hasil);
            $this->db->where($field, $kode);
            $this->db->where('urusan <>', '');
            $q = $this->db->get($tabel);
            $data  = $q->result_array();
            $baris = $q->num_rows();
            return $data[0][$hasil];
        }

        function get_nama_pend($kode,$hasil,$tabel,$field)
        {
            $this->db->select($hasil);
            $this->db->where($field, '$kode');
            $q = $this->db->get($tabel);
            $data  = $q->result_array();
            $baris = $q->num_rows();
            return $data[0][$hasil];
        }
        
        function get_nama2($kode,$hasil,$tabel,$field,$field2,$kode2)
        {
            $this->db->select($hasil);
            $this->db->where($field, $kode);
            $this->db->where($field2, $kode2);
            $q = $this->db->get($tabel);
            $data  = $q->result_array();
            $baris = $q->num_rows();
            return $data[0][$hasil];
        }
        
        function get_program($id)
        {
            $this->db->select('*');
            $this->db->from('m_prog');
            $this->db->order_by('kd_program',$id);
            $this->db->limit($limit,$offset);
            return $this->db->get();
        }
        
        function combo_urus($skpd='',$tahun='') 
        {
                   
            $csql    = "SELECT * from ms_urusan order by kd_urusan ";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"urusan\" id=\"urusan\">";
            $cRet   .= "<option value=\"\">--Pilih Kode Urusan--</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['kd_urusan']==$skpd) ? " selected" : "";
               if (!empty($row['kd_urusan'])) 
                    $cRet .= "<option value='".$row['kd_urusan']."'".$selected.">".$row['kd_urusan']." | ".$row['nm_urusan']."</option>";
            
            }
            $cRet .= "</select>";
            
            return $cRet;
        }
        
        function combo_skpd($skpd='',$tahun='') 
        {
                   
            $csql    = "SELECT * from ms_skpd order by kd_skpd ";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"skpd\" id=\"skpd\" >";
            $cRet   .= "<option value=\"\">--Pilih Kode SKPD--</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['kd_skpd']==$skpd) ? " selected" : "";
               if (!empty($row['kd_skpd'])) 
                    $cRet .= "<option value='".$row['kd_skpd']."'".$selected.">".$row['kd_skpd']." | ".$row['nm_skpd']."</option>";
            
            }
            $cRet .= "</select>";
            
            return $cRet;
        }
        
        
        function combo_bln($skpd='',$tahun='') 
        {        
            $csql    = "SELECT * from ms_bln ";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"bln\" id=\"bln\">";
            $cRet   .= "<option value=\"\">--Pilih Blan--</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['kd']==$skpd) ? " selected" : "";
               if (!empty($row['kd'])) 
                    $cRet .= "<option value='".$row['kd']."'".$selected.">".$row['kd']." | ".$row['nm']."</option>";
            
            }
            $cRet .= "</select>";
            
            return $cRet;
        }
        function combo_bank($bank='') 
        {        
            $csql    = "SELECT * from ms_bank order by kode ";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"bank\" id=\"bank\">";
            $cRet   .= "<option value=\"\">--Pilih Bank--</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['kode']==$bank) ? " selected" : "";
               if (!empty($row['kode'])) 
                    $cRet .= "<option value='".$row['kode']."'".$selected.">".$row['kode']." | ".$row['nama']."</option>";
            
            }
            $cRet .= "</select>";
            
            return $cRet;
        }
        
        function combo_skpd1($skpd='',$tahun='') 
        {
                   
            $csql    = "SELECT * from ms_skpd order by kd_skpd ";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"skpd\" id=\"skpd\" onchange=\"javascript:validate_combo();\">";
            $cRet   .= "<option value=\"\">--Pilih Kode SKPD--</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['kd_skpd']==$skpd) ? " selected" : "";
               if (!empty($row['kd_skpd'])) 
                    $cRet .= "<option value='".$row['kd_skpd']."'".$selected.">".$row['kd_skpd']." | ".$row['nm_skpd']."</option>";
            
            }
            $cRet .= "</select>";
            
            return $cRet;
        }
        
        function combo_giat1($giat='',$tahun='') 
        {
                   
            $csql    = "SELECT * from ms_kegiatan order by kd_kegiatan ";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"giat\" id=\"giat\" onchange=\"javascript:validate_combo();\">"; 
            $cRet   .= "<option value=\"\">--Pilih Kode Kegiatan--</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['kd_kegiatan']==$giat) ? " selected" : "";
               if (!empty($row['kd_kegiatan'])) 
                    $cRet .= "<option value='".$row['kd_kegiatan']."'".$selected.">".$row['kd_kegiatan']." | ".$row['nm_kegiatan']."</option>";
            
            }
            $cRet .= "</select>";
            
            return $cRet;
        }
        
        function combo_giat($giat='',$skpd=''){
                   
            $csql    = "SELECT a.kd_kegiatan,b.nm_kegiatan FROM trskpd a LEFT JOIN ms_kegiatan b ON a.kd_kegiatan=b.kd_kegiatan where a.kd_skpd like '%$skpd%' ORDER BY b.kd_kegiatan";
            $query   = $this->db->query($csql);
             
            $cRet    = "<select name=\"giat\" id=\"giat\" onchange=\"javascript:validate_combo();\">"; 
            $cRet   .= "<option value=\"\">--Pilih Kode Kegiatan--</option>";
            foreach ($query->result_array() as $row)
            {
               $selected = ($row['kd_kegiatan']==$giat) ? " selected" : "";
               if (!empty($row['kd_kegiatan'])) 
                    $cRet .= "<option value='".$row['kd_kegiatan']."' ".$selected.">".$row['kd_kegiatan']." | ".$row['nm_kegiatan']."</option>";
         
            }
            $cRet .= "</select>";        
            return $cRet;
        }
        
        function combo_bulan($id='',$script=''){
            $cRet    = '';                        
            $cRet    = "<select name=\"$id\" id=\"$id\" $script >";
            $cRet   .= "<option value='0'>Pilih Bulan</option>"; 
            $cRet   .= "<option value='1'>Januari</option>";
            $cRet   .= "<option value='2'>Februari</option>";
            $cRet   .= "<option value='3'>Maret</option>";
            $cRet   .= "<option value='4'>April</option>";
            $cRet   .= "<option value='5'>Mei</option>";
            $cRet   .= "<option value='6'>Juni</option>";
            $cRet   .= "<option value='7'>Juli</option>";
            $cRet   .= "<option value='8'>Agustus</option>";
            $cRet   .= "<option value='9'>September</option>";
            $cRet   .= "<option value='10'>Oktober</option>";
            $cRet   .= "<option value='11'>November</option>";
            $cRet   .= "<option value='12'>Desember</option>";
            $cRet   .= "</select>";        
            return $cRet;
        }
        
        function combo_beban($id='',$script=''){
            $cRet    = '';                        
            $cRet    = "<select name=\"$id\" id=\"$id\" $script >";
            $cRet   .= "<option value='5'>Keseluruhan</option>";                 
            $cRet   .= "<option value='52'>Belanja Langsung</option>";
            $cRet   .= "<option value='51'>Belanja Tidak Langsung</option>"; 
            $cRet   .= "<option value='62'>Pembiayaan</option>";       
            $cRet   .= "</select>";        
            return $cRet;
        }
        
        //function terbilang($number) {
    //   
    //        $hyphen      = ' ';
    //        $conjunction = ' ';
    //        $separator   = ' ';
    //        $negative    = 'minus ';
    //        $decimal     = ' koma ';
    //        $dictionary  = array(0 => 'nol',1 => 'satu',2 => 'dua',3 => 'tiga',4 => 'empat',5 => 'lima',6 => 'enam',7 => 'tujuh',
    //            8 => 'delapan',9 => 'sembilan',10 => 'sepuluh',11  => 'sebelas',12 => 'dua belas',13 => 'tiga belas',14 => 'empat belas',
    //            15 => 'lima belas',16 => 'enam belas',17 => 'tujuh belas',18 => 'delapan belas',19 => 'sembilan belas',20 => 'dua puluh',
    //            30 => 'tiga puluh',40 => 'empat puluh',50 => 'lima puluh',60 => 'enam puluh',70 => 'tujuh puluh',80 => 'delapan puluh',
    //            90 => 'sembilan puluh',100 => 'ratus',1000 => 'ribu',1000000 => 'juta',1000000000 => 'milyar',1000000000000 => 'triliun',
    //        );
    //       
    //        if (!is_numeric($number)) {
    //            return false;
    //        }
    //       
    //        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
    //            // overflow
    //            trigger_error(
    //                'terbilang only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
    //                E_USER_WARNING
    //            );
    //            return false;
    //        }
    //    
    //        if ($number < 0) {
    //            return $negative . $this->terbilang(abs($number));
    //        }
    //       
    //        $string = $fraction = null;
    //       
    //        if (strpos($number, '.') !== false) {
    //            list($number, $fraction) = explode('.', $number);
    //        }
    //       
    //        switch (true) {
    //            case $number < 21:
    //                $string = $dictionary[$number];
    //                break;
    //            case $number < 100:
    //                $tens   = ((int) ($number / 10)) * 10;
    //                $units  = $number % 10;
    //                $string = $dictionary[$tens];
    //                if ($units) {
    //                    $string .= $hyphen . $dictionary[$units];
    //                }
    //                break;
    //            case $number < 1000:
    //                $hundreds  = $number / 100;
    //                $remainder = $number % 100;
    //                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
    //                if ($remainder) {
    //                    $string .= $conjunction . $this->terbilang($remainder);
    //                }
    //                break;
    //            default:
    //                $baseUnit = pow(1000, floor(log($number, 1000)));
    //                $numBaseUnits = (int) ($number / $baseUnit);
    //                $remainder = $number % $baseUnit;
    //                $string = $this->terbilang($numBaseUnits) . ' ' . $dictionary[$baseUnit];
    //                if ($remainder) {
    //                    $string .= $remainder < 100 ? $conjunction : $separator;
    //                    $string .= $this->terbilang($remainder);
    //                }
    //                break;
    //        }
    //       
    //        if (null !== $fraction && is_numeric($fraction)) {
    //            $string .= $decimal;
    //            $words = array();
    //            foreach (str_split((string) $fraction) as $number) {
    //                $words[] = $dictionary[$number];
    //            }
    //            $string .= implode(' ', $words);
    //        }
    //       
    //        return $string;
    //    }
        function  tanggal_format_indonesia($tgl){
            
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;

    }
        
          function terbilang_get_valid($str,$from,$to,$min=1,$max=9){
                $val=false;
                $from=($from<0)?0:$from;
                for ($i=$from;$i<$to;$i++){
                    if (((int) $str{$i}>=$min)&&((int) $str{$i}<=$max)) $val=true;
                }
                return $val;
            }
            function terbilang_get_str($i,$str,$len){
                $numA=array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan");
                $numB=array("","se","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
                $numC=array("","satu ","dua ","tiga ","empat ","lima ","enam ","tujuh ","delapan ","sembilan ");
                $numD=array(0=>"puluh",1=>"belas",2=>"ratus",4=>"ribu", 7=>"juta", 10=>"milyar", 13=>"triliun");
                $buf="";
                $pos=$len-$i;
                switch($pos){
                    case 1:
                            if (!$this->terbilang_get_valid($str,$i-1,$i,1,1))
                                $buf=$numA[(int) $str{$i}];
                        break;
                    case 2: case 5: case 8: case 11: case 14:
                            if ((int) $str{$i}==1){
                                if ((int) $str{$i+1}==0)
                                    $buf=($numB[(int) $str{$i}]).($numD[0]);
                                else
                                    $buf=($numB[(int) $str{$i+1}]).($numD[1]);
                            }
                            else if ((int) $str{$i}>1){
                                    $buf=($numB[(int) $str{$i}]).($numD[0]);
                            }               
                        break;
                    case 3: case 6: case 9: case 12: case 15:
                            if ((int) $str{$i}>0){
                                    $buf=($numB[(int) $str{$i}]).($numD[2]);
                            }
                        break;
                    case 4: case 7: case 10: case 13:
                            if ($this->terbilang_get_valid($str,$i-2,$i)){
                                if (!$this->terbilang_get_valid($str,$i-1,$i,1,1))
                                    $buf=$numC[(int) $str{$i}].($numD[$pos]);
                                else
                                    $buf=$numD[$pos];
                            }
                            else if((int) $str{$i}>0){
                                if ($pos==4)
                                    $buf=($numB[(int) $str{$i}]).($numD[$pos]);
                                else
                                    $buf=($numC[(int) $str{$i}]).($numD[$pos]);
                            }
                        break;
                }
                return $buf;
            }
            function terbilang($nominal){
                $buf="";
                $str=$nominal."";
                $len=strlen($str);
                for ($i=0;$i<$len;$i++){
                    $buf=trim($buf)." ".$this->terbilang_get_str($i,$str,$len);
                }
                return trim($buf);
            }
        
          function _mpdf($judul='',$isi='',$lMargin='',$rMargin='',$font=0,$orientasi='') {
            
            ini_set("memory_limit","-1");
            $this->load->library('mpdf');
            
            /*
            $this->mpdf->progbar_altHTML = '<html><body>
                                            <div style="margin-top: 5em; text-align: center; font-family: Verdana; font-size: 12px;"><img style="vertical-align: middle" src="'.base_url().'images/loading.gif" /> Creating PDF file. Please wait...</div>';        
            $this->mpdf->StartProgressBarOutput();
            */
            
            $this->mpdf->defaultheaderfontsize = 6; /* in pts */
            $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
            $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

            $this->mpdf->defaultfooterfontsize = 6; /* in pts */
            $this->mpdf->defaultfooterfontstyle = BI;   /* blank, B, I, or BI */
            $this->mpdf->defaultfooterline = 1; 
            $this->mpdf->SetLeftMargin = $lMargin;
            $this->mpdf->SetRightMargin = $rMargin;
            //$this->mpdf->SetHeader('SIMAKDA||');
            //$jam = date("H:i:s");
            //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Simakda| Page {PAGENO} of {nb}');
            //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');
            
            $this->mpdf->AddPage($orientasi,'','','','',$lMargin,$rMargin);
            
            if (!empty($judul)) $this->mpdf->writeHTML($judul);
            $this->mpdf->writeHTML($isi);         
            $this->mpdf->Output();
                   
        }
     

     function _mpdf_folio($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='') {
                    

            ini_set("memory_limit","-1");
            $this->load->library('mpdf');
            //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
            
            
            $this->mpdf->defaultheaderfontsize = 6; /* in pts */
            $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
            $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

            $this->mpdf->defaultfooterfontsize = 6; /* in pts */
            $this->mpdf->defaultfooterfontstyle = BI;   /* blank, B, I, or BI */
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
            $this->mpdf->AddPage($orientasi,'',$hal1,'1','off');
            $this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
            if (!empty($judul)) $this->mpdf->writeHTML($judul);
            $this->mpdf->writeHTML($isi);         
            $this->mpdf->Output();
                   
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
            return  "April";
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
        
        function get_ang($field,$rek,$skpd){
            $hasil='';
            $csql = " SELECT SUM($field) AS nilai FROM trdrka WHERE LEFT(kd_rek5,1)='$rek' AND kd_skpd='$skpd'";
            $query1 = $this->db->query($csql);  
            foreach($query1->result_array() as $resulte)
            {   
                $hasil=$resulte[nilai];
            }   
            return $hasil;
        }
        function get_ang1($field,$rek){
            $hasil='';
            $csql = " SELECT SUM($field) AS nilai FROM trdrka WHERE LEFT(kd_rek5,1)='$rek' ";
            $query1 = $this->db->query($csql);  
            foreach($query1->result_array() as $resulte)
            {   
                $hasil=$resulte[nilai];
            }   
            return $hasil;
        }
        
        function get_ang2($field,$rek){
            $hasil='';
            $csql = " SELECT SUM($field) AS nilai FROM trdrka WHERE LEFT(kd_rek5,2)='$rek'";
            $query1 = $this->db->query($csql);  
            foreach($query1->result_array() as $resulte)
            {   
                $hasil=$resulte[nilai];
            }   
            return $hasil;
        }
        //sumber dana
        function q_sumberdana($field,$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,$nilai_sumber4,$where,$group,$notin){
            $hasil = '';
            $sql="select kode,nama,sum(pad) [pad],sum(DAK) [dak],sum(DAKNF) [daknf],sum(DAU) [dau],sum(DBHP) [dbhp],sum(did) [did],sum(lain2) [lain2] from(
                select $field SUM (CASE WHEN $sumber = 'PAD' THEN $nilai_sumber ELSE 0 END) AS PAD,
                SUM (CASE WHEN $sumber = 'DAK FISIK' THEN $nilai_sumber ELSE 0 END) AS DAK,
                SUM (CASE WHEN $sumber = 'DAK NON FISIK' THEN $nilai_sumber ELSE 0 END) AS DAKNF,
                SUM (CASE WHEN $sumber = 'DAU' THEN $nilai_sumber ELSE 0 END) AS DAU,
                SUM (CASE WHEN $sumber = 'DBHP' THEN $nilai_sumber ELSE 0 END) AS DBHP,
                SUM (CASE WHEN $sumber = 'DID' THEN $nilai_sumber ELSE 0 END) AS DID,
                SUM (CASE WHEN $sumber $notin THEN $nilai_sumber ELSE 0 END) AS Lain2
                from trdrka $where group by $group
                union all
                select $field SUM (CASE WHEN $sumber2 = 'PAD' THEN $nilai_sumber2 ELSE 0 END) AS PAD,
                SUM (CASE WHEN $sumber2 = 'DAK FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAK,
                SUM (CASE WHEN $sumber2 = 'DAK NON FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAKNF,
                SUM (CASE WHEN $sumber2 = 'DAU' THEN $nilai_sumber2 ELSE 0 END) AS DAU,
                SUM (CASE WHEN $sumber2 = 'DBHP' THEN $nilai_sumber2 ELSE 0 END) AS DBHP,
                SUM (CASE WHEN $sumber2 = 'DID' THEN $nilai_sumber2 ELSE 0 END) AS DID,
                SUM (CASE WHEN $sumber2 $notin THEN $nilai_sumber2 ELSE 0 END) AS Lain2
                from trdrka $where group by $group
                union all
                select $field SUM (CASE WHEN $sumber3 = 'PAD' THEN $nilai_sumber3 ELSE 0 END) AS PAD,
                SUM (CASE WHEN $sumber3 = 'DAK FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAK,
                SUM (CASE WHEN $sumber3 = 'DAK NON FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAKNF,
                SUM (CASE WHEN $sumber3 = 'DAU' THEN $nilai_sumber3 ELSE 0 END) AS DAU,
                SUM (CASE WHEN $sumber3 = 'DBHP' THEN $nilai_sumber3 ELSE 0 END) AS DBHP,
                SUM (CASE WHEN $sumber3 = 'DID' THEN $nilai_sumber3 ELSE 0 END) AS DID,
                SUM (CASE WHEN $sumber3 $notin THEN $nilai_sumber3 ELSE 0 END) AS Lain2
                from trdrka $where group by $group
                union all
                select $field SUM (CASE WHEN $sumber4 = 'PAD' THEN $nilai_sumber4 ELSE 0 END) AS PAD,
                SUM (CASE WHEN $sumber4 = 'DAK FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAK,
                SUM (CASE WHEN $sumber4 = 'DAK NON FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAKNF,
                SUM (CASE WHEN $sumber4 = 'DAU' THEN $nilai_sumber4 ELSE 0 END) AS DAU,
                SUM (CASE WHEN $sumber4 = 'DBHP' THEN $nilai_sumber4 ELSE 0 END) AS DBHP,
                SUM (CASE WHEN $sumber4 = 'DID' THEN $nilai_sumber4 ELSE 0 END) AS DID,
                SUM (CASE WHEN $sumber4 $notin THEN $nilai_sumber4 ELSE 0 END) AS Lain2
                from trdrka $where group by $group
                ) as a group by kode,nama order by kode";
            $hasil=$this->db->query($sql);
            return $hasil;
        }
        
        function q_lamp1_sdana($id,$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,$nilai_sumber4,$notin,$where){
            $hasil = '';
            $sql1="select kd_rek,rek,nm_rek,sum(pad) [pad],sum(DAK) [dak],sum(DAKNF) [daknf],sum(DAU) [dau],sum(DBHP) [dbhp],sum(did) [did],sum(lain2) [lain2] from(
                    -------------------sumber1------------------------
                    SELECT a.kd_rek1 AS kd_rek,rtrim(a.lra) AS rek, a.nm_rek1 AS nm_rek ,
                    sum (CASE WHEN $sumber = 'PAD' THEN $nilai_sumber ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber = 'DAK FISIK' THEN $nilai_sumber ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber = 'DAK NON FISIK' THEN $nilai_sumber ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber = 'DAU' THEN $nilai_sumber ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber = 'DBHP' THEN $nilai_sumber ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber = 'DID' THEN $nilai_sumber ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber $notin THEN $nilai_sumber ELSE 0 END) AS Lain2
                    FROM ms_rek1 a INNER JOIN trdrka b
                    ON a.kd_rek1=LEFT(b.kd_rek5,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='$id' $where GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                    UNION ALL 
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber = 'PAD' THEN $nilai_sumber ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber = 'DAK FISIK' THEN $nilai_sumber ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber = 'DAK NON FISIK' THEN $nilai_sumber ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber = 'DAU' THEN $nilai_sumber ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber = 'DBHP' THEN $nilai_sumber ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber = 'DID' THEN $nilai_sumber ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber $notin THEN $nilai_sumber ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber = 'PAD' THEN $nilai_sumber ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber = 'DAK FISIK' THEN $nilai_sumber ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber = 'DAK NON FISIK' THEN $nilai_sumber ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber = 'DAU' THEN $nilai_sumber ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber = 'DBHP' THEN $nilai_sumber ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber = 'DID' THEN $nilai_sumber ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber $notin THEN $nilai_sumber ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3 
                    UNION ALL 
                    --------------------sumber2--------------------------
                    SELECT a.kd_rek1 AS kd_rek,rtrim(a.lra) AS rek, a.nm_rek1 AS nm_rek ,
                    sum (CASE WHEN $sumber2 = 'PAD' THEN $nilai_sumber2 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber2 = 'DAK FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber2 = 'DAK NON FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber2 = 'DAU' THEN $nilai_sumber2 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber2 = 'DBHP' THEN $nilai_sumber2 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber2 = 'DID' THEN $nilai_sumber2 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber2 $notin THEN $nilai_sumber2 ELSE 0 END) AS Lain2
                    FROM ms_rek1 a INNER JOIN trdrka b
                    ON a.kd_rek1=LEFT(b.kd_rek5,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='$id' $where GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                    UNION ALL 
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber2 = 'PAD' THEN $nilai_sumber2 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber2 = 'DAK FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber2 = 'DAK NON FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber2 = 'DAU' THEN $nilai_sumber2 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber2 = 'DBHP' THEN $nilai_sumber2 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber2 = 'DID' THEN $nilai_sumber2 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber2 $notin THEN $nilai_sumber2 ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber2 = 'PAD' THEN $nilai_sumber2 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber2 = 'DAK FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber2 = 'DAK NON FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber2 = 'DAU' THEN $nilai_sumber2 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber2 = 'DBHP' THEN $nilai_sumber2 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber2 = 'DID' THEN $nilai_sumber2 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber2 $notin THEN $nilai_sumber2 ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3 
                    UNION ALL 
                    ------------------------sumber3-------------------
                    SELECT a.kd_rek1 AS kd_rek,rtrim(a.lra) AS rek, a.nm_rek1 AS nm_rek ,
                    sum (CASE WHEN $sumber3 = 'PAD' THEN $nilai_sumber3 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber3 = 'DAK FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber3 = 'DAK NON FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber3 = 'DAU' THEN $nilai_sumber3 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber3 = 'DBHP' THEN $nilai_sumber3 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber3 = 'DID' THEN $nilai_sumber3 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber3 $notin THEN $nilai_sumber3 ELSE 0 END) AS Lain2
                    FROM ms_rek1 a INNER JOIN trdrka b
                    ON a.kd_rek1=LEFT(b.kd_rek5,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='$id' $where GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                    UNION ALL 
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber3 = 'PAD' THEN $nilai_sumber3 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber3 = 'DAK FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber3 = 'DAK NON FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber3 = 'DAU' THEN $nilai_sumber3 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber3 = 'DBHP' THEN $nilai_sumber3 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber3 = 'DID' THEN $nilai_sumber3 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber3 $notin THEN $nilai_sumber3 ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber3 = 'PAD' THEN $nilai_sumber3 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber3 = 'DAK FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber3 = 'DAK NON FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber3 = 'DAU' THEN $nilai_sumber3 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber3 = 'DBHP' THEN $nilai_sumber3 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber3 = 'DID' THEN $nilai_sumber3 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber3 $notin THEN $nilai_sumber3 ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3
                    UNION ALL  
                    ----------------------sumber4-------------------------
                    SELECT a.kd_rek1 AS kd_rek,rtrim(a.lra) AS rek, a.nm_rek1 AS nm_rek ,
                    sum (CASE WHEN $sumber4 = 'PAD' THEN $nilai_sumber4 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber4 = 'DAK FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber4 = 'DAK NON FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber4 = 'DAU' THEN $nilai_sumber4 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber4 = 'DBHP' THEN $nilai_sumber4 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber4 = 'DID' THEN $nilai_sumber4 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber4 $notin THEN $nilai_sumber4 ELSE 0 END) AS Lain2
                    FROM ms_rek1 a INNER JOIN trdrka b
                    ON a.kd_rek1=LEFT(b.kd_rek5,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='$id' $where GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                    UNION ALL 
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber4 = 'PAD' THEN $nilai_sumber4 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber4 = 'DAK FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber4 = 'DAK NON FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber4 = 'DAU' THEN $nilai_sumber4 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber4 = 'DBHP' THEN $nilai_sumber4 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber4 = 'DID' THEN $nilai_sumber4 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber4 $notin THEN $nilai_sumber4 ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber4 = 'PAD' THEN $nilai_sumber4 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber4 = 'DAK FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber4 = 'DAK NON FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber4 = 'DAU' THEN $nilai_sumber4 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber4 = 'DBHP' THEN $nilai_sumber4 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber4 = 'DID' THEN $nilai_sumber4 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber4 $notin THEN $nilai_sumber4 ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3 
                    )as gabung group by  kd_rek,rek,nm_rek ORDER BY kd_rek";
            $hasil=$this->db->query($sql1);    
            return $hasil;
        }

        function q_lamp1_sdana2($id,$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,$nilai_sumber4,$notin,$where){
            $hasil = '';
            $sql1="select kd_rek,rek,nm_rek,sum(pad) [pad],sum(DAK) [dak],sum(DAKNF) [daknf],sum(DAU) [dau],sum(DBHP) [dbhp],sum(did) [did],sum(lain2) [lain2] from(
                    -------------------sumber1------------------------
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber = 'PAD' THEN $nilai_sumber ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber = 'DAK FISIK' THEN $nilai_sumber ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber = 'DAK NON FISIK' THEN $nilai_sumber ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber = 'DAU' THEN $nilai_sumber ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber = 'DBHP' THEN $nilai_sumber ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber = 'DID' THEN $nilai_sumber ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber $notin THEN $nilai_sumber ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber = 'PAD' THEN $nilai_sumber ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber = 'DAK FISIK' THEN $nilai_sumber ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber = 'DAK NON FISIK' THEN $nilai_sumber ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber = 'DAU' THEN $nilai_sumber ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber = 'DBHP' THEN $nilai_sumber ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber = 'DID' THEN $nilai_sumber ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber $notin THEN $nilai_sumber ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3 
                    UNION ALL 
                    --------------------sumber2--------------------------
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber2 = 'PAD' THEN $nilai_sumber2 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber2 = 'DAK FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber2 = 'DAK NON FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber2 = 'DAU' THEN $nilai_sumber2 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber2 = 'DBHP' THEN $nilai_sumber2 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber2 = 'DID' THEN $nilai_sumber2 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber2 $notin THEN $nilai_sumber2 ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber2 = 'PAD' THEN $nilai_sumber2 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber2 = 'DAK FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber2 = 'DAK NON FISIK' THEN $nilai_sumber2 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber2 = 'DAU' THEN $nilai_sumber2 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber2 = 'DBHP' THEN $nilai_sumber2 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber2 = 'DID' THEN $nilai_sumber2 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber2 $notin THEN $nilai_sumber2 ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3 
                    UNION ALL 
                    ------------------------sumber3-------------------
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber3 = 'PAD' THEN $nilai_sumber3 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber3 = 'DAK FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber3 = 'DAK NON FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber3 = 'DAU' THEN $nilai_sumber3 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber3 = 'DBHP' THEN $nilai_sumber3 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber3 = 'DID' THEN $nilai_sumber3 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber3 $notin THEN $nilai_sumber3 ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber3 = 'PAD' THEN $nilai_sumber3 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber3 = 'DAK FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber3 = 'DAK NON FISIK' THEN $nilai_sumber3 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber3 = 'DAU' THEN $nilai_sumber3 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber3 = 'DBHP' THEN $nilai_sumber3 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber3 = 'DID' THEN $nilai_sumber3 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber3 $notin THEN $nilai_sumber3 ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3
                    UNION ALL  
                    ----------------------sumber4------------------------- 
                    SELECT a.kd_rek2 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek2 AS nm_rek ,
                    sum (CASE WHEN $sumber4 = 'PAD' THEN $nilai_sumber4 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber4 = 'DAK FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber4 = 'DAK NON FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber4 = 'DAU' THEN $nilai_sumber4 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber4 = 'DBHP' THEN $nilai_sumber4 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber4 = 'DID' THEN $nilai_sumber4 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber4 $notin THEN $nilai_sumber4 ELSE 0 END) AS Lain2
                    FROM ms_rek2 a INNER JOIN trdrka b
                    ON a.kd_rek2=LEFT(b.kd_rek5,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='$id' $where GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                    UNION ALL 
                    SELECT a.kd_rek3 AS kd_rek,rtrim(a.lra) AS rek,a.nm_rek3 AS nm_rek ,
                    sum (CASE WHEN $sumber4 = 'PAD' THEN $nilai_sumber4 ELSE 0 END) AS PAD,
                    SUM (CASE WHEN $sumber4 = 'DAK FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAK,
                    SUM (CASE WHEN $sumber4 = 'DAK NON FISIK' THEN $nilai_sumber4 ELSE 0 END) AS DAKNF,
                    SUM (CASE WHEN $sumber4 = 'DAU' THEN $nilai_sumber4 ELSE 0 END) AS DAU,
                    SUM (CASE WHEN $sumber4 = 'DBHP' THEN $nilai_sumber4 ELSE 0 END) AS DBHP,
                    SUM (CASE WHEN $sumber4 = 'DID' THEN $nilai_sumber4 ELSE 0 END) AS DID,
                    SUM (CASE WHEN $sumber4 $notin THEN $nilai_sumber4 ELSE 0 END) AS Lain2
                    FROM ms_rek3 a INNER JOIN trdrka b
                    ON a.kd_rek3=LEFT(b.kd_rek5,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='$id' $where GROUP BY a.kd_rek3,a.lra, a.nm_rek3 
                    )as gabung group by  kd_rek,rek,nm_rek ORDER BY kd_rek";
            $hasil=$this->db->query($sql1);    
            return $hasil;
        }
        
        //edit_sumber_kegi_sempurna
        function q_kegi_sumber($id,$angg,$nsumber1,$nsumber2,$nsumber3,$nsumber4){
            $hasil = '';
            $sql1="select * from(
                        select b.kd_kegiatan,b.nm_kegiatan,sum($angg) [nilai],STUFF(
                            (select '; '+rtrim(ltrim(sumber)) from(
                                select kd_kegiatan,$nsumber1 [sumber]  from trdrka where kd_skpd='$id' 
                                group by kd_kegiatan,$nsumber1
                                union all
                                select kd_kegiatan,$nsumber2 [sumber] from trdrka where kd_skpd='$id' 
                                and $nsumber2<>''
                                group by kd_kegiatan,$nsumber2
                                union all
                                select kd_kegiatan,$nsumber3 [sumber] from trdrka where kd_skpd='$id' 
                                and $nsumber3<>''
                                group by kd_kegiatan,$nsumber3
                                union all
                                select kd_kegiatan,$nsumber4 [sumber] from trdrka where kd_skpd='$id'
                                and $nsumber4<>''
                                group by kd_kegiatan,$nsumber4
                                )a where a.kd_kegiatan=b.kd_kegiatan group by sumber FOR XML PATH('')), 1, 1, ''
                        ) [sumber]
                    from trdrka b where b.kd_skpd='$id' group by b.kd_kegiatan,b.nm_kegiatan
                    )as d where d.nilai<>0 order by kd_kegiatan";
            $hasil=$this->db->query($sql1);    
            return $hasil;
        }

        function q_update_sumber_kegi($ckegi,$sumber,$nangg,$npad,$ndak,$ndaknf,$ndau,$ndbhp,$ndid){
            $hasil = '';
            $sql1="update trdrka set sumber1_su='$sumber',sumber2_su='',sumber3_su='',sumber4_su='',
                                     sumber1_ubah='$sumber',sumber2_ubah='',sumber3_ubah='',sumber4_ubah='',
                                     nsumber1_su=$nangg,nsumber2_su=0,nsumber3_su=0,nsumber4_su=0,
                                     nsumber1_ubah=$nangg,nsumber2_ubah=0,nsumber3_ubah=0,nsumber4_ubah=0 where kd_kegiatan='$ckegi'
                   update trdtagih set nil_pad=$npad,nil_dak=$ndak,nil_daknf=$ndaknf,nil_dau=$ndau,nil_dbhp=$ndbhp,nil_did=$ndid 
                   where kd_kegiatan='$ckegi'
                   update trdspp set nil_pad=$npad,nil_dak=$ndak,nil_daknf=$ndaknf,nil_dau=$ndau,nil_dbhp=$ndbhp,nil_did=$ndid 
                   where kd_kegiatan='$ckegi'               
                   update trdtransout set nil_pad=$npad,nil_dak=$ndak,nil_daknf=$ndaknf,nil_dau=$ndau,nil_dbhp=$ndbhp,nil_did=$ndid 
                   where kd_kegiatan='$ckegi'";
            $hasil=$this->db->query($sql1);    
            return $hasil;
        }    
        //end //edit_sumber_kegi_sempurna

        function q_update_sumber_transrek5($ckegi,$crek5,$sumber,$nangg,$npad,$ndak,$ndaknf,$ndau,$ndbhp,$ndid){
            $hasil = '';
            $sql1="update trdtagih set nil_pad=$npad,nil_dak=$ndak,nil_daknf=$ndaknf,nil_dau=$ndau,nil_dbhp=$ndbhp,nil_did=$ndid 
                   where kd_kegiatan='$ckegi' and kd_rek5='$crek5'
                   update trdspp set nil_pad=$npad,nil_dak=$ndak,nil_daknf=$ndaknf,nil_dau=$ndau,nil_dbhp=$ndbhp,nil_did=$ndid 
                   where kd_kegiatan='$ckegi' and kd_rek5='$crek5'              
                   update trdtransout set nil_pad=$npad,nil_dak=$ndak,nil_daknf=$ndaknf,nil_dau=$ndau,nil_dbhp=$ndbhp,nil_did=$ndid 
                   where kd_kegiatan='$ckegi' and kd_rek5='$crek5'" ;
            $hasil=$this->db->query($sql1);    
            return $hasil;
        }    
        
        function check_sdana($sumber){
            $sumber1 = $sumber;
            if($sumber=='DAK FISIK'){
                $sumber1 = 'dak';
            }else if($sumber=='DAK NON FISIK'){
                $sumber1 = 'daknf';
            }   
            return $sumber1;
        }

        function realbln_sumber(){
            $hasil  = '';$query='';
            $csql ="select top 1 triwulan from status_sumberdana where status=1 order by triwulan desc";
            $query = $this->db->query($csql);
            foreach($query->result_array() as $resulte)
            {   
                $hasil=$resulte['triwulan'];//$resulte->triwulan;top 1 triwulan
            }       
            

            switch($hasil) {
                case 1;  
                    $hasil = 3;
                break;
                case 2;
                    $hasil = 6;
                break;
                case 3;
                    $hasil = 9;
                break;
                case 4;
                    $hasil = 12;
                break;      
            }
            return $hasil;
        }
            
        function qtrans_sdana($sumber,$giat,$rek,$skpd){
            $sumber = $this->check_sdana($sumber);
            $triw = $this->realbln_sumber();
            $nilai  = 'nil_'.$sumber;
            $hasil  = 0;
            $csql ="select sum(nilai) [total] from (
                        select 'spp' [jdl],sum(isnull(b.$nilai,0)) [nilai] from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                        where b.kd_skpd='$skpd' and b.kd_kegiatan='$giat' and b.kd_rek5='$rek' and jns_spp not in ('1','2') and month(a.tgl_spp)<='$triw'
                        union all
                        select 'tagih' [jdl],isnull(sum(isnull(b.$nilai,0)),0) [nilai] from trhtagih a join trdtagih b on a.no_bukti=b.no_bukti 
                        and a.kd_skpd=b.kd_skpd 
                        where b.kd_skpd='$skpd' and b.kd_kegiatan='$giat' and b.kd_rek='$rek' and month(a.tgl_bukti)<='$triw' and b.no_bukti not in 
                        (select no_tagih from trhspp where kd_skpd='$skpd')
                        union all
                        select 'trans' [jdl],isnull(sum(isnull(b.nil_dau,0)),0) [nilai]  from trhtransout a join trdtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
                        where b.kd_skpd='$skpd' and b.kd_kegiatan='$giat' and b.kd_rek5='$rek' and month(a.tgl_bukti)<='$triw' and jns_spp  in ('1')
                    ) as gabung ";
            $hasil = $this->db->query($csql);
            return $hasil;
        } 
        
        function qcek_anggaran_kas($skpd,$angg){
            $hasil  = 0;
            $csql ="SELECT * from(
                        select a.kd_skpd,a.kd_sub_kegiatan,a.ang,isnull(b.kas,0) kas from(
                            select kd_skpd,kd_sub_kegiatan,sum($angg) [ang] from trdrka where kd_skpd='$skpd'
                            group by kd_skpd,kd_sub_kegiatan
                        )as a left join (
                            select kd_skpd,kd_sub_kegiatan,sum($angg) [kas] from trdskpd where kd_skpd='$skpd'
                            group by kd_skpd,kd_sub_kegiatan
                        )b on b.kd_skpd=a.kd_skpd and b.kd_sub_kegiatan=a.kd_sub_kegiatan 
                    )as c where kas<>ang order by kd_sub_kegiatan";
            $hasil = $this->db->query($csql);
            return $hasil;
        }

        function kode_ttd($kode){
            $kode1='';
            switch ($kode) {
                case 'PA':
                    $kode1 = 'Pengguna Anggaran';
                break;
                case 'KPA':
                    $kode1 = 'Kuasa Pengguna Anggaran';
                break;
            }
            return $kode1;
        }  

       function qcek_anggaran_kasbl($skpd,$angg){
            $hasil  = 0;
            $csql ="select * from(
                        select a.kd_skpd,a.kd_kegiatan,a.ang,isnull(b.kas,0) kas from(
                            select kd_skpd,kd_kegiatan,sum($angg) [ang] from trdrka where kd_skpd='$skpd' and right(kd_kegiatan,5) not in ('00.51','00.04')
                            group by kd_skpd,kd_kegiatan
                        )as a left join (
                            select kd_skpd,kd_kegiatan,sum($angg) [kas] from trdskpd where kd_skpd='$skpd' and right(kd_kegiatan,5) not in ('00.51','00.04')
                            group by kd_skpd,kd_kegiatan
                        )b on b.kd_skpd=a.kd_skpd and b.kd_kegiatan=a.kd_kegiatan 
                    )as c where kas<>ang order by kd_kegiatan";
            $hasil = $this->db->query($csql);
            return $hasil;
        } 

        
        
        function qcekdanarka($skpd,$s1,$ns1,$angg){
            $hasil  = 0;
            $csql ="select kd_skpd,kd_sub_kegiatan,kd_rek6 from trdrka where  ($s1='' or $ns1=0) 
                    and kd_skpd='$skpd' and $angg <> 0 order by kd_skpd,kd_sub_kegiatan,kd_rek6";
            $hasil = $this->db->query($csql);
            return $hasil;
        } 

        function qcekrincian($skpd,$angg){
            $hasil  = 0;
            $csql ="select * from(
                        select a.no_trdrka,a.kd_skpd,kd_sub_kegiatan,kd_rek6,$angg,t from trdrka a left join(
                        select no_trdrka,sum(total) [t] from trdpo group by no_trdrka) b on a.no_trdrka=b.no_trdrka where a.kd_skpd='$skpd'
                        )as d where nilai<>t ";
            $hasil = $this->db->query($csql);
            return $hasil;
        } 

        function qringkasangg_urus(){
            $hasil  = 0;
            $csql ="select nomor,urut,kode,nama,sum(pend) [pend],sum(btl) [btl],sum(bl) [bl] from(
                        select 1 [nomor] ,e.kd_urusan1 [urut],e.kd_urusan1 [kode],e.nm_urusan1 [nama],
                        (case when left(d.kd_rek5,1)='4' then isnull(sum(nilai),0) else 0 end) [pend],
                        (case when left(d.kd_rek5,2)='51' then isnull(sum(nilai),0) else 0 end) [btl],
                        (case when left(d.kd_rek5,2)='52' then isnull(sum(nilai),0) else 0 end) [bl] 
                        from ms_urusan1_baru  e join ms_urusan_baru a on left(a.kd_urusan_baru,1)=e.kd_urusan1
                        join ms_urusan_baru_skpd b on a.kd_urusan_baru=b.kd_urusan_baru  
                        join trskpd c on a.kd_urusan=c.kd_urusan and b.kd_skpd=c.kd_skpd 
                        join trdrka d on c.kd_kegiatan=d.kd_kegiatan 
                        group by kd_urusan1,nm_urusan1, d.kd_rek5
                    )as urut1 group by nomor,urut,kode,nama
                    union all
                    select nomor,urut,kode,nama,sum(pend) [pend],sum(btl) [btl],sum(bl) [bl] from(
                        select 2 [nomor],a.kd_urusan_baru [urut],a.kd_urusan_baru [kode],a.nm_urusan [nama],
                        (case when left(d.kd_rek5,1)='4' then isnull(sum(nilai),0) else 0 end) [pend],
                        (case when left(d.kd_rek5,2)='51' then isnull(sum(nilai),0) else 0 end) [btl],
                        (case when left(d.kd_rek5,2)='52' then isnull(sum(nilai),0) else 0 end) [bl] 
                        from ms_urusan_baru  a join ms_urusan_baru_skpd b on a.kd_urusan_baru=b.kd_urusan_baru  
                        join trskpd c on a.kd_urusan=c.kd_urusan and b.kd_skpd=c.kd_skpd 
                        join trdrka d on c.kd_kegiatan=d.kd_kegiatan 
                        group by a.kd_urusan_baru,nm_urusan,d.kd_rek5
                    )as urut2 group by nomor,urut,kode,nama 
                    union all
                    select nomor,urut,kode,nama,sum(pend) [pend],sum(btl) [btl],sum(bl) [bl] from(
                        select 3 [nomor],a.kd_urusan_baru+'.'+ e.kd_org [urut],e.kd_org [kode],rtrim(e.nm_org) [nama],
                        (case when left(d.kd_rek5,1)='4' then isnull(sum(nilai),0) else 0 end) [pend],
                        (case when left(d.kd_rek5,2)='51' then isnull(sum(nilai),0) else 0 end) [btl],
                        (case when left(d.kd_rek5,2)='52' then isnull(sum(nilai),0) else 0 end) [bl]
                        from ms_urusan_baru a join ms_urusan_baru_skpd b on a.kd_urusan_baru=b.kd_urusan_baru 
                        join trskpd c on a.kd_urusan=c.kd_urusan and b.kd_skpd=c.kd_skpd
                        join ms_organisasi e on LEFT(c.kd_skpd,7)=e.kd_org
                        join trdrka d on c.kd_kegiatan=d.kd_kegiatan group by a.kd_urusan_baru,e.kd_org,e.nm_org,d.kd_rek5
                    )as urut3 group by nomor,urut,kode,nama                
                    order by [urut]";
            $hasil = $this->db->query($csql);
            return $hasil;
        }   

    }

    /* End of file fungsi_model.php */
    /* Location: ./application/models/fungsi_model.php */