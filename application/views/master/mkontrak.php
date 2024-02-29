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
    <script src="<?php echo base_url(); ?>assets/js/shortcut.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <script type="text/javascript">
        var kode = '';
        var giat = '';
        var nomor = '';
        var judul = '';
        var cid = 0;
        var lcidx = 0;
        var lcstatus = '';
        get_skpd();

        $(document).ready(function() {

            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 680,
                width: 550,
                modal: true,
                autoOpen: false
            });

            $('#tgal').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {
                    var m = date.getMonth() + 1;
                    bulan = m;
                    //get_hasil();
                }
            });

        });

        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/master/load_kontrak',
                idField: 'id',
                toolbar: '#toolbar',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                loadMsg: "Tunggu Sebentar....!!",
                pagination: "true",
                nowrap: "true",
                columns: [
                    [{
                            field: 'no_kontrak',
                            title: 'Nomor Kontrak',
                            width: 290,
                            align: "left"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai Kontrak',
                            width: 100
                        },
                        {
                            field: 'nm_kerja',
                            title: 'Nama Pekerjaan',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'nmpel',
                            title: 'Pelaksana Pekerjaan',
                            width: 110,
                            align: "left"
                        },
                        {
                            field: 'tgl_kerja',
                            title: 'Tanggal Kontrak',
                            width: 100,
                            align: "left"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kd = rowData.no_kontrak;
                    nl = rowData.nilai;
                    nmpel = rowData.nmpel;
                    nm = rowData.nm_kerja;
                    tgl = rowData.tgl_kerja;
                    get(kd, nl, nm, tgl, nmpel);
                    lcidx = rowIndex;

                },
                onDblClickRow: function(rowIndex, rowData) {
                    lcidx = rowIndex;
                    judul = 'Edit Data Kontrak';
                    edit_data();
                }

            });


            $('#nmpel').combogrid({
                panelWidth: 500,
                idField: 'nama',
                textField: 'nama',
                mode: 'remote',
                fitColumns: true,
                url: '<?php echo base_url(); ?>index.php/master/ambil_pelaksana_kontrak',
                columns: [
                    [{
                            field: 'kode',
                            title: 'Kode',
                            width: 100
                        },
                        {
                            field: 'nama',
                            title: 'Pelaksana',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nama_bank").attr("value", rowData.nama.toUpperCase());
                }
            });

        });



        function get(kd, nl, nm, tgl, nmpel) {

            $("#kontrak").attr("value", kd);
            $("#nilai").attr("value", nl);
            // $("#nmpel").attr("value",nmpel);
            $("#nmpel").combogrid("setValue", nmpel);
            $("#nm").attr("value", nm);
            $("#tgal").datebox("setValue", tgl);




        }



        function get_skpd() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#dinas").attr("value", data.kd_skpd);
                    $("#nm_u").attr("value", data.nm_skpd);
                }
            });
        }

        function kosong() {
            $("#kontrak").attr("value", '');
            $("#nilai").attr("value", '');
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/master/load_kontrak',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function simpan_rek1() {

            // $('#save').linkbutton('disable');
            // $('#hapus').linkbutton('disable');
            // document.getElementById("save").disabled = true;
            // document.getElementById("hapus").disabled = true;
            var ckontrak = document.getElementById('kontrak').value;
            var ckontrak = alltrim(ckontrak);
            var cnilaii = document.getElementById('nilai').value;
            var cnilai = angka(document.getElementById('nilai').value);
            var cskpd = document.getElementById('dinas').value;
            var cnm_kerja = document.getElementById('nm').value;
            var cnmpel = $('#nmpel').combogrid('getValue'); //document.getElementById('nmpel').value;
            var ctgl = $('#tgal').datebox('getValue');

            if (ckontrak == '') {
                // $('#save').linkbutton('enable');
                // $('#hapus').linkbutton('enable');
                document.getElementById("save").disabled = false;
                document.getElementById("hapus").disabled = false;
                swal("Error", "Nomor Kontrak Tidak Boleh Kosong", "error");
                exit();
            }

            if (cnmpel == '') {
                // $('#save').linkbutton('enable');
                // $('#hapus').linkbutton('enable');
                document.getElementById("save").disabled = false;
                document.getElementById("hapus").disabled = false;
                swal("Error", "Pelaksana Pekerjaan Tidak Boleh Kosong", "error");
                exit();
            }

            if (cnilaii == "") {
                // $('#save').linkbutton('enable');
                // $('#hapus').linkbutton('enable');
                document.getElementById("save").disabled = false;
                document.getElementById("hapus").disabled = false;
                swal("Error", "Nilai Tidak Boleh Kosong", "error");
                exit();
            }

            if (cnm_kerja == "") {
                // $('#save').linkbutton('enable');
                // $('#hapus').linkbutton('enable');
                document.getElementById("save").disabled = false;
                document.getElementById("hapus").disabled = false;
                swal("Error", "Nama Pekerjaan Tidak Boleh Kosong", "error");
                exit();
            }

            if (ctgl == "") {
                // $('#save').linkbutton('enable');
                // $('#hapus').linkbutton('enable');
                document.getElementById("save").disabled = false;
                document.getElementById("hapus").disabled = false;
                swal("Error", "Tanggal Tidak Boleh Kosong", "error");
                exit();
            }

            if (lcstatus == 'tambah') {
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: ckontrak,
                            tabel: 'ms_kontrak',
                        }),
                        url: '<?php echo base_url(); ?>index.php/master/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == '1') {
                                alert("Nomor Telah Dipakai!");
                                document.getElementById("nomor").focus();
                                exit();
                            } else if (status_cek == '0') {
                                lcinsert = "(no_kontrak,nilai,kd_skpd,nm_kerja,tgl_kerja,nmpel)";
                                lcvalues = "('" + ckontrak + "','" + cnilai + "','" + cskpd + "','" + cnm_kerja + "','" + ctgl + "','" + cnmpel + "')";

                                $.ajax({
                                    type: "POST",
                                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master_kontrak',
                                    data: ({
                                        tabel: 'ms_kontrak',
                                        kolom: lcinsert,
                                        nilai: lcvalues,
                                        cid: 'no_kontrak',
                                        lcid: ckontrak,
                                        nil_kontrak: cnilai
                                    }),
                                    dataType: "json",
                                    success: function(data) {
                                        nilai = data;

                                        if (nilai != 2) {

                                            swal("Error", "Gagal Simpan.!!!, Cek Kembali Inputan", "error");
                                            // $('#save').linkbutton('enable');
                                            // $('#hapus').linkbutton('enable');
                                            document.getElementById("save").disabled = false;
                                            document.getElementById("hapus").disabled = false;
                                            exit();

                                        } else {

                                            swal("Sukses", "Data Berhasil Tersimpan", "success");
                                            // $('#save').linkbutton('enable');
                                            // $('#hapus').linkbutton('enable');
                                            document.getElementById("save").disabled = false;
                                            document.getElementById("hapus").disabled = false;
                                            $("#dialog-modal").dialog('close');
                                            $('#dg').edatagrid('reload');
                                            return;

                                        }
                                    }
                                });


                            }
                        }
                    });
                });



            } else {

                $(function() {
                    $.ajax({
                        type: 'POST',
                        data: ({
                            kontrak: ckontrak
                        }),
                        url: "<?php echo base_url(); ?>index.php/master/cek_kontrak",
                        dataType: "json",
                        success: function(data) {

                            nilai = data;


                            if (cnilai < nilai) {

                                swal("Error", "Nilai Kontrak Kurang Dari Penagihan yang sudah di buat.!!", "error");
                                document.getElementById("save").disabled = false;
                                document.getElementById("hapus").disabled = false;
                                // $('#save').linkbutton('enable');
                                // $('#hapus').linkbutton('enable');
                                exit();

                            } else {

                                lcquery = "UPDATE ms_kontrak SET nilai='" + cnilai + "',kd_skpd='" + cskpd + "',nmpel='" + cnmpel + "',nm_kerja='" + cnm_kerja + "',tgl_kerja='" + ctgl + "' where no_kontrak='" + ckontrak + "'";

                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo base_url(); ?>/index.php/master/update_master',
                                        data: ({
                                            st_query: lcquery
                                        }),
                                        dataType: "json"
                                    });
                                    swal("Sukses", "Data Berhasil Tersimpan", "success");
                                    // $('#save').linkbutton('enable');
                                    document.getElementById("save").disabled = false;
                                    // $('#hapus').linkbutton('enable');
                                    document.getElementById("hapus").disabled = false;
                                    $("#dialog-modal").dialog('close');
                                    $('#dg').edatagrid('reload');
                                });

                            }
                        }
                    });

                });


            }


        }

        function edit_data() {
            lcstatus = 'edit';
            judul = 'Edit Data Kontrak';
            $("#dialog-modal").dialog({
                title: judul
            });
            $("#dialog-modal").dialog('open');
            document.getElementById("kontrak").disabled = true;
        }


        function tambah() {
            lcstatus = 'tambah';
            judul = 'Input Data Kontrak';
            $("#dialog-modal").dialog({
                title: judul
            });
            kosong();
            $("#dialog-modal").dialog('open');
            document.getElementById("kontrak").disabled = false;
            document.getElementById("kontrak").focus();
        }

        function keluar() {
            $("#dialog-modal").dialog('close');
        }

        function hapus() {
            // $('#hapus').linkbutton('disable');
            // document.getElementById("save").disabled = false;
            document.getElementById("hapus").disabled = true;
            var ckontrak = document.getElementById('kontrak').value;
            var cskpd = document.getElementById('dinas').value;

            $(document).ready(function() {

            });
            // alert(csql);
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/master/hapus_master_kontrak',
                dataType: 'json',
                data: ({
                    tabel: 'ms_kontrak',
                    cnid: ckontrak,
                    cid: 'no_kontrak',
                    skpd: cskpd
                }),
                success: function(data) {
                    status = data.pesan;
                    // alert(status);
                    // return;
                    if (status == 0) {
                        swal("Error", "Nomer Kontrak Sudah Di Pakai di Penagihan..!!", "error");
                        $('#hapus').linkbutton('enable');
                        exit();

                    } else if (status == 1) {
                        $('#dg').datagrid('deleteRow', lcidx);
                        swal("Sukses", "Data Berhasil Dihapus..!!", "success");
                        // $('#hapus').linkbutton('enable');
                        document.getElementById("hapus").disabled = false;
                        $("#dialog-modal").dialog('close');
                        exit();
                    }
                }
            });


        }


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

        function alltrim(kata) {
            //alert(kata);

            // $cnmgiatx = $cnmgiats.split("/" && ",").join(" ");
            b = (kata.split("'").join("`"));
            c = (b.split(" ").join(""));
            d = (c.replace(/\s/g, ""));
            return d

        }
    </script>

