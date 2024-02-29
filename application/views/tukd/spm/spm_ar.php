<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.min.css">
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
  <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
  <script type="text/javascript">
    var nl = 0;
    var tnl = 0;
    var idx = 0;
    var tidx = 0;
    var oldRek = 0;
    var rek = 0;
    var lcstatus = '';
    var jumlah_pajak = 0;
    var pidx = 0;

    $(function() {

      $(document).ready(function() {
        $("#alerts").hide();
        $("#dialog-batal").dialog({
          height: 300,
          width: 700,
          modal: true,
          autoOpen: false
        });
        get_skpd2();
        //seting_tombol();
      });



      $('#dd').datebox({
        required: true,
        formatter: function(date) {
          var y = date.getFullYear();
          var m = date.getMonth() + 1;
          var d = date.getDate();
          return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
        },
        onSelect: function(date) {

          $("#kebutuhan_bulan").attr("Value", (date.getMonth() + 1));
        }
      });



      $('#cspm').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spm/pilih_spm',
        idField: 'no_spm',
        textField: 'no_spm',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'no_spm',
              title: 'SPM',
              width: 60
            },
            {
              field: 'kd_skpd',
              title: 'SKPD',
              align: 'left',
              width: 60
            },
            {
              field: 'no_spp',
              title: 'SPP',
              width: 60
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          kode = rowData.no_spm;
          skpd = rowData.kd_skpd;
          //val_ttd(skpd);
        }
      });

      function get_skpd2() {

        $.ajax({
          url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
          type: "POST",
          dataType: "json",
          success: function(data) {
            skpd = data.kd_skpd;
            tombolsimpan(skpd);
          }
        });
      }

      function tombolsimpan(skpd) {
        $(function() {
          $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>index.php/master/cek_tombol/" + skpd,
            dataType: "json",
            success: function(data) {
              $.each(data, function(i, n) {
                var kunci = n['kunci_spm'];
                if (kunci == '1') {
                  document.getElementById("save").disabled = true;
                  document.getElementById("btntambah").disabled = true;
                } else {
                  document.getElementById("save").disabled = false;
                  document.getElementById("btntambah").disabled = false;
                }
              });
            }
          });
        });
      }


      $('#bank1').combogrid({
        panelWidth: 200,
        url: '<?php echo base_url(); ?>/index.php/spm/config_bank2',
        idField: 'kd_bank',
        textField: 'kd_bank',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'kd_bank',
              title: 'Kd Bank',
              width: 40
            },
            {
              field: 'nama_bank',
              title: 'Nama',
              width: 140
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          //$("#kode").attr("value",rowData.kode);
          $("#nama_bank").attr("value", rowData.nama_bank);
        }
      });

      $('#cc').combobox({
        url: '<?php echo base_url(); ?>/index.php/spm/load_jenis_beban',
        valueField: 'id',
        textField: 'text',
        onSelect: function(rowIndex, rowData) {
          //validate_tombol();
        }
      });

      $('#spm').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spm/load_spm',

        idField: 'id',
        rownumbers: "true",
        fitColumns: "true",
        singleSelect: "true",
        autoRowHeight: "false",
        loadMsg: "Tunggu Sebentar....!!",
        pagination: "true",
        nowrap: "true",

        columns: [
          [{
              title: '',
              width: 5,
              checkbox: "true"
            },
            {
              field: 'no_spm',
              title: 'Nomor SPM',
              width: 70
            },
            {
              field: 'tgl_spm',
              title: 'Tanggal',
              width: 30
            },
            {
              field: 'kd_skpd',
              title: ' SKPD',
              width: 30,
              align: "left",
              hidden: true
            },
            {
              field: 'keperluan',
              title: 'Keterangan',
              width: 140,
              align: "left"
            }
          ]
        ],
        rowStyler: function(rowIndex, rowData) {
          if (rowData.status == 1) {
            return 'background-color:#4bbe68;color:white';
          }
        },
        onSelect: function(rowIndex, rowData) {
          urut = rowData.urut;
          no_spm = rowData.no_spm;
          no_spp = rowData.no_spp;
          skpd = rowData.kd_skpd;
          tgs = rowData.tgl_spm;
          st = rowData.status;
          jns = rowData.jns_spp;
          jns_bbn = rowData.jns_beban;
          nospd = rowData.no_spd;
          tgspp = rowData.tgl_spp;
          cnpwp = rowData.npwp;
          nbl = rowData.bulan;
          ckep = rowData.keperluan;
          bank = rowData.bank;
          crekan = rowData.nmrekan;
          cnorek = rowData.no_rek;
          cnmskpd = rowData.nm_skpd;
          csp2d_batal = rowData.sp2d_batal;
          cket_batal = rowData.ket_batal;
          getspm(urut, no_spm, no_spp, tgs, st, jns, skpd, nospd, tgspp, cnpwp, nbl, ckep, bank, crekan, cnorek, cnmskpd, jns_bbn, csp2d_batal, cket_batal);
          $("#no_spm").attr('disabled', true);
          $("#spm_pot").attr('disabled', true);
          detail();
          lcstatus = 'edit';
        },
        onDblClickRow: function(rowIndex, rowData, st) {
          section2();
        }
      });



      $('#nospp').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spm/nospp_2',
        idField: 'no_spp',
        textField: 'no_spp',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'no_spp',
              title: 'No',
              width: 60
            },
            {
              field: 'kd_skpd',
              title: 'SKPD',
              align: 'left',
              width: 80
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          no_spp = rowData.no_spp;
          skpd = rowData.kd_skpd;
          sp = rowData.no_spd;
          bl = rowData.bulan;
          tg = rowData.tgl_spp;
          tgspd = rowData.tgl_spd;
          jns = rowData.jns_spp;
          jns_bbn = rowData.jns_beban;
          kep = rowData.keperluan;
          np = rowData.npwp;
          rekan = rowData.nmrekan;
          bk = rowData.bank;
          ning = rowData.no_rek;
          nm = rowData.nm_skpd.trim();
          get(no_spp, skpd, sp, tg, bl, jns, kep, np, rekan, bk, ning, nm, jns_bbn, tgspd);

          detail();
          // get_spm();

        }
      });


      $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spm/select_data1',
        autoRowHeight: "true",
        idField: 'id',
        toolbar: "#toolbar",
        rownumbers: "true",
        fitColumns: false,
        singleSelect: "true"
      });


      $('#rekpajak').combogrid({
        panelWidth: 800,
        idField: 'kd_rek6',
        textField: 'kd_rek6',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/spm/rek_pot',
        columns: [
          [{
              field: 'kd_rek6',
              title: 'Kode Rekening',
              width: 100
            },
            {
              field: 'map_pot',
              title: 'Nama Rekening',
              width: 100
            },
            {
              field: 'nm_rek6',
              title: 'Nama Rekening',
              width: 600
            },
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          $("#nmrekpajak").attr("value", rowData.nm_rek6);
          $("#map_pot").attr("value", rowData.map_pot);
        }
      });


      $('#dgpajak').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spm/pot',
        idField: 'id',
        toolbar: "#toolbar",
        rownumbers: "true",
        fitColumns: false,
        autoRowHeight: "true",
        singleSelect: false,
        columns: [
          [{
              field: 'id',
              title: 'id',
              width: 100,
              align: 'left',
              hidden: 'true'
            },
            {
              field: 'kd_trans',
              title: 'Rek. Trans',
              width: 100,
              align: 'left'
            },
            {
              field: 'kd_sub_kegiatan',
              title: 'Sub Kegiatan',
              width: 100,
              align: 'left'
            },
            {
              field: 'kd_rek6',
              title: 'Rekening',
              width: 100,
              align: 'left'
            },
            {
              field: 'map_pot',
              title: 'Rekening',
              width: 100,
              align: 'left',
              hidden: 'true'
            },
            {
              field: 'nm_rek6',
              title: 'Nama Rekening',
              width: 317
            },
            {
              field: 'nilai',
              title: 'Nilai',
              width: 100,
              align: "right"
            },
            {
              field: 'hapus',
              title: 'Hapus',
              width: 100,
              align: "center",
              formatter: function(value, rec) {
                return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
              }
            }
          ]
        ]
      });

      $('#ttd1').combogrid({
        panelWidth: 600,
        idField: 'nip',
        textField: 'nip',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/spm/load_ttd/BK',
        columns: [
          [{
              field: 'nip',
              title: 'NIP',
              width: 200
            },
            {
              field: 'nama',
              title: 'Nama',
              width: 400
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          $("#nmttd1").attr("value", rowData.nama);
        }
      });

      $('#ttd2').combogrid({
        panelWidth: 600,
        idField: 'nip',
        textField: 'nip',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/spm/load_ttd2/PPTK/PPK',
        columns: [
          [{
              field: 'nip',
              title: 'NIP',
              width: 200
            },
            {
              field: 'nama',
              title: 'Nama',
              width: 400
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          $("#nmttd2").attr("value", rowData.nama);
        }
      });

      $('#ttd3').combogrid({
        panelWidth: 600,
        idField: 'nip',
        textField: 'nip',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/spm/load_ttd/PA',
        columns: [
          [{
              field: 'nip',
              title: 'NIP',
              width: 200
            },
            {
              field: 'nama',
              title: 'Nama',
              width: 400
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          $("#nmttd3").attr("value", rowData.nama);
        }
      });

      $('#ttd4').combogrid({
        panelWidth: 600,
        idField: 'nip',
        textField: 'nip',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/spm/load_ttd3/BUD',
        columns: [
          [{
              field: 'nip',
              title: 'NIP',
              width: 200
            },
            {
              field: 'nama',
              title: 'Nama',
              width: 400
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          $("#nmttd4").attr("value", rowData.nama);
        }
      });
    });




    function detail() {
      $(function() {
        $('#dg').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spm/select_data1',
          queryParams: ({
            spp: no_spp
          }),
          idField: 'idx',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "true",
          singleSelect: false,
          onLoadSuccess: function(data) {
            load_sum_spm();
          },
          columns: [
            [{
                field: 'ck',
                title: 'ck',
                checkbox: true,
                hidden: true
              },
              {
                field: 'kdkegiatan',
                title: 'Sub Kegiatan',
                width: 150,
                align: 'left'
              },
              {
                field: 'kdrek5',
                title: 'Rekening',
                width: 70,
                align: 'left'
              },
              {
                field: 'nmrek5',
                title: 'Nama Rekening',
                width: 350
              },
              {
                field: 'nilai1',
                title: 'Nilai',
                width: 170,
                align: 'right'
              }
            ]
          ]
        });
      });
    }



    function detail1() {
      $(function() {
        var no_spp = '';
        $('#dg').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spm/select_data1',
          queryParams: ({
            spp: no_spp
          }),
          idField: 'idx',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "true",
          singleSelect: false,
          columns: [
            [{
                field: 'ck',
                title: 'ck',
                checkbox: true,
                hidden: true
              },
              {
                field: 'kdkegiatan',
                title: 'Sub Kegiatan',
                width: 150,
                align: 'left'
              },
              {
                field: 'kdrek5',
                title: 'Rekening',
                width: 70,
                align: 'left'
              },
              {
                field: 'nmrek5',
                title: 'Nama Rekening',
                width: 400
              },
              {
                field: 'nilai1',
                title: 'Nilai',
                width: 100,
                align: 'right'
              }
            ]
          ]
        });
      });
    }


    function get(no_spp, kd_skpd, no_spd, tgl_spp, bulan, jns_spp, keperluan, npwp, rekanan, bank, rekening, nm_skpd, jns_bbn, tgspd) {
      $("#nospp").attr("value", no_spp);
      $("#nospp1").attr("value", no_spp);
      $("#dn").attr("value", kd_skpd);
      $("#tgl_spp").attr("value", tgl_spp);
      $("#tgl_spd").attr("value", tgspd);
      $("#sp").attr("value", no_spd);
      $("#kebutuhan_bulan").attr("Value", bulan);
      $("#ketentuan").attr("Value", keperluan);
      $("#jns_beban").attr("Value", jns_spp);
      $("#npwp").attr("Value", npwp);
      $("#rekanan").attr("Value", rekanan);
      $("#bank1").combogrid("setValue", bank);
      $("#rekening").attr("Value", rekening);
      $("#nmskpd").attr("Value", nm_skpd);
      validate_jenis_edit(jns_bbn);
      validate_rek_trans(no_spp);

    }


    function getspm(urut, no_spm, no_spp, tgl_spm, status, jns_spp, kd_skpd, nospd, tgspp, npwp, bulan, keperluan, bank, rekanan, rekening, nm_skpd, jns_bbn, sp2d_batal, ket_batal) {

      if (jns_spp == '6' || jns_spp == '5') {

        // document.getElementById("buat").style.display = '';

        $("#buat").show();
      } else {
        $("#buat").show();
        // document.getElementById("buat").style.display = 'none';
      }
      $("#nospp").combogrid("clear");
      $("#dd_spm").attr("value", urut);
      $("#no_spm").attr("value", no_spm);
      $("#spm_pot").attr("value", no_spm);
      $("#no_spm_hide").attr("value", no_spm);
      $("#nospp").combogrid("setValue", no_spp);
      $("#nospp1").attr("value", no_spp);
      $("#dd").datebox("setValue", tgl_spm);
      // $("#dd").datebox("disabled",true);
      $("#save").attr("disabled", true);
      $("#jns_beban").attr("Value", jns_spp);
      $("#dn").attr("Value", kd_skpd);
      $("#sp").attr("value", nospd);
      $("#tgl_spp").attr("value", tgspp);
      $("#npwp").attr("Value", npwp);
      $("#kebutuhan_bulan").attr("Value", bulan);
      $("#ketentuan").attr("Value", keperluan);
      $("#bank1").combogrid("setValue", bank);
      $("#rekanan").attr("Value", rekanan);
      $("#rekening").attr("Value", rekening);
      $("#nmskpd").attr("Value", nm_skpd);
      $("#ket_batal").attr("Value", ket_batal);


      tampil_potongan();
      load_sum_pot();
      validate_jenis_edit(jns_bbn);
      tombol(status);
      validate_rek_trans(no_spp);
      status_batal(sp2d_batal);
      $('#rnospm').linkbutton('disable');
    }

    function status_batal($status1) {
      // alert($status1);
      if ($status1 == '1') {
        document.getElementById("save").disabled = true;
        // document.getElementById("del").disabled = true;
        document.getElementById("save-pot").disabled = true;
        // document.getElementById("del-pot").disabled = true;
        document.getElementById("edit-ket").disabled = false;
        document.getElementById("batal").disabled = false;
        document.getElementById("del1").disabled = false;
        document.getElementById("cetak").disabled = true;

        $("#alerts").show();
        document.getElementById("p2").innerHTML = "SPP - SPM dalam Status Batal";
      } else {
        document.getElementById("p2").innerHTML = "";
      }
    }

    function kosong() {

      lcstatus = 'tambah';
      cdate = '<?php echo date("Y-m-d"); ?>';
      $("#dd_spm").attr("value", '');
      $("#no_spm").attr("value", '');
      $("#no_spm_hide").attr("value", '');
      $("#spm_pot").attr("value", '');
      $("#dd").datebox("setValue", cdate);
      $("#nospp").combogrid("setValue", '');
      $("#dn").attr("value", '');
      $("#sp").attr("value", '');
      $("#tgl_spp").attr("value", '');
      $("#kebutuhan_bulan").attr("Value", '');
      $("#ketentuan").attr("Value", '');
      $("#jns_beban").attr("Value", '');
      $("#npwp").attr("Value", '');
      $("#rekanan").attr("Value", '');
      $("#bank1").combogrid("setValue", '');
      $("#rekening").attr("Value", '');
      $("#nmskpd").attr("Value", '');
      detail1();
      $("#no_spm").attr('disabled', true);
      $("#spm_pot").attr('disabled', true);
      $("#nospp").combogrid("clear");
      tombolnew();
      $("#totalrekpajak").attr("value", 0);
      document.getElementById("p1").innerHTML = "";
      document.getElementById("p2").innerHTML = "";
      $('#rnospm').linkbutton('enable');
      $("#update").attr('disabled', true);
      $("#batal").attr('disabled', true);
      // $("#cetak").attr('disabled', true);
      $("#edit-ket").attr('disabled', true);

    }


    $(document).ready(function() {
      $("#accordion").accordion();
      $("#lockscreen").hide();
      $("#frm").hide();
      $("#dialog-modal").dialog({
        height: 650,
        width: 970,
        modal: true,
        autoOpen: false
      });
      get_tahun();

    });


    function cetak() {
      var nom = document.getElementById("no_spm").value;
      $("#cspm").combogrid("setValue", nom);
      $("#dialog-modal").dialog('open');
    }


    function keluar() {
      $("#dialog-modal").dialog('close');
    }

    function get_tahun() {
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spm/config_tahun',
        type: "POST",
        dataType: "json",
        success: function(data) {
          tahun_anggaran = data;
        }
      });

    }

    function cari() {
      var kriteria = document.getElementById("txtcari").value;
      $(function() {
        $('#spm').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spm/load_spm',
          queryParams: ({
            cari: kriteria
          })
        });
      });
    }

    function data_no_spp() {
      $('#nospp').combogrid({
        url: '<?php echo base_url(); ?>/index.php/spm/nospp_2'
      });
    }

    function simpan_spm() {
      // get_spm();     
      var a1 = (document.getElementById('no_spm').value).split(" ").join("");
      var a1_hide = document.getElementById('no_spm_hide').value;
      var a1_dd = document.getElementById('dd_spm').value;
      var b1 = $('#dd').datebox('getValue');
      var b = document.getElementById('tgl_spp').value;
      var c = document.getElementById('jns_beban').value;
      var d = document.getElementById('kebutuhan_bulan').value;
      var e = document.getElementById('ketentuan').value;
      var f = document.getElementById('rekanan').value;
      var g = $("#bank1").combogrid("getValue");
      var h = document.getElementById('npwp').value;
      var i = document.getElementById('rekening').value;
      var j = document.getElementById('nmskpd').value;
      var k = document.getElementById('dn').value;
      var l = document.getElementById('sp').value;
      var m = document.getElementById('rekspm1').value;
      var cc = $('#cc').combobox('getValue');

      alert(b1);
      var tahun_input = b1.substring(0, 4);
      if (tahun_input != tahun_anggaran) {
        alert('Tahun tidak sama dengan tahun Anggaran');
        exit();
      }
      if (a1 == "") {
        alert("No SPM Tidak Boleh Kosong");
        exit();
      }
      if (l == "") {
        alert("No SPD Tidak Boleh Kosong");
        exit();
      }
      if (b > b1) {
        alert("Tanggal SMP tidak boleh lebih kecil dari tanggal SPP");
        exit();
      }
      var lenket = e.length;
      if (lenket > 1000) {
        alert('Keterangan Tidak boleh lebih dari 1000 karakter');
        exit();
      }
      if (lcstatus == 'tambah') {

        lcinsert = " ( no_spm,     tgl_spm,   no_spp,       kd_skpd,  nm_skpd,  tgl_spp,  bulan,   no_spd,  keperluan, username, last_update, status, jns_spp, jenis_beban,  bank,     nmrekan,  no_rek,   npwp,    nilai, urut   ) ";
        lcvalues = " ( '" + a1 + "',   '" + b1 + "',  '" + no_spp + "', '" + k + "',  '" + j + "',  '" + b + "',  '" + d + "', '" + l + "', '" + e + "',   '',       '',          '0',    '" + c + "', '" + cc + "',  '" + g + "',  '" + f + "',  '" + i + "',  '" + h + "', '" + m + "', '" + a1_dd + "' ) ";

        $(document).ready(function() {
          $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>/index.php/spm/simpan_tukd_spm',
            data: ({
              tabel: 'trhspm',
              kolom: lcinsert,
              nilai: lcvalues,
              cid: 'no_spm',
              lcid: a1,
              tagih: no_spp
            }),
            dataType: "json",
            success: function(data) {
              status = data;
              if (status == '0') {
                alert('Gagal Simpan..!!');
                exit();
              } else if (status == '1') {
                alert('Nomor SPM Sudah Terpakai...!!!,  Ganti Nomor SPM...!!!');
                exit();
              } else {
                //cek potongan
                var ctot_det_pot = 0;
                $('#dgpajak').datagrid('selectAll');
                var rows = $('#dgpajak').datagrid('getSelections');
                for (var x = 0; x < rows.length; x++) {
                  cnilai3 = angka(rows[x].nilai);
                  ctot_det_pot = ctot_det_pot + cnilai3;
                }
                //jika potongan tidak ada                     
                if (ctot_det_pot == 0) {
                  $("#no_spm_hide").attr("value", a1);
                  lcstatus = 'edit';
                  alert('Data Tersimpan..!! Tak ada potongan!');
                  data_no_spp();
                  $('#rnospm').linkbutton('disable');
                } else {
                  //input potongan
                  $('#dgpajak').datagrid('selectAll');
                  var rows = $('#dgpajak').datagrid('getSelections');
                  for (var i = 0; i < rows.length; i++) {
                    cidx = rows[i].idx;
                    csubkegiatan = rows[i].kd_sub_kegiatan;
                    ckdrek6 = rows[i].kd_rek6;
                    cmap_pot = rows[i].map_pot;
                    ckd_trans = rows[i].kd_trans;
                    cnm_rek6 = rows[i].nm_rek6;
                    cnilai = angka(rows[i].nilai);
                    no = i + 1;
                    if (i > 0) {
                      csql = csql + "," + "('" + a1 + "','" + csubkegiatan + "','" + ckdrek6 + "','" + cnm_rek6 + "','" + cnilai + "','" + k + "',' ','" + ckd_trans + "','" + cmap_pot + "')";
                    } else {
                      csql = "values('" + a1 + "','" + csubkegiatan + "','" + ckdrek6 + "','" + cnm_rek6 + "','" + cnilai + "','" + k + "',' ','" + ckd_trans + "','" + cmap_pot + "')";
                    }
                  }

                  $(document).ready(function() {
                    $.ajax({
                      type: "POST",
                      dataType: 'json',
                      data: ({
                        no: a1,
                        sql: csql
                      }),
                      beforeSend: function(xhr) {
                        $("#loading").dialog('open');
                      },
                      url: '<?php echo base_url(); ?>/index.php/spm/dsimpan_potspm',
                      success: function(data) {
                        status = data.pesan;
                        if (status == '1') {
                          $("#loading").dialog('close');
                          $("#no_spm_hide").attr("value", a1);
                          lcstatus = 'edit';
                          alert('Data Tersimpan..!!');
                          data_no_spp();
                          $('#rnospm').linkbutton('disable');
                          exit();
                        } else {
                          //$("#loading").dialog('close');
                          lcstatus = 'tambah';
                          alert('Detail Gagal Tersimpan...!!!');
                        }
                      }
                    });
                  });
                  //akhir input potongan
                }




              }
            }
          });
        });

      } else {

        lcquery = " UPDATE trhspm SET no_spm='" + a1 + "',  tgl_spm='" + b1 + "',  no_spp='" + no_spp + "', kd_skpd='" + k + "',  nm_skpd='" + j + "', tgl_spp='" + b + "',  bulan='" + d + "',   no_spd='" + l + "',  keperluan='" + e + "',  username='',  last_update='',  status='0',  jns_spp='" + c + "', jenis_beban='" + cc + "',  bank='" + g + "',  nmrekan='" + f + "',  no_rek='" + i + "',  npwp='" + h + "',  nilai='" + m + "',urut='" + a1_dd + "' where no_spm='" + a1_hide + "' AND kd_skpd='" + k + "' ";
        $(document).ready(function() {
          $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>/index.php/spm/update_spm',
            data: ({
              st_query: lcquery,
              tabel: 'trhspm',
              cid: 'no_spm',
              lcid: a1,
              lcid_h: a1_hide
            }),
            dataType: "json",
            success: function(data) {
              status = data;
              if (status == '1') {
                alert('Nomor SPM Sudah Terpakai...!!!,  Ganti Nomor SPM...!!!');
                exit();
              }
              if (status == '2') {
                //cek potongan
                var ctot_det_pot = 0;
                $('#dgpajak').datagrid('selectAll');
                var rows = $('#dgpajak').datagrid('getSelections');
                for (var x = 0; x < rows.length; x++) {
                  cnilai3 = angka(rows[x].nilai);
                  ctot_det_pot = ctot_det_pot + cnilai3;
                }
                //jika potongan tidak ada                     
                if (ctot_det_pot == 0) {
                  $("#no_spm_hide").attr("value", a1);
                  lcstatus = 'edit';
                  alert('Data Tersimpan..!! Tak ada potongan!');
                  data_no_spp();
                  $('#rnospm').linkbutton('disable');
                } else {
                  $('#dgpajak').datagrid('selectAll');
                  var rows = $('#dgpajak').datagrid('getSelections');
                  for (var i = 0; i < rows.length; i++) {
                    cidx = rows[i].idx;
                    csub_kegiatan = rows[i].kd_sub_kegiatan;
                    ckdrek6 = rows[i].kd_rek6;
                    cmap_pot = rows[i].map_pot;
                    ckd_trans = rows[i].kd_trans;
                    cnm_rek6 = rows[i].nm_rek6;
                    cnilai = angka(rows[i].nilai);
                    no = i + 1;
                    if (i > 0) {
                      var csql = csql + "," + "('" + a1 + "','" + csub_kegiatan + "','" + ckdrek6 + "','" + cnm_rek6 + "','" + cnilai + "','" + k + "',' ','" + ckd_trans + "','" + cmap_pot + "')";
                    } else {
                      var csql = "values('" + a1 + "','" + csub_kegiatan + "','" + ckdrek6 + "','" + cnm_rek6 + "','" + cnilai + "','" + k + "',' ','" + ckd_trans + "','" + cmap_pot + "')";
                    }
                  }
                  $(document).ready(function() {
                    //alert(csql);
                    //exit();
                    $.ajax({
                      type: "POST",
                      dataType: 'json',
                      data: ({
                        no: a1,
                        sql: csql,
                        no_hide: a1_hide
                      }),
                      url: '<?php echo base_url(); ?>/index.php/spm/update_dsimpan_potspm',
                      success: function(data) {
                        status = data.pesan;
                        if (status == '1') {
                          $("#loading").dialog('close');
                          $("#no_spm_hide").attr("value", a1);
                          lcstatus = 'edit';
                          alert('Data Tersimpan..!!');
                          data_no_spp();
                          $('#rnospm').linkbutton('disable');
                        } else {
                          //$("#loading").dialog('close');
                          lcstatus = 'tambah';
                          alert('Detail Gagal Tersimpan...!!!');
                        }
                      }
                    });
                  });
                }
              }
              if (status == '0') {
                alert('Gagal Simpan...!!!');
                exit();
              }
            }
          });
        });
      }
      //$("#no_spm_hide").attr("Value",a1);
    }

    function edit_keterangan() {

      var a1 = (document.getElementById('no_spm').value).split(" ").join("");
      var a1_hide = document.getElementById('no_spm_hide').value;
      var b1 = $('#dd').datebox('getValue');
      var b = document.getElementById('tgl_spp').value;
      var c = document.getElementById('jns_beban').value;
      var d = document.getElementById('kebutuhan_bulan').value;
      var e = document.getElementById('ketentuan').value;
      var f = document.getElementById('rekanan').value;
      var g = $("#bank1").combogrid("getValue");
      var h = document.getElementById('npwp').value;
      var i = document.getElementById('rekening').value;
      var j = document.getElementById('nmskpd').value;
      var k = document.getElementById('dn').value;
      var l = document.getElementById('sp').value;
      var m = document.getElementById('rekspm1').value;
      var cc = $('#cc').combobox('getValue');
      var tahun_input = b1.substring(0, 4);
      if (tahun_input != tahun_anggaran) {
        alert('Tahun tidak sama dengan tahun Anggaran');
        exit();
      }
      if (a1 == "") {
        alert("No SPM Tidak Boleh Kosong");
        exit();
      }
      if (l == "") {
        alert("No SPD Tidak Boleh Kosong");
        exit();
      }
      if (b > b1) {
        alert("Tanggal SMP tidak boleh lebih kecil dari tanggal SPP");
        exit();
      }
      var lenket = e.length;
      if (lenket > 1000) {

        alert('Keterangan Tidak boleh lebih dari 1000 karakter');
        exit();
      }

      lcquery = " UPDATE trhspm SET keperluan='" + e + "', tgl_spm='" + b1 + "' WHERE no_spm='" + a1 + "' AND no_spp='" + no_spp + "' AND kd_skpd='" + k + "'";
      lcquery2 = " UPDATE trhspp SET keperluan='" + e + "' WHERE no_spp='" + no_spp + "' AND kd_skpd='" + k + "'";
      $(document).ready(function() {
        $.ajax({
          type: "POST",
          url: '<?php echo base_url(); ?>/index.php/spm/update_ket_spm',
          data: ({
            st_query: lcquery,
            st_query2: lcquery2,
            tabel: 'trhspm',
            cid: 'no_spm',
            lcid: a1,
            lcid_h: a1_hide
          }),
          dataType: "json",
          success: function(data) {
            status = data;
            if (status == '1') {
              alert('Nomor SPM Sudah Terpakai...!!!,  Ganti Nomor SPM...!!!');
              exit();
            }
            if (status == '2') {
              alert('Data Berhasil Disimpan...!!!');
              exit();
            }
            if (status == '0') {
              alert('Gagal Simpan...!!!');
              exit();
            }
          }
        });
      });
    }



    function simpan(reke, nrek) {
      var spm = document.getElementById('no_spm').value;
      var cskpd = document.getElementById('dn').value;

      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            cskpd: cskpd,
            spm: spm,
            kd_rek6: reke,
            nmrek: nrek
          }),
          dataType: "json",
          url: '<?php echo base_url(); ?>/index.php/spm/pot_simpan'
        });
      });
    }


    function psimpan(reke, nrek, nilai, ket) {
      var spm = document.getElementById('no_spm').value;
      var cskpd = document.getElementById('dn').value;
      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            cskpd: cskpd,
            spm: spm,
            kd_rek6: reke,
            nmrek: nrek,
            nilai: nilai,
            ket: ket
          }),
          dataType: "json",
          url: '<?php echo base_url(); ?>/index.php/spm/potsimpan'
        });
      });
    }


    function hhapus() {
      var spm = document.getElementById("no_spm_hide").value;
      var urll = '<?php echo base_url(); ?>/index.php/spm/hapus_spm';
      if (spm != '') {
        var del = confirm('Anda yakin akan menghapus SPM ' + spm + '  ?');
        if (del == true) {
          $(document).ready(function() {
            $.post(urll, ({
              no: spm,
              spp: no_spp
            }), function(data) {
              status = data;
            });
          });
        }
      }
    }

    function getSelections(idx) {
      var ids = [];
      var rows = $('#pot').edatagrid('getSelections');
      for (var i = 0; i < rows.length; i++) {
        ids.push(rows[i].kd_rek5);
      }
      return ids.join(':');
    }

    function load_sum_spm() {
      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            spp: no_spp
          }),
          url: "<?php echo base_url(); ?>index.php/spm/load_sum_spm",
          dataType: "json",
          success: function(data) {
            $.each(data, function(i, n) {
              $("#rekspm").attr("value", n['rekspm']);
              $("#rekspm1").attr("value", n['rekspm1']);
            });
          }
        });
      });
    }

    function load_sum_pot() {
      var spm = document.getElementById('no_spm').value;
      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            spm: spm
          }),
          url: "<?php echo base_url(); ?>index.php/spm/load_sum_pot",
          dataType: "json",
          success: function(data) {
            $.each(data, function(i, n) {
              //$("#totalrekpajak").attr("value",number_format(n['rektotal'],2,'.',','));
              $("#totalrekpajak").attr("value", n['rektotal']);
            });
          }
        });
      });
    }

    function section1() {
      $(document).ready(function() {
        $('#section1').click();
        // $('#spm').edatagrid('reload');
      });
    }

    function section2() {
      $(document).ready(function() {
        $('#section2').click();


      });
    }

    function section3() {
      $(document).ready(function() {
        $('#section3').click();
      });
    }

    ///copy
    function tombol(st) {
      if (st == 1) {

        document.getElementById("save").disabled = true;
        document.getElementById("update").disabled = true;
        // document.getElementById("del").disabled = true;
        document.getElementById("save-pot").disabled = true;
        // document.getElementById("del-pot").disabled = true;
        document.getElementById("edit-ket").disabled = false;
        document.getElementById("batal").disabled = true;
        $('#rnospm').linkbutton('disable');
        document.getElementById("p1").innerHTML = "SPM SUDAH DIBUAT SP2D!!";
        $("#alerts").show();
      } else {
        $("#alerts").hide();
        document.getElementById("update").disabled = false;
        // document.getElementById("save").disabled = false;
        // document.getElementById("del").disabled = false;
        document.getElementById("save-pot").disabled = false;
        // document.getElementById("del-pot").disabled = false;
        document.getElementById("edit-ket").disabled = true;
        document.getElementById("batal").disabled = false;
        $('#rnospm').linkbutton('enable');

        document.getElementById("p1").innerHTML = "";
      }
    }

    function tombolnew() {
      document.getElementById("save").disabled = false;
      // document.getElementById("del").disabled = false;
      document.getElementById("save-pot").disabled = false;
      // document.getElementById("del-pot").disabled = false;
      document.getElementById("edit-ket").disabled = false;
    }

    function openWindow(url) {
      var kode = $("#cspm").combogrid("getValue");
      var no = kode.split("/").join("123456789");
      var ttd = $("#ttd1").combogrid("getValue");
      var ttd1 = ttd.split(" ").join("123456789");
      var ttd_2 = $("#ttd2").combogrid("getValue");
      var ttd2 = ttd_2.split(" ").join("123456789");
      var ttd_3 = $("#ttd3").combogrid("getValue");
      var ttd3 = ttd_3.split(" ").join("123456789");
      var ttd_4 = $("#ttd4").combogrid("getValue");
      var ttd4 = ttd_4.split(" ").join("123456789");
      var tanpa = document.getElementById('tanpa_tanggal').checked;
      var baris = document.getElementById("baris").value;
      var buat = document.getElementById("jns_ls").value;

      if (jns == '1') {
        url1 = url.replace('cetakspm2', 'cetakspm2_up');
      } else if (jns == '2') {
        url1 = url.replace('cetakspm2', 'cetakspm2_gu');
      } else if (jns == '3') {
        url1 = url.replace('cetakspm2', 'cetakspm2_tu');
      } else if (jns == '4') {
        url1 = url.replace('cetakspm2', 'cetakspm2_lsgj');
      } else if (jns == '5') {
        url1 = url.replace('cetakspm2', 'cetakspm2_lspk');
      } else if (jns == '6') {
        url1 = url.replace('cetakspm2', 'cetakspm2_barjas');
      } else if (jns == '7') {
        url1 = url.replace('cetakspm2', 'cetakspm2_gu_nihil');
      }

      if (tanpa == false) {
        tanpa = 0;
      } else {
        tanpa = 1;
      }
      if (ttd == '') {
        alert("Pilih Bendahara Pengeluaran Terlebih Dahulu!");
        exit();
      }
      if (ttd_2 == '') {
        alert("Pilih PPTK Terlebih Dahulu!");
        exit();
      }
      if (ttd_3 == '') {
        alert("Pilih Pengguna Anggaran Terlebih Dahulu!");
        exit();
      }
      if (ttd_4 == '') {
        alert("Pilih PPKD Terlebih Dahulu!");
        exit();
      }

      if (buat == '') {
        buat = '-';
      }
      window.open(url1 + '/' + no + '/' + skpd + '/' + jns + '/' + ttd1 + '/' + ttd2 + '/' + ttd3 + '/' + ttd4 + '/' + tanpa + '/' + baris + '/' + buat, '_blank');
      window.focus();
    }



    function cek() {
      var lcno = document.getElementById('no_spm').value;
      if (lcno != '') {
        section3();
        $("#totalrekpajak").attr("value", 0);
        $("#nilairekpajak").attr("value", 0);
        tampil_potongan();
        load_sum_pot();
        $("#rekpajak").combogrid("setValue", '');
        $("#nmrekpajak").attr("value", '');
        $("#map_pot").attr("value", '');

      } else {
        alert('Nomor SPM Tidak Boleh kosong')
        document.getElementById('no_spm').focus();
        exit();
      }
    }

    function Update() {
      var k = document.getElementById('dn').value;
      var no_spm = (document.getElementById('no_spm').value).split(" ").join("");
      $('#dgpajak').datagrid('selectAll');
      var rows = $('#dgpajak').edatagrid('getSelections');
      for (var i = 0; i < rows.length; i++) {
        cidx = rows[i].idx;
        csubkegiatan = rows[i].kd_sub_kegiatan;
        ckdrek6 = rows[i].kd_rek6;
        cmap_pot = rows[i].map_pot;
        ckd_trans = rows[i].kd_trans;
        cnm_rek6 = rows[i].nm_rek6;
        cnilai = angka(rows[i].nilai);
        no = i + 1;
        // if (ckdrek6 != undefined) {
        // var sql = "values('" + no_spm + "','" + csubkegiatan + "','" + ckdrek6 + "','" + cnm_rek6 + "','" + cnilai + "','" + k + "',' ','" + ckd_trans + "','" + cmap_pot + "')";
        // }
        if (i > 0) {
          var sql = sql + "," + "('" + no_spm + "','" + csubkegiatan + "','" + ckdrek6 + "','" + cnm_rek6 + "','" + cnilai + "','" + k + "',' ','" + ckd_trans + "','" + cmap_pot + "')";
        } else {
          var sql = "values('" + no_spm + "','" + csubkegiatan + "','" + ckdrek6 + "','" + cnm_rek6 + "','" + cnilai + "','" + k + "',' ','" + ckd_trans + "','" + cmap_pot + "')";
        }
      }
      $(document).ready(function() {
        $.ajax({
          type: "POST",
          dataType: 'json',
          data: ({
            cno_spm: no_spm,
            csql: sql
          }),
          beforeSend: function(xhr) {
            $("#loading").dialog('open');
          },
          url: '<?php echo base_url(); ?>/index.php/spm/UpdatePotongan',
          success: function(data) {
            status = data.pesan;
            if (status == '1') {
              alert('Data TerUpdate..!!');
            }
            location.reload();
          }
        });
      });


    }

    function append_save() {
      var no_spm_pot = document.getElementById('no_spm').value;
      $('#dgpajak').datagrid('selectAll');
      var rows = $('#dgpajak').datagrid('getSelections');
      jgrid = rows.length;
      var kd_trans = $("#rektrans").combogrid("getValue");
      var rek_pajak = $("#rekpajak").combogrid("getValue");
      var nm_rek_pajak = document.getElementById("nmrekpajak").value;
      var sub_kegiatan = document.getElementById("sub_kegiatan").value;
      // alert(sub_kegiatan);
      // alert(sub_kegiatan);
      // return;
      var map_pot = document.getElementById("map_pot").value;
      var nilai_pajak = document.getElementById("nilairekpajak").value;
      var nil_pajak = angka(nilai_pajak);
      var dinas = document.getElementById('dn').value;
      var vnospm = document.getElementById('no_spm').value;
      var cket = '0';
      var jumlah_pajak = document.getElementById('totalrekpajak').value;
      jumlah_pajak = angka(jumlah_pajak);
      if (no_spm_pot == '') {
        alert("Isi No SPM Terlebih Dahulu...!!!");
        exit();
      }
      if (kd_trans == '') {
        alert("Isi Rekening Transaksi Terlebih Dahulu...!!!");
        exit();
      }
      if (rek_pajak == '') {
        alert("Isi Rekening Pajak Terlebih Dahulu...!!!");
        exit();
      }

      if (nilai_pajak == 0) {
        alert("Isi Nilai Terlebih Dahulu...!!!");
        exit();
      }

      pidx = jgrid + 1;

      $('#dgpajak').edatagrid('appendRow', {
        kd_sub_kegiatan: sub_kegiatan,
        kd_rek6: rek_pajak,
        map_pot: map_pot,
        kd_trans: kd_trans,
        nm_rek6: nm_rek_pajak,
        nilai: nilai_pajak,
        id: pidx
      });
      /*
      $(document).ready(function(){      
                $.ajax({
                type     : 'POST',
                url      : "<?php echo base_url(); ?>index.php/spm/dsimpan_pot_ar",
                data     : ({cskpd:dinas,spm:vnospm,kd_rek5:rek_pajak,nmrek:nm_rek_pajak,nilai:nil_pajak,ket:cket,kd_trans:kd_trans}),
                dataType : "json"
                });
            });
            */
      $("#rekpajak").combogrid("setValue", '');
      $("#nmrekpajak").attr("value", '');
      $("#map_pot").attr("value", '');
      $("#nilairekpajak").attr("value", 0);
      jumlah_pajak = jumlah_pajak + nil_pajak;
      $("#totalrekpajak").attr('value', number_format(jumlah_pajak, 2, '.', ','));
      validate_rekening();
    }


    function validate_rekening() {
      $('#dgpajak').datagrid('selectAll');
      var rows = $('#dgpajak').datagrid('getSelections');
      frek = '';
      rek5 = '';
      for (var p = 0; p < rows.length; p++) {
        rek5 = rows[p].kd_rek5;
        if (p > 0) {
          frek = frek + ',' + rek5;
        } else {
          frek = rek5;
        }
      }

      $(function() {
        $('#rekpajak').combogrid({
          panelWidth: 700,
          idField: 'kd_rek6',
          textField: 'kd_rek6',
          mode: 'remote',
          url: '<?php echo base_url(); ?>index.php/spm/rek_pot',
          queryParams: ({
            kdrek: frek
          }),
          columns: [
            [{
                field: 'kd_rek6',
                title: 'Kode Rekening',
                width: 100
              },
              {
                field: 'nm_rek6',
                title: 'Nama Rekening',
                width: 700
              }
            ]
          ],
          onSelect: function(rowIndex, rowData) {
            $("#nmrekpajak").attr("value", rowData.nm_rek6);
            $("#map_pot").attr("value", rowData.map_pot);
          }
        });
      });
      $('#dgpajak').datagrid('unselectAll');
    }


    function hapus_detail() {

      var vnospm = document.getElementById('no_spm').value;
      var dinas = document.getElementById('dn').value;

      var rows = $('#dgpajak').edatagrid('getSelected');
      var ctotalpotspm = document.getElementById('totalrekpajak').value;

      bkdrek = rows.kd_rek6;
      bnilai = rows.nilai;

      var idx = $('#dgpajak').edatagrid('getRowIndex', rows);
      var tny = confirm('Yakin Ingin Menghapus Data, Rekening : ' + bkdrek + '  Nilai :  ' + bnilai + ' ?');

      if (tny == true) {
        $('#dgpajak').datagrid('deleteRow', idx);
        $('#dgpajak').datagrid('unselectAll');
        /* 
        var urll = '<?php echo base_url(); ?>index.php/spm/dsimpan_pot_delete_ar';
        $(document).ready(function(){
        $.post(urll,({cskpd:dinas,spm:vnospm,kd_rek5:bkdrek}),function(data){
        status = data;
           if (status=='0'){
               alert('Gagal Hapus..!!');
               exit();
           } else {
               alert('Data Telah Terhapus..!!');
               exit();
           }
        });
        });    
        */
        ctotalpotspm = angka(ctotalpotspm) - angka(bnilai);
        $("#totalrekpajak").attr("Value", number_format(ctotalpotspm, 2, '.', ','));
        validate_rekening();
      }
    }


    function tampil_potongan() {
      var vnospm = document.getElementById('no_spm').value;
      $(function() {
        $('#dgpajak').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spm/pot',
          queryParams: ({
            spm: vnospm
          }),
          idField: 'id',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "false",
          singleSelect: "true",
          nowrap: "true",
          columns: [
            [{
                field: 'id',
                title: 'id',
                width: 100,
                align: 'left',
                hidden: 'true'
              },
              {
                field: 'kd_sub_kegiatan',
                title: 'Sub Kegiatan',
                width: 100,
                align: 'left'
              },
              {
                field: 'kd_trans',
                title: 'Rek. Trans',
                width: 100,
                align: 'left'
              },
              {
                field: 'kd_rek6',
                title: 'Rekening',
                width: 100,
                align: 'left'
              },
              {
                field: 'map_pot',
                title: 'Rekening',
                width: 100,
                align: 'left',
                hidden: 'true'
              },
              {
                field: 'nm_rek6',
                title: 'Nama Rekening',
                width: 317
              },
              {
                field: 'nilai',
                title: 'Nilai',
                width: 100,
                align: "right"
              },
              {
                field: 'hapus',
                title: 'Hapus',
                width: 100,
                align: "center",
                formatter: function(value, rec) {
                  return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                }
              }
            ]
          ]
        });
      });
    }

    //tanda
    function validate_jenis_edit($jns_bbn) {
      var beban = document.getElementById('jns_beban').value;
      var skpd = document.getElementById('dn').value;
      $('#cc').combobox({
        url: '<?php echo base_url(); ?>/index.php/spm/load_jenis_beban/' + beban,
      });

      $('#cc').combobox('setValue', jns_bbn);


    }

    function inputnomor() {
      var nomorspm = document.getElementById('no_spm').value;
      $("#spm_pot").attr("value", nomorspm);
    }


    function validate_rek_trans(no_spp) {
      var nospp_pot = document.getElementById('nospp1').value;
      //alert(nospp_pot);
      $(function() {
        $('#rektrans').combogrid({
          panelWidth: 600,
          idField: 'kd_rek5',
          textField: 'kd_rek5',
          mode: 'remote',
          url: '<?php echo base_url(); ?>index.php/spm/rek_pot_trans',
          queryParams: ({
            nospp_pot: nospp_pot
          }),

          columns: [
            [{
                field: 'kd_rek5',
                title: 'NIP',
                width: 200
              },
              {
                field: 'nm_rek5',
                title: 'Nama',
                width: 400
              }
            ]
          ],
          onSelect: function(rowIndex, rowData) {
            $("#nmrektrans").attr("value", rowData.nm_rek5);
            $("#sub_kegiatan").attr("value", rowData.kd_sub_kegiatan);
          }
        });
      });


    }

    function get_spm() {
      var jenis_ls = document.getElementById('jns_beban').value;
      var skpdspm = document.getElementById('dn').value;
      var nospp = $('#nospp').combogrid('getValue');
      var nospm = document.getElementById("no_spm_hide").value;

      if (nospp == '') {
        alert('Pilih terlebih dahulu No SPP');
        return;
      }
      var jns = "";
      var $jns2 = '';
      $("#no_spm").attr("value", '');
      if (jenis_ls == 4) {

        var no = nospp.includes("BTL");
        if (no) {
          $("#no_spm").attr("value", '');
          jns = "BTL";
        } else {
          $("#no_spm").attr("value", '');
          jns = 'GJ';
        }

        //alert(JSON.stringify(rows));  

      } else if (jenis_ls == 6 || jenis_ls == 5) {
        jns = "LS";
      } else if (jenis_ls == 1) {
        jns = "UP";
      } else if (jenis_ls == 2) {
        jns = "GU";
      } else if (jenis_ls == 3) {
        jns = "TU";
      } else if (jenis_ls == 7) {
        jns = "GU-NIHIL";
      }

      if (jenis_ls == 4) {
        $jns2 = 'BTL';
      } else {
        $jns2 = 'BL';
      }

      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spm/config_spm/' + $jns2,
        type: "POST",
        dataType: "json",
        data: ({
          nospm1: nospm
        }),
        success: function(data) {
          no_spm = data.nomor;
          var inisial = no_spm + "/SPM/" + jns + "/" + skpdspm + "/" + tahun_anggaran;
          $("#no_spm").attr("value", inisial);
          $("#spm_pot").attr("value", inisial);
          $("#dd_spm").attr("value", no_spm);

        }
      });
    }

    //copy


    function form_batal() {
      $("#no_spm_batal").attr('disabled', true);
      $("#no_spp_batal").attr('disabled', true);
      document.getElementById("no_spm_batal").value = document.getElementById("no_spm").value;
      $("#no_spp_batal").attr("value", $('#nospp').combogrid('getValue'));

      $("#dialog-batal").dialog('open');
    }

    function keluar_batal() {
      $("#dialog-batal").dialog('close');
    }

    function batal() {
      var no_spm = document.getElementById("no_spm_batal").value;
      var no_spp = document.getElementById("no_spp_batal").value;
      var ket = document.getElementById("ket_batal").value;
      var beban = document.getElementById('jns_beban').value;

      if (no_spp != '') {
        var del = confirm('Anda yakin akan Membatalkan SPM: ' + no_spm + '  ?');
        if (del == true) {
          /*ini untuk delete
          $(document).ready(function(){
                  $.post(urll,({no:sp2d,spm:no_spm}),function(data){
                  status = data;
                  spm_combo(); */
          if (ket == '') {
            alert('Keterangan harus diisi');
            exit();
          }


          $(document).ready(function() {
            $.ajax({
              type: "POST",
              url: '<?php echo base_url(); ?>/index.php/spm/batal_spp',
              data: ({
                nospp: no_spp,
                ket: ket,
                jns_spp: beban
              }),
              dataType: "json",
              success: function(data) {
                status = data;
                if (status == '1') {
                  keluar_batal();
                  alert('SPP - SPM Berhasil Dibatalkan');
                } else {
                  keluar_batal();
                  alert('SPP - SPM Gagal Dibatalkan');
                }
              }
            });
          });
        }
      }
    }
  </script>

  <STYLE TYPE="text/css">
    input.right {
      text-align: right;
    }

    .tooltip {
      position: relative;
      display: inline-block;
      border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
      visibility: hidden;
      width: 160px;
      background-color: black;
      color: #fff;
      text-align: left;
      border-radius: 6px;
      padding: 5px 0;

      /* Position the tooltip */
      position: absolute;
      z-index: 1;
      top: -5px;
      left: 105%;
    }

    .tooltip:hover .tooltiptext {
      visibility: visible;
    }
  </STYLE>

  <style>
    input[type=text],
    select {
      padding: px 20px;
      /*margin: 8px 0;*/
      display: inline-block;
      /*border: 1px solid #ccc;*/
      border-radius: 4px;
      box-sizing: border-box;
    }

    .alert {
      padding: 5px;
      background-color: #ff5555ff;
      color: white;
    }

    .alert-warning {
      padding: 5px;
      background-color: #ffe680ff;
      color: black;
    }
  </style>

