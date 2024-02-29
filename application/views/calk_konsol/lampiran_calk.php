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
	  get_tahun();
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
	
    function get_tahun(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
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
		
		function mapping_calk(){
		document.getElementById('load').style.visibility='visible';
		var bln1 = document.getElementById('bulan1').value;
		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1',bln:bln1}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/calk_konsol/proses_mapping",
			success:function(data){
			if (data = 1){
					alert('REKAL CALK SELESAI');
					document.getElementById('load').style.visibility='hidden';
				}
			}
		 });
		});
	}
		
		function cetak(print)
        {
			var dcetak = $('#dcetak').datebox('getValue');         
			var  ttd = $('#ttd').combogrid('getValue');     
            var ttd1   = ttd.split(" ").join("a"); 
			var skpd = document.getElementById('sskpd').value;
			var lampiran = document.getElementById('lampiran').value;
			var jnsctk = document.getElementById('jnsctk').value;
			var judul = document.getElementById('judul').value;
            
			var thn = dcetak.substr(0,4);
			
			if(thn != tahun_anggaran){
				alert("Tahun Pada Tanggal Cetak Tidak Boleh Melebihi Tahun "+tahun_anggaran);
				exit();
			}
            
            if(lampiran=='1'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_kata_pengantar"; 
			}else if(lampiran=='2'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_daftar_isi"; 
			}else if(lampiran=='3'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_pernyataan_tanggung_jawab"; 
			}else if(lampiran=='4'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_ringkasan_lk"; 
			}else if(lampiran=='5'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_Ilra"; 
			}else if(lampiran=='6'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_IIneraca"; 
			}else if(lampiran=='7'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_IIIlo"; 
			}else if(lampiran=='8'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_IVlpe";  
			}else if(lampiran=='9'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babIII_pend_64";
			}else if(lampiran=='10'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_penutup";
			}else if(lampiran=='11'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_pendahuluan";
			}else if(lampiran=='12'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babiv";
			}else if(lampiran=='13'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babIII_belanja_64";
			}else if(lampiran=='14'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babIII_lo";
			}else if(lampiran=='15'){
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babIII_beban";
			}else if(lampiran=='16'){ //copy
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babII";
                jdl = 'Bab II';
			}else if(lampiran=='17'){ //copy
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babIII_neraca";
			}else if(lampiran=='18'){ //copy
				var url    = "<?php echo site_url(); ?>/calk_konsol/cetak_babIII_lpe";
			}
			
			window.open(url+'/'+dcetak+'/'+ttd1+'/'+skpd+'/'+jnsctk+'/'+judul, '_blank');
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

<h3>CETAK CALK</h3>
    <div>
    <p align="right">         
        <table id="sp2d" title="Cetak Buku Besar" style="width:870px;height:300px;" >
		 <!--<tr>
			<td width="20%" height="40" ><B>Rekal s/d Bulan</B></td>
			<td><?php echo $this->rka_model->combo_bulan('bulan1'); ?> </td>
		</tr>
		<tr >
			<td width="20%" height="40" >&nbsp;</td>
			<td width="80%" align="left"> <INPUT TYPE="button" VALUE="Rekal CALK" style="height:40px;width:100px" onclick="mapping_calk()" >			
			</td>
		</tr>-->
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
			<td width="20%" height="40" ><B>Lampiran</B></td>
			<td width="80%">
				<select name="lampiran" id="lampiran" style="height: 27px; width: 190px;">
					 <option value="1">KATA PENGANTAR</option>
					 <option value="2">DAFTAR ISI</option>
					 <option value="3">PERNYATAAN TANGGUNG JAWAB</option>
					 <option value="4">RINGKASAN LAPORAN KEUANGAN</option>
					 <option value="5">I. LRA</option>
					 <option value="7">II. LO</option>
					 <option value="6">III. NERACA</option>
					 <option value="8">IV. LPE</option>
					 <option value="11">BAB I PENDAHULUAN</option>
					 <option value="16">BAB II IKHTISAR PENCAPAIAN KINERJA KEUANGAN</option>
					 <option value="9">BAB III LRA (PENDAPATAN)</option>
					 <option value="13">BAB III LRA (BELANJA)</option>
					 <option value="14">BAB III LO (PENDAPATAN)</option>
					 <option value="15">BAB III LO (BEBAN)</option>
					 <option value="17">BAB III NERACA</option>
					 <option value="18">BAB III LPE</option>
					 <option value="12">BAB IV. PENJELASAN ATAS INFORMASI-INFORMASI NON KEUANGAN</option>
					 <option value="10">BAB V PENUTUP</option>
				 </select>
				 <select name="judul" id="judul" style="height: 27px; width: 120px;">
					 <option value="1">Tanpa Judul</option>
					 <option value="2">Dengan Judul</option>
				 </select>
            </td>
			<td width="80%">
            </td>
		</tr>
		<tr >
			<td width="20%" height="40" ><B>Jenis Cetakkan</B></td>
			<td width="80%"><select name="jnsctk" id="jnsctk" style="height: 27px; width: 190px;">
             <option value="1" >Preview</option>
             <option value="2" >Cetak</option>
            </td>
		</tr>
		<tr >
			<td >&nbsp;</td>
			<td >&nbsp;</td>
		</tr>
		<tr >
			<td colspan="3" align="center">
				<INPUT TYPE="button" VALUE="CETAK" ONCLICK="cetak(0)" style="height:40px;width:100px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!--<INPUT TYPE="button" VALUE="CETAK PDF" ONCLICK="cetak(1)" style="height:40px;width:100px" >-->
			</td>
		</tr>
		<tr >
			<td colspan="3" align="center" style="visibility:hidden" >	<DIV id="load" > <IMG SRC="<?php echo base_url(); ?>assets/images/mapping.gif" WIDTH="270" HEIGHT="40" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table>                      
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>