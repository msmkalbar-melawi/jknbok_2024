
    
  
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
    
    var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
    
    $(function(){
   	    $('#dd').datebox({  
        required:true,
        formatter:$.fn.datebox.defaults.formatter = function(date){
	       var y = date.getFullYear();
	       var m = date.getMonth()+1;
	       var d = date.getDate();
	       return y+'/'+m+'/'+d;
            }
        });
   	});
    
    $(function(){
            $('#dn').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/skpd',  
                    idField:'kd_skpd',                    
                    textField:'nm_skpd',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_skpd',title:'kode',width:60},  
                        {field:'nm_skpd',title:'nama',align:'left',width:80} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_skpd;
                    validate_keg(kode);
                    }   
                });
           }); 

	$(function(){
	   	    var mskpd = document.getElementById('skpd').value;           
            //var murusan = document.getElementById('bank').value;
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_giat',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_kegiatan;
								jns=rowData.jns_kegiatan;
								simpan(rk,oldRek,jns);
							 },
				 onSelect:function(){							
						  oldRek=getSelections(getRowIndex(this));	
                            //alert(oldRek);
						  },
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Rekening',
					 width:30,
					 align:'left',	
					 editor:{type:"combobox",
      		                options:{valueField:'kd_rek5',
									  textField:'kd_rek5',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_giat/'+mskpd,
									  required:true,
									  onSelect:function(){							
						                      oldRek=getSelections(getRowIndex(this));	
                                                //alert(oldRek);
						                  }
									  }
							}
					},                    
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:30,
					 editor:{type:"text"}
					},
                    {field:'jk',
					 title:'Anggaran',
					 width:20,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
                    {field:'nilai',
					 title:'Nilai',
					 width:20,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     }
                     
				]]	
			
			});
  	
		  

		});




		function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_kegiatan);
			}
			return ids.join(':');
		}


		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


        function simpan(baru,lama,jns){		
		var curus = document.getElementById('urusan').value;
		var cskpd = document.getElementById('skpd').value;        
        if (lama==''){ lama=baru}
        //alert(curus+cskpd+baru+lama+jns);
            $(function(){
				$('#dg').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka/psimpan/'+cskpd+'/'+curus+'/'+baru+'/'+lama+'/'+jns,
					 idField:'id',
					 toolbar:"#toolbar",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true"
				});
			});

		}
		
														

        function validate_combo(){
        var cskpd = document.getElementById('skpd').value;
        //alert(cskpd);
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_giat/'+cskpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true"
			});
		});
        }
        
        function hapus(){
				//var cgiat = document.getElementById('giat').value;
				var cskpd = document.getElementById('skpd').value;
				var rek=getSelections();
                //alert(cskpd+rek) 
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus kegiatan '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/ghapus/'+cskpd+'/'+rek,
							 idField:'id',
							 toolbar:"#toolbar",              
							 rownumbers:"true", 
							 fitColumns:"true",
							 singleSelect:"true"
						});
					});
				
				}
				}
		}
        
		
    
	</script>
    
</head>
<body>

<div id="content">  
<?php
//data spp head
$no_spp		='';		
$tgl_spp	='';
$kd_skpd	='';	
$nm_skpd	='';	
$beban		='';
$keperluan	='';
$bln		='';	
$rkan		='';		
$bank		='';		
$npwp		='';		
$rek		='';		
$spd		='';
$kegiatan	='';	
$nm_kegiatan='';	
$count1 ='';
$totp ='';
//echo "<br />";
//print_r($_SESSION["rka"]["nilai"]);
?>
				  


