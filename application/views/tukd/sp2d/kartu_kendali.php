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
      //get_skpd();                                                            
    }); 
    
	$(function(){
	//$('#sskpd').combogrid({  
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
//			//validate_giat();
//		}  
//		});

		$('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/LAMA/tukd/skpd_2',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]], 
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd; 
              // bend(kode);               
               $("#nmskpd").attr("value",rowData.nm_skpd);
               //$('#giat').combogrid({url:'<?php echo base_url(); ?>index.php/rka/load_trskpd',
                        //             queryParams:({kode:kode})
                        //             });
			
			validate_kegi(kode);
           }  
         });

                 
        $('#dcetak').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
        
        $('#dcetak2').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
        
        $('#ttd').combogrid({  
		panelWidth:500,  
		url: '<?php echo base_url(); ?>/index.php/LAMA/tukd/list_ttd',  
			idField:'nip',                    
			textField:'nama',
			mode:'remote',  
			fitColumns:true,  
			columns:[[  
				{field:'nip',title:'NIP',width:60},  
				{field:'nama',title:'NAMA',align:'left',width:100}								
			]],
			onSelect:function(rowIndex,rowData){
			nip = rowData.nip;
			
			}   
		});
	});
	
	
	 function validate_kegi(kode){
           $(function(){
		 $('#kg').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/LAMA/tukd/pgiat/'+kode,  			
                    idField:'kd_sub_kegiatan',  
                    textField:'kd_sub_kegiatan',
                    mode:'remote',  
                    fitColumns:true,                       
                    columns:[[  
                        {field:'kd_sub_kegiatan',title:'Kode',width:30},  
                        {field:'nm_sub_kegiatan',title:'Nama',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kegi   = rowData.kd_sub_kegiatan;
                    nmkegi = rowData.nm_sub_kegiatan;
                    $("#nm_kg").attr("value",rowData.nm_sub_kegiatan);

					validate_rek(kegi);
                    }    
                });
                
		       });
        }
    
	 function validate_rek(cgiat){
           $(function(){
        $('#kdrek5').combogrid({  
		panelWidth:630,  
		idField:'kd_rek6',  
		textField:'kd_rek6',  
		mode:'remote',
		url:'<?php echo base_url(); ?>/index.php/LAMA/tukd/ld_rek/'+cgiat,  
		columns:[[  
			{field:'kd_rek6',title:'Kode Rekening',width:100},  
			{field:'nm_rek6',title:'Nama Rekening',width:500}    
		]],
		onSelect:function(rowIndex,rowData){
			rekening = rowData.kd_rek6;
			//$("#kdrek5").attr("value",rowData.kd_rek5);
			$("#nmrek5").attr("value",rowData.nm_rek6);
		}  
		}); 
		
				       });
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
                                        $("#skpd").attr("value",data.kd_skpd);
        								kdskpd = data.kd_skpd;
                                               
        							  }                                     
        	});  
        }



		function cetak_kartu_kendali($cetak)
        {
			var cetak =$cetak;           	
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');      
			var ttd    = nip;                           
            var ttd1 =ttd.split(" ").join("abc"); 
			var skpd   = kode;  	
			var kdkegi   =$('#kg').combogrid('getValue');  		
			var rek5   = rekening; 

			var url    = "<?php echo site_url(); ?>LAMA/tukd/cetak_kartu_kendali"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd+'/'+kdkegi+'/'+rek5+'/'+dcetak2+'/'+cetak);
			window.focus();
        }

		function cetak_kartu_kendali_kegi($cetak)
        {
			var cetak =$cetak;           	
			var dcetak = $('#dcetak').datebox('getValue');      
			var dcetak2 = $('#dcetak2').datebox('getValue');      
			var ttd    = nip;                           
            var ttd1 =ttd.split(" ").join("a"); 
			var skpd   = kode;  	
			var kdkegi   =$('#kg').combogrid('getValue');  		
			var rek5   = rekening; 

			var url    = "<?php echo site_url(); ?>LAMA/tukd/cetak_kartu_kendali_kegi"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd+'/'+kdkegi+'/'+dcetak2+'/'+cetak);
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

<div id="accordion">

<h3>CETAK KARTU KENDALI</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="Cetak KARTU KENDALI" style="width:870px;height:300px;" >  
		<tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
			<td width="80%"><input id="skpd" name="skpd" style="width: 150px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>KEGIATAN</B></td>
			<td width="80%"><input id="kg" name="kg" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_kg" name="nm_kg" style="width: 500px; border:0;" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>REKENING</B></td>
			<td width="80%"><input id="kdrek5" name="kdrek5" style="width: 150px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmrek5" name="nmrek5" style="width: 500px; border:0;" /></td>
		</tr>

		<tr >
			<td width="20%" height="40" ><B>PERIODE</B></td>
			<td width="80%"><input id="dcetak" name="dcetak" type="text"  style="width:155px" />&nbsp;&nbsp;s/d&nbsp;&nbsp;<input id="dcetak2" name="dcetak2" type="text"  style="width:155px" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>PENANDA TANGAN</B></td>
			<td width="80%"><input id="ttd" name="ttd" type="text"  style="width:230px" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>Cetak Per Kegiatan</B></td>
			<td width="80%"> <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_kartu_kendali_kegi(2);">Cetak Layar</a>
<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_kartu_kendali_kegi(1);">Cetak Pdf</a>
			</td>
			
		</tr>
		<tr >
			<td width="20%" height="40" ><B>Cetak Per Rekening</B></td>
			<td width="80%"><INPUT TYPE="button" VALUE="Cetak Layar" ONCLICK="cetak_kartu_kendali(2)" style="height:40px;width:100px" > &nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE="button" VALUE="Cetak PDF" ONCLICK="cetak_kartu_kendali(1)" style="height:40px;width:100px" >
			</td>
			
		</tr>
		<tr >
			<td >&nbsp</td>
			<td >&nbsp</td>
		</tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>