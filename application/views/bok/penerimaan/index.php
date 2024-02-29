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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <style>
        #tagih {
            position: relative;
            width: 500px;
            height: 70px;
            padding: 0.4em;
        }
    </style>
    <script type="text/javascript">
        var kode = '';
        var giat = '';
        var nomor = '';
        var judul = '';
        var cid = 0;
        var lcidx = 0;
        var lcstatus = '';

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 650,
                width: 900,
                modal: true,
                autoOpen: false,
            });
            $("#tagih").hide();
            get_skpd();
            get_tahun();
            document.getElementById("pesan").innerHTML = "";
        });


        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/loaddata',
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
                            field: 'no_terima',
                            title: 'Nomor Terima',
                            width: 50,
                            align: "center"
                        },
                        {
                            field: 'tgl_terima',
                            title: 'Tanggal',
                            width: 30
                        },
                        {
                            field: 'kd_skpd',
                            title: 'S K P D',
                            width: 30,
                            align: "center"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Rekening',
                            width: 50,
                            align: "center"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 50,
                            align: "right"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomor = rowData.no_terima;
                    no_tetap = rowData.no_tetap;
                    tgl = rowData.tgl_terima;
                    kode = rowData.kd_skpd;
                    lcket = rowData.keterangan;
                    lcrek = rowData.kd_rek6;
                    kd_rek_lo = rowData.kd_rek;
                    jenis = rowData.jenis;
                    sumber = rowData.sumber;
                    lcnilai = rowData.nilai;
                    sts = rowData.sts_tetap;
                    giat = rowData.kd_sub_kegiatan;
                    tgl_tetap = rowData.tgl_tetap;
                    kunci = rowData.kunci;
                    lcidx = rowIndex;
                    user_nm = rowData.user_nm;
                    no_kas = rowData.no_kas;
                    lcstatus = 'edit';
                    get(nomor, no_tetap, tgl, kode, lcket, lcrek, kd_rek_lo, sumber, lcnilai, sts, giat, tgl_tetap, user_nm, kunci, jenis,no_kas);
                },
                onDblClickRow: function(rowIndex, rowData) {
                    lcstatus = 'edit';
                    lcidx = rowIndex;
                    judul = 'Edit Data Penerimaan';
                    edit_data();
                }
            });


            $('#tanggal').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {

                }
            });

            $('#pengirim').combogrid({
                panelWidth: 700,
                idField: 'kd_pengirim',
                textField: 'kd_pengirim',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/penerimaan/load_pengirim',
                columns: [
                    [{
                            field: 'kd_pengirim',
                            title: 'Kode Pengirim',
                            width: 140
                        },
                        {
                            field: 'nm_pengirim',
                            title: 'Nama Pengirim',
                            width: 700
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kd_pengirim = rowData.kd_pengirim;
                    $("#nmpengirim").attr("value", rowData.nm_pengirim);
                }

            });

        });


        function get_skpd() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    kode = data.kd_skpd;
                    validate_rek();
                    penetapan();
                }
            });
        }




        function validate_rek() {
            $(function() {
                $('#rek').combogrid({
                    panelWidth: 700,
                    idField: 'kd_rek6',
                    textField: 'kd_rek6',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/ambil_rek_tetap',
                    columns: [
                        [{
                                field: 'kd_rek6',
                                title: 'Kode Rek LRA',
                                width: 100
                            },
                            {
                                field: 'kd_rek_lo',
                                title: 'Kode Rek LO',
                                width: 100
                            },
                            {
                                field: 'nm_rek',
                                title: 'Uraian Rinci',
                                width: 200
                            },
                            {
                                field: 'nm_rek5',
                                title: 'Uraian Obyek',
                                width: 200
                            },
                            {
                                field: 'kd_sub_kegiatan',
                                title: 'Sub Kegiatan',
                                width: 500
                            }

                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {

                        $("#nmrek").attr("value", rowData.nm_rek.toUpperCase());
                        $("#kd_rek_lo").attr("value", rowData.kd_rek_lo);
                        $("#giat").attr("value", rowData.kd_sub_kegiatan);
                    }
                });
            });
        }

        function get_tahun() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/penerimaan/config_tahun',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    tahun_anggaran = data;
                }
            });

        }

        function penetapan() {
            var kode = kode;
            $('#notetap').combogrid({
                panelWidth: 420,
                idField: 'no_tetap',
                textField: 'no_tetap',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/load_no_tetap',
                queryParams: ({
                    cari: kode
                }),
                columns: [
                    [{
                            field: 'no_tetap',
                            title: 'No Penetapan',
                            width: 140
                        },
                        {
                            field: 'tgl_tetap',
                            title: 'Tanggal',
                            width: 140
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            width: 140
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    var ststagih = '1';
                    $("#tgltetap").attr("value", rowData.tgl_tetap);
                    $("#rek").combogrid("setValue", rowData.kd_rek6);
                    $("#kd_rek_lo").attr("Value", rowData.kd_rek_lo);
                    $("#nmrek").attr("Value", rowData.nm_rek6);
                    $("#nomor").attr("Value", rowData.no_tetap);
                    $("#giat").attr("Value", rowData.kd_sub_kegiatan);
                    $("#ket").attr("value", rowData.keterangan);
                    $("#nilai").attr("value", number_format(rowData.nilai, 2, '.', ','));
                    $("#nil_tetap").attr("value", number_format(rowData.nilai, 2, '.', ','));
                }
            });
        }

        function section2() {
            $(document).ready(function() {
                $('#section2').click();
            });
        }

        function section1() {
            $(document).ready(function() {
                $('#section1').click();
                $('#dg').edatagrid('reload');
            });
        }

        function get(nomor, no_tetap, tgl, kode, lcket, lcrek, kd_rek_lo, sumber, lcnilai, sts, giat, tgl_tetap, user_nm, kunci, jenis,no_kas) {
            // alert(lcrek);
            $("#notetap").combogrid("setValue", no_tetap);
            $("#nomor_kas").attr("value", no_kas);
            $("#nomor").attr("value", nomor);
            $("#nomor_hide").attr("value", nomor);
            $("#kunci").attr("value", kunci);
            $("#tanggal").datebox("setValue", tgl);
            $("#rek").combogrid("setValue", lcrek);
            $("#pengirim").combogrid("setValue", sumber);
            $("#kd_rek_lo").attr("value", kd_rek_lo);
            // $("#rekcheck").attr("value", rek);
            $("#giat").attr("Value", giat);
            $("#nilai").attr("value", lcnilai);
            $("#ket").attr("value", lcket);
            if (sts == '1') {
                $("#status").attr("checked", true);
                $("#tagih").show();
                $("#nil_tetap").attr("value", lcnilai);
                $("#tgltetap").attr("value", tgl_tetap);
            } else {
                $("#status").attr("checked", false);
                $("#tagih").hide();
                $("#nil_tetap").attr("value", '');
                $("#tgltetap").attr("value", '');
            }
            if (kunci == '1') {
                $('#save').linkbutton('disable');
            } else {
                $('#save').linkbutton('enable');
                $('#del').linkbutton('enable');
            }
            $("#save").html('Update');
        }


        function kosong() {
            $("#nomor").attr("value", '');
            $("#nomor_hide").attr("value", '');
            $("#tanggal").datebox("setValue", '');
            $("#nilai").attr("value", '');
            $("#rek").combogrid("setValue", '');
            $("#pengirim").combogrid("setValue", '');
            $("#nmrek").attr("value", '');
            $("#giat").attr("Value", '');
            $("#ket").attr("value", '');
            $("#notetap").combogrid("setValue", '');
            $("#tgltetap").attr("value", '');
            $("#status").attr("checked", false);
            $("#tagih").hide();
            $('#save').linkbutton('enable');
            document.getElementById("pesan").innerHTML = "";
            document.getElementById("nomor").focus();
            lcstatus = 'tambah';
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/penerimaan/load_terima',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }



        function simpan_terima() {
            var dgn_penetapan = document.getElementById('status').checked;
            var cno = document.getElementById('nomor').value;
            var cno_nokas = document.getElementById('nomor_kas').value;
            var cno_nokas_hide = document.getElementById('nomor_kas_hide').value;
            var cno_hide = document.getElementById('nomor_hide').value;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById('skpd').value;
            var cnmskpd = document.getElementById('nmskpd').value;
            var rek = $('#rek').combogrid('getValue');
            var kd_rek_lo = $('#kd_rek_lo').val();
            var cpengirim = '-';
            var kegi = document.getElementById('giat').value;
            var lcket = document.getElementById('ket').value;
            var lntotal = angka(document.getElementById('nilai').value);
            lctotal = number_format(lntotal, 0, '.', ',');
            var cstatus = document.getElementById('status').checked;
            var tot_tetap = angka(document.getElementById('nil_tetap').value);
            var tahun_input = ctgl.substring(0, 4);
            //---------------------------------------------------------------------------------------------
            // if (dgn_penetapan == false) {
            //     swal("Error", "Penerimaan harus ada penetapan", "error");
            //     return;
            // }
            if (tahun_input != tahun_anggaran) {
                swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
                exit();
            }
            // if (cstatus == false) {
            //     swal("Error", "Harus menggunakan penetapan", "warning");
            //     return;
            // } else {
            //     if (tot_tetap < lntotal) {
            //         swal("Error", "Melebihi nilai", "warning");
            //         exit();
            //     }
            // }

            var ctetap = $('#notetap').combogrid('getValue');
            var ctgltetap = document.getElementById('tgltetap').value;

            if (cno == '') {
                swal("Error", "Nomor  Tidak Boleh Kosong", "warning");
                exit();
            }

            if (cno == '') {
                swal("Error", "Nomor  Tidak Boleh Kosong", "warning");
                exit();
            }

            if (cpengirim == '') {
                swal("Error", "Pengirim Tidak Boleh Kosong", "warning");
                exit();
            }

            if (rek == '') {
                swal("Error", "Rekening  Tidak Boleh Kosong", "warning");
                exit();
            }

            if (ctgl == '') {
                swal("Error", "Tanggal Tidak Boleh Kosong", "warning");
                exit();
            }
            if (cskpd == '') {
                swal("Error", "SKPD Tidak Boleh Kosong", "warning");
                exit();
            }

            if (lcstatus == 'tambah') {
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: cno,
                            tabel: 'tr_terima',
                            field: 'no_terima'
                        }),
                        url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1) {
                                swal("Error", "Nomor sudah ada", "warning");
                                document.getElementById("nomor").focus();
                                return;
                            }
                            if (status_cek == 0) {
                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/simpan_data',
                                        data: {
                                            tabel: 'jkn_tr_terima',
                                            cid: 'no_terima',
                                            lcid: cno,
                                            no_terima: cno,
                                            tgl_terima: ctgl,
                                            no_tetap: ctetap,
                                            tgl_tetap: ctgltetap,
                                            sts_tetap: cstatus,
                                            kd_skpd: cskpd,
                                            kd_sub_kegiatan: kegi,
                                            kd_rek6: rek,
                                            kd_rek_lo: kd_rek_lo,
                                            nilai: lntotal,
                                            keterangan: lcket,
                                            jenis: '1',
                                            sumber: cpengirim,
                                            cno_nokas: cno_nokas
                                        },
                                        dataType: "json",
                                        beforeSend: function() {
                                            $("#save").attr("disabled", "disabled");
                                            setTimeout(3000);
                                        },
                                        success: function(response) {
                                            if (response.pesan == '1') {
                                                $('#dg').datagrid('reload');
                                                $("#dialog-modal").dialog('close');
                                                swal("Berhasil", "Data Berhasil Tersimpan", "success");
                                            }
                                        },
                                        complete: function(response) {
                                            $("#save").removeAttr('disabled');
                                            kosong();
                                        },
                                    });
                                });
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
                            no_hide: cno_hide
                        }),
                        url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == '1') {
                                swal("Error", "Nomor telah dipakai", "warning");
                                return;
                            }
                            if (status_cek == 0) {
                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/update_data',
                                        data: {
                                            tabel: 'jkn_tr_terima',
                                            cid: 'no_terima',
                                            lcid: cno,
                                            no_terima: cno,
                                            tgl_terima: ctgl,
                                            no_tetap: ctetap,
                                            tgl_tetap: ctgltetap,
                                            sts_tetap: cstatus,
                                            kd_skpd: cskpd,
                                            kd_sub_kegiatan: kegi,
                                            kd_rek6: rek,
                                            kd_rek_lo: kd_rek_lo,
                                            nilai: lntotal,
                                            keterangan: lcket,
                                            jenis: '1',
                                            sumber: cpengirim,
                                            no_hide: cno_hide
                                        },
                                        dataType: "json",
                                        beforeSend: function() {
                                            $("#save").attr("disabled", "disabled");
                                        },
                                        success: function(data) {
                                            if (data.pesan == '0') {
                                                swal("Error", "Maaf! Data Tidak Berhasil Diupdate", "error");
                                            } else {
                                                $('#dg').datagrid('reload');
                                                $("#dialog-modal").dialog('close');
                                                swal("Berhasil", "Data Berhasil Diupdate", "success");



                                            }
                                        },
                                        complete: function(response) {
                                            $("#save").removeAttr('disabled');
                                            kosong();
                                        },
                                    });
                                });
                                //akhir
                            }
                        }
                    });
                });
            }

        }



        function edit_data() {
            lcstatus = 'edit';
            judul = 'Edit Data Penerimaan';
            $("#dialog-modal").dialog({
                title: judul
            });
            $("#dialog-modal").dialog('open');
            document.getElementById("nomor").disabled = false;
        }


        function tambah() {
            $('input[name="status_setor"]').removeAttr('disabled', true);
            $("#notetap").combogrid("setValue", '');
            get_nourut();

            lcstatus = 'tambah';
            judul = 'Input Data Penerimaan';
            $("#dialog-modal").dialog({
                title: judul
            });
            $("#dialog-modal").dialog('open');

            document.getElementById("nomor").disabled = false;
            document.getElementById("nomor").focus();
            kosong();

        }

        function get_nourut() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/no_urut',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#nomor_kas").attr("value", data.no_urut);
                }
            });
        }


        function keluar() {
            $("#dialog-modal").dialog('close');
            kosong();
            $("#save").html('Simpan');
        }

        function hapus() {
            if ($("#kunci").val() == '1') {
                swal("Error", "Penerimaan sudah ada penyetoran, hapus penyetoran terlebih dahulu", "warning");
                return;
            }
            var rows = $("#dg").edatagrid("getSelected");
            var nobkt = rows.no_terima;

            var tanya = confirm('Apakah Data Nomor Terima ' + nobkt + ' Akan Di Hapus ?');

            if (tanya == true) {
                var urll = '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/delete_data';
                $(document).ready(function() {
                    $.post(urll, {
                        no: nomor,
                        skpd: kode
                    }, function(data) {
                        status = data;
                        if (status == '0') {
                            alert('Gagal Hapus..!!');
                            return;
                        } else {
                            $('#dg').datagrid('deleteRow', lcidx);
                            alert('Data Berhasil Dihapus..!!');
                            kosong();
                            $("#save").html('Simpan');
                            return;
                        }
                    });
                });
            }
        }

        function runEffect() {
            $('#notetap').combogrid({
                panelWidth: 420,
                idField: 'no_tetap',
                textField: 'no_tetap',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/bok/PenerimaanBOKController/load_no_tetap',
                queryParams: ({
                    kd: kode
                }),
                columns: [
                    [{
                            field: 'no_tetap',
                            title: 'No Penetapan',
                            width: 140
                        },
                        {
                            field: 'tgl_tetap',
                            title: 'Tanggal',
                            width: 140
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            width: 140
                        }
                    ]
                ]
            });
            var selectedEffect = 'blind';
            var options = {};
            $("#tagih").toggle(selectedEffect, options, 500);
            $("#notetap").combogrid("setValue", '');
            $("#tgltetap").attr("value", '');
            //$("#nilai").attr("value",'');
            $("#skpd").attr("setValue", '');
            $("#rek").combogrid("setValue", '');
            $("#nil_tetap").attr("value", '');

        };

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }

        function delCommas(nStr) {
            nStr += ' ';
            x2 = nStr.length;
            var x = nStr;
            var i = 0;
            while (i < x2) {
                x = x.replace(',', '');
                i++;
            }
            return x;
        }

        // function opt(val) {
        //     ctk = val;
        //     if (ctk == 'Dengan Setor') {
        //         $("#pem1").show();
        //         $("#pem2").hide();
        //     } else if (ctk == 'Tanpa Setor') {
        //         $("#pem1").hide();
        //         $("#pem2").show();
        //     } else {
        //         exit();
        //     }
        // }

        function _jenis1() {
            var jns = $('#jns').attr('value');
            var nomor = $('#nomor').attr('value');
            $("#nomor").attr("value", jns);

        }
    </script>

