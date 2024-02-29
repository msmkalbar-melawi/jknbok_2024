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
		$("#div1").hide();
		$("#div2").hide(); 			 
		$("#div3").hide(); 			 
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

		$('#pasal').combogrid({  
                panelWidth:550,  
                idField:'kd_rek5',  
                textField:'kd_rek5',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_pasal_pajak',  
                columns:[[  
                    {field:'kd_rek5',title:'Kode',width:150},
                    {field:'nm_rek5',title:'Rekening',width:350}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_pasal").attr("value",rowData.nm_rek5);
           } 
            }); 

			
         });
	
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    
    function get_skpd(){
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
		
		function validate_jenis() {
			var jns   =  document.getElementById('jenis').value;
			 if ((jns=='1') ||(jns=='4') ){
						$("#div1").show();
						$("#div2").hide();
						$("#div3").hide();
					} else  if ((jns=='2') ||(jns=='3') ||(jns=='0') ){
						$("#div1").hide();
						$("#div2").show();
						$("#div3").hide();
						} else {
						$("#div1").hide();
						$("#div2").hide();	
						$("#div3").hide();
						}         	
        }
		function validate_pasal() {
			var rinci1   =  document.getElementById('rinci1').value;
			 if (rinci1=='6'){
						$("#div3").show();
				
						} else {
						$("#div3").hide();
						}         	
        }
		
		
		function cetak(ctk)
        {
			var skpd   = kdskpd; 
			var bulan   =  document.getElementById('bulan1').value;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			//var jns   = document.getElementById('jenis').value;
			var spasi   = document.getElementById('spasi').value;
			if(ctglttd==''){
				alert('Tanggal tidak boleh kosong!');
				exit();
			}
			if(bulan==''){
				alert('Bulan tidak boleh kosong!');
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
			
			var url    = "<?php echo site_url(); ?>/tukd2/register_denda_keterlambatan";  
			
			window.open(url+'/'+skpd+'/'+bulan+'/'+ctk+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+'-'+'/'+spasi, '_blank');
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



<h3>CETAK REGISTER DENDA KETERLAMBATAN</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:922px;height:200px;" >  
		<tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
		</tr>
        <tr >
			<td width="20%" height="40" ><B>BULAN</B></td>
			<td><?php echo $this->rka_model->combo_bulan('bulan1'); ?> </td>
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
			<td width="20%" height="40" ><B>SPASI</B></td>
			<td><input id="spasi"  style="width: 50px" type="number" value="3" /> </td>
		</tr>
		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak</a>
			</td>
		</tr>
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>