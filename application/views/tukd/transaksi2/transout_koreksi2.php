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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
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


        .satu {
            width: 197px;
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
                height: 750,
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
                rowStyler: function(index, row) {
                    if ((row.ketlpj == 1) || (row.ketspj == 1)) {
                        return 'color:#02087f;';
                    }
                },
                url: '<?php echo base_url(); ?>/index.php/koreksi/load_transout_koreksi2',
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
                            width: 20
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
                            width: 90,
                            align: "left"
                        },
                        {
                            field: 'ket',
                            title: 'Keterangan',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'ketlpj',
                            title: 'LPJ',
                            width: 10,
                            align: "left"
                        },
                        {
                            field: 'ketspj',
                            title: 'SPJ',
                            width: 10,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomor = rowData.no_bukti;
                    tgl = rowData.tgl_bukti;
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
                    ststagih = rowData.sts_tagih;
                    vpay = rowData.pay;
                    statlpj = rowData.ketlpj;
                    statspj = rowData.ketspj;

                    get(nomor, tgl, kode, nama, ket, jns, tot, notagih, tgltagih, ststagih, vpay, nokas_pot, tglpot, ketpot, statlpj, statspj);
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
                fitColumns: false,
                singleSelect: "true",
                autoRowHeight: "false",
                fit: "true",
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
                            title: 'No SP2D',
                            hidden: "true"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'sub Kegiatan',
                            width: 142
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            hidden: "true"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 62
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 380,
                            align: "left"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 120,
                            align: "right"
                        },
                        {
                            field: 'sumber',
                            title: 'SUMBER',
                            width: 100,
                            align: 'right'
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

            $('#dgpajak').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/tukd/pot',
                idField: 'id',
                //toolbar        : "#toolbar",              
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
                fitColumns: false,
                singleSelect: "true",
                autoRowHeight: "true",
                fit: "true",
                loadMsg: "Tunggu Sebentar....!!",
                nowrap: "true",

                onSelect: function(rowIndex, rowData) {
                    cidx = rowIndex;
                },
                columns: [
                    [{
                            field: 'hapus',
                            title: 'Hapus',
                            width: 80,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                            }
                        },
                        {
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true",
                            width: 100
                        },
                        {
                            field: 'no_sp2d',
                            title: 'No SP2D',
                            width: 130
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'sub Kegiatan',
                            width: 150
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            hidden: "true",
                            width: 100
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 80,
                            align: 'left'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            align: "left",
                            width: 200
                        },
                        {
                            field: 'nilai',
                            title: 'Rupiah',
                            align: "right",
                            width: 100
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber',
                            width: 100,
                            align: 'right'
                        },
                        {
                            field: 'lalu',
                            title: 'Sudah Dibayarkan',
                            align: "right",
                            width: 100
                        },
                        {
                            field: 'sp2d',
                            title: 'SP2D Non UP',
                            align: "right",
                            width: 100
                        },
                        {
                            field: 'anggaran',
                            title: 'Anggaran',
                            align: "right",
                            width: 100
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
                    cek_status_ang();
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

            $('#tgl_koreksi').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {
                    // cek_status_ang();
                    cek_status_angkas();
                    // cek_status_spj();
                }
            });


            $("#sdana1").combogrid({
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

            $('#dg3').edatagrid({
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                loadMsg: "Tunggu Sebentar....!!",
                nowrap: "true",
                columns: [
                    [{
                            field: 'sumber',
                            title: 'Sumber Dana',
                            hidden: "true"
                        },
                        {
                            field: 'anggaran',
                            title: 'S. Dana',
                            width: 98
                        },
                        {
                            field: 'sub kegiatan',
                            title: 'sub kegiatan',
                            width: 120
                        },
                        {
                            field: 'kd_rek6',
                            title: 'rekening',
                            width: 98
                        }
                    ]
                ]
            });


            // $('#tgltagih').datebox({  
            //                required:true,
            //                formatter :function(date){
            //                  var y = date.getFullYear();
            //                  var m = date.getMonth()+1;
            //                  var d = date.getDate();    
            //                  return y+'-'+m+'-'+d;
            //                }
            //         });
            //                    
            //       $('#skpd').combogrid({  
            //           panelWidth:700,  
            //           idField:'kd_skpd',  
            //           textField:'kd_skpd',  
            //           mode:'remote',                      
            //           url:'<?php echo base_url(); ?>index.php/tukd/skpd',  
            //           columns:[[  
            //               {field:'kd_skpd',title:'Kode SKPD',width:100},  
            //               {field:'nm_skpd',title:'Nama SKPD',width:700}    
            //           ]],  
            //           onSelect:function(rowIndex,rowData){
            //               kode = rowData.kd_skpd;               
            //               $("#nmskpd").attr("value",rowData.nm_skpd);
            //               $('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/koreksi/load_trskpd',queryParams:({kd:kode,jenis:'52'})});                 
            //           } 
            //       });              
            //alert(kode);                                 
            $('#giat').combogrid({
                panelWidth: 700,
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/koreksi/load_trskpd_koreksi',
                queryParams: ({
                    kd: kode,
                    jenis: jenis
                }),
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
                    idxGiat = rowIndex;
                    giat = rowData.kd_sub_kegiatan;
                    var jnsbeban = document.getElementById('beban').value;
                    var nomor = document.getElementById('nomor').value;
                    var kode = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
                    rekkosong();
                    sp2dkosong();
                    $("#nmgiat").attr("value", rowData.nm_sub_kegiatan);
                    $("#giat_koreksi").combogrid("setValue", giat);
                    $("#nmgiat_koreksi").attr("value", rowData.nm_sub_kegiatan);
                    $("#sp2d").combogrid({
                        url: '<?php echo base_url(); ?>index.php/koreksi/load_sp2d_koreksi',
                        queryParams: ({
                            jenis: jnsbeban,
                            giat: giat,
                            kd: kode,
                            bukti: nomor
                        })
                    });
                    //$('#sp2d').combogrid('setValue','');
                    //load_total_spd();
                    //load_total_trans();
                }
            });

            $('#giat_koreksi').combogrid({
                panelWidth: 700,
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/koreksi/load_trskpd',
                queryParams: ({
                    kd: kode,
                    jenis: jenis
                }),
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
                    idxGiat = rowIndex;
                    giat_koreksi = rowData.kd_sub_kegiatan;
                    var jnsbeban = document.getElementById('beban').value;
                    var nomor = document.getElementById('nomor').value;
                    var kode = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
                    $("#nmgiat_koreksi").attr("value", rowData.nm_sub_kegiatan);
                    $("#sp2d_koreksi").combogrid({
                        url: '<?php echo base_url(); ?>index.php/koreksi/load_sp2d_koreksi',
                        queryParams: ({
                            jenis: jnsbeban,
                            giat: giat,
                            kd: kode,
                            bukti: nomor
                        })
                    });
                    sp2dkoreksikosong();
                    rekkoreksikosong();
                    sdana1kosong();
                    //$('#sp2d').combogrid('setValue','');
                    //load_total_spd();
                    //load_total_trans();
                }
            });

            $('#rekpajak').combogrid({
                panelWidth: 700,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/rek_pot',
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
                ],
                onSelect: function(rowIndex, rowData) {
                    var ststagih = '1';
                    $("#tgltagih").attr("value", rowData.tgl_tagih);
                    $("#skpd").attr("value", rowData.kd_skpd);
                    $("#keterangan").attr("value", rowData.ket);
                    $("#beban").attr("value", '1');
                    $("#total").attr("value", number_format(rowData.nil, 2, '.', ','));
                    //load_detail_tagih();
                    tombol(ststagih);

                }
            });

            $('#sp2d').combogrid({
                panelWidth: 330,
                idField: 'no_sp2d',
                textField: 'no_sp2d',
                mode: 'local',
                columns: [
                    [{
                        field: 'no_sp2d',
                        title: 'Nomor Sp2d',
                        width: 330
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    var nosp2d = rowData.no_sp2d;
                    //var nilsp2d = rowData.nilai;
                    //var tglsp2d = rowData.tgl_sp2d;              
                    var nobukti = document.getElementById('nomor').value;
                    // var tglbukti = document.getElementById('tanggal').value;
                    var jnsbeban = document.getElementById('beban').value;
                    //var sisa = angka(rowData.sisa);              
                    //alert(nosp2d+'/'+nilsp2d+'/'+tglsp2d+'/'+nobukti+'/'+jnsbeban+'/'+sisa);
                    var kode = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
                    var giat = $('#giat').combogrid('getValue');
                    // $('#giat').combogrid('setValue',nosp2d);
                    $("#sp2d_koreksi").combogrid("setValue", nosp2d);
                    var jj = 0;
                    var frek = '';
                    //$('#rek').combogrid('setValue','');

                    $('#dg1').datagrid('selectAll');
                    var rows = $('#dg1').datagrid('getSelections');
                    for (var p = 0; p < rows.length; p++) {
                        cgiat = rows[p].kd_sub_kegiatan;
                        rek5 = rows[p].kd_rek6;
                        nil = angka(rows[p].nilai);
                        // sisa    = sisa - nil;                   
                        if (cgiat == giat) {
                            if (jj > 0) {
                                frek = frek + ',' + rek5;
                            } else {
                                frek = rek5;
                            }
                            jj++;
                        }
                    }
                    // $('#sisasp2d').attr('value',number_format(sisa,2,'.',','));
                    $('#dg1').edatagrid('unselectAll');
                    $('#rek').combogrid({
                        url: '<?php echo base_url(); ?>index.php/koreksi/load_rek_koreksi',
                        queryParams: ({
                            sp2d: nosp2d,
                            no: nobukti,
                            jenis: jnsbeban,
                            giat: giat,
                            kd: kode,
                            rek: frek
                        })
                    });
                    //load_sisa_bank();
                    //load_sisa_tunai();
                    //load_sisa_pot_ls();
                    //total_sisa_pot();
                }
            });


            $('#sp2d_koreksi').combogrid({
                panelWidth: 330,
                idField: 'no_sp2d',
                textField: 'no_sp2d',
                mode: 'local',
                columns: [
                    [{
                        field: 'no_sp2d',
                        title: 'Nomor Sp2d',
                        width: 330
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    var nosp2d = rowData.no_sp2d;
                    //var nilsp2d = rowData.nilai;
                    //var tglsp2d = rowData.tgl_sp2d;              
                    var nobukti = document.getElementById('nomor').value;
                    // var tglbukti = document.getElementById('tanggal').value;
                    var jnsbeban = document.getElementById('beban').value;
                    //var sisa = angka(rowData.sisa);              
                    //alert(nosp2d+'/'+nilsp2d+'/'+tglsp2d+'/'+nobukti+'/'+jnsbeban+'/'+sisa);
                    var kode = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
                    var giat = $('#giat_koreksi').combogrid('getValue');
                    var jj = 0;
                    var frek = '';
                    //$('#rek').combogrid('setValue','');

                    $('#dg1').datagrid('selectAll');
                    var rows = $('#dg1').datagrid('getSelections');
                    for (var p = 0; p < rows.length; p++) {
                        cgiat = rows[p].kd_sub_kegiatan;
                        rek5 = rows[p].kd_rek6;
                        nil = angka(rows[p].nilai);
                        //sisa    = sisa - nil;                   
                        if (cgiat == giat) {
                            if (jj > 0) {
                                frek = frek + ',' + rek5;
                            } else {
                                frek = rek5;
                            }
                            jj++;
                        }
                    }
                    // $('#sisasp2d').attr('value',number_format(sisa,2,'.',','));
                    $('#dg1').edatagrid('unselectAll');
                    $('#rek_koreksi').combogrid({
                        url: '<?php echo base_url(); ?>index.php/koreksi/load_rek',
                        queryParams: ({
                            sp2d: nosp2d,
                            no: nobukti,
                            jenis: jnsbeban,
                            giat: giat,
                            kd: kode,
                            rek: frek
                        })
                    });
                    rekkoreksikosong();
                    //load_sisa_bank();
                    //load_sisa_tunai();
                    //load_sisa_pot_ls();
                    //total_sisa_pot();
                }
            });


            $('#rek').combogrid({
                panelWidth: 650,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                mode: 'remote',
                columns: [
                    [{
                            field: 'no_bku',
                            title: 'No BKU',
                            width: 50,
                            align: 'center'
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 90,
                            align: 'center'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 200
                        },
                        {
                            field: 'nilai',
                            title: 'nilai',
                            width: 120,
                            align: 'right'
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $('#nmrek').attr('value', rowData.nm_rek6);
                    var nobkus = rowData.no_bku;
                    // $('#nil_pad_a').attr('value',number_format(rowData.nil_pad,2,'.',','));
                    // $('#nil_dak_a').attr('value',number_format(rowData.nil_dak,2,'.',','));
                    // $('#nil_daknf_a').attr('value',number_format(rowData.nil_daknf,2,'.',','));
                    // $('#nil_dau_a').attr('value',number_format(rowData.nil_dau,2,'.',','));
                    // $('#nil_dbhp_a').attr('value',number_format(rowData.nil_dbhp,2,'.',','));
                    // $('#nil_did_a').attr('value',number_format(rowData.nil_did,2,'.',','));


                    var ctgl = $('#tgl_koreksi').datebox('getValue');
                    var kode = document.getElementById('skpd').value;
                    var kd_giat = $('#giat').combogrid('getValue');
                    var rek = rowData.kd_rek6;
                    var cnosp2d = $('#sp2d').combogrid('getValue');
                    $('#rek_koreksi').combogrid('setValue', rek);
                    $('#s_dana').combogrid({
                        panelWidth: 500,
                        url: '<?php echo base_url(); ?>/index.php/koreksi/load_sdana',
                        queryParams: ({
                            nosp2d: cnosp2d,
                            skpd: kode,
                            giat: kd_giat,
                            rek: rek,
                            nobkus: nobkus
                        }),
                        idField: 'sumber',
                        textField: 'sumber',
                        mode: 'remote',
                        fitColumns: true,
                        columns: [
                            [{
                                    field: 'sumber',
                                    title: 'Kode',
                                    width: 70
                                },
                                {
                                    field: 'nmsumber',
                                    title: 'Sumber Dana',
                                    align: 'left',
                                    width: 330
                                },
                                {
                                    field: 'nilai',
                                    title: 'Nilai',
                                    align: 'left',
                                    width: 100
                                }
                            ]
                        ],
                        onSelect: function(rowIndex, rowData) {
                            sumber = rowData.sumber;
                            nmsumber = rowData.nmsumber;
                            nilai = rowData.nilai;

                            $("#nms_dana").attr("value", rowData.nmsumber);
                            $('#nilai').attr('value', number_format(rowData.nilai, 2, '.', ','));
                        }
                    });
                    /*
                $('#dg3').datagrid('selectAll');
                var AAA = $('#dg3').datagrid('getData');
                var sdana1 = $('#sdana1').combogrid('grid');    
                sdana1.datagrid('loadData', AAA);                
            
                alert(AAA); 
               
                
                
                $("#sdana1").combogrid({
                    panelWidth:300,
                    idField   :'sumber',
                    textField :'sumber',
                    mode      :'remote',
                    url       : '<?php echo base_url(); ?>index.php/tukd/ambil_sdana',
                    queryParams:({tgl:ctgl,skpd:kode,giat:kd_giat,kdrek5:rek}),
                    columns   : [[
                    {field:'sumber',title:'Sumber Dana',width:100},
                    {field:'anggaran',title:'S.Dana Penyusunan',width:120},
                    {field:'anggaran_semp',title:'S.Dana Penyempurnaan',width:120},
                    {field:'anggaran_ubah',title:'S.Dana Perubahan',width:120}
                    ]],
                    onSelect :function(rowIndex,rowData){
                        selectRow = rowData.sumber;   
                        artChanged = true;/*
                        $('#sisa_sumber').attr('value',number_format(0,2,'.',','));
                        $('#sisa_sumber_semp').attr('value',number_format(0,2,'.',','));
                        $('#sisa_sumber_ubah').attr('value',number_format(0,2,'.',','));        
                        load_total_sdana(kd_giat,rek,selectRow);
                        $("#tot_sumber").attr("value",rowData.anggaran);
                        $("#tot_sumber_semp").attr("value",rowData.anggaran_semp);
                        $("#tot_sumber_ubah").attr("value",rowData.anggaran_ubah);
                    },
                    onChange: function(rowIndex,rowData){
                          artChanged = true;     
                          selectRow = rowData.sumber;                                      
                    },
                    onLoadSuccess : function (data) {  
                        var t = $(this).combogrid('getValue');
                        if (artChanged) {  
                        if (selectRow == null || t != selectRow) { 
                            $(this).combogrid('setValue', '');
                            
                        } 
                        }  
                        
                        artChanged = false;  
                        selectRow = null; 
                    
                    },
                    onHidePanel: function () {  
                        var t = $(this).combogrid('getValue');  
                        if (artChanged) {  
                        if (selectRow == null || t != selectRow) {
                            $(this).combogrid('setValue', '');  
                            
                        } 
                        }  
                        artChanged = false;  
                        selectRow = null;  
                    }                                  
                });      */
                }
            });

            function loadsumber(rek_koreksi, kd_giat) {
                // alert("hakam");

                $('#dg3').datagrid('selectAll');
                var sdana = $('#dg3').datagrid('getData');
                var jdl = '';
                var selectRow = '';

                $("#sdana1").combogrid({
                    panelWidth: 300,
                    idField: 'sumber',
                    textField: 'sumber',
                    mode: 'remote',
                    loadData: sdana,
                    columns: [
                        [{
                                field: 'sumber',
                                title: 'Sumber Dana',
                                width: 100
                            },
                            {
                                field: 'anggaran',
                                title: 'S.Dana',
                                width: 120
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {

                        selectRow = rowData.sumber;
                        // jdl = '';
                        $('#nmsdana1').attr('value', rowData.nmsumber);
                        $('#sdana1_susun').attr('value', rowData.anggaran);

                        load_total_sdana(kd_giat, rek_koreksi, selectRow);




                        coreksi = $('#rek_koreksi').combogrid('getValue').trim();
                        cek_rek5 = cek_rekkoreksi(coreksi);
                        if (cek_rek5 == 0) {
                            $("#rek_koreksi").combogrid("clear");
                        }

                    }
                });

                // $("#sdana2").combogrid({
                //     panelWidth:300,
                //     idField   :'sumber',
                //     textField :'sumber',
                //     mode      :'remote',
                //     loadData  : sdana,
                //     columns   : [[
                //     {field:'sumber',title:'Sumber Dana',width:100},
                //     {field:'anggaran',title:'S.Dana Penyusunan',width:120},
                //     {field:'anggaran_semp',title:'S.Dana Penyempurnaan',width:120},
                //     {field:'anggaran_ubah',title:'S.Dana Perubahan',width:120}
                //     ]],
                //     onSelect :function(rowIndex,rowData){
                //         selectRow = rowData.sumber;  
                //         jdl = '#sdana2_real';
                //         $('#sdana2_susun').attr('value',rowData.anggaran);
                //         $('#sdana2_sempurna').attr('value',rowData.anggaran_semp);
                //         $('#sdana2_ubah').attr('value',rowData.anggaran_ubah);
                //         load_total_sdana(kd_giat,rek_koreksi,selectRow,jdl);

                //     }                   
                // });   



                var sdana1 = $('#sdana1').combogrid('grid');
                sdana1.datagrid('loadData', sdana);
                // var sdana2 = $('#sdana2').combogrid('grid');   
                // sdana2.datagrid('loadData', sdana);          
                // var sdana3 = $('#sdana3').combogrid('grid');   
                // sdana3.datagrid('loadData', sdana);          
            }


            function k() {
                $("#rek_koreksi").combogrid("enable");
            }

            $('#rek_koreksi').combogrid({
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
                            title: 'Lalu',
                            width: 120,
                            align: 'right'
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $('#nmrek_koreksi').attr('value', rowData.nm_rek6);
                    document.getElementById('nilai_koreksi').select();

                    var ctgl = $('#tgl_koreksi').datebox('getValue');
                    var kode = document.getElementById('skpd').value;
                    var kd_giat = $('#giat_koreksi').combogrid('getValue');
                    var cnosp2d = $('#sp2d_koreksi').combogrid('getValue');
                    // alert(cnosp2d);
                    var rek = rowData.kd_rek6;
                    var jenis = document.getElementById('beban').value;
                    var anggaran = rowData.anggaran;
                    // alert();
                    var lalu = rowData.lalu;
                    var sp2d = rowData.sp2d;
                    if (jenis == '1') {
                        $('#ang').attr('value', number_format(anggaran, 2, '.', ','));
                        //    $('#ang_semp').attr('value',number_format(anggaran_semp,2,'.',','));
                        //    $('#ang_ubah').attr('value',number_format(anggaran_ubah,2,'.',','));
                    } else {
                        $('#ang').attr('value', number_format(sp2d, 2, '.', ','));
                        // $('#ang_semp').attr('value',number_format(sp2d,2,'.',','));
                        // $('#ang_ubah').attr('value',number_format(sp2d,2,'.',','));                   
                    }
                    $('#lalu').attr('value', number_format(lalu, 2, '.', ','));
                    $('#sdana1_real').attr('value', number_format(lalu, 2, '.', ','));
                    var ang_ini = angka(document.getElementById('ang').value);
                    var txtstatuss = document.getElementById('txtstatus').value;
                    // alert('hitung sisa SD');
                    // if (txtstatuss == 'Penyusunan') {
                    //     var ang_ini = angka(document.getElementById('ang').value);
                    // }
                    // else if (txtstatuss=='Penyempurnaan'){
                    //   var ang_ini = angka(document.getElementById('ang_semp').value);
                    // }
                    // else{
                    //   var ang_ini = angka(document.getElementById('ang_ubah').value);
                    // } 
                    $('#sisa_lalu').attr('value', number_format(ang_ini - lalu, 2, '.', ','));
                    $('#sisa_sdana1_real').attr('value', number_format(ang_ini - lalu, 2, '.', ','));


                    sdana1kosong();
                    load_total_angkas();
                    load_total_spd();
                    // sdana2kosong();
                    // sdana3kosong();                
                    $('#dg3').datagrid('loadData', []);
                    $('#dg3').edatagrid({

                        url: '<?php echo base_url(); ?>index.php/koreksi/ambil_sdana',
                        queryParams: ({
                            tgl: ctgl,
                            skpd: kode,
                            giat: kd_giat,
                            kdrek5: rek,
                            nosp2d: cnosp2d,
                            jnsbeban: jenis
                        }),
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
                                    field: 'sumber',
                                    title: 'Sumber Dana',
                                    width: 98
                                },
                                {
                                    field: 'anggaran',
                                    title: 'S. Dana',
                                    width: 98
                                },
                                {
                                    field: 'sub_kegiatan',
                                    title: 'sub kegiatan',
                                    width: 120
                                },
                                {
                                    field: 'kd_rek6',
                                    title: 'rekening',
                                    width: 98
                                }
                            ]
                        ],
                        onLoadSuccess: function(data) {
                            loadsumber(rek, kd_giat);

                        }
                    });

                }
            });


        });



        function load_total_sdana(giat, kode_rek, sumber, jdl) {
            var kode = document.getElementById('skpd').value;
            $(jdl).attr("value", '9,999,999,999,999.00');
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tukd/load_total_sdana",
                    dataType: "json",
                    data: ({
                        giat: giat,
                        kode: kode,
                        sumber: sumber,
                        kode_rek: kode_rek
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            var real_sd = angka(n['total_trans_sumber']);

                            // $(jdl).attr("value", n['total_trans_sumber']);

                            var txtstatuss = document.getElementById('txtstatus').value;
                            var sd_ini = angka(document.getElementById('sdana1_susun').value);
                            // alert(txtstatuss);
                            // alert(real_sd);
                            // alert(jdl);
                            // $('#sisa_sdana1_real').attr('value', number_format(sd_ini - real_sd, 2, '.', ','));


                        });
                        //sisa_sumber();

                        // ---------------



                        // ---------------

                    }
                });
            });

        }

        function rekkosong() {
            $("#rek").combogrid("clear");
            var rek = $('#rek').combogrid('grid');
            rek.datagrid('loadData', []);
        }

        function rekkoreksikosong() {
            $("#rek_koreksi").combogrid("clear");
            var rek_koreksi = $('#rek_koreksi').combogrid('grid');
            rek_koreksi.datagrid('loadData', []);
        }

        function sp2dkoreksikosong() {
            $("#sp2d_koreksi").combogrid("clear");
            var sp2d_koreksi = $('#sp2d_koreksi').combogrid('grid');
            sp2d_koreksi.datagrid('loadData', []);
        }

        function sp2dkosong() {
            $("#sp2d").combogrid("clear");
            var sp2d = $('#sp2d').combogrid('grid');
            sp2d.datagrid('loadData', []);
        }

        function sdana1kosong() {
            $("#sdana1").combogrid("clear");
            var sdana1 = $('#sdana1').combogrid('grid');
            sdana1.datagrid('loadData', []);
        }

        // function sdana2kosong(){
        //     $("#sdana2").combogrid("clear");
        //     var sdana2 = $('#sdana2').combogrid('grid');    
        //     sdana2.datagrid('loadData', []);                        
        // }



        function load_sisa_bank() {
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tukd/load_sisa_bank",
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

        function load_total_spd() {
            var giat = $('#giat').combogrid('getValue');
            var kode = document.getElementById('skpd').value;

            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tukd/load_total_spd",
                    dataType: "json",
                    data: ({
                        giat: giat,
                        kode: kode
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#tot_spd").attr("value", n['total_spd']);
                        });
                    }
                });
            });
        }

        function load_total_trans() {
            var no_simpan = document.getElementById('no_simpan').value;
            var giat = $('#giat').combogrid('getValue');
            var kode = document.getElementById('skpd').value;

            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tukd/load_total_trans",
                    dataType: "json",
                    data: ({
                        giat: giat,
                        kode: kode,
                        no_simpan: no_simpan
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#tot_trans").attr("value", n['total']);
                        });
                    }
                });
            });
        }

        function load_sisa_tunai() {
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tukd/load_sisa_tunai",
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

        function load_sisa_pot_ls() {
            var sp2d_pot = $('#sp2d').combogrid('getValue');
            var ckas = angka(document.getElementById('sisa_tunai').value);

            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/tukd/load_sisa_pot_ls",
                    dataType: "json",
                    data: ({
                        sp2d: sp2d_pot
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#pot_ls").attr("value", n['sisa']);
                        });
                    }
                });
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
                    //$("#status_ang").attr("value",data.status_ang);
                }
            });
        }

        function total_sisa_pot() {
            var ckas1 = angka(document.getElementById('sisa_tunai').value);
            var cpot = angka(document.getElementById('pot_ls').value);
            $('#total_sisa').attr('value', number_format(ckas1 + cpot, 2, '.', ','));
        }

        function total_sisa_spd() {
            var tot_spd = angka(document.getElementById('tot_spd').value);
            var tot_trans = angka(document.getElementById('tot_trans').value);
            totsisa = tot_spd - tot_trans;

            $('#tot_sisa').attr('value', number_format(totsisa, 2, '.', ','));

        }

        function get_skpd() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#skpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    $("#txtstatus").attr("value", data.jns_ang);
                    kode = data.kd_skpd;
                    statu = data.jns_ang;
                    if (statu == 'M') {
                        $("#txtstatus_hidden").attr("value", "Penetapan");
                    } else if (statu == 'P1') {
                        $("#txtstatus_hidden").attr("value", "Penyempurnaan I");
                    } else if (statu == 'P2') {
                        $("#txtstatus_hidden").attr("value", "Penyempurnaan II");
                    } else if (statu == 'P3') {
                        $("#txtstatus_hidden").attr("value", "Penyempurnaan III");
                    } else if (statu == 'P4') {
                        $("#txtstatus_hidden").attr("value", "Penyempurnaan IV");
                    } else if (statu == 'P5') {
                        $("#txtstatus_hidden").attr("value", "Penyempurnaan V");
                    } else if (statu == 'U1') {
                        $("#txtstatus_hidden").attr("value", "Perubahan I");
                    }

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

        function kegia() {
            var jns1 = document.getElementById('beban').value;
            $('#giat').combogrid({
                url: '<?php echo base_url(); ?>index.php/koreksi/load_trskpd_koreksi',
                queryParams: ({
                    kd: kode,
                    jenis: jns1
                })
            });
            $('#giat_koreksi').combogrid({
                url: '<?php echo base_url(); ?>index.php/koreksi/load_trskpd',
                queryParams: ({
                    kd: kode,
                    jenis: jns1
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

                var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_pot_delete';
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
                kosong3();
            }

        }

        function load_detail() {
            var kk = nomor;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = document.getElementById("skpd").value; //$('#skpd').combogrid('getValue');             
            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/koreksi/load_dtransout',
                    data: ({
                        no: kk
                    }),
                    dataType: "json",
                    success: function(data) {
                        $('#dg1').datagrid('loadData', []);
                        $('#dg1').edatagrid('reload');
                        $.each(data, function(i, n) {
                            no = n['no_bukti'];
                            nosp2d = n['no_sp2d'];
                            giat = n['kd_sub_kegiatan'];
                            nmgiat = n['nm_sub_kegiatan'];
                            rek5 = n['kd_rek6'];
                            nmrek5 = n['nm_rek6'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            clalu = number_format(n['lalu'], 2, '.', ',');
                            csp2d = number_format(n['sp2d'], 2, '.', ',');
                            sumber = n['sumber'];


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
                                sumber: sumber,
                                sp2d: csp2d,
                                anggaran: canggaran
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
                    url: '<?php echo base_url(); ?>/index.php/tukd/load_dpot',
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
                                field: 'nm_rek',
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
                            nmrek5 = n['nm_rek6'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            clalu = number_format(n['lalu'], 2, '.', ',');
                            csp2d = number_format(n['sp2d'], 2, '.', ',');
                            canggaran = number_format(n['anggaran'], 2, '.', ',');
                            nil_pad = number_format(n['nil_pad'], 2, '.', ',');
                            nil_dak = number_format(n['nil_dak'], 2, '.', ',');
                            nil_daknf = number_format(n['nil_daknf'], 2, '.', ',');
                            nil_dau = number_format(n['nil_dau'], 2, '.', ',');
                            nil_dbhp = number_format(n['nil_dbhp'], 2, '.', ',');
                            nil_did = number_format(n['nil_did'], 2, '.', ',');

                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                no_sp2d: nosp2d,
                                kd_sub_kegiatan: giat,
                                nm_sub_kegiatan: nmgiat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                                pad: nil_pad,
                                dak: nil_dak,
                                daknf: nil_daknf,
                                dau: nil_dau,
                                dbhp: nil_dbhp,
                                did: nil_did,
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
                            nmrek5 = n['nm_rek6'];
                            nil = number_format(n['nilai'], 2, '.', ',');
                            clalu = number_format(n['lalu'], 2, '.', ',');
                            csp2d = number_format(n['sp2d'], 2, '.', ',');
                            canggaran = number_format(n['anggaran'], 2, '.', ',');
                            nil_pad = number_format(n['nil_pad'], 2, '.', ',');
                            nil_dak = number_format(n['nil_dak'], 2, '.', ',');
                            nil_daknf = number_format(n['nil_daknf'], 2, '.', ',');
                            nil_dau = number_format(n['nil_dau'], 2, '.', ',');
                            nil_dbhp = number_format(n['nil_dbhp'], 2, '.', ',');
                            nil_did = number_format(n['nil_did'], 2, '.', ',');

                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: no,
                                no_sp2d: nosp2d,
                                kd_sub_kegiatan: giat,
                                nm_sub_kegiatan: nmgiat,
                                kd_rek6: rek5,
                                nm_rek6: nmrek5,
                                nilai: nil,
                                pad: nil_pad,
                                dak: nil_dak,
                                daknf: nil_daknf,
                                dau: nil_dau,
                                dbhp: nil_dbhp,
                                did: nil_did,
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
                            title: 'Sub Kegiatan',
                            width: 150
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            hidden: "true"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 100
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 300,
                            align: "left"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 120,
                            align: "right"
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber',
                            width: 100,
                            align: 'right'
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
                canggaran = rows[p].anggaran;
                nil_pad = rows[p].pad;
                nil_dak = rows[p].dak;
                nil_daknf = rows[p].daknf;
                nil_dau = rows[p].dau;
                nil_dbhp = rows[p].dbhp;
                nil_did = rows[p].did;

                $('#dg2').edatagrid('appendRow', {
                    no_bukti: no,
                    no_sp2d: nosp2d,
                    kd_sub_kegiatan: giat,
                    nm_sub_kegiatan: nmgiat,
                    kd_rek6: rek5,
                    nm_rek6: nmrek5,
                    nilai: nil,
                    pad: nil_pad,
                    dak: nil_dak,
                    daknf: nil_daknf,
                    dau: nil_dau,
                    dbhp: nil_dbhp,
                    did: nil_did,
                    lalu: lal,
                    sp2d: csp2d,
                    anggaran: canggaran
                });
            }
            $('#dg1').edatagrid('unselectAll');
        }

        function set_grid2() {
            $('#dg2').edatagrid({
                rownumbers: "true",
                fitColumns: false,
                singleSelect: "true",
                autoRowHeight: "true",
                fit: "true",
                loadMsg: "Tunggu Sebentar....!!",
                nowrap: "true",
                columns: [
                    [{
                            field: 'hapus',
                            title: 'Hapus',
                            width: 40,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                            }
                        },
                        {
                            field: 'no_bukti',
                            title: 'No Bukti',
                            hidden: "true",
                            width: 10
                        },
                        {
                            field: 'no_sp2d',
                            title: 'No SP2D',
                            width: 130
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'sub Kegiatan',
                            width: 150
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            hidden: "true",
                            width: 100
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 80,
                            align: 'left'
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            align: "left",
                            width: 200
                        },
                        {
                            field: 'nilai',
                            title: 'Rupiah',
                            align: "right",
                            width: 100
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber',
                            width: 100,
                            align: 'right'
                        },
                        {
                            field: 'lalu',
                            title: 'Sudah Dibayarkan',
                            align: "right",
                            width: 100
                        },
                        {
                            field: 'sp2d',
                            title: 'SP2D Non UP',
                            align: "right",
                            width: 100
                        },
                        {
                            field: 'anggaran',
                            title: 'Anggaran',
                            align: "right",
                            width: 100
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

        function get(nomor, tgl, kode, nama, ket, jns, tot, notagih, tgltagih, ststagih, vpay, nokas_pot, tglpot, ketpot, statlpj, statspj) {
            $("#nomor").attr("value", nomor);
            $("#no_simpan").attr("value", nomor);
            $("#tanggal").datebox("setValue", tgl);
            $("#nokas").attr("value", nokas_pot);
            $("#tglkas").datebox("setValue", tglpot);
            $("#tgl_koreksi").datebox("setValue", tgltagih);
            $("#kete").attr("value", ketpot);
            $("#keterangan").attr("value", ket);
            $("#beban").attr("value", jns);
            $("#total").attr("value", number_format(tot, 2, '.', ','));
            $("#notagih").combogrid("setValue", notagih);
            $("#jns_tunai").attr("value", vpay);
            $("#status").attr("checked", false);
            status_transaksi = 'edit';
            if (ststagih == 1) {
                $("#status").attr("checked", true);
                $("#tagih").show();
                //load_detail_tagih();
            } else {
                $("#status").attr("checked", false);
                $("#tagih").hide();
            }
            tombollpj(statlpj, statspj)
            tombol(ststagih);
        }


        function tombollpj(statlpj, statspj) {
            if ((statlpj == 1) || (statspj == 1)) {
                // $('#save').linkbutton('disable');
                document.getElementById("save").disabled = true;
                // $('#del').linkbutton('disable');
                document.getElementById("del").disabled = true;
            } else {
                // $('#save').linkbutton('enable');
                document.getElementById("save").disabled = false;
                // $('#del').linkbutton('enable');
                document.getElementById("del").disabled = false;

            }
        }

        function tombol(st) {
            if (st == '1') {
                // $('#tambah').linkbutton('disable');
                // $('#hapus').linkbutton('disable');
                document.getElementById("tambah").disabled = true;
                document.getElementById("hapus").disabled = true;
            } else {
                // $('#tambah').linkbutton('enable');
                // $('#hapus').linkbutton('enable');
                document.getElementById("tambah").disabled = false;
                document.getElementById("hapus").disabled = false;

            }
        }

        function tombolnew() {

            // $('#save').linkbutton('enable');
            document.getElementById("save").disabled = false;
            document.getElementById("del").disabled = false;
            // $('#del').linkbutton('enable');

        }


        function kosong() {
            cdate = '<?php echo date("Y-m-d"); ?>';
            $("#nomor").attr("value", '');
            $("#no_simpan").attr("value", '');
            $("#tanggal").datebox("setValue", '');
            //$("#skpd").combogrid("setValue",'');
            //$("#nmskpd").attr("value",'');
            $("#keterangan").attr("value", '');
            $("#beban").attr("value", '');
            $("#total").attr("value", '0');
            $("#notagih").combogrid("setValue", '');
            $("#tgltagih").attr("value", '');
            $("#sisa_tunai").attr("value", '');
            $("#sisa_bank").attr("value", '');
            $("#status").attr("checked", false);
            $("#tagih").hide();
            status_transaksi = 'tambah';
            load_detail_baru();
            document.getElementById("nomor").focus();
            tombolnew();
            get_nourut();
        }

        function kosong1() {
            $("#sdana1_susun").attr("value", '0.00');
            // $("#sdana2_susun").attr("value",'0.00');
            // $("#sdana3_susun").attr("value",'0.00');
            // $("#sdana1_sempurna").attr("value", '0.00');
            // $("#sdana2_sempurna").attr("value",'0.00');
            // $("#sdana3_sempurna").attr("value",'0.00');
            // $("#sdana1_ubah").attr("value", '0.00');
            // $("#sdana2_ubah").attr("value",'0.00');
            // $("#sdana3_ubah").attr("value",'0.00');
            $("#sdana1_trans").attr("value", '0.00');
            // $("#sdana2_trans").attr("value",'0.00');
            // $("#sdana3_trans").attr("value",'0.00');
            $("#sdana1_real").attr("value", '0.00');
            // $("#sdana2_real").attr("value",'0.00');
            // $("#sdana3_real").attr("value",'0.00');
            $("#nilai_koreksi").attr("value", '0.00');
            $("#nilai").attr("value", '0.00');
        }

        function get_nourut() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/cms/no_urut',
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
                    url: '<?php echo base_url(); ?>/index.php/koreksi/load_transout_koreksi2',
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
            //                data     : ({cskpd:dinas,spm:vnospm,kd_rek6:rek_pajak,nmrek:nm_rek_pajak,nilai:nil_pajak,ket:cket}),
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
            var nosp2d = $('#sp2d').combogrid('getValue');
            var rek = $('#rek').combogrid('getValue');
            var dana = $('#s_dana').combogrid('getValue');
            var jenis = document.getElementById('beban').value;
            var nmrek = document.getElementById('nmrek').value;
            var crek = $('#rek').combogrid('grid'); // get datagrid object
            //var grek      = crek.datagrid('getSelected'); // get the selected row
            //var canggaran   = number_format(grek.anggaran,2,'.',',');
            // var csp2d     = number_format(grek.sp2d,2,'.',',');
            // var clalu     = number_format(grek.lalu,2,'.',',');
            var nilai_rek = angka(document.getElementById('nilai').value);
            var lcjenis = document.getElementById('jns_tunai').value;
            var nil = document.getElementById('nilai').value;
            var total1 = angka(document.getElementById('total1').value);
            // var pad_a       = document.getElementById('nil_pad_a').value;
            // var dak_a       = document.getElementById('nil_dak_a').value;
            // var daknf_a     = document.getElementById('nil_daknf_a').value;
            // var dau_a       = document.getElementById('nil_dau_a').value;
            // var dbhp_a      = document.getElementById('nil_dbhp_a').value;
            // var did_a       = document.getElementById('nil_did_a').value;
            // if(pad_a!='0.00'){pad_a = '-'+pad_a;}
            // if(dak_a!='0.00'){dak_a = '-'+dak_a;}
            // if(daknf_a!='0.00'){daknf_a = '-'+daknf_a;}
            // if(dau_a!='0.00'){dau_a = '-'+dau_a;}
            // if(dbhp_a!='0.00'){dbhp_a = '-'+dbhp_a;}
            // if(did_a!='0.00'){did_a = '-'+did_a;}
            var nil = '-' + nil;
            akumulasi = total1 + nilai_rek;

            if (rek == '') {
                alert('Pilih rekening Dahulu');
                exit();
            }

            if (dana == '') {
                alert('Pilih Sumber Dana Dahulu');
                exit();
            }
            if (nosp2d == '') {
                alert('Pilih sp2d Dahulu');
                exit();
            }

            if (nosp2d == 'undefined') {
                alert("No sp2d kosong");
                exit();
            }

            if (nmrek == '') {
                alert('Pilih rekening Dahulu');
                exit();
            }


            if (nil == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
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
                sumber: dana,
                lalu: 0,
                sp2d: 0,
                anggaran: 0
            });
            $('#dg2').edatagrid('appendRow', {
                no_bukti: no,
                no_sp2d: nosp2d,
                kd_sub_kegiatan: giat,
                nm_sub_kegiatan: nmgiat,
                kd_rek6: rek,
                nm_rek6: nmrek,
                nilai: nil,
                sumber: dana,
                lalu: 0,
                sp2d: 0,
                anggaran: 0
            });
            kosong2();
            total = total1 + (nilai_rek * -1);
            $('#total1').attr('value', number_format(total, 2, '.', ','));
            $('#total').attr('value', number_format(total, 2, '.', ','));

        }

        function cek_sumber(n) {
            var data = $('#dg3').datagrid('getData');
            var rows = data.rows;
            var cek = 0;
            for (i = 0; i < rows.length; i++) {
                if (n == rows[i].sumber) {
                    cek = 1;
                }
            }
            return cek;
        }

        function cek_rekkoreksi(n) {
            var data = $('#dg3').datagrid('getData');
            var rows = data.rows;
            var cek = 0;
            for (i = 0; i < rows.length; i++) {
                if (n == rows[i].kd_rek6) {
                    cek = 1;
                }
            }
            return cek;
        }



        function append_save_koreksi() {


            var no = document.getElementById('nomor').value;
            var giat_koreksi = $('#giat_koreksi').combogrid('getValue');
            var dana_koreksi = $('#sdana1').combogrid('getValue');
            var nmgiat_koreksi = document.getElementById('nmgiat_koreksi').value;
            var nosp2d_koreksi = $('#sp2d_koreksi').combogrid('getValue');
            var rek_koreksi = $('#rek_koreksi').combogrid('getValue').trim();
            var jenis = document.getElementById('beban').value;
            var nmrek_koreksi = document.getElementById('nmrek_koreksi').value;
            var crek_koreksi = $('#rek_koreksi').combogrid('grid'); // get datagrid object

            var nilai_trans = 0
            $('#dg1').edatagrid('getData').rows.forEach((row) => {
                if (row.kd_rek6 == rek_koreksi && row.kd_sub_kegiatan == giat_koreksi) {
                    nilai_trans += angka(row.nilai)
                }
            })
            //var grek_koreksi      = crek_koreksi.datagrid('getSelected'); // get the selected row
            //var canggaran   = number_format(grek.anggaran,2,'.',',');
            //var csp2d     = number_format(grek.sp2d,2,'.',',');
            //var clalu     = number_format(grek.lalu,2,'.',',');
            var vstatus = document.getElementById('txtstatus').value.trim();
            var nilai_rek_koreksi = angka(document.getElementById('nilai_koreksi').value);
            var lcjenis = document.getElementById('jns_tunai').value;
            var nil_koreksi = document.getElementById('nilai_koreksi').value;
            var total1 = angka(document.getElementById('total1').value);
            var ang = angka(document.getElementById('ang').value);
            var lalu = angka(document.getElementById('lalu').value);

            var sdana1_susun = angka(document.getElementById('sdana1_susun').value);
            var sdana1_trans = angka(document.getElementById('sdana1_trans').value);
            var sdana1_real = angka(document.getElementById('sdana1_real').value);


            // Angkas
            var ssisaspd = angka(document.getElementById('sisa_spd').value);
            var ssisaangkas = angka(document.getElementById('sisa_angkas').value);


            var vsdana1 = $('#sdana1').combogrid('getValue').trim();
            var nil_pad = 0;
            var nil_dak = 0;
            var nil_daknf = 0;
            var nil_dau = 0;
            var nil_dbhp = 0;
            var nil_did = 0;

            var ceksumber1 = cek_sumber(vsdana1);
            if (ceksumber1 == 0) {
                swal("Error", "Nama Sumber Dana Pertama tidak ada. Cek lagi.", "error");
                return;
            }

            if (nilai_rek_koreksi > ssisaspd) {
                alert('Nilai koreksi melebihi sisa SPD');
                exit();
            }

            if (nilai_rek_koreksi > ssisaangkas) {
                alert('Nilai koreksi melebihi sisa Anggaran Kas');
                exit();
            }


            akumulasi = total1 + nilai_rek_koreksi;

            if (rek_koreksi == '') {
                alert('Pilih rekening Dahulu');
                exit();
            }
            if (nosp2d_koreksi == '') {
                alert('Pilih sp2d Dahulu');
                exit();
            }

            if (nosp2d_koreksi == 'undefined') {
                alert("No sp2d kosong");
                exit();
            }

            if (nmrek_koreksi == '') {
                alert('Pilih rekening Dahulu');
                exit();
            }


            if (nil_koreksi == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                exit();
            }

            if (giat_koreksi == '') {
                alert('Pilih Kegiatan Dahulu');
                return;
            }
            if (nmgiat_koreksi == '') {
                alert('Pilih Kegiatan Dahulu');
                exit();
            }



            if (vsdana1 == '') {
                swal("Error", "Sumber Dana Ke-1 tidak boleh kosong", "error");
                return;
            }

            var sisa_ang = ang - (lalu + nilai_rek_koreksi + nilai_trans);

            console.log(sdana1_trans + sdana1_real)
            if (vstatus == 'M') {
                var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                if (sisa1_susun < 0) {
                    swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Penetapan!!!, Cek Lagi...!!!", "error");
                    return;
                }
                if (sisa_ang < 0) {
                    swal("Error", "Nilai Transaksi Melebihi Anggaran Penetapan!!!, Cek Lagi...!!!", "error");
                    return;
                }
            } else if (vstatus == 'P1') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Penyempurnaan I !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Penyempurnaan I !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            } else if (vstatus == 'P2') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Penyempurnaan II !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Penyempurnaan II !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            } else if (vstatus == 'P3') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Penyempurnaan III !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Penyempurnaan III !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            } else if (vstatus == 'P4') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Penyempurnaan IV !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Penyempurnaan IV !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            } else if (vstatus == 'P5') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Penyempurnaan V !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Penyempurnaan V !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            } else if (vstatus == 'U1') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Perubahan I !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Perubahan I !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            } else if (vstatus == 'U2') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Perubahan II !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Perubahan II !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            } else if (vstatus == 'U3') {
                // var sisa1_susun = sdana1_susun - (sdana1_trans + sdana1_real + nilai_trans);
                // if (sisa1_susun < 0) {
                //     swal("Error", "Nilai " + vsdana1 + " Melebihi Sisa Sumber Dana Perubahan III !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
                // if (sisa_ang < 0) {
                //     swal("Error", "Nilai Transaksi Melebihi Anggaran Perubahan III !!!, Cek Lagi...!!!", "error");
                //     return;
                // }
            }



            $('#dg1').edatagrid('appendRow', {
                no_bukti: no,
                no_sp2d: nosp2d_koreksi,
                kd_sub_kegiatan: giat_koreksi,
                nm_sub_kegiatan: nmgiat_koreksi,
                kd_rek6: rek_koreksi,
                nm_rek6: nmrek_koreksi,
                nilai: nil_koreksi,
                sumber: dana_koreksi,
                lalu: 0,
                sp2d: 0,
                anggaran: 0
            });
            $('#dg2').edatagrid('appendRow', {
                no_bukti: no,
                no_sp2d: nosp2d_koreksi,
                kd_sub_kegiatan: giat_koreksi,
                nm_sub_kegiatan: nmgiat_koreksi,
                kd_rek6: rek_koreksi,
                nm_rek6: nmrek_koreksi,
                nilai: nil_koreksi,
                sumber: dana_koreksi,
                lalu: 0,
                sp2d: 0,
                anggaran: 0
            });

            kosong3();
            total = total1 + nilai_rek_koreksi;
            $('#total1').attr('value', number_format(total, 2, '.', ','));
            $('#total').attr('value', number_format(total, 2, '.', ','));


        }



        function validate_rekening() {
            $('#dgpajak').datagrid('selectAll');
            var rows = $('#dgpajak').datagrid('getSelections');

            frek = '';
            rek5 = '';
            for (var p = 0; p < rows.length; p++) {
                rek5 = rows[p].kd_rek6;
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
            kosong1();

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
                kegia()
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
            $('#nmrek').attr('value', '');
            $('#nilai').attr('value', '0');
            $('#nmgiat').attr('value', '');
        }

        function kosong3() {
            $('#giat_koreksi').combogrid('setValue', '');
            $('#sp2d_koreksi').combogrid('setValue', '');
            $('#rek_koreksi').combogrid('setValue', '');
            $('#nmrek_koreksi').attr('value', '');
            $('#nilai_koreksi').attr('value', '0');
            $('#nmgiat_koreksi').attr('value', '');
        }



        function keluar() {
            /*var nilai_rek   = angka(document.getElementById('nilai').value);        
                var tot_tunai   = angka(document.getElementById('total_sisa').value);        
                var tot_bank    = angka(document.getElementById('sisa_bank').value);        
                var lcjenis     = document.getElementById('jns_tunai').value;        
                
            if((lcjenis=='TUNAI') &&(nilai_rek>tot_tunai)){
              alert('Total Transaksi melebihi Sisa Kas Tunai');
              exit();
              }
            if((lcjenis=='BANK') &&(nilai_rek>tot_bank)){
              alert('Total Transaksi melebihi Sisa Simpanan Bank');
              exit();
              }
            */
            $("#dialog-modal").dialog('close');
            $('#dg2').edatagrid('reload');
            kosong2();
            kosong3();
        }

        function hapus_giat() {
            tot3 = 0;
            var tot = angka(document.getElementById('total').value);
            tot3 = tot - nilx;
            $('#total').attr('value', number_format(tot3, 2, '.', ','));
            $('#dg1').datagrid('deleteRow', idx);
        }

        function hapus() {
            var cnomor = document.getElementById('nomor').value;
            var urll = '<?php echo base_url(); ?>index.php/tukd/hapus_transout';
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

        function hitung_nilai() {
            var a = angka(document.getElementById('sdana1_trans').value);
            // var b = angka(document.getElementById('sdana2_trans').value);
            // var c = angka(document.getElementById('sdana3_trans').value); 
            var e = a;
            $("#nilai_koreksi").attr("value", number_format(e, 2, '.', ','));

        }

        function simpan_transout() {
            var cno = document.getElementById('nomor').value;
            var ctgl = $('#tanggal').datebox('getValue');
            var ctgl_koreksi = $('#tgl_koreksi').datebox('getValue');
            var no_simpan = document.getElementById('no_simpan').value;
            var cnokaspot = document.getElementById('nomor').value;
            var cskpd = document.getElementById('skpd').value; //$('#skpd').combogrid('getValue');
            var cnmskpd = document.getElementById('nmskpd').value;
            var cket = document.getElementById('keterangan').value;
            var cjenis = document.getElementById('beban').value;
            var cstatus = document.getElementById('status').checked;
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
            //var ckas = angka(document.getElementById('total_sisa').value);  
            //var cbank = angka(document.getElementById('sisa_bank').value);  
            var tahun_input = ctgl.substring(0, 4);
            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                exit();
            }
            /*
            if (cjenis_bayar=='TUNAI' && ctotal>ckas){
                    alert('Nilai Melebihi sisa KAS Tunai');
                    exit();
                }
            if (cjenis_bayar=='BANK' && ctotal>cbank){
                    alert('Nilai Melebihi sisa Simpanan Bank');
                    exit();
                }
            */
            if (cno == '') {
                alert('Nomor Bukti Tidak Boleh Kosong');
                exit();
            }
            if (ctgl == '') {
                alert('Tanggal Bukti Tidak Boleh Kosong');
                exit();
            }
            if (cskpd == '') {
                alert('Kode SKPD Tidak Boleh Kosong');
                exit();
            }
            if (cjenis == '') {
                alert('Jenis beban Tidak Boleh Kosong');
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

            // if (ctot_det != 0){
            //   alert('Nilai Koreksi masih ada sisa');
            //   exit();
            // }



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
                        url: '<?php echo base_url(); ?>/index.php/koreksi/cek_simpan',
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
                                            nokas: cno,
                                            tglkas: ctgl,
                                            ctgl_koreksi: ctgl_koreksi,
                                            skpd: cskpd,
                                            nmskpd: cnmskpd,
                                            beban: cjenis,
                                            ket: cket,
                                            status: cstatus,
                                            notagih: ctagih,
                                            tgltagih: ctgltagih,
                                            total: ctotal,
                                            cpay: cjenis_bayar,
                                            nokas_pot: cnokaspot
                                        }),
                                        url: '<?php echo base_url(); ?>/index.php/koreksi/simpan_transout_koreksi2',
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
                                        csumber = rows[p].sumber;
                                        // cnil_pad   = angka(rows[p].pad);
                                        // cnil_dak   = angka(rows[p].dak);
                                        // cnil_daknf = angka(rows[p].daknf);
                                        // cnil_dau   = angka(rows[p].dau);
                                        // cnil_dbhp  = angka(rows[p].dbhp);
                                        // cnil_did   = angka(rows[p].did);

                                        if (p > 0) {
                                            csql = csql + "," + "('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "'," +
                                                "'" + csumber + "')";
                                        } else {
                                            csql = "values('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "'," +
                                                "'" + csumber + "')";
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
                                                beban: cjenis,
                                                status: cstatus
                                            }),
                                            url: '<?php echo base_url(); ?>/index.php/koreksi/simpan_transout_koreksi2',
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
                                            nokas: cno,
                                            tglkas: ctgl,
                                            ctgl_koreksi: ctgl_koreksi,
                                            skpd: cskpd,
                                            nmskpd: cnmskpd,
                                            beban: cjenis,
                                            ket: cket,
                                            status: cstatus,
                                            notagih: ctagih,
                                            tgltagih: ctgltagih,
                                            total: ctotal,
                                            cpay: cjenis_bayar,
                                            nokas_pot: cnokaspot,
                                            no_bku: no_simpan
                                        }),
                                        url: '<?php echo base_url(); ?>/index.php/koreksi/simpan_transout_koreksi_edit',
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
                                        csumber = rows[p].sumber;
                                        // cnil_pad   = angka(rows[p].pad);
                                        // cnil_dak   = angka(rows[p].dak);
                                        // cnil_daknf = angka(rows[p].daknf);
                                        // cnil_dau   = angka(rows[p].dau);
                                        // cnil_dbhp  = angka(rows[p].dbhp);
                                        // cnil_did   = angka(rows[p].did);

                                        if (p > 0) {
                                            csql = csql + "," + "('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "'," +
                                                "'" + csumber + "')";
                                        } else {
                                            csql = "values('" + cnobukti + "','" + cnosp2d + "','" + ckdgiat + "','" + cnmgiat + "','" + crek + "','" + cnmrek + "','" + cnilai + "','" + cskpd + "'," +
                                                "'" + csumber + "')";
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
                                                skpd: cskpd,
                                                sql: csql,
                                                beban: cjenis,
                                                status: cstatus,
                                                no_bku: no_simpan
                                            }),
                                            url: '<?php echo base_url(); ?>/index.php/koreksi/simpan_transout_koreksi_edit',
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

        // SPD ANGKAS
        function load_total_spd() {
            var giat = $('#giat_koreksi').combogrid('getValue');
            var kode = document.getElementById('skpd').value;
            var koderek = $('#rek_koreksi').combogrid('getValue');
            var tgl_cek = $('#tgl_koreksi').datebox('getValue');
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
                            $("#nil_spd").attr("value", n['total_spd']);
                        });
                    }
                });
            });
        }

        function cek_status_angkas() {
            var tgl_cek = $('#tgl_koreksi').datebox('getValue');
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/penagihan/cek_status_angkas',
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
            var giat = $('#giat_koreksi').combogrid('getValue');
            var kode = document.getElementById('skpd').value;
            var koderek = $('#rek_koreksi').combogrid('getValue');
            // var nosp2d = $('#sp2d').combogrid('getValue');
            var tgl_cek = $('#tgl_koreksi').datebox('getValue');
            var jnsbeban = document.getElementById('beban').value;

            var sts_angkas = document.getElementById('status_angkas').value;

            $(function() {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: ({
                        kegiatan: giat,
                        kd_skpd: kode,
                        kdrek6: koderek,
                        tgl: tgl_cek,
                        sts_angkas: sts_angkas,
                        jnsbeban: jnsbeban
                    }),
                    url: '<?php echo base_url(); ?>index.php/cms/total_angkas',
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#nil_angkas").attr("Value", n['nilai']);
                            var n_totalangkas = n['nilai'];
                            load_angkas_lalu();
                        });
                    }
                });

            });
        }

        function load_angkas_lalu() {
            var giat = $('#giat_koreksi').combogrid('getValue');
            var kode = document.getElementById('skpd').value;
            var koderek = $('#rek_koreksi').combogrid('getValue');
            var nosp2d = $('#sp2d_koreksi').combogrid('getValue');
            var tgl_cek = $('#tgl_koreksi').datebox('getValue');
            // var nosp2d  = $('#sp2d').combogrid('getValue');
            var jnsbeban = document.getElementById('beban').value;
            var cno = document.getElementById('nomor').value;
            var no_simpan = document.getElementById('no_simpan').value;
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
                        no_simpan: no_simpan
                    }),
                    url: '<?php echo base_url(); ?>index.php/cms/load_total_trans_spd',
                    success: function(data) {
                        $.each(data, function(i, n) {
                            // $("#nilai_angkas_lalu").attr("Value",n['total']);
                            $("#tot_trans").attr("value", n['total']);

                            var n_angkaslalu = n['total'];
                            var spdlalu = n['total'];
                            var total_angkas = document.getElementById('nil_angkas').value;
                            var total_spd = document.getElementById('nil_spd').value;

                            var n_sisaangkas = angka(total_angkas) - angka(n_angkaslalu);
                            var n_sisaspd = angka(total_spd) - angka(spdlalu);

                            $("#sisa_angkas").attr("Value", number_format(n_sisaangkas, 2, '.', ','));
                            $('#sisa_spd').attr('value', number_format(n_sisaspd, 2, '.', ','));
                        });
                    }
                });

            });
        }
        // SPD ANGKAS

        function reload_data() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/tukd2/load_transout_koreksi2',
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
                            width: 20
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
                            width: 90,
                            align: "left"
                        },
                        {
                            field: 'ket',
                            title: 'Keterangan',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'ketlpj',
                            title: 'LPJ',
                            width: 10,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomor = rowData.no_bukti;
                    tgl = rowData.tgl_bukti;
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
                    ststagih = rowData.sts_tagih;
                    vpay = rowData.pay;
                    statlpj = rowData.ketlpj;

                    get(nomor, tgl, kode, nama, ket, jns, tot, notagih, tgltagih, ststagih, vpay, nokas_pot, tglpot, ketpot, statlpj);

                    if (ststagih != '1') {
                        load_detail();
                    }
                },
                onDblClickRow: function(rowIndex, rowData) {
                    section2();
                }
            });
        }
    </script>

