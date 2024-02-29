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
    
    var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var rek=0;
    
    //$(function(){
//   	    $('#dd').datebox({  
//        required:true,
//        formatter:$.fn.datebox.defaults.formatter = function(date){
//	       var y = date.getFullYear();
//	       var m = date.getMonth()+1;
//	       var d = date.getDate();
//	       return y+'/'+m+'/'+d;
//            },
//        onSelect: function(date){
//		tgl=date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
//	}
//        });
//   	});
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
                    validate_spd(kode);
                    }   
                });
           });           
              
            $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1',  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:30},  
                        {field:'tgl_spd',title:'Tanggal',align:'left',width:70}                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    spd = rowData.no_spd;                    
                                        
                    }    
                });
           });
           
    
        $(function(){
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                 autoRowHeight:"true",
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 singleSelect:"true",
                                  
			});
		}); 
             
   
        
        function validate_spd(kode){
           $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd1/'+kode,  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
    
   
        
        function detail1(){
        $(function(){
	   	    var spp = document.getElementById('no_spp').value;                
			$('#dg1').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/tukd/select_data1',
                queryParams:({spp:spp}),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"true",
                 singleSelect:false,
                 onAfterEdit:function(rowIndex, rowData, changes){								
								kegiatan=rowData.kdkegiatan;
								rekeing=rowData.kdrek5;
                                nilai=rowData.nilai1;
								dsimpan(kegiatan,rekeing,nilai);
							 },                			 				 
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},                     
                     {field:'kdkegiatan',
					 title:'Kegiatan',
					 width:150,
					 align:'left',                     	
					 editor:{type:"text"}
					},
					{field:'kdrek5',
					 title:'Rekening',
					 width:70,
					 align:'left',                     	
					 editor:{type:"text"}
					},
					{field:'nmrek5',
					 title:'Nama Rekening',
					 width:430,
					 editor:{type:"text"}
					},
                    {field:'nilai1',
					 title:'Nilai',
					 width:100,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     }
				]]	
			
			});
  	

		});
        }



		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


        function simpan(giat,reke){		
		var spp = document.getElementById('no_spp').value;
		var cskpd =kode;
        var cspd = spd;
        $(function(){      
            $.ajax({
            type: 'POST',
            data: ({cskpd:cskpd,cspd:spd,cspp:spp,cgiat:giat,crek:reke}),
            dataType:"json",
            url:'<?php echo base_url(); ?>/index.php/tukd/tsimpan'
         });
        });
		}       
		
       
        $(document).ready(function() {
            $("#accordion").accordion();
        });
        
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
     }
     
     function section3(){
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
         $('#dg1').edatagrid('reload');
     } 
     
     function hsimpan(){
        var a = document.getElementById('no_spp').value;       
        var c = document.getElementById('jns_beban').value; 
        var d = document.getElementById('kebutuhan_bulan').value;
        var e = document.getElementById('ketentuan').value;
        var f = document.getElementById('rekanan').value;
        var g = document.getElementById('bank1').value;
        var h = document.getElementById('npwp').value;
        var i = document.getElementById('rekening').value;
        var j = document.getElementById('dd').value; 
  
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cskpd:kode,no_spp:a,cspd:spd,keperluan:e,bulan:d,jns_spp:c,rekanan:f,bank1:g,npwp:h,rekening:i,tgl_spp:j}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/simpan",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
    }
    
    function dsimpan(kegiatan,rekening,nilai){
        var a = document.getElementById('no_spp').value;       
        //alert(a+kode+kegiatan+rekening+nilai);
        
        $(function(){      
         $.ajax({
            type: 'POST',
            data: ({cno_spp:a,cskpd:kode,cgiat:kegiatan,crek:rekening,nilai:nilai}),
            dataType:"json",
            url:"<?php echo base_url(); ?>index.php/tukd/dsimpan",
            success:function(data){
                if (data = 1){
                    alert('Data Berhasil Tersimpan');
                }else{
                    alert('Data Gagal Berhasil Tersimpan');
                }
            }
         });
        });
    } 
    
    function hapus(){				
                var spp = document.getElementById("no_spp").value;                
                var nospp =spp.split("/").join("123456789");
				var giat=getSelections();
                var rek=getSelections1();
                alert(spp+giat+rek+nospp);
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus rekening '+rek+' kegiatan'+giat+ ' ?');
				if  (del==true){
					$(function(){
						$('#dg1').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/tukd/thapus/'+nospp+'/'+giat+'/'+rek,
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
        
        function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdkegiatan);
			}
			return ids.join(':');
		}
        
        function getSelections1(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kdrek5);
			}
			return ids.join(':');
		}
                        
    </script>

</head>
<body>



<div id="content">

  
   
   
<div id="accordion">
<h3><a href="#" id="section1">Input SPP</a></h3>
   <div  style="height: 350px;">
   <p>
  <!-- <?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> -->
               
