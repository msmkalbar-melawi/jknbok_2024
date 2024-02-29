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
 
	var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
    var detIndex=0;
    
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800,
                modal: true,
                autoOpen:false                
            });             
        });    

   
	  	$(function(){
            $('#kdrek').combogrid({  
            panelWidth:200,  
            idField:'kd_rek2',  
            textField:'kd_rek2',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/master/load_rek1',  
            columns:[[  
                {field:'kd_rek2',title:'Kode ',width:20},  
                {field:'nm_rek2',title:'Nama ',width:180}    
            ]],
            onSelect:function(rowIndex,rowData){
                rekening = rowData.kd_rek2;
                $("#nmgiat").attr("value",rowData.nm_rek2);
                validate_rek();
            }  
            }); 
            });
	

        
														

        function validate_rek(){
			//var cgiat = document.getElementById('giat').value;       
            $(function(){
			$('#dg').edatagrid({
				 url: '<?php echo base_url(); ?>/index.php/master/load_rek5/'+rekening,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 showFooter:"true",
                 //pagination:"true",
				 nowrap:false,
				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk1=rowData.kd_rek4;
								rk2=rowData.kd_rek5;
								rk3=rowData.map_lra1;
                                rk4=rowData.map_lo;
                                nama=rowData.nm_rek5;
								simpan(rk1,rk2,rk3,rk4,nama);
							 },
				 onSelect:function(){							
							  oldRek=getSelections(getRowIndex(this));
                             
						  },
				
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
                     {field:'kd_rek4',
					 title:'Rek Objek',
					 width:15,
					 align:'left',	
					 editor:{type:"text"}
					},
					{field:'kd_rek5',
					 title:'Rek Rincian',
					 width:15,
					 align:'left',	
					 editor:{type:"text"}
					},
                    {field:'map_lra1',
					 title:'Rek LRA',
					 width:15,
					 align:'left',	
					 editor:{type:"text"}
					},
                    {field:'map_lo',
					 title:'Rek LO',
					 width:15,
					 align:'left',	
					 editor:{type:"text"}
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:80,
					 editor:{type:"text"}
					}
				]]

			});
		});
		
        }
        
     
        function simpan(rk1,rk2,rk3,rk4,nama){
            var a =rk1;
            var b =rk2;
            var c =rk3;
            var d =rk4;
            var e =nama;
		
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({rek4:a,rek5:b,rek_lra:c,rek_lo:d,nama:e}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/master/simpan_rek5",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
    }
    
		
        
		
	
        
        $(document).ready(function() {
            $("#accordion").accordion();
        });
  

    
</script>


<STYLE TYPE="text/css"> 
input.right{ 
         text-align:right; 
         } 
</STYLE> 


</head>
<body>

<div id="content">
<table style="border-collapse:collapse;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
   
   <tr>
   <td><h3>KELOMPOK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="kdrek" name="kdrek" style="width: 50px;" />  
  &nbsp;&nbsp;&nbsp;&nbsp;<input id="nmgiat" name="nmgiat" style="width: 650px; border:0;  " /> </h3></td>
   </tr>
   </table>
  
<div id="accordion">
<h2><a href="#" id="section1" onclick="javascript:validate_combo()" >Rekening Rincian Objek</a></h2>
   <div  style="height: 400px;">       
        <table id="dg" title="Input Rekening Rencana Kegiatan Anggaran" style="width:880px;height:400px;" >          
        </table>  
        <div id="toolbarx">
    		<button  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Baru</button>
            <!--<button class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</button>
    		<button  class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</button>-->
    		<button  class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">Simpan</button>
    		<button  class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Batal</button>
        </div>
    </div>
    


</div>  	
</body>

</html>