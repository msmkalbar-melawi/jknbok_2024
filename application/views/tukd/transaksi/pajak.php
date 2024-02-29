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
                url:'<?php echo base_url(); ?>index.php/cetak_pajak/load_ttd/BK',  
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
                url:'<?php echo base_url(); ?>index.php/cetak_pajak/load_ttd/PA',  
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
                idField:'kd_rek6',  
                textField:'kd_rek6',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/cetak_pajak/load_pasal_pajak',  
                columns:[[  
                    {field:'kd_rek6',title:'Kode',width:150},
                    {field:'nm_rek6',title:'Rekening',width:350}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_pasal").attr("value",rowData.nm_rek6);
           } 
            }); 

			
         });
	
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    
    function get_skpd(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka_penetapan/config_skpd',
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
			var spasi  = document.getElementById('spasi').value; 
			var skpd   = kdskpd; 
			var bulan   =  document.getElementById('bulan1').value;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			var ttd1   = $("#ttd1").combogrid('getValue');
			var ttd2   = $("#ttd2").combogrid('getValue'); 
			var jns   = document.getElementById('jenis').value;
			var rinci   = document.getElementById('rinci').value;
			var rinci1   = document.getElementById('rinci1').value;
			var pasal   = $("#pasal").combogrid('getValue'); 
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
			var url    = "<?php echo site_url(); ?>cetak_pajak/cetak_pajak1";  
			var url2    = "<?php echo site_url(); ?>cetak_pajak/cetak_pajak2";  
			var url3    = "<?php echo site_url(); ?>cetak_pajak/cetak_pajak3";  
			var url4    = "<?php echo site_url(); ?>cetak_pajak/cetak_pajak4";  
			var url5    = "<?php echo site_url(); ?>cetak_pajak/cetak_pajak5";  
			if(((jns==1)||(jns==4)) && (rinci==0) || (rinci==1)){
			window.open(url2+'/'+skpd+'/'+bulan+'/'+ctk+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+jns+'/'+rinci+'/'+spasi, '_blank');
			window.focus();
			}
			else if(((jns==2)||(jns==3)) && (rinci1==4)){
			window.open(url3+'/'+skpd+'/'+bulan+'/'+ctk+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+jns+'/'+rinci+'/'+spasi, '_blank');
			window.focus();
			}
			else if(((jns==0)||(jns==2)||(jns==3)) && (rinci1==5)){
			window.open(url4+'/'+skpd+'/'+bulan+'/'+ctk+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+jns+'/'+rinci+'/'+spasi, '_blank');
			window.focus();
			}
			else if(((jns==0)||(jns==2)||(jns==3)) && (rinci1==6)){
			window.open(url5+'/'+skpd+'/'+bulan+'/'+ctk+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+jns+'/'+rinci+'/'+pasal+'/'+spasi, '_blank');
			window.focus();
			} else{
			window.open(url+'/'+skpd+'/'+bulan+'/'+ctk+'/'+ttd_1+'/'+ctglttd+'/'+ttd_2+'/'+jns+'/'+spasi, '_blank');
			window.focus();
			}
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



<h3>CETAK BUKU PEMBANTU PAJAK</h3>
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
			<td width="20%" height="40" ><B>PILIHAN</B></td><td>
		<select name="jenis" id="jenis" onchange="javascript:validate_jenis();" >
		 <option value=" "> --Pilih--</option>     
		 <option value="0"> Cetak Semua</option> 
		 <option value="2"> Cetak Tanpa LS Barang & Jasa Pajak Pihak Ketiga</option>
		 <option value="3"> Cetak Hanya LS Barang & Jasa Pajak Pihak Ketiga</option>
		 <option value="1"> UP/GU/TU</option>
		 <option value="4"> LS</option>
		</td>
		</tr>
		<tr>
                <td colspan="3">
                 <div id="div1">
                        <table style="width:100%;" border="0">
                            <td width="20%"></td>
                            <td width="79%"><select name="rinci" id="rinci" >
											 <option value="0"> Global</option>     
											 <option value="1"> Rinci</option>
                            </td>
                        </table>
                </div>
                <div id="div2">
                        <table style="width:100%;" border="0">
                            <td width="20%"></td>
                            <td width="79%"><select name="rinci1" id="rinci1" onchange="javascript:validate_pasal();" >
											 <option value="4"> Rincian Penerimaan dan Penyetoran</option>     
											 <option value="5"> Rekapitulasi Penerimaan dan Penyetoran</option>
											 <option value="6"> Per Pasal</option>
                            </td>
                        </table>
                </div>
				<div id="div3">
                        <table style="width:100%;" border="0">
                            <td width="20%"></td>
                            <td width="79%"><input type="text" id="pasal" style="width: 100px;" /> &nbsp;&nbsp;
										<input type="text" id="nm_pasal" readonly="true" style="width: 200px;border:0" /> 
                            </td>
                        </table>
                </div>
                </td>
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
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
							<td width="20%">Spasi</td>
                            <td><input type="number" id="spasi" style="width: 100px;" value="1"/> 
							
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
<!-- 		<tr >
			<td colspan="2">
			<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(0);">Cetak</a>
			<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(1);">Cetak</a>
			</td>
		</tr> -->
		 <tr>
                <td colspan="3" align="center">
                <button  class="button-biru" plain="true" onclick="javascript:cetak(0);return false"><i class="fa fa-print"></i> Layar</button>
                <button  class="button-kuning" plain="true" onclick="cetak(1);return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <?php
                ?> 
                
                </td>                
            </tr>
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>