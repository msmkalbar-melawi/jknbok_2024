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
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 400,
            width: 900,
            modal: true,
            autoOpen:false,
        });
       get_tapd()
       
        });    
     
  
    
     
     $(function(){ 
    
        $('#nip1').combogrid({  
           panelWidth:700,  
           idField:'nip',  
           textField:'nip',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_ttd',  
           columns:[[  
               {field:'nip',title:'NIP',width:100},  
               {field:'nama',title:'NAMA',width:300},
               {field:'jabatan',title:'JABATAN',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $("#nama1").attr("value",rowData.nama.toUpperCase());
               $("#jabatan1").attr("value",rowData.jabatan.toUpperCase());
               //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih'});                 
           }  
       }); 
       
        $('#nip2').combogrid({  
           panelWidth:700,  
           idField:'nip',  
           textField:'nip',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_ttd',  
           columns:[[  
               {field:'nip',title:'NIP',width:100},  
               {field:'nama',title:'NAMA',width:300},
               {field:'jabatan',title:'JABATAN',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $("#nama2").attr("value",rowData.nama.toUpperCase());
               $("#jabatan2").attr("value",rowData.jabatan.toUpperCase());
               //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih'});                 
           }  
       });
       $('#nip3').combogrid({  
           panelWidth:700,  
           idField:'nip',  
           textField:'nip',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_ttd',  
           columns:[[  
               {field:'nip',title:'NIP',width:100},  
               {field:'nama',title:'NAMA',width:300},
               {field:'jabatan',title:'JABATAN',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $("#nama3").attr("value",rowData.nama.toUpperCase());
               $("#jabatan3").attr("value",rowData.jabatan.toUpperCase());
               //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih'});                 
           }  
       }); 
       
       $('#nip4').combogrid({  
           panelWidth:700,  
           idField:'nip',  
           textField:'nip',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_ttd',  
           columns:[[  
               {field:'nip',title:'NIP',width:100},  
               {field:'nama',title:'NAMA',width:300},
               {field:'jabatan',title:'JABATAN',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $("#nama4").attr("value",rowData.nama.toUpperCase());
               $("#jabatan4").attr("value",rowData.jabatan.toUpperCase());
               //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih'});                 
           }  
       });
       
       $('#nip5').combogrid({  
           panelWidth:700,  
           idField:'nip',  
           textField:'nip',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_ttd',  
           columns:[[  
               {field:'nip',title:'NIP',width:100},  
               {field:'nama',title:'NAMA',width:300},
               {field:'jabatan',title:'JABATAN',width:300}    
           ]],  
           onSelect:function(rowIndex,rowData){              
               $("#nama5").attr("value",rowData.nama.toUpperCase());
               $("#jabatan5").attr("value",rowData.jabatan.toUpperCase());
               //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih'});                 
           }  
       });            
    }); 
    
   


    
    function get_tapd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/master/get_tapd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#nip1").combogrid("setValue",data.nip);
        								$("#thn").attr("value",data.thn_ang);
                                        $("#prov").attr("value",data.provinsi);
        								$("#kab").attr("value",data.kab_kota);
                                        $("#ibu").attr("value",data.daerah);
        								$("#tgl_rka").datebox("setValue",data.tgl_rka);
                                        $("#tgl_dpa").datebox("setValue",data.tgl_dpa);
        								$("#tgl_ubah").datebox("setValue",data.tgl_ubah);
                                        $("#tgl_dppa").datebox("setValue",data.tgl_dppa);
        								$("#rek_kasda").attr("value",data.rek_kasda);
                                        $("#rek_kasin").attr("value",data.rek_kasin);
        								$("#rek_kasout").attr("value",data.rek_kasout);
                                        $("#rk_skpd").attr("value",data.rk_skpd);
        								$("#rk_skpkd").attr("value",data.rk_skpkd);

        								
        							  }                                     
        	});  
        }       
   
       

    
    function kosong(){
        $('#nip1').combogrid('getValue','');
		$("#nama1").attr("value",'');
        $("#jabatan1").attr("value",'');
	
       
       
    }
    

    
       function simpan()
       {
        var cskpd = $('#skpd').combogrid('getValue');
        var cthn = document.getElementById('thn').value;
        var cprov = document.getElementById('prov').value;
        var ckab = document.getElementById('kab').value;
       
        // alert(rek_lo);       
       
        

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_sclient',
                    data: ({tabel:'sclient',cskpd:cskpd,cthn:cthn,cprov:cprov,ckab:ckab,cibu:cibu,ctgl_rka:ctgl_rka,ctgl_dpa:ctgl_dpa,ctgl_ubah:ctgl_ubah,
                            ctgl_ubah:ctgl_ubah,ctgl_dppa:ctgl_dppa,crek_kasda:crek_kasda,crek_kasin:crek_kasin,crek_kasout:crek_kasout,crk_skpd:crk_skpd,
                            crk_skpkd:crk_skpkd,chead1:chead1,chead2:chead2,chead3:chead3,chead4:chead4,cingat1:cingat1,cingat2:cingat2,cingat3:cingat3,
                            cingat4:cingat4,cingat5:cingat5}),
                    dataType:"json"
                });
            });    
            
            

        
        alert("Data Berhasil disimpan");

    } 
    

  
   </script>

