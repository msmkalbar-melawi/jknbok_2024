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
            width: 600,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){  
        
     $('#kode_u').combogrid({  
       panelWidth:500,  
       idField:'kd_urusan',  
       textField:'kd_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_urusan',  
       columns:[[  
           {field:'kd_urusan',title:'Kode Urusan',width:100},  
           {field:'nm_urusan',title:'Nama Urusan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_urus = rowData.kd_urusan;
            $("#nm_u").attr("value",rowData.nm_urusan.toUpperCase());
            //muncul();                
       }  
     });     
        
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_skpd',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_skpd',
    		title:'Kode SKPD',
    		width:15,
            align:"center"},
    	    {field:'kd_urusan',
    		title:'Kode urusan',
    		width:15,
            align:"center"},
            {field:'nm_skpd',
    		title:'Nama SKPD',
    		width:50},
            {field:'npwp',
    		title:'NPWP',
    		width:15}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_s = rowData.kd_skpd;
          kd_u = rowData.kd_urusan;
          nm_s = rowData.nm_skpd;
          npwp = rowData.npwp;
          rek = rowData.rekening;
          get(kd_s,kd_u,nm_s,npwp,rek); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
          lcidx = rowIndex;
          judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kd_s,kd_u,nm_s,npwp,rek) {
        
        $("#kode").attr("value",kd_s);
        $("#kode_u").combogrid("setValue",kd_u);
        $("#nama").attr("value",nm_s);
        $("#npwp").attr("value",npwp); 
        $("#reke").attr("value",rek);     
                       
    }
       
    function kosong(){
        $("#kode").attr("value",'');
        $("#kode_u").combogrid("setValue",'');
        $("#nama").attr("value",'');
        $("#npwp").attr("value",'');
        $("#reke").attr("value",''); 
    }
    
    function muncul(){
        //alert(kd_s);
        var c_urus=kd_urus+'.';
        var c_skpd=kd_s;
        if(lcstatus=='tambah'){ 
            $("#kode").attr("value",c_urus);
        } else {
            $("#kode").attr("value",c_skpd);
        }     
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_skpd',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_skpd(){
       
        var ckode = document.getElementById('kode').value;
        var ckode_u= $('#kode_u').combogrid('getValue');
        var cnama = document.getElementById('nama').value;
        var cnpwp = document.getElementById('npwp').value;
        var crek = document.getElementById('reke').value;
                
        if (ckode==''){
            alert('Kode Golongan Tidak Boleh Kosong');
            exit();
        } 
        if (ckode_u==''){
            alert('Kode Golongan Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama Golongan Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_skpd,kd_urusan,nm_skpd,npwp,rekening)";
            lcvalues = "('"+ckode+"','"+ckode_u+"','"+cnama+"','"+cnpwp+"','"+crek+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_skpd',kolom:lcinsert,nilai:lcvalues,cid:'kd_skpd',lcid:ckode}),
                    dataType:"json"
                });
            });   
           
        } else{
            
            lcquery = "UPDATE ms_skpd SET nm_skpd='"+cnama+"',kd_urusan='"+ckode_u+"',npwp='"+cnpwp+"',rekening='"+crek+"' where kd_skpd='"+ckode+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json"
            });
            });
            
            
        }
        
        
        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        lcstatus = 'edit';
     }    
     

     
     function hapus(){
        var ckode = document.getElementById('kode').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_skpd',cnid:ckode,cid:'kd_skpd'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
            }
         });
        });    
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
<h3 align="center"><u><b><a>INPUTAN MASTER SKPD</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
            <td width="5%" colspan=""><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah</a></td>
            <td width="5%" colspan=""><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
            <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
            <td colspan="4">
            <table id="dg" title="LISTING DATA SKPD" style="width:900px;height:440px;" >  
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
                <td width="30%">KODE URUSAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode_u" style="width:50px;"/><input type="text" id="nm_u" style="width:310px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:100px;"/></td>  
            </tr>
                       
            <tr>
                <td width="30%">NAMA SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NPWP</td>
                <td width="1%">:</td>
                <td><input type="text" id="npwp" style="width:200px;"/></td>  
            </tr>
            <tr>
                <td width="30%">REKENING</td>
                <td width="1%">:</td>
                <td><input type="text" id="reke" style="width:200px;"/></td>  
            </tr>
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_skpd();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>