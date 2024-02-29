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
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/tukd_bank/load_penerima',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'kodeBank',
    		title:'Kode Bank',
    		width:15,
            align:"center"},
			{field:'namaBank',
    		title:'Nama Bank',
    		width:20,
            align:"left"},
            {field:'namaPenerima',
    		title:'Nama Penerima',
    		width:20,
			align:"left"},
			{field:'noAkun',
    		title:'No Rekening',
    		width:20,
			align:"left"},
			{field:'npwp',
    		title:'NPWP',
    		width:20,
			align:"left"}
        ]],
        onSelect:function(rowIndex,rowData){
          kdbank = rowData.kodeBank;
          nmbank = rowData.namaBank;
		  norek = rowData.noAkun;
		  nmpenerima = rowData.namaPenerima;
		  npwp = rowData.npwp;
          get(kdbank,nmbank,norek,nmpenerima,npwp); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Bank'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kdbank,nmbank,norek,nmpenerima,npwp) {
        
        $("#kode").attr("value",kdbank);
        $("#nama").attr("value",nmbank);     
        $("#norek").attr("value",norek);
		$("#nmpenerima").attr("value",nmpenerima);
		$("#npwp").attr("value",npwp);		
    }
       
    function kosong(){
        $("#kode").attr("value",'PDKBIDJ1');
        $("#nama").attr("value",'BANK KALBAR');
		$("#norek").attr("value",'');
		$("#nmpenerima").attr("value",'');
		$("#npwp").attr("value",'');
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_bank',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
    function simpan_rek1(){
       //alert('Berhasil Input Daftar Penerima');
	   //exit();
        var ckode = document.getElementById('kode').value;
        var cnama = document.getElementById('nama').value;
		var cnorek = document.getElementById('norek').value;
		var cnmpenerima = document.getElementById('nmpenerima').value;
		var cnpwp = document.getElementById('npwp').value;
        var cfield='noAkun'  ;      
        if (ckode==''){
            alert('Kode  Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama  Tidak Boleh Kosong');
            exit();
        }
		if (cnorek==''){
            alert('Rekening Tidak Boleh Kosong');
            exit();
        }
		if (cnmpenerima==''){
            alert('Penerima Tidak Boleh Kosong');
            exit();
        }
        if (cnpwp==''){
            alert('NPWP Tidak Boleh Kosong');
            exit();
        }
		
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kodeBank,namaBank,namaPenerima,noAkun,npwp)";
            lcvalues = "('"+ckode+"','"+cnama+"','"+cnmpenerima+"','"+cnorek+"','"+cnpwp+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/tukd_bank/simpan_penerima',
                    data: ({tabel:'ms_penerima',kolom:lcinsert,nilai:lcvalues,cid:cfield,lcid:cnorek,ckode:ckode,cnama:cnama,cnorek:cnorek,cnmpenerima:cnmpenerima,cnpwp:cnpwp}),
                    dataType:"json",
					success:function(data)
					{
						$a=data;
						if($a=='3'){							
							alert('Rekening Tidak Terdaftar di Bank/Sudah terdafter');
						}else if($a=='1'){
							alert('Sudah Pernah diDaftarkan');
						}else{
							alert('Berhasil Input Daftar Penerima');
						}
						//document.write(data);
					} 
                });
            });   
           
        } else{
            
            lcquery = "UPDATE ms_penerima SET namaPenerima='"+cnmpenerima+"',npwp='"+cnpwp+"',namaBank='"+cnama+"',kodeBank="+ckode+"' where noAkun='"+cnorek+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json"
            });
            });
            
            
        }
        
        
        //alert("Data Berhasil disimpan");
        //$("#dialog-modal").dialog('close');
        //$('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Penerima';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("norek").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Penerima';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
        var ckode = document.getElementById('norek').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_penerima',cnid:ckode,cid:'noAkun'}),function(data){
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
<h3 align="center"><u><b><a>INPUTAN DAFTAR REKENING PENERIMA </a></b></u></h3>
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
        <table id="dg" title="LISTING DATA BANK" style="width:900px;height:365px;" >  
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
                <td width="30%">KODE BANK</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:100px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">NAMA BANK</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:360px;"/></td>  
            </tr>
			<tr>
                <td width="30%">NO REKENING</td>
                <td width="1%">:</td>
                <td><input type="text" id="norek" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA PENERIMA</td>
                <td width="1%">:</td>
                <td><input type="text" id="nmpenerima" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">npwp</td>
                <td width="1%">:</td>
                <td><input type="text" id="npwp" style="width:360px;"/></td>  
            </tr>
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_rek1();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>