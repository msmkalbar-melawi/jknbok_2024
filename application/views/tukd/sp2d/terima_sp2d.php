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

    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <script type="text/javascript">
        var nl = 0;
        var tnl = 0;
        var idx = 0;
        var tidx = 0;
        var oldRek = 0;
        var rek = 0;

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
        });
        $(function() {
            $('#dkasda').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });
        });

        $(function() {
            $('#dkas').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
                }
            });
        });

        $(function() {
            $('#bank1').combogrid({
                panelWidth: 200,
                url: '<?php echo base_url(); ?>/index.php/sp2d/config_bank2',
                idField: 'kd_bank',
                textField: 'kd_bank',
                //mode:'remote',  
                fitColumns: true,
                columns: [
                    [{
                            field: 'kd_bank',
                            title: 'Kd Bank',
                            width: 40
                        },
                        {
                            field: 'nama_bank',
                            title: 'Nama',
                            width: 140
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    //$("#kode").attr("value",rowData.kode);
                    $("#nama_bank").attr("value", rowData.nama_bank);
                }
            });
        });


        $(function() {
            $('#csp2d').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/sp2d/pilih_sp2d',
                idField: 'no_sp2d',
                textField: 'no_sp2d',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_sp2d',
                            title: 'SP2D',
                            width: 60
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            align: 'left',
                            width: 60
                        },
                        {
                            field: 'no_spm',
                            title: 'SPM',
                            width: 60
                        }

                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kode = rowData.no_sp2d;
                    dns = rowData.kd_skpd;
                    val_ttd(dns);
                }
            });
        });

        $(function() {
            $('#cc').combobox({
                url: '<?php echo base_url(); ?>/index.php/sp2d/load_jenis_beban',
                valueField: 'id',
                textField: 'text',
                onSelect: function(rowIndex, rowData) {
                    validate_tombol();
                }
            });
        });

        function val_ttd(dns) {
            $(function() {
                $('#ttd').combogrid({
                    panelWidth: 500,
                    url: '<?php echo base_url(); ?>/index.php/sp2d/pilih_ttd/' + dns,
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
        $(function() {
            $('#sp2d').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sp2d/load_terima_sp2d',
                rowStyler: function(index, row) {
                    if (row.status == 'Sudah diterima') {
                        return 'background-color:#4bbe68;color:white';
                    }
                },
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
                            field: 'no_sp2d',
                            title: 'Nomor SP2D',
                            width: 50
                        },
                        {
                            field: 'no_spm',
                            title: 'Nomor SPM',
                            width: 50
                        },
                        {
                            field: 'tgl_sp2d',
                            title: 'Tanggal',
                            width: 25
                        },
                        {
                            field: 'nm_skpd',
                            title: ' SKPD',
                            width: 50,
                            align: "left"
                        },
                        {
                            field: 'keperluan',
                            title: ' Keperluan',
                            width: 50,
                            align: "left"
                        },
                        {
                            field: 'status',
                            title: 'Status',
                            width: 30,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    no_sp2d = rowData.no_sp2d;
                    no_spm = rowData.no_spm;
                    tgs = rowData.tgl_sp2d;
                    st = rowData.status;
                    nokas = rowData.no_terima;
                    dkas = rowData.dterima;
                    dkasda = rowData.dkasda;
                    nocek = rowData.nocek;
                    status_cair = rowData.status_cair;
                    status_trm = rowData.status_trm;
                    getspm(no_sp2d, no_spm, tgs, st, nokas, dkas, nocek, status_cair, status_trm, dkasda);
                },
                onDblClickRow: function(rowIndex, rowData) {
                    st = rowData.status;
                    section2(st);
                }
            });
        });


        $(function() {
            $('#nospm').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/sp2d/nospm1',
                idField: 'no_spm',
                textField: 'no_spm',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_spm',
                            title: 'No',
                            width: 60
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            align: 'left',
                            width: 80
                        }

                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    no_spm = rowData.no_spm
                    tgspm = rowData.tgl_spm
                    no_spp = rowData.no_spp;
                    dn = rowData.kd_skpd;
                    sp = rowData.no_spd;
                    bl = rowData.bulan;
                    tg = rowData.tgl_spp;
                    jn = rowData.jns_spp;
                    jns_bbn = rowData.jns_beban;
                    kep = rowData.keperluan;
                    np = rowData.npwp;
                    rekan = rowData.nmrekan;
                    bk = rowData.bank;
                    ning = rowData.no_rek;
                    nm = rowData.nm_skpd;
                    get(no_spm, tgspm, no_spp, dn, sp, tg, bl, jn, kep, np, rekan, bk, ning, nm, jns_bbn);

                    detail();
                    pot();
                }
            });
        });





        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sp2d/select_data1',
                autoRowHeight: "true",
                idField: 'id',
                toolbar: "#toolbar",
                rownumbers: "true",
                fitColumns: false,
                singleSelect: "true"

            });
        });



        $(function() {
            $('#pot').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/sp2d/pot',
                autoRowHeight: "true",
                idField: 'id',
                toolbar: "#toolbar",
                rownumbers: "true",
                fitColumns: false,
                singleSelect: "true",

            });
        });



        function detail() {
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/sp2d/select_data1',
                    queryParams: ({
                        spp: no_spp
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "true",
                    singleSelect: false,
                    onLoadSuccess: function(data) {
                        load_sum_spm();
                    },
                    columns: [
                        [{
                                field: 'ck',
                                title: 'ck',
                                checkbox: true,
                                hidden: true
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
                                width: 400
                            },
                            {
                                field: 'nilai1',
                                title: 'Nilai',
                                width: 100,
                                align: 'right'
                            }

                        ]
                    ]

                });


            });
        }

        function detail1() {
            $(function() {
                var no_spp = '';
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/sp2d/select_data1',
                    queryParams: ({
                        spp: no_spp
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "true",
                    singleSelect: false,
                    columns: [
                        [{
                                field: 'ck',
                                title: 'ck',
                                checkbox: true,
                                hidden: true
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
                                width: 400
                            },
                            {
                                field: 'nilai1',
                                title: 'Nilai',
                                width: 100,
                                align: 'right'
                            }

                        ]
                    ]

                });


            });
        }



        function pot() {
            $(function() {
                //alert(no_spm);                         
                $('#pot').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/sp2d/pot',
                    queryParams: ({
                        spm: no_spm
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "true",
                    singleSelect: false,
                    onLoadSuccess: function(data) {
                        load_sum_pot();
                    },
                    columns: [
                        [{
                                field: 'ck',
                                title: 'ck',
                                checkbox: true,
                                hidden: true
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
                                width: 550
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                width: 100,
                                align: 'right'
                            }

                        ]
                    ]

                });


            });
        }

        function pot1() {
            $(function() {
                var no_spm = '';
                $('#pot').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/sp2d/pot',
                    queryParams: ({
                        spm: no_spm
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "true",
                    singleSelect: false,
                    columns: [
                        [{
                                field: 'ck',
                                title: 'ck',
                                checkbox: true,
                                hidden: true
                            },
                            {
                                field: 'kd_rek5',
                                title: 'Rekening',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'nm_rek5',
                                title: 'Nama Rekening',
                                width: 550
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                width: 100,
                                align: 'right'
                            }

                        ]
                    ]

                });


            });
        }

        function get(no_spm, tgspm, no_spp, kd_skpd, no_spd, tgl_spp, bulan, jns_spp, keperluan, npwp, rekanan, bank, rekening, nm_skpd) {

            $("#no_spm").attr("value", no_spm);
            $("#tgl_spm").attr("value", tgspm);
            $("#nospp").attr("value", no_spp);
            $("#dn").attr("value", kd_skpd);
            $("#sp").attr("value", no_spd);
            $("#tgl_spp").attr("value", tgl_spp);
            $("#kebutuhan_bulan").attr("Value", bulan);
            $("#ketentuan").attr("Value", keperluan);
            $("#jns_beban").attr("Value", jns_spp);
            $("#npwp").attr("Value", npwp);
            $("#rekanan").attr("Value", rekanan);
            $("#bank1").combogrid("setValue", bank);
            $("#rekening").attr("Value", rekening);
            $("#nmskpd").attr("Value", nm_skpd);
            validate_jenis_edit(jns_bbn);
        }

        function getspm(no_sp2d, no_spm, tgl_sp2d, status, nokas, dkas, nocek, status_cair, status_trm, dkasda) {
            $("#no_sp2d").attr("value", no_sp2d);
            $("#dd").datebox("setValue", tgl_sp2d);
            $("#dkasda").datebox("setValue", dkasda);
            $("#dkas").datebox("setValue", dkas);
            $("#nocek").attr("Value", nocek);
            $("#nokas").attr("Value", nokas);
            $("#nospm").combogrid("setValue", no_spm);
            if (status_trm == '1') {
                $("#dkas").datebox("setValue", dkas);
            } else {
                $("#dkas").datebox("setValue", dkasda);
            }
            tombol(status);
        }

        function kosong() {
            $("#no_sp2d").attr("value", '');
            $("#dd").datebox("setValue", '');
            $("#nospm").combogrid("setValue", '');
            $("#nospp").attr("value", '');
            $("#dn").attr("value", '');
            $("#sp").attr("value", '');
            $("#tgl_spp").attr("value", '');
            $("#tgl_spm").attr("value", '');
            $("#kebutuhan_bulan").attr("Value", '');
            $("#ketentuan").attr("Value", '');
            $("#jns_beban").attr("Value", '');
            $("#npwp").attr("Value", '');
            $("#rekanan").attr("Value", '');
            $("#bank1").combogrid("setValue", '');
            $("#rekening").attr("Value", '');
            $("#nmskpd").attr("Value", '');
            document.getElementById("p1").innerHTML = "";
            detail1();
            pot1();
            $("#nospm").combogrid("clear");
            //tombolnew();      
        }


        $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();
            $("#frm").hide();
            $("#dialog-modal").dialog({
                height: 200,
                width: 700,
                modal: true,
                autoOpen: false
            });
            get_tahun();
        });

        function cetak() {
            $("#dialog-modal").dialog('open');
        }

        function get_tahun() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/sp2d/config_tahun',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    tahun_anggaran = data;
                }
            });

        }

        function keluar() {
            $("#dialog-modal").dialog('close');
        }

        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#sp2d').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/sp2d/load_terima_sp2d',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function validate_jenis_edit($jns_bbn) {
            var beban = document.getElementById('jns_beban').value;
            $('#cc').combobox({
                url: '<?php echo base_url(); ?>/index.php/sp2d/load_jenis_beban/' + beban,
            });

            $('#cc').combobox('setValue', jns_bbn);
        }


        function simpan_cair() {
            var nokas = document.getElementById('nokas').value;
            var tglcair = $('#dkas').datebox('getValue');
            var tglkasda = $('#dkasda').datebox('getValue');
            var tglsp2d = $('#dd').datebox('getValue');
            var nocek = document.getElementById('nocek').value;
            var total = document.getElementById('total').value;
            var nosp2d = document.getElementById('no_sp2d').value;
            var cskpd = document.getElementById('dn').value;
            var cket = document.getElementById('ketentuan').value;
            var cbeban = document.getElementById('jns_beban').value;
            var tahun_input = tglcair.substring(0, 4);
            if (tahun_input != tahun_anggaran) {
                swal("Gagal Simpan", "Tahun tidak sama dengan Tahun Anggaran", "error");
                exit();
            }

            if (tglsp2d > tglcair) {
                swal("Gagal Simpan", "Tanggal Terima tidak boleh lebih kecil dari tanggal SP2D", "error");
                exit();
            }
            if (tglcair == '') {
                swal("Gagal Simpan", "Tanggal tidak boleh kosong", "error");
                exit();
            }
            if (tglcair != tglkasda) {
                swal("Gagal Simpan", "Tanggal Terima harus sama dengan Tanggal Kasda", "error");
                exit();
            }
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        nkas: nokas,
                        tcair: tglcair,
                        ncek: nocek,
                        tot: total,
                        nsp2d: nosp2d,
                        skpd: cskpd,
                        ket: cket,
                        beban: cbeban
                    }),
                    dataType: "json",
                    url: "<?php echo base_url(); ?>index.php/sp2d/simpan_terima_sp2d",
                    success: function(data) {
                        if (data = 1) {
                            //alert('SP2D Telah Dicairkan');
                        }
                    }
                });
            });
            swal("Sukses", "SP2D Telah Diterima", "success");
            document.getElementById("p1").innerHTML = "SP2D Sudah diterima!!";
            $('#nokas').attr('readonly', true);

        }

        function batal_cair() {
            if (status_cair == '1') {
                swal("Error", "SP2D telah Dicairkan. Silakan batalkan pencairan terlebih dahulu", "error");
                exit();
            }
            var nokas = document.getElementById('nokas').value;
            var tglcair = $('#dkas').datebox('getValue');
            var nocek = document.getElementById('nocek').value;
            var total = document.getElementById('total').value;
            var nosp2d = document.getElementById('no_sp2d').value;

            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        nkas: nokas,
                        tcair: tglcair,
                        ncek: nocek,
                        tot: total,
                        nsp2d: nosp2d
                    }),
                    dataType: "json",
                    url: "<?php echo base_url(); ?>index.php/sp2d/batal_terima",
                    success: function(data) {
                        if (data = 1) {
                            alert('Penerimaan Dibatalkan');
                            $("#nokas").attr("Value", '');
                            $("#dkas").datebox("setValue", '');
                            $("#nocek").attr("Value", '');
                            document.getElementById("p1").innerHTML = " ";
                            get_nourut();
                            $('#nokas').attr('readonly', false);
                            swal("Sukses", "SP2D Telah Dibatalkan", "success");
                        }
                    }
                });
            });
        }


        function hhapus() {
            var sp2d = document.getElementById("no_sp2d").value;
            //var spp = document.getElementById("no_spp").value; 
            alert(sp2d + no_spm);
            var urll = '<?php echo base_url(); ?>/index.php/tukd/hapus_sp2d';
            if (sp2d != '') {
                var del = confirm('Anda yakin akan menghapus SP2D ' + sp2d + '  ?');
                if (del == true) {
                    $(document).ready(function() {
                        $.post(urll, ({
                            no: sp2d,
                            spm: no_spm
                        }), function(data) {
                            status = data;

                        });
                    });

                }
            }
        }



        function load_sum_spm() {
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        spp: no_spp
                    }),
                    url: "<?php echo base_url(); ?>index.php/tukd/load_sum_spm",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#rekspm").attr("value", n['rekspm']);
                            $("#total").attr("value", n['rekspm']);
                        });
                    }
                });
            });
        }

        function load_sum_pot() {
            //var spm = document.getElementById('no_spm').value;              
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        spm: no_spm
                    }),
                    url: "<?php echo base_url(); ?>index.php/tukd/load_sum_pot",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#rektotal").attr("value", n['rektotal']);
                        });
                    }
                });
            });
        }

        function section1() {
            $(document).ready(function() {
                $('#section1').click();
            });
        }

        function section2(st) {

            if (st == 'Sudah diterima') {
                document.getElementById("btcair").value = "BATAL TERIMA";
                $("#btcair").css("background-color", "#ff2003");
            } else {
                get_nourut();
                document.getElementById("btcair").value = "TERIMA SP2D";
                $("#btcair").css("background-color", "#4CAF50");
            }

            $(document).ready(function() {
                $('#section2').click();
            });
        }

        function get_nourut() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/cms/no_urut',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#nokas").attr("value", data.no_urut);
                }
            });
        }

        function section3() {
            $(document).ready(function() {
                $('#section3').click();
            });
        }

        function tombol(st) {
            if (st == 'Sudah diterima') {
                $('#save').linkbutton('disable');
                $('#del').linkbutton('disable');
                $('#poto').linkbutton('disable');
                document.getElementById("p1").innerHTML = "SP2D Sudah diterima!!";
            } else {
                $('#save').linkbutton('enable');
                $('#del').linkbutton('enable');
                $('#poto').linkbutton('enable');
                document.getElementById("p1").innerHTML = "";
            }
        }

        function tombolnew() {

            $('#save').linkbutton('enable');
            $('#del').linkbutton('enable');
            $('#poto').linkbutton('enable');

        }



        function cair() {
            var cap = document.getElementById("btcair").value;
            var nokas = document.getElementById("nokas").value;

            if (nokas == '') {
                alert('Nomor Kas Harus Diisi !!!');
                exit;
            }

            if (cap == 'TERIMA SP2D') {
                simpan_cair();
                document.getElementById("btcair").value = "BATAL TERIMA";
                $("#btcair").css("background-color", "#ff2003");
            } else {
                batal_cair();
                document.getElementById("btcair").value = "TERIMA SP2D";
                $("#btcair").css("background-color", "#4CAF50");
            }
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

            <h3><a href="#" id="section1" onclick="javascript:$('#sp2d').edatagrid('reload')">PENERIMAAN SP2D</a></h3>
            <div>
                <p align="right">
                    <button type="primary" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</button>
                    <input type="text" value="" id="txtcari" />
                <table id="sp2d" title="List SP2D" style="width:870px;height:450px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2" onclick="javascript:$('#dg').edatagrid('reload')">DATA SP2D</a></h3>
            <div style="height: 400px;">
                <p id="p1" style="font-size: x-large;color: red;"></p>
                <p>
                    <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->

                    <button TYPE="edit" ONCLICK="javascript:section1();" style="height:40px;"><i class="fa fa-arrow-left"></i> Kembali</button>
                    <INPUT TYPE="button" name="btcair" id="btcair" VALUE="CAIRKAN" ONCLICK="cair()" style="height:40px;width:100px">



                <table border='0' style="font-size:11px">

                    <tr>
                        <td>No Terima </td>
                        <td><input type="text" name="nokas" id="nokas" style="width:150px"></td>
                        <td>Tgl Terima
                            <input id="dkas" name="dkas" type="text" style="width:155px" />
                            Tgl Kasda
                            <input id="dkasda" name="dkasda" type="text" style="width:155px" disabled />
                            <input id="nocek" name="nocek" type="text" style="width:155px" hidden />
                        </td>
                        <td>Nilai</td>
                        <td colspan="2"><input class="right" type="text" name="total" id="total" style="width:100px" align="right" readonly="true"></td>
                    </tr>
                </table>

                <table border='0' style="font-size:11px">

                    <tr>
                        <td>No SP2D </td>
                        <td><input type="text" name="no_sp2d" id="no_sp2d" readonly="true" style="width:300px" disabled></td>
                        <td>Tgl SP2D </td>
                        <td><input id="dd" name="dd" type="text" readonly="true" style="width:155px" disabled /></td>
                    </tr>
                    <tr>
                        <td>No SPM</td>
                        <td><input type="text" name="nospm" id="nospm" style="width:300px" disabled></td>
                        <td>Tgl SPM </td>
                        <td><input id="tgl_spm" name="tgl_spm" type="text" readonly="true" style="width:150px" disabled /></td>
                    </tr>
                    <tr>
                        <td width="8%">No SPP</td>
                        <td><input id="nospp" name="nospp" readonly="true" style="width:300px" /></td>
                        <td>Tgl SPP </td>
                        <td><input id="tgl_spp" name="tgl_spp" type="text" readonly="true" style="width:150px" /></td>
                    </tr>
                    <tr>
                        <td width='8%'>SKPD</td>
                        <td width="53%">
                            <input id="dn" name="dn" readonly="true" style="width:150px" />
                        </td>
                        <td width='8%'>Bulan</td>
                        <td width="31%"><select name="kebutuhan_bulan" id="kebutuhan_bulan" readonly="true" style="width:150px" disabled>
                                <option value="">...Pilih Kebutuhan Bulan... </option>
                                <option value="1">1 | Januari</option>
                                <option value="2">2 | Februari</option>
                                <option value="3">3 | Maret</option>
                                <option value="4">4 | April</option>
                                <option value="5">5 | Mei</option>
                                <option value="6">6 | Juni</option>
                                <option value="7">7 | Juli</option>
                                <option value="8">8 | Agustus</option>
                                <option value="9">9 | September</option>
                                <option value="10">10 | Oktober</option>
                                <option value="11">11 | November</option>
                                <option value="12">12 | Desember</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td width='8%'>&nbsp;</td>
                        <td width='53%'><textarea name="nmskpd" id="nmskpd" cols="40" rows="1" style="border: 0;" readonly="true"></textarea></td>
                        <td width='8%'>Keperluan</td>
                        <td width='31%'><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" readonly="true"></textarea></td>
                    </tr>
                    <tr>
                        <td width='8%'>No SPD</td>
                        <td><input id="sp" name="sp" style="width:300px" readonly="true" /></td>
                        <td width='8%'>Rekanan</td>
                        <td><textarea id="rekanan" name="rekanan" cols="30" rows="1" readonly="true"> </textarea></td>
                    </tr>

                    <tr>
                        <td>Beban</td>
                        <td><select name="jns_beban" id="jns_beban" readonly="true" style="width:150px" disabled>
                                <option value="">...Pilih Jenis Beban... </option>
                                <option value="1">UP</option>
                                <option value="2">GU</option>
                                <option value="3">TU</option>
                                <option value="4">LS GAJI</option>
                                <option value="5">LS Pihak Ketiga Lainnya</option>
                                <option value="6">LS Barang Jasa</option>
                            </select></td>
                        <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">BANK</td>
                        <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input name="bank1" id="bank1" style="width:50px" />
                            &nbsp;<input type="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:300px" /></td>
                    </tr>
                    <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                        <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Jenis</td>
                        <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input id="cc" name="dept" style="width: 190px;" value=" Pilih Jenis Beban"></td>
                        <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                        <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width='8%'>NPWP</td>
                        <td width='53%'><input type="text" name="npwp" id="npwp" value="" readonly="true" style="width:150px" /></td>
                        <td width='8%'>Rekening</td>
                        <td width='31%'><input type="text" name="rekening" id="rekening" value="" readonly="true" style="width:150px" /></td>
                    </tr>

                </table>
                <table id="dg" title=" Detail SPM" style="width:850%;height:250%;">

                </table>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rekspm" id="rekspm" style="width:140px" align="right" readonly="true"><br />
                <table id="pot" title="List Potongan" style="width:850px;height:150px;">
                </table>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal" style="width:140px" align="right" readonly="true">
                <!-- <?php echo form_close(); ?> -->


                </p>
            </div>




        </div>

    </div>


</body>

</html>