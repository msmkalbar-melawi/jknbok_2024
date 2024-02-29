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

	function mapping(){
		document.getElementById('load').style.visibility='visible';

		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1'}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/akuntansi/proses_mapping",
			success:function(data){
alert('MAPPING SELESAI');
			if (data = 1){
					alert('MAPPING SELESAI');
					document.getElementById('load').style.visibility='hidden';
				}
			}
		 });
		});
	}

    </script>

</head>
<body>

<div id="content">

<div id="accordion">

<h3>MAPPING REALISASI</h3>
    <div>
    <p >         
        <table id="sp2d" title="Mapping Realisasi" style="width:870px;height:300px;" >  
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="PROSES" style="height:40px;width:100px" onclick="mapping()" >
			</td>
		</tr>
		<tr height="70%" >
			<td align="center" style="visibility:hidden" >	<DIV id="load" > <IMG SRC="<?php echo base_url(); ?>assets/images/mapping.gif" WIDTH="270" HEIGHT="40" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>