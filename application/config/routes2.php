<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
$route['penerima'] 					= "sp2d_bank/mpenerima";//OK
$route['data_opd'] 					= "master/data_opd";//OK
$route['mttd'] 						= "master/mttd";//OK
$route['sumber_dana_kasda'] 		= "master/sumber_dana_kasda";//OK


$route['default_controller'] = "welcome";
$route['tambah_rka_penyusunan'] 						= "rka_rancang/tambah_rka_penyusunan";//OK
$route['rka_skpd_penyusunan'] 							= "rka_rancang/rka0_penyusunan";//OK
$route['preview_rka0_penyusunan/(:any)'] 				= "rka_rancang/preview_rka0_penyusunan/";//OK
$route['rka_pendapatan_penyusunan'] 					= "rka_rancang/rka_pendapatan_penyusunan";//OK
$route['preview_pendapatan_penyusunan/(:any)'] 			= "rka_rancang/preview_pendapatan_penyusunan/";//OK
$route['preview_rka0_penyusunan_org/(:any)'] 			= "rka_rancang/preview_rka0_penyusunan_org/";//OK
$route['rka_belanja_skpd'] 								= "rka_rancang/rka22_penyusunan";//OK
$route['preview_rka22_penyusunan/(:any)'] 				= "Rka_rancang/preview_rka22_penyusunan/";//OK
$route['rka_rincian_belanja_skpd'] 						= "rka_rancang/rka221_penyusunan";//OK
$route['preview_rka221_penyusunan/(:any)'] 				= "rka_rancang/preview_rka221_penyusunan/";//OK
$route['daftar_kegiatan_penyusunan/(:any)'] 			= "rka_rancang/daftar_kegiatan_penyusunan/";//OK
$route['pilih_giat_penyusunan'] 						= "rka_rancang/tambah_giat_penyusunan";//OK
$route['rka_pembiayaan_penyusunan'] 					= "rka_rancang/rka_pembiayaan_penyusunan";//OG
$route['preview_rka_pembiayaan_penyusunan/(:any)'] 		= "rka_rancang/preview_rka_pembiayaan_penyusunan/";//OG

$route['dpa_belanja_skpd_penetapan/(:any)']				= "cetak_dpa/preview_rka_belanja_skpd_penetapan";
$route['dpa_belanja']									= "cetak_dpa/ctk_dpa22";
$route['dpa_skpd'] 										= "cetak_dpa/dpa_skpd_penetapan";
$route['dpa_pendapatan'] 								= "cetak_dpa/dpa_pendapatan_penetapan";
$route['dpa_rinci_belanja'] 							= "cetak_dpa/dpa221";
$route['daftar_kegiatan_penetapan1/(:any)'] 			= "cetak_dpa/daftar_kegiatan_penetapan1/";
$route['preview_dpa_skpd_penetapan/(:any)'] 			= "cetak_dpa/preview_dpa_skpd_penetapan/";
$route['preview_dpa_rincian_belanja_skpd_penetapan/(:any)'] = "cetak_dpa/preview_dpa_rincian_belanja_skpd_penetapan/";
$route['preview_dpa_pendapatan_penetapan/(:any)'] 		= "cetak_dpa/preview_pendapatan_penetapan/";
$route['dpa_pembiayaan'] 								= "cetak_dpa/dpa_pembiayaan_penetapan";//OG
$route['preview_dpa_pembiayaan_penetapan/(:any)'] 		= "cetak_dpa/preview_dpa_pembiayaan_penetapan/";

