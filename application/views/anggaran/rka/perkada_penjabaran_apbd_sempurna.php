

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
    var ctk = '1';
        
/*      $(document).ready(function() {
            
        get_skpd();
		 
        }); */
    
     $(function(){ 
        
      $("#div_rekap").hide();
            $("#div_skpd").hide();
			
            $("#div_bend").hide();
			
            $("#div_ttd").hide();
			 $("#nm_skpd").attr("value",'');
      

        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        }); 
        
        
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
				skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);
            }  
            });

			$('#org').combogrid({  
            panelWidth:700,  
            idField:'kd_org',  
            textField:'kd_org',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/org_skpd',  
            columns:[[  
                {field:'kd_org',title:'Kode Organisasi',width:100},  
                {field:'nm_org',title:'Nama Organisasi',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
				skpd = rowData.kd_org;
                $("#nmorg").attr("value",rowData.nm_org);
            }  
            }); 			
      
        	$('#ttd1').combogrid({  
    		panelWidth:500,  
    		url: '<?php echo base_url(); ?>/index.php/rka/ttd_gub',  
    			idField:'nip',                    
    			textField:'nama',
    			mode:'remote',  
    			fitColumns:true,  
    			columns:[[  
    				{field:'nip',title:'NIP',width:60},  
    				{field:'nama',title:'NAMA',align:'left',width:100}								
    			]],
    			onSelect:function(rowIndex,rowData){
    			nip = rowData.nip;
    			
    			}   
    		});
        $('#ttd2').combogrid({  
            panelWidth:400,  
            idField:'nip',  
            textField:'nama',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/load_ttd_gub',  
            columns:[[  
                {field:'nip',title:'NIP',width:200},  
                {field:'nama',title:'Nama',width:400}    
            ]]  
        });           

    });        

	   
	 
    
     function cetak(){
        $("#dialog-modal").dialog('close');
     } 
     

     
     function openWindow( url ){
         var ckdskpd = $('#skpd').combogrid('getValue');
         var ckdorg = $('#org').combogrid('getValue');
			rek_fil
		 var  ttd = $('#ttd1').combogrid('getValue');
          var ttd = ttd.split(" ").join("123456789");
          ctglttd = $('#tgl_ttd').datebox('getValue');
         var fil = document.getElementById('rek_fil').value;
        var  ttd_2 = $('#ttd2').combogrid('getValue');
        var ttd2 = ttd_2.split(" ").join("a");

		 //alert(fil);
			 if(ctk==3){
            lc = '/Penjabaran APBD Penyempurnaan - '+ckdskpd+'?kd_skpd='+ckdskpd+'&f_rek='+fil+'&tgl_ttd='+ctglttd+'&ttd='+ttd+'&cpilih=3'+'&ttd2='+ttd2+'';
         } else if(ctk==2){
				lc = '/Penjabaran APBD Penyempurnaan - '+ckdorg+'?kd_skpd='+ckdorg+'&f_rek='+fil+'&tgl_ttd='+ctglttd+'&ttd='+ttd+'&cpilih=2'+'&ttd2='+ttd2+'';
			 } else {
	   
				lc = '/Penjabaran APBD Penyempurnaan?kd_skpd='+''+'&f_rek='+fil+'&tgl_ttd='+ctglttd+'&ttd='+ttd+'&cpilih=1'+'&ttd2='+ttd2+'';
			 }
         window.open(url+lc,'_blank');
         window.focus();
         //window.open(url+'/'+ckdskpd+'/'+ctgl1+'/'+ctgl2+'/'+tglttd, '_blank');
         //'window.focus();
     }  
     
     function opt(val){        
        ctk = val; 
        if (ctk=='1'){
            $("#div_rekap").hide();
            $("#div_skpd").hide();
        } else if (ctk=='2'){
            $("#div_rekap").show();
            $("#div_skpd").hide();
				} else if (ctk=='3'){
				$("#div_rekap").hide();
				$("#div_skpd").show();
				} else {
				exit();
				}                 
    }     

	
	
	function cek($cetak){
	url="<?php echo site_url(); ?>/rka/cetak_pergub_penjabaran_apbd_sempurna/"+$cetak;
	if(ctk==''){
	alert("Pilih Jenis Laporan");
	exit();
		} else if (ctk==2 && $('#org').combogrid('getValue')==''){
		alert("Pilih Nama SKPD Terlebih Dahulu")
			} else if (ctk==3 && $('#skpd').combogrid('getValue')==''){
			alert("Pilih Nama SKPD Terlebih Dahulu")
			}	else {
					openWindow( url );
					}
	}
	
  function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
  
  
   </script>


<div id="content1" align="center"> 
    <h3 align="center"><b>CETAK LAPORAN PENJABARAN APBD PENYEMPURNAAN</b></h3>
    <fieldset style="width: 70%;">
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td>
				<input type="radio" name="cetak" value="1" onclick="opt(this.value)" />Keseluruhan &ensp;
                <input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" />Rekap SKPD
                <input type="radio" name="cetak" value="3" id="status" onclick="opt(this.value)" />Per SKPD
                </td>
                <td>&ensp;</td>
                <td>&nbsp</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp</td>
            </tr>

            <tr>
                <td colspan="3">
                 <div id="div_rekap">
                        <table style="width:100%;" border="0">
                            <td width="20%">REKAP SKPD</td>
                            <td width="1%">:</td>
                            <td width="79%"><input id="org" name="org" style="width: 100px;" />&ensp;
                            <input type="text" id="nmorg" readonly="true" style="width: 400px;border:0" />
                            </td>
                        </table>
                </div>
				
                 <div id="div_skpd">
                        <table style="width:100%;" border="0">
                            <td width="20%">SKPD</td>
                            <td width="1%">:</td>
                            <td width="79%"><input id="skpd" name="skpd" style="width: 100px;" />&ensp;
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
                            <td width="20%">TANGGAL TTD</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
			<tr>
		<td colspan="4">
                <div id="div_ttd">
                        <table style="width:100%;" border="0">
                            <td width="20%">TTD</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd1" style="width: 100px;" /> 
                            </td> 
						
                        </table>
                </div>
        </td> 
		</tr>
		<tr>
		<td colspan="4">
                <div id="div_rek">
                        <table style="width:100%;" border="0">
                            <td width="20%">Rekening  </td>
                            <td width="1%">:</td>
                            <td><input type="text" id="rek_fil" style="width: 100px;" maxlength="10" onkeypress="return isNumberKey(event)" /> 
                            </td> 
						
                        </table>
                </div>
        </td> 
		</tr>
        <tr>
            <td colspan="4">
                <div id="div_rek">
                    <table style="width:100%;" border="0">
                        <tr>
                            <td width="20%" style="border: none;">Penandatanganan</td>
                            <td width="1%" style="border: none;">:</td>
                            <td style="border: none;"><input type="text" id="ttd2" style="width: 200px;" />&nbsp;&nbsp;
                        </tr>
                    </table>
                </div>
            </td> 
        </tr> 
			
                  
            <tr>
                <td colspan="3" align="center">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(0)">Cetak</a>
				<a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cek(1)">Cetak</a>
                </td>                
            </tr>
        </table>  
            
    </fieldset>  
</div>	
