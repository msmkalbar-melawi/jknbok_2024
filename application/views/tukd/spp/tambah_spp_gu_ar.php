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

    <script type="text/javascript">
        var no_spp = '';
        var kode = '';
        var spd = '';
        var st_12 = 'edit';
        var nidx = 100
        var spd2 = '';
        var spd3 = '';
        var spd4 = '';
        var lcstatus = '';
        get_tahun();

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();
            $("#frm").hide();
            $("#dialog-modal").dialog({
                height: 400,
                width: 700,
                modal: true,
                autoOpen: false
            });
            $("#dialog-batal").dialog({
                height: 300,
                width: 700,
                modal: true,
                autoOpen: false
            });
            $("#dialog-modal-tr").dialog({
                height: 320,
                width: 500,
                modal: true,
                autoOpen: false
            });
            get_skpd();
            $("#no_spp").attr('disabled', true);
            //seting_tombol();
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
            //
            $('#dd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {
                    var m = date.getMonth() + 1;
                    $("#bulan").attr('value', m);
                    get_spp();
                }
            });

            $('#ttd1').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spp/load_ttd/BK',
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
                    $("#nmttd1").attr("value", rowData.nama);
                }
            });

            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spp/load_ttd/PPTK',
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
                    $("#nmttd2").attr("value", rowData.nama);
                }

            });

            $('#ttd3').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spp/load_ttd/PA',
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
                    $("#nmttd3").attr("value", rowData.nama);
                }

            });

            $('#ttd4').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spp/load_ttd3/BUD',
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
                    $("#nmttd4").attr("value", rowData.nama);
                }

            });

            $('#rekanan').combogrid({
                panelWidth: 400,
                url: '<?php echo base_url(); ?>/index.php/spp/perusahaan',
                idField: 'kode',
                textField: 'kode',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'nmrekan',
                            title: 'Nama Rekanan / Bendahara',
                            width: 150
                        },
                        {
                            field: 'rekening',
                            title: 'Rekening',
                            width: 150
                        },
                        {
                            field: 'npwp',
                            title: 'NPWP',
                            width: 100
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    //$("#kode").attr("value",rowData.kode);
                    $("#dir").attr("value", rowData.pimpinan);
                    $("#npwp").attr("value", rowData.npwp);
                    $("#alamat").attr("value", rowData.alamat);
                    $("#rekening").attr("value", rowData.rekening);
                    $("#nama_rekanan").attr("value", rowData.nmrekan);
                    $("#rekening").attr('disabled', true);
                    $("#npwp").attr('disabled', true);
                    $("#nama_rekanan").attr('disabled', true);
                    $("#alamat").attr('disabled', true);
                    $("#dir").attr('disabled', true);

                }
            });


            $('#cspp').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/load_spp_gu',
                idField: 'no_spp',
                textField: 'no_spp',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_spp',
                            title: 'SPP',
                            width: 60
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            align: 'left',
                            width: 60
                        },
                        {
                            field: 'tgl_spp',
                            title: 'Tanggal',
                            width: 60
                        }

                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomer = rowData.no_spp;
                    kode = rowData.kd_skpd;
                    jns = rowData.jns_spp;
                    val_ttd(kode);
                }
            });


            $('#spp').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/spp/load_spp_gu',
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
                            field: 'no_spp',
                            title: 'NO SPP',
                            width: 60
                        },
                        {
                            field: 'tgl_spp',
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
                            field: 'keperluan',
                            title: 'Keterangan',
                            width: 110,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    urut = rowData.urut;
                    nomer = rowData.no_spp;
                    kode = rowData.kd_skpd;
                    spd = rowData.no_spd;
                    tg = rowData.tgl_spp;
                    jn = rowData.jns_spp;
                    kep = rowData.keperluan;
                    np = rowData.npwp;
                    bk = rowData.bank;
                    rekan = rowData.nmrekan;
                    ning = rowData.no_rek;
                    status = rowData.status;
                    no_bukti = rowData.no_bukti;
                    no_bukti2 = rowData.no_bukti2;
                    no_bukti3 = rowData.no_bukti3;
                    no_bukti4 = rowData.no_bukti4;
                    no_bukti5 = rowData.no_bukti5;
                    nlpj = rowData.no_lpj;
                    csp2d_batal = rowData.sp2d_batal;
                    cket_batal = rowData.ket_batal;
                    get(urut, nomer, kode, spd, tg, jn, kep, np, bk, rekan, ning, status, no_bukti, no_bukti2, no_bukti3, no_bukti4, no_bukti5, nlpj, csp2d_batal, cket_batal);
                    detail_trans_3();
                    load_sum_sppp();
                    lcstatus = 'edit';
                },
                onDblClickRow: function(rowIndex, rowData) {
                    section1();
                }
            });

            $('#spdi').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/spd1',
                queryParams: ({
                    dns: kode,
                    cjenis: '52'
                }),
                idField: 'no_spd',
                textField: 'no_spd',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_spd',
                            title: 'No SPD',
                            width: 50
                        },
                        {
                            field: 'tgl_spd',
                            title: 'Tanggal',
                            align: 'left',
                            width: 70
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    spd = rowData.no_spd;
                }
            });


            $('#spd1').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/spd1',
                queryParams: ({
                    dns: kode
                }),
                idField: 'no_spd',
                textField: 'no_spd',
                mode: 'remote',
                fitColumns: true,
                data: ({
                    cjenis: '52'
                }),
                columns: [
                    [{
                            field: 'no_spd',
                            title: 'No SPD',
                            width: 50
                        },
                        {
                            field: 'tgl_spd',
                            title: 'Tanggal',
                            align: 'left',
                            width: 70
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    spd2 = rowData.no_spd;
                }
            });


            $('#spd2').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/spd1',
                queryParams: ({
                    dns: kode
                }),
                idField: 'no_spd',
                textField: 'no_spd',
                mode: 'remote',
                fitColumns: true,
                data: ({
                    cjenis: '52'
                }),
                columns: [
                    [{
                            field: 'no_spd',
                            title: 'No SPD',
                            width: 50
                        },
                        {
                            field: 'tgl_spd',
                            title: 'Tanggal',
                            align: 'left',
                            width: 70
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    spd3 = rowData.no_spd;
                }
            });

            $('#bank1').combogrid({
                panelWidth: 700,
                url: '<?php echo base_url(); ?>/index.php/spp/config_bank2',
                idField: 'kd_bank',
                textField: 'kd_bank',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'kd_bank',
                            title: 'Kd Bank',
                            width: 150
                        },
                        {
                            field: 'nama_bank',
                            title: 'Nama',
                            width: 500
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    //$("#kode").attr("value",rowData.kode);
                    $("#nama_bank").attr("value", rowData.nama_bank);
                }
            });

            $('#spd3').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/spd1',
                queryParams: ({
                    dns: kode
                }),
                idField: 'no_spd',
                textField: 'no_spd',
                mode: 'remote',
                data: ({
                    cjenis: '52'
                }),
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_spd',
                            title: 'No SPD',
                            width: 50
                        },
                        {
                            field: 'tgl_spd',
                            title: 'Tanggal',
                            align: 'left',
                            width: 70
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    spd4 = rowData.no_spd;
                }
            });




            $('#nlpj').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/nlpj',
                queryParams: ({
                    dns: kode
                }),
                idField: 'no_lpj',
                textField: 'no_lpj',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_lpj',
                            title: 'No LPJ',
                            width: 50
                        },
                        {
                            field: 'tgl_lpj',
                            title: 'Tanggal',
                            align: 'left',
                            width: 70
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    clpj = rowData.no_lpj;
                    detail_plj();
                    sum_total();

                }
            });



            $('#notrans').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/select_data_tran_5',
                idField: 'no_bukti',
                textField: 'no_bukti',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                        field: 'no_bukti',
                        title: 'No Bukti',
                        width: 50
                    }]
                ]
            });

            $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
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
                            hidden: 'true'
                        },
                        {
                            field: 'no_bukti',
                            title: 'No Bukti',
                            width: 100,
                            align: 'left'
                        },
                        {
                            field: 'kdsubkegiatan',
                            title: 'Kegiatan',
                            width: 150,
                            align: 'left'
                        },
                        {
                            field: 'kdrek6',
                            title: 'Rekening',
                            width: 70,
                            align: 'left'
                        },
                        {
                            field: 'nmrek6',
                            title: 'Nama Rekening',
                            width: 230
                        },
                        {
                            field: 'nilai1',
                            title: 'Nilai',
                            width: 140,
                            align: 'right'
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber',
                            width: 50,
                            align: 'right'
                        }

                        //					,
                        //                    {field:'hapus',title:'',width:35,align:"center",
                        //                    formatter:function(value,rec){ 
                        //                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                        //                    }
                        //                    }
                    ]
                ]
            });
        });



        function val_ttd(dns) {
            $(function() {
                $('#ttd').combogrid({
                    panelWidth: 500,
                    url: '<?php echo base_url(); ?>/index.php/spp/pilih_ttd/' + dns,
                    idField: 'nip',
                    textField: 'nama',
                    mode: 'remote',
                    fitColumns: true,
                    columns: [
                        [{
                                field: 'nip',
                                title: 'NIP',
                                width: 60
                            },
                            {
                                field: 'nama',
                                title: 'NAMA',
                                align: 'left',
                                width: 100
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        nip = rowData.nip;
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
                    $("#dn").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    kode = data.kd_skpd;
                    validate_spd(kode);
                    //hapus kalau sudah
                    /* if(kode=='2.02.01.20'){
                    	$('#tambah').linkbutton('enable');
                    }else{
                    	$('#tambah').linkbutton('disable');
                    } */
                }
            });
        }



        function sum_total() {
            var cnlpj = $('#nlpj').combogrid('getValue');
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/spp/load_sum_lpj_ag",
                    data: ({
                        lpj: cnlpj
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            //$("#rektotal").attr("value",n['rektotal']);
                            //$("#rektotal1").attr("value",n['rektotal1']);
                            $("#rektotal").attr('value', number_format(n['cjumlah'], 2, '.', ','));
                            $("#rektotal1").attr('value', number_format(n['cjumlah'], 2, '.', ','));
                        });
                    }
                });
            });
        }




        function validate_spd(kode) {
            $(function() {
                $('#spdi').combogrid({
                    queryParams: ({
                        dns: kode,
                        cjenis: '5'
                    }),
                    panelWidth: 500,
                    url: '<?php echo base_url(); ?>/index.php/spp/spd1',
                    idField: 'no_spd',
                    textField: 'no_spd',
                    mode: 'remote',
                    fitColumns: true
                });
            });
        }


        function get(urut, no_spp, kd_skpd, no_spd, tgl_spp, jns_spp, keperluan, npwp, bank, rekanan, rekening, status, no_bukti, no_bukti2, no_bukti3, no_bukti4, no_bukti5, nlpj, sp2d_batal, ket_batal) {
            $("#dd_spp").attr("value", urut);
            $("#no_spp").attr("value", no_spp);
            $("#no_spp_hide").attr("value", no_spp);
            $("#dn").attr("Value", kd_skpd);
            $("#spdi").combogrid("setValue", no_spd);
            $("#nlpj").combogrid("setValue", nlpj);


            $("#dd").datebox("setValue", tgl_spp);
            $("#ketentuan").attr("Value", keperluan);
            $("#jns_beban").attr("Value", jns_spp);
            $("#npwp").attr("Value", npwp);
            $("#rekanan").combogrid("setValue", rekanan);
            $("#bank1").combogrid("setValue", bank);
            $("#rekening").attr("Value", rekening);

            $("#no1").attr("Value", no_bukti);
            $("#no2").attr("Value", no_bukti2);
            $("#no3").attr("Value", no_bukti3);
            $("#no4").attr("Value", no_bukti4);
            $("#no5").attr("Value", no_bukti5);
            tombol(status);
            status_batal(sp2d_batal);
            $('#rnospp').linkbutton('disable');
        }


        function status_batal($status1) {
            if ($status1 == '1') {
                $('#save').linkbutton('disable');
                $('#del').linkbutton('disable');
                $('#rnospp').linkbutton('disable');
                $('#batal').linkbutton('disable');
                $('#cetak').linkbutton('disable');
                document.getElementById("p2").innerHTML = "SPP dalam Status Batal";
            } else {
                document.getElementById("p2").innerHTML = "";
            }
        }

        function kunci_sementara() {
            var kode_s = document.getElementById('dn').value;
            var kunci = '';
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spp/ckunci_sementara',
                data: ({
                    kode_s: kode_s
                }),
                type: "POST",
                dataType: "json",
                success: function(data) {
                    kunci = data.kode;
                    //alert(kunci);
                    if (kunci != '') {
                        $('#save').linkbutton('disable');
                    } else {
                        $('#save').linkbutton('enable');
                    }
                }
            });
        }


        function kosong() {
            kunci_sementara();
            // get_spp();
            $("#no_spp_hide").attr("value", '');
            $("#spdi").combogrid("setValue", '');
            $("#nlpj").combogrid("setValue", '');
            $("#dd").datebox("setValue", '');
            $("#dd1").datebox("setValue", '');
            $("#dd2").datebox("setValue", '');
            $("#ketentuan").attr("Value", '');
            $("#jns_beban").attr("Value", '2');
            $("#npwp").attr("Value", '');
            $("#rekanan").combogrid("setValue", '');
            $("#bank1").combogrid("setValue", '');
            $("#rekening").attr("Value", '');
            document.getElementById("p1").innerHTML = "";
            document.getElementById("no_spp").focus();
            $("#spdi").combogrid("clear");
            $("#nlpj").combogrid("clear");
            $("#notrans").combogrid("setValue", '');

            $("#no1").attr("Value", '');
            $("#no2").attr("Value", '');
            $("#no3").attr("Value", '');
            $("#no4").attr("Value", '');
            $("#no5").attr("Value", '');
            $('#del').linkbutton('enable');
            $('#save').linkbutton('enable');
            //$('#save').linkbutton('disable');
            st_12 = 'baru';
            detail_trans_kosong();

            document.getElementById('rektotal1').value = 0;
            document.getElementById('rektotal').value = 0;
            lcstatus = 'tambah';
            $('#rnospp').linkbutton('enable');
            document.getElementById("p2").innerHTML = "";
        }


        function getRowIndex(target) {
            var tr = $(target).closest('tr.datagrid-row');
            return parseInt(tr.attr('datagrid-row-index'));
        }


        function cetak() {
            var nom = document.getElementById("no_spp").value;
            $("#dialog-modal").dialog('open');
            $("#cspp").combogrid("setValue", nom);
        }


        function keluar() {
            $("#dialog-modal").dialog('close');
        }


        function keluar_no() {
            $("#dialog-modal-tr").dialog('close');
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#spp').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/load_spp_gu',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }


        function section1() {

            $(document).ready(function() {
                $('#section1').click();
            });
            //$("#jns_beban").attr("value",'2');
        }


        function section4() {
            $(document).ready(function() {
                $('#section4').click();
            });
        }


        function section5() {
            $(document).ready(function() {
                $("#dialog-modal-tr").click();
            });
        }

        function tambah_no() {
            judul = 'Input Data No Transaksi';
            $("#dialog-modal-tr").dialog({
                title: judul
            });
            $("#dialog-modal-tr").dialog('open');

            document.getElementById("no_spp").focus();

            if (st_12 == 'baru') {
                $("#no1").attr("value", '');
                $("#no2").attr("value", '');
                $("#no3").attr("value", '');
                $("#no4").attr("value", '');
                $("#no5").attr("value", '');
            }
        }

        function tambah_no2() {
            judul = 'Input Data No Transaksi';
            $("#dialog-modal-tr").dialog({
                title: judul
            });
            $("#dialog-modal-tr").dialog('open');
            document.getElementById("no_spp").focus();

            if (st_12 == 'baru') {
                $("#no1").attr("value", '');
                $("#no2").attr("value", '');
                $("#no3").attr("value", '');
                $("#no4").attr("value", '');
                $("#no5").attr("value", '');
            }
        }


        function list_lpj() {
            $('#nlpj').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/nlpj',
                queryParams: ({
                    dns: kode
                }),
                idField: 'no_lpj',
                textField: 'no_lpj',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_lpj',
                            title: 'No LPJ',
                            width: 50
                        },
                        {
                            field: 'tgl_lpj',
                            title: 'Tanggal',
                            align: 'left',
                            width: 70
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    lpj = rowData.no_lpj;
                }
            });
        }




        function hsimpan() {
            $('#save').linkbutton('disable');
            var a = (document.getElementById('no_spp').value).split(" ").join("");
            var a_hide = document.getElementById('no_spp_hide').value;
            var a_dd = a.substr(11, 6);
            var b = $('#dd').datebox('getValue');
            var c = document.getElementById('jns_beban').value;
            var e = document.getElementById('ketentuan').value;
            var f = $("#rekanan").combogrid("getValue");
            var f1 = document.getElementById('dir').value;
            var nm_rekan = document.getElementById('nama_rekanan').value;
            var g = $("#bank1").combogrid("getValue");
            var h = document.getElementById('npwp').value;
            var i = document.getElementById('rekening').value;
            var j = document.getElementById('nmskpd').value;
            var k = angka(document.getElementById('rektotal1').value);
            var nno1 = document.getElementById('no1').value;
            var nno2 = document.getElementById('no2').value;
            var nno3 = document.getElementById('no3').value;
            var nno4 = document.getElementById('no4').value;
            var nno5 = document.getElementById('no5').value;
            var nolpj = $('#nlpj').combogrid('getValue');

            if (nolpj == '') {
                alert("Nomor LPJ tidak Boleh Kosong");
                exit();
            }
            if (kode == '') {
                alert("Kode SKPD Tidak Boleh Kosong");
                exit();
            }
            if (lcstatus == 'tambah') {
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: a,
                            tabel: 'trhspp',
                            field: 'no_spp'
                        }),
                        url: '<?php echo base_url(); ?>/index.php/spp/cek_simpan_spp',
                        success: function(data) {
                            status_cek = data.pesan;
                            status = data.pesanttd;

                            if (status == 1) {
                                alert("SKPD ini belum membuat Penanda tangan PPTK, Harap Konfirmasi ke Kabid Anggaran");
                                return;
                            }
                            if (status_cek == 1) {
                                alert("Nomor Telah Dipakai!");
                                document.getElementById("nomor").focus();
                                exit();
                            }
                            if (status_cek == 0 || status == 0) {
                                alert("Nomor Bisa dipakai");
                                //---------------------           
                                // kurang nya bulan

                                lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, bank,    nmrekan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,    kd_kegiatan,  nm_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2,  no_bukti3,  no_bukti4,  no_bukti5,  no_spd2,    no_spd3,    no_spd4,    no_lpj, urut      )";
                                lcvalues = "('" + a + "', '" + kode + "', '" + e + "',   '',      '" + spd + "', '" + c + "', '" + g + "', '" + nm_rekan + "',       '" + i + "', '" + h + "', '" + j + "',  '" + b + "', '0',    '',           '',            '" + k + "',  '" + nno1 + "',  '',           '',           '',          '',          '',        '',          '',         '',        '" + nno2 + "', '" + nno3 + "', '" + nno4 + "', '" + nno5 + "', '" + spd2 + "', '" + spd3 + "', '" + spd4 + "' , '" + nolpj + "', '" + a_dd + "' )";
                                // alert(lcvalues);
                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo base_url(); ?>/index.php/spp/simpan_tukd_spp',
                                        data: ({
                                            tabel: 'trhspp',
                                            kolom: lcinsert,
                                            nilai: lcvalues,
                                            cid: 'no_spp',
                                            lcid: a
                                        }),
                                        dataType: "json",
                                        beforeSend: function(xhr) {
                                            $("#loading").dialog('open');
                                        },
                                        success: function(data) {
                                            status = data;
                                            if (status == '0') {
                                                alert('Gagal Simpan..!!');
                                                exit();
                                            } else if (status == '1') {
                                                alert('Data Sudah Ada...!!!');
                                                exit();
                                            } else {
                                                //simpan detail
                                                $('#dg1').datagrid('selectAll');
                                                var rows = $('#dg1').datagrid('getSelections');
                                                for (var i = 0; i < rows.length; i++) {
                                                    cidx = rows[i].idx;
                                                    cnobukti1 = rows[i].no_bukti;
                                                    ckdgiat = rows[i].kdsubkegiatan;
                                                    cnmgiat = rows[i].nmkegiatan;
                                                    ckdrek = rows[i].kdrek6;
                                                    cnmrek = rows[i].nmrek6;
                                                    csumber = rows[i].sumber;
                                                    cnilai = angka(rows[i].nilai1);
                                                    no = i + 1;
                                                    if (i > 0) {
                                                        csql = csql + "," + "('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + cnmgiat + "','" + no + "','" + spd + "','" + cnobukti1 + "','" + csumber + "')";
                                                    } else {
                                                        csql = "values('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + cnmgiat + "','" + no + "','" + spd + "','" + cnobukti1 + "','" + csumber + "')";
                                                    }
                                                }
                                                $(document).ready(function() {
                                                    //alert(csql);
                                                    //exit();
                                                    $.ajax({
                                                        type: "POST",
                                                        dataType: 'json',
                                                        data: ({
                                                            no: a,
                                                            sql: csql
                                                        }),
                                                        url: '<?php echo base_url(); ?>/index.php/spp/dsimpan_gu',
                                                        success: function(data) {
                                                            status = data.pesan;
                                                            if (status == '1') {
                                                                $("#loading").dialog('close');
                                                                alert('Data Berhasil Tersimpan...!!!');
                                                                $("#no_spp_hide").attr("value", a);
                                                                lcstatus = 'edit';
                                                            } else {
                                                                $("#loading").dialog('close');
                                                                lcstatus = 'tambah';
                                                                alert('Detail Gagal Tersimpan...!!!');
                                                            }
                                                        }
                                                    });
                                                });
                                            }
                                        }
                                    });
                                });
                                //-----    
                            }
                        }
                    });
                });



            } else {
                //alert(z);
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: a,
                            tabel: 'trhspp',
                            field: 'no_spp'
                        }),
                        url: '<?php echo base_url(); ?>/index.php/spp/cek_simpan_spp',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1 && a != a_hide) {
                                alert("Nomor Telah Dipakai!");
                                exit();
                            }
                            if (status_cek == 0 || a == a_hide) {
                                alert("Nomor Bisa dipakai");
                                //-------     
                                //lcquery = " UPDATE trhspp SET kd_skpd='"+kode+"', keperluan='"+e+"', bulan='"+d+"', no_spd='"+spd+"', jns_spp='"+c+"', bank='"+g+"', nmrekan='"+f+"', no_rek='"+i+"', npwp='"+h+"', nm_skpd='"+j+"', tgl_spp='"+b+"', status='0', nilai='"+k+"', kd_kegiatan='"+kegi+"', nm_kegiatan='"+l+"', kd_program='"+m+"', nm_program='"+n+"', pimpinan='"+f1+"', no_tagih='"+p+"', tgl_tagih='"+q+"', sts_tagih='"+o+"', no_spp='"+a+"' where no_spp='"+a_hide+"' "; 
                                lcquery = " UPDATE trhspp SET no_spp='" + a + "', kd_skpd='" + kode + "', keperluan='" + e + "',  no_spd='" + spd + "',  jns_spp='" + c + "', bank='" + g + "', nmrekan='" + nm_rekan + "', no_rek='" + i + "', npwp='" + h + "', nm_skpd='" + j + "', tgl_spp='" + b + "', status='0', nilai='" + k + "', no_bukti='" + nno1 + "',no_lpj='" + nolpj + "' where no_spp='" + a_hide + "' ";

                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo base_url(); ?>/index.php/spp/update_tukd',
                                        data: ({
                                            st_query: lcquery,
                                            tabel: 'trhspp',
                                            cid: 'no_spp',
                                            lcid: a,
                                            lcid_h: a_hide
                                        }),
                                        dataType: "json",
                                        beforeSend: function(xhr) {
                                            $("#loading").dialog('open');
                                        },
                                        success: function(data) {
                                            status = data;

                                            if (status == '1') {
                                                alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                                                exit();
                                            }

                                            if (status == '2') {
                                                //simpan detail
                                                $('#dg1').datagrid('selectAll');
                                                var rows = $('#dg1').datagrid('getSelections');
                                                for (var i = 0; i < rows.length; i++) {
                                                    cidx = rows[i].idx;
                                                    cnobukti1 = rows[i].no_bukti;
                                                    ckdgiat = rows[i].kdsubkegiatan;
                                                    cnmgiat = rows[i].nmkegiatan;
                                                    ckdrek = rows[i].kdrek6;
                                                    cnmrek = rows[i].nmrek6;
                                                    csumber = rows[i].sumber;
                                                    cnilai = angka(rows[i].nilai1);
                                                    no = i + 1;
                                                    if (i > 0) {
                                                        csql = csql + "," + "('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + cnmgiat + "','" + no + "','" + spd + "','" + cnobukti1 + "','" + csumber + "')";
                                                    } else {
                                                        csql = "values('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + cnmgiat + "','" + no + "','" + spd + "','" + cnobukti1 + "','" + csumber + "')";
                                                    }
                                                }
                                                $(document).ready(function() {
                                                    //alert(csql);
                                                    //exit();
                                                    $.ajax({
                                                        type: "POST",
                                                        dataType: 'json',
                                                        data: ({
                                                            no: a,
                                                            sql: csql,
                                                            no_hide: a_hide
                                                        }),
                                                        url: '<?php echo base_url(); ?>/index.php/spp/dsimpan_gu_edit',
                                                        success: function(data) {
                                                            status = data.pesan;
                                                            if (status == '1') {
                                                                $("#loading").dialog('close');
                                                                alert('Data Berhasil Tersimpan...!!!');
                                                                $("#no_spp_hide").attr("value", a);
                                                                lcstatus = 'edit';
                                                            } else {
                                                                $("#loading").dialog('close');
                                                                lcstatus = 'tambah';
                                                                alert('Detail Gagal Tersimpan...!!!');
                                                            }
                                                        }
                                                    });
                                                });
                                            }

                                            if (status == '0') {
                                                alert('Gagal Simpan...!!!');
                                                exit();
                                            }
                                        }
                                    });
                                });
                            }
                            //---------
                        }

                    });
                });

            }
            list_lpj();
            $('#save').linkbutton('enable');
        }


        function dsimpan() {
            var a = document.getElementById('no_spp').value;
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        cno_spp: a
                    }),
                    dataType: "json",
                    url: "<?php echo base_url(); ?>index.php/spp/dsimpan_spp"
                });
            });
        }


        function detsimpan() {
            $('#save').linkbutton('disable');
            var a = (document.getElementById('no_spp').value).split(" ").join("");
            var a_hide = document.getElementById('no_spp_hide').value.split(" ").join("");
            var nolpj = $('#nlpj').combogrid('getValue');

            $(document).ready(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/spp/dsimpan_hapus",
                    data: ({
                        cno_spp: a_hide,
                        lcid: a,
                        lcid_h: a_hide
                    }),
                    dataType: "json",
                    success: function(data) {
                        status = data;
                        if (status == '0') {
                            alert('Gagal Hapus Detail Old');
                            exit();
                        }
                    }
                });
            });

            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');

            for (var i = 0; i < rows.length; i++) {

                cidx = rows[i].idx;
                cnobukti1 = rows[i].no_bukti;
                ckdgiat = rows[i].kdsubkegiatan;
                cnmgiat = rows[i].nmkegiatan;
                ckdrek = rows[i].kdrek6;
                cnmrek = rows[i].nmrek6;
                csumber = rows[i].sumber;
                cnilai = angka(rows[i].nilai1);

                no = i + 1;

                $(document).ready(function() {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo base_url(); ?>index.php/spp/dsimpan_gu",
                        data: ({
                            cno_spp: a,
                            cno_spphide: a_hide,
                            cskpd: kode,
                            cgiat: ckdgiat,
                            crek: ckdrek,
                            ngiat: cnmgiat,
                            nrek: cnmrek,
                            nilai: cnilai,
                            kd: no,
                            no_bukti1: cnobukti1,
                            nolpj: nolpj,
                            sumber: csumber
                        }),
                        dataType: "json"
                    });
                });
            }

            $("#no_spp_hide").attr("Value", a);
            $('#dg1').edatagrid('unselectAll');
            $('#save').linkbutton('disable');


        }


        function hhapus() {
            var nolpj = $('#nlpj').combogrid('getValue');
            var spp = document.getElementById("no_spp_hide").value;
            var urll = '<?php echo base_url(); ?>/index.php/spp/hhapus_gu';
            if (spp != '') {
                var del = confirm('Anda yakin akan menghapus SPP ' + spp + '  ?');
                alert('No LPJ :' + nolpj);
                if (del == true) {
                    $(document).ready(function() {
                        $.post(urll, ({
                            no: spp,
                            nolpj: nolpj
                        }), function(data) {
                            status = data;
                            list_lpj();
                        });
                    });
                }
            }
        }


        function kembali() {
            $('#kem').click();
        }


        function load_sum_sppp() {
            var nom = document.getElementById('no_spp').value;
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/spp/load_sum_spp",
                    data: ({
                        spp: nom
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            //$("#rektotal").attr("value",n['rektotal']);
                            //$("#rektotal1").attr("value",n['rektotal1']);
                            $("#rektotal").attr('value', number_format(n['rektotal'], 2, '.', ','));
                            $("#rektotal1").attr('value', number_format(n['rektotal'], 2, '.', ','));
                        });
                    }
                });
            });
        }


        function load_sum_tran() {
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        no_bukti: no_bukti
                    }),
                    url: "<?php echo base_url(); ?>index.php/spp/load_sum_tran",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            //$("#rektotal").attr("value",n['rektotal']);
                            //$("#rektotal1").attr("value",n['rektotal1']);
                            $("#rektotal").attr('value', number_format(n['rektotal'], 2, '.', ','));
                            $("#rektotal1").attr('value', number_format(n['rektotal'], 2, '.', ','));

                        });
                    }
                });
            });
        }


        function tombol(st) {
            if (st == 1) {
                $('#save').linkbutton('disable');
                $('#del').linkbutton('disable');
                $('#rnospp').linkbutton('disable');
                $('#batal').linkbutton('disable');
                document.getElementById("p1").innerHTML = "Sudah di Buat SPM...!!!";
            } else {

                $('#save').linkbutton('disable');
                $('#rnospp').linkbutton('enable');
                $('#del').linkbutton('enable');
                $('#batal').linkbutton('enable');
                document.getElementById("p1").innerHTML = "";
            }
        }

        function seting_tombol() {
            $('#tambah').linkbutton('disable');
            $('#save').linkbutton('disable');
            $('#del').linkbutton('disable');
            document.getElementById("p1").innerHTML = "Batas Pembuatan SPP GU sudah selesai";
        }

        function openWindow(url) {
            var spasi = document.getElementById('spasi').value;
            var tanpa = document.getElementById('tanpa_tanggal').checked;
            var nomer = $("#cspp").combogrid('getValue');
            var jns = document.getElementById('jns_beban').value;
            var no = nomer.split("/").join("123456789");
            var ttd1 = $("#ttd1").combogrid('getValue');
            var ttd2 = $("#ttd2").combogrid('getValue');
            var ttd4 = $("#ttd4").combogrid('getValue');

            if (tanpa == false) {
                tanpa = 0;
            } else {
                tanpa = 1;
            }



            if (ttd1 == '') {
                alert("Bendahara Pengeluaran tidak boleh kosong!");
                exit();
            }
            if (ttd2 == '') {
                alert("PPTK tidak boleh kosong!");
                exit();
            }
            if (ttd4 == '') {
                alert("PPKD tidak boleh kosong!");
                exit();
            }
            var ttd_1 = ttd1.split(" ").join("123456789");
            var ttd_2 = ttd2.split(" ").join("123456789");
            var ttd_4 = ttd4.split(" ").join("123456789");

            window.open(url + '/' + no + '/' + kode + '/' + jns + '/' + ttd_1 + '/' + ttd_2 + '/' + ttd_4 + '/' + spasi + '/' + tanpa, '_blank');
            window.focus();
        }

        function cetak_spp(url) { //belum
            var spasi = document.getElementById('spasi').value;
            var nomer = $("#cspp").combogrid('getValue');
            var jns = document.getElementById('jns_beban').value;
            var no = nomer.split("/").join("123456789");
            var ttd1 = $("#ttd1").combogrid('getValue');
            var ttd2 = $("#ttd2").combogrid('getValue');
            var ttd3 = $("#ttd3").combogrid('getValue');
            var ttd4 = $("#ttd4").combogrid('getValue');
            var ttdppk = '0';
            var tanpa = document.getElementById('tanpa_tanggal').checked;
            if (tanpa == false) {
                tanpa = 0;
            } else {
                tanpa = 1;
            }
            if (ttd1 == '') {
                alert("Bendahara Pengeluaran tidak boleh kosong!");
                exit();
            }
            if (ttd2 == '') {
                alert("PPTK tidak boleh kosong!");
                exit();
            }
            // if ( ttd4 =='' ){
            //     alert("PPKD tidak boleh kosong!");
            //     exit();
            // }
            var ttd_1 = ttd1.split(" ").join("123456789");
            var ttd_2 = ttd2.split(" ").join("123456789");
            var ttd_3 = ttd3.split(" ").join("123456789");
            var ttd_4 = ttd4.split(" ").join("123456789");

            window.open(url + '/' + no + '/' + kode + '/' + jns + '/' + ttd_1 + '/' + ttd_2 + '/' + ttd_4 + '/' + spasi + '/' + tanpa + '/' + ttd_3 + '/' + ttdppk, '_blank');
            window.focus();
        }

        function cetak_spp_2(url) {
            var spasi = document.getElementById('spasi').value;
            var nomer = $("#cspp").combogrid('getValue');
            var jns = document.getElementById('jns_beban').value;
            var no = nomer.split("/").join("123456789");
            var ttd3 = $("#ttd3").combogrid('getValue');
            var tanpa = document.getElementById('tanpa_tanggal').checked;
            if (tanpa == false) {
                tanpa = 0;
            } else {
                tanpa = 1;
            }
            if (ttd3 == '') {
                alert("Bendahara Pengeluaran tidak boleh kosong!");
                exit();
            }

            var ttd_3 = ttd3.split(" ").join("123456789");

            // window.open(url+'/'+no+'/'+kode+'/'+jns+'/'+ttd_3+'/'+tanda, '_blank');
            window.open(url + '/' + no + '/' + kode + '/' + jns + '/' + ttd_3 + '/' + tanpa + '/' + spasi, '_blank');
            window.focus();
        }


        function detail_trans_3() {
            var nomer = document.getElementById('no_spp').value;
            $(function() {
                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                    queryParams: ({
                        spp: nomer
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "false",
                    singleSelect: "true",
                    nowrap: "true",
                    onLoadSuccess: function(data) {},
                    onSelect: function(rowIndex, rowData) {
                        kd = rowIndex;
                        idx = rowData.idx;
                    },
                    columns: [
                        [{
                                field: 'idx',
                                title: 'idx',
                                width: 100,
                                align: 'left',
                                hidden: 'true'
                            },
                            {
                                field: 'no_bukti',
                                title: 'No Bukti',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'kdsubkegiatan',
                                title: 'Kegiatan',
                                width: 150,
                                align: 'left'
                            },
                            {
                                field: 'kdrek6',
                                title: 'Rekening',
                                width: 70,
                                align: 'left'
                            },
                            {
                                field: 'nmrek6',
                                title: 'Nama Rekening',
                                width: 230
                            },
                            {
                                field: 'nilai1',
                                title: 'Nilai',
                                width: 140,
                                align: 'right'
                            },
                            {
                                field: 'sumber',
                                title: 'Sumber',
                                width: 500,
                                align: 'right'
                            }

                            //					 ,
                            //                    {field:'hapus',title:'',width:35,align:"center",
                            //                    formatter:function(value,rec){ 
                            //                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                            //                    }
                            //                    }


                        ]
                    ]
                });
            });
        }


        function detail_trans_kosong() {
            var no_kos = '';
            $(function() {
                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                    queryParams: ({
                        spp: no_kos
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "false",
                    singleSelect: "true",
                    nowrap: "true",
                    columns: [
                        [{
                                field: 'idx',
                                title: 'idx',
                                width: 100,
                                align: 'left',
                                hidden: 'true'
                            },
                            {
                                field: 'no_bukti',
                                title: 'No Bukti',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'kdsubkegiatan',
                                title: 'Kegiatan',
                                width: 150,
                                align: 'left'
                            },
                            {
                                field: 'kdrek6',
                                title: 'Rekening',
                                width: 70,
                                align: 'left'
                            },
                            {
                                field: 'nmrek6',
                                title: 'Nama Rekening',
                                width: 230
                            },
                            {
                                field: 'nilai1',
                                title: 'Nilai',
                                width: 140,
                                align: 'right'
                            },
                            {
                                field: 'sumber',
                                title: 'Sumber',
                                width: 50,
                                align: 'right'
                            }

                            //					 ,
                            //                    {field:'hapus',title:'',width:35,align:"center",
                            //                    formatter:function(value,rec){ 
                            //                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                            //                    }
                            //                    }
                        ]
                    ]
                });
            });
        }



        function masuk_grid() {

            var i = 0;
            var ctotal_spp = document.getElementById('rektotal').value;

            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data_tran_4',
                    data: ({
                        no_bukti1: vnobukti
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            xnobukti = n['no_bukti'];
                            xgiat = n['kdsubkegiatan'];
                            xkdrek6 = n['kdrek6'];
                            xnmrek6 = n['nmrek6'];
                            xnilai = n['nilai1'];
                            xsumber = n['sumber'];

                            ctotal_spp = angka(ctotal_spp) + angka(xnilai);

                            if (i == 0) {
                                nidx = nidx + i + 1
                            } else {
                                nidx = nidx + i
                            }
                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: xnobukti,
                                kdsubkegiatan: xgiat,
                                kdrek6: xkdrek6,
                                nmrek6: xnmrek6,
                                nilai1: xnilai,
                                sumber: xsumber,
                                idx: nidx
                            });
                            $('#dg1').edatagrid('unselectAll');
                            $('#rektotal').attr('value', number_format(ctotal_spp, 0, '.', ','));
                        });
                    }
                });
            });
        }


        function filter_nobukti() {
            var vvnobukti = '';
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelections');
            for (var i = 0; i < rows.length; i++) {
                vvnobukti = vvnobukti + "A" + rows[i].no_bukti + "A";
                if (i < rows.length && i != rows.length - 1) {
                    vvnobukti = vvnobukti + 'B';
                }
            }
            $('#dg1').datagrid('unselectAll');
            $('#notrans').combogrid({
                url: '<?php echo base_url(); ?>index.php/spp/select_data_tran_5',
                queryParams: ({
                    no_bukti1: vvnobukti
                })
            });
        }


        function set_grid() {
            $('#dg1').edatagrid({
                columns: [
                    [{
                            field: 'idx',
                            title: 'idx',
                            width: 100,
                            align: 'left',
                            hidden: 'true'
                        },
                        {
                            field: 'no_bukti',
                            title: 'No Bukti',
                            width: 100,
                            align: 'left'
                        },
                        {
                            field: 'kdsubkegiatan',
                            title: 'Kegiatan',
                            width: 150,
                            align: 'left'
                        },
                        {
                            field: 'kdrek6',
                            title: 'Rekening',
                            width: 70,
                            align: 'left'
                        },
                        {
                            field: 'nmrek6',
                            title: 'Nama Rekening',
                            width: 230
                        },
                        {
                            field: 'nilai1',
                            title: 'Nilai',
                            width: 140,
                            align: 'right'
                        },
                        {
                            field: 'sumber',
                            title: 'Sumber',
                            width: 50,
                            align: 'right'
                        }


                        //					 ,
                        //                    {field:'hapus',title:'',width:35,align:"center",
                        //                    formatter:function(value,rec){ 
                        //                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                        //                    }
                        //                    }
                    ]
                ]
            });
        }


        function hapus_detail() {

            var a = document.getElementById('no_spp').value;
            var rows = $('#dg1').edatagrid('getSelected');
            var ctotal_spp = document.getElementById('rektotal').value;

            bbukti = rows.no_bukti;
            bkdrek = rows.kdrek6;
            bkdsubkegiatan = rows.kdsubkegiatan;
            bnilai = rows.nilai1;
            ctotal_spp = angka(ctotal_spp) - angka(bnilai);

            var idx = $('#dg1').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, No Bukti :  ' + bbukti + '  Rekening :  ' + bkdrek + '  Nilai :  ' + bnilai + ' ?');

            if (tny == true) {

                $('#rektotal').attr('value', number_format(ctotal_spp, 2, '.', ','));
                $('#dg1').datagrid('deleteRow', idx);
                $('#dg1').datagrid('unselectAll');

                var urll = '<?php echo base_url(); ?>index.php/spp/dsimpan_spp';
                $(document).ready(function() {
                    $.post(urll, ({
                        cnospp: a,
                        ckdgiat: bkdsubkegiatan,
                        ckdrek: bkdrek,
                        cnobukti: bbukti
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
            }
        }


        function detail_plj() {
            var cnlpj = $('#nlpj').combogrid('getValue');
            $(function() {
                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/load_data_transaksi',
                    queryParams: ({
                        nolpj: cnlpj
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "false",
                    singleSelect: "true",
                    nowrap: "true",
                    onLoadSuccess: function(data) {},
                    onSelect: function(rowIndex, rowData) {
                        kd = rowIndex;
                        idx = rowData.idx;
                    },
                    columns: [
                        [{
                                field: 'idx',
                                title: 'idx',
                                width: 100,
                                align: 'left',
                                hidden: 'true'
                            },
                            {
                                field: 'no_bukti',
                                title: 'No Bukti',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'kdsubkegiatan',
                                title: 'Kegiatan',
                                width: 150,
                                align: 'left'
                            },
                            {
                                field: 'kdrek6',
                                title: 'Rekening',
                                width: 70,
                                align: 'left'
                            },
                            {
                                field: 'nmrek6',
                                title: 'Nama Rekening',
                                width: 230
                            },
                            {
                                field: 'nilai1',
                                title: 'Nilai',
                                width: 140,
                                align: 'right'
                            },
                            {
                                field: 'sumber',
                                title: 'Sumber',
                                width: 50,
                                align: 'right'
                            }

                        ]
                    ]
                });
            });

        }

        function load_data(clpj) {


            var ntotal_trans = document.getElementById('rektotal').value;
            ntotal_trans = angka(ntotal_trans);

            var skpd = document.getElementById('dn').value;

            var cnlpj = $('#nlpj').combogrid('getValue');



            $(document).ready(function() {

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/spp/load_data_transaksi',
                    data: ({
                        kdskpd: skpd,
                        nolpj: cnlpj
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            xnobukti = n['no_bukti'];
                            xgiat = n['kdsubkegiatan'];
                            xkdrek6 = n['kdrek6'];
                            xnmrek6 = n['nmrek6'];
                            xnilai = n['nilai1'];
                            xsumber = n['sumber'];



                            ntotal_trans = ntotal_trans + angka(xnilai);

                            $('#dg1').edatagrid('appendRow', {
                                no_bukti: xnobukti,
                                kdsubkegiatan: xgiat,
                                kdrek6: xkdrek6,
                                nmrek6: xnmrek6,
                                nilai1: xnilai,
                                sumber: xsumber,
                                idx: i
                            });
                            $('#dg1').edatagrid('unselectAll');
                            $('#rektotal').attr('value', number_format(ntotal_trans, 2, '.', ','));
                        });
                    }
                });
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


        function get_spp() {
            $("#no_spp").attr("value", '');
            var jns = "GU";
            var kode = document.getElementById('dn').value;
            var bulan = document.getElementById('bulan').value;
            var jns2 = document.getElementById('jns_beban').value;

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spp/config_spp/' + bulan + '/' + jns2 + '/' + jns,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    no_spp = data.nomor;
                    var inisial = '13.07/02.0/' + no_spp + "/" + jns + "/" + kode + "/M/" + bulan + '/' + tahun_anggaran;
                    $("#no_spp").attr('disabled', true);
                    $("#no_spp").attr("value", inisial);
                    $("#dd_spp").attr("value", no_spp);
                }
            });
        }


        function form_batal() {
            $("#no_spp_batal").attr('disabled', true);
            $("#no_spp_batal").attr("value", document.getElementById("no_spp").value);

            $("#dialog-batal").dialog('open');
        }

        function keluar_batal() {
            $("#dialog-batal").dialog('close');
        }

        function batal() {
            var no_spp = document.getElementById("no_spp_batal").value;
            var ket = document.getElementById("ket_batal").value;
            if (no_spp != '') {
                var del = confirm('Anda yakin akan Membatalkan SPP: ' + no_spp + '  ?');
                if (del == true) {
                    if (ket == '') {
                        alert('Keterangan harus diisi');
                        exit();
                    }


                    $(document).ready(function() {
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>/index.php/spp/batal_spp',
                            data: ({
                                nospp: no_spp,
                                ket: ket
                            }),
                            dataType: "json",
                            success: function(data) {
                                status = data;
                                if (status == '1') {
                                    keluar_batal();
                                    alert('SPP Berhasil Dibatalkan');
                                } else {
                                    keluar_batal();
                                    alert('SPP Gagal Dibatalkan');
                                }
                            }
                        });
                    });
                }
            }
        }
    </script>

    <STYLE TYPE="text/css">
        input.right {
            text-align: right;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 160px;
            background-color: black;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 5px 0;

            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            top: -5px;
            left: 105%;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
        }
    </STYLE>

