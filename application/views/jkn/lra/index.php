<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />

    <style>
        #tagih {
            position: relative;
            width: 922px;
            height: 100px;
            padding: 0.4em;
        }

        input.right {
            text-align: right;
        }

        fieldset {
            width: 100% !important;
        }
    </style>
</head>

<body>

    <div id="content">

        <LEGEND>CETAK LAPORAN JKN/BOK</LEGEND>
        <p align="right">
        <table id="sp2d" title="Cetak" style="width:100%;height:200px;">
            <tr>
                <td width="20%">SKPD</td>
                <td><input type="text" id="skpd" style="width: 170px;" readonly />&nbsp;&nbsp;
                    <input type="text" id="nm_skpd" style="width: 170px;" readonly />
                </td>
            </tr>
            <tr>
                <td width="20%">Periode</td>
                <td><input type="text" id="periode1" style="width: 170px;" />&nbsp;&nbsp;
                    <input type="text" id="periode2" style="width: 170px;" />
                </td>
            </tr>
            <tr>
                <td>Tanggal TTD</td>
                <td><input type="text" id="tgl_ttd" style="width: 170px;" /></td>
            </tr>
            <tr>
                <td>Anggaran</td>
                <td>
                    <select id="anggaran" style="width: 170px;">
                        <option value="" selected>-- Pilih --</option>
                        <?php
                            foreach ($anggaran as $item) {
                                ?>
                                    <option value="<?= $item->kode ?>"><?= $item->nama ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>
                    <select id="jenis" style="width: 170px;">
                        <option value="" selected>-- Pilih --</option>
                        <option value="jkn">JKN</option>
                        <option value="bok">BOK</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>PA</td>
                <td><input id="ttd" name="ttd" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd" name="nmttd" style="width: 170px;border:0" /> </td>
            </tr>
            <tr>
                <td><b>Cetak LRA</b></td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>
            </tr>
        </table>
        </p>
    </div>
</body>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
<script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#periode1').datebox({
            required: true,
            formatter: function(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                return y + '-' + m + '-' + d;
            }
        });
        $('#periode2').datebox({
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

        $('#ttd').combogrid({
            panelWidth: 600,
            idField: 'id_ttd',
            textField: 'nip',
            mode: 'remote',
            url: '<?php echo base_url(); ?>index.php/jkn/LraController/ttd',
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
                $("#nmttd").attr("value", rowData.nama);
            }
        });

        $.ajax({
            url: '<?php echo base_url(); ?>index.php/jkn/LraController/config_skpd',
            type: "POST",
            dataType: "json",
            success: function(data) {
                $("#skpd").attr("value", data.kd_skpd);
                $("#nm_skpd").attr("value", data.nm_skpd);
                kode = data.kd_skpd;
                validate_rek();

            }
        });
    });

    function cetak(ctk) {
        var periode1 = $('#periode1').datebox('getValue');
        var periode2 = $('#periode2').datebox('getValue');
        var tgl_ttd = $('#tgl_ttd').datebox('getValue');
        var ttd = $("#ttd").combogrid("getValue");
        var jenis = $("#jenis").val();
        var anggaran = $("#anggaran").val();
        if (periode1 == '') {
            alert('Isi periode terlebih dahulu');
            return;
        }
        if (periode2 == '') {
            alert('Isi periode terlebih dahulu');
            return;
        }
        if (tgl_ttd == '') {
            alert('Isi tanggal tanda tangan terlebih dahulu');
            return;
        }
        if (ttd == '') {
            alert('Pilih nama tanda tangan terlebih dahulu');
            return;
        }
        if (jenis == '') {
            alert('Pilih jenis terlebih dahulu');
            return;
        }
        if (!anggaran) {
           return alert('Silahkan pilih jenis anggaran') 
        }

        urll = "<?php echo base_url(); ?>index.php/jkn/LraController/laporan";
        window.open(urll + '?periode1=' + periode1 + '&periode2=' + periode2 + '&tgl_ttd=' + tgl_ttd + '&ttd=' + ttd + '&jenis=' + jenis + '&ctk=' + ctk + '&anggaran='+anggaran, '_blank');
        window.focus();
    }
</script>


</html>