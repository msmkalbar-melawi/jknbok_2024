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

        <LEGEND>CETAK LAPORAN REALISASI ANGGARAN SKPD</LEGEND>
        <p align="right">
        <table id="sp2d" title="Cetak" style="width:100%;height:200px;">
            <tr>
                <td width="20%">Bulan</td>
                <td><input type="text" id="bulan" style="width: 170px;" /> </td>
            </tr>
            <tr>
                <td width="20%" height="40"><B>JENIS ANGGARAN </B></td>
                <td width="80%"> <input id="rak" name="rak" class="form-control" style="width: 170px;" />
                </td>
            </tr>

            <!-- <tr>
                        <td><B>Jenis Anggaran</B></td>
                        <td>
                            <select name="jenis_ang" id="jenis_ang" class="select" style="width: 170px;">
                                <option value="1">PENYUSUNAN</option>
                                <option value="2">PERGESERAN</option>
                                <option value="3">PERUBAHAN</option>
                            </select>
                        </td>
                    </tr> -->
            <tr>
                <td>Tanggal TTD</td>
                <td><input type="text" id="tgl_ttd" style="width: 170px;" /></td>
            </tr>
            <tr>
                <td>PA</td>
                <td><input id="ttd" name="ttd" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd" name="nmttd" style="width: 170px;border:0" /> </td>
            </tr>
            <tr>
                <td>PPK</td>
                <td><input id="ttd2" name="ttd" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd2" name="nmttd" style="width: 170px;border:0" /> </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><b>Cetak LRA</b></td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetaklra(1);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetaklra(0);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetaklra(2);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>

            </tr>
            <tr>
                <td>Cetak LRA Permen 77</td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetak77(1);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetak77(0);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetak77(2);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>
            </tr>
            <tr>
                <td>Cetak LRA Permen 90</td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>
            </tr>
            <tr>
                <td>Cetak LRA Permen Sub RO 90</td>
                <td>
                    <button class="button-kuning" plain="true" onclick="javascript:cetak_sub_ro(1);"><i class="fa fa-print"></i> Cetak Layar</a></button>
                    <button class="button-hitam" plain="true" onclick="javascript:cetak_sub_ro(0);"><i class="fa fa-pdf"></i> Cetak PDF</a></button>
                    <button class="button-biru" plain="true" onclick="javascript:cetak_sub_ro(2);"><i class="fa fa-excel"></i> Cetak Excel</a></button>
                </td>
            </tr>


            <tr hidden style="background-color: #F8F9F9 ;">
                <td> <b>Akun Jenis</b></td>
                <td>:
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(1);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(0);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak2(2);">(64)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak2(3);">Word(64)</a>-->
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak6(1);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak6(0);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak6(2);">(12)</a>
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak134(4,1);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak134(4,0);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak134(4,2);">(13)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak6(3);">Word(13)</a></td>-->
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </tr>
            <tr hidden style="background-color: #E5E8E8">
                <td><b>Akun Rincian</b></td>
                <td>:
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak3(1);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak3(0);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak3(2);">(64)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak3(3);">Word(64)</a>-->
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak7(1);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak7(0);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak7(2);">(12)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak7(3);">Word(13)</a>-->
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak134(5,1);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak134(5,0);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak134(5,2);">(13)</a>
                </td>
            </tr>
            <tr hidden style="background-color: #F8F9F9 ;">
                <td><b>Penjabaran</b></td>
                <td>:
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak4(1);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak4(0);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak4(2);">(64)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak4(3);">Word(64)</a>-->
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak8(1);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak8(0);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak8(2);">(13)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak8(3);">Word(13)</a>-->
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak8_12(1);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak8_12(0);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak8_12(2);">(12)</a>

                </td>
            </tr>
            <tr hidden style="background-color: #E5E8E8">
                <td><b>Penjabaran (S.Dana)</b></td>
                <td>:
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak4_sumber(1);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak4_sumber(0);">(64)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak4_sumber(2);">(64)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak4(3);">Word(64)</a>-->
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak8sumber(1);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak8sumber(0);">(13)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak8sumber(2);">(13)</a>
                    &nbsp;
                    <hr> :
                    <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak8_sumber(1);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak8_sumber(0);">(12)</a>
                    <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak8_sumber(2);">(12)</a>
                    <!--<a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak9(3);">Word(13)</a>-->
                </td>
            </tr>
        </table>
        </p>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <script type="text/javascript">
        var nip = '';
        var kdskpd = '';
        var kdrek5 = '';
        var bulan = '';
        var ctk = 1;

        $(document).ready(function() {
            $("#accordion").accordion();

            $("#dialog-modal").dialog({
                height: 100,
                width: 922
            });

            $('#sskpd').combogrid({
                panelWidth: 630,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/skpd_2',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 100
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 500
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kdskpd = rowData.kd_skpd;
                    $("#nmskpd").attr("value", rowData.nm_skpd);
                    $("#skpd").attr("value", rowData.kd_skpd);
                }
            });

            $('#dcetak').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#dcetak2').datebox({
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
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',
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

            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'id_ttd',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/PPK',
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

            $('#rak').combogrid({
                idField: 'kode',
                textField: 'nama',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/cetak_spj/anggaran',
                columns: [
                    [{
                        field: 'nama',
                        title: 'Nama',
                        width: 210
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    rak = rowData.kode;

                }
            });

            $('#bulan').combogrid({
                panelWidth: 120,
                panelHeight: 300,
                idField: 'bln',
                textField: 'nm_bulan',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka/bulan',
                columns: [
                    [{
                        field: 'nm_bulan',
                        title: 'Nama Bulan',
                        width: 700
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    bulan = rowData.nm_bulan;
                    $("#bulan").attr("value", rowData.nm_bulan);
                }
            });

            $("#kode_skpd").hide();
        });

        function submit() {
            if (ctk == '') {
                alert('Pilih Jenis Cetakan');
                exit();
            }
            document.getElementById("frm_ctk").submit();
        }

        // function opt(val) {
        //     ctk = val;
        //     if (ctk == '1') {
        //         $("#tagih").hide();
        //         $("#dcetak").datebox("setValue", '');
        //         $("#dcetak2").datebox("setValue", '');
        //     } else if (ctk == '2') {
        //         $("#tagih").show();
        //     } else {
        //         exit();
        //     }
        // }

        function opt(val) {
            ctk = val;
            if (ctk == '1') {
                // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo';
            } else if (ctk == '2') {
                $("#kode_skpd").show();
                // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
            } else {
                exit();
            }
            // $('#frm_ctk').attr('action',urll);                        
        }

        function cetak($pilih) {
            var pilih = $pilih;
            //var jns_ang = document.getElementById('jenis_ang').value;
            //alert(jns_ang);
            cbulan = $('#bulan').combogrid('getValue');
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var jns_ang = $("#rak").combogrid('getValue');

            if (ctk == 1) {
                urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra/' + cbulan;
                if (bulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD", "warning");
                    exit();
                }
                if (ttd1 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
                if (ttd2 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
            } else {
                urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_unit/' + cbulan + '/' + kdskpd;
                if (kdskpd == '') {
                    alert("Pilih Unit dulu");
                    exit();
                }
                if (bulan == '') {
                    alert("Pilih Bulan dulu");
                    exit();
                }
            }
            window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang, '_blank');
            window.focus();

        }


        function cetak77($pilih) {
            var pilih = $pilih;
            //var jns_ang = document.getElementById('jenis_ang').value;
            //alert(jns_ang);
            cbulan = $('#bulan').combogrid('getValue');
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');

            var jns_ang = $("#rak").combogrid('getValue');


            if (ctk == 1) {
                urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_77/' + cbulan;
                if (bulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD", "warning");
                    exit();
                }
                if (ttd1 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
                if (ttd2 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
            } else {
                urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_unit/' + cbulan + '/' + kdskpd;
                if (kdskpd == '') {
                    alert("Pilih Unit dulu");
                    exit();
                }
                if (bulan == '') {
                    alert("Pilih Bulan dulu");
                    exit();
                }
            }
            window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetaklra($pilih) {
            var pilih = $pilih;
            //var jns_ang = document.getElementById('jenis_ang').value;
            //alert(jns_ang);
            cbulan = $('#bulan').combogrid('getValue');
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var jns_ang = $("#rak").combogrid('getValue');

            if (ctk == 1) {
                urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_baru/' + cbulan;
                if (bulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD", "warning");
                    exit();
                }
                if (ttd1 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
                if (ttd2 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
            } else {
                urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_unit/' + cbulan + '/' + kdskpd;
                if (kdskpd == '') {
                    alert("Pilih Unit dulu");
                    exit();
                }
                if (bulan == '') {
                    alert("Pilih Bulan dulu");
                    exit();
                }
            }
            window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak_sub_ro($pilih) {
            var pilih = $pilih;
            //var jns_ang = document.getElementById('jenis_ang').value;
            cbulan = $('#bulan').combogrid('getValue');
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var jns_ang = $("#rak").combogrid('getValue');

            if (ctk == 1) {
                urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_sub_ro/' + cbulan;
                if (bulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD", "warning");
                    exit();
                }
                if (ttd1 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
                if (ttd2 == '') {
                    swal("Error", "Pilih Penandatangan dulu", "warning");
                    exit();
                }
            } else {
                // urll = '<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_unit/' + cbulan + '/' + kdskpd;
                if (kdskpd == '') {
                    alert("Pilih Unit dulu");
                    exit();
                }
                if (bulan == '') {
                    alert("Pilih Bulan dulu");
                    exit();
                }
            }
            window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ttd2 + '/' + tgl_ttd + '/' + 3 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak2($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            //var tgl_ttd   = $("#tgl_ttd").combogrid('getValue');
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak6($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis_ang/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak134(jenis, $pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            cbulan = $('#bulan').combogrid('getValue');

            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis_ang13/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang + '/' + jenis, '_blank');
            window.focus();

        }

        function cetak3($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_rincian/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak7($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            cbulan = $('#bulan').combogrid('getValue');

            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_rincian_ang/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak4($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak4_sumber($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_sumber/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8sumber($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang_sumber/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8_12($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang_12/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8_sumber($pilih) {

            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var jns_ang = document.getElementById('jenis_ang').value;
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_sumber12/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + jns_ang, '_blank');
            window.focus();

        }

        function runEffect() {
            var selectedEffect = 'blind';
            var options = {};
            $("#tagih").toggle(selectedEffect, options, 500);
        };

        function pilih() {
            op = '1';
        };

        function cetak9($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang_sdana/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2, '_blank');
            window.focus();

        }
    </script>
</body>
</fieldset>

</html>