	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
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
            $('#gt').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/kegiatan',  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_kegiatan',title:'kode',width:30},  
                        {field:'nm_kegiatan',title:'nama',align:'left',width:70} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    kode1 = rowData.kd_kegiatan;                    
                    validate_spd(kode1);
                    }   
                });
           });
           
            $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd',  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_spd',title:'No SPD',width:30},  
                        {field:'kd_kegiatan',title:'Kegiatan',align:'left',width:70}                          
                    ]]  
                });
           }); 
           
    function validate_keg(kode){
           $(function(){
            $('#gt').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/kegiatan/'+kode,  
                    idField:'kd_kegiatan',  
                    textField:'kd_kegiatan',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
        
    function validate_spd(kode1){
           $(function(){
            $('#sp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/spd/'+kode1,  
                    idField:'no_spd',  
                    textField:'no_spd',
                    mode:'remote',  
                    fitColumns:true
                });
           });
        }
        
        
    </script> 

	<title>Untitled 1</title>
</head>

<body>
<div id="content">
	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/tukd/spp"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

<?php echo form_open('tukd/simpan', array('class' => 'basic')); ?> 
               
<table border='0' style="font-size:11px" >
 
 <tr>   
   <td width="5%" >SKPD</td>
   <td><input id="dn" name="dn" style="width:150px" /></td>
   <td width="7%" width='10%'>Kebutuhan Bulan</td>
   <td width="41%" width='10%'>
   <select  name="kebutuhan_bulan" id="kebutuhan_bulan" >
   <option value="">...Pilih Kebutuhan Bulan... </option>
   <option value="1" >1 | Januari</option>
   <option value="2">2 | Februari</option>
   <option value="3">3 | Maret</option>
   <option value="4">4 | April</option>
   <option value="5">5 | Mei</option>
   <option value="6">6 | Juni</option>
   <option value="7">7 | Juli</option>
   <option value="8">8 | Agustus</option>
   <option value="9">9 | September</option>
   <option value="10">10 | Oktober</option>
   <option value="11">11 | November</option>
   <option value="12">12 | Desember</option>
   </select></td>   
    </tr>
 <tr>
   <td width='10%'>No SPP</td>
   <td width="20%" ><input type="text" name="no_spp" id="no_spp" /></td> 
   <td width='10%'>Tanggal</td>
   <td width="20%" ><input id="dd" name="dd" type="text" value="" readonly="readonly" /></td> 
   
 </tr>
 <tr>
   <td width='10%'>Keperluan</td>
   <td width='10%'><textarea name="ketentuan" id="ketentuan" cols="30" rows="2" ></textarea></td>
   <td width='10%'>Atas Beban</td>
   <td width='10%'><select name="jns_beban" id="jns_beban">
   	 <option value="">...Pilih Jenis Beban... </option>	
     <option value="2">GU</option>
     <option value="3">TU</option>
     <option value="4">LS GAJI</option>
     <option value="5">LS PPKD</option>
     <option value="6">LS Barang Jasa</option>
   </select></td>
 </tr>
 <tr>
   <td width='10%'>No SPD </td>
   <td><input id="sp" name="sp" style="width:150px" /></td>
   <td width='10%'>Kegiatan</td>
   <td><input id="gt" name="gt" style="width:150px" /></td>
 </tr>
 <tr>
   <td width='10%'>Rekanan</td>
   <td width='10%'><textarea name="rekanan" cols="30" rows="1" > </textarea></td>
   <td width='10%'>Bank</td>
   <td width='10%'>
   								<?php
								  		$bank1="select * from ms_bank ";
                                        $pagingquery1 = $bank1; //echo "edit  $pagingquery1<br />";
                                        $res = mysql_query($pagingquery1)or die("pagingquery gagal".mysql_error());
								?>
                                <select name="bank1" id="bank1" style="height: 27px; width: 100px;">
                                <option value="">...Pilih Bank </option>
       							<?php
								        if($res)
                                        {
                                        while ($result = mysql_fetch_row($res)) 
                                            {
  								  ?>
                                       <option value="<?php echo $result[0]; ?>" <?php if($result[0]==$bank1){echo "selected";}?>> <?php echo $result[0]."-".$result[1]; ?> </option>
                                 <?php 
                                            }
                                        }
                                  ?>
            </select></td>
 </tr>
 <tr>
   <td width='10%'>NPWP</td>
   <td width='10%'><input type="text" name="npwp" id="npwp" value="" /></td>
   <td width='10%'>Rekening</td>
   <td width='10%'><input type="text" name="rekening" id="rekening"  value="" /></td>
 </tr>

        
         <tr>
         <td width='10%'></td>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
            <td width='10%'></td>
            <td width='10%'></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div>


</body>
</html>