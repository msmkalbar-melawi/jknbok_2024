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
            width: 600px;
            height: 70px;
            padding: 0.4em;
        }

        .satu {
            width: 130px;
        }

        .dua {
            width: 154px;
        }
    </style>
    <script type="text/javascript">
        var kode = '';
        var giat = '';
        var jenis = '';
        var nomor = '';
        var cid = 0;

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 850,
                width: 1100,
                modal: true,
                autoOpen: false
            });
            $("#tagih").hide();
            get_skpd();
            get_tahun();
        });

        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/panjar/load_transout_panjar',
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
                            field: 'ck',
                            title: '',
                            checkbox: 'true',
                            width: 40
                        },
                        {
                            field: 'no_bukti',
                            title: 'Nomor Bukti',
                            width: 50
                        },
                        {
                            field: 'tgl_bukti',
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
                        },
                        {
                            field: 'simbollpj',
                            title: 'LPJ',
                            width: 10,
                            align: "left"
                        },
                        {
                            field: 'simbolspj',
                            title: 'SPJ',
                            width: 10,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomor = rowData.no_bukti;
                    tgl = rowData.tgl_bukti;
                    nokas = rowData.no_kas;
                    tglkas = rowData.tgl_kas;
                    nokas_pot = rowData.no_kas_pot;
                    tglpot = rowData.tgl_pot;
                    ketpot = rowData.ketpot;
                    kode = rowData.kd_skpd;
                    nama = rowData.nm_skpd;
                    ket = rowData.ket;
                    jns = rowData.jns_beban;
                    tot = rowData.total;
                    notagih = rowData.no_tagih;
                    tgltagih = rowData.tgl_tagih;
                    nopanjar = rowData.no_panjar;
                    ststagih = rowData.sts_tagih;
                    vpay = rowData.pay;
                    statlpj = rowData.ketlpj;
                    statspj = rowData.ketspj;
                    get(nokas, tglkas, nomor, tgl, kode, nama, ket, jns, tot, notagih, tgltagih, ststagih, vpay, nokas_pot, tglpot, ketpot, nopanjar, statlpj, statspj);
                    load_detail_pot(nokas_pot);
                    if (ststagih != '1') {
                        load_detail();
                    }
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
                onSelect: function(rowIndex, rowData) {
                    idx = rowIndex;
                    nilx = rowData.nilai;
                },
                columns: [
                    [{
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true"
                        },
                        {
                            field: 'no_sp2d',
                            title: 'No SP2D'
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kegiatan',
                            width: 100
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            hidden: "true"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 50
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 70,
                            align: "right"
                        },
                        {
                            field: 'lalu',
                            title: 'Sudah Dibayarkan',
                            align: "right",
                            width: 30,
                            hidden: 'true'
                        },
                        {
                            field: 'sp2d',
                            title: 'SP2D Non UP',
                            align: "right",
                            width: 30,
                            hidden: 'true'
                        },
                        {
                            field: 'anggaran',
                            title: 'Anggaran',
                            align: "right",
                            width: 30,
                            hidden: 'true'
                        }
                    ]
                ]
            });

            $("#sumber_dn").combogrid({
                panelWidth: 300,
                columns: [
                    [{
                            field: '',
                            title: 'Sumber Dana',
                            width: 100
                        },
                        {
                            field: '',
                            title: 'Anggaran',
                            width: 190
                        }
                    ]
                ]
            });

            $('#dgpajak').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/Panjar/pot',
                idField: 'id',
                rownumbers: "true",
                fitColumns: false,
                autoRowHeight: "true",
                singleSelect: false,
                columns: [
                    [{
                            field: 'id',
                            title: 'id',
                            width: 100,
                            align: 'left',
                            hidden: 'true'
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Rekening',
                            width: 100,
                            align: 'left'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 317
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 250,
                            align: "right"
                        },
                        {
                            field: 'hapus',
                            title: 'Hapus',
                            width: 100,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pot();" />';
                            }
                        }
                    ]
                ]
            });

            $('#dg2').edatagrid({
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "true",
                loadMsg: "Tunggu Sebentar....!!",
                nowrap: "false",
                onSelect: function(rowIndex, rowData) {
                    cidx = rowIndex;
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
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true",
                            width: 30
                        },
                        {
                            field: 'no_sp2d',
                            title: 'No SP2D',
                            width: 40
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kegiatan',
                            width: 50
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            hidden: "true",
                            width: 30
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 25,
                            align: 'center'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            align: "left",
                            width: 40
                        },
                        {
                            field: 'nilai',
                            title: 'Rupiah',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'lalu',
                            title: 'Sudah Dibayarkan',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber Dana',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'sp2d',
                            title: 'SP2D Non UP',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'anggaran',
                            title: 'Anggaran',
                            align: "right",
                            width: 30
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
                },
                onSelect: function(date) {
                    //cek_status_ang();
                    cek_status_spj();
                }
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

            $('#tgl_kas').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    $("#tanggal").datebox("setValue", y + '-' + m + '-' + d);
                    cek_status_ang();
                    cek_status_angkas();
                }
            });


            $('#no_panjar').combogrid({
                panelWidth: 400,
                idField: 'no_panjar_lalu',
                textField: 'no_panjar_lalu',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/panjar/load_nopanjar_trans',
                columns: [
                    [{
                            field: 'no_panjar_lalu',
                            title: 'No Panjar',
                            width: 100
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 150
                        },
                        {
                            field: 'kembali',
                            title: 'Kembali',
                            width: 150
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    no_panjar = rowData.no_panjar_lalu;
                    $("#nopanjar").attr("value", no_panjar);
                    $('#total_panjar').attr('value', number_format(rowData.nilai, 2, '.', ','));
                    $('#kembali_panjar').attr('value', number_format(rowData.kembali, 2, '.', ','));
                    $("#giat").combogrid({
                        url: '<?php echo base_url(); ?>index.php/panjar/load_giat_panjar',
                        queryParams: ({
                            nomor: no_panjar
                        })
                    });
                }
            });



            $('#giat').combogrid({
                panelWidth: 700,
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                queryParams: ({
                    kd: kode,
                    jenis: jenis
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
                    idxGiat = rowIndex;
                    giat = rowData.kd_sub_kegiatan;
                    var panjar = $('#no_panjar').combogrid('getValue');
                    var nobukti = document.getElementById('nomor').value;
                    var kode = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');


                    $("#nmgiat").attr("value", rowData.nm_sub_kegiatan);
                    var jns = document.getElementById('beban').value;

                    $('#sp2d').combogrid({
                        url: '<?php echo base_url(); ?>index.php/panjar/load_sp2d_panjar',
                        queryParams: ({
                            giat: giat,
                            jns: jns
                        })
                    });


                }
            });


            $('#sp2d').combogrid({
                panelWidth: 350,
                idField: 'nosp2d',
                textField: 'nosp2d',
                mode: 'remote',
                columns: [
                    [{
                            field: 'nosp2d',
                            title: 'Kode Rekening',
                            width: 200
                        },
                        {
                            field: 'tglsp2d',
                            title: 'Nama Rekening',
                            width: 150
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nosp2d = rowData.nosp2d;
                    var panjar = $('#no_panjar').combogrid('getValue');
                    var nobukti = document.getElementById('nomor').value;
                    var jns = document.getElementById('beban').value;
                    var kode = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
                    $('#rek').combogrid({
                        url: '<?php echo base_url(); ?>index.php/panjar/load_rek_panjar',
                        queryParams: ({
                            no: nobukti,
                            giat: giat,
                            kd: kode,
                            panjar: panjar,
                            nosp2d: nosp2d,
                            jns: jns
                        })
                    });
                    document.getElementById('nilai').select();


                }
            });


            $('#rekpajak').combogrid({
                panelWidth: 700,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/panjar/rek_pot',
                columns: [
                    [{
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 100
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 700
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nmrekpajak").attr("value", rowData.nm_rek6.toUpperCase());
                }
            });

            $('#notagih').combogrid({
                panelWidth: 420,
                idField: 'no_tagih',
                textField: 'no_tagih',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/panjar/load_no_penagihan',
                queryParams: ({
                    kd: kode
                }),
                columns: [
                    [{
                            field: 'no_tagih',
                            title: 'No Penagihan',
                            width: 140
                        },
                        {
                            field: 'tgl_tagih',
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
                    $("#tgltagih").attr("value", rowData.tgl_tagih);
                    $("#skpd").attr("value", rowData.kd_skpd);
                    $("#keterangan").attr("value", rowData.ket);
                    $("#beban").attr("value", '1');
                    $("#total").attr("value", number_format(rowData.nil, 2, '.', ','));
                    load_detail_tagih();
                    tombol(ststagih);

                }
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
                            field: 'lalu',
                            title: 'Transaksi Lalu',
                            width: 120,
                            align: 'right'
                        },
                        {
                            field: 'panjar_lalu',
                            title: 'Panjar Lalu',
                            width: 120,
                            align: 'right'
                        },
                        {
                            field: 'ang_murni',
                            title: 'Anggaran',
                            width: 120,
                            align: 'right'
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    var jenis = document.getElementById('beban').value;
                    var total_panjar = angka(document.getElementById('total_panjar').value);
                    var kembali_panjar = angka(document.getElementById('kembali_panjar').value);
                    var jenis = document.getElementById('beban').value;
                    var sp2dd = $('#sp2d').combogrid('getValue');
                    // var anggaran = rowData.anggaran;
                    //var lalu = rowData.lalu;
                    var ang_murni = rowData.ang_murni;
                    // var ang_semp = rowData.ang_semp;
                    // var ang_ubah = rowData.ang_ubah;
                    var lalu = rowData.lalu;
                    var sp2d = rowData.sp2d;
                    var panjar_lalu = rowData.panjar_lalu;
                    //sisa = anggaran-lalu;    
                    sisa_panjar = total_panjar - panjar_lalu - kembali_panjar;
                    //$('#sisa').attr('value',number_format(sisa,2,'.',','));
                    $('#panjar_lalu').attr('value', number_format(panjar_lalu, 2, '.', ','));
                    $('#sisa_panjar').attr('value', number_format(sisa_panjar, 2, '.', ','));
                    $('#nmrek').attr('value', rowData.nm_rek6);

                    if (jenis == '1') {
                        sisa_murni = ang_murni - lalu;
                        // sisa_semp = ang_semp-lalu;    
                        // sisa_ubah = ang_ubah-lalu; 
                        $('#ang_murni').attr('value', number_format(ang_murni, 2, '.', ','));
                        //    $('#ang_semp').attr('value',number_format(ang_semp,2,'.',','));
                        //    $('#ang_ubah').attr('value',number_format(ang_ubah,2,'.',','));


                    } else {
                        sisa_murni = sp2d - lalu;
                        // sisa_semp = sp2d-lalu;                    
                        // sisa_ubah = sp2d-lalu; 
                        $('#ang_murni').attr('value', number_format(sp2d, 2, '.', ','));
                        // $('#ang_semp').attr('value',number_format(sp2d,2,'.',','));
                        // $('#ang_ubah').attr('value',number_format(sp2d,2,'.',','));                   
                    }
                    $('#lalu_murni').attr('value', number_format(lalu, 2, '.', ','));
                    $('#lalu_sd').attr('value', number_format(lalu, 2, '.', ','));
                    // $('#lalu_semp').attr('value',number_format(lalu,2,'.',','));
                    // $('#lalu_ubah').attr('value',number_format(lalu,2,'.',','));
                    $('#sisa_murni').attr('value', number_format(sisa_murni, 2, '.', ','));
                    // $('#sisa_semp').attr('value',number_format(sisa_semp,2,'.',','));
                    // $('#sisa_ubah').attr('value',number_format(sisa_ubah,2,'.',','));
                    // load_total_trans();
                    // total_sisa_spd();
                    // sisa_sumberdana();
                    load_total_angkas();
                    load_total_spd();

                    sumber_dnkosong();
                    var kd_giat = $('#giat').combogrid('getValue');
                    var rek6 = rowData.kd_rek6;
                    var ctgl = $('#tanggal').datebox('getValue');

                    $('#sumber_dn').combogrid({
                        panelWidth: 700,
                        idField: 'sumber',
                        textField: 'sumber',
                        mode: 'remote',
                        url: '<?php echo base_url(); ?>/index.php/cms/ambil_sdana',

                        queryParams: ({
                            tgl: ctgl,
                            skpd: kode,
                            giat: kd_giat,
                            rek: rek6,
                            jnsbeban: jenis,
                            nosp2d: sp2dd
                        }),
                        columns: [
                            [{
                                    field: 'sumber_dana',
                                    title: 'Sumber Dana',
                                    width: 98
                                },
                                {
                                    field: 'nilaidana',
                                    title: 'S. Dana Penyusunan',
                                    width: 98
                                },
                                //   {field:'anggaran_semp',title:'S. Dana Penyempurnaan',width:98},
                                //     {field:'anggaran_ubah',title:'S. Dana Perubahan',width:98},
                                // {field:'kegiatan',title:'kegiatan',width:120},
                                {
                                    field: 'rek6',
                                    title: 'rekening',
                                    width: 98
                                }
                            ]
                        ],
                        onSelect: function(rowIndex, rowData) {
                            selectRow = rowData.sumber;
                            nilai = rowData.nilaidana;
                            $('#ketsdana1').attr('value', rowData.sumber_dana);
                            $('#nmsumberdana').attr('value', rowData.nmsumber);
                            $('#ang_sd').attr('value',nilai);
                            // $('#ang_semp_sd').attr('value',rowData.anggaran_semp);
                            // $('#ang_ubah_sd').attr('value',rowData.anggaran_ubah);
                            // load_total_sdana(kd_giat, rek, selectRow);
                            sisa_sumber();
                        }
                    });
                }
            });
        });

        function load_total_sdana(giat1, kode_rek1, sumber1) {
            var kode1 = document.getElementById('skpd').value;
            var ang_sd = angka(document.getElementById('ang_sd').value);
            var nosp2d1 = $('#sp2d').combogrid('getValue');
            // var sumber = $('#ketsdana1').combogrid('getValue');
            // $('#lalu_sd').attr('value', '9,999,999,999,999.00');
            // alert(giat1);
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tukd/load_total_sdana2",
                    dataType: "json",
                    data: ({
                        giat: giat1,
                        kode: kode1,
                        sumber: sumber1,
                        kode_rek: kode_rek1,
                        nosp2d: nosp2d1
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            var total_trans_sumber2 = angka(n['total_trans_sumber']);
                            // var total = data.total_trans_sumber;
                            // alert(total);
                            $('#lalu_sd').attr("value",n['total_trans_sumber']);
                            // $('#lalu_semp_sd').attr("value", n['total_trans_sumber']);
                            // $('#lalu_ubah_sd').attr("value", n['total_trans_sumber']);
                            $('#sisa_sd').attr("value", number_format((ang_sd), 2, '.', ','));

                        });
                    }
                });
            });

        }

        function sumber_dnkosong() {
            $("#sumber_dn").combogrid("clear");
            $('#ketsdana1').attr('value', '');
            var sumber_dn = $('#sumber_dn').combogrid('grid');
            sumber_dn.datagrid('loadData', []);
            kosong1();
        }

        function sisa_sumber() {
            var ang_sd = angka(document.getElementById('ang_sd').value);
            // alert(ang_sd);
            // var ang_semp_sd = angka(document.getElementById('ang_semp_sd').value);
            // var ang_ubah_sd = angka(document.getElementById('ang_ubah_sd').value);
            var lalu_sd = angka(document.getElementById('lalu_sd').value);
            // alert(lalu_sd);
            // var lalu_semp_sd = angka(document.getElementById('lalu_semp_sd').value);
            // var lalu_ubah_sd = angka(document.getElementById('lalu_ubah_sd').value);
            $('#sisa_sd').attr("value", number_format((ang_sd - lalu_sd), 2, '.', ','));
            // $('#sisa_semp_sd').attr("value",number_format((ang_semp_sd - lalu_semp_sd),2,'.',','));
            // $('#sisa_ubah_sd').attr("value",number_format((ang_ubah_sd - lalu_ubah_sd),2,'.',','));
        }

        function kosong1() {
            $("#sumber_dn").attr("value", '0.00');
            // $("#sumber_semp_dn").attr("value",'0.00');
            // $("#sumber_ubah_dn").attr("value",'0.00');
            // $("#lalu_sd").attr("value", '9,999,999,999,999.00');
            // $("#lalu_semp_sd").attr("value",'9,999,999,999,999.00');
            // $("#lalu_ubah_sd").attr("value",'9,999,999,999,999.00');
            // sisa_sumber();
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
                            // $("#rekspm1").attr("value",n['rekspm1']);
                        });
                    }
                });
            });
        }

        function load_sisa_tunai() {
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tunai/load_sisa_tunai",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#sisa_tunai").attr("value", n['sisa']);
                            // $("#rekspm1").attr("value",n['rekspm1']);
                        });
                    }
                });
            });
        }

        function get_skpd() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    kode = data.kd_skpd;
                    kegia();
                }
            });
        }




        function get_tahun() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spp/config_tahun',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    tahun_anggaran = data;
                }
            });

        }

        function cek_status_ang() {
            var tgl_cek = $('#tanggal').datebox('getValue');
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/tukd/cek_status_ang',
                data: ({
                    tgl_cek: tgl_cek
                }),
                //queryParams    : ({ tgl_cek:tgl_cek }),
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#status_ang").attr("value", data.nm_ang);
                }
            });
        }

        function kegia() {
            $('#giat').combogrid({
                url: '<?php echo base_url(); ?>index.php/panjar/load_trskpd',
                queryParams: ({
                    kd: kode,
                    jenis: '52'
                })
            });
        }

        function hapus_detail_pot() {

            var vnospm = document.getElementById('nomor').value;
            var dinas = document.getElementById('skpd').value;

            var rows = $('#dgpajak').edatagrid('getSelected');
            var ctotalpotspm = document.getElementById('totalrekpajak').value;

            bkdrek = rows.kd_rek6;
            bnilai = rows.nilai;

            var idx = $('#dgpajak').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, Rekening : ' + bkdrek + '  Nilai :  ' + bnilai + ' ?');

            if (tny == true) {

                $('#dgpajak').datagrid('deleteRow', idx);
                $('#dgpajak').datagrid('unselectAll');

                var urll = '<?php echo base_url(); ?>index.php/panjar/dsimpan_pot_delete';
                $(document).ready(function() {
                    $.post(urll, ({
                        cskpd: dinas,
                        spm: vnospm,
                        kd_rek6: bkdrek
                    }), function(data) {
                        status = data;
                        if (status == '0') {
                            alert('Gagal Hapus..!!');
                            exit();
                        } else {
                            alert('Data Telah Terhapus..!!');
                            exit();
                        }
                    });
                });

                ctotalpotspm = angka(ctotalpotspm) - angka(bnilai);
                $("#totalrekpajak").attr("Value", number_format(ctotalpotspm, 2, '.', ','));
                validate_rekening();
            }
        }

        function hapus_detail() {
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

        function load_detail() {
            var kk = nomor;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById("skpd").value; //$('#skpd').combogrid('getValue');             

            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/panjar/load_dtransout',
                    data: ({
                        no: kk
                    }),
                    dataType: "json",
                    success: function(data) {
                        $('#dg1').datagrid('loadData', []);
                        $('#dg1').edatagrid('reload');
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            giat = n['kd_sub_kegiatan'];
                            nmgiat = n['nm_sub_kegiatan'];
                            rek5 = n['kd_rek6'];
                            nmrek5 = n['nm_rek6'];
                            cnosp2d = n['no_sp2d'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            clalu = number_format(n['lalu'], 2, '.', ',');
                            csp2d = number_format(n['sp2d'], 2, '.', ',');
                            canggaran = number_format(n['anggaran'], 2, '.', ',');
                            csumber = n['sumber'];
                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                kd_sub_kegiatan: giat,
                                nm_sub_kegiatan: nmgiat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                                lalu: clalu,
                                no_sp2d: cnosp2d,
                                sp2d: csp2d,
                                anggaran: canggaran,
                                sumber: csumber
                            });
                        });
                    }
                });
            });
            set_grid();
        }



        function load_detail_pot(nosts) {
            //alert(nosts);
            $(function() {
                $('#dgpajak').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/panjar/load_dpot',
                    queryParams: ({
                        no: nosts
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "true",
                    singleSelect: false,
                    columns: [
                        [{
                                field: 'id',
                                title: 'ID',
                                hidden: "true"
                            },
                            {
                                field: 'kd_rek6',
                                title: 'Nomor Rekening',
                                width: 150
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 400
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                align: 'right',
                                width: 200
                            }
                        ]
                    ]
                });
            });
        }

        function load_detail_tagih() {
            var kk = $('#notagih').combogrid('getValue');
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById("skpd").value; //$('#skpd').combogrid('getValue');             
            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/panjar/load_dtagih',
                    data: ({
                        no: kk
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            nosp2d = n['no_sp2d'];
                            giat = n['kd_sub_kegiatan'];
                            nmgiat = n['nm_sub_kegiatan'];
                            rek5 = n['kd_rek'];
                            nmrek5 = n['nm_rek6'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            clalu = number_format(n['lalu'], 2, '.', ',');
                            csp2d = number_format(n['sp2d'], 2, '.', ',');
                            canggaran = number_format(n['anggaran'], 2, '.', ',');
                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                no_sp2d: nosp2d,
                                kd_sub_kegiatan: giat,
                                nm_sub_kegiatan: nmgiat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                                lalu: clalu,
                                sp2d: csp2d,
                                anggaran: canggaran
                            });
                        });
                    }
                });
            });
            set_grid();
        }

        function load_detail_baru() {
            var kk = '';

            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd/load_dtagih',
                    data: ({
                        no: kk
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            nosp2d = n['no_sp2d'];
                            giat = n['kd_sub_kegiatan'];
                            nmgiat = n['nm_sub_kegiatan'];
                            rek5 = n['kd_rek'];
                            nmrek5 = n['nm_rek5'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            clalu = number_format(n['lalu'], 2, '.', ',');
                            csp2d = number_format(n['sp2d'], 2, '.', ',');
                            canggaran = number_format(n['anggaran'], 2, '.', ',');
                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                no_sp2d: nosp2d,
                                kd_sub_kegiatan: giat,
                                nm_sub_kegiatan: nmgiat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                                lalu: clalu,
                                sp2d: csp2d,
                                anggaran: canggaran
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
                            title: 'Kegiatan',
                            width: 100
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            hidden: "true"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 50
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 70,
                            align: "right"
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber Dana',
                            width: 70,
                            align: "right"
                        },
                        {
                            field: 'lalu',
                            title: 'Sudah Dibayarkan',
                            align: "right",
                            width: 30,
                            hidden: 'true'
                        },
                        {
                            field: 'sp2d',
                            title: 'SP2D Non UP',
                            align: "right",
                            width: 30,
                            hidden: 'true'
                        },
                        {
                            field: 'anggaran',
                            title: 'Anggaran',
                            align: "right",
                            width: 30,
                            hidden: 'true'
                        }
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

            $('#dg2').datagrid('loadData', []);
            $('#dg2').edatagrid('reload');

            for (var p = 0; p < rows.length; p++) {
                no = rows[p].no_bukti;
                nosp2d = rows[p].no_sp2d;
                giat = rows[p].kd_sub_kegiatan;
                nmgiat = rows[p].nm_sub_kegiatan;
                rek5 = rows[p].kd_rek6;
                nmrek5 = rows[p].nm_rek6;
                nil = rows[p].nilai;
                lal = rows[p].lalu;
                csp2d = rows[p].sp2d;
                csumber = rows[p].sumber;
                canggaran = rows[p].anggaran;

                $('#dg2').edatagrid('appendRow', {
                    no_bukti: no,
                    no_sp2d: nosp2d,
                    kd_sub_kegiatan: giat,
                    nm_sub_kegiatan: nmgiat,
                    kd_rek6: rek5,
                    nm_rek6: nmrek5,
                    nilai: nil,
                    lalu: lal,
                    sumber: csumber,
                    sp2d: csp2d,
                    anggaran: canggaran
                });
            }
            $('#dg1').edatagrid('unselectAll');
        }

        function set_grid2() {
            $('#dg2').edatagrid({
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
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true",
                            width: 30
                        },
                        {
                            field: 'no_sp2d',
                            title: 'No SP2D',
                            width: 40
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kegiatan',
                            width: 50
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            hidden: "true",
                            width: 30
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 25,
                            align: 'center'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            align: "left",
                            width: 40
                        },
                        {
                            field: 'nilai',
                            title: 'Rupiah',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'lalu',
                            title: 'Sudah Dibayarkan',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'sp2d',
                            title: 'SP2D Non UP',
                            align: "right",
                            width: 30
                        },
                        {
                            field: 'anggaran',
                            title: 'Anggaran',
                            align: "right",
                            width: 30
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
            reload_data();
        }

        function section2() {
            $(document).ready(function() {
                $('#section2').click();
                document.getElementById("nomor").focus();
            });
            set_grid();
        }

        function section3() {
            $(document).ready(function() {
                $('#section3').click();
            });
        }

        function get(nokas, tglkas, nomor, tgl, kode, nama, ket, jns, tot, notagih, tgltagih, ststagih, vpay, nokas_pot, tglpot, ketpot, nopanjar, statlpj, statspj) {
            $("#nomor").attr("value", nomor);
            $("#no_simpan").attr("value", nomor);
            $("#nopanjar").attr("value", nopanjar);
            $("#tanggal").datebox("setValue", tgl);
            $("#no_kas").attr("value", nokas);
            $("#tgl_kas").datebox("setValue", tglkas);
            $("#nokas").attr("value", nokas_pot);
            $("#tglkas").datebox("setValue", tglpot);
            $("#kete").attr("value", ketpot);
            $("#keterangan").attr("value", ket);
            $("#beban").attr("value", jns);
            $("#total").attr("value", number_format(tot, 2, '.', ','));
            $("#notagih").combogrid("setValue", notagih);
            $("#tgltagih").attr("Value", tgltagih);
            $("#jns_tunai").attr("value", vpay);
            $("#status").attr("checked", false);
            status_transaksi = 'edit';
            if (ststagih == 1) {
                $("#status").attr("checked", true);
                $("#tagih").show();
                load_detail_tagih();
            } else {
                $("#status").attr("checked", false);
                $("#tagih").hide();
            }

            tombollpj(statlpj, statspj);
            tombol(ststagih);
        }

        function tombol(st) {
            if (st == '1') {
                $('#tambah').linkbutton('disable');
                $('#hapus').linkbutton('disable');
            } else {
                $('#tambah').linkbutton('enable');
                $('#hapus').linkbutton('enable');

            }
        }

        function tombolnew() {

            $('#tambah').linkbutton('enable');
            $('#hapus').linkbutton('enable');

        }

        function kosong() {
            cdate = '<?php echo date("Y-m-d"); ?>';
            $("#nomor").attr("value", '');
            $("#no_simpan").attr("value", '');
            $("#no_kas").attr("value", '');
            $("#tanggal").datebox("setValue", '');
            $("#tgl_kas").datebox("setValue", '');
            //$("#skpd").combogrid("setValue",'');
            //$("#nmskpd").attr("value",'');
            $("#keterangan").attr("value", '');
            $("#beban").attr("value", '');
            $("#total").attr("value", '0');
            $("#notagih").combogrid("setValue", '');
            $("#tgltagih").attr("value", '');
            $("#jns_tunai").attr("value", '');
            $("#status").attr("checked", false);
            $("#tagih").hide();
            status_transaksi = 'tambah';
            load_detail_baru();
            get_nourut();

            $("#nilai").attr("value", '0');
            $("#jns_tunai").attr("value", "PANJAR");
            $('#save').linkbutton('enable');
            $('#del').linkbutton('enable');

            document.getElementById("nomor").focus();
            tombolnew();
        }

        function get_nourut() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/cms/no_urut',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#no_kas").attr("value", data.no_urut);
                    $("#nomor").attr("value", data.no_urut);
                }
            });
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/panjar/load_transout_panjar',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function append_save_pot() {

            $('#dgpajak').datagrid('selectAll');
            var rows = $('#dgpajak').datagrid('getSelections');
            jgrid = rows.length;

            var rek_pajak = $("#rekpajak").combogrid("getValue");
            var nm_rek_pajak = document.getElementById("nmrekpajak").value;
            var nilai_pajak = document.getElementById("nilairekpajak").value;
            var nil_pajak = angka(nilai_pajak);
            var dinas = document.getElementById('skpd').value;
            var vnospm = document.getElementById('nomor').value;
            var cket = '0';
            var jumlah_pajak = document.getElementById('totalrekpajak').value;
            jumlah_pajak = angka(jumlah_pajak);

            if (rek_pajak == '') {
                alert("Isi Rekening Terlebih Dahulu...!!!");
                exit();
            }

            if (nilai_pajak == 0) {
                alert("Isi Nilai Terlebih Dahulu...!!!");
                exit();
            }

            pidx = jgrid + 1;

            $('#dgpajak').edatagrid('appendRow', {
                kd_rek6: rek_pajak,
                nm_rek6: nm_rek_pajak,
                nilai: nilai_pajak,
                id: pidx
            });
            //$(document).ready(function(){      
            //                $.ajax({
            //                type     : 'POST',
            //                url      : "<?php echo base_url(); ?>index.php/tukd/dsimpan_pot_ar",
            //                data     : ({cskpd:dinas,spm:vnospm,kd_rek5:rek_pajak,nmrek:nm_rek_pajak,nilai:nil_pajak,ket:cket}),
            //                dataType : "json"
            //                });
            //            });

            //$("#rekpajak").combogrid("setValue",'');
            //$("#nmrekpajak").attr("value",'');
            $("#nilairekpajak").attr("value", 0);
            jumlah_pajak = jumlah_pajak + nil_pajak;
            $("#totalrekpajak").attr('value', number_format(jumlah_pajak, 2, '.', ','));
            validate_rekening();

        }

        function append_save() {

            var no = document.getElementById('nomor').value;
            var giat = $('#giat').combogrid('getValue');
            var nmgiat = document.getElementById('nmgiat').value;
            var rek = $('#rek').combogrid('getValue');
            var nosp2d = $('#sp2d').combogrid('getValue');
            var nmrek = document.getElementById('nmrek').value;
            var crek = $('#rek').combogrid('grid'); // get datagrid object
            var csumber = document.getElementById('ketsdana1').value;
            var grek = crek.datagrid('getSelected'); // get the selected row
            var canggaran = number_format(grek.ang_ubah, 2, '.', ',');
            var csp2d = number_format(grek.sp2d, 2, '.', ',');
            var clalu = number_format(grek.lalu, 2, '.', ',');
            //var sisa    = angka(document.getElementById('sisa').value);  
            var sisa_panjar = angka(document.getElementById('sisa_panjar').value);
            var nil = document.getElementById('nilai').value;
            var nilai_rek = angka(document.getElementById('nilai').value);
            var sisa_murni = angka(document.getElementById('sisa_murni').value);
            // var sisa_semp   = angka(document.getElementById('sisa_semp').value);                
            // var sisa_ubah   = angka(document.getElementById('sisa_ubah').value);                
            var status_ang = document.getElementById('status_ang').value;
            var total1 = angka(document.getElementById('total1').value);
            var tot_sisa_spd = angka(document.getElementById('tot_sisa').value);
            var jenis = document.getElementById('beban').value;
            var nmrek = document.getElementById('nmrek').value;
            // SD
            var sisa_sd = angka(document.getElementById('sisa_sd').value);
            // var sisa_semp_sd= angka(document.getElementById('sisa_semp_sd').value);
            // var sisa_ubah_sd= angka(document.getElementById('sisa_ubah_sd').value);


            //tot = sisa_panjar - angka(nil);
            //tot1 = sisa - angka(nil);
            var akumulasi = total1 + nilai_rek;


            if (nosp2d == '') {
                alert("No sp2d kosong");
            }
            if (csumber == '') {
                alert("Sumber Dana kosong");
            }
            if (nosp2d == 'undefined') {
                alert("No sp2d kosong");
            }
            if (crek == '') {
                alert("Rekening kosong");
            }
            if (giat == '') {
                alert("Kegiatan kosong");
            }
            if (nmrek == '') {
                alert('Pilih rekening Dahulu');
                exit();
            }

            if (nil == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                exit();
            }
            if (status_ang == '') {
                alert('Pilih Tanggal Dahulu');
                exit();
            }
            if (nilai_rek > sisa_panjar) {
                alert('Transaksi melebihi Sisa Panjar');
                exit();
            }

            if (akumulasi > sisa_panjar) {
                alert('Total Transaksi melebihi Sisa Panjar');
                exit();
            }


            /*sumber dana*/
            if ((status_ang == 'Penetapan') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penetapan Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan I') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Penyempurnaan I Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan II') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Penyempurnaan II Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan III') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Penyempurnaan III Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan IV') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Penyempurnaan IV Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan V') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Penyempurnaan V Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Perubahan') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Perubahan II') && (nilai_rek > sisa_sd)) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan II Sumber Dana...!!!, Cek Lagi...!!!');
                exit();
            }
            
            
            // if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_ubah_sd)){
            //      alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan Sumber Dana...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_semp_sd)){
            //      alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan Sumber Dana...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyusunan')&&(nilai_rek > sisa_sd)){
            //      alert('Nilai Melebihi Sisa Anggaran Penyusunan Sumber Dana...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }            


           
            if ((status_ang == 'Penetapan') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Penetapan...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan I') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan I...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan II') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan III') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan III...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan IV') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan IV...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Penyempurnaan V') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan V...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Perubahan') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'Perubahan II') && (nilai_rek > sisa_murni)) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan II...!!!, Cek Lagi...!!!');
                exit();
            }
            

            if ((jenis == '1') && (akumulasi > tot_sisa_spd)) {
                alert('Total Transaksi melebihi Sisa SPD');
                exit();
            }
            if (giat == '') {
                alert('Pilih Kegiatan Dahulu');
                exit();
            }
            if (nmgiat == '') {
                alert('Pilih Kegiatan Dahulu');
                exit();
            }

            $('#dg1').edatagrid('appendRow', {
                no_bukti: no,
                no_sp2d: nosp2d,
                kd_sub_kegiatan: giat,
                nm_sub_kegiatan: nmgiat,
                kd_rek6: rek,
                nm_rek6: nmrek,
                nilai: nil,
                lalu: clalu,
                sumber: csumber,
                sp2d: csp2d,
                anggaran: canggaran
            });
            $('#dg2').edatagrid('appendRow', {
                no_bukti: no,
                no_sp2d: nosp2d,
                kd_sub_kegiatan: giat,
                nm_sub_kegiatan: nmgiat,
                kd_rek6: rek,
                nm_rek6: nmrek,
                nilai: nil,
                lalu: clalu,
                sumber: csumber,
                sp2d: csp2d,
                anggaran: canggaran
            });
            kosong2();
            total = angka(document.getElementById('total1').value) + nilai_rek;
            $('#total1').attr('value', number_format(total, 2, '.', ','));
            $('#total').attr('value', number_format(total, 2, '.', ','));
        }

        function validate_rekening() {

            $('#dgpajak').datagrid('selectAll');
            var rows = $('#dgpajak').datagrid('getSelections');

            frek = '';
            rek5 = '';
            for (var p = 0; p < rows.length; p++) {
                rek5 = rows[p].kd_rek5;
                if (p > 0) {
                    frek = frek + ',' + rek5;
                } else {
                    frek = rek5;
                }
            }

            $(function() {
                $('#rekpajak').combogrid({
                    panelWidth: 700,
                    idField: 'kd_rek6',
                    textField: 'kd_rek6',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/tukd/rek_pot_ar',
                    queryParams: ({
                        kdrek: frek
                    }),
                    columns: [
                        [{
                                field: 'kd_rek6',
                                title: 'Kode Rekening',
                                width: 100
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 700
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        $("#nmrekpajak").attr("value", rowData.nm_rek6.toUpperCase());
                    }
                });
            });
            $('#dgpajak').datagrid('unselectAll');
        }

        function tambah() {
            var nor = document.getElementById('nomor').value;
            var tot = document.getElementById('total').value;
            var kd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');


            $('#notagih').combogrid({
                panelWidth: 420,
                idField: 'no_tagih',
                textField: 'no_tagih',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_no_penagihan',
                queryParams: ({
                    kd: kode
                }),
                columns: [
                    [{
                            field: 'no_tagih',
                            title: 'No Penagihan',
                            width: 140
                        },
                        {
                            field: 'tgl_tagih',
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


            $('#dg2').edatagrid('reload');
            $('#total1').attr('value', tot);
            $('#giat').combogrid('setValue', '');
            $('#sp2d').combogrid('setValue', '');
            $('#rek').combogrid('setValue', '');
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
            $('#giat').combogrid('setValue', '');
            $('#sp2d').combogrid('setValue', '');
            $('#rek').combogrid('setValue', '');
            // $('#sisasp2d').attr('value','0');
            // $('#sisa').attr('value','0');
            //$('#nilai').attr('value','0');
            $('#nilai').attr('value', '0');
            $('#nmgiat').attr('value', '');
            $('#sisa_murni').attr('value', '0');
            // $('#sisa_semp').attr('value','0');
            // $('#sisa_ubah').attr('value','0');
            $('#ang_murni').attr('value', '0');
            $('#ang_semp').attr('value', '0');
            $('#ang_ubah').attr('value', '0');
            $('#lalu_murni').attr('value', '0');
            $('#lalu_semp').attr('value', '0');
            $('#lalu_ubah').attr('value', '0');
        }

        function load_total_spd() {
            var giat = $('#giat').combogrid('getValue');
            var kode = document.getElementById('skpd').value;
            var koderek = $('#rek').combogrid('getValue');
            var tgl_cek = $('#tanggal').datebox('getValue');

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
                            load_total_trans();
                        });
                    }
                });
            });
        }

        function cek_status_angkas() {
            var tgl_cek = $('#tanggal').datebox('getValue');
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/tukd/cek_status_angkas',
                data: ({
                    tgl_cek: tgl_cek
                }),
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#status_angkas").attr("value", data.status);
                }
            });
        }

        function load_total_angkas() {
            var giat = $('#giat').combogrid('getValue');
            var kode = document.getElementById('skpd').value;
            var koderek = $('#rek').combogrid('getValue');
            // var nosp2d = $('#sp2d').combogrid('getValue');
            var ctgl = $('#tanggal').datebox('getValue');
            var sts_angkas = document.getElementById('status_angkas').value;
            $(function() {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: ({
                        giat: giat,
                        kode: kode,
                        koderek: koderek,
                        tgl: ctgl,
                        stt: sts_angkas
                    }),
                    url: '<?php echo base_url(); ?>index.php/cms/load_total_angkas',
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#total_angkas").attr("Value", n['total_angkas']);
                            var n_totalangkas = n['total_angkas'];
                            load_angkas_lalu();
                        });
                    }
                });

            });
        }


        // 

        function load_angkas_lalu() {
            var no_simpan = document.getElementById('no_simpan').value;
            var giat = $('#giat').combogrid('getValue');
            var kode = document.getElementById('skpd').value;
            var koderek = $('#rek').combogrid('getValue');
            var nosp2d = $('#sp2d').combogrid('getValue');
            var ctglkas = $('#tgl_kas').datebox('getValue');
            var jnsbeban = document.getElementById('beban').value;
            var cno = document.getElementById('nomor').value;
            $(function() {

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: ({
                        giat: giat,
                        kode: kode,
                        kdrek6: koderek,
                        beban: jnsbeban,
                        sp2d: nosp2d,
                        no_bukti: cno,
                        tgl: ctglkas,
                        no_simpan: no_simpan
                    }),
                    url: '<?php echo base_url(); ?>index.php/cms/load_total_trans_spd',
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#nilai_angkas_lalu").attr("Value", n['total']);
                            $("#tot_trans").attr("value", n['total']);
                            total_sisa_spd();
                            var n_angkaslalu = n['total'];
                            var total_angkas = document.getElementById('total_angkas').value;
                            var n_sisaangkas = angka(total_angkas) - angka(n_angkaslalu);
                            $("#nilai_sisa_angkas").attr("Value", number_format(n_sisaangkas, 2, '.', ','));
                        });
                    }
                });

            });
        }
        // function load_total_trans(){
        //   var no_simpan = document.getElementById('no_simpan').value;  
        //   var giat = $('#giat').combogrid('getValue');
        //   var kode = document.getElementById('skpd').value;
        //   var koderek = $('#rek').combogrid('getValue') ;
        //   var nosp2d = $('#sp2d').combogrid('getValue');
        //   var jnsbeban = document.getElementById('beban').value;
        //   $(function(){      
        //        $.ajax({
        //           type: 'POST',
        //           url:"<?php echo base_url(); ?>index.php/cms/load_total_trans_spd",
        //           dataType:"json",
        //     data: ({giat:giat,kode:kode,kdrek6:koderek,beban:jnsbeban,sp2d:nosp2d,no_simpan:no_simpan}),
        //           success:function(data){ 
        //               $.each(data, function(i,n){
        //                   $("#tot_trans").attr("value",n['total']);
        //                   total_sisa_spd();
        //               });
        //           }
        //        });
        //       });
        //   }

        function total_sisa_spd() {
            var tot_spd = angka(document.getElementById('tot_spd').value);
            var tot_trans = angka(document.getElementById('tot_trans').value);
            totsisa = tot_spd - tot_trans;

            $('#tot_sisa').attr('value', number_format(totsisa, 2, '.', ','));

        }

        function keluar() {
            //var sisa    = angka(document.getElementById('sisa').value);  
            var sisa_panjar = angka(document.getElementById('sisa_panjar').value);
            var total_trans = angka(document.getElementById('total1').value);
            selisih = total_trans - sisa_panjar;
            // selisih1 = total_trans - sisa;


            if (total_trans > sisa_panjar) {
                alert('Total Transaksi Melebihi Sisa Panjar');
                exit();
                $("#dialog-modal").dialog('close');
                $('#dg2').edatagrid('reload');
                kosong2();
            } else {
                $("#dialog-modal").dialog('close');
                $('#dg2').edatagrid('reload');
                kosong2();
            }
        }

        function hapus_giat() {
            tot3 = 0;
            var tot = angka(document.getElementById('total').value);
            tot3 = tot - nilx;
            $('#total').attr('value', number_format(tot3, 2, '.', ','));
            $('#dg1').datagrid('deleteRow', idx);
        }

        function hapus() {
            var cnomor = document.getElementById('no_simpan').value;
            var urll = '<?php echo base_url(); ?>index.php/panjar/hapus_transout_panjar';
            var tny = confirm('Yakin Ingin Menghapus Data, Nomor Bukti : ' + cnomor);
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

        function simpan_transout() {
            var cno = document.getElementById('nomor').value;
            var ctgl = $('#tanggal').datebox('getValue');
            var cnokas = document.getElementById('no_kas').value;
            var ctglkas = $('#tgl_kas').datebox('getValue');
            var no_simpan = document.getElementById('no_simpan').value;
            var no_panjar = document.getElementById('nopanjar').value;
            var cnokaspot = document.getElementById('nokas').value;
            var cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            var cnmskpd = document.getElementById('nmskpd').value;
            var cket = document.getElementById('keterangan').value;
            var cstatus = document.getElementById('status').checked;
            var jns = document.getElementById('beban').value;
            var cjenis_bayar = document.getElementById('jns_tunai').value;

            var csql = '';
            if (cstatus == false) {
                cstatus = 0;
            } else {
                cstatus = 1;
            }

            var ctagih = $('#notagih').combogrid('getValue');
            var ctgltagih = document.getElementById('tgltagih').value;
            var ctotal = angka(document.getElementById('total').value);

            alert('Nomor Panjar :' + no_panjar);
            if (cnokas == '') {
                alert('Nomor Kas Tidak Boleh Kosong');
                exit();
            }
            if (cno == '') {
                alert('Nomor Bukti Tidak Boleh Kosong');
                exit();
            }
            if (ctgl == '') {
                alert('Tanggal Bukti Tidak Boleh Kosong');
                exit();
            }
            var tahun_input = ctgl.substring(0, 4);
            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                exit();
            }
            if (cskpd == '') {
                alert('Kode SKPD Tidak Boleh Kosong');
                exit();
            }

            if (cjenis_bayar == '') {
                alert('Jenis Pembayaran Tidak Boleh Kosong');
                exit();
            }

            var ctot_det = 0;
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');
            for (var p = 0; p < rows.length; p++) {
                cnilai = angka(rows[p].nilai);
                ctot_det = ctot_det + cnilai;
            }
            if (ctotal != ctot_det) {
                alert('Nilai Rincian tidak sama dengan Total, Silakan Refresh kembali halaman ini!');
                exit();
            }

            if (ctot_det == 0) {
                alert('Rincian Tidak ada rekening!');
                exit();
            }

            if (status_transaksi == 'tambah') {
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: cno,
                            tabel: 'trhtransout',
                            field: 'no_bukti'
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

                                //------- mulai

                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        data: ({
                                            tabel: 'trhtransout',
                                            no: cno,
                                            tgl: ctgl,
                                            nokas: cnokas,
                                            tglkas: ctglkas,
                                            skpd: cskpd,
                                            nmskpd: cnmskpd,
                                            beban: jns,
                                            ket: cket,
                                            status: cstatus,
                                            notagih: ctagih,
                                            tgltagih: ctgltagih,
                                            total: ctotal,
                                            cpay: cjenis_bayar,
                                            nokas_pot: cnokaspot,
                                            nopanjar: no_panjar
                                        }),
                                        url: '<?php echo base_url(); ?>/index.php/panjar/simpan_transout_panjar',
                                        success: function(data) {
                                            status = data.pesan;
                                        }
                                    });
                                });

                                if (status == '0') {
                                    alert('Gagal Simpan...!!');
                                    exit();
                                }

                                if (status != '0') {
                                    $('#dg1').datagrid('selectAll');
                                    var rows = $('#dg1').datagrid('getSelections');
                                    for (var p = 0; p < rows.length; p++) {
                                        cnobukti = cno;
                                        cnosp2d = rows[p].no_sp2d;
                                        ckdgiat = rows[p].kd_sub_kegiatan;
                                        cnmgiat = rows[p].nm_sub_kegiatan;
                                        crek = rows[p].kd_rek6;
                                        cnmrek = rows[p].nm_rek6;
                                        cnilai = angka(rows[p].nilai);
                                        csumber = angka(rows[p].sumber);

                                        if (p > 0) {
                                            csql = csql + "," + "('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + csumber + "')";
                                        } else {
                                            csql = "values('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + csumber + "')";
                                        }
                                    }

                                    $(document).ready(function() {
                                        // alert(csql);
                                        $.ajax({
                                            type: "POST",
                                            dataType: 'json',
                                            data: ({
                                                tabel: 'trdtransout',
                                                no: cno,
                                                sql: csql,
                                                beban: jns,
                                                status: cstatus
                                            }),
                                            url: '<?php echo base_url(); ?>/index.php/panjar/simpan_transout_panjar',
                                            success: function(data) {
                                                status = data.pesan;
                                                if (status == '1') {
                                                    alert('Data Berhasil Tersimpan...!!!');
                                                    status_transaksi = 'edit;'
                                                    $("#no_simpan").attr("value", cno);
                                                    var abc = '1';
                                                } else {
                                                    alert('Data Gagal Tersimpan...!!!');
                                                }
                                            }
                                        });
                                    });
                                }

                                //---------
                            }
                        }
                    });
                });



            } else {
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: cno,
                            tabel: 'trhtransout',
                            field: 'no_bukti'
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

                                //-----
                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        data: ({
                                            tabel: 'trhtransout',
                                            no: cno,
                                            tgl: ctgl,
                                            nokas: cnokas,
                                            tglkas: ctglkas,
                                            skpd: cskpd,
                                            nmskpd: cnmskpd,
                                            beban: jns,
                                            ket: cket,
                                            status: cstatus,
                                            notagih: ctagih,
                                            tgltagih: ctgltagih,
                                            total: ctotal,
                                            cpay: cjenis_bayar,
                                            nokas_pot: cnokaspot,
                                            no_bku: no_simpan,
                                            nopanjar: no_panjar
                                        }),
                                        url: '<?php echo base_url(); ?>/index.php/panjar/simpan_transout_panjar_edit',
                                        success: function(data) {
                                            status = data.pesan;
                                        }
                                    });
                                });

                                if (status == '0') {
                                    alert('Gagal Simpan...!!');
                                    exit();
                                }

                                if (status != '0') {
                                    $('#dg1').datagrid('selectAll');
                                    var rows = $('#dg1').datagrid('getSelections');
                                    for (var p = 0; p < rows.length; p++) {
                                        cnobukti = cno;
                                        cno_sp2d = rows[p].no_sp2d;
                                        ckdgiat = rows[p].kd_sub_kegiatan;
                                        cnmgiat = rows[p].nm_sub_kegiatan;
                                        crek = rows[p].kd_rek6;
                                        cnmrek = rows[p].nm_rek6;
                                        csumber = rows[p].sumber;
                                        cnilai = angka(rows[p].nilai);

                                        if (p > 0) {
                                            csql = csql + "," + "('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + csumber + "')";
                                        } else {
                                            csql = "values('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "','" + csumber + "')";
                                        }
                                    }
                                    $(document).ready(function() {
                                        // alert(csql);
                                        $.ajax({
                                            type: "POST",
                                            dataType: 'json',
                                            data: ({
                                                tabel: 'trdtransout',
                                                no: cno,
                                                sql: csql,
                                                skpd: cskpd,
                                                beban: jns,
                                                status: cstatus,
                                                no_bku: no_simpan
                                            }),
                                            url: '<?php echo base_url(); ?>/index.php/tukd/simpan_transout_panjar_edit',
                                            success: function(data) {
                                                status = data.pesan;
                                                if (status == '1') {
                                                    alert('Data Berhasil Tersimpan...!!!');
                                                    status_transaksi = 'edit;'
                                                    $("#no_simpan").attr("value", cno);
                                                    var abc = '1';
                                                } else {
                                                    alert('Data Gagal Tersimpan...!!!');
                                                }
                                            }
                                        });
                                    });
                                }


                                //----
                            }
                        }
                    });
                });
            }
            //End of Function
        }

        function simpan_potongan() {

            var cnokas = document.getElementById('nokas').value;
            var ctglkas = $('#tglkas').datebox('getValue');
            var cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            var cnmskpd = document.getElementById('nmskpd').value;
            var ckete = document.getElementById('kete').value;



            var ctotal = angka(document.getElementById('totalrekpajak').value);
            // alert(cnokas+'/'+ctglkas+'/'+cskpd+'/'+cnmskpd+'/'+ckete+'/'+ctotal)


            if (cnokas == '') {
                alert('Nomor Kas Tidak Boleh Kosong');
                exit();
            }
            if (ctglkas == '') {
                alert('Tanggal Bukti Tidak Boleh Kosong');
                exit();
            }


            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    data: ({
                        tabel: 'trhtrmpot',
                        no: cnokas,
                        tgl: ctglkas,
                        skpd: cskpd,
                        nmskpd: cnmskpd,
                        ket: ckete,
                        total: ctotal
                    }),
                    url: '<?php echo base_url(); ?>/index.php/tukd/simpan_potongan',
                    success: function(data) {
                        status = data.pesan;
                    }
                });
            });

            if (status == '0') {
                alert('Gagal Simpan...!!');
                exit();
            }

            if (status != '0') {
                $('#dgpajak').datagrid('selectAll');
                var rows = $('#dgpajak').datagrid('getSelections');
                for (var q = 0; q < rows.length; q++) {
                    cnobukti = cnokas;
                    crek = rows[q].kd_rek6;
                    cnmrek = rows[q].nm_rek6;
                    cnilai = angka(rows[q].nilai);

                    if (q > 0) {
                        csql = csql + "," + "('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "')";
                    } else {
                        csql = "values('" + cnobukti + "','" + crek + "','" + cnmrek + "','" + cnilai + "')";
                    }
                }
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            tabel: 'trdtrmpot',
                            no: cnokas,
                            sql: csql
                        }),
                        url: '<?php echo base_url(); ?>/index.php/tukd/simpan_potongan',
                        success: function(data) {
                            status = data.pesan;
                            if (status == '1') {
                                alert('Data Berhasil Tersimpan...!!!');
                            } else {
                                alert('Data Gagal Tersimpan...!!!');
                            }
                        }
                    });
                });
            }
        }

        function sisa_bayar() {
            var sisa = angka(document.getElementById('sisa').value);
            var nil = angka(document.getElementById('nilai').value);
            var sisasp2d = angka(document.getElementById('sisasp2d').value);
            var tot = 0;
            //alert(sisa+'/'+nil);        
            tot = sisa - nil;
            if (nil > sisasp2d) {
                alert('Nilai Melebihi Sisa Sp2d');
                exit();
            } else {
                if (tot < 0) {
                    alert('Nilai Melebihi Sisa');
                    exit();
                }
            }

        }


        function runEffect() {
            var selectedEffect = 'blind';
            var options = {};
            $("#tagih").toggle(selectedEffect, options, 500);
            $("#notagih").combogrid("setValue", '');
            $("#tgltagih").attr("value", '');
            //$("#skpd").combogrid("setValue",'');
            $("#keterangan").attr("value", '');
            $("#beban").attr("value", '');
            load_detail_baru();

        };



        function hit_lalu() {
            var cgiat = $('#giat').combogrid('getValue');
            var csp2d = $('#sp2d').combogrid('getValue');
            var crek = $('#rek').combogrid('getValue');
            var cno = document.getElementById('nomor').value;
            var ctgl = $('#tanggal').combogrid('getValue');
            var ckode = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            var jns = document.getElementById('jenis').value;
            // alert(cgiat+'/'+csp2d+'/'+crek+'/'+cno+'/'+ctgl+'/'+ckode);        
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/tukd/out_lalu',
                data: ({
                    giat: cgiat,
                    sp2d: csp2d,
                    rek: crek,
                    nomor: cno,
                    tgl: ctgl,
                    skpd: ckode,
                    jenis: jns
                }),
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, n) {
                        clalu = n['lalu'];
                        $('#sisa').attr('value', clalu);
                    });
                }
            });
        }

        function hit_lalu2(cgiat, nosp2d, rek5, no, ctgl, cskpd) {
            // alert(cgiat+'/'+nosp2d+'/'+rek5+'/'+no+'/'+ctgl+'/'+cskpd);
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/tukd/out_lalu',
                data: ({
                    giat: cgiat,
                    sp2d: nosp2d,
                    rek: rek5,
                    nomor: no,
                    tgl: ctgl,
                    skpd: cskpd
                }),
                dataType: "json",
                success: function(data) {
                    //clalu =data;
                    $.each(data, function(i, n) {
                        clalu = n['lalu'];
                    });
                }
            });
            //alert(clalu);
            return clalu;
        }

        function cek() {
            var lcno = document.getElementById('nomor').value;
            if (lcno != '') {
                section3();
                $("#totalrekpajak").attr("value", 0);
                $("#nilairekpajak").attr("value", 0);
                tampil_potongan();
                load_sum_pot();
                $("#rekpajak").combogrid("setValue", '');
                $("#nmrekpajak").attr("value", '');

            } else {
                alert('Nomor  Tidak Boleh kosong')
                document.getElementById('no_spm').focus();
                exit();
            }
        }

        function tampil_potongan() {

            var vnospm = document.getElementById('nomor').value;
            alert(vnospm);
            $(function() {
                $('#dgpajak').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/tukd/losd_pot_a',
                    queryParams: ({
                        spm: vnospm
                    }),
                    idField: 'id',
                    //toolbar       : "#toolbar",              
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "false",
                    singleSelect: "true",
                    nowrap: "true",
                    columns: [
                        [{
                                field: 'id',
                                title: 'id',
                                width: 100,
                                align: 'left',
                                hidden: 'true'
                            },
                            {
                                field: 'kd_rek6',
                                title: 'Rekening',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 317
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                width: 250,
                                align: "right"
                            },
                            {
                                field: 'hapus',
                                title: 'Hapus',
                                width: 100,
                                align: "center",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_pot();" />';
                                }
                            }
                        ]
                    ]
                });
            });
        }

        function load_sum_pot() {
            var spm = document.getElementById('no_spm').value;
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        spm: spm
                    }),
                    url: "<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            //$("#totalrekpajak").attr("value",number_format(n['rektotal'],2,'.',','));
                            $("#totalrekpajak").attr("value", n['rektotal']);
                        });
                    }
                });
            });
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

        function reload_data() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/panjar/load_transout_panjar',
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
                            field: 'ck',
                            title: '',
                            checkbox: 'true',
                            width: 40
                        },
                        {
                            field: 'no_bukti',
                            title: 'Nomor Bukti',
                            width: 50
                        },
                        {
                            field: 'tgl_bukti',
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
                        },
                        {
                            field: 'simbollpj',
                            title: 'LPJ',
                            width: 10,
                            align: "left"
                        },
                        {
                            field: 'simbolspj',
                            title: 'SPJ',
                            width: 10,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomor = rowData.no_bukti;
                    tgl = rowData.tgl_bukti;
                    nokas = rowData.no_kas;
                    tglkas = rowData.tgl_kas;
                    nokas_pot = rowData.no_kas_pot;
                    tglpot = rowData.tgl_pot;
                    ketpot = rowData.ketpot;
                    kode = rowData.kd_skpd;
                    nama = rowData.nm_skpd;
                    ket = rowData.ket;
                    jns = rowData.jns_beban;
                    tot = rowData.total;
                    notagih = rowData.no_tagih;
                    tgltagih = rowData.tgl_tagih;
                    nopanjar = rowData.no_panjar;
                    ststagih = rowData.sts_tagih;
                    vpay = rowData.pay;
                    statlpj = rowData.ketlpj;
                    statspj = rowData.ketspj;

                    get(nokas, tglkas, nomor, tgl, kode, nama, ket, jns, tot, notagih, tgltagih, ststagih, vpay, nokas_pot, tglpot, ketpot, nopanjar, statlpj, statspj);

                    if (ststagih != '1') {
                        load_detail();
                    }
                },
                onDblClickRow: function(rowIndex, rowData) {
                    section2();
                }
            });
        }

        function cek_status_spj() {
            var tgl_cek = $('#tanggal').datebox('getValue');
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/tukd/cek_status_spj',
                data: ({
                    tgl_cek: tgl_cek
                }),
                //queryParams    : ({ tgl_cek:tgl_cek }),
                type: "POST",
                dataType: "json",
                success: function(data) {
                    status_spj = data.status_spj;
                    if (status_spj == 1) {
                        $('#save').linkbutton('disable');
                        alert("SPJ sudah disahkan pada bulan ini.")
                    } else {
                        $('#save').linkbutton('enable');
                    }
                }
            });
        }

        function tombollpj(statlpj, statspj) {
            if ((statlpj == 1) || (statspj == 1) || (statlpj == 2)) {
                $('#save').linkbutton('disable');
                $('#del').linkbutton('disable');
                $("#tanggal").datebox('disable');
            } else {
                $('#save').linkbutton('enable');
                $('#del').linkbutton('enable');
                $("#tanggal").datebox('enable');
            }
        }
    </script>


