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

        $('#tgl_1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
        
        $('#tgl_2').datebox({  
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
    //         $('#bulan').combogrid({  
    //        panelWidth:120,
    //        panelHeight:300,  
    //        idField:'bln',  
    //        textField:'nm_bulan',  
    //        mode:'remote',
    //        url:'<?php echo base_url(); ?>index.php/rka/bulan',  
    //        columns:[[ 
    //            {field:'nm_bulan',title:'Nama Bulan',width:700}    
    //        ]] 
    //    });       
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
			var no_sp3b   = document.getElementById('no_sp3b').value;
			//var bulan   =  document.getElementById('bulan').value;
			var ctglttd = $('#tgl_ttd').datebox('getValue');
            
            //var cbulan = document.getElementById('bulan').value;
            var ctgl_1 = $('#tgl_1').datebox('getValue');
            var ctgl_2 = $('#tgl_2').datebox('getValue');
			var  ttd2 = $('#ttd2').combogrid('getValue');
		    ttd2 = ttd2.split(" ").join("123456789");
			
			var url    = "<?php echo site_url(); ?>blud/SP3BBludController/cetak_sp3b_blud";  
			if(ctgl_1==''){
			alert('Pilih Tanggal Periode dulu')
			exit()
			}
            if(ctgl_2==''){
			alert('Pilih Tanggal Periode dulu')
			exit()
			}
            if(no_sp3b==''){
			alert('Isi No SP3B dulu')
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
			
			window.open(`${url}?skpd=${ckdskpd}&tanggalAwal=${ctgl_1}&tanggalAkhir=${ctgl_2}&nosp3b=${no_sp3b}&cetak=${ctk}&ttd=${ttd2}&ctglttd=${ctglttd}`, '_blank');
			
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
                            <td width="20%">PERIODE</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="tgl_1" style="width: 200px;" /> s.d. <input type="text" id="tgl_2" style="width: 200px;" />
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
            <tr>
                <td colspan="3">
                <div id="div_nomor">
                        <table style="width:100%;" border="0">
                            <td width="20%">Nomor SP3B</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="no_sp3b" style="width: 200px;" maxlength="30" /> Contoh : 900/01/RSUD-BLUD/KEU/XII/2023
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
                            <td width="20%">Pengguna Anggaran</td>
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