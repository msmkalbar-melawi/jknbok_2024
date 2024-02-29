<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class tarikancontroller extends CI_Controller
{

  public $org_keu = "";
  public $skpd_keu = "";

  function __contruct()
  {
    parent::__construct();
  }

  function tariksis()
  {


    ini_set('max_execution_time', 0);

    $base_url = 'https://melawikab.sipd.kemendagri.go.id/daerah/main?';

    $cookie = 'XSRF-TOKEN=eyJpdiI6ImhNbkZic200TlZURnJDcU1kV2lrZlE9PSIsInZhbHVlIjoiRFFTTlJaV0lcL0pGMVNjMVFkXC8ydVcxR0hOd3BJUDdEQkhkWlpjMWowM3ZUNFFnTlRtNXFub2NuS2lIMVZObld1Rmc3emdwXC9tbGd0dFViV1wvTGVRQlwvXC9kTDEwSHUwMmxvaDFQXC8xN1A5a2VlYTNYYkNubDFWNDFjckhKTUwxKzE3IiwibWFjIjoiZWFjN2NmYzJkNWUzNTI1NDRiNTdiNjQyYmM5YTU5NTFlZTRhMjg0MWMyNzEwN2EwYzg2ZmQyZWNmMmZkMTZjYiJ9; laravel_session=eyJpdiI6IlR1MWlqUEtEMXJKUUJXak1CVWtEOHc9PSIsInZhbHVlIjoiQ1ZqMHZSM09MbVwvak5BMzM1cmdYOGxBQmhUTFNoUnJnVnhOemhLVXZ4TEFqdTRyNzA1QlwvaDVGZGplVWtpNmhLQ0VRcW1SaEM5dURNdHVPaG9sSGRNSmRVZWN2a2g1UEpVUHFJSThvTSszY29vemZZZ0xWKzdESU9JWHBFTGVLNyIsIm1hYyI6ImQ3MDg2YzMyMjhjZjJhMzkyZjczMTZkMWI1M2FkYmU4OGU5NDAyNWQwNWRhNjQ2NDBjODIxOTdmYjA1ZjY4NGYifQ==';

    $_token = 'f17bac93f50047965b3629c15270dfd1';

    $v1bnA1m = 'DNVJiPsYbC7XIjYv0gcVzDmVkTtdWUVyONl2Mnc4';
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => "https://melawikab.sipd.kemendagri.go.id/daerah/main?i3bkiHSyB@reYk3XIGzc7Jw0SGVd5/0nIFuNK2UZLKzgx5evRiWZIIBo5S40zF4gDbqjc7HOyo7aiOvDMegVQocvouEG0jniZeHEw9AlzVQK9kQeJlLgwAPlpG4DQRRTprl6yFkgzXiyWgbzk@34rcWNJqRh39n8jxm3lVqKRzrCdwCJhUdeHlKnASfJJGUMNz9K61V/O8HvRfSIzfE8Gg==",
      CURLOPT_COOKIE => $cookie,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS => http_build_query([
        'draw' => 1,
        'start' => 0,
        'length' => -1,
        'search' => [
          'value' => '',
        ],
        '_token' => $_token,
        'v1bnA1m' => $v1bnA1m,
      ]),
    ]);
    $response = curl_exec($ch); // List All Skpd
    $responseObj = json_decode($response);


    curl_close($ch);
    foreach ($responseObj->data as $value) {
      // Insert Data SKPD
      $kd_skpd = $value->kode_skpd;
      $nm_skpd = str_replace($value->kode_skpd . ' ', '', $value->nama_skpd->nama_skpd);
      $kd_urusan = substr($kd_skpd, 0, 4);
      $nilai_kua = $value->batasanpagu;
      $nilai_pagu = $value->nilaipagu;
      // $rincian = $value->rincian;
      $urusan1 = substr($kd_skpd, 0, 4);
      $urusan2 = substr($kd_skpd, 5, 4);
      $urusan3 = substr($kd_skpd, 10, 4);

      // $stmt = $this->db->query("SELECT * FROM ms_skpd WHERE kd_skpd = '$kd_skpd'");
      // $skpd = $stmt->row();
      /*  if ($skpd) {
              // SKPD does exist
              // if ($skpd->status == 1) continue;
            } else {
         
              $stmt = $this->db->query("INSERT ms_skpd_tes (kd_skpd, nm_skpd, kd_urusan, nilai_kua) VALUES 
                  ('$kd_skpd', '$nm_skpd', '$kd_urusan', '$nilai_kua')"
              );
              if ($stmt === false) print_r(sqlsrv_errors());
              $stmt = $this->db->query("SELECT * FROM ms_skpd_tes WHERE kd_skpd = '$kd_skpd'");
              $skpd = $stmt->row();
            }*/
      // End of Insert Data SKPD


      $pageUrl = $value->nama_skpd->sParam;
      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $base_url . $pageUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
      ]);

      $htmlResponse = curl_exec($ch); // Detail Skpd (Halaman List Sub Kegiatan)

      $pattern = '/lru8="([^"]*)/';
      preg_match($pattern, $htmlResponse, $matches);
      $kegiatanUrl = $matches[1];
      curl_close($ch);

      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $kegiatanUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query([
          'draw' => 1,
          'start' => 0,
          'length' => -1,
          'search' => [
            'value' => '',
          ],
          '_token' => $_token,
          'v1bnA1m' => $v1bnA1m,
        ]),
      ]);
      $response = curl_exec($ch);

      $daftarSubKegiatan = json_decode($response);
      curl_close($ch);
      foreach ($daftarSubKegiatan->data as $value) {
        // Insert data trskpd
        $no_idtrskpd = $kd_gabungan = $value->kode_sub_skpd . '.' . $value->kode_sub_giat . '.M';
        $jns_ang = 'M';
        $kd_gabungan = $value->kode_sub_skpd . '.' . $value->kode_sub_giat;
        $kd_bidang_urusan = $value->kode_bidang_urusan;
        $nm_sub_skpd = $value->nama_sub_skpd;
        $kd_sub_skpd = $value->kode_sub_skpd;
        $nm_bidang_urusan = $value->nama_bidang_urusan;
        $kd_program = $value->kode_program;
        $nm_program = $value->nama_program;
        $kd_kegiatan = $value->kode_giat;
        $nm_kegiatan = str_replace($value->kode_giat . ' ', '', $value->nama_giat->nama_giat);
        $kd_sub_kegiatan = $value->kode_sub_giat;
        $nm_sub_kegiatan = str_replace($value->kode_sub_giat . ' ', '', $value->nama_sub_giat->nama_sub_giat);
        $nilai_kua = is_null($value->pagu) ? 'null' : $value->pagu;
        $total = is_null($value->rincian) ? 'null' : $value->rincian;

        // $cek=$this->db->query("SELECT count(kd_skpd) cek from ms_skpd_tes where kd_skpd='$kd_sub_skpd'")->row()->cek;
        // if($cek==0){
        //     $stmt = $this->db->query("INSERT ms_skpd (kd_skpd, nm_skpd ) VALUES ('$kd_sub_skpd', '$nm_sub_skpd')");
        // }



        $stmt = $this->db->query("SELECT * FROM trskpd WHERE kd_skpd = '$kd_sub_skpd' AND kd_sub_kegiatan = '$kd_sub_kegiatan'");
        $trskpd = $stmt->row();
        if ($trskpd) {
          if ($trskpd->status_sub_kegiatan == 1) continue;
        } else {
          $stmt = $this->db->query("INSERT trskpd (id, jns_ang, kd_gabungan, kd_bidang_urusan, kd_skpd, nm_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, nilai_kua, total)
              VALUES ('$no_idtrskpd', '$jns_ang' , '$kd_gabungan', '$kd_bidang_urusan', '$kd_sub_skpd', '$nm_sub_skpd', '$kd_program', '$nm_program', '$kd_kegiatan', '$nm_kegiatan', '$kd_sub_kegiatan', '$nm_sub_kegiatan', $nilai_kua, $total)");
          if ($stmt === false) {
            echo "INSERT trskpd (id, jns_ang, kd_gabungan, kd_bidang_urusan, kd_skpd, nm_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, nilai_kua, total)
              VALUES ('$no_idtrskpd', '$jns_ang', '$kd_gabungan', '$kd_bidang_urusan', '$kd_sub_skpd', '$nm_sub_skpd', '$kd_program', '$nm_program', '$kd_kegiatan', '$nm_kegiatan', '$kd_sub_kegiatan', '$nm_sub_kegiatan', $nilai_kua, $total)" . PHP_EOL;
            print_r(sqlsrv_errors());
          }
          $stmt = $this->db->query("SELECT * FROM trskpd WHERE kd_gabungan= '$kd_gabungan'");
          $trskpd = $stmt->row();
        }
        // End of Insert data trskpd

        // Find detail sub kegiatan url
        $pattern = "/onclick=detilGiat\('([^']*)/";
        preg_match($pattern, $value->action, $matches);
        $detailSubKegiatanUrl = $matches[1];

        $ch = curl_init();
        curl_setopt_array($ch, [
          CURLOPT_URL => $base_url . $detailSubKegiatanUrl,
          CURLOPT_COOKIE => $cookie,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_POSTFIELDS => http_build_query([
            '_token' => $_token,
            'v1bnA1m' => $v1bnA1m,
          ]),
        ]);
        $response = curl_exec($ch);
        $detailSubKegiatan = json_decode($response);
        curl_close($ch);

        // Insert indikator kegiatan, indikator program, dan sub keluaran

        // Find detail rincian url
        $pattern = "/href='main\?([^']*)/";
        preg_match($pattern, $value->action, $matches);
        $detailRincianUrl = $matches[1];

        $ch = curl_init();
        curl_setopt_array($ch, [
          CURLOPT_URL => $base_url . $detailRincianUrl,
          CURLOPT_COOKIE => $cookie,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_RETURNTRANSFER => true,
        ]);
        $response = curl_exec($ch);
        $detailRincianHtmlResponse = $response;
        curl_close($ch);

        /*1*/
        if (!empty($detailSubKegiatan->dataBl)) {

          foreach ($detailSubKegiatan->dataBl as $bl) {
            $this->db->query("INSERT INTO dataBl  (kd_gabungan,kd_skpd,kd_program,kd_kegiatan,kd_sub_kegiatan,sasaran,satuan,waktu_awal,waktu_akhir,outputsubgiat)
                      values ('$kd_gabungan','$kd_sub_skpd','{$bl->kode_program}','{$bl->kode_giat}','{$bl->kode_sub_giat}','{$bl->sasaran}','{$bl->satuan}','{$bl->waktu_awal}','{$bl->waktu_akhir}','{$bl->output_sub_giat}')
                      ");
          }
        }


        /*2*/
        if (!empty($detailSubKegiatan->dataCapaian)) {
          foreach ($detailSubKegiatan->dataCapaian as $capaian) {

            $this->db->query("INSERT INTO dataCapaian (kd_gabungan,kd_skpd,kd_sub_kegiatan,capaianteks,targetcapaian,satuancapaian,targetcapaianteks)
                    values ('$kd_gabungan','$kd_sub_skpd','$kd_sub_kegiatan','{$capaian->capaianteks}','{$capaian->targetcapaian}','{$capaian->satuancapaian}','{$capaian->targetcapaianteks}')");
          }
        }



        /*3*/
        if (!empty($detailSubKegiatan->dataDana)) {
          foreach ($detailSubKegiatan->dataDana as $dana) {
            $this->db->query("INSERT INTO dataDana (kd_gabungan,kd_skpd,kd_sub_kegiatan,kodedana,namadana,pagudana)
                    values ('$kd_gabungan','$kd_sub_skpd','{$kd_sub_kegiatan}','{$dana->kodedana}','{$dana->namadana}','{$dana->pagudana}')");
          }
        }

        /*4*/


        if (!empty($detailSubKegiatan->dataHasil)) {
          foreach ($detailSubKegiatan->dataHasil as $hasil) {
            $this->db->query("INSERT INTO dataHasil (kd_gabungan, kd_skpd, kd_sub_kegiatan, hasilteks,targethasil,satuanhasil,targethasilteks)
                    values ('$kd_gabungan','$kd_sub_skpd','$kd_sub_kegiatan','{$hasil->hasilteks}','{$hasil->targethasil}','{$hasil->satuanhasil}','{$hasil->targethasilteks}')");
          }
        }

        /*5*/
        if (!empty($detailSubKegiatan->dataLokout)) {
          foreach ($detailSubKegiatan->dataLokout as $lokout) {
            $this->db->query("INSERT INTO dataLokout (kd_gabungan,kd_skpd,kd_sub_kegiatan,camatteks,daerahteks,lurahteks) values 
                    ('$kd_gabungan','$kd_sub_skpd','$kd_sub_kegiatan','{$lokout->camatteks}','{$lokout->daerahteks}','{$lokout->lurahteks}')");
          }
        }

        /*6*/
        if (!empty($detailSubKegiatan->dataOutput)) {
          foreach ($detailSubKegiatan->dataOutput as $output) {
            $this->db->query("INSERT INTO dataOutput (kd_gabungan,kd_skpd,kd_sub_kegiatan,outputteks,satuanoutput,targetoutput,targetoutputteks)
                    values ('$kd_gabungan','$kd_sub_skpd','$kd_sub_kegiatan','{$output->outputteks}','{$output->targetoutput}','{$output->satuanoutput}','{$output->targetoutputteks}')");
          }
        }

        /*7*/
        if (!empty($detailSubKegiatan->dataOutputGiat)) {
          foreach ($detailSubKegiatan->dataOutputGiat as $output) {
            $this->db->query("INSERT INTO dataOutputGiat (kd_gabungan,kd_skpd,kd_sub_kegiatan,outputteks,satuanoutput,targetoutput,targetoutputteks)
                    values ('$kd_gabungan','$kd_sub_skpd','$kd_sub_kegiatan','{$output->outputteks}','{$output->targetoutput}','{$output->satuanoutput}','{$output->targetoutputteks}')");
          }
        }


        /*8*/
        if (!empty($detailSubKegiatan->dataTag)) {

          foreach ($detailSubKegiatan->dataTag as $Tag) {
            $this->db->query("INSERT INTO dataTag (kd_gabungan,kd_skpd,kd_sub_kegiatan,namalabel) values 
                    ('$kd_gabungan','$kd_sub_skpd','$kd_sub_kegiatan','{$Tag->namalabel}')");
          }
        }

        /*end indikator*/


        // Find rek belanja url
        $pattern = '/lru18="([^"]*)/';
        preg_match($pattern, $detailRincianHtmlResponse, $matches);
        $rekBelanjaUrl = $matches[1];

        $ch = curl_init();
        curl_setopt_array($ch, [
          CURLOPT_URL => $rekBelanjaUrl,
          CURLOPT_COOKIE => $cookie,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_POSTFIELDS => http_build_query([
            'draw' => 1,
            'start' => 0,
            'length' => -1,
            'search' => [
              'value' => '',
            ],
            '_token' => $_token,
            'v1bnA1m' => $v1bnA1m,
          ]),
        ]);
        $response = curl_exec($ch);
        $rekBelanja = json_decode($response);
        curl_close($ch);

        foreach ($rekBelanja->data as $value) {
          // Insert into trdrka
          $kd_rek6 = str_replace('.', '', $value->kode_akun);
          $id = $kd_sub_skpd . '.' . $trskpd->kd_sub_kegiatan . '.' . $kd_rek6 . '.M';
          $jns_ang = 'M';
          $no_trdrka = $kd_sub_skpd . '.' . $trskpd->kd_sub_kegiatan . '.' . $kd_rek6;
          $nm_rek6 = str_replace($value->kode_akun . ' ', '', $value->nama_akun);
          $nilai = $value->nilai;
          $stmt = $this->db->query("SELECT * FROM trdrka WHERE kd_skpd = '$kd_sub_skpd' AND kd_sub_kegiatan = '{$trskpd->kd_sub_kegiatan}' AND kd_rek6 = '$kd_rek6'");
          $trdrka = $stmt->row();
          if ($trdrka) {
          } else {
            $stmt = $this->db->query("INSERT trdrka (id, jns_ang, no_trdrka, kd_skpd, nm_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai)
            VALUES ('$id', '$jns_ang', '$no_trdrka', '$kd_sub_skpd', '$nm_sub_skpd', '{$trskpd->kd_sub_kegiatan}', '{$trskpd->nm_sub_kegiatan}', '$kd_rek6', '$nm_rek6', $nilai)");
            if ($stmt === false) {
              echo "INSERT trdrka (no_trdrka, kd_skpd, nm_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai)
              VALUES ('$id','$jns_ang','$no_trdrka', '$kd_sub_skpd', '$nm_sub_skpd', '{$trskpd->kd_sub_kegiatan}', '{$trskpd->nm_sub_kegiatan}', '$kd_rek6', '$nm_rek6', $nilai)" . PHP_EOL;
              print_r(sqlsrv_errors());
            }
          }
        }

        $pattern = '/lru1="([^"]*)/';
        preg_match($pattern, $detailRincianHtmlResponse, $matches);
        $uraianRekBelanjaUrl = $matches[1];

        $ch = curl_init();
        curl_setopt_array($ch, [
          CURLOPT_URL => $uraianRekBelanjaUrl,
          CURLOPT_COOKIE => $cookie,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_POSTFIELDS => http_build_query([
            'draw' => 1,
            'start' => 0,
            'length' => -1,
            'search' => [
              'value' => '',
            ],
            '_token' => $_token,
            'v1bnA1m' => $v1bnA1m,
          ]),
        ]);
        $response = curl_exec($ch);
        $uraianRekBelanja = json_decode($response);
        curl_close($ch);

        $this->db->query("DELETE trdpo WHERE no_trdrka LIKE '{$trskpd->kd_gabungan}%'");


        $header = null;
        $subheader = null;
        $cek_trdrka = null;
        foreach ($uraianRekBelanja->data as $value) {
          $kd_rek6 = str_replace('.', '', $value->kode_akun);
          $no_trdrka = $trskpd->kd_gabungan . '.' . $kd_rek6;
          $id_standard_harga = is_null($value->id_standar_harga) ? 'null' : $value->id_standar_harga;
          $kd_barang = $value->kode_standar_harga;
          $kd_sub_skpd = $value->kode_sub_skpd;
          $kd_sub_kegiatan = $value->kode_sub_giat;
          $uraian = $value->nama_standar_harga->nama_komponen;
          $spesifikasi = $value->nama_standar_harga->spek_komponen;
          $satuan = $value->satuan;
          $volume = is_null($value->volume) ? 'null' : $value->volume;
          $koefisien = $value->koefisien;
          $subs_bl_teks = $value->subs_bl_teks;
          $ket_bl_teks = $value->ket_bl_teks;
          $volume1 = is_null($value->vol_1) ? 'null' : $value->vol_1;
          $volume2 = is_null($value->vol_2) ? 'null' : $value->vol_2;
          $volume3 = is_null($value->vol_3) ? 'null' : $value->vol_3;
          $volume4 = is_null($value->vol_4) ? 'null' : $value->vol_4;
          $satuan1 = $value->sat_1;
          $satuan2 = $value->sat_2;
          $satuan3 = $value->sat_3;
          $satuan4 = $value->sat_4;
          $harga = is_null($value->harga_satuan) ? 'null' : $value->harga_satuan;
          $ppn = is_null($value->pajak) ? 'null' : $value->pajak;
          $total = is_null($value->rincian) ? 'null' : $value->rincian;
          $cek_trdrka = $no_trdrka;
          $jns_ang = 'M';

          /*header*/
          // if($no_trdrka==$cek_trdrka and $header!=$subs_bl_teks){
          //     $stmt = $this->db->query(
          //       "INSERT trdpo_tes (kd_skpd,kd_sub_kegiatan,header, no_trdrka, kd_rek6,  uraian, subs_bl_teks) VALUES (
          //       '$kd_sub_skpd', '$kd_sub_kegiatan',1, '$no_trdrka', '$kd_rek6',  '$subs_bl_teks', '$subs_bl_teks')");
          //     $header=$subs_bl_teks;

          // }
          /*subheader*/
          // if($no_trdrka==$cek_trdrka and $subheader!=$ket_bl_teks){
          //     $stmt = $this->db->query(
          //       "INSERT trdpo_tes (kd_skpd,kd_sub_kegiatan,header, no_trdrka, kd_rek6,  uraian, subs_bl_teks,ket_bl_teks,kode) VALUES (
          //       '$kd_sub_skpd', '$kd_sub_kegiatan',1, '$no_trdrka', '$kd_rek6',  '$ket_bl_teks', '$subs_bl_teks','$ket_bl_teks',2)");
          //     $subheader=$ket_bl_teks;
          // }

          $cek_trdrka = $no_trdrka;


          $stmt = $this->db->query(
            "INSERT trdpo (jns_ang, kd_skpd, kd_sub_kegiatan, no_trdrka, kd_rek6, kd_barang, uraian, spesifikasi, satuan, volume, koefisien, volume1, volume2, volume3, volume4,
              satuan1, satuan2, satuan3, satuan4, harga, total, pajak, id_standar_harga,sub_header,header) 
              VALUES ('$jns_ang', '$kd_sub_skpd', '$kd_sub_kegiatan', '$no_trdrka', '$kd_rek6', '$kd_barang', '$uraian', '$spesifikasi', '$satuan', $volume, '$koefisien',
              $volume1, $volume2, $volume3, $volume4, '$satuan1', '$satuan2', '$satuan3', '$satuan4', $harga, $total, $ppn, $id_standard_harga, '$ket_bl_teks', '$subs_bl_teks')"
          );
          if ($stmt === false) {
            echo "INSERT trdpo (jns_ang, kd_skpd, kd_sub_kegiatan, no_trdrka, kd_rek6, kd_barang, uraian, spesifikasi, satuan, volume, koefisien, volume1, volume2, volume3, volume4,
              satuan1, satuan2, satuan3, satuan4, harga, total, pajak, id_standar_harga,sub_header,header) VALUES ('$jns_ang', '$kd_sub_skpd', '$kd_sub_kegiatan', '$no_trdrka', '$kd_rek6', '$kd_barang', '$uraian', '$spesifikasi', '$satuan', $volume, '$koefisien',
              $volume1, $volume2, $volume3, $volume4, '$satuan1', '$satuan2', '$satuan3', '$satuan4', $harga, $total, $ppn, $id_standard_harga,'$ket_bl_teks', '$subs_bl_teks')" . PHP_EOL;
            print_r(sqlsrv_errors());
          }
          $last_kd_rek6 = $value->kode_akun;
          $last_header = $value->subs_bl_teks;
          $last_subheader = $value->ket_bl_teks;
        }/* end foreach ($uraianRekBelanja->data as $value)*/

        // Update Status trskpd
        $stmt = $this->db->query("SELECT SUM(total) AS total FROM trdpo WHERE no_trdrka LIKE '{$trskpd->kd_gabungan}%'");
        $row = $stmt->row();
        if ($row->total == $trskpd->total) {
          $stmt = $this->db->query("UPDATE trskpd SET status_sub_kegiatan = 1 WHERE kd_gabungan = '{$trskpd->kd_gabungan}'");
          if ($stmt === false) {
            echo "UPDATE trskpd SET status_sub_kegiatan = 1 WHERE kd_gabungan = '{$trskpd->kd_gabungan}'" . PHP_EOL;
            print_r(sqlsrv_errors());
          }
        }
      }
    } /*end looping skpd*/
  } /*end tarik sis*/



  // function sampah(){
  //        if ($last_kd_rek6 == $value->kode_akun) {
  //         if ($last_header != $value->subs_bl_teks) {
  //           // Insert Header
  //           $stmt = $this->db->query("INSERT trdpo (header, no_trdrka, kd_rek6, uraian) VALUES (1, '$no_trdrka', '$kd_rek6', '{$value->subs_bl_teks}'); SELECT SCOPE_IDENTITY()");
  //           if ($stmt === false) print_r(sqlsrv_errors());

  //           /*$this->db->next_result($stmt);
  //           $this->db->affected_rows($stmt);*/
  //           $last_header_id = $this->db->get_field($stmt, 0);
  //         }
  //         if ($last_subheader != $value->ket_bl_teks) {
  //           // Insert Sub Header
  //           $stmt = $this->db->query("INSERT trdpo (header, no_trdrka, kd_rek6, uraian, header_id) VALUES (1, '$no_trdrka', '$kd_rek6', '{$value->ket_bl_teks}', $last_header_id); SELECT SCOPE_IDENTITY()");
  //           if ($stmt === false) print_r(sqlsrv_errors());
  //           /*$this->db->next_result($stmt);
  //           $this->db->affected_rows($stmt);*/
  //           $last_subheader_id = $this->db->get_field($stmt, 0);
  //         }
  //       } else {
  //         if ($last_header != $value->subs_bl_teks) {
  //           // Insert Header
  //           $stmt = $this->db->query("INSERT trdpo (header, no_trdrka, kd_rek6, uraian) VALUES (1, '$no_trdrka', '$kd_rek6', '{$value->subs_bl_teks}'); SELECT SCOPE_IDENTITY()");
  //           if ($stmt === false) print_r(sqlsrv_errors());
  //           /*$this->db->next_result($stmt);
  //           $this->db->affected_rows($stmt);*/
  //           $last_header_id = $this->db->get_field($stmt, 0);
  //         } else {
  //           $stmt = $this->db->query("INSERT trdpo (header, no_trdrka, kd_rek6, uraian) VALUES (1, '$no_trdrka', '$kd_rek6', '[#]'); SELECT SCOPE_IDENTITY()");
  //           if ($stmt === false) print_r(sqlsrv_errors());
  //           /*$this->db->next_result($stmt);
  //           $this->db->affected_rows($stmt);*/
  //           $last_header_id = $this->db->get_field($stmt, 0);
  //         }
  //         if ($last_header == $value->subs_bl_teks && $last_subheader == $value->ket_bl_teks) {
  //           // Insert Sub Header
  //           $stmt = $this->db->query("INSERT trdpo (header, no_trdrka, kd_rek6, uraian, header_id) VALUES (1, '$no_trdrka', '$kd_rek6', '[-]', $last_header_id); SELECT SCOPE_IDENTITY()");
  //           if ($stmt === false) print_r(sqlsrv_errors());
  //           /*$this->db->next_result($stmt);
  //           $this->db->affected_rows($stmt);*/
  //           $last_subheader_id = $this->db->get_field($stmt, 0);
  //         } else {
  //           $stmt = $this->db->query("INSERT trdpo (header, no_trdrka, kd_rek6, uraian, header_id) VALUES (1, '$no_trdrka', '$kd_rek6', '{$value->ket_bl_teks}', $last_header_id); SELECT SCOPE_IDENTITY()");
  //           if ($stmt === false) print_r(sqlsrv_errors());
  //           /*$this->db->next_result($stmt);
  //           $this->db->affected_rows($stmt);*/
  //           $last_subheader_id = $this->db->get_field($stmt, 0);
  //         }
  //       } /*end if($last_kd_rek6 == $value->kode_akun)*/
  // }





  function pendapatan()
  {

    ini_set('max_execution_time', 0);

    $base_url = 'https://melawikab.sipd.kemendagri.go.id/daerah/main?';

    $cookie = 'XSRF-TOKEN=eyJpdiI6IlwvRERsZ1hLTjFGd2tsMUpjajZIaWNRPT0iLCJ2YWx1ZSI6ImFxdDMwc3hBWXVTUlwvbUc1VHlSVjNzSHA0cWk5R2M1MlwvRDdXeXFOaWhwYUhETTUxOXZEeFVzTU84UHd4ODQwS0x6Tk1NZ3cwYUtcL2gzSmdWUWdZRFRHNCtoVk5PcDdickVLYmFNVitKQk5cL1BoMWd4UkJ4ck9Sb3JyMFZZUnM2WCIsIm1hYyI6IjgxY2E4MTkxNGNkYTBiNDNkMGU3OTlkZmYwMDIxNzY4Njk2MWE3NmJiMzQ3MWY3ZmVjNDBlMzkzMTA3NjYwOTkifQ==; laravel_session=eyJpdiI6ImlTQmU3XC9JTE1ocDV3Q3o1Skt1NndBPT0iLCJ2YWx1ZSI6IkEzRElPOFZxbG5XXC9KQ1lQNE1DdTFXRnVabkkyVFhVTFJOSGVwOVpMUHFscm1HM05sQ2VXcVg4c0pqanh2aVRZT2lVRk4wbVg2UEtOQ29VeWVWRTJEbkpqRTBFd1NaT1hXRlBRU0RLQWo1TzFKeUVZdmRDbjRoOWd4NlhQQUtJMiIsIm1hYyI6IjM1YzBjYjIwMGVkNTkwNDk0ZTFlMDliZGIwMGZmODczMTQzMmYyMDgxM2Y4YWIwNzg1YmZkMzE2YjRkM2YxNDQifQ==';
    $_token = 'b46d0bc85fd440567da83589f965e85b';

    $v1bnA1m = 'DNVJiPsYbC7XIjYv0gcVzDmVkTtdWUVyONl2Mnc4';
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => "https://melawikab.sipd.kemendagri.go.id/daerah/main?C9N6TgGIPkfSpzasBQYtQ5j/czhl80G8gL@hlyYlGdu9d9OUHnsPa/zWmLuq@/xwb4fsK5teNNToTIVHm@xAHVN1LcbvsF2PaZ2L53L/Y8hXblOr9SvqlurIySQwhrLTX3t1K2xCk1XS8efaRYzIuw5Nl4VwRsbWCd2HuvtLE5lYYge6Vh8zwRCJ9/sN05fM0FHbT8HwzO7iXG4YTNwVog==",
      CURLOPT_COOKIE => $cookie,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS => http_build_query([
        'draw' => 1,
        'start' => 0,
        'length' => -1,
        'search' => [
          'value' => '',
        ],
        '_token' => $_token,
        'v1bnA1m' => $v1bnA1m,
      ]),
    ]);
    $response = curl_exec($ch); // List All Skpd
    $responseObj = json_decode($response);



    curl_close($ch);
    foreach ($responseObj->data as $value) {
      // Insert Data SKPD
      $kd_skpd = $value->kode_skpd;
      $nm_skpd = str_replace($value->kode_skpd . ' ', '', $value->nama_skpd->nama_skpd);
      $nourusan = substr($kd_skpd, 0, 4);

      $stmt = $this->db->query("SELECT * FROM ms_skpd WHERE kd_skpd = '$kd_skpd'");
      $skpd = $stmt->row();
      // $stmt = $this->db->query("INSERT ms_skpd (kd_skpd,nm_skpd, kd_urusan)
      // VALUES ('$kd_skpd','$nm_skpd','$nourusan')");


      $pageUrl = $value->nama_skpd->sParam;
      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $base_url . $pageUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
      ]);

      $htmlResponse = curl_exec($ch); // Detail Skpd (Halaman List Sub Kegiatan)

      $pattern = '/lru2="([^"]*)/';
      preg_match($pattern, $htmlResponse, $matches);
      $kegiatanUrl = $matches[1];
      curl_close($ch);

      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $kegiatanUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query([
          'draw' => 1,
          'start' => 0,
          'length' => -1,
          'search' => [
            'value' => '',
          ],
          '_token' => $_token,
          'v1bnA1m' => $v1bnA1m,
        ]),
      ]);
      $response = curl_exec($ch);

      $daftarSubKegiatan = json_decode($response);

      curl_close($ch);

      if (!empty($daftarSubKegiatan->data)) { /*jika ada isinya gasskeen*/



        foreach ($daftarSubKegiatan->data as $value) {

          $no_idtrskpd = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.00.04.M';
          $kd_rek6 = str_replace('.', '', $value->kode_akun);
          $jns_ang = 'M';
          $no_trdrka   = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.00.04.' . $kd_rek6;
          $no_id   = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.00.04.' . $kd_rek6 . '.M';
          $kd_gabungan = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.00.04';
          $kd_sub_kegiatan = substr($kd_skpd, 0, 4) . '.00.0.00.04';
          $kd_kegiatan = substr($kd_skpd, 0, 4) . '.00.0.00';
          $kd_program  = substr($kd_skpd, 0, 4) . '.00';
          $kd_bidang_urusan = substr($kd_skpd, 0, 4);
          $nm_rek6 = $value->nama_akun;
          $nilai = is_null($value->total) ? 'null' : $value->total;
          $header = $value->uraian;
          $subheader = $value->keterangan;
          $uraian = $value->nama_akun;





          $stmt = $this->db->query("SELECT count(*) ada FROM trskpd WHERE kd_gabungan='$kd_gabungan'");
          $trskpd = $stmt->row();

          if ($trskpd->ada == 0) {
            $stmt = $this->db->query("INSERT trskpd (id, jns_ang,kd_gabungan, kd_bidang_urusan, kd_skpd, nm_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan)
                            VALUES ('$no_idtrskpd', '$jns_ang', '$kd_gabungan', '$kd_bidang_urusan', '$kd_skpd', '$nm_skpd', '$kd_program', 'Non Program', '$kd_kegiatan', 'Pendapatan', '$kd_sub_kegiatan', 'Pendapatan')");
          }

          $this->db->query("INSERT INTO trdrka (id, no_trdrka, jns_ang, kd_skpd, nm_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6,nilai) 
                    values ('$no_id','$no_trdrka','$jns_ang','$kd_skpd','$nm_skpd','$kd_sub_kegiatan','Pendapatan','$kd_rek6','$nm_rek6','$nilai')");

          // /*header*/
          // $this->db->query("INSERT INTO trdpo (no_trdrka, header, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,header)
          //     values ('$no_trdrka',1,'$kd_skpd','$kd_sub_kegiatan','$kd_rek6',':: $header',':: $header')");

          // /*subheader*/
          // $this->db->query("INSERT INTO trdpo (no_trdrka, header, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,ket_bl_teks,header,kode)
          //     values ('$no_trdrka',1,'$kd_skpd','$kd_sub_kegiatan','$kd_rek6','::: $subheader','::: $subheader',':: $header',2)");

          /*rincian*/
          $this->db->query("INSERT INTO trdpo (jns_ang,no_trdrka, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,sub_header,header,total, volume1,harga)
                        values ('$jns_ang','$no_trdrka','$kd_skpd','$kd_sub_kegiatan','$kd_rek6','$uraian','::: $subheader','$header','$nilai',1,'$nilai')");
        } /*end foreach($daftarSubKegiatan->data as  $value)*/
      }/* end !empty($daftarSubKegiatan)->data*/
    } /*end looping skpd*/
  } /*end pendapatan*/




  function biayamasuk()
  {

    ini_set('max_execution_time', 0);

    $base_url = 'https://melawikab.sipd.kemendagri.go.id/daerah/main?';

    $cookie = 'XSRF-TOKEN=eyJpdiI6IlN6SThtaUo0TjNETU93WitUN3g2UVE9PSIsInZhbHVlIjoiNnY1NTN3bGYzbTFiRjVsbzYxMlhEZlM0eWVKbUdNN3dHU2RcL1wvNXBiXC80bDBEaWY5VW5jSUpMMjhNeVRlamJKK1lZN1lYdGVoZzJmQ2RZRUZHZGJ2QTFhZWZjakh0K3M3bUdsQ2VmZTBvaW9kQUQwbXMrU3lDejdNZ0F5OE5PcGIiLCJtYWMiOiI0ZDg5Y2JkNmJlMmM5YWQ0OTliYWQ4ZDEwMTkyOGRkZTdiOGMxZTJmODA0NjhkNDdiZjU2OWNhYzA0ODg2OGVjIn0=; laravel_session=eyJpdiI6ImFYcU9FdkpNeEQrMmNNbDh3dUI1b2c9PSIsInZhbHVlIjoiZjBzZzM1ZjVRRXNZelZDeWxXTlhHZGhySFU0UmNrWm1LbW1jRGVsMmxCUDhqWXdTMk1zVDBZbHVpc1hIc3dwN21jS2Y4Mk9Wa1pVbDh2QW5rUjZDelp2WFBXOGdpeXNFVVhaM09EcW43U2dpWVNpUm1EanR0b0dqWnVDT2pOc2YiLCJtYWMiOiJlMmU2YmI0OWE4MjM4NzQ4MGY2NGVmMjcyYTRmYTU3YTFjMjEyZWQwNTliMTAxOTkxM2ExNmQ3MzNiNjFmNjYyIn0=';

    $_token = 'b390221fbc13fa628218bd25609bbcf2';

    $v1bnA1m = 'DNVJiPsYbC7XIjYv0gcVzDmVkTtdWUVyONl2Mnc4';
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => "https://melawikab.sipd.kemendagri.go.id/daerah/main?LHTdqTJxjYNp2wuHJMRikgC332QhT0qpYy4Oh52/Mltc@nCn/MZ9wDqwdxFIEXVJbwjhF6nKRkw42H1OQRHDSQpC4Ldj3VdKwOS3X/Ojt0SV@BESfHTvzZ2p2wMo3Jj8fqkW4QNXZn6cYq3UUHY94cZiAiDxuR27U2axa0vLkJSvZ/DjaDF9Yda8f9HJpZKRtvqdtvzQiSaTVyIyCkC0VEUu33EZMQi7KYAHdqc85e0=",
      CURLOPT_COOKIE => $cookie,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS => http_build_query([
        'draw' => 1,
        'start' => 0,
        'length' => -1,
        'search' => [
          'value' => '',
        ],
        '_token' => $_token,
        'v1bnA1m' => $v1bnA1m,
      ]),
    ]);
    $response = curl_exec($ch); // List All Skpd
    $responseObj = json_decode($response);



    curl_close($ch);
    foreach ($responseObj->data as $value) {
      // Insert Data SKPD
      $kd_skpd = $value->kode_skpd;
      $nm_skpd = str_replace($value->kode_skpd . ' ', '', $value->nama_skpd->nama_skpd);
      $nourusan = substr($kd_skpd, 0, 4);

      $stmt = $this->db->query("SELECT * FROM ms_skpd WHERE kd_skpd = '$kd_skpd'");
      $skpd = $stmt->row();
      //   $stmt = $this->db->query("INSERT ms_skpd_tes (kd_skpd,nm_skpd, kd_urusan)
      //   VALUES ('$kd_skpd','$nm_skpd','$nourusan')");



      $pageUrl = $value->nama_skpd->sParam;
      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $base_url . $pageUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
      ]);

      $htmlResponse = curl_exec($ch); // Detail Skpd (Halaman List Sub Kegiatan)

      $pattern = '/lru2="([^"]*)/';
      preg_match($pattern, $htmlResponse, $matches);
      $kegiatanUrl = $matches[1];
      curl_close($ch);

      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $kegiatanUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query([
          'draw' => 1,
          'start' => 0,
          'length' => -1,
          'search' => [
            'value' => '',
          ],
          '_token' => $_token,
          'v1bnA1m' => $v1bnA1m,
        ]),
      ]);
      $response = curl_exec($ch);

      $daftarSubKegiatan = json_decode($response);

      curl_close($ch);

      if (!empty($daftarSubKegiatan->data)) { /*jika ada isinya gasskeen*/



        foreach ($daftarSubKegiatan->data as $value) {

          $no_idtrskpd = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06.M';
          $jns_ang = 'M';
          $kd_rek6 = str_replace('.', '', $value->kode_akun);
          $no_trdrka   = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06.' . $kd_rek6;
          $no_id   = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06.' . $kd_rek6 . '.M';
          $kd_gabungan = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06';
          $kd_sub_kegiatan = substr($kd_skpd, 0, 4) . '.00.0.06.06';
          $kd_kegiatan = substr($kd_skpd, 0, 4) . '.00.0.00';
          $kd_program  = substr($kd_skpd, 0, 4) . '.00';
          $kd_bidang_urusan = substr($kd_skpd, 0, 4);
          $nm_rek6 = $value->nama_akun;
          $nilai = is_null($value->total) ? 'null' : $value->total;
          $header = $value->uraian;
          $subheader = $value->keterangan;
          $uraian = $value->nama_akun;



          $stmt = $this->db->query("SELECT count(*) ada FROM trskpd WHERE kd_gabungan='$kd_gabungan'");
          $trskpd = $stmt->row();

          if ($trskpd->ada == 0) {
            $stmt = $this->db->query("INSERT trskpd (id, jns_ang,kd_gabungan, kd_bidang_urusan, kd_skpd, nm_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan)
                        VALUES ('$no_idtrskpd','$jns_ang','$kd_gabungan', '$kd_bidang_urusan', '$kd_skpd', '$nm_skpd', '$kd_program', 'Non Program', '$kd_kegiatan', 'Pendapatan', '$kd_sub_kegiatan', 'Pendapatan')");
          }
          $this->db->query("INSERT INTO trdrka (id,no_trdrka, jns_ang, kd_skpd, nm_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6,nilai) 
                values ('$no_id','$no_trdrka','$jns_ang','$kd_skpd','$nm_skpd','$kd_sub_kegiatan','Pendapatan','$kd_rek6','$nm_rek6','$nilai')");

          // /*header*/
          // $this->db->query("INSERT INTO trdpo (no_trdrka, header, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,subs_bl_teks)
          //     values ('$no_trdrka',1,'$kd_skpd','$kd_sub_kegiatan','$kd_rek6',':: $header',':: $header')");

          // /*subheader*/
          // $this->db->query("INSERT INTO trdpo (no_trdrka, header, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,ket_bl_teks,subs_bl_teks,kode)
          //     values ('$no_trdrka',1,'$kd_skpd','$kd_sub_kegiatan','$kd_rek6','::: $subheader','::: $subheader',':: $header',2)");

          /*rincian*/
          $this->db->query("INSERT INTO trdpo (jns_ang,no_trdrka, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,sub_header,header,total, volume1,harga)
                        values ('$jns_ang', '$no_trdrka','$kd_skpd','$kd_sub_kegiatan','$kd_rek6','$uraian','::: $subheader','$header','$nilai',1,'$nilai')");
        } /*end foreach($daftarSubKegiatan->data as  $value)*/
      }/* end !empty($daftarSubKegiatan)->data*/
    } /*end looping skpd*/
  } /*end pendapatan*/




  function biayakeluar()
  {

    ini_set('max_execution_time', 0);

    $base_url = 'https://melawikab.sipd.kemendagri.go.id/daerah/main?';

    $cookie = 'XSRF-TOKEN=eyJpdiI6Ijg5TnNkNEFpcEpFRGFETnluVEh3bWc9PSIsInZhbHVlIjoiMldtcldkbnd0VnJiaWxcL0d6RDZWbXUzMUFcL2s2dFFsTlRmeHpDVENROU5ReDdweDhBSUVMK2VzUEIzeG5nYkpjMDJVZ3VqelwvMlFOdzNXd1kzVUxrbExUVzk5QVkybzhtNUhVZDlcL1MxdG5QU3hUNVZad2Z2N05JbXB4WDFwY2E4IiwibWFjIjoiMWUxNjUwNmQyZWEzY2I3MTdiZmFkODlkN2JjNzhkMDJlZmJjNWUxMmU2YTJjZjZhNmU1MDVkNjdiN2JhMTBhZSJ9; laravel_session=eyJpdiI6Ing5QlNIV3ZZeFRnR0ZIT2MxNXQ1d3c9PSIsInZhbHVlIjoibGZwZGphK0RoXC9xOWRHUkl6bzl1RjdFamdHVkE5ejRIYWVEYUdQZUcyUEphaEF3YlV3enoxOStlK3h4NGlWWThKSmRPT2JHSmtZU1VpOExDV1JmWjg1bzFEOXNib3czUnh3VVhGWGZqeDZTNmo5NUltOFNQZXNGNFwvWUc3OUMxbiIsIm1hYyI6ImU2NmZkZWU2NGU3Mjc0YmQ4NDg0OGJmOTdkYzkwMzM4NDBhMDk5YzAwY2I0MTM0NzI1NDhlMmFkMGRkMzBjYWIifQ==';

    $_token = '55de16e255493bfe6a2d15395f85a2ec';

    $v1bnA1m = 'DNVJiPsYbC7XIjYv0gcVzDmVkTtdWUVyONl2Mnc4';
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => "https://melawikab.sipd.kemendagri.go.id/daerah/main?HDEmFzGKGWPmWGWmsnb1bhB5VPfjJfiap@GfWpCMEbK7YCKcCd8AGpk2EuT5KX9AZM0OAMymPOQJvXi3Us0R4RaGoUsFfcvXTihdmeT0KtjnOEzAAaPozT5yTXmnY2iTD8TV0RkMDXcvSf@XsFsXPhj2PuvuwUM03ALSQxKQHHCbL0tay0orpcktRhi/vQJi8lHb84HEdeEbHBjdPw/xiODTsnK@jQlCKoupmDid5LI=",
      CURLOPT_COOKIE => $cookie,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS => http_build_query([
        'draw' => 1,
        'start' => 0,
        'length' => -1,
        'search' => [
          'value' => '',
        ],
        '_token' => $_token,
        'v1bnA1m' => $v1bnA1m,
      ]),
    ]);
    $response = curl_exec($ch); // List All Skpd
    $responseObj = json_decode($response);



    curl_close($ch);
    foreach ($responseObj->data as $value) {
      // Insert Data SKPD
      $kd_skpd = $value->kode_skpd;
      $nm_skpd = str_replace($value->kode_skpd . ' ', '', $value->nama_skpd->nama_skpd);

      $stmt = $this->db->query("SELECT * FROM ms_skpd WHERE kd_skpd = '$kd_skpd'");
      $skpd = $stmt->row();



      $pageUrl = $value->nama_skpd->sParam;
      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $base_url . $pageUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
      ]);

      $htmlResponse = curl_exec($ch); // Detail Skpd (Halaman List Sub Kegiatan)

      $pattern = '/lru2="([^"]*)/';
      preg_match($pattern, $htmlResponse, $matches);
      $kegiatanUrl = $matches[1];
      curl_close($ch);

      $ch = curl_init();
      curl_setopt_array($ch, [
        CURLOPT_URL => $kegiatanUrl,
        CURLOPT_COOKIE => $cookie,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => http_build_query([
          'draw' => 1,
          'start' => 0,
          'length' => -1,
          'search' => [
            'value' => '',
          ],
          '_token' => $_token,
          'v1bnA1m' => $v1bnA1m,
        ]),
      ]);
      $response = curl_exec($ch);

      $daftarSubKegiatan = json_decode($response);

      curl_close($ch);

      if (!empty($daftarSubKegiatan->data)) { /*jika ada isinya gasskeen*/



        foreach ($daftarSubKegiatan->data as $value) {

          $no_idtrskpd = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06.M';
          $kd_rek6 = str_replace('.', '', $value->kode_akun);
          $jns_ang = 'M';
          $no_id   = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06.' . $kd_rek6 . '.M';
          $no_trdrka   = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06.' . $kd_rek6;
          $kd_gabungan = $kd_skpd . '.' . substr($kd_skpd, 0, 4) . '.00.0.06.06';
          $kd_sub_kegiatan = substr($kd_skpd, 0, 4) . '.00.0.06.06';
          $kd_kegiatan = substr($kd_skpd, 0, 4) . '.00.0.00';
          $kd_program  = substr($kd_skpd, 0, 4) . '.00';
          $kd_bidang_urusan = substr($kd_skpd, 0, 4);
          $nm_rek6 = $value->nama_akun;
          $nilai = is_null($value->total) ? 'null' : $value->total;
          $header = $value->uraian;
          $subheader = $value->keterangan;
          $uraian = $value->nama_akun;



          $stmt = $this->db->query("SELECT count(*) ada FROM trskpd WHERE kd_gabungan='$kd_gabungan'");
          $trskpd = $stmt->row();

          if ($trskpd->ada == 0) {
            $stmt = $this->db->query("INSERT trskpd (id, jns_ang,kd_gabungan, kd_bidang_urusan, kd_skpd, nm_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan)
                        VALUES ('$no_idtrskpd','$jns_ang','$kd_gabungan', '$kd_bidang_urusan', '$kd_skpd', '$nm_skpd', '$kd_program', 'Non Program', '$kd_kegiatan', 'Pendapatan', '$kd_sub_kegiatan', 'Pendapatan')");
          }

          $this->db->query("INSERT INTO trdrka (id,no_trdrka, jns_ang, kd_skpd, nm_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6,nilai) values ('$no_id','$no_trdrka','$jns_ang','$kd_skpd','$nm_skpd','$kd_sub_kegiatan','Pendapatan','$kd_rek6','$nm_rek6','$nilai')");

          // /*header*/
          // $this->db->query("INSERT INTO trdpo (no_trdrka, header, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,subs_bl_teks)
          //     values ('$no_trdrka',1,'$kd_skpd','$kd_sub_kegiatan','$kd_rek6','$uraian',':: $header')");

          // /*subheader*/
          // $this->db->query("INSERT INTO trdpo (no_trdrka, header, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,ket_bl_teks)
          //     values ('$no_trdrka',1,'$kd_skpd','$kd_sub_kegiatan','$kd_rek6','$uraian','::: $subheader')");

          /*rincian*/
          $this->db->query("INSERT INTO trdpo (jns_ang, no_trdrka, kd_skpd, kd_sub_kegiatan, kd_rek6, uraian,sub_header,header,total, volume1,harga)
                        values ('$jns_ang', '$no_trdrka','$kd_skpd','$kd_sub_kegiatan','$kd_rek6','$uraian','::: $subheader','$header','$nilai',1,'$nilai')");
        } /*end foreach($daftarSubKegiatan->data as  $value)*/
      }/* end !empty($daftarSubKegiatan)->data*/
    } /*end looping skpd*/
  } /*end pendapatan*/
}
