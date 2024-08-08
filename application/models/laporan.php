<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Model {
    /**
    * Inisialisasi propery query
    *
    * @var string $query
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private $query;

    /**
    * Inisialisasi propery builder
    *
    * @var string $builder
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private $builder;

    /**
    * Inisialisasi propery type data yang akan di ambil (JKN/BOK)
    *
    * @var string $query
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private $type;

    /**
    * inisialisasi Kode Rekening
    * 
    * @var int
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private $rekening;

    /**
    * periode tanggal untuk mengambil data harus dalam bentuk array
    * 
    * @var array $periode 
    * @return 
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private $periode;

    /**
    * Kode Skpd
    * 
    * @var string $skpd 
    * @return 
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private $skpd;

    /**
    * Inisialisasi jenis anggaran
    * 
    * @var string $anggaran 
    * @return 
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private $anggaran;

    public function __construct()
    {
        parent::__construct();
        $this->type = 'jkn';
        $this->rekening = 6;
        $this->query = "";
        $this->builder = "";
    }

    /**
    * Set value type
    * 
    * @param string $type
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    public function type($type = 'jkn')
    {
        $this->type = $type === 'jkn' ? 'jkn' : 'bok';
        return $this;
    }

    /**
    * set ms rekening yang akan di ambil 1-6
    * 
    * @param int $rekening
    * @return self
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    public function rekening($rekening)
    {
        $this->rekening = $rekening > 6 ? 6 : $rekening;
        $this->terima();
        $this->anggaran();
        return $this;
    }

    /**
    * set nilai periode
    * 
    * @param array $periode
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    public function periode($periode)
    {
        $this->periode = $periode;
        return $this;
    }

    /** 
     * Menambahkan UNION ALL query builder
     * 
     * @return self
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function union()
    {
        $this->builder .= " UNION ALL ";
        return $this;
    }

    /**
    * Set nilai skpd
    * 
    * @param string $skpd
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    public function skpd($skpd)
    {
        $this->skpd = $skpd;
        return $this;
    }

    /** 
     * Generate query builder
     * @return string $sql
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function toSql()
    {
        $this->_baseQuery();
        $sql = $this->query;
        $this->resetQuery();
        return $sql;
    }

    /**
    * Set value jenis anggaran
    * 
    * @param string $anggaran
    * @return self
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    public function jenisAnggaran($anggaran)
    {
        $this->anggaran = $anggaran;
        return $this;
    }

    private function _baseQuery()
    {
        $transout = $this->type.'_trhtransout';
        $detailtransaout = $this->type.'_trdtransout';

        $sql = "SELECT 
                source.urut,
                source.kode_rekening AS kd_rek6,
                source.nama_rekening AS nm_rek6,
                kegiatan.kd_sub_kegiatan,
                kegiatan.nm_sub_kegiatan,
                source.anggaran,";
        $sql .= "ISNULL(
                    (
                    SELECT SUM
                        ( trd.nilai ) 
                    FROM
                        ".$detailtransaout." AS trd
                        INNER JOIN ". $transout ." AS trh 
                        ON trh.kd_skpd = trd.kd_skpd AND trd.no_bukti = trh.no_bukti
                    WHERE
                        trh.kd_skpd = source.kd_skpd 
                        AND LEFT(trd.kd_rek6,LEN(source.kode_rekening)) = source.kode_rekening 
                        AND trd.kd_sub_kegiatan = source.kd_sub_kegiatan
                        AND trh.tgl_bukti BETWEEN '". $this->periode[0] ."' 
                        AND '". $this->periode[1] ."' 
                    ),
                    0 
                ) AS realisasi";
        $sql .= " FROM (";
        $sql .= $this->builder;
        $sql .= ") AS source ";
        $sql .= " INNER JOIN ms_sub_kegiatan AS kegiatan ON source.kd_sub_kegiatan = kegiatan.kd_sub_kegiatan ORDER BY kd_sub_kegiatan DESC, urut, kode_rekening";
        $this->query = $sql;
        return $this->query;
    }
    
    private function terima()
    {
        $terima = $this->type.'_tr_terima';
        $this->builder .= "SELECT
                kd_skpd,
                kd_sub_kegiatan,
                ".($this->rekening - 1) ." AS urut,
                ". $this->_masterKodeRekening() ." AS kode_rekening,
                ". $this->_masterNamaRekening() ." AS nama_rekening,
                SUM ( nilai ) AS anggaran 
            FROM
                $terima terima
                ". $this->joinRekening('terima.kd_rek6') ."
            WHERE
                tgl_terima BETWEEN '". $this->periode[0] ."' 
                AND '". $this->periode[1] ."' 
                AND kd_skpd = '". $this->skpd ."' 
            GROUP BY
                kd_skpd,
                kd_sub_kegiatan,
                ". $this->_masterKodeRekening() .",
                ".$this->_masterNamaRekening()." UNION ALL ";

        return $this;
    }

    private function anggaran()
    {
        $jenisAnggaran = $this->anggaran ? $this->anggaran : 'M';
        $anggaran = $this->type.'_trdrka';
        $this->builder .= "SELECT
                kd_skpd,
                kd_sub_kegiatan,
                ".($this->rekening - 1) ." AS urut,
                ". $this->_masterKodeRekening() ." AS kode_rekening,
                ". $this->_masterNamaRekening() ." AS nama_rekening,
                SUM ( nilai ) AS anggaran 
            FROM
                $anggaran rka
                ". $this->joinRekening('rka.kd_rek6') ."
            WHERE
                 kd_skpd = '". $this->skpd ."' 
                 AND jns_ang = '". $jenisAnggaran ."'
            GROUP BY
                kd_skpd,
                kd_sub_kegiatan,
                ". $this->_masterKodeRekening() .",
                ".$this->_masterNamaRekening()."";
    }

    /** 
     * Menjalankan query
     * @return object $result
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function get()
    {
        $this->_baseQuery();
        $result =  $this->db->query($this->query);
        $this->resetQuery(); // reset query;
        return $result;
    }

     /** 
     * Reset query setelah di jalankan
     * @return $this
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    private function resetQuery()
    {
        $this->query = "";
        $this->builder = "";
    }

    private function joinRekening($alias)
    {
        $master = [
            1 => " INNER JOIN ms_rek1 AS rek ON rek.kd_rek1 = LEFT($alias,1)",
            2 => " INNER JOIN ms_rek2 AS rek ON rek.kd_rek2 = LEFT($alias,2)",
            3 => " INNER JOIN ms_rek3 AS rek ON rek.kd_rek3 = LEFT($alias,4)",
            4 => " INNER JOIN ms_rek4 AS rek ON rek.kd_rek4 = LEFT($alias,6)",
            5 => " INNER JOIN ms_rek5 AS rek ON rek.kd_rek5 = LEFT($alias,8)",
            6 => " INNER JOIN ms_rek6 AS rek ON rek.kd_rek6 = $alias",
        ];

        return $master[$this->rekening];
    }

    private function _masterNamaRekening()
    {
        $masterNamaRekening = [
            1 => 'rek.nm_rek1',
            2 => 'rek.nm_rek2',
            3 => 'rek.nm_rek3',
            4 => 'rek.nm_rek4',
            5 => 'rek.nm_rek5',
            6 => 'rek.nm_rek6',
        ];
        return $masterNamaRekening[$this->rekening];
    }

    private function _masterKodeRekening()
    {
        $masterKodeRekening = [
            1 => 'rek.kd_rek1',
            2 => 'rek.kd_rek2',
            3 => 'rek.kd_rek3',
            4 => 'rek.kd_rek4',
            5 => 'rek.kd_rek5',
            6 => 'rek.kd_rek6',
        ];
        return $masterKodeRekening[$this->rekening];
    }
}