<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['penerima']     = "sp2d_bank/mpenerima"; //OK
$route['data_opd']     = "master/data_opd"; //OK
$route['mttd']       = "master/mttd"; //OK
$route['mperusahaan']   = "master/mperusahaan"; //OK


$route['bp_ro_penerimaan'] = "tukd/bp_ro_penerimaan";
//----------------------------------------------------
$route['default_controller'] = "welcome";
//----------------------------------------------------
$route['tambah_rka_penyusunan']             = "rka_rancang/tambah_rka_penyusunan"; //OK
$route['rka_skpd_penyusunan']               = "rka_rancang/rka0_penyusunan"; //OK
$route['preview_rka0_penyusunan/(:any)']         = "rka_rancang/preview_rka0_penyusunan/"; //OK
$route['rka_pendapatan_penyusunan']           = "rka_rancang/rka_pendapatan_penyusunan"; //OK
$route['preview_pendapatan_penyusunan/(:any)']       = "rka_rancang/preview_pendapatan_penyusunan/"; //OK
$route['preview_rka0_penyusunan_org/(:any)']       = "rka_rancang/preview_rka0_penyusunan_org/"; //OK
$route['rka_belanja_skpd']                 = "rka_rancang/rka22_penyusunan"; //OK
$route['preview_rka22_penyusunan/(:any)']         = "Rka_rancang/preview_rka22_penyusunan/"; //OK
$route['rka_rincian_belanja_skpd']             = "rka_rancang/rka221_penyusunan"; //OK
$route['preview_rka221_penyusunan/(:any)']         = "rka_rancang/preview_rka221_penyusunan/"; //OK
$route['daftar_kegiatan_penyusunan/(:any)']       = "rka_rancang/daftar_kegiatan_penyusunan/"; //OK
$route['pilih_giat_penyusunan']             = "rka_rancang/tambah_giat_penyusunan"; //OK
$route['rka_pembiayaan_penyusunan']           = "rka_rancang/rka_pembiayaan_penyusunan"; //OG
$route['preview_rka_pembiayaan_penyusunan/(:any)']     = "rka_rancang/preview_rka_pembiayaan_penyusunan/"; //OG

$route['dpa_belanja_skpd_penetapan/(:any)']        = "cetak_dpa/preview_rka_belanja_skpd_penetapan";
$route['dpa_belanja']                  = "cetak_dpa/ctk_dpa22";
$route['dpa_skpd']                     = "cetak_dpa/dpa_skpd_penetapan";
$route['dpa_pendapatan']                 = "cetak_dpa/dpa_pendapatan_penetapan";
$route['dpa_rinci_belanja']               = "cetak_dpa/dpa221";
$route['daftar_kegiatan_penetapan1/(:any)']       = "cetak_dpa/daftar_kegiatan_penetapan1/";
$route['preview_dpa_skpd_penetapan/(:any)']       = "cetak_dpa/preview_dpa_skpd_penetapan/";
$route['preview_dpa_rincian_belanja_skpd_penetapan/(:any)'] = "cetak_dpa/preview_dpa_rincian_belanja_skpd_penetapan/";
$route['preview_dpa_pendapatan_penetapan/(:any)']     = "cetak_dpa/preview_pendapatan_penetapan/";
$route['dpa_pembiayaan']                     = "cetak_dpa/dpa_pembiayaan_penetapan"; //OG
$route['preview_dpa_pembiayaan_penetapan/(:any)']     = "cetak_dpa/preview_dpa_pembiayaan_penetapan/";


$route['cetak_dpa_rekap_geser2']                         = "cetak_rka/cetak_rka_rekap_geser2/DPA";



$route['tambah_rka_penetapan']                 = "rka_penetapan/tambah_rka_penetapan"; //OG
$route['update_rka_penetapan']                 = "rka_penetapan/tambah_rka_penetapan_sumber"; //OG
$route['rka_skpd_penetapan']               = "rka_penetapan/rka0_penetapan";
$route['preview_rka_skpd_penetapan/(:any)']       = "rka_penetapan/preview_rka_skpd_penetapan/";
$route['preview_rka_skpd_penetapan_org/(:any)']     = "rka_penetapan/preview_rka_skpd_penetapan_org/";
$route['rka_pendapatan_penetapan']             = "rka_penetapan/rka_pendapatan_penetapan"; //OK
$route['preview_pendapatan_penetapan/(:any)']       = "rka_penetapan/preview_pendapatan_penetapan/"; //OK
$route['rka_belanja_skpd_penetapan']           = "rka_penetapan/rka_belanja_skpd_penetapan";
$route['preview_rka_belanja_skpd_penetapan/(:any)']   = "Rka_penetapan/preview_rka_belanja_skpd_penetapan/";
$route['rka_rincian_belanja_skpd_penetapan']      = "rka_penetapan/rka_rincian_belanja_skpd_penetapan";
$route['preview_rincian_belanja_skpd_penetapan/(:any)'] = "rka_penetapan/preview_rincian_belanja_skpd_penetapan/";
$route['daftar_kegiatan_penetapan/(:any)']         = "rka_penetapan/daftar_kegiatan_penetapan/";
$route['pilih_giat_penetapan']               = "rka_penetapan/tambah_giat_penetapan"; //OK
$route['rka_pembiayaan_penetapan']             = "rka_penetapan/rka_pembiayaan_penetapan"; //OG
$route['preview_rka_pembiayaan_penetapan/(:any)']     = "rka_penetapan/preview_rka_pembiayaan_penetapan/"; //OG
$route['anggaran_kas_penetapan']             = "rka_ro/angkas_ro"; //OG
$route['angkas_sempurna']                 = "rka_ro/angkas_sempurna"; //OG
$route['cetak_anggaran_kas_penetapan']           = "rka_ro/cetak_angkas_ro/1"; //OG
$route['cetak_anggaran_kas_penetapan_giat']       = "rka_ro/cetak_angkas_giat/1"; //OG
$route['preview_anggaran_kas_penetapan/(:any)']     = "rka_penetapan/preview_cetak_anggaran_kas"; //OK
$route['cek_angkas_skpd/(:any)']             = "rka_penetapan/cek_angkas_skpd"; //OG