</head>
<body>

<div id="content"> 
    <div id="accordion">
        <h3 align="center"><u><b><a href="#" id="section1">MASTER TAPD</a></b></u></h3>
            <div>
                
                    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
                    <fieldset>
                    <table align="center" style="width:100%;" border="0">
                    <tr>
                      <td width="21%" ></td>
                      <td width="13%">NIP</td>
                      <td width="23%">NAMA</td>
                      <td width="43%">JABATAN</td>
                        
                    </tr> 
                    <tr>
                        <td width="21%"></td>
                      <td width="13%"><input id="nip1" name="nip1" style="width: 140px;" /></td>
                      <td width="23%"><input type="text" id="nama1" style="width: 240px;"/></td>
                      <td width="43%"><input type="text" id="jabatan1" style="width: 300px;"/></td>
                        
                    </tr> 
                    
                    <tr>
                        <td width="21%"></td>
                      <td width="13%"><input id="nip2" name="nip2" style="width: 140px;" /></td>
                      <td width="23%"><input type="text" id="nama2" style="width: 240px;"/></td>
                      <td width="43%"><input type="text" id="jabatan2" style="width: 300px;"/></td>
                        
                    </tr> 
                    
                    <tr>
                        <td width="21%"></td>
                      <td width="13%"><input id="nip3" name="nip3" style="width: 140px;" /></td>
                      <td width="23%"><input type="text" id="nama3" style="width: 240px;"/></td>
                      <td width="43%"><input type="text" id="jabatan3" style="width: 300px;"/></td>
                        
                    </tr> 
                    
                    <tr>
                        <td width="21%"></td>
                      <td width="13%"><input id="nip4" name="nip4" style="width: 140px;" /></td>
                      <td width="23%"><input type="text" id="nama4" style="width: 240px;"/></td>
                      <td width="43%"><input type="text" id="jabatan4" style="width: 300px;"/></td>
                        
                    </tr> 
                    
                    <tr>
                        <td width="21%"></td>
                      <td width="13%"><input id="nip5" name="nip4" style="width: 140px;" /></td>
                      <td width="23%"><input type="text" id="nama5" style="width: 240px;"/></td>
                      <td width="43%"><input type="text" id="jabatan5" style="width: 300px;"/></td>
                        
                    </tr> 
                   
                    <tr>
                        <td colspan="4" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
        		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:kosong();">Kosongkan</a>
                        </td>                
                    </tr>
                </table>       
                </fieldset>
            

    </div>   

</div>

</div>


  	
</body>

</html>




  	
