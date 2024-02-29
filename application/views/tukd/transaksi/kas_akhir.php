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

    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <script type="text/javascript">
        var nip = '';
        var kdskpd = '';
        var kdrek5 = '';
        var confrim = confirm("Mencetak BKU terlebih dahulu sebelum mencetak Buku Kas Akhir Bulan");
        if (confrim == true) {
            alert("MANTAP, FOKUS");
        } else {
            alert("ULANGI");
            alert("KURANG FOKUS");
            location.reload();
        }

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 400,
                width: 800
            });
            get_skpd();
        });

        $(function() {
            //	$('#sskpd').combogrid({  
            //		panelWidth:630,  
            //		idField:'kd_skpd',  
            //		textField:'kd_skpd',  
            //		mode:'remote',
            //		url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
            //		columns:[[  
            //			{field:'kd_skpd',title:'Kode SKPD',width:100},  
            //			{field:'nm_skpd',title:'Nama SKPD',width:500}    
            //		]],
            //		onSelect:function(rowIndex,rowData){
            //			kdskpd = rowData.kd_skpd;
            //			$("#nmskpd").attr("value",rowData.nm_skpd);
            //			$("#skpd").attr("value",rowData.kd_skpd);
            //           
            //		}  
            //		}); 
        });
        $(function() {
            $('#ttd').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/BK',
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
                    $("#nama").attr("value", rowData.nama);
                }
            });
        });

        $(function() {
            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
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
                    $("#nama2").attr("value", rowData.nama);
                }
            });
        });

        $(function() {
            $('#tgl_ttd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });
        });

        function validate1() {
            var bln1 = document.getElementById('bulan1').value;

        }

        function get_skpd() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#sskpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    // $("#skpd").attr("value",rowData.kd_skpd);
                    kdskpd = data.kd_skpd;

                }
            });

        }


        function cetak(ctk) {
            var spasi = document.getElementById('spasi').value;
            var nip = nip;
            var skpd = kdskpd;
            var bulan = document.getElementById('bulan1').value;
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            var ttd = $('#ttd').combogrid('getValue');
            ttd = ttd.split(" ").join("123456789");
            var ttd2 = $('#ttd2').combogrid('getValue');
            ttd2 = ttd2.split(" ").join("123456789");
            var url = "<?php echo site_url(); ?>cetak_tukd/cetak_kas_akhir_unit";
            if (bulan == 0) {
                alert('Pilih Bulan dulu')
                exit()
            }
            if (ctglttd == '') {
                alert('Pilih Tanggal tanda tangan dulu')
                exit()
            }
            if (ttd == '') {
                alert('Pilih Bendahara Pengeluaran dulu')
                exit()
            }
            if (ttd2 == '') {
                alert('Pilih Pengguna Anggaran dulu')
                exit()
            }
            window.open(url + '/' + skpd + '/' + bulan + '/' + ctk + '/' + ttd + '/' + ttd2 + '/' + ctglttd + '/' + spasi, '_blank');
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



        <div class="card border-danger" style="width:930px">
            <div class="card-header bg-light" align="center">
                <h3>CETAK LAPORAN KAS AKHIR BULAN</h3>
            </div>
            <div class="card-body">


                <table id="sp2d" title="Cetak Buku Besar" width="100%" style="border-style: none; border-bottom: none">
                    <tr>
                        <td style="border-style: none; border-bottom: none" width="20%" height="40"><B>OPD/UNIT</B></td>
                        <td style="border-style: none; border-bottom: none" width="80%"><input id="sskpd" name="sskpd" readonly="true" style="width: 300px;border: none" readonly="true" />&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 350px; border:0;" readonly="true" /></td>
                    </tr>
                    <tr>
                        <td style="border-style: none; border-bottom: none" width="20%" height="40"><B>BULAN</B></td>
                        <td style="border-style: none; border-bottom: none"><?php echo $this->rka_model->combo_bulan('bulan1', 'onchange="javascript:validate1();"'); ?> </td onclick="return confirm('hapus data yang telah diinput?')">
                    </tr>
                    <tr>
                        <td style="border-style: none; border-bottom: none" colspan="4">
                            <div id="div_tgl">
                                <table style="width:100%;" border="0">
                                    <td style="border-style: none; border-bottom: none" width="20%">Tanggal TTD</td>
                                    <td style="border-style: none; border-bottom: none"><input type="text" id="tgl_ttd" style="width: 100px;" />
                                    </td>
                                </table>
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <td style="border-style: none; border-bottom: none" colspan="4">
                            <div id="div_bend">
                                <table style="width:100%;" border="0">
                                    <td style="border-style: none; border-bottom: none" width="20%">Bendahara Pengeluaran</td>
                                    <td style="border-style: none; border-bottom: none"><input type="text" id="ttd" style="width: 300px;" /> &nbsp;&nbsp;
                                        <input type="nama" id="nama" readonly="true" style="width: 350px;border:0" />

                                    </td>
                                </table>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="border-style: none; border-bottom: none" colspan="4">
                            <div id="div_bend2">
                                <table style="width:100%;" border="0">
                                    <td style="border-style: none; border-bottom: none" width="20%">Pengguna Anggaran</td>
                                    <td style="border-style: none; border-bottom: none"><input type="text" id="ttd2" style="width: 300px;" /> &nbsp;&nbsp;
                                        <input type="nama2" id="nama2" readonly="true" style="width: 350px;border:0" />

                                    </td>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-style: none; border-bottom: none" colspan="4">
                            <div id="div_bend2">
                                <table style="width:100%;" border="0">
                                    <td style="border-style: none; border-bottom: none" width="20%">Spasi</td>
                                    <td style="border-style: none; border-bottom: none"><input class="form-control" type="number" id="spasi" style="width: 100px;" value="1" />

                                    </td>
                                </table>
                            </div>
                        </td>
                    </tr>


                </table>



            </div>
            <div class="card-footer" align="center">
                <button class="btn btn-dark" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-television"></i> Layar</button>
                <button class="btn btn-warning" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-file-pdf-o"></i> PDF</button>
            </div>
        </div>
        <br />&nbsp;

        <br /> &nbsp;<font color="#fff">footer</font>
    </div>


</body>

</html>