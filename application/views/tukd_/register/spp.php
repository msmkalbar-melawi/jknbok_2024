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
    
    
    
    

        
    $(function(){ 
     $('#skpd').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/skpd_2',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'kd_skpd',
    		title:'Kode SKPD',
    		width:20},
            {field:'nm_skpd',
    		title:'Nama SKPD',
    		width:80}
        ]],
        onSelect:function(rowIndex,rowData){
          skpd = rowData.kd_skpd;                                                           
        },
        onDblClickRow:function(rowIndex,rowData){
            cetak();   
        }
    });
    }); 

        $(document).ready(function() {
            $("#accordion").accordion();           
            $("#dialog-modal").dialog({            
        });
        });

    function cetakall()
        {
        var url ="<?php echo site_url(); ?>/tukd/cetak_register_spp";       
        window.open(url, '_blank');
        window.focus();
        }
    function cetak()
        {
        var url ="<?php echo site_url(); ?>/tukd/cetak_register_spp";       
        window.open(url+'/'+skpd, '_blank');
        window.focus();
        }
    </script>

</head>
<body>



<div id="content">

<div id="accordion">

<h3><a href="#" id="section1" onclick="javascript:$('#sp2d').edatagrid('reload')">List SKPD</a></h3>
    <div>
    <p align="right">         
       
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetakall();">cetak Keseluruhan</a>               
        
        <table id="skpd" title="List SP2D" style="width:870px;height:450px;" >  
        </table>
                  
        
    </p> 
    </div>


    

   
  
</div>

</div> 


 	
</body>

</html>