</head>

<body>

    <div id="content">
        <h3 align="center"><u><b><a>INPUTAN MASTER NOMOR KONTRAK</a></b></u></h3>
        <div align="center">
            <p align="center">
            <table style="width:400px;" border="0">
                <tr>
                    <td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah</a></td>
                    <td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
                    <td><input type="text" value="" id="txtcari" style="width:300px;" /></td>
                </tr>
                <tr>
                    <td colspan="5">
                        <table id="dg" title="LISTING DATA KONTRAK" style="width:900px;height:455px;">
                        </table>
                    </td>
                </tr>
            </table>



            </p>
        </div>
    </div>

    <div id="dialog-modal" title="">
        <p class="validateTips">Semua Inputan Harus Di Isi.</p>
        <p class="validateTips " style="color: red;">*Isi Nomor Kontrak Tanpa Spasi</p>
        <p class="validateTips " style="color: red;">*Isi Nilai Dengan Nilai Total Per Nomer Kontrak</p>
        <fieldset>
            <div class="row" style="width: 550px">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Kode SKPD/Unit</label>
                            <input type="text" id="dinas" class="form-control" style="width:500px;" disabled="true" />
                        </div>
                        <div class="form-group">
                            <label>Nama SKPD/Unit</label>
                            <input type="text" id="nm_u" class="form-control" style="width:500px;" disabled="true" />
                        </div>
                        <div class="form-group">
                            <label>Nomor Kontrak</label>
                            <input type="text" id="kontrak" class="form-control" style="width:500px;" />
                        </div>
                        <div class="form-group">
                            <label>Tanggal Kontrak</label><br>
                            <input type="text" id="tgal" style="width:150px;" />
                        </div>
                        <div class="form-group">
                            <label>Pelaksana Pekerjaan</label><br>
                            <input type="text" id="nmpel" class="form-control" style="width:520px;" />
                        </div>
                        <div class="form-group">
                            <label>Nama Pekerjaan</label>
                            <textarea type="text" id="nm" class="form-control" style="width:500px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Nilai Kontrak</label>
                            <input type="text" id="nilai" class="form-control" style="width:170px; text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                        </div>
                    </div>
                    <div class="card-footer" align="center">
                        <button class="btn btn-info" id="save" plain="true" onclick="javascript:simpan_rek1();">Simpan</button>
                        <button class="btn btn-danger" id="hapus" plain="true" onclick="javascript:hapus();">Hapus</button>
                        <button class="btn btn-warning" plain="true" onclick="javascript:keluar();">Kembali</button>
                    </div>
                </div>
            </div>

        </fieldset>
    </div>

</body>

</html>