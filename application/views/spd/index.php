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
    var kode = '';
    var giat = '';
    var nomor = '';
    var judul = '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var ctk = '';
    var text = '';



    $(document).ready(function() {

        get_skpd();
    });

    $(function() {

        // $("#div_bulan").hide();
        $("#div_periode").hide();

        $('#tgl1').datebox({
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

        $('#tgl2').datebox({
            required: true,
            formatter: function(date) {
                var y = date.getFullYear();
                var m = date.getMonth() + 1;
                var d = date.getDate();
                return y + '-' + m + '-' + d;
            }
        });
    });


    function get_skpd() {

        $.ajax({
            url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
            type: "POST",
            dataType: "json",
            success: function(data) {
                $("#skpd").attr("value", data.kd_skpd);
                $("#nmskpd").attr("value", data.nm_skpd);
                kode = data.kd_skpd;
            }
        });

    }

    function opt(val) {
        ctk = val;
        if (ctk == '1') {
            $("#div_periode").show();
        } else {
            exit();
        }
    }


    $(function() {
        $('#ttd1').combogrid({
            panelWidth: 600,
            idField: 'nip',
            textField: 'nip',
            mode: 'remote',
            url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',
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

    function cetak(jenis) {
        let skpd = document.getElementById('skpd').value;
        let tgl1 = $('#tgl1').datebox('getValue');
        let tgl2 = $('#tgl2').datebox('getValue');
        let tgl_ttd = $('#tgl_ttd').datebox('getValue');
        let nip_ttd = $('#ttd1').combogrid('getValue');

        if (skpd == '') {
            alert("SKPD tidak boleh kosong");
            return;
        } else if (tgl1 == '') {
            alert("Periode pertama tidak boleh kosong");
            return;
        } else if (tgl2 == '') {
            alert("Periode kedua tidak boleh kosong");
            return;
        } else if (tgl_ttd == '') {
            alert("Tanggal tanda tangan tidak boleh kosong");
            return;
        } else if (nip_ttd == '') {
            alert("NIP tidak boleh kosong");
            return;
        }
        let url = "<?php echo site_url(); ?>spdcontroller/cetakregister_spd/" + jenis + "/" + skpd + "/" + tgl1 + "/" + tgl2 + "/" + tgl_ttd + "/" + nip_ttd;
        window.open(url);
    }
</script>


<div id="content" align="center" style="background: white">
    <h3 align="center"><b>Cetakan SPD</b></h3>
    <table align="center" style="width:100%;" border="0">
        <tr>
            <td><input type="radio" name="cetak" value="1" onclick="opt(this.value)" />Periode &ensp;
            </td>
            <td>&ensp;</td>
            <td>&nbsp</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp</td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="div_skpd">
                    <table style="width:100%;" border="0">
                        <td width="20%">SKPD</td>
                        <td width="1%">:</td>
                        <td width="79%"><input id="skpd" name="skpd" style="width: 200px;" />&ensp;
                            <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="div_periode">
                    <table style="width:100%;" border="0">
                        <td width="20%">PERIODE</td>
                        <td width="1%">:</td>
                        <td width="79%"><input type="text" id="tgl1" style="width: 200px;" /> s.d. <input type="text" id="tgl2" style="width: 200px;" />
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="div_bend">
                    <table style="width:100%;" border="0">
                        <td width="20%">TANGGAL TTD</td>
                        <td width="1%">:</td>
                        <td><input type="text" id="tgl_ttd" style="width: 200px;" />
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div id="div_bend">
                    <table style="width:100%;" border="0">
                        <tr>
                            <td width="20%">Penanda tangan</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd1" style="width: 200px;" />
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <td></td>
        <td>
            <button type="primary" onclick="javascript:cetak(0);return false"><i class="fa fa-television"></i> Layar</button>
            <button type="pdf" onclick="javascript:cetak(1);return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
        </td>
    </table>
</div>