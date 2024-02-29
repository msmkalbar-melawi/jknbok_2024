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

    <style>

    </style>
    <script type="text/javascript">
        var no_kas = '';
        var kd_skpd = '';

        $(document).ready(function() {

            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 720,
                width: 1000,
                modal: true,
                autoOpen: false
            });

            $("#dialog-modal-rekening").dialog({
                height: 260,
                width: 900,
                modal: true,
                autoOpen: false
            });
            // get_skpd();
            get_tahun();
        });
        $(document).ready(function() {
            // tanggal
            $('#tanggal').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {}
            });
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/loaddata',
                idField: 'id',
                rownumbers: "true",
                // fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                loadMsg: "Tunggu Sebentar....!!",
                pagination: "true",
                nowrap: "true",
                columns: [
                    [{
                            field: 'no_kas',
                            title: 'Nomor',
                            width: 100,
                            align: "center"
                        },
                        {
                            field: 'tgl_kas',
                            title: 'Tanggal',
                            width: 100,
                            align: "center"
                        },
                        {
                            field: 'kd_skpd',
                            title: 'Skpd',
                            width: 170,
                            align: "center"
                        },
                        {
                            field: 'keterangan',
                            title: 'Keterangan',
                            width: 300,
                            align: "left"
                        },

                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 107,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    no_kas = rowData.no_kas;
                    tgl_kas = rowData.tgl_kas;
                    no_bukti = rowData.no_bukti;
                    tgl_bukti = rowData.tgl_bukti;
                    kd_skpd = rowData.kd_skpd;
                    keterangan = rowData.keterangan;
                    jenis = rowData.jenis;
                    nilai = rowData.nilai;
                    nm_skpd = rowData.nm_skpd;
                    no_transaksi = rowData.no_transaksi;
                    get(no_kas, tgl_kas, no_bukti, tgl_bukti, kd_skpd, keterangan, jenis, nilai, nm_skpd, no_transaksi);
                    edit_data(no_bukti, kd_skpd, jenis, no_transaksi);
                }
            });
        });


        function edit_data(no_bukti, kd_skpd, jenis, no_transaksi) {
            $("#save").html('Update');
            // document.getElementById("no_trans").readOnly = true;
            lcstatus = 'edit';
            judul = 'Edit Data Droping Dana';

            $(document).ready(function() {
                $('#section2').click();
                // document.getElementById("nomor").focus();
            });
            $(document).ready(function() {

                $('#dg1').edatagrid({
                    columns: [
                        [{
                                field: 'no_bukti',
                                title: 'No Bukti',
                                hidden: "true"
                            },
                            {
                                field: 'kd_sub_kegiatan',
                                title: 'Sub Kegiatan',
                                width: 100
                            },
                            {
                                field: 'nm_sub_kegiatan',
                                title: 'Nama Kegiatan',
                                width: 200
                            },
                            {
                                field: 'kd_rek6',
                                title: 'Kode Rekening',
                                width: 100
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 200,
                                align: "left"
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                width: 150,
                                align: "left"
                            },
                            {
                                field: 'aksi',
                                title: 'Aksi',
                                width: 100,
                                align: "center",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                                }
                            },
                        ]
                    ]
                });

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/loadingdata',
                    data: ({
                        no_bukti: no_bukti,
                        jenis: jenis,
                        kd_skpd: kd_skpd,
                        no_transaksi: no_transaksi
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            giat = n['kd_sub_kegiatan'];
                            nm_sub_kegiatan = n['nm_sub_kegiatan'];
                            rek5 = n['kd_rek6'];
                            nmrek5 = n['nm_rek6'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                kd_sub_kegiatan: giat,
                                nm_sub_kegiatan: nm_sub_kegiatan,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,

                            });

                        });
                    }
                });
            });

        }

        function load_sisa_bank() {
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/cms/load_sisa_bank",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#sisa_bank").attr("value", n['sisa']);
                        });
                    }
                });
            });
        }

        function load_total_spd() {
            var giat = $('#giat').combogrid('getValue');
            var koderek = $('#rek').combogrid('getValue');
            var kode = document.getElementById('skpd').value;
            var tgl_cek = $('#tanggal').datebox('getValue');
            var status = document.getElementById('status_angkas').value;
            // alert(status);
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/cms/load_total_spd",
                    dataType: "json",
                    data: ({
                        giat: giat,
                        kode: kode,
                        kdrek6: koderek,
                        tgl: tgl_cek
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#tot_spd").attr("value", n['total_spd']);
                            // load_total_trans();
                        });
                    }
                });
            });


            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/cms/load_total_angkas",
                    dataType: "json",
                    data: ({
                        giat: giat,
                        kode: kode,
                        koderek: koderek,
                        tgl: tgl_cek,
                        stt: status
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#tot_angkas").attr("value", n['total_angkas']);
                            // load_total_trans();
                        });
                    }
                });
            });


        }


        function numberFormat(n) {
            let nilai = number_format(n, 2, '.', ',');
            return nilai;
        }


        function get_skpd() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    $("#kdbidang").attr("value", "<?php echo $this->session->userdata('pcNama'); ?>");
                    $("#nmbidang").attr("value", data.nm_bidang);
                    kode = data.kd_skpd;
                    //kegia();              
                }
            });
        }

        function get_tahun() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/tukd/config_tahun',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    tahun_anggaran = data;
                }
            });

        }

        function hapus_detail() {
            var rows = $('#dg1').edatagrid('getSelected');
            cgiat = rows.kd_sub_kegiatan;
            crek = rows.kd_rek6;
            cnil = rows.nilai;
            var idx = $('#dg1').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, Sub Kegiatan : ' + cgiat + ' Rekening : ' + crek + ' Nilai : ' + cnil);
            if (tny == true) {
                $('#dg1').edatagrid('deleteRow', idx);
                total = angka(document.getElementById('total').value) - angka(cnil);
                $('#total1').attr('value', number_format(total, 2, '.', ','));
                $('#total').attr('value', number_format(total, 2, '.', ','));
                kosong2();
            }

        }


        function section1() {
            $(document).ready(function() {
                $('#section1').click();
                $('#dg').edatagrid('unselectAll');
            });
        }


        function get(no_kas, tgl_kas, no_bukti, tgl_bukti, kd_skpd, keterangan, jenis, nilai, nm_skpd, no_transaksi) {
            $('#total1').attr('value', number_format(nilai, 2, '.', ','));
            $('#total').attr("value", number_format(nilai, 2, '.', ','));
            $("#nomor").attr("value", no_bukti);
            $("#tanggal").datebox("setValue", tgl_bukti);
            $("#beban").attr("value", jenis);
            $("#skpd").attr("value", kd_skpd);
            $("#nmskpd").attr("value", nm_skpd);
            $("#keterangan").attr("value", keterangan);
            $("#no_trans").attr("value", no_transaksi);
            $("#no_trans_hidden").attr("value", no_transaksi);
            status_transaksi = 'edit';

        }

        function kosong() {
            // get_nourut();
            cdate = '<?php echo date("Y-m-d"); ?>';
            $("#nomor").attr("value", '');
            $("#no_simpan").attr("value", '');
            $("#tanggal").datebox("setValue", '');
            $("#keterangan").attr("value", '');
            $("#beban").attr("value", '');
            $("#total").attr("value", '0');
            status_transaksi = 'tambah';
            document.getElementById("nomor").focus();
            tombolnew();
        }

        function get_nourut() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/no_urut',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#nomor").attr("value", data.no_urut);
                }
            });
        }

        function cari() {

            // reload_data();
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/loaddata',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }


        function append_save() {
            var no = document.getElementById('nomor').value;
            var giat = $('#giat').combogrid('getValue');
            var nmgiat = document.getElementById('nmgiat').value;
            var rek = $('#rek').combogrid('getValue');
            var nmrek = document.getElementById('nmrek').value;
            var nil = document.getElementById('nilai').value;
            var nilai = angka(document.getElementById('nilai').value);

            if (giat == '') {
                alert('Pilih Kegiatan Dahulu');
                exit();
            }
            if (rek == '') {
                alert('Pilih Rekening Belanja Dahulu');
                exit();
            }

            if (nil == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                exit();
            }

            $('#dg1').edatagrid('appendRow', {
                no_bukti: no,
                kd_sub_kegiatan: giat,
                nm_sub_kegiatan: nmgiat,
                kd_rek6: rek,
                nm_rek6: nmrek,
                nilai: nil
            });
            $('#dg2').edatagrid('appendRow', {
                no_bukti: no,
                kd_sub_kegiatan: giat,
                nm_sub_kegiatan: nmgiat,
                kd_rek6: rek,
                nm_rek6: nmrek,
                nilai: nil
            });
            total = angka(document.getElementById('total1').value) + angka(nil);
            $('#total1').attr('value', number_format(total, 2, '.', ','));
            $('#total').attr('value', number_format(total, 2, '.', ','));
        }




        function kosong3() {
            $('#ang').attr('value', '0');
            $('#sisa').attr('value', '0');
            $('#tot_sisa').attr('value', '0');
            $('#ang_sd').attr('value', '0');
            $('#sisa_sd').attr('value', '0');
        }

        function kosong2() {
            $('#giat').combogrid('setValue', '');
            $('#rek').combogrid('setValue', '');
            $('#sumber_dn').combogrid('setValue', '');
            $('#sisasp2d').attr('value', '0');
            $('#sisa').attr('value', '0');
            $('#nilai').attr('value', '0');
            $('#nmgiat').attr('value', '');
            $('#nmrek').attr('value', '');
            $('#ang').attr('value', '0');
            $('#lalu').attr('value', '0');
            $('#ang_sd').attr('value', '0');
            $('#lalu_sd').attr('value', '0');
            $('#sisa_sd').attr('value', '0');
            $("#pot_ls").attr("value", '');
            $("#total_sisa").attr("value", '');
            $('#tot_spd').attr('value', '0');
            $('#tot_trans').attr('value', '0');
            $('#tot_sisa').attr('value', '0');
            $('#tot_angkas').attr('value', '0');
            $('#tot_trans_angkas').attr('value', '0');
            $('#tot_sisa_angkas').attr('value', '0');
        }


        function hapus() {
            var cnomor = document.getElementById('nomor').value;
            var skpd = $('#skpd').val();
            var no_trans = $('#no_trans').val();
            var cjenis = $('#beban').val();
            var urll = '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/hapus_data';
            var tny = confirm('Yakin Ingin Menghapus Data, Nomor Bukti : ' + cnomor);
            if (tny == true) {
                $(document).ready(function() {
                    $.ajax({
                        url: urll,
                        dataType: 'json',
                        type: "POST",
                        data: ({
                            no: cnomor,
                            skpd: skpd,
                            no_trans: no_trans,
                            cjenis: cjenis
                        }),
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                $('#dg').edatagrid('reload');
                                alert('Data Berhasil Terhapus');
                                $('#section1').click();
                            } else if (status == '1') {
                                alert('Gagal Hapus...!!');
                            }
                        }

                    });
                });
            }
        }


        function simpan_transout() {
            var cno = document.getElementById('nomor').value;
            var no_trans = $('#no_trans').val();
            var no_trans_hidden = $('#no_trans_hidden').val();
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            var cket = document.getElementById('keterangan').value;
            var cjenis = document.getElementById('beban').value;
            var tahun_input = ctgl.substring(0, 4);
            if (cno == '') {
                alert('no tidak boleh kosong');
                return;
            }
            if (ctgl == '') {
                alert('Tanggal tidak boleh kosong');
                return;
            }
            if (cjenis == '') {
                alert('Beban tidak boleh kosong');
                return;
            }
            if (cket == '') {
                alert('Keterangan tidak boleh kosong');
                return;
            }
            if (no_trans == '') {
                alert('Nomor Transaksi tidak boleh kosong');
                return;
            }
            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }
            $('#dg1').edatagrid('selectAll');
            var rows = $('#dg1').edatagrid('getSelections');
            if (rows.length == 0) {
                alert('Tidak ada Rincian rekening belanja');
                return;
            }
            if (status_transaksi == 'tambah') {
                //    Looping
                $('#dg1').datagrid('selectAll');
                var lcinsert;
                var lcvalues;
                var rows = $('#dg1').datagrid('getSelections');
                for (var p = 0; p < rows.length; p++) {
                    cnobukti = rows[p].no_bukti;
                    ckdgiat = rows[p].kd_sub_kegiatan;
                    cnmgiat = rows[p].nm_sub_kegiatan;
                    crek = rows[p].kd_rek6;
                    cnmrek = rows[p].nm_rek6;
                    cnilai = angka(rows[p].nilai);
                    if (p > 0) {
                        lcinsert = "(no_bukti, kd_sub_kegiatan, nm_sub_kegiatan,kd_rek6, nm_rek6, nilai, kd_skpd, jenis)";
                        lcvalues = lcvalues + "," + "('" + cnobukti + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                            "','" + cskpd + "','" + cjenis + "')";
                    } else {
                        lcinsert = "(no_bukti, kd_sub_kegiatan, nm_sub_kegiatan,kd_rek6, nm_rek6, nilai, kd_skpd,jenis )";
                        lcvalues = "('" + cnobukti + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                            "','" + cskpd + "','" + cjenis + "')";
                    }
                }


                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/simpan_data',
                        data: ({
                            tabel: 'bok_trdrka',
                            kolom: lcinsert,
                            nilai: lcvalues,
                            cno: cno,
                            cjenis: cjenis,
                            cket: cket,
                            ctgl: ctgl,
                            cskpd: cskpd,
                            total: angka($('#total').val()),
                            no_trans: no_trans

                        }),
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                exit();
                            } else if (status == '1') {
                                alert('Data Tersimpan..!!');
                                $('#dg').edatagrid('reload');
                                $('#section1').click();
                            }
                        }
                    });
                });
            } else {
                // Edit
                //    Looping
                $('#dg1').datagrid('selectAll');
                var lcinsert;
                var lcvalues;
                var rows = $('#dg1').datagrid('getSelections');
                for (var p = 0; p < rows.length; p++) {
                    cnobukti = rows[p].no_bukti;
                    ckdgiat = rows[p].kd_sub_kegiatan;
                    cnmgiat = rows[p].nm_sub_kegiatan;
                    crek = rows[p].kd_rek6;
                    cnmrek = rows[p].nm_rek6;
                    cnilai = angka(rows[p].nilai);
                    if (p > 0) {
                        lcinsert = "(no_bukti, kd_sub_kegiatan, nm_sub_kegiatan,kd_rek6, nm_rek6, nilai, kd_skpd, jenis)";
                        lcvalues = lcvalues + "," + "('" + cnobukti + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                            "','" + cskpd + "','" + cjenis + "')";
                    } else {
                        lcinsert = "(no_bukti, kd_sub_kegiatan, nm_sub_kegiatan,kd_rek6, nm_rek6, nilai, kd_skpd, jenis)";
                        lcvalues = "('" + cnobukti + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                            "','" + cskpd + "','" + cjenis + "')";
                    }
                }


                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/update_data',
                        data: ({
                            tabel: 'bok_trdrka',
                            kolom: lcinsert,
                            nilai: lcvalues,
                            cno: cno,
                            cjenis: cjenis,
                            cket: cket,
                            ctgl: ctgl,
                            cskpd: cskpd,
                            total: angka($('#total').val()),
                            no_trans: no_trans,
                            no_trans_hidden: no_trans_hidden
                        }),
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Update Data..!!No transaksi sudah ada di pembukuan');
                                return;
                            } else if (status == '1') {
                                alert('Data berhasil diupdate..!!');
                                $('#dg').edatagrid('unselectAll');
                                $('#dg').edatagrid('reload');
                                $('#section1').click();
                                kosong();
                                // get_nourut();
                            }
                        }
                    });
                });
            }
        }





        function datagrid_kosong() {
            $('#dg1').edatagrid('selectAll');
            var rows = $('#dg1').edatagrid('getSelections');
            for (var i = rows.length - 1; i >= 0; i--) {
                var index = $('#dg1').edatagrid('getRowIndex', rows.id);
                $('#dg1').edatagrid('deleteRow', index);
                //alert("aa");
            }
        }



        function cek_angka(a) {
            if (!/^[0-9.]+$/.test(a.value)) {
                a.value = a.value.substring(0, a.value.length - 1000);
            }
        }

        function cek_huruf(b) {
            b.value = b.value.toUpperCase();
        }

        // Hakam
        $(document).ready(function() {
            $("#tambahtransaksi").click(function() {
                $("#save").html('Simpan');
                get_nourut();
                $("#tanggal").datebox("setValue", '');
                $("#keterangan").val('');
                $("#beban").val('');
                $("#no_trans").val('');
                status_transaksi = 'tambah';
                $('#total').attr('value', 0);
                $('#total1').attr('value', 0);
                // get_nourut();
                get_skpd();
                $('#section2').click();
                //datagrid
                $('#dg1').edatagrid({
                    columns: [
                        [{
                                field: 'no_bukti',
                                title: 'No Bukti',
                                hidden: "true"
                            },
                            {
                                field: 'kd_sub_kegiatan',
                                title: 'Sub Kegiatan',
                                width: 100
                            },
                            {
                                field: 'nm_sub_kegiatan',
                                title: 'Nama Kegiatan',
                                width: 200
                            },
                            {
                                field: 'kd_rek6',
                                title: 'Kode Rekening',
                                width: 100
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 200,
                                align: "left"
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                width: 150,
                                align: "left"
                            },
                            {
                                field: 'aksi',
                                title: 'Aksi',
                                width: 100,
                                align: "center",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                                }
                            },
                        ]
                    ]
                });
            });

            $("#tambahkegiatan").click(function() {
                var keterangan = $('#keterangan').val();
                var tanggal = $('#tanggal').datebox('getValue');
                var skpd = $('#skpd').val();
                var beban = $('#beban').val();
                if (keterangan == '' || tanggal == '' || skpd == '' || beban == '') {
                    alert('Isi data terlebih dahulu');
                    return;
                } else {
                    $("#dialog-modal").dialog('open');
                }
                // datagrid
                $('#dg2').edatagrid({
                    columns: [
                        [{
                                field: 'no_bukti',
                                title: 'No Bukti',
                                hidden: "true",
                            },
                            {
                                field: 'kd_sub_kegiatan',
                                title: 'Sub Kegiatan',
                                width: 150
                            },
                            {
                                field: 'nm_sub_kegiatan',
                                title: 'Nama Kegiatan',
                                width: 200
                            },
                            {
                                field: 'kd_rek6',
                                title: 'Kode Rekening',
                                width: 150,
                                align: 'center'
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                align: "left",
                                width: 250
                            },
                            {
                                field: 'nilai',
                                title: 'Rupiah',
                                align: "left",
                                width: 190
                            }
                        ]
                    ]
                });

                // Sub Kegiatan
                $('#giat').combogrid({
                    panelWidth: 700,
                    idField: 'kd_sub_kegiatan',
                    textField: 'kd_sub_kegiatan',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/load_subkegiatan',
                    queryParams: ({
                        kd: '1.02.0.00.0.00.01.0000'
                    }),
                    columns: [
                        [{
                                field: 'kd_sub_kegiatan',
                                title: 'Kode Kegiatan',
                                width: 140
                            },
                            {
                                field: 'nm_sub_kegiatan',
                                title: 'Nama Kegiatan',
                                width: 700
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        nm_sub_kegiatan = rowData.nm_sub_kegiatan;
                        kd_sub_kegiatan = rowData.kd_sub_kegiatan;
                        $('#nmgiat').attr('value', nm_sub_kegiatan);
                        // Sub Rekening
                        $('#rek').combogrid({
                            url: '<?php echo base_url(); ?>index.php/bok/DropinganggaranController/rekening_belanja',
                            queryParams: ({
                                kd_sub_kegiatan: kd_sub_kegiatan,
                                kd: '1.02.0.00.0.00.01.0000'
                            })
                        });
                    }
                });

                $('#rek').combogrid({
                    panelWidth: 350,
                    idField: 'kd_rek6',
                    textField: 'kd_rek6',
                    mode: 'remote',
                    columns: [
                        [{
                                field: 'kd_rek6',
                                title: 'Kode Rekening',
                                width: 100,
                                align: 'center'
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 400
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        nm_rek6 = rowData.nm_rek6;
                        $('#nmrek').attr('value', nm_rek6);
                    }
                });
            });

            $("#keluarmodal").click(function() {
                $("#dialog-modal").dialog('close');
            });
        });
    </script>

</head>

<body>



    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1">List Droping Anggaran BOK</a></h3>
            <div>
                <p align="right">
                    <input type="text" value="" id="txtcari" />
                    <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                    <button class="button" style="display: inline" id="tambahtransaksi"><i class="fa fa-tambah"></i> Tambah</button>
                <table id="dg" title="List Transaksi Droping Anggaran" style="width:870px;height:590px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2">Transaksi Droping Anggaran</a></h3>
            <div style="height: 350px;">
                <p>
                <div id="demo"></div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td>Nomor </td>
                        <td><input type="text" class="input" id="nomor" style="width: 200px;" />
                        </td>
                        <td>Tanggal </td>
                        <td><input type="text" id="tanggal" style="width: 200px;" /></td>
                    </tr>
                    <tr>
                        <td>S K P D</td>
                        <td><input id="skpd" class="input" name="skpd" style="width: 200px;" /><input type="hidden" id="nmbidang" style="border:0;width: 400px;  " readonly="true" /><input type="hidden" id="kdbidang" class="input" name="kdbidang" style="width: 200px;" /></td>
                        <td>Nama :</td>
                        <td><input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true" /></td>
                    </tr>

                    <tr>
                        <td>No Transaksi</td>
                        <td><input type="text" class="input" id="no_trans" style="width: 200px;" />
                            <input type="hidden" class="input" id="no_trans_hidden" style="width: 200px;" />
                        </td>
                        <td>Jenis Beban</td>
                        <td><select name="beban" id="beban" style="width: 220px;">
                                <option value="">--- Pilih ---</option>
                                <!-- <option value="1">Kapitasi</option>
                                <option value="2">Non Kapitasi (APBD)</option> -->
                                <option value="3">Bantuan Operasional Kesehatan (BOK)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="4"><textarea id="keterangan" class="textarea" style="width: 650px; height: 40px;"></textarea></td>
                    </tr>
                    <td colspan="3" align="right">
                        <button id="save" class="button-biru" onclick="javascript:simpan_transout();"><i class="fa fa-save"></i> Simpan</button>
                        <button id="del" class="button-merah" onclick="javascript:hapus();"><i class="fa fa-hapus"></i> Hapus</button>
                        <button class="button-abu" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>

                    </td>
                </table>
                <div id="toolbar" align="right">
                    <button id="tambahkegiatan" style="display: inline" class="button"><i class="fa fa-tambah"></i> Tambah Kegiatan</button>
                </div>
                <table id="dg1" title="Rekening Anggaran BOK" style="width:870px;height:200px;">
                </table>
                <table align="center" style="width:100%;" border="0">
                    <tr>
                        <td width="60%">&nbsp;</td>
                        <td align="right">Total &nbsp;</td>
                        <td align="right" width="27%">:&nbsp;<input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                    </tr>
                </table>
                </p>
            </div>
            <h3><a href="#" id="section3"></a></h3>
        </div>
    </div>

    <div id="dialog-modal" title="Input Kegiatan *)Semua Inputan Harus Di Isi.">
        <!--<p class="validateTips">Semua Inputan Harus Di Isi.</p>-->
        <fieldset>
            <table>

                <tr>
                    <td>Kode Kegiatan</td>
                    <td>:</td>
                    <td><input id="giat" name="giat" style="width: 200px;" /></td>
                    <td>Nama Kegiatan</td>
                    <td>:</td>
                    <td colspan="4"><input type="text" id="nmgiat" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr>
                    <td>Kode Rekening</td>
                    <td>:</td>
                    <td><input id="rek" name="rek" style="width: 200px;" /></td>
                    <td>Nama Rekening</td>
                    <td>:</td>
                    <td colspan="4"><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>

                <!-- <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td colspan="7"><input type="text" id="status_ang" readonly="true" style="text-align:left;border:0;width: 150px;" /></td>

                </tr>
                <tr>
                    <td>Status Angkas</td>
                    <td>:</td>
                    <td colspan="7"><input type="text" id="status_angkas" readonly="true" style="text-align:left;border:0;width: 150px;" /></td>

                </tr> -->
                <!-- <tr>
                    <td>Sisa Nilai SP2D</td>
                    <td>:</td>
                    <td colspan="7"><input type="text" id="sisa_uang_sp2d" style="text-align: right; width: 150px;" /></td>
                </tr> -->
                <tr>
                    <td>Nilai</td>
                    <td>:</td>
                    <td colspan="7"><input type="text" id="nilai" style="text-align: right; width: 190px;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" id="keluarmodal">Keluar</a>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table id="dg2" align="center" title="Input Rekening" style="width:940px;height:180px;">
            </table>
            <table align="right">
                <tr>
                    <td>Total</td>
                    <td>:</td>
                    <td><input type="text" id="total1" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;" /></td>
                </tr>
            </table>

        </fieldset>
    </div>
</body>

</html>