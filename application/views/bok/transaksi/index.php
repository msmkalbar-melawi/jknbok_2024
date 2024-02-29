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
        var jenis = '';
        var nomor = '';
        var kd_skpd = '';
        var no_sp2d = '';
        var kd_sub_kegiatan = '';
        var map_pot = '';
        var nm_rek6_pot = '';
        var kd_sub_kegiatan_pot = '';
        var no_potongan;
        var no_kas_pot = '';
        let status = '';

        $(document).ready(function() {

            $('#rek').combogrid({
                panelWidth: 650,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                columns: [
                    [{
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 70,
                            align: 'center'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 200
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 200
                        }
                    ]
                ]
            });
            $('#kd_rekbelanja').combogrid({
                panelWidth: 400,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                columns: [
                    [{
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 200,
                            align: 'center'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 200
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kd_sub_kegiatan_pot = rowData.kd_sub_kegiatan;
                    $('#nm_rekbelanja').attr('value', rowData.nm_rek6);
                }
            });

            $('#kd_rekpot').combogrid({
                url: '<?php echo base_url(); ?>index.php/jkn/TransaksiJKNController/map_pot',
                panelWidth: 400,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                mode: 'remote',
                columns: [
                    [{
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 200,
                            align: 'center'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 200
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    map_pot = rowData.map_pot;
                    nm_rek6_pot = rowData.nm_rek6;
                    $('#nm_rekpot').attr('value', rowData.nm_rek6);
                }
            });
            $('#sp2d').combogrid({
                panelWidth: 400,
                idField: 'no_transaksi',
                textField: 'no_transaksi',
                columns: [
                    [{
                            field: 'no_transaksi',
                            title: 'No Transaksi',
                            width: 100
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Rekening Belanja',
                            width: 100
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening Belanja',
                            width: 200
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 150
                        }
                    ]
                ]
            });

            $('#kode_sub').combogrid({
                panelWidth: 500,
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/kode_sub',
                columns: [
                    [{
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Sub',
                            width: 100
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    var nm_sub_kegiatan = rowData.nm_sub_kegiatan;
                    $("#nm_sub_kegiatan").val(nm_sub_kegiatan);
                }
            });

            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 720,
                width: 1000,
                modal: true,
                autoOpen: false
            });

            $("#dialog-modal-potongan").dialog({
                height: 600,
                width: 1000,
                modal: true,
                autoOpen: false
            });
            $("#tagih").hide();
            get_skpd();
            get_tahun();
        });

        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/loaddata',
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
                            title: 'Nomor',
                            width: 100,
                            align: "center"
                        },
                        {
                            field: 'tgl_bukti',
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
                        },
                        {
                            field: 'status',
                            title: 'Status',
                            width: 60,
                            align: "center"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    lcstatus = 'edit';
                    nomor = rowData.no_kas;
                    tgl_kas = rowData.tgl_kas;
                    no_bukti = rowData.no_bukti;
                    tgl_bukti = rowData.tgl_bukti;
                    kd_skpd = rowData.kd_skpd;
                   // no_sp2d = rowData.no_sp2d;
                    nilai = rowData.nilai;
                    keterangan = rowData.keterangan;
                    kd_sub_kegiatan = rowData.kd_sub_kegiatan;
                    jenis_spp = rowData.jns_spp;
                    kd_rek6 = rowData.kd_rek6;
                    nm_rek6 = rowData.nm_rek6;
                    no_kas_pot = rowData.no_kas_pot;
                    status = rowData.status1;
                    // alert(no_kas_pot);
                    get(nomor, tgl_kas, no_bukti, tgl_bukti, kd_skpd, nilai, keterangan, jenis_spp, kd_rek6, nm_rek6, kd_sub_kegiatan);
                    edit_data();
                }
            });


            // Hakam
            $(document).ready(function() {
                $("#tambah").click(function() {
                    set_grid2();
                    var jnsbeban = $('#beban').val();
                    var tgl = $('#tanggal').datebox('getValue');
                    var keterangan = $('#keterangan').val();
                    var kode_sub = $("#kode_sub").combogrid("getValue");
                    if (jnsbeban == null || tgl == '' || keterangan == '' || kode_sub == '') {
                        alert('Lengkapi data terlebih dahulu');
                        return;
                    }
                    $("#dialog-modal").dialog('open');
                    // Sp2d
                    if (status_transaksi == 'tambah') {
                        // index.php/bok/TransaksiController/sp2d
                        $('#kode_sub').combogrid({
                            panelWidth: 500,
                            idField: 'kd_sub_kegiatan',
                            textField: 'kd_sub_kegiatan',
                            mode: 'remote',
                            url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/kode_sub',
                            columns: [
                                [{
                                        field: 'kd_sub_kegiatan',
                                        title: 'Kode Sub',
                                        width: 100
                                    },
                                    {
                                        field: 'nm_sub_kegiatan',
                                        title: 'Nama Sub Kegiatan',
                                        width: 400
                                    }
                                ]
                            ],
                            onSelect: function(rowIndex, rowData) {
                                kd_sub_kegiatan = rowData.kd_sub_kegiatan;
                                $('#sp2d').combogrid({
                                    panelWidth: 400,
                                    idField: 'no_transaksi',
                                    textField: 'no_transaksi',
                                    mode: 'remote',
                                    url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/sp2d',
                                    queryParams: ({
                                        cskpd: $('#skpd').val(),
                                        jnsbeban: jnsbeban,
                                        kd_sub_kegiatan: kd_sub_kegiatan
                                    }),
                                    columns: [
                                        [{
                                                field: 'no_transaksi',
                                                title: 'Nomor Trans',
                                                width: 100
                                            },
                                            {
                                                field: 'tgl_kas',
                                                title: 'Tanggal',
                                                width: 100
                                            }
                                        ]
                                    ],
                                    onSelect: function(rowIndex, rowData) {
                                        nosp2d = rowData.no_transaksi;
                                        // Rekening Sp2d
                                        $('#rek').combogrid({
                                            url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/rekening_sp2d',
                                            queryParams: ({
                                                //cno_sp2d: nosp2d,
                                                cskpd: $('#skpd').val(),
                                                jnsbeban: jnsbeban
                                            })
                                        });
                                        $('#kd_rekbelanja').combogrid({
                                            url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/rekening_sp2d',
                                            queryParams: ({
                                                cno_sp2d: nosp2d,
                                                cskpd: $('#skpd').val(),
                                                jnsbeban: jnsbeban
                                            })
                                        });
                                        $('#kd_rekbelanja').combogrid({
                                            panelWidth: 400,
                                            idField: 'kd_rek6',
                                            textField: 'kd_rek6',
                                            columns: [
                                                [{
                                                        field: 'kd_rek6',
                                                        title: 'Kode Rekening',
                                                        width: 200,
                                                        align: 'center'
                                                    },
                                                    {
                                                        field: 'nm_rek6',
                                                        title: 'Nama Rekening',
                                                        width: 200
                                                    }
                                                ]
                                            ]
                                        });
                                        $('#rek').combogrid({
                                            panelWidth: 650,
                                            idField: 'kd_rek6',
                                            textField: 'kd_rek6',
                                            mode: 'remote',
                                            columns: [
                                                [{
                                                        field: 'kd_rek6',
                                                        title: 'Kode Rekening',
                                                        width: 70,
                                                        align: 'center'
                                                    },
                                                    {
                                                        field: 'nm_rek6',
                                                        title: 'Nama Rekening',
                                                        width: 200
                                                    },
                                                    {
                                                        field: 'nilai',
                                                        title: 'Nilai',
                                                        width: 200
                                                    }
                                                ]
                                            ],
                                            onSelect: function(rowIndex, rowData) {
                                                nm_rek6 = rowData.nm_rek6;
                                                kd_rek6 = rowData.kd_rek6;
                                                kd_sub_kegiatan = rowData.kd_sub_kegiatan;
                                                nilai = rowData.nilai;
                                                $('#nmrek').attr('value', nm_rek6);
                                                $('#anggaran').attr('value', number_format(nilai, 2, '.', ','));

                                                // Realisasi Rekening Belanja dan Sub Kegiatannya
                                                $.ajax({
                                                    type: 'POST',
                                                    url: "<?php echo base_url(); ?>index.php/bok/TransaksiController/load_realisasi_rekeningsp2d",
                                                    dataType: "json",
                                                    data: {
                                                        cnosp2d: nosp2d,
                                                        ckd_rek6: kd_rek6,
                                                        ckd_sub_kegiatan: kd_sub_kegiatan

                                                    },
                                                    success: function(data) {
                                                        if (data.length == 0) {
                                                            var nilai_realisasi = 0;
                                                            $("#total_transaksi").attr("value", number_format(nilai_realisasi, 2, '.', ','));
                                                            var sisa = angka(nilai) - nilai_realisasi;
                                                            $("#sisa_transaksi").attr("value", number_format(sisa, 2, '.', ','));
                                                        } else {
                                                            $.each(data, function(i, n) {
                                                                var nilai_realisasi = angka(n['nilai']);
                                                                $("#total_transaksi").attr("value", number_format(nilai_realisasi, 2, '.', ','));
                                                                var sisa = angka(nilai) - nilai_realisasi;
                                                                $("#sisa_transaksi").attr("value", number_format(sisa, 2, '.', ','));
                                                            });
                                                        }
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });

                            }
                        });
                        // 
                        $('#dg2').edatagrid({
                            columns: [
                                [{
                                        field: 'no_bukti',
                                        title: 'No Bukti',
                                        hidden: "true",
                                        width: 100
                                    },
                                    {
                                        field: 'no_transaksi',
                                        title: 'No Transaksi',
                                        width: 150
                                    },
                                    {
                                        field: 'kd_sub_kegiatan',
                                        title: 'Sub Kegiatan',
                                        width: 150
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
                                        align: "right",
                                        width: 150
                                    },
                                    {
                                        field: 'hapus',
                                        title: 'Hapus',
                                        width: 100,
                                        align: "center",
                                        formatter: function(value, rec) {
                                            return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                                        }
                                    },
                                ]
                            ]
                        });
                        $('#dg1').edatagrid('selectAll');
                        var rows = $('#dg1').edatagrid('getSelections');
                        for (var p = 0; p < rows.length; p++) {
                            no = rows[p].no_bukti;
                            nosp2d = rows[p].no_sp2d;
                            giat = rows[p].kd_sub_kegiatan;
                            rek5 = rows[p].kd_rek6;
                            nmrek5 = rows[p].nm_rek6;
                            nil = rows[p].nilai;
                            $('#dg2').edatagrid('appendRow', {
                                no_bukti: no,
                                no_transaksi: nosp2d,
                                kd_sub_kegiatan: giat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                            });
                        }
                    } else {
                        $('#sp2d').combogrid({
                            panelWidth: 400,
                            idField: 'no_transaksi',
                            textField: 'no_transaksi',
                            mode: 'local',
                            url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/sp2d',
                            queryParams: ({
                                cskpd: $('#skpd').val(),
                                jnsbeban: jnsbeban
                            }),
                            columns: [
                                [{
                                        field: 'no_transaksi',
                                        title: 'No Transaksi',
                                        width: 100
                                    },
                                    {
                                        field: 'kd_rek6',
                                        title: 'Rekening Belanja',
                                        width: 100
                                    },
                                    {
                                        field: 'nm_rek6',
                                        title: 'Nama Rekening Belanja',
                                        width: 200
                                    },
                                    {
                                        field: 'nilai',
                                        title: 'Nilai',
                                        width: 150
                                    }
                                ]
                            ],
                            onSelect: function(rowIndex, rowData) {
                                nosp2d = rowData.no_sp2d;
                                kd_sub_kegiatan = rowData.kd_sub_kegiatan;
                                // Rekening Sp2d
                                $('#rek').combogrid({
                                    url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/rekening_sp2d',
                                    queryParams: ({
                                        cno_sp2d: nosp2d,
                                        cskpd: $('#skpd').val()
                                    })
                                });
                                $('#rek').combogrid({
                                    panelWidth: 650,
                                    idField: 'kd_rek6',
                                    textField: 'kd_rek6',
                                    mode: 'remote',
                                    columns: [
                                        [{
                                                field: 'kd_rek6',
                                                title: 'Kode Rekening',
                                                width: 70,
                                                align: 'center'
                                            },
                                            {
                                                field: 'nm_rek6',
                                                title: 'Nama Rekening',
                                                width: 200
                                            },
                                            {
                                                field: 'nilai',
                                                title: 'Nilai',
                                                width: 200
                                            }
                                        ]
                                    ],
                                    onSelect: function(rowIndex, rowData) {
                                        nm_rek6 = rowData.nm_rek6;
                                        kd_rek6 = rowData.kd_rek6;
                                        nilai = rowData.nilai;
                                        $('#nmrek').attr('value', nm_rek6);
                                        $('#anggaran').attr('value', number_format(nilai, 2, '.', ','));

                                        // Realisasi Rekening Belanja dan Sub Kegiatannya
                                        $.ajax({
                                            type: 'POST',
                                            url: "<?php echo base_url(); ?>index.php/bok/TransaksiController/load_realisasi_rekeningsp2d",
                                            dataType: "json",
                                            data: {
                                                cnosp2d: nosp2d,
                                                ckd_rek6: kd_rek6,
                                                ckd_sub_kegiatan: kd_sub_kegiatan

                                            },
                                            success: function(data) {
                                                if (data.length == 0) {
                                                    var nilai_realisasi = 0;
                                                    $("#total_transaksi").attr("value", number_format(nilai_realisasi, 2, '.', ','));
                                                    var sisa = angka(nilai) - nilai_realisasi;
                                                    $("#sisa_transaksi").attr("value", number_format(sisa, 2, '.', ','));
                                                } else {
                                                    $.each(data, function(i, n) {
                                                        var nilai_realisasi = angka(n['nilai']);
                                                        $("#total_transaksi").attr("value", number_format(nilai_realisasi, 2, '.', ','));
                                                        var sisa = angka(nilai) - nilai_realisasi;
                                                        $("#sisa_transaksi").attr("value", number_format(sisa, 2, '.', ','));
                                                    });
                                                }
                                            }
                                        });
                                    }
                                });

                            }
                        });

                        $('#dg2').edatagrid({
                            columns: [
                                [{
                                        field: 'no_bukti',
                                        title: 'No Bukti',
                                        hidden: "true",
                                        width: 100
                                    },
                                    {
                                        field: 'no_transaksi',
                                        title: 'No Transaksi',
                                        width: 150
                                    },
                                    {
                                        field: 'kd_sub_kegiatan',
                                        title: 'Sub Kegiatan',
                                        width: 150
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
                                        align: "right",
                                        width: 150
                                    },
                                    {
                                        field: 'hapus',
                                        title: 'Hapus',
                                        width: 100,
                                        align: "center",
                                        formatter: function(value, rec) {
                                            return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                                        }
                                    },
                                ]
                            ]
                        });

                        $('#dg1').edatagrid('selectAll');
                        var rows = $('#dg1').edatagrid('getSelections');
                        for (var p = 0; p < rows.length; p++) {
                            no = rows[p].no_bukti;
                            nosp2d = rows[p].no_sp2d;
                            giat = rows[p].kd_sub_kegiatan;
                            rek5 = rows[p].kd_rek6;
                            nmrek5 = rows[p].nm_rek6;
                            nil = rows[p].nilai;
                            $('#dg2').edatagrid('appendRow', {
                                no_bukti: no,
                                no_transaksi: nosp2d,
                                kd_sub_kegiatan: giat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                            });
                        }
                    }

                });
                // Button Keluar Append Save
                $("#keluar").click(function() {
                    $("#dialog-modal").dialog('close');
                });
                $("#keluarpotongan").click(function() {
                    $("#dialog-modal-potongan").dialog('close');
                });
                // Button Open Append Save
                $("#tambahpotongan").click(function() {
                    $("#dialog-modal-potongan").dialog('open');
                    $('#kd_rekbelanja').combogrid({
                        url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/rekening_sp2d',
                        queryParams: ({
                            cno_sp2d: nosp2d,
                            cskpd: $('#skpd').val()
                        })
                    });
                    $('#kd_rekbelanja').combogrid({
                        panelWidth: 400,
                        idField: 'kd_rek6',
                        textField: 'kd_rek6',
                        columns: [
                            [{
                                    field: 'kd_rek6',
                                    title: 'Kode Rekening',
                                    width: 200,
                                    align: 'center'
                                },
                                {
                                    field: 'nm_rek6',
                                    title: 'Nama Rekening',
                                    width: 200
                                }
                            ]
                        ]
                    });
                });


            });

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

            $('#tglkas').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });


            $('#rekening_awal').combogrid({
                url: "<?php echo base_url(); ?>index.php/bok/TransaksiController/cari_rekening_awal",
                panelWidth: 150,
                idField: 'rekening',
                textField: 'rekening',
                columns: [
                    [{
                            field: 'rekening',
                            title: 'Rekening Bendahara',
                            width: 130
                        },
                        {
                            field: 'nama',
                            title: 'Nama Bendahara',
                            width: 130
                        }
                    ]
                ]
            });

            $('#rekening_tujuan').combogrid({
                url: "<?php echo base_url(); ?>index.php/cms/cari_rekening_tujuan/1",
                panelWidth: 730,
                idField: 'rekening',
                textField: 'rekening',
                mode: 'remote',
                columns: [
                    [{
                            field: 'rekening',
                            title: 'Rekening',
                            width: 120
                        },
                        {
                            field: 'nm_rekening',
                            title: 'Nama',
                            width: 290
                        },
                        {
                            field: 'ket',
                            title: 'Keterangan Tambahan',
                            width: 290
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nm_rekening_tujuan").attr("Value", rowData.nm_rekening);
                    $("#kd_bank_tujuan").combogrid("setValue", rowData.nmbank);

                    document.getElementById('nilai_trf').select();
                }
            });

            $('#kd_bank_tujuan').combogrid({
                url: "<?php echo base_url(); ?>index.php/cms/cari_bank",
                panelWidth: 200,
                idField: 'nama',
                textField: 'nama',
                columns: [
                    [{
                        field: 'nama',
                        title: 'Bank',
                        width: 200
                    }]
                ]
            });

            $('#tglvoucher').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(g) {
                    // cari_tgl();
                }
            });
        });

        function edit_data() {
            $("#save").html('Update');
            status_transaksi = 'edit';
            judul = 'Edit Transaksi';
            $(document).ready(function() {
                $('#section2').click();
                // Transout
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/loadingdata',
                    data: ({
                        no: nomor,
                        skpd: kd_skpd,
                        no_sp2d: no_sp2d
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            nosp2d = n['no_sp2d'];
                            giat = n['kd_sub_kegiatan'];
                            rek5 = n['kd_rek6'];
                            nmrek5 = n['nm_rek6'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                no_sp2d: nosp2d,
                                kd_sub_kegiatan: giat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                            });
                            $('#total').attr("value", number_format(n['total'], 2, '.', ','));
                            $('#total1').attr("value", number_format(n['total'], 2, '.', ','));
                        });
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/loadingdata_potongan',
                    data: ({
                        no: nomor,
                        skpd: kd_skpd,
                        no_kas_pot: no_kas_pot,
                        no_sp2d: no_sp2d
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            nosp2d = n['no_sp2d'];
                            giat = n['kd_sub_kegiatan'];
                            rek5 = n['kd_rek6'];
                            nmrek5 = n['nm_rek6'];
                            kd_rek_trans = n['kd_rek_trans'];
                            ebilling = n['ebilling'];
                            map_pot = n['map_pot'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            $('#potongan').edatagrid('appendRow', {
                                no_bukti: no,
                                no_sp2d: no_sp2d,
                                kd_sub_kegiatan: giat,
                                kd_rek6: rek5,
                                kd_rek_trans: kd_rek_trans,
                                ebilling: ebilling,
                                map_pot: map_pot,
                                nm_rek6: nmrek5,
                                nilai: nil,
                            });
                            $('#total_potongan').attr("value", number_format(n['total'], 2, '.', ','));
                            // $('#total1').attr("value", number_format(n['total'], 2, '.', ','));
                        });
                    }
                });
            });
            set_grid();
        }



        function load_total_spd() {
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
                url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
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
            $('#dg2').edatagrid('selectAll');
            var rows = $('#dg2').edatagrid('getSelected');
            cgiat = rows.kd_sub_kegiatan;
            crek = rows.kd_rek6;
            cnil = rows.nilai;
            var idx = $('#dg2').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : ' + cgiat + ' Rekening : ' + crek + ' Nilai : ' + cnil);
            if (tny == true) {
                $('#dg2').edatagrid('deleteRow', idx);
                $('#dg1').edatagrid('deleteRow', idx);
                total = angka(document.getElementById('total1').value) - angka(cnil);
                $('#total1').attr('value', number_format(total, 2, '.', ','));
                $('#total').attr('value', number_format(total, 2, '.', ','));
                kosong2();
            }

        }

        function hapus_detail_pot() {
            var rows = $('#potongan').edatagrid('getSelected');
            ccrek = rows.kd_rek6;
            ccnilai = rows.nilai;
            var total_pot = angka($('#total_potongan').val());
            var jmltotal = 0;
            jmltotal = total_pot - angka(ccnilai);
            var idx = $('#potongan').edatagrid('getRowIndex', rows);
            var tnya = confirm('Yakin Ingin Menghapus Data, Rekening Potongan : ' + ccrek + ' ?');
            if (tnya == true) {
                $('#potongan').edatagrid('deleteRow', idx);
                $('#total_potongan').val(number_format(jmltotal, 2, '.', ','));
            }

        }

        function load_detail() {
            var kk = nomor;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById("skpd").value;


            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/pindah_bank/load_dtransout_bnk',
                    data: ({
                        no: kk,
                        skpd: cskpd
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            nosp2d = n['no_sp2d'];
                            $('#nmosp2d').attr('value', nosp2d);
                            giat = n['kd_kegiatan'];
                            nmgiat = n['nm_kegiatan'];
                            rek5 = n['kd_rek5'];
                            nmrek5 = n['nm_rek5'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                no_sp2d: nosp2d,
                                kd_sub_kegiatan: giat,
                                nm_kegiatan: nmgiat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil
                            });
                        });
                    }
                });
            });
            set_grid();
        }

        function set_grid() {
            $('#dg1').edatagrid({
                columns: [
                    [{
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true"
                        },
                        {
                            field: 'no_sp2d',
                            title: 'No SP2D',
                            hidden: "true"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Sub Kegiatan',
                            width: 220
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 200
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
                            width: 205,
                            align: "left"
                        }
                    ]
                ]
            });
            $('#potongan').edatagrid({
                columns: [
                    [{
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true"
                        },
                        {
                            field: 'no_sp2d',
                            title: 'No SP2D',
                            hidden: "true"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Sub Kegiatan',
                            width: 210,
                            // hidden: "true"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening Potongan',
                            width: 180,
                        },
                        {
                            field: 'kd_rek_trans',
                            title: 'Kode Transaksi',
                            width: 180,
                        },
                        {
                            field: 'map_pot',
                            title: 'Map Pot',
                            hidden: "true"
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 300,
                            align: "left"
                        },
                        {
                            field: 'ebilling',
                            title: 'Nomor Billing',
                            width: 200,
                            align: "left"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 130,
                            align: "left"
                        },
                        {
                            field: 'hapus',
                            title: 'Hapus',
                            width: 150,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail_pot();" />';
                            }
                        },
                    ]
                ]
            });
        }

        function load_detail2() {
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');
            if (rows.length == 0) {
                set_grid2();
                exit();
            }
            for (var p = 0; p < rows.length; p++) {
                no = rows[p].no_bukti;
                nosp2d = rows[p].no_transaksi;
                giat = rows[p].kd_sub_kegiatan;
                rek5 = rows[p].kd_rek6;
                nmrek5 = rows[p].nm_rek6;
                nil = rows[p].nilai;
                $('#dg2').edatagrid('appendRow', {
                    no_bukti: no,
                    no_transaksi: nosp2d,
                    kd_sub_kegiatan: giat,
                    kd_rek6: rek5,
                    nm_rek6: nmrek5,
                    nilai: nil,
                });
            }
            $('#dg1').edatagrid('unselectAll');
        }

        function set_grid2() {
            $('#dg2').edatagrid({
                columns: [
                    [{
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true",
                            width: 100
                        },
                        {
                            field: 'no_transaksi',
                            title: 'No Transaksi',
                            width: 150
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Sub Kegiatan',
                            width: 150
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
                            align: "right",
                            width: 150
                        },
                        {
                            field: 'hapus',
                            title: 'Hapus',
                            width: 100,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                            }
                        },
                    ]
                ]
            });
        }

        function section1() {
            $(document).ready(function() {
                $('#section1').click();
                $("#no_simpan").attr("value", '');
            });
        }

        function section2() {
            get_nourut();
            set_grid();
            $("#save").html('Simpan');
            $(document).ready(function() {
                $('#section2').click();
                document.getElementById("nomor").focus();
            });
        }


        function get(nomor, tgl_kas, no_bukti, tgl_bukti, kd_skpd, no_sp2d, nilai, keterangan, jenis_spp, kd_rek6, nm_rek6, kd_sub_kegiatan) {
            $("#nomor").attr("value", no_bukti);
            $("#kode_sub").combogrid("setValue", kd_sub_kegiatan);
            $("#sp2d").combogrid("setValue", no_sp2d);
            $("#rek").combogrid("setValue", kd_rek6);
            $("#nmrek").attr("value", nm_rek6);
            $("#tanggal").datebox("setValue", tgl_kas);
            $("#beban").attr("value", jenis_spp);
            $("#keterangan").attr("value", keterangan);
            status_transaksi = 'edit';
        }

        function kosong() {
            // get_nourut();
            cdate = '<?php echo date("Y-m-d"); ?>';
            $("#nomor").attr("value", '');
            $("#nomor_tgl").attr("value", '');
            $("#no_simpan").attr("value", '');
            $("#tanggal").datebox("setValue", '');
            $("#keterangan").attr("value", '');
            $("#beban").attr("value", '');
            $("#total1").attr("value", '0');
            $("#total").attr("value", '0');
            $("#total_potongan").attr("value", '0');
            status_transaksi = 'tambah';
            document.getElementById("nomor").focus();
        }

        function get_nourut() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/no_urut',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#nomor").attr("value", data.no_urut);
                }
            });
        }

        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/loaddata',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function cari_tgl() {

            var kriteria = $('#tglvoucher').datebox('getValue');
            // alert(kriteria);
            if (kriteria == '') {
                alert('Tanggal Tidak Boleh Kosong');
                exit();
            } else {
                $(function() {
                    $('#dg').edatagrid({
                        url: '<?php echo base_url(); ?>/index.php/pindah_bank/load_tgltransout_bnk',
                        queryParams: ({
                            cari: kriteria
                        }),
                        success: function() {
                            // set_grid();
                        }
                    });
                });
            }
        }



        function append_save() {
            var no = document.getElementById('nomor').value;
            var nosp2d = $('#sp2d').combogrid('getValue');
            var rek = $('#rek').combogrid('getValue');
            var nmrek = document.getElementById('nmrek').value;
            var jenis = document.getElementById('beban').value;
            var sisa_transaksi = angka(document.getElementById('sisa_transaksi').value);
            var nilai = document.getElementById('nilai').value;
            var nilai1 = angka(document.getElementById('nilai').value);

            if (sisa_transaksi < nilai1) {
                alert('Sisa Uang tidak cukup');
                return;
            }
            if (rek == '') {
                alert('Pilih Rekening Dahulu');
                return;
            }
            if (nosp2d == '' || nosp2d == undefined) {
                alert('Pilih sp2d Dahulu');
                return;
            }
            if (nilai == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                return;
            }
            $('#dg1').edatagrid('selectAll');
            var rows = $('#dg1').edatagrid('getSelections');
            for (var p = 0; p < rows.length; p++) {
                no = rows[p].no_bukti;
                nosp2d = rows[p].no_transaksi;
                giat = rows[p].kd_sub_kegiatan;
                rek5 = rows[p].kd_rek6;
                nmrek5 = rows[p].nm_rek6;
                nil = rows[p].nilai;
            }
            if (rows.length > 0) {
                alert('Satu Transaksi Hanya boleh satu rekening');
                return;
            }

            $('#dg1').edatagrid('appendRow', {
                no_bukti: no,
                no_sp2d: nosp2d,
                kd_sub_kegiatan: kd_sub_kegiatan,
                kd_rek6: rek,
                nm_rek6: nmrek,
                nilai: nilai
            });
            $('#dg2').edatagrid('appendRow', {
                no_bukti: no,
                no_transaksi: nosp2d,
                kd_sub_kegiatan: kd_sub_kegiatan,
                kd_rek6: rek,
                nm_rek6: nmrek,
                nilai: nilai
            });


            total = angka(document.getElementById('total').value) + angka(nilai);
            $('#total').attr('value', number_format(total, 2, '.', ','));
            $('#total1').attr('value', number_format(total, 2, '.', ','));
        }

        function append_save_potongan() {
            var no = parseInt(document.getElementById('nomor').value);
            no_potongan = no + 1;
            var nosp2d = $('#sp2d').combogrid('getValue');
            var kd_rekbelanja = $('#kd_rekbelanja').combogrid('getValue');
            var kd_rekbpotongan = $('#kd_rekpot').combogrid('getValue');
            var nilai = document.getElementById('nilai_pot').value;
            var ebilling = document.getElementById('ebilling').value;

            if (kd_rekbelanja == '') {
                alert('Kode Rekenig Belanja tidak boleh kosong');
                return;
            }
            if (kd_rekbpotongan == '') {
                alert('Kode Rekenig Potongan tidak boleh kosong');
                return;
            }
            if (nosp2d == '') {
                alert('No bukti tidak boleh kosong');
                return;
            }
            if (ebilling == '') {
                alert('Billing tidak boleh kosong');
                return;
            }
            if (nilai == '') {
                alert('Nilai 0 !, cek lagi');
                return;
            }
            $('#potongan').edatagrid('selectAll');
            $('#potongan').edatagrid('getSelections');
            $('#potongan').edatagrid('appendRow', {
                no_bukti: no_potongan,
                no_sp2d: nosp2d,
                kd_sub_kegiatan: giat,
                kd_rek6: kd_rekbpotongan,
                kd_rek_trans: kd_rekbelanja,
                ebilling: ebilling,
                kd_sub_kegiatan: kd_sub_kegiatan_pot,
                map_pot: map_pot,
                nm_rek6: nm_rek6_pot,
                nilai: nilai,
            });
            total1 = angka(document.getElementById('total_potongan').value);
            total = angka(document.getElementById('total_potongan').value) + angka(nilai);
            $('#total_potongan').attr('value', number_format(total, 2, '.', ','));
            // Setelah Append Save
            $('#kd_rekbelanja').combogrid('setValue', '');
            $('#kd_rekpot').combogrid('setValue', '');
            $('#nilai_pot').val('');
            $('#ebilling').val('');

        }


        function tambah() {
            var nor = document.getElementById('nomor').value;
            var tot = document.getElementById('total').value;
            var kd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            $('#dg2').edatagrid('reload');
            $('#total1').attr('value', tot);
            $('#sp2d').combogrid('setValue', '');
            $('#rek').combogrid('setValue', '');
            $('#sumber_dn').combogrid('setValue', '');
            $('#nmrek').attr('value', '');
            var tgl = $('#tanggal').datebox('getValue');
            var jns1 = document.getElementById('beban').value;
            if (kd != '' && tgl != '' && jns1 != '' && nor != '') {
                $("#dialog-modal").dialog('open');
                load_detail2();
            } else {
                alert('Harap Isi Kode SKPD, Tanggal Transaksi & Jenis Beban SP2D ');
            }
        }

        function kosong2() {
            $('#sp2d').combogrid('setValue', '');
            $('#rek').combogrid('setValue', '');
            $('#nmrek').attr('value', '');
            $('#anggaran').attr('value', 0);
            $('#total_transaksi').attr('value', 0);
            $('#sisa_transaksi').attr('value', 0);
            $('#nilai').attr('value', 0);
        }


        function hapus() {
            var cnomor = document.getElementById('nomor').value;
            var skpd = document.getElementById('skpd').value;
            if (status == '1') {
                alert('Transaksi sudah diLPJkan, tidak bisa dihapus');
                return;
            }
            var urll = '<?php echo base_url(); ?>index.php/bok/TransaksiController/hapus_data';
            var tny = confirm('Yakin Ingin Menghapus Data, Nomor Bukti : ' + cnomor);
            if (tny == true) {
                $(document).ready(function() {
                    $.ajax({
                        url: urll,
                        dataType: 'json',
                        type: "POST",
                        data: ({
                            no: cnomor,
                            skpd: skpd
                        }),
                        success: function(data) {
                            status = data;
                            if (status == '1') {
                                $('#dg').edatagrid('reload');
                                alert('Data Berhasil Terhapus');
                                $('#section1').click();
                                kosong();
                            } else if (status == '0') {
                                alert('Gagal Hapus...!! Transaksi sudah disetor potongan');
                            }
                        }

                    });
                });
            }
        }


        function simpan_transout() {
            var cno = document.getElementById('nomor').value;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            var cket = document.getElementById('keterangan').value;
            var cjenis = document.getElementById('beban').value;
            var sp2d = $('#sp2d').combogrid('getValue');
            var total = angka(document.getElementById('total').value);
            var total_potongan = angka(document.getElementById('total_potongan').value);
            var tahun_input = ctgl.substring(0, 4);
            if (status == '1') {
                alert('Transaksi sudah diLPJkan, tidak bisa diupdate !');
                return;
            }
            var lcinsertpot;
            var lcvaluespot;
            if (cno == '') {
                alert('no tidak boleh kosong');
                return;
            }
            if (ctgl == '') {
                alert('Tanggal tidak boleh kosong');
                return;
            }
            if (cket == '') {
                alert('Keterangan tidak boleh kosong');
                return;
            }

            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                return;
            }

            if (status_transaksi == 'tambah') {
                //    Looping
                $('#dg1').datagrid('selectAll');
                var rows = $('#dg1').datagrid('getSelections');
                for (var p = 0; p < rows.length; p++) {
                    cnobukti = cno;
                    cnosp2d = rows[p].no_sp2d;
                    ckdgiat = rows[p].kd_sub_kegiatan;
                    crek = rows[p].kd_rek6;
                    cnmrek = rows[p].nm_rek6;
                    cnilai = angka(rows[p].nilai);
                    lcinsert = "(no_bukti, kd_skpd, kd_sub_kegiatan, kd_rek6, nm_rek6, nilai, no_sp2d)";
                    lcvalues = "('" + cno + "','" + cskpd + "','" + ckdgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                        "','" + cnosp2d + "')";
                }

                $('#potongan').datagrid('selectAll');
                var rows = $('#potongan').datagrid('getSelections');
                for (var p = 0; p < rows.length; p++) {
                    cnobukti = rows[p].no_bukti;
                    cno_sp2d = rows[p].no_sp2d;
                    ckdgiat = rows[p].kd_sub_kegiatan;
                    crek = rows[p].kd_rek6;
                    ckd_rek_trans = rows[p].kd_rek_trans;
                    cnmrek = rows[p].nm_rek6;
                    cmap_pot = rows[p].map_pot;
                    cebilling = rows[p].ebilling;
                    cnilai = angka(rows[p].nilai);

                    if (p > 0) {
                        lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                        lcvaluespot = lcvaluespot + "," + "('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                            "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    } else {
                        lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                        lcvaluespot = "values('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                            "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    }
                    // lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                    // lcvaluespot = "('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                    //     "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    // if (p > 0) {
                    //     lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                    //     lcvaluespot = "('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                    //         "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    // } else {
                    //     lcinsertpot = '';
                    //     lcvaluespot = '';
                    // }
                }

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/simpan_data',
                        data: ({
                            tabel: 'bok_trdtransout',
                            kolom: lcinsert,
                            kolompot: lcinsertpot,
                            nilai: lcvalues,
                            nilaipot: lcvaluespot,
                            cno: cno,
                            cket: cket,
                            cjenis: cjenis,
                            cskpd: cskpd,
                            ctgl: ctgl,
                            sp2d: sp2d,
                            total: total,
                            no_potongan: no_potongan,
                            total_potongan: total_potongan,
                        }),
                        beforeSend: function() {
                            $("#save").attr("disabled", "disabled");
                        },
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
                        },
                        complete: function(response) {
                            $("#save").removeAttr('disabled');
                            kosong();
                        }
                    });
                });
            } else {
                // Edit
                // alert("EDIT");
                // return;
                //    Looping
                $('#dg1').datagrid('selectAll');
                var rows = $('#dg1').datagrid('getSelections');
                for (var p = 0; p < rows.length; p++) {
                    cnobukti = cno;
                    cnosp2d = rows[p].no_sp2d;
                    ckdgiat = rows[p].kd_sub_kegiatan;
                    crek = rows[p].kd_rek6;
                    cnmrek = rows[p].nm_rek6;
                    cnilai = angka(rows[p].nilai);
                    lcinsert = "(no_bukti, kd_skpd, kd_sub_kegiatan, kd_rek6, nm_rek6, nilai, no_sp2d)";
                    lcvalues = "('" + cno + "','" + cskpd + "','" + ckdgiat + "','" + crek + "','" + cnmrek + "','" + cnilai +
                        "','" + cnosp2d + "')";
                }

                $('#potongan').datagrid('selectAll');
                var rows = $('#potongan').datagrid('getSelections');
                for (var p = 0; p < rows.length; p++) {
                    cnobukti = rows[p].no_bukti;
                    cno_sp2d = rows[p].no_sp2d;
                    ckdgiat = rows[p].kd_sub_kegiatan;
                    crek = rows[p].kd_rek6;
                    ckd_rek_trans = rows[p].kd_rek_trans;
                    cnmrek = rows[p].nm_rek6;
                    cmap_pot = rows[p].map_pot;
                    cebilling = rows[p].ebilling;
                    cnilai = angka(rows[p].nilai);
                    if (p > 0) {
                        lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                        lcvaluespot = lcvaluespot + "," + "('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                            "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    } else {
                        lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                        lcvaluespot = "values('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                            "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    }
                    // lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                    // lcvaluespot = "('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                    //     "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    // if (p > 0) {
                    //     lcinsertpot = "(no_bukti, kd_rek6, nm_rek6, nilai, kd_skpd, kd_rek_trans, ebilling, map_pot, kd_sub_kegiatan)";
                    //     lcvaluespot = "('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + ckd_rek_trans +
                    //         "','" + cebilling + "','" + cmap_pot + "','" + ckdgiat + "')";
                    // } else {
                    //     lcinsertpot = '';
                    //     lcvaluespot = '';
                    // }

                }

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/bok/TransaksiController/update_data',
                        data: ({
                            tabel: 'bok_trdtransout',
                            kolom: lcinsert,
                            kolompot: lcinsertpot,
                            nilai: lcvalues,
                            nilaipot: lcvaluespot,
                            cno: cno,
                            cket: cket,
                            cjenis: cjenis,
                            cskpd: cskpd,
                            ctgl: ctgl,
                            sp2d: sp2d,
                            total: total,
                            cno_potongan: no_kas_pot,
                            total_potongan: total_potongan,
                        }),
                        beforeSend: function() {
                            $("#save").attr("disabled", "disabled");
                        },
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                exit();
                            } else if (status == '1') {
                                alert('Data Berhasil diupdate..!!');
                                $('#dg').edatagrid('reload');
                                $('#section1').click();
                            }
                        },
                        complete: function(response) {
                            $("#save").removeAttr('disabled');
                            kosong();
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
    </script>

</head>

<body>



    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1">List Transaksi BOK</a></h3>
            <div>
                <p align="right">
                    <input type="text" value="" id="txtcari" />
                    <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                    <button class="button" style="display: inline" onclick="javascript:kosong();section2();datagrid_kosong();"><i class="fa fa-tambah"></i> Tambah</button>
                <table id="dg" title="List Transaksi BOK" style="width:870px;height:590px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2">Transaksi</a></h3>
            <div style="height: 350px;">
                <div id="demo"></div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td>Nomor </td>
                        <td><input type="text" class="input" id="nomor" style="width: 200px;" />
                            <input type="hidden" id="nomor_tgl" style="width: 200px;" readonly="true" />
                        </td>
                        <td>Tanggal <input type="text" id="tanggal" style="width: 200px;" /></td>
                    </tr>
                    <tr>
                        <td>S K P D</td>
                        <td><input id="skpd" class="input" name="skpd" style="width: 200px;" /><input type="hidden" id="nmbidang" style="border:0;width: 400px;  " readonly="true" /><input type="hidden" id="kdbidang" class="input" name="kdbidang" style="width: 200px;" /></td>
                        <td>Nama : <input type="text" id="nmskpd" style="border:0;width: 300px;" readonly="true" /></td>
                    </tr>

                    <tr>
                        <td>Jenis Beban</td>
                        <td><select name="beban" id="beban" style="width: 210px;">
                                <option value="">--- Pilih ---</option>
                                <!-- <option value="1">Kapitasi</option>
                                <option value="2">Non Kapitasi (APBD)</option> -->
                                <option value="3">Bantuan Operasional Kesehatan (BOK)</option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Sub Kegiatan</td>
                        <td>
                            <input id="kode_sub" name="kode_sub" style="width:210px;" />
                        </td>
                        <td><input id="nm_sub_kegiatan" name="nm_sub_kegiatan" style="width:300px;" /></td>
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
                    <button id="tambah" style="display: inline" class="button"><i class="fa fa-tambah"></i> Tambah Transaksi</button>
                    <!-- <button id="hapus" style="display: inline" class="button""><i class="fa fa-hapus"></i> </button> -->
                </div>
                <table id="dg1" title="Rekening Belanja BOK" style="width:870px;height:200px;">
                </table>
                <table align="center" style="width:100%;" border="0">
                    <tr>
                        <td width="60%">&nbsp;</td>
                        <td align="right">Total Belanja&nbsp;</td>
                        <td align="right" width="27%">:&nbsp;<input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                    </tr>
                </table>
                <div id="toolbar" align="right">
                    <button id="tambahpotongan" style="display: inline" class="button"><i class="fa fa-tambah"></i> Tambah Potongan</button>
                    <!-- <button id="hapus" style="display: inline" class="button""><i class="fa fa-hapus"></i> </button> -->
                </div>
                <table id="potongan" title="Rekening Potongan Belanja BOK" style="width:870px;height:200px;">
                </table>
                <table align="center" style="width:100%;" border="0">
                    <tr>
                        <td width="60%">&nbsp;</td>
                        <td align="right">Total Potongan</td>
                        <td align="right" width="27%">:&nbsp;<input type="text" id="total_potongan" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="dialog-modal" title="Input Kegiatan *)Semua Inputan Harus Di Isi.">
        <!--<p class="validateTips">Semua Inputan Harus Di Isi.</p>-->
        <fieldset>
            <table>
                <tr>
                    <td>No Transaksi</td>
                    <td>:</td>
                    <td colspan="7"><input id="sp2d" name="sp2d" style="width: 200px;" />
                        <input type="hidden" id="nmosp2d" name="nmosp2d" style="width: 200px;" />
                    </td>
                </tr>
                <tr>
                    <td>Kode Rekening</td>
                    <td>:</td>
                    <td><input id="rek" name="rek" style="width: 200px;" /></td>
                    <td>Nama Rekening</td>
                    <td>:</td>
                    <td colspan="4"><input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr id="this">
                    <td bgcolor="#FFD700">Anggaran </td>
                    <td bgcolor="#FFD700">:</td>
                    <td bgcolor="#FFD700"><input type="text" id="anggaran" readonly="true" style="text-align:right;border:0;width: 150px;" /></td>
                    <td bgcolor="#FFD700">Realisasi</td>
                    <td bgcolor="#FFD700">:</td>
                    <td bgcolor="#FFD700"><input type="text" id="total_transaksi" readonly="true" style="text-align:right;border:0;width: 150px;" /></td>
                    <td bgcolor="#FFD700">Sisa</td>
                    <td bgcolor="#FFD700">:</td>
                    <td bgcolor="#FFD700"><input type="text" id="sisa_transaksi" readonly="true" style="text-align:right;border:0;width: 150px;" /></td>
                </tr>
                <tr>
                    <td>Nilai</td>
                    <td>:</td>
                    <td colspan="7"><input type="text" id="nilai" style="text-align: right; width: 150px;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                        <button class="easyui-linkbutton" iconCls="icon-undo" plain="true" id="keluar">Keluar</button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table id="dg2" title="Input Rekening" style="width:950px;height:180px;">
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

    <div id="dialog-modal-potongan" title="Potongan Rekening Belanja">
        <fieldset>
            <table>
                <tr>
                    <td>Kode Rekening</td>
                    <td>:</td>
                    <td><input id="kd_rekbelanja" name="kd_rekbelanja" style="width: 200px;" /></td>
                    <td>Nama Rekening</td>
                    <td>:</td>
                    <td colspan="4"><input type="text" id="nm_rekbelanja" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr>
                    <td>Kode Rekening Potongan</td>
                    <td>:</td>
                    <td><input id="kd_rekpot" name="kd_rekpot" style="width: 200px;" /></td>
                    <td>Nama Rekening</td>
                    <td>:</td>
                    <td colspan="4"><input type="text" id="nm_rekpot" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr>
                    <td>Nilai</td>
                    <td>:</td>
                    <td colspan="7"><input type="text" id="nilai_pot" style="text-align: right; width: 150px;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                </tr>
                <tr>
                    <td>No Billing</td>
                    <td>:</td>
                    <td colspan="7"><input type="text" id="ebilling" style="text-align: right; width: 150px;" /></td>
                </tr>

            </table>
        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save_potongan();">Simpan</a>
                        <button class="easyui-linkbutton" iconCls="icon-undo" plain="true" id="keluarpotongan">Keluar</button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</body>

</html>