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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />

    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <script>
        var kode = '';
        var kegiatan = '';
        var xrekening = '';
        var xnmkegiatan = '';
        var xkegiatan = '';
        var total_pic = 0;
        var status_anggaran = '';

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#kegi").hide();
            $("#lab1").hide();
            $("#lab2").hide();
            $("#loading").hide();
            proteksi();
            status_angkas();
            document.getElementById("myDIV").style.visibility = "hidden";
        });

        $(function() {
            $('#cc').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka_ro/skpduser',
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
                    $("#kegi").hide();
                    kodeskpd = rowData.kd_skpd;
                    status_anggaran = rowData.jns_ang;
                    $("#ck").combogrid("clear");
                    $("#total_pic2").attr("value", '');
                    $("#nmskpd").attr("value", rowData.nm_skpd);


                    $('#rak').combogrid({
                        panelWidth: 400,
                        idField: 'kode',
                        textField: 'nama',
                        mode: 'remote',
                        url: '<?php echo base_url(); ?>index.php/rka_ro/ambil_rak_angkas/' + status_anggaran,
                        columns: [
                            [
                                // {field:'kode',title:'Kode',width:100},  
                                {
                                    field: 'nama',
                                    title: 'Nama',
                                    width: 400
                                }
                            ]
                        ],
                        onSelect: function(rowIndex, rowData) {
                            rak = rowData.kode;
                            setTimeout(tampil, 500);
                            rek('');
                            cek_status(kodeskpd, 9);
                            cek_status_kunci();
                        }
                    });


                }
            });


        });


        $(function() {
            $('#ck').combogrid({
                panelWidth: 700,
                idField: 'kd_kegiatan',
                textField: 'kd_kegiatan',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka_ro/load_giat',
                columns: [
                    [{
                            field: 'kd_kegiatan',
                            title: 'Kode Kegiatan',
                            width: 180
                        },
                        {
                            field: 'nm_kegiatan',
                            title: 'Nama Kegiatan',
                            width: 520
                        }
                    ]
                ]
            });
        });

        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/rka_ro/ambil_rek_angkas_ro_geser',
                idField: 'id',
                toolbar: "#toolbar",
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                rowStyler: function(index, row) {
                    if (row.selisih == 0) {
                        return 'background-color:#ffffff;';
                    } else {
                        return 'background-color:#ff471a;';
                    }

                },
                columns: [
                    [{
                            field: 'kd_rek5',
                            title: 'Kode Rekening',
                            width: 70
                        },
                        {
                            field: 'nm_rek5',
                            title: 'Nama Rekening',
                            width: 200
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai Anggaran',
                            width: 100,
                            align: "right"
                        },
                        {
                            field: 'nilai_rak',
                            title: 'Nilai RAK',
                            width: 90,
                            align: "right"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    ckd_rek5 = rowData.kd_rek5;
                    xrekening = ckd_rek5;
                    cnm_rek5 = rowData.nm_rek5;
                    cnilai = rowData.nilai;

                    get(ckd_rek5, cnm_rek5, cnilai);
                },
                onDblClickRow: function(rowIndex, rowData) {
                    ckd_rek5 = rowData.kd_rek5;
                    xrekening = ckd_rek5;
                    cnm_rek5 = rowData.nm_rek5;
                    cnilai = rowData.nilai;
                    get(ckd_rek5, cnm_rek5, cnilai);
                    section2();
                }
            });
        });

        function tampil() {
            $("#kegi").show();
            $("#lab1").show();
            $("#lab2").show();
        }


        function proteksi() {
            var user = <?php echo json_encode($this->session->userdata('kdskpd')); ?>;

            if (user == '3.31.3.30.0.00.01.0002') {

                alert('Selamat datang ' + user);
            } else if (user = '2.09.0.00.0.00.01.0000') {
                protectskpdbaru();
            } else {
                protect();
            }
        }

        function protectskpdbaru() {
            var d = new Date();
            var n = d.getMonth();
            var y = d.getFullYear();
            // 0 januari
            // 1 Februari
            // if(n=='0' && y=='2023'){

            // }else 
            if (n == '0' && y == '2023') {
                document.getElementById("jan").disabled = false;
            } else if (n == '1' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
            } else if (n == '2' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;

            } else if (n == '3' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;

            } else if (n == '4' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
            } else if (n == '5' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
                document.getElementById("jun").disabled = false;
            } else if (n == '6' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
                document.getElementById("jun").disabled = false;
                document.getElementById("jul").disabled = false;
            } else if (n == '7' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
                document.getElementById("jun").disabled = false;
                document.getElementById("jul").disabled = false;
                document.getElementById("ags").disabled = false;
            } else if (n == '8' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
                document.getElementById("jun").disabled = false;
                document.getElementById("jul").disabled = false;
                document.getElementById("ags").disabled = false;
                document.getElementById("sep").disabled = false;
            } else if (n == '9' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
                document.getElementById("jun").disabled = false;
                document.getElementById("jul").disabled = false;
                document.getElementById("ags").disabled = false;
                document.getElementById("sep").disabled = false;
                document.getElementById("okt").disabled = false;
            } else if (n == '10' && y == '2023') {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
                document.getElementById("jun").disabled = false;
                document.getElementById("jul").disabled = false;
                document.getElementById("ags").disabled = false;
                document.getElementById("sep").disabled = false;
                document.getElementById("okt").disabled = false;
                document.getElementById("nov").disabled = false;
            } else {
                document.getElementById("jan").disabled = false;
                document.getElementById("feb").disabled = false;
                document.getElementById("mar").disabled = false;
                document.getElementById("apr").disabled = false;
                document.getElementById("mei").disabled = false;
                document.getElementById("jun").disabled = false;
                document.getElementById("jul").disabled = false;
                document.getElementById("ags").disabled = false;
                document.getElementById("sep").disabled = false;
                document.getElementById("okt").disabled = false;
                document.getElementById("nov").disabled = false;
                document.getElementById("des").disabled = false;
            }
        }

        function protect() {
            var d = new Date();
            var n = d.getMonth();
            var y = d.getFullYear();
            // 0 januari
            // 1 Februari
            // if(n=='0' && y=='2023'){

            // }else 
            if (n == '0' && y == '2023') {
                document.getElementById("jan").disabled = true;
            } else if (n == '1' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
            } else if (n == '2' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;

            } else if (n == '3' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;

            } else if (n == '4' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
            } else if (n == '5' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
            } else if (n == '6' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = false;
            } else if (n == '7' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = false;
                document.getElementById("ags").disabled = false;
            } else if (n == '8' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = false;
                document.getElementById("sep").disabled = false;
            } else if (n == '9' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = true;
                document.getElementById("sep").disabled = false;
                document.getElementById("okt").disabled = false;
            } else if (n == '10' && y == '2023') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = true;
                document.getElementById("sep").disabled = false;
                document.getElementById("okt").disabled = false;
                document.getElementById("nov").disabled = false;
            } else {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = true;
                document.getElementById("sep").disabled = true;
                document.getElementById("okt").disabled = false;
                document.getElementById("nov").disabled = false;
                document.getElementById("des").disabled = false;
            }
        }

        function giat(kodeskpd, rak) {

            // var rak = $('#rak').combogrid('getValue');
            // alert(jns_ang);
            $(function() {
                $('#ck').combogrid({
                    panelWidth: 700,
                    idField: 'kd_kegiatan',
                    textField: 'kd_kegiatan',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/rka_ro/load_giat/' + kodeskpd + "/" + rak,
                    columns: [
                        [{
                                field: 'kd_kegiatan',
                                title: 'Kode Sub Kegiatan',
                                width: 150
                            },
                            {
                                field: 'nm_kegiatan',
                                title: 'Nama Sub Kegiatan',
                                width: 520
                            }

                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        kegiatan = rowData.kd_kegiatan;
                        xkegiatan = rowData.kd_kegiatan;
                        xnmkegiatan = rowData.nm_kegiatan;
                        total = rowData.total;
                        total_pic = total;
                        skpdd = rowData.kd_skpd;
                        $("#jumlah").attr("value", total);
                        $("#skpdd").attr("value", skpdd);
                        $("#total_pic2").attr("value", total_pic);
                        $("#nmgiat").attr("value", rowData.nm_kegiatan);
                        $(function() {
                            $.ajax({
                                type: 'POST',
                                data: ({
                                    kegiatan: kegiatan
                                }),
                                url: "<?php echo base_url(); ?>index.php/rka_ro/total_triwulan_geser/nilai_" + rak + "/" + skpdd,
                                dataType: "json",
                                success: function(data) {
                                    $.each(data, function(i, n) {
                                        $("#kd_skpd").attr("value", n['kd_skpd']);
                                        $("#kegiatan_kd").attr("value", n['kegiatan_kd']);
                                        $("#tw1").attr("value", n['tw1']);
                                        $("#tw2").attr("value", n['tw2']);
                                        $("#tw3").attr("value", n['tw3']);
                                        $("#tw4").attr("value", n['tw4']);
                                    });
                                }
                            });
                        });

                        rek(kegiatan);
                    }
                });

            });
        }

        function cek_status(kdskpd) {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd2',
                type: "POST",
                dataType: "json",
                data: ({
                    kdskpd: kdskpd
                }),
                success: function(data) {
                    sta = data.statu;
                    kunci = data.angkas_m;
                    jns_ang = data.jns_ang;
                    $("#jns_ang").attr("value", jns_ang);
                    tombol(sta, kunci);
                    giat(kodeskpd, rak);
                }
            });
        }

        function cek_status_kunci() {
            var rak = $('#rak').combogrid('getValue');
            $.ajax({
                type: 'POST',
                data: ({
                    kunci_rak: rak
                }),
                url: "<?php echo base_url(); ?>index.php/rka_ro/stts_kunci_angkas",
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, n) {
                        stts_kunci = n['kunci_angkas'];
                        // alert(stts_kunci);
                        if (stts_kunci == '1') {
                            document.getElementById("kunci1").disabled = true;
                            document.getElementById("isi").innerHTML = "Inputan dikunci!! Anggaran Kas Sudah disahkan";
                            document.getElementById("myDIV").style.visibility = "visible";
                        } else {
                            document.getElementById("myDIV").style.visibility = "hidden";
                            document.getElementById("kunci1").disabled = false;
                            document.getElementById("isi").innerHTML = "";
                        }
                    });
                }
            });


        }

        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        function kunci_inputan() {
            var kdskpd = $('#cc').combogrid('getValue');
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd2',
                type: "POST",
                dataType: "json",
                data: ({
                    kdskpd: kdskpd
                }),
                success: function(data) {
                    sta = data.statu;
                    kunci = data.angkas_m;
                    tombol(sta, kunci);
                    if (kunci == 1) {
                        alert("INPUTAN TELAH DIKUNCI");
                    }
                }
            });
        }

        function tombol(st, kunci) {
            if (kunci == '1') {
                $('#kunci1').remove();
                $('#kunci2').remove();
                $('#kunci3').remove();
                document.getElementById("p1").innerHTML = "INPUTAN TELAH DIKUNCI";
                document.getElementById("isi").innerHTML = "INPUTAN TELAH DIKUNCI";
                status_apbd = '1';
            }

        }

        function get(ckd_rek5, cnm_rek5, cnilai) {
            var gabrek = ckd_rek5 + ' - ' + cnm_rek5;
            var gabkeg = xkegiatan + ' - ' + xnmkegiatan;
            $("#kode_rek").attr("value", ckd_rek5);
            $("#nm_rek").attr("value", cnm_rek5);
            $("#nm_keg").attr("value", xnmkegiatan);
            $("#kode_keg").attr("value", xkegiatan);
            $("#jumlah_rek").attr("value", cnilai);
            anggkas_keg();
        }

        function anggkas_keg() {
            var a = $('#cc').combogrid('getValue');
            var b = $('#ck').combogrid('getValue');
            var d = $('#rak').combogrid('getValue');
            var c = xrekening;

            //alert(a);alert(b);alert(c);

            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        skpd: a,
                        keg: b,
                        rek5: c,
                        rak: d
                    }),
                    url: "<?php echo base_url(); ?>index.php/rka_ro/realisasi_angkas_ro",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {

                            $("#realtriw1").attr("value", n['triw1']);
                            $("#realtriw2").attr("value", n['triw2']);
                            $("#realtriw3").attr("value", n['triw3']);
                            $("#realtriw4").attr("value", n['triw4']);
                        });
                    }
                });
            });

            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        skpd: a,
                        keg: b,
                        rek5: c,
                        rak: d
                    }),
                    url: "<?php echo base_url(); ?>index.php/rka_ro/realisasi_angkas_ro_bulan",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#real1").attr("value", n['real1']);
                            $("#real2").attr("value", n['real2']);
                            $("#real3").attr("value", n['real3']);
                            $("#real4").attr("value", n['real4']);
                            $("#real5").attr("value", n['real5']);
                            $("#real6").attr("value", n['real6']);
                            $("#real7").attr("value", n['real7']);
                            $("#real8").attr("value", n['real8']);
                            $("#real9").attr("value", n['real9']);
                            $("#real10").attr("value", n['real10']);
                            $("#real11").attr("value", n['real11']);
                            $("#real12").attr("value", n['real12']);
                        });
                    }
                });
            });
        }

        function status_angkas() {
            var skpd = <?php echo json_encode($this->session->userdata('kdskpd')); ?>;
            var jns_ang = <?php echo json_encode($jns_anggaran); ?>;
            if (jns_ang == 'M') {
                jns_ang1 = "Penetapan";
            } else if (jns_ang == 'P1') {
                jns_ang1 = "Penyempurnaan I";
            } else if (jns_ang == 'P2') {
                jns_ang1 = "Penyempurnaan II";
            } else if (jns_ang == 'P3') {
                jns_ang1 = "Penyempurnaan III";
            } else if (jns_ang == 'P4') {
                jns_ang1 = "Penyempurnaan IV";
            } else if (jns_ang == 'P5') {
                jns_ang1 = "Penyempurnaan V";
            } else if (jns_ang == 'U1') {
                jns_ang1 = "Perubahan I";
            } else if (jns_ang == 'U2') {
                jns_ang1 = "Perubahan II";
            } else if (jns_ang == 'U3') {
                jns_ang1 = "Perubahan III";
            }
            swal("Berhasil", "Selamat Datang " + skpd + "<br> Dengan Status Anggaran " + jns_ang1, "warning");
        }

        function rek(kegiatan) {
            var skpd = $("#cc").combogrid("getValue");
            var kegiatan = $("#ck").combogrid("getValue");
            var rak = $("#rak").combogrid("getValue");
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/rka_ro/ambil_rek_angkas_ro_geser/' + kegiatan + '/' + skpd + '/nilai_' + rak
                });
            });
            load(skpd, rak);
        }

        function section1() {
            $(document).ready(function() {
                $('#section1').click();
                $('#dg').datagrid('reload');
            });
        }

        function section2() {
            $(document).ready(function() {
                $('#section2').click();
            });
            kosongkan();
            load();
        }



        function hitung() {
            var jumlah = angka(document.getElementById('jumlah_rek').value);
            var a = angka(document.getElementById('jan').value);
            var b = angka(document.getElementById('feb').value);
            var c = angka(document.getElementById('mar').value);
            var d = angka(document.getElementById('apr').value);
            var e = angka(document.getElementById('mei').value);
            var f = angka(document.getElementById('jun').value);
            var g = angka(document.getElementById('jul').value);
            var h = angka(document.getElementById('ags').value);
            var i = angka(document.getElementById('sep').value);
            var j = angka(document.getElementById('okt').value);
            var k = angka(document.getElementById('nov').value);
            var l = angka(document.getElementById('des').value);
            tr1 = eval(a + '+' + b + '+' + c);
            tr2 = eval(d + '+' + e + '+' + f);
            tr3 = eval(g + '+' + h + '+' + i);
            tr4 = eval(j + '+' + k + '+' + l);

            $("#tr1").attr("value", number_format(tr1, 2, '.', ','));
            $("#tr2").attr("value", number_format(tr2, 2, '.', ','));
            $("#tr3").attr("value", number_format(tr3, 2, '.', ','));
            $("#tr4").attr("value", number_format(tr4, 2, '.', ','));

            kas = tr1 + tr2 + tr3 + tr4;
            $("#kas").attr("value", number_format(kas, 2, '.', ','));
            selisih = jumlah - kas;
            $("#selisih").attr("value", number_format(selisih, 2, '.', ','));

        }

        function bagi() {
            var total = angka(document.getElementById('jumlah_rek').value);
            var tot = angka(document.getElementById('jumlah_rek').value);
            var rata = Math.floor(total / 12);

            var trata = rata * 12;
            var selisih = total - trata;

            $("#jan").attr("value", number_format(rata, 2, '.', ','));
            $("#feb").attr("value", number_format(rata, 2, '.', ','));
            $("#mar").attr("value", number_format(rata, 2, '.', ','));
            $("#apr").attr("value", number_format(rata, 2, '.', ','));
            $("#mei").attr("value", number_format(rata, 2, '.', ','));
            $("#jun").attr("value", number_format(rata, 2, '.', ','));
            $("#jul").attr("value", number_format(rata, 2, '.', ','));
            $("#ags").attr("value", number_format(rata, 2, '.', ','));
            $("#sep").attr("value", number_format(rata, 2, '.', ','));
            $("#okt").attr("value", number_format(rata, 2, '.', ','));
            $("#nov").attr("value", number_format(rata, 2, '.', ','));
            $("#des").attr("value", number_format(rata, 2, '.', ','));
            $("#tr1").attr("value", number_format(rata * 3, 2, '.', ','));
            $("#tr2").attr("value", number_format(rata * 3, 2, '.', ','));
            $("#tr3").attr("value", number_format(rata * 3, 2, '.', ','));
            $("#tr4").attr("value", number_format(rata * 3, 2, '.', ','));
            $("#kas").attr("value", number_format(trata, 2, '.', ','));
            $("#selisih").attr("value", number_format(selisih, 2, '.', ','));
        }

        function kosongkan() {
            $("#jan").attr("value", number_format(0, 2, '.', ','));
            $("#feb").attr("value", number_format(0, 2, '.', ','));
            $("#mar").attr("value", number_format(0, 2, '.', ','));
            $("#apr").attr("value", number_format(0, 2, '.', ','));
            $("#mei").attr("value", number_format(0, 2, '.', ','));
            $("#jun").attr("value", number_format(0, 2, '.', ','));
            $("#jul").attr("value", number_format(0, 2, '.', ','));
            $("#ags").attr("value", number_format(0, 2, '.', ','));
            $("#sep").attr("value", number_format(0, 2, '.', ','));
            $("#okt").attr("value", number_format(0, 2, '.', ','));
            $("#nov").attr("value", number_format(0, 2, '.', ','));
            $("#des").attr("value", number_format(0, 2, '.', ','));
            $("#tr1").attr("value", number_format(0, 2, '.', ','));
            $("#tr2").attr("value", number_format(0, 2, '.', ','));
            $("#tr3").attr("value", number_format(0, 2, '.', ','));
            $("#tr4").attr("value", number_format(0, 2, '.', ','));
            $("#kas").attr("value", number_format(0, 2, '.', ','));
            $("#selisih").attr("value", number_format(0, 2, '.', ','));
            $("#kas_rek").attr("value", number_format(0, 2, '.', ','));
        }

        function simpan() {
            kunci_inputan();
            var a = angka(document.getElementById('jan').value);
            var b = angka(document.getElementById('feb').value);
            var c = angka(document.getElementById('mar').value);
            var d = angka(document.getElementById('apr').value);
            var e = angka(document.getElementById('mei').value);
            var f = angka(document.getElementById('jun').value);
            var g = angka(document.getElementById('jul').value);
            var h = angka(document.getElementById('ags').value);
            var i = angka(document.getElementById('sep').value);
            var j = angka(document.getElementById('okt').value);
            var k = angka(document.getElementById('nov').value);
            var l = angka(document.getElementById('des').value);
            var m = angka(document.getElementById('tr1').value);
            var n = angka(document.getElementById('tr2').value);
            var o = angka(document.getElementById('tr3').value);
            var p = angka(document.getElementById('tr4').value);
            //---------------------------------------------------------------------
            var real1 = document.getElementById('real1').value;
            var real2 = document.getElementById('real3').value;
            var real3 = document.getElementById('real3').value;
            var real4 = document.getElementById('real4').value;
            var real5 = document.getElementById('real5').value;
            var real6 = document.getElementById('real6').value;
            var real7 = document.getElementById('real7').value;
            var real8 = document.getElementById('real8').value;
            var real9 = document.getElementById('real9').value;
            var real10 = document.getElementById('real10').value;
            var real11 = document.getElementById('real11').value;
            var real12 = document.getElementById('real12').value;


            var rak = $("#rak").combogrid('getValue');
            var kol_rak = 'nilai_' + rak;

            var realtw1 = angka(document.getElementById('realtriw1').value);
            var realtw2 = angka(document.getElementById('realtriw2').value);
            var realtw3 = angka(document.getElementById('realtriw3').value);
            var realtw4 = angka(document.getElementById('realtriw4').value);

            var nselisih = angka(document.getElementById('selisih').value);

            //----------------------------------------------------------------------
            // if (m < realtw1) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 1');
            //     return;
            // }
            // if ((m - realtw1) + n < realtw2) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 2');
            //     return;
            // }
            // if ((m - realtw1) + (n - realtw2) + o < realtw3) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 3');
            //     return;
            // }
            // if ((m - realtw1) + (n - realtw2) + (o - realtw3) + p < realtw4) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 4');
            //     return;
            // }
            // if (m + d < realtw1) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 1');
            //     return;
            // }
            // if ((m - realtw1) + n + g < realtw2) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 2');
            //     return;
            // }
            // if ((m - realtw1) + (n - realtw2) + o + j < realtw3) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 3');
            //     return;
            // }
            // if ((m - realtw1) + (n - realtw2) + (o - realtw3) + p < realtw4) {
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 4');
            //     return;
            // }

            //----------------------------------------------------------------------

            if (nselisih < 0) {
                alert('Pembagian Anggaran Melebihi Total Anggaran...!!!');
                return;
            }

            if (nselisih > 0) {
                alert('Masih ada sisa Anggaran yang belum dibagi...!!!');
                return;
            }

            var total_rek = angka(document.getElementById('jumlah_rek').value);
            var total_rek_kas = angka(document.getElementById('kas').value);

            if (total_rek != total_rek_kas) {
                alert('Total Rekening Tidak Sama...!!!');
                return;
            }

            var total_keg = angka(document.getElementById('jumlah').value);

            // if(m<realtw1){
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 1');
            //     return;
            // }
            // if((m-realtw1)+n<realtw2){
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 2');
            //     return;
            // }
            // if((m-realtw1)+(n-realtw2)+o<realtw3){
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 3');
            //     return;
            // }
            // if((m-realtw1)+(n-realtw2)+(o-realtw3)+p<realtw4){
            //     alert('nilai anggaran kurang dari nilai realisasi di Triwulan 4');
            //     return;
            // }
            
            var jns_ang = document.getElementById('jns_ang').value;
            var skpdd = $("#cc").combogrid('getValue');
            var xkegiatan = $("#ck").combogrid("getValue");
            // alert(kol_rak);

            // alert(skpdd);
            // alert(xkegiatan);
            if (nselisih == 0) {

                $(function() {
                    $("#loading").show();
                    $.ajax({
                        type: 'POST',
                        data: ({
                            csts: kol_rak,
                            cskpd: skpdd,
                            crek5: xrekening,
                            cgiat: xkegiatan,
                            jan: a,
                            feb: b,
                            mar: c,
                            apr: d,
                            mei: e,
                            jun: f,
                            jul: g,
                            ags: h,
                            sep: i,
                            okt: j,
                            nov: k,
                            des: l,
                            tr1: m,
                            tr2: n,
                            tr3: o,
                            tr4: p,
                            jns_ang: jns_ang
                        }),
                        dataType: "json",
                        url: "<?php echo base_url(); ?>index.php/rka_ro/simpan_trskpd_ro",
                        success: function(data) {
                            if (data = 1) {
                                alert('Data Berhasil Tersimpan...!!!');
                            } else {
                                alert('Data Gagal Berhasil Tersimpan...!!!');
                            }
                            $("#loading").hide();
                        }
                    });
                });
            } else {
                $("#loading").hide();
                alert('sisa Anggaran harus sama dengan nilai nol...!!!');
                return;
            }
        }

        function load(skpd, rak) {

            var skpd = $("#cc").combogrid('getValue');
            var rak = $("#rak").combogrid('getValue');
            // var jns_ang =document.getElementById('jns_ang').value;
            // alert(jns_ang);
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        p: kegiatan,
                        s: xrekening
                    }),
                    url: "<?php echo base_url(); ?>index.php/rka_ro/load_trdskpd_geser/nilai_" + rak + "/" + skpd,
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            bulan = n['bulan'];
                            switch (bulan) {
                                case '1':
                                    $("#jan").attr("value", n['nilai']);
                                    break;
                                case '2':
                                    $("#feb").attr("value", n['nilai']);
                                    break;
                                case '3':
                                    $("#mar").attr("value", n['nilai']);
                                    break;
                                case '4':
                                    $("#apr").attr("value", n['nilai']);
                                    break;
                                case '5':
                                    $("#mei").attr("value", n['nilai']);
                                    break;
                                case '6':
                                    $("#jun").attr("value", n['nilai']);
                                    break;
                                case '7':
                                    $("#jul").attr("value", n['nilai']);
                                    break;
                                case '8':
                                    $("#ags").attr("value", n['nilai']);
                                    break;
                                case '9':
                                    $("#sep").attr("value", n['nilai']);
                                    break;
                                case '10':
                                    $("#okt").attr("value", n['nilai']);
                                    break;
                                case '11':
                                    $("#nov").attr("value", n['nilai']);
                                    break;
                                case '12':
                                    $("#des").attr("value", n['nilai']);
                                    break;
                            }
                            hitung();
                        });
                    }
                });
            });
        }

        function enter(ckey, _cid) {
            if (ckey == 13 || ckey == 9) {
                document.getElementById(_cid).focus();
            }

        }
    </script>