$route['tambah_rka_penetapan'] 			    			= "rka_penetapan/tambah_rka_penetapan";//OG
$route['update_rka_penetapan'] 			    			= "rka_penetapan/tambah_rka_penetapan_sumber";//OG
$route['rka_skpd_penetapan'] 							= "rka_penetapan/rka0_penetapan";
$route['preview_rka_skpd_penetapan/(:any)'] 			= "rka_penetapan/preview_rka_skpd_penetapan/";
$route['preview_rka_skpd_penetapan_org/(:any)'] 		= "rka_penetapan/preview_rka_skpd_penetapan_org/";
$route['rka_pendapatan_penetapan'] 						= "rka_penetapan/rka_pendapatan_penetapan";//OK
$route['preview_pendapatan_penetapan/(:any)'] 			= "rka_penetapan/preview_pendapatan_penetapan/";//OK
$route['rka_belanja_skpd_penetapan'] 					= "rka_penetapan/rka_belanja_skpd_penetapan";
$route['preview_rka_belanja_skpd_penetapan/(:any)'] 	= "Rka_penetapan/preview_rka_belanja_skpd_penetapan/";
$route['rka_rincian_belanja_skpd_penetapan']			= "rka_penetapan/rka_rincian_belanja_skpd_penetapan";
$route['preview_rincian_belanja_skpd_penetapan/(:any)'] = "rka_penetapan/preview_rincian_belanja_skpd_penetapan/";
$route['daftar_kegiatan_penetapan/(:any)'] 				= "rka_penetapan/daftar_kegiatan_penetapan/";
$route['pilih_giat_penetapan'] 							= "rka_penetapan/tambah_giat_penetapan";//OK
$route['rka_pembiayaan_penetapan'] 						= "rka_penetapan/rka_pembiayaan_penetapan";//OG
$route['preview_rka_pembiayaan_penetapan/(:any)'] 		= "rka_penetapan/preview_rka_pembiayaan_penetapan/";//OG

// u=murni
$route['anggaran_kas_penetapan'] 						= "rka_ro/angkas_ro";//OG
$route['cetak_anggaran_kas_penetapan'] 					= "rka_ro/cetak_angkas_ro/1";//OG
$route['cetak_anggaran_kas_penetapan_giat'] 			= "rka_ro/cetak_angkas_giat/1";//OG
$route['preview_anggaran_kas_penetapan/(:any)'] 		= "rka_penetapan/preview_cetak_anggaran_kas";//OK
$route['cek_angkas_skpd/(:any)'] 						= "rka_penetapan/cek_angkas_skpd";//OG

$route['cek_anggaran'] 									= "rka_ro/cek_anggaran";

// murni geser 1
$route['anggaran_kas_penetapan1'] 						= "rka_ro/angkas_ro1";//OG
$route['cetak_anggaran_kas_penetapan1'] 				= "rka_ro/cetak_angkas_ro1/1";//OG
$route['cetak_anggaran_kas_penetapan_giat1'] 			= "rka_ro/cetak_angkas_giat1/1";//OG
$route['preview_anggaran_kas_penetapan1/(:any)'] 		= "rka_penetapan/preview_cetak_anggaran_kas1";//OK
$route['cek_angkas_skpd1/(:any)'] 						= "rka_penetapan/cek_angkas_skpd1";//OG

// PENYEMPURNAAN 1
$route['anggaran_kas_penyempurnaan'] 					= "rka_ro/angkas_penyempurnaan";
$route['anggaran_kas_penyempurnaan11'] 					= "rka_ro/angkas_penyempurnaan11";

// PENYEMPURNAAN 2
$route['input_rak'] 					= "rka_ro/angkas_penyempurnaan2";

$route['cetak_anggaran_kas_penyempurnaan'] 				= "rka_ro/cetak_angkas_ro_penyempurnaan/1";//OG
$route['cetak_anggaran_kas_penyempurnaan_giat'] 		= "rka_ro/cetak_angkas_giat_penyempurnaan/1";//OG
$route['tambah_rka_sempurna'] 			        = "rka_penyempurnaan/tambah_rka_sempurna";//OG
$route['tambah_detail_sempurna'] 			        = "rka_penyempurnaan/tambah_detail_sempurna";//OG
$route['dpa_skpd_penyempurnaan'] 				= "cetak_dpa/dpa_skpd_penyempurnaan";
$route['tambah_rka_sempurna2'] 			        = "rka_penyempurnaan2/tambah_rka_sempurna2";//OG




