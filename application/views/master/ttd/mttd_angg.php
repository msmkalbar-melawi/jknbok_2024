<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>   
   
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css"/>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    
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
            height: 350,
            width: 650,
            modal: true,
            autoOpen:false
        });
					get_skpd();

        });    
     
     $(function(){ 
     /*   
     $('#dinas').combogrid({  
       panelWidth:500,  
       idField:'kd_skpd',  
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_skpd',  
       columns:[[  
           {field:'kd_skpd',title:'Kode SKPD',width:100},  
           {field:'nm_skpd',title:'Nama SKPD',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_u").attr("value",rowData.nm_skpd.toUpperCase());
          // $("#kode").attr("value",rowData.kd_urusan.toUpperCase()+'.');                
       }  
     });   
     */
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_ttd_angg',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'nip',
    		title:'NIP',
    		width:10,
            align:"center"},
            {field:'nama',
    		title:'Nama',
    		width:10},
            {field:'jabatan',
    		title:'Jabatan',
    		width:15},
            {field:'kd_skpd',
    		title:'SKPD',
    		width:5,
            align:"center"},
            {field:'kode',
    		title:'Kode',
    		width:5,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nip = rowData.nip;
          nm = rowData.nama;
          jab = rowData.jabatan;
          pang = rowData.pangkat;
          dns = rowData.kd_skpd;
          kd = rowData.kode;
          get(nip,nm,jab,pang,dns,kd); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Penandatangan'; 
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
				$("#dinas").attr("value",data.kd_skpd);
				$("#nm_u").attr("value",data.nm_skpd);
				 }	
        	});  
        }
    
    function get(nip,nm,jab,pang,dns,kd) {
        
        $("#nip").attr("value",nip);
        $("#no_simpan").attr("value",nip);
        $("#nama").attr("value",nm); 
        $("#dinas").attr("value",dns); 
        $("#jabat").attr("value",jab);
        $("#pang").attr("value",pang);
        $("#kd").combobox("setValue",kd);    
                       
    }
       
    function kosong(){
        $("#nip").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#nama").attr("value",''); 
        $("#jabat").attr("value",'');
        $("#pang").attr("value",'');
        $("#kd").combobox("setValue",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_ttd_angg',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_ttd(){
         //alert(jaka);
        var cnip = document.getElementById('nip').value;
        var no_simpan = document.getElementById('no_simpan').value;
        var cnama = document.getElementById('nama').value;
        var cdinas =  document.getElementById('dinas').value;
        var cjabat = document.getElementById('jabat').value;
        var cpang = document.getElementById('pang').value;
        var ckode = $('#kd').combobox('getValue');
        var cbidang = '1';
       // alert(cnip+'/'+cnama+'/'+cdinas+'/'+cjabat+'/'+cpang+'/'+ckode);
        // alert("sasa");      
        if (cnip==''){
            alert('NIP  Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama  Tidak Boleh Kosong');
            exit();
        }
        if (ckode==''){
            alert('Kode  Tidak Boleh Kosong');
            exit();
        }
	

        
      	if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cnip,jabat:ckode,tabel:'ms_ttd',field:'nip',field2:'kode'}),
                    url: '<?php echo base_url(); ?>/index.php/master/cek_simpan_ttd',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nip").focus();
						exit();
						} 
						if(status_cek==0){
						//alert("Nomor Bisa dipakai");
            
            lcinsert = "(nip,nama,jabatan,pangkat,kd_skpd,kode,bidang)";
            lcvalues = "('"+cnip+"','"+cnama+"','"+cjabat+"','"+cpang+"','"+cdinas+"','"+ckode+"','"+cbidang+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_ttd',kolom:lcinsert,nilai:lcvalues,cid:'nip',lcid:cnip}),
                    dataType:"json",
					success:function(data){                        
                         if (data == 2){              
                            alert('Data Berhasil Tersimpan...!!!');
							lcstatus = 'edit;'
							$("#no_simpan").attr("value",cnip);
                            
                        } else{ 
                            alert('Nip Telah Dipakai. Coba tambahkan spasi!. Data Gagal Tersimpan...!!!');
                        }                                             
                    }
                });
            });   
           
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
                    data: ({no:cnip,jabat:ckode,tabel:'ms_ttd',field:'nip',field2:'kode'}),
                    url: '<?php echo base_url(); ?>/index.php/master/cek_simpan_ttd',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cnip!=no_simpan){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cnip==no_simpan){
						alert("Nomor Bisa dipakai");
						
		//-----
            lcquery = "UPDATE ms_ttd SET nama='"+cnama+"',jabatan='"+cjabat+"',pangkat='"+cpang+"',kd_skpd='"+cdinas+"',kode='"+ckode+"' ,nip='"+cnip+"' where nip='"+no_simpan+"' AND kd_skpd='"+cdinas+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json",
				success:function(data){                        
                         if (data == 2){              
                            alert('Data Berhasil Tersimpan...!!!');
							lcstatus = 'edit;'
							$("#no_simpan").attr("value",cnip);
                        } else{ 
                            alert('Data Gagal Tersimpan...!!!');
                        }                                             
                    }
            });
            });
        }
        }
		
		});
		});
        }   
        alert('Refresh Data...!!!');     
        keluar();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penandatangan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nip").disabled=true;
        }    
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Penandatangan';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nip").disabled=false;
        document.getElementById("nip").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 
     }    
    
     function hapus(){
        var ckode = $('#kd').combobox('getValue');
        var cnip = document.getElementById('nip').value;
        var cbidang = '1';
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master_ttd';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_ttd',cnid:cnip,cid:'nip',kode:ckode,cbidang:cbidang}),function(data){
            status = data;
            if (status==1){
                $('#dg').datagrid('deleteRow',lcidx);   
					alert('Data Berhasil Dihapus..!!');
					exit();
				} else {
					alert('Gagal Hapus..!!');
					exit();
				}
         });
        }); 
        keluar();   
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
<h3 align="center"><u><b><a>INPUTAN MASTER PENANDATANGAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%">
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a></td>               
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA PENANDATANGAN" style="width:900px;height:365px;" >  
        </table>
        </td>
        </tr>
    </table>    
    
        
 
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
           <tr>
                <td width="30%">NIP</td>
                <td width="1%">:</td>
                <td><input type="text" id="nip" style="width:200px;"/> &nbsp; &nbsp; &nbsp; <input type="hidden" id="no_simpan" style="width:100px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">NAMA </td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">Jabatan </td>
                <td width="1%">:</td>
                <td><input type="text" id="jabat" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">Pangkat </td>
                <td width="1%">:</td>
                <td><input type="text" id="pang" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="dinas" style="width:100px;"/></td>  
            </tr> 
            <tr>
                <td width="30%"></td>
                <td width="1%"></td>
                <td><input type="text" id="nm_u" style="width:400px;"/></td>  
            </tr> 
            <tr>
                <td width="30%">KODE</td>
                <td width="1%">:</td>
                <td><input id="kd" style="width:250px;" class="easyui-combobox" data-options="
            		valueField: 'value',
            		textField: 'label',
            		data: [{
            			label: '',
            			value: ''
            		},{
            			label: 'Kuasa / Pengguna Anggaran',
            			value: 'PA'
            		},{
            			label: 'Bendahara Pengeluaran',
            			value: 'BK'
            		},{
            			label: 'Bendahara Penerimaan',
            			value: 'BP'
            		},{
            			label: 'PPTK',
            			value: 'PPTK'
            		},{
            			label: 'PPK',
            			value: 'PPK'
            		},{
            			label: 'BUD',
            			value: 'BUD'
            		},{
            			label: 'PPKD',
            			value: 'PPKD'
            		},{
            			label: 'Setda',
            			value: 'SETDA'
            		},{
            			label: 'Kepala Daerah',
            			value: 'KD'
            		}]"/>
                </td>  
                
            </tr>
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_ttd();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>