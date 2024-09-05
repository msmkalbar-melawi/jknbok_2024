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


    <script type="text/javascript">
        var no_lpj = '';
        var kode = '';
        var spd = '';
        var st_12 = 'edit';
        var nidx = 100
        var spd2 = '';
        var spd3 = '';
        var spd4 = '';
        var lcstatus = '';
        var status_lpj = 0;

        $(document).ready(function() {

            $("#accordion").accordion();
            $("#lockscreen").hide();
            $("#frm").hide();
            $("#dialog-modal").dialog({
                height: 450,
                width: 700,
                modal: true,
                autoOpen: false
            });
            $("#dialog-modal-tr").dialog({
                height: 270,
                width: 500,
                modal: true,
                autoOpen: false
            });
            $("#div1").hide();

            $("#loading").dialog({
                resizable: false,
                width: 200,
                height: 130,
                modal: true,
                draggable: false,
                autoOpen: false,
                closeOnEscape: false
            });

        });


        $(function() {
            $('#dd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#ddd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#dd1').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });


            $('#dd2').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });


            $('#ttd2').combogrid({
                panelWidth: 200,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/bok/SP3BController/load_ttd/JKNBOK-PA',
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
                }
            });






            $('#spp').edatagrid({
                url: '<?php echo base_url(); ?>index.php/bok/SP3BController/load_data',
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
                            field: 'no_lpj',
                            title: 'NO LPJ',
                            width: 60
                        },
                        {
                            field: 'tgl_lpj',
                            title: 'Tanggal',
                            width: 40
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 170,
                            align: "left"
                        },
                        {
                            field: 'ket',
                            title: 'Keterangan',
                            width: 110,
                            align: "left"
                        }
                    ]
                ],
                rowStyler: function(rowIndex, rowData) {
                    if (rowData.status == '1') {
                        return 'background-color:#4bbe68;color:white';
                    }
                },
                onSelect: function(rowIndex, rowData) {
                    nomer = rowData.no_lpj;
                    kode = rowData.kd_skpd;
                    nmskpd = rowData.nm_skpd;
                    tgllpj = rowData.tgl_lpj;
                    cket = rowData.ket;
                    status_lpj = rowData.status;
                    tgl_awal = rowData.tgl_awal;
                    tgl_akhir = rowData.tgl_akhir;
                    no_sp3b = rowData.no_sp3b;


                    get(nomer, kode, tgllpj, cket, status_lpj, tgl_awal, tgl_akhir, nmskpd, no_sp3b);
                    lcstatus = 'edit';
                },
                onDblClickRow: function(rowIndex, rowData) {}
            });
        });





        function keluar() {
            $("#dialog-modal").dialog('close');
        }

        function get(nomer, kode, tgllpj, cket, status_lpj, tgl_awal, tgl_akhir, nmskpd, no_sp3b) {

            $("#save").html('Update');
            $("#no_sp3b").attr("value", no_sp3b);
            $("#no_lpj").attr("value", nomer);
            $("#cspp").attr("value", nomer);
            // $('#no_lpj').prop('readonly', true);
            $("#no_simpan").attr("value", nomer);
            $("#skpd").attr("Value", kode);
            $("#nmskpd").attr("Value", nmskpd);
            $("#dd").datebox("setValue", tgllpj);
            $("#dd1").datebox("setValue", tgl_awal);
            $("#dd2").datebox("setValue", tgl_akhir);
            $("#keterangan").attr("Value", cket);
            var skpd = $('#skpd').val();
            var tgllpj = $('#dd').datebox('getValue');
            var nolpj = $('#no_lpj').val();
            var no_simpan = $('#no_simpan').val();
            var keterangan = $('#keterangan').val();
            var total = rupiah($('#rektotal').val());
            var tgl1 = $('#dd1').datebox('getValue');
            var tgl2 = $('#dd2').datebox('getValue');

            $('#section1').click();

            $(document).ready(function() {
                // $.ajax({
                //     type: "POST",
                //     url: '<?php echo base_url(); ?>index.php/bok/SP3BController/cek_data',
                //     data: ({
                //         nomer: nolpj,
                //         kode: kode
                //     }),
                //     dataType: "json",
                //     success: function(data) {
                //         if (data == '1') {
                //             $("#save").attr('disabled', true);
                //             $("#delete").prop('disabled', true);
                //         } else {
                //             $("#save").prop('disabled', false);
                //             $("#delete").prop('disabled', false);
                //         }
                //     }
                // });

                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/bok/SP3BController/select_lpj',
                    queryParams: ({
                        lpj: nomer
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "false",
                    singleSelect: "true",
                    nowrap: "false",
                    columns: [
                        [{
                                field: 'idx',
                                title: 'idx',
                                width: 100,
                                align: 'left',
                                hidden: true
                            },
                            {
                                field: 'kd_skpd',
                                title: 'Puskesmas',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'no_bukti',
                                title: 'No Bukti',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'kd_sub_kegiatan',
                                title: 'Kode Kegiatan',
                                width: 150,
                                align: 'left'
                            },
                            {
                                field: 'kd_rek6',
                                title: 'Rekening',
                                width: 70,
                                align: 'left'
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 280
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                width: 140,
                                align: 'left'
                            },
                            {
                                field: 'Aksi',
                                title: 'Aksi',
                                width: 50,
                                align: "left",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                                }
                            }
                        ]
                    ]
                });

                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/bok/SP3BController/select_lpj",
                    dataType: "json",
                    data: {
                        lpj: nomer
                    },
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $('#rektotal').attr('value', new Intl.NumberFormat('id-ID').format(n['total']));
                        });
                    }
                });

                $('#save').click(function() {
                    var skpd = $('#skpd').val();
                    var tgllpj = $('#dd').datebox('getValue');
                    var nolpj = $('#no_lpj').val();
                    var no_sp3b = $('#no_sp3b').val();
                    var no_simpan = $('#no_simpan').val();
                    var keterangan = $('#keterangan').val();
                    var total = rupiah($('#rektotal').val());
                    var tgl1 = $('#dd1').datebox('getValue');
                    var tgl2 = $('#dd2').datebox('getValue');
                    if (nolpj == '') {
                        swal("Error", "Nomor Tidak Boleh Kosong!", "error");
                        return;
                    }
                    if (tgllpj == '') {
                        swal("Error", "Tanggal Tidak Boleh Kosong!", "error");
                        return;
                    }
                    if (keterangan == '') {
                        swal("Error", "Nomor Tidak Boleh Kosong!", "error");
                        return;
                    }
                    $('#dg1').datagrid('selectAll');
                    var rows = $('#dg1').datagrid('getSelections');
                    if (rows.length < 0) {
                        alert('Tidak ada transaksi, silahkan cek kembali');
                        return;
                    }
                    for (var p = 0; p < rows.length; p++) {
                        cidx = rows[p].idx;
                        cno_lpj = nolpj;
                        ckd_skpd = rows[p].kd_skpd;
                        cno_bukti = rows[p].no_bukti;
                        ckdgiat = rows[p].kd_sub_kegiatan;
                        crek = rows[p].kd_rek6;
                        cnmrek = rows[p].nm_rek6;
                        cnilai = rows[p].nilai;
                        if (p > 0) {
                            lcinsert = "(no_lpj, no_bukti, kd_sub_kegiatan, kd_rek6, nm_rek6, nilai, kd_skpd)";
                            lcvalues = lcvalues + "," + "('" + cno_lpj + "','" + cno_bukti + "','" + ckdgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                                "','" + ckd_skpd + "')";
                        } else {
                            lcinsert = "(no_lpj, no_bukti, kd_sub_kegiatan, kd_rek6, nm_rek6, nilai, kd_skpd)";
                            lcvalues = "values('" + cno_lpj + "','" + cno_bukti + "','" + ckdgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                                "','" + ckd_skpd + "')";
                        }
                    }

                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/bok/SP3BController/update_data',
                        data: ({
                            kolom: lcinsert,
                            nilai: lcvalues,
                            skpd: skpd,
                            tgllpj: tgllpj,
                            nolpj: nolpj,
                            keterangan: keterangan,
                            total: total,
                            tgl1: tgl1,
                            tgl2: tgl2,
                            no_simpan: no_simpan,
                            no_sp3b: no_sp3b
                        }),
                        beforeSend: function() {
                            $("#save").attr("disabled", "disabled");
                        },
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '1') {
                                alert('Data berhasil diupdate..!!');
                                $('#dg').edatagrid('reload');
                                $('#section4').click();
                            } else if (status == '2') {
                                alert('No LPJ sudah jadi SP2B, tidak dapat diedit lagi..!!');
                                return;
                            }
                        },
                        complete: function(response) {
                            $("#save").removeAttr('disabled');
                        }
                    });

                });

            });

        }



        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#spp').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/bok/SP3BController/load_data',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function kosong() {
            $("#no_lpj").attr("value", '');
            $("#no_simpan").attr("value", '');
            $("#dd").datebox("setValue", '');
            $("#dd2").datebox("setValue", '');
            $("#dd1").datebox("setValue", '');
            $("#keterangan").attr("value", '');
            $("#rektotal").attr("value", 0);
            lcstatus = 'tambah';

        }


        function kembali() {
            $('#kem').click();
        }

        function openWindow(url) {
            var vnospp = document.getElementById('cspp').value;
            var kode = $('#skpd').val();
            var cspp = $('#cspp').val();
            var tglq = $('#dd1').datebox('getValue');
            var tglr = $('#dd2').datebox('getValue');
            var jns = $('#jenis').val();
            var tglttd = $('#ddd').datebox('getValue');
            var ttd = $('#ttd1').combogrid('getValue');
            var ttd_2 = $('#ttd2').combogrid('getValue');

            lc = "?nomerspp=" + vnospp + "&kdskpd=" + kode + "&jnsspp=" + jns + "&cspp=" + cspp + "&tgl1=" + tglq + "&tgl2=" + tglr + "&tglttd=" + tglttd + "&ttd=" + ttd + "&ttd_2=" + ttd_2;
            window.open(url + lc, '_blank');
            window.focus();
        }

        function get_skpd() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                }
            });
        }

        function rupiah(n) {
            let n1 = n.split('.').join('');
            let rupiah = n1.split(',').join('.');
            return parseFloat(n1) || 0;
        }

        function datagrid_kosong() {
            $('#dg1').edatagrid('selectAll');
            var rows = $('#dg1').edatagrid('getSelections');
            for (var i = rows.length - 1; i >= 0; i--) {
                var index = $('#dg1').edatagrid('getRowIndex', rows.idx);
                $('#dg1').edatagrid('deleteRow', index);
                //alert("aa");
            }
        }


        // Hakam
        $(document).ready(function() {
            $('#tambah').click(function() {
                kosong();
                lcstatus = 'tambah';
                if (lcstatus == 'tambah') {
                    $('#section1').click();
                    get_skpd();
                    $("#rektotal").attr("value", 0);
                    $('#dg1').edatagrid({
                        idField: 'idx',
                        toolbar: "#toolbar",
                        rownumbers: "true",
                        fitColumns: false,
                        autoRowHeight: "false",
                        singleSelect: "true",
                        nowrap: "false",
                        columns: [
                            [{
                                    field: 'idx',
                                    title: 'idx',
                                    width: 100,
                                    align: 'left',
                                    hidden: true
                                },
                                {
                                    field: 'kd_skpd',
                                    title: 'Puskesmas',
                                    width: 100,
                                    align: 'left'
                                },
                                {
                                    field: 'no_bukti',
                                    title: 'No Bukti',
                                    width: 100,
                                    align: 'left'
                                },
                                {
                                    field: 'kd_sub_kegiatan',
                                    title: 'Kode Kegiatan',
                                    width: 150,
                                    align: 'left'
                                },
                                {
                                    field: 'kd_rek6',
                                    title: 'Rekening',
                                    width: 70,
                                    align: 'left'
                                },
                                {
                                    field: 'nm_rek6',
                                    title: 'Nama Rekening',
                                    width: 280
                                },
                                {
                                    field: 'nilai',
                                    title: 'Nilai',
                                    width: 140,
                                    align: 'left'
                                },
                                {
                                    field: 'Aksi',
                                    title: 'Aksi',
                                    width: 50,
                                    align: "left",
                                    formatter: function(value, rec) {
                                        return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                                    }
                                }
                            ]
                        ]
                    });
                    $('#load_data').click(function() {
                        var tgl1 = $('#dd1').datebox('getValue');
                        var tgl2 = $('#dd2').datebox('getValue');
                        var tgllpj = $('#dd').datebox('getValue');
                        var nolpj = $('#no_lpj').val();
                        var keterangan = $('#keterangan').val();
                        if (tgl1 == '') {
                            alert('Isi Tanggal Awal Terlebih Dahulu...!!!');
                            return;
                        }
                        if (tgl2 == '') {
                            alert('Isi Tanggal Akhir Terlebih Dahulu...!!!');
                            return;
                        }
                        if (nolpj == '') {
                            alert('Isi No LPJ Terlebih Dahulu...!!!');
                            return;
                        }
                        if (tgllpj == '') {
                            alert('Isi Tanggal LPJ Terlebih Dahulu...!!!');
                            return;
                        }
                        if (keterangan == '') {
                            alert('Isi Keterangan Terlebih Dahulu...!!!');
                            return;
                        }

                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>index.php/bok/SP3BController/load_data_lpj',
                            data: ({
                                tgl1: tgl1,
                                tgl2: tgl2,
                                kdskpd: $('#skpd').val()
                            }),
                            dataType: "json",
                            success: function(data) {
                                $.each(data, function(i, n) {
                                    xnobukti = n['no_bukti'];
                                    xskpd = n['kdskpd'];
                                    xgiat = n['kd_sub_kegiatan'];
                                    xkdrek6 = n['kd_rek6'];
                                    xnmrek6 = n['nm_rek6'];
                                    xtotal = n['total'];
                                    xnilai = new Intl.NumberFormat('id-ID').format(n['nilai']);

                                    $('#dg1').edatagrid('appendRow', {
                                        kd_skpd: xskpd,
                                        no_bukti: xnobukti,
                                        kd_sub_kegiatan: xgiat,
                                        kd_rek6: xkdrek6,
                                        nm_rek6: xnmrek6,
                                        nilai: xnilai,
                                        idx: i
                                    });
                                    $('#dg1').edatagrid('unselectAll');
                                    $('#rektotal').attr('value', new Intl.NumberFormat("id-ID").format(xtotal));
                                });

                            }

                        });
                    });

                    $('#save').click(function() {
                        var skpd = $('#skpd').val();
                        var tgllpj = $('#dd').datebox('getValue');
                        var nolpj = $('#no_lpj').val();
                        var no_sp3b = $('#no_sp3b').val();
                        var keterangan = $('#keterangan').val();
                        var total = rupiah($('#rektotal').val());
                        var tgl1 = $('#dd1').datebox('getValue');
                        var tgl2 = $('#dd2').datebox('getValue');
                        if (nolpj == '') {
                            swal("Error", "Nomor Tidak Boleh Kosong!", "error");
                            return;
                        }
                        if (tgllpj == '') {
                            swal("Error", "Tanggal Tidak Boleh Kosong!", "error");
                            return;
                        }
                        if (keterangan == '') {
                            swal("Error", "Nomor Tidak Boleh Kosong!", "error");
                            return;
                        }
                        // var rows = $('#dg1').datagrid('getSelections');
                        // if (rows.length == undefined || rows.length == '') {
                        //     alert('Tidak ada transaksi');
                        //     return;
                        // }
                        var lcinsert;
                        var lcvalues;
                        $('#dg1').datagrid('selectAll');
                        var rows = $('#dg1').datagrid('getSelections');

                        // if (rows.length < 1) {
                        //     alert('Tidak ada transaksi, silahkan cek kembali');
                        //     return;
                        // }
                        for (var i = 0; i < rows.length; i++) {
                            cidx = rows[i].idx;
                            ckd_skpd = rows[i].kd_skpd;
                            cno_bukti = rows[i].no_bukti;
                            ckdgiat = rows[i].kd_sub_kegiatan;
                            crek = rows[i].kd_rek6;
                            cnmrek = rows[i].nm_rek6;
                            cnilai = rupiah(rows[i].nilai);
                            if (i > 0) {
                                lcinsert = "(no_lpj, no_bukti, kd_sub_kegiatan, kd_rek6, nm_rek6, nilai, kd_skpd)";
                                lcvalues = lcvalues + "," + "('" + nolpj + "','" + cno_bukti + "','" + ckdgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                                    "','" + ckd_skpd + "')";
                            } else {
                                lcinsert = "(no_lpj, no_bukti, kd_sub_kegiatan, kd_rek6, nm_rek6, nilai, kd_skpd)";
                                lcvalues = "values('" + nolpj + "','" + cno_bukti + "','" + ckdgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                                    "','" + ckd_skpd + "')";
                            }
                        }

                        $(document).ready(function() {
                            $.ajax({
                                type: "POST",
                                url: '<?php echo base_url(); ?>index.php/bok/SP3BController/simpan_data',
                                data: ({
                                    kolom: lcinsert,
                                    nilai: lcvalues,
                                    skpd: skpd,
                                    tgllpj: tgllpj,
                                    nolpj: nolpj,
                                    keterangan: keterangan,
                                    total: total,
                                    tgl1: tgl1,
                                    tgl2: tgl2,
                                    no_sp3b: no_sp3b
                                }),
                                beforeSend: function() {
                                    $("#save").attr("disabled", "disabled");
                                },
                                dataType: "json",
                                success: function(data) {
                                    status = data;
                                    if (status == '1') {
                                        alert('Data Tersimpan..!!');
                                        $('#dg').edatagrid('reload');
                                        $('#section4').click();
                                    } else if (status == '2') {
                                        alert('No LPJ sudah ada..!!');
                                        return;
                                    }
                                },
                                complete: function(response) {
                                    $("#save").removeAttr('disabled');
                                }
                            });
                        });
                    });
                }


            });

            // Cetak 
            $('#cetak').click(function() {
                $("#dialog-modal").dialog('open');
            });

            // cetak ttd 

            $('#cetakk').click(function() {
                $("#dialog-modal").dialog('open');
            });



        });

        function section4() {
            $(document).ready(function() {
                $('#section4').click();
                // location.reload();
            });
        }

        function section5() {
            $(document).ready(function() {
                $('#section5').click();
                // location.reload();
            });
        }



        function hapus() {
            var skpd = $('#skpd').val();
            var nomolpj = $('#no_lpj').val();
            var tny = confirm('Yakin Ingin Menghapus Data, Nomor Bukti : ' + nomolpj);
            if (tny == true) {
                $(document).ready(function() {
                    $.ajax({
                        url: '<?php echo base_url(); ?>index.php/bok/SP3BController/hapus_data',
                        dataType: 'json',
                        type: "POST",
                        data: ({
                            nomolpj: nomolpj,
                            skpd: skpd
                        }),
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == '2') {
                                swal("Error", "No LPJ sudah jadi SP2B !", "warning");
                            } else if (status_cek == '1') {
                                swal("Berhasil", "Berhasil dihapus!", "success");
                            }
                        }

                    });
                });
            }
        }

        function hapus_detail() {

            var a = document.getElementById('no_lpj').value;
            $('#dg1').edatagrid('selectAll');
            var rows = $('#dg1').edatagrid('getSelected');
            var ctotal_lpj = document.getElementById('rektotal').value;
            bbukti = rows.no_bukti;
            bkdrek = rows.kd_rek6;
            bkdkegiatan = rows.kd_sub_kegiatan;
            bnilai = rows.nilai;
            ctotal_lpj = ctotal_lpj - bnilai;

            if (status_lpj == 1 || status_lpj == 2) {
                alert('Sudah Disetujui tidak bisa dihapus');
                return;
            } else {
                var idx = $('#dg1').edatagrid('getRowIndex', rows);
                var tny = confirm('Yakin Ingin Menghapus Data, No Bukti :  ' + bbukti + '  Rekening :  ' + bkdrek + '  Nilai :  ' + bnilai + ' ?');
                if (tny == true) {
                    $('#rektotal').attr('value', number_format(ctotal_lpj, 2, '.', ','));
                    $('#dg1').datagrid('deleteRow', idx);
                    $('#dg1').datagrid('unselectAll');
                }
            }
        }

        $(function() {
            $('#ttd1').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/jkn/SP2BController/load_ttd_dinkes/PA',
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
                url: '<?php echo base_url(); ?>index.php/jkn/SP2BController/load_ttd/JKNBOK-PA',
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

        // $(document).ready(function(){
        //     $('#cetakpdf').click(function () {
        //         var ttd = $('#ttd1').combogrid('getValue');
        //         var ttd_2 = $('#ttd2').combogrid('getValue');
        //         var cspp = document.getElementById('cspp').value;
        //         var jenis = document.getElementById('jenis').value;
        //         var tglq = $('#dd1').datebox('getValue');
        //         var tgl = $('#ddd').datebox('getValue');
        //         url = "<?php echo site_url(); ?>bok/SP3BController/laporanlpjbok/" + $cetak + "/" + atas + "/" + bawah + "/" + kiri + "/" + kanan;

        //         // url = "<?php echo site_url(); ?>bok/SP3BController/laporanlpjbok";
        //         if(ttd==''){
        //             alert("Pilih Tanggal Tanda Tangan Dinkes Terlebih Dahulu");
        //             return;
        //         }
        //         if(ttd2==''){
        //             alert("Pilih Tanggal Tanda Tangan Puskesmas Terlebih Dahulu");
        //             return;
        //         }
        //         if(jenis==''){
        //             alert("Pilih Jenis Cetakan Terlebih Dahulu");
        //             return;
        //         }
        //         if(tgl==''){
        //             alert("Pilih Tanggal Tanda Tangan Terlebih Dahulu");
        //             return;
        //         }
        //         openWindow(url);
        //     });

        //     });

        function cek1($cetak) {
            var ttd = $('#ttd1').combogrid('getValue');
            var ttd_2 = $('#ttd2').combogrid('getValue');
            var ctglttd = $('#ddd').datebox('getValue');
            var jenis = $('#jenis').val();
            var atas = document.getElementById('atas').value;
            var bawah = document.getElementById('bawah').value;
            var kanan = document.getElementById('kanan').value;
            var kiri = document.getElementById('kiri').value;

            url = "<?php echo site_url(); ?>bok/SP3BController/laporanlpjbok/" + $cetak + "/" + atas + "/" + bawah + "/" + kiri + "/" + kanan;
            if (ttd == '') {
                alert("Pilih Tanggal Tanda Tangan Dinkes Terlebih Dahulu");
                return;
            }
            if (ttd_2 == '') {
                alert("Pilih Tanggal Tanda Tangan Puskesmas Terlebih Dahulu");
                return;
            }
            if (jenis == '' || jenis == undefined) {
                alert("Pilih Jenis Cetakan Terlebih Dahulu");
                return;
            }
            if (jenis == '2') {
                alert("Sedang Perbaikan");
                return;
            }
            if (ctglttd == '') {
                alert("Pilih Tanggal Tanda Tangan Terlebih Dahulu");
                return;
            }
            openWindow(url);

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
        <div id="accordion" style="width:970px;height:970px;">
            <h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List SP3B BOK</a></h3>
            <div>
                <p align="center">
                <h1>INPUT SP3B BOK</h1>
                </p>
                <p align="right">
                    <button class="button" id="tambah"><i class="fa fa-plus" style="font-size:15px"></i> Tambah</button>
                    <input type="text" value="" id="txtcari" />
                    <button class="button-biru" onclick="javascript:cari();"><i class="fa fa-search" style="font-size:15px"></i> </button>
                <table id="spp" title="List SP3B" style="width:910px;height:450px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section1">Input SP3B</a></h3>

            <div style="height: 350px;">
                <p id="p1" style="font-size: x-large;color: red;"></p>
                <p>




                <fieldset style="width:850px;height:650px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">

                    <table border='0' style="font-size:11px">

                        <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">

                            <td width='20%'>Puskesmas</td>
                            <td width="80%"><input id="skpd" name="skpd" readonly="true" style="width:200px; border: 0; " />
                                <input type="text" name="nmskpd" id="nmskpd" style="width:300px;"> </input>
                            </td>
                        </tr>


                        <tr style=" border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
                            <td width='20%'>No LPJ</td>
                            <td width='80%'><input type="text" name="no_lpj" id="no_lpj" placeholder="Nomor LPJ Tanpa Spasi" style="width:225px" />
                                <input type="text" name="no_simpan" id="no_simpan" style="border:0;width:225px" hidden />
                            </td>
                        </tr>
                        <tr style=" border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
                            <td width='20%'>No SP3B</td>
                            <td width='80%'><input type="text" name="no_sp3b" id="no_sp3b" placeholder="Nomor SP3B Tanpa Spasi" style="width:225px" />
                                <input type="text" name="no_sp3b_simpan" id="no_sp3b_simpan" style="border:0;width:225px" hidden />
                            </td>
                        </tr>

                        <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
                            <td width='20%'>Tanggal</td>
                            <td>&nbsp;<input id="dd" name="dd" type="text" style="width:95px" /></td>
                        </tr>
                        <tr>
                            <td width='20%' style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">KETERANGAN</td>
                            <td width='80%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea name="keterangan" id="keterangan" cols="30" rows="2"></textarea></td>
                        </tr>

                        <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
                            <td width='20%'></td>
                            <td width="80%">&nbsp; </td>
                        </tr>
                        <tr style="height: 30px;">
                            <td colspan="4">
                                <div align="right">
                                    <button type="button" id="save" type="primary"><i class="fa fa-save" style="font-size:15px;color:blue"></i> Simpan</button>
                                    <button type="button" id="delete" class="btn btn-danger" onclick="javascript:hapus();">Hapus</button>
                                    <button id="cetak" class="button-orange"><i class="fa fa-print" style="font-size:15px"></i> Cetak</button>
                                    <button id="kem" class="button-kuning" onclick="javascript:section4();"><i class="fa fa-arrow-left" style="font-size:15px"></i> Kembali</button>
                                </div>
                            </td>
                        </tr>

                        <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">

                            <td colspan='6'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Transaksi</td>
                        </tr>

                        <tr style="height: 10px;">
                            <td colspan='4'>
                                <input id="dd1" name="dd1" type="text" style="width:95px" />&nbsp;S/D&nbsp;<input id="dd2" name="dd2" type="text" style="width:95px" />
                                &nbsp;&nbsp;&nbsp;&nbsp;<a id="load_data" style="width:70px" class="easyui-linkbutton" iconCls="icon-add" plain="true">Tampil</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <!-- <a id="load_kosong" style="width:70px" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Kosong</a> -->
                            </td>
                        </tr>


                    </table>
                    <table id="dg1" title="Input Detail LPJ" style="width:900%;height:300%;">
                    </table>
                    <table border='0' style="width:100%;height:5%;">
                        <tr>
                            <td style="border-bottom: none;"><B>Total</B></td>
                            <td style="border-bottom: none;"><input type="text" name="rektotal" id="rektotal" style="width:200px" readonly="true"></td>
                        </tr>


                    </table>

                    </p>

                </fieldset>
            </div>
        </div>
        <div id="loading" title="Loading...">
            <table align="center">
                <tr align="center">
                    <td><img id="search1" height="50px" width="50px" src="<?php echo base_url(); ?>/image/loadingBig.gif" /></td>
                </tr>
                <tr>
                    <td>Loading...</td>
                </tr>
            </table>
        </div>


    </div>



    <div id="dialog-modal" title="CETAK LPJ">
        <fieldset>
            <table>
                <tr>
                    <td width="200px">NO LPJ :</td>
                    <td><input id="cspp" disabled name="cspp" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td>TTD Dinkes :</td>
                    <td><input type="text" id="ttd1" style="width: 200px;" /> <input type="text" id="nm_ttd1" readonly="true" style="width: 150px;border:0" /></td>

                </tr>
                <tr>
                    <td>TTD Puskesmas :</td>
                    <td><input type="text" id="ttd2" style="width: 200px;" /> <input type="nm_ttd2" id="nm_ttd2" readonly="true" style="width: 150px;border:0" /></td>
                </tr>
                <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
                    <td width='20%'>Tanggal TTD :</td>
                    <td>&nbsp;<input id="ddd" name="ddd" type="text" style="width:95px" /></td>
                </tr>
                <tr>
                    <td>Jenis Cetakan</td>
                    <td>
                        <select name="jenis" id="jenis">
                            <option value=" "> --Pilih--</option>
                            <option value="0"> SP3B</option>
                            <option value="1"> LPJ</option>
                            <option value="2"> Lampiran</option>
                    </td>
                </tr>

                <tr>
                    <td colspan='2' width="100%" height="40"><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
                </tr>
                <tr>
                    <td colspan='2'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Kiri : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                        Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                        Atas : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                        Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                    </td>
                </tr>

                <tr style="height: 30px;">
                    <td colspan="4">
                        <div align="center">

                            <button id="cetaklayar" onclick="javascript:cek1(0);" class="button-orange"><i class="fa fa-television" style="font-size:15px"></i> Cetak Layar</button>
                            <button id="cetakpdf" class="button-orange" onclick="javascript:cek1(1);"><i class="fa fa-print" style="font-size:15px"></i> PDF</button>
                            <button id="kem" class="button-kuning" onclick="javascript:keluar();"><i class="fa fa-arrow-left" style="font-size:15px"></i> Kembali</button>

                        </div>
                    </td>
                </tr>

            </table>
    </div>
</body>

</html>