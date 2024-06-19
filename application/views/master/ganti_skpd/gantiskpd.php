<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
<script type="text/javascript">
    $(document).ready(function() {
        $("#pem1").hide();
    });

    $(function() {
        $('#jenis').on('change', function() {
            var jenis = this.value;
            if (jenis == '') {
                alert('Pilih Jenis Terlebih dahulu');
                return;
            }
            $('#pcskpd').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                queryParams: ({
                    jenis: jenis
                }),
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/jkn/PenerimaanJKNController/skpd',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 200
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 700
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    urusan = rowData.kd_skpd;
                    $("#nm_skpd").attr("value", rowData.nm_skpd);
                }
            });
        });

    });


    function simpan() {
        var skpd = $("#pcskpd").combogrid("getValue");
        var nmskpd = document.getElementById('nm_skpd').value;
        var user = document.getElementById('username').value;
        var nama = document.getElementById('username').value;
        // alert(nmskpd);
        $(document).ready(function() {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/simpan_set_skpd',
                data: ({
                    skpd: skpd,
                    user: user,
                    nama: nama
                }),
                dataType: "json",
                success: function(data) {
                    status = data;
                    if (skpd == '') {
                        swal("Error", "Pilih SKPD dulu ya", "error");
                        return;
                    }
                    if (nmskpd == '') {
                        swal("Error", "Nama SKPD Masih kosong ya", "error");
                        return;
                    }
                    if (status == '0') {
                        swal("Error", "Gagal Simpan", "error");
                        return;
                    } else {
                        swal("Berhasil", " SKPD berhasil diganti!", "success");
                        myFunction();
                    }
                }
            });
        });



    }

    function myFunction() {
        window.location.href = "<?php echo base_url(); ?>master/set_skpd";
    }
</script>
<div id="content">

    <legend>GANTI SKPD</legend>
    <table width="100%" align="center" border="0">
        <tr>
            <td width="10%">Username</td>
            <td width="90%"><input type="text" id="username" disabled class="input" value="<?php echo $this->session->userdata('pcNama'); ?>"></td>
        </tr>
        <tr>
            <td width="10%">SKPD Aktif</td>
            <td width="90%">
                <input type="text" class="input" disabled style="display: inline-block;" value="<?php echo $this->session->userdata('kdskpd'); ?>">
                <input type="text" class="input" disabled style="display: inline-block; width: 300px" value="<?php $kd = $this->session->userdata('kdskpd');
                                                                                                                echo $this->db->query("SELECT nm_skpd from bok_ms_skpd where kd_skpd='$kd'
                                                                                                                UNION ALL
                                                                                                                SELECT nm_skpd from ms_skpd_jkn where kd_skpd='$kd'")->row()->nm_skpd; ?>">
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>

            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Jenis</td>

            <td><select name="jenis" id="jenis" style="width: 179px;">
                    <option value=''>--Pilih--</option>
                    <option value="skpd">SKPD</option>
                    <option value="jknbok">JKN/BOK</option>
                </select></td>
        </tr>
        <tr>
            <td width="10%">Ganti SKPD</td>
            <td width="90%"><input type="text" style="width: 179px;" id="pcskpd">
                <input type="text" class="input" disabled style="display: inline-block;width: 300px" id="nm_skpd">
                <font color="red"> // PASTIKAN KODE SKPD DAN NAMA SKPD MUNCUL </font>
            </td>
        </tr>
        <tr>
            <td width="10%"></td>
            <td width="90%"><button class="button" onclick="javascript:simpan();"> Simpan</button></td>
        </tr>
    </table>

</div>