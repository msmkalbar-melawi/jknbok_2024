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
		url: '<?php echo base_url(); ?>/index.php/penerimaan/load_terima',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true",                       
        columns:[[
    	    {field:'no_terima',
    		title:'Nomor Terima',
    		width:50,
            align:"center"},
            {field:'tgl_terima',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"center"},
            {field:'kd_rek6',
    		title:'Rekening',
    		width:50,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:50,
            align:"right"},
            {field:'simbol',
    		title:'SPJ',
    		width:10,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor     = rowData.no_terima;
          no_tetap  = rowData.no_tetap;
          tgl       = rowData.tgl_terima;
          kode      = rowData.kd_skpd;
          lcket     = rowData.keterangan;
          lcrek     = rowData.kd_rek6;
          rek       = rowData.kd_rek;
          sumber       = rowData.sumber;
          lcnilai   = rowData.nilai;
          sts       = rowData.sts_tetap;
		  giat	    = rowData.kd_sub_kegiatan;
		  tgl_tetap = rowData.tgl_tetap;
		  spj 		= rowData.spj;
		  kunci 	= rowData.kunci;
          lcidx     = rowIndex;
		  user_nm = rowData.user_nm;
          get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,sumber,lcnilai,sts,giat,tgl_tetap,spj,user_nm,kunci);   
        },
        onDblClickRow:function(rowIndex,rowData){
           lcstatus = 'edit';
           lcidx    = rowIndex;
           judul    = 'Edit Data Penerimaan'; 
           edit_data();   
        }
        });
        
        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }, onSelect: function(date){
		  cek_status_spj();
          }
        });
		
		// $('#pengirim').combogrid({
  //          panelWidth:700,  
  //          idField:'kd_pengirim',  
  //          textField:'kd_pengirim',  
  //          mode:'remote',
  //          url:'<?php echo base_url(); ?>index.php/penerimaan/load_pengirim',             
  //          columns:[[  
  //              {field:'kd_pengirim',title:'Kode Pengirim',width:140},  
  //              {field:'nm_pengirim',title:'Nama Pengirim',width:700}
  //          ]],  
  //          onSelect:function(rowIndex,rowData){
  //              kd_pengirim = rowData.kd_pengirim;
  //              $("#nmpengirim").attr("value",rowData.nm_pengirim);                                      
  //          }
              
  //       });
    
    });
    

    function get_skpd() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/penerimaan/config_skpd',
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
        
	
	function cek_status_spj(){
        var tgl_cek = $('#tanggal').datebox('getValue');
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/tukd/cek_status_spj_terima',
            data: ({tgl_cek:tgl_cek}),
            type: "POST",
            dataType:"json",                         
            success:function(data){
			status_spj = data.status_spj;
				if(status_spj==1){
					$('#save').linkbutton('disable');
					swal("Error", "SPJ sudah disahkan pada bulan ini", "warning");
					document.getElementById("pesan").innerHTML="Tangal Periode ini sudah Di SPJ-kan";
					document.getElementById("pesan").style.color="Green";
				} else{
					$('#save').linkbutton('enable');
					document.getElementById("pesan").innerHTML="";
				}
			}  
          });
        }
	
	
     function validate_rek(){
	  	$(function(){
        $('#rek').combogrid({  
           panelWidth : 700,  
           idField    : 'kd_rek',  
           textField  : 'kd_rek',  
           mode       : 'remote',
           url        : '<?php echo base_url(); ?>index.php/penerimaan/ambil_rek_tetap/'+kode,             
           columns    : [[  
		           {field:'kd_rek6',title:'Kode Rek LRA',width:100},  
               {field:'kd_rek',title:'Kode Rek LO',width:100},
			         {field:'nm_rek',title:'Uraian Rinci',width:200},
			         {field:'nm_rek5',title:'Uraian Obyek',width:200},
               {field:'kd_sub_kegiatan',title:'Kegiatan',width:500}
              ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek.toUpperCase());
               $("#rek1").attr("value",rowData.kd_rek6);
			   $("#rekcheck").attr("value",rowData.kd_rek);
               $("#giat").attr("value",rowData.kd_sub_kegiatan);
              }    
            });
	  	    });
		} 
        
    function get_tahun()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/penerimaan/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        } 
     function penetapan(){
         var kode = kode;
         $('#notetap').combogrid({  
           panelWidth  : 420,  
           idField     : 'no_tetap',  
           textField   : 'no_tetap',  
           mode        : 'remote',
           url         : '<?php echo base_url(); ?>index.php/penerimaan/load_no_tetap',
           queryParams : ({cari:kode}),             
           columns:[[  
               {field:'no_tetap',title:'No Penetapan',width:140},  
               {field:'tgl_tetap',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}]],  
           onSelect:function(rowIndex,rowData){
            var ststagih='1';
            $("#tgltetap").attr("value",rowData.tgl_tetap);
            $("#rek").combogrid("setValue",rowData.kd_rek_lo);
            $("#rek1").attr("Value",rowData.kd_rek6);
            $("#nmrek").attr("Value",rowData.nm_rek6);
            $("#keterangan").attr("value",rowData.ket);
            $("#nilai").attr("value",number_format(rowData.nilai,2,'.',','));  
            $("#nil_tetap").attr("value",number_format(rowData.nilai,2,'.',','));  
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
       
     function get(nomor,no_tetap,tgl,kode,lcket,lcrek,rek,sumber,lcnilai,sts,giat,tgl_tetap,spj,user_nm){
	    
		$("#notetap").combogrid("setValue",no_tetap);
        $("#nomor").attr("value",nomor);
        $("#nomor_hide").attr("value",nomor);
        $("#kunci").attr("value",kunci);
        $("#tanggal").datebox("setValue",tgl);
        $("#rek").combogrid("setValue",rek);
        $("#pengirim").combogrid("setValue",sumber);
        $("#rek1").attr("Value",lcrek);
		$("#rekcheck").attr("value",rek);
		$("#giat").attr("Value",giat);
        $("#nilai").attr("value",lcnilai);
		$("#ket").attr("value",lcket);
        if (sts==1){            
            $("#status").attr("checked",true);
            $("#tagih").show();
			$("#nil_tetap").attr("value",lcnilai);
			$("#tgltetap").attr("value",tgl_tetap);
        } else {
            $("#status").attr("checked",false);
            $("#tagih").hide();
			$("#tgltetap").attr("value",'');
        }
		if(spj==1){
			$('#save').linkbutton('disable');
			$('#tanggal').datebox('disable');
			document.getElementById("pesan").innerHTML="Sudah Di SPJ-kan";
			document.getElementById("pesan").style.color="Green";
		} else{
			$('#save').linkbutton('enable');
			$('#tanggal').datebox('enable');
			document.getElementById("pesan").innerHTML="";
		}		
		
		 if(kunci=='1'){
			$('#save').linkbutton('disable');
			$('#del').linkbutton('disable');		
		}else{
			$('#save').linkbutton('enable');
			$('#del').linkbutton('enable');			
		}
	}
    
    
    function kosong(){
      $("#nomor").attr("value",'');
      $("#nomor_hide").attr("value",'');
      $("#tanggal").datebox("setValue",'');
      $("#nilai").attr("value",'');        
      $("#rek").combogrid("setValue",'');
      $("#pengirim").combogrid("setValue",'');
      $("#rek1").attr("Value",'');
      $("#rekcheck").attr("Value",'');
      $("#nmrek").attr("value",'');
      $("#giat").attr("Value",'');
      $("#ket").attr("value",'');
      $("#notetap").combogrid("setValue",'');        
      $("#tgltetap").attr("value",'');
      $("#status").attr("checked",false);      
      $("#tagih").hide();
      $('#save').linkbutton('enable');
		document.getElementById("pesan").innerHTML="";
        document.getElementById("nomor").focus();         
        lcstatus = 'tambah';       
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_terima',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
      
    
    function simpan_terima() {
      var cno      = document.getElementById('nomor').value;
      var cno_hide = document.getElementById('nomor_hide').value;
      var ctgl     = $('#tanggal').datebox('getValue');
      var cskpd    = document.getElementById('skpd').value;
      var cnmskpd  = document.getElementById('nmskpd').value ;
      var lckdrek  = $('#rek').combogrid('getValue');
      var rek      = document.getElementById('rek1').value;
      var rekcheck = document.getElementById('rekcheck').value ;
      // var cpengirim = $('#pengirim').combogrid('getValue');
      var cpengirim = document.getElementById('pengirim').value ;
      var kegi      = document.getElementById('giat').value;
      var lcket    = document.getElementById('ket').value;
      var lntotal  = angka(document.getElementById('nilai').value);
      lctotal  = number_format(lntotal,0,'.',',');
      var cstatus  = document.getElementById('status').checked;
      var tot_tetap  = angka(document.getElementById('nil_tetap').value);
		var tahun_input = ctgl.substring(0, 4);
		if (tahun_input != tahun_anggaran){
			swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
			exit();
		}
        if (cstatus==false){
           cstatus=0;
        }else{
            cstatus=1;
			if(tot_tetap<lntotal){
			swal("Error", "Melebihi nilai", "warning");
				exit();
			}
        }
        
        var ctetap    = $('#notetap').combogrid('getValue');
        var ctgltetap = document.getElementById('tgltetap').value;
                
        if (cno==''){
			swal("Error", "Nomor  Tidak Boleh Kosong", "warning");
            exit();
        } 
		
		// if (cpengirim==''){
		// 	swal("Error", "Pengirim Tidak Boleh Kosong", "warning");
  //           exit();
  //       } 
		
		if (rek==''){
			swal("Error", "Rekening  Tidak Boleh Kosong", "warning");
            exit();
        } 
		
		if (lckdrek==''){
			swal("Error", "Rekening  Tidak Boleh Kosong", "warning");
            exit();
        } 
		
        if (ctgl==''){
			swal("Error", "Tanggal Tidak Boleh Kosong", "warning");
            exit();
        }
        if (cskpd==''){
			swal("Error", "SKPD Tidak Boleh Kosong", "warning");
            exit();
        }
            
		if (lckdrek != rekcheck){
            alert('Rekening Tidak Sesuai');
            exit();
        }	
		
        if ( lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_terima',field:'no_terima'}),
                    url: '<?php echo base_url(); ?>/index.php/penerimaan/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						swal("Error", "Nomor Telah Dipakai!", "warning");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
						//mulai
           
            lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_sub_kegiatan,   kd_rek6,   kd_rek_lo,     nilai,         keterangan, jenis, sumber  ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+ctetap+"', '"+ctgltetap+"', '"+cstatus+"', '"+cskpd+"', '"+kegi+"',  '"+rek+"', '"+lckdrek+"', '"+lntotal+"', '"+lcket+"', '1', '"+cpengirim+"' ) ";
            
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/penerimaan/simpan_terima_ag',
                    data     : ({tabel       :'tr_terima',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_terima',   lcid       :cno}),
                    dataType : "json",
                    success  : function(data) {
                        status = data;
                        if ( status == '0') {
							swal("Error", "Gagal Simpan", "warning");
                            exit();
                        }  else {
								swal("Berhasil", "Data Berhasil Tersimpan", "success");
                                lcstatus = 'edit';
                                $("#dialog-modal").dialog('close');
                                $('#dg').edatagrid('reload');
                                //exit();
                             }
                    }
                });
            }); 
            
            
           
       //akhir-mulai 
        }
		}
		});
		});
		
        
            
       } else {
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_terima',field:'no_terima'}),
                    url: '<?php echo base_url(); ?>/index.php/penerimaan/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=cno_hide){
						swal("Error", "Nomor telah dipakai", "warning");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide){
						alert("Nomor Bisa dipakai");
			//mulai	
            
           lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_sub_kegiatan,   kd_rek6,   kd_rek_lo,     nilai,         keterangan, jenis, sumber  ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+ctetap+"', '"+ctgltetap+"', '"+cstatus+"', '"+cskpd+"', '"+kegi+"',  '"+rek+"', '"+lckdrek+"', '"+lntotal+"', '"+lcket+"', '1', '"+cpengirim+"' ) ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/penerimaan/update_terima_ag',
                data     : ({tabel       :'tr_terima',  kolom       :lcinsert,        nilai       :lcvalues,        cid       :'no_terima',   lcid       :cno,no_hide:cno_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                        
                        if ( status=='2' ){
								swal("Berhasil", "Data Berhasil Disimpan", "success");
                                lcstatus = 'edit';
                                $("#nomor_hide").attr("Value",cno) ;
                                $("#dialog-modal").dialog('close');
                                $('#dg').edatagrid('reload');
                               // exit();
                        }
                        
                        if ( status=='0' ){
							swal("Error", "Simpan Gagal", "warning");
                            exit();
                        }
                    }
            });
            });
        //akhir
        }
			}
		});
		});
        }
       
    }
    
    
    
  
    
    
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
    }    
        
    
    function tambah(){
        
		$("#notetap").combogrid("setValue",'');

		
		lcstatus = 'tambah';
        judul = 'Input Data Penerimaan';
        $("#dialog-modal").dialog({ title: judul });
		$("#dialog-modal").dialog('open');
		
		document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
		kosong();
     } 


     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        
        var rows  = $("#dg").edatagrid("getSelected") ;
        var nobkt = rows.no_terima ;
                
        var tanya = confirm('Apakah Data Nomor Terima '+nobkt+' Akan Di Hapus ???') ;
        
        if ( tanya == true ) {
        
            var urll  = '<?php echo base_url(); ?>index.php/penerimaan/hapus_terima';
            $(document).ready(function(){
             $.post(urll,({no:nomor,skpd:kode}),function(data){
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
    
    function runEffect() {
		$('#notetap').combogrid({  
           panelWidth:420,  
           idField:'no_tetap',  
           textField:'no_tetap',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/penerimaan/load_no_tetap',
           queryParams:({kd:kode}),             
           columns:[[  
               {field:'no_tetap',title:'No Penetapan',width:140},  
               {field:'tgl_tetap',title:'Tanggal',width:140},
               {field:'kd_skpd',title:'SKPD',width:140}
           ]]  
		});
        var selectedEffect = 'blind';            
        var options = {};                      
        $( "#tagih" ).toggle( selectedEffect, options, 500 );
        $("#notetap").combogrid("setValue",'');
        $("#tgltetap").attr("value",'');
        //$("#nilai").attr("value",'');
        $("#skpd").combogrid("setValue",'');
        $("#rek").combogrid("setValue",'');
        $("#nil_tetap").attr("value",'');
        
    };     
       
    function addCommas(nStr)
    {
    	nStr += '';
    	x = nStr.split(',');
        x1 = x[0];
    	x2 = x.length > 1 ? ',' + x[1] : '';
    	var rgx = /(\d+)(\d{3})/;
    	while (rgx.test(x1)) {
    		x1 = x1.replace(rgx, '$1' + '.' + '$2');
    	}
    	return x1 + x2;
    }
    
    function delCommas(nStr)
    {
    	nStr += ' ';
    	x2 = nStr.length;
        var x=nStr;
        var i=0;
    	while (i<x2) {
    		x = x.replace(',','');
            i++;
    	}
    	return x;
    }
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN PENERIMAAN</a></b></u></h3>
    <div>
    <p align="right">         
        <button type="submit" class="easyui-linkbutton" plain="true" onclick="javascript:tambah();kosong()"><i class="fa fa-plus"></i> Tambah</button>               
        <button type="delete" id="del" class="easyui-linkbutton" plain="true" onclick="javascript:hapus();"><i class="fa fa-trash"></i> Hapus</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" value="" id="txtcari"/>
        <button type="primary" class="easyui-linkbutton" plain="true" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
        <table id="dg" title="Listing data Penerimaan " style="width:870px;height:450px;" >  
        </table>
 
    </p> 
    </div>   
</div>
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
			<tr>
                <td colspan="3">
                <p id="pesan" style="font-size: large;"></p>
                </td>
            </tr>
            <tr>
                <td colspan="5"><b>Dengan Penetapan</b><input type="checkbox" id="status"  onclick="javascript:runEffect();"/>
                    <div id="tagih">
                        <table>
                            <tr>
                                <td>No Penetapan&nbsp;</td>
                                <td><input type="text" id="notetap" style="width: 200px;" /></td>
                                <td>&nbsp;Tgl&nbsp;&nbsp;</td>
                                <td><input type="text" id="tgltetap" style="width: 100px;" /></td> 
								<td>&nbsp;Nilai&nbsp;&nbsp;</td>
                                <td><input type="text" id="nil_tetap" style="width: 100px; text-align: right;" /></td>   
                            </tr>
                        </table> 
                    </div>
                
                </td>                
            </tr>
            <tr>
                <td>No. Terima</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/><input type="hidden" id="nomor_hide" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tanggal </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td>Rekening</td>
                <td></td>
                <td><input id="rek" name="rek" style="width: 140px;" readonly="true"/> <input id="rek1" style="border:0;width: 140px;" readonly="true"/>
                 <input type="text" id="nmrek" style="border:0;width: 600px;" readonly="true"/>
				 <input type="hidden" id="rekcheck" style="border:0;width: 600px;" readonly="true"/></td>                
            </tr> 
			 <tr>
                <td>Pengirim</td>
                <td></td>
                <td><input id="pengirim" name="pengirim" style="width: 140px;" />
                 <input type="text" id="nmpengirim" style="border:0;width: 600px;" readonly="true"/>
            </tr> 
            <tr>
                <td>Kegiatan</td>
                <td></td>
                <td><input type="text" id="giat" style="width: 140px;" readonly="true"/>
                 </td>                
            </tr> 
			<tr hidden>
                <td>Kunci</td>
                <td></td>
                <td><input type="text" id="kunci" style="width: 140px;" readonly="true"/>
                </td>                
            </tr> 
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
            <tr>
                <td colspan="3" align="center">
                  <button type="primary" id="save" class="easyui-linkbutton" plain="true" onclick="javascript:simpan_terima();"><i class="fa fa-save"></i> Simpan</button>
		              <button type="edit" class="easyui-linkbutton" plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Kembali</button>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>