</head>

<body>

    <div id="content">
        <div id="accordion" style="width:970px;height:970px;">
            <h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List SPP GU</a></h3>
            <div>
                <!--<font color=red><B> Batas Pembuatan SPP GU sudah selesai! </B></font>-->

                <p align="right">
                    <a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:section1();kosong();">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                    <input type="text" value="" id="txtcari" />
                <table id="spp" title="List SPP" style="width:910px;height:450px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section1">Input SPP GU</a></h3>

            <div style="height: 350px;">
                <p id="p1" style="font-size: x-large;color: red;"></p>
                <p id="p2" style="font-size: x-large;color: red;"></p>
                <p>

                <fieldset style="width:850px;height:850px;border-color: white;border-style:hidden;border-spacing:0; ">
                    <table border='0' style="font-size:9px" cellspacing="0">

                        <tr style="height: 10px;">
                            <td width='20%'>No SPP</td>
                            <td><input type="text" name="no_spp" id="no_spp" style="width:300px" onkeyup="this.value=this.value.toUpperCase()" />
                                <input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px" />
                            </td>
                            <td>Tanggal</td>
                            <td>&nbsp;<input id="dd" name="dd" type="text" style="width:95px" /><input type="hidden" id="dd_spp" name="dd_spp" /></td><input type="hidden" id="bulan" name="bulan" /></td>
                        </tr>

                        <tr style="height: 10px;">

                            <td width='20%'>SKPD</td>
                            <td width="30%">
                                <input id="dn" name="dn" readonly="true" style="width:130px; border: 0; " />
                                <input name="nmskpd" type="text" id="nmskpd" size="60" readonly="true" style="border: 0;" />
                            </td>
                            <td width='15%'>Beban</td>
                            <td width="35%"><select name="jns_beban" id="jns_beban">
                                    <option value="2">GU</option>
                                </select></td>

                        </tr>

                        <tr style="height: 5px;">
                            <td width='20%'>No SPD</td>
                            <td width='30%'><input id="spdi" name="spdi" style="width:300px" /></td>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekanan/Bendahara</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                                <input id="rekanan" name="rekanan" style="width:100px" />
                                <input id="nama_rekanan" name="nama_rekanan" style="width:190px" />
                                <input id="dir" name="dir" style="width:190px" />
                            </td>
                        </tr>

                        <tr style="height: 5px;">
                            <td width='15%'>Keperluan</td>
                            <td width='35%' colspan='4'><textarea name="ketentuan" id="ketentuan" cols="80" rows="1"></textarea></td>
                        </tr>

                        <tr style="height: 5px;">

                            <td width='20%'>NPWP</td>
                            <td width='30%'><input type="text" name="npwp" id="npwp" value="" style="width:225px" /></td>

                            <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">BANK</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="bank1" id="bank1" />
                                &nbsp;<input type="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:150" /></td>

                        </tr>

                        <tr style="height: 30px;">

                            <td width='20%'>No LPJ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td width='30%'><input id="nlpj" name="nlpj" style="width:225px" /></td>


                            <td width='15%'>Rekening</td>
                            <td width='35%'>&nbsp;<input type="text" name="rekening" id="rekening" value="" style="width:200px" /></td>
                        </tr>

                        <!--<tr style="height: 10px;">
    <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
    <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
    <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
    <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
 </tr>-->

                        <tr style="height: 30px;">

                            <!--<td colspan="1">
          <div align="left">
          </div>
      </td>-->

                            <td colspan="4">
                                <div align="right">
                                    <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>-->
                                    <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:hsimpan();">Simpan</a>
                                    <!--<a id="del"class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section4();">Hapus</a>-->
                                    <a id="batal" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:form_batal();">Batal SPP</a>
                                    <a id="cetak" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>
                                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section4();">Kembali</a>
                                </div>
                            </td>
                        </tr>

                        <!--<tr style="height: 10px;">
       <td>No Transaksi</td>
       <td><input type="text" name="notrans" id="notrans"  value="" style="width:200px"/></td>
       
       <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
       <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
       <td width='15%'>&nbsp;&nbsp;&nbsp;</td>
       <td width='35%'>&nbsp;&nbsp;&nbsp;</td>
  </tr>-->


                        <!-- <tr>
 

   <td colspan="4"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	  <div align="left">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="load" style="width:70px" class="easyui-linkbutton" iconCls="icon-add" plain="true"  onclick="javascript:load_data();" >Tampil</a>
		            <a id="load_kosong" style="width:70px" class="easyui-linkbutton" iconCls="icon-remove" plain="true"  onclick="javascript:detail_trans_kosong();" >Kosong</a>
       </div> 
		
		</td> 
 
