<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>

  <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
  <style>
    #tagih {
      position: relative;
      width: 700px;
      height: 70px;
      padding: 0.4em;
    }
  </style>

  <script type="text/javascript">
    var nl = 0;
    var tnl = 0;
    var idx = 0;
    var tidx = 0;
    var oldRek = 0;
    var rek = 0;
    var kode = '';
    var pidx = 0;
    var frek = '';
    var rek5 = '';
    var edit = '';
    var lcstatus = '';
    var n_totalspd = 0;

    $(document).ready(function() {
      $("#accordion").accordion({
        height: 600
      });
      $("#lockscreen").hide();
      $("#frm").hide();
      $("#dialog-modal").dialog({
        height: 500,
        width: 700,
        modal: true,
        autoOpen: false
      });
      $("#dialog-modal-rek").dialog({
        height: 450,
        width: 1100,
        modal: true,
        autoOpen: false
      });
      $("#tagih").hide();
      $("#loading").hide();
      get_skpd();
      get_tahun();

    });


    $(function() {
      $('#dd').datebox({
        required: true,
        formatter: function(date) {
          var y = date.getFullYear();
          var m = date.getMonth() + 1;
          var d = date.getDate();
          return y + '-' + m + '-' + d;
        },
        onSelect: function(date) {
          var m = date.getMonth() + 1;
          $("#kebutuhan_bulan").attr('value', m);
          cek_status_ang();
          cek_status_angkas();
          $("#kg").combogrid("setValue", '');
          $("#sp").combogrid("setValue", '');
          $("#nm_kg").attr("Value", '');
          $("#tglspd").datebox("setValue", '');
        }
      });

      $('#tgl_mulai').datebox({
        required: true,
        formatter: function(date) {
          var y = date.getFullYear();
          var m = date.getMonth() + 1;
          var d = date.getDate();
          return y + '-' + m + '-' + d;
        }
      });

      $('#tgl_akhir').datebox({
        required: true,
        formatter: function(date) {
          var y = date.getFullYear();
          var m = date.getMonth() + 1;
          var d = date.getDate();
          return y + '-' + m + '-' + d;
        }
      });

      $('#tgl_ttd').datebox({
        required: true,
        formatter: function(date) {
          var y = date.getFullYear();
          var m = date.getMonth() + 1;
          var d = date.getDate();
          return y + '-' + m + '-' + d;
        }
      });

      $('#rekanan').combogrid({});

      $('#tglspd').datebox({
        required: true,
        formatter: function(date) {
          var y = date.getFullYear();
          var m = date.getMonth() + 1;
          var d = date.getDate();
          return y + '-' + m + '-' + d;
        }
      });


      $('#rek_skpd').combogrid({
        panelWidth: 700,
        idField: 'kd_skpd',
        textField: 'kd_skpd',
        mode: 'remote',
        columns: [
          [{
              field: 'kd_skpd',
              title: 'Kode SKPD',
              width: 100
            },
            {
              field: 'nm_skpd',
              title: 'Nama SKPD',
              width: 700
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          kode = rowData.kd_skpd;
          $("#rek_nmskpd").attr("value", rowData.nm_skpd.toUpperCase());
        }
      });


      $('#cspp').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spp/load_spp_tu',
        idField: 'no_spp',
        textField: 'no_spp',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'no_spp',
              title: 'SPP',
              width: 60
            },
            {
              field: 'kd_skpd',
              title: 'SKPD',
              align: 'left',
              width: 60
            },
            {
              field: 'tgl_spp',
              title: 'Tanggal',
              width: 60
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          nomer = rowData.no_spp;
          kode = rowData.kd_skpd;
          jns = rowData.jns_spp;
        }
      });

      $('#cc').combobox({});

      $('#ttd1').combogrid({
        panelWidth: 600,
        idField: 'nip',
        textField: 'nip',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/spp/load_ttd/BK',
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
        url: '<?php echo base_url(); ?>index.php/spp/load_ttd/PPTK',
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
        url: '<?php echo base_url(); ?>index.php/spp/load_ttd/PA',
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
        url: '<?php echo base_url(); ?>index.php/spp/load_ttdppkd/BUD',
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

      $('#notagih').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spp/load_no_penagihan',
        idField: 'no_tagih',
        textField: 'no_tagih',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'no_tagih',
              title: 'No Penagihan',
              width: 140
            },
            {
              field: 'tgl_tagih',
              title: 'Tanggal',
              width: 140
            },
            {
              field: 'kd_skpd',
              title: 'SKPD',
              width: 140
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          var ststagih = '1';
          no_tagih = rowData.no_tagih;
          $("#tgltagih").attr("value", rowData.tgl_tagih);
          $("#nil").attr("value", rowData.nila);
          $("#ni").attr("value", rowData.nil);
          $("#ketentuan").attr("Value", rowData.ket);
          $("#kg").combogrid("setValue", rowData.kegiatan);

          detail_tagih(no_tagih);
          $("#rektotal_ls").attr('value', rowData.nila);
          $("#rektotal1_ls").attr('value', rowData.nil);
          get_skpd();
        }
      });

      $('#bank1').combogrid({
        panelWidth: 700,
        url: '<?php echo base_url(); ?>/index.php/spp/config_bank2',
        idField: 'kd_bank',
        textField: 'kd_bank',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'kd_bank',
              title: 'Kd Bank',
              width: 150
            },
            {
              field: 'nama_bank',
              title: 'Nama',
              width: 500
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          $("#nama_bank").attr("value", rowData.nama_bank);
        }
      });

      $('#spp').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spp/load_spp_tu',
        idField: 'id',
        rownumbers: "true",
        fitColumns: "true",
        singleSelect: "true",
        autoRowHeight: "false",
        loadMsg: "Tunggu Sebentar....!!",
        pagination: "true",
        nowrap: "true",
        rowStyler: function(index, row) {
          if (row.status == "1") {
            return 'background-color:#03d3ff;';
          }
        },
        columns: [
          [{
              field: 'no_spp',
              title: 'Nomor SPP',
              width: 40
            },
            {
              field: 'tgl_spp',
              title: 'Tanggal',
              width: 25
            },
            {
              field: 'kd_skpd',
              title: 'Nama SKPD',
              width: 25,
              align: "left"
            },
            {
              field: 'keperluan',
              title: 'Keterangan',
              width: 140,
              align: "left"
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          urut = rowData.urut;
          no_spp = rowData.no_spp;
          kode = rowData.kd_skpd;
          sp = rowData.no_spd;
          bl = rowData.bulan;
          tg = rowData.tgl_spp;
          jn = rowData.jns_spp;
          kep = rowData.keperluan;
          bk = rowData.bank;
          ning = rowData.no_rek;
          status = rowData.status;
          kegi = rowData.kd_sub_kegiatan;
          // alert(kegi);
          nmgiat = rowData.nm_sub_kegiatan;
          // alert(nmgiat);
          kprog = rowData.kd_program;
          nprog = rowData.nm_program;
          tot_spp = rowData.tot_spp_;
          get(urut, no_spp, kode, sp, tg, bl, jn, kep, bk, ning, status, kegi, nmgiat, kprog, nprog, tot_spp);

          //det();       
          detail_trans_3();
          //validate_kegiatan() ;
          load_sum_spp();
          edit = 'T';
          lcstatus = 'edit';
        },
        onDblClickRow: function(rowIndex, rowData) {
          section2();
        }
      });

      $('#sp').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spp/spd1',
        idField: 'no_spd',
        textField: 'no_spd',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'no_spd',
              title: 'No SPD',
              width: 70
            },
            {
              field: 'tgl_spd2',
              title: 'Tanggal',
              align: 'left',
              width: 30
            },
            {
              field: 'nilai',
              title: 'Nilai',
              align: 'right',
              width: 40
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          spd = rowData.no_spd;
          tglspd = rowData.tgl_spd;
          $("#tglspd").datebox("setValue", tglspd);
          validate_kegi(spd);
        }
      });

      // Hakam

      $('#kg').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spp/kegi',
        idField: 'kd_sub_kegiatan',
        textField: 'kd_sub_kegiatan',
        mode: 'remote',
        fitColumns: true,
        columns: [
          [{
              field: 'kd_sub_kegiatan',
              title: 'Kode',
              width: 30
            },
            {
              field: 'nm_sub_kegiatan',
              title: 'Nama',
              align: 'left',
              width: 70
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {

          kegi = rowData.kd_sub_kegiatan;
          nmkegi = rowData.nm_sub_kegiatan;
          $("#nm_kg").attr("value", rowData.nm_sub_kegiatan);
          prog = rowData.kd_program;
          $("#kp").attr("value", rowData.kd_program);
          nmprog = rowData.nm_program;
          $("#nm_kp").attr("value", rowData.nm_program);
          nilai = rowData.nilai;
          det();
          validate_rekening(kegi);
        }
      });


      $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spp/select_data2',
        autoRowHeight: "true",
        idField: 'id',
        toolbar: "#toolbar",
        rownumbers: "true",
        fitColumns: false,
        singleSelect: "true",
      });


      $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
        autoRowHeight: "true",
        idField: 'id',
        toolbar: "#toolbar",
        rownumbers: "true",
        fitColumns: false,
        singleSelect: "true",
      });


      $('#dgsppls').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
        idField: 'idx',
        toolbar: "#toolbar",
        rownumbers: "true",
        fitColumns: false,
        autoRowHeight: "false",
        singleSelect: "true",
        nowrap: "false",
        columns: [
          [{
              field: 'idx',
              title: 'idx',
              width: 100,
              align: 'left',
              hidden: 'true'
            },
            {
              field: 'kdsubkegiatan',
              title: 'Kegiatan',
              width: 150,
              align: 'left'
            },
            {
              field: 'kdsubkegiatan',
              title: 'Kegiatan',
              width: 150,
              align: 'left'
            },
            {
              field: 'kdrek6',
              title: 'Rekening',
              width: 70,
              align: 'left'
            },
            {
              field: 'nmrek6',
              title: 'Nama Rekening',
              width: 280
            },
            {
              field: 'nilai1',
              title: 'Nilai',
              width: 140,
              align: 'right'
            },
            {
              field: 'sumber',
              title: 'sumber',
              width: 140,
              align: 'right'
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




      $('#rek_kegi').combogrid({
        panelWidth: 700,
        idField: 'kd_sub_kegiatan',
        textField: 'kd_sub_kegiatan',
        mode: 'remote',
        columns: [
          [{
              field: 'kd_sub_kegiatan',
              title: 'Kode Kegiatan',
              width: 150
            },
            {
              field: 'nm_sub_kegiatan',
              title: 'Nama Kegiatan',
              width: 700
            }
          ]
        ]
      });


      $('#rek_reke').combogrid({
        panelWidth: 700,
        idField: 'kd_rek5',
        textField: 'kd_rek5',
        mode: 'remote',
        columns: [
          [{
              field: 'kd_rek5',
              title: 'Kode Rekening',
              width: 150
            },
            {
              field: 'nm_rek5',
              title: 'Nama Rekening',
              width: 700
            }
          ]
        ]
      });


      $('#sumber_dn').combogrid({
        panelWidth: 200,
        idField: 'sumber_dana',
        textField: 'sumber_dana',
        mode: 'remote',
        columns: [
          [{
            field: 'sumber_dana',
            title: 'Sumber Dana',
            width: 180
          }]
        ]
      });

    });



    // function get_spp(){
    //   var jenis_ls = document.getElementById('jns_beban').value;
    //   var bulan_spp = document.getElementById('kebutuhan_bulan').value;
    //   var jns_spp = 'TU';

    //   $.ajax({
    //     url:'<?php echo base_url(); ?>index.php/spp/config_spp/'+bulan_spp+'/'+jenis_ls+'/'+jns_spp,
    //     type: "POST",
    //     dataType:"json",                         
    //     success:function(data){
    //       no_spp = data.nomor;
    //       var inisial = "13.07/.02.0/"+no_spp+"/"+jns_spp+"/"+kode+"/M/"+bulan_spp+"/"+tahun_anggaran;
    //       $("#no_spp").attr('disabled',true);
    //       $("#no_spp").attr("value",inisial);
    //       $("#dd_spp").attr("value",no_spp);
    //     }                                     
    //   });
    // }

    function get_spp() {
      var jenis_ls = document.getElementById('jns_beban').value;
      var jns = 'TU';
      var bulan_spp = document.getElementById('kebutuhan_bulan').value;


      $("#ketentuan").attr('disabled', true);
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spp/config_spp/' + bulan_spp + '/' + jenis_ls + '/' + jns,
        type: "POST",
        dataType: "json",
        success: function(data) {
          no_spp = data.nomor;
          var inisial = "13.07/02.0/" + no_spp + "/" + jns + "/" + kode + "/M/" + bulan_spp + "/" + tahun_anggaran;
          $("#no_spp").attr("value", inisial);
          $("#dd_spp").attr("value", no_spp);
        }
      });
    }

    function get_skpd() {
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
        type: "POST",
        dataType: "json",
        success: function(data) {
          kode = data.kd_skpd;
          $("#dn").attr("value", data.kd_skpd);
          $("#rek_skpd").combogrid("setValue", data.kd_skpd);
          $("#nmskpd").attr("value", data.nm_skpd);
          $("#rek_skpd").combogrid("setValue", data.kd_skpd);
          $("#rek_nmskpd").attr("value", data.nm_skpd.toUpperCase());

        }
      });
    }

    function get_tahun() {
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spp/config_tahun',
        type: "POST",
        dataType: "json",
        success: function(data) {
          tahun_anggaran = data;
        }
      });
    }

    function cek_status_ang() {
      var tgl_cek = $('#dd').datebox('getValue');
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spp/cek_status_ang',
        data: ({
          tgl_cek: tgl_cek
        }),
        type: "POST",
        dataType: "json",
        success: function(data) {
          $("#status_ang").attr("value", data.nm_ang);
        }
      });
    }

    function data_notagih() {
      $('#notagih').combogrid({
        url: '<?php echo base_url(); ?>/index.php/spp/load_no_penagihan'
      });
    }

    function detail_tagih(no_tagih) {
      $(function() {
        $('#dgsppls').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spp/select_data_tagih',
          queryParams: ({
            no: no_tagih
          }),
          idField: 'idx',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "false",
          singleSelect: "true",
          nowrap: "true",
          onLoadSuccess: function(data) {},
          onSelect: function(rowIndex, rowData) {

            kd = rowIndex;
            idx = rowData.idx;
            tkdkegiatan = rowData.kdkegiatan;
            tnmkegiatan = rowData.tnmkegiatan;
            tkdrek5 = rowData.kdrek5;
            tnmrek5 = rowData.nmrek5;
            tnilai1 = rowData.nilai1;
            tsumber = rowData.tsumber;


          },
          columns: [
            [{
                field: 'idx',
                title: 'idx',
                width: 100,
                align: 'left',
                hidden: 'true'
              },
              {
                field: 'kdkegiatan',
                title: 'Kegiatan',
                width: 180,
                align: 'left'
              },
              {
                field: 'nmkegiatan',
                title: 'Nama',
                width: 180,
                align: 'left',
                hidden: 'true'
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
                width: 280
              },
              {
                field: 'sumber',
                title: 'sumber',
                width: 280,
                hidden: 'true'
              },
              {
                field: 'nilai1',
                title: 'Nilai',
                width: 140,
                align: 'right'
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

    function validate_kegiatan() {
      var kode_s = document.getElementById('dn').value;
      $(function() {
        $('#rek_kegi').combogrid({
          panelWidth: 700,
          idField: 'kd_sub_kegiatan',
          textField: 'kd_sub_kegiatan',
          mode: 'remote',
          url: '<?php echo base_url(); ?>index.php/spp/load_trskpd_ar_tu',
          queryParams: ({
            kdskpd: kode_s
          }),
          columns: [
            [{
                field: 'kd_sub_kegiatan',
                title: 'Kode Kegiatan',
                width: 150
              },
              {
                field: 'nm_sub_kegiatan',
                title: 'Nama Kegiatan',
                width: 700
              }
            ]
          ],
          onSelect: function(rowIndex, rowData) {
            $("#nm_rek_kegi").attr("value", rowData.nm_sub_kegiatan);
            $("#rek_reke").combogrid("setValue", '');



            kd_kegia = rowData.kd_sub_kegiatan;
            validate_rekening(kd_kegia);

          }
        });
      });
    }


    function validasi_sumber_dana() {

      var kode_keg = $('#rek_kegi').combogrid('getValue');
      var koderek = $('#rek_reke').combogrid('getValue');

      $(function() {
        $('#sumber_dn').combogrid({
          panelWidth: 200,
          idField: 'sumber_dana',
          textField: 'sumber_dana',
          mode: 'remote',
          url: '<?php echo base_url(); ?>index.php/spp/load_reksumber_dana',
          queryParams: ({
            giat: kode_keg,
            kd: kode,
            rek: koderek
          }),
          columns: [
            [{
              field: 'sumber_dana',
              title: 'Sumber Dana',
              width: 180
            }]
          ],
          onSelect: function(rowIndex, rowData) {
            var parsumber = rowData.sumber_dana;
            var vnilaidana = rowData.nilaidana;
            // var vnilaidana_semp = rowData.nilaidana_semp;
            // var vnilaidana_ubah = rowData.nilaidana_ubah;                                                                               
            var lalu_ubahspp = angka(document.getElementById('rek_nilai_spp').value);

            $("#rek_nilai_ang_dana").attr("Value", number_format(vnilaidana, 2, '.', ','));
            $("#rek_nilai_spp_dana").attr("Value", number_format(lalu_ubahspp, 2, '.', ','));
            // $("#rek_nilai_ang_semp_dana").attr("Value",number_format(vnilaidana_semp,2,'.',','));
            // $("#rek_nilai_spp_semp_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));
            // $("#rek_nilai_ang_ubah_dana").attr("Value",number_format(vnilaidana_ubah,2,'.',','));
            // $("#rek_nilai_spp_ubah_dana").attr("Value",number_format(lalu_ubahspp,2,'.',','));  

            var sisa_nil_dana = vnilaidana - lalu_ubahspp;
            // var sisa_nil_semp_dana = vnilaidana_semp-lalu_ubahspp;
            // var sisa_nil_ubah_dana = vnilaidana_ubah-lalu_ubahspp;

            $("#rek_nilai_sisa_dana").attr("Value", number_format(sisa_nil_dana, 2, '.', ','));
            // $("#rek_nilai_sisa_semp_dana").attr("Value",number_format(sisa_nil_semp_dana,2,'.',','));
            // $("#rek_nilai_sisa_ubah_dana").attr("Value",number_format(sisa_nil_ubah_dana,2,'.',','));                           
          }
        });
      });
    }

    function validate_rekening(kegiatan = '') {
      //alert("asd");
      $('#dgsppls').datagrid('selectAll');
      var rows = $('#dgsppls').datagrid('getSelections');
      frek = '';
      rek5 = '';
      for (var p = 0; p < rows.length; p++) {
        rek5 = rows[p].kdrek5;
        if (p > 0) {
          frek = frek + ',' + rek5;
        } else {
          frek = rek5;
        }
      }

      var beban = document.getElementById('jns_beban').value;
      var kode_s = document.getElementById('dn').value;
      var kode_keg = $('#rek_kegi').combogrid('getValue');
      var tgl_spp = $('#dd').datebox('getValue');
      var spdss = $('#sp').combogrid('getValue');
      var status_angkas = document.getElementById('status_angkas').value;

      var nospp = document.getElementById('no_spp').value;

      $(function() {
        $('#rek_reke').combogrid({
          panelWidth: 700,
          idField: 'kd_rek6',
          textField: 'kd_rek6',
          mode: 'remote',
          url: '<?php echo base_url(); ?>index.php/spp/load_rek_ar',
          queryParams: ({
            kdkegiatan: kegiatan,
            kdrek: frek,
            bbn: beban
          }),
          columns: [
            [{
                field: 'kd_rek6',
                title: 'Kode Rekening',
                width: 150
              },
              {
                field: 'nm_rek6',
                title: 'Nama Rekening',
                width: 700
              }
            ]
          ],
          onSelect: function(rowIndex, rowData) {

            $("#nm_rek_reke").attr("value", rowData.nm_rek6);
            var koderek = rowData.kd_rek6;
            //  alert(koderek);

            $.ajax({
              type: "POST",
              dataType: "json",
              data: ({
                kegiatan: kegiatan,
                kdrek6: koderek,
                kd_skpd: kode_s,
                no_spp: nospp
              }),
              url: '<?php echo base_url(); ?>index.php/spp/jumlah_ang_spp',
              success: function(data) {
                $.each(data, function(i, n) {
                  $("#rek_nilai_ang").attr("Value", n['nilai']);
                  $("#rek_nilai_spp").attr("Value", n['nilai_spp_lalu']);
                  // $("#rek_nilai_ang_semp").attr("Value",n['nilai_sempurna']);
                  // $("#rek_nilai_spp_semp").attr("Value",n['nilai_spp_lalu']);
                  // $("#rek_nilai_ang_ubah").attr("Value",n['nilai_ubah']);
                  // $("#rek_nilai_spp_ubah").attr("Value",n['nilai_spp_lalu']);
                  $("#sumber_dn").combogrid('setValue', '');
                  var n_ang = n['nilai'];
                  var n_ang_semp = n['nilai_sempurna'];
                  var n_ang_ubah = n['nilai_ubah'];
                  var n_spp = n['nilai_spp_lalu'];
                  var n_sisa = angka(n_ang) - angka(n_spp);
                  // var n_sisa_semp = angka(n_ang_semp) - angka(n_spp) ;
                  // var n_sisa_ubah = angka(n_ang_ubah) - angka(n_spp) ;
                  $("#rek_nilai_sisa").attr("Value", number_format(n_sisa, 2, '.', ','));
                  // $("#rek_nilai_sisa_semp").attr("Value",number_format(n_sisa_semp,2,'.',','));
                  // $("#rek_nilai_sisa_ubah").attr("Value",number_format(n_sisa_ubah,2,'.',','));
                  validasi_sumber_dana();

                  cek_status_ang();
                  var tgl_spd = $('#tglspd').datebox('getValue');
                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: ({
                      kegiatan: kegiatan,
                      kd_skpd: kode_s,
                      tglspd: tgl_spd,
                      kdrek6: koderek,
                      beban: beban,
                      tgl_spp: tgl_spp
                    }),
                    url: '<?php echo base_url(); ?>index.php/spp/total_spd',
                    success: function(data) {
                      $.each(data, function(i, n) {
                        $("#total_spd").attr("Value", n['nilai']);
                        n_totalspd = n['nilai'];

                      });
                    }
                  });

                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {
                      kegiatan: kode_keg,
                      kd_skpd: kode_s,
                      tglspd: tgl_spd,
                      kdrek6: koderek,
                      beban: beban,
                      tglspp: tgl_spp,
                      status_angkas: status_angkas,
                      spd: spdss
                    },
                    url: '<?php echo base_url(); ?>index.php/spp/total_angkas',
                    success: function(data) {
                      $.each(data, function(i, n) {
                        $("#total_angkas").attr("Value", n['nilai']);
                        var n_totalangkas = n['nilai'];
                        // var n_sisa = angka(n_ang) - angka(n_spp) ;
                        // $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
                      });
                    }
                  });

                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: ({
                      giat: kode_keg,
                      kode: kode_s,
                      spd: spdss,
                      kdrek6: koderek,
                      beban: beban
                    }),
                    url: '<?php echo base_url(); ?>index.php/spp/load_total_trans_angkas',
                    success: function(data) {
                      $.each(data, function(i, n) {
                        $("#nilai_angkas_lalu").attr("Value", n['total']);
                        var n_angkaslalu = n['total'];
                        var total_angkas = document.getElementById('total_angkas').value;
                        var n_sisaangkas = angka(total_angkas) - angka(n_angkaslalu);
                        $("#nilai_sisa_angkas").attr("Value", number_format(n_sisaangkas, 2, '.', ','));
                      });
                    }
                  });


                  $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: ({
                      giat: kegiatan,
                      kode: kode_s,
                      kdrek6: koderek
                    }),
                    url: '<?php echo base_url(); ?>index.php/spp/load_total_trans_spd',
                    success: function(data) {
                      $.each(data, function(i, n) {
                        $("#nilai_spd_lalu").attr("Value", n['total']);
                        var n_spdlalu = n['total'];
                        var total_spd = n_totalspd;
                        var n_sisaspd = angka(total_spd) - angka(n_spdlalu);
                        $("#nilai_sisa_spd").attr("Value", number_format(n_sisaspd, 2, '.', ','));
                      });
                    }
                  });
                });
              }
            });
          }
        });
      });
      $('#dgsppls').datagrid('unselectAll');
    }


    function cek_status_angkas() {
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spp/cek_status_angkas',
        type: "POST",
        dataType: "json",
        success: function(data) {
          var kode = data.nama;
          // alert(kode);
          $("#status_angkas").attr("value", kode);
          validate_rekening(kode);
        }
      });
    }


    function validate_kegi(spd) {
      var cskpd = document.getElementById('dn').value;
      var tgl_spp = $('#dd').datebox('getValue');

      /*if(tgl_spp==''){
          alert('Tanggal SPP tidak sama dengan hari ini');
          exit();
        }*/

      if (cskpd != '') { //hapus jika selesai 
        $(function() {
          $('#kg').combogrid({
            panelWidth: 500,
            url: '<?php echo base_url(); ?>/index.php/spp/kegiatan_spd_tu',
            queryParams: ({
              spd: spd,
              tgl_spp: tgl_spp
            }),
            idField: 'kd_sub_kegiatan',
            textField: 'kd_sub_kegiatan',
            mode: 'remote',
            fitColumns: true
          });
        });
      } else { //hapus jika selesai 
        $(function() {
          $('#kg').combogrid({
            panelWidth: 500,
            url: '<?php echo base_url(); ?>/index.php/tukd/kegiatan_spd',
            queryParams: ({
              spd: spd,
              tgl_spp: tgl_spp
            }),
            idField: 'kd_sub_kegiatan',
            textField: 'kd_sub_kegiatan',
            mode: 'remote',
            fitColumns: true
          });
        });

      }
    }


    function det() {
      $(function() {
        $('#dg').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/tukd/select_data2',
          queryParams: ({
            giat: kegi
          }),
          idField: 'id',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "true",
          singleSelect: false,
          onLoadSuccess: function(data) {
            detail1();
          },
          onClickRow: function(rowIndex, rowData) {
            keg = rowData.kd_kegiatan;
            rk = rowData.kd_rek5;
            nkeg = rowData.nm_kegiatan;
            nrek = rowData.nm_rek5;
            ang = rowData.a;
            kel = rowData.b;
            sisa = ang - kel;
            simpan(keg, rk, nkeg, nrek, sisa);
            detail1();
          },
          columns: [
            [{
                field: 'ck',
                title: 'ck',
                checkbox: true,
                hidden: true
              },
              {
                field: 'pilih',
                title: 'pilih',
                width: 20,
                align: 'center',
                checkbox: true,
                hidden: true
              },
              {
                field: 'kd_kegiatan',
                title: 'Kegiatan',
                width: 150,
                align: 'left'
              },
              {
                field: 'kd_rek5',
                title: 'Rekening',
                width: 70,
                align: 'left'
              },
              {
                field: 'nm_rek5',
                title: 'Nama Rekening',
                width: 300
              },
              {
                field: 'a',
                title: 'Nilai Anggaran',
                width: 100,
                align: 'right',
                hidden: true
              },
              {
                field: 'b',
                title: 'SPP Lalu',
                width: 100,
                align: 'right',
                hidden: true
              },
              {
                field: 'nilai',
                title: 'Nilai Anggaran',
                width: 100,
                align: 'right'
              },
              {
                field: 'total',
                title: 'SPP Lalu',
                width: 100,
                align: 'right'
              }
            ]
          ]
        });
      });
    }



    function detail1() {
      $(function() {
        var spp = document.getElementById('no_spp').value;
        $('#dg1').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
          queryParams: ({
            spp: spp
          }),
          idField: 'idx',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "true",
          singleSelect: false,
          onLoadSuccess: function(data) {
            //load_sum_spp();                        
          },
          onSelect: function(rowIndex, rowData) {
            kd = rowIndex;
          },
          onAfterEdit: function(rowIndex, rowData, changes) {
            skegiatan = rowData.kdsubkegiatan;
            nskegiatan = rowData.nmsubkegiatan;
            rekeing = rowData.kdrek6;
            nrekeing = rowData.nmrek6;
            nilai = rowData.nilai1;
            si = rowData.sis;
            kd = rowIndex;
            dsimpan(skegiatan, rekeing, nskegiatan, nrekeing, nilai, si, kd);
          },
          columns: [
            [{
                field: 'ck',
                title: 'ck',
                checkbox: true,
                hidden: true
              },
              {
                field: 'kdsubkegiatan',
                title: 'Sub Kegiatan',
                width: 150,
                align: 'left'
              },
              {
                field: 'kdrek6',
                title: 'Rekening',
                width: 70,
                align: 'left'
              },
              {
                field: 'nmrek6',
                title: 'Nama Rekening',
                width: 300
              },
              {
                field: 'sisa',
                title: 'Sisa',
                width: 100,
                align: 'right'
              },
              {
                field: 'nilai1',
                title: 'Nilai',
                width: 100,
                align: 'right',
                editor: {
                  type: "numberbox"
                }
              }
            ]
          ]
        });
      });
    }


    function get(urut, no_spp, kd_skpd, no_spd, tgl_spp, bulan, jns_spp, keperluan, bank, rekening, status, kegi, nmgiat, prog, nmprog, tot_spp) {

      // alert(nmgiat);
      $("#dd_spp").attr("value", urut);
      $("#no_spp").attr("value", no_spp);
      $("#no_spp_hide").attr("value", no_spp);
      $("#no_simpan").attr("value", no_spp);
      $("#sp").combogrid("setValue", no_spd);
      $("#dd").datebox("setValue", tgl_spp);
      $("#kebutuhan_bulan").attr("Value", bulan);
      $("#ketentuan").attr("Value", keperluan);
      $("#jns_beban").attr("Value", jns_spp);
      $("#bank1").combogrid("setValue", bank);
      $("#rekening").attr("Value", rekening);
      $("#kg").combogrid("setValue", kegi);
      $("#nm_kg").attr("Value", nmgiat);
      $("#kp").attr("setValue", prog);
      $("#nm_kp").attr("Value", nmprog);

      //validate_tombol();
      tombol(status);
    }

    function kosong() {
      $("#no_spp_hide").attr("value", '');
      $("#no_simpan").attr("value", '');
      $("#sp").combogrid("setValue", '');
      $("#dd").datebox("setValue", '');
      $("#tgl_mulai").datebox("setValue", '');
      $("#tgl_akhir").datebox("setValue", '');
      $("#tglspd").datebox("setValue", '');
      $("#kebutuhan_bulan").attr("Value", '');
      $("#ketentuan").attr("Value", '');
      $("#jns_beban").attr("Value", '');
      $("#npwp").attr("Value", '');
      $("#rekanan").combogrid("setValue", '');
      $("#dir").attr("Value", '');
      $("#bank1").combogrid("setValue", '');
      $("#rekening").attr("Value", '');
      $("#kg").combogrid("setValue", '');
      $("#nm_kg").attr("Value", '');
      $("#kg").combogrid("setValue", '');
      $("#nm_kg").attr("Value", '');
      $("#nama_bank").attr("Value", '');
      $("#kontrak").attr("Value", '');
      $("#lanjut").attr("Value", '');
      $("#alamat").attr("Value", '');
      $("#kp").attr("setValue", '');
      $("#nm_kp").attr("Value", '');
      document.getElementById("p1").innerHTML = "";
      $("#sp").combogrid("clear");
      $("#kg").combogrid("clear");
      $("#cc").combobox("setValue", '');
      $("#notagih").combogrid("clear");
      //det_baru();
      tombolnew();
      detail_kosong();
      validate_kegiatan();
      // get_spp();
      var pidx = 0;
      edit = 'F';
      data_notagih();
      $("#rektotal_ls").attr("Value", 0);
      $("#rektotal1_ls").attr("Value", 0);

      lcstatus = 'tambah';
      $("#tgltagih").attr("value", '');
      $("#nil").attr("value", '');
      $("#ni").attr("value", '');
      $("#status").attr("checked", false);
      $("#tagih").hide();
    }



    function getRowIndex(target) {
      var tr = $(target).closest('tr.datagrid-row');
      return parseInt(tr.attr('datagrid-row-index'));
    }



    function simpan(giat, reke, nkeg, nrek, sisa) {
      var spp = document.getElementById('no_spp').value;
      var cskpd = kode;
      var cspd = spd;

      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            cskpd: cskpd,
            cspd: spd,
            cspp: spp,
            cgiat: giat,
            crek: reke,
            cnmgiat: nkeg,
            cnmrek: nrek,
            sspp: sisa
          }),
          dataType: "json",
          url: '<?php echo base_url(); ?>/index.php/tukd/tsimpan'
        });
      });
    }


    function cetak() {
      var nom = document.getElementById("no_spp").value;
      $("#cspp").combogrid("setValue", nom);
      $("#dialog-modal").dialog('open');
    }


    function keluar() {
      $("#dialog-modal").dialog('close');
    }


    function keluar_rek() {
      $("#dialog-modal-rek").dialog('close');
      $("#dgsppls").datagrid("unselectAll");

      $("#rek_nilai").attr("Value", 0);
      $("#rek_nilai_ang").attr("Value", 0);
      $("#rek_nilai_spp").attr("Value", 0);
      $("#rek_nilai_sisa").attr("Value", 0);
      $("#rek_nilai_ang_semp").attr("Value", 0);
      $("#rek_nilai_spp_semp").attr("Value", 0);
      $("#rek_nilai_sisa_semp").attr("Value", 0);
      // $("#rek_nilai_ang_ubah").attr("Value",0);
      // $("#rek_nilai_spp_ubah").attr("Value",0);
      // $("#rek_nilai_sisa_ubah").attr("Value",0);
    }


    function cari() {
      var kriteria = document.getElementById("txtcari").value;
      $(function() {
        $('#spp').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spp/load_spp_tu',
          queryParams: ({
            cari: kriteria
          })
        });
      });
    }


    function section1() {
      $(document).ready(function() {
        $('#section1').click();
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


    function hsimpan() {
      cdate = '<?php echo date("Y-m-d"); ?>';
      var a = (document.getElementById('no_spp').value).split(" ").join("");
      var a_hide = document.getElementById('no_spp_hide').value;
      var a_dd = a.substr(11, 6);
      var b = $('#dd').datebox('getValue');
      var c = document.getElementById('jns_beban').value;
      var d = document.getElementById('kebutuhan_bulan').value;
      var e = document.getElementById('ketentuan').value;
      var g = $("#bank1").combogrid("getValue");
      var i = document.getElementById('rekening').value;
      var j = document.getElementById('nmskpd').value;
      var k1 = document.getElementById('rektotal1_ls').value;
      var l = document.getElementById('nm_kg').value;
      var m = document.getElementById('kp').value;
      var n = document.getElementById('nm_kp').value;
      var z = $("#sp").combogrid("getValue");
      var y = $("#kg").combogrid("getValue");
      var k = angka(k1);
      var kdskpd = document.getElementById('dn').value;
      if (a == '') {
        alert("Isi Nomor SPP Terlebih Dahulu...!!!");
        exit();
      }
      if (kdskpd == '') {
        alert("Isi Kode SKPD Terlebih Dahulu...!!!");
        exit();
      }
      if (z == '') {
        alert("Isi Nomor SPD Terlebih Dahulu...!!!");
        exit();
      }

      if (b == '') {
        alert("Isi Tanggal Terlebih Dahulu...!!!");
        exit();
      }
      /*if (b > cdate){
      alert('Tanggal tak boleh melebihi tanggal saat ini !');
      exit();
    }*/

      var tahun_input = b.substring(0, 4);
      if (tahun_input != tahun_anggaran) {
        alert('Tahun tidak sama dengan tahun Anggaran');
        exit();
      }

      if (c == '') {
        alert("Isi Beban Terlebih Dahulu...!!!");
        exit();
      }

      if (d == '') {
        alert("Isi Kebutuhan Bulan Terlebih Dahulu...!!!");
        exit();
      }

      if (y == '') {
        alert("Isi Kode Kegiatan Terlebih Dahulu...!!!");
        exit();
      }


      if (l == '') {
        alert("Pilih Kegiatan Terlebih Dahulu...!!!");
        exit();
      }

      var lenket = e.length;
      if (lenket > 1000) {
        alert('Keterangan Tidak boleh lebih dari 1000 karakter');
        exit();
      }


      //Cek Datagrid
      var ctot_det = 0;
      $('#dgsppls').datagrid('selectAll');
      var rows = $('#dgsppls').datagrid('getSelections');
      for (var x = 0; x < rows.length; x++) {
        cnilai3 = angka(rows[x].nilai1);
        ctot_det = ctot_det + cnilai3;
      }
      if (k != ctot_det) {
        alert('Nilai Rincian tidak sama dengan Total, Silakan Refresh kembali halaman ini!');
        exit();
      }

      if (ctot_det == 0) {
        alert('Rincian Rekening Kosong');
        exit();
      }



      if (lcstatus == 'tambah') {
        $(document).ready(function() {
          // alert(csql);
          $.ajax({
            type: "POST",
            dataType: 'json',
            data: ({
              no: a,
              tabel: 'trhspp',
              field: 'no_spp'
            }),
            url: '<?php echo base_url(); ?>/index.php/spp/cek_simpan_spp',
            success: function(data) {
              status_cek = data.pesan;
              status = data.pesanttd;


              if (status == 1) {
                alert("SKPD ini belum membuat Penanda tangan PPTK, Harap Konfirmasi ke Kabid Anggaran");
                return;
              }
              if (status_cek == 1) {
                alert("Nomor Telah Dipakai!");
                return;
                // document.getElementById("nomor").focus();
                // exit();
              }
              if (status_cek == 0 || status == 0) {


                //---------

                lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, jns_beban, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,       nm_sub_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 , alamat, kontrak, lanjut, tgl_mulai, tgl_akhir,kd_sub_kegiatan,urut)";
                lcvalues = "('" + a + "', '" + kdskpd + "', '" + e + "',   '" + d + "', '" + spd + "', '" + c + "', '1', '" + g + "', '',  '" + i + "', '', '" + j + "',  '" + b + "', '0',    '',           '',            '" + k + "',  '',           '" + l + "',         '" + m + "',     '" + n + "',     '',  '',     '',    '',    '',       '',        '',        '',        '',      '',      '',      '', '','','','','" + y + "','" + a_dd + "' )";


                $(document).ready(function() {
                  $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/spp/simpan_tukd_tu',
                    data: ({
                      tabel: 'trhspp',
                      kolom: lcinsert,
                      nilai: lcvalues,
                      cid: 'no_spp'
                    }),
                    dataType: "json",
                    beforeSend: function(xhr) {
                      $("#loading").show();
                    },
                    success: function(data) {
                      status = data;
                      if (status == '0') {
                        alert('Gagal Simpan..!!');
                        exit();
                      } else if (status == '1') {
                        alert('Data Sudah Ada..!!');
                        exit();
                      } else {
                        $('#dgsppls').datagrid('selectAll');
                        var rows = $('#dgsppls').datagrid('getSelections');

                        for (var i = 0; i < rows.length; i++) {
                          cidx = rows[i].idx;
                          ckdgiat = rows[i].kdsubkegiatan;
                          cnmgiat = rows[i].nmsubkegiatan;
                          ckdrek = rows[i].kdrek6;
                          cnmrek = rows[i].nmrek6;
                          cnilai = angka(rows[i].nilai1);
                          sumber = rows[i].sumber;
                          no = i + 1;
                          cgiat = ckdgiat.substr(0, 22);

                          if (i > 0) {
                            csql = csql + "," + "('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kdskpd + "','" + j + "','" + ckdgiat + "','" + spd + "','" + sumber + "','" + cnmgiat + "','" + kdskpd + "')";
                          } else {
                            csql = "values('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kdskpd + "','" + j + "','" + ckdgiat + "','" + spd + "','" + sumber + "','" + cnmgiat + "','" + kdskpd + "')";
                          }
                        }
                        $(document).ready(function() {

                          $.ajax({
                            type: "POST",
                            dataType: 'json',
                            data: ({
                              no: a,
                              sql: csql
                            }),
                            url: '<?php echo base_url(); ?>/index.php/spp/dsimpan_ag',
                            success: function(data) {
                              status = data.pesan;
                              if (status == '1') {
                                $("#loading").hide();
                                alert('Data Berhasil Tersimpan...!!!');
                                $("#no_spp_hide").attr("value", a);
                                lcstatus = 'edit';
                                section1();
                              } else {
                                $("#loading").hide();
                                lcstatus = 'tambah';
                                alert('Detail Gagal Tersimpan...!!!');
                              }
                            }
                          });
                        });
                      }
                    }
                  });
                });

                //----------

              }
            }
          });
        });



      } else {

        $(document).ready(function() {

          $.ajax({
            type: "POST",
            dataType: 'json',
            data: ({
              no: a,
              tabel: 'trhspp',
              field: 'no_spp'
            }),
            url: '<?php echo base_url(); ?>/index.php/spp/cek_simpan_spp',
            success: function(data) {
              status_cek = data.pesan;
              if (status_cek == 1 && a != a_hide) {
                alert("Nomor Telah Dipakai!");
                exit();
              }
              if (status_cek == 0 || a == a_hide) {



                //---------
                lcquery = " UPDATE trhspp SET kd_skpd='" + kdskpd + "', keperluan='" + e + "', bulan='" + d + "', no_spd='" + z + "', jns_spp='" + c + "',jns_beban='1', bank='" + g + "', nmrekan='', no_rek='" + i + "', npwp='', nm_skpd='" + j + "', tgl_spp='" + b + "', status='0', nilai='" + k + "', kd_kegiatan='" + kegi + "', nm_kegiatan='" + l + "', kd_program='" + m + "', nm_program='" + n + "', pimpinan='', no_tagih='', tgl_tagih='', sts_tagih='', no_spp='" + a + "',alamat ='', kontrak='',lanjut='',tgl_mulai='',tgl_akhir='' where no_spp='" + a_hide + "' AND kd_skpd='" + kdskpd + "' ";


                $(document).ready(function() {
                  $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/spp/update_tukd_spp',
                    data: ({
                      st_query: lcquery,
                      tabel: 'trhspp',
                      cid: 'no_spp',
                      lcid: a,
                      lcid_h: a_hide
                    }),
                    dataType: "json",
                    beforeSend: function(xhr) {
                      $("#loading").show();
                    },
                    success: function(data) {
                      status = data;

                      if (status == '1') {

                        alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                        exit();
                      }

                      if (status == '2') {
                        $('#dgsppls').datagrid('selectAll');
                        var rows = $('#dgsppls').datagrid('getSelections');

                        for (var i = 0; i < rows.length; i++) {
                          cidx = rows[i].idx;
                          ckdgiat = rows[i].kdsubkegiatan;
                          cnmrekgiat = rows[i].nmsubkegiatan;
                          ckdrek = rows[i].kdrek6;
                          cnmrek = rows[i].nmrek6;
                          cnilai = angka(rows[i].nilai1);
                          csumber = rows[i].sumber;
                          cgiat = ckdgiat.substr(0, 22);
                          no = i + 1;
                          if (i > 0) {
                            csql = csql + "," + "('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kdskpd + "','" + ckdgiat + "','" + spd + "','" + csumber + "','" + cnmrekgiat + "')";
                          } else {
                            csql = "values('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kdskpd + "','" + ckdgiat + "','" + spd + "','" + csumber + "','" + cnmrekgiat + "')";
                          }
                        }
                        $(document).ready(function() {

                          $.ajax({
                            type: "POST",
                            dataType: 'json',
                            data: ({
                              no: a,
                              sql: csql,
                              no_hide: a_hide
                            }),
                            url: '<?php echo base_url(); ?>/index.php/spp/dsimpan_ag_edit',
                            success: function(data) {
                              status = data.pesan;
                              if (status == '1') {
                                $("#loading").hide();
                                alert('Data Berhasil Tersimpan...!!!');
                                $("#no_spp_hide").attr("value", a);
                                lcstatus = 'edit';
                                data_notagih();
                              } else {
                                $("#loading").hide();
                                lcstatus = 'tambah';
                                alert('Detail Gagal Tersimpan...!!!');
                              }
                            }
                          });
                        });
                      }

                      if (status == '0') {
                        alert('Gagal Simpan...!!!');
                        exit();
                      }

                    }
                  });
                });

                //-----------
              }
            }
          });
        });

      }

    }


    function dsimpan(kegiatan, rekening, nkegiatan, nrekening, nilai, sis, kd) {
      var a = document.getElementById('no_spp').value;
      $jak = eval(sis);
      $son = eval(nilai);
      if ($son > $jak) {
        alert('nilai melebihi anggaran')
      } else {
        $(function() {
          $.ajax({
            type: 'POST',
            data: ({
              cno_spp: a,
              cskpd: kode,
              cgiat: kegiatan,
              crek: rekening,
              ngiat: nkegiatan,
              nrek: nrekening,
              nilai: nilai,
              sis: sis,
              kd: kd
            }),
            dataType: "json",
            url: "<?php echo base_url(); ?>index.php/tukd/dsimpan"
          });
        });
      }
    }


    function detsimpan() {

      var a = document.getElementById('no_spp').value;
      var kode = $("#rek_skpd").combogrid("getValue");
      var cnmgiat = document.getElementById('nm_rek_kegi').value;
      var cnobukti1 = '';
      var a_hide = document.getElementById('no_spp_hide').value;

      $(document).ready(function() {
        $.ajax({
          type: 'POST',
          url: "<?php echo base_url(); ?>index.php/tukd/dsimpan_hapus",
          data: ({
            cno_spp: a_hide,
            lcid: a,
            lcid_h: a_hide
          }),
          dataType: "json",
          success: function(data) {
            status = data;
            if (status == '0') {
              alert('Gagal Hapus Detail Old');
              exit();
            }
          }
        });
      });


      $('#dgsppls').datagrid('selectAll');
      var rows = $('#dgsppls').datagrid('getSelections');

      for (var i = 0; i < rows.length; i++) {
        cidx = rows[i].idx;
        ckdgiat = rows[i].kdsubkegiatan;
        ckdgiat = rows[i].nmsubkegiatan;
        ckdrek = rows[i].kdrek6;
        cnmrek = rows[i].nmrek6;
        cnilai = angka(rows[i].nilai1);
        csumber = rows[i].sumber;

        no = i + 1;
        $(document).ready(function() {
          $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>index.php/tukd/dsimpan",
            data: ({
              cno_spp: a,
              cskpd: kode,
              cgiat: ckdgiat,
              crek: ckdrek,
              ngiat: cnmgiat,
              nrek: cnmrek,
              nilai: cnilai,
              kd: no,
              no_bukti1: cnobukti1
            }),
            dataType: "json"
          });
        });
      }
      $("#no_spp_hide").attr("Value", a);
      $('#dgsppls').edatagrid('unselectAll');
    }


    function hapus() {
      var spp = document.getElementById("no_spp").value;
      var nospp = spp.split("/").join("123456789");
      var giat = getSelections();
      var rek = getSelections1();
      if (rek != '') {
        var del = confirm('Anda yakin akan menghapus rekening ' + rek + ' kegiatan' + giat + ' ?');
        if (del == true) {
          $(function() {
            $('#dg1').edatagrid({
              url: '<?php echo base_url(); ?>/index.php/tukd/thapus/' + nospp + '/' + giat + '/' + rek,
              idField: 'id',
              toolbar: "#toolbar",
              rownumbers: "true",
              fitColumns: "true",
              singleSelect: "true"
            });
          });

        }
      }
    }


    function hhapus() {

      var spp = document.getElementById("no_spp").value;
      var nospp = spp.split("/").join("######");
      var urll = '<?php echo base_url(); ?>/index.php/spp/hapus_spp3';
      if (spp != '') {
        var del = confirm('Anda yakin akan menghapus SPP ' + spp + '  ?');
        if (del == true) {
          $(document).ready(function() {
            $.post(urll, ({
              no: nospp
            }), function(data) {
              status = data;
              if (status == 1) {
                alert('Data Berhasil Di Hapus');
              } else if (status == 2) {
                alert('Data SPP No. ' + spp + 'Sudah di SPM kan');
                exit();
              } else {
                alert('Data Gagl di Hapus');
              }

            });
          });
        }
      }
    }


    function getSelections(idx) {
      var ids = [];
      var rows = $('#dg1').edatagrid('getSelections');
      for (var i = 0; i < rows.length; i++) {
        ids.push(rows[i].kdkegiatan);
      }
      return ids.join(':');
    }

    function getSelections1(idx) {
      var ids = [];
      var rows = $('#dg1').edatagrid('getSelections');
      for (var i = 0; i < rows.length; i++) {
        ids.push(rows[i].kdrek5);
      }
      return ids.join(':');
    }

    function kembali() {
      $('#kem').click();
    }


    function load_sum_spp() {
      var nospp = document.getElementById('no_spp').value;

      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            spp: nospp
          }),
          url: "<?php echo base_url(); ?>index.php/spp/load_sum_spp",
          dataType: "json",
          success: function(data) {
            $.each(data, function(i, n) {
              $("#rektotal_ls").attr('value', number_format(n['rektotal'], 2, '.', ','));
              $("#rektotal1_ls").attr('value', number_format(n['rektotal'], 2, '.', ','));
            });
          }
        });
      });
    }



    function tombol(st) {
      if (st == 1) {
        $('#save').hide();
        $('#del').hide();
        document.getElementById("p1").innerHTML = "Sudah di Buat SPM...!!!";
      } else {
        $('#save').show();
        $('#del').show();
        document.getElementById("p1").innerHTML = "";
      }
    }


    function tombolnew() {
      $('#save').show();
      $('#del').show();
      $('#det').show();
      $('#sav').show();
      $('#dele').show();
    }


    function cetak_spp3() {
      var urll = '<?php echo base_url(); ?>/index.php/tukd/cetakspp3';
      if (spp != '') {
        var del = confirm('Anda yakin akan mencetak SPP ' + nomer + '  ?');
        if (del == true) {
          $(document).ready(function() {
            $.post(urll, ({
              no: nomer
            }), function(data) {
              status = data;
            });
          });
        }
      }
    }


    function cetak_spp(url) {
      var spasi = document.getElementById('spasi').value;
      var nomer = $("#cspp").combogrid('getValue');
      var jns = document.getElementById('jns_beban').value;
      var no = nomer.split("/").join("123456789");
      var ttd1 = $("#ttd1").combogrid('getValue');
      var ttd2 = $("#ttd2").combogrid('getValue');
      var ttd3 = $("#ttd3").combogrid('getValue');
      var ttd4 = $("#ttd4").combogrid('getValue');
      var tanpa = document.getElementById('tanpa_tanggal').checked;
      if (tanpa == false) {
        tanpa = 0;
      } else {
        tanpa = 1;
      }

      if (ttd1 == '') {
        alert("Bendahara Pengeluaran tidak boleh kosong!");
        exit();
      }
      if (ttd2 == '') {
        alert("PPTK tidak boleh kosong!");
        exit();
      }
      if (ttd4 == '') {
        alert("PPKD tidak boleh kosong!");
        exit();
      }
      var ttd_1 = ttd1.split(" ").join("123456789");
      var ttd_2 = ttd2.split(" ").join("123456789");
      var ttd_3 = ttd3.split(" ").join("123456789");
      var ttd_4 = ttd4.split(" ").join("123456789");

      window.open(url + '/' + no + '/' + kode + '/' + jns + '/' + ttd_1 + '/' + ttd_2 + '/' + ttd_4 + '/' + spasi + '/' + tanpa + '/' + ttd_3, '_blank');
      window.focus();
    }


    function cetak_spp_2(url) {
      var spasi = document.getElementById('spasi').value;
      var nomer = $("#cspp").combogrid('getValue');
      var jns = document.getElementById('jns_beban').value;
      var no = nomer.split("/").join("123456789");
      var ttd3 = $("#ttd3").combogrid('getValue');
      var tanpa = document.getElementById('tanpa_tanggal').checked;
      if (tanpa == false) {
        tanpa = 0;
      } else {
        tanpa = 1;
      }
      if (ttd3 == '') {
        alert("Bendahara Pengeluaran tidak boleh kosong!");
        exit();
      }

      var ttd_3 = ttd3.split(" ").join("123456789");

      window.open(url + '/' + no + '/' + kode + '/' + jns + '/' + ttd_3 + '/' + tanpa + '/' + spasi, '_blank');
      window.focus();
    }

    function detail() {
      var lcno = document.getElementById('no_spp').value;
      if (lcno != '') {
        section3();
      } else {
        alert('Nomor SPP Tidak Boleh kosong')
        document.getElementById('no_spp').focus();
        exit();
      }
    }


    function detail_trans_3() {
      $(function() {
        $('#dgsppls').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
          queryParams: ({
            spp: no_spp
          }),
          idField: 'idx',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "false",
          singleSelect: "true",
          nowrap: "true",
          onLoadSuccess: function(data) {},
          onSelect: function(rowIndex, rowData) {

            kd = rowIndex;
            idx = rowData.idx;
            kdsubkegiatan = rowData.kdsubkegiatan;
            kdsubkegiatan = rowData.nmsubkegiatan;
            tkdrek6 = rowData.kdrek6;
            tnmrek6 = rowData.nmrek6;
            tnilai1 = rowData.nilai1;
            tsumber = rowData.sumber;
          },
          columns: [
            [{
                field: 'idx',
                title: 'idx',
                width: 100,
                align: 'left',
                hidden: 'true'
              },
              {
                field: 'kdsubkegiatan',
                title: 'Sub Kegiatan',
                width: 150,
                align: 'left'
              },
              {
                field: 'nmsubkegiatan',
                title: 'Nama',
                width: 150,
                align: 'left',
                hidden: 'true'
              },
              {
                field: 'kdrek6',
                title: 'Rekening',
                width: 70,
                align: 'left'
              },
              {
                field: 'nmrek6',
                title: 'Nama Rekening',
                width: 280
              },
              {
                field: 'sumber',
                title: 'sumber',
                width: 280,
                hidden: 'true'
              },
              {
                field: 'nilai1',
                title: 'Nilai',
                width: 140,
                align: 'right'
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


    function detail_kosong() {

      var no_spp = '';
      $(function() {
        $('#dgsppls').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
          queryParams: ({
            spp: no_spp
          }),
          idField: 'idx',
          toolbar: "#toolbar",
          rownumbers: "true",
          fitColumns: false,
          autoRowHeight: "false",
          singleSelect: "true",
          nowrap: "true",
          onLoadSuccess: function(data) {},
          onSelect: function(rowIndex, rowData) {
            kd = rowIndex;
            idx = rowData.idx;
          },
          columns: [
            [{
                field: 'idx',
                title: 'idx',
                width: 100,
                align: 'left',
                hidden: 'true'
              },
              {
                field: 'kdsubkegiatan',
                title: 'Kegiatan',
                width: 150,
                align: 'left'
              },
              {
                field: 'nmsubkegiatan',
                title: 'Nama',
                width: 150,
                align: 'left',
                hidden: 'true'
              },
              {
                field: 'kdrek6',
                title: 'Rekening',
                width: 70,
                align: 'left'
              },
              {
                field: 'nmrek6',
                title: 'Nama Rekening',
                width: 280
              },
              {
                field: 'sumber',
                title: 'sumber',
                width: 280,
                hidden: 'true'
              },
              {
                field: 'nilai1',
                title: 'Nilai',
                width: 140,
                align: 'right'
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


    function tambah() {

      var cek_kegi = $("#kg").combogrid('getValue');
      if (cek_kegi == '') {
        alert('Isi Kode Kegiatan Terlebih Dahulu....!!!');
        exit();
      }
      $("#dialog-modal-rek").dialog('open');
      $("#rek_skpd").combogrid("disable");
      $("#rek_kegi").combogrid("disable");
      $("#rek_kegi").combogrid("setValue", '');
      $("#nm_rek_kegi").attr("Value", '');
      $("#rek_reke").combogrid("setValue", '');
      $("#nm_rek_reke").attr("Value", '');

      var kegi_tmb = $("#kg").combogrid('getValue');
      var nm_kegi_tmb = document.getElementById('nm_kg').value;
      var kd_angkas = document.getElementById('status_angkas').value;

      $("#rek_kegi").combogrid("setValue", kegi_tmb);
      $("#nm_rek_kegi").attr("Value", nm_kegi_tmb);

      $("#rek_nilai").attr("Value", 0);
      $("#rek_nilai_ang").attr("Value", 0);
      $("#rek_nilai_spp").attr("Value", 0);
      $("#rek_nilai_sisa").attr("Value", 0);
      //  $("#rek_nilai_ang_semp").attr("Value",0);
      //  $("#rek_nilai_spp_semp").attr("Value",0);
      //  $("#rek_nilai_sisa_semp").attr("Value",0);
      //  $("#rek_nilai_ang_ubah").attr("Value",0);
      //  $("#rek_nilai_spp_ubah").attr("Value",0);
      //  $("#rek_nilai_sisa_ubah").attr("Value",0);


    }


    function append_save() {

      $('#dgsppls').datagrid('selectAll');
      var rows = $('#dgsppls').datagrid('getSelections');
      jgrid = rows.length;

      var jumtotal = document.getElementById('rektotal_ls').value;
      jumtotal = angka(jumtotal);

      var vrek_skpd = $('#rek_skpd').combobox('getValue');
      var vrek_kegi = $('#rek_kegi').combobox('getValue');
      var vrek_reke = $('#rek_reke').combobox('getValue');
      var cnil = document.getElementById('rek_nilai').value;
      var vsumber_dn = $('#sumber_dn').combobox('getValue');
      var cnilai = cnil;


      var cnil_sisa = angka(document.getElementById('rek_nilai_sisa').value);
      var cnil_sisa_spd = angka(document.getElementById('nilai_sisa_spd').value);
      // var cnil_sisa_semp   = angka(document.getElementById('rek_nilai_sisa_semp').value) ;
      // var cnil_sisa_ubah   = angka(document.getElementById('rek_nilai_sisa_ubah').value) ;
      var cnil_input = angka(document.getElementById('rek_nilai').value);
      var cnil_sisa_dana = angka(document.getElementById('rek_nilai_sisa_dana').value);
      // var cnil_sisa_semp_dana   = angka(document.getElementById('rek_nilai_sisa_semp_dana').value) ;
      // var cnil_sisa_ubah_dana   = angka(document.getElementById('rek_nilai_sisa_ubah_dana').value) ;
      var status_ang = document.getElementById('status_ang').value;
      var tot_input = angka(document.getElementById('rektotal1_ls').value);
      akumulasi = cnil_input + tot_input;

      if (status_ang == '') {
        alert('Pilih Tanggal Dahulu');
        exit();
      }

      if (akumulasi > cnil_sisa_spd) {
        alert('Nilai Melebihi Sisa Dana SPD...!!!, Cek Lagi...!!!');
        exit();
      }

      if (cnil_input == 0) {
        alert('Nilai Nol.....!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Perubahan') && (cnil_input > cnil_sisa_ubah)) {
        alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!');
        exit();
      }

      if ((status_ang == 'Penyempurnaan') && (cnil_input > cnil_sisa_ubah)) {
        alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Penyempurnaan') && (cnil_input > cnil_sisa_semp)) {
        alert('Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Penyusunan') && (cnil_input > cnil_sisa_ubah)) {
        alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Penyusunan') && (cnil_input > cnil_sisa_semp)) {
        alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Penyusunan') && (cnil_input > cnil_sisa)) {
        alert('Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!');
        exit();
      }

      //sumber dana
      if ((status_ang == 'Perubahan') && (cnil_input > cnil_sisa_ubah_dana)) {
        alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Penyempurnaan') && (cnil_input > cnil_sisa_ubah_dana)) {
        alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Penyempurnaan') && (cnil_input > cnil_sisa_semp_dana)) {
        alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan...!!!, Cek Lagi...!!!');
        exit();
      }
      if ((status_ang == 'Penyusunan') && (cnil_input > cnil_sisa_ubah_dana)) {
        alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!');
        exit();
      }
      /*     if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp_dana)){
             alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
             exit();
           }*/
      if ((status_ang == 'Penyusunan') && (cnil_input > cnil_sisa_dana)) {
        alert('Nilai Melebihi Sisa Sumber Dana Penyusunan...!!!, Cek Lagi...!!!');
        exit();
      }

      var vnm_rek_reke = document.getElementById('nm_rek_reke').value;
      var nmkg = document.getElementById('nm_kg').value;

      if (edit == 'F') {
        pidx = pidx + 1;
      }

      if (edit == 'T') {
        pidx = jgrid;
        pidx = pidx + 1;
      }

      $('#dgsppls').edatagrid('appendRow', {
        kdsubkegiatan: vrek_kegi,
        nmsubkegiatan: nmkg,
        kdrek6: vrek_reke,
        nmrek6: vnm_rek_reke,
        nilai1: cnilai,
        sumber: vsumber_dn,
        idx: pidx
      });
      $("#dialog-modal-rek").dialog('close');

      jumtotal = jumtotal + angka(cnil);
      $("#rektotal_ls").attr('value', number_format(jumtotal, 2, '.', ','));
      $("#rektotal1_ls").attr('value', number_format(jumtotal, 2, '.', ','));
      $("#dgsppls").datagrid("unselectAll");

    }


    // function get_spp(){
    //     var jns ="";var $jns2 = "";
    //         $("#no_spp").attr("value",'');
    //     var kode   = document.getElementById('dn').value;
    //     jns = "TU";
    //     jns2 = 'BL';

    //       $.ajax({
    //         url:'<?php echo base_url(); ?>index.php/spp/config_spp/'+jns2,
    //         type: "POST",
    //         dataType:"json",                         
    //         success:function(data){
    //           no_spp = data.nomor;
    //         var inisial = no_spp + "/SPP/"+jns+"/"+kode+"/"+tahun_anggaran;
    //         $("#no_spp").attr('disabled',true);
    //                 $("#no_spp").attr("value",inisial);
    //                 $("#dd_spp").attr("value",no_spp);
    //           }                                     
    //       });
    //     }


    function hapus_detail() {

      var a = document.getElementById('no_spp').value;
      var rows = $('#dgsppls').edatagrid('getSelected');
      var ctotalspp = document.getElementById('rektotal_ls').value;

      bkdrek = rows.kdrek5;
      bkdkegiatan = rows.kdkegiatan;
      bnilai = rows.nilai1;
      bbukti = '';
      alert(ctotalspp);
      ctotalspp = angka(ctotalspp) - angka(bnilai);

      var idx = $('#dgsppls').edatagrid('getRowIndex', rows);
      var tny = confirm('Yakin Ingin Menghapus Data, Rekening : ' + bkdrek + '  Nilai :  ' + bnilai + ' ?');

      if (tny == true) {

        $('#dgsppls').datagrid('deleteRow', idx);
        $('#dgsppls').datagrid('unselectAll');
        $("#rektotal_ls").attr("Value", number_format(ctotalspp, 2, '.', ','));
        $("#rektotal1_ls").attr("Value", number_format(ctotalspp, 2, '.', ','));

        var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_spp';
        $(document).ready(function() {
          $.post(urll, ({
            cnospp: a,
            ckdgiat: bkdkegiatan,
            ckdrek: bkdrek,
            cnobukti: bbukti
          }), function(data) {
            status = data;
            if (status == '0') {
              alert('Gagal Hapus..!!');
              exit();
            } else {
              alert('Data Telah Terhapus..!!');
              exit();
            }
          });
        });
      }
    }
  </script>

  <STYLE TYPE="text/css">
    input.right {
      text-align: right;
    }
  </STYLE>

</head>

<body>

  <div id="content">
    <div id="accordion">
      <h3><a href="#" id="section1" onclick="javascript:$('#spp').edatagrid('reload')">List SPP</a></h3>

      <div style="height:350px;">
        <p align="right">
          <button class="button" onclick="javascript:section2();kosong();"> <i class="fa fa-tambah"></i> Tambah</button>
          <button class="button-cerah" onclick="javascript:cari();"> <i class="fa fa-cari"></i> Cari</button>
          <input type="text" class="input" style="display: inline;" value="" id="txtcari" placeholder="Pencarian" />
        <table id="spp" title="List SPP" style="width:870px;height:650px;">
        </table>
        </p>
      </div>

      <h3><a href="#" id="section2">Input SPP</a></h3>

      <div style="height:350px;">
        <p id="p1" style="font-size: x-large;color: red;"></p>

        <fieldset style="width:870px;height:950px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">
          <table border='1' style="font-size:11px">

            <tr>
              <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
              <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true" ; /></td>
              <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
              <td style="border-bottom: double 1px red;border-top: double 1px red;" colspan="2"><i>Tidak Perlu diisi atau di Edit</i></td>

            </tr>
            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
              <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
              <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
            </tr>

            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPP</td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="no_spp" id="no_spp" style="width:300px" onkeyup="this.value=this.value.toUpperCase()" disabled />
                * No Otomatis<input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px" /></td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal</td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="dd" name="dd" style="width: 200px" type="text" /><input id="dd_spp" name="dd_spp" type="hidden" /></td>
            </tr>
            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
              <td width="53%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                &nbsp;<input id="dn" name="dn" readonly="true" style="width:130px; border: 0;" /> </td>
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Bulan</td>
              <td width="31%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select name="kebutuhan_bulan" style="width: 200px" class="select" id="kebutuhan_bulan">
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
                </select></td>
            </tr>
            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
              <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input id="nmskpd" name="nmskpd" type="text" style="border: 0;width:250px;" readonly="true"></textarea></td>
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Keperluan</td>
              <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><textarea name="ketentuan" class="textarea" style="width: 200px" id="ketentuan" cols="30" rows="1"></textarea></td>
            </tr>

            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Beban</td>
              <td width="31%" style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><select name="jns_beban" id="jns_beban" onchange="javascript:get_spp();">
                  <option value="3" selected="selected">TU</option>
                </select></td>
              <td colspan="2" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
            </tr>


            <tr>
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPD</td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input id="sp" name="sp" style="width:190px" />&nbsp;&nbsp; <input id="tglspd" name="tglspad" type="text" disabled /></td>
              </td>
            </tr>

            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Kegiatan</td>
              <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="kg" name="kg" style="width:190px" />
                &nbsp;<input type="hidden" id="kp" name="kp" style="width:160px" />
                &nbsp;&nbsp;<input id="nm_kg" name="nm_kg" style="width:500px;border: 0;" />
                <input type="hidden" id="nm_kp" name="nm_kp" />
              </td>
            </tr>

            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">BANK</td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" style="width: 190px" name="bank1" id="bank1" />
                &nbsp;&nbsp;<input type="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:250" /></td>
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
              <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" class="input" name="rekening" id="rekening" value="" style="width:190px" /></td>
            </tr>


            </tr>

            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
              <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
              <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
              <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
            </tr>

            <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
              <td colspan="4" align='right' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;">
                <button id="del" class="button-merah" onclick="javascript:hhapus();section1();"><i class="fa fa-hapus"></i> Hapus</button>
                <button class="button-kuning" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>
                <button id="save" class="button-biru" onclick="javascript:hsimpan();"><i class="fa fa-simpan"></i> Simpan</button>
                <button class="button-hitam" onclick="javascript:cetak();"><i class="fa fa-print"></i> Cetak</button>
            </tr>
          </table>


          <!------------------------------------------------------------------------------------------------------------------>

          <table id="dgsppls" title="Input Detail SPP" style="width:870px;height:300%;">
          </table>

          <div id="toolbar" align="left">
            <button class="button" onclick="javascript:tambah();"> <i class="fa fa-tambah"></i> Tambah Rekening</button>
          </div>

          <table border='0' style="width:100%;height:5%;">
            <td width='39%'></td>
            <td width='15%'><input class="right" type="hidden" name="rektotal1_ls" id="rektotal1_ls" style="width:140px" align="right" readonly="true"></td>
            <td width='9%'><B>Total</B></td>
            <td width='32%'><input class="right" type="text" name="rektotal_ls" id="rektotal_ls" style="width:140px" align="right" readonly="true"></td>
          </table>
        </fieldset>
        <!------------------------------------------------------------------------------------------------------------------>
      </div>

    </div>
  </div>
  <div id="loading" class="loader1">
    <div class="loader2"></div>

  </div>


  <div id="dialog-modal-rek" title="Input Rekening">
    <p class="validateTips"></p>
    <fieldset>
      <table align="center" style="width:100%;" border="0">

        <tr>
          <td width='15%'>SKPD</td>
          <td width='3%'>:</td>
          <td colspan="6" width='82%'><input id="rek_skpd" name="rek_skpd" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="rek_nmskpd" style="border:0;width: 350px;" readonly="true" /></td>
        </tr>

        <tr>
          <td>KEGIATAN</td>
          <td>:</td>
          <td colspan="6"><input id="rek_kegi" name="rek_kegi" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_kegi" style="border:0;width: 400px;" readonly="true" /></td>
        </tr>

        <tr>
          <td>REKENING</td>
          <td>:</td>
          <td colspan="6"><input id="rek_reke" name="rek_reke" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_reke" style="border:0;width: 400px;" readonly="true" /></td>
        </tr>
        <tr>
          <td>SUMBER DANA</td>
          <td>:</td>
          <td colspan="6"><input id="sumber_dn" name="sumber_dn" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_reke" style="border:0;width: 400px;" readonly="true" /></td>
        </tr>

        <tr>
          <td bgcolor="#99FF99">TOTAL SPD</td>
          <td bgcolor="#99FF99">:</td>
          <td bgcolor="#99FF99"><input type="text" id="total_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#99FF99">SPD TERPAKAI</td>
          <td bgcolor="#99FF99">:</td>
          <td bgcolor="#99FF99"><input type="text" id="nilai_spd_lalu" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#99FF99">SISA</td>
          <td bgcolor="#99FF99">:</td>
          <td bgcolor="#99FF99"><input type="text" id="nilai_sisa_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
        </tr>
        <tr>
          <td bgcolor="#99FF99">ANGKAS</td>
          <td bgcolor="#99FF99">:</td>
          <td bgcolor="#99FF99"><input type="text" id="total_angkas" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#99FF99">ANGKAS TERPAKAI</td>
          <td bgcolor="#99FF99">:</td>
          <td bgcolor="#99FF99"><input type="text" id="nilai_angkas_lalu" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#99FF99">SISA</td>
          <td bgcolor="#99FF99">:</td>
          <td bgcolor="#99FF99"><input type="text" id="nilai_sisa_angkas" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
        </tr>

        <tr>
          <td>ANGGARAN</td>
          <td>:</td>
        </tr>
        <tr>
          <td bgcolor="#87CEFA">ANGGARAN</td>
          <td bgcolor="#87CEFA">:</td>
          <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#87CEFA">SPP TERPAKAI</td>
          <td bgcolor="#87CEFA">:</td>
          <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#87CEFA">SISA</td>
          <td bgcolor="#87CEFA">:</td>
          <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
        </tr>

        <!-- <tr>
            <td bgcolor="#87CEFA">PENYEMPURNAAN</td>
            <td bgcolor="#87CEFA">:</td>
            <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
            <td bgcolor="#87CEFA">SPP TERPAKAI</td>
            <td bgcolor="#87CEFA">:</td>
            <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
            <td bgcolor="#87CEFA">SISA</td>
            <td bgcolor="#87CEFA">:</td>
            <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>        
          </tr>
          <tr>
            <td bgcolor="#87CEFA">PERUBAHAN</td>
            <td bgcolor="#87CEFA">:</td>
            <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
            <td bgcolor="#87CEFA">SPP TERPAKAI</td>
            <td bgcolor="#87CEFA">:</td>
            <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
            <td bgcolor="#87CEFA">SISA</td>
            <td bgcolor="#87CEFA">:</td>
            <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>        
          </tr> -->
        <tr>
          <td>SUMBER DANA</td>
          <td>:</td>
        </tr>
        <tr>
          <td bgcolor="#FFA07A">SUMBER DANA</td>
          <td bgcolor="#FFA07A">:</td>
          <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#FFA07A">SPP TERPAKAI</td>
          <td bgcolor="#FFA07A">:</td>
          <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
          <td bgcolor="#FFA07A">SISA</td>
          <td bgcolor="#FFA07A">:</td>
          <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
        </tr>

        <!-- <tr>
            <td bgcolor="#FFA07A">PENYEMPURNAAN</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
            <td bgcolor="#FFA07A">SPP TERPAKAI</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
            <td bgcolor="#FFA07A">SISA</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>       
          </tr>
          <tr>
            <td bgcolor="#FFA07A">PERUBAHAN</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
            <td bgcolor="#FFA07A">SPP TERPAKAI</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
            <td bgcolor="#FFA07A">SISA</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>       
          </tr> -->

        <tr>
          <td>&nbsp;&nbsp;&nbsp;</td>
          <td>&nbsp;&nbsp;&nbsp;</td>
          <td>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td>NILAI</td>
          <td>:</td>
          <td><input type="text" id="rek_nilai" style="width: 196px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
        <tr>
          <td>STATUS</td>
          <td>:</td>
          <td><input type="text" id="status_ang" style="width: 196px; border:0; text-align: left;" readonly="true" /></td>
          <td>STATUS ANGKAS</td>
          <td>:</td>
          <td><input type="text" id="status_angkas" style="width: 196px; border:0; text-align: left;" readonly="true" /></td>

        </tr>

        <tr>
          <td colspan="6" align="center">
            <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_rek();">Keluar</a>
          </td>
        </tr>

      </table>
    </fieldset>

  </div>

  <div id="dialog-modal" title="CETAK SPP">
    <p class="validateTips">SILAHKAN PILIH SPP</p>
    <fieldset>
      <table>
        <tr>
          <td width="110px">NO SPP:</td>
          <td><input id="cspp" name="cspp" style="width: 170px;" disabled /> &nbsp; &nbsp; &nbsp; <input type="checkbox" id="tanpa_tanggal"> Tanpa Tanggal</td>
        </tr>

        <tr>
          <td width="110px">Bend. Pengeluaran:</td>
          <td><input id="ttd1" name="ttd1" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd1" name="nmttd1" style="width: 170px;border:0" /></td>
        </tr>
        <tr>
          <td width="110px">PPTK:</td>
          <td><input id="ttd2" name="ttd2" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd2" name="nmttd2" style="width: 170px;border:0" /></td>
        </tr>
        <tr>
          <td width="110px">PA:</td>
          <td><input id="ttd3" name="ttd3" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd3" name="nmttd3" style="width: 170px;border:0" /></td>
        </tr>
        <tr>
          <td width="110px">PPKD:</td>
          <td><input id="ttd4" name="ttd4" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd4" name="nmttd4" style="width: 170px;border:0" /></td>
        </tr>
        <tr>
          <td width="110px">SPASI:</td>
          <td><input type="number" id="spasi" style="width: 100px;" value="1" /></td>
        </tr>
      </table>
    </fieldset>
    <div>
    </div>

    <!-- cetakan -->
    <table>
      <tr>
        <td>
          Pengantar
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp1_3/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp1_3/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          Rincian
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp3/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp3/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
      </tr>
      <!-- -------------- -->
      <tr>
        <td>
          Ringkasan
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp2/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp2/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          Pernyataan
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>spp/cetakspp4/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>spp/cetakspp4/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
      </tr>

      <!-- -------------- -->
      <tr>
        <td>
          Permintaan
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp5/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp5/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          SPTB/Kontrak
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>spp/cetakspp6/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>spp/cetakspp6/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
      </tr>
      <tr>
        <td colspan="5"> Permendagri 77</td>
      </tr>

      <tr>
        <td>
          Permintaan
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp77/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp('<?php echo site_url(); ?>spp/cetakspp77/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
        <td>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          SPTB/Kontrak
        </td>
        <td>
          <button class="button-biru" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>spp/cetakspp77_rincian/0');return false;"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
          <button class="button-orange" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>spp/cetakspp77_rincian/1');return false;"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
        </td>
      </tr>




      <tr>
        <td>
          &nbsp;
        </td>
        <td>
          <button class="button-kuning" onclick="javascript:keluar();"><i class="fa fa-arrow-left" style="font-size:15px"></i> Kembali</button>
        </td>
        <td>
          &nbsp;
        </td>
        <td>
          &nbsp;
        </td>
        <td>
          &nbsp;
        </td>
      </tr>



    </table>
    <!-- end cetakan -->

  </div>

</body>

</html>