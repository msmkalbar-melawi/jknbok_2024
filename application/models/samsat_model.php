<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class samsat_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

	function savesamsat($tabel,$data,$tgl,$skpd){
		$hasil = 0;$cek = 0;
        $csql1 ="delete $tabel where tgl_samsat='$tgl' and kd_upt='$skpd'";
        $cek = $this->db->query($csql1);
        if($cek>0){
            $csql ="insert into $tabel values $data";
            $hasil = $this->db->query($csql);
        }
        return $hasil;
	}

    function tampil_samsat($tgl,$skpd,$tgl2){
        $hasil  = 0;

		$csql = "select isnull(no_tetap,'') no_tetap,isnull(no_terima,'') no_terima,isnull(no_sts,'') no_sts,tgl_samsat,kd_skpd,ISNULL(no_rek,no_rek2) [no_rek],
                ISNULL(nm_rek5,'') nm_rek5,jenis,kd_uptbyr,ISNULL(nm_pengirim,'') [nm_pengirim],
                isnull(kd_rek_lo,'') kd_rek_lo,isnull(keterangan,'') keterangan,kd_kegiatan,sum(nilai) [nilai] from(
                select replace(tgl_samsat,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/tetap'+kode+'/samsat' [no_tetap],
                replace(tgl_samsat,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/terima'+kode+'/samsat' [no_terima],
                replace($tgl2,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/sts'+kode+'/samsat/'+replace(tgl_samsat,'-','') [no_sts],
                a.tgl_samsat,kd_upt [kd_skpd],b.kd_rek5 [no_rek],a.no_rek [no_rek2],b.nm_rek5,
                a.kode [jenis],a.kd_uptbyr,c.nm_pengirim,
                a.jml_pener [nilai],b.map_lo [kd_rek_lo],b.nm_rek5+' dari '+c.nm_pengirim [keterangan],LEFT(a.kd_upt,5)+kd_upt+'.00.04' [kd_kegiatan]
                from tsamsat a  left join  
                    (
                        select d.kd_rek5,d.kd_rek64,d.nm_rek5,d.map_lo from ms_rek5 d join trdrka_pend e on d.kd_rek5=e.kd_rek5 
                        where kd_skpd='$skpd'
                        group by d.kd_rek5,d.kd_rek64,d.nm_rek5,d.map_lo
                    ) b on a.no_rek=b.kd_rek5
                left join ms_pengirim c on a.kd_uptbyr=c.kd_pengirim where tgl_samsat='$tgl' and kd_upt='$skpd'
                )as gabung group by no_tetap,no_terima,no_sts,tgl_samsat,kd_skpd,no_rek,no_rek2,nm_rek5,jenis,kd_uptbyr,nm_pengirim,kd_rek_lo,keterangan,kd_kegiatan
                order by kd_uptbyr,no_rek ";
		
		/*rumus piutang lama
        $csql = "select isnull(no_tetap,'') no_tetap,isnull(no_terima,'') no_terima,isnull(no_sts,'') no_sts,tgl_samsat,kd_skpd,ISNULL(no_rek,no_rek2) [no_rek],
                ISNULL(nm_rek5,'') nm_rek5,jenis,kd_uptbyr,ISNULL(nm_pengirim,'') [nm_pengirim],
                isnull(kd_rek_lo,'') kd_rek_lo,isnull(keterangan,'') keterangan,kd_kegiatan,sum(nilai) [nilai] from(
                select replace(tgl_samsat,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/tetap'+(case a.kode when '1' then '/Piutang' else '' end) [no_tetap],
                replace(tgl_samsat,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/terima'+(case a.kode when '1' then '/Piutang' else '' end) [no_terima],
                replace(tgl_samsat,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/sts'+(case a.kode when '1' then '/Piutang' else '' end) [no_sts],
                a.tgl_samsat,kd_upt [kd_skpd],b.kd_rek64 [no_rek],a.no_rek [no_rek2],b.nm_rek5,
                (case a.kode when '1' then '1' end) [jenis],a.kd_uptbyr,c.nm_pengirim,
                a.jml_pener [nilai],b.map_lo [kd_rek_lo],b.nm_rek5+' dari '+c.nm_pengirim [keterangan],LEFT(a.kd_upt,5)+kd_upt+'.00.04' [kd_kegiatan]
                from tsamsat a  left join  
                    (
                        select d.kd_rek5,d.kd_rek64,d.nm_rek5,d.map_lo from ms_rek5_sementara d join trdrka_pend e on d.kd_rek64=e.kd_rek5 
                        where kd_skpd='$skpd'
                        group by d.kd_rek5,d.kd_rek64,d.nm_rek5,d.map_lo
                    ) b on a.no_rek=b.kd_rek5
                left join ms_pengirim c on a.kd_uptbyr=c.kd_pengirim where tgl_samsat='$tgl' and kd_upt='$skpd'
                )as gabung group by no_tetap,no_terima,no_sts,tgl_samsat,kd_skpd,no_rek,no_rek2,nm_rek5,jenis,kd_uptbyr,nm_pengirim,kd_rek_lo,keterangan,kd_kegiatan
                order by kd_uptbyr,no_rek ";*/
        /*
        $csql = "select top 1 no_tetap,no_terima,no_sts,tgl_samsat,kd_skpd,no_rek,jenis,kd_uptbyr,kd_rek_lo,keterangan,kd_kegiatan,sum(nilai) [nilai] from(
                select 'A' [no_tetap],
                replace(tgl_samsat,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/terima'+(case a.kode when '1' then '/Piutang' else '' end) [no_terima],
                replace(tgl_samsat,'-','')+'/'+b.kd_rek5+'/'+kd_uptbyr+'/'+kd_upt+'/sts'+(case a.kode when '1' then '/Piutang' else '' end) [no_sts],
                a.tgl_samsat,kd_upt [kd_skpd],a.no_rek,
                (case a.kode when '1' then '1' end) [jenis],a.kd_uptbyr,
                a.jml_pener [nilai],b.map_lo [kd_rek_lo],b.nm_rek5+' dari '+c.nm_pengirim [keterangan],LEFT(a.kd_upt,5)+kd_upt+'.00.04' [kd_kegiatan]
                from tsamsat a join  ms_rek5 b on a.no_rek=b.kd_rek5
                join ms_pengirim c on a.kd_uptbyr=c.kd_pengirim where tgl_samsat='$tgl' and kd_upt='$skpd'
                )as gabung group by no_tetap,no_terima,no_sts,tgl_samsat,kd_skpd,no_rek,jenis,kd_uptbyr,kd_rek_lo,keterangan,kd_kegiatan
                order by kd_uptbyr,no_rek";
        */
        $hasil = $this->db->query($csql);
        return $hasil;
    }  
    

	
}