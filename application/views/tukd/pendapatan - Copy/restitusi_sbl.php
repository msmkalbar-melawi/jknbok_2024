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
         get_skpd(); 
		 get_tahun();
		 document.getElementById("pesan").innerHTML="";
        });    
    
     
     $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd/load_restitusi',
        idField      : 'id',            
        rownumbers   : "true", 
        fitColumns   : "true",
        singleSelect : "true",
        autoRowHeight: "false",
        loadMsg      : "Tunggu Sebentar....!!",
        pagination   : "true",
        nowrap       : "true",                       
        columns:[[
    	    {field:'no_sts',
    		title:'Nomor Terima',
    		width:50,
            align:"center"},
            {field:'tgl_sts',
    		title:'Tanggal',
    		width:30},
            {field:'kd_skpd',
    		title:'S K P D',
    		width:30,
            align:"center"},
            {field:'nilai',
    		title:'Nilai',
    		width:50,
            align:"right"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor     = rowData.no_sts;
          tgl       = rowData.tgl_sts;
          kode      = rowData.kd_skpd;
          lcket     = rowData.keterangan;
          lcnilai   = rowData.nilai;
          giat	    = rowData.kd_kegiatan;
          kd_rek5	= rowData.kd_rek5;
          nm_rek5	= rowData.nm_rek5;
          sumber	= rowData.sumber;
          no_cek	= rowData.no_cek;
		  lcidx     = rowIndex;
		  get(nomor,tgl,kode,lcket,lcnilai,giat,kd_rek5,nm_rek5,sumber,no_cek);   
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
		
		$('#pengirim').combogrid({
           panelWidth:700,  
           idField:'kd_pengirim',  
           textField:'kd_pengirim',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/tukd/load_pengirim',             
           columns:[[  
               {field:'kd_pengirim',title:'Kode Pengirim',width:140},  
               {field:'nm_pengirim',title:'Nama Pengirim',width:700}
           ]],  
           onSelect:function(rowIndex,rowData){
               kd_pengirim = rowData.kd_pengirim;
               $("#nmpengirim").attr("value",rowData.nm_pengirim);                                      
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
           url        : '<?php echo base_url(); ?>index.php/tukd/ambil_rek_tetap/'+kode,             
           columns    : [[  
		       {field:'kd_rek5',title:'Kode Rek LRA',width:100},  
               {field:'kd_rek',title:'Kode Rek LO',width:100},
			   {field:'nm_rek',title:'Uraian Rinci',width:200},
			   {field:'nm_rek4',title:'Uraian Obyek',width:200},
                {field:'kd_kegiatan',title:'Kegiatan',width:500}
              ]],
               onSelect:function(rowIndex,rowData){
               $("#nmrek").attr("value",rowData.nm_rek.toUpperCase());
               $("#rek1").attr("value",rowData.kd_rek5);
			   $("#rekcheck").attr("value",rowData.kd_rek);
               $("#giat").attr("value",rowData.kd_kegiatan);
              }    
            });
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
       
     function get(nomor,tgl,kode,lcket,lcnilai,giat,kd_rek5,nm_rek5,sumber,no_cek){
	    
		$("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#pengirim").combogrid("setValue",sumber);
		$("#rek1").attr("Value",kd_rek5);
		$("#nmrek").attr("Value",nm_rek5);
		$("#giat").attr("Value",giat);
        $("#nilai").attr("value",lcnilai);
		$("#ket").attr("value",lcket);
		$("#no_cek").attr("value",no_cek);
		$('#save').linkbutton('disable');
		
		if(no_cek==1){
			$('#del').linkbutton('disable');
		}else{
			$('#del').linkbutton('enable');
		}
		
	}
    
    
    function kosong(){
        $("#nomor").attr("value",'');
        $("#tanggal").datebox("setValue",'');
		$("#nilai").attr("value",'');        
        $("#pengirim").combogrid("setValue",'');
        $("#rek1").attr("Value",'');
        $("#nmrek").attr("value",'');
		$("#giat").attr("Value",'');
        $("#ket").attr("value",'');
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
        var ctgl     = $('#tanggal').datebox('getValue');
        var cskpd    = document.getElementById('skpd').value;
        var cnmskpd  = document.getElementById('nmskpd').value ;
        var rek      = document.getElementById('rek1').value;
		var cpengirim = $('#pengirim').combogrid('getValue');
        var kegi      = document.getElementById('giat').value;
        var lcket    = document.getElementById('ket').value;
        var lntotal  = angka(document.getElementById('nilai').value);
            lctotal  = number_format(lntotal,0,'.',',');
		var tahun_input = ctgl.substring(0, 4);
		
		if (tahun_input != tahun_anggaran){
			swal("Error", "Tahun tidak sama dengan tahun Anggaran", "error");
			exit();
		}
		
        if (cno==''){
			swal("Error", "Nomor  Tidak Boleh Kosong", "warning");
            exit();
        } 
		
		if (cpengirim==''){
			swal("Error", "Pengirim Tidak Boleh Kosong", "warning");
            exit();
        } 
		
		if (rek==''){
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
		
        if ( lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'trhkasin_pkd',field:'no_sts'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						swal("Error", "Nomor Telah Dipakai!", "warning");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						//mulai
           
            lcinsert        = " ( no_sts, tgl_sts, kd_skpd, kd_kegiatan, total, keterangan, jns_trans) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+cskpd+"', '"+kegi+"', '"+lntotal+"', '"+lcket+"', '3') ";
			
			lcinsert2        = " ( no_sts, kd_skpd, kd_kegiatan, rupiah, kd_rek5,sumber) ";
            lcvalues2        = " ( '"+cno+"','"+cskpd+"', '"+kegi+"', '"+lntotal+"', '"+rek+"','"+cpengirim+"') ";
            
			
            $(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_resti',
                    data     : ({tabel       :'trhkasin_pkd',tabel2       :'trdkasin_pkd',  kolom       :lcinsert,   kolom2       :lcinsert2,        nilai       :lcvalues, nilai2       :lcvalues2,        cid       :'no_sts',   lcid       :cno}),
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
		/* $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_terima',field:'no_terima'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=cno_hide){
						swal("Error", "Nomor telah dipakai", "warning");
						exit();
						} 
						if(status_cek==0 || cno==cno_hide){
						alert("Nomor Bisa dipakai");
			//mulai	
            
           lcinsert        = " ( no_terima, tgl_terima, no_tetap,     tgl_tetap,       sts_tetap,     kd_skpd,  kd_kegiatan,   kd_rek5,   kd_rek_lo,     nilai,         keterangan, jenis, sumber  ) ";
            lcvalues        = " ( '"+cno+"', '"+ctgl+"', '"+ctetap+"', '"+ctgltetap+"', '"+cstatus+"', '"+cskpd+"', '"+kegi+"',  '"+rek+"', '"+lckdrek+"', '"+lntotal+"', '"+lcket+"', '1', '"+cpengirim+"' ) ";
            
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_terima_ag',
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
		}); */
		
		  swal("Error", "Simpan Gagal", "warning");
          exit();
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
        var nobkt = rows.no_sts;
                
        var tanya = confirm('Apakah Data Nomor Terima '+nobkt+' Akan Di Hapus ???') ;
        
        if ( tanya == true ) {
        
            var urll  = '<?php echo base_url(); ?>index.php/tukd/hapus_restitusi';
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
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN RESTITUSI</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();kosong()">Tambah</a>               
        <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="Listing data Restitusi" style="width:870px;height:450px;" >  
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
                <td>No. Terima</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/></td>  
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
                <td><input id="rek" name="rek" style="width: 140px;" /> <input id="rek1" style="border:0;width: 140px;" readonly="true"/>
                 <input type="text" id="nmrek" style="border:0;width: 600px;" readonly="true"/></td>            
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
                <td>No Cek</td>
                <td></td>
                <td><input type="text" id="no_cek" style="width: 140px;" readonly="true"/>
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
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_terima();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>