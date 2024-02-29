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
		url: '<?php echo base_url(); ?>/index.php/calk/load_calk_pendapatan_414',
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
    		width:10,
            align:"center"},
            {field:'nm_rek5',
    		title:'Nama Rekening',
    		width:35,
            align:"left"},
            {field:'ket1',
    		title:'Uraian',
    		width:55,
            align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_skpd     = rowData.kd_skpd;
          kd_rek5     = rowData.kd_rek5;
          nm_rek5     = rowData.nm_rek5;
          kd_ang     = rowData.kd_ang;
          ket1        = rowData.ket1;
          ket2        = rowData.ket2;
          lcidx       = rowIndex;
          
		  get(kd_skpd,kd_rek5,nm_rek5,kd_ang,ket1,ket2);   
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
       
     function get(kd_skpd,kd_rek5,nm_rek5,kd_ang,ket1,ket2){
	    $("#kd_rek5").attr("value",kd_rek5);
	    $("#kd_ang").attr("value",kd_ang);
	    $("#nm_rek5").attr("value",nm_rek5);
		$("#ket1").attr("value",ket1);
		$("#ket2").attr("value",ket2);
    }
    
    function simpan_terima() {
		
        var kd_skpd   = document.getElementById('skpd').value;
        var nm_skpd   = document.getElementById('nmskpd').value;
        var kd_ang    = document.getElementById('kd_ang').value;
        var kd_rek5    = document.getElementById('kd_rek5').value;
        var nm_rek5    = document.getElementById('nm_rek5').value;
        var ket1_pend = document.getElementById('ket1').value; 
        //var ket2_pend = document.getElementById('ket2').value; 
		var ket1      = '<p>' + ket1_pend.replace(/\n/g, "</p>\n<p>") + '</p>';
		//var ket2      = '<p>' + ket2_pend.replace(/\n/g, "</p>\n<p>") + '</p>';
		
		$(document).ready(function(){
              
          lcinsert = "(kd_skpd,kd_rek5, nm_rek5,ket1, kd_ang, ket2) ";
          lcvalues = "( '"+kd_skpd+"', '"+kd_rek5+"', '"+nm_rek5+"', '"+ket1+"', '"+kd_ang+"', '"+ket2+"') ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/calk/simpan_pend_calk',
                data     : ({tabel :'lamp_calk_at', kd_skpd:kd_skpd,kd_ang:kd_ang,kd_rek5:kd_rek5,  kolom :lcinsert, nilai:lcvalues}),
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
<h3 align="center"><u><b><a href="#" id="section1">Edit Pendapatan</a></b></u></h3>
    <div>
        <table id="dg" title="Listing Data Pendapatan" style="width:870px;height:450px;" >  
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
                <td>Kode Anggaran</td>
                <td></td>
                <td><input id="kd_ang" name="kd_ang" style="width: 140px;" disabled /></td>                            
            </tr>
			<tr>
                <td>Kode Rekening</td>
                <td></td>
                <td><input id="kd_rek5" name="kd_rek5" style="width: 140px;" disabled />  <input type="text" id="nm_rek5" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Uraian</td>
                <td colspan="2"><textarea rows="5" cols="50" id="ket1" style="width: 740px;"></textarea>
                </td> 
            </tr>
			<!--<tr hidden>
                <td>Keterangan Realisasi</td>
                <td colspan="2"><textarea rows="5" cols="50" id="ket2" style="width: 740px;"></textarea>
                </td> 
            </tr>-->
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