// TUKD
// PENDAPATAN
$route['pendapatan_penetapan'] 				= "Penetapan/penetapan_pendapatan";
$route['sts_kas']            				="tukd/sts_kas";
$route['lap_trm_str']                		="tukd/lap_trm_str";
$route['spj_terima']                		="tukd/spj_terima";
$route['lap_sts']                			="tukd/lap_sts";

//PENERIMAAN
$route['penerimaan_piutang'] 				= "Penerimaan/penerimaan_piutang";
$route['penerimaan'] 						= "Penerimaan/penerimaan_skpd";

// Penyetoran pendapatan
$route['penyetoran_piutang'] 				= "Penerimaan/penyetoran_piutang";
$route['penyetoran'] 						= "Penyetoran/penyetorans";

//PENGELUARAN
$route['penagihan_prov'] 					= "Penagihan/penagihan_skpd";

$route['kontrak']							= "master/mkontrak";
$route['penagihan'] 						= "Penagihan/penagihanskpd";
$route['penagihan2'] 						= "Penagihan/penagihanskpd_kasda";

$route['spp_up'] 							= "Spp/sppup";
$route['spp_ls'] 							= "Spp/sppls";
$route['spp_tu'] 							= "Spp/spptu";
$route['spp_gu'] 							= "Spp/sppgu";		
$route['spm_ar'] 							= "Spm/spm1";

$route['register_spp'] 							= "Spp/reg_spp";

$route['sp2d_skpd'] 						= "Sp2d/sp2dskpd";
$route['terima_sp2d'] 						= "Sp2d/terima_sp2d";
$route['cair_sp2d_skpd'] 					= "Sp2d/sp2d_cair";
$route['kartu_kendali'] 					= "Sp2d/kartu_kendali";	


	


// CMS
$route['transaksi_cms'] 					= "cms/index";
$route['upload_transaksi_cms'] 			    = "clist_upload/index";
$route['validasi_transaksi_cms'] 			= "clist_validasi/index";
$route['ctrmpot'] 			                = "ctrmpot/index";
$route['cpanjar_cms'] 			            = "cpanjar_cms/index";
$route['ctambah_panjar_cms'] 			    = "ctambah_panjar_cms/index";
$route['cpanjar_upload'] 			        = "cpanjar_upload/index";
$route['cpanjar_validasi'] 			        = "cpanjar_validasi/index";
$route['setor_simpanan_bidang'] 			= "cms/setor_simpanan_bidang";
$route['setor_simpanan_bidang_gu'] 			= "cms/setor_simpanan_bidang_gu";
$route['upload_setor_simpanan_bidang'] 		= "cms/upload_setor_simpanan_bidang";
$route['validasi_setor_simpanan_bidang'] 	= "cms/validasi_setor_simpanan_bidang";
$route['ambil_bank_bidang'] 				= "cms/ambil_bank_bidang";
$route['setor_sisakas_unit'] 				= "cms/setor_sisakas_bidang";
$route['upload_setor_sisakas_unit'] 		= "cms/upload_setor_simpanan_bidang";
$route['validasi_setor_sisakas_unit'] 		= "cms/validasi_setor_simpanan_bidang";

// TUNAI
$route['ambil_simpanan'] 					= "Tunai/ambil_simpanan_ar";
$route['setor_simpanan'] 					= "Tukd/setor_simpanan";
$route['transout'] 							= "Tunai/transout";

//cetak tukd
$route['bku'] 							= "Cetak_tukd/bku";
$route['spj'] 							= "Cetak_spj/index";
$route['buku_simpanan_bank'] 			= "Cetak_bukubank/index";
$route['buku_tunai'] 					= "Cetak_buku_tunai/index";
$route['buku_pajak'] 					= "Cetak_pajak/index";
$route['rincian_objek'] 				= "Cetak_rincian_objek/index";
$route['kas_bulan'] 					= "cetak_tukd/kas_bulan"; //belum
$route['dth'] 							= "tukd/dth";
$route['realisasi_fisik'] 				= "Cetak_real_fisik/index";

$route['cetak_kartu_kendali'] 			= "Cetak_kartukendali/index";
$route['register_pajak'] 					= "Cetak_pajak/register_pajak";	


//pindahbuku

