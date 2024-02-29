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
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.maskedinput.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <script type="text/javascript">
        var status_transaksi = '';
        var no_sp2d = '';



        $(function() {

            $("#accordion").accordion();
            $("#lockscreen").hide();
            $("#frm").hide();
            $("#dialog-modal").dialog({
                height: 200,
                width: 700,
                modal: true,
                autoOpen: false
            });
            $('#dd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });
            // SKPD
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/jkn/TransaksiJKNController/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                }
            });
            // Combogrid noterima
            $('#trmpot').combogrid({
                columns: [
                    [{
                            field: 'no_bukti',
                            title: 'No',
                            width: 60
                        },
                        {
                            field: 'tgl_bukti',
                            title: 'Tanggal',
                            align: 'left',
                            width: 30
                        }

                    ]
                ],
            });
            // List Datagrid
            $(function() {
                $('#pot_out').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/loaddata',
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
                                field: 'no_bukti',
                                title: 'Nomor Bukti',
                                width: 40
                            },
                            {
                                field: 'tgl_bukti',
                                title: 'Tanggal Bukti',
                                width: 40
                            },
                            {
                                field: 'ket',
                                title: 'Keterangan',
                                width: 140,
                                align: "left"
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        no_bukti = rowData.no_bukti;
                        tgl_bukti = rowData.tgl_bukti;
                        jns_spp = rowData.jns_spp;
                        no_terima = rowData.no_terima;
                        npwp = rowData.npwp;
                        no_sp2d = rowData.no_sp2d;
                        ket = rowData.ket;
                        kd_skpd = rowData.kd_skpd;
                        status_transaksi = 'edit';
                        getpot(no_bukti, tgl_bukti, jns_spp, no_terima, npwp, no_sp2d, ket, kd_skpd);
                        section2();
                    }
                });
            });
        });

        function getpot(no_bukti, tgl_bukti, jns_spp, no_terima, npwp, no_sp2d, ket, kd_skpd) {
            $("#save").html('Update');
            $("#no_bukti").attr("value", no_bukti);
            $("#dd").datebox("setValue", tgl_bukti);
            $("#beban").attr("value", jns_spp);
            $("#trmpot").combogrid("setValue", no_terima);
            $("#dd").datebox("setValue", tgl_bukti);
            $("#npwp").attr("value", npwp);
            $("#ketentuan").attr("value", ket);
            $('#pot').edatagrid({
                url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/loaddata_trmpot',
                queryParams: ({
                    no_terima: no_terima,
                    skpd: kd_skpd
                })
            });
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/loaddata_trmpot",
                type: 'POST',
                data: ({
                    no_terima: no_terima,
                    skpd: kd_skpd
                }),
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, n) {
                        $("#total_potongan").attr("value", number_format(n['total'], 2, '.', ','));
                    });
                }
            });
            $('#pot').edatagrid({
                columns: [
                    [{
                            field: 'id',
                            title: 'ID',
                            hidden: true
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Sub Kegiatan',
                            width: 150,
                            align: 'left',
                            hidden: true
                        },
                        {
                            field: 'kd_rek_trans',
                            title: 'Kode Transaksi',
                            width: 150,
                            align: 'left',
                            hidden: true
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening Pot',
                            width: 150,
                            align: 'left'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening Pot',
                            width: 200,
                            align: 'left'
                        },
                        {
                            field: 'map_pot',
                            title: 'Map Pot',
                            width: 175,
                            align: 'left'
                        },
                        {
                            field: 'ntpn',
                            title: 'NTPN',
                            width: 175,
                            align: 'left'
                        },
                        {
                            field: 'ebilling',
                            title: 'NO Billing',
                            width: 175,
                            align: 'left'
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 200,
                            align: 'left'
                        }
                    ],

                ],
                onSelect: function(rowIndex, rowData) {
                    lcidx = rowIndex;
                    id = rowData.idx;
                    no_bukti = rowData.no_bukti;
                    kd_rek6 = rowData.kd_rek6;
                    nm_rek6 = rowData.nm_rek6;
                    nilai = rowData.nilai;
                    kd_skpd = rowData.skpd;
                    kd_rek_trans = rowData.kd_rek_trans;
                    ebilling = rowData.ebilling;
                    map_pot = rowData.map_pot;
                    kd_sub_kegiatan = rowData.kd_sub_kegiatan;
                    ntpn = rowData.ntpn;
                    get_rek(id, no_bukti, kd_rek6, nm_rek6, nilai, ntpn, ebilling);
                    edit_data();

                }
            });
        }

        function get_nourut() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/no_urut',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#no_bukti").attr("value", data.no_urut);
                }
            });

        }

        function section2() {
            $(document).ready(function() {
                $('#section2').click();
            });
        }

        function kosong() {
            $("#no_bukti").attr("value", '');
            $("#dd").datebox("setValue", '');
            $("#trmpot").combogrid("setValue", '');
            $("#beban").attr("Value", '');
            $("#npwp").attr("Value", '');
            $("#nmskpd").attr("Value", '');
            $("#ketentuan").attr("value", '');
            status_transaksi = 'tambah';
            // $('#pot').datagrid('reload')
            // $("#trmpot").combogrid("clear");
        }

        // Klik Tombol Tambah Transaksi
        $(document).ready(function() {
            $("#tambahtransaksi").click(function() {
                $('#section2').click();
                status_transaksi = 'tambah';
                if (status_transaksi == 'tambah') {
                    $("#save").html('Simpan');
                    get_nourut();
                    // List Potongan
                    $('#pot').edatagrid({
                        columns: [
                            [{
                                    field: 'id',
                                    title: 'ID',
                                    // hidden: true
                                },
                                {
                                    field: 'kd_sub_kegiatan',
                                    title: 'Sub Kegiatan',
                                    width: 150,
                                    align: 'left',
                                    hidden: true
                                },
                                {
                                    field: 'kd_rek_trans',
                                    title: 'Kode Transaksi',
                                    width: 150,
                                    align: 'left',
                                    hidden: true
                                },
                                {
                                    field: 'kd_rek6',
                                    title: 'Kode Rekening Pot',
                                    width: 150,
                                    align: 'left'
                                },
                                {
                                    field: 'nm_rek6',
                                    title: 'Nama Rekening Pot',
                                    width: 200,
                                    align: 'left'
                                },
                                {
                                    field: 'map_pot',
                                    title: 'Map Pot',
                                    width: 175,
                                    align: 'left'
                                },
                                {
                                    field: 'ntpn',
                                    title: 'NTPN',
                                    width: 175,
                                    align: 'left'
                                },
                                {
                                    field: 'ebilling',
                                    title: 'NO Billing',
                                    width: 175,
                                    align: 'left'
                                },
                                {
                                    field: 'nilai',
                                    title: 'Nilai',
                                    width: 200,
                                    align: 'left'
                                },
                            ]
                        ],
                        onSelect: function(rowIndex, rowData) {
                            lcidx = rowIndex;
                            id = rowData.idx;
                            no_bukti = rowData.no_bukti;
                            kd_rek6 = rowData.kd_rek6;
                            nm_rek6 = rowData.nm_rek6;
                            nilai = rowData.nilai;
                            kd_skpd = rowData.skpd;
                            kd_rek_trans = rowData.kd_rek_trans;
                            ebilling = rowData.ebilling;
                            map_pot = rowData.map_pot;
                            kd_sub_kegiatan = rowData.kd_sub_kegiatan;
                            ntpn = rowData.ntpn;
                            get_rek(id, no_bukti, kd_rek6, nm_rek6, nilai, ntpn, ebilling);
                            edit_data();

                        }
                    });
                    $('#trmpot').combogrid({
                        panelWidth: 500,
                        url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/no_terima',
                        idField: 'no_bukti',
                        textField: 'no_bukti',
                        mode: 'remote',
                        fitColumns: true,
                        columns: [
                            [{
                                    field: 'no_bukti',
                                    title: 'No',
                                    width: 60
                                },
                                {
                                    field: 'tgl_bukti',
                                    title: 'Tanggal',
                                    align: 'left',
                                    width: 30
                                }

                            ]
                        ],
                        onSelect: function(rowIndex, rowData) {
                            no_terima = rowData.no_bukti;
                            jns_spp = rowData.jns_spp;
                            no_sp2d = rowData.no_sp2d;
                            $('#beban').val(jns_spp);
                            $("#beban").prop("disabled", true);

                            $('#pot').edatagrid({
                                url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/loaddata_trmpot',
                                queryParams: ({
                                    no_terima: no_terima,
                                    skpd: $('#skpd').val()
                                })
                            });
                            $.ajax({
                                url: "<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/loaddata_trmpot",
                                type: 'POST',
                                data: ({
                                    no_terima: no_terima,
                                    skpd: $('#skpd').val()
                                }),
                                dataType: "json",
                                success: function(data) {
                                    $.each(data, function(i, n) {
                                        $("#total_potongan").attr("value", number_format(n['total'], 2, '.', ','));
                                    });
                                }
                            });
                        }
                    });
                }
            });
        });


        function get_rek(id, no_bukti, kd_rek6, nm_rek6, nilai, ntpn, ebilling) {
            $("#id").attr("value", id);
            $("#rek").attr("value", kd_rek6);
            $("#nm_rek5").attr("Value", nm_rek6);
            $("#nilei").attr("Value", nilai);
            $("#ntpn").attr("Value", ntpn);
            $("#ebilling").attr("Value", ebilling);
        }

        function edit_data() {
            judul = 'Isi Data Potongan';
            $("#dialog-modal").dialog({
                title: judul
            });
            $("#dialog-modal").dialog('open');
            $('#id').attr('readonly', true);
            $('#rek').attr('readonly', true);
            $('#nm_rek5').attr('readonly', true);
            $('#nilei').attr('readonly', true);
            document.getElementById("ntpn").focus;
            document.getElementById("ebilling").focus;
        }

        function keluar() {
            $("#dialog-modal").dialog('close');
        }

        function append_save_potongan() {
            var id = document.getElementById('id').value;
            var kd_rek = document.getElementById('rek').value;
            var ntpn = document.getElementById('ntpn').value;
            var no_terima = $("#trmpot").combogrid("getValue");
            var skpd = document.getElementById('skpd').value;
            var ebilling = document.getElementById('ebilling').value;
            if (ntpn == '') {
                alert('Silahkan isi NTPN');
                return;
            }
            if (ebilling == '') {
                alert('Silahkan isi Ebilling');
                return;
            }
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        no_terima: no_terima,
                        ntpn: ntpn,
                        ebilling: ebilling,
                        skpd: skpd,
                        idx: id,
                        kd_rek: kd_rek
                    }),
                    dataType: "json",
                    url: "<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/update_potongan",
                    success: function(data) {
                        if (data = '1') {
                            swal("OK", "Data potongan berhasil Disimpan", "success");
                            $("#dialog-modal").dialog('close');
                            $('#pot').edatagrid('reload');
                        }
                    }
                });
            });
            $('#pot').edatagrid({
                url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/loaddata_trmpot',
                queryParams: ({
                    no_terima: no_terima,
                    skpd: $('#skpd').val()
                })
            });
        }


        function simpan_data() {
            var no_bukti = $('#no_bukti').val();
            var tgl_setor = $('#dd').datebox('getValue');
            var no_terima = $("#trmpot").combogrid("getValue");
            var cjenis = document.getElementById('beban').value;
            var skpd = $('#skpd').val();
            var ketentuan = document.getElementById('ketentuan').value;
            var total_potongan = angka(document.getElementById('total_potongan').value);
            var npwp = $('#npwp').val();
            if (npwp == '') {
                alert('npwp tidak boleh kosong');
                return;
            }
            if (ketentuan == '') {
                alert('Keterangan tidak boleh kosong');
                return;
            }
            if (tgl_setor == '') {
                alert('tanggal setor tidak boleh kosong');
                return;
            }
            var datapot = $('#pot').edatagrid('selectAll');
            if (datapot.length == 0) {
                alert('Cek lagi datanya');
                return;
            }

            if (status_transaksi == 'tambah') {
                $('#pot').datagrid('selectAll');
                var rows = $('#pot').datagrid('getSelections');
                for (var p = 0; p < rows.length; p++) {
                    cnobukti = no_bukti;
                    kd_sub_kegiatan = rows[p].kd_sub_kegiatan;
                    kd_rek_trans = rows[p].kd_rek_trans;
                    kd_rek6 = rows[p].kd_rek6;
                    nm_rek6 = rows[p].nm_rek6;
                    cnmrek = rows[p].nm_rek6;
                    map_pot = rows[p].map_pot;
                    ntpn = rows[p].ntpn;
                    ebilling = rows[p].ebilling;
                    cnilai = angka(rows[p].nilai);

                    if (p > 0) {
                        lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, ntpn, map_pot, kd_sub_kegiatan)";
                        lcvaluespot = lcvaluespot + "," + "('" + cnobukti + "','" + kd_rek6 + "','" + nm_rek6 + "','" + cnilai + "','" + skpd + "','" + kd_rek_trans +
                            "','" + ebilling + "','" + ntpn + "','" + map_pot + "','" + kd_sub_kegiatan + "')";
                    } else {
                        lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, ntpn, map_pot, kd_sub_kegiatan)";
                        lcvaluespot = "('" + cnobukti + "','" + kd_rek6 + "','" + nm_rek6 + "','" + cnilai + "','" + skpd + "','" + kd_rek_trans +
                            "','" + ebilling + "','" + ntpn + "','" + map_pot + "','" + kd_sub_kegiatan + "')";
                    }
                }
                // Ajax
                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/simpan_data',
                        data: ({
                            kolompot: lcinsertpot,
                            nilaipot: lcvaluespot,
                            cno: no_bukti,
                            cket: ketentuan,
                            cjenis: cjenis,
                            cskpd: skpd,
                            ctgl: tgl_setor,
                            sp2d: no_sp2d,
                            npwp: npwp,
                            total_potongan: total_potongan,
                            no_terima: no_terima
                        }),
                        beforeSend: function() {
                            $("#save").attr("disabled", "disabled");
                        },
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                return;
                            } else if (status == '1') {
                                alert('Data Tersimpan..!!');
                            }
                        },
                        complete: function(response) {
                            $('#pot_out').edatagrid('reload');
                            $('#section1').click();
                            $("#save").removeAttr('disabled');
                        }
                    });
                });

            } else {
                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/update_data',
                        data: ({
                            cno: no_bukti,
                            cket: ketentuan,
                            cjenis: cjenis,
                            cskpd: skpd,
                            ctgl: tgl_setor,
                            no_terima: no_terima
                        }),
                        beforeSend: function() {
                            $("#save").attr("disabled", "disabled");
                        },
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal diupdate..!!');
                                exit();
                            } else if (status == '1') {
                                alert('Data Berhasil diupdate..!!');

                            }
                        },
                        complete: function(response) {
                            $('#pot_out').edatagrid('reload');
                            $('#section1').click();
                            $("#save").removeAttr('disabled');
                            // kosong();
                        }
                    });
                });

            }


        }

        function section1() {
            $(document).ready(function() {
                $('#section1').click();
                kosong();
            });
        }

        function hhapus() {
            var no_bukti = $('#no_bukti').val();
            var no_terima = $("#trmpot").combogrid("getValue");
            var skpd = $('#skpd').val();
            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/jkn/SetorPotonganController/hapus_data',
                    data: ({
                        cno: no_bukti,
                        cskpd: skpd,
                        no_terima: no_terima
                    }),
                    beforeSend: function() {
                        $("#save").attr("disabled", "disabled");
                    },
                    dataType: "json",
                    success: function(data) {
                        status = data;
                        if (status == '0') {
                            alert('Gagal dihapus..!!');
                            exit();
                        } else if (status == '1') {
                            alert('Data Berhasil dihapus..!!');
                            kosong();
                        }
                    },
                    complete: function(response) {
                        $('#pot_out').edatagrid('reload');
                        $('#section1').click();
                        $("#save").removeAttr('disabled');
                        location.reload(true);
                    }
                });
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

            <h3><a href="#" id="section1" onclick="javascript:$('#pot_out').edatagrid('reload')">List Setor Potongan</a></h3>
            <div>
                <p align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" id="tambahtransaksi">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                    <input type="text" value="" id="txtcari" />
                <table id="pot_out" title="List" style="width:870px;height:450px;">
                </table>


                </p>
            </div>

            <h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')">Input Setor Potongan</a></h3>
            <div style="height: 350px;">
                <p id="p1" style="font-size: x-large;color: red;"></p>
                <table border='0' style="font-size:11px; width:100%;">
                    <tr>
                        <td>No Bukti </td>
                        <td><input type="text" name="no_bukti" id="no_bukti" /></td>
                        <td>Tanggal </td>
                        <td><input id="dd" name="dd" type="text" /></td>
                    </tr>
                    <tr>
                        <td>No Terima </td>
                        <td><input type="text" name="trmpot" id="trmpot" /></td>
                        <td>Beban</td>
                        <td><select name="beban" id="beban" style="width: 210px;">
                                <option value="">--- Pilih ---</option>
                                <option value="1">Kapitasi</option>
                                <option value="2">Non Kapitasi (APBD)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>SKPD</td>
                        <td colspan="3"><input type="text" id="skpd" name="skpd" style="width:200px;" />&nbsp;&nbsp;&nbsp;
                            <input type="text" id="nmskpd" name="nmskpd" readonly="true" style="width:300px; border:0" />
                        </td>
                    </tr>
                    <tr>
                        <td>NPWP</td>
                        <td colspan='3'><input text="text" id="npwp" name="npwp" style="width:200px;"></input></td>
                    </tr>
                    <tr>
                        <td>Katerangan</td>
                        <td colspan='3'><textarea id="ketentuan" style="width:600px; height: 30px;"></textarea></td>
                    </tr>

                    <tr>
                        <td colspan="4" align="right">
                            <button id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_data();">Simpan</button>
                            <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>-->
                            <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>
                            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                        </td>
                        <!--<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:();">cetak</a>-->
                    </tr>
                </table>
                <table id="pot" title="List Potongan" style="width:900px;height:150px;">
                </table>
                <table>
                    <tr>
                        <td width="60%">&nbsp;</td>
                        <td align="right">Total Potongan</td>
                        <td align="right" width="27%">:&nbsp;<input type="text" id="total_potongan" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                    </tr>
                </table>
            </div>




        </div>

    </div>

    <div id="dialog-modal" title="NTPN">
        <p class="validateTips">INPUT NTPN</p>
        <fieldset>
            <table>
                <tr>
                    <td width="110px">Id:</td>
                    <td><input id="id" name="id" style="width: 170px;" readonly="true" /></td>
                </tr>
                <tr>
                    <td width="110px">Rekening:</td>
                    <td><input id="rek" name="rek" style="width: 170px;" /></td>
                </tr>
                <tr>
                    <td width="110px">Nama Rekening:</td>
                    <td><input id="nm_rek5" name="nm_rek5" style="width: 170px;" /></td>
                </tr>
                <tr>
                    <td width="110px">Nilai:</td>
                    <td><input id="nilei" name="nilei" style="width: 170px;" /></td>
                </tr>
                <tr>
                    <td width="110px">NTPN:</td>
                    <td><input id="ntpn" name="ntpn" style="width: 170px;" /></td>
                </tr>
                <tr>
                    <td width="110px">No Billing:</td>
                    <td><input id="ebilling" name="ebilling" style="width: 170px;" /></td>
                </tr>
            </table>
        </fieldset>
        <button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save_potongan();">Simpan</button>&nbsp; &nbsp; &nbsp;
        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>
    </div>

</body>

</html>