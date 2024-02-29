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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript">
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 400,
            width: 900,
            modal: true,
            autoOpen:false,
        });
       
      //  get_sclient()
        });    
     
  
    
     
     $(function(){ 
      $('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/rka/skpd',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih'});                 
           }  
       });     
    }); 


    
     function rekal(){
        var siadinda = document.getElementById('siadinda').value;
        var simakda = document.getElementById('simakda').value;
        var nama = document.getElementById('nama').value; 
        var dir = document.getElementById('dir').value;  
        var skpd = $('#skpd').combogrid('getValue');
		if(dir==''){
			alert("Pilih Directory Terlebih Dahulu!");
			exit();
		}
		if(skpd==''){
			alert("Pilih SKPD Terlebih Dahulu!");
			exit();
		}
		if(nama==''){
			alert("Nama tidak boleh Kosong!");
			exit();
		}
		document.getElementById('load').style.visibility='visible';
		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1',dir:dir,simakda:simakda,siadinda:siadinda,nama:nama,skpd:skpd}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/akuntansi/proses_transfer_anggaran",
			success:function(data){
				if (data == '1'){
					alert('Transfer Berhasil!!');
				}else{
					alert('CLIENT LAIN SEDANG MELAKUKAN REKAL');					
				}
				document.getElementById('load').style.visibility='hidden';
			}
		 });
		});
	}  
       

   

    </script>

</head>
<body>

<div id="content">

<div id="accordion">

<h3>TRANSFER - BACKUP</h3>
    <div>
	<center><b> TRANSFER ANGGARAN SIMAKDA -> SIMAKDA SKPD </b></center>
    <p >         
        <table id="sp2d" title="Rekal Transaksi" style="width:870px;height:300px;" > 
		<tr>
                <td><b> &nbsp;&nbsp;&nbsp; Database Simakda : </b> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="simakda"   name="simakda" style="width:250px;" value="simakda_2016" /></td>
				
           </tr><tr>
                <td><b> &nbsp;&nbsp;&nbsp; Database Siadinda : </b> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="siadinda"   name="siadinda" style="width:250px;" value="simakdaskpd_2016" /></td>
				
           </tr>
		   <!--
		   <tr>
				<td><b>&nbsp;&nbsp;&nbsp; Pilih Tabel : </b><select name="dir" id="dir" >
					 <option value="">PILIH Tabel</option>
					 <option value="1">KUA</option>
					 <option value="2">Program</option>
					 <option value="3">Kegiatan</option>
					 <option value="4">Kegiatan Terpilih</option>
					 <option value="5">Rekening</option>
				   </select></td>
				
           </tr>
		   -->
		   <tr><td> &nbsp; </td></tr>
		  <tr>
                 <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SKPD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b>&nbsp;&nbsp;&nbsp;&nbsp;<input id="skpd" name="skpd" style="width: 140px;" /> &nbsp;<input type="text" id="nmskpd" style="width:200px; border:0"/></td>  
            </tr>    
		<tr>
                <td><b> &nbsp;&nbsp;&nbsp; Nama File &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </b>  &nbsp;&nbsp;<input type="text" id="nama"   name="nama" style="width:250px;"/></td>
				
           </tr>
		   <tr>
				<td><b>&nbsp;&nbsp;&nbsp; Pilih Directory &nbsp;&nbsp;&nbsp;&nbsp;: </b><select name="dir" id="dir" >&nbsp;&nbsp;
					 <option value="">PILIH DIRECTORY</option>
					 <option value="C">C:\</option>
					 <option value="D">D:\</option>
					 <option value="E">E:\</option>
					 <option value="F">F:\</option>
					 <option value="G">G:\</option>
					 <option value="H">H:\</option>
				   </select></td>
				
           </tr>  
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="PROSES" style="height:40px;width:100px" onclick="rekal()" >
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