</tr>-->



                    </table>

                    <table id="dg1" title="Input Detail SPP" style="width:900%;height:300%;">
                    </table>


                    <table border='0' style="width:100%;height:5%;">
                        <td width='34%'></td>
                        <td width='35%'><input class="right" type="hidden" name="rektotal1" id="rektotal1" style="width:140px" align="right" readonly="true"></td>
                        <td width='6%'><B>Total</B></td>
                        <td width='25%'><input class="right" type="text" name="rektotal" id="rektotal" style="width:140px" align="right" readonly="true"></td>
                    </table>

                    </p>

                </fieldset>
            </div>
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

    <div id="dialog-modal" title="CETAK SPP">

        <p class="validateTips">SILAHKAN PILIH SPP</p>
        <fieldset>
            <table>
                <tr>
                    <td width="110px">NO SPP:</td>
                    <td><input id="cspp" name="cspp" style="width: 170px;" disabled /> &nbsp; &nbsp; &nbsp; <input type="checkbox" id="tanpa_tanggal"> Tanpa Tanggal</td>
                </tr>

                <tr>
                    <td width="110px">Bend. Pengeluaran:</td>
                    <td><input id="ttd1" name="ttd1" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd1" name="nmttd1" style="width: 170px;border:0" /></td>
                </tr>
                <tr>
                    <td width="110px">PPTK:</td>
                    <td><input id="ttd2" name="ttd2" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd2" name="nmttd2" style="width: 170px;border:0" /></td>
                </tr>
                <tr>
                    <td width="110px">PA:</td>
                    <td><input id="ttd3" name="ttd3" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd3" name="nmttd3" style="width: 170px;border:0" /></td>
                </tr>
                <tr>
                    <td width="110px">PPKD:</td>
                    <td><input id="ttd4" name="ttd4" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd4" name="nmttd4" style="width: 170px;border:0" /></td>
                </tr>
                <tr>
                    <td width="110px">SPASI:</td>
                    <td><input type="number" id="spasi" style="width: 100px;" value="1" /></td>
                </tr>
            </table>
        </fieldset>

        <div>
            <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77/1');return false;">
                <font color="white"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;SPP</font>
            </button> &nbsp;&nbsp;
            <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp3/1');return false;">
                <font color="white"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Rincian</font>
            </button>&nbsp;&nbsp;&nbsp;
            <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>index.php/spp/cetakspp4/1');return false;">
                <font color="white"><i class="fa fa-file-pdf-o"></i>&nbsp;&nbsp;Pernyataan</font>
            </button>

        </div>
        <div>
            <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77/0');return false;">
                <font color="white"><i class="fa fa-television"></i> SPP</font>
            </button>
            &nbsp;&nbsp;
            <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp3/0');return false;">
                <font color="white"><i class="fa fa-television"></i> Rincian</font>
            </button>
            &nbsp;&nbsp;
            <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>index.php/spp/cetakspp4/0');return false;">
                <font color="white"><i class="fa fa-television"></i> Pernyataan</font>
            </button>
        </div>

        <!--   <a href="<?php echo site_url(); ?>tukd/cetakspp1/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Pengantar</a>
	<a href="<?php echo site_url(); ?>tukd/cetakspp2/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Ringkasan</a>
	<a href="<?php echo site_url(); ?>tukd/cetakspp3/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
	<a href="<?php echo site_url(); ?>tukd/cetakspp6/1 "class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">SPTB/Kontrak</a>
	<br/>
	<a href="<?php echo site_url(); ?>tukd/cetakspp1/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Pengantar</a>
	<a href="<?php echo site_url(); ?>tukd/cetakspp2/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Ringkasan</a>
	<a href="<?php echo site_url(); ?>tukd/cetakspp3/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false;">Rincian</a>
	<a href="<?php echo site_url(); ?>tukd/cetakspp6/0 "class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_spp_2(this.href);return false;">SPTB/Kontrak</a>
	&nbsp;&nbsp;&nbsp;<a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>   -->

    </div>

    <div id="dialog-modal-tr" title="">
        <p class="validateTips">Pilih Nomor Transaksi</p>
        <fieldset>
            <table align="center" style="width:100%;" border="0">

                <tr>
                    <td>1. No Transaksi</td>
                    <td></td>
                    <td><input id="no1" name="no1" style="width: 320px;" /> </td>
                </tr>

                <tr>
                    <td>2. No Transaksi</td>
                    <td></td>
                    <td><input id="no2" name="no2" style="width: 320px;" /> </td>
                </tr>

                <tr>
                    <td>3. No Transaksi</td>
                    <td></td>
                    <td><input id="no3" name="no3" style="width: 320px;" /> </td>
                </tr>

                <tr>
                    <td>4. No Transaksi</td>
                    <td></td>
                    <td><input id="no4" name="no4" style="width: 320px;" /> </td>
                </tr>

                <tr>
                    <td>5. No Transaksi</td>
                    <td></td>
                    <td><input id="no5" name="no5" style="width: 320px;" /> </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:detail_trans_2();">Pilih</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_no();">Kembali</a>
                    </td>
                </tr>

            </table>
        </fieldset>
    </div>
    <div id="dialog-batal" title="KETERANGAN PEMBATALAN SPP">
        <p class="validateTips">KETERANGAN PEMBATALAN SPP</p>
        <fieldset>
            <table>
                <tr>
                    <td width="110px">NO SPP:</td>
                    <td><input id="no_spp_batal" name="no_spp_batal" style="width: 170px;" readonly="true" /></td>
                </tr>
                <tr>
                    <td width="110px">KETERANGAN PEMBATALAN SPM:</td>
                    <td><textarea name="ket_batal" id="ket_batal" cols="70" rows="2"></textarea></td>
                </tr>
            </table>
        </fieldset>
        <a id="del1" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:batal();javascript:section1();">BATAL</a>
        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_batal();">Keluar</a>
    </div>
</body>

</html>