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
      get_skpd();                                                            
    }); 
    
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
        
        $('#ttd').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/pa',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });        
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
                                        $("#skpd").attr("value",data.kd_skpd);
        								kdskpd = data.kd_skpd;
                                               
        							  }                                     
        	});  
        }

		function kata_pengantar(){
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;

			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_kata_pengantar"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd, '_blank');
			window.focus();
        }
		
		function pernyataan_tanggung_jawab(){
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;

			var url    = "<?php echo site_url(); ?>akuntansi/cetak_pernyataan_tanggung_jawab"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd, '_blank');
			window.focus();
        }
		
		function ringkasan_lk(){
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;

			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_ringkasan_lk"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd, '_blank');
			window.focus();
        }
		
		function daftar_isi()
        {
			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_daftar_isi"; 
			window.open(url, '_blank');
			window.focus();
        }

		function lra(){
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;

			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_Ilra"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd, '_blank');
			window.focus();
        }
		
		function neraca(){
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;

			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_IIneraca"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd, '_blank');
			window.focus();
        }
		
		function lo(){
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;

			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_IIIlo"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd, '_blank');
			window.focus();
        }
		
		function lpe(){
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;

			var url    = "<?php echo site_url(); ?>/akuntansi/cetak_IVlpe"; 
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd, '_blank');
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

<h3>CETAK BUKU BESAR</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:870px;height:300px;" >  
		<tr >
			<td width="20%" height="40" ><B>SKPD</B></td>
			<td width="80%"><input id="sskpd" name="sskpd" readonly="true" style="width: 150px;border: 0;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 500px; border:0;" /></td>
		</tr>

		<tr >
			<td width="20%" height="40" ><B>TANGGAL CETAK</B></td>
			<td width="80%"><input id="dcetak" name="dcetak" type="text"  style="width:155px" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>PENGGUNA ANGGARAN</B></td>
			<td width="80%"><input id="ttd" name="ttd" type="text"  style="width:230px" /></td>
		</tr>
		<tr >
			<td width="20%" height="40" >&nbsp</td>
			<td width="80%"> 
			<INPUT TYPE="button" VALUE="KATA PENGANTAR" ONCLICK="kata_pengantar()" style="height:40px;width:120px" > &nbsp;&nbsp;&nbsp;&nbsp; 
			<INPUT TYPE="button" VALUE="DAFTAR ISI" ONCLICK="daftar_isi()" style="height:40px;width:100px" >&nbsp;&nbsp;&nbsp;&nbsp; 
			<INPUT TYPE="button" VALUE="PERNYATAAN TANGGUNG JAWAB" ONCLICK="pernyataan_tanggung_jawab()" style="height:40px;width:220px" ><br><br> 
			<INPUT TYPE="button" VALUE="RINGKASAN LAPORAN KEUANGAN" ONCLICK="ringkasan_lk()" style="height:40px;width:220px" >
			<INPUT TYPE="button" VALUE="I. LAPORAN REALISASI ANGGARAN (LRA)" ONCLICK="lra()" style="height:40px;width:250px" ><br><br>
			<INPUT TYPE="button" VALUE="II. NERACA" ONCLICK="neraca()" style="height:40px;width:100px" >
			<INPUT TYPE="button" VALUE="III. LO" ONCLICK="lo()" style="height:40px;width:100px">
			<INPUT TYPE="button" VALUE="IV. LPE" ONCLICK="lpe()" style="height:40px;width:100px">
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