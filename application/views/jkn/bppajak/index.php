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
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/jkn/BppajakController/Configskpd",
                type: 'POST',
                dataType: "JSON",
                success: function(data, status) {
                    $('#sskpd').val(data.kd_skpd);
                    $('#nmskpd').val(data.nm_skpd);
                },
                error: function(xhr, desc, err) {
                    console.log("error");
                }
            });
            //Tanda Tangan
            $("#ttd1").combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/jkn/BppajakController/ttd',
                columns: [
                    [{
                            field: "nip",
                            title: 'NIP',
                            width: 200
                        },
                        {
                            field: "nama",
                            title: 'Nama',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nm_ttd1").attr("value", rowData.nama);
                }
            });

            $("#ttd2").combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/jkn/BppajakController/ttdPA',
                columns: [
                    [{
                            field: "nip",
                            title: 'NIP',
                            width: 200
                        },
                        {
                            field: "nama",
                            title: 'Nama',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nm_ttd2").attr("value", rowData.nama);
                }
            });
        });

        function cetak(jns) {
            let periode1 = $('#periode1').val();
            let periode2 = $('#periode2').val();
            let tglttd = $('#tglttd').val();
            let jenis = $('#jenis').val();
            let ttd1 = $('#ttd1').combogrid('getValue');
            let ttd2 = $('#ttd2').combogrid('getValue');
            let url = '<?php echo base_url(); ?>index.php/jkn/BppajakController/laporanbppajak' + "?periode1=" + periode1 + "&periode2=" + periode2 + "&ttd1=" + ttd1 + "&ttd2=" + ttd2 + "&tanggalttd=" + tglttd +"&jenis=" + jenis + "&jnscetak=" + jns;
            if (periode1 == '' || periode2 == '' || tglttd == '' || ttd1 == '' || ttd2 == '' || jenis == '') {
                alert('Lengkapi data');
                return;
            }
            window.open(url, '_blank');
            window.focus();
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



        <h3>CETAK BUKU PEMBANTU PAJAK</h3>
        <div id="accordion">

            <p align="right">
            <table id="sp2d" title="Cetak Buku Besar" style="width:922px;height:200px;">
                <tr>
                    <td width="20%" height="40"><B>SKPD</B></td>
                    <td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
                </tr>
                <tr>
                    <td width="20%">Periode</td>
                    <td><input type="date" id="periode1" name="periode1">&nbsp;&nbsp;
                        <input type="date" id="periode2" name="periode2">
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
                    <td width="20%" height="40"><B>TANGGAL TTD</B></td>
                    <td width="80%"><input type="date" id="tglttd" name="tglttd" style="width:110px"></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div id="div_bend">
                            <table style="width:100%;" border="0">
                                <td width="20%">Bendahara Pengeluaran</td>
                                <td><input type="text" id="ttd1" name="ttd1" style="width: 200px;" /> &nbsp;&nbsp;
                                    <input type="text" id="nm_ttd1" readonly="true" style="width: 200px;border:0" />

                                </td>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div id="div_bend">
                            <table style="width:100%;" border="0">
                                <td width="20%">Pengguna Anggaran</td>
                                <td><input type="text" id="ttd2" style="width: 200px;" /> &nbsp;&nbsp;
                                    <input type="nm_ttd2" id="nm_ttd2" readonly="true" style="width: 200px;border:0" />

                                </td>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div id="div_bend">
                            <table style="width:100%;" border="0">
                                <td width="20%">Spasi</td>
                                <td><input type="number" id="spasi" style="width: 100px;" value="1" />

                                </td>
                            </table>
                        </div>
                    </td>
                </tr>
                <!-- 		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak</a>
			</td>
		</tr> -->
                <tr>
                    <td colspan="3" align="center">
                        <button class="button-biru" plain="true" id="cetak" value="0" onclick="cetak(this.value)" name="cetak"><i class="fa fa-print"></i> Layar</button>
                        <button class="button-kuning" plain="true" id="cetak" value="1" name="cetak" onclick="cetak(this.value)"><i class="fa fa-file-pdf-o"></i> PDF</button>
                        <?php
                        ?>

                    </td>
                </tr>
            </table>
            </p>


        </div>
    </div>


</body>

</html>