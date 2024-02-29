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
  <style>    
    #tagih {
        position: relative;
        width: 922px;
        height: 100px;
        padding: 0.4em;
    }  
    </style>
    <script type="text/javascript"> 
    var nip='';
	var kdskpd='';
	var kdrek5='';
	var bulan='';
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 100,
                width: 922            
            });             
        });   
    $(function(){
	$('#sskpd').combogrid({  
		panelWidth:630,  
		idField:'kd_skpd',  
		textField:'kd_skpd',  
		mode:'remote',
		url:'<?php echo base_url(); ?>index.php/tukd/skpd_3',  
		columns:[[  
			{field:'kd_skpd',title:'Kode SKPD',width:100},  
			{field:'nm_skpd',title:'Nama SKPD',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kdskpd = rowData.kd_skpd;
			$("#nmskpd").attr("value",rowData.nm_skpd);
			$("#skpd").attr("value",rowData.kd_skpd);
           
		}  
		}); 
	});
	
        
     
    function submit(){
        if (ctk==''){
            alert('Pilih Jenis Cetakan');
            exit();
        }
        document.getElementById("frm_ctk").submit();    
    }
        
    $(function(){
   	     $('#dcetak').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});

	$(function(){   
		 $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		}); 
		
	$(function(){  
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
               $("#nama2").attr("value",rowData.nama);
           } 
            });          
         }); 
	
    $(function(){
   	     $('#dcetak2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
   	});
       
    $(function(){
   	    //$("#status").attr("option",false);
        $("#kode_skpd").hide();
   	});   
     /*   
    function opt(val){        
        ctk = val; 
        if (ctk=='1'){
            $("#tagih").hide();
            $("#dcetak").datebox("setValue",'');
            $("#dcetak2").datebox("setValue",'');
        } else if (ctk=='2'){
           $("#tagih").show();
           } else {
            exit();
        } 
    } 
*/	

	function opt(val){        
        ctk = val; 
        if (ctk=='1'){
			$("#kode_skpd").hide();
        } else if (ctk=='2'){
			$("#kode_skpd").show();
           // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
        } else if(ctk=='3'){
			$("#kode_skpd").hide();
        }  else {
			$("#kode_skpd").hide();
		}        
       // $('#frm_ctk').attr('action',urll);                        
    } 
	
    function cetak($pilih){
			var pilih =$pilih;
			cbulan = $('#bulan').combogrid('getValue');
			var  ttd2 = $('#ttd2').combogrid('getValue');
		    ttd2 = ttd2.split(" ").join("123456789");
			var ctglttd = $('#tgl_ttd').datebox('getValue');
			if(ctk==1){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/ctk_lpe/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else if(ctk==2){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/ctk_lpe_unit/'+cbulan+'/'+kdskpd;
				if (kdskpd==''){
				alert("Pilih Unit dulu");
				exit();
				}if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			} else if(ctk==3){
				urll ='<?php echo base_url(); ?>index.php/akuntansi/ctk_lpe_biro/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}else{
				urll ='<?php echo base_url(); ?>index.php/akuntansi/ctk_lpe_setda/'+cbulan;
				if (bulan==''){
				alert("Pilih Bulan dulu");
				exit();	
				}
			}				
    			//var url    = "<?php echo site_url(); ?>/akuntansi/cetak_lra_lo";	  
    			window.open(urll+'/'+pilih+'/'+ttd2+'/'+ctglttd, '_blank');
    			window.focus();
            
        }
	
	$(function(){ 
		           $('#bulan').combogrid({  
                   panelWidth:120,
                   panelHeight:300,  
                   idField:'bln',  
                   textField:'nm_bulan',  
                   mode:'remote',
                   url:'<?php echo base_url(); ?>index.php/rka/bulan',  
                   columns:[[ 
                       {field:'nm_bulan',title:'Nama Bulan',width:700}    
                   ]],
					onSelect:function(rowIndex,rowData){
						bulan = rowData.nm_bulan;
						$("#bulan").attr("value",rowData.nm_bulan);
					}
               }); 
		  });
    
		function runEffect() {
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        };
        
      function pilih() {
       op = '1';       
      };   
     
        
    
    </script>

    <STYLE TYPE="text/css"> 
		 input.right{ 
         text-align:right; 
         } 
	</STYLE> 

</head>
<body>

<div id="content">



<h3>CETAK LAPORAN PERUBAHAN EKUITAS SKPD</h3>       
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Cetak" style="width:922px;height:200px;" >          
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="1" onclick="opt(this.value)" /><b>&nbsp; SKPD</b></td></tr>
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>&nbsp; Per Unit</b>
                    <div id="kode_skpd">
                        <table style="width:100%;" border="0">
                            <tr >
                    			<td width="22px" height="40%" ><B>Unit&nbsp;&nbsp;</B></td>
                    			<td width="900px"><input id="sskpd" name="sskpd" style="width: 100px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 670px; border:0;" /></td>
                    		</tr>
                        </table> 
                    </div>
        </td>
        </tr> 
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="3" onclick="opt(this.value)" /><b>&nbsp; 3 BIRO (KDH-SETDA-UMUM)</b></td></tr>
        <tr><td width="922px" colspan="2"><input type="radio" name="cetak" value="4" onclick="opt(this.value)" /><b>&nbsp; SETDA (SETDA-KDH)</b></td></tr>
               
        <tr>
                <td colspan="2">
                <div id="div_periode">
                        <table style="width:100%;" border="0">
                            <td width="22px" height="40%"><B>Bulan</B></td>
                            <td width="900px"><input type="text" id="bulan" style="width: 100px;" /> 
                            </td>
                        </table>
                </div>
                </td>
            </tr>
        <tr>
                <td colspan="2">
                <div id="div_bend2">
                        <table style="width:100%;" border="0">
                            <td width="150px" height="40%"><B>Tanggal TTD</B></td>
                            <td width="700px"><input type="text" id="tgl_ttd" style="width: 100px;" />  
                            </td>
                        </table>
                </div>
                </td>
            </tr>
		 <tr>
                <td colspan="2">
                <div id="div_bend2">
                        <table style="width:100%;" border="0">
                            <td width="150px" height="40%"><B>Pengguna Anggaran</B></td>
                            <td width="700px"><input type="text" id="ttd2" style="width: 200px;" />
							<input type="text" id="nama2" style="width: 200px;" /> 
                            </td>
                        </table>
                </div>
                </td>
            </tr>
		
		<tr >
			<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(1);">Cetak</a>
            <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(4);">Cetak PDF</a>
            <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Cetak excel</a>
            <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Cetak word</a></td>
		</tr>
		
        </table>                      
    </p> 
    

</div>

</div>

 	
</body>

</html>