</head>

<body>



    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1">List Pembayaran Transaksi Panjar
                    <br />
                    <!-- <font color="#F60B0B">Pengajuan Transaksi dengan sumber dana DAK FISIK untuk sementara dihentikan. Trims</font> -->
                </a></h3>
            <div>
                <p align="right">
                    <button class="button" plain="true" onclick="javascript:section2();kosong();datagrid_kosong();"><i class="fa fa-tambah"></i> Tambah</button>
                    <button class="button-biru" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i> </button>
                    <input type="text" value="" id="txtcari" />
                <table id="dg" title="List Pembayaran Transaksi" style="width:870px;height:600px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2">PEMBAYARAN TRANSAKSI</a></h3>
            <div style="height: 350px;">
                <p>
                <div id="demo"></div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td colspan="5"><input hidden type="checkbox" id="status" onclick="javascript:runEffect();" />
                            <div id="tagih">
                                <table>
                                    <tr>
                                        <td>No.Penagihan</td>
                                        <td><input type="text" id="notagih" /></td>

                                        <td>Tgl Penagihan</td>
                                        <td><input type="text" id="tgltagih" style="width: 140px;" /></td>
                                    </tr>
                                </table>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="border-bottom: double 1px red;"><i>No. BKU<i></td>
                        <td style="border-bottom: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true" ; /></td>
                        <td style="border-bottom: double 1px red;">&nbsp;&nbsp;</td>
                        <td style="border-bottom: double 1px red;" colspan="2"><i>Tidak Perlu diisi atau di Edit</i></td>

                    </tr>

                    <tr>
                        <td>No. Kas</td>
                        <td><input type="text" id="no_kas" style="width: 200px;" onclick="javascript:select();" /></td>
                        <td>&nbsp;&nbsp;</td>
                        <td>Tanggal Kas</td>
                        <td><input type="text" id="tgl_kas" style="width: 140px;" /></td>
                    </tr>

                    <tr>
                        <td>No. Bukti</td>
                        <td><input type="text" id="nomor" style="width: 200px;" onclick="javascript:select();" /></td>
                        <td>&nbsp;&nbsp;</td>
                        <td>Tanggal Bukti</td>
                        <td><input type="text" id="tanggal" style="width: 140px;" /></td>
                    </tr>
                    <tr>
                        <td>No Panjar</td>
                        <td><input id="nopanjar" name="nopanjar" style="border:0;width: 140px;" readonly="true" /></td>
                    </tr>
                    <tr>
                        <td>S K P D</td>
                        <td><input id="skpd" name="skpd" style="width: 140px;" /></td>
                        <td></td>
                        <td>Nama SKPD :</td>
                        <td><input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true" /></td>
                    </tr>
                    <tr>
                        <td>Jenis Beban</td>
                        <td colspan="4"><?php echo $this->tukd_model->combo_beban('beban'); ?> </td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="4"><textarea id="keterangan" style="width: 650px; height: 40px;"></textarea></td>
                    </tr>

                    <tr>

                        <td>Pembayaran</td>
                        <td>
                            <select name="jns_tunai" id="jns_tunai">
                                <option value="TUNAI">TUNAI</option>
                                <option value="BANK">BANK</option>
                            </select>
                        </td>

                        <td colspan="3" align="right">
                            <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();datagrid_kosong();">Tambah</a>-->
                            <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_transout();">Simpan</a>
                            <!-- <a id="poto" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:cek();">Potongan</a>-->
                            <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
                            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                        </td>
                    </tr>
                </table>
                <table id="dg1" title="Rekening" style="width:870px;height:450px;">
                </table>
                <div id="toolbar" align="right">
                    <a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Kegiatan</a>
                    <!--<input type="checkbox" id="semua" value="1" /><a onclick="">Semua Kegiatan</a>-->
                    <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_giat();">Hapus Kegiatan</a>

                </div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td align="right">Total : <input type="text" id="total" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                    </tr>
                </table>


                </p>
            </div>
            <h3><a href="#" id="section3">Potongan</a></h3>

            <div>
                <fieldset>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                    <fieldset>
                        <table border='0' style="font-size:11px">
                            <tr>
                                <td>NO KAS</td>
                                <td>:</td>
                                <td><input type="text" id="nokas" name="nokas" style="width:200px;" /></td>
                                <td>Tanggal :<input type="text" id="tglkas" name="tglkas" style="width:100px;" /></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td colspan='2'><input type="text" id="kete" name="kete" style="width:400px;" /></td>
                            </tr>
                            <tr>
                                <td>Rekening Potongan</td>
                                <td>:</td>
                                <td><input type="text" id="rekpajak" name="rekpajak" style="width:200px;" /></td>
                                <td><input type="text" id="nmrekpajak" name="nmrekpajak" style="width:400px;border:0px;" /></td>
                            </tr>
                            <tr>
                                <td align="left">Nilai</td>
                                <td>:</td>
                                <td><input type="text" id="nilairekpajak" name="nilairekpajak" style="width:200px;text-align:right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="center">
                                    <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:append_save_pot();">Tambah</a>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    &nbsp;&nbsp;
                    <table border='0' style="font-size:11px;width:850px;height:30px;">
                        <tr>
                            <td colspan="3" align="center">
                                <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_potongan()();">Simpan</a>
                                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                            </td>

                        </tr>
                        <tr>
                            <td width='50%'></td>
                            <td width='20%' align="right">Total</td>
                            <td width='30%'><input type="text" id="totalrekpajak" name="totalrekpajak" style="width:250px;text-align:right;" /></td>
                        </tr>
                    </table>
                    <table id="dgpajak" title="List Potongan" style="width:850px;height:300px;">
                    </table>


                </fieldset>
            </div>

        </div>
    </div>



    <div id="dialog-modal" title="Input Kegiatan">
        <p class="validateTips">Semua Inputan Harus Di Isi.</p>
        <fieldset>
            <table border="0">
                <tr>
                    <td>No Panjar</td>
                    <td>:</td>
                    <td><input id="no_panjar" name="no_panjar" style="width: 157px;" /></td>
                </tr>
                <tr>
                    <td>Kode Kegiatan</td>
                    <td>:</td>
                    <td><input id="giat" name="giat" style="width: 157px;" /></td>
                    <td>Nm. Kegiatan</td>
                    <td>:</td>
                    <td colspan="5"><input type="nmgiat" id="nmgiat" readonly="true" style="border:0;width: 450px;" /></td>

                </tr>
                <tr>
                    <td>No SP2D</td>
                    <td>:</td>
                    <td><input id="sp2d" name="sp2d" style="width: 157px;" /></td>

                </tr>
                <tr>
                    <td>Kode Rekening</td>
                    <td>:</td>
                    <td><input id="rek" name="rek" style="width: 157px;" /></td>
                    <td>Nama Rekening</td>
                    <td>:</td>
                    <td colspan="5"><input type="text" id="nmrek" readonly="true" style="border:0;width: 450px;" /></td>
                </tr>
                <tr>
                    <td>Sumber Dana</td>
                    <td>:</td>
                    <td colspan="7"><input id="ketsdana1" name="ketsdana1" style="width: 134px;" /><input id="sumber_dn" name="sumber_dn" style="width: 19px;" />&nbsp;&nbsp;&nbsp;<input id="nmsumberdana" readonly="true" name="nmsumberdana" style="width: 350px;" /></td>
                </tr>
                <tr>
                    <td bgcolor="#99FF99">Anggaran / SP2D</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="ang_murni" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                    <td bgcolor="#99FF99">Lalu</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="lalu_murni" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                    <td bgcolor="#99FF99">Sisa</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="sisa_murni" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                </tr>
                <!-- <tr>
            <td bgcolor="#e374ff">Penyempurnaan / SP2D &nbsp; &nbsp; &nbsp;</td>
            <td bgcolor="#e374ff">:</td>
            <td bgcolor="#e374ff"><input type="text" id="ang_semp" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#e374ff">Lalu</td>
            <td bgcolor="#e374ff">:</td>
            <td bgcolor="#e374ff"><input type="text" id="lalu_semp" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#e374ff">Sisa</td>
            <td bgcolor="#e374ff">:</td>
            <td bgcolor="#e374ff"><input type="text" id="sisa_semp" readonly="true" style="text-align:right;border:0;" class="dua"/></td>            
        </tr> -->
                <!-- <tr>
            <td bgcolor="#51ffd4">Perubahan / SP2D </td>
            <td bgcolor="#51ffd4">:</td>
            <td bgcolor="#51ffd4"><input type="text" id="ang_ubah" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#51ffd4">Lalu</td>
            <td bgcolor="#51ffd4">:</td>
            <td bgcolor="#51ffd4"><input type="text" id="lalu_ubah" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#51ffd4">Sisa</td>
            <td bgcolor="#51ffd4">:</td>
            <td bgcolor="#51ffd4"><input type="text" id="sisa_ubah" readonly="true" style="text-align:right;border:0;" class="dua"/></td>            
        </tr> -->
                <tr>
                    <td bgcolor="#FFA07A">Anggaran SumberDana/SP2D</td>
                    <td bgcolor="#FFA07A">:</td>
                    <td bgcolor="#FFA07A"><input type="text" id="ang_sd" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                    <td bgcolor="#FFA07A">Lalu</td>
                    <td bgcolor="#FFA07A">:</td>
                    <td bgcolor="#FFA07A"><input type="text" id="lalu_sd" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                    <td bgcolor="#FFA07A">Sisa</td>
                    <td bgcolor="#FFA07A">:</td>
                    <td bgcolor="#FFA07A"><input type="text" id="sisa_sd" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                </tr>
                <!-- <tr>
            <td bgcolor="#FFA07A">Penyempurnaan S.Dana/SP2D &nbsp; &nbsp; &nbsp;</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="ang_semp_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#FFA07A">Lalu</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="lalu_semp_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#FFA07A">Sisa</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="sisa_semp_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td>            
        </tr> -->
                <!-- <tr>
            <td bgcolor="#FFA07A">Perubahan SumberDana/SP2D </td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="ang_ubah_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#FFA07A">Lalu</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="lalu_ubah_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td> 
            <td bgcolor="#FFA07A">Sisa</td>
            <td bgcolor="#FFA07A">:</td>
            <td bgcolor="#FFA07A"><input type="text" id="sisa_ubah_sd" readonly="true" style="text-align:right;border:0;" class="dua"/></td>            
        </tr> -->
                <tr id="hidethis">
                    <td bgcolor="#FFD700">SPD </td>
                    <td bgcolor="#FFD700">:</td>
                    <td bgcolor="#FFD700"><input type="text" id="tot_spd" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                    <td bgcolor="#FFD700">Realisasi</td>
                    <td bgcolor="#FFD700">:</td>
                    <td bgcolor="#FFD700"><input type="text" id="tot_trans" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                    <td bgcolor="#FFD700">Sisa</td>
                    <td bgcolor="#FFD700">:</td>
                    <td bgcolor="#FFD700"><input type="text" id="tot_sisa" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                </tr>
                <tr>
                    <td bgcolor="#FF0000">Angkas </td>
                    <td bgcolor="#FF0000">:</td>
                    <td bgcolor="#FF0000"><input type="text" id="total_angkas" readonly="true" class="dua" style="text-align:right;border:0;" /></td>
                    <td bgcolor="#FF0000">Realisasi</td>
                    <td bgcolor="#FF0000">:</td>
                    <td bgcolor="#FF0000"><input type="text" id="nilai_angkas_lalu" readonly="true" class="dua" style="text-align:right;border:0;" /></td>
                    <td bgcolor="#FF0000">Sisa</td>
                    <td bgcolor="#FF0000">:</td>
                    <td bgcolor="#FF0000"><input type="text" id="nilai_sisa_angkas" readonly="true" class="dua" style="text-align:right;border:0;" /></td>
                </tr>
                <tr>
                    <td>Status Anggaran</td>
                    <td>:</td>
                    <td><input type="text" id="status_ang" readonly="true" style="text-align:left;border:0;" class="dua" /></td>
                </tr>

                <tr>
                    <td>Status Angkas</td>
                    <td>:</td>
                    <td><input type="text" id="status_angkas" readonly="true" style="text-align:left;border:0;" class="dua" /></td>
                </tr>
                <!--
    <tr>
            <td >Sisa Anggaran</td>
            <td>:</td>
            <td><input type="text" id="sisa" readonly="true" style="text-align:right;border:0;width: 150px;"/></td>            
        </tr>
    -->
                <tr>
                    <td>Total Panjar</td>
                    <td>:</td>
                    <td><input type="text" id="total_panjar" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                <tr>
                    <td>Transaksi Panjar</td>
                    <td>:</td>
                    <td><input type="text" id="panjar_lalu" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                <tr>
                    <td>Kembali Panjar</td>
                    <td>:</td>
                    <td><input type="text" id="kembali_panjar" readonly="true" style="text-align:right;border:0;" class="dua" /></td>
                <tr>
                <tr>
                    <td>Sisa Panjar</td>
                    <td>:</td>
                    <td><input type="text" id="sisa_panjar" readonly="true" style="text-align:right;border:0;" class="dua" /></td>

                </tr>


                <tr>
                    <td>Nilai</td>
                    <td>:</td>
                    <td><input type="text" id="nilai" style="text-align: right;width: 152px;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:sisa_bayar();" /></td>

                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="right">
                <tr>
                    <td>Total</td>
                    <td>:</td>
                    <td><input type="text" id="total1" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;" /></td>
                </tr>
            </table>
            <table id="dg2" title="Input Rekening" style="width:1030px;height:270px;">
            </table>

        </fieldset>
    </div>


</body>

</html>