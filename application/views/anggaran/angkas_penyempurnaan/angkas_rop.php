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

    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <script>
        var kode = '';
        var kegiatan = '';
        var xrekening = '';
        var xnmkegiatan = '';
        var xkegiatan = '';
        var total_pic = 0;

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#kegi").hide();
            $("#lab1").hide();
            $("#lab2").hide();
            $("#loading").hide();
            protect();
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
                    kunci_inputan();
                    kode = rowData.kd_skpd;
                    $("#ck").combogrid("clear");
                    $("#total_pic2").attr("value", '');
                    giat(kode);
                    setTimeout(tampil, 500);
                    rek('');
                    cek_status(kode, 9);
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
                    if (row.s_n_ro1 == 0) {
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
                            field: 'nilai_sempurna',
                            title: 'Nilai Murni Terakhir',
                            width: 90,
                            align: "right"
                        },
                        {
                            field: 'nilai_sempurna',
                            title: 'Nilai Terinput',
                            width: 90,
                            align: "right"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    ckd_rek5 = rowData.kd_rek5;
                    xrekening = ckd_rek5;
                    cnm_rek5 = rowData.nm_rek5;
                    cnilai_ubah = rowData.nilai_sempurna;
                    get(ckd_rek5, cnm_rek5, cnilai_ubah);
                },
                onDblClickRow: function(rowIndex, rowData) {
                    ckd_rek5 = rowData.kd_rek5;
                    xrekening = ckd_rek5;
                    cnm_rek5 = rowData.nm_rek5;
                    cnilai_ubah = rowData.nilai_sempurna;
                    get(ckd_rek5, cnm_rek5, cnilai_ubah);
                    section2();
                }
            });
        });

        function tampil() {
            $("#kegi").show();
            $("#lab1").show();
            $("#lab2").show();
        }


        function protect() {
            var d = new Date();
            var n = d.getMonth();
            var y = d.getFullYear();
            // 0 januari
            // 1 Februari
            if (n == '0' && y == '2021') {

            } else if (n == '1' && y == '2021') {
                document.getElementById("jan").disabled = true;
            } else if (n == '2' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
            } else if (n == '3' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;

            } else if (n == '4' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;

            } else if (n == '5' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
            } else if (n == '6' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
            } else if (n == '7' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
            } else if (n == '8' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = true;
            } else if (n == '9' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = true;
                document.getElementById("sep").disabled = true;
            } else if (n == '10' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = true;
                document.getElementById("sep").disabled = true;
                document.getElementById("okt").disabled = true;
            } else if (n == '11' && y == '2021') {
                document.getElementById("jan").disabled = true;
                document.getElementById("feb").disabled = true;
                document.getElementById("mar").disabled = true;
                document.getElementById("apr").disabled = true;
                document.getElementById("mei").disabled = true;
                document.getElementById("jun").disabled = true;
                document.getElementById("jul").disabled = true;
                document.getElementById("ags").disabled = true;
                document.getElementById("sep").disabled = true;
                document.getElementById("okt").disabled = true;
                document.getElementById("nov").disabled = true;
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
                document.getElementById("okt").disabled = true;
                document.getElementById("nov").disabled = true;
                document.getElementById("des").disabled = true;
            }
        }

        function giat(kode) {

            $(function() {
                $('#ck').combogrid({
                    panelWidth: 700,
                    idField: 'kd_kegiatan',
                    textField: 'kd_kegiatan',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/rka_ro/load_giat/' + kode,
                    columns: [
                        [{
                                field: 'kd_kegiatan',
                                title: 'Kode Kegiatan',
                                width: 150
                            },
                            {
                                field: 'nm_kegiatan',
                                title: 'Nama Kegiatan',
                                width: 520
                            }

                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        kegiatan = rowData.kd_kegiatan;
                        xkegiatan = rowData.kd_kegiatan;
                        xnmkegiatan = rowData.nm_kegiatan;
                        total = rowData.total_sempurna;
                        total_pic = total;
                        skpdd = rowData.kd_skpd;
                        $("#jumlah").attr("value", total);
                        $("#skpdd").attr("value", skpdd);
                        $("#total_pic2").attr("value", total_pic);

                        $(function() {
                            $.ajax({
                                type: 'POST',
                                data: ({
                                    kegiatan: kegiatan
                                }),
                                url: "<?php echo base_url(); ?>index.php/rka_ro/total_triwulan_geser/nilai_sempurna/" + kode,
                                dataType: "json",
                                success: function(data) {
                                    $.each(data, function(i, n) {
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
                    tombol(sta, kunci);
                }
            });
        }

        function kunci_inputan() {
            var kdskpd = $('#cc').combogrid('getValue');
            alert(kdskpd);            
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka_penetapan/config_angkas',
                type: "POST",
                dataType: "json",
                data: ({
                    kdskpd: kdskpd
                }),
                success: function(data) {
                    sta = data.sempurna1;
                    // kunci = data.angkas_m;
                    // tombol(sta, kunci);
                    if (sta == 1) {
                        alert(sta);
                         document.getElementById("kunci1").disabled = true;
                        alert("INPUTAN TELAH DIKUNCI");
                    }
                }
            });
        }

        function tombol(st, kunci) {
            if (kunci == '1') {
                document.getElementById("kunci1").disabled = true;
                $('#kunci2').remove();
                $('#kunci3').remove();
                document.getElementById("p1").innerHTML = "INPUTAN TELAH DIKUNCI";
                document.getElementById("isi").innerHTML = "INPUTAN TELAH DIKUNCI";
                status_apbd = '1';
            }

        }

        function get(ckd_rek5, cnm_rek5, cnilai_ubah) {
            var gabrek = ckd_rek5 + ' - ' + cnm_rek5;
            var gabkeg = xkegiatan + ' - ' + xnmkegiatan;
            $("#nm_rek").attr("value", gabrek);
            $("#nm_keg").attr("value", gabkeg);
            $("#jumlah_rek").attr("value", cnilai_ubah);
            anggkas_keg();
        }

        function anggkas_keg() {
            var a = $('#cc').combogrid('getValue');
            var b = $('#ck').combogrid('getValue');
            var c = xrekening;

            //alert(a);alert(b);alert(c);

            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        skpd: a,
                        keg: b,
                        rek5: c
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
                        rek5: c
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

        function rek(kegiatan) {
            var skpd = $("#cc").combogrid("getValue");
            var kegiatan = $("#ck").combogrid("getValue");
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/rka_ro/ambil_rek_angkas_ro_geser/' + kegiatan + '/' + skpd
                });
            });
            load(skpd);
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


            var realtw1 = angka(document.getElementById('realtriw1').value);
            var realtw2 = angka(document.getElementById('realtriw2').value);
            var realtw3 = angka(document.getElementById('realtriw3').value);
            var realtw4 = angka(document.getElementById('realtriw4').value);

            var nselisih = angka(document.getElementById('selisih').value);

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

            if (m < realtw1) {
                alert('nilai anggaran kurang dari nilai realisasi di Triwulan 1');
                return;
            }
            if ((m - realtw1) + n < realtw2) {
                alert('nilai anggaran kurang dari nilai realisasi di Triwulan 2');
                return;
            }
            if ((m - realtw1) + (n - realtw2) + o < realtw3) {
                alert('nilai anggaran kurang dari nilai realisasi di Triwulan 3');
                return;
            }
            if ((m - realtw1) + (n - realtw2) + (o - realtw3) + p < realtw4) {
                alert('nilai anggaran kurang dari nilai realisasi di Triwulan 4');
                return;
            }

            if (nselisih == 0) {

                $(function() {
                    $("#loading").show();
                    $.ajax({
                        type: 'POST',
                        data: ({
                            csts: 'sempurna11',
                            cskpda: kode,
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
                            tr4: p
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

        function load(skpd) {

            var skpd = $("#cc").combogrid('getValue');
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        p: kegiatan,
                        s: xrekening,
                        jns: 'susun'
                    }),
                    url: "<?php echo base_url(); ?>index.php/rka_ro/load_trdskpd_geser/nilai_sempurna/" + skpd,
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
            <h3><a href="#" id="section1">Anggaran Kas Belanja Sub RO Penyempurnaan I</a></h3>
            <div style="height: 350px;">
                <p>
                <h5><b>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><input id="cc" name="skpd" style="width: 400px;" /><input type='hidden' id="skpdd" name="skpdd" style="width: 400px;" /> </h5>
                <p id="p1"></p>
                <div id="kegi">
                    <h5><b>KEGIATAN&nbsp;&nbsp;&nbsp;&nbsp;</b><input id="ck" name="kegiatan" style="width: 400px;" /> </h5>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <h3> Rp. <input id="total_pic2" name="total_pic2" style="width: 225px; text-align: right; font-weight: bold; border-left: none; border-right: none; border-top: none;" />
                   </h3>
                    <br>
                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label id="lab1" style="background-color: #ff471a; display: inline; border: 1px solid black; padding: 5px;">Selisih</label>
                <label id="lab2" style="background-color: #ffffff; display: inline; border: 1px solid black; padding: 5px;">Sama</label>
                <br><br>
                <!--<input id="s" type="submit" name="submit" value="Input Anggaran Kas Penyempurnaan" onclick="javascript:section2();"/><br /><br />-->
                <table width="100%">
                    <tr>
                        <td>Kode Kegiatan</td>
                        <td>Triwulan I</td>
                        <td>Triwulan II</td>
                        <td>Triwulan III</td>
                        <td>Triwulan IV</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="input" name="kegiatan_kd" id="kegiatan_kd" readonly="true" style="text-align: right"></td>
                        <td><input type="text" class="input" name="tw1" id="tw1" readonly="true" style="text-align: right"></td>
                        <td><input type="text" class="input" name="tw2" id="tw2" readonly="true" style="text-align: right"></td>
                        <td><input type="text" class="input" name="tw3" id="tw3" readonly="true" style="text-align: right"></td>
                        <td><input type="text" class="input" name="tw4" id="tw4" readonly="true" style="text-align: right"></td>
                    </tr>
                </table>

                <table id="dg" title="Rekening Rencana Kegiatan Anggaran" style="width:875px;height:350px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2"></a></h3>
            <div>
                <p>
                <div class="result">
                    <table align="center">
                        <tr>
                            <td><b>Sub Kegiatan</b></td>
                            <td colspan="5" width="60%"><input disabled="true" class="input" id="nm_keg" name="nm_keg" style="text-align: left; width:640px;"></td>
                        </tr>
                        <tr>
                            <td><b>Akun</b></td>
                            <td colspan="5" width="60%"><input disabled="true" class="input" id="nm_rek" name="nm_rek" style="text-align: left; width:640px;"></td>
                        </tr>
                        <tr>
                            <td><b>Total Anggaran Kas Kegiatan</b></td>
                            <td colspan="2" width="30%"><input type="decimal" disabled="true" class="input" id="jumlah" name="jumlah" style="text-align: right; background-color:#F0E68C;"></td>
                            <td><b>Total Rekening</b></td>
                            <td colspan="2" width="30%"><input type="decimal" disabled="true" class="input" id="jumlah_rek" name="jumlah_rek" style="text-align: right;  background-color:#F0E68C;"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td align="center">Bulan</td>
                            <td align="center">Angkas</td>
                            <td align="center">Realisasi</td>
                            <td align="center">Bulan</td>
                            <td align="center">Angkas</td>
                            <td align="center">Realisasi</td>
                        </tr>
                        <tr>
                            <td><b>Januari</td>
                            <td><input type="decimal" id="jan" name="jan" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'feb');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real1" name="real1" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td><b>April</td>
                            <td><input type="decimal" id="apr" name="apr" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'mei');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real4" name="real4" style="text-align: right; background-color:#FFE4C4;" /></td>
                        </tr>
                        <tr>
                            <td><b>Februari</td>
                            <td><input type="decimal" id="feb" name="feb" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'mar');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real2" name="real2" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td><b>Mei</td>
                            <td><input type="decimal" id="mei" name="mei" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'jun');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real5" name="real5" style="text-align: right; background-color:#FFE4C4;" /></td>
                        </tr>
                        <tr>
                            <td><b>Maret</td>
                            <td><input type="decimal" id="mar" name="mar" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'apr');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real3" name="real3" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td><b>Juni</td>
                            <td><input type="decimal" id="jun" name="jun" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'jul');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real6" name="real6" style="text-align: right; background-color:#FFE4C4;" /></td>
                        </tr>
                        <tr>
                            <td><b>Triwulan I</td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="tr1" name="tr1" style="text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                            <td></td>
                            <td><b>Triwulan II</td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="tr2" name="tr2" style="text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Realisasi I</td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="realtriw1" name="realtriw1" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td></td>
                            <td><b>Realisasi II</td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="realtriw2" name="realtriw2" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Juli</td>
                            <td><input type="decimal" id="jul" name="jul" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'ags');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real7" name="real7" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td><b>Oktober</td>
                            <td><input type="decimal" id="okt" name="okt" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'nov');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real10" name="real10" style="text-align: right; background-color:#FFE4C4;" /></td>
                        </tr>
                        <tr>
                            <td><b>Agustus</td>
                            <td><input type="decimal" id="ags" name="ags" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'sep');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real8" name="real8" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td><b>November</td>
                            <td><input type="decimal" id="nov" name="nov" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'des');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real11" name="real11" style="text-align: right; background-color:#FFE4C4;" /></td>
                        </tr>
                        <tr>
                            <td><b>September</td>
                            <td><input type="decimal" id="sep" name="sep" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:enter(event.keyCode,'okt');return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real9" name="real9" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td><b>Desember</td>
                            <td><input type="decimal" id="des" name="des" value="0" class="input" onclick="javascript:select();" onkeyup="javascript:hitung();" style="text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="real12" name="real12" style="text-align: right; background-color:#FFE4C4;" /></td>
                        </tr>
                        <tr>
                            <td><b>Triwulan III</td>
                            <td><input type="decimal" disabled="true" class="input" id="tr3" name="tr3" style="text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                            <td></td>
                            <td><b>Triwulan IV</td>
                            <td><input type="decimal" disabled="true" class="input" id="tr4" name="tr4" style="text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Realisasi III</td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="realtriw3" name="realtriw3" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td></td>
                            <td><b>Realisasi IV</td>
                            <td><input type="decimal" disabled="true" class="input" align="right" id="realtriw4" name="realtriw4" style="text-align: right; background-color:#FFE4C4;" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b>Total Anggaran Kas</td>
                            <td colspan="2"><input type="decimal" disabled="true" class="input" id="kas" name="kas" style="text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                            <td><b>Selisih/Sisa</td>
                            <td colspan="2"><input type="decimal" disabled="true" class="input" id="selisih" name="selisih" style="text-align: right; background-color:#F0E68C;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                        </tr>
                        <tr>
                            <td colspan="6" align="center">
                                <p id="isi"></p>

                                <button class="btn btn-primary" id='kunci1' onclick="javascript:simpan();"><i class="fa fa-simpan"></i> Simpan</button>
                                <button class="button-merah" onclick="javascript:kosongkan();" id='kunci2'> <i class="fa fa-clear"></i> Kosongkan</button>
                                <button class="button-abu" onclick="javascript:bagi();" id='kunci3'><i class="fa fa"></i> Bagi Rata</button>
                                <button class="button-abu" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>
                        </tr>
                    </table>
                </div>
                </p>
            </div>

        </div>

    </div>
</body>

<div class="loader1" id="loading">
    <div class="loader2"></div>
</div>

</html>