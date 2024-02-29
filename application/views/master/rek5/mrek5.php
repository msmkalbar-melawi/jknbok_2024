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
            height: 390,
            width: 600,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){  
        
     $('#rek64').combogrid({  
       panelWidth:500,  
       idField:'kd_rek4',  
       textField:'kd_rek4',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening4_64',  
       columns:[[  
           {field:'kd_rek4',title:'Kode Rekening',width:100},  
           {field:'nm_rek4',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_rek64 = rowData.kd_rek4;
            $("#nm_64").attr("value",rowData.nm_rek4.toUpperCase());
           // muncul();                
       }  
     });
     
     $('#rek4').combogrid({  
       panelWidth:500,  
       idField:'kd_rek4',  
       textField:'kd_rek4',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_rekening4',  
       columns:[[  
           {field:'kd_rek4',title:'Kode Rekening',width:100},  
           {field:'nm_rek4',title:'Nama Rekening',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_rek4 = rowData.kd_rek4;
            $("#nm_u").attr("value",rowData.nm_rek4.toUpperCase());
           // muncul();                
       }  
     });     
        
        
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/master/load_rekening5',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_rek5',
    		title:'Kode Rek 21',
    		width:10,
            align:"center"},
            {field:'nm_rek5',
    		title:'Nama Rek 21',
    		width:35},
    	    {field:'kd_rek64',
    		title:'Kode Rek 64',
    		width:10,
            align:"center"},           
            {field:'nm_rek64',
    		title:'Nama Rek 64',
    		width:35}//,
//            {field:'map_lo',
//    		title:'MAP LO',
//    		width:15,
//            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_s = rowData.kd_rek5;
          kd_64 = rowData.kd_rek64;
          kd_u_64 = rowData.kd_rek4_64;
          kd_u = rowData.kd_rek4;
          nm_s = rowData.nm_rek5;
          nm_64 = rowData.nm_rek64;
          lo = rowData.map_lo;
          lra = rowData.map_lra1;
          get(kd_s,kd_u,kd_u_64,nm_s,lo,lra,kd_64,nm_64); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kd_s,kd_u,kd_u_64,nm_s,lo,lra,kd_64,nm_64) {
        
        $("#kode").attr("value",kd_s);
        $("#kode1").attr("value",kd_s);
        $("#kode64").attr("value",kd_64);
        $("#kode1_64").attr("value",kd_64);
        $("#rek4").combogrid("setValue",kd_u);
        $("#rek64").combogrid("setValue",kd_u_64);
        $("#nama").attr("value",nm_s);
        $("#nama64").attr("value",nm_64);  
        $("#lra").attr("value",lra);
        $("#lo").attr("value",lo);  
                       
    }
       
    function kosong(){
        $("#kode").attr("value",'');
        $("#kode64").attr("value",'');
        $("#rek4").combogrid("setValue",'');
        $("#rek64").combogrid("setValue",'');
        $("#nama").attr("value",'');
        $("#nama64").attr("value",'');
        $("#lra").attr("value",'');
        $("#lo").attr("value",'');
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
		url: '<?php echo base_url(); ?>/index.php/master/load_rekening5',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_rek5(){
       
        var ckode = document.getElementById('kode').value;
        var ckode1 = document.getElementById('kode1').value;
        var ckode64 = document.getElementById('kode64').value;
        var ckode1_64 = document.getElementById('kode1_64').value;
        var crek4= $('#rek4').combogrid('getValue');
        var crek64= $('#rek64').combogrid('getValue');
        var cnama = document.getElementById('nama').value;
        var cnama64 = document.getElementById('nama64').value;
        var clo = document.getElementById('lo').value;
        var clra = document.getElementById('lra').value;
        if (ckode==''){
            alert('Kode  Tidak Boleh Kosong');
            exit();
        } 
        if (crek4==''){
            alert('Kode  Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama  Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_rek5,kd_rek4,kd_rek4_64,nm_rek5,map_lra1,map_lo,kd_rek64,nm_rek64)";
            lcvalues = "('"+ckode+"','"+crek4+"','"+crek64+"','"+cnama+"','"+clra+"','"+clo+"','"+ckode64+"','"+cnama64+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_rek5',kolom:lcinsert,nilai:lcvalues,cid:'kd_rek64',lcid:ckode64}),
                    dataType:"json",
                    success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else if(status=='1'){
                            alert('Data Sudah Ada..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
                });
            });   
           
        } else{
            
            lcquery = "UPDATE ms_rek5 SET kd_rek5='"+ckode+"',nm_rek5='"+cnama+"',kd_rek64='"+ckode64+"',nm_rek64='"+cnama64+"',kd_rek4_64='"+crek64+"',kd_rek4='"+crek4+"',map_lra1='"+clra+"',map_lo='"+clo+"' where kd_rek64='"+ckode64+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
                data: ({st_query:lcquery}),
                dataType:"json",
                success:function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        }else{
                            alert('Data Tersimpan..!!');
                            exit();
                        }
                    }
            });
            });
            
            
        }
        
        
        alert("Data Berhasil disimpan");
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Rincian Objek';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("kode").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Rincian Objek';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        //lcstatus = 'edit';
     }    
     

     
     function hapus(){
        var ckode = document.getElementById('kode64').value;
        
        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
        $(document).ready(function(){
         $.post(urll,({tabel:'ms_rek5',cnid:ckode,cid:'kd_rek64'}),function(data){
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
<h3 align="center"><u><b><a>INPUTAN MASTER REKENING RINCIAN OBJEK</a></b></u></h3>
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
        <table id="dg" title="LISTING DATA REKENING RINCIAN OBJEK" style="width:900px;height:440px;" >  
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
                <td width="30%">KODE OBJEK ANGGARAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="rek4" style="width:100px;"/><input type="text" id="nm_u" style="width:310px;"/></td>  
            </tr> 
            <tr>
                <td width="30%">KODE OBJEK REALISASI</td>
                <td width="1%">:</td>
                <td><input type="text" id="rek64" style="width:100px;"/><input type="text" id="nm_64" style="width:310px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE REKENING ANGGARAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:100px;"/><input type="hidden" id="kode1" style="width: 140px;" readonly="true"/></td>  
            </tr>
            <tr>
                <td width="30%">KODE REKENING REALISASI</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode64" style="width:100px;"/><input type="hidden" id="kode1_64" style="width: 140px;" readonly="true"/></td>  
            </tr>
            <tr>
                <td width="30%">MAP AKRUAL</td>
                <td width="1%">:</td>
                <td><input type="text" id="lo" style="width:100px;"/></td>  
            </tr> 
            <tr>
                <td width="30%">MAP LRA</td>
                <td width="1%">:</td>
                <td><input type="text" id="lra" style="width:100px;"/></td>  
            </tr>           
            <tr>
                <td width="30%">NAMA REKENING</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama" style="width:360px;"/></td>  
            </tr>
            <tr>
                <td width="30%">NAMA REKENING 64</td>
                <td width="1%">:</td>
                <td><input type="text" id="nama64" style="width:360px;"/></td>  
            </tr>
          
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_rek5();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>