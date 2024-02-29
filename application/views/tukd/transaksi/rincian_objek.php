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
		var kode = '';

		$(document).ready(function() {
			$("#accordion").accordion();
			$("#dialog-modal").dialog({
				height: 400,
				width: 800
			});
			get_skpd();
			$('#j_ang').combogrid({
				panelWidth: 400,
				idField: 'kode',
				textField: 'nama',
				mode: 'remote',
				url: '<?php echo base_url(); ?>index.php/rka_ro/load_jang/',
				columns: [
					[{
						field: 'nama',
						title: 'Nama',
						width: 400
					}]
				],
				onSelect: function(rowIndex, rowData) {}
			});

		});



		//	$(function(){
		//	$('#sskpd').combogrid({  
		//		panelWidth:630,  
		//		idField:'kd_skpd',  
		//		textField:'kd_skpd',  
		//		mode:'remote',
		//		url:'<?php echo base_url(); ?>index.php/akuntansi/skpd',  
		//		columns:[[  
		//			{field:'kd_skpd',title:'Kode SKPD',width:100},  
		//			{field:'nm_skpd',title:'Nama SKPD',width:500}    
		//		]],
		//		onSelect:function(rowIndex,rowData){
		//			kdskpd = rowData.kd_skpd;
		//			$("#nmskpd").attr("value",rowData.nm_skpd);
		//			$("#skpd").attr("value",rowData.kd_skpd);
		//			validate_giat(kdskpd);
		//            validate_ttd(kdskpd);
		//            validate_rek(kode);
		//		}  
		//		});
		//        
		//       
		//	});

		function get_skpd() {

			$.ajax({
				url: '<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
				type: "POST",
				dataType: "json",
				success: function(data) {
					$("#sskpd").attr("value", data.kd_skpd);
					$("#nmskpd").attr("value", data.nm_skpd);
					kdskpd = data.kd_skpd;
					validate_giat(kdskpd);
					validate_ttd(kdskpd);
					validate_rek(kode);

				}
			});

		}

		function validate_giat() {
			$(function() {
				$('#giat').combogrid({
					panelWidth: 700,
					idField: 'kd_sub_kegiatan',
					textField: 'kd_sub_kegiatan',
					mode: 'remote',
					url: '<?php echo base_url(); ?>/index.php/cetak_rincian_objek/ld_giat_rinci_objek/' + kdskpd,
					columns: [
						[{
								field: 'kd_sub_kegiatan',
								title: 'Kode Kegiatan',
								width: 150
							},
							{
								field: 'nm_sub_kegiatan',
								title: 'Nama Kegiatan',
								width: 660
							}
						]
					],
					onSelect: function(rowIndex, rowData) {
						kode = rowData.kd_sub_kegiatan;
						$("#nm_giat").attr("value", rowData.nm_sub_kegiatan);
						validate_rek(kode);
					}
				});
			});
		}

		function validate_rek() {
			$(function() {
				$('#kdrek5').combogrid({
					panelWidth: 630,
					idField: 'kd_rek6',
					textField: 'kd_rek6',
					mode: 'remote',
					url: '<?php echo base_url(); ?>index.php/cetak_rincian_objek/ld_rek_rinci_objek/' + kode,
					columns: [
						[{
								field: 'kd_rek6',
								title: 'Kode Rekening',
								width: 100
							},
							{
								field: 'nm_rek6',
								title: 'Nama Rekening',
								width: 500
							}
						]
					],
					onSelect: function(rowIndex, rowData) {
						rekening = rowData.kd_rek6;
						$("#kdrek5").attr("value", rowData.kd_rek6);
						$("#nmrek5").attr("value", rowData.nm_rek6);
					}
				});
			});
		}

		$(function() {
			$('#dcetak').datebox({
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
			$('#dcetak2').datebox({
				required: true,
				formatter: function(date) {
					var y = date.getFullYear();
					var m = date.getMonth() + 1;
					var d = date.getDate();
					return y + '-' + m + '-' + d;
				}
			});
		});

		function validate_ttd() {
			$(function() {
				$('#ttd1').combogrid({
					panelWidth: 600,
					idField: 'nip',
					textField: 'nip',
					mode: 'remote',
					url: '<?php echo base_url(); ?>index.php/cetak_rincian_objek/load_ttd2/BK',
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
					},
					onLoadSuccess: function(data) {
						$("#ttd1").combogrid('setValue', data.rows[0].nip);
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


				cdate = '<?php echo date("Y-m-d"); ?>';
				$("#tgl_ttd").datebox("setValue", cdate);

				$('#ttd2').combogrid({
					panelWidth: 600,
					idField: 'nip',
					textField: 'nip',
					mode: 'remote',
					url: '<?php echo base_url(); ?>index.php/cetak_rincian_objek/load_ttd/PA',
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
					},
					onLoadSuccess: function(data) {
						$("#ttd2").combogrid('setValue', data.rows[0].nip);
					}
				});
			});
		}


		function cetak(ctk) {
			var spasi = document.getElementById('spasi').value;
			var kgiat = kode.split(" ").join("");;
			var skpd = kdskpd.split(" ").join("");;
			var rekening = $("#kdrek5").combogrid('getValue');
			var dcetak = $('#dcetak').datebox('getValue');
			var dcetak2 = $('#dcetak2').datebox('getValue');
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			if (kode == '') {
				alert('Kegiatan tidak boleh kosong!');
				exit();
			}
			if (rekening == '') {
				alert('Rekening tidak boleh kosong!');
				exit();
			}
			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}
			if (dcetak == '') {
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

			var rek5 = rekening.split(" ").join("");
			var ttd_1 = ttd1.split(" ").join("123456789");
			var ttd_2 = ttd2.split(" ").join("123456789");
			var url = "<?php echo site_url(); ?>cetak_rincian_objek/ctk_rincian_objek";
			window.open(url + '/' + dcetak + '/' + ttd_1 + '/' + kdskpd + '/' + rek5 + '/' + dcetak2 + '/' + kgiat + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + spasi, '_blank');
			window.focus();
		}


		function cetak2(ctk) {
			var spasi = document.getElementById('spasi').value;
			var dcetak = $('#dcetak').datebox('getValue');
			var dcetak2 = $('#dcetak2').datebox('getValue');
			var kgiat = kode.split(" ").join("");;
			var skpd = kdskpd.split(" ").join("");;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}
			if (dcetak == '') {
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
			var url = "<?php echo site_url(); ?>cetak_rincian_objek/cetak_rincian_objek_kegiatan";
			window.open(url + '/' + dcetak + '/' + ttd_1 + '/' + kdskpd + '/' + dcetak2 + '/' + kgiat + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + spasi, '_blank');
			window.focus();
		}

		function cetak3(ctk) {
			var spasi = document.getElementById('spasi').value;
			var dcetak = $('#dcetak').datebox('getValue');
			var dcetak2 = $('#dcetak2').datebox('getValue');
			var skpd = kdskpd.split(" ").join("");;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1 = $("#ttd1").combogrid('getValue');
			var ttd2 = $("#ttd2").combogrid('getValue');
			if (ctglttd == '') {
				alert('Tanggal tidak boleh kosong!');
				exit();
			}
			if (dcetak == '') {
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
			var url = "<?php echo site_url(); ?>cetak_rincian_objek/cetak_rincian_objek_all";
			window.open(url + '/' + dcetak + '/' + ttd_1 + '/' + kdskpd + '/' + dcetak2 + '/' + ctglttd + '/' + ttd_2 + '/' + ctk + '/' + spasi, '_blank');
			window.focus();
		}

		function cetak4(ctk) {
			var spasi = document.getElementById('spasi').value;
			var kgiat = kode.split(" ").join("");;
			var rekening = $("#kdrek5").combogrid('getValue');
			var skpd = kdskpd.split(" ").join("");
			var anggaran = $("#j_ang").combogrid('getValue');
			var url = "<?php echo site_url(); ?>cetak_rincian_objek/cetak_cek_rekening";
			window.open(url + '/' + skpd + '/' + kgiat + '/' + rekening + '/' + ctk + '/' + anggaran, '_blank');
			window.focus();
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


		<h3>CETAK LAPORAN RINCIAN PEROBJEK</h3>
		<div id="accordion">
			<p align="right">
			<table id="sp2d" title="CETAK LAPORAN RINCIAN PEROBJEK" style="width:922px;height:200px;">
				<tr>
					<td width="20%" style="border-top: solid 1px black;border-left: solid 1px black;border-bottom: none;"><B>SKPD</B></td>
					<td width="80%" style="border-top: solid 1px black;border-right: solid 1px black;border-bottom: none;"><input id="sskpd" name="sskpd" style="width: 150px;" readonly="true" />&nbsp;<input id="nmskpd" name="nmskpd" style="width: 565px; " readonly="true" /></td>
				</tr>
				<tr>
					<td style="border-left: solid 1px black;border-bottom: none;"><B>KEGIATAN</B></td>
					<td style="border-right: solid 1px black;border-bottom: none;"><input id="giat" name="giat" style="width: 170px;" />&nbsp;<input id="nm_giat" name="nm_giat" style="width: 480px;" readonly="true" /></td>
				</tr>

				<tr>
					<td style="border-left: solid 1px black;border-bottom: none;"><B>REKENING</B></td>
					<td style="border-right: solid 1px black;border-bottom: none;"><input id="kdrek5" name="kdrek5" style="width: 170px;" />&nbsp;<input id="nmrek5" name="nmrek5" style="width: 480px;" readonly="true" /></td>
				</tr>

				<tr>
					<td style="border-left: solid 1px black;border-bottom: none;"><B>PERIODE</B></td>
					<td style="border-right: solid 1px black;border-bottom: none;"><input id="dcetak" name="dcetak" type="text" style="width:100px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text" style="width:100px" /></td>
				</tr>
				<tr>
					<td style="border-left: solid 1px black;border-bottom: none;"><B>TANGGAL TTD</B></td>
					<td style="border-right: solid 1px black;border-bottom: none;"><input id="tgl_ttd" name="tgl_ttd" style="width: 100px;" /></td>
				</tr>
				<tr>
					<td style="border-left: solid 1px black;border-bottom: none;"><B>Pengguna Anggaran</B></td>
					<td style="border-right: solid 1px black;border-bottom: none;"><input type="text" id="ttd2" style="width: 200px;" />&nbsp;<input type="nm_ttd2" id="nm_ttd2" readonly="true" style="width: 200px;border:0" /></td>
				</tr>
				<tr>
					<td style="border-left: solid 1px black;border-bottom: solid 1px black;"><B>Bendahara Pengeluaran</B></td>
					<td style="border-right: solid 1px black;border-bottom: solid 1px black;"><input type="text" id="ttd1" style="width: 200px;" />&nbsp;<input type="text" id="nm_ttd1" readonly="true" style="width: 200px;border:0" /></td>
				</tr>
				<tr>
					<td style="border-left: solid 1px black;border-top: none;border-bottom: solid 1px black;"><B>Spasi</B></td>
					<td style="border-right: solid 1px black;border-top: none;border-bottom: solid 1px black;"><input type="number" id="spasi" style="width: 50px;" value="1" /> </td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: none;">
						<input type="text" id="1" style="width:700px;border:0" placeholder="Cetak Per-rekening" readonly="true" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: solid 1px black;">
						<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak Layar</a>&nbsp; &nbsp; &nbsp;
						<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak PDF</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: none;">
						<input type="text" id="1" style="width:700px;border:0" placeholder="Cetak Langsung Perkegiatan, Tidak perlu pilih rekening namun Loading cetakan lebih lama" readonly="true" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: solid 1px black;">
						<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(0);">Cetak Layar</a>&nbsp; &nbsp; &nbsp;
						<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(1);">Cetak PDF</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: none;">
						<input type="text" id="1" style="width:700px;border:0" placeholder="Cetak Langsung semua Rekening, Loading cetakan lebih lama" readonly="true" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: solid 1px black;">
						<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak3(0);">Cetak Layar</a>&nbsp; &nbsp; &nbsp;
						<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak3(1);">Cetak PDF</a>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: none;">&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;
						<input type="text" id="1" style="width:220px;border:0;" placeholder="Cek Pemakaian Anggaran Rekening" readonly="true" /> &nbsp;&nbsp;

						<input id="j_ang" name="j_ang" style="width: 300px;" />

					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-left: solid 1px black;border-right: solid 1px black;border-bottom: solid 1px black;">
						<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak4(0);">Cetak Layar</a>&nbsp; &nbsp; &nbsp;
						<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak4(1);">Cetak PDF</a>
					</td>
				</tr>
			</table>
			</p>

		</div>
	</div>


</body>

</html>