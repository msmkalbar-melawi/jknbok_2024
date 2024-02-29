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

    <script>
        var kode = '';
        var kegiatan = '';
        var xrekening = '';
        var xnmkegiatan = '';
        var xkegiatan = '';
        var total_pic = 0;

        $(document).ready(function() {
            $('#tgl_ttd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $("#accordion").accordion({});
            $('#ck').combogrid();
            $('#ttd1').combogrid();
            $('#ttd2').combogrid();
        });

        $(function() {
            $('#cc').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka_ro/skpduser',
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
                    nm = rowData.nm_skpd;
                    jns_ang = rowData.jns_ang;
                    $("#ck").combogrid("clear");
                    $("#total_pic2").attr("value", '');
                    $("#skpdd").attr("Value", nm);
                    jang();
                }
            });
        });




        function giat() {
            var kode = $("#cc").combogrid("getValue");
            var jns_ang = $("#j_ang").combogrid("getValue");
            $(function() {
                $('#ck').combogrid({
                    panelWidth: 700,
                    idField: 'kd_kegiatan',
                    textField: 'kd_kegiatan',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/rka_ro/load_giat_sempurna/' + kode + "/" + jns_ang,
                    columns: [
                        [{
                                field: 'kd_kegiatan',
                                title: 'Kode Kegiatan',
                                width: 150
                            },
                            {
                                field: 'nm_kegiatan',
                                title: 'Nama Kegiatan',
                                width: 520
                            }

                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        kegiatan = rowData.kd_kegiatan;
                        xkegiatan = rowData.kd_kegiatan;
                        xnmkegiatan = rowData.nm_kegiatan;
                        total = rowData.total;
                        total_pic = total;
                        skpdd = rowData.kd_skpd;
                        $("#jumlah").attr("value", total);
                        $("#nm_giat").attr("value", xnmkegiatan);
                        $("#total_pic2").attr("value", total_pic);
                    }
                });
                $('#ttd1').combogrid({
                    panelWidth: 400,
                    idField: 'urut',
                    textField: 'nip',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/rka_ro/load_ttd_unit/' + kode,
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
                        $("#nm_ttd1").attr("value", rowData.nama);
                    }
                });

                $('#ttd2').combogrid({
                    panelWidth: 400,
                    idField: 'urut',
                    textField: 'nip',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/rka_ro/load_ttd_bud',
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
                        $("#nm_ttd2").attr("value", rowData.nama);
                        tampil();
                    }
                });
            });

        }




        function jang() {
            $('#j_ang').combogrid({
                panelWidth: 400,
                idField: 'kode',
                textField: 'nama',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka_ro/load_jang/',
                columns: [
                    [{
                        field: 'nama',
                        title: 'Nama',
                        width: 400
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    kd_jang = rowData.kode;
                    jangkas(kd_jang);
                }
            });
        }

        function jangkas(kd_jang) {
            $('#j_angkas').combogrid({
                panelWidth: 400,
                idField: 'kode',
                textField: 'nama',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka_ro/load_jangkas/' + kd_jang,
                columns: [
                    [{
                        field: 'nama',
                        title: 'Nama',
                        width: 400
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    giat();
                }
            });
        }

        function tampil() {
            var tj_jang = $("#j_ang").combogrid("getValue");
            var tj_jangkas = $("#j_angkas").combogrid("getValue");
            var skpd = $("#cc").combogrid("getValue");
            var giat = $("#ck").combogrid("getValue");
            var ttd1 = $("#ttd1").combogrid("getValue");
            var ttd2 = $("#ttd2").combogrid("getValue");
            var tgl = $("#tgl_ttd").datebox("getValue");
            if ((skpd != '' && giat != '')) {
                var url = "<?php echo site_url(); ?>rka_ro/cetak_angkas_ro_preview/a/2020-01-01/1/1/" + tj_jang + "/" + tj_jangkas + "/" + skpd + "/" + giat + "/hidden/1";
                document.getElementById('tampil').innerHTML =
                    "<embed src='" + url + "' width='900 px' height='500px'>";
            }
        }

        function cetak(ctk) {
            var tj_jang = $("#j_ang").combogrid("getValue");
            var tj_jangkas = $("#j_angkas").combogrid("getValue");
            var skpd = $("#cc").combogrid("getValue");
            var giat = $("#ck").combogrid("getValue");
            var ttd1 = $("#ttd1").combogrid("getValue");
            var ttd2 = $("#ttd2").combogrid("getValue");
            if ((ttd1 == '' || ttd2 == '') || tgl == '') {
                alert("Harap diisi semua!");
                exit();
            }
            var tgl = $("#tgl_ttd").datebox("getValue");
            var url = "<?php echo site_url(); ?>rka_ro/cetak_angkas_ro_preview/66159202463fd4c312b063293b88f6063b28f0cc175b9c0f1b6a831c399e26977260c8102d29fcd525162d02eed4566b/" + tgl + "/" + ttd1 + "/" + ttd2 + "/" + tj_jang + "/" + tj_jangkas + "/" + skpd + "/" + giat + "/oke/" + ctk + "/";
            window.open(url);
        }
    </script>

</head>
<?php
if ($jns_ang == '1') {
    $select1 = "selected";
    $select2 = "";
    $select3 = "";
} else if ($jns_ang == '2') {
    $select1 = "";
    $select2 = "selected";
    $select3 = "";
} else {
    $select1 = "";
    $select2 = "";
    $select3 = "selected";
}

?>

<body>


    <div id="content">


        <div class="card border-danger" style="width:950px">
            <div class="card-header bg-light" align="center">
                <h3>CETAK ANGGARAN KAS SUB RINCIAN OBJEK</h3>
            </div>
            <div class="card-body">
                <table style="border-style: none; border-bottom: none" width="100%">
                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none">
                            OPD/UNIT
                        </td>
                        <td style="border-style: none; border-bottom: none">
                            &nbsp;<input id="cc" name="skpd" style="width: 300px;" /> <input id="skpdd" name="skpdd" style="width: 300px; border-style: none" />
                        </td>
                    </tr>

                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none">
                            Jenis Anggaran
                        </td>
                        <td style="border-style: none; border-bottom: none">
                            <input id="j_ang" name="j_ang" style="width: 300px;" />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none">
                            Jenis Anggaran Kas
                        </td>
                        <td style="border-style: none; border-bottom: none">
                            <input id="j_angkas" name="j_angkas" style="width: 300px;" />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none">
                            Sub Kegiatan
                        </td>
                        <td style="border-style: none; border-bottom: none">
                            &nbsp;<input id="ck" name="kegiatan" style="width: 300px;" /> <input id="nm_giat" name="kegiatan" style="width: 350px; border-style: none" />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none">
                            Penandatangan
                        </td>
                        <td style="border-style: none; border-bottom: none">
                            &nbsp;<input id="ttd1" name="ttd1" style="width: 300px;" /> <input id="nm_ttd1" name="nm_ttd1" style="width: 350px; border-style: none" />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none">
                            Penandatangan 2
                        </td>
                        <td style="border-style: none; border-bottom: none">
                            &nbsp;<input id="ttd2" name="ttd2" style="width: 300px;" /> <input id="nm_ttd2" name="nm_ttd2" style="width: 350px; border-style: none" />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none">
                            Tanggal ttd
                        </td>
                        <td style="border-style: none; border-bottom: none">
                            &nbsp;<input id="tgl_ttd" name="kegiatan" style="width: 300px;" />
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" style="border-style: none; border-bottom: none"></td>
                        <td style="border-style: none; border-bottom: none">&nbsp;
                            <button onclick="javascript:cetak(1);" class="btn btn-dark" iconCls="icon-print" plain="true" style="cursor: pointer; padding:10px"><i class="fa fa-television"></i> Layar</button>
                            <button onclick="javascript:cetak(2);" class="btn btn-pdf" iconCls="icon-pdf" plain="true" style="cursor: pointer; padding:10px"><i class="fa fa-file-pdf-o"></i> PDF</button>
                            <button onclick="javascript:cetak(3);" class="btn btn-success" iconCls="icon-excel" plain="true" style="cursor: pointer; padding:10px"><i class="fa fa-file-excel-o"></i> Excel</button>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="card-footer">
                <label id="tampil">
            </div>
        </div>




    </div>
</body>

</html>