<table border='0' style="font-size:11px" >
 
 <tr>   
   <td width="8%" >SKPD</td>
   <td><input id="dn" name="dn" value="<?php echo $spp->kd_skpd; ?>" style="width:320px"  /></td>
   <td>Bulan</td>
   <td>
   <select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
   <option value="">...Pilih Kebutuhan Bulan... </option>
   <option value="1" <?php if("1"=="$spp->bulan"){echo "selected";}?>>1 | Januari</option>
   <option value="2" <?php if("2"=="$spp->bulan"){echo "selected";}?>>2 | Februari</option>
   <option value="3" <?php if("3"=="$spp->bulan"){echo "selected";}?>>3 | Maret</option>
   <option value="4" <?php if("4"=="$spp->bulan"){echo "selected";}?>>4 | April</option>
   <option value="5" <?php if("5"=="$spp->bulan"){echo "selected";}?>>5 | Mei</option>
   <option value="6" <?php if("6"=="$spp->bulan"){echo "selected";}?>>6 | Juni</option>
   <option value="7" <?php if("7"=="$spp->bulan"){echo "selected";}?>>7 | Juli</option>
   <option value="8" <?php if("8"=="$spp->bulan"){echo "selected";}?>>8 | Agustus</option>
   <option value="9" <?php if("9"=="$spp->bulan"){echo "selected";}?>>9 | September</option>
   <option value="10" <?php if("10"=="$spp->bulan"){echo "selected";}?>>10 | Oktober</option>
   <option value="11" <?php if("11"=="$spp->bulan"){echo "selected";}?>>11 | November</option>
   <option value="12" <?php if("12"=="$spp->bulan"){echo "selected";}?>>12 | Desember</option>
   </select></td>   
    </tr>
 <tr>
   <td width='8%'>No SPP</td>
   <td width="53%" ><input type="text" name="no_spp" id="no_spp" value="<?php echo $spp->no_spp; ?>" />     
      Tanggal 
      <input id="dd" name="dd" type="text" value="<?php echo $spp->tgl_spp; ?>" /></td> 
   <td width='8%'>Keperluan</td>
   <td width="31%" ><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" ><?php echo $spp->keperluan; ?></textarea></td> 
   
 </tr>
 <tr>
   <td width='8%'>No SPD </td>
   <td width='53%'><input id="sp" name="sp" style="width:150px" value="<?php echo $spp->no_spd; ?>" /></td>
   <td width='8%'>Rekanan</td>
   <td width='31%'><textarea id="rekanan" name="rekanan" cols="30" rows="1" > <?php echo $spp->nmrekan; ?> </textarea></td>
 </tr>
 <tr>
   <td width='8%'>Beban</td>
   <td><select name="jns_beban" id="jns_beban">
     <option value="">...Pilih Jenis Beban... </option>
     <option value="2" <?php if("2"=="$spp->jns_spp"){echo "selected";}?>>GU</option>
     <option value="3" <?php if("3"=="$spp->jns_spp"){echo "selected";}?>>TU</option>
     <option value="4" <?php if("4"=="$spp->jns_spp"){echo "selected";}?>>LS GAJI</option>
     <option value="5" <?php if("5"=="$spp->jns_spp"){echo "selected";}?>>LS PPKD</option>
     <option value="6" <?php if("6"=="$spp->jns_spp"){echo "selected";}?>>LS Barang Jasa</option>
   </select></td>
   <td width='8%'>Bank</td>
   <td><?php
		$bank1="select * from ms_bank ";
        $pagingquery1 = $bank1; //echo "edit  $pagingquery1<br />";
        $res = mysql_query($pagingquery1)or die("pagingquery gagal".mysql_error());
	   ?>
       <select name="bank1" id="bank1" style="height: 27px; width: 100px;">
       <option value="">...Pilih Bank </option>
       <?php
		 if($res){
            while ($result = mysql_fetch_row($res)) 
         {
       ?>
       <option value="<?php echo $result[0]; ?>" <?php if($result[0]==$spp->bank){echo "selected";}?>> <?php echo $result[0]."-".$result[1]; ?> </option>
       <?php 
         }
         }
         ?>
     </select></td>
 </tr>
 
 <tr>
   <td width='8%'>NPWP</td>
   <td width='53%'><input type="text" name="npwp" id="npwp" value="<?php echo $spp->npwp; ?>" /></td>
   <td width='8%'>Rekening</td>
   <td width='31%'><input type="text" name="rekening" id="rekening"  value="<?php echo $spp->no_rek; ?>" /></td>
 </tr>

        
         <!--<tr>
         <td width='8%'></td>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
            <td width='8%'></td>
            <td width='31%'></td>
        </tr>-->
        
    </table>
    <!-- <?php echo form_close(); ?> -->
    <input type="submit" name="submit1" value="Input Detail" onclick="detail1();javascript:section3();"/>
    <input type="submit" name="submit2" value="SIMPAN" onclick="javascript:hsimpan()"/>
    <button onClick="window.location='<?php echo site_url(); ?>/tukd/tambah_spp'" style=" width: 70px; heigth: 40px;">BARU</button>    
    <button onClick="window.location='<?php echo site_url(); ?>/tukd/spp'" style=" width: 70px; heigth: 40px;">kembali</button>
    <button onClick="window.location='<?php echo site_url(); ?>/tukd/cetak_spp'" style=" width: 70px; heigth: 40px;">CETAK</button>   
   </p>
    </div>
    

<h3><a href="#" id="section3" onclick="javascript:$('#dg1').edatagrid('reload')" >Input Detail SPP</a></h3>
    <div>
    <p> 
        
       <table id="dg1" title="Input Detail SPP" style="width:850%;height:350%;" >  
        
        </table>  
        
  		<button  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Baru</button>
   		<button  class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</button>
   		<button  class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">Simpan</button>
   		<button  class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Batal</button>
     
    </p> 
    </div>    

</div>

</div>  	
</body>

</html>