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

  <script type="text/javascript">
    var no_spp = '';
    var kode = '';
    var lcstatus = '';

    $(document).ready(function() {
      $("#accordion").accordion();
      $("#lockscreen").hide();
      $("#frm").hide();
      $("#dialog-modal").dialog({
        height: 430,
        width: 700,
        modal: true,
        autoOpen: false
      });
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
          $("#bulan").attr('value', m);
          get_spp();
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
              width: 120
            },
            {
              field: 'nama_bank',
              title: 'Nama',
              width: 500
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          //$("#kode").attr("value",rowData.kode);
          $("#nama_bank").attr("value", rowData.nama_bank);
        }
      });

      $('#cspp').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spp/load_spp_up',
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
        url: '<?php echo base_url(); ?>index.php/spp/load_ttd3/BUD',
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


      $('#spp').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spp/load_spp_up',
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
              field: 'no_spp',
              title: 'NO SPP',
              width: 40
            },
            {
              field: 'tgl_spp',
              title: 'Tanggal',
              width: 25
            },
            {
              field: 'nm_skpd',
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
          nomer = rowData.no_spp;
          kode = rowData.kd_skpd;
          spd = rowData.no_spd;
          tg = rowData.tgl_spp;
          jn = rowData.jns_spp;
          kep = rowData.keperluan;
          np = rowData.npwp;
          bk = rowData.bank;
          ning = rowData.no_rek;
          status = rowData.status;
          get(nomer, kode, spd, tg, jn, kep, np, bk, ning, status);
          detail1_up();
          lcstatus = 'edit';
        },
        onDblClickRow: function(rowIndex, rowData) {
          section1();
        }
      });


      $('#sp').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spp/spd1',
        queryParams: ({
          cjenis: '5'
        }),
        idField: 'no_spd',
        textField: 'no_spd',
        mode: 'remote',
        fitColumns: true,
        onLoadSuccess: function(data) {
          detail1_up();
        },
        columns: [
          [{
              field: 'no_spd',
              title: 'No SPD',
              width: 30
            },
            {
              field: 'tgl_spd',
              title: 'Tanggal',
              align: 'left',
              width: 70
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          spd = rowData.no_spd;
          //append_jak()                                                       
        }
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


      $('#rekup').combogrid({
        panelWidth: 500,
        url: '<?php echo base_url(); ?>/index.php/spp/spd1_up',
        idField: 'kdrek6',
        textField: 'kdrek6',
        mode: 'remote',
        fitColumns: true,
        /*
        onLoadSuccess:function(data){
          detail1();                                           
        },
        */
        columns: [
          [{
              field: 'kdrek6',
              title: 'Kode Rekening',
              width: 50
            },
            {
              field: 'nmrek6',
              title: 'Nama Rekening',
              align: 'left',
              width: 100
            }
          ]
        ],
        onSelect: function(rowIndex, rowData) {
          $("#nmrekup").attr("value", rowData.nmrek6);
        }
      });



    });





    function get_skpd() {
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spp/config_skpd',
        type: "POST",
        dataType: "json",
        success: function(data) {
          $("#dn").attr("value", data.kd_skpd);
          $("#nmskpd").attr("value", data.nm_skpd);
          kode = data.kd_skpd;

          validate_spd(kode);
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

    function validate_spd(kode) {
      $(function() {
        $('#sp').combogrid({
          panelWidth: 500,
          url: '<?php echo base_url(); ?>/index.php/spp/spd1/' + kode,
          idField: 'no_spd',
          textField: 'no_spd',
          mode: 'remote',
          fitColumns: true
        });
      });
    }



    function detail1_up() {

      var no_spp = document.getElementById('no_spp').value;

      $.ajax({
        url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
        type: "POST",
        data: ({
          spp: no_spp
        }),
        dataType: "json",
        success: function(data) {
          $.each(data, function(i, n) {
            $("#rekup").combogrid("setValue", n['kdrek6']);
            $("#nmrekup").attr("Value", n['nmrek6']);
            $("#nilaiup").attr("Value", n['nilai1']);
          });
        }
      });

    }


    function getnilai_up() {

      $.ajax({
        url: '<?php echo base_url(); ?>/index.php/spp/ambil_nilai_up',
        type: "POST",
        dataType: "json",
        success: function(data) {
          $.each(data, function(i, n) {
            $("#nilaiup").attr("Value", number_format(n['nilai_up'], 2, '.', ','));
          });
        }
      });

    }





    function detail1() {

      var no_spp = document.getElementById('no_spp').value;
      $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
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
          load_sum_spp();
        },
        onSelect: function(rowIndex, rowData) {
          kd = rowIndex;
        },
        onAfterEdit: function(rowIndex, rowData, changes) {
          kd_rek6 = rowData.kdrek6;
          nm_rek6 = rowData.nmrek6;
          nilai = rowData.nilai1;
          kd = rowIndex;
          dsimpan(kd_rek6, nm_rek6, nilai, kd);
        },
        columns: [
          [{
              field: 'ck',
              title: 'ck',
              checkbox: true,
              hidden: true
            },
            {
              field: 'kdrek6',
              title: 'Rekening',
              width: 100,
              align: 'left'
            },
            {
              field: 'nmrek6',
              title: 'Nama Rekening',
              width: 530
            },
            {
              field: 'nilai1',
              title: 'Nilai',
              width: 140,
              align: 'right',
              editor: {
                type: "numberbox"
              }
            }
          ]
        ]
      });

    }


    function detail() {
      $(function() {
        var no_spp = '';
        $('#dg1').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
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
                field: 'kdrek6',
                title: 'Rekening',
                width: 100,
                align: 'left'
              },
              {
                field: 'nmrek6',
                title: 'Nama Rekening',
                width: 530
              },
              {
                field: 'nilai1',
                title: 'Nilai',
                width: 140,
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


    /*
    function append_jak(){                     
        $('#dg1').datagrid('appendRow',{kdrek6:'1110302',nmrek6:'Uang Persediaan',nilai1:'0'});
    }
    */


    function get(no_spp, kd_skpd, no_spd, tgl_spp, jns_spp, keperluan, npwp, bank, rekening, status) {
      $("#no_spp").attr("value", no_spp);
      $("#no_spp_hide").attr("value", no_spp);

      $("#dn").attr("Value", kd_skpd);
      $("#sp").combogrid("setValue", no_spd);
      $("#dd").datebox("setValue", tgl_spp);
      $("#ketentuan").attr("Value", keperluan);
      $("#jns_beban").attr("Value", jns_spp);
      $("#npwp").attr("Value", npwp);
      $("#bank1").combogrid("setValue", bank);
      $("#rekening").attr("Value", rekening);
      $("#no_spp").attr('disabled', true);
      tombol(status);
    }

    function kosong() {
      lcstatus = 'tambah';
      $('#save').linkbutton('enable');
      $('#del').linkbutton('enable');
      $('#sav').linkbutton('enable');
      $('#dele').linkbutton('enable');

      $("#no_spp").attr("value", '');
      $("#no_spp_hide").attr("value", '');

      $("#rekup").combogrid("setValue", '');
      $("#nmrekup").attr("Value", '');
      $("#nilaiup").attr("Value", 0);

      $("#sp").combogrid("setValue", '');
      $("#dd").datebox("setValue", '');
      get_sk_up();
      $("#npwp").attr("Value", '');
      $("#bank1").combogrid("setValue", '');
      $("#rekening").attr("Value", '');
      $("#no_spp").attr('disabled', true);
      $("#nilaiup").attr('disabled', true);
      document.getElementById("p1").innerHTML = "";
      document.getElementById("no_spp").focus();
      $("#sp").combogrid("clear");
      detail();
      // get_spp();
      getnilai_up();

    }

    function getRowIndex(target) {
      var tr = $(target).closest('tr.datagrid-row');
      return parseInt(tr.attr('datagrid-row-index'));
    }



    function cetak() {
      var nom = document.getElementById("no_spp").value;
      $("#cspp").combogrid("setValue", nom);
      $("#dialog-modal").dialog('open');
    }

    function keluar() {
      $("#dialog-modal").dialog('close');
    }

    function cari() {
      var kriteria = document.getElementById("txtcari").value;
      $(function() {
        $('#spp').edatagrid({
          url: '<?php echo base_url(); ?>/index.php/spp/load_spp_up',
          queryParams: ({
            cari: kriteria
          })
        });
      });
    }

    function setgrid() {
      $('#dg1').edatagrid({
        columns: [
          [{
              field: 'ck',
              title: 'ck',
              checkbox: true,
              hidden: true
            },
            {
              field: 'kdrek6',
              title: 'Rekening',
              width: 100,
              align: 'left'
            },
            {
              field: 'nmrek6',
              title: 'Nama Rekening',
              width: 530
            },
            {
              field: 'nilai1',
              title: 'Nilai',
              width: 140,
              align: 'right',
              editor: {
                type: "numberbox"
              }
            }

          ]
        ]
      });
    }


    function section1() {
      $(document).ready(function() {
        $('#section1').click();
        // setgrid()                                              
      });
    }

    function section4() {
      $(document).ready(function() {
        $('#section4').click();
      });
    }


    function hsimpan() {

      var a = (document.getElementById('no_spp').value).split(" ").join("");
      var a_hide = document.getElementById('no_spp_hide').value;
      var a_dd = a.substr(0, 6);
      var b = $('#dd').datebox('getValue');
      var c = document.getElementById('jns_beban').value;
      var e = document.getElementById('ketentuan').value;
      var g = $("#bank1").combogrid("getValue");
      var spd = $("#sp").combogrid("getValue");
      var h = document.getElementById('npwp').value;
      var i = document.getElementById('rekening').value;
      var j = document.getElementById('nmskpd').value;
      var k = angka(document.getElementById('nilaiup').value);
      var rek_up = $("#rekup").combogrid("getValue");
      var jns = '1';

      if (rek_up == '') {
        alert('Pilih Rekening Terlebih Dahulu');
        exit;
      }
      var tahun_input = b.substring(0, 4);
      if (tahun_input != tahun_anggaran) {
        alert('Tahun tidak sama dengan tahun Anggaran');
        exit();
      }
      if (spd == '') {
        alert("Nilai Nomor SPD tidak boleh Kosong");
        return;
      }
      /*
      $(function(){      
       $.ajax({
          type: 'POST',
          data: ({cskpd:kode,cspd:spd,no_spp:a,tgl_spp:b,jns_spp:c,keperluan:e,nmskpd:j,bank:g,npwp:h,rekening:i,nilai:k}),
          dataType:"json",
          url:"<?php //echo base_url(); 
                ?>index.php/tukd/simpan",
          success:function(data){
              if (data = 1){
                  alert('Data Berhasil Tersimpan');
              }else{
                  alert('Data Gagal Berhasil Tersimpan');
              }
          }
       });
      });
      */

      if (lcstatus == 'tambah') {
        // kurang nya
        lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, jns_beban,  bank,    nmrekan,  no_rek,   npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,   no_bukti, kd_sub_kegiatan,  nm_sub_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4,urut )";
        lcvalues = "('" + a + "', '" + kode + "', '" + e + "',   ''   ,   '" + spd + "', '" + c + "',  '" + jns + "','" + g + "', ''     ,  '" + i + "',  '" + h + "', '" + j + "',  '" + b + "', '0',    '',           '',            '" + k + "', '',       '',           '',           '',          '',          '',        '',          '',         '',        '',        '',        '',        '',        '',      '',      '','" + a_dd + "'         )";
        //alert(lcvalues);
        $(document).ready(function() {
          $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>/index.php/spp/simpan_tukd_spp',
            data: ({
              tabel: 'trhspp',
              kolom: lcinsert,
              nilai: lcvalues,
              cid: 'no_spp',
              lcid: a
            }),
            dataType: "json",
            success: function(data) {
              status = data;
              if (status == '0') {
                alert('Gagal Simpan..!!');
                exit();
              } else if (status == '1') {
                alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                exit();
              } else {
                dsimpan_up();
                alert('Data Tersimpan..!!');
                lcstatus = 'edit';
                exit();
              }
            }
          });
        });

      } else {

        lcquery = " UPDATE trhspp SET kd_skpd='" + kode + "', keperluan='" + e + "', no_spd='" + spd + "', jns_spp='" + c + "', bank='" + g + "', no_rek='" + i + "', npwp='" + h + "', nm_skpd='" + j + "', tgl_spp='" + b + "', status='0', nilai='" + k + "', no_spp='" + a + "' where no_spp='" + a_hide + "' AND  kd_skpd='" + kode + "' ";

        $(document).ready(function() {
          $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>/index.php/tukd/update_tukd_spp',
            data: ({
              st_query: lcquery,
              tabel: 'trhspp',
              cid: 'no_spp',
              lcid: a,
              lcid_h: a_hide
            }),
            dataType: "json",
            success: function(data) {
              status = data;

              if (status == '1') {
                alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                exit();
              }

              if (status == '2') {
                dsimpan_up_edit();
                alert('Data Tersimpan...!!!');
                lcstatus = 'edit';
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
    }


    function dsimpan_up() {

      var a = (document.getElementById('no_spp').value).split(" ").join("");
      var rek_up = $("#rekup").combogrid("getValue");
      var nm_rek_up = document.getElementById('nmrekup').value;
      var nilai_up = angka(document.getElementById('nilaiup').value);
      // alert("'"+a+"','"+rek_up+"','"+nilai_up+"','"+kode+"'");


      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            cno_spp: a,
            cskpd: kode,
            crek: rek_up,
            nrek: nm_rek_up,
            nilai: nilai_up
          }),
          dataType: "json",
          url: "<?php echo base_url(); ?>index.php/spp/dsimpan_up",
          success: function(data) {}
        });
      });
      $("#no_spp_hide").attr("Value", a);
    }

    function dsimpan_up_edit() {

      var a = (document.getElementById('no_spp').value).split(" ").join("");
      var a_hide = document.getElementById('no_spp_hide').value;
      var rek_up = $("#rekup").combogrid("getValue");
      var nm_rek_up = document.getElementById('nmrekup').value;
      var nilai_up = angka(document.getElementById('nilaiup').value);
      alert("'" + a + "','" + rek_up + "','" + nilai_up + "','" + kode + "'");


      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            cno_spp: a,
            cskpd: kode,
            crek: rek_up,
            nrek: nm_rek_up,
            nilai: nilai_up,
            no_hide: a_hide
          }),
          dataType: "json",
          url: "<?php echo base_url(); ?>index.php/spp/dsimpan_up_edit",
          success: function(data) {}
        });
      });
      $("#no_spp_hide").attr("Value", a);
    }


    function dsimpan(kd_rek6, nm_rek6, nilai, kd) {
      var a = document.getElementById('no_spp').value;
      //alert(a);    
      $(function() {
        $.ajax({
          type: 'POST',
          data: ({
            cno_spp: a,
            cskpd: kode,
            crek: kd_rek6,
            nrek: nm_rek6,
            nilai: nilai,
            kd: kd
          }),
          dataType: "json",
          url: "<?php echo base_url(); ?>index.php/spp/dsimpan"
        });
      });
    }


    function detsimpan() {
      var a = document.getElementById('no_spp').value;
      $('#dg1').datagrid('selectAll');
      var rows = $('#dg1').datagrid('getSelections');
      //alert(rows); 
      for (var i = 0; i < rows.length; i++) {
        ckdgiat = rows[i].kdkegiatan;
        cnmgiat = rows[i].nmkegiatan;
        ckdrek = rows[i].kdrek6;
        cnmrek = rows[i].nmrek6;
        cnilai = rows[i].nilai1;
        cnilai_s = rows[i].sis;
        no = i + 1;
        $(document).ready(function() {
          $.ajax({
            type: 'POST',
            url: "<?php echo base_url(); ?>index.php/spp/dsimpan",
            data: ({
              cno_spp: a,
              cskpd: kode,
              cgiat: ckdgiat,
              crek: ckdrek,
              ngiat: cnmgiat,
              nrek: cnmrek,
              nilai: cnilai,
              sis: cnilai_s,
              kd: no
            }),
            dataType: "json"
          });
        });
      }
    }



    function hhapus() {
      var spp = document.getElementById("no_spp").value;
      var urll = '<?php echo base_url(); ?>/index.php/spp/hhapus';
      if (spp != '') {
        var del = confirm('Anda yakin akan menghapus SPP ' + spp + '  ?');
        if (del == true) {
          $(document).ready(function() {
            $.post(urll, ({
              no: spp
            }), function(data) {
              status = data;
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
      //alert(idx);
      var ids = [];
      var rows = $('#dg1').edatagrid('getSelections');
      for (var i = 0; i < rows.length; i++) {
        ids.push(rows[i].kdrek6);
      }
      return ids.join(':');
    }


    function kembali() {
      $('#kem').click();
    }

    function load_sum_spp() {
      var spp = document.getElementById('no_spp').value;
      var nospp = spp.split("/").join("123456789");
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
              $("#rektotal").attr("value", n['rektotal']);
              $("#rektotal1").attr("value", n['rektotal1']);
            });
          }
        });
      });
    }

    function tombol(st) {
      if (st == '1') {
        $('#save').linkbutton('disable');
        $('#del').linkbutton('disable');
        $('#sav').linkbutton('disable');
        $('#dele').linkbutton('disable');
        document.getElementById("p1").innerHTML = "Sudah di Buat SPM!!";
      } else {
        /*  $('#save').linkbutton('enable');
         $('#del').linkbutton('enable');
         $('#sav').linkbutton('enable');
         $('#dele').linkbutton('enable'); */
        //tanda
        $('#save').linkbutton('enable');
        $('#del').linkbutton('enable');
        $('#sav').linkbutton('enable');
        $('#dele').linkbutton('enable');
        document.getElementById("p1").innerHTML = "";
      }
    }


    function openWindow(url) {
      var nomer = $("#cspp").combobox('getValue');
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


      window.open(url + '/' + no + '/' + kode + '/' + jns + '/' + ttd_1 + '/' + ttd_2 + '/' + ttd_4 + '/' + tanpa + '/' + ttd_3, '_blank');
      window.focus();
    }

    function openWindow2(url) {
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

      // window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanda, '_blank');
      window.open(url + '/' + no + '/' + kode + '/' + jns + '/' + ttd_3 + '/' + tanpa, '_blank');
      window.focus();
    }

    function get_spp() {
      var jenis_ls = document.getElementById('jns_beban').value;
      var jns = "UP";
      var jns2 = 'UP';
      var bulan_spp = document.getElementById('bulan').value;


      $("#ketentuan").attr('disabled', true);
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spp/config_spp/' + bulan_spp + '/' + jenis_ls + '/' + jns2,
        type: "POST",
        dataType: "json",
        success: function(data) {
          no_spp = data.nomor;
          var inisial = no_spp + "/" + jns + "/" + kode + "/M/" + bulan_spp + "/" + tahun_anggaran;
          $("#no_spp").attr("value", inisial);
          $("#dd_spp").attr("value", no_spp);
        }
      });
    }

    function get_sk_up() {
      var nmskpd = document.getElementById('nmskpd').value.trim();
      $.ajax({
        url: '<?php echo base_url(); ?>index.php/spp/config_sk_up',
        type: "POST",
        dataType: "json",
        success: function(data) {
          sk_up = data.sk_up;
          tgl_up = data.tgl_up;
          var inisial2 = "Pembayaran Uang Persediaan (UP) Berdasarkan Keputusan Bupati Melawi " + sk_up + " Tanggal " + tgl_up + " Tentang Besaran Pagu Uang Persediaan Satuan Kerja Perangkat Daerah Di Lingkungan Pemerintah Kabupaten Melawi Tahun Anggaran " + tahun_anggaran;
          $("#ketentuan").attr("value", inisial2);
        }
      });
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
      <h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List SPP</a></h3>

      <div class="col-md-12">
        <div class="col-md-6" align="center">
          <h2>INPUT SPP UP</h2>
        </div>
        <div class="col-md-6">
          <p align="right"><button type='submit' class="easyui-linkbutton" plain="true" onclick="javascript:section1();kosong();"><i class="fa fa-plus"></i> Tambah</button>
            <input type="text" value="" id="txtcari" />
            <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
          </p>
          <table id="spp" title="List SPP" style="width:870px;height:450px;">
          </table>
        </div>
      </div>

      <h3><a href="#" id="section1">Input SPP</a></h3>
      <div style="height: 350px;">
        <p id="p1" style="font-size: x-large;color: red;"></p>
        <p>

        <fieldset style="width:850px;height:650px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">

          <table border='1' style="font-size:11px">

            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
              <td width="8%" style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
              <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
              <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
              <td style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
            </tr>



            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
              <td width="8%" style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">No SPP</td>
              <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><input type="text" name="no_spp" id="no_spp" onkeyup="this.value=this.value.toUpperCase()" style="width:300px;" /><input type="hidden" name="no_spp_hide" id="no_spp_hide" onclick="javascript:select();" style="width:200px;" /> * No Otomatis</td>
              <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">Tanggal</td>
              <td style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;<input id="bulan" name="bulan" type="hidden" /><input id="dd" name="dd" type="text" /><input type="hidden" id="dd_spp" name="dd_spp" /></td>
            </tr>

            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
              <td width='8%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">SKPD</td>
              <td width="53%" style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
                <input id="dn" name="dn" readonly="true" style="width:200px; border: 0; " />
              </td>
              <td width='8%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">Beban</td>
              <td width="31%" style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><select name="jns_beban" id="jns_beban">
                  <option value="1">UP</option>
                </select></td>
            </tr>

            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
              <td width='8%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
              <td width='53%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea name="nmskpd" id="nmskpd" cols="40" rows="1" style="border: 0;" readonly="true"></textarea></td>
              <td width='8%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">Keperluan</td>
              <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" style="background-color: #dddcda;"></textarea></td>
            </tr>

            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
              <td width='8%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">No SPD</td>
              <td style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><input id="sp" name="sp" style="width:200px" /></td>

              <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">BANK</td>
              <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="bank1" id="bank1" />
                &nbsp;<input type="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:150" /></td>
            </tr>

            <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
              <td style="border-bottom-style:hidden;border-right-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='8%'>NPWP</td>
              <td style="border-bottom-style:hidden;border-right-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='53%'><input type="text" name="npwp" id="npwp" value="" style="width:200px;" /></td>
              <td style="border-bottom-style:hidden;border-right-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='8%'>Rekening</td>
              <td style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;" width='31%'>&nbsp;<input type="text" name="rekening" id="rekening" value="" style="width:200px;" /></td>
            </tr>

            <tr>
              <td colspan="4">
                <font color="red">Catatan: Silahkan Pilih SPD TW 1 (koordinasikan dengan bidang perbendaharaan)</font>
              </td>
            </tr>

            <tr style="border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
              <td width="8%" style="border-right-style:hidden;border-bottom-color:black;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
              <td style="border-right-style:hidden;border-bottom-color:black;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
              <td style="border-right-style:hidden;border-bottom-color:black;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
              <td style="border-bottom-color:black;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">&nbsp;</td>
            </tr>
          </table>

          <!--<table border='1' width='100%'>
            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
                 <td width='30%' style="font-size:20px;font:bold;color: #004080;">DETAIL SPP UP</td>
                 <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
                 <td width='30%'>&nbsp;&nbsp;&nbsp;</td>
                 <td width='10' >&nbsp;&nbsp;&nbsp;</td>
                 <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>-->


          <table border='1'>

            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
              <td colspan='3' style="font-size:20px;font:bold;color:#004080;">DETAIL SPP UP</td>
            </tr>

            <tr>
              <td colspan='3' style="border-bottom-style:hidden;">&nbsp;</td>
            </tr>


            <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;border-bottom-style:hidden;">
              <td width='10%' style="border-right-style:hidden;border-bottom-style:hidden;">Rekening</td>
              <td width='15%' style="border-right-style:hidden;border-bottom-style:hidden;"><input type="text" name="rekup" id="rekup" value="" style="width:200px;" /></td>
              <td width='75%' style="border-bottom-style:hidden;"><input type="text" name="nmrekup" id="nmrekup" value="" style="width:500px;border:0" readonly="true" /></td>
            </tr>

            <tr style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
              <td width='10' style="border-bottom-style:hidden;border-right-style:hidden;">Nilai</td>
              <td width='15%' style="border-bottom-style:hidden;border-bottom-color:black;border-right-style:hidden;"><input type="text" name="nilaiup" id="nilaiup" value="" style="width:200px;text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))" /> </td>
              <td width='75' style="border-bottom-style:hidden;">&nbsp;</td>
            </tr>

            <tr>
              <td colspan='3' style="border-bottom-color:black;">&nbsp;</td>
            </tr>

          </table>


          <table align="right">
            <tr style="border-bottom-style:hidden;border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;">
              <td align="right">
                <div>
                  <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>-->
                  <button type="primary" id="save" class="easyui-linkbutton" plain="true" onclick="javascript:$('#dg1').edatagrid('addRow');javascript:$('#dg1').edatagrid('reload');javascript:hsimpan();"><i class="fa fa-save"></i> Simpan</button>
                  <button type='delete' id="del" class="easyui-linkbutton" plain="true" onclick="javascript:hhapus();"><i class="fa fa-trash"></i> Hapus</button>
                  <button type='edit' class="easyui-linkbutton" plain="true" onclick="javascript:section4();"><i class="fa fa-arrow-left"></i> Kembali</button>
                  <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:cetak();"><i class="fa fa-print"></i> cetak</button>
                </div>
              </td>
            </tr>
          </table>

          <!--<table id="dg1" title="Input Detail SPP" style="width:850%;height:150%;" >  
        </table>-->

          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

          <!-- <B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:90px" align="rigth" readonly="true" >
            <input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:90px" align="rigth" readonly="true" >  
            -->


          <!--<table border='0' style="width:100%;height:5%;"> 
             <td width='30%'></td>
             <td width='40%'><input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" ></td>
             <td width='15%'><B>Total</B></td>
             <td width='15%'><input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" ></td>
        </table>-->

          </p>
        </fieldset>
      </div>

    </div>
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
      </table>
    </fieldset>
    <div>
    </div>
    <button type='pdf' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp1_1/1');return false;"><i class="fa fa-file-pdf-o"></i> Pengantar</button>&nbsp;
    <button type='pdf' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp2/1');return false;"><i class="fa fa-file-pdf-o"></i> Ringkasan</button>&nbsp;
    <button type='pdf' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp3/1');return false;"><i class="fa fa-file-pdf-o"></i> Rincian</button>&nbsp;
    <button type='pdf' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow2('<?php echo base_url(); ?>spp/cetakspp4/1');return false;"><i class="fa fa-file-pdf-o"></i> Pernyataan</button>
    <br />
    <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp1_1/0');return false;"><i class="fa fa-television"></i> Pengantar</button>
    <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp2/0');return false;"><i class="fa fa-television"></i> Ringkasan</button>
    <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp3/0');return false;"><i class="fa fa-television"></i> Rincian</button>
    <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow2('<?php echo base_url(); ?>spp/cetakspp4/0');return false;"><i class="fa fa-television"></i> Pernyataan</button>


    <br />
    Permendagri 77
    <br />
    <button type='pdf' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp77/0');return false;"><i class="fa fa-file-pdf-o"></i> Pengantar</button>
    <button type='pdf' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp77_rinci/0');return false;"><i class="fa fa-file-pdf-o"></i> Ringkasan</button>
    <br />
    <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp77/0');return false;"><i class="fa fa-television"></i> Pengantar</button>
    <button type='primary' class="easyui-linkbutton" plain="true" onclick="javascript:openWindow('<?php echo base_url(); ?>spp/cetakspp77_rinci/0');return false;"><i class="fa fa-television"></i> Ringkasan</button>
    <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Kembali</button>



  </div>

</body>

</html>