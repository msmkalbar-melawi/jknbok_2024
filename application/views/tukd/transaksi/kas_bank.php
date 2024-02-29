

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
   
     $(function(){ 
        
      
      
       $('#tgl1').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });  
        
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });         
      
    
			$('#ttd1').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/cetak_bukubank/load_ttd/BK',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd1").attr("value",rowData.nama);
                    }  
				}); 
			
			$('#ttd2').combogrid({  
					panelWidth:600,  
					idField:'nip',  
					textField:'nip',  
					mode:'remote',
					url:'<?php echo base_url(); ?>index.php/cetak_bukubank/load_ttd/PA',  
					columns:[[  
						{field:'nip',title:'NIP',width:200},  
						{field:'nama',title:'Nama',width:400}    
					]],
                    onSelect:function(rowIndex,rowData){
                    $("#nmttd2").attr("value",rowData.nama);
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
    
   
     
     function openWindow( url ){
		var spasi  = document.getElementById('spasi').value; 
        ctglttd = $('#tgl_ttd').datebox('getValue');
        ctgl1 =  $('#bulan').combogrid('getValue');
		var ttd1   = $("#ttd1").combogrid('getValue');
		var ttd2   = $("#ttd2").combogrid('getValue'); 
		if(ctglttd==''){
			alert('Tanggal tidak boleh kosong!');
			exit();
		}
		if(ctgl1==''){
			alert('Bulan tidak boleh kosong!');
			exit();
		}
		if(ttd1==''){
			alert('Bendahara Pengeluaran tidak boleh kosong!');
			exit();
		}
		if(ttd2==''){
			alert('Pengguna Anggaran tidak boleh kosong!');
			exit();
		}
		var ttd_1 =ttd1.split(" ").join("123456789");
        var ttd_2 =ttd2.split(" ").join("123456789");
		lc = '?tgl1='+ctgl1+'&tgl_ttd='+ctglttd+'&ttd1='+ttd_1+'&ttd2='+ttd_2+'&spasi='+spasi;
        window.open(url+lc,'_blank');
        window.focus();
         
     }  
     
    
    
    
  
   </script>


<div id="content" align="center"> 
    <h3 align="center"><b>BUKU PEMBANTU BANK</b></h3>
    
     <table align="center" style="width:100%;" border="0">
            
           
            <tr>
                <td colspan="3">
                
                <div id="div_periode">
                        <table style="width:100%;" border="0">
                            <td width="20%">PERIODE</td>
                            <td width="1%">:</td>
                            <td width="79%"><input type="text" id="bulan" style="width: 200px;" /> 
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
                            <td><input type="text" id="ttd1" style="width: 200px;" /> 
							<input type="text" id="nmttd1" style="width: 200px;border:0" /> 
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
							<input type="text" id="nmttd2" style="width: 200px;border:0" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			<tr>
				 <td colspan="3">
				 <div id="div_bend">
					<table style="width:100%;" border="0">
						<td width="20%">Spasi</td>
						<td width="1%">:</td>
						<td><input type="number" id="spasi" style="width: 200px;" value="1"/></td>                       
					</table>
				</td>
			</tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            
                <tr>
                <td colspan="3" align="center">
                <button  class="button-biru" plain="true" onclick="javascript:openWindow('cetak_bukubank/cetak_simpanan_bank/0');return false"><i class="fa fa-print"></i> Layar</button>
                <button  class="button-kuning" plain="true" onclick="javascript:openWindow('cetak_bukubank/cetak_simpanan_bank/1');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
                <?php
                ?> 
                
                </td>                
            </tr>

            <tr>
                <td colspan="3" align="center">
                <button  class="button-biru" plain="true" onclick="javascript:openWindow('cetak_bukubank/cetak_simpanan_bank_new/0');return false"><i class="fa fa-print"></i> Layar (Format Baru)</button>
                <button class="button-kuning" plain="true" onclick="javascript:openWindow('cetak_bukubank/cetak_simpanan_bank_new/1');return false"><i class="fa fa-file-pdf-o"></i> PDF (Format Baru)</button>
                <?php
                ?> 
                
                </td>                
            </tr>
        </table>  
            
  
</div>	