</head>

<body>



    <div id="content">

        <div id="accordion">
            <h3><a href="#" id="section1">Home</a></h3>
            <div style="height: 350px;">

                <div class="row">
                    <div class="card">
                        <div class="card-header bg-light" align="center">
                            <h4>Rencana Anggaran Kas Belanja Sub Rincian Objek</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label> OPD/Unit</label><br>
                                <input id="cc" name="skpd" class="form-control" style="width: 400px;" /> &nbsp;<input type='text' id="nmskpd" name="nmskpd" style="border: none;width: 380px;" />
                            </div>
                            <div class="form-group">
                                <label> Jenis RAK</label><br>
                                <input id="rak" name="rak" class="form-control" style="width: 400px;" />
                            </div>
                            <div class="form-group">
                                <label> Sub Kegiatan</label><br>
                                <input id="ck" name="kegiatan" class="form-control" style="width: 400px;" /> &nbsp;<input type='text' id="nmgiat" name="nmgiat" style="border: none;width: 380px;" />
                            </div>
                            <div class="form-group">
                                <label>Nilai Anggaran</label><br>
                                <input id="total_pic2" name="total_pic2" class="form-control" style="width: 225px; text-align: right; font-weight: bold; border-left: none; border-right: none; border-top: none;" />
                            </div>
                            <div class="form-group">
                                <label>Keterangan warna:</label><br><br>
                                <label style="background-color: #ff471a; display: inline; border: 1px solid black; padding: 5px;">Selisih</label>
                                <label style="background-color: #ffffff; display: inline; border: 1px solid black; padding: 5px;">Sama</label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>&nbsp;
                <div class="row">
                    <div class="card">
                        <div class="card-body bg-info">
                            <table width="100%" style="border:none">
                                <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" align="center">
                                    <td>
                                        <font color="white">Triwulan I </font>
                                    </td>
                                    <td>
                                        <font color="white">Triwulan II </font>
                                    </td>
                                    <td>
                                        <font color="white">Triwulan III </font>
                                    </td>
                                    <td>
                                        <font color="white">Triwulan IV </font>
                                    </td>
                                </tr>
                                <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" align="center">

                                    <td><input type="text" class="input" name="tw1" id="tw1" readonly="true" style="text-align: right"></td>
                                    <td><input type="text" class="input" name="tw2" id="tw2" readonly="true" style="text-align: right"></td>
                                    <td><input type="text" class="input" name="tw3" id="tw3" readonly="true" style="text-align: right"></td>
                                    <td><input type="text" class="input" name="tw4" id="tw4" readonly="true" style="text-align: right"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <p>


                    <br><br>
                    <!--<input id="s" type="submit" name="submit" value="Input Anggaran Kas Penyempurnaan" onclick="javascript:section2();"/><br /><br />-->


                <table id="dg" title="Rekening RAK" style="width:875px;height:350px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2"></a></h3>

            <div>
                <p>
                <div class="result">
                    <div class="row">

                        <table>
                            <tr>
                                <td width="45%">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Kode Sub kegiatan</label>
                                                <input disabled="true" class="form-control" id="kode_keg" name="kode_keg" style="text-align: left; width:380px;">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Sub Kegiatan</label>
                                                <textarea disabled="true" class="form-control" id="nm_keg" rows="3" name="kode_rek" style="text-align: left; width:380px;"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Anggaran Sub Kegiatan</label>
                                                <input type="decimal" disabled="true" class="form-control" id="jumlah" name="jumlah" style="text-align: right; background-color:#F0E68C;width: 380px">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td width="10%">&nbsp;</td>
                                <td width="45%">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Kode Rekening</label>
                                                <input disabled="true" class="form-control" id="kode_rek" name="kode_rek" style="text-align: left; width:380px;">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Rekening</label>
                                                <textarea disabled="true" class="form-control" id="nm_rek" rows="3" name="nm_rek" style="text-align: left; width:380px;"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Anggaran Rekening</label>
                                                <input type="decimal" disabled="true" class="form-control" id="jumlah_rek" name="jumlah_rek" style="text-align: right;  background-color:#F0E68C;width: 380px;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="row">
                        <div class="card">
                            <div class="card-body bg-success">
                                <table border="0" width="100%">
                                    <tr>
                                        <td width="45%">
                                            <font color="white">RAK terinput</font>
                                        </td>
                                        <td width="10%">&nbsp;</td>
                                        <td width="45%">
                                            <font color="white">RAK belum terinput</font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="45%">
                                            <input type="decimal" disabled="true" class="form-control" id="kas" name="kas" style="width: 400px; text-align: right; background-color:#fff;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                                        </td>
                                        <td width="10%">&nbsp;</td>
                                        <td width="45%">
                                            <input type="decimal" disabled="true" class="form-control" id="selisih" name="selisih" style="width: 400px;text-align: right; background-color:#fff;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table>
                            <tr>
                                <td width="48%">
                                    <div class="card">
                                        <div class="card-header">
                                            Triwulan I
                                        </div>
                                        <div class="card-body">
                                            <table border="0">
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Januari" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Februari" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Maret" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="RAK" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="jan" name="jan" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="feb" name="feb" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="mar" name="mar" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" id="tr1" name="tr1" style="width: 130px;text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Realisasi" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real1" name="real1" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real2" name="real2" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real3" name="real3" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="realtriw1" name="realtriw1" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                                <td width="4%">&nbsp;</td>
                                <td width="48%">
                                    <div class="card">
                                        <div class="card-header">
                                            Triwulan II
                                        </div>
                                        <div class="card-body">
                                            <table border="0">
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="April" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Mei" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Juni" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="RAK" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="apr" name="apr" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="mei" name="mei" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="jun" name="jun" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" id="tr2" name="tr2" style="width: 130px;text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Realisasi" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real4" name="real4" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real5" name="real5" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real6" name="real6" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="realtriw2" name="realtriw2" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="48%">
                                    <div class="card">
                                        <div class="card-header">
                                            Triwulan III
                                        </div>
                                        <div class="card-body">
                                            <table border="0">
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Juli" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Agustus" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="September" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="RAK" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="jul" name="jul" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="ags" name="ags" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="sep" name="sep" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" id="tr3" name="tr3" style="width: 130px;text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Realisasi" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real7" name="real7" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real8" name="real8" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real9" name="real9" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="realtriw3" name="realtriw3" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                                <td width="4%">&nbsp;</td>
                                <td width="48%">
                                    <div class="card">
                                        <div class="card-header">
                                            Triwulan IV
                                        </div>
                                        <div class="card-body">
                                            <table border="0">
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Oktober" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="November" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Desember" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="" name="" value="&nbsp;" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="RAK" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="okt" name="okt" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="nov" name="nov" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" id="des" name="des" value="0" class="form-control" onclick="javascript:select();" onkeyup="javascript:hitung();" style="width: 130px;text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" id="tr4" name="tr4" style="width: 130px;text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="" name="" value="Realisasi" class="form-control" style="width: 60px;border:none" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real10" name="real10" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real11" name="real11" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="real12" name="real12" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="decimal" disabled="true" class="form-control" align="right" id="realtriw4" name="realtriw4" style="width: 130px;text-align: right; background-color:#FFE4C4;" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <div class="alert alert-danger" role="alert" id="myDIV">
                                        <p id="isi"></p>
                                    </div>

                                    <button class="btn btn-primary" id='kunci1' onclick="javascript:simpan();"><i class="fa fa-simpan"></i> Simpan</button>
                                    <button class="btn btn-danger" onclick="javascript:kosongkan();" id='kunci2' disabled> <i class="fa fa-clear"></i> Kosongkan</button>
                                    <button class="btn btn-secondary" onclick="javascript:bagi();"><i class="fa fa-expand"></i> Bagi Rata</button>
                                    <button class="btn btn-warning" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
                </p>
            </div>

        </div>

    </div>
</body>

<div class="loader1" id="loading">
    <div class="loader2"></div>
</div>
<input type="text" id="jns_ang" hidden>

</html>