$route['cek_anggaran']                   = "rka_ro/cek_anggaran";
$route['cek_anggaran_geser']               = "rka_ro/cek_anggaran_geser";
$route['cetak_anggaran_kas_penetapan_giat_geser']     = "rka_ro/cetak_angkas_giat_geser/2"; //OK
$route['cetak_anggaran_kas_penetapan_geser']       = "rka_ro/cetak_angkas_ro/2"; //OK
$route['cetak-dpa-belanja-ubah']                         = "cetak_rka/rka_belanja_ubah/DPPA"; //OK
$route['cetak-dpa-pendapatan-ubah']                     = "cetak_rka/cetak_rka_pendapatan_ubah/DPA";
$route['cetak-dpa-rekap-ubah']                                 = "cetak_rka/cetak_rka_rekap_ubah/DPPA";


$route['preview_rka_skpd_pergeseran/(:any)']             = "cetak_rka/preview_rka_skpd_pergeseran/";
$route['preview_belanja_pergeseran/(:any)']             = "cetak_rka/preview_belanja_pergeseran/";
$route['preview_pendapatan_pergeseran/(:any)']                 = "cetak_rka/preview_pendapatan_pergeseran/"; //OK
$route['anggaran_kas_penetapan1']             = "rka_ro/angkas_ro1";

// Anggaran Kas Penyempurnaan I
$route['anggaran_kas_penyempurnaan']           = "rka_ro/angkas_penyempurnaan";
$route['anggaran_kas_penyempurnaan1']            = "rka_ro/anggaran_kas_penyempurnaan1";

//anggaran penyempurnaan2
$route['tambah_rka_sempurna2']                     = "rka_penyempurnaan2/tambah_rka_sempurna2"; //OG

// TUKD
//REALISASI FISIk
$route['realisasi_fisik']         = "tukd/realisasi_fisik";
// PENDAPATAN
$route['pendapatan_penetapan']         = "Penetapan";
$route['sts_kas']                    = "tukd/sts_kas";
$route['lap_trm_str']                    = "tukd/lap_trm_str";
$route['spj_terima']                    = "tukd/spj_terima";
$route['lap_sts']                      = "tukd/lap_sts";
$route['kembali_panjar']                    = "tukd/kembalipanjar";
$route['sts_belanja']                    = "tukd/sts_belanja";

//PENERIMAAN
$route['penerimaan_piutang']         = "Penerimaan/penerimaan_piutang";
$route['penerimaan']             = "Penerimaan/penerimaan_skpd";

// Penyetoran pendapatan
$route['penyetoran_piutang']         = "Penerimaan/penyetoran_piutang";
$route['penyetoran']             = "Penyetoran/penyetorans";

//PENGELUARAN
$route['penagihan_prov']           = "Penagihan/penagihan_skpd";

$route['kontrak']              = "master/mkontrak";
$route['penagihan']             = "Penagihan/penagihanskpd";

$route['spp_up']               = "Spp/sppup";
$route['spp_ls']               = "Spp/sppls";
$route['spp_tu']               = "Spp/spptu";
$route['spp_gu']               = "Spp/sppgu";
$route['spm_ar']               = "Spm/spm1";

$route['register_spp']             = "Spp/reg_spp";

$route['sp2d_skpd']             = "Sp2d/sp2dskpd";
$route['terima_sp2d']             = "Sp2d/terima_sp2d";
$route['cair_sp2d_skpd']           = "Sp2d/sp2d_cair";
$route['kartu_kendali']           = "Sp2d/kartu_kendali";

// SPP GU NIHIL
$route['spp_gu_nihil']         = "Spp/sppgunihil";

$route['cetak_kartukendali']           = "cetak_kartukendali/index";

