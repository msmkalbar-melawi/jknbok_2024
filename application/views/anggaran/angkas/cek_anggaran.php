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
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.min.css">
  <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

  <script type="text/javascript">
    var kdstatus = '';
    var kd = '';

    $(document).ready(function() {
      $("#accordion").accordion();
      $("#dialog-modal").dialog({
        height: 420,
        width: 600,
        modal: true,
        autoOpen: false
      });
    });


    $(function() {
      $('#kode').combogrid({
        panelWidth: 700,
        idField: 'kd_skpd',
        textField: 'kd_skpd',
        mode: 'remote',
        url: '<?php echo base_url(); ?>index.php/rka_penetapan/skpduser2',
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
          kd = rowData.kd_skpd;
          $("#nmskpd").attr("value", rowData.nm_skpd.toUpperCase());
        }
      });

      $('#status').combogrid({
        panelWidth: 200,
        idField: 'kode',
        textField: 'nama',
        mode: 'remote',
        url: '<?= base_url(); ?>index.php/rka_penetapan/statusanggaran',
        columns: [
          [{
            field: 'nama',
            title: 'Nama',
            width: 100
          }]
        ],
      });

      $('#status_angkas').combogrid({
        panelWidth: 200,
        idField: 'kode',
        textField: 'nama',
        mode: 'remote',
        url: '<?= base_url(); ?>index.php/rka_penetapan/statusangkas',
        columns: [
          [{
            field: 'nama',
            title: 'Nama',
            width: 100
          }]
        ],
        onSelect: function() {
          cetakbawah();
        }
      });

    });

    function cetakbawah() {
      var ckdskpd = $('#kode').combogrid('getValue');
      var status_ang = $('#status').combogrid('getValue');
      var kdstatus_angkas = $('#status_angkas').combogrid('getValue');
      // alert('status Anggaran :' + ckdskpd + ' Status Angkas :' + kdstatus_angkas);
      // alert(kdstatus_angkas);
      if (ckdskpd == '' || status_ang == '' || kdstatus_angkas == '') {
        alert("GAGAL MEMUAT !, COBA LAGI ANDA BELUM BERUNTUNG");
        return;
      } else {
        url = "<?php echo site_url(); ?>rka_ro/preview_cetakan_cek_anggaran/" + ckdskpd + '/0/' + status_ang + '/' + kdstatus_angkas + '/Report-cek-anggaran';
        document.getElementById("demo").innerHTML = "<embed src=" + url + " width='900 px' height='500px'></embed>";
      }


    }



    function cek($cetak, $jns) {
      var ckdskpd = $('#kode').combogrid('getValue');
      var status_ang = $('#status').combogrid('getValue');
      var kdstatus_angkas = $('#status_angkas').combogrid('getValue');
      //  alert(kdstatus_angkas);

      url = "<?php echo site_url(); ?>rka_ro/preview_cetakan_cek_anggaran/" + ckdskpd + '/' + $cetak + '/' + status_ang + '/' + kdstatus_angkas + '/Report-cek-anggaran'

      openWindow(url, $jns);
    }


    function openWindow(url, $jns) {

      lc = '';
      window.open(url + lc, '_blank');
      window.focus();

    }
  </script>

</head>

<body>

  <div id="content">
    <h3 align="center"><u><b><a>CEK NILAI ANGGARAN DAN ANGGARAN KAS</a></b></u></h3>
    <div align="center">
      <p align="center">
      <table style="width:100%;" border="0">
        <tr>
          <td width="15%">SKPD/UNIT</td>
          <td width="1%">:</td>
          <td colspan="2">&nbsp;&nbsp;<input type="text" id="kode" class="form-control" style="width:200px;" /></td>
          <td><input type="text" id="nmskpd" style="border:0;width:400px;" /></td>
        </tr>
        <tr>
          <td width="10%">Status Anggaran</td>
          <td width="1%">:</td>
          <td>
            <input id="status" name="status" class="form-control" style="width: 200px;" />
          </td>
        </tr>
        <tr>
          <td width="10%">Status Angkas</td>
          <td width="1%">:</td>
          <td>
            <input id="status_angkas" name="status_angkas" class="form-control" onclick="javascript:cetakbawah()" style="cursor: pointer; width: 200px" />
          </td>
        </tr>
        <tr>
          <td width="10%">Cetak Laporan</td>
          <td width="1%">:</td>
          <td colspan="3">
            <button class="btn btn-outline-dark" plain="true" onclick="javascript:cek(0,'skpd');return false"><i class="fa fa-television"></i> Layar</button>
            <button class="btn btn-outline-warning" plain="true" onclick="javascript:cek(1,'skpd');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
            <button class="btn btn-outline-success" plain="true" onclick="javascript:cek(2,'skpd');return false"><i class="fa fa-file-excel-o"></i> EXCEL</button>
          </td>
        </tr>


      </table>

      </p>
    </div>
    <label id="demo"></label>
  </div>

</body>

</html>