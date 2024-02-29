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
    var kdx='';
     
     $(document).ready(function() {
            $("#accordion").accordion();            
             get_skpd();               
        });   
    
//	$(function(){
//	$('#sskpd').combogrid({ 
//	    
//		panelWidth:630,  
//		idField:'kd_skpd',  
//		textField:'kd_skpd',          
//		mode:'remote',
//        
//		url:'<?php echo base_url(); ?>index.php/akuntansi/config_skpd_2',  
//		columns:[[  
//			{field:'kd_skpd',title:'Kode SKPD',width:100},  
//			{field:'nm_skpd',title:'Nama SKPD',width:500}    
//		]],
//		onSelect:function(rowIndex,rowData){
//			kdskpd = rowData.kd_skpd;                
//			$("#nmskpd").attr("value",rowData.nm_skpd);
//			//$("#skpd").attr("value",rowData.kd_skpd);
//            kode=rowData.kd_skpd;
//           reke(kode);
//		}  
//		}); 
//	});
//	
//    $(function(){
//	$('#srek').combogrid({  	   
//		panelWidth:630,  
//		idField:'kd_rek5',  
//		textField:'kd_rek5',  
//		mode:'remote',        
//		url:"<?php echo base_url(); ?>index.php/tukd/rek5_skpd",  
//		columns:[[  
//			{field:'kd_rek5',title:'Kode Rekening',width:100},  
//			{field:'nm_rek5',title:'Nama Rekening',width:500}    
//		]],
//		onSelect:function(rowIndex,rowData){
//			kdrek5 = rowData.kd_rek5;
//			$("#nmrek5").attr("value",rowData.nm_rek5);
//			$("#rek5").attr("value",rowData.kd_rek5);
//           
//		}  
//		}); 
//	});
    
    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                        kdskpd = data.kd_skpd; 
        								kdskpd = data.kd_skpd;
                                        kode=data.kd_skpd;
                                        reke(kode);
                                               
        							  }                                     
        	});  
        }
    
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    
    
    function reke(kode){
       $(function(){
           $('#srek').combogrid({  	   
    		panelWidth:630,  
    		idField:'kd_rek5',  
    		textField:'kd_rek5',  
    		mode:'remote',        
    		url:"<?php echo base_url(); ?>index.php/tukd/rek5_skpd"+'/'+kode,  
    		columns:[[  
    			{field:'kd_rek5',title:'Kode Rekening',width:100},  
    			{field:'nm_rek5',title:'Nama Rekening',width:500}    
    		]],
    		onSelect:function(rowIndex,rowData){
    			kdrek5 = rowData.kd_rek5;
    			$("#nmrek5").attr("value",rowData.nm_rek5);
    			$("#rek5").attr("value",rowData.kd_rek5);
               
    		}  
    		}); 
  	   });
     
     }
     
    
		function cetak()
        {
			
			var skpd   = kdskpd; 
            var rek5    = kdrek5;
			var bulan   =  document.getElementById('bulan1').value;
			var url    = "<?php echo site_url(); ?>/tukd/cetak_tobjek";  
			window.open(url+'/'+skpd+'/'+bulan+'/'+ rek5 , '_blank');
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



<h3>BUKU PEMBANTU PER RINCIAN OBJEK PENERIMAAN</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Rincian Objek" style="width:922px;height:200px;" >  
		<tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td>
        </tr>
        <tr >
            <td width="20%" height="40" ><B>Rekening</B></td>
			<td width="80%"><input id="srek" name="srek" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmrek5" name="nmrek5" style="width: 500px; border:0;" /></td>
                
		</tr>
        <tr >
			<td width="20%" height="40" ><B>BULAN</B></td>
			<td><?php echo $this->rka_model->combo_bulan('bulan1','onchange="javascript:validate1();"'); ?> </td>
		</tr>
	
		<tr >
			<td colspan="2"><a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak();">Cetak</a></td>
		</tr>
		
        </table>                      
    </p> 
    

</div>
</div>

 	
</body>

</html>