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
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <style>    
    #tagih {
        position : relative;
        width    : 500px;
        height   : 70px;
        padding  : 0.4em;
    }  
    
    </style>
    <script type="text/javascript">
    
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0;
    var lcidx    = 0;
    var lcstatus = '';
	var kd_rek5 = '';
                    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height   : 550,
            width    : 900,
            modal    : true,
            autoOpen : false,
        });
		 get_tahun();
		 get_skpd2();
		 document.getElementById("pesan").innerHTML="";
		
		$('#skpd').combogrid({  
			   panelWidth:700,  
			   idField:'kd_skpd',  
			   textField:'kd_skpd',  
			   mode:'remote',
			   url:'<?php echo base_url(); ?>index.php/rka/skpd_konsol/'+skpd,  
			   columns:[[  
				   {field:'kd_skpd',title:'Kode SKPD',width:100},  
				   {field:'nm_skpd',title:'Nama SKPD',width:900}    
			   ]],  
			   onSelect:function(rowIndex,rowData){
				   kode = rowData.kd_skpd;
			   }  
		   });
		
		});    
    
     function get_skpd2()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        		skpd = data.kd_skpd;
				
        							  }                                     
        	});  
        } 
	 
	 function get_sk()
        {
			$(document).ready(function() {
			   $('#skpd').combogrid({  
			   panelWidth:700,  
			   idField:'kd_skpd',  
			   textField:'kd_skpd',  
			   mode:'remote',
			   url:'<?php echo base_url(); ?>index.php/rka/skpd_konsol/'+skpd,  
			   columns:[[  
				   {field:'kd_skpd',title:'Kode SKPD',width:100},  
				   {field:'nm_skpd',title:'Nama SKPD',width:900}    
			   ]],  
			   onSelect:function(rowIndex,rowData){
				   kode = rowData.kd_skpd;
				   $("#nmskpd").attr("value",rowData.nm_skpd);
				   get_nourut();
			   }  
		   });
		   });
		}
	
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/calk_konsol/load_calk_neraca_1442',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true",                       
        columns:[[
    	    {field:'kd_rek',
    		title:'rek',
    		width:10,
            align:"center"},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:15,
            align:"center"},
			{field:'nm_rek',
    		title:'Nama Rekening',
    		width:35,
            align:"left"},
            {field:'ket',
    		title:'Uraian',
    		width:55,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_rek5     = rowData.kd_rek;
          kd_skpd     = rowData.kd_skpd;
          nm_rek5     = rowData.nm_rek;
          kd_rinci     = rowData.kd_rinci;
          ket1        = rowData.ket;
          lcidx       = rowIndex;
          
		  get(kd_rek5,kd_skpd,nm_rek5,ket1,kd_rinci);   
        },
        onDblClickRow:function(rowIndex,rowData){
           lcstatus = 'edit';
           lcidx    = rowIndex;
           judul    = 'Edit Data Penerimaan'; 
           edit_data();   
        }
        });
    
    });
    
    function get_tahun()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        } 
    
     function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
       
    function get(kd_rek5,kd_skpd,nm_rek5,ket1,kd_rinci){
		$('#skpd').combogrid("setValue",kd_skpd);
		$("#kd_rek5").attr("value",kd_rek5);
	    $("#nm_rek5").attr("value",nm_rek5);
		$("#ket1").attr("value",ket1);
		$("#kd_rinci").attr("value",kd_rinci);
		
		$("#skpd").combogrid('disable');
		
    }
    
    function simpan_terima() {
		
        var kd_skpd   = $('#skpd').combogrid('getValue') ;
        var kd_rek5    = document.getElementById('kd_rek5').value;
        var nm_rek5    = document.getElementById('nm_rek5').value;
        var kd_rinci    = document.getElementById('kd_rinci').value;
        var ket1_pend = document.getElementById('ket1').value; 
		var ket1      = '<p>' + ket1_pend.replace(/\n/g, "</p>\n<p>") + '</p>';
		
		$(document).ready(function(){
              
          lcinsert = "(kd_skpd,kd_rek, ket, kd_rinci) ";
          lcvalues = "( '"+kd_skpd+"', '"+kd_rek5+"','"+ket1+"','"+kd_rinci+"') ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/calk_konsol/simpan_neraca_calk',
                data     : ({tabel :'isi_neraca_calk', kd_skpd:kd_skpd,kd_rek:kd_rek5,  kolom :lcinsert, nilai:lcvalues}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        if ( status=='2' ){
								swal("Berhasil", "Data Berhasil Disimpan", "success");
                                $("#dialog-modal").dialog('close');
                                $('#dg').edatagrid('reload');
                        }
                        
                        if ( status=='0' ){
							swal("Error", "Simpan Gagal", "warning");
                            exit();
                        }
                    }
            });
            });
       
		});
        
		
    }
	
	
	function kosong(){
        $("#ket1").attr("value",'');
        lcstatus = 'tambah'; 
		get_sk();
    }
    
	
	function tambah(){
        $("#kd_rek5").attr("value",'1442');
		lcstatus = 'tambah';
        judul = 'Input Data';
        $("#dialog-modal").dialog({ title: judul });
		$("#dialog-modal").dialog('open');
		$("#skpd").combogrid('enable');
		kosong();
     } 
        
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
    }    

	function hapus(){
        var rows  = $("#dg").edatagrid("getSelected") ;
       
		var nobkt = rows.ket;
		var kode = rows.kd_skpd;
       
        var tanya = confirm('Apakah Data '+nobkt+' Akan Di Hapus ???') ;
        
        if ( tanya == true ) {
        
            var urll  = '<?php echo base_url(); ?>index.php/calk_konsol/hapus_calk';
            $(document).ready(function(){
             $.post(urll,({no:nobkt,skpd:kode}),function(data){
                status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    $('#dg').datagrid('deleteRow',lcidx);   
                    alert('Data Berhasil Dihapus..!!');
                    $("#dg").edatagrid("unselectAll") ;
                    exit();
                }
             });
            });    
        }
    } 
	
	function get_nourut(){
			var kd_rek5  = document.getElementById('kd_rek5').value;
			var kdskpd = $('#skpd').combogrid('getValue') ;
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/calk_konsol/get_nourut',
        		type: "POST",
        		dataType:"json", 
				data    : ({kd_rek5:kd_rek5,kdskpd:kdskpd}),
        		success:function(data){
				$("#kd_rinci").attr("value",data.no_urut);
			  }                                     
        	});  
        }
    
	
     function exit(){
        $("#dialog-modal").dialog('close');
		$('#dg').edatagrid('reload');
     }    
	 
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">Edit Piutang Pajak Daerah</a></b></u></h3>
    <div>
		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();kosong();">Tambah</a> 
		<a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <table id="dg" title="Listing Data Kas Penerimaan" style="width:870px;height:450px;" >  
        </table>
  </div>   
</div>
</div>

<div id="dialog-modal" title="">
    <p class="validateTips"></p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
			<tr>
                <td colspan="3">
                <p id="pesan" style="font-size: large;"></p>
                </td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" readonly="true" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td                        
            </tr>
			<tr>
                <td>Kode Rekening</td>
                <td></td>
                <td><input id="kd_rek5" name="kd_rek5" style="width: 140px;" disabled />  <input type="text" id="nm_rek5" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Kode Rinci</td>
                <td></td>
                <td><input id="kd_rinci" name="kd_rinci" style="width: 140px;" disabled /></td>                            
            </tr>
			<tr>
                <td>Uraian</td>
                <td colspan="2"><textarea rows="5" cols="50" id="ket1" style="width: 740px;"></textarea>
                </td> 
            </tr>
            <tr>
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_terima();">Simpan</a>
				
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:exit();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>