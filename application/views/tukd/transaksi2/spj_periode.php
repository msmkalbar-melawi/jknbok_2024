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
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script type="text/javascript"> 
    var nip='';
	var kdskpd='';
	var kdrek5='';
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800            
            });  
            get_skpd();           
        });   
    
	$(function(){
	 $('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BK',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd1").attr("value",rowData.nama);
           } 
            });

		$('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		
		$('#tgl1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });

		$('#tgl2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
			
         
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd2").attr("value",rowData.nama);
           } 
            }); 
	});
	
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                       // $("#skpd").attr("value",rowData.kd_skpd);
        								kdskpd = data.kd_skpd;
                                        
        							  }                                     
        	});
             
        }
		function cetak(ctk){
			var skpd   = kdskpd; 
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ctgl1 = $('#tgl1').datebox('getValue');
			var ctgl2 = $('#tgl2').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			var atas   =  document.getElementById('atas').value;
			var bawah   =  document.getElementById('bawah').value;
			var kanan   =  document.getElementById('kanan').value;
			var kiri   =  document.getElementById('kiri').value;
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(ctgl1==0){
				alert('Tanggal Awal tidak boleh kosong!');
				exit();
			}
			if(ctgl2==0){
				alert('Tanggal Akhir tidak boleh kosong!');
				exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}			
			var ttd_1 =ttd1.split(" ").join("123456789");
			var ttd_2 =ttd2.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>/tukd2/cetak_spj_periode";  
			window.open(url+'/'+skpd+'/'+ctgl1+'/'+ctgl2+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+ctk+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan+'/1', '_blank');
			window.focus();
        }
		
		function cetak1(ctk){
			var skpd   = kdskpd; 
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ctgl1 = $('#tgl1').datebox('getValue');
			var ctgl2 = $('#tgl2').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			var atas   =  document.getElementById('atas').value;
			var bawah   =  document.getElementById('bawah').value;
			var kanan   =  document.getElementById('kanan').value;
			var kiri   =  document.getElementById('kiri').value;
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(ctgl1==0){
				alert('Tanggal Awal tidak boleh kosong!');
				exit();
			}
			if(ctgl2==0){
				alert('Tanggal Akhir tidak boleh kosong!');
				exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}			
			var ttd_1 =ttd1.split(" ").join("123456789");
			var ttd_2 =ttd2.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>/tukd2/cetak_spj_periode";  
			window.open(url+'/'+skpd+'/'+ctgl1+'/'+ctgl2+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+ctk+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan+'/2', '_blank');
			window.focus();
        }
		
        function cetak2(ctk){
			var skpd   = kdskpd; 
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ctgl1 = $('#tgl1').datebox('getValue');
			var ctgl2 = $('#tgl2').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			var atas   =  document.getElementById('atas').value;
			var bawah   =  document.getElementById('bawah').value;
			var kanan   =  document.getElementById('kanan').value;
			var kiri   =  document.getElementById('kiri').value;
			if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
			}
			if(ctgl1==0){
				alert('Tanggal Awal tidak boleh kosong!');
				exit();
			}
			if(ctgl2==0){
				alert('Tanggal Akhir tidak boleh kosong!');
				exit();
			}
			if(ttd1==''){
				alert('Bendahara Pengeluaran tidak boleh kosong!');
				exit();
			}
			if(ttd2==''){
				alert('Pengguna Anggaran tidak boleh kosong!');
				exit();
			}			
			var ttd_1 =ttd1.split(" ").join("123456789");
			var ttd_2 =ttd2.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>/tukd2/cetak_spj_periode";  
			window.open(url+'/'+skpd+'/'+ctgl1+'/'+ctgl2+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+ctk+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan+'/3', '_blank');
			window.focus();
        }
        

    </script>

    <STYLE TYPE="text/css"> 
		 input.right{ 
         text-align:right; 
         } 
	</STYLE> 

</head>
<body>

<div id="content">



<h3>CETAK LAPORAN PERTANGGUNGJAWABAN SKPD (SPJ)</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:922px;height:200px;" >  
		<tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
		</tr>
        <tr >
			<td width="20%" height="40" ><B>TANGGAL</B></td>
			<td width="80%"><input id="tgl1" name="tgl1" style="width: 150px;" /> S/D <input id="tgl2" name="tgl2" style="width: 150px;" /></td>
		</tr> 
		<tr >
			<td width="20%" height="40" ><B>TANGGAL TTD</B></td>
			<td width="80%"><input id="tgl_ttd" name="tgl_ttd" style="width: 150px;" /></td>
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
		<tr >
			<td colspan='2'width="20%" height="40" ><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
		</tr>
		<tr >
			<td colspan='2'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Kiri  : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
			Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
			Atas  : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
			Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
			</td>
		</tr>
		
		
		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">SPJ Fungsional</a>
			&nbsp;&nbsp;
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">SPJ Fungsional</a>
			</td>
		</tr>
		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak1(0);">SPJ Administratif</a>
			&nbsp;&nbsp;
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak1(1);">SPJ Administratif</a>
			</td>
		</tr>
		
		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak2(0);">SPJ Belanja</a>
			&nbsp;&nbsp;
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak2(1);">SPJ Belanja</a>
			</td>
		</tr>
		
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>