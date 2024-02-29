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
       url:'<?php echo base_url(); ?>index.php/mapping/get_skpd',  
       columns:[[  
           {field:'kd_skpd',title:'Kode skpd',width:100},  
           {field:'nm_skpd',title:'Nama skpd',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nama").attr("value",rowData.nm_skpd.toUpperCase());
           $("#validasi").attr("value",rowData.validasi_kegiatan);                
       }  
     });   
        
        
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/mapping/load_validasi',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",
        rowStyler: function(index,row){
                    if (row.validasi_kegiatan==1){
                      return 'background-color:#3be8ff;';
                    }else{
                      return 'background-color:#ffffff;';
                    }
                  },                         
        columns:[[
            {field:'kd_skpd',
            title:'Kode SKPD',
            width:15,
            align:"center"},
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:50},
            {field:'status',
            title:'STATUS VALIDASI',
            width:15,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          kdskpd  = rowData.kd_skpd;
          nama    = rowData.nm_skpd;
          validasi= rowData.validasi_kegiatan;
          get(kdskpd,nama,validasi); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });
       
    });        

 
    
    function get(kdskpd,nama,validasi) {
        $("#kode").combogrid("setValue",kdskpd);
        $("#nama").attr("value",nama);
        $("#validasi").attr("value",validasi);     
                       
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
        url: '<?php echo base_url(); ?>/index.php/mapping/load_validasi',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_skpd(){
        var ckdskpd   = $('#kode').combogrid('getValue');
        var cnmskpd   = document.getElementById('nama').value;
        var cvalidasi = document.getElementById('validasi').value;

        if (cvalidasi==""){
          lcquery = "UPDATE ms_skpd SET validasi_kegiatan=null where kd_skpd='"+ckdskpd+"'";
        }else{
          lcquery = "UPDATE ms_skpd SET validasi_kegiatan='"+cvalidasi+"' where kd_skpd='"+ckdskpd+"'";
        }

            
            

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
<h3 align="center"><u><b><a>INPUTAN VALIDASI KEGIATAN</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        <tr>
        <td width="10%"></td>               
        
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
                <td width="30%">Validasi</td>
                <td width="1%">:</td>
                <td><select  name="validasi" id="validasi" >
                   <option value="">BELUM VALIDASI</option>
                   <option value="1">SUDAH DI VALIDASI</option>
                 </select></td>  
            </tr>
            
            
            
            
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_skpd();">Simpan</a>
                <!-- <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a> -->
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>