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
    // oke vin, coba


    
    
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
		url: '<?php echo base_url(); ?>/index.php/calk/load_calk_beban_911',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true",                       
        columns:[[
            {field:'nm_rek',
    		title:'Nama Rekening',
    		width:55,
            align:"left"},
            {field:'nilai',
    		title:'nilai',
    		width:55,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_rek     = rowData.kd_rek;
          nm_rek     = rowData.nm_rek;
          nilai      = rowData.nilai;
          lcidx      = rowIndex;
          
		  get(kd_rek,nm_rek,nilai);   
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
					validate_rek();
					penetapan();
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
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
       
     function get(kd_rek,nm_rek,nilai){
	    $("#kd_rek").attr("value",kd_rek);
	    $("#nm_rek").attr("value",nm_rek);
		$("#nilai").attr("value",nilai);
    }
    
    function simpan_terima() {
		
        var kd_skpd   = document.getElementById('skpd').value;
        var nm_skpd   = document.getElementById('nmskpd').value;       
        var kd_rek5    = document.getElementById('kd_rek').value;
        var nm_rek5    = document.getElementById('nm_rek').value;
		var lntotal  = angka(document.getElementById('nilai').value);
		
		$(document).ready(function(){
              
          lcinsert = "(kd_skpd,kd_rek, nm_rek, nilai) ";
          lcvalues = "( '"+kd_skpd+"', '"+kd_rek+"', '"+nm_rek+"', '"+lntotal+"') ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/calk/simpan_beban_calk',
                data     : ({tabel :'nilai_beban_calk', kd_skpd:kd_skpd,kd_rek:kd_rek,  kolom :lcinsert, nilai:lcvalues}),
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
        
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
    }    

     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
	 
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">Edit Beban</a></b></u></h3>
    <div>
        <table id="dg" title="Listing Data Beban" style="width:870px;height:450px;" >  
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
                <td><input id="skpd" name="skpd" style="width: 140px;" readonly="true" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
			<tr>
                <td>Kode Rekening</td>
                <td></td>
                <td><input id="kd_rek" name="kd_rek" style="width: 140px;" disabled />  <input type="text" id="nm_rek" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
			 <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_terima();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>