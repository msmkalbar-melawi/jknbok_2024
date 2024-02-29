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
    var ctk = '';
        
   
        $(function(){
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
		
		
		
		$('#cunit').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/dpa_penetapan/skpd',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){                            
                cek_ttd();
                    }

            });

				 
		 
		 function cek_ttd(){ 
         var ckdskpd = $('#cunit').combogrid('getValue');
         var ckdskpd1 = ckdskpd.substr(0,17); 
            $('#ttd2').combogrid({  
                panelWidth:400,  
                idField:'nip',  
                textField:'nama',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/dpa_penetapan/load_ttd_set_pa/'+ckdskpd1,
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });          
         }
		
        $(function(){  
	       $('#dg_cunit').edatagrid({
				url           : '<?php echo base_url(); ?>/index.php/dpa_penetapan/skpd_trdrka',
                 idField      : 'id',
                 toolbar      : "#toolbar",              
                 rownumbers   : "true", 
                 fitColumns   : "true",
                 singleSelect : "true",
			 	onSelect:function(rowIndex,rowData){							
    				},
				columns:[[
	                {field:'id',
					 title:'id',
					 width:10,
                     hidden:true
					},
					{field:'kd_skpd',
					 title:'Rekening',
					 width:12,
					 align:'left'	
					}
				]]
			});
		
		});	
		

		});	

	function cek($cetak,$jns,$semua){
         var ckdskpd = $('#cunit').combogrid('getValue');
		 
		 if(ckdskpd==''){
			alert('SKPD Belum dipilih');
			return;
		 }	 
		 
		 var thn = '<?php echo $this->session->userdata("pcThang"); ?>';
         
		 if ($('input[name="chkrinci"]:checked').val()=='1'){
            var crinci = 1;
         } else{
            var crinci = 0;
         }       
         if ($jns == 'cover'){
            url="<?php echo site_url(); ?>preview_cover_dpa_skpd/"+ckdskpd+'/'+$cetak+'/Report Cover DPA-0'
			openWindow( url,$jns );
         }else{
			if($semua == '1'){
				$('#dg_cunit').datagrid('selectAll');
				var rows = $('#dg_cunit').datagrid('getSelections');   
				for(var p=0;p<rows.length;p++){
					ckdskpd  = rows[p].kd_skpd; 
					if ($jns != 'unit'){
						var ckdskpd = ckdskpd.substring(0,17);  
					}
         
					url="<?php echo site_url(); ?>preview_dpa_skpd_penetapan/"+ckdskpd+'/'+$cetak+'/'+crinci+'/DPA-SKPD '+ckdskpd+' Tahun '+thn+'.pdf'
					openWindow( url,$jns );		
				}	             
			}else{
				if ($jns != 'unit'){
					var ckdskpd = ckdskpd.substring(0,17);  
				}

				url="<?php echo site_url(); ?>preview_dpa_skpd_penetapan/"+ckdskpd+'/'+$cetak+'/'+crinci+'/DPA-SKPD '+ckdskpd+' Tahun '+thn
				openWindow( url,$jns );
			}
		 } 
         
        
    }
    
 
 function openWindow( url,$jns ){
            var ckdskpd = $('#cunit').combogrid('getValue');
           var  ctglttd = $('#tgl_ttd').datebox('getValue');
		   var ckdunit = $('#cunit').combogrid('getValue');     
           var  ttd_2 = $('#ttd2').combogrid('getValue');
		   var ttd2 = ttd_2.split(" ").join("a");
           if ($jns != 'all')
           { 
                if (ckdunit=='' ){
                    alert("Kode Unit Tidak Boleh Kosong"); 
                return;
                }
           }
           /*
		   if (ttd=='' || ctglttd=='' ){
		   alert("Penanda tangan 1 atau tanggal Tanda tangan tidak boleh kosong");
		   } else {
            lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd1+'&ttd2='+ttd2+'';
			window.open(url+lc,'_blank');
			window.focus();
			}*/
            
            lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd2+'';
			window.open(url+lc,'_blank');
			window.focus();
		  
     } 
	 
	 function alltrim(kata){
	 //alert(kata);
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c
	 
	 }
   </script>

	<div id="content">        
    	<h1>CETAK DPA REKAPITULASI
       </h1>
        
        <?php echo form_close(); ?>   
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
		<br />
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0"> 
							<td width="20%">Unit</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="cunit" style="width: 100px;" /> 
                            <td><input type="text" id="nmskpd" readonly="true" style="width: 605px;border:0" /></td>
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
                <div id="div_bend">
                        <table style="width:100%;" border="0">							
							<td width="20%">PPKD/ SEKDA</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd2" style="width: 200px;" /> 
                            </td> 
                        </table>
                </div>
        </td> 


		</tr>
	
        <tr>
                
                <td><input type="checkbox" name="chkrinci" id="chkrinci" value="1" checked="checked" /> Cetak Rincian
                </td>
                <td>&ensp;</td>
                <td>&nbsp</td>
            </tr>
        
		<tr>
		
		<div style="display:none">
			<table id="dg_cunit"  style="width:875px;height:370px;"> 
			</table> 
		</div>

        <table class="narrow">


		
        <tr>
           <td width="20%">Cetak SKPD</td>
           <td>
                    <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'unit','0');return false"><i class="fa fa-television"></i> Layar</button>
                    <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'unit','0');return false"><i class="fa fa-file-pdf-o"></i> PDF</button> 
                    <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:cek(3,'unit','1');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
			</td>    
        </tr>
        
        <tr>
           <td width="10%">Cetak Organisasi</td>
            <td>    
                <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'skpd','0');return false"><i class="fa fa-television"></i> Layar</button>
                <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'skpd','0');return false"><i class="fa fa-file-pdf-o"></i> PDF</button> 
                <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:cek(3,'skpd','1');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
			</td>    
        </tr>
 
        <tr>
           <td width="10%">Cetak Cover</td>
            <td>    

                <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'cover','0');return false"><i class="fa fa-television"></i> Layar</button>
                <button type="pdf" class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'cover','0');return false"><i class="fa fa-file-pdf-o"></i> PDF</button> 
                <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:cek(3,'cover','1');return false"><i class="fa fa-file-pdf-o"></i> PDF</button>
           </td>    
        </tr>
 
        </table>        
        <div class="clear"></div>
	</div>