</head>

<body>

    <div id="content">
        <div id="accordion">
            <h3 align="center"><u><b><a href="#" id="section1">INPUTAN PENERIMAAN</a></b></u></h3>
            <div>
                <p align="right">
                    <button type="submit" class="easyui-linkbutton" plain="true" onclick="javascript:tambah();kosong();"><i class="fa fa-plus"></i> Tambah</button>
                    <button type="delete" id="del" class="easyui-linkbutton" plain="true" onclick="javascript:hapus();"><i class="fa fa-trash"></i> Hapus</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="text" value="" id="txtcari" onkeyup="javascript:cari();" />
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
                <table id="dg" title="Listing data Penerimaan " style="width:870px;height:450px;">
                </table>

                </p>
            </div>
        </div>
    </div>

    <div id="dialog-modal" title="">
        <p class="validateTips">Semua Inputan Harus Di Isi.</p>
        <fieldset>
            <table align="center" style="width:100%;" border="0">
                <tr>
                    <td colspan="3">
                        <p id="pesan" style="font-size: large;"></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="5"><b>Dengan Penetapan</b><input type="checkbox" id="status" onclick="javascript:runEffect();" />
                        <div id="tagih">
                            <table>
                                <tr>
                                    <td>No Penetapan&nbsp;</td>
                                    <td><input type="text" id="notetap" style="width: 200px;" /></td>
                                    <td>&nbsp;Tgl&nbsp;&nbsp;</td>
                                    <td><input type="text" id="tgltetap" style="width: 100px;" disabled /></td>
                                    <td>&nbsp;Nilai&nbsp;&nbsp;</td>
                                    <td><input type="text" id="nil_tetap" style="width: 100px; text-align: right;" disabled /></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>No. Kas</td>
                    <td></td>
                    <td><input type="text" id="nomor_kas" style="width: 200px;" /><input type="hidden" id="nomor_kas_hide" style="width: 200px;" />
                    </td>
                </tr>
                <tr>
                    <td>No. Terima</td>
                    <td></td>
                    <td><input type="text" id="nomor" style="width: 200px;" /><input type="hidden" id="nomor_hide" style="width: 200px;" />
                    </td>
                </tr>
                <tr>
                    <td>Tanggal </td>
                    <td></td>
                    <td><input type="text" id="tanggal" style="width: 210px;" /></td>
                </tr>
                <tr>
                    <td>S K P D</td>
                    <td></td>
                    <td><input id="skpd" name="skpd" style="width: 200px;" /> <input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true" /></td>
                </tr>
                <tr>
                    <td>Rekening</td>
                    <td></td>
                    <td><input id="rek" name="rek" style="width: 208px;" readonly="true" />
                        <input type="text" id="nmrek" style="border:0;width: 300px;" readonly="true" />
                        <input type="hidden" id="kd_rek_lo" style="border:0;width: 600px;" readonly="true" />
                    </td>
                </tr>

                <tr style="display: none">
                    <td>Pengirim</td>
                    <td></td>
                    <td><input id="pengirim" name="pengirim" style="width: 140px;" value="-" />
                        <input type="text" id="nmpengirim" style="border:0;width: 600px;" readonly="true" value="-" />
                </tr>
                <tr>
                    <td>Kegiatan</td>
                    <td></td>
                    <td><input type="text" id="giat" style="width: 200px;" readonly="true" />
                    </td>
                </tr>


                <tr hidden>
                    <td>Kunci</td>
                    <td></td>
                    <td><input type="text" id="kunci" style="width: 140px;" readonly="true" />
                    </td>
                </tr>
                <tr>
                    <td>Nilai</td>
                    <td></td>
                    <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        <button type="primary" class="easyui-linkbutton" id="save" onclick="javascript:simpan_terima();"><i class="fa fa-save"></i> Simpan</button>
                        <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Kembali</button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</body>

</html>