//koreksi
$route['koreksi_transaksi']                         = "koreksi/transout_koreksi";
$route['koreksi_transaksi_nominal']                    = "koreksi/transout_koreksi2";

//EDIT SPP SPM
$route['editsppspm']           = "editsppspm/index";
// END

// CMS
$route['transaksi_cms']           = "cms/index";
$route['upload_transaksi_cms']           = "clist_upload/index";
$route['validasi_transaksi_cms']       = "clist_validasi/index";
$route['ctrmpot']                       = "ctrmpot/index";
$route['cpanjar_cms']                   = "cpanjar_cms/index";
$route['ctambah_panjar_cms']           = "ctambah_panjar_cms/index";
$route['cpanjar_upload']               = "cpanjar_upload/index";
$route['cpanjar_validasi']               = "cpanjar_validasi/index";
$route['setor_simpanan_bidang']       = "cms/setor_simpanan_bidang";
$route['upload_setor_simpanan_bidang']     = "cms/upload_setor_simpanan_bidang";
$route['validasi_setor_simpanan_bidang']   = "cms/validasi_setor_simpanan_bidang";
$route['ambil_bank_bidang']         = "cms/ambil_bank_bidang";

// TUNAI
$route['ambil_simpanan']           = "Tunai/ambil_simpanan_ar";
$route['transout']               = "Tunai/transout";

$route['setor_simpanan'] = "tukd/setor_simpanan";

//cetak tukd
$route['bku']               = "Cetak_tukd/bku";
$route['dth']                 = "tukd/dth";
$route['spj']               = "Cetak_spj/index";
$route['buku_simpanan_bank']       = "Cetak_bukubank/index";
$route['buku_tunai']           = "Cetak_buku_tunai/index";
$route['buku_pajak']           = "Cetak_pajak/index";
$route['rincian_objek']         = "Cetak_rincian_objek/index";


//pindahbuku

$route['pindahbuku']               = "pindah_bank/transout";
$route['trmpot_pndhbank']             = "tunai_trmpot/trmpot_pndhbank";


//setor
$route['strpot']               = "setor/strpot";

// SPD
$route['cetak_spd'] = 'SPDController/index';

//LPJ
$route['lpj_tu']                   = "Lpj/tu";
$route['lpjup']                    = "lpj/up";
$route['lpjup_unit']             = "lpj/up_unit";
$route['validasi_lpj_unit']     = "lpj/validasi_up_unit";
$route['lpjup_gabungan']         = "lpj/up_gab";

//MAPPING
$route['imapping']               = "mapping/imapping";
$route['input_indikator']           = "mapping/input_indikator";
$route['validasi_indikator']         = "mapping/validasi_indikator";
$route['validasi_kegiatan']         = "mapping/validasi_kegiatan";

//MASTER
$route['ganti_password']           = "master/ganti_pass";
$route['kontrak']               = "master/mkontrak";

$route['index'] = "index";
$route['login'] = "login";
$route['logout'] = "logout";
$route['404_override'] = 'my404';

// JKN
$route['masterttdjkn'] = "jkn/MasterTTDController/index";
$route['penetapanjkn'] = "jkn/PenetapanJKNController/index";
$route['penerimaanjkn'] = "jkn/PenerimaanJKNController/index";
$route['bkupenerimaanjkn'] = "jkn/PenerimaanJKNController/laporanindex";
$route['dropingdanajkn'] = "jkn/DropingdanaJKNController/index";
$route['ambilsimpananjkn'] = "jkn/AmbilsimpananController/index";
$route['laporanbkujkn'] = "jkn/BKUController/index";
$route['transaksijkn'] = "jkn/JKNTransController/index";
$route['setorpotonganjkn'] = "jkn/SetorPotonganController/index";
$route['dropinganggaranjkn'] = "jkn/DropinganggaranJKNController/index";
$route['sp2bjkn'] = "jkn/SP2BController/index";
$route['jkntrans'] = "jkn/JKNTransController/jkntrans";

//LRA JKN BOK
$route['lrajknbokskpd'] = "jkn/LraController/index";
$route['penerimaanbok'] = "bok/PenerimaanBOKController/index";
$route['bkupenerimaanbok'] = "bok/PenerimaanBOKController/laporanindex";
$route['bppajakjknbok'] = "jkn/BppajakController/index";
// BOK
$route['dropinganggaranbok'] = "bok/DropinganggaranController/index";
$route['laporanbkubok'] = "bok/BKUController/index";
$route['transaksibok'] = "bok/TransaksiController/index";
$route['setorpotonganbok'] = "bok/SetorPotonganController/index";
$route['sp3bbok'] = "bok/SP3BController/index";
$route['jurnalumumbok'] = "bok/JurnalUmumControllerBok/index";
$route['boktrans'] = "bok/BOKTransController/boktrans";


// BLUD
$route['sp3bblud'] = "blud/SP3BBludController/index";

// RINCIAN KARTU KENDALI
$route['bukurincian']="bukurincian/BukuRincianController/index";


// End


/* End of file routes.php */
/* Location: ./application/config/routes.php */