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
                    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height   : 550,
            width    : 900,
            modal    : true,
            autoOpen : false,
        });
        $("#tagih").hide();
         get_skpd(); 
		 get_tahun();
		 document.getElementById("pesan").innerHTML="";
        });    
    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/calk/load_calk_neraca_1443',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true",                       
        columns:[[
    	    {field:'kd_skpd',
    		title:'Kode SKPD',
    		width:15,
            align:"center"},
            {field:'kd_rek',
    		title:'Kode Rekening',
    		width:20,
            align:"center"},
            {field:'ket',
    		title:'Uraian',
    		width:55,
            align:"left"},
            {field:'nilai',
    		title:'Nilai',
    		width:20,
            align:"right"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_skpd    = rowData.kd_skpd;
          kd_rek     = rowData.kd_rek;
          ket        = rowData.ket;
          nilai      = rowData.nilai;
          lcidx      = rowIndex;
		  get(kd_skpd,kd_rek,ket,nilai);   
        },
        onDblClickRow:function(rowIndex,rowData){
           lcstatus = 'edit';
           lcidx    = rowIndex;
           judul    = 'Edit Data Penerimaan'; 
           edit_data();   
        }
        });
    
    });
    

    function get_skpd() {
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
    
      function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
     }

     function section1(){
         $(document).ready(function(){    
             exit();  
         });
     }
       
     function get(kd_skpd,kd_rek,ket,nilai){
	    $("#kd_rek").attr("value",kd_rek);
		$("#ket").attr("value",ket);
		$("#nilai").attr("value",nilai);
		
    }
    
    function simpan_terima() {
		
        var kd_skpd   = document.getElementById('skpd').value;
        var nm_skpd   = document.getElementById('nmskpd').value;
        var kd_rek    = document.getElementById('kd_rek').value;
		var nilai     = angka(document.getElementById('nilai').value);
        var ket1_pend = document.getElementById('ket').value; 
		var ket1      = '<p>' + ket1_pend.replace(/\n/g, "</p>\n<p>") + '</p>';
		
		$(document).ready(function(){
              
          lcinsert = "(kd_skpd,kd_rek, ket, nilai, kd_rinci) ";
          lcvalues = "( '"+kd_skpd+"', '1443"+kd_rek+"', '"+ket1+"', '"+nilai+"', '"+kd_rek+"') ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/calk/simpan_neraca_calk',
                data     : ({tabel :'isi_neraca_calk', kd_skpd:kd_skpd,kd_rek:kd_rek,  kolom :lcinsert, nilai:lcvalues}),
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
        $("#ket").attr("value",'');
        $("#nilai").attr("value",'');
        $("#kd_rek").attr("value",'');
        $("#nm_rek").attr("value",'');
		$('#save').linkbutton('enable');
        lcstatus = 'tambah';       
    }
    
	 
	function tambah(){
        
		lcstatus = 'tambah';
        judul = 'Input Data Tanah';
        $("#dialog-modal").dialog({ title: judul });
		$("#dialog-modal").dialog('open');
		kosong();
		get_nomor();
     } 
	 
	 function get_nomor()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/calk/no_urut/1443',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
				$("#kd_rek").attr("value",data.no_urut);
			  }                                     
        	});  
        }
	 
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Tanah';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
    }    

     function exit(){
        $("#dialog-modal").dialog('close');
		$('#dg').edatagrid('reload');
     }    
	 
	 function hapus(){
        var cnomor = document.getElementById('ket').value;
        var kd_skpd   = document.getElementById('skpd').value;
		var nilai     = angka(document.getElementById('nilai').value);
		var kd_rek     = document.getElementById('kd_rek').value;
		
		
        var urll   = '<?php echo base_url(); ?>index.php/calk/hapus_calk2';
		var del=confirm('Anda yakin akan menghapus '+cnomor+'  ?');
		if  (del==true){
			$(document).ready(function(){
			 $.post(urll,({no:cnomor,skpd:kd_skpd,nilai:nilai,kd_rek:kd_rek}),function(data){
				status = data;
				if (status=='0'){
					alert('Gagal Hapus..!!');
					exit();
				} else {
					alert('Data Berhasil Dihapus..!!');
					exit();
				}
			 });
			});  
		}
		
    }
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">Edit Neraca (Tanah) Mutasi Bertambah</a></b></u></h3>
    <div>
	<p align="right"> 
		<a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();kosong()">Tambah</a>               
        <table id="dg" title="Listing Data Neraca (Tanah)" style="width:870px;height:450px;" >  
        </table>
	</p>
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
                <td><input id="skpd" name="skpd" style="width: 140px;" readonly="true" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
			<tr>
                <td>Kode Rekening</td>
                <td></td>
                <td><input id="kd_rek" name="kd_rek" style="width: 140px;" disabled />  <input type="text" id="nm_rek" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Uraian</td>
                <td colspan="2"><textarea rows="5" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
			 <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_terima();">Simpan</a>
				<a id="delete" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();section1();">Hapus</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:exit();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>