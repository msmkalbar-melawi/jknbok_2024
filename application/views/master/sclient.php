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
       
        get_sclient()
        });    
     
  
    
     
     $(function(){ 
     
        
         $('#tgl_rka').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_dpa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_ubah').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_dpa').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
         $('#tgl_dppa').datebox({  
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
               kode = rowData.kd_skpd;               
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
               //$('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tagih'});                 
           }  
       });     
    }); 
    
    function get_sclient()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/master/get_sclient',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").combogrid("setValue",data.kd_skpd);
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
                                        //$("#head1").attr("value",data.head1);
//        								$("#head2").attr("value",data.head2);
//                                        $("#head3").attr("value",data.head3);
//        								$("#head4").attr("value",data.head4);
//                                        $("#ingat1").attr("value",data.ingat1);
//        								$("#ingat2").attr("value",data.ingat2);
//                                        $("#ingat3").attr("value",data.ingat3);
//        								$("#ingat4").attr("value",data.ingat4);
//                                        $("#ingat5").attr("value",data.ingat5);
        								
        							  }                                     
        	});  
        }       


    
       
       

    
    function kosong(){
        $("#skpd").attr("value",'');
		$("#thn").attr("value",'');
        $("#prov").attr("value",'');
		$("#kab").attr("value",'');
        $("#ibu").attr("value",'');
		$("#tgl_rka").datebox("setValue",'');
        $("#tgl_dpa").datebox("setValue",'');
		$("#tgl_ubah").datebox("setValue",'');
        $("#tgl_dppa").datebox("setValue",'');
		$("#rek_kasda").attr("value",'');
        $("#rek_kasin").attr("value",'');
		$("#rek_kasout").attr("value",'');
        $("#rk_skpd").attr("value",'');
		$("#rk_skpkd").attr("value",'');
        //$("#head1").attr("value",'');
//		$("#head2").attr("value",'');
//        $("#head3").attr("value",'');
//		$("#head4").attr("value",'');
//        $("#ingat1").attr("value",'');
//		$("#ingat2").attr("value",'');
//        $("#ingat3").attr("value",'');
//		$("#ingat4").attr("value",'');
//        $("#ingat5").attr("value",''); 
       
    }
    

    
       function simpan()
       {
        var cskpd = $('#skpd').combogrid('getValue');
        var cthn = document.getElementById('thn').value;
        var cprov = document.getElementById('prov').value;
        var ckab = document.getElementById('kab').value;
        var cibu = document.getElementById('ibu').value;
        var ctgl_rka = $('#tgl_rka').datebox('getValue');
        var ctgl_dpa = $('#tgl_dpa').datebox('getValue');
        var ctgl_ubah = $('#tgl_ubah').datebox('getValue');
        var ctgl_dppa = $('#tgl_dppa').datebox('getValue');
        var crek_kasda = document.getElementById('rek_kasda').value;
        var crek_kasin = document.getElementById('rek_kasin').value;
        var crek_kasout = document.getElementById('rek_kasout').value;
        var crk_skpd = document.getElementById('rk_skpd').value;
        var crk_skpkd = document.getElementById('rk_skpkd').value;
        //var chead1 = document.getElementById('head1').value;
//        var chead2 = document.getElementById('head2').value;
//        var chead3 = document.getElementById('head3').value;
//        var chead4 = document.getElementById('head4').value;
//        var cingat1 = document.getElementById('ingat1').value;
//        var cingat2 = document.getElementById('ingat2').value;
//        var cingat3 = document.getElementById('ingat3').value;
//        var cingat4 = document.getElementById('ingat4').value;
//        var cingat5 = document.getElementById('ingat5').value;
         //alert(ctgl_dpa);       
        if (cskpd==''){
            alert('SKPD Tidak Boleh Kosong');
            exit();
        } 
        if (cthn==''){
            alert('TAHUN ANGGARAN Tidak Boleh Kosong');
            exit();
        }
        if (cprov==''){
            alert('PROVINSI Tidak Boleh Kosong');
            exit();
        }
        if (ckab==''){
            alert('KABUPATEN/KOTA Tidak Boleh Kosong');
            exit();
        }
        if (cibu==''){
            alert('IBU KOTA Tidak Boleh Kosong');
            exit();
        }
        

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_sclient',
                    data: ({tabel:'sclient',cskpd:cskpd,cthn:cthn,cprov:cprov,ckab:ckab,cibu:cibu,ctgl_rka:ctgl_rka,ctgl_dpa:ctgl_dpa,ctgl_ubah:ctgl_ubah,
                            ctgl_ubah:ctgl_ubah,ctgl_dppa:ctgl_dppa,crek_kasda:crek_kasda,crek_kasin:crek_kasin,crek_kasout:crek_kasout,crk_skpd:crk_skpd,
                            crk_skpkd:crk_skpkd}),
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
        <h3 align="center"><u><b><a href="#" id="section1">MASTER CLIENT</a></b></u></h3>
            <div>
                
                    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
                    <fieldset>
                    <table align="center" style="width:100%;" border="0">
                    <tr>
                        <td width="21%">SKPD</td>
                      <td width="31%"><input id="skpd" name="skpd" style="width: 140px;" /></td>
                        <td width="19%">TAHUN ANGGARAN</td>
                      <td width="29%"><input type="text" id="thn" style="width: 140px;"/></td>
                        
                    </tr> 
                    <tr>
                    
                        <td width="19%">DAERAH</td>
                      <td width="29%"><input type="text" id="kab" style="width: 140px;"/></td>
                        <td width="21%">PROVINSI</td>
                      <td width="31%"><input id="prov" name="prov" style="width: 140px;" /></td>
                      
                        
                    </tr>
                    <tr>
                        <td width="21%">IBU KOTA</td>
                      <td width="31%"><input id="ibu" name="ibu" style="width: 140px;" /></td>
                        <td>TGL RKA </td>
                        <td><input type="text" id="tgl_rka" style="width: 140px;" /></td>
                        
                    </tr>             
                    <tr>
                        
                         <td>TGL DPA</td>
                        <td><input type="text" id="tgl_dpa" style="width: 140px;" /></td>
                        <td>TGL PERUBAHAN </td>
                        <td><input type="text" id="tgl_ubah" style="width: 140px;" /></td>
                        
                    </tr>
                    <tr>
                        <td>TGL DPPA</td>
                        <td><input type="text" id="tgl_dppa" style="width: 140px;" /></td>
                        <td width="19%">REK KASDA</td>
                      <td width="29%"><input id="rek_kasda" style="width: 140px;" /></td>
                                                    
                    </tr>
                    <tr>
                        <td>REK KASIN </td>
                        <td><input id="rek_kasin" name="rek_kasin" style="width: 140px;" /></td>
                        <td>REK KASOUT </td>
                         <td><input id="rek_kasout" name="rek_kasout" style="width: 140px;" /></td>
                                        
                    </tr> 
                    <tr>
                        <td>RK SKPD </td>
                         <td><input id="rk_skpd" name="rk_skpd" style="width: 140px;" /></td>
                         <td>RK SKPKD </td>
                         <td><input id="rk_skpkd" name="rk_skpkd" style="width: 140px;" /></td>
                                       
                    </tr>            
                    <!--<tr>
                        <td>HEAD 1</td>
                        <td><textarea rows="1" cols="50" id="head1" style="width: 250px;"></textarea></td> 
                        <td>HEAD 2</td>
                        <td><textarea rows="1" cols="50" id="head2" style="width: 250px;"></textarea></td> 
                         
                    </tr>
                    
                    <tr>
                        <td>HEAD 3</td>
                        <td><textarea rows="1" cols="50" id="head3" style="width: 250px;"></textarea>
                        </td> 
                        <td>HEAD 4</td>
                        <td><textarea rows="1" cols="50" id="head4" style="width: 250px;"></textarea>
                        </td>
                    </tr>
                   
                    <tr>
                        <td>INGAT 1</td>
                        <td><textarea rows="1" cols="50" id="ingat1" style="width: 250px;"></textarea>
                        </td> 
                        <td>INGAT 2</td>
                        <td><textarea rows="1" cols="50" id="ingat2" style="width: 250px;"></textarea>
                        </td> 
                         
                    </tr>
               
                    <tr>
                        <td>INGAT 3</td>
                        <td><textarea rows="1" cols="50" id="ingat3" style="width: 250px;"></textarea>
                        </td> 
                        <td>INGAT 4</td>
                        <td ><textarea rows="1" cols="50" id="ingat4" style="width: 250px;"></textarea>
                        </td> 
                    </tr>
                  
                    <tr>
                        <td> INGAT 5</td>
                        <td colspan="3" ><textarea rows="1" cols="50" id="ingat5" style="width: 250px;"></textarea>
                        </td> 
                    </tr>-->
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




  	