</head>

<body>



    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1">List Koreksi Pembayaran</a></h3>

            <table width="100%" border="0">
                <tr>
                    <td colspan="2" align="center">
                        <h4>INPUTAN JURNAL KOREKSI HARUS MASUK DALAM LPJ / DI-LPJ-KAN AGAR NILAI BISA BERUBAH</h4>
                    </td>
                </tr>
                <tr>
                    <td width="70%">
                        <button class="btn btn-success" plain="true" onclick="javascript:section2();kosong();datagrid_kosong();"><i class="fa fa-plus"></i> Tambah</button>
                        <button onclick="location.href='<?php echo site_url(); ?>koreksi/cetak_jurnal_k2'" class="btn btn-dark" plain="true"><i class="fa fa-print"></i> Cetak</button>
                    </td>
                    <td align="right" width="30%">

                        <div class="input-group">
                            <input type="text" class="form-control" value="" id="txtcari" />
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="button" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <!-- <table id="dg" title="LISTING DATA KONTRAK" style="width:900px;height:365px;" >  
        </table> -->
                        <table id="dg" title="List Koreksi Transaksi" style="width:900px;height:600px;">
                        </table>
                    </td>
                </tr>
            </table>

            <!--     <div><B>INPUTAN JURNAL KOREKSI HARUS MASUK DALAM LPJ / DI-LPJ-KAN AGAR NILAI BISA BERUBAH</b> </BR>
    <p align="right">    
        <a href="<?php echo site_url(); ?>/tukd2/cetak_jurnal_k" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:input_lengkap(this.href);return false">Cetak</a>
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" >Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
                                 
    </p> 
    </div> -->

            <h3><a href="#" id="section2">KOREKSI TRANSAKSI</a></h3>
            <div style="height: 350px;">
                <p> <B>INPUTAN JURNAL KOREKSI HARUS MASUK DALAM LPJ / DI-LPJ-KAN AGAR NILAI BISA BERUBAH</b>
                <div id="demo"> </div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td hidden colspan="5"><b>P E N A G I H A N</b><input type="checkbox" id="status" onclick="javascript:runEffect();" />
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
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>NO. BKU</label>
                            <input type="text" class="form-control" id="no_simpan" style="border:0;width: 400px;" readonly="true" ; />
                            <small>Tidak Perlu diisi atau di Edit</small>
                        </td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">

                        </td>
                    </tr>

                    <tr>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>NO. BUKTI</label>
                            <input type="text" id="nomor" class="form-control" style="width: 400px;" maxlength="35" />
                        </td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>TANGGAL TRANSAKSI</label><br>
                            <input type="text" id="tanggal" class="form-control" style="width: 140px;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>KODE OPD/UNIT</label>
                            <input id="skpd" type="text" class="form-control" name="skpd" style="width: 400px;" disabled="true" />
                        </td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>TANGGAL KOREKSI</label><br>
                            <input type="text" id="tgl_koreksi" class="form-control" style="width: 140px;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>NAMA OPD/UNIT</label>
                            <textarea id="nmskpd" class="form-control" style="border:0;width: 400px; height: 40px;" readonly="true"></textarea>
                        </td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>KETERANGAN</label>
                            <textarea id="keterangan" class="form-control" style="width: 400px; height: 40px;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>JENIS BEBAN</label><br>
                            <?php echo $this->tukd_model->combo_beban('beban'); ?>
                        </td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <label>PEMBAYARAN</label>
                            <select name="jns_tunai" id="jns_tunai" class="form-control" style="width: 420px;">
                                <option value="TUNAI">TUNAI</option>
                                <option value="BANK">BANK</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" align="right">
                            <button id="save" class="btn btn-primary" plain="true" onclick="javascript:simpan_transout();"><i class="fa fa-save"></i> Simpan</button>
                            <button id="del" class="btn btn-danger" plain="true" onclick="javascript:hapus();section1();"><i class="fa fa-trash"></i> Hapus</button>
                            <button class="btn btn-warning" plain="true" onclick="javascript:section1();"><i class="fa fa-arrow-left"></i> Kembali</button>
                        </td>
                    </tr>


                </table>
                <table id="dg1" title="Rincian" style="width:870px;height:450px;">
                </table>
                <div id="toolbar" align="right">
                    <button id="tambah" class="btn btn-success" plain="true" onclick="javascript:tambah();"><i class="fa fa-plus"></i> Tambah Rincian</button>
                    <button id="hapus" class="btn btn-danger" plain="true" onclick="javascript:hapus_giat();"><i class="fa fa-trash"></i> Hapus Rincian</button>

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
                                <td>Tanggal Transaksi:<input type="text" id="tglkas" name="tglkas" style="width:100px;" /></td>
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
            <table border='0' width="100%">
                <tr>
                    <td width="45%">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                Pilih Transaksi Awal
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Sub Kegiatan</label><br />
                                    <input id="giat" type="text" class="form-control" name="giat" style="width: 300px;" />&nbsp;&nbsp;
                                    <input type="text" id="nmgiat" readonly="true" style="border:0;width: 400px;" />
                                </div>
                                <div class="form-group">
                                    <label>Nomor SP2D</label><br />
                                    <input type="text" class="form-control" id="sp2d" name="sp2d" style="width: 300px;" />
                                </div>
                                <div class="form-group">
                                    <label>Rekening Belanja</label><br />
                                    <input type="text" class="form-control" id="rek" name="rek" style="width: 300px;" />&nbsp;&nbsp;
                                    <input type="text" id="nmrek" readonly="true" style="border:0;width: 400px;" />
                                </div>
                                <div class="form-group">
                                    <label>Sumber Dana</label><br />
                                    <input type="text" class="form-control" id="s_dana" name="s_dana" style="width: 300px;" />&nbsp;&nbsp;
                                    <input type="text" id="nms_dana" readonly="true" style="border:0;width: 400px;" />
                                </div>
                                <div class="form-group">
                                    <label>Nilai</label><br />
                                    <input disabled="true" type="text" class="form-control" id="nilai" class="satu" style="text-align: right;width: 280px;" onkeypress="return(currencyFormat(this,',','.',event))" />
                                </div>
                            </div>
                            <div class="card-footer" align="center">
                                <button class="btn btn-success" plain="true" onclick="javascript:append_save();"><i class="fa fa-plus"></i> Tambah</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <table border='0' width="100%">
                <tr>
                    <td colspan="5">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                Pilih Transaksi Koreksi
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Sub Kegiatan</label><br />
                                    <input id="giat_koreksi" name="giat_koreksi" class="form-control" type="text" style="width: 300px;" />&nbsp;&nbsp;
                                    <input type="text" id="nmgiat_koreksi" readonly="true" style="border:0;width: 400px;" />
                                </div>
                                <div class="form-group">
                                    <label>Nomor SP2D</label><br />
                                    <input id="sp2d_koreksi" name="sp2d_koreksi" class="form-control" type="text" style="width: 300px;" />
                                </div>
                                <div class="form-group">
                                    <label>Rekening Belanja</label><br />
                                    <input id="rek_koreksi" name="rek_koreksi" class="form-control" type="text" style="width: 300px;" />
                                    &nbsp;&nbsp;<input type="text" id="nmrek_koreksi" readonly="true" style="border:0;width: 400px;" />
                                </div>
                                <div class="form-group">
                                    <label>Sumber Dana</label><br />
                                    <input id="sdana1" name="sdana1" class="form-control" style="width: 300px;" />
                                    &nbsp;&nbsp;<input type="text" id="nmsdana1" name="nmsdana1" readonly="true" style="border:0;width: 400px;" />
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <div class="card">
                            <div class="card-body bg-info">
                                <div class="form-group">
                                    <label>Anggaran</label><br />
                                </div>
                                <div class="form-group">
                                    <label>Anggaran</label><br />
                                    <input type="text" class="form-control" id="ang" value="0.00" style="text-align:right;border:0;width: 260px;" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Realisasi</label><br />
                                    <input type="text" class="form-control" id="lalu" value="0.00" style="text-align:right;border:0;width: 260px;" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Sisa Anggaran</label><br />
                                    <input type="text" class="form-control" id="sisa_lalu" value="0.00" style="text-align:right;border:0;width: 260px;" disabled="true" />
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="5%">
                        &nbsp;
                    </td>
                    <td width="30%">
                        <div class="card">
                            <div class="card-body bg-warning">
                                <div class="form-group">
                                    <label>Sumber Dana</label><br />
                                </div>
                                <div class="form-group">
                                    <label>Sumber Dana</label><br />
                                    <input type="text" id="sdana1_susun" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Realisasi</label><br />
                                    <input type="text" id="sdana1_real" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Sisa Sumber Dana</label><br />
                                    <input type="text" id="sisa_sdana1_real" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="5%">
                        &nbsp;
                    </td>
                    <td width="30%">
                        <div class="card">
                            <div class="card-body bg-secondary text-white">
                                <div class="form-group">
                                    <label>SPD & Angkas</label><br />
                                </div>
                                <div class="form-group">
                                    <label>Nilai SPD</label><br />
                                    <input type="text" id="nil_spd" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Nilai Angkas</label><br />
                                    <input type="text" id="nil_angkas" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Realisasi</label><br />
                                    <input type="text" id="tot_trans" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Sisa SPD</label><br />
                                    <input type="text" id="sisa_spd" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                                <div class="form-group">
                                    <label>Sisa Angkas</label><br />
                                    <input type="text" id="sisa_angkas" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" disabled="true" />
                                </div>
                            </div>
                        </div>
                    </td>


                </tr>
                <tr>
                    <td width="20%">
                        <div class="card">
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <label>Status Anggaran</label><br />
                                    <input id="txtstatus_hidden" name="txtstatus_hidden" class="form-control" readonly="true" style="width: 260px;" />
                                    <input id="txtstatus" name="txtstatus" class="form-control" readonly="true" style="width: 260px;" hidden />
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="5%">
                        &nbsp;
                    </td>
                    <td width="20%">
                        <div class="card">
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <label>Status Anggaran Kas</label><br />
                                    <input id="status_angkas" name="status_angkas" class="form-control" readonly="true" style="width: 260px;" />
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="5%">
                        &nbsp;
                    </td>
                    <td>
                        <div class="card">
                            <div class="card-body bg-success">
                                <div class="form-group">
                                    <label>Nilai Koreksi</label><br />
                                    <input type="text" id="sdana1_trans" value="0.00" style="text-align:right;border:0;width: 260px;" class="form-control" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_nilai();" />
                                    <input type="hidden" class="form-control" id="nilai_koreksi" value="0.00" style="text-align: right;width: 260px;" onkeypress="return(currencyFormat(this,',','.',event))" disabled="true" class="satu" />
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" align="center">
                        <div class="card">
                            <div class="card-footer">
                                <button class="btn btn-primary" plain="true" onclick="javascript:append_save_koreksi();"><i class="fa fa-save"></i> Simpan</button>
                                <button class="btn btn-warning" iconCls="icon-undo" plain="true" onclick="javascript:keluar();"><i class="fa fa-undo"></i> Keluar</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>



            <div style="display:none">
                <table id="dg3" title="A" style="width:850px;height:300px;"></table>
            </div>

        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="right">
                <tr>
                    <td>Total</td>
                    <td>:</td>
                    <td><input type="text" id="total1" value="0" readonly="true" style="font-size: large;text-align: right;border:0;width: 200px;" /></td>
                </tr>
            </table>
            <table id="dg2" title="Input Rekening" style="width:950px;height:270px;">
            </table>

        </fieldset>
    </div>


</body>

</html>