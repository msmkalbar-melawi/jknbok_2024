<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1">List Jurnal Umum BOK</a></h3>
            <div>
                <p align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section2();kosong();load_detail();">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                    <input type="text" value="" id="txtcari" />
                <table id="dg" title="List Jurnal" style="width:870px;height:600px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2">JURNAL UMUM</a></h3>
            <div style="height: 350px;">
                <p>
                <table align="center" style="width:100%;">
                    <tr>
                        <td style="border-bottom: double 1px red;"><i>No. Tersimpan<i></td>
                        <td style="border-bottom: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true" ; /></td>
                        <td style="border-bottom: double 1px red;">&nbsp;&nbsp;</td>
                        <td style="border-bottom: double 1px red;" colspan="2"><i>Tidak Perlu diisi atau di Edit</i></td>

                    </tr>
                    <tr>
                        <td>No. Voucher</td>
                        <td><input type="text" id="nomor" style="width: 200px;" /></td>
                        <td>&nbsp;&nbsp;</td>
                        <td>Tanggal Voucher</td>
                        <td><input type="text" id="tanggal" style="width: 140px;" /></td>
                    </tr>
                    <tr>
                        <td>S K P D</td>
                        <td><input id="skpd" name="skpd" readonly="true" style="width: 140px; border:0;" /></td>
                        <td></td>
                        <td>Nama SKPD :</td>
                        <td><input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true" /></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="4"><textarea id="keterangan" style="width: 650px; height: 40px;"></textarea></td>
                    </tr>
                    <tr>
                        <td>Jenis Jurnal</td>
                        <td><select name="j_j" id="j_j" onclick="javascript:get_j_d();">
                                <option value="0">Umum</option>
                                <option value="2">Koreksi Persediaan</option>
                                <option value="1">Revaluasi</option>
                                <option value="3">Lain - Lain</option>
                            </select></td>
                        <td colspan="4" align="right">

                            <?php
                            $usernm     = $this->session->userdata('pcNama');

                            if ($usernm == 'akuntansi') {
                                echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-add\" plain=\"true\" onclick=\"javascript:kosong();load_detail();\">Tambah</a>";
                                echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-save\" plain=\"true\" onclick=\"javascript:simpan_ju();\">Simpan</a>";
                                echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"javascript:hapus();section1();\">Hapus</a>";
                            } else {
                                echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-add\" plain=\"true\" onclick=\"javascript:kosong();load_detail();\">Tambah</a>";
                                echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-save\" plain=\"true\" onclick=\"javascript:simpan_ju();\">Simpan</a>";
                                echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-remove\" plain=\"true\" onclick=\"javascript:hapus();section1();\">Hapus</a>";
                            }
                            ?>

                            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                        </td>
                    </tr>
                </table>

                <?php
                $thn_ang = $this->session->userdata('pcThang');
                $thn_ang_l = $thn_ang - 1;
                ?>

                <tr>
                    <td><select name="j_d" id="j_d" onclick="javascript:get_mutasi();">
                            <option value=""></option>
                            <option value="99">Saldo Awal Neraca</option>
                            <option value="89">Saldo Awal LO</option>
                            <option value="1">Penyisihan Piutang</option>
                            <option value="2">Koreksi Penyusutan</option>
                            <option value="3">Hibah Keluar</option>
                            <option value="4">Mutasi Masuk antar OPD</option>
                            <option value="5">Mutasi Keluar antar OPD</option>
                            <option value="6">Penghapusan TPTGR</option>
                            <option value="7">Perubahan Kode Rekening</option>
                            <option value="8">Koreksi Tanah</option>
                            <option value="9">Koreksi Utang Belanja</option>
                            <option value="10">Reklass Antar Akun</option>
                            <option value="11">Tagihan Penjualan Angsuran</option>
                            <option value="12">Penyertaan Modal</option>
                            <option value="13">Persediaan APBN yang belum Tercatat TA <?php echo $thn_ang_l; ?></option>
                            <option value="15">Koreksi Dana Transfer Pemerintah Pusat</option>
                            <option value="16">Koreksi Gedung dan Bangunan</option>
                            <option value="17">Koreksi Persediaan</option>
                            <option value="18">Koreksi Kas</option>
                            <option value="19">Extracomptable</option>
                            <option value="23">Koreksi Peralatan dan Mesin</option>
                            <option value="24">Koreksi Jaringan Irigasi Jembatan</option>
                            <option value="26">Koreksi Aset Tetap Lainnya</option>
                            <option value="27">Koreksi Piutang</option>
                            <option value="28">Koreksi Aset Lain Lain</option>
                            <option value="30">Pelimpahan Masuk</option>
                            <option value="31">Pelimpahan Keluar</option>
                        </select></td>


                    <div id="mutasi">
                        <td>SKPD Mutasi
                            <input id="skpd_mutasi" name="skpd_mutasi" style="width:170px;" />
                            <input id="nmskpd_mutasi" name="nmskpd_mutasi" readonly="true" style="width:620px;border:0;background-color:transparent;color: black;" disabled="true" />
                        </td>
                    </div>


                </tr>
                <tr>
                    <td><select name="d_d" id="d_d">
                            <option value="0">Jurnal Umum</option>
                            <option value="20">Extracomptable</option>
                            <option value="21">Penghapusan</option>
                            <option value="22">Hibah Pemerintah Lainnya</option>
                            <option value="25">Koreksi Lain-Lain</option>
                            <option value="29">Reklas Aset Lain-Lain</option>
                        </select></td>
                </tr>

                <tr>
                    <td>
                        <p style="color:red; font-style: italic;">*Untuk penginputan JURNAL MUTASI MASUK / MUTASI KELUAR hanya perlu menginputkan Nomor BAST dan Tanggal BAST. Terima kasih.</p>
                    </td>
                </tr>

                <table id="dg1" title="Rekening" style="width:870px;height:350px;">
                </table>
                <div id="toolbar" align="right">
                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Kegiatan</a>
                    <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua Kegiatan</a>-->
                    <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus Kegiatan</a>
                </div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td align="right">Total Debet : <input type="text" id="total_d" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                        <td align="right">Total Kredit : <input type="text" id="total_k" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                    </tr>
                </table>

                </p>
            </div>

        </div>
    </div>


    <div id="dialog-modal" title="Input ">
        <p class="validateTips">Semua Inputan Harus Di Isi.</p>
        <fieldset>
            <table>
                <tr>
                    <td width="100">Jenis</td>
                    <td>:</td>
                    <td width="300"><input id="jenis" name="jenis" value="" /> </td>
                </tr>
                <tr>
                    <td width="100">Debet / Kredit</td>
                    <td>:</td>
                    <td width="300"><input id="rk" name="rk" value="" /> </td>
                </tr>
                <tr>
                    <td width="100">Kode Kegiatan</td>
                    <td>:</td>
                    <td width="300"><input id="giat" name="giat" style="width: 200px;" /></td>
                    <td>Nama Kegiatan</td>
                    <td>:</td>
                    <td><input type="text" id="nmgiat" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr>
                    <td width="100">Kode Rekening</td>
                    <td>:</td>
                    <td><input id="rek" name="rek" style="width: 200px;" /></td>
                    <td>Nama Rekening</td>
                    <td>:</td>
                    <td>
                    <textarea id="rek_ang" readonly="true" style="width: 200px; height :11px; resize : none" disabled></textarea>
                    <textarea id="nmrek" readonly="true" style="width: 500px; height: 40px; resize : none" disabled></textarea></td>
                   </tr>
                <tr>
                    <td width="100">Nilai</td>
                    <td>:</td>
                    <td><input type="text" id="nilai" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                </tr>
                <tr>
                    <td width="100">un-posting</td>
                    <td>:</td>
                    <td><input id="posting" type="checkbox" /></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td>
                        <?php
                        $usernm     = $this->session->userdata('pcNama');

                        if ($usernm == 'akuntansi') {
                            echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-save\" plain=\"true\" onclick=\"javascript:append_save();\">Simpan</a>";
                        } else {
                            echo "<a class=\"easyui-linkbutton\" iconCls=\"icon-save\" plain=\"true\" onclick=\"javascript:append_save();\">Simpan</a>";
                        }
                        ?>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="right">
                <tr>
                    <td>Total Debet</td>
                    <td>:</td>
                    <td><input type="text" id="totald" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;" /></td>
                    <td>Total Kredit</td>
                    <td>:</td>
                    <td><input type="text" id="totalk" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;" /></td>
                </tr>
            </table>
            <table id="dg2" title="Input Rekening" style="width:950px;height:270px;">
            </table>

        </fieldset>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <script>
        var idx = '';

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 650,
                width: 1000,
                modal: true,
                autoOpen: false
            });

            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/akuntansi/load_ju',
                idField: 'no_voucher',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                loadMsg: "Tunggu Sebentar....!!",
                pagination: "true",
                nowrap: "true",
                columns: [
                    [{
                            field: 'no_voucher',
                            title: 'Nomor Voucher',
                            width: 50
                        },
                        {
                            field: 'tgl_voucher',
                            title: 'Tanggal',
                            width: 30
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'ket',
                            title: 'Keterangan',
                            width: 100,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomor = rowData.no_voucher;
                    tgl = rowData.tgl_voucher;
                    skpd = rowData.kd_skpd;
                    nmskpd = rowData.nm_skpd;
                    ket = rowData.ket;
                    reev = rowData.reev;
                    j_d = rowData.tgl_real;
                    d_d = rowData.tgl_real;
                    total_d = rowData.total_d;
                    total_k = rowData.total_k;
                    skpd_mutasi = rowData.kd_skpd_mutasi;
                    nmskpd_mutasi = rowData.nm_skpd_mutasi;
                    get(nomor, tgl, skpd, nmskpd, ket, reev, j_d, d_d, total_d, total_k, skpd_mutasi, nmskpd_mutasi);
                    load_detail();
                },
                onDblClickRow: function(rowIndex, rowData) {
                    section2();

                }
            });

            $('#dg1').edatagrid({
                toolbar: '#toolbar',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                loadMsg: "Tunggu Sebentar....!!",
                nowrap: "true",
                rowStyler: function(index, row) {
                    if (row.rk == 'K') {
                        return 'background-color:#FCE6E6;';
                    }
                },
                onSelect: function(rowIndex, rowData) {
                    idx = rowIndex;
                }
            });

            $('#dg2').edatagrid({
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "true",
                loadMsg: "Tunggu Sebentar....!!",
                nowrap: "false",
                rowStyler: function(index, row) {
                    if (row.rk == 'K') {
                        return 'background-color:#FDDADA;';
                    }
                },
                columns: [
                    [{
                            field: 'hapus',
                            title: 'Hapus',
                            width: 11,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                            }
                        },
                        {
                            field: 'no_voucher',
                            title: 'Nomor Voucher',
                            width: 50,
                            hidden: "true"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kegiatan',
                            width: 70
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            hidden: "true"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rek',
                            width: 30
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'debet',
                            title: 'Debet',
                            width: 70,
                            align: "right"
                        },
                        {
                            field: 'kredit',
                            title: 'Kredit',
                            width: 70,
                            align: "right"
                        },
                        {
                            field: 'rk',
                            title: 'D/K',
                            width: 20,
                            align: "center"
                        },
                        {
                            field: 'jns',
                            title: 'Jenis',
                            width: 20,
                            align: "left",
                            hidden: 'true'
                        },
                        {
                            field: 'post',
                            title: 'Posting',
                            width: 20,
                            align: "left"
                        }
                    ]
                ]
            });

            $('#tanggal').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#skpd_mutasi').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/akuntansi/skpd',
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
                    $('#skpd_mutasi').attr('value', rowData.kd_skpd);
                    $('#nmskpd_mutasi').attr('value', rowData.nm_skpd);
                }
            });

            $('#jenis').combobox({
                valueField: 'value',
                textField: 'label',
                data: [{
                        label: '0 || Perubahan SAL',
                        value: '0'
                    },
                    {
                        label: '1 || Aset',
                        value: '1'
                    },
                    {
                        label: '2 || Hutang',
                        value: '2'
                    },
                    {
                        label: '3 || Ekuitas',
                        value: '3'
                    },
                    {
                        label: '4 || Pendapatan',
                        value: '4'
                    },
                    {
                        label: '5 || Belanja',
                        value: '5'
                    },
                    {
                        label: '6 || Pembiayaan',
                        value: '6'
                    },
                    {
                        label: '7 || Pendapatan LO',
                        value: '7'
                    },
                    {
                        label: '8 || Beban LO',
                        value: '8'
                    },
                ],
                onSelect: function(rec) {
                    cjenis = rec.value;
                    cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');  
                    frek = '';
                    $('#giat').combogrid('setValue', '');
                    $('#rek').combogrid('setValue', '');
                    $('#nmrek').attr('value', '');
                    $('#nmgiat').attr('value', '');
                    $('#giat').combogrid({
                        url: '<?php echo base_url(); ?>index.php/bok/JurnalUmumControllerBok/load_ju_trskpd',
                        queryParams: ({
                            kd: cskpd,
                            jenis: cjenis
                        })
                    });
                    var jj = 0;
                    $('#dg2').datagrid('selectAll');
                    var rows = $('#dg2').datagrid('getSelections');
                    for (var p = 0; p < rows.length; p++) {
                        cgiat = rows[p].kd_sub_kegiatan;
                        rek6 = rows[p].kd_rek6;
                        if (cgiat == '') {
                            if (jj > 0) {
                                frek = frek + ',' + rek6;
                            } else {
                                frek = rek6;
                            }
                            jj++;
                        }
                    }
                    //alert(cjenis) ;     
                    $('#dg2').edatagrid('unselectAll');
                    $('#rek').combogrid({
                        url: '<?php echo base_url(); ?>index.php/bok/JurnalUmumControllerBok/load_ju_rek',
                        queryParams: ({
                            jenis: cjenis,
                            giat: '',
                            kd: cskpd,
                            rek: frek
                        })
                    });

                }
            });

            $('#rk').combobox({
                valueField: 'value',
                textField: 'label',
                data: [{
                        label: 'Debet',
                        value: 'D'
                    },
                    {
                        label: 'Kredit',
                        value: 'K'
                    }
                ]
            });

            $('#giat').combogrid({
                panelWidth: 700,
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                columns: [
                    [{
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Sub Kegiatan',
                            width: 140
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            width: 700
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    cgiat = rowData.kd_sub_kegiatan;
                    cjenis = $('#jenis').combobox('getValue');
                    cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
                    frek = '';
                    $('#rek').combogrid('setValue', '');
                    $('#nmgiat').attr('value', rowData.nm_sub_kegiatan);
                    var jj = 0;
                    $('#dg2').datagrid('selectAll');
                    var rows = $('#dg2').datagrid('getSelections');
                    for (var p = 0; p < rows.length; p++) {
                        dgiat = rows[p].kd_sub_kegiatan;
                        rek6 = rows[p].kd_rek6;
                        if (dgiat != '') {
                            if (jj > 0) {
                                frek = frek + ',' + rek6;
                            } else {
                                frek = rek6;
                            }
                            jj++;
                        }
                    }
                    $('#dg2').edatagrid('unselectAll');
                    $('#rek').combogrid({
                        url: '<?php echo base_url(); ?>index.php/bok/JurnalUmumControllerBok/load_ju_rek',
                        queryParams: ({
                            jenis: cjenis,
                            giat: cgiat,
                            kd: cskpd,
                            rek: frek
                        })
                    });
                }
            });

            $('#rek').combogrid({
                panelWidth: 900,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                mode: 'remote',
                columns: [
                    [{
                            field: 'kd_rek_ang',
                            title: 'Rek Ang',
                            width: 100
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Rek 64',
                            width: 100
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 600
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nmrek").attr("value", rowData.nm_rek6);
                    $("#rek_ang").attr("value", rowData.kd_rek_ang);
                }
            });

            get_skpd();
            $("#j_d").hide();
            $("#d_d").hide();
            $("#skpd_mutasi").combogrid();

        });

        function get_skpd() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/JurnalUmumControllerBok/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    cskpd = data.kd_skpd;

                }
            });
        }

        function get_j_d() {

            var creev = document.getElementById('j_j').value;

            if (creev == '3') {
                $("#j_d").show();
                $("#d_d").hide();
            } else if (creev == '0') {
                $("#d_d").show();
                $("#j_d").hide();
            } else {
                $("#j_d").hide();
                $("#d_d").hide();
            }
        }



        $(function() {
            $("#mutasi").hide();
        });

        function get_mutasi() {

            var kondisi = document.getElementById('j_d').value;

            if (kondisi == '4' || kondisi == '5') {
                $("#mutasi").show();

            } else {
                $("#mutasi").hide();

            }
        }

        function kosong() {
            cdate = '<?php echo date("Y-m-d"); ?>';
            $("#nomor").attr("value", '');
            $("#tanggal").datebox("setValue", cdate);
            //$("#skpd").combogrid("setValue",'');
            //$("#nmskpd").attr("value",'');
            $("#keterangan").attr("value", '');
            $("#j_j").attr("value", '');
            $("#j_d").attr("value", '');
            $("#d_d").attr("value", '');
            $("#total_d").attr("value", '0');
            $("#total_k").attr("value", '0');
            $("#nomor").attr('disabled', false);
            document.getElementById("nomor").focus();
            status_jurnal = 'tambah';
        }

        function kosong2() {
            $('#jenis').combobox('setValue', '');
            $('#rk').combobox('setValue', '');
            $('#giat').combogrid('setValue', '');
            $('#rek').combogrid('setValue', '');
            $('#rek_64').combogrid('setValue', '');
            $('#nilai').attr('value', '0');
            $('#nmrek').attr('value', '');
            $('#nmgiat').attr('value', '');
        }

        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/akuntansi/load_ju',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function set_grid() {
            $('#dg1').edatagrid({
                columns: [
                    [{
                            field: 'no_voucher',
                            title: 'Nomor Voucher',
                            width: 50,
                            hidden: true
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kegiatan',
                            width: 70
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            hidden: true
                        },
                        {
                            field: 'kd_rek_ang',
                            title: 'Anggaran',
                            width: 30
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rek',
                            width: 30
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 100,
                            align: 'left'
                        },
                        {
                            field: 'debet',
                            title: 'Debet',
                            width: 70,
                            align: 'right'
                        },
                        {
                            field: 'kredit',
                            title: 'Kredit',
                            width: 70,
                            align: 'right'
                        },
                        {
                            field: 'rk',
                            title: 'D/K',
                            width: 20,
                            align: 'center'
                        },
                        {
                            field: 'jns',
                            title: 'Jenis',
                            width: 20,
                            align: 'left',
                            hidden: true
                        },
                        {
                            field: 'post',
                            title: 'Posting',
                            width: 20,
                            align: 'left'
                        }
                    ]
                ]
            });
        }

        function set_grid2() {
            $('#dg2').edatagrid({
                columns: [
                    [{
                            field: 'hapus',
                            title: 'Hapus',
                            width: 19,
                            align: 'center',
                            formatter: function(value, rec) {
                                return "<img src='<?php echo base_url(); ?>/assets/images/icon/cross.png' onclick='javascript:hapus_detail();'' />";
                            }
                        },
                        {
                            field: 'no_voucher',
                            title: 'Nomor Voucher',
                            width: 50,
                            hidden: true
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kegiatan',
                            width: 70
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            hidden: true
                        },
                        {
                            field: 'kd_rek_ang',
                            title: 'Anggaran',
                            width: 30
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rek',
                            width: 30
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 100,
                            align: 'left'
                        },
                        {
                            field: 'debet',
                            title: 'Debet',
                            width: 70,
                            align: 'right'
                        },
                        {
                            field: 'kredit',
                            title: 'Kredit',
                            width: 70,
                            align: 'right'
                        },
                        {
                            field: 'rk',
                            title: 'D/K',
                            width: 20,
                            align: 'center'
                        },
                        {
                            field: 'jns',
                            title: 'Jenis',
                            width: 20,
                            align: 'left',
                            hidden: true
                        },
                        {
                            field: 'post',
                            title: 'Posting',
                            width: 20,
                            align: 'left'
                        }
                    ]
                ]
            });
        }

        function section1() {
            $(document).ready(function() {
                $('#section1').click();
            });
            set_grid();
        }

        function section2() {
            $(document).ready(function() {
                $('#section2').click();
                document.getElementById("nomor").focus();
            });
            set_grid();
        }

        function tambah() {
            var no = document.getElementById('nomor').value;
            var totd = document.getElementById('total_d').value;
            var totk = document.getElementById('total_k').value;
            var cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            var ctgl = $('#tanggal').datebox('getValue');
            $('#dg2').edatagrid('reload');
            $('#totald').attr('value', totd);
            $('#totalk').attr('value', totk);
            kosong2();

            if (cskpd != '' && ctgl != '' && no != '') {
                $("#dialog-modal").dialog('open');
                set_grid2();
                load_detail2();
            } else {
                alert('Harap Isi Kode SKPD, Tanggal Transaksi & Nomor Transaksi ');
            }
        }

        function keluar() {
            $("#dialog-modal").dialog('close');
            $('#dg2').edatagrid('reload');
            kosong2();
        }

        function load_detail() {
            var i = 0;
            var kk = document.getElementById("nomor").value;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');             
            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/akuntansi/load_dju',
                    data: ({
                        no: kk
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            no = n['no_voucher'];
                            giat = n['kd_sub_kegiatan'];
                            nmgiat = n['nm_sub_kegiatan'];
                            rek6 = n['kd_rek6'];
                            rek_ang = n['kd_rek_ang'];
                            crk = n['rk'];

                            if (crk == 'K') {
                                nmrek6 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + n['nm_rek6'];
                            } else {
                                nmrek6 = n['nm_rek6'];
                            }
                            cdebet = number_format(n['debet'], 2, '.', ',');
                            ckredit = number_format(n['kredit'], 2, '.', ',');

                            cjns = n['jns'];
                            cpos = n['post'];

                            $('#dg1').edatagrid('appendRow', {
                                no_voucher: no,
                                kd_sub_kegiatan: giat,
                                nm_sub_kegiatan: nmgiat,
                                kd_rek_ang: rek_ang,
                                kd_rek6: rek6,
                                nm_rek6: nmrek6,
                                debet: cdebet,
                                kredit: ckredit,
                                rk: crk,
                                jns: cjns,
                                post: cpos
                            });
                        });
                    }
                });
            });
            set_grid();
        }

        function load_detail2() {
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');
            if (rows.length == 0) {
                set_grid2();
                exit();
            }
            for (var p = 0; p < rows.length; p++) {
                no = rows[p].no_voucher;
                giat = rows[p].kd_sub_kegiatan;
                nmgiat = rows[p].nm_sub_kegiatan;
                rek6 = rows[p].kd_rek6;
                nmrek6 = rows[p].nm_rek6;
                cdebet = rows[p].debet;
                ckredit = rows[p].kredit;
                crk = rows[p].rk;
                cjns = rows[p].jns;
                cpos = rows[p].post;
                $('#dg2').edatagrid('appendRow', {
                    no_voucher: no,
                    kd_sub_kegiatan: giat,
                    nm_sub_kegiatan: nmgiat,
                    kd_rek6: rek6,
                    nm_rek6: nmrek6,
                    debet: cdebet,
                    kredit: ckredit,
                    rk: crk,
                    jns: cjns
                });
            }
            $('#dg1').edatagrid('unselectAll');
        }

        function get(nomor, tgl, skpd, nmskpd, ket, reev, j_d, d_d, total_d, total_k, skpd_mutasi, nmskpd_mutasi) {
            $('#nomor').attr('value', nomor);
            $('#no_simpan').attr('value', nomor);
            $('#tanggal').datebox('setValue', tgl);
            //$('#skpd').combogrid('setValue',skpd);
            //$('#nmskpd').attr('value',nmskpd);
            $('#keterangan').attr('value', ket);
            $('#j_j').attr('value', reev);
            $('#j_d').attr('value', j_d);
            $('#d_d').attr('value', d_d);
            $('#total_d').attr('value', number_format(total_d, 2, '.', ','));
            $('#total_k').attr('value', number_format(total_k, 2, '.', ','));
            $("#skpd_mutasi").combogrid("setValue", skpd_mutasi);
            $("#nmskpd_mutasi").attr("Value", nmskpd_mutasi);

            //$("#nomor").attr('disabled',true);
            status_jurnal = 'edit';


        }

        function hapus_giat() {
            var totd = angka(document.getElementById('total_d').value);
            var totk = angka(document.getElementById('total_k').value);
            var rows = $('#dg1').edatagrid('getSelected');
            var cgiat = rows.kd_sub_kegiatan;
            var crek = rows.kd_rek6;
            if (rows.rk == 'D') {
                cnil = rows.debet;
            } else {
                cnil = rows.kredit;
            }

            var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : ' + cgiat + ' Rekening : ' + crek + ' Nilai : ' + cnil);
            if (tny == true) {
                if (rows.rk == 'D') {
                    totd = totd - angka(rows.debet);
                    $('#total_d').attr('value', number_format(totd, 2, '.', ','));
                } else {
                    totk = totk - angka(rows.kredit);
                    $('#total_k').attr('value', number_format(totk, 2, '.', ','));
                }
                $('#dg1').edatagrid('deleteRow', idx);
            }
        }

        function hapus_detail() {
            var rows = $('#dg2').edatagrid('getSelected');
            cgiat = rows.kd_sub_kegiatan;
            crek = rows.kd_rek6;
            cdeb = rows.debet;
            ckre = rows.kredit;
            crk = rows.rk;
            if (crk == 'K') {
                cnil = ckre;
            } else {
                cnil = cdeb;
            }

            var idx = $('#dg2').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : ' + cgiat + ' Rekening : ' + crek + ' Nilai : ' + cnil);
            if (tny == true) {
                $('#dg2').edatagrid('deleteRow', idx);
                $('#dg1').edatagrid('deleteRow', idx);
                if (crk == 'D') {
                    total = angka(document.getElementById('totald').value) - angka(cnil);
                    $('#totald').attr('value', number_format(total, 2, '.', ','));
                    $('#total_d').attr('value', number_format(total, 2, '.', ','));
                } else {
                    total = angka(document.getElementById('totalk').value) - angka(cnil);
                    $('#totalk').attr('value', number_format(total, 2, '.', ','));
                    $('#total_k').attr('value', number_format(total, 2, '.', ','));
                }
                kosong2();
            }
        }

        function hapus() {
            var cnomor = document.getElementById('nomor').value;
            var urll = '<?php echo base_url(); ?>index.php/akuntansi/hapus_ju';
            var tny = confirm('Yakin Ingin Menghapus Data, Nomor Voucher : ' + cnomor);
            if (tny == true) {
                $(document).ready(function() {
                    $.ajax({
                        url: urll,
                        dataType: 'json',
                        type: "POST",
                        data: ({
                            no: cnomor
                        }),
                        success: function(data) {
                            status = data.pesan;
                            if (status == '1') {
                                alert('Data Berhasil Terhapus');
                            } else {
                                alert('Gagal Hapus');
                            }
                        }

                    });
                });
            }
        }

        function append_save() {
            var cjnstetap = document.getElementById('posting').checked;
            var no = document.getElementById('nomor').value;
            var cjns = $('#jenis').combobox('getValue');
            var cgiat = $('#giat').combogrid('getValue');
            var nmgiat = document.getElementById('nmgiat').value;
            var crek = $('#rek').combogrid('getValue');
            var crek_ang = document.getElementById('rek_ang').value;
            var nmrek = document.getElementById('nmrek').value;
            var cnil = document.getElementById('nilai').value;
            var ctotald = document.getElementById('totald').value;
            var ctotalk = document.getElementById('totalk').value;
            var crk = $('#rk').combobox('getValue');
            var cnilai = angka(cnil);

            if (!(cjns == '' || crek == '' || crk == '')) {
                if (cjnstetap == true) {
                    pos = '0';
                } else {
                    pos = '1';
                }
                if (crk == 'D') {
                    cdeb = cnil;
                    ckre = '0';
                    ctotald = angka(ctotald) + cnilai;
                    $('#totald').attr('value', number_format(ctotald, 2, '.', ','));
                    $('#total_d').attr('value', number_format(ctotald, 2, '.', ','));
                } else {
                    cdeb = '0';
                    ckre = cnil;
                    nmrek = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + nmrek;
                    ctotalk = angka(ctotalk) + cnilai;
                    $('#totalk').attr('value', number_format(ctotalk, 2, '.', ','));
                    $('#total_k').attr('value', number_format(ctotalk, 2, '.', ','));
                }

                $('#dg1').edatagrid('appendRow', {
                    no_voucher: no,
                    kd_sub_kegiatan: cgiat,
                    nm_sub_kegiatan: nmgiat,
                    kd_rek_ang: crek_ang,
                    kd_rek6: crek,
                    nm_rek6: nmrek,
                    debet: cdeb,
                    kredit: ckre,
                    rk: crk,
                    jns: cjns,
                    post: pos
                });
                $('#dg2').edatagrid('appendRow', {
                    no_voucher: no,
                    kd_sub_kegiatan: cgiat,
                    nm_sub_kegiatan: nmgiat,
                    kd_rek_ang: crek_ang,
                    kd_rek6: crek,
                    nm_rek6: nmrek,
                    debet: cdeb,
                    kredit: ckre,
                    rk: crk,
                    jns: cjns,
                    post: pos
                });

                kosong2();
            } else {
                alert('Jenis Rekening, Kode Rekening dan Nilai tidak boleh kosong');
                exit();
            }
        }

        function simpan_ju() {
            var cno = document.getElementById('nomor').value;
            var no_simpan = document.getElementById('no_simpan').value;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById('skpd').value;
            var cnmskpd = document.getElementById('nmskpd').value;
            var cket = document.getElementById('keterangan').value;
            var ctotald = angka(document.getElementById('total_d').value);
            var ctotalk = angka(document.getElementById('total_k').value);
            var creev = document.getElementById('j_j').value;
            var j_d = document.getElementById('j_d').value;
            var d_d = document.getElementById('d_d').value;
            var skpd_mutasi = $('#skpd_mutasi').combogrid('getValue');
            var nmskpd_mutasi = document.getElementById('nmskpd_mutasi').value;

            if (creev == '3') {
                cj_d = j_d;
            } else {
                cj_d = d_d;
            }


            if (cno == '') {
                alert('Nomor Tidak Boleh Kosong');
                exit();
            }
            if (ctgl == '') {
                alert('Tanggal Tidak Boleh Kosong');
                exit();
            }
            if (cskpd == '') {
                alert('Kode SKPD Tidak Boleh Kosong');
                exit();
            }
            if ((ctotald < 0) || (ctotalk < 0)) {
                alert('Rincian tidak boleh Minus');
                exit();
            }

            if (ctotald != ctotalk) {
                alert('Kredit dan Debet harus sama!');
                exit();
            }

            if (creev == '3' && cj_d == '') {
                alert('Harap Dipilih Kategori dari Jenis Lain-Lain');
                exit();
            }

            if (creev == '0' && cj_d == '') {
                alert('Harap Dipilih Kategori dari Jenis Umum');
                exit();
            }

            if (j_d == 4) {


                if (skpd_mutasi == '') {
                    alert('SKPD Mutasi Tidak boleh Kosong');
                    exit();
                }

                skpd_mutasi_ = skpd_mutasi;
                nmskpd_mutasi_ = nmskpd_mutasi;
                ket_mutasi1 = 'Mutasi Masuk dari ';
                ket_mutasi2 = ' Berdasarkan BAST ';
            } else if (j_d == 5) {


                if (skpd_mutasi == '') {
                    alert('SKPD Mutasi Tidak boleh Kosong');
                    exit();
                }

                skpd_mutasi_ = skpd_mutasi;
                nmskpd_mutasi_ = nmskpd_mutasi;
                ket_mutasi1 = 'Mutasi Keluar ke ';
                ket_mutasi2 = ' Berdasarkan BAST ';
            } else {
                skpd_mutasi_ = '';
                nmskpd_mutasi_ = '';
                ket_mutasi1 = '';
                ket_mutasi2 = '';
            }


            if (status_jurnal == 'tambah') {
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: cno,
                            tabel: 'trhju_pkd',
                            field: 'no_voucher'
                        }),
                        url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1) {
                                alert("Nomor Telah Dipakai!");
                                document.getElementById("nomor").focus();
                                exit();
                            }
                            if (status_cek == 0) {
                                alert("Nomor Bisa dipakai");



                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        data: ({
                                            tabel: 'trhju_pkd',
                                            no: cno,
                                            tgl: ctgl,
                                            skpd: cskpd,
                                            nmskpd: cnmskpd,
                                            ket: cket,
                                            total_d: ctotald,
                                            total_k: ctotalk,
                                            reev: creev,
                                            tgl_real: cj_d,
                                            ket_mutasi1: ket_mutasi1,
                                            ket_mutasi2: ket_mutasi2,
                                            skpd_mutasi: skpd_mutasi_,
                                            nmskpd_mutasi: nmskpd_mutasi_
                                        }),
                                        url: '<?php echo base_url(); ?>/index.php/akuntansi/simpan_ju',
                                        success: function(data) {
                                            status = data.pesan;

                                            if (status == '0') {
                                                alert('Gagal Simpan...!!');
                                                return;
                                            } else if (status == '2') {
                                                alert('Nomor telah dipakai!!');
                                                return;
                                            } else {
                                                $('#dg1').datagrid('selectAll');
                                                var dgrid = $('#dg1').datagrid('getSelections');
                                                for (var w = 0; w < dgrid.length; w++) {
                                                    cnovoucher = cno;
                                                    ckdgiat = dgrid[w].kd_sub_kegiatan;
                                                    cnmgiat = dgrid[w].nm_sub_kegiatan;
                                                    crek_ang = dgrid[w].kd_rek_ang;
                                                    crek = dgrid[w].kd_rek6;
                                                    cnmrek = dgrid[w].nm_rek6;
                                                    cnmrek = cnmrek.split('&nbsp;').join('');
                                                    cdebet = angka(dgrid[w].debet);
                                                    ckredit = angka(dgrid[w].kredit);
                                                    crk = dgrid[w].rk;
                                                    cjns = dgrid[w].jns;
                                                    cpos = dgrid[w].post;

                                                    if (w > 0) {
                                                        csql = csql + ",('" + cnovoucher + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cdebet + "','" + ckredit + "','" + crk + "','" + cjns + "','" + cskpd + "','" + cpos + "','" + w + "','" + crek_ang + "')";
                                                    } else {
                                                        csql = " values('" + cnovoucher + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cdebet + "','" + ckredit + "','" + crk + "','" + cjns + "','" + cskpd + "','" + cpos + "','" + w + "','" + crek_ang + "')";
                                                    }

                                                }
                                                $(document).ready(function() {
                                                    $.ajax({
                                                        type: "POST",
                                                        dataType: 'json',
                                                        data: ({
                                                            tabel: 'trdju_pkd',
                                                            no: cno,
                                                            sql: csql
                                                        }),
                                                        url: '<?php echo base_url(); ?>/index.php/akuntansi/simpan_ju',
                                                        success: function(data) {
                                                            status = data.pesan;
                                                            if (status == '1') {
                                                                alert('Data Berhasil Tersimpan');
                                                                status_jurnal = 'edit;'
                                                                $("#no_simpan").attr("value", cno);
                                                            } else {
                                                                alert('Data Gagal Tersimpan');
                                                            }
                                                        }
                                                    });
                                                });

                                            }

                                        }
                                    });
                                });
                                //----

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
                            no: cno,
                            tabel: 'trhju_pkd',
                            field: 'no_voucher'
                        }),
                        url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1 && cno != no_simpan) {
                                alert("Nomor Telah Dipakai!");
                                exit();
                            }
                            if (status_cek == 0 || cno == no_simpan) {
                                alert("Nomor Bisa dipakai");
                                //---
                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        data: ({
                                            tabel: 'trhju_pkd',
                                            no_bku: no_simpan,
                                            no: cno,
                                            tgl: ctgl,
                                            skpd: cskpd,
                                            nmskpd: cnmskpd,
                                            ket: cket,
                                            total_d: ctotald,
                                            total_k: ctotalk,
                                            reev: creev,
                                            tgl_real: cj_d,
                                            skpd_mutasi: skpd_mutasi_,
                                            nmskpd_mutasi: nmskpd_mutasi_
                                        }),
                                        url: '<?php echo base_url(); ?>/index.php/akuntansi/simpan_ju_edit',
                                        success: function(data) {
                                            status = data.pesan;

                                            if (status == '0') {
                                                alert('Gagal Simpan...!!');
                                                return;
                                            } else if (status == '2') {
                                                alert('Nomor telah dipakai!!');
                                                return;
                                            } else {
                                                $('#dg1').datagrid('selectAll');
                                                var dgrid = $('#dg1').datagrid('getSelections');
                                                for (var w = 0; w < dgrid.length; w++) {
                                                    cnovoucher = cno;
                                                    ckdgiat = dgrid[w].kd_sub_kegiatan;
                                                    cnmgiat = dgrid[w].nm_sub_kegiatan;
                                                    crek_ang = dgrid[w].kd_rek_ang;
                                                    crek = dgrid[w].kd_rek6;
                                                    cnmrek = dgrid[w].nm_rek6;
                                                    cnmrek = cnmrek.split('&nbsp;').join('');
                                                    cdebet = angka(dgrid[w].debet);
                                                    ckredit = angka(dgrid[w].kredit);
                                                    crk = dgrid[w].rk;
                                                    cjns = dgrid[w].jns;
                                                    cpos = dgrid[w].post;

                                                    if (w > 0) {
                                                        csql = csql + ",('" + cnovoucher + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cdebet + "','" + ckredit + "','" + crk + "','" + cjns + "','" + cskpd + "','" + cpos + "','" + w + "','" + crek_ang + "')";
                                                    } else {
                                                        csql = " values('" + cnovoucher + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cdebet + "','" + ckredit + "','" + crk + "','" + cjns + "','" + cskpd + "','" + cpos + "','" + w + "','" + crek_ang + "')";
                                                    }

                                                }
                                                $(document).ready(function() {
                                                    $.ajax({
                                                        type: "POST",
                                                        dataType: 'json',
                                                        data: ({
                                                            tabel: 'trdju_pkd',
                                                            no_bku: no_simpan,
                                                            no: cno,
                                                            sql: csql
                                                        }),
                                                        url: '<?php echo base_url(); ?>/index.php/akuntansi/simpan_ju_edit',
                                                        success: function(data) {
                                                            status = data.pesan;
                                                            if (status == '1') {
                                                                alert('Data Berhasil Tersimpan');
                                                                status_jurnal = 'edit;'
                                                                $("#no_simpan").attr("value", cno);
                                                            } else {
                                                                alert('Data Gagal Tersimpan');
                                                            }
                                                        }
                                                    });
                                                });

                                            }

                                        }
                                    });
                                });
                            }
                        }
                    });
                });
            }

        }
    </script>
</body>

</html>