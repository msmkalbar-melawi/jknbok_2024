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
            $( "#dialog-modal" ).dialog({
                height: 100,
                width: 100            
            });   
            get_skpd();     
            cekskpd();     
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
    
	/*$(function(){
	$('#sskpd').combogrid({ 
	    
		panelWidth:630,  
		idField:'kd_skpd',  
		textField:'kd_skpd',          
		mode:'remote',
        
		//url:'<?php echo base_url(); ?>rka/config_skpd_ms',  
		columns:[[  
			{field:'kd_skpd',title:'Kode SKPD',width:100},  
			{field:'nm_skpd',title:'Nama SKPD',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			kdskpd = rowData.kd_skpd;                
			$("#nmskpd").attr("value",rowData.nm_skpd);
			$("#skpd").attr("value",rowData.kd_skpd);
            kode=rowData.kd_skpd;
            reke(kode);
		}  
		}); 
	});*/
	

	$(function(){
	$('#srek').combogrid({  	   
		panelWidth:630,  
		idField:'kd_rek5',  
		textField:'kd_rek5',  
		mode:'remote',        
		url:"<?php echo base_url(); ?>index.php/tukd/rek5_skpd",  
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
    

    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>rka/config_skpd_2',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								//$("#sskpd").combogrid("setvalue",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
                                        $("#skpd").attr("value",data.kd_skpd);
        								kdskpd=rowData.kd_skpd;
            							
        							  }                                     
        	});  
        }
		 $(function(){  
            $('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'id_ttd',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/pa',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });          
         });
		 
		 $(function(){  
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'id_ttd',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/bp',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });          
         });

    function validate1(){
        var bln1 = document.getElementById('bulan1').value;        
    }
    
    function cekskpd(){
			$('#sskpd2').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>tukd/skpd__pend',
            //queryParams: ({kdskpd:kdskpd2}),
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}
             ]],       
            onSelect:function(rowIndex,rowData){
				kdskpd = rowData.kd_skpd;
                $("#sskpd2").attr("value",rowData.kd_skpd);
                $("#nmskpd").attr("value",rowData.nm_skpd);
                //ttd1();
                //ttd2();
                reke(kdskpd); 
            }
            });
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
     
    
		function cetak(ctk)
        {
			var tgl_ttd= $('#tgl_ttd').datebox('getValue');
			var skpd   = kdskpd; 
            var rek5    = kdrek5;
			var bulan   =  document.getElementById('bulan1').value;
			
			if(rek5==''){
				alert("Pilih rek  terlebih dahulu");
			}
			else if(bulan==''){
				alert("pilih Bulan terlebih dahulu!");
			}
			else {
				var url    = "<?php echo site_url(); ?>index.php/tukd/cetak_tobjek"; 
			window.open(url+'/'+skpd+'/'+bulan+'/'+ rek5 +'/'+ ctk+'/'+tgl_ttd, '_blank');
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


<h3>BUKU PEMBANTU PER RINCIAN OBJEK PENERIMAAN</h3>

    
    <p align="right">         
        <table id="sp2d" title="Rincian Objek" style="width:100%;height:200px;" >  
        <tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
  			<td width="80%"> <input id="sskpd2" name="sskpd2" style="width:150px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 450px; border:0;" /></td>
		</tr>	
		<tr >
            <td width="20%" height="40" ><B>Rekening</B></td>
			<td width="80%"><input id="srek" name="srek" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmrek5" name="nmrek5" style="width: 500px; border:0;" /></td>
                
		</tr>
        <tr >
			<td width="20%" height="40" ><B>BULAN</B></td>
			<td ><?php echo $this->rka_model->combo_bulan('bulan1','onchange="javascript:validate1();"'); ?> </td>
		</tr>
        <tr >
            <td width="21%" height="40" ><B>Tanggal TTD</B></td>
            <td> <input type="text" id="tgl_ttd"> </td>
        </tr>
		<td colspan="4">
                        <table style="width:100%;" border="0">
                            <tr>
                            <td width="18%">Pengguna Anggaran</td>
                            <td width="1%"></td>
                            <td><input type="text" id="ttd1" style="width: 180px;" /> 
                            </td> 
                            </tr>
							<tr>
                            <td width="20%">Bendahara Penerimaan</td>
                            <td width="1%"></td>
                            <td><input type="text" id="ttd2" style="width: 180px;" /> 
                            </td>
        </tr>
       
                   
		<tr>
            <td colspan="6" align = "center"><a class="button-biru" onclick="javascript:cetak(2);"></i> Cetak Layar</a>
            <a class="button-kuning" onclick="javascript:cetak(0);"> <i class="fa fa-pdf"></i> Cetak PDF</a>
			<a class="button" onclick="javascript:cetak(1);"> <i class="fa fa-excel"></i> Cetak Excel</a></td>
		</tr>   
        </table>
        </table>                 


</div>