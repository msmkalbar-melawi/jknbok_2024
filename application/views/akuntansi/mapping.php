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
			if (data = 1){
					alert('POSTING JURNAL TUKD DAN ASET SELESAI');
					document.getElementById('load').style.visibility='hidden';
				}
			}
		 });
		});
	}

	function transfer_lra(){
		document.getElementById('load').style.visibility='visible';

		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1'}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/akuntansi/transfer_lk_lra",
			success:function(data){
			if (data = 1){
					alert('TRANSFER JURNAL LRA KE LKPD SELESAI');
					document.getElementById('load').style.visibility='hidden';
				}
			}
		 });
		});
	}
	
	function transfer_loraca(){
		document.getElementById('load').style.visibility='visible';

		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1'}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/akuntansi/transfer_lk_loraca",
			success:function(data){
			if (data = 1){
					alert('TRANSFER JURNAL LO/NERACA KE LKPD SELESAI');
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

<h3>REKAL JURNAL</h3>
<h3>( " Mohon Maaf untuk mempercepat proses kerja server, 
Menu ini dimatikan jika ingin merekal transaksi untuk jurnal dan LK, Mohon kontak ke verifikator untuk direkalkan dari server. Terima Kasih " )</h3>
    <div>
    <!--<p >         
        <table id="jurnal" title="Rekal Jurnal" style="width:870px;height:300px;" >  
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="POSTING JURNAL TUKD DAN ASET" style="height:40px;width:300px" onclick="mapping()" >
			</td>
		</tr>
		
		<tr>
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="TRANSFER JURNAL LRA KE LKPD" style="height:40px;width:300px" onclick="transfer_lra()" >
			</td>
		</tr>
		
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="TRANSFER JURNAL LO/NERACA KE LKPD" style="height:40px;width:300px" onclick="transfer_loraca()" >
			</td>
		</tr>
		<tr height="70%" >
			<td align="center" style="visibility:hidden" >	<DIV id="load" > <IMG SRC="<?php echo base_url(); ?>assets/images/mapping.gif" WIDTH="270" HEIGHT="40" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table>                      
    </p>-->
    </div>
</div>
</div>

 	
</body>

</html>