</head>



<body>



  <div id="content">
    <div id="accordion">
      <h3><a href="#" id="section1" onclick="javascript:$('#spm').edatagrid('reload')">List SPM</a></h3>
      <div>
        <p align="right">
          <button id="btntambah" class="btn btn-success" onclick="javascript:kosong();section2();"> <i class="fa fa-plus"></i> Tambah</button>
          <button class="btn btn-dark" plain="true" onclick="javascript:cetak();"> <i class="fa fa-print"></i>cetak</button>
          <button class="btn btn-info" plain="true" onclick="javascript:cari();"> <i class="fa fa-search"></i> Cari</button>
          <input type="text" class="form-control" value="" id="txtcari" style="width:175px;" />
        <table id="spm" title="List SPM" style="width:870px;height:450px;">
        </table>
        </p>
      </div>

      <h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')">Input SPM</a></h3>

      <div style="height: 350px;">
        <div class="alert-warning">
          <?php
          if (date("d") <= 13) { ?>
            <B>CATATAN: <br>DI ATAS TANGGAL 13, SPM GU DAN TU TIDAK BISA DIBUAT APABILA BELUM MELENGKAPI KELENGKAPAN SPJ KE AKUNTANSI</B>
          <?php } else { ?>
            <B>CATATAN: <br> SPM GU DAN TU TIDAK BISA DIBUAT APABILA BELUM MELENGKAPI KELENGKAPAN SPJ KE AKUNTANSI</B>
          <?php } ?><br>
          <a href="<?php echo site_url(); ?>/pengumuman/ctk_register_spj/5/1" target="_blank">Cek Daftar Kelengkapan</a>
        </div>
        <div class="alert" id="alerts">
          <!-- <strong>Danger!</strong> -->
          <p id="p2" style="font-size: x-large;color: white;"></p>
          <p id="p1" style="font-size: 16px"></p>
        </div>

        <fieldset style="width:850px;height:850px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">

          <table border='0' width="100%" style="font-size:11px">
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">NO SPP</label><br>
                <input id="nospp" class="form-control" name="nospp" style="width:400px;" />
                <input type="hidden" name="nospp1" id="nospp1" />
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">TANGGAL SPP</label><br>
                <input id="tgl_spp" class="form-control" name="tgl_spp" type="text" readonly="true" style="width:100px;" placeholder="Tanggal SPP" />
              </td>
            </tr>
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">NO SPM</label><br>
                <div class="input-group">
                  <input type="text" class="form-control" name="no_spm" id="no_spm" style="width:300px;" onkeyup="this.value=this.value.toUpperCase(); javascript:inputnomor();" />
                  <div class="input-group-append">
                    <button class="btn btn-info" onclick="javascript:get_spm();"><i class="fa fa-refresh"></i> </button>
                  </div>
                </div>



                <input type="hidden" name="no_spm_hide" id="no_spm_hide" onclick="javascript:select();" style="width:200px;" />
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">TANGGAL SPM</label><br>
                <input id="dd" name="dd" type="text" style="width:100px;" placeholder="Tanggal SPM" />
                <input id="dd_spm" name="dd_spm" type="hidden" />
              </td>
            </tr>
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">NO SPD</label><br>
                <input type="text" id="sp" name="sp" class="form-control" style="width:400px" readonly="true" />
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">TANGGAL SPD</label><br>
                <input id="tgl_spd" name="tgl_spd" class="form-control" type="text" readonly="true" style="width:100px;" placeholder="Tanggal SPD" />
              </td>
            </tr>
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">OPD/UNIT</label><br>
                <input type="text" id="dn" name="dn" class="form-control" style="width:400px" readonly="true" />
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">BULAN</label><br>
                <select name="kebutuhan_bulan" class="form-control" id="kebutuhan_bulan" style="width:400px;">
                  <option value="">...Pilih Kebutuhan Bulan... </option>
                  <option value="1">1 | Januari</option>
                  <option value="2">2 | Februari</option>
                  <option value="3">3 | Maret</option>
                  <option value="4">4 | April</option>
                  <option value="5">5 | Mei</option>
                  <option value="6">6 | Juni</option>
                  <option value="7">7 | Juli</option>
                  <option value="8">8 | Agustus</option>
                  <option value="9">9 | September</option>
                  <option value="10">10 | Oktober</option>
                  <option value="11">11 | November</option>
                  <option value="12">12 | Desember</option>
                </select>
              </td>
            </tr>
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">NAMA OPD/UNTI</label><br>
                <textarea name="nmskpd" id="nmskpd" class="form-control" cols="62" rows="3" style="border: 0;width: 380px;" readonly="true"></textarea>
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">KEPERLUAN</label><br>
                <textarea name="ketentuan" id="ketentuan" cols="62" rows="3"></textarea>
              </td>
            </tr>
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">BEBAN</label><br>
                <select name="jns_beban" id="jns_beban" class="form-control" style="width:400px;">
                  <option value="">...Pilih Jenis Beban... </option>
                  <option value="1">UP</option>
                  <option value="2">GU</option>
                  <option value="3">TU</option>
                  <option value="4">LS GAJI</option>
                  <option value="5">LS Pihak Ketiga Lainnya</option>
                  <option value="6">LS Barang Jasa</option>
                  <option value="7">GU NIHIL</option>
                </select>
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">REKANAN</label><br>
                <textarea id="rekanan" name="rekanan" class="form-control" cols="62" style="width: 400px;" readonly="true"> </textarea>
              </td>
            </tr>
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">JENIS</label><br>
                <input id="cc" name="dept" class="form-control" style="width: 400px;" value=" Pilih Jenis Beban">
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">BANK</label><br>
                <input type="text" name="bank1" id="bank1" style="width:70px;" />
                <input type="text" readonly="true" style="border:hidden;width:330px" id="nama_bank" name="nama_bank" />
              </td>
            </tr>
            <tr>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">NPWP</label><br>
                <input type="text" name="npwp" class="form-control" id="npwp" value="" style="width:400px;" />
              </td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                <label for="fname">REKENING</label><br>
                <input type="text" name="rekening" class="form-control" id="rekening" value="" style="width:400px;" />
              </td>
            </tr>
            <tr>
              <td colspan="2" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                &nbsp;
              </td>
            </tr>
            <tr>
              <td align="center" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" colspan="2">
                <button id="edit-ket" class="btn btn-info" plain="true" onclick="javascript:edit_keterangan();"> <i class="fa fa-pencil"></i> Edit Keterangan</button>
                <button id="save" class="btn btn-primary" plain="true" onclick="javascript:simpan_spm();"> <i class="fa fa-save"></i> Simpan</button>
                <!--<button id="del" class="btn btn-"  plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</button>-->
                <button id="batal" class="btn btn-danger" plain="true" onclick="javascript:form_batal();"> <i class="fa fa-window-close"></i> Batal SPM - SPP</button>
                <!-- Hanya Update Potongan -->
                <button id="update" class="btn btn-success" plain="true" onclick="javascript:Update();">Update Potongan</button>
                <!--    <button id="poto" class="btn btn-"  plain="true" onclick="javascript:cek();">Potongan</button> -->
                <button class="btn btn-warning" plain="true" onclick="javascript:section1();"> <i class="fa fa-arrow-left"></i> Kembali</button>
                <button id="cetak" class="btn btn-dark" plain="true" onclick="javascript:cetak();"> <i class="fa fa-print"></i> cetak</button>
              </td>
            </tr>
          </table>

          <table id="dg" title=" Detail SPM" style="width:850%;height:250%;">
          </table>

          <!--
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rekspm" id="rekspm"  style="width:140px" align="right" readonly="true" >
        <input class="right" type="hidden" name="rekspm1" id="rekspm1"  style="width:100px" align="right" readonly="true" >
        -->

          <table border='0'>

            <tr>
              <td width='400px'></td>
              <td width='220px'></td>
              <td width='240px'></td>
            </tr>

            <tr>
              <td></td>
              <td align='right'><B>Total</B></td>
              <td align="right"><input class="form-control" type="text" name="rekspm" id="rekspm" style="width:200px" align="right" readonly="true">
                <input class="form-control" type="hidden" name="rekspm1" id="rekspm1" style="width:100px" align="right" readonly="true">
              </td>
            </tr>
          </table>
          </p>

          <!--dari sini -->

          <fieldset>
            <table border='0' style="font-size:11px">
              <tr>
                <td>No. SPM</td>
                <td>:</td>
                <td><input type="text" id="spm_pot" class="form-control" name="spm_pot" style="width:200px;" /></td>
              </tr>
              <tr>
                <td>Rekening Transaksi</td>
                <td>:</td>
                <td><input type="text" id="rektrans" name="rektrans" style="width:200px;" />
                  <input type="hidden" id="sub_kegiatan" name="sub_kegiatan" style="width:200px;" />
                </td>
                <td><input type="text" id="nmrektrans" name="nmrektrans" readonly="true" style="width:400px;border:0px;" /></td>
              </tr>
              <tr>
                <td>Rekening Potongan</td>
                <td>:</td>
                <td><input type="text" id="rekpajak" name="rekpajak" style="width:200px;" /><input type="hidden" id="map_pot" name="map_pot" style="width:200px;" /></td>
                <td><input type="text" id="nmrekpajak" name="nmrekpajak" style="width:400px;border:0px;" readonly="true" /></td>
              </tr>
              <tr>
                <td align="left">Nilai</td>
                <td>:</td>
                <td><input type="text" class="form-control" id="nilairekpajak" name="nilairekpajak" style="width:200px;text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="4" align="center">
                  <button id="save-pot" class="btn btn-primary" plain="true" onclick="javascript:append_save();">
                    <font color="oranga"> <i class="fa fa-plus"></i></font> Tambah Potongan
                  </button>
                </td>
              </tr>
            </table>
          </fieldset>

          &nbsp;&nbsp;

          <table id="dgpajak" title="List Potongan" style="width:850px;height:300px;">
          </table>

          <table border='0' style="font-size:11px;width:850px;height:30px;">
            <tr>
              <td width='50%'></td>
              <td width='20%' align="right">Total</td>
              <td width='30%'><input type="text" id="totalrekpajak" name="totalrekpajak" style="width:250px;text-align:right;" /></td>
            </tr>
          </table>


          <!--Sampai sini -->
        </fieldset>
      </div>

    </div>
  </div>

  <div id="dialog-modal" title="CETAK SPM">
    <p class="validateTips">SILAHKAN PILIH SPM</p>
    <fieldset>
      <table border="0" width="100%">
        <tr>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">NO SPM</label><br>
            <input id="cspm" name="cspm" style="width: 170px;" disabled />&nbsp; &nbsp; &nbsp; <input type="checkbox" id="tanpa_tanggal"> Tanpa Tanggal
          </td>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">Kelengkapan</label><br>
            <button class="button-orange" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm5/1');return false;"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="button-hitam" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm5/0');return false;"><i class="fa fa-television"></i> Layar</button>
            <button class="button-cerah" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm5/2');return false;"><i class="fa fa-download"></i> Download</button>
          </td>
        </tr>
        <tr>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">Bendahara Pengeluaran</label><br>
            <input id="ttd1" name="ttd1" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd1" name="nmttd1" style="width: 170px;border:0" />
          </td>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">BERKAS SPM</label><br>
            <button class="button-orange" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetak_spm/1');return false;"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="button-hitam" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetak_spm/0');return false;"><i class="fa fa-television"></i> Layar</button>
            <button class="button-cerah" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetak_spm/2');return false;"><i class="fa fa-download"></i> Download</button>
          </td>
        </tr>
        <tr>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">PPTK/PPK</label><br>
            <input id="ttd2" name="ttd2" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd2" name="nmttd2" style="width: 170px;border:0" />
          </td>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">Ringkasan</label><br>
            <button class="button-orange" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm2/1');return false;"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="button-hitam" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm2/0');return false;"><i class="fa fa-television"></i> Layar</button>
            <button class="button-cerah" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm2/2');return false;"><i class="fa fa-download"></i> Download</button>
          </td>
        </tr>
        <tr>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">PA</label><br>
            <input id="ttd3" name="ttd3" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd3" name="nmttd3" style="width: 170px;border:0" />
          </td>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">Pengantar</label><br>
            <button class="button-orange" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm1/1');return false;"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="button-hitam" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm1/0');return false;"><i class="fa fa-television"></i> Layar</button>
            <button class="button-cerah" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm1/2');return false;"><i class="fa fa-download"></i> Download</button>
          </td>
        </tr>
        <tr>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">PPKD</label><br>
            <input id="ttd4" name="ttd4" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd4" name="nmttd4" style="width: 170px;border:0" />
          </td>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">Lampiran</label><br>
            <button class="button-orange" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm4/1');return false;"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="button-hitam" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm4/0');return false;"><i class="fa fa-television"></i> Layar</button>
            <button class="button-cerah" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm4/2');return false;"><i class="fa fa-download"></i> Download</button>
          </td>
        </tr>
        <tr>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <div id="buat" name="buat">
              <label for="fname">JENIS</label><br>
              <select name="jns_ls" id="jns_ls" style="width:400px;">
                <option value="" selected="selected">...Pilih Jenis LS... </option>
                <option value="1">Gaji Induk, Gaji Terusan, Kekurangan Gaji</option>
                <option value="2">Gaji Susulan</option>
                <option value="3">Tambahan Penghasilan</option>
                <option value="4">Honorarium PNS</option>
                <option value="5">Honorarium Tenaga Kontrak</option>
                <option value="6">Pengadaan Barang dan Jasa/Konstruksi/Konsultansi</option>
                <option value="7">Pengadaan Konsumsi</option>
                <option value="8">Sewa Rumah Jabatan/Gedung untuk Kantor/Gedung Pertemuan/Tempat Pertemuan/Tempat Penginapan/Kendaraan</option>
                <option value="9">Pengadaan Sertifikat Tanah</option>
                <option value="10">Pengadaan Tanah</option>
                <option value="11">Hibah Barang dan Jasa pada Pihak Ketiga</option>
                <option value="16">Hibah Konstruksi pada Pihak Ketiga</option>
                <option value="12">LS Bantuan Sosial pada Pihak Ketiga</option>
                <option value="13">Hibah Uang Pada Pihak Ketiga</option>
                <option value="14">Bantuan Keuangan Pada Kabupaten/Kota</option>
                <option value="15">Bagi Hasil Pajak dan Bukan Pajak</option>
                <option value="98">Belanja Operasional KDH/WKDH dan Pimpinan DPRD</option>
                <option value="99">Pembiayaan pada Pihak Ketiga Lainnya</option>
                <option value="100">GU NIHIL</option>
              </select>
            </div>
          </td>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">Tanggung Jawab SPM</label><br>
            <button class="button-orange" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/jawab_spm/1');return false;"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="button-hitam" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/jawab_spm/0');return false;"><i class="fa fa-television"></i> Layar</button>
            <button class="button-cerah" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/jawab_spm/2');return false;"><i class="fa fa-download"></i> Download</button>
          </td>
        </tr>
        <tr>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            Baris SPM : &nbsp; <input type="number" id="baris" name="baris" style="width: 30px;border:0" value="15" /> &nbsp;<button class="button-kuning" plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Keluar</button>
          </td>
          <td width="50%%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
            <label for="fname">Pernyataan</label><br>
            <button class="button-orange" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm6/1');return false;"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="button-hitam" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm6/0');return false;"><i class="fa fa-television"></i> Layar</button>
            <button class="button-cerah" plain="true" onclick="javascript:openWindow('<?php echo site_url(); ?>spm/cetakspm6/2');return false;"><i class="fa fa-download"></i> Download</button>
          </td>
        </tr>
      </table>


    </fieldset>
    <br />


  </div>
</body>

<div id="dialog-batal" title="KETERANGAN PEMBATALAN SPM">
  <p class="validateTips">KETERANGAN PEMBATALAN SPM</p>
  <fieldset>
    <table>
      <tr>
        <td width="110px">NO SPM:</td>
        <td><input id="no_spm_batal" name="no_spm_batal" style="width: 170px;" readonly="true" /></td>
      </tr>
      <tr>
        <td width="110px">NO SPP:</td>
        <td><input id="no_spp_batal" name="no_spp_batal" style="width: 170px;" readonly="true" /></td>
      </tr>
      <tr>
        <td width="110px">KETERANGAN PEMBATALAN SPM:</td>
        <td><textarea name="ket_batal" id="ket_batal" cols="70" rows="2"></textarea></td>
      </tr>
    </table>
  </fieldset>
  <button id="del1" class="btn btn-danger" iconCls="icon-remove" plain="true" onclick="javascript:batal();javascript:section1();">BATAL</button>
  <button class="btn btn-warning" iconCls="icon-undo" plain="true" onclick="javascript:keluar_batal();">Keluar</button>
</div>

</body>

</html>