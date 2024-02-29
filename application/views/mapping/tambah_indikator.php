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
            height: 700,
            width: 1000,
            modal: true,
            autoOpen:false
        });

            $("#kode_prog").hide();
        }); 

        function get_sub_kegiatanall(){
    var ckdkegiatan = $('#kd_kegiatan').combogrid('getValue');
    var ckdskpd = $('#kd_skpd').combogrid('getValue');
    $('#kd_sub_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_sub_kegiatan',  
       textField:'kd_sub_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_sub_kegiatan90all',
       queryParams:({kode:ckdkegiatan,skpd:ckdskpd}), 
       columns:[[  
           {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:100},  
           {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
          $("#nm_sub_kegiatan").attr("value",rowData.nm_sub_kegiatan.toUpperCase());                
          $("#kd_program").combogrid("setValue",rowData.kd_sub_kegiatan.substring(0,7));
          $("#kd_program").hide();
       }  
     }); 

    $('#kd_skpd').combogrid("disable"); 
    $('#kd_kegiatan').combogrid("disable"); 
    $('#kd_sub_kegiatan').combogrid("disable"); 
}   
     
     $(function(){


      $('#kd_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_kegiatan',  
       textField:'kd_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_kegiatan90', 
       columns:[[  
           {field:'kd_kegiatan',title:'Kode Kegiatan',width:100},  
           {field:'nm_kegiatan',title:'Nama Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_kegiatan").attr("value",rowData.nm_kegiatan.toUpperCase());
           get_sub_kegiatan();              
       }  
     }); 


    $('#kd_sub_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_sub_kegiatan',  
       textField:'kd_sub_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_sub_kegiatan90all', 
       columns:[[  
           {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:100},  
           {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
          $("#nm_sub_kegiatan").attr("value",rowData.nm_sub_kegiatan.toUpperCase());                
          $("#kd_program").combogrid("setValue",rowData.kd_sub_kegiatan.substring(0,7));
          $("#kd_program").hide();
       }  
     });   
        
     $('#kd_skpd').combogrid({  
       panelWidth:550,  
       idField:'kd_skpd',  
       textField:'kd_skpd',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/skpd_mapping',  
       columns:[[  
           {field:'kd_skpd',title:'Kode skpd',width:150},  
           {field:'nm_skpd',title:'Nama skpd',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_skpd").attr("value",rowData.nm_skpd.toUpperCase()); 
           get_kegiatan();
       }  
     }); 
function get_kegiatan(){
      var ckdskpd = $('#kd_skpd').combogrid('getValue');
      $('#kd_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_kegiatan',  
       textField:'kd_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_kegiatan90', 
       queryParams:({kode:ckdskpd}), 
       columns:[[  
           {field:'kd_kegiatan',title:'Kode Kegiatan',width:100},  
           {field:'nm_kegiatan',title:'Nama Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_kegiatan").attr("value",rowData.nm_kegiatan.toUpperCase());
           if(lcstatus=='tambah'){ 
            get_sub_kegiatan();
           }else{
            get_sub_kegiatanall()
           }
           
       }  
     }); 
}


function get_sub_kegiatan(){
    var ckdkegiatan = $('#kd_kegiatan').combogrid('getValue');
    var ckdskpd = $('#kd_skpd').combogrid('getValue');
    $('#kd_sub_kegiatan').combogrid({  
       panelWidth:500,  
       idField:'kd_sub_kegiatan',  
       textField:'kd_sub_kegiatan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_sub_kegiatan90',
       queryParams:({kode:ckdkegiatan,skpd:ckdskpd}), 
       columns:[[  
           {field:'kd_sub_kegiatan',title:'Kode Sub Kegiatan',width:100},  
           {field:'nm_sub_kegiatan',title:'Nama Sub Kegiatan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
          $("#nm_sub_kegiatan").attr("value",rowData.nm_sub_kegiatan.toUpperCase());                
          $("#kd_program").combogrid("setValue",rowData.kd_sub_kegiatan.substring(0,7));
          $("#kd_program").hide();
       }  
     }); 
}





      $('#kd_program').combogrid({  
       panelWidth:500,  
       idField:'kd_program',  
       textField:'kd_program',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/mapping/get_program90',
       columns:[[  
           {field:'kd_program',title:'Kode program',width:100},  
           {field:'nm_program',title:'Nama program',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
           $("#nm_program").attr("value",rowData.nm_program.toUpperCase());
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
        url: '<?php echo base_url(); ?>/index.php/mapping/load_trskpd',
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
            {field:'kd_sub_kegiatan',
            title:'Kode Sub Kegiatan',
            width:15,
            align:"center"},
            {field:'nm_sub_kegiatan',
            title:'Nama Sub Kegiatan',
            width:50},
            {field:'pagu',
            title:'Pagu',
            width:50}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_skpd           =   rowData.kd_skpd;           
          nm_skpd           =   rowData.nm_skpd;           
          kd_kegiatan       =   rowData.kd_kegiatan;       
          nm_kegiatan       =   rowData.nm_kegiatan;       
          kd_sub_kegiatan   =   rowData.kd_sub_kegiatan;   
          nm_sub_kegiatan   =   rowData.nm_sub_kegiatan;   
          lokasi            =   rowData.lokasi;            
          waktu_giat        =   rowData.waktu_giat;        
          sasaran_giat      =   rowData.sasaran_giat;      
          tu_capai          =   rowData.tu_capai;          
          tu_mas            =   rowData.tu_mas;            
          tu_kel            =   rowData.tu_kel;            
          tu_has            =   rowData.tu_has;            
          tk_capai          =   rowData.tk_capai;          
          tk_mas            =   rowData.tk_mas;            
          tk_kel            =   rowData.tk_kel;            
          tk_has            =   rowData.tk_has;            
          ang_lalu          =   rowData.ang_lalu1;          
          pagu              =   rowData.pagu;
          get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan,kd_sub_kegiatan,nm_sub_kegiatan,lokasi,waktu_giat,sasaran_giat,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,ang_lalu,pagu); 
          lcidx = rowIndex;  
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data'; 
           edit_data();
        }
        
        });
       
    });        

 
    
    function get(kd_skpd,nm_skpd,kd_kegiatan,nm_kegiatan,kd_sub_kegiatan,nm_sub_kegiatan,lokasi,waktu_giat,sasaran_giat,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,ang_lalu,pagu){
        
           $("#kd_skpd").combogrid("setValue",kd_skpd);
           $("#kd_kegiatan").combogrid("setValue",kd_kegiatan);
           $("#kd_sub_kegiatan").combogrid("setValue",kd_sub_kegiatan);
           $("#kd_program").combogrid("setValue",kd_program);
           

           $("#kd_sub_kegiatan").attr("value",kd_sub_kegiatan);

           $("#waktu_giat").attr("value",waktu_giat);
           $("#sasaran_giat").attr("value",sasaran_giat);
           $("#tu_capai").attr("value",tu_capai);
           $("#tu_mas").attr("value",tu_mas);
           $("#tu_kel").attr("value",tu_kel);
           $("#tu_has").attr("value",tu_has);

           $("#tk_capai").attr("value",tk_capai);
           $("#tk_mas").attr("value",tk_mas);
           $("#tk_kel").attr("value",tk_kel);
           $("#tk_has").attr("value",tk_has);
           
           $("#lokasi").attr("value",lokasi);

           $("#ang_lalu").attr("value",ang_lalu);
           $("#pagu").attr("value",pagu);
           get_sub_kegiatanall();
                       
    }
       
    function kosong(){
           $("#kd_skpd").combogrid("setValue",'');
           $("#kd_kegiatan").combogrid("setValue",'');
           $("#kd_sub_kegiatan").combogrid("setValue",'');
           $("#kd_program").combogrid("setValue",'');
           $("#nm_sub_kegiatan").attr("value",'');
           // $("#kd_sub_kegiatan").attr("value",'');
           
           $("#nm_skpd").attr("value",'');
           $("#nm_kegiatan").attr("value",'');
           $("#nm_sub_kegiatan").attr("value",'');
           $("#nm_program").attr("value",'');

           $("#waktu_giat").attr("value",'');
           $("#sasaran_giat").attr("value",'');
           $("#tu_capai").attr("value",'');
           $("#tu_mas").attr("value",'');
           $("#tu_kel").attr("value",'');
           $("#tu_has").attr("value",'');

           $("#tk_capai").attr("value",'');
           $("#tk_mas").attr("value",'');
           $("#tk_kel").attr("value",'');
           $("#tk_has").attr("value",'');
           
           $("#lokasi").attr("value",'');

           $("#ang_lalu").attr("value",'');
           $("#pagu").attr("value",'');
                       
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
    
       function simpan(){
        var ckdskpd           = $('#kd_skpd').combogrid('getValue');
        var cnmskpd           = document.getElementById('nm_skpd').value;
        var ckd_kegiatan      = $('#kd_kegiatan').combogrid('getValue');
        var ckd_sub_kegiatan  = $('#kd_sub_kegiatan').combogrid('getValue');
        var ckdsub           = document.getElementById('kd_sub_kegiatan').value;
        var ckd_program       = $('#kd_program').combogrid('getValue');
        var cnm_program       = document.getElementById('nm_program').value;
        var cnm_kegiatan      = document.getElementById('nm_kegiatan').value;
        var cnm_sub_kegiatan  = document.getElementById('nm_sub_kegiatan').value;
        
        var clokasi           = document.getElementById('lokasi').value;
        var csasaran_giat     = document.getElementById('sasaran_giat').value;
        var cwaktu_giat       = document.getElementById('waktu_giat').value;
        
        var ctu_capai         = document.getElementById('tu_capai').value;
        var ctu_mas           = document.getElementById('tu_mas').value;
        var ctu_kel           = document.getElementById('tu_kel').value;
        var ctu_has           = document.getElementById('tu_has').value;
        
        var ctk_capai         = document.getElementById('tk_capai').value;
        var ctk_mas           = document.getElementById('tk_mas').value;
        var ctk_kel           = document.getElementById('tk_kel').value;
        var ctk_has           = document.getElementById('tk_has').value;

        var cang_lalu         = angka(document.getElementById('ang_lalu').value);
        var cpagu             = angka(document.getElementById('pagu').value);
        
                
        if (ckdskpd==''){
            alert('Silahkan Pilih Kode SKPD');
            exit();
        }
        if (ckd_kegiatan==''){
            alert('Silahkan Pilih Kode Kegiatan Sesuai Permendagri 90');
            exit();
        }
        if (ckd_sub_kegiatan==''){
            alert('Silahkan Pilih Kode Kegiatan Sesuai Permendagri 90');
            exit();
        }

        var gabung=ckdskpd+'.'+ckd_sub_kegiatan;
        
        if(lcstatus=='tambah'){ 
            
            lcinsert = "(kd_gabungan,kd_kegiatan,kd_program,kd_urusan,kd_skpd,nm_skpd,lokasi,sasaran_giat,waktu_giat,tu_capai,tu_mas,tu_kel,tu_has,tk_capai,tk_mas,tk_kel,tk_has,ang_lalu,nilai_kua,kd_sub_kegiatan,nm_sub_kegiatan)";
            lcvalues = "('"+gabung+"','"+ckd_kegiatan+"','"+ckd_program+"','"+ckd_program.substring(0,4)+"','"+ckdskpd+"','"+cnmskpd+"','"+clokasi+"','"+csasaran_giat+"','"+cwaktu_giat+"','"+ctu_capai+"','"+ctu_mas+"','"+ctu_kel+"','"+ctu_has+"','"+ctk_capai+"','"+ctk_mas+"','"+ctk_kel+"','"+ctk_has+"','"+cang_lalu+"','"+cpagu+"','"+ckd_sub_kegiatan+"','"+cnm_sub_kegiatan+"')";
            
            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/mapping/simpan_skpd',
                    data: ({tabel:'trskpd',kolom:lcinsert,nilai:lcvalues,cid:'kd_gabungan',lcid:gabung}),
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
            
            lcquery = "UPDATE trskpd SET lokasi='"+clokasi+"',sasaran_giat='"+csasaran_giat+"',waktu_giat='"+cwaktu_giat+"',tu_capai='"+ctu_capai+"',tu_mas='"+ctu_mas+"',tu_kel='"+ctu_kel+"',tu_has='"+ctu_has+"',tk_capai='"+ctk_capai+"',tk_mas='"+ctk_mas+"',tk_kel='"+ctk_kel+"',tk_has='"+ctk_has+"' where kd_skpd='"+ckdskpd+"' and kd_sub_kegiatan='"+ckd_sub_kegiatan+"'";

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
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#kd_sub_kegiatan").attr("Value",'');
        $("#kd_sub_kegiatan").combogrid("setValue",'');
        $("#dialog-modal").dialog('open');
        $('#kd_skpd').combogrid("enable"); 
        $('#kd_kegiatan').combogrid("enable"); 
        $('#kd_sub_kegiatan').combogrid("enable"); 
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
<h3 align="center"><u><b><a>INPUTAN INDIKATOR KEGIATAN</a></b></u></h3>
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
        <table id="dg" title="LISTING KEGIATAN" style="width:900px;height:440px;" >  
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
                <td colspan="2" width="35%"><input type="text" id="kd_skpd" style="width:200px;"/>&nbsp;&nbsp;<input type="text" id="nm_skpd" style="width:360px;" disabled/></td>

            </tr>
                       
            <tr>
                <td width="30%">KODE KEGIATAN </td>
                <td colspan="2" width="35%"><input type="text" id="kd_kegiatan" style="width:200px;"/>&nbsp;&nbsp;<input type="text" id="nm_kegiatan" style="width:360px;" disabled/></td>
            </tr>
            
            <tr>
                <td width="30%">KODE SUB KEGIATAN</td>
                <td colspan="2" width="35%"><input type="text" id="kd_sub_kegiatan" style="width:200px;"/>&nbsp;&nbsp;<input type="text" id="nm_sub_kegiatan" style="width:360px;" disabled/></td>
            </tr>
            <tr>
                <td width="30%"></td>
                <td colspan="2" width="35%"><div id="kode_prog"><input type="hidden" id="kd_program" style="width:200px;" hidden /></div>&nbsp;&nbsp;<input type="hidden" id="nm_program" style="width:360px;" disabled/></td>
                
            </tr>
            <tr>
              <td colspan="3">
                <hr>
              </td>
            </tr>
            <tr>
                <td width="30%">LOKASI</td>
                <td width="35%"><input type="text" id="lokasi" style="width:400px;"/></td>
                <td width="35%"></td>  
            </tr>
            <tr>
                <td width="30%">WAKTU</td>
                <td width="35%"><input type="text" id="waktu_giat" style="width:400px;" /></td>
                <td width="35%">&nbsp;&nbsp;</td>  
            </tr>
            <tr>
                <td width="30%">SASARAN</td>
                <td width="1%"><input type="text" id="sasaran_giat" style="width:400px;"/></td>
                <td>&nbsp;</td>  
            </tr>
            <tr>
              <td colspan="3">&nbsp;

              </td>
            <tr>
              <td colspan="3"><hr>
              </td>
            </tr>
            <tr>
                <td width="30%"><b>INDIKATOR</b></td>
                <td width="35%" align="center"><b>TOLAK UKUR</b></td>
                <td width="35%" align="center"><b>CAPAIAN KINERJA</b></td>  
            </tr>
            <tr>
                <td width="30%">CAPAIAN</td>
                <td width="35%"><input type="text" id="tu_capai" style="width:400px;"/></td>
                <td width="35%"><input type="text" id="tk_capai" style="width:400px;"/></td>  
            </tr>
            <tr>
                <td width="30%">MASUKAN</td>
                <td width="35%"><input type="text" id="tu_mas" style="width:400px;"/></td>
                <td width="35%"><input type="text" id="tk_mas" style="width:400px;"/></td>  
            </tr>
            <tr>
                <td width="30%">KELUARAN</td>
                <td width="35%"><input type="text" id="tu_kel" style="width:400px;"/></td>
                <td width="35%"><input type="text" id="tk_kel" style="width:400px;"/></td>  
            </tr>
            <tr>
                <td width="30%">HASIL</td>
                <td width="1%"><input type="text" id="tu_has" style="width:400px;"/></td>
                <td width="35%"><input type="text" id="tk_has" style="width:400px;"/></td>  
            </tr>
            <tr>
              <td colspan="3"><hr>
              </td>
            </tr>
            <tr>
                <td width="30%">Anggaran Lalu</td>
                <td width="1%"><input  id="ang_lalu" name="ang_lalu" style="width:250px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td width="35%">&nbsp;</td>  
            </tr>
            <tr>
                <td width="30%">Pagu</td>
                <td width="1%"><input  id="pagu" name="pagu" style="width:250px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
                <td width="35%">&nbsp;</td>  
            </tr>
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr> 

            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>