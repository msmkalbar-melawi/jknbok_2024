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
 
	var kdskpd="";
	var kegiatan="";
    
    $(document).ready(function(){
        $('#skpd').hide();
        $('#giat').hide();
        get_skpd();
    });
    
	$(function(){
	   	    var mgiat = document.getElementById('giat').value;
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_real',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",

				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Kode Rekening',
					 width:20,
					 align:'left'
				    },
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:60,
					 align:'left'
					},
                    {field:'nilai_ubah',
					 title:'Anggaran',
					 width:20,
                     align:'right'
                     },
					 {field:'real',
					  title:'Realisasi',
					  width:20,
					  align:'center'	 		 
					 },
					 {field:'sisa',
					  title:'Sisa Anggaran',
					  width:20,
					  align:'center'	 		 
					 },
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek=rec.kd_rek5
							return ' <p onclick="javascript:section('+rec.kd_rek5+');">Rincian</p>';
						}
			 		}
				]]	
			
			});
  	
      //     $(function(){
//            $('#sskpd').combogrid({  
//            panelWidth:700,  
//            idField:'kd_skpd',  
//            textField:'kd_skpd',  
//            mode:'remote',
//            url:'<?php echo base_url(); ?>index.php/rka/skpd',  
//            columns:[[  
//                {field:'kd_skpd',title:'Kode SKPD',width:100},  
//                {field:'nm_skpd',title:'Nama SKPD',width:700}    
//            ]],
//            onSelect:function(rowIndex,rowData){
//                kdskpd = rowData.kd_skpd;
//                $("#nmskpd").attr("value",rowData.nm_skpd);
//                $("#skpd").attr("value",rowData.kd_skpd);
//                validate_giat();
//            }  
//            }); 
//            });
		});
        
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
                                        validate_giat();              
        							  }                                     
        	});  
        }

        function validate_giat(){
		  $(function(){
            $('#kdgiat').combogrid({  
            panelWidth:700,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/pgiat/'+kdskpd,  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode SKPD',width:150},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:650}    
            ]],
            onSelect:function(rowIndex,rowData){
                kegiatan = rowData.kd_kegiatan;
                $("#nmgiat").attr("value",rowData.nm_kegiatan);
                $("#giat").attr("value",rowData.kd_kegiatan);
                validate_combo();
            }  
            }); 
            });
		}

														

        function validate_combo(){
			var cgiat = document.getElementById('giat').value;       
			var cskpd = document.getElementById('skpd').value;       
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_real/'+cgiat+'/'+cskpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 showFooter:true,
				 nowrap:false,

				 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Kode Rekening',
					 width:20,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:60,
					 align:'left'
					},
                    {field:'nilai_ubah',
					 title:'Anggaran',
					 width:20,
                     align:'right'
                     },
					 {field:'real',
					  title:'Realisasi SP2D',
					  width:20,
                      align:'right'
			 		 },
					 {field:'sisa',
					  title:'Sisa Anggaran',
					  width:20,
                      align:'right'
			 		 },
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek=rec.kd_rek5
							return ' <p onclick="javascript:cetak('+rec.kd_rek5+');">Cetak</p>';
						}
			 		}
				]]

			});
		});

		
        }
        
    		  
        function cetak(kdrek){
			var url    = "<?php echo site_url(); ?>/tukd/ctk_manreal/"+kdskpd+"/"+kegiatan+"/"+kdrek;
			window.open(url, '_blank');
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

   <?php echo $prev; ?><br />
   <table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
   <tr>
   <td height="30" ><h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="sskpd" name="sskpd" readonly="true" style="width: 150px; border:0;" />
   <input id="nmskpd" name="nmskpd" readonly="true" style="width: 650px; border:0;  " /></h3></td>
   </tr>
   <tr>
   <td><h3>KEGIATAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="kdgiat" name="kdgiat" style="width: 150px;" />  
  <input id="nmgiat" name="nmgiat" style="width: 650px; border:0;  " /> </h3></td>
   </tr>
   </table>
   <div  style="height: 350px;">
        <table id="dg" title="Realisasi Rekening Anggaran" style="width:880px;height:300px;" >          
        </table>  
    </div>
    
</div>




</div>  	
</body>

</html>