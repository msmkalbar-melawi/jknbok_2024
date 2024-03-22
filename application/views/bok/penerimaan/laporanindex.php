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
        var nip = '';
        var kdskpd = '';
        var kdrek5 = '';

        $(document).ready(function() {
            get_skpd();
            // cekskpd();



        });

        $(function() {
            //$('#sskpd').combogrid({  
            //		panelWidth:630,  
            //		idField:'kd_skpd',  
            //		textField:'kd_skpd',  
            //		mode:'remote',
            //		url:'<?php echo base_url(); ?>index.php/akuntansi/skpd',  
            //		columns:[[  
            //			{field:'kd_skpd',title:'Kode SKPD',width:100},  
            //			{field:'nm_skpd',title:'Nama SKPD',width:500}    
            //		]],
            //		onSelect:function(rowIndex,rowData){
            //			kdskpd = rowData.kd_skpd;
            //			$("#nmskpd").attr("value",rowData.nm_skpd);
            //			$("#skpd").attr("value",rowData.kd_skpd);
            //			//validate_giat();
            //		}  
            //		}); 

            $('#tgl_ttd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
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
        });

        $(function() {
            $('#ttd1').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/load_ttd/JKNBOK-PA',
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
                ]
            });
        });

        $(function() {
            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/load_ttd2/JKNBOK-BK',
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
                ]
            });
        });


        function get_skpd() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#sskpd2").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    $("#skpd").attr("value", data.kd_skpd);
                    kdskpd = data.kd_skpd;
                    vskpd = kdskpd.substring(8, 10);
                    if (vskpd == '00' || vskpd == '01') {

                    } else {
                        document.getElementById("jnsctk").options[0] = null;
                    }
                }
            });
        }



        // function cekskpd() {
        //     $('#sskpd2').combogrid({
        //         panelWidth: 700,
        //         idField: 'kd_skpd',
        //         textField: 'kd_skpd',
        //         mode: 'remote',
        //         url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/config_skpd',
        //         //queryParams: ({kdskpd:kdskpd2}),
        //         columns: [
        //             [{
        //                     field: 'kd_skpd',
        //                     title: 'Kode SKPD',
        //                     width: 100
        //                 },
        //                 {
        //                     field: 'nm_skpd',
        //                     title: 'Nama SKPD',
        //                     width: 700
        //                 }
        //             ]
        //         ],
        //         onSelect: function(rowIndex, rowData) {
        //             skpd = rowData.kd_skpd;
        //             $("#sskpd2").attr("value", rowData.kd_skpd);
        //             $("#nmskpd").attr("value", rowData.nm_skpd);
        //         }
        //     });
        // }

        function cetak(jns) {
            var dcetak = $('#dcetak').datebox('getValue');
            var dcetak2 = $('#dcetak2').datebox('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            var skpd = $('#sskpd2').val();
            var spasi = document.getElementById('spasi').value;
            var ttd = $('#ttd1').combogrid('getValue');
            var ttd = ttd.split(" ").join("123456789");
            var ttd2 = $('#ttd2').combogrid('getValue');
            var ttd2 = ttd2.split(" ").join("123456789");


            if (dcetak == '' || dcetak2 == '') {
                alert('Periode belum dipilih');
                return;
            }
            lc = '?&tgl_ttd=' + ctglttd + '&ttd=' + ttd + '&ttd2=' + ttd2;

            var url = "<?php echo site_url(); ?>bok/PenerimaanBOKController/laporanpenerimaanjkn";
            window.open(url + '/' + dcetak + '/' + dcetak2 + '/' + skpd + '/' + '/' + jns + '/' + spasi + lc, '_blank');
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

        <div id="accordion">

            <h3>CETAK BUKU PENERIMAAN DAN PENYETORAN</h3>
            <div>
                <p align="right">
                <table id="sp2d" title="Cetak Buku Penerimaan dan Penyetoran" style="width:870px;height:300px;">
                    <tr>
                        <td width="20%" height="40"><B>SKPD</B><input type="hidden" id="sskpd" name="sskpd" readonly="true" style="width: 150px; border: 0;" /></td>

                        <td width="80%"> <input id="sskpd2" name="sskpd2" style="width:150px;border: 0;" readonly />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
                    </tr>
                    <tr>
                        <td width="20%" height="40"><B>PERIODE</B></td>
                        <td width="80%"><input id="dcetak" name="dcetak" type="text" style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text" style="width:155px" /></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div id="div_bend">
                                <table style="width:100%;" border="0">
                                    <td width="20%">TANGGAL TTD</td>
                                    <td width="1%">:</td>
                                    <td><input type="text" id="tgl_ttd" style="width: 100px;" />
                                    </td>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <td colspan="4">
                        <div id="div_bend">
                            <table style="width:100%;" border="0">
                                <td width="20%">Pengguna Anggaran</td>
                                <td width="1%">:</td>
                                <td><select type="text" id="ttd1" style="width: 100px;"></select>
                                </td>

                                <td width="20%">Bendahara BOK</td>
                                <td width="1%">:</td>
                                <td><input type="text" id="ttd2" style="width: 100px;" />
                                </td>
                            </table>
                        </div>
                    </td>
                    </tr>
                    <tr>
                        <td width="20%" height="40"><B>SPASI</B></td>
                        <td width="80%"><input id="spasi" name="spasi" type="number" style="width:155px" value="1" />
                        </td>
                    </tr>

                    <tr>
                        <td width="20%" height="40">&nbsp</td>
                        <td width="80%"> <INPUT TYPE="button" VALUE="CETAK LAYAR" ONCLICK="cetak(0)" style="height:40px;width:100px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE="button" VALUE="CETAK PDF" ONCLICK="cetak(1)" style="height:40px;width:100px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE="button" VALUE="CETAK EXCEL" ONCLICK="cetak(2)" style="height:40px;width:100px">
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                </p>
            </div>
        </div>
    </div>


</body>

</html>