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
            width: 650,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){  
        
     $('#kode_p').combogrid({  
       panelWidth:500,  
       idField:'kd_program',  
       textField:'kd_program',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_program',  
       columns:[[  
           {field:'kd_program',title:'Kode Program',width:100},  
           {field:'nm_program',title:'Nama Program',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           kd_prog = rowData.kd_program;
           $("#nm_u").attr("value",rowData.nm_program.toUpperCase());
          // muncul();  
           
                          
       }  
     });     
        
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_kegiatan',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_kegiatan',
    		title:'Kode Kegiatan',
    		width:15,
            align:"center"},
    	    {field:'kd_program',
    		title:'Kode program',
    		width:15,
            align:"center"},
            {field:'nm_kegiatan',
    		title:'Nama Kegiatan',
    		width:50},
            {field:'jns_kegiatan',
    		title:'Jenis Kegiatan',
    		width:15}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_k = rowData.kd_kegiatan;
          kd_p = rowData.kd_program;
          nm_k = rowData.nm_kegiatan;
          jns = rowData.jns_kegiatan;
          get(kd_k,kd_p,nm_k,jns); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kd_k,kd_p,nm_k,jns) {
        
        $("#kode").attr("value",kd_k);
        $("#kode_p").combogrid("setValue",kd_p);
        $("#nama").attr("value",nm_k);
        $("#jns_k").combobox("setValue",jns);      
                       
    }
       
    function kosong(){
        $("#kode").attr("value",'');
        $("#kode_p").combogrid("setValue",'');
        $("#nama").attr("value",'');
        $("#jns_k").combobox("setValue",'')
    }
    
    function muncul(){
        var c_prog=kd_prog+'.';
        var c_keg=kd_k
        if(lcstatus=='tambah'){ 
            $("#kode").attr("value",c_prog);
        } else {
        $("#kode").attr("value",c_keg);
        }     
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_kegiatan',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_skpd(){
       
        var ckode = document.getElementById('kode').value;
        var ckode_p= $('#kode_p').combogrid('getValue');
        var cnama = document.getElementById('nama').value;
        var cjns = $('#jns_k').combobox('getValue');
                
        if (ckode==''){
            alert('Kode Golongan Tidak Boleh Kosong');
            exit();
        } 
        if (ckode_p==''){
            alert('Kode Golongan Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama Golongan Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_kegiatan,kd_program,nm_kegiatan,jns_kegiatan)";
            lcvalues = "('"+ckode+"','"+ckode_p+"','"+cnama+"','"+cjns+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'m_giat',kolom:lcinsert,nilai:lcvalues,cid:'kd_kegiatan',lcid:ckode}),
                    dataType:"json"
                });
            });   
           
        } else{
            
            lcquery = "UPDATE m_giat SET nm_kegiatan='"+cnama+"',kd_program='"+ckode_p+"',jns_kegiatan='"+cjns+"' where kd_kegiatan='"+ckode+"'";
            lcquery1 = "UPDATE trskpd SET nm_kegiatan='"+cnama+"' where kd_kegiatan1='"+ckode+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery,st_query1:lcquery1}),
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
        judul = 'Edit Data Kegiatan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Kegiatan';
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
         $.post(urll,({tabel:'m_giat',cnid:ckode,cid:'kd_kegiatan'}),function(data){
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
<h3 align="center"><u><b><a>INPUTAN MASTER KEGIATAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="5%"><a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah();">Tambah</a></td>
        <td width="5%" colspan="2"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
        <td><input type="text" value="" id="txtcari" style="width:300px;"/></td>
        </tr>
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA KEGIATAN" style="width:900px;height:440px;" >  
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
                <td width="30%">KODE PROGRAM</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode_p" style="width:130px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA PROGRAM</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_u" style="width:350px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE KEGIATAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:130px;"/></td>  
            </tr>
                       
            <tr>
                <td width="30%">NAMA KEGIATAN</td>
                <td width="1%">:</td>
                <td><textarea name="nama" id="nama" cols="50" rows="1" ></textarea></td>  
            </tr>
            <tr>
                <td width="30%">JENIS</td>
                <td width="1%">:</td>
                <td><input id="jns_k" style="width:250px;" class="easyui-combobox" data-options="
            		valueField: 'value',
            		textField: 'label',
            		data: [{
            			label: '',
            			value: ''
            		},{
            			label: 'PENDAPATAN',
            			value: '4'
            		},{
            			label: 'BELANJA TIDAK LANGSUNG',
            			value: '51'
            		},{
            			label: 'BELANJA LANGSUNG',
            			value: '52'
            		}]"/>
                </td>  
                
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