<?php echo form_open('tukd/tambah', array('class' => 'basic')); ?>                
<table border='0' style="font-size:11px" >
 
 <tr>   
   <td width="5%" >SKPD</td>
   <td width="10%" ><?php
								  		$skpd="select kd_skpd,nm_skpd from ms_skpd ";
                                        $pagingquery1 = $skpd; //echo "edit  $pagingquery1<br />";
                                        $res = mysql_query($pagingquery1)or die("pagingquery gagal".mysql_error());
								?>
                                <select name="skpd" id="skpd"  >
                                <option value="">...Pilih SKPD </option>
       							<?php
								        if($res)
                                        {
                                        while ($result = mysql_fetch_row($res)) 
                                            {
  								  ?>
                                       <option value="<?php echo $result[0]; ?>" <?php if($result[0]==$bank){echo "selected";}?>> <?php echo $result[0]." - ".$result[1]; ?> </option>
                                 <?php 
                                            }
                                        }
                                  ?>
            </select></td>  
   <td width="7%" width='10%'>Kebutuhan Bulan</td>
   <td width="41%" width='10%'>
   <select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
   <option value="">...Pilih Kebutuhan Bulan... </option>
   <option value="1" <?php if($bln =='1'){ echo "selected='selected'";} ?> >Januari</option>
   <option value="2"  <?php if($bln=='2') { echo "selected='selected'";} ?> >Februari</option>
   <option value="3"  <?php if($bln=='3') { echo "selected='selected'";} ?> >Maret</option>
   <option value="4"  <?php if($bln=='4') { echo "selected='selected'";} ?> >April</option>
   <option value="5"  <?php if($bln=='5') { echo "selected='selected'";} ?> >Mei</option>
   <option value="6"  <?php if($bln=='6') { echo "selected='selected'";} ?> >Juni</option>
   <option value="7"  <?php if($bln=='7') { echo "selected='selected'";} ?> >Juli</option>
   <option value="8"  <?php if($bln=='8') { echo "selected='selected'";} ?> >Agustus</option>
   <option value="9"  <?php if($bln=='9') { echo "selected='selected'";} ?> >September</option>
   <option value="10"  <?php if($bln=='10') { echo "selected='selected'";} ?> >Oktober</option>
   <option value="11"  <?php if($bln=='11') { echo "selected='selected'";} ?> >November</option>
   <option value="12"  <?php if($bln=='12') { echo "selected='selected'";} ?> >Desember</option>
   </select> </tr>
 <tr>
   <td width='10%'>No SPP</td>
   <td width="20%" ><input type="text" name="no_spp" id="no_spp" />Tanggal<input id="dd" type="text" value="<?php echo $tgl_spp; ?>" readonly="readonly" /></td> 
   <td width='10%'>Keperluan</td>
   <td width='10%'><textarea name="ketentuan" cols="30" rows="2" ><?php echo $keperluan; ?></textarea></td>
 </tr>
 <tr>
   <td width='10%'>No SPP </td>
   <td width='10%'><input type="text" name="no_spp" id="no_spp" 
   value="<?php echo $no_spp; ?>"  />
     <input type="button" name="list22" value="Cari" onclick="PopupCenter('main/penatausahaan/spp/pilih_spp.php?spp=<?php echo "ls";?>', 'myPop1',800,500);" />
     </td>
   <td width='10%'>Atas Beban</td>
   <td width='10%'><select name="jns_beban" id="jns_beban">
   	 <option value="">...Pilih Jenis Beban... </option>	
     <option value="2"  <?php if($beban=='2') { echo "selected='selected'";} ?> >GU</option>
     <option value="3"  <?php if($beban=='3') { echo "selected='selected'";} ?> >TU</option>
     <option value="4"  <?php if($beban=='4') { echo "selected='selected'";} ?> >LS GAJI</option>
     <option value="5"  <?php if($beban=='5') { echo "selected='selected'";} ?> >LS PPKD</option>
     <option value="6"  <?php if($beban=='6') { echo "selected='selected'";} ?> >LS Barang Jasa</option>
   </select></td>
 </tr>
 <tr>
   <td width='10%'>No SPD </td>
   <td width='10%'><input type="text" name="no_spd" id="no_spd" onclick="PopupCenter('main/penatausahaan/spp/pilih_spd.php', 'myPop1',600,500);"
   value="<?php echo $spd; ?>" readonly="readonly" />
     <input type="button" name="list23" value="Pilih" onclick="PopupCenter('main/penatausahaan/spp/pilih_spd.php', 'myPop1',600,500);" /></td>
   <td width='10%'>Kegiatan</td>
   <td width='10%'><input type="text" name="kd_kegiatan" id="kd_kegiatan" onclick="PopupCenter('main/penatausahaan/spp/pilih_kegiatan.php', 'myPop1',600,500);"
   value="<?php echo $kegiatan; ?>" readonly="readonly" /></td>
 </tr>
 <tr>
   <td width='10%'>Rekanan</td>
   <td width='10%'><textarea name="rekanan" cols="30" rows="1" ><?php echo $rkan; ?> </textarea></td>
   <td width='10%'>Bank</td>
   <td width='10%'>
   								<?php
								  		$bank1="select * from ms_bank ";
                                        $pagingquery1 = $bank1; //echo "edit  $pagingquery1<br />";
                                        $res = mysql_query($pagingquery1)or die("pagingquery gagal".mysql_error());
								?>
                                <select name="bank1" id="bank1"  >
                                <option value="">...Pilih Bank </option>
       							<?php
								        if($res)
                                        {
                                        while ($result = mysql_fetch_row($res)) 
                                            {
  								  ?>
                                       <option value="<?php echo $result[0]; ?>" <?php if($result[0]==$bank){echo "selected";}?>> <?php echo $result[0]."-".$result[1]; ?> </option>
                                 <?php 
                                            }
                                        }
                                  ?>
            </select></td>
 </tr>
 <tr>
   <td width='10%'>NPWP</td>
   <td width='10%'><input type="text" name="npwp" id="npwp" value="<?php echo $npwp; ?>" /></td>
   <td width='10%'>Rekening</td>
   <td width='10%'><input type="text" name="rekening" id="rekening"  value="<?php echo $rek; ?>" /></td>
 </tr>
 <tr>
         <td width='10%'></td>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
            <td width='10%'></td>
            <td width='10%'></td>
        </tr>
</table><br />
<?php echo form_close(); ?>  

  
   <table id="dg" title="Pilih Kegiatan Anggaran" style="width:910%;height:300%" >  
        

	</table>    	    
        <button type="button" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
        <button type="button" onclick="javascript:hapus()">HAPUS</button>
       	<button class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
		<button class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">HAPUS</button>
		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">SIMPAN</button>
		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">BATAL</button>
	
	     
 
</div>  	

