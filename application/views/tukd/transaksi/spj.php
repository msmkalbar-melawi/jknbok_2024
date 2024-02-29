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
		var nip = '';
		var kdskpd = '';
		var kdrek5 = '';
		var pil_ctk = '';

		$(document).ready(function() {

			$("#dperiode").hide();
			$("#dbulan").hide();

			$("#accordion").accordion();
			$("#dialog-modal").dialog({
				height: 400,
				width: 800
			});
			get_skpd();
		});

		$(function() {
			$('#ttd1').combogrid({
				panelWidth: 600,
				idField: 'nip',
				textField: 'nip',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/BK',
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
					$("#nm_ttd1").attr("value", rowData.nama);
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

			$('#tgl_1').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#tgl_2').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});

			$('#ttd2').combogrid({
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
				],
				onSelect: function(rowIndex, rowData) {
					$("#nm_ttd2").attr("value", rowData.nama);
				}
			});
			$('#rak').combogrid({
				panelWidth: 210,
				idField: 'kode',
				textField: 'nama',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/cetak_spj/anggaran',
				columns: [
					[{
						field: 'nama',
						title: 'Nama',
						width: 210
					}]
				],
				onSelect: function(rowIndex, rowData) {
					rak = rowData.kode;

				}
			});
		});

		function validate1() {
			var bln1 = document.getElementById('bulan1').value;

		}

		function get_skpd() {

			$.ajax({
				url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#sskpd").attr("value", data.kd_skpd);
					$("#nmskpd").attr("value", data.nm_skpd);
					// $("#skpd").attr("value",rowData.kd_skpd);
					kdskpd = data.kd_skpd;

				}
			});

		}

		function cetak(ctk) {
			var skpd = kdskpd;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var jns_ang = $("#rak").combogrid('getValue');

			if (pil_ctk == '1') {
				//priode    
				var ctgl_1 = $('#tgl_1').datebox('getValue');
				var ctgl_2 = $('#tgl_2').datebox('getValue');

				if (ctgl_2 == '') {
					alert('Periode tidak boleh kosong!');
					exit();
				}

			} else {
				//bulan
				var bulan = document.getElementById('bulan1').value;
				if (bulan == 0) {
					alert('Bulan tidak boleh kosong!');
					exit();
				}

			}

			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}

			if (ttd1 == '') {
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if (ttd2 == '') {
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 = ttd1.split(" ").join("123456789");
			var ttd_2 = ttd2.split(" ").join("123456789");

			if (pil_ctk == '2') {
				var url = "<?php echo site_url(); ?>cetak_spj/spj";
				window.open(url + '/' + skpd + '/' + bulan + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk/' + jns_ang, '_blank');
				window.focus();
			} else {
				var url = "<?php echo site_url(); ?>cetak_spj/cetak_spj_priode";
				window.open(url + '/' + skpd + '/' + ctgl_1 + '/' + ctgl_2 + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk/' + jns_ang, '_blank');
				window.focus();
			}

		}

		function cetak2(ctk) {
			var skpd = kdskpd;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var jns_ang = $("#rak").combogrid('getValue');

			if (pil_ctk == '1') {
				//priode    
				var ctgl_1 = $('#tgl_1').datebox('getValue');
				var ctgl_2 = $('#tgl_2').datebox('getValue');

				if (ctgl_2 == '') {
					alert('Periode tidak boleh kosong!');
					exit();
				}

			} else {
				//bulan
				var bulan = document.getElementById('bulan1').value;
				if (bulan == 0) {
					alert('Bulan tidak boleh kosong!');
					exit();
				}

			}

			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}

			if (ttd1 == '') {
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if (ttd2 == '') {
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 = ttd1.split(" ").join("123456789");
			var ttd_2 = ttd2.split(" ").join("123456789");

			if (pil_ctk == '2') {
				var url = "<?php echo site_url(); ?>cetak_spj/spj_melawi";
				window.open(url + '/' + skpd + '/' + bulan + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk/' + jns_ang, '_blank');
				window.focus();
			} else {
				var url = "<?php echo site_url(); ?>cetak_spj/cetak_spj_priode";
				window.open(url + '/' + skpd + '/' + ctgl_1 + '/' + ctgl_2 + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk/' + jns_ang, '_blank');
				window.focus();
			}

		}


		function cetak3(ctk) {
			var skpd = kdskpd;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var jns_ang = $("#rak").combogrid('getValue');

			if (pil_ctk == '1') {
				//priode    
				var ctgl_1 = $('#tgl_1').datebox('getValue');
				var ctgl_2 = $('#tgl_2').datebox('getValue');

				if (ctgl_2 == '') {
					alert('Periode tidak boleh kosong!');
					exit();
				}

			} else {
				//bulan
				var bulan = document.getElementById('bulan1').value;
				if (bulan == 0) {
					alert('Bulan tidak boleh kosong!');
					exit();
				}

			}

			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}

			if (ttd1 == '') {
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if (ttd2 == '') {
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 = ttd1.split(" ").join("123456789");
			var ttd_2 = ttd2.split(" ").join("123456789");

			if (pil_ctk == '2') {
				var url = "<?php echo site_url(); ?>cetak_spj/spjadministrasi_melawi";
				window.open(url + '/' + skpd + '/' + bulan + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk/' + jns_ang, '_blank');
				window.focus();
			} else {
				var url = "<?php echo site_url(); ?>cetak_spj/cetak_spj_priode";
				window.open(url + '/' + skpd + '/' + ctgl_1 + '/' + ctgl_2 + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk/' + jns_ang, '_blank');
				window.focus();
			}

		}

		function cetak1(ctk) {
			var skpd = kdskpd;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var jns_ang = $("#rak").combogrid('getValue');

			if (pil_ctk == '1') {
				//priode    
				var ctgl_1 = $('#tgl_1').datebox('getValue');
				var ctgl_2 = $('#tgl_2').datebox('getValue');

				if (ctgl_2 == '') {
					alert('Periode tidak boleh kosong!');
					exit();
				}

			} else {
				//bulan
				var bulan = document.getElementById('bulan1').value;
				if (bulan == 0) {
					alert('Bulan tidak boleh kosong!');
					exit();
				}

			}

			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}

			if (ttd1 == '') {
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if (ttd2 == '') {
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 = ttd1.split(" ").join("123456789");
			var ttd_2 = ttd2.split(" ").join("123456789");

			if (pil_ctk == '2') {
				var url = "<?php echo site_url(); ?>cetak_spj/spj";
				window.open(url + '/' + skpd + '/' + bulan + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/2' + '/bk' + '/' + jns_ang, '_blank');
				window.focus();
			} else {
				var url = "<?php echo site_url(); ?>cetak_spj/cetak_spj_priode";
				window.open(url + '/' + skpd + '/' + ctgl_1 + '/' + ctgl_2 + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/2' + '/bk' + '/' + jns_ang, '_blank');
				window.focus();
			}

		}

		function cetak_ali(ctk) {
			var skpd = kdskpd;
			//var bulan   =  document.getElementById('bulan1').value;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var jns_ang = document.getElementById('jns_ang').value;
			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}

			if (pil_ctk == '1') {
				//priode    
				var ctgl_1 = $('#tgl_1').datebox('getValue');
				var ctgl_2 = $('#tgl_2').datebox('getValue');

				if (ctgl_2 == '') {
					alert('Periode tidak boleh kosong!');
					exit();
				}

			} else {
				//bulan
				var bulan = document.getElementById('bulan1').value;
				if (bulan == 0) {
					alert('Bulan tidak boleh kosong!');
					exit();
				}

			}

			if (ttd1 == '') {
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if (ttd2 == '') {
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 = ttd1.split(" ").join("123456789");
			var ttd_2 = ttd2.split(" ").join("123456789");


			if (pil_ctk == '2') {
				var url = "<?php echo site_url(); ?>cetak_spj/spj";
				window.open(url + '/' + skpd + '/' + bulan + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk' + '/' + jns_ang, '_blank');
				window.focus();
			} else {
				var url = "<?php echo site_url(); ?>cetak_spj/cetak_spj_priode";
				window.open(url + '/' + skpd + '/' + ctgl_1 + '/' + ctgl_2 + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk' + '/' + jns_ang, '_blank');
				window.focus();
			}
		}

		function cetak_ali2(ctk) {
			var skpd = kdskpd;
			//var bulan   =  document.getElementById('bulan1').value;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			var atas = document.getElementById('atas').value;
			var bawah = document.getElementById('bawah').value;
			var kanan = document.getElementById('kanan').value;
			var kiri = document.getElementById('kiri').value;
			var jns_ang = document.getElementById('jns_ang').value;
			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}

			if (pil_ctk == '1') {
				//priode    
				var ctgl_1 = $('#tgl_1').datebox('getValue');
				var ctgl_2 = $('#tgl_2').datebox('getValue');

				if (ctgl_2 == '') {
					alert('Periode tidak boleh kosong!');
					exit();
				}

			} else {
				//bulan
				var bulan = document.getElementById('bulan1').value;
				if (bulan == 0) {
					alert('Bulan tidak boleh kosong!');
					exit();
				}

			}

			if (ttd1 == '') {
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if (ttd2 == '') {
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}
			var ttd_1 = ttd1.split(" ").join("123456789");
			var ttd_2 = ttd2.split(" ").join("123456789");


			if (pil_ctk == '2') {
				var url = "<?php echo site_url(); ?>cetak_spj/spj_melawi";
				window.open(url + '/' + skpd + '/' + bulan + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk' + '/' + jns_ang, '_blank');
				window.focus();
			} else {
				var url = "<?php echo site_url(); ?>cetak_spj/cetak_spj_priode";
				window.open(url + '/' + skpd + '/' + ctgl_1 + '/' + ctgl_2 + '/' + ttd_1 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + atas + '/' + bawah + '/' + kiri + '/' + kanan + '/1' + '/bk' + '/' + jns_ang, '_blank');
				window.focus();
			}
		}

		function opt(val) {
			pil_ctk = val;
			if (pil_ctk == '1') {
				$("#dbulan").hide();
				$("#dperiode").show();
			} else if (pil_ctk == '2') {
				$("#dbulan").show();
				$("#dperiode").hide();
			} else {
				exit();
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



		<h3>CETAK LAPORAN PERTANGGUNGJAWABAN SKPD (SPJ)</h3>


		<p align="right">
		<table id="sp2d" title="Cetak Buku Besar" style="width:100%;height:200px;">

			<tr>
				<td width="20%" height="40"><B>SKPD</B></td>
				<td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
			</tr>

			<tr>
				<td width="20%" height="40">
					<input type="radio" name="cetak" value="1" onclick="opt(this.value)" />Periode
					<input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" />Bulan
				</td>
				<td>
					<div id="dbulan">
						<?php echo $this->rka_model->combo_bulan('bulan1', 'onchange="javascript:validate1();"'); ?>
					</div>
					<div id="dperiode">
						<input type="text" id="tgl_1" style="width: 200px;" /> s.d. <input type="text" id="tgl_2" style="width: 200px;" />
					</div>
				</td>
			</tr>

			<tr>
				<td width="20%" height="40"><B>JENIS ANGGARAN </B></td>
				<td width="80%"> <input id="rak" name="rak" class="form-control" style="width: 200px;" />
				</td>
			</tr>


			<tr>
				<td width="20%" height="40"><B>TANGGAL TTD</B></td>
				<td width="80%"><input id="tgl_ttd" name="tgl_ttd" style="width: 200px;" /></td>
			</tr>
			<tr>
				<td colspan="4">
					<div id="div_bend">
						<table style="width:100%;" border="0">
							<td width="20%">Pengguna Anggaran</td>
							<td><input type="text" id="ttd2" style="width: 200px;" /> &nbsp;&nbsp;
								<input type="nm_ttd2" id="nm_ttd2" readonly="true" style="width: 200px;border:0" />
							</td>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<div id="div_bend">
						<table style="width:100%;" border="0">
							<td width="20%">Bendahara Pengeluaran</td>
							<td><input type="text" id="ttd1" style="width: 200px;" /> &nbsp;&nbsp;
								<input type="text" id="nm_ttd1" readonly="true" style="width: 200px;border:0" />
							</td>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan='2' width="20%" height="40"><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
			</tr>
			<tr>
				<td colspan='2'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Kiri : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
					Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
					Atas : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
					Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
				</td>
			</tr>


			<tr align="center">
				<td colspan="2">
					<button class="button-hitam" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-television"></i> SPJ Fungsional</a>
						&nbsp;&nbsp;
						<button class="button-biru" plain="true" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-pdf"></i> SPJ Fungsional</a>
							&nbsp;&nbsp;
							<button class="button-kuning" plain="true" plain="true" onclick="javascript:cetak_ali(2);"><i class="fa fa-excel"></i> SPJ Fungsional</a>
				</td>
			</tr>

			<tr align="center">
				<td colspan="2">
					<button class="button-hitam" plain="true" onclick="javascript:cetak2(0);"><i class="fa fa-television"></i> SPJ Fungsional Akuntansi</a>
						&nbsp;&nbsp;
						<button class="button-biru" plain="true" plain="true" onclick="javascript:cetak2(1);"><i class="fa fa-pdf"></i> SPJ Fungsional Akuntansi</a>
							&nbsp;&nbsp;
							<button class="button-kuning" plain="true" plain="true" onclick="javascript:cetak_ali2(2);"><i class="fa fa-excel"></i> SPJ Fungsional Akuntansi</a>
				</td>
			</tr>

			<tr align="center">
				<td colspan="2">
					<button class="button-hitam" plain="true" plain="true" onclick="javascript:cetak1(0);"><i class="fa fa-television"></i> SPJ Administratif</a>
						&nbsp;&nbsp;
						<button class="button-biru" plain="true" plain="true" onclick="javascript:cetak1(1);"><i class="fa fa-pdf"></i> SPJ Administratif</a>
							&nbsp;&nbsp;
							<button class="button-kuning" plain="true" plain="true" onclick="javascript:cetak_ali1(2);"><i class="fa fa-excel"></i> SPJ Administratif</a>
				</td>
			</tr>
			<tr align="center">
				<td colspan="2">
					<button class="button-hitam" plain="true" plain="true" onclick="javascript:cetak3(0);"><i class="fa fa-television"></i> SPJ Administratif Akuntansi</a>
						&nbsp;&nbsp;
						<button class="button-biru" plain="true" plain="true" onclick="javascript:cetak3(1);"><i class="fa fa-pdf"></i> SPJ Administratif Akuntansi</a>
							&nbsp;&nbsp;
							<button class="button-kuning" plain="true" plain="true" onclick="javascript:cetak_ali3(2);"><i class="fa fa-excel"></i> SPJ Administratif Akuntansi</a>
				</td>
			</tr>

		</table>
		</p>



	</div>


</body>

</html>