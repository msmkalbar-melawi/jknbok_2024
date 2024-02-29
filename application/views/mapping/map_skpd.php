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
            height: 400,
            width: 800,
            modal: true,
            autoOpen:false
        });
        });    
     
     $(function(){  
        
     $('#kode').combogrid({  
       panelWidth:500,  
       idField:'kd_skpd',  
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_skpd',  
       columns:[[  
           {field:'kd_skpd',title:'Kode skpd',width:100},  
           {field:'nm_skpd',title:'Nama skpd',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nama").attr("value",rowData.nm_skpd.toUpperCase());                
       }  
     }); 


     $('#kd_u1').combogrid({  
       panelWidth:500,  
       idField:'kd_bidang_urusan',  
       textField:'kd_bidang_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_urusan90',  
       columns:[[  
           {field:'kd_bidang_urusan',title:'Kode Urusan',width:100},  
           {field:'nm_bidang_urusan',title:'Nama Urusan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_u1").attr("value",rowData.nm_bidang_urusan.toUpperCase());                
       }  
     }); 
     $('#kd_u2').combogrid({  
       panelWidth:500,  
       idField:'kd_bidang_urusan',  
       textField:'kd_bidang_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_urusan90',  
       columns:[[  
           {field:'kd_bidang_urusan',title:'Kode Urusan',width:100},  
           {field:'nm_bidang_urusan',title:'Nama Urusan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_u2").attr("value",rowData.nm_bidang_urusan.toUpperCase());                
       }  
     }); 
     $('#kd_u3').combogrid({  
       panelWidth:500,  
       idField:'kd_bidang_urusan',  
       textField:'kd_bidang_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/ambil_urusan90',  
       columns:[[  
           {field:'kd_bidang_urusan',title:'Kode Urusan',width:100},  
           {field:'nm_bidang_urusan',title:'Nama Urusan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_u3").attr("value",rowData.nm_bidang_urusan.toUpperCase());                
       }  
     });     
        
        
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_skpd',
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
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:50},
            {field:'kode90',
            title:'Kode SKPD 90',
            width:15,
            align:"center"},
            {field:'nama90',
            title:'Nama SKPD 90',
            width:50}
        ]],
        onSelect:function(rowIndex,rowData){
          kdskpd  = rowData.kd_skpd;
          nama    = rowData.nm_skpd;
          kd_u1   = rowData.kd_u1;
          kd_u2   = rowData.kd_u2;
          kd_u3   = rowData.kd_u3;
          kd_u4   = rowData.kd_u4;
          kd_u5   = rowData.kd_u5;
          nm_u4   = rowData.nama90;
          get(kdskpd,nama,kd_u1,kd_u2,kd_u3,kd_u4,kd_u5,nm_u4); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kdskpd,nama,kd_u1,kd_u2,kd_u3,kd_u4,kd_u5,nm_u4) {
        
        
        $("#kode").combogrid("setValue",kdskpd);
        $("#nama").attr("value",nama);
        
        if (kd_u1=='0-00'){
          $("#kd_u1").attr("value",'');
        }else{
          $("#kd_u1").combogrid("setValue",kd_u1.replace('-','.'));
        }
        if (kd_u2=='0-00'){
          $("#kd_u2").attr("value",'');
        }else{
          $("#kd_u2").combogrid("setValue",kd_u2.replace('-','.'));
        }
        if (kd_u3=='0-00'){
          $("#kd_u3").attr("value",'');
        }else{
          $("#kd_u3").combogrid("setValue",kd_u3.replace('-','.'));
        }

        

        $("#kd_u4").attr("value",kd_u4);
        $("#kd_u5").attr("value",kd_u5);
        $("#nm_4").attr("value",nm_u4);     
                       
    }
       
    function kosong(){
        $("#kd_u5").attr("value",'');
        $("#kd_u4").attr("value",'');
        $("#kd_u3").attr("value",'');
        $("#kd_u2").attr("value",'');
        $("#kd_u1").attr("value",'');
        $("#nm_u1").attr("value",'');
        $("#nm_u2").attr("value",'');
        $("#nm_u3").attr("value",'');
        $("#nm_4").attr("value",'');

        $("#kd_u3").combogrid("setValue",'');
        $("#kd_u2").combogrid("setValue",'');
        $("#kd_u1").combogrid("setValue",'');
        $("#kode").combogrid("setValue",'');
        $("#nama").attr("value",'');
        $('#kode').combogrid("enable"); 
    }
    
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_skpd',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_skpd(){
        var ckdskpd = $('#kode').combogrid('getValue');
        var cnmskpd = document.getElementById('nama').value;
        var ckd_u1  = $('#kd_u1').combogrid('getValue');
        var ckd_u2  = $('#kd_u2').combogrid('getValue');
        var ckd_u3  = $('#kd_u3').combogrid('getValue');
        var ckd_u4  = document.getElementById('kd_u4').value;
        var ckd_u5  = document.getElementById('kd_u5').value;
        var cnm_4   = document.getElementById('nm_4').value;
                
        if (ckdskpd==''){
            alert('Silahkan Pilih Kode SKPD yang akan dilakukan proses mapping');
            exit();
        }
        if (cnm_4==''){
            alert('Silahkan isi Nama SKPD Sesuai Permendagri 90');
            exit();
        }


        if (ckd_u1!=''){
          var ckd1    =ckd_u1.replace('.','-');
        }else{
          var ckd1 ='0-00';
        }

        if (ckd_u2!=''){
          var ckd2    =ckd_u2.replace('.','-');
        }else{
          var ckd2 ='0-00'; 
        }

        if (ckd_u3!=''){
          var ckd3    =ckd_u3.replace('.','-');
        }else{
          var ckd3 ='0-00';
        }

        if (ckd_u4!=''){
          var ckd4    =ckd_u4;
        }else{
          var ckd4 ='00';
        }

        if (ckd_u5!=''){
          var ckd5    =ckd_u5;
        }else{
          var ckd5 ='00';
        }

        var gabung=ckd1+'.'+ckd2+'.'+ckd3+'.'+ckd4+'.'+ckd5;
        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_skpd,nm_skpd,kd_u1,kd_u2,kd_u3,kd_u4,kd_u5,kode90,nama90)";
            lcvalues = "('"+ckdskpd+"','"+cnmskpd+"','"+ckd1+"','"+ckd2+"','"+ckd3+"','"+ckd4+"','"+ckd5+"','"+gabung+"','"+cnm_4+"')";

            lcinsert2 = "(kd_skpd,kd_urusan,nm_skpd)";
            lcvalues2 = "('"+gabung+"','"+ckd1.replace('-','.')+"','"+cnm_4+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/mapping/simpan_skpddouble',
                    data: ({tabel:'mapping_skpd',kolom:lcinsert,nilai:lcvalues,cid:'kd_skpd',lcid:ckdskpd,tabel2:'ms_skpd',kolom2:lcinsert2,nilai2:lcvalues2,cid2:'kd_skpd',lcid2:gabung}),
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
                            keluar();
                        }
                    }
                });
            });   
           
        } else{
            
            lcquery = "UPDATE mapping_skpd SET kd_u1='"+ckd1+"',kd_u2='"+ckd2+"',kd_u3='"+ckd3+"',kd_u4='"+ckd4+"',kd_u5='"+ckd5+"',kode90='"+gabung+"',nama90='"+cnm_4+"' where kd_skpd='"+ckdskpd+"'";

            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/mapping/update_skpd',
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
        
        
      
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 

    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        $('#kode').combogrid("disable"); 
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        $('#kode').combogrid("enable"); 
        $("#kode").combogrid("setValue",'');
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload'); 
     }    
    
     function hapus(){
        var ckode = $('#kode').combogrid('getValue');
        
        var urll = '<?php echo base_url(); ?>index.php/mapping/hapus_skpd';
        $(document).ready(function(){
         $.post(urll,({tabel:'mapping_skpd',cnid:ckode,cid:'kd_skpd'}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
                keluar();
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
<h3 align="center"><u><b><a>INPUTAN MASTER BIDANG URUSAN</a></b></u></h3>
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
        <table id="dg" title="LISTING SKPD" style="width:900px;height:440px;" >  
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
                <td width="30%">KODE SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:100px;"/>&nbsp;&nbsp;<input type="text" id="nama" style="width:360px;" disabled/></td>  
            </tr>
                       
            <tr>
                <td width="30%">KODE URUSAN 1</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_u1" style="width:100px;"/>&nbsp;&nbsp;<input type="text" id="nm_u1" style="width:360px;" disabled/></td>  
            </tr>
            
            <tr>
                <td width="30%">KODE URUSAN 2</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_u2" style="width:100px;"/>&nbsp;&nbsp;<input type="text" id="nm_u2" style="width:360px;" disabled/></td>  
            </tr>
            <tr>
                <td width="30%">KODE URUSAN 3</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_u3" style="width:100px;"/>&nbsp;&nbsp;<input type="text" id="nm_u3" style="width:360px;" disabled/></td>  
            </tr>
            <tr>
                <td width="30%">KODE SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_u4" placeholder="00" style="width:100px;" maxlength="2" />&nbsp;&nbsp;</td>  
            </tr>
            <tr>
                <td width="30%">KODE UNIT/UPTD</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_u5" placeholder="00" style="width:100px;"maxlength="2"/></td>  
            </tr>

            <tr>
                <td width="30%">NAMA SKPD SESUAI PERMENDAGRI 90</td>
                <td width="1%">:</td>
                <td><input type="text" id="nm_4" style="width:400px;"/></td>  
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