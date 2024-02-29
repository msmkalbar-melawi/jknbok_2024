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


      $(document).ready(function() {
            
        get_skpd();
        });

     $(function() {
           
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });  
        $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama2").attr("value",rowData.nama);
           } 
            });
            $('#bulan').combogrid({  
           panelWidth:120,
           panelHeight:300,  
           idField:'bln',  
           textField:'nm_bulan',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/rka/bulan',  
           columns:[[ 
               {field:'nm_bulan',title:'Nama Bulan',width:700}    
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
                                        $("#skpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);
                                        kode = data.kd_skpd;
                                      
                                        
                                      }                                     
            });
             
        }
        function cetaksp3b(ctk)
        {	
            var ckdskpd = document.getElementById('skpd').value;
			//var skpd   = document.getElementById('skpd').value;
			//var bulan   =  document.getElementById('bulan').value;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
            
            var cbulan = $('#bulan').combogrid('getValue');
			var  ttd2 = $('#ttd2').combogrid('getValue');
		    ttd2 = ttd2.split(" ").join("123456789");
			var atas   =  "15";
			var bawah   =  "15";
			var kanan   =  "15";
			var kiri   =  "15";

			var url    = "<?php echo site_url(); ?>tukd_pusk/cetak_sp3b_blud";  
			if(cbulan==0){
			alert('Pilih Bulan dulu')
			exit()
			}
			if(ctglttd==''){
			alert('Pilih Tanggal tanda tangan dulu')
			exit()
			}			
			if(ttd2==''){
			alert('Pilih Pengguna Anggaran dulu')
			exit()
			}
			//  if(pusk==''){
			//  alert('Pilih FKTP dulu')
			//  exit()
			//  }
			
			window.open(url+'/'+ckdskpd+'/'+cbulan+'/'+ctk+'/'+ttd2+'/'+ctglttd+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan, '_blank');
			window.focus();
        }
    
    </script>

</head>
<body>

<div id="content" align="center"> 
    <h3 align="center"><b>CETAK SP3B</b></h3>
    
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td colspan="3">
                <div id="div_skpd">
                        <table style="width:100%;" border="0">
                            <td width="20%">BLUD </td>
                            <td width="1%">:</td>
                            <td width="79%"><input id="skpd" name="skpd" style="width: 200px;" />&ensp;
                            <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
                            </td>
                        </table>
                </div>
                </td>
            </tr>
           
            <tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">BULAN</td>
                            <td width="1%">:</td>
                            <td><input id="bulan" name="bulan" style="width: 200px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">TANGGAL TTD</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="tgl_ttd" style="width: 200px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			
			<tr>
                <td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">Bend. Pengeluaran</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd2" style="width: 200px;" /> 
							<input type="text" id="ttd2" style="width: 200px;border:0" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			
            
                <tr>
                <td colspan="3" align="center">
                <button  class="button-biru" plain="true" onclick="javascript:cetaksp3b(0);"><i class="fa fa-print"></i> Layar</button>
                <button  class="button-kuning" plain="true" onclick="javascript:cetaksp3b(1);"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <?php
                ?> 
                
                </td>                
            </tr>

            <!-- <tr>
                <td colspan="3" align="center">
                <button  class="button-biru" plain="true" onclick="javascript:openWindow('cetak_bukubank/cetak_simpanan_bank_new/0');return false"><i class="fa fa-print"></i> Layar (Format Baru)</button>
                <button class="button-kuning" plain="true" onclick="javascript:openWindow('cetak_bukubank/cetak_simpanan_bank_new/1');return false"><i class="fa fa-file-pdf-o"></i> PDF (Format Baru)</button>
                <?php
                ?> 
                
                </td>                
            </tr> -->
        </table>  
            
  
</div>	