$route['pindahbuku'] 							= "pindah_bank/transout";
// $route['trmpot_pndhbank'] 						= "spp/maintenance";
// $route['trmpot_pndhbank'] 						= "tunai_trmpot/trmpot_pndhbank";
$route['trmpot'] 								= "trmpot/index";

//setor
$route['strpot'] 							= "setor/strpot";


//LPJ
$route['lpj_tu'] 							= "Lpj/tu";
$route['lpjup_skpd'] 					    = "lpj/up_skpd";
$route['lpjup'] 					    	= "lpj/up";
$route['lpjup_unit'] 					   	= "lpj/up_unit";	
$route['validasi_lpj_unit'] 				= "lpj/validasi_up_unit";	

// PANJAR
$route['jawabpanjar'] 							= "tukd/jawabpanjar";
$route['sisa_panjar'] 							= "tukd/sisa_panjar";
$route['panjar'] 								= "tukd/panjar";
$route['tambahpanjar'] 							= "tukd/tambahpanjar";
$route['transaksi_panjar'] 						= "tukd/transaksi_panjar";
$route['pengeluaran_lain'] 						= "tukd/pengeluaran_lain";
//= "tukd/transaksi_panjar";

// CP
$route['reg_cp'] 								= "Cp/reg_cp";

// AKUNTANSI
$route['koreksi_transaksi'] 						= "koreksi/transout_koreksi";
$route['koreksi_transaksi_nominal']					= "koreksi/transout_koreksi2";


//MAPPING
$route['imapping'] 							= "mapping/imapping";
$route['input_indikator'] 					= "mapping/input_indikator";
$route['validasi_indikator'] 				= "mapping/validasi_indikator";
$route['validasi_kegiatan'] 				= "mapping/validasi_kegiatan";


//MASTER
$route['ganti_password'] 					= "master/ganti_pass";
$route['kontrak'] 							= "master/mkontrak";

//dpa sempurna
$route['cetak-dpa-rekap-geser'] 						= "cetak_rka/cetak_rka_rekap_geser/DPA";
$route['cetak-dpa-rekap-geser2'] 						= "cetak_rka/cetak_rka_rekap_geser2/DPA";
$route['preview_rka_skpd_pergeseran/(:any)'] 			= "cetak_rka/preview_rka_skpd_pergeseran/";
$route['cetak-dpa-pendapatan-geser'] 					= "cetak_rka/cetak_rka_pendapatan_geser/DPA";
$route['cetak-dpa-pendapatan-geser2'] 					= "cetak_rka/cetak_rka_pendapatan_geser2/DPA";
$route['preview_pendapatan_pergeseran/(:any)'] 			= "cetak_rka/preview_pendapatan_pergeseran/";//OK
$route['preview_pendapatan_pergeseran2/(:any)'] 			= "cetak_rka/preview_pendapatan_pergeseran2/";//OK
$route['cetak-dpa-belanja-geser'] 						= "cetak_rka/rka_belanja_geser/DPA";//OK
$route['cetak-dpa-belanja-geser2'] 						= "cetak_rka/rka_belanja_geser2/DPA";//OK
$route['preview_belanja_pergeseran/(:any)'] 			= "cetak_rka/preview_belanja_pergeseran/";
$route['preview_belanja_pergeseran2/(:any)'] 			= "cetak_rka/preview_belanja_pergeseran2/";
$route['cetak-dpa-pembiayaan-geser'] 			 		= "cetak_rka/cetak_rka_pembiayaan_pergeseran/DPA";
$route['cetak-dpa-pembiayaan-geser2'] 			 		= "cetak_rka/cetak_rka_pembiayaan_pergeseran2/DPA";
$route['preview_rka_pembiayaan_pergeseran/(:any)'] 		= "cetak_rka/preview_rka_pembiayaan_pergeseran/";

// $route['logout'] 										= "welcome/logout";

$route['index'] = "index";
$route['login'] = "login";
$route['logout'] = "logout";
$route['404_override'] = 'my404';


/* End of file routes.php */
/* Location: ./application/config/routes.php */