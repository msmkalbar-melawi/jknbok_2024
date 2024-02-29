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
        });    
     
     $(function(){ 
        
      $('#nip1').combogrid({  
           panelWidth:700,  
           idField:'nip',  
           textField:'nip',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_ttd',  
           columns:[[  
               {field:'nip',title:'NIP',width:100},  
               {field:'nama',title:'NAMA',width:200},
               {field:'jabatan',title:'JABATAN',width:200},
               {field:'kd_skpd',title:'KD SKPD',width:50}    
			   
           ]],  
           onSelect:function(rowIndex,rowData){              
               $("#nama1").attr("value",rowData.nama);
               $("#jabatan1").attr("value",rowData.jabatan);               
               $("#kd_skpd").attr("value",rowData.kd_skpd);               
           }  
       });    
     
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_tapd',
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
    		title:'KD SKPD',
    		width:15}
        ]],
        onSelect:function(rowIndex,rowData){
          nip = rowData.nip;
          nm = rowData.nama;
          jab = rowData.jabatan;
          kd_skpd = rowData.kd_skpd;
          get(nip,nm,jab,kd_skpd); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data TAPD'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(nip,nm,jab,kd_skpd) {
        
        $("#nip1").combogrid("setValue",nip);
        $("#nama1").attr("value",nm); 
        $("#jabatan").attr("value",jab);
        $("#kd_skpd").attr("value",kd_skpd);
       
                       
    }
       
    function kosong(){
        $("#nip1").attr("value",'');
        $("#nama1").attr("value",''); 
        $("#jabatan1").attr("value",'');
        $("#kd_skpd").attr("value",'');
      
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_tapd',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_tapd(){
         //alert(jaka);
        var cnip = $('#nip1').combogrid('getValue');
        var cnama = document.getElementById('nama1').value;
        var cjabat = document.getElementById('jabatan1').value;
        var ckdskpd = document.getElementById('kd_skpd').value;
                
        if (cnip==''){
            alert('NIP  Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama  Tidak Boleh Kosong');
            exit();
        }
       

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(nip,nama,jabatan,kd_skpd)";
            lcvalues = "('"+cnip+"','"+cnama+"','"+cjabat+"','"+ckdskpd+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_tapd',
                    data: ({tabel:'tapd',kolom:lcinsert,nilai:lcvalues,lcid:cnip}),
                    dataType:"json"
                });
            });   
           
        } else{
            
            lcquery = "UPDATE tapd SET nama='"+cnama+"',jabatan='"+cjabat+"',kd_skpd='"+cskpd+"'  where nip='"+cnip+"'";

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
     }    
    
     function hapus(){
        var cnip = $('#nip1').combogrid('getValue');
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'tapd',cnid:cnip,cid:'nip'}),function(data){
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
<h3 align="center"><u><b><a>INPUTAN TIM ANGGARAN PEMERINTAH DAERAH</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
		<td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:tambah();">Tambah</a></td>
        <td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA TAPD" style="width:900px;height:365px;" >  
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
                <td><input type="text" id="nip1" style="width:100px;"/></td>  
            </tr>            
            <tr>
                <td width="30%">NAMA </td>
                <td width="1%">:</td>
                <td><input type="text" id="nama1" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">Jabatan </td>
                <td width="1%">:</td>
                <td><input type="text" id="jabatan1" style="width:360px;"/></td>  
            </tr>
			<tr>
                <td width="30%">Kode SKPD </td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_skpd" style="width:360px;"/></td>  
            </tr>
            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_tapd();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>