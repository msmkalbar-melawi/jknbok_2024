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
            width: 700px;
            height: 70px;
            padding: 0.4em;
        }
    </style>

    <script type="text/javascript">
        var nl = 0;
        var tnl = 0;
        var idx = 0;
        var tidx = 0;
        var oldRek = 0;
        var rek = 0;
        var kode = '';
        var pidx = 0;
        var frek = '';
        var rek5 = '';
        var edit = '';
        var lcstatus = '';
        var no_spp = '';
        var tahun_anggaran = '';
        var yy = '';

        $(document).ready(function() {

            $("#accordion").accordion({
                height: 600
            });
            $("#lockscreen").hide();
            $("#frm").hide();
            $("#dialog-modal").dialog({
                height: 500,
                width: 850,
                modal: true,
                autoOpen: false
            });

            $("#dialog-batal").dialog({
                height: 300,
                width: 700,
                modal: true,
                autoOpen: false
            });

            $('#q_minus')._propAttr('checked', false);

            $("#dialog-modal-rek").dialog({
                height: 450,
                width: 1100,
                modal: true,
                autoOpen: false
            });
            $("#tagih").hide();
            get_skpd();
            get_tahun();
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
                },
                onSelect: function(date) {
                    var m = date.getMonth() + 1;
                    $("#kebutuhan_bulan").attr('value', m);
                    var yy = date.getFullYear();
                    cek_status_ang();
                    cek_status_angkas();
                    var tahunsekarang = date.getFullYear();
                    $("#tahunsekarang").attr("value", tahunsekarang);

                }
            });

            $('#tgl_mulai').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#tgl_akhir').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#tgl_ttd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
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

            $('#tglspd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#cspp').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/load_spp',
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
                }
            });

            $('#cc').combobox({
                url: '<?php echo base_url(); ?>/index.php/spp/load_jenis_beban',
                valueField: 'id',
                textField: 'text',
                onSelect: function(rowIndex, rowData) {
                    validate_tombol();
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

            $('#ttdppk').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spp/load_ttd/PPK',
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
                    $("#nmppk").attr("value", rowData.nama);
                }

            });

            $('#ttd3').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spp/load_ttd_pakpa/PA/KPA',
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
                url: '<?php echo base_url(); ?>index.php/spp/load_ttdppkd/PPKD',
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

            $('#notagih').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/load_no_penagihan',
                idField: 'no_tagih',
                textField: 'no_tagih',
                mode: 'remote',
                fitColumns: true,
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
                    no_tagih = rowData.no_tagih;
                    $("#tgltagih").attr("value", rowData.tgl_tagih);
                    $("#nil").attr("value", rowData.nila);
                    $("#ni").attr("value", rowData.nil);
                    $("#ketentuan").attr("Value", rowData.ket);
                    $("#kontrak").attr("Value", rowData.kontrak);
                    $("#kg").combogrid("setValue", rowData.kegiatan);

                    // validate_jenis();
                    //validate_jenis_edit(3);
                    detail_tagih(no_tagih);
                    $("#rektotal_ls").attr('value', rowData.nila);
                    $("#rektotal1_ls").attr('value', rowData.nil);
                    get_skpd();
                }
            });

            //copy   
            $('#tglgj').combogrid({
                panelWidth: 620,
                //url: '<?php echo base_url(); ?>/index.php/tukd/load_taspen',  
                idField: 'tgl_spp',
                textField: 'tgl_spp',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'tgl_spp',
                            title: 'Tanggal',
                            width: 75,
                            align: 'center'
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            width: 75,
                            align: 'center'
                        },
                        {
                            field: 'nila',
                            title: 'Total Gaji',
                            width: 130,
                            align: 'right'
                        },
                        {
                            field: 'ket',
                            title: 'KET',
                            width: 320,
                            align: 'left'
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    var ststagih = '1';
                    tgl_spp = rowData.tgl_spp;
                    var mtgl_spp = new Date(tgl_spp);
                    var xth = tgl_spp.substr(0, 4);


                    $("#tahunsekarang").attr("value", xth);
                    $("#dd").datebox("setValue", tgl_spp);
                    $("#nilgj").attr("value", rowData.nila);
                    $("#nigj").attr("value", rowData.nil);
                    $("#ketentuan").attr("Value", rowData.ket);
                    //$("#kg").combogrid("setValue",rowData.kegiatan);
                    $("#jns_beban").attr("Value", '4');
                    $("#kebutuhan_bulan").attr('value', mtgl_spp.getMonth() + 1);
                    cek_status_ang();
                    $("#bank1").combogrid("setValue", '05');
                    $('#lanjut').attr("value", '2');
                    //$("#cc").combobox("setValue",'');
                    validate_jenis();
                    $("#cc").combobox("setValue", '1');
                    //$("#jns_beban").attr("Value",'6');
                    //validate_jenis_edit(3);
                    //detail_tagih(no_tagih);
                    $("#rektotal_ls").attr('value', rowData.nila);
                    $("#rektotal1_ls").attr('value', rowData.nil);
                    get_skpd();
                    detail_taspen(tgl_spp, kode);
                }
            });
            //copy

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

            // $('#npwp_combo').combogrid({  
            //     panelWidth:180,  
            //     url: '<?php echo base_url(); ?>/index.php/spp/config_npwp',  
            //         idField:'npwp',  
            //         textField:'npwp',
            //         mode:'remote',  
            //         fitColumns:true,  
            //         columns:[[  
            //                {field:'npwp',title:'NPWP',width:150}
            //            ]],  
            //         onSelect:function(rowIndex,rowData){
            //         $("#npwp").attr("value",rowData.npwp);
            //         }   
            //     });

            // $('#rekening_combo').combogrid({  
            //     panelWidth:180,  
            //     url: '<?php echo base_url(); ?>/index.php/spp/config_npwp',  
            //         idField:'rekening',  
            //         textField:'rekening',
            //         mode:'remote',  
            //         fitColumns:true,  
            //         columns:[[  
            //                {field:'rekening',title:'rekening',width:150}
            //            ]],  
            //         onSelect:function(rowIndex,rowData){
            //         $("#rekening").attr("value",rowData.rekening);
            //         }   
            //     });             



            $('#spp').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/spp/load_spp',
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
                            field: 'no_spp',
                            title: 'Nomor SPP',
                            width: 120
                        },
                        {
                            field: 'tgl_spp',
                            title: 'Tanggal',
                            width: 45
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            width: 80,
                            align: "left"
                        },
                        {
                            field: 'keperluan',
                            title: 'Keterangan',
                            width: 100,
                            align: "left"
                        }
                    ]
                ],
                rowStyler: function(rowIndex, rowData) {
                    if (rowData.status == 1) {
                        return 'background-color:#4bbe68;color:white';
                    }
                },
                onSelect: function(rowIndex, rowData) {
                    no_spp = rowData.no_spp;
                    urut = rowData.urut;
                    kode = rowData.kd_skpd;
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
                    status = rowData.status;
                    kegi = rowData.kd_sub_kegiatan;
                    nm = rowData.nm_sub_kegiatan;
                    kprog = rowData.kd_program;
                    nprog = rowData.nm_program;
                    dir = rowData.dir;
                    notagih = rowData.no_tagih;
                    tgltagih = rowData.tgl_tagih;
                    sttagih = rowData.sts_tagih;
                    alamat = rowData.alamat;
                    kontrak = rowData.kontrak;
                    lanjut = rowData.lanjut;
                    tgl_mulai = rowData.tgl_mulai;
                    tgl_akhir = rowData.tgl_akhir;
                    tot_spp = rowData.tot_spp_;
                    bidangg = rowData.bidang;
                    csp2d_batal = rowData.sp2d_batal;
                    cket_batal = rowData.ket_batal;
                    //alert(bidangg);
                    get(urut, no_spp, kode, sp, tg, bl, jn, kep, np, rekan, bk, ning, status, kegi, nm, kprog, nprog, dir, notagih, tgltagih, sttagih, alamat, kontrak, lanjut, tgl_mulai, tgl_akhir, jns_bbn, tot_spp, csp2d_batal, cket_batal);
                    //det();  
                    //   alert(csp2d_batal);     
                    detail_trans_3();
                    validate_kegiatan();
                    load_sum_spp();
                    edit = 'T';
                    lcstatus = 'edit';
                },
                onDblClickRow: function(rowIndex, rowData) {
                    section2();

                    //copy
                    // $("#status_taspen").attr("checked",false); 
                    // cek_taspen();
                    //copy   
                }
            });

            var jenis = 51;

            $('#sp').combogrid({
                panelWidth: 500,
                //url: '<?php echo base_url(); ?>/index.php/tukd/spd1', 
                queryParams: ({
                    cjenis: jenis
                }),
                idField: 'no_spd',
                textField: 'no_spd',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'no_spd',
                            title: 'No SPD',
                            width: 100
                        },
                        {
                            field: 'tgl_spd2',
                            title: 'Tanggal',
                            align: 'left',
                            width: 30
                        },
                        {
                            field: 'bulan',
                            title: 'Bulan SPD',
                            align: 'left',
                            width: 40
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            align: 'right',
                            width: 40
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    spd = rowData.no_spd;
                    tglspd = rowData.tgl_spd;
                    $("#tglspd").datebox("setValue", tglspd);
                    validate_kegi(spd);
                }
            });


            $('#kg').combogrid({
                panelWidth: 500,
                url: '<?php echo base_url(); ?>/index.php/spp/kegi',
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'kd_sub_kegiatan',
                            title: 'Kode',
                            width: 60
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama',
                            align: 'left',
                            width: 120
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kegi = rowData.kd_sub_kegiatan;
                    nmkegi = rowData.nm_sub_kegiatan;
                    bidang = rowData.kdbidang;
                    $("#bidangg").attr("value", bidang);
                    $("#nm_kg").attr("value", rowData.nm_sub_kegiatan);
                    prog = rowData.kd_program;
                    $("#kp").attr("value", rowData.kd_program);
                    nmprog = rowData.nm_program;
                    $("#nm_kp").attr("value", rowData.nm_program);
                    nilai = rowData.nilai;
                    det();
                    // clear datagrid
                    $('#dgsppls').datagrid('reload');
                    $("#rektotal_ls").attr("Value", 0);
                    // ----------
                    // $('#dgsppls').datagrid('loadData',[]);
                    // $("#dgsppls").remove();

                }
            });


            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/spp/select_data2',
                autoRowHeight: "true",
                idField: 'id',
                toolbar: "#toolbar",
                rownumbers: "true",
                fitColumns: false,
                singleSelect: "true",
            });


            $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                autoRowHeight: "true",
                idField: 'id',
                toolbar: "#toolbar",
                rownumbers: "true",
                fitColumns: false,
                singleSelect: "true",
            });


            $('#dgsppls').edatagrid({
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
                            field: 'kdsubkegiatan',
                            title: 'Sub Kegiatan',
                            width: 160,
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
                            width: 280
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
                            width: 100,
                            align: 'center',
                            hidden: 'true'
                        },
                        {
                            field: 'nmsumber',
                            title: 'Sumber Dana',
                            width: 100,
                            align: 'center'
                        },
                        {
                            field: 'hapus',
                            title: 'Hapus',
                            width: 50,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                            }
                        }
                    ]
                ]
            });


            $('#rek_skpd').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spp/skpd_2',
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
                    kode = rowData.kd_skpd;
                    $("#rek_nmskpd").attr("value", rowData.nm_skpd.toUpperCase());
                }
            });


            $('#rek_kegi').combogrid({
                panelWidth: 700,
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                columns: [
                    [{
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Kegiatan',
                            width: 150
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            width: 700
                        }
                    ]
                ]
            });


            $('#rek_reke').combogrid({
                panelWidth: 700,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                mode: 'remote',
                columns: [
                    [{
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 150
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 700
                        }
                    ]
                ]
            });

            $('#sumber_dn').combogrid({
                panelWidth: 200,
                idField: 'sumber_dana',
                textField: 'sumber_dana',
                mode: 'remote',
                columns: [
                    [{
                            field: 'sumber_dana',
                            title: 'Kode',
                            width: 180
                        },
                        {
                            field: 'nm_sumber_dana',
                            title: 'Sumber Dana',
                            width: 180
                        }
                    ]
                ]
            });

        });


        function get_skpd() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/Spp/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var kd = data.kd_skpd;
                    var kd1 = data.nm_skpd;
                    // alert(kd);
                    $("#dn").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    $("#rek_skpd").combogrid("setValue", data.kd_skpd);
                    $("#rek_nmskpd").attr("value", data.nm_skpd.toUpperCase());
                    kode = data.kd_skpd;
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

        function get_spp() {

            if (lcstatus == 'tambah') {
                $("#no_spp").attr("value", '');
                var jenis_ls = document.getElementById('jns_beban').value;
                var bulan_spp = document.getElementById('kebutuhan_bulan').value;
                var jns = "";
                var jns_spp = "LS";

                if (jenis_ls == 4) {
                    jns = "LS";
                    $('#dgsppls').datagrid('selectAll');
                    var data = $('#dgsppls').datagrid('getData');
                    var rows = data.rows;
                    for (var p = 0; p < rows.length; p++) {
                        rek5 = rows[p].kdrek5;
                        if (rek5.substr(0, 5) == '51101') {
                            jns = "LS";
                        }
                    }
                }

                $.ajax({
                    url: '<?php echo base_url(); ?>index.php/spp/config_spp/' + bulan_spp + '/' + jenis_ls + '/' + jns_spp,
                    type: "POST",
                    dataType: "json",
                    success: function(data) {
                        no_spp = data.nomor;
                        bulan = data.bulan;
                        var inisial = no_spp + "/LS/" + kode + "/M/" + bulan + "/" + tahun_anggaran;
                        // $("#no_spp").attr('disabled',true);
                        $("#no_spp").attr("value", inisial);
                        $("#dd_spp").attr("value", no_spp);
                    }
                });
            }
        }

        function cek_status_ang() {
            var tgl_cek = $('#dd').datebox('getValue');
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spp/cek_status_ang',
                data: ({
                    tgl_cek: tgl_cek
                }),
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var jns_ang = data.jns_ang;
                    var nm_ang = data.nm_ang;
                    $("#status_ang").attr("value", jns_ang);
                    $("#nm_ang").attr("value", nm_ang);
                }
            });
        }

        function cek_status_angkas() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spp/cek_status_angkas',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var kode = data.nama;
                    // alert(kode);
                    $("#status_angkas").attr("value", kode);
                    validate_rekening(kode);
                }
            });
        }


        function data_notagih() {
            $('#notagih').combogrid({
                url: '<?php echo base_url(); ?>/index.php/spp/load_no_penagihan'
            });
        }

        function detail_tagih(no_tagih) {
            //alert("aaa");
            $(function() {
                $('#dgsppls').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data_tagih',
                    queryParams: ({
                        no: no_tagih
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
                        tkdkegiatan = rowData.kdkegiatan;
                        tkdrek6 = rowData.kdrek6;
                        tnmrek6 = rowData.nmrek6;
                        tnilai1 = rowData.nilai1;
                        tsumber = rowData.sumber;
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
                                field: 'kdkegiatan',
                                title: 'Kode Kegiatan',
                                width: 160,
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
                                width: 280
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
                                width: 100,
                                align: 'right',
                                hidden: 'true'
                            },
                            {
                                field: 'nmsumber',
                                title: 'Nama Sumber',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'hapus',
                                title: 'Hapus',
                                width: 50,
                                align: "center",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                                }
                            }
                        ]
                    ]
                });
            });
        }
        //copy
        function detail_taspen(tglsppt, skpd) {
            skpd = kode;
            //alert("aaa");
            $(function() {
                $('#dgsppls').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data_taspen',
                    queryParams: ({
                        tgl: tglsppt,
                        skpd: skpd
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
                        tkdkegiatan = rowData.kdkegiatan;
                        tkdrek6 = rowData.kdrek6;
                        tnmrek6 = rowData.nmrek6;
                        tnilai1 = rowData.nilai1;
                        tsumber = rowData.sumber;
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
                                field: 'kdkegiatan',
                                title: 'Kode Kegiatan',
                                width: 160,
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
                                width: 280
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
                                width: 100,
                                align: 'right',
                                hidden: 'true'
                            },
                            {
                                field: 'nmsumber',
                                title: 'Sumber',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'hapus',
                                title: 'Hapus',
                                width: 50,
                                align: "center",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                                }
                            }
                        ]
                    ]
                });
            });
        }
        //copy



        function validate_kegiatan() {
            var kode_s = document.getElementById('dn').value;
            $(function() {
                $('#rek_kegi').combogrid({
                    panelWidth: 700,
                    idField: 'kd_sub_kegiatan',
                    textField: 'kd_sub_kegiatan',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/spp/load_trskpd_ar_2',
                    queryParams: ({
                        kdskpd: kode_s
                    }),
                    columns: [
                        [{
                                field: 'kd_sub_kegiatan',
                                title: 'Kode Kegiatan',
                                width: 150
                            },
                            {
                                field: 'nm_sub_kegiatan',
                                title: 'Nama Kegiatan',
                                width: 700
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        $("#nm_rek_kegi").attr("value", rowData.nm_sub_kegiatan);
                        $("#rek_reke").combogrid("setValue", '');
                        //kd_kegia = rowData.kd_sub_kegiatan;
                        validate_rekening();

                    }
                });
            });
        }


        function validate_rekening() {
            // alert(kode);
            $('#dgsppls').datagrid('selectAll');
            var rows = $('#dgsppls').datagrid('getSelections');
            frek = '';
            rek5 = '';
            for (var p = 0; p < rows.length; p++) {
                rek5 = rows[p].kdrek6;
                if (p > 0) {
                    frek = frek + ',' + rek5;
                } else {
                    frek = rek5;
                }
            }
            var beban = document.getElementById('jns_beban').value;
            var kode_s = document.getElementById('dn').value;
            var kode_keg = $('#rek_kegi').combogrid('getValue');
            var status_angkas = document.getElementById('status_angkas').value;
            // alert(kode_keg);
            // alert(status_angkas);
            var nospp = document.getElementById('no_spp').value;
            var spdss = $('#sp').combogrid('getValue');
            var kebutuhanbulan = document.getElementById('kebutuhan_bulan').value;


            $(function() {
                $('#rek_reke').combogrid({
                    panelWidth: 700,
                    idField: 'kd_rek6',
                    textField: 'kd_rek6',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/spp/load_rek_ar',
                    queryParams: ({
                        kdkegiatan: kode_keg,
                        kdrek: frek,
                        bbn: beban
                    }),
                    columns: [
                        [{
                                field: 'kd_rek6',
                                title: 'Kode Rekening',
                                width: 150
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 700
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        var koderek = rowData.kd_rek6;
                        var nmarek = rowData.nm_rek6;
                        $("#nm_rek_reke").attr("value", nmarek);
                        $("#sumber_dn").combogrid('setValue', '');
                        $("#nm_sumber_dn").attr("value", '');
                        $("#rek_nilai_ang_dana").attr("Value", number_format(0, 2, '.', ','));
                        $("#rek_nilai_spp_dana").attr("Value", number_format(0, 2, '.', ','));
                        $("#rek_nilai_sisa_dana").attr("Value", number_format(0, 2, '.', ','));
                        validasi_sumber_dana();


                        if ((koderek == "510101080001") || (koderek == "5110105")) {
                            $("#q_minus").attr('disabled', false);
                        } else {
                            $('#q_minus')._propAttr('checked', false);
                            $("#q_minus").attr('disabled', true);
                        }

                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            data: ({
                                kegiatan: kode_keg,
                                kdrek6: koderek,
                                kd_skpd: kode_s,
                                no_spp: nospp
                            }),
                            url: '<?php echo base_url(); ?>index.php/spp/jumlah_ang_spp',
                            success: function(data) {
                                $.each(data, function(i, n) {
                                    $("#rek_nilai_ang").attr("Value", n['nilai']);
                                    var n_ang = n['nilai'];
                                    var n_spp = n['nilai_spp_lalu'];
                                    var n_sisa = angka(n_ang) - angka(n_spp);
                                    $("#rek_nilai_sisa").attr("Value", number_format(n_sisa, 2, '.', ','));
                                    var tgl_spd = $('#tglspd').datebox('getValue');
                                    var tgl_sppnow = $('#dd').datebox('getValue');
                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        data: ({
                                            kegiatan: kode_keg,
                                            kd_skpd: kode_s,
                                            tglspd: tgl_spd,
                                            kdrek6: koderek,
                                            beban: beban,
                                            tgl_spp: tgl_sppnow
                                        }),
                                        url: '<?php echo base_url(); ?>index.php/spp/total_spd',
                                        success: function(data) {
                                            $.each(data, function(i, n) {
                                                $("#total_spd").attr("Value", n['nilai']);
                                                var n_totalspd = n['nilai'];
                                                // var n_sisa = angka(n_ang) - angka(n_spp) ;
                                                // $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
                                            });
                                        }
                                    });

                                    $.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        data: ({
                                            kegiatan: kode_keg,
                                            kd_skpd: kode_s,
                                            tglspd: tgl_spd,
                                            spd: spdss,
                                            kdrek6: koderek,
                                            beban: beban,
                                            status_angkas: status_angkas,
                                            tglspp: tgl_sppnow

                                        }),
                                        url: '<?php echo base_url(); ?>index.php/spp/total_angkas',
                                        success: function(data) {
                                            $.each(data, function(i, n) {
                                                $("#total_angkas").attr("Value", n['nilai']);
                                                var n_totalangkas = n['nilai'];
                                                // var n_sisa = angka(n_ang) - angka(n_spp) ;
                                                // $("#rek_nilai_sisa").attr("Value",number_format(n_sisa,2,'.',','));
                                            });
                                        }
                                    });

                                    var param_total_trans = {
                                        giat: kode_keg,
                                        kode: kode_s,
                                        kdrek6: koderek,
                                        beban: beban,
                                        tgl: tgl_sppnow
                                    };
                                    if ($('#status').is(':checked')) {
                                        param_total_trans.no_tagih = $('#notagih').combogrid('getValue')
                                    }
                                });
                            }
                        });
                    }
                });
            });
            $('#dgsppls').datagrid('unselectAll');
        }


        function validasi_sumber_dana() {

            var kode_keg = $('#rek_kegi').combogrid('getValue');
            var koderek = $('#rek_reke').combogrid('getValue');
            var statusang = document.getElementById('status_ang').value;

            $(function() {
                $('#sumber_dn').combogrid({
                    panelWidth: 180,
                    idField: 'sumber_dana',
                    // textField:'sumber_dana',  
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/spp/load_reksumber_dana',
                    queryParams: ({
                        giat: kode_keg,
                        kd: kode,
                        rek: koderek,
                        sttang: statusang
                    }),
                    columns: [
                        [{
                                field: 'sumber_dana',
                                title: 'Kode',
                                width: 180
                            }
                            // {field:'nm_sumber_dana',title:'Sumber Dana',width:250}
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        var parsumber = rowData.sumber_dana;
                        var parnmsumber = rowData.nm_sumber_dana;
                        var vnilaidana = rowData.nilaidana;
                        var lalu_ubahspp = angka(document.getElementById('rek_nilai_spp').value);

                        $("#nm_sumber_dn").attr("Value", parnmsumber);
                        $("#rek_nilai_ang_dana").attr("Value", number_format(vnilaidana, 2, '.', ','));
                        $("#rek_nilai_spp_dana").attr("Value", number_format(lalu_ubahspp, 2, '.', ','));
                        load_total_trans();
                    }
                });
            });
        }

        function numberFormat(n) {
            let nilai = number_format(n, 2, '.', ',');
            return nilai;
        }
        //Create By Hakam
        function load_total_trans() {
            var giat = $('#rek_kegi').combogrid('getValue');
            var kode = document.getElementById('dn').value;
            var koderek = $('#rek_reke').combogrid('getValue');
            var sumber_dn = $('#sumber_dn').combogrid('getValue');
            $(function() {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/spp/load_total_trans_spd",
                    dataType: "json",
                    data: ({
                        cgiat: giat,
                        ckode: kode,
                        ckdrek6: koderek,
                        csumber_dn: sumber_dn
                    }),
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#nilai_spd_lalu").attr("value", n['total']);
                            $("#nilai_angkas_lalu").attr("value", n['total']);
                            $("#rek_nilai_spp").attr("value", n['total']);
                            $("#rek_nilai_spp_dana").attr("value", n['total']);

                        });
                        $("#rek").combogrid('enable');
                        // Sisa SPD
                        let total_spd = angka(document.getElementById('total_spd').value);
                        let realisasi_spd = angka(document.getElementById('nilai_spd_lalu').value);
                        let sisa_spd = total_spd - realisasi_spd;
                        $("#nilai_sisa_spd").val(numberFormat(sisa_spd));
                        // Sisa Angkas
                        let tot_angkas = angka(document.getElementById('total_angkas').value);
                        let tot_trans_angkas = angka(document.getElementById('nilai_angkas_lalu').value);
                        let sisa_angkas = tot_angkas - tot_trans_angkas;
                        $("#nilai_sisa_angkas").val(numberFormat(sisa_angkas));
                        // Sisa Anggaran Rekening
                        let tot_ang = angka(document.getElementById('rek_nilai_ang').value);
                        let tot_lalu = angka(document.getElementById('rek_nilai_spp').value);
                        let sisa_lalu = tot_ang - tot_lalu;
                        $("#rek_nilai_sisa").val(numberFormat(sisa_lalu));
                        // Sisa Anggaran Sumber dana
                        let tot_ang_sd = angka(document.getElementById('rek_nilai_ang_dana').value);
                        let tot_lalu_sd = angka(document.getElementById('rek_nilai_spp_dana').value);
                        let sisasumber_lalu = tot_ang_sd - tot_lalu_sd;
                        $("#rek_nilai_sisa_dana").val(numberFormat(sisasumber_lalu));
                    }
                });
            });
        }



        function validate_spd(kode) {
            $(function() {
                $('#sp').combogrid({
                    panelWidth: 500,
                    url: '<?php echo base_url(); ?>/index.php/spp/spd11/' + kode,
                    idField: 'no_spd',
                    textField: 'no_spd',
                    mode: 'remote',
                    fitColumns: true
                });
            });
        }


        function validate_kegi(spd) {
            $(function() {
                $('#kg').combogrid({
                    panelWidth: 500,
                    url: '<?php echo base_url(); ?>/index.php/spp/kegiatan_spd',
                    queryParams: ({
                        spd: spd
                    }),
                    idField: 'kd_sub_kegiatan',
                    textField: 'kd_sub_kegiatan',
                    mode: 'remote',
                    fitColumns: true
                });
            });
        }


        function det() {
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data2',
                    queryParams: ({
                        giat: kegi
                    }),
                    idField: 'id',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "true",
                    singleSelect: false,
                    onLoadSuccess: function(data) {
                        detail1();
                    },
                    onClickRow: function(rowIndex, rowData) {
                        keg = rowData.kd_sub_kegiatan;
                        rk = rowData.kd_rek6;
                        nkeg = rowData.nm_sub_kegiatan;
                        nrek = rowData.nm_rek6;
                        ang = rowData.a;
                        kel = rowData.b;
                        sisa = ang - kel;
                        simpan(keg, rk, nkeg, nrek, sisa);
                        detail1();
                    },
                    columns: [
                        [{
                                field: 'ck',
                                title: 'ck',
                                checkbox: true,
                                hidden: true
                            },
                            {
                                field: 'pilih',
                                title: 'pilih',
                                width: 20,
                                align: 'center',
                                checkbox: true,
                                hidden: true
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
                                width: 300
                            },
                            {
                                field: 'a',
                                title: 'Nilai Anggaran',
                                width: 100,
                                align: 'right',
                                hidden: true
                            },
                            {
                                field: 'b',
                                title: 'SPP Lalu',
                                width: 100,
                                align: 'right',
                                hidden: true
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai Anggaran',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'total',
                                title: 'SPP Lalu',
                                width: 100,
                                align: 'right'
                            }
                        ]
                    ]
                });
            });
        }


        function det_baru() {
            var kegi = '';
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data2',
                    queryParams: ({
                        giat: kegi
                    }),
                    idField: 'id',
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
                                field: 'pilih',
                                title: 'pilih',
                                width: 20,
                                align: 'center',
                                checkbox: true,
                                hidden: true
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
                                width: 300
                            },
                            {
                                field: 'a',
                                title: 'Nilai Anggaran',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'b',
                                title: 'SPP Lalu',
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
                var spp = document.getElementById('no_spp').value;
                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                    queryParams: ({
                        spp: spp
                    }),
                    idField: 'idx',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: false,
                    autoRowHeight: "true",
                    singleSelect: false,
                    onLoadSuccess: function(data) {
                        //load_sum_spp();                        
                    },
                    onSelect: function(rowIndex, rowData) {
                        kd = rowIndex;
                    },
                    onAfterEdit: function(rowIndex, rowData, changes) {
                        subkegiatan = rowData.kdsubkegiatan;
                        nsubkegiatan = rowData.nmsubkegiatan;
                        rekeing = rowData.kdrek6;
                        nrekeing = rowData.nmrek6;
                        nilai = rowData.nilai1;
                        si = rowData.sis;
                        kd = rowIndex;
                        dsimpan(subkegiatan, rekeing, nsubkegiatan, nrekeing, nilai, si, kd);
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
                                title: 'Kode Kegiatan',
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
                                width: 300
                            },
                            {
                                field: 'sisa',
                                title: 'Sisa',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'nilai1',
                                title: 'Nilai',
                                width: 100,
                                align: 'right',
                                editor: {
                                    type: "numberbox"
                                }
                            }
                        ]
                    ]
                });
            });
        }


        function get(urut, no_spp, kd_skpd, no_spd, tgl_spp, bulan, jns_spp, keperluan, npwp, rekanan, bank, rekening, status, giat, nmgiat, prog, nmprog, pim, notagih, tgltagih, ststagih, alamat, kontrak, lanjut, tgl_mulai, tgl_akhir, jns_bbn, tot_spp, sp2d_batal, ket_batal) {
            $("#dd_spp").attr("value", urut);
            $("#no_spp").attr("value", no_spp);
            $("#no_spp_hide").attr("value", no_spp);
            $("#no_simpan").attr("value", no_spp);
            $("#sp").combogrid("setValue", no_spd);
            $("#dd").datebox("setValue", tgl_spp);
            $("#tgl_mulai").datebox("setValue", tgl_mulai);
            $("#tgl_akhir").datebox("setValue", tgl_akhir);
            $("#kebutuhan_bulan").attr("Value", bulan);
            $("#ketentuan").attr("Value", keperluan);
            $("#jns_beban").attr("Value", jns_spp);
            $("#npwp").attr("Value", npwp);
            $("#rekanan").combogrid("setValue", rekanan);
            $("#dir").attr("Value", pim);
            $("#alamat").attr("Value", alamat);
            $("#kontrak").attr("Value", kontrak);
            $("#lanjut").attr("Value", lanjut);
            $("#bank1").combogrid("setValue", bank);
            $("#rekening").attr("Value", rekening);
            $("#kg").combogrid("setValue", giat);
            $("#nm_kg").attr("Value", nmgiat);
            $("#kp").attr("setValue", prog);
            $("#nm_kp").attr("Value", nmprog);
            $("#notagih").combogrid("setValue", notagih);
            $("#tgltagih").attr("Value", tgltagih);
            $("#ket_batal").attr("Value", ket_batal);
            validate_jenis_edit(jns_bbn);
            validate_tombol();


            $("#status").attr("checked", false);
            if (ststagih == 1) {
                $("#status").attr("checked", true);
                $("#tagih").show();
                $("#nil").attr('value', number_format(tot_spp, 2, '.', ','));
            } else {
                $("#status").attr("checked", false);
                $("#tagih").hide();
                $("#nil").attr("value", '');
            }

            tombol(status, csp2d_batal);
            //  alert(status);         
        }

        //copy
        function cek_taspen() {
            $("#tglgj").combogrid("clear");
            $("#nilgj").attr("value", '');
            $("#nigj").attr("value", '');
            if (document.getElementById("status_taspen").checked == true) {
                $("#gjtaspen").show();
            } else {
                $("#gjtaspen").hide();
            }
        }
        //copy

        function kosong() {
            validate_kegiatan();
            $("#no_spp").attr("value", '');
            $("#no_spp_hide").attr("value", '');
            $("#dd_spp").attr("value", '');
            $("#no_simpan").attr("value", '');
            $("#sp").combogrid("setValue", '');
            $("#dd").datebox("setValue", '');
            $("#tgl_mulai").datebox("setValue", '');
            $("#tgl_akhir").datebox("setValue", '');
            $("#tglspd").datebox("setValue", '');
            $("#kebutuhan_bulan").attr("Value", '');
            $("#ketentuan").attr("Value", '');
            $("#jns_beban").attr("Value", '');
            $("#npwp").attr("Value", '');
            $("#rekanan").combogrid("setValue", '');
            $("#dir").attr("Value", '');
            $("#bank1").combogrid("setValue", '');
            $("#rekening").attr("Value", '');
            $("#kg").combogrid("setValue", '');
            $("#nm_kg").attr("Value", '');
            $("#kg").combogrid("setValue", '');
            $("#nm_kg").attr("Value", '');
            $("#nama_bank").attr("Value", '');
            $("#kontrak").attr("Value", '');
            $("#lanjut").attr("Value", '');
            $("#alamat").attr("Value", '');
            $("#kp").attr("setValue", '');
            $("#nm_kp").attr("Value", '');
            document.getElementById("p1").innerHTML = "";
            $("#sp").combogrid("clear");
            $("#kg").combogrid("clear");
            $("#cc").combobox("setValue", '');
            $("#notagih").combogrid("clear");
            //det_baru();
            $('#save').linkbutton('enable');
            $('#batal').linkbutton('enable');
            $('#del1').linkbutton('enable');
            $('#cetak').linkbutton('disable');
            detail_kosong();

            var pidx = 0;
            edit = 'F';
            data_notagih();
            $("#rektotal_ls").attr("Value", 0);
            $("#rektotal1_ls").attr("Value", 0);

            lcstatus = 'tambah';
            //$("#notagih").combogrid("setValue",'');
            $("#tgltagih").attr("value", '');
            //$("#nmskpd").attr("value",'');
            $("#nil").attr("value", '');
            $("#ni").attr("value", '');
            $("#status").attr("checked", false);
            $("#tagih").hide();
            $("#status_taspen").attr("checked", false);
            $("#nilgj").attr('value', number_format(0, 2, '.', ','));
            // cek_taspen();              

        }



        function getRowIndex(target) {
            var tr = $(target).closest('tr.datagrid-row');
            return parseInt(tr.attr('datagrid-row-index'));
        }



        function simpan(giat, reke, nkeg, nrek, sisa) {
            var spp = document.getElementById('no_spp').value;
            var cskpd = kode;
            var cspd = spd;

            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        cskpd: cskpd,
                        cspd: spd,
                        cspp: spp,
                        cgiat: giat,
                        crek: reke,
                        cnmgiat: nkeg,
                        cnmrek: nrek,
                        sspp: sisa
                    }),
                    dataType: "json",
                    url: '<?php echo base_url(); ?>/index.php/spp/tsimpan'
                });
            });
        }


        function cetak() {
            var nom = document.getElementById("no_spp").value;
            $("#cspp").combogrid("setValue", nom);
            $("#dialog-modal").dialog('open');
        }


        function keluar() {
            $("#dialog-modal").dialog('close');
        }


        function keluar_rek() {
            $("#dialog-modal-rek").dialog('close');
            $("#dgsppls").datagrid("unselectAll");

            $("#rek_nilai").attr("Value", 0);
            $("#rek_nilai_ang").attr("Value", 0);
            $("#rek_nilai_spp").attr("Value", 0);
            $("#rek_nilai_sisa").attr("Value", 0);

            $("#rek_nilai_ang_dana").attr("Value", 0);
            $("#rek_nilai_spp_dana").attr("Value", 0);
            $("#rek_nilai_sisa_dana").attr("Value", 0);
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#spp').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/load_spp',
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
        }


        function section2() {
            $(document).ready(function() {
                $('#section2').click();

            });
        }


        function section3() {
            $(document).ready(function() {
                $('#section3').click();
            });
        }


        function hsimpan() {
            var a = (document.getElementById('no_spp').value).split(" ").join("");
            var a_hide = document.getElementById('no_spp_hide').value;
            var a_dd = a.substr(0, 6);
            var b = $('#dd').datebox('getValue');
            var c = document.getElementById('jns_beban').value;
            var d = document.getElementById('kebutuhan_bulan').value;
            var e = document.getElementById('ketentuan').value;
            var f = $("#rekanan").combogrid("getValue");
            var f1 = document.getElementById('dir').value;
            var nm_rekan = document.getElementById('nama_rekanan').value;
            var g = $("#bank1").combogrid("getValue");
            var h = document.getElementById('npwp').value;
            var i = document.getElementById('rekening').value;
            var j = document.getElementById('nmskpd').value.trim();
            var k1 = document.getElementById('rektotal1_ls').value;
            var l = document.getElementById('nm_kg').value;
            var m = document.getElementById('kp').value;
            var n = document.getElementById('nm_kp').value;
            var alamat = document.getElementById('alamat').value;
            var kontrak = document.getElementById('kontrak').value;
            var lanjut = document.getElementById('lanjut').value;
            var tgl_mulai = $('#tgl_mulai').datebox('getValue');
            var tgl_akhir = $('#tgl_akhir').datebox('getValue');
            var o = document.getElementById('status').checked;
            var jenis = $("#cc").combobox("getValue");
            var z = $("#sp").combogrid("getValue");
            var y = $("#kg").combogrid("getValue");
            var k = angka(k1);
            var p = $('#notagih').combogrid('getValue');
            var q = document.getElementById('tgltagih').value;
            var kd_bidang = document.getElementById('bidangg').value;
            //var tglsppt     = $('#tglgj').combogrid("getValue") ; 
            //alert(kd_bidang);
            //nilgj
            var xx = y.substr(0, 21);
            if (o == false) {
                o = 0;
                var p = '';
                var q = '';
            } else {
                o = 1;
            }
            // if (o == 1 && p == '') {
            //     alert("Nomor Penagihan Tidak Boleh Kosong...!!!");
            //     exit();
            // }
            if (a == '') {
                alert("Isi Nomor SPP Terlebih Dahulu...!!!");
                exit();
            }

            if (z == '') {
                alert("Isi Nomor SPD Terlebih Dahulu...!!!");
                exit();
            }

            if (h == '') {
                alert("Isi NPWP Terlebih Dahulu...!!!");
                exit();
            }

            if (i == '') {
                alert("Isi Rekening Terlebih Dahulu...!!!");
                exit();
            }

            if (e == '') {
                alert("Isi Keperluan Terlebih Dahulu...!!!");
                exit();
            }

            if (g == '') {
                alert("Isi Bank Terlebih Dahulu...!!!");
                exit();
            }

            if (b == '') {
                alert("Isi Tanggal Terlebih Dahulu...!!!");
                exit();
            }

            if (kode == '') {
                alert("Isi SKPD Terlebih Dahulu...!!!");
                exit();
            }

            var tahun_input = b.substring(0, 4);
            //khusus januri
            if (tahun_input != tahun_anggaran) {
                alert('Tahun tidak sama dengan tahun Anggaran');
                exit();
            }

            if (c == '') {
                alert("Isi Beban Terlebih Dahulu...!!!");
                exit();
            }

            if (d == '') {
                alert("Isi Kebutuhan Bulan Terlebih Dahulu...!!!");
                exit();
            }

            if (y == '') {
                alert("Isi Kode Kegiatan Terlebih Dahulu...!!!");
                exit();
            }
            var len_y = y.length;
            if (len_y != 15) {
                alert("Kode Kegiatan Salah!");
                exit();
            }

            if (jenis.trim() == 'Pilih Jenis Beban') {
                alert('Jenis Belum Dipilih');
                return;
            }
            if (l == '') {
                alert("Pilih Kegiatan Terlebih Dahulu...!!!");
                exit();
            }
            // if (c == 6 && jenis == 3 && kontrak == '') {
            //     alert("Nomor Kontrak Harus Diisi...!!!");
            //     exit();
            // }
            if (c == 6 && jenis == 6 && kontrak == '') {
                alert("Nomor Kontrak Harus Diisi...!!!");
                exit();
            }
            // if ( c == 6 && jenis== 2 && f=='' ){
            //     alert("Rekanan Harus Diisi...!!!") ;
            //     exit();
            // }
            if (c == 6 && jenis == 3 && f == '') {
                alert("Rekanan Harus Diisi...!!!");
                exit();
            }
            if (c == 6 && jenis == 3 && f1 == '') {
                alert("Direktur/Nama Rekanan Harus Diisi...!!!");
                exit();
            }

            if (c == 6 && jenis == 6 && p == '') {
                alert("Nomor Penagihan Tidak Boleh Kosong...!!!");
                exit();
            }
            var lenket = e.length;
            if (lenket > 1000) {
                alert('Keterangan Tidak boleh lebih dari 1000 karakter');
                exit();
            }


            //Cek Datagrid
            var ctot_det = 0;
            $('#dgsppls').datagrid('selectAll');
            var rows = $('#dgsppls').datagrid('getSelections');
            for (var x = 0; x < rows.length; x++) {
                cnilai3 = angka(rows[x].nilai1);
                ctot_det = ctot_det + cnilai3;
            }

            /*if(nilgj==''){                
            if (k != ctot_det){
                alert('Nilai Rincian tidak sama dengan Total, Silakan Refresh kembali halaman ini!');
                exit();
            }
            }*/

            if (ctot_det == 0) {
                alert('Rincian Rekening Kosong');
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
                                if (kode == '') {
                                    alert("Isi SKPD Terlebih Dahulu...!!!");
                                    return;
                                }

                                if (j == '') {
                                    alert("Nama SKPD Kosong...!!!");
                                    return;
                                }

                                //---------

                                lcinsert = "(no_spp,  kd_skpd,    keperluan, bulan,   no_spd,    jns_spp, jns_beban, bank,    id_perusahaan,  no_rek,  npwp,    nm_skpd,  tgl_spp, status, username,     last_update,   nilai,    no_bukti,     kd_sub_kegiatan,  nm_sub_kegiatan,  kd_program,  nm_program,  pimpinan,  no_tagih,    tgl_tagih,  sts_tagih, no_bukti2, no_bukti3, no_bukti4, no_bukti5, no_spd2, no_spd3, no_spd4 , alamat, kontrak, lanjut, tgl_mulai, tgl_akhir, urut, nmrekan)";
                                lcvalues = "('" + a + "', '" + kode + "', '" + e + "',   '" + d + "', '" + spd + "', '" + c + "', '" + jenis + "', '" + g + "', '" + f + "',  '" + i + "', '" + h + "', '" + j + "',  '" + b + "', '0',    '',           '',            '" + k + "',  '',           '" + y + "',   '" + l + "',      '" + m + "',     '" + n + "',     '" + f1 + "',  '" + p + "',     '" + q + "',    '" + o + "',    '',       '',        '',        '',        '',      '',      '',      '" + alamat + "', '" + kontrak + "','" + lanjut + "','" + tgl_mulai + "','" + tgl_akhir + "','" + a_dd + "','" + nm_rekan + "')";
                                //lcupdate = " UPDATE trhtagih SET sts_tagih='1' where no_bukti='"+p+"' "; 

                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo base_url(); ?>/index.php/spp/simpan_tukd_spp',
                                        //copy
                                        data: ({
                                            tabel: 'trhspp',
                                            kolom: lcinsert,
                                            nilai: lcvalues,
                                            cid: 'no_spp',
                                            jns_spp: c,
                                            jns_beban: jenis,
                                            lcid: a,
                                            tagih: p
                                        }),
                                        //copy
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
                                                alert('Data Sudah Ada..!!');
                                                exit();
                                            } else {
                                                $('#dgsppls').datagrid('selectAll');
                                                var rows = $('#dgsppls').datagrid('getSelections');

                                                for (var i = 0; i < rows.length; i++) {
                                                    cidx = rows[i].idx;
                                                    ckdgiat = rows[i].kdkegiatan;
                                                    ckdrek = rows[i].kdrek6;
                                                    cnmrek = rows[i].nmrek6;
                                                    cnilai = angka(rows[i].nilai1);
                                                    cgiat = ckdgiat.substr(0, 21);
                                                    csumber = rows[i].sumber;
                                                    no = i + 1;
                                                    if (i > 0) {
                                                        csql = csql + "," + "('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + spd + "','" + kd_bidang + "','" + csumber + "')";
                                                    } else {
                                                        csql = "values('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + spd + "','" + kd_bidang + "','" + csumber + "')";
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
                                                        url: '<?php echo base_url(); ?>/index.php/spp/dsimpan_ag_ls',
                                                        success: function(data) {
                                                            status = data.pesan;
                                                            if (status == '1') {
                                                                $("#loading").dialog('close');
                                                                alert('Data Berhasil Tersimpan...!!!');
                                                                $("#no_spp_hide").attr("value", a);
                                                                lcstatus = 'edit';
                                                                section1();
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

                                //----------

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


                                //---------
                                lcquery = " UPDATE trhspp SET kd_skpd='" + kode + "', keperluan='" + e + "', bulan='" + d + "', no_spd='" + z + "', jns_spp='" + c + "',jns_beban='" + jenis + "', bank='" + g + "', id_perusahaan='" + f + "', no_rek='" + i + "', npwp='" + h + "', nm_skpd='" + j + "', tgl_spp='" + b + "', status='0', nilai='" + k + "', kd_sub_kegiatan='" + kegi + "', nm_sub_kegiatan='" + l + "', kd_program='" + m + "', nm_program='" + n + "', pimpinan='" + f1 + "', no_tagih='" + p + "', tgl_tagih='" + q + "', sts_tagih='" + o + "', no_spp='" + a + "',alamat ='" + alamat + "', kontrak='" + kontrak + "',lanjut='" + lanjut + "',tgl_mulai='" + tgl_mulai + "',tgl_akhir='" + tgl_akhir + "',nmrekan='" + nm_rekan + "' where no_spp='" + a_hide + "' AND kd_skpd='" + kode + "' ";

                                //          alert(lcquery);
                                //exit();
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
                                                //alert("aaaa");
                                                alert('Nomor SPP Sudah Terpakai...!!!,  Ganti Nomor SPP...!!!');
                                                exit();
                                            }

                                            if (status == '2') {

                                                $('#dgsppls').datagrid('selectAll');
                                                var rows = $('#dgsppls').datagrid('getSelections');

                                                for (var i = 0; i < rows.length; i++) {
                                                    cidx = rows[i].idx;
                                                    ckdgiat = rows[i].kdsubkegiatan;
                                                    // alert(ckdgiat);
                                                    ckdrek = rows[i].kdrek6;
                                                    cnmrek = rows[i].nmrek6;
                                                    cnilai = angka(rows[i].nilai1);
                                                    cgiat = ckdgiat.substr(0, 21);

                                                    no = i + 1;



                                                    if (i > 0) {
                                                        csql = csql + "," + "('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + spd + "','" + kd_bidang + "')";
                                                    } else {
                                                        csql = "values('" + a + "','" + ckdrek + "','" + cnmrek + "','" + cnilai + "','" + kode + "','" + ckdgiat + "','" + spd + "','" + kd_bidang + "')";
                                                    }
                                                }
                                                // $(document).ready(function(){
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
                                                    url: '<?php echo base_url(); ?>/index.php/spp/dsimpan_ag_edit_ls',
                                                    success: function(data) {
                                                        status = data.pesan;
                                                        if (status == '1') {
                                                            $("#loading").dialog('close');
                                                            alert('Data Berhasil Tersimpan...!!!');
                                                            $("#no_spp_hide").attr("value", a);
                                                            lcstatus = 'edit';
                                                            data_notagih();
                                                            exit();
                                                        } else {
                                                            $("#loading").dialog('close');
                                                            lcstatus = 'tambah';
                                                            alert('Detail Gagal Tersimpan...!!!');
                                                            exit();
                                                        }
                                                    }
                                                });
                                                // });            
                                            }

                                            if (status == '0') {
                                                alert('Gagal Simpan...!!!');
                                                exit();
                                            }

                                        }
                                    });
                                });

                                //-----------
                            }
                        }
                    });
                });

            }

        }


        function dsimpan(kegiatan, rekening, nkegiatan, nrekening, nilai, sis, kd) {
            var a = document.getElementById('no_spp').value;
            $jak = eval(sis);
            $son = eval(nilai);

            if ($son > $jak) {
                alert('nilai melebihi anggaran')
            } else {
                $(function() {
                    $.ajax({
                        type: 'POST',
                        data: ({
                            cno_spp: a,
                            cskpd: kode,
                            cgiat: kegiatan,
                            crek: rekening,
                            ngiat: nkegiatan,
                            nrek: nrekening,
                            nilai: nilai,
                            sis: sis,
                            kd: kd
                        }),
                        dataType: "json",
                        url: "<?php echo base_url(); ?>index.php/spp/dsimpan"
                    });
                });
            }
        }


        function detsimpan() {

            var a = document.getElementById('no_spp').value;
            var kode = $("#rek_skpd").combogrid("getValue");
            var cnmgiat = document.getElementById('nm_rek_kegi').value;
            var cnobukti1 = '';
            var a_hide = document.getElementById('no_spp_hide').value;

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


            $('#dgsppls').datagrid('selectAll');
            var rows = $('#dgsppls').datagrid('getSelections');

            for (var i = 0; i < rows.length; i++) {
                cidx = rows[i].idx;
                ckdgiat = rows[i].kdkegiatan;
                ckdrek = rows[i].kdrek6;
                cnmrek = rows[i].nmrek6;
                cnilai = angka(rows[i].nilai1);

                no = i + 1;
                $(document).ready(function() {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo base_url(); ?>index.php/spp/dsimpan",
                        data: ({
                            cno_spp: a,
                            cskpd: kode,
                            cgiat: ckdgiat,
                            crek: ckdrek,
                            ngiat: cnmgiat,
                            nrek: cnmrek,
                            nilai: cnilai,
                            kd: no,
                            no_bukti1: cnobukti1
                        }),
                        dataType: "json"
                    });
                });
            }
            $("#no_spp_hide").attr("Value", a);
            $('#dgsppls').edatagrid('unselectAll');
        }


        function hapus() {
            var spp = document.getElementById("no_spp").value;
            var nospp = spp.split("/").join("123456789");
            var giat = getSelections();
            var rek = getSelections1();
            if (rek != '') {
                var del = confirm('Anda yakin akan menghapus rekening ' + rek + ' kegiatan' + giat + ' ?');
                if (del == true) {
                    $(function() {
                        $('#dg1').edatagrid({
                            url: '<?php echo base_url(); ?>/index.php/spp/thapus/' + nospp + '/' + giat + '/' + rek,
                            idField: 'id',
                            toolbar: "#toolbar",
                            rownumbers: "true",
                            fitColumns: "true",
                            singleSelect: "true"
                        });
                    });

                }
            }
        }

        function hhapus() {
            var del = confirm('Anda yakin akan menghapus SPP ' + spp + '  ?');
            var spp = document.getElementById("no_spp").value;
            var nospp = spp.split("/").join("######");
            var urll = '<?php echo base_url(); ?>/index.php/spp/hapus_spp3';
            if (spp != '') {
                var del = confirm('Anda yakin akan menghapus SPP ' + spp + '  ?');
                if (del == true) {
                    $(document).ready(function() {
                        $.post(urll, ({
                            no: nospp
                        }), function(data) {
                            status = data;
                            if (status == 1) {
                                alert('Data Berhasil Di Hapus');
                            } else if (status == 2) {
                                alert('Data SPP No. ' + spp + 'Sudah di SPM kan');
                                exit();
                            } else {
                                alert('Data Gagl di Hapus');
                            }

                        });
                    });
                }
            }
        }


        function getSelections(idx) {
            var ids = [];
            var rows = $('#dg1').edatagrid('getSelections');
            for (var i = 0; i < rows.length; i++) {
                ids.push(rows[i].kdkegiatan);
            }
            return ids.join(':');
        }

        function getSelections1(idx) {
            var ids = [];
            var rows = $('#dg1').edatagrid('getSelections');
            for (var i = 0; i < rows.length; i++) {
                ids.push(rows[i].kdrek6);
            }
            return ids.join(':');
        }

        function kembali() {
            $('#kem').click();
        }


        function load_sum_spp() {
            var nospp = document.getElementById('no_spp').value;
            //var nospp =spp.split("/").join("123456789");       
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        spp: nospp
                    }),
                    url: "<?php echo base_url(); ?>index.php/spp/load_sum_spp",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#rektotal_ls").attr('value', number_format(n['rektotal'], 2, '.', ','));
                            $("#rektotal1_ls").attr('value', number_format(n['rektotal'], 2, '.', ','));
                        });
                    }
                });
            });
        }



        function tombol(st, csp2d_batal) {
            if (st == 1) {
                document.getElementById("p1").innerHTML = "Sudah dibuat SPM...!!!";
                $('#save').linkbutton('disable');
                $('#batal').linkbutton('disable');
            }else if(st!=1 && csp2d_batal!=1){
                document.getElementById("p1").innerHTML = "Belum dibuat SPM...!!!";
                $('#save').linkbutton('enable');
                $('#batal').linkbutton('enable');
            }else if(csp2d_batal== 1){
                document.getElementById("p1").innerHTML = "Status SPP Dibatalkan...!!!";
                $('#save').linkbutton('disable');
                $('#batal').linkbutton('enable');
                $('#del1').linkbutton('disable');
                
            }
        }




        function cetak_spp3() { //belum
            var urll = '<?php echo base_url(); ?>/index.php/spp/cetakspp3';
            if (spp != '') {
                var del = confirm('Anda yakin akan mencetak SPP ' + nomer + '  ?');
                if (del == true) {
                    $(document).ready(function() {
                        $.post(urll, ({
                            no: nomer
                        }), function(data) {
                            status = data;
                        });
                    });
                }
            }
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
            var ttdppk = $("#ttdppk").combogrid('getValue');
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

        function detail() {
            var lcno = document.getElementById('no_spp').value;
            if (lcno != '') {
                section3();
            } else {
                alert('Nomor SPP Tidak Boleh kosong')
                document.getElementById('no_spp').focus();
                exit();
            }
        }

        function validate_jenis_edit() {
            var beban = document.getElementById('jns_beban').value;
            var jenis = $("#cc").combobox('getValue');
            var tanggal_spp = $('#dd').datebox('getValue');
            var bulan = document.getElementById('kebutuhan_bulan').value;
            $('#cc').combobox({
                url: '<?php echo base_url(); ?>/index.php/spp/load_jenis_beban/' + beban,
            });
            // $('#sp').combogrid({url:'<?php echo base_url(); ?>/index.php/spp/spd1_ag/'+beban,
            $('#sp').combogrid({
                url: '<?php echo base_url(); ?>/index.php/spp/spd1_ag/' + beban + '/' + tanggal_spp + '/' + bulan,
            });
            if (beban == '6') {
                $("#npwp").attr('disabled', false);
                $("#tgl_mulai").datebox('enable');
                $("#tgl_akhir").datebox('enable');
                $("#rekanan").combogrid('enable');
                $("#dir").attr('disabled', false);
                $("#alamat").attr('disabled', false);
                $("#kontrak").attr('disabled', false);
                $("#bank1").combogrid('enable');
                $("#rekening").attr('disabled', false);
            } else {

                if ((beban == '4') && (jenis == '9')) {
                    $("#npwp").attr('disabled', false);
                    $("#tgl_mulai").datebox('disable');
                    $("#tgl_akhir").datebox('disable');
                    $("#rekanan").combogrid('enable');
                    $("#dir").attr('disabled', false);
                    $("#alamat").attr('disabled', false);
                    $("#kontrak").attr('disabled', true);
                    $("#bank1").combogrid('enable');
                    $("#rekening").attr('disabled', false);
                } else {
                    $("#npwp").attr('disabled', false);
                    $("#tgl_mulai").datebox('disable');
                    $("#tgl_akhir").datebox('disable');
                    $("#rekanan").combogrid('enable');
                    $("#dir").attr('disabled', true);
                    $("#alamat").attr('disabled', true);
                    $("#kontrak").attr('disabled', true);
                    $("#bank1").combogrid('enable');
                    $("#rekening").attr('disabled', false);
                }

            }
            $('#cc').combobox('setValue', jns_bbn);
        }

        function validate_jenis() {
            var tanggal_spp = $('#dd').datebox('getValue');
            if (tanggal_spp == '') {
                alert("Isi Tanggal SPP Terlebih Dahulu!");
                $("#jns_beban").attr("Value", '');
                exit();
            }
            var beban = document.getElementById('jns_beban').value;
            var bulan = document.getElementById('kebutuhan_bulan').value;
            var jenis = $("#cc").combobox('getValue');
            // alert(bulan);
            $('#cc').combobox({
                url: '<?php echo base_url(); ?>/index.php/spp/load_jenis_beban/' + beban,
            });
            $('#sp').combogrid({
                url: '<?php echo base_url(); ?>/index.php/spp/spd1_ag/' + beban + '/' + tanggal_spp + '/' + bulan,
            });
            if (beban == '6') {
                $("#npwp").attr('disabled', false);
                $("#tgl_mulai").datebox('enable');
                $("#tgl_akhir").datebox('enable');
                // $("#rekanan").combogrid('enable');
                $("#dir").attr('disabled', false);
                $("#alamat").attr('disabled', false);
                $("#kontrak").attr('disabled', false);
                $("#bank1").combogrid('enable');
                $("#rekening").attr('disabled', false);
            } else {
                if ((beban == '4') && (jenis == '9')) {
                    $("#npwp").attr('disabled', false);
                    $("#tgl_mulai").datebox('disable');
                    $("#tgl_akhir").datebox('disable');
                    // $("#rekanan").combogrid('enable');
                    $("#dir").attr('disabled', false);
                    $("#alamat").attr('disabled', false);
                    $("#kontrak").attr('disabled', true);
                    $("#bank1").combogrid('enable');
                    $("#rekening").attr('disabled', false);
                } else {
                    $("#npwp").attr('disabled', false);
                    $("#tgl_mulai").datebox('disable');
                    $("#tgl_akhir").datebox('disable');
                    // $("#rekanan").combogrid('enable');
                    $("#dir").attr('disabled', true);
                    $("#alamat").attr('disabled', true);
                    $("#kontrak").attr('disabled', true);
                    $("#bank1").combogrid('enable');
                    $("#rekening").attr('disabled', false);
                }
            }

            get_spp();
        }

        function validate_tombol() {
            var beban = document.getElementById('jns_beban').value;
            var jenis = $("#cc").combobox('getValue');
            if ((beban == '6') && (jenis == '3')) {
                $("#npwp").attr('disabled', false);
                $("#tgl_mulai").datebox('enable');
                $("#tgl_akhir").datebox('enable');
                // $("#rekanan").combogrid('enable');
                $("#dir").attr('disabled', false);
                $("#alamat").attr('disabled', false);
                $("#kontrak").attr('disabled', false);
                $("#bank1").combogrid('enable');
                $("#rekening").attr('disabled', false);
            } else if ((beban == '6') && (jenis == '2')) {
                $("#npwp").attr('disabled', false);
                $("#tgl_mulai").datebox('disable');
                $("#tgl_akhir").datebox('disable');
                // $("#rekanan").combogrid('enable');
                $("#dir").attr('disabled', false);
                $("#alamat").attr('disabled', false);
                $("#kontrak").attr('disabled', true);
                $("#bank1").combogrid('enable');
                $("#rekening").attr('disabled', false);
            } else if ((beban == '4') && (jenis == '9')) {
                $("#npwp").attr('disabled', false);
                $("#tgl_mulai").datebox('disable');
                $("#tgl_akhir").datebox('disable');
                // $("#rekanan").combogrid('enable');
                $("#dir").attr('disabled', false);
                $("#alamat").attr('disabled', false);
                $("#kontrak").attr('disabled', true);
                $("#bank1").combogrid('enable');
                $("#rekening").attr('disabled', false);
            } else {
                $("#npwp").attr('disabled', false);
                $("#tgl_mulai").datebox('disable');
                $("#tgl_akhir").datebox('disable');
                // $("#rekanan").combogrid('enable');
                $("#dir").attr('disabled', true);
                $("#alamat").attr('disabled', true);
                $("#kontrak").attr('disabled', true);
                $("#bank1").combogrid('enable');
                $("#rekening").attr('disabled', false);
            }
        }

        function runEffect() {
            var selectedEffect = 'explode';
            var options = {};
            $("#tagih").toggle(selectedEffect, options, 500);
            $("#notagih").combogrid("setValue", '');
            $("#tgltagih").attr("value", '');
            //$("#nmskpd").attr("value",'');
            $("#nil").attr("value", '');
            $("#ni").attr("value", '');
        };

        //copy
        function runEffect_taspen() {
            var selectedEffect = 'explode';
            var options = {};
            $("#gjtaspen").toggle(selectedEffect, options, 500);
            $("#tglgj").attr("value", '');
            $("#nilgj").attr("value", '');
            $("#nigj").attr("value", '');

            loadrek_taspen();
        };

        function loadrek_taspen() {
            $('#tglgj').combogrid({
                panelWidth: 620,
                url: '<?php echo base_url(); ?>/index.php/spp/load_taspen',
                idField: 'tgl_spp',
                textField: 'tgl_spp',
                mode: 'remote',
                fitColumns: true,
                columns: [
                    [{
                            field: 'tgl_spp',
                            title: 'Tanggal',
                            width: 75,
                            align: 'center'
                        },
                        {
                            field: 'kd_skpd',
                            title: 'SKPD',
                            width: 75,
                            align: 'center'
                        },
                        {
                            field: 'nila',
                            title: 'Total Gaji',
                            width: 130,
                            align: 'right'
                        },
                        {
                            field: 'ket',
                            title: 'KET',
                            width: 320,
                            align: 'left'
                        }
                    ]
                ]
            });
        }

        //copy

        function detail_trans_3() {
            $(function() {
                $('#dgsppls').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                    queryParams: ({
                        spp: no_spp
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
                        tkdkegiatan = rowData.kdsubkegiatan;
                        tkdrek6 = rowData.kdrek6;
                        tnmrek6 = rowData.nmrek6;
                        tnilai1 = rowData.nilai1;
                        tsumber = rowData.sumber;
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
                                field: 'kdsubkegiatan',
                                title: 'Sub Kegiatan',
                                width: 100,
                                align: 'left',
                                hidden: true
                            },
                            {
                                field: 'kdrek6',
                                title: 'Rekening',
                                width: 100,
                                align: 'left'
                            },
                            {
                                field: 'nmrek6',
                                title: 'Nama Rekening',
                                width: 270
                            },
                            {
                                field: 'nilai1',
                                title: 'Nilai',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'sumber',
                                title: 'Sumber',
                                width: 100,
                                align: 'right',
                                hidden: 'true'
                            },
                            {
                                field: 'nmsumber',
                                title: 'Sumber',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'hapus',
                                title: 'Hapus',
                                width: 50,
                                align: "center",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                                }
                            }
                        ]
                    ]
                });
            });
        }


        function detail_kosong() {

            var no_spp = '';
            $(function() {
                $('#dgsppls').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spp/select_data1',
                    queryParams: ({
                        spp: no_spp
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
                                field: 'kdkegiatan',
                                title: 'Kode',
                                width: 160,
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
                                width: 280
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
                                width: 100,
                                align: 'right',
                                hidden: 'true'
                            },
                            {
                                field: 'nmsumber',
                                title: 'Sumber',
                                width: 100,
                                align: 'right'
                            },
                            {
                                field: 'hapus',
                                title: 'Hapus',
                                width: 50,
                                align: "center",
                                formatter: function(value, rec) {
                                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                                }
                            }
                        ]
                    ]
                });
            });
        }


        function tambah() {

            var cek_kegi = $("#kg").combogrid('getValue');
            if (cek_kegi == '') {
                alert('Isi Kode Sub Kegiatan Terlebih Dahulu....!!!');
                exit();
            }
            $("#dialog-modal-rek").dialog('open');
            $("#rek_skpd").combogrid("disable");
            $("#rek_kegi").combogrid("disable");
            $("#nm_rek_kegi").attr("Value", '');
            $("#rek_reke").combogrid("setValue", '');
            $("#nm_rek_reke").attr("Value", '');
            $("#nm_sumber_dn").attr("Value", '');
            $("#sumber_dn").combogrid("setValue", '');

            // SPD
            $("#total_spd").attr("Value", 0);
            $("#nilai_spd_lalu").attr("Value", 0);
            $("#nilai_sisa_spd").attr("Value", 0);
            // Angkas
            $("#total_angkas").attr("Value", 0);
            $("#nilai_angkas_lalu").attr("Value", 0);
            $("#nilai_sisa_angkas").attr("Value", 0);
            // Anggaran
            $("#rek_nilai_ang").attr("Value", 0);
            $("#rek_nilai_spp").attr("Value", 0);
            $("#rek_nilai_sisa").attr("Value", 0);
            // Anggaran Sumber Dana
            $("#rek_nilai_ang_dana").attr("Value", 0);
            $("#rek_nilai_spp_dana").attr("Value", 0);
            $("#rek_nilai_sisa_dana").attr("Value", 0);

            var kegi_tmb = $("#kg").combogrid('getValue');
            // alert(kegi_tmb);
            var nm_kegi_tmb = document.getElementById('nm_kg').value;

            $("#rek_kegi").combogrid("setValue", kegi_tmb);
            $("#nm_rek_kegi").attr("Value", nm_kegi_tmb);
            //alert(cek_kegi);
            $("#total_spd").attr("Value", 0);
            $("#nilai_spd_lalu").attr("Value", 0);
            $("#nilai_sisa_spd").attr("Value", 0);
            $("#rek_nilai").attr("Value", 0);
            $("#rek_nilai_ang").attr("Value", 0);
            $("#rek_nilai_spp").attr("Value", 0);
            $("#rek_nilai_sisa").attr("Value", 0);

            var ang = document.getElementById('status_ang').value;
            var kd_angkas = document.getElementById('status_angkas').value;
            $("#rek_nilai_ang_dana").attr("Value", 0);
            $("#rek_nilai_spp_dana").attr("Value", 0);
            $("#rek_nilai_sisa_dana").attr("Value", 0);
        }


        function append_save() {

            $('#dgsppls').datagrid('selectAll');
            var rows = $('#dgsppls').datagrid('getSelections');
            jgrid = rows.length;

            var jumtotal = document.getElementById('rektotal_ls').value;
            jumtotal = angka(jumtotal);

            var vrek_skpd = $('#rek_skpd').combobox('getValue');
            var vrek_kegi = $('#rek_kegi').combobox('getValue');
            var vrek_reke = $('#rek_reke').combobox('getValue');
            var vsumber_dn = $('#sumber_dn').combobox('getValue');
            var vnmsumber_dn = document.getElementById('nm_sumber_dn').value;
            var cnil = document.getElementById('rek_nilai').value;
            var cnilai = cnil;
            var cnil_sisa_spd = angka(document.getElementById('nilai_sisa_spd').value);
            var cnil_sisa_angkas = angka(document.getElementById('nilai_sisa_angkas').value);
            var cnil_sisa = angka(document.getElementById('rek_nilai_sisa').value);
            //var cnil_sisa_semp   = angka(document.getElementById('rek_nilai_sisa_semp').value) ;
            //  var cnil_sisa_ubah   = angka(document.getElementById('rek_nilai_sisa_ubah').value) ;
            var cnil_sisa_dana = angka(document.getElementById('rek_nilai_sisa_dana').value);
            // var cnil_sisa_semp_dana   = angka(document.getElementById('rek_nilai_sisa_semp_dana').value) ;
            // var cnil_sisa_ubah_dana   = angka(document.getElementById('rek_nilai_sisa_ubah_dana').value) ;
            var cnil_input = angka(document.getElementById('rek_nilai').value);
            var status_ang = document.getElementById('status_ang').value;
            var beban_gj = document.getElementById('jns_beban').value;
            var tot_input = angka(document.getElementById('rektotal1_ls').value);
            akumulasi = cnil_input + tot_input;

            if ($('#q_minus').attr('checked')) {
                cnilai_ = -1 * cnil_input;
                cnilai = number_format(cnilai_, 2, '.', ',');

                cnil_ = -1 * cnil_input;
                cnil = number_format(cnil_, 2, '.', ',');
            }

            //LS Barang & Jasa    
            if (vsumber_dn == '') {
                alert('Pilih Sumber Dana Dahulu');
                exit();
            }

            if ((status_ang == '') && (beban_gj != '4')) {
                alert('Pilih Tanggal Dahulu');
                exit();
            }
            // SPD
            if ((cnil_input > cnil_sisa_spd) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!');
                exit();
            }

            if ((cnil_input > cnil_sisa_spd) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!');
                exit();
            }
            // 
            // Anggaran Kas
            if ((cnil_input > cnil_sisa_angkas) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Kas...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((cnil_input > cnil_sisa_angkas) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Kas...!!!, Cek Lagi...!!!');
                exit();
            }
            // 


            if (cnil_input == 0) {
                alert('Nilai Nol.....!!!, Cek Lagi...!!!');
                exit();
            }

            if ((status_ang == 'U1') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'U2') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P1') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan I...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P2') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P3') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan III...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P4') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan IV...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P5') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan V...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'M') && (cnil_input > cnil_sisa) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Anggaran Penetapan...!!!, Cek Lagi...!!!');
                exit();
            }


            // Gaji
            if ((status_ang == 'U1') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'U2') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Perubahan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P1') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan I...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P2') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P3') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan III...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P4') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan IV...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P5') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan V...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'M') && (cnil_input > cnil_sisa) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Anggaran Penetapan...!!!, Cek Lagi...!!!');
                exit();
            }
            // End

            //sumber dana
            if ((status_ang == 'U1') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'U2') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Perubahan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P1') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan I...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P2') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P3') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan III...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P4') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan IV...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P5') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan V...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'M') && (cnil_input > cnil_sisa_dana) && (beban_gj != '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penetapan...!!!, Cek Lagi...!!!');
                exit();
            }

            // Sumber Dana Gaji
            if ((status_ang == 'U1') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'U2') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Perubahan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P1') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan I...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P2') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan II...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P3') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan III...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P4') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan IV...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'P5') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan V...!!!, Cek Lagi...!!!');
                exit();
            }
            if ((status_ang == 'M') && (cnil_input > cnil_sisa_dana) && (beban_gj == '4')) {
                alert('Nilai Melebihi Sisa Sumber Dana Penetapan...!!!, Cek Lagi...!!!');
                exit();
            }

            //gaji
            // if ((cnil_input > cnil_sisa_spd) && (beban_gj == '4')) {
            //     alert('Nilai Melebihi Sisa SPD...!!!, Cek Lagi...!!!');
            //     exit();
            // }

            // if ((status_ang=='Perubahan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Anggaran Perubahan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_semp)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Anggaran Penyempurnaan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }

            // if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_ubah)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Anggaran Rencana Perubahan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Anggaran Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Anggaran Penyusunan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }


            // //sumber dana
            // if ((status_ang=='Perubahan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Sumber Dana Perubahan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyempurnaan')&&(cnil_input > cnil_sisa_semp_dana)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Sumber Dana Penyempurnaan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }

            // if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_ubah_dana)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Sumber Dana Rencana Perubahan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_semp_dana)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Sumber Dana Rencana Penyempurnaan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }
            // if ( (status_ang=='Penyusunan')&&(cnil_input > cnil_sisa_dana)&& (beban_gj == '4')){
            //      alert('Nilai Melebihi Sisa Sumber Dana Penyusunan...!!!, Cek Lagi...!!!') ;
            //      exit();
            // }

            var vnm_rek_reke = document.getElementById('nm_rek_reke').value;

            if (edit == 'F') {
                pidx = pidx + 1;
            }

            if (edit == 'T') {
                pidx = jgrid;
                pidx = pidx + 1;
            }

            $('#dgsppls').edatagrid('appendRow', {
                kdkegiatan: vrek_kegi,
                kdrek6: vrek_reke,
                nmrek6: vnm_rek_reke,
                nilai1: cnilai,
                sumber: vsumber_dn,
                nmsumber: vnmsumber_dn,
                idx: pidx
            });

            $("#dialog-modal-rek").dialog('close');

            jumtotal = jumtotal + angka(cnil);
            $("#rektotal_ls").attr('value', number_format(jumtotal, 2, '.', ','));
            $("#rektotal1_ls").attr('value', number_format(jumtotal, 2, '.', ','));
            $("#dgsppls").datagrid("unselectAll");

        }


        function hapus_detail() {

            var a = document.getElementById('no_spp').value;
            var rows = $('#dgsppls').edatagrid('getSelected');
            var ctotalspp = document.getElementById('rektotal_ls').value;

            bkdrek = rows.kdrek6;
            bkdkegiatan = rows.kdkegiatan;
            bnilai = rows.nilai1;
            bbukti = '';
            // alert(ctotalspp);
            ctotalspp = angka(ctotalspp) - angka(bnilai);

            var idx = $('#dgsppls').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, Rekening : ' + bkdrek + '  Nilai :  ' + bnilai + ' ?');

            if (tny == true) {

                $('#dgsppls').datagrid('deleteRow', idx);
                $('#dgsppls').datagrid('unselectAll');
                $("#rektotal_ls").attr("Value", number_format(ctotalspp, 2, '.', ','));
                $("#rektotal1_ls").attr("Value", number_format(ctotalspp, 2, '.', ','));

                var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_spp';
                $(document).ready(function() {
                    $.post(urll, ({
                        cnospp: a,
                        ckdgiat: bkdkegiatan,
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

        function huruf_besar() {
            var n_awal = document.getElementById('no_spp').value;
            $("no_spp").attr("Value", n_awal.toUpperCase());
        }


        function form_batal() {
            $("#no_spp_batal").attr('disabled', true);
            document.getElementById("no_spp_batal").value = document.getElementById("no_spp").value;
            //   $("#no_spp_batal").attr("value",$('#nospp').combogrid('getValue'));     
            $("#dialog-batal").dialog('open');
        }

        function keluar_batal() {
            $("#dialog-batal").dialog('close');
        }

        function batal() {
            var no_spp = document.getElementById("no_spp_batal").value;
            var ket = document.getElementById("ket_batal").value;
            var beban = document.getElementById('jns_beban').value;

            if (no_spp != '') {
                var del = confirm('Anda yakin akan Membatalkan SPP: ' + no_spp + '  ?');
                if (del == true) {
                    /*ini untuk delete
                    $(document).ready(function(){
                            $.post(urll,({no:sp2d,spm:no_spm}),function(data){
                            status = data;
                            spm_combo(); */
                    if (ket == '') {
                        alert('Keterangan harus diisi');
                        exit();
                    }


                    $(document).ready(function() {
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>/index.php/Spp/batal_spp',
                            data: ({
                                nospp: no_spp,
                                ket: ket,
                                jns_spp: beban
                            }),
                            dataType: "json",
                            success: function(data) {
                                status = data;
                                if (status == '1') {
                                    keluar_batal();
                                    alert('SPP - SPM Berhasil Dibatalkan');
                                } else {
                                    keluar_batal();
                                    alert('SPP - SPM Gagal Dibatalkan');
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
    </STYLE>

</head>

<body>
    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1" onclick="javascript:$('#spp').edatagrid('reload')">List SPP</a></h3>

            <div style="height:600px;">
                <p align="right">
                    <button class="button" onclick="javascript:section2();kosong();"><i class="fa fa-tambah"></i> Tambah</button>
                    <!---     <button type="primary" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>    -->
                    <button class="button-cerah" onclick="javascript:cari();"><i class="fa fa-cari"></i>Cari</button>
                    <input type="text" value="" id="txtcari" />
                <table id="spp" title="List SPP" style="width:870px;height:650px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2">Input SPP</a></h3>

            <div style="height:620px;">
                <p id="p1" style="font-size: x-large;color: red;"></p>

                <fieldset style="width:850px;height:950px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">
                    <table border='1' style="font-size:11px">
                        <tr style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;">
                            <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;" colspan="5"><b>P E N A G I H A N </b><input type="checkbox" id="status" onclick="javascript:runEffect();" />
                                <div id="tagih">
                                    <table>
                                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                            <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No.Penagihan</td>
                                            <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" id="notagih" /></td>

                                            <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tgl Penagihan</td>
                                            <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" id="tgltagih" style="width: 140px;" disabled /></td>
                                            <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nilai </td>
                                            <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><input type="text" id="nil" style="width: 140px;" disabled />
                                                <input type="hidden" id="ni" style="width: 140px;" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                            </td>
                        </tr>

                        <tr>
                            <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                            <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true" ; /></td>
                            <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
                            <td style="border-bottom: double 1px red;border-top: double 1px red;" colspan="2"><i>Tidak Perlu diisi atau di Edit</i></td>

                        </tr>
                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                            <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                            <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
                        </tr>

                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPP</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="text" name="no_spp" id="no_spp" style="width:300px" onkeyup="this.value=this.value.toUpperCase()" disabled /><input type="hidden" name="no_spp_hide" id="no_spp_hide" style="width:140px" /></td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="dd" name="dd" type="text" /><input type="hidden" id="dd_spp" name="dd_spp" /></td>
                        </tr>
                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
                            <td width="53%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                &nbsp;<input id="dn" name="dn" readonly="true" style="width:130px; border: 0;" /> <input type="hidden" id="bidangg" name="bidangg" /></td>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Bulan</td>
                            <td width="31%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><select name="kebutuhan_bulan" id="kebutuhan_bulan" disabled>
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
                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
                            <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><textarea name="nmskpd" id="nmskpd" cols="40" rows="4" style="border: 0;" readonly="true"></textarea></td>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Keperluan</td>
                            <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><textarea name="ketentuan" id="ketentuan" cols="35" rows="5"></textarea></td>
                        </tr>

                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Beban</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><select name="jns_beban" id="jns_beban" onchange="javascript:validate_jenis();" style="height: 27px; width:190px;">
                                    <option value="">...Pilih Beban... </option>
                                    <option value="4">LS Gaji</option>
                                    <option value="6">LS Barang Jasa</option>
                                    <option value="5">LS Pihak Ketiga Lainnya</option>
                                </select>
                            </td>
                            <td colspan="2" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                        </tr>

                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Jenis</td>
                            <td width='92%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input id="cc" name="dept" style="width: 190px;" value=" Pilih Jenis Beban"></td>
                            <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">BANK</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="bank1" id="bank1" />
                                &nbsp;&nbsp;<input type="input" readonly="true" style="border:hidden" id="nama_bank" name="nama_bank" style="width:150" /><input type="input" hidden readonly="true" style="border:hidden" id="tahunsekarang" name="tahunsekarang" style="width:150" /></td>
                        </tr>

                        <tr>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">No SPD</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input id="sp" name="sp" style="width:190px" /></td>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekanan/Bendahara</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                                <input id="rekanan" name="rekanan" style="width:100px" />
                                <input id="nama_rekanan" name="nama_rekanan" style="width:190px" />
                                <input id="dir" name="dir" style="width:190px" />
                            </td>
                        </tr>
                        <tr hidden>
                            <input id="tglspd" name="tglspad" type="text" disabled />
                        </tr>

                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Sub Kegiatan</td>
                            <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input id="kg" name="kg" style="width:190px" />
                                &nbsp;<input type="hidden" id="kp" name="kp" style="width:160px" />
                                &nbsp;&nbsp;<input id="nm_kg" name="nm_kg" style="width:500px;border: 0;" />
                                <input type="hidden" id="nm_kp" name="nm_kp" />
                            </td>
                        </tr>

                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">NPWP</td>
                            <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<input type="hidden" name="npwp_combo" id="npwp_combo" style="width:20px" />&nbsp;<input type="text" name="npwp" id="npwp" value="" style="width:190px" /></td>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
                            <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="hidden" name="rekening_combo" id="rekening_combo" style="width:20px" />&nbsp;<input type="text" name="rekening" id="rekening" value="" style="width:190px" /></td>
                        </tr>


                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Alamat Perusahaan</td>
                            <td width='92%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;<textarea name="alamat" id="alamat" cols="35" rows="2"></textarea></td>
                            <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Tanggal Mulai/Akhir</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><input id="tgl_mulai" name="tgl_mulai" style="width:190px" />
                                &nbsp;
                                <input id="tgl_akhir" name="tgl_akhir" style="width:190px" />
                        </tr>

                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Lanjut</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"> &nbsp; <select name="lanjut" id="lanjut" style="height: 27px; width: 190px;">
                                    <option value="">...Pilih ... </option>
                                    <option value="1">IYA</option>
                                    <option value="2">TIDAK</option>
                            </td>
                            </td>

                        </tr>
                        <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">
                            <td width="8%" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nomor Kontrak</td>
                            <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;<input type="text" name="kontrak" id="kontrak" />
                            </td>

                        </tr>
                        <!--
     <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-Si:hidden;">&nbsp;</td>
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
     </tr>  -->

                        <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
                            <td colspan="4" align='center' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;">
                                <!--<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();">Baru</a>-->
                                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:hsimpan();">Simpan</a>
                                <!-- <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hhapus();javascript:section1();">Hapus</a>-->
                                <a id="batal" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:form_batal();">Batal SPM - SPP</a>
                                <!--<a id="det" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:detail();">Detail</a>-->
                                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section1();">Kembali</a>
                                <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">cetak</a>
                            </td>
                        </tr>
                    </table>


                    <!------------------------------------------------------------------------------------------------------------------>

                    <table id="dgsppls" title="Input Detail SPP" style="width:850%;height:250%;">
                    </table>

                    <div id="toolbar" align="left">
                        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah Rekening</a>
                    </div>

                    <table border='0' style="width:100%;height:5%;">
                        <td width='39%'></td>
                        <td width='15%'><input class="right" type="hidden" name="rektotal1_ls" id="rektotal1_ls" style="width:140px" align="right" readonly="true"></td>
                        <td width='10%'><B>Total</B></td>
                        <td width='31%'><input class="right" type="text" name="rektotal_ls" id="rektotal_ls" style="background-color: #FFA07A; width:140px" align="right" readonly="true"></td>
                    </table>
                </fieldset>
                <!------------------------------------------------------------------------------------------------------------------>
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


    <div id="dialog-modal-rek" title="Input Rekening">
        <p class="validateTips"></p>
        <fieldset>
            <table align="center" style="width:100%;" border="0">

                <tr>
                    <td width='17%'>SKPD</td>
                    <td width='3%'>:</td>
                    <td colspan="6" width='80%'><input id="rek_skpd" name="rek_skpd" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="rek_nmskpd" style="border:0;width: 350px;" readonly="true" /></td>
                </tr>

                <tr>
                    <td>SUB KEGIATAN</td>
                    <td>:</td>
                    <td colspan="6"><input id="rek_kegi" name="rek_kegi" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_kegi" style="border:0;width: 400px;" readonly="true" /></td>
                </tr>

                <tr>
                    <td>REKENING</td>
                    <td>:</td>
                    <td colspan="6"><input id="rek_reke" name="rek_reke" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_reke" style="border:0;width: 400px;" readonly="true" /></td>
                </tr>

                <tr>
                    <td>SUMBER DANA</td>
                    <td>:</td>
                    <td colspan="6"><input id="sumber_dn" name="sumber_dn" style="width: 200px;" /><input id="nm_sumber_dn" name="nm_sumber_dn" style="width: 350px;" disabled /></td>
                </tr>

                <tr>
                    <td bgcolor="#99FF99">TOTAL SPD</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="total_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#99FF99">SPD TERPAKAI</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="nilai_spd_lalu" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#99FF99">SISA</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="nilai_sisa_spd" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
                </tr>

                <tr>
                    <td bgcolor="#99FF99">ANGKAS</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="total_angkas" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#99FF99">ANGKAS TERPAKAI</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="nilai_angkas_lalu" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#99FF99">SISA</td>
                    <td bgcolor="#99FF99">:</td>
                    <td bgcolor="#99FF99"><input type="text" id="nilai_sisa_angkas" style="background-color:#99FF99; width: 196px; text-align: right; " readonly="true" /></td>
                </tr>

                <tr>
                    <td>ANGGARAN</td>
                    <td>:</td>
                </tr>

                <tr>
                    <td bgcolor="#87CEFA">ANGGARAN</td>
                    <td bgcolor="#87CEFA">:</td>
                    <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#87CEFA">SPP TERPAKAI</td>
                    <td bgcolor="#87CEFA">:</td>
                    <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#87CEFA">SISA</td>
                    <td bgcolor="#87CEFA">:</td>
                    <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                </tr>

                <!-- <tr>
                <td bgcolor="#87CEFA">PENYEMPURNAAN</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA">SPP TERPAKAI</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_semp" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr>
            <tr>
                <td bgcolor="#87CEFA">PERUBAHAN</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_ang_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#87CEFA">SPP TERPAKAI</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_spp_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#87CEFA">SISA</td>
                <td bgcolor="#87CEFA">:</td>
                <td bgcolor="#87CEFA"><input type="text" id="rek_nilai_sisa_ubah" style="background-color: #87CEFA; width: 196px; text-align: right; " readonly="true" /></td>              
            </tr> -->
                <tr>
                    <td>SUMBER DANA</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td bgcolor="#FFA07A">SUMBER DANA</td>
                    <td bgcolor="#FFA07A">:</td>
                    <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#FFA07A">SPP TERPAKAI</td>
                    <td bgcolor="#FFA07A">:</td>
                    <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                    <td bgcolor="#FFA07A">SISA</td>
                    <td bgcolor="#FFA07A">:</td>
                    <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                </tr>

                <!-- <tr>
                <td bgcolor="#FFA07A">PENYEMPURNAAN</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A">SPP TERPAKAI</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_semp_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr>
            <tr>
                <td bgcolor="#FFA07A">PERUBAHAN</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_ang_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td> 
                <td bgcolor="#FFA07A">SPP TERPAKAI</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_spp_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>
                <td bgcolor="#FFA07A">SISA</td>
                <td bgcolor="#FFA07A">:</td>
                <td bgcolor="#FFA07A"><input type="text" id="rek_nilai_sisa_ubah_dana" style="background-color: #FFA07A; width: 196px; text-align: right; " readonly="true" /></td>             
            </tr> -->
                <tr>
                    <td>NILAI</td>
                    <td>:</td>
                    <td><input type="text" id="rek_nilai" style="width: 196px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                    <td>Minus <input id="q_minus" name="q_minus" type="checkbox" value="1" /></td>
                <tr>
                    <td>STATUS ANGGARAN</td>
                    <td>:</td>
                    <td>
                        <input type="text" hidden id="status_ang" style="width: 196px; border:0; text-align: left;" />
                        <input type="text" id="nm_ang" style="width: 196px; border:0; text-align: left;" readonly="true" />
                    </td>
                    <td>STATUS ANGKAS</td>
                    <td>:</td>
                    <td><input type="text" id="status_angkas" style="width: 196px; border:0; text-align: left;" readonly="true" /></td>

                </tr>


                <tr>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="6" align="center">
                        <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_rek();">Keluar</a>
                    </td>
                </tr>

            </table>
        </fieldset>

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
                    <td width="110px">Bendahara:</td>
                    <td><input id="ttd1" name="ttd1" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd1" name="nmttd1" style="width: 170px;border:0" /></td>
                </tr>
                <tr>
                    <td width="110px">PPTK:</td>
                    <td><input id="ttd2" name="ttd2" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd2" name="nmttd2" style="width: 170px;border:0" /></td>
                </tr>
                <tr>
                    <td width="110px">PPK:</td>
                    <td><input id="ttdppk" name="ttdppk" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmppk" name="nmppk" style="width: 170px;border:0" /></td>
                </tr>
                <tr>
                    <td width="110px">PA/KPA:</td>
                    <td><input id="ttd3" name="ttd3" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd3" name="nmttd3" style="width: 170px;border:0" /></td>
                </tr>
                <tr hidden>
                    <td width="110px">PPKD:</td>
                    <td><input id="ttd4" name="ttd4" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd4" name="nmttd4" style="width: 170px;border:0" /></td>
                </tr>
                <tr hidden>
                    <td width="110px">SPASI:</td>
                    <td><input type="number" id="spasi" style="width: 100px;" value="1" /></td>
                </tr>
            </table>
        </fieldset>
        <div>
        </div>

        <table border="0">
            <tr>
                <td>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i>SPP</font>
                    </button>
                </td>
                <td hidden>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp1_ls/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i> Pengantar</font>
                    </button>
                </td>
                <td hidden>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp2/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i> Ringkasan</font>
                    </button>
                </td>
                <td>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp3/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i> Rincian</font>
                    </button>
                </td>
                <td>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>index.php/spp/cetakspp4/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i> Pernyataan</font>
                    </button>
                </td>
                <td hidden>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp5/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i> Permintaan</font>
                    </button>
                </td>
                <td hidden>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>index.php/spp/cetakspp6/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i> SPTJM</font>
                    </button>
                </td>
                <td hidden>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetak_kelengkapan_spp/1');return false;">
                        <font color="white"><i class="fa fa-file-pdf-o"></i>Kelengkapan SPP</font>
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77/0');return false;">
                        <font color="white"><i class="fa fa-television"></i> SPP</font>
                    </button>
                </td>
                <td hidden>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp1_ls/0');return false;">
                        <font color="white"><i class="fa fa-television"></i> Pengantar</font>
                    </button>
                </td>
                <td hidden>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp2/0');return false;">
                        <font color="white"><i class="fa fa-television"></i> Ringkasan</font>
                    </button>
                </td>
                <td>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp3/0');return false;">
                        <font color="white"><i class="fa fa-television"></i> Rincian</font>
                    </button>
                </td>
                <td>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>index.php/spp/cetakspp4/0');return false;">
                        <font color="white"><i class="fa fa-television"></i> Pernyataan</font>
                    </button>
                </td>
                <td hidden>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp5/0');return false;">
                        <font color="white"><i class="fa fa-television"></i> Permintaan</font>
                    </button>
                </td>
                <td hidden>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp_2('<?php echo site_url(); ?>index.php/spp/cetakspp6/0');return false;">
                        <font color="white"><i class="fa fa-television"></i> SPTJM</font>
                    </button>
                </td>
                <td hidden>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetak_kelengkapan_spp/0');return false;">
                        <font color="white"><i class="fa fa-television"></i>Kelengkapan SPP</font>
                    </button>
                </td>
            </tr>
        </table>

        <div hidden>
            <p>Permendagri 77</p>
            <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77/1');return false;">
                <font color="white"><i class="fa fa-file-pdf-o"></i>SPP</font>
            </button>

            <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77/1">
                <font color="white"><i class="fa fa-television"></i> Rincian</font>
            </button>
            <br />
            <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77/0');return false;">
                <font color="white"><i class="fa fa-television"></i> SPP</font>
            </button>

            <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cetak_spp('<?php echo site_url(); ?>index.php/spp/cetakspp77_rincian/0">
                <font color="white"><i class="fa fa-television"></i> Rincian</font>
            </button>
        </div>
        <br />
        &nbsp;&nbsp;&nbsp;<button type="edit" plain="true" onclick="javascript:keluar()">
            <font color="black"><i class="fa fa-arrow-left"></i> Kembali</font>
        </button>
    </div>
    <div id="dialog-batal" title="KETERANGAN PEMBATALAN SPM">
        <p class="validateTips">KETERANGAN PEMBATALAN SPP</p>
        <fieldset>
            <table>
                <tr>
                    <td width="110px">NO SPP:</td>
                    <td><input id="no_spp_batal" name="no_spp_batal" style="width: 170px;" readonly="true" /></td>
                </tr>
                <tr>
                    <td width="110px">KETERANGAN PEMBATALAN SPP:</td>
                    <td><textarea name="ket_batal" id="ket_batal" cols="70" rows="2"></textarea></td>
                </tr>
            </table>
        </fieldset>
        <a id="del1" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:batal();javascript:section1();">BATAL</a>
        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_batal();">Keluar</a>
    </div>
</body>

</html>