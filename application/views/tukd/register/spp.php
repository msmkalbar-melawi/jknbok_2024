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

	<script type="text/javascript">
		$(function() {
			$('#ttd1').combogrid({
				panelWidth: 600,
				idField: 'nip',
				textField: 'nip',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/pa',
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

			$('#tgl1').datebox({
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






		$(function() {
			$('#ttd2').combogrid({
				panelWidth: 600,
				idField: 'nip',
				textField: 'nip',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/bk',
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



		$(document).ready(function() {
			$("#accordion").accordion();
			get_skpd();
			$("#dialog-modal").dialog({});
			$('#tgl_ttd').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

		});

		function cetakall() {
			var url = "<?php echo site_url(); ?>/tukd/cetak_register_spp";
			window.open(url, '_blank');
			window.focus();
		}

		function cetak(ctk) {
			var ckdskpd = document.getElementById('skpd').value;
			var ttd = $('#ttd1').combogrid('getValue');
			var ttd = ttd.split(" ").join("123456789");
			var ttd2 = $('#ttd2').combogrid('getValue');
			var ttd2 = ttd2.split(" ").join("123456789");
			var ctglttd = $('#tgl_ttd').datebox('getValue');

			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var ctgl1 = $('#tgl1').datebox('getValue');
			var ctgl2 = $('#tgl2').datebox('getValue');

			if (ttd == '') {
				alert('pilih penanda tangan dulu');
				return
			}
			if (ttd2 == '') {
				alert('pilih penanda tangan dulu');
				return
			}
			if (ctglttd == '') {
				alert('pilih tanggal dulu');
				return
			}

			var url = "<?php echo site_url(); ?>tukd/cetak_register_spp";
			window.open(url + '/' + ckdskpd + '/' + ttd + '/' + ttd2 + '/' + ctglttd + '/' + ctk + "/" + atas + "/" + bawah + "/" + kiri + "/" + kanan + '?tgl1=' + ctgl1 + '&tgl2=' + ctgl2, '_blank');
			window.focus();
		}

		function cetak2(ctk) {
			var ckdskpd = document.getElementById('skpd').value;
			var ttd = $('#ttd1').combogrid('getValue');
			var ttd = ttd.split(" ").join("123456789");
			var ttd2 = $('#ttd2').combogrid('getValue');
			var ttd2 = ttd2.split(" ").join("123456789");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var ctgl1 = $('#tgl1').datebox('getValue');
			var ctgl2 = $('#tgl2').datebox('getValue');


			if (ttd == '') {
				alert('pilih penanda tangan dulu');
				return
			}
			if (ttd2 == '') {
				alert('pilih penanda tangan dulu');
				return
			}
			if (ctglttd == '') {
				alert('pilih tanggal dulu');
				return
			}

			var url = "<?php echo site_url(); ?>tukd/cetak_register_spm";
			window.open(url + '/' + ckdskpd + '/' + ttd + '/' + ttd2 + '/' + ctglttd + '/' + ctk + "/" + atas + "/" + bawah + "/" + kiri + "/" + kanan + '?tgl1=' + ctgl1 + '&tgl2=' + ctgl2, '_blank');
			window.focus();
		}

		function cetak3(ctk) {
			var ckdskpd = document.getElementById('skpd').value;
			var ttd = $('#ttd1').combogrid('getValue');
			var ttd = ttd.split(" ").join("123456789");
			var ttd2 = $('#ttd2').combogrid('getValue');
			var ttd2 = ttd2.split(" ").join("123456789");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var ctgl1 = $('#tgl1').datebox('getValue');
			var ctgl2 = $('#tgl2').datebox('getValue');


			if (ttd == '') {
				alert('pilih penanda tangan dulu');
				return
			}
			if (ttd2 == '') {
				alert('pilih penanda tangan dulu');
				return
			}
			if (ctglttd == '') {
				alert('pilih tanggal dulu');
				return
			}

			var url = "<?php echo site_url(); ?>tukd/cetak_register_sp2d";
			window.open(url + '/' + ckdskpd + '/' + ttd + '/' + ttd2 + '/' + ctglttd + '/' + ctk + "/" + atas + "/" + bawah + "/" + kiri + "/" + kanan + '?tgl1=' + ctgl1 + '&tgl2=' + ctgl2, '_blank');
			window.focus();
		}





		function get_skpd() {

			$.ajax({
				url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#skpd").attr("value", data.kd_skpd);
					$("#nmskpd").attr("value", data.nm_skpd);
					kode = data.kd_skpd;
					validate_rek();

				}
			});

		}
	</script>

</head>

<body>



	<div id="content">

		<div id="accordion">

			<p align="center">
			<table title="Cetak" style="width: 900px;" border="0">

				<tr>
					<td width="20%">SKPD</td>
					<td width="1%">:</td>
					<td width="79%"><input id="skpd" name="skpd" style="width: 130px;border:none" readonly="true" />&ensp;
						<input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
					</td>
				</tr>
				<tr>
					<td>Periode</td>
					<td>:</td>
					<td><input type="text" id="tgl1" style="width: 100px;" /> s.d. <input type="text" id="tgl2" style="width: 100px;" />
					</td>
				</tr>

				<tr>
					<td width="20%">TANGGAL TTD</td>
					<td width="1%">:</td>
					<td><input type="text" id="tgl_ttd" style="width: 100px;" /> </td>
				</tr>
				<tr>
					<td colspan="4">
						<table style="width:100%;" border="0">
							<td width="20%">Pengguna Anggaran</td>
							<td width="1%">:</td>
							<td><input type="text" id="ttd1" style="width: 100px;" />
							</td>

							<td width="20%">Bendahara Pengeluaran</td>
							<td width="1%">:</td>
							<td><input type="text" id="ttd2" style="width: 100px;" />
							</td>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan='4' width="100%" height="40"><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
				</tr>
				<tr>
					<td colspan='4'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Kiri : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="10" /> &nbsp;&nbsp;
						Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="2" /> &nbsp;&nbsp;
						Atas : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="10" /> &nbsp;&nbsp;
						Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
					</td>
				</tr>


				<tr>
					<td colspan="2"> Register SPP</td>
					<td>
						<button class="button-hitam" onclick="javascript:cetak(1);"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
						<button class="button-orange" onclick="javascript:cetak(0);"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
						<button class="button" onclick="javascript:cetak(2);"><i class="fa fa-file-excel-o" style="font-size:15px"></i> Excel</button>
						<button class="button-biru" onclick="javascript:cetak(3);"><i class="fa fa-file-word-o" style="font-size:15px"></i> Word</button>
				</tr>
				<tr>
					<td colspan="2"> Register SPM</td>
					<td>
						<button class="button-hitam" onclick="javascript:cetak2(1);"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
						<button class="button-orange" onclick="javascript:cetak2(0);"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
						<button class="button" onclick="javascript:cetak2(2);"><i class="fa fa-file-excel-o" style="font-size:15px"></i> Excel</button>
						<button class="button-biru" onclick="javascript:cetak2(3);"><i class="fa fa-file-word-o" style="font-size:15px"></i> Word</button>
					</td>
				</tr>
				<tr>
					<td colspan="2"> Register SP2D</td>
					<td>
						<button class="button-hitam" onclick="javascript:cetak3(1);"><i class="fa fa-television" style="font-size:15px"></i> Layar</button>
						<button class="button-orange" onclick="javascript:cetak3(0);"><i class="fa fa-file-pdf-o" style="font-size:15px"></i> PDF</button>
						<button class="button" onclick="javascript:cetak3(2);"><i class="fa fa-file-excel-o" style="font-size:15px"></i> Excel</button>
						<button class="button-biru" onclick="javascript:cetak3(3);"><i class="fa fa-file-word-o" style="font-size:15px"></i> Word</button>
					</td>
				</tr>
			</table>
			</p>



		</div>

